<?php
/*============================================================================
*\    exBlogMix Ver: 1.2.0  exBlogMix 网络日志(PHP+MYSQL) 1.2.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
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

checkLogin();       ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限

$aboutAuthor=splitInfo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="elliott,hunter">
<meta name="keywords" content="elliott,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by elliott">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>{title}</title>
<script src=addubb.js></script>
<link href="../images/admin.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<form method="post" action="<? echo $PHP_SELF; ?>" name="exEdit">
<script language="JavaScript">
    function exEdit(iEdit) {
    document.exEdit.content.value += iEdit+" ";
    document.exEdit.content.focus();
}
</script>
<?php
    if($_POST['action'] == "添加公告")
	{
	    addAnnounce($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email']);
	}
	elseif($_GET['action'] == "delete")
	{
		deleteAnnounce($_GET['id']);
	}
	elseif($_POST['action'] == "修改公告")
	{
		UpdateAnnounce($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['iID']);
	}
    if($_GET['action'] == "add")
	{
?>
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr>
      <td class="a1" colspan=2 height=25 align="center"><b>添加公告</b></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告标题:</td>
      <td width="80%"><input name="title" type="text" size="40" class="input">
</td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告作者:</td>
      <td width="80%"><input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input"></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">辅助功能:</td>
      <td width="80%">
	     <table border="0" align="left" cellpadding="1" cellspacing="1">
            <tr>
              <td><a href="javascript:exEdit(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" width="15" height="15" border="0"></a></td>
              <td><a href="javascript:exEdit(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" width="15" height="15" border="0"></a></td>
              <td colspan="2">　</td>
            </tr>
            <tr>
              <td height="19" colspan="10">　</td>
            </tr>
            <tr>
                  <td><img  onclick=showcode()  src="../images/ex_code/ex_code.gif" alt="插入代码" width="23" height="22" border="0"></td>
                  <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="插入引用" width="23" height="22" border="0"></td>
                  <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="插入框架" width="23" height="22" border="0"></td>
                  <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="插入email" width="23" height="22" border="0"></td>
                  <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="插入超级链接" width="23" height="22" border="0"></td>
                  <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="插入图像链接" width="23" height="22" border="0"></td>
                  <td><img  onclick=bold()  src="../images/ex_code/ex_bold.gif" alt="插入粗体字" width="23" height="22" border="0"></td>
                  <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="插入斜体字" width="23" height="22" border="0"></td>
                  <td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="插入Flash文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=ra()  src="../images/ex_code/ex_ra.gif" alt="插入ram音频文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="插入rm视频文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=wmv()  src="../images/ex_code/ex_wmv.gif" alt="插入wmv视频文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=wma()  src="../images/ex_code/ex_wma.gif" alt="插入wma音频文件" width="23" height="22" border="0"></td>
            </tr>
        </table></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告内容:</td>
      <td width="80%"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"></textarea></td>
    </tr>
    <tr align="center" class=a4>
      <td height=30 colspan="2">
	  <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
	  <input type="submit" name="action" value="添加公告" class="botton">
	  </td>
    </tr>
  </table>
  <?php
    }
	elseif($_GET[action] == "edit")
	{
		checkUserUid($_SESSION[exPass]);     ### 检查用户权限
		$resultAnnounce=selectAnnounceModify();                  ### 显示文章列表
?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr align="center">
      <td height="25" colspan="3" class="a1"><b>编辑公告</b></td>
    </tr>
    <?php
    while($editAnnounce=mysql_fetch_array($resultAnnounce))
	{
?>
    <tr class="a4">
      <td width="100" height="23" align="center">&nbsp;id:[<? echo "$editAnnounce[id]"; ?>]</td>
      <td><? echo "$editAnnounce[title]" ?></td>
      <td width="100" align="center"><a href="./editannounce.php?action=modify&id=<? echo "$editAnnounce[id]"?>">[编辑]</a><a href="./editannounce.php?action=delete&id=<? echo "$editAnnounce[id]"?>">[删除]</a></td>
    </tr>
    <? } ?>
    <tr class="a4">
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>
  <? }
   else if($_GET[action] == "modify")
   { 
	   checkUserUid($_SESSION[exPass]);     ### 检查用户权限
       selectEditAnnounce($_GET[id]);           ### 查询当前待编辑的链接信息
	   //print_r($resultAnnounce);
?>
   
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr>
      <td class="a1" colspan=2 height=25 align="center"><b>编辑公告</b></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告标题:</td>
      <td width="80%"><input name="title" type="text" class="input" value="<? echo $resultAnnounce['title']; ?>" size="40">
      </td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告作者:</td>
      <td width="80%"><input name="author" type="text" value="<? echo $resultAnnounce['author']; ?>" class="input"></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">辅助功能:</td>
      <td width="80%">
        <table border="0" align="left" cellpadding="1" cellspacing="1">
          <tr>
            <td><a href="javascript:exEdit(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" width="15" height="15" border="0"></a></td>
            <td colspan="2">　</td>
          </tr>
          <tr>
            <td height="19" colspan="10">　</td>
          </tr>
          <tr>
                  <td><img  onclick=showcode()  src="../images/ex_code/ex_code.gif" alt="插入代码" width="23" height="22" border="0"></td>
                  <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="插入引用" width="23" height="22" border="0"></td>
                  <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="插入框架" width="23" height="22" border="0"></td>
                  <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="插入email" width="23" height="22" border="0"></td>
                  <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="插入超级链接" width="23" height="22" border="0"></td>
                  <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="插入图像链接" width="23" height="22" border="0"></td>
                  <td><img  onclick=bold()  src="../images/ex_code/ex_bold.gif" alt="插入粗体字" width="23" height="22" border="0"></td>
                  <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="插入斜体字" width="23" height="22" border="0"></td>
                  <td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="插入Flash文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=ra()  src="../images/ex_code/ex_ra.gif" alt="插入ram音频文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="插入rm视频文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=wmv()  src="../images/ex_code/ex_wmv.gif" alt="插入wmv视频文件" width="23" height="22" border="0"></td>
                  <td><img  onclick=wma()  src="../images/ex_code/ex_wma.gif" alt="插入wma音频文件" width="23" height="22" border="0"></td>
          </tr>
      </table></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告内容:</td>
      <td width="80%"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"><? echo $resultAnnounce['content']; ?></textarea></td>
    </tr>
    <tr align="center" class=a4>
      <td height=30 colspan="2">
	  <input type="hidden" name="iID" value="<? echo $_GET['id']; ?>">
        <input type="hidden" name="email" value="<? echo $resultAnnounce['email']; ?>">
        <input type="submit" name="action" value="修改公告" class="botton">
      </td>
    </tr>
  </table>
  <? } ?>
</form>
</body>
</html>
