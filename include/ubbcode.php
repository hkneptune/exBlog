<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * UBB code parser for exBlog
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

class Coder
{
	var $patterns = array();
	var $prefix_patterns = array();
	var $postfix_patterns = array();

	function Coder()
	{
	} 

	function addTag($pattern, $html = "", $is_pcre = TRUE, $is_callback = FALSE)
	{
		if (TRUE == is_array($pattern))
		{
			for ($i = 0; $i < count($pattern); $i++)
			{
				if (FALSE == in_array($pattern[$i]['pat'], $this->patterns)) $this->patterns[] = array("search" => $pattern[$i]['pat'], "replace" => $pattern[$i]['html'], "pcre" => $pattern[$i]['pcre'], "callback" => $pattern[$i]['callback']);
			} 
		} elseif (FALSE == in_array($pattern, $this->patterns)) $this->patterns[] = array("search" => $pattern, "replace" => $html, "pcre" => $is_pcre, "callback" => $is_callback);
	} 

	function addTagByOrder($pattern, $html = "", $is_pcre = TRUE, $type = "prefix")
	{
		if (TRUE == is_array($pattern))
		{
			for ($i = count($pattern) - 1; $i < 0; $i--)
			{
				if ("prefix" == $type) $this->prefix_patterns[] = array("pat" => $pattern[$i]['pat'], "html" => $pattern[$i]['html'], "pcre" => $is_pcre);
				else $this->postfix_patterns[] = array("pat" => $pattern[$i]['pat'], "html" => $pattern[$i]['html'], "pcre" => $is_pcre);
			} 
		} 
		else
		{
			if ("prefix" == $type) $this->prefix_patterns[] = array("pat" => $pattern, "html" => $html, "pcre" => $is_pcre);
			else $this->postfix_patterns[] = array("pat" => $pattern, "html" => $html, "pcre" => $is_pcre);
		} 
	} 

	function getTags()
	{
		return $this->patterns;
	} 

	function parseCode($str)
	{
		for ($i = 0; $i < count($this->patterns); $i++)
		{
			if (TRUE == $this->patterns[$i]['callback']) $funname = "preg_replace_callback";
			else $funname = "preg_replace";
			if (TRUE == $this->patterns[$i]['pcre']) $str = $funname($this->patterns[$i]['search'], $this->patterns[$i]['replace'], $str);
			else $str = str_replace($this->patterns[$i]['search'], $this->patterns[$i]['replace'], $str);
		} 
		$this->loaded_tags = FALSE;
		return $str;
	} 
} 

class UbbCode extends Coder
{
	var $patterns = array();
	var $prefix_patterns = array();
	var $postfix_patterns = array();
	var $loaded_tags = FALSE;

	function UbbCode()
	{
	} 

	function highlightCode($matched)
	{
		$highlight = "";
		$code = $matched[2];
		$mode = strtolower($matched[1]);
		$base_path = dirname(__FILE__);
		$geshi_lang_path = "$base_path/geshi/";
		if (FALSE == @file_exists($geshi_lang_path . $mode . '.php')) $mode = 'php';
		include_once('geshi.php');
		$highlight_op = new GeSHi($code, $mode, $geshi_lang_path);
/*		$highlight_op->set_header_type(GESHI_HEADER_PRE);
		$highlight_op->enable_classes();
		$highlight_op->set_overall_style('color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', TRUE);
		$highlight_op->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
		$highlight_op->set_code_style('color: #000020;', 'color: #000020;');
	
		$highlight_op->set_link_styles(GESHI_LINK, 'color: #000060;');
		$highlight_op->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');
*/
		$highlight = $highlight_op->parse_code();
		unset($highlight_op);
		return '<code class="' . $mode . '">' . html_entity_decode($highlight) . '</code>';
	}

	function unHighlight($matched)
	{
		$code = $matched[2];
		$code = str_replace(array("&#91;", "&#93;", "&#40;", "&#41;", "&#123;", "&#125;", "<br />"), array("[", "]", "(", ")", "{", "}", "\n"), $code);
		$code = preg_replace('!<[^>]*?>!', ' ', $code);
		return "[code={$matched[1]}]" . $code . "[/code]";
	}

	function parse($str)
	{
		if (FALSE == $this->loaded_tags)
		{
			$this->patterns = array();
			if (TRUE == count($this->prefix_patterns)) $this->addTag($this->prefix_patterns);
			$this->addTag("/\[code=(.*?)\](.*?)\[\/code\]/si", array(&$this, "highlightCode"), TRUE, TRUE);
			$this->addTag("\r\n", "<br />", FALSE);
			$this->addTag("\n", "<br />", FALSE);
			$this->addTag("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", FALSE);
			$this->addTag(";;", "&#59;&#59;", FALSE);
			$this->addTag("/\[hr\]/si", "<hr size=\"1\" />", TRUE);
			$this->addTag("/\[h1\](.*?)\[\/h1\]/si", "<h1>\\1</h1>", TRUE);
			$this->addTag("/\[h2\](.*?)\[\/h2\]/si", "<h2>\\1</h2>", TRUE);
			$this->addTag("/\[h3\](.*?)\[\/h3\]/si", "<h3>\\1</h3>", TRUE);
			$this->addTag("/\[h4\](.*?)\[\/h4\]/si", "<h4>\\1</h4>", TRUE);
			$this->addTag("/\[h5\](.*?)\[\/h5\]/si", "<h5>\\1</h5>", TRUE);
			$this->addTag("/\[h6\](.*?)\[\/h6\]/si", "<h6>\\1</h6>", TRUE);
			$this->addTag("/\[b\](.*?)\[\/b\]/si", "<b>\\1</b>", TRUE);
			$this->addTag("/\[i\](.*?)\[\/i\]/si", "<i>\\1</i>", TRUE);
			$this->addTag("/\[s\](.*?)\[\/s\]/si", "<s>\\1</s>", TRUE);
			$this->addTag("/\[strike\](.*?)\[\/strike\]/si", "<strike>\\1</strike>", TRUE);
			$this->addTag("/\[u\](.*?)\[\/u\]/si", "<u>\\1</u>", TRUE);
			$this->addTag("/\[color=([^\]]*?)\](.*?)\[\/color\]/si", "<span style=\"color:\\1\">\\2</span>", TRUE);
			$this->addTag("/\[size=(\d+)\](.*?)\[\/size\]/si", "<span style=\"font-size:\\1em\">\\2</span>", TRUE);
			$this->addTag("/\[font=([^\]]*?)\](.*?)\[\/font\]/si", "<span style=\"font-family:\\1\">\\2</span>", TRUE);
			$this->addTag("/\[fly\](.*?)\[\/fly\]/si", "<marquee>\\1</marquee>", TRUE);
			$this->addTag("/\[move\](.*?)\[\/move\]/si", "<marquee>\\1</marquee>", TRUE);
			$this->addTag("/\[sub\](.*?)\[\/sub\]/si", "<sub>\\1</sub>", TRUE);
			$this->addTag("/\[sup\](.*?)\[\/sup\]/si", "<sup>\\1</sup>", TRUE);
			$this->addTag("/\[left\](.*?)\[\/left\]/si", "<div align=\"left\">\\1</div>", TRUE);
			$this->addTag("/\[center\](.*?)\[\/center\]/si", "<div align=\"center\">\\1</div>", TRUE);
			$this->addTag("/\[right\](.*?)\[\/right\]/si", "<div align=\"right\">\\1</div>", TRUE);
			$this->addTag("/\[justify\](.*?)\[\/justify\]/si", "<div align=\"justify\">\\1</div>", TRUE);
			$this->addTag("/\[align=([^\]]*?)\](.*?)\[\/align\]/si", "<div align=\"\\1\">\\2</div>", TRUE);
			$this->addTag("/\[shadow=([^\]]*?)\](.*?)\[\/shadow\]/si", "<span style=\"width=80%; filter:shadow(color=\\1)\">\\2</span>", TRUE);
			$this->addTag("/\[dropshadow=([^\]]*?)\](.*?)\[\/dropshadow\]/si", "<span style=\"width=80%; filter:dropshadow(color=\\1)\">\\2</span>", TRUE);
			$this->addTag("/\[glow=([^\]]*?)\](.*?)\[\/glow\]/si", "<span style=\"width=80%; filter:glow(color=\\1)\">\\2</span>", TRUE);
			$this->addTag("/\[flipH\](.*?)\[\/flipH\]/si", "<span style=\"width=80%; filter:flipH\">\\1</span>", TRUE);
			$this->addTag("/\[flipV\](.*?)\[\/flipV\]/si", "<span style=\"width=80%; filter:flipV\">\\1</span>", TRUE);
			$this->addTag("/\[blur\](.*?)\[\/blur\]/si", "<span style=\"width=80%; filter:blur\">\\1</span>", TRUE);
			$this->addTag("/\[invert\](.*?)\[\/invert\]/si", "<span style=\"width=80%; filter:invert\">\\1</span>", TRUE);
			$this->addTag("/\[xray\](.*?)\[\/xray\]/si", "<span style=\"width=80%; filter:xray\">\\1</span>", TRUE);
			$this->addTag("/\[url=([^\]]*?)\](.*?)\[\/url\]/si", "<a href=\"\\1\" title=\"Link\" target=\"_blank\">\\2</a>", TRUE);
			$this->addTag("/\[url\]www\.(.*?)\[\/url\]/si", "<a href=\"http://www.\\1\" title=\"Link\" target=\"_blank\">www.\\1</a>", TRUE);
			$this->addTag("/\[url\](.*?)\[\/url\]/si", "<a href=\"\\1\" title=\"Link\" target=\"_blank\">\\1</a>", TRUE);
			$this->addTag("/\[email=([^\]]*?)\](.*?)\[\/email\]/si", "<a href=\"mailto:\\1\" title=\"Send Mail\">\\2</a>", TRUE);
			$this->addTag("/\[email\](.*?)\[\/email\]/si", "<a href=\"mailto:\\1\" title=\"Send Mail\">\\1</a>", TRUE);
			$this->addTag("/\[list=u\](.*?)\[\/list\]/si", "<ul>\\1</ul>", TRUE);
			$this->addTag("/\[list=o\](.*?)\[\/list\]/si", "<ol type=\"1\">\\1</ol>", TRUE);
			$this->addTag("/\[list=c\](.*?)\[\/list\]/si", "<ul type=\"circle\">\\1</ul>", TRUE);
			$this->addTag("/\[list=s\](.*?)\[\/list\]/si", "<ul type=\"square\">\\1</ul>", TRUE);
			$this->addTag("/\[list\](.*?)\[\/list\]/si", "<ul>\\1</ul>", TRUE);
			$this->addTag("/\[list=([^\]]*?)\](.*?)\[\/list\]/si", "<ol type=\"\\1\">\\2</ol>", TRUE);
			$this->addTag("/\[\*=(\d+)\](.*?)\[\/\*\]/si", "<li value=\"\\1\">\\2</li>", TRUE);
			$this->addTag("/\[\*\](.*?)\[\/\*\]/si", "<li>\\1</li>", TRUE);
			$this->addTag("/\[img\](.*?)\[\/img\]/si", "<img src=\"\\1\" alt=\"image\" border=\"0\" />", TRUE);
			$this->addTag("/\[img=(\d+),(\d+)\](.*?)\[\/img\]/si", "<img alt=\"image\" src=\"\\3\" width=\"\\1\" height=\"\\2\" border=\"0\" />", TRUE);
			$this->addTag("/\[img=([^\]]*?) wh=(\d+),(\d+)\](.*?)\[\/img\]/si", "<img title=\"\\1\" src=\"\\4\" alt=\"\\1\" width=\"\\2\" height=\"\\3\" border=\"0\" />", TRUE);
			$this->addTag("/\[img=([^\]]*?)\](.*?)\[\/img\]/si", "<img alt=\"\\1\" src=\"\\2\" title=\"\\1\" border=\"0\" />", TRUE);
			$this->addTag("/\[limg\](.*?)\[\/limg\]/si", "<img align=\"left\" alt=\"image\" src=\"\\1\" border=\"0\" />", TRUE);
			$this->addTag("/\[cimg\](.*?)\[\/cimg\]/si", "<div align=\"center\"><img src=\"\\1\" alt=\"image\" border=\"0\" /></div>", TRUE);
			$this->addTag("/\[rimg\](.*?)\[\/rimg\]/si", "<img align=\"right\" alt=\"image\" src=\"\\1\" border=\"0\" />", TRUE);
			$this->addTag("/:ex(.*?):/si", "<img src=\"images/ex_pose/ex\\1.gif\" alt=\"pose\" border=\"0\" />", TRUE);
			$this->addTag("/\[smile\](.*?)\[\/smile\]/si", "<img src=\"images/ex_pose/\\1.gif\" alt=\"pose\" border=\"0\" />", TRUE);
			$this->addTag("/\[table\](.*?)\[\/table\]/si", "<table border=\"1\">\\1</table>", TRUE);
			$this->addTag("/\[tr\](.*?)\[\/tr\]/si", "<tr>\\1</tr>", TRUE);
			$this->addTag("/\[throw=(\d+)\](.*?)\[\/throw\]/si", "<th rowspan=\"\\1\">\\2</th>", TRUE);
			$this->addTag("/\[thcol=(\d+)\](.*?)\[\/thcol\]/si", "<th colspan=\"\\1\">\\2</th>", TRUE);
			$this->addTag("/\[tdrow=(\d+)\](.*?)\[\/tdrow\]/si", "<td rowspan=\"\\1\">\\2</td>", TRUE);
			$this->addTag("/\[tdcol=(\d+)\](.*?)\[\/tdcol\]/si", "<td colspan=\"\\1\">\\2</td>", TRUE);
			$this->addTag("/\[th\](.*?)\[\/th\]/si", "<th>\\1</th>", TRUE);
			$this->addTag("/\[td\](.*?)\[\/td\]/si", "<td>\\1</td>", TRUE);
			$this->addTag("/\[quote\](.*?)\[\/quote\]/si", "<p><strong>Quote:</strong><br /><div style=\"border: 1px dotted #999;background: #eed;padding: 5px;overflow: hidden;font-size: 90%;\">\\1</div></p>", TRUE);
			$this->addTag("/\[code\](.*?)\[\/code\]/si", "<p><strong>Code:</strong><br /><div style=\"border: 1px dotted #999;background: #dee;padding: 5px;overflow: hidden;font-size: 90%;\">\\1</div></p>", TRUE);
			$this->addTag("/\[html\](.*?)\[\/html\]/si", "<p><strong>Html Code:</strong><br /><div style=\"border: 1px dotted #999;background: #ede;padding: 5px;overflow: hidden;font-size: 90%;\">\\1</div></p>", TRUE);
			$this->addTag("/\[reply\](.*?)\[\/reply\]/si", "<hr size=\"0\" noshade width=\"95%\" /><span style=\"color: red; padding: 0px 0px 0px 5em; width: 90%;\">\\1</span>", TRUE);
			$this->addTag("/\[p\]/si", "<p>", TRUE);
			$this->addTag("/\[\/p\]/si", "</p>", TRUE);
			$this->addTag("/\[pre\](.*?)\[\/pre\]/si", "<pre>\\1</pre>", TRUE);
			$this->addTag("/\[iframe\](.*?)\[\/iframe\]/si", "<iframe src=\"\\1\" frameborder=\"0\" width=\"95%\" height=\"480\"><a href=\"\\1\" target=\"_blank\">CLick Here!</a></iframe>", TRUE);
			$this->addTag("/\[sig\](.*?)\[\/sig\]/si", "<div style=\"text-align: left; color: darkgreen; margin-left: 5%\"><br /><br />--------------------------<br />\\1<br />--------------------------</div>", TRUE);
			$this->addTag("/\[mp3\]([^\[]+)\[\/mp3\]/i", "<object align=middle classid=CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95 class=OBJECT id=MediaPlayer width=480 height=360 ><param name=AUTOSTART VALUE=true ><param name=ShowStatusBar value=-1><param name=Filename value=\\1><embed type=application/x-oleobject codebase=http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701 flename=mp src=\"\\1\" width=480 height=360></embed></object>", TRUE);
			if (TRUE == count($this->postfix_patterns)) $this->addTag($this->postfix_patterns);
			$this->loaded_tags = TRUE;
		} 
		return $this->parseCode($str);
	} 

	function unParse($str)
	{
		if (FALSE == $this->loaded_tags)
		{
			$this->patterns = array();
			if (TRUE == count($this->prefix_patterns)) $this->addTag($this->prefix_patterns);
			$this->addTag("&#59;&#59;", ";;", FALSE);
			$this->addTag("&nbsp;", " ", FALSE);
			$this->addTag('/<code\s+class="(.*?)">(.*?)<\/code>/si', array(&$this, "unHighlight"), TRUE, TRUE);
			$this->addTag("/<object\s+align=middle\s+classid=CLSID:22d6f312\-b0f6\-11d0\-94ab\-0080c74c7e95\s+class=OBJECT\s+id=MediaPlayer\s+width=480\s+height=360\s+><param\s+name=AUTOSTART\s+VALUE=true\s+><param\s+name=ShowStatusBar\s+value=\-1><param\s+name=Filename\s+value=([^>]+)><embed\s+type=application\/x\-oleobject\s+codebase=http:\/\/activex\.microsoft\.com\/activex\/controls\/mplayer\/en\/nsmp2inf\.cab#Version=5,1,52,701\s+flename=mp\s+src=\"(.*?)\"\s+width=480\s+height=360><\/embed><\/object>/i", "[mp3]\\1[/mp3]", TRUE);
			$this->addTag("/<div style=\"text-align: left; color: darkgreen; margin-left: 5%\"><br \/><br \/>--------------------------<br \/>(.*?)<br \/>--------------------------<\/div>/si", "[sig]\\1[/sig]", TRUE);
			$this->addTag("/<iframe src=\"(.*?)\"(.*?)>(.*?)<\/iframe>/si", "[iframe]\\1[/iframe]", TRUE);
			$this->addTag("/<pre>(.*?)<\/pre>/si", "[pre]\\1[/pre]", TRUE);
			$this->addTag("/<hr size=\"0\" noshade width=\"95%\" \/><span style=\"color: red; padding: 0px 0px 0px 5em; width: 90%;\">(.*?)<\/span>/si", "[reply]\\1[/reply]", TRUE);
			$this->addTag("/<p><strong>Html Code:<\/strong><br \/><div style=\"border: 1px dotted #999;background: #ede;padding: 5px;overflow: hidden;font-size: 90%;\">(.*?)<\/div><\/p>/si", "[html]\\1[/html]", TRUE);
			$this->addTag("/<p><strong>Code:<\/strong><br \/><div style=\"border: 1px dotted #999;background: #dee;padding: 5px;overflow: hidden;font-size: 90%;\">(.*?)<\/div><\/p>/si", "[code]\\1[/code]", TRUE);
			$this->addTag("/<p><strong>Quote:<\/strong><br \/><div style=\"border: 1px dotted #999;background: #eed;padding: 5px;overflow: hidden;font-size: 90%;\">(.*?)<\/div><\/p>/si", "[quote]\\1[/quote]", TRUE);
			$this->addTag("/<td colspan=\"(\d+)\">(.*?)<\/td>/si", "[tdcol=\\1]\\2[/tdcol]", TRUE);
			$this->addTag("/<td rowspan=\"(\d+)\">(.*?)<\/td>/si", "[tdrow=\\1]\\2[/tdrow]", TRUE);
			$this->addTag("/<th colspan=\"(\d+)\">(.*?)<\/th>/si", "[thcol=\\1]\\2[/thcol]", TRUE);
			$this->addTag("/<th rowspan=\"(\d+)\">(.*?)<\/th>/si", "[throw=\\1]\\2[/throw]", TRUE);
			$this->addTag("/<td>(.*?)<\/td>/si", "[td]\\1[/td]", TRUE);
			$this->addTag("/<th>(.*?)<\/th>/si", "[th]\\1[/th]", TRUE);
			$this->addTag("/<tr>(.*?)<\/tr>/si", "[tr]\\1[/tr]", TRUE);
			$this->addTag("/<table border=\"1\">(.*?)<\/table>/si", "[table]\\1[/table]", TRUE);
			$this->addTag("/<img src=\"images\/ex_pose\/ex(.*?)\.gif\"(.*?)>/si", ":ex\\1:", TRUE);
			$this->addTag("/<img src=\"images\/ex_pose\/(.*?).gif\" alt=\"pose\" border=\"0\" \/>/si", "[smile]\\1[/smile]", TRUE);
			$this->addTag("/<img align=\"right\" alt=\"image\" src=\"(.*?)\"(.*?)>/si", "[rimg]\\1[/rimg]", TRUE);
			$this->addTag("/<div align=\"center\"><img src=\"(.*?)\" alt=\"image\"(.*?)><\/div>/si", "[cimg]\\1[/cimg]", TRUE);
			$this->addTag("/<img align=\"left\" alt=\"image\" src=\"(.*?)\"(.*?)>/si", "[limg]\\1[/limg]", TRUE);
			$this->addTag("/<img alt=\"image\" src=\"(.*?)\" width=\"(\d+)\" height=\"(\d+)\"(.*?)>/si", "[img=\\2,\\3]\\1[/img]", TRUE);
			$this->addTag("/<img title=\"(.*?)\" src=\"(.*?)\"(.*?)width=\"(\d+)\" height=\"(\d+)\"(.*?)>/si", "[img=\\1 wh=\\4,\\5]\\2[/img]", TRUE);
			$this->addTag("/<img alt=\"(.*?)\" src=\"(.*?)\" title=\"(.*?)\"(.*?)>/si", "[img=\\1]\\2[/limg]", TRUE);
			$this->addTag("/<img src=\"(.*?)\" alt=\"image\" border=\"0\"(.*?)>/si", "[img]\\1[/img]", TRUE);
			$this->addTag("/<img alt=\"image\" src=\"(.*?)\" border=\"0\"(.*?)>/si", "[img]\\1[/img]", TRUE);
			$this->addTag("/<li value=\"(\d+)\">(.*?)<\/li>/si", "[*=\\1]\\2[/*]", TRUE);
			$this->addTag("/<li>(.*?)<\/li>/si", "[*]\\1[/*]", TRUE);
			$this->addTag("/<ol style=\"list-style-type:decimal;\">(.*?)<\/ol>/si", "[list=1]\\1[/list]", TRUE);
			$this->addTag("/<ol style=\"list-style-type:lower-alpha;\">(.*?)<\/ol>/si", "[list=a]\\1[/list]", TRUE);
			$this->addTag("/<ol style=\"list-style-type:upper-alpha;\">(.*?)<\/ol>/si", "[list=A]\\1[/list]", TRUE);
			$this->addTag("/<ol style=\"list-style-type:lower-roman;\">(.*?)<\/ol>/si", "[list=i]\\1[/list]", TRUE);
			$this->addTag("/<ol style=\"list-style-type:upper-roman;\">(.*?)<\/ol>/si", "[list=I]\\1[/list]", TRUE);
			$this->addTag("/<ul type=\"square\">(.*?)<\/ul>/si", "[list=s]\\1[/list]", TRUE);
			$this->addTag("/<ul type=\"circle\">(.*?)<\/ul>/si", "[list=c]\\1[/list]", TRUE);
			$this->addTag("/<ol type=(.*?)>(.*?)<\/ol>/si", "[list=\\1]\\2[/list]", TRUE);
			$this->addTag("/<ul>(.*?)<\/ul>/si", "[list]\\1[/list]", TRUE);
			$this->addTag("/<a href=\"mailto:(.*?)\" title=\"Send Mail\">(.*?)<\/a>/si", "[email=\\1]\\2[/email]", TRUE);
			$this->addTag("/<a href=\"(.*?)\" title=\"Link\" target=\"_blank\">(.*?)<\/a>/", "[url=\\1]\\2[/url]", TRUE);
			$this->addTag("/<a( title=\".*?\")* href=\"(.*?)\" target=\"_blank\">(.*?)<\/a>/", "[url=\\2]\\3[/url]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:xray\">(.*?)<\/span>/", "[xray]\\1[/xray]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:invert\">(.*?)<\/span>/", "[invert]\\1[/invert]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:blur\">(.*?)<\/span>/", "[blur]\\1[/blur]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:flipV\">(.*?)<\/span>/", "[flipV]\\1[/flipV]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:flipH\">(.*?)<\/span>/", "[flipH]\\1[/flipH]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:glow\(color=(.*?)\)\">(.*?)<\/span>/", "[glow=\\1]\\2[/glow]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:dropshadow\(color=(.*?)\)\">(.*?)<\/span>/", "[dropshadow=\\1]\\2[/dropshadow]", TRUE);
			$this->addTag("/<span style=\"width=80%; filter:shadow\(color=(.*?)\)\">(.*?)<\/span>/", "[shadow=\\1]\\2[/shadow]", TRUE);
			$this->addTag("/<div align=\"(.*?)\">(.*?)<\/div>/", "[\\1]\\2[/\\1]", TRUE);
			$this->addTag("/<sub>(.*?)<\/sub>/si", "[sub]\\1[/sub]", TRUE);
			$this->addTag("/<sup>(.*?)<\/sup>/si", "[sup]\\1[/sup]", TRUE);
			$this->addTag("/<marquee>(.*?)<\/marquee>/si", "[move]\\1[/move]", TRUE);
			$this->addTag("/<span style=\"font-family:(.*?)\">(.*?)<\/span>/si", "[font=\\1]\\2[/font]", TRUE);
			$this->addTag("/<span style=\"font-size:(\d+)em\">(.*?)<\/span>/si", "[size=\\1]\\2[/size]", TRUE);
			$this->addTag("/<font size=\"(\d+?)\">(.*?)<\/font>/si", "[size=\\1]\\2[/size]", TRUE);
			$this->addTag("/<span style=\"color:(.*?)\">(.*?)<\/span>/si", "[color=\\1]\\2[/color]", TRUE);
			$this->addTag("/<font color=\"(.*?)\">(.*?)<\/font>/si", "[color=\\1]\\2[/color]", TRUE);
			$this->addTag("/<hr size=\"(\d+)\" \/>/si", "[hr]", TRUE);
			$this->addTag("/<span style=\"text-decoration: underline;\">(.*?)<\/span>/", "[u]\\1[/u]", TRUE);
			$this->addTag("/<font color=\"(.*?)\">(.*?)<\/font>/", "\[color=\\1]\\2[/color]", TRUE);
			$this->addTag("/<u>(.*?)<\/u>/", "[u]\\1[/u]", TRUE);
			$this->addTag("/<s>(.*?)<\/s>/", "[s]\\1[/s]", TRUE);
			$this->addTag("/<strike>(.*?)<\/strike>/", "[strike]\\1[/strike]", TRUE);
			$this->addTag("/<i>(.*?)<\/i>/", "[i]\\1[/i]", TRUE);
			$this->addTag("/<em>(.*?)<\/em>/", "[em]\\1[/em]", TRUE);
			$this->addTag("/<b>(.*?)<\/b>/", "[b]\\1[/b]", TRUE);
			$this->addTag("/<strong>(.*?)<\/strong>/", "[b]\\1[/b]", TRUE);
			$this->addTag("/<p>/si", "[p]", TRUE);
			$this->addTag("/<\/p>/si", "[/p]", TRUE);
			$this->addTag("<br />", "\n", FALSE);
			if (TRUE == count($this->postfix_patterns)) $this->addTag($this->postfix_patterns);
			$this->loaded_tags = TRUE;
		} 
		return $this->parseCode($str);
	} 
} 

?>
