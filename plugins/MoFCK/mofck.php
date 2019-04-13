<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * FCKEditor configure manager for exBlog
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

define("PLUGIN_MoFCK_VERSION", "0.1 alpha");
define('MODULE_NAME', 'plugin-adm.mofck');
define('PLUGIN_NAME', 'MoFCK');
define('PLUGIN_LANGAGE', 'plugin.mofck');

require_once('../../include/extension.php');

class MoFCK extends ExtensionLib
{
	var $errors = array();

	function MoFCK($config_file = '')
	{
		$this->setModuleName(MODULE_NAME);
		$this->initRequired($config_file, PLUGIN_NAME, PLUGIN_LANGAGE); 
		// Set configure filename for a extension
		if (FALSE == empty($config_file))
		{
			if (FALSE == file_exists($config_file)) $this->_errors[] = $this->_lang['plugin']['mofck'][67];
			else $this->config_file = $config_file;
		} 

		if (FALSE == empty($this->_input['_GET']['action']) && 'getString' == $this->_input['_GET']['action']) $this->_getConfigItemsName();
		elseif (FALSE == empty($this->_input['_POST']['action']) && 'modify' == $this->_input['_POST']['action']) $this->modifyFckConfig();
		else $this->printModifyForm();

		exit();
	} 

	function _getConfigFile()
	{
		$config_content = '';
		$file_id = @fopen($this->config_file, 'rb');
		while (FALSE == @feof($file_id))
		{
			$buffer = @fgets($file_id, 4096);
			if ('' != trim($buffer) && FALSE == preg_match('/^[(\/\/)|(\*)|(\*\/)|(\/\*)]/', trim($buffer)))
			{
				if (TRUE == preg_match('/(.*?);\s+\/\/(.*)/', trim($buffer))) $config_content .= preg_replace('/(.*?);\s+\/\/(.*)/', '\1	;', trim($buffer));
				else $config_content .= trim($buffer);
			} 
		} 
		@fclose($file_id);
		return $config_content;
	} 

	function _getConfigItems($config_content)
	{
		$config_items = array();
		preg_match_all('/FCKConfig\.(.*?)\s*=\s*(.*?)\s+;/i', $config_content, $config_items);
		return $config_items;
	} 

	function _getConfigItemsName($config_item = '')
	{
		$config_name = '';
		$config_names = array('CustomConfigurationsPath' => $this->_lang['plugin']['mofck'][0], 
			// 'EditorAreaCSS'	=> '文本编辑区风格',
			// 'DocType'	=> '文档头',
			'BaseHref' => $this->_lang['plugin']['mofck'][1], 
			// 'FullPage'	=> '是否覆盖整个页面',
			'Debug' => $this->_lang['plugin']['mofck'][2], 
			// 'SkinPath'	=> '皮肤根URL地址',
			// 'PluginsPath'	=> '插件根URL地址',
			'AutoDetectLanguage' => $this->_lang['plugin']['mofck'][3],
			'DefaultLanguage' => $this->_lang['plugin']['mofck'][4],
			'ContentLangDirection' => $this->_lang['plugin']['mofck'][5],
			/*			'EnableXHTML'	=> 'XHTML格式输出',
			'EnableSourceXHTML'	=> '',
			'ProcessHTMLEntities'	=> '处理HTML特殊字符',
			'IncludeLatinEntities'	=> '',
			'IncludeGreekEntities'	=> '',
*/ 'FillEmptyBlocks' => $this->_lang['plugin']['mofck'][6],
			'FormatSource' => $this->_lang['plugin']['mofck'][7],
			'FormatOutput' => $this->_lang['plugin']['mofck'][8],
			/*			'FormatIndentator'	=> '',
			'GeckoUseSPAN'	=> '',
			'StartupFocus'	=> '',
*/ 'ForcePasteAsPlainText' => $this->_lang['plugin']['mofck'][9],
			'ForceSimpleAmpersand' => $this->_lang['plugin']['mofck'][10],
			'TabSpaces' => $this->_lang['plugin']['mofck'][11],
			'ShowBorders' => $this->_lang['plugin']['mofck'][12],
			'UseBROnCarriageReturn' => $this->_lang['plugin']['mofck'][13],
			'ToolbarStartExpanded' => $this->_lang['plugin']['mofck'][14],
			'ToolbarCanCollapse' => $this->_lang['plugin']['mofck'][15],
			'IEForceVScroll' => $this->_lang['plugin']['mofck'][16],
			'IgnoreEmptyParagraphValue' => $this->_lang['plugin']['mofck'][17],
			'ContextMenu' => $this->_lang['plugin']['mofck'][18],
			'FontColors' => $this->_lang['plugin']['mofck'][19], 
			// 'FontNames'	=> $this->_lang['plugin']['mofck'][20],
			// 'FontSizes'	=> $this->_lang['plugin']['mofck'][21],
			// 'FontFormats'	=> $this->_lang['plugin']['mofck'][22],
			'StylesXmlPath' => $this->_lang['plugin']['mofck'][23], 
			// 'TemplatesXmlPath'	=> 'XML模板URL地址',
			/*			'SpellChecker'	=> '拼写检查器',
			'IeSpellDownloadUrl'	=> '微软拼写检查扩展下载地址',
*/'MaxUndoLevels' => $this->_lang['plugin']['mofck'][24],
			'DisableImageHandles' => $this->_lang['plugin']['mofck'][25],
			'DisableTableHandles' => $this->_lang['plugin']['mofck'][26],
			'LinkDlgHideTarget' => $this->_lang['plugin']['mofck'][27],
			'LinkDlgHideAdvanced' => $this->_lang['plugin']['mofck'][28],
			'ImageDlgHideLink' => $this->_lang['plugin']['mofck'][29],
			'ImageDlgHideAdvanced' => $this->_lang['plugin']['mofck'][30],
			'FlashDlgHideAdvanced' => $this->_lang['plugin']['mofck'][31],
			'LinkBrowser' => $this->_lang['plugin']['mofck'][32],
			'LinkBrowserURL' => $this->_lang['plugin']['mofck'][33],
			'LinkBrowserWindowWidth' => $this->_lang['plugin']['mofck'][34],
			'LinkBrowserWindowHeight' => $this->_lang['plugin']['mofck'][35],
			'ImageBrowser' => $this->_lang['plugin']['mofck'][36],
			'ImageBrowserURL' => $this->_lang['plugin']['mofck'][37],
			'ImageBrowserWindowWidth' => $this->_lang['plugin']['mofck'][38],
			'ImageBrowserWindowHeight' => $this->_lang['plugin']['mofck'][39],
			'FlashBrowser' => $this->_lang['plugin']['mofck'][40],
			'FlashBrowserURL' => $this->_lang['plugin']['mofck'][41],
			'FlashBrowserWindowWidth' => $this->_lang['plugin']['mofck'][42],
			'FlashBrowserWindowHeight' => $this->_lang['plugin']['mofck'][43],
			'LinkUpload' => $this->_lang['plugin']['mofck'][44],
			'LinkUploadURL' => $this->_lang['plugin']['mofck'][45],
			'LinkUploadAllowedExtensions' => $this->_lang['plugin']['mofck'][46],
			'LinkUploadDeniedExtensions' => $this->_lang['plugin']['mofck'][47],
			'ImageUpload' => $this->_lang['plugin']['mofck'][48],
			'ImageUploadURL' => $this->_lang['plugin']['mofck'][49],
			'ImageUploadAllowedExtensions' => $this->_lang['plugin']['mofck'][50],
			'ImageUploadDeniedExtensions' => $this->_lang['plugin']['mofck'][51],
			'FlashUpload' => $this->_lang['plugin']['mofck'][52],
			'FlashUploadURL' => $this->_lang['plugin']['mofck'][53],
			'FlashUploadAllowedExtensions' => $this->_lang['plugin']['mofck'][54],
			'FlashUploadDeniedExtensions' => $this->_lang['plugin']['mofck'][55],
			'SmileyPath' => $this->_lang['plugin']['mofck'][56],
			'SmileyImages' => $this->_lang['plugin']['mofck'][57],
			'SmileyColumns' => $this->_lang['plugin']['mofck'][58],
			'SmileyWindowWidth' => $this->_lang['plugin']['mofck'][59],
			'SmileyWindowHeight' => $this->_lang['plugin']['mofck'][60],
			'ToolbarSets\["(.*)"\]' => $this->_lang['plugin']['mofck'][61]
			// 'ToolbarSets\["(.*)"\]'	=> "工具栏 \$1"
			);
		$i = 0;
		foreach ($config_names as $key => $value)
		{
			if (TRUE == empty($config_item)) echo "\t$i\t=>\t'$value',";
			elseif (TRUE == preg_match("/$key/", $config_item)) $config_name = preg_replace("/$key/", $value, $config_item);
			$i++;
		} 
		return $config_name;
	} 

	function _getHtmlElementByValue($config_item_value, $item_index)
	{
		$element = '';
		// Strip all quote marks
		// $config_item_value = preg_replace("/^['|\"]([^']*)['|\"]/", "\$1", $config_item_value);
		$config_item_value = htmlspecialchars($config_item_value, ENT_QUOTES);
		if ('false' == strtolower($config_item_value) || 'true' == strtolower($config_item_value))
			$element = "<select name=config_item_{$item_index}><option value='true'>{$this->_lang['plugin']['mofck'][63]}</option><option value='false'" . ((TRUE == preg_match('/false/i', $config_item_value)) ? ' selected' : '') . ">{$this->_lang['plugin']['mofck'][64]}</option></select>";
		elseif (TRUE == preg_match('/^\d+$/', $config_item_value)) $element = "<input type=text name='config_item_{$item_index}' value='$config_item_value' class=\"input\">";
		elseif (20 > strlen($config_item_value)) $element = "<input type=text name='config_item_{$item_index}' value='$config_item_value' class=\"input\">";
		else $element = "<textarea rows=8 cols=60 name='config_item_{$item_index}' wrap=\"VIRTUAL\" class=\"input\">$config_item_value</textarea>";
		return $element;
	} 

	function printModifyForm()
	{
		$this->config_items = $this->_getConfigItems($this->_getConfigFile());
		echo "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<meta Name=\"author\" content=\"exSoft\">
<meta name=\"keywords\" content=\"{$this->_config['global']['sitekeyword']}\">
<meta name=\"description\" content=\"{$this->_config['global']['Description']}\">
<meta name=\"MSSmartTagsPreventParsing\" content=\"TRUE\">
<meta http-equiv=\"MSThemeCompatible\" content=\"Yes\">
<title>{$this->_config['global']['siteName']} - Powered by exSoft</title>
<link href=\"../../images/style.css\" rel=\"stylesheet\" type=\"text/css\">
</head>
<body class=\"main\" dir=\"{$this->_lang['public']['dir']}\">
<form action={$_SERVER['PHP_SELF']} method=post>
<input type=hidden name=action value=modify>
<table width=500 border=\"0\" cellpadding=0 cellspacing=0 class=\"border\">
  <tr bgcolor=\"#99CC66\">
    <td bgcolor=\"#FFFFFF\">
      <table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"main\">
        <tr><td colspan=\"2\" class=\"menu\"><div align=\"center\">{$this->_lang['plugin']['mofck'][65]}</div></td></tr>
		";

		for ($i = 0; $i < count($this->config_items[1]); $i++)
		{
			$tmp = $this->_getConfigItemsName($this->config_items[1][$i]);
			if (FALSE == empty($tmp))
			{
				echo "<tr><td width=\"9%\" valign=\"top\" align=\"right\" nowrap>" . $this->_getConfigItemsName($this->config_items[1][$i]) . ": </td>";
				echo "<td width=\"80%\">" . $this->_getHtmlElementByValue($this->config_items[2][$i], $i) . "</td></tr>";
			} 
		} 

		echo "
<tr><td colspan=2 align=center><input type=submit value='{$this->_lang['plugin']['mofck'][62]}'></td></tr>
      </table>
    </td>
  </tr>
</table>
</form>
</body></html>
		";
	} 

	function modifyFckConfig()
	{
		$lines = '';
		$this->config_items = $this->_getConfigItems($this->_getConfigFile());
		for ($i = 0; $i < count($this->config_items[1]); $i++)
		{
			if (TRUE == isset($this->_input['_POST']["config_item_$i"])) $lines .= "FCKConfig.{$this->config_items[1][$i]}\t=\t" . $this->_input['_POST']["config_item_$i"] . " ;\n";
			else $lines .= "FCKConfig.{$this->config_items[1][$i]}\t=\t{$this->config_items[2][$i]};\n";
		} 
		$lines .= 'if( window.console ) window.console.log( \'Config is loaded!\' ) ;	// @Packager.Compactor.RemoveLine'; 
		// Backup Config File
		@rename($this->config_file, $this->config_file . '.bak'); 
		// Rewrite the configure file
		$file_id = @fopen($this->config_file, 'wb');
		$result = @fwrite($file_id, $lines);
		@fclose($file_id);
		$this->showMesg(sprintf($this->_lang['plugin']['mofck'][66], $this->config_file . '.bak'), $_SERVER['PHP_SELF']);
		return $result;
	} 

	function showMesg($mesg, $return_url)
	{
		echo "
<html><head>
<!-- css -->
<style type=text/css>
.menu { font-family: Verdana; font-size: 8pt; font-weight: bold; }
.content { font-family: Verdana; font-size: 8pt; }
.border { border: 1px #000000 dotted; }
a:visited {  color: #000000; text-decoration: none }
a:link {  color: #000000; text-decoration: none }
a:hover {  color: blue; text-decoration: underline }
</style>
<!-- css -->
<title>{$this->_lang['public'][13]}</title>
<meta http-equiv=Content-Type content=\"text/html; charset=utf-8>_lang['public']['charset']}\">
<meta HTTP-EQUIV=REFRESH CONTENT=\"2;URL=$return_url\">
</head>
<body dir=\"{$this->_lang['public']['dir']}\">
<div id=msgboard style=\"position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;\">
<table cellspacing=0 cellpadding=5 width=100% class=border>
<tr bgcolor=#99CC66>
<td height=11 align=left bgcolor=#E1E4CE class=menu>{$this->_lang['public'][14]}</td></tr><tr>
<td align=left class=content>
		";

		if (TRUE == is_array($mesg) && 1 < count($mesg))
		{
			for ($i = 0; $i < count($mesg); $i++) echo "<li><font color=red>{$mesg[$i]}</font>";
		} elseif (TRUE == is_array($mesg)) echo "<font color=red>{$mesg[0]}</font>";
		else echo "<font color=red>$mesg</font>";

		echo "
<p align=left><a href=\"$return_url\">{$this->_lang['public'][15]}</a></p></td></tr>
</table></div>
</body></html>
		";

		exit();
	} 
} 

new MoFCK('../../images/FCKeditor/fckconfig.js');

?>
