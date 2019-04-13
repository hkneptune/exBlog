<?
/*==================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004 - 2005. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在主页提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: 框架左页面
*\=================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/list.php");

$globalInfo=selectGlobal();          ### 查询blog常项设置

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
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[0]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="../index.php"  target="_blank"><?php echo $lang[1]; ?></a><br>
              <a href="./frame.php?action=exit" target="_parent"><?php echo $lang[2]; ?></a>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>

<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[3]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center">
              <a href="./main.php" target="mainFrame"><?php echo $lang[4]; ?></a><br>
              <a href="./other.php" target="mainFrame"><?php echo $lang[5]; ?></a><br>
              <a href="./editannounce.php?action=add" target="mainFrame"><?php echo $lang[6]; ?></a><br>
              <a href="./editannounce.php?action=edit" target="mainFrame"><?php echo $lang[7]; ?></a> 
              <br>
              <a href="./editabout.php?action=edit" target="mainFrame"><?php echo $lang[8]; ?></a>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[10]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center">
     <!--     <a href="./editblog.php?action=add" target="mainFrame">UBB - BLOG</a><br>
              <a href="./edithtmlblog.php?action=add" target="mainFrame">HTML-BLOG</a><br>  -->
              <a href="./editblog.php?action=add" target="mainFrame"><?php echo $lang[11]; ?></a><br>
              <a href="./editblog.php?action=list" target="mainFrame"><?php echo $lang[12]; ?></a><br>
              <a href="./editsort.php?action=add" target="mainFrame"><?php echo $lang[13]; ?></a><br>
              <a href="./editsort.php?action=edit" target="mainFrame"><?php echo $lang[14]; ?></a><br>
              <a target="mainFrame" href="./editsort.php?action=rank"><?php echo $lang[15]; ?></a> 
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[16]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2"> <table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="./editlinks.php?action=add" target="mainFrame"><?php echo $lang[17]; ?></a><br>
              <a href="./editlinks.php?action=edit" target="mainFrame"><?php echo $lang[18]; ?></a><br>
<a target="mainFrame" href="./editlinks.php?action=rank"><?php echo $lang[19]; ?></a></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[20]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF">
              <div align="center"><a href="./editcomment.php?action=edit" target="mainFrame"><?php echo $lang[21]; ?></a>
              <br>
              <a target="mainFrame" href="./edittrackback.php?action=edit"><?php echo $lang[34]; ?></a> 
</div>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[35]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF">
              <div align="center"><a href="./keyword.php?action=list" target="mainFrame"><?php echo $lang[36]; ?></a>
</div>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[22]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="./edituser.php?action=adduser" target="mainFrame"><?php echo $lang[23]; ?></a><br>
              <a href="./edituser.php?action=list" target="mainFrame"><?php echo $lang[24]; ?></a> 
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[25]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a target="mainFrame" href="./backup.php"><?php echo $lang[26]; ?></a><br>
              <a target="mainFrame" href="./optimize.php"><?php echo $lang[27]; ?></a> 
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[31]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center">
              <a target="mainFrame" href="./upload.php?action=edit"><?php echo $lang[9]; ?></a>
              <br />
              <a target="mainFrame" href="./upload.php?action=addForm"><?php echo $lang[32]; ?></a> 
              <br />
              <a target="mainFrame" href="./upload.php?action=list"><?php echo $lang[33]; ?></a>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center">Plugin</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a target="mainFrame" href="./../plugin/trackback.php">TrackBack Ping</a>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center"><?php echo $lang[28]; ?></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><?php echo $lang[29]; ?><font color="#4B0082"><? echo $globalInfo['Version']; ?></font><br />
<?php echo $lang[30]; ?><br>
              <a href="http://www.exBlog.net/" target="mainFrame">Www.exBlog.Net</font></a> 
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>