# Node.js安装

## 从 NodeSource 中安装 Node.js 和 npm

NodeSource 是一个公司，聚焦于提供企业级的 Node 支持。它维护了一个 APT 软件源，其中包含了很多 Node.js 版本。如果你的应用需要指定版本的Node.js 版本，使用这个软件源。
在写作的时候，NodeSource 软件源提供了以下版本：

- v14.x - 最新稳定版
- v13.x
- v12.x - 最新长期版本
- v10.x - 前一个长期版本

我们将会安装 Node.js 版本 14.x:
1）以 sudo 用户身份运行下面的命令，下载并执行 NodeSource 安装脚本：

```
curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -
```

这个脚本将会添加 NodeSource 的签名 key 到你的系统，创建一个 apt 源文件，安装必备的软件包，并且刷新 apt 缓存。
如果你需要另外的 Node.js 版本，例如`12.x`，将`setup_14.x`修改为`setup_12.x`。
2）NodeSource 源启用成功后，安装 Node.js 和 npm:

```
sudo apt install nodejs
```

nodejs 软件包同时包含`node`和`npm`二进制包。
3）验证 Node.js 和 npm 是否正确安装。打印它们的版本号：

```
node --version
```

输出：

```
v14.2.0
npm --version
```

输出：

```
6.14.4
```

想要从 npm 编译本地扩展，你需要安装开发工具：

```
sudo apt install build-essential
```