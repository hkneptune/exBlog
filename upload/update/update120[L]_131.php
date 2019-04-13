<?php
/**
 *  exBlog 1.2.0_[L]  [to] exBlog 1.3.1 升级程序
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

	$query  = "ALTER TABLE `$x[global]` ADD `GDswitch` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `exurlon` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `sitekeyword` VARCHAR(100) DEFAULT 'exSoft,exBlog,exBook,Weblog,blog,',";
	$query .= "ADD `langURL` VARCHAR(40) DEFAULT 'language/chinese_gb2312',";
	$query .= "ADD `summarynum` INT(5) DEFAULT '450',";
	$query .= "ADD `alltitlenum` INT(5) DEFAULT '18',";
	$query .= "ADD `listblognum` INT(5) DEFAULT '6',";
	$query .= "ADD `listallnum` INT(5) DEFAULT '20'";

    @mysql_query($query)
    ? $result.="更改表结构 `$x[global]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[global]` 失败! ...... <font color='red'>×</font><br />";
	
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

	$query  = "ALTER TABLE `$x[blog]` ADD `keyword` VARCHAR(100) AFTER `addtime`,";
	$query .= "ADD `nocom` ENUM( '0', '1' ) DEFAULT '0' NOT NULL,";
	$query .= "ADD `summarycontent` TEXT AFTER `keyword`";
    @mysql_query($query)
    ? $result.="更改表结构 `$x[blog]` 成功!...... <font color='blue'>√</font><br />"
    : $result.="更改表结构 `$x[blog]` 失败! ...... <font color='red'>×</font><br />";

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

/* 创建exblog_upload表 */
function CreateUpload()
{
	global $x;
 
    $query="DROP TABLE IF EXISTS $x[upload];";
    @mysql_query($query)
    ? $result.="重置表 `$x[upload]` 成功!...... <font color='blue'>$lang[69]</font><br />"
    : $result.="重置表 `$x[upload]` 失败! ...... <font color='red'>$lang[70]</font><br />";

    $query="CREATE TABLE `$x[upload]` (";
    $query.="max_file_size int(8) default NULL,";
    $query.="destination_folder varchar(40) default NULL,";
    $query.="up_type varchar(255) default NULL)";
    @mysql_query($query)
    ? $result.="新建表 `$x[upload]` 成功!...... <font color='blue'>$lang[69]</font><br />"
    : $result.="新建表 `$x[upload]` 失败! ...... <font color='red'>$lang[70]</font><br />";

    $query="INSERT INTO `$x[upload]` (max_file_size,destination_folder,up_type)";
    $query.=" VALUES('2000000', 'upload/', 'gif,jpg,png,zip,rar,txt')";
    @mysql_query($query)
    ? $result.="初始化 `$x[upload]` 成功!...... <font color='blue'>$lang[69]</font><br />"
    : $result.="初始化 `$x[upload]` 失败! ...... <font color='red'>$lang[70]</font><br />";

	return $result;

}

/* 创建exblg_keyword表 */
function CreateKeyword()
{
	global $x;
 
    $query="DROP TABLE IF EXISTS $x[keyword];";
    @mysql_query($query)
    ? $result.="$lang[64] `$x[keyword]` 成功!...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[64] `$x[keyword]` 失败! ...... <font color='red'>$lang[70]</font><br />";

    $query  = "CREATE TABLE `$x[keyword]` (";
    $query .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    $query .= "word VARCHAR(100) NOT NULL,";
    $query .= "content VARCHAR(255) NOT NULL,";
    $query .= "url VARCHAR(150))";
    @mysql_query($query)
    ? $result.="$lang[65] `$x[keyword]` 成功!...... <font color='blue'>$lang[69]</font><br />"
    : $result.="$lang[65] `$x[keyword]` 失败! ...... <font color='red'>$lang[70]</font><br />";

	$query="INSERT INTO `$x[keyword]` (word, content)";
	$query.=" VALUES('exBlog','HOME: http://www.exBlog.net')";
    @mysql_query($query)
    ? $result.="初始化 `$x[keyword]` 成功!...... <font color='blue'>$lang[69]</font><br />"
    : $result.="初始化 `$x[keyword]` 失败! ...... <font color='red'>$lang[70]</font><br />";

	return $result;
    
}

/* 调整用户 */
function usergo()
{
	global $x;
    $query="SELECT * FROM `$x[user]`";
    if ($result11=@mysql_query($query) ){
      $result.="";
    }else{
      $result.="调整 用户 失败! ...... <font color='red'>×</font><br />";
    }
    while($row=mysql_fetch_array($result11))
    {
	$sql=mysql_query("SELECT * FROM `$x[admin]` WHERE user='$row[name]'");
	$num=mysql_num_rows($sql);

	if($num == 0)
	{
	    $query="INSERT INTO `$x[admin]` (uid, user, password, email)";
	    $query.=" VALUES('3','$row[name]','$row[password]','$row[email]')";
    @mysql_query($query)
    ? $result.="调整 用户 $row[name] 成功!...... <font color='blue'>√</font><br />"
    : $result.="调整 用户 $row[name] 失败! ...... <font color='red'>×</font><br />";
	}
    }
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

$ret .= alterGlobal();
$ret .= alterBlog();
$ret .= createTrackback();
$ret .= CreateUpload();
$ret .= CreateKeyword();
$ret .= usergo();
$ret .= sortgo();

echo $ret;

?>
