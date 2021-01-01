# Git文件泄露

本机工具地址：C:\\\CTFTools\Web\信息收集\GitHack-master

使用方法：

```
python2 GitHack.py http://XXXXXXXXXXX/.git/
```



## 介绍

当前大量开发人员使用git进行版本控制，对站点自动部署。如果配置不当,可能会将.git文件夹直接部署到线上环境。这就引起了git泄露漏洞