<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L] exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: wap添加comment处理
*\===========================================================================*/
  header("Content-type: text/vnd.wap.wml");
  echo("<?xml version=\"1.0\" encoding=\"gb2312\"?>\n");
  echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/ DTD/wml_1.1.xml\">\n\n");
?>
<?php
  require("../include/config.inc.php");
  include("utftogb.php");
  require("../include/global-C.inc.php");
function codecheck($chs)
{
	if(ord(substr($chs,0,1))>0xE0){
	$chs = new Chinese("UTF8","GB2312","$chs");
	$chs = $chs->ConvertIT();
		return $chs;
		}else{
		return $chs;
		}
}
	setGlobal();
	global $x;
	$id=$_GET['id'];
	$author=codecheck($_POST[admin]);
	$password=$_POST[password];
	$email=$_POST[email];
	$homepage=$_POST[homepage];
	$qq=$_POST[qq];
	$content=codecheck($_POST[content]);
	$query1 = "SELECT * FROM $x[global]";
	$sql=mysql_query($query1);
	$rows1=mysql_fetch_array($sql);
    	$siteName=$rows1['siteName'];
if($password == "")
{
$query2 = "SELECT name FROM `$x[user]` WHERE name = '$author'";
$result = mysql_query($query2);
	if(mysql_num_rows($result))
	{
	echo ("<wml>\n");
 	echo ("<card id=\"error\" ontimer=\"editcomment.php\">\n");
	echo ("<timer value=\"30\" />\n"); 
 	echo ("<p>\n");
 	echo ("你是已注册用户,请填写密码！3秒后返回\n");
 	echo ("</p>\n");
 	echo ("</card></wml>\n");
	}
	else
	{
	$query3="INSERT INTO `$x[comment]` (commentID, commentSort, author, email, homepage, qq, content, addtime)";
	$query3.=" VALUES('$id', '', '$author', '$email', '$homepage', '$qq', '$content', NOW())";
		if(mysql_query($query3))
		{
 		echo ("<wml>\n");
 		echo ("<card id=\"success\" ontimer=\"index.php\">\n");
 		echo ("<timer value=\"30\" />\n");
 		echo ("<p align=\"center\"><b>$siteName</b><br/></p>"); 
 		echo ("<p align=\"center\">添加评论成功！3秒后返回首页\n");
 		echo ("</p>\n");
 		echo ("</card> </wml>\n");
		}
		else
		{
		echo ("<wml>\n");
 		echo ("<card id=\"error\" ontimer=\"editcomment.php\">\n");
		echo ("<timer value=\"30\" />\n"); 
 		echo ("<p>\n");
 		echo ("添加评论失败！3秒后返回\n");
 		echo ("</p>\n");
 		echo ("</card></wml>\n");
		}
	}
}
else
{
 $password=md5($password);
 $query2 = "SELECT * FROM `$x[user]` WHERE name = '$author' AND password = '$password'";
 $result = mysql_query($query2);
	if(!mysql_num_rows($result))
	{
	echo ("<wml>\n");
 	echo ("<card id=\"error\" ontimer=\"editcomment.php\">\n");
	echo ("<timer value=\"30\" />\n"); 
 	echo ("<p>\n");
 	echo ("用户名或密码错误！3秒后返回\n");
 	echo ("</p>\n");
 	echo ("</card></wml>\n");
	}
	else
	{
	$query3="INSERT INTO `$x[comment]` (commentID, commentSort, author, email, homepage, qq, content, addtime)";
	$query3.=" VALUES('$id', '', '$author', '$email', '$homepage', '$qq', '$content', NOW())";
		if(mysql_query($query3))
		{
 		echo ("<wml>\n");
 		echo ("<card id=\"success\" ontimer=\"index.php\">\n");
 		echo ("<timer value=\"30\" />\n");
 		echo ("<p align=\"center\"><b>$siteName</b><br/></p>"); 
 		echo ("<p align=\"center\">添加评论成功！3秒后返回首页\n");
 		echo ("</p>\n");
 		echo ("</card> </wml>\n");
		}
		else
		{
		echo ("<wml>\n");
 		echo ("<card id=\"error\" ontimer=\"editcomment.php\">\n");
		echo ("<timer value=\"30\" />\n"); 
 		echo ("<p>\n");
 		echo ("添加评论失败！3秒后返回\n");
 		echo ("</p>\n");
 		echo ("</card></wml>\n");
		}
	}
}
?>

