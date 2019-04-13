<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Categories manager for exBlog
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

define('MODULE_NAME', 'core-adm.editsort');

require_once("./public.php");

class EditSort extends CommonLib
{
	var $errors = array();
	var $sort_id = 0;

	function EditSort()
	{
		$this->_initEnv(MODULE_NAME, 'editsort', 0);

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->sort_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->sort_id = intval($this->_input['_GET']['id']);

		if (FALSE == empty($this->_input['_GET']['action']))
		{
			switch ($this->_input['_GET']['action'])
			{
				case 'add' : $this->_printAddModifyForm();
					break;
				case 'edit' :
					if (TRUE == empty($this->sort_id)) $this->_printSortList();
					else $this->_printAddModifyForm();
					break;
				case 'delete':
					if (FALSE == empty($this->sort_id)) $this->_deleteSorts($this->sort_id);
					else $this->_printSortList();
					break;
				case 'rank': $this->_printChangeSortRank();
					break;
				default: $this->_printSortList();
			} 
		} elseif (FALSE == empty($this->_input['_POST']['action']))
		{
			switch ($this->_input['_POST']['action'])
			{
				case 'addasort' : $this->_addSort();
					break;
				case 'updasort' : $this->_modifySort();
					break;
				case 'changerank' : $this->_changeSortRank();
					break;
			} 
		} 
		else $this->_printSortList();

		$this->_destroyEnv();
	} 

	function _printAddModifyForm()
	{
		$sort_info = $this->_getSortInfo();
		$this->printPageHeader();
		echo "
<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>" . ((FALSE == $this->sort_id || TRUE == empty($sort_info['id'])) ? $this->_lang['editsort'][8] : $this->_lang['editsort'][0]) . "</b></td></tr>
      <tr><td width=\"20%\" align=\"center\">{$this->_lang['editsort'][1]}</td><td width=\"80%\"> <input name=\"enName\" type=\"text\" class=\"input\" size=\"30\" value=\"" . ((FALSE == $this->sort_id || TRUE == empty($sort_info['id'])) ? "" : $sort_info['enName']) . "\"> {$this->_lang['editsort'][28]}<font color=\"#0000FF\">{$this->_lang['editsort'][2]}</font></td></tr>
      <tr><td align=\"center\">{$this->_lang['editsort'][3]}</td><td><input name=\"cnName\" type=\"text\" class=\"input\" size=\"30\" value=\"" . ((FALSE == $this->sort_id || TRUE == empty($sort_info['id'])) ? "" : $sort_info['cnName']) . "\"> {$this->_lang['editsort'][28]}<font color=\"#0000FF\">{$this->_lang['editsort'][4]}</font> </td></tr>
      <tr><td align=\"center\">{$this->_lang['editsort'][5]}</td><td><input name=\"description\" type=\"text\" class=\"input\" size=\"30\" value=\"" . ((FALSE == $this->sort_id || TRUE == empty($sort_info['id'])) ? "" : $sort_info['description']) . "\"> {$this->_lang['editsort'][28]}<font color=\"#0000FF\">{$this->_lang['editsort'][6]}</font></td></tr>
      <tr><td colspan=\"2\">&nbsp;</td></tr>
      <tr align=\"center\"><td height=\"30\" colspan=\"2\">
      	 ";
		if (FALSE == $this->sort_id || TRUE == empty($sort_info['id']))
			echo "<input type=\"hidden\" name=\"action\" value=\"addasort\"><input type=\"submit\" value=\"{$this->_lang['editsort'][9]}\" class=\"botton\">";
		else
			echo "<input type=\"hidden\" name=\"action\" value=\"updasort\"><input type=\"hidden\" name=\"id\" value=\"{$this->sort_id}\"><input type=\"submit\" value=\"{$this->_lang['editsort'][11]}\" class=\"botton\">";
		echo "
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type=\"reset\" value=\"{$this->_lang['editsort'][10]}\" class=\"botton\"></td>
      </tr>
    </table>
  </td></tr>
</table>
</form>
		";
		$this->printPageFooter();
	} 

	function _checkAddModifyForm()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (TRUE == empty($this->_input['_POST']['enName'])) $this->errors[] = $this->_lang['editsort'][19];
		elseif (20 < strlen($this->_input['_POST']['enName'])) $this->errors[] = $this->_lang['editsort'][20];
		if (TRUE == empty($this->_input['_POST']['cnName'])) $this->errors[] = $this->_lang['editsort'][21];
		elseif (20 < strlen($this->_input['_POST']['cnName'])) $this->errors[] = $this->_lang['editsort'][22];
		if (TRUE == empty($this->_input['_POST']['description'])) $this->errors[] = $this->_lang['editsort'][23];
		elseif (50 < strlen($this->_input['_POST']['description'])) $this->errors[] = $this->_lang['editsort'][24];
	} 

	function _checkRankOrderForm()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (TRUE == empty($this->_input['_POST']['ids']) || FALSE == is_array($this->_input['_POST']['ids']) || FALSE == count($this->_input['_POST']['ids']))
			$this->errors[] = $this->_lang['editsort'][32];
		if (TRUE == empty($this->_input['_POST']['order']) || FALSE == is_array($this->_input['_POST']['order']) || FALSE == count($this->_input['_POST']['order']))
			$this->errors[] = $this->_lang['editsort'][32];
	} 

	function _printSortList()
	{
		$this->printPageHeader();
		echo "
<Script Language=JavaScript>
function confirmDelete()
{
		sort_id = document.forms[0].editSort.value;
		result = confirm('{$this->_lang['editsort'][14]}');
		if (true == result)
		{
			if (sort_id != 'NULL') goUrl('delete');
			else alert('{$this->_lang['editsort'][13]}');
		}
		else return false;
}
function goUrl(action)
{
	sort_id = document.forms[0].editSort.value;
	new_url = '{$_SERVER['PHP_SELF']}?action=' + action + '&id=' + sort_id;
	location.href = new_url;
}
</Script>
<form name='sortlist'>
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>{$this->_lang['editsort'][11]}</b></td></tr>
      <tr><td width=\"20%\" height=\"23\" align=\"center\">{$this->_lang['editsort'][12]}</td>
      <td width=\"80%\">
        <select name=\"editSort\" class=\"botton\" id=\"editSort\"><option value=\"NULL\" selected>{$this->_lang['editsort'][13]}</option>
		";

		$sorts_list = $this->_getSorts();
		for ($i = 0; $i < count($sorts_list); $i++)
		{
			echo "<option value=\"" . $sorts_list[$i]['id'] . "\">" . $sorts_list[$i]['cnName'] . "</option>";
		} 

		echo "
        </select></td>
      </tr>
      <tr><td colspan=\"2\">&nbsp;</td></tr>
      <tr align=\"center\"><td height=\"30\" colspan=\"2\">
        <input type=\"button\" value=\"{$this->_lang['editsort'][26]}\" class=\"botton\" onClick=\"goUrl('edit')\">&nbsp;&nbsp;&nbsp;&nbsp;
        <input type=\"button\" value=\"{$this->_lang['editsort'][27]}\" onClick=\"confirmDelete()\" class=\"botton\">
      </td></tr>
    </table>
  </td></tr>
</table>
</form>
		";
		$this->printPageFooter();
	} 

	/*
	function _printChangeSortRank()
	{
		$this->printPageHeader();
		echo "<script language=javascript>";

		$sorts_list = $this->_getSorts();
		echo "var sorts = new Array(" . count($sorts_list) . ");";
		for ($i = 0; $i < count($sorts_list); $i++) echo "sorts[$i]=new Option(\"" . $sorts_list[$i]['cnName'] . "\", {$sorts_list[$i]['id']});";

		echo "
function redrawNewList(x)
{
	tmpobj = document.forms[0].newID;
	for (m = tmpobj.options.length - 1; m > 0;m--) { tmpobj.options[m] = null; }
	j = 0;
	for (i=0;i<sorts.length;i++)
	{
		if ( x !== i)
		{
			tmpobj.options[j] = new Option(sorts[i].value + \" \" + sorts[i].text, sorts[i].value);
			j++;
		}
	}
}
</script>
<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
  <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
    <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>{$this->_lang['editsort'][15]}</b></td></tr>
	<tr><td width=\"20%\" nowrap>{$this->_lang['editsort'][16]}</td><td><select name=\"oldID\" class=\"botton\" onChange=redrawNewList(this.options.selectedIndex)>
<script language=javascript>
for (i=0;i<sorts.length;i++) { document.write(\"<option value=\"+sorts[i].value+\">\"+sorts[i].value+\"&nbsp;\"+sorts[i].text+\"</option>\"); }
</script>
	 </select></td></tr>
	<tr><td width=\"20%\" nowrap>{$this->_lang['editsort'][17]}<br></td><td><select name=\"newID\" class=\"botton\"></select></td></tr>
	<tr><td colspan=2 align=center><input type=\"hidden\" name=\"action\" value=\"ranasort\"><input type=\"submit\" value=\"{$this->_lang['editsort'][18]}\" class=\"botton\"></td></tr>
</table></td>
</form>
<script language=javascript>redrawNewList(0);</script>
		";
		$this->printPageFooter();
	}
*/

	function _printChangeSortRank()
	{
		$sorts_list = $this->_getSorts();
		$this->printPageHeader();
		echo "
<script language=javascript>
function JHshNumberText()
{
	if ( !(((window.event.keyCode >= 48) && (window.event.keyCode <= 57)) || (window.event.keyCode == 13) || (window.event.keyCode == 46) || (window.event.keyCode == 45))) window.event.keyCode = 0;
}
</script>
<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>{$this->_lang['editsort'][15]}</b></td></tr>
		";
		for ($i = 0; $i < count($sorts_list); $i++)
		{
			echo "<tr><td align=left><a href={$_SERVER['PHP_SELF']}?action=edit&id={$sorts_list[$i]['id']}>{$sorts_list[$i]['cnName']}</a></td><td align=right><input type=hidden name=ids[] value='{$sorts_list[$i]['id']}'><input typ=text name=order[] value='{$sorts_list[$i]['sortOrder']}' size=2 onKeypress=\"JHshNumberText()\"></td></tr>";
		} 
		echo "
      <tr><td colspan=2 align=center>
        <input type=hidden name=action value=changerank>
        <input type=submit value=\"{$this->_lang['editsort'][31]}\" class=\"botton\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <input type=reset value=\"{$this->_lang['editsort'][30]}\" class=\"botton\"></td></tr>
    </table>
  </td></tr>
</table>
</form>
		";
		$this->printPageFooter();
	} 

	function _changeSortRank()
	{
		$this->_checkRankOrderForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 重组分类ID和分类顺序hash
		$query_string = "";
		for ($i = 0; $i < count($this->_input['_POST']['ids']); $i++)
		{
			if (TRUE == empty($this->_input['_POST']['order'][$i]) || FALSE == is_numeric($this->_input['_POST']['order'][$i])) $tmp_sort_order = 0;
			else $tmp_sort_order = intval($this->_input['_POST']['order'][$i]);
			$query_string .= ";;update {$this->_dbop->prefix}sort set sortOrder=$tmp_sort_order where id=" . intval($this->_input['_POST']['ids'][$i]);
		} 
		if (FALSE == empty($query_string))
		{
			$this->_dbop->query(substr($query_string, 2));
			$this->_dbop->freeResult(); 
			// 输出成功信息
			$this->showMesg($this->_lang['admin'][54], $_SERVER['PHP_SELF']);
		} 
	} 

	function _addSort()
	{
		$this->_checkAddModifyForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 查找同名分类是否存在
		$query_string = "select * from {$this->_dbop->prefix}sort where enName='" . $this->_input['_POST']['enName'] . "' OR cnName='" . $this->_input['_POST']['cnName'] . "'";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $this->_showError($this->_lang['editsort'][25], $_SERVER['PHP_SELF']);
		$this->_dbop->freeResult(); 
		// 添加分类
		$query_string = "insert into {$this->_dbop->prefix}sort (enName, cnName, description, sortOrder) values ('" . $this->_input['_POST']['enName'] . "', '" . $this->_input['_POST']['cnName'] . "', '" . $this->_input['_POST']['description'] . "', 0)";
		$this->_dbop->query($query_string);
		$this->_dbop->freeResult(); 
		// 输出成功信息
		$this->showMesg($this->_lang['admin'][43], $_SERVER['PHP_SELF']);
	} 

	function _modifySort()
	{
		$this->_checkAddModifyForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 查找分类是否存在
		$query_string = "select * from {$this->_dbop->prefix}sort where id={$this->sort_id}";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $old_sort_info = $this->_dbop->fetchArray(0, 'ASSOC');
		else $this->_showError($this->_lang['editsort'][29], $_SERVER['PHP_SELF']);
		$this->_dbop->freeResult(); 
		// 更新分类信息
		$query_string = "update {$this->_dbop->prefix}sort set enName='" . $this->_input['_POST']['enName'] . "', cnName='" . $this->_input['_POST']['cnName'] . "', description='" . $this->_input['_POST']['description'] . "' where id={$this->sort_id}";
		$this->_dbop->query($query_string);
		$this->_dbop->freeResult(); 
		// 输出成功信息
		$this->showMesg(sprintf($this->_lang['admin'][49], $old_sort_info['cnName']), $_SERVER['PHP_SELF']);
	} 

	function _deleteSorts($sort_ids)
	{
		if (FALSE == is_array($sort_ids)) $sort_ids = explode(",", $sort_ids);
		$condition1 = '';
		$condition2 = '';
		$condition3 = '';
		$query_string = '';
		for ($i = 0; $i < count($sort_ids); $i++)
		{
			if (TRUE == is_numeric($sort_ids[$i]))
			{
				$condition1 .= " or id=" . intval($sort_ids[$i]);
				$condition2 .= " or sort=" . intval($sort_ids[$i]);
				$condition3 .= " or commentSort=" . intval($sort_ids[$i]);
			} 
		} 
		if (FALSE == empty($condition1)) $query_string .= "delete from {$this->_dbop->prefix}sort where " . substr($condition1, 3);
		if (FALSE == empty($condition2)) $query_string .= ";;delete from {$this->_dbop->prefix}blog where " . substr($condition2, 3);
		if (FALSE == empty($condition3)) $query_string .= ";;delete from {$this->_dbop->prefix}comment where " . substr($condition3, 3);
		if (FALSE == empty($query_string))
		{
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult(); 
			// 输出成功信息
			$this->showMesg($this->_lang['admin'][66], $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->_lang['editsort'][62], $_SERVER['PHP_SELF']);
	} 

	function _getSorts()
	{
		$sorts_list = array();
		$query_string = "select * from {$this->_dbop->prefix}sort order by id desc";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $sorts_list = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		return $sorts_list;
	} 

	function _getSortInfo()
	{
		$sort_info = array();
		if (TRUE == $this->sort_id)
		{
			$query_string = "select * from {$this->_dbop->prefix}sort where id={$this->sort_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $sort_info = $this->_dbop->fetchArray(0, 'ASSOC');
			$this->_dbop->freeResult();
		} 
		return $sort_info;
	} 
} 

new EditSort();

?>
