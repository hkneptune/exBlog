<?php
/*==================================================================
*\    exBlog Ver: 1.3.0 exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\------------------------------------------------------------------
*\    Copyright(C) exSoft Team,  2004 - 2005. All rights reserved
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
	$x['user'] = "$exBlog[one]"."user";
	$x['online'] = "$exBlog[one]"."online";
	//$x['photo'] = "$exBlog[one]"."photo";
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

######### 用户登录 #########
function userLogin($iUser, $iPassword, $initVal, $postVal, $wloc)
{
	global $x, $langpubfun;
	if (!checkImageVal($initVal, $postVal))
	{
		showError("$langpubfun[17]");
	}
	$iPassword=md5($iPassword);
	$query1=mysql_query("SELECT * FROM `$x[admin]` WHERE user='$iUser' AND password='$iPassword'");
	$result1=mysql_num_rows($query1);
 if ($result1) {
        $res=mysql_fetch_array($query1);
 }
//	$query2=mysql_query("SELECT * FROM `$x[user]` WHERE name='$iUser' AND password='$iPassword'");
//	$result2=mysql_num_rows($query2);
// if ($result2) {
//        $res=mysql_fetch_array($query2);
//        $exBlogUserhomepage=$res[homepage];
//        $exBlogUserhomeqq=$res[qq];
// }
           $result=0;
//           $result=$result1+$result2;
           $result=$result1;
	if($result)
	{
  			setcookie("exBlogUser","",time()-3600);
  			setcookie("exBlogUserid","",time()-3600);
			setcookie("exBlogUserpassword","",time()-3600);
  			setcookie("exBlogUseremail","",time()-3600);
  			setcookie("exBlogUserhomepage","",time()-3600);
			setcookie("exBlogUserqq","",time()-3600);

			setcookie("exBlogUser","$iUser||$res[email]",time()+86400);
			setcookie("exBlogUserid",$iUser,time()+86400);
			setcookie("exBlogUserpassword",$iPassword,time()+86400);
			setcookie("exBlogUseremail",$res[email],time()+86400);
			setcookie("exBlogUserhomepage",$exBlogUserhomepage,time()+86400);
			setcookie("exBlogUserqq",$exBlogUserhomeqq,time()+86400);
		session_start();
		$_SESSION['exPass']=$iUser;
		$_SESSION['userID'] = $set['id'];
        if ($wloc == "frame.php")
		header("Location: frame.php");
        elseif ($wloc == "no")
        $wloc="no";
        else
        display($langpubfun[22],"./index.php");
	}
	else
	{
		showError("$langpubfun[18]");
	}
}

######### 用户退出登录 #########
function userLogout($wloc)
{
	global $langpubfun;
	setcookie("exBlogUser","",time()-3600);
  			setcookie("exBlogUserid","",time()-3600);
			setcookie("exBlogUserpassword","",time()-3600);
  			setcookie("exBlogUserhomepage","",time()-3600);
			setcookie("exBlogUserqq","",time()-3600);
	session_start();
	session_unregister("exPass");
	session_destroy();
	$showMsg="$langpubfun[19]";
    $showReturn=$wloc;
    display($showMsg, $showReturn);
}

######### 检查用户是否非法登录 #########
function checkLogin()
{
	global $langpubfun;
	session_cache_limiter('private, must-revalidate');
	session_start();
	if(!session_is_registered("exPass"))
	{
		showError("$langpubfun[20]");
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

###### 检查用户权限 ######
function checkUserUid($iUser, $iUid)
{
	global $x, $langpubfun;
//	$queryUserUid="SELECT * FROM `$x[admin]` WHERE user='$iUser' AND uid='0'";
	$queryUserUid="SELECT * FROM `$x[admin]` WHERE user='$iUser'";
	$resultUserUid=mysql_query($queryUserUid) or die("$langpubfun[20]");
	$rows=mysql_fetch_array($resultUserUid);
	$Uid=$rows[uid];
	if ($Uid>$iUid) showError("$langpubfun[21]");	//数越大，权限越低
//	$userUidNum=mysql_num_rows($resultUserUid);
//	if(!$userUidNum) showError("$langpubfun[21]");
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

######### 对话框 #########
function display2($iShowMsg,$iShowReturn)
{
	global $langpublic, $langpubfun;
?>
</form>
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
</head>
<body dir="<?php echo $langpublic[dir]; ?>">
<div id="msgboard" style="FILTER: revealTrans(transition=23,duration=0.5) blendTrans(duration=0.5); position:absolute; left:100px; top:100px; width:350px; height:100px; z-index:1; visibility: hidden">
<table cellspacing="0" cellpadding="5" width="100%" class="border">
<tr bgcolor="#99CC66">
<td height="11" align="left" bgcolor="#E1E4CE" class="menu"><?php echo $langpubfun[14]; ?></td></tr><tr>
<td align="left" class="content"><font color="red"><? echo "$iShowMsg"; ?></font> 
<p align="left"><a href="<? echo "$iShowReturn"; ?>"><?php echo $langpubfun[16]; ?></a></p></td></tr>
</table></div>
</body></html>
<?
    exit;
}
?>
