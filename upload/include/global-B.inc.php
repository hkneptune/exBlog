<?php
/*============================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio,  2004 - 2005. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 后台常用函数
*\===========================================================================*/

######### 检查标题是否合法 #########
function checkSubject($iTitle)
{
	global $langadfun;
	if(trim($iTitle) == "")
	{
		$result="$langadfun[5]";
		return $result;
	}
}

###### 检查分类名长度 ######
function checkSortLen($iSort)
{
	global $langadfun;
	if(trim($iSort) == "")
	{
		$result="$langadfun[6]";
		return $result;
	}
	if(strlen($iSort) > 15)
	{
		$result="$langadfun[7]";
		return $result;
	}
}

######### 分析作者EMAIL #########
function splitInfo()
{
	$tmp=$_COOKIE['exBlogUser'];
	$result=explode("||",$tmp);
	return $result;
}

######### search current Language #########
function searchCurLanguage($current)
{
	global $x;
	$query=mysql_query("SELECT langURL FROM `$x[global]`");
	$tmp=mysql_fetch_array($query);
	$tmp2=explode("/",$tmp['langURL']);

	if($current == $tmp2[1])
	{
		echo "selected";
	}
}

######### search current templates #########
function searchCurTemplates($current)
{
	global $x;
	$query=mysql_query("SELECT tmpURL FROM `$x[global]`");
	$tmp=mysql_fetch_array($query);
	$tmp2=explode("/",$tmp['tmpURL']);

	if($current == $tmp2[1])
	{
		echo "selected";
	}
}

######### read Language dir ######
function readLanguage()
{
	if($handle=opendir("../language"))
	{
		while(false !== $file=readdir($handle))
		{
			if($file != "." && $file != "..")
			{
				$dir[]=$file;
			}
		}
	}
	closedir($handle);
	return $dir;
}

######### read templates dir ######
function readTemplates()
{
	if($handle=opendir("../templates"))
	{
		while(false !== $file=readdir($handle))
		{
			if($file != "." && $file != "..")
			{
				$dir[]=$file;
			}
		}
	}
	closedir($handle);
	return $dir;
}

######### 检查链接名字是否合法 #########
function checkHomepage($iHomepage)
{
	global $langadfun;
	if(trim($iHomepage) == "")
	{
		$result="$langadfun[8]";
		return $result;
	}
	elseif(strlen($iHomepage) > 20)
	{
		$result="$langadfun[9]";
		return $result;
	}
	elseif(eregi("[<>{}(),%#|^&!`$]",$iHomepage))
	{
		$result="$langadfun[10]";
		return $result;
	}
}

######### 检查链接URL是否合法 #########
function checkURL($iLink)
{
	global $langadfun;
	if(trim($iLink) == "")
	{
		$result="$langadfun[11]";
		return $result;
	}
	if(!eregi("^http://.+\..+$",$iLink))
	{
		$result.="$langadfun[12]";
		return $result;
	}
}

######### 检查链接图片格式是否合法 Code Start #########
		   function checkIMGname($iLogoURL){
	global $langadfun;
			if ($iLogoURL!="") {
	    
				$imgEndName = strrchr($iLogoURL,'.');
 
				if ($imgEndName!=".gif" and $imgEndName!=".jpg" and $imgEndName!=".png"){

					$result="$langadfun[13]";
					return $result;	
				} 
			}
		   }

######### 检查install文件是否存在 ##########
function installExists()
{
	global $langadfun;
	if(file_exists("install.php"))
	{
		$str="$langadfun[14]";
		$str.="$langadfun[15]";
		$str.="$langadfun[16]";
		return $str;
	}
}

######### 编辑BLOG时选择默认分类 #########


/**
 *	在提供下拉列表框时, 根据数据库内容选项相应的项
 *	@param	$str 下拉框当前值
 *	@author elliott [at] 2004-09-06 
 */
function selected2isCountOnlineUser($str)
{
	global $x;

	$query = mysql_query("SELECT * FROM `$x[global]` WHERE isCountOnlineUser = '$str'");
	if(@mysql_num_rows($query))
	{
		echo "selected";
	}
}
function selected2GDswitch($str)
{
	global $x;

	$query = mysql_query("SELECT * FROM `$x[global]` WHERE GDswitch = '$str'");
	if(@mysql_num_rows($query))
	{
		echo "selected";
	}
}
function selected2exurl($str)
{
	global $x;

	$query = mysql_query("SELECT * FROM `$x[global]` WHERE exurlon = '$str'");
	if(@mysql_num_rows($query))
	{
		echo "selected";
	}
}
function editBlogSelSort($curSort, $blogSort)
{
	if($curSort == $blogSort)
	{
		echo "selected";
	}
}
/**
 *	在提供下拉列表框时, 根据数据库内容选项相应的项
 *	@param	$str 下拉框当前值
 *	@author elliott [at] 2004-09-06 
 */
function selected2activeRun($str)
{
	global $x;

	$query = mysql_query("SELECT * FROM `$x[global]` WHERE activeRun = '$str'");
	if(@mysql_num_rows($query))
	{
		echo "selected";
	}
}


######### 分析是否能过查找显示编辑BLOG #########
function analysisPostBlogID($pID, $gID)
{
	if($_POST['action'] == "actediablog")
	{
		echo $pID;
	}
	else
	{
		echo $gID;
	}
}

######### 删除install文件 ########
function delInstall()
{
	global $langadfun;
	chmod("../admin",0775);
	chmod("../admin/install.php",0777);
	unlink("../admin/install.php");
	echo "$langadfun[17]";
}

######### 取得服务器相关信息 #########
function getServerInfo()
{
	$server[]=getenv("SERVER_SOFTWARE");
	//$server[]=
	$sql=mysql_query("SELECT VERSION()");
	//echo $sql;
	return $server;
}

######### 取得数据库相关信息 #########
function getMysqlInfo()
{
$sqlinfo=mysql_fetch_array(mysql_query("SELECT VERSION() AS version"));
return $sqlinfo;
}

######### 查询论坛常规信息 #########
function selectGlobal()
{
	global $x;
	$queryGlobal="SELECT * FROM `$x[global]`";
	$globalInfo=mysql_fetch_array(mysql_query($queryGlobal));
	return $globalInfo;
}

/* 查询待编辑链接 */
function selectEditLink($iID)
{
    global $editLink, $x, $langadfun;
    $query="SELECT * FROM `$x[links]` WHERE id='$iID'";
    $editLink=mysql_fetch_array(mysql_query($query)) or die("$langadfun[18]");
}

######### 查询about相关信息 ########
function selectAbout()
{
	global $x;
	$queryAbout="SELECT * FROM `$x[aboutme]`";
	$aboutInfo=mysql_fetch_array(mysql_query($queryAbout));
	return $aboutInfo;
}

/**
 *	检测所有已通过验证的友情链接记录
 *	@param null
 *	@return 已经过mysql_query()查询的资源,直接供mysql_fetch_array()使用
 *  @author elliott [at] 2004-08-29
 */
function selectPassLinks()
{
    global $x, $langadfun;

    $query="SELECT * FROM `$x[links]` WHERE visible = '1' ORDER BY id DESC";
    $resource=mysql_query($query) or die("$langadfun[19]");

	return $resource;
}

/**
 *	检测所有未通过验证的友情链接记录
 *	@param null
 *	@return 已经过mysql_query()查询的资源,直接供mysql_fetch_array()使用
 *  @author elliott [at] 2004-08-29
 */
function selectUnpassLinks()
{
	global $x, $langadfun;

	$query = "SELECT * FROM `$x[links]` WHERE visible = '0' ORDER BY id DESC";
	$resource = mysql_query($query) or die("$langadfun[20]");

	return $resource;
}

/* 删除链接 */
function deleteLinks($iID)
{
	global $x, $langadfun;
    $query="DELETE FROM `$x[links]` WHERE id='$iID'";
    @mysql_query($query) or die(", $langadfun[21]");
    $showMsg=", $langadfun[22]";
    $showReturn="./editlinks.php?action=edit";
    display($showMsg, $showReturn);
}

/* 更新链接记录 */
function updateLink($iHomepage, $iUrl, $iLogoURL, $iDescription, $iID, $visible)
{
	global $x, $langadfun;
	$result=checkHomepage($iHomepage);
	$result.=checkURL($iUrl);
	$result.=checkIMGname($iLogoURL);
	if($result)
	{
		showError($result);
	}
    $query="UPDATE `$x[links]` SET homepage='$iHomepage',url='$iUrl',";
    $query.="logoURL='$iLogoURL',description='$iDescription', visible = '$visible' WHERE id='$iID'";
    @mysql_query($query) or die("$langadfun[23]");
    $showMsg="$langadfun[24]";
    $showReturn="./editlinks.php?action=edit";
    display($showMsg, $showReturn);
}

/**
 *	更新BLOG全局信息
 *	@param $iSiteName 站点名称
 *	@param $iSiteUrl  站点URL
 *	@param $iCopyright 站点版权
 *  @param $iTmpURL 模版相对路径
 *	@param $activeRun 站点是否运行
 *	@param $unactiveRunMessage 站点未运行时显示消息
 *	@param $isCountOnlineUser 是否统计在线用户
 *
 *	@return null
 *	@author elliott [at] 2004-08-30
 */

function updateGlobal($iSiteName,$iSiteUrl,$iCopyright,$iTmpURL, $activeRun, $unactiveRunMessage, $isCountOnlineUser, $description, $webmaster, $GDswitch, $exurlon, $sitekeyword, $summarynum, $alltitlenum, $listblognum, $listallnum, $languageURL)
{
	global $x, $langadfun;
	$qUpdateGlobal  = "UPDATE `$x[global]` SET siteName='$iSiteName', siteUrl='$iSiteUrl',";
	$qUpdateGlobal .= "copyright='$iCopyright', tmpURL='$iTmpURL', activeRun = '$activeRun',";
	$qUpdateGlobal .= "unactiveRunMessage = '$unactiveRunMessage', isCountOnlineUser = '$isCountOnlineUser', Description = '$description', Webmaster = '$webmaster', GDswitch = '$GDswitch', exurlon = '$exurlon', sitekeyword = '$sitekeyword', summarynum = '$summarynum', alltitlenum = '$alltitlenum', listblognum = '$listblognum', listallnum = '$listallnum', langURL = '$languageURL'";
	//@mysql_query($qUpdateGlobal) or die("更新常规信息时失败!");
	@mysql_query($qUpdateGlobal) or die(mysql_error());
	$showMsg="$langadfun[25]";
    $showReturn="./other.php";
    display($showMsg, $showReturn);
}

######### 添加公告 #########
function addAnnounce($iTitle, $iContent, $iAuthor, $iEmail)
{
	global $x, $langadfun;

	$result=checkSubject($iTitle);
	$result.=checkUser($iAuthor);
	$result.=checkEmail($iEmail);
	$result.=checkContent($iContent);
	if($result)
	{
		showError($result);
	}

	$query="INSERT INTO `$x[announce]` (title,content,author,email,addtime)";
	$query.=" VALUES('$iTitle','$iContent','$iAuthor','$iEmail',NOW())";
	@mysql_query($query) or die(mysql_error());
	$showMsg="$langadfun[26]";
    $showReturn="./editannounce.php?action=add";
    display($showMsg, $showReturn);
}

/* 查询待编辑公告 */
function selectEditAnnounce($iID)
{
global $resultAnnounce, $x, $langadfun;
$query="SELECT * FROM `$x[announce]` WHERE id='$iID'";
$resultAnnounce=mysql_fetch_array(mysql_query($query)) or die("$langadfun[27]");
}

/* 更新公告 */
function updateAnnounce($iTitle, $iContent, $iAuthor, $iEmail, $iID)
{
global $x, $langadfun;
$result=checkSubject($iTitle);
$result.=checkUser($iAuthor);
$result.=checkContent($iContent);
if($result)
{
showError($result);
}
$query="UPDATE `$x[announce]` SET title='$iTitle',content='$iContent',";
$query.="author='$iAuthor',email='$iEmail' WHERE id='$iID'";
@mysql_query($query) or die("$langadfun[28]");
$showMsg="$langadfun[29]";
$showReturn="./editannounce.php?action=edit";
display($showMsg, $showReturn);
}

/* update blog content */
function updateBlog($title, $content, $author, $email, $sort, $ID, $addtime, $keyword, $summarycontent, $weather, $top, $hidden, $html)
{
	global $x, $langadfun;

	$check = new CheckData();

	if(!$check->checkOther($title, true, 0))
		$ret = "$langadfun[30]";
	if(!$check->checkName($author, true, false))
		$ret .= "$langadfun[31]";
	if(!$check->checkEmail($email, false))
		$ret .= "$langadfun[32]";
	if(!$check->checkOther($content, true, 6))
		$ret .= "$langadfun[33]";

	if($ret)
	{
		showError($ret);
	}

	if($iSort == "NULL")
	{
		showError("$langadfun[34]");
	}
	$qUpdateArt="UPDATE `$x[blog]` SET title='$title', content='$content',";
	$qUpdateArt.="author='$author', email='$email', sort='$sort', addtime='$addtime', keyword='$keyword', summarycontent='$summarycontent', weather = '$weather', top = '$top', hidden = '$hidden', html = '$html' WHERE id='$ID'";
	@mysql_query($qUpdateArt) or die("$langadfun[35]");
	$showMsg="$langadfun[36]";
    $showReturn="./editblog.php?action=list";
    display($showMsg, $showReturn);
}

/* update comment content */
function updateComment($id, $content, $author, $addtime ,$adminreply ,$email ,$homepage ,$qq )
{
	global $x, $langadfun;
	
	if($adminreply =="")
	{
	$qUpdateComment="UPDATE `$x[comment]` SET content='$content', author='$author', addtime='$addtime',";
	$qUpdateComment.="email='$email', homepage='$homepage', qq='$qq' WHERE id='$id'";
	}
	else
	{
	$icontent=$content.'[reply][b]'.$langadfun[37].'[/b]'.$adminreply.'[/reply]';
	$qUpdateComment="UPDATE `$x[comment]` SET content='$icontent', author='$author', addtime='$addtime',";
	$qUpdateComment.="email='$email', homepage='$homepage', qq='$qq' WHERE id='$id'";
	}
	@mysql_query($qUpdateComment) or die("$langadfun[38]");
	$showMsg="$langadfun[39]";
    $showReturn="./editcomment.php?action=edit";
    display($showMsg, $showReturn);
}

/* update trackback content */
function updateTrackback($id, $url, $blog_name, $title ,$excerpt ,$addtime )
{
	global $x, $langadfun;

	$qUpdateTrackback="UPDATE `$x[trackback]` SET url='$url', blog_name='$blog_name', title='$title',";
	$qUpdateTrackback.="excerpt='$excerpt', addtime='$addtime' WHERE id='$id'";

	@mysql_query($qUpdateTrackback) or die("$langadfun[38]");
	$showMsg="$langadfun[39]";
    $showReturn="./edittrackback.php?action=edit";
    display($showMsg, $showReturn);
}

/* 删除相应的 trackback 信息 */
function deleteTrackback($iID)
{
	global $x, $langadfun;
	if($iID=="")
	{
		showError("$langadfun[90]");
	}
	$query3=mysql_query("SELECT * FROM `$x[trackback]` WHERE id='$iID'");
	$num=mysql_num_rows($query3);
	if($num == "")
	{
		showError("$langadfun[91]");
	}

    $query="DELETE FROM `$x[trackback]` WHERE id='$iID'";
    @mysql_query($query) or die("$langadfun[92]");
    $showMsg="$langadfun[93]";
    $showReturn="./edittrackback.php?action=edit";
    display($showMsg, $showReturn);
}

/* 查询当前要修改 trackback 相关信息 */
function selectOneTrackbackInfo($iID)
{
	global $x;
	$querySelectOneTrackbackInfo="SELECT * FROM `$x[trackback]` WHERE id='$iID'";
	$trackbackInfo=mysql_fetch_array(mysql_query($querySelectOneTrackbackInfo));
	return $trackbackInfo;
}

/* edit trackback page */
function selectTrackbackModify()
{
    global $resultTrackback, $page, $total_page, $x;
	$page_size=30;
    $queryTrackback="SELECT * FROM `$x[trackback]` ORDER BY id DESC";
	$result=mysql_query($queryTrackback);
	$trackbackSum=mysql_num_rows($result);
	$total_page = ceil($trackbackSum/$page_size);
	$page=$_GET['page'];
    if(!$page) $page=1;
    $start = ($page-1)*$page_size;
    //判断当前页号
    if(!$page)
	{
		$page=1;
	}
	else
	{
		$page+=0;
		$page=floor($page);
		if($page>$total_page) $page=$total_page;
		if($page<1) $page=1;
	}
    $query="SELECT * FROM `$x[trackback]` ORDER BY id DESC limit $start,$page_size";
    $resultTrackback=mysql_query($query);	
}

function cutTrackbackPage()
{
	global $page,$total_page, $langadfun;
	$prev_page=$page-1;
	$next_page=$page+1;
	if($prev_page >= 1)
	{
		$prev_page="<a href=edittrackback.php?action=edit&page=$prev_page>$langadfun[108]</a>";
	}
	else
	{
		$prev_page="$langadfun[108]";
	}
	if($next_page>$total_page)
	{
		$next_page="$langadfun[109]";
	}
	else
	{
		$next_page="<a href=edittrackback.php?action=edit&page=$next_page>$langadfun[109]</a>";
	}
	$pageshow="$langadfun[110] <font color='red'>$page</font> $langadfun[111], $langadfun[112] <font color='red'>$total_page</font> $langadfun[111]  ";
	$pageshow.="<a href=edittrackback.php?action=edit>$langadfun[113]</a> $prev_page $next_page <a href=edittrackback.php?action=edit&page=$total_page>$langadfun[114]</a>";
	return $pageshow;
}

######### read upload dir ######
function readUpload($upload)
{
	if($handle=opendir($upload))
	{
		while(false !== $file=readdir($handle))
		{
			if($file != "." && $file != "..")
			{
				$dir[]=$file;
			}
		}
	}
	closedir($handle);
	return $dir;
}

/* 查询所有分类列表 */
function selectAllSort()
{
    global $resultSort,$x, $langadfun;
    $query="SELECT * FROM `$x[sort]` ORDER BY id DESC";
    $resultSort=mysql_query($query) or die("$langadfun[40]");
}

/* 添加新文章分类 */
function addSort($ienName, $icnName, $iDescription)
{
	global $x, $langadfun;
	$result=checkSortLen($ienName);
	$result.=checkSortLen($icnName);
	if($result)
	{
		showError($result);
	}
    $query="SELECT * FROM `$x[sort]` WHERE enName='$ienName' OR cnName='$icnName'";
    $result=mysql_num_rows(mysql_query($query));
    if($result > 0)
	{
         showError("$langadfun[41]");
    }
	$query="INSERT INTO `$x[sort]` (enName, cnName, description)";
    $query.=" VALUES('$ienName', '$icnName', '$iDescription')";
    @mysql_query($query) or die("$langadfun[42]");
    $showMsg="$langadfun[43]";
    $showReturn="./editsort.php?action=add";
    display($showMsg, $showReturn);
}

/* 查询BLOG数,评论数,访问数 后台用 */
function InfoQuery()
{
	global $x, $commentNum, $blogNum, $visited, $tbNum;
	$sql=mysql_query("SELECT id FROM `$x[comment]`");
	$commentNum=mysql_num_rows($sql);  //论评总数
	$sql=mysql_query("SELECT id FROM `$x[trackback]`");
	$tbNum=mysql_num_rows($sql);     //Blog总数
	$sql=mysql_query("SELECT id FROM `$x[blog]`");
	$blogNum=mysql_num_rows($sql);     //Blog总数
	$sql=mysql_query("SELECT * FROM `$x[visits]`");
	$tmp=mysql_fetch_array($sql);
	$visited=$tmp['visits'];           //访问数
}

/* 选择一个待编辑分类 */
function selectOneSort($iEditSort)
{
	global $x, $langadfun;
	if($iEditSort == "NULL")
	{
		showError("$langadfun[44]");
	}
    global $resultOneSort;
    $query="SELECT * FROM `$x[sort]` WHERE cnName='$iEditSort'";
	$resultOneSort=mysql_fetch_array(mysql_query($query)) or die("$langadfun[45]");
}

/* 更新分类 */
function updateSort($iCnName,$iEnName,$iDescription,$iOldID,$iID)
{
	global $x, $langadfun;
	$result=checkSortLen($iCnName);
	$result.=checkSortLen($iEnName);
	if($result)
	{
		showError($result);
	}
    //-----  更新分类名 -------//
	$query="UPDATE `$x[sort]` SET cnName='$iCnName',enName='$iEnName',";
	$query.="description='$iDescription' WHERE id='$iID'";
	@mysql_query($query) or die("$langadfun[46]");

	$showMsg="$langadfun[49]";
    $showReturn="./editsort.php?action=edit";
    display($showMsg, $showReturn);
}

/* 排序分类 * by sheeryiro*/
function updateSortrank($ioldID,$inewID)
{
	global $x, $langadfun;
      if($ioldID==$inewID)
	{
		showError("$langadfun[50]");
	}
    //-----  被替换分类暂时移位 -------//
    $query="UPDATE `$x[sort]` SET `id` = '0' WHERE `id` = '$inewID' LIMIT 1 ";
    @mysql_query($query) or die("$langadfun[51]");
	//----- 更新原有文章分类名 ------//
	$query2="UPDATE `$x[blog]` SET sort='0' WHERE sort='$inewID'";
	@mysql_query($query2) or die("$langadfun[47]");
	$query3="UPDATE `$x[comment]` SET commentSort='0' WHERE commentSort='$inewID'";
	@mysql_query($query3) or die("$langadfun[48]");
	
    //----- 替换分类到被替换分类位置 ------//
    $query2="UPDATE `$x[sort]` SET `id` = '$inewID' WHERE `id` = '$ioldID' LIMIT 1";
    @mysql_query($query2) or die("$langadfun[52]");
	//----- 更新原有文章分类名 ------//
	$query2="UPDATE `$x[blog]` SET sort='$inewID' WHERE sort='$iOldID'";
	@mysql_query($query2) or die("$langadfun[47]");
	$query3="UPDATE `$x[comment]` SET commentSort='$inewID' WHERE commentSort='$iOldID'";
	@mysql_query($query3) or die("$langadfun[48]");

    //----- 还原被替换分类到新位置 ------//
    $query3="UPDATE `$x[sort]` SET `id` = '$ioldID' WHERE `id` = '0' LIMIT 1";
    @mysql_query($query3) or die("$langadfun[53]");
	//----- 更新原有文章分类名 ------//
	$query2="UPDATE `$x[blog]` SET sort='$ioldID' WHERE sort='0'";
	@mysql_query($query2) or die("$langadfun[47]");
	$query3="UPDATE `$x[comment]` SET commentSort='$ioldID' WHERE commentSort='0'";
	@mysql_query($query3) or die("$langadfun[48]");

	$showMsg="$langadfun[54]";
    $showReturn="./editsort.php?action=rank";
    display($showMsg, $showReturn);

}
/* 更新分类 * by sheeryiro*/
function updateLinkrank($ioldID,$inewID)
{
	global $x, $langadfun;
      if($ioldID==$inewID)
	{
		showError("$langadfun[55]");
	}
    //-----  被替换链接暂时移位 -------//
	$query="UPDATE `$x[links]` SET `id` = '0' WHERE `id` = '$inewID' LIMIT 1 ";
	@mysql_query($query) or die("$langadfun[56]");
	
	//----- 替换链接到被替换链接位置 ------//
	$query2="UPDATE `$x[links]` SET `id` = '$inewID' WHERE `id` = '$ioldID' LIMIT 1";
	@mysql_query($query2) or die("$langadfun[57]");
	//----- 还原被替换链接到新位置 ------//
	$query3="UPDATE `$x[links]` SET `id` = '$ioldID' WHERE `id` = '0' LIMIT 1";
	@mysql_query($query3) or die("$langadfun[58]");

	$showMsg="$langadfun[59]";
    $showReturn="./editlinks.php?action=rank";
    display($showMsg, $showReturn);
	
}

######### 删除所选公告 #########
function deleteAnnounce($iID)
{
	global $x, $langadfun;
	$query="DELETE FROM	`$x[announce]` WHERE id='$iID'";
	@mysql_query($query) or die("$langadfun[60]");
	$showMsg="$langadfun[61]";
    $showReturn="./editannounce.php?action=edit";
    display($showMsg, $showReturn);
}

/* 删除所选分类 */
function deleteSort($iSort)
{
	global $x, $langadfun;
	if($iSort == "NULL")
	{
		showError("$langadfun[62]");
    }
	$querysort="SELECT * FROM `$x[sort]` WHERE cnName='$iSort'";
	$resultSort=mysql_fetch_array(mysql_query($querysort));
	$id=$resultSort['id'];

	$query="DELETE FROM `$x[sort]` WHERE cnName='$iSort'";
    @mysql_query($query) or die("$langadfun[63]");

    $query2="DELETE FROM `$x[blog]` WHERE sort='$id'";
    @mysql_query($query2) or die("$langadfun[64]");
	$query3="DELETE FROM `$x[comment]` WHERE commentSort='$id'";
	@mysql_query($query3) or die("$langadfun[65]");

    $showMsg="$langadfun[66]";
    $showReturn="./editsort.php?action=edit";
    display($showMsg, $showReturn);
}


/* update myself description */
function updateInfo($iName, $iAge, $iEmail, $iQq, $iIcq, $iMsn, $iDescription)
{
	global $x, $langadfun;
	$query="UPDATE `$x[aboutme]` SET name='$iName', age='$iAge', email='$iEmail', qq='$iQq',";
    $query.="icq='$iIcq', msn='$iMsn', description='$iDescription'";
    @mysql_query($query) or die("$langadfun[67]");
    $showMsg="$langadfun[68]";
    $showReturn="./editabout.php?action=edit";
    display($showMsg, $showReturn);
}


/* add one links */
function addLinks($iHomepage, $iUrl, $iLogoURL, $iDescription, $iVisible)
{
	global $x, $langadfun;
	$result=checkHomepage($iHomepage);
	$result.=checkURL($iUrl);
	$result.=checkIMGname($iLogoURL);
	if($result)
	{
		showError($result);
	}
	//$result.=checkDescription($iDescription);
    $query="SELECT * FROM `$x[links]` WHERE homepage='$iHomepage' AND url='$iUrl' ORDER BY id DESC LIMIT 0,1";
    $result=mysql_num_rows(mysql_query($query));
    if($result > 0)
    {
		showError("$langadfun[69]");
    }
    $query="INSERT INTO `$x[links]` (homepage, logoURL, url, description, visits, visible)";
    $query.=" VALUES('$iHomepage', '$iLogoURL', '$iUrl', '$iDescription','0', '$iVisible')";
    @mysql_query($query) or die("$langadfun[70]");
    $showMsg="$langadfun[71]";
    $showReturn="./editlinks.php?action=add";
    display($showMsg, $showReturn);
}

/**
 *	添加一篇新的日志
 *	@param $title 日志的标题
 *	@param $content  日志的内容
 *	@param $author 日志的作者
 *  @param $email 作者email地址
 *	@param $sort 日志的分类
 *	@param $weather 添加这篇日志当天的天气
 *	@param $top 是否置顶
 *	@return null
 *	@author elliott [at] 2004-08-30
 *	last modify: 2004-09-03
 */
function addBlog($title, $content, $author, $email, $sort, $addtime, $keyword, $summarycontent, $weather, $top, $hidden, $html, $trackback)
{
	global $x, $langadfun;

	$check = new CheckData();

	if(!$check->checkOther($title, true, 0))
		$ret = "$langadfun[72]";
	if(!$check->checkName($author, true, false))
		$ret .= "$langadfun[73]";
	if(!$check->checkEmail($email, false))
		$ret .= "$langadfun[74]";
	if(!$check->checkOther($content, true, 6))
		$ret .= "$langadfun[75]";
########################
if ($trackback) {
	if(!$check->checkUrl($trackback, true))
		$ret = "TrackBack Ping URL 格式不对！";
}
########################

	if($ret)
	{
		showError($ret);
	}

	if($iSort == "NULL")
	{
		showError("$langadfun[76]");
	}
	$query="SELECT * FROM `$x[blog]` WHERE title='$iTitle' AND content='$iContent' AND sort='$iSort' ORDER BY id DESC LIMIT 0,1";
    $result2=mysql_num_rows(mysql_query($query));
    if($result2 > 0)
    {
         showError('$langadfun[77]');
    }

	$iAuthor=trim($iAuthor);

	if(!$addtime){
		$addtime=date(Y."-".m."-".d." ".H.":".i.":".s);	//$addtime=now();
	}
		$query="INSERT INTO `$x[blog]` (title, content, author, email, sort, visits, addtime, keyword, summarycontent, weather, top, hidden, html)";
		$query.=" VALUES('$title', '$content', '$author', '$email', '$sort', '0', '$addtime', '$keyword', '$summarycontent', '$weather', '$top', '$hidden', '$html')";
		@mysql_query($query) or die(mysql_error());

	if($html=="0")
	{
		$showReturn="./editblog.php?action=add";
	}else{
		$showReturn="./edithtmlblog.php?action=add";
	}

    if (empty($trackback)) {
        $showMsg="$langadfun[78]";
		display($showMsg, $showReturn);
	} else {
	$tmpglobalInfo=selectGlobal();
	$sql=@mysql_query("SELECT * FROM `$x[blog]` ORDER BY id DESC LIMIT 0,1");
	$tmp=@mysql_fetch_array($sql);
	$tmpid=$tmp['id'];
		$len = strlen($content);
		if ($len <= 20)
		{
			$excerpt = $content;
		}
		else
		{
			$excerpt = substr(($content),"0","100").chr(0);
			$excerpt = $excerpt."...";
			$excerpt = htmlspecialchars($excerpt);
	    }
########################
            if ($trackback) {
     $url=$tmpglobalInfo[siteUrl]."/reply/".$tmpid.".html";
     $blog_name=$tmpglobalInfo[siteName];
                $error = sendPing(0, $title, $url, $excerpt, $blog_name, $trackback);
     }
########################
        $showMsg.=$langadfun[79].$error;
		display($showMsg, $showReturn);
	}
}

/* edit blog page */
function selectBlogModify($s_addtime,$s_sort,$s_title,$s_content,$s_px,$s_sc)
{
    global $resultBlog, $page, $total_page, $x;
if (!$s_sc) $s_sc="DESC";
if ($s_addtime) $sq_addtime="addtime LIKE '$s_addtime%' AND";
if ($s_sort!="NULL") $sq_sort="sort='$s_sort' AND";
if ($s_title) $sq_title="title LIKE '%$s_title%' AND";
if ($s_content) $sq_content="content LIKE '%$s_content%' AND";
if (!$s_px) $s_px="id";
	$page_size=30;
    $queryBlog="SELECT * FROM `$x[blog]` WHERE $sq_addtime $sq_sort $sq_title $sq_content 1=1 ORDER BY $s_px $s_sc";
    //$queryBlog="SELECT * FROM `$x[blog]` ORDER BY id ".$s_sc;
	$result=mysql_query($queryBlog);
	$artSum=mysql_num_rows($result);
	$total_page = ceil($artSum/$page_size);
	$page=$_GET['page'];
    if(!$page) $page=1;
    $start = ($page-1)*$page_size;
    //判断当前页号
    if(!$page)
	{
		$page=1;
	}
	else
	{
		$page+=0;
		$page=floor($page);
		if($page>$total_page) $page=$total_page;
		if($page<1) $page=1;
	}
    $query="SELECT * FROM `$x[blog]` WHERE $sq_addtime $sq_sort $sq_title $sq_content 1=1 ORDER BY $s_px $s_sc limit $start,$page_size";
    //$query="SELECT * FROM `$x[blog]` WHERE addtime LIKE '$s_addtime%' AND sort='$s_sort' AND title LIKE '%$s_title%' AND content LIKE '%$s_content%' ORDER BY $s_px $s_sc limit $start,$page_size";
    //$query="SELECT * FROM `$x[blog]` ORDER BY id DESC limit $start,$page_size";
    $resultBlog=mysql_query($query);	
}

/* edit comment page */
function selectCommentModify($s_blogid, $s_addtime,$s_author,$s_content,$s_sc)
{
    global $resultComment, $page, $total_page, $x;
if (!$s_sc) $s_sc="DESC";
if ($s_addtime) $sq_addtime="addtime LIKE '$s_addtime%' AND";
if ($s_blogid) $sq_blogid="commentID='$s_blogid' AND";
if ($s_author) $sq_author="author='$s_author' AND";
if ($s_content) $sq_content="content LIKE '%$s_content%' AND";
if (!$s_px) $s_px="id";
	$page_size=30;
    $queryComment="SELECT * FROM `$x[comment]` WHERE $sq_addtime $sq_blogid $sq_author $sq_content 1=1 ORDER BY $s_px $s_sc";
    //$queryComment="SELECT * FROM `$x[comment]` ORDER BY id DESC";
	$result=mysql_query($queryComment);
	$commentSum=mysql_num_rows($result);
	$total_page = ceil($commentSum/$page_size);
	$page=$_GET['page'];
    if(!$page) $page=1;
    $start = ($page-1)*$page_size;
    //判断当前页号
    if(!$page)
	{
		$page=1;
	}
	else
	{
		$page+=0;
		$page=floor($page);
		if($page>$total_page) $page=$total_page;
		if($page<1) $page=1;
	}
    $query="SELECT * FROM `$x[comment]` WHERE $sq_addtime $sq_blogid $sq_author $sq_content 1=1 ORDER BY $s_px $s_sc limit $start,$page_size";
    //$query="SELECT * FROM `$x[comment]` ORDER BY id DESC limit $start,$page_size";
    $resultComment=mysql_query($query);	
}

/* edit blog page */
function selectAnnounceModify()
{
	global $x;
    $query="SELECT * FROM `$x[announce]` ORDER BY id DESC";
    $resultAnnounce=mysql_query($query);
	return $resultAnnounce;
}

/* 查询当前要修改文章相关信息 */
function selectOneBlogInfo($iID)
{
	global $x;
	$querySelectOneBlogInfo="SELECT * FROM `$x[blog]` WHERE id='$iID'";
	$blogInfo=mysql_fetch_array(mysql_query($querySelectOneBlogInfo));
	return $blogInfo;
}

/* 删除相应的BLOG信息 */
function deleteBlog($iID)
{
	global $x, $langadfun;
	if($iID=="")
	{
		showError("$langadfun[83]");
	}
	$query3=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$iID'");
	$num=mysql_num_rows($query3);
	if($num == "")
	{
		showError("$langadfun[84]");
	}

    $query="DELETE FROM `$x[blog]` WHERE id='$iID'";
    @mysql_query($query) or die("$langadfun[85]");
    $query2="DELETE FROM `$x[comment]` WHERE commentID='$iID'";
    @mysql_query($query2) or die("$langadfun[85]");
    $showMsg="$langadfun[86]";
    $showReturn="./editblog.php?action=list";
    display($showMsg, $showReturn);
}
/*批量删除功能的函数Encoded By Anthrax*/
function delMore($delId)
{
    global $x, $langadfun;
	$Aid=(array)$delId;
	foreach($Aid AS $articleid)
	{
	  $query="DELETE FROM `$x[blog]` WHERE id='$articleid'";
      @mysql_query($query) or die("$langadfun[87]");
      $query2="DELETE FROM `$x[comment]` WHERE commentID='$articleid'";
      @mysql_query($query2) or die("$langadfun[88]");
	 }
    $showMsg="$langadfun[89]";
	$showReturn="./editblog.php?action=list"; 
    display($showMsg, $showReturn);
}
/*批量移动文章功能的函数*/
function moveMore($moveId, $sortId)
{
    global $x, $langadfun;
  if($sortId=="") $sortId="NULL";
  if($sortId=="NULL"){
    $showMsg="$langadfun[128]";
    showError($showMsg);
  }else{
	$Aid=(array)$moveId;
	foreach($Aid AS $articleid)
	{
	  $query="UPDATE `$x[blog]` SET sort='$sortId' WHERE id='$articleid'";
      @mysql_query($query) or die("$langadfun[129]");
      $query2="UPDATE `$x[comment]` SET commentSort='$sortId' WHERE commentID='$articleid'";
      @mysql_query($query2) or die("$langadfun[130]");
	 }
    $showMsg="$langadfun[131]";
	$showReturn="./editblog.php?action=list"; 
    display($showMsg, $showReturn);
  }
}
/* 查询当前要修改评论相关信息 */
function selectOneCommentInfo($iID)
{
	global $x;
	$querySelectOneCommentInfo="SELECT * FROM `$x[comment]` WHERE id='$iID'";
	$commentInfo=mysql_fetch_array(mysql_query($querySelectOneCommentInfo));
	return $commentInfo;
}

/* 删除相应的评论信息 */
function deleteComment($iID)
{
	global $x, $langadfun;
	if($iID=="")
	{
		showError("$langadfun[90]");
	}
	$query3=mysql_query("SELECT * FROM `$x[comment]` WHERE id='$iID'");
	$num=mysql_num_rows($query3);
	if($num == "")
	{
		showError("$langadfun[91]");
	}

    $query="DELETE FROM `$x[comment]` WHERE id='$iID'";
    @mysql_query($query) or die("$langadfun[92]");
    $showMsg="$langadfun[93]";
    $showReturn="./editcomment.php?action=edit";
    display($showMsg, $showReturn);
}


/* 查询超级用户列表 */
function selectAdmin()
{
	global $resultAdmin, $x, $langadfun;
	$query="SELECT * FROM `$x[admin]` WHERE uid='0'";
	$resultAdmin=mysql_query($query) or die("$langadfun[94]");
}

/* 查询普通用户列表 */
function selectUser()
{
	global $resultUser, $x, $langadfun;
	$query="SELECT * FROM `$x[admin]` WHERE uid='1'";
	$resultUser=mysql_query($query) or die("$langadfun[95]");
}

/* 查询访客用户列表 */
function selectVisitor()
{
	global $resultVisitor, $x, $langadfun;
	//$query="SELECT * FROM `$x[user]`";
	$query="SELECT * FROM `$x[admin]` WHERE uid='3'";
	$resultVisitor=mysql_query($query) or die("$langadfun[105]");
}

/* 添加新用户 */
function addNewUser($iUser, $iPassword, $iPassword_2, $iEmail, $iPhone, $iUid)
{
	global $x, $langadfun;
	$result=checkUser($iUser);
	$result.=checkPassword($iPassword, $iPassword_2);
	$result.=checkEmail($iEmail);
	if($result)
	{
		showError($result);
	}
	if($iUid == "NULL")
		showError("$langadfun[96]");
	$iPassword=md5($iPassword);

	$query="SELECT * FROM `$x[admin]` WHERE user='$iUser'";
	$result=mysql_num_rows(mysql_query($query));
	if($result)
	{
		showError("$langadfun[97]");
	}
	$query="INSERT INTO `$x[admin]` (uid,user,password,email,phone) ";
	$query.="VALUES('$iUid','$iUser','$iPassword','$iEmail','$iPhone')";
	@mysql_query($query) or die("$langadfun[98]");
	$showMsg="$langadfun[99]!";
    $showReturn="./edituser.php?action=adduser";
    display($showMsg, $showReturn);
}

/* 删除所选用户 */
function deleteSelectObject($iAdmin, $iUser, $iVisitor)
{
	global $x, $langadfun;
	if($iAdmin == "NULL" && $iUser == "NULL" && $iVisitor == "NULL")
		showError("$langadfun[100]");
  if($iVisitor == "NULL")
  {
	if($iAdmin == "NULL")
		$delID=$iUser;
	elseif($iUser == "NULL")
		$delID=$iAdmin;
	$query="DELETE FROM `$x[admin]` WHERE id='$delID'";
	@mysql_query($query) or die("$langadfun[101]");
	$showMsg="$langadfun[102]";
    $showReturn="./edituser.php?action=list";
    display($showMsg, $showReturn);
  }else{
		$delID=$iVisitor;
	$query="DELETE FROM `$x[user]` WHERE id='$delID'";
	@mysql_query($query) or die("$langadfun[101]");
	$showMsg="$langadfun[102]";
    $showReturn="./edituser.php?action=list";
    display($showMsg, $showReturn);
  }
}

/* 选择一个待操作的用户 */
function selectOneObject($iAdmin, $iUser)
{
	global $resultOneObj, $x, $langadfun;
	if($iAdmin == "NULL" && $iUser == "NULL")
		showError("$langadfun[103]");
	elseif($iAdmin == "NULL")
		$selID=$iUser;
	elseif($iUser == "NULL")
		$selID=$iAdmin;
	$query="SELECT * FROM `$x[admin]` WHERE id='$selID'";
	$resultOneObj=mysql_fetch_array(mysql_query($query)) or die("$langadfun[104]");
}

/* 更新所选用户操作 */
function updateOneObj($iUser, $iPassword, $iPassword_2, $iEmail, $iPhone, $iUid, $iID)
{
	global $x, $langadfun;
	$result=checkUser($iUser);
	if ($iPassword != "" && $iPassword_2 != "")
	{
		
		$result.=checkPassword($iPassword, $iPassword_2);
		$iPassword=md5($iPassword);
		$param = "password = '$iPassword', ";
	}
	else
	{
		$param = "";
	}
	$result.=checkEmail($iEmail);
	if($result)
	{
		showError($result);
	}
	if($iUid == "NULL")
		showError("$langadfun[106]");
	
	//echo "UPDATE `$x[admin]` SET user='$iUser', '$param' email='$iEmail', uid='$iUid' WHERE id='$iID'";
	$query="UPDATE `$x[admin]` SET user='$iUser', $param email='$iEmail', phone='$iPhone', ";
	$query.="uid='$iUid' WHERE id='$iID'";
	//@mysql_query($query) or die("更新所选用户信息时出错!");
	@mysql_query($query) or die(mysql_error());
	$showMsg="$langadfun[107]";
    $showReturn="./edituser.php?action=list";
    display($showMsg, $showReturn);
}

/*
 *  Name:          getDBsize()
 *  Parameter:     null
 *  Description:   取得exBlog占用数据库大小 
 *	Return:        返回所占数据库大小 [KB] (float)
 *  Write:         elliott [at] 2004-07-29
*/
function getDBsize()
{
	global $x, $exBlog;
	$retSize = 0;
	$query = MYSQL_QUERY("SHOW TABLE STATUS FROM `$exBlog[dbname]`");
	while($rows = MYSQL_FETCH_ARRAY($query))
	{
		if ($rows['Name'] == $x['aboutme']  ||
			$rows['Name'] == $x['admin']    ||
			$rows['Name'] == $x['announce'] ||
			$rows['Name'] == $x['blog']     ||
			$rows['Name'] == $x['comment']  ||
			$rows['Name'] == $x['global']   ||
			$rows['Name'] == $x['links']    ||
			$rows['Name'] == $x['sort']     ||
			$rows['Name'] == $x['user']     ||
			$rows['Name'] == $x['visits']
			)
		{
			$retSize += $rows['Data_length'] + $rows['Index_length'] + $rows['Data_free'];
		}
	}
	return number_format($retSize/1024, "2");
}

/*
 *  Name:          getSysRunTime()
 *  Parameter:     null
 *  Description:   取得exBlog自安装至今运行天数 
 *	Return:        exBlog自安装至今运行天数 (int)
 *  Author:        elliott [at] 2004-08-12
*/
function getSysRunTime()
{
	global $x;
	$query = mysql_query("SELECT initTime FROM `$x[global]`");
	$res = mysql_fetch_array($query);
	$nowTime = date("Y-m-d");
	$day = (strtotime($nowTime) - strtotime($res[0])) / (60*60*24);
	if ($day === 0)
	{
		return "1";
	}
	else
	{
		return $day;
	}
}

/* Blog后台分页函数 */
function cutBlogPage()
{
	global $page,$total_page, $langadfun;
	$prev_page=$page-1;
	$next_page=$page+1;
	if($prev_page >= 1)
	{
		$prev_page="<a href=editblog.php?action=list&page=$prev_page>$langadfun[108]</a>";
	}
	else
	{
		$prev_page="$langadfun[108]";
	}
	if($next_page>$total_page)
	{
		$next_page="$langadfun[109]";
	}
	else
	{
		$next_page="<a href=editblog.php?action=list&page=$next_page>$langadfun[109]</a>";
	}
	$pageshow="$langadfun[110] <font color='red'>$page</font> $langadfun[111], $langadfun[112] <font color='red'>$total_page</font> $langadfun[111]  ";
	$pageshow.="<a href=editblog.php?action=list>$langadfun[113]</a> $prev_page $next_page <a href=editblog.php?action=list&page=$total_page>$langadfun[114]</a>";
	return $pageshow;
}

/* sheeryiro changed*/
/* Blog后台分页函数 */
function cutCommentPage()
{
	global $page,$total_page, $langadfun;
	$prev_page=$page-1;
	$next_page=$page+1;
	if($prev_page >= 1)
	{
		$prev_page="<a href=editcomment.php?action=edit&page=$prev_page>$langadfun[108]</a>";
	}
	else
	{
		$prev_page="$langadfun[108]";
	}
	if($next_page>$total_page)
	{
		$next_page="$langadfun[109]";
	}
	else
	{
		$next_page="<a href=editcomment.php?action=edit&page=$next_page>$langadfun[109]</a>";
	}
	$pageshow="$langadfun[110] <font color='red'>$page</font> $langadfun[111], $langadfun[112] <font color='red'>$total_page</font> $langadfun[111]  ";
	$pageshow.="<a href=editcomment.php?action=edit>$langadfun[113]</a> $prev_page $next_page <a href=editcomment.php?action=edit&page=$total_page>$langadfun[114]</a>";
	return $pageshow;
}

function selectAllWeather()
{
    global $resultWeather,$x, $langadfun;
    $query="SELECT * FROM `$x[weather]` ORDER BY id DESC";
    $resultWeather=mysql_query($query) or die("$langadfun[115]");
}
function editBlogSelWeather($curWeather, $blogWeather)
{
	if($curWeather == $blogWeather)
	{
		echo "selected";
	}
}
function editBlogSelTop($curTop)
{
    global $langadfun;
	if($curTop == 1)
	{
		echo "$langadfun[116]";
	}
       else if($curTop == 0)
	{
		echo "$langadfun[117]";
	}
}
function editBlogSelHidden($curHidden)
{
    global $langadfun;
	if($curHidden == 1)
	{
		echo "$langadfun[118]";
	}
       else if($curHidden == 0)
	{
		echo "$langadfun[119]";
	}
}
function updateUploadset($imax_file_size, $idestination_folder, $iup_type)
{
	global $x, $langadfun;
	$query="UPDATE `$x[upload]` SET max_file_size='$imax_file_size', destination_folder='$idestination_folder',up_type='$iup_type'";
    @mysql_query($query) or die("$langadfun[120]");
    $showMsg="$langadfun[121]";
    $showReturn="./upload.php?action=edit";
    display($showMsg, $showReturn);
}

/* 查询当前要修改名词注释相关信息 */
function selectOneKeywordInfo($iID)
{
	global $x;
	$querySelectOneKeywordInfo="SELECT * FROM `$x[keyword]` WHERE id='$iID'";
	$keywordInfo=mysql_fetch_array(mysql_query($querySelectOneKeywordInfo));
	return $keywordInfo;
}

/* 添加名词注释 */
function addKeyword($iword, $icontent, $iurl)
{
	global $x, $langadfun;

	$query="SELECT * FROM `$x[keyword]` WHERE word='$iword'";
	$result=mysql_num_rows(mysql_query($query));
	if($result)
	{
		showError("NO!");
	}
	$query="INSERT INTO `$x[keyword]` (word,content,url)";
	$query.=" VALUES('$iword','$icontent','$iurl')";
	@mysql_query($query) or die(mysql_error());
	$showMsg="OK!";
    $showReturn="./keyword.php?action=list";
    display($showMsg, $showReturn);
}

/* 删除名词注释 */
function deleteKeyword($iID)
{
	global $x, $langadfun;
    $query="DELETE FROM `$x[keyword]` WHERE id='$iID'";
    @mysql_query($query) or die("NO!");
    $showMsg="OK!";
    $showReturn="./keyword.php?action=list";
    display($showMsg, $showReturn);
}

/* 更新名词注释 */
function updateKeyword($iid, $iword, $icontent, $iurl)
{
	global $x, $langadfun;

    $query="UPDATE `$x[keyword]` SET word='$iword',content='$icontent',";
    $query.="url='$iurl' WHERE id='$iid'";
    @mysql_query($query) or die("NO!");
    $showMsg="OK!";
    $showReturn="./keyword.php?action=list";
    display($showMsg, $showReturn);
}

/* 名词注释列表 */
function selectKeywordList()
{
    global $resultKeyword, $x;
    $query="SELECT * FROM `$x[keyword]` ORDER BY id DESC";
    $resultKeyword=mysql_query($query);	
}

# 发送 开始
#################
# 发送 ping ,如果出错就返回错误信息
#################
	function sendPing($title, $url, $excerpt, $blog_name, $ping_url) {
      // 检测
      if ( $ping_url == '' )
         return $langadfun[122];

      $parsed_url = parse_url( $ping_url );

      // error: bad url
      if ( $parsed_url['scheme'] != 'http' ||   $parsed_url['host'] == '' )
         return $langadfun[123];

      // 推测端口数 guess port number
      $port = ( $parsed_url['port'] ) ? $parsed_url['port'] : 80;

      // 创建内容 create contents
      $content  = 'title=' . urlencode( $title );
      $content .= '&url=' . urlencode( $url );
      $content .= '&excerpt=' . urlencode( $excerpt );
      $content .= '&blog_name=' . urlencode( $blog_name );

      $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';

      // 创建 HTTP 请求 create HTTP request
      $request  = 'POST ' . $parsed_url['path'];
      if ( $parsed_url['query'] != '' )
         $request .= '?' . $parsed_url['query'];
      $request .= " HTTP/1.1\r\n";
      $request .= "Accept: */*\r\n";
      $request .= "User-Agent: " . $user_agent . "\r\n";
      $request .=   "Host: " . $parsed_url['host'] . ":" . $port . "\r\n";
      $request .= "Connection: Keep-Alive\r\n";
      $request .= "Cache-Control: no-cache\r\n";
      $request .= "Connection: Close\r\n";
      $request .=   "Content-Length: " . strlen( $content ) . "\r\n";
      $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $request .= "\r\n";
      $request .= $content;

      $socket = fsockopen( $parsed_url['host'], $port, $errno, $errstr );
      // 检测 socket 是否连接成功
      if ( ! $socket )
         return $langadfun[124].$errstr.' ('.$errno.')';

      // 发送请求 send request
      fputs( $socket, $request );

      // 接收应答 recieve response
      $result = '';
      while ( ! feof ( $socket ) ) {
         $result .= fgets( $socket, 4096 );
      }
      // 关闭 socket 连接
      fclose( $socket );

      // 检测错误级别
      // instead of parsing the XML, just check for the error string
      // [TODO] extract real error message and return that
      if ( strstr($result,'<error>1</error>') )
         //return 'TrackBack Ping 发送出错: '.htmlspecialchars($result);
         return $langadfun[125];
      if ( strstr($result,'<error>0</error>') )
         return $langadfun[126];
      if ( !strstr($result,'<error>') )
         return $langadfun[127];
   }
# 发送 结束
?>
