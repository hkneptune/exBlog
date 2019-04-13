<?php
/*=================================================================
*\   exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2004 - 2005. All rights reserved
*\-----------------------------------------------------------------
*\   主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\-----------------------------------------------------------------
*\   本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\-----------------------------------------------------------------
*\   本页说明: 安装exBlog页面
*\===============================================================*/

$GLOBALS[exBlogVer] = "exBlog Ver: 1.3.0";	//当前版本 显示用
$GLOBALS[exBlogVer_s] = "exBlog 1.3.0";		//当前版本 检测用(用户千万不要修改)
$GLOBALS[langURL]="language/chinese_gb2312";	//设置默认语言包目录

//---- 语言包 ----//
include("../$langURL/public.php");
include("../$langURL/install.php");

require("../include/createSql.inc.php");
require("../include/global-C.inc.php");

setup_head();

###### 欢迎 ######
if($GLOBALS[action] == "" || $_POST[action] == "step_welcome")
{
    step_welcome();

###### 用户许可协议 ######
}elseif($_POST[action] == "step_licence")
{
    step_licence();

###### 数据库配置 ######
}elseif($_POST[action] == "step_db")
{
    step_db();

###### 创建数据库 ######
}elseif($_POST[action] == "step_create_db")
{
    step_create_db();

###### 创建 config.inc.php 配置文件 ######
}elseif($_POST[action] == "step_create_config")
{
    step_create_config($_POST[host], $_POST[user], $_POST[password], $_POST[dbname] ,$_POST[one]);

###### 确认 config.inc.php 配置文件 ######
}elseif($_POST[action] == "step_config")
{
    include("../include/config.inc.php");
    step_config();

###### 建立数据库表 ######
}elseif($_POST[action] == "step_create_table")
{
    include("../include/config.inc.php");
    step_create_table();

###### 基本资料 ######
}elseif($_POST[action] == "step_global")
{
    include("../include/config.inc.php");
    step_global();
}elseif($_POST[action] == "step_global2")
{
    include("../include/config.inc.php");
	CreateGlobal_go($_POST['exBlogName'],$_POST['exBlogUrl'],$_POST['exBlogCopyright'],
			 $_POST['templatesURL'],
			 $_POST['description'], $_POST['webmaster'],
			 $_POST['sitekeyword'], $_POST['languageURL'])
	? step_global2(1)
	: step_global2(0);

###### 创建管理员 ######
}elseif($_POST[action] == "step_admin")
{
    include("../include/config.inc.php");
    step_admin();
}elseif($_POST[action] == "step_admin2")
{
    include("../include/config.inc.php");
	CreateSystem($_POST['user'], $_POST['password'], $_POST['password_2'],
		 	$_POST['email'], $_POST['phone'], $exBlog['one'])
	? step_admin2(1)
	: step_admin2(0);

###### 结束 ######
}elseif($_POST[action] == "step_end")
{
    include("../include/config.inc.php");
    step_end();
###### 删除install.php ######
}elseif($_POST[action] == "step_del")
{
    step_del();
}

setup_foot();

##############################
###### 页面头 ######
function setup_head()
{
	global $exBlogVer;
	global $lang, $langpublic;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<title><?php echo $exBlogVer; ?> <?php echo $lang[0]; ?>...... exSoft Team</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
<style>
<!--
.nowt        { text-align: center; font-weight: bold; background-color: #CCCCCC; font-size: 7pt }
.noww        { text-align: center; font-size: 7pt }
-->
</style>
</head>
<body dir="<?php echo $langpublic[dir]; ?>">
<div class="notice">
<img src="../images/china.gif" alt="China" border="0" /><b>为了世界和平,反对日本成为安理会常任理事国!</b>
</div>
<form method="post" action="<?php echo $PHP_SELF; ?>">
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
    <tr> 
    <td bgcolor="#FFFFFF" class="menu"><div align="center"><font color="#FF0000"><?php echo $exBlogVer; ?></font> <?php echo $lang[0]; ?></div></td>
  </tr>
</table>
<br />
<?php
}
###### 页面脚 ######
function setup_foot()
{
?>
</form>
</body>
</html>
<?php
}
###### 欢迎 函数 ######
function step_welcome()
{
	global $exBlogVer;
	global $lang, $langpublic;
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="nowt">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
    <tr>
    <td bgcolor="#FFFFFF" class="main"><br />
        &nbsp; <img src="../images/group.gif" width="17" height="14"> <?php echo $lang[1]; ?><br />
        <p><img src="../images/exblogface.gif" align="right" border="0">
	 <font color="#FF0000">&gt;</font> <?php echo $lang[2]; ?>
        <div align="center">
          <input type="hidden" name="action" value="step_licence">
          <input type="submit" value="<?php echo $lang[3]; ?>" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="button" value="<?php echo $lang[4]; ?>" onclick="window.close()" class="botton">
          <br />
          &nbsp; </div></td>
  </tr>
</table>
<?php
}
###### 用户许可协议 函数 ######
function step_licence()
{
	global $exBlogVer;
	global $lang, $langpublic;
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="nowt">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
    <tr> 
    <td bgcolor="#FFFFFF" class="main"><br />
        &nbsp; <img src="../images/group.gif" width="17" height="14"> <?php echo $lang[1]; ?><br />
        <p> <font color="#FF0000">&gt;</font> <?php echo $lang[5]; ?>
        <p align="right"><?php echo $lang[6]; ?><br />
          2004.10.31</p>
        <div align="center">
          <input type="hidden" name="action" value="step_db">
          <input type="submit" value="<?php echo $lang[7]; ?>" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="button" value="<?php echo $lang[8]; ?>" onclick="window.close()" class="botton">
          <br />
          &nbsp; </div></td>
  </tr>
</table>
<?php
}
###### 数据库配置 函数 ######
function step_db()
{
	global $exBlogVer;
	global $lang, $langpublic;
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="nowt">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF"> 
        <table width="100%" border="0" class="main">
          <tr> 
            <td height="16" colspan="2" class="menu"><div align="center"> <strong><?php echo $lang[9]; ?></strong>
              </div></td>
          </tr>

          <tr>
            <td><?php echo $lang[10]; ?></td>
            <td><input name="host" type="text" class="input" value="localhost"></td>
          </tr>
          <tr>
            <td><?php echo $lang[11]; ?></td>
            <td><input name="user" type="text" class="input" value="root"></td>
          </tr>
          <tr>
            <td><?php echo $lang[12]; ?></td>
            <td><input name="password" type="text" class="input" value=""></td>
          </tr>
          <tr>
            <td><?php echo $lang[13]; ?></td>
            <td><input name="dbname" type="text" class="input" value="test"><input name="mySQL_create" value="1" type="checkbox" id="mySQL_create" /> <label for="mySQL_create">创建</label></td>
          </tr>
          <tr>
            <td><?php echo $lang[14]; ?></td>
            <td><input name="one" type="text" class="input" value="exblog_"></td>
          </tr>

          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><?php echo $lang[15]; ?></td>
          </tr>
        </table>
        <br />
        <br />
        <br />
        <div align="center">  
          <input type="hidden" name="action" value="step_create_db">
          <input type="button" value="<?php echo $lang[16]; ?>" onClick="window.location='install.php?action=step_licence';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="reset" value="<?php echo $lang[17]; ?>" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
  </tr>
</table>
<?php
}
###### 创建数据库 函数 ######
function step_create_db()
{
	global $host, $user, $password, $dbname ,$one, $exBlogVer, $mySQL_create;
	global $lang, $langpublic;

    if ($mySQL_create == 1) {
	@mysql_create_db( $dbname )
	 ? $result = $lang[19].$dbname.$lang[20]
	 : $result = $lang[19].$dbname.$lang[21];

?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="nowt">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF" class="content"><b><?php echo $lang[22]; ?></b><br /> <br />
        <br />
        <br />
		<? echo $result; ?>
		<br />
          <input type="hidden" name="host" value="<?php echo $host; ?>">
          <input type="hidden" name="user" value="<?php echo $user; ?>">
          <input type="hidden" name="password" value="<?php echo $password; ?>">
          <input type="hidden" name="dbname" value="<?php echo $dbname; ?>">
          <input type="hidden" name="one" value="<?php echo $one; ?>">
		<div align="center">
          <input type="hidden" name="action" value="step_create_config">
          <input type="button" value="<?php echo $lang[18]; ?>" onClick="window.location='install.php?action=step_db';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php
    }else{
	step_create_config($host, $user, $password, $dbname ,$one);
    }
}
###### 创建 config.inc.php 配置文件 函数 ######
function step_create_config($host, $user, $password, $dbname ,$one)
{
	global $exBlogVer;
	global $lang, $langpublic;

	$result = $lang[23];

 if (@file_exists('../include/config.inc.php')){
       is_writable('../include/config.inc.php')
       ? $e=1
       : $result = $lang[23];
 }

	if ($fp = @fopen('../include/config.inc.php', 'w')) {
		$config_data = "<" . "?php \n";
		$config_data .= "\n";
		$config_data .= "######### exBlog网络日志相关设置 #########\n\t";
		$config_data .= "	\$exBlog['host'] = \"" . $host . "\";		//数据库地址\n";
		$config_data .= "	\$exBlog['user'] = \"" . $user . "\";		//数据库用户名\n";
		$config_data .= "	\$exBlog['password'] = \"" . $password . "\";	//数据库密码\n";
		$config_data .= "	\$exBlog['dbname'] = \"" . $dbname . "\";	//数据库名\n";
		$config_data .= "	\$exBlog['one'] = \"" . $one . "\";		//新建数据表名前缀\n";
		$config_data .= "\n";
		$config_data .= "######### 以下不用改动 #########\n";
		$config_data .= "	include(\"condb.php\");\n";
		$config_data .= "	connect_db();\n";
		$config_data .= "?" . ">";

		$result = @fputs($fp, $config_data, strlen($config_data));
		fclose($fp);

			if ($result)
				$result = $lang[24];
	}
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="nowt">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF" class="content"><b> <?php echo $lang[25]; ?></b><br /> <br />
        <br />
        <br />
		<? echo $result; ?>
		<br />
		<div align="center">
          <input type="hidden" name="action" value="step_config">
          <input type="button" value="<?php echo $lang[16]; ?>" onClick="window.location='install.php?action=step_db';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php
}
###### 确认 config.inc.php 配置文件 函数 ######
function step_config()
{
	global $exBlogVer;
	global $exBlog;
	global $lang, $langpublic;
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="nowt">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
    <td bgcolor="#FFFFFF" class="content"><b> <?php echo $lang[26]; ?></b><br />
        <br />
        <br />
		<?php echo $lang[27]; ?><font color="blue"><?php echo $exBlog['dbname']; ?></font>
		<br />
        <br />
        <b><?php echo $lang[28]; ?></b><br />
        <br />
        <br />
		<?php echo $lang[29]; ?><font color="blue"><?php echo $exBlog['one']; ?></font>
		<br />
        <br />
		<?php echo $lang[30]; ?>
		<br />
		<br />
		<?php echo $lang[31]; ?>
		<br />
		<br />
        <div align="center">
          <input type="hidden" name="action" value="step_create_table">
          <input type="button" value="<?php echo $lang[16]; ?>" onClick="window.location='install.php?action=step_db';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />&nbsp;
        </div>
    </td>
  </tr>
</table>
<?php
}
###### 建立数据库表 函数 ######
function step_create_table()
{
	global $exBlogVer, $result;
	global $exBlog;
	global $lang, $langpublic;

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
	//CreatePhoto($exBlog['one']);
	CreateUpload($exBlog['one']);
	createUser($exBlog['one']);
	createOnline($exBlog['one']);
	createTrackback($exBlog['one']);
	//createAttachment($exBlog['one']);
	CreateKeyword($exBlog['one']);
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="nowt">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF" class="content"><b> <?php echo $lang[32]; ?></b><br /> <br />
        <br />
        <br />
		<? echo $result; ?>
  <br />
		<div align="center">
          <input type="hidden" name="action" value="step_global">
          <input type="button" value="<?php echo $lang[16]; ?>" onClick="window.location='install.php?action=step_config';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php
}
###### 基本资料 函数 ######
function step_global()
{
	global $exBlogVer;
	global $lang, $langpublic;
 
$readDir2=readLanguage();            ### 读语言目录
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="nowt">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF"> 
        <table width="100%" border="0" class="main">
          <tr> 
            <td height="16" colspan="2" class="menu"><div align="center"> <strong><?php echo $lang[33]; ?></strong>
              </div></td>
          </tr>

          <tr>
            <td><?php echo $lang[34]; ?></td>
            <td><input name="exBlogName" type="text" class="input" value="exBlog"></td>
          </tr>
          <tr>
            <td><?php echo $lang[35]; ?></td>
            <td><input name="exBlogUrl" type="text" class="input" value="http://www.exBlog.net"></td>
          </tr>
          <tr>
            <td><?php echo $lang[36]; ?></td>
            <td><input name="exBlogCopyright" type="text" class="input" value="Copyright &copy; 2003-2005 &lt;a href='http://www.exblog.net' target='_blank' class='copy'&gt;exBlog ver: 1.3.0&lt;/a&gt; All Rights Reserved."></td>
          </tr>
          <tr>
            <td><?php echo $lang[37]; ?></td>
            <td><select name="languageURL" class="botton">
          <?php
               $cout=count($readDir2);
               for($i=0; $i<$cout; $i++)
               {
           ?>
          <option value="language/<? echo $readDir2[$i]; ?>"
				<? searchCurLanguage($readDir2[$i]); ?>> <? echo $readDir2[$i]; ?>
          </option>
          <?php } ?>
        </select></td>
          </tr>
          <tr>
            <td><?php echo $lang[38]; ?></td>
            <td><input name="templatesURL" type="text" class="input" value="templates/i7c"></td>
          </tr>
          <tr>
            <td><?php echo $lang[39]; ?></td>
            <td><input name="description" type="text" class="input" value="Power by exBlog.Net"></td>
          </tr>
          <tr>
            <td><?php echo $lang[40]; ?></td>
            <td><input name="webmaster" type="text" class="input" value="exSoft (support@exblog.net)"></td>
          </tr>
          <tr>
            <td><?php echo $lang[41]; ?></td>
            <td><input name="sitekeyword" type="text" class="input" value="exsoft,exblog,blog,weblog,blog,"></td>
          </tr>

          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><?php echo $lang[42]; ?></td>
          </tr>
        </table>
        <br />
        <br />
        <br />
        <div align="center">  
          <input type="hidden" name="action" value="step_global2">
          <input type="reset" value="<?php echo $lang[17]; ?>" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
  </tr>
</table>
<?php

}
###### 基本资料2 函数 ######
function step_global2($on)
{
	global $exBlogVer;
	global $lang, $langpublic;

    $on
    ? $result = $lang[43]
    : $result = $lang[44];
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="nowt">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF" class="content"><b> <?php echo $lang[45]; ?></b><br /> <br />
        <br />
        <br />
		<? echo $result; ?>
  <br />
		<div align="center">
          <input type="hidden" name="action" value="step_admin">
          <input type="button" value="<?php echo $lang[16]; ?>" onClick="window.location='install.php?action=step_global';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php

}
###### 创建管理员 函数 ######
function step_admin()
{
	global $exBlogVer;
	global $exBlog;
	global $lang, $langpublic;

?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="nowt">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF"> 
        <table width="100%" border="0" class="main">
          <tr> 
            <td height="16" colspan="2" class="menu"><div align="center"> <strong><?php echo $lang[46]; ?></strong>
              </div></td>
          </tr>
          <tr> 
            <td><?php echo $lang[47]; ?></td>
            <td><input name="user" type="text" class="input"></td>
          </tr>
          <tr>
            <td><?php echo $lang[48]; ?></td>
            <td><input name="password" type="password" class="input"></td>
          </tr>
          <tr> 
            <td width="25%"><?php echo $lang[49]; ?></td>
            <td width="75%"><input name="password_2" type="password" class="input"></td>
          </tr>
          <tr> 
            <td><?php echo $lang[50]; ?></td>
            <td><input name="email" type="text" id="email" class="input"></td>
          </tr>
          <tr> 
            <td><?php echo $lang[51]; ?></td>
            <td><input name="phone" type="text" id="phone" class="input"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><?php echo $lang[52]; ?></td>
          </tr>
        </table>
        <br />
        <br />
        <br />
        <div align="center">  
          <input type="hidden" name="action" value="step_admin2">
          <input type="reset" value="<?php echo $lang[17]; ?>" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
  </tr>
</table>
<?php

}
###### 创建管理员2 函数 ######
function step_admin2($on)
{
	global $exBlogVer;
	global $lang, $langpublic;

    $on
    ? $result = $lang[53]
    : $result = $lang[54];
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="nowt">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66"> 
      <td bgcolor="#FFFFFF" class="content"><b> <?php echo $lang[55]; ?></b><br /> <br />
        <br />
        <br />
		<? echo $result; ?>
  <br />
		<div align="center">
          <input type="hidden" name="action" value="step_end">
          <input type="button" value="<?php echo $lang[16]; ?>" onClick="window.location='install.php?action=step_admin';" class="botton">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="<?php echo $lang[18]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php

}
###### 结束 函数 ######
function step_end()
{
	global $exBlogVer;
	global $exBlog;
	global $lang, $langpublic;

?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="nowt">STEP 12</td>
    <td width="8%" class="noww">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66">
      <td bgcolor="#FFFFFF" class="content"><b><?php echo $lang[56]; ?></b><br /> <br />
        <br />
        <br />
	<?php echo $lang[57]; ?>
		<br />
		<div align="center">
          <input type="hidden" name="action" value="step_del">
          <input type="submit" value="<?php echo $lang[58]; ?>" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php
}
###### 删除 install.php 函数 ######
function step_del()
{
	global $exBlogVer;
	global $exBlog;
	global $lang, $langpublic;
 
	@chmod("../admin",0775);
	@chmod("../admin/install.php",0777);
	if(@unlink("../admin/install.php"))
    {
    $lang_top = $lang[63];
    $lang_con = $lang[63];
    }else{
    $lang_top = $lang[59];
    $lang_con = $lang[60];
    }
?>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="border">
  <tr>
    <td width="7%" class="noww">STEP 1</td>
    <td width="7%" class="noww">STEP 2</td>
    <td width="7%" class="noww">STEP 3</td>
    <td width="7%" class="noww">STEP 4</td>
    <td width="8%" class="noww">STEP 5</td>
    <td width="8%" class="noww">STEP 6</td>
    <td width="8%" class="noww">STEP 7</td>
    <td width="8%" class="noww">STEP 8</td>
    <td width="8%" class="noww">STEP 9</td>
    <td width="8%" class="noww">STEP 10</td>
    <td width="8%" class="noww">STEP 11</td>
    <td width="8%" class="noww">STEP 12</td>
    <td width="8%" class="nowt">STEP 13</td>
  </tr>
</table>
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="border">
    <tr bgcolor="#99CC66">
      <td bgcolor="#FFFFFF" class="content"><b><?php echo $lang_top; ?></b><br /> <br />
        <br />
        <br />
 <font color="#FF0000"><?php echo $lang_con; ?></font>
		<br />
		<div align="center">
          <input type="hidden" name="action" value="step_del">
          <input type="button" value="<?php echo $lang[61]; ?>" onClick="window.location='login.php';" class="botton">
          <br />
          &nbsp; </div></td>
    </tr>
  </table>
<?php

}
#####################
######### search current Language #########
function searchCurLanguage($current)
{
	global $langURL;
	$tmp=$langURL;
	$tmp2=explode("/",$tmp['langURL']);

	if($current == $tmp2[1])
	{
		echo "selected";
	}
}

######### read Language dir ######
function readLanguage()
{
	if($handle=opendir("../language"))
	{
		while(false !== $file=readdir($handle))
		{
			if($file != "." && $file != "..")
			{
				$dir[]=$file;
			}
		}
	}
	closedir($handle);
	return $dir;
}

?>
