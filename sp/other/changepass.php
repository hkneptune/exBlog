<?php

//------------------------------------------------------------------------+
//
//   exblog 用户名和密码修改工具    by tomy
//
//   此文件请上传到跟index.php在同一个目录
//
//------------------------------------------------------------------------+

	if(isset($Submit)){

		$nn = $_POST['n'];  
		$pp = $_POST['p']; 
	
		include("include/config.inc.php");
		$sql = "UPDATE ".$exBlog['one']."admin SET user= '".$nn."', password= '".md5($pp)."' WHERE id=1";
		
		if(mysql_query($sql))
		{
			print '修改密码成功！';
		}else{
			print '修改失败～';
		}
		
		$sql1 = "select * from ".$exBlog['one']."admin";
		$query = mysql_query($sql1);
		$arr = mysql_fetch_array($query);
		
		print '用户名:'.$nn.'<br>';
		print '密码:'.$pp.'<br>';
		
		exit;
	}

?>

<form name="form1" method="post" action="changepass.php">
  <p>请输入您要修改的用户名和密码,点击提交进行修改：</p>
  <p>用户名:
      <input name="n" type="text" id="n">
    </p>
  <p>
    密　码:
      <input name="p" type="password" id="p">
    </p>
  <p><br>
    <input type="submit" name="Submit" value="提交">
  </p>
</form>