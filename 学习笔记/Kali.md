# NMAP

扫描当前网段存在主机

```
nmap -sn IP网段/xx
```

扫描目标IP开放的端口

```
nmap IP地址

（1）单个主机如  nmap 10.29.1.113

（2）以CIDR标记法表示整个网段 如 nmap 10.29.1.0/24 

（3）十进制的IP区间如 nmap 10.29.1-5.1 表示4个网段的IP

（4）多个主机目标10.29.1.1 10.29.3-5.1
```



# Hydra&XHydra

端口爆破：FTP、SSH等

一、爆破FTP

```
hydra  -L  /root/Desktop/user.txt  -P  /root/Desktop/pass.txt  ftp://192.168.59.153
```

 二、爆破SSH

```
hydra -L  /root/Desktop/user.txt  -P  /root/Desktop/pass.txt  192.168.59.153 ssh
```



# msfconsole

端口渗透

一、进入工具

```
msfconsole
```

二、找到对应得模块

```
search  ftp（协议名）
```

三、查看配置模块

```
show options
```

四、设置相关参数

- 设置主机名

  ```
  set rhost 192.168.59.153
  ...
  ```

五、利用模块

```
exploit
```

