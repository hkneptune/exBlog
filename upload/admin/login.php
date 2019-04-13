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
*\    本页说明: 用户登陆
*\===========================================================================*/


require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();

//   if ($_COOKIE['exBlogUserid']!="") header("location: ./frame.php");
	session_cache_limiter('private, must-revalidate');
	session_start();
	if(session_is_registered("exPass") && $_POST['action'] != "actlogin") header("location: ./frame.php");



//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/login.php");

	$tmpglobalInfo=selectGlobal();
	session_start();
  if($_POST['action'] == "actlogin")
  {
    if ($tmpglobalInfo[GDswitch]) {
	userLogin($_POST['user'], $_POST['passwd'], $_SESSION['sysImg'], $_POST['imgVal'], "frame.php");
    }else{
	userLogin($_POST['user'], $_POST['passwd'], 1, 1, "frame.php");
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<title><?php echo $lang[0]; ?></title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body dir="<?php echo $langpublic[dir]; ?>">
<p>&nbsp;</p>
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">
  <table width="361" border="0" align="center" cellpadding=10 cellspacing=10 class="border">
    <tr bgcolor="#99CC66"> 
    <td align="center" bgcolor="#FFFFFF" class="mycss"><table width="310" border="0" align="center" cellpadding="2" cellspacing="4" class="main">
          <tr> 
            <td width="19%" rowspan="7"><img src="../images/user_login.jpg" alt="Admin Logining..." width="60" height="70"></td>
            <td colspan="2" class="menu"><div align="center"><?php echo $lang[1]; ?></td>
          </tr>
          <tr> 
            <td><?php echo $lang[2]; ?></td>
            <td> <input name="user" type="text" maxlength="20" class="input"></td>
          </tr>
          <tr> 
            <td><?php echo $lang[3]; ?></td>
            <td><input name="passwd" type="password" maxlength="20" class="input"></td>
          </tr>
<?php
if ($tmpglobalInfo[GDswitch]) {
	echo"
          <tr>
            <td><?php echo $lang[4]; ?></td>
            <td><input name=\"imgVal\" type=\"text\" size=\"5\" class=\"input\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <img src=\"../include/checkImage.php\" align=\"absmiddle\"></td>
          </tr>
	";
}
?>
          <tr> 
            <td colspan="2"><div align="center"> 
	  <input type="hidden" name="action" value="actlogin">
                <input type="submit" value="<?php echo $lang[5]; ?>" class="botton">
                &nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="reset" value="<?php echo $lang[6]; ?>" class="botton">
              </div></td>
          </tr>
          <tr> 
            <td colspan="2" align="center"><a href="../index.php"><?php echo $lang[7]; ?></a></td>
          </tr>
        </table>
	</td>
    </tr>
</table>
</form>
</body>
</html>
