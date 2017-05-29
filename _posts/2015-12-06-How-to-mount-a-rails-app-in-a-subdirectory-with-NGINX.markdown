---
layout: post
title: "How to mount a rails app to subdirectory with Nginx"
date: 2015-12-06
description: "This article mainly focus on how to mount a rails app to a subdirectory Using Nginx. And you can also learn how to use Nginx as a reverse proxy server to serve your rails+unicorn app after reading this article."
tags: [nginx,rails]
---

This article mainly focus on how to mount a rails app to a subdirectory Using Nginx. And you can also learn how to use Nginx as a reverse proxy server to serve your rails+unicorn app after reading this article.

#### Why I write this post
About two weeks ago, I came across a problem making rails to serve a subdirectory of a website instead of the whole website. It means that My rails app is only resposible for a specific subdirectory. For example, My website is `http://www.abc.com` and I want every request from `/mysubdir/` will be handled by rails and the others will be processed by other applications such `php、java...`.

#### Configure rails first 
After google for a while, I found rails provide an easy way for us to map our rails app to a subdirectory.
In your `config/application.rb` file, add the following code:

```ruby
module YourAPPName
  class Application < Rails::Application
    config.relative_url_root = '/subdir'
    # some other configuration code
  end
end
```

#### Getting Rails respond on subdirectory

Now we had mounted rails on a subdirectory. The next step we need to do is configuring rack to pass requests on that subdirectory to our Rails application. Now modified config.ru file:

```ruby
map Rails::Application.config.relative_url_root || "/" do
  run Rails.Application
end
```

#### Check if everything works properly 
Here we will check to see if our application has been configfigured with a relative_url_root. Staring the rails sever in development mode and make a request on 'http://localhost:3000', we will see all the static files are requested from `/subdir/assets/` instead of `/assets`. If so, congratulation! Your rails application had been mounted on the desired subdirectory.

#### Letting Nginx to serve our static files

It is recommended letting Nginx to serve our static files(imges, css ,js) because of the speed of Nginx. But first we should tell Nginx where my static assets are. When a request comes to our rails application on our subdirectory path, For examplele http://abc.com/subdir/assets/images/favico.ico, Nginx will look in our public directory for a file mathing the path `/subdir/assets/images/favico.ico`. The file doesn’t exist there, it exists at `/assets/images/favico.ico`. So we have the following configuration:

```ruby
upstream unicorn_sock {
  server your_sock_path;
}

server {
root <path_to_your_rails_app>/public;
location @proxy_rails_app {
  proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
  proxy_set_header Host $http_host;
  proxy_redirect off;
  proxy_pass http://unicorn_sock;
}
location /subdir/ {
  alias <path_to_your_rails_app>/public/;
  try_files $uri @proxy_rails_app;
}
try_files $uri @proxy_rails_app;
# some other configuration code
}
```

#### That is all!
Now Nginx is correctlly handing the assets files and Rail application is successfully responding to subdirectory.

#### Reference 
* [Mounting a Rails 4 A in a Subdirectory Using NGINX](http://stevesaarinen.com/blog/2013/05/16/mounting-rails-in-a-subdirectory-with-nginx-and-unicorn/)

* [Rails guide](http://guides.rubyonrails.org/configuring.html#deploy-to-a-subdirectory-relative-url-root)
