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
 *  File: keyword.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-07-16 22:33
 *  Homepage: www.exblog.net
 *
 * $Id$
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once("./public.php");

class Keyword extends CommonLib
{
	var $errors = array();
	var $keyword_id = 0;

	function Keyword()
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
		include_once("../{$this->_config['global']['langURL']}/keyword.php");
		$this->_lang['keyword'] = $lang;

		// 用户验证
		if (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['public'][21], "./login.php");

		// 输入数据
		$this->_input['_POST'] = $_POST;

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->keyword_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($_GET['id']) && TRUE == is_numeric($_GET['id'])) $this->keyword_id = intval($_GET['id']);

		if (FALSE == empty($this->_input['_POST']['action']))
		{
			switch ($this->_input['_POST']['action'])
			{
				case 'add' : $this->_addKeyword(); break;
				case 'modify' : $this->_modifyKeyword(); break;
				default : $this->_printMainPage(); break;
			}
		}
		elseif (FALSE == empty($_GET['action']) && 'delete' == $_GET['action']) $this->_deleteKeyword();
		else $this->_printMainPage();

		exit();
	}

	function _printMainPage()
	{
		$this->printPageHeader();

		$keyword_info = $this->_getKeywordInfo();

		echo "
<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
    <table width=\"100%\" border=\"0\" class=\"main\">
      <tr><td colspan=\"2\" class=\"menu\" align=\"center\"><b>" . ((FALSE == empty($this->keyword_id)) ? $this->_lang['keyword'][10] : $this->_lang['keyword'][3]) . "</b></td></tr>
      <tr><td width=\"20%\" height=\"23\" align=\"center\">{$this->_lang['keyword'][4]}</td><td width=\"80%\"><input name=\"word\" type=\"text\" class=\"input\" id=\"word\" value=\"" . ((FALSE == empty($this->keyword_id)) ? $keyword_info['word'] : '') . "\"></td></tr>
      <tr><td height=\"23\" align=\"center\">{$this->_lang['keyword'][5]}</td><td valign=\"top\"><input name=\"url\" type=\"text\" class=\"input\" id=\"url\" size=\"40\" value=\"" . ((FALSE == empty($this->keyword_id)) ? $keyword_info['url'] : '') . "\"> {$this->_lang['keyword'][6]}</td></tr>
      <tr><td align=\"center\">{$this->_lang['keyword'][7]}</td><td><textarea name=\"content\" cols=\"60\" rows=\"8\" wrap=\"VIRTUAL\" class=\"input\" id=\"content\">" . ((FALSE == empty($this->keyword_id)) ? $keyword_info['content'] : '') . "</textarea></td></tr>
      <tr align=\"center\"><td height=\"30\" colspan=\"2\">
		";

		if (FALSE == empty($this->keyword_id))
			echo "<input type=\"hidden\" name=\"id\" value=\"{$this->keyword_id}\"><input type=\"hidden\" name=\"action\" value=\"modify\"><input type=\"submit\" value=\"{$this->_lang['keyword'][11]}\" class=\"botton\">";
		else
			echo "<input type=\"hidden\" name=\"action\" value=\"add\"><input type=\"submit\" value=\"{$this->_lang['keyword'][8]}\" class=\"botton\">";

		echo "</td></tr>
    </table>
    </form>

    <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"main\">
      <tr align=\"center\"><td height=\"25\" colspan=\"4\" class=\"menu\"><b>{$this->_lang['keyword'][9]}</b></td></tr>
		";

		$keywords = $this->_getKeywordsList();

		for ($i = 0; $i < count($keywords); $i++)
		{
			echo "
      <tr class=\"main\">
        <td width=\"15%\" height=\"23\"><a href=\"./keyword.php?action=edit&id={$keywords[$i]['id']}\">{$keywords[$i]['word']}</a></td>
        <td width=\"65%\"><a href=\"./keyword.php?action=edit&id={$keywords[$i]['id']}\">{$keywords[$i]['content']}</a></td>
        <td width=\"10%\"><a href=\"./keyword.php?action=edit&id={$keywords[$i]['id']}\">{$this->_lang['keyword'][0]}</a></td>
        <td width=\"10%\"><a href=\"./keyword.php?action=delete&id={$keywords[$i]['id']}\" onclick=\"if(confirm ('{$this->_lang['keyword'][1]}')){;}else{return false;}\">{$this->_lang['keyword'][2]}</a></td>
      </tr>
			";
		}

		echo "</table></td></tr></table>";

		$this->printPageFooter();
	}

	function _getKeywordsList()
	{
		$keywords = array();
		$query_string = "select * from {$this->_dbop->prefix}keyword";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $keywords = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		return $keywords;
	}

	function _getKeywordInfo()
	{
		$keyword_info = array();
		if (FALSE == empty($this->keyword_id))
		{
			$query_string = "select * from {$this->_dbop->prefix}keyword where id={$this->keyword_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $keyword_info = $this->_dbop->fetchArray(0, 'ASSOC');
			else
			{
				$this->keyword_id = 0;
				$this->errors[] = $this->_lang['keyword'][12];
			}
			$this->_dbop->freeResult();
		}
		return $keyword_info;
	}

	function _addKeyword()
	{
		$this->_checkAddForm();
		if (FALSE == count($this->errors))
		{
			// 检查名词是否已经存在
			$query_string = "select word from {$this->_dbop->prefix}keyword where word='{$this->_input['_POST']['word']}'";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = sprintf($this->_lang['keyword'][18], $this->_input['_POST']['word']);
			$this->_dbop->freeResult();

			if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);

			$query_string = "insert into {$this->_dbop->prefix}keyword (word, content, url) values ('" . $this->_input['_POST']['word'] . "', '" . $this->_input['_POST']['content'] . "', '" . $this->_input['_POST']['url'] . "')";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
			$this->showMesg(sprintf($this->_lang['keyword'][17], $this->_input['_POST']['word']), $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	}

	function _modifyKeyword()
	{
		$this->_checkAddForm();
		if (FALSE == count($this->errors))
		{
			// 检查名词是否已经存在
			$old_keyword_info = $this->_getKeywordInfo();

			if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);

			$query_string = "update {$this->_dbop->prefix}keyword set
				word='" . $this->_input['_POST']['word'] . "',
				content='" . $this->_input['_POST']['content'] . "',
				url='" . $this->_input['_POST']['url'] . "'
				where id={$this->keyword_id}";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
			$this->showMesg(sprintf($this->_lang['keyword'][21], $old_keyword_info['word']), $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	}

	function _deleteKeyword()
	{
		$old_keyword_info = $this->_getKeywordInfo();
		if (FALSE == empty($this->keyword_id))
		{
			$query_string = "delete from {$this->_dbop->prefix}keyword where id={$this->keyword_id}";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
			$this->showMesg(sprintf($this->_lang['keyword'][23], $old_keyword_info['word']), $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->_lang['keyword'][22], $_SERVER['PHP_SELF']);
	}

	function _checkAddForm()
	{
		$this->escapeSqlCharsFromData($this->_input['_POST']);
		if (TRUE == empty($this->_input['_POST']['word'])) $this->errors[] = $this->_lang['keyword'][13];
		elseif (100 < strlen($this->_input['_POST']['word'])) $this->errors[] = $this->_lang['keyword'][14];
		if (TRUE == empty($this->_input['_POST']['url'])) $this->_input['_POST']['url'] = '';
		elseif (FALSE == preg_match("/^http:\/\//i", $this->_input['_POST']['url'])) $this->errors[] = $this->_lang['keyword'][15];
		elseif (150 < strlen($this->_input['_POST']['url'])) $this->errors[] = $this->_lang['keyword'][16];
		if (TRUE == empty($this->_input['_POST']['content'])) $this->errors[] = $this->_lang['keyword'][19];
	}
}

new Keyword();
?>