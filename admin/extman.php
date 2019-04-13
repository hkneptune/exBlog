<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Extensions managing interface for exBlog
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

define('MODULE_NAME', 'core-adm.extman');

require_once("./public.php");

class ExtensionManager extends CommonLib
{
	var $_errors = array();
	var $_dbop = 0;
	var $extensions_list = array();

	function ExtensionManager()
	{
		$this->_initEnv(MODULE_NAME, 'extman', 0);

		if (FALSE == empty($this->_input['_GET']['action']))
		{
			switch ($this->_input['_GET']['action'])
			{
				case 'enable': $this->enableOrDisableExtension();
				case 'uninstall': $this->uninstallExtension();
				default: $this->printOutExtensionsList();
			} 
		} 
		else $this->printOutExtensionsList();
		$this->_destroyEnv();
	} 

	function printOutExtensionsList()
	{
		$this->getExtensions();
		$this->printPageHeader();

		echo "
<table cellpadding=4 cellspacing=1 border=0 width=100% align=center class=border>
  <tr bgcolor=#99CC66>
    <td bgcolor=#FFFFFF class=main><br>
      <table width=100% border=0 cellpadding=1 cellspacing=1 class=main>
        <tr><td class=menu align=center><b>{$this->_lang['extman'][0]}</b></td></tr>
        <tr><td align=center>
          <table width=95% cellpadding=2 cellspacing=2 border=0 align=center class=main>
            <tr><td colspan=4>{$this->_lang['extman'][1]}</td></tr>
            <tr><td align=center>{$this->_lang['extman'][2]}</td><td>{$this->_lang['extman'][3]}</td><td align=center>{$this->_lang['extman'][7]}</td><td align=center>{$this->_lang['extman'][4]}</td><td align=center>{$this->_lang['extman'][5]}</td><td align=center>{$this->_lang['extman'][6]}</td><td align=center>{$this->_lang['extman'][8]}</td></tr>
		";
		for ($i = 0; $i < count($this->extensions_list['installed']); $i++)
		{
			$extension_info = $this->loadConfigFromFile($this->extensions_list['installed'][$i]['plugin_config_file']);
			echo "<tr>";
			echo "<td align=center>{$this->extensions_list['installed'][$i]['plugin_id']}</td>";
			echo "<td>{$this->extensions_list['installed'][$i]['plugin_name']}</td>";
			echo "<td align=center>" . ((TRUE == isset($extension_info['author'])) ? $extension_info['author'] : $this->_lang['extman'][9]) . "</td>";
			echo "<td align=center>" . strftime('%Y-%m-%d %H:%M:%S', $this->extensions_list['installed'][$i]['plugin_install_time']) . "</td>";
			echo "<td align=center>" . ((TRUE == isset($extension_info['version'])) ? $extension_info['version'] : $this->_lang['extman'][9]) . "</td>";
			echo "<td align=center>" . (('1' == $this->extensions_list['installed'][$i]['plugin_enabled']) ? $this->_lang['extman'][10] : $this->_lang['extman'][11]) . "</td>";
			echo "<td align=center>"
			 . (('' != $this->extensions_list['installed'][$i]['plugin_public_url']) ? "<a href=\"../plugins{$this->extensions_list['installed'][$i]['plugin_public_url']}\" target=_blank>{$this->_lang['extman'][12]}</a>" : '') . ' '
			 . (('' != $this->extensions_list['installed'][$i]['plugin_manage_url']) ? "<a href=\"../plugins{$this->extensions_list['installed'][$i]['plugin_manage_url']}\">{$this->_lang['extman'][13]}</a>" : '') . ' '
			 . (('1' == $this->extensions_list['installed'][$i]['plugin_enabled']) ? "<a href=\"{$_SERVER['PHP_SELF']}?action=enable&id={$this->extensions_list['installed'][$i]['plugin_id']}\">{$this->_lang['extman'][15]}</a>" : "<a href=\"{$_SERVER['PHP_SELF']}?action=enable&id={$this->extensions_list['installed'][$i]['plugin_id']}\">{$this->_lang['extman'][14]}</a>")
			 . " <a href=\"{$_SERVER['PHP_SELF']}?action=uninstall&id={$this->extensions_list['installed'][$i]['plugin_id']}\">{$this->_lang['extman'][16]}</a></td></tr>";
		} 
		echo "</table>";
		if (TRUE == count($this->extensions_list['uninstall']))
		{
			echo "<br /><table width=95% cellpadding=2 cellspacing=2 border=0 align=center class=main>
            <tr><td colspan=4>{$this->_lang['extman'][17]}</td></tr>
            <tr><td>{$this->_lang['extman'][3]}</td><td align=center>{$this->_lang['extman'][7]}</td><td align=center>{$this->_lang['extman'][5]}</td><td align=center>{$this->_lang['extman'][8]}</td></tr>";
			for ($i = 0; $i < count($this->extensions_list['uninstall']); $i++)
			{
				echo "<tr>";
				echo "<td>{$this->extensions_list['uninstall'][$i]['plugin']['name']}</td>";
				echo "<td align=center>" . ((TRUE == isset($this->extensions_list['uninstall'][$i]['plugin']['author'])) ? $this->extensions_list['uninstall'][$i]['plugin']['author'] : $this->_lang['extman'][9]) . "</td>";
				echo "<td align=center>" . ((TRUE == isset($this->extensions_list['uninstall'][$i]['plugin']['version'])) ? $this->extensions_list['uninstall'][$i]['plugin']['version'] : $this->_lang['extman'][9]) . "</td>";
				echo "<td align=center>"
				 . " <a href=\"../plugins/extension_install.php?name={$this->extensions_list['uninstall'][$i]['path']}\" target=_blank>{$this->_lang['extman'][18]}</a> <a href=\"{$_SERVER['PHP_SELF']}?action=uninstall&path={$this->extensions_list['uninstall'][$i]['path']}\">{$this->_lang['extman'][19]}</a></td></tr>";
			} 
			echo "</table>";
		} 

		echo "
        </td></tr>
      </table>
    </td>
  </tr>
</table>
		";

		$this->printPageFooter();
	} 

	/**
	 * 
	 * @brief Get a list of all extensions
	 */
	function getExtensions()
	{
		$base_path = '../plugins';
		$dirs_in_plugin = $this->_walkDir($base_path, TRUE);
		$this->extensions_list = array('installed' => array(), 'uninstall' => array());
		for ($i = 0; $i < count($dirs_in_plugin); $i++)
		{
			$extension_path = $base_path . '/' . $dirs_in_plugin[$i]['path'];
			if (TRUE == @file_exists($extension_path . '/install.ini'))
			{
				$tmp = $this->loadConfigFromFile($extension_path . '/install.ini', $dirs_in_plugin[$i]['path']);
				if (TRUE == is_array($tmp) && 0 < count($tmp)) $this->extensions_list['uninstall'][] = $tmp;
			} 
			$this->extensions_list['installed'] = $this->loadConfigFromDatabase();
		} 
		return $this->extensions_list;
	} 

	function loadConfigFromFile($config_file, $extension_name = '')
	{
		$tmp = @parse_ini_file($config_file, TRUE);
		if (TRUE == is_array($tmp) && 0 < count($tmp))
		{
			$tmp['path'] = $extension_name;
			if (FALSE == isset($tmp['plugin']['name'])) $tmp['plugin']['name'] = $extension_name;
		} 
		return $tmp;
	} 

	function loadConfigFromDatabase()
	{
		$query_string = "select * from {$this->_dbop->prefix}plugin";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $result = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $result = array();
		$this->_dbop->freeResult();
		return $result;
	} 

	function enableOrDisableExtension()
	{
		if (TRUE == empty($this->_input['_GET']['id']) || FALSE == is_numeric($this->_input['_GET']['id'])) $this->_errors[] = $this->_lang['extman'][23];
		else
		{
			$extension_id = intval($this->_input['_GET']['id']);
			$query_string = "select * from {$this->_dbop->prefix}plugin where plugin_id=$extension_id";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getNumRows()) $this->_errors[] = $this->_lang['extman'][20];
			else $extension_info = $this->_dbop->fetchArray(0, 'ASSOC');
			$this->_dbop->freeResult();
		} 

		if (FALSE == count($this->_errors))
		{
			$query_string = "update {$this->_dbop->prefix}plugin set plugin_enabled='" . (('1' == $extension_info['plugin_enabled']) ? 0 : 1) . "' where plugin_id=$extension_id";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
			$message = (('1' == $extension_info['plugin_enabled']) ? $this->_lang['extman'][22] : $this->_lang['extman'][21]);
			$this->showMesg(sprintf($message, $extension_info['plugin_name']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->_errors);
	} 

	function uninstallExtension()
	{
		if (FALSE == empty($this->_input['_GET']['id']) && TRUE == is_numeric($this->_input['_GET']['id']))
		{
			$extension_id = intval($this->_input['_GET']['id']);
			$query_string = "select * from {$this->_dbop->prefix}plugin where plugin_id=$extension_id";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getNumRows()) $this->_errors[] = $this->_lang['extman'][20];
			else $extension_info = $this->_dbop->fetchArray(0, 'ASSOC');
			$this->_dbop->freeResult();

			if (FALSE == count($this->_errors))
			{ 
				// remove record in the table
				$query_string = "delete from {$this->_dbop->prefix}plugin where plugin_id=$extension_id";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult();
			} 
		} elseif (FALSE == empty($this->_input['_GET']['path']) && FALSE == preg_match('/[^A-Za-z0-9_]/', $this->_input['_GET']['path']))
		{
			$extension_info['plugin_public_url'] = "/{$this->_input['_GET']['path']}/tmp.txt";
			$extension_info['plugin_name'] = $this->_input['_GET']['path'];
		} 
		else $this->_errors[] = $this->_lang['extman'][23];

		if (FALSE == count($this->_errors))
		{
			$result_message = sprintf($this->_lang['extman'][24], $extension_info['plugin_name']); 
			// remove all public files and managing files
			// For public files
			if (FALSE == empty($extension_info['plugin_public_url']))
			{
				$public_base_dir = '../plugins' . dirname($extension_info['plugin_public_url']);
				if (FALSE == $this->_removeDir($public_base_dir)) $result_message .= sprintf($this->_lang['extman'][25], $public_base_dir);
			} 
			// for managing files
			if (FALSE == empty($extension_info['plugin_manage_url']))
			{
				$manage_base_dir = '../plugins' . dirname($extension_info['plugin_manage_url']);
				if (FALSE == $this->_removeDir($manage_base_dir)) $result_message .= sprintf($this->_lang['extman'][26], $manage_base_dir);
			} 
			// print out result message
			$this->showMesg(sprintf($result_message, $extension_info['plugin_name']), $_SERVER['PHP_SELF']);
		} 
		else $this->_showError($this->_errors);
	} 

	function _removeDir($dir_path)
	{
		if (FALSE == @file_exists($dir_path)) return TRUE;
		elseif (FALSE == @is_dir($dir_path)) return FALSE;
		if ('/' != substr($dir_path, -1)) $dir_path .= '/';
		$dir_op = @dir($dir_path);
		while (FALSE !== ($entry = $dir_op->read()))
		{
			if ('.' == $entry || '..' == $entry) continue;
			$entry_path = $dir_path . $entry;
			if (TRUE == @is_dir($entry_path)) $this->_removeDir($entry_path);
			else @unlink($entry_path);
		} 
		$dir_op->close();
		return @rmdir($dir_path);
	} 
} 

new ExtensionManager();

?>
