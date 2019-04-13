<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
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

define('MODULE_NAME', 'core-pub.rss');

require_once("./public.php");
class Rss extends PublicCommon
{
	var $errors = array();
	var $rss_version = '2.0';
	var $template_name = '';
	var $counter = array("blog_perpage" => 15, "current_page" => 1, "pages" => 1, "blogs" => 0);
	var $current_sort = 0;
	var $current_page = 1;
	var $output2file = FALSE;
	var $output_filename = "";
	var $start_time = 0.0;
	var $current_time = 0;

	function Rss()
	{
		$this->setModuleName(MODULE_NAME);
		$this->start_time = $this->microtime_float();
		$this->current_time = time();

		$this->_loadDatabaseConfig();
		$this->_loadSystemOptions();

		$this->_getVariables(); 
		// Pager
		$this->counter = $this->_getPagesInfo();
		$this->counter['blog_perpage'] = $this->_config['global']['listblognum'];
		if (FALSE == empty($this->_input['_GET']['page']) && TRUE == is_numeric($this->_input['_GET']['page'])) $this->current_page = intval($this->_input['_GET']['page']);
		if (1 > $this->current_page) $this->current_page = 1;
		elseif ($this->counter['pages'] < $this->current_page) $this->current_page = $this->counter['pages'];
		$this->counter['current_page'] = $this->current_page; 
		// Rss Version
		if (FALSE == empty($this->_input['_GET']['version']))
		{
			switch ($this->_input['_GET']['version'])
			{
				case "1" : $this->rss_version = "1.0";
					break;
				case "091" : $this->rss_version = "0.91";
					break;
				case "atom" : $this->rss_version = "atom";
					break;
				default: $this->rss_version = "2.0";
					break;
			} 
		} 

		include_once("./{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun);

		include_once("./include/Smarty/Smarty.class.php");
		$this->_html = new Smarty();
		$this->_html->template_dir = "./{$this->_config['global']['tmpURL']}";
		$this->_html->compile_dir = "./{$this->_config['global']['tmpURL']}/compiled";
		$this->_html->config_dir = "./{$this->_config['global']['tmpURL']}/config";
		$this->_html->debugging = FALSE;

		$this->end_time = $this->microtime_float();

		$this->_html->assign(
			array("charset" => $this->_lang['public']['charset'],
				"language" => $this->_lang['public']['language'],
				"text_direct" => $this->_lang['public']['dir'],
				"PHP_CONFIG" => $this->_config,
				"start_time" => $this->start_time,
				"PHP_LANG" => $this->_lang,
				"blogs_list" => $this->_getBlogs(),
				"end_time" => $this->end_time
				)
			);

		$this->template_name = $this->_getTemplateFilename();

		$this->outputRss();

		if (TRUE == count($this->errors)) $this->logop->fetchMessage($this->errors, 1);

		$this->_destroyEnv();
	} 

	function _getPagesInfo()
	{
		$query_string = "select count(*) as counter from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where t2.id=t1.sort";
		if (FALSE == empty($this->current_sort)) $query_string .= " and t1.sort={$this->current_sort}";
		$query_string .= " limit " . (($this->counter["current_page"] - 1) * $this->counter["blog_perpage"]) . ", {$this->counter['blog_perpage']}";
		$this->_dbop->query($query_string);
		$blog_counter = $this->_dbop->fetchArray(0, 'ASSOC');
		$this->_dbop->freeResult();

		$total_pages = ceil($blog_counter['counter'] / intval($this->_config['global']['listblognum']));
		if (0 == $total_pages) $total_pages = 1;
		return array("pages" => $total_pages, "blogs" => $blog_counter['counter']);
	} 

	function _getBlogs()
	{
		$query_string = "select t1.*, t2.cnName from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where t1.top!='1' and t2.id=t1.sort";
		if (FALSE == empty($this->current_sort)) $query_string .= " and t1.sort={$this->current_sort}";
		$query_string .= " order by t1.id desc
			limit " . (($this->current_page - 1) * intval($this->_config['global']['listblognum'])) . ", " . intval($this->_config['global']['listblognum']);
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $result = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $result = array();
		$this->_dbop->freeResult();
		for ($i = 0; $i < count($result); $i++)
		if ('2.0' == $this->rss_version) $result[$i]['addtime'] = date("D, d M Y H:i:s T", $result[$i]['addtime']);
		else $result[$i]['addtime'] = date("Y-m-d\TH:i:s O", $result[$i]['addtime']);
		return $result;
	} 

	function outputRss()
	{
		if (TRUE == $this->output2file && FALSE == empty($this->output_filename))
		{
			$content = $this->_html->fetch($this->template_name);
			$file_id = @fopen($this->output_filename, "wb");
			@flock($file_id, LOCK_EX);
			@fwrite($file_id, $content);
			@flock($file_id, LOCK_UN);
			@fclose($file_id);
		} 
		else
		{
			header("Content-type:application/xml");
			$this->_html->display($this->template_name);
		} 
	} 

	function _getTemplateFilename()
	{
		switch ($this->rss_version)
		{
			case "2.0": $this->template_name = "rss_20.tpl";
				break;
			case "1.0": $this->template_name = "rss_10.tpl";
				break;
			case "0.91": $this->template_name = "rss_091.tpl";
				break;
			case "atom": $this->template_name = "atom.tpl";
				break;
			default: $this->template_name = "rss_20.tpl";
				break;
		} 
		return $this->template_name;
	} 
} 

new Rss();
?>