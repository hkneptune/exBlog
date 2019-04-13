<?php
require_once("./public.php");
/**
 * @brief Wap extension
 * @author feeling <feeling@exblog.org>
 * @date $Date$
 **/
class Wap extends PublicCommon
{
	/**
	 * @internal
	 * @brief Error messages
	 **/
	var $errors = array();
	var $start_time = 0.0;
	var $current_time = 0;

	/**
	 * @brief Template filename
	 **/
	var $template_name = 'wap.tpl';

	/**
	 * @internal
	 * @brief blog ID
	 **/
	var $blog_id = 0;

	function Wap()
	{
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
		$this->_input = array_merge($_GET, $_POST);
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $_POST;

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->blog_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->blog_id = intval($this->_input['_GET']['id']);

		$this->printContent();

		exit();
	}

	function printContent()
	{
		header("Content-type: text/vnd.wap.wml");

		$this->_initHtml();

		if (FALSE == empty($this->_input['_POST']['name'])) $this->_addComment();
		elseif (FALSE == empty($this->_input['_GET']['action']))
		{
			$this->_html->assign("action", $this->_input['_GET']['action']);
			if ('view' == $this->_input['_GET']['action'] && FALSE == empty($this->blog_id))
			{
				$this->_html->assign(
					array(
						"card_id"		=> "showarticle",
						"article_info"	=> $this->_getLatestRecords("blog", 1, "", "id={$this->blog_id}", "*", 0, TRUE)
					)
				);
			}
			elseif ('addcomment' == $this->_input['_GET']['action'] && FALSE == empty($this->blog_id))
			{
				// Do Nothing
			}
			elseif ('viewcomment' == $this->_input['_GET']['action'] && FALSE == empty($this->blog_id)) $this->_html->assign(array("action" => "viewcomment", "comments_list" => $this->_getLatestRecords("comment", 10, "id desc", "commentID={$this->blog_id}", $fields_list = "*"), "card_id" => "comment"));
			else $this->_html->assign(array("action" => "list", "articles_list" => $this->_getLatestRecords("blog", 5, "id desc", "hidden='0'", $fields_list = "title, addtime, id"), "card_id" => "article"));
		}
		else $this->_html->assign(array("action" => "list", "articles_list" => $this->_getLatestRecords("blog", 5, "id desc", "hidden='0'", $fields_list = "title, addtime, id"), "card_id" => "article"));

		$this->_html->display($this->template_name);

		exit();
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
				$query_string = "update {$this->_dbop->prefix}admin set commentCount=commentCount+1, connectionCount=connectionCount+1, ipaddress='" . $this->_getClientIp() . "', lastvisit='" . time() . "' where id={$_SESSION['userID']}";
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
			$_SESSION['userID'] = $member_info['id'];
		}
		else
		{
			$member_info = array();
			$this->errors[] = $this->_lang['wap'][28];
		}
		$this->_dbop->freeResult();
		return $member_info;
	}
}

new Wap();
?>