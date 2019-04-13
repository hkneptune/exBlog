<?php 
/*===========================================================================
*\    exBlog Ver: 1.3.0 exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\---------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004 - 2005. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\---------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\---------------------------------------------------------------------------
*\    本页说明: 外部调用文件
*\==========================================================================*/

require("./include/config.inc.php");
require("./include/global-A.inc.php");
require("./include/global-C.inc.php");

setGlobal();

$result=shell($_GET['sort']);

//$imgt定义是否使用图片，为空为不使用,这里只填路径就可；
$imgt = 'images/site_icon.gif';

//$znum为定义截取字数,默认为20；
$znum=90;

if(!empty($imgt)){
	$img = '<img src='.$imgt.' border=0>';
}else{
	$img='§. ';
}

?>
document.write("<? echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='main'>"; ?>");
<?
while($rows=mysql_fetch_array($result))
{
	$len=strlen($rows['title']);
	if($len<=$znum)
	{
		$title=$rows['title'];
	}
	else
	{
		$title=substr($rows['title'],0,$znum).chr(0);
		$title=$title."...";
	}
?>
document.write("<? echo"<tr>";?>");
document.write("<? echo "<td width='65%'>$img <A href=http://kiki.class07.com/index.php?play=show&id=$rows[id] target=_blank>$title</A></td>"; ?>");
document.write("<? echo "</tr>"; ?>");
<?
}
?>
document.write("<? echo "</table>"; ?>");