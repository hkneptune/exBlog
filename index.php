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
 *  File: index.php
 *  Author: feeling <feeling@exblog.org>
 *  Date: 2005-08-09 11:47
 *  Homepage: www.exblog.net
 *
 * $Id$
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once("./public.php");

class IndexPage extends PublicCommon
{
	var $errors = array();
	var $current_page = 1;
	var $current_time = 0;
	var $current_sort = 0;
	var $current_id = 0;
	var $timeout = 300;
	var $announce_id = 0;
	var $template_name = '';
	var $mode = 'normal';

	function IndexPage()
	{
		// Redirect to WAP page
		if (9201 == $_SERVER['REMOTE_PORT'])
		{
			header("location: wap.php");
			exit();
		}
		$this->start_time = $this->microtime_float();
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig();
		// 装入系统配置
		$this->_loadSystemOptions();
		// 装入语言文件
		include_once("./{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);
		// 装入功能对应语言文件
		include_once("./{$this->_config['global']['langURL']}/index.php");
		$this->_lang['index'] = $lang;

		// 组合POST和GET数据
		$this->_input = array_merge($_GET, $_POST);
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $_POST;

		if (FALSE == empty($_GET['sort']) && TRUE == is_numeric($_GET['sort'])) $this->current_sort = intval($_GET['sort']);
		if (FALSE == empty($_GET['id']) && TRUE == is_numeric($_GET['id'])) $this->current_id = intval($_GET['id']);
		if (FALSE == empty($_GET['anid']) && TRUE == is_numeric($_GET['anid'])) $this->announce_id = intval($_GET['anid']);
		if (FALSE == empty($_GET['page']) && TRUE == is_numeric($_GET['page'])) $this->current_page = intval($_GET['page']);
		if (FALSE == empty($_GET['mode']) && 'list' == $_GET['mode']) $this->mode = 'list';
		$this->_getCurrentTime();

		$this->_counter();

		@session_start();
		$this->_initHtml();

		if (FALSE == empty($this->_input['_POST']['action']))
		{
			if ('reglink' == $this->_input['_POST']['action']) $this->handlerRegisterLink();
			else $this->_printBlogsList();
		}
		elseif (FALSE == empty($_GET['play']))
		{
			if ('reply' == $_GET['play'] && FALSE == empty($this->current_id) && TRUE == is_numeric($this->current_id)) $this->_printBlogInfo();
			elseif ('announce' == $_GET['play'] && FALSE == empty($this->announce_id) && TRUE == is_numeric($this->announce_id)) $this->_printAnnounceInfo();
			elseif ('about' == $_GET['play']) $this->printAboutInfo();
			elseif ('links' == $_GET['play'])
			{
				if (FALSE == empty($_GET['action']))
				{
					if ('GoTo' == $_GET['action']) $this->goToLink();
					elseif ('Register' == $_GET['action']) $this->printRegisterLinkForm();
					else $this->printLinksList();
				}
				else $this->printLinksList();
			}
			elseif ('tag' == $_GET['play'] && TRUE == empty($_GET['tags'])) $this->printTagsList();
			elseif ('logout' == $_GET['play']) $this->logout();
			else $this->_printBlogsList();
		}
		else $this->_printBlogsList();
		$this->end_time = $this->microtime_float();
		$this->_html->assign("end_time", $this->end_time);
		$this->_html->display($this->template_name);

		exit();
	}

	// 点击友情链接
	function goToLink()
	{
		$query_string = "select * from {$this->_dbop->prefix}links where id={$this->current_id} and visible='1'";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $link_info = $this->_dbop->fetchArray(0, 'ASSOC');
		else
		{
			$this->errors[] = $this->_lang['index'][31];
			$link_info = array();
		}
		$this->_dbop->freeResult();

		if (FALSE == count($this->errors))
		{
			$query_string = "update {$this->_dbop->prefix}links set visits=visits+1 where id={$this->current_id} and visible='1'";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
//			echo $link_info['homepage'];
			header("Location: {$link_info['url']}");
		}
		else $this->_printBlogsList();
	}

	// 友情链接申请表单
	function printRegisterLinkForm()
	{
		$this->template_name = "register_link.tpl";
	}

	// 输出BLOG列表
	function _printBlogsList()
	{
		$counter = $this->_getPagesInfo();
		$counter['blog_perpage'] = $this->_config['global']['listblognum'];
		if ($this->current_page > $counter['pages']) $this->current_page = $counter['pages'];
		$counter['current_page'] = $this->current_page;
		$this->_html->assign(
			array(
				"blogs_list"		=> $this->_getBlogs(),
				"settop_blogs_list"	=> $this->_getTopArticles(),
				"counter"			=> $counter
			)
		);
		if ('list' != $this->mode) $this->template_name = "blog_list.tpl";
		else $this->template_name = "blog_list_mode_list.tpl";
	}

	// 输出BLOG信息
	function _printBlogInfo()
	{
		$blog_info = $this->_getBlogInfo();
		if (FALSE == empty($blog_info['content'])) $blog_info['content'] = $this->_parseKeyword($blog_info['content']);

		if (TRUE == count($blog_info))
		{
			// 更新阅读统计
			$query_string = "update {$this->_dbop->prefix}blog set visits=visits+1 where id={$this->current_id}";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();
			// 输出页面
			$this->_html->assign(
				array(
					"blog_info"	=> $blog_info,
//					"trackbacks"	=> $this->_getTrackbacks(),
					"comments_list"	=> $this->_getLatestRecords("comment", 10, "id desc", "commentID='{$this->current_id}'")
				)
			);
			$this->template_name = "blog_content.tpl";
		}
	}

	// 输出指定公告内容
	function _printAnnounceInfo()
	{
		$announce_info = $this->_getLatestRecords("announce", 1, "", "id={$this->announce_id}", "*", 0, TRUE);
		if (TRUE == count($announce_info))
		{
			$this->_html->assign("announce_info", $announce_info);
			$this->template_name = "announce.tpl";
		}
	}

	// 输出友情链接列表
	function printLinksList()
	{
		$links_info = $this->_getLatestRecords("links", 0, "id desc", "visible='1'");
		$this->_html->assign("links_list", $links_info);
		$this->template_name = "link_list.tpl";
	}

	// 输出 tag 列表
	function printTagsList()
	{
		// 获取TAG列表
		$tmp = $this->_getLatestRecords("tags", 0, "id desc", "", "tag");

		// 生成tag列表
		$new_tmp = '';
		for ($i = 0; $i < count($tmp); $i++) $new_tmp .= "|{$tmp[$i]['tag']}";
		if (TRUE == strlen($new_tmp)) $new_tmp = substr($new_tmp, 1);
		$tags_list = explode("|", $new_tmp);
		$tags_list = array_unique($tags_list);

		$this->_html->assign("tags_list", $tags_list);
		$this->template_name = "tag_list.tpl";
	}

	// 输出关于信息
	function printAboutInfo()
	{
		$about_info = $this->_getLatestRecords("aboutme", 1, "", "", "*", 0, TRUE);

		$this->_html->assign("about_info", $about_info);
		$this->template_name = "about_info.tpl";
	}

	// 注册友情链接
	function handlerRegisterLink()
	{
		if (TRUE == empty($this->_input['_POST']['webTitle'])) $this->errors[] = $this->_lang['index'][55];
		elseif (20 < strlen($this->_input['_POST']['webTitle'])) $this->errors[] = $this->_lang['index'][56];
		if (TRUE == empty($this->_input['_POST']['webUrl'])) $this->errors[] = $this->_lang['index'][57];
		elseif (150 < strlen($this->_input['_POST']['webUrl'])) $this->errors[] = $this->_lang['index'][58];
		elseif (FALSE == preg_match("/^http:\/\/.*/i", $this->_input['_POST']['webUrl'])) $this->errors[] = $this->_lang['index'][59];
		if (TRUE == empty($this->_input['_POST']['webLogo'])) $this->_input['_POST']['webLogo'] = '';
		elseif (150 < strlen($this->_input['_POST']['webLogo'])) $this->errors[] = $this->_lang['index'][60];
		elseif (FALSE == preg_match("/^http:\/\/.*?\.[png|jpg|gif]$/i", $this->_input['_POST']['webLogo'])) $this->errors[] = $this->_lang['index'][61];
		if (TRUE == empty($this->_input['_POST']['webDescription'])) $this->_input['_POST']['webDescription'] = '';
		elseif (100 < strlen($this->_input['_POST']['webDescription'])) $this->errors[] = $this->_lang['index'][62];
		@session_start();
		if (TRUE == empty($this->_input['_POST']['imgVal']) || TRUE == empty($_SESSION['verify_string']['RegisterLink']) || $this->_input['_POST']['imgVal'] != $_SESSION['verify_string']['RegisterLink']) $this->errors[] = $this->_lang['index'][63];
		if (FALSE == count($this->errors))
		{
			// 检查同名/同链接的友情链接是否已经存在
			$query_string = "select * from {$this->_dbop->prefix}links where homepage='{$this->_input['_POST']['webTitle']}' or url='{$this->_input['_POST']['webUrl']}'";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['index'][65];
			$this->_dbop->freeResult();

			if (FALSE == count($this->errors))
			{
				$query_string = "insert into {$this->_dbop->prefix}links (homepage, logoURL, url, description, visits, visible, linkOrder) values ('{$this->_input['_POST']['webTitle']}', '{$this->_input['_POST']['webLogo']}', '{$this->_input['_POST']['webUrl']}', '{$this->_input['_POST']['webDescription']}', 0, '0', 0)";
				$this->_dbop->query($query_string);
				$this->_dbop->freeResult();
				$this->_html->assign("RegisterLinkSuccessful", TRUE);
				$this->template_name = "register_link.tpl";
			}
			else
			{
				$this->_html->assign("messages", $this->errors);
				$this->template_name = "errors.tpl";
			}
		}
		else
		{
			$this->_html->assign("messages", $this->errors);
			$this->template_name = "errors.tpl";
		}
	}

	// 退出系统
	function logout()
	{
		setcookie("exBlogUser", "", time()-3600);
		@session_start();
		session_unregister("exPass");
		session_unregister("exPassword");
		session_unregister("userID");
		session_destroy();
		header("location: {$_SERVER['PHP_SELF']}");
	}

// -------------------------------------------------------------------------------------------------------------------------------
//  blog 相关的方法
// -------------------------------------------------------------------------------------------------------------------------------

	function _getTopArticles()
	{
		$query_string = "select t1.*, t2.cnName from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where t1.top='1' and t2.id=t1.sort";
		if (FALSE == empty($this->current_sort)) $query_string .= " and t1.sort={$this->current_sort}";
		$query_string .=" order by id desc";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $top_articles = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $top_articles = array();
		$this->_dbop->freeResult();
		return $top_articles;
	}

	function _getBlogs()
	{
		$blog_priod = $this->_getBlogStartAndEnd();
		$start_time = $blog_priod['start_time'];
		$end_time = $blog_priod['end_time'];
		$query_string = "select t1.*, t2.cnName from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where t1.top!='1' and t2.id=t1.sort
			and t1.addtime between $start_time and $end_time and t1.top<>'1'";
		if (FALSE == empty($this->current_sort)) $query_string .= " and t1.sort={$this->current_sort}";
		if (FALSE == empty($_GET['tags']))
		{
			$tag_keyword = $this->escapeSqlCharsFromData($_GET['tags']);
			$query_string .= " and (t1.keyword LIKE '%,$tag_keyword' or t1.keyword LIKE '$tag_keyword,%' or t1.keyword LIKE '%,$tag_keyword,%' or t1.keyword='$tag_keyword')";
		}
		$query_string .= " order by t1.id desc
			limit " . (($this->current_page - 1) * intval($this->_config['global']['listblognum'])) .", " . intval($this->_config['global']['listblognum']);
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $result = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $result = array();
		$this->_dbop->freeResult();
		for ($i = 0; $i < count($result); $i++) $result[$i]['summarycontent'] = html_entity_decode($result[$i]['summarycontent']);
		return $result;
	}

	function _getBlogInfo()
	{
		$query_string = "select t1.*, t2.cnName from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where t1.id={$this->current_id} and t2.id=t1.sort";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $blog_info = $this->_dbop->fetchArray(0, 'ASSOC');
		else
		{
			$blog_info = array();
			$this->errors[] = $this->_lang['index'][1];
		}
		if (FALSE == empty($blog_info['keyword'])) $blog_info['keywords'] = explode(',', $blog_info['keyword']);
		$this->_dbop->freeResult();
		return $blog_info;
	}

	function _parseKeyword($content)
	{
		// 获取名字解释列表
		$keyword = array();
		$query_string = "select word, content, url from {$this->_dbop->prefix}keyword";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $keyword = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();

		// 解释名词
		if (TRUE == count($keyword))
		{
			include_once("./include/ubbcode.php");
			$ubb_op = new Coder();
			for ($i = 0; $i < count($keyword); $i++)
			{
				$replace_str = "<acronym title=\"{$keyword[$i]['word']}: {$keyword[$i]['content']}\">{$keyword[$i]['word']}</acronym>";
				if (FALSE == empty($keyword[$i]['url'])) $replace_str = "<a href=\"{$keyword[$i]['url']}\" target=\"_blank\">$replace_str</a>";
				$ubb_op->addTag($keyword[$i]['word'], $replace_str, FALSE);
			}
			$content = $ubb_op->parseCode($content);
		}

		return $content;
	}

// -------------------------------------------------------------------------------------------------------------------------------
//  其他公用方法
// -------------------------------------------------------------------------------------------------------------------------------

	function _getPagesInfo()
	{
		$blog_priod = $this->_getBlogStartAndEnd();
		$start_time = $blog_priod['start_time'];
		$end_time = $blog_priod['end_time'];
		$query_string = "select count(*) as counter from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where t2.id=t1.sort
			and t1.addtime between $start_time and $end_time and t1.top<>'1'";
		if (FALSE == empty($this->current_sort)) $query_string .= " and t1.sort={$this->current_sort}";
		if (FALSE == empty($_GET['tags']))
		{
			$tag_keyword = $this->escapeSqlCharsFromData($_GET['tags']);
			$query_string .= " and (t1.keyword LIKE '%,$tag_keyword' or t1.keyword LIKE '$tag_keyword,%' or t1.keyword LIKE '%,$tag_keyword,%' or t1.keyword='$tag_keyword')";
		}
		$this->_dbop->query($query_string);
		$blog_counter = $this->_dbop->fetchArray(0, 'ASSOC');
		$this->_dbop->freeResult();

		$total_pages = ceil($blog_counter['counter'] / intval($this->_config['global']['listblognum']));
		if (0 == $total_pages) $total_pages = 1;
		return array("pages" => $total_pages, "blogs" => $blog_counter['counter']);
	}

	function _getBlogStartAndEnd()
	{
		$count_date_part = $this->_getCurrentTime();
		if (2 < $count_date_part)
		{
			$start_time = mktime(0, 0, 0, date("m", $this->current_time), date("d", $this->current_time), date("Y", $this->current_time));
			$end_time = mktime(0, 0, 0, date("m", $this->current_time), date("d", $this->current_time) + 1, date("Y", $this->current_time));
		}
		elseif (1 < $count_date_part)
		{
			$start_time = mktime(0, 0, 0, date("m", $this->current_time), 1, date("Y", $this->current_time));
			$end_time = mktime(0, 0, 0, date("m", $this->current_time) + 1, 1, date("Y", $this->current_time));
		}
		elseif (0 < $count_date_part)
		{
			$start_time = mktime(0, 0, 0, 1, 1, date("Y", $this->current_time));
			$end_time = mktime(0, 0, 0, 1, 1, date("Y", $this->current_time) + 1);
		}
		else
		{
			$start_time = intval($this->_config['global']['initTime']);
			$end_time = time();
		}
		return array("start_time" => $start_time, "end_time" => $end_time);
	}
}

new IndexPage();
?>