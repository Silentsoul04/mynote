## XSS蓝莲花

```
git clone https://github.com/sqlsec/BlueLotus_XSSReceiver.git && cd BlueLotus_XSSReceiver
docker build -t bluelotus .
docker run --restart=always -d -p 8000:80 bluelotus
```

