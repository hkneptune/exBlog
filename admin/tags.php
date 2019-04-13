<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Tags manager for exBlog
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

define('MODULE_NAME', 'core-adm.tags');

require_once("./public.php");

class Tags extends CommonLib
{
	var $errors = array();
	var $tag_id = 0;

	function Tags()
	{
		$this->_initEnv(MODULE_NAME, 'tags', 1); 
		// 获取指定的tag id
		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->tag_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->tag_id = intval($this->_input['_GET']['id']);
		else $this->tag_id = 0;

		if (FALSE == empty($this->_input['_POST']['action']))
		{
			switch ($this->_input['_POST']['action'])
			{
				case 'add' : $this->_addTag();
					break;
				case 'modify' : $this->_modifyTag();
					break;
				default : $this->_printTagsList();
					break;
			} 
		} elseif (FALSE == empty($this->_input['_GET']['action']))
		{
			switch ($this->_input['_GET']['action'])
			{
				case 'delete' : $this->_deleteTag();
					break;
				default : $this->_printTagsList();
					break;
			} 
		} 
		else $this->_printTagsList();

		$this->_destroyEnv();
	} 

	function _printTagsList()
	{
		$tag_info = $this->_getTagInfo();
		$this->printPageHeader();
		echo "
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
    <table width=\"100%\" border=\"0\" class=\"main\">
      <tr><td colspan=\"2\" class=\"menu\" align=\"center\"><b>" . ((TRUE == empty($this->tag_id)) ? $this->_lang['tags'][3] : $this->_lang['tags'][7]) . "</b></td></tr>
      <tr><td height=\"23\" align=\"center\" valign=\"top\">{$this->_lang['tags'][5]}</td><td valign=\"top\"><input name=\"tag\" type=\"text\" class=\"input\" id=\"tag\" size=\"50\" value=\"" . ((TRUE == empty($this->tag_id)) ? '' : $tag_info['tag']) . "\">";

		if (TRUE == empty($this->tag_id))
			echo "<input type=\"hidden\" name=\"action\" value=\"add\"> <input type=\"submit\" value=\"{$this->_lang['tags'][6]}\" class=\"botton\">";
		else
			echo "<input type=\"hidden\" name=\"id\" value=\"{$this->tag_id}\"><input type=\"hidden\" name=\"action\" value=\"modify\"> <input type=\"submit\" value=\"{$this->_lang['tags'][8]}\" class=\"botton\">";

		echo "
      <br />{$this->_lang['tags'][9]}</td></tr>
    </table>
    </form>
    <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"main\">
      <tr align=\"center\"><td height=\"25\" colspan=\"4\" class=\"menu\"><b>{$this->_lang['tags'][4]}</b></td></tr>
		";

		$tags_list = $this->_getTagsList();

		for ($i = 0; $i < count($tags_list); $i++)
		{
			echo "
      <tr class=\"main\">
        <td width=\"15%\" height=\"23\">[{$tags_list[$i]['id']}]</td>
<!--        <td width=\"65%\"><a href=\"./tags.php?action=edit&id={$tags_list[$i]['id']}\">{$tags_list[$i]['tag']}</a></td>
        <td width=\"10%\"><a href=\"./tags.php?action=edit&id={$tags_list[$i]['id']}\">{$this->_lang['tags'][0]}</a></td>  -->
        <td width=\"65%\"><a href=\"./tags.php?id={$tags_list[$i]['id']}\">{$tags_list[$i]['tag']}</a></td>
        <td width=\"10%\"><a href=\"./tags.php?action=delete&id={$tags_list[$i]['id']}\" onclick=\"if(confirm ('{$this->_lang['tags'][1]}')){;}else{return false;}\">{$this->_lang['tags'][2]}</a></td>
      </tr>
			";
		} 

		echo "
    </table>
  </td></tr>
</table>
		";
		$this->printPageFooter();
	} 

	function _checkForm()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (FALSE == preg_match('/^[^\|]+.*[^\|]$/', $this->_input['_POST']['tag'])) $this->errors[] = $this->_lang['tags'][11];
	} 

	function _addTag()
	{
		$condition = "";
		$new_tags = ""; 
		// 检查POST表单
		$this->_checkForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 获取已经存在的tag内容列表以及ID
		$tags_list = explode("|", $this->_input['_POST']['tag']);
		for ($i = 0; $i < count($tags_list); $i++)
		{
			if (FALSE == empty($tags_list[$i])) $condition .= " or tag LIKE '%|{$tags_list[$i]}' or tag LIKE '{$tags_list[$i]}|%' or tag LIKE '%|{$tags_list[$i]}|%' or tag='{$tags_list[$i]}'";
		} 
		$query_string = "select id, tag from {$this->_dbop->prefix}tags where" . substr($condition, 3);
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $tmp = $this->_dbop->fetchArrayBat(0, "ASSOC");
		else $tmp = array();
		$this->_dbop->freeResult(); 
		// 组合已经存在的tag并构造重组条件
		$condition = "";
		for ($i = 0; $i < count($tmp); $i++)
		{
			$new_tags .= "|{$tmp[$i]['tag']}";
			$condition .= " or id={$tmp[$i]['id']}";
		} 
		if (FALSE == empty($new_tags)) $new_tags = substr($new_tags, 1) . "|{$this->_input['_POST']['tag']}";
		else $new_tags = $this->_input['_POST']['tag']; 
		// 保证tag唯一性
		$tmp = explode("|", $new_tags);
		$new_tags = "";
		$tmp = array_unique($tmp);
		foreach ($tmp as $key => $value)
		{
			if ('' != $value) $new_tags .= "|$value";
		} 
		if (FALSE == empty($new_tags)) $new_tags = substr($new_tags, 1);

		$query_string = "insert into {$this->_dbop->prefix}tags (tag) values ('$new_tags')";
		if (FALSE == empty($condition)) $query_string .= ";;delete from {$this->_dbop->prefix}tags where " . substr($condition, 4); // 删除已经被组合的tag
		$this->_dbop->query($query_string);
		$this->_dbop->freeResult();

		$this->showMesg($this->_lang['tags'][10], $_SERVER['PHP_SELF']);
	} 

	function _modifyTag()
	{
		$new_tags = "";
		$condition = ""; 
		// 检查POST表单数据
		$this->_checkForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 获取已经存在的tag内容列表以及ID
		$tags_list = explode("|", $this->_input['_POST']['tag']);
		for ($i = 0; $i < count($tags_list); $i++)
		{
			if (FALSE == empty($tags_list[$i])) $condition .= " or tag LIKE '%|{$tags_list[$i]}' or tag LIKE '{$tags_list[$i]}|%' or tag LIKE '%|{$tags_list[$i]}|%' or tag='{$tags_list[$i]}'";
		} 
		$query_string = "select id, tag from {$this->_dbop->prefix}tags where" . substr($condition, 3);
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $tmp = $this->_dbop->fetchArrayBat(0, "ASSOC");
		else $tmp = array();
		$this->_dbop->freeResult(); 
		// 组合已经存在的tag并构造重组条件
		$condition = "";
		for ($i = 0; $i < count($tmp); $i++)
		{
			if ($this->tag_id != $tmp[$i]['id'])
			{
				$new_tags .= "|{$tmp[$i]['tag']}";
				$condition .= " or id={$tmp[$i]['id']}";
			} 
		} 
		if (FALSE == empty($new_tags)) $new_tags .= "|{$this->_input['_POST']['tag']}";
		else $new_tags = $this->_input['_POST']['tag']; 
		// 保证tag唯一性
		$tmp = explode("|", $new_tags);
		$new_tags = "";
		$tmp = array_unique($tmp);
		foreach ($tmp as $key => $value)
		{
			if ('' != $value) $new_tags .= "|$value";
		} 
		if (FALSE == empty($new_tags)) $new_tags = substr($new_tags, 1);

		$query_string = "update {$this->_dbop->prefix}tags set tag='$new_tags' where id={$this->tag_id}";
		if (FALSE == empty($condition)) $query_string .= ";;delete from {$this->_dbop->prefix}tags where " . substr($condition, 4); // 删除已经被组合的tag
		$this->_dbop->query($query_string);
		$this->_dbop->freeResult();

		$this->showMesg($this->_lang['tags'][13], $_SERVER['PHP_SELF']);
	} 

	function _deleteTag()
	{ 
		// 获取选定 tag 的内容
		$query_string = "select tag from {$this->_dbop->prefix}tags where id={$this->tag_id}";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $query_string_new = "delete from {$this->_dbop->prefix}tags where id={$this->tag_id}";
		$this->_dbop->freeResult();

		if (FALSE == empty($query_string_new))
		{
			$this->_dbop->query($query_string_new);
			$this->_dbop->freeResult();
			$this->showMesg($this->_lang['tags'][15], $_SERVER['PHP_SELF']);
		} 
		else $this->showMesg($this->_lang['tags'][16], $_SERVER['PHP_SELF']);
	} 

	function _getTagsList()
	{
		$tags_list = array();
		$query_string = "select * from {$this->_dbop->prefix}tags";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $tags_list = $this->_dbop->fetchArrayBat();
		$this->_dbop->freeResult();
		return $tags_list;
	} 

	function _getTagInfo()
	{
		$tag_info = array();
		if (FALSE == empty($this->tag_id))
		{
			$query_string = "select * from {$this->_dbop->prefix}tags where id={$this->tag_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $tag_info = $this->_dbop->fetchArray();
			else $this->tag_id = 0;
			$this->_dbop->freeResult();
		} 
		return $tag_info;
	} 
} 

new Tags();

?>
