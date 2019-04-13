<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Modify about information for exBlog
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

define("MODULE_NAME", "core-adm.editabout");

require_once("./public.php");

class About extends CommonLib
{
	var $errors = array();

	function About()
	{
		$this->_initEnv(MODULE_NAME, 'editabout', 0); 
		// 获取关于信息
		if (FALSE == $this->_getAboutInfo()) $this->_showError($this->_lang['editabout'][10]);

		if (FALSE == empty($this->_input['_POST']['action']) && 'gogogo' == $this->_input['_POST']['action']) $this->_updateAboutInfo();
		else $this->_printEditForm();

		$this->_destroyEnv();
	} 

	function _printEditForm()
	{
		$this->printPageHeader();
		echo "
<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
<table width=500 border=\"0\" cellpadding=0 cellspacing=0 class=\"border\">
  <tr bgcolor=\"#99CC66\">
    <td bgcolor=\"#FFFFFF\">
      <table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"main\">
        <tr><td colspan=\"2\" class=\"menu\"><div align=\"center\">{$this->_lang['editabout'][0]}</div></td></tr>
        <tr><td width=\"9%\" valign=\"top\" align=\"right\">{$this->_lang['editabout'][1]}</td><td width=\"80%\"> <input name=\"name\" type=\"text\" value=\"{$this->about_info['name']}\" class=\"input\"></td></tr>
        <tr><td valign=\"top\" align=\"right\">{$this->_lang['editabout'][2]}</td><td><input name=\"age\" type=\"text\" class=\"input\" id=\"age\" value=\"{$this->about_info['age']}\" size=\"5\"></td></tr>
        <tr><td valign=\"top\" align=\"right\">{$this->_lang['editabout'][3]}</td><td><input name=\"email\" type=\"text\" class=\"input\" value=\"{$this->about_info['email']}\" size=\"30\"></td></tr>
        <tr><td valign=\"top\" align=\"right\">{$this->_lang['editabout'][4]}</td><td><input name=\"qq\" type=\"text\" class=\"input\" id=\"qq\" value=\"{$this->about_info['qq']}\"></td></tr>
        <tr><td valign=\"top\" align=\"right\">{$this->_lang['editabout'][5]}</td><td><input name=\"icq\" type=\"text\" class=\"input\" id=\"icq\" value=\"{$this->about_info['icq']}\"></td></tr>
        <tr><td valign=\"top\" align=\"right\">{$this->_lang['editabout'][6]}</td><td><input name=\"msn\" type=\"text\" class=\"input\" id=\"msn\" value=\"{$this->about_info['msn']}\" size=\"30\"></td></tr>
        <tr><td valign=\"top\" nowrap>{$this->_lang['editabout'][7]}</td><td><textarea name=\"description\" cols=\"60\" rows=\"8\" wrap=\"VIRTUAL\" class=\"input\" id=\"description\">{$this->about_info['description']}</textarea></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan=\"2\"><div align=\"center\">
          <input type=\"hidden\" name=\"action\" value=\"gogogo\">
          <input type=\"submit\" value=\"{$this->_lang['editabout'][8]}\" class=\"botton\"> &nbsp;&nbsp;&nbsp;&nbsp; <input type=\"submit\" value=\"{$this->_lang['editabout'][9]}\" class=\"botton\">
        </div></td></tr>
      </table>
    </td>
  </tr>
</table>
</form>
		";
		$this->printPageFooter();
	} 

	function _getAboutInfo()
	{
		$query_string = "select * from {$this->_dbop->prefix}aboutme limit 0, 1";
		$this->_dbop->query($query_string);
		$result = $this->_dbop->fetchArray(0, "ASSOC");
		$this->_dbop->freeResult();
		if (FALSE == empty($result) && TRUE == is_array($result) && 0 < count($result)) $this->about_info = $result;
		else return FALSE;
		return $this->about_info;
	} 

	function _updateAboutInfo()
	{
		$this->_checkForm();
		if (0 < count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);
		else
		{
			$query_string = "update {$this->_dbop->prefix}aboutme set
					name='" . $this->_input['_POST']['name'] . "',
					age={$this->_input['_POST']['age']},
					email='" . $this->_input['_POST']['email'] . "',
					qq={$this->_input['_POST']['qq']},
					icq={$this->_input['_POST']['icq']},
					msn='" . $this->_input['_POST']['msn'] . "',
					description='" . $this->_input['_POST']['description'] . "'";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getAffectedRows()) $this->_showError($this->_lang['admin'][67], $_SERVER['PHP_SELF']);
			else $this->showMesg($this->_lang['admin'][68], $_SERVER['PHP_SELF']);
		} 
	} 

	function _checkForm()
	{
		if (TRUE == empty($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['editabout'][11];
		elseif (30 < strlen(trim($this->_input['_POST']['name']))) $this->errors[] = $this->_lang['editabout'][12];
		$this->_input['_POST']['name'] = trim($this->_input['_POST']['name']);
		if (TRUE == empty($this->_input['_POST']['age']) || FALSE == is_numeric($this->_input['_POST']['age'])) $this->_input['_POST']['age'] = 0;
		else $this->_input['_POST']['age'] = intval($this->_input['_POST']['age']);
		if (TRUE == empty($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['editabout'][13];
		elseif (FALSE == $this->_isEmailAddress(trim($this->_input['_POST']['email']))) $this->errors[] = $this->_lang['editabout'][14];
		else $this->_input['_POST']['email'] = trim($this->_input['_POST']['email']);
		if (TRUE == empty($this->_input['_POST']['qq']) || FALSE == is_numeric($this->_input['_POST']['qq'])) $this->_input['_POST']['qq'] = 0;
		else $this->_input['_POST']['qq'] = intval($this->_input['_POST']['qq']);
		if (TRUE == empty($this->_input['_POST']['icq']) || FALSE == is_numeric($this->_input['_POST']['icq'])) $this->_input['_POST']['icq'] = 0;
		else $this->_input['_POST']['icq'] = intval($this->_input['_POST']['icq']);
		if (TRUE == empty($this->_input['_POST']['msn'])) $this->_input['_POST']['msn'] = '';
		elseif (FALSE == $this->_isEmailAddress(trim($this->_input['_POST']['msn']))) $this->errors[] = $this->_lang['editabout'][15];
		else $this->_input['_POST']['msn'] = trim($this->_input['_POST']['msn']);
		if (TRUE == empty($this->_input['_POST']['description'])) $this->_input['_POST']['description'] = '';
	} 
} 

new About();

?>
