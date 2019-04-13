<?

/*===========================================================================
*\    exBlogMix Ver: 1.2.0  exBlogMix 网络日志(PHP+MYSQL) 1.2.0 版
*\---------------------------------------------------------------------------
*\    Copyright(C) Elliott & Hunter, 2004. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
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

$exubb = new ExUbb();

$query = mysql_query("SELECT * FROM `$x[global]`");
$tmp_global = mysql_fetch_array($query);
$rssFeed = new RssFeedBuild();
$rssFeed->setChannel($tmp_global['siteName'], $tmp_global['siteUrl'], "exblog RSS Feed");

$query = mysql_query("SELECT * FROM `$x[blog]` ORDER BY id DESC LIMIT 0,15");
while($rows = mysql_fetch_array($query))
{
	$rows['title']=htmlspecialchars($rows['title']);
	$rows['content']=htmlspecialchars($rows['content']);
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
	$rows['content']=epost($rows['content']);
	//echo "  <msg id=\"$rows[id]\" author=\"$rows[author]\">\n";
	//echo "  <title sort=\"$rows[sort]\"><![CDATA[$rows[title]]]></title>\n";
	//echo "  <content><![CDATA[$rows[content]]]></content>\n";
	//echo "  <other visits=\"$rows[visits]\" email=\"$rows[email]\" addtime=\"$rows[addtime]\"></other>\n";
	$url = $tmp_global['siteUrl']."/?play=show&id=".$rows['id'];
	$rssFeed->setItem($rows['title'], $url, $rows['content']);
}
$rssFeed->buildRssFeed("ECHO");


?>