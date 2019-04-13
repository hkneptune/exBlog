<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Public Library for Extensions of exBlog

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

define('ROOT_PATH', '../../');
/**
 * 
 * @class ExtensionLib extension.php "/include/extension.php"
 * @brief Public library for all extensions of exblog
 * @author $Author$ 
 * @Date : $Date$
 * 
 * $Id$
 */
class ExtensionLib
{
	/**
	 * 
	 * @brief Error message-bus
	 */
	var $_errors = array();
	/**
	 * 
	 * @brief HTML handler
	 */
	var $_html = 0;
	/**
	 * 
	 * @brief Database handler
	 */
	var $_dbop = 0;
	/**
	 * 
	 * @brief All configure.
	 * @note All elements:
	 * @li global Core configure
	 * @li plugin plugin configure
	 */
	var $_config = array();
	/**
	 * 
	 * @brief All input data
	 * @see _getVariables
	 */
	var $_input = array();

	/**
	 * 
	 * @brief get configure about the core
	 */
	function getSystemConfig()
	{ 
		// Load configure saved in the database
		if (FALSE == is_resource($this->_dbop)) $this->_initDatabaseConnection();
		$query_string = "select * from {$this->_dbop->prefix}global limit 0, 1";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getNumRows()) $this->_errors[] = 'Could not get configure about the core';
		else $this->_config['global'] = $this->_dbop->fetchArray(0, 'ASSOC');
		$this->_dbop->freeResult();
		/*
		The Version Number must be saved into the database.
		// Load version number saved in configure file
		*/
		include(ROOT_PATH . 'include/config.inc.php');
		if (FALSE == empty($version['update'])) $this->_config['global']['version_number'] = $version['update'];
		return $this->_config['global'];
	} 

	/**
	 * 
	 * @brief Init connection to a database
	 */
	function _initDatabaseConnection()
	{
		include(ROOT_PATH . "include/config.inc.php");
		include_once(ROOT_PATH . "include/mysql.php");
		$this->_dbop = new Database();
		$this->_dbop->host = $exBlog['host'];
		$this->_dbop->user = $exBlog['user'];
		$this->_dbop->passwd = $exBlog['password'];
		$this->_dbop->prefix = $exBlog['one'];
		$this->_dbop->dbname = $exBlog['dbname'];
		$this->_dbop->connect("", "", "", "", "", "", TRUE);
		$this->_dbop->setCharset($exBlog['charset']);
		return $this->_dbop;
	} 

	/**
	 * 
	 * @brief load a language file
	 * @param  $language_name language filename
	 * @note The selected language would be saved into global variable $this->_lang.
	 * @n If $language_name like as name.subname, it would be saved in $this->_lang[name][subname], else $this->_lang[$language_name]
	 */
	function _loadLanguage($language_name)
	{
		if (TRUE == empty($this->_lang['public']))
		{
			include_once(ROOT_PATH . "{$this->_config['global']['langURL']}/public.php");
			$this->_lang['public'] = $langpublic;
			$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
		} 
		include_once("../../{$this->_config['global']['langURL']}/{$language_name}.php");
		$tmp = explode(".", $language_name);
		if (1 < count($tmp)) $this->_lang[$tmp[0]][$tmp[1]] = $lang;
		else $this->_lang[$tmp[0]] = $lang;
		return $this->_lang;
	} 

	/**
	 * 
	 * @brief init HTML handler
	 * @param  $template_path path of templates
	 * @param  $other_param the additional parameter to added into template variables
	 */
	function _initHtml($template_path, $other_param = '')
	{
		require_once(ROOT_PATH . 'include/Smarty/Smarty.class.php');
		$this->_html = new Smarty();
		$this->_html->template_dir = $template_path;
		$this->_html->compile_dir = "{$template_path}/compiled";
		$this->_html->config_dir = "{$template_path}/config";
		$this->_html->debugging = FALSE;
		$this->_html->assign("PHP_CONFIG", $this->_config['global']);
		$this->_html->assign("PHP_LANG", $this->_lang);
		if (FALSE == empty($other_param) && TRUE == is_array($other_param))
		{
			foreach ($other_param as $key => $value) $this->_html->assign($key, $value);
		} 
		return $this->_html;
	} 

	/**
	 * 
	 * @brief Load configure about a extension
	 * @param  $extension_name Name of a extension
	 * @return If successful, the configure would be return, else return FALSE
	 */
	function loadConfigForExtension($extension_name)
	{
		$query_string = "select * from {$this->_dbop->prefix}plugin where plugin_name='$extension_name'";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getNumRows()) $this->_errors[] = 'Could not load configure about the selected extension';
		else $this->_config['plugin'][$extension_name] = $this->_dbop->fetchArray(0, 'ASSOC');
		$this->_dbop->freeResult();
		if (TRUE == isset($this->_config['plugin'][$extension_name])) return $this->_config['plugin'][$extension_name];
		else return FALSE;
	} 

	/**
	 * 
	 * @brief Load setting about a extension from a configure file
	 * @param  $extension_name name of a extension
	 * @return NULL 
	 */
	function loadSettingForExtension($extension_name)
	{
		if ('.' == substr($this->_config['plugin'][$extension_name]['plugin_config_file'], 0, 1)) $this->_config['plugin'][$extension_name]['plugin_config_file'] = '../' . $this->_config['plugin'][$extension_name]['plugin_config_file'];
		if (FALSE == @file_exists($this->_config['plugin'][$extension_name]['plugin_config_file'])) $this->_errors[] = 'Could not load setting about the selected extension';
		else $this->_config['plugin'][$extension_name] = array_merge($this->_config['plugin'][$extension_name], parse_ini_file($this->_config['plugin'][$extension_name]['plugin_config_file']));
	} 

	/**
	 * 
	 * @brief Detect whether a user could use this function
	 * @param  $user_level Maximum level of users to use this function. The users whose level were less than this level could use.
	 * @return If the user could use, return TRUE, else return FALSE
	 */
	function checkUser($user_level)
	{
		session_cache_limiter('private, must-revalidate');

		@session_start(); 
		// Unlogin?
		if (FALSE == session_is_registered("exPass") || FALSE == session_is_registered("userID") || FALSE == session_is_registered("exPassword")) return FALSE; 
		// User's authority
		$query_string = "select id from {$this->_dbop->prefix}admin where id={$_SESSION['userID']} and uid<=$user_level";

		$this->_dbop->query($query_string);

		if (FALSE == $this->_dbop->getAffectedRows()) return FALSE;

		else return TRUE;
	} 

	/**
	 * 
	 * @brief traversal a directory
	 * @param  $dir_path The directory to traversal
	 * @param  $only_dir Whether get all sub-directories only, default was yes@n TRUE = yes, FALSE = no
	 * @return a list of all files and directories, except current directory(.), parent directory(..) and hidden files or directories,  if successful, else return a empty list.
	 * @n The list as below: array( array('path' => '/path/to/file1', 'name' => 'Name_Of_File1'), array('path' => '/path/to/file2', 'name' => 'Name_Of_File2'), ...)
	 */
	function _walkDir($dir_path, $only_dir = TRUE)
	{
		$entries = array(); 
		// Whether was the directory existed, and was a real directory?
		if (false == @file_exists($dir_path) || false == @is_dir($dir_path)) return $entries;
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

	/**
	 * 
	 * @brief Set module name for ACL driver
	 * @param  $module_name Module name. It would be same as register in table module during install this extension
	 */
	function setModuleName($module_name)
	{
		$this->module_name = $module_name;
	} 

	/**
	 * 
	 * @brief Show error messages
	 * @param  $errors A list of error messages. It could be a string or a array
	 * @param  $return_url Where to go after ouput error messages
	 * @note The script would be terminated after call this method!! All codes below the call method could not be executed.
	 */
	function _showError($errors = "", $return_url = "")
	{
		if (TRUE == empty($errors)) $errors = $this->_errors;

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
		} elseif (TRUE == is_array($errors)) echo "<font color=red>{$errors[0]}</font>";

		else echo "<font color=red>$errors</font>";

		echo "<p align=left><a href=\"javascript:history.go(-1);\">{$this->_lang['public'][12]}</a></p></td></tr></table></div></body></html>";

		exit();
	} 

	/**
	 * 
	 * @brief A group of codes required during initalizing
	 * @note Two Constants were used in it, so you must define them before call this method
	 * @li PLUGIN_LANGUAGE The language package for this extension
	 * @li PLUGIN_NAME Plugin name.same as register in table module
	 */
	function initRequired($config_file, $plugin_name, $plugin_language)
	{
		$this->_initDatabaseConnection();
		$this->_getVariables();
		$this->getSystemConfig();
		$this->_loadLanguage($plugin_language);
		$this->loadConfigForExtension($plugin_name);
		$this->loadSettingForExtension($plugin_name);

		if (TRUE == count($this->_errors)) $this->_showError($this->_errors); // If error during load configure
		elseif (FALSE == $this->_isBlogHang()) // Is the blog had been hang?
			{
				$this->_errors[] = (FALSE == empty($this->_config['global']['unactiveRunMessage']) ? $this->_config['global']['unactiveRunMessage'] : $this->_lang['public'][29]);
			$this->_showError($this->_errors);
		} elseif ('1' != $this->_config['plugin'][PLUGIN_NAME]['plugin_enabled'])
		{
			$this->_errors[] = $this->_lang['public'][84];
			$this->_showError($this->_errors);
		} 
		else // ACL rules apply
			{
				include_once(ROOT_PATH . 'include/acl.php');

			$acl_handler = new Acl($this->_errors, $this->module_name, settype($this->_config['global']['ACLSwitch'], "boolean"));
			$acl_handler->_dbop = &$this->_dbop;

			if (FALSE == $acl_handler->handleAcl())
			{
				$this->_errors[] = $this->_lang['public'][82];
				$this->_showError($this->_errors);
			} 

			unset($acl_handler);
		} 
		// authority
		if (FALSE == $this->checkUser($this->_config['plugin'][PLUGIN_NAME]['plugin_manage_level']))
		{
			$this->_errors[] = $this->_lang['public'][21];
			$this->_showError($this->_errors, ROOT_PATH . "admin/login.php");
		} 
	} 

	/**
	 * 
	 * @brief More heavy for initalizing
	 * @note This method was heavy version for initalizing whole extension library, You must call initRequired() before calling it.
	 */
	function heavyerInit()
	{ 
		// Get templates list
		$templates = $this->_walkDir(ROOT_PATH . "templates", TRUE); 
		// Get path of the current template
		$template_path = $this->_config['global']['tmpURL'];
		$template_name = '';
		if (FALSE == empty($this->_input['_GET']['template'])) $template_name = $this->_formatDirName(urldecode($this->input['_GET']['template']));
		elseif (FALSE == empty($this->_input['_COOKIE']['template_selected'])) $template_name = $this->_input['_COOKIE']['template_selected'];
		else $template_name = $this->_formatDirName(basename($template_path));
		for ($i = 0; $i < count($templates); $i++)
		{
			if ($template_name == $templates[$i]['name'])
			{
				$template_path = "templates/{$templates[$i]['path']}";
				$this->_config['global']['tmpURL'] = $template_path;
				break;
			} 
		} 
		$this->_config['global']['tmpName'] = $template_name; 
		// Save the selected template
		setcookie("template_selected", $template_name); 
		// Top menus
		$topmenu_home = (FALSE == preg_match("/play=tag/i", $_SERVER['QUERY_STRING']) && FALSE == preg_match("/play=links/i", $_SERVER['QUERY_STRING']) && FALSE == preg_match("/play=about/i", $_SERVER['QUERY_STRING']));
		$topmenus[] = $this->_addTopMenu($this->_lang['public'][66], ROOT_PATH . 'index.php?mode=normal', $topmenu_home);
		$topmenus[] = $this->_addTopMenu($this->_lang['public'][70], ROOT_PATH . 'index.php?play=tag', preg_match("/play=tag/i", $_SERVER['QUERY_STRING']));
		$topmenus[] = $this->_addTopMenu($this->_lang['public'][67], ROOT_PATH . 'index.php?play=links', preg_match("/play=links/i", $_SERVER['QUERY_STRING'])); 
		// list of extensions
		for ($i = 0; $i < count($this->_config['plugin']); $i++)
		{
			if ('1' == $this->_config['plugin'][$i]['plugin_add_topmenu'] && '1' == $this->_config['plugin'][$i]['plugin_enabled'])
				$topmenus[] = $this->_addTopMenu($this->_config['plugin'][$i]['plugin_name'],
					ROOT_PATH . "plugins{$this->_config['plugin'][$i]['plugin_public_url']}",
					preg_match("/" . preg_quote("plugins{$this->_config['plugin'][$i]['plugin_public_url']}", "/") . "/i", $_SERVER['PHP_SELF'])
					);
		} 
		$topmenus[] = $this->_addTopMenu($this->_lang['public'][68], ROOT_PATH . 'index.php?play=about', preg_match("/play=about/i", $_SERVER['QUERY_STRING']));
		$topmenus[] = $this->_addTopMenu($this->_lang['public'][69], ROOT_PATH . 'admin/login.php', false);

		$this->_getStatInfo(); 
		// list of comments
		$comments = $this->_getLatestRecords("comment", 10, "id desc");
		for ($i = 0; $i < count($comments); $i++) $comments[$i]['summycontent'] = $this->_substr($comments[$i]['content'], 0, $this->_config['global']['alltitlenum']);

		$current_time = time(); 
		// Initalizing HTML handler
		$other_param = array('top_menus' => $topmenus,
			'PHP_HAD_LOGIN' => isset($this->_input['_SESSION']['userID']),
			'templates' => $templates,
			'columns_list' => $this->_getSorts(),
			'last_articles_list' => $this->_getLastestArticles(10),
			'last_comments_list' => $comments,
			'last_links_list' => $this->_getLatestRecords("links", 10, "linkOrder, id desc", "visible='1'"),
			'last_trackbacks_list' => $this->_getLatestRecords("trackback", 10, "trackback_id desc"),
			'announces_list' => $this->_getLatestRecords("announce", 5, "id desc"),
			'current_time' => $current_time,
			'calendar_title' => sprintf($this->_lang['public'][75], date("Y", $current_time), date("m", $current_time)),
			'calendar_weekdays' => $this->_lang['public'][76],
			'calendar_days' => $this->_getCalendar(date("Y", $current_time), date("m", $current_time)),
			'pigeonholes_list' => $this->_getPigeonhole(date("Y", $current_time)),
			'nearby_dates' => array('yearup' => date("Y", $current_time) - 1,
				'yeardown' => date("Y", $current_time) + 1,
				'monthup' => date("m", $current_time) - 1,
				'monthdown' => date("m", $current_time) + 1
				)
			);

		if (FALSE == empty($this->_input['_SESSION']['userID']))
			$other_param['user_info'] = array("id" => $this->_input['_SESSION']['userID'],
				"emai" => $this->_input['_SESSION']['exUseremail'],
				"level" => $this->_input['_SESSION']['userLevel'],
				"name" => $this->_input['_SESSION']['exPass']
				);

		$this->_initHtml(ROOT_PATH . $this->_config['global']['tmpURL'], $other_param);
	} 

	/**
	 * 
	 * @brief Format name of a directory
	 * @param  $dir_name Real name of a directory
	 * @return a formatted name of a directory
	 */
	function _formatDirName($dir_name)
	{
		$search_pattern = array("/_/");
		$replace_pattern = array(" ");
		return preg_replace($search_pattern, $replace_pattern, $dir_name);
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
		if (TRUE == get_magic_quotes_gpc())
		{
			if (TRUE == count($_POST)) foreach ($_POST as $key => $value) $this->_input['_POST'][$key] = stripslashes($value);
		} 
		else $this->_input['_POST'] = $_POST;
		$this->_input = array_merge($this->_input, $_POST);
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

	/**
	 * 
	 * @brief Add a item into top menu bar
	 * @param  $menu_text Description of a menu, show on the menu
	 * @param  $menu_url Link of a menu
	 * @param  $selected Whether the menu had been selected
	 */
	function _addTopMenu($menu_text, $menu_url = '', $selected = FALSE)
	{
		if (FALSE == empty($menu_url)) $top_menu = array("text" => $menu_text, "url" => $menu_url, "selected" => $selected);
		return $top_menu;
	} 

	/**
	 * 
	 * @brief Get stat information about this system
	 */
	function _getStatInfo()
	{
		$query_string = "select * from {$this->_dbop->prefix}visits order by currentDate desc limit 0, 1";

		$this->_dbop->query($query_string);

		if (FALSE == $this->_dbop->getNumRows())
		{
			$this->_config['global']['visits'] = 0;

			$this->_config['global']['todayVisits'] = 0;
		} 

		else
		{
			$tmp = $this->_dbop->fetchArray(0, 'ASSOC');

			$this->_config['global']['visits'] = $tmp['visits'];

			$this->_config['global']['todayVisits'] = $tmp['todayVisits'];
		} 

		$this->_dbop->freeResult();

		if ('1' == $this->_config['global']['isCountOnlineUser'])
		{
			$query_string = "select count(*) as online_count from {$this->_dbop->prefix}online";

			$this->_dbop->query($query_string);

			$tmp = $this->_dbop->fetchArray(0, 'ASSOC');

			$this->_dbop->freeResult();

			$this->_config['global']['online'] = $tmp['online_count'];
		} 

		else $this->_config['global']['online'] = 0;
	} 

	/**
	 * 
	 * @brief Get a list of all sorts
	 * @return The list
	 * @note The list woule be saved into ::$sorts too.
	 */
	function _getSorts()
	{
		$query_string = "select * from {$this->_dbop->prefix}sort order by sortOrder, id asc";

		$this->_dbop->query($query_string);

		if (TRUE == $this->_dbop->getNumRows()) $this->sorts = $this->_dbop->fetchArrayBat(0, 'ASSOC');

		else $this->sorts = array();

		$this->_dbop->freeResult();

		return $this->sorts;
	} 

	/**
	 * 
	 * @brief Get last x articles
	 * @param  $num How many articles would be fetched
	 * @param  $field_names which fields would be return. default was all fields
	 * @return a list of articles
	 */
	function _getLastestArticles($num, $field_names = '*')
	{
		$articles = $this->_getLatestRecords("blog", $num, "id desc", "", $field_names);

		for ($i = 0; $i < count($articles); $i++) $articles[$i]['summytitle'] = $this->_substr($articles[$i]['title'], 0, $this->_config['global']['alltitlenum']);

		return $articles;
	} 

	/**
	 * 
	 * @brief Get last x records from a select table
	 * @param  $table_name Name of table
	 * @param  $num How many records would be fetched
	 * @param  $order_fields field order for sort records, include sort order. example: id desc or id desc, name asc, like ORDER in mysql
	 * @param  $conditions condition for fetching records. all records must be matched. like WHERE in mysql
	 * @param  $fields_list which fields would be return. default was all fields
	 * @param  $offset offset number for starting fetching records.
	 * @param  $return_only_first Whether return one record only.
	 * @n TRUE = yes, FALSE = no. Default was no
	 * @n If it was TRUE, a array which using field names as key would be return,
	 * @n else a array which every element was a record in would be return
	 */
	function _getLatestRecords($table_name, $num = 0, $order_fields = "", $conditions = "", $fields_list = "*", $offset = 0, $return_only_first = FALSE)
	{
		$latest_records = array();

		$query_string = "select $fields_list from {$this->_dbop->prefix}$table_name";

		if (false == empty($conditions)) $query_string .= " where $conditions";

		if (false == empty($order_fields)) $query_string .= " order by $order_fields";

		if (false == empty($num)) $query_string .= " limit $offset, $num";

		$this->_dbop->query($query_string);

		if (true == $this->_dbop->getNumRows())
		{
			if (FALSE == $return_only_first) $latest_records = $this->_dbop->fetchArrayBat(0, 'ASSOC');

			else $latest_records = $this->_dbop->fetchArray(0, 'ASSOC');
		} 

		$this->_dbop->freeResult();

		return $latest_records;
	} 

	/**
	 * 
	 * @brief Calendar
	 * @param  $year year
	 * @param  $month month
	 */
	function _getCalendar($year, $month)
	{
		$total_days = date("t", mktime(0, 0, 0, $month, 1, $year));

		$start_weekday = date("w", mktime(0, 0, 0, $month, 1, $year));

		$blog_dates = $this->_getBlogDate(mktime(0, 0, 0, $month, 1, $year), mktime(0, 0, 0, $month + 1, 1, $year));

		$tmp = $start_weekday + $total_days;

		if ($tmp % 7 != 0) $total_count = $tmp + 7 - $tmp % 7;

		else $total_count = $tmp;

		for ($i = 0; $i < $total_count; $i++)
		{
			$tmp_1 = $i - $start_weekday + 1;

			if ($i < $start_weekday) $calendar_day[$i] = array("text" => "&nbsp;", "url" => '', 'today' => FALSE);

			elseif ($i >= $tmp) $calendar_day[$i] = array("text" => "&nbsp;", "url" => '', 'today' => FALSE);

			else
			{
				if ($tmp_1 == date("d", $this->current_time)) $calendar_day[$i] = array("text" => $tmp_1, 'today' => TRUE);

				else $calendar_day[$i] = array("text" => $tmp_1, 'today' => FALSE);

				if (TRUE == $this->_countBlogsByDate($blog_dates, mktime(0, 0, 0, $month, $tmp_1, $year), mktime(0, 0, 0, $month, $tmp_1 + 1, $year))) $calendar_day[$i]['url'] = "{$year}-{$month}-{$tmp_1}";

				else $calendar_day[$i]['url'] = '';
			} 
		} 

		return $calendar_day;
	} 

	/**
	 * 
	 * @brief Get a list of archives' date
	 * @param  $year year
	 */
	function _getPigeonhole($year)
	{
		$pigeonhole = array();

		$start_time = mktime(0, 0, 0, 1, 1, $year);

		$blog_dates = $this->_getBlogDate($start_time, time());

		for ($i = 0; $i < date('m'); $i++)
		{
			$end_time = $start_time + 3600 * 24 * date("t", $start_time);

			if (true == $this->_countBlogsByDate($blog_dates, $start_time, $end_time))

				$pigeonhole[] = array("text" => sprintf($this->_lang['public'][83], date('Y', $start_time), date('m', $start_time)),

					"url" => sprintf("%s-%s", date('Y', $start_time), date('m', $start_time))

					);

			$start_time = $end_time;
		} 

		return $pigeonhole;
	} 

	/**
	 * 
	 * @brief Get a list of articles in a period of time
	 * @param  $start_time Start time
	 * @param  $end_time End time
	 */
	function _getBlogDate($start_time, $end_time)
	{
		return $this->_getLatestRecords("blog", 0, "addtime asc", "addtime BETWEEN $start_time AND $end_time", "addtime");
	} 

	/**
	 * 
	 * @brief Get number of articles in a period of time
	 * @param  $blog_dates A list of date for articles
	 * @param  $start_time Start time
	 * @param  $end_time End time
	 * @return TRUE if number of articles was great than 0, else return FALSE
	 */
	function _countBlogsByDate($blog_dates, $start_time, $end_time)
	{
		for ($i = 0; $i < count($blog_dates); $i++)
		{
			if ($start_time <= intval($blog_dates[$i]['addtime']) && $end_time >= intval($blog_dates[$i]['addtime'])) return TRUE;
		} 

		return FALSE;
	} 

	/**
	 * 
	 * @brief Detect whether the blog is hang
	 * @return Return TRUE if hang, else return FALSE
	 */
	function _isBlogHang()
	{
		if ('0' == $this->_config['global']['activeRun']) return false;

		return true;
	} 
} 

?>
