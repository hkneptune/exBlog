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

define('MODULE_NAME', 'core-pub.trackback');

require_once('./public.php');
class TrackBackRecieve extends PublicCommon
{
	var $errors = array();
	var $notices = array();
	var $_input = array();

	function TrackBackRecieve()
	{
		$this->setModuleName(MODULE_NAME); 
		// 装入数据库配置并实例化
		$this->_loadDatabaseConfig(); 
		// 装入系统配置
		$this->_loadSystemOptions(); 
		// 装入语言文件
		include_once("./{$this->_config['global']['langURL']}/public.php");
		$this->_lang['public'] = $langpublic;
		$this->_lang['public'] = array_merge($this->_lang['public'], $langpubfun); 
		// 装入功能对应语言文件
		include_once("./{$this->_config['global']['langURL']}/trackback.php");
		$this->_lang['trackback'] = $lang;

		$this->_getVariables();
		foreach($this->_input['_POST'] as $key => $value) $this->_input['_POST'][$key] = urldecode($value);

		$this->addTrackback();
		$this->printOutResult();

		if (TRUE == count($this->errors)) $this->logop->fetchMessage($this->errors, 1);

		$this->_destroyEnv();
	} 

	function _checkData()
	{ 
		// 目标Blog判断
		if (TRUE == empty($this->_input['_GET']['id']) || FALSE == is_numeric($this->_input['_GET']['id'])) $this->errors[] = $this->_lang['trackback'][0];
		else
		{
			$this->_input['_GET']['id'] = intval($this->_input['_GET']['id']); 
			// 检查指定的目标Blog是否存在
			$query_string = "select * from {$this->_dbop->prefix}blog where id={$this->_input['_GET']['id']}";
			$this->_dbop->query($query_string);
			if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['trackback'][5];
			$this->_dbop->freeResult();
		} 
		// Trackback 标题判断
		if (TRUE == empty($this->_input['_POST']['title'])) $this->errors[] = $this->_lang['trackback'][1];
		else $this->_input['_POST']['title'] = strip_tags($this->_input['_POST']['title']); 
		// Trackback 内容判断
		if (TRUE == empty($this->_input['_POST']['excerpt'])) $this->errors[] = $this->_lang['trackback'][2]; 
		// Trackback 来源Blog名称判断
		if (TRUE == empty($this->_input['_POST']['blog_name'])) $this->errors[] = $this->_lang['trackback'][3]; 
		// Trackback 来源Blog地址判断
		if (TRUE == empty($this->_input['_POST']['url']) || FALSE == preg_match('/^http:\/\//i', $this->_input['_POST']['url'])) $this->errors[] = $this->_lang['trackback'][4];
	} 

	function addTrackback()
	{
		$this->_checkData();
		if (FALSE == count($this->errors))
		{ 
			// 检查来自同源、同名的trackback是否存在
			$query_string = "select * from {$this->_dbop->prefix}trackback where TrackbackID={$this->_input['_GET']['id']} and url='{$this->_input['_POST']['url']}' and blog_name='{$this->_input['_POST']['blog_name']}' and title='{$this->_input['_POST']['title']}'";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['trackback'][6];
			$this->_dbop->freeResult();
		} 

		if (FALSE == count($this->errors))
		{ 
			// 添加Trackback记录
			$query_string = "insert into {$this->_dbop->prefix}trackback (
				TrackbackID, url, blog_name, title, excerpt, addtime, user_agent, user_referer
				) values (
				{$this->_input['_GET']['id']}, '{$this->_input['_POST']['url']}', '{$this->_input['_POST']['blog_name']}',
				'{$this->_input['_POST']['title']}', '{$this->_input['_POST']['excerpt']}', '" . time() . "', '" . $_SERVER['HTTP_USER_AGENT'] . "', '" . $_SERVER['HTTP_REFERER'] . "'
				)";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult(); 
			// 更新Trackback统计
			$query_string = "update {$this->_dbop->prefix}global set trackbackCount=(select count(*) from {$this->_dbop->prefix}trackback);;update {$this->_dbop->prefix}blog set trackbackCount=(select count(*) from {$this->_dbop->prefix}trackback where TrackbackID={$this->_input['_GET']['id']}) where id={$this->_input['_GET']['id']}";
			$this->_dbop->query($query_string);
			$this->_dbop->freeResult();

			$this->notices[] = $this->_lang['trackback'][7];
		} 
	} 

	function printOutResult()
	{
		header("Content-type:application/xml");
		echo "<?xml version=\"1.0\" encoding=\"{$this->_lang['public']['charset']}\"?" . ">";
		echo '<response>';
		echo '<error>' . count($this->errors) . '</error>';
		echo '<messages>';
		for ($i = 0; $i < count($this->errors); $i++) echo "<message>{$this->errors[$i]}</message>";
		for ($i = 0; $i < count($this->notices); $i++) echo "<message>{$this->notices[$i]}</message>";
		echo '</messages>';
		echo '</response>';
	} 
} 
new TrackBackRecieve();
?>