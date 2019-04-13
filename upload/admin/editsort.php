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
*\    本页说明: 添加&编辑&删除分类页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

checkLogin();                        ##查询是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限
?>
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
<?php
if($_POST['action'] == "添加分类")
{
	addSort($_POST['enName'], $_POST['cnName'], $_POST['description']);
}
elseif($_POST['action'] == "删除分类")
{
    deleteSort($_POST['editSort']);
}
elseif($_POST['action'] == "提交分类")
{
    updateSort($_POST['cnName'],$_POST['enName'],$_POST['description'],$_POST['oldCnName'],$_POST['id']);
}
elseif($_POST['action'] == "重新排序")
{
    updateSortrank($_POST['oldID'],$_POST['newID']);
}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
<?
if($_POST['action'] == "编辑分类")
   {
       selectOneSort($_POST['editSort']);
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>修改分类</b></td>
    </tr>
    <tr>
      <td width="20%" height="23" align="center">英文名:</td>
      <td width="80%"><input name="enName" type="text" class="input" size="30" value="<? echo "$resultOneSort[enName]"; ?>">
      Usage: <font color="#0000FF">article</font></td>
    </tr>
    <tr>
      <td height="23" align="center">中文名:</td>
      <td><input name="cnName" type="text" class="input" size="30" value="<? echo "$resultOneSort[cnName]"; ?>">
      Usage: <font color="#0000FF">技术文章</font> </td>
    </tr>
    <tr>
      <td height="23" align="center">描　述:</td>
      <td><input name="description" type="text" class="input" size="30" value="<? echo "$resultOneSort[description]"; ?>">
      Usage: <font color="#0000FF">收集一些技术方面文章</font></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td height="30" colspan="2">        <input name="id" type="hidden" value="<? echo "$resultOneSort[id]"; ?>">        <input name="oldCnName" type="hidden" value="<? echo $resultOneSort[cnName]; ?>">        <input name="action" type="submit" class="botton" id="action" value="提交分类">      </td>
    </tr>
  </table></td>
  </tr>
</table>
  <?
 }
elseif($_GET['action'] == "add")
{
?>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>添加新分类</b></td>
    </tr>
    <tr>
      <td width="20%" align="center">英文名:</td>
      <td width="80%">
        <input name="enName" type="text" class="input" size="30">
      Usage: <font color="#0000FF">article</font></td>
    </tr>
    <tr>
      <td align="center">中文名</td>
      <td><input name="cnName" type="text" class="input" size="30">
      Usage: <font color="#0000FF">技术文章</font> </td>
    </tr>
    <tr>
      <td align="center">描述:</td>
      <td><input name="description" type="text" class="input" size="30">
      Usage: <font color="#0000FF">收集一些技术方面文章</font></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td height="30" colspan="2">        
	<input type="submit" name="action" value="添加分类" class="botton">&nbsp;&nbsp;&nbsp;&nbsp;        <input type="submit" name="action" value="刷新重写" class="botton">      </td>
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
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>编辑分类</b></td>
    </tr>
    <tr>
      <td width="20%" height="23" align="center">现有分类:</td>
      <td width="80%">
        <select name="editSort" class="botton">
          <option value="NULL" selected>请选择要操作的分类</option>
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <option value="<? echo "$row[cnName]"; ?>"><? echo "$row[id]&nbsp$row[cnName]"; ?></option>
          <? } ?>
      </select></td>
    </tr>
	<tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td height="30" colspan="2">        <input type="submit" name="action" value="编辑分类" class="botton">&nbsp;&nbsp;&nbsp;&nbsp;        <input type="submit" name="action" value="删除分类" onClick="return confirm('删除分类将会把该会类下所有文章删除,你确定吗?')" class="botton">            </td>
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
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>分类排序（数字越小越靠前）</b></td>
    </tr>
	<tr>
	<td width="20%">请选择要修改顺序的分类</td>
	<td width="20%"><select name="oldID" class="botton">
	 <? selectAllSort();
	 while($row1=mysql_fetch_array($resultSort)) { ?>
      <option value="<? echo "$row1[id]"; ?>"><? echo "$row1[id]&nbsp$row1[cnName]"; ?></option>
	<? } ?>
	 </select></td>
	<td width="20%">请选择被替换顺序的分类<br></td>
	<td width="20%"><select name="newID" class="botton">
	 <? selectAllSort();
	 while($row2=mysql_fetch_array($resultSort)) { ?>
      <option value="<? echo "$row2[id]"; ?>"><? echo "$row2[id]&nbsp$row2[cnName]"; ?></option>
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
