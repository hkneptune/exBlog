<?php
/*============================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2005. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: Trackback Ping 处理
*\===========================================================================*/
/*    例子：
*\    http://127.0.0.1/blog/extb.php?id=1
*\----------------------------------------------------------------------------
*\    url
*\    blog_name
*\    title
*\    excerpt
*\===========================================================================*/

require("./include/config.inc.php");

if (empty($id)) tbshow("Invalid Trackback ID",1); //没有指定文章编号
else{
$query="SELECT * FROM `".$exBlog[one]."blog` WHERE id='$id'";

$result=mysql_num_rows(mysql_query($query));
	if( !$result ) tbshow("Invalid Trackback ID",1); //没有找到这篇文章
}

if (empty($excerpt)) tbshow("No Content Submitted",1); //没有发来内容

if (empty($url) || !eregi("http://", $url)) tbshow("Invalid URI",1); //地址错误

//处理各信息
$url=strip_tags(safe_convert($url));
$excerpt=strip_tags(safe_convert($excerpt));
$excerpt=cuttheword($excerpt,250); //把内容截取250个字符
if (empty($title)) $title=$excerpt;
$title=cuttheword($title,18); //把标题截取18个字符
$title=strip_tags(safe_convert($title));
if (empty($blog_name)) $blog_name=cuttheword($url,18); //把站名截取20个字符
$blog_name=strip_tags(safe_convert($blog_name));
$tbtime=date(Y."-".m."-".d." ".H.":".i.":".s);


$query="SELECT * FROM `".$exBlog[one]."trackback` WHERE TrackbackID='$id' AND blog_name='$blog_name'";
$query.=" AND url='$url' AND excerpt='$excerpt' ORDER BY id DESC LIMIT 0,1";
    $result=mysql_num_rows(mysql_query($query));
	if($result > 0) tbshow("Sorry!",1); //重复发送
 
$query="INSERT INTO `".$exBlog[one]."trackback` (TrackbackID, url, blog_name, title, excerpt, addtime)";
$query.=" VALUES('$id', '$url', '$blog_name', '$title', '$excerpt', '$tbtime')";
	$result=@mysql_query($query);
	if($result > 0) tbshow("OK!",0); //成功
    else tbshow("Error!",1); //失败

//输出
function tbshow($tbword, $errorid)
{
header("Content-type:application/xml");
echo "<?xml version=\"1.0\" encoding=\"gb2312\"?".">";
print <<<eot
<response>
<error>$errorid</error>
<message>$tbword</message>
</response>
eot;
exit;
}
//处理文字，去掉不安全因素
function safe_convert($d)
{
	$d = htmlspecialchars($d);
	$d = str_replace("<","&lt;",$d);
	$d = str_replace(">","&gt;",$d);
	$d = str_replace("javascript","j avascript",$d);
	$d = str_replace("vbscript","v bscript",$d);
	$d = str_replace("\r","<br />",$d);
	$d = str_replace("\n","",$d);
	$d = str_replace("  "," &nbsp;",$d);
	$d = str_replace("{","&#123;",$d);
	$d = str_replace("}","&#125;",$d);
	return $d;
}
//截取字符
function cuttheword($wordgo, $cutn)
{
		$len = strlen($wordgo);
		if ($len <= $cutn) $wordgo = $wordgo;
		else $wordgo = substr($wordgo,"0","$cutn").chr(0)."...";
        return $wordgo;
}
