<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 RC1  exBlog 网络日志(PHP+MYSQL) 1.2.0 RC1 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: BLOG配置文件
*\===========================================================================*/

######### exBlog网络日志相关设置 #########
$exBlog['host']="localhost";                //数据库地址
$exBlog['user']="db_user";                     //数据库用户名
$exBlog['password']="db_pwd";                     //数据库密码
$exBlog['dbname']="exblog";                //数据库名
$exBlog['one']="exblog_";                   //新建数据表名前缀,建议不要修改.如果一个数据库中要放多个exBlog,则修改.        
######### 以下不用改动 #########
$dblink=mysql_connect($exBlog['host'], $exBlog['user'], $exBlog['password']) or die("用户名或密码错误!");
@mysql_select_db($exBlog['dbname'], $dblink) or die("选择表名时失败!");

?>