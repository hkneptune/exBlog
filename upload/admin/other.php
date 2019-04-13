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
*\    本页说明: 常规配置页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();

checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限
$globalInfo=selectGlobal();          ### 查询blog常项设置
$readDir=readTemplates();            ### 读模版目录
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
<?
    if($_POST[action] == "提交更新")
	{
		updateGlobal($_POST['exBlogName'],$_POST['exBlogUrl'],$_POST['exBlogCopyright'],$_POST['templatesURL'], $_POST['activeRun'], $_POST['unactiveRunMessage'], $_POST['isCountOnlineUser'], $_POST['description'], $_POST['webmaster']);
	}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>常规选项设置</b></td>
    </tr>
    <tr> 
      <td colspan="2" height=23 align="center"><a href="#" onclick="window.open('http://www.exblog.net/checkv.php?action=check&v=<? echo $globalInfo['Version']; ?>', 'newwindow', 'height=200, width=300, top=100,left=300, toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no, status=no')">+ 检查exBlog最新版本 +</a></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center">模版文件:</td>
      <td width="79%">&nbsp; <select name="templatesURL" class="botton">
          <?
    $cout=count($readDir);
	for($i=0; $i<$cout; $i++)
	{
?>
          <option value="templates/<? echo $readDir[$i]; ?>" 
				<? searchCurTemplates($readDir[$i]); ?>> <? echo $readDir[$i]; ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center">站点名称:</td>
      <td width="79%">&nbsp; <input name="exBlogName" type="text" class="input" value="<? echo $globalInfo['siteName']; ?>" size="60"></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center">站点 URL:</td>
      <td width="79%">&nbsp; <input name="exBlogUrl" type="text" class="input" value="<? echo $globalInfo['siteUrl']; ?>" size="60"></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center">管理员信箱:</td>
      <td width="79%">&nbsp; <input name="webmaster" type="text" class="input" value="<? echo $globalInfo['Webmaster']; ?>" size="60"></td>
    </tr>
    <tr>
      <td height=23 align="center">是否统计在线人数:</td>
      <td>&nbsp;
	  <select name="isCountOnlineUser">
	 	  <option value="1" <? selected2isCountOnlineUser("1"); ?>>是</option>
          <option value="0" <? selected2isCountOnlineUser("0"); ?>>否</option>
        </select></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center">Blog简介:</td>
      <td width="79%">&nbsp; <textarea name="description" cols="60" rows="6" wrap="VIRTUAL" class="input"><? echo $globalInfo['Description']; ?></textarea> 
      </td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center">版权信息:</td>
      <td width="79%">&nbsp; <textarea name="exBlogCopyright" cols="60" rows="6" wrap="VIRTUAL" class="input"><? echo $globalInfo['copyright']; ?></textarea> 
      </td>
    </tr>
    <tr> 
      <td height=23 align="center">是否关闭 BLOG:</td>
      <td>&nbsp; <select name="activeRun">
          <option value="1"<? selected2activeRun("1"); ?>>开启</option>
          <option value="0" <? selected2activeRun("0"); ?>>关闭</option>
        </select></td>
    </tr>
    <tr> 
      <td height=23 align="center">关闭 BLOG 时信息:<br>
        (在 <font color="#FF0000">关闭</font> 情况下才可用) </td>
      <td>&nbsp; <textarea name="unactiveRunMessage" cols="60" rows="6" wrap="VIRTUAL" class="input" id="unactiveRunMessage"><? echo $globalInfo['unactiveRunMessage']; ?></textarea></td>
    </tr>
    <tr align="center"> 
      <td height=30 colspan="2"><input type="submit" name="action" value="提交更新" class="botton"></td>
    </tr>
  </table></td>
  </tr>
</table>
</form>
</body>
</html>