---
title: CentOS 8.x的Docker安装
date: 2020-10-4 15:10:08
tags:
 - docker
 - 教程
 - vps
description: 教程
---
# CentOS 8.x Docker的安装

## 前言

文章参考[centos8.3安装docker](https://www.cnblogs.com/lhd1998/p/13297229.html)



## 添加Docker存储库

首先，我们必须添加一个外部存储库以获得Docker CE。我们将使用官方的Docker CE CentOS存储库。



## 1、下载docker-ce的repo

```
curl https://download.docker.com/linux/centos/docker-ce.repo -o /etc/yum.repos.d/docker-ce.repo
```

![img](https://mmbiz.qpic.cn/mmbiz_png/jhtEbpg4m6EY4Qecbho653yBGYeyzM7mCWPExYcY5A0ICMtbSqJ6Qd9Gdib1hibaOSEdWfhx2FHYkOeFOk0WpHgg/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)



## 2、安装依赖（这是相比CentOS 7的关键步骤）

```
yum install https://download.docker.com/linux/fedora/30/x86_64/stable/Packages/containerd.io-1.2.6-3.3.fc30.x86_64.rpm
```

![img](https://mmbiz.qpic.cn/mmbiz_png/jhtEbpg4m6EY4Qecbho653yBGYeyzM7mJss8rX4RXgPeTaMfQSNujficwvhPgjUUYkKYNuPw05nSBcWNXJGKglA/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

## 3、安装docker-ce

```
yum install docker-ce
```

![img](https://mmbiz.qpic.cn/mmbiz_png/jhtEbpg4m6EY4Qecbho653yBGYeyzM7mawEUR52cjIynJSaNB2yMJ95tHr5jrKPYu9OJibsnCUGkGxD0zyws2yQ/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

## 4、启动docker

```
systemctl start docker
```

![img](https://mmbiz.qpic.cn/mmbiz_png/jhtEbpg4m6EY4Qecbho653yBGYeyzM7msOiawQLPlVA2YiaZRvDlnhxHt3GicdXLb7IibWxgQGKZeoWSxs27YicHgew/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

我们可以用以下命令检查该服务是否正在正常运行：

```
systemctl status docker
```

![img](https://mmbiz.qpic.cn/mmbiz_png/jhtEbpg4m6EY4Qecbho653yBGYeyzM7mqicJD1dDtpib1lzgLY4kcvNbVNPkLQyGetC00uegJTDlZfiaDcUwMydwg/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

到此，Docker安装成功，其他功能有待测试。



## Centos8安装Docker流程及报错解决方案

[Centos8安装Docker流程及报错解决方案](https://blog.csdn.net/qq_42759386/article/details/104931165?utm_medium=distribute.wap_relevant.none-task-blog-BlogCommendFromMachineLearnPai2-2.nonecase&depth_1-utm_source=distribute.wap_relevant.none-task-blog-BlogCommendFromMachineLearnPai2-2.nonecase)



# 拉取镜像

它会首先搜索本地镜像，如果没有找到，它会从 Docker Hub 上拉取相应的镜像

```
docker pull 拉取的镜像名
```



# docker常用命令 

```
docker exec -it h5ai /bin/bash		//进入docker
```

```bash
mysql_secure_installation  //初始化数据库
```

```
docker ps -a	//拿到容器ID
```

```
docker cp 本地文件路径 ID全称:容器路径		//将本地文件上传到容器的指定目录中
```

![在这里插入图片描述](https://img-blog.csdnimg.cn/2018110810415992.png)

[LAMP docker安装教程](https://blog.csdn.net/xphouziyu/article/details/81838912)，网站根目录`/var/www/html`



docker容器生命周期管理

docker run 在一个新的容器执行一个命令

docker start 开一个或者更多已经停止的容器

**docker stop 停止一个运行的容器**

docker restart 重新开始一个运行的容器

**docker kill 杀死一个运行的容器**

docker rm 删除一个或更多的容器

docker pause 暂停一个容器的所有进程

docker unpaunse 恢复一个容器的所有进程

docker容器操作运维

**docker ps 列出容器**	-a所有

docker inspect 列出关于容器或镜像的底层信息

docker top 显示一个容器运行的进程

docker attach 附上一个运行的容器

docker events 得到实时的来自服务的事件

docker log 获得一个容器的日志

docker wait 阻塞直到一个容器停止，然后打印它的退出代码

docker export 导出一个容器的文件系统作为一个tar文件

**docker port 列出容器映射的端口**

docker版本管理

docker commit 创建一个新的镜像从一个容器

docker cp 从容器复制一个文件或目录到本地目录或标准输出

docker diff 展示一个容器文件系统的变化

docker镜像仓库

docker login 登入仓库

**docker pull 拉取镜像**

**docker push 推送镜像**

docker search 搜索镜像

docker本地镜像管理

**docker images 列出本地的镜像**

docker rmi 删除本地的镜像

docker tag 打标签本地的镜像

docker build 通过Dockerfile构建本地的镜像

**docker histroy 列出镜像的历史**

docker save 保存本地的镜像为一个tar文件

docker import 通过tar导入镜像

docker load 加载tar镜像

docker其它命令

docker info

docker version



## docker私有仓库操作

\1. 配置docker daemon的参数，由于每个发行版的linux的init系统不用，所以使用通用的命令行方式重启docker daemon

docker daemon --insecure-registry 192.168.6.65:5000 &

\2. 认证私有仓库

docker login -u  -p  -e  192.168.6.65:5000

\3. 拉取镜像

docker pull 192.168.6.65:5000/centos:latest

\4. tag本地镜像

docker tag 192.168.6.65:5000/centos:1.0.0 192.168.6.65:5000/centos:latest

\5. 推送镜像

docker push 192.168.6.65:5000/centos:1.0.0

6.docker， mac 的镜像目录

/Users/{YourUserName}/Library/Containers/com.docker.docker/Data/com.docker.driver.amd64-linux/Docker.qcow2 保存到文件当中



## docker run 命令详解

#### docker run [OPTIONS] IMAGE [COMMAND] [ARG...] 

-d,--detach=false指定容器运行于前台还是后台，默认为false

-i,--interactive=false打开STDIN，用于控制台交互

-t,--tty=false分配tty设备，该可以支持终端登录，默认为false

-u,--user=""指定容器的用户

-a,--attach=[]      登录容器（必须是以docker run -d启动的容器）

-w,--workdir=""指定容器的工作目录

-c,--cpu-shares=0设置容器CPU权重，在CPU共享场景使用

-e,--env=[]        指定环境变量，容器中可以使用该环境变量

-m,--memory=""指定容器的内存上限

-P,--publish-all=false指定容器暴露的端口

-p,--publish=[]      指定容器暴露的端口

-h,--hostname=""指定容器的主机名

-v,--volume=[]      给容器挂载存储卷，挂载到容器的某个目录

--volumes-from=[]     给容器挂载其他容器上的卷，挂载到容器的某个目录

--cap-add=[]        添加权限，权限清单详见：http://linux.die.net/man/7/capabilities

--cap-drop=[]       删除权限，权限清单详见：http://linux.die.net/man/7/capabilities

--cidfile=""运行容器后，在指定文件中写入容器PID值，一种典型的监控系统用法

--cpuset=""设置容器可以使用哪些CPU，此参数可以用来容器独占CPU

--device=[]        添加主机设备给容器，相当于设备直通

--dns=[]          指定容器的dns服务器

--dns-search=[]      指定容器的dns搜索域名，写入到容器的/etc/resolv.conf文件

--entrypoint=""覆盖image的入口点

--env-file=[]       指定环境变量文件，文件格式为每行一个环境变量

--expose=[]        指定容器暴露的端口，即修改镜像的暴露端口

--link=[]         指定容器间的关联，使用其他容器的IP、env等信息

--lxc-conf=[]       指定容器的配置文件，只有在指定--exec-driver=lxc时使用

--name=""指定容器名字，后续可以通过名字进行容器管理，links特性需要使用名字

--net="bridge"容器网络设置:

bridge 使用docker daemon指定的网桥

host  //容器使用主机的网络

container:NAME_or_ID>//使用其他容器的网路，共享IP和PORT等网络资源

none 容器使用自己的网络（类似--net=bridge），但是不进行配置

--privileged=false指定容器是否为特权容器，特权

容器拥有所有的capabilities

--restart="no"指定容器停止后的重启策略:

no：容器退出时不重启

on-failure：容器故障退出（返回值非零）时重启

always：容器退出时总是重启

--rm=false指定容器停止后自动删除容器(不支持以docker run -d启动的容器)

--sig-proxy=true设置由代理接受并处理信号，但是SIGCHLD、SIGSTOP和SIGKILL不能被代理

example：

1.docker run -d--name=server-db -p 3306:3306 centos6.8-mysql /usr/bin/mysql_safe –d

主机3306端口映射到容器3306端口

2.docker run -it --rm centos6.8 容器结束后自动删除

3.docker run -d--name=server-db -p 3306:3306 -v /server/mysql-data:/mysql-data centos6.8-mysql /usr/bin/mysql_safe –d

将主机的/server/mysql-data 目录映射到容器 /mysql-data 中

## docker 技巧 

1..清理无用的docker容器命令

docker ps -aqf  status=exited | xargs docker rm

2.清理无用镜像的命令

docker images -qf dangling=true | xargs docker rmi

3.清理无用的docker挂载

docker volume rm $(docker volume ls -q -f dangling=true)

dockerignore使用

类似.gitignore一样，运行Dockerfile里的COPY指令的时候会根据.dockerignore进行部分目录或者文件忽略。

进入容器

```
sudo docker exec -it xxxxx sh
```

