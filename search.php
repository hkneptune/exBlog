<?php
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

		// 组合POST和GET数据
		$this->_input = array_merge($_GET, $_POST);
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $_POST;

		if (FALSE == empty($_GET['page']) && TRUE == is_numeric($_GET['page'])) $this->current_page = intval($_GET['page']);

		$this->_initHtml();

		$this->printSearchResult();

		// 获取当前时间以微秒为单位的Unix时间戳
		$this->end_time = $this->microtime_float();

		$this->_html->assign("end_time", $this->end_time);
		$this->_html->display($this->template_name);

		exit();
	}

	function sqlConstructor()
	{
		$this->escapeSqlCharsFromData($this->_input);
		if (TRUE == empty($this->_input['keyword'])) $this->errors[] = $this->_lang['search'][0];
		$query_string = "select t1.*, t2.cnName from {$this->_dbop->prefix}blog as t1, {$this->_dbop->prefix}sort as t2 where ";

		// 如果没有打开高级模式，则不使用全文搜索
		if (TRUE == empty($this->_input['_POST']['adv'])) $query_string .= "t1.title LIKE '%{$this->_input['keyword']}%' or t1.content LIKE '%{$this->_input['keyword']}%'";
		$query_string .= " and t2.id=t1.sort";
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
		$result = array_unique($result);
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
			array(
				"blogs_list"		=> $search_records,
				"counter"		=> $counter,
				"PHP_INPUT"	=> $this->_input
			)
		);
		$this->template_name = "search_result_list.tpl";
	}
}

new SearchBall();
?>