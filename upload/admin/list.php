<?
/*==================================================================
*\    exBlogMix Ver: 1.2.0  exBlogMix 网络日志(PHP+MYSQL) 1.2.0 版
*\------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在主页提出)
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
<meta Name="author" content="elliott,hunter">
<meta name="keywords" content="elliott,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by elliott">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>{title}</title>
<style type="text/css">
BODY {
	BACKGROUND:799ae1; MARGIN: 0px; FONT: 9pt 宋体
}
TD {
	FONT: 12px 宋体
}
A {
	FONT: 12px 宋体; COLOR: #000000; TEXT-DECORATION: none
}
A:hover {
	COLOR: #428eff; TEXT-DECORATION: underline
}
.sec_menu {
	BORDER-RIGHT: white 1px solid; BACKGROUND: #d6dff7; OVERFLOW: hidden; BORDER-LEFT: white 1px solid; BORDER-BOTTOM: white 1px solid
}

.menu_title SPAN {
	FONT-WEIGHT: bold; LEFT: 8px; COLOR: #215dc6; POSITION: relative; TOP: 2px
}

.menu_title2 SPAN {
	FONT-WEIGHT: bold; LEFT: 8px; COLOR: #428eff; POSITION: relative; TOP: 2px
}
.updown {
	cursor: hand;
}
</style>

</head>






<table cellspacing="0" cellpadding="0" width="158" align="center"><tr><td valign="bottom" height="42">
	<a href="javascript:location.reload()">
	<img height="38" src="../images/title.gif" width="158" border="0"></a></td></tr><tr>
	  <td class="menu_title" onmouseover="this.className='menu_title2';" onmouseout="this.className='menu_title';" background="../images/title_bg_quit.gif" height="25"><span><a target="mainFrame" href="./main.php" onclick="message()"><b><font color="215DC6">管理首页</font></b></a> | <a href="./frame.php?action=exit" target="_parent"><font color="215DC6"><b>退出</b></font></a></span></td>

</tr>

<tr>
<td align="center" onMouseOver=aa('up') onMouseOut=StopScroll()><font color="#FFFFFF" face="Webdings" class="updown">5</font>
</td>
</tr>
</table>
<script>
var he=document.body.clientHeight-105
document.write("<div id=tt style=height:"+he+";overflow:hidden>")
</script>


<table cellspacing="0" cellpadding="0" width="158" align="center"><tr><td class="menu_title" id="menuTitle1" onmouseover="this.className='menu_title2';" onclick="showsubmenu(1)" onmouseout="this.className='menu_title';" style="cursor:hand" background="../images/admin_left_2.gif" height="25"><span>常规选项 </span></td></tr><tr><td id="submenu1" style="DISPLAY: "><div class="sec_menu" style="WIDTH: 158px"><table cellspacing="0" cellpadding="0" width="135" align="center"><tr><td height="20"><a target="mainFrame" href="./other.php">系统常规配置</a></td></tr><tr>
  <td height="20"><a target="mainFrame" href="./editabout.php?action=edit">基本说明修改</a></td>
</tr></table>
</div><div style="WIDTH: 158px"><table cellspacing="0" cellpadding="0" width="135" align="center"><tr><td height="20"></td></tr></table></div></td></tr></table>
<table cellspacing="0" cellpadding="0" width="158" align="center">
  <tr>
    <td class="menu_title" id="menuTitle1" onmouseover="this.className='menu_title2';" onclick="showsubmenu(2)" onmouseout="this.className='menu_title';" style="cursor:hand" background="../images/admin_left_4.gif" height="25"><span>BLOG选项</span></td>
  </tr>
  <tr>
    <td id="submenu2" style="DISPLAY: "><div class="sec_menu" style="WIDTH: 158px">
        <table cellspacing="0" cellpadding="0" width="135" align="center">
          <tr>
            <td height="20"><a target="mainFrame" href="./editblog.php?action=add">添加Blog</a></td>
          </tr>
          <tr>
            <td height="20"><a target="mainFrame" href="./editblog.php?action=edit">编辑Blog</a></td>
          </tr>
          <tr>
            <td height="20"><a target="mainFrame" href="./editsort.php?action=add">添加分类</a></td>
          </tr>
          <tr>
            <td height="20"><a target="mainFrame" href="./editsort.php?action=edit">编辑分类</a></td>
          </tr>
        </table>
    </div>
        <div style="WIDTH: 158px">
          <table cellspacing="0" cellpadding="0" width="135" align="center">
            <tr>
              <td height="20"></td>
            </tr>
          </table>
      </div></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="158" align="center">
  <tr>
    <td class="menu_title" id="menuTitle1" onmouseover="this.className='menu_title2';" onclick="showsubmenu(3)" onmouseout="this.className='menu_title';" style="cursor:hand" background="../images/admin_left_3.gif" height="25"><span>系统公告</span></td>
  </tr>
  <tr>
    <td id="submenu3" style="DISPLAY: none"><div class="sec_menu" style="WIDTH: 158px">
        <table cellspacing="0" cellpadding="0" width="135" align="center">
          <tr>
            <td height="20"><a target="mainFrame" href="./editannounce.php?action=add">添加公告</a></td>
          </tr>
          <tr>
            <td height="20"><a target="mainFrame" href="./editannounce.php?action=edit">删除公告</a></td>
          </tr>
        </table>
      </div>
        <div style="WIDTH: 158px">
          <table cellspacing="0" cellpadding="0" width="135" align="center">
            <tr>
              <td height="20"></td>
            </tr>
          </table>
      </div></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="158" align="center"><tr>
  <td class="menu_title" id="menuTitle1" onmouseover="this.className='menu_title2';" onclick="showsubmenu(4)" onmouseout="this.className='menu_title';" style="cursor:hand" background="../images/admin_left_6.gif" height="25"><span>链接选项</span></td>
</tr><tr><td id="submenu4" style="DISPLAY: none"><div class="sec_menu" style="WIDTH: 158px">
  <table cellspacing="0" cellpadding="0" width="135" align="center">
    <tr>
      <td height="20"><a target="mainFrame" href="./editlinks.php?action=add">添加链接</a></td>
    </tr>
    <tr>
      <td height="20"><a target="mainFrame" href="./editlinks.php?action=edit">编辑链接</a></td>
    </tr>
  </table>
</div><div style="WIDTH: 158px"><table cellspacing="0" cellpadding="0" width="135" align="center"><tr><td height="20"></td></tr></table></div></td></tr></table><table cellspacing="0" cellpadding="0" width="158" align="center"><tr>
  <td class="menu_title" id="menuTitle1" onmouseover="this.className='menu_title2';" onclick="showsubmenu(5)" onmouseout="this.className='menu_title';" style="cursor:hand" background="../images/admin_left_5.gif" height="25"><span>用户管理</span> </td>
</tr><tr><td id="submenu5" style="DISPLAY: none"><div class="sec_menu" style="WIDTH: 158px">
  <table cellspacing="0" cellpadding="0" width="135" align="center">
    <tr>
      <td height="20"><a target="mainFrame" href="./edituser.php?action=adduser">添加用户</a></td>
    </tr>
    <tr>
      <td height="20"><a target="mainFrame" href="./edituser.php?action=edit">编辑用户</a></td>
    </tr>
  </table>
</div></td></tr></table>&nbsp; <table cellspacing="0" cellpadding="0" width="158" align="center"><tr>
  <td class="menu_title" id="menuTitle1" onmouseover="this.className='menu_title2';" onmouseout="this.className='menu_title';" background="../images/admin_left_9.gif" height="25"><span>exBlog信息</span> </td>
</tr><tr><td><div class="sec_menu" style="WIDTH: 158px"><table cellspacing="0" cellpadding="0" width="135" align="center"><tr><td height="20"><br>
版本：exBlogMix 1.2.0<br>
<br>版权所有：<br>
 exSoft Studio <br>
(<a target="_blank" href="http://exblog.fengling.net/"><font color="000000">exBlog.FengLing</font><font color="FF0000">.Net</font></a>)<br>
<br></td></tr></table></div></td></tr></table></tr></tbody></table>
</div>

<table cellspacing="0" cellpadding="0" width="158" align="center">
<tr>
<td align="center" onMouseOver=aa('Down') onMouseOut=StopScroll() valign="bottom"><font color="#FFFFFF" face="Webdings" class="updown">6</font>
</td>
</tr>
</table>

<script>




function aa(Dir)
{tt.doScroll(Dir);Timer=setTimeout('aa("'+Dir+'")',250)}//这里250为滚动速度
function StopScroll(){if(Timer!=null)clearTimeout(Timer)}



function initIt(){
divColl=document.all.tags("DIV");
for(i=0; i<divColl.length; i++) {
whichEl=divColl(i);
if(whichEl.className=="child")whichEl.style.display="none";}
}
function expands(el) {
whichEl1=eval(el+"Child");
if (whichEl1.style.display=="none"){
initIt();
whichEl1.style.display="block";
}else{whichEl1.style.display="none";}
}
var tree= 0;
function loadThreadFollow(){
if (tree==0){
document.frames["hiddenframe"].location.replace("tree.asp");
tree=1
}
}

function showsubmenu(sid)
{
whichEl = eval("submenu" + sid);
if (whichEl.style.display == "none")
{
eval("submenu" + sid + ".style.display=\"\";");
}
else
{
eval("submenu" + sid + ".style.display=\"none\";");
}
}

</script>

<iframe height="0" width="0" name="hiddenframe"></iframe>