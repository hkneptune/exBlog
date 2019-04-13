<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L] exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 公共常用函数
*\===========================================================================*/

###### 设置一些公共变量 ######
function setGlobal()
{
	global $x,$exBlog;
	$x['admin'] = "$exBlog[one]"."admin";
	$x['aboutme'] = "$exBlog[one]"."aboutme";
	$x['announce'] = "$exBlog[one]"."announce";
	$x['article'] = "$exBlog[one]"."article";
	$x['blog'] = "$exBlog[one]"."blog";
	$x['comment'] = "$exBlog[one]"."comment";
	$x['global'] = "$exBlog[one]"."global";
	$x['links'] = "$exBlog[one]"."links";
	$x['sort'] = "$exBlog[one]"."sort";
	$x['visits'] = "$exBlog[one]"."visits";
	$x['user'] = "$exBlog[one]"."user";
	$x['online'] = "$exBlog[one]"."online";
	$x['photo'] = "$exBlog[one]"."photo";
	$x['weather'] = "$exBlog[one]"."weather";
	
}

###### 检查用户名是否合法 ######
function checkUser($user)
{
    if(strlen($user)>15)
	{
        $result.="用户名不能超过15个字符!<br>";
        return $result;
    }
    if(trim($user)=="")
	{
        $result.="用户名不能为空!<br>";
        return $result;
	}
    if(eregi("[<>{}(),%#|^&!`$]",$user))
	{
        $result.="用户名只能用a-z,0-9和'_'线组成!<br>";
        return $result;
	}
}

###### 检查EMAIL是否合法 ######
function checkEmail($email)
{
    if(!trim($email)=="")
	{
        if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email))
		{ 
            $result.="Email格式不正确!<br>";
            return $result;
		}
	}
}

######### 检查用户提交内容是否合法 #########
function checkContent($content)
{
    if(trim($content)=="")
	{
        $result.="内容不能为空!<br>";
        return $result;
	}
    if(strlen($content)<4)
	{
        $result.="内容不能少于4个字符!<br>";
        return $result;
	}
}

######### 检查密码是否合法 ######### 
function checkPassword($iPasswd_1, $iPasswd_2)
{
	if(trim($iPasswd_1) == "" && trim($iPasswd_2 == ""))
	{
		$result.="密码不为能空!<br>";
		return $result;
	}
	elseif($iPasswd_1 != $iPasswd_2)
	{
		$result.="两次密码输入不一致!<br>";
		return $result;
	}
	elseif(strlen($iPasswd_1) < 6)
	{
		$result="密码长度不能小于6位!<br>";
		return $result;
	}
	elseif(eregi("[<>{}(),%#|^&!`$]",$iPasswd_1))
	{
        $result.="密码中不能含有特殊字符!<br>";
        return $result;
	}
}

######### 显示错误页面 #########
function showError($msg)
{
?>
<!-- css -->
<style type="text/css">
.menu { font-family: "Verdana"; font-size: 8pt; font-weight: bold; }
.content { font-family: "Verdana"; font-size: 8pt; }
.border { border: 1px #000000 dotted; }
a:visited {  color: #000000; text-decoration: none }
a:link {  color: #000000; text-decoration: none }
a:hover {  color: blue; text-decoration: underline }
</style>
<!-- css -->
<title> Error 错误</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="Javascript">
	function Show(divid) {
	divid.filters.revealTrans.apply(); 
	divid.style.visibility = "visible"; 
	divid.filters.revealTrans.play(); 
}
</script>
</head>
<body Onload="Show(msgboard)">
<div id="msgboard" style="FILTER: revealTrans(transition=23,duration=0.5) blendTrans(duration=0.5); position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1; visibility: hidden">
<table cellspacing="0" cellpadding="5" width="100%" class="border">
<tr bgcolor="#99CC66">
<td height="11" align="left" bgcolor="#E1E4CE" class="menu"> Error 错误原因可能是:</td></tr><tr> 
<td align="left" class="content"><font color="red"><br><? echo $msg; ?></font>
<p align="left"><a href="javascript:history.go(-1);">&lt;&gt; 点击这里返回上一页 &lt;&gt;</a></p></td></tr>
</table></div>
<?
exit;
}

######### 对话框 #########
function display($iShowMsg,$iShowReturn)
{
?>
<html><head>
<!-- css -->
<style type="text/css">
.menu { font-family: "Verdana"; font-size: 8pt; font-weight: bold; }
.content { font-family: "Verdana"; font-size: 8pt; }
.border { border: 1px #000000 dotted; }
a:visited {  color: #000000; text-decoration: none }
a:link {  color: #000000; text-decoration: none }
a:hover {  color: blue; text-decoration: underline }
</style>
<!-- css -->
<title>POST成功!</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta HTTP-EQUIV="REFRESH" CONTENT="2;URL=<? echo "$iShowReturn"; ?>">
<script language="Javascript">
	function Show(divid) {
	divid.filters.revealTrans.apply(); 
	divid.style.visibility = "visible"; 
	divid.filters.revealTrans.play(); 
}
	function Hide(divid) {
	divid.filters.revealTrans.apply();
	divid.style.visibility = "hidden";
	divid.filters.revealTrans.play();
}
</script>
</head>
<body Onload="Show(msgboard)">
<div id="msgboard" style="FILTER: revealTrans(transition=23,duration=0.5) blendTrans(duration=0.5); position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1; visibility: hidden">
<table cellspacing="0" cellpadding="5" width="100%" class="border">
<tr bgcolor="#99CC66">
<td height="11" align="left" bgcolor="#E1E4CE" class="menu">系统消息:POST成功!</td></tr><tr> 
<td align="left" class="content"><font color="red"><? echo "$iShowMsg"; ?></font> 
<p align="left"><a href="<? echo "$iShowReturn"; ?>"> 两秒钟后将自动返回...<br>
如果你不想等待或浏览器没有自动返回请点击这里返回! </a></p></td></tr>
</table></div>
<script>setTimeout('Hide(msgboard)',3000);</script>
</body></html>
<?
    exit;
}
?>