<?php
/*=================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 框架右页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/main.php");

checkLogin();      ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 1);  ### 检查用户权限

/* 检查安装文件信息 */
if($_GET['action'] == "delInstall")
{
	chmod("../admin",0775);
	chmod("../admin/install.php",0777);
	unlink("../admin/install.php");
	echo "$langadfun[17]";
	//delInstall();
}
$server=getServerInfo();
$sqlinfo=getMysqlInfo();
$safe=installExists();

InfoQuery();  //取得BLOG数,评论数,访问数

$dbSize = getDBsize();
$sysRunDay = getSysRunTime();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>

<body class="main" dir="<?php echo $langpublic[dir]; ?>">
<table cellpadding="4" cellspacing="1" border="0" width="100%" align=center class="border">
<tr bgcolor="#99CC66">
<td bgcolor="#FFFFFF" class="main"><br>
<?
    if($safe)
	{
		echo $safe;
	}	
?>
	<br>
      <table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
        <tr> 
          <td colspan="2" class="menu" align="center"><b><?php echo $lang[0]; ?></b></td>
</tr>
<tr>
    <td height=23 colspan=2><b><?php echo $lang[1]; ?></b></td>
</tr>
<tr>
<td width="20%" height=23></td>
<td width="80%"><?php echo $lang[2]; ?>- <? echo $blogNum; ?> <?php echo $lang[3]; ?>- <? echo $commentNum; ?> <?php echo $lang[4]; ?>- <? echo $tbNum; ?> <?php echo $lang[1]; ?>访问数- <? echo $visited; ?> <br />
	<?php echo $lang[5]; ?>- <? echo $dbSize; ?> KB <?php echo $lang[6]; ?>- <? echo $sysRunDay; ?> <?php echo $lang[7]; ?></td>
</tr>
<tr>
<td height=23 colspan=2><b>- <?php echo $lang[8]; ?></b></td>
</tr>
<tr>
<td width="20%" height=23 align="center"><?php echo $lang[9]; ?></td>
<td width="80%">&nbsp;<? echo $server[0] ?></td>
</tr>
<tr>
<td width="20%" height=23 align="center"><?php echo $lang[10]; ?></td>
<td width="80%">&nbsp;<? echo $sqlinfo[0] ?></td>
</tr>
</table>

	<hr size="0" noshade width="90%">
<table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="main">
  <tr>
    <td class="menu" colspan=4 height=25 align="center"><b><?php echo $lang[11]; ?></b></td>
  </tr>
    <tr>
    <td height=23 colspan=4><b>- [项目经理]</b></td>
  </tr>
  <tr>
    <td width="15%" height=23 align="center"><strong>netxiong</strong></td>
    <td width="35%">&nbsp;Manager - 01</td>
    <td width="15%" height=23 align="center"><strong>Viva</strong></td>
    <td width="35%">&nbsp;Manager - 02</td>
  </tr>
  <tr>
    <td height=23 colspan=4><b>- [代码部] - 招人ing..</b></td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>elliott</strong></td>
    <td>&nbsp;Coder - 01</td>
    <td width="14%" height=23 align="center"><strong>Tomy</strong></td>
    <td>&nbsp;Coder - 02</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>Sheer</strong></td>
    <td>&nbsp;Coder - 03</td>
    <td width="14%" height=23 align="center"><strong>流水流云</strong></td>
    <td>&nbsp;Coder - 04</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>Anthrax</strong></td>
    <td>&nbsp;Coder - 05</td>
    <td width="14%" height=23 align="center"><strong>&nbsp;</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height=23 colspan=4><b>- [美工部] - 招人ing.. </b></td>
  </tr>    
  <tr>
    <td width="14%" height=23 align="center"><strong>Hunter</strong></td>
    <td>&nbsp;Skiner - 01</td>
    <td width="14%" height=23 align="center"><strong>madman</strong></td>
    <td>&nbsp;Skiner - 02</td>
  </tr>
  <tr>
    <td height=23 colspan=4><b>- [文档部] </b></td>
  </tr>
   <tr>
    <td width="14%" height=23 align="center"><strong>Tomy</strong></td>
    <td>&nbsp;Documenter - 01</td>
    <td width="14%" height=23 align="center"><strong>Viva</strong></td>
    <td>&nbsp;Documenter - 02</td>
  </tr>
    <tr>
    <td height=23 colspan=4><b>- [测试部] - 招人ing..</b></td>
  </tr>
    <tr>
    <td width="14%" height=23 align="center"><strong>BBS</strong></td>
    <td colspan=3><a href="http://bbs.exBlog.net/">http://bbs.exBlog.net/</a></td>
  </tr>
</table>
	<hr size="0" noshade width="90%">
<table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
  <tr>
    <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[12]; ?></b></td>
  </tr>
  <tr>
    <td height=23 colspan=2><?php echo $lang[13]; ?></td>
  </tr>
  <tr>
    <td width="20%" height=23 align="center">Support WEB :</td>
    <td width="80%">&nbsp; <a href="http://www.exBlog.net/">http://www.exBlog.net/</a></td>
  </tr>
  <tr>
    <td width="20%" height=23 align="center">Support BBS :</td>
    <td width="80%">&nbsp; <a href="http://bbs.exblog.net/"><?php echo $lang[14]; ?></a></td>
  </tr>
  <tr>
    <td width="20%" height=23 align="center">Support Mail :</td>
    <td width="80%">&nbsp; <a href="mailto:support@exblog.net">support@exblog.net</a> <a href="mailto:support@exblog.net">support@exblog.net</a></td>
  </tr>
</table></td>
  </tr>
</table>
</body>
</html>
