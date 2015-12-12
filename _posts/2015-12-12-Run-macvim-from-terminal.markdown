---
layout: post
title: Run macvim from terminal
---

MacVim is a wonderful tool in mac. It is a port of the text editor Vim to Mac OS X that is meant to look better and integrate more seamlessly with the Mac than the older Carbon port of Vim. This article show you how to run macvim from terminal.

#### Confusing about opening a gui windows when run 'mvim'?
After you had your macvim installed and try to run the folllowing command: 
{% highlight bash %}
$ mvim
{% endhighlight%}
A new gui window will be opened instead of just staying on terminal.

#### Try to reinstall your macvim with option `--with-override-system-vim`
{% highlight bash %}
$ brew install macvim --with-override-system-vim
{% endhighlight%}
it will create `vi` and `vim` command under `/usr/local/bin/`
and if you want to open a file from terminal using macvim, just type 'vi file' or 'vim file '. These two commands acted as `mvim -v`. That's all.
#### Don't want to reinstall macvim?
If you don't want to reinstall your macvim, making a alias is a recommend way. And a line to you `bashrc` or `zshrc`.
{% highlight bash %}
$ alias vi='mvim -v'
{% endhighlight%}
