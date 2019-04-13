<?php 
############################################################ 
// exBlog 的伪静态地址实现页面
// 能让原来这样滴地址：
// http://www.madcn.com/blog/?play=reply&id=475
// 喀嚓！就变成这样了：
// http://www.madcn.com/blog/exurl.php/475.html 
// 据说，有“欺骗”搜索引擎之功效，呵呵
// 实现原理很简单，在这里，我写的太乱了
// 有些多余的东西也没有删掉，对功能没有影响，仅供娱乐
// 有兴趣的朋友可以访问：
// http://www.madcn.com/blog/exurl.php/475.html 
############################################################ 

#########################
// 不兼容保护 1 表示关闭 可以绝对的屏蔽掉这个功能
$off = "0";
if ( $off ) { 
    include("index.php");
    exit();
} 

#########################

//echo " $_SERVER[PATH_INFO] <br>"; //输出 /s/w.htm 

if ( isset($_SERVER["PATH_INFO"]) ) { 
  //list($nothing, $play, $id) = explode('/', $_SERVER["PATH_INFO"]); 
  //list($nothing, $id) = explode('/', $_SERVER["PATH_INFO"]); 
  $exurl = explode('/', $_SERVER["PATH_INFO"]); 
} 
//当没有参数时发出提示
if ( !isset($exurl[1]) ) { echo "访问页面不存在 <br>";exit(); }

//开始判断第一层参数，一般是对应 play=$play
switch ($exurl[1]) {
//这个有点多余，但好象还算有意义，一旦提交双斜杠的时候就发挥作用了，担不是根本的解决办法。

  case "":
    echo "访问页面不存在 <br>";exit();
    break;

############################################################ 
//当 play=reply 时。对应：
// http://www.madcn.com/blog/?play=reply&id=475
// http://www.madcn.com/blog/exurl.php/reply/475.html 
############################################################ 

  case "reply":
    $play = "reply";
    if ( !isset($exurl[2]) ) { echo "访问页面不存在 <br>";exit(); }
    $id = $exurl[2];
    $id_num = strlen($id);//取得字符串长度 
    if( $id_num>4 ) { 
    //用正则表达式匹配 
      $id = preg_replace("/(.*).html/is","\\1",$id); //这个为什么方前面你知道么?呵! 
      $id = preg_replace("/(.*).htm/is","\\1",$id); 
      $id = preg_replace("/(.*).php/is","\\1",$id); 
      $id = preg_replace("/(.*).asp/is","\\1",$id); 
      $id = preg_replace("/(.*).cgi/is","\\1",$id); 
      $id = preg_replace("/(.*).txt/is","\\1",$id); 
    } 
    $exurl=2;
    include("index.php");
    //header("location: ./../../index.php?play=$play&id=$id");
    break;

//当 play=view 时。

  case "view":
    $play="view";
    if ( !isset($exurl[2]) ) { echo "访问页面不存在 <br>";exit(); }

  if( !isset($exurl[3] ) ) {
############################################################
//当不存在翻页的时候
// http://www.madcn.com/blog/?play=view&sort=xiangsu
// http://www.madcn.com/blog/exurl.php/view/xiangsu.html 
############################################################

    $sort = $exurl[2];
    $id_num = strlen($sort);//取得字符串长度 
    if( $id_num>4 ) { 
    //用正则表达式匹配 
      $sort = preg_replace("/(.*).html/is","\\1",$sort); //这个为什么方前面你知道么?呵! 
      $sort = preg_replace("/(.*).htm/is","\\1",$sort); 
      $sort = preg_replace("/(.*).php/is","\\1",$sort); 
      $sort = preg_replace("/(.*).asp/is","\\1",$sort); 
      $sort = preg_replace("/(.*).cgi/is","\\1",$sort); 
      $sort = preg_replace("/(.*).txt/is","\\1",$sort); 
    } 
    $exurl=2;
    include("index.php");
    //header("location: ./../../index.php?play=$play&sort=$sort");
  }else{

############################################################ 
//当需要翻页的时候
// http://www.madcn.com/blog/?play=view&sort=xiangsu&page=1
// http://www.madcn.com/blog/exurl.php/view/xiangsu/1.html 
############################################################ 
    $sort = $exurl[2];
    $page = $exurl[3];
    $id_num = strlen($page);//取得字符串长度 
    if( $id_num>4 ) { 
    //用正则表达式匹配 
      $page = preg_replace("/(.*).html/is","\\1",$page); //这个为什么方前面你知道么?呵! 
      $page = preg_replace("/(.*).htm/is","\\1",$page); 
      $page = preg_replace("/(.*).php/is","\\1",$page); 
      $page = preg_replace("/(.*).asp/is","\\1",$page); 
      $page = preg_replace("/(.*).cgi/is","\\1",$page); 
      $page = preg_replace("/(.*).txt/is","\\1",$page); 
    } 
    $exurl=3;
    include("index.php");
    //header("location: ./../../../index.php?play=$play&sort=$sort&page=$page");
  }

    break;

############################################################ 
//当 play=show 时。
// http://www.madcn.com/blog/?play=show&id=475
// http://www.madcn.com/blog/exurl.php/show/475.html 
############################################################ 

  case "show":
    $play="show";
    if ( !isset($exurl[2]) ) { echo "访问页面不存在 <br>";exit(); }
    $id = $exurl[2];
    $id_num = strlen($id);//取得字符串长度 
    if( $id_num>4 ) { 
    //用正则表达式匹配 
      $id = preg_replace("/(.*).html/is","\\1",$id); //这个为什么方前面你知道么?呵! 
      $id = preg_replace("/(.*).htm/is","\\1",$id); 
      $id = preg_replace("/(.*).php/is","\\1",$id); 
      $id = preg_replace("/(.*).asp/is","\\1",$id); 
      $id = preg_replace("/(.*).cgi/is","\\1",$id); 
      $id = preg_replace("/(.*).txt/is","\\1",$id); 
    } 
    $exurl=2;
    include("index.php");
    //header("location: ./../../index.php?play=$play&id=$id");
    break;

############################################################ 
//当 play=links 时。
// http://www.madcn.com/blog/?play=links
// http://www.madcn.com/blog/exurl.php/links.html 
############################################################ 

  case "links.html":
    $play="links";
    $exurl=1;
    include("index.php");
    //header("location: ./../index.php?play=$play");
    break;

############################################################ 
//当 play=search 时。
// http://www.madcn.com/blog/?play=search
// http://www.madcn.com/blog/exurl.php/search.html 
############################################################ 

  case "search.html":
    $play="search";
    $exurl=1;
    include("index.php");
    //header("location: ./../index.php?play=$play");
    break;

############################################################ 
//当 play=announce 时。
// http://www.madcn.com/blog/?play=announce&id=2
// http://www.madcn.com/blog/exurl.php/announce/2.html 
############################################################ 

  case "announce":
    $play="announce";
    if ( !isset($exurl[2]) ) { echo "访问页面不存在 <br>";exit(); }
    $id = $exurl[2];
    $id_num = strlen($id);//取得字符串长度 
    if( $id_num>4 ) { 
    //用正则表达式匹配 
      $id = preg_replace("/(.*).html/is","\\1",$id); //这个为什么方前面你知道么?呵! 
      $id = preg_replace("/(.*).htm/is","\\1",$id); 
      $id = preg_replace("/(.*).php/is","\\1",$id); 
      $id = preg_replace("/(.*).asp/is","\\1",$id); 
      $id = preg_replace("/(.*).cgi/is","\\1",$id); 
      $id = preg_replace("/(.*).txt/is","\\1",$id); 
    } 
    $exurl=2;
    include("index.php");
    //header("location: ./../../index.php?play=$play&id=$id");
    break;

//当 play=calendar 时。

  case "calendar":
    $play="calendar";
    if ( !isset($exurl[2]) ) { echo "访问页面不存在 <br>";exit(); }
    $id3 = strstr($exurl[3], ".html");
    $id4 = strstr($exurl[4], ".html");
    if ($id3 == false && $id4 == false) 
    {
############################################################ 
// http://www.madcn.com/blog/?play=calendar&date=2005-01-12
// http://www.madcn.com/blog/exurl.php/calendar/2005-01-12.html 
############################################################ 
      $date = $exurl[2];
      $id_num = strlen($date);//取得字符串长度 
      if( $id_num>4 ) { 
      //用正则表达式匹配 
        $date = preg_replace("/(.*).html/is","\\1",$date); //这个为什么方前面你知道么?呵! 
        $date = preg_replace("/(.*).htm/is","\\1",$date); 
        $date = preg_replace("/(.*).php/is","\\1",$date); 
        $date = preg_replace("/(.*).asp/is","\\1",$date); 
        $date = preg_replace("/(.*).cgi/is","\\1",$date); 
        $date = preg_replace("/(.*).txt/is","\\1",$date); 
      } 
    $exurl=2;
    include("index.php");
      //header("location: ./../../index.php?play=$play&date=$date");
      break;
    }elseif ($id4 == false){
############################################################ 
// http://www.madcn.com/blog/?play=calendar&year=2005&month=01
// http://www.madcn.com/blog/exurl.php/calendar/2005/01.html 
############################################################ 
      $year = $exurl[2];
      $month = $exurl[3];
          $month_num = strlen($month);//取得字符串长度 
          if( $month_num>5 ) { 
          //用正则表达式匹配 
            $month = preg_replace("/(.*).html/is","\\1",$month); 
          } 
    $exurl=3;
    include("index.php");
//header("location: ./../../../index.php?play=$play&year=$year&month=$month");
      break;
    }else{
############################################################ 
// http://www.madcn.com/blog/?play=calendar&year=2005&month=01&page=2
// http://www.madcn.com/blog/exurl.php/calendar/2005/01/2.html 
############################################################ 
      $year = $exurl[2];
      $month = $exurl[3];
      $page = $exurl[4];
          $month_num = strlen($page);//取得字符串长度 
          if( $month_num>5 ) { 
          //用正则表达式匹配 
            $page = preg_replace("/(.*).html/is","\\1",$page); 
          } 
    $exurl=4;
    include("index.php");
//header("location: ./../../../index.php?play=$play&year=$year&month=$month&page=$page");
      break;
    }

############################################################ 
//当 play=regLink 时。
// http://www.madcn.com/blog/?play=regLink
// http://www.madcn.com/blog/exurl.php/regLink.html 
############################################################ 

  case "regLink.html":
    $play="regLink";
    $exurl=1;
    include("index.php");
    //header("location: ./../index.php?play=$play");
    break;

//当 有其他情况 时。

  default:

//什么也没有，不由分说，去首页
    $id1 = strstr($exurl[1], ".html");
    if ($id1 == false) {
    $exurl=1;
    include("index.php");
        //header("location: ./../index.php");
    }else{

    $id2 = strstr($exurl[1], "page");
        if ($id2 == false) {

############################################################ 
//文章的地址简化
// http://www.madcn.com/blog/?play=reply&id=475
// http://www.madcn.com/blog/exurl.php/475.html 
############################################################ 
          $play = "reply";
          $id = $exurl[1];
          $id_num = strlen($id);//取得字符串长度 
          if( $id_num>5 ) { 
          //用正则表达式匹配 
            $id = preg_replace("/(.*).html/is","\\1",$id); 
          } 
    $exurl=1;
    include("index.php");
          //header("location: ./../index.php?play=$play&id=$id");
    break;
        }else{
############################################################ 
//翻页
// http://www.madcn.com/blog/?page=2
// http://www.madcn.com/blog/exurl.php/page2.html 
############################################################ 
          $page = $exurl[1];
          $id_num = strlen($page);//取得字符串长度 
          if( $id_num>9 ) { 
          //用正则表达式匹配 
            $page = preg_replace("/page(.*).html/is","\\1",$page); 
          } 
    $exurl=1;

    include("index.php");
          //header("location: ./../index.php?page=$page");
    break;
        }
}

}

?>
