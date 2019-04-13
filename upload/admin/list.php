<?
/*==================================================================
*\    exBlog Ver: 1.2.0 [L]  exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在主页提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: 框架左页面
*\=================================================================*/
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
</head>
<body class="main">
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center">管理后台</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="../index.php"  target="_blank">我的Blog</a><br>
              <a href="./frame.php?action=exit" target="_parent">退出管理</a>
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
          <td class="menu"> <div align="center">常规选项</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center">
              <a href="./main.php" target="mainFrame">管理首页</a><br>
              <a href="./other.php" target="mainFrame">常规配置</a><br>
              <a href="./editannounce.php?action=add" target="mainFrame">添加公告</a><br>
              <a href="./editannounce.php?action=edit" target="mainFrame">编辑公告</a> 
              <br>
              <a href="./editabout.php?action=edit" target="mainFrame">编辑个人说明</a>
              <br>
              <a target="mainFrame" href="./photoset.php?action=edit">图片上传设置</a> 
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
          <td class="menu"> <div align="center">文章选项</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="./editblog.php?action=add" target="mainFrame">UBB - BLOG</a><br>
              <a href="./edithtmlblog.php?action=add" target="mainFrame">HTML-BLOG</a><br>
              <a href="./editblog.php?action=edit" target="mainFrame">编辑BLOG</a><br>
              <a href="./editsort.php?action=add" target="mainFrame">添加分类</a><br>
              <a href="./editsort.php?action=edit" target="mainFrame">编辑分类</a><br>
              <a target="mainFrame" href="./editsort.php?action=rank">分类排序</a> 
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
          <td class="menu"> <div align="center">链接选项</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2"> <table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="./editlinks.php?action=add" target="mainFrame">添加链接</a><br>
              <a href="./editlinks.php?action=edit" target="mainFrame">编辑连接</a><br>
<a target="mainFrame" href="./editlinks.php?action=rank">链接排序</a></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="99%" border="0" cellspacing="2" cellpadding="1" class="main">
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
        <tr bgcolor="#99CC66"> 
          <td class="menu"> <div align="center">评论管理</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF">
              <div align="center"><a href="./editcomment.php?action=edit" target="mainFrame">评论管理</a></div>
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
          <td class="menu"> <div align="center">用户管理</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a href="./edituser.php?action=adduser" target="mainFrame">添加用户</a><br>
              <a href="./edituser.php?action=edit" target="mainFrame">编辑用户</a> 
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
          <td class="menu"> <div align="center">数据管理</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><a target="mainFrame" href="./backup.php">备份数据库</a><br>
              <a target="mainFrame" href="./optimize.php">优化数据库</a> 
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
          <td class="menu"> <div align="center">exBlog信息</div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" class="main">
        <tr> 
          <td bgcolor="#FFFFFF">版本：<br>exBlog 1.2.0 [L] 圣诞版<br>
版权所有：<br>exSoft Team<br>
              <a href="http://www.exBlog.net/" target="mainFrame">Www.exBlog.Net</a> 
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>