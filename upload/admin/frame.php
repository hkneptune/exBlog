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
*\    本页说明: 后台框架页面
*\===========================================================================*/

require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
if($_GET[action] == "exit")
{
	userLogout();
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="elliott,hunter">
<meta name="keywords" content="elliott,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by elliott">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlogMix 后台管理系统</title>

<link href="../images/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
.navPoint {COLOR: white; CURSOR: hand; FONT-FAMILY: Webdings; FONT-SIZE: 9pt}
</style>

<script>
function switchSysBar(){
if (switchPoint.innerText==3){
switchPoint.innerText=4
document.all("frmTitle").style.display="none"
}else{
switchPoint.innerText=3
document.all("frmTitle").style.display=""
}}
</script>

</head>

<body scroll=no topmargin=0  rightmargin=0  leftmargin=0>

<table border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%">
  <tr>
    <td height="100%" align="middle" vAlign="center" noWrap id="frmTitle" name="frmTitle">    
    <iframe frameBorder="0" id="carnoc" name="carnoc" scrolling=no  src="list.php" style="HEIGHT: 100%; VISIBILITY: inherit; WIDTH: 170px; Z-INDEX: 2">
    </iframe>    </td>
    <td class=a2 style="WIDTH: 9pt"> 
    <table border="0" cellPadding="0" cellSpacing="0" height="100%">
      <tr>
        <td style="HEIGHT: 100%" onclick="switchSysBar()">
        <font style="FONT-SIZE: 9pt; CURSOR: default; COLOR: #ffffff">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <span class="navPoint" id="switchPoint" title="关闭/打开左栏">3</span><br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        屏幕切换 </font></td>
      </tr>
    </table>
    </td>
    <td width="100%" height="100%">
    <iframe frameBorder="0" id="mainFrame" name="mainFrame" scrolling="yes" src="main.php" style="HEIGHT: 100%; VISIBILITY: inherit; WIDTH: 100%; Z-INDEX: 1">
    </iframe>	</td>
  </tr>
</table>
</body>
</html>
