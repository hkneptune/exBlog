<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * User manager for exBlog
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

define('MODULE_NAME', 'core-adm.edituser');

require_once("./public.php");

class EditUser extends CommonLib
{
	var $errors = array();
	var $user_id = 0;
	var $user_levels = array(0, 1, 3);

	function EditUser()
	{
		$this->_initEnv(MODULE_NAME, 'edituser', 4);

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->user_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->user_id = intval($this->_input['_GET']['id']);

		$this->user_level_titles = array($this->_lang['edituser'][19], $this->_lang['edituser'][20], $this->_lang['edituser'][25]);

		if (FALSE == empty($this->_input['_POST']['action']))
		{
			switch ($this->_input['_POST']['action'])
			{
				case 'add' : $this->_addUser();
					break;
				case 'modify' : $this->_modifyUserInfo();
					break;
				default : $this->_printUsersList();
			} 
		} elseif (FALSE == empty($this->_input['_GET']['action']))
		{
			switch ($this->_input['_GET']['action'])
			{
				case 'add' :
				case 'edit' : $this->_printModifyForm();
					break;
				case 'view' : $this->_printUserInfo();
					break;
				case 'delete' : $this->_deleteUser();
					break;
				default : $this->_printUsersList();
			} 
		} 
		else $this->_printUsersList();

		$this->_destroyEnv();
	} 

	function _printUsersList()
	{
		$users_list = $this->_getUsersList();
		$this->printPageHeader();

		echo "
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\" valign=\"top\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=5 height=25 align=\"center\"><b>{$this->_lang['edituser'][0]}</b></td></tr>
    <tr>
      <td>{$this->_lang['edituser'][27]}</td>
      <td align=\"center\">{$this->_lang['edituser'][28]}</td>
      <td align=\"center\">{$this->_lang['edituser'][29]}</td>
      <td align=\"center\">{$this->_lang['edituser'][30]}</td>
      <td>&nbsp;</td>
    </tr>
		";

		for ($i = 0; $i < count($users_list); $i++)
		{
			echo "
    <tr>
      <td><a href={$_SERVER['PHP_SELF']}?action=view&id={$users_list[$i]['id']}>{$users_list[$i]['user']}</a></td>
      <td align=\"center\">" . $this->_printEmailAddress($users_list[$i]['email'], $users_list[$i]['showEmail']) . "</td>
      <td align=\"center\">" . $this->_getLevelName($users_list[$i]['uid']) . "</td>
      <td align=\"center\">" . date('Y-m-d H:i:s', intval($users_list[$i]['lastvisit'])) . "</td>
      <td align=\"center\" nowrap><a href={$_SERVER['PHP_SELF']}?action=edit&id={$users_list[$i]['id']}>{$this->_lang['edituser'][24]}</a>|<a href={$_SERVER['PHP_SELF']}?action=delete&id={$users_list[$i]['id']}>{$this->_lang['edituser'][7]}</a></td>
    </tr>
			";
		} 

		echo "</table></td></tr></table>";

		$this->printPageFooter();
	} 

	function _printModifyForm()
	{
		$user_info = $this->_getUserInfo();
		$this->printPageHeader();
		echo "
<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\" valign=\"top\">
    <form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"95%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>" . ((FALSE == empty($this->user_id)) ? $this->_lang['edituser'][8] : $this->_lang['edituser'][22]) . "</b></td></tr>
      <tr><td width=\"20%\" align=\"center\">{$this->_lang['edituser'][9]}</td><td width=\"80%\"><input name=\"user\" type=\"text\" class=\"input\" value=\"" . ((FALSE == empty($this->user_id)) ? $user_info['user'] : '') . "\"></td></tr>
      <tr><td align=\"center\">{$this->_lang['edituser'][10]}</td><td><input name=\"password\" type=\"password\" class=\"input\"></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['edituser'][12]}</td><td><input name=\"password_2\" type=\"password\" class=\"input\"></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['edituser'][13]}</td><td><input name=\"email\" type=\"text\" class=\"input\" value=\"" . ((FALSE == empty($this->user_id)) ? $user_info['email'] : '') . "\"></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['edituser'][42]}</td><td><select name=\"show_email\" class=\"botton\">
        <option value=\"hidden\" " . ((FALSE == empty($this->user_id) && 'hidden' === $user_info['showEmail']) ? " selected" : '') . ">{$this->_lang['edituser'][43]}</option>
        <option value=\"escape\" " . ((FALSE == empty($this->user_id) && 'escape' === $user_info['showEmail']) ? " selected" : '') . ">{$this->_lang['edituser'][44]}</option>
        <option value=\"visible\" " . ((FALSE == empty($this->user_id) && 'visible' === $user_info['showEmail']) ? " selected" : '') . ">{$this->_lang['edituser'][45]}</option>
      </select></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['edituser'][38]}</td><td><input name=\"homepage\" type=\"text\" class=\"input\" value=\"" . ((FALSE == empty($this->user_id)) ? $user_info['homepage'] : '') . "\"></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['edituser'][14]}</td><td><input name=\"phone\" type=\"text\" class=\"input\" title=\"{$this->_lang['edituser'][15]}\" value=\"" . ((FALSE == empty($this->user_id)) ? $user_info['phone'] : '') . "\"> <font color=\"#FF0000\">{$this->_lang['edituser'][16]}</font></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['edituser'][17]}</td><td><select name=\"exUid\" class=\"botton\">
		";

		for ($i = 0; $i < count($this->user_levels); $i++)
		{
			@session_start();
			if ($_SESSION['userLevel'] <= $this->user_levels[$i]) echo "<option value=\"{$this->user_levels[$i]}\" " . ((FALSE == empty($this->user_id) && $this->user_levels[$i] === intval($user_info['uid'])) ? " selected" : '') . ">{$this->user_level_titles[$i]}</option>";
		} 

		echo "
      </select></td></tr>
      <tr align=\"center\"><td height=\"30\" colspan=\"2\">
		";

		if (FALSE == empty($this->user_id)) echo "<input type=\"hidden\" name=\"action\" value=\"modify\"><input type=\"hidden\" name=\"id\" value=\"{$this->user_id}\"><input type=\"submit\" class=\"botton\" value=\"{$this->_lang['edituser'][21]}\">";
		else echo "<input type=\"hidden\" name=\"action\" value=\"add\"><input type=\"submit\" class=\"botton\" value=\"{$this->_lang['edituser'][23]}\">";

		echo "
      </td></tr></form>
      <tr><td colspan=\"2\">&nbsp;</td></tr>
    </table>
  </td></tr>
</table>
		";

		$this->printPageFooter();
	} 

	function _printUserInfo()
	{
		$user_info = $this->_getUserInfo();

		if (FALSE == empty($this->user_id))
		{
			$this->printPageHeader();

			echo "
<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\" valign=\"top\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"95%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>" . sprintf($this->_lang['edituser'][31], $user_info['user']) . "</b></td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][32]}&nbsp;</td><td width=75%>{$user_info['id']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][9]}&nbsp;</td><td width=75%>{$user_info['user']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][13]}&nbsp;</td><td width=75%>" . $this->_printEmailAddress($user_info['email'], $user_info['showEmail']) . "</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][38]}&nbsp;</td><td width=75%>{$user_info['homepage']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][14]}&nbsp;</td><td width=75%>{$user_info['phone']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][33]}&nbsp;</td><td width=75%>{$user_info['ipaddress']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][34]}&nbsp;</td><td width=75%>" . date('Y-m-d H:i:s', $user_info['lastvisit']) . "</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][35]}&nbsp;</td><td width=75%>{$user_info['blogCount']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][36]}&nbsp;</td><td width=75%>{$user_info['commentCount']}</td></tr>
      <tr><td width=25% align=right>{$this->_lang['edituser'][37]}&nbsp;</td><td width=75%>{$user_info['connectionCount']}</td></tr>
      <tr><td colspan=\"2\" align=center>
      <hr size=1 width=90% noshadow>
      <input type=button value=\"" . sprintf($this->_lang['edituser'][39], $user_info['user']) . "\" class=\"botton\" onClick=\"location.href='{$_SERVER['PHP_SELF']}?action=edit&id={$user_info['id']}';\">&nbsp; &nbsp; &nbsp;
      <input type=button value=\"{$this->_lang['edituser'][40]}\" onClick='history.back();' class=\"botton\">
      </td></tr>
    </table>
  </td></tr>
</table>
			";

			$this->printPageFooter();
		} 
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	} 
	// 添加用户
	function _addUser()
	{
		if (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['edituser'][64], $_SERVER['PHP_SELF']);
		$this->_checkModifyForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']); 
		// 检查同名用户是否存在
		$query_string = "select * from {$this->_dbop->prefix}admin where user='{$this->_input['_POST']['user']}'";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['edituser'][58];
		$this->_dbop->freeResult(); 
		// 添加用户
		if (FALSE == count($this->errors))
		{
			$query_string = "insert into {$this->_dbop->prefix}admin (
				uid, user, password, email, phone, blogCount, commentCount, connectionCount, homepage, showEmail, ipaddress, lastvisit
				) values (
				{$this->_input['_POST']['exUid']}, '{$this->_input['_POST']['user']}', '" . md5($this->_input['_POST']['password']) . "',
				'{$this->_input['_POST']['email']}', '{$this->_input['_POST']['phone']}', 0, 0, 0, '{$this->_input['_POST']['homepage']}',
				'{$this->_input['_POST']['show_email']}', '255.255.255.255', '0'
				)";
			$this->_dbop->query($query_string);
			$query_string = "update {$this->_dbop->prefix}global set userCount=userCount+1";
			$this->_dbop->query($query_string);
			$this->showMesg(sprintf($this->_lang['edituser'][59], $this->_input['_POST']['user']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	} 
	// 修改指定用户信息
	function _modifyUserInfo()
	{
		$this->_checkModifyForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']); 
		// 获取用户的老信息
		$old_user_info = $this->_getUserInfo();
		if (FALSE == count($this->errors))
		{
			@session_start();
			if ($this->user_id != $_SESSION['userID'] && $old_user_info['uid'] <= $_SESSION['userLevel']) $this->_showError($this->_lang['public'][64], $_SERVER['PHP_SELF']);
			$query_string = "update {$this->_dbop->prefix}admin set ";
			if (FALSE == empty($this->_input['_POST']['password'])) $query_string .= "password='" . md5($this->_input['_POST']['password']) . "',";
			$query_string .= "user='{$this->_input['_POST']['user']}',
				email='{$this->_input['_POST']['email']}',
 				phone='{$this->_input['_POST']['phone']}',
				homepage='{$this->_input['_POST']['homepage']}',
				showEmail='{$this->_input['_POST']['show_email']}',
				uid={$this->_input['_POST']['exUid']}
				where id={$this->user_id}";
			$this->_dbop->query($query_string);

			@session_start();
			if ($this->user_id == $_SESSION['userID']) $this->_flushPrivilege();

			$this->showMesg(sprintf($this->_lang['edituser'][41], $old_user_info['user']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	} 
	// 删除用户
	function _deleteUser()
	{ 
		// 获取用户的老信息
		$old_user_info = $this->_getUserInfo(); 
		// 检查系统用户是否为最后一个
		$query_string = "select id from {$this->_dbop->prefix}admin where id<>{$this->user_id} and uid=0";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['edituser'][62];
		$this->_dbop->freeResult();

		if (FALSE == count($this->errors))
		{
			$query_string = "delete from {$this->_dbop->prefix}admin where id={$this->user_id}";
			$this->_dbop->query($query_string);
			$query_string = "update {$this->_dbop->prefix}global set userCount=userCount-1";
			$this->_dbop->query($query_string); 
			// 如果删除的是本用户账号
			@session_start();
			if (FALSE == empty($_SESSION['userID']) && $_SESSION['userID'] == $this->user_id)
			{
				session_unregister("exPass");
				session_unregister("exPassword");
				session_unregister("userID");
				session_destroy();
			} 
			$this->showMesg(sprintf($this->_lang['edituser'][63], $old_user_info['user']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	} 
	// 检查POST表单数据
	function _checkModifyForm()
	{ 
		// 屏蔽非法字符
		$this->escapeSqlCharsFromData($this->_input); 
		// 用户名检查
		if (TRUE == empty($this->_input['_POST']['user'])) $this->errors[] = $this->_lang['edituser'][46]; 
		// Disable all characters else word characters
		/* Comment it for adding other users
		elseif (FALSE == preg_match("/^[a-zA-Z]\w+$/i", $this->_input['_POST']['user'])) $this->errors[] = $this->_lang['edituser'][47];
		*/
		elseif (20 < strlen($this->_input['_POST']['user'])) $this->errors[] = $this->_lang['edituser'][48];
		if (FALSE == empty($this->_input['_POST']['password']))
		{
			if (TRUE == empty($this->_input['_POST']['password_2'])) $this->errors[] = $this->_lang['edituser'][49];
			elseif ($this->_input['_POST']['password'] != $this->_input['_POST']['password_2']) $this->errors[] = $this->_lang['edituser'][50];
			elseif (6 > strlen($this->_input['_POST']['password'])) $this->errors[] = $this->_lang['edituser'][60];
		} elseif (FALSE == empty($this->_input['_POST']['action']) && 'add' == $this->_input['_POST']['action']) $this->errors[] = $this->_lang['edituser'][61];
		if (TRUE == empty($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['edituser'][51];
		elseif (FALSE == $this->_isEmailAddress($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['edituser'][52];
		elseif (35 < strlen($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['edituser'][53];
		if (FALSE == empty($this->_input['_POST']['phone']) && FALSE == preg_match("/^13\d{9}$/", $this->_input['_POST']['phone'])) $this->errors[] = $this->_lang['edituser'][54];
		else $this->_input['_POST']['phone'] = '';
		if (FALSE == empty($this->_input['_POST']['homepage']))
		{
			if (FALSE == preg_match("/^http:\/\/\w+[\w\.\-\/]+[^.]$/i", $this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['edituser'][55];
			elseif (100 < strlen($this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['edituser'][56];
		} 
		else $this->_input['_POST']['homepage'] = '';
		if (TRUE == empty($this->_input['_POST']['show_email']) || FALSE == in_array($this->_input['_POST']['show_email'], array('hidden', 'escape', 'visible'))) $this->_input['_POST']['show_email'] = 'escape'; 
		// 用户权限级别
		@session_start();
		if (FALSE == isset($this->_input['_POST']['exUid']) || FALSE == in_array(intval($this->_input['_POST']['exUid']), $this->user_levels)) $this->errors[] = $this->_lang['edituser'][57];
		elseif (intval($this->_input['_POST']['exUid']) < $_SESSION['userLevel']) $this->errors[] = $this->_lang['edituser'][64];
	} 

	function _getUsersList($user_level = 0)
	{
		@session_start();
		$users_list = array();
		if (TRUE == empty($user_id)) $user_id = $_SESSION['userLevel'];
		$query_string = "select * from {$this->_dbop->prefix}admin where id={$_SESSION['userID']} or uid>$user_id";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $users_list = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		return $users_list;
	} 

	function _getUserInfo()
	{
		$user_info = array();
		if (FALSE == empty($this->user_id))
		{
			$query_string = "select * from {$this->_dbop->prefix}admin where id={$this->user_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $user_info = $this->_dbop->fetchArray(0, 'ASSOC');
			else
			{
				$this->errors[] = $this->_lang['edituser'][26];
				$this->user_id = 0;
			} 
			$this->_dbop->freeResult();
		} 
		return $user_info;
	} 

	function _getLevelName($user_level)
	{
		switch ($user_level)
		{
			case '0' : $level_name = $this->_lang['edituser'][19];
				break;
			case '1' : $level_name = $this->_lang['edituser'][20];
				break;
			case '3' : $level_name = $this->_lang['edituser'][25];
				break;
			default : $level_name = '';
				break;
		} 
		return $level_name;
	} 
} 

new EditUser();

?>
