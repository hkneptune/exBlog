<?php
###################
# 本程序[PHP简单防盗链]核心部分来自网络 不知其原作者 且向其致敬 谢谢
# 经过修改适用于 exBlog 1.3.0
# <a href="http://test.exblog.net/nolink.php?site=1&file=snow.gif" target="_blank">111</a>
# <img src="http://test.exblog.net/nolink.php?site=1&file=snow.gif" border="0">
###################
require("./include/config.inc.php");
require("./include/global-C.inc.php");
setGlobal();
selectUP();

$ADMIN[defaulturl] = "$info[siteUrl]/images/404.html";//盗链返回的地址
$ADMIN[defaultimg] = "$info[siteUrl]/images/404.gif";//盗链返回的图片
$okaysites = array("$info[siteUrl]","http://www.madcn.com","http://www.exblog.net"); //白名单 
$ADMIN[url_1] = "$info[siteUrl]/$info_up[destination_folder]";//下载地点1
$reffer = $_SERVER['HTTP_REFERER'];
if($reffer) {
$yes = 0;
while(list($domain, $subarray) = each($okaysites)) {
if (ereg($subarray,"$reffer")) {
$yes = 1;
}
}
$theu = "url"."_"."$_GET[site]";
if ($ADMIN[$theu] AND $yes == 1) {
header("Location: ".$ADMIN[$theu].$_GET[file]);
} else {
	if (strstr($_GET[file],".gif") || strstr($_GET[file],".bmp") || strstr($_GET[file],".png") || strstr($_GET[file],".jpg")) header("Location: $ADMIN[defaultimg]");
	else header("Location: $ADMIN[defaulturl]");
}
} else {
	if (strstr($_GET[file],".gif") || strstr($_GET[file],".bmp") || strstr($_GET[file],".png") || strstr($_GET[file],".jpg")) header("Location: $ADMIN[defaultimg]");
	else header("Location: $ADMIN[defaulturl]");
}

###### 查询上传信息 ######
function selectUP()
{
	global $t, $x, $info, $info_up, $exurlon, $db;

	$sql=mysql_query("SELECT * FROM `$x[global]`");
    $info=mysql_fetch_array($sql);
	$sql_up=mysql_query("SELECT * FROM `$x[upload]`");
    $info_up=mysql_fetch_array($sql_up);
}
?>