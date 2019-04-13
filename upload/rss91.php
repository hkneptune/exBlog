<?php
/*============================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\----------------------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\---------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\---------------------------------------------------------------------------
*\    本页说明: 显示RSS页面
*\==========================================================================*/

include_once("./include/config.inc.php");
include_once("./include/RssFeedBuild91.inc.php");
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
$rssFeed = new RssFeedBuild();
$rssFeed->setChannel($tmp_global['siteName'], $tmp_global['siteUrl'], $tmp_global['Description'], $tmp_global['Webmaster'], $tmp_global['copyright'], "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");

$query = mysql_query("SELECT * FROM `$x[blog]` WHERE hidden='0' ORDER BY id DESC LIMIT 0,15");
while($rows = mysql_fetch_array($query))
{
	$rows['title']=htmlspecialchars($rows['title']);
if($rows['html']=="0"){
	$rows['content']=htmlspecialchars($rows['content']);
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
}
	$rows['content'] = html_clean($rows['content']);
	$rows['content']=epost($rows['content']);
	$at_month=substr($rows['addtime'],5,2);
	$at_day=substr($rows['addtime'],8,2);
	$at_year=substr($rows['addtime'],0,4);
	$at_time=substr($rows['addtime'],11,8);
	$at_julian=juliantojd($at_month,$at_day,$at_year);
	$at_mn=jdmonthname($at_julian,2);
	$at_week=jddayofweek($at_julian,2);
	$modified=$at_week.", ".$at_day." ".$at_mn." ".$at_year." ".$at_time." +0800";
	$url = $tmp_global['siteUrl']."/index.php?id=".$rows['id'];
	$rssFeed->setItem($rows['title'], $url, $rows['content'], $modified);
}
$rssFeed->buildRssFeed("ECHO");


?>