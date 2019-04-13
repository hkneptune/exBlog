<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Access Control List managing interface for exBlog
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

define('MODULE_NAME', 'core-adm.acl');

require_once("./public.php");

/**
 * 
 * @class AclManager acl.php "/admin/acl.php"
 * @brief Manage your ACL rules
 * @author $Author$ 
 * @Last Modified $Date$
 */
class AclManager extends CommonLib
{
	var $_errors = array();
	var $_dbop = 0;
	var $_acl_rules = array();

	function AclManager()
	{
		$this->_initEnv(MODULE_NAME, 'acl', 0);

		if (FALSE == empty($this->_input['_POST']['action']) && 'modify' == $this->_input['_POST']['action']) $this->UpdateAcl();
		$this->printEditPage();

		$this->_destroyEnv();
	} 

	/**
	 * 
	 * @brief Print out the page for managing ACL rules
	 */
	function printEditPage()
	{
		$this->printPageHeader();

		echo "
<table cellpadding=4 cellspacing=1 border=0 width=100% align=center class=border>
  <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
  <input type=hidden name=action value=modify>
  <tr bgcolor=#99CC66>
    <td bgcolor=#FFFFFF class=main><br>
      <table width=100% border=0 cellpadding=1 cellspacing=1 class=main>
        <tr><td class=menu align=center><b>{$this->_lang['acl'][0]}</b></td></tr>
        <tr><td>
          <table width=90% border=0 cellpadding=2 cellspacing=2 align=center class=main>
            <tr><td valign=top nowrap>{$this->_lang['acl'][3]}</td><td>{$this->_lang['acl'][5]}</td></tr>
            <tr><td valign=top nowrap>{$this->_lang['acl'][4]}</td><td>
		";
		$usable_module_names = $this->_getUsableModuleNames();
		for ($i = 0; $i < count($usable_module_names); $i++) echo $usable_module_names[$i]['module_name'] . " &nbsp; ";
		echo "</td></tr>
          </table>
        </td></tr>
        <tr><td align=center><textarea name=acl_rules rows=10 cols=80 wrap=VIRTUAL class=input>" . $this->buildRulesLine($this->_readAclRules()) . "</textarea></td></tr>
        <tr><td align=center><input type=submit value={$this->_lang['acl'][1]}> &nbsp; &nbsp; <input type=reset value={$this->_lang['acl'][2]}></td></tr>
      </table>
    </td>
  </tr>
  </form>
</table>
		";

		$this->printPageFooter();
	} 

	/**
	 * 
	 * @brief Update ACL rules using the POST data
	 * @notice The table acl would be empty before update ACL rules
	 */
	function UpdateAcl()
	{
		$query_string = "TRUNCATE TABLE {$this->_dbop->prefix}acl;;";
		$tmp = $this->_getUsableModuleNames();
		$usable_module_names = array();
		for ($i = 0; $i < count($tmp); $i++) $usable_module_names[] = $tmp[$i]['module_name'];
		$rules_lines = explode("\n", str_replace("\r\n", "\n", $this->_input['_POST']['acl_rules']));
		for ($i = 0; $i < count($rules_lines); $i++)
		{
			if ('' == trim($rules_lines[$i])) continue;
			$tmp_line = explode(' ', rtrim($rules_lines[$i]));
			if (4 > count($tmp_line)) continue;
			elseif ('' == trim($tmp_line[0]) || '' == trim($tmp_line[1]) || '' == trim($tmp_line[2]) || '' == trim($tmp_line[3])) continue;
			elseif (FALSE == in_array($tmp_line[0], $usable_module_names) && FALSE == preg_match('/^[core]|[plugin]|\*\-/', $tmp_line[0])) continue;
			elseif ('custom' != $tmp_line[1] && 'default' != $tmp_line[1]) continue;
			elseif ('accept' != $tmp_line[2] && 'drop' != $tmp_line[2]) continue;
			if (TRUE == empty($tmp_line[4])) $tmp_line[4] = '';
			if (TRUE == empty($tmp_line[5])) $tmp_line[5] = '';
			$query_string .= "insert into {$this->_dbop->prefix}acl (acl_ipzone, acl_action, acl_type, acl_module, acl_refer, acl_agent) values ('{$tmp_line[3]}', '{$tmp_line[2]}', '{$tmp_line[1]}', '{$tmp_line[0]}','{$tmp_line[5]}', '{$tmp_line[4]}');;";
		} 
		$query_string = substr($query_string, 0, -2);
		$this->_dbop->query($query_string);
		$this->_dbop->freeResult();
	} 

	/**
	 * 
	 * @brief Get ACL rules
	 * @param  $module_name Module name, it must be same as what saved in the module list
	 * @return A list of all matched rules. all elements in the list use a same name as name of fields in the table acl
	 */
	function _readAclRules($module_name = '*-*')
	{
		$tmp_query_condition = ''; 
		// Generate SQL query string
		$query_string = "select * from {$this->_dbop->prefix}acl";
		$tmp_splitor = explode('-', $module_name);
		if (2 > count($tmp_splitor)) $this->_errors[] = 'Invalid module found!';
		elseif ('*-*' != $module_name)
		{
			if ('*' == $tmp_splitor[0]) $tmp_query_condition .= " where acl_module LIKE '%-";
			else $tmp_query_condition .= " where acl_module LIKE '{$tmp_splitor[0]}-";
			if ('*' == $tmp_splitor[1]) $tmp_query_condition .= "%'";
			else $tmp_query_condition .= "{$tmp_splitor[1]}'";
		} 
		$query_string .= $tmp_query_condition . ' order by acl_module desc, acl_type desc, acl_action asc'; 
		// Fetch ACL rules
		if (FALSE == count($this->_errors))
		{
			$this->_dbop->query($query_string);
			$this->_acl_rules = $this->_dbop->fetchArrayBat(0, 'ASSOC');
			$this->_dbop->freeResult();
		} 
		return $this->_acl_rules;
	} 

	/**
	 * 
	 * @brief Format the acl rule records to human-readable
	 * @param  $acl_rules ACL rules
	 */
	function buildRulesLine($acl_rules)
	{
		$rules_line = '';
		for ($i = 0; $i < count($acl_rules); $i++) $rules_line .= "{$acl_rules[$i]['acl_module']} {$acl_rules[$i]['acl_type']} {$acl_rules[$i]['acl_action']} {$acl_rules[$i]['acl_ipzone']} {$acl_rules[$i]['acl_agent']} {$acl_rules[$i]['acl_refer']}\n";
		return $rules_line;
	} 

	/**
	 * 
	 * @brief Get a list of all module names registered
	 */
	function _getUsableModuleNames()
	{
		$query_string = "select module_name from {$this->_dbop->prefix}module order by module_id asc";
		$this->_dbop->query($query_string);
		$module_names = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		return $module_names;
	} 
} 

new AclManager();

?>
