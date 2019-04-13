<?php
/*============================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\----------------------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
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
<?php
  require("../include/config.inc.php");
  require("../include/global-A.inc.php");
  require("../include/global-C.inc.php");
  setGlobal();
  global $x;
	$query = "SELECT * FROM $x[global]";
	$sql=mysql_query($query);
	$rows=mysql_fetch_array($sql);
    	$siteName=$rows['siteName'];
  $query1 = "SELECT * FROM $x[blog] WHERE hidden='0' ORDER BY id DESC LIMIT 0,5";
  $result=mysql_query($query1);
?>
<?
 echo ("<wml>\n");
 echo ("<card id=\"article\">\n");
 echo ("<p align=\"center\"><b>$siteName</b><br/></p>");
 echo ("<p align=\"center\"><b>-浏览文章-</b><br/></p>");
?>
<?php
	$i=1;
 while($rows=mysql_fetch_array($result))
{
	$len=strlen($rows['title']);
	if($len<=80)
	{
		$title=$rows['title'];
	}
	else
	{
		$title=substr($rows['title'],0,80).chr(0);
		$title=$title."...";
	}
$time=substr($rows['addtime'],0,10);
$id=$rows['id'];
 echo ("<p>\n");
 echo ("<b>$i&nbsp;</b><a href=\"query.php?id=$id\">$title</a>$time<br/>\n");
 echo ("</p>\n");
	$i=$i+1;
}
?>
<?
 echo ("<p align=\"center\">\n"); 
 echo ("Powered by Exblog\n");
 echo ("</p>\n");
 echo ("</card> </wml>\n");
?>