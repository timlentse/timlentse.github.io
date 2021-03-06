---
layout: post
title: "ActiveRecord connection via ssh"
date: 2015-06-13 18:00
description: "Making a ssh connection of ActiveRecord is very easy and the following will show you how to achieve it"
tags: [activerecord,net-ssh-gateway]
---
Making a ssh connection of ActiveRecord is very easy and the following will show you how to achieve it

#### Gems dependencies

* active_record
* mysql2
* net-ssh-gateway

#### Make a ssh connection

```ruby

require 'net/ssh/gateway'
require 'active_record'
require 'mysql2'
tunnel = Net::SSH::Gateway.new(
  'host', #Host which could connect to desired database
  'user', # Username of the host
  :password=>'your vps password'
  )
port = tunnel.open('sem3','3306')
# database configuration
CONFIG = {
  adapter:  "mysql2",
  host:     "127.0.0.1",
  username: "public_user",
  password: "public_user",
  database: "production",
  port: port,
  pool: 5,
  timeout: 5000,
  reconnect: true,
}

class BaseGeneralHotelPoi < ActiveRecord::Base
  self.establish_connection(CONFIG)
end

p BaseGeneralHotelPoi.take
tunnel.shutdown!

```

#### Use ssh authentication
Just like ssh-login, we can also use ssh key as the identification

```ruby
tunnel = Net::SSH::Gateway.new('seo','op', :keys => ['/Users/timlen/.ssh/id_rsa'])
```

You get it !
