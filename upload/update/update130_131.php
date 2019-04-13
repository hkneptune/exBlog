<?php
/**
 *  exBlog 1.3.0  [to] exBlog 1.3.1 升级程序
 *  2004-05-02
 */

include("../include/config.inc.php");
include("../include/global-C.inc.php");

setGlobal(); //设置全局变量,得到表名

/**
 *	改变 $x_blog 表结构, 添加 GDswitch 字段 sitekeyword 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterGlobal()
{
	global $x;

	$query  = "UPDATE `$x[global]` SET Version='1.3.1'"; //为了版本检测请按这个标准写这个: 'exBlog 1.3.1'
    @mysql_query($query)
    ? $result.="更新 `$x[global]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更新 `$x[global]` 失败! ...... <font color='red'>×</font><br />";
	
	return $result;
}

/**
 *	改变 $x_blog 表结构, 添加 keyword 字段 summarycontent 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterBlog()
{	
	global $x;

	$query  = "ALTER TABLE `$x[blog]` ADD `nocom` ENUM( '0', '1' ) DEFAULT '0' NOT NULL";
    @mysql_query($query)
    ? $result.="更改表结构 `$x[blog]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[blog]` 失败! ...... <font color='red'>×</font><br />";

	return $result;
}

$ret .= alterGlobal();
$ret .= alterBlog();

echo $ret;

?>
