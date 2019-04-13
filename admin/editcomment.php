<?php
require_once("./public.php");

class EditComment extends CommonLib
{
	var $errors = array();
	var $comment_id = 0;

	function EditComment()
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
		include_once("../{$this->_config['global']['langURL']}/editcomment.php");
		$this->_lang['comment'] = $lang;

		// 用户验证
		if (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['public'][21], "./login.php");

		if (FALSE == empty($_GET['id']) && TRUE == is_numeric($_GET['id'])) $this->comment_id = intval($_GET['id']);

		if (FALSE == empty($_GET['action']) && 'delete' == $_GET['action']) $this->_deleteComment();
		else $this->_printCommentsList();

		exit();
	}

	function _printCommentsList()
	{
		$this->printPageHeader();

		echo "
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"main\">
      <tr align=\"center\"><td height=\"25\" colspan=\"4\" class=\"menu\"><b>{$this->_lang['comment'][6]}</b></td></tr>
		";

		$comments_list = $this->_getCommentList();
		for ($i = 0; $i < count($comments_list); $i++)
		{
			echo "
      <tr class=\"main\">
        <td width=\"15%\" height=\"23\">&nbsp;id:[{$comments_list[$i]['id']}]</td>
<!--        <td width=\"65%\"><a href=\"./editcomment.php?action=modify&id={$comments_list[$i]['id']}\">{$comments_list[$i]['title']}</a></td>
        <td width=\"10%\"><a href=\"./editcomment.php?action=modify&id={$comments_list[$i]['id']}\">{$this->_lang['comment'][7]}</a></td>  -->
        <td width=\"65%\"><span title=\"{$comments_list[$i]['content']}\">{$comments_list[$i]['title']}</span></td>
        <td width=\"10%\"><a href=\"./editcomment.php?action=delete&id={$comments_list[$i]['id']}\" onclick=\"if(confirm ('{$this->_lang['comment'][8]}')){;}else{return false;}\">{$this->_lang['comment'][9]}</a></td>
      </tr>
			";
		}

		echo "
      <tr class=\"main\"><td colspan=\"4\">&nbsp; </td></tr>
      <tr><td colspan=\"4\"><!--  分页显示  --></td></tr>
      <form action='{$_SERVER['PHP_SELF']}' method=POST>
      <tr><td colspan=\"5\"><hr><b>{$this->_lang['comment'][11]}</b><br />
        {$this->_lang['comment'][12]} <input name=\"s_blogsubject\" type=\"text\" class=\"input\" size=\"5\">
<!--        {$this->_lang['comment'][13]} <input name=\"s_addtime\" type=\"text\" class=\"input\" title=\"{$this->_lang['comment'][14]}\" size=\"5\"> -->
        {$this->_lang['comment'][20]} <input name=\"s_author\" type=\"text\" class=\"input\" title=\"{$this->_lang['comment'][21]}\" size=\"5\">
<!--        {$this->_lang['comment'][15]} <input name=\"s_content\" type=\"text\" class=\"input\" title=\"{$this->_lang['comment'][16]}\" size=\"5\"> -->
	   {$this->_lang['comment'][23]} <input name=\"s_email\" type=\"text\" class=\"input\" size=\"5\">
        <select name=\"s_sc\" class=\"botton\"><option value=\"DESC\" selected>{$this->_lang['comment'][17]}</option><option value=\"ASC\">{$this->_lang['comment'][18]}</option></select> {$this->_lang['comment'][19]}
        <input type=hidden name=action value=search><input type=\"submit\" value=\"{$this->_lang['comment'][22]}\" class=\"botton\">
      </td></tr>
      </form>
    </table>
		";

		$this->printPageFooter();
	}

	function _deleteComment()
	{
		if (FALSE == empty($this->comment_id))
		{
			$query_string = "select t1.*, t2.title from {$this->_dbop->prefix}comment as t1, {$this->_dbop->prefix}blog as t2 where t1.commentID=t2.id and t1.id={$this->comment_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $comment_info = $this->_dbop->fetchArray(0, 'ASSOC');
			else $this->comment_id = 0;
			$this->_dbop->freeResult();
			if (FALSE == empty($this->comment_id))
			{
				$query_string = "delete from {$this->_dbop->prefix}comment where id={$this->comment_id}";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult();
				$this->showMesg(sprintf($this->_lang['comment'][24], $comment_info['author'], date('Y-m-d H:i:s', intval($comment_info['addtime']))), $_SERVER['PHP_SELF']);
			}
			else $this->_showError($this->_lang['comment'][25], $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->_lang['comment'][26], $_SERVER['PHP_SELF']);
	}

	function _getCommentList()
	{
		$condition = "";
		$comments_list = array();
		$query_string = "select t1.*, t2.title from {$this->_dbop->prefix}comment as t1, {$this->_dbop->prefix}blog as t2 where t1.commentID=t2.id";
		if (FALSE == empty($this->_input['_POST']['action']) && 'search' == $this->_input['_POST']['action'])
		{
			if (FALSE == empty($this->_input['_POST']['s_blogsubject'])) $condition .= " and t2.title LIKE '%{$this->_input['_POST']['s_blogsubject']}%'";
			if (FALSE == empty($this->_input['_POST']['s_author'])) $condition .= " and t1.author='{$this->_input['_POST']['s_author']}'";
			if (FALSE == empty($this->_input['_POST']['s_email'])) $condition .= " and t1.email='{$this->_input['_POST']['s_email']}'";
		}
		$query_string .= $condition;
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $comments_list = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		return $comments_list;
	}
}

new EditComment();
?>