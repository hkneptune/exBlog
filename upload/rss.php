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
include_once("./include/RssFeedBuild.inc.php");
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
$rssFeed = new RssFeedBuild();
$rssFeed->setChannel($siteName, $siteUrl,  $Description, "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");

$query = mysql_query("SELECT * FROM `$x[blog]` ORDER BY id DESC LIMIT 0,15");
while($rows = mysql_fetch_array($query))
{
	$rows['title']=htmlspecialchars($rows['title']);
if($rows['html']=="0"){
	$rows['content']=htmlspecialchars($rows['content']);
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
}
	$rows['content']=preg_replace("/\:ex(.+?)\:/is","",$rows['content']);
	$modified=substr($rows['addtime'],0,10)."T".substr($rows['addtime'],11,8)." +0800";
	//echo "  <msg id=\"$rows[id]\" author=\"$rows[author]\">\n";
	//echo "  <title sort=\"$rows[sort]\"><![CDATA[$rows[title]]]></title>\n";
	//echo "  <content><![CDATA[$rows[content]]]></content>\n";
	//echo "  <other visits=\"$rows[visits]\" email=\"$rows[email]\" addtime=\"$rows[addtime]\"></other>\n";

if($exurlyorn != 1)
$url = $siteUrl."/?id=".$rows['id'];
else
$url = $siteUrl."/exurl.php/reply/".$rows['id'].".html";

	$rssFeed->setItem($rows['title'], $url, $rows['content'], $modified);
}
$rssFeed->buildRssFeed("ECHO");


?>