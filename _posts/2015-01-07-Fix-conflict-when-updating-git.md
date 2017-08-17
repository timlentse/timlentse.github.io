---
layout: post
title: "Fix conflict when updating git"
date:   2015-01-07 19:31:00
description: "It is a cool thing to use git for version control."
tags: [git]
---

It is a cool thing to use git for version control. The idea is simple, when the developers update it I simply have to update 
the local repository to the recent HEAD in the master branch and I have the up-to-date stuff to link against.

<h3>But how to do this?</h3>   

```shell
$ git pull
``` 

Should do the trick when executed in the directory where the `.git` is located (find this with ls -lah to show hidden files).

You may get an error like: 

```shell
timlentse@timlentse:~/Gitpro/daodao$ git pull   
Updating d9319e1..3ce366f   
error: Your local changes to the following files would be overwritten by merge:   
	daodao.gemspec   
Please, commit your changes or stash them before you can merge.   
Aborting      
```

<h3>How to fix?</h3>   

```shell
timlentse@timlentse:~/Gitpro/daodao$ git stash save "update the daodao.gemspec"   
Saved working directory and index state On master: update the daodao.gemspec   
HEAD is now at d9319e1 reserve the pure gem   
```   

<h3>Finished!</h3>       

```shell
Updating d9319e1..3ce366f   
Fast-forward   
 daodao.gemspec           | 9 +++++++--   
 lib/daodao.rb            | 4 ++++   
 lib/daodao/hotel.rb      | 6 +++---   
 lib/daodao/hotel_list.rb | 6 +++---   
 lib/daodao/rank.rb       | 8 ++++----   
 spec/hotel_list_spec.rb  | 5 +++--   
 spec/hotel_spec.rb       | 5 +++--   
 spec/rank_spec.rb        | 4 ++--   
 spec/spec_helper.rb      | 2 +-   
 9 files changed, 30 insertions(+), 19 deletions(-)   
```  

