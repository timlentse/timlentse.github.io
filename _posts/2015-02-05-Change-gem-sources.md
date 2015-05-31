---
layout: post
title: "Change gem sources"
date: 2015-02-05 14:15
---
简单介绍修改ruby gems 下载源的方法

####<b>为什么需要换ruby gems 安装源</b>
在国内网络环境中安装gems经常会失败，这是因为rubygems.org 中的资源文件是存放在 Amazon S3 上面的,
很多时候，在安装gem的过程中会出现资源文件间歇性连接失败，为了避免这些问题，我们需要从另外一个gem服务器下载安装。
通过gem sources命令配置源，或通过修改Gemfile中的source语句可以实现。

####<b>常用的源</b>
https://rubygems.org/ 官方下载源
https://ruby.taobao.org 淘宝的下载源，这是一个完整 rubygems.org 镜像，
你可以用此代替官方版本，同步频率目前为15分钟一次以保证尽量与官方服务同步。

显示当前使用的sources       
{% highlight ruby %}  
  gem sources    
{% endhighlight %}   
添加一个source    
{% highlight ruby %} 
  gem sources -a #{url}    
{% endhighlight %}  
删除一个source    
{% highlight ruby %}   
  gem sources -r #{url}      
{% endhighlight %}

更新source cache  
{% highlight ruby %} 
  gem sources -u
{% endhighlight %} 

从固定地址安装gem   
{% highlight ruby %} 
  gem install bundler -p https://ruby.taobao.org/
{% endhighlight %}  