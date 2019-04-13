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
*\    本页说明: 显示RSS页面
*\==========================================================================*/

include_once("./include/config.inc.php");
include_once("./include/RssFeedBuild2.inc.php");
include_once("./include/global-A.inc.php");
include_once("./include/global-C.inc.php");
include_once("./include/ExUbb.inc.php");

setGlobal();

$exubb = new ExUbb();

$query = mysql_query("SELECT * FROM `$x[global]`");
$tmp_global = mysql_fetch_array($query);
$rssFeed = new RssFeedBuild();
if($sort==""){
	if(!$comment){
$rssFeed->setChannel($tmp_global['siteName'], $tmp_global['siteUrl'], $tmp_global['description'], $tmp_global['webmaster'], $tmp_global['copyright'], "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}else{
$rssFeed->setChannel($tmp_global['siteName']."—最近的评论", $tmp_global['siteUrl'], $tmp_global['description'], $tmp_global['webmaster'], $tmp_global['copyright'], "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}
}else{
$sortquery = mysql_query("SELECT * FROM `$x[sort]` WHERE enName='$sort'");
$tmp_sort = mysql_fetch_array($sortquery);
	if($tmp_sort)
	{
$rssFeed->setChannel($tmp_global['siteName']."—".$tmp_sort['cnName'], $tmp_global['siteUrl']."?play=view&sort=".$tmp_sort['enName'], $tmp_sort['description'], $tmp_global['webmaster'], $tmp_global['copyright'], "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}
	else
	{
$rssFeed->setChannel($tmp_global['siteName'], $tmp_global['siteUrl'], $tmp_global['description'], $tmp_global['webmaster'], $tmp_global['copyright'], "http://www.madcn.com/blog/templates/madblog2/images/icon_power.png");
	}
}



if($sort==""){
	if(!$comment){
$query = mysql_query("SELECT * FROM `$x[blog]` ORDER BY id DESC LIMIT 0,15");
	}else{
$query = mysql_query("SELECT * FROM `$x[comment]` ORDER BY id DESC LIMIT 0,$comment");
	}
}else{
$query = mysql_query("SELECT * FROM `$x[blog]` WHERE sort='$tmp_sort[cnName]' ORDER BY id DESC LIMIT 0,15");
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
	$rows['content']=epost($rows['content']);
	$at_month=substr($rows['addtime'],5,2);
	$at_day=substr($rows['addtime'],8,2);
	$at_year=substr($rows['addtime'],0,4);
	$at_time=substr($rows['addtime'],11,8);
	$modified=$at_day." ".$at_month." ".$at_year." ".$at_time." +0800";
	$url = $tmp_global['siteUrl']."/?play=show&id=".$rows['id'];
	$rssFeed->setItem($rows['title'], $url, $rows['content'], $modified);
	}else{
	$at_month=substr($rows['addtime'],5,2);
	$at_day=substr($rows['addtime'],8,2);
	$at_year=substr($rows['addtime'],0,4);
	$at_time=substr($rows['addtime'],11,8);
	$modified=$at_day." ".$at_month." ".$at_year." ".$at_time." +0800";
	$url = $tmp_global['siteUrl']."/?play=show&id=".$rows['commentID'];
	$rssFeed->setItem($rows['author']." 朋友评论", $url, "<b>E-mail:".$rows['email']."<br />Homepage:".$rows['homepage']."<br />QQ:".$rows['qq']."</b><br />".$rows['content'], $modified);
	}
}
$rssFeed->buildRssFeed("ECHO");


?>