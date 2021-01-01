---
title: 一个好用的命令——screen
date: 2020-8-15 8:26:08
tags:
 - screen
 - 教程
 - vps
description: 教程
---



# screen命令使用方法



## 什么是screen

Linux screen命令用于多重视窗管理程序，此处所谓的视窗，是指一个全屏幕的文字模式画面。通常只有在使用telnet登入主机或是使用老式的终端机时，才有可能用到screen程序。



## 语法

> ```
> screen [-AmRvx -ls -wipe][-d <作业名称>][-h <行数>][-r <作业名称>][-s <shell>][-S <作业名称>]
> ```

**参数说明**：

- -A 　将所有的视窗都调整为目前终端机的大小。
- -d<作业名称> 　将指定的screen作业离线。
- -h<行数> 　指定视窗的缓冲区行数。
- -m 　即使目前已在作业中的screen作业，仍强制建立新的screen作业。
- -r<作业名称> 　恢复离线的screen作业。
- -R 　先试图恢复离线的作业。若找不到离线的作业，即建立新的screen作业。
- -s <shell> 　指定建立新视窗时，所要执行的shell。
- -S<作业名称> 　指定screen作业的名称。
- -v 　显示版本信息。
- -x 　恢复之前离线的screen作业。
- -ls或--list 　显示目前所有的screen作业。
- -wipe 　检查目前所有的screen作业，并删除已经无法使用的screen作业。



## 下载screen

除部分精简的系统或者定制的系统大部分都安装了screen命令，如果没有安装，CentOS系统可以执行：

```
apt-get install screen
yum install screen
```

**注意：**CentOS 8上移除了screen，需要[安装epel](https://www.vpser.net/manage/centos-rhel-linux-third-party-source-epel.html)后安装screen执行

Debian/Ubuntu系统执行：

```
apt-get install screen
```



## 常见screen命令

### 创建screen会话

```
screen -S lnmp
```

screen就会创建一个名字为lnmp的会话



### 查看当前系统screen运行窗口

```
screen ls
```



### 删除一个窗口

```
screen -X -S 122128 quit	# 这里的122128是窗口ID号，我们可以ls查看
```



### 暂时离开，保留screen会话中的任务或程序

当需要临时离开时（会话中的程序不会关闭，仍在运行）可以用快捷键Ctrl+a d(即按住Ctrl，依次再按a,d)



### 恢复screen会话

当回来时可以再执行执行：**screen -r lnmp** 即可恢复到离开前创建的lnmp会话的工作界面。如果忘记了，或者当时没有指定会话名，可以执行：**screen -ls** screen会列出当前存在的会话列表，如下图：
[![screen-ls](https://www.vpser.net/uploads/2010/10/screen-ls.jpg)](https://www.vpser.net/uploads/2010/10/screen-ls.jpg)

11791.lnmp即为刚才的screen创建的lnmp会话，目前已经暂时退出了lnmp会话，所以状态为Detached，当使用screen -r lnmp后状态就会变为Attached，11791是这个screen的会话的进程ID，恢复会话时也可以使用：**screen -r 11791**



### 关闭screen的会话

执行：**exit** ，会提示：[screen is terminating]，表示已经成功退出screen会话。



### 远程演示

首先演示者先在服务器上执行 **screen -S test** 创建一个screen会话，观众可以链接到远程服务器上执行**screen -x test** 观众屏幕上就会出现和演示者同步。



### 常用快捷键

Ctrl+a c ：在当前screen会话中创建窗口
Ctrl+a w ：窗口列表
Ctrl+a n ：下一个窗口
Ctrl+a p ：上一个窗口
Ctrl+a 0-9 ：在第0个窗口和第9个窗口之间切换