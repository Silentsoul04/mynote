# 备份你的Hexo博客

## 介绍

为了防止本地hexo因为不可抗力导致损坏，备份我们的hexo本地资料是很有必要的。

在这里我们就可以利用git的分支系统进行多终端工作了，这样每次打开不一样的电脑，只需要进行简单的配置和在github上把文件同步下来，就可以无缝操作了。



## 机制

由于本地的hexo使用`hexo d`上传部署到github的其实是hexo编译后的文件，是用来生成网页的，不包含源文件。

也就是上传的是在本地目录里自动生成的`.deploy_git`里面。

其他文件 ，包括我们写在source 里面的，和配置文件，主题文件，都没有上传到github

所以可以利用git的分支管理，将源文件上传到github的另一个分支即可。



## 开始备份

### 步骤

1. github博客仓库创建新分支，并设为默认
2. 克隆到本地，整合备份数据
3. 推送至默认的新分支进行备份



那首先我们需要在仓库创建新的分支，例如设置为：hexo

![img](https://pic1.zhimg.com/80/v2-ebb3e05632e85ab036663390305caa1c_720w.jpg?source=1940ef5c)

然后在这个仓库的settings中，选择默认分支为hexo分支（这样每次同步的时候就不用指定分支，比较方便）。

![img](https://pic4.zhimg.com/80/v2-1899b6219f3787832652813b958b9b3d_720w.jpg?source=1940ef5c)

然后在本地的任意目录下，打开git bash，将博客仓库clone至本地；

```
git clone git@github.com:Username/Username.github.io.git
```

**注意：因为默认分支已经设成了hexo，所以clone时只clone了hexo**



接下来在克隆到本地的`Username.github.io.git`文件夹中，把除了.git 文件夹外的所有文件都删掉。比如将themes主题文件夹中的`.git`删除，**否则无法将主题文件夹push**；

再把之前我们写的博客源文件全部复制过来，除了`.deploy_git`

**注意：**这边复制过来的源文件应该有一个`.gitignore`，用来忽略一些不需要的文件，如果没有的话，自己新建一个，在里面写上如下，表示这些类型文件不需要git：

```text
.DS_Store
Thumbs.db
db.json
*.log
node_modules/
public/
.deploy*/
```



在`Username.github.io`文件夹执行`npm install`，`npm install hexo-deployer-git`(这里可以看看分支是不是显示为Hexo)

![img](https://upload-images.jianshu.io/upload_images/4904768-2d12049be9999009.png?imageMogr2/auto-orient/strip|imageView2/2/w/476/format/webp)

最后在`Username.github.io`文件夹使用git bash，使用下面的git命令

```text
git add .
git commit –m "add branch"
git push 
```

这样就上传完了，可以去你的github上看一看hexo分支有没有上传上去，其中`node_modules`、`public`、`db.json`已经被忽略掉了，没有关系，不需要上传的，因为在别的电脑上需要重新输入命令安装 。



## 发布新文章&及时备份

在本地对博客修改（包括修改主题样式、发布新文章等）后

1、执行`git add .`，`git commit -m "提交文件"`，`git push origin hexo`来提交Hexo网站源文件；（由于我们在windows下，所以直接写个bat）

```
git add .
git commit -m "beifen"
git push origin hexo
```

2、执行hexo g -d 生成静态网页部署到github上；
 （每次发布重复这两步，它们之间没有严格的顺序）

**注意：**这里hexo的_config.yml配置文件需要保证我们上传分支设置正确

```
deploy:
  type: git
  repo: git@github.com:antlers12/antlers12.github.io.git
  branch: master		# 这些决定上传那个分支
```



## 更换电脑恢复Hexo

一样的，跟之前的环境搭建一样

### 步骤

1. 安装git

2. 设置git全局邮箱和用户名

   ```
   git config --global user.name "yourgithubname"
   git config --global user.email "yourgithubemail"
   ```

3. 设置ssh key

   ```
   ssh-keygen -t rsa -C "youremail"
   #生成后填到github和coding上（有coding平台的话）
   #验证是否成功
   ssh -T git@github.com
   ssh -T git@git.coding.net #(有coding平台的话)
   ```

4. 安装nodejs

5. 安装hexo

   ```
   npm install hexo-cli -g
   ```

6. 然后在本地空文件夹克隆我们备份的文件，这里我们指定一下只clone hexo分支

   ```
   git clone -b hexo git@github.com:Username/Username.github.io.git
   ```

7. 在文件夹内执行以下命令

   ```
   npm install hexo-deployer-git
   ```




**Tips:**

1. 不要忘了，每次写完最好都把源文件上传一下

```text
git add .
git commit –m "xxxx"
git push 
```

1. 如果是在已经编辑过的电脑上，已经有clone文件夹了，那么，每次只要和远端同步一下就行了

```text
git pull
```



## 附录

Hexo的源文件说明：
 1、`_config.yml`站点的配置文件，需要拷贝；
 2、`themes/`主题文件夹，需要拷贝；
 3、`source`博客文章的.md文件，需要拷贝；
 4、`scaffolds/`文章的模板，需要拷贝；
 5、`package.json`安装包的名称，需要拷贝；
 6、`.gitignore`限定在push时哪些文件可以忽略，需要拷贝；
 7、`.git/`主题和站点都有，标志这是一个git项目，不需要拷贝；
 8、`node_modules/`是安装包的目录，在执行npm install的时候会重新生成，不需要拷贝；
 9、`public`是hexo g生成的静态网页，不需要拷贝；
 10、`.deploy_git`同上，hexo g也会生成，不需要拷贝；
 11、`db.json`文件，不需要拷贝。