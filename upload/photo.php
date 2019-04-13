<?php

/*============================================================================
*\    exBlog Ver: 1.2.0 RC1 exBlog 网络日志(PHP+MYSQL) 1.2.0 RC1 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 图片上传处理
*\===========================================================================*/



require("include/config.inc.php");
require("include/global-B.inc.php");
require("include/global-C.inc.php");
setGlobal();
checkLogin();                        
checkUserUid($_SESSION[exPass]);
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
    'image/x-png');
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
<meta Name="author" content="elliott,hunter">
<meta name="keywords" content="elliott,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by elliott">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>exBlog图片上传程序</title>
<link href="images/admin.css" rel="stylesheet" type="text/css">
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
<table cellpadding="４" cellspacing="1" border="0" width="60%" align=center class="a2">
    <tr>
      <td class="a1" colspan=2 height=25 align="center"><b>exBlog图片上传页面</b></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">上传文件:</td>
      <td width="80%">&nbsp; <input name="upfile" type="file"> </td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">允许上传的文件类型</td>
      <td width="80%"><?=implode(', ',$uptypes)?></td>
    </tr>
	 <tr align="center" class=a4>
      <td height=30 colspan="2">
	  <input type="submit" value="上传">
    </tr>
   </table>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{ 
 if (!is_uploaded_file($_FILES["upfile"][tmp_name]))
 //是否存在文件
 {  
  echo "图片不存在!";
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
 $destination = $destination_folder.time().".".$ftype;
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
 echo " <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"60%\" align=center class=a2>";
 echo " <tr>";
 echo " <td class=a1 colspan=2 height=25 align=center><b>图片成功上传！</b></td>";
 echo " </tr>";
 echo " <tr class=a4>";
 echo " <td width=100%>文件名:<font color=blue>".$destination_folder.$fname."</font><br></td>";
 echo " </tr>";
 echo " <tr class=a4>";
 echo " <td width=100%>宽度:".$image_size[0]."&nbsp&nbsp&nbsp&nbsp长度:".$image_size[1]."&nbsp&nbsp&nbsp&nbsp大小:".$file["size"]." bytes </td> ";
 echo " <tr class=a4>";
 echo " <td width=100%>如果选择默认位置,请将此段代码复制到BLOG编辑区: <span id=img><font color=blue>[img]".$destination_folder.$fname."[/img]</font></span><br><a href=# onClick='copyText(document.all.img)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
 echo " <tr class=a4>";
 echo " <td width=100%> 如果选择图片居左,请将此段代码复制到BLOG编辑区: <span id=limg><font color=blue>[limg]".$destination_folder.$fname."[/img]</font></span><br><a href=# onClick='copyText(document.all.limg)'><b>点击这里复制到剪贴板</b></a></td>"; 
 echo " </tr>";
 echo " <tr class=a4>";
 echo " <td width=100%>如果选择图片居右,请将此段代码复制到BLOG编辑区: <span id=rimg><font color=blue>[rimg]".$destination_folder.$fname."[/img]</font></span><br><a href=# onClick='copyText(document.all.rimg)'><b>点击这里复制到剪贴板</b></a></td>";
 echo " </tr>";
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
  echo " <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"60%\" align=center class=a2>";
  echo " <tr>";
  echo " <td class=a1 colspan=2 height=25 align=center><b>图片预览：</b></td>";
  echo " </tr>";
  echo " <tr>";
  echo " <td align=\"center\" valign=\"middle\"><img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
  echo " alt=\"图片预览:\r文件名:".$destination."\">";
  echo " </td>";
  echo " </tr>";
  echo " </table>";
 }
} 
?>
</body>
</html>



