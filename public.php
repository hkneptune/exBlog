<?php
class PublicCommon
{
	var $_top_menu = array();

	function _loadSystemOptions()
	{
		$query_string = "select * from {$this->_dbop->prefix}global limit 0, 1";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getAffectedRows()) return FALSE;
		else $this->_config['global'] = $this->_dbop->fetchArray($this->_dbop->query_id, MYSQL_ASSOC);
		$this->_dbop->freeResult();
		include("./include/config.inc.php");
		if (FALSE == empty($version['update'])) $this->_config['global']['version_number'] = $version['update'];
		// 装入插件配置
		if (TRUE == file_exists("./include/config.plugin.php"))
		{
			include_once("./include/config.plugin.php");
			$this->_config['plugin'] = $plugins;
		}
		else $this->_config['plugin'] = array();
		return $this->_config['global'];
	}

	function _loadDatabaseConfig()
	{
		if (FALSE == @file_exists("./include/config.inc.php")) die("Could not loading configuration!");
		include_once("./include/config.inc.php");
		include_once("./include/mysql.php");
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

	//  获取分类列表
	function _getSorts()
	{
		$query_string = "select * from {$this->_dbop->prefix}sort order by sortOrder, id asc";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $this->sorts = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $this->sorts = array();
		$this->_dbop->freeResult();
		return $this->sorts;
	}

	//  检查BLOG状态
	function _isBlogHang()
	{
		if ('0' == $this->_config['global']['activeRun']) return FALSE;
		return TRUE;
	}

	function _initHtml()
	{
		// Detect all templates
		$templates = $this->_walkDir("./templates", TRUE);
//		array_unshift($templates, array('path' => $this->_config['global']['tmpURL'], 'name' => $this->_formatDirName(basename($this->_config['global']['tmpURL']))));

		// Get path of the current template
		$template_path = $this->_config['global']['tmpURL'];
		$template_name = '';
		if (FALSE == empty($_GET['template'])) $template_name = $this->_formatDirName(urldecode($_GET['template']));
		elseif (FALSE == empty($_COOKIE['template_selected'])) $template_name = $_COOKIE['template_selected'];
		for ($i = 0; $i < count($templates); $i++)
		{
			if ($template_name == $templates[$i]['name'])
			{
				$template_path = "templates/{$templates[$i]['path']}";
				$this->_config['global']['tmpURL'] = $template_path;
				break;
			}
		}

		// Save the selected template
		setcookie ("template_selected", $template_name);

		include_once("./include/Smarty/Smarty.class.php");
		$this->_html = new Smarty();
		$this->_html->template_dir = "./$template_path";
		$this->_html->compile_dir = "./$template_path/compiled";
		$this->_html->config_dir = "./$template_path/config";
		$this->_html->debugging = FALSE;

		// Top menus
		$topmenu_home = (FALSE == preg_match("/play=tag/i", $_SERVER['QUERY_STRING']) && FALSE == preg_match("/play=links/i", $_SERVER['QUERY_STRING']) && FALSE == preg_match("/play=about/i", $_SERVER['QUERY_STRING']));
		$this->_addTopMenu($this->_lang['public'][66], 'index.php?mode=normal', $topmenu_home);
		$this->_addTopMenu($this->_lang['public'][70], 'index.php?play=tag', preg_match("/play=tag/i", $_SERVER['QUERY_STRING']));
		$this->_addTopMenu($this->_lang['public'][67], 'index.php?play=links', preg_match("/play=links/i", $_SERVER['QUERY_STRING']));

		// 插件列表
		for ($i = 0; $i < count($this->_config['plugin']); $i++)
		{
			if (FALSE == empty($this->_config['plugin'][$i]['add_topmenu'])) $this->_addTopMenu($this->_config['plugin'][$i]['text'], "plugin/{$this->_config['plugin'][$i]['url']}", preg_match("/" . preg_quote("plugin/{$this->_config['plugin'][$i]['url']}", "/") . "/i", $_SERVER['PHP_SELF']));
		}

		$this->_addTopMenu($this->_lang['public'][68], 'index.php?play=about', preg_match("/play=about/i", $_SERVER['QUERY_STRING']));
		$this->_addTopMenu($this->_lang['public'][69], 'admin/login.php', FALSE);

		$this->_getStatInfo();

		$this->_html->assign(
			array(
				"charset"	=> $this->_lang['public']['charset'],
				"language"	=> $this->_lang['public']['language'],
				"text_direct"	=> $this->_lang['public']['dir'],
				"top_menus"	=> $this->_top_menu,
				"PHP_CONFIG"	=> $this->_config,
				"start_time"	=> $this->start_time,
				"PHP_LANG"		=> $this->_lang,
				"PHP_HAD_LOGIN"	=> isset($_SESSION['userID']),
				"templates"	=> $templates
			)
		);
		if (FALSE == $this->_isBlogHang())
		{
			$this->errors[] = (FALSE == empty($this->_config['global']['unactiveRunMessage']) ? $this->_config['global']['unactiveRunMessage'] : $this->_lang['public'][29]);
			$this->_html->assign("messages", $this->errors);
			$this->_html->display("errors.tpl");
			exit();
		}

		@session_start();
		if (FALSE == empty($_SESSION['userID']))
		{
			$user_info = array(
				"id"	=> $_SESSION['userID'],
				"emai"	=> $_SESSION['exUseremail'],
				"level"	=> $_SESSION['userLevel'],
				"name"	=> $_SESSION['exPass']
			);
			$this->_html->assign("user_info", $user_info);
		}

		// 左侧边栏
		$comments = $this->_getLatestRecords("comment", 10, "id desc");
		for ($i = 0; $i < count($comments); $i++)
		{
			$comments[$i]['summycontent'] = $this->_substr($comments[$i]['content'], 0, $this->_config['global']['alltitlenum']);
		}
		$this->_html->assign(
			array(
				"columns_list"	=> $this->_getSorts(),
				"last_articles_list"	=> $this->_getLastestArticles(10),
				"last_comments_list"	=> $comments,
				"last_links_list"	=> $this->_getLatestRecords("links", 10, "linkOrder, id desc", "visible='1'"),
				"announces_list"	=> $this->_getLatestRecords("announce", 5, "id desc"),
				"current_time"	=> $this->current_time,
				"calendar_title"	=> sprintf($this->_lang['public'][75], date("Y", $this->current_time), date("m", $this->current_time)),
				"calendar_weekdays"	=> $this->_lang['public'][76],
				"calendar_days"	=> $this->_getCalendar(date("Y", $this->current_time), date("m", $this->current_time)),
				"pigeonholes_list"	=> $this->_getPigeonhole(date("Y", $this->current_time)),
				"nearby_dates"	=> array(
					"yearup" => date("Y", $this->current_time) - 1,
					"yeardown"	=> date("Y", $this->current_time) + 1,
					"monthup"	=> date("m", $this->current_time) - 1,
					"monthdown"	=> date("m", $this->current_time) + 1
				)
			)
		);
	}

	function _addTopMenu($menu_text, $menu_url = '', $selected = FALSE)
	{
		if (FALSE == empty($menu_url)) $this->_top_menu[] = array("text" => $menu_text, "url" => $menu_url, "selected" => $selected);
	}

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

	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function _getCalendar($year, $month)
	{
		$total_days = date("t", mktime(0, 0, 0, $month, 1, $year));
		$start_weekday = date("w", mktime(0, 0, 0, $month, 1, $year));
		$blog_dates = $this->_getBlogDate(mktime(0,0,0, $month, 1, $year), mktime(0,0,0, $month + 1, 1, $year));
		$tmp = $start_weekday + $total_days;
		if ($tmp % 7 != 0) $total_count = $tmp + 7 - $tmp % 7;
		else $total_count = $tmp;
		for ($i  = 0; $i < $total_count; $i++)
		{
			$tmp_1 = $i - $start_weekday + 1;
			if ($i < $start_weekday) $calendar_day[$i] = array("text" => "&nbsp;", "url" => '', 'today' => FALSE);
			elseif ($i >= $tmp) $calendar_day[$i] = array("text" => "&nbsp;", "url" => '', 'today' => FALSE);
			else
			{
				if ($tmp_1 == date("d", $this->current_time)) $calendar_day[$i] = array("text" => $tmp_1, 'today' => TRUE);
				else $calendar_day[$i] = array("text" => $tmp_1, 'today' => FALSE);
				if (TRUE == $this->_countBlogsByDate($blog_dates, mktime(0,0,0, $month, $tmp_1, $year), mktime(0,0,0, $month, $tmp_1 + 1, $year))) $calendar_day[$i]['url'] = "{$year}-{$month}-{$tmp_1}";
				else $calendar_day[$i]['url'] = '';
			}
		}
		return $calendar_day;
	}

	function _getPigeonhole($year)
	{
		$pigeonhole = array();
		$start_time = mktime(0, 0, 0, 1, 1, $year);
		$blog_dates = $this->_getBlogDate($start_time, time());
		for ($i = 0; $i < date('m'); $i++)
		{
			$end_time = $start_time + 3600 * 24 * date("t", $start_time);
			if (TRUE == $this->_countBlogsByDate($blog_dates, $start_time, $end_time))
				$pigeonhole[] = array(
					"text" => sprintf("%s年 %s月", date('Y', $start_time), date('m', $start_time)),
					"url"	=> sprintf("%s-%s", date('Y', $start_time), date('m', $start_time))
				);
			$start_time = $end_time;
		}
		return $pigeonhole;
	}

	function _getBlogDate($start_time, $end_time)
	{
		return $this->_getLatestRecords("blog", 0, "addtime asc", "addtime BETWEEN $start_time AND $end_time", "addtime");
	}

	function _getLatestRecords($table_name, $num = 0, $order_fields = "", $conditions = "", $fields_list = "*", $offset = 0, $return_only_first = FALSE)
	{
		$latest_records = array();
		$query_string = "select $fields_list from {$this->_dbop->prefix}$table_name";
		if (FALSE == empty($conditions)) $query_string .= " where $conditions";
		if (FALSE == empty($order_fields)) $query_string .= " order by $order_fields";
		if (FALSE == empty($num)) $query_string .=" limit $offset, $num";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows())
		{
			if (FALSE == $return_only_first) $latest_records = $this->_dbop->fetchArrayBat(0, 'ASSOC');
			else $latest_records = $this->_dbop->fetchArray(0, 'ASSOC');
		}
		$this->_dbop->freeResult();
		return $latest_records;
	}

	function _countBlogsByDate($blog_dates, $start_time, $end_time)
	{
		for ($i = 0; $i < count($blog_dates); $i++)
		{
			if ($start_time <= intval($blog_dates[$i]['addtime']) && $end_time >= intval($blog_dates[$i]['addtime'])) return TRUE;
		}
		return FALSE;
	}

	function _counter()
	{
		$current_time = time();
		$start_time = mktime(0, 0, 0, date("m", $current_time), date("d", $current_time), date("Y", $current_time));
		$query_string = "select * from {$this->_dbop->prefix}visits where currentDate='$start_time'";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getNumRows())
		{
			$this->_dbop->freeResult();
			$query_string = "select visits from {$this->_dbop->prefix}visits order by currentDate desc limit 0, 1";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getNumRows()) $total_visits = 1;
			else
			{
				$tmp = $this->_dbop->fetchArray(0, 'ASSOC');
				$total_visits = $tmp['visits'] + 1;
			}
			$this->_dbop->freeResult();
			$query_string = "insert into {$this->_dbop->prefix}visits (visits, currentDate, todayVisits) values ($total_visits, '$start_time', 1)";
		}
		else $query_string = "update {$this->_dbop->prefix}visits set visits=visits+1, todayVisits=todayVisits+1 where currentDate='$start_time'";
		$this->_dbop->query($query_string);
		$this->_dbop->freeResult();

		// 在线人数统计
		if ('1' == $this->_config['global']['isCountOnlineUser'])
		{
			$query_string = "select lastLoginTime from {$this->_dbop->prefix}online where ip='" . $this->_getClientIp() . "'";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getNumRows()) $query_string = "insert into {$this->_dbop->prefix}online (ip, lastLoginTime) values ('" . $this->_getClientIp() . "', '" . time() . "')";
			else $query_string = "update {$this->_dbop->prefix}online set lastLoginTime='" . time() . "' where ip='" . $this->_getClientIp() . "'";
			$this->_dbop->freeResult();
			$query_string .= ";;delete from {$this->_dbop->prefix}online where lastLoginTime < (" . time() . "-{$this->timeout})";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
		}

		return TRUE;
	}

	function _getClientIp()
	{
		if (FALSE == empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
		elseif (FALSE == empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
		else return $_SERVER['REMOTE_ADDR'];
	}

	function _getCurrentTime()
	{
		$this->current_time = time();
		$count_date_part = 0;

		if (FALSE == empty($_GET['date']))
		{
			$current_date = explode("-", $_GET['date']);
			$count_date_part = count($current_date);
			if (0 < $count_date_part)
			{
				if (2 < $count_date_part) $this->current_time = mktime(0, 0, 0, intval($current_date[1]), intval($current_date[2]), intval($current_date[0]));
				elseif (1 < $count_date_part) $this->current_time = mktime(0, 0, 0, intval($current_date[1]), 1, intval($current_date[0]));
				else $this->current_time = mktime(0, 0, 0, 1, 1, intval($current_date[0]));
			}
		}
		return $count_date_part;
	}

	function _getLastestArticles($num, $field_names = '*')
	{
		$articles = $this->_getLatestRecords("blog", $num, "id desc", "", $field_names);
		for ($i = 0; $i < count($articles); $i++) $articles[$i]['summytitle'] = $this->_substr($articles[$i]['title'], 0, $this->_config['global']['alltitlenum']);
		return $articles;
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