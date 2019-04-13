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

define('MODULE_NAME', 'core-pub.wap');

require_once("./public.php");
/**
 * 
 * @brief Wap extension
 * @author feeling <feeling@exblog.org> 
 * @date $Date$
 */
class Wap extends PublicCommon
{
	/**
	 * 
	 * @internal 
	 * @brief Error messages
	 */
	var $errors = array();
	var $start_time = 0.0;
	var $current_time = 0;

	/**
	 * 
	 * @brief Template filename
	 */
	var $template_name = 'wap.tpl';

	/**
	 * 
	 * @internal 
	 * @brief blog ID
	 */
	var $blog_id = 0;

	/**
	 * 
	 * @internal 
	 * @brief Sort ID
	 */
	var $sort_id = 0;

	function Wap()
	{
		$this->setModuleName(MODULE_NAME); 
		// 获取当前时间以微秒为单位的Unix时间戳
		$this->start_time = $this->microtime_float();
		$this->current_time = time(); 
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig(); 
		// 装入系统配置
		$this->_loadSystemOptions(); 
		// 装入语言文件
		include_once("./{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun); 
		// 装入功能对应语言文件
		include_once("./{$this->_config['global']['langURL']}/wap.php");
		$this->_lang['wap'] = $lang; 
		// 组合POST和GET数据
		$this->_getVariables();

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->blog_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->blog_id = intval($this->_input['_GET']['id']);
		if (FALSE == empty($this->_input['_POST']['sortid']) && TRUE == is_numeric($this->_input['_POST']['sortid'])) $this->sort_id = intval($this->_input['_POST']['sortid']);
		elseif (FALSE == empty($this->_input['_GET']['sortid']) && TRUE == is_numeric($this->_input['_GET']['sortid'])) $this->sort_id = intval($this->_input['_GET']['sortid']);

		$this->printContent();

		if (TRUE == count($this->errors)) $this->logop->fetchMessage($this->errors, 1);

		$this->_destroyEnv();
	} 

	function printContent()
	{
		header("Content-type: text/vnd.wap.wml");

		$this->_initHtml();

		$condition = "hidden='0'";
		if (FALSE == empty($this->sort_id)) $condition .= " and sort={$this->sort_id}";
		if (FALSE == empty($this->_input['_POST']['name'])) $this->_addComment();
		elseif (FALSE == empty($this->_input['_GET']['action']))
		{
			$this->_html->assign("action", $this->_input['_GET']['action']);
			if ('view' == $this->_input['_GET']['action'] && FALSE == empty($this->blog_id))
			{
				$this->_html->assign(
					array("card_id" => "showarticle",
						"article_info" => $this->_getLatestRecords("blog", 1, "", "id={$this->blog_id}", "*", 0, TRUE)
						)
					);
			} elseif ('addcomment' == $this->_input['_GET']['action'] && FALSE == empty($this->blog_id))
			{ 
				// Do Nothing
			} elseif ('viewcomment' == $this->_input['_GET']['action'] && FALSE == empty($this->blog_id)) $this->_html->assign(array("action" => "viewcomment", "comments_list" => $this->_getLatestRecords("comment", 10, "id desc", "commentID={$this->blog_id}", $fields_list = "*"), "card_id" => "comment"));
			elseif ('addblog' == $this->_input['_GET']['action'] && FALSE == empty($this->sort_id)) $this->_html->assign(array('action' => 'addblog'));
			else $this->_html->assign(array("action" => "list", "articles_list" => $this->_getLatestRecords("blog", 5, "id desc", $condition, $fields_list = "title, addtime, id"), "card_id" => "article", "sort_id" => $this->sort_id));
		} 
		else $this->_html->assign(array("action" => "list", "articles_list" => $this->_getLatestRecords("blog", 5, "id desc", $condition, $fields_list = "title, addtime, id"), "card_id" => "article", "sort_id" => $this->sort_id));

		$this->_html->display($this->template_name);
	} 

	function _addComment()
	{
		$this->_checkForm();
		if (TRUE == count($this->errors)) $this->_html->assign(array("action" => "error", "message" => $this->errors, "card_id" => "error"));
		else
		{ 
			// Get information about a blog
			$this->blog_info = $this->_getLatestRecords("blog", 1, "", $conditions = "id={$this->blog_id}", $fields_list = "sort", 0, TRUE);
			if (TRUE == is_array($this->blog_info) && TRUE == count($this->blog_info))
			{ 
				// insert a new record into table comment
				$query_string = "insert into {$this->_dbop->prefix}comment (commentID, commentSort, author, email, homepage, content, addtime, ipaddress) values ({$this->blog_id}, '{$this->blog_info['sort']}', '{$this->_input['_POST']['name']}', '{$this->_input['_POST']['email']}', '{$this->_input['_POST']['homepage']}', '{$this->_input['_POST']['content']}', '" . time() . "', '" . $this->_getClientIp() . "')";
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

				$this->_html->assign(array("action" => "message", "message" => $this->_lang['wap'][29], "card_id" => "success"));
			} 
			else $this->_html->assign(array("action" => "error", "message" => array($this->_lang['wap'][30]), "card_id" => "error"));
		} 
	} 

	function _checkForm()
	{
		if (TRUE == empty($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['wap'][20];
		elseif (20 < strlen($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['wap'][21];
		if (TRUE == empty($this->_input['_POST']['password'])) $this->errors[] = $this->_lang['wap'][22];
		if (TRUE == empty($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['wap'][23];
		elseif (FALSE == $this->_isEmailAddress($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['wap'][24];
		elseif (35 < strlen($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['comment'][25];
		if (FALSE == empty($this->_input['_POST']['homepage']))
		{
			if (FALSE == preg_match("/^http:\/\/\w+[\w\.\-\/]+[^.]$/i", $this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['comment'][26];
			elseif (100 < strlen($this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['comment'][27];
		} 
		else $this->_input['_POST']['homepage'] = '';
		if (FALSE == empty($this->_input['_POST']['name']) && FALSE == empty($this->_input['_POST']['password'])) $member_info = $this->_getMemberInfo("user='{$this->_input['_POST']['name']}' and password='" . md5($this->_input['_POST']['password']) . "'");

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

	function _getMemberInfo($conditions)
	{
		$query_string = "select * from {$this->_dbop->prefix}admin where $conditions";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows())
		{
			$member_info = $this->_dbop->fetchArray(0, 'ASSOC');
			$this->_input['_SESSION']['userID'] = $member_info['id'];
		} 
		else
		{
			$member_info = array();
			$this->errors[] = $this->_lang['wap'][28];
		} 
		$this->_dbop->freeResult();
		return $member_info;
	} 

	function addArticle()
	{ 
		// Check data from POST
		$this->_checkAddingArticleForm(); 
		// Whether the user could add a article?
		if (FALSE == empty($this->_input['_POST']['name']) && FALSE == empty($this->_input['_POST']['password']))
		{
			$query_sting = "select * from {$this->_dbop->prefix}admin where user='{$this->_input['_POST']['name']}' and password='" . md5($this->_input['_POST']['password']) . "' and uid";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['admin'][94];
			$this->_dbop->freeResult();
		} 
		// Add a article
		if (FALSE == count($this->errors)) // If nothing was wrong
			{
				// Whether a article with same subject and in a same category was existed?
				$query_string = "select * from {$this->_dbop->prefix}blog where title='" . $this->_input['_POST']['subject'] . "' and sort='" . $this->_input['_POST']['esort'] . "' limit 0, 1";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['admin'][76];
			else
			{
				$query_string = "insert into {$this->_dbop->prefix}blog (
					sort, title, content, author, author_id, email, visits, addtime, keyword, summarycontent,
					weather, top, hidden, html, nocom, commentCount, trackbackCount
					) values (
					{$this->_input['_POST']['esort']}, '" . $this->_input['_POST']['subject'] . "', '" . $this->_input['_POST']['content'] . "',
					'" . $this->_input['_POST']['name'] . "', {$user_info['id']}, '" . $user_info['email'] . "', 0,
					'" . $this->_input['_POST']['post_time'] . "', '" . $this->_input['_POST']['keyword'] . "', '" . $this->_input['_POST']['summarycontent'] . "',
					'cloudy', '0', '0', '0',
					'0', 0, 0)";
				$this->_dbop->query($query_string);
				$this->blog_id = $this->_dbop->getInsertId();

				$this->_addBlogKeyword();

				$query_string = "update {$this->_dbop->prefix}admin set blogCount=blogCount+1 where id={$_SESSION['userID']};;update {$this->_dbop->prefix}sort set blogCount=blogCount+1 where id={$this->_input['_POST']['esort']};;update {$this->_dbop->prefix}global set blogCount=blogCount+1";
				$this->_dbop->query($query_string);

				if (FALSE == empty($this->_input['_POST']['trackback']))
				{
					if (100 > $this->getStrLen($this->_input['_POST']['content'])) $tmp_content = $this->_input['_POST']['content'];
					else $tmp_content = $this->_substr($this->_input['_POST']['content'], 0, 100);
					$blog_url = "{$this->_config['global']['siteUrl']}/index.php?play=reply&id={$this->blog_id}";
					$blog_name = $this->_config['global']['siteName'];
					$this->_sendPing($this->_input['_POST']['title'], $blog_url, $tmp_content, $blog_name, $this->_input['_POST']['trackback']);
				} 
			} 
			$this->_dbop->freeResult();
		} 

		if (TRUE == count($this->errors)) $this->_html->assign(array("action" => "error", "message" => $this->errors, "card_id" => "error"));
		else $this->_html->assign(array("action" => "message", "message" => $this->_lang['wap'][43], "card_id" => "success"));
	} 

	function _checkAddingArticleForm()
	{
		if (TRUE == empty($this->_input['_POST']['subject'])) $this->errors[] = $this->_lang['admin'][30];
		elseif (strlen(trim($this->_input['_POST']['subject'])) > 50) $this->errors[] = $this->_lang['admin'][134];
		else $this->_input['_POST']['subject'] = trim($this->_input['_POST']['subject']);
		if (TRUE == empty($this->_input['_POST']['content']) || 6 > strlen(trim($this->_input['_POST']['content']))) $this->errors[] = $this->_lang['admin'][33];
		else $this->_input['_POST']['content'] = trim($this->_input['_POST']['content']);
		if (TRUE == empty($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['admin'][31];
		elseif (20 < strlen(trim($this->_input['_POST']['name']))) $this->errors[] = $this->_lang['admin'][135];
		else $this->_input['_POST']['name'] = trim($this->_input['_POST']['name']);
		if (TRUE == empty($this->_input['_POST']['esort']) || FALSE == is_numeric($this->_input['_POST']['esort'])) $this->_input['_POST']['esort'] = 1;
		else $this->_input['_POST']['esort'] = intval(trim($this->_input['_POST']['esort']));
		if (TRUE == empty($this->_input['_POST']['keyword'])) $this->_input['_POST']['keyword'] = '';
		elseif (strlen(trim($this->_input['_POST']['keyword'])) > 100) $this->errors[] = $this->_lang['admin'][138];
		else $this->_input['_POST']['keyword'] = trim($this->_input['_POST']['keyword']);
		if (FALSE == empty($this->_input['_POST']['content'])) $this->_input['_POST']['summarycontent'] = $this->_substr(strip_tags($this->_input['_POST']['content']), 0, $this->_config['global']['summarynum']);
		else $this->_input['_POST']['summarycontent'] = '';
		if (TRUE == empty($this->_input['_POST']['trackback'])) $this->_input['_POST']['trackback'] = '';
		elseif (FALSE == preg_match("/^http:\/\/.*/", $this->_input['_POST']['trackback'])) $this->errors[] = $this->_lang['admin'][132];
		$this->_input['_POST']['post_time'] = time();

		include_once("./include/ubbcode.php");
		$ubb_op = new UbbCode();
		$ubb_op->addTagByOrder("&", "&amp;", FALSE);
		$ubb_op->addTagByOrder("<", "&lt;", FALSE);
		$ubb_op->addTagByOrder(">", "&gt;", FALSE);
		$ubb_op->addTagByOrder("'", "&#39;", FALSE);
		$this->_input['_POST']['summarycontent'] = $ubb_op->parse(strip_tags($this->_input['_POST']['summarycontent']));
		$this->_input['_POST']['content'] = $ubb_op->parse($this->_input['_POST']['content']);
		$this->_input['_POST']['summarycontent'] = html_entity_decode($this->_input['_POST']['summarycontent']);
		unset($ubb_op);
		$this->escapeSqlCharsFromData($this->_input['_POST']);
	} 

	function _sendPing($blog_subject, $blog_url, $summy_content, $blog_name, $trackback_url)
	{
		if (TRUE == empty($trackback_url)) $this->errors[] = $this->_lang['admin'][122];
		$parse_urls = parse_url($trackback_url);
		if (TRUE == empty($parse_urls['scheme']) || 'http' != strtolower($parse_urls['scheme']) || TRUE == empty($parse_urls['host'])) $this->errors[] = $this->_lang['admin'][123];
		$parse_urls['port'] = (TRUE == empty($parse_urls['port'])) ? 80 : $parse_urls['port'];

		$post_string = "title=" . urlencode($blog_subject)
		 . "&url=" . urlencode($blog_url)
		 . "&excerpt=" . urlencode($summy_content)
		 . "&blog_name=" . urlencode($blog_name);
		$user_agent = "Mozilla/4.0 (compatible; exBlog {$this->_config['global']['Version']}; " . PHP_OS . ")";
		$post_content = "POST {$parse_urls['path']}" . (FALSE == empty($parse_urls['query']) ? '?' . $parse_urls['query'] : '')
		 . " HTTP/1.1\r\n"
		 . "Accept: */*\r\n"
		 . "User-Agent: $user_agent\r\n"
		 . "Host: {$parse_urls['host']}:{$parse_urls['port']}\r\n"
		 . "Connection: Keep-Alive\r\n"
		 . "Cache-Control: no-cache\r\n"
		 . "Connection: Close\r\n"
		 . "Content-Length: " . strlen($post_string) . "\r\n"
		 . "Content-Type: application/x-www-form-urlencoded\r\n\r\n$post_string";
		$address = gethostbyname($parse_urls['host']);

		if (FALSE == count($this->errors))
		{
			$result = '';
			$socket_id = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if (FALSE == $socket_id) $this->errors[] = $this->_lang['admin'][124] . socket_strerror($socket_id);
			else
			{
				$socket = socket_connect($socket_id, $address, $parse_urls['port']);
				if (FALSE == $socket) $this->errors[] = $this->_lang['admin'][124] . socket_strerror($socket_id);
				else
				{
					socket_write($socket_id, $post_content, strlen($post_content));
					while ($out = socket_read($socket_id, 2048)) $result .= $out;
					socket_close($socket_id);

					if (FALSE == strstr($result, '<error>0</error>')) $this->errors[] = $this->_lang['editblog'][33];
					else $this->notices[] = $this->_lang['editblog'][34];
				} 
			} 
		} 
	} 

	function _addBlogKeyword()
	{
		$condition = '';
		$existed_tags = array();
		$new_keywords = "";
		$tmp_tags = "";
		$keywords = explode(",", $this->_input['_POST']['keyword']);

		foreach ($keywords as $key => $value)
		{
			if (FALSE == empty($value)) $condition .= "or tag LIKE '%|$value' or tag LIKE '$value|%' or tag LIKE '%|$value|%' or tag='$value'";
		} 
		if (FALSE == empty($condition))
		{
			$condition = substr($condition, 2);
			$query_string = "select * from {$this->_dbop->prefix}tags where $condition";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $tmp = $this->_dbop->fetchArrayBat(0, 'ASSOC');
			else $tmp = array();
			$this->_dbop->freeResult();
			for ($i = 0; $i < count($tmp); $i++) $tmp_tags .= "|{$tmp[$i]['tag']}";
			if (TRUE == strlen($tmp_tags)) $tmp_tags = substr($tmp_tags, 1);
			$existed_tags = explode("|", $tmp_tags);
			$existed_tags = array_unique($existed_tags);
			for ($i = 0; $i < count($keywords); $i++)
			{
				if (FALSE == empty($keywords[$i]) && FALSE == in_array($keywords[$i], $existed_tags)) $new_keywords .= "|{$keywords[$i]}";
			} 

			if (FALSE == empty($new_keywords))
			{
				$query_string = "insert into {$this->_dbop->prefix}tags (tag) values ('" . substr($new_keywords, 1) . "')";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult();
			} 
		} 
	} 
} 

new Wap();
?>