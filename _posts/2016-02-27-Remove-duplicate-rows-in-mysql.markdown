---
layout: post
title: "Remove duplicate rows from a table in mysql"
date: 2016-02-27 17:00
---

This is a simple sql for removing duplicate rows from a table and just keep only one having the lowest `id`

{% highlight bash %}

DELETE r1 FROM table_name r1, table_name r2 WHERE r1.id > r2.id AND r1.name = r2.name
{% endhighlight %}

Note that the `name` is the field name you want to ensure values are unique

### Constraint unique

If you want to remove duplicate rows decided by more than one column

{% highlight bash %}

DELETE r1 FROM table_name r1, table_name r2 WHERE r1.id > r2.id AND r1.name1 = r2.name1 
and r1.name2=r2.name2 and ...

{% endhighlight %}

### Reference 
* [stackoverflow](http://stackoverflow.com/questions/4685173/delete-all-duplicate-rows-except-for-one-in-mysql)


