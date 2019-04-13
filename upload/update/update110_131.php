<?php
/**
 *  exBlog v1.1.0 [to] exBlog 1.3.1 升级程序
 *  2004-05-02
 */

include("../include/config.inc.php");
include("../include/global-C.inc.php");

setGlobal(); //设置全局变量,得到表名

/**
 *	改变 $x_blog 表结构, 添加 weather, top 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterBlog()
{	
	global $x;

	$query  = "ALTER TABLE `$x[blog]` ADD `weather` VARCHAR(20),";
	$query .= "ADD `top` ENUM('0', '1') DEFAULT '0' NOT NULL,";
	$query .= "ADD `hidden` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,"; 
	$query .= "ADD `html` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `nocom` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `keyword` VARCHAR(100) AFTER `addtime`,";
	$query .= "ADD `summarycontent` TEXT AFTER `keyword`";
    @mysql_query($query)
    ? $result.="更改表结构 `$x[blog]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[blog]` 失败! ...... <font color='red'>×</font><br />";

	$query = "UPDATE `$x[blog]` SET `weather` = 'null', `top` = '0'";
    @mysql_query($query)
    ? $result.="更新 `$x[blog]` 表内容成功!...... <font color='blue'>√</font><br />"
    : $result.="更新 `$x[blog]` 表内容失败! ...... <font color='red'>×</font><br />";

	return $result;
}

/**
 *	改变 $x_global 表结构, 添加 activeRun, unactiveRunMessage, initTime 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterGlobal()
{
	global $x;
	
	$curTime = date("Y-m-d");
	$unactiveMsg = "由于 xxx 原因, 本 blog 暂时关闭 xxx 天.";

	$query  = "ALTER TABLE `$x[global]` ADD `activeRun` ENUM('0', '1') DEFAULT '1' NOT NULL,";
	$query .= "ADD `unactiveRunMessage` TEXT,"; 
	$query .= "ADD `initTime` VARCHAR(10),";
	$query .= "ADD `langURL` VARCHAR(40) DEFAULT 'language/chinese_gb2312',";
	$query .= "ADD `isCountOnlineUser` ENUM('0', '1') DEFAULT '0' NOT NULL,";
       $query .= "ADD `Description` TEXT NOT NULL,";
	$query .= "ADD `Version` VARCHAR( 20 ) DEFAULT 'exBlog 1.3.1',"; //为了版本检测请按这个标准写这个: 'exBlog 1.3.0'
	$query .= "ADD `Webmaster` VARCHAR( 35 ) DEFAULT 'support@exblog.net' NOT NULL,";
	$query .= "ADD `GDswitch` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `exurlon` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `sitekeyword` VARCHAR(100) DEFAULT 'exSoft,exBlog,exBook,Weblog,blog,',";
	$query .= "ADD `summarynum` INT(5) DEFAULT '450',";
	$query .= "ADD `alltitlenum` INT(5) DEFAULT '18',";
	$query .= "ADD `listblognum` INT(5) DEFAULT '6',";
	$query .= "ADD `listallnum` INT(5) DEFAULT '20'";
    @mysql_query($query)
    ? $result.="更改表结构 `$x[global]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[global]` 失败! ...... <font color='red'>×</font><br />";


	$query  = "UPDATE `$x[global]` SET `activeRun` = '1', `unactiveRunMessage` = '$unactiveMsg',";
	$query .= "`initTime` = '$curTime', `isCountOnlineUser` = '0'";
    @mysql_query($query)
    ? $result.="更新 `$x[global]` 表内容成功!...... <font color='blue'>√</font><br />"
    : $result.="更新 `$x[global]` 表内容失败! ...... <font color='red'>×</font><br />";

	
	return $result;
}

/**
 *	改变 $x_links 表结构, 添加 visible 字段
 *	@param null
 *	@return 添加成功信息 String
 */
function alterLinks()
{
	global $x;

	$query = "ALTER TABLE `$x[links]` ADD `visible` ENUM('0', '1') DEFAULT '0' NOT NULL";
    @mysql_query($query)
    ? $result.="更改表结构 `$x[links]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[links]` 失败! ...... <font color='red'>×</font><br />";
	

	$query = "UPDATE `$x[links]` SET `visible` = '1'";
    @mysql_query($query)
    ? $result.="更新 `$x[links]` 表内容成功!...... <font color='blue'>√</font><br />"
    : $result.="更新 `$x[links]` 表内容失败! ...... <font color='red'>×</font><br />";


	return $result;
}

/**
 *	新建表 $x[user] 存放 BLOG 用户注册资料
 *	评论时可以注册为用户,以防ID被滥用
 *	@param null
 *	@return 新建成功信息 String
 */
function createUser()
{
	global $x;
	
	$query  = "CREATE TABLE `$x[user]` (";
	$query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query .= "name VARCHAR(20) NOT NULL,";
	$query .= "password VARCHAR(33) NOT NULL,";
	$query .= "email VARCHAR(35),";
	$query .= "homepage VARCHAR(40),";
	$query .= "qq INT(12))";
    @mysql_query($query)
    ? $result.="新建表 `$x[user]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="新建表 `$x[user]` 失败! ...... <font color='red'>×</font><br />";

	return $result;
}

/**
 *	新建表 $x[user] 存放 BLOG 用户注册资料
 *	评论时可以注册为用户,以防ID被滥用
 *	@param null
 *	@return 新建成功信息 String
 */
function createOnline()
{
	global $x;

	$query  = "CREATE TABLE `$x[online]` (";
	$query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query .= "ip VARCHAR(15) NOT NULL,";
	$query .= "lastLoginTime DATETIME)";
    @mysql_query($query)
    ? $result.="新建表 `$x[online]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="新建表 `$x[online]` 失败! ...... <font color='red'>×</font><br />";


	return $result; 
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
    @mysql_query($query)
    ? $result.="新建表 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="新建表 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";


	return $result; 
}

/* 创建exblog_upload表 */
function CreateUpload()
{
	global $x;
 
    $query="DROP TABLE IF EXISTS $x[upload];";
    @mysql_query($query)
    ? $result.="重置表 `$x[upload]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="重置表 `$x[upload]` 失败! ...... <font color='red'>×</font><br />";

    $query="CREATE TABLE `$x[upload]` (";
    $query.="max_file_size int(8) default NULL,";
    $query.="destination_folder varchar(40) default NULL,";
    $query.="up_type varchar(255) default NULL)";
    @mysql_query($query)
    ? $result.="新建表 `$x[upload]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="新建表 `$x[upload]` 失败! ...... <font color='red'>×</font><br />";

    $query="INSERT INTO `$x[upload]` (max_file_size,destination_folder,up_type)";
    $query.=" VALUES('2000000', 'upload/', 'gif,jpg,png,zip,rar,txt')";
    @mysql_query($query)
    ? $result.="初始化 `$x[upload]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="初始化 `$x[upload]` 失败! ...... <font color='red'>×</font><br />";

	return $result;

}

/* 创建exblg_keyword表 */
function CreateKeyword()
{
	global $x;
 
    $query="DROP TABLE IF EXISTS $x[keyword];";
    @mysql_query($query)
    ? $result.="重置表 `$x[keyword]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="重置表 `$x[keyword]` 失败! ...... <font color='red'>×</font><br />";

    $query  = "CREATE TABLE `$x[keyword]` (";
    $query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query .= "word VARCHAR(100) NOT NULL,";
    $query .= "content VARCHAR(255) NOT NULL,";
    $query .= "url VARCHAR(150))";
    @mysql_query($query)
    ? $result.="新建表 `$x[keyword]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="新建表 `$x[keyword]` 失败! ...... <font color='red'>×</font><br />";

	$query="INSERT INTO `$x[keyword]` (word, content)";
	$query.=" VALUES('exBlog','HOME: http://www.exBlog.net')";
    @mysql_query($query)
    ? $result.="初始化 `$x[keyword]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="初始化 `$x[keyword]` 失败! ...... <font color='red'>×</font><br />";

	return $result;
    
}

/**
 *	添加值到 $x[sort], 添加一个 默认分类 栏目
 *	@param null
 *	@return 更新成功信息 String
 */
function insertSort()
{
	global $x;

	$ienName = "default";
	$icnName = "默认分类";
	$sortDescription = "如果你没有新建栏目, blog 将发布在这里 :)";

	$query="INSERT INTO `$x[sort]` (enName, cnName, description)";
    $query.=" VALUES('$ienName', '$icnName', '$sortDescription')";
    @mysql_query($query)
    ? $result.="添加分类栏目至 `$x[sort]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加分类栏目至 `$x[sort]` 失败! ...... <font color='red'>×</font><br />";

	return $result;
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
    @mysql_query($query)
    ? $result.="添加天气至 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加天气至 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"2\", \"sunny\", \"晴朗\")";
    @mysql_query($query)
    ? $result.="添加天气至 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加天气至 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"3\", \"cloudy\", \"多云\")";
    @mysql_query($query)
    ? $result.="添加天气至 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加天气至 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"4\", \"rain\", \"下雨\")";
    @mysql_query($query)
    ? $result.="添加天气至 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加天气至 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"5\", \"snow\", \"下雪\")";
    @mysql_query($query)
    ? $result.="添加天气至 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加天气至 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";
	$query  = "INSERT INTO `$x[weather]` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"6\", \"cloudysky\", \"阴天\")";
    @mysql_query($query)
    ? $result.="添加天气至 `$x[weather]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="添加天气至 `$x[weather]` 失败! ...... <font color='red'>×</font><br />";

	
	return $result;
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
    @mysql_query($query)
    ? $result.="更改表结构 `$x[admin]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[admin]` 失败! ...... <font color='red'>×</font><br />";

	return $result;
}

/**
 *	新建表 $x[trackback] 存放 Ttrackback 资料
 *	@param null
 *	@return 新建成功信息 String
 */
function createTrackback()
{
	global $x;
	
	$query  = "CREATE TABLE `$x[trackback]` (";
	$query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query .= "TrackbackID INT(10) DEFAULT '0' NOT NULL,";
	$query .= "url VARCHAR(200) NOT NULL,";
	$query .= "blog_name VARCHAR(35),";
	$query .= "title VARCHAR(40),";
	$query .= "excerpt VARCHAR(255),";
	$query .= "addtime DATETIME)";
    @mysql_query($query)
    ? $result.="新建表 `$x[trackback]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="新建表 `$x[trackback]` 失败! ...... <font color='red'>×</font><br />";

	return $result;
}

/* 调整文章分类 */
function sortgo()
{
	global $x;
$query="SELECT * FROM `$x[sort]` ORDER BY id DESC";
    if ($result11=@mysql_query($query) ){
      $result.="";
    }else{
      $result.="调整 文章分类 失败! ...... <font color='red'>×</font><br />";
    }
    while($row=mysql_fetch_array($result11))
	{
	$query2="UPDATE `$x[blog]` SET sort='$row[id]' WHERE sort='$row[cnName]'";
    @mysql_query($query2)
    ? $result.="调整 $row[cnName] 分类 成功!...... <font color='blue'>√</font><br />"
    : $result.="调整 $row[cnName] 文章分类 失败! ...... <font color='red'>×</font><br />";
	$query3="UPDATE `$x[comment]` SET commentSort='$row[id]' WHERE commentSort='$row[cnName]'";
    @mysql_query($query3)
    ? $result.="调整 $row[cnName] 分类 成功!...... <font color='blue'>√</font><br />"
    : $result.="调整 $row[cnName] 文章分类 失败! ...... <font color='red'>×</font><br />";
	}

	return $result;
}

$ret  = alterBlog();
$ret .= alterGlobal();
$ret .= alterLinks();
$ret .= alterAdmin();
$ret .= createOnline();
$ret .= createWeather();
$ret .= createTrackback();
$ret .= CreateKeyword();
$ret .= CreateUpload();
$ret .= insertSort();
$ret .= insertWeather();
$ret .= sortgo();

echo $ret;

?>