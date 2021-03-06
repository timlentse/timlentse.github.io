---
layout: post
title: "Change gem sources"
date: 2015-02-05 14:15
description: "简单介绍修改ruby gems 下载源的方法"
tags: [rubygems]
---

简单介绍修改ruby gems 下载源的方法

#### 为什么需要换ruby gems 安装源
在国内网络环境中安装gems经常会失败，这是因为rubygems.org 中的资源文件是存放在 Amazon S3 上面的,
很多时候，在安装gem的过程中会出现资源文件间歇性连接失败，为了避免这些问题，我们需要从另外一个gem服务器下载安装。
通过gem sources命令配置源，或通过修改Gemfile中的source语句可以实现。

#### 常用的源
* 官方下载源：https://rubygems.org/
* ruby-china镜像下载源：https://gems.ruby-china.org
这是一个国内最权威的gems下载源，你可以用此代替官方版本，同步频率目前为15分钟一次以保证尽量与官方服务同步。

* 显示当前使用的sources  

```shell 
gem sources    
```

* 添加一个source    

```shell 
gem sources -a #{url} 

```

* 删除一个source 

```shell 
gem sources -r #{url}      
```

* 更新source cache  

```shell 
gem sources -u
```
 
* 从固定地址安装gem  

```shell 
gem install bundler -p https://gems.ruby-china.org
```
