<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * MySQL Database Operator
 * Copyright (C) 2005, 2006  exSoft.net
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *  File: $RCSfile$
 *  Author: feeling <feeling@exblog.org>
 *  Last Modified: $Author$
 *  Date: $Date$
 *  Homepage: www.exblog.net
 *
 * $Id$
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * ~chinese MySQL 数据库操作工具
 * ~chinese 本工具集支持 MySQL / SQLite 库
 * ~chinese 本工具集可能使用到了错误处理句柄，因此在使用之前请选择好你的错误处理方式
 * ~chinese
 * 
 * @class Database mysql.php "driver/mysql.php"
 * @brief 
 * @author feeling <feeling@4ulm.org> 
 * @date 2005-01-16 03:37:16
 * @note 
 * @warning 
 * @todo 
 * @li 扩展 close() 方法，加入对以持续连接方式建立的连接的支持
 */
class Database
{
	/**
	 * ~chinese 数据库服务器IP地址或主机名
	 * 
	 * @brief 
	 */
	var $host = "localhost";
	/**
	 * ~chinese 数据库操作用户名
	 * 
	 * @brief 
	 */
	var $user = "root";
	/**
	 * ~chinese 数据库操作用户名密码
	 * 
	 * @brief 
	 */
	var $passwd = "";
	/**
	 * ~chinese 默认操作数据库名称
	 * 
	 * @brief 
	 */
	var $dbname = "";
	/**
	 * ~chinese 数据库服务器TCP端口
	 * 
	 * @brief 
	 */
	var $port = 3306;
	/**
	 * ~chinese 数据库服务器UNIX SOCKET文件位置
	 * 
	 * @brief 
	 */
	var $socket = "/tmp/mysql.sock";
	/**
	 * ~chinese 是否使用持续连接@li TRUE ＝ 是。将加大服务器负载，但是可以尽量减少数据库服务器出现“连接数过多”的错误提示
	 * 
	 * @brief 
	 * @li FALSE ＝ 否。将减小服务器负载，但是对于并发数较多的站点可能会导致时常出现“连接数过多”的错误提示
	 */
	var $pconnect = TRUE;
	/**
	 * ~chinese 数据库中所有数据表名的前缀，可用于区别同一程序的多个实例
	 * 
	 * @brief 
	 */
	var $prefix = "";
	/**
	 * ~chinese 数据库运行模式，可选值如下：@li mysql ＝ 数据库为MySQL，并计划使用PHP的MySQL库@li mysqli ＝ 数据库为 MySQL，并计划使用PHP的库
	 * 
	 * @brief 
	 */
	var $mode = "mysql";

	/**
	 * ~chinese 数据库连接ID
	 * 
	 * @internal 
	 * @brief 
	 */
	var $connect_id = 0;
	/**
	 * ~chinese 数据库查询ID
	 * 
	 * @internal 
	 * @brief 
	 */
	var $query_id = 0;
	/**
	 * ~chinese 数据库查询结果
	 * 
	 * @brief 
	 */
	var $records = array();
	/**
	 * ~chinese 数据库查询的最近错误记录
	 * 
	 * @brief 
	 * @see Database::getError()
	 */
	var $last_error = array();
	/**
	 * ~chinese 数据库缺省字符集
	 * 
	 * @brief 
	 */
	var $charset = "DEFAULT";
	/**
	 * ~chinese MySQL版本
	 * 
	 * @brief 
	 */
	var $version = "4.1.13a-nt";
	/**
	 * ~chinese 是否支持自定义字符集
	 * 
	 * @brief 
	 */
	var $support_charset = FALSE;

	/**
	 * ~chinese 构造器
	 * ~chinese 用于设置操作习惯的选项列表，其可用的索引名称为 "host", "user", "passwd", "dbname", "pconnect", "prefix", "socket", "port", "mode" 之一
	 * 
	 * @brief 
	 * @param  $options 
	 */
	function Database($options = "")
	{
		if ((TRUE == !empty($options)) && (TRUE == is_array($options)))
		{
			if (TRUE == array_key_exists("host", $options)) $this->host = $options["host"];
			if (TRUE == array_key_exists("user", $options)) $this->user = $options["user"];
			if (TRUE == array_key_exists("passwd", $options)) $this->passwd = $options["passwd"];
			if (TRUE == array_key_exists("dbname", $options)) $this->dbname = $options["dbname"];
			if (TRUE == array_key_exists("pconnect", $options)) $this->pconnect = $options["pconnect"];
			if (TRUE == array_key_exists("prefix", $options)) $this->prefix = $options["prefix"];
			if (TRUE == array_key_exists("socket", $options)) $this->socket = $options["socket"];
			if (TRUE == array_key_exists("port", $options)) $this->port = $options["port"];
			if (TRUE == array_key_exists("mode", $options)) $this->mode = $options["mode"];
		} 
	} 

	/**
	 * ~chinese 连接指定的数据库服务器，如果指定了数据库名称，则在成功连接数据库服务器以后选定该数据库
	 * ~chinese 数据库服务器IP地址或主机名
	 * ~chinese 数据库操作用户名
	 * ~chinese 数据库操作用户名密码
	 * ~chinese 默认操作数据库名称
	 * ~chinese 数据库服务器TCP端口
	 * ~chinese 数据库服务器UNIX SOCKET文件位置
	 * ~chinese 如果成功则返回连接ID，否则将错误信息标记为 E_USER_ERROR 并传递到错误处理句柄
	 * 
	 * @brief 
	 * @param  $host 
	 * @param  $user 
	 * @param  $passwd 
	 * @param  $dbname 
	 * @param  $port 
	 * @param  $socket 
	 * @return 
	 */
	function connect($host = "", $user = "", $passwd = "", $dbname = "", $port = "", $socket = "", $select_db = TRUE)
	{ 
		// -- Set default values for empty variables
		if (TRUE == empty($host)) $host = $this->host;
		if (TRUE == empty($user)) $user = $this->user;
		if (TRUE == empty($dbname)) $dbname = $this->dbname;
		if (TRUE == empty($passwd)) $passwd = $this->passwd;
		if (TRUE == empty($port) || "localhost" == $host) $port = $this->port;
		else $host = "$host:$port";
		if (TRUE == empty($socket)) $socket = $this->socket;

		if ("mysqli" != $this->mode) // -- Use MySQL functions
			{
				$function_name = (TRUE == $this->pconnect) ? "mysql_pconnect" : "mysql_connect";
			if (empty($passwd)) $connect_id = @$function_name($host, $user) or trigger_error("Could not connect to the database host", E_USER_ERROR);
			else $connect_id = @$function_name($host, $user, $passwd) or trigger_error("Could not connect to the database host", E_USER_ERROR);
			$this->connect_id = &$connect_id;
			if (TRUE === is_resource($connect_id))
			{
				if ($dbname != "" && TRUE == $select_db) @mysql_select_db($dbname, $connect_id) or trigger_error($this->getError("message"), E_USER_ERROR);
			} 
			else trigger_error(gettext('No any database had been selected'), E_USER_WARNING);
		} 
		else // -- Use MySQLi functions
			{
				$connect_id = @mysqli_connect($host, $user, $passwd, $dbname, $port, $socket) or trigger_error($this->getError("message"), E_USER_ERROR);
		} 
		$this->connect_id = &$connect_id; 
		// Detect MySQL version
		$this->query("SELECT VERSION() AS version");
		$result = $this->fetchArray(0, 'ASSOC');
		$this->freeResult();
		$this->version = $result['version'];
		$this->support_charset = $this->_isSupportCharset();
		return $this->connect_id;
	} 

	function selectDb($dbname = "", $connect_id = 0)
	{
		$result = FALSE;
		if (TRUE == empty($dbname)) $dbname = $this->dbname;
		if (FALSE == is_resource($connect_id)) $connect_id = &$this->connect_id;
		if (FALSE == empty($dbname)) $result = @mysql_select_db($dbname, $connect_id) or trigger_error($this->getError("message"), E_USER_ERROR);
		return $result;
	} 

	/**
	 * ~chinese 获取指定连接的最后一次错误信息
	 * ~chinese 获取的错误信息类型，可以为以下选项之一或者使用逗号","分隔的选项列表@li number 错误号@li message 错误描述
	 * ~chinese 指定需要获取的连接ID。如果为空，则使用最近一次的连接ID
	 * ~chinese 如果 $type 为一个选项，则返回该选项对应的值；否则返回一个索引为已指定的错误信息类型的指定连接错误信息
	 * 
	 * @brief 
	 * @param  $type 
	 * @param  $connect_id 
	 * @return 
	 */
	function getError($type, $connect_id = 0)
	{
		$type_list = explode(",", $type);
		$result = array();
		if (FALSE == is_resource($connect_id)) $connect_id = &$this->connect_id;
		if (TRUE == in_array("number", $type_list))
		{
			if ("mysqli" != $this->mode) $result["number"] = mysql_errno($connect_id);
			else $result["number"] = mysqli_errno($connect_id);
		} 
		if (TRUE == in_array("message", $type_list))
		{
			if ("mysqli" != $this->mode) $result["message"] = mysql_error($connect_id);
			else $result["message"] = mysqli_error($connect_id);
		} 
		if (count($result) < 2) return array_shift($result);
		else return $result;
	} 

	function setCharset($charset_name)
	{
		$this->charset = $charset_name;
	} 

	/**
	 * ~chinese 执行指定的SQL查询
	 * ~chinese 需要执行的SQL查询语句。如果需要同时执行多个SQL查询语句，每个SQL查询语句使用分号";"分隔
	 * ~chinese 需要执行的目标连接ID。如果为空，则使用最近一次的连接ID
	 * ~chinese 如果成功则返回SQL查询ID；否则将错误信息标记为 E_USER_WARNING 并传递到错误处理句柄
	 * 
	 * @brief 
	 * @param  $query_string 
	 * @param  $connect_id 
	 * @return 
	 */
	function query($query_string, $connect_id = 0)
	{
		if (FALSE == is_resource($connect_id)) $connect_id = &$this->connect_id;
		$queries_string = explode(";;", $query_string);
		if ("mysqli" != $this->mode)
		{
			if (TRUE == $this->support_charset) @mysql_query("set CHARACTER SET {$this->charset}", $connect_id)/* or trigger_error($this->getError("message"), E_USER_WARNING)*/;
			foreach ($queries_string as $key => $query_str)
			{
				if (FALSE == empty($query_str)) $query_id = @mysql_query($query_str, $connect_id) or trigger_error($this->getError("message"), E_USER_WARNING);
			} 
		} 
		else
		{
			if (TRUE == $this->support_charset) @mysqli_query($connect_id, "set CHARACTER SET {$this->charset}") or trigger_error($this->getError("message"), E_USER_WARNING);
			foreach ($queries_string as $key => $query_str)
			{
				if (FALSE == empty($query_str)) $query_id = @mysqli_query($connect_id, $query_string) or trigger_error($this->getError("message"), E_USER_WARNING);
			} 
		} 
		$this->query_id = &$query_id;
		return $this->query_id;
	} 

	/**
	 * ~chinese 获取指定SQL查询的结果
	 * ~chinese 需要获取的目标查询ID。如果唯恐，则使用最近一次的SQL查询ID
	 * ~chinese 需要返回的查询结果类型。
	 * ~chinese 返回一个包括执行查询结果第一条记录的数组
	 * ~chinese 本方法只能返回查询结果的第一条记录信息！如果需要返回指定查询的所有结果，请使用 fetchArrayBat()
	 * ~chinese 建议在使用本方法以后手动清除以保存的查询结果
	 * 
	 * @brief 
	 * @param  $query_id 
	 * @param  $fetch_type 
	 * @li ASSOC ＝ 仅返回以字段名作为索引名的结果
	 * @li NUM ＝ 仅返回以字段顺序号为索引名的结果
	 * @li BOTH ＝返回以字段名和字段顺序号为索引名的结果
	 * @return 
	 * @warning 
	 * @note 
	 * @see fetchArrayBat(), freeResult()
	 */
	function fetchArray($query_id = 0, $fetch_type = "BOTH")
	{
		if (FALSE == is_resource($query_id)) $query_id = &$this->query_id;
		if ("mysqli" != $this->mode) $function_name = "mysql_fetch_array";
		else $function_name = "mysqli_fetch_array";
		switch ($fetch_type)
		{
			case "ASSOC": $fetch_type_php = (("mysqli" != $this->mode) ? MYSQL_ASSOC : MYSQLI_ASSOC);
				break;
			case "NUM": $fetch_type_php = (("mysqli" != $this->mode) ? MYSQL_NUM : MYSQLI_NUM);
				break;
			default: $fetch_type_php = (("mysqli" != $this->mode) ? MYSQL_BOTH : MYSQLI_BOTH);
		} 
		$result = @$function_name($query_id, $fetch_type_php)
		/**
		 * * or trigger_error($this->getError("message"), E_USER_WARNING)/*
		 */;
		if (FALSE == $result) return FALSE;
		$this->records[] = $result;
		return $result;
	} 

	/**
	 * ~chinese 获取指定SQL查询的所有结果
	 * ~chinese 需要获取的目标查询ID。如果为空，则使用最近一次的SQL查询ID
	 * ~chinese 需要返回的查询结果类型。
	 * ~chinese 返回一个包括执行查询结果所有记录的数组
	 * ~chinese 本方法会返回查询结果的所有记录信息！如果需要返回指定查询的第一条结果，请使用 fetchArray()
	 * ~chinese 强烈建议在使用本方法以后手动清除以保存的查询结果
	 * 
	 * @brief 
	 * @param  $query_id 
	 * @param  $fetch_type 
	 * @li ASSOC ＝ 仅返回以字段名作为索引名的结果
	 * @li NUM ＝ 仅返回以字段顺序号为索引名的结果
	 * @li BOTH ＝返回以字段名和字段顺序号为索引名的结果
	 * @return 
	 * @warning 
	 * @note 
	 * @see fetchArray(), freeResult()
	 */
	function fetchArrayBat($query_id = 0, $fetch_type = 0)
	{
		if (FALSE == is_resource($query_id)) $query_id = &$this->query_id;
		if ("mysqli" != $this->mode) $function_name = "mysql_fetch_array";
		else $function_name = "mysqli_fetch_array";
		switch ($fetch_type)
		{
			case "ASSOC": $fetch_type_php = (("mysqli" != $this->mode) ? MYSQL_ASSOC : MYSQLI_ASSOC);
				break;
			case "NUM": $fetch_type_php = (("mysqli" != $this->mode) ? MYSQL_NUM : MYSQLI_NUM);
				break;
			default: $fetch_type_php = (("mysqli" != $this->mode) ? MYSQL_BOTH : MYSQLI_BOTH);
		} 
		while ($tmp = $function_name($query_id, $fetch_type_php))
		{
			if ((FALSE != $tmp) && (count($tmp) > 0)) $this->records[] = $tmp;
		} 
		return $this->records;
	} 

	function fetchRow($query_id = 0)
	{
		if (FALSE == is_resource($query_id)) $query_id = &$this->query_id;
		$this->records = @mysql_fetch_row($query_id);
		return $this->records;
	} 

	/**
	 * ~chinese 获得上次查询操作中自动生成的字段值
	 * ~chinese 需要执行的目标连接ID。如果为空，则使用最近一次的连接ID
	 * ~chinese 如果成功则返回上次查询操作中自动生成的字段值，否则将错误信息标记为 E_USER_WARNING 并传递给错误处理句柄
	 * 
	 * @brief 
	 * @param  $connect_id 
	 * @return 
	 */
	function getInsertId($connect_id = 0)
	{
		if (FALSE == is_resource($connect_id)) $connect_id = &$this->connect_id;
		if ("mysqli" != $this->mode) $function_name = "mysql_insert_id";
		else $function_name = "mysqli_insert_id";
		$this->insert_id = @$function_name($connect_id) or trigger_error($this->getError("message"), E_USER_WARNING);
		return $this->insert_id;
	} 

	/**
	 * ~chinese 获得指定数据表中的记录总数
	 * ~chinese 需要执行的目标连接ID。如果为空，则使用最近一次的连接ID
	 * ~chinese 查询的条件约束
	 * ~chinese 如果成功则返回指定数据库中的记录总数，否则将错误信息标记为 E_USER_WARNING 并传递给错误处理句柄
	 * 
	 * @brief 
	 * @param  $table_name 需要查询的数据表名
	 * @param  $connect_id 
	 * @param  $condition 
	 * @param  $ 
	 */
	function getTotalCount($table_name, $connect_id = 0, $condition = '')
	{
		if (TRUE == empty($table_name)) return trigger_error($this->error_mesgs[7], E_USER_WARNING);
		$query_string = "select count(*) from " . $this->prefix . "$table_name $condition";
		if (FALSE == is_resource($connect_id)) $connect_id = &$this->connect_id;
		if ("mysqli" != $this->mode)
		{
			$query_id = @mysql_query($query_string, $connect_id) or trigger_error($this->getError("message"), E_USER_WARNING);
			$result = @mysql_fetch_array($query_id, MYSQL_NUM) or trigger_error($this->getError("message"), E_USER_WARNING);
		} 
		else
		{
			$query_id = @mysqli_query($connect_id, $query_string) or trigger_error($this->getError("message"), E_USER_WARNING);
			$result = @mysql_fetch_array($query_id, MYSQLI_NUM) or trigger_error($this->getError("message"), E_USER_WARNING);
		} 
		return $result[0];
	} 

	/**
	 * ~chinese 获得指定SQL查询所影响的记录数
	 * ~chinese 需要执行的目标连接ID。如果为空，则使用最近一次的连接ID
	 * 
	 * @brief 
	 * @param  $connect_id 
	 */
	function getAffectedRows($connect_id = 0)
	{
		if (FALSE == is_resource($connect_id)) $connect_id = &$this->connect_id;
		$function_name = ("mysqli" != $this->mode) ? "mysql_affected_rows" : "mysqli_affected_rows";
		$result = @$function_name($connect_id) or $result = 0;
		return $result;
	} 

	/**
	 * ~chinese 获得指定SQL SELECT查询获取的记录数
	 * ~chinese 需要获取的目标查询ID。如果为空，则使用最近一次的SQL查询ID
	 * 
	 * @brief 
	 * @param  $query_id 
	 */
	function getNumRows($query_id = 0)
	{
		if (FALSE == is_resource($query_id)) $query_id = &$this->query_id;
		$function_name = ("mysqli" != $this->mode) ? "mysql_num_rows" : "mysqli_num_rows";
		$result = @$function_name($query_id) or $result = 0;
		return intval($result);
	} 

	/**
	 * ~chinese 关闭指定的数据库连接
	 * ~chinese  需要执行的目标连接ID。如果为空，则使用最近一次的连接ID
	 * ~chinese 本方法暂时对使用持续连接建立的数据库连接无效
	 * 
	 * @brief 
	 * @param  $connect_id 
	 * @note 
	 */
	function close($connect_id = 0)
	{
		if (FALSE == is_resource($connect_id)) $connect_id = $this->connect_id;
		$function_name = ("mysqli" != $this->mode) ? "mysql_close" : "mysqli_close";
		@$function_name($connect_id);
	} 

	/**
	 * ~chinese 释放指定连接上最后一次查询所使用的内存
	 * ~chinese 需要获取的目标查询ID。如果为空，则使用最近一次的SQL查询ID
	 * ~chinese 推荐在使用 fetchArrayBat() 以后立即使用本方法
	 * 
	 * @brief 
	 * @param  $query_id 
	 * @note 
	 * @see fetchArrayBat
	 */
	function freeResult($query_id = "")
	{
		if (FALSE == is_resource($query_id)) $query_id = &$this->query_id;
		$function_name = ("mysqli" != $this->mode) ? "mysql_free_result" : "mysqli_free_result";
		@$function_name($query_id);
		$this->records = array();
	} 

	/**
	 * 
	 * @brief Detect whether the version of MySQL database was greater than 4.1
	 * @return TRUE if greater than 4.1, else return FALSE
	 */
	function _isSupportCharset()
	{
		$version_str = explode(".", $this->version);
		if (4 > intval($version_str[0])) return FALSE;
		elseif (4 == intval($version_str[0]) && 1 > intval($version_str[1])) return FALSE;
		else return TRUE;
	} 
} 

?>
