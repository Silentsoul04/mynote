# HTML5学习笔记

## HTML 简介

HTML 是用来描述网页的一种语言

- HTML 指的是超文本标记语言: **H**yper**T**ext **M**arkup **L**anguage
- HTML 不是一种编程语言，而是一种**标记**语言
- 标记语言是一套**标记标签** (markup tag)
- HTML 使用标记标签来**描述**网页
- HTML 文档包含了HTML **标签**及**文本**内容
- HTML文档也叫做 **web 页面**

## HTML 基本文档

```html
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>我是标题</title>
</head>

<body>
<!-- 我是一个注释 -->
<h1></h1>
<p></p>
可见文本……
</body>
</html>
```



## HTML 元素

标题：

```
<h1>这是标题</h1>
```

段落：

```
<p>这是段落</p>
```

图片：

```
<img src="xxxx.jpg">
```

链接：

```
<a href="https://www.bilibili.com/">
```

水平线：

```
<hr />
```

换行：

```
<br>
```



## HTML 文本格式化

### 文本格式化标签

| 标签     | 描述         |
| -------- | ------------ |
| <b>      | 定义粗体文本 |
| <em>     | 定义着重文字 |
| <i>      | 定义斜体字   |
| <small>  | 定义小号字   |
| <strong> | 定义加重语气 |
| <sub>    | 定义下标字   |
| <sup>    | 定义上标字   |
| <ins>    | 定义插入字   |
| <del>    | 定义删除字   |



### "计算机输出" 标签

| 标签   | 描述               |
| :----- | :----------------- |
| <code> | 定义计算机代码     |
| <kbd>  | 定义键盘码         |
| <samp> | 定义计算机代码样本 |
| <var>  | 定义变量           |
| <pre>  | 定义预格式文本     |



### 引文, 引用, 及标签定义

| 标签         | 描述               |
| :----------- | :----------------- |
| <abbr>       | 定义缩写           |
| <address>    | 定义地址           |
| <bdo>        | 定义文字方向       |
| <blockquote> | 定义长的引用       |
| <q>          | 定义短的引用语     |
| <cite>       | 定义引用、引证     |
| <dfn>        | 定义一个定义项目。 |

## HTML 链接

### 超链接

HTML使用标签 <a>来设置超文本链接。

超链接可以是一个字，一个词，或者一组词，也可以是一幅图像，您可以点击这些内容来跳转到新的文档或者当前文档中的某个部分

类似这样：

```
<a href="url">链接文本</a>
```

### 锚点链接

使用id属性，来快速跳转到当前页面的某一位置

链接：

```
<a href="#tips">访问有用的提示部分</a>
```

“#”+需要定位的ID

定位ID：

```
<a id="tips">有用的提示部分</a>
```



## HTML 表格

表格由 **<table>** 标签来定义。每个表格均有若干行（由 **<tr>** 标签定义），每行被分割为若干单元格（由 <td> 标签定义）。字母 td 指表格数据（table data），即数据单元格的内容。数据单元格可以包含文本、图片、列表、段落、表单、水平线、表格等等。

```html
<table border="1">
    <tr>
        <td>row 1, cell 1</td>
        <td>row 1, cell 2</td>
    </tr>
    <tr>
        <td>row 2, cell 1</td>
        <td>row 2, cell 2</td>
    </tr>
</table>
```

在浏览器显示如下：

![img](https://www.runoob.com/wp-content/uploads/2013/07/4AEE0F4B-669C-4BBC-BEC4-6953E1B0E278.jpg)

### 表格标签

| 标签       | 描述                 |
| :--------- | :------------------- |
| <table>    | 定义表格             |
| <th>       | 定义表格的表头       |
| <tr>       | 定义表格的行         |
| <td>       | 定义表格单元         |
| <caption>  | 定义表格标题         |
| <colgroup> | 定义表格列的组       |
| <col>      | 定义用于表格列的属性 |
| <thead>    | 定义表格的页眉       |
| <tbody>    | 定义表格的主体       |
| <tfoot>    | 定义表格的页脚       |

## HTML 列表

### 无序列表

无序列表使用 <ul><li> 标签

```
<ul>
<li>Coffee</li>
<li>Milk</li>
</ul>
```

浏览器显示如下：

> - Coffee
> - Milk

### 有序列表

有序列表始于 <ol> <li>标签

```
<ol>
<li>Coffee</li>
<li>Milk</li>
</ol>
```

浏览器中显示如下：

> 1. Coffee
> 2. Milk

### 自定义列表

自定义列表以 <dl> 标签开始。每个自定义列表项以 <dt> 开始。每个自定义列表项的定义以 <dd> 开始

```
<dl>
<dt>Coffee</dt>
<dd>- black hot drink</dd>
<dt>Milk</dt>
<dd>- white cold drink</dd>
</dl>
```

浏览器显示如下：

> Coffee
>
> \- black hot drink
>
> Milk
>
> \- white cold drink

## HTML 表单

表单是一个包含表单元素的区域

表单元素是允许用户在表单中输入内容,比如：文本域(textarea)、下拉列表、单选框(radio-buttons)、复选框(checkboxes)等等

表单使用表单标签 <form> 来设置

```
<form>
.
input 元素
.
</form>
```

### input 标签

定义一个输入域

```
<input type="text" name="usern" required>
```

#### type类型

| type类型 | 描述                                                     |
| -------- | -------------------------------------------------------- |
| text     | 定义一个单行的文本字段（默认宽度为 20 个字符）           |
| password | 定义密码字段（字段中的字符会被遮蔽）                     |
| hidden   | 定义隐藏输入字段                                         |
| date     | 包括年、月、日，不包括时间                               |
| datetime | 包括年、月、日、时、分、秒                               |
| email    | 定义 e-mail 地址的字段                                   |
| file     | 定义文件选择字段和 "浏览..." 按钮，供文件上传            |
| number   | 定义用于输入数字的字段                                   |
| radio    | 定义单选按钮                                             |
| checkbox | 定义复选框                                               |
| submit   | 定义提交按钮                                             |
| image    | 定义图像作为提交按钮                                     |
| reset    | 定义重置按钮（重置所有的表单值为默认值）                 |
| url      | 定义用于输入 URL 的字段                                  |
| color    | 定义拾色器，选择颜色                                     |
| button   | 定义可点击的按钮（通常与 JavaScript 一起使用来启动脚本） |
| tel      | 定义用于输入电话号码的字段                               |

#### 常见属性

- name：定义标签值的名字

  ```
  name="shenfenzheng"
  ```

- autofocus：自动获得光标焦点，方便输入关键词

  ```
  autofocus="ture"
  ```

- required：输入验证，检查是否输入

  ```
  required
  ```

- multiple：指定输入框可以选择多个值，适用于**email**和**file**类型，也可用于**select标签**

  ```
  multiple
  ```

- pattern：用于验证input 类型输入框中，用户输入的内容是否与**所定义的正则表达式**相匹配，使用于 **type=text，search，url，email 和 password** 的 <inpute> 标记

  ```
  pattern="^\d{15}|\d{18}$"
  <!--(检查用户输入的是否为15位、18位数字)-->
  ```

- placeholder：输入框显示一段文本用于提示用户，当用户输入了内容后自动清空，适用于 type=text，search，tel，url，email 以及 password 的<input>标记

  ```
  placeholder="请输入用户名"
  ```
  
- value： input 元素设定值

  对于不同的输入类型，value 属性的用法也不同：

  - type="button", "reset", "submit" - 定义按钮上的显示的文本
  - type="text", "password", "hidden" - 定义输入字段的初始值
  - type="checkbox", "radio", "image" - 定义与输入相关联的值

  **注释：**<input type="checkbox"> 和 <input type="radio"> 中必须设置 value 属性。

  **注释：**value 属性无法与 <input type="file"> 一同使用。

### textarea 标签

定义一个多行的文本域

```
<textarea cols="40" rows="5" name="jieshao">请输入您的个人介绍</textarea>
```

#### textarea 可选属性

- name：用户定义控件的名称
- readonly：控件内容为只读
- disabled：第一次加载时禁用（显示为灰色）

### 表单标签

常见标签：

- input：定义输入域
- datalist：指定一个预先定义的输入控件选项列表
- keygen：定义了表单的密钥对生成器字段
- output：定义一个计算结果
- textarea：定义一个多行的文本域



| 标签       | 描述                                         |
| ---------- | -------------------------------------------- |
| <input>    | 定义输入域                                   |
| <textarea> | 定义文本域 (一个多行的输入控件)              |
| <label>    | 定义了 <input> 元素的标签，一般为输入标题    |
| <fieldset> | 定义了一组相关的表单元素，并使用外框包含起来 |
| <legend>   | 定义了 <fieldset> 元素的标题                 |
| <select>   | 定义了下拉选项列表                           |
| <optgroup> | 定义选项组                                   |
| <option>   | 定义下拉列表中的选项                         |
| <button>   | 定义一个点击按钮                             |
| <datalist> | 指定一个预先定义的输入控件选项列表           |
| <keygen>   | 定义了表单的密钥对生成器字段                 |
| <output>   | 定义一个计算结果                             |

HTML  区块元素&内联元素

### 区块元素

大多数 HTML 元素被定义为**块级元素**或**内联元素**。

块级元素在浏览器显示时，通常会以新行来开始（和结束）。

实例: <h1>, <p>, <ul>, <table>

### 内联元素

内联元素在显示时通常不会以新行开始。

实例: <b>, <td>, <a>, <img>



