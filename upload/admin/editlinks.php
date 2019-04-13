<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L] exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
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

checkLogin();        ##查询是否非法登录
$passLinks = selectPassLinks();    ##查询所有通过验证链接记录
$unpassLinks = selectUnpassLinks();  ##查询所有未通过验证的链接记录
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
<title>{title}</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<form method="post" action="<? echo $PHP_SELF; ?>">
<?php
if($_POST['action'] == "添加链接")
{
    addLinks($_POST['homepage'], $_POST['url'], $_POST['logoURL'], $_POST['description'], $_POST['webVisible']);
}
if($_GET['action'] == "delete")
{
    deleteLinks($_GET[id]);
}
if($_POST['action'] == "编辑链接")
{
    updateLink($_POST['homepage'], $_POST['url'], $_POST['logoURL'], $_POST['description'], $_POST['id'], $_POST['webVisible']);
}
if($_POST['action'] == "重新排序")
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
            <td colspan="2" class="menu"><b>添加友情链接</b></td>
    </tr>
    <tr> 
      <td width="20%" height="23" align="center">站点名字: </td>
      <td width="80%"><input name="homepage" type="text" class="input" id="homepage"></td>
    </tr>
    <tr> 
      <td height="23" align="center">站点URL:</td>
      <td><input name="url" type="text" class="input" id="url" size="40">
        要以http://开头</td>
    </tr>
    <tr> 
      <td height="23" align="center">logoURL:</td>
      <td valign="top"><input name="logoURL" type="text" class="input" id="logoURL" size="40">
        要以http://开头</td>
    </tr>
    <tr> 
      <td align="center">站点描述:</td>
      <td><textarea name="description" cols="60" rows="8" wrap="VIRTUAL" class="input" id="description"></textarea> 
      </td>
    </tr>
    <tr>
      <td align="center">显示站点:</td>
      <td><input type="radio" name="webVisible"  value="1" checked>
        显示 
        <input type="radio" name="webVisible" value="0">
        不显示</td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center"> 
      <td height="30" colspan="2"> <input type="submit" name="action" value="添加链接" class="botton"> 
      </td>
    </tr>
  </table></td>
    </tr>
  </table>
  <?
}
else if($_GET['action'] == "edit")
{
	checkUserUid($_SESSION[exPass]);     ### 检查用户权限
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu"><b>编辑链接-已通过验证的站点</b></td>
    </tr>
    <?php
    while($editLinks=mysql_fetch_array($passLinks))
	{
?>
    <tr>
      <td width="15%" height="23" align="center">&nbsp;ID:[<? echo "$editLinks[id]"; ?>]</td>
      <td width="25%"><? echo "$editLinks[homepage]" ?></td>
      <td width="30%" style="WORD-BREAK: break-all; WORD-WRAP: break-word; table-layout:fixed;"><a href="<? echo "$editLinks[url]"; ?>" target="_blank"><? echo "$editLinks[url]" ?></a></td>
      <td width="20%"><a href="./editlinks.php?action=modify&id=<? echo "$editLinks[id]"?>">[编辑链接]</a>
	  <td width="10%"><a href="./editlinks.php?action=delete&id=<? echo "$editLinks[id]"?>">[删除]</a>
    <tr>
        <? } ?>
        <td colspan="5">&nbsp; </td>
    </tr>
  </table>
  
  <br>
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu"><b>编辑链接-<font color="#FF0000">未通过验证的站点</font></b></td>
    </tr>
<?php
    while($editLinks=mysql_fetch_array($unpassLinks))
	{
?>
    <tr>
      <td width="15%" height="23" align="center">&nbsp;ID:[<? echo "$editLinks[id]"; ?>]</td>
      <td width="25%"><? echo "$editLinks[homepage]" ?></td>
      <td width="30%" style="WORD-BREAK: break-all; WORD-WRAP: break-word; table-layout:fixed;"><a href="<? echo "$editLinks[url]"; ?>" target="_blank"><? echo "$editLinks[url]" ?></a></td>
      <td width="20%"><a href="./editlinks.php?action=modify&id=<? echo "$editLinks[id]"?>">[通过申请]</a></td>
      <td width="10%"><a href="./editlinks.php?action=delete&id=<? echo "$editLinks[id]"?>">[删除]</a>
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
	   checkUserUid($_SESSION[exPass]);     ### 检查用户权限
       selectEditLink($_GET[id]);           ### 查询当前待编辑的链接信息
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu"><b>修改友情链接</b></td>
    </tr>
    <tr> 
      <td width="15%" height="23" align="center">站点名字:</td>
      <td width="85%"><input name="homepage" type="text" class="input" value="<? echo $editLink['homepage']; ?>"></td>
    </tr>
    <tr> 
      <td align="center">站点URL:</td>
      <td><input name="url" type="text" class="input" size="40" value="<? echo $editLink['url']; ?>">
        http://开头 </td>
    </tr>
    <tr> 
      <td align="center">logoURL:</td>
      <td><input name="logoURL" type="text" class="input" id="logoURL3" value="<? echo $editLink['logoURL']; ?>" size="40">
        http://开头</td>
    </tr>
    <tr> 
      <td align="center">站点描述:</td>
      <td><textarea name="description" cols="60" rows="8" wrap="VIRTUAL" class="input"><? echo "$editLink[description]"; ?></textarea> 
      </td>
    </tr>
    <tr>
      <td align="center">显示站点:</td>
      <td><input type="radio" name="webVisible"  value="1" checked>
        显示 
        <input type="radio" name="webVisible" value="0">
        不显示</td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center"> 
      <td height="30" colspan="2"> <input type="hidden" name="id" value="<? echo "$_GET[id]";?>"> 
        <input type="submit" name="action" value="编辑链接" class="botton"> </td>
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
            <td colspan="2" class="menu"><b>链接排序（数字越小越靠前）</b></td>
    </tr>
	<tr>
	<td width="20%">请选择要修改顺序的链接</td>
	<td width="20%"><select name="oldID" class="botton">
	 <? 
	 while($editLinks1=mysql_fetch_array($passLinks)) { ?>
      <option value="<? echo "$editLinks1[id]"; ?>"><? echo "$editLinks1[id]&nbsp$editLinks1[homepage]"; ?></option>
	<? } ?>
	 </select></td>
	<td width="20%">请选择被替换顺序的链接<br></td>
	<td width="20%"><select name="newID" class="botton">
	 <? $passLinks1 = selectPassLinks();
	 while($editLinks2=mysql_fetch_array($passLinks1)) { ?>
      <option value="<? echo "$editLinks2[id]"; ?>"><? echo "$editLinks2[id]&nbsp$editLinks2[homepage]"; ?></option>
	<? } ?>
	</select></td>
	<td width="20%"><input type="submit" name="action" value="重新排序" class="botton">
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
