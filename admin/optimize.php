<?php
require_once("./public.php");
class Optimize extends CommonLib
{
	var $table_names = array();
	var $tables = array();
	var $messages = array();
	var $query_strings = array();

	function Optimize()
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
		include_once("../{$this->_config['global']['langURL']}/optimize.php");
		$this->_lang['optimize'] = $lang;
		include_once("../{$this->_config['global']['langURL']}/blogadmin.php");
		$this->_lang['admin'] = $langadfun;

		// 用户验证
		if (FALSE == $this->checkUser(0)) $this->_showError($this->_lang['public'][21], "./login.php");

		$this->_getTables();
		$this->_printResultPage();
		exit();
	}

	function _printResultPage()
	{
		$this->printPageHeader();

		echo "
<table width=500 border=\"0\" cellpadding=0 cellspacing=0 class=\"border\">
  <tr bgcolor=\"#99CC66\"><td bgcolor=\"#FFFFFF\">
<center><font class=\"title\">" . sprintf($this->_lang['optimize'][0], $this->_dbop->dbname) . "</font></center><br>
<table border=0 align=\"center\" class=\"main\" width=95%>
  <tr>
    <td width=30%>{$this->_lang['optimize'][1]}</td>
    <td width=25% align=center>{$this->_lang['optimize'][2]}</td>
    <td width=25% align=center>{$this->_lang['optimize'][3]}</td>
    <td width=20% align=center>{$this->_lang['optimize'][4]}</td>
  </tr>
		";

		$total_bytes = 0;
		$total_gains = 0;
		for ($i = 0; $i < count($this->tables); $i++)
		{
			$this->_dbop->query($this->query_strings[$i]);
			$gain = intval($this->tables[$i]['Data_free']) / 1024;
			$total_size = round((intval($this->tables[$i]['Data_length']) + intval($this->tables[$i]['Index_length'])) / 1024, 3);
			echo "
  <tr>
    <td>{$this->tables[$i]['Name']}</td>
    <td align=center>$total_size KB</td>
    <td align=center>{$this->_lang['optimize'][5]}</td>
    <td align=center>$gain KB</td>
  </tr>
			";
			$total_bytes += $total_size;
			$total_gains += $gain;
		}

		echo "
  <tr>
    <td colspan=3 align=right>{$this->_lang['optimize'][10]}</td>
    <td align=center>$total_gains KB</td>
  </tr>
</table>
</center>
  </td></tr>
</table>
		";

		$this->printPageFooter();
	}

	function _getTables()
	{
		$this->_dbop->query("show table status from {$this->_dbop->dbname}");
		$result = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		if (FALSE == empty($result) && TRUE == is_array($result) && 0 < count($result))
		{
			for ($i = 0; $i < count($result); $i++)
			{
				if (TRUE == preg_match("/^" . preg_quote($this->_dbop->prefix) . "/", $result[$i]['Name']))
				{
					$this->query_strings[] = "optimize table {$result[$i]['Name']}";
					$this->tables[] = $result[$i];
				}
			}
		}
		else return FALSE;
		return TRUE;
	}
}
new Optimize();
?>