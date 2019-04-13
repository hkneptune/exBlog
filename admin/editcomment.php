<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Comments manager for exBlog
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

define('MODULE_NAME', 'core-adm.editcomment');

require_once("./public.php");

class EditComment extends CommonLib
{
	var $errors = array();
	var $comment_id = 0;
	var $total_comments_number = 0;
	var $number_per_page = 5;
	var $current_page = 1;
	var $total_pages = 1;

	function EditComment()
	{
		$this->_initEnv(MODULE_NAME, 'editcomment', 0);
		include_once("../{$this->_config['global']['langURL']}/editcomment.php");

		if (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->comment_id = intval($this->_input['_GET']['id']);
		if (FALSE == empty($this->_input['_GET']['page']) && TRUE == is_numeric($this->_input['_GET']['page'])) $this->current_page = intval($this->_input['_GET']['page']);

		if (FALSE == empty($this->_input['_POST']['action']) && 'reply' == $this->_input['_POST']['action']) $this->replyComment($this->comment_id);
		elseif (FALSE == empty($this->_input['_GET']['action']) && 'reply' == $this->_input['_GET']['action']) $this->printCommentForm($this->comment_id);
		elseif (FALSE == empty($this->_input['_GET']['action']) && 'delete' == $this->_input['_GET']['action']) $this->_deleteComment($this->comment_id);
		else $this->_printCommentsList();

		$this->_destroyEnv();
	} 

	function _printCommentsList()
	{
		$this->printPageHeader();

		echo "
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"main\">
      <tr align=\"center\"><td height=\"25\" colspan=\"4\" class=\"menu\"><b>{$this->_lang['editcomment'][6]}</b></td></tr>
		";

		$comments_list = $this->_getCommentList();
		for ($i = 0; $i < count($comments_list); $i++)
		{
			echo "
      <tr class=\"main\">
        <td width=\"15%\" height=\"23\">&nbsp;id:[{$comments_list[$i]['id']}]</td>
<!--        <td width=\"65%\"><a href=\"./editcomment.php?action=modify&id={$comments_list[$i]['id']}\">{$comments_list[$i]['title']}</a></td>
        <td width=\"10%\"><a href=\"./editcomment.php?action=modify&id={$comments_list[$i]['id']}\">{$this->_lang['editcomment'][7]}</a></td>  -->
        <td width=\"65%\"><span title=\"" . htmlspecialchars($comments_list[$i]['content']) . "\">{$comments_list[$i]['title']}</span></td>
        <td width=\"10%\" nowrap><a href=\"./editcomment.php?action=reply&id={$comments_list[$i]['id']}\">{$this->_lang['editcomment'][7]}</a> &nbsp; <a href=\"./editcomment.php?action=delete&id={$comments_list[$i]['id']}\" onclick=\"if(confirm ('{$this->_lang['editcomment'][8]}')){;}else{return false;}\">{$this->_lang['editcomment'][9]}</a></td>
      </tr>
			";
		} 

		echo "
      <tr class=\"main\"><td colspan=\"4\">&nbsp; </td></tr>
      <tr><td colspan=\"4\">" . $this->_printPageCounter() . "</td></tr>
      <form action='{$_SERVER['PHP_SELF']}' method=POST>
      <tr><td colspan=\"5\"><hr><b>{$this->_lang['editcomment'][11]}</b><br />
        {$this->_lang['editcomment'][12]} <input name=\"s_blogsubject\" type=\"text\" class=\"input\" size=\"5\">
<!--        {$this->_lang['editcomment'][13]} <input name=\"s_addtime\" type=\"text\" class=\"input\" title=\"{$this->_lang['editcomment'][14]}\" size=\"5\"> -->
        {$this->_lang['editcomment'][20]} <input name=\"s_author\" type=\"text\" class=\"input\" title=\"{$this->_lang['editcomment'][21]}\" size=\"5\">
<!--        {$this->_lang['editcomment'][15]} <input name=\"s_content\" type=\"text\" class=\"input\" title=\"{$this->_lang['editcomment'][16]}\" size=\"5\"> -->
	   {$this->_lang['editcomment'][23]} <input name=\"s_email\" type=\"text\" class=\"input\" size=\"5\">
        <select name=\"s_sc\" class=\"botton\"><option value=\"DESC\" selected>{$this->_lang['editcomment'][17]}</option><option value=\"ASC\">{$this->_lang['editcomment'][18]}</option></select> {$this->_lang['editcomment'][19]}
        <input type=hidden name=action value=search><input type=\"submit\" value=\"{$this->_lang['editcomment'][22]}\" class=\"botton\">
      </td></tr>
      </form>
    </table>
		";

		$this->printPageFooter();
	}

	function printCommentForm($comment_id)
	{
		$comment_info = $this->getCommentInfo($comment_id);
		if (FALSE == count($comment_info)) $this->errors[] = $this->_lang['editcomment'][25];
		if (FALSE == count($this->errors))
		{
			$this->printPageHeader();
			echo "
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <form action=\"{$_SERVER['PHP_SELF']}?id=$comment_id\" method=POST>
  <input type=hidden name=action value=reply>
  <tr><td height=\"180\">
    <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"main\">
      <tr align=\"center\"><td height=\"25\" colspan=\"4\" class=\"menu\"><b>{$this->_lang['editcomment'][30]}</b></td></tr>
      <tr align=\"center\"><td align=right>{$this->_lang['editcomment'][1]}</td><td align=left>{$comment_info['author']}</td><td align=right>{$this->_lang['editcomment'][2]}</td><td align=left>" . date('Y-m-d H:i:s', $comment_info['addtime']) . "</td></tr>
      <tr align=\"center\"><td align=right>{$this->_lang['editcomment'][31]}</td><td align=left>{$comment_info['email']}</td><td align=right>{$this->_lang['editcomment'][32]}</td><td align=left>{$comment_info['qq']}</td></tr>
      <tr align=\"center\"><td align=right>{$this->_lang['editcomment'][33]}</td><td colspan=3 align=left>{$comment_info['homepage']}</td></tr>
      <tr align=\"center\"><td align=right valign=top>{$this->_lang['editcomment'][3]}</td><td colspan=3 align=left>{$comment_info['content']}</td></tr>
      <tr align=\"center\"><td align=right valign=top>{$this->_lang['editcomment'][4]}</td><td colspan=3 align=left><textarea name=\"reply_content\" cols=\"50\" rows=\"10\" wrap=\"VIRTUAL\" class=\"input\"></textarea></td></tr>
      <tr align=\"center\"><td colspan=4 align=center><input type=submit value=\"{$this->_lang['editcomment'][34]}\" class=\"botton\"> &nbsp; &nbsp; <input type=reset value=\"{$this->_lang['editcomment'][35]}\" class=\"botton\"></td></tr>
    </table>
  </tr>
</table>
			";

			$this->printPageFooter();
		}
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	}

	function _deleteComment($comment_id)
	{
		if (FALSE == empty($comment_id))
		{
			$query_string = "select t1.*, t2.title from {$this->_dbop->prefix}comment as t1, {$this->_dbop->prefix}blog as t2 where t1.commentID=t2.id and t1.id=$comment_id";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $comment_info = $this->_dbop->fetchArray(0, 'ASSOC');
			else $this->comment_id = 0;
			$this->_dbop->freeResult();
			if (FALSE == empty($this->comment_id))
			{
				$query_string = "delete from {$this->_dbop->prefix}comment where id=$comment_id";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult(); 
				// Update static information
				$query_string = "update {$this->_dbop->prefix}global set commentCount=(select count(*) from {$this->_dbop->prefix}comment)";
				$this->_dbop->query($query_string); 
				// Update static information
				$query_string = "update {$this->_dbop->prefix}blog set commentCount=(select count(*) from {$this->_dbop->prefix}comment where commentID={$comment_info['commentID']}) where id={$comment_info['commentID']}";
				$this->_dbop->query($query_string);
				$this->showMesg(sprintf($this->_lang['editcomment'][24], $comment_info['author'], date('Y-m-d H:i:s', intval($comment_info['addtime']))), $_SERVER['PHP_SELF']);
			} 
			else $this->_showError($this->_lang['editcomment'][25], $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->_lang['editcomment'][26], $_SERVER['PHP_SELF']);
	}

	function replyComment($comment_id)
	{
		if (FALSE == empty($comment_id) && FALSE == empty($this->_input['_POST']['reply_content']))
		{
			$comment_info = $this->getCommentInfo($comment_id);
			if (FALSE == empty($this->comment_id))
			{
				include_once("../include/ubbcode.php");
				$ubb_op = new UbbCode();
				$new_content = $comment_info['content']
					. $ubb_op->parse(sprintf($this->_lang['editcomment'][27], $_SESSION['exPass'], $this->_input['_POST']['reply_content']));
				unset($ubb_op); 
				$query_string = "update {$this->_dbop->prefix}comment set content='$new_content' where id=$comment_id";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult();
				$this->showMesg($this->_lang['editcomment'][28], $_SERVER['PHP_SELF']);
			}
			else $this->_showError($this->_lang['editcomment'][25], $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->_lang['editcomment'][29], $_SERVER['PHP_SELF']);
	}

	function _getCommentList()
	{
		$condition = "";
		$comments_list = array();

		if (FALSE == empty($this->_input['_POST']['action']) && 'search' == $this->_input['_POST']['action'])
		{
			if (FALSE == empty($this->_input['_POST']['s_blogsubject'])) $condition .= " and t2.title LIKE '%{$this->_input['_POST']['s_blogsubject']}%'";
			if (FALSE == empty($this->_input['_POST']['s_author'])) $condition .= " and t1.author='{$this->_input['_POST']['s_author']}'";
			if (FALSE == empty($this->_input['_POST']['s_email'])) $condition .= " and t1.email='{$this->_input['_POST']['s_email']}'";
			if (FALSE == empty($this->_input['_POST']['s_sc']) && 'ASC' == $this->_input['_POST']['s_sc']) $condition .= " order by t1.id ASC";
			else $condition .= " order by t1.id DESC";
		}
		$query_string = "select count(*) from {$this->_dbop->prefix}comment as t1, {$this->_dbop->prefix}blog as t2 where t2.id=t1.commentID" . $condition;
		$this->_dbop->query($query_string);
		$tmp = $this->_dbop->fetchArray(0, 'NUM');
		$this->_dbop->freeResult();
		$this->total_comments_number = $tmp[0];
		$this->total_pages = ceil($this->total_comments_number / $this->number_per_page);
		if (0 == $this->total_pages) $this->total_pages = 1;
		if ($this->current_page > $this->total_pages) $this->current_page = $this->total_pages;
		elseif (1 > $this->current_page) $this->current_page = 1;

		$query_string = "select t1.*, t2.title from {$this->_dbop->prefix}comment as t1, {$this->_dbop->prefix}blog as t2 where t2.id=t1.commentID";
		$query_string .= $condition . " limit " . ($this->current_page - 1) * $this->number_per_page . ", {$this->number_per_page}";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $comments_list = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		return $comments_list;
	} 

	function getCommentInfo($comment_id)
	{
		$comment_info = array();
		$query_string = "select t1.*,t2.id, t2.title from {$this->_dbop->prefix}comment as t1, {$this->_dbop->prefix}blog as t2 where t1.id=$comment_id and t2.id=t1.commentID";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $comment_info = $this->_dbop->fetchArray(0, 'ASSOC');
		else $this->comment_id = 0;
		$this->_dbop->freeResult();
		return $comment_info;
	}

	function _printPageCounter()
	{
		if ($this->current_page > 1) $prev_page = "<a href={$_SERVER['PHP_SELF']}?action=list>{$this->_lang['admin'][113]}</a> <a href={$_SERVER['PHP_SELF']}?action=list&page=" . ($this->current_page - 1) . " title='{$this->_lang['admin'][108]}'>{$this->_lang['admin'][108]}</a>";
		else $prev_page = '';
		if ($this->current_page < $this->total_pages) $next_page = "<a href={$_SERVER['PHP_SELF']}?action=list&page=" . ($this->current_page + 1) . " title='{$this->_lang['admin'][109]}'>{$this->_lang['admin'][109]}</a> <a href={$_SERVER['PHP_SELF']}?action=list&page={$this->total_pages}>{$this->_lang['admin'][114]}</a>";
		else $next_page = '';

		return "{$this->_lang['admin'][110]} <font color='red'>{$this->current_page}</font> {$this->_lang['admin'][111]}, {$this->_lang['admin'][112]} <font color='red'>{$this->total_pages}</font> {$this->_lang['admin'][111]} $prev_page $next_page";
	}
} 

new EditComment();

?>
