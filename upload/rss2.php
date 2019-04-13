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
include_once("./include/RssFeedBuild2.inc.php");
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
if($sort==""){
	if(!$comment){

$rssFeed->setChannel($siteName, $siteUrl, $Description, $Webmaster, $copyright, "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}else{
$rssFeed->setChannel($siteName."==".$lang['1'], $siteUrl, $Description, $Webmaster, $copyright, "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}

}else{

$sortquery = mysql_query("SELECT * FROM `$x[sort]` WHERE enName='$sort'");
$tmp_sort = mysql_fetch_array($sortquery);

	if($tmp_sort){
$cnName = htmlspecialchars($tmp_sort['cnName']);
$enName = htmlspecialchars($tmp_sort['enName']);
$Description = $tmp_sort['Description'];

if($exurlyorn != 1)
$url = $siteUrl."/index.php?play=view&sort=".$enName;
else
$url = $siteUrl."/exurl.php/view/".$enName.".html";

$rssFeed->setChannel($siteName."==".$cnName, $url, $Description, $Webmaster, $copyright, "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}else{
$rssFeed->setChannel($siteName, $siteUrl, $Description, $Webmaster, $copyright, "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}

}



if($sort==""){
	if(!$comment){
$query = mysql_query("SELECT * FROM `$x[blog]` WHERE hidden='0' ORDER BY id DESC LIMIT 0,15");
	}else{
$query = mysql_query("SELECT * FROM `$x[comment]` ORDER BY id DESC LIMIT 0,$comment");
	}
}else{
$query = mysql_query("SELECT * FROM `$x[blog]` WHERE sort='$tmp_sort[cnName]' AND hidden='0' ORDER BY id DESC LIMIT 0,15");
}
while($rows = mysql_fetch_array($query))
{
	if(!$comment){
	$rows['title']=htmlspecialchars($rows['title']);
if($rows['html']=="0"){
	$rows['content']=htmlspecialchars($rows['content']);
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
}
	$rows['content'] = html_clean($rows['content']);
	$at_month=substr($rows['addtime'],5,2);
	$at_day=substr($rows['addtime'],8,2);
	$at_year=substr($rows['addtime'],0,4);
	$at_hour=substr($rows['addtime'],11,2);
	$at_minute=substr($rows['addtime'],14,2);
	$at_second=substr($rows['addtime'],17,2);
	$timestamp=mktime($at_hour, $at_minute, $at_second, $at_month, $at_day, $at_year);
	$modified=gmdate("D, d M Y H:i:s", $timestamp)." GMT";

if($exurlyorn != 1)
$url = $siteUrl."/index.php?id=".$rows['id'];
else
$url = $siteUrl."/exurl.php/reply/".$rows['id'].".html";

	$author = $rows['author']." (".$rows['email'].")";
	$sort = $rows['sort'];
	$rssFeed->setItem($rows['title'], $url, $rows['content'], $modified, $author, $sort);
	}else{
	$at_month=substr($rows['addtime'],5,2);
	$at_day=substr($rows['addtime'],8,2);
	$at_year=substr($rows['addtime'],0,4);
	$at_hour=substr($rows['addtime'],11,2);
	$at_minute=substr($rows['addtime'],14,2);
	$at_second=substr($rows['addtime'],17,2);
	$timestamp=mktime($at_hour, $at_minute, $at_second, $at_month, $at_day, $at_year);
	$modified=gmdate("D, d M Y H:i:s", $timestamp)." GMT";

if($exurlyorn != 1)
$url = $siteUrl."/index.php?id=".$rows['commentID'];
else
$url = $siteUrl."/exurl.php/reply/".$rows['commentID'].".html";

	$rssFeed->setItem($rows['author']." ".$lang['2'], $url, "<b>E-mail:".$rows['email']."<br />Homepage:".$rows['homepage']."<br />QQ:".$rows['qq']."</b><br />".$rows['content'], $modified, $rows['author'], "Comment");
	}
}
$rssFeed->buildRssFeed("ECHO");


?>