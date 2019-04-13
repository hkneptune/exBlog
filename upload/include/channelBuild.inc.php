<?php
/**
 *	频道 生成类
 */

class RssFeedBuild
{
	var $rssHeader;
	var $rssChannel;
	var $rssItems;
	var $rssItem;

	/**
	 *	构造函数, 添加xml文件头
	 *	@param   null
	 *	@return  null
	 */
	function RssFeedBuild()
	{
		global $langpublic;
		$this->rssHeader  = "<?xml version=\"1.0\" encoding=\"$langpublic[charset]\"?>\n";
	}
	
	/**
	 *	设置频道相关内容
	 *	@param $title 频道的标题
	 *	@param $link  与频道对应的那个网站URL
	 *	@return null
	 */
	function setChannel($title, $link)
	{
		global $lang;
		$this->rssChannel = " <CHANNEL BASE=\"$link\" HREF=\"$link\"><TITLE>$title</TITLE>\n";
		$this->rssChannel .= " 	<SCHEDULE STARTDATE=\"2004-11-23\"><INTERVALTIME DAY=\"7\" /><EARLIESTTIME HOUR=\"0\" /><LATESTTIME HOUR=\"24\" /></SCHEDULE>\n";
		$this->rssChannel .= " 	<ITEM HREF=\"$link\"><TITLE>$lang[0]</TITLE></ITEM>\n";
	}
	
	/**
	 *	设置分类记录
	 *	@param $enName 分类英文名
	 *	@param $cnName 分类中文名
	 */
	function setItem($enName, $cnName)
	{
		$this->rssItem .= "	<ITEM HREF=\"/?play=view&sort=$enName\"><TITLE>$cnName</TITLE></ITEM>\n";
	}

	/**
	 *	以直接输出或写入的方式生成.cdf文件
	 *	@param ECHO 直接输出channel
	 *	@param WRITE 写入方式生成 channel, 生成文件名为 channel.cdf
	 */
	function buildRssFeed($tag)
	{
		if($tag == "ECHO")
		{
			header("Content-type:application/xml");
			echo $this->rssHeader;
			echo $this->rssChannel;
			echo $this->rssItem;
			echo "	<ITEM HREF=\"http://www.exBlog.net\"><TITLE>exBlog</TITLE></ITEM>\n";
			echo "</CHANNEL>";
		}
		else if($tag == "WRITE")
		{
			$all  = $this->rssHeader;
			$all .= $this->rssChannel;
			$all .= $this->rssItem;
			$all .= "	<ITEM HREF=\"http://www.exBlog.net\"><TITLE>exBlog</TITLE></ITEM>\n";
			$all .= "</CHANNEL>";
			$fp = @fopen("upload/channel.cdf", w);
			@fputs($fp, $all);

//	<script type="text/JavaScript">window.external.AddChannel("upload/channel.cdf");</script>

			header( "location: upload/channel.cdf" );die();
		}
	}
}

?>