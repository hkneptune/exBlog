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
*\    本页说明: 添加&编辑&删除用户页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

checkLogin();                         ##查询是否非法登录
checkUserUid($_SESSION[exPass]);      ### 检查用户权限
selectAdmin();                        ##查询当前超级用户
selectUser();                         ##查询当前普通用户

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
<form action="./edituser.php" method="post">
<?
    if($_GET[action] == "edit")
	{
?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr>
      <td height="25" colspan="4" align="center" class="a1"><b>用户管理界面</b></td>
    </tr>
    <tr class="a4">
      <td height="23" align="center">当前管理员:</td>
      <td>
        <select name="exAdmin" class="botton">
          <option value="NULL" selected>当前超级用户</option>
          <? while($adminList=mysql_fetch_array($resultAdmin)) { ?>
          <option value="<? echo "$adminList[id]"; ?>"><? echo "$adminList[user]"; ?></option>
          <? } ?>
        </select>
      </td>
      <td width="50%" colspan="2" rowspan="2">
        <input type="submit" name="action" value="修改所选用户权限" class="botton">
&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="action" type="submit" class="botton" onClick="return confirm('你确定要删吗？')" value="删除所选用户"></td>
    <tr class="a4">
      <td width="20%" height="23" align="center">当前普通用户:</td>
      <td width="30%">
        <select name="exUser" class="botton">
          <option value="NULL" selected>当前普通用户</option>
          <? while($userList=mysql_fetch_array($resultUser)) { ?>
          <option value="<? echo "$userList[id]"; ?>"><? echo "$userList[user]"; ?></option>
          <? } ?>
        </select>
      </td>
    <tr class="a4">
      <td colspan="4">&nbsp;</td>
    </tr>
  </table>
  <? 
	}
    else if($_POST['action'] == "修改所选用户权限")
	{
		selectOneObject($_POST['exAdmin'], $_POST['exUser']);
?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr>
      <td height="25" colspan="2" align="center" class="a1"><b>编辑所选用户</b></td>
    </tr>
    <tr class="a4">
      <td width="20%" align="center">用户名:</td>
      <td width="80%"><input type="text" name="user" value="<? echo "$resultOneObj[user]"; ?>" class="input">      </td>
    <tr class="a4">
      <td align="center">密码: </td>
      <td><input name="password" type="password" class="input">
        <font color="#FF0000">(如果不须修改，请不要填写！下同) </font></td>
    <tr class="a4">
      <td align="center">确认密码: </td>
      <td><input name="password_2" type="password" class="input">
      </td>
    <tr class="a4">
      <td align="center">E-Mail:</td>
      <td><input type="text" name="email" value="<? echo "$resultOneObj[email]"; ?>" class="input"></td>
    <tr class="a4">
      <td align="center">用户权限:</td>
      <td><select name="exUid" class="botton">
          <option value="NULL" selected>请选择权限</option>
          <option value="0">超级用户</option>
          <option value="1">普通用户</option>
      </select></td>
    <tr align="center" class="a4">
      <td height="30" colspan="2">        <input type="hidden" name="id" value="<? echo "$resultOneObj[id]"; ?>">        <input name="action" type="submit" class="botton" value="保存操作">      </td>
    <tr class="a4">
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <? 
    }
    elseif($_GET['action'] == "adduser")
    {
?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr align="center">
      <td height="25" colspan="2" class="a1"><b>添加新用户</b></td>
    </tr>
    <tr class="a4">
      <td width="20%" align="center">用户名:</td>
      <td width="80%"><input name="user" type="text" class="input">      </td>
    <tr class="a4">
      <td align="center">密码: </td>
      <td><input name="password" type="password" class="input"></td>
    <tr class="a4">
      <td height="23" align="center">确认密码: </td>
      <td><input name="password_2" type="password" class="input"></td>
    <tr class="a4">
      <td height="23" align="center">E-Mail:</td>
      <td><input name="email" type="text" class="input"></td>
    <tr class="a4">
      <td height="23" align="center">用户权限:</td>
      <td><select name="exUid" class="botton">
          <option value="NULL" selected>请选择权限</option>
          <option value="0">超级用户</option>
          <option value="1">普通用户</option>
      </select></td>
    <tr align="center" class="a4">
      <td height="30" colspan="2">        <input name="action" type="submit" class="botton" value="添加用户">      </td>
    <tr class="a4">
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <? } ?>
</form>
</body>
</html>
<?

if($_POST[action] == "删除所选用户")
{
	deleteSelectObject($_POST[exAdmin],$_POST[exUser]);
}
elseif($_POST[action] == "保存操作")
{
	updateOneObj($_POST[user], $_POST[password], $_POST[password_2], $_POST[email], $_POST[exUid], $_POST[id]);
}
else if($_POST[action] == "添加用户")
{
	addNewUser($_POST[user], $_POST[password], $_POST[password_2], $_POST[email], $_POST[exUid]);
}

?>
