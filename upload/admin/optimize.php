<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 [L]  exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 数据库优化页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

checkLogin();       ### 检查用户是否非法登录
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlog - Powered by exSoft</title>
<link href="../images/admin.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>

<style type="text/css">
<!--
.style7 {font-size: 8pt}
-->
</style>
</head>
<?
global $dbhost, $dbuname, $dbpass, $dbname;
$dbhost=$exBlog['host'];
$dbuname=$exBlog['user'];
$dbpass=$exBlog['password'];
$dbname=$exBlog['dbname'];
$prefix=$exBlog['one'];
$len=strpos($prefix,"_")+1;
echo "<center><font class=\"title\">".最优化数据库."$dbname</font></center><br><br>"
	."<table border=1 align=\"center\"><tr><td><div align=center>".数据表."</div></td><td><div align=center>".大小."</div></td><td><div align=center>".状态."</div></td><td><div align=center>".已节省空间."</div></td></tr>";
    $db_clean = $dbname;
    $tot_data = 0;
    $tot_idx = 0;
    $tot_all = 0;
    $local_query = 'SHOW TABLE STATUS FROM '.$dbname;
    $result = mysql_query($local_query);
    if (mysql_num_rows($result)) {
	while ($row = mysql_fetch_array($result)) {
    	     $subtable = substr($row[0],0,$len);
	if($subtable==$prefix)
           {
            $tot_data = $row['Data_length'];
            $tot_idx  = $row['Index_length'];
            $total = $tot_data + $tot_idx;
            $total = $total / 1024 ;
            $total = round ($total,3);
            $gain= $row['Data_free'];
            $gain = $gain / 1024 ;
            $total_gain += $gain;
            $gain = round ($gain,3);   
            $local_query = 'OPTIMIZE TABLE '.$row[0];
	     $resultat  = mysql_query($local_query);
       	    if ($gain == 0) {
       		echo "<tr><td>"."$row[0]"."</td>"."<td>"."$total"." Kb"."</td>"."<td>".已完成优化."</td><td>0 Kb</td></tr>";
       	    } else {
       	   	echo "<tr><td><b>"."$row[0]"."</b></td>"."<td><b>"."$total"." Kb"."</b></td>"."<td><b>".完成优化！."</b></td><td><b>"."$gain"." Kb</b></td></tr>";
       	    }
	     }
	else{
	     continue;
	     }	
	}	
	}
    echo "</table>";
    echo "</center>";
    echo "<br>";
    $total_gain = round ($total_gain,3);
    echo "<center><b>".最优化结果."</b><br><br>"
	."".共节省空间." "."$total_gain"." Kb<br>";

?>