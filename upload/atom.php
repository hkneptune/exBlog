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
*\    本页说明: 显示RSS页面
*\==========================================================================*/

include_once("./include/config.inc.php");
include_once("./include/atomFeedBuild.inc.php");
include_once("./include/global-A.inc.php");
include_once("./include/global-C.inc.php");
include_once("./include/ExUbb.inc.php");

setGlobal();

//---- 语言包 ----//
selectLangURL();
include("./$langURL/public.php");
include("./$langURL/rss.php");

$exubb = new ExUbb();

$query = mysql_query("SELECT * FROM `$x[global]`");
$tmp_global = mysql_fetch_array($query);
	$exurlyorn=$tmp_global[exurlon];	//伪静态连接开关
$siteName = htmlspecialchars($tmp_global['siteName']);
$siteUrl = $tmp_global['siteUrl'];
$Description = $tmp_global['Description'];
$Webmaster = htmlspecialchars($tmp_global['Webmaster']);
$copyright = $tmp_global['copyright'];
$rssFeed = new RssFeedBuild();
$rssFeed->setChannel($siteName, $siteUrl, $Description, $Webmaster, $copyright);

$query = mysql_query("SELECT * FROM `$x[blog]` ORDER BY id DESC LIMIT 0,15");
while($rows = mysql_fetch_array($query))
{
	$rows['title']=htmlspecialchars($rows['title']);
	$rows['author']=htmlspecialchars($rows['author']);
if($rows['html']=="0"){
	$rows['content']=htmlspecialchars($rows['content']);
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
}
	$rows['content']=preg_replace("/\:ex(.+?)\:/is","",$rows['content']);
	$modified=substr($rows['addtime'],0,10)."T".substr($rows['addtime'],11,8)." +0800";
	$issued=substr($rows['addtime'],0,10)."T".substr($rows['addtime'],11,8)." +0800";

if($exurlyorn != 1)
$url = $siteUrl."/?id=".$rows['id'];
else
$url = $siteUrl."/exurl.php/reply/".$rows['id'].".html";

	$id = $tmp_global['siteUrl'].":1:".$rows['id'];
	$author = htmlspecialchars($rows['author']);
	$email = htmlspecialchars($rows['email']);
	$content = htmlspecialchars($rows['content']);
	$rssFeed->setItem($id, $title, $url, $content, $modified, $issued, $author, $email);
}
$rssFeed->buildRssFeed("ECHO");


?>