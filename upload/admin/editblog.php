<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L]  exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exblog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 添加&编辑&删除blog页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
require("../include/CheckData.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

checkLogin();       ### 检查用户是否非法登录
$aboutAuthor=splitInfo();
/* 查询分类列表 */
selectAllSort();
selectAllWeather();
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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>

<style type="text/css">
<!--
.style7 {font-size: 8pt}
-->
</style>

<script language="JavaScript" type="text/javascript" src="../include/ex2.js"></script>

</head>

<!-- auto copy to clipboard js by sheeryiro-->
<script language="JavaScript">
    function copyC(){
	therange=document.exEdit.content.createTextRange();
	therange.execCommand("Copy");
	}

   function pasteC(){
	alert("本还原操作将还原最近一次日志发表失败内容！");
	document.exEdit.content.focus();
	document.exEdit.content.createTextRange().execCommand("Paste");
	}
</script>
<form method="post" action="<? echo $PHP_SELF; ?>" name="exEdit">
<script type="text/javascript" language="JavaScript">
<!--
    function exEditadd(iEdit) {
    AddText(iEdit);
    document.exEdit.content.focus();
    }
    function vertify()
	{
	if (document.exEdit.esort.value=="")
		{
		alert("您没有选择文章的种类！");
		return false;
		}
	else
		{
		return true;
		}
	}

    function check()
	{
	if (exEdit.title.value=="") {
		alert ('对不起，请填写必填项目：标题！');
		exEdit.title.focus();
		return false;
	}
	if (exEdit.author.value=="") {
		alert ('对不起，请填写必填项目：作者！');
		exEdit.author.focus();
		return false;
	}
	if (exEdit.content.value=="") {
		alert ('对不起，请填写必填项目：内容！');
		exEdit.content.focus();
		return false;
	}
	if (exEdit.content.value.length<6) {
		alert ('对不起，内容不可小于 6 个字符！');
		exEdit.content.focus();
		return false;
	}
copyC();
	}

defmode = "normalmode";		// default mode (normalmode, advmode, helpmode)

if (defmode == "advmode") {
        helpmode = false;
        normalmode = false;
        advmode = true;
} else if (defmode == "helpmode") {
        helpmode = true;
        normalmode = false;
        advmode = false;
} else {
        helpmode = false;
        normalmode = true;
        advmode = false;
}

var timerID = null
var timerRunning = false

function go_time(){
    if(timerRunning){
        clearTimeout(timerID)
    timerRunning = false
    document.exEdit.at_hour.value = o_hour
    document.exEdit.at_minute.value = o_minute
    document.exEdit.at_second.value = o_second
    document['gogotime'].src='../images/ex_code/time_stop.gif'
    document['gogotime'].alt='使用当前时间'
		}else{
    o_hour = document.exEdit.at_hour.value
    o_minute = document.exEdit.at_minute.value
    o_second = document.exEdit.at_second.value
    showtime()
    document['gogotime'].src='../images/ex_code/time_start.gif'
    document['gogotime'].alt='使用自定义时间'
		}
}

function showtime(){
    var now = new Date()
    var hours = now.getHours()
    var minutes = now.getMinutes()
    var seconds = now.getSeconds()
    hours  = ((hours < 10) ? "0" : "") + hours
    minutes  = ((minutes < 10) ? "0" : "") + minutes
    seconds  = ((seconds < 10) ? "0" : "") + seconds
    document.exEdit.at_hour.value = hours
    document.exEdit.at_minute.value = minutes
    document.exEdit.at_second.value = seconds 
    timerID = setTimeout("showtime()",1000)
    timerRunning = true
}

function  onlyNum()
{
if(!((event.keyCode>=48&&event.keyCode<=57)||(event.keyCode>=96&&event.keyCode<=105)||(event.keyCode==8)))
event.returnValue=false;
}


//-->
</script>

<?
    if($_POST['action'] == "添加文章")
	{
		if(empty($_POST[at_year])) $at_year=date(Y);
		if(empty($_POST[at_month])) $at_month=date(m);
		if(empty($_POST[at_day])) $at_day=date(d);
		if(empty($_POST[at_hour])) $at_hour=date(H);
		if(empty($_POST[at_minute])) $at_minute=date(i);
		if(empty($_POST[at_second])) $at_second=date(s);
		$addtime=$at_year."-".$at_month."-".$at_day." ".$at_hour.":".$at_minute.":".$at_second;
	    addBlog($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['esort'], $addtime, $_POST['weather'], $_POST['isTop'], $_POST[isHidden], $_POST[html]);
	}
	elseif($_POST['action'] == "编辑文章")
	{
		if(empty($_POST[at_year])) $at_year="0000";
		if(empty($_POST[at_month])) $at_month="00";
		if(empty($_POST[at_day])) $at_day="00";
		if(empty($_POST[at_hour])) $at_hour="00";
		if(empty($_POST[at_minute])) $at_minute="00";
		if(empty($_POST[at_second])) $at_second="00";
		$addtime=$at_year."-".$at_month."-".$at_day." ".$at_hour.":".$at_minute.":".$at_second;
		updateBlog($_POST[title], $_POST[content], $_POST[author], $_POST[email], $_POST[esort], $_POST[id], $addtime, $_POST['weather'], $_POST['isTop'], $_POST[isHidden], $_POST[html]);
	}
	elseif($_GET[action] == "delete" || $_POST[action] == "删除")
	{
		if($_POST[action] == "删除")
		{
			deleteBlog($_POST['searchID']);
		}
		else
		{
			deleteBlog($_GET['id']);
		}
	}
	if($_POST[action] == "批量删除")
	{
		delMore($_POST['article']);  //批量删除文章Encoded By Anthrax
	}

$modifygo=0;
if($_POST['searchID']!="")$modifygo=1;

	if($_GET['action'] == "modify" || $_POST['action'] == "编辑" || $modifygo==1)
	{
		checkUserUid($_SESSION[exPass]);          ### 检查用户权限
		//---- 检果是否通过search查找的 ----//
		if($_POST['action'] == "编辑" || $modifygo==1)
		{
			$BlogInfo=selectOneBlogInfo($_POST['searchID']); ### 查询当前待编辑的文章内容
			if($BlogInfo[title]=="" && $BlogInfo[content]=="")
			{
				showError("未找到该ID相关信息");
			}
		}
		else
		{
			$BlogInfo=selectOneBlogInfo($_GET['id']); ### 查询当前待编辑的文章内容
		}
?>
<body class="main">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>编辑文章</b></td>
    </tr>
    <tr>
      <td width="40" height="23" align="center">标题:</td>
      <td><input name="title" type="text" size="35" value="<? echo $BlogInfo[title]; ?>" class="input">
          <select name="isTop" id="isTop">
          <option value="<? echo $BlogInfo[top]; ?>" selected><? editBlogSelTop($BlogInfo[top])?></option>
          <option value="<? echo 1-$BlogInfo[top]; ?>"><? editBlogSelTop(1-$BlogInfo[top])?></option>
      </select>
          <input type="hidden" name="html" value="0">
      </td>
      <td align="center">天气:</td>
      <td><select name="weather" class="botton"> 
          <? 
	while($row=mysql_fetch_array($resultWeather))
	{ 
?>
          <option value="<? echo $row['enWeather']; ?>" <? editBlogSelWeather($row['enWeather'],$BlogInfo['weather']); ?>> <? echo $row['cnWeather']; ?></option>
          <? } ?>
      </select></td>
    </tr>
    <tr>
      <td height="23" align="center">作者:</td>
      <td><input name="author" type="text" value="<? echo $BlogInfo[author]; ?>" class="input">
      　是否隐藏:　<select name="isHidden" id="isHidden">
          <option value="<? echo $BlogInfo[hidden]; ?>" selected><? editBlogSelHidden($BlogInfo[hidden])?></option>
          <option value="<? echo 1-$BlogInfo[hidden]; ?>"><? editBlogSelHidden(1-$BlogInfo[hidden])?></option>
      </select>　
	</td>

      <td align="center">分类:</td>
      <td><select name="esort" class="botton">
          <? 
	while($row=mysql_fetch_array($resultSort))
	{ 
?>
          <option value="<? echo $row['cnName']; ?>" <? editBlogSelSort($row['cnName'],$BlogInfo['sort']); ?>> <? echo $row['cnName']; ?></option>
          <? } ?>
      </select>
</td>
    </tr>
    <tr> 
      <td align="center" valign="top">辅助:</td>
      <td colspan="3"><table border="0" cellpadding="1" cellspacing="1">
          <tr> 
                  <td><a href="javascript:exEditadd(':ex2_0:')"><img src="../images/ex_pose/ex2_0.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_1:')"><img src="../images/ex_pose/ex2_1.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_2:')"><img src="../images/ex_pose/ex2_2.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_3:')"><img src="../images/ex_pose/ex2_3.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_4:')"><img src="../images/ex_pose/ex2_4.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_5:')"><img src="../images/ex_pose/ex2_5.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_6:')"><img src="../images/ex_pose/ex2_6.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_7:')"><img src="../images/ex_pose/ex2_7.gif" width="16" height="16" border="0"></a></td>

                  <td><a href="javascript:exEditadd(':ex2_8:')"><img src="../images/ex_pose/ex2_8.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_9:')"><img src="../images/ex_pose/ex2_9.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_10:')"><img src="../images/ex_pose/ex2_10.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_11:')"><img src="../images/ex_pose/ex2_11.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_12:')"><img src="../images/ex_pose/ex2_12.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_13:')"><img src="../images/ex_pose/ex2_13.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_14:')"><img src="../images/ex_pose/ex2_14.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_15:')"><img src="../images/ex_pose/ex2_15.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_16:')"><img src="../images/ex_pose/ex2_16.gif" width="16" height="16" border="0"></a></td></tr><tr>
                  <td><a href="javascript:exEditadd(':ex2_17:')"><img src="../images/ex_pose/ex2_17.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_18:')"><img src="../images/ex_pose/ex2_18.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_19:')"><img src="../images/ex_pose/ex2_19.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_20:')"><img src="../images/ex_pose/ex2_20.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_21:')"><img src="../images/ex_pose/ex2_21.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_22:')"><img src="../images/ex_pose/ex2_22.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_23:')"><img src="../images/ex_pose/ex2_23.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_24:')"><img src="../images/ex_pose/ex2_24.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_25:')"><img src="../images/ex_pose/ex2_25.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_26:')"><img src="../images/ex_pose/ex2_26.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_27:')"><img src="../images/ex_pose/ex2_27.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_28:')"><img src="../images/ex_pose/ex2_28.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_29:')"><img src="../images/ex_pose/ex2_29.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_30:')"><img src="../images/ex_pose/ex2_30.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_31:')"><img src="../images/ex_pose/ex2_31.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_32:')"><img src="../images/ex_pose/ex2_32.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_33:')"><img src="../images/ex_pose/ex2_33.gif" width="16" height="16" border="0"></a></td></tr><tr>
                  <td><a href="javascript:exEditadd(':ex2_34:')"><img src="../images/ex_pose/ex2_34.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_35:')"><img src="../images/ex_pose/ex2_35.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_36:')"><img src="../images/ex_pose/ex2_36.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_37:')"><img src="../images/ex_pose/ex2_37.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_38:')"><img src="../images/ex_pose/ex2_38.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_39:')"><img src="../images/ex_pose/ex2_39.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_40:')"><img src="../images/ex_pose/ex2_40.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_41:')"><img src="../images/ex_pose/ex2_41.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_42:')"><img src="../images/ex_pose/ex2_42.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_43:')"><img src="../images/ex_pose/ex2_43.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_44:')"><img src="../images/ex_pose/ex2_44.gif" width="16" height="16" border="0"></a></td>
</tr><tr>

                  <td><a href="javascript:exEditadd(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" border="0"></a></td>

                  <td><a href="javascript:exEditadd(':ex_8:')"><img src="../images/ex_pose/ex_8.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_9:')"><img src="../images/ex_pose/ex_9.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_10:')"><img src="../images/ex_pose/ex_10.gif" border="0"></a></td>
</tr>
          <tr> 
            <td><img  onclick=code()  src="../images/ex_code/ex_code.gif" alt="插入代码" width="23" height="22" border="0"></td>
            <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="插入引用" width="23" height="22" border="0"></td>
            <td><img  onclick=bold()   src="../images/ex_code/ex_bold.gif" alt="插入粗体字" width="23" height="22" border="0"></td>
            <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="插入斜体字" width="23" height="22" border="0"></td>
            <td><img  onclick=strike()  src="../images/ex_code/ex_strike.gif" alt="插入删除字" width="23" height="22" border="0"></td>
            <td><img  onclick=sup()  src="../images/ex_code/ex_sup.gif" alt="插入上标字" width="23" height="22" border="0"></td>
            <td><img  onclick=exsub()  src="../images/ex_code/ex_sub.gif" alt="插入下标字" width="23" height="22" border="0"></td>
       		<td><img  onclick=list()  src="../images/ex_code/ex_list.gif" alt="插入列表" width="23" height="22" border="0"></td>
			<td><img  onclick=left()  src="../images/ex_code/ex_left.gif" alt="文字左对齐" width="23" height="22" border="0"></td>
			<td><img  onclick=center()  src="../images/ex_code/ex_center.gif" alt="文字居中" width="23" height="22" border="0"></td>
			<td><img  onclick=right()  src="../images/ex_code/ex_right.gif" alt="文字右对齐" width="23" height="22" border="0"></td>
			<td><img  onclick=justify()  src="../images/ex_code/ex_justify.gif" alt="文字左右自适应" width="23" height="22" border="0"></td>
			<td><img  onclick=underline()  src="../images/ex_code/ex_underline.gif" alt="插入下划线文字" width="23" height="22" border="0"></td>
            <td><img  onclick=move()  src="../images/ex_code/ex_move.gif" alt="移动文字" width="23" height="22" border="0"></td>
            <td><img  onclick=exblur()  src="../images/ex_code/ex_blur.gif" alt="模糊字" width="23" height="22" border="0"></td>
            <td><img  onclick=fliph()  src="../images/ex_code/ex_fliph.gif" alt="插入左右颠倒文字" width="23" height="22" border="0"></td>
            <td><img  onclick=flipv()  src="../images/ex_code/ex_flipv.gif" alt="插入上下颠倒文字" width="23" height="22" border="0"></td>
          </tr>
          <tr>
            <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="插入框架" width="23" height="22" border="0"></td>
            <td><img  onclick=htmlcode()  src="../images/ex_code/ex_htmlcode.gif" alt="插入HTML代码" width="23" height="22" border="0"></td>
            <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="插入email" width="23" height="22" border="0"></td>
            <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="插入超级链接" width="23" height="22" border="0"></td>
            <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="插入图像链接" width="23" height="22" border="0"></td>
			<td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="插入Flash文件" width="23" height="22" border="0"></td>
			<td><img  onclick=mp3()  src="../images/ex_code/ex_mp3.gif" alt="插入mp3音频" width="23" height="22" border="0"></td>
			<td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="插入rm视频" width="23" height="22" border="0"></td>
			<td><img  onclick=ra()  src="../images/ex_code/ex_ra.gif" alt="插入ra音频" width="23" height="22" border="0"></td>
			<td><img  onclick=wmv()  src="../images/ex_code/ex_wmv.gif" alt="插入wmv视频 支持格式 asf wmv avi" width="23" height="22" border="0"></td>
			<td><img  onclick=wma()  src="../images/ex_code/ex_wma.gif" alt="插入wma音频" width="23" height="22" border="0"></td>
			<td><img  onclick=mov()  src="../images/ex_code/ex_mov.gif" alt="插入mov 支持格式 mov pitc aiff" width="23" height="22" border="0"></td>
      <td colspan="5" align="right"><select name="mode" onChange="chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)">
<option value=2>UBB提示插入</option>
<option value=1>UBB帮助信息</option>
<option value=0>UBB直接插入</option>
</select></td>
	</tr>
</table>
     <table class="main">
     <tr>
           <td width="190">字体设置
<select onChange=chfont(this.options[this.selectedIndex].value) name=font>
<option value="宋体" selected>宋体</option>
<option value="楷体_GB2312">楷体</option>
<option value="新宋体">新宋体</option>
<option value="黑体">黑体</option>
<option value="隶书">隶书</option>
<option value="Andale Mono">Andale Mono</option>
<option value=Arial>Arial</option>
<option value="Arial Black">Arial Black</option>
<option value="Book Antiqua">Book Antiqua</option>
<option value="Century Gothic">Century Gothic</option>
<option value="Comic Sans MS">Comic Sans MS</option>
<option value="Courier New">Courier New</option>
<option value=Georgia>Georgia</option>
<option value=Impact>Impact</option>
<option value=Tahoma>Tahoma</option>
<option value="Times New Roman" >Times New Roman</option>
<option value="Trebuchet MS">Trebuchet MS</option>
<option value="Script MT Bold">Script MT Bold</option>
<option value=Stencil>Stencil</option>
<option value=Verdana>Verdana</option>
<option value="Lucida Console">Lucida Console</option>
</select>
</td>
         <td width="50">
           <select name="size" onFocus="this.selectedIndex=2" onChange="chsize(this.options[this.selectedIndex].value)" size="1">
<option value="-2">-2</option>
<option value="-1">-1</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3" selected>3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option></select>
         </td>
         <td width="85">
<SELECT onchange=chcolor(this.options[this.selectedIndex].value) name=color> 
<option style=background-color:#F0F8FF;color:#F0F8FF value=#F0F8FF>#F0F8FF</option>
<option style=background-color:#FAEBD7;color:#FAEBD7 value=#FAEBD7>#FAEBD7</option>
<option style=background-color:#00FFFF;color:#00FFFF value=#00FFFF>#00FFFF</option>
<option style=background-color:#7FFFD4;color:#7FFFD4 value=#7FFFD4>#7FFFD4</option>
<option style=background-color:#F0FFFF;color:#F0FFFF value=#F0FFFF>#F0FFFF</option>
<option style=background-color:#F5F5DC;color:#F5F5DC value=#F5F5DC>#F5F5DC</option>
<option style=background-color:#FFE4C4;color:#FFE4C4 value=#FFE4C4>#FFE4C4</option>
<option style=background-color:#000000;color:#000000 value=#000000>#000000</option>
<option style=background-color:#FFEBCD;color:#FFEBCD value=#FFEBCD>#FFEBCD</option>
<option style=background-color:#0000FF;color:#0000FF value=#0000FF>#0000FF</option>
<option style=background-color:#8A2BE2;color:#8A2BE2 value=#8A2BE2>#8A2BE2</option>
<option style=background-color:#A52A2A;color:#A52A2A value=#A52A2A>#A52A2A</option>
<option style=background-color:#DEB887;color:#DEB887 value=#DEB887>#DEB887</option>
<option style=background-color:#5F9EA0;color:#5F9EA0 value=#5F9EA0>#5F9EA0</option>
<option style=background-color:#7FFF00;color:#7FFF00 value=#7FFF00>#7FFF00</option>
<option style=background-color:#D2691E;color:#D2691E value=#D2691E>#D2691E</option>
<option style=background-color:#FF7F50;color:#FF7F50 value=#FF7F50>#FF7F50</option>
<option style=background-color:#6495ED;color:#6495ED value=#6495ED selected>#6495ED</option>
<option style=background-color:#FFF8DC;color:#FFF8DC value=#FFF8DC>#FFF8DC</option>
<option style=background-color:#DC143C;color:#DC143C value=#DC143C>#DC143C</option>
<option style=background-color:#00FFFF;color:#00FFFF value=#00FFFF>#00FFFF</option>
<option style=background-color:#00008B;color:#00008B value=#00008B>#00008B</option>
<option style=background-color:#008B8B;color:#008B8B value=#008B8B>#008B8B</option>
<option style=background-color:#B8860B;color:#B8860B value=#B8860B>#B8860B</option>
<option style=background-color:#A9A9A9;color:#A9A9A9 value=#A9A9A9>#A9A9A9</option>
<option style=background-color:#006400;color:#006400 value=#006400>#006400</option>
<option style=background-color:#BDB76B;color:#BDB76B value=#BDB76B>#BDB76B</option>
<option style=background-color:#8B008B;color:#8B008B value=#8B008B>#8B008B</option>
<option style=background-color:#556B2F;color:#556B2F value=#556B2F>#556B2F</option>
<option style=background-color:#FF8C00;color:#FF8C00 value=#FF8C00>#FF8C00</option>
<option style=background-color:#9932CC;color:#9932CC value=#9932CC>#9932CC</option>
<option style=background-color:#8B0000;color:#8B0000 value=#8B0000>#8B0000</option>
<option style=background-color:#E9967A;color:#E9967A value=#E9967A>#E9967A</option>
<option style=background-color:#8FBC8F;color:#8FBC8F value=#8FBC8F>#8FBC8F</option>
<option style=background-color:#483D8B;color:#483D8B value=#483D8B>#483D8B</option>
<option style=background-color:#2F4F4F;color:#2F4F4F value=#2F4F4F>#2F4F4F</option>
<option style=background-color:#00CED1;color:#00CED1 value=#00CED1>#00CED1</option>
<option style=background-color:#9400D3;color:#9400D3 value=#9400D3>#9400D3</option>
<option style=background-color:#FF1493;color:#FF1493 value=#FF1493>#FF1493</option>
<option style=background-color:#00BFFF;color:#00BFFF value=#00BFFF>#00BFFF</option>
<option style=background-color:#696969;color:#696969 value=#696969>#696969</option>
<option style=background-color:#1E90FF;color:#1E90FF value=#1E90FF>#1E90FF</option>
<option style=background-color:#B22222;color:#B22222 value=#B22222>#B22222</option>
<option style=background-color:#FFFAF0;color:#FFFAF0 value=#FFFAF0>#FFFAF0</option>
<option style=background-color:#228B22;color:#228B22 value=#228B22>#228B22</option>
<option style=background-color:#FF00FF;color:#FF00FF value=#FF00FF>#FF00FF</option>
<option style=background-color:#DCDCDC;color:#DCDCDC value=#DCDCDC>#DCDCDC</option>
<option style=background-color:#F8F8FF;color:#F8F8FF value=#F8F8FF>#F8F8FF</option>
<option style=background-color:#FFD700;color:#FFD700 value=#FFD700>#FFD700</option>
<option style=background-color:#DAA520;color:#DAA520 value=#DAA520>#DAA520</option>
<option style=background-color:#808080;color:#808080 value=#808080>#808080</option>
<option style=background-color:#008000;color:#008000 value=#008000>#008000</option>
<option style=background-color:#ADFF2F;color:#ADFF2F value=#ADFF2F>#ADFF2F</option>
<option style=background-color:#F0FFF0;color:#F0FFF0 value=#F0FFF0>#F0FFF0</option>
<option style=background-color:#FF69B4;color:#FF69B4 value=#FF69B4>#FF69B4</option>
<option style=background-color:#CD5C5C;color:#CD5C5C value=#CD5C5C>#CD5C5C</option>
<option style=background-color:#4B0082;color:#4B0082 value=#4B0082>#4B0082</option>
<option style=background-color:#FFFFF0;color:#FFFFF0 value=#FFFFF0>#FFFFF0</option>
<option style=background-color:#F0E68C;color:#F0E68C value=#F0E68C>#F0E68C</option>
<option style=background-color:#E6E6FA;color:#E6E6FA value=#E6E6FA>#E6E6FA</option>
<option style=background-color:#FFF0F5;color:#FFF0F5 value=#FFF0F5>#FFF0F5</option>
<option style=background-color:#7CFC00;color:#7CFC00 value=#7CFC00>#7CFC00</option>
<option style=background-color:#FFFACD;color:#FFFACD value=#FFFACD>#FFFACD</option>
<option style=background-color:#ADD8E6;color:#ADD8E6 value=#ADD8E6>#ADD8E6</option>
<option style=background-color:#F08080;color:#F08080 value=#F08080>#F08080</option>
<option style=background-color:#E0FFFF;color:#E0FFFF value=#E0FFFF>#E0FFFF</option>
<option style=background-color:#FAFAD2;color:#FAFAD2 value=#FAFAD2>#FAFAD2</option>
<option style=background-color:#90EE90;color:#90EE90 value=#90EE90>#90EE90</option>
<option style=background-color:#D3D3D3;color:#D3D3D3 value=#D3D3D3>#D3D3D3</option>
<option style=background-color:#FFB6C1;color:#FFB6C1 value=#FFB6C1>#FFB6C1</option>
<option style=background-color:#FFA07A;color:#FFA07A value=#FFA07A>#FFA07A</option>
<option style=background-color:#20B2AA;color:#20B2AA value=#20B2AA>#20B2AA</option>
<option style=background-color:#87CEFA;color:#87CEFA value=#87CEFA>#87CEFA</option>
<option style=background-color:#778899;color:#778899 value=#778899>#778899</option>
<option style=background-color:#B0C4DE;color:#B0C4DE value=#B0C4DE>#B0C4DE</option>
<option style=background-color:#FFFFE0;color:#FFFFE0 value=#FFFFE0>#FFFFE0</option>
<option style=background-color:#00FF00;color:#00FF00 value=#00FF00>#00FF00</option>
<option style=background-color:#32CD32;color:#32CD32 value=#32CD32>#32CD32</option>
<option style=background-color:#FAF0E6;color:#FAF0E6 value=#FAF0E6>#FAF0E6</option>
<option style=background-color:#FF00FF;color:#FF00FF value=#FF00FF>#FF00FF</option>
<option style=background-color:#800000;color:#800000 value=#800000>#800000</option>
<option style=background-color:#66CDAA;color:#66CDAA value=#66CDAA>#66CDAA</option>
<option style=background-color:#0000CD;color:#0000CD value=#0000CD>#0000CD</option>
<option style=background-color:#BA55D3;color:#BA55D3 value=#BA55D3>#BA55D3</option>
<option style=background-color:#9370DB;color:#9370DB value=#9370DB>#9370DB</option>
<option style=background-color:#3CB371;color:#3CB371 value=#3CB371>#3CB371</option>
<option style=background-color:#7B68EE;color:#7B68EE value=#7B68EE>#7B68EE</option>
<option style=background-color:#00FA9A;color:#00FA9A value=#00FA9A>#00FA9A</option>
<option style=background-color:#48D1CC;color:#48D1CC value=#48D1CC>#48D1CC</option>
<option style=background-color:#C71585;color:#C71585 value=#C71585>#C71585</option>
<option style=background-color:#191970;color:#191970 value=#191970>#191970</option>
<option style=background-color:#F5FFFA;color:#F5FFFA value=#F5FFFA>#F5FFFA</option>
<option style=background-color:#FFE4E1;color:#FFE4E1 value=#FFE4E1>#FFE4E1</option>
<option style=background-color:#FFE4B5;color:#FFE4B5 value=#FFE4B5>#FFE4B5</option>
<option style=background-color:#FFDEAD;color:#FFDEAD value=#FFDEAD>#FFDEAD</option>
<option style=background-color:#000080;color:#000080 value=#000080>#000080</option>
<option style=background-color:#FDF5E6;color:#FDF5E6 value=#FDF5E6>#FDF5E6</option>
<option style=background-color:#808000;color:#808000 value=#808000>#808000</option>
<option style=background-color:#6B8E23;color:#6B8E23 value=#6B8E23>#6B8E23</option>
<option style=background-color:#FFA500;color:#FFA500 value=#FFA500>#FFA500</option>
<option style=background-color:#FF4500;color:#FF4500 value=#FF4500>#FF4500</option>
<option style=background-color:#DA70D6;color:#DA70D6 value=#DA70D6>#DA70D6</option>
<option style=background-color:#EEE8AA;color:#EEE8AA value=#EEE8AA>#EEE8AA</option>
<option style=background-color:#98FB98;color:#98FB98 value=#98FB98>#98FB98</option>
<option style=background-color:#AFEEEE;color:#AFEEEE value=#AFEEEE>#AFEEEE</option>
<option style=background-color:#DB7093;color:#DB7093 value=#DB7093>#DB7093</option>
<option style=background-color:#FFEFD5;color:#FFEFD5 value=#FFEFD5>#FFEFD5</option>
<option style=background-color:#FFDAB9;color:#FFDAB9 value=#FFDAB9>#FFDAB9</option>
<option style=background-color:#CD853F;color:#CD853F value=#CD853F>#CD853F</option>
<option style=background-color:#FFC0CB;color:#FFC0CB value=#FFC0CB>#FFC0CB</option>
<option style=background-color:#DDA0DD;color:#DDA0DD value=#DDA0DD>#DDA0DD</option>
<option style=background-color:#B0E0E6;color:#B0E0E6 value=#B0E0E6>#B0E0E6</option>
<option style=background-color:#800080;color:#800080 value=#800080>#800080</option>
<option style=background-color:#FF0000;color:#FF0000 value=#FF0000>#FF0000</option>
<option style=background-color:#BC8F8F;color:#BC8F8F value=#BC8F8F>#BC8F8F</option>
<option style=background-color:#4169E1;color:#4169E1 value=#4169E1>#4169E1</option>
<option style=background-color:#8B4513;color:#8B4513 value=#8B4513>#8B4513</option>
<option style=background-color:#FA8072;color:#FA8072 value=#FA8072>#FA8072</option>
<option style=background-color:#F4A460;color:#F4A460 value=#F4A460>#F4A460</option>
<option style=background-color:#2E8B57;color:#2E8B57 value=#2E8B57>#2E8B57</option>
<option style=background-color:#FFF5EE;color:#FFF5EE value=#FFF5EE>#FFF5EE</option>
<option style=background-color:#A0522D;color:#A0522D value=#A0522D>#A0522D</option>
<option style=background-color:#C0C0C0;color:#C0C0C0 value=#C0C0C0>#C0C0C0</option>
<option style=background-color:#87CEEB;color:#87CEEB value=#87CEEB>#87CEEB</option>
<option style=background-color:#6A5ACD;color:#6A5ACD value=#6A5ACD>#6A5ACD</option>
<option style=background-color:#708090;color:#708090 value=#708090>#708090</option>
<option style=background-color:#FFFAFA;color:#FFFAFA value=#FFFAFA>#FFFAFA</option>
<option style=background-color:#00FF7F;color:#00FF7F value=#00FF7F>#00FF7F</option>
<option style=background-color:#4682B4;color:#4682B4 value=#4682B4>#4682B4</option>
<option style=background-color:#D2B48C;color:#D2B48C value=#D2B48C>#D2B48C</option>
<option style=background-color:#008080;color:#008080 value=#008080>#008080</option>
<option style=background-color:#D8BFD8;color:#D8BFD8 value=#D8BFD8>#D8BFD8</option>
<option style=background-color:#FF6347;color:#FF6347 value=#FF6347>#FF6347</option>
<option style=background-color:#40E0D0;color:#40E0D0 value=#40E0D0>#40E0D0</option>
<option style=background-color:#EE82EE;color:#EE82EE value=#EE82EE>#EE82EE</option>
<option style=background-color:#F5DEB3;color:#F5DEB3 value=#F5DEB3>#F5DEB3</option>
<option style=background-color:#FFFFFF;color:#FFFFFF value=#FFFFFF>#FFFFFF</option>
<option style=background-color:#F5F5F5;color:#F5F5F5 value=#F5F5F5>#F5F5F5</option>
<option style=background-color:#FFFF00;color:#FFFF00 value=#FFFF00>#FFFF00</option>
<option style=background-color:#9ACD32;color:#9ACD32 value=#9ACD32>#9ACD32</option>
</SELECT>
       </td>
<td>
                特殊字符：<select name="fontsign" onChange="exEditadd(this.options[this.selectedIndex].value)" >
                          <option value="哈哈！大家一起跟我喊：exBlog!">特</option>
                          <option value="■">■</option>
                          <option value="□">□</option>
                          <option value="▲">▲</option>
                          <option value="△">△</option>
                          <option value="▼">▼</option>
                          <option value="▽">▽</option>
                          <option value="◆">◆</option>
                          <option value="◇">◇</option>
                          <option value="○">○</option>
                          <option value="◎">◎</option>
                          <option value="●">●</option>
                          <option value="★">★</option>
                          <option value="◆">◆</option>
                          <option value="◇">◇</option>
                          <option value="○">○</option>
                          <option value="◎">◎</option>
                          <option value="●">●</option>
                          <option value="★">★</option>
                          <option value="☆">☆</option>
                          <option value="♀">♀</option>
                          <option value="♂">♂</option>
                          <option value="		">Tab</option>
                        </select>
</td>
</tr>
   </table>

      </td>
    </tr>
    <tr>
      <td rowspan="2" align="center" valign="top">内容:<br>
<br>
<br><a onclick="document.exEdit.content.rows+=20;" title="拉长文本框，方便书写和查看">↑↑<br><b>拉长</b><br>↓↓</a><br>
<br><a onclick="if(document.exEdit.content.rows>=20)document.exEdit.content.rows-=20;else return false" title="缩短文本框">↓↓<br><b>缩短</b><br>↑↑</a></td>
      <td colspan="4"><textarea name="content" cols="70" rows="10" wrap="VIRTUAL" class="input"  onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);"><? echo $BlogInfo[content]; ?></textarea></td>
    </tr>

		<tr>
			<td colspan="3">
<?php
	$at_month=substr($BlogInfo[addtime],5,2);
	$at_day=substr($BlogInfo[addtime],8,2);
	$at_year=substr($BlogInfo[addtime],0,4);
	$at_time=substr($BlogInfo[addtime],11,8);
	$at_hour=substr($BlogInfo[addtime],11,2);
	$at_minute=substr($BlogInfo[addtime],14,2);
	$at_second=substr($BlogInfo[addtime],17,2);
?>
		发布日期：年<input type="text" name="at_year" value="<?php echo $at_year; ?>" size="5" maxlength="4" title="格式：0000">
		月<select name="at_month"> 
<option value="00"  <?php if($at_month=="00")echo "selected"; ?>></option> 
<option value="01"  <?php if($at_month=="01")echo "selected"; ?>>1 月</option>
<option value="02"  <?php if($at_month=="02")echo "selected"; ?>>2 月</option>
<option value="03"  <?php if($at_month=="03")echo "selected"; ?>>3 月</option>
<option value="04"  <?php if($at_month=="04")echo "selected"; ?>>4 月</option>
<option value="05"  <?php if($at_month=="05")echo "selected"; ?>>5 月</option>
<option value="06"  <?php if($at_month=="06")echo "selected"; ?>>6 月</option>
<option value="07"  <?php if($at_month=="07")echo "selected"; ?>>7 月</option>
<option value="08"  <?php if($at_month=="08")echo "selected"; ?>>8 月</option>
<option value="09"  <?php if($at_month=="09")echo "selected"; ?>>9 月</option>
<option value="10"  <?php if($at_month=="10")echo "selected"; ?>>10 月</option>
<option value="11"  <?php if($at_month=="11")echo "selected"; ?>>11 月</option>
<option value="12"  <?php if($at_month=="12")echo "selected"; ?>>12 月</option>
		</select>日<select name="at_day">
<option value="00"  <?php if($at_day=="00")echo "selected"; ?>></option>
<option value="01"  <?php if($at_day=="01")echo "selected"; ?>>1日</option>
<option value="02"  <?php if($at_day=="02")echo "selected"; ?>>2日</option>
<option value="03"  <?php if($at_day=="03")echo "selected"; ?>>3日</option>
<option value="04"  <?php if($at_day=="04")echo "selected"; ?>>4日</option>
<option value="05"  <?php if($at_day=="05")echo "selected"; ?>>5日</option>
<option value="06"  <?php if($at_day=="06")echo "selected"; ?>>6日</option>
<option value="07"  <?php if($at_day=="07")echo "selected"; ?>>7日</option>
<option value="08"  <?php if($at_day=="08")echo "selected"; ?>>8日</option>
<option value="09"  <?php if($at_day=="09")echo "selected"; ?>>9日</option>
<option value="10"  <?php if($at_day=="10")echo "selected"; ?>>10日</option>
<option value="11"  <?php if($at_day=="11")echo "selected"; ?>>11日</option>
<option value="12"  <?php if($at_day=="12")echo "selected"; ?>>12日</option>
<option value="13"  <?php if($at_day=="13")echo "selected"; ?>>13日</option>
<option value="14"  <?php if($at_day=="14")echo "selected"; ?>>14日</option>
<option value="15"  <?php if($at_day=="15")echo "selected"; ?>>15日</option>
<option value="16"  <?php if($at_day=="16")echo "selected"; ?>>16日</option>
<option value="17"  <?php if($at_day=="17")echo "selected"; ?>>17日</option>
<option value="18"  <?php if($at_day=="18")echo "selected"; ?>>18日</option>
<option value="19"  <?php if($at_day=="19")echo "selected"; ?>>19日</option>
<option value="20"  <?php if($at_day=="20")echo "selected"; ?>>20日</option>
<option value="21"  <?php if($at_day=="21")echo "selected"; ?>>21日</option>
<option value="22"  <?php if($at_day=="22")echo "selected"; ?>>22日</option>
<option value="23"  <?php if($at_day=="23")echo "selected"; ?>>23日</option>
<option value="24"  <?php if($at_day=="24")echo "selected"; ?>>24日</option>
<option value="25"  <?php if($at_day=="25")echo "selected"; ?>>25日</option>
<option value="26"  <?php if($at_day=="26")echo "selected"; ?>>26日</option>
<option value="27"  <?php if($at_day=="27")echo "selected"; ?>>27日</option>
<option value="28"  <?php if($at_day=="28")echo "selected"; ?>>28日</option>
<option value="29"  <?php if($at_day=="29")echo "selected"; ?>>29日</option>
<option value="30"  <?php if($at_day=="30")echo "selected"; ?>>30日</option>
<option value="31"  <?php if($at_day=="31")echo "selected"; ?>>31日</option>
		</select>
		时<input type="text" name="at_hour" value="<?php echo $at_hour; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="格式：00">
		分<input type="text" name="at_minute" value="<?php echo $at_minute; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="格式：00">
		秒<input type="text" name="at_second" value="<?php echo $at_second; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="格式：00">
<img onclick="go_time();" onmouseover="this.style.cursor='hand';" name="gogotime" border="0" src="../images/ex_code/time_start.gif" alt="使用当前时间">
			</td>
    	</tr>

   <tr>
      <td colspan="4" align="center"><a href="../photo.php" target="_blank">↑点击此打开附件上传器↑</a>
　<A onclick=pasteC() href="#">→若发布失败请点此还原←</A>
　<a href="javascript:checklength(document.exEdit);" title="计算文字字节数">◆[查看长度]◆</a></td>
    </tr>
    <tr align="center">
      <td height="30" colspan="6"><input type="hidden" name="id" value="<? analysisPostBlogID($_POST['searchID'], $_GET['id']); ?>">
          <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
          <input type="submit" name="action" value="编辑文章" class="botton" onClick="javascript: return check();">
      </td>
    </tr>
 </table>
  <?
	}
    elseif($_GET['action'] == "add")
	{
?>
<body class="main" onload="go_time();">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>添加新文章</b></td>
    </tr>
    <tr> 
      <td width="40" height="23" align="center">标题:</td>
      <td height="23"> <input name="title" type="text" size="35" class="input"> <select name="isTop" id="isTop">
          <option value="0" selected>不置顶</option>
          <option value="1">置　顶</option>
        </select>
          <input type="hidden" name="html" value="0">
      </td>
      <td align="center">天气:</td>
      <td><select name="weather">
          <option value="null">请选择天气</option>
          <option value="sunny">晴朗</option>
		  <option value="cloudysky">阴天</option>
          <option value="cloudy">多云</option>
          <option value="night">夜晚</option>
          <option value="rain">下雨</option>
          <option value="snow">下雪</option>
        </select></td>
    </tr>
    <tr> 
      <td height="23" align="center">作者:</td> <td height="23"><input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input">
 　是否隐藏:　<select name="isHidden" id="isHidden">
          <option value="0" selected>否</option>
          <option value="1">是</option>
</select>　</td>
      <td align="center">分类:</td>
<td><select name="esort">
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <option value="<? echo $row['cnName']; ?>"><? echo $row['cnName']; ?></option>
          <? } ?>
        </select>
</td>
    </tr>
    <tr> 
      <td align="center" valign="top">辅助:</td>
      <td colspan="3"><table border="0" cellpadding="1" cellspacing="1">
          <tr> 
                  <td><a href="javascript:exEditadd(':ex2_0:')"><img src="../images/ex_pose/ex2_0.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_1:')"><img src="../images/ex_pose/ex2_1.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_2:')"><img src="../images/ex_pose/ex2_2.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_3:')"><img src="../images/ex_pose/ex2_3.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_4:')"><img src="../images/ex_pose/ex2_4.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_5:')"><img src="../images/ex_pose/ex2_5.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_6:')"><img src="../images/ex_pose/ex2_6.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_7:')"><img src="../images/ex_pose/ex2_7.gif" width="16" height="16" border="0"></a></td>

                  <td><a href="javascript:exEditadd(':ex2_8:')"><img src="../images/ex_pose/ex2_8.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_9:')"><img src="../images/ex_pose/ex2_9.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_10:')"><img src="../images/ex_pose/ex2_10.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_11:')"><img src="../images/ex_pose/ex2_11.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_12:')"><img src="../images/ex_pose/ex2_12.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_13:')"><img src="../images/ex_pose/ex2_13.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_14:')"><img src="../images/ex_pose/ex2_14.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_15:')"><img src="../images/ex_pose/ex2_15.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_16:')"><img src="../images/ex_pose/ex2_16.gif" width="16" height="16" border="0"></a></td></tr><tr>
                  <td><a href="javascript:exEditadd(':ex2_17:')"><img src="../images/ex_pose/ex2_17.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_18:')"><img src="../images/ex_pose/ex2_18.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_19:')"><img src="../images/ex_pose/ex2_19.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_20:')"><img src="../images/ex_pose/ex2_20.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_21:')"><img src="../images/ex_pose/ex2_21.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_22:')"><img src="../images/ex_pose/ex2_22.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_23:')"><img src="../images/ex_pose/ex2_23.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_24:')"><img src="../images/ex_pose/ex2_24.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_25:')"><img src="../images/ex_pose/ex2_25.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_26:')"><img src="../images/ex_pose/ex2_26.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_27:')"><img src="../images/ex_pose/ex2_27.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_28:')"><img src="../images/ex_pose/ex2_28.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_29:')"><img src="../images/ex_pose/ex2_29.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_30:')"><img src="../images/ex_pose/ex2_30.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_31:')"><img src="../images/ex_pose/ex2_31.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_32:')"><img src="../images/ex_pose/ex2_32.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_33:')"><img src="../images/ex_pose/ex2_33.gif" width="16" height="16" border="0"></a></td></tr><tr>
                  <td><a href="javascript:exEditadd(':ex2_34:')"><img src="../images/ex_pose/ex2_34.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_35:')"><img src="../images/ex_pose/ex2_35.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_36:')"><img src="../images/ex_pose/ex2_36.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_37:')"><img src="../images/ex_pose/ex2_37.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_38:')"><img src="../images/ex_pose/ex2_38.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_39:')"><img src="../images/ex_pose/ex2_39.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_40:')"><img src="../images/ex_pose/ex2_40.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_41:')"><img src="../images/ex_pose/ex2_41.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_42:')"><img src="../images/ex_pose/ex2_42.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_43:')"><img src="../images/ex_pose/ex2_43.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_44:')"><img src="../images/ex_pose/ex2_44.gif" width="16" height="16" border="0"></a></td>
</tr><tr>

                  <td><a href="javascript:exEditadd(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" border="0"></a></td>

                  <td><a href="javascript:exEditadd(':ex_8:')"><img src="../images/ex_pose/ex_8.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_9:')"><img src="../images/ex_pose/ex_9.gif" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex_10:')"><img src="../images/ex_pose/ex_10.gif" border="0"></a></td>
          </tr>
          <tr> 
            <td><img  onclick=code()  src="../images/ex_code/ex_code.gif" alt="插入代码" width="23" height="22" border="0"></td>
            <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="插入引用" width="23" height="22" border="0"></td>
            <td><img  onclick=bold()   src="../images/ex_code/ex_bold.gif" alt="插入粗体字" width="23" height="22" border="0"></td>
            <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="插入斜体字" width="23" height="22" border="0"></td>
            <td><img  onclick=strike()  src="../images/ex_code/ex_strike.gif" alt="插入删除字" width="23" height="22" border="0"></td>
            <td><img  onclick=sup()  src="../images/ex_code/ex_sup.gif" alt="插入上标字" width="23" height="22" border="0"></td>
            <td><img  onclick=exsub()  src="../images/ex_code/ex_sub.gif" alt="插入下标字" width="23" height="22" border="0"></td>
       		<td><img  onclick=list()  src="../images/ex_code/ex_list.gif" alt="插入列表" width="23" height="22" border="0"></td>
			<td><img  onclick=left()  src="../images/ex_code/ex_left.gif" alt="文字左对齐" width="23" height="22" border="0"></td>
			<td><img  onclick=center()  src="../images/ex_code/ex_center.gif" alt="文字居中" width="23" height="22" border="0"></td>
			<td><img  onclick=right()  src="../images/ex_code/ex_right.gif" alt="文字右对齐" width="23" height="22" border="0"></td>
			<td><img  onclick=justify()  src="../images/ex_code/ex_justify.gif" alt="文字左右自适应" width="23" height="22" border="0"></td>
			<td><img  onclick=underline()  src="../images/ex_code/ex_underline.gif" alt="插入下划线文字" width="23" height="22" border="0"></td>
            <td><img  onclick=move()  src="../images/ex_code/ex_move.gif" alt="移动文字" width="23" height="22" border="0"></td>
            <td><img  onclick=exblur()  src="../images/ex_code/ex_blur.gif" alt="模糊字" width="23" height="22" border="0"></td>
            <td><img  onclick=fliph()  src="../images/ex_code/ex_fliph.gif" alt="插入左右颠倒文字" width="23" height="22" border="0"></td>
            <td><img  onclick=flipv()  src="../images/ex_code/ex_flipv.gif" alt="插入上下颠倒文字" width="23" height="22" border="0"></td>
          </tr>
          <tr>
            <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="插入框架" width="23" height="22" border="0"></td>
            <td><img  onclick=htmlcode()  src="../images/ex_code/ex_htmlcode.gif" alt="插入HTML代码" width="23" height="22" border="0"></td>
            <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="插入email" width="23" height="22" border="0"></td>
            <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="插入超级链接" width="23" height="22" border="0"></td>
            <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="插入图像链接" width="23" height="22" border="0"></td>
			<td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="插入Flash文件" width="23" height="22" border="0"></td>
			<td><img  onclick=mp3()  src="../images/ex_code/ex_mp3.gif" alt="插入mp3音频" width="23" height="22" border="0"></td>
			<td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="插入rm视频" width="23" height="22" border="0"></td>
			<td><img  onclick=ra()  src="../images/ex_code/ex_ra.gif" alt="插入ra音频" width="23" height="22" border="0"></td>
			<td><img  onclick=wmv()  src="../images/ex_code/ex_wmv.gif" alt="插入wmv视频 支持格式 asf wmv avi" width="23" height="22" border="0"></td>
			<td><img  onclick=wma()  src="../images/ex_code/ex_wma.gif" alt="插入wma音频" width="23" height="22" border="0"></td>
			<td><img  onclick=mov()  src="../images/ex_code/ex_mov.gif" alt="插入mov 支持格式 mov pitc aiff" width="23" height="22" border="0"></td>
      <td colspan="5" align="right"><select name="mode" onChange="chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)">
<option value=2>UBB提示插入</option>
<option value=1>UBB帮助信息</option>
<option value=0>UBB直接插入</option>
</select></td>
	</tr>
		  </table>
		  <table class="main">
		<tr>
			<td width="190" valign="top">字体设置          
<select onChange=chfont(this.options[this.selectedIndex].value) name=font>
<option value="宋体" selected>宋体</option>
<option value="楷体_GB2312">楷体</option>
<option value="新宋体">新宋体</option>
<option value="黑体">黑体</option>
<option value="隶书">隶书</option>
<option value="Andale Mono">Andale Mono</option>
<option value=Arial>Arial</option>
<option value="Arial Black">Arial Black</option>
<option value="Book Antiqua">Book Antiqua</option>
<option value="Century Gothic">Century Gothic</option>
<option value="Comic Sans MS">Comic Sans MS</option>
<option value="Courier New">Courier New</option>
<option value=Georgia>Georgia</option>
<option value=Impact>Impact</option>
<option value=Tahoma>Tahoma</option>
<option value="Times New Roman" >Times New Roman</option>
<option value="Trebuchet MS">Trebuchet MS</option>
<option value="Script MT Bold">Script MT Bold</option>
<option value=Stencil>Stencil</option>
<option value=Verdana>Verdana</option>
<option value="Lucida Console">Lucida Console</option>
</select>
			</td>
			<td width="50">
           	<select name="size" onFocus="this.selectedIndex=2" onChange="chsize(this.options[this.selectedIndex].value)" size="1">
			<option value="-2">-2</option>
			<option value="-1">-1</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected>3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option></select>
			</td>
			<td width="85">
<SELECT onchange=chcolor(this.options[this.selectedIndex].value) name=color> 
<option style=background-color:#F0F8FF;color:#F0F8FF value=#F0F8FF>#F0F8FF</option>
<option style=background-color:#FAEBD7;color:#FAEBD7 value=#FAEBD7>#FAEBD7</option>
<option style=background-color:#00FFFF;color:#00FFFF value=#00FFFF>#00FFFF</option>
<option style=background-color:#7FFFD4;color:#7FFFD4 value=#7FFFD4>#7FFFD4</option>
<option style=background-color:#F0FFFF;color:#F0FFFF value=#F0FFFF>#F0FFFF</option>
<option style=background-color:#F5F5DC;color:#F5F5DC value=#F5F5DC>#F5F5DC</option>
<option style=background-color:#FFE4C4;color:#FFE4C4 value=#FFE4C4>#FFE4C4</option>
<option style=background-color:#000000;color:#000000 value=#000000>#000000</option>
<option style=background-color:#FFEBCD;color:#FFEBCD value=#FFEBCD>#FFEBCD</option>
<option style=background-color:#0000FF;color:#0000FF value=#0000FF>#0000FF</option>
<option style=background-color:#8A2BE2;color:#8A2BE2 value=#8A2BE2>#8A2BE2</option>
<option style=background-color:#A52A2A;color:#A52A2A value=#A52A2A>#A52A2A</option>
<option style=background-color:#DEB887;color:#DEB887 value=#DEB887>#DEB887</option>
<option style=background-color:#5F9EA0;color:#5F9EA0 value=#5F9EA0>#5F9EA0</option>
<option style=background-color:#7FFF00;color:#7FFF00 value=#7FFF00>#7FFF00</option>
<option style=background-color:#D2691E;color:#D2691E value=#D2691E>#D2691E</option>
<option style=background-color:#FF7F50;color:#FF7F50 value=#FF7F50>#FF7F50</option>
<option style=background-color:#6495ED;color:#6495ED value=#6495ED selected>#6495ED</option>
<option style=background-color:#FFF8DC;color:#FFF8DC value=#FFF8DC>#FFF8DC</option>
<option style=background-color:#DC143C;color:#DC143C value=#DC143C>#DC143C</option>
<option style=background-color:#00FFFF;color:#00FFFF value=#00FFFF>#00FFFF</option>
<option style=background-color:#00008B;color:#00008B value=#00008B>#00008B</option>
<option style=background-color:#008B8B;color:#008B8B value=#008B8B>#008B8B</option>
<option style=background-color:#B8860B;color:#B8860B value=#B8860B>#B8860B</option>
<option style=background-color:#A9A9A9;color:#A9A9A9 value=#A9A9A9>#A9A9A9</option>
<option style=background-color:#006400;color:#006400 value=#006400>#006400</option>
<option style=background-color:#BDB76B;color:#BDB76B value=#BDB76B>#BDB76B</option>
<option style=background-color:#8B008B;color:#8B008B value=#8B008B>#8B008B</option>
<option style=background-color:#556B2F;color:#556B2F value=#556B2F>#556B2F</option>
<option style=background-color:#FF8C00;color:#FF8C00 value=#FF8C00>#FF8C00</option>
<option style=background-color:#9932CC;color:#9932CC value=#9932CC>#9932CC</option>
<option style=background-color:#8B0000;color:#8B0000 value=#8B0000>#8B0000</option>
<option style=background-color:#E9967A;color:#E9967A value=#E9967A>#E9967A</option>
<option style=background-color:#8FBC8F;color:#8FBC8F value=#8FBC8F>#8FBC8F</option>
<option style=background-color:#483D8B;color:#483D8B value=#483D8B>#483D8B</option>
<option style=background-color:#2F4F4F;color:#2F4F4F value=#2F4F4F>#2F4F4F</option>
<option style=background-color:#00CED1;color:#00CED1 value=#00CED1>#00CED1</option>
<option style=background-color:#9400D3;color:#9400D3 value=#9400D3>#9400D3</option>
<option style=background-color:#FF1493;color:#FF1493 value=#FF1493>#FF1493</option>
<option style=background-color:#00BFFF;color:#00BFFF value=#00BFFF>#00BFFF</option>
<option style=background-color:#696969;color:#696969 value=#696969>#696969</option>
<option style=background-color:#1E90FF;color:#1E90FF value=#1E90FF>#1E90FF</option>
<option style=background-color:#B22222;color:#B22222 value=#B22222>#B22222</option>
<option style=background-color:#FFFAF0;color:#FFFAF0 value=#FFFAF0>#FFFAF0</option>
<option style=background-color:#228B22;color:#228B22 value=#228B22>#228B22</option>
<option style=background-color:#FF00FF;color:#FF00FF value=#FF00FF>#FF00FF</option>
<option style=background-color:#DCDCDC;color:#DCDCDC value=#DCDCDC>#DCDCDC</option>
<option style=background-color:#F8F8FF;color:#F8F8FF value=#F8F8FF>#F8F8FF</option>
<option style=background-color:#FFD700;color:#FFD700 value=#FFD700>#FFD700</option>
<option style=background-color:#DAA520;color:#DAA520 value=#DAA520>#DAA520</option>
<option style=background-color:#808080;color:#808080 value=#808080>#808080</option>
<option style=background-color:#008000;color:#008000 value=#008000>#008000</option>
<option style=background-color:#ADFF2F;color:#ADFF2F value=#ADFF2F>#ADFF2F</option>
<option style=background-color:#F0FFF0;color:#F0FFF0 value=#F0FFF0>#F0FFF0</option>
<option style=background-color:#FF69B4;color:#FF69B4 value=#FF69B4>#FF69B4</option>
<option style=background-color:#CD5C5C;color:#CD5C5C value=#CD5C5C>#CD5C5C</option>
<option style=background-color:#4B0082;color:#4B0082 value=#4B0082>#4B0082</option>
<option style=background-color:#FFFFF0;color:#FFFFF0 value=#FFFFF0>#FFFFF0</option>
<option style=background-color:#F0E68C;color:#F0E68C value=#F0E68C>#F0E68C</option>
<option style=background-color:#E6E6FA;color:#E6E6FA value=#E6E6FA>#E6E6FA</option>
<option style=background-color:#FFF0F5;color:#FFF0F5 value=#FFF0F5>#FFF0F5</option>
<option style=background-color:#7CFC00;color:#7CFC00 value=#7CFC00>#7CFC00</option>
<option style=background-color:#FFFACD;color:#FFFACD value=#FFFACD>#FFFACD</option>
<option style=background-color:#ADD8E6;color:#ADD8E6 value=#ADD8E6>#ADD8E6</option>
<option style=background-color:#F08080;color:#F08080 value=#F08080>#F08080</option>
<option style=background-color:#E0FFFF;color:#E0FFFF value=#E0FFFF>#E0FFFF</option>
<option style=background-color:#FAFAD2;color:#FAFAD2 value=#FAFAD2>#FAFAD2</option>
<option style=background-color:#90EE90;color:#90EE90 value=#90EE90>#90EE90</option>
<option style=background-color:#D3D3D3;color:#D3D3D3 value=#D3D3D3>#D3D3D3</option>
<option style=background-color:#FFB6C1;color:#FFB6C1 value=#FFB6C1>#FFB6C1</option>
<option style=background-color:#FFA07A;color:#FFA07A value=#FFA07A>#FFA07A</option>
<option style=background-color:#20B2AA;color:#20B2AA value=#20B2AA>#20B2AA</option>
<option style=background-color:#87CEFA;color:#87CEFA value=#87CEFA>#87CEFA</option>
<option style=background-color:#778899;color:#778899 value=#778899>#778899</option>
<option style=background-color:#B0C4DE;color:#B0C4DE value=#B0C4DE>#B0C4DE</option>
<option style=background-color:#FFFFE0;color:#FFFFE0 value=#FFFFE0>#FFFFE0</option>
<option style=background-color:#00FF00;color:#00FF00 value=#00FF00>#00FF00</option>
<option style=background-color:#32CD32;color:#32CD32 value=#32CD32>#32CD32</option>
<option style=background-color:#FAF0E6;color:#FAF0E6 value=#FAF0E6>#FAF0E6</option>
<option style=background-color:#FF00FF;color:#FF00FF value=#FF00FF>#FF00FF</option>
<option style=background-color:#800000;color:#800000 value=#800000>#800000</option>
<option style=background-color:#66CDAA;color:#66CDAA value=#66CDAA>#66CDAA</option>
<option style=background-color:#0000CD;color:#0000CD value=#0000CD>#0000CD</option>
<option style=background-color:#BA55D3;color:#BA55D3 value=#BA55D3>#BA55D3</option>
<option style=background-color:#9370DB;color:#9370DB value=#9370DB>#9370DB</option>
<option style=background-color:#3CB371;color:#3CB371 value=#3CB371>#3CB371</option>
<option style=background-color:#7B68EE;color:#7B68EE value=#7B68EE>#7B68EE</option>
<option style=background-color:#00FA9A;color:#00FA9A value=#00FA9A>#00FA9A</option>
<option style=background-color:#48D1CC;color:#48D1CC value=#48D1CC>#48D1CC</option>
<option style=background-color:#C71585;color:#C71585 value=#C71585>#C71585</option>
<option style=background-color:#191970;color:#191970 value=#191970>#191970</option>
<option style=background-color:#F5FFFA;color:#F5FFFA value=#F5FFFA>#F5FFFA</option>
<option style=background-color:#FFE4E1;color:#FFE4E1 value=#FFE4E1>#FFE4E1</option>
<option style=background-color:#FFE4B5;color:#FFE4B5 value=#FFE4B5>#FFE4B5</option>
<option style=background-color:#FFDEAD;color:#FFDEAD value=#FFDEAD>#FFDEAD</option>
<option style=background-color:#000080;color:#000080 value=#000080>#000080</option>
<option style=background-color:#FDF5E6;color:#FDF5E6 value=#FDF5E6>#FDF5E6</option>
<option style=background-color:#808000;color:#808000 value=#808000>#808000</option>
<option style=background-color:#6B8E23;color:#6B8E23 value=#6B8E23>#6B8E23</option>
<option style=background-color:#FFA500;color:#FFA500 value=#FFA500>#FFA500</option>
<option style=background-color:#FF4500;color:#FF4500 value=#FF4500>#FF4500</option>
<option style=background-color:#DA70D6;color:#DA70D6 value=#DA70D6>#DA70D6</option>
<option style=background-color:#EEE8AA;color:#EEE8AA value=#EEE8AA>#EEE8AA</option>
<option style=background-color:#98FB98;color:#98FB98 value=#98FB98>#98FB98</option>
<option style=background-color:#AFEEEE;color:#AFEEEE value=#AFEEEE>#AFEEEE</option>
<option style=background-color:#DB7093;color:#DB7093 value=#DB7093>#DB7093</option>
<option style=background-color:#FFEFD5;color:#FFEFD5 value=#FFEFD5>#FFEFD5</option>
<option style=background-color:#FFDAB9;color:#FFDAB9 value=#FFDAB9>#FFDAB9</option>
<option style=background-color:#CD853F;color:#CD853F value=#CD853F>#CD853F</option>
<option style=background-color:#FFC0CB;color:#FFC0CB value=#FFC0CB>#FFC0CB</option>
<option style=background-color:#DDA0DD;color:#DDA0DD value=#DDA0DD>#DDA0DD</option>
<option style=background-color:#B0E0E6;color:#B0E0E6 value=#B0E0E6>#B0E0E6</option>
<option style=background-color:#800080;color:#800080 value=#800080>#800080</option>
<option style=background-color:#FF0000;color:#FF0000 value=#FF0000>#FF0000</option>
<option style=background-color:#BC8F8F;color:#BC8F8F value=#BC8F8F>#BC8F8F</option>
<option style=background-color:#4169E1;color:#4169E1 value=#4169E1>#4169E1</option>
<option style=background-color:#8B4513;color:#8B4513 value=#8B4513>#8B4513</option>
<option style=background-color:#FA8072;color:#FA8072 value=#FA8072>#FA8072</option>
<option style=background-color:#F4A460;color:#F4A460 value=#F4A460>#F4A460</option>
<option style=background-color:#2E8B57;color:#2E8B57 value=#2E8B57>#2E8B57</option>
<option style=background-color:#FFF5EE;color:#FFF5EE value=#FFF5EE>#FFF5EE</option>
<option style=background-color:#A0522D;color:#A0522D value=#A0522D>#A0522D</option>
<option style=background-color:#C0C0C0;color:#C0C0C0 value=#C0C0C0>#C0C0C0</option>
<option style=background-color:#87CEEB;color:#87CEEB value=#87CEEB>#87CEEB</option>
<option style=background-color:#6A5ACD;color:#6A5ACD value=#6A5ACD>#6A5ACD</option>
<option style=background-color:#708090;color:#708090 value=#708090>#708090</option>
<option style=background-color:#FFFAFA;color:#FFFAFA value=#FFFAFA>#FFFAFA</option>
<option style=background-color:#00FF7F;color:#00FF7F value=#00FF7F>#00FF7F</option>
<option style=background-color:#4682B4;color:#4682B4 value=#4682B4>#4682B4</option>
<option style=background-color:#D2B48C;color:#D2B48C value=#D2B48C>#D2B48C</option>
<option style=background-color:#008080;color:#008080 value=#008080>#008080</option>
<option style=background-color:#D8BFD8;color:#D8BFD8 value=#D8BFD8>#D8BFD8</option>
<option style=background-color:#FF6347;color:#FF6347 value=#FF6347>#FF6347</option>
<option style=background-color:#40E0D0;color:#40E0D0 value=#40E0D0>#40E0D0</option>
<option style=background-color:#EE82EE;color:#EE82EE value=#EE82EE>#EE82EE</option>
<option style=background-color:#F5DEB3;color:#F5DEB3 value=#F5DEB3>#F5DEB3</option>
<option style=background-color:#FFFFFF;color:#FFFFFF value=#FFFFFF>#FFFFFF</option>
<option style=background-color:#F5F5F5;color:#F5F5F5 value=#F5F5F5>#F5F5F5</option>
<option style=background-color:#FFFF00;color:#FFFF00 value=#FFFF00>#FFFF00</option>
<option style=background-color:#9ACD32;color:#9ACD32 value=#9ACD32>#9ACD32</option>
</SELECT>
			</td>
<td>
                特殊字符：<select name="fontsign" onChange="exEditadd(this.options[this.selectedIndex].value)" >
                          <option value="哈哈！大家一起跟我喊：exBlog!">特</option>
                          <option value="■">■</option>
                          <option value="□">□</option>
                          <option value="▲">▲</option>
                          <option value="△">△</option>
                          <option value="▼">▼</option>
                          <option value="▽">▽</option>
                          <option value="◆">◆</option>
                          <option value="◇">◇</option>
                          <option value="○">○</option>
                          <option value="◎">◎</option>
                          <option value="●">●</option>
                          <option value="★">★</option>
                          <option value="◆">◆</option>
                          <option value="◇">◇</option>
                          <option value="○">○</option>
                          <option value="◎">◎</option>
                          <option value="●">●</option>
                          <option value="★">★</option>
                          <option value="☆">☆</option>
                          <option value="♀">♀</option>
                          <option value="♂">♂</option>
                          <option value="		">Tab</option>
                        </select>
</td>
</tr>
   </table>


      </td>
    </tr>
   <tr>
      <td align="center" valign="top">内容:<br>
<br>
<br><a onclick="document.exEdit.content.rows+=20;" title="拉长文本框，方便书写和查看">↑↑<br><b>拉长</b><br>↓↓</a><br>
<br><a onclick="if(document.exEdit.content.rows>=20)document.exEdit.content.rows-=20;else return false" title="缩短文本框">↓↓<br><b>缩短</b><br>↑↑</a></td>
      <td colspan="3"><textarea name="content" cols="70" rows="10" wrap="VIRTUAL" class="input"  onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);"><? echo $BlogInfo[content]; ?></textarea></td>
    </tr>

		<tr><td></td>
			<td colspan="3">
<?php

	$at_month=date(m);
	$at_day=date(d);
	$at_year=date(Y);
	$at_time=date(H.":".i.":".s);
	$at_hour=date(H);
	$at_minute=date(i);
	$at_second=date(s);
?>
		发布日期：年<input type="text" name="at_year" value="<?php echo $at_year; ?>" size="5" maxlength="4" title="格式：0000">
		月<select name="at_month"> 
<option value="00"  <?php if($at_month=="00")echo "selected"; ?>></option> 
<option value="01"  <?php if($at_month=="01")echo "selected"; ?>>1 月</option>
<option value="02"  <?php if($at_month=="02")echo "selected"; ?>>2 月</option>
<option value="03"  <?php if($at_month=="03")echo "selected"; ?>>3 月</option>
<option value="04"  <?php if($at_month=="04")echo "selected"; ?>>4 月</option>
<option value="05"  <?php if($at_month=="05")echo "selected"; ?>>5 月</option>
<option value="06"  <?php if($at_month=="06")echo "selected"; ?>>6 月</option>
<option value="07"  <?php if($at_month=="07")echo "selected"; ?>>7 月</option>
<option value="08"  <?php if($at_month=="08")echo "selected"; ?>>8 月</option>
<option value="09"  <?php if($at_month=="09")echo "selected"; ?>>9 月</option>
<option value="10"  <?php if($at_month=="10")echo "selected"; ?>>10 月</option>
<option value="11"  <?php if($at_month=="11")echo "selected"; ?>>11 月</option>
<option value="12"  <?php if($at_month=="12")echo "selected"; ?>>12 月</option>
		</select>日<select name="at_day">
<option value="00"  <?php if($at_day=="00")echo "selected"; ?>></option>
<option value="01"  <?php if($at_day=="01")echo "selected"; ?>>1日</option>
<option value="02"  <?php if($at_day=="02")echo "selected"; ?>>2日</option>
<option value="03"  <?php if($at_day=="03")echo "selected"; ?>>3日</option>
<option value="04"  <?php if($at_day=="04")echo "selected"; ?>>4日</option>
<option value="05"  <?php if($at_day=="05")echo "selected"; ?>>5日</option>
<option value="06"  <?php if($at_day=="06")echo "selected"; ?>>6日</option>
<option value="07"  <?php if($at_day=="07")echo "selected"; ?>>7日</option>
<option value="08"  <?php if($at_day=="08")echo "selected"; ?>>8日</option>
<option value="09"  <?php if($at_day=="09")echo "selected"; ?>>9日</option>
<option value="10"  <?php if($at_day=="10")echo "selected"; ?>>10日</option>
<option value="11"  <?php if($at_day=="11")echo "selected"; ?>>11日</option>
<option value="12"  <?php if($at_day=="12")echo "selected"; ?>>12日</option>
<option value="13"  <?php if($at_day=="13")echo "selected"; ?>>13日</option>
<option value="14"  <?php if($at_day=="14")echo "selected"; ?>>14日</option>
<option value="15"  <?php if($at_day=="15")echo "selected"; ?>>15日</option>
<option value="16"  <?php if($at_day=="16")echo "selected"; ?>>16日</option>
<option value="17"  <?php if($at_day=="17")echo "selected"; ?>>17日</option>
<option value="18"  <?php if($at_day=="18")echo "selected"; ?>>18日</option>
<option value="19"  <?php if($at_day=="19")echo "selected"; ?>>19日</option>
<option value="20"  <?php if($at_day=="20")echo "selected"; ?>>20日</option>
<option value="21"  <?php if($at_day=="21")echo "selected"; ?>>21日</option>
<option value="22"  <?php if($at_day=="22")echo "selected"; ?>>22日</option>
<option value="23"  <?php if($at_day=="23")echo "selected"; ?>>23日</option>
<option value="24"  <?php if($at_day=="24")echo "selected"; ?>>24日</option>
<option value="25"  <?php if($at_day=="25")echo "selected"; ?>>25日</option>
<option value="26"  <?php if($at_day=="26")echo "selected"; ?>>26日</option>
<option value="27"  <?php if($at_day=="27")echo "selected"; ?>>27日</option>
<option value="28"  <?php if($at_day=="28")echo "selected"; ?>>28日</option>
<option value="29"  <?php if($at_day=="29")echo "selected"; ?>>29日</option>
<option value="30"  <?php if($at_day=="30")echo "selected"; ?>>30日</option>
<option value="31"  <?php if($at_day=="31")echo "selected"; ?>>31日</option>
		</select>
		时<input type="text" name="at_hour" value="<?php echo $at_hour; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="格式：00">
		分<input type="text" name="at_minute" value="<?php echo $at_minute; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="格式：00">
		秒<input type="text" name="at_second" value="<?php echo $at_second; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="格式：00">
<img onclick="go_time();" onmouseover="this.style.cursor='hand';" name="gogotime" border="0" src="../images/ex_code/time_stop.gif" alt="使用自定义时间">
			</td>
    	</tr>


   <tr>
      <td colspan="4" align="center"><a href="../photo.php" target="_blank">↑点击此打开附件上传器↑</a>
　<A onclick=pasteC() href="#">→若发布失败请点此还原←</A>
　<a href="javascript:checklength(document.exEdit);" title="计算文字字节数">◆[查看长度]◆</a></td>
    </tr>
    <tr align="center"> 
      <td height="30" colspan="4"> <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>"> 
        <input type="submit" name="action" value="添加文章" class="botton" onClick="javascript: return check();"></td>
    </tr>
  </table>
  <?php
    }
	elseif($_GET['action'] == "edit")			
	{
		checkUserUid($_SESSION[exPass]);     ### 检查用户权限
		selectBlogModify();                  ### 显示文章列表
		cutBlogPage();                       ### 分页
?>
<body class="main">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan="5" height=25 align="center"><b>修改文章</b></td>
    </tr>
    <tr>
      <td height="23">&nbsp;</td>
      <td colspan="2"><font color="#FF0000">#</font> <font color="#0000FF">通过ID直接查找文章</font>
          <input name="searchID" type="text" class="input" size="5"></td>
      <td><input type="submit" name="action" value="编辑" class="botton"></td>
      <td><input type="submit" name="action" value="删除" class="botton"></td>
    </tr>
    <?php
    while($editBlog=mysql_fetch_array($resultBlog))
	{
?>
    <tr>
      <td width="6%" height="23">&nbsp;id:[<? echo "$editBlog[id]"; ?>]      </td>
      <td width="3%"><div align="right">
        <input type="checkbox" name="article[]" value="<? echo "$editBlog[id]"?>">
      </div></td>
      <? if($editBlog[html]=="0"){ ?>
      <td width="36%"><a href="../admin/./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>" title="编辑该文章"><? echo "$editBlog[title]" ?></a></td>
      <td width="5%"><a href="../admin/./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>">[编辑]</a></td>
<? }else{ ?>
      <td width="65%"><a href="../admin/./edithtmlblog.php?action=modify&id=<? echo "$editBlog[id]"?>" title="编辑该文章"><? echo "$editBlog[title]" ?></a></td>
      <td width="10%"><a href="../admin/./edithtmlblog.php?action=modify&id=<? echo "$editBlog[id]"?>">[编辑]</a></td>
<? } ?>
      <td width="10%"><a href="../admin/./editblog.php?action=delete&id=<? echo "$editBlog[id]"?>" onclick="if(confirm ('你确认要删除么？')){;}else{return false;}">[删除]</a></td>
    </tr>
    <? } ?>
    <tr>
      <td colspan="3"><div align="center"></div></td>
      <td colspan="2"><input type="submit" name="action" value="批量删除" class="botton" onclick="if(confirm ('你确认要删除么？')){;}else{return false;}"></td>
      
	</tr>
    <tr>
      <td colspan="4">

  <? 
	 $show_pages=cutBlogPage();
		  //显示最终结果
	echo"<br>查询结果<br>$show_pages<br>";
	 echo $display;
    }
	?>
</td>
    </tr>
  </table>
</form>
</body>
</html>

