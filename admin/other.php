<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * System configure guide for exBlog
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

define('MODULE_NAME', 'core-adm.other');

require_once("./public.php");

class ModifySystemOptions extends CommonLib
{
	var $errors = array();
	var $config_file = "../include/config.inc.php";

	function ModifySystemOptions()
	{
		$this->_initEnv(MODULE_NAME, 'other', 0);

		if (FALSE == empty($this->_input['_POST']['action']) && 'gogogo' == $this->_input['_POST']['action']) $this->_handlerModify();
		else $this->_printOutPage();

		$this->_destroyEnv();
	} 

	function _printOutPage()
	{
		$languages = $this->_walkDir("../language");
		$count_languages = count($languages);
		$templates = $this->_walkDir("../templates");
		$count_templates = count($templates);
		$this->printPageHeader();
		$this->_loadFromPost();
		echo "
<form method=post action=\"{$_SERVER['PHP_SELF']}\">
  <table border=0 cellpadding=0 cellspacing=0 class=border>
    <tr><td height=180 bgcolor=#FFFFFF class=main>
  <table cellpadding=4 cellspacing=1 border=0 width=100% align=center class=main>
    <tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['other'][1]}</b></td></tr>
    <tr><td colspan=2 height=23 align=center><a href=# onclick=\"window.open('http://www.exblog.net/checkv.php?action=check&v={$this->_config['global']['version_number']}', 'newwindow', 'height=200, width=300, top=100,left=300, toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no, status=no');\">{$this->_lang['other'][2]}</a></td></tr>
    <tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['other'][3]}</b></td></tr>
    <tr><td width=21% height=23 align=center>{$this->_lang['other'][4]}</td><td width=79%>&nbsp; <select name=languageURL class=botton>";

		for ($i = 0; $i < $count_languages; $i++)
		{
			echo "<option value=\"language/{$languages[$i]['path']}\"";
			if ("language/{$languages[$i]['path']}" == $this->_config['global']['langURL']) echo " selected=\"selected\"";
			echo "> {$languages[$i]['name']}</option>";
		} 
		echo "</select></td></tr><tr><td width=\"21%\" height=23 align=\"center\">{$this->_lang['other'][5]}</td><td width=\"79%\">&nbsp; <select name=\"templatesURL\" class=\"botton\">";

		for ($i = 0; $i < $count_templates; $i++)
		{
			echo "<option value=\"templates/{$templates[$i]['path']}\"";
			if ("templates/{$templates[$i]['path']}" == $this->_config['global']['tmpURL']) echo " selected=\"selected\"";
			echo "> {$templates[$i]['name']}</option>";
		} 

		echo "
        </select></td></tr>
	<tr><td height=23 align=center>{$this->_lang['other'][39]}</td><td>&nbsp; <input name=siteSubName type=text class=input value=\"{$this->_config['global']['siteSubName']}\" size=60><span class=errorMesg>" . ((FALSE == empty($this->errors['siteSubName'])) ? "<br />{$this->errors['siteSubName']}" : "") . "</span></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][6]}</td><td>&nbsp; <input name=exBlogName type=text class=input value=\"{$this->_config['global']['siteName']}\" size=60><span class=errorMesg>" . ((FALSE == empty($this->errors['exBlogName'])) ? "<br />{$this->errors['exBlogName']}" : "") . "</span></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][7]}</td><td>&nbsp; <input name=exBlogUrl type=text class=input value=\"{$this->_config['global']['siteUrl']}\" size=60><br />" . ((FALSE == empty($this->errors['exBlogUrl'])) ? "<span class=errorMesg>{$this->errors['exBlogUrl']}</span>": "<font color=red>* {$this->_lang['other'][8]}</font>") . "</td></tr>
    <tr><td width=21% height=23 align=center>{$this->_lang['other'][9]}</td><td width=79%>&nbsp; <input name=webmaster type=text class=input value=\"{$this->_config['global']['Webmaster']}\" size=60><span class=errorMesg>" . ((FALSE == empty($this->errors['webmaster'])) ? "<br />{$this->errors['webmaster']}" : "") . "</span></td></tr>
    <tr><td width=21% height=23 align=center>{$this->_lang['other'][10]}</td><td width=79%>&nbsp; <textarea name=description cols=60 rows=6 wrap=VIRTUAL class=input>{$this->_config['global']['Description']}</textarea><span class=errorMesg>" . ((FALSE == empty($this->errors['description'])) ? "<br />{$this->errors['description']}" : "") . "</span></td></tr>
    <tr><td width=21% height=23 align=center>{$this->_lang['other'][11]}</td><td width=79%>&nbsp; <input name=sitekeyword type=text class=input value=\"{$this->_config['global']['sitekeyword']}\" size=60><br />" . ((FALSE == empty($this->errors['sitekeyword'])) ? "<span class=errorMesg>{$this->errors['sitekeyword']}</span>": "<font color=red>* {$this->_lang['other'][12]}</font>") . "</td></tr>
    <tr><td width=21% height=23 align=center>{$this->_lang['other'][13]}</td><td width=79%>&nbsp; <textarea name=exBlogCopyright cols=60 rows=6 wrap=VIRTUAL class=input>{$this->_config['global']['copyright']}</textarea><span class=errorMesg>" . ((FALSE == empty($this->errors['exBlogCopyright'])) ? "<br />{$this->errors['exBlogCopyright']}" : "") . "</span></td></tr>
    <tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['other'][14]}</b></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][15]}</td><td>&nbsp; <select name=activeRun><option value=1" . (("1" == $this->_config['global']['activeRun']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['activeRun']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][18]}<br> {$this->_lang['other'][19]} </td><td>&nbsp; <textarea name=unactiveRunMessage cols=60 rows=6 wrap=VIRTUAL class=input id=unactiveRunMessage>{$this->_config['global']['unactiveRunMessage']}</textarea><span class=errorMesg>" . ((FALSE == empty($this->errors['unactiveRunMessage'])) ? "<br />{$this->errors['unactiveRunMessage']}" : "") . "</span></td></tr>
    <tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['other'][20]}</b></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][21]}</td><td>&nbsp; <input name=summarynum type=text class=input value=\"{$this->_config['global']['summarynum']}\" size=10> " . ((FALSE == empty($this->errors['summarynum'])) ? "<span class=errorMesg>{$this->errors['summarynum']}</span>": "<font color=red>* {$this->_lang['other'][22]}</font>") . "</td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][23]}</td><td>&nbsp; <input name=alltitlenum type=text class=input value=\"{$this->_config['global']['alltitlenum']}\" size=10> " . ((FALSE == empty($this->errors['alltitlenum'])) ? "<span class=errorMesg>{$this->errors['alltitlenum']}</span>": "<font color=red>* {$this->_lang['other'][24]}</font>") . "</td></tr>
    <tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['other'][25]}</b></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][26]}</td><td>&nbsp; <input name=listblognum type=text class=input value=\"{$this->_config['global']['listblognum']}\" size=10> " . ((FALSE == empty($this->errors['listblognum'])) ? "<span class=errorMesg>{$this->errors['listblognum']}</span>": "<font color=red>* {$this->_lang['other'][27]}</font>") . "</font></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][28]}</td><td>&nbsp; <input name=listallnum type=text class=input value=\"{$this->_config['global']['listallnum']}\" size=10> " . ((FALSE == empty($this->errors['listallnum'])) ? "<span class=errorMesg>{$this->errors['listallnum']}</span>": "<font color=red>* {$this->_lang['other'][29]}</font>") . "</font></td></tr>
    <tr><td class=menu colspan=2 height=25 align=center><b>{$this->_lang['other'][30]}</b></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][31]}</td><td>&nbsp;<select name=isCountOnlineUser><option value=1" . (("1" == $this->_config['global']['isCountOnlineUser']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['isCountOnlineUser']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][32]}</td><td>&nbsp;<select name=GDswitch><option value=1" . (("1" == $this->_config['global']['GDswitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['GDswitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][33]}</td><td>&nbsp;<select name=exurlon><option value=1" . (("1" == $this->_config['global']['exurlon']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['exurlon']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][40]}</td><td>&nbsp;<select name=RegisterSwitch><option value=1" . (("1" == $this->_config['global']['RegisterSwitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['RegisterSwitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][41]}</td><td>&nbsp;<select name=ACLSwitch><option value=1" . (("1" == $this->_config['global']['ACLSwitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['ACLSwitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][42]}</td><td>&nbsp;<select name=SyslogSwitch><option value=1" . (("1" == $this->_config['global']['SyslogSwitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][16]}</option><option value=0" . (("0" == $this->_config['global']['SyslogSwitch']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][17]}</option></select></td></tr>
    ";
		if ("1" == $this->_config['global']['SyslogSwitch']) echo "
    <tr><td height=23 align=center>{$this->_lang['other'][43]}</td><td>&nbsp;<input type=text name=SyslogLocation value='{$this->_config['global']['SyslogLocation']}'  class=input></td></tr>
    <tr><td height=23 align=center>{$this->_lang['other'][44]}</td><td>&nbsp;<select name=SyslogLevel><option value=1" . ((1 === $this->_config['global']['SyslogLevel']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][45]}</option><option value=2" . ((2 == $this->_config['global']['SyslogLevel']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][46]}</option><option value=3" . ((3 == $this->_config['global']['SyslogLevel']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][47]}</option><option value=0" . ((0 == $this->_config['global']['SyslogLevel']) ? " selected=\"selected\"" : "") . ">{$this->_lang['other'][48]}</option></select> " . ((FALSE == empty($this->errors['SyslogLevel'])) ? "<span class=errorMesg>{$this->errors['SyslogLevel']}</span>": '') . "</td></tr>
    	";
		else echo "<input type=hidden name=SyslogLevel value='{$this->_config['global']['SyslogLevel']}'><input type=hidden name=SyslogLocation value='{$this->_config['global']['SyslogLocation']}'>";

		echo "
    <tr align=center><input type=hidden name=action value=gogogo><td height=30 colspan=2><input type=submit value=\"{$this->_lang['other'][0]}\" class=botton></td></tr>
  </table></td></tr></table></form>
		";
		$this->printPageFooter();
	} 

	function _handlerModify()
	{
		$this->_checkModifyForm();
		if (TRUE == count($this->errors)) $this->_printOutPage();
		else
		{
			$this->_input['_POST'] = $this->escapeSqlCharsFromData($this->_input['_POST']);
			$query_string = "update {$this->_dbop->prefix}global set
				siteSubName='" . $this->_input['_POST']['siteSubName'] . "',
				siteName='" . $this->_input['_POST']['exBlogName'] . "',
				siteUrl='" . $this->_input['_POST']['exBlogUrl'] . "',
				copyright='" . $this->_input['_POST']['exBlogCopyright'] . "',
				tmpURL='" . $this->_input['_POST']['templatesURL'] . "',
				langURL='" . $this->_input['_POST']['languageURL'] . "',
				activeRun='" . $this->_input['_POST']['activeRun'] . "',
				unactiveRunMessage='" . $this->_input['_POST']['unactiveRunMessage'] . "',
				isCountOnlineUser='" . $this->_input['_POST']['isCountOnlineUser'] . "',
				Description='" . $this->_input['_POST']['description'] . "',
				Webmaster='" . $this->_input['_POST']['webmaster'] . "',
				sitekeyword='" . $this->_input['_POST']['sitekeyword'] . "',
				GDswitch='" . $this->_input['_POST']['GDswitch'] . "',
				exurlon='" . $this->_input['_POST']['exurlon'] . "',
				summarynum={$this->_input['_POST']['summarynum']},
				alltitlenum={$this->_input['_POST']['alltitlenum']},
				listblognum={$this->_input['_POST']['listblognum']},
				listallnum={$this->_input['_POST']['listallnum']},
				RegisterSwitch='" . $this->_input['_POST']['RegisterSwitch'] . "',
				ACLSwitch='" . $this->_input['_POST']['ACLSwitch'] . "',
				SyslogSwitch='" . $this->_input['_POST']['SyslogSwitch'] . "'";
			if (TRUE == intval($this->_input['_POST']['SyslogSwitch'])) $query_string .= ",SyslogLocation='" . $this->_input['_POST']['SyslogLocation'] . "', SyslogLevel={$this->_input['_POST']['SyslogLevel']}";
			$this->_dbop->query($query_string); 
			// 重写配置
			$this->_reWriteConfig(); 
			// 重新载入系统配置
			$this->_loadSystemOptions();
			// $this->_printOutPage();
			$this->showMesg($this->_lang['other'][38], $_SERVER['PHP_SELF']);
		} 
	} 

	function _loadFromPost()
	{
		if (TRUE == count($this->_input['_POST']))
		{
			$this->_config['global']['siteSubName'] = $this->_input['_POST']['siteSubName'];
			$this->_config['global']["siteName"] = $this->_input['_POST']['exBlogName'];
			$this->_config['global']["siteUrl"] = $this->_input['_POST']['exBlogUrl'];
			$this->_config['global']["Webmaster"] = $this->_input['_POST']['webmaster'];
			$this->_config['global']["Description"] = $this->_input['_POST']['description'];
			$this->_config['global']["sitekeyword"] = $this->_input['_POST']['sitekeyword'];
			$this->_config['global']["copyright"] = $this->_input['_POST']['exBlogCopyright'];
			$this->_config['global']["activeRun"] = $this->_input['_POST']['activeRun'];
			$this->_config['global']["isCountOnlineUser"] = $this->_input['_POST']['isCountOnlineUser'];
			$this->_config['global']["GDswitch"] = $this->_input['_POST']['GDswitch'];
			$this->_config['global']["ACLSwitch"] = $this->_input['_POST']['ACLSwitch'];
			$this->_config['global']["SyslogSwitch"] = $this->_input['_POST']['SyslogSwitch'];
			$this->_config['global']["SyslogLocation"] = $this->_input['_POST']['SyslogLocation'];
			$this->_config['global']["SyslogLevel"] = $this->_input['_POST']['SyslogLevel'];
			$this->_config['global']["RegisterSwitch"] = $this->_input['_POST']['RegisterSwitch'];
			$this->_config['global']["exurlon"] = $this->_input['_POST']['exurlon'];
			$this->_config['global']["summarynum"] = $this->_input['_POST']['summarynum'];
			$this->_config['global']["alltitlenum" ] = $this->_input['_POST']['alltitlenum'];
			$this->_config['global']["listblognum"] = $this->_input['_POST']['listblognum'];
			$this->_config['global']["listallnum"] = $this->_input['_POST']['listallnum'];
			$this->_config['global']["unactiveRunMessage"] = $this->_input['_POST']['unactiveRunMessage'];
		} 
	} 

	function _checkModifyForm()
	{
		if (TRUE == empty($this->_input['_POST']['siteSubName'])) $this->errors['siteSubName'] = $this->_lang['other'][35];
		elseif (50 < strlen($this->_input['_POST']['siteSubName'])) $this->errors['siteSubName'] = sprintf($this->_lang['other'][36], 50);
		if (TRUE == empty($this->_input['_POST']['exBlogName'])) $this->errors['exBlogName'] = $this->_lang['other'][35];
		elseif (50 < strlen($this->_input['_POST']['exBlogName'])) $this->errors['exBlogName'] = sprintf($this->_lang['other'][36], 50);
		if (TRUE == empty($this->_input['_POST']['exBlogUrl'])) $this->errors['exBlogUrl'] = $this->_lang['other'][35];
		elseif (50 < strlen($this->_input['_POST']['exBlogUrl'])) $this->errors['exBlogUrl'] = sprintf($this->_lang['other'][36], 50);
		elseif (FALSE == preg_match("/^(http:\/){0,1}\/(.*)[^\/]$/", $this->_input['_POST']['exBlogUrl'])) $this->errors['exBlogUrl'] = $this->_lang['other'][37];
		if (TRUE == empty($this->_input['_POST']['sitekeyword'])) $this->_input['_POST']['sitekeyword'] = '';
		elseif (100 < strlen($this->_input['_POST']['sitekeyword'])) $this->errors['sitekeyword'] = sprintf($this->_lang['other'][36], 100);
		if (TRUE == empty($this->_input['_POST']['webmaster'])) $this->errors['webmaster'] = $this->_lang['other'][35];
		elseif (35 < strlen($this->_input['_POST']['webmaster'])) $this->errors['webmaster'] = sprintf($this->_lang['other'][36], 35);
		elseif (FALSE == $this->_isEmailAddress($this->_input['_POST']['webmaster'])) $this->errors['webmaster'] = $this->_lang['other'][37];
		if (TRUE == empty($this->_input['_POST']['description'])) $this->_input['_POST']['description'] = '';
		if (TRUE == empty($this->_input['_POST']['exBlogCopyright'])) $this->_input['_POST']['exBlogCopyright'] = '';
		$this->_checkFormBool('actoveRun');
		if (TRUE == empty($this->_input['_POST']['unactiveRunMessage'])) $this->_input['_POST']['unactiveRunMessage'] = '';
		$this->_checkFormNum('summarynum');
		$this->_checkFormNum('alltitlenum');
		$this->_checkFormNum('listblognum');
		$this->_checkFormNum('listallnum');
		$this->_checkFormBool('isCountOnlineUser');
		$this->_checkFormBool('GDswitch');
		$this->_checkFormBool('exurlon');
		$this->_checkFormBool('RegisterSwitch');
		$this->_checkFormBool('ACLSwitch');
		$this->_checkFormBool('SyslogSwitch');
		if (TRUE == isset($this->_input['_POST']['SyslogSwitch']) && TRUE == intval($this->_input['_POST']['SyslogSwitch'])) $this->_checkFormNum('SyslogLevel');
	} 

	function _checkFormNum($name)
	{
		if (FALSE == isset($this->_input['_POST'][$name])) $this->errors[$name] = $this->_lang['other'][35];
		elseif (FALSE == is_numeric($this->_input['_POST'][$name])) $this->errors[$name] = $this->_lang['other'][37];
		else $this->_input['_POST'][$name] = intval(trim($this->_input['_POST'][$name]));
	} 

	function _checkFormBool($name)
	{
		if (TRUE == empty($this->_input['_POST'][$name]) || "1" != $this->_input['_POST'][$name]) $this->_input['_POST'][$name] = 0;
		else $this->_input['_POST'][$name] = 1;
	} 

	function _reWriteConfig()
	{
		$parttern = "/\\\$exBlog\['charset'\] = \"([^\"]*?)\";.*/";
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
				include("../{$this->_input['_POST']['languageURL']}/public.php");
				$this->_lang['public'] = $langpublic;
				$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
				$buffer = "\$exBlog['charset'] = \"{$this->_lang['public']['charset']}\";";
			} elseif (TRUE == preg_match("/^\/\/\s+Generated\s+By\s+Installation\s+Guide/i", $buffer)) $buffer = $buffer . " | Modified By Configure Guide on " . date("r");
			if ('?>' != $buffer)$tmp .= $buffer . "\n";
		} 
		@flock($file_id, LOCK_UN);
		@fclose($file_id);

		$tmp .= "?" . ">\n";

		$file_id = @fopen($this->config_file, "wb");
		@flock($file_id, LOCK_EX);
		$result = @fwrite($file_id, $tmp);
		@flock($file_id, LOCK_SH);
		@fclose($file_id);

		return $result;
	} 
} 

new ModifySystemOptions();

?>
