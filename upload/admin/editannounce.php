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
*\    本页说明: 添加&编辑&删除公告页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/editannounce.php");

checkLogin();       ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 0);  ### 检查用户权限

$aboutAuthor=splitInfo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../include/ex2.js"></script>
<script language="JavaScript" type="text/javascript" src="./javascript.php"></script>
</head>
<body class="main" dir="<?php echo $langpublic[dir]; ?>">
<form method="post" action="<? echo $PHP_SELF; ?>" name="exEdit">

<?php
    if($_POST['action'] == "addaad")
	{
	    addAnnounce($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email']);
	}
	elseif($_GET['action'] == "delete")
	{
		deleteAnnounce($_GET['id']);
	}
	elseif($_POST['action'] == "ediaad")
	{
		updateAnnounce($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['iID']);
	}
    if($_GET['action'] == "add")
	{
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr>
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[0]; ?></b></td>
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[1]; ?></td>
      <td width="85%"><input name="title" type="text" size="40" class="input">
</td>
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[2]; ?></td>
      <td width="85%"><input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input"></td>
    </tr>
    <tr>
      <td width="15%" height=98 rowspan="2" align="center"><?php echo $lang[3]; ?></td>
      <td width="85%">
	     <table border="0" align="left" cellpadding="1" cellspacing="1">
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
	</tr>
	    </table>
		</td>
    </tr>
    <tr>
      <td><table class="main">
		<tr>
           <td><?php echo $lang[43]; ?>
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
           	<select name="size" onFocus="this.selectedIndex=2" onChange="chsize(this.options[this.selectedIndex].value)" size="1">
			<option value="-2">-2</option>
			<option value="-1">-1</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected>3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option></select>
           <select name="color" onFocus="this.selectedIndex=1" onChange="chcolor(this.options[this.selectedIndex].value)" size="1">
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
</select>
<?php echo $lang[54]; ?><select name="mode" onChange="chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)">
<option value=2><?php echo $lang[40]; ?></option>
<option value=1><?php echo $lang[41]; ?></option>
<option value=0><?php echo $lang[42]; ?></option>
</select>
			</td>
    	</tr>
   </table></td>
    </tr>    
    <tr>
      <td align="center" width="15%" valign="top"><?php echo $lang[4]; ?><br>
<br>
<br><a onclick=document.exEdit.content.rows='30'; title="<?php echo $lang[5]; ?>"><?php echo $lang[6]; ?></a><br>
<br><a onclick=document.exEdit.content.rows='10'; title="<?php echo $lang[7]; ?>"><?php echo $lang[8]; ?></a></td>
      <td width="85%"><textarea name="content" cols="70" rows="10" wrap="VIRTUAL" class="input" onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);"></textarea></td>
    </tr>
   <tr>
      <td colspan="4" align="center"><a href="upload.php?action=addForm&ins=1" target="_blank"><?php echo $lang[9]; ?></a>
 <A onclick=pasteC() href="#"><?php echo $lang[50]; ?></A>
 <a href="javascript:checklength(document.exEdit);" title="<?php echo $lang[51]; ?>"><?php echo $lang[52]; ?></a></td>
    </tr>
    <tr align="center">
      <td height=30 colspan="2">
	  <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
	  <input type="hidden" name="action" value="addaad">
	  <input type="submit" value="<?php echo $lang[53]; ?>" class="botton" onClick="javascript: return adcheck();">
	  </td>
    </tr>
  </table>
  <?php
    }
	elseif($_GET[action] == "edit")
	{
		checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限
		$resultAnnounce=selectAnnounceModify();                  ### 显示文章列表
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[55]; ?></b></td>
    </tr>
    <?php
    while($editAnnounce=mysql_fetch_array($resultAnnounce))
	{
?>
    <tr>
      <td width="100" height="23" align="center">&nbsp;id:[<? echo "$editAnnounce[id]"; ?>]</td>
      <td><? echo "$editAnnounce[title]" ?></td>
      <td width="100" align="center"><a href="./editannounce.php?action=modify&id=<? echo "$editAnnounce[id]"?>"><?php echo $lang[56]; ?></a><a href="./editannounce.php?action=delete&id=<? echo "$editAnnounce[id]"?>" onclick="if(confirm ('<?php echo $lang[57]; ?>')){;}else{return false;}"><?php echo $lang[58]; ?></a></td>
    </tr>
    <? } ?>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>
  <? }
   else if($_GET[action] == "modify")
   { 
	   checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限
       selectEditAnnounce($_GET[id]);           ### 查询当前待编辑的链接信息
	   //print_r($resultAnnounce);
?>
   
  <table width="550" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="４" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr> 
      <td class="menu" colspan=2 height=25 align="center"><b><?php echo $lang[59]; ?></b></td>
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[1]; ?></td>
      <td width="85%"><input name="title" type="text" size="40" value="<? echo htmlspecialchars($resultAnnounce['title']); ?>" class="input">
</td>
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[2]; ?></td>
      <td width="85%"><input name="author" type="text" value="<? echo htmlspecialchars($aboutAuthor['0']); ?>" class="input"></td>
    </tr>
    <tr>
      <td width="15%" height=98 rowspan="2" align="center"><?php echo $lang[3]; ?></td>
      <td width="85%">
	     <table border="0" align="left" cellpadding="1" cellspacing="1">
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
	</tr>
	    </table>
		</td>
    </tr>
    <tr>
      <td><table class="menu">
		<tr>
           <td><?php echo $lang[43]; ?>
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
           	<select name="size" onFocus="this.selectedIndex=2" onChange="chsize(this.options[this.selectedIndex].value)" size="1">
			<option value="-2">-2</option>
			<option value="-1">-1</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected>3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option></select>
           <select name="color" onFocus="this.selectedIndex=1" onChange="chcolor(this.options[this.selectedIndex].value)" size="1">
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
</select>
<?php echo $lang[54]; ?><select name="mode" onChange="chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)">
<option value=2><?php echo $lang[40]; ?></option>
<option value=1><?php echo $lang[41]; ?></option>
<option value=0><?php echo $lang[42]; ?></option>
</select>			</td>
    	</tr>
   </table></td>
    </tr>    
    <tr>
      <td align="center" width="15%" valign="top"><?php echo $lang[4]; ?><br>
<br>
<br><a onclick=document.exEdit.content.rows='30'; title="<?php echo $lang[5]; ?>"><?php echo $lang[6]; ?></a><br>
<br><a onclick=document.exEdit.content.rows='10'; title="<?php echo $lang[7]; ?>"><?php echo $lang[8]; ?></a></td>
      <td width="85%"><textarea name="content" cols="70" rows="10" wrap="VIRTUAL" class="input" onSelect="javascript: storeCaret(this);" onClick="javascript: storeCaret(this);" onKeyUp="javascript: storeCaret(this);"><? echo htmlspecialchars($resultAnnounce['content']); ?></textarea></td>
    </tr>
   <tr>
      <td colspan="4" align="center"><a href="upload.php?action=addForm&ins=1" target="_blank"><?php echo $lang[9]; ?></a>
 <A onclick=pasteC() href="#"><?php echo $lang[50]; ?></A>
 <a href="javascript:checklength(document.exEdit);" title="<?php echo $lang[51]; ?>"><?php echo $lang[52]; ?></a></td>
    </tr>
    <tr align="center">
      <td height=30 colspan="2">
	  <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
	  <input type="hidden" name="iID" value="<? echo $resultAnnounce['id']; ?>">
	  <input type="hidden" name="action" value="ediaad">
	  <input type="submit" value="<?php echo $lang[60]; ?>" class="botton" onClick="javascript: return adcheck();">
	  </td>
    </tr>
  </table></td>
  </tr>
</table>
  <? } ?>
</form>
</body>
</html>
