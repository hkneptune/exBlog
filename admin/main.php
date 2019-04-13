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
 *  File: main.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-11 15:02
 *  Homepage: www.exblog.net
 *
 * $Id: main.php,v 1.1.1.1 2005/07/01 09:21:20 feeling Exp $
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once("./public.php");

class AdminHome extends CommonLib
{
	function AdminHome()
	{
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig();
		// 装入系统配置
		$this->_loadSystemOptions();
		// 装入语言文件
		include_once("../{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
		// 装入功能对应语言文件
		include_once("../{$this->_config['global']['langURL']}/main.php");
		$this->_lang['main'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/blogadmin.php");
		$this->_lang['admin'] = $langadfun;

		@session_start();
		// 用户验证
//		if (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['public'][21], "./login.php");

		if (FALSE == empty($_GET['action']) && 'delInstall' == $_GET['action']) $this->_delInstallFile();
		else $this->_printOutPage();

		exit();
	}

	function _printOutPage()
	{
		$this->_getServerInfo();
		$this->_listTeamMembers();
		$this->printPageHeader();
		echo "<table cellpadding=4 cellspacing=1 border=0 width=100% align=center class=border><tr bgcolor=#99CC66><td bgcolor=#FFFFFF class=main><br>";

		if (FALSE == $this->_checkInstallFile()) echo "{$this->_lang['admin'][14]}{$this->_lang['admin'][15]}{$this->_lang['admin'][16]}";

		echo "<br><table width=100% border=0 cellpadding=1 cellspacing=1 class=main><tr><td colspan=2 class=menu align=center><b>{$this->_lang['main'][0]}</b></td></tr><tr><td height=23 colspan=2><b>- {$this->_lang['main'][1]}</b></td></tr><tr><td width=20% height=23></td><td width=80%>{$this->_lang['main'][2]}- {$this->status['num_blog']} {$this->_lang['main'][3]}- {$this->status['num_comment']} {$this->_lang['main'][4]}- {$this->status['num_trackback']} {$this->_lang['main'][1]}{$this->_lang['main'][15]}- {$this->status['num_visited']} <br /> {$this->_lang['main'][5]}- {$this->status['db_size']} {$this->_lang['main'][6]}- {$this->status['uptime']} {$this->_lang['main'][7]}</td></tr><tr><td height=23 colspan=2><b>- {$this->_lang['main'][8]}</b></td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][9]}</td><td width=80%>&nbsp;{$this->status['www']['server']}</td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][10]}</td><td width=80%>&nbsp;{$this->status['database']['version']}</td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][25]}</td><td width=80%>&nbsp;{$this->status['database']['host']}</td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][26]}</td><td width=80%>&nbsp;{$this->status['database']['client']}</td></tr><tr><td height=23 colspan=2><b>- {$this->_lang['main'][27]}</b></td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][28]}</td><td width=80%>&nbsp;{$_SESSION['exPass']}</td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][29]}</td><td width=80%>&nbsp;" . date("Y-m-d H:i:s", $_SESSION['exUserlastvisit']) . "</td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][30]}</td><td width=80%>&nbsp;{$_SESSION['exUserIpAddress']}</td></tr></table><hr size=0 noshade width=90%>";

		echo "<table cellpadding=4 cellspacing=1 border=0 width=95% align=center class=main><tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['main'][11]}</b></td></tr>";

		echo "<tr><td width=25% height=23 nowrap><b>- {$this->_lang['main'][16]}</b></td><td width=75% height=23>";
		$this->_printMemberList($this->members['manager']);
		echo "</td></tr>";

		echo "<tr><td width=25% height=23 nowrap><b>- {$this->_lang['main'][17]}</b></td><td width=75% height=23>";
		$this->_printMemberList($this->members['coder']);
		echo "</td></tr>";

		echo "<tr><td width=25% height=23 nowrap><b>- {$this->_lang['main'][18]}</b></td><td width=75% height=23>";
		$this->_printMemberList($this->members['skiner']);
		echo "</td></tr>";

		echo "<tr><td width=25% height=23 nowrap><b>- {$this->_lang['main'][19]}</b></td><td width=75% height=23>";
		$this->_printMemberList($this->members['documenter']);
		echo "</td></tr>";

		echo "<tr><td width=25% height=23 nowrap><b>- {$this->_lang['main'][20]}</b></td><td width=75% height=23>";
		$this->_printMemberList($this->members['tester']);
		echo "</td></tr>";

		echo "<tr><td width=25% height=23 nowrap><b>- {$this->_lang['main'][21]}</b></td><td width=75% height=23>";
		$this->_printMemberList($this->members['benefactor']);
		echo "</td></tr>";

		echo "</table><hr size=0 noshade width=90%>";

		echo "<table width=100% border=0 cellpadding=1 cellspacing=1 class=main><tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['main'][12]}</b></td></tr><tr><td height=23 colspan=2>{$this->_lang['main'][13]}</td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][22]} :</td><td width=80%>&nbsp; <a href=http://www.exBlog.net/>http://www.exBlog.net/</a></td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][23]} :</td><td width=80%>&nbsp; <a href=http://bbs.exblog.net/>{$this->_lang['main'][14]}</a></td></tr><tr><td width=20% height=23 align=center>{$this->_lang['main'][24]} :</td><td width=80%>&nbsp; <a href=mailto:support@exblog.net>support@exblog.net</a></td></tr></table></td></tr></table>";

		$this->printPageFooter();
	}

	function _checkInstallFile()
	{
		$install_file = "./install.php";
		if (TRUE == @file_exists($install_file)) return FALSE;
		else return TRUE;
	}

	function _printMemberList($members)
	{
		$tpl_with_email = " <a href=mailto:%s>%s</a>,";
		$tpl_without_email = " %s,";
		$tpl_with_homepage = " <a href=http://%s>%s</a>,";
		for ($i = 0; $i < count($members); $i++)
		{
			if (TRUE == isset($members[$i]['email']) && FALSE == empty($members[$i]['email'])) printf($tpl_with_email, $members[$i]['email'], $members[$i]['name']);
			elseif (TRUE == isset($members[$i]['homepage']) && FALSE == empty($members[$i]['homepage'])) printf($tpl_with_homepage, $members[$i]['homepage'], $members[$i]['name']);
			else printf($tpl_without_email, $members[$i]['name']);
		}
	}

	function _getServerInfo()
	{
		$this->status['www']['server'] = getenv("SERVER_SOFTWARE");

		$query_string = "SELECT VERSION() AS version";
		$this->_dbop->query($query_string);
		$tmp = $this->_dbop->fetchArray($this->_dbop->query_id, 'ASSOC');
		$this->_dbop->freeResult();
		$this->status['database']['version'] = $tmp['version'];
		$this->status['database']['host'] = mysql_get_host_info();
		$this->status['database']['client'] = mysql_get_client_info();

		$query_string = "select blogCount, commentCount, trackbackCount from {$this->_dbop->prefix}global limit 0, 1";
		$this->_dbop->query($query_string);
		$tmp = $this->_dbop->fetchArray($this->_dbop->query_id, MYSQL_ASSOC);
		$this->_dbop->freeResult();
		$this->status['num_blog'] = $tmp['blogCount'];
		$this->status['num_comment'] = $tmp['commentCount'];
		$this->status['num_trackback'] = $tmp['trackbackCount'];

		// 获取访问统计数
		$query_string = "select visits from {$this->_dbop->prefix}visits order by currentDate desc limit 0, 1";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $tmp = $this->_dbop->fetchArray(0, 'ASSOC');
		else $tmp['visits'] = 0;
		$this->_dbop->freeResult();
		$this->status['num_visited'] = $tmp['visits'];

		$this->status['db_size'] = $this->_getDatabaseSize();

		$this->status['uptime'] = $this->_getUptime();
	}

	function _getDatabaseSize()
	{
		$size = 0;
		$tables = array('aboutme', 'admin', 'announce', 'blog', 'comment', 'global', 'links', 'sort', 'user', 'visits', 'tags');
		array_walk($tables, array($this, "_addTablePrefix"), $this->_dbop->prefix);
		$query_string = "SHOW TABLE STATUS FROM {$this->_dbop->dbname}";
		$this->_dbop->query($query_string);
		$tmp = $this->_dbop->fetchArrayBat($this->_dbop->query_id, MYSQL_ASSOC);
		$this->_dbop->freeResult();
		for ($i = 0; $i < count($tmp); $i++)
		{
			if (TRUE == in_array($tmp[$i]['Name'], $tables)) $size += $tmp[$i]['Data_length'] + $tmp[$i]['Index_length'] + $tmp[$i]['Data_free'];
		}
		return $this->_formatFileSize($size);
	}

	function _getUptime()
	{
		$now_time = time();
		$day = 1;
		if ($now_time != $this->_config['global']['initTime']) $day = ($now_time - $this->_config['global']['initTime']) / 3600 / 24;
		return ceil($day);
	}

	function _addTablePrefix(&$value, $key, $prefix)
	{
		$value = $prefix . $value;
	}

	function _listTeamMembers()
	{
		$this->members['manager'] = array(
			array('name' => 'netxiong', 'email' => 'netxiong@hotmail.com'),
			array('name' => 'Viva', 'email' => 'sachxp@126.com')
		);
		$this->members['coder'] = array(
			array('name' => 'feeling', 'email' => 'feeling_2005@126.com'),
			array('name' => 'elliott'),
			array('name' => 'Tomy'),
			array('name' => 'Sheer'),
			array('name' => '流水流云'),
			array('name' => 'Anthrax')
		);
		$this->members['skiner'] = array(
			array('name' => 'madman', 'homepage' => 'www.madcn.com'),
			array('name' => 'Hunter')
		);
		$this->members['documenter'] = array(
			array('name' => 'Viva', 'email' => 'sachxp@126.com'),
			array('name' => 'Tomy')
		);
		$this->members['tester'] = array(
			array('name' => 'BBS', 'homepage' => 'bbs.exBlog.net/')
		);
		$this->members['benefactor'] = array(
			array('name' => '混蛋70', 'homepage' => 'www.w3u.cn'),
			array('name' => 'codesky', 'homepage' => 'www.flydream.cn'),
			array('name' => 'neptune', 'homepage' => 'neptune.t35.com/blog/'),
			array('name' => 'aplapl', 'homepage' => 'aplapl.hwagain.com/myblog'),
			array('name' => 'ryhbgs'),
			array('name' => 'rick'),
			array('name' => 'gogo'),
			array('name' => 'howell', 'email' => 'vwell@163.com'),
			array('name' => '蓝鸟', 'email' => 'admin@wfdxh.com')
		);
	}

	function _delInstallFile()
	{
		$install_file = "./install.php";
		if (TRUE == file_exists($install_file)) $result = @unlink($install_file);
		if (TRUE == $result) $this->showMesg($this->_lang['admin'][17], $_SERVER['PHP_SELF']);
		else $this->_showError($this->_lang['admin'][14]);
	}
}

new AdminHome();
?>