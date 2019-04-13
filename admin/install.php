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
 *  File: install.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-26 18:18
 *  Homepage: www.exblog.net
 *
 * $Id: install.php,v 1.3 2005/07/06 18:05:43 feeling Exp $
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

define("exBlogVer", "V 1.5.0 alpha D2");
define("exBlogVer_s", "01050020050907");
define("langURL", "Chinese_GB2312");

class html
{
	var $_input = array();
	var $_lang = array();
	var $_notices = array();
	var $_mesgs = array();
	var $steps_count = 8;
	var $_errors_count = 0;

	function html()
	{
		global $lang, $langpublic;
		$this->_input['_GET'] = &$_GET;
		$this->_input['_POST'] = &$_POST;
		$this->_input['_COOKIE'] = &$_COOKIE;
		$this->_input['_SERVER'] = &$_SERVER;
		$this->_input['_ENV'] = &$_ENV;
		$this->_lang['install'] = $lang;
		$this->_lang['public'] = $langpublic;
	}

	function printHeader($current_step = 1)
	{
		echo "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<title>" . exBlogVer . " {$this->_lang['install'][0]}...... exSoft Team</title>
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
<body dir={$this->_lang['public']['dir']}>
<form method=\"post\" action=\"{$this->_input['_SERVER']['PHP_SELF']}?lang={$this->language_package}\">
<input type=\"hidden\" name=\"action\" value=\"step{$current_step}\">
<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td bgcolor=\"#FFFFFF\" class=\"menu\"><div align=\"center\"><font color=\"#FF0000\">" . exBlogVer . "</font> {$this->_lang['install'][0]}</div></td></tr>
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
	for ($i = 1; $i <= $this->steps_count; $i++) echo "<td width=\"7%\" class=\"" . (($i === $current_step) ? "nowt" : "noww") . "\">" . sprintf($this->_lang['install'][81], $i) . "</td>";
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

	function _showMesgs($current_step, $action_desc)
	{
		$this->printHeader($current_step);
		echo "<p style=\"margin-left: 10px; margin-right: 10px; font-size: 13px; font-weight: bold;\"> $action_desc</p>";
		echo "<table width=95% cellpadding=2 cellspacing=2 border=0 align=center>";
		for ($i = 0; $i < count($this->_mesgs); $i++)
		{
			$style_name = "noticeMesg";
			if (TRUE == $this->_notices[$i][0])
			{
				$this->_errors_count++;
				$style_name = "errorMesg";
			}
			echo "<tr><td align=left bgcolor=\"#FFFFFF\" class=\"main\">{$this->_mesgs[$i]}</td><td bgcolor=\"#FFFFFF\" class=\"main\"><span class=\"$style_name\">{$this->_notices[$i][1]}</span></td>";
		}
		echo "</table>";
		echo "<div align=center>";
		if (0 < $this->_errors_count) echo sprintf($this->_lang['install'][86], $this->_errors_count) . "<br /><br /><input type=button value=\"{$this->_lang['install'][16]}\" class=\"botton\" onClick=\"javascript:location.href='{$this->_input['_SERVER']['PHP_SELF']}?step=$current_step&action=undo';\" disabled title=\"{$this->_lang['install'][21]}\"/>";
		elseif ($current_step == $this->steps_count) echo "<input type=button value=\"{$this->_lang['install'][58]}\" class=\"botton\" onClick=\"window.location='login.php';\"/>";
		else echo "<input type=submit value=\"{$this->_lang['install'][18]}\" class=\"botton\" />";
		echo "<br />&nbsp; </div>";
	}

	function _showWelcome($current_step)
	{
		$this->printHeader($current_step);
		echo "
        &nbsp; <img src=\"../images/group.gif\" width=\"17\" height=\"14\"> {$this->_lang['install'][1]}<br />
		<p style=\"margin-left: 10px; margin-right: 10px;\"> {$this->_lang['install'][2]}</p>
        <div align=\"center\"><textarea rows=17 cols=75 style=\"border: 1 solid #C7C7C7; overflow-y:hidden;\">{$this->_lang['install'][5]}</textarea></div>
        <p align=\"right\" style=\"margin-left: 10px; margin-right: 10px;\">{$this->_lang['install'][6]}<br />
          2004.10.31</p>
        <div align=\"center\">
          <input type=\"submit\" value=\"{$this->_lang['install'][7]}\" class=\"botton\">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type=\"button\" value=\"{$this->_lang['install'][8]}\" onclick=\"window.close()\" class=\"botton\">
          <br />
          &nbsp; </div>
		";
	}

	function _showDatabaseForm($current_step)
	{
		$this->printHeader($current_step);
		echo "
<table cellpadding=2 cellspacing=2 border=0 align=center>
  <tr><td colspan=2 align=center class=\"menu\"><strong>{$this->_lang['install'][9]}</strong></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][10]}</td><td><input type=text name=host value=\"localhost\" class=\"input\"></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][11]}</td><td><input type=text name=user value=\"root\" class=\"input\"></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][12]}</td><td><input type=password name=password value=\"\" class=\"input\"></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][13]}</td><td><input type=text name=dbname value=\"test\" class=\"input\"><input name=\"new_dbname\" value=\"1\" type=\"checkbox\" id=\"new_dbname\" title=\"{$this->_lang['install'][94]}\"/> <label for=\"new_dbname\" title=\"{$this->_lang['install'][92]}\" class=\"main\">{$this->_lang['install'][93]}</label></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][14]}</td><td><input type=text name=prefix value=\"exblog_\" class=\"input\"></td></tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan=2 class=\"main\">{$this->_lang['install'][15]}</td></tr>
</table>
<br /><br /><br />
<div align=\"center\">
<input type=\"button\" value=\"{$this->_lang['install'][16]}\" onClick=\"javascript:location.href='{$this->_input['_SERVER']['PHP_SELF']}?step=$current_step&action=undo';\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"reset\" value=\"{$this->_lang['install'][17]}\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"submit\" value=\"{$this->_lang['install'][18]}\" class=\"botton\"><br /> &nbsp;
</div>
		";
	}

	function _showSiteInfo($current_step, $langs, $templates)
	{
		$this->printHeader($current_step);
		echo "
<table cellpadding=2 cellspacing=2 border=0 align=center>
  <tr><td colspan=2 align=center class=\"menu\"><strong>{$this->_lang['install'][33]}</strong></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][34]}</td><td><input type=text name=exBlogName value=\"exBlog\" class=\"input\" size=30></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][35]}</td><td><input type=text name=exBlogUrl value=\"http://www.exBlog.net\" class=\"input\" size=30></td></tr>
  <tr><td align=right class=\"main\" valign=top>{$this->_lang['install'][36]}</td><td><textarea name=exBlogCopyright rows=5 cols=40 class=\"input\">Copyright &copy; 2003-2005 &lt;a href=http://www.exblog.net target=_blank class=copy&gt;exBlog&lt;/a&gt; All Rights Reserved.</textarea></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][37]}</td><td><select name=languageURL class=\"button\">";
		for ($i = 0; $i < count($langs); $i++) echo "<option value=\"language/{$langs[$i]['path']}\">{$langs[$i]['name']}</option>";
		echo "</select></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][38]}</td><td><select name=templatesURL class=\"button\">";
		for ($i = 0; $i < count($templates); $i++) echo "<option value=\"templates/{$templates[$i]['path']}\">{$templates[$i]['name']}</option>";
		echo "</select></td></tr>
  <tr><td align=right class=\"main\" valign=top>{$this->_lang['install'][39]}</td><td><textarea name=description rows=3 cols=40 class=\"input\">Power by exBlog.Net</textarea></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][40]}</td><td><input type=text name=webmaster value=\"support@exblog.net\" class=\"input\" size=30></td></tr>
  <tr><td align=right class=\"main\" valign=top>{$this->_lang['install'][41]}</td><td><textarea name=sitekeyword rows=3 cols=40 class=\"input\">exsoft,exblog,blog,weblog,blog,</textarea></td></tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan=2 class=\"main\">{$this->_lang['install'][42]}</td></tr>
</table>
<br /><br /><br />
<div align=\"center\">
<input type=\"button\" value=\"{$this->_lang['install'][16]}\" onClick=\"javascript:location.href='{$this->_input['_SERVER']['PHP_SELF']}?step=$current_step&action=undo';\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"reset\" value=\"{$this->_lang['install'][17]}\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"submit\" value=\"{$this->_lang['install'][18]}\" class=\"botton\"><br /> &nbsp;
</div>
		";
	}

	function _showAddAdministrator($current_step, $error_mesgs = array())
	{
		$this->printHeader($current_step);
		echo "
<table cellpadding=2 cellspacing=2 border=0 align=center>
  <tr><td colspan=2 align=center class=\"menu\"><strong>{$this->_lang['install'][46]}</strong></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][47]}</td><td><input type=text name=user value=\"" . ((FALSE == empty($this->_input["_POST"]['user'])) ? $this->_input["_POST"]['user'] : '') . "\" class=\"input\"><span class=errorDesc id=user>";
		if (FALSE == empty($error_mesgs["user"])) echo $error_mesgs["user"];
		echo "</span></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][48]}</td><td><input type=password name=password value=\"" . ((FALSE == empty($this->_input["_POST"]['password'])) ? $this->_input["_POST"]['password'] : '') . "\" class=\"input\"><span class=errorDesc id=password>";
		if (FALSE == empty($error_mesgs["password"])) echo $error_mesgs["password"];
		echo "</span></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][49]}</td><td><input type=password name=password_2 value=\"" . ((FALSE == empty($this->_input["_POST"]['password_2'])) ? $this->_input["_POST"]['password_2'] : '') . "\" class=\"input\"><span class=errorDesc id=password_2>";
		if (FALSE == empty($error_mesgs["password_2"])) echo $error_mesgs["password_2"];
		echo "</span></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][50]}</td><td><input type=text name=email value=\"" . ((FALSE == empty($this->_input["_POST"]['email'])) ? $this->_input["_POST"]['email'] : '') . "\" id=email class=\"input\"><span class=errorDesc id=email>";
		if (FALSE == empty($error_mesgs["email"])) echo $error_mesgs["email"];
		echo "</span></td></tr>
  <tr><td align=right class=\"main\">{$this->_lang['install'][51]}</td><td><input type=text name=phone value=\"" . ((FALSE == empty($this->_input["_POST"]['phone'])) ? $this->_input["_POST"]['phone'] : '') . "\" id=phone class=\"input\" title='{$this->_lang['install'][97]}'><span class=errorDesc id=phone>";
		if (FALSE == empty($error_mesgs["phone"])) echo $error_mesgs["phone"];
		echo "</span></td></tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan=2 class=\"main\">{$this->_lang['install'][52]}</td></tr>
</table>
<br /><br /><br />
<div align=\"center\">
<input type=\"button\" value=\"{$this->_lang['install'][16]}\" onClick=\"javascript:location.href='{$this->_input['_SERVER']['PHP_SELF']}?step=$current_step&action=undo';\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"reset\" value=\"{$this->_lang['install'][17]}\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"submit\" value=\"{$this->_lang['install'][18]}\" class=\"botton\"><br /> &nbsp;
</div>
		";
	}

	function _errorHandler($error_level, $error_mesg, $error_file, $error_line, $error_content)
	{
		switch ($error_level)
		{
			case E_USER_ERROR| E_WARNING | E_ERROR: $error_header = "FATAL"; break;
			case E_USER_WARNING: $error_header = "WARNING"; break;
			case E_USER_NOTICE: $error_header = "NOTICE"; break;
			default: $error_header = "UNKNOWN"; break;
		}
		echo "
<html>
<head>
<title>$error_header Error!</title>
<style type=text/css>
body { font-family: Verdana, Arial, tahoma; }
td { font-size: 12px; }
td.title { font-size: 14px; color: #FFFFFF; font-weight:bold; background-color:#000000; height: 25; text-align:center; }
</style>
</head>
<body>
<table width=450 height=300 cellpadding=2 cellspacing=2 border=1 bordercolor=#000000 align=center>
  <tr><td class=title>$error_header Error!</td></tr>
  <tr>
	<td bordercolor=#FFFFFF valign=top>
    <table width=90% border=0 align=center cellspacing=4 cellspadding=4>
	  <tr><td width=20% nowrap align=right valign=top>Message: </td><td width=80%><font color=#FF0000><b>$error_mesg</b></font></td></tr>
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
		if ($error_level == E_USER_ERROR || $error_level == E_WARNING || $error_level == E_ERROR) die();
	}
}

class Handler extends Undo
{
	var $_input = array();
	var $_lang = array();
	var $_notices = array();
	var $_mesgs = array();
	var $steps_count = 8;
	var $_errors_count = 0;
	var $default_handler = "errorHandler";
	var $language_package = "";
	var $language_packages = array();

	function Handler()
	{
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $this->_handlePostData($_POST);
		$this->_input['_SERVER'] = $_SERVER;
		$this->_input['_ENV'] = &$_ENV;

		// 语言包设置
		if (FALSE == empty($this->_input['_GET']['lang'])) $this->language_package = $this->_input['_GET']['lang'];
		else $this->language_package = langURL;

		$this->language_packages = $this->_walkDir("../language");
		for ($i = 0; $i < count($this->language_packages); $i++) $tmp[] = basename($this->language_packages[$i]['path']);
		if (FALSE == in_array($this->language_package, $tmp)) $this->language_package = langURL;
		// 装入语言文件
		include_once("../language/{$this->language_package}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
		include_once("../language/{$this->language_package}/install.php");
		$this->_lang['install'] = $lang;
		

		if (function_exists($this->default_handler)) set_error_handler($this->default_handler);
		else set_error_handler(array(&$this, "_errorHandler"));

		if (TRUE == empty($this->_input['_POST']['action'])) $this->_showWelcome(1);
		elseif ('step1' == $this->_input['_POST']['action']) $this->_checkDirProperty(2);
		elseif ('step2' == $this->_input['_POST']['action']) $this->_showDatabaseForm(3);
		elseif ('step3' == $this->_input['_POST']['action']) $this->_createDatabaseStructure(4);
		elseif ('step4' == $this->_input['_POST']['action']) $this->_showSiteInfo(5, $this->_walkDir("../language"), $this->_walkDir("../templates"));
		elseif ('step5' == $this->_input['_POST']['action']) $this->_saveSiteInfo(6);
		elseif ('step6' == $this->_input['_POST']['action']) $this->_showAddAdministrator(7);
		elseif ('step7' == $this->_input['_POST']['action']) $this->_addAdministrator(8);
		elseif ('step8' == $this->_input['_POST']['action']) { header("Location: login.php"); exit(); }
		else $this->_showWelcome(1);

		$this->printFooter();

		exit();
	}

	function _checkDirProperty($current_step)
	{
		$this->_mesgs[] = sprintf($this->_lang['install'][84], '../upload');
		if (FALSE == @file_exists("../upload")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][87], "../upload"));
		elseif (FALSE == @is_dir("../upload")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][88], '../upload'));
		elseif (FALSE == @is_writable("../upload/")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][89], '../upload'));
		else $this->_notices[] = array(FALSE, $this->_lang['install'][83]);

		$this->_mesgs[] = sprintf($this->_lang['install'][84], '../include');
		if (FALSE == @file_exists("../include")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][87], "../upload"));
		elseif (FALSE == @is_dir("../include")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][88], '../upload'));
		elseif (FALSE == @is_writable("../include/")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][89], '../upload'));
		else $this->_notices[] = array(FALSE, $this->_lang['install'][83]);

		$this->_mesgs[] = sprintf($this->_lang['install'][85], '../include/config.inc.php');
		if (TRUE == @file_exists("../include/config.inc.php")) $this->_notices[] = array(TRUE, sprintf($this->_lang['install'][90], "../include/config.inc.php"));
		else $this->_notices[] = array(FALSE, $this->_lang['install'][83]);
		$this->_showMesgs($current_step, $this->_lang['install'][91]);
	}

	function _createDatabaseStructure($current_step)
	{
		$query_postfix = ", DEFAULT CHARACTER SET {$this->_lang['public']['charset']}";

		include_once("../include/mysql.php");
		$dbop = new Database();
		$dbop->host = $this->_input['_POST']['host'];
		$dbop->user = $this->_input['_POST']['user'];
		$dbop->passwd = $this->_input['_POST']['password'];
		$dbop->prefix = $this->_input['_POST']['prefix'];
		$dbop->connect("", "", "", "", "", "", FALSE);
		$dbop->setCharset($this->_lang['public']['charset']);

		$this->_mesgs[] = sprintf($this->_lang['install'][22], $this->_input['_POST']['dbname']);
		$str = "create database {$this->_input['_POST']['dbname']}";
		if (TRUE == $dbop->support_charset) $str .= substr($query_postfix, 1);
		$query_strings[] =  $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}admin");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}admin;;CREATE TABLE {$this->_input['_POST']['prefix']}admin (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, uid TINYINT(1) UNSIGNED NOT NULL, user VARCHAR(20) NOT NULL, password VARCHAR(33) NOT NULL, email VARCHAR(35) NOT NULL, phone VARCHAR(11) DEFAULT '0' NOT NULL, blogCount int(10) unsigned not null, commentCount int(10) unsigned not null, connectionCount int(10) unsigned not null, homepage varchar(100) not null, showEmail ENUM('hidden', 'escape', 'visible') not null default 'hidden', ipaddress varchar(15) not null default '255.255.255.255', lastvisit varchar(20) not null default '0') type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}visits");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}visits;;CREATE TABLE {$this->_input['_POST']['prefix']}visits (visits INT(20) UNSIGNED, currentDate VARCHAR(20), todayVisits INT(5)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}visits");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}visits (visits,currentDate,todayVisits) VALUES('0','" . mktime(0, 0, 0) . "','0')";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}announce");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}announce;;CREATE TABLE {$this->_input['_POST']['prefix']}announce (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR(40), content TEXT, author VARCHAR(20), email VARCHAR(35), addtime varchar(20)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}weather");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}weather;;CREATE TABLE {$this->_input['_POST']['prefix']}weather (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, enWeather VARCHAR(10) NOT NULL, cnWeather VARCHAR(20) NOT NULL) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}weather");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}weather (enWeather, cnWeather) VALUES('null', '" . $this->_lang['install'][71] . "');;INSERT INTO {$this->_input['_POST']['prefix']}weather (enWeather, cnWeather) VALUES ('sunny', '" . $this->_lang['install'][72] . "');;INSERT INTO {$this->_input['_POST']['prefix']}weather (enWeather, cnWeather) VALUES ('cloudy', '" . $this->_lang['install'][73] . "');;INSERT INTO {$this->_input['_POST']['prefix']}weather (enWeather, cnWeather) VALUES ('rain', '" . $this->_lang['install'][74] . "');;INSERT INTO {$this->_input['_POST']['prefix']}weather (enWeather, cnWeather) VALUES ('snow', '" . $this->_lang['install'][75] . "');;INSERT INTO {$this->_input['_POST']['prefix']}weather (enWeather, cnWeather) VALUES ('cloudysky', '" . $this->_lang['install'][76] . "')";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}sort");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}sort;;CREATE TABLE {$this->_input['_POST']['prefix']}sort (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, enName VARCHAR(20) NOT NULL, cnName VARCHAR(20) NOT NULL, description VARCHAR(50), sortOrder int(10) unsigned not null default 0, blogCount int(10) unsigned not null, commentCount int(10) unsigned not null, trackbackCount int(10) unsigned not null) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}sort");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}sort (enName, cnName, description) VALUES('default', '" . $this->_lang['install'][77] . "', '" . $this->_lang['install'][78] . "')";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}blog");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}blog;;CREATE TABLE {$this->_input['_POST']['prefix']}blog (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, sort VARCHAR(20) NOT NULL, title VARCHAR(50) NOT NULL, content TEXT NOT NULL, author VARCHAR(20), email VARCHAR(35), visits INT(10), addtime VARCHAR(20), keyword VARCHAR(100), summarycontent TEXT, weather VARCHAR(20), top ENUM('0', '1') DEFAULT '0' NOT NULL, hidden ENUM( '0', '1' ) DEFAULT '0' NOT NULL, html ENUM( '0', '1' ) DEFAULT '0' NOT NULL, nocom ENUM( '0', '1' ) DEFAULT '0' NOT NULL, commentCount int(10) unsigned not null, trackbackCount int(10) unsigned not null) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}comment");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}comment;;CREATE TABLE {$this->_input['_POST']['prefix']}comment (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, commentID INT(10), commentSort VARCHAR(20), author VARCHAR(20), email VARCHAR(35), homepage VARCHAR(40), qq INT(12), content TEXT, addtime varchar(20), ipaddress varchar(15) not null) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}links");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}links;;CREATE TABLE {$this->_input['_POST']['prefix']}links (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, homepage VARCHAR(20) NOT NULL, logoURL VARCHAR(150), url VARCHAR(150) NOT NULL, description VARCHAR(100), visits INT(10), visible ENUM('0', '1'), linkOrder int(10) unsigned not null default 0) type=MYISAM";
		if (TRUE == $dbop->_isSupportCharset()) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}links");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}links (homepage, url, description, visits, visible, linkOrder) VALUES('exBlog\'s Home','http://www.exBlog.net','exSoft Team', '0', '1', 0);;INSERT INTO {$this->_input['_POST']['prefix']}links (homepage, url, description, visits, visible, linkOrder) VALUES('4U@Design','http://www.4ulm.org','4U@Design Team', '0', '1', 0)";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}aboutme");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}aboutme;;CREATE TABLE {$this->_input['_POST']['prefix']}aboutme (name VARCHAR(20), age INT(3), email VARCHAR(35), qq INT(12), icq INT(12), msn VARCHAR(35), description TEXT) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}aboutme");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}aboutme (name,age,email,qq,icq,msn,description) VALUES('Name','20','@','0','0','@','" . $this->_lang['install'][79] . "')";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}upload");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}upload;;CREATE TABLE {$this->_input['_POST']['prefix']}upload (max_file_size int(8) default NULL, destination_folder text default NULL, up_type varchar(255) default NULL, max_width smallint(4) unsigned default 1, max_height smallint(4) unsigned default 1, watermark_type enum('string','image') default 'string', watermark text, up_url text not null default '', fulltext(destination_folder)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}upload");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}upload (max_file_size,destination_folder,up_type, up_url) VALUES('2000000', '/', 'gif,jpg,png,zip,rar,txt', '')";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}global");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}global;;CREATE TABLE {$this->_input['_POST']['prefix']}global (siteName VARCHAR(50), siteUrl VARCHAR(50), copyright VARCHAR(255), tmpURL VARCHAR(40), langURL VARCHAR(40), activeRun ENUM('0', '1') DEFAULT '1', unactiveRunMessage TEXT, initTime VARCHAR(20), isCountOnlineUser ENUM('0', '1') DEFAULT '0', Description TEXT NOT NULL , Version VARCHAR(20), Webmaster VARCHAR(35) DEFAULT 'support@exblog.net' NOT NULL , sitekeyword VARCHAR(100) DEFAULT 'exsoft,exblog,blog,weblog,blog', GDswitch ENUM( '0', '1' ) DEFAULT '0' NOT NULL, exurlon ENUM( '0', '1' ) DEFAULT '0' NOT NULL, summarynum INT(5) DEFAULT '450' , alltitlenum INT(5) DEFAULT '18' , listblognum INT(5) DEFAULT '6' , listallnum INT(5) DEFAULT '20', blogCount int(10) unsigned not null, commentCount int(10) unsigned not null, trackbackCount int(10) unsigned not null, userCount int(10) unsigned not null, siteSubName varchar(50) not null) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}online");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}online;;CREATE TABLE {$this->_input['_POST']['prefix']}online (id INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY, ip VARCHAR(15) NOT NULL, lastLoginTime varchar(20)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}trackback");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}trackback;;CREATE TABLE {$this->_input['_POST']['prefix']}trackback (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, TrackbackID INT(10) DEFAULT '0' NOT NULL, url VARCHAR(200) NOT NULL, blog_name VARCHAR(35), title VARCHAR(40), excerpt VARCHAR(255), addtime varchar(20)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}keyword");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}keyword;;CREATE TABLE {$this->_input['_POST']['prefix']}keyword (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, word VARCHAR(100) NOT NULL, content text NOT NULL, url VARCHAR(150)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;
		$this->_mesgs[] = sprintf($this->_lang['install'][66], "{$this->_input['_POST']['prefix']}keyword");
		$query_strings[] = "INSERT INTO {$this->_input['_POST']['prefix']}keyword (word, content) VALUES ('exBlog','HOME: http://www.exBlog.net')";

		$this->_mesgs[] = sprintf($this->_lang['install'][65], "{$this->_input['_POST']['prefix']}tags");
		$str = "DROP TABLE IF EXISTS {$this->_input['_POST']['prefix']}tags;;CREATE TABLE {$this->_input['_POST']['prefix']}tags (id int(11) unsigned NOT NULL auto_increment, tag varchar(100) NOT NULL default '', PRIMARY KEY  (id)) type=MYISAM";
		if (TRUE == $dbop->support_charset) $str .= $query_postfix;
		$query_strings[] = $str;

		if (FALSE == empty($this->_input['_POST']['new_dbname']))
		{
			$dbop->query($query_strings[0]);
			$tmp_error_mesg = $dbop->getError('message');
			$this->_notices[] = (FALSE == empty($tmp_error_mesg))  ? array(TRUE, $tmp_error_mesg) : array(FALSE, $this->_lang['install'][83]);
		}
		else array_shift($this->_mesgs);
		$dbop->selectDb($this->_input['_POST']['dbname']);
		for ($i = 1; $i < count($query_strings); $i++)
		{
			$dbop->query($query_strings[$i]);
			$tmp_error_mesg = $dbop->getError('message');
			$this->_notices[] = (FALSE == empty($tmp_error_mesg))  ? array(TRUE, $tmp_error_mesg) : array(FALSE, $this->_lang['install'][83]);
		}
		$dbop->close();
		unset($dbop);
		// 保存数据库配置
		$tmp_error_mesg = "";
		$str2write = "<" . "?php\n// Generated By Installation Guide on " . date("r") . "\n"
			. "\$version['string'] = \"" . exBlogVer . "\";\n"
			. "\$version['update'] = \"" . exBlogVer_s . "\";\n"
			. "\$exBlog['host'] = \"" . $this->_input['_POST']['host'] . "\";\t// Host name or IP address of the database server\n"
			. "\$exBlog['user'] = \"" . $this->_input['_POST']['user'] . "\";\t// User name\n"
			. "\$exBlog['password'] = \"" . $this->_input['_POST']['password'] . "\";\t// Password of the user\n"
			. "\$exBlog['dbname'] = \"" . $this->_input['_POST']['dbname'] . "\";\t// Database bane\n"
			. "\$exBlog['one'] = \"" . $this->_input['_POST']['prefix'] . "\";\t// Prefix of a new table\n"
			. "\$exBlog['charset'] = \"{$this->_lang['public']['charset']}\";\t// Charset for database\n"
			. "\n?" . ">";
		$this->_mesgs[] = $this->_lang['install'][25];
		$file_id = @fopen("../include/config.inc.php", "w") or $tmp_error_mesg = sprintf($this->_lang['install'][95], "../include/config.inc.php");
		$result = @fwrite($file_id, $str2write) or $tmp_error_mesg = (FALSE == empty($tmp_error_mesg)) ? $tmp_error_mesg : sprintf($this->_lang['install'][96], "../include/config.inc.php");
		@fclose($file_id);
		$this->_notices[] = (TRUE == $result) ? array(FALSE, $this->_lang['install'][83]) : array(TRUE, $tmp_error_mesg);
		$this->_showMesgs($current_step, $this->_lang['install'][32]);
	}

	function _saveSiteInfo($current_step)
	{
//		global $exBlog;	// 并不推荐这样使用，但是因为 config.inc.php 中引用的 condb.php 中使用到了 $exBlog
		$this->_mesgs[] = $this->_lang['install'][45];
		include_once("../include/config.inc.php");
		$query_string = "delete from {$exBlog['one']}global;;INSERT INTO {$exBlog['one']}global (siteName, siteUrl, copyright, tmpURL, langURL, activeRun, unactiveRunMessage, initTime, isCountOnlineUser, Description, Version, Webmaster, sitekeyword, GDswitch, exurlon, summarynum, alltitlenum, listblognum, listallnum, blogCount, commentCount, trackbackCount, userCount, siteSubName) VALUES('" . $this->_input['_POST']['exBlogName'] . "', '" . $this->_input['_POST']['exBlogUrl'] . "', '" . $this->_input['_POST']['exBlogCopyright'] . "', '" . $this->_input['_POST']['templatesURL'] . "', '" . $this->_input['_POST']['languageURL'] . "', '1', '" . $this->_lang['install'][80] . "', '" . time() . "', '0', '" . $this->_input['_POST']['description'] . "', '" . exBlogVer . "', '" . $this->_input['_POST']['webmaster'] . "', '" . $this->_input['_POST']['sitekeyword'] . "', '0', '0', '450', '18', '6', '20', 0, 0, 0, 1, 'Site Sub Name')";
		include_once("../include/mysql.php");
		$dbop = new Database();
		$dbop->host = $exBlog['host'];
		$dbop->user = $exBlog['user'];
		$dbop->passwd = $exBlog['password'];
		$dbop->dbname = $exBlog['dbname'];
		$dbop->prefix = $exBlog['one'];
		$dbop->connect();
		$dbop->setCharset($this->_lang['public']['charset']);
		$dbop->query($query_string);
		$tmp_error_mesg = $dbop->getError("message");
		$dbop->close();
		unset($dbop);
		$this->_notices[] = (FALSE == empty($tmp_error_mesg)) ? array(TRUE, $tmp_error_mesg) : array(FALSE, $this->_lang['install'][83]);
		$this->_notices[] = array(FALSE, $this->_lang['install'][83]);
		$this->_showMesgs($current_step, $this->_lang['install'][45]);
	}

	function _addAdministrator($current_step)
	{
		global $exBlog;	// 并不推荐这样使用，但是因为 config.inc.php 中引用的 condb.php 中使用到了 $exBlog
		$errorsMesg = $this->_checkFormAdmin();
		if (TRUE == count($errorsMesg)) $this->_showAddAdministrator(7, $errorsMesg);
		else
		{
			$this->_mesgs[] = $this->_lang['install'][55];
			include_once("../include/config.inc.php");
			include_once("../include/mysql.php");
			$query_string = "delete from {$exBlog['one']}admin;;INSERT INTO {$exBlog['one']}admin (user, uid, password, email, phone) VALUES('" . $this->_input['_POST']['user'] . "', '0', '" . md5($this->_input['_POST']['password']) . "', '" . $this->_input['_POST']['email'] . "', '" . $this->_input['_POST']['phone'] . "')";
			$dbop = new Database();
			$dbop->host = $exBlog['host'];
			$dbop->user = $exBlog['user'];
			$dbop->passwd = $exBlog['password'];
			$dbop->dbname = $exBlog['dbname'];
			$dbop->prefix = $exBlog['one'];
			$dbop->connect();
			$dbop->setCharset($this->_lang['public']['charset']);
			$dbop->query($query_string);
			$tmp_error_mesg = $dbop->getError("message");
			$dbop->close();
			unset($dbop);
			$this->_notices[] = (FALSE == empty($tmp_error_mesg)) ? array(TRUE, $tmp_error_mesg) : array(FALSE, $this->_lang['install'][83]);
			$this->_notices[] = array(FALSE, $this->_lang['install'][83]);
			// 删除本文件
			if (TRUE == empty($tmp_error_mesg))
			{
				$this->_mesgs[] = $this->_lang['install'][98];
				if (FALSE == @unlink(basename($this->_input['_SERVER']['PHP_SELF']))) $this->_notices[] = array(TRUE, $this->_lang['install'][59]);
				else $this->_notices[] = array(FALSE, $this->_lang['install'][83]);
			}
			$this->_showMesgs($current_step, $this->_lang['install'][55]);
		}
	}

	function _checkFormAdmin()
	{
		$errorsMesg = array();
		if (TRUE == empty($this->_input['_POST']['user'])) $errorsMesg['user'] = $this->_lang['install'][3];
		elseif (20 < strlen($this->_input['_POST']['user'])) $errorsMesg['user'] = sprintf($this->_lang['install'][4], 20);
		if (TRUE == empty($this->_input['_POST']['password'])) $errorsMesg['password'] = $this->_lang['install'][3];
		if (TRUE == empty($this->_input['_POST']['password_2'])) $errorsMesg['password_2'] = $this->_lang['install'][3];
		if ($this->_input['_POST']['password'] != $this->_input['_POST']['password_2']) $errorsMesg['password_2'] = $this->_lang['install'][19];
		elseif (6 > strlen($this->_input['_POST']['password'])) $errorsMesg['password'] = $this->_lang['install'][100];
		if (TRUE == empty($this->_input['_POST']['email'])) $errorsMesg['email'] = $this->_lang['install'][3];
		elseif (FALSE == $this->_isEmailAddress($this->_input['_POST']['email'])) $errorsMesg['email'] = $this->_lang['install'][20];
		if (TRUE == empty($this->_input['_POST']['phone'])) $this->_input['_POST']['phone'] = '';
		elseif (FALSE == preg_match("/^13[1|3|4|5|6|7|8|9|0]\d{8}$/", $this->_input['_POST']['phone'])) $errorsMesg['phone'] = $this->_lang['install'][20];
		return $errorsMesg;
	}

	function _walkDir($dir_path)
	{
		$dirop = dir($dir_path);
		while (FALSE !== ($entry = $dirop->read()))
		{
			if (substr($entry, 0, 1) != "." && "CVS" != $entry) $entries[] = array("path" => $entry, "name" => $this->_formatDirName($entry));
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

	function _handlePostData($data)
	{
		foreach ($data as $key => $value) $data[$key] = mysql_escape_string(trim($value));
		return $data;
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
}

class Undo extends Html
{
	var $_input = array();
	var $_lang = array();
	var $_notices = array();
	var $_mesgs = array();
	var $steps_count = 8;
	var $_errors_count = 0;

	function Undo()
	{
		global $lang, $langpublic;
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $this->_handlePostData($_POST);
		$this->_input['_SERVER'] = $_SERVER;
		$this->_input['_ENV'] = &$_ENV;
		$this->_lang['install'] = $lang;
		$this->_lang['public'] = $langpublic;
	}
}

new Handler();
?>
