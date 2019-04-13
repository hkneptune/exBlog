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
 *  File: editannounce.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-06-26 18:18
 *  Homepage: www.exblog.net
 *
 * $Id: editannounce.php,v 1.3 2005/07/02 05:37:43 feeling Exp $
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once("./public.php");

class Announce extends CommonLib
{
	var $announce_id = 0;
	var $errors = array();

	function Announce()
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
		include_once("../{$this->_config['global']['langURL']}/editannounce.php");
		$this->_lang['announce'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/javascript.php");
		$this->_lang['javascript'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/blogadmin.php");
		$this->_lang['admin'] = $langadfun;
		// 输入数据
		$this->_input['_POST'] = $_POST;

		// 用户验证
		if (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['public'][21], "./login.php");

		if (FALSE == empty($this->_input['_POST']['id']) && TRUE == is_numeric($this->_input['_POST']['id'])) $this->announce_id = intval($this->_input['_POST']['id']);
		elseif (FALSE == empty($_GET['id']) && TRUE == is_numeric($_GET['id'])) $this->announce_id = intval($_GET['id']);

		if (FALSE == empty($_POST['action']))
		{
			if ('addaad' == $_POST['action']) $this->_addAnnounceHandler();
			elseif ('ediaad' == $_POST['action'] && FALSE == empty($this->announce_id)) $this->_modifyAnnounceHandler();
		}
		if (FALSE == empty($_GET['action']))
		{
			if ("modify" == $_GET['action'] && FALSE == empty($this->announce_id))
			{
				$this->_getAnnounce($this->announce_id);
				$this->_printOutForm();
			}
			elseif ("edit" == $_GET['action']) $this->_printOutAnnounceList();
			elseif ("delete" == $_GET['action'] && FALSE == empty($this->announce_id)) $this->_deleteAnnounceHandler();
			else $this->_printOutForm();
		}
		else $this->_printOutForm();

		exit();
	}

	function _printOutForm()
	{
		$this->printPageHeader();

		echo "
<script language=\"JavaScript\" type=\"text/javascript\" src=\"../include/ex2.js\"></script>
<script language=Javascript>
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
	//对用户自定义时间是否合法进行验证 END

	if (noe==\"1\")
	{
		//对日期是否为空进行验证
		if (exEdit.at_datevalue.value!='' || exEdit.at_hour.value!='' || exEdit.at_minute.value!='' || exEdit.at_second.value!='')
		{
			if (confirm(check_t10)) {	}
			else return false;
		}
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

function onlyNum()
{
	if(!((event.keyCode>=48 && event.keyCode<=57) || (event.keyCode>=96 && event.keyCode<=105) || event.keyCode == 8)) event.returnValue = false;
}

ubb_codes = new Array();
ubb_codes[0] = new Array('ex_code.gif', 'code', 23, 22, \"{$this->_lang['announce'][10]}\");
ubb_codes[1] = new Array('ex_quote.gif', 'quote', 23, 22, \"{$this->_lang['announce'][11]}\");
ubb_codes[2] = new Array('ex_bold.gif', 'bold', 23, 22, \"{$this->_lang['announce'][12]}\");
ubb_codes[3] = new Array('ex_italicize.gif', 'italicize', 23, 22, \"{$this->_lang['announce'][13]}\");
ubb_codes[4] = new Array('ex_strike.gif', 'strike', 23, 22, \"{$this->_lang['announce'][14]}\");
ubb_codes[5] = new Array('ex_sup.gif', 'sup', 23, 22, \"{$this->_lang['announce'][15]}\");
ubb_codes[6] = new Array('ex_sub.gif', 'exsub', 23, 22, \"{$this->_lang['announce'][16]}\");
ubb_codes[7] = new Array('ex_list.gif', 'list', 23, 22, \"{$this->_lang['announce'][17]}\");
ubb_codes[8] = new Array('ex_left.gif', 'left', 23, 22, \"{$this->_lang['announce'][18]}\");
ubb_codes[9] = new Array('ex_center.gif', 'center', 23, 22, \"{$this->_lang['announce'][19]}\");
ubb_codes[10] = new Array('ex_right.gif', 'right', 23, 22, \"{$this->_lang['announce'][20]}\");
ubb_codes[11] = new Array('ex_justify.gif', 'justify', 23, 22, \"{$this->_lang['announce'][21]}\");
ubb_codes[12] = new Array('ex_underline.gif', 'underline', 23, 22, \"{$this->_lang['announce'][22]}\");
ubb_codes[13] = new Array('ex_move.gif', 'move', 23, 22, \"{$this->_lang['announce'][23]}\");
ubb_codes[14] = new Array('ex_blur.gif', 'exblur', 23, 22, \"{$this->_lang['announce'][24]}\");
ubb_codes[15] = new Array('ex_fliph.gif', 'fliph', 23, 22, \"{$this->_lang['announce'][25]}\");
ubb_codes[16] = new Array('ex_flipv.gif', 'flipv', 23, 22, \"{$this->_lang['announce'][26]}\");
ubb_codes[17] = new Array('ex_iframe.gif', 'frame', 23, 22, \"{$this->_lang['announce'][27]}\");
ubb_codes[18] = new Array('ex_htmlcode.gif', 'htmlcode', 23, 22, \"{$this->_lang['announce'][28]}\");
ubb_codes[19] = new Array('ex_email.gif', 'tag_email', 23, 22, \"{$this->_lang['announce'][29]}\");
ubb_codes[20] = new Array('ex_url.gif', 'hyperlink', 23, 22, \"{$this->_lang['announce'][30]}\");
ubb_codes[21] = new Array('ex_image.gif', 'image', 23, 22, \"{$this->_lang['announce'][31]}\");
ubb_codes[22] = new Array('ex_swf.gif', 'flash', 23, 22, \"{$this->_lang['announce'][32]}\");
ubb_codes[23] = new Array('ex_mp3.gif', 'mp3', 23, 22, \"{$this->_lang['announce'][33]}\");
ubb_codes[24] = new Array('ex_rm.gif', 'rm', 23, 22, \"{$this->_lang['announce'][34]}\");
ubb_codes[25] = new Array('ex_ra.gif', 'ra', 23, 22, \"{$this->_lang['announce'][35]}\");
ubb_codes[26] = new Array('ex_wmv.gif', 'wmv', 23, 22, \"{$this->_lang['announce'][36]}\");
ubb_codes[27] = new Array('ex_wma.gif', 'wma', 23, 22, \"{$this->_lang['announce'][37]}\");
ubb_codes[28] = new Array('ex_mov.gif', 'mov', 23, 22, \"{$this->_lang['announce'][38]}\");
ubb_codes[29] = new Array('ex_object.gif', 'exobject', 23, 22, \"{$this->_lang['announce'][39]}\");

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

function font_list()
{
	for (var i = 0; i < fonts.length; i++) document.write(\"<option value=\\\"\"+fonts[i]+\"\\\">\"+fonts[i]+\"</option>\");
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
	for (var i = 0; i < colors.length; i++) document.write(\"<option style=background-color:\"+colors[i]+\";color:\"+colors[i]+\" value=\"+colors[i]+\">\"+colors[i]+\"</option>\");
}

function manageattachments(url, width, height)
{
	window.open(url, \"upload_file\", \"statusbar=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes,width=\" + width + \",height=\" + height);
	return false;
}
-->
</script>
<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\" name=\"exEdit\">
 <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
  <tr><td height=\"180\">
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\">
      <tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>" . ((FALSE == isset($this->announce_info['title'])) ? $this->_lang['announce'][0] : $this->_lang['announce'][59]) . "</b></td></tr>
      <tr><td width=\"15%\" height=23 align=\"center\">{$this->_lang['announce'][1]}</td><td width=\"85%\"><input name=\"title\" type=\"text\" size=\"40\" value=\"" . ((FALSE == isset($this->announce_info['title'])) ? "" : $this->announce_info['title']) . "\" class=\"input\"></td></tr>
      <tr><td width=\"15%\" height=23 align=\"center\">{$this->_lang['announce'][2]}</td><td width=\"85%\"><input name=\"author\" type=\"text\" value=\"" . $_SESSION['exPass'] . "\" class=\"input\"></td></tr>
      <tr><td width=\"15%\" height=98 rowspan=\"2\" align=\"center\">{$this->_lang['announce'][3]}</td><td width=\"85%\">
        <table border=\"0\" align=\"left\" cellpadding=\"1\" cellspacing=\"1\">
          <tr><script language=Javascript>ex2_list();</script></tr><tr><script language=Javascript>ex_list();</script></tr><tr><script language=Javascript>ubb_codes_list();</script></tr>
        </table>
      </td></tr>
    <tr>
      <td><table class=\"menu\">
		<tr>
           <td>{$this->_lang['announce'][43]}
<select onChange=chfont(this.options[this.selectedIndex].value) name=font>
<option value=\"{$this->_lang['announce'][44]}\" selected>{$this->_lang['announce'][44]}</option>
<option value=\"{$this->_lang['announce'][46]}\">{$this->_lang['announce'][45]}</option>
<option value=\"{$this->_lang['announce'][47]}\">{$this->_lang['announce'][47]}</option>
<option value=\"{$this->_lang['announce'][48]}\">{$this->_lang['announce'][48]}</option>
<option value=\"{$this->_lang['announce'][49]}\">{$this->_lang['announce'][49]}</option>
<script language=javascript>font_list();</script></select>
<select name=\"size\" onFocus=\"this.selectedIndex=2\" onChange=\"chsize(this.options[this.selectedIndex].value)\" size=\"1\">
<script language=javascript>font_sizes_list();</script>
</select>
<select name=\"color\" onFocus=\"this.selectedIndex=1\" onChange=\"chcolor(this.options[this.selectedIndex].value)\" size=\"1\">
<script language=javascript>color_list();</script>
</select>
{$this->_lang['announce'][54]}<select name=\"mode\" onChange=\"chmode(document.exEdit.mode.options[document.exEdit.mode.selectedIndex].value)\">
<option value=2>{$this->_lang['announce'][40]}</option>
<option value=1>{$this->_lang['announce'][41]}</option>
<option value=0>{$this->_lang['announce'][42]}</option>
</select>			</td>
    	</tr>
   </table></td>
    </tr>
    <tr>
      <td align=\"center\" width=\"15%\" valign=\"top\">{$this->_lang['announce'][4]}<br>
<br>
<br><a onclick=document.exEdit.content.rows='30'; title=\"{$this->_lang['announce'][5]}\">{$this->_lang['announce'][6]}</a><br>
<br><a onclick=document.exEdit.content.rows='10'; title=\"{$this->_lang['announce'][7]}\">{$this->_lang['announce'][8]}</a></td>
      <td width=\"85%\"><textarea name=\"content\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\" class=\"input\" onSelect=\"javascript: storeCaret(this);\" onClick=\"javascript: storeCaret(this);\" onKeyUp=\"javascript: storeCaret(this);\">" . ((FALSE == isset($this->announce_info['title'])) ? "" : $this->announce_info['content']) . "</textarea></td>
    </tr>
   <tr>
      <td colspan=\"4\" align=\"center\"><a href=\"#\" onClick=\"manageattachments('upload.php?action=addForm&ins=1&type=ubb&element=content', 400, 200)\">{$this->_lang['announce'][9]}</a>
 <A onclick=pasteC() href=\"#\">{$this->_lang['announce'][50]}</A>
 <a href=\"javascript:checklength(document.exEdit);\" title=\"{$this->_lang['announce'][51]}\">{$this->_lang['announce'][52]}</a></td>
    </tr>
    <tr align=\"center\">
      <td height=30 colspan=\"2\">
	  <input type=\"hidden\" name=\"action\" value=\"" . ((TRUE == empty($this->announce_id)) ? "addaad" : "ediaad") . "\">";
		if (FALSE == empty($this->announce_id)) echo "<input type=\"hidden\" name=\"id\" value=\"{$this->announce_id}\">";
		echo "
	  <input type=\"submit\" value=\"" . ((TRUE == empty($this->announce_id)) ? $this->_lang['announce'][53] : $this->_lang['announce'][60]) . "\" class=\"botton\" onClick=\"javascript: return adcheck();\">
	  </td>
    </tr>
  </table></td>
  </tr>
</table>
</form>
		";

		$this->printPageFooter();
	}

	function _printOutAnnounceList()
	{
		$announces = $this->_getAnnounceList();
		$this->printPageHeader();
		echo "<table width=\"540\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\"><tr><td height=\"180\" valign=\"top\"><table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"98%\" align=center class=\"main\"><tr><td class=\"menu\" colspan=2 height=25 align=\"center\"><b>{$this->_lang['announce'][55]}</b></td></tr>";
		for ($i = 0; $i < count($announces); $i++)
		{
			echo "<tr><td width=\"100\" height=\"23\" align=\"center\">&nbsp;id:[{$announces[$i]['id']}]</td><td>{$announces[$i]['title']}</td><td width=\"100\" align=\"center\"><a href=\"./editannounce.php?action=modify&id={$announces[$i]['id']}\">{$this->_lang['announce'][56]}</a><a href=\"./editannounce.php?action=delete&id={$announces[$i]['id']}\" onclick=\"if(confirm ('\" . $this->_lang['announce'][57] . \"')){;}else{return false;}\">{$this->_lang['announce'][58]}</a></td></tr>";
		}
		echo "<tr><td colspan=\"3\">&nbsp;</td></tr></table>";
		$this->printPageFooter();
	}

	function _getAnnounce($id)
	{
		$query_string = "select * from {$this->_dbop->prefix}announce where id=$id";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['admin'][27];
		else $this->announce_info = $this->_dbop->fetchArray();
		$this->_dbop->freeResult();
		// 处理公告内容
		include_once("../include/ubbcode.php");
		$ubb_op = new UbbCode();
		$this->announce_info['content'] = $ubb_op->unParse($this->announce_info['content']);
		unset($ubb_op);
		return $this->announce_info;
	}

	function _getAnnounceList()
	{
		$announces = array();
		$query_string = "select * from {$this->_dbop->prefix}announce ORDER BY id DESC";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getAffectedRows()) $announces = $this->_dbop->fetchArrayBat();
		$this->_dbop->freeResult();
		return $announces;
	}

	function _checkForm()
	{
		if (TRUE == empty($this->_input['_POST']['title'])) $this->errors[] = $this->_lang['admin'][5];
		elseif (40 < strlen($this->_input['_POST']['title'])) $this->errors[] = $this->_lang['announce'][62];
		if (TRUE == empty($this->_input['_POST']['author'])) $this->_input['_POST']['author'] = $_SESSION['exPass'];
		elseif (20 < strlen($this->_input['_POST']['author'])) $this->errors[] = $this->_lang['announce'][63];
		if (TRUE == empty($this->_input['_POST']['content'])) $this->errors[] = $this->_lang['announce'][64];
	}

	function _addAnnounceHandler()
	{
		$this->escapeSqlCharsFromData($this->_input['_POST']);
		$this->_checkForm();
		if (FALSE == count($this->errors))
		{
			// 处理公告内容
			include_once("../include/ubbcode.php");
			$ubb_op = new UbbCode();
			$this->_input['_POST']['content'] = $ubb_op->parse($this->_input['_POST']['content']);
			unset($ubb_op);

			// 检查同名公告是否存在
			$query_string = "select * from {$this->_dbop->prefix}announce where title='" . $this->_input['_POST']['title'] . "'";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getAffectedRows()) $this->_showError($this->_lang['announce'][62], $_SERVER['PHP_SELF']);
			$this->_dbop->freeResult();

			$query_string = "insert into {$this->_dbop->prefix}announce (title,content,author,email,addtime) values ('" . $this->_input['_POST']['title'] . "', '" . $this->_input['_POST']['content'] . "', '" . $this->_input['_POST']['author'] . "', '" . $_SESSION['exUseremail'] . "', '" . time() . "')";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getAffectedRows()) $this->_showError($this->_lang['announce'][61], $_SERVER['PHP_SELF']);
			$this->_dbop->freeResult();
			$this->showMesg($this->_lang['admin'][26], $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	}

	function _modifyAnnounceHandler()
	{
		$this->escapeSqlCharsFromData($this->_input['_POST']);
		$this->_checkForm();
		if (FALSE == count($this->errors))
		{
			// 处理公告内容
			include_once("../include/ubbcode.php");
			$ubb_op = new UbbCode();
			$this->_input['_POST']['content'] = $ubb_op->parse($this->_input['_POST']['content']);
			unset($ubb_op);

			// 检查同名公告是否存在
			$query_string = "select * from {$this->_dbop->prefix}announce where title='" . $this->_input['_POST']['title'] . "' and id!={$this->announce_id}";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getAffectedRows()) $this->_showError($this->_lang['announce'][62], $_SERVER['PHP_SELF']);
			$this->_dbop->freeResult();

			$query_string = "update {$this->_dbop->prefix}announce set
				title='" . $this->_input['_POST']['title'] . "',
				content='" . $this->_input['_POST']['content'] . "',
				author='" . $this->_input['_POST']['author'] . "',
				email='" . $_SESSION['exUseremail'] . "'
				where id={$this->announce_id}";
			$this->_dbop->query($query_string);
//			if (FALSE == $this->_dbop->getAffectedRows()) $this->_showError($this->_lang['admin'][28], $_SERVER['PHP_SELF']);
			$this->_dbop->freeResult();
			$this->showMesg($this->_lang['admin'][29], $_SERVER['PHP_SELF']);
		}
		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	}

	function _deleteAnnounceHandler()
	{
		$query_string = "select title from {$this->_dbop->prefix}announce where id={$this->announce_id}";
		$this->_dbop->query($query_string);
		if (FALSE == $this->_dbop->getAffectedRows()) $this->errors[] = $this->_lang['admin'][63];
		else $announce_info = $this->_dbop->fetchArray(0, 'ASSOC');
		$this->_dbop->freeResult();
		if (0 < count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']);
		else
		{
			$query_string = "delete from {$this->_dbop->prefix}announce where id={$this->announce_id}";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
			$this->showMesg(sprintf($this->_lang['admin'][61], $announce_info['title']), $_SERVER['PHP_SELF']);
		}
	}
}

new Announce();
?>