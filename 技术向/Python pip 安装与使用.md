---
title: Python pip 安装与使用
date: 2020-8-6 18:51:23
tags:
 - Python
 - pip
 - 教程
description: 教程
---
# Python pip 安装与使用

判断pip安装：

```
pip --version
```

如果你还未安装，则可以使用以下方法来安装：

```
$ curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py   # 下载安装脚本
$ sudo python get-pip.py    # 运行安装脚本
```

> **注意：**用哪个版本的 Python 运行安装脚本，pip 就被关联到哪个版本，如果是 Python3 则执行以下命令：
>
> ```
> $ sudo python3 get-pip.py    # 运行安装脚本。
> ```
>
> 一般情况 pip 对应的是 Python 2.7，pip3 对应的是 Python 3.x。

部分 Linux 发行版可直接用包管理器安装 pip，如 Debian 和 Ubuntu：

```
sudo apt-get install python-pip
```



## pip 最常用命令

**显示版本和路径**

```
pip --version
```

**获取帮助**

```
pip --help
```

**升级 pip**

```
pip install -U pip
```

> 如果这个升级命令出现问题 ，可以使用以下命令：
>
> ```
> sudo easy_install --upgrade pip
> ```

**安装包**

```
pip install SomePackage              # 最新版本
pip install SomePackage==1.0.4       # 指定版本
pip install 'SomePackage>=1.0.4'     # 最小版本
```

比如我要安装 Django。用以下的一条命令就可以，方便快捷。

```
pip install Django==1.7
```

**升级包**

```
pip install --upgrade SomePackage
```

升级指定的包，通过使用==, >=, <=, >, < 来指定一个版本号。

**卸载包**

```
pip uninstall SomePackage
```

**搜索包**

```
pip search SomePackage
```

**显示安装包信息**

```
pip show 
```

**查看指定包的详细信息**

```
pip show -f SomePackage
```

**列出已安装的包**

```
pip list
```

**查看可升级的包**

```
pip list -o
```



## 注意事项

如果 Python2 和 Python3 同时有 pip，则使用方法如下：

Python2：

```
python2 -m pip install XXX
```

Python3:

```
python3 -m pip install XXX
```

若由于一些局域网的原因，使用 pip 出现 “connection timeout”，连接超时可以使用国内的镜像网站下载：

-  http://e.pypi.python.org
-  http://pypi.douban.com/simple

命令如下:

```
pip install -i http://pypi.douban.com/simple --trusted-host pypi.douban.com packagename # packagename是要下载的包的名字
pip install -i http://e.pypi.python.org --trusted-host e.pypi.python.org --upgrade pip # 升级pip
```



## 常见问题

pip3升级失败

```
python -m ensurepip
```





## Crypto安装问题

[Python在终端通过pip安装好包以后，在Pycharm中依然无法使用的解决办法](https://blog.csdn.net/kouyi5627/article/details/80531442)

 from Crypto.Util.number import *
ImportError: No module named Crypto.Util.number

当初安装为 pip install crypto导致该问题，第三包有问题需要重新安装

卸载： pip uninstall crypto pycryptodome

重新安装： pip install pycryptodome -i ![img](file:///C:\Users\Antlers\AppData\Roaming\Tencent\QQTempSys\%W@GJ$ACOF(TYDYECOKVDYB.png)https://pypi.tuna.tsinghua.edu.cn/simple

重新执行后，恢复正常