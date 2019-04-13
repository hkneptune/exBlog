<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 RC1  exBlog 网络日志(PHP+MYSQL) 1.2.0 RC1 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 更新个人简介页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");

setGlobal();

checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限
$aboutInfo=selectAbout();            ### 查询about信息
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
<?php
if($_POST[action] == "更新资料")
{
    updateInfo($_POST[name], $_POST[age], $_POST[email], $_POST[qq], $_POST[icq], $_POST[msn],$_POST[description]);
}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
<?php
	if($_GET[action] == "edit")
	{
?>
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr>
      <td class="a1" colspan=2 height=25 align="center"><b>个人资料设置</b></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">名 字(Name):</td>
      <td width="80%">&nbsp;<input name="name" type="text" value="<? echo $aboutInfo['name']; ?>" class="input">         </td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">年 龄(Age):</td>
      <td width="80%">&nbsp;<input name="age" type="text" class="input" id="age" value="<? echo $aboutInfo['age']; ?>" size="5"></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">电 邮(Email):</td>
      <td width="80%">&nbsp;<input name="email" type="text" class="input" value="<? echo $aboutInfo['email']; ?>" size="30"></td>
    </tr>
	<tr class=a4>
      <td width="20%" height=23 align="center">QQ:</td>
      <td width="80%">&nbsp;<input name="qq" type="text" class="input" id="qq" value="<? echo $aboutInfo['qq']; ?>"></td>
    </tr>
	<tr class=a4>
      <td width="20%" height=23 align="center">ICQ:</td>
      <td width="80%">&nbsp;<input name="icq" type="text" class="input" id="icq" value="<? echo $aboutInfo['icq']; ?>"></td>
    </tr>
	<tr class=a4>
      <td width="20%" height=23 align="center">MSN:</td>
      <td width="80%">&nbsp;<input name="msn" type="text" class="input" id="msn" value="<? echo $aboutInfo['msn']; ?>" size="30"></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">其他简介:</td>
      <td width="80%">&nbsp;<textarea name="description" cols="60" rows="8" wrap="VIRTUAL" class="input" id="description"><? echo $aboutInfo['description']; ?></textarea></td>
    </tr>
    <tr align="center" class=a4>
      <td height=30 colspan="2">
	  <input type="submit" name="action" value="更新资料" class="botton">
	  &nbsp;&nbsp;&nbsp;&nbsp; 
	  <input type="reset"  value="刷新重写">	  </td>
    </tr>
  </table>
  <? } ?>
</form>
</body>
</html>
