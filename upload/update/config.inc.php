<?php 

# exBlog 1.3.0 之前版本的用户请将本文件设置正确替换原有文件.


######### exBlog网络日志相关设置 #########
	$exBlog['host'] = "localhost";		//数据库地址
	$exBlog['user'] = "test";		//数据库用户名
	$exBlog['password'] = "";		//数据库密码
	$exBlog['dbname'] = "test";		//数据库名
	$exBlog['one'] = "exblog_";		//新建数据表名前缀

######### 以下不用改动 #########
	include("condb.php");
	connect_db();
?>