<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L]  exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 后台常用函数
*\===========================================================================*/

######### 用户登录 #########
function userLogin($iUser, $iPassword, $initVal, $postVal)
{
	global $x;
	if (!checkImageVal($initVal, $postVal))
	{
		showError("输入的验证码有误,请输入和右侧图像中数字相同的值.");
	}
	$iPassword=md5($iPassword);
	$query=mysql_query("SELECT * FROM `$x[admin]` WHERE user='$iUser' AND password='$iPassword'");
	$result=mysql_num_rows($query);
	if($result)
	{
		$set=mysql_fetch_array($query);
		setcookie("exBlogUser","$iUser||$set[email]",time()+86400);
		session_start();
		$_SESSION['exPass']=$iUser;
		$_SESSION['userID'] = $set['id'];
		header("Location: frame.php");
	}
	else
	{
		showError("用户名或密码不正确!");
	}
}

######### 用户退出登录 #########
function userLogout()
{
	setcookie("exBlogUser");
	session_start();
	session_unregister("exPass");
	session_destroy();
	$showMsg="用户登出操作成功!";
    $showReturn="../index.php";
    display($showMsg, $showReturn);
}

######### 检查用户是否非法登录 #########
function checkLogin()
{
	session_cache_limiter('private, must-revalidate');
	session_start();
	if(!session_is_registered("exPass"))
	{
		showError("用户名或密码不正确!");
	}
}


/*
 *  Name:          checkImageVal()
 *  Parameter:     $initVal 随机生成的验证码值
 *                 $postVal 用户填写的验证码值
 *  Description:   对比用户输入验证码是否和系统生成的一样
 *	Return:        相为返回'true' 不相同返回'false' (Boolean)
 *  Write:         elliott [at] 2004-07-29
*/
function checkImageVal($initVal, $postVal)
{
	if (strcmp($initVal, $postVal) == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

###### 检查用户权限 ######
function checkUserUid($iUser)
{
	global $x;
	$queryUserUid="SELECT * FROM `$x[admin]` WHERE user='$iUser' AND uid='0'";
	$resultUserUid=mysql_query($queryUserUid) or die("查询用户权限时失败!");
	
	$userUidNum=mysql_num_rows($resultUserUid);
	if(!$userUidNum)
	{
		showError("该帐号为普通用户,无权浏览该页面!");
	}
}

######### 检查标题是否合法 #########
function checkSubject($iTitle)
{
	if(trim($iTitle) == "")
	{
		$result="标题不能为空!<br>";
		return $result;
	}
}

###### 检查分类名长度 ######
function checkSortLen($iSort)
{
	if(trim($iSort) == "")
	{
		$result="分类名不能为空!<br>";
		return $result;
	}
	if(strlen($iSort) > 15)
	{
		$result="分类名超过限制长度!<br>";
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
	if(trim($iHomepage) == "")
	{
		$result="站点名不能空!<br>";
		return $result;
	}
	elseif(strlen($iHomepage) > 20)
	{
		$result="站点名不能大于20个字符!<br>";
		return $result;
	}
	elseif(eregi("[<>{}(),%#|^&!`$]",$iHomepage))
	{
		$result="站点名中不能含有特殊字符!<br>";
		return $result;
	}
}

######### 检查链接URL是否合法 #########
function checkURL($iLink)
{
	if(trim($iLink) == "")
	{
		$result="链接URL不能为空!<br>";
		return $result;
	}
	if(!eregi("^http://.+\..+$",$iLink))
	{
		$result.="网站URL只能由a-z,A-Z,0-9及'_'线组成!<br>";
		return $result;
	}
}

######### 检查链接图片格式是否合法 Code Start #########
		   function checkIMGname($iLogoURL){
			if ($iLogoURL!="") {
	    
				$imgEndName = strrchr($iLogoURL,'.');
 
				if ($imgEndName!=".gif" and $imgEndName!=".jpg" and $imgEndName!=".png"){

					$result="图片格式不正确,如果没有图片请不要填写<br>";
					return $result;	
				} 
			}
		   }

######### 检查install文件是否存在 ##########
function installExists()
{
	if(file_exists("install.php"))
	{
		$str="<font color=red>Warning: Install.php文件没有删除,这将导致一些安全漏洞.</font><br>";
		$str.="请点击 <a href=\"$PHP_SELF?action=delInstall\">[这里删除]</a> 或进入FTP更名!!!<br>";
		$str.="如果你不能直接删除,请进入FTP删除!";
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
	if($_POST['action'] == "编辑")
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
	chmod("../admin",0775);
	chmod("../admin/install.php",0777);
	unlink("../admin/install.php");
	echo "<font color=red>install.php文件成功删除!</font>";
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
    global $editLink, $x;
    $query="SELECT * FROM `$x[links]` WHERE id='$iID'";
    $editLink=mysql_fetch_array(mysql_query($query)) or die("查询待编辑连接时失败!");
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
    global $x;

    $query="SELECT * FROM `$x[links]` WHERE visible = '1' ORDER BY id DESC";
    $resource=mysql_query($query) or die("查询已通过验证链接信息时失败!");

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
	global $x;

	$query = "SELECT * FROM `$x[links]` WHERE visible = '0' ORDER BY id DESC";
	$resource = mysql_query($query) or die("查询未通过验证链接信息时失败!");

	return $resource;
}

/* 删除链接 */
function deleteLinks($iID)
{
	global $x;
    $query="DELETE FROM `$x[links]` WHERE id='$iID'";
    @mysql_query($query) or die("删除链接时失败!");
    $showMsg="删除链接成功!";
    $showReturn="./editlinks.php?action=edit";
    display($showMsg, $showReturn);
}

/* 更新链接记录 */
function updateLink($iHomepage, $iUrl, $iLogoURL, $iDescription, $iID, $visible)
{
	global $x;
	$result=checkHomepage($iHomepage);
	$result.=checkURL($iUrl);
	$result.=checkIMGname($iLogoURL);
	if($result)
	{
		showError($result);
	}
    $query="UPDATE `$x[links]` SET homepage='$iHomepage',url='$iUrl',";
    $query.="logoURL='$iLogoURL',description='$iDescription', visible = '$visible' WHERE id='$iID'";
    @mysql_query($query) or die("更新链接时失败!");
    $showMsg="更新链接成功!";
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

function updateGlobal($iSiteName,$iSiteUrl,$iCopyright,$iTmpURL, $activeRun, $unactiveRunMessage, $isCountOnlineUser, $description, $webmaster)
{
	global $x;
	$qUpdateGlobal  = "UPDATE `$x[global]` SET siteName='$iSiteName', siteUrl='$iSiteUrl',";
	$qUpdateGlobal .= "copyright='$iCopyright', tmpURL='$iTmpURL', activeRun = '$activeRun',";
	$qUpdateGlobal .= "unactiveRunMessage = '$unactiveRunMessage', isCountOnlineUser = '$isCountOnlineUser', Description = '$description', Webmaster = '$webmaster'";
	//@mysql_query($qUpdateGlobal) or die("更新常规信息时失败!");
	@mysql_query($qUpdateGlobal) or die(mysql_error());
	$showMsg="更新常规信息成功!";
    $showReturn="./other.php";
    display($showMsg, $showReturn);
}

######### 添加公告 #########
function addAnnounce($iTitle, $iContent, $iAuthor, $iEmail)
{
	global $x;

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
	$showMsg="添加公告成功!";
    $showReturn="./editannounce.php?action=add";
    display($showMsg, $showReturn);
}

/* 查询待编辑公告 */
function selectEditAnnounce($iID)
{
global $resultAnnounce, $x;
$query="SELECT * FROM `$x[announce]` WHERE id='$iID'";
$resultAnnounce=mysql_fetch_array(mysql_query($query)) or die("查询待编辑连接时失败!");
}

/* 更新公告 */
function updateAnnounce($iTitle, $iContent, $iAuthor, $iEmail, $iID)
{
global $x;
$result=checkSubject($iTitle);
$result.=checkUser($iAuthor);
$result.=checkContent($iContent);
if($result)
{
showError($result);
}
$query="UPDATE `$x[announce]` SET title='$iTitle',content='$iContent',";
$query.="author='$iAuthor',email='$iEmail' WHERE id='$iID'";
@mysql_query($query) or die("更新公告时失败!");
$showMsg="更新公告成功!";
$showReturn="./editannounce.php?action=edit";
display($showMsg, $showReturn);
}

/* update blog content */
function updateBlog($title, $content, $author, $email, $sort, $ID, $addtime, $weather, $top, $hidden, $html)
{
	global $x;

	$check = new CheckData();

	if(!$check->checkOther($title, true, 0))
		$ret = "BLOG标题必须填写.<br>";
	if(!$check->checkName($author, true, false))
		$ret .= "作者名称必须填写且不能含有特殊字符.<br>";
	if(!$check->checkEmail($email, false))
		$ret .= "email格式不正确,请检查.<br>";
	if(!$check->checkOther($content, true, 6))
		$ret .= "BLOG内容必须填写且不能少于6字符.<br>";

	if($ret)
	{
		showError($ret);
	}

	if($iSort == "NULL")
	{
		showError("请选择文章所属分类!");
	}
	$qUpdateArt="UPDATE `$x[blog]` SET title='$title', content='$content',";
	$qUpdateArt.="author='$author', email='$email', sort='$sort', addtime='$addtime', weather = '$weather', top = '$top', hidden = '$hidden', html = '$html' WHERE id='$ID'";
	@mysql_query($qUpdateArt) or die("更新article时失败!");
	$showMsg="更新Article成功!";
    $showReturn="./editblog.php?action=edit";
    display($showMsg, $showReturn);
}

/* update comment content */
function updateComment($id, $content, $author, $addtime ,$adminreply)
{
	global $x;
	
	if($adminreply =="")
	{
	$qUpdateComment="UPDATE `$x[comment]` SET content='$content', author='$author', addtime='$addtime' WHERE id='$id'";
	}
	else
	{
	$icontent=$content.'[reply][b]管理员回复：[/b]'.$adminreply.'[/reply]';
	$qUpdateComment="UPDATE `$x[comment]` SET content='$icontent',";
	$qUpdateComment.="author='$author', addtime='$addtime' WHERE id='$id'";
	}
	@mysql_query($qUpdateComment) or die("更新comment时失败!");
	$showMsg="更新Comment成功!";
    $showReturn="./editcomment.php?action=edit";
    display($showMsg, $showReturn);
}

/* 查询所有分类列表 */
function selectAllSort()
{
    global $resultSort,$x;
    $query="SELECT * FROM `$x[sort]` ORDER BY id DESC";
    $resultSort=mysql_query($query) or die("查询所有分类时失败!");
}

/* 添加新文章分类 */
function addSort($ienName, $icnName, $iDescription)
{
	global $x;
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
         showError("该分类名在数据库中已存在!");
    }
	$query="INSERT INTO `$x[sort]` (enName, cnName, description)";
    $query.=" VALUES('$ienName', '$icnName', '$iDescription')";
    @mysql_query($query) or die("添加新文章分类时失败!");
    $showMsg="添加新分类成功!";
    $showReturn="./editsort.php?action=add";
    display($showMsg, $showReturn);
}

/* 查询BLOG数,评论数,访问数 后台用 */
function InfoQuery()
{
	global $x, $commentNum, $blogNum, $visited;
	$sql=mysql_query("SELECT id FROM `$x[comment]`");
	$commentNum=mysql_num_rows($sql);  //论评总数
	$sql=mysql_query("SELECT id FROM `$x[blog]`");
	$blogNum=mysql_num_rows($sql);     //Blog总数
	$sql=mysql_query("SELECT * FROM `$x[visits]`");
	$tmp=mysql_fetch_array($sql);
	$visited=$tmp['visits'];           //访问数
}

/* 选择一个待编辑分类 */
function selectOneSort($iEditSort)
{
	global $x;
	if($iEditSort == "NULL")
	{
		showError("请选择待编辑的分类!");
	}
    global $resultOneSort;
    $query="SELECT * FROM `$x[sort]` WHERE cnName='$iEditSort'";
	$resultOneSort=mysql_fetch_array(mysql_query($query)) or die("选择待编辑分类时失败!");
}

/* 更新分类 */
function updateSort($iCnName,$iEnName,$iDescription,$iOldCnName,$iID)
{
	global $x;
	$result=checkSortLen($iCnName);
	$result.=checkSortLen($iEnName);
	if($result)
	{
		showError($result);
	}
    //-----  更新分类名 -------//
	$query="UPDATE `$x[sort]` SET cnName='$iCnName',enName='$iEnName',";
	$query.="description='$iDescription' WHERE id='$iID'";
	@mysql_query($query) or die("更新所选分类时失败!");
	
	//----- 更新原有文章分类名 ------//
	$query2="UPDATE `$x[blog]` SET sort='$iCnName' WHERE sort='$iOldCnName'";
	@mysql_query($query2) or die("更新原有文章分类时失败!");
	$query3="UPDATE `$x[comment]` SET commentSort='$iCnName' WHERE commentSort='$iOldCnName'";
	@mysql_query($query3) or die("更文章评论所在分类是时失败!");

	$showMsg="更新所选分类和原来文章所在分类及评论所在分类成功!";
    $showReturn="./editsort.php?action=edit";
    display($showMsg, $showReturn);
	
}
/* 更新分类 * by sheeryiro*/
function updateSortrank($ioldID,$inewID)
{
	global $x;
      if($ioldID==$inewID)
	{
		showError("不能自己和自己替换");
	}
    //-----  被替换分类暂时移位 -------//
	$query="UPDATE `$x[sort]` SET `id` = '0' WHERE `id` = '$inewID' LIMIT 1 ";
	@mysql_query($query) or die("更新所选分类时失败!");
	
	//----- 替换分类到被替换分类位置 ------//
	$query2="UPDATE `$x[sort]` SET `id` = '$inewID' WHERE `id` = '$ioldID' LIMIT 1";
	@mysql_query($query2) or die("更新原有文章分类时失败!");
	//----- 还原被替换分类到新位置 ------//
	$query3="UPDATE `$x[sort]` SET `id` = '$ioldID' WHERE `id` = '0' LIMIT 1";
	@mysql_query($query3) or die("更文章评论所在分类时失败!");

	$showMsg="分类从新排序成功!";
    $showReturn="./editsort.php?action=rank";
    display($showMsg, $showReturn);
	
}
/* 更新分类 * by sheeryiro*/
function updateLinkrank($ioldID,$inewID)
{
	global $x;
      if($ioldID==$inewID)
	{
		showError("不能自己和自己替换");
	}
    //-----  被替换链接暂时移位 -------//
	$query="UPDATE `$x[links]` SET `id` = '0' WHERE `id` = '$inewID' LIMIT 1 ";
	@mysql_query($query) or die("更新链接顺序时失败!");
	
	//----- 替换链接到被替换链接位置 ------//
	$query2="UPDATE `$x[links]` SET `id` = '$inewID' WHERE `id` = '$ioldID' LIMIT 1";
	@mysql_query($query2) or die("更新链接顺序时失败!");
	//----- 还原被替换链接到新位置 ------//
	$query3="UPDATE `$x[links]` SET `id` = '$ioldID' WHERE `id` = '0' LIMIT 1";
	@mysql_query($query3) or die("更链接顺序时失败!");

	$showMsg="链接重新排序成功!";
    $showReturn="./editlinks.php?action=rank";
    display($showMsg, $showReturn);
	
}

######### 删除所选公告 #########
function deleteAnnounce($iID)
{
	global $x;
	$query="DELETE FROM	`$x[announce]` WHERE id='$iID'";
	@mysql_query($query) or die("删除公告时失败!");
	$showMsg="删除所选公告成功!";
    $showReturn="./editannounce.php?action=edit";
    display($showMsg, $showReturn);
}

/* 删除所选分类 */
function deleteSort($iSort)
{
	global $x;
	if($iSort == "NULL")
	{
		showError("请选择待操作的分类!");
    }
	$query="DELETE FROM `$x[sort]` WHERE cnName='$iSort'";
    @mysql_query($query) or die("删除分类时失败!");
    $query2="DELETE FROM `$x[blog]` WHERE sort='$iSort'";
    @mysql_query($query2) or die("删除所选分类下文章时失败!");
	$query3="DELETE FROM `$x[comment]` WHERE commentSort='$iSort'";
	@mysql_query($query3) or die("删除该分类下文章评论时失败!");

    $showMsg="删除分类和该分类下所有文章及评论成功!";
    $showReturn="./editsort.php?action=edit";
    display($showMsg, $showReturn);
}


/* update myself description */
function updateInfo($iName, $iAge, $iEmail, $iQq, $iIcq, $iMsn, $iDescription)
{
	global $x;
	$query="UPDATE `$x[aboutme]` SET name='$iName', age='$iAge', email='$iEmail', qq='$iQq',";
    $query.="icq='$iIcq', msn='$iMsn', description='$iDescription'";
    @mysql_query($query) or die("更新个人资料时失败!");
    $showMsg="更新个人资料成功!";
    $showReturn="./editabout.php?action=edit";
    display($showMsg, $showReturn);
}


/* add one links */
function addLinks($iHomepage, $iUrl, $iLogoURL, $iDescription, $iVisible)
{
	global $x;
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
		showError("请不要重复添加相同信息!");
    }
    $query="INSERT INTO `$x[links]` (homepage, logoURL, url, description, visits, visible)";
    $query.=" VALUES('$iHomepage', '$iLogoURL', '$iUrl', '$iDescription','0', '$iVisible')";
    @mysql_query($query) or die("添加友情链接时失败!");
    $showMsg="添加友情链接成功!";
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
function addBlog($title, $content, $author, $email, $sort, $addtime, $weather, $top, $hidden, $html)
{
	global $x;

	$check = new CheckData();

	if(!$check->checkOther($title, true, 0))
		$ret = "BLOG标题必须填写.<br>";
	if(!$check->checkName($author, true, false))
		$ret .= "作者名称必须填写且不能含有特殊字符.<br>";
	if(!$check->checkEmail($email, false))
		$ret .= "email格式不正确,请检查.<br>";
	if(!$check->checkOther($content, true, 6))
		$ret .= "BLOG内容必须填写且不能少于6字符.<br>";

	if($ret)
	{
		showError($ret);
	}

	if($iSort == "NULL")
	{
		showError("请选择文章所属分类!");
	}
	$query="SELECT * FROM `$x[blog]` WHERE title='$iTitle' AND content='$iContent' AND sort='$iSort' ORDER BY id DESC LIMIT 0,1";
    $result2=mysql_num_rows(mysql_query($query));
    if($result2 > 0)
    {
         showError('请不要重复添加相同信息!');
    }

	$iAuthor=trim($iAuthor);

	if(!$addtime){
		$addtime=date(Y."-".m."-".d." ".H.":".i.":".s);	//$addtime=now();
	}

    $query="INSERT INTO `$x[blog]` (title, content, author, email, sort, visits, addtime, weather, top, hidden, html)";
    $query.=" VALUES('$title', '$content', '$author', '$email', '$sort', '0', '$addtime', '$weather', '$top', '$hidden', '$html')";
    @mysql_query($query) or die(mysql_error());
    $showMsg="添加文章成功!";
	if($html=="0")
	{	$showReturn="./editblog.php?action=add";
	}else{
		$showReturn="./edithtmlblog.php?action=add";
	}
		display($showMsg, $showReturn);
}

/* edit blog page */
function selectBlogModify()
{
    global $resultBlog, $page, $total_page, $x;
	$page_size=30;
    $queryBlog="SELECT * FROM `$x[blog]` ORDER BY id DESC";
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
    $query="SELECT * FROM `$x[blog]` ORDER BY id DESC limit $start,$page_size";
    $resultBlog=mysql_query($query);	
}

/* edit comment page */
function selectCommentModify()
{
    global $resultComment, $page, $total_page, $x;
	$page_size=30;
    $queryComment="SELECT * FROM `$x[comment]` ORDER BY id DESC";
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
    $query="SELECT * FROM `$x[comment]` ORDER BY id DESC limit $start,$page_size";
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
	global $x;
	if($iID=="")
	{
		showError("删除ID不能为空!");
	}
	$query3=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$iID'");
	$num=mysql_num_rows($query3);
	if($num == "")
	{
		showError("未找到该ID相关信息!");
	}

    $query="DELETE FROM `$x[blog]` WHERE id='$iID'";
    @mysql_query($query) or die("删除相应BLOG记录时失败!");
    $query2="DELETE FROM `$x[comment]` WHERE commentID='$iID'";
    @mysql_query($query2) or die("删除相应BLOG评论时失败!");
    $showMsg="删除Blog及相应评论成功!";
    $showReturn="./editblog.php?action=edit";
    display($showMsg, $showReturn);
}
/*批量删除功能的函数Encoded By Anthrax*/
function delMore($delId)
{
    global $x;
	$Aid=(array)$delId;
	foreach($Aid AS $articleid)
	{
	  $query="DELETE FROM `$x[blog]` WHERE id='$articleid'";
      @mysql_query($query) or die("删除相应BLOG记录时失败!");
      $query2="DELETE FROM `$x[comment]` WHERE commentID='$articleid'";
      @mysql_query($query2) or die("删除相应BLOG评论时失败!");
	 }
    $showMsg="批量删除Blog及相应评论成功!";
	$showReturn="./editblog.php?action=edit"; 
    display($showMsg, $showReturn);
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
	global $x;
	if($iID=="")
	{
		showError("删除ID不能为空!");
	}
	$query3=mysql_query("SELECT * FROM `$x[comment]` WHERE id='$iID'");
	$num=mysql_num_rows($query3);
	if($num == "")
	{
		showError("未找到该ID相关信息!");
	}

    $query="DELETE FROM `$x[comment]` WHERE id='$iID'";
    @mysql_query($query) or die("删除相应评论记录时失败!");
    $showMsg="删除评论成功!";
    $showReturn="./editcomment.php?action=edit";
    display($showMsg, $showReturn);
}


/* 查询超级用户列表 */
function selectAdmin()
{
	global $resultAdmin, $x;
	$query="SELECT * FROM `$x[admin]` WHERE uid='0'";
	$resultAdmin=mysql_query($query) or die("查询超级用户时失败!");
}

/* 查询普通用户列表 */
function selectUser()
{
	global $resultUser, $x;
	$query="SELECT * FROM `$x[admin]` WHERE uid='1'";
	$resultUser=mysql_query($query) or die("查询普通用户时失败!");
}

/* 添加新用户 */
function addNewUser($iUser, $iPassword, $iPassword_2, $iEmail, $iPhone, $iUid)
{
	global $x;
	$result=checkUser($iUser);
	$result.=checkPassword($iPassword, $iPassword_2);
	$result.=checkEmail($iEmail);
	if($result)
	{
		showError($result);
	}
	if($iUid == "NULL")
		showError("请选择待分配权限!");
	$iPassword=md5($iPassword);

	$query="SELECT * FROM `$x[admin]` WHERE user='$iUser'";
	$result=mysql_num_rows(mysql_query($query));
	if($result)
	{
		showError("对不起,该用户名已存在!");
	}
	$query="INSERT INTO `$x[admin]` (uid,user,password,email,phone) ";
	$query.="VALUES('$iUid','$iUser','$iPassword','$iEmail','$iPhone')";
	@mysql_query($query) or die("添加新用户时失败!");
	$showMsg="添加新用户成功!";
    $showReturn="./edituser.php?action=adduser";
    display($showMsg, $showReturn);
}

/* 删除所选用户 */
function deleteSelectObject($iAdmin, $iUser)
{
	global $x;
	if($iAdmin == "NULL" && $iUser == "NULL")
		showError("请选择待操作的用户!");
	elseif($iAdmin == "NULL")
		$delID=$iUser;
	elseif($iUser == "NULL")
		$delID=$iAdmin;
	$query="DELETE FROM `$x[admin]` WHERE id='$delID'";
	@mysql_query($query) or die("删除所选用户时失败!");
	$showMsg="删除所选用户成功!";
    $showReturn="./edituser.php?action=edit";
    display($showMsg, $showReturn);
}

/* 选择一个待操作的用户 */
function selectOneObject($iAdmin, $iUser)
{
	global $resultOneObj, $x;
	if($iAdmin == "NULL" && $iUser == "NULL")
		showError("请选择待操作的用户!");
	elseif($iAdmin == "NULL")
		$selID=$iUser;
	elseif($iUser == "NULL")
		$selID=$iAdmin;
	$query="SELECT * FROM `$x[admin]` WHERE id='$selID'";
	$resultOneObj=mysql_fetch_array(mysql_query($query)) or die("选择一个待操作用户时失败!");
}

/* 更新所选用户操作 */
function updateOneObj($iUser, $iPassword, $iPassword_2, $iEmail, $iPhone, $iUid, $iID)
{
	global $x;
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
		showError("请选择待分配权限!");
	
	//echo "UPDATE `$x[admin]` SET user='$iUser', '$param' email='$iEmail', uid='$iUid' WHERE id='$iID'";
	$query="UPDATE `$x[admin]` SET user='$iUser', $param email='$iEmail', phone='$iPhone', ";
	$query.="uid='$iUid' WHERE id='$iID'";
	//@mysql_query($query) or die("更新所选用户信息时出错!");
	@mysql_query($query) or die(mysql_error());
	$showMsg="更新所选用户信息成功!";
    $showReturn="./edituser.php?action=edit";
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
	global $page,$total_page;
	$prev_page=$page-1;
	$next_page=$page+1;
	if($prev_page >= 1)
	{
		$prev_page="<a href=editblog.php?action=edit&page=$prev_page>上页</a>";
	}
	else
	{
		$prev_page="上页";
	}
	if($next_page>$total_page)
	{
		$next_page="下页";
	}
	else
	{
		$next_page="<a href=editblog.php?action=edit&page=$next_page>下页</a>";
	}
	$pageshow="第 <font color='red'>$page</font> 页, 共 <font color='red'>$total_page</font> 页  ";
	$pageshow.="<a href=editblog.php?action=edit>首页</a> $prev_page $next_page <a href=editblog.php?action=edit&page=$total_page>末页</a>";
	return $pageshow;
}

/* sheeryiro changed*/
/* Blog后台分页函数 */
function cutCommentPage()
{
	global $page,$total_page;
	$prev_page=$page-1;
	$next_page=$page+1;
	if($prev_page >= 1)
	{
		$prev_page="<a href=editcomment.php?action=edit&page=$prev_page>上页</a>";
	}
	else
	{
		$prev_page="上页";
	}
	if($next_page>$total_page)
	{
		$next_page="下页";
	}
	else
	{
		$next_page="<a href=editcomment.php?action=edit&page=$next_page>下页</a>";
	}
	$pageshow="第 <font color='red'>$page</font> 页, 共 <font color='red'>$total_page</font> 页  ";
	$pageshow.="<a href=editcomment.php?action=edit>首页</a> $prev_page $next_page <a href=editcomment.php?action=edit&page=$total_page>末页</a>";
	return $pageshow;
}

function selectAllWeather()
{
    global $resultWeather,$x;
    $query="SELECT * FROM `$x[weather]` ORDER BY id DESC";
    $resultWeather=mysql_query($query) or die("查询所有天气时失败!");
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
	if($curTop == 1)
	{
		echo "置　顶";
	}
       else if($curTop == 0)
	{
		echo "不置顶";
	}
}
function editBlogSelHidden($curHidden)
{
	if($curHidden == 1)
	{
		echo "是";
	}
       else if($curHidden == 0)
	{
		echo "否";
	}
}
function updatePhotoset($imax_file_size, $idestination_folder, $iwatermark, $iwaterposition, $iwaterstring)
{
	global $x;
	$query="UPDATE `$x[photo]` SET max_file_size='$imax_file_size', destination_folder='$idestination_folder',watermark='$iwatermark', waterposition='$iwaterposition', waterstring='$iwaterstring'";
    @mysql_query($query) or die("上传图片设置失败!");
    $showMsg="上传图片设置成功!";
    $showReturn="./photoset.php?action=edit";
    display($showMsg, $showReturn);
}
function selectPhotoset()
{
	global $x;
	$queryPhotoset="SELECT * FROM `$x[photo]`";
	$photoInfo=mysql_fetch_array(mysql_query($queryPhotoset));
	return $photoInfo;
}

?>