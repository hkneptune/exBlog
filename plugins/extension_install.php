<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Extension installation guide for exBlog
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

class ExtensionInstall
{
	/**
	 * 
	 * @brief error messages-bus
	 */
	var $_errors = array();
	/**
	 * 
	 * @brief notice messages-bus
	 */
	var $_notices = array();
	/**
	 * 
	 * @brief connection handler to database
	 */
	var $_dbop = array();
	/**
	 * 
	 * @brief require exBlog version
	 */
	var $_require_version = '';
	/**
	 * 
	 * @brief install configure options
	 */
	var $_install_options = array();
	/**
	 * 
	 * @brief filename of install configure
	 */
	var $_install_options_filename = '';
	/**
	 * 
	 * @brief whether the install options had been loaded
	 */
	var $_install_options_loaded = FALSE;
	/**
	 * 
	 * @brief system configure
	 */
	var $_system_configure = array();
	/**
	 * 
	 * @brief whether the system configure had been loaded
	 */
	var $_system_configure_loaded = FALSE;
	/**
	 * 
	 * @brief SQL sentences for a extension
	 */
	var $sql_sentences = array();

	var $current_step = 1;
	var $max_steps = 2;
	var $_result = array();
	var $_errors_count = 0;

	function ExtensionInstall()
	{
		$this->_getVariables();
		if (TRUE == empty($this->_input['_GET']['name'])) $this->_errors[] = 'No extension had been selected';
		$this->extension_name = $this->_input['_GET']['name'];
		$this->_install_options_filename = "../plugins/{$this->extension_name}/install.ini";

		if (FALSE == count($this->_errors))
		{
			$this->_loadSystemConfigure();
			$this->loadInstallOptions($this->extension_name);
		} 
		if (FALSE == count($this->_errors))
		{
			$this->sqlInit();
			$this->_getSystemSetting();
			$this->_selectLanguages();
			$this->countStep();
		} 

		if (TRUE == count($this->_errors)) $this->outputErrors();
		else
		{
			if (FALSE == empty($this->_input['_POST']['step'])) $this->current_step = intval($this->_input['_POST']['step']) + 1;
			if (FALSE == empty($this->steps[($this->current_step - 1)])) eval($this->steps[$this->current_step - 1]);
		} 

		exit();
	} 

	function outputErrors()
	{
		echo "
<html>
<head>
<title>Error!</title>
<style type=text/css>
body { font-family: Verdana, Arial, tahoma; }
td { font-size: 12px; }
td.title { font-size: 14px; color: #FFFFFF; font-weight:bold; background-color:#000000; height: 25; text-align:center; }
</style>
</head>
<body>
<table width=450 height=300 cellpadding=2 cellspacing=2 border=1 bordercolor=#000000 align=center>
  <tr><td class=title>Error!</td></tr>
  <tr>
	<td bordercolor=#FFFFFF valign=top>
    <table width=90% border=0 align=center cellspacing=4 cellspadding=4>
	  <tr><td width=20% nowrap align=right valign=top>Message: </td><td width=80%><ol type=1>";
		for ($i = 0; $i < count($this->_errors); $i++) echo "<li><font color=#FF0000><b>{$this->_errors[$i]}</b></font>";
		echo "
      </ol></td></tr>
	  <tr><td width=20% nowrap align=right valign=top>TODO: </td><td width=80%>
	   <li><a href=javascript:history.back()>Go back to the previous page</a></li>
	   <li><a href=javascript:window.close()>Close this window</a></li>
      </td></tr>
	</table>
    </td>
  </tr>
</table>
</body>
</html>
		";
		exit();
	} 

	function countStep()
	{
		if (TRUE == intval($this->_install_options['install']['output_licence']) && FALSE == empty($this->_install_options['plugin']['licence']))
		{
			$this->steps[] = '$this->outputPage(TRUE, $this->_lang[\'plugin\'][\'install\'][13]);';
			$this->max_steps++;
		} 
		$this->steps[] = '$this->compareVersion();$this->fileMaintenance($this->_install_options[\'install\'][\'require_file\']);$this->outputPage(FALSE, $this->_lang[\'plugin\'][\'install\'][14]);';
		if (FALSE == empty($this->_install_options['install']['database_layout']))
		{
			$this->steps[] = '$this->sqlMaintenance();$this->outputPage(FALSE, $this->_lang[\'plugin\'][\'install\'][15]);';
			$this->max_steps++;
		} 
		if (FALSE == empty($this->_install_options['install']['file_layout']))
		{
			$this->steps[] = '$this->fileMaintenance($this->_install_options[\'install\'][\'file_layout\']);$this->outputPage(FALSE, $this->_lang[\'plugin\'][\'install\'][16]);';
			$this->max_steps++;
		} 
		$this->steps[] = '$this->saveConfigure();$this->registerExtension();$this->eraseInstallConfig();$this->outputPage(FALSE, $this->_lang[\'plugin\'][\'install\'][17]);';
		return $this->max_steps;
	} 

	function outputPage($is_licence, $page_title)
	{
		$display_version = $this->_splitString($this->_install_options['plugin']['version']);
		$author_info = $this->_splitString($this->_install_options['plugin']['author']);
		echo "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<title>" . sprintf($this->_lang['plugin']['install'][0], $this->_install_options['plugin']['name'] . ' ' . $display_version[0]) . "</title>
<link href=\"../images/style.css\" rel=\"stylesheet\" type=\"text/css\">
<style>
<!--
body { font-size: 12px; }
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
<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}?name={$this->extension_name}&lang={$this->language_package}\">
<input type=\"hidden\" name=\"step\" value=\"{$this->current_step}\">
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td bgcolor=\"#FFFFFF\" class=\"menu\"><div align=\"center\">" . sprintf($this->_lang['plugin']['install'][1], $this->_install_options['plugin']['name'] . ' ' . $display_version[0]) . "</div></td></tr>
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
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr>
		";
		for ($i = 0; $i <= $this->max_steps - 1; $i++) echo "<td width=\"7%\" class=\"" . ((($i + 1) == $this->current_step) ? 'nowt' : 'noww') . "\">" . sprintf($this->_lang['plugin']['install'][2], $i + 1) . "</td>";
		echo "
  </tr>
</table>
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
    <tr>
    <td bgcolor=\"#FFFFFF\" class=\"main\"><br />
        &nbsp; <img src=\"../images/group.gif\" width=\"17\" height=\"14\"> {$this->_lang['plugin']['install'][3]} <font color='#0000FF'>{$author_info[0]}&lt;{$author_info[1]}&gt;</font><br /><br />
		";

		if (TRUE == $is_licence) echo "<table width=80% cellpadding=2 cellspacing=2 align=center border=0><tr><td align=center><textarea rows=17 cols=70 style=\"border: 1 solid #C7C7C7; overflow-y:hidden;\">" . $this->_outputLicence() . "</textarea></td></tr></table><div align=center><input type=\"submit\" value=\"" . $this->_lang['plugin']['install'][4] . "\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp; <input type=\"button\" value=\"{$this->_lang['plugin']['install'][5]}\" onclick=\"window.close()\" class=\"botton\">";
		else $this->_showMesgs($page_title);

		echo "<br />&nbsp; </div></td></tr></table></form></body></html>";
	} 

	function _showMesgs($page_title)
	{
		echo "<p style=\"margin-left: 10px; margin-right: 10px; font-size: 13px; font-weight: bold;\"> $page_title</p>";
		echo "<table width=80% cellpadding=2 cellspacing=2 border=0 align=center>";
		for ($i = 0; $i < count($this->_notices); $i++)
		{
			$style_name = "noticeMesg";
			if (FALSE == $this->_result[$i])
			{
				$this->_errors_count++;
				$style_name = "errorMesg";
			} 
			echo "<tr><td align=left bgcolor=\"#FFFFFF\" class=\"main\">{$this->_notices[$i]}</td><td bgcolor=\"#FFFFFF\" class=\"main\"><span class=\"$style_name\" align=right>" . ((TRUE == $this->_result[$i]) ? $this->_lang['plugin']['install'][11] : $this->_lang['plugin']['install'][12]) . "</span></td>";
		} 
		echo "</table>";
		echo "<div align=center>";
		if (0 < $this->_errors_count) echo sprintf($this->_lang['plugin']['install'][8], $this->_errors_count) . "<br />";
		elseif ($this->current_step == $this->max_steps) echo "<input type=button value=\"{$this->_lang['plugin']['install'][7]}\" class=\"botton\" onClick=\"window.close();\"/>";
		else echo "<input type=submit value=\"{$this->_lang['plugin']['install'][6]}\" class=\"botton\" />";
		echo "<br />&nbsp; </div>";
	} 

	/**
	 * 
	 * @brief Load install options
	 */
	function loadInstallOptions()
	{
		if (FALSE == @file_exists($this->_install_options_filename)) $this->_errors[] = 'Could not found the filename for install configure';
		else
		{
			$this->_install_options = @parse_ini_file($this->_install_options_filename, TRUE);
			if (FALSE == $this->_install_options) $this->_errors[] = 'Could not load install options';
			else $this->_install_options_loaded = TRUE;
		} 
		return $this->_install_options_loaded;
	} 

	/**
	 * 
	 * @brief Load base system configure
	 */
	function _loadSystemConfigure()
	{
		if (FALSE == @file_exists('../include/config.inc.php')) $this->_errors[] = 'Could not found system configure';
		else
		{
			include('../include/config.inc.php');
			$this->_system_configure['global']['version_number'] = $version['update'];
			$this->_system_configure['global']['version_string'] = $version['string'];
			$this->_system_configure['global']['db']['host'] = $exBlog['host'];
			$this->_system_configure['global']['db']['dbname'] = $exBlog['dbname'];
			$this->_system_configure['global']['db']['user'] = $exBlog['user'];
			$this->_system_configure['global']['db']['password'] = $exBlog['password'];
			$this->_system_configure['global']['db']['prefix'] = $exBlog['one'];
			$this->_system_configure['global']['charset'] = $exBlog['charset'];
			$this->_system_configure_loaded = TRUE;
		} 
		return $this->_system_configure_loaded;
	} 

	function _getSystemSetting()
	{
		$query_string = "select * from {$this->_dbop->prefix}global limit 0, 1";
		$this->_dbop->query($query_string);
		$this->_system_configure['global'] = array_merge($this->_system_configure['global'], $this->_dbop->fetchArray(0, 'ASSOC'));
		$this->_dbop->freeResult();
	} 

	/**
	 * 
	 * @brief whether current system version match the version install required
	 * @return return FALSE if not, else TRUE
	 */
	function compareVersion()
	{
		$compare_match = FALSE;
		$this->_notices[] = $this->_lang['plugin']['install'][10]; 
		// parse the require version variable
		if (FALSE == empty($this->_install_options['install']['require_version']))
		{
			$version_required = explode('-', $this->_install_options['install']['require_version']); 
			// Only for a version
			if (1 == count($version_required)) $compare_match = ('et' == $this->_compareExblogVersion($version_required[0], $this->_system_configure['global']['version_number']));
			else
			{
				if (FALSE == empty($version_required[0])) $compare_match = ('et' == $this->_compareExblogVersion($version_required[0], $this->_system_configure['global']['version_number']) || 'lt' == $this->_compareExblogVersion($version_required[0], $this->_system_configure['global']['version_number']));
				if (FALSE == empty($version_required[1])) $compare_match = ('et' == $this->_compareExblogVersion($version_required[0], $this->_system_configure['global']['version_number']) || 'gt' == $this->_compareExblogVersion($version_required[0], $this->_system_configure['global']['version_number']));
			} 
		} 
		else $compare_match = TRUE;
		$this->_result[] = $compare_match;
		return $compare_match;
	} 

	function _compareExblogVersion($ver1, $ver2)
	{
		$ver1_array = array(intval(substr($ver1, 0, 6)), intval(substr($ver1, 6)));
		$ver2_array = array(intval(substr($ver2, 0, 6)), intval(substr($ver2, 6)));
		if ($ver1_array[0] > $ver2_array[0]) return 'gt';
		elseif ($ver1_array[0] == $ver2_array[0])
		{
			if ($ver1_array[1] > $ver2_array[1]) return 'gt';
			elseif ($ver1_array[1] == $ver2_array[1]) return 'et';
			else return 'lt';
		} 
		else return 'lt';
	} 

	/**
	 * 
	 * @brief Initialize connection to database
	 * @return handler to the connection
	 */
	function sqlInit()
	{
		include_once('../include/mysql.php');
		$this->_dbop = new Database();
		$this->_dbop->host = $this->_system_configure['global']['db']['host'];
		$this->_dbop->user = $this->_system_configure['global']['db']['user'];
		$this->_dbop->passwd = $this->_system_configure['global']['db']['password'];
		$this->_dbop->prefix = $this->_system_configure['global']['db']['prefix'];
		$this->_dbop->dbname = $this->_system_configure['global']['db']['dbname'];
		$this->_dbop->connect("", "", "", "", "", "", TRUE);
		$this->_dbop->setCharset($this->_system_configure['global']['charset']);
		return $this->_dbop;
	} 

	/**
	 * 
	 * @brief Execute SQL sentences and fetch result
	 */
	function sqlMaintenance()
	{
		if (FALSE == empty($this->_install_options['install']['database_layout']))
		{
			$this->_sqlParse($this->_install_options['install']['database_layout']);
			for ($i = 0; $i < count($this->sql_sentences); $i++)
			{
				$sql_result = $this->_dbop->query($this->sql_sentences[$i]);
				// $sql_result = TRUE;
				if (TRUE == $sql_result) $this->_result[] = TRUE;
				else
				{
					$this->_result[] = FALSE;
					$this->_errors[] = $this->dbop->getError('message');
				} 
			} 
		} 
	} 

	function eraseInstallConfig()
	{
		$this->_notices[] = $this->_lang['plugin']['install'][26];
		if (TRUE == in_array(FALSE, $this->_result)) $this->_result[] = FALSE;
		else $this->_result[] = @unlink($this->_install_options_filename);
		// else $this->_result[] = FALSE;
	} 

	/**
	 * 
	 * @brief Execute file maintenance tasks and fetch result
	 */
	function fileMaintenance($file_sentence)
	{
		if (FALSE == empty($file_sentence))
		{
			$this->_fileParse($file_sentence);
			/*			for ($i = 0; $i < count($this->file_sentences); $i++)
			{
				$tmp = $this->file_sentences[$i];
				$this->_result[] = eval($tmp);
			}
*/ } 
	} 

	/**
	 * 
	 * @brief Register a extension
	 */
	function registerExtension()
	{
		$this->_notices[] = $this->_lang['plugin']['install'][9];
		if (TRUE == in_array(FALSE, $this->_result)) $this->_result[] = FALSE;
		else
		{ 
			// Whether the extension had been existed?
			$query_string = "select plugin_name from {$this->_dbop->prefix}plugin where plugin_name='{$this->_install_options['plugin']['name']}'";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows())
			{
				$this->_errors[] = sprintf('The extension %s had been existed', $$this->_install_options['plugin']['name']);
				$this->_result[] = FALSE;
			} 
			else
			{
				$query_string = "insert into {$this->_dbop->prefix}plugin (
					plugin_name, plugin_public_url, plugin_manage_url, plugin_manage_level,
					plugin_add_topmenu, plugin_enabled, plugin_install_time, plugin_config_file
				) values (
					'{$this->_install_options['plugin']['name']}', '{$this->_install_options['plugin']['public_url']}', '{$this->_install_options['plugin']['manage_url']}', {$this->_install_options['plugin']['manage_level']},
					'{$this->_install_options['plugin']['add_top_menu']}', '1', '" . time() . "', '{$this->_install_options['plugin']['config_file']}'
				);;insert into {$this->_dbop->prefix}module (module_name) values ('{$this->_install_options['plugin']['module']}')";
				$this->_dbop->query($query_string);
				$this->_result[] = TRUE;
			} 
		} 
		return $this->_result;
	} 

	/**
	 * 
	 * @brief Save configure for a extension
	 */
	function saveConfigure()
	{
		$result = TRUE;
		$this->_notices[] = $this->_lang['plugin']['install'][18];
		if (FALSE == empty($this->_install_options['plugin']['config_file']))/* Do nothing */ ;
		else $this->_install_options['plugin']['config_file'] = "../plugins/{$this->extension_name}/{$this->_install_options['plugin']['name']}.conf";
		$content = "author\t= {$this->_install_options['plugin']['author']}\n"
		 . "licence\t= {$this->_install_options['plugin']['licence']}\n"
		 . "version\t= {$this->_install_options['plugin']['version']}\n'";
		if (FALSE == empty($this->_install_options['configure']))
		{
			foreach ($this->_install_options['configure'] as $key => $value) $content .= "$key\t=\t$value\n";
		} 
		$file_id = fopen($this->_install_options['plugin']['config_file'], 'wb');
		@flock($file_id, LOCK_EX);
		$result = fwrite($file_id, $content);
		@flock($file_id, LOCK_UN);
		@fclose($file_id);
		$this->_result[] = $result;
		return $result;
	} 

	/**
	 * 
	 * @brief Parse SQL sentences and add descriptions for them
	 * @param  $sql_sentence the SQL sentences wanted to be parsed
	 * @return The SQL sentences would be saved in ::$sql_sentences and descriptions would be saved in ::$sql_messages
	 * @notice 
	 * @li sql sentences could be separated by two semicolons(;;)
	 * @li Parser only supports delete/alter/insert/update sentences, other sentences would be erased
	 */
	function _sqlParse($sql_sentence)
	{
		$sql_sentence = str_replace('%%%PREFIX%%%', $this->_system_configure['global']['db']['prefix'], $sql_sentence);
		$sql_sentences = explode("@", $sql_sentence);
		for ($i = 0; $i < count($sql_sentences); $i++)
		{
			if (TRUE == preg_match('/^alter\s+table/i', $sql_sentences[$i]))
			{
				$this->sql_sentences[] = $sql_sentences[$i];
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][27], preg_replace('/^alter\s+table\s+(.*?)\s+/i', '\\1', $sql_sentences[$i]));
			} elseif (TRUE == preg_match('/^create\s+table/i', $sql_sentences[$i]))
			{
				$this->sql_sentences[] = $sql_sentences[$i];
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][28], preg_replace('/^create\s+table\s+(.*?)\s+.*/i', '\\1', $sql_sentences[$i]));
			} elseif (TRUE == preg_match('/^delete/i', $sql_sentences[$i]))
			{
				$this->sql_sentences[] = $sql_sentences[$i];
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][29], preg_replace('/^delete.*?from\s+(.*?)\s*.*/i', '\\1', $sql_sentences[$i]));
			} elseif (TRUE == preg_match('/^insert\s+into/i', $sql_sentences[$i]))
			{
				$this->sql_sentences[] = $sql_sentences[$i];
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][30], preg_replace('/^insert\s+into\s+(.*?)\s+.*/i', '\\1', $sql_sentences[$i]));
			} elseif (TRUE == preg_match('/^update/i', $sql_sentences[$i]))
			{
				$this->sql_sentences[] = $sql_sentences[$i];
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][31], preg_replace('/^update\s+(.*?)\s+set.*/i', '\\1', $sql_sentences[$i]));
			} 
		} 
		return $this->sql_sentences;
	} 

	/**
	 * 
	 * @brief Parse file maintenance sentences and add descriptions for them
	 * @param  $file_sentence the file maintenance sentences wanted to be parsed
	 * @return The file maintenance sentences would be saved in ::$file_sentences and descriptions would be saved in ::$file_messages
	 * @notice 
	 * @li file maintenance sentences could be separated by one semicolons(;)
	 * @li Parser only supports mkdir/rmdir/rmfile/file_exists/not_file_exists/dir_exists/not_dir_exists sentences, other sentences would be erased
	 */
	function _fileParse($file_sentence)
	{
		$file_sentences = explode('@', $file_sentence);
		for ($i = 0; $i < count($file_sentences); $i++)
		{
			if (TRUE == preg_match('/^mkdir:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^mkdir:(.*)/i', '\\1', $file_sentences[$i]);
				$this->_result[] = @mkdir($target, 0700);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][19], $target);
			} elseif (TRUE == preg_match('/^rmdir:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^rmdir:(.*)/i', '\\1', $file_sentences[$i]);
				$this->_result[] = @rmdir($target);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][20], $target);
			} elseif (TRUE == preg_match('/^rmfile:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^rmfile:(.*)/i', '\\1', $file_sentences[$i]);
				$this->_result[] = @unlink($target);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][21], $target);
			} elseif (TRUE == preg_match('/^file_exists:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^file_exists:(.*)/', '\\1', $file_sentences[$i]);
				$this->_result[] = @file_exists($target);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][22], $target);
			} elseif (TRUE == preg_match('/^not_file_exists:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^not_file_exists:(.*)/', '\\1', $file_sentences[$i]);
				$this->_result[] = !@file_exists($target);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][23], $target);
			} elseif (TRUE == preg_match('/^dir_exists:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^dir_exists:(.*)/', '\\1', $file_sentences[$i]);
				$this->_result[] = @is_dir($target);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][24], $target);
			} elseif (TRUE == preg_match('/^not_dir_exists:/i', $file_sentences[$i]))
			{
				$target = preg_replace('/^not_dir_exists:(.*)/', '\\1', $file_sentences[$i]);
				$this->_result[] = !@is_dir($target);
				$this->_notices[] = sprintf($this->_lang['plugin']['install'][25], $target);
			} 
		} 
//		return $this->file_sentences;
	} 

	/**
	 * 
	 * @brief Parse licence configure
	 */
	function _parseLicence()
	{
		$tmp = strtolower($this->_install_options['plugin']['licence']);
		if ('gpl' == $tmp) $this->_install_options['licence']['file'] = '../docs/GPL.txt';
		elseif ('lgpl' == $tmp) $this->_install_options['licence']['file'] = '../docs/LGPL.txt';
		elseif ('mpl' == $tmp) $this->_install_options['licence']['file'] = '../docs/MPL.txt';
		elseif ('asl' == $tmp) $this->_install_options['licence']['file'] = '../docs/ASL.txt';
		elseif ('bsdl' == $tmp) $this->_install_options['licence']['file'] = '../docs/BSDL.txt';
		elseif ('mitl' == $tmp) $this->_install_options['licence']['file'] = '../docs/MITL.txt';
		else $this->_install_options['licence']['file'] = $this->_install_options['plugin']['licence'];
		return $this->_install_options['licence']['file'];
	} 

	function _outputLicence()
	{
		$content = '';
		$this->_parseLicence();
		if (FALSE == empty($this->_install_options['licence']['file']))
		{
			$tmp = @file($this->_install_options['licence']['file']);
			if (TRUE == is_array($tmp)) $content = implode("", $tmp);
		} 
		else $content = $this->_install_options['plugin']['licence'];
		return $content;
	} 

	function _selectLanguages()
	{
		if (FALSE == empty($this->_input['_GET']['lang'])) $this->language_package = $this->_input['_GET']['lang'];
		else $this->language_package = substr(strrchr($this->_system_configure['global']['langURL'], "/"), 1);

		$this->language_packages = $this->_walkDir("../language");
		for ($i = 0; $i < count($this->language_packages); $i++) $tmp[] = basename($this->language_packages[$i]['path']);
		if (FALSE == in_array($this->language_package, $tmp)) $this->language_package = substr(strrchr($this->_system_configure['global']['langURL'], "/"), 1); 
		// Load language packages
		include_once("../language/{$this->language_package}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
		include_once("../language/{$this->language_package}/plugin.install.php");
		$this->_lang['plugin']['install'] = $lang;
	} 

	function _walkDir($dir_path, $filter_pattern = '')
	{
		$dirop = dir($dir_path);
		while (FALSE !== ($entry = $dirop->read()))
		{
			if (substr($entry, 0, 1) != "." && "CVS" != $entry)
			{
				if (TRUE == empty($filter_pattern)) $entries[] = array("path" => $entry, "name" => $this->_formatDirName($entry));
				elseif (TRUE == preg_match($filter_pattern, $entry)) $entries[] = $entry;
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

	function _splitString($str)
	{
		$str_array = array(preg_replace("/(.*?)\s+<.*?>/", "\\1", $str), preg_replace("/.*?\s+<(.*?)>/", "\\1", $str));
		return $str_array;
	}

	/**
	 * 
	 * @brief Get all variables, included COOKIE, SESSION, POST and GET data
	 * @note All variables would be saved in ::$_input order by GPCS ,
	 * @n and every group of variables would be saved into ::$_input using their name as key.
	 */
	function _getVariables()
	{
		$this->_input = array();
		$this->_input['_GET'] = $_GET;
		$this->_input = array_merge($this->_input, $_GET);
		if (TRUE == count($_POST)) $tmp_array = $this->stripSlashesInArray($_POST);
		else $tmp_array = array();
		$this->_input['_POST'] = $tmp_array;
		$this->_input = array_merge($this->_input, $tmp_array);
		if (FALSE == empty($_COOKIE))
		{
			$this->_input['_COOKIE'] = $_COOKIE;
			$this->_input = array_merge($this->_input, $_COOKIE);
		} 
		else $this->_input['_COOKIE'] = array();
		@session_start();
		if (FALSE == empty($_SESSION))
		{
			$this->_input['_SESSION'] = $_SESSION;
			$this->_input = array_merge($this->_input, $_SESSION);
		} 
		else $this->_input['_SESSION'] = array();
		return $this->_input;
	} 

	function stripSlashesInArray($array_one)
	{
		if (TRUE == get_magic_quotes_gpc())
		{
			if (TRUE == count($array_one)) foreach ($array_one as $key => $value)
			{
				if (FALSE == is_array($value)) $array_one[$key] = stripslashes($value);
				else $array_one[$key] = $this->stripSlashesInArray($value);
			} 
		} 
		return $array_one;
	} 
} 

new ExtensionInstall();

?>
