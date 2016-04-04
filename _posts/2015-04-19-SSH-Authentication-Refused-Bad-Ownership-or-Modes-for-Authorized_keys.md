---
layout: post
title: "Bad ownership or modes for file authorized_keys"
date: 2015-04-19 10:02
---

It is a good thing to set ssh keys for the remote host. We can login a remote host via 
public key we have set so that no need to enter password every time we try to connect remote
server.

## How to set a ssh-key
If you google for a bit , you will find some many tutorials about how to set ssh keys.
A good article about that is [here](https://www.digitalocean.com/community/tutorials/how-to-set-up-ssh-keys--2).


## Still need to enter password even though ssh-key was set?

In some case, though ssh-keys was properly set, we still need enter password as if ssh-key not existed.
SSH has a verbose mode , just add the `-v` option.

![alt](/img/screenshot/ssh_keys_error/verbose-ssh.png)

The above way seems told nothing, but `cat /var/log/secure` will give more useful information.
In the terminal of remote host, type :

{% highlight ruby %}
sudo cat /var/log/secure  
{% endhighlight %}

we found something as the following:

{% highlight ruby %}
Apr 19 09:54:19 iZ94z0jz2inZ sshd[5704]: Authentication refused: bad ownership or modes 
for file /home/test/.ssh/authorized_keys
{% endhighlight %}

For security reason, the mode of files under .ssh directorys is strictly set. SSH doesn’t like it if your home or ~/.ssh directories
or ~./ssh/authorized_keys have group write permissions. Your home directory should be writable only by you, ~/.ssh should be 700, 
and authorized_keys should be 600.

The following shot is the wrong mode:
![alt](/img/screenshot/ssh_keys_error/wrong_mode.png)   
we can see that authorized_keys file has writable permissions for group, which is not allowed by ssh:

Change to right mode:

![alt](/img/screenshot/ssh_keys_error/right_mode.png)

Now you can login you remote host without entering  passwd, enjoy!



