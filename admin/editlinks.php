<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Friend links manager for exBlog
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

define('MODULE_NAME', 'core-adm.editlinks');

require_once("./public.php");

class EditLinks extends CommonLib
{
	var $errors = array();
	var $link_id = 0;

	function EditLinks()
	{
		$this->_initEnv(MODULE_NAME, 'editlinks', 0);

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->link_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id'])) $this->link_id = intval($this->_input['_GET']['id']);

		if (FALSE == empty($this->_input['_GET']['action']))
		{
			if ('add' == $this->_input['_GET']['action']) $this->_printEditLinks();
			elseif ('edit' == $this->_input['_GET']['action'] && FALSE == empty($this->link_id)) $this->_printEditLinks();
			elseif ('rank' == $this->_input['_GET']['action']) $this->_printLinksOrder();
			elseif ('delete' == $this->_input['_GET']['action'] && FALSE == empty($this->link_id)) $this->_deleteLinks($this->link_id);
			else $this->_printLinksList();
		} elseif (FALSE == empty($this->_input['_POST']['action']))
		{
			switch ($this->_input['_POST']['action'])
			{
				case 'addalink' : $this->_addLink();
					break;
				case 'edialink' : $this->_modifyLink();
					break;
				case 'ranalink' : $this->_changeSortRank();
					break;
				default : $this->_printEditLinks();
					break;
			} 
		} 
		else $this->_printEditLinks();

		$this->_destroyEnv();
	} 

	function _printEditLinks()
	{
		$link_info = $this->_getLinkInfo();

		$this->printPageHeader();
		echo "
<form action={$_SERVER['PHP_SELF']} method=POST>
<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table width=\"100%\" border=\"0\" class=\"main\">
      <tr><td colspan=\"2\" class=\"menu\" align=\"center\"><b>" . ((FALSE == count($link_info)) ? $this->_lang['editlinks'][0] : $this->_lang['editlinks'][11]) . "</b></td></tr>
      <tr><td width=\"20%\" height=\"23\" align=\"center\">{$this->_lang['editlinks'][1]}</td><td width=\"80%\"><input name=\"homepage\" type=\"text\" class=\"input\" id=\"homepage\" value=\"" . ((FALSE == count($link_info)) ? '' : $link_info['homepage']) . "\"></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['editlinks'][2]}</td><td><input name=\"url\" type=\"text\" class=\"input\" id=\"url\" size=\"40\" value=\"" . ((FALSE == count($link_info)) ? '' : $link_info['url']) . "\"> {$this->_lang['editlinks'][3]}</td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['editlinks'][4]}</td><td valign=\"top\"><input name=\"logoURL\" type=\"text\" class=\"input\" id=\"logoURL\" size=\"40\" value=\"" . ((FALSE == count($link_info)) ? '' : $link_info['logoURL']) . "\"> {$this->_lang['editlinks'][5]}</td></tr>
      <tr><td align=\"center\" valign=\"center\">{$this->_lang['editlinks'][6]}</td><td><textarea name=\"description\" cols=\"60\" rows=\"8\" wrap=\"VIRTUAL\" class=\"input\" id=\"description\">" . ((FALSE == count($link_info)) ? '' : $link_info['description']) . "</textarea></td></tr>
      <tr><td align=\"center\">{$this->_lang['editlinks'][7]}</td><td><input type=\"radio\" name=\"webVisible\"  value=\"1\" " . ((FALSE == empty($link_info['visible'])) ? 'checked' : '') . "> {$this->_lang['editlinks'][8]} <input type=\"radio\" name=\"webVisible\" value=\"0\" " . ((FALSE == empty($link_info['visible'])) ? '' : 'checked') . "> {$this->_lang['editlinks'][9]}</td></tr>
      <tr><td colspan=\"2\">&nbsp;</td></tr>
      <tr align=\"center\">";

		if (TRUE == count($link_info)) echo "<input type=\"hidden\" name=\"id\" value=\"{$this->link_id}\">";

		echo "<input type=\"hidden\" name=\"action\" value=\"" . ((FALSE == count($link_info)) ? 'addalink' : 'edialink') . "\"><td height=\"30\" colspan=\"2\"> <input type=\"submit\" value=\"" . ((FALSE == count($link_info)) ? $this->_lang['editlinks'][10] : $this->_lang['editlinks'][17]) . "\" class=\"botton\"></td></tr>
    </table>
  </td></tr>
</table>
</form>
		";
		$this->printPageFooter();
	} 

	function _printLinksList()
	{
		$links_info = $this->_getLinksList();

		$this->printPageHeader();

		echo "
<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table width=\"100%\" border=\"0\" class=\"main\">
      <tr><td colspan=\"5\" class=\"menu\"><b>{$this->_lang['editlinks'][11]}</b></td></tr>
		";

		for ($i = 0; $i < count($links_info); $i++)
		{
			if ('1' == $links_info[$i]['visible']) echo "
      <tr>
        <td width=\"15%\" height=\"23\" align=\"center\">&nbsp;ID:[{$links_info[$i]['id']}]</td>
        <td width=\"25%\">{$links_info[$i]['homepage']}</td>
        <td width=\"30%\" style=\"WORD-BREAK: break-all; WORD-WRAP: break-word; table-layout:fixed;\"><a href=\"{$links_info[$i]['url']}\" target=\"_blank\">{$links_info[$i]['url']}</a></td>
        <td width=\"20%\"><a href=\"{$_SERVER['PHP_SELF']}?action=edit&id={$links_info[$i]['id']}\">{$this->_lang['editlinks'][12]}</a></td>
        <td width=\"10%\"><a href=\"{$_SERVER['PHP_SELF']}?action=delete&id={$links_info[$i]['id']}\">{$this->_lang['editlinks'][13]}</a></td>
      </tr>
			";
		} 

		echo "
      <tr><td colspan=\"5\">&nbsp; </td></tr>
    </table>
    <br>
    <table width=\"100%\" border=\"0\" class=\"main\">
      <tr><td colspan=\"2\" class=\"menu\"><b>{$this->_lang['editlinks'][14]}</b></td></tr>
		";

		for ($i = 0; $i < count($links_info); $i++)
		{
			if ('0' == $links_info[$i]['visible']) echo "
      <tr>
        <td width=\"15%\" height=\"23\" align=\"center\">&nbsp;ID:[{$links_info[$i]['id']}]</td>
        <td width=\"25%\">{$links_info[$i]['homepage']}</td>
        <td width=\"30%\" style=\"WORD-BREAK: break-all; WORD-WRAP: break-word; table-layout:fixed;\"><a href=\"{$links_info[$i]['url']}\" target=\"_blank\">{$links_info[$i]['url']}</a></td>
        <td width=\"20%\"><a href=\"{$_SERVER['PHP_SELF']}?action=edit&id={$links_info[$i]['id']}\">{$this->_lang['editlinks'][15]}</a></td>
        <td width=\"10%\"><a href=\"{$_SERVER['PHP_SELF']}?action=delete&id={$links_info[$i]['id']}\">{$this->_lang['editlinks'][13]}</a></td>
      </tr>
			";
		} 

		echo "
      <tr><td colspan=\"5\">&nbsp; </td></tr>
    </table>
  </td></tr>
</table>
		";

		$this->printPageFooter();
	} 

	function _printLinksOrder()
	{
		$links_info = $this->_getLinksList();

		$this->printPageHeader();

		echo "
<form action={$_SERVER['PHP_SELF']} method=POST>
<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
  <table width=\"100%\" border=\"0\" class=\"main\">
    <tr><td colspan=\"2\" class=\"menu\"><b>{$this->_lang['editlinks'][18]}</b></td></tr>
		";

		for ($i = 0; $i < count($links_info); $i++)
		{
			echo "<tr><td align=left><a href={$_SERVER['PHP_SELF']}?action=edit&id={$links_info[$i]['id']}>{$links_info[$i]['homepage']}</a> : <a href={$links_info[$i]['url']} target=_blank>{$links_info[$i]['url']}</a></td><td align=right><input type=hidden name=ids[] value='{$links_info[$i]['id']}'><input typ=text name=order[] value='{$links_info[$i]['linkOrder']}' size=2></td></tr>";
		} 

		echo "
    <tr><td colspan=\"2\" align=center>
      <input type=\"hidden\" name=\"action\" value=\"ranalink\">
      <input type=\"submit\" value=\"{$this->_lang['editlinks'][21]}\" class=\"botton\">
    </td></tr>
  </table>
  </td></tr>
</table>
</form>
		";

		$this->printPageFooter();
	} 

	function _checkEditForm()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (TRUE == empty($this->_input['_POST']['url'])) $this->errors[] = $this->_lang['editlinks'][23];
		elseif (FALSE == preg_match("/^http:\/\//", $this->_input['_POST']['url'])) $this->errors[] = $this->_lang['editlinks'][30];
		elseif (150 < strlen($this->_input['_POST']['url'])) $this->errors[] = $this->_lang['editlinks'][27];
		if (TRUE == empty($this->_input['_POST']['logoURL'])) $this->_input['_POST']['logoURL'] = '';
		elseif (FALSE == preg_match("/^http:\/\//", $this->_input['_POST']['logoURL'])) $this->errors[] = $this->_lang['editlinks'][31];
		elseif (150 < strlen($this->_input['_POST']['logoURL'])) $this->errors[] = $this->_lang['editlinks'][28];
		if (TRUE == empty($this->_input['_POST']['homepage']) && TRUE == empty($this->_input['_POST']['url'])) $this->errors[] = $this->_lang['editlinks'][22];
		elseif (TRUE == empty($this->_input['_POST']['homepage'])) $this->_input['_POST']['homepage'] = $this->_input['_POST']['url'];
		elseif (20 < strlen($this->_input['_POST']['homepage'])) $this->errors[] = $this->_lang['editlinks'][26];
		if (TRUE == empty($this->_input['_POST']['description']) && TRUE == empty($this->_input['_POST']['url'])) $this->errors[] = $this->_lang['editlinks'][24];
		elseif (TRUE == empty($this->_input['_POST']['description'])) $this->_input['_POST']['description'] = $this->_input['_POST']['url'];
		elseif (100 < strlen($this->_input['_POST']['description'])) $this->errors[] = $this->_lang['editlinks'][29];
		if (FALSE == isset($this->_input['_POST']['webVisible']) || FALSE == is_numeric($this->_input['_POST']['webVisible'])) $this->_input['_POST']['webVisible'] = 1;
		elseif (TRUE == empty($this->_input['_POST']['webVisible'])) $this->_input['_POST']['webVisible'] = 0;
		else $this->_input['_POST']['webVisible'] = 1;
	} 

	function _checkRankOrderForm()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (TRUE == empty($this->_input['_POST']['ids']) || FALSE == is_array($this->_input['_POST']['ids']) || FALSE == count($this->_input['_POST']['ids']))
			$this->errors[] = $this->_lang['editlinks'][36];
		if (TRUE == empty($this->_input['_POST']['order']) || FALSE == is_array($this->_input['_POST']['order']) || FALSE == count($this->_input['_POST']['order']))
			$this->errors[] = $this->_lang['editlinks'][36];
	} 

	function _addLink()
	{
		$this->_checkEditForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 链接是否已经存在？
		$query_string = "select * from {$this->_dbop->prefix}links where url='" . trim($this->_input['_POST']['url']) . "'";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['editlinks'][32];
		$this->_dbop->freeResult();

		if (FALSE == count($this->errors))
		{
			$query_string = "insert into {$this->_dbop->prefix}links (homepage, logoURL, url, description, visits, visible, linkOrder) values ('{$this->_input['_POST']['homepage']}', '" . trim($this->_input['_POST']['logoURL']) . "', '" . trim($this->_input['_POST']['url']) . "', '{$this->_input['_POST']['description']}', 0, '{$this->_input['_POST']['webVisible']}', 0)";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult(); 
			// 输出成功信息
			$this->showMesg(sprintf($this->_lang['editlinks'][33], $this->_input['_POST']['homepage']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->errors);
	} 

	function _modifyLink()
	{
		$this->_checkEditForm();
		if (TRUE == count($this->errors)) $this->_showError($this->errors); 
		// 链接是否已经存在？
		$query_string = "select * from {$this->_dbop->prefix}links where id={$this->link_id}";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['editlinks'][34];
		else $old_link_info = $this->_dbop->fetchArray(0, 'ASSOC');
		$this->_dbop->freeResult();

		if (FALSE == count($this->errors))
		{
			$query_string = "update {$this->_dbop->prefix}links set
				homepage='{$this->_input['_POST']['homepage']}',
				logoURL='" . trim($this->_input['_POST']['logoURL']) . "',
				url='" . trim($this->_input['_POST']['url']) . "',
				description='{$this->_input['_POST']['description']}',
				visible='{$this->_input['_POST']['webVisible']}'
				where id={$this->link_id}";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult(); 
			// 输出成功信息
			$this->showMesg(sprintf($this->_lang['editlinks'][35], $old_link_info['homepage']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->errors);
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
			$query_string .= ";;update {$this->_dbop->prefix}links set linkOrder=$tmp_sort_order where id=" . intval($this->_input['_POST']['ids'][$i]);
		} 
		if (FALSE == empty($query_string))
		{
			$this->_dbop->query(substr($query_string, 2));
			$this->_dbop->freeResult(); 
			// 输出成功信息
			$this->showMesg($this->_lang['admin'][59], $_SERVER['PHP_SELF']);
		} 
	} 

	function _deleteLinks($link_ids)
	{
		if (FALSE == is_array($link_ids)) $link_ids = explode(",", $link_ids);
		$query_string = "";
		$condition = "";
		for ($i = 0; $i < count($link_ids); $i++)
		{
			if (TRUE == is_numeric($link_ids[$i])) $condition .= " or id=" . intval($link_ids[$i]);
		} 
		if (FALSE == empty($condition)) $query_string .= "delete from {$this->_dbop->prefix}links where " . substr($condition, 3);
		if (FALSE == empty($query_string))
		{
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult(); 
			// 输出成功信息
			$this->showMesg($this->_lang['admin'][22], $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->_lang['admin'][21], $_SERVER['PHP_SELF']);
	} 

	function _getLinksList()
	{
		$query_string = "select * from {$this->_dbop->prefix}links";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $links_info = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $links_info = array();
		$this->_dbop->freeResult();
		return $links_info;
	} 

	function _getLinkInfo()
	{
		$link_info = array();
		if (FALSE == empty($this->link_id))
		{
			$query_string = "select * from {$this->_dbop->prefix}links where id={$this->link_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $link_info = $this->_dbop->fetchArray(0, 'ASSOC');
			else $this->_showError($this->_lang['editlinks'][34], $_SERVER['PHP_SELF']);
			$this->_dbop->freeResult();
		} 
		return $link_info;
	} 
} 

new EditLinks();

?>
