<?php
return array(

'TMPL_PARSE_STRING' => array(

'__VENDOR__' => __ROOT__ . '/Public/vendor', // 配置自定义资源文件夹：第三方库
'__ADIST__' => __ROOT__ . '/Public/Admin/dist', // 配置自定义资源文件夹：admin
'__HDIST__' => __ROOT__ . '/Public/Home/dist', // 配置自定义资源文件夹：home
'__DIST__' => __ROOT__ . '/Public/dist', // 配置自定义资源文件夹：home
'__PUBLIC__' => __ROOT__ . '/Public', // 配置自定义资源文件夹

),

/* 数据库设置 */
'DB_TYPE' => 'mysql', // 数据库类型
'DB_HOST' => '127.0.0.1', // 服务器地址
'DB_NAME' => 'wshop', // 数据库名
'DB_USER' => 'root', // 用户名
'DB_PWD' => 'mysqlyh12138..', // 密码
'DB_PORT' => '3306', // 端口
'DB_PREFIX' => 'wshop_', // 数据库表前缀


);