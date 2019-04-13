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
*\    本页说明: wap添加blog处理
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
	$admin=$_POST[admin];
	$password=md5($_POST[password]);
	$title=codecheck($_POST[title]);
	$content=codecheck($_POST[content]);
	$sort=codecheck($_POST[sort]);
	$query1 = "SELECT * FROM $x[global]";
	$sql=mysql_query($query1);
	$rows1=mysql_fetch_array($sql);
    	$siteName=$rows1['siteName'];
	$query2=mysql_query("SELECT * FROM `$x[admin]` WHERE user='$admin' AND password='$password'");
	$rows2=mysql_fetch_array($query2);	
	$result=mysql_num_rows($query2);
if($result)
{
	$query3="INSERT INTO `$x[blog]` (title, content, author, email, sort, visits, addtime, weather, top, hidden)";
    	$query3.=" VALUES('$title', '$content', '$admin', '$rows2[email]', '$sort', '0', now(), '$_POST[weather]', '$_POST[top]', '$_POST[hidden]')";
	$result2=mysql_query($query3);
	if($result2)
	{
 echo ("<wml>\n");
 echo ("<card id=\"success\" ontimer=\"index.php\">\n");
 echo ("<timer value=\"30\" />\n");
 echo ("<p align=\"center\"><b>$siteName</b><br/></p>"); 
 echo ("<p align=\"center\">添加blog成功！3秒后返回首页\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n"); 
 echo ("Powered by Exblog\n");
 echo ("</p>\n");
 echo ("</card> </wml>\n");
	}
	else
	{
	echo ("<wml>\n");
 	echo ("<card id=\"error\" ontimer=\"editblog.php\">\n");
	echo ("<timer value=\"30\" />\n"); 
 	echo ("<p>\n");
 	echo ("添加文章失败！3秒后返回\n");
 	echo ("</p>\n");
 	echo ("</card></wml>\n");
	}
}
else
{
 echo ("<wml>\n");
 echo ("<card id=\"error\" ontimer=\"editblog.php\">\n");
 echo ("<timer value=\"30\" />\n"); 
 echo ("<p align=\"center\"><b>$siteName</b><br/></p>"); 
 echo ("<p>\n");
 echo ("用户名或密码错误！3秒后返回\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n"); 
 echo ("Powered by Exblog\n");
 echo ("</p>\n");
 echo ("</card></wml>\n");
}
?>
