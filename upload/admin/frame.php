<?php
/*=================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在主页提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: 后台框架页面
*\=================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/frame.php");

if($_GET[action] == "exit")
{
	userLogout("../index.php", "admin");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title><?php echo $lang[0]; ?></title>
</head>
<frameset rows="*" cols="210,*" framespacing="0" frameborder="yes" border="0">
  <frame src="list.php" name="leftFrame" frameborder="no" scrolling="auto" noresize>
  <frame src="main.php" name="mainFrame" frameborder="no" scrolling="auto" noresize>
</frameset>
<noframes><body>

</body></noframes>
</html>
