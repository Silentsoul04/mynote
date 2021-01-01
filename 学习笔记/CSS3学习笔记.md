# CSS 学习笔记

## CSS 简介

CSS 是一种用来表现HTML或XML等文件样式的计算机语言。不仅可以静态地修饰网页，还可以配合各种脚本语言动态地对网页各元素进行格式化。

- CSS 指层叠样式表 (**C**ascading **S**tyle **S**heets)
- 样式定义**如何显示** HTML 元素
- 样式通常存储在**样式表**中
- 把样式添加到 HTML 4.0 中，是为了**解决内容与表现分离的问题**
- **外部样式表**可以极大提高工作效率
- 外部样式表通常存储在 **CSS 文件**中
- 多个样式定义可**层叠**为一个

## CSS 语法

CSS 规则由两个主要的部分构成：选择器，以及一条或多条声明:

![img](https://www.runoob.com/wp-content/uploads/2013/07/632877C9-2462-41D6-BD0E-F7317E4C42AC.jpg)

## HTML 中引入CSS样式表

```html
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" href="mycss.css" type="text/css">
</head>

<body>
</body>
</html>
```



## CSS 选择器

### 标记选择器

以标签名作为选择：

```
标记名{
	属性1:属性值1;
    属性2:属性值2;
    属性3:属性值3;
}
```

例如：

```
p{
	font-size:12px;
	color:#666;
}
```



### 类选择器

所需标签内添加class属性来定位：

```
.green {
	color: green;
	font-size: 12px;
}
```

```
<ul class="green">
	<li>HTML</li>
	<li>CSS</li>
	<ul>
		<li>选择器</li>
		<li>盒子模型</li>
		<li>浮动与定位</li>
	</ul>     
</ul>
```



### ID选择器

所需标签内添加ID属性来定位：

```
#purple {
	color: purple;
	font-size: 12px;
	font-weight: bold;
}
```

```
<p id="purple">ID选择器</p>
```



### 通配符选择器

针对所有的元素：

```
*{
	margin: 0;
	padding: 0;
	border: 0;
}
/*消除默认的参数值*/
```



### 交集选择器

选择符合要求的标签进行控制：

```
标签名.类名{
	属性:属性值;
}
```

例如：

```
ul.wenben{
	color: purple;
	font-size: 16px;
}
```



### 后代选择器

用来选择元素或元素组的后代，当发生嵌套时，内层标记就会成为外层标记的后代

```
p strong{
	color: blue;
}
```

```
<p>段落文本<strong>嵌套在里面</strong></p>
```



### 并集选择器

通过“逗号“连接任何一种选择器，进行统一定义样式

```
h2,.class1,#ID6,p strong,span.strong{
	font-size:14px;
	color:red;
}
```



### 关系选择器

#### 子代选择器（>）

子代选择器主要用来选择某个元素的的第一级子元素

```
h1>strong{
	font-size:14px;
	color:pink;
}
```

```
<h1>这个<strong>是重点</strong>的<strong>内容</strong></h1>
<!--这里“是重点”和“内容”都会被选择到-->
```



#### 兄弟选择器

兄弟选择器用来选择与某个元素位于同一父元素之中

##### 临近兄弟选择器

两个元素有同一个父亲，而且第二个元素必须紧跟第一个元素

```
p+h2{
	color: green;
	font-family: "宋体";
	font-size: 20px;
}
```

```
<h2>《赠汪伦》</h2>
<p>李白乘舟将欲行，</p>
<h2>忽闻岸上踏歌声。</h2>
<h2>桃花潭水深千尺，</h2>
<h2>不及汪伦送我情。</h2>
```

##### 普通兄弟选择器

两个元素有同一个父亲，而且第二个元素不需要紧跟第一个元素

```
p~h3{
	color: blue;
	font-family: "微软雅黑";
	font-size: 20px;
}
```

```
<h2>《赠汪伦》</h2>
<p>李白乘舟将欲行，</p>
<h2>忽闻岸上踏歌声。</h2>
<h2>桃花潭水深千尺，</h2>
<h3>不及汪伦送我情。</h3>
```



### 属性选择器

选择属性中，以某一个字符串进行选择

#### E[att^=value] 选择器

属性中以什么**开头**进行选择

```
p[id^="one"]{
			color: pink;
			font-family: "微软雅黑";
			font-size: 20px;
		}
```

```
<p id="one"> </p>
<p id="two"> </p>
<p id="one1"> </p>
<p id="two1"> </p>
```

#### E[att$=value] 选择器

属性中以什么**结束**进行选择

```
p[id$="main"]{
			color: #0cf;
			font-family: "黑体";
			font-size: 20px;
		}
```

```
<h2>文章2</h2>
<p id="old1"> </p>
<p id="old2"> </p>
<p id="oldmain"> </p>
<p id="newmain"> </p>
```

#### E[att^=value] 选择器

属性中是否**含有**进行选择

```
p[id*="demo"]{
			color: #0ca;
			font-family: "新宋体";
			font-size: 20px;
		}
```

```
<h2>文章3</h2>
<p id="demo1"> </p>
<p id="main1">  </p>
<p id="newdemo">  </p>
<p id="olddemo">  </p>
```



### 结构化伪类选择器

都是以“:”开头的选择器

#### :root 选择器

对页面所有元素适用

```
:root{
	font-size: 24px;
	font-family: "宋体";
	color: blue;
}
```

#### :not 选择器

想要排除某个结构元素下的子结构元素

```
body *:not(h3){
	color: orange;
	font-weight: bold;
	font-size: 20px;
}
```

#### :only-child

用于匹配某个父元素的唯一一个子元素

```
li:only-child{
	color: red;
}
```

```
<ul>
	<li>没错，就我一个</li>
</ul>
```

#### :first-child & :last-child 

用于匹配某个父元素中第一个子元素，或者最后一个子元素

```
p:first-child{
	color: purple;
}
p:last-child{
	color: orange;
}
```

#### :nth-child(n) & :nth-last-child(n)

选择父元素中正序第n个子元素，或者倒序第n个子元素

```
p:nth-child(2){
	color: green;
	font-style: oblique;
}
p:nth-last-child(2){
	color: red;
}
```

#### :nth-of-type() & :nth-last-of-type()

选择**特定**的第几个元素，第3个<p>标签

```
h2:nth-of-type(odd){
	color: red;
}
/*选择奇数标签<h2>*/

li:nth-of-type(even){
	color: black;
}
/*选择偶数标签<li>*/

p:nth-of-type(3){
	color: orange;
}
/*选择正序第3个<p>*/

p:nth-last-of-type(2){
	color: orange;
}
/*选择倒序第2个<p>*/
```

#### :empty 选择器

选择标签内文本为空的所有元素

```
div:empty{
	width: 100px;
	height: 20px;
	background: blue;
}
```

#### :target 选择器

当点击了页面中的链接（锚点链接）时，:target 选择器所设置的样式才会起作用

```
:target{
	background-color: #e4eecc;
}
```

```
<p><a href="#news1">跳转至内容 1</a></p>
.
.
.
<p id="news1"><b>内容 1...</b></p>
```

#### :before 选择器 & :after 选择器

:before 选择器，用于在被选择**内容前**插入内容

:after 选择器，用于在被选择**内容后**插入内容

**注意：必须搭配 content 属性来指定插入内容**

```
p:before{
	content: "十五的月亮";
	color:red;
	font-weight: bold;
}
p:after{
	content: url(moon.jpg);
}
```

```
<p>十六圆<br></p>
```

#### 链接伪类

| 超链接标记<a>的伪类 | 含义                                 |
| ------------------- | ------------------------------------ |
| a:link              | 选择所有未被访问的链接               |
| a:visited           | 选择所有已被访问的链接               |
| a:active            | 选择点击不动时的链接                 |
| a:hover             | 选择鼠标指针经过，悬停位于其上的链接 |

```
a:link,a:visited{
	color: #333;
	text-decoration: none;
}
a:hover{
	color: red;
	text-decoration: underline;
}
```



## CSS 选择器优先级

标记选择=1，类选择器=10，ID选择器=100，继承性权重=0，行内样式 >100，权重可叠加。



## CSS 盒子模型

### 盒子模型

所有HTML元素可以看作盒子，在CSS中，"box model"这一术语是用来设计和布局时使用。

CSS盒模型本质上是一个盒子，封装周围的HTML元素，它包括：边距，边框，填充，和实际内容。

下面的图片说明了盒子模型(Box Model)：

![CSS box-model](https://www.runoob.com/images/box-model.gif)

不同部分的说明：

- **Margin(外边距)** - 清除边框外的区域，外边距是透明的。
- **Border(边框)** - 围绕在内边距和内容外的边框。
- **Padding(内边距)** - 清除内容周围的区域，内边距是透明的。
- **Content(内容)** - 盒子的内容，显示文本和图像。



#### margin 的属性

控制元素外间距

```
margin: 0px 40px;
```

#### border 的属性

##### 边框样式：

| border-style 属性值 | 效果                                                  |
| ------------------- | ----------------------------------------------------- |
| none                | 默认无边框                                            |
| dotted              | 定义一个点线边框                                      |
| dashed              | 定义一个虚线边框                                      |
| solid               | 定义实线边框                                          |
| double              | 定义两个边框。 两个边框的宽度和 border-width 的值相同 |
| groove              | 定义3D沟槽边框。效果取决于边框的颜色值                |
| ridge               | 定义3D脊边框。效果取决于边框的颜色值                  |
| inset               | 定义一个3D的嵌入边框。效果取决于边框的颜色值          |
| outset              | 定义一个3D突出边框。 效果取决于边框的颜色值           |

##### 边框可单独设置：

单值为四边，两个值分别为“上下”和“左右”，三个值分别为“上”；“左右”和“下”，四个值分别为“上”；“右”；“下”；“左”

所有属性都可以设置在一起，不论顺序，空格连接



### div 标记

是一个区块容器标记，是盒子的主要应用



### 居中设置

- text-align:center（文字等内容居中）
- 另外一个为margin:0 auto；



## CSS 背景设置

### 背景颜色&图像

```
h1{
	background-color: white;
	background-image: url(images/biaoti_02.jpg);
}
```

### 背景图像透明度设置

```
img:nth-child(2){
	opacity:0.5;    /*图像半透明*/
}
```

### 背景图像平铺与位置

#### 图像的平铺用background-repeat 属性控制

- repeat：沿水平和竖直两个方向平铺（默认值）
- no-repeat：不平铺（图像位于元素左上角，只显示一个）
- repent-x：只沿水平方向平铺
- repent-y：只沿竖直方向平铺

```
div{
	background-repeat: no-repeat;	/*取消图片重复平铺*/
}	
```

#### 图像的位置设置用background-position 属性控制

- 水平方向值：left，center，right
- 垂直方向值：top，center，bottom
- 也可以使用百分比进行设置

```
div{
	background-position: right,center;
}
```

### 背景图像固定方式与大小

#### background-size 属性

```
background-size: auto 48px;
```

### 背景图像的显示区域

#### background-origin 属性

可以选择从盒子的什么地方开始平铺，例如选择从盒子的 Content(内容) 开始平铺

- padding-box：背景图像相对内边距区域定位
- border-box：背景图像相对边框定位
- content-box：背景图像相对内容框定位

### 多背景的叠加和图层

直接设置，确定好图层的顺序

```
div{
	background-image: url(images/taiyang.png),url(images/caodi.png),url(images/tiankong.png);
	background-position: right top,bottom,center;
}
```



## CSS 渐变

### 线性渐变 linear-gradient 属性

```
.one{
		background-image: linear-gradient(30deg,green,blue);/*设置线性渐变*/
	}
```

![TIM截图20200503100648](E:\ZYC project\markdown works\学习笔记\CSS3学习笔记 img\TIM截图20200503100648.png)

### 径向渐变 radial-gradient 属性

```
.two{
	border-radius: 50%;/*设置圆角边框*/
	background-image: radial-gradient(ellipse at center,#0f0,#030);
}
```

![TIM截图20200503100821](E:\ZYC project\markdown works\学习笔记\CSS3学习笔记 img\TIM截图20200503100821.png)

### 重复渐变

分为重复的**线性渐变**和**径向渐变**两种

#### 重复的线性渐变 repeating-linear-gradient

```
.three{
		background-image: repeating-linear-gradient(90deg,#E50743,#E8ED30 10%,#3FA62E 15%);
	}
```

![TIM截图20200503100831](E:\ZYC project\markdown works\学习笔记\CSS3学习笔记 img\TIM截图20200503100831.png)

#### 重复的径向渐变 repeating-radial-gradient

```
.four{
		border-radius: 50%;
		background-image:repeating-radial-gradient(circle at 50% 50%,#E50743,#E8ED30 10%,#3FA62E 15%);
	}
```

![TIM截图20200503100838](E:\ZYC project\markdown works\学习笔记\CSS3学习笔记 img\TIM截图20200503100838.png)

[CSS3 渐变详细介绍](https://www.runoob.com/css3/css3-gradients.html)



## 浮动与定位

### 什么是 CSS Float（浮动）？

CSS 的 Float（浮动），会使元素向左或向右移动，其周围的元素也会重新排列。

### 元素怎样浮动

元素的水平方向浮动，意味着元素只能左右移动而不能上下移动。

一个浮动元素会尽量向左或向右移动，直到它的外边缘碰到包含框或另一个浮动框的边框为止。

浮动元素之后的元素将围绕它。

浮动元素之前的元素将不会受到影响。

如果图像是右浮动，下面的文本流将环绕在它左边。

### 设置浮动

```
.son1{
	background-color:#00FF00;
	float: left;
	/* 这里设置son1的浮动方式：左浮动*/
}
```



### 清除浮动

1. clear 清除法，设置空标记来清除father1的浮动影响

   ```
   .clear1{
   	clear: both;
   }	
   ```

   ```
   <div class="father1">
     <div class="son1">Box-1</div>
     <div class="son2">Box-2</div>
     <div class="son3">Box-3 <br/>
       Box-3<br/>
       Box-3<br/>
       Box-3<br/>
    </div>
   	<div class="clear1"></div>
   ```

   

2. 利用设置父标记的**overflow属性**来清除浮动影响

   ```
   .father2{
   	overflow: hidden;
   }
   ```

   **overflow属性**

   用来对超出盒子范围的内容进行设置，例如内容超出会出现滚动条

   ```
   div {
   	width: 300px;
   	height: 200px;
   	border: 3px solid #F9C;
   	overflow: auto;		/* 这里设置**overflow属性为内容超出会出现滚动条*/
   	background-image: url(timg2.jpg);
   	background-repeat: no-repeat;
   	line-height: 30px;
   	padding: 10px;
   	font-family: "微软雅黑";
   }
   ```

   

3. 利用**after伪对象**来清除father3的浮动影响（**常用**）

   ```
   .father3:after{
   	content: "" ;
   	display: block;
   	clear: both;
   	visibility: hidden;
   	height: 0px;
   }
   ```



### CSS 的定位

position 属性指定了元素的定位类型

- absolute
- relative
- static
- fixed
- sticky



#### 相对定位 relative

相对**正常的位置**进行定位，**会保留**原位置不被占用

```
div{
    position:relative;
    left:-20px;
    top:10px;
}
```



#### 绝对定位 absolute

相对最近的**被定义的父元素**，**不会保留**原位置，会被占用

```
h2{
    position:absolute;
    left:100px;
    top:150px;
}
```

**注意：如果要绝对定位子元素，父元素必须要增加一个相对定位的属性**

#### fixed 定位

元素的位置相对于浏览器窗口是固定位置，即使窗口是滚动的它也不会移动

```
p.pos_fixed{
    position:fixed;
    top:30px;
    right:5px;
}
```

#### 粘性定位 sticky

sticky 英文字面意思是粘，粘贴，所以可以把它称之为粘性定位。

**position: sticky;** 基于用户的滚动位置来定位。

粘性定位的元素是依赖于用户的滚动，在 **position:relative** 与 **position:fixed** 定位之间切换。

它的行为就像 **position:relative;** 而当页面滚动超出目标区域时，它的表现就像 **position:fixed;**，它会固定在目标位置。

元素定位表现为在跨越特定阈值前为相对定位，之后为固定定位。

这个特定阈值指的是 top, right, bottom 或 left 之一，换言之，指定 top, right, bottom 或 left 四个阈值其中之一，才可使粘性定位生效。否则其行为与相对定位相同。

**注意:** Internet Explorer, Edge 15 及更早 IE 版本不支持 sticky 定位。 Safari 需要使用 -webkit- prefix (查看以下实例)。

```
div.sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
  padding: 5px;
  background-color: #cae8ca;
  border: 2px solid #4CAF50;
}
```

```
<p>尝试滚动页面。</p>
<p>注意: IE/Edge 15 及更早 IE 版本不支持 sticky 属性。</p>

<div class="sticky">我是粘性定位!</div>

<div style="padding-bottom:2000px">
  <p>滚动我</p>
  <p>来回滚动我</p>
  <p>滚动我</p>
  <p>来回滚动我</p>
  <p>滚动我</p>
  <p>来回滚动我</p>
</div>
```

#### z-index 层叠等级

**注意：z-index 的属性值越大，越在上面一层**

```
.block1{
	width: 45px;
	height: 15px;
	text-align: center;
	background-color:#fff0ac;
	position: relative;
	z-index: 3;		/*这里表示由下往上第四层*/
}
.block2{
	width: 45px;
	height: 15px;
	text-align: center;
	background-color:#ffc24c;
	position: relative;
	left: 25px;
	top: -10px;
	z-index: 1;		/*这里表示由下往上第二层*/
}
.block3{
	width: 45px;
	height: 15px;
	text-align: center;
	background-color:#c7ff9d;
	position: relative;
	left: 50px;
	top: -20px;
	z-index: 0;		/*这里表示由下往上第一层*/
}
```

#### 元素类型 & span 标记

| 元素类型 | 常见元素                                       | 特点                                                         |
| :------- | ---------------------------------------------- | ------------------------------------------------------------ |
| 块元素   | <h1><h6>，<p>，<div>，<ul>，<ol>，<li>等       | 以区域块的形式出现，可以设置**宽度**，**高度**，**对齐**等属性 |
| 行内元素 | <strong>，<b>，<em>，<i>，<del>，<a>，<span>等 | 也称内联元素或内嵌元素，特点是不必在新的一行开始，也不强迫其他元素在新的一行显示。它们不占有独立的区域，仅靠自身的字体大小，和图像尺寸来支撑。一般**不可以**设置宽度，高度，对齐等属性 |

##### 元素的转换

- inline：转换为行内元素
- block：转换为块级元素
- inline-block：此元素显示为行内块元素，可以设置宽高和对齐等属性，但不会独占一行
- none：此元素将被隐藏，不显示，与不占用页面空间，相当于元素不存在

```
div:hover a {
	display: block;
}

li{
display: inline-block;
}
```

