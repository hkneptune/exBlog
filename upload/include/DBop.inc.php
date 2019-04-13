<?
 /**
  *  数据库操作类
  *  Last Modify: 2004-08-27
  *
  *	@author elliott [at] 2004-08-27
  *	@version 1.0.0
  */
  
 class DBop
 {
 	var $dbHost;
 	var $dbUser;
 	var $dbPassword;
 	var $dbName;
 
 	var $dbLink;
 	var $query;
 
 	/**
 	 *  构造函数
 	 *  Last Modify: 2004-08-27
 	 *
 	 *	@param $host 数据库地址
 	 *	@param $user 数据库使用者用户名
 	 *	@param $password 数据库使用者密码 
 	 *	@param $table 数据库表名
 	 */
 	function DBop($host, $user, $password, $dbName)
 	{
 		$this->dbHost = $host;
 		$this->dbUser = $user;
 		$this->dbPassword = $password;
 		$this->dbName = $dbName;
 	}
 
 	/**
 	 *	暂时性方法
 	 *	php类使用方法不熟悉,不知子类如何调用父类构造函数
 	 *	:~(
 	 */
 	function setDBop($host, $user, $password, $dbName)
 	{
 		$this->dbHost = $host;
 		$this->dbUser = $user;
 		$this->dbPassword = $password;
 		$this->dbName = $dbName;
 	}
 
 	/**
 	 *  连接数据库
 	 *  Last Modify: 2004-08-27
 	 */
 	function getConn()
 	{
 		$this->dbLink = mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword) or die(mysql_error());
 		
 		mysql_select_db($this->dbName) or die(mysql_error());
 	}
 
 	/**
 	 *  查询数据库
 	 *  Last Modify: 2004-08-27
 	 */
 	function executeQuery($str)
 	{
 		//echo $str."<br>";
 		$this->query = mysql_query($str) or die(mysql_error());
 	}
 	
 	/**
 	 *	取得结果集中的数据并生成数组形式
 	 *	@param null
 	 *	@return 成功返回数组,否则返回FALSE
 	 */
 	function recordArray()
 	{
 		$rows = mysql_fetch_array($this->query);
 		if(!is_array($rows))
 		{
 			echo "取得结果集失败!";
 			return false;
 		}
 		return $rows;
 	}
 
 	/**
 	 *	取得查询记录的列数
 	 *	@param null
 	 *	@return 取得的列数目
 	 */
 	function getNumRows()
 	{
 		return mysql_num_rows($this->query);
 	}
 }
 
 ?>