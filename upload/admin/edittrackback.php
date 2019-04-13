<?php
/*============================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 添加&编辑&删除 Trackback 页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
require("../include/CheckData.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/editcomment.php");

checkLogin();       ### 检查用户是否非法登录
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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<style type="text/css">
<!--
.style7 {font-size: 8pt}
-->
</style>
</head>
<body class="main" dir="<?php echo $langpublic[dir]; ?>">
<script language="JavaScript">
    function copyC(){
	therange=document.exEdit.content.createTextRange();
	therange.execCommand("Copy");
	}

   function pasteC(){
	alert("本还原操作将还原最近一次日志发表失败内容！");
	document.exEdit.content.focus();
	document.exEdit.content.createTextRange().execCommand("Paste");
	}
</script>
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>" name="exEdit">
<?
	if($_POST['action'] == "ediacom")
	{
		updateTrackback($_POST[id], $_POST[url], $_POST[blog_name], $_POST[title], $_POST[excerpt], $_POST[addtime]);
	}
	elseif($_GET['action'] == "delete")
	{
			deleteTrackback($_GET['id']);
	}
	if($_GET['action'] == "modify")
	{
			$TrackbackInfo=selectOneTrackbackInfo($_GET['id']); ### 查询当前待编辑的文章内容

?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
 <table width="98%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b><?php echo $lang[0]; ?></b></td>
    </tr>
	<tr align="center">
      <td height="25" class="main" width="5">url:</td>
      <td height="25" class="main" align="left" width="487" colspan="3"><input name="url" type="text" value="<? echo htmlspecialchars($TrackbackInfo[url]); ?>" class="input" size="60"></td>
	</tr>
	<tr align="center">
      <td height="25" class="main" width="5">blog_name:</td>
      <td height="25" class="main" align="left" width="487" colspan="3"><input name="blog_name" type="text" value="<? echo htmlspecialchars($TrackbackInfo[blog_name]); ?>" class="input" size="60"></td>
	</tr>
	<tr align="center">
      <td height="25" class="main" width="5">title:</td>
      <td height="25" class="main" align="left" width="487" colspan="3"><input name="title" type="text" value="<? echo htmlspecialchars($TrackbackInfo[title]); ?>" class="input" size="60"></td>
	</tr>
	<tr align="center">
      <td height="25" class="main" width="5">addtime:</td>
      <td height="25" class="main" align="left" width="487" colspan="3"><input name="addtime" type="text" value="<? echo htmlspecialchars($TrackbackInfo[addtime]); ?>" class="input" size="60"></td>
	</tr>
	<tr align="center">
      <td height="25" class="main" width="5">excerpt:</td>
      <td height="25" class="main" align="left" width="487" colspan="3"><textarea name="excerpt" cols="60" rows="5" wrap="VIRTUAL" class="input"><? echo htmlspecialchars($CommentInfo[excerpt]); ?></textarea></td>
	</tr>
	<tr align="center">
      <td colspan="4" class="main"><input type="hidden" name="id" value="<? echo $_GET['id']; ?>">
<input type="hidden" name="action" value="ediacom"> 
          <input type="submit" value="<?php echo $lang[5]; ?>" class="botton">
      </td>
    </tr>
 </table>
  <?
	}
    elseif($_GET['action'] == "edit")
	{
	checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限
	selectTrackbackModify();                  ### 显示文章列表
	cutTrackbackPage();                       ### 分页
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="98%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b><?php echo $lang[6]; ?></b></td>
    </tr>
	
    <?php
    while($editTrackback=mysql_fetch_array($resultTrackback))
	{
	$editTrackback['excerpt']=substr($editTrackback['excerpt'],"0","80").chr(0);
	$editTrackback['excerpt']=$editTrackback['excerpt']."...";
?>
    <tr class="main">
      <td width="15%" height="23">&nbsp;id:[<? echo "$editTrackback[id]"; ?>]</td>
      <td width="65%"><a href="../admin/./edittrackback.php?action=modify&id=<? echo "$editTrackback[id]"?>"><? echo "$editTrackback[excerpt]" ?></a></td>
      <td width="10%"><a href="../admin/./edittrackback.php?action=modify&id=<? echo "$editTrackback[id]"?>"><?php echo $lang[7]; ?></a></td>
      <td width="10%"><a href="../admin/./edittrackback.php?action=delete&id=<? echo "$editTrackback[id]"?>" onclick="if(confirm ('<?php echo $lang[8]; ?>')){;}else{return false;}"><?php echo $lang[9]; ?></a></td>
    </tr>
    <? } ?>
    <tr class="main">
      <td colspan="4">&nbsp; </td>
    </tr>
    <tr>
      <td colspan="4">
  <? 
	 $show_pages=cutTrackbackPage();
		  //显示最终结果
	echo"<br>$lang[10]<br>$show_pages<br>";
	 echo $display;
    }
	?>
</td>
    </tr>
  </table>
</form>
</body>
</html>
