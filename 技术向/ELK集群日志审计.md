

docker启动ELK

https://blog.csdn.net/zab635590867/article/details/110264144

https://blog.csdn.net/abc8125/article/details/106858862





```
docker run --restart=always -p 5601:5601 -p 9200:9200 -p 5044:5044 -e ES_MIN_MEM=128m -e ES_MAX_MEM=1024m -it --name elk sebp/elk

docker run --restart=always -p 5601:5601 -p 9200:9200 -p 5044:5044 -it --name elk sebp/elk



sudo docker run --restart=always -p 5601:5601 -p 9200:9200 -p 5044:5044 -it -e LOGSTASH_START=0 -e KIBANA_START=0 --name elk registry.cn-hangzhou.aliyuncs.com/testhub/elk
```









参考：https://www.cnblogs.com/mazhilin/p/12096381.html

拉取镜像

```
docker pull docker.elastic.co/elasticsearch/elasticsearch:7.10.1
```

重命名镜像

```
docker tag docker.elastic.co/elasticsearch/elasticsearch:7.10.1 elasticsearch:latest
```

部署命令

```
docker run -itd -p 9200:9200 -p 9300:9300 --restart=always --privileged=true --name elasticsearch-server -e "discovery.type=single-node" -e ES_JAVA_OPTS="-Xms=512m -Xms=512m" elasticsearch:latest /usr/share/elasticsearch/config /usr/share/elasticsearch/logs
```

进行配置

```
docker exec -it elasticsearch-server /bin/bash
```

