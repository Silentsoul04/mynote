---
title: LAMP框架搭建
date: 2020-3-23 10:16:15
tags:
 - LAMP
 - 教程
 - vps
description: 教程
---
## LAMP的搭建

- 如果您的服务器系统：Amazon Linux / CentOS / RedHat

```
yum -y install wget git
git clone https://github.com/teddysun/lamp.git
cd lamp
chmod 755 *.sh
./lamp.sh
```

- 如果您的服务器系统：Debian / Ubuntu

```
apt-get -y install wget git
git clone https://github.com/teddysun/lamp.git
cd lamp
chmod 755 *.sh
./lamp.sh
```

- [自动化安装模式](https://lamp.sh/autoinstall.html)

```
./lamp.sh -h
```

- 自动化安装模式示例

```
./lamp.sh --apache_option 1 --apache_modules mod_wsgi,mod_security --db_option 2 --db_root_pwd teddysun.com --php_option 5 --php_extensions apcu,ioncube,imagick,redis,mongodb,libsodium,swoole --db_manage_modules phpmyadmin,adminer --kodexplorer_option 1
```



## 升级

```
CD  〜 /灯
git reset --hard //重置索引和工作树
git pull //首先获取最新版本
chmod 755 * .sh

./upgrade.sh //选择一个进行升级
./upgrade.sh apache //升级Apache
./upgrade.sh db //升级MySQL或MariaDB
./upgrade.sh php //升级PHP
./upgrade.sh phpmyadmin //升级phpMyAdmin
./upgrade.sh管理员//升级管理员
```



## 后备

- 您必须先修改配置，然后再运行
- 备份MySQL或MariaDB日期数据库，文件和目录
- 备份文件使用SHA1消息摘要通过AES256-cbc加密（取决于`openssl`命令）（选项）
- 自动将备份文件传输到Google云端硬盘（取决于[`rclone`](https://teddysun.com/469.html)命令）（可选）
- 自动将备份文件传输到FTP服务器（取决于`ftp`命令）（可选）
- 自动从Google云端硬盘或FTP服务器删除远程文件（可选）

```
./backup.sh
```



## 卸载

```
./uninstall.sh
```

[文章参考](https://lamp.sh/autoinstall.html)