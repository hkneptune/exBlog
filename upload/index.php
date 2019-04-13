<?
/*===========================================================================
*\    exBlog Ver: 1.2.0 RC1 exBlog 网络日志(PHP+MYSQL) 1.2.0 RC1 版
*\---------------------------------------------------------------------------
*\    Copyright(C) Elliott & Hunter, 2004. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\---------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\---------------------------------------------------------------------------
*\    本页说明: 框架右页面
*\==========================================================================*/

//----- 载入数据库,模版类 ---------//
require("./include/config.inc.php");
require("./include/template.inc");     
require("./include/global-A.inc.php");
require("./include/CheckData.inc.php");
require("./include/global-C.inc.php");
require("./include/date.inc.php");
require("./include/ExUbb.inc.php");
//---- 查询数据库表名及模版名 ----//
setGlobal();
selectTmpURL();

//查询BLOG是否暂时关闭, 关闭则下面程序片段不运行
getBlogIsRun();

//---- 添加网站访问量 ----//
addVisits();

$t = new template("$tmpURL");
$t->set_file(array("b_column" => "b_column.html",
                   "b_comment" => "b_comment.html",
				   "b_calendar" => "b_calendar.html",
				   "b_link" => "b_link.html"));

$t->set_block("b_calendar","RowCalendar","RowsCalendar");
$t->set_block("b_column","RowColumn","RowsColumn");
$t->set_block("b_comment","RowComment","RowsComment");
$t->set_block("b_link","RowLinks","RowsLinks");

//---- 查询日历等相关信息 ----//
selectCalendar($_GET['month'],$_GET['year']);
//---- 查询标题及版权信息 ----//
selectTitCopy();
//---- 查询栏目相关信息开始 -----//
selectColumn();
//---- 查询文章,访问量,BLOG数,论平数等信息 ----//
selectSomeInfo();
//---- 查询最新5条评论信息 ----//
selectNew5comment();
//---- 最新5条友情链接 ----//
select5links();

//---- 查询是否开启统计在线人数 ----//
if(getIsCountOnlineUser())
{
	$tag = true;
	if($tag)
	{
		include_once("./include/GetOnlineUser.inc.php");
		include_once("./include/DBop.inc.php");

		$online = new GetOnlineUser($exBlog['host'], $exBlog['user'], $exBlog['password'], $exBlog['dbname']);
		$online->setDBtable($x['online']);
		$tag = false;
	}
		$num = $online->check();
		$t->set_var("onlineUserNum", $num);
}

//---- 分析栏目,评论,链接,信息 ----//
$t->parse("PutColumn","RowsColumn");       
$t->parse("PutComment","RowsComment");
$t->parse("PutLinks","RowsLinks");
$t->parse("PutCalendar","RowsCalendar");


########################### default page analysis start ###########################
if($_GET['play'] == "")
{
	$t->set_file(array("b_blog"=>"b_blog.html",
				       "Index"=>"index.html"));
	$t->set_block("b_blog","RowBlog","RowsBlog");

	selectBlog($_GET['year'],$_GET['month']);

    selectAnnounce();  //查询公告信息

	$t->parse("PutBlog","RowsBlog");
	$t->parse("OUT","Index");
    $t->p("OUT");
}
########################### comment page analysis start ###########################
elseif($_GET['play'] == "reply")
{
	$t->set_file(array("b_reply"=>"b_reply.html",
				       "Comment"=>"comment.html"));
	$t->set_block("b_reply","RowReply","RowsReply");
	if($_POST['action'] == "提交信息")
	{
		addComment($_POST['id'], $_POST['commentSort'], $_POST['name'],$_POST['email'],$_POST['homepage'],$_POST['qq'],$_POST['content'], $_POST['password'], $_POST['register']);
	}

	if($_POST['tag'] != true)
	{
		addArtVisits($_GET['id']);
	}

    showCurrentBlog($_GET['id']);
    showCurrentBlogComment($_GET['id']);

    $t->parse("PutReply","RowsReply");
    $t->parse("OUT","Comment");
    $t->p("OUT");
}
########################### analysis sort start ###########################
elseif($_GET['play'] == "view")
{
	$t->set_file(array("b_blog"=>"b_blog.html",
		               "article"=>"article.html"));
	$t->set_block("b_blog","RowBlog","RowsBlog");

    selectCurSortArt($_GET['sort']);
	
	$t->parse("PutBlog","RowsBlog");
    $t->parse("OUT","article");
    $t->p("OUT");
}
########################### show article analysis start ###########################
elseif($_GET['play'] == "show")
{
	$t->set_file("showArt","showArt.html");	

    addArtVisits($_GET['id']);
    showArticle($_GET['id'],$_GET['make'],$_GET['key']);
	
    $t->parse("OUT","showArt");
    $t->p("OUT");
}
########################### links page analysis start ###########################
elseif($_GET['play'] == "links")
{
	if($_GET['action'] == "GoTo")
    {
	    updateLinkAndHeader($_GET['url'],$_GET['id']);
    }
	$t->set_file(array("b_links"=>"b_links.html",
			   	       "links"=>"links.html"));
    $t->set_block("b_links","Row2Links","Row2sLinks");

	selectAllLinks();
	
	$t->parse("PutLinks2","Row2sLinks");
    $t->parse("OUT","links");
    $t->p("OUT");
}
########################### search page analysis start ###########################
elseif($_GET['play'] == "search")
{
	if($_GET['make']=="view")
	{
		$t->set_file("showArt","showArt.html");
        $curID=$_GET[id];
        addArtVisits($curID);
        showArticle($curID,$_GET['make'],$_GET['key']);
	
       $t->parse("OUT","showArt");
       $t->p("OUT");
	   exit;
	}

	$t->set_file(array("b_keyword"=>"b_keyword.html",
		               "i_search"=>"search.html"));
	$t->set_block("b_keyword","RowKeyword","RowsKeyword");
	searchInfo($_POST['keyword']);
	$t->parse("PutKeyword","RowsKeyword");
	$t->parse("OUT","i_search");
	$t->p("OUT");
}
########################### announce page analysis start ###########################
elseif($_GET['play'] == "announce")
{
	$t->set_file("announce","announce.html");
	
	putAnnounce($_GET['id']); //显示公告
	$t->parse("OUT","announce");
	$t->p("OUT");
}
########################### calendar page into analysis start ###########################
elseif($_GET['play'] == "calendar")
{
	if(isset($_GET['year']) && isset($_GET['month']))
    {
	    $t->set_file(array("b_blog"=>"b_blog.html",
		    		       "calendar"=>"calendar.html"));
	    $t->set_block("b_blog","RowBlog","RowsBlog");
		

	    selectCurrentYearMonthBlog($_GET['year'],$_GET['month']);
        $t->set_var("currentSort","通过指定月份访问该月BLOG记录");
	    $t->parse("PutBlog","RowsBlog");
	    $t->parse("OUT","calendar");
	    $t->p("OUT");
		exit;
    }
	$t->set_file(array("b_blog"=>"b_blog.html",
				       "article"=>"article.html"));
	$t->set_block("b_blog","RowBlog","RowsBlog");

	selectCurrentDateBlog($_GET['date']);

	$t->parse("PutBlog","RowsBlog");
	$t->parse("OUT","article");
	$t->p("OUT");
}
//显示申请友情链接页面
elseif($_GET['play'] == "regLink")
{
	if($_POST['action'] == "申请链接")
	{
		addNewLink($_POST['webTitle'], $_POST['webUrl'], $_POST['webLogo'], $_POST['webDescription']);
	}
	$t->set_file("regLink", "regLink.html");
	$t->parse("OUT", "regLink");
	$t->p("OUT");
}
?>