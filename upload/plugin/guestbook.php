<?php
/*==================================================================
*\    Plugin for exBlog (http://www.exBlog.net/)
*\------------------------------------------------------------------
*\    Copyright(C) 2003 - 2005 exSoft Team, All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: Guestbook 插件
*\================================================================*/

require("../include/config.inc.php");
require("../include/global-C.inc.php");

setGlobal();

//---- 语言包 ----//
include("../language/chinese_gb2312/public.php");

session_start();

if( empty($_GET['action']) && empty($_POST['action']) ){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_index();

}elseif($_GET['action'] == "add"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_add();

}elseif($_GET['action'] == "del"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_del();

}elseif($_GET['action'] == "reply"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_reply();

}elseif($_GET['action'] == "help"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_help();

}elseif($_POST['action'] == "admin_add"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_admin_add();

}elseif($_POST['action'] == "admin_del"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_admin_del();

}elseif($_POST['action'] == "admin_reply"){
    require("./guestbook/guestbook_config.php");
    if($gb[header]){ header("location: ".$gb[header]);exit(); }
    fun_gb_admin_reply();

}elseif($_GET['action'] == "admin_config"){
checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限
 if (@file_exists('./guestbook/guestbook_config.php')){
    require("./guestbook/guestbook_config.php");
 }
    fun_gb_admin_config();

}elseif($_POST['action'] == "admin_config_go"){
checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限
    fun_gb_admin_config_go();

}

# 显示列表页面 开始
function fun_gb_index()
{
global $gb;

 if (@file_exists($gb[dbtable])){
        if (!@is_writable($gb[dbtable])) {
              error("对不起！留言存放文件不可使用，请确认权限！");
        }
 } else {
	@fopen($gb[dbtable],"w");
 }

$show_admin=0;
if( !checkunregist() ){
	if( !fun_checkUserUid($_SESSION['exPass'], 0) ) $show_admin=1;
}

?>
<html>
<head>
<title><? echo $gb[title]; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="exBlog">
<link rel="stylesheet" href="./guestbook/images/fonts.css">
</head>
<body bgcolor="#FFFFFF" text="#000000">
<div align="center"><img src="./guestbook/images/banner.gif" width="468" height="60"></div>
<br>
<table cellspacing=1 cellpadding=3 width=70% bgcolor='#000000' class='fonts' align='center'>
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="22"> 
      <div align="center"><a href="./guestbook.php?action=add"><img src="./guestbook/images/add.gif" width="16" height="16" border="0" alt="添加留言"> 
        我要留言</a> | <a href="./guestbook.php?action=help"><img src="./guestbook/images/help.gif" width="17" height="17" border="0" alt="使用帮助"> 
        使用帮助</a> | <a href="mailto:<? echo $email; ?>"><img src="./guestbook/images/email.gif" width="15" height="15" border="0" alt="给我写信"> 
        联系版主</a> | <a href="<? echo $gb[website]; ?>"><img src="./guestbook/images/homepage.gif" width="16" height="16" border="0" alt="返回主页"> 
        返回主页</a> </div>
    </td>
  </tr>
</table>
<br>
<?

$msg=array_reverse(file($gb[dbtable]));
$rows=count($msg);
$total=ceil($rows/$gb[pagesize]);	//计算总页面数

if($gb[pagesize]*$total<$rows){		//如果每页显示数*总页数小于留言总条数则
$total++;				//总页面数加1
}
$page=$_GET['page'];
if($_POST['pagepage2']){		//如果用户提交了页面数则
$page=$_POST['pagepage2']-1;		//$page 当前页面
if($_POST['pagepage2']>$total){		//如果用户提交的页面数大于总页面数则
$page=$total-1;
}
if($_POST['pagepage2']<1){		//如果用户提交的页面数小于1则
$page=0;				//当前页面为0
}
}
$total2=$total-1;
$page2=$page+1;
$pp=$page*$gb[pagesize];		//计算开始条数
$pp2=$pp+$gb[pagesize];			//计算结尾条数
$nextpage=$page+1;			//下一个页面
$prevpage=$page-1;			//上一个页面
?>
<?

for($i=$pp;$i<$pp2;$i++){
if($i<$rows){
$tmp=explode("||",$msg[$i]);
$tmp[8]=epost($tmp[8]);
?>
<table cellspacing="1" cellpadding="3" width="70%" bgcolor="#000000" class="fonts" align="center" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
  <tr>
    <td align="center" bgcolor="#99CC66" class="mycss" height="11" width="21%"> 
      <div align="left"><font color="#000000">第</font><font color="#990000">
	  <? echo $tmp[0]; ?> 
        <font color="#000000">条留言</font></font></div>
    </td>
    <td align="center" bgcolor="#99CC66" class="mycss" height="11"> 
      <div align="left"> 发表时间<font color="#000000">:[</font><font color="#990000"> 
        <? echo $tmp[7]; ?>
        <font color="#000000">] <img src="./guestbook/images/new.gif" width="11" height="10"></font></font></div>
    </td>
  </tr>
  <tr>
    <td align="center" bgcolor="#FFFFFF" class="mycss" width="21%" height="151" valign="top">
      <div align="center"><font color="#000000">Name:</font><b><font color="#990000"><? echo $tmp[1]; ?>
        </font></b> <br>
        <br>
        <? if($tmp[3]==boy){?>
        <img src="./guestbook/images/boy.gif" width="70" height="90"> <br>
        <? }else{ ?>
        <img src="./guestbook/images/girl-1.gif" width="70" height="90"> <br>
        <? } ?>
        <br>
        <a href="mailto:<? echo $tmp[2] ?>"><img src="./guestbook/images/email.gif" width="15" height="15" border="0"> 
        邮件</a><br>
        <a href="<? echo $tmp[5]; ?>" target="_blank"><img src="./guestbook/images/homepage.gif" width="16" height="16" border="0"> 
        主页</a><br>
        <a href="http://search.tencent.com/cgi-bin/friend/user_show_info?ln=<? echo $tmp[4]; ?>" target="_blank"><img src="./guestbook/images/oicq.gif" width="16" height="16" border="0"> 
        OICQ</a></div>
    </td>
    <td align="center" bgcolor="#FFFFFF" class="mycss" height="151" valign="top"> 
      <div align="left"><font color="#FF0000">Guest Message:<br>
        </font><br>
        <? echo $tmp[8];
?> <br>
        <br><? if(isset($tmp[9])){

echo "<hr size='1' color='#000000'>";

}
?>
		</div><? for($j=9;$j<count($tmp);$j++){ ?>

	  <? if(isset($tmp[$j])){ ?>

      <div align='left'><font color="#333399">版主回复: [<? echo $j-8; ?>] </font><br>
       <? echo $tmp[$j]; ?> <br><br>
 <? }} ?>
      </div>
    </td>
  </tr>

  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="2" width="21%"> 
      <div align="left"></div>
    </td>
    <td align="center" class="mycss" height="2"> 
      <div align="left"> <img src="./guestbook/images/ip.gif" width="16" height="15"> 
        <font color="#000000">IP:</font><font color="#990000"> <? echo $tmp[6]; ?></font>
<?php
if($show_admin==1){
?>
        <font color="#000000">|</font> <a href="./guestbook.php?action=reply&id=<? echo $tmp[0]; ?>"><img src="./guestbook/images/reply.gif" width="16" height="16" border="0"> 
        回复</a> | <a href="./guestbook.php?action=del&id=<? echo $tmp[0]; ?>"><img src="./guestbook/images/del.gif" width="16" height="16" border="0"> 
        删除</a> |
<?php
}
?>
      </div></td>
  </tr>
</table>
<br>
<? }} ?>

<table cellspacing='1' cellpadding='3' width='70%' bgcolor='#000000' class='fonts' align='center'>
<form action="<? echo $_SERVER[PHP_SELF]; ?>" method="post" name="pageform">
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="22"> 
        <div align="center">目前共有<font color='red'><b> 
          <? echo "$rows"; ?>
          </b></font>条留言&nbsp; 
          <?if ($page != 0){ ?>
          <a href="./guestbook.php?page=<?echo $prevpage?>"><img alt="上一页" border="0" src="./guestbook/images/prevpage.gif" width="45" height="19"></a> 
          <?}?>
          <? if ($page != $total2) {?>
          <a href="./guestbook.php?page=<?echo $nextpage?>"><img alt="下一页" border="0" src="./guestbook/images/nextpage.gif" width="45" height="19"></a> 
          <?} ?>
          &nbsp;第<b><font face="Tahoma"> 
          <?echo "<font color=red>$page2</font>/$total"; ?>
          </font></b>&nbsp; 第 
          <input type="text" name="pagepage2" size="2" class="input">页 
<input type="submit" value="跳转" class="botton"></div>
      </td>
  </tr>
</form>
</table>
<center><? echo ubb($gb['copyright']); //请不要修改以下内容 ?><br />Powered by <a href="http://www.exBlog.net">exSoft</a></center>
</body>
</html>

<?php
}
# 显示列表页面 结束

# 显示发言页面 开始
function fun_gb_add()
{
?>
<html>
<head>
<title><? echo $gb[title]; ?></title>
</head>
<link rel="stylesheet" href="./guestbook/images/fonts.css">

<body bgcolor="#FFFFFF" text="#000000">
<form action="<? echo $_SERVER[PHP_SELF]; ?>" method="post" name="vbform" onSubmit="return validate(this)">
<script language="JavaScript">
function smilie(thesmilie) {
document.vbform.message.value += thesmilie+" ";
document.vbform.message.focus();
}
</script>
  <table cellspacing=1 cellpadding=3 width=55% bgcolor="#000000" class="fonts" align="center">
    <tr bgcolor="#99CC66"> 
      <td align="center" class="mycss" height="11"> 
        <div align="center"><font color="#000000">::::Your Message::::</font></div>
      </td>
    </tr>
    <tr> 
      <td align="center" bgcolor="#FFFFFF" class="mycss"> 
        <div align="left"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr> 
              <td width="19%">昵称:</td>
              <td width="81%"> 
                <input type="TEXT" name="name" value="<?php echo $_SESSION['exPass']; ?>" size="25" maxlength="30" class="input">
                <font color="#FF0000">*</font> </td>
            </tr>
            <tr> 
              <td width="19%" height="14">Email:&nbsp;&nbsp;&nbsp; </td>
              <td width="81%" height="14"> 
                <input type="TEXT" name="email" value="<?php echo $_SESSION['exUseremail']; ?>" size="25" maxlength="30" class="input">
              </td>
            </tr>
            <tr> 
              <td width="19%">姓别:</td>
              <td width="81%"> 
                <input type="radio" name="sex" value="boy" class="input" checked>
                男 
                <input type="radio" name="sex" value="girl" class="input">
                女 </td>
            </tr>
            <tr> 
              <td width="19%">OICQ:</td>
              <td width="81%"> 
                <input type="TEXT"  name="oicq" size="25" maxlength="10" class="input">
              </td>
            </tr>
            <tr> 
              <td width="19%">个人主页:</td>
              <td width="81%"> 
                <input type="TEXT"  name="homepage" size="25" maxlength="30" class="input" value="http://">
              </td>
            </tr>
            <tr> 
              <td width="19%" valign="top">表情:</td>
              <td width="81%"> 
                <table cellpadding="3" cellspacing="1" border="0" bgcolor="990000" class="smilieTable" width="228">
                  <tr align='center' bgcolor="#FFFFFF"> 
                    <td colspan="5"> 
                      <table cellpadding="3" cellspacing="1" border="0" class="smilieTable" align="center" width="100%">
                        <tr align='center'> 
                          <td><a href="javascript:smilie(':biggrin:')"><img src="./guestbook/images/post/biggrin.gif" alt="大笑" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':blink:')"><img src="./guestbook/images/post/blink.gif" width="19" height="19" alt="流口水" border="0"></a></td>
                          <td><a href="javascript:smilie(':cool:')"><img src="./guestbook/images/post/cool.gif" alt="Cool" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':dry:')"><img src="./guestbook/images/post/dry.gif" alt="狂笑" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':tongue:')"><img src="./guestbook/images/post/tongue.gif" width="20" height="20" border="0" alt="添舌头"></a></td>
                          <td><a href="javascript:smilie(':wub:')"><img src="./guestbook/images/post/wub.gif" width="19" height="19" border="0" alt="脸红"></a></td>
                        </tr>
                        <tr align='center'> 
                          <td><a href="javascript:smilie(':mad:')"><img src="./guestbook/images/post/mad.gif" alt="气愤" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':ohmy:')"><img src="./guestbook/images/post/ohmy.gif" alt="啊" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':rolleyes:')"><img src="./guestbook/images/post/rolleyes.gif" alt="微笑" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':sad:')"><img src="./guestbook/images/post/sad.gif" alt="没办法" border="0" width="20" height="20"></a></td>
                          <td><a href="javascript:smilie(':wacko:')"><img src="./guestbook/images/post/wacko.gif" width="20" height="20" alt="想问题" border="0"></a></td>
                          <td><a href="javascript:smilie(':wink:')"><img src="./guestbook/images/post/wink.gif" width="20" height="20" alt="眨眼" border="0"></a></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr> 
              <td width="19%" valign="top">
                <p>Message: </p>
                <p>Html: <font color="#FF0000">off</font><br>
                  <a href="./guestbook.php?action=help" target="_blank">UBB</a> : <font color="#FF0000">on</font></p>
                </td>
              <td width="81%" valign="middle"> 
                <textarea name="message" rows="7" cols="50" wrap="virtual" tabindex="2" class="input"></textarea>
                <font color="#FF0000">*</font> </td>
            </tr>
			<tr> 
              <td colspan="2"><br>
                <div align="center">
                  <input type="hidden" name="action" value="admin_add">
                  <input type="submit" value="提交" class="botton">
                  <input type="reset" value="重填" class="botton" name="reset">
                <input type="button" value="返回上一页" onClick="javascript:history.back(-1);" class='botton'>
				</div>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
    <tr bgcolor="#99CC66"> 
      <td align="center" class="mycss" height="2">
        <div align="left"><font color="#000000">Notice:带</font><font color="#990000"> 
          <font color="#FF0000">*</font> <font color="#000000">号的必须填写</font></font></div>
      </td>
    </tr>
  </table>
  </form>
<center><? echo ubb($gb['copyright']); //请不要修改以下内容 ?></center>
</body>
</html>

<?php
}
# 显示发言页面 结束

# 显示删除页面 开始
function fun_gb_del()
{
?>

<link rel="stylesheet" href="./guestbook/images/fonts.css">
<br><br><br><br><br>
<form action="<? echo $_SERVER[PHP_SELF]; ?>" method="post">
<table cellspacing=1 cellpadding=3 width=50% bgcolor="#000000" class="fonts" align="center" name="passwd">
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="11"> 
      <div align="center"><font color="#990000"><b><> 删除留言 <></b></font></div>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td align="center" class="mycss"> 
<div align="left">
          <br>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
if( !checkunregist() && !fun_checkUserUid($_SESSION['exPass'], 0) ) {
?>
            <tr> 
              <td width="20%">Admin:</td>
              <td width="80%"> 
                <?php echo $_SESSION['exPass']; ?> 是否确认删除这一条留言？
              </td>
            </tr>
<?php
}else{
?>
            <tr> 
              <td width="18%">User:</td>
              <td width="82%"> 
                <input type="text"  name="user" maxlength="30" class="input"> 请输入管理员用户名&密码
              </td>
            </tr>
            <tr> 
              <td width="18%">Password:</td>
              <td width="82%"> 
                <input type="password"  name="passwd" maxlength="30" class="input">
              </td>
            </tr>
<?php
}
?>
          </table>
          <div align="center"> <br>
		  <input type="hidden" name=id value=<? echo $_GET[id]; ?>>
                  <input type="hidden" name="action" value="admin_del">
            <input type="submit" value="删除" class="botton" >
			<input type="button" value="返回上一页" onClick="javascript:history.back(-1);" class='botton'>
            <br>
          </div>
        </div>
       </td>
  </tr>
</table>
</form>

<?php
}
# 显示删除页面 结束

# 显示回复页面 开始
function fun_gb_reply()
{
?>

<link rel="stylesheet" href="./guestbook/images/fonts.css">
<br><br><br><br><br>
<form action="<? echo $_SERVER[PHP_SELF]; ?>" method="post">
<table cellspacing=1 cellpadding=3 width=50% bgcolor="#000000" class="fonts" align="center" name="passwd">
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="11"> 
      <div align="center"><font color="#990000"><b><> 回复留言 <></b></font></div>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td align="center" class="mycss"> 
<div align="left">
          <br>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
if( !checkunregist() && !fun_checkUserUid($_SESSION['exPass'], 0) ) {
?>
            <tr> 
              <td width="25%">Admin:</td>
              <td width="75%"> 
                <?php echo $_SESSION['exPass']; ?> 已经登陆！
              </td>
            </tr>
<?php
}else{
?>
            <tr> 
              <td width="25%">User:</td>
              <td width="75%"> 
                <input type="text"  name="user" maxlength="30" class="input"> 请输入管理员用户名&密码
              </td>
            </tr>
            <tr> 
              <td width="25%">Password:</td>
              <td width="75%"> 
                <input type="password"  name="passwd" maxlength="30" class="input">
              </td>
            </tr>
<?php
}
?>
			<tr> 
              <td width="25%">回复内容:</td>
              <td width="75%"> 
                <textarea name="repmsg" rows="8" cols="47" class="input"></textarea>
              </td>
            </tr>
          </table>
          <div align="center"> <br>
		  <input type="hidden" name=id value=<? echo $_GET[id]; ?>>
                  <input type="hidden" name="action" value="admin_reply">
            <input type="submit" value="回复" class="botton" >
			<input type="button" value="返回上一页" onClick="javascript:history.back(-1);" class='botton'>
            <br>
          </div>
        </div>
       </td>
  </tr>
</table>
</form>

<?php
}
# 显示回复页面 结束

# 显示帮助页面 开始
function fun_gb_help()
{
global $gb;
?>
<html>
<head>
<title><? echo $gb[title]; ?></title>
</head>
<link rel="stylesheet" href="./guestbook/images/fonts.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000">
<table cellspacing=1 cellpadding=3 width=55% bgcolor="#000000" class="fonts" align="center">
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="11"> 
      <div align="center"><font color="#000000">:::: 使用帮助 ::::</font></div>
    </td>
  </tr>
  <tr> 
    <td align="center" bgcolor="#FFFFFF" class="mycss"> 
      <div align="left"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr> 
            <td colspan="2"> 
              <p>1.本留言本[exBook]是 exBlog 的插件版本,欢迎使用!<br>
                <br>2.添加留言时支持一部分表情功能 [点击即可]<br>
                <br>
                3.支持一部分UBB代码 如:<br>
                .....[url]http://www.exblog.net[/url]<br>
                .....即显示 <a href="http://www.exblog.net" target="_blank">http://www.exblog.net</a><br>
                .....[email]exblog@fengling.net[/email]<br>
                .....即显示 <a href="mailto:exblog@fengling.net">exblog@fengling.net</a><br>
                .....[color=red]test color[/color]<br>
                .....即显示 <font color="#FF0000">test color </font>{ red 可为颜色代码,如:#000000,#FF0000 
                等... }<br>
                .....更多: [i][/i],[b][/b],[quote][/quote],[code][/code],[pre][/pre]<br>
                <br>
                4.没什么好说的了 -______-!!</p>
              </td>
          </tr>
          <tr> 
            <td colspan="2"><br>
              <div align="center"> 
                <input type="button" value="返回上一页" onClick="javascript:history.back(-1);" class='botton' name="button">
              </div>
            </td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
<?php
}
# 显示帮助页面 结束

# 处理发言信息 开始
function fun_gb_admin_add()
{
global $gb;

$result.=gb_checkuser($_POST['name']);
$result.=gb_checkemail($_POST['email']);
$result.=gb_checkqq($_POST['oicq']);
$result.=gb_checkcontent($_POST['message']);
if($result){
error($result);
}
if(file_exists($gb[dbtable])){
$fp=file($gb[dbtable]);
$num=count($fp)+1;
}else{
$num=1;
}
$ip=getenv("REMOTE_ADDR");
$addtime=date("Y-m-d H:i:s");
$fp=fopen("$gb[dbtable]","a+");
$message=$_POST['message'];
$message=htmlspecialchars($message);
$message=ubb($message);

$all="$num||$_POST[name]||$_POST[email]||$_POST[sex]||$_POST[oicq]||";
$all.="$_POST[homepage]||$ip||$addtime||$message\n";
@fputs($fp,$all) or die(error('写入信息时出错!'));
isok("添加留言成功!请稍等...");
}
# 处理发言信息 结束

# 处理删除信息 开始
function fun_gb_admin_del()
{
global $gb;
$go=0;
if( !checkunregist() && !fun_checkUserUid($_SESSION['exPass'], 0) ) {
$go=1;
}elseif( checkuserexist($_POST[user], $_POST[passwd], "NULL") ){
$go=1;
}else{
error('用户名或密码错误!');
}

if($go){
$msg="";
$tmp=file($gb[dbtable]);
$len=count($tmp);
for($i=0;$i<$len;$i++){
$info=explode("||",$tmp[$i]);
if($info[0]==$_POST[id]){
continue;
}
$msg.=$tmp[$i];
}
$fp=fopen("$gb[dbtable]","w");
@fputs($fp,$msg);
isok('删除留言成功!');
fclose($fp);
}else{
error("用户名或密码不正确!");
}
}
# 处理删除信息 结束

# 处理回复信息 开始
function fun_gb_admin_reply()
{
global $gb;
$go=0;
if( !checkunregist() && !fun_checkUserUid($_SESSION['exPass'], 0) ) {
$go=1;
}elseif( checkuserexist($_POST[user], $_POST[passwd], "NULL") ){
$go=1;
}else{
error('用户名或密码错误!');
}

if($go){
if(!trim($_POST[repmsg])){
error("请填写回复内容!");
}
$repmsg = str_replace("\n","",htmlspecialchars($_POST['repmsg']));
//其它的一些处理略
$msg="";   
$tmp=file($gb[dbtable]);  //把所有留言信息放到$tmp数组中
$len=count($tmp);     //取得列数
for($i=0;$i<$len;$i++){
$tmp[$i] = trim($tmp[$i]);
if($tmp[$i] == "") continue;
$info=explode("||",$tmp[$i]);  
if($info[0]==$_POST[id]){  
$repmsg=ubb($repmsg);
$msg.=$tmp[$i]."||".$repmsg."\n";
continue;
}
$rows=str_replace("\n","<br>",$tmp[$i]);
$msg.=$rows."\n";
}
$fp=fopen("$gb[dbtable]","w");
fputs($fp,$msg);
isok('回复留言成功!');
fclose($fp);
}
}
# 处理回复信息 结束

# 后台管理信息 开始
function fun_gb_admin_config()
{
global $gb;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL,TrackBack">
<meta name="description" content="exBlog - Powered by exSoft">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body class="main" dir="ltr">

<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr> 
    <td><table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
          <tr> 
            <td colspan="2" class="menu"><div align="center">留言本配置</div></td>
          </tr>
          <tr>
            <td>使用转向:</td>
            <td><input name="header" type="text" class="input" value="<?php echo $gb[header]; ?>" size="60"><br />如果不想使用本留言本,请输入转向留言本地址,否则请填 0 </td>
          </tr>
          <tr> 
            <td width="25%">文件地址:</td>
            <td width="75%"> <input name="dbtable" type="text" value="<?php echo $gb[dbtable]; ?>" class="input" size="60"><br />存放留言路径及文件名 越奇怪越好！
            </td>
          </tr>
          <tr> 
            <td>留言薄标题名称:</td>
            <td> <input name="title" type="text" value="<?php echo $gb[title]; ?>" class="input" size="40"> 
            </td>
          </tr>
          <tr>
            <td>主页地址:</td>
            <td> <input name="website" type="text" value="<?php echo $gb[website] ?>" class="input" size="60"> 
            </td>
          </tr>
          <tr> 
            <td>每页显示的留言条数:</td>
            <td><input name="pagesize" type="text" class="input" value="<?php echo $gb[pagesize]; ?>" size="2"></td>
          </tr>
          <tr> 
            <td valign="top">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td valign="top">版权信息:<br><br>Html: <font color="#FF0000">off</font><br><a href="./guestbook.php?action=help" target="_blank">UBB</a> : <font color="#FF0000">on</font></td>
            <td><textarea name="copyright" cols="60" rows="8" wrap="VIRTUAL" class="input"><?php echo $gb[copyright]; ?></textarea>
            </td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center"> 
<input type="hidden" name="action" value="admin_config_go">
                <input type="submit" value="完成" class="botton">
                &nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="reset" value="重置" class="botton">
              </div></td>
          </tr>
        </table></td>
  </tr>
</table>

</form>

</body></html>
<?php
}
# 后台管理信息 结束

# 处理后台管理信息 开始
function fun_gb_admin_config_go()
{
global $gb;

 if (@file_exists('./guestbook/guestbook_config.php')){
       is_writable('./guestbook/guestbook_config.php')
       ? $e=1
       : error("文件权限不符，不可修改！<br>请确保 plugin/guestbook/guestbook_config.php 可写！");
 }

	if ($fp = @fopen('./guestbook/guestbook_config.php', 'w')) {
		$config_data =
"<" . "?php
//是否转向 0 表示否
\$gb[header]=\"" . $_POST['header'] . "\";
//存放留言路径及文件名 越奇怪越好！
\$gb[dbtable]=\"" . $_POST['dbtable'] . "\";
//留言薄标题名称
\$gb[title]=\"" . $_POST['title'] . "\";
//主页地址
\$gb[website]=\"" . $_POST['website'] . "\";
//每页显示的留言条数
\$gb[pagesize]=\"" . $_POST['pagesize'] . "\";
//版权信息
\$gb[copyright]=\"" . $_POST['copyright'] . "\";
?" . ">";

		$result = @fputs($fp, $config_data, strlen($config_data));
		fclose($fp);

			if ($result) display("修改成功！","./guestbook.php?action=admin_config");
  }
}
# 处理后台管理信息 结束



//----------------------//
//   检查用户输入数据   //
//----------------------//
function gb_checkuser($user){
if(strlen($user)>15){
$result.="用户名不能超过15个字符!<br>";
return $result;
}
if(trim($user)==""){
$result.="用户名不能为空!<br>";
return $result;
}
if(eregi("[<>{}(),%#|^&!`$]",$user)){
$result.="用户名只能用a-z,0-9和'_'线组成!<br>";
return $result;
}
}
function gb_checkpasswd($passwd){
if(trim($passwd)==""){
$result.="密码必须填写!<br>";
return $result;
}
if(!eregi("^[a-zA-Z0-9]+$",$passwd)){
$result.="密码只能用a-z,0-9和'_'线组成!<br>";
return $result;
}
}
function gb_checkemail($email){
/*if(trim($email)==""){
$result.="E-mail不能为空!<br>";
return $result;
}*/
if(!trim($email)==""){
if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email)){ 
$result.="Email格式不正确!<br>";
return $result;
}
}
}
function gb_checkqq($qq){
if(!trim($qq)==""){
if(!eregi("^[0-9]+$",$qq)){
$result.="TencentQQ格式不正确!<br>";
return $result;
}
}
}
function gb_checkattack($content){
if(eregi("[\[|\]|\{|\}|\\|\/]",$content)){
$result.="特长中不能包含'<>{}[]/\'等特殊字符!<br>";
return $result;
}
}
function gb_checkcontent($content){
if(trim($content)==""){
$result.="留言不能为空!<br>";
return $result;
}
if(strlen($content)<8){
$result.="-____-!! 你真坏,就跟我说这么一点,起码也要8个字符!<br>";
return $result;
}
/*if(eregi("[]",$content)){
$result.="留言中不能包含'<>{}[]/\'等特殊字符!<br>";
return $result;
}*/
}

//----------------------//
//        UBB代码       //
//----------------------//
function ubb($Text) {
  $Text=ereg_replace("\r\n","<br>",$Text);
  $Text=ereg_replace("\r","<br>",$Text);
  $Text=preg_replace("/\\t/is","  ",$Text);
  $Text=preg_replace("/\[color=(.+?)\](.*)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
  $Text=preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","<a href=\\1 target=_blank>\\1</a>",$Text);
  $Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>http://\\1</a>",$Text);
  $Text=preg_replace("/\[url=(http:\/\/.+?)\](.*)\[\/url\]/is","<a href=\\1 target=_blank>\\2</a>",$Text);
  $Text=preg_replace("/\[url=(.+?)\](.*)\[\/url\]/is","<a href=http://\\1 target=_blank>\\2</a>",$Text);
  $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
  $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href=mailto:\\1>\\1</a>",$Text);
  $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
  $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
  $Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is","<blockquote><b>引用:</b><hr>\\1<hr></blockquote>", $Text);
   $Text=preg_replace("/\[code\](.+?)\[\/code\]/is","<blockquote><font size='1' face='Times New Roman'>code:</font><hr color='lightblue'><i>\\1</i><hr color='lightblue'></blockquote>", $Text);
return $Text;
}

//----------------------//
//       表情替换       //
//----------------------//
function epost($message){
$message=str_replace(":biggrin:","<img src=./guestbook/images/post/biggrin.gif>",$message);
$message=str_replace(":blink:","<img src=./guestbook/images/post/blink.gif>",$message);
$message=str_replace(":cool:","<img src=./guestbook/images/post/cool.gif>",$message);
$message=str_replace(":dry:","<img src=./guestbook/images/post/dry.gif>",$message);
$message=str_replace(":tongue:","<img src=./guestbook/images/post/tongue.gif>",$message);
$message=str_replace(":wub:","<img src=./guestbook/images/post/wub.gif>",$message);
$message=str_replace(":mad:","<img src=./guestbook/images/post/mad.gif>",$message);
$message=str_replace(":ohmy:","<img src=./guestbook/images/post/ohmy.gif>",$message);
$message=str_replace(":rolleyes:","<img src=./guestbook/images/post/rolleyes.gif>",$message);
$message=str_replace(":sad:","<img src=./guestbook/images/post/sad.gif>",$message);
$message=str_replace(":wacko:","<img src=./guestbook/images/post/wacko.gif>",$message);
$message=str_replace(":wink:","<img src=./guestbook/images/post/wink.gif>",$message);
return $message;
}

//----------------------//
//       出错页面       //
//----------------------//
function error($error){
?>
<link rel="stylesheet" href="./guestbook/images/fonts.css">
<table cellspacing=1 cellpadding=3 width=55% bgcolor="#000000" class="fonts" align="center">
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="11"> 
      <div align="left"> Error 错误原因可能是:</div>
    </td>
  </tr>
  <tr> 
    <td align="center" bgcolor="#FFFFFF" class="mycss"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr> 
            <br>
          <td colspan="2"><font color="red"><? echo $error; ?></font>
            <p><a href="javascript:history.go(-1);">&lt;&gt; 点击这里返回上一页 &lt;&gt; 
              </a></p>
          </td>
          </tr></table>
</table>
<?
exit;
} 

//----------------------//
//     成功跳转页面     //
//----------------------//
function isok($isok){
?>
<html>
<head>
<meta HTTP-EQUIV="REFRESH" CONTENT="2;URL=./guestbook.php">
<head>
<link rel="stylesheet" href="./guestbook/images/fonts.css">
<table cellspacing=1 cellpadding=3 width=55% bgcolor="#000000" class="fonts" align="center">
  <tr bgcolor="#99CC66"> 
    <td align="center" class="mycss" height="11"> 
      <div align="left"> 系统消息:POST成功!</div>
    </td>
  </tr>
  <tr> 
    <td align="center" bgcolor="#FFFFFF" class="mycss"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr> 
            <br>
          <td colspan="2"><font color="red"><? echo $isok; ?></font>
<p><a href="./guestbook.php">两秒钟后将自动返回...如果你不想等待请点击这里返回!
              </a></p>
          </td>
          </tr></table>
</table>
<? 
exit;
}
?>
