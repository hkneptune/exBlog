<?php
class Html
{
	function printHeader($current_step = 1)
	{
		echo "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<title>{$this->destination_version['str']} {$this->_lang['upgrade'][0]}</title>
<link href=\"../images/style.css\" rel=\"stylesheet\" type=\"text/css\">
<style>
<!--
.nowt { text-align: center; font-weight: bold; background-color: #CCCCCC; font-size: 12 px; }
.noww { text-align: center; font-size: 12 px; }
.errorMesg { color: #FF0000; }
.noticeMesg { color: #0000FF; }
.errorDesc { color: #FF0000; font-size: 11px; }
-->
</style>
<script language=JavaScript>
function changeLanguage(obj_value)
{
	query_string = location.search;
	reg_expression_1 = /(.*?)lang=(.*?)&(.*)/g;
	reg_expression_2 = /(.*?)lang=.*/g;
	if ('' == query_string) query_string = \"?lang=\" + obj_value;
	else if (reg_expression_1.test(query_string)) query_string = query_string.replace(reg_expression_1, \"$1lang=\" + obj_value +\"&$3\");
	else if (reg_expression_2.test(query_string)) query_string = query_string.replace(reg_expression_2, \"$1lang=\" + obj_value);
	else query_string += \"&lang=\" + obj_value;
	tmp_string = location.protocol + \"//\" + location.hostname;
	if ('' != location.port) tmp_string += \":\" + location.port;
	tmp_string += location.pathname + query_string;
	location.href = tmp_string;
}
</script>
</head>
<body dir=ltr>
<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}?lang={$this->language_package}\">
<input type=\"hidden\" name=\"action\" value=\"step{$current_step}\">
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td bgcolor=\"#FFFFFF\" class=\"menu\"><div align=\"center\"><font color=\"#FF0000\">{$this->destination_version['str']} {$this->_lang['upgrade'][0]}</div></td></tr>
</table>
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
  <tr><td align=right><select name=language_package id=language_package onChange=changeLanguage(this.options[selectedIndex].value)>
		";
		for ($i = 0; $i < count($this->language_packages); $i++)
		{
			echo "<option value=\"{$this->language_packages[$i]['path']}\"";
			if ($this->language_package == $this->language_packages[$i]['path']) echo " selected";
			echo ">{$this->language_packages[$i]['name']}</option>";
		}
		echo "
  </select></td></tr>
</table>
<!--  当前步骤游标 -->
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr>";
	for ($i = 1; $i <= $this->steps_count; $i++) echo "<td width=\"7%\" class=\"" . (($i === $this->current_step) ? "nowt" : "noww") . "\">" . sprintf($this->_lang['upgrade'][1], $i) . "</td>";
	echo "
  </tr>
</table>
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
    <tr>
    <td bgcolor=\"#FFFFFF\" class=\"main\"><br />";
	}

	function printFooter()
	{
		echo "</td></tr></table></form></body></html>";
	}

	function _showWelcome()
	{
		$this->printHeader($this->current_step);
		echo "
        &nbsp; <img src=\"../images/group.gif\" width=\"17\" height=\"14\">{$this->_lang['upgrade'][2]}<br />
		<p style=\"margin-left: 10px; margin-right: 10px;\">{$this->_lang['upgrade'][3]}</p>
        <div align=\"center\"><textarea rows=17 cols=75 style=\"border: 1 solid #C7C7C7; overflow-y:hidden;\">{$this->_lang['upgrade'][4]}</textarea></div>
        <p align=\"right\" style=\"margin-left: 10px; margin-right: 10px;\">{$this->_lang['upgrade'][5]}</p>
        <div align=\"center\">
          <input type=\"submit\" value=\"{$this->_lang['upgrade'][6]}\" class=\"botton\">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type=\"button\" value=\"{$this->_lang['upgrade'][7]}\" onclick=\"window.close()\" class=\"botton\">
          <br />
          &nbsp; </div>
		";
	}

	function _showActions($action_desc, $message, $result)
	{
		$this->printHeader($this->current_step);
		$errors_count = 0;
		echo "<p style=\"margin-left: 10px; margin-right: 10px; font-size: 13px; font-weight: bold;\"> $action_desc</p>";
		echo "<table width=95% cellpadding=2 cellspacing=2 border=0 align=center>";
		if (FALSE == is_array($message))
		{
			$style_name = "noticeMesg";
			if (FALSE == $result)
			{
				$errors_count++;
				$style_name = "errorMesg";
			}
			echo "<tr><td align=left bgcolor=\"#FFFFFF\" class=\"main\">{$message}</td><td bgcolor=\"#FFFFFF\" class=\"main\"><span class=\"$style_name\">" . ((FALSE == $result) ? $this->_lang['upgrade'][9] : $this->_lang['upgrade'][8]) . "</span></td>";
		}
		else
		{
			for ($i = 0; $i < count($message); $i++)
			{
				$style_name = "noticeMesg";
				if (FALSE == $result[$i])
				{
					$errors_count++;
					$style_name = "errorMesg";
				}
				echo "<tr><td align=left bgcolor=\"#FFFFFF\" class=\"main\">{$message[$i]}</td><td bgcolor=\"#FFFFFF\" class=\"main\"><span class=\"$style_name\">" . ((FALSE == $result[$i]) ? $this->_lang['upgrade'][9] : $this->_lang['upgrade'][8]) . "</span></td>";
			}
		}
		echo "</table>";
		echo "<div align=center>";
		if (0 < $errors_count) echo sprintf($this->_lang['upgrade'][10], $errors_count) . "<br /><br /><input type=button value=\"{$this->_lang['upgrade'][11]}\" class=\"botton\" onClick=\"javascript:location.href='{$_SERVER['PHP_SELF']}?step={$this->current_step}&action=undo';\" disabled title=\"{$this->_lang['upgrade'][12]}\"/>";
		elseif ($this->current_step == $this->steps_count) echo "<input type=button value=\"{$this->_lang['upgrade'][13]}\" class=\"botton\" onClick=\"window.location='login.php';\"/>";
		else echo "<input type=submit value=\"{$this->_lang['upgrade'][14]}\" class=\"botton\" />";
		echo "<br />&nbsp; </div>";

//		if (0 < count($this->errors)) print_r($this->errors);
	}

}

class Upgrade extends Html
{
	var $errors = array();
	var $actions = array();
	var $action_messages = array();
	var $sql_sentences = array();
	var $sql_messages = array();
	var $steps_count = 4;
	var $current_step = 1;
	var $config_file = "../include/config.inc.php";
	var $_dbop = 0;
	var $new_config_items = array();
	var $_lang = array();
	var $language_package = "";
	var $language_packages = array();

	function Upgrade()
	{
		$this->_loadDatabaseConfig();
		$this->_loadOldSystemOptions();

		// 语言包设置
		if (FALSE == empty($_GET['lang'])) $this->language_package = $_GET['lang'];
		else $this->language_package = basename($this->_config['global']['langURL']);
		$this->language_packages = $this->_walkDir("../language");
		for ($i = 0; $i < count($this->language_packages); $i++) $tmp[] = basename($this->language_packages[$i]['path']);
		if (FALSE == in_array($this->language_package, $tmp)) $this->language_package = basename($this->_config['global']['langURL']);

		include_once("../language/{$this->language_package}/public.php");
		include_once("../language/{$this->language_package}/upgrade.php");
		$this->_lang['upgrade'] = $lang;
		$this->_lang['public'] = $langpublic;

		if (FALSE == empty($_POST['action']) && TRUE == preg_match("/^step\d+$/i", $_POST['action'])) $this->current_step = intval(preg_replace("/^step(\d+)$/i", "$1", $_POST['action'])) + 1;
		if ($this->steps_count < $this->current_step) $this->current_step = 1;
	}

	function startUpgrade()
	{
		if (FALSE == empty($_POST['action']))
		{
			if ('step1' == $_POST['action'])
			{
				// 装入旧版本配置文件
				$messages[] = $this->_lang['upgrade'][15];
				$results[] = $this->_loadOldConfig($this->config_file);
				// 比较版本信息
				$messages[] = $this->_lang['upgrade'][16];
				$results[] = $this->_compareVersions();
				$this->_showActions($this->_lang['upgrade'][17], $messages, $results);
			}
			elseif ('step2' == $_POST['action'])
			{
				$messages = $this->sql_messages;
				$results = $this->_executeSqlQueries($this->sql_sentences);
				$this->_showActions($this->_lang['upgrade'][18], $messages, $results);
			}
			elseif ('step3' == $_POST['action'])
			{
				$messages = $this->_lang['upgrade'][19];
				$results = $this->_reWriteConfig();
				$this->_showActions($this->_lang['upgrade'][20], $messages, $results);
			}
			else $this->_showWelcome();
		}
		else $this->_showWelcome();
		exit();
	}

	// 添加SQL操作
	function _addSqlSentences($sql_sentence)
	{
		if (FALSE == is_array($sql_sentence))
		{
			$tmp = str_replace("%%%PREFIX%%%", '', $sql_sentence);
			$this->sql_sentences[] = $sql_sentence;
			if (TRUE == preg_match("/^ALTER\s+TABLE\s+/i", $sql_sentence))
				$this->sql_messages[] = sprintf($this->_lang['upgrade'][21], preg_replace("/^ALTER\s+TABLE\s+([^\s]*?)\s+(.*)/i", "\\1", $tmp));
			elseif (TRUE == preg_match("/^CREATE\s+TABLE\s+/i", $sql_sentence))
				$this->sql_messages[] = sprintf($this->_lang['upgrade'][22], preg_replace("/^CREATE\s+TABLE\s+([^\s]+?)\s+(.*)/i", "\\1", $tmp));
			elseif (TRUE == preg_match("/^DROP\s+TABLE\s+/i", $sql_sentence))
				$this->sql_messages[] = sprintf($this->_lang['upgrade'][23], preg_replace("/^DROP\s+TABLE\s+([^\s]+?)\s+(.*)/i", "\\1", $tmp));
			elseif (TRUE == preg_match("/^UPDATE\s+/i", $sql_sentence))
				$this->sql_messages[] = sprintf($this->_lang['upgrade'][24], preg_replace("/^UPDATE\s+([^\s]+?)\s+(.*)/i", "\\1", $tmp));
			else $this->sql_messages[] = $tmp;
		}
		else
		{
			foreach ($sql_sentence as $key => $value) $this->_addSqlSentences($value);
		}
	}

/**
	function _addActions($funname)
	{

	}
**/
	function _reWriteConfig()
	{
		$parttern = "/\\\$version\['(\w+?)'\] = \"([^\"]*?)\";.*/";
		$replace_1 = '$1';
		$replace_2 = '$2';
		$tmp = '';
		$file_id = @fopen($this->config_file, "rb");
		@flock($file_id, LOCK_SH);
		while (FALSE == feof($file_id))
		{
			$buffer = trim(fgets($file_id, 4096));
			if (TRUE == preg_match($parttern, $buffer))
			{
				$key_name = preg_replace($parttern, $replace_1, $buffer);
				if ("string" == $key_name) $buffer = "\$version['string'] = \"{$this->destination_version['str']}\";";
				elseif ("update" == $key_name) $buffer = "\$version['update'] = \"{$this->destination_version['num']}\";";
			}
			elseif (TRUE == preg_match("/^\/\/\s+Generated\s+By\s+Installation\s+Guide/i", $buffer)) $buffer = $buffer ." | Modified By Upgrade Guide on " . date("r");
			if ('?>' != $buffer)$tmp .= $buffer . "\n";
		}
		@flock($file_id, LOCK_UN);
		@fclose($file_id);

		if (0 < count($this->new_config_items)) $tmp .= "// Added by Upgrade Guide on " . date("r") . "\n";
		for ($i = 0; $i < count($this->new_config_items); $i++) $tmp .= $this->new_config_items[$i] . "\n";
		$tmp .= "?" . ">\n";

		$file_id = @fopen($this->config_file, "wb");
		@flock($file_id, LOCK_EX);
		$result = @fwrite($file_id, $tmp);
		@flock($file_id, LOCK_SH);
		@fclose($file_id);

		return $result;
	}

	function _addConfigItems($items)
	{
		if (FALSE == is_array($items)) $this->new_config_items[] = $items;
		else
		{
			foreach ($items as $key => $value) $this->_addConfigItems($value);
		}
	}

	// 设置需求版本
	function setRequireVersion($version_number, $version_string)
	{
		$this->required_version = array("str" => $version_string, "num" => $version_number);
	}

	// 设置升级目标版本
	function setDestinationVersion($version_number, $version_string)
	{
		$this->destination_version = array("str" => $version_string, "num" => $version_number);
	}

	// 比较版本
	function _compareVersions()
	{
		$result = FALSE;
		if (TRUE == empty($this->required_version['num'])) $this->errors[] = $this->_lang['upgrade'][25];
		elseif (TRUE == empty($this->old_version['num'])) $this->errors[] = $this->_lang['upgrade'][26];
		elseif ($this->required_version['num'] != $this->old_version['num'])
			$this->errors[] = sprintf($this->_lang['upgrade'][27], $this->required_version['str'], $this->old_version['str']);
		else $result = TRUE;
		return $result;
	}

	function _loadDatabaseConfig()
	{
		if (FALSE == @file_exists($this->config_file)) die("Could not found the old configure file");
		include_once($this->config_file);
		include_once("../include/mysql.php");
		$this->_dbop = new Database();
		$this->_dbop->host = $exBlog['host'];
		$this->_dbop->user = $exBlog['user'];
		$this->_dbop->passwd = $exBlog['password'];
		$this->_dbop->prefix = $exBlog['one'];
		$this->_dbop->dbname = $exBlog['dbname'];
		$this->_dbop->connect("", "", "", "", "", "", TRUE);
		// 设置数据库缺省字符集
		$this->_dbop->setCharset("GB2312");
	}

	// SQL操作
	function _executeSqlQueries($sql_sentences)
	{

		if (TRUE == is_array($sql_sentences))
		{
			for ($i = 0; $i < count($this->sql_sentences); $i++)
			{
				$results[] = $this->_dbop->query(str_replace("%%%PREFIX%%%", $this->_dbop->prefix, $sql_sentences[$i]));
				$this->_dbop->freeResult();
			}
		}
		else
		{
			$results[] = $this->_dbop->query(str_replace("%%%PREFIX%%%", $this->_dbop->prefix, $sql_sentences));
			$this->_dbop->freeResult();
		}

		return $results;
	}

	// 获取旧版本配置信息
	function _loadOldConfig($config_file)
	{
		$result = FALSE;
		$config_context = "";
		$parttern1 = "/\\\$exBlog\['(\w+?)'\] = \"([^\"]*?)\";.*/";
		$parttern2 = "/\\\$version\['(\w+?)'\] = \"([^\"]*?)\";.*/";
		$replace_1 = '$1';
		$replace_2 = '$2';
		if (FALSE == @file_exists($config_file)) $this->errors[] = sprintf($this->_lang['upgrade'][28], $config_file);
		else
		{
			$file_id = @fopen($config_file, "rb");
			for ($i = 0; $i < 7; $i++)
			{
				$tmp = trim(@fgets($file_id, 1024));
				if (1 < $i)
				{
					if (TRUE == preg_match($parttern1, $tmp))
					{
						$key_name = preg_replace($parttern1, $replace_1, $tmp);
						if ("one" != $key_name) $dbconfig[$key_name] = preg_replace($parttern1, $replace_2, $tmp);
						else $dbconfig['prefix'] = preg_replace($parttern1, $replace_2, $tmp);
					}
					elseif (TRUE == preg_match($parttern2, $tmp))
					{
						$key_name = preg_replace($parttern2, $replace_1, $tmp);
						if ("string" == $key_name) $this->old_version['str'] = preg_replace($parttern2, $replace_2, $tmp);
						elseif ("update" == $key_name) $this->old_version['num'] = preg_replace($parttern2, $replace_2, $tmp);
					}
				}
			}
			@fclose($file_id);
			$this->dbconfig = $dbconfig;
			$result = TRUE;
		}
		return $result;
	}

	function _loadOldSystemOptions()
	{
		$query_string = "select * from {$this->_dbop->prefix}global limit 0, 1";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getAffectedRows()) return FALSE;
		else $this->_config['global'] = $this->_dbop->fetchArray($this->_dbop->query_id, MYSQL_ASSOC);
		$this->_dbop->freeResult();
		include($this->config_file);
		if (FALSE == empty($version['update'])) $this->_config['global']['version_number'] = $version['update'];
		return $this->_config['global'];
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
}
?>