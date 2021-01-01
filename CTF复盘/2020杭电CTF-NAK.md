# 又见面了

看见后觉得是base64加密的

```
RlBJWkwlN0JTa2t6RXVhR21ncnQlN0QlMjBDbGFzc2ljYWwlMjBjcnlwdG8=
```

于是base64解码后得到，发现像URL编码

```
FPIZL%7BSkkzEuaGmgrt%7D%20Classical%20crypto
```

进行url解码

```
FPIZL{SkkzEuaGmgrt} Classical crypto
```

再进行凯撒解码，找到flag

```
ZJCTF{MeetYouAgaln} Wfummcwuf wlsjni
```



# 尺蠖求伸

得到文件后，IDA打开，查找字符串后找到flag

![2020杭电CTF (2)](E:%5C%E5%AD%A6%E4%B9%A0%E7%AC%94%E8%AE%B0%5Cmarkdown%20works%5CCTF%E5%A4%8D%E7%9B%98%5Cimages%5C2020%E6%9D%AD%E7%94%B5CTF%20(2).png)

进入后查看伪代码

![2020杭电CTF (1)](E:%5C%E5%AD%A6%E4%B9%A0%E7%AC%94%E8%AE%B0%5Cmarkdown%20works%5CCTF%E5%A4%8D%E7%9B%98%5Cimages%5C2020%E6%9D%AD%E7%94%B5CTF%20(1).png)

flag被base64加密了，于是进行解密，得到

```
ZJCTF{rE_15_H4rD_8U7_U5EfuL}
```

