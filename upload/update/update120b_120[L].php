<?php
/**
 * 1.2.0_beta_0909 [to] exBlog 1.2.0_[L] 升级程序
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

	$query  = "ALTER TABLE `$x[blog]` ADD `hidden` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `html` ENUM( '0', '1' ) DEFAULT '0' NOT NULL";
	@mysql_query($query) or die("更改表结构 `$x[blog]` 失败!");

	return "更改表结构 `$x[blog]` 成功!<br>";
}

/**
 *	改变 $x_global 表结构, 添加description,webmaster 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterGlobal()
{
	global $x;
	


	$query  = "ALTER TABLE `$x[global]` ADD `Description` TEXT NOT NULL,";
	$query .= "ADD `Version` VARCHAR( 20 ) DEFAULT 'exBlog 1.2.0 [L]',"; //为了版本检测请按这个标准写这个: 'exBlog 1.2.0 [L]'
	$query .= "ADD `Webmaster` VARCHAR( 35 ) DEFAULT 'exblog@fengling.net' NOT NULL";
	@mysql_query($query) or die("更改表结构 `$x[global]` 失败!");
	
	return "更改表结构 `$x[global]` 成功!<br>";
}

/**
 *	新建表 $x[photo] 存放 上传设置资料
 *	@param null
 *	@return 新建成功信息 String
 */
function createPhoto()
{
	global $x;

	$query  = "CREATE TABLE `$x[photo]` (";
	$query .= "max_file_size int(8) default NULL,";
	$query .= "destination_folder varchar(40) default NULL,";
	      $query .= "watermark int(1) default NULL,";
       $query .= "waterposition int(1) default NULL,";
	$query .= "waterstring varchar(40) default NULL)";
	@mysql_query($query) or die("新建表 `$x[photo]` 失败!");


	return "新建表 `$x[photo]` 成功!<br>"; 
}

/**
 *	新建表 $x[weather] 存放 天气中英文名资料
 *	@param null
 *	@return 新建成功信息 String
 */
function createWeather()
{
	global $x;

	$query  = "CREATE TABLE `$x[weather]` (";
	$query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query .= "enWeather VARCHAR(10) NOT NULL,";
      $query .= "cnWeather VARCHAR(20) NOT NULL)";
	@mysql_query($query) or die("新建表 `$x[weather]` 失败!");

	return "新建表 `$x[weather]` 成功!<br>"; 
}

/**
 *	添加值到 $x[photo], 添加上传文件默认设置
 *	@param null
 *	@return 更新成功信息 String
 */
function insertPhoto()
{
	global $x;

	$max_file_size = "2000000";
	$destination_folder = "upload/";
	$watermark = "0";
	$waterposition = "2";
	$waterstring = "http://www.exBlog.net/";

	$query="INSERT INTO `$x[photo]` (max_file_size, destination_folder, watermark, waterposition, waterstring)";
       $query.=" VALUES('$max_file_size', '$destination_folder', '$watermark', '$waterposition', '$waterstring')";
	@mysql_query($query) or die("添加分类栏目至 `$x[sort]` 失败!");

	return "添加上传设置至 `$x[photo]` 成功!<br>";
}

/**
 *	添加值到 $x[weather], 添加天气中英文对照
 *	@param null
 *	@return 更新成功信息 String
 */
function insertWeather()
{
    global $x;

	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"1\", \"null\", \"请选择天气\")";
   	@mysql_query($query) or die("添加天气至 `$x[weather]` 失败!");
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"2\", \"sunny\", \"晴朗\")";
   	@mysql_query($query) or die("添加天气至 `$x[weather]` 失败!");
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"3\", \"cloudy\", \"多云\")";
	@mysql_query($query) or die("添加天气至 `$x[weather]` 失败!");
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"4\", \"rain\", \"下雨\")";
	@mysql_query($query) or die("添加天气至 `$x[weather]` 失败!");
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"5\", \"snow\", \"下雪\")";
   	@mysql_query($query) or die("添加天气至 `$x[weather]` 失败!");
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"6\", \"cloudysky\", \"阴天\")";
   	@mysql_query($query) or die("添加天气至 `$x[weather]` 失败!");

	
	return "添加天气对照至 `$x[weather]` 成功!<br>";
}
/**
 *	改变 $x_admin 表结构, 添加phone 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterAdmin()
{	
	global $x;

	$query  = "ALTER TABLE `$x[admin]` ADD `phone` VARCHAR(11) DEFAULT '0' NOT NULL";
	@mysql_query($query) or die("更改表结构 `$x[admin]` 失败!");

	return "更改表结构 `$x[admin]` 成功!<br>";
}
$ret .= alterGlobal();
$ret .= alterBlog()
$ret .= createPhoto();
$ret .= createWeather();
$ret .= insertPhoto();
$ret .= insertWeather();
$ret .= insertAdmin();

echo $ret;

?>