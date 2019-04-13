<?
//---------------- 查询指定年月的BLOG --------------------//
function selectCurrentYearMonthBlog($iYear,$iMonth)
{
	global $t, $page, $prev_page, $next_page, $total_page, $x ,$info, $langfun, $exurlon;
	$page_size=$info[listblognum];
	$tmpMonth=$iMonth;  //用于分页处

    if($iMonth<=9)
	{
		$iMonth="0".$iMonth;
	}
	$iTime=$iYear."-".$iMonth;
	//echo $iTime;
    $queryArt="SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iTime%' ORDER BY id DESC";
    $result=mysql_query($queryArt);
    $artSum=mysql_num_rows($result);
    $total_page = ceil($artSum/$page_size);
    $page=$page;
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
    $sql=mysql_query("SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iTime%' ORDER BY id DESC LIMIT $start,$page_size");

    $num=mysql_num_rows($sql);
    if($num == 0)
    {
	    $t->set_var(array("blogTitle"=>"$langfun[45]","currentSort"=>$sortName[cnName],"blogWeather" => "$GLOBALS[expath]/images/weather/null.gif"));

    }
    else
    {

	    $i=0;
	    while($rows=mysql_fetch_array($sql))
	    {
		    if($i<=$num)
	    	{
			if(checkunregist()&&$rows['hidden']=="1")
		  {   
			$b_id[$i]=$rows['id'];
			$b_title[$i]="$langfun[21]";
			$b_author[$i]="$langfun[22]";
			$b_addtime[$i]="$langfun[22]";
			$b_sort[$i]="$langfun[22]";
			$b_visits[$i]="$langfun[22]";
			$b_weather[$i]="null";
			$commentNum[$i]="$langfun[22]";
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
		         $rows['content']=substr($rows['content'],"0",$info[summarynum]).chr(0);
	             $rows['content']=$rows['content']."...";
               }
############
            }
		     $rows['title']=htmlspecialchars($rows['title']);

$querysort="SELECT * FROM `$x[sort]` WHERE id='$rows[sort]'";
$resultSort=mysql_fetch_array(mysql_query($querysort));
$sort[$i]=$resultSort['cnName'];

if($rows['html']=="0"){
	            $rows['content']=htmlspecialchars($rows['content']);
##########################
$exubb = new ExUbb();
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
##########################
}
	            $rows['content']=epost($rows['content']);

				$rows['addtime']=eregi_replace($iTime,"<font color=red><b><u>$iTime</u></b></font>","$rows[addtime]");

		    	$b_id[$i]=$rows['id'];
			    $b_title[$i]=$rows['title'];
				$b_author[$i]=$rows['author'];
				$b_email[$i]=$rows['email'];
				$b_content[$i]=$rows['content'];
				$b_addtime[$i]=$rows['addtime'];
				$b_sort[$i]=$rows['sort'];
                           $b_weather[$i] = $rows['weather'];
		 
				$query=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$b_id[$i]'");
				$commentNum[$i]=mysql_num_rows($query);
		$sql2=mysql_query("SELECT TrackbackID FROM `$x[trackback]` WHERE TrackbackID='$b_id[$i]'");
		$TrackbackNum[$i]=mysql_num_rows($sql2);
                 
	     	   }
			$i++;
		    }
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
		    $t->set_var(array("blogID"=>$b_id[$i],
				              "blogTitle"=>$b_title[$i],
				      "url_reply"=>$url_reply,
				      "url_show"=>$url_show,
				              "blogContent"=>$b_content[$i],
				              "blogSort"=>$sort[$i],
				              "blogSortid"=>$b_sort[$i],
				              "blogAuthor"=>$b_author[$i],
				              "blogEmail"=>$b_email[$i],
				              "blogAddtime"=>$b_addtime[$i],
			                  "blogVisits"=>$commentNum[$i],
			                  "TrackbackNum"=>$TrackbackNum[$i],
			                  "currentSort"=>$sortName['cnName'],
							  "blogWeather" => "$GLOBALS[expath]/images/weather/".$b_weather[$i].".gif"));
	    }
    }
	    	$t->parse("m_blog","RowsBlog", true);

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
    $url_firstpage=$GLOBALS[expath]."/exurl.php/calendar/".$iYear."/".$tmpMonth.".html";
    $url_prevpage=$GLOBALS[expath]."/exurl.php/calendar/".$iYear."/".$tmpMonth."/".$next_page.".html";
    $url_nextpage=$GLOBALS[expath]."/exurl.php/calendar/".$iYear."/".$tmpMonth."/".$next_page.".html";
    $url_totalpage=$GLOBALS[expath]."/exurl.php/calendar/".$iYear."/".$tmpMonth."/".$total_page.".html";
}else{
    $url_firstpage=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$iYear."&amp;month=".$tmpMonth;
    $url_prevpage=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$iYear."&amp;month=".$tmpMonth."&amp;page=".$prev_page;
    $url_nextpage=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$iYear."&amp;month=".$tmpMonth."&amp;page=".$next_page;
    $url_totalpage=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$iYear."&amp;month=".$tmpMonth."&amp;page=".$total_page;
}
	$t->set_var(array("curYear"=>$iYear,
		              "curMonth"=>$tmpMonth,
                      "url_firstpage"=>$url_firstpage,
                      "url_prevpage"=>$url_prevpage,
                      "url_nextpage"=>$url_nextpage,
                      "url_totalpage"=>$url_totalpage,
		              "curPage"=>$page,
		              "totalPage"=>$total_page,
                      "prev_page"=>$prev_page,
		              "next_page"=>$next_page,
		              "total_page"=>$total_page));
}

/*  为什么还是一直忘不了她？ i miss you TianZhen ^__^   */

//---------------- 查询指定日期BLOG ------------------//
function selectCurrentDateBlog($iDate)
{
	global $t, $x, $page, $prev_page, $next_page, $total_page, $info, $langfun;
	$page_size=$info[listblognum];

    // 设置导航栏内容
	$t->set_var("currentSort","$langfun[46]");
    $queryArt="SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iDate%' ORDER BY id DESC";
    $result=mysql_query($queryArt);
    $artSum=mysql_num_rows($result);
    $total_page = ceil($artSum/$page_size);
    $page=$page;
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
	$query=mysql_query("SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iDate%' ORDER BY id DESC LIMIT $start,$page_size");
	$num=mysql_num_rows($query);

    if($num == 0)
    {
	    $t->set_var(array("blogTitle"=>"$langfun[45]","currentSort"=>$sortName[cnName],"blogWeather" => "$GLOBALS[expath]/images/weather/null.gif"));
    }
    else
    {
	$i=0;
	while($rows=mysql_fetch_array($query))
	{
		if($i<$num)
		{
			if(checkunregist()&&$rows['hidden']=="1")
		  {   
			$today_blogID[$i]=$rows['id'];
			$today_blogTitle[$i]="$langfun[21]";
			$today_blogAuthor[$i]="$langfun[22]";
			$today_blogAddtime[$i]="$langfun[22]";
			$today_blogSort[$i]="$langfun[22]";
			$blog_visits[$i]="$langfun[22]";
			$today_blogWeather[$i]="null";
			$today_blogReply[$i]="$langfun[22]";
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
		         $rows['content']=substr($rows['content'],"0",$info[summarynum]).chr(0);
	             $rows['content']=$rows['content']."...";
               }
############
            }
		    $rows['title']=htmlspecialchars($rows['title']);
if($rows['html']=="0"){
	        $rows['content']=htmlspecialchars($rows['content']);
##########################
$exubb = new ExUbb();
	$exubb->setString($rows['content']);
	$rows['content'] = $exubb->parse();
##########################
}
	        $rows['content']=epost($rows['content']);
			$rows['addtime']=eregi_replace($iDate,"<font color=red><b><u>$iDate</u></b></font>",$rows['addtime']);

            $today_blogID[$i]=$rows['id'];
    		$today_blogTitle[$i]=$rows['title'];
	    	$today_blogContent[$i]=$rows['content'];
		    $today_blogAuthor[$i]=$rows['author'];
			$today_blogEmail[$i]=$rows['email'];
		    $today_blogAddtime[$i]=$rows['addtime'];
		    $today_blogSort[$i]=$rows['sort'];

$querysort="SELECT * FROM `$x[sort]` WHERE id='$rows[sort]'";
$resultSort=mysql_fetch_array(mysql_query($querysort));
$sort[$i]=$resultSort['cnName'];

	           $today_blogWeather[$i] = $rows['weather'];
                  $blog_visits[$i] = $rows['visits'];
                
			$sql2=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$today_blogID[$i]'");
			$today_blogReply[$i]=mysql_num_rows($sql2);
		$sql2=mysql_query("SELECT TrackbackID FROM `$x[trackback]` WHERE TrackbackID='$today_blogID[$i]'");
		$TrackbackNum[$i]=mysql_num_rows($sql2);
		   }
			$i++;
		}
	}
	for($i=0; $i<$num; $i++)
	{
if ($exurlon) {
    $url_reply=$GLOBALS[expath]."/exurl.php/reply/".$today_blogID[$i].".html";
    $url_show=$GLOBALS[expath]."/exurl.php/show/".$today_blogID[$i].".html";
}else{
    $url_reply=$GLOBALS[expath]."/index.php?play=reply&amp;id=".$today_blogID[$i];
    $url_show=$GLOBALS[expath]."/index.php?play=show&amp;id=".$today_blogID[$i];
}
		$t->set_var(array("blogID"=>$today_blogID[$i],
				 "url_reply"=>$url_reply,
				 "url_show"=>$url_show,
			              "blogTitle"=>$today_blogTitle[$i],
			              "blogContent"=>$today_blogContent[$i],
			              "blogSort"=>$sort[$i],
			              "blogSortid"=>$today_blogSort[$i],
			              "blogAuthor"=>$today_blogAuthor[$i],
			              "blogEmail"=>$today_blogEmail[$i],
			              "blogVisits"=>$today_blogReply[$i],
			                  "TrackbackNum"=>$TrackbackNum[$i],
			              "blogAddtime"=>$today_blogAddtime[$i],
					  "viewNum" => $blog_visits[$i],
						  "blogWeather" => "$GLOBALS[expath]/images/weather/".$today_blogWeather[$i].".gif"));
	}
}
	    	$t->parse("m_blog","RowsBlog", true);

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
    //$url_firstpage=$GLOBALS[expath]."/exurl.php/calendar/".$iDate.".html";
    //$url_prevpage=$GLOBALS[expath]."/exurl.php/calendar/".$iDate."/".$next_page.".html";
    //$url_nextpage=$GLOBALS[expath]."/exurl.php/calendar/".$iDate."/".$next_page.".html";
    //$url_totalpage=$GLOBALS[expath]."/exurl.php/calendar/".$iDate."/".$total_page.".html";
    $url_firstpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate;
    $url_prevpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate."&amp;page=".$prev_page;
    $url_nextpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate."&amp;page=".$next_page;
    $url_totalpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate."&amp;page=".$total_page;
}else{
    $url_firstpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate;
    $url_prevpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate."&amp;page=".$prev_page;
    $url_nextpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate."&amp;page=".$next_page;
    $url_totalpage=$GLOBALS[expath]."/index.php?play=calendar&amp;date=".$iDate."&amp;page=".$total_page;
}
	$t->set_var(array("date"=>$iDate,
                      "url_firstpage"=>$url_firstpage,
                      "url_prevpage"=>$url_prevpage,
                      "url_nextpage"=>$url_nextpage,
                      "url_totalpage"=>$url_totalpage,
		              "curPage"=>$page,
		              "totalPage"=>$total_page,
                      "prev_page"=>$prev_page,
		              "next_page"=>$next_page,
		              "total_page"=>$total_page));
}

//--------------- 分析当年日期有无BLOG ----------------------//
function selectBlogToToday($month,$year,$day)
{
	global $x, $exurlon;

	if($month<=9)
		$month="0".$month;
	if($day<=9)
		$day="0".$day;
	$iTime=$year."-".$month."-".$day;
if ($exurlon) {
    $url_date=$GLOBALS[expath]."/exurl.php/calendar/";
}else{
    $url_date=$GLOBALS[expath]."/index.php?play=calendar&amp;date=";
}
	$query=mysql_query("SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iTime%'");
	$num=mysql_num_rows($query);
	if($num!=0)
	{
		if($day<=9)
		{
			$tDay=explode("0",$day);
			$tmp.="<a href=\"".$url_date.$iTime."\"><b>$tDay[1]</b></a>";
		}
		else
		{
			$tmp.="<a href=\"".$url_date.$iTime."\"><b>$day</b></a>";
		}
	}
	return $tmp;
}

//---------------- 显示日历 ----------------------//
function selectCalendar($month,$year)
{
	global $t, $langfun, $exurlon;
	if($year == "")
    {
		$year = date("Y");
	}
	else
	{
		$year = $year;
	}
	if($month == "")
	{
		$month = date("n");
	}
	else
	{
		$month = $month;
	}	
	$weekDay = date("w",mktime(0,0,0,$month,1,$year));       //星期中的第几天
	$monthTotalDay = date("t",mktime(0,0,0,$month,1,$year)); //给定月份所应有天数
	$monthDay = date("j");
	

    $br=0;
    for($i=1; $i<=$weekDay; $i++)
    {
	    $tmp.="<td class=\"daycell\">&nbsp;</td>";
	    $br++;
    }
    for($i=1; $i<=$monthTotalDay; $i++)
    {
	    $br++;
	    if($i==$monthDay && $month==date("n") && $year==date("Y"))
	    {
			$tt=selectBlogToToday($month,$year,$i);
			if($tt)
			{
				if($br==1 || $br==7)
				$tmp.="<td class=\"weektodaycell\">$tt</td>";
				else
				$tmp.="<td class=\"daycell\">$tt</td>";
		    }
			else
			{
				$tmp.="<td class=\"todaycell\">$i</td>";
			}
			if($br>=7)
		    {
			    $tmp.="</tr><tr>";
			    $br=0;
		    }
	    }
	    else
	    {
			$tt=selectBlogToToday($month,$year,$i);
			if($tt)
			{
				if($br==1 || $br==7)
				$tmp.="<td class=\"weekdaycell\">$tt</td>";
				else
				$tmp.="<td class=\"daycell\">$tt</td>";
		    }
			else
			{
				if($br==1 || $br==7)
				$tmp.="<td class=\"weekdaycell\">$i</td>";
				else
				$tmp.="<td class=\"daycell\">$i</td>";
			}

		    if($br>=7)
		    {   
				$tmp.= "</tr><tr>";
			    $br=0;
		    }			
	    }
		
    }
	if($br!=0)
	{
		for($zz=$br; $zz<7;$zz++)
		{
			$tmp.="<td class=\"daycell\">&nbsp;</td>";
	    }
	}

	if($month<12)
	{
		$next_month=$month+1;
		$curYear=$year;
	}
	else
	{
		$next_month=1;
		$curYear=$year+1;
	}
	if($month>1)
	{
		$prev_month=$month-1;
		$curYear=$year;
	}
	else
	{
		$prev_month=12;
		$curYear=$year-1;
	}
	$next_year=$year+1;
	$prev_year=$year-1;

if ($exurlon) {
    $url_nextmonth=$GLOBALS[expath]."/exurl.php/calendar/".$year."/".$next_month.".html";
    $url_prevmonth=$GLOBALS[expath]."/exurl.php/calendar/".$year."/".$prev_month.".html";
    $url_nextyear=$GLOBALS[expath]."/exurl.php/calendar/".$next_year."/".$month.".html";
    $url_prevyear=$GLOBALS[expath]."/exurl.php/calendar/".$prev_year."/".$month.".html";
}else{
    $url_nextmonth=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$year."&amp;month=".$next_month;
    $url_prevmonth=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$year."&amp;month=".$prev_month;
    $url_nextyear=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$next_year."&amp;month=".$month;
    $url_prevyear=$GLOBALS[expath]."/index.php?play=calendar&amp;year=".$prev_year."&amp;month=".$month;
}

	$t->set_var(array("url_nextmonth"=>$url_nextmonth,
	                  "url_prevmonth"=>$url_prevmonth,
	                  "url_nextyear"=>$url_nextyear,
		              "url_prevyear"=>$url_prevyear));
	$t->set_var(array("next_month"=>$next_month,
	                  "prev_month"=>$prev_month,
		              "curYear"=>$year));
	$t->set_var(array("curMonth"=>$month,
		              "next_year"=>$next_year,
		              "prev_year"=>$prev_year));
    $t->set_var("currentYearMonth","$year $langfun[47] $month $langfun[48]");
	$t->set_var("today",$tmp);
	$t->parse("m_calendar","RowCalendar",true);
}
?>
