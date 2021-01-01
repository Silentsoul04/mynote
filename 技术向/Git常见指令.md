---
title: Git常见指令
date: 2020-09-08 12:36:13
tags:
 - git
description: 教程
---

## 1.1本地库初始化

**命令：**git init

会在当前目录下创建一个隐藏目录 “.git”，会存放当前项目本地库相关的子目录和文件，非常重要，不要乱搞！！！



## 1.2设置签名

**形式：**

- 用户名：Antlers
- Email地址：2333@999.com

**作用：**区分不同的开发人员身份，可以随便写一个甚至不存在的地址

**辨析：**这个设置的签名和登录**远程库**（**代码托管中心**，例如：GitHub）的账号、密码是不一样的

**命令：**

- 项目级别/仓库级别：仅在当前本地库范围有效
  - git **config** user.name antlers_pro		//后面跟上的_pro是自我提示为项目级别
  - git **config** user.email 2333_pro@999.com
  - 信息保存位置：./.git/config 文件下
- 系统用户级别：登录当前操作系统的用户范围
  - git config *--global* user.name antlers_glb
  - git config *--global* user.email 2333_pro@999.com
  - 信息保存位置：~/.gitconfig 文件下

**级别优先级：**

​	就近原则：项目级别优先于系统级别

​	二者都没有是不被允许的，操作会报错