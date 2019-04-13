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
<form method="post" action="<? echo $PHP_SELF; ?>" name="exEdit" onsubmit="return vertify();">
<script language="JavaScript">
    function exEdit(iEdit) {
    document.exEdit.content.value += iEdit+" ";
    document.exEdit.content.focus();
}
</script>
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
</script>
<?
    if($_POST['action'] == "添加文章")
	{
	    addBlog($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['esort'], $_POST['weather'], $_POST['isTop']);
	}
	elseif($_POST['action'] == "编辑文章")
	{
		updateBlog($_POST[title], $_POST[content], $_POST[author], $_POST[email], $_POST[esort], $_POST[id], $_POST['weather'], $_POST['isTop']);
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
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr align="center"> 
      <td height="25" colspan="4" class="a1"><b>编辑新文章</b></td>
    </tr>
    <tr class="a4"> 
      <td width="15%" height="23" align="center">文章标题:</td>
      <td><input name="title" type="text" size="40" value="<? echo $BlogInfo[title]; ?>" class="input"> 
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
    <tr class="a4"> 
      <td height="23" align="center">文章作者:</td>
      <td width="45%"> <input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input"></td>
      <td width="14%" align="center">文章分类:</td>
      <td width="26%"><select name="esort" class="botton">
          <? 
	while($row=mysql_fetch_array($resultSort))
	{ 
?>
          <option value="<? echo $row['cnName']; ?>" <? editBlogSelSort($row['cnName'],$BlogInfo['sort']); ?>> 
          <? echo $row['cnName']; ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr class="a4">
      <td height="23" align="center">置顶:</td>
      <td><select name="isTop" id="isTop">
          <option value="0" selected>否</option>
          <option value="1">是</option>
        </select></td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="a4"> 
      <td align="center">辅助功能:</td>
      <td colspan="3"><table border="0" cellpadding="1" cellspacing="1">
          <tr> 
            <td><a href="javascript:exEdit(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" width="15" height="15" border="0"></a></td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td height="19" colspan="10">&nbsp;</td>
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
    <tr class="a4"> 
      <td align="center" valign="top">文章内容:</td>
      <td colspan="3"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"><? echo $BlogInfo[content]; ?></textarea> 
      </td>
    </tr>
    <tr align="center" class="a4"> 
      <td height="30" colspan="4"> <input type="hidden" name="id" value="<? analysisPostBlogID($_POST['searchID'], $_GET['id']); ?>"> 
        <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>"> 
        <input type="submit" name="action" value="编辑文章" class="botton"> </td>
    </tr>
  </table>
  <?
	}
    elseif($_GET['action'] == "add")
	{
?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr align="center"> 
      <td height="25" colspan="4" class="a1">- 添加新文章</td>
    </tr>
    <tr class="a4"> 
      <td width="20%" height="23" align="center">文章标题:</td>
      <td height="23"> <input name="title" type="text" size="40" class="input"> 
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
    <tr class="a4"> 
      <td height="23" align="center">文章作者:</td>
      <td width="40%"> <input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input"> 
      </td>
      <td width="20%" align="center">文章分类:</td>
      <td width="20%"> <select name="esort" class="botton">
          <? while($row=mysql_fetch_array($resultSort)) { ?>
          <option value="<? echo $row['cnName']; ?>"><? echo $row['cnName']; ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr class="a4">
      <td height="23" align="center">置顶:</td>
      <td><select name="isTop" id="isTop">
          <option value="0" selected>否</option>
          <option value="1">是</option>
        </select></td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="a4"> 
      <td align="center">辅助功能:</td>
      <td colspan="3"><table border="0" cellpadding="1" cellspacing="1">
          <tr> 
            <td><a href="javascript:exEdit(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" width="15" height="15" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" width="15" height="15" border="0"></a></td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td height="19" colspan="10">&nbsp;</td>
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
    <tr class="a4"> 
      <td align="center">文章内容:</td>
      <td colspan="3"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"></textarea> 
      </td>
    </tr>
    <tr align="center" class="a4"> 
      <td height="30" colspan="4"> <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>"> 
        <input type="submit" name="action" value="添加文章" class="botton"> </td>
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
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr align="center">
      <td height="25" colspan="4" class="a1"><b>修改文章</b></td>
    </tr>
    <tr class="a4">
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
    <tr class="a4">
      <td width="15%" height="23">&nbsp;id:[<? echo "$editBlog[id]"; ?>]</td>
      <td width="65%"><a href="./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>"><? echo "$editBlog[title]" ?></a></td>
      <td width="10%"><a href="./editblog.php?action=modify&id=<? echo "$editBlog[id]"?>">[编辑]</a></td>
      <td width="10%"><a href="./editblog.php?action=delete&id=<? echo "$editBlog[id]"?>">[删除]</a></td>
    </tr>
    <? } ?>
    <tr class="a4">
      <td colspan="4">&nbsp; </td>
    </tr>
  </table>
  <? 
	 $show_pages=cutBlogPage();
		  //显示最终结果
	echo"<br>查询结果<br>$show_pages<br>";
	 echo $display;
    }
	?>
</form>
</body>
</html>
