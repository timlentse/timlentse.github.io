---
layout: post
title: "使用df, du查看磁盘空间使用情况的区别"
date: "2016-06-30"
---
剖析使用df,du查看磁盘空间使用情况的区别。

### 1. Why this blog
最近在工作中遇到一个问题，就是公司线上服务器磁盘空间满了，导致nginx无法继续写入日志。于是尝试手动清空一些不需要的文件以腾出磁盘空间，后来发现事情远远不是这么简单，其他不需要的文件和历史日志文件加起来不过3-4G左右，不可能是导致磁盘满的罪魁祸首，于是想到 `du` 来查看究竟是那些文件占满了磁盘。

### 2. 奇怪的 `du` 跟 `df`

使用 `du` 查看根目录下每一个文件夹占用的空间
{% highlight shell %}
$ du / --max-depth=1 -h
{% endhighlight %}

结果输出:

{% highlight shell %}
0    /sys
806M    /var
20K    /data
8.0K    /opt
7.6M    /bin
42M    /boot
26M    /etc
12K    /tmp
.......
.......
3.5G    /home
4.0K    /media
172K    /dev

4.0K    /selinux
1.3G    /usr
4.0K    /srv
21M    /lib64
14M    /sbin
6.4G    /
{% endhighlight %}

使用 `df` 查看磁盘剩余情况
{% highlight shell %}
$ df -h
{% endhighlight %}

结果是：
{% highlight shell %}
Filesystem            Size  Used Avail Use% Mounted on
/dev/vda2              40G  39.7G 300M  100% /
tmpfs                 1.9G     0  1.9G   0% /dev/shm
/dev/vda1             504M   58M  421M  13% /boot
/dev/vda5             1.5G   35M  1.4G   3% /data
{% endhighlight %}

使用 `du` 显示总共使用了 6.4G ,但是 `df` 显示却是磁盘已经满了,为什么会显示不同的结果？Google 一下 在[这里](http://askubuntu.com/questions/280342/why-do-df-and-du-commands-show-different-disk-usage)找到答案。

### 3. `df` 跟 `du` 的区别
* df 报告整个文件系统的使用情况，针对的是磁盘
* du 计算文件，文件夹的占用的空间大小

那么为什么 `df` 跟 `du /` 相差这么大呢？

原因在于不正当删除文件。当删除一个正在被其他进程使用的文件，其实这个文件描述符并没有被系统释放，也就是说这个文件占用的磁盘空间并没有被释放，而且进程仍然会不断往这个文件里面写入东西，导致占用的磁盘空间会越来越大，从而占满这个磁盘。但是由于这个文件的硬链接被删除了，`du` 是没法统计到这类型文件的大小的(因为没法找到文件名)，从而导致上面的诡异事件。

### 4. 列出被删除但是仍然被使用的文件

{% highlight shell %}
$ lsof | grep '(deleted)'
{% endhighlight %}

结果我的机器显示一大堆：
{% highlight shell %}
ruby       5132        work    1w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby       5132        work    2w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby       5132        work   10w      REG              252,2  132784015     655397 /home/work/rails_project/shared/log/production.log (deleted)
ruby      14597        work    1w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      14597        work    2w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      14597        work   10w      REG              252,2  132784015     655397 /home/work/rails_project/shared/log/production.log (deleted)
ruby      14646        work    1w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      14646        work    2w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      14646        work   10w      REG              252,2  132784015     655397 /home/work/rails_project/shared/log/production.log (deleted)
ruby      15367        work    1w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      15367        work    2w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      15367        work   10w      REG              252,2  132784015     655397 /home/work/rails_project/shared/log/production.log (deleted)
ruby      15530        work    1w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      15530        work    2w      REG              252,2    2617890     655377 /home/work/rails_project/shared/log/unicorn.log (deleted)
ruby      15530        work   10w      REG              252,2  132784015     655397 /home/work/rails_project/shared/log/production.log (deleted)
{% endhighlight %}

上面显示很多虽然被删除但仍然被进程使用的文件

如果需要释放这些文件，需要把进程上面的进程关闭，如：
{% highlight shell %}
$ kill -9 5132
{% endhighlight %}

或者使用awk把所有相关进程关闭
{% highlight shell %}
lsof | grep '(deleted)' | awk '{print $2}' | xargs kill -9
{% endhighlight %}
