<?
//---------------- 查询指定年月的BLOG --------------------//
function selectCurrentYearMonthBlog($iYear,$iMonth)
{
	global $t, $prev_page, $next_page, $total_page, $x;
	$page_size=5;
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
    $sql=mysql_query("SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iTime%' ORDER BY id DESC LIMIT $start,$page_size");

    $num=mysql_num_rows($sql);
    if($num == 0)
    {
	    $t->set_var(array("blogTitle"=>"该月目前无任何文章","currentSort"=>$sortName[cnName]));
	    $t->parse("RowsBlog","RowBlog",true);
    }
    else
    {
	    $i=0;
	    while($rows=mysql_fetch_array($sql))
	    {
		    if($i<=$num)
	    	{
				$rows['content']=substr($rows['content'],"0","450").chr(0);
	            $rows['content']=$rows['content']."...";
		    	$rows['title']=htmlspecialchars($rows['title']);
	            $rows['content']=htmlspecialchars($rows['content']);
		    	$rows['content']=ubb($rows['content']);
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

	     		$i++;
		    }
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
	$t->set_var(array("curYear"=>$iYear,
		              "curMonth"=>$tmpMonth,
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
	global $t, $x;

    // 设置导航栏内容
	$t->set_var("currentSort","通过日历页面访问指定日期BLOG");
	$query=mysql_query("SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iDate%'");
	$num=mysql_num_rows($query);

	$i=0;
	while($rows=mysql_fetch_array($query))
	{
		if($i<$num)
		{
			$rows['content']=substr($rows['content'],"0","450").chr(0);
	        $rows['content']=$rows['content']."...";
		    $rows['title']=htmlspecialchars($rows['title']);
	        $rows['content']=htmlspecialchars($rows['content']);
		    $rows['content']=ubb($rows['content']);
	        $rows['content']=epost($rows['content']);
			$rows['addtime']=eregi_replace($iDate,"<font color=red><b><u>$iDate</u></b></font>",$rows['addtime']);

            $today_blogID[$i]=$rows['id'];
    		$today_blogTitle[$i]=$rows['title'];
	    	$today_blogContent[$i]=$rows['content'];
		    $today_blogAuthor[$i]=$rows['author'];
			$today_blogEmail[$i]=$rows['email'];
		    $today_blogAddtime[$i]=$rows['addtime'];
		    $today_blogSort[$i]=$rows['sort'];
	           $today_blogWeather[$i] = $rows['weather'];
                  $blog_visits[$i] = $rows['visits'];

			$sql2=mysql_query("SELECT commentID FROM `$x[comment]` WHERE commentID='$today_blogID[$i]'");
			$today_blogReply[$i]=mysql_num_rows($sql2);

			$i++;
		}
	}
	for($i=0; $i<$num; $i++)
	{
		$t->set_var(array("blogID"=>$today_blogID[$i],
			              "blogTitle"=>$today_blogTitle[$i],
			              "blogContent"=>$today_blogContent[$i],
			              "blogSort"=>$today_blogSort[$i],
			              "blogAuthor"=>$today_blogAuthor[$i],
			              "blogEmail"=>$today_blogEmail[$i],
			              "blogVisits"=>$today_blogReply[$i],
			              "blogAddtime"=>$today_blogAddtime[$i],
					  "viewNum" => $blog_visits[$i],
						  "blogWeather" => "./images/weather/".$today_blogWeather[$i].".gif"));

		$t->parse("RowsBlog","RowBlog",true);
	}
}

//--------------- 分析当年日期有无BLOG ----------------------//
function selectBlogToToday($month,$year,$day)
{
	global $x;

	if($month<=9)
		$month="0".$month;
	if($day<=9)
		$day="0".$day;
	$iTime=$year."-".$month."-".$day;
	$query=mysql_query("SELECT * FROM `$x[blog]` WHERE addtime LIKE '%$iTime%'");
	$num=mysql_num_rows($query);
	if($num!=0)
	{
		if($day<=9)
		{
			$tDay=explode("0",$day);
			$tmp.="<a href=\"?play=calendar&date=$iTime\"><b>$tDay[1]</b></a></td>";
		}
		else
		{
			$tmp.="<a href=\"?play=calendar&date=$iTime\"><b>$day</b></a></td>";
		}
	}
	return $tmp;
}

//---------------- 显示日历 ----------------------//
function selectCalendar($month,$year)
{
	global $t;
	if($_GET['year'] == "")
    {
		$year = date("Y");
	}
	else
	{
		$year = $_GET['year'];
	}
	if($_GET['month'] == "")
	{
		$month = date("n");
	}
	else
	{
		$month = $_GET['month'];
	}	
	$weekDay = date("w",mktime(0,0,0,$month,1,$year));       //星期中的第几天
	$monthTotalDay = date("t",mktime(0,0,0,$month,1,$year)); //给定月份所应有天数
	$monthDay = date("j");
	

    $br=0;
    for($i=1; $i<=$weekDay; $i++)
    {
	    $tmp.="<td bgcolor=\"#ffffff\">&nbsp;</td>";
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
				$tmp.="<td bgcolor=\"#dddddd\" class=\"calendar\">$tt</td>";
		    }
			else
			{
				$tmp.="<td bgcolor=\"#dddddd\" class=\"calendar\">$i</td>";
			}
			if($br>=7)
		    {
			    $tmp.="</tr><tr align=\"center\" class=\"calendar\">";
			    $br=0;
		    }
	    }
	    else
	    {
			$tt=selectBlogToToday($month,$year,$i);
			if($tt)
			{
				$tmp.="<td bgcolor=\"#ffffff\" class=\"calendar\">$tt</td>";
		    }
			else
			{
				$tmp.="<td bgcolor=\"#ffffff\" class=\"calendar\">$i</td>";
			}

		    if($br>=7)
		    {   
				$tmp.= "</tr><tr align=\"center\" class=\"calendar\">";
			    $br=0;
		    }			
	    }
		
    }
	if($br!=0)
	{
		for($zz=$br; $zz<7;$zz++)
		{
			$tmp.="<td bgcolor=\"#ffffff\">&nbsp;</td>";
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
	$t->set_var(array("next_month"=>$next_month,
	                  "prev_month"=>$prev_month,
		              "curYear"=>$year));
	$t->set_var(array("curMonth"=>$month,
		              "next_year"=>$next_year,
		              "prev_year"=>$prev_year));
    $t->set_var("currentYearMonth","$year 年 $month 月");
	$t->set_var("today",$tmp);
	$t->parse("RowsCalendar","RowCalendar",true);
}
?>