---
layout: post
title: "Get the real remote ip in nginx"
date: 2015-08-16 18:00:00
description: "多层nginx中获取真实ip"
tags: [nginx]
---

#### 多层nginx中获取真实ip

在多层nginx中，若在后层的代理服务器中获得真实的用户IP，而不是上一层的代理服务器的IP，在反向代理服务器在转发请求的http头信息中，可以增加x_forwarded_for信息，用以记录原有客户端的IP地址和原来客户端的请求的服务器地址；即如下添加：

```nginx
server {

  proxy_set_header            Host $host;

  proxy_set_header            X-Real-Ip $remote_addr;

  proxy_set_header            X-Forwarded-For $proxy_add_x_forwarded_for;

}
```

proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for 一句会一层一层的记录转发信息
$http_x_forwarded_for记录的是原始用户的IP


