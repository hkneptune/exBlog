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
*\    本页说明: 添加&编辑&删除链接页面
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
include("../$langURL/editlinks.php");

checkLogin();        ##查询是否非法登录
checkUserUid($_SESSION[exPass], 0);  ### 检查用户权限
$passLinks = selectPassLinks();    ##查询所有通过验证链接记录
$unpassLinks = selectUnpassLinks();  ##查询所有未通过验证的链接记录
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
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">
<?php
if($_POST['action'] == "addalink")
{
    addLinks($_POST['homepage'], $_POST['url'], $_POST['logoURL'], $_POST['description'], $_POST['webVisible']);
}
if($_GET['action'] == "delete")
{
    deleteLinks($_GET[id]);
}
if($_POST['action'] == "edialink")
{
    updateLink($_POST['homepage'], $_POST['url'], $_POST['logoURL'], $_POST['description'], $_POST['id'], $_POST['webVisible']);
}
if($_POST['action'] == "ranalink")
{
    updateLinkrank($_POST['oldID'],$_POST['newID']);
}
if($_GET['action'] == "add")
{
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu" align="center"><b><?php echo $lang[0]; ?></b></td>
    </tr>
    <tr> 
      <td width="20%" height="23" align="center"><?php echo $lang[1]; ?></td>
      <td width="80%"><input name="homepage" type="text" class="input" id="homepage"></td>
    </tr>
    <tr> 
      <td height="23" align="center"><?php echo $lang[2]; ?></td>
      <td><input name="url" type="text" class="input" id="url" size="40">
        <?php echo $lang[3]; ?></td>
    </tr>
    <tr> 
      <td height="23" align="center"><?php echo $lang[4]; ?></td>
      <td valign="top"><input name="logoURL" type="text" class="input" id="logoURL" size="40">
        <?php echo $lang[5]; ?></td>
    </tr>
    <tr> 
      <td align="center"><?php echo $lang[6]; ?></td>
      <td><textarea name="description" cols="60" rows="8" wrap="VIRTUAL" class="input" id="description"></textarea> 
      </td>
    </tr>
    <tr>
      <td align="center"><?php echo $lang[7]; ?></td>
      <td><input type="radio" name="webVisible"  value="1" checked>
        <?php echo $lang[8]; ?> 
        <input type="radio" name="webVisible" value="0">
        <?php echo $lang[9]; ?></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center"> 
<input type="hidden" name="action" value="addalink"> 
      <td height="30" colspan="2"> <input type="submit" value="<?php echo $lang[10]; ?>" class="botton"> 
      </td>
    </tr>
  </table></td>
    </tr>
  </table>
  <?
}
else if($_GET['action'] == "edit")
{
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu"><b><?php echo $lang[11]; ?></b></td>
    </tr>
    <?php
    while($editLinks=mysql_fetch_array($passLinks))
	{
?>
    <tr>
      <td width="15%" height="23" align="center">&nbsp;ID:[<? echo "$editLinks[id]"; ?>]</td>
      <td width="25%"><? echo "$editLinks[homepage]" ?></td>
      <td width="30%" style="WORD-BREAK: break-all; WORD-WRAP: break-word; table-layout:fixed;"><a href="<? echo "$editLinks[url]"; ?>" target="_blank"><? echo "$editLinks[url]" ?></a></td>
      <td width="20%"><a href="./editlinks.php?action=modify&id=<? echo "$editLinks[id]"?>"><?php echo $lang[12]; ?></a>
	  <td width="10%"><a href="./editlinks.php?action=delete&id=<? echo "$editLinks[id]"?>"><?php echo $lang[13]; ?></a>
    <tr>
        <? } ?>
        <td colspan="5">&nbsp; </td>
    </tr>
  </table>
  
  <br>
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu"><b><?php echo $lang[14]; ?></b></td>
    </tr>
<?php
    while($editLinks=mysql_fetch_array($unpassLinks))
	{
?>
    <tr>
      <td width="15%" height="23" align="center">&nbsp;ID:[<? echo "$editLinks[id]"; ?>]</td>
      <td width="25%"><? echo "$editLinks[homepage]" ?></td>
      <td width="30%" style="WORD-BREAK: break-all; WORD-WRAP: break-word; table-layout:fixed;"><a href="<? echo "$editLinks[url]"; ?>" target="_blank"><? echo "$editLinks[url]" ?></a></td>
      <td width="20%"><a href="./editlinks.php?action=modify&id=<? echo "$editLinks[id]"?>"><?php echo $lang[15]; ?></a></td>
      <td width="10%"><a href="./editlinks.php?action=delete&id=<? echo "$editLinks[id]"?>"><?php echo $lang[13]; ?></a>
    <tr>
<? } ?>
        <td colspan="5">&nbsp; </td>
    </tr>
  </table></td>
    </tr>
  </table>
  <? }
   else if($_GET[action] == "modify")
   { 
       selectEditLink($_GET[id]);           ### 查询当前待编辑的链接信息
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu" align="center"><b><?php echo $lang[16]; ?></b></td>
    </tr>
    <tr> 
      <td width="15%" height="23" align="center"><?php echo $lang[1]; ?></td>
      <td width="85%"><input name="homepage" type="text" class="input" value="<? echo htmlspecialchars($editLink['homepage']); ?>"></td>
    </tr>
    <tr> 
      <td align="center"><?php echo $lang[2]; ?></td>
      <td><input name="url" type="text" class="input" size="40" value="<? echo htmlspecialchars($editLink['url']); ?>">
        <?php echo $lang[3]; ?> </td>
    </tr>
    <tr> 
      <td align="center"><?php echo $lang[4]; ?></td>
      <td><input name="logoURL" type="text" class="input" id="logoURL3" value="<? echo htmlspecialchars($editLink['logoURL']); ?>" size="40">
        <?php echo $lang[5]; ?></td>
    </tr>
    <tr> 
      <td align="center"><?php echo $lang[6]; ?></td>
      <td><textarea name="description" cols="60" rows="8" wrap="VIRTUAL" class="input"><? echo htmlspecialchars($editLink[description]); ?></textarea> 
      </td>
    </tr>
    <tr>
      <td align="center"><?php echo $lang[7]; ?></td>
      <td><input type="radio" name="webVisible"  value="1" checked>
        <?php echo $lang[8]; ?> 
        <input type="radio" name="webVisible" value="0">
        <?php echo $lang[9]; ?></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center"> 
      <td height="30" colspan="2"> <input type="hidden" name="id" value="<? echo "$_GET[id]";?>"> 
<input type="hidden" name="action" value="edialink"> 
        <input type="submit" value="<?php echo $lang[17]; ?>" class="botton"> </td>
    </tr>
  </table></td>
    </tr>
  </table>
    <? 
   }
elseif($_GET['action'] == "rank")
{
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu"><b><?php echo $lang[18]; ?></b></td>
    </tr>
	<tr>
	<td width="20%"><?php echo $lang[19]; ?></td>
	<td width="20%"><select name="oldID" class="botton">
	 <? 
	 while($editLinks1=mysql_fetch_array($passLinks)) { ?>
      <option value="<? echo "$editLinks1[id]"; ?>"><? echo "$editLinks1[id]&nbsp$editLinks1[homepage]"; ?></option>
	<? } ?>
	 </select></td>
	<td width="20%"><?php echo $lang[20]; ?><br></td>
	<td width="20%"><select name="newID" class="botton">
	 <? $passLinks1 = selectPassLinks();
	 while($editLinks2=mysql_fetch_array($passLinks1)) { ?>
      <option value="<? echo "$editLinks2[id]"; ?>"><? echo "$editLinks2[id]&nbsp$editLinks2[homepage]"; ?></option>
	<? } ?>
	</select></td>
<input type="hidden" name="action" value="ranalink"> 
	<td width="20%"><input type="submit" value="<?php echo $lang[21]; ?>" class="botton">
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
