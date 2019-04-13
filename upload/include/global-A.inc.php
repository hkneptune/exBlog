<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 RC1  exBlog 网络日志(PHP+MYSQL) 1.2.0 RC1 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 前台常用函数
*\----------------------------------------------------------------------------
*\    UBB代码改进 by lsly
*\===========================================================================*/


###### 更新文章访问量 ######
function addArtVisits($iID)
{
	global $x;
    $query="UPDATE `$x[blog]` SET visits=visits+1 WHERE id='$iID'";
    @mysql_query($query) or die("更新文章访问次数时失败!");
}

###### 更新站点访问量 ######
function addVisits()
{
	global $x;
    if(!$_COOKIE[exBlogVisits] == "Visitsed")
    {
        setcookie("exBlogVisits","Visitsed",time()+86400);
		//--------- 总访问量 -----------//
        $queryVisits="UPDATE `$x[visits]` SET visits=visits+1";
        @mysql_query($queryVisits) or die("添加访问记录时失败!");
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
	global $x;
	
	$check = new CheckData();
	if(!$check->checkName($iAuthor, true, false))
		$ret .= "用户昵称必须填写且不能含有特殊字符且长度大于3字符.<br>";
	if(!$check->checkPassword($password, $password, false))
		$ret .= "密码中不能含有特殊字符且长度不能小于6字符.<br>";
	if(!$check->checkEmail($iEmail, false))
		$ret .= "email格式不正确.<br>";
	if(!$check->checkUrl($iHomepage, false))
		$ret .= "主页地址格式不正确.<br>";
	if(!$check->checkNumberOnly($iQQ, false, 5, 12))
		$ret .= "QQ格式不正确.<br>";
	if(!$check->checkOther($iContent, true, 6))
		$ret .= "评论内容必须填写且长度不能小于6字符.<br>";
	


	if($ret)
	{
		showError($ret);
	}

	if($register == "yes")
	{
		if(!$check->checkPassword($password, $password, true))
			$ret .= "如果你选择注册昵称的话,密码必须填写且长度不能小于6字符.<br>";
		if($ret)
			showError($ret);
		addUser($iAuthor, $password, $email, $homepage, $qq);
	}

	if($password == "")
	{
		$query2 = "SELECT name FROM `$x[user]` WHERE name = '$iAuthor'";
		$result = mysql_query($query2);
		if(mysql_num_rows($result))
			showError("用户名或密码不正确, 不能发表评论.");
	}
	else if($password != "")
	{
		$passwd = md5($password);
		$query2 = "SELECT * FROM `$x[user]` WHERE name = '$iAuthor' AND password = '$passwd'";
		$result = mysql_query($query2);
		if(!mysql_num_rows($result))
			showError("用户名或密码不正确, 不能发表评论.");
	}

	$query="SELECT * FROM `$x[comment]` WHERE commentID='$iCommentID' AND author='$iAuthor'";
	$query.="AND content='$iContent' ORDER BY id DESC LIMIT 0,1";
    $result=mysql_num_rows(mysql_query($query));
	if($result > 0)
		showError("请不要重复添加相同信息!");

	$iAuthor=trim($iAuthor);

	$query="INSERT INTO `$x[comment]` (commentID, commentSort, author, email, homepage, qq, content, addtime)";
	$query.=" VALUES('$iCommentID','$iCommentSort', '$iAuthor', '$iEmail', '$iHomepage', '$iQQ', '$iContent', NOW())";
	@mysql_query($query) or die("添加评论时失败!");
	$showMsg="添加Blog评论信息成功";
	$showReturn="./?play=reply&id=$iCommentID";
	display($showMsg, $showReturn);
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
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[global]`");
    $info=mysql_fetch_array($sql);
    $t->set_var(array("title"=>$info[siteName],
		              "copyright"=>$info[copyright],
		              "templatesURL"=>$info[tmpURL]));
}

######### 查询当前模版目录 ######
function selectTmpURL()
{
	global $tmpURL, $x;
	$sql=@mysql_query("SELECT tmpURL FROM `$x[global]`");
	$tmp=@mysql_fetch_array($sql);
	$tmpURL=$tmp['tmpURL'];
}

######### 首页调用 #########
function shell($iSort)
{
	global $x;
	if($iSort=="")
    {
		$query = "SELECT * FROM `$x[blog]` ORDER BY id DESC LIMIT 0,8";
	}
	else
	{
		$query = "SELECT * FROM `$x[blog]` WHERE sort='$iSort' ORDER BY id DESC LIMIT 0,8";
	}
	$result=mysql_query($query);
	return $result;
}

######## 查询公告信息 #########
function selectAnnounce()
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[announce]` ORDER BY id DESC");
	while($rows=mysql_fetch_array($sql))
	{
		$tmp.="·<a href=\"#\" onclick=\"window.open('?play=announce&id=$rows[id]', 'newwindow', 'height=400, width=500, top=100,left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no')\" class=\"G\">$rows[title]</a>";
		$tmp.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$t->set_var("putAnnounce",$tmp);	
}

######### 显示公告 #########
function putAnnounce($iID)
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[announce]` WHERE id='$iID'");
	$rows=mysql_fetch_array($sql);

    if(strlen($rows['title'] > 20))
	{
		$rows['title']=substr($rows['title'],"0","20").chr(0);
		$rows['title']=$rows['$title']."...";
	}
	
	$rows['title']=htmlspecialchars($rows['title']);
	$rows['content']=htmlspecialchars($rows[content]);
	$rows['content']=str_replace("\n","<br>",$rows[content]);
	$rows['content']=str_replace("  ","&nbsp;",$rows[content]);
    $rows['content']=epost($rows[content]);
	
	$exubb = new ExUbb();
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
	
	$t->set_var(array("announceTitle"=>$rows['title'],
		              "announceContent"=>$rows['content'],
		              "announceAuthor"=>$rows['author'],
		              "announceEmail"=>$rows['email'],
		              "announceAddtime"=>$rows['addtime']));
}

######### 执行搜索 #########
function searchInfo($key)
{
	global $t, $x;
	$i=0;
	$result=checkKeyword($key);
	if($result)
	{
		showError($result);
	}
	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE content LIKE '%$key%'");
	$num=mysql_num_rows($sql);
	if($num == "0")
	{
		showError("Sorry,未找到相关记录.");
	}
	while($rows=mysql_fetch_array($sql))
	{
		if($i<$num)
		{
			$rows['title']=htmlspecialchars($rows['title']);
			$rows['title']=eregi_replace($key,"<font color=red><b><u>$key</u></b></font>","$rows[title]");
			$key_id[$i]=$rows['id'];
			$key_title[$i]=$rows['title'];
			$key_visits[$i]=$rows['visits'];
			$i++;
		}
	}
	for($i=0; $i<$num; $i++)
	{
		$t->set_var(array("keyTitle"=>$key_title[$i],
			              "keyID"=>$key_id[$i],
			              "visits"=>$key_visits[$i],
			              "keyword"=>$key));
		$t->parse("RowsKeyword","RowKeyword",true);
	}
}

######### 查询文章数,访问量,BLOG数,评论数等 ######
function selectSomeInfo()
{
	global $t, $x;
	$sql=mysql_query("SELECT id FROM `$x[comment]`");
	$commentNum=mysql_num_rows($sql);  //论评总数
	$sql=mysql_query("SELECT id FROM `$x[blog]`");
	$blogNum=mysql_num_rows($sql);     //Blog总数
	$sql=mysql_query("SELECT * FROM `$x[visits]`");
	$tmp=mysql_fetch_array($sql);
	$visited=$tmp['visits'];             //站点总访问量 
	$todayVisits=$tmp['todayVisits'];
	$userNum = getUserNum();
	
	$t->set_var(array("visNum"=>$visited,
		     		  "blogNum"=>$blogNum,
			    	  "reNum"=>$commentNum,
		              "todNum"=>$todayVisits,
					  "userNum" => $userNum));
}

###### 查询栏目相关信息 ######
function selectColumn()
{
	global $t,$x;
	$i=0;
	$sql="SELECT * FROM `$x[sort]` ORDER BY id ASC ";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num == 0)
	{
		$t->set_var("column","Null");
		$t->parse("RowsColumn","RowColumn",true);
	}
	while($rows=mysql_fetch_array($result))
	{
		if($i<$num)
		{
			$enName[$i]=$rows['enName'];
			$cnName[$i]=$rows['cnName'];
			$description[$i]=$rows['description'];
			$i++;
		}
	}
	
	for($j=0; $j<$num; $j++)
	{
		$t->set_var(array("column"=>$cnName[$j],
                          "sortName"=>$enName[$j],
		                  "description"=>$description[$j]));
		$t->parse("RowsColumn","RowColumn",true);
	}
}

###### 查询最新5条评论记录 ######
function selectNew5comment()
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[comment]` ORDER BY id DESC LIMIT 0,6");
	$num=mysql_num_rows($sql);
	if($num == 0)
	{
		$t->set_var("comment","Null");
		$t->parse("RowsComment","RowComment",true);
	}
	$i=0;
	while($rows=mysql_fetch_array($sql))
	{
		$comID[$i]=$rows[commentID];
		$content[$i]=$rows[content];
		//---- 把标题截取20个字符 ----//
		$len = strlen($content[$i]);
		if ($len <= 20)
		{
			$content[$i] = $content[$i];
		}
		else
		{
			$content[$i] = substr($content[$i],"0","20").chr(0); 
			$content[$i] = $content[$i]."...";
			$content[$i] = htmlspecialchars($content[$i]);
	    }
	    $i++;
	}
	
	for($a=0; $a<$i; $a++)
	{
		$t->set_var(array("id"=>$comID[$a],"comment"=>$content[$a]));
		$t->parse("RowsComment", "RowComment", true);
	}
}

###### 查询最新5条评论记录 ######
function select5links()
{
	global $t, $x;
    $sql=mysql_query("SELECT *,RAND() AS randid FROM `$x[links]` WHERE visible = '1' ORDER BY randid"); 
	$num=mysql_num_rows($sql);
	if($num == 0)
	{
		$t->set_var(array("homepage"=>"Null",
		                  "visits"=>"0"));
		$t->parse("RowsLinks","RowLinks",true);
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
			$homepage[$iii]="<img src=\"$logoURL[$iii]\" align=\"middle\" border=\"0\">";
		}
	    $t->set_var(array("id"=>$id[$iii],
		                  "url"=>$url[$iii],
		                  "homepage"=>$homepage[$iii],
		                  "description"=>$description[$iii],
		                  "visits"=>$visits[$iii]));
	    $t->parse("RowsLinks", "RowLinks", true);
    }
}

###### 评论页面显示当前BLOG ######
function showCurrentBlog($curID)
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$curID'");
	$tmpBlog=mysql_fetch_array($sql);
	$sql=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$curID'");
	$reNum=mysql_num_rows($sql);


    //$tmpBlog['content']=substr($tmpBlog[content],"0","350").chr(0);
	//$tmpBlog['content']=$tmpBlog[content]."...";
	$tmpBlog['title']=htmlspecialchars($tmpBlog['title']);
	$tmpBlog['content']=htmlspecialchars($tmpBlog['content']);
	$tmpBlog['content']=str_replace("\n","<br>",$tmpBlog['content']);
	$tmpBlog['content']=str_replace("  ","&nbsp;",$tmpBlog['content']);
    $tmpBlog['content']=epost($tmpBlog['content']);

	$exubb = new ExUbb();
	$exubb->setString($tmpBlog['content']);
	$tmpBlog['content'] = $exubb->parse();

	$t->set_var(array("blogTitle"=>$tmpBlog['title'],
		              "blogContent"=>$tmpBlog['content'],
		              "blogAuthor"=>$tmpBlog['author'],
		              "blogEmail"=>$tmpBlog['email'],
		              "blogSort"=>$tmpBlog['sort'],
		              "blogAddtime"=>$tmpBlog['addtime'],
		              "blogVisits"=>$reNum,
					  "blogWeather" => "./images/weather/".$tmpBlog['weather'].".gif",
					  "viewNum" => $tmpBlog['visits'],
		              "blogID"=>$curID,
		              "currentID"=>$curID));
}

###### 显示所选文章 ######
function showArticle($curID,$make,$key)
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[blog]` WHERE id='$curID'");
	$rows=mysql_fetch_array($sql);
 
	$rows['title']=htmlspecialchars($rows['title']);
	$rows['content']=htmlspecialchars($rows['content']);
	$rows['content']=epost($rows['content']);

	$exubb = new ExUbb();
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();

	if(!trim($make)=="" && !trim($key)=="")
	{
		$rows['title']=eregi_replace($key,"<font color=red><b><u>$key</u></b></font>","$rows[title]");
		$rows['content']=eregi_replace($key,"<font color=red><b><u>$key</u></b></font>","$rows[content]");
	}
	
	$t->set_var(array("artTitle"=>$rows['title'],
	                  "author"=>$rows['author'],
					  "sort"=>$rows['sort'],
					  "time"=>$rows['addtime'],
					  "visits"=>$rows['visits'],
					  "artContent"=>$rows['content']));
}

###### 显示当前BLOG的评论 ######
function showCurrentBlogComment($curID)
{
	global $t, $x;
	$sql2=mysql_query("SELECT * FROM `$x[comment]` WHERE commentID='$curID' ORDER BY id DESC");
	$reNum2=mysql_num_rows($sql2);
	if($reNum2==0)
	{
		$t->set_var(array("reContent"=>"",
		"reAuthor"=>"",
		"reEmail"=>"",
		"reAddtime"=>"",
		"homepage"=>"",
		"qq"=>""));
		$t->parse("RowsReply","RowReply", true);
	}
	else
	{
		$i=0;
		while($rows=mysql_fetch_array($sql2))
		{
			if($i<$reNum2)
			{

			$rows['content']=htmlspecialchars($rows['content']);
	            $rows['content']=str_replace("\n","<br>",$rows['content']);
	            $rows['content']=str_replace("  ","&nbsp;",$rows['content']);
                     $exubb = new ExUbb();
	              $exubb->setString($rows['content']);
	              $rows['content'] = $exubb->parse();
				$re_content[$i]=$rows['content'];
				$re_author[$i]=$rows['author'];
				$re_addtime[$i]=$rows['addtime'];
				$re_email[$i]=$rows['email'];
				$re_homepage[$i]=$rows['homepage'];
				$re_qq[$i]=$rows['qq'];
				$i++;
			}
		}
		for($i=0; $i<$reNum2; $i++)
		{
			$t->set_var(array("reContent"=>$re_content[$i],
				              "reAuthor"=>$re_author[$i],
				              "reEmail"=>$re_email[$i],
				              "reAddtime"=>$re_addtime[$i],
				              "homepage"=>$re_homepage[$i],
				              "qq"=>$re_qq[$i]));
			$t->parse("RowsReply","RowReply", true);
		}		
	}
}

###### 查询当前分类所有文章标题 ######
function selectCurSortArt($isort)
{
	global $t, $prev_page, $next_page, $total_page, $x;

	$exubb = new ExUbb();

	$sql=mysql_query("SELECT * FROM `$x[sort]` WHERE enName='$isort'");
    $sortName=mysql_fetch_array($sql);

    $page_size=10;
    $queryArt="SELECT * FROM `$x[blog]` WHERE sort='$sortName[cnName]' ORDER BY id DESC";
    $result=mysql_query($queryArt);
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
    $prev_page=$page-1;
    $next_page=$page+1;
    $sql=mysql_query("SELECT * FROM `$x[blog]` WHERE sort='$sortName[cnName]' ORDER BY id DESC LIMIT $start,$page_size");

    $num=mysql_num_rows($sql);
    if($num == 0)
    {
	    $t->set_var(array("blogTitle"=>"该分类目前无任何文章","currentSort"=>$sortName[cnName]));
	    $t->parse("RowsBlog","RowBlog",true);
    }
    else
    {
	    $i=0;
	    while($rows=mysql_fetch_array($sql))
	    {
				$rows['content']=substr($rows['content'],"0","450").chr(0);
	            $rows['content']=$rows['content']."...";
		    	$rows['title']=htmlspecialchars($rows['title']);
	            $rows['content']=htmlspecialchars($rows['content']);
		    	$exubb->setString($rows['content']);
				$rows['content'] = $exubb->parse();
	            $rows['content']=epost($rows['content']);


		    	$b_id[$i]=$rows['id'];
			    $b_title[$i]=$rows['title'];
				$b_author[$i]=$rows['author'];
				$b_email[$i]=$rows['email'];
				$b_content[$i]=$rows['content'];
				$b_addtime[$i]=$rows['addtime'];
				$b_sort[$i]=$rows['sort'];
				$b_viewNum[$i] = $rows['visits'];
				$b_weather[$i] = $rows['weather'];

				$query=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$b_id[$i]'");
				$commentNum[$i]=mysql_num_rows($query);

	     		$i++;
	    }
	    for($i=0; $i<$num; $i++)
	    {
		    $t->set_var(array("blogID"=>$b_id[$i],
				              "blogTitle"=>$b_title[$i],
				              "blogContent"=>$b_content[$i],
				              "blogSort"=>$b_sort[$i],
				              "blogAuthor"=>$b_author[$i],
				              "blogEmail"=>$b_email[$i],
				              "blogAddtime"=>$b_addtime[$i],
			                  "blogVisits"=>$commentNum[$i],
			                  "currentSort"=>$sortName['cnName'],
							  "viewNum" => $b_viewNum[$i],
							  "blogWeather" => "./images/weather/".$b_weather[$i].".gif"));
	    	$t->parse("RowsBlog","RowBlog", true);
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
	$t->set_var(array("sortName"=>$isort,
                      "curPage"=>$page,"totalPage"=>$total_page,
                      "prev_page"=>$prev_page,"next_page"=>$next_page,"total_page"=>$total_page));
}

###### 查询链接信息 ######
function selectAllLinks()
{
	global $t, $x;
	$sql=mysql_query("SELECT * FROM `$x[links]` WHERE visible = '1' ORDER BY id ASC");
    $linkNum=mysql_num_rows($sql);

    if($linkNum > 0)
    {   
	    $i=0;
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
			    $i++;
				
		    }
    	}

	    for($i=0; $i<$linkNum; $i++)
	    {	
			if($link_logo[$i] != "")
			{
				$tmp="<img src=\"$link_logo[$i]\" alt=\"$link_description[$i]\" border=\"0\" align=\"middle\">";
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
			

		    $t->parse("Row2sLinks","Row2Links",true);
	    }
	
    }
    else
    {
	    $t->set_var("homepage","目前无任何链接");
	    $t->parse("Row2sLinks","Row2Links",true);
    }
}


###### 查询最新6条Blog记录并分页 ######
function selectBlog($year,$month)
{
	global $t, $prev_page, $next_page, $total_page, $x;
	
	$exubb = new ExUbb();

	$page_size=6;
    $queryBlog="SELECT * FROM `$x[blog]` ORDER BY id DESC ";
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
    $prev_page=$page-1;
    $next_page=$page+1;

	$i = 0;   // blog数组下标

	//先存储置顶贴
	$query = "SELECT * FROM `$x[blog]` WHERE top = '1' ORDER BY id DESC";
	$result = mysql_query($query);
	while($rows = mysql_fetch_array($result))
	{
		$rows['content']=substr($rows['content'],"0","450").chr(0);
	    $rows['content']=$rows['content']."...";
		$rows['title']=htmlspecialchars($rows['title']);
	    $rows['content']=htmlspecialchars($rows['content']);
	
		$exubb->setString($rows['content']);
		$rows['content'] = $exubb->parse();      
		$rows['content']=epost($rows['content']);
		
		$blog_top[$i] = "<font color=red>[置顶]</font>";
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
	
		$i++;
	}
	for($j = 0; $j < $i; $j++)
	{
		$t->set_var( array("isTop" =>$blog_top[$j],
							  "blogID"=>$blog_id[$j],
			                  "blogTitle"=>$blog_title[$j],
			                  "blogContent"=>$blog_content[$j],
		    	              "blogAuthor"=>$blog_author[$j],
				              "blogEmail"=>$blog_email[$j],
				              "blogSort"=>$blog_sort[$j],
			                  "blogAddtime"=>$blog_addtime[$j],
			                  "blogVisits"=>$blog_visits[$j],
				              "blogWeather" => "./images/weather/".$blog_weather[$j].".gif",
							  "viewNum" => $blog_num[$j]));
	}

    $sql=mysql_query("SELECT * FROM `$x[blog]` WHERE top = '0' ORDER BY id DESC LIMIT $start,$page_size");
	$num=mysql_num_rows($sql);

    if($num == 0)
    {
	    $t->set_var("blogTitle","目前无任何Blog.");
	    $t->parse("RowsBlog","RowBlog",true);
    }
    else
    {
	    while($rows=mysql_fetch_array($sql))
	    {
		    //if($i<$num)
		    {
				$rows['content']=substr($rows['content'],"0","450").chr(0);
	            $rows['content']=$rows['content']."...";
		    	$rows['title']=htmlspecialchars($rows['title']);
	            $rows['content']=htmlspecialchars($rows['content']);
	
				$exubb->setString($rows['content']);
		    	$rows['content'] = $exubb->parse();
	            
				$rows['content']=epost($rows['content']);

		    	$blog_id[$i] = $rows['id'];
		    	$blog_title[$i] = $rows['title'];
		    	$blog_content[$i] = $rows['content'];
		    	$blog_author[$i] = $rows['author'];
				$blog_email[$i] = $rows['email'];
				$blog_sort[$i] = $rows['sort'];
		    	$blog_addtime[$i] = $rows['addtime'];
				$blog_weather[$i] = $rows['weather'];
				//$blog_visits[$i] = 
				$blog_num[$i] = $rows['visits'];

		    	//---- 查询该BLOG的论评数 ----//
			    $sql2=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$blog_id[$i]'");
			    $blog_visits[$i]=mysql_num_rows($sql2);
			    $i++;
		    }
	    }
	    for($i=0; $i<$num+$j; $i++)
	    {
		    $t->set_var(array("isTop" =>$blog_top[$i],
							  "blogID"=>$blog_id[$i],
			                  "blogTitle"=>$blog_title[$i],
			                  "blogContent"=>$blog_content[$i],
		    	              "blogAuthor"=>$blog_author[$i],
				              "blogEmail"=>$blog_email[$i],
				              "blogSort"=>$blog_sort[$i],
			                  "blogAddtime"=>$blog_addtime[$i],
			                  "blogVisits"=>$blog_visits[$i],
				              "blogWeather" => "./images/weather/".$blog_weather[$i].".gif",
							  "viewNum" => $blog_num[$i]));
			$t->parse("RowsBlog","RowBlog",true);
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
    $t->set_var(array("curPage"=>$page,"totalPage"=>$total_page,
                      "prev_page"=>$prev_page,"next_page"=>$next_page,"total_page"=>$total_page));
}


###### 检查链接名字是否合法 ######
function checkHomepage2($homepage)
{
	if(!trim($homepage)=="")
	{
		if(!eregi("^http://.+\..+$",$homepage))
		{
			$result.="网站URL只能由a-z,A-Z,0-9及'_'线组成!";
			return $result;
		}
	}
}

###### 检查QQ号码是否合法 ######
function checkQQ($iQQ)
{
	if(!trim($iQQ)=="")
	{
		if(!eregi("^[0-9]+$",$iQQ))
		{
			$result.="TencentQQ格式不正确!<br>";
			return $result;
		}
	}
}

######### 检查搜索关键字 #########
function checkKeyword($key)
{
	if(trim($key) == "")
	{
		$result.="请填写关键字!<br>";
		return $result;
	}
	elseif(strlen($key) <= 2)
	{
		$result.="关键字必须超2个字符!<br>";
		return $result;
	}
	elseif(eregi("[<>{}(),%#|^&!`$]",$key))
	{
        $result.="关键字中不能含有特殊字符!<br>";
        return $result;
	}
}

###### UBB代码 ######
function ubb($Text)
{
$Text=str_replace("\n","<br>",$Text);
$Text=str_replace("  ","&nbsp;",$Text);
//$Text=ereg_replace("\r\n","<br>",$Text);
   //$Text=ereg_replace("\r","<br>",$Text);
   $Text=preg_replace("/\\t/is","  ",$Text); 
   $Text=preg_replace("/\[color=(.+?)\](.*)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
   $Text=preg_replace("/\[color=\s*(.*?)\s*\]\s*(.*?)\s*\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
   $Text=preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","<a href=\\1 target=\"_blank\">\\1</a>",$Text);
   $Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>http://\\1</a>",$Text);
   $Text=preg_replace("/\[url=(http:\/\/.+?)\](.*)\[\/url\]/is","<a href=\\1 target=_blank>\\2</a>",$Text);
   $Text=preg_replace("/\[url=(.+?)\](.*)\[\/url\]/is","<a href=http://\\1 target=_blank>\\2</a>",$Text);
   $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
   $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href=mailto:\\1>\\1</a>",$Text);
   $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
   $Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
   $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
   $Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is","
                 <center>
                     <table border=\"0\" width=\"97%\" cellspacing=\"0\" cellpadding=\"0\">
                                   <tr><td>&nbsp;&nbsp;QUOTE:</td></tr>
                                   <tr><td>
                                             <table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=#000000>
                                                      <tr><td align=\"left\" width=\"100%\" bgcolor=#e6e6e6><font color=\"#0000FF\" face=\"Verdana\">\\1</font></td></tr>
                                             </table>
                                           </td>
                                   </tr>
                     </table>
             </center>", $Text);
   $Text=preg_replace("/\[code\]\s*(.*?)\s*\[\/code\]/is","
                 <center>
                     <table border=\"0\" width=\"97%\" cellspacing=\"0\" cellpadding=\"0\">
                                   <tr><td>&nbsp;&nbsp;CODE:</td></tr>
                                   <tr><td>
                                             <table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=#000000>
                                                      <tr><td align=\"left\" width=\"100%\" bgcolor=#e6e6e6><font color=\"#0000FF\" face=\"Verdana\">\\1</font></td></tr>
                                             </table>
                                           </td>
                                   </tr>
                     </table>
             </center>", $Text);
   $Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<a href=\"\\1\" target=_blank><img src=\"\\1\" border=0 onload=\"javascript:if(this.width>screen.width-300)this.width=screen.width-300\" title=\"Open New Window To See This Pic\">
            </a>",$Text);
   $Text=preg_replace("/\[iframe\]\s*(.+?)\s*\[\/iframe\]/is","<iframe src=\\1 frameborder=\"0\" allowtransparency=\"true\" scrolling=\"yes\" width=\"97%\" height=\"480\"></iframe>",$Text);
   $Text=preg_replace("/\[swf\]\s*(.+?)\s*\[\/swf\]/is","<a href=\\1 target=\"_blank\">[Open New Window For this Flash]</a><br><embed width=\"550\" height=\"375\" src=\\1 type=\"application/x-shockwave-flash\" PLAY=1></embed>",$Text);
   $Text=preg_replace("/\[wmv\]\s*(.+?)\s*\[\/wmv\]/is","<embed src=\\1  height=\"256\" width=\"314\" autostart=0></embed>",$Text);
   $Text=preg_replace("/\[wma\]\s*(.+?)\s*\[\/wma\]/is","<embed src=\\1  height=\"45\" width=\"314\" autostart=0></embed>",$Text);
   $Text=preg_replace("/\[ra\]\s*(.+?)\s*\[\/ra\]/is","<object classid=\"clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA\" id=\"RAOCX\" width=\"253\" height=\"60\">
       <param name=\"_ExtentX\" value=\"6694\">
       <param name=\"_ExtentY\" value=\"1588\">
       <param name=\"AUTOSTART\" value=\"0\">
       <param name=\"SHUFFLE\" value=\"0\">
       <param name=\"PREFETCH\" value=\"0\">
       <param name=\"NOLABELS\" value=\"0\">
       <param name=\"SRC\" value=\"\\1\">
       <param name=\"CONTROLS\" value=\"StatusBar,ControlPanel\">
       <param name=\"LOOP\" value=\"0\">
       <param name=\"NUMLOOP\" value=\"0\">
       <param name=\"CENTER\" value=\"0\">
       <param name=\"MAINTAINASPECT\" value=\"0\">
       <param name=\"BACKGROUNDCOLOR\" value=\"#000000\">
       <embed src=\"\\1\" width=\"253\" autostart=\"true\" height=\"60\">
       </embed></object>",$Text);
   $Text=preg_replace("/\[rm\]\s*(.+?)\s*\[\/rm\]/is","<object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=\"241\" id=\"Player\" width=\"316\" VIEWASTEXT>
       <param name=\"_ExtentX\" value=\"12726\">
       <param name=\"_ExtentY\" value=\"8520\">
       <param name=\"AUTOSTART\" value=\"0\">
       <param name=\"SHUFFLE\" value=\"0\">
       <param name=\"PREFETCH\" value=\"0\">
       <param name=\"NOLABELS\" value=\"0\">
       <param name=\"CONTROLS\" value=\"ImageWindow\">
       <param name=\"CONSOLE\" value=\"_master\">
       <param name=\"LOOP\" value=\"0\">
       <param name=\"NUMLOOP\" value=\"0\">
       <param name=\"CENTER\" value=\"0\">
       <param name=\"MAINTAINASPECT\" value=\"\\1\">
       <param name=\"BACKGROUNDCOLOR\" value=\"#000000\">
     </object><br>
     <object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=\"32\" id=\"Player\" width=\"316\" VIEWASTEXT VIEWASTEXT>
       <param name=\"_ExtentX\" value=\"18256\">
       <param name=\"_ExtentY\" value=\"794\">
       <param name=\"AUTOSTART\" value=\"0\">
       <param name=\"SHUFFLE\" value=\"0\">
       <param name=\"PREFETCH\" value=\"0\">
       <param name=\"NOLABELS\" value=\"0\">
       <param name=\"CONTROLS\" value=\"controlpanel\">
       <param name=\"CONSOLE\" value=\"_master\">
       <param name=\"LOOP\" value=\"0\">
       <param name=\"NUMLOOP\" value=\"0\">
       <param name=\"CENTER\" value=\"0\">
       <param name=\"MAINTAINASPECT\" value=\"0\">
       <param name=\"BACKGROUNDCOLOR\" value=\"#000000\">
       <param name=\"SRC\" value=\"\\1\">
     </object>",$Text);
   return $Text;
}

/* 更新链接访问次数并转向至链接站点 */
function updateLinkAndHeader($iUrl,$iID)
{
	global $x;
    $query="UPDATE `$x[links]` SET visits=visits+1 WHERE id='$iID'";
	@mysql_query($query) or die("更新链接访问次数时失败!");
	header("Location: $iUrl");   
}

/* 表情替换函数 */
function epost($message)
{
	global $tmpURL;
	$message=str_replace(":ex_0:","<img src=./images/ex_pose/ex_0.gif>",$message);
	$message=str_replace(":ex_1:","<img src=./images/ex_pose/ex_1.gif>",$message);
	$message=str_replace(":ex_2:","<img src=./images/ex_pose/ex_2.gif>",$message);
	$message=str_replace(":ex_3:","<img src=./images/ex_pose/ex_3.gif>",$message);
	$message=str_replace(":ex_4:","<img src=./images/ex_pose/ex_4.gif>",$message);
	$message=str_replace(":ex_5:","<img src=./images/ex_pose/ex_5.gif>",$message);
	$message=str_replace(":ex_6:","<img src=./images/ex_pose/ex_6.gif>",$message);
	$message=str_replace(":ex_7:","<img src=./images/ex_pose/ex_7.gif>",$message);
	$message=str_replace(":ex_8:","<img src=./images/ex_pose/ex_8.gif>",$message);
	$message=str_replace(":ex_9:","<img src=./images/ex_pose/ex_9.gif>",$message);
	$message=str_replace(":ex_10:","<img src=./images/ex_pose/ex_10.gif>",$message);
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
	 global $x;
	 $ret; 
	 $check = new CheckData();

	 if(!$check->checkOther($title, true, 2))
		 $ret .= "网站名称必须填写且长度最少大于2字符.\n";
	 else if(!$check->checkUrl($url, true))
		 $ret .= "URL格式必须填写并且以http://开头.\n";
	 else if(!$check->checkUrl($url, false))
		 $ret .= "Logo地址不正确,请以http://开头.\n";
	 else if(!$check->checkOther($description, false, 0))
		 $ret .= "描述信息如果填写的话, 描述信息必须大于3字符.\n";
	 
	 if($ret)
	 {
		 showError($ret);
	 }

	 $query  = "INSERT INTO `$x[links]` (homepage, logoURL, url, description, visits, visible)";
	 $query .= " VALUES('$title', '$logo', '$url', '$description', '0', '0')";

	 @mysql_query($query) or die("添加记录时失败");
	 
	 $showMsg="您的申请已添加,待管理员审合后才能显示!";
     $showReturn="./?play";
	 display($showMsg, $showReturn);
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
		showError($ret['unactiveRunMessage']);
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
	global $x;

	$query = "SELECT name FROM `$x[user]` WHERE name = '$name'";
	$result = mysql_query($query);
	
	if(mysql_num_rows($result))
	{
		showError("对不起, 此昵称已被注册.");
	}

	$passwd = md5($password);

	$query  = "INSERT INTO `$x[user]` (name, password, email, homepage, qq)";
	$query .= " VALUES('$name', '$passwd', '$email', '$homepage', '$qq')";
	@mysql_query($query) or die("添加新用户时失败!");
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

	$query = "SELECT id FROM `$x[user]`";
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

?>