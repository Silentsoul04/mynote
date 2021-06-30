---
title: Hexo+Git+VPS 轻松打造属于自己的博客
date: 2020-12-4 17:00:08
tags:
 - hexo
 - 教程
 - git
 - vps
description: 教程
---

# Hexo+VPS+Git的一系列搭建教程

## 前言

1. 感谢你可以看到这篇文章，欢迎与我继续交流
2. 转载请注明出处！



## 目的

hexo部署在本地，将生成的网页文件通过Git上传到自己的VPS中，实现自动化更新。



## 整个搭建流程

第一部分：本地Hexo初始化，包括安装NodeJS、Git。

第二部分：服务器的搭建，包括安装Git，Nginx配置、创建git用户。

第三部分：使用Git自动化部署发布自己的博客。



## 本地搭建

### 环境

本次我们的本地环境为：windows 10系统，所有以下在windows10 64位下进行演示。

首先你要去Node官网下载[Node.js](http://nodejs.org/)！然后还要下载[git](https://git-scm.com/download/win)！

废话少说，开整！！！



### 安装Node.js

进入官网[Node.js](http://nodejs.org/)，选择我们需要的安装包下载，打开安装包一直next，下一步就可以了，默认安装就行。



### 安装 Git

打开我们的官网[git](https://git-scm.com/download/win)，我们选择windows版本的下载

![git安装](https://antlers.oss-cn-hangzhou.aliyuncs.com/blog_images/git%E5%AE%89%E8%A3%85_1.png)

我们这边的也是一直next，下一步就可以了。直接安装官方默认的来就行！

安装完后，我们右键会有Git Bash，安装完成后的Git Bash，其作用与系统自带的CMD命令行相同，系统中的CMD命令同样可以在Git Bash中完成。我们后面的本地运行窗口都在这里完成。

git安装完成后，需要进行配置

输入如下，其中" "中的your name 和your email为你的Git Hub用户名(非昵称)与邮箱（这样也方便以后在Github的使用）

```
git config --global user.name "your name"
git config --global user.email "your email"
```

并可通过以下命令查询用户名与邮箱

```
git config user.name
git config user.email
```



### 安装Hexo

我们在本地找一个地方做为我们Hexo的数据数据存放，例如我的存放目录为**E:\blog**，我们在这个目录下右键打开**Git Bash Here**

先查看我们的Node 和 npm有没有安装好

```
node -v
npm -v
```

![hexo安装](https://antlers.oss-cn-hangzhou.aliyuncs.com/blog_images/hexo%E5%AE%89%E8%A3%85_1.png)

因为npm为国外源，下载速度感人。我们需要先来安装个cnpm使下载指向国内源以提高速度，以后下载什么东西都用cnpm了。

这里我们选择使用淘宝镜像下载 cnpm，在上面终端继续输入

```
npm install -g cnpm --registry=https://registry.npm.taobao.org
```

下载完后查看cnpm版本，查询成功则证明安装完成。

```
cnpm -v
```



**现在我们正式的开始下载Hexo**，使用cnpm下载hexo！

```
cnpm install -g hexo-cli
```

验证是否安装成功

```
hexo -v
```

现在我们安装组件

```
cnpm install hexo --save
```



这步是关键，我们需要进行Hexo的初始化（注意我们的路径）。我们可以使用`pwd`查看该目录是否为我们希望的地址。

**注意**：这里我们的目录要是一个空的，若blog文件夹非空，则会报错！

这里等待时间较长，约几分钟。完成后就可以在目录下看到数据文件了。

```
hexo init
```



### Hexo的一些常用命令

这里我们经常用到的有三个命令

```
hexo clean		#用来清理缓存文件
hexo g      #生成文件
hexo s     #运行本地服务器，端口默认4000
hexo d   #推送到服务器
```

这里我们可以运行`hexo s`在本地进行测试，打开浏览器输入`127.0.0.1:4000`看到：

![hexo安装](https://antlers.oss-cn-hangzhou.aliyuncs.com/blog_images/hexo%E5%AE%89%E8%A3%85_2.png)

**看到这个就说明我们的本地环境搭建完成！！！**





## VPS服务器搭建

### 环境

我这里使用的是CentOS 8.2，使用别的发行版具体命令会有一些区别！



### 初始化服务器

如果你是一个新的服务器我们先先进行初始化，更新操作可以跳过

```
yum -y update
```



### 安装Git

```
yum install git

git --version
```



### 添加git用户

这里我们新添加一个用户来运行git服务

```
adduser git	
```

> 虽说现在的仓库只有我们自己在使用，新建一个 `git` 用户显得不是很有必要，但是为了安全起见，还是建议使用单独的 `git` 用户来专门运行 `git` 服务



### git用户证书登录

我们这里需要创建证书登录，需要我们在本地生成RSA进行SSH加密通讯，可以防止每次 push 都输入密码

我们打开本地的**Git Bash Here**，your email填写刚刚我们本地填过的，按3次Enter就完成的创建

```
ssh-keygen -t rsa -C "your email"
```

我们的密钥对就可以在本地的`C:\Users\自己的用户名\.ssh`的目录下看到！

![git证书登录](https://antlers.oss-cn-hangzhou.aliyuncs.com/blog_images/git%E8%AF%81%E4%B9%A6%E7%99%BB%E5%BD%95.png)

这个id_rsa是我们的私钥，id_rsa_pub是我们的公钥，打开我们的这个公钥，复制出来我们待会要用到。

打开VPS服务器，我们需要添加公钥

```
su git		# 切换到git用户
mkdir .ssh
vim /home/git/.ssh/authorized_keys		# 在git用户家目录中添加我们的公钥，按i进行编辑，复制刚刚的公钥进入，按ESC 再 :wq 保存退出
```

对了，我们的.ssh文件夹要设置为700权限，authorized_keys要设置为 600权限

```
chmod 700 /home/git/.ssh
chmod 600 /home/git/.ssh/authorized_keys
```

我们可以SSH登录测试一下，如果我们登录成功就说明我们成功了。本地**Git Bash Here**输入：

```
ssh git@你服务器IP
```

如果之前登陆过一个之前连过的VPS，由于公钥不一样了，所以无法登录，提示信息是 KEY 验证失败。我们可以输入下面的命令来更新信息：

```
ssh-keygen -R 你要访问的IP地址
```

这里我们可以限制git用户的SSH，毕竟这里是一个安全隐患。

```
vim /etc/passwd
```

我们打开这个文件，在文件末尾可以找到类似这样的行：

```
git:x:1002:1002::/home/git:/bin/bash      改为：       git:x:1002:1002::/home/git:/usr/bin/git-shell
```

修改后，我们只允许用户进行`git push/pull` 操作。



### 初始化 Git 仓库

我们要在服务器找个地方存放我们的Git仓库和www的根目录

这里是我的目录结构，就是个参考，hexo_blog作为博客根目录，/www/repo/blog.git 作为hexo的git仓库

```
/www
|--hexo_blog
|--repo
	|--blog.git
```

命令如下，我们用root用户输入

```
mkdir -p /opt/www/hexo_blog
mkdir -p /opt/www/repo

cd /opt/www/repo
sudo git init --bare blog.git
```



### 配置 git hooks

关于 hooks 的详情内容可以[参考这里](https://git-scm.com/book/zh/v2/自定义-Git-Git-钩子)。

我们这里要使用的是 `post-receive` 的 hook，这个 hook 会在整个 git 操作过程完结以后被运行。

在 `blog.git/hooks` 目录下新建一个 `post-receive` 文件：

```
cd /opt/www/repo/blog.git/hooks
vim post-receive
```

在 `post-receive` 文件中写入如下内容：

```
git --work-tree=/opt/www/hexo_blog --git-dir=/opt/www/repo/blog.git checkout -f
```

注意，/opt`/www/hexo_blog` 要换成你自己的部署目录。上面那句 git 命令可以在我们每次 push 完之后，把部署目录更新到博客的最新生成状态。这样便可以完成达到自动部署的目的了。

不要忘记设置这个文件的可执行权限：

```
chmod +x post-receive
```

最后我们把`blog.git`和`hexo_blog`的拥有者给git用户

```
sudo chown -R git:git /opt/www/repo/blog.git
sudo chown -R git:git /opt/www/hexo_blog
```



### 安装Nginx

安装Nginx

```
yum -y install gcc make zlib-devel pcre pcre-devel openssl-devel
sudo yum install -y nginx
```



完成安装后，我们先对Nginx进行配置，再启动Nginx服务

1. 打开Nginx配置文件

   ```
   vim /etc/nginx/nginx.conf
   ```

2. 修改Nginx服务80端口的root目录

   ```
   server {
           listen       80 default_server;
           listen       [::]:80 default_server;
           server_name  antlers.xyz;	# 这里是你的域名
           root         /www/hexo_blog;	# 这里要改成网站的根目录
   
           # Load configuration files for the default server block.
           include /etc/nginx/default.d/*.conf;
   
           location / {
           }
   ```

3. 这里最上面还有一个用户的设置

   ```
   user root;		# 这里我就改为的root用户
   worker_processes auto;
   error_log /var/log/nginx/error.log;
   pid /run/nginx.pid;
   ```



启动Nginx服务，如果是使用yum安装的话就是用以下的指令进行启动

```
systemctl start nginx
```

我们随便写一个index.html文件放到80端口的根目录（/www/hexo_blog）下进行测试，IP访问一下！

```html
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Test</title>
</head>
<body>

<h1>Test！</h1>

</body>
</html>
```

成功后我们VPS服务器端的配置就全部结束了！



## 本地Hexo配置文件配置

首先回顾一下，我们是否完成了所有的配置，本地git用户是否可以SSH连接到服务器。

打开我们Hexo本地的配置目录`_config.yml`，找到最后的一段进行修改。保留唯一的deploy配置。

```
deploy:
  type: git
  repo: git@你的服务器IP:/www/repo/blog.git		# 格式：服务器用户名@IP:Git仓库目录
  branch: master
```

保存后打开**Git Bash Here**，推送文章到服务器端。

```
hexo d
```



## 本地Hexo的目录结构

```
hexo目录结构：

├── node_modules：是依赖包
├── public  #存放被解析markdown、html文件
├── scaffolds #当您新建文章时，根据 scaffold生成文件
├── source  #资源文件夹
|   └── _posts #博客文章目录
└── themes #主题
├── _config.yml   #网站的配置信息。标题、网站名称等
├── db.json：#source解析所得到的
├── package.json  # 应用程序的配置信息
```

我们只要将文章丢到`source\_posts`里就好了，再`hexo clean`、`hexo g`、`hexo d`就好了！