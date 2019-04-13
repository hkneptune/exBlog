<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * frame page for exBlog
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

define('MODULE_NAME', 'core-adm.frame');

require_once("./public.php");

class FramePage extends CommonLib
{
	var $member_info = array();
	function FramePage()
	{
		$this->_initEnv(MODULE_NAME, 'frame', 99);

		if (FALSE == empty($this->_input['_GET']['action']) && 'exit' == $this->_input['_GET']['action']) $this->logout();
		$this->_printOutPage();

		$this->_destroyEnv();
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
.navPoint {
	COLOR: white;
	FONT-FAMILY: Webdings;
	FONT-SIZE: 9pt;
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
          <TD onclick=\"switchSysBar()\" style=\"CURSOR: hand; HEIGHT: 100%\" title=\"{$this->_lang['frame'][1]}\">
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
		@session_start();
		session_unregister("exPass");
		session_unregister("exPassword");
		session_unregister("userID");
		session_destroy();
		$this->showMesg($this->_lang['public'][19], "./login.php");
	} 
} 

new FramePage();

?>
