<?php
/**
 *	检测在线用户类
 *  Last Modify: 2004-09-06
 *
 *	@author elliott [at] 2004-09-06
 *	@version 1.0.0
 */

include("DBop.inc.php");

class GetOnlineUser extends DBop
{
	var $checkSecond = 300;
	var $userIP;
	
	var $dbTable;
	var $dbRows;
	
	/**
	 *	设置存放用户在线信息表
	 *	@param $dbTable 表名
	 *	@return null
	 */
	function GetOnlineUser($host, $user, $password, $dbName)
	{
		$this->setDBop($host, $user, $password, $dbName);
		$this->getConn();
	}

	/**
	 *	设置检测用户在线秒数
	 *	@param sec
	 *	@return null
	 */
	function setCheckSec($sec)
	{
		$this->checkSecond = $sec;
	}
	
	function setDBtable($str)
	{
		$this->dbTable = $str;
	}

	/**
	 *	取得客户端IP,并以此做为判断的根据
	 *	@param null
	 *	@return null
	 */
	function getIP()
	{
		return $this->userIP = getEnv("REMOTE_ADDR");
	}
	
	/**
	 *	设置检测存活区间时间,默认为5分钟
	 *	@param $second 秒数
	 *	@return null
	 */
	function setCheckSecond($second)
	{
		$this->checkSecond = $second;
	}

	/**
	 *	判断在线用户
	 *	@param null
	 *	@return null
	 */
	function check()
	{
		//删除大于一小时的记录
		$this->executeQuery("DELETE FROM `$this->dbTable` WHERE (UNIX_TIMESTAMP(NOW())- UNIX_TIMESTAMP(lastLoginTime)) > '3600'");
		$time = date("Y-m-d H:i:s");
		$ip = $this->getIP();
		$this->executeQuery("SELECT * FROM `$this->dbTable` WHERE ip = '$ip'");

		if($this->getNumRows())
		{
			$this->executeQuery("UPDATE `$this->dbTable` SET lastLoginTime = '$time' WHERE ip = '$ip'");
		}
		else
		{
			$this->executeQuery("INSERT INTO `$this->dbTable` (ip, lastLoginTime) VALUES ('$ip', '$time')");
		}
		$this->executeQuery("SELECT COUNT(*) AS onlineNum FROM `$this->dbTable` WHERE (UNIX_TIMESTAMP(NOW())- UNIX_TIMESTAMP(lastLoginTime)) <= $this->checkSecond");

		$this->dbRows = $this->recordArray();

		return $this->dbRows['onlineNum'];
	}

	function display($rows)
	{
		echo $rows['onlineNum'];
	}
}

/*
useage:

$online = new GetOnlineUser("localhost", "elliott", "working@mysql", "only");
$online->setDBtable($x['online']);
$onlineNum = $online->check();
echo $onlineNum;

*/

?>