<?php
/*============================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\----------------------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\---------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\---------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\==========================================================================*/

header("Content-type: text/html");

//----- 简单判断是否安装 ---------//
if (!file_exists("include/config.inc.php")) {
    if (file_exists("admin/install.php")) {
	header("location: ./admin/install.php");
    }else{
	echo "对不起！您还没有安装 exBlog ，请完整的上传所有文件，然后运行安装程序！";
    }
    exit();
}

//----- 伪静态地址相关 ---------//
if($_GET['exurl'])$GLOBALS[exurl]=0;
if($_POST['exurl'])$GLOBALS[exurl]=0;

switch ($GLOBALS[exurl]) {
  case 1:
    $GLOBALS[expath]="..";
    break;
  case 2:
    $GLOBALS[expath]="../..";
    break;
  case 3:
    $GLOBALS[expath]="../../..";
    break;
  case 4:
    $GLOBALS[expath]="../../../..";
    break;
  default:
    $GLOBALS[expath]=".";
    break;
}
// $GLOBALS[expath]

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
$t = new template("./$tmpURL");

//---- 语言包 ----//
selectLangURL();
include("./$langURL/public.php");
langpublic();
include("./$langURL/blogshow.php");
langblogshow();

//查询BLOG是否暂时关闭, 关闭则下面程序片段不运行
getBlogIsRun();

//---- 添加网站访问量 ----//
addVisits();

$t->set_file("index", "index.html");
$t->set_file("b_all", "b_all.html");

$t->set_block("b_all", "RowsLinks", "m_links");
$t->set_block("b_all", "RowsColumn", "m_column");
$t->set_block("b_all", "RowsComment", "m_comment");
$t->set_block("b_all", "RowCalendar", "m_calendar");
$t->set_block("b_all", "RowsAnnounce", "m_announce");
$t->set_block("b_all", "RowsTrackback", "m_trackback");
$t->set_block("b_all", "RowsNewblog", "m_newblog");
$t->set_block("b_all", "RowsPigeonhole", "m_pigeonhole");

//---- 查询标题及版权信息 ----//
//echo "$info[siteName] <br>";
selectTitCopy();
//---- 查询日历等相关信息 ----//
selectCalendar($_GET['month'],$_GET['year']);
//---- 查询栏目相关信息开始 -----//
selectColumn();
//---- 查询文章,访问量,BLOG数,论平数等信息 ----//
selectSomeInfo();
//---- 查询最新Blog信息 ----//
selectNewblog();
//---- 查询最新评论信息 ----//
selectNewcomment();
//---- 查询最新引用信息 ----//
selectNewtrackback();
//---- 最新友情链接 ----//
selectlinks();
//---- 按月归档 ----//
pigeonhole();

selectAnnounce();

//---- 查询是否开启统计在线人数 ----//
if(getIsCountOnlineUser())
{
	$_POST['tag'] = true;
	if($_POST['tag'])
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
$t->parse("PutColumn","m_column");
$t->parse("PutComment","m_comment");
$t->parse("PutTrackback","m_trackback");
$t->parse("PutLinks","m_links");
$t->parse("PutCalendar","m_calendar");
$t->parse("Putnewblog","m_newblog");
$t->parse("Putpigeonhole","m_pigeonhole");


########################### default page analysis start ###########################
if( empty($_GET['play']) && empty($_GET['id'])
		 && empty($_POST['play']) && empty($_POST['id']) )
{
	$t->set_block("b_all", "RowsTopBlog", "m_Topblog");
	$t->set_block("b_all", "RowsBlog", "m_blog");
	selectBlog();
	$t->parse("PutBlogs", "m_blog");
		$t->set_block("b_all", "RowPage", "m_Page");
		$t->parse("m_Page","RowPage", true);
		$t->parse("PutPage", "m_Page");
	$t->parse("OUT","index");

	if($_POST['tag'] != true)
	{
		addArtVisits($id);
	}
    $t->p("OUT");
}
########################### comment page analysis start ###########################
elseif($_GET['play'] == "reply" || $_POST['play'] == "reply" || !empty($_GET['id'])
		 && $_GET['play'] != "links" && $_GET['play'] != "show")
{
	if($_POST['do'] == "add_replay") {
		addComment(intval($_POST['id']), $_POST['commentSort'], $_POST['name'],$_POST['email'],$_POST['homepage'],$_POST['qq'],$_POST['content'], $_POST['password'], $_POST['register']);
	}

	$t->set_block("b_all", "RowsBlog_show", "m_blog");
	$t->set_block("b_all", "RowsKeywords", "m_keywords");
	$t->set_block("b_all", "RowsReply", "m_replay");
	$t->set_block("b_all", "RowsTb", "m_Tb");

	$t->set_block("b_all", "RowsReplayform", "m_form");
		$t->parse("m_keywords","RowsKeywords", true);
		$t->parse("m_form","RowsReplayform", true);
			$t->set_block("b_all", "RowsKeywords_f", "m_Keywords_f");
			$t->parse("m_Keywords_f","RowsKeywords_f", true);
			$t->parse("PutBlogKeywords_f", "m_Keywords_f");
			$t->set_block("b_all", "RowsReply_f", "m_BlogComments_f");
			$t->parse("m_BlogComments_f","RowsReply_f", true);
//			$t->parse("PutBlogComments_f", "m_BlogComments_f");
			$t->set_block("b_all", "RowsTb_f", "m_Tb_f");
			$t->parse("m_Tb_f","RowsTb_f", true);
//			$t->parse("PutBlogTrackbacks_f", "m_Tb_f");
			$t->set_block("b_all", "RowsReplayform_f", "m_Replayform_f");
			$t->parse("m_Replayform_f","RowsReplayform_f", true);
			$t->parse("PutCommentForm_f", "m_Replayform_f");

//	 if($_POST['tag'] != true) {
	 	addArtVisits(intval($_GET['id']));
//	 }

	showCurrentBlog(intval($_GET['id']),$_GET['make'],$_GET['key']);
	showCurrentBlogComment(intval($_GET['id']), $GLOBALS[nocom]);
	showCurrentTrackback(intval($_GET['id']), $GLOBALS[nocom]);
	
	$t->parse("PutBlogs", "m_blog");
	$t->parse("PutBlogKeywords", "m_keywords");
if ($GLOBALS[nocom] != "1") {
			$t->parse("PutBlogComments_f", "m_BlogComments_f");
			$t->parse("PutBlogTrackbacks_f", "m_Tb_f");
	$t->parse("PutBlogComments", "m_replay");
	$t->parse("PutBlogTrackbacks", "m_Tb");
	$t->parse("PutCommentForm", "m_form");
}
    $t->parse("OUT", "index");
    $t->p("OUT");
}
########################### analysis sort start ###########################
elseif($_GET['play'] == "view")
{
	$t->set_block("b_all", "RowsBlog", "m_blog");
    selectCurSortArt($_GET['sort']);
	$t->parse("PutSortBlogs","m_blog");
		$t->set_block("b_all", "RowPage", "m_Page");
		$t->parse("m_Page","RowPage", true);
		$t->parse("PutPage", "m_Page");
    $t->parse("OUT","index");
    $t->p("OUT");
}
########################### show article analysis start ###########################
elseif($_GET['play'] == "show")
{
	$t->set_file("showArt","showArt.html");	

    addArtVisits(intval($_GET['id']));
    showArticle(intval($_GET['id']));
	
    $t->parse("OUT","showArt");
    $t->p("OUT");
}
########################### links page analysis start ###########################
elseif($_GET['play'] == "links")
{
	if($_GET['action'] == "GoTo") {
	    updateLinkAndHeader($_GET['url'], intval($_GET['id']));
    }
			$t->set_block("b_all", "Rows2Links_f", "m_2Links_f");
			$t->parse("m_2Links_f","Rows2Links_f", true);
			$t->parse("PutLinks2_f", "m_2Links_f");
    $t->set_block("b_all", "Rows2Links", "m_links");
	selectAllLinks();
	$t->parse("PutLinks2", "m2_links");
    $t->parse("OUT","index");
    $t->p("OUT");
}
########################### search page analysis start ###########################
elseif($_GET['play'] == "search" || $_POST['play'] == "search")
{
	if($_GET['make']=="view")
	{
	$t->set_block("b_all", "RowsBlog_show", "m_blog");
	$t->set_block("b_all", "RowsKeywords", "m_keywords");
	$t->set_block("b_all", "RowsReply", "m_replay");
	$t->set_block("b_all", "RowsTb", "m_Tb");

	$t->set_block("b_all", "RowsReplayform", "m_form");
		$t->parse("m_keywords","RowsKeywords", true);
		$t->parse("m_form","RowsReplayform", true);
			$t->set_block("b_all", "RowsKeywords_f", "m_Keywords_f");
			$t->parse("m_Keywords_f","RowsKeywords_f", true);
			$t->parse("PutBlogKeywords_f", "m_Keywords_f");
			$t->set_block("b_all", "RowsReply_f", "m_BlogComments_f");
			$t->parse("m_BlogComments_f","RowsReply_f", true);
//			$t->parse("PutBlogComments_f", "m_BlogComments_f");
			$t->set_block("b_all", "RowsTb_f", "m_Tb_f");
			$t->parse("m_Tb_f","RowsTb_f", true);
//			$t->parse("PutBlogTrackbacks_f", "m_Tb_f");
			$t->set_block("b_all", "RowsReplayform_f", "m_Replayform_f");
			$t->parse("m_Replayform_f","RowsReplayform_f", true);
			$t->parse("PutCommentForm_f", "m_Replayform_f");
        $GLOBALS['curID']=intval($_GET['id']);
        addArtVisits($GLOBALS['curID']);
		showCurrentBlog($GLOBALS['curID'],$_GET[make],$_GET[key]);
		showCurrentBlogComment($GLOBALS['curID']);
		showCurrentTrackback($GLOBALS['curID']);

	$t->parse("PutBlogs", "m_blog");
	$t->parse("PutBlogKeywords", "m_keywords");
if ($GLOBALS[nocom] != "1") {
			$t->parse("PutBlogComments_f", "m_BlogComments_f");
			$t->parse("PutBlogTrackbacks_f", "m_Tb_f");
	$t->parse("PutBlogComments", "m_replay");
	$t->parse("PutBlogTrackbacks", "m_Tb");
	$t->parse("PutCommentForm", "m_form");
}
		$t->parse("OUT","index");
		$t->p("OUT");
	   exit;
	}

			$t->set_block("b_all", "RowsKeyword_f", "m_Keyword_f");
			$t->parse("m_Keyword_f","RowsKeyword_f", true);
			$t->parse("PutSearch_f", "m_Keyword_f");
	$t->set_block("b_all","RowsKeyword","m_keyword");
	searchInfo($_POST['keyword']);
	$t->parse("PutSearch","m_keyword");
	$t->parse("OUT","index");
	$t->p("OUT");
}
########################### announce page analysis start ###########################
elseif($_GET['play'] == "announce")
{
	$t->set_file("announce","announce.html");
	
	putAnnounce(intval($_GET['anid'])); //显示公告
	$t->parse("OUT","announce");
	$t->p("OUT");
}
########################### calendar page into analysis start ###########################
elseif($_GET['play'] == "calendar")
{
	$t->set_block("b_all", "RowsBlog", "m_blog");

	if(isset($_GET['year']) && isset($_GET['month']))
	{
	    selectCurrentYearMonthBlog($_GET['year'],$_GET['month']);
        $t->set_var("currentSort", $lang[145]);
	}elseif(isset($_GET['date']))
	{
	selectCurrentDateBlog($_GET['date']);
	}
		$t->set_block("b_all", "RowPage", "m_Page");
		$t->parse("m_Page","RowPage", true);
		$t->parse("PutPage", "m_Page");
	    $t->parse("PutBlogs","m_blog");
	    $t->parse("OUT","index");
	$t->p("OUT");
}
########################### 显示关于页面 ###########################
elseif($_GET['play'] == "about")
{
	$t->set_block("b_all","RowsAbout","m_About");
	$t->parse("m_About","RowsAbout",true);
	$t->parse("PutAbout","m_About");

	$aboutInfo=selectAbout();            ### 查询about信息

	$name = htmlspecialchars($aboutInfo['name']);
	$age = $aboutInfo['age'];
	$email = $aboutInfo['email'];
	$qq = $aboutInfo['qq'];
	$icq = $aboutInfo['icq'];
	$msn = $aboutInfo['msn'];
	$description = $aboutInfo['description'];

	$t->set_var(array(	"currentSort"=>$lang[139],
				"name"=>$name,
				"age"=>$age,
				"email"=>$email,
				"qq"=>$qq,
				"icq"=>$icq,
				"msn"=>$msn,
				"description"=>$description));

    $t->parse("OUT","index");
    $t->p("OUT");
}
########################### 用户显示 ###########################
elseif($_GET['play'] == "user")
{ 
	$t->set_block("b_all","RowsUser","m_User");
		$t->set_block("b_all","RowsUser_f","m_User_f");
		$t->parse("m_User_f","RowsUser_f", true);

	selectAdmin();                        ##查询当前超级用户
	selectUser();                         ##查询当前普通用户
	selectVisitor();                      ##查询当前访客用户

	user_list();

	$t->parse("PutAbout_list","m_User");
	$t->parse("PutAbout","m_User_f");
	$t->set_var("currentSort" , $lang[140]);

    $t->parse("OUT","index");
    $t->p("OUT");
}
########################### 显示申请友情链接页面 ###########################
elseif($_POST['play'] == "regLink" || $_GET['play'] == "regLink")
{
	if($_POST['action'] == "actreglink")
	{
		addNewLink($_POST['webTitle'], $_POST['webUrl'], $_POST['webLogo'], $_POST['webDescription']);
	}
		$t->set_block("b_all", "RowsReglink", "m_Reglink");
		$t->parse("m_Reglink","RowsReglink", true);
		$t->parse("PutRegLink", "m_Reglink");
		$t->set_var("currentSort",$lang[54]);
	$t->parse("OUT", "index");
	$t->p("OUT");
}
########################### 用户登陆 ###########################
elseif($_POST['play'] == "login")
{
 	if($_POST['action'] == "actlogin")
 	{
 		userLogin($_POST['user'], $_POST['passwd'], 1, 1, "index.php", "show");
 	}
}
########################### 退出登陆 ###########################
elseif($_GET['play'] == "exit")
{
 	userLogout("index.php", "show");
}
########################### 提交无效值返回首页 ###########################
else
{
 	header("location: ./index.php");
}
?>
