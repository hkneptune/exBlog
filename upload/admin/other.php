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
*\    本页说明: 常规配置页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/other.php");

checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 0);  ### 检查用户权限
$globalInfo=selectGlobal();          ### 查询blog常项设置
$readDir=readTemplates();            ### 读模版目录
$readDir2=readLanguage();            ### 读语言目录

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
<?
    if($_POST[action] == "gogogo")
	{
		updateGlobal($_POST['exBlogName'],$_POST['exBlogUrl'],$_POST['exBlogCopyright'], $_POST['templatesURL'], $_POST['activeRun'], $_POST['unactiveRunMessage'], $_POST['isCountOnlineUser'], $_POST['description'], $_POST['webmaster'], $_POST['GDswitch'], $_POST['exurlon'], $_POST['sitekeyword'], $_POST['summarynum'], $_POST['alltitlenum'], $_POST['listblognum'], $_POST['listallnum'], $_POST['languageURL']);
	}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[1]; ?></b></td>
    </tr>
    <tr> 
      <td colspan="2" height=23 align="center"><a href="#" onclick="window.open('http://www.exblog.net/checkv.php?action=check&v=<? echo $globalInfo['Version']; ?>', 'newwindow', 'height=200, width=300, top=100,left=300, toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no, status=no')"><?php echo $lang[2]; ?></a></td>
    </tr>
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[3]; ?></b></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[4]; ?></td>
      <td width="79%">&nbsp; <select name="languageURL" class="botton">
          <?
    $cout=count($readDir2);
	for($i=0; $i<$cout; $i++)
	{
?>
          <option value="language/<? echo $readDir2[$i]; ?>" 
				<? searchCurLanguage($readDir2[$i]); ?>> <? echo $readDir2[$i]; ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[5]; ?></td>
      <td width="79%">&nbsp; <select name="templatesURL" class="botton">
          <?
    $cout=count($readDir);
	for($i=0; $i<$cout; $i++)
	{
?>
          <option value="templates/<? echo $readDir[$i]; ?>" 
				<? searchCurTemplates($readDir[$i]); ?>> <? echo $readDir[$i]; ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[6]; ?></td>
      <td width="79%">&nbsp; <input name="exBlogName" type="text" class="input" value="<? echo htmlspecialchars($globalInfo['siteName']); ?>" size="60"></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[7]; ?></td>
      <td width="79%">&nbsp; <input name="exBlogUrl" type="text" class="input" value="<? echo $globalInfo['siteUrl']; ?>" size="60">
      <br /><font color="red">* <?php echo $lang[8]; ?></font></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[9]; ?></td>
      <td width="79%">&nbsp; <input name="webmaster" type="text" class="input" value="<? echo $globalInfo['Webmaster']; ?>" size="60"></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[10]; ?></td>
      <td width="79%">&nbsp; <textarea name="description" cols="60" rows="6" wrap="VIRTUAL" class="input"><? echo htmlspecialchars($globalInfo['Description']); ?></textarea> 
      </td>
    </tr>
    <tr>
      <td width="21%" height=23 align="center"><?php echo $lang[11]; ?></td>
      <td width="79%">&nbsp; <input name="sitekeyword" type="text" class="input" value="<? echo htmlspecialchars($globalInfo['sitekeyword']); ?>" size="60">
      <br /><font color="red">* <?php echo $lang[12]; ?></font></td>
    </tr>
    <tr> 
      <td width="21%" height=23 align="center"><?php echo $lang[13]; ?></td>
      <td width="79%">&nbsp; <textarea name="exBlogCopyright" cols="60" rows="6" wrap="VIRTUAL" class="input"><? echo htmlspecialchars($globalInfo['copyright']); ?></textarea> 
      </td>
    </tr>
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[14]; ?></b></td>
    </tr>
    <tr> 
      <td height=23 align="center"><?php echo $lang[15]; ?></td>
      <td>&nbsp; <select name="activeRun">
          <option value="1"<? selected2activeRun("1"); ?>><?php echo $lang[16]; ?></option>
          <option value="0" <? selected2activeRun("0"); ?>><?php echo $lang[17]; ?></option>
        </select></td>
    </tr>
    <tr> 
      <td height=23 align="center"><?php echo $lang[18]; ?><br>
        <?php echo $lang[19]; ?> </td>
      <td>&nbsp; <textarea name="unactiveRunMessage" cols="60" rows="6" wrap="VIRTUAL" class="input" id="unactiveRunMessage"><? echo htmlspecialchars($globalInfo['unactiveRunMessage']); ?></textarea></td>
    </tr>
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[20]; ?></b></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[21]; ?></td>
      <td>&nbsp; <input name="summarynum" type="text" class="input" value="<? echo $globalInfo['summarynum']; ?>" size="10"> <font color="red">* <?php echo $lang[22]; ?></font></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[23]; ?></td>
      <td>&nbsp; <input name="alltitlenum" type="text" class="input" value="<? echo $globalInfo['alltitlenum']; ?>" size="10"> <font color="red">* <?php echo $lang[24]; ?></font></td>
    </tr>
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[25]; ?></b></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[26]; ?></td>
      <td>&nbsp; <input name="listblognum" type="text" class="input" value="<? echo $globalInfo['listblognum']; ?>" size="10"> <font color="red">* <?php echo $lang[27]; ?></font></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[28]; ?></td>
      <td>&nbsp; <input name="listallnum" type="text" class="input" value="<? echo $globalInfo['listallnum']; ?>" size="10"> <font color="red">* <?php echo $lang[29]; ?></font></td>
    </tr>
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[30]; ?></b></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[31]; ?></td>
      <td>&nbsp;
	  <select name="isCountOnlineUser">
	 	  <option value="1" <? selected2isCountOnlineUser("1"); ?>><?php echo $lang[16]; ?></option>
          <option value="0" <? selected2isCountOnlineUser("0"); ?>><?php echo $lang[17]; ?></option>
        </select></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[32]; ?></td>
      <td>&nbsp;
	  <select name="GDswitch">
	 	  <option value="1" <? selected2GDswitch("1"); ?>><?php echo $lang[16]; ?></option>
          <option value="0" <? selected2GDswitch("0"); ?>><?php echo $lang[17]; ?></option>
        </select></td>
    </tr>
    <tr>
      <td height=23 align="center"><?php echo $lang[33]; ?></td>
      <td>&nbsp;
	  <select name="exurlon">
	 	  <option value="1" <? selected2exurl("1"); ?>><?php echo $lang[16]; ?></option>
          <option value="0" <? selected2exurl("0"); ?>><?php echo $lang[17]; ?></option>
        </select></td>
    </tr>
    <tr align="center"> <input type="hidden" name="action" value="gogogo">
      <td height=30 colspan="2"><input type="submit" value="<?php echo $lang[0]; ?>" class="botton"></td>
    </tr>
  </table></td>
  </tr>
</table>
</form>
</body>
</html>
