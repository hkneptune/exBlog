<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
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
 *  File: public.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-26 18:20
 *  Homepage: www.exblog.net
 *
 * $Id: public.php,v 1.2 2005/07/02 05:39:05 feeling Exp $
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class CommonLib
{

	var $_lang = array();
	var $_config = array();

	function CommonLib()
	{
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig();
		// 装入系统配置
		$this->_loadSystemOptions();
		// 装入语言文件
		include_once("../{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
		// 输入数据
		$this->_input['_POST'] = $_POST;
		$this->_input['_POST'] = $this->escapeSqlCharsFromData($this->_input['_POST']);
	}

	function printPageHeader()
	{
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<meta Name=\"author\" content=\"exSoft\">
<meta name=\"keywords\" content=\"{$this->_config['global']['sitekeyword']}\">
<meta name=\"description\" content=\"{$this->_config['global']['Description']}\">
<meta name=\"MSSmartTagsPreventParsing\" content=\"TRUE\">
<meta http-equiv=\"MSThemeCompatible\" content=\"Yes\">
<title>{$this->_config['global']['siteName']} - Powered by exSoft</title>
<link href=\"../images/style.css\" rel=\"stylesheet\" type=\"text/css\">
</head>
<body class=\"main\" dir=\"{$this->_lang['public']['dir']}\">
		";
	}

	function printPageFooter()
	{
		echo "</body></html>";
	}

	function _showError($errors = "", $return_url = "")
	{
		if (TRUE == empty($errors)) $errors = $this->errors;
		echo "
<html>
<!-- css -->
<style type=text/css>
.menu { font-family: Verdana; font-size: 8pt; font-weight: bold; }
.content { font-family: Verdana; font-size: 8pt; }
.border { border: 1px #000000 dotted; }
a:visited {  color: #000000; text-decoration: none }
a:link {  color: #000000; text-decoration: none }
a:hover {  color: blue; text-decoration: underline }
</style>
<!-- css -->
<title>{$this->_lang['public'][10]}</title>
<meta http-equiv=Content-Type content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
		";
		if (FALSE == empty($return_url)) echo "<meta HTTP-EQUIV=REFRESH CONTENT=\"2;URL=$return_url\">";
		echo "
</head>
<body dir=\"{$this->_lang['public']['dir']}\">
<div id=msgboard style=\"position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;\">
<table cellspacing=0 cellpadding=5 width=100% class=border>
<tr bgcolor=#99CC66>
<td height=11 align=left bgcolor=#E1E4CE class=menu>{$this->_lang['public'][11]}</td></tr><tr>
<td align=left class=content>
		";

		if (TRUE == is_array($errors) && 1 < count($errors))
		{
			for ($i = 0; $i < count($errors); $i++) echo "<li><font color=red>{$errors[$i]}</font>";
		}
		elseif (TRUE == is_array($errors)) echo "<font color=red>{$errors[0]}</font>";
		else echo "<font color=red>$errors</font>";

		if (TRUE == empty($return_url)) echo "<p align=left><a href=\"javascript:history.go(-1);\">{$this->_lang['public'][12]}</a></p>";

		echo "</td></tr></table></div></body></html>";
		exit();
	}

	function showMesg($mesg, $return_url)
	{
		echo "
<html><head>
<!-- css -->
<style type=text/css>
.menu { font-family: Verdana; font-size: 8pt; font-weight: bold; }
.content { font-family: Verdana; font-size: 8pt; }
.border { border: 1px #000000 dotted; }
a:visited {  color: #000000; text-decoration: none }
a:link {  color: #000000; text-decoration: none }
a:hover {  color: blue; text-decoration: underline }
</style>
<!-- css -->
<title>{$this->_lang['public'][13]}</title>
<meta http-equiv=Content-Type content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<meta HTTP-EQUIV=REFRESH CONTENT=\"2;URL=$return_url\">
</head>
<body dir=\"{$this->_lang['public']['dir']}\">
<div id=msgboard style=\"position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;\">
<table cellspacing=0 cellpadding=5 width=100% class=border>
<tr bgcolor=#99CC66>
<td height=11 align=left bgcolor=#E1E4CE class=menu>{$this->_lang['public'][14]}</td></tr><tr>
<td align=left class=content>
		";

		if (TRUE == is_array($mesg) && 1 < count($mesg))
		{
			for ($i = 0; $i < count($mesg); $i++) echo "<li><font color=red>{$mesg[$i]}</font>";
		}
		elseif (TRUE == is_array($mesg)) echo "<font color=red>{$mesg[0]}</font>";
		else echo "<font color=red>$mesg</font>";

		echo "
<p align=left><a href=\"$return_url\">{$this->_lang['public'][15]}</a></p></td></tr>
</table></div>
</body></html>
		";

		exit();
	}

	/**
	 * @brief @~chinese 装入系统选项
	 * @return @~chinese 如果成功则返回TRUE，否则返回FALSE
	 **/
	function _loadSystemOptions()
	{
		$query_string = "select * from {$this->_dbop->prefix}global limit 0, 1";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getAffectedRows()) return FALSE;
		else $this->_config['global'] = $this->_dbop->fetchArray($this->_dbop->query_id, MYSQL_ASSOC);
		$this->_dbop->freeResult();
		include("../include/config.inc.php");
		if (FALSE == empty($version['update'])) $this->_config['global']['version_number'] = $version['update'];
		return $this->_config['global'];
	}

	function _loadDatabaseConfig()
	{
		if (FALSE == @file_exists("../include/config.inc.php")) $this->_showError("Could not loading configuration!");
		include_once("../include/config.inc.php");
		include_once("../include/mysql.php");
		$this->_dbop = new Database();
		$this->_dbop->host = $exBlog['host'];
		$this->_dbop->user = $exBlog['user'];
		$this->_dbop->passwd = $exBlog['password'];
		$this->_dbop->prefix = $exBlog['one'];
		$this->_dbop->dbname = $exBlog['dbname'];
		$this->_dbop->connect("", "", "", "", "", "", TRUE);
		// 设置数据库缺省字符集
		$this->_dbop->setCharset($exBlog['charset']);
	}

	/**
	 * @brief @~chinese 遍历指定的目录
	 * @param $dir_path @~chinese 需要遍历的目录
	 * @param $only_dir @~chinese 是否只返回子目录列表，缺省为是。@n TRUE＝是，FALSE＝否
	 * @return @~chinese 如果成功则返回一个目录下所有文件的列表（不包括当前目录.和上级目录.以及隐藏文件/目录），否则返回一个空列表。
	 * @n 文件列表的格式如： array( array('path' => '/path/to/file1', 'name' => 'Name_Of_File1'), array('path' => '/path/to/file2', 'name' => 'Name_Of_File2'), ...)
	 **/
	function _walkDir($dir_path, $only_dir = TRUE)
	{
		$entries = array();
		// 检查目标是否存在并且是否为目录
		if (FALSE == @file_exists($dir_path) || FALSE == @is_dir($dir_path)) return $entries;
		$dirop = dir($dir_path);
		while (FALSE !== ($entry = $dirop->read()))
		{
			if (substr($entry, 0, 1) != "." && $entry != "CVS")
			{
				if ((TRUE == $only_dir && TRUE == @is_dir("$dir_path/$entry")) || FALSE == $only_dir) $entries[] = array("path" => $entry, "name" => $this->_formatDirName($entry));
			}
		}
		$dirop->close();
		return $entries;
	}

	function _formatDirName($dir_name)
	{
		$search_pattern = array("/_/");
		$replace_pattern = array(" ");
		return preg_replace($search_pattern, $replace_pattern, $dir_name);
	}

	function _isEmailAddress($email)
	{
		if (TRUE != preg_match('|[^@]{1,64}@[^@]{1,255}|', $email)) return FALSE;
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++)
		{
			if (TRUE != preg_match("/^[a-zA-Z0-9_\-]+$/", $local_array[$i])) return FALSE;
		}
		if (TRUE !== ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) { echo "DD"; return FALSE; }
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if (TRUE != preg_match("/^[a-zA-Z0-9_\-]+$/", $domain_array[$i])) return FALSE;
			}
		}
		return TRUE;
	}

	function _formatFileSize($size)
	{
		$size = intval($size);
		if ($size > 1024000000) $new_size = sprintf("%01.2f GB", $size / 1024000000);
		elseif ($size > 1024000) $new_size = sprintf("%01.2f MB", $size / 1024000);
		elseif ($size > 1024) $new_size = sprintf("%01.2f KB", $size / 1024);
		else $new_size = sprintf("%d Bytes", $size);
		return $new_size;
	}

	function escapeSqlCharsFromData(&$var_names, $except_vars = "")
	{
		if (TRUE == is_array($var_names))
		{
			foreach ($var_names as $key => $value) $var_names[$key] = $this->escapeSqlCharsFromData($value, $except_vars);
		}
		else
		{
			if (FALSE == empty($except_vars) && FALSE == is_array($except_vars)) $except_vars[] = $except_vars;
			elseif (TRUE == empty($except_vars)) $except_vars = array();
			if (FALSE == in_array($var_names, $except_vars))
			{
				if (TRUE == get_magic_quotes_gpc()) $var_names = stripslashes($var_names);
				if (TRUE == function_exists("mysql_real_escape_string")) $var_names = mysql_real_escape_string($var_names, $this->_dbop->connect_id);	// Require PHP version >= 4.3.0
				else $var_names = mysql_escape_string($var_names);	// PHP version < 4.3.0
			}
		}
		return $var_names;
	}

	function checkUser($user_level)
	{
		session_cache_limiter('private, must-revalidate');
		@session_start();
		// 用户未登录
		if (FALSE == session_is_registered("exPass") || FALSE == session_is_registered("userID") || FALSE == session_is_registered("exPassword")) return FALSE;
		// 检查用户权限
		$query_string = "select id from {$this->_dbop->prefix}admin where id={$_SESSION['userID']} and uid<=$user_level";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getAffectedRows()) return FALSE;
		else return TRUE;
	}

	function getUserInfo($user_id = 0, $info_name = "", $use_cache = TRUE)
	{
		@session_start();
		if (TRUE == empty($user_id)) $user_id = $_SESSION['userID'];
		if (FALSE == $use_cache || TRUE == empty($this->member_info) || FALSE == count($this->member_info))
		{
			$query_string = "select * from {$this->_dbop->prefix}admin where id=$user_id";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['public'][27];
			else $this->member_info = $this->_dbop->fetchArray();
			$this->_dbop->freeResult();
		}
		if (TRUE == empty($info_name)) return $this->member_info;
		if (TRUE == array_key_exists($info_name, $this->member_info)) return $this->member_info[$info_name];
		else $this->errors[] = $this->_lang['public'][28];
		return FALSE;
	}

	// ----------------------------------------------------------------------------
	// 中文截取
	// ----------------------------------------------------------------------------
	function _substr($str, $start, $length = -1)
	{
		if ($length == 0) return "";
		if ($start < 0) $start = 0;
		for ($i = 0; $i < $start; $i++)
		{
			if (ord(substr($str, $i, 1)) >= 0x81)
			{
				$start++;
				$i++;
			}
		}
		if ($start > $this->getStrLen($str)) return "";
		$ss = "";
		if ($length == -1) $ss = substr($str, $start);
		else
		{
			for ($i = $start; $i < $start + $length; $i++)
			{
				if (ord(substr($str, $i, 1)) >= 0x81)
				{
					$ss .= substr($str, $i, 2);
					$length++;
					$i++;
				}
				else $ss .= substr($str, $i, 1);
			}
		}
		if ($this->getStrLen($str) > $this->getStrLen($ss)) $ss .= "...";
		return $ss;
	}

	function getStrLen($str)
	{
		$len = strlen($str);
		$l = 0;
		for ($i = 0;$i < $len; $i++)
		{
			if (ord(substr($str,$i,1)) >= 0x81) $i++;
			$l++;
		}
		return $l;
	}

	function _printEmailAddress($email, $switch_name)
	{
		switch ($switch_name)
		{
			case 'hidden' : $email = ''; break;
			case 'escape' : $email = str_replace("@", "#", $email); break;
			default : break;
		}
		return $email;
	}

	function _flushPrivilege()
	{
		@session_start();
		$user_info = $this->getUserInfo($_SESSION['userID']);
		if (TRUE == $user_info)
		{
			session_unregister("exPass");
			session_unregister("userID");
			$_SESSION['exPass'] = $user_info['user'];
			$_SESSION['userID'] = $user_info['id'];
			$_SESSION['exUseremail'] = $user_info['email'];
			$_SESSION['exUserlastvisit'] = $user_info['lastvisit'];
			$_SESSION['exUserIpAddress'] = $user_info['ipaddress'];
			$_SESSION['userLevel'] = $user_info['uid'];
		}
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
		return TRUE;
	}
}
?>
