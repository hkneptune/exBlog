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
 *  File: editblog.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-26 18:17
 *  Homepage: www.exblog.net
 *
 * $Id: editblog.php,v 1.3 2005/07/02 05:38:33 feeling Exp $
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require_once("./public.php");

class EditBlog extends CommonLib
{
	var $errors = array();
	var $show_more = FALSE;
	var $author = '';
	var $got_options = FALSE;
	var $current_time = 0;
	var $current_page = 1;
	var $total_page = 1;
	var $page_size = 30;
	var $blog_count = 0;
	var $blog_id = 0;

	function EditBlog()
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
		include_once("../{$this->_config['global']['langURL']}/editblog.php");
		$this->_lang['editblog'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/javascript.php");
		$this->_lang['javascript'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/blogadmin.php");
		$this->_lang['admin'] = $langadfun;
		// 输入数据
		$this->_input['_POST'] = $_POST;
		$this->_input['_GET'] = $_GET;

		// 用户验证
		if (FALSE == $this->checkUser(1)) $this->_showError($this->_lang['public'][21], "./login.php");

		// 检查是否指定blog ID
		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->blog_id = intval($this->_input['_POST']['id']);
		if (FALSE == empty($_GET['id']) && TRUE == is_numeric($_GET['id'])) $this->blog_id = intval($_GET['id']);

		if (TRUE == empty($_GET['action']) && TRUE == empty($this->_input['_POST']['action'])) $this->_printBlogList();
		elseif (FALSE == empty($this->_input['_POST']['action']) && 'actaddablog' == $this->_input['_POST']['action']) $this->_addBlogHandler();
		elseif (FALSE == empty($this->_input['_POST']['action']) && 'actediablog' == $this->_input['_POST']['action']) $this->_modifyBlogHandler();
		elseif (FALSE == empty($_GET['action']) && 'delete' == $_GET['action'] && FALSE == empty($this->blog_id)) $this->_removeBlogs($this->blog_id);
		elseif (FALSE == empty($_GET['action']) && ('add' == $_GET['action'] || ('modify' == $_GET['action'] && FALSE == empty($this->blog_id)))) $this->_printModifyPage();
		else $this->_printBlogList();

		exit();
	}

	// 输出BLOG列表页面
	function _printBlogList()
	{
		$this->_getOptionByULevel($_SESSION['userID'], 1);
		$blogs_list = $this->_getBlogList();
		$this->printPageHeader();
		echo "<table width=\"95%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\"><tr><td height=\"180\" valign=\"top\"><table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\"><tr><td class=\"menu\" colspan=\"5\" height=25 align=\"center\"><b>{$this->_lang['editblog'][91]}</b></td></tr><tr><td height=\"23\">&nbsp;</td><td colspan=\"2\"><font color=\"#FF0000\">#</font> <font color=\"#0000FF\">{$this->_lang['editblog'][92]}</font> <input name=\"searchID\" type=\"text\" class=\"input\" size=\"5\" onKeyDown=\"if(event.keyCode==13)event.returnValue=false\"></td><td><input type=\"submit\" name=\"action\" value=\"{$this->_lang['editblog'][122]}\" class=\"botton\"></td><td><input type=\"submit\" name=\"action\" value=\"{$this->_lang['editblog'][123]}\" class=\"botton\"></td></tr>";
		if (TRUE == is_array($blogs_list) && TRUE == count($blogs_list))
		{
			for ($i = 0; $i < count($blogs_list); $i++)
			{
				echo "<tr><td width=\"6%\" height=\"23\">id:[{$blogs_list[$i]['id']}]</td><td width=\"3%\"><div align=\"right\"><input type=\"checkbox\" name=\"article[]\" value=\"{$blogs_list[$i]['id']}\"></div></td><td width=\"36%\"><a href=\"../admin/editblog.php?action=modify&id={$blogs_list[$i]['id']}\" title=\"{$this->_lang['editblog'][93]}\">{$blogs_list[$i]['title']}</a></td><td width=\"5%\"><a href=\"../admin/editblog.php?action=modify&id={$blogs_list[$i]['id']}\">{$this->_lang['editblog'][94]}</a></td><td width=\"5%\" nowrap><a href=\"../admin/editblog.php?action=modify&id={$blogs_list[$i]['id']}&mode=adv\">{$this->_lang['editblog'][128]}</a></td><td width=\"5%\"><a href=\"../admin/./editblog.php?action=delete&id={$blogs_list[$i]['id']}\" onclick=\"if(confirm ('" . $this->_lang['editblog'][95] . "')){;}else{return false;}\">{$this->_lang['editblog'][96]}</a></td></tr>";
			}
		}

		echo "<tr><td colspan=\"5\"><div align=\"right\">{$this->_lang['editblog'][116]}<select name=\"b_sort\" class=\"botton\">";

		$query_string = "select id, cnName from {$this->_dbop->prefix}sort";
		$this->_dbop->query($query_string);
		$result = $this->_dbop->fetchArrayBat(0, "ASSOC");
		$this->_dbop->freeResult();
		if (TRUE == is_array($result) && TRUE == count($result))
		{
			for ($i = 0; $i < count($result); $i++) echo "<option value=\"{$result[$i]['id']}\">{$result[$i]['cnName']}</option>";
		}

		echo "</select><input type=\"submit\" name=\"action\" value=\"{$this->_lang['editblog'][124]}\" class=\"botton\" onclick=\"if(confirm ('" . $this->_lang['editblog'][118] . "')){;}else{return false;}\"> &nbsp;{$this->_lang['editblog'][119]}<input type=\"submit\" name=\"action\" value=\"{$this->_lang['editblog'][125]}\" class=\"botton\" onclick=\"if(confirm ('" . $this->_lang['editblog'][95] . "')){;}else{return false;}\"></div></td></tr><tr><td colspan=\"5\"><br>{$this->_lang['editblog'][97]}<br>" . $this->_printPageCounter() . "<br></td></tr>";

		if (TRUE == $this->show_more)
		{
			echo "<tr><td colspan=\"5\"><hr><b>{$this->_lang['editblog'][98]}</b><br />{$this->_lang['editblog'][99]} {$this->_lang['editblog'][100]} <input name=\"s_addtime\" type=\"text\" class=\"input\" title=\"{$this->_lang['editblog'][101]}\" size=\"5\">{$this->_lang['editblog'][102]}<select name=\"s_sort\" class=\"botton\"><option value=\"NULL\" selected>{$this->_lang['editblog'][103]}</option>";
			if (TRUE == is_array($result) && TRUE == count($result))
			{
				for ($i = 0; $i < count($result); $i++) echo "<option value=\"{$result[$i]['id']}\">{$result[$i]['cnName']}</option>";
			}
			echo "</select>{$this->_lang['editblog'][104]}<input name=\"s_title\" type=\"text\" class=\"input\" title=\"{$this->_lang['editblog'][105]}\" size=\"5\">{$this->_lang['editblog'][106]}<input name=\"s_content\" type=\"text\" class=\"input\" title=\"{$this->_lang['editblog'][107]}\" size=\"5\"><select name=\"s_sc\" class=\"botton\"><option value=\"DESC\" selected>{$this->_lang['editblog'][113]}</option><option value=\"ASC\">{$this->_lang['editblog'][114]}</option></select>{$this->_lang['editblog'][115]}<input type=\"submit\" name=\"action\" value=\"{$this->_lang['editblog'][126]}\" class=\"botton\">";
		}

		echo "</td></tr></table></form>";

		$this->printPageFooter();
	}

	// 输出添加/修改BLOG页面
	function _printModifyPage()
	{
		$this->current_time = time();
		if (FALSE == empty($this->blog_id) && TRUE == is_numeric($this->blog_id)) $blog_info = $this->_getBlogInfo();
		else $blog_info = array();
		if (FALSE == empty($blog_info['addtime'])) $this->current_time = $blog_info['addtime'];
		$this->printPageHeader();

		echo "
<script language=\"JavaScript\" type=\"text/javascript\" src=\"cal_images/popcalendar.js\"></script>
<script language=JavaScript>
<!--
var checklength_t1 = \"{$this->_lang['javascript'][85]}\";
var checklength_t2 = \"{$this->_lang['javascript'][86]}\";

var pasteCtext = \"{$this->_lang['javascript'][87]}\";

var vertify_t1 = \"{$this->_lang['javascript'][88]}\";


var check_t1 = \"{$this->_lang['javascript'][89]}\";
var check_t2 = \"{$this->_lang['javascript'][90]}\";
var check_t3 = \"{$this->_lang['javascript'][91]}\";
var check_t4 = \"{$this->_lang['javascript'][92]}\";

var check_t5 = \"{$this->_lang['javascript'][93]}\";
var check_t6 = \"{$this->_lang['javascript'][94]}\";
var check_t7 = \"{$this->_lang['javascript'][95]}\";
var check_t8 = \"{$this->_lang['javascript'][96]}\";
var check_t9 = \"{$this->_lang['javascript'][97]}\";

var check_t10 = \"{$this->_lang['javascript'][98]}\";

var check_t11 = \"{$this->_lang['javascript'][99]}\";
var check_t12 = \"{$this->_lang['javascript'][100]}\";
var check_t13 = \"{$this->_lang['javascript'][101]}\";
var check_t14 = \"{$this->_lang['javascript'][102]}\";
var check_t15 = \"{$this->_lang['javascript'][103]}\";
var check_t16 = \"{$this->_lang['javascript'][104]}\";
var check_t17 = \"{$this->_lang['javascript'][105]}\";
var check_t18 = \"{$this->_lang['javascript'][106]}\";
var check_t19 = \"{$this->_lang['javascript'][107]}\";
var check_t20 = \"{$this->_lang['javascript'][108]}\";
var check_t21 = \"{$this->_lang['javascript'][109]}\";
var check_t22 = \"{$this->_lang['javascript'][110]}\";

function MM_reloadPage(init)
{  //reloads the window if Nav4 resized
	if (init==true) with (navigator)
	{
  		if ((appName==\"Netscape\") && (parseInt(appVersion)==4))
		{
			document.MM_pgW=innerWidth;
			document.MM_pgH=innerHeight;
			onresize=MM_reloadPage;
		}
	}
	else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function vertify()
{
	if (document.exEdit.esort.value==\"\")
	{
		alert(vertify_t1);
		return false;
	}
	else
	{
		return true;
	}
}

function adcheck()
{
	if (exEdit.title.value==\"\")
	{
		alert(\"{$this->_lang['javascript'][111]}\");
		exEdit.title.focus();
		return false;
	}
	if (exEdit.author.value==\"\")
	{
		alert (\"{$this->_lang['javascript'][112]}\");
		exEdit.author.focus();
		return false;
	}
	if (exEdit.content.value==\"\")
	{
		alert (\"{$this->_lang['javascript'][113]}\");
		exEdit.content.focus();
		return false;
	}
	if (exEdit.content.value.length<6)
	{
		alert (\"{$this->_lang['javascript'][114]}\");
		exEdit.content.focus();
		return false;
	}
	copyC();
}

function onlyNum()
{
	if(!((event.keyCode>=48 && event.keyCode<=57) || (event.keyCode>=96 && event.keyCode<=105) || event.keyCode == 8)) event.returnValue = false;
}
//-->
</script>
<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\" name=\"exEdit\">
<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr>
    <td height=\"180\">
      <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
        <tr><td class=\"menu\" colspan=4 height=25 align=\"center\"><b>" . ((FALSE == empty($blog_info['id'])) ? $this->_lang['editblog'][1] : $this->_lang['editblog'][90]) . "</b></td></tr>
        <tr>
          <td height=\"23\" align=\"center\">{$this->_lang['editblog'][2]}</td>
          <td><input name=\"title\" type=\"text\" size=\"30\" value=\"" . ((FALSE == empty($blog_info['title'])) ? $blog_info['title'] : '') . "\" class=\"input\"><select name=\"isTop\" id=\"isTop\"><option value=\"0\"" . ((TRUE == isset($blog_info['top']) && 0 == $blog_info['top']) ? ' selected' : '') . ">{$this->_lang['admin'][117]}</option><option value=\"1\"" . ((TRUE == isset($blog_info['top']) && 1 == $blog_info['top']) ? ' selected' : '') . ">{$this->_lang['admin'][116]}</option></select></td>
          <td align=\"center\">{$this->_lang['editblog'][3]}</td>
          <td><select name=\"weather\" class=\"botton\">";

		$query_string = "select * from {$this->_dbop->prefix}weather";
		$this->_dbop->query($query_string);
		$result = $this->_dbop->fetchArrayBat(0, "ASSOC");
		$this->_dbop->freeResult();
		if (TRUE == is_array($result) && TRUE == count($result))
		{
			for ($i = 0; $i < count($result); $i++)
			{
				echo "<option value=\"{$result[$i]['enWeather']}\"";
				if (FALSE == empty($blog_info['weather']) && $result[$i]['enWeather'] == $blog_info['weather']) echo " selected";
				echo ">{$result[$i]['cnWeather']}</option>";
			}
		}

		@session_start();
		echo "
          </select></td>
        </tr>
        <tr>
          <td height=\"23\" align=\"center\">{$this->_lang['editblog'][4]}</td>
          <td><input name=\"author\" type=\"text\" value=\"" . ((FALSE == empty($blog_info['author'])) ? $blog_info['author'] : $_SESSION['exPass']) . "\" class=\"input\">{$this->_lang['editblog'][5]} <select name=\"isHidden\" id=\"isHidden\"><option value=\"0\"" . ((TRUE == isset($blog_info['hidden']) && 0 == $blog_info['hidden']) ? ' selected' : '') . ">{$this->_lang['admin'][119]}</option><option value=\"1\"" . ((TRUE == isset($blog_info['hidden']) && 1 == $blog_info['hidden']) ? ' selected' : '') . ">{$this->_lang['admin'][118]}</option></select></td>
          <td align=\"center\">{$this->_lang['editblog'][6]}</td><td><select name=\"esort\" class=\"botton\">";

		$query_string = "select id, cnName from {$this->_dbop->prefix}sort";
		$this->_dbop->query($query_string);
		$result = $this->_dbop->fetchArrayBat(0, "ASSOC");
		$this->_dbop->freeResult();
		if (TRUE == is_array($result) && TRUE == count($result))
		{
			for ($i = 0; $i < count($result); $i++)
			{
				echo "<option value=\"{$result[$i]['id']}\"";
				if (FALSE == empty($blog_info['sort']) && $result[$i]['id'] == $blog_info['sort']) echo " selected";
				echo ">{$result[$i]['cnName']}</option>";
			}
		}

		echo "
          </select></td>
        </tr>
		";

		if (FALSE == empty($_GET['mode']) && 'adv' == $_GET['mode'])
		{
			echo "
    <tr><td align=\"center\">{$this->_lang['editblog'][120]}</td><td><select name=\"nocom\" id=\"nocom\"><option value=\"0\"" . ((TRUE == isset($blog_info['nocom']) && 0 == $blog_info['nocom']) ? ' selected' : '') . ">{$this->_lang['admin'][118]}</option><option value=\"1\"" . ((TRUE == isset($blog_info['nocom']) && 1 == $blog_info['nocom']) ? ' selected' : '') . ">{$this->_lang['admin'][119]}</option></select></td><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][54]}</td>
      <td colspan=\"4\"><textarea name=\"summarycontent\" cols=\"70\" rows=\"4\" wrap=\"VIRTUAL\" class=\"input\">" . ((FALSE == empty($blog_info['summarycontent'])) ? $blog_info['summarycontent'] : '') . "</textarea></td>
    </tr>
    <tr>
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][54]}</td>
      <td colspan=\"4\">
			";

			include("../images/FCKeditor/fckeditor.php");
//			$sBasePath = $_SERVER['PHP_SELF'] ;
			$sBasePath = "../images/FCKeditor/";

			$oFCKeditor = new FCKeditor('content') ;
			$oFCKeditor->BasePath = $sBasePath ;

			$oFCKeditor->Config['AutoDetectLanguage']	= true ;
			$oFCKeditor->Config['DefaultLanguage']		= 'en' ;
			$oFCKeditor->ToolbarSet = 'Default';

			$oFCKeditor->Value = ((FALSE == empty($blog_info['content'])) ? $blog_info['content'] : '');
			$oFCKeditor->Width = '100%';
			$oFCKeditor->Height = 300;
			$oFCKeditor->Create() ;

		echo "
	 </td>
    </tr>
    <tr>
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][59]}<br></td>
      <td colspan=\"4\"><input name=\"keyword\" type=\"text\" size=\"70\" class=\"input\" value=\"" . ((FALSE == empty($blog_info['keyword'])) ? $blog_info['keyword'] : '') . "\">
	<br><font color=\"red\">* {$this->_lang['editblog'][60]}</font>
      </td>
    </tr>
    <tr>
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][61]}<br></td>
      <td colspan=\"4\">{$this->_lang['editblog'][62]} <input maxLength=\"10\" size=\"10\" value=\"" . date("Y-m-d", $this->current_time) . "\" name=\"at_datevalue\" />
      <SCRIPT language=javascript>
	<!--
	if (!document.layers) {
	document.write(\"<input class=botton type=button onclick='popUpCalendar(this, exEdit.at_datevalue, \\\"yyyy-mm-dd\\\")' value='{$this->_lang['editblog'][63]}'>\")
	}
	//-->
	</SCRIPT>
	{$this->_lang['editblog'][64]}<input type=\"text\" name=\"at_hour\" value=\"" . date("H", $this->current_time) . "\" size=\"3\" maxlength=\"2\" style=\"ime-mode:disabled\" onkeydown=\"onlyNum();\" title=\"{$this->_lang['editblog'][67]}00\">
	{$this->_lang['editblog'][65]}<input type=\"text\" name=\"at_minute\" value=\"" . date("i", $this->current_time) . "\" size=\"3\" maxlength=\"2\" style=\"ime-mode:disabled\" onkeydown=\"onlyNum();\" title=\"{$this->_lang['editblog'][67]}00\">
	{$this->_lang['editblog'][66]}<input type=\"text\" name=\"at_second\" value=\"" . date("s", $this->current_time) . "\" size=\"3\" maxlength=\"2\" style=\"ime-mode:disabled\" onkeydown=\"onlyNum();\" title=\"{$this->_lang['editblog'][67]}00\">
      </td>
    </tr>
    <input type=hidden name=mode value=adv>
			";
		}
		else echo "
<script language=\"JavaScript\" type=\"text/javascript\" src=\"../include/ex2.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"cal_images/lw_layers.js\"></script>
<script language=\"JavaScript\" type=\"text/javascript\" src=\"cal_images/lw_menu.js\"></script>
<script language=JavaScript>
<!--
var text_input = \"{$this->_lang['javascript'][0]}\";
var text_enter_url = \"{$this->_lang['javascript'][1]}\";
var frame_help = \"{$this->_lang['javascript'][2]}\"
var help_mode = \"{$this->_lang['javascript'][3]}\";
var adv_mode = \"{$this->_lang['javascript'][4]}\";
var normal_mode = \"{$this->_lang['javascript'][5]}\";
var email_help = \"{$this->_lang['javascript'][6]}\";
var email_normal = \"{$this->_lang['javascript'][7]}\";
var email_normal_input = \"{$this->_lang['javascript'][8]}\";
var fontsize_help = \"{$this->_lang['javascript'][9]}\";
var fontsize_normal = \"{$this->_lang['javascript'][10]}\";
var font_help = \"{$this->_lang['javascript'][11]}\";
var font_normal = \"{$this->_lang['javascript'][12]}\";
var exblur_help = \"{$this->_lang['javascript'][13]}\";
var exblur_normal = \"{$this->_lang['javascript'][14]}\";
var bold_help = \"{$this->_lang['javascript'][15]}\";
var bold_normal = \"{$this->_lang['javascript'][16]}\";
var strike_help = \"{$this->_lang['javascript'][17]}\";
var strike_normal = \"{$this->_lang['javascript'][18]}\";
var italicize_help = \"{$this->_lang['javascript'][19]}\";
var italicize_normal = \"{$this->_lang['javascript'][20]}\";
var sup_help = \"{$this->_lang['javascript'][21]}\";
var sup_normal = \"{$this->_lang['javascript'][22]}\";
var sub_help = \"{$this->_lang['javascript'][23]}\";
var sub_normal = \"{$this->_lang['javascript'][24]}\";
var move_help = \"{$this->_lang['javascript'][25]}\";
var move_normal = \"{$this->_lang['javascript'][26]}\";
var quote_help = \"{$this->_lang['javascript'][27]}\"
var quote_normal = \"{$this->_lang['javascript'][28]}\";
var color_help = \"{$this->_lang['javascript'][29]}\";
var color_normal = \"{$this->_lang['javascript'][30]}\";
var left_help = \"{$this->_lang['javascript'][31]}\";
var left_normal = \"{$this->_lang['javascript'][32]}\";
var center_help = \"{$this->_lang['javascript'][33]}\";
var center_normal = \"{$this->_lang['javascript'][34]}\";
var right_help = \"{$this->_lang['javascript'][35]}\";
var right_normal = \"{$this->_lang['javascript'][36]}\";
var justify_help = \"{$this->_lang['javascript'][37]}\";
var justify_normal = \"{$this->_lang['javascript'][38]}\";
var link_help = \"{$this->_lang['javascript'][39]}\";
var link_normal = \"{$this->_lang['javascript'][40]}\";
var link_normal_input = \"{$this->_lang['javascript'][41]}\";
var image_help = \"{$this->_lang['javascript'][42]}\"
var image_normal = \"{$this->_lang['javascript'][43]}\";
var flash_help = \"{$this->_lang['javascript'][44]}\";
var flash_normal = \"{$this->_lang['javascript'][45]}\";
var flash_size = \"{$this->_lang['javascript'][46]}\";
var fliph_help = \"{$this->_lang['javascript'][47]}\";
var fliph_normal = \"{$this->_lang['javascript'][48]}\";
var flipv_help = \"{$this->_lang['javascript'][49]}\";
var flipv_normal = \"{$this->_lang['javascript'][50]}\";
var code_help = \"{$this->_lang['javascript'][51]}\";
var code_normal = \"{$this->_lang['javascript'][52]}\";
var htmlcode_help = \"{$this->_lang['javascript'][53]}\";
var htmlcode_normal = \"{$this->_lang['javascript'][54]}\";
var list_help = \"{$this->_lang['javascript'][55]}\";
var list_normal = \"{$this->_lang['javascript'][56]}\";
var list_normal_error = \"{$this->_lang['javascript'][57]}\";
var list_normal_input = \"{$this->_lang['javascript'][58]}\";
var spoiler_help = \"{$this->_lang['javascript'][59]}\";
var spoiler_normal = \"{$this->_lang['javascript'][60]}\";
var underline_help = \"{$this->_lang['javascript'][61]}\";
var underline_normal = \"{$this->_lang['javascript'][62]}\";
var wmv_help = \"{$this->_lang['javascript'][63]}\";
var wmv_normal = \"{$this->_lang['javascript'][64]}\";
var wma_help = \"{$this->_lang['javascript'][65]}\";
var wma_normal = \"{$this->_lang['javascript'][66]}\";
var rm_help = \"{$this->_lang['javascript'][67]}\";
var rm_normal = \"{$this->_lang['javascript'][68]}\";
var rm_size = \"{$this->_lang['javascript'][69]}\";
var ra_help = \"{$this->_lang['javascript'][70]}\";
var ra_normal = \"{$this->_lang['javascript'][71]}\";
var mp3_help = \"{$this->_lang['javascript'][72]}\";
var mp3_normal = \"{$this->_lang['javascript'][73]}\";
var mov_help = \"{$this->_lang['javascript'][74]}\";
var mov_normal = \"{$this->_lang['javascript'][75]}\";

var media_help = \"{$this->_lang['javascript'][76]}\";
var media_normal = \"{$this->_lang['javascript'][77]}\";
var music_help = \"{$this->_lang['javascript'][78]}\";
var music_normal = \"{$this->_lang['javascript'][79]}\";
var music_id = \"{$this->_lang['javascript'][80]}\";

var exobject_help = \"{$this->_lang['javascript'][81]}\";
var exobject_normal = \"{$this->_lang['javascript'][82]}\";
var exobject_id = \"{$this->_lang['javascript'][83]}\";
var exobject_size = \"{$this->_lang['javascript'][84]}\";

<!-- auto copy to clipboard js by sheeryiro-->
function copyC()
{
	therange=document.exEdit.content.createTextRange();
	therange.execCommand(\"Copy\");
}

function pasteC()
{
	alert(pasteCtext);
	document.exEdit.content.focus();
	document.exEdit.content.createTextRange().execCommand(\"Paste\");
}

function exEditadd(iEdit)
{
	AddText(iEdit);
	document.exEdit.content.focus();
}

function vertify()
{
	if (document.exEdit.esort.value==\"\")
	{
		alert(vertify_t1);
		return false;
	}
	else
	{
		return true;
	}
}

function adcheck()
{
	if (exEdit.title.value==\"\")
	{
		alert(\"{$this->_lang['javascript'][111]}\");
		exEdit.title.focus();
		return false;
	}
	if (exEdit.author.value==\"\")
	{
		alert (\"{$this->_lang['javascript'][112]}\");
		exEdit.author.focus();
		return false;
	}
	if (exEdit.content.value==\"\")
	{
		alert (\"{$this->_lang['javascript'][113]}\");
		exEdit.content.focus();
		return false;
	}
	if (exEdit.content.value.length<6)
	{
		alert (\"{$this->_lang['javascript'][114]}\");
		exEdit.content.focus();
		return false;
	}
	copyC();
}

function check(noe)
{
	if (exEdit.title.value==\"\")
	{
		alert (check_t1);
		exEdit.title.focus();
		return false;
	}
	if (exEdit.author.value==\"\")
	{
		alert (check_t2);
		exEdit.author.focus();
		return false;
	}
	if (exEdit.content.value==\"\")
	{
		alert (check_t3);
		exEdit.content.focus();
		return false;
	}
	if (exEdit.content.value.length<6)
	{
		alert (check_t4);
		exEdit.content.focus();
		return false;
	}
	copyC();

	//对用户自定义时间是否合法进行验证 GO
	if ((exEdit.at_datevalue.value.length<10) && (exEdit.at_datevalue.value.length!=''))
	{
		alert(check_t5);
		exEdit.at_datevalue.focus();
		return false;
	}
	if ((exEdit.at_second.value.length<2) && (exEdit.at_second.value.length!=''))
	{
		alert(check_t6);
		exEdit.at_second.focus();
		return false;
	}
	if ((exEdit.at_hour.value.length<2) && (exEdit.at_hour.value.length!=''))
	{
		alert(check_t7);
		exEdit.at_hour.focus();
		return false;
	}
	if ((exEdit.at_minute.value.length<2) && (exEdit.at_minute.value.length!=''))
	{
		alert(check_t8);
		exEdit.at_minute.focus();
		return false;
	}
	if ((exEdit.at_hour.value>23)||(exEdit.at_minute.value>59)||(exEdit.at_second.value>59))
	{
		alert(check_t9);
		exEdit.at_hour.focus();
		return false;
	}
}

defmode = \"normalmode\";		// default mode (normalmode, advmode, helpmode)

if (defmode == \"advmode\")
{
	helpmode = false;
	normalmode = false;
	advmode = true;
}
else if (defmode == \"helpmode\")
{
	helpmode = true;
	normalmode = false;
	advmode = false;
}
else
{
	helpmode = false;
	normalmode = true;
	advmode = false;
}

var timerID = null;
var timerRunning = false;

function MakeArray(size)
{
	this.length = size;
	for(var i = 1; i <= size; i++) this[i] = \"\";
  	return this;
}

function stopclock()
{
	if (timerRunning) clearTimeout(timerID);
	timerRunning = false;
}

function showtime()
{
	var now = new Date();
	var year = now.getYear();
	var month = now.getMonth() + 1;
	var date = now.getDate();
	var hours = now.getHours();
	var minutes = now.getMinutes();
	var seconds = now.getSeconds();
	var day = now.getDay();
	Day = new MakeArray(7);
	Day[0]=check_t11;
	Day[1]=check_t12;
	Day[2]=check_t13;
	Day[3]=check_t14;
	Day[4]=check_t15;
	Day[5]=check_t16;
	Day[6]=check_t17;
	var timeValue = \"\";
	timeValue += year + check_t18;
	timeValue += ((month < 10) ? \"0\" : \"\") + month + check_t19;
	timeValue += date + check_t20;
	timeValue += (Day[day]) + \"  \";
	timeValue += ((hours <= 12) ? hours : hours - 12);
	timeValue += ((minutes < 10) ? \":0\" : \":\") + minutes;
	timeValue += ((seconds < 10) ? \":0\" : \":\") + seconds;
	timeValue += (hours < 12) ? check_t21 : check_t22;
	liveclock.innerHTML = timeValue;
	timerID = setTimeout(\"showtime()\",1000);
	timerRunning = true;
}

function startclock()
{
	stopclock();
	showtime()
}

ubb_codes = new Array();
ubb_codes[0] = new Array('ex_code.gif', 'code', 23, 22, \"{$this->_lang['editblog'][10]}\");
ubb_codes[1] = new Array('ex_quote.gif', 'quote', 23, 22, \"{$this->_lang['editblog'][11]}\");
ubb_codes[2] = new Array('ex_bold.gif', 'bold', 23, 22, \"{$this->_lang['editblog'][12]}\");
ubb_codes[3] = new Array('ex_italicize.gif', 'italicize', 23, 22, \"{$this->_lang['editblog'][13]}\");
ubb_codes[4] = new Array('ex_strike.gif', 'strike', 23, 22, \"{$this->_lang['editblog'][14]}\");
ubb_codes[5] = new Array('ex_sup.gif', 'sup', 23, 22, \"{$this->_lang['editblog'][15]}\");
ubb_codes[6] = new Array('ex_sub.gif', 'exsub', 23, 22, \"{$this->_lang['editblog'][16]}\");
ubb_codes[7] = new Array('ex_list.gif', 'list', 23, 22, \"{$this->_lang['editblog'][17]}\");
ubb_codes[8] = new Array('ex_left.gif', 'left', 23, 22, \"{$this->_lang['editblog'][18]}\");
ubb_codes[9] = new Array('ex_center.gif', 'center', 23, 22, \"{$this->_lang['editblog'][19]}\");
ubb_codes[10] = new Array('ex_right.gif', 'right', 23, 22, \"{$this->_lang['editblog'][20]}\");
ubb_codes[11] = new Array('ex_justify.gif', 'justify', 23, 22, \"{$this->_lang['editblog'][21]}\");
ubb_codes[12] = new Array('ex_underline.gif', 'underline', 23, 22, \"{$this->_lang['editblog'][22]}\");
ubb_codes[13] = new Array('ex_move.gif', 'move', 23, 22, \"{$this->_lang['editblog'][23]}\");
ubb_codes[14] = new Array('ex_blur.gif', 'exblur', 23, 22, \"{$this->_lang['editblog'][24]}\");
ubb_codes[15] = new Array('ex_fliph.gif', 'fliph', 23, 22, \"{$this->_lang['editblog'][25]}\");
ubb_codes[16] = new Array('ex_flipv.gif', 'flipv', 23, 22, \"{$this->_lang['editblog'][26]}\");
ubb_codes[17] = new Array('ex_iframe.gif', 'frame', 23, 22, \"{$this->_lang['editblog'][27]}\");
ubb_codes[18] = new Array('ex_htmlcode.gif', 'htmlcode', 23, 22, \"{$this->_lang['editblog'][28]}\");
ubb_codes[19] = new Array('ex_email.gif', 'tag_email', 23, 22, \"{$this->_lang['editblog'][29]}\");
ubb_codes[20] = new Array('ex_url.gif', 'hyperlink', 23, 22, \"{$this->_lang['editblog'][30]}\");
ubb_codes[21] = new Array('ex_image.gif', 'image', 23, 22, \"{$this->_lang['editblog'][31]}\");
ubb_codes[22] = new Array('ex_swf.gif', 'flash', 23, 22, \"{$this->_lang['editblog'][32]}\");
ubb_codes[23] = new Array('ex_mp3.gif', 'mp3', 23, 22, \"{$this->_lang['editblog'][33]}\");
ubb_codes[24] = new Array('ex_rm.gif', 'rm', 23, 22, \"{$this->_lang['editblog'][34]}\");
ubb_codes[25] = new Array('ex_ra.gif', 'ra', 23, 22, \"{$this->_lang['editblog'][35]}\");
ubb_codes[26] = new Array('ex_wmv.gif', 'wmv', 23, 22, \"{$this->_lang['editblog'][36]}\");
ubb_codes[27] = new Array('ex_wma.gif', 'wma', 23, 22, \"{$this->_lang['editblog'][37]}\");
ubb_codes[28] = new Array('ex_mov.gif', 'mov', 23, 22, \"{$this->_lang['editblog'][38]}\");
ubb_codes[29] = new Array('ex_object.gif', 'exobject', 23, 22, \"{$this->_lang['editblog'][39]}\");

fonts = new Array();
fonts[0] = \"Andale Mono\";
fonts[1] = \"Arial\";
fonts[2] = \"Arial Black\";
fonts[3] = \"Book Antiqua\";
fonts[4] = \"Century Gothic\";
fonts[5] = \"Comic Sans MS\";
fonts[6] = \"Courier New\";
fonts[7] = \"Georgia\";
fonts[8] = \"Impact\";
fonts[9] = \"Tahoma\";
fonts[10] = \"Times New Roman \";
fonts[11] = \"Trebuchet MS\";
fonts[12] = \"Script MT Bold\";
fonts[13] = \"Stencil\";
fonts[14] = \"Verdana\";
fonts[15] = \"Lucida Console\";

colors = new Array();
colors[0] = \"#000000\";
colors[1] = \"#FFFFFF\";
colors[2] = \"#FF0000\";
colors[3] = \"#FFFF00\";
colors[4] = \"#00FF00\";
colors[5] = \"#00FFFF\";
colors[6] = \"#0000FF\";
colors[7] = \"#FF00FF\";
colors[8] = \"#808080\";
colors[9] = \"#C0C0C0\";
colors[10] = \"#800000\";
colors[11] = \"#808000\";
colors[12] = \"#008000\";
colors[13] = \"#008080\";
colors[14] = \"#000080\";
colors[15] = \"#800080\";

function ubb_codes_list()
{
	for (var i = 0; i < ubb_codes.length; i++)
	{
		document.write(\"<td><img  onclick=\\\"\"+ubb_codes[i][1]+\"()\\\"  src=\\\"../images/ex_code/\"+ubb_codes[i][0]+\"\\\" alt=\\\"\"+ubb_codes[i][4]+\"\\\" width=\\\"\"+ubb_codes[i][2]+\"\\\" height=\\\"\"+ubb_codes[i][3]+\"\\\" border=\\\"0\\\"></td>\");
		if (0 < i && 0 == ((i +1) % 17)) document.write(\"</tr><tr>\");
	}
}

function ex2_list()
{
	for (var i = 0; i < 51; i++)
	{
		document.write(\"<td><a href=\\\"javascript:exEditadd(':ex2_\"+i+\":')\\\"><img src=\\\"../images/ex_pose/ex2_\"+i+\".gif\\\" width=\\\"16\\\" height=\\\"16\\\" border=\\\"0\\\"></a></td>\");
		if (0 < i && 0 == ((i +1) % 17)) document.write(\"</tr><tr>\");
	}
}

function ex_list()
{
	for (var i = 0; i < 11; i++) document.write(\"<td><a href=\\\"javascript:exEditadd(':ex_\"+i+\":')\\\"><img src=\\\"../images/ex_pose/ex_\"+i+\".gif\\\" border=\\\"0\\\"></a></td>\");
}

function font_list(inForm)
{
	for (var i = 0; i < fonts.length; i++)
	{
		inForm.font.options[i+5] = new Option(fonts[i], fonts[i]);
	}
}

function font_sizes_list()
{
	for (var i = 0; i < 9; i++)
	{
		if (2 != i)
		{
			document.write(\"<option value=\\\"\"+(i - 2)+\"\\\"\");
			if (5 == i) document.write(\" selected\");
			document.write(\">\"+(i - 2)+\"</option>\");
		}
	}
}

function color_list()
{
	for (var i = 0; i < colors.length; i++) document.write(\"<option style=\\\"background-color:\"+colors[i]+\";color:\"+colors[i]+\"\\\" value=\"+colors[i]+\">\"+colors[i]+\"</option>\");
}

function manageattachments(url, width, height)
{
	window.open(url, \"upload_file\", \"statusbar=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes,width=\" + width + \",height=\" + height);
	return false;
}
//-->
</script>
        <tr>
          <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][7]}<br><br><span style=\"cursor:hand;width:100%;\" onclick=\"showIntro('moretools');showIntro('moretools_sum');showIntro('moretools_exface');showIntro('moretools_keyword');showIntro('moretools_date');" . ((FALSE == empty($_GET['action']) && 'add' == $_GET['action']) ? "showIntro('moretools_trackback');" : "") . "\" title=\"{$this->_lang['editblog'][8]}\">{$this->_lang['editblog'][9]}</span></td>
          <td colspan=\"3\">
            <div id=\"_face_ex\">Loading...</div>
            <table border=\"0\" cellpadding=\"1\" cellspacing=\"1\">
              <tr style=\"display:none;\" id=\"moretools_exface\"><script language=Javascript>ex_list();</script>
              <td colspan=\"6\" align=\"right\" class=\"main\">{$this->_lang['editblog'][120]}<select name=\"nocom\" id=\"nocom\"><option value=\"0\"" . ((TRUE == isset($blog_info['nocom']) && 0 == $blog_info['nocom']) ? ' selected' : '') . ">{$this->_lang['admin'][118]}</option><option value=\"1\"" . ((TRUE == isset($blog_info['nocom']) && 1 == $blog_info['nocom']) ? ' selected' : '') . ">{$this->_lang['admin'][119]}</option></select></td></tr>
              <tr><script language=Javascript>ubb_codes_list();</script><td colspan=\"4\" align=\"right\"><select name=\"mode\" onChange=\"chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)\"><option value=2>{$this->_lang['editblog'][40]}</option><option value=1>{$this->_lang['editblog'][41]}</option><option value=0>{$this->_lang['editblog'][42]}</option></select></td></tr>
            </table>
            <table class=\"main\">
              <tr>
                <td width=\"200\" nowrap>{$this->_lang['editblog'][43]} <select onChange=chfont(this.options[this.selectedIndex].value) name=font id=font><option value=\"{$this->_lang['editblog'][44]}\" selected>{$this->_lang['editblog'][44]}</option><option value=\"{$this->_lang['editblog'][46]}\">{$this->_lang['editblog'][45]}</option><option value=\"{$this->_lang['editblog'][47]}\">{$this->_lang['editblog'][47]}</option><option value=\"{$this->_lang['editblog'][48]}\">{$this->_lang['editblog'][48]}</option><option value=\"{$this->_lang['editblog'][49]}\">{$this->_lang['editblog'][49]}</option></select><script language=javascript>font_list(document.forms[0]);</script></td>
                <td width=\"50\"><select name=\"size\" onFocus=\"this.selectedIndex=2\" onChange=\"chsize(this.options[this.selectedIndex].value)\" size=\"1\"><script language=javascript>font_sizes_list();</script></select></td>
                <td width=\"85\"><SELECT onchange=chcolor(this.options[this.selectedIndex].value) name=color><script language=javascript>color_list();</script></SELECT></td>
                <td>{$this->_lang['editblog'][50]}<select name=\"fontsign\" onChange=\"exEditadd(this.options[this.selectedIndex].value)\" ><option value=\"{$this->_lang['editblog'][51]}\">{$this->_lang['editblog'][52]}</option><option value=\"■\">■</option><option value=\"□\">□</option><option value=\"▲\">▲</option><option value=\"△\">△</option><option value=\"▼\">▼</option><option value=\"▽\">▽</option><option value=\"◆\">◆</option><option value=\"◇\">◇</option><option value=\"○\">○</option><option value=\"◎\">◎</option><option value=\"●\">●</option><option value=\"☆\">☆</option><option value=\"★\">★</option><option value=\"◆\">◆</option><option value=\"◇\">◇</option><option value=\"○\">○</option><option value=\"◎\">◎</option><option value=\"●\">●</option><option value=\"→\">→</option><option value=\"←\">←</option><option value=\"↑\">↑</option><option value=\"↓\">↓</option><option value=\"♀\">♀</option><option value=\"♂\">♂</option><option value=\"\t\">Tab</option></select></td></tr></table></td>
    </tr>
   <tr style=\"display:none;\" id=\"moretools_sum\">
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][53]}<br></td>
      <td colspan=\"4\"><textarea name=\"summarycontent\" cols=\"70\" rows=\"4\" wrap=\"VIRTUAL\" class=\"input\">" . ((FALSE == empty($blog_info['summarycontent'])) ? $blog_info['summarycontent'] : '') . "</textarea></td>
    </tr>
    <tr>
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][54]}<br>
<br>
<br><a style=\"cursor:hand;width:100%;\" onclick=\"document.exEdit.content.rows+=20;\" title=\"{$this->_lang['editblog'][55]}\">{$this->_lang['editblog'][56]}</a><br>
<br><a style=\"cursor:hand;width:100%;\" onclick=\"if(document.exEdit.content.rows>=20)document.exEdit.content.rows-=20;else return false\" title=\"{$this->_lang['editblog'][57]}\">{$this->_lang['editblog'][58]}</a></td>
      <td colspan=\"4\"><textarea name=\"content\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\" class=\"input\"  onSelect=\"javascript: storeCaret(this);\" onClick=\"javascript: storeCaret(this);\" onKeyUp=\"javascript: storeCaret(this);\">" . ((FALSE == empty($blog_info['content'])) ? $blog_info['content'] : '') . "</textarea></td>
    </tr>

   <tr style=\"display:none;\" id=\"moretools_trackback\">
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][85]}<br></td>
      <td colspan=\"3\"><input name=\"trackback\" type=\"text\" size=\"70\" class=\"input\" value=\"\">
	<br><font color=\"red\">* {$this->_lang['editblog'][86]}</font>
    </td>
  </tr>
  <tr style=\"display:none;\" id=\"moretools_keyword\">
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][59]}<br></td>
      <td colspan=\"4\"><input name=\"keyword\" type=\"text\" size=\"70\" class=\"input\" value=\"" . ((FALSE == empty($blog_info['keyword'])) ? $blog_info['keyword'] : '') . "\">
	<br><font color=\"red\">* {$this->_lang['editblog'][60]}</font>
      </td>
    </tr>
   <tr style=\"display:none;\" id=\"moretools_date\">
      <td align=\"center\" valign=\"top\">{$this->_lang['editblog'][61]}<br></td>
      <td colspan=\"4\">{$this->_lang['editblog'][62]} <input maxLength=\"10\" size=\"10\" value=\"" . date("Y-m-d", $this->current_time) . "\" name=\"at_datevalue\" />
      <SCRIPT language=javascript>
	<!--
	if (!document.layers) {
	document.write(\"<input class=botton type=button onclick='popUpCalendar(this, exEdit.at_datevalue, \\\"yyyy-mm-dd\\\")' value='{$this->_lang['editblog'][63]}'>\")
	}
	//-->
	</SCRIPT>
	{$this->_lang['editblog'][64]}<input type=\"text\" name=\"at_hour\" value=\"" . date("H", $this->current_time) . "\" size=\"3\" maxlength=\"2\" style=\"ime-mode:disabled\" onkeydown=\"onlyNum();\" title=\"{$this->_lang['editblog'][67]}00\">
	{$this->_lang['editblog'][65]}<input type=\"text\" name=\"at_minute\" value=\"" . date("i", $this->current_time) . "\" size=\"3\" maxlength=\"2\" style=\"ime-mode:disabled\" onkeydown=\"onlyNum();\" title=\"{$this->_lang['editblog'][67]}00\">
	{$this->_lang['editblog'][66]}<input type=\"text\" name=\"at_second\" value=\"" . date("s", $this->current_time) . "\" size=\"3\" maxlength=\"2\" style=\"ime-mode:disabled\" onkeydown=\"onlyNum();\" title=\"{$this->_lang['editblog'][67]}00\">
      </td>
    </tr>
   <tr style=\"display:none;\" id=\"moretools\">
      <td align=\"center\" valign=\"top\"></td>
      <td colspan=\"3\">
 <a href=\"#\" onClick=\"manageattachments('upload.php?action=addForm&ins=1&type=ubb&element=content', 400, 200)\">{$this->_lang['editblog'][68]}</a>
 <a onclick=pasteC() href=\"#\">{$this->_lang['editblog'][69]}</a>
 <a href=\"javascript:checklength(document.exEdit);\" title=\"{$this->_lang['editblog'][70]}\">{$this->_lang['editblog'][71]}</a>
			</td>
    	</tr>
		";
		echo "
    <tr align=\"center\">
      <td height=\"30\" colspan=\"6\">";
		if (FALSE == empty($blog_info['id']))
		{
			echo "<input type=\"hidden\" name=\"action\" value=\"actediablog\"><input type=\"hidden\" name=\"id\" value=\"{$this->blog_id}\"><input type=\"submit\" value=\"{$this->_lang['editblog'][72]}\" class=\"botton\">";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"action\" value=\"actaddablog\">
        <input type=\"submit\" value=\"{$this->_lang['editblog'][90]}\" class=\"botton\" onClick=\"javascript: return check(1);\"></td>";
		}

		if (FALSE == (FALSE == empty($_GET['mode']) && 'adv' == $_GET['mode'])) echo "</td></tr></table><div id=\"face_ex\" style=\"display:none\"><script language=\"JavaScript\" src=\"../images/ex_pose/face_ex.js\"></script></div><script>document.all(\"_face_ex\").innerHTML = document.all(\"face_ex\").innerHTML;</script>";
		echo "</form>";

		$this->printPageFooter();
	}

	// 根据用户级别调整部分参数
	function _getOptionByULevel($uid, $level)
	{
		if (FALSE == $this->got_options)
		{
			$user_level = $this->getUserInfo($uid, "uid");
			if (FALSE !== $user_level && $user_level < $level) $this->show_more = TRUE;
			else $this->author = $_SESSION['exPass'];
			$this->got_options = TRUE;
		}
	}

	// 获取指定blog信息
	function _getBlogInfo()
	{
		$blog_info = array();
		if (FALSE == empty($this->blog_id) && TRUE == is_numeric($this->blog_id))
		{
			$query_string = "select * from {$this->_dbop->prefix}blog where id=" . intval($this->blog_id);
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getAffectedRows()) $blog_info = $this->_dbop->fetchArray(0, "ASSOC");
			$this->_dbop->freeResult();
			include_once("../include/ubbcode.php");
			$ubb_op = new UbbCode();
			$ubb_op->addTagByOrder("&amp;", "&", FALSE, "postfix");
			$ubb_op->addTagByOrder("&gt;", ">", FALSE, "postfix");
			$ubb_op->addTagByOrder("&lt;", "<", FALSE, "postfix");
			$ubb_op->addTagByOrder("&#39;", "'", FALSE, "postfix");
			$blog_info['summarycontent'] = $ubb_op->unParse($blog_info['summarycontent']);
			if (TRUE == empty($this->_input['_GET']['mode']) || 'adv' != $this->_input['_GET']['mode']) $blog_info['content'] = $ubb_op->unParse($blog_info['content']);
			unset($ubb_op);
		}
		return $blog_info;
	}

	// 获取BLOG列表，其中同时获取BLOG总数
	function _getBlogList()
	{
		if (TRUE == empty($_GET['page']) || FALSE == is_numeric($_GET['page'])) $this->current_page = 1;
		else $this->current_page = intval($_GET['page']);
		$this->_getOptionByULevel($_SESSION['userID'], 1);
		$query_string = "select count(*) as counter from {$this->_dbop->prefix}blog";
		if (FALSE == empty($this->author)) $query_string .= " where author='" . $this->author . "'";
		$this->_dbop->query($query_string);
		$result = $this->_dbop->fetchArray(0, "ASSOC");
		$this->_dbop->freeResult();
		$this->blog_count = $result['counter'];

		$this->total_page = ceil($this->blog_count / $this->page_size);
		if (0 == $this->total_page) $this->total_page = 1;
		if ($this->current_page < 1) $this->current_page = 1;
		elseif ($this->total_page < $this->current_page) $this->current_page = $this->total_page;

		$query_string = "select * from {$this->_dbop->prefix}blog";
		if (FALSE == empty($this->author)) $query_string .= " where author='" . $this->author . "'";
		$query_string .= " order by id desc limit " . ($this->current_page - 1) * $this->page_size . ", " . $this->page_size;
		$this->_dbop->query($query_string);
		$result = $this->_dbop->fetchArrayBat(0, "ASSOC");
		$this->_dbop->freeResult();
		return $result;
	}

	// 检查修改表单提交的数据
	function _checkModifyForm()
	{
		if (TRUE == empty($this->_input['_POST']['title'])) $this->errors[] = $this->_lang['admin'][30];
		elseif (strlen(trim($this->_input['_POST']['title'])) > 50) $this->errors[] = $this->_lang['admin'][134];
		else $this->_input['_POST']['title'] = trim($this->_input['_POST']['title']);
		if (TRUE == empty($this->_input['_POST']['content']) || 6 > strlen(trim($this->_input['_POST']['content']))) $this->errors[] = $this->_lang['admin'][33];
		else $this->_input['_POST']['content'] = trim($this->_input['_POST']['content']);
		if (TRUE == empty($this->_input['_POST']['author'])) $this->errors[] = $this->_lang['admin'][31];
		elseif (20 < strlen(trim($this->_input['_POST']['author']))) $this->errors[] = $this->_lang['admin'][135];
		else $this->_input['_POST']['author'] = trim($this->_input['_POST']['author']);
/*		if (TRUE == empty($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['admin'][136];
		elseif (strlen(trim($this->_input['_POST']['email'])) > 35) $this->errors[] = $this->_lang['admin'][137];
		elseif (FALSE == $this->_isEmailAddress($this->_input['_POST']['email'])) $this->errors[] = $this->_lang['admin'][74];
*/		if (TRUE == empty($this->_input['_POST']['esort']) || FALSE == is_numeric($this->_input['_POST']['esort'])) $this->_input['_POST']['esort'] = 1;
		else $this->_input['_POST']['esort'] = intval(trim($this->_input['_POST']['esort']));
		if (TRUE == empty($this->_input['_POST']['keyword'])) $this->_input['_POST']['keyword'] = '';
		elseif (strlen(trim($this->_input['_POST']['keyword'])) > 100) $this->errors[] = $this->_lang['admin'][138];
		else $this->_input['_POST']['keyword'] = trim($this->_input['_POST']['keyword']);
		if (TRUE == empty($this->_input['_POST']['summarycontent']))
		{
			if (FALSE == empty($this->_input['_POST']['content'])) $this->_input['_POST']['summarycontent'] = $this->_substr(strip_tags($this->_input['_POST']['content']), 0, $this->_config['global']['summarynum']);
		}
		else $this->_input['_POST']['summarycontent'] = trim($this->_input['_POST']['summarycontent']);
		if (TRUE == empty($this->_input['_POST']['trackback'])) $this->_input['_POST']['trackback'] = '';
		elseif (FALSE == preg_match("/^http:\/\/.*/", $this->_input['_POST']['trackback'])) $this->errors[] = $this->_lang['admin'][132];
		// 组合时间
		if (FALSE == empty($this->_input['_POST']['at_datevalue']))
		{
			$tmp = explode("-", $this->_input['_POST']['at_datevalue']);
			$hour = (FALSE == empty($this->_input['_POST']['at_hour'])) ? intval($this->_input['_POST']['at_hour']) : 0;
			$minute = (FALSE == empty($this->_input['_POST']['at_minute'])) ? intval($this->_input['_POST']['at_minute']) : 0;
			$second = (FALSE == empty($this->_input['_POST']['at_second'])) ? intval($this->_input['_POST']['at_second']) : 0;
			if (3 == count($tmp)) $this->_input['_POST']['post_time'] = mktime($hour, $minute, $second, intval($tmp[1]), intval($tmp[2]), intval($tmp[0]));
			else $this->_input['_POST']['post_time'] = time();
		}
		else $this->_input['_POST']['post_time'] = time();

		// 处理BLOG内容
		include_once("../include/ubbcode.php");
		// 如果不为高级编辑模式，则需要屏蔽内容和摘要中直接的HTML代码
		$ubb_op = new UbbCode();
		$ubb_op->addTagByOrder("<", "&lt;", FALSE);
		$ubb_op->addTagByOrder(">", "&gt;", FALSE);
		$ubb_op->addTagByOrder("'", "&#39;", FALSE);
		$ubb_op->addTagByOrder("&", "&amp;", FALSE);
		$this->_input['_POST']['summarycontent'] = $ubb_op->parse(strip_tags($this->_input['_POST']['summarycontent']));
		if (TRUE == empty($this->_input['_POST']['mode']) || 'adv' != $this->_input['_POST']['mode'])
		{
			$this->_input['_POST']['content'] = $ubb_op->parse($this->_input['_POST']['content']);
			$this->_input['_POST']['summarycontent'] = html_entity_decode($this->_input['_POST']['summarycontent']);
		}
		unset($ubb_op);
		$this->escapeSqlCharsFromData($this->_input['_POST']);
	}

	// 添加Blog内容
	function _addBlogHandler()
	{
		$this->_checkModifyForm();
		if (FALSE == count($this->errors))
		{
			// 检查同名同分类的blog是否已经存在？
			$query_string = "select * from {$this->_dbop->prefix}blog where title='" . $this->_input['_POST']['title'] . "' and sort='" . $this->_input['_POST']['esort'] . "' limit 0, 1";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['admin'][76];
			$this->_dbop->freeResult();
			if (TRUE == is_array($this->errors) && 0 < count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);

			// 插入新blog信息
			$query_string = "insert into {$this->_dbop->prefix}blog (
				sort, title, content, author, email, visits, addtime, keyword, summarycontent, weather, top, hidden, html, nocom, commentCount, trackbackCount
				) values (
				{$this->_input['_POST']['esort']}, '" . $this->_input['_POST']['title'] . "', '" . $this->_input['_POST']['content'] . "', '" . $this->_input['_POST']['author'] . "',
				'" . $_SESSION['exUseremail'] . "', 0, '" . $this->_input['_POST']['post_time'] . "', '" . $this->_input['_POST']['keyword'] . "', '" . $this->_input['_POST']['summarycontent'] . "',
				'" . $this->_input['_POST']['weather'] . "', '" . $this->_input['_POST']['isTop'] . "', '" . $this->_input['_POST']['isHidden'] . "', '0',
				'" . $this->_input['_POST']['nocom'] . "', 0, 0)";
			$this->_dbop->query($query_string);
			$blog_id = $this->_dbop->getInsertId();

			// 添加tags
			$this->_addBlogKeyword();

			// 更新统计信息
			$query_string = "update {$this->_dbop->prefix}admin set blogCount=blogCount+1 where id={$_SESSION['userID']};;update {$this->_dbop->prefix}sort set blogCount=blogCount+1 where id={$this->_input['_POST']['esort']};;update {$this->_dbop->prefix}global set blogCount=blogCount+1";
			$this->_dbop->query($query_string);

			// 如果trackback存在
			if (FALSE == empty($this->_input['_POST']['trackback']))
			{
				if (100 > $this->getStrLen($this->_input['_POST']['content'])) $tmp_content = $this->_input['_POST']['content'];
				else $tmp_content = $this->_substr($this->_input['_POST']['content'], 0, 100);
				$blog_url = "{$this->_config['global']['siteUrl']}/reply/{$blog_id}.html";
				$blog_name = $this->_config['global']['siteName'];
				$this->_sendPing($this->_input['_POST']['title'], $blog_url, $tmp_content, $blog_name, $trackback_url);
			}
		}
		if (0 < count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);
		else $this->showMesg($this->_lang['admin'][78], $_SERVER['PHP_SELF']);
	}

	function _sendPing($blog_subject, $blog_url, $summy_content, $blog_name, $trackback_url)
	{
		if (TRUE == empty($trackback_url)) $this->errors[] = $this->_lang['admin'][122];
		$parse_urls = parse_url($trackback_url);
		if (TRUE == empty($parse_urls['schema']) || 'http' != $parse_urls['schema'] || TRUE == empty($parse_urls['host'])) $this->errors[] = $this->_lang['admin'][123];
		// 如果端口信息不存在则使用80端口
		$parse_urls['port'] = (TRUE == empty($parse_urls['port'])) ? 80 : $parse_urls['port'];

		// 构造POST信息
		$post_string = "title=" . urlencode($blog_subject)
			. "&url=" . urlencode($blog_url)
			. "&excerpt=" . urlencode($summy_content)
			. "&blog_name=" . urlencode($blog_name);
		// 用户代理信息
		$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$post_content = "POST {$parse_urls['path']}?{$parse_urls['query']}\r\n"
			. "HEAD / HTTP/1.1\r\n"
			. "Accept: */*\r\n"
			. "User-Agent: $user_agent\r\n"
			. "Host: {$parse_urls['host']}:{$parse_urls['port']}\r\n"
			. "Connection: Keep-Alive\r\n"
			. "Cache-Control: no-cache\r\n"
			. "Connection: Close\r\n"
			. "Content-Length: " . strlen($post_string) . "\r\n"
			. "Content-Type: application/x-www-form-urlencoded\r\n\r\n$post_string";
		$address = gethostbyname($parse_urls['host']);

		if (FALSE == count($this->errors))
		{
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if ($socket < 0) $this->errors[] = $this->_lang['admin'][124] . socket_strerror($socket);
			else
			{
				$socket_id = socket_connect($socket, $address, $parse_urls['port']);
				if ($socket_id < 0) $this->errors[] = $this->_lang['admin'][124] . socket_strerror($socket_id);
				else
				{
					socket_write($socket_id, $post_content, strlen($post_content));
					while ($out = socket_read($socket_id, 2048)) $result .= $out;
					socket_close($socket);
				}
			}
		}
	}

	// 修改BLOG内容
	function _modifyBlogHandler()
	{
		$this->_checkModifyForm();
		if (FALSE == count($this->errors))
		{
			// 检查同名同分类的blog是否已经存在？
			$query_string = "select * from {$this->_dbop->prefix}blog where id={$this->blog_id}";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['admin'][139];
			else $old_blog_info = $this->_dbop->fetchArray();
			$this->_dbop->freeResult();
			if (TRUE == is_array($this->errors) && 0 < count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);

			// 更新blog信息
			$query_string = "update {$this->_dbop->prefix}blog set
				sort={$this->_input['_POST']['esort']},
				title='" . $this->_input['_POST']['title'] . "',
				content='" . $this->_input['_POST']['content'] . "',
				author='" . $this->_input['_POST']['author'] . "',
				email='" . $_SESSION['exUseremail'] . "',
				addtime='" . $this->_input['_POST']['post_time'] . "',
				keyword='" . $this->_input['_POST']['keyword'] . "',
				summarycontent='" . $this->_input['_POST']['summarycontent'] . "',
				weather='" . $this->_input['_POST']['weather'] . "',
				top='" . $this->_input['_POST']['isTop'] . "',
				hidden='" . $this->_input['_POST']['isHidden'] . "',
				nocom='" . $this->_input['_POST']['nocom'] . "'
				where id={$this->blog_id}";
			$this->_dbop->query($query_string);

			// 添加tags
			$this->_addBlogKeyword();

			// 更新统计信息
			$query_string = "";
			if ($this->_input['_POST']['esort'] != $old_blog_info['sort']) $query_string = "update {$this->_dbop->prefix}sort set blogCount=blogCount+1 where id={$this->_input['_POST']['esort']};;update {$this->_dbop->prefix}sort set blogCount=blogCount-1 where id={$old_blog_info['sort']}";
			if (FALSE == empty($query_string))$this->_dbop->query($query_string);

		}
		if (0 < count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);
		else $this->showMesg($this->_lang['admin'][36], $_SERVER['PHP_SELF']);
	}

	// 删除blog
	function _removeBlogs($ids)
	{
		if (FALSE == is_array($ids)) $ids =explode(",", $ids);
		$condition = "";
		for ($i = 0; $i < count($ids); $i++) $condition .= " or id=" . intval($ids[$i]);
		// 删除BLOG
		$query_string = "delete from {$this->_dbop->prefix}blog where " . substr($condition, 3);
		$this->_dbop->query($query_string);
//		$counter = $this->_dbop->getAffectedRows();
		$this->_dbop->freeResult();

		// 删除评论
		$comment_counter = 0;
		for ($i = 0; $i < count($ids); $i++)
		{
			$query_string = "delete from {$this->_dbop->prefix}comment where commentID=" . intval($ids[$i]);
			$this->_dbop->query($query_string);
			$tmp_counter = $this->_dbop->getAffectedRows();
			$this->_dbop->freeResult();
			$comment_counter += $tmp_counter;
		}

		// 更新统计信息
		$query_string = "select count(*) as new_count from {$this->_dbop->prefix}blog";
		$this->_dbop->query($query_string);
		$blog_count = $this->_dbop->fetchArray(0, "ASSOC");
		$this->_dbop->freeResult();
		$query_string = "update {$this->_dbop->prefix}global set blogCount={$blog_count['new_count']}, commentCount=commentCount-$comment_counter";
		$this->_dbop->query($query_string);
		// 更新分类统计信息
		$query_string = "select count(*) as new_count, commentSort from {$this->_dbop->prefix}comment group by commentSort";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getAffectedRows())
		{
			$this->_dbop->fetchArray(0, "ASSOC");
			$comment_count = $this->_dbop->records;
		}
		else $comment_count = 0;
		$this->_dbop->freeResult();
		$query_string = "select count(*) as new_count, sort from {$this->_dbop->prefix}blog group by sort";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getAffectedRows())
		{
			$this->_dbop->fetchArray(0, "ASSOC");
			$blog_count = $this->_dbop->records;
		}
		else $blog_count = 0;
		$this->_dbop->freeResult();
		// 更新分类的评论数
		$query_string = "";
		if (TRUE == is_array($comment_count))
		{
			for ($i = 0; $i < count($comment_count); $i++) $query_string .= ";;update {$this->_dbop->prefix}sort set commentCount={$comment_count[$i]['new_count']} where id={$comment_count[$i]['commentSort']}";
			$this->_dbop->query(substr($query_string, 2));
			$this->_dbop->freeResult();
		}
		// 更新分类的blog数
		$query_string = "";
		if (TRUE == is_array($blog_count))
		{
			for ($i = 0; $i < count($blog_count); $i++) $query_string .= ";;update {$this->_dbop->prefix}sort set blogCount={$blog_count[$i]['new_count']} where id={$blog_count[$i]['sort']}";
			$this->_dbop->query(substr($query_string, 2));
			$this->_dbop->freeResult();
		}

		$this->showMesg($this->_lang['admin'][86], $_SERVER['PHP_SELF']);
	}

	function _addBlogKeyword()
	{
		$condition = '';
		$existed_tags = array();
		$new_keywords = "";
		$tmp_tags = "";
		$keywords = explode(",", $this->_input['_POST']['keyword']);

		foreach ($keywords as $key => $value)
		{
			if (FALSE == empty($value)) $condition .= "or tag LIKE '%|$value' or tag LIKE '$value|%' or tag LIKE '%|$value|%' or tag='$value'";
		}
		if (FALSE == empty($condition))
		{
			$condition = substr($condition, 2);
			$query_string = "select * from {$this->_dbop->prefix}tags where $condition";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $tmp = $this->_dbop->fetchArrayBat(0, 'ASSOC');
			else $tmp = array();
			$this->_dbop->freeResult();
			for ($i = 0; $i < count($tmp); $i++) $tmp_tags .= "|{$tmp[$i]['tag']}";
			if (TRUE == strlen($tmp_tags)) $tmp_tags = substr($tmp_tags, 1);
			$existed_tags = explode("|", $tmp_tags);
			$existed_tags = array_unique($existed_tags);
			for ($i = 0; $i < count($keywords); $i++)
			{
				if (FALSE == empty($keywords[$i]) && FALSE == in_array($keywords[$i], $existed_tags)) $new_keywords .= "|{$keywords[$i]}";
			}

			if (FALSE == empty($new_keywords))
			{
				$query_string = "insert into {$this->_dbop->prefix}tags (tag) values ('" . substr($new_keywords, 1) . "')";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult();
			}
		}
	}

	// 返回 BLOG 分页内容
	function _printPageCounter()
	{
		if ($this->current_page > 1) $prev_page = "<a href=./editblog.php?action=list>{$this->_lang['admin'][113]}</a> <a href=./editblog.php?action=list&page=" . ($this->current_page - 1) . " title='{$this->_lang['admin'][108]}'>{$this->_lang['admin'][108]}</a>";
		else $prev_page = '';
		if ($this->current_page < $this->total_page) $next_page = "<a href=./editblog.php?action=list&page=" . ($this->current_page + 1) . " title='{$this->_lang['admin'][109]}'>{$this->_lang['admin'][109]}</a> <a href=./editblog.php?action=list&page={$this->total_page}>{$this->_lang['admin'][114]}</a>";
		else $next_page = '';

		if ($this->current_page < $this->total_page)
		{
			echo "<a href={$_SERVER['PHP_SELF']}?page=" . ($this->current_page + 1) . " title='{$this->_lang['admin'][109]}'>{$this->_lang['admin'][109]}</a>";
			echo "<a href={$_SERVER['PHP_SELF']}?page={$this->total_page} title='{$this->_lang['admin'][114]}'>{$this->_lang['admin'][114]}</a>";
		}

		return "{$this->_lang['admin'][110]} <font color='red'>{$this->current_page}</font> {$this->_lang['admin'][111]}, {$this->_lang['admin'][112]} <font color='red'>{$this->total_page}</font> {$this->_lang['admin'][111]} $prev_page $next_page";
	}
}

new EditBlog();
?>