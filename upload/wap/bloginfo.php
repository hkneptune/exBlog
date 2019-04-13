<?php
/*============================================================================
*\    exBlog Ver: 1.3.0 exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: wap网站信息
*\===========================================================================*/
  header("Content-type: text/vnd.wap.wml");
  echo("<?xml version=\"1.0\" encoding=\"gb2312\"?>\n");
  echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/ DTD/wml_1.1.xml\">\n\n");
  require("../include/config.inc.php");
  require("../include/global-C.inc.php");
  setGlobal();
  global $x;
  $sql1=mysql_query("SELECT * FROM `$x[visits]`");
	$tmp=mysql_fetch_array($sql1);
	$visited=$tmp['visits'];           
	$todayVisits=$tmp['todayVisits'];
  $sql2=mysql_query("SELECT id FROM `$x[comment]`");
	$commentNum=mysql_num_rows($sql2);  
  $sql3=mysql_query("SELECT id FROM `$x[blog]`");
	$blogNum=mysql_num_rows($sql3); 
?>
<?
 echo ("<wml>\n");
 echo ("<card id=\"showinfo\">\n");
 echo ("<p align=\"center\"><b>$siteName</b><br/></p>");
 echo ("<p align=\"center\"><b>-网站信息-</b><br/></p>");
 echo ("<p>共有BLOG:$blogNum\n");
 echo ("</p>\n");
 echo ("<p>共有评论:$commentNum\n");
 echo ("</p>\n");
 echo ("<p>共访问数:$visited\n");
 echo ("</p>\n");
 echo ("<p>今日访问:$todayVisits\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n"); 
 echo ("Powered by Exblog\n");
 echo ("</p>\n");
 echo ("</card></wml>\n");

?>
   
