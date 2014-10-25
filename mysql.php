<?php
    $dbc = mysql_connect("127.0.0.1","root","11311048");
    if (!$dbc) {
        echo "数据库链接错误!";
    } else {
        echo "锐想PHP系列教程，PHP环境搭建:Windows7系统配置PHP+Apache+MySQL环境教程";
    }
    mysql_close();
?>