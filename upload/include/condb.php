<?php
function connect_db()
{
global $exBlog;
$dblink=mysql_connect($exBlog['host'], $exBlog['user'], $exBlog['password']) or die("数据库用户名或密码错误!");
@mysql_select_db($exBlog['dbname'], $dblink) or die("数据库选择表名时失败!");

include_once("phplib/db_mysql.inc");
include_once("phplib/ct_sql.inc");
include_once("phplib/UseDB.inc.php");
$db = new UseDB($exBlog['host'], $exBlog['user'], $exBlog['password'], $exBlog['dbname']);
}
?>