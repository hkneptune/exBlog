<?php
/*===================================================================
*\    exBlog Ver: 1.3.0 exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\-------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004 - 2005. All rights reserved
*\-------------------------------------------------------------------
*\    主页:http://www.exblog.net (如有任何使用&BUG问题请在论坛提出)
*\-------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\-------------------------------------------------------------------
*\    本页说明: 创建MySql数据库表格
*\=================================================================*/

/* 创建exblog_admin表 */
function CreateAdmin($i)
{   
    global $result;
	global $lang, $langpublic;
	$i_admin="$i"."admin";

    $query="DROP TABLE IF EXISTS $i_admin;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_admin` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_admin` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
    $query="CREATE TABLE `$i_admin` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="uid INT(1) UNSIGNED NOT NULL,";
    $query.="user VARCHAR(20) NOT NULL,";
    $query.="password VARCHAR(33) NOT NULL,";
    $query.="email VARCHAR(35) NOT NULL,";
    $query.="phone VARCHAR(11) DEFAULT '0' NOT NULL)";

    @mysql_query($query)
    ? $result.="$lang[65] `$i_admin` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_admin` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
}

/* 创建exblog_visits表 */
function CreateVisits($i)
{
    global $result;
	global $lang, $langpublic;
	$i_visits="$i"."visits";

    $query="DROP TABLE IF EXISTS $i_visits;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_visits` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_visits` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
    $curDate=date("Y-m-d");
    $query="CREATE TABLE `$i_visits` (";
    $query.="visits INT(20) UNSIGNED,";
	$query.="currentDate VARCHAR(20),";
	$query.="todayVisits INT(5))";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_visits` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_visits` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="INSERT INTO `$i_visits` (visits,currentDate,todayVisits)";
    $query.=" VALUES('0','$curDate','0')";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_visits` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_visits` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/* 创建 exblog_announce 公告栏表 */
function createAnnounce($i)
{
	global $result;
    global $lang, $langpublic;
	$i_announce="$i"."announce";
 
    $query="DROP TABLE IF EXISTS $i_announce;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_announce` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_announce` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query="CREATE TABLE `$i_announce` (";
	$query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query.="title VARCHAR(40),";
	$query.="content TEXT,";
	$query.="author VARCHAR(20),";
	$query.="email VARCHAR(35),";
	$query.="addtime DATETIME)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_announce` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_announce` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}


/* 创建文章分类 exblog_weather 表 */
function CreateWeather($i)
{
    global $result;
    global $lang, $langpublic;
	$i_weather="$i"."weather";
 
    $query="DROP TABLE IF EXISTS $i_weather;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_weather` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_weather` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="enWeather VARCHAR(10) NOT NULL,";
    $query.="cnWeather VARCHAR(20) NOT NULL)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_weather` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query  = "INSERT INTO `$i_weather` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"1\", \"null\", \"$lang[71]\")";
    @mysql_query($query)
    ? $result.=""
    : $result.="$lang[66] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
	
	$query  = "INSERT INTO `$i_weather` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"2\", \"sunny\", \"$lang[72]\")";
    @mysql_query($query)
    ? $result.=""
    : $result.="$lang[66] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
	$query  = "INSERT INTO `$i_weather` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"3\", \"cloudy\", \"$lang[73]\")";
    @mysql_query($query)
    ? $result.=""
    : $result.="$lang[66] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
	$query  = "INSERT INTO `$i_weather` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"4\", \"rain\", \"$lang[74]\")";
    @mysql_query($query)
    ? $result.=""
    : $result.="$lang[66] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
	$query  = "INSERT INTO `$i_weather` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"5\", \"snow\", \"$lang[75]\")";
    @mysql_query($query)
    ? $result.=""
    : $result.="$lang[66] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
	$query  = "INSERT INTO `$i_weather` (id, enWeather, cnWeather)";
	$query .= " VALUES(\"6\", \"cloudysky\", \"$lang[76]\")";
    @mysql_query($query)
    ? $result.=""
    : $result.="$lang[66] `$i_weather` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
	$result .= "$lang[66] `$i_weather` $lang[67]...... <font color='blue'>$lang[69]</font><br />";
}
/* 创建文章类别 exblog_sort表 */
function CreateSort($i)
{
    global $result;
    global $lang, $langpublic;
	$i_sort="$i"."sort";
 
    $query="DROP TABLE IF EXISTS $i_sort;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_sort` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_sort` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_sort` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="enName VARCHAR(20) NOT NULL,";
    $query.="cnName VARCHAR(20) NOT NULL,";
    $query.="description VARCHAR(50))";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_sort` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_sort` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query  = "INSERT INTO `$i_sort` (enName, cnName, description)";
	$query .= " VALUES(\"default\", \"$lang[77]\", \"$lang[78]\")";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_sort` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_sort` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
}
/* 创建文章 exblog_blog 表 */
function CreateBlog($i)
{
    global $result;
    global $lang, $langpublic;
	$i_article="$i"."blog";
 
    $query="DROP TABLE IF EXISTS $i_article;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_article` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_article` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_article` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="sort VARCHAR(20) NOT NULL,";
    $query.="title VARCHAR(50) NOT NULL,";
    $query.="content TEXT NOT NULL,";
    $query.="author VARCHAR(20),";
	$query.="email VARCHAR(35),";
    $query.="visits INT(10),";
    $query.="addtime VARCHAR(20),";
    $query.="keyword VARCHAR(100),";
    $query.="summarycontent TEXT,";
	$query .= "weather VARCHAR(20),";
	$query .= "top ENUM('0', '1') DEFAULT '0' NOT NULL,";
    $query.="hidden ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
    $query.="html ENUM( '0', '1' ) DEFAULT '0' NOT NULL)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_article` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_article` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
}

/* 创建exblg_comment表 */
function CreateComment($i)
{
    global $result;
    global $lang, $langpublic;
	$i_comment="$i"."comment";
 
    $query="DROP TABLE IF EXISTS $i_comment;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_comment` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_comment` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_comment` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="commentID INT(10),";
	$query.="commentSort VARCHAR(20),";
    $query.="author VARCHAR(20),";
    $query.="email VARCHAR(35),";
	$query.="homepage VARCHAR(40),";
	$query.="qq INT(12),";
    $query.="content TEXT,";
    $query.="addtime DATETIME)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_comment` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_comment` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
}

/* 创建exblg_Links表 */
function CreateLinks($i)
{
    global $result;
    global $lang, $langpublic;
	$i_links="$i"."links";
 
    $query="DROP TABLE IF EXISTS $i_links;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_links` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_links` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query  = "CREATE TABLE `$i_links` (";
    $query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query .= "homepage VARCHAR(20) NOT NULL,";
	$query .= "logoURL VARCHAR(150),";
    $query .= "url VARCHAR(150) NOT NULL,";
    $query .= "description VARCHAR(100),";
    $query .= "visits INT(10),";
	$query .= "visible ENUM('0', '1'))";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_links` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_links` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query="INSERT INTO `$i_links` (homepage, logoURL, url, description, visits, visible)";
	$query.=" VALUES('exBlog\'s Home','./images/logo_s.gif','http://www.exBlog.net','exSoft Team', '0', '0')";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_links` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_links` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
}

/* 创建exblog_aboutme表 */
function CreateAboutme($i)
{
    global $result;
    global $lang, $langpublic;
	$i_aboutme="$i"."aboutme";
 
    $query="DROP TABLE IF EXISTS $i_aboutme;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_aboutme` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_aboutme` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_aboutme` (";
    $query.="name VARCHAR(20),";
    $query.="age INT(3),";
    $query.="email VARCHAR(35),";
    $query.="qq INT(12),";
    $query.="icq INT(12),";
    $query.="msn VARCHAR(35),";
    $query.="description TEXT)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_aboutme` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_aboutme` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="INSERT INTO `$i_aboutme` (name,age,email,qq,icq,msn,description)";
    $query.=" VALUES('Name','20','@','0','0','@','$lang[79]')";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_aboutme` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_aboutme` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/* 创建exblog_photo表 */
function CreatePhoto($i)
{
    global $result;
    global $lang, $langpublic;
	$i_photo="$i"."photo";
 
    $query="DROP TABLE IF EXISTS $i_photo;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_photo` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_photo` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_photo` (";
    $query.="max_file_size int(8) default NULL,";
    $query.="destination_folder varchar(40) default NULL,";
    $query.="watermark int(1) default NULL,";
    $query.="waterposition int(1) default NULL,";
    $query.="waterstring varchar(40) default NULL)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_photo` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_photo` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="INSERT INTO `$i_photo` (max_file_size,destination_folder,watermark,waterposition,waterstring)";
    $query.=" VALUES('2000000', 'upload/', '0', '2', 'http://www.exblog.net/')";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_photo` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_photo` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/* 创建exblog_upload表 */
function CreateUpload($i)
{
    global $result;
    global $lang, $langpublic;
	$i_upload="$i"."upload";
 
    $query="DROP TABLE IF EXISTS $i_upload;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_upload` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_upload` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$i_upload` (";
    $query.="max_file_size int(8) default NULL,";
    $query.="destination_folder varchar(40) default NULL,";
    $query.="up_type varchar(255) default NULL)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_upload` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_upload` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query="INSERT INTO `$i_upload` (max_file_size,destination_folder,up_type)";
    $query.=" VALUES('2000000', 'upload/', 'gif,jpg,png,zip,rar,txt')";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_upload` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_upload` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/* 创建网站常用信息表 */
function CreateGlobal($i)
{
    global $result;
    global $lang, $langpublic;
	$i_global="$i"."global";
 
    $query="DROP TABLE IF EXISTS $i_global;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_global` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_global` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query  = "CREATE TABLE `$i_global` (";
    $query .= "siteName VARCHAR(50),";
    $query .= "siteUrl VARCHAR(50),";
    $query .= "copyright VARCHAR(255),";
	$query .= "tmpURL VARCHAR(40),";
	$query .= "langURL VARCHAR(40),";
	$query .= "activeRun ENUM('0', '1') DEFAULT '1',";
	$query .= "unactiveRunMessage TEXT,";
	$query .= "initTime VARCHAR(10),";
	$query .= "isCountOnlineUser ENUM('0', '1') DEFAULT '0',";
	$query .= "Description TEXT NOT NULL ,";
	$query .= "Version VARCHAR( 20 ) DEFAULT 'exBlog 1.3.0' ,";
	$query .= "Webmaster VARCHAR( 35 ) DEFAULT 'support@exblog.net' NOT NULL ,";
	$query .= "sitekeyword VARCHAR(100) DEFAULT 'exsoft,exblog,blog,weblog,blog,',";
	$query .= "GDswitch ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "exurlon ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "summarynum INT(5) DEFAULT '450' ,";
	$query .= "alltitlenum INT(5) DEFAULT '18' ,";
	$query .= "listblognum INT(5) DEFAULT '6' ,";
	$query .= "listallnum INT(5) DEFAULT '20' )";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_global` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_global` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/**
 *	新建用户表
 *	@param $i 要新建的表名前缀
 *	@return null
 */
function createUser($i)
{
	global $result;
    global $lang, $langpublic;
	$i_user = "$i"."user";
 
    $query="DROP TABLE IF EXISTS $i_user;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_user` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_user` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query  = "CREATE TABLE `$i_user` (";
	$query .= "id INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,";
	$query .= "name VARCHAR(20) NOT NULL,";
	$query .= "password VARCHAR(33) NOT NULL,";
	$query .= "email VARCHAR(35),";
	$query .= "homepage VARCHAR(40),";
	$query .= "qq INT(12))";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_user` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_user` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/**
 *	创建在线用户表
 *	@param $i 要新建的表名前缀
 *	@return null
 */
function createOnline($i)
{
	global $result;
    global $lang, $langpublic;

	$i_online = $i."online";
 
    $query="DROP TABLE IF EXISTS $i_online;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_online` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_online` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query  = "CREATE TABLE `$i_online` (";
	$query .= "id INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,";
	$query .= "ip VARCHAR(15) NOT NULL,";
	$query .= "lastLoginTime DATETIME)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_online` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_online` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/**
 *	新建表 $x[trackback] 存放 Ttrackback 资料
 *	@param null
 *	@return 新建$lang[67]信息 String
 */
function createTrackback($i)
{
	global $result;
    global $lang, $langpublic;

	$i_trackback = $i."trackback";
 
    $query="DROP TABLE IF EXISTS $i_trackback;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_trackback` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_trackback` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query  = "CREATE TABLE `$i_trackback` (";
	$query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query .= "TrackbackID INT(10) DEFAULT '0' NOT NULL,";
	$query .= "url VARCHAR(200) NOT NULL,";
	$query .= "blog_name VARCHAR(35),";
	$query .= "title VARCHAR(40),";
	$query .= "excerpt VARCHAR(255),";
	$query .= "addtime DATETIME)";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_trackback` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_trackback` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/* 创建 exblog_attachment 公告栏表 */
function createAttachment($i)
{
	global $result;
    global $lang, $langpublic;
	$i_attachment="$i"."attachment";
 
    $query="DROP TABLE IF EXISTS $i_attachment;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_attachment` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_attachment` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query="CREATE TABLE `$i_attachment` (";
	$query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query.="blogid int(10) NOT NULL default '0',";
	$query.="filename varchar(255) NOT NULL default '',";
	$query.="mimetype varchar(25) NOT NULL default '',";
	$query.="filetype varchar(15) NOT NULL default '',";
	$query.="filesize int(10) NOT NULL default '0',";
	$query.="saveas varchar(255) NOT NULL default '',";
	$query.="hot int(11) NOT NULL default '0')";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_attachment` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_attachment` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

}

/* 创建exblg_keyword表 */
function CreateKeyword($i)
{
    global $result;
    global $lang, $langpublic;
	$i_links="$i"."keyword";
 
    $query="DROP TABLE IF EXISTS $i_keyword;";
    @mysql_query($query)
    ? $result.="$lang[64] `$i_keyword` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$i_keyword` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

    $query  = "CREATE TABLE `$i_keyword` (";
    $query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query .= "word VARCHAR(100) NOT NULL,";
    $query .= "content VARCHAR(255) NOT NULL,";
    $query .= "url VARCHAR(150))";
    @mysql_query($query)
    ? $result.="$lang[65] `$i_keyword` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$i_keyword` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";

	$query="INSERT INTO `$i_keyword` (word, content)";
	$query.=" VALUES('exBlog','HOME: http://www.exBlog.net')";
    @mysql_query($query)
    ? $result.="$lang[66] `$i_keyword` $lang[67]...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[66] `$i_keyword` $lang[68]! ...... <font color='red'>$lang[70]</font><br />";
    
}

##############################
###### 创建基本资料 操作数据库 函数 ######
function CreateGlobal_go( $exBlogName, $exBlogUrl, $exBlogCopyright, $templatesURL,
			 $description, $webmaster, $sitekeyword, $languageURL )
{
	global $exBlog;
	global $exBlogVer_s;
    global $lang, $langpublic;

	$i_global=$exBlog['one']."global";

	$unactiveRunMessage = $lang[80];
	$initTime = date("Y-m-d");

    $query="INSERT INTO `$i_global` (siteName, siteUrl, copyright, tmpURL, langURL,
					activeRun, unactiveRunMessage, initTime,
					isCountOnlineUser, Description,
					Version, Webmaster, sitekeyword,
					GDswitch, exurlon,
					summarynum, alltitlenum, listblognum, listallnum)";

    $query.=" VALUES('$exBlogName', '$exBlogUrl', '$exBlogCopyright', '$templatesURL', '$languageURL',
					'1', '$unactiveRunMessage', '$initTime',
					'0', '$description',
					'$exBlogVer_s', '$webmaster', '$sitekeyword',
					'0', '0', '450', '18', '6', '20')";

    return @mysql_query($query) ? 1 : 0;

}
###### 创建基本资料 操作数据库 函数 ######
function CreateSystem($iUser, $iPassword, $iPassword_2, $iEmail, $iPhone)
{
	global $exBlog;
    global $lang, $langpublic;
	$i_admin=$exBlog['one']."admin";
	$result=checkUser($iUser);
	$result.=checkPassword($iPassword, $iPassword_2);
	$result.=checkEmail($iEmail);
	if($result)
	{
		showError($result);
	}
    $iPassword=md5($iPassword);
    $query="INSERT INTO `$i_admin` (user, uid, password, email, phone)";
    $query.=" VALUES('$iUser', '0', '$iPassword', '$iEmail', '$iPhone')";

    return @mysql_query($query) ? 1 : 0;

}
?>
