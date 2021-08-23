## 未知文件

**通常有以下可能**

- 压缩包（.gz，.bzip2等）

  ```
  tar xvf flag.bzip2
  ```

  特殊解压方法

  ```
  gunzip < flag.bzip2
  ```

- 磁盘文件，文件系统数据需要挂载

  ```
  mount -o loop forensic100 /tmp/forensic
  ```



linux下查看文件类型

```
file 文件名
```



### 文件反转

```python
import os
f = open('文件名',"rb")#二进制形式打开
f = f.read()[::-1]
for i in f:
    ans = str(hex(i))[2:][::-1]
    if len(ans) == 1:
        ans = ans + '0'
    print(ans,end='')
```



## 图像类

**入手方向**

- 图片高度
- 

### 通道工具 stegsolve

不用多说了，就是好用



### 提取隐藏文件 foremost

```
git clone https://github.com/raddyfiy/foremost.git
```

binary里就有编译好的，直接用这个就好

常用命令

```
foremost.exe flag.png
```



### LSB神器 zsteg

一句话，无敌！

**安装**

```
git clone https://github.com/zed-0xff/zsteg
cd zsteg/
gem install zsteg
```

**zsteg的使用方法**

- 查看LSB信息

  ```
  zsteg flag.png
  ```

- 检测zlib

  ```
  # -b的位数是从1开始的
  zsteg zlib.bmp -b 1 -o xy -v
  ```

- 显示细节

  ```
  zsteg flag.png -v
  ```

- 尝试所有已知的组合

  ```
  zsteg flag.png -a
  ```

- 导出内容

  ```
  zsteg -E "b1,bgr,lsb,xy" flag.png > p.exe
  ```

**常见命令**

```
zsteg flag.bmp
```







## 流量包

**常见入手方法**

- 追踪TCP流
- 字符串搜索关键词，flag格式（flag，ctf等），文件（png，jpg等）
- 



### 导出流数据

需要选择导出原始数据

![流3](images/CTF%E6%9D%82%E9%A1%B9%E6%80%BB%E7%BB%93.assets/20201126185109364.png)

## 镜像，磁盘

### 恢复磁盘删除文件

linux安装extundelete `sudo apt-get install extundelete`

用extundelete恢复误删文件命令

```
extundelete disk-image --restore-alls
```

