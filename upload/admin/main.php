<?php
/*============================================================================
*\    exBlogMix Ver: 1.2.0  exBlogMix 网络日志(PHP+MYSQL) 1.2.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
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

checkLogin();      ### 检查用户是否非法登录

/* 检查安装文件信息 */
if($_GET['action'] == "delInstall")
{
	chmod("../admin",0775);
	chmod("../admin/install.php",0777);
	unlink("../admin/install.php");
	echo "<font color=red>install.php文件成功删除!</font>";
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="elliott,hunter">
<meta name="keywords" content="elliott,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by elliott">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>{title}</title>
<link href="../images/admin.css" rel="stylesheet" type="text/css">
</head>

<body class="main">
<?
    if($safe)
	{
		echo "<center>$safe</center>";
	}	
?>
<table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="a2">
<tr>
<td class="a1" colspan=2 height=25 align="center"><b>系统信息统计</b></td>
</tr>
<tr class=a4>
    <td height=23 colspan=2><b>- 系统信息:</b>&nbsp;Blog数- <? echo $blogNum; ?> 评论数- 
      <? echo $commentNum; ?> 访问数- <? echo $visited; ?> 数据库占用大小- <? echo $dbSize; ?> 
      KB 程序至今运行了- <? echo $sysRunDay; ?> 天</td>
</tr>
<tr class=a4>
<td height=23 colspan=2><b>- 程序环境信息:</b></td>
</tr>
<tr class=a4>
<td width="20%" height=23 align="center">服务器环境:</td>
<td width="80%">&nbsp;<? echo $server[0] ?></td>
</tr>
<tr class=a4>
<td width="20%" height=23 align="center">MySQL版本:</td>
<td width="80%">&nbsp;<? echo $sqlinfo[0] ?></td>
</tr>
</table>

<br>
<table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="a2">
  <tr>
    <td class="a1" colspan=2 height=25 align="center"><b>开发团队 - ExSoft</b></td>
  </tr>
  <tr class=a4>
    <td height=23 colspan=2><b>- 原始创作人员: </b></td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center">程序设计:</td>
    <td width="80%">&nbsp;elliott</td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center">美工设计:</td>
    <td width="80%">&nbsp;Hunter</td>
  </tr>  
  <tr class=a4>
    <td height=23 colspan=2><b>- 成员协作分工:</b></td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center"><strong>elliott</strong></td>
    <td width="80%">&nbsp; 负责程序开发、Bugs修复 </td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center"><strong>Hunter</strong></td>
    <td width="80%">&nbsp; 首席美工,负责程序skin的编写,界面美化</td>
  </tr>
   <tr class=a4>
    <td width="20%" height=23 align="center"><strong>Viva</strong></td>
    <td width="80%">&nbsp; 负责程序的发布、Studio工作的整体协调、在线技术支持、问题解答、宣传工作</td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center"><strong>流水流云</strong></td>
    <td width="80%">&nbsp; 负责程序测试、意见统筹并协助elliott修复Bugs </td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center"><strong>Tomy</strong></td>
    <td width="80%">&nbsp; 负责协助elliott完善程序代码,及在线技术支持、问题解答</td>
  </tr>
</table>
<br>
<table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="a2">
  <tr>
    <td class="a1" colspan=2 height=25 align="center"><b>技术支持信息</b></td>
  </tr>
  <tr class=a4>
    <td height=23 colspan=2>　一个程序难免有BUG,如果你发现任何BUG,请进入程序支持网站报告BUG. <span class="style1"><font color="#FF0000">我们的进步离不开您的帮助</font></span>.</td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center">Support WEB :</td>
    <td width="80%">&nbsp;<a href="http://www.fengling.net/">http://www.fengling.net/</a></td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center">Support BBS :</td>
    <td width="80%">&nbsp; <a href="http://bbs.fengling.net/">风灵之都论坛</a></td>
  </tr>
  <tr class=a4>
    <td width="20%" height=23 align="center">Support Mail :</td>
    <td width="80%">&nbsp; <a href="mailto:exblog@fengling.net">exblog@fengling.net</a> <a href="mailto:exblog@yeah.net">exblog@yeah.net</a></td>
  </tr>
</table>
</body>
</html>
