<?php

/*============================================================================
*\    exBlog Ver: 1.2.0 [L] exBlog 网络日志(PHP+MYSQL) 1.2.0 [L] 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 图片上传处理
*\===========================================================================*/



require("include/config.inc.php");
require("include/global-B.inc.php");
require("include/global-C.inc.php");
setGlobal();
checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限
global $t, $x;
$query="SELECT * FROM $x[photo]";
$sql=mysql_query($query);
$result=mysql_fetch_array($sql);
$uptypes=array('image/jpg',   //支持图片类型
    'image/jpeg', 
    'image/png', 
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png',
	'application/x-rar-compressed',
	'application/x-zip-compressed',
	'application/x-shockwave-flash',
	'application/x-msdownload',
	'application/octet-stream',
	'text/txt',
	'text/htm',
	'text/html',
	'audio/mpeg',);
$max_file_size=$result[max_file_size];     //最大文件限制，单位：byte
$destination_folder=$result[destination_folder]; //上传文件夹路径
$watermark=$result[watermark];      //是否添加水印
$watertype=1;      //水印类型(1为文字,2为图片)
$waterposition=$result[waterposition];      //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring=$result[waterstring];  //水印字符串
$waterimg="xplore.gif";    //水印图片
$imgpreview=1;      //是否生成预览图(1为生成,其他为不生成);
$imgpreviewsize=1/2;    //缩略图比例
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="exsoft">
<meta name="keywords" content="exsoft,exBlog,weblog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exsoft">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlog图片上传程序</title>
<link href="images/style.css" rel="stylesheet" type="text/css">
<SCRIPT language=JavaScript>
<!--
function copyText(obj) {
var rng = document.body.createTextRange();
rng.moveToElementText(obj);
rng.scrollIntoView();
rng.select();
rng.execCommand("Copy");
rng.collapse(false);
}
//-->
</SCRIPT>
</head>

<body>
<form enctype="multipart/form-data" method="post" name="upform">
  <table width="90%" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr>
      <td class="menu" colspan=2 height=25 align="center"><b>exBlog文件上传页面</b></td>
    </tr>
    <tr>
      <td width="20%" height=23 align="center">上传文件:</td>
      <td width="80%">&nbsp; <input name="upfile" type="file" class=input> </td>
    </tr>
    <tr>
      <td width="20%" height=23 align="center">允许上传的文件类型</td>
      <td width="80%">jpg,jpeg,pjpeg,gif,bmp,png,htm,html,txt,rar,zip,swf,mpeg,exe</td>
    </tr>
	 <tr align="center" class=a4>
      <td height=30 colspan="2">
	  <input type="submit" value="上传" class="botton">
    </tr>
   </table>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{ 
 if (!is_uploaded_file($_FILES["upfile"][tmp_name]))
 //是否存在文件
 {  
  echo "文件不存在!";
  exit;
 }
 
    $file = $_FILES["upfile"];
   if($max_file_size < $file["size"])
   //检查文件大小
   {
    echo "文件太大!";
    exit;
   }

 if(!in_array($file["type"], $uptypes))
 //检查文件类型
 {
    echo "文件类型不符!".$file["type"];
    exit;  
 }
 
 if(!file_exists($destination_folder))
  mkdir($destination_folder);
  
 $filename=$file["tmp_name"];
 $image_size = getimagesize($filename); 
 $pinfo=pathinfo($file["name"]);
 $ftype=$pinfo[extension];
 $realname=basename($file["name"],".".$ftype);
 $destination = $destination_folder.$realname.".".$ftype;
  
 

 if (file_exists($destination) && $overwrite != true)
 { 
            echo "同名文件已经存在了"; 
            exit;
    }
    
    if(!move_uploaded_file ($filename, $destination))
    {
       echo "移动文件出错"; 
            exit; 
    }

 $pinfo=pathinfo($destination);
 $fname=$pinfo[basename];

 if(($file["type"]=="image/gif")||($file["type"]=="image/pjpeg")||($file["type"]=="image/png")||($file["type"]=="image/bmp")||($file["type"]=="image/jpg")||($file["type"]=="image/jpeg"))
 {

 echo " <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"60%\" align=center class=main>";
 echo " <tr>";
 echo " <td class=menu colspan=2 height=25 align=center><b>图片成功上传！</b></td>";
 echo " </tr>";
 echo " <tr>";
 echo " <td width=100%>文件名:<font color=blue>".$destination_folder.$fname."</font><br></td>";
 echo " </tr>";
 echo " <tr>";
 echo " <td width=100%>宽度:".$image_size[0]."&nbsp&nbsp&nbsp&nbsp长度:".$image_size[1]."&nbsp&nbsp&nbsp&nbsp大小:".$file["size"]." bytes </td> ";
 echo " <tr>";
if($html==0){
 echo " <td width=100%>如果选择默认位置,请将此段代码复制到BLOG编辑区: <span id=img><font color=blue>[img]".$destination_folder.$fname."[/img]</font></span><br><a href=# onClick='copyText(document.all.img)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
 echo " <tr>";
 echo " <td width=100%> 如果选择图片居左,请将此段代码复制到BLOG编辑区: <span id=limg><font color=blue>[limg]".$destination_folder.$fname."[/limg]</font></span><br><a href=# onClick='copyText(document.all.limg)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
 echo " <tr>";
 echo " <td width=100%>如果选择图片居右,请将此段代码复制到BLOG编辑区: <span id=rimg><font color=blue>[rimg]".$destination_folder.$fname."[/rimg]</font></span><br><a href=# onClick='copyText(document.all.rimg)'><b>点击这里复制到剪贴板</b></a></td>";
 echo " </tr>";
}else{
 echo " <td width=100%>如果选择默认位置,请将此段代码复制到BLOG编辑区: <span id=img><font color=blue>&lt;img src=".$destination_folder.$fname." border=0 &gt;</font></span><br><a href=# onClick='copyText(document.all.img)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
}
 echo " </table>";
 if($watermark==1)
 	{
  $iinfo=getimagesize($destination,$iinfo);
  $nimage=imagecreatetruecolor($image_size[0],$image_size[1]);
  $white=imagecolorallocate($nimage,255,255,255);
  $black=imagecolorallocate($nimage,0,0,0);
  $red=imagecolorallocate($nimage,255,0,0);
  imagefill($nimage,0,0,$white);
  switch ($iinfo[2])
  	{
   case 1:
    $simage =imagecreatefromgif($destination);
    break; 
   case 2:
    $simage =imagecreatefromjpeg($destination);
    break; 
   case 3:
    $simage =imagecreatefrompng($destination);
    break; 
   case 6:
    $simage =imagecreatefromwbmp($destination);
    break; 
   default:
    die("不支持的文件类型");
    exit;
  	}
  
  imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
  imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);
  
  switch($watertype)
  	{
   case 1:   //加水印字符串
    imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
    break;
   case 2:   //加水印图片
    $simage1 =imagecreatefromgif("xplore.gif");
    imagecopy($nimage,$simage1,0,0,0,0,85,15);
    imagedestroy($simage1);
    break;
 	 }  
  
  switch ($iinfo[2])
  	{
   case 1:
    imagegif($nimage, $destination);
    break; 
   case 2:
    imagejpeg($nimage, $destination);
    break; 
   case 3:
    imagepng($nimage, $destination);
    break; 
   case 6:
    imagewbmp($nimage, $destination);
    //imagejpeg($nimage, $destination);
    break;
  	}
  
  //覆盖原上传文件
  imagedestroy($nimage);
  imagedestroy($simage);
 	}
 
 if($imgpreview==1)
 	{
  echo " <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"60%\" align=center class=main>";
  echo " <tr>";
  echo " <td class=menu colspan=2 height=25 align=center><b>图片预览：</b></td>";
  echo " </tr>";
  echo " <tr>";
  echo " <td align=\"center\" valign=\"middle\"><img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
  echo " alt=\"图片预览:\r文件名:".$destination."\">";
  echo " </td>";
  echo " </tr>";
  echo " </table>";
 	}
 }
 else{
 echo " <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"60%\" align=center class=main>";
 echo " <tr>";
 echo " <td class=menu colspan=2 height=25 align=center><b>文件成功上传！</b></td>";
 echo " </tr>";
 echo " <tr>";
 echo " <td width=100%>文件名:<font color=blue>".$destination_folder.$fname."</font><br></td>";
 echo " </tr>";
 echo " <tr>";
 echo " <td width=100%>大小:".$file["size"]." bytes </td> ";
 echo " <tr>";
if($html==0){
 echo " <td width=100%>请将此代码复制到BLOG编辑区 <span id=file><font color=blue>[url]".$destination_folder.$fname."[/url]</font></span><br><a href=# onClick='copyText(document.all.file)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
}else{
 echo " <td width=100%>请将此代码复制到BLOG编辑区 <span id=file><font color=blue>&lt;a href=".$destination_folder.$fname." target=_blank&gt;".$destination_folder.$fname."&lt;/a&gt;</font></span><br><a href=# onClick='copyText(document.all.file)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
}
 echo " <tr>";
 echo " <td width=100%>如果上传的是SWF，MPEG类型文件，请自行更换相应代码，使之适合你的BLOG框架大小，以保持美观</td>"; 
 echo " </tr>";
 echo " </table>";
 	}
} 
?>
</body>
</html>



