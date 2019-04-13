<?php

class Chinese
{
	var $unicode_table = array();
	var $SourceText = "";
	var $config  =  array(
		'codetable_dir'         => "./codetable/",           //  存放各种语言互换表的目录
		'SourceLang'            => 'UTF-8',                    //  字符的原编码
		'TargetLang'            => 'GB2312',                    //  转换后的编码
		'GBtoUnicode_table'     => 'gb-unicode.table',    //  简体中文转换为UNICODE的对照表
	);
	function Chinese( $SourceLang , $TargetLang , $SourceString='')
	{
		if ($SourceLang != '') {
		    $this->config['SourceLang'] = $SourceLang;
		}

		if ($TargetLang != '') {
		    $this->config['TargetLang'] = $TargetLang;
		}

		if ($SourceString != '') {
		    $this->SourceText = $SourceString;
		}

		$this->OpenTable();
	} // 结束 Chinese 的悉构函数
	function _hex2bin( $hexdata )
	{
		for ( $i=0; $i<strlen($hexdata); $i+=2 )
			$bindata.=chr(hexdec(substr($hexdata,$i,2)));

		return $bindata;
	}
	function OpenTable()
	{			
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
	} // 结束 OpenTable 函数
	function OpenFile( $position , $isHTML=false )
	{
	    $tempcontent = @file($position);

		if (!$tempcontent) {
		    echo "打开文件失败！";
			exit;
		}

		$this->SourceText = implode("",$tempcontent);

		if ($isHTML) {
			$this->SourceText = eregi_replace( "charset=".$this->config['SourceLang'] , "charset=".$this->config['TargetLang'] , $this->SourceText);

			$this->SourceText = eregi_replace("\n", "", $this->SourceText);

			$this->SourceText = eregi_replace("\r", "", $this->SourceText);
		}
	} // 结束 OpenFile 函数
	function SiteOpen( $position )
	{
	    $tempcontent = @file($position);

		if (!$tempcontent) {
		    echo "打开文件失败！";
			exit;
		}

		// 将数组的所有内容转换为字符串
		$this->SourceText = implode("",$tempcontent);

		$this->SourceText = eregi_replace( "charset=".$this->config['SourceLang'] , "charset=".$this->config['TargetLang'] , $this->SourceText);
	} // 结束 OpenFile 函数
	function setvar( $parameter , $value )
	{
		if(!trim($parameter))
			return $parameter;

		$this->config[$parameter] = $value;

	} // 结束 setvar 函数
	/**
	 * 简体、繁体中文 <-> UTF8 互相转换的函数
	 *
	 * 详细说明
	 * @形参      
	 * @起始      1.1
	 * @最后修改  1.5
	 * @访问      内部
	 * @返回      字符串
	 * @throws    
	 */
	function CHStoUTF8(){
			$out = "";
			$len = strlen($this->SourceText);
			$i = 0;
			while($i < $len) {
				$c = ord( substr( $this->SourceText, $i++, 1 ) );
				switch($c >> 4)
				{ 
					case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
						// 0xxxxxxx
						$out .= substr( $this->SourceText, $i-1, 1 );
					break;
					case 12: case 13:
						// 110x xxxx   10xx xxxx
						$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char3 = $this->unicode_table[(($c & 0x1F) << 6) | ($char2 & 0x3F)];

						if ($this->config["TargetLang"]=="GB2312")
							$out .= $this->_hex2bin( dechex(  $char3 + 0x8080 ) );

						if ($this->config["TargetLang"]=="BIG5")
							$out .= $this->_hex2bin( $char3 );
					break;
					case 14:
						// 1110 xxxx  10xx xxxx  10xx xxxx
						$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char3 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char4 = $this->unicode_table[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];

						if ($this->config["TargetLang"]=="GB2312")
							$out .= $this->_hex2bin( dechex ( $char4 + 0x8080 ) );

						if ($this->config["TargetLang"]=="BIG5")
							$out .= $this->_hex2bin( $char4 );
					break;
				}
			}

			// 返回结果
			return $out;
	} // 结束 CHStoUTF8 函数
	function ConvertIT()
	{
		return $this->CHStoUTF8();
	} // 结束 ConvertIT 函数

} // 结束类库

?>
