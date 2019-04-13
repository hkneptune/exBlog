<?php
/*
 * Created on Mar 5, 2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class UseDB extends DB_Sql {
 	
 	var $classname = "UseDB";
 	var $Host;
 	var $Database;
 	var $User;
 	var $Password;
 	
 	function UseDB($host, $user, $passwd, $dbname) {
 		$this->Host = $host;
 		$this->User = $user;
 		$this->Password = $passwd;
 		$this->Database = $dbname;
 	}
 	
 	function halt($msg) {
 		printf("数据库出现错误：%s \n", $msg);
 		printf("MYSQL错误信息：%s (%s) \n", $this->Errno, $this->Error);
 		die("数据库操作挂起！");
 	}	
 }
?>
