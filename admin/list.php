<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Administrator menus listing for exBlog
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

define('MODULE_NAME', 'core-adm.list');

require_once("./public.php");

class MenuList extends CommonLib
{
	function MenuList()
	{
		$this->_initEnv(MODULE_NAME, 'list', 4);

		$this->getUserInfo();

		$this->_printOutPage();

		$this->_destroyEnv();
	} 

	function _printOutPage()
	{
		$this->printPageHeader();

		echo "

<table width=\"99%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"main\">

  <tr>

    <td bgcolor=\"#FFFFFF\"> <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=\"#000000\">

        <tr bgcolor=\"#99CC66\">

          <td class=\"menu\"> <div align=\"center\">{$this->_lang['list'][0]}</div></td>

        </tr>

      </table></td>

  </tr>

  <tr>

    <td colspan=\"2\">

	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#000000\" class=\"main\">

        <tr>

          <td bgcolor=\"#FFFFFF\"><div align=\"center\"><a href=\"../index.php\"  target=\"_blank\">{$this->_lang['list'][1]}</a><br>

              <a href=\"./frame.php?action=exit\" target=\"_parent\">{$this->_lang['list'][2]}</a>

            </div></td>

        </tr>

      </table></td>

  </tr>

</table>

<br>

		";

		echo $this->_getAllMenus();

		echo "

<table width=\"99%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"main\">

  <tr>

    <td bgcolor=\"#FFFFFF\"> <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=\"#000000\">

        <tr bgcolor=\"#99CC66\">

          <td class=\"menu\"> <div align=\"center\">{$this->_lang['list'][28]}</div></td>

        </tr>

      </table></td>

  </tr>

  <tr>

    <td colspan=\"2\">

	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#000000\" class=\"main\">

        <tr>

          <td bgcolor=\"#FFFFFF\">{$this->_lang['list'][29]}<font color=\"#4B0082\">{$this->_config['global']['Version']}</font><br />{$this->_lang['list'][30]}<br>

              <a href=\"http://www.exBlog.net/\" target=\"mainFrame\">Www.exBlog.Net</font></a>

            </div></td>

        </tr>

      </table></td>

  </tr>

</table>

		";

		$this->printPageFooter();
	} 

	function _getAllMenus()
	{
		$menus = ""; 
		// Options
		$menu1 = $this->_getAMenu("./main.php", $this->_lang['list'][4], 0, 99);

		$menu1 .= $this->_getAMenu("./other.php", $this->_lang['list'][5], 1, 1);

		$menu1 .= $this->_getAMenu("./editannounce.php?action=add", $this->_lang['list'][6], 0, 1);

		$menu1 .= $this->_getAMenu("./editannounce.php?action=edit", $this->_lang['list'][7], 0, 1);

		$menu1 .= $this->_getAMenu("./editabout.php?action=edit", $this->_lang['list'][8], 0, 1);
		$menu1 .= $this->_getAMenu("./acl.php", $this->_lang['list'][41], 1, 1);

		$menus .= $this->_getMenus($this->_lang['list'][3], $menu1, 2); 
		// Blogs and Sorts
		$menu2 = $this->_getAMenu("./editblog.php?action=add", $this->_lang['list'][11], 1, 99);

		$menu2 .= $this->_getAMenu("./editblog.php?action=add&mode=adv", $this->_lang['list'][40], 1, 99);

		$menu2 .= $this->_getAMenu("./editblog.php?action=list", $this->_lang['list'][12], 0, 99);

		$menu2 .= $this->_getAMenu("./editsort.php?action=add", $this->_lang['list'][13], 0, 1);

		$menu2 .= $this->_getAMenu("./editsort.php?action=edit", $this->_lang['list'][14], 1, 1);

		$menu2 .= $this->_getAMenu("./editsort.php?action=rank", $this->_lang['list'][15], 0, 1);

		$menus .= $this->_getMenus($this->_lang['list'][10], $menu2, 2); 
		// Links
		$menu3 = $this->_getAMenu("./editlinks.php?action=add", $this->_lang['list'][17], 0, 99);

		$menu3 .= $this->_getAMenu("./editlinks.php?action=edit", $this->_lang['list'][18], 0, 99);

		$menu3 .= $this->_getAMenu("./editlinks.php?action=rank", $this->_lang['list'][19], 0, 99);

		$menus .= $this->_getMenus($this->_lang['list'][16], $menu3, 1); 
		// Tags
		$menu4 = $this->_getAMenu("./tags.php", $this->_lang['list'][38], 1, 99);

		$menus .= $this->_getMenus($this->_lang['list'][37], $menu4, 2); 
		// Comments and trackback
		$menu5 = $this->_getAMenu("./editcomment.php?action=edit", $this->_lang['list'][21], 1, 99);
		// $menu5 .= $this->_getAMenu("./edittrackback.php?action=edit", $this->_lang['list'][34], 0, 99);
		$menus .= $this->_getMenus($this->_lang['list'][20], $menu5, 1); 
		// Keyword
		$menu6 = $this->_getAMenu("./keyword.php?action=list", $this->_lang['list'][36], 0, 99);

		$menus .= $this->_getMenus($this->_lang['list'][35], $menu6, 1); 
		// Members Manager
		$menu7 = $this->_getAMenu("./edituser.php?action=add", $this->_lang['list'][23], 0, 1);

		$menu7 .= $this->_getAMenu("./edituser.php?action=list", $this->_lang['list'][24], 1, 99);

		$menus .= $this->_getMenus($this->_lang['list'][22], $menu7, 3); 
		// Database
		$menu8 = $this->_getAMenu("./backup.php", $this->_lang['list'][26], 0, 99);

		$menu8 .= $this->_getAMenu("./optimize.php", $this->_lang['list'][27], 0, 99);

		$menus .= $this->_getMenus($this->_lang['list'][25], $menu8, 1); 
		// Upload Setting
		$menu9 = $this->_getAMenu("./upload.php?action=edit", $this->_lang['list'][9], 0, 99);

		/*		$menu9 .= $this->_getAMenu("./upload.php?action=addForm", $this->_lang['list'][32], 0, 99);

		$menu9 .= $this->_getAMenu("./upload.php?action=list", $this->_lang['list'][33], 0, 99);

*/ $menus .= $this->_getMenus($this->_lang['list'][31], $menu9, 1); 
		// Plugins
		$menu10 = $this->_getAMenu("./extman.php", $this->_lang['list'][42], 1, 99);

		$menu10 .= $this->_addPluginMenu();

		if (FALSE == empty($menu10)) $menus .= $this->_getMenus($this->_lang['list'][39], $menu10, 1);

		return $menus;
	} 

	function _getAMenu($url, $text, $top, $level)
	{
		if (TRUE == $top) $menu_string = "<a href=\"$url\" target=\"mainFrame\"><font color=\"blue\">$text</font></a><br>";

		else $menu_string = "<a href=\"$url\" target=\"mainFrame\">$text</a><br>";

		if ($level > $this->member_info['uid']) return $menu_string;

		else return "";
	} 

	function _getMenus($text, $menu_list, $max_level)
	{
		$menus_string = "

<table width=\"99%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"main\">

  <tr>

    <td bgcolor=\"#FFFFFF\"> <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=\"#000000\">

        <tr bgcolor=\"#99CC66\">

          <td class=\"menu\"> <div align=\"center\">$text</div></td>

        </tr>

      </table></td>

  </tr>

  <tr>

    <td colspan=\"2\">

	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#000000\" class=\"main\">

        <tr>

          <td bgcolor=\"#FFFFFF\"><div align=\"center\">$menu_list</div></td>

        </tr>

      </table></td>

  </tr>

</table>

<br>

		";

		if ($max_level > $this->member_info['uid']) return $menus_string;

		else return "";
	} 

	function _addPluginMenu()
	{
		$tmp = ''; 
		// Get All plugins
		$plugin_list = array();

		$query_string = "select plugin_id, plugin_name, plugin_manage_url, plugin_manage_level from {$this->_dbop->prefix}plugin where plugin_manage_url != '' and plugin_enabled='1'";

		$this->_dbop->query($query_string);

		if (TRUE == $this->_dbop->getNumRows()) $plugin_list = $this->_dbop->fetchArrayBat(0, 'ASSOC');

		$this->_dbop->freeResult(); 
		// Add plugins into the list of managers
		if (TRUE == count($plugin_list))
		{
			for ($i = 0; $i < count($plugin_list); $i++)
			{
				if (FALSE == empty($plugin_list[$i]['plugin_manage_url'])) $tmp .= $this->_getAMenu("./../plugins{$plugin_list[$i]['plugin_manage_url']}", $plugin_list[$i]['plugin_name'], 0, $plugin_list[$i]['plugin_manage_level']);
			} 
		} 

		return $tmp;
	} 
} 

new MenuList();

?>
