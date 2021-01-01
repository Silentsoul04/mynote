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

docker run -d --privileged=true --name nps -p 8080:8080 -p 8024:8024 -p 10150-10179:10150-10179 -v /www/nps:/nps/conf oldiy/nps-server:latest
修改密码后
docker rm 容器号
```

自动，但可能会发生冲突

```
docker run -d --privileged=true --name nps --net=host -v <本机conf目录>:/nps/conf oldiy/nps-server:latest
```



### 自行搭建

下载服务端nps，并安装

```
wget https://github.com/ehang-io/nps/releases/download/v0.26.8/linux_amd64_server.tar.gz

tar -zxvf linux_amd64_server.tar.gz

./nps start
```



## 客户端

我们去Github下载自己需要的客户端，[NPS项目地址](https://github.com/ehang-io/nps)



## Web端配置

此时应该就能访问你的NPS网页了，访问地址是 [你服务IP:端口] 例：123.123.123.123:8080

可以B站看视频进行相关配置，[B站视频地址](https://www.bilibili.com/video/BV19J411R7xa?from=search&seid=12146123682062738925)

[自己搭建nps内网穿透反向代理服务器使用教程](https://idc.wanyunshuju.com/aqst/1821.html)

https://ld246.com/article/1596364309400