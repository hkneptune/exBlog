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
*\    本页说明: 用户登陆
*\===========================================================================*/


require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();

session_start();
if($_POST['action'] == "用户登录")
{
	userLogin($_POST['user'], $_POST['passwd'], $_SESSION['sysImg'], $_POST['imgVal']);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>exBlogMix 后台登录界面</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<p>&nbsp;</p>
<form method="post" action="<? echo $PHP_SELF; ?>">
  <table width="361" border="0" align="center" cellpadding=10 cellspacing=10 class="border">
    <tr bgcolor="#99CC66"> 
    <td align="center" bgcolor="#FFFFFF" class="mycss"><table width="310" border="0" align="center" cellpadding="2" cellspacing="4" class="main">
          <tr> 
            <td width="19%" rowspan="7"><img src="../images/user_login.jpg" alt="Admin Logining..." width="60" height="70"></td>
            <td colspan="2" class="menu"><div align="center">#. Exblog 管理登录</td>
          </tr>
          <tr> 
            <td>用户名(User): </td>
            <td> <input name="user" type="text" maxlength="20" class="input"></td>
          </tr>
          <tr> 
            <td>密　码(PWD):</td>
            <td><input name="passwd" type="password" maxlength="20" class="input"></td>
          </tr>
          <tr>
            <td>验证码:</td>
            <td><input name="imgVal" type="text" size="5" class="input">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <img src="../include/checkImage.php" align="absmiddle"></td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center"> 
                <input type="submit" name="action" value="用户登录" class="botton">
                &nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="reset" name="刷新重写" value="刷新重写" class="botton">
              </div></td>
          </tr>
          <tr> 
            <td colspan="2" align="center"><a href="../index.php">返回首页[Home]</a></td>
          </tr>
        </table>
	</td>
    </tr>
</table>
</form>
</body>
</html>