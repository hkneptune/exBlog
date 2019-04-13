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
*\    本页说明: 添加&编辑&删除用户页面
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
include("../$langURL/edituser.php");

checkLogin();                         ##查询是否非法登录
checkUserUid($_SESSION[exPass], 0);   ### 检查用户权限
selectAdmin();                        ##查询当前超级用户
selectUser();                         ##查询当前普通用户
selectVisitor();                      ##查询当前访客用户

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
<form action="./edituser.php" method="post">
<?
    if($_GET[action] == "list")
	{
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=3 height=25 align="center"><b><?php echo $lang[0]; ?></b></td>
    </tr>
    <tr>
      <td height="23" align="center"><?php echo $lang[1]; ?></td>
      <td>
        <select name="exAdmin" class="botton">
          <option value="NULL" selected><?php echo $lang[2]; ?></option>
          <? while($adminList=mysql_fetch_array($resultAdmin)) { ?>
          <option value="<? echo "$adminList[id]"; ?>"><? echo "$adminList[user]"; ?></option>
          <? } ?>
        </select>
      </td>
      <td width="50%">
        <input type="submit" name="action" value=" Edit " class="botton">
	</td>
    <tr>
      <td width="20%" height="23" align="center"><?php echo $lang[3]; ?></td>
      <td width="30%">
        <select name="exUser" class="botton">
          <option value="NULL" selected><?php echo $lang[4]; ?></option>
          <? while($userList=mysql_fetch_array($resultUser)) { ?>
          <option value="<? echo "$userList[id]"; ?>"><? echo "$userList[user]"; ?></option>
          <? } ?>
        </select>
      </td>
      <td width="50%">
	<input name="action" type="submit" class="botton" onClick="return confirm('<?php echo $lang[5]; ?>')" value="Delete">
      </td>
    <tr>
    <tr>
      <td class="menu" colspan=3 height=25 align="center"><b><?php echo $lang[6]; ?></b></td>
    </tr>
<? while($VisitorList=mysql_fetch_array($resultVisitor)) { ?>
    <tr>
      <td colspan=3 height=25>#<? echo "$VisitorList[id]"; ?> <a href="./edituser.php?action=delete&exVisitor=<? echo "$VisitorList[id]"?>" onclick="if(confirm ('<?php echo $lang[5]; ?>')){;}else{return false;}"><?php echo $lang[7]; ?></a> <a href="./edituser.php?action= Edit &exUser=<? echo "$VisitorList[id]"?>&exAdmin=NULL"><?php echo $lang[24]; ?></a> <b><? echo "$VisitorList[user]"; ?></b> <? echo "$VisitorList[email]"; ?></td>
    </tr>
<? } ?>
  </table></td>
  </tr>
</table>
  <? 
	}
    else if($action == " Edit ")
	{
		selectOneObject($exAdmin, $exUser);
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[8]; ?></b></td>
    </tr>
    <tr>
      <td width="20%" align="center"><?php echo $lang[9]; ?></td>
      <td width="80%"><input type="text" name="user" value="<? echo htmlspecialchars($resultOneObj[user]); ?>" class="input">      </td>
    <tr>
      <td align="center"><?php echo $lang[10]; ?></td>
      <td><input name="password" type="password" class="input">
        <font color="#FF0000"><?php echo $lang[11]; ?></font></td>
    <tr>
      <td align="center"><?php echo $lang[12]; ?></td>
      <td><input name="password_2" type="password" class="input">
      </td>
    <tr>
      <td align="center"><?php echo $lang[13]; ?></td>
      <td><input type="text" name="email" value="<? echo "$resultOneObj[email]"; ?>" class="input"></td>
    <tr>
    <tr>
      <td align="center"><?php echo $lang[14]; ?></td>
      <td><input type="text" name="phone" value="<? echo htmlspecialchars($resultOneObj[phone]); ?>" class="input" title="<?php echo $lang[15]; ?>"> <font color="#FF0000"><?php echo $lang[16]; ?></font></td>
    <tr>
      <td align="center"><?php echo $lang[17]; ?></td>
      <td><select name="exUid" class="botton">
          <option value="NULL"><?php echo $lang[18]; ?></option>
          <option value="0" <?php editUserSelUid(0,$resultOneObj[uid]); ?>><?php echo $lang[19]; ?></option>
          <option value="1" <?php editUserSelUid(1,$resultOneObj[uid]); ?>><?php echo $lang[20]; ?></option>
          <option value="3" <?php editUserSelUid(3,$resultOneObj[uid]); ?>><?php echo $lang[25]; ?></option>
      </select></td>
    <tr align="center">                                                                                                    <input type="hidden" name="action" value="actediauser">
      <td height="30" colspan="2">        <input type="hidden" name="id" value="<? echo "$resultOneObj[id]"; ?>">        <input type="submit" class="botton" value="<?php echo $lang[21]; ?>">      </td>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table></td>
  </tr>
</table>
  <? 
    }
    elseif($_GET['action'] == "adduser")
    {
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[22]; ?></b></td>
    </tr>
    <tr>
      <td width="20%" align="center"><?php echo $lang[9]; ?></td>
      <td width="80%"><input name="user" type="text" class="input">      </td>
    <tr>
      <td align="center"><?php echo $lang[10]; ?></td>
      <td><input name="password" type="password" class="input"></td>
    <tr>
      <td height="23" align="center"><?php echo $lang[12]; ?></td>
      <td><input name="password_2" type="password" class="input"></td>
    <tr>
      <td height="23" align="center"><?php echo $lang[13]; ?></td>
      <td><input name="email" type="text" class="input"></td>
    <tr>
      <td height="23" align="center"><?php echo $lang[14]; ?></td>
      <td><input name="phone" type="text" class="input" title="<?php echo $lang[15]; ?>">
      <font color="#FF0000"><?php echo $lang[16]; ?></font></td>
    <tr>
      <td height="23" align="center"><?php echo $lang[17]; ?></td>
      <td><select name="exUid" class="botton">
          <option value="NULL" selected><?php echo $lang[18]; ?></option>
          <option value="0"><?php echo $lang[19]; ?></option>
          <option value="1"><?php echo $lang[20]; ?></option>
          <option value="3"><?php echo $lang[25]; ?></option>
      </select></td>
    <tr align="center">                  <input type="hidden" name="action" value="actaddauser">
      <td height="30" colspan="2">        <input type="submit" class="botton" value="<?php echo $lang[23]; ?>">      </td>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table></td>
  </tr>
</table>
  <? } ?>
</form>
</body>
</html>
<?

if($_POST[action] == "Delete" || $_GET[action] == "Delete")
{
	deleteSelectObject($_POST[exAdmin],$_POST[exUser],$_GET[exVisitor]);
}
elseif($_POST[action] == "actediauser")
{
	updateOneObj($_POST[user], $_POST[password], $_POST[password_2], $_POST[email], $_POST[phone], $_POST[exUid], $_POST[id]);
}
else if($_POST[action] == "actaddauser")
{
	addNewUser($_POST[user], $_POST[password], $_POST[password_2], $_POST[email], $_POST[phone], $_POST[exUid]);
}
else if($_GET[action] == "delete")
{
	deleteSelectObject("NULL","NULL",$_GET[exVisitor]);
}

?>
