<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L]  exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
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
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>

<body class="main">
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
          <td colspan="2" class="menu" align="center"><b>系统信息统计</b></td>
</tr>
<tr>
    <td height=23 colspan=2><b>- 系统信息:</b></td>
</tr>
<tr>
<td width="20%" height=23 align="center" colspan=2>Blog数- <? echo $blogNum; ?> 评论数- 
      <? echo $commentNum; ?> 访问数- <? echo $visited; ?> 数据库占用大小- <? echo $dbSize; ?> 
      KB 程序至今运行了- <? echo $sysRunDay; ?> 天</td>
</tr>
<tr>
<td height=23 colspan=2><b>- 程序环境信息:</b></td>
</tr>
<tr>
<td width="20%" height=23 align="center">服务器环境:</td>
<td width="80%">&nbsp;<? echo $server[0] ?></td>
</tr>
<tr>
<td width="20%" height=23 align="center">MySQL版本:</td>
<td width="80%">&nbsp;<? echo $sqlinfo[0] ?></td>
</tr>
</table>

	<hr size="0" noshade width="90%">
<table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="main">
  <tr>
    <td class="menu" colspan=4 height=25 align="center"><b>开发团队 - exSoft</b></td>
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
    <td colspan="3">&nbsp;Coder - 01 负责程序开发、Bugs 修复程序原创作者</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>Tomy:</strong></td>
    <td colspan="3">&nbsp;Coder - 02 负责协助 elliott 完善程序代码，升级文档编写</td>
  </tr>
   <tr>
    <td width="14%" height=23 align="center"><strong>Sheer:</strong></td>
    <td colspan="3">&nbsp;Coder - 03 负责 1.2.0 RC1 及后续版本开发，Bugs修复</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>流水流云:</strong></td>
    <td colspan="3">&nbsp;Coder - 04  负责程序测试、意见统筹及Bugs修复</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>Anthrax:</strong></td>
    <td colspan="3">&nbsp;Coder - 05  负责程序测试、意见统筹及Bugs修复</td>
  </tr>
  <tr>
    <td height=23 colspan=4><b>- [美工部] - 招人ing.. </b></td>
  </tr>    
  <tr>
    <td width="14%" height=23 align="center"><strong>Hunter</strong></td>
    <td colspan="3">&nbsp;Skiner - 01 程序原创美工</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>madman</strong></td>
    <td colspan="3">&nbsp;Skiner - 02 程序现任美工</td>
  </tr>
  <tr>
    <td height=23 colspan=4><b>- [文档部] </b></td>
  </tr>
   <tr>
    <td width="14%" height=23 align="center"><strong>Tomy</strong></td>
    <td colspan="3">&nbsp;Documenter - 01</td>
  </tr>
  <tr>
    <td width="14%" height=23 align="center"><strong>Viva</strong></td>
    <td colspan="3">&nbsp;Documenter - 02</td>
  </tr>
    <tr>
    <td height=23 colspan=4><b>- [测试部] - 欢迎大家加入 exBlog 的 Tester 行列。您的名字将会光荣出现在今后我们的发布版本中</b></td>
  </tr>
  <tr>
    <td width="15%" height=23 align="center"><strong>流水流云</strong></td>
    <td width="35%" align="center"><strong>Viva</strong></td>
    <td width="15%" height=23 align="center"><strong>abcdefg11</strong></td>
    <td width="35%" align="center"><strong>md-chinese</strong></td>
  </tr>
  <tr>
    <td width="15%" height=23 align="center"><strong>Neptune</strong></td>
    <td width="35%" align="center"><strong>snk</strong></td>
    <td width="15%" height=23 align="center"><strong>wolfleader</strong></td>
    <td width="35%" align="center"><strong>wrdhl</strong></td>
  </tr>
  <tr>
    <td width="15%" height=23 align="center"><strong>都有了</strong></td>
    <td width="35%" align="center"></td>
    <td width="15%" height=23 align="center"></td>
    <td width="35%" align="center"></td>
  </tr>
</table>
	<hr size="0" noshade width="90%">
<table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
  <tr>
    <td class="menu" colspan=2 height=25 align="center"><b>技术支持信息</b></td>
  </tr>
  <tr>
    <td height=23 colspan=2>　一个程序难免有BUG,如果你发现任何BUG,请进入程序支持网站报告BUG. <br>　<span class="style1"><font color="#FF0000">我们的进步离不开您的帮助</font></span>.</td>
  </tr>
  <tr>
    <td width="20%" height=23 align="center">Support WEB :</td>
    <td width="80%">&nbsp; <a href="http://www.exBlog.net/">http://www.exBlog.net/</a></td>
  </tr>
  <tr>
    <td width="20%" height=23 align="center">Support BBS :</td>
    <td width="80%">&nbsp; <a href="http://bbs.fengling.net/">风灵之都论坛</a></td>
  </tr>
  <tr>
    <td width="20%" height=23 align="center">Support Mail :</td>
    <td width="80%">&nbsp; <a href="mailto:exblog@fengling.net">exblog@fengling.net</a> <a href="mailto:exblog@yeah.net">exblog@yeah.net</a></td>
  </tr>
</table></td>
  </tr>
</table>
</body>
</html>
