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

define('MODULE_NAME', 'core-pub.search');

require_once("./public.php");

class SearchBall extends PublicCommon
{
	var $errors = array();
	var $_input = array();
	var $current_time = 0;
	var $start_time = 0.0;
	var $end_time = 0.0;
	var $current_page = 1;
	var $records_perpage = 6;

	function SearchBall()
	{
		$this->setModuleName(MODULE_NAME); 
		// 获取当前时间以微秒为单位的Unix时间戳
		$this->start_time = $this->microtime_float();
		$this->current_time = time(); 
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig(); 
		// 装入系统配置
		$this->_loadSystemOptions(); 
		// 装入语言文件
		include_once("./{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun); 
		// 装入功能对应语言文件
		include_once("./{$this->_config['global']['langURL']}/search.php");
		$this->_lang['search'] = $lang; 
		// 每页记录数
		$this->records_perpage = $this->_config['global']['listblognum']; 
		// 浏览次数统计
		$this->_counter();

		$this->_getVariables();

		if (FALSE == empty($this->_input['_GET']['page']) && TRUE == is_numeric($this->_input['_GET']['page'])) $this->current_page = intval($this->_input['_GET']['page']);

		$this->_initHtml();

		$this->printSearchResult(); 
		// 获取当前时间以微秒为单位的Unix时间戳
		$this->end_time = $this->microtime_float();

		$this->_html->assign("end_time", $this->end_time);
		$this->_html->display($this->template_name);

		if (TRUE == count($this->errors)) $this->logop->fetchMessage($this->errors, 1);

		$this->_destroyEnv();
	} 

	function sqlConstructor()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (TRUE == empty($this->_input['keyword'])) $this->errors[] = $this->_lang['search'][0];
		$query_string = "select t1.*, t2.cnName from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where "; 
		// 如果没有打开高级模式，则不使用全文搜索
		if (TRUE == empty($this->_input['_POST']['adv'])) $query_string .= "(t1.title LIKE '%{$this->_input['keyword']}%' or t1.content LIKE '%{$this->_input['keyword']}%')";
		else $query_string .= "t1.title LIKE '%{$this->_input['keyword']}%'";
		$query_string .= " and t2.id=t1.sort";
		if (TRUE == empty($this->_input['_SESSION']['userID'])) $query_string .= " and t1.hidden='0'";
		return $query_string;
	} 

	function getRecordsList()
	{
		$query_string = $this->sqlConstructor();
		$query_string .= " order by t1.id desc limit " . (($this->current_page - 1) * $this->records_perpage) . ", {$this->records_perpage}";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $result = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		else $result = array();
		$this->_dbop->freeResult();
		// $result = array_unique($result);
		return $result;
	} 

	function getCounterInfo($perpage)
	{
		$query_string = $this->sqlConstructor();
		$this->_dbop->query($query_string);
		$records_count = $this->_dbop->getNumRows();
		$this->_dbop->freeResult();
		$total_pages = ceil($records_count / $perpage);
		return array("pages" => $total_pages, "blogs" => $records_count);
	} 

	function printSearchResult()
	{
		$search_records = $this->getRecordsList();
		$counter = $this->getCounterInfo($this->records_perpage);
		$counter['blog_perpage'] = $this->records_perpage;
		$counter['current_page'] = $this->current_page;
		$this->_html->assign(
			array("blogs_list" => $search_records,
				"counter" => $counter,
				"PHP_INPUT" => $this->_input
				)
			);
		$this->template_name = "search_result_list.tpl";
	} 
} 

new SearchBall();
?>