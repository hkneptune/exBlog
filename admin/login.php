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
 *  File: login.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-26 18:19
 *  Homepage: www.exblog.net
 *
 * $Id$
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once("./public.php");

class Login extends CommonLib
{
	var $errors = array();

	function Login()
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
		include_once("../{$this->_config['global']['langURL']}/login.php");
		$this->_lang['login'] = $lang;

		// 输入数据
		$this->_input['_POST'] = $_POST;

		@session_start();
		if (TRUE == session_is_registered("exPass") && TRUE == session_is_registered("userID") && TRUE == session_is_registered("exPassword")) header("Location: frame.php");

		if (FALSE == empty($this->_input['_POST']['action']) && 'actlogin' == $this->_input['_POST']['action']) $this->_loginHandler();
		else $this->_printLoginPage();

		exit();
	}

	function _loginHandler()
	{
		$this->_checkPostForm();
		if (FALSE == count($this->errors))
		{
			$this->_input['_POST'] = $this->escapeSqlCharsFromData($this->_input['_POST']);
			$passwd_encrypted = md5($this->_input['_POST']['passwd']);
			$query_string = "select * from {$this->_dbop->prefix}admin where user='" . $this->_input['_POST']['user'] . "' and password='$passwd_encrypted'";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['public'][18];
			else
			{
				$member_info = $this->_dbop->fetchArray();

				$query_string = "update {$this->_dbop->prefix}admin set connectionCount=connectionCount+1, ipaddress='" . $this->_getClientIp() . "', lastvisit='" . time() . "' where id={$member_info['id']}";
				$this->_dbop->query($query_string);

				setcookie("exBlogUser", "", time()-3600);
				setcookie("exBlogUser", "{$member_info['user']}||{$member_info['email']}", time()+86400);

				@session_start();
				session_unregister("exPass");
				session_unregister("exPassword");
				session_unregister("userID");
				$_SESSION['exPass'] = $member_info['user'];
				$_SESSION['exPassword'] = $this->_input['_POST']['passwd'];
				$_SESSION['userID'] = $member_info['id'];
				$_SESSION['exUseremail'] = $member_info['email'];
				$_SESSION['exUserlastvisit'] = $member_info['lastvisit'];
				$_SESSION['exUserIpAddress'] = $member_info['ipaddress'];
				$_SESSION['userLevel'] = $member_info['uid'];
			}
			$this->_dbop->freeResult();

			if (FALSE == count($this->errors))
			{
				if (TRUE == empty($_SERVER['HTTP_REFERER'])) header("Location: frame.php");
				else header("Location: {$_SERVER['HTTP_REFERER']}");
			}
			else $this->_showError($this->errors);
		}
		else $this->_showError($this->errors);
	}

	function _printLoginPage()
	{
		$this->printPageHeader();

		echo "<p>&nbsp;</p><form method='post' action=\"{$_SERVER['PHP_SELF']}\"><table width='361' border='0' align='center' cellpadding=10 cellspacing=10 class='border'><tr bgcolor='#99CC66'><td align='center' bgcolor='#FFFFFF' class='mycss'><table width='310' border='0' align='center' cellpadding='2' cellspacing='4' class='main'><tr><td width='19%' rowspan='7'><img src='../images/user_login.jpg' alt='Admin Logining...' width='60' height='70'></td><td colspan='2' class='menu'><div align='center'>{$this->_lang['login'][1]}</td></tr><tr><td>{$this->_lang['login'][2]}</td><td> <input name='user' type='text' maxlength='20' class='input'></td></tr><tr><td>{$this->_lang['login'][3]}</td><td><input name='passwd' type='password' maxlength='20' class='input'></td></tr>";

		if ('1' == $this->_config['global']['GDswitch'])
			echo"<tr><td>{$this->_lang['login'][4]}</td><td><input name=\"imgVal\" type=\"text\" size=\"5\" class=\"input\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img src=\"../include/VerifyImage.php?type=login\" align=\"absmiddle\"></td></tr>";

		echo "<tr><td colspan='2'><div align='center'><input type='hidden' name='action' value='actlogin'><input type='submit' value=\"{$this->_lang['login'][5]}\" class='botton'> &nbsp;&nbsp;&nbsp;&nbsp; <input type='reset' value=\"{$this->_lang['login'][6]}\" class='botton'></div></td></tr><tr><td colspan='2' align='center'><a href='../index.php'>{$this->_lang['login'][7]}</a></td></tr></table></td></tr></table></form>";

		$this->printPageFooter();
	}

	function _checkPostForm()
	{
		if (TRUE == empty($this->_input['_POST']['user'])) $this->errors[] = $this->_lang['public'][1];
		elseif (15 < strlen($this->_input['_POST']['user'])) $this->errors[] = $this->_lang['public'][0];
		elseif (FALSE == preg_match("/^[a-zA-Z0-9]\w+$/", $this->_input['_POST']['user'])) $this->errors[] = $this->_lang['public'][2];
		if (TRUE == empty($this->_input['_POST']['passwd'])) $this->errors[] = $this->_lang['public'][6];
		elseif (6 > strlen($this->_input['_POST']['passwd'])) $this->errors[] = $this->_lang['public'][8];
		if ($this->_config['global']['GDswitch'] == '1')
		{
			if (TRUE == empty($this->_input['_POST']['imgVal'])) $this->errors[] = $this->_lang['public'][72];
			else
			{
				@session_start();
				if (TRUE == empty($_SESSION['verify_string']['login']) || $this->_input['_POST']['imgVal'] != $_SESSION['verify_string']['login']) $this->errors[] = $this->_lang['public'][73];
			}
		}
	}

	function _getClientIp()
	{
		if (FALSE == empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
		elseif (FALSE == empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
		else return $_SERVER['REMOTE_ADDR'];
	}
}

new Login();
?>