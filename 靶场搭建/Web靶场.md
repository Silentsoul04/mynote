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