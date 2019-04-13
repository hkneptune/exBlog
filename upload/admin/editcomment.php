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
*\    本页说明: 添加&编辑&删除comment页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
require("../include/CheckData.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

checkLogin();       ### 检查用户是否非法登录
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
<body class="main">
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
<form method="post" action="<? echo $PHP_SELF; ?>" name="exEdit">
<?
	if($_POST['action'] == "编辑评论")
	{
		updateComment($_POST[id], $_POST[content], $_POST[author], $_POST[addtime], $_POST[adminreply]);
	}
	elseif($_GET['action'] == "delete")
	{
			deleteComment($_GET['id']);
	}
	if($_GET['action'] == "modify")
	{
			$CommentInfo=selectOneCommentInfo($_GET['id']); ### 查询当前待编辑的文章内容

?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
 <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b>修改评论</b></td>
    </tr>
	<tr align="center">
      <td height="25" class="main">评论作者:</td>
      <td height="25" class="main" align="left"><input name="author" type="text" value="<? echo $CommentInfo[author]; ?>" class="input"></td>
      <td height="25" class="main">时间:</td>
      <td height="25" class="main" align="left"><input name="addtime" type="text" value="<? echo $CommentInfo[addtime]; ?>" class="input"></td>
	</tr>
	<tr align="center">
      <td height="25" colspan="1" class="main">内容:</td>
      <td height="25" colspan="1" class="main" align="left"><textarea name="content" cols="30" rows="5" wrap="VIRTUAL" class="input"><? echo $CommentInfo[content]; ?></textarea></td>
      <td height="25" colspan="1" class="main">回复:</td>
      <td height="25" colspan="1" class="main" align="left"><textarea name="adminreply" cols="30" rows="5" wrap="VIRTUAL" class="input"></textarea></td></tr>
	<tr align="center">
      <td colspan="4" class="main"><input type="hidden" name="id" value="<? echo $_GET['id']; ?>">
          <input type="submit" name="action" value="编辑评论" class="botton">
      </td>
    </tr>
 </table>
  <?
	}
    elseif($_GET['action'] == "edit")
	{
	checkUserUid($_SESSION[exPass]);     ### 检查用户权限
	selectCommentModify();                  ### 显示文章列表
	cutCommentPage();                       ### 分页
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b>修改评论</b></td>
    </tr>
	
    <?php
    while($editComment=mysql_fetch_array($resultComment))
	{
	$editComment['content']=substr($editComment['content'],"0","80").chr(0);
	$editComment['content']=$editComment['content']."...";
?>
    <tr class="main">
      <td width="15%" height="23">&nbsp;id:[<? echo "$editComment[id]"; ?>]</td>
      <td width="65%"><a href="../admin/./editcomment.php?action=modify&id=<? echo "$editComment[id]"?>"><? echo "$editComment[content]" ?></a></td>
      <td width="10%"><a href="../admin/./editcomment.php?action=modify&id=<? echo "$editComment[id]"?>">[编辑]</a></td>
      <td width="10%"><a href="../admin/./editcomment.php?action=delete&id=<? echo "$editComment[id]"?>" onclick="if(confirm ('你确认要删除么？')){;}else{return false;}">[删除]</a></td>
    </tr>
    <? } ?>
    <tr class="main">
      <td colspan="4">&nbsp; </td>
    </tr>
    <tr>
      <td colspan="4">
  <? 
	 $show_pages=cutCommentPage();
		  //显示最终结果
	echo"<br>查询结果<br>$show_pages<br>";
	 echo $display;
    }
	?>
</td>
    </tr>
  </table>
</form>
</body>
</html>
