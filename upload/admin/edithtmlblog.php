<?php
/*============================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
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
selectAllWeather(); /* sheeryiro changed */
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
</head>
<body class="main">
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
<!-- 弹出html编辑器 -->
<script>
function openeditor(){
if (navigator.appName!="Microsoft Internet Explorer")
   alert("此功能 Netscape 用户不能使用！")
else {newwin=window.open('htmleditor/editor.html','','width=544,height=294');  newwin.focus(); }
}
</script>
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>" name="exEdit" onsubmit="copyC()">
<script language="JavaScript">
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

function checklength(theform) {
message = "";
	alert("你的信息已经有 "+theform.content.value.length+" 字节."+message);
}
</script>

<script language="JavaScript">

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
	}
</script>	

<?
    if($_POST['action'] == "添加文章")
	{
		if(!$_POST[at_year]) $at_year=date(Y);
		if(!$_POST[at_month]) $at_month=date(m);
		if(!$_POST[at_day]) $at_day=date(d);
		if(!$_POST[at_hour]) $at_hour=date(H);
		if(!$_POST[at_minute]) $at_minute=date(i);
		if(!$_POST[at_second]) $at_second=date(s);
		$addtime=$at_year."-".$at_month."-".$at_day." ".$at_hour.":".$at_minute.":".$at_second;
	    addBlog($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['esort'], $addtime, $_POST['weather'], $_POST['isTop'], $_POST[isHidden], $_POST[html]);
	}
	elseif($_POST['action'] == "编辑文章")
	{
		if(!$_POST[at_year]) $at_year="0000";
		if(!$_POST[at_month]) $at_month="00";
		if(!$_POST[at_day]) $at_day="00";
		if(!$_POST[at_hour]) $at_hour="00";
		if(!$_POST[at_minute]) $at_minute="00";
		if(!$_POST[at_second]) $at_second="00";
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
	if($_GET['action'] == "modify" || $_POST['action'] == "编辑")
	{
		checkUserUid($_SESSION[exPass]);          ### 检查用户权限
		//---- 检果是否通过search查找的 ----//
		if($_POST['action'] == "编辑")
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

  <table width="510" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>编辑新文章</b></td>
    </tr>
    <tr>
      <td width="40" height="23" align="center">标题:</td>
      <td><input name="title" type="text" size="35" value="<? echo $BlogInfo[title]; ?>" class="input">
          <select name="isTop" id="isTop">
          <option value="<? echo $BlogInfo[top]; ?>" selected><? editBlogSelTop($BlogInfo[top])?></option>
          <option value="<? echo 1-$BlogInfo[top]; ?>"><? editBlogSelTop(1-$BlogInfo[top])?></option>
      </select>
          <input type="hidden" name="html" value="1">
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
      <td height="23" align="center" colspan="4">作者:
      <input name="author" type="text" value="<? echo $BlogInfo[author]; ?>" class="input">
      　分类:
      <select name="esort" class="botton">
          <? 
	while($row=mysql_fetch_array($resultSort))
	{ 
?>
          <option value="<? echo $row['cnName']; ?>" <? editBlogSelSort($row['cnName'],$BlogInfo['sort']); ?>> <? echo $row['cnName']; ?></option>
          <? } ?>
      </select>
      是否隐藏:<select name="isHidden" id="isHidden">
          <option value="<? echo $BlogInfo[hidden]; ?>" selected><? editBlogSelHidden($BlogInfo[hidden])?></option>
          <option value="<? echo 1-$BlogInfo[hidden]; ?>"><? editBlogSelHidden(1-$BlogInfo[hidden])?></option>
      </select>
</td>
    </tr>


		<tr>
      <td align="center" valign="top">发布日期:</td>
			<td colspan="3" width="100%">
<?php

	$at_month=substr($BlogInfo[addtime],5,2);
	$at_day=substr($BlogInfo[addtime],8,2);
	$at_year=substr($BlogInfo[addtime],0,4);
	$at_time=substr($BlogInfo[addtime],11,8);
	$at_hour=substr($BlogInfo[addtime],11,2);
	$at_minute=substr($BlogInfo[addtime],14,2);
	$at_second=substr($BlogInfo[addtime],17,2);
?>
		年<input type="text" class="bginput" name="at_year" value="<?php echo $at_year; ?>" size="5" maxlength="4">
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
		</select>时<select name="at_hour"> 
<option value="00"  <?php if($at_hour=="00")echo "selected"; ?>>00</option> 
<option value="01"  <?php if($at_hour=="01")echo "selected"; ?>>01</option>
<option value="02"  <?php if($at_hour=="02")echo "selected"; ?>>02</option>
<option value="03"  <?php if($at_hour=="03")echo "selected"; ?>>03</option>
<option value="04"  <?php if($at_hour=="04")echo "selected"; ?>>04</option>
<option value="05"  <?php if($at_hour=="05")echo "selected"; ?>>05</option>
<option value="06"  <?php if($at_hour=="06")echo "selected"; ?>>06</option>
<option value="07"  <?php if($at_hour=="07")echo "selected"; ?>>07</option>
<option value="08"  <?php if($at_hour=="08")echo "selected"; ?>>08</option>
<option value="09"  <?php if($at_hour=="09")echo "selected"; ?>>09</option>
<option value="10"  <?php if($at_hour=="10")echo "selected"; ?>>10</option>
<option value="11"  <?php if($at_hour=="11")echo "selected"; ?>>11</option>
<option value="12"  <?php if($at_hour=="12")echo "selected"; ?>>12</option>
<option value="13"  <?php if($at_hour=="13")echo "selected"; ?>>13</option>
<option value="14"  <?php if($at_hour=="14")echo "selected"; ?>>14</option>
<option value="15"  <?php if($at_hour=="15")echo "selected"; ?>>15</option>
<option value="16"  <?php if($at_hour=="16")echo "selected"; ?>>16</option>
<option value="17"  <?php if($at_hour=="17")echo "selected"; ?>>17</option>
<option value="18"  <?php if($at_hour=="18")echo "selected"; ?>>18</option>
<option value="19"  <?php if($at_hour=="19")echo "selected"; ?>>19</option>
<option value="20"  <?php if($at_hour=="20")echo "selected"; ?>>20</option>
<option value="21"  <?php if($at_hour=="21")echo "selected"; ?>>21</option>
<option value="22"  <?php if($at_hour=="22")echo "selected"; ?>>22</option>
<option value="23"  <?php if($at_hour=="23")echo "selected"; ?>>23</option>
		</select>
		分<input type="text" class="bginput" name="at_minute" value="<?php echo $at_minute; ?>" size="3" maxlength="2">
		秒<input type="text" class="bginput" name="at_second" value="<?php echo $at_second; ?>" size="3" maxlength="2">
      </td>
    </tr>


    <tr> 
      <td align="center" valign="top">辅助:</td>
      <td colspan="3">
		  <table class="main">
		<tr>
			<td width="100%">
<a href="javascript:openeditor();" title="使用[HTML编辑器]更方便所见即所得，但功能有限。">◆[HTML编辑器]◆</a>   指定发布日期:<input name="addtime" type="text" value="<? echo $BlogInfo[addtime]; ?>" class="input">
			</td>
    	</tr>
   </table>

      </td>
    </tr>
    <tr>
      <td rowspan="2" align="center" valign="top">内容:<br>
<br>
<br><a onclick=document.exEdit.content.rows='30'; title="拉长文本框，方便书写和查看">↑↑<br><b>拉长</b><br>↓↓</a><br>
<br><a onclick=document.exEdit.content.rows='10'; title="缩短文本框">↓↓<br><b>缩短</b><br>↑↑</a></td>
      <td colspan="4"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"><? echo $BlogInfo[content]; ?></textarea></td>
    </tr>
   <tr>
      <td colspan="4" align="center"><a href="../photo.php?html=1" target="_blank">↑点击此打开附件上传器↑</a>
　<A onclick=pasteC() href="#">→若发布失败请点此还原←</A>
　<a href="javascript:checklength(document.exEdit);" title="计算文字字节数">◆[查看长度]◆</a></td>
    </tr>
    <tr align="center">
      <td height="30" colspan="6"><input type="hidden" name="id" value="<? analysisPostBlogID($_POST['searchID'], $_GET['id']); ?>">
          <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
          <input type="submit" name="action" value="编辑文章" class="botton" onClick="javascript: return check();">
      </td>
    </tr>
    <tr> 
      <td height="30" colspan="4">
<FONT color=#ff0000><STRONG>HTML编辑器使用注意事项</STRONG></FONT> 
<UL>
<LI>这个只是exBlog捆绑的编辑器，可能功能并不能满足你的要求。</LI>
<LI>你可以选择exBlog随后发布的编辑器插件，来替换这个编辑器。</LI>
<LI>本编辑器仅支持IE5.0以上版本的浏览器。</LI>
<LI>不要在这里写脚本，会被过滤的。</LI>
<LI>不要弄太夸张的表格，可能显示时会对版面有影响。</LI>
<LI>嘿！Blog快乐！</LI></UL>
      </td>
    </tr>
 </table>
  <?
	}
    elseif($_GET['action'] == "add")
	{

	echo "<br /><FONT color=#ff0000><STRONG>HTML编辑器还没有完善在这个版本,暂不提供支持,给您带来的不便,请见谅.</STRONG></FONT><br />";
	exit();

?>
  <table width="510" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="100%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>添加新文章</b></td>
    </tr>
    <tr> 
      <td width="40" height="23" align="center">标题:</td>
      <td height="23"> <input name="title" type="text" size="35" class="input"> <select name="isTop" id="isTop">
          <option value="0" selected>不置顶</option>
          <option value="1">置　顶</option>
        </select>
          <input type="hidden" name="html" value="1">
      </td>
      <td align="center">天气:</td>
      <td><select name="weather">
          <option value="null">请选择天气</option>
          <option value="sunny">阳光</option>
          <option value="cloudy">多云</option>
          <option value="night">夜晚</option>
          <option value="rain">下雨</option>
          <option value="snow">下雪</option>
        </select></td>
    </tr>
    <tr> 
      <td height="23" align="center" colspan="4">作者:<input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input">
　分类:<select name="esort" class="botton">
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <option value="<? echo $row['cnName']; ?>"><? echo $row['cnName']; ?></option>
          <? } ?>
        </select>
      是否隐藏:<select name="isHidden" id="isHidden">
          <option value="0" selected>否</option>
          <option value="1">是</option>
</select>
</td>
    </tr>




		<tr>
      <td align="center" valign="top">发布日期:</td>
			<td colspan="3" width="100%">
<?php

	$at_month=date(m);
	$at_day=date(d);
	$at_year=date(Y);
	$at_time=date(H.":".i.":".s);
	$at_hour=date(H);
	$at_minute=date(i);
	$at_second=date(s);
?>
		年<input type="text" class="bginput" name="at_year" value="<?php echo $at_year; ?>" size="5" maxlength="4">
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
		</select>时<select name="at_hour"> 
<option value="00"  <?php if($at_hour=="00")echo "selected"; ?>>00</option> 
<option value="01"  <?php if($at_hour=="01")echo "selected"; ?>>01</option>
<option value="02"  <?php if($at_hour=="02")echo "selected"; ?>>02</option>
<option value="03"  <?php if($at_hour=="03")echo "selected"; ?>>03</option>
<option value="04"  <?php if($at_hour=="04")echo "selected"; ?>>04</option>
<option value="05"  <?php if($at_hour=="05")echo "selected"; ?>>05</option>
<option value="06"  <?php if($at_hour=="06")echo "selected"; ?>>06</option>
<option value="07"  <?php if($at_hour=="07")echo "selected"; ?>>07</option>
<option value="08"  <?php if($at_hour=="08")echo "selected"; ?>>08</option>
<option value="09"  <?php if($at_hour=="09")echo "selected"; ?>>09</option>
<option value="10"  <?php if($at_hour=="10")echo "selected"; ?>>10</option>
<option value="11"  <?php if($at_hour=="11")echo "selected"; ?>>11</option>
<option value="12"  <?php if($at_hour=="12")echo "selected"; ?>>12</option>
<option value="13"  <?php if($at_hour=="13")echo "selected"; ?>>13</option>
<option value="14"  <?php if($at_hour=="14")echo "selected"; ?>>14</option>
<option value="15"  <?php if($at_hour=="15")echo "selected"; ?>>15</option>
<option value="16"  <?php if($at_hour=="16")echo "selected"; ?>>16</option>
<option value="17"  <?php if($at_hour=="17")echo "selected"; ?>>17</option>
<option value="18"  <?php if($at_hour=="18")echo "selected"; ?>>18</option>
<option value="19"  <?php if($at_hour=="19")echo "selected"; ?>>19</option>
<option value="20"  <?php if($at_hour=="20")echo "selected"; ?>>20</option>
<option value="21"  <?php if($at_hour=="21")echo "selected"; ?>>21</option>
<option value="22"  <?php if($at_hour=="22")echo "selected"; ?>>22</option>
<option value="23"  <?php if($at_hour=="23")echo "selected"; ?>>23</option>
		</select>
		分<input type="text" class="bginput" name="at_minute" value="<?php echo $at_minute; ?>" size="3" maxlength="2">
		秒<input type="text" class="bginput" name="at_second" value="<?php echo $at_second; ?>" size="3" maxlength="2">
      </td>
    </tr>

    <tr> 
      <td align="center" valign="top">辅助:</td>
      <td colspan="3"><a href="javascript:openeditor();" title="使用[HTML编辑器]更方便所见即所得，但功能有限。">◆[HTML编辑器]</a>
      </td>
    </tr>
   <tr>
      <td align="center" valign="top">内容:<br>
<br>
<br><a onclick=document.exEdit.content.rows='30'; title="拉长文本框，方便书写和查看">↑↑<br><b>拉长</b><br>↓↓</a><br>
<br><a onclick=document.exEdit.content.rows='10'; title="缩短文本框">↓↓<br><b>缩短</b><br>↑↑</a></td>
      <td colspan="3"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"><? echo $BlogInfo[content]; ?></textarea></td>
    </tr>
   <tr>
      <td colspan="4" align="center"><a href="../photo.php?html=1" target="_blank">↑点击此打开附件上传器↑</a>
　<A onclick=pasteC() href="#">→若发布失败请点此还原←</A>
　<a href="javascript:checklength(document.exEdit);" title="计算文字字节数">◆[查看长度]◆</a></td>
    </tr>
    <tr align="center"> 
      <td height="30" colspan="4"> <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>"> 
        <input type="submit" name="action" value="添加文章" class="botton" onClick="javascript: return check();"> </td>
    </tr>
    <tr> 
      <td height="30" colspan="4">
<FONT color=#ff0000><STRONG>HTML编辑器使用注意事项</STRONG></FONT> 
<UL>
<LI>这个只是exBlog捆绑的编辑器，可能功能并不能满足你的要求。</LI>
<LI>你可以选择exBlog随后发布的编辑器插件，来替换这个编辑器。</LI>
<LI>本编辑器仅支持IE5.0以上版本的浏览器。</LI>
<LI>不要在这里写脚本，会被过滤的。</LI>
<LI>不要弄太夸张的表格，可能显示时会对版面有影响。</LI>
<LI>嘿！Blog快乐！</LI></UL>
      </td>
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
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b>修改文章</b></td>
    </tr>
    <tr>
      <td height="23">&nbsp;</td>
      <td><font color="#FF0000">#</font> <font color="#0000FF">通过ID直接查找文章</font>
          <input name="searchID" type="text" class="input" size="5"></td>
      <td><input type="submit" name="action" value="编辑" class="botton"></td>
      <td><input type="submit" name="action" value="删除" class="botton"></td>
    </tr>
    <?php
    while($editBlog=mysql_fetch_array($resultBlog))
	{
?>
    <tr>
      <td width="15%" height="23">&nbsp;id:[<? echo "$editBlog[id]"; ?>]</td>
<? if($editBlog[html]=="0"){ ?>
      <td width="65%"><a href="../admin/./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>"><? echo "$editBlog[title]" ?></a></td>
      <td width="10%"><a href="../admin/./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>">[编辑]</a></td>
<? }else{ ?>
      <td width="65%"><a href="../admin/./edithtmlblog.php?action=modify&id=<? echo "$editBlog[id]"?>"><? echo "$editBlog[title]" ?></a></td>
      <td width="10%"><a href="../admin/./edithtmlblog.php?action=modify&id=<? echo "$editBlog[id]"?>">[编辑]</a></td>
<? } ?>
      <td width="10%"><a href="../admin/./editblog.php?action=delete&id=<? echo "$editBlog[id]"?>" onclick="if(confirm ('你确认要删除么？')){;}else{return false;}">[删除]</a></td>
    </tr>
    <? } ?>
    <tr>
      <td colspan="4">&nbsp; </td>
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
