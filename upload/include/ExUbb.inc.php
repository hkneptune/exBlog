<?
/**
 *	UBB类
 *	
 *  useage:
 *		$str = "[url]1.swf[/url]";
 *		$exubb = new ExUbb();
 *		$exubb->setString($str);    //设置待分析的字符串
 *		$str2 = $exubb->parse();	//用默认设置开始分析,结果存放在 $str2 中
 *		echo $str2;
 *		  
 *	version: 1.0.0
 *  author: elliott [at] 2004-08-30
 */
class ExUbb
{	
	var $str;
	var $pattern = array();    //存放UBB标签的字符数组
	var $replace = array();    //存放代转换的字符数组

	var $parseFont_ = true;		//分析字体类格式
	var $parseLink_ = true;		//分析链接类格式
	var $parseCode_ = true;		//分析代码类格式
	var $parseMedia_ = true;		//分析媒体类格式
	
	/**
	 *	设置待分析的字符串
	 *	@param $str 待分析的字符串
	 *	@return null
	 */
	function setString($str)
	{
		$this->str = $str;
	}
	
	/**
	 *	设置是否分析字体格式
	 *	@param $tag 为 true 时分析字体格式,为 false 时不分析
	 *	@return null
	 */
	function setParseFont($tag)
	{
		$this->parseFont_ = $tag;
	}

	/**
	 *	设置是否分析链接类格式
	 *	@param $tag 为 true 时分析链接类格式,为 false 时不分析
	 *	@return null
	 */
	function setParseLink($tag)
	{
		$this->parseLink_ = $tag;
	}

	/**
	 *	设置是否分析代码类格式
	 *	@param $tag 为 true 时分析代码类格式,为 false 时不分析
	 *	@return null
	 */
	function setParseCode($tag)
	{
		$this->parseCode_ = $tag;
	}

	/**
	 *	设置是否分析媒体类格式
	 *	@param $tag 为 true 时分析媒体类格式,为 false 时不分析
	 *	@return null
	 */	
	function setParseMedia($tag)
	{
		$this->parseMedia_ = $tag;
	}

	/**
	 *	设置分析本类支持的全部UBB格式
	 *	@param null
	 *	@return null
	 */
	function setParseAll()
	{
		$this->parseFont_ = true;
		$this->parseLink_ = true;
		$this->parseCode_ = true;
		$this->parseMedia_ = true;
	}

	/**
	 *	分析字体类标签,如字体大小,颜色,移动等.
	 *	@param null
	 *	@return null
	 */
	function parseFont()
	{
		$pattern = array(
					     '/\[b\]\s*(.+?)\[\/b\]/is',
					     '/\[u\]\s*(.+?)\[\/u\]/is',
					     '/\[i\]\s*(.+?)\[\/i\]/is',
					     '/\[s\]\s*(.+?)\[\/s\]/is',
						 '/\[size=\s*(.+?)\]\s*(.+?)\[\/size\]/is',
						 '/\[color=\s*(.+?)\]\s*(.+?)\[\/color]/is',
						 '/\[font=\s*(.+?)\]\s*(.+?)\[\/font\]/is',
					     '/\[fly\]\s*(.+?)\[\/fly\]/is',
						 '/\[sub\]\s*(.+?)\[\/sub\]/is',
						 '/\[sup\]\s*(.+?)\[\/sup\]/is',
						 '/\[left\]\s*(.+?)\[\/left\]/is',
						 '/\[center\]\s*(.+?)\[\/center\]/is',
						 '/\[right\]\s*(.+?)\[\/right\]/is'
			            );
		$this->pattern = array_merge($this->pattern, $pattern);

		$replace = array(
					     '<strong>\\1</strong>',
					     '<u>\\1</u>',
					     '<em>\\1</em>',
					     '<s>\\1</s>',
						 '<font size="\\1">\\2</font>',
					     '<font color=\\1>\\2</font>',
						 '<font face=\\1>\\2</font>',
						 '<MARQUEE>\\1</MARQUEE>',
						 '<sub>\\1</sub>',
						 '<sup>\\1</sup>',
						 '<div align="left">\\1</div>',
						 '<div align="center">\\1</div>',
						 '<div align="right">\\1</div>',
		                );
		$this->replace = array_merge($this->replace, $replace);
	}

	/**
	 *	分析代码类标签,如引用,例子等.
	 *	@param null
	 *	@return null
	 */
	function parseCode()
	{
		$pattern = array(
						 '/\[hr\]/is',
					     '/\[quote\]\s*(.+?)\[\/quote\]/is',
					     '/\[code\]\s*(.+?)\[\/code\]/is',
			            );
		$this->pattern = array_merge($this->pattern, $pattern);

		$replace = array(
						 '<hr size="1">',
						 '<p>&nbsp;&nbsp;引用:<hr size="1">\\1<hr size="1"></p>',
						 '<p><table border="0" width="97%" cellspacing="0" cellpadding="0"><tr><td>&nbsp;&nbsp;代码:</td></tr><tr><td><table border="0" width="100%" cellspacing="1" cellpadding="10" bgcolor="#000000"><tr><td align="left" width="100%" bgcolor="#e6e6e6"><font face="Courier New">\\1</font></td></tr></table></td></tr></table></p>',
			            );
		$this->replace = array_merge($this->replace, $replace);
	}

	/**
	 *	分析连接类标签,如URL,EMAIL,图像等
	 *	@param null
	 *	@return null
	 */
	function parseLink()
	{
		$pattern = array(
					     '/\[url\]\s*(.+?)\[\/url\]/is',
						 '/\[url=\s*(.+?)\]\s*(.+?)\[\/url\]/is',
					     '/\[email\]\s*(.+?)\[\/email\]/is',
						 '/\[email=\s*(.+?)\]\s*(.+?)\[\/email\]/is',
						 '/\[img\]\s*(.+?)\[\/img\]/is',
						 '/\[limg\]\s*(.+?)\[\/limg\]/is',
						 '/\[cimg\]\s*(.+?)\[\/cimg\]/is',
						 '/\[rimg\]\s*(.+?)\[\/rimg\]/is'
			            );
		$this->pattern = array_merge($this->pattern, $pattern);

		$replace = array(
						 '<a href="\\1" target="_blank">\\1</a>',
						 '<a href="\\1" target="_blank">\\2</a>',
						 '<a href="mailto:\\1">\\1</a>',
						 '<a href="mailto:\\1">\\2</a>',
						 '<img src="\\1">',
						 '<img src="\\1" align="left">',
						 '<div align="center"><img src="\\1"></div>',
						 '<img src="\\1" align="right">',
						);
		$this->replace = array_merge($this->replace, $replace);
	}

	/**
	 *	分析媒体类标签,如flash
	 *	@param null
	 *	@return null
	 */
	function parseMedia()
	{
		$pattern = array(
						 '/\[swf\]\s*(.+?)\[\/swf\]/is',
						 '/\[swf=(\d+),\s*(\d+)\]\s*(.+?)\[\/swf\]/is',
			            );
		$this->pattern = array_merge($this->pattern, $pattern);

		$replace = array(
						 '<embed src="\\1" type="application/x-shockwave-flash" PLAY="1"></embed>',
					     '<embed width="\\1" height="\\2" src="\\3" type="application/x-shockwave-flash" PLAY="1"></embed>',
						);
		$this->replace = array_merge($this->replace, $replace);
	}

	/**
	 *	分析UBB类格式	
	 *	@param null
	 *	@return $str 分析完后的UBB格式
	 */
	function parse()
	{
		//print_r($this->pattern);
		//print_r($this->replace);

		//echo $this->str;

		if($this->parseFont_)
			$this->parseFont();
		if($this->parseLink_)
			$this->parseLink();
		if($this->parseCode_)
			$this->parseCode();
		if($this->parseMedia_)
			$this->parseMedia();
		
		$this->str = str_replace("\n", "<br>", $this->str);
		$this->str = preg_replace($this->pattern, $this->replace, $this->str);
		return $this->str;
	}
}

//example:

/*
$str = "http://elliott.126.com";
$exubb = new ExUbb();
$exubb->setString($str);
$str2 = $exubb->parse();
echo $str2;
*/

?>