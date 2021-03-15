---
title: H5ai的搭建
date: 2020-8-15 19:27:08
tags:
 - H5ai
 - 教程
 - LAMP
 - vps
description: 教程
---
# H5ai的搭建

## 一、搭建LNMP框架

搭建h5ai首先需要准备搭建好**LNMP**框架，这里我们用军哥的**LNMP**一键安装，使用一键安装可以很方便的选择不同的版本进行安装。

```linux
wget http://soft.vpser.net/lnmp/lnmp1.6.tar.gz -cO lnmp1.6.tar.gz && tar zxf lnmp1.6.tar.gz && cd lnmp1.6 && ./install.sh lnmp 
```

请注意最后面的**LNMP**参数，如需要**LNMPA**或**LAMP**式，请替换**LNMP**为你要安装的模式。     

具体安装就去看军哥的教程：https://lnmp.org/install.html

## 二、配置H5ai

1. 下载**h5ai**并解压

   ```linux
   cd /home/wwwroot/H5ai
   
   wget https://release.larsjung.de/h5ai/h5ai-0.29.2.zip
   
   unzip h5ai-0.29.2.zip
   ```

2. 修改默认首页的配置项

   **nginx 1.2** 在 `nginx.conf` 中的修改

   ```linux
   vim /usr/local/nginx/conf/vhost/Filename.conf
   ```

   找到index，进行修改

   ```linux
   index index.html index.php /_h5ai/public/index.php
   ```

3. 修改PHP配置-禁用函数，解除禁用函数scandir、exec和passthru、putenv

   ```linux
   vim /usr/local/php/etc/php.ini
   ```

   找到这一行：`disable_functions =`，删除被禁用的函数

## 三、测试&完善H5ai

1. 访问`http://你的域名或IP/_h5ai/public/index.php`进入后台检查，默认密码为空，直接点击login进入

   后台会检查安装情况，最好都yes！

2. **PDF thumbs**

   这里需要安装安装imagemagick扩展

   Ubuntu/Debian系统：

   ```linux
   apt-get install imagemagick -y
   ```

   CentOS系统:

   ```linux
   yum install ImageMagick -y
   ```

3. **EXIF**

   1. 进入php

      ```linux
      cd /usr/local/lnmp1.4-full/src/php-5.6.31/ext
      ```

   2. 进入exif

      ```linux
      cd exif && /usr/local/php/bin/phpize
      ```

   3. 配置

      ```linux
      ./configure --with-php-config=/usr/local/php/bin/php-config
      ```

   4. 安装

      ```linux
      sudo make && sudo make install
      ```

   5. 增加配置

      ```
      cd /usr/local/php/conf.d
      ```

      ```
sudo vim 008-exif.ini
      ```
      
      ```
      extension = "exif.so"
      ```
   
   6. 重启php
   
      ```
      service php-fpm restart
      ```
   

## 四、个性化配置

H5ai可以启用一些个性化服务，只需要修改相关配置即可。配置路径：

`_h5ai/private/conf/options.json`

1. **开启文件搜索**

   在配置文件中搜索“search”，将false改为ture

   ```linux
    "search": {
   	"enabled": true,
   	"advanced": true,
   	"debounceTime": 300,
   	"ignorecase": true
   },
   ```

2. **打包下载**

   在配置文件中搜索“select”，增加复选框

   ```linux
   "select": {
   	"enabled": true,     
   	"clickndrag": true,     
   	"checkboxes": true
    },
   ```

   继续搜索“download”，将false改为ture。注意查看默认打包的格式，可以选择默认的`.tar`也可以改成`.zip`，只需要将php-tar改为shell-zip

3. **二维码扫码下载**
   在配置文件中搜索“info”，将false改为true，即可以实现通过扫码下载文件

   ```linux
   "info": {
   	"enabled": true,
       "show": true,
       "qrcode": true,
       "qrFill": "#999",
       "qrBack": "#fff"
    },
   ```

更简单的方法可以使用“宝塔面板”进行搭建：[使用宝塔面板和h5ai搭建资源分享站](https://www.ratodo.com/article/h5ai-share.html/)

也可以看看这篇文章：[h5ai 目录列表程序完整安装使用教程](https://www.n-1.cn/29.html/)



# 利用docker进行搭建

### 拉取并配置镜像

```linux
#命令
docker run [-t/-d] -p [80]:80 -v [$PWD]:/h5ai --name h5ai ilemonrain/h5ai:[lite/full]

#示例
docker run --restart=always -t -p 2333:80 -d -v /opt/h5ai:/h5ai --name h5ai ilemonrain/h5ai:full
```

- **-d/-t：**决定是以**后台运行模式启动**或**是前台监控模式启动**。 使用-d参数启动，镜像将不会输出任何日志到你的Console，直接以Daemon模式启动。Deamon模式启动下，可以使用docker logs h5ai命令显示启动日志。 使用-t参数启动，将会直接Attach你的镜像到你的Console，这个模式启动下，你可以直观的看到镜像的启动过程，适合于初次部署镜像，以及镜像Debug部署使用。你可以使用Ctrl+C将Docker镜像转入后台运行，使用docker attach h5ai命令显示启动日志。 
- **-p [80]:80：**h5ai on Docker 需要映射的端口，**方括号中端口可任意修改为你需要的端口**。
- **-v /home/h5ai:/h5ai：**映射目录，将会自动在选定的目录下创建h5ai程序目录(_h5ai)和Apache2必要的.htaccess文件，如果在在使用完成后不需要这两个文件，可以自行删除；如果需要映射当前目录(可以使用pwd命令确定)，请直接输入 “$PWD”。 
- **--name h5ai：**Docker容器的名称，可以自行修改。 #ilemonrain/h5ai:[lite/full]：启动的镜像名称，请注意：如果你只是为了测试镜像，或者Docker宿主机所在网络环境不佳，请使用lite分支 (即 ilemonrain/h5ai , ilemonrain/h5ai:latest , ilemonrain/h5ai:lite均可)；正式使用或者需要完整功能，请使用full分支 (ilemonrain/h5ai:full)。