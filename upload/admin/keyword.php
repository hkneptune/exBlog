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
*\    本页说明: 名词注释管理页面
*\=================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");

setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/keyword.php");

checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body class="main" dir="<?php echo $langpublic[dir]; ?>">


<?php

if($_POST['action'] == "add")
{
	addKeyword($_POST[word], $_POST[content], $_POST[url]);
}
elseif($_GET['action'] == "delete")
{
	deleteKeyword($_GET['id']);
}
elseif($_POST['action'] == "update")
{
	updateKeyword($_POST[id], $_POST[word], $_POST[content], $_POST[url]);
}
elseif($_GET['action'] == "list")
{
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu" align="center"><b><?php echo $lang[3]; ?></b></td>
    </tr>
    <tr> 
      <td width="20%" height="23" align="center"><?php echo $lang[4]; ?></td>
      <td width="80%"><input name="word" type="text" class="input" id="word"></td>
    </tr>
    <tr> 
      <td height="23" align="center"><?php echo $lang[5]; ?></td>
      <td valign="top"><input name="url" type="text" class="input" id="url" size="40">
        <?php echo $lang[6]; ?></td>
    </tr>
    <tr> 
      <td align="center"><?php echo $lang[7]; ?></td>
      <td><textarea name="content" cols="60" rows="8" wrap="VIRTUAL" class="input" id="content"></textarea> 
      </td>
    </tr>
    <tr align="center"> 
<input type="hidden" name="action" value="add"> 
      <td height="30" colspan="2"> <input type="submit" value="<?php echo $lang[8]; ?>" class="botton"> 
      </td>
    </tr>
  </table>
</form>
<table width="98%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b><?php echo $lang[9]; ?></b></td>
    </tr>

    <?php
	selectKeywordList();                  ### 查询列表
    while($editKeyword=mysql_fetch_array($GLOBALS[resultKeyword]))
	{
	$editKeyword['content']=substr($editKeyword['content'],"0","80");
	$editKeyword['content']=$editKeyword['content']."...";
?>
    <tr class="main">
      <td width="15%" height="23"><a href="./keyword.php?action=modify&id=<? echo "$editKeyword[id]"?>"><? echo "$editKeyword[word]" ?></a></td>
      <td width="65%"><a href="./keyword.php?action=edit&id=<? echo "$editKeyword[id]"?>"><? echo "$editKeyword[content]" ?></a></td>
      <td width="10%"><a href="./keyword.php?action=edit&id=<? echo "$editKeyword[id]"?>"><?php echo $lang[0]; ?></a></td>
      <td width="10%"><a href="./keyword.php?action=delete&id=<? echo "$editKeyword[id]"?>" onclick="if(confirm ('<?php echo $lang[1]; ?>')){;}else{return false;}"><?php echo $lang[2]; ?></a></td>
    </tr>

    <? } ?>
  </table>
      </td>
  </tr>
</table>
<? }
elseif($_GET[action] == "edit")
{
	$KeywordInfo=selectOneKeywordInfo($_GET['id']); ### 查询当前待编辑的文章内容
?>
<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">

  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="100%" border="0" class="main">
          <tr> 
            <td colspan="2" class="menu" align="center"><b><?php echo $lang[10]; ?></b></td>
    </tr>
    <tr> 
      <td width="20%" height="23" align="center"><?php echo $lang[4]; ?></td>
      <td width="80%"><input name="word" type="text" class="input" id="word" value="<? echo htmlspecialchars($KeywordInfo[word]); ?>"></td>
    </tr>
    <tr> 
      <td height="23" align="center"><?php echo $lang[5]; ?></td>
      <td valign="top"><input name="url" type="text" class="input" id="url" size="40" value="<? echo htmlspecialchars($KeywordInfo[url]); ?>">
        <?php echo $lang[6]; ?></td>
    </tr>
    <tr> 
      <td align="center"><?php echo $lang[7]; ?></td>
      <td><textarea name="content" cols="60" rows="8" wrap="VIRTUAL" class="input" id="content"><? echo htmlspecialchars($KeywordInfo[content]); ?></textarea> 
      </td>
    </tr>
    <tr align="center"> 
<input type="hidden" name="action" value="update"> 
<input type="hidden" name="id" value="<?php echo $KeywordInfo[id]; ?>"> 
      <td height="30" colspan="2"> <input type="submit" value="<?php echo $lang[11]; ?>" class="botton"> 
      </td>
    </tr>
  </table>
</form>

<table width="98%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b>名词注释列表</b></td>
    </tr>

    <?php
	selectKeywordList();                  ### 查询列表
    while($editKeyword=mysql_fetch_array($GLOBALS[resultKeyword]))
	{
	$editKeyword['content']=substr($editKeyword['content'],"0","80");
	$editKeyword['content']=$editKeyword['content']."...";
?>
    <tr class="main">
      <td width="15%" height="23"><a href="./keyword.php?action=modify&id=<? echo "$editKeyword[id]"?>"><? echo "$editKeyword[word]" ?></a></td>
      <td width="65%"><a href="./keyword.php?action=edit&id=<? echo "$editKeyword[id]"?>"><? echo "$editKeyword[content]" ?></a></td>
      <td width="10%"><a href="./keyword.php?action=edit&id=<? echo "$editKeyword[id]"?>"><?php echo $lang[0]; ?></a></td>
      <td width="10%"><a href="./keyword.php?action=delete&id=<? echo "$editKeyword[id]"?>" onclick="if(confirm ('<?php echo $lang[1]; ?>')){;}else{return false;}"><?php echo $lang[2]; ?></a></td>
    </tr>

    <? } ?>
  </table>
      </td>
  </tr>
</table>
<? } ?>

</body>
</html>






