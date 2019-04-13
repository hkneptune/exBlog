<?php

//------------------------------------------------------------------------+
//
//   exBlog 卸载程序
//   
//   为什么要使用这个程序？难道你失望到了极点？
//   
//   如果你放弃了 exBlog ，告诉我们为什么。
//
//   请先备份你的数据！此文件请上传到跟index.php在同一个目录
//
//------------------------------------------------------------------------+


/** exBlog 卸载程序 **/

include("include/config.inc.php");
include("include/global-C.inc.php");

setGlobal(); //设置全局变量,得到表名

function uninstallexBlog()
{	
	global $x;

	$query  = "DROP TABLE `$x[aboutme]`, `$x[admin]`, `$x[announce]`, `$x[blog]`, `$x[comment]`, `$x[global]`, `$x[links]`, `$x[online]`, `$x[photo]`, `$x[sort]`, `$x[user]`, `$x[visits]`, `$x[weather]`;";

	@mysql_query($query) or die("删除表失败!");

	return "卸载成功!<br>";
}

$ret  = uninstallexBlog();

echo $ret;

?>