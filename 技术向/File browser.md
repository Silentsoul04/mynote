---
title: 一款好用的文件管理器——File Browser
date: 2020-12-2 10:00:08
tags:
 - File Browser
 - 教程
 - docker
 - vps
description: 教程
---
# 基于Docker的File Browser搭建

## 简介

File Browser 是一个基于 Web 的文件管理器。它可以使你随时随地的对设备的文件进行基本的管理操作，如：创建、删除、移动、复制等。它除了可以让你进行文件管理之外，还有一些其他的功能。它支持多个用户的管理，而且每个用户可以拥有自己可以访问的文件和权限。它还支持文件分享，就行网盘那样，你可以通过它来向你的朋友分享文件。你还可以用它来执行一些 Linux 命令，比如你想要在当前目录下克隆一个代码库，就可以用它来执行`git`等命令。



## 搭建

这里我们使用docker进行搭建，只需要一行命令就可以完成搭建

```
docker run --restart=always --name filebrowser -d -v /opt/filebrowser:/srv -v /opt/filebrowserconfig.json:/etc/config.json -v /opt/filebrowser/database.db:/etc/database.db -p 2334:80 filebrowser/filebrowser
```

 **命令解释：**

- `--restart=always`：docker一启动就启动这个容器
- `--name filebrowser`：本地管理时的容器名
- `-v /opt/filebrowser:/srv`：目录或文件的对应关系，前面是本地目录，后面是对应容器的目录
- `-p 8080:80`：端口对应，前面是我们服务器的端口，对应容器的80端口

我们等待运行完成后就可以访问 IP+8080 ，默认用户名和密码都是admin，记得更改！！！