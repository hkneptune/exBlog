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
*\    本页说明: wap查询处理
*\===========================================================================*/
  header("Content-type: text/vnd.wap.wml");
  echo("<?xml version=\"1.0\" encoding=\"gb2312\"?>\n");
  echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/ DTD/wml_1.1.xml\">\n\n");
?>
<?php
  require("../include/config.inc.php");
  require("../include/global-C.inc.php");
  require("../include/ExUbb.inc.php");
  setGlobal();
	global $x;
	$query = "SELECT * FROM $x[global]";
	$sql=mysql_query($query);
	$rows=mysql_fetch_array($sql);
    	$siteName=$rows['siteName'];
	$id=$_GET['id'];
	$query1 = "SELECT * FROM $x[blog] WHERE id='$id'";
	$sql=mysql_query($query1);
	$rows=mysql_fetch_array($sql);
    	$title=$rows['title'];
	$content=clearUbb($rows['content']);
	$author=$rows['author']; 
?>
<?
 echo ("<wml>\n");
 echo ("<card id=\"showarticle\">\n");
 echo ("<p align=\"center\"><b>$siteName</b><br/></p>");
 echo ("<p align=\"center\"><b>-浏览全文-</b><br/></p>");
 echo ("<p>标题：$title\n");
 echo ("</p>\n");
 echo ("<p>作者：$author\n");
 echo ("</p>\n");
 echo ("<p>内容：$content\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n");
 echo ("<a href=\"querycomment.php?id=$id\">查看评论</a><br/>\n");
 echo ("<a href=\"editcomment.php?id=$id\">发表评论</a>\n");
 echo ("</p>\n");
 echo ("<p align=\"center\">\n"); 
 echo ("Powered by Exblog\n");
 echo ("</p>\n");
 echo ("</card> </wml>\n");

?>
 
