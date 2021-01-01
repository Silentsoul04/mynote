---
title: KMS 服务
date: 2020-3-12 9:45:46
tags:
 - KMS
 - 教程
 - vps
description: 教程
---
# KMS 服务

用来激活 VOL 版本的 Windows 和 Office

> 系统支持：CentOS 6+，Debian 7+，Ubuntu 12+
> 虚拟技术：任意
> 内存要求：≥128M



## 服务器端配置**

```linux
wget https://github.com/Wind4/vlmcsd/releases/download/svn1112/binaries.tar.gz

tar zxvf binaries.tar.gz

cd binaries/Linux/intel/static

./vlmcsd-x64-musl-static

ps -ef | grep vlmcsd-x64-musl-static
```



## 客户端激活

> cd /d "%SystemRoot%\system32"
> slmgr /ipk W269N-WFGWX-YVC9B-4J6C9-T83GX
> slmgr /skms 149.28.52.34   #更改服务器IP地址
> slmgr /ato
> slmgr /xpr

[参考文章](https://teddysun.com/530.html)