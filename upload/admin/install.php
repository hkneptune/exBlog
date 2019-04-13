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
*\    本页说明: 安装exBlog页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/createSql.inc.php");
require("../include/global-C.inc.php");

###### 创建管理员 ######
if($_POST['action'] == "创建管理员")
{
	CreateSystem($_POST['user'], $_POST['password'], $_POST['password_2'], $_POST['email'], $exBlog['one']);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>exBlog Ver: 1.2.0 RC1 安装向导...... exSoft Studio</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<form method="post" action="<? echo $PHP_SELF; ?>">
<?php
if($_POST['action'] == "同意许可")
{
?>
  <table width=600 border="0" align="center" cellpadding=5 cellspacing=0 class="border">
    <tr bgcolor="#99CC66"> 
    <td bgcolor="#FFFFFF" class="content"><b>&nbsp;请确认新建表名前缀......</b><br>
        <br>
        <br>
		当前config.inc.php配置文件新建表名为:<font color="blue"><? echo $exBlog['one']; ?></font>
		<br>
        <br>
		此设置可以让你一个表中安装多个exblog.如果只安装一个建议不要修改!
		<br>
		<br>
		如果确认,请点击进入下一步.
		<br>
		<br>
        <div align="center">  
          <input type="submit" name="action" value="点击建立数据库各表" class="botton">
          <br>&nbsp;
        </div>
    </td>
  </tr>
</table>
<?	
    exit;
    }
	###### 建立数据库表 ######
if($_POST['action'] == "点击建立数据库各表")
{
    CreateAdmin($exBlog['one']);
	createAnnounce($exBlog['one']);
	CreateBlog($exBlog['one']);
	CreateSort($exBlog['one']);
	CreateVisits($exBlog['one']);
	CreateComment($exBlog['one']);
	CreateAboutme($exBlog['one']);
	CreateLinks($exBlog['one']);
	CreateGlobal($exBlog['one']);
	CreateWeather($exBlog['one']);
	CreatePhoto($exBlog['one']);
	createUser($exBlog['one']);
	createOnline($exBlog['one']);
?>
  <table width=600 border="0" align="center" cellpadding=5 cellspacing=0 class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF" class="content"><b>&nbsp;正在创建以下数据库各表......</b><br> <br> 
        <br>
        <br>
		<? echo $result; ?>
		<br> 
		<div align="center"> 
          <input type="submit" name="action" value="点击创建管理员" class="botton">
          <br>
          &nbsp; </div></td>
    </tr>
  </table>
<?
	exit;
}
	if($_POST[action] == "点击创建管理员")
	{
?>
  <table width=600 border="0" align="center" cellpadding=0 cellspacing=0 class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF"> 
        <table width="100%" border="0" class="main">
          <tr> 
            <td height="16" colspan="2" class="menu"><div align="center"> <strong>现在装创建系统管理员:</strong> 
              </div></td>
          </tr>
          <tr> 
            <td>管理员用户名:</td>
            <td><input name="user" type="text" class="input"></td>
          </tr>
          <tr>
            <td>管理员密码:</td>
            <td><input name="password" type="password" class="input"></td>
          </tr>
          <tr> 
            <td width="25%">管理员密码:</td>
            <td width="75%"><input name="password_2" type="password" class="input"></td>
          </tr>
          <tr> 
            <td>管理员Email:</td>
            <td><input name="email" type="text" id="email" class="input"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2">Notice: 管理员密码以md5格式存储.^*^</td>
          </tr>
        </table>
        <br>
        <br>
        <br>
        <div align="center">  
          <input type="submit" name="action" value="创建管理员" class="botton">
          <br>&nbsp;
        </div>
    </td>
  </tr>
</table>
<?
	exit;
    }
	else
	{
?>
  <table width=600 border="0" align="center" cellpadding=0 cellspacing=0 class="border">
    <tr> 
    <td bgcolor="#FFFFFF" class="menu"><div align="center"><font color="#FF0000">exBlog Ver: 1.2.0 RC1</font> 安装向导</div></td>
  </tr>
</table>
<br>
  <table width=600 border="0" align="center" cellpadding=0 cellspacing=0 class="border">
    <tr> 
    <td bgcolor="#FFFFFF" class="main"><br>
        &nbsp; <img src="../images/group.gif" width="17" height="14"> 开发者: <font color="#0000FF">exSoft Studio - elliott, hunter, Viva, sheer, Tomy, 流水流云</font> 
        版权所有 (c) 2004, <em><a href="http://exblog.fengling.net" target="_blank">exSoft 
        Studio</a></em><br>
        <p> <font color="#FF0000">&gt;</font> 用户许可协议:<br>
          <br>
          1、exBlog 为免费程序,你可以修改源代码但请保留版权信息.<br>
          个人网站可以免费使用本系统,并可自由传播,传播过程请保持本系统的完整性.<br>
          任何个人或企业未经许可不得将本系统用作商业用途.</p>
        <p> 2、使用过程中为美化程序或修改BUG您可以对源代码进行修改,如要传播您的修改版本<br>
          请保留原程序中的 exBlog 版权信息.<br>
          <br>
          3、您可以查看 exBlog 的源代码,但在没有获得作者正式许可的情况下,不能以除了<br>
          美化界面、修改BUG以外的其他任何理由修改 exBlog 的源程序,更不能修改相关<br>
          说明档和程序显示的版权信息. </p>
        <p> 4、exBlog 是开放源代码软件,如果您对 exBlog 有好的想法和意见,并希望自己<br>
          修改 exBlog 完善其功能时,必须与我们取得联系,在获得我们正式的许可后,<br>
          在完整保留 exBlog 版权信息和说明档的前提下. 对 exBlog 进行修改. </p>
        <p> 5、本程序欢迎转载,您可以在原样完整保留全部版权信息和说明档的前提下,传播和发布<br>
          本程序,但将 exBlog 用于商业目的或违背上述条款的传播都是被禁止的. </p>
        <p> 6、安装 exBlog 建立在完全同意本授权文档的基础之上,因此而产生的纠纷,违反授权<br>
          协议的一方将承担全部责任.</p>
        <p> 7、如您想获得技术支持,请EMAIL至exblog@fengling.net进行完全免费的注册,注明网站URL<br>
          在以后过程中如程序有BUG或升级将通过EMAIL通知您.</p>
        <p align="right">exSoft Studio - exSoft 开发组<br>
          2004.10.22 </p>
        <div align="center"> 
          <input type="submit" name="action" value="同意许可" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" name="action" value="不同意" onclick="window.close()" class="botton">
          <br>
          <br>
        </div>
        </td>
  </tr>
</table>
<? } ?>
</form>
</body>
</html>
