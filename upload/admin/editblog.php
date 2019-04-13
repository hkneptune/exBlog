<?php
/*=================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
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

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/editblog.php");

checkLogin();       ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 1);  ### 检查用户权限
//$aboutAuthor=splitInfo();
/* 查询分类列表 */
selectAllSort();
selectAllWeather();
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<title>exBlog - Powered by exSoft</title>

<link href="../images/style.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../include/ex2.js"></script>
<script language="JavaScript" type="text/javascript" src="cal_images/popcalendar.js"></script>
<script language="JavaScript" type="text/javascript" src="cal_images/lw_layers.js"></script>
<script language="JavaScript" type="text/javascript" src="cal_images/lw_menu.js"></script>
<script language="JavaScript" type="text/javascript" src="./javascript.php"></script>

</head>

<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>" name="exEdit">


<?php

    if($_POST['action'] == "actaddablog")
	{
		if(empty($_POST[at_datevalue])){ $_POST[at_datevalue]=date(Y."-".m."-".d); }

		if(empty($_POST[at_hour])) { $_POST[at_hour]=date(H); }
		if(empty($_POST[at_minute])) { $_POST[at_minute]=date(i); }
		if(empty($_POST[at_second])) { $_POST[at_second]=date(s); }

		$addtime=$_POST[at_datevalue]." ".$_POST[at_hour].":".$_POST[at_minute].":".$_POST[at_second];

	    addBlog($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['esort'], $addtime, $_POST['keyword'], $_POST['summarycontent'], $_POST['weather'], $_POST['isTop'], $_POST[isHidden], $_POST[html], $_POST[nocom], $_POST[trackback]);
	}
	elseif($_POST['action'] == "actediablog")
	{
		if(empty($_POST[at_datevalue])){ $_POST[at_datevalue]="0000-00-00"; }

		if(empty($_POST[at_hour])) { $_POST[at_hour]="00"; }
		if(empty($_POST[at_minute])) { $_POST[at_minute]="00"; }
		if(empty($_POST[at_second])) { $_POST[at_second]="00"; }

		$addtime=$_POST[at_datevalue]." ".$_POST[at_hour].":".$_POST[at_minute].":".$_POST[at_second];

		updateBlog($_POST[title], $_POST[content], $_POST[author], $_POST[email], $_POST[esort], $_POST[id], $addtime, $_POST['keyword'], $_POST['summarycontent'], $_POST['weather'], $_POST['isTop'], $_POST[isHidden], $_POST[html], $_POST[nocom]);
	}
	elseif($_GET[action] == "delete" || $_POST[action] == "Delete")
	{
		if($_POST[action] == "Delete")
		{
			deleteBlog($_POST['searchID']);
		}
		else
		{
			deleteBlog($_GET['id']);
		}
	}
	if($_POST[action] == "Batch Delete")
	{
		delMore($_POST['article']);  //批量删除文章Encoded By Anthrax
	}
	if($_POST[action] == "Batch Move")
	{
		moveMore($_POST['article'], $_POST['b_sort']);  //批量移动文章
	}

$modifygo=0;
if($_POST['searchID']!="")$modifygo=1;

	if($_GET['action'] == "modify" || $_POST['action'] == "Edit" || $modifygo==1)
	{
		checkUserUid($_SESSION[exPass],1);          ### 检查用户权限

		//---- 检果是否通过search查找的 ----//
		if($_POST['action'] == "Edit" || $modifygo==1)
		{
			$BlogInfo=selectOneBlogInfo($_POST['searchID']); ### 查询当前待编辑的文章内容
			if($BlogInfo[title]=="" && $BlogInfo[content]=="")
			{
				showError("$lang[0]");
			}
		}
		else
		{
			$BlogInfo=selectOneBlogInfo($_GET['id']); ### 查询当前待编辑的文章内容
   if( fun_checkUserUid($_SESSION[exPass],0) ) {
    if( !fun_checkUserUid($_SESSION[exPass],1) ) {
	if( $_SESSION[exPass]!=$BlogInfo[author] ) {
		showError("$lang[121]");
	}
    }
}
		}
?>
<body class="main" dir="<?php echo $langpublic[dir]; ?>">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=4 height=25 align="center"><b><?php echo $lang[1]; ?></b></td>
    </tr>
    <tr>
      <td height="23" align="center"><?php echo $lang[2]; ?></td>
      <td><input name="title" type="text" size="35" value="<? echo htmlspecialchars($BlogInfo[title]); ?>" class="input">
          <select name="isTop" id="isTop">
          <option value="<? echo $BlogInfo[top]; ?>" selected><? editBlogSelTop($BlogInfo[top])?></option>
          <option value="<? echo 1-$BlogInfo[top]; ?>"><? editBlogSelTop(1-$BlogInfo[top])?></option>
      </select>
          <input type="hidden" name="html" value="0">
      </td>
      <td align="center"><?php echo $lang[3]; ?></td>
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
      <td height="23" align="center"><?php echo $lang[4]; ?></td>
      <td><input name="author" type="text" value="<? echo htmlspecialchars($BlogInfo[author]); ?>" class="input">
       <?php echo $lang[5]; ?> <select name="isHidden" id="isHidden">
          <option value="<? echo $BlogInfo[hidden]; ?>" selected><? editBlogSelHidden($BlogInfo[hidden])?></option>
          <option value="<? echo 1-$BlogInfo[hidden]; ?>"><? editBlogSelHidden(1-$BlogInfo[hidden])?></option>
      </select> 
	</td>

      <td align="center"><?php echo $lang[6]; ?></td>
      <td><select name="esort" class="botton">
          <? 
	while($row=mysql_fetch_array($resultSort))
	{ 
?>
          <option value="<? echo $row['id']; ?>" <? editBlogSelSort($row['id'],$BlogInfo['sort']); ?>> <? echo $row['cnName']; ?></option>
          <? } ?>
      </select>
</td>
    </tr>
    <tr> 
      <td align="center" valign="top"><?php echo $lang[7]; ?><br><br>
<span style="cursor:hand;width:100%;" onclick="showIntro('moretools');showIntro('moretools_sum');showIntro('moretools_exface');showIntro('moretools_keyword');showIntro('moretools_date');" title="<?php echo $lang[8]; ?>">
<?php echo $lang[9]; ?></span>
</td>
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
                  <td><a href="javascript:exEditadd(':ex2_45:')"><img src="../images/ex_pose/ex2_45.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_46:')"><img src="../images/ex_pose/ex2_46.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_47:')"><img src="../images/ex_pose/ex2_47.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_48:')"><img src="../images/ex_pose/ex2_48.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_49:')"><img src="../images/ex_pose/ex2_49.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_50:')"><img src="../images/ex_pose/ex2_50.gif" width="16" height="16" border="0"></a></td>
</tr><tr style="display:none;" id="moretools_exface">
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
      <td colspan="6" align="right" class="main">
<?php echo $lang[120]; ?>
<select name="nocom" id="nocom">
          <option value="<? echo $BlogInfo[nocom]; ?>" selected><? editBlogSelNocom($BlogInfo[nocom])?></option>
          <option value="<? echo 1-$BlogInfo[nocom]; ?>"><? editBlogSelNocom(1-$BlogInfo[nocom])?></option>
</select>
      </td>
          </tr>
          <tr> 
            <td><img  onclick=code()  src="../images/ex_code/ex_code.gif" alt="<?php echo $lang[10]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="<?php echo $lang[11]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=bold()   src="../images/ex_code/ex_bold.gif" alt="<?php echo $lang[12]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="<?php echo $lang[13]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=strike()  src="../images/ex_code/ex_strike.gif" alt="<?php echo $lang[14]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=sup()  src="../images/ex_code/ex_sup.gif" alt="<?php echo $lang[15]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=exsub()  src="../images/ex_code/ex_sub.gif" alt="<?php echo $lang[16]; ?>" width="23" height="22" border="0"></td>
       		<td><img  onclick=list()  src="../images/ex_code/ex_list.gif" alt="<?php echo $lang[17]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=left()  src="../images/ex_code/ex_left.gif" alt="<?php echo $lang[18]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=center()  src="../images/ex_code/ex_center.gif" alt="<?php echo $lang[19]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=right()  src="../images/ex_code/ex_right.gif" alt="<?php echo $lang[20]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=justify()  src="../images/ex_code/ex_justify.gif" alt="<?php echo $lang[21]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=underline()  src="../images/ex_code/ex_underline.gif" alt="<?php echo $lang[22]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=move()  src="../images/ex_code/ex_move.gif" alt="<?php echo $lang[23]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=exblur()  src="../images/ex_code/ex_blur.gif" alt="<?php echo $lang[24]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=fliph()  src="../images/ex_code/ex_fliph.gif" alt="<?php echo $lang[25]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=flipv()  src="../images/ex_code/ex_flipv.gif" alt="<?php echo $lang[26]; ?>" width="23" height="22" border="0"></td>
          </tr>
          <tr>
            <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="<?php echo $lang[27]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=htmlcode()  src="../images/ex_code/ex_htmlcode.gif" alt="<?php echo $lang[28]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="<?php echo $lang[29]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="<?php echo $lang[30]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="<?php echo $lang[31]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="<?php echo $lang[32]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=mp3()  src="../images/ex_code/ex_mp3.gif" alt="<?php echo $lang[33]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="<?php echo $lang[34]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=ra()  src="../images/ex_code/ex_ra.gif" alt="<?php echo $lang[35]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=wmv()  src="../images/ex_code/ex_wmv.gif" alt="<?php echo $lang[36]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=wma()  src="../images/ex_code/ex_wma.gif" alt="<?php echo $lang[37]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=mov()  src="../images/ex_code/ex_mov.gif" alt="<?php echo $lang[38]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=exobject()  src="../images/ex_code/ex_object.gif" alt="<?php echo $lang[39]; ?>" width="23" height="22" border="0"></td>
      <td colspan="4" align="right"><select name="mode" onChange="chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)">
<option value=2><?php echo $lang[40]; ?></option>
<option value=1><?php echo $lang[41]; ?></option>
<option value=0><?php echo $lang[42]; ?></option>
</select></td>
	</tr>
</table>
     <table class="main">
     <tr>
           <td width="190"><?php echo $lang[43]; ?>
<select onChange=chfont(this.options[this.selectedIndex].value) name=font>
<option value="<?php echo $lang[44]; ?>" selected><?php echo $lang[44]; ?></option>
<option value="<?php echo $lang[46]; ?>"><?php echo $lang[45]; ?></option>
<option value="<?php echo $lang[47]; ?>"><?php echo $lang[47]; ?></option>
<option value="<?php echo $lang[48]; ?>"><?php echo $lang[48]; ?></option>
<option value="<?php echo $lang[49]; ?>"><?php echo $lang[49]; ?></option>
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
<option value="1" selected>1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option></select>
         </td>
         <td width="85">
<SELECT onchange=chcolor(this.options[this.selectedIndex].value) name=color> 
<option style=background-color:#000000;color:#000000 value=#000000 selected>#000000</option>
<option style=background-color:#FFFFFF;color:#FFFFFF value=#FFFFFF>#FFFFFF</option>
<option style=background-color:#FF0000;color:#FF0000 value=#FF0000>#FF0000</option>
<option style=background-color:#FFFF00;color:#FFFF00 value=#FFFF00>#FFFF00</option>
<option style=background-color:#00FF00;color:#00FF00 value=#00FF00>#00FF00</option>
<option style=background-color:#00FFFF;color:#00FFFF value=#00FFFF>#00FFFF</option>
<option style=background-color:#0000FF;color:#0000FF value=#0000FF>#0000FF</option>
<option style=background-color:#FF00FF;color:#FF00FF value=#FF00FF>#FF00FF</option>
<option style=background-color:#808080;color:#808080 value=#808080>#808080</option>
<option style=background-color:#C0C0C0;color:#C0C0C0 value=#C0C0C0>#C0C0C0</option>
<option style=background-color:#800000;color:#800000 value=#800000>#800000</option>
<option style=background-color:#808000;color:#808000 value=#808000>#808000</option>
<option style=background-color:#008000;color:#008000 value=#008000>#008000</option>
<option style=background-color:#008080;color:#008080 value=#008080>#008080</option>
<option style=background-color:#000080;color:#000080 value=#000080>#000080</option>
<option style=background-color:#800080;color:#800080 value=#800080>#800080</option>
</SELECT>
       </td>
<td>
                <?php echo $lang[50]; ?><select name="fontsign" onChange="exEditadd(this.options[this.selectedIndex].value)" >
                          <option value="<?php echo $lang[51]; ?>"><?php echo $lang[52]; ?></option>
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
                          <option value="☆">☆</option>
                          <option value="★">★</option>
                          <option value="◆">◆</option>
                          <option value="◇">◇</option>
                          <option value="○">○</option>
                          <option value="◎">◎</option>
                          <option value="●">●</option>
                          <option value="→">→</option>
                          <option value="←">←</option>
                          <option value="↑">↑</option>
                          <option value="↓">↓</option>
                          <option value="♀">♀</option>
                          <option value="♂">♂</option>
                          <option value="	">Tab</option>
                        </select>
</td>
</tr>
   </table>

      </td>
    </tr>
   <tr style="display:none;" id="moretools_sum">
      <td align="center" valign="top"><?php echo $lang[53]; ?><br></td>
      <td colspan="4"><textarea name="summarycontent" cols="70" rows="4" wrap="VIRTUAL" class="input"><? echo htmlspecialchars($BlogInfo[summarycontent]); ?></textarea></td>
    </tr>
    <tr>
      <td align="center" valign="top"><?php echo $lang[54]; ?><br>
<br>
<br><a style="cursor:hand;width:100%;" onclick="document.exEdit.content.rows+=20;" title="<?php echo $lang[55]; ?>"><?php echo $lang[56]; ?></a><br>
<br><a style="cursor:hand;width:100%;" onclick="if(document.exEdit.content.rows>=20)document.exEdit.content.rows-=20;else return false" title="<?php echo $lang[57]; ?>"><?php echo $lang[58]; ?></a></td>
      <td colspan="4"><textarea name="content" cols="70" rows="10" wrap="VIRTUAL" class="input"  onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);"><? echo htmlspecialchars($BlogInfo[content]); ?></textarea></td>
    </tr>

   <tr style="display:none;" id="moretools_keyword">
      <td align="center" valign="top"><?php echo $lang[59]; ?><br></td>
      <td colspan="4"><input name="keyword" type="text" size="70" class="input" value="<? echo htmlspecialchars($BlogInfo[keyword]); ?>">
	<br><font color="red">* <?php echo $lang[60]; ?></font>
      </td>
    </tr>
   <tr style="display:none;" id="moretools_date">
      <td align="center" valign="top"><?php echo $lang[61]; ?><br></td>
      <td colspan="4">
<?php
	$at_datevalue=substr($BlogInfo[addtime],0,10);
	$at_month=substr($BlogInfo[addtime],5,2);
	$at_day=substr($BlogInfo[addtime],8,2);
	$at_year=substr($BlogInfo[addtime],0,4);
	$at_time=substr($BlogInfo[addtime],11,8);
	$at_hour=substr($BlogInfo[addtime],11,2);
	$at_minute=substr($BlogInfo[addtime],14,2);
	$at_second=substr($BlogInfo[addtime],17,2);
?>
		<?php echo $lang[62]; ?>
	<input maxLength="10" size="10" value="<?php echo $at_datevalue; ?>" name="at_datevalue" />
        <SCRIPT language=javascript>
	<!--
	if (!document.layers) {
	document.write("<input class=botton type=button onclick='popUpCalendar(this, exEdit.at_datevalue, \"yyyy-mm-dd\")' value='<?php echo $lang[63]; ?>'>")
	}
	//-->
	</SCRIPT>
		<?php echo $lang[64]; ?><input type="text" name="at_hour" value="<?php echo $at_hour; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="<?php echo $lang[67]; ?>00">
		<?php echo $lang[65]; ?><input type="text" name="at_minute" value="<?php echo $at_minute; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="<?php echo $lang[67]; ?>00">
		<?php echo $lang[66]; ?><input type="text" name="at_second" value="<?php echo $at_second; ?>" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="<?php echo $lang[67]; ?>00">
      </td>
    </tr>
   <tr style="display:none;" id="moretools">
      <td align="center" valign="top"></td>
      <td colspan="3">
 <a href="upload.php?action=addForm&ins=1" target="_blank"><?php echo $lang[68]; ?></a>
 <a onclick=pasteC() href="#"><?php echo $lang[69]; ?></a>
 <a href="javascript:checklength(document.exEdit);" title="<?php echo $lang[70]; ?>"><?php echo $lang[71]; ?></a>
			</td>
    	</tr>
    <tr align="center">
      <td height="30" colspan="6"><input type="hidden" name="id" value="<? analysisPostBlogID($_POST['searchID'], $_GET['id']); ?>">
          <input type="hidden" name="email" value="<? echo htmlspecialchars($BlogInfo[email]); ?>">
                <input type="hidden" name="action" value="actediablog">
          <input type="submit" value="<?php echo $lang[72]; ?>" class="botton" onClick="javascript: return check(0);">
      </td>
    </tr>
 </table>
  <?
	}
    elseif($_GET['action'] == "add" || $_POST['act'] == "add")
	{
?>
<body class="main" onload="startclock();" dir="<?php echo $langpublic[dir]; ?>">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=4 height=25 align="center"><b><?php echo $lang[73]; ?></b></td>
    </tr>
    <tr> 
      <td height="23" align="center"><?php echo $lang[2]; ?></td>
      <td height="23"> <input name="title" type="text" size="35" class="input"> <select name="isTop" id="isTop">
          <option value="0" selected><?php echo $lang[74]; ?></option>
          <option value="1"><?php echo $lang[75]; ?></option>
        </select>
          <input type="hidden" name="html" value="0">
      </td>
      <td align="center"><?php echo $lang[3]; ?></td>
      <td><select name="weather">
          <option value="null"><?php echo $lang[76]; ?></option>
          <option value="sunny"><?php echo $lang[77]; ?></option>
		  <option value="cloudysky"><?php echo $lang[78]; ?></option>
          <option value="cloudy"><?php echo $lang[79]; ?></option>
          <option value="night"><?php echo $lang[80]; ?></option>
          <option value="rain"><?php echo $lang[81]; ?></option>
          <option value="snow"><?php echo $lang[82]; ?></option>
        </select></td>
    </tr>
    <tr> 
      <td height="23" align="center"><?php echo $lang[4]; ?></td> <td height="23"><input name="author" type="text" value="<? echo $_SESSION['exPass']; ?>" class="input">
 　<?php echo $lang[5]; ?>　<select name="isHidden" id="isHidden">
          <option value="0" selected><?php echo $lang[83]; ?></option>
          <option value="1"><?php echo $lang[84]; ?></option>
</select>　</td>
      <td align="center"><?php echo $lang[6]; ?></td>
<td><select name="esort">
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <option value="<? echo $row['id']; ?>" <?php editBlogSelSort("default", $row['enName']); ?>><? echo $row['cnName']; ?></option>
          <? } ?>
        </select>
</td>
    </tr>
    <tr> 
      <td align="center" valign="top"><?php echo $lang[7]; ?><br><br>
<span style="cursor:hand;width:100%;" onclick="showIntro('moretools');showIntro('moretools_sum');showIntro('moretools_exface');showIntro('moretools_keyword');showIntro('moretools_date');showIntro('moretools_trackback');" title="<?php echo $lang[8]; ?>"><?php echo $lang[9]; ?></span>
</td>
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
                  <td><a href="javascript:exEditadd(':ex2_45:')"><img src="../images/ex_pose/ex2_45.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_46:')"><img src="../images/ex_pose/ex2_46.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_47:')"><img src="../images/ex_pose/ex2_47.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_48:')"><img src="../images/ex_pose/ex2_48.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_49:')"><img src="../images/ex_pose/ex2_49.gif" width="16" height="16" border="0"></a></td>
                  <td><a href="javascript:exEditadd(':ex2_50:')"><img src="../images/ex_pose/ex2_50.gif" width="16" height="16" border="0"></a></td>
</tr><tr style="display:none;" id="moretools_exface">
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
      <td colspan="6" align="right" class="main">
<?php echo $lang[120]; ?>
<select name="nocom" id="nocom">
          <option value="0" selected><?php echo $lang[84]; ?></option>
          <option value="1"><?php echo $lang[83]; ?></option>
</select>
      </td>
          </tr>
          <tr>
            <td><img  onclick=code()  src="../images/ex_code/ex_code.gif" alt="<?php echo $lang[10]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="<?php echo $lang[11]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=bold()   src="../images/ex_code/ex_bold.gif" alt="<?php echo $lang[12]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="<?php echo $lang[13]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=strike()  src="../images/ex_code/ex_strike.gif" alt="<?php echo $lang[14]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=sup()  src="../images/ex_code/ex_sup.gif" alt="<?php echo $lang[15]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=exsub()  src="../images/ex_code/ex_sub.gif" alt="<?php echo $lang[16]; ?>" width="23" height="22" border="0"></td>
       		<td><img  onclick=list()  src="../images/ex_code/ex_list.gif" alt="<?php echo $lang[17]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=left()  src="../images/ex_code/ex_left.gif" alt="<?php echo $lang[18]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=center()  src="../images/ex_code/ex_center.gif" alt="<?php echo $lang[19]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=right()  src="../images/ex_code/ex_right.gif" alt="<?php echo $lang[20]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=justify()  src="../images/ex_code/ex_justify.gif" alt="<?php echo $lang[21]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=underline()  src="../images/ex_code/ex_underline.gif" alt="<?php echo $lang[22]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=move()  src="../images/ex_code/ex_move.gif" alt="<?php echo $lang[23]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=exblur()  src="../images/ex_code/ex_blur.gif" alt="<?php echo $lang[24]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=fliph()  src="../images/ex_code/ex_fliph.gif" alt="<?php echo $lang[25]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=flipv()  src="../images/ex_code/ex_flipv.gif" alt="<?php echo $lang[26]; ?>" width="23" height="22" border="0"></td>
          </tr>
          <tr>
            <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="<?php echo $lang[27]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=htmlcode()  src="../images/ex_code/ex_htmlcode.gif" alt="<?php echo $lang[28]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="<?php echo $lang[29]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="<?php echo $lang[30]; ?>" width="23" height="22" border="0"></td>
            <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="<?php echo $lang[31]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="<?php echo $lang[32]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=mp3()  src="../images/ex_code/ex_mp3.gif" alt="<?php echo $lang[33]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="<?php echo $lang[34]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=ra()  src="../images/ex_code/ex_ra.gif" alt="<?php echo $lang[35]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=wmv()  src="../images/ex_code/ex_wmv.gif" alt="<?php echo $lang[36]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=wma()  src="../images/ex_code/ex_wma.gif" alt="<?php echo $lang[37]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=mov()  src="../images/ex_code/ex_mov.gif" alt="<?php echo $lang[38]; ?>" width="23" height="22" border="0"></td>
			<td><img  onclick=exobject()  src="../images/ex_code/ex_object.gif" alt="<?php echo $lang[39]; ?>" width="23" height="22" border="0"></td>
      <td colspan="4" align="right"><select name="mode" onChange="chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)">
<option value=2><?php echo $lang[40]; ?></option>
<option value=1><?php echo $lang[41]; ?></option>
<option value=0><?php echo $lang[42]; ?></option>
</select></td>
	</tr>
</table>
     <table class="main">
     <tr>
           <td width="190"><?php echo $lang[43]; ?>
<select onChange=chfont(this.options[this.selectedIndex].value) name=font>
<option value="<?php echo $lang[44]; ?>" selected><?php echo $lang[44]; ?></option>
<option value="<?php echo $lang[46]; ?>"><?php echo $lang[45]; ?></option>
<option value="<?php echo $lang[47]; ?>"><?php echo $lang[47]; ?></option>
<option value="<?php echo $lang[48]; ?>"><?php echo $lang[48]; ?></option>
<option value="<?php echo $lang[49]; ?>"><?php echo $lang[49]; ?></option>
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
<option value="1" selected>1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option></select>
         </td>
         <td width="85">
<SELECT onchange=chcolor(this.options[this.selectedIndex].value) name=color>
<option style=background-color:#000000;color:#000000 value=#000000 selected>#000000</option>
<option style=background-color:#FFFFFF;color:#FFFFFF value=#FFFFFF>#FFFFFF</option>
<option style=background-color:#FF0000;color:#FF0000 value=#FF0000>#FF0000</option>
<option style=background-color:#FFFF00;color:#FFFF00 value=#FFFF00>#FFFF00</option>
<option style=background-color:#00FF00;color:#00FF00 value=#00FF00>#00FF00</option>
<option style=background-color:#00FFFF;color:#00FFFF value=#00FFFF>#00FFFF</option>
<option style=background-color:#0000FF;color:#0000FF value=#0000FF>#0000FF</option>
<option style=background-color:#FF00FF;color:#FF00FF value=#FF00FF>#FF00FF</option>
<option style=background-color:#808080;color:#808080 value=#808080>#808080</option>
<option style=background-color:#C0C0C0;color:#C0C0C0 value=#C0C0C0>#C0C0C0</option>
<option style=background-color:#800000;color:#800000 value=#800000>#800000</option>
<option style=background-color:#808000;color:#808000 value=#808000>#808000</option>
<option style=background-color:#008000;color:#008000 value=#008000>#008000</option>
<option style=background-color:#008080;color:#008080 value=#008080>#008080</option>
<option style=background-color:#000080;color:#000080 value=#000080>#000080</option>
<option style=background-color:#800080;color:#800080 value=#800080>#800080</option>
</SELECT>
       </td>
<td>
                <?php echo $lang[50]; ?><select name="fontsign" onChange="exEditadd(this.options[this.selectedIndex].value)" >
                          <option value="<?php echo $lang[51]; ?>"><?php echo $lang[52]; ?></option>
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
                          <option value="☆">☆</option>
                          <option value="★">★</option>
                          <option value="◆">◆</option>
                          <option value="◇">◇</option>
                          <option value="○">○</option>
                          <option value="◎">◎</option>
                          <option value="●">●</option>
                          <option value="→">→</option>
                          <option value="←">←</option>
                          <option value="↑">↑</option>
                          <option value="↓">↓</option>
                          <option value="♀">♀</option>
                          <option value="♂">♂</option>
                          <option value="	">Tab</option>
                        </select>
</td>
</tr>
   </table>


      </td>
    </tr>
   <tr style="display:none;" id="moretools_sum">
      <td align="center" valign="top"><?php echo $lang[53]; ?><br></td>
      <td colspan="3"><textarea name="summarycontent" cols="70" rows="4" wrap="VIRTUAL" class="input"></textarea></td>
    </tr>
   <tr>
      <td align="center" valign="top"><?php echo $lang[54]; ?><br>
<br>
<br><a style="cursor:hand;width:100%;" onclick="document.exEdit.content.rows+=20;" title="<?php echo $lang[55]; ?>"><?php echo $lang[56]; ?></a><br>
<br><a style="cursor:hand;width:100%;" onclick="if(document.exEdit.content.rows>=20)document.exEdit.content.rows-=20;else return false" title="<?php echo $lang[57]; ?>"><?php echo $lang[58]; ?></a></td>
      <td colspan="3"><textarea name="content" cols="70" rows="10" wrap="VIRTUAL" class="input"  onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);"></textarea></td>
    </tr>
   <tr style="display:none;" id="moretools_trackback">
      <td align="center" valign="top"><?php echo $lang[85]; ?><br></td>
      <td colspan="3"><input name="trackback" type="text" size="70" class="input" value="">
	<br><font color="red">* <?php echo $lang[86]; ?></font>
      </td>
    </tr>
   <tr style="display:none;" id="moretools_keyword">
      <td align="center" valign="top"><?php echo $lang[59]; ?><br></td>
      <td colspan="3"><input name="keyword" type="text" size="70" class="input">
	<br><font color="red">* <?php echo $lang[60]; ?></font>
      </td>
    </tr>
   <tr style="display:none;" id="moretools_date">
      <td align="center" valign="top"><?php echo $lang[61]; ?><br></td>
      <td colspan="3">
	<input maxLength="10" size="10" name="at_datevalue" />
        <SCRIPT language=javascript>
	<!--
	if (!document.layers) {
	document.write("<input class=botton type=button onclick='popUpCalendar(this, exEdit.at_datevalue, \"yyyy-mm-dd\")' value='<?php echo $lang[63]; ?>'>")
	}
	//-->
	</SCRIPT>
		<?php echo $lang[64]; ?><input type="text" name="at_hour" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="<?php echo $lang[67]; ?>00">
		<?php echo $lang[65]; ?><input type="text" name="at_minute" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="<?php echo $lang[67]; ?>00">
		<?php echo $lang[66]; ?><input type="text" name="at_second" size="3" maxlength="2" style="ime-mode:disabled" onkeydown="onlyNum();" title="<?php echo $lang[67]; ?>00">
	<br><font color="red">* <?php echo $lang[87]; ?>　<br /><?php echo $lang[88]; ?> <?php echo date(Y."-".m."-".d." ".H.":".i.":".s); ?><br /><?php echo $lang[89]; ?> <span id="liveclock"></span></font>
      </td>
    </tr>
   <tr style="display:none;" id="moretools">
      <td align="center" valign="top"></td>
      <td colspan="3">
  <a href="upload.php?action=addForm&ins=1" target="_blank"><?php echo $lang[68]; ?></a>
　<a onclick="pasteC()" href="#"><?php echo $lang[69]; ?></a>
　<a href="javascript:checklength(document.exEdit);" title="<?php echo $lang[70]; ?>"><?php echo $lang[71]; ?></a>
			</td>
    	</tr>
    <tr align="center"> 
      <td height="30" colspan="4"> <input type="hidden" name="email" value="<? echo $_SESSION['exUseremail']; ?>">
      <input type="hidden" name="action" value="actaddablog">
        <input type="submit" value="<?php echo $lang[90]; ?>" class="botton" onClick="javascript: return check(1);"></td>
    </tr>
  </table>
  </form>

  <?php
    }
	elseif($_GET['action'] == "list" || $_POST['action'] == "List" )
	{
		checkUserUid($_SESSION[exPass], 1);     ### 检查用户权限
	$show_more=1;
if( fun_checkUserUid($_SESSION[exPass],0) ) {
    if( !fun_checkUserUid($_SESSION[exPass],1) ) {
	$s_author=$_SESSION[exPass];
	$show_more=0;
    }
}
$s_addtime = $_POST['s_addtime'];
$s_sort = $_POST['s_sort'];
if ($s_sort=="") $s_sort="NULL";
$s_title = $_POST['s_title'];
$s_content = $_POST['s_content'];
$s_px = $_POST['s_px'];
$s_sc = $_POST['s_sc'];
		selectBlogModify($s_addtime,$s_sort,$s_title,$s_content,$s_px,$s_sc,$s_author);                  ### 显示文章列表
		cutBlogPage();                       ### 分页
?>
<body class="main">
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan="5" height=25 align="center"><b><?php echo $lang[91]; ?></b></td>
    </tr>
    <tr>
      <td height="23">&nbsp;</td>
      <td colspan="2"><font color="#FF0000">#</font> <font color="#0000FF"><?php echo $lang[92]; ?></font>
          <input name="searchID" type="text" class="input" size="5" onKeyDown="if(event.keyCode==13)event.returnValue=false"></td>
      <td><input type="submit" name="action" value="Edit" class="botton"></td>
      <td><input type="submit" name="action" value="Delete" class="botton"></td>
    </tr>
    <?php
    while($editBlog=mysql_fetch_array($resultBlog))
	{
?>
    <tr>
      <td width="6%" height="23">id:[<? echo "$editBlog[id]"; ?>]</td>
      <td width="3%"><div align="right">
        <input type="checkbox" name="article[]" value="<? echo "$editBlog[id]"?>">
      </div></td>
      <? if($editBlog[html]=="0"){ ?>
      <td width="36%"><a href="../admin/./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>" title="<?php echo $lang[93]; ?>"><? echo "$editBlog[title]" ?></a></td>
      <td width="5%"><a href="../admin/./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>"><?php echo $lang[94]; ?></a></td>
<? }else{ ?>
      <td width="65%"><a href="../admin/./edithtmlblog.php?action=modify&id=<? echo "$editBlog[id]"?>" title="<?php echo $lang[93]; ?>"><? echo "$editBlog[title]" ?></a></td>
      <td width="10%"><a href="../admin/./edithtmlblog.php?action=modify&id=<? echo "$editBlog[id]"?>"><?php echo $lang[94]; ?></a></td>
<? } ?>
      <td width="10%"><a href="../admin/./editblog.php?action=delete&id=<? echo "$editBlog[id]"?>" onclick="if(confirm ('<?php echo $lang[95]; ?>')){;}else{return false;}"><?php echo $lang[96]; ?></a></td>
    </tr>
    <? } ?>
    <tr>
      <td colspan="5">
<div align="right">
<?php echo $lang[116]; ?>
<select name="b_sort" class="botton">
          <option value="NULL" selected><?php echo $lang[117]; ?></option>
          <? $i=0; ?>
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <? $b_sortid[$i]=$row[id]; ?>
          <? $b_sortcnName[$i]=$row[cnName]; ?>
          <option value="<? echo $b_sortid[$i]; ?>"><? echo $b_sortcnName[$i]; ?></option>
          <? $i++; ?>
          <? } ?>
</select>
<input type="submit" name="action" value="Batch Move" class="botton" onclick="if(confirm ('<?php echo $lang[118]; ?>')){;}else{return false;}">
<?php echo $lang[119]; ?>
<input type="submit" name="action" value="Batch Delete" class="botton" onclick="if(confirm ('<?php echo $lang[95]; ?>')){;}else{return false;}">
</div>
	</td>
      
	</tr>
    <tr>
      <td colspan="5">

  <? 
	 $show_pages=cutBlogPage();
		  //显示最终结果
	echo"<br>$lang[97]<br>$show_pages<br>";
	 echo $display;

	?>
</td>
    </tr>
<?php

if( $show_more==1 ) {

?>


    <tr>
      <td colspan="5">
<hr>
<b><?php echo $lang[98]; ?></b><br />
<?php echo $lang[99]; ?> 
<?php echo $lang[100]; ?>
<input name="s_addtime" type="text" class="input" title="<?php echo $lang[101]; ?>" size="5">
<?php echo $lang[102]; ?>
<select name="s_sort" class="botton">
          <option value="NULL" selected><?php echo $lang[103]; ?></option>
          <? for($a=0; $a<$i; $a++) { ?>
          <option value="<? echo $b_sortid[$a]; ?>"><? echo $b_sortcnName[$a]; ?></option>
          <? } ?>
</select>
<?php echo $lang[104]; ?>
<input name="s_title" type="text" class="input" title="<?php echo $lang[105]; ?>" size="5">
<?php echo $lang[106]; ?>
<input name="s_content" type="text" class="input" title="<?php echo $lang[107]; ?>" size="5">
<!--
<br />
<?php echo $lang[108]; ?>
<select name="s_px" class="botton">
          <option value="id" selected><?php echo $lang[109]; ?></option>
          <option value="id,addtime,title"><?php echo $lang[110]; ?></option>
          <option value="title,id,addtime"><?php echo $lang[111]; ?></option>
          <option value="addtime,title,id"><?php echo $lang[112]; ?></option>
</select>
-->
<select name="s_sc" class="botton">
          <option value="DESC" selected><?php echo $lang[113]; ?></option>
          <option value="ASC"><?php echo $lang[114]; ?></option>
</select>
<?php echo $lang[115]; ?>

<input type="submit" name="action" value="List" class="botton">

  <?php 
    }
	?>
	</td>
    </tr>
<?php

}

?>
  </table>
</form>
</body>
</html>


