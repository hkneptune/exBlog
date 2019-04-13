<?
/*============================================================================
*\    exBlog Ver: 1.3.0 exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: wap首页显示
*\===========================================================================*/

  header("Content-type: text/vnd.wap.wml");
  echo("<?xml version=\"1.0\" encoding=\"gb2312\"?>\n");
  echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/ DTD/wml_1.1.xml\">\n\n");
?>
<?
  require("../include/config.inc.php");
  require("../include/global-C.inc.php");
  setGlobal();
	global $x;
	$query = "SELECT * FROM $x[global]";
	$sql=mysql_query($query);
	$rows=mysql_fetch_array($sql);
    	$siteName=$rows['siteName'];
?>
<?
 echo ("<wml>\n");
 echo ("<card id=\"article\">\n");
 echo ("<p align=\"center\"><b>$siteName</b><br/></p>");
 echo ("<p align=\"center\">\n");
 echo ("<a href=\"article.php\">浏览文章</a>\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n");
 echo ("<a href=\"editblog.php\">添加文章</a>\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n");
 echo ("<a href=\"bloginfo.php\">站点信息</a>\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n");
 echo ("<a href=\"admininfo.php\">站长信息</a>\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n"); 
 echo ("Powered by Exblog\n");
 echo ("</p>\n");
 echo ("</card></wml>\n");
 ?>