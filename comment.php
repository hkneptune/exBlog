<?php
require_once("./public.php");

class Comment extends PublicCommon
{
	var $errors = array();
	var $blog_id = 0;
	var $redirect_url = "";
	var $current_time = 0;

	function Comment()
	{
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

		// 组合POST和GET数据
		$this->_input = array_merge($_GET, $_POST);
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $_POST;

		$this->redirect_url = $_SERVER['HTTP_REFERER'];

		$this->_getCurrentTime();

		$this->_initHtml();

		if (FALSE == empty($this->_input['id']) && TRUE == is_numeric($this->_input['id'])) $this->blog_id = intval($this->_input['id']);

		if (FALSE == empty($this->_input['_POST']['action']) && 'add_replay' == $this->_input['_POST']['action']) $this->addComment();

		$this->end_time = $this->microtime_float();
		$this->_html->assign("end_time", $this->end_time);
		$this->_html->display($this->template_name);

		exit();
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
			}

			header("location: {$this->redirect_url}");
		}
	}

	function checkForm()
	{
		@session_start();

		// not login?
		if (TRUE == empty($_SESSION['exPass']))
		{
			if (TRUE == empty($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['comment'][0];
			elseif (20 < strlen($this->_input['_POST']['name'])) $this->errors[] = $this->_lang['comment'][1];
			if (TRUE == empty($this->_input['_POST']['password'])) $this->errors[] = $this->_lang['comment'][2];
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
			else $member_info = $this->_getMemberInfo("user='{$this->_input['_POST']['name']}' and password='" . md5($this->_input['_POST']['password']) . "'");
		}
		else
		{
			$member_info = $this->_getMemberInfo("id={$_SESSION['userID']}");
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

	function _getMemberInfo($conditions)
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
*/			$_SESSION['exPass'] = $member_info['user'];
			$_SESSION['userID'] = $member_info['id'];
			$_SESSION['exUseremail'] = $member_info['email'];
			$_SESSION['exUserlastvisit'] = $member_info['lastvisit'];
			$_SESSION['exUserIpAddress'] = $member_info['ipaddress'];
			$_SESSION['userLevel'] = $member_info['uid'];
		}
		else
		{
			$member_info = array();
			$this->errors[] = $this->_lang['comment'][8];
		}
		$this->_dbop->freeResult();
		return $member_info;
	}

	function _addMemberInfo()
	{
		$member_id = 0;
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
			setcookie("exBlogUser", "{$member_info['user']}||{$member_info['email']}", time()+86400);

			@session_start();
			session_unregister("exPass");
			session_unregister("exPassword");
			session_unregister("userID");
			$_SESSION['exPass'] = $this->_input['_POST']['name'];
			$_SESSION['userID'] = $member_id;
			$_SESSION['exUseremail'] = $this->_input['_POST']['email'];
			$_SESSION['exUserlastvisit'] = 0;
			$_SESSION['exUserIpAddress'] = '255.255.255.255';
			$_SESSION['userLevel'] = 2;
		}

		return $member_id;
	}
}

new Comment();
?>