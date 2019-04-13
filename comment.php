<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
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

define('MODULE_NAME', 'core-pub.comment');

require_once("./public.php");

class Comment extends PublicCommon
{
	var $errors = array();

	var $blog_id = 0;

	var $redirect_url = "";

	var $current_time = 0;

	function Comment()
	{
		$this->setModuleName(MODULE_NAME);

		$this->start_time = $this->microtime_float(); 
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig(); 
		// 装入系统配置
		$this->_loadSystemOptions(); 
		// 装入语言文件
		include_once("./{$this->_config['global']['langURL']}/public.php");

		$this->_lang['public'] = $langpublic;

		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun); 
		// 装入功能对应语言文件
		include_once("./{$this->_config['global']['langURL']}/comment.php");

		$this->_lang['comment'] = $lang;

		$this->_getVariables();

		$this->redirect_url = $_SERVER['HTTP_REFERER'];

		$this->_getCurrentTime();

		$this->_initHtml();

		if (FALSE == empty($this->_input['id']) && TRUE == is_numeric($this->_input['id'])) $this->blog_id = intval($this->_input['id']);

		if (FALSE == empty($this->_input['_POST']['action']) && 'add_replay' == $this->_input['_POST']['action']) $this->addComment();

		$this->end_time = $this->microtime_float();

		$this->_html->assign("end_time", $this->end_time);

		$this->_html->display($this->template_name);

		if (TRUE == count($this->errors)) $this->logop->fetchMessage($this->errors, 1);

		$this->_destroyEnv();
	} 

	function getCommentsList()
	{
	} 

	function addComment()
	{
		$this->checkForm();

		if (TRUE == count($this->errors))
		{
			$this->_html->assign("messages", $this->errors);

			$this->template_name = "errors.tpl";
		} 

		else
		{ 
			// Get information about a blog
			$this->blog_info = $this->_getLatestRecords("blog", 1, "", $conditions = "id={$this->blog_id}", $fields_list = "sort", 0, TRUE);

			if (TRUE == is_array($this->blog_info) && TRUE == count($this->blog_info))
			{ 
				// insert a new record into table comment
				@session_start();

				$query_string = "insert into {$this->_dbop->prefix}comment (commentID, commentSort, author, author_id, email, homepage, content, addtime, ipaddress) values ({$this->blog_id}, '{$this->blog_info['sort']}', '{$this->_input['_POST']['name']}', {$this->_input['_SESSION']['userID']}, '{$this->_input['_POST']['email']}', '{$this->_input['_POST']['homepage']}', '{$this->_input['_POST']['content']}', '" . time() . "', '" . $this->_getClientIp() . "')";

				$this->_dbop->query($query_string);

				$this->_dbop->freeResult(); 
				// Update state information
				$query_string = "update {$this->_dbop->prefix}global set commentCount=(select count(*) from {$this->_dbop->prefix}comment);;update {$this->_dbop->prefix}sort set commentCount=(select count(*) from {$this->_dbop->prefix}comment where commentSort={$this->blog_info['sort']}) where id={$this->blog_info['sort']}";

				$this->_dbop->query($query_string);

				$this->_dbop->freeResult(); 
				// Update state for a user
				$query_string = "update {$this->_dbop->prefix}admin set commentCount=commentCount+1, connectionCount=connectionCount+1, ipaddress='" . $this->_getClientIp() . "', lastvisit='" . time() . "' where id={$this->_input['_SESSION']['userID']}";

				$this->_dbop->query($query_string);

				$this->_dbop->freeResult(); 
				// Update state for a blog
				$query_string = "update {$this->_dbop->prefix}blog set commentCount=(select count(*) from {$this->_dbop->prefix}comment where commentID={$this->blog_id}) where id={$this->blog_id}";

				$this->_dbop->query($query_string);

				$this->_dbop->freeResult();
			} 

			header("location: {$this->redirect_url}");
		} 
	} 

	function checkForm()
	{
		@session_start(); 
		// not login?
		if (TRUE == empty($this->_input['_SESSION']['exPass']))
		{
			if (FALSE == empty($this->_input['_SESSION']['exPass'])) $this->_input['_POST']['name'] = $this->_input['_SESSION']['exPass'];

			if (TRUE == empty($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['comment'][0];

			elseif (20 < strlen($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['comment'][1];
			if (TRUE == empty($this->_input['_POST']['password']))/*$this->errors[] = $this->_lang['comment'][2];*/ $this->_input['_POST']['password'] = '';
			if (FALSE == empty($this->_input['_SESSION']['exUseremail'])) $this->_input['_POST']['email'] = $this->_input['_SESSION']['exUseremail'];

			if (TRUE == empty($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['comment'][3];

			elseif (FALSE == $this->_isEmailAddress($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['comment'][4];

			elseif (35 < strlen($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['comment'][5];

			if (FALSE == empty($this->_input['_POST']['homepage']))
			{
				if (FALSE == preg_match("/^http:\/\/\w+[\w\.\-\/]+[^.]$/i", $this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['comment'][6];

				elseif (100 < strlen($this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['comment'][7];
			} 

			else $this->_input['_POST']['homepage'] = '';

			if (FALSE == empty($this->_input['_POST']['register']) && 'yes' == $this->_input['_POST']['register']) $this->_addMemberInfo();

			else $member_info = $this->_getMemberInfo("user='{$this->_input['_POST']['name']}' and password='" . md5($this->_input['_POST']['password']) . "'", "user='{$this->_input['_POST']['name']}'");
		} 

		else
		{
			$member_info = $this->_getMemberInfo("id={$this->_input['_SESSION']['userID']}");

			if (FALSE == count($member_info)) $this->errors[] = $this->_lang['comment'][8];

			else
			{
				$this->_input['_POST']['name'] = $member_info['user'];

				$this->_input['_POST']['email'] = $member_info['email'];

				$this->_input['_POST']['homepage'] = $member_info['homepage'];
			} 
		} 

		if (FALSE == isset($this->_input['_POST']['content'])) $this->_input['_POST']['content'] = '';

		$this->_input['_POST']['content'] = trim($this->_input['_POST']['content']);

		if (TRUE == empty($this->_input['_POST']['content']))
		{
			$this->errors[] = $this->_lang['comment'][10];
		} 

		else
		{
			include_once("./include/ubbcode.php");

			$ubbop = new UbbCode();

			$this->_input['_POST']['content'] = $ubbop->parse(htmlspecialchars($this->_input['_POST']['content'], ENT_QUOTES));

			unset($ubbop);
		} 

		$this->_input = $this->escapeSqlCharsFromData($this->_input);
	} 

	function _getMemberInfo($conditions, $conditions_added)
	{
		$query_string = "select * from {$this->_dbop->prefix}admin where $conditions";

		$this->_dbop->query($query_string);

		if (TRUE == $this->_dbop->getNumRows())
		{
			$member_info = $this->_dbop->fetchArray(0, 'ASSOC'); 
			// Save information about a user
			/*			setcookie("exBlogUser", "", time()-3600);

			setcookie("exBlogUser", "{$member_info['user']}||{$member_info['email']}", time()+86400);

*/

			@session_start();

			/*			session_unregister("exPass");

			session_unregister("exPassword");

			session_unregister("userID");

*/ $this->_input['_SESSION']['exPass'] = $member_info['user'];

			$this->_input['_SESSION']['userID'] = $member_info['id'];

			$this->_input['_SESSION']['exUseremail'] = $member_info['email'];

			$this->_input['_SESSION']['exUserlastvisit'] = $member_info['lastvisit'];

			$this->_input['_SESSION']['exUserIpAddress'] = $member_info['ipaddress'];

			$this->_input['_SESSION']['userLevel'] = $member_info['uid'];
			@session_write_close();
		} 

		else
		{
			/*			$member_info = array();

			$this->errors[] = $this->_lang['comment'][8];

*/
			$this->_dbop->freeResult();
			$query_string = "select * from {$this->_dbop->prefix}admin where $conditions_added";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['comment'][8];
			else
			{
				$member_info = array('id' => 0,
					'uid' => 99,
					'user' => (TRUE == empty($this->_input['_POST']['name']) ? $this->_input['_SESSION']['exPass'] : $this->_input['_POST']['name']),
					'password' => '',
					'email' => (TRUE == empty($this->_input['_POST']['email']) ? $this->_input['_SESSION']['exUseremail'] : $this->_input['_POST']['email']),
					'phone' => '0',
					'blogCount' => 0,
					'commentCount' => 0,
					'connectionCount' => 0,
					'homepage' => '',
					'showEmail' => 'visible',
					'ipaddress' => '255.255.255.255',
					'lastvisit' => time()
					);
				@session_start();
				if (FALSE == empty($this->_input['_POST']['name'])) $this->_input['_SESSION']['exPass'] = $this->_input['_POST']['name'];

				$this->_input['_SESSION']['userID'] = 0;

				if (FALSE == empty($this->_input['_POST']['email'])) $this->_input['_SESSION']['exUseremail'] = $this->_input['_POST']['email'];

				$this->_input['_SESSION']['exUserlastvisit'] = time();

				$this->_input['_SESSION']['exUserIpAddress'] = '255.255.255.255';

				$this->_input['_SESSION']['userLevel'] = 99;
			} 
		} 

		$this->_dbop->freeResult();

		return $member_info;
	} 

	function _addMemberInfo()
	{
		$member_id = 0;

		if ('1' == $this->_config['global']['RegisterSwitch'])
		{ 
			// Was this a member with same name existed?
			$query_string = "select user,email from {$this->_dbop->prefix}admin where user='{$this->_input['_POST']['name']}' or email='{$this->_input['_POST']['email']}";

			$this->_dbop->query($query_string);

			$result = $this->_dbop->getNumRows();

			$this->_dbop->freeResult();

			if (TRUE == $result) $this->errors[] = $this->_lang['comment'][9];

			else
			{
				$query_string = "insert into {$this->_dbop->prefix}admin (uid, user, password, email, phone, blogCount, commentCount, connectionCount, homepage, showEmail, ipaddress, lastvisit) values (2, '{$this->_input['_POST']['name']}', '" . md5($this->_input['_POST']['password']) . "', '{$this->_input['_POST']['email']}', '0', 0, 0, 0, '{$this->_input['_POST']['homepage']}', 'escape', '255.255.255.255', '0')";

				$this->_dbop->query($query_string);

				$member_id = $this->_dbop->getInsertId();

				$this->_dbop->freeResult(); 
				// Save information about a user
				setcookie("exBlogUser", "", time()-3600);

				setcookie("exBlogUser", "{$member_info['user']}||{$member_info['email']}", time() + 86400);

				@session_start();

				session_unregister("exPass");

				session_unregister("exPassword");

				session_unregister("userID");

				$this->_input['_SESSION']['exPass'] = $this->_input['_POST']['name'];

				$this->_input['_SESSION']['userID'] = $member_id;

				$this->_input['_SESSION']['exUseremail'] = $this->_input['_POST']['email'];

				$this->_input['_SESSION']['exUserlastvisit'] = 0;

				$this->_input['_SESSION']['exUserIpAddress'] = '255.255.255.255';

				$this->_input['_SESSION']['userLevel'] = 2;
				@session_write_close();
			} 
		} 

		else
		{
			$member_id = 0; 
			// Save information about a user
			setcookie("exBlogUser", "", time()-3600);

			setcookie("exBlogUser", "{$this->_input['_POST']['name']}||{$this->_input['_POST']['email']}", time() + 86400);
			@session_start();
			$this->_input['_SESSION']['exPass'] = $this->_input['_POST']['name'];

			$this->_input['_SESSION']['userID'] = 0;

			$this->_input['_SESSION']['exUseremail'] = $this->_input['_POST']['email'];

			$this->_input['_SESSION']['exUserlastvisit'] = time();

			$this->_input['_SESSION']['exUserIpAddress'] = '255.255.255.255';

			$this->_input['_SESSION']['userLevel'] = 99;
			@session_write_close();
		} ;

		return $member_id;
	} 
} 

new Comment();
?>