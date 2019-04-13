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
*\    本页说明: wap添加文章
*\===========================================================================*/

  header("Content-type: text/vnd.wap.wml;");
  echo("<?xml version=\"1.0\" encoding=\"gb2312\"?>\n");
  echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/ DTD/wml_1.1.xml\">\n\n");
?>
<?
  require("../include/config.inc.php");
  require("../include/global-C.inc.php");
  setGlobal();
  global $x,$result;
  $query = "SELECT * FROM `$x[sort]` ORDER BY id DESC";
  $result=mysql_query($query);
  $query1 = "SELECT * FROM `$x[admin]` ORDER BY id DESC";
  $result1=mysql_query($query1);
?>
<?

	echo ("<wml>\n");
 	echo ("<card id=\"editblog\" ordered=\"true\">\n");
	echo ("<p align=\"center\"><b>-添加文章-</b><br/></p>\n");
 	echo ("<p>\n");
	echo ("用户:<select name=\"admin\">\n");
	while($row=mysql_fetch_array($result1))
	{ 
    echo ("<option value=\"$row[user]\">$row[user]</option>\n");
     }
    echo ("</select>\n");
	echo ("密码:<input name=\"password\" type=\"text\" /><br/>\n");
 	echo ("标题:<input name=\"title\" type=\"text\" />\n");
 	echo ("内容:<input name=\"content\" type=\"text\" />\n");
	echo ("分类:<select name=\"sort\">\n");
	while($row=mysql_fetch_array($result))
	{ 
    echo ("<option value=\"$row[id]\">$row[cnName]</option>\n");
     }
    echo ("</select>\n");
	echo ("天气:<select name=\"weather\">\n");
    echo ("<option value=\"sunny\">阳光</option>\n");
    echo ("<option value=\"cloudy\">多云</option>\n");
    echo ("<option value=\"rain\">下雨</option>\n");
    echo ("<option value=\"snow\">下雪</option>\n");
    echo ("</select>\n");
	echo ("置顶:<select name=\"top\">\n");
    echo ("<option value=\"0\">否</option>\n");
    echo ("<option value=\"1\">是</option>\n");
    echo ("</select>\n");
	echo ("隐藏:<select name=\"hidden\">\n");
    echo ("<option value=\"0\">否</option>\n");
    echo ("<option value=\"1\">是</option>\n");
    echo ("</select>\n");
	echo ("</p>\n");
	echo ("<p align=\"center\">\n");
	echo ("<anchor title=\"submit\">确定\n");
 	echo ("<go href=\"addblog.php\" method=\"post\">\n");
	echo ("<postfield name=\"admin\" value=\"$(admin)\" />\n");
 	echo ("<postfield name=\"password\" value=\"$(password)\" />\n");
	echo ("<postfield name=\"title\" value=\"$(title)\" />\n");
 	echo ("<postfield name=\"content\" value=\"$(content)\" />\n");
	echo ("<postfield name=\"sort\" value=\"$(sort)\" />\n");
 	echo ("<postfield name=\"weather\" value=\"$(weather)\" />\n");
 	echo ("<postfield name=\"top\" value=\"$(top)\" />\n");
	echo ("<postfield name=\"hidden\" value=\"$(hidden)\" />\n");
 	echo ("</go></anchor>\n");
	echo ("</p>\n");
 	echo ("</card>\n");
 	echo ("</wml>\n");
?>