# Web靶场搭建

DVWA：

```
docker run --restart=always -it --name dvwa -p 2333:80 vulnerables/web-dvwa
```

sqli-labs：

```
docker run --restart=always -dt --name sqli -p 2334:80 acgpiano/sqli-labs
```

xssPlatform搭建：https://segmentfault.com/a/1190000021899373



```
docker run --name mysqlserver -e MYSQL_ROOT_PASSWORD=123 -d -i -p 3309:3306  mysql:5.6
```

```
docker run --restart=always --name xssPlatform_test --link mysqlserver:db  -d -i  -p 2336:80   daxia/websafe:latest
```

