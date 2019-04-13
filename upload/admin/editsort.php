<?php
/*============================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004 - 2005. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 添加&编辑&删除分类页面
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
include("../$langURL/editsort.php");

checkLogin();                        ##查询是否非法登录
checkUserUid($_SESSION[exPass], 0);  ### 检查用户权限
?>
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
<?php
if($_POST['action'] == "addasort")
{
	addSort($_POST['enName'], $_POST['cnName'], $_POST['description']);
}
elseif($_POST['action'] == "Delete")
{
    deleteSort($_POST['editSort']);
}
elseif($_POST['action'] == "updasort")
{
    updateSort($_POST['cnName'],$_POST['enName'],$_POST['description'],$_POST['oldCnName'],$_POST['id']);
}
elseif($_POST['action'] == "ranasort")
{
    updateSortrank($_POST['oldID'],$_POST['newID']);
}
?>
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">
<?
if($_POST['action'] == " Edit ")
   {
       selectOneSort($_POST['editSort']);
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[0]; ?></b></td>
    </tr>
    <tr>
      <td width="20%" height="23" align="center"><?php echo $lang[1]; ?></td>
      <td width="80%"><input name="enName" type="text" class="input" size="30" value="<? echo htmlspecialchars($resultOneSort[enName]); ?>">
      Usage: <font color="#0000FF"><?php echo $lang[2]; ?></font></td>
    </tr>
    <tr>
      <td height="23" align="center"><?php echo $lang[3]; ?></td>
      <td><input name="cnName" type="text" class="input" size="30" value="<? echo htmlspecialchars($resultOneSort[cnName]); ?>">
      Usage: <font color="#0000FF"><?php echo $lang[4]; ?></font> </td>
    </tr>
    <tr>
      <td height="23" align="center"><?php echo $lang[5]; ?></td>
      <td><input name="description" type="text" class="input" size="30" value="<? echo htmlspecialchars($resultOneSort[description]); ?>">
      Usage: <font color="#0000FF"><?php echo $lang[6]; ?></font></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td height="30" colspan="2">        <input name="id" type="hidden" value="<? echo "$resultOneSort[id]"; ?>">        <input name="oldCnName" type="hidden" value="<? echo $resultOneSort[id]; ?>">
<input type="hidden" name="action" value="updasort"> 
        <input type="submit" class="botton" id="action" value="<?php echo $lang[7]; ?>">      </td>
    </tr>
  </table></td>
  </tr>
</table>
  <?
 }
elseif($_GET['action'] == "add")
{
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[8]; ?></b></td>
    </tr>
    <tr>
      <td width="20%" align="center"><?php echo $lang[1]; ?></td>
      <td width="80%">
        <input name="enName" type="text" class="input" size="30">
      Usage: <font color="#0000FF"><?php echo $lang[2]; ?></font></td>
    </tr>
    <tr>
      <td align="center"><?php echo $lang[3]; ?></td>
      <td><input name="cnName" type="text" class="input" size="30">
      Usage: <font color="#0000FF"><?php echo $lang[4]; ?></font> </td>
    </tr>
    <tr>
      <td align="center"><?php echo $lang[5]; ?></td>
      <td><input name="description" type="text" class="input" size="30">
      Usage: <font color="#0000FF"><?php echo $lang[6]; ?></font></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td height="30" colspan="2">
<input type="hidden" name="action" value="addasort">     
	<input type="submit" value="<?php echo $lang[9]; ?>" class="botton">&nbsp;&nbsp;&nbsp;&nbsp;        <input type="submit" value="<?php echo $lang[10]; ?>" class="botton">      </td>
    </tr>
  </table></td>
  </tr>
</table>
  <?
}
elseif($_GET['action'] == "edit")
{
    selectAllSort();
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[11]; ?></b></td>
    </tr>
    <tr>
      <td width="20%" height="23" align="center"><?php echo $lang[12]; ?></td>
      <td width="80%">
        <select name="editSort" class="botton">
          <option value="NULL" selected><?php echo $lang[13]; ?></option>
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <option value="<? echo "$row[cnName]"; ?>"><? echo "$row[id]&nbsp$row[cnName]"; ?></option>
          <? } ?>
      </select></td>
    </tr>
	<tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td height="30" colspan="2">        <input type="submit" name="action" value=" Edit " class="botton">&nbsp;&nbsp;&nbsp;&nbsp;        <input type="submit" name="action" value="Delete" onClick="return confirm('<?php echo $lang[14]; ?>')" class="botton">            </td>
    </tr>
  </table></td>
  </tr>
</table>
    <? 
   }
elseif($_GET['action'] == "rank")
{
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[15]; ?></b></td>
    </tr>
	<tr>
	<td width="20%"><?php echo $lang[16]; ?></td>
	<td width="20%"><select name="oldID" class="botton">
	 <? selectAllSort();
	 while($row1=mysql_fetch_array($resultSort)) { ?>
      <option value="<? echo "$row1[id]"; ?>"><? echo "$row1[id]&nbsp$row1[cnName]"; ?></option>
	<? } ?>
	 </select></td>
	<td width="20%"><?php echo $lang[17]; ?><br></td>
	<td width="20%"><select name="newID" class="botton">
	 <? selectAllSort();
	 while($row2=mysql_fetch_array($resultSort)) { ?>
      <option value="<? echo "$row2[id]"; ?>"><? echo "$row2[id]&nbsp$row2[cnName]"; ?></option>
	<? } ?>
	</select></td>
<input type="hidden" name="action" value="ranasort"> 
	<td width="20%"><input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
	</td>
	</tr>
</table>	</td>
  </tr>
</table>
   <? 
   }
?>
</form>
</body>
</html>
