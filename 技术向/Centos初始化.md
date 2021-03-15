# Centos初始化

在运行更新之前，您可以使用以下命令检查可用更新：

```
sudo yum check-update
```

要更新单个程序包，请使用 yum install 命令，后跟要更新的程序包的名称。例如，要仅更新 curl 您要运行的包：

```
sudo yum install curl
```

要更新所有包，请使用以下 yum update 命令：

```
sudo yum update
```