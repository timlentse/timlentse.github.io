---
layout: post
title: "How to create user and grant privileges in mysql"
---

This blog instructs about how to create user and grant different privileges to different users in mysql.

### How to create a user in mysql
First you should login mysql server with root account or other account with grant options
{% highlight bash %}
$ mysql -uroot -p
{% endhighlight %}
Show existed accounts:
![show accounts](/img/5F32B806-C381-4F9C-AB9B-252949C325EC.png)
{% highlight bash %}
mysql> SELECT Host, User FROM mysql.user;
{% endhighlight %}

create a new account call "timlen" and it can only connect to server at localhost

{% highlight bash %}
mysql> CREATE USER 'timlen'@'localhost' IDENTIFIED BY 'foo';
{% endhighlight %}

Now we can see the new account existed in table `mysql.user`

If you want to connect mysql server remotely, just run the following command:
{% highlight bash %}
mysql> CREATE USER 'timlen'@'%' IDENTIDIED BY 'foo';
{% endhighlight %}

### Grant privileges to the new account
We can grant account 'timlen' with all privileges, which means it can perform `select, delete, update, create ...` on target table and the sql as the following:
{% highlight bash %}
mysql> GRANT ALL PRIVILEGES ON *.* TO 'timlen'@'localhost' WITH GRANT OPTION;
{% endhighlight %}

Flush privileges and show the grants for `timlen`
{% highlight bash %}
mysql> FLUSH PRIVILEGES;
mysql> SHOW GRANTS for 'timlen'@'localhost';
{% endhighlight %}
![show grant](/img/CF730E2D-5C45-423F-9A1D-9366B5FCB828.png)

### How to grant readonly permission to new user
Mysql offer many options for us to set different privileges for different users and we can easily do this:
remove the `all privileges` that we have just granted for `timlen`
{% highlight bash %}
mysql> REVOKE ALL PRIVILEGES  ON *.* FROM 'timlen'@'localhost';
mysql> FLUSH PRIVILEGES;
{% endhighlight %}
Now we can grant `select` to `timlen` 
{% highlight bash %}
mysql> GRANT SELECT ON *.* TO 'timlen'@'localhost';
{% endhighlight %}
### Other privileges are as following

|Privilege| Meaning|

ALL PRIVILEGES --- Sets all simple privileges except GRANT OPTION

ALTER --- Enables use of ALTER TABLE

CREATE  --- Enables use of CREATE TABLE

CREATE --- TEMPORARY TABLES Enables use of CREATE TEMPORARY TABLE

DELETE  --- Enables use of DELETE

DROP  --- Enables use of DROP TABLE

EXECUTE --- Not implemented

FILE  --- Enables use of SELECT ... INTO OUTFILE and LOAD DATA INFILE

INDEX --- Enables use of CREATE INDEX and DROP INDEX

INSERT  --- Enables use of INSERT

LOCK TABLES --- Enables use of LOCK TABLES on tables for which you have the SELECT privilege

PROCESS --- Enables the user to see all processes with SHOW PROCESSLIST

REFERENCES --- Not implemented

RELOAD  --- Enables use of FLUSH

REPLICATION CLIENT  --- Enables the user to ask where slave or master servers are

REPLICATION SLAVE --- Needed for replication slaves (to read binary log events from the master)

SELECT  --- Enables use of SELECT

SHOW DATABASES --- shows all databases

SHUTDOWN  --- Enables use of MySQLadmin shutdown

SUPER --- Enables use of CHANGE MASTER, KILL, PURGE MASTER LOGS, and SET GLOBAL statements, the MySQLadmin debug command; allows you to connect (once) even if max_connections is reached

UPDATE  --- Enables use of UPDATE

USAGE --- Synonym for privileges

GRANT OPTION  --- Enables privileges to be granted

