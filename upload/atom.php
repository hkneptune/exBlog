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
include_once("./include/atomFeedBuild.inc.php");
include_once("./include/global-A.inc.php");
include_once("./include/global-C.inc.php");
include_once("./include/ExUbb.inc.php");

setGlobal();

$exubb = new ExUbb();

$query = mysql_query("SELECT * FROM `$x[global]`");
$tmp_global = mysql_fetch_array($query);
$rssFeed = new RssFeedBuild();
$rssFeed->setChannel($tmp_global['siteName'], $tmp_global['siteUrl'], $tmp_global['description']);

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
	$rows['content']=epost($rows['content']);
	$modified=substr($rows['addtime'],0,10)."T".substr($rows['addtime'],11,8)." +0800";
	$issued=substr($rows['addtime'],0,10)."T".substr($rows['addtime'],11,8)." +0800";
	//echo "  <msg id=\"$rows[id]\" author=\"$rows[author]\">\n";
	//echo "  <title sort=\"$rows[sort]\"><![CDATA[$rows[title]]]></title>\n";
	//echo "  <content><![CDATA[$rows[content]]]></content>\n";
	//echo "  <other visits=\"$rows[visits]\" email=\"$rows[email]\" addtime=\"$rows[addtime]\"></other>\n";
	$url = $tmp_global['siteUrl']."/?id=".$rows['id'];
	$id = $tmp_global['siteUrl'].":1:".$rows['id'];
	$rssFeed->setItem($id, $rows['title'], $url, $rows['content'], $modified, $issued, $rows['author']);
}
$rssFeed->buildRssFeed("ECHO");


?>