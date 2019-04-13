<?php
/*=================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: 公共常用函数
*\================================================================*/

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
	$x['online'] = "$exBlog[one]"."online";
	$x['weather'] = "$exBlog[one]"."weather";
	$x['trackback'] = "$exBlog[one]"."trackback";
	$x['upload'] = "$exBlog[one]"."upload";
	$x['keyword'] = "$exBlog[one]"."keyword";
}

###### 通用的语言信息 ######
function langpublic()
{
	global $t, $langpublic, $langpubfun;

  	  $t->set_var(array("langlanguage"=>$langpublic[language],
"langcharset"=>$langpublic[charset],
				 "langdir"=>$langpublic[dir]));
}

###### 前台的语言信息 ######
function langblogshow()
{
	global $t, $lang, $langfun;

 reset($lang);
 while(list($key,$langgo)=each($lang))
 {
     $t->set_var("lang".$key,$langgo);
 }

}

######### 查询语言目录 ######
function selectLangURL()
{
	global $langURL, $x;
	$sql=@mysql_query("SELECT * FROM `$x[global]`");
	$tmp=@mysql_fetch_array($sql);
	$langURL=$tmp['langURL'];
}

###### 后台的语言信息 ######
function langadmin()
{
	global $langadfun;

}

########################### 用户列表函数 ###########################
/* 查询about相关信息 */
function selectAbout()
{
	global $x;
	$queryAbout="SELECT * FROM `$x[aboutme]`";
	$aboutInfo=mysql_fetch_array(mysql_query($queryAbout));
	return $aboutInfo;
}
/* 查询超级用户列表 */
function selectAdmin()
{
	global $resultAdmin, $x, $langpubfun;
	$query="SELECT * FROM `$x[admin]` WHERE uid='0'";
	$resultAdmin=mysql_query($query) or die("$langfun[24]");
}

/* 查询普通用户列表 */
function selectUser()
{
	global $resultUser, $x, $langpubfun;
	$query="SELECT * FROM `$x[admin]` WHERE uid='1'";
	$resultUser=mysql_query($query) or die("$langfun[25]");
}

/* 查询访客用户列表 */
function selectVisitor()
{
	global $resultVisitor, $x, $langpubfun;
	$query="SELECT * FROM `$x[admin]` WHERE uid='3'";
	$resultVisitor=mysql_query($query) or die("$langfun[26]");
}

######### 用户登录 #########
function userLogin($iUser, $iPassword, $initVal, $postVal, $wloc, $iShowadmin)
{
	global $x, $langpubfun;
	if (!checkImageVal($initVal, $postVal))
	{
        	if($iShowadmin=="admin")showError("$langpubfun[17]");
		elseif($iShowadmin=="show")showError_show("$langpubfun[17]");
	}
	$iPassword=md5($iPassword);

	$query=mysql_query("SELECT * FROM `$x[admin]` WHERE user='$iUser' AND password='$iPassword'");
	$result=mysql_num_rows($query);

	if($result)
	{
        	$res=mysql_fetch_array($query);
  			setcookie("exBlogUser","",time()-3600);
			setcookie("exBlogUser","$iUser||$res[email]",time()+86400);

		session_start();
		session_unregister("exPass");
		session_unregister("exPassword");
		session_unregister("userID");
		$_SESSION['exPass'] = $iUser;
		$_SESSION['exPassword'] = $iPassword;
		$_SESSION['userID'] = $res['id'];
		$_SESSION['exUseremail'] = $res[email];
        if ($wloc == "frame.php")
		header("Location: frame.php");
        elseif ($wloc == "no")
        $wloc="no";
        else
        	if($iShowadmin=="admin")display($langpubfun[22],"./index.php");
		elseif($iShowadmin=="show")display_show($langpubfun[22],"./index.php");
	}
	else
	{
        	if($iShowadmin=="admin")showError("$langpubfun[18]");
		elseif($iShowadmin=="show")showError_show("$langpubfun[18]");
	}
}

######### 用户退出登录 #########
function userLogout($wloc, $iShowadmin)
{
	global $langpubfun;
	setcookie("exBlogUser","",time()-3600);
/*
  			setcookie("exBlogUserid","",time()-3600);
			setcookie("exBlogUserpassword","",time()-3600);
  			setcookie("exBlogUserhomepage","",time()-3600);
			setcookie("exBlogUserqq","",time()-3600);
*/
	session_start();
	session_unregister("exPass");
	session_unregister("exPassword");
	session_unregister("userID");
	session_unregister("exUseremail");
	session_destroy();
	$showMsg="$langpubfun[19]";
	$showReturn=$wloc;
        	if($iShowadmin=="admin")display($showMsg, $showReturn);
		elseif($iShowadmin=="show")display_show($showMsg, $showReturn);
}

######### 对非法登录用户进行提示 #########
function checkLogin()
{
	global $langpubfun;

	if(checkunregist())
	{
		showError("$langpubfun[20]");
	}
}

###### 检查用户是否登陆 ######
function checkunregist()
{
	session_cache_limiter('private, must-revalidate');
	session_start();
	if(session_is_registered("exPass") && session_is_registered("userID") && session_is_registered("exPassword"))
	{ 
		return false;
	}
	else
	{
		return ture;
	}
}

###### 检查用户是否存在 ######
function checkuserexist($iAuthor, $iPassword, $iUid)
{
	global $x;
if ( $iPassword!="NULL" ) $s_password="AND password = '$iPassword'";
if ( $iUid!="NULL" ) $s_uid="AND uid = '$iUid'";
	$query = "SELECT user FROM `$x[admin]` WHERE user = '$iAuthor' $s_password $s_uid";
	$result = mysql_query($query);
	$result=mysql_num_rows($result);

	if( $result )
	{ 
		return ture;
	}
	else
	{
		return false;
	}
}


/*
 *  Name:          checkImageVal()
 *  Parameter:     $initVal 随机生成的验证码值
 *                 $postVal 用户填写的验证码值
 *  Description:   对比用户输入验证码是否和系统生成的一样
 *	Return:        相为返回'true' 不相同返回'false' (Boolean)
 *  Write:         elliott [at] 2004-07-29
*/
function checkImageVal($initVal, $postVal)
{
	if (strcmp($initVal, $postVal) == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

###### 对用户权限不符进行提示 ######
function checkUserUid($iUser, $iUid)
{
	global $langpubfun;
	if (fun_checkUserUid($iUser, $iUid)) showError("$langpubfun[21]");
}

###### 检查用户权限 ######
function fun_checkUserUid($iUser, $iUid)
{
	global $x, $langpubfun;
	$queryUserUid="SELECT * FROM `$x[admin]` WHERE user='$iUser'";
	$resultUserUid=mysql_query($queryUserUid) or die("$langpubfun[20]");
	$rows=mysql_fetch_array($resultUserUid);
	$Uid=$rows[uid];
	if ($Uid>$iUid)	//数越大，权限越低
	{
		return true;
	}
	else
	{
		return false;
	}
}

###### 检查用户名是否合法 ######
function checkUser($user)
{
	global $langpubfun;
    if(strlen($user)>15)
	{
        $result.="$langpubfun[0]";
        return $result;
    }
    if(trim($user)=="")
	{
        $result.="$langpubfun[1]";
        return $result;
	}
    if(eregi("[<>{}(),%#|^&!`$]",$user))
	{
        $result.="$langpubfun[2]";
        return $result;
	}
}

###### 检查EMAIL是否合法 ######
function checkEmail($email)
{
	global $langpubfun;
    if(!trim($email)=="")
	{
        if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email))
		{ 
            $result.="$langpubfun[3]";
            return $result;
		}
	}
}

######### 检查用户提交内容是否合法 #########
function checkContent($content)
{
	global $langpubfun;
    if(trim($content)=="")
	{
        $result.="$langpubfun[4]";
        return $result;
	}
    if(strlen($content)<4)
	{
        $result.="$langpubfun[5]";
        return $result;
	}
}

######### 检查密码是否合法 ######### 
function checkPassword($iPasswd_1, $iPasswd_2)
{
	global $langpubfun;
	if(trim($iPasswd_1) == "" && trim($iPasswd_2 == ""))
	{
		$result.="$langpubfun[6]>";
		return $result;
	}
	elseif($iPasswd_1 != $iPasswd_2)
	{
		$result.="$langpubfun[7]";
		return $result;
	}
	elseif(strlen($iPasswd_1) < 6)
	{
		$result="$langpubfun[8]";
		return $result;
	}
	elseif(eregi("[<>{}(),%#|^&!`$]",$iPasswd_1))
	{
        $result.="$langpubfun[9]";
        return $result;
	}
}

###### 来自 SaBlog 的部分代码 go ######
# 虽然 exBlog 已经对相应问题进行了处理
# 但为了安全起见
# 故引用这一段代码确保了Blog的可访问性
# 向 angel 致敬 谢谢

error_reporting(7);

// 允许程序在 register_globals = off 的环境下工作
if ( function_exists('ini_get') ) {
	$onoff = ini_get('register_globals');
} else {
	$onoff = get_cfg_var('register_globals');
}
if ($onoff != 1) {
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
	@extract($_SERVER, EXTR_SKIP);
	@extract($_COOKIE, EXTR_SKIP);
	@extract($_SESSION, EXTR_SKIP);
	@extract($_FILES, EXTR_SKIP);
	@extract($_ENV, EXTR_SKIP);
}

// 去除转义字符
function stripslashes_array($array) {
	while (list($k,$v) = each($array)) {
		if (is_string($v)) {
			$array[$k] = stripslashes($v);
		} else if (is_array($v))  {
			$array[$k] = stripslashes_array($v);
		}
	}
	return $array;
}

// 判断 magic_quotes_gpc 状态
if (get_magic_quotes_gpc()) {
    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_COOKIE = stripslashes_array($_COOKIE);
}

set_magic_quotes_runtime(0);

###### 来自 SaBlog 的部分代码 end ######

######### 前台显示错误页面函数 #########
function showError_show($iShowMsg)
{
	global $t, $tmpURL;

    if (!is_dir($tmpURL) || !file_exists($tmpURL."/error.html")) {
	showError($iShowMsg);
    } else {
	$t->set_file("showError","error.html");
	$t->set_var("msg",$iShowMsg);
	$t->parse("OUT","showError");
	$t->p("OUT");
    exit;
    }
}
######### 前台显示提示页面函数 #########
function display_show($iShowMsg,$iShowReturn)
{
	global $t, $tmpURL;

    if (!is_dir($tmpURL) || !file_exists($tmpURL."/display.html")) {
	display($iShowMsg,$iShowReturn);
    } else {
	$t->set_file("display","display.html");
	$t->set_var("msg",$iShowMsg);
	$t->set_var("return",$iShowReturn);
	$t->parse("OUT","display");
	$t->p("OUT");
    exit;
    }
}

######### 显示错误页面 #########
function showError($msg)
{
	global $langpublic, $langpubfun;
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
<title><?php echo $langpubfun[10]; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
</head>
<body dir="<?php echo $langpublic[dir]; ?>">
<div id="msgboard" style="position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;">
<table cellspacing="0" cellpadding="5" width="100%" class="border">
<tr bgcolor="#99CC66">
<td height="11" align="left" bgcolor="#E1E4CE" class="menu"><?php echo $langpubfun[11]; ?></td></tr><tr>
<td align="left" class="content"><font color="red"><br><? echo $msg; ?></font>
<p align="left"><a href="javascript:history.go(-1);"><?php echo $langpubfun[12]; ?></a></p></td></tr>
</table></div>
<?
exit;
}

######### 对话框 #########
function display($iShowMsg,$iShowReturn)
{
	global $langpublic, $langpubfun;
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
<title><?php echo $langpubfun[13]; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta HTTP-EQUIV="REFRESH" CONTENT="2;URL=<? echo "$iShowReturn"; ?>">
</head>
<body dir="<?php echo $langpublic[dir]; ?>">
<div id="msgboard" style="position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;">
<table cellspacing="0" cellpadding="5" width="100%" class="border">
<tr bgcolor="#99CC66">
<td height="11" align="left" bgcolor="#E1E4CE" class="menu"><?php echo $langpubfun[14]; ?></td></tr><tr>
<td align="left" class="content"><font color="red"><? echo "$iShowMsg"; ?></font> 
<p align="left"><a href="<? echo "$iShowReturn"; ?>"><?php echo $langpubfun[15]; ?></a></p></td></tr>
</table></div>
</body></html>
<?
    exit;
}

?>
