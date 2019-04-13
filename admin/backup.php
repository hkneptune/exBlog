<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Backup the database for exBlog
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

define('MODULE_NAME', 'core-adm.backup');

require_once("./public.php");

class Backup extends CommonLib
{
	var $current_time = "";
	var $tables = array();
	var $linefeed = "\n";

	function Backup()
	{
		$this->_initEnv(MODULE_NAME, '', 0);
		$this->setModuleName(MODULE_NAME);

		@set_time_limit(600);
		$this->current_time = date("Y-m-d H:i:s O");
		$client = $_SERVER["HTTP_USER_AGENT"];
		if (TRUE == ereg('[^(]*\((.*)\)[^)]*', $client, $regs))
		{
			$os = $regs[1];
			if (TRUE == eregi("Win", $os)) $this->linefeed = "\r\n";
		} 
		$this->_getTableList();

		header("Content-disposition: filename={$this->current_time}_{$this->_dbop->dbname}.sql");
		header("Content-type: application/octetstream");
		header("Pragma: no-cache");
		header("Expires: 0");
		print $this->_getSqlSentences();
		$this->_destroyEnv();
	} 

	function _getSqlSentences()
	{
		$sql_sentences = "# ========================================================{$this->linefeed}";
		$sql_sentences .= "#{$this->linefeed}";
		$sql_sentences .= "# Dumping Data for database {$this->_dbop->dbname}{$this->linefeed}";
		$sql_sentences .= "# Date: {$this->current_time}{$this->linefeed}";
		$sql_sentences .= "#{$this->linefeed}";
		$sql_sentences .= "# ========================================================{$this->linefeed}";
		$sql_sentences .= "{$this->linefeed}";
		for ($i = 0; $i < count($this->tables); $i++)
		{ 
			// Structure
			$sql_sentences .= "{$this->linefeed}";
			$sql_sentences .= "# --------------------------------------------------------{$this->linefeed}";
			$sql_sentences .= "#{$this->linefeed}";
			$sql_sentences .= "# Structure of '{$this->tables[$i]}'{$this->linefeed}";
			$sql_sentences .= "#{$this->linefeed}";
			$tmp = $this->_getTableDefinition($this->tables[$i]);
			if (FALSE == empty($tmp)) $sql_sentences .= $tmp; 
			// Data
			$sql_sentences .= "{$this->linefeed}";
			$sql_sentences .= "# --------------------------------------------------------{$this->linefeed}";
			$sql_sentences .= "#{$this->linefeed}";
			$sql_sentences .= "# Dumping Data of '{$this->tables[$i]}'{$this->linefeed}";
			$sql_sentences .= "#{$this->linefeed}";
			$tmp = $this->_getTableContent($this->tables[$i]);
			if (FALSE == empty($tmp)) $sql_sentences .= $tmp;
		} 
		$sql_sentences .= "{$this->linefeed}";
		return $sql_sentences;
	} 

	function _getTableDefinition($table_name)
	{
		$schema_string = "CREATE TABLE $table_name ({$this->linefeed}";
		$this->_dbop->query("show fields from $table_name");
		$result = $this->_dbop->fetchArrayBat(0, "ASSOC");
		$this->_dbop->freeResult();
		if (FALSE == empty($result) && 0 < count($result))
		{
			for ($i = 0; $i < count($result); $i++)
			{
				$schema_string .= " {$result[$i]['Field']} {$result[$i]['Type']}";
				if (TRUE == isset($result[$i]['Default']) && FALSE == empty($result[$i]['Default'])) $schema_string .= " DEFAULT {$result[$i]['Default']}";
				if ('YES' != $result[$i]['Null']) $schema_string .= " NOT NULL";
				if ('' != $result[$i]['Extra']) $schema_string .= " {$result[$i]['Extra']}";
				$schema_string .= ",{$this->linefeed}";
			} 

			$schema_string = ereg_replace("," . $this->linefeed . "$", "", $schema_string);
			$this->_dbop->query("show keys from $table_name");
			$result1 = $this->_dbop->fetchArrayBat(0, "ASSOC");
			$this->_dbop->freeResult();
			if (FALSE == empty($result1) && 0 < count($result1))
			{
				for ($i = 0; $i < count($result1); $i++)
				{
					$kname = $result1[$i]['Key_name'];
					if (("PRIMARY" != $kname) && (0 == $result1[$i]['Non_unique'])) $kname = "UNIQUE|$kname";
					if (FALSE == isset($index[$kname])) $index[$kname] = array();
					$index[$kname][] = $result1[$i]['Column_name'];
				} 
				while (list($x, $columns) = @each($index))
				{
					$schema_string .= ",{$this->linefeed}";
					if ("PRIMARY" == $x) $schema_string .= " PRIMARY KEY (" . implode($columns, ", ") . ")";
					elseif ("UNIQUE" == substr($x, 0, 6)) $schema_string .= " UNIQUE " . substr($x, 7) . " (" . implode($columns, ", ") . ")";
					else $schema_string .= " KEY $x (" . implode($columns, ", ") . ")";
				} 
			} 
			// else $schema_string = substr($schema_string, 0, -1);
			$schema_string .= "{$this->linefeed})";
			return $schema_string;
		} 
		else return FALSE;
	} 

	function _getTableContent($table_name)
	{
		$this->_dbop->query("select * from $table_name");
		$result = $this->_dbop->fetchArrayBat(0, "ASSOC");
		$this->_dbop->freeResult();
		$schema_string = "";
		if (FALSE == empty($result) && 0 < count($result))
		{
			for ($i = 0; $i < count($result); $i++)
			{
				$fields_list = "";
				$values_list = "";
				foreach ($result[$i] as $key => $value)
				{
					$fields_list .= "{$key}, ";
					$values_list .= "'" . $this->escapeSqlCharsFromData($value) . "', ";
				} 
				if (TRUE == strlen($fields_list)) $fields_list = substr($fields_list, 0, -2);
				if (TRUE == strlen($values_list)) $values_list = substr($values_list, 0, -2);
				if (TRUE == strlen($values_list)) $schema_string .= "INSERT INTO $table_name ($fields_list) VALUES ($values_list);{$this->linefeed}";
			} 
		} 
		else return FALSE;
		if (FALSE == empty($schema_string)) return $schema_string;
		else return FALSE;
	} 

	function _getTableList()
	{
		$this->_dbop->query("show tables from {$this->_dbop->dbname}");
		$result = $this->_dbop->fetchArrayBat(0, "NUM");
		$this->_dbop->freeResult();
		if (FALSE == empty($result) && TRUE == is_array($result) && 0 < count($result))
		{
			for ($i = 0; $i < count($result); $i++)
			{
				if (TRUE == preg_match("/^" . preg_quote($this->_dbop->prefix) . "/", $result[$i][0])) $this->tables[] = $result[$i][0];
			} 
		} 
		else return FALSE;
		return $this->tables;
	} 
} 

new Backup();

?>
