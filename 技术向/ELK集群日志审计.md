# ELK日志审计集群搭建

## 简介

ELK是由 Elasticsearch、Logstash和Kibana 三部分组件组成

> Elasticsearch：是个开源分布式搜索引擎，它的特点有：分布式，零配置，自动发现，索引自动分片，索引副本机制，restful风格接口，多数据源，自动搜索负载等。
>
> Logstash：是一个完全开源的工具，它可以对你的日志进行收集、分析，并将其存储供以后使用。
>
> kibana：是一个开源和免费的工具，它可以为 Logstash 和 ElasticSearch 提供的日志分析友好的 Web 界面，可以帮助您汇总、分析和搜索重要数据日志。

除了我们需要有这个分析平台，我们还需要监控的设备发送日志到这个平台，这里我们就还需要Filebeat了

> Filebeat 是用于单用途数据托运人的平台。它们以轻量级代理的形式安装，并将来自成百上千台机器的数据发送到 Logstash 或 Elasticsearch。



## 环境

![img](https://antlersmaskdown.oss-cn-hangzhou.aliyuncs.com/e098cee6a0abafcbbebcb0a1c169a617f50.png)

参考文章：

[使用docker安装ELK](https://blog.csdn.net/abc8125/article/details/106858862)、[Docker部署ELK](https://my.oschina.net/u/4313128/blog/4074001)、[Linux系统Centos7 基于Docker搭建ELK分布式日志系统](https://www.cnblogs.com/mazhilin/p/12096381.html)、[ELK部署笔记（docker-compose部署）](https://blog.csdn.net/zab635590867/article/details/110264144)

这里我们主要参考——[Docker安装部署ELK](https://www.jianshu.com/p/d29b64b02b4d)，来用docker一个一个安装





## 创建自定义的网络

我们这里创建一个用于连接到同一网络的其他服务(例如Kibana))

```
docker network create my-network
```

规划相关的重要文件映射关系，我们选择映射在/root下

```
mkdir -p /root/data/es/{conf,data}
mkdir -p /root/data/logstash/config
```



## 安装Elasticsearch

```
docker pull elasticsearch:7.10.1

docker run -d --name elasticsearch --net my-network -p 9200:9200 -p 9300:9300 -e "discovery.type=single-node" -v /root/data/es/data:/var/lib/elasticsearch elasticsearch:7.10.1
```

检测 elasticsearch 是否启动成功

```
curl 127.0.0.1:9200
```



## 安装Logstash

```
docker pull logstash:7.10.1
```

我们将docker中logstash的文件复制到本地创建的目录中，进行配置修改，随便起一个docker，记得待会删掉

```
docker run -tid --name logstash logstash:7.10.1
docker cp -a logstash:/opt/logstash/config /root/data/logstash
docker stop logstash
docker rm logstash
```

可以设置刚刚复制出来的文件内容——logstash.yml

```
path.config: /root/docker-root/logstash/conf.d/*.conf
path.logs: /root/docker-root/logstash/logs
```

设置文件——pipelines.yml

```
input {
    beats {
        port => 5044
        codec => "json"
    }
}

output {
  elasticsearch { hosts => ["10.128.2.13:9200"] }
  stdout { codec => rubydebug }
}

#########################
input {
    beats {
    port => 5044
    codec => "json"
}
}

output {
  elasticsearch { hosts => ["elasticsearch:9200"] }
  stdout { codec => rubydebug }
}
```

启动 Logstash

```
docker run -d --restart=always --log-driver json-file --log-opt max-size=10m --log-opt max-file=2 -p 5044:5044 --name logstash -v /root/data/logstash/config:/usr/share/logstash/config logstash:7.10.1
```



## 安装Kibana

使用和 Elasticsearch 相同版本镜像 7.10.1 （不一样可能会出现问题）

```
docker pull kibana:7.10.1
```

和之前一样，将文件复制出来

```
docker run -tid --name kibana kibana:7.10.1
docker cp -a kibana:/opt/kibana/config/kibana.yml /root/data/es/conf
docker stop kibana
docker rm kibana
```

启动Kibana，顺便设置一个中文模式

```
docker run -d  --restart=always --name kibana --net my-network -p 5601:5601 -e ELASTICSEARCH_HOSTS=http://10.128.2.13:9200 -e “I18N_LOCALE=zh-CN” kibana:7.10.1
```

这里可能有开启防火墙，我们需要设置一下防火墙

```
sudo ufw allow 5601 // 开启5601端口
```



## 安装Filebeat

### 普通部署

```
curl -L -O https://artifacts.elastic.co/downloads/beats/filebeat/filebeat-7.10.2-amd64.deb
sudo dpkg -i filebeat-7.10.2-amd64.deb
```

编辑配置文件

```
vim filebeat-6.2.4-linux-x86_64/filebeat.yml
```

```
#=========================== Filebeat prospectors =============================

filebeat.prospectors:

- type: log
  enabled: true
  paths:
   - /data/logs/admin.log
   - /data/logs/mobile.log
  fields:
   log_source: node1
   logtype: applog
  document_type: applog
  multiline.pattern: '^\['
  multiline.negate: true
  multiline.match: after
  exclude_lines: ['DEBUG']



#----------------------------- Logstash output --------------------------------
output.logstash:
  # The Logstash hosts
  hosts: ["192.168.1.56:5044"]
```

filebeat.yml 配置的主要有两个部分，一个是日志收集，一个是日志输出的配置。

配置解释：

- type: log 读取日志文件的每一行（默认）
- enabled: true 该配置是否生效,如果改为false,将不收集该配置的日志
- paths: 要抓取日志的全路径
- fields: 自定义属性,可以定义多个,继续往下排就行
- multiline.pattern: 正则表达式
- multiline.negate: true 或 false；默认是false，匹配pattern的行合并到上一行；true，不匹配pattern的行合并到上一
- multiline.match: after 或 before，合并到上一行的末尾或开头
- exclude_lines: ['DEBUG'] 该属性配置不收集DEBUG级别的日志,如果配置多行 这个配置也要放在多行的后面
- 192.168.1.56:5044 为输出到Logstash的地址和端口。

启动filebeat

```
nohup ./filebeat -e -c filebeat.yml &
```



### docker部署

```
docker pull docker.io/prima/filebeat
```



```
curl -L -O https://raw.githubusercontent.com/elastic/beats/7.1/deploy/docker/filebeat.docker.yml
```





### 验证

输入日志文件

进入/data/logs目录输入日志

```
[root@VM_108_39_centos logs]# echo "删除用户" >> admin.log
[root@VM_108_39_centos logs]#  echo "提现成功 " >> mobile.log
[root@VM_108_39_centos logs]# echo "注册成功 " >> mobile.log
[root@VM_108_39_centos logs]#  echo "I love you,admin" >> admin.log
```

