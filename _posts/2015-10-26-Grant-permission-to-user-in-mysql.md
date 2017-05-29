---
layout: post
title: "How to create user and grant privileges in mysql"
description: "This blog instructs about how to create user and grant different privileges to different users in mysql."
date: 2015-10-26
tags: [mysql]
---

This blog instructs about how to create user and grant different privileges to different users in mysql.

#### 1. How to create a user in mysql
First you should login mysql server with root account or other account with grant options

```zsh
$ mysql -uroot -p
```

Show existed accounts:
![show accounts](/images/5F32B806-C381-4F9C-AB9B-252949C325EC.png)

```mysql
mysql> SELECT Host, User FROM mysql.user;
```

create a new account call "timlen" and it can only connect to server at localhost

```mysql
mysql> CREATE USER 'timlen'@'localhost' IDENTIFIED BY 'foo';
```

Now we can see the new account existed in table `mysql.user`

If you want to connect mysql server remotely, just run the following command:

```mysql
mysql> CREATE USER 'timlen'@'%' IDENTIDIED BY 'foo';
```

#### 2. Grant privileges to the new account
We can grant account 'timlen' with all privileges, which means it can perform `select, delete, update, create ...` on target table and the sql as the following:

```mysql
mysql> GRANT ALL PRIVILEGES ON *.* TO 'timlen'@'localhost' WITH GRANT OPTION;
```

Flush privileges and show the grants for `timlen`

```mysql
mysql> FLUSH PRIVILEGES;
mysql> SHOW GRANTS for 'timlen'@'localhost';
```

![show grant](/images/CF730E2D-5C45-423F-9A1D-9366B5FCB828.png)

#### 3. How to grant readonly permission to a new user
Mysql offer many options for us to set different privileges for different users and we can easily do this:
remove the `all privileges` that we have just granted for `timlen`

```mysql
mysql> REVOKE ALL PRIVILEGES  ON *.* FROM 'timlen'@'localhost';
mysql> FLUSH PRIVILEGES;
```

Now we can grant `select` to `timlen` on all databases

```mysql
mysql> GRANT SELECT ON *.* TO 'timlen'@'localhost';
mysql> FLUSH PRIVILEGES;
```

#### 4. Other privileges are as following

```shell
| Privilege          | Meaning                                                                                                                                                                       |
|--------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| ALL PRIVILEGES     | Sets all simple privileges except GRANT OPTION                                                                                                                                |
| ALTER              | Enables use of ALTER TABLE                                                                                                                                                    |
| CREATE             | Enables use of CREATE TABLE                                                                                                                                                   |
| CREATE TEMPORARY TABLES              | Enables use of CREATE TEMPORARY TABLE                                                                                                                        |
| DELETE             | Enables use of DELETE                                                                                                                                                         |
| DROP               | Enables use of DROP TABLE                                                                                                                                                     |
| EXECUTE            | Not implemented                                                                                                                                                               |
| FILE               | Enables use of SELECT ... INTO OUTFILE and LOAD DATA INFILE                                                                                                                   |
| INDEX              | Enables use of CREATE INDEX and DROP INDEX                                                                                                                                    |
| INSERT             | Enables use of INSERT                                                                                                                                                         |
| LOCK TABLES        | Enables use of LOCK TABLES on tables for which you have the SELECT privilege                                                                                                  |
| PROCESS            | Enables the user to see all processes with SHOW PROCESSLIST                                                                                                                   |
| REFERENCES         | Not implemented                                                                                                                                                               |
| RELOAD             | Enables use of FLUSH                                                                                                                                                          |
| REPLICATION CLIENT | Enables the user to ask where slave or master servers are                                                                                                                     |
| REPLICATION SLAVE  | Needed for replication slaves (to read binary log events from the master)                                                                                                     |
| SELECT             | Enables use of SELECT                                                                                                                                                         |
| SHOW DATABASES     | shows all databases                                                                                                                                                           |
| SHUTDOWN           | Enables use of MySQLadmin shutdown                                                                                                                                            |
| SUPER              | Enables use of CHANGE MASTER, KILL, PURGE MASTER LOGS and SET GLOBAL statements.|
| UPDATE             | Enables use of UPDATE                                                                                                                                                         |
| USAGE              | Synonym for privileges                                                                                                                                                        |
| GRANT OPTION       | Enables privileges to be granted                                                                                                                                              |
```