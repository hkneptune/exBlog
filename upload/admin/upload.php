<?php
/*==================================================================
*\    exBlog Ver: 1.3.0  exBlog 网络日志(PHP+MYSQL) 1.3.0 版
*\------------------------------------------------------------------
*\    Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: 文件上传页面
*\=================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
require("../include/UploadFile.inc.php");

setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/upload.php");

checkLogin();                        ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 0);     ### 检查用户权限

### 查询图片上传设定信息 ###
  global $t, $x;
  $query="SELECT * FROM $x[upload]";
  $sql=mysql_query($query);
  $result=mysql_fetch_array($sql);
	$sql=mysql_query("SELECT * FROM `$x[global]`");
	$info=mysql_fetch_array($sql);
############################

$max_file_size=$result[max_file_size];     //最大文件限制，单位：byte

$upload="../".$result[destination_folder];     //目录
//$upload = "../upload/";
$readDir=readUpload($upload);            ### 读目录
$cout=count($readDir);

//用户提交附件
if ($_POST['act'] == "addAttachment") {
		$save_path = $upload;
		$type=explode(",",$result[up_type]);
		//$type = array('gif', 'jpg', 'png', 'zip', 'rar', 'txt');
		$upload = new UploadFile($_FILES['user_upload_file'], $save_path, $max_file_size, $type);
		$num = $upload->upload();

		if ($num != 0) {
			//取得用户上传文件信息
			$save_info = $upload->getSaveInfo();
			//将一部分用户上传文件信息存入数据库，如附件名，附件存储路径
			//该附件属性哪篇blog,要等发布blog后，取id，再插入数据库中。
			//$insert_id = saveAttachmentInfo($save_info);
		}
		else {
			echo "$lang[8];<br />";
		}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $langpublic[charset]; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by exSoft">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../include/upload.js"></script>
</head>
<body class="main" dir="<?php echo $langpublic[dir]; ?>">


<?php
if($_GET['action'] == "delete")
{
	if($_GET['name'] != "" && !strstr($_GET['name'], "..") ){
		unlink($upload.$_GET['name']);
			$showMsg="ok!";
			$showReturn="./upload.php?action=list";
			display($showMsg, $showReturn);
//echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=$showReturn\">";
	exit();
	}
}
elseif($_GET['action'] == "list")
{
?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="98%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b>UploadFile</b></td>
    </tr>

<?php for($i=0; $i<$cout; $i++){ ?>

    <tr>
      <td width="50%"><a href="<?php echo $upload.$readDir[$i]; ?>"><? echo "$readDir[$i]"; ?></a></td>
      <td width="20%"></td>
      <td width="20%"></td>
      <td width="10%"><a href="../admin/./upload.php?action=delete&name=<? echo "$readDir[$i]"; ?>" onclick="if(confirm ('Del OK?')){;}else{return false;}">[DEL]</a></td>
    </tr>

<?php }
}
elseif ($_GET['action'] == "addForm" || $_POST['action'] == "addForm") {
 ?>
  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
<table width="98%" border="0" align="center" cellpadding="4" cellspacing="1" class="main">
    <tr align="center">
      <td height="25" colspan="4" class="menu"><b>UploadFile</b></td>
    </tr>
    <tr>
      <td height="25" colspan="4">

  <!--  上传附件表单开始-->
<form enctype="multipart/form-data" method="POST" action="<? echo $PHP_SELF; ?>">
  <table cellpadding="4" cellspacing="1" border="0" width="100%" align="center" class="main">
    <tr> 
      <td width="20%"><?php echo $lang[9]; ?></td>
      <td colspan="3">
<a href="javascript:;" name="attach" id="attach" onclick="add()" title="<?php echo $lang[10]; ?>"><b><?php echo $lang[11]; ?></b></a>
      </td>
		<td>
	  <input type="hidden" name="act" value="addAttachment">
	  <input type="hidden" name="action" value="addForm">
          <input name="submit" type="submit"  value="<?php echo $lang[12]; ?>" class="botton">
		</td>
		</tr>
	<tr>
		<td></td>
		<td colspan="4">
			<div id="tab_attach">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">

				</table>
			</div>
			<span id="idfilespan"></span>
		</td>
	</tr>
	<tr>
		<td colspan="5">
	<?php echo $lang[13]; ?>
		</td>
	</tr>


<?php
  		//如果上传成功数目不等于0的话，就把上传文件的信息显示出来
  		if ($num != 0) {
			    echo "<tr>&nbsp;</tr>";
			for ($i = 0; $i < $num; $i++) {
				echo "<tr>"; 
				echo "<td>$lang[14]</td>";
				echo "<td width=\"19%\">".$save_info[$i]['name']."</td>";
				echo "<td width=\"15%\">$lang[15]</td>";
				echo "<td width=\"15%\">".$save_info[$i]['size']."</td>";
				echo "<td>";
				if ($ins) {
					if (strstr($save_info[$i][saveas],".gif") || strstr($save_info[$i][saveas],".bmp") || strstr($save_info[$i][saveas],".png") || strstr($save_info[$i][saveas],".jpg")) {
						echo "<a href=\"javascript:;\" onclick=\"javascript:self.opener.exEdit.content.value+='[img]".$info[siteUrl]."/nolink.php?site=1&file=".$save_info[$i][saveas]."[/img]';\">$lang[16]</a> ";
						echo "<a href=\"javascript:;\" onclick=\"javascript:self.opener.exEdit.content.value+='[url]".$info[siteUrl]."/nolink.php?site=1&file=".$save_info[$i][saveas]."[/url]';\">$lang[17]</a> ";
						echo "<br />";
					}else{
						echo "<a href=\"javascript:;\" onclick=\"javascript:self.opener.exEdit.content.value+='[url]".$info[siteUrl]."/nolink.php?site=1&file=".$save_info[$i][saveas]."[/url]';\">$lang[17]1</a> ";
						echo "<a href=\"javascript:;\" onclick=\"javascript:self.opener.exEdit.content.value+='[url]".$info[siteUrl]."/".$result[destination_folder].$save_info[$i][saveas]."[/url]';\">$lang[17]2</a> ";
						echo "<br />";
					}
				}
				echo "<a href=\"".$info[siteUrl]."/nolink.php?site=1&file=".$save_info[$i][saveas]."\">$lang[18]</a>";
				echo "<a href=\"".$info[siteUrl]."/".$result[destination_folder].$save_info[$i][saveas]."\">$lang[19]</a>";
				echo "<a href=\" $PHP_SELF?action=delete&name=". $save_info[$i]['saveas']."\">$lang[20]</a>";
				echo "</td>";
				echo "</tr>";
			}
		}
?>
  </table>
</form>
<!--  上传附件表单结束-->

</td>

<?php
}
elseif($_POST[action] == "gogogo")
{
    updateUploadset($_POST[max_file_size], $_POST[destination_folder], $_POST[up_type]);
}
elseif($_GET[action] == "edit")
{
?>
<form method="post" action="<? echo $PHP_SELF; ?>">

  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr>
      <td height="180">
  <table cellpadding="4" cellspacing="1" border="0" width="98%" align=center class="main">
    <tr>
      <td class="menu" colspan=3 height=25 align="center"><b><?php echo $lang[0]; ?></b></td>
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[1]; ?></td>
      <td>&nbsp;<input name="max_file_size" type="text" value="<? echo $result['max_file_size']; ?>" class="input" size="50" />
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[2]; ?></td>
      <td>&nbsp;<input name="destination_folder" type="text" value="<? echo $result['destination_folder']; ?>" class="input" size="50" /><br />
      <?php echo $lang[3]; ?></td>
    </tr>
    <tr>
      <td width="15%" height=23 align="center"><?php echo $lang[4]; ?></td>
      <td>&nbsp;<input name="up_type" type="text" value="<? echo $result['up_type']; ?>" class="input" size="50" /><br />
      <?php echo $lang[5]; ?></td>
    </tr>

    <tr align="center">
      <td height=30 colspan="3">
<input type="hidden" name="action" value="gogogo">
	  <input type="submit" value="<?php echo $lang[6]; ?>" class="botton">
	  &nbsp;&nbsp;&nbsp;&nbsp; 
	  <input type="reset" value="<?php echo $lang[7]; ?>" class="botton">	  </td>
    </tr>
  </table></td>
  </tr>
</table>
</form>
  <? } ?>
</body>
</html>

