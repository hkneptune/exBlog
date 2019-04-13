<?php
/*============================================================================
*\    exBlogMix Ver: 1.2.0  exBlogMix 网络日志(PHP+MYSQL) 1.2.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 创建MySql数据库表格
*\===========================================================================*/

/* 创建exblog_blog表 */
function CreateAdmin($i)
{   
    global $result;
	$i_admin="$i"."admin";

    $query="CREATE TABLE `$i_admin` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="uid INT(1) UNSIGNED NOT NULL,";
    $query.="user VARCHAR(20) NOT NULL,";
    $query.="password VARCHAR(33) NOT NULL,";
    $query.="email VARCHAR(35) NOT NULL)";

    @mysql_query($query) or die("创建 `$i_admin` 失败! ...... <font color='red'>X</font><br>");
    $result="创建表 `$i_admin` 成功...... <font color='red'>√</font><br>";
}

/* 创建exblog_visits表 */
function CreateVisits($i)
{
    global $result;
	$i_visits="$i"."visits";

    $curDate=date("Y-m-d");
    $query="CREATE TABLE `$i_visits` (";
    $query.="visits INT(20) UNSIGNED,";
	$query.="currentDate VARCHAR(20),";
	$query.="todayVisits INT(5))";
    @mysql_query($query) or die("创建 `$i_visits` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_visits` 成功...... <font color='red'>√</font><br>";
    $query="INSERT INTO `$i_visits` (visits,currentDate,todayVisits)";
    $query.=" VALUES('0','$curDate','0')";
    @mysql_query($query) or die("初始化 `$i_visits` 失败 ...... <font color='red'>X</font><br>");
    $result.="初始化 `$i_visits` 成功...... <font color='red'>√</font><br>";	
}

/* 创建 exblog_announce 公告栏表 */
function createAnnounce($i)
{
	global $result;
	$i_announce="$i"."announce";

	$query="CREATE TABLE `$i_announce` (";
	$query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$query.="title VARCHAR(40),";
	$query.="content TEXT,";
	$query.="author VARCHAR(20),";
	$query.="email VARCHAR(35),";
	$query.="addtime DATETIME)";
	//@mysql_query($query) or die("创建 `$i_announce` 失败 ...... <font color='red'>X</font><br>");
    mysql_query($query) or die(mysql_error());
	$result.="初始化 `$i_announce` 成功...... <font color='red'>√</font><br>";	
}


/* 创建文章分类 exblog_weather 表 */
function CreateWeather($i)
{
    global $result;
	$i_weather="$i"."weather";

    $query="CREATE TABLE `$i_weather` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="enName VARCHAR(6) NOT NULL,";
    $query.="cnName VARCHAR(20) NOT NULL)";
    @mysql_query($query) or die("创建表 `$i_weather` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_weather` 成功...... <font color='red'>√</font><br>";

	$query  = "INSERT INTO `$i_weather` (id, enName, cnName)";
	$query .= " VALUES(\"1\", \"null\", \"请选择天气\")";
	@mysql_query($query) or die("初始化 `$i_weather` 失败 ...... <font color='red'>X</font><br>");
	$query  = "INSERT INTO `$i_weather` (id, enName, cnName)";
	$query .= " VALUES(\"2\", \"sunny\", \"阳光\")";
	@mysql_query($query) or die("初始化 `$i_weather` 失败 ...... <font color='red'>X</font><br>");
	$query  = "INSERT INTO `$i_weather` (id, enName, cnName)";
	$query .= " VALUES(\"3\", \"cloudy\", \"多云\")";
	@mysql_query($query) or die("初始化 `$i_weather` 失败 ...... <font color='red'>X</font><br>");
	$query  = "INSERT INTO `$i_weather` (id, enName, cnName)";
	$query .= " VALUES(\"4\", \"rain\", \"下雨\")";
	@mysql_query($query) or die("初始化 `$i_weather` 失败 ...... <font color='red'>X</font><br>");
	$query  = "INSERT INTO `$i_weather` (id, enName, cnName)";
	$query .= " VALUES(\"5\", \"snow\", \"下雪\")";
	@mysql_query($query) or die("初始化 `$i_weather` 失败 ...... <font color='red'>X</font><br>");	
	$result .= "初始化 `$i_weather` 成功...... <font color='red'>√</font><br>";
}
/* 创建文章类别 exblog_sort表 */
function CreateSort($i)
{
    global $result;
	$i_sort="$i"."sort";

    $query="CREATE TABLE `$i_sort` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="enName VARCHAR(20) NOT NULL,";
    $query.="cnName VARCHAR(20) NOT NULL,";
    $query.="description VARCHAR(50))";
    @mysql_query($query) or die("创建表 `$i_sort` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_sort` 成功...... <font color='red'>√</font><br>";

	$query  = "INSERT INTO `$i_sort` (enName, cnName, description)";
	$query .= " VALUES(\"default\", \"默认栏目\", \"此栏目为系统默认栏目\")";
	@mysql_query($query) or die("初始化 `$i_sort` 失败 ...... <font color='red'>X</font><br>");
	$result .= "初始化 `$i_sort` 成功...... <font color='red'>√</font><br>";
}
/* 创建文章 exblog_blog 表 */
function CreateBlog($i)
{
    global $result;
	$i_article="$i"."blog";

    $query="CREATE TABLE `$i_article` (";
    $query.="id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query.="sort VARCHAR(20) NOT NULL,";
    $query.="title VARCHAR(50) NOT NULL,";
    $query.="content TEXT NOT NULL,";
    $query.="author VARCHAR(20),";
	$query.="email VARCHAR(35),";
    $query.="visits INT(10),";
    $query.="addtime VARCHAR(20),";
	$query .= "weather VARCHAR(20),";
	$query .= "top ENUM('0', '1') DEFAULT '0' NOT NULL)";
    @mysql_query($query) or die("创建表 `$i_article` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_article` 成功...... <font color='red'>√</font><br>";
}

/* 创建exblg_comment表 */
function CreateComment($i)
{
    global $result;
	$i_comment="$i"."comment";

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
    @mysql_query($query) or die("创建 `$i_comment` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_comment` 成功...... <font color='red'>√</font><br>";
}

/* 创建exblg_Links表 */
function CreateLinks($i)
{
    global $result;
	$i_links="$i"."links";

    $query  = "CREATE TABLE `$i_links` (";
    $query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query .= "homepage VARCHAR(20) NOT NULL,";
	$query .= "logoURL VARCHAR(150),";
    $query .= "url VARCHAR(150) NOT NULL,";
    $query .= "description VARCHAR(100),";
    $query .= "visits INT(10),";
	$query .= "visible ENUM('0', '1'))";
    @mysql_query($query) or die("创建 `$i_links` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_links` 成功...... <font color='red'>√</font><br>";

	$query="INSERT INTO `$i_links` (homepage, logoURL, url, description, visits, visible)";
	$query.=" VALUES('exBlogMix\'s Home','http://sachxp.51.net/images/logo.gif','http://www.fengling.net','exBlogMix 官方主页', '0', '0')";
	@mysql_query($query) or die("初始化 `$i_links` 失败....... <font color='red'>X</font><br>");
	$result.="初始化 `$i_links` 成功...... <font color='red'>√</font><br>";
}

/* 创建exblog_aboutme表 */
function CreateAboutme($i)
{
    global $result;
	$i_aboutme="$i"."aboutme";

    $query="CREATE TABLE `$i_aboutme` (";
    $query.="name VARCHAR(20),";
    $query.="age INT(3),";
    $query.="email VARCHAR(35),";
    $query.="qq INT(12),";
    $query.="icq INT(12),";
    $query.="msn VARCHAR(35),";
    $query.="description TEXT)";
    @mysql_query($query) or die("创建 `$i_aboutme` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_aboutme` 成功...... <font color='red'>√</font><br>";

    $query2="INSERT INTO `$i_aboutme` (name,age,email,qq,icq,msn,description)";
    $query2.=" VALUES('exblog','20','exblog@fengling.net','8318138','0','','欢迎使用exBlog程序,如何任何问题请在主页或来信提出,谢谢!!')";
    @mysql_query($query2) or die("初始化 `$i_aboutme` 失败 ...... <font color='red'>X</font><br>");
    $result.="初始化 `$i_aboutme` 成功...... <font color='red'>√</font><br>";
}

/* 创建exblog_photo表 */
function CreatePhoto($i)
{
    global $result;
	$i_photo="$i"."photo";

    $query="CREATE TABLE `$i_photo` (";
    $query.="max_file_size int(8) default NULL,";
    $query.="destination_folder varchar(40) default NULL,";
    $query.="watermark int(1) default NULL,";
    $query.="waterposition int(1) default NULL,";
    $query.="waterstring varchar(20) default NULL)";
    @mysql_query($query) or die("创建 `$i_photo` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_photo` 成功...... <font color='red'>√</font><br>";
    $query2="INSERT INTO `$i_photo` (max_file_size,destination_folder,watermark,waterposition,waterstring)";
    $query2.=" VALUES('2000000', 'uploadimg/', '0', '1', 'kiki.class07.com')";
    @mysql_query($query2) or die("初始化 `$i_photo` 失败 ...... <font color='red'>X</font><br>");
    $result.="初始化 `$i_photo` 成功...... <font color='red'>√</font><br>";
}
/* 创建系统管理员 */
function CreateSystem($iUser, $iPassword, $iPassword_2, $iEmail, $i)
{
	$i_admin="$i"."admin";
	$result=checkUser($iUser);
	$result.=checkPassword($iPassword, $iPassword_2);
	$result.=checkEmail($iEmail);
	if($result)
	{
		showError($result);
	}
    $iPassword=md5($iPassword);
    $query="INSERT INTO `$i_admin` (user, uid, password, email)";
    $query.=" VALUES('$iUser', '0', '$iPassword', '$iEmail')";

    @mysql_query($query) or die("创建系统管理员时失败!");
	$showMsg="恭喜!exblog安装完成!";
	$showReturn="./login.php";
    display($showMsg, $showReturn);
}

/* 创建网站常用信息表 */
function CreateGlobal($i)
{
    global $result;
	$i_global="$i"."global";
	
	$nowTime = date("Y-m-d");

    $query  = "CREATE TABLE `$i_global` (";
    $query .= "siteName VARCHAR(50),";
    $query .= "siteUrl VARCHAR(50),";
    $query .= "copyright VARCHAR(255),";
	$query .= "tmpURL VARCHAR(40),";
	$query .= "activeRun ENUM('0', '1') DEFAULT '1',";
	$query .= "unactiveRunMessage TEXT,";
	$query .= "initTime VARCHAR(10),";
	$query .= "isCountOnlineUser ENUM('0', '1') DEFAULT '0')";
    @mysql_query($query) or die("创建 `$i_global` 失败 ...... <font color='red'>X</font><br>");
    $result.="创建表 `$i_global` 成功...... <font color='red'>√</font><br>";
	
    $query="INSERT INTO `$i_global` (siteName, siteUrl, copyright, tmpURL, activeRun, unactiveRunMessage, initTime, isCountOnlineUser)";
    $query.=" VALUES('exblog ver:1.2.0', 'http://exblog.fengling.net','Copyright &copy; 2004-2005 <a href=\"http://exblog.fengling.net\" target=\"_blank\" class=\"copy\">exblog ver: 1.2.0</a> All Rights Reserved.',";
	$query.="'templates/i7c', '1', '由于XXX原因,本站暂时关闭X天.','$nowTime', '0')";
    @mysql_query($query) or die("初始化 `$i_global` 信息时失败 ...... <font color='red'>X</font><br>");
    $result.="初始化 `$i_global` 成功...... <font color='red'>√</font><br>";
}

/**
 *	新建用户表
 *	@param $i 要新建的表名前缀
 *	@return null
 */
function createUser($i)
{
	global $result;
	$i_user = "$i"."user";

	$query  = "CREATE TABLE `$i_user` (";
	$query .= "id INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,";
	$query .= "name VARCHAR(20) NOT NULL,";
	$query .= "password VARCHAR(33) NOT NULL,";
	$query .= "email VARCHAR(35),";
	$query .= "homepage VARCHAR(40),";
	$query .= "qq INT(12))";

	@mysql_query($query) or die("创建 `$i_user` 失败 ...... <font color='red'>X</font><br>");
	$result.="创建表 `$i_user` 成功...... <font color='red'>√</font><br>";
}

/**
 *	创建在线用户表
 *	@param $i 要新建的表名前缀
 *	@return null
 */
function createOnline($i)
{
	global $result;

	$i_online = $i."online";

	$query  = "CREATE TABLE `$i_online` (";
	$query .= "id INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,";
	$query .= "ip VARCHAR(15) NOT NULL,";
	$query .= "lastLoginTime DATETIME)";

	@mysql_query($query) or die("创建 `$i_online` 失败 ...... <font color='red'>X</font><br>");
	$result.="创建表 `$i_online` 成功...... <font color='red'>√</font><br>";
}

?>