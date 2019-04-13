<?
/*===========================================================================
*\    exBlogMix Ver: 1.2.0 exBlogMix 网络日志(PHP+MYSQL) 1.2.0 版
*\---------------------------------------------------------------------------
*\    Copyright(C) Elliott & Hunter, 2004. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
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
?>
document.write("<? echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='main'>"; ?>");
<?
while($rows=mysql_fetch_array($result))
{
	$len=strlen($rows['title']);
	if($len<=20)
	{
		$title=$rows['title'];
	}
	else
	{
		$title=substr($rows['title'],0,20).chr(0);
		$title=$title."...";
	}
?>
document.write("<? echo"<tr>";?>");
document.write("<? echo "<td width='65%'>§.<A href=index.php?play=show&id=$rows[id] target=_blank>$title</A></td>"; ?>");
document.write("<? echo "</tr>"; ?>");
<?
}
?>
document.write("<? echo "</table>"; ?>");