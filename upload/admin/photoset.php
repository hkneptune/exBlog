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
*\    本页说明: 上传图片设置页面  sheeryiro 编写
*\===========================================================================*/
require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();
checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限
$photosetInfo=selectPhotoset();      ### 查询图片上传设定信息 
?>
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
if($_POST[action] == "更新设定")
{
    updatePhotoset($_POST[max_file_size], $_POST[destination_folder], $_POST[watermark], $_POST[waterposition], $_POST[waterstring]);
}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
<?php
	if($_GET[action] == "edit")
	{
?>
  <table cellpadding="４" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr>
      <td class="a1" colspan=3 height=25 align="center"><b>图片上传设置</b></td>
    </tr>
    <tr class=a4>
      <td width="15%" height=23 align="center">图片最大值(kb):</td>
      <td colspan="2">&nbsp;<input name="max_file_size" type="text" value="<? echo $photosetInfo['max_file_size']; ?>" class="input"></td>
    </tr>
    <tr class=a4>
      <td width="15%" height=23 align="center">保存文件夹</td>
      <td width="15%">&nbsp;<input name="destination_folder" type="text" value="<? echo $photosetInfo['destination_folder']; ?>" class="input"></td>
      <td width="70%">相对路径，以"/"结尾;如"upload/，Linux系列主机必须设置此目录属性为777</td>
    </tr>
	<tr class=a4>
      <td width="15%" height=23 align="center">是否加水印</td>
      <td width="15%">&nbsp;<input name="watermark" type="text" class="input" value="<? echo $photosetInfo['watermark']; ?>" size="1"></td>
      <td width="70%">1为加水印,0为不加水印(需要GD库支持，建议设为0)</td>
	</tr>
		<tr class=a4>
      <td width="15%" height=23 align="center">水印位置：</td>
      <td width="15%">&nbsp;<input name="waterposition" type="text" class="input" value="<? echo $photosetInfo['waterposition']; ?>" size="1"></td>
      <td width="70%">1为左下角,2为右下角,3为左上角,4为右上角,5为居中</td>
	</tr>
    <tr class=a4>
      <td width="15%" height=23 align="center">水印文字:</td>
      <td colspan="2">&nbsp;<input name="waterstring" type="text" class="input" value="<? echo $photosetInfo['waterstring']; ?>" size="20"></td>
    </tr>
    <tr align="center" class=a4>
      <td height=30 colspan="3">
	  <input type="submit" name="action" value="更新设定" class="botton">
	  &nbsp;&nbsp;&nbsp;&nbsp; 
	  <input type="reset" name="action" value="刷新重写" class="botton">	  </td>
    </tr>
  </table>
  <? } ?>
</form>
</body>
</html>
