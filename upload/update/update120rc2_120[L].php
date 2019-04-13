<?php
/**
 *  1.2.0_RC2  [to] exBlog 1.2.0_[L] 升级程序
 *  @author sheer [at] 2004-11-25
 */

include("../include/config.inc.php");
include("../include/global-C.inc.php");

setGlobal(); //设置全局变量,得到表名

/**
 *	改变 $x_blog 表结构, 添加 hidden 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterBlog()
{	
	global $x;

	$query  = "ALTER TABLE `$x[blog]` ADD `html` ENUM( '0', '1' ) DEFAULT '0' NOT NULL";
	@mysql_query($query) or die("更改表结构 `$x[blog]` 失败!");

	return "更改表结构 `$x[blog]` 成功!<br>";
}
function alterAdmin()
{	
	global $x;

	$query  = "ALTER TABLE `$x[admin]` ADD `phone` VARCHAR(11) DEFAULT '0' NOT NULL";
	@mysql_query($query) or die("更改表结构 `$x[admin]` 失败!");

	return "更改表结构 `$x[admin]` 成功!<br>";
}
function alterGlobal()
{
	global $x;

	$query  = "ALTER TABLE `$x[global]` CHANGE `Version` `Version` VARCHAR( 20 ) DEFAULT 'exBlog 1.2.0 [L]'"; //为了版本检测请按这个标准写这个: 'exBlog 1.2.0 [L]'
	@mysql_query($query) or die("更改表结构 `$x[global]` 失败!");

	$query  = "UPDATE `$x[global]` SET Version='exBlog 1.2.0 [L]'"; //为了版本检测请按这个标准写这个: 'exBlog 1.2.0 [L]'
	@mysql_query($query) or die("更改表结构 `$x[global]` 失败!");
	
	return "更改表结构 `$x[global]` 成功!<br>";
}

/**
 *	改变 $x_weather 表结构,修改enWeather字段长度
 *	@param null
 *	@return 添加成功信息 String
 */
function alterWeather()
{	
	global $x;

	$query  = "ALTER TABLE `$x[weather]` CHANGE `enWeather` `enWeather` VARCHAR( 10 ) NOT NULL ";
	@mysql_query($query) or die("更改表结构 `$x[weather]` 失败!");
	$query  = "INSERT INTO `$x[weather]` ( `id` , `enWeather` , `cnWeather` ) VALUES ('6', 'cloudysky', '阴天') ";
	@mysql_query($query) or die("更改表结构 `$x[weather]` 失败!");	
	$query  = "UPDATE `$x[weather]` SET `cnWeather` = '晴朗' WHERE `cnWeather` = '阳光' LIMIT 1 ";
	@mysql_query($query) or die("更改表结构 `$x[weather]` 失败!");

	return "更改表结构 `$x[weather]` 成功!<br>";
}

$ret .= alterBlog();
$ret .= alterAdmin();
$ret .= alterGlobal();
$ret .= alterWeather();

echo $ret;

?>
