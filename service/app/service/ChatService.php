<?php

namespace app\service;

use Orhanerday\OpenAi\OpenAi;
use support\Log;
use support\Redis;
use Workerman\Connection\TcpConnection;

class ChatService
{
    protected string $user_chat_context_prefix = 'user_chat_context:'; //用户上下文

    public function chat(TcpConnection $connection, string $question, int $user_id): bool
    {
        //内容审查
        if ($this->moderation($question)) {
            //抱歉,您的问题出现敏感内容,请重新提问！
            $connection->send(json_encode(['event' => 'chat', 'content' => '抱歉,您的问题出现敏感内容,多次出现可能导致封号']));
            Log::error("对话出现敏感内容", ['user_id' => $user_id, 'question' => $question]);
            return true;
        }
        $content = '';

        $result = $this->openai()->chat([
            'model' => env('OPENAI_MODEL'),
            'stream' => true,
            'messages' => $this->getMessageBody($user_id, $question),
        ], function ($curl_info, $data) use ($connection, &$content) {
            $arr = json_decode(substr($data, 5), true);
            if (isset($arr['choices'][0]['delta']['content'])) {
                $answer = $arr['choices'][0]['delta']['content'];
                $content .= $answer;
                $connection->send(json_encode(['event' => 'chat', 'content' => $answer]));
            }
            return strlen($data);
        });
        if ($result) {
            //保存上下文
            $this->setMessageBody($user_id, $content, 'assistant');
        }
        return (bool)$result;
    }

    /**
     * 内容审核
     * @param string $question
     * @return bool
     */
    public function moderation(string $question): bool
    {
        $response = $this->openai()->moderation([
            'input' => $question
        ]);
        if ($response) {
            $response = json_decode($response, true);
            if ($response['results'][0]['flagged']) {
                return true;
            }
        }
        return false;
    }

    public function openai(): OpenAi
    {
        return new OpenAi(getenv('OPENAI_KEY'));
    }

    /**
     * 获取消息体
     * @param int $user_id
     * @param string $title
     * @return array
     */
    public function getMessageBody(int $user_id, string $title): array
    {
        $context = Redis::get($this->user_chat_context_prefix . $user_id);
        if ($context) {
            return json_decode($context, true);
        }
        return [["role" => "system", "content" => $title]];
    }

    /**
     * 更新消息体
     * @param int $user_id
     * @param string $role
     * @param string $content
     * @return void
     */
    public function setMessageBody(int $user_id, string $content, string $role = 'user'): void
    {
        $ttl = 60;
        $context = Redis::get($this->user_chat_context_prefix . $user_id);
        if (!$context) {
            Redis::setEx($this->user_chat_context_prefix . $user_id, $ttl, json_encode([
                ["role" => 'system', "content" => $content]
            ]));
            return;
        }
        $context = json_decode($context, true);
        if (count($context) >= 4) {
            array_shift($context);
        }
        $context[] = ["role" => $role, "content" => $content];
        Redis::setEx($this->user_chat_context_prefix . $user_id, $ttl, json_encode($context));
    }
}