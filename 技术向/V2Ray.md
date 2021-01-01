---
title: V2Ray的搭建及使用说明
date: 2020-6-20 20:32:10
tags:
 - V2Ray
 - 教程
 - vps
description: 教程
---
# V2Ray的安装及使用说明

搭建v2ray的步骤大体如下：

- 首先你先得准备一个VPS，获得VPS的IP、root用户名及密码、SSH端口(一般为22)等；
- 借用Xshell工具登录到自己的VPS;
- 安装V2Ray，配置相关信息，完成安装；
- 在所需要的设备上添加对应的V2Ray信息就可以成功使用了；

没有VPS的可以使用跟我一样的[Vulter](https://www.vultr.com/?ref=8327534)家的VPS，这家位于国外的VPS厂商价格较为便宜，而且支付方便，直接用支付宝即可完成支付



## 一、安装V2Ray

下载V2Ray

```linux
bash <(curl -s -L https://git.io/v2ray.sh)
```

根据提示选择安装

安装完成后，希望最好更改一下SSH连接的默认端口号(22)，并更新Xshell属性中的端口号

```linux
wget -N --no-check-certificate https://raw.githubusercontent.com/ToyoDAdoubiBackup/doubi/master/ssh_port.sh && chmod +x ssh_port.sh && bash ssh_port.sh
```

更加详细的安装教学可以查看GitHub上的[V2Ray搭建详细图文教程]([https://github.com/233boy/v2ray/wiki/V2Ray%E6%90%AD%E5%BB%BA%E8%AF%A6%E7%BB%86%E5%9B%BE%E6%96%87%E6%95%99%E7%A8%8B](https://github.com/233boy/v2ray/wiki/V2Ray搭建详细图文教程))，相信你一定可以完成安装



## 二、查看V2Ray

查看配置信息

```linux
v2ray info
```

生成配置连接

```
v2ray url
```

最后保存uuid，v2ray的端口号



## 三、电脑端使用V2Ray科学上网

我这里使用的[Clash](https://github.com/Dreamacro/clash.git)，这个软件界面简洁，美观，下面是基本的使用方法

我们需要用文本工具进行编辑，拓展名为`.yml`

只需更改下面的这三个

```linux
server: 149.28.225.219 #你的VPS的IP地址
port: 63438 #注意这里是V2ray的端口号，不是Xshell的端口号
uuid: 56ef7dbe-8d03-492f-a97a-4a741fabea41 #uuid
```

> port: 7890
> socks-port: 7891
> allow-lan: false
> mode: Rule
> log-level: info
> external-controller: 127.0.0.1:9090
> experimental:
>   ignore-resolve-fail: true
> Proxy:
>
>   - name: vmess
>     type: vmess
>     server: 149.28.225.219
>     port: 63438
>     uuid: 56ef7dbe-8d03-492f-a97a-4a741fabea41
>     alterId: 233
>     cipher: Auto
> Proxy Group:
>   - name: Auto
>     proxies:
>       - vmess
>     type: url-test
>     url: http://www.gstatic.com/generate_204
>     interval: 300
>   - name: Fallback-Auto
>     type: fallback
>     proxies:
>       - vmess
>     url: http://www.gstatic.com/generate_204
>     interval: 300
>   - name: Load-Balance
>     type: load-balance
>     proxies:
>       - vmess
>     url: http://www.gstatic.com/generate_204
>     interval: 300
>   - name: Proxy
>     type: select
>     proxies:
>       - vmess
>       - Auto
> Rule:
>   - DOMAIN-SUFFIX,google.com,Auto
>   - DOMAIN-KEYWORD,google,Auto
>   - DOMAIN,google.com,Auto
>   - DOMAIN-SUFFIX,ad.com,REJECT
>   - IP-CIDR,127.0.0.0/8,DIRECT
>   - SRC-IP-CIDR,192.168.1.201/32,DIRECT
>   - GEOIP,CN,DIRECT
>   - DST-PORT,80,DIRECT
>   - SRC-PORT,7777,DIRECT
>   - MATCH,Auto

更改完之后，将配置文件扔进**Profiles**中，再在**Genera**l中开启**System Proxy**服务



## 四、手机端的科学上网

我这里用的是**v2rayNG**，需要的请在谷歌商店自行下载，只需要将**Vmess**复制出来再导入，具体使用方法就不介绍了



## 五、V2ray加速

我这里用的是**BBR Plus**进行加速

```
wget -N --no-check-certificate "https://raw.githubusercontent.com/chiakge/Linux-NetSpeed/master/tcp.sh"

chmod +x tcp.sh

./tcp.sh
```

在执行`./tcp.sh`后，进入加速选项，选择**BBR Plus**即可