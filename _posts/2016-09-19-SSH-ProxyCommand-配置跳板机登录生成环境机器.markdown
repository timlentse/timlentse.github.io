---
layout: post
title: "使用SSH ProxyCommand 配置跳板机登录生产环境机器"
date: 2016-09-19
tags: [ssh]
---

在公司开发中，为了安全起见，生产环境跟开发环境是相互隔离开来的。也就是说在开发环境网络中无法直接ssh登录到生产环境的机器，
如果需要登录生产环境的机器，通常会需要借助跳板机，先登录到跳板机，然后通过跳板机登录到生产环境。

* 大致的过程如下面的图示：

```shell
+-------------+       +----------+      +--------------+
| 开发环境机器  | <---> |   跳板机  | <--> | 生产环境机器   |
+-------------+       +----------+      +--------------+
```

* 换成shell 命令就是

```shell

$ ssh foo@jumphost -----> ssh bar@production_host

```
* 两步操作变一步操作

正如上面说到的，如果你需要登录生产环境的机器，你需要输入两个命令
。那能不能一个命令就搞定呢？答案是肯定的。

```shell

$ ssh -tt foo@jumphost ssh -tt bar@production_host

```
* 借助` ProxyCommand` 把配置写到config文件里面

如果我们把配置写到 ~/.ssh/config 文件里面，这样就更加减轻我们敲键盘的工作了

在 `~/.ssh/config` 文件里面加上：

```vim

Host production
hostname 192.168.1.100
user bar
ProxyCommand ssh foo@jumphost -W %h:%p

```
加入之后，直接执行 `ssh producton` 就可以登录到生产环境。
