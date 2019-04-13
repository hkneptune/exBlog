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
 *  File: frame.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-08 20:34
 *  Homepage: www.exblog.net
 *
 * $Id: frame.php,v 1.1.1.1 2005/07/01 09:21:20 feeling Exp $
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

error_reporting(0);
require_once("./public.php");

class FramePage extends CommonLib
{
	var $member_info = array();
	function FramePage()
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
		include_once("../{$this->_config['global']['langURL']}/frame.php");
		$this->_lang['frame'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/blogadmin.php");
		$this->_lang['admin'] = $langadfun;

		@session_start();
		if (FALSE == session_is_registered("exPass") || FALSE == session_is_registered("userID") || FALSE == session_is_registered("exPassword")) $this->_showError($this->_lang['public'][21], "./login.php");
		// 用户验证
//		elseif (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['public'][21], "./login.php");

		if (FALSE == empty($_GET['action']) && 'exit' == $_GET['action']) $this->logout();
		$this->_printOutPage();

		exit();
	}

	function _printOutPage()
	{
		$this->printPageHeader();

		echo "
<SCRIPT>
function switchSysBar()
{
	if (switchPoint.innerText == 3)
	{
		switchPoint.innerText = 4;
		document.all(\"frmTitle\").style.display = \"none\";
	}
	else
	{
		switchPoint.innerText = 3;
		document.all(\"frmTitle\").style.display = \"\";
	}
}
</SCRIPT>
<style type=text/css>
body
{
	margin-top: 0;
	margin-left: 0;
	margin-right: 0;
	margin-bottom: 0;
}
</style>
<TABLE border=\"0\" cellPadding=\"0\" cellSpacing=\"0\" height=\"100%\" width=\"100%\">
  <TBODY>
  <TR>
    <TD align=\"middle\" id=\"frmTitle\" noWrap=\"noWrap\" vAlign=\"center\" name=\"fmTitle\">
	<IFRAME frameBorder=\"0\" id=\"leftFrame\" name=\"leftFrame\" scrolling=\"vertical\" src=\"list.php\" style=\"HEIGHT: 100%; VISIBILITY: inherit; WIDTH: 200px; Z-INDEX: 2\"></IFRAME>
    <TD bgColor=\"#cccccc\" style=\"WIDTH: 10pt\">
      <TABLE border=\"0\" cellPadding=\"0\" cellSpacing=\"0\" height=\"100%\">
        <TBODY>
        <TR>
          <TD onclick=\"switchSysBar()\" style=\"CURSOR: hand; HEIGHT: 100%\" title=\"Display/Hide the Sidebar\">
		<SPAN class=\"navPoint\" id=\"switchPoint\">3</SPAN>
          </TD>
        </TR>
        </TBODY>
      </TABLE>
    </TD>
    <TD style=\"WIDTH: 100%\">
	<IFRAME frameBorder=\"0\" id=\"mainFrame\" name=\"mainFrame\" src=\"main.php\" style=\"HEIGHT: 100%; VISIBILITY: inherit; WIDTH: 100%; Z-INDEX: 1\"></IFRAME>
    </TD>
  </TR>
  </TBODY>
</TABLE>
		";

		$this->printPageFooter();
	}

	function logout()
	{
		setcookie("exBlogUser", "", time()-3600);
		session_start();
		session_unregister("exPass");
		session_unregister("exPassword");
		session_unregister("userID");
		session_destroy();
		$this->showMesg($this->_lang['public'][19], "./login.php");
	}
}

new FramePage();
?>