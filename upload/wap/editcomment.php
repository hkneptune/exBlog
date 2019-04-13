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
*\    本页说明: wap添加评论
*\===========================================================================*/

  header("Content-type: text/vnd.wap.wml;");
  echo("<?xml version=\"1.0\" encoding=\"gb2312\"?>\n");
  echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/ DTD/wml_1.1.xml\">\n\n");
?>
<?
	$id=$_GET['id'];
	echo ("<wml>\n");
 	echo ("<card id=\"editcomment\" ordered=\"true\">\n");
	echo ("<p align=\"center\"><b>-发表评论-</b><br/></p>\n");
 	echo ("<p>\n");
 	echo ("用户*:<input name=\"admin\" type=\"text\" /><br/>\n");
 	echo ("密码:<input name=\"password\" type=\"text\" emptyok=\"true\" /><br/>\n");
	echo ("E-mail:<input name=\"email\" type=\"text\" emptyok=\"true\" /><br/>\n");
	echo ("主页:<input name=\"homepage\" type=\"text\" emptyok=\"true\" /><br/>\n");
	echo ("QQ:<input name=\"qq\" type=\"text\" emptyok=\"true\" /><br/>\n");
  	echo ("内容*:<input name=\"content\" type=\"text\" /><br/>\n");
	echo ("提示：带*标记的为必填项\n");
	echo ("</p>\n");
	echo ("<p align=\"center\">\n");
	echo ("<anchor title=\"submit\">确定\n");
 	echo ("<go href=\"addcomment.php?id=$id\" method=\"post\">\n");
	echo ("<postfield name=\"admin\" value=\"$(admin)\" />\n");
 	echo ("<postfield name=\"password\" value=\"$(password)\" />\n");
	echo ("<postfield name=\"email\" value=\"$(email)\" />\n");
 	echo ("<postfield name=\"homepage\" value=\"$(homepage)\" />\n");
 	echo ("<postfield name=\"qq\" value=\"$(qq)\" />\n");
 	echo ("<postfield name=\"content\" value=\"$(content)\" />\n");
 	echo ("</go></anchor>\n");
	echo ("</p>\n");
 	echo ("</card>\n");
 	echo ("</wml>\n");
?>
