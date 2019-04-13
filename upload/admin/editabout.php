<?php
/*==================================================================
*\    exBlog Ver: 1.2.0 [L] exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在主页提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: 更新个人简介页面
*\=================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");

setGlobal();

checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限
$aboutInfo=selectAbout();            ### 查询about信息
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
</head>
<body class="main">
<?php
if($_POST[action] == "更新资料")
{
    updateInfo($_POST[name], $_POST[age], $_POST[email], $_POST[qq], $_POST[icq], $_POST[msn],$_POST[description]);
}
?>
<form method="post" action="<? echo $PHP_SELF; ?>">
<?php
	if($_GET[action] == "edit")
	{
?>
  <table width=500 border="0" cellpadding=0 cellspacing=0 class="border">
    <tr bgcolor="#99CC66"> 
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
          <tr> 
            <td colspan="2" class="menu"><div align="center">- 个人资料更新</div></td>
          </tr>
          <tr> 
            <td width="9%">Name:</td>
            <td width="80%"> <input name="name" type="text" value="<? echo $aboutInfo['name']; ?>" class="input"> 
            </td>
          </tr>
          <tr> 
            <td>Age:</td>
            <td><input name="age" type="text" class="input" id="age" value="<? echo $aboutInfo['age']; ?>" size="5"></td>
          </tr>
          <tr> 
            <td valign="top">Email:</td>
            <td><input name="email" type="text" class="input" value="<? echo $aboutInfo['email']; ?>" size="30"></td>
          </tr>
          <tr> 
            <td valign="top">QQ:</td>
            <td><input name="qq" type="text" class="input" id="qq" value="<? echo $aboutInfo['qq']; ?>"></td>
          </tr>
          <tr> 
            <td valign="top">ICQ:</td>
            <td><input name="icq" type="text" class="input" id="icq" value="<? echo $aboutInfo['icq']; ?>"></td>
          </tr>
          <tr> 
            <td valign="top">MSN:</td>
            <td><input name="msn" type="text" class="input" id="msn" value="<? echo $aboutInfo['msn']; ?>" size="30"></td>
          </tr>
          <tr> 
            <td valign="top">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td valign="top">其它简介:</td>
            <td><textarea name="description" cols="60" rows="8" wrap="VIRTUAL" class="input" id="description"><? echo $aboutInfo['description']; ?></textarea> 
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center"> 
                <input type="submit" name="action" value="更新资料" class="botton">
                &nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="submit" name="action" value="刷新重写" class="botton">
              </div></td>
          </tr>
        </table></td>
  </tr>
</table>
<? } ?>
</form>
</body>
</html>
