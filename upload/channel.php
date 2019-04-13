<?

/*===========================================================================
*\    exBlog Ver: 1.2.0 [L] exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\---------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\---------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\---------------------------------------------------------------------------
*\    本页说明: 频道生成页面
*\==========================================================================*/

include_once("./include/config.inc.php");
include_once("./include/channelBuild.inc.php");
include_once("./include/global-A.inc.php");
include_once("./include/global-C.inc.php");

setGlobal();

$query = mysql_query("SELECT * FROM `$x[global]`");
$tmp_global = mysql_fetch_array($query);
$rssFeed = new RssFeedBuild();
$rssFeed->setChannel($tmp_global['siteName'], $tmp_global['siteUrl'], $tmp_global['description']);

$query = mysql_query("SELECT * FROM `$x[sort]` ORDER BY id DESC");
while($rows = mysql_fetch_array($query))
{
	$rows['enName']=htmlspecialchars($rows['enName']);
	$rows['cnName']=htmlspecialchars($rows['cnName']);
$enName=$rows['enName'];
$cnName=$rows['cnName'];
echo "$enName $cnName";
	$rssFeed->setItem($rows['enName'], $rows['cnName']);
}
$rssFeed->buildRssFeed("WRITE");


?>