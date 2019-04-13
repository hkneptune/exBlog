<?php
/*=================================================================
*\   exBlog Ver: 1.3.final  exBlog 网络日志(PHP+MYSQL) 1.3.final 版
*\-----------------------------------------------------------------
*\   Copyright(C) exSoft Team, 2003 - 2005. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 数据库备分页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();
//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");

checkLogin();       ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass], 0);  ### 检查用户权限
		@set_time_limit(600);
		$crlf="\n";
		global $dbhost, $dbuname, $dbpass, $dbname;
		$dbhost=$exBlog['host'];
		$dbuname=$exBlog['user'];
		$dbpass=$exBlog['password'];
		$dbname=$exBlog['dbname'];
              $prefix=$exBlog['one'];
              $len=strpos($prefix,"_")+1;
		$time=date("Y-n-j");
              $backname=$time."_".$dbname;
		header("Content-disposition: filename=$backname.sql");
		header("Content-type: application/octetstream");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		// doing some DOS-CRLF magic...
		$client = $_SERVER["HTTP_USER_AGENT"];
		if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) 
		{
		$os = $regs[1];
		// this looks better under WinX
		if (eregi("Win",$os)) 
		    $crlf="\r\n";
		}
		
		
		function my_handler($sql_insert)
		{
		    global $crlf;
		    echo "$sql_insert;$crlf";
		}
		
		// Get the content of $table as a series of INSERT statements.
		// After every row, a custom callback function $handler gets called.
		// $handler must accept one parameter ($sql_insert);
		function get_table_content($db, $table, $handler)
		{
		    $result = mysql_db_query($db, "SELECT * FROM $table") or mysql_die();
		    $i = 0;
		    while($row = mysql_fetch_row($result))
		    {
		//        set_time_limit(60); // HaRa
		        $table_list = "(";
		
		        for($j=0; $j<mysql_num_fields($result);$j++)
		            $table_list .= mysql_field_name($result,$j).", ";
		
		        $table_list = substr($table_list,0,-2);
		        $table_list .= ")";
		
		        if(isset($GLOBALS["showcolumns"]))
		            $schema_insert = "INSERT INTO $table $table_list VALUES (";
		        else
		            $schema_insert = "INSERT INTO $table VALUES (";
		
		        for($j=0; $j<mysql_num_fields($result);$j++)
		        {
		            if(!isset($row[$j]))
		                $schema_insert .= " NULL,";
		            elseif($row[$j] != "")
		                $schema_insert .= " '".addslashes($row[$j])."',";
		            else
		                $schema_insert .= " '',";
		        }
		        $schema_insert = ereg_replace(",$", "", $schema_insert);
		        $schema_insert .= ")";
		        $handler(trim($schema_insert));
		        $i++;
		    }
		    return (true);
		}
		
		// Return $table's CREATE definition
		// Returns a string containing the CREATE statement on success
		function get_table_def($db, $table, $crlf)
		{
		    $schema_create = "";
		    //$schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
		    $schema_create .= "CREATE TABLE $table ($crlf";
		
		    $result = mysql_db_query($db, "SHOW FIELDS FROM $table") or mysql_die();
		    while($row = mysql_fetch_array($result))
		    {
		        $schema_create .= "   $row[Field] $row[Type]";
		
		        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
		            $schema_create .= " DEFAULT '$row[Default]'";
		        if($row["Null"] != "YES")
		            $schema_create .= " NOT NULL";
		        if($row["Extra"] != "")
		            $schema_create .= " $row[Extra]";
		        $schema_create .= ",$crlf";
		    }
		    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
		    $result = mysql_db_query($db, "SHOW KEYS FROM $table") or mysql_die();
		    while($row = mysql_fetch_array($result))
		    {
		        $kname=$row['Key_name'];
		        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
		            $kname="UNIQUE|$kname";
		         if(!isset($index[$kname]))
		             $index[$kname] = array();
		         $index[$kname][] = $row['Column_name'];
		    }
		
		    while(list($x, $columns) = @each($index))
		    {
		         $schema_create .= ",$crlf";
		         if($x == "PRIMARY")
		             $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
		         elseif (substr($x,0,6) == "UNIQUE")
		            $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
		         else
		            $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
		    }
		
		    $schema_create .= "$crlf)";
		    return (stripslashes($schema_create));
		}
		
		function mysql_die($error = "")
		{
		    echo "<b> $strError </b><p>";
		    if(isset($sql_query) && !empty($sql_query))
		    {
		        echo "$strSQLQuery: <pre>$sql_query</pre><p>";
		    }
		    if(empty($error))
		        echo $strMySQLSaid.mysql_error();
		    else
		        echo $strMySQLSaid.$error;
		    echo "<br><a href=\"javascript:history.go(-1)\">$strBack</a>";
		    exit;
		}
		

		mysql_pconnect($dbhost, $dbuname, $dbpass);
		@mysql_select_db("$dbname") or die ("Unable to select database");
		
		$tables = mysql_list_tables($dbname);
		
		$num_tables = @mysql_numrows($tables);
		if($num_tables == 0)
		{
		    echo $strNoTablesFound;
		}
		else
		{
		    $i = 0;
		    $heure_jour = date ("H:i");
		    print "# ========================================================$crlf";
		    print "#$crlf";
		    print "#$heure_jour:$dbname$crlf";
		    print "#$crlf";
		    print "# ========================================================$crlf";
		    print "$crlf";
		    
		    while($i < $num_tables)
		    { 
		        $table = mysql_tablename($tables, $i);
			 $subtable = substr($table,0,$len);
			if($subtable==$prefix)
			{
		        print $crlf;
		        print "# --------------------------------------------------------$crlf";
		        print "#$crlf";
		        print "# $strTableStructure '$table'$crlf";
		        print "#$crlf";
		        print $crlf;
		
		        echo get_table_def($dbname, $table, $crlf).";$crlf$crlf";
		        
			print "#$crlf";
			print "# $strDumpingData '$table'$crlf";
			print "#$crlf";
			print $crlf;
			
			get_table_content($dbname, $table, "my_handler");
		
		        $i++;
			}
			else
			{
			$i++;
			continue;
			}
		    }
		}
?>
