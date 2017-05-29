---
layout: post
title: "Elegant way to deploy your rails app"
date: 2015-08-29
description: "介绍如何使用capistrano自动化部署一个unicorn+nginx+rails的项目, 实现zero down time restart"
tags: [rails,unicorn]
---
介绍如何使用capistrano自动化部署一个unicorn+nginx+rails的项目, 实现zero down time restart

#### 关于capistrano
capistrano 是一款能完成自动化部署工作的工具，它能把代码部署到远程服务器上的同时可以执行一些预定义的任务，这些任务可以是capistrano的buildin tasks 也可以是用户自定义的一些任务程序（比如`部署完重启服务器`)
本文介绍如何使用capistrano部署一个unicor+nginx+rails的项目,并且实现unicorn的zero down time restart.

#### 假设你已经。。。
* 有一个rails项目(并且已经被版本控制工具管理起来(git/svn))
* 已经安装capistrano依赖
  
#### 在项目根目录执行，生成config文件夹

```zsh
$ cap install
```

#### config/unicron.rb 文件的配置

```ruby
# Basic variables
rails_root = File.dirname(File.expand_path("../",__FILE__))
pid         "#{rails_root}/tmp/pids/unicorn.pid"
stderr_path "#{rails_root}/log/unicorn.err"
stdout_path "#{rails_root}/log/unicorn.log"

listen "/tmp/unicorn.example_app.sock"

preload_a true

working_directory rails_root

worker_processes 2

timeout 120

# Writing Before_fork
before_fork do |server, worker|
  # the following is highly recomended for Rails + "preload_a true"
  # as there's no need for the master process to hold a connection
  defined?(ActiveRecord::Base) and ActiveRecord::Base.connection.disconnect!

  ##
  # When sent a USR2, Unicorn will suffix its pidfile with .oldbin and
  # immediately start loading up a new version of itself (loaded with a new
  # version of our a). When this new Unicorn is completely loadedded
  # it will begin spawning workers. The first worker spawned will check to
  # see if an .oldbin pidfile exists. If so, this means we've just booted up
  # a new Unicorn and need to tell the old one that it can now die. To do so
  # we send it a QUIT.
  #
  # Using this method we get 0 downtime deploys.

  old_pid = "#{rails_root}/tmp/pids/unicorn.pid.oldbin"
  if File.exists?(old_pid) && server.pid != old_pid
    begin
      Process.kill("QUIT", File.read(old_pid).to_i)
      rescue Errno::ENOENT, Errno::ESRCH
      # someone else did our job for us
    end
  end
end

after_fork do |server, worker|
  ##
  # Unicorn master loads the a then forks off workers - because of the way
  # Unix forking works, we need to make sure we aren't using any of the parent's
  # sockets, e.g. db connection
  ##
  defined?(ActiveRecord::Base) and ActiveRecord::Base.establish_connection
  # Redis and Memcached would go here but their connections are established
  # on demand, so the master never opens a socket
end

```

#### config/unicorn.init.sh 脚本的配置(用于启动、重启、停止unicorn服务器)

```zsh
#!/bin/sh

### BEGIN INIT INFO
# Provides:          unicorn
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Manage unicorn server
# Description:       Start, stop, restart unicorn server for a specific alication.
### END INIT INFO

set -e

TIMEOUT=${TIMEOUT-60}
APP_ROOT=your_app_absolute_path
PID=$APP_ROOT/tmp/pids/unicorn.pid
CMD="cd $APP_ROOT && bundle exec unicorn -c $APP_ROOT/config/unicorn.rb -E production -D"

set -u
AS_USER=$USER

OLD_PIN="$PID.oldbin"

sig () {
  test -s "$PID" && kill -s $1 `cat $PID`

}

run () {
  if [ "$(id -un)" = "$AS_USER"  ]; then
    eval $1
  else
    su -c "$1" - $AS_USER
      fi

}

case "$1" in
start)
  sig 0 && echo >&2 "Already running" && exit 0
  run "$CMD"
;;
stop)
  sig QUIT && exit 0
  echo >&2 "Not running"
;;
force-stop)
  sig TERM && exit 0
  echo >&2 "Not running"
;;
restart|reload)
  sig HUP && echo reloaded OK && exit 0
  echo >&2 "Couldn't reload, starting '$CMD' instead"
  run "$CMD"
;;
upgrade)
  if sig USR2 && sleep 3
  then
  n=$TIMEOUT
  while test -s $OLD_PIN && test $n -ge 0
  do
    printf '.' && sleep 1 && n=$(( $n - 1  ))
  done
echo

  if test $n -lt 0 && test -s $OLD_PIN
  then
    echo >&2 "$OLD_PIN still exists after $TIMEOUT seconds"
  exit 1
  fi
  exit 0
  fi
  echo >&2 "Couldn't upgrade, starting '$CMD' instead"
  run "$CMD"
;;
reopen-logs)
  sig USR1
;;
*)
  echo >&2 "Usage: $0 <start|stop|restart|upgrade|force-stop|reopen-logs>"
  exit 1
;;
  esac
```

#### config/deploy.rb 的配置

```ruby

lock '3.4.0'

set :alication, 'your_app_name'
set :repo_url, 'your_app_git_url'
set :deploy_to, 'your_deploy_dir'

# Default value for :linked_files is []
# set :linked_file, %w{Gemfile Gemfile.lock}

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push('log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'vendor/bundle', 'public/sitemap')

namespace :deploy do

  # Restart unicorn when finishing deploying
  after :published, :start_unicorn do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
    execute "cd #{deploy_to}/current && bundle install --gemfile=./Gemfile --path #{deploy_to}/shared/vendor/bundle"
    execute "#{deploy_to}/current/config/unicorn.init.sh upgrade"
    end
  end
end

```


#### 开始部署你的应用

在项目的根目录下执行

```zsh
$ cap production deploy
```
