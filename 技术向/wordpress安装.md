# wordpress安装

```
# 默认密码为空，如果出现 Enter password 直接回车即可
mysql -u root -p

use mysql;
# passwd 则为你想设置的密码
update user set authentication_string=password("passwd") where user="root";
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'passwd';(mysql8.0以后)
# 开启远程登录
update user set host='%' where user='root';
GRANT ALL ON *.* TO 'root'@'%';
# 刷新权限
FLUSH PRIVILEGES;

service mysql restart



sudo mysql_secure_installation
```









```
docker run --name wordpress -p 80:80 -d wordpress
```









**安装**

```
sudo apt-get install mysql-server 
```

