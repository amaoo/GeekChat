# GeekChat
# 基于ChatGPT的微信小程序

这是一个基于OpenAI的ChatGPT API的微信小程序项目，前端采用uni-app框架，后端使用PHP Webman。通过这个小程序，用户可以与ChatGPT模型进行对话。

## 功能特点

1. 代码采用Stream流模式通信，一边生成一边输出，响应速度超过官网，H5可采用JS的EventSource，小程序可采用JS的websocket
2. 支持Markdown格式，代码高亮，代码复制

## 安装教程

### 前端

1. 克隆此项目到本地：
2. 使用HBuilderX或者微信开发者工具打开项目文件夹 siteinfo.js，配置接口地址，socket地址，appid等。
3. 在HBuilderX或微信开发者工具中运行项目。

### 后端

1. 克隆此项目到本地，安装依赖，根据需要配置.env文件（ChatGPT密钥，mysql配置，redis配置，微信登录配置）。


## 演示二维码

请使用微信扫描下方二维码体验小程序：

<img src="https://chatgpt.pcmxj.com/uploads/github/qrcode.jpg" alt="演示二维码" width="400"/>

<img src="https://chatgpt.pcmxj.com/uploads/github/demo1.jpg" alt="演示截图" width="400"/>

<img src="https://chatgpt.pcmxj.com/uploads/github/demo2.jpg" alt="演示截图" width="400"/>

<img src="https://chatgpt.pcmxj.com/uploads/github/demo3.jpg" alt="演示截图" width="400"/>

## 即将推出的功能

1. AI对话增加语音输入输出。
2. AI绘画：根据您的描述，使用AI技术自动生成独特的艺术作品。
3. AI配音：使用AI技术将您的文本转换成逼真的人声，为您提供更生动的聆听体验。
4. 管理后台。

## 打赏支持

如果您觉得这个项目对您有帮助，可以扫描下方二维码进行打赏支持，非常感谢您的支持！

<img src="https://chatgpt.pcmxj.com/uploads/github/donate_qrcode.jpg" alt="打赏二维码" width="400"/>

## 贡献与支持

如果您在使用过程中遇到问题，欢迎提交Issue。我们非常感谢您的反馈和贡献！