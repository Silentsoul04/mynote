# Apache

下载地址：[Apache 下载](https://www.apachelounge.com/download/)

### 安装

下载后解压，修改配置文件后，用管理员模式下运行cmd进行安装

1. 修改主配置文件"httpd.conf"中SRVROOT值（大约37行附近）

   ```
   #修改路径到实际目录下
   Define SRVROOT "C:/wanp/apache"
   ```

2. 管理员模式下运行cmd

   ```
   实际路径\bin\httpd.exe -k install
   ```

3. 双击启动/bin/ApacheMonitor.exe

4. 右下角双击图标后start启动apache

5. 浏览器输入127.0.0.1查看是否成功启动

### 检测配置文件语法

cmd下运行

```
完整路径下/bin/httpd.exe -t
```

### 多站点配置

1. 在apache 主配置文件（httpd.conf），引用多站点配置

   ```
   #大约在517行，取消注释，将该路径下的配置文件包含到主配置文件下
   
   # Virtual hosts
   Include conf/extra/httpd-vhosts.conf
   ```

2. 在虚拟主机配置文件（httpd-vhosts.conf）中，再挨个网站进行配置（每个网站一个配置）

