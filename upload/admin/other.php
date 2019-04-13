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
    if($_POST[action] == "提交更新")
	{
		updateGlobal($_POST['exBlogName'],$_POST['exBlogUrl'],$_POST['exBlogCopyright'],$_POST['templatesURL'], $_POST['activeRun'], $_POST['unactiveRunMessage'], $_POST['isCountOnlineUser']);
	}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr> 
      <td class="a1" colspan=2 height=25 align="center"><b>常规选项设置</b></td>
    </tr>
    <tr class=a4> 
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
    <tr class=a4> 
      <td width="21%" height=23 align="center">站点名称:</td>
      <td width="79%">&nbsp; <input name="exBlogName" type="text" class="input" value="<? echo $globalInfo['siteName']; ?>" size="60"></td>
    </tr>
    <tr class=a4> 
      <td width="21%" height=23 align="center">站点 URL:</td>
      <td width="79%">&nbsp; <input name="exBlogUrl" type="text" class="input" value="<? echo $globalInfo['siteUrl']; ?>" size="60"></td>
    </tr>
    <tr class=a4>
      <td height=23 align="center">是否统计在线人数:</td>
      <td>&nbsp;
	  <select name="isCountOnlineUser">
	 	  <option value="1" <? selected2isCountOnlineUser("1"); ?>>是</option>
          <option value="0" <? selected2isCountOnlineUser("0"); ?>>否</option>
        </select></td>
    </tr>
    <tr class=a4> 
      <td width="21%" height=23 align="center">exBlog版权信息:</td>
      <td width="79%">&nbsp; <textarea name="exBlogCopyright" cols="60" rows="6" wrap="VIRTUAL" class="input"><? echo $globalInfo['copyright']; ?></textarea> 
      </td>
    </tr>
    <tr class=a4> 
      <td height=23 align="center">关闭 BLOG:</td>
      <td>&nbsp; <select name="activeRun">
          <option value="1"<? selected2activeRun("1"); ?>>开启</option>
          <option value="0" <? selected2activeRun("0"); ?>>关闭</option>
        </select></td>
    </tr>
    <tr class=a4> 
      <td height=23 align="center">关闭 BLOG 时信息:<br>
        (在 <font color="#FF0000">关闭</font> 情况下才可用) </td>
      <td>&nbsp; <textarea name="unactiveRunMessage" cols="60" rows="6" wrap="VIRTUAL" class="input" id="unactiveRunMessage"><? echo $globalInfo['unactiveRunMessage']; ?></textarea></td>
    </tr>
    <tr align="center" class=a4> 
      <td height=30 colspan="2"><input type="submit" name="action" value="提交更新" class="botton"></td>
    </tr>
  </table>
</form>
</body>
</html>