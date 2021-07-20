---
title: 基于NPS的内网穿透搭建
date: 2020-6-9 9:42:27
tags:
 - NPS
 - 教程
 - vps
 - 内网穿透
description: 教程
---
# NPS内网穿透

内网穿透我们就不介绍了，我们介绍一下这个内网穿透的项目！



## 介绍

这是一款轻量级、高性能、功能强大的内网穿透代理服务器。支持tcp、udp、socks5、http等几乎所有流量转发，可用来访问内网网站、本地支付接口调试、ssh访问、远程桌面，内网dns解析、内网socks5代理等等……，并带有功能强大的web管理端。



##  特点

- 协议支持全面，兼容几乎所有常用协议，例如tcp、udp、http(s)、socks5、p2p、http代理...
- 全平台兼容(linux、windows、macos、群辉等)，支持一键安装为系统服务
- 控制全面，同时支持服务端和客户端控制
- https集成，支持将后端代理和web服务转成https，同时支持多证书
- 操作简单，只需简单的配置即可在web ui上完成其余操作
- 展示信息全面，流量、系统信息、即时带宽、客户端版本等
- 扩展功能强大，该有的都有了（缓存、压缩、加密、流量限制、带宽限制、端口复用等等）
- 域名解析具备自定义header、404页面配置、host修改、站点保护、URL路由、泛解析等功能
- 服务端支持多用户和用户注册功能



## 服务器端

### Doker版拉取并安装nps镜像

- 执行以下命令安装nps服务器端
- 将**<本机conf目录>**修改为你自己需要保存conf文件的目录
- 修改并输入以下命令启动nps服务器

手动修改端口号

```
docker run -d --privileged=true --name nps -p 8080:8080 -p 8024:8024 -p 10150-10179:10150-10179 -v <本机conf目录>:/nps/conf oldiy/nps-server:latest

#示例
docker run -d --privileged=true --name nps -p 8080:8080 -p 8024:8024 -p 10150-10179:10150-10179 -v /home/nps:/nps/conf oldiy/nps-server:latest

docker run -d --privileged=true --name nps -p 8080:8080 -p 8024:8024 -p 10150-10179:10150-10179 -v /root/nps:/nps/conf oldiy/nps-server:latest
修改密码后
docker rm 容器号
```

自动，但可能会发生冲突

```
docker run -d --privileged=true --name nps --net=host -v <本机conf目录>:/nps/conf oldiy/nps-server:latest
```

默认账户/密码：admin/123

修改密码，直接修改映射出的主配置文件就好了。

记得去放行端口



### 自行搭建

下载服务端nps，并安装

```
wget https://github.com/ehang-io/nps/releases/download/v0.26.10/linux_amd64_server.tar.gz

tar -zxvf linux_amd64_server.tar.gz

./nps start
```

### 服务端启动

下载完服务器压缩包后，解压，然后进入解压后的文件夹

- 执行安装命令

对于linux|darwin `sudo ./nps install`

对于windows，管理员身份运行cmd，进入安装目录 `nps.exe install`

- 默认端口

nps默认配置文件使用了80，443，8080，8024端口

80与443端口为域名解析模式默认端口

8080为web管理访问端口

8024为网桥端口，用于客户端与服务器通信

- 启动

对于linux|darwin `sudo nps start`

对于windows，管理员身份运行cmd，进入程序目录 `nps.exe start`

```
安装后windows配置文件位于 C:\Program Files\nps，linux和darwin位于/etc/nps
```



## 客户端

我们去Github下载自己需要的客户端，[NPS项目地址](https://github.com/ehang-io/nps)

在服务端web页面新增

![image-20210708122839770](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210708122839770.png)

设置对应的参数

![image-20210708122948471](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210708122948471.png)

添加完成后，可以在列表中看到客户端信息，且客户端处于offline状态（因为我们还未在内网设备上输入指令连接服务端）。点击左侧按钮查看详细信息，我们可以看到系统生成（或手动指定）的通信密钥，以及客户端连接服务端的命令。

![image-20210708123056832](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210708123056832.png)

直接复制命令就可以在客户端使用了



## Web端配置

此时应该就能访问你的NPS网页了，访问地址是 [你服务IP:端口] 例：123.123.123.123:8080

可以B站看视频进行相关配置，[B站视频地址](https://www.bilibili.com/video/BV19J411R7xa?from=search&seid=12146123682062738925)

[自己搭建nps内网穿透反向代理服务器使用教程](https://idc.wanyunshuju.com/aqst/1821.html)

https://ld246.com/article/1596364309400

