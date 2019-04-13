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
*\    本页说明: 外部调用文件
*\==========================================================================*/

//----- 载入数据库,模版类 ---------//
require("./include/config.inc.php");
require("./include/template.inc");
require("./include/global-A.inc.php");
require("./include/global-C.inc.php");
require("./include/ExUbb.inc.php");
$t = new template("qqimages");
//---- 查询数据库表名 ----//
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("./$langURL/public.php");
langpublic();
include("./$langURL/blogshow.php");
langblogshow();

//---- 添加网站访问量 ----//
addVisits();

$t->set_file("b_all" , "b_all.html");

$t->set_block("b_all","RowsColumn","m_column");
$t->set_block("b_all","RowsComment","m_comment");
$t->set_block("b_all","RowsNewblog","m_newblog");

//---- 查询标题及版权信息 ----//
selectTitCopy();
//---- 查询文章,访问量,BLOG数,评论数等信息 ----//
selectSomeInfo();
//---- 查询栏目相关信息开始 -----//
selectColumn();
//---- 查询最新Blog信息 ----//
selectNewblog();
//---- 查询最新评论信息 ----//
selectNewcomment();
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
$t->parse("PutColumn","m_column");       
$t->parse("PutComment","m_comment");
$t->parse("Putnewblog","m_newblog");

	$t->set_file("qq", "index.html");
	$t->parse("OUT", "qq");
	$t->p("OUT");

?>
