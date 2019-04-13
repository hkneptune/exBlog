<?php
/*=================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\-----------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\-----------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\-----------------------------------------------------------------
*\    本页说明: 前台常用函数
*\=================================================================*/

###### 更新文章访问量 ######
function addArtVisits($iID)
{
	global $x, $langfun;
    $query="UPDATE `$x[blog]` SET visits=visits+1 WHERE id='$iID'";
    @mysql_query($query) or die("$langfun[0]");
}

###### 更新站点访问量 ######
function addVisits()
{
	global $x, $langfun;
    if(!$_COOKIE[exBlogVisits] == "Visitsed")
    {
        setcookie("exBlogVisits","Visitsed",time()+86400);
		//--------- 总访问量 -----------//
        $queryVisits="UPDATE `$x[visits]` SET visits=visits+1";
        @mysql_query($queryVisits) or die("$langfun[1]");
		//------ 当天访问量 --------//
		$curTime=date("Y-m-d");
		$query=mysql_query("SELECT currentDate FROM `$x[visits]` WHERE currentDate='$curTime'");
		$num=mysql_num_rows($query);
		if($num == 0)
		{
			$query2=mysql_query("UPDATE `$x[visits]` SET currentDate='$curTime'");
			$query3=mysql_query("UPDATE `$x[visits]` SET todayVisits=1");
		}
		else
		{
			$query4=mysql_query("UPDATE `$x[visits]` SET todayVisits=todayVisits+1");
		}
    }
}


###### 添加评论内容 ######
function addComment($iCommentID, $iCommentSort, $iAuthor, $iEmail, $iHomepage, $iQQ, $iContent, $password, $register)
{
	global $x, $langfun;
 
 if (!checkunregist()) {
      $iAuthor=$_SESSION['exPass'];
      $password="111111";
      $iEmail=$_SESSION['exUseremail'];
      //$iHomepage=$_COOKIE['exBlogUserhomepage'];
      //$iQQ=$_COOKIE['exBlogUserqq'];
 }
	
	$check = new CheckData();
	if(!$check->checkName($iAuthor, true, false))
		$ret .= "$langfun[2]";
	if(!$check->checkPassword($password, $password, false))
		$ret .= "$langfun[3]";
	if(!$check->checkEmail($iEmail, false))
		$ret .= "$langfun[4]";
	if(!$check->checkUrl($iHomepage, false))
		$ret .= "$langfun[5]";
	if(!$check->checkNumberOnly($iQQ, false, 5, 12))
		$ret .= "$langfun[6]";
	if(!$check->checkOther($iContent, true, 6))
		$ret .= "$langfun[7]";
	$queryid=mysql_query("SELECT id FROM `$x[blog]` WHERE id='$iCommentID'");
	if(!mysql_num_rows($queryid))
       $ret .= "$langfun[8]";
       if($ret)
	{
		showError_show($ret);
	}

	$queryid=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$iCommentID'");
	$tmpBlog=mysql_fetch_array($queryid);
	$nocom = $tmpBlog['nocom'];
	if($nocom=="1") { 
		showError_show("sorry!");
		return 0;
	}

	$iAuthor=trim($iAuthor);

	if($register == "yes")
	{
		if(!$check->checkPassword($password, $password, true))
			$ret .= "$langfun[9]";
		if($ret)
			showError_show($ret);
		addUser($iAuthor, $password, $iEmail, $iHomepage, $iQQ);
	}

	if($password == "")
	{
		if( checkuserexist($iAuthor, "NULL", "NULL") )
			showError_show("$langfun[10]");
	}
	else if($password != "")
	{
 if (!checkunregist()) {
		$passwd=$_SESSION['exPassword'];
 }else{
		$passwd = md5($password);
 }
		if( checkuserexist($iAuthor, $passwd, "NULL") )
			showError_show("$langfun[11]");
	}

	$query="SELECT * FROM `$x[comment]` WHERE commentID='$iCommentID' AND author='$iAuthor'";
	$query.="AND content='$iContent' ORDER BY id DESC LIMIT 0,1";
    $result=mysql_num_rows(mysql_query($query));
	if($result > 0)
		showError_show("$langfun[12]");

	$iAuthor=trim($iAuthor);

	$query="INSERT INTO `$x[comment]` (commentID, commentSort, author, email, homepage, qq, content, addtime)";
	$query.=" VALUES('$iCommentID','$iCommentSort', '$iAuthor', '$iEmail', '$iHomepage', '$iQQ', '$iContent', NOW())";
	@mysql_query($query) or die("$langfun[13]");
	$showMsg="$langfun[14]";
	$showReturn="./?play=reply&amp;id=$iCommentID";
	display_show($showMsg, $showReturn);
}

/**
 *	查询是否统计 BLOG 在线人数
 *	@param null
 *	@return true or false
 *	@author elliott [at] 2004-09-06
 */
function getIsCountOnlineUser()
{
	global $x, $t;

	$query = mysql_query("SELECT * FROM `$x[global]` WHERE isCountOnlineUser = '1'");
	if(@mysql_num_rows($query))
	{
		return true;
	}
	return false;
}



###### 查询标题及版权信息 ######
function selectTitCopy()
{
	global $t, $x, $info, $exurlon, $db;

	//$db->query("SELECT * FROM `$x[global]`");
	//$db->next_record();

	$sql=mysql_query("SELECT * FROM `$x[global]`");
    $info=mysql_fetch_array($sql);
	$sql=mysql_query("SELECT * FROM `$x[aboutme]`");
    $info2=mysql_fetch_array($sql);
	$copyrights=$info[copyright];
	$exurlon=$info[exurlon];	//伪静态连接开关

// 如果BLOG地址后写了/就给它去掉
	while (substr($info[siteUrl], -1) == '/') {
		$len_siteUrl = strlen($info[siteUrl]);
		$info[siteUrl] = substr($info[siteUrl],0,$len_siteUrl-1);
	}
 
   if (!checkunregist()) {
		$t->set_block("b_all","RowLogin","RowsLogin");
		$t->parse("RowsLogin","RowLogin",true);
		$t->parse("PutLogin","RowsLogin");
    }else{
		$t->set_block("b_all","RownoLogin","RowsnoLogin");
		$t->parse("RowsnoLogin","RownoLogin",true);
		$t->parse("PutLogin","RowsnoLogin");
    }
       
if ($exurlon) {
    $url_links=$GLOBALS[expath]."/exurl.php/links.html";
    $url_regLink=$GLOBALS[expath]."/exurl.php/regLink.html";
}else{
    $url_links=$GLOBALS[expath]."/index.php?play=links";
    $url_regLink=$GLOBALS[expath]."/index.php?play=regLink";
}
  	  $t->set_var(array(	"title"=>html_clean(htmlspecialchars($info[siteName])),
				"copyright"=>html_clean($copyrights),
				"siteUrl"=>html_clean(htmlspecialchars($info[siteUrl])),
				"Webmaster"=>html_clean(htmlspecialchars($info[Webmaster])),
				"Webmastername"=>html_clean(htmlspecialchars($info2[name])),
				"Webmasteremail"=>htmlspecialchars($info2[email]),
				"Webmasterage"=>htmlspecialchars($info2[age]),
				"WebmasterQQ"=>htmlspecialchars($info2[qq]),
				"Webmastericq"=>htmlspecialchars($info2[icq]),
				"Webmastermsn"=>htmlspecialchars($info2[msn]),
				"Webmasterdescription"=>$info2[description],
				"sitedescription"=>html_clean(htmlspecialchars($info[Description])),
				"sitekeyword"=>html_clean(htmlspecialchars($info[sitekeyword])),
				"url_links"=>$url_links,
				"url_regLink"=>$url_regLink,
				"templatesURL"=>$GLOBALS[expath]."/".$GLOBALS[tmpURL],
				"exPATH"=>$GLOBALS[expath]));
}

######### 查询当前模版目录 ######
function selectTmpURL()
{
	global $tmpURL, $x, $db;
	//$db->query("SELECT tmpURL FROM `$x[global]`");
	//$db->next_record();
	//$tmpURL = $db->Record['tmpURL'];
	$sql=@mysql_query("SELECT * FROM `$x[global]`");
	$tmp=@mysql_fetch_array($sql);

	if (empty($_GET[tm])) {
	    if (empty($_COOKIE['c_tmpURL'])) {
		setcookie ("c_tmpURL", $tmp['tmpURL']);
		$tmpURL=$tmp['tmpURL'];
	    }else {
		$tmpURL="templates/".$_COOKIE['c_tmpURL'];
	    }
	}else {
		setcookie ("c_tmpURL", $_GET[tm]);
		$tmpURL="templates/".$_GET[tm];
	}

	if (!is_dir($tmpURL) || !file_exists($tmpURL."/index.html") || !file_exists($tmpURL."/b_all.html")) {
		setcookie ("c_tmpURL", $tmp['tmpURL']);
		$tmpURL=$tmp['tmpURL'];
	}
}

######### 首页调用 #########
function shell($iSort)
{
	global $x;
	if($iSort=="")
    {
		$query = "SELECT * FROM `$x[blog]` WHERE hidden='0' ORDER BY id DESC LIMIT 0,8";
	}
	else
	{
		$sql=mysql_query("SELECT * FROM `$x[sort]` WHERE enName='$iSort'");
		$sortName=mysql_fetch_array($sql);
		$query = "SELECT * FROM `$x[blog]` WHERE hidden='0' AND sort='$sortName[id]' ORDER BY id DESC LIMIT 0,8";
	}
	$result=mysql_query($query);
	return $result;
}

######## 查询公告信息 #########
function selectAnnounce()
{
	global $t, $x, $langfun, $db;
	//$db->query("SELECT * FROM `$x[announce]` ORDER BY id DESC");
	$sql=mysql_query("SELECT * FROM `$x[announce]` ORDER BY id DESC");
	$num=mysql_num_rows($sql);

	if($num == 0)
	{
		//$t->set_var("announcetitle","$langfun[15]");
		//$t->set_var("announcecontent","$langfun[15]");
		//$t->parse("m_announce","RowsAnnounce",true);
		//$t->parse("putAnnounce","m_announce");
	}else{
	$i=0;

	while($rows=mysql_fetch_array($sql))	//while($db->next_record())
	{
		$announceid[$i]=$rows[id];
		$announcetitle[$i]=$rows[title];
		$announcecontent[$i]=$rows[content];
		$announceauthor[$i]=$rows[author];
		$announceemail[$i]=$rows[email];
		$announceaddtime[$i]=$rows[addtime];
		//$announcetitle[$i]=clearUbb($announcetitle[$i]);
		$announcetitle[$i]=html_clean($announcetitle[$i]);
  	$exubb = new ExUbb();
	$exubb->setString($announcecontent[$i]);
		$announcecontent[$i] = $exubb->parse_more();
		$announcecontent[$i]=epost($announcecontent[$i]);
########################################
		//---- 把标题截取18个字符 ----//
//		$len = strlen($announcetitle[$i]);
//		if ($len <= 18)
//		{
//			$announcetitle[$i] = $announcetitle[$i];
//		}
//		else
//		{
//			$announcetitle[$i] = substr($announcetitle[$i],"0","18");
//			$announcetitle[$i] = $announcetitle[$i]."...";
//			$announcetitle[$i] = htmlspecialchars($announcetitle[$i]);
//	    }
########################################

			        $url_announce[$i]=$GLOBALS[expath]."/index.php?play=announce&amp;anid=".$announceid[$i];

		$t->set_var(array(	"announceid"=>$announceid[$i],
					"announcetitle"=>$announcetitle[$i],
					"url_announce"=>$url_announce[$i],
					"announceauthor"=>$announceauthor[$i],
					"announceemail"=>$announceemail[$i],
					"announceaddtime"=>$announceaddtime[$i],
					"announcecontent"=>$announcecontent[$i]));
   		$t->parse("m_announce", "RowsAnnounce", true);
	    $i++;
	}
	$t->parse("putAnnounce","m_announce");
	}
}

######### 显示公告 #########
function putAnnounce($iID)
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[announce]` WHERE id='$iID'");
	$rows=mysql_fetch_array($sql);

    if(strlen($rows['title'] > 20))
	{
		$rows['title']=substr($rows['title'],"0","20");
		$rows['title']=$rows['$title']."...";
	}
	
	$rows['title']=htmlspecialchars($rows['title']);
	$rows['title'] = html_clean($rows['title']);

	$rows['content']=htmlspecialchars($rows[content]);
	$rows['content']=str_replace("\n","<br />",$rows[content]);
	$rows['content']=str_replace("<br>","<br />",$rows[content]);
	$rows['content']=str_replace("  ","&nbsp;",$rows[content]);
	$rows['content']=str_replace("/\\t/is","  ",$rows[content]);
	
	$exubb = new ExUbb();
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse_more();

	$rows['content'] = html_clean($rows['content']);

    $rows['content']=epost($rows[content]);
	$t->set_var(array(	"announceTitle"=>$rows['title'],
				"announceContent"=>$rows['content'],
				"announceAuthor"=>$rows['author'],
				"announceEmail"=>$rows['email'],
				"announceAddtime"=>$rows['addtime']));
}

######### 执行搜索 #########
function searchInfo($key)
{
	global $t, $x, $langfun;
	$i=0;
	$result=checkKeyword($key);
	if($result)
	{
		showError_show($result);
	}
	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE hidden='0' AND content LIKE '%$key%' OR title LIKE '%$key%'");
	$num=mysql_num_rows($sql);
	if($num == "0")
	{
		showError_show("$langfun[16]");
	}
	while($rows=mysql_fetch_array($sql))
	{
		if($i<$num)
		{
			$rows['title']=htmlspecialchars($rows['title']);
			$rows['title']=eregi_replace($key,"<span style=\"color: red;font-weight: bold;background-color: #F0F0F0;text-decoration:underline;\">$key</span>","$rows[title]");
			$key_id[$i]=$rows['id'];
			$key_title[$i]=$rows['title'];
			$key_visits[$i]=$rows['visits'];
			$i++;
		}
	}
	for($i=0; $i<$num; $i++)
	{
		$t->set_var(array(	"keyTitle"=>$key_title[$i],
					"keyID"=>$key_id[$i],
					"visits"=>$key_visits[$i],
					"keyword"=>$key));
		$t->parse("m_keyword","RowsKeyword",true);
	}
}

######### 查询文章数,访问量,BLOG数,评论数等 ######
function selectSomeInfo()
{
	global $t, $x;
	$sql=mysql_query("SELECT id FROM `$x[comment]`");
	$commentNum=mysql_num_rows($sql);  //论评总数
	$sql=mysql_query("SELECT id FROM `$x[trackback]`");
	$TrackbackNum=mysql_num_rows($sql);  //论评总数
	$sql=mysql_query("SELECT id FROM `$x[blog]`");
	$blogNum=mysql_num_rows($sql);     //Blog总数
	$sql=mysql_query("SELECT * FROM `$x[visits]`");
	$tmp=mysql_fetch_array($sql);
	$visited=$tmp['visits'];             //站点总访问量 
	$todayVisits=$tmp['todayVisits'];
	$userNum = getUserNum();
	$sql="SELECT * FROM `$x[global]`";    //Blog简介
	$tmpglobalInfo=mysql_fetch_array(mysql_query($sql));
	$blogdescription=$tmpglobalInfo['Description'];
	
	$t->set_var(array(	"visNum"=>$visited,
				"blogNum"=>$blogNum,
				"reNum"=>$commentNum,
				"tbNum"=>$TrackbackNum,
				"todNum"=>$todayVisits,
				"blogdescription"=>$blogdescription,
				"userNum" => $userNum));
}

###### 查询栏目相关信息 ######
function selectColumn()
{
	global $t,$x, $exurlon;
	$i=0;
	$sql="SELECT * FROM `$x[sort]` ORDER BY id ASC ";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num == 0)
	{
		$t->set_var("column","Null");
		$t->parse("m_column", "RowsColumn", true);
	}else {
		while($rows=mysql_fetch_array($result))
		{
			$enName[$i]=$rows['enName'];
			$cnName[$i]=$rows['cnName'];
			$description[$i]=$rows['description'];

			$tmpsql=mysql_query("SELECT * FROM `$x[blog]` WHERE sort='$rows[id]'");

			$colblogNum[$i]=mysql_num_rows($tmpsql);     //栏目blog数

			    if ($exurlon) {
			        $url_sort=$GLOBALS[expath]."/exurl.php/view/".$enName[$i].".html";
			    }else{
			        $url_sort=$GLOBALS[expath]."/index.php?play=view&amp;sort=".$enName[$i];
			    }
			$t->set_var(array(	"column"=>$cnName[$i],
						"sortName"=>$enName[$i],
						"url_sort"=>$url_sort,
						"colblogNum"=>$colblogNum[$i],
						"description"=>$description[$i]));
			$t->parse("m_column","RowsColumn",true);
		}
	}
}

###### 查询最新blog记录 ######
function selectNewblog()
{
	global $t, $x ,$info, $langfun, $exurlon;
	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE hidden='0' ORDER BY id DESC LIMIT 0,$info[listallnum]");
	$num=mysql_num_rows($sql);

	if($num == 0)
	{
		$t->set_var("newblogtitle","$langfun[17]");
		$t->parse("m_newblog","RowsNewblog",true);
	}
	$i=0;
	while($rows=mysql_fetch_array($sql))
	{
		$newblogid[$i]=$rows[id];
		$newblogtitle[$i]=$rows[title];
		$newblogtitle[$i]=clearUbb($newblogtitle[$i]);
		$newblogtitle[$i]=html_clean($newblogtitle[$i]);

		//---- 把标题截取18个字符 ----//
		$len = strlen($newblogtitle[$i]);
		if ($len <= $info[alltitlenum])
		{
			$newblogtitle[$i] = $newblogtitle[$i];
		}
		else
		{
			$newblogtitle[$i] = substr($newblogtitle[$i],"0",$info[alltitlenum]);
			$newblogtitle[$i] = $newblogtitle[$i]."...";
			$newblogtitle[$i] = htmlspecialchars($newblogtitle[$i]);
	    }
	    $i++;
	}
	
	for($a=0; $a<$i; $a++)
	{
if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$newblogid[$a].".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$newblogid[$a].".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$newblogid[$a];
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$newblogid[$a];
}
		$t->set_var(array("url_reply"=>$url_reply,"url_show"=>$url_show));
		$t->set_var(array("newblogid"=>$newblogid[$a],"newblogtitle"=>$newblogtitle[$a]));
		$t->parse("m_newblog","RowsNewblog",true);
	}
}

###### 查询最新评论记录 ######
function selectNewcomment()
{
	global $t, $x, $info, $langfun, $exurlon;
	$sql=mysql_query("SELECT * FROM `$x[comment]` ORDER BY id DESC LIMIT 0,$info[listallnum]");
	$num=mysql_num_rows($sql);
	if($num == 0)
	{
		$t->set_var("comment","$langfun[18]");
		$t->set_var("comid","s");
		$t->parse("m_comment", "RowsComment", true);
	}
	else {
$i=0;
	    while($rows=mysql_fetch_array($sql))
	    {
		$comID[$i]=$rows[commentID];
		$content[$i]=$rows[content];
		$content[$i]=clearUbb($content[$i]);
		$content[$i]=html_clean($content[$i]);
		$comid[$i]=$rows[id];

		//---- 把标题截取20个字符 ----//
		$len = strlen($content[$i]);
		if ($len <= $info[alltitlenum])
		{
			$content[$i] = $content[$i];
		}
		else
		{
			$content[$i] = substr($content[$i],"0",$info[alltitlenum]);
			$content[$i] = $content[$i]."...";
			$content[$i] = htmlspecialchars($content[$i]);
		    }

		    if ($exurlon) {
		        $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$comID[$i].".html";
		        $url_show=$GLOBALS[expath]."/exurl.php/show/".$comID[$i].".html";
		    }else{
		        $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$comID[$i];
		        $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$comID[$i];
		    }
		$t->set_var(array("url_reply"=>$url_reply,"url_show"=>$url_show));
		$t->set_var(array("id"=>$comID[$i],"comment"=>$content[$i]));
		$t->set_var("comid",$comid[$i]);
		$t->parse("m_comment", "RowsComment", true);
	    }
$i++;
	}
}

###### 查询最新引用记录 ######
function selectNewtrackback()
{
	global $t, $x, $info, $langfun, $exurlon;
	$sql=mysql_query("SELECT * FROM `$x[trackback]` ORDER BY id DESC LIMIT 0,$info[listallnum]");
	$num=mysql_num_rows($sql);
	if($num == 0)
	{
		$t->set_var("trackback","$langfun[19]");
		$t->set_var("tbid","s");
		$t->parse("m_trackback","RowsTrackback",true);
	}
	$i=0;
	while($rows=mysql_fetch_array($sql))
	{
		$tbID[$i]=$rows[TrackbackID];
		$content[$i]=$rows[excerpt];
		$content[$i]=clearUbb($content[$i]);
		$content[$i]=html_clean($content[$i]);
		$tbid[$i]=$rows[id];

		//---- 把标题截取字符 ----//
		$len = strlen($content[$i]);
		if ($len <= $info[alltitlenum])
		{
			$content[$i] = $content[$i];
		}
		else
		{
			$content[$i] = substr($content[$i],"0",$info[alltitlenum]);
			$content[$i] = $content[$i]."...";
			$content[$i] = htmlspecialchars($content[$i]);
	    }
	    $i++;
	}

	for($a=0; $a<$i; $a++)
	{
if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$tbID[$a].".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$tbID[$a].".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$tbID[$a];
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$tbID[$a];
}
		$t->set_var(array("url_reply"=>$url_reply,"url_show"=>$url_show));
		$t->set_var(array("id"=>$tbID[$a],"trackback"=>$content[$a]));
		$t->set_var("tbid",$tbid[$a]);
		$t->parse("m_trackback", "RowsTrackback", true);
	}
}

###### 查询最新友情链接 ######
function selectlinks()
{
	global $t, $x, $langfun;
    $sql=mysql_query("SELECT *,RAND() AS randid FROM `$x[links]` WHERE visible = '1' ORDER BY randid LIMIT 0,6"); 
	$num=mysql_num_rows($sql);
	if($num == 0)
	{
		$t->set_var(array("homepage"=>"$langfun[20]",
		                  "visits"=>"0"));
		$t->parse("m_links", "RowsLinks", true);
	}
	$i=0;
    while($rows=mysql_fetch_array($sql))
    {
	    $id[$i]=$rows['id'];
	    $url[$i]=$rows['url'];
	    $homepage[$i]=$rows['homepage'];
		$logoURL[$i]=$rows['logoURL'];
	    $description[$i]=$rows['description'];
	    $visits[$i]=$rows['visits'];
	    $i++;
    }
    for($iii=0; $iii<$i; $iii++)
    {
		if($logoURL[$iii] != "")
		{
			$homepage[$iii]="<img src=\"$logoURL[$iii]\" align=\"middle\" border=\"0\" alt=\"logo\" onerror=\"this.src='http://www.madcn.com/madcg/images/logo/logo1.gif'\" />";
		}
	    $t->set_var(array(	"id"=>$id[$iii],
				"url"=>$url[$iii],
				"homepage"=>$homepage[$iii],
				"description"=>$description[$iii],
				"visits"=>$visits[$iii]));
		$t->parse("m_links", "RowsLinks", true);
    }
/* 重置 go */
		$t->set_var(array("homepage"=>"","visits"=>"0"));
/* 重置 end */
}
/**
 * 随机显示5条友情链接,并用phplib的模版功能循环显示出来.
 *
 */
function selectLinks_2() {
	
	global $t, $x, $langfun;
    $sql = mysql_query("SELECT *,RAND() AS randid FROM `$x[links]` WHERE visible = '1' ORDER BY randid"); 
	$num = mysql_num_rows($sql);
	if($num == 0) {
		$t->set_var(array("homepage" => "$langfun[20]",
		                  "visits" => "0"));
		$t->parse("m_links", "RowsLinks", true);
	}
	else {
		while($rows = mysql_fetch_array($sql)) {
			$t->set_var(array(	"id" => $rows['id'],
						"url" => $rows['url'],
						"homepage" => $rows['homepage'],
						"logoURL" => $rows['logoURL'],
						"description" => $rows['description'],
						"visits" => $rows['visits']));
			$t->parse("m_links", "RowsLinks", true);
		}
	}
}

###### 评论页面显示当前BLOG ######
function showCurrentBlog($curID,$make,$key)
{
	global $t, $x, $langfun, $exurlon, $nocom, $info;
	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$curID'");
	$tmpBlog=mysql_fetch_array($sql);
	$sql=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$curID'");
	$reNum=mysql_num_rows($sql);
	$sql=mysql_query("SELECT TrackbackID FROM `$x[trackback]` WHERE TrackbackID='$curID'");
	$tbNum=mysql_num_rows($sql);
$nocom = $tmpBlog['nocom'];
$hidden_on = "0";
if($tmpBlog['hidden']=="1") { 
	if(!checkunregist()) { 
		if(fun_checkUserUid($_SESSION[exPass], 1)) { $hidden_on = "1"; }
	}else{
		$hidden_on = "1";
	}
}

if($hidden_on == "1")
{		$t->set_var(array(	"blogID"=>$curID,
					"currentID"=>$curID,
					"url_reply"=>"",
					"url_show"=>"",
					"blogTitle"=>"$langfun[21]",
					"blogContent"=>"",
					"blogSort"=>"$langfun[22]",
					"blogAuthor"=>"$langfun[22]",
					"blogEmail"=>"",
					"blogAddtime"=>"0000-00-00 00:00",
					"blogVisits"=>"0",
					"TrackbackNum"=>"0",
					"currentSort"=>"$langfun[21]",
					"viewNum" =>"0",
					"blogWeather" => "$GLOBALS[expath]/images/weather/null.gif"));
}else
	{

	$tmpBlog['title']=htmlspecialchars($tmpBlog['title']);
$querysort="SELECT * FROM `$x[sort]` WHERE id='$tmpBlog[sort]'";
$resultSort=mysql_fetch_array(mysql_query($querysort));
$sort=$resultSort['cnName'];

	$tmpBlog['title']=html_clean($tmpBlog['title']);
	$tmpBlog['title_ed']=urlencode($tmpBlog['title']);
if($tmpBlog[html]=="0"){
	$tmpBlog['content']=htmlspecialchars($tmpBlog['content']);
	$tmpBlog['content']=str_replace("\n","<br />",$tmpBlog['content']);
	$tmpBlog['content']=str_replace("<br>","<br />",$tmpBlog[content]);
	$tmpBlog['content']=str_replace("  ","&nbsp;",$tmpBlog[content]);
	$tmpBlog['content']=str_replace("/\\t/is","  ",$tmpBlog[content]);

	$exubb = new ExUbb();
	$exubb->setString($tmpBlog['content']);
	$tmpBlog['content'] = $exubb->parse_more();

}
	if(!trim($make)=="" && !trim($key)=="")
	{
		$tmpBlog['content']=highlight($tmpBlog['content'],$key);
	}
# 词汇解释 开始
	$acronym_sql=mysql_query("SELECT * FROM `$x[keyword]` ORDER BY id DESC");
	if(mysql_num_rows($acronym_sql))
	{
		while($acronym_rows=mysql_fetch_array($acronym_sql))
		{
			$tmpBlog['content'] = acronym($tmpBlog['content'],$acronym_rows['word'],$acronym_rows['content'],$acronym_rows['url']);
		}
	}
# 词汇解释 结束
	$tmpBlog['content'] = html_clean($tmpBlog['content']);

	$tmpBlog['content']=epost($tmpBlog['content']);

	if ($exurlon) {
	    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$curID.".html";
	    $url_show=$GLOBALS[expath]."/exurl.php/show/".$curID.".html";
	    $url_w=$info[siteUrl]."/exurl.php/reply/".$curID.".html";
	}else{
	    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$curID;
	    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$curID;
	    $url_w=$info[siteUrl]."/index.php?play=reply&amp;id=".$curID;
	}
		$t->set_var(array("url_w"=>$url_w,"url_reply"=>$url_reply,"url_show"=>$url_show));
		$t->set_var(array(	"blogTitle"=>$tmpBlog['title'],
					"blogTitle_ed"=>$tmpBlog['title_ed'],
					"blogContent"=>$tmpBlog['content'],
					"blogAuthor"=>$tmpBlog['author'],
					"blogEmail"=>$tmpBlog['email'],
					"blogSort"=>$sort,
					"blogSortid"=>$tmpBlog['sort'],
					"blogAddtime"=>$tmpBlog['addtime'],
					"blogKeyword"=>$tmpBlog['keyword'],
					"blogVisits"=>$reNum,
					"TrackbackNum"=>$tbNum,
					"blogWeather" => "$GLOBALS[expath]/images/weather/".$tmpBlog['weather'].".gif",
					"viewNum" => $tmpBlog['visits'],
					"blogID"=>$curID,
					"repon"=>$repon,
					"reptxt"=>$key,
					"currentSort"=>$tmpBlog['title'],
					"currentID"=>$curID));
	}

   if (!checkunregist()) {
		$t->set_block("b_all","RowLogincom","RowsLogincom");
		$t->parse("RowsLogincom","RowLogincom",true);
		$t->parse("PutLogincom","RowsLogincom");
		$t->set_var("exBlogUserid", $_SESSION['exPass']);
    }else{
		$t->set_block("b_all","RownoLogincom","RowsnoLogincom");
		$t->parse("RowsnoLogincom","RownoLogincom",true);
		$t->parse("PutLogincom","RowsnoLogincom");
    }

		$t->parse("m_blog", "RowsBlog_show");
}

###### 显示所选文章 ######
function showArticle($curID)
{
	global $t, $x, $langfun, $exurlon, $db;

//	$query = "SELECT * FROM `$x[blog]` WHERE id='$curID'";
//	$db->query($query);
//	$db->next_record();

	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$curID'");
	$rows=mysql_fetch_array($sql);
$hidden_on = "0";
if($rows['hidden']=="1") { 
	if(!checkunregist()) { 
		if(fun_checkUserUid($_SESSION[exPass], 1)) { $hidden_on = "1"; }
	}else{
		$hidden_on = "1";
	}
}

if($hidden_on == "1")
	{ $t->set_var(array(	"artTitle"=>"$langfun[21]",
				"author"=>"$langfun[22]",
				"sort"=>"$langfun[22]",
				"time"=>"0000-00-00 00:00",
				"visits"=>"0",
				"blogID"=>$curID,
				"artContent"=>""));
	}
else
	{
//		$title = htmlspecialchars($db->Record['title']);
		$rows['title']=htmlspecialchars($rows['title']);
$querysort="SELECT * FROM `$x[sort]` WHERE id='$rows[sort]'";
$resultSort=mysql_fetch_array(mysql_query($querysort));
$sort=$resultSort['cnName'];
if($rows['html']=="0"){
		$rows['content']=htmlspecialchars($rows['content']);
		$rows['content']=str_replace("\n","<br />",$rows['content']);
		$rows['content']=str_replace("<br>","<br />",$rows[content]);
		$rows['content']=str_replace("  ","&nbsp;",$rows[content]);
		$rows['content']=str_replace("/\\t/is","  ",$rows[content]);
	$exubb = new ExUbb();
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse_more();
}
	$rows['content']=epost($rows['content']);

	$rows['title'] = html_clean($rows['title']);
	$rows['content'] = html_clean($rows['content']);

	}

if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$curID.".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$curID.".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$curID;
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$curID;
}
		$t->set_var(array("url_reply"=>$url_reply,"url_show"=>$url_show));
	$t->set_var(array(	"artTitle"=>$rows['title'],
				"author"=>$rows['author'],
				"sort"=>$sort,
				"time"=>$rows['addtime'],
				"blogKeyword"=>$rows['keyword'],
				"visits"=>$rows['visits'],
				"blogID"=>$curID,
				"artContent"=>$rows['content']));
}
###### 显示当前BLOG的评论 ######
function showCurrentBlogComment($curID, $nocom)
{
	global $t, $x, $langfun, $info;
if ($nocom == "1") {
		$t->set_var("reContent","$langfun[49]");
		$t->set_var("comid","");
		$t->set_var("compid","");
		$t->parse("m_replay", "RowsReply", true);
		return 0;
}
	$sql2=mysql_query("SELECT * FROM `$x[comment]` WHERE commentID='$curID' ORDER BY id DESC");
	$reNum2=mysql_num_rows($sql2);
	if($reNum2==0)
	{
		$t->set_var("reContent","$langfun[23]");
		$t->set_var("comid","");
		$t->set_var("compid","");
		$t->parse("m_replay", "RowsReply", true);
	}
	else
	{

		while($rows=mysql_fetch_array($sql2))	//while($db->next_record())
		{
			$rows['content']=htmlspecialchars($rows['content']);

	            $rows['content']=str_replace("\n","<br />",$rows['content']);
	            $rows['content']=str_replace("<br>","<br />",$rows[content]);
	            $rows['content']=str_replace("  ","&nbsp;",$rows[content]);
		    $rows['content']=str_replace("/\\t/is","  ",$rows[content]);
			$exubb = new ExUbb();
			$exubb->setString($rows['content']);
			$rows['content'] = $exubb->parse();
		    $rows['content']=epost($rows['content']);
		    $rows['content'] = html_clean($rows['content']);
		    $rows['author'] = html_clean($rows['author']);
		    $rows['email'] = html_clean($rows['email']);
		    $rows['homepage'] = html_clean($rows['homepage']);
		    $rows['qq'] = html_clean($rows['cqq']);
				$re_content[$i]=$rows['content'];
				$re_author[$i]=$rows['author'];
				$re_addtime[$i]=$rows['addtime'];
				$re_email[$i]=$rows['email'];
				$re_homepage[$i]=$rows['homepage'];
				$re_qq[$i]=$rows['qq'];
				$re_id[$i]=$rows['id'];
				$re_pid[$i]=$i+1;
#################
	if($re_email[$i]!="")$gravataraddr=$re_email[$i];
		if ($gravataraddr) {
			$defaultgravatar=urlencode($info[siteUrl]."/images/gravatar.jpg");	$grav_url="http://www.gravatar.com/avatar.php?gravatar_id=".md5($gravataraddr)."&amp;default=$defaultgravatar";
			$gravatar[$i]="<a href=\"$grav_url\" target=\"_blank\"><img src=\"$grav_url\" alt=\"Gravatar\" align=\"left\" width=\"40\" style=\"border: 1px solid #000\" /></a>";
		} else {
			$gravatar[$i]="";
		}
#################
			$t->set_var(array(	"reContent"=>$re_content[$i],
						"comid"=>$re_id[$i],
						"compid"=>$re_pid[$i],
						"reAuthor"=>$re_author[$i],
						"reEmail"=>$re_email[$i],
						"reAddtime"=>$re_addtime[$i],
						"homepage"=>$re_homepage[$i],
						"gravatar"=>$gravatar[$i],
						"qq"=>$re_qq[$i]));
			$t->parse("m_replay", "RowsReply", true);
				$i++;
		}		
	}
}

###### 显示当前BLOG的引用 ######
function showCurrentTrackback($curID ,$nocom)
{
	global $t, $x, $langfun;

if ($nocom == "1") {
		$t->set_var("tbid","");
		$t->set_var("tbpid","");
		$t->set_var("tbexcerpt","$langfun[50]");
		$t->parse("m_Tb","RowsTb", true);
		return 0;
}
	$sql2=mysql_query("SELECT * FROM `$x[trackback]` WHERE TrackbackID='$curID' ORDER BY id DESC");
	$reNum2=mysql_num_rows($sql2);
	if($reNum2==0)
	{
		$t->set_var("tbid","");
		$t->set_var("tbpid","");
		$t->set_var("tbexcerpt","$langfun[24]");
		$t->parse("m_Tb","RowsTb", true);
	}
	else
	{
		$i=0;
		while($rows=mysql_fetch_array($sql2))
		{
			$rows['excerpt']=htmlspecialchars($rows['excerpt']);

	            $rows['excerpt']=str_replace("\n","<br />",$rows['excerpt']);
	            $rows['excerpt']=str_replace("<br>","<br />",$rows[excerpt]);
	            $rows['excerpt']=str_replace("  ","&nbsp;",$rows[excerpt]);
	            $rows['excerpt']=str_replace("/\\t/is","  ",$rows[excerpt]);
	$rows['excerpt'] = html_clean($rows['excerpt']);
	$rows['title'] = html_clean($rows['title']);
	$rows['blog_name'] = html_clean($rows['blog_name']);
	$rows['url'] = html_clean($rows['url']);
				$re_excerpt[$i]=$rows['excerpt'];
				$re_title[$i]=$rows['title'];
				$re_addtime[$i]=$rows['addtime'];
				$re_blog_name[$i]=$rows['blog_name'];
				$re_url[$i]=$rows['url'];
				$re_id[$i]=$rows['id'];
				$re_pid[$i]=$i+1;

			$t->set_var(array(	"tbtitle"=>$re_title[$i],
						"tbid"=>$re_id[$i],
						"tbpid"=>$re_pid[$i],
						"tbexcerpt"=>$re_excerpt[$i],
						"tbaddtime"=>$re_addtime[$i],
						"tbblog_name"=>$re_blog_name[$i],
						"tburl"=>$re_url[$i]));
		$t->parse("m_Tb","RowsTb", true);
				$i++;
		}
	}
}

###### 查询当前分类所有文章标题 ######
function selectCurSortArt($isort)
{
	global $t, $prev_page, $next_page, $total_page, $x, $info, $langfun, $exurlon;

	$exubb = new ExUbb();

	$sql=mysql_query("SELECT * FROM `$x[sort]` WHERE enName='$isort'");
    $sortName=mysql_fetch_array($sql);

    $page_size=$info[listblognum];
    $queryArt="SELECT * FROM `$x[blog]` WHERE sort='$sortName[id]' ORDER BY id DESC";
    $result=mysql_query($queryArt);
    $artSum=mysql_num_rows($result);
    $total_page = ceil($artSum/$page_size);
    $page = intval($_GET['page']);
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
    $prev_page=$page-1;
    $next_page=$page+1;
    $sql=mysql_query("SELECT * FROM `$x[blog]` WHERE sort='$sortName[id]' ORDER BY id DESC LIMIT $start,$page_size");

    $num=mysql_num_rows($sql);
    if($num == 0)
    {
		    $t->set_var(array(	"blogID"=>"0",
					"url_reply"=>"",
					"url_show"=>"",
					"blogTitle"=>"$langfun[25]",
					"blogContent"=>"$langfun[25]",
					"blogSort"=>"NULL",
					"blogAuthor"=>"WebMaster",
					"blogEmail"=>"",
					"blogAddtime"=>"0000-00-00 00:00",
					"blogVisits"=>"0",
					"TrackbackNum"=>"0",
					"currentSort"=>"$langfun[25]",
					"viewNum" =>"0",
					"blogWeather" => "$GLOBALS[expath]/images/weather/null.gif"));
	    $t->parse("m_blog", "RowsBlog", true);
    }
    else
    {
	 $i=0;
	 while($rows=mysql_fetch_array($sql))
	    {
		$hidden_on = "0";
		if($tmpBlog['hidden']=="1") { 
			if(!checkunregist()) { 
				if(fun_checkUserUid($_SESSION[exPass], 1)) { $hidden_on = "1"; }
			}else{
				$hidden_on = "1";
			}
		}

		if($hidden_on == "1")
				{   
					$b_id[$i]=$rows['id'];
					$b_title[$i]="$langfun[21]";
					$b_author[$i]="$langfun[22]";
					$b_addtime[$i]="0000-00-00 00:00";
					$b_sort[$i]="$langfun[22]";
					$b_weather[$i]="null";
					$commentNum[$i]="0";
					$TrackbackNum[$i]="0";
					$b_viewNum[$i]="0";
					$b_content[$i]="";
				}
			else
				{
            if($rows['summarycontent']!="") {
                 $rows['content']=$rows['summarycontent'];
            }else{
############
               if($info['summarynum']=="0") {
			$rows['content']=$rows['content'];
               }else{
			$rows['content']=substr($rows['content'],"0",$info[summarynum]);
			$rows['content']=$rows['content']."...";
               }
############
            }
		    	$rows['title']=htmlspecialchars($rows['title']);
if($rows['html']=="0"){
			$rows['content']=htmlspecialchars($rows['content']);
			$rows['content']=str_replace("\n","<br />",$rows['content']);
			$rows['content']=str_replace("<br>","<br />",$rows[content]);
			$rows['content']=str_replace("  ","&nbsp;",$rows[content]);
			$rows['content']=str_replace("/\\t/is","  ",$rows[content]);
		    	$exubb->setString($rows['content']);
			$rows['content'] = $exubb->parse_more();
}
# 词汇解释 开始
	$acronym_sql=mysql_query("SELECT * FROM `$x[keyword]` ORDER BY id DESC");
	if(mysql_num_rows($acronym_sql))
	{
		while($acronym_rows=mysql_fetch_array($acronym_sql))
		{
			$rows['content'] = acronym($rows['content'],$acronym_rows['word'],$acronym_rows['content'],$acronym_rows['url']);
		}
	}
# 词汇解释 结束
	            $rows['content']=epost($rows['content']);

	$rows['title'] = html_clean($rows['title']);
	$rows['content'] = html_clean($rows['content']);
	$rows['author'] = html_clean($rows['author']);
	$rows['email'] = html_clean($rows['email']);
	$rows['sort'] = html_clean($rows['sort']);


				$b_id[$i]=$rows['id'];
				$b_title[$i]=$rows['title'];
				$b_author[$i]=$rows['author'];
				$b_email[$i]=$rows['email'];
				$b_content[$i]=$rows['content'];
				$b_addtime[$i]=$rows['addtime'];
				$b_sort[$i]=$sortName[cnName];
//$querysort="SELECT * FROM `$x[sort]` WHERE id='$rows[sort]'";
//$resultSort=mysql_fetch_array(mysql_query($querysort));
//$b_sort[$i]=$resultSort['cnName'];
				$b_viewNum[$i] = $rows['visits'];
				$b_weather[$i] = $rows['weather'];
	

				$query=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$b_id[$i]'");
				$commentNum[$i]=mysql_num_rows($query);
				$query=mysql_query("SELECT TrackbackID FROM `$x[trackback]` WHERE TrackbackID='$b_id[$i]'");
				$TrackbackNum[$i]=mysql_num_rows($query);
				}
	     		$i++;
				
	    }
	    for($i=0; $i<$num; $i++)
	    {
if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$b_id[$i].".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$b_id[$i].".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$b_id[$i];
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$b_id[$i];
}
		    $t->set_var(array(	"blogID"=>$b_id[$i],
					"url_reply"=>$url_reply,
					"url_show"=>$url_show,
					"blogTitle"=>$b_title[$i],
					"blogContent"=>$b_content[$i],
					"blogSort"=>$b_sort[$i],
					"blogAuthor"=>$b_author[$i],
					"blogEmail"=>$b_email[$i],
					"blogAddtime"=>$b_addtime[$i],
					"blogVisits"=>$commentNum[$i],
					"TrackbackNum"=>$TrackbackNum[$i],
					"currentSort"=>$sortName['cnName'],
					"viewNum" => $b_viewNum[$i],
					"blogWeather" => "$GLOBALS[expath]/images/weather/".$b_weather[$i].".gif"));
	    $t->parse("m_blog", "RowsBlog", true);
	    }
    }
    if($prev_page >= 1)
    {
	    $prev_page=$page-1;
    }
    else
    {
	    $prev_page=$prev_page;
    }
    if($next_page>$total_page)
    {
	    $next_page=$next_page-1;
    }
    else
    {
	    $next_page=$page+1;
    }
    
if ($exurlon) {
    $url_firstpage=$GLOBALS[expath]."/exurl.php/view/".$isort.".html";
    $url_prevpage=$GLOBALS[expath]."/exurl.php/view/".$isort."/".$prev_page.".html";
    $url_nextpage=$GLOBALS[expath]."/exurl.php/view/".$isort."/".$next_page.".html";
    $url_totalpage=$GLOBALS[expath]."/exurl.php/view/".$isort."/".$total_page.".html";
}else{
    $url_firstpage=$GLOBALS[expath]."/index.php?play=view&amp;sort=".$isort;
    $url_prevpage=$GLOBALS[expath]."/index.php?play=view&amp;sort=".$isort."&amp;page=".$prev_page;
    $url_nextpage=$GLOBALS[expath]."/index.php?play=view&amp;sort=".$isort."&amp;page=".$next_page;
    $url_totalpage=$GLOBALS[expath]."/index.php?play=view&amp;sort=".$isort."&amp;page=".$total_page;
}
	$t->set_var(array(	"sortName"=>$isort,
				"curPage"=>$page,
				"totalPage"=>$total_page,
				"url_firstpage"=>$url_firstpage,
				"url_prevpage"=>$url_prevpage,
				"url_nextpage"=>$url_nextpage,
				"url_totalpage"=>$url_totalpage,
				"prev_page"=>$prev_page,
				"next_page"=>$next_page,
				"total_page"=>$total_page));
}

###### 查询链接信息 ######
function selectAllLinks()
{
	global $t, $x, $langfun, $db;

	$sql=mysql_query("SELECT * FROM `$x[links]` WHERE visible = '1' ORDER BY id ASC");
    $linkNum=mysql_num_rows($sql);

    if($linkNum > 0)
    {   

	    while($rows2=mysql_fetch_array($sql))
	    {
		    if($i<$linkNum)
		    {
	    		$link_id[$i]=$rows2['id'];
	    		$link_homepage[$i]=$rows2['homepage'];
	    		$link_url[$i]=$rows2['url'];
				$link_logo[$i]=$rows2['logoURL'];
		    	$link_visits[$i]=$rows2['visits'];
			    $link_description[$i]=$rows2['description'];
				
		    }

			if($link_logo[$i] != "")
			{
				$tmp="<img src=\"$link_logo[$i]\" alt=\"$link_description[$i]\" border=\"0\" align=\"middle\" onload=\"if(this.width>88) this.width=88;\" />";
				$t->set_var("logoURL",$tmp);
			}
			else
			{
				$t->set_var("logoURL","");
			}
			$t->set_var(array("id"=>$link_id[$i],
			                  "homepage"=>$link_homepage[$i],
			                  "url"=>$link_url[$i],
			                  "visits"=>$link_visits[$i],
			                  "description"=>$link_description[$i]));
			

		    $t->parse("m2_links", "Rows2Links", true);
	    }
	
    }
    else
    {
	    $t->set_var("homepage","$langfun[26]");
		    $t->parse("m2_links", "Rows2Links", true);
    }
}


###### 查询最新Blog记录并分页 ######
function selectBlog()
{
	global $t, $prev_page, $next_page, $total_page, $x, $info, $langfun, $exurlon;
	
	$exubb = new ExUbb();

    $page_size=$info[listblognum];
    $queryBlog="SELECT * FROM `$x[blog]` WHERE top = '0' ORDER BY id DESC ";
    $result=mysql_query($queryBlog);
    $artSum=mysql_num_rows($result);
    $total_page = ceil($artSum/$page_size);
    $page = intval($_GET['page']);
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
    $prev_page=$page-1;
    $next_page=$page+1;

	$i = 0;   // blog数组下标

	//先存储置顶贴
	$query_top = "SELECT * FROM `$x[blog]` WHERE top = '1' ORDER BY id DESC";
	$result_top = mysql_query($query_top);
	while($rows_top = mysql_fetch_array($result_top))
	{
		$hidden_on = "0";
		if($rows_top['hidden']=="1") { 
			if(!checkunregist()) { 
				if(fun_checkUserUid($_SESSION[exPass], 1)) { $hidden_on = "1"; }
			}else{
				$hidden_on = "1";
			}
		}

		if($hidden_on == "1")
		{   
			$blog_id[$i]=$rows_top['id'];
			$blog_title[$i]="$langfun[21]";
			$blog_author[$i]="$langfun[22]";
			$blog_addtime[$i]="0000-00-00 00:00";
			$blog_sort[$i]="$langfun[22]";
			$blog_visits[$i]="0";
			$TrackbackNum[$i]="0";
			$blog_weather[$i]="null";
			$blog_content[$i]="";
			$blog_num[$i]="0";
		}
		else
		{
            if($rows_top['summarycontent']!="") {
                 $rows_top['content']=$rows_top['summarycontent'];
            }else{
############
               if($info['summarynum']=="0") {
		         $rows_top['content']=$rows_top['content'];
               }else{
		         $rows_top['content']=substr($rows_top['content'],"0",$info[summarynum]);
	             $rows_top['content']=$rows_top['content']."...";
               }
############
            }
		$rows_top['title']=htmlspecialchars($rows_top['title']);
$querysort="SELECT * FROM `$x[sort]` WHERE id='$rows_top[sort]'";
$resultSort=mysql_fetch_array(mysql_query($querysort));
$rows_top['sort']=$resultSort['cnName'];

if($rows_top['html']=="0"){
	    $rows_top['content']=htmlspecialchars($rows_top['content']);
	    $rows_top['content']=str_replace("\n","<br />",$rows_top['content']);
	    $rows_top['content']=str_replace("<br>","<br />",$rows_top[content]);
	    $rows_top['content']=str_replace("  ","&nbsp;",$rows_top[content]);
	    $rows_top['content']=str_replace("/\\t/is","  ",$rows_top[content]);

		$exubb->setString($rows_top['content']);
		$rows_top['content'] = $exubb->parse_more();
}
# 词汇解释 开始
	$acronym_sql=mysql_query("SELECT * FROM `$x[keyword]` ORDER BY id DESC");
	if(mysql_num_rows($acronym_sql))
	{
		while($acronym_rows=mysql_fetch_array($acronym_sql))
		{
			$rows_top['content'] = acronym($rows_top['content'],$acronym_rows['word'],$acronym_rows['content'],$acronym_rows['url']);
		}
	}
# 词汇解释 结束
		$rows_top['content']=epost($rows_top['content']);

	$rows_top['title'] = html_clean($rows_top['title']);
	$rows_top['content'] = html_clean($rows_top['content']);
	$rows_top['author'] = html_clean($rows_top['author']);
	$rows_top['email'] = html_clean($rows_top['email']);
	$rows_top['sort'] = html_clean($rows_top['sort']);
		
		$blog_top[$i] = "$langfun[27]";
		$blog_id[$i] = $rows_top['id'];
		$blog_title[$i] = $rows_top['title'];
		$blog_content[$i] = $rows_top['content'];
		$blog_author[$i] = $rows_top['author'];
		$blog_email[$i] = $rows_top['email'];
		$blog_sort[$i] = $rows_top['sort'];
		$blog_addtime[$i] = $rows_top['addtime'];
		$blog_weather[$i] = $rows_top['weather'];
		$blog_num[$i] = $rows_top['visits'];
		    	//---- 查询该BLOG的论评数 ----//
			    $sql2=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$blog_id[$i]'");
			    $blog_visits[$i]=mysql_num_rows($sql2);
		$sql2=mysql_query("SELECT TrackbackID FROM `$x[trackback]` WHERE TrackbackID='$blog_id[$i]'");
		$TrackbackNum[$i]=mysql_num_rows($sql2);

		}

if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$blog_id[$i].".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$blog_id[$i].".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$blog_id[$i];
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$blog_id[$i];
}
		$t->set_var( array("isTop" =>$blog_top[$i],
				"url_reply"=>$url_reply,
				"url_show"=>$url_show,
				"blogID"=>$blog_id[$i],
				"blogTitle"=>$blog_title[$i],
				"blogContent"=>$blog_content[$i],
				"blogAuthor"=>$blog_author[$i],
				"blogEmail"=>$blog_email[$i],
				"blogSort"=>$blog_sort[$i],
				"blogAddtime"=>$blog_addtime[$i],
				"blogVisits"=>$blog_visits[$i],
				"TrackbackNum"=>$TrackbackNum[$i],
				"blogWeather" => "$GLOBALS[expath]/images/weather/".$blog_weather[$i].".gif",
				"viewNum" => $blog_num[$i]));
		$i++;
	    $t->parse("m_Topblog", "RowsTopBlog", true);
	}

    if(mysql_num_rows($result_top) != 0)
    {
	$t->parse("PutTopBlogs", "m_Topblog");
    }
/* 重置数据 go */
		$t->set_var( array("isTop" =>"",
				"url_reply"=>"",
				"url_show"=>"",
				"blogID"=>"",
				"blogTitle"=>"",
				"blogContent"=>"",
				"blogAuthor"=>"",
				"blogEmail"=>"",
				"blogSort"=>"",
				"blogAddtime"=>"",
				"blogVisits"=>"",
				"TrackbackNum"=>"",
				"blogWeather" => "",
				"viewNum" => ""));
/* 重置数据 end */

	$i = 0;   // blog数组下标

    $sql=mysql_query("SELECT * FROM `$x[blog]` WHERE top = '0' ORDER BY id DESC LIMIT $start,$page_size");
	$num=mysql_num_rows($sql);

    if($num == 0)
    {
		$t->set_var( array("isTop" =>"",
				"url_reply"=>"",
				"url_show"=>"",
				"blogID"=>"0",
				"blogTitle"=>"$langfun[28]",
				"blogContent"=>"$langfun[28]",
				"blogAuthor"=>"WebMaster",
				"blogEmail"=>"",
				"blogSort"=>"NULL",
				"blogAddtime"=>"0000-00-00 00:00",
				"blogVisits"=>"0",
				"TrackbackNum"=>"0",
				"blogWeather" => "$GLOBALS[expath]/images/weather/null.gif",
				"viewNum" => "0"));
	    $t->parse("m_blog", "RowsBlog", true);
    }
    else
    {
	while($rows=mysql_fetch_array($sql))
	    {
		$hidden_on = "0";
		if($rows['hidden']=="1") { 
			if(!checkunregist()) { 
				if(fun_checkUserUid($_SESSION[exPass], 1)) { $hidden_on = "1"; }
			}else{
				$hidden_on = "1";
			}
		}

		if($hidden_on == "1")
		{   
			$blog_id[$i]=$rows['id'];
			$blog_title[$i]="$langfun[21]";
			$blog_author[$i]="$langfun[22]";
			$blog_addtime[$i]="0000-00-00 00:00";
			$blog_sort[$i]="$langfun[22]";
			$blog_visits[$i]="0";
			$TrackbackNum[$i]="0";
			$blog_num[$i]="0";
			$blog_weather[$i]="null";
			$blog_content[$i]="";
		}
	 	else
		{
            if($rows['summarycontent']!="") {
                 $rows['content']=$rows['summarycontent'];
            }else{
############
               if($info['summarynum']=="0") {
		         $rows['content']=$rows['content'];
               }else{
		         $rows['content']=substr($rows['content'],"0",$info[summarynum]);
	             $rows['content']=$rows['content']."...";
               }
############
            }
		    	$rows['title']=htmlspecialchars($rows['title']);
$querysort="SELECT * FROM `$x[sort]` WHERE id='$rows[sort]'";
$resultSort=mysql_fetch_array(mysql_query($querysort));
$rows['sort']=$resultSort['cnName'];
if($rows['html']=="0"){
	$rows['content']=htmlspecialchars($rows['content']);
	$rows['content']=str_replace("\n","<br />",$rows['content']);
	$rows['content']=str_replace("<br>","<br />",$rows[content]);
	$rows['content']=str_replace("  ","&nbsp;",$rows[content]);
	$rows['content']=str_replace("/\\t/is","  ",$rows[content]);
	
				$exubb->setString($rows['content']);
		    	$rows['content'] = $exubb->parse_more();
}
# 词汇解释 开始
	$acronym_sql=mysql_query("SELECT * FROM `$x[keyword]` ORDER BY id DESC");
	if(mysql_num_rows($acronym_sql))
	{
		while($acronym_rows=mysql_fetch_array($acronym_sql))
		{
			$rows['content'] = acronym($rows['content'],$acronym_rows['word'],$acronym_rows['content'],$acronym_rows['url']);
		}
	}
# 词汇解释 结束
				$rows['content']=epost($rows['content']);

	$rows['title'] = html_clean($rows['title']);
	$rows['content'] = html_clean($rows['content']);
	$rows['author'] = html_clean($rows['author']);
	$rows['email'] = html_clean($rows['email']);
	$rows['sort'] = html_clean($rows['sort']);

		    	$blog_id[$i] = $rows['id'];
		    	$blog_title[$i] = $rows['title'];
		    	$blog_content[$i] = $rows['content'];
		    	$blog_author[$i] = $rows['author'];
				$blog_email[$i] = $rows['email'];
				$blog_sort[$i] = $rows['sort'];
		    	$blog_addtime[$i] = $rows['addtime'];
				$blog_weather[$i] = $rows['weather'];
				$blog_num[$i] = $rows['visits'];

		    	//---- 查询该BLOG的论评数 ----//
			    $sql2=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$blog_id[$i]'");
			    $blog_visits[$i]=mysql_num_rows($sql2);
		$sql2=mysql_query("SELECT TrackbackID FROM `$x[trackback]` WHERE TrackbackID='$blog_id[$i]'");
		$TrackbackNum[$i]=mysql_num_rows($sql2);
		}
			    $i++;
	    }
	    for($i=0; $i<$num+$j; $i++)
	    {
if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$blog_id[$i].".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$blog_id[$i].".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$blog_id[$i];
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$blog_id[$i];
}
		    $t->set_var(array(	"isTop" =>$blog_top[$i],
					"url_reply"=>$url_reply,
					"url_show"=>$url_show,
					"blogID"=>$blog_id[$i],
					"blogTitle"=>$blog_title[$i],
					"blogContent"=>$blog_content[$i],
					"blogAuthor"=>$blog_author[$i],
					"blogEmail"=>$blog_email[$i],
					"blogSort"=>$blog_sort[$i],
					"blogAddtime"=>$blog_addtime[$i],
					"blogVisits"=>$blog_visits[$i],
					"TrackbackNum"=>$TrackbackNum[$i],
					"blogWeather" => "$GLOBALS[expath]/images/weather/".$blog_weather[$i].".gif",
					"viewNum" => $blog_num[$i]));
	    $t->parse("m_blog", "RowsBlog", true);
	    }
		
		
    }
	

    if($prev_page >= 1)
    {
    	$prev_page=$page-1;
    }
    else
    {
    	$prev_page=$prev_page;
    }
    if($next_page>$total_page)
    {
    	$next_page=$next_page-1;
    }
    else
    {
    	$next_page=$page+1;
    }
if ($exurlon) {
    $url_firstpage=$GLOBALS[expath]."/index.php/";
    $url_prevpage=$GLOBALS[expath]."/exurl.php/page".$prev_page.".html";
    $url_nextpage=$GLOBALS[expath]."/exurl.php/page".$next_page.".html";
    $url_totalpage=$GLOBALS[expath]."/exurl.php/page".$total_page.".html";
}else{
    $url_firstpage=$GLOBALS[expath]."/index.php";
    $url_prevpage=$GLOBALS[expath]."/index.php?page=".$prev_page;
    $url_nextpage=$GLOBALS[expath]."/index.php?page=".$next_page;
    $url_totalpage=$GLOBALS[expath]."/index.php?page=".$total_page;
}
    $t->set_var(array(	"curPage"=>$page,
			"totalPage"=>$total_page,
			"url_firstpage"=>$url_firstpage,
			"url_prevpage"=>$url_prevpage,
			"url_nextpage"=>$url_nextpage,
			"url_totalpage"=>$url_totalpage,
			"prev_page"=>$prev_page,
			"next_page"=>$next_page,
			"total_page"=>$total_page));
}


###### 检查链接名字是否合法 ######
function checkHomepage2($homepage)
{
	global $langfun;
	if(!trim($homepage)=="")
	{
		if(!eregi("^http://.+\..+$",$homepage))
		{
			$result.="$langfun[29]";
			return $result;
		}
	}
}

###### 检查QQ号码是否合法 ######
function checkQQ($iQQ)
{
	global $langfun;
	if(!trim($iQQ)=="")
	{
		if(!eregi("^[0-9]+$",$iQQ))
		{
			$result.="$langfun[30]";
			return $result;
		}
	}
}

######### 检查搜索关键字 #########
function checkKeyword($key)
{
	global $langfun;
	if(trim($key) == "")
	{
		$result.="$langfun[31]";
		return $result;
	}
	elseif(strlen($key) <= 2)
	{
		$result.="$langfun[32]";
		return $result;
	}
	elseif(eregi("[<>{}(),%#|^&!`$]",$key))
	{
        $result.="$langfun[33]";
        return $result;
	}
}

/* 更新链接访问次数并转向至链接站点 */
function updateLinkAndHeader($iUrl,$iID)
{
	global $x, $langfun;
    $query="UPDATE `$x[links]` SET visits=visits+1 WHERE id='$iID'";
	@mysql_query($query) or die("$langfun[34]");

	header("Location: $iUrl");   
}

###### 表情 ######
/* 表情替换函数 */
function epost($message)
{
   global $langfun;
   $message=preg_replace("/\:ex(.+?)\:/is","<img src=\""."$GLOBALS[expath]"."/images/ex_pose/ex\\1.gif\" alt=\"pose\" />",$message);
	return $message;
}

############ guest some info ############
function getGuestInfo()
{
	global $guestIp, $guestExplorer, $guestOS;
	$guestIp=$_SERVER["REMOTE_ADDR"];        //浏览者IP
	$guestOS=$_SERVER["HTTP_USER_AGENT"];    //浏览者操作系统及浏览器
	//$url=$_SERVER["HTTP_REFERER"];      //访问本页的上一个页面地址
	//分析浏览器
	if(strpos($guestOS,"NetCaptor"))$guestExplorer="NetCaptor";
	elseif(strpos($guestOS,"MSIE 6"))$guestExplorer="MSIE6.x";
	elseif(strpos($guestOS,"MSIE 5"))$guestExplorer="MSIE5.x";
	elseif(strpos($guestOS,"MSIE 4"))$guestExplorer="MSIE4.x";
	elseif(strpos($guestOS,"Netscape"))$guestExplorer="Netscape";
	elseif(strpos($guestOS,"Opera"))$guestExplorer="Opera";
	else $guestExplorer="Other";
	
	//分析操作系统
	if(strpos($guestOS,"Windows NT 5.0"))$guestOS="Windows2000";
	elseif(strpos($guestOS,"Windows NT 5.1"))$guestOS="WindowsXP";
	elseif(strpos($guestOS,"Windows NT 5.2"))$guestOS="Windows2003";
	elseif(strpos($guestOS,"Windows NT"))$guestOS="WindowsNT";
	elseif(strpos($guestOS,"Windows 9"))$guestOS="Windows98";
	elseif(strpos($guestOS,"unix"))$guestOS="Unix";
	elseif(strpos($guestOS,"linux"))$guestOS="Linux";
	elseif(strpos($guestOS,"SunOS"))$guestOS="SunOS";
	elseif(strpos($guestOS,"BSD"))$guestOS="FreeBSD";
	elseif(strpos($guestOS,"Mac"))$guestOS="Mac";
	else $guestOS="Other";
}

/**
 *	添加一条新的友情链接记录,要后台管理员批准才能显示
 *  @param:	$title 站点名称
 *  @param: $url   站点URL		
 *	@param: $logo  站点LOGO的URL
 *	@param: $description 站点描述 
 *	@return: null
 *  @author: elliott [at] 2004-08-29
 */
 function addNewLink($title, $url, $logo, $description)
 {
	 global $x, $langfun;
	 $ret; 
	 $check = new CheckData();

	 if(!$check->checkOther($title, true, 2))
		 $ret .= "$langfun[35]\n";
	 else if(!$check->checkUrl($url, true))
		 $ret .= "$langfun[36]\n";
	 else if(!$check->checkUrl($url, false))
		 $ret .= "$langfun[37]\n";
	 else if(!$check->checkOther($description, false, 0))
		 $ret .= "$langfun[38]\n";
	 
	 if($ret)
	 {
		 showError_show($ret);
	 }

	 $query  = "INSERT INTO `$x[links]` (homepage, logoURL, url, description, visits, visible)";
	 $query .= " VALUES('$title', '$logo', '$url', '$description', '0', '0')";

	 @mysql_query($query) or die("$langfun[39]");
	 
	 $showMsg="$langfun[40]";
     $showReturn="./?play";
	 display_show($showMsg, $showReturn);
 }

/**
 *	查询后台是否设置BLOG暂时关闭,如关闭则只显示管理员设置的关闭信息
 *  @param:  null
 *	@return: null
 *  @author: elliott [at] 2004-08-30
 */
function getBlogIsRun()
{
	global $x;

	$query = "SELECT activeRun, unactiveRunMessage FROM `$x[global]` WHERE activeRun = '0'";
	$result = mysql_query($query);

	if($row = mysql_num_rows($result))
	{
		$ret = mysql_fetch_array($result);
		showError_show($ret['unactiveRunMessage']);
	}
}

/**
 *	添加新用户
 *  @param:  $name 用户昵称
 *	@param:  $password 用户密码 
 *  @param:  $email 用户email
 *	@param:  $homepage 用户主页地址
 *	@param:  $qq 用户QQ号码
 *	@return: null
 *  @author: elliott [at] 2004-08-30
 */
function addUser($name, $password, $email, $homepage, $qq)
{
	global $x, $langfun;

	$query3 = "SELECT user FROM `$x[admin]` WHERE user='$name'";
	$result3 = mysql_query($query3);
	
           $result=0;
           $result=mysql_num_rows($result3);
		if( $result )
	{
		showError_show("$langfun[41]");
	}

	$passwd = md5($password);

	$query  = "INSERT INTO `$x[admin]` (user, password, email, uid)";
	$query .= " VALUES('$name', '$passwd', '$email', '3')";
	@mysql_query($query) or die("$langfun[42]");
 
    userLogin($name, $password, 1, 1, "no", "show");
 
}

/**
 *	返回本BLOG用户注册数目
 *  @param:  $null 用户昵称
 *	@return: 返回注册人数(int)
 *  @author: elliott [at] 2004-08-30
 */
function getUserNum()
{
	global $x;

	$query = "SELECT id FROM `$x[admin]`";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
	return $num;
}

/**
 *	取得 blog 浏览数
 *  @param:  $id 待查询文章ID
 *	@return: $num 文章浏览数目
 *  @author: elliott [at] 2004-08-30
 */
function getBlogVisitsNum($id)
{
	global $x;

	$query = "SELECT visits FROM `$x[blog]` WHERE id = '$id'";
	$result = mysql_query($query);
	$rows = mysql_fetch_array($result);

	return $rows['visits'];
}

/* 前台显示用户列表处理 */
function user_list()
{
	global $t, $x, $langfun, $resultAdmin, $resultUser, $resultVisitor;

$lineno=0;
$i=0;
while($adminList=mysql_fetch_array($resultAdmin)) {

	$name = htmlspecialchars($adminList['user']);
	if($adminList['email']!="") $email = $adminList['email']; else $email = $langfun[51];

	$t->set_var(array(	"name"=>$name,
				"pass"=>$langfun[52],
				"email"=>$email,
				"qq"=>$qq,
				"lineno"=>$lineno,
				"homepage"=>""));
	$t->parse("m_User","RowsUser",true);

if($lineno) $lineno=0; else $lineno=1;

}
$i=0;
while($userList=mysql_fetch_array($resultUser)) {

	$name = htmlspecialchars($userList['user']);
	if($userList['email']!="") $email = $userList['email']; else $email = $langfun[51];

	$t->set_var(array(	"name"=>$name,
				"pass"=>$langfun[53],
				"email"=>$email,
				"qq"=>$qq,
				"lineno"=>$lineno,
				"homepage"=>""));
	$t->parse("m_User","RowsUser",true);

if($lineno) $lineno=0; else $lineno=1;

}
$i=0;
while($VisitorList=mysql_fetch_array($resultVisitor)) {

	$name = htmlspecialchars($VisitorList['user']);
	if($VisitorList['email']!="") $email = $VisitorList['email']; else $email = $langfun[51];

	$homepage = $VisitorList['homepage'];

	$t->set_var(array(	"name"=>$name,
				"pass"=>$langfun[54],
				"email"=>$email,
				"qq"=>$qq,
				"lineno"=>$lineno,
				"homepage"=>$homepage));
	$t->parse("m_User","RowsUser",true);

if($lineno) $lineno=0; else $lineno=1;

}
}

/*
用处：通过正则表达式加亮关键词 by Minisheep
要求：备查文章内除HTML标签外所有 < 和 > 符号分别用 &lt; 和 &gt; 替代
	$content=str_replace("<","&lt;",$content);
	$content=str_replace(">","&gt;",$content);
	$content=highlight($content,$key);
$content：要加亮的备查文章
$key：关键字
版权：随意使用 但请保留此信息
*/
function highlight($str,$key) {
	return(preg_replace("/([^<]*)($key)([^>]*)($|<.+)/isU","$1<span style=\"color:#f00;font-weight:bold;text-decoration:underline;background-color: #F0F0F0;\">$2</span>$3$4",$str));
}

function acronym($str,$a_word,$a_content,$a_url) {
    if(!$a_url){
	return(preg_replace("/([^<]*)($a_word)([^>]*)($|<.+)/isU","$1<acronym title=\"$a_word: $a_content\">$2</acronym>$3$4",$str));
    }else{
	return(preg_replace("/([^<]*)($a_word)([^>]*)($|<.+)/isU","$1<a href=\"$a_url\" target=\"_blank\"><acronym title=\"$a_word: $a_content\">$2</acronym></a>$3$4",$str));
    }
}

/*
用处：加亮关键词 by MadCN.com
要求：备查文章内除HTML标签外所有 < 和 > 符号分别用 &lt; 和 &gt; 替代
	$content=str_replace("<","&lt;",$content);
	$content=str_replace(">","&gt;",$content);
	$content=highlight($content,$key);
可能存在问题：效率不高
$content：要加亮的备查文章
$key：关键字
版权：随意使用 但请保留此信息
*/
function highlight_2($content,$key) {
    $k_fi=substr($key,0,1);		//取得关键词第一个字符
    $k_len=strlen($key); 		//计算关键词字数
    $l_len=strlen($content);		//计算备查文章字数
    for($l_n=0;$l_n<$l_len;$l_n++)	//根据备查文章字数开始循环
    {
        $l_s=substr($content,$l_n,1);	//取得备查文章当前字符
        if($l_s=="<")		//如果这个字符是标签的开始的话
        {
            while($l_s!=">")	//我们就寻找这个标签的关闭
            {
                $con.=$l_s;	//导入结果
                $l_n++;		//当然要开始取备查文章的下一个字符
                $l_s=substr($content,$l_n,1);
            }
                $con.=$l_s;
        }
        elseif(strtolower($l_s)==strtolower($k_fi))	//如果这个字符与关键词第一个字符相同的话
        {
	    $l_key=substr($content,$l_n,$k_len);	//取备查文章当前位置是否匹配关键词
	    if(strtolower($l_key)!=strtolower($key))
	    {
                $con.=$l_s;	//导入结果
	    }
	    else	//如果匹配
	    {
	        $l_n+=$k_len-1;	//计数跳过相应字数
                $con.="<span style=\"color:#f00;font-weight:bold;text-decoration:underline;background-color: #F0F0F0;\">";
                $con.=$l_key;
                $con.="</span>";	//加亮关键词
	    }
        }
        else
        {
	    $con.=$l_s;	//导入结果
        }
    }
	return $con;
}


?>
