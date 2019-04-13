<?php
/**
 *	RSS1.0 Feed生成类
 *	输出rss feed内容,供相关工具察看rss用.
 *  useage:
 *		#1> include(rss.inc.php);
 *		#2> $rssFeed = new RssFeedBuild();
 *		#3> $rssFeed->setChannel("频道标题", "与频道相对应的网站URL", "与频道相对应的描述", "logo地址");
 *		#4> $rssFeed->setItem("文章标题", "与文章相对应的网站URL", "与文章相对应的描述", "文章发布时间");
 *		#5> $rssFeed->buildRssFeed($param);  //$param值为ECHO或WRITE, 为ECHO时,直接输出,否则写入rss.xml文件
 *	version: 1.0.0
 *  author: elliott [at] 2004-08-29
 * RSS1.0的RSS是 RDF Site Summary 的缩写
 * Madman( madcn.com )修改于 2004-10-26
 */

class RssFeedBuild
{
	var $rssHeader;
	var $rssChannel;
	var $rssItems;
	var $rssItem;

	/**
	 *	构造函数, 添加xml文件头, rss feed根元素
	 *	@param   null
	 *	@return  null
	 */
	function RssFeedBuild()
	{
		global $langpublic;
		$this->rssHeader  = "<?xml version=\"1.0\" encoding=\"$langpublic[charset]\"?>\n";
		$this->rssHeader .= "<?xml-stylesheet href=\"./images/rss.css\" type=\"text/css\"?>\n";
		$this->rssHeader .= "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n xmlns=\"http://purl.org/rss/1.0/\"\n xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"\n xmlns:admin=\"http://webns.net/mvcb/\"\n xmlns:cc=\"http://web.resource.org/cc/\">\n";
	}
	
	/**
	 *	设置频道相关内容
	 *	@param $title 频道的标题
	 *	@param $link  与频道对应的那个网站URL
	 *	@param $description 与频道相关的简单介绍
	 *	@return null
	 */
	function setChannel($title, $link, $description, $logourl)
	{
		$this->rssChannel  = "<channel rdf:about=\"$link\">\n";
		$this->rssChannel .= " <title><![CDATA[".$title."]]></title>\n";
		$this->rssChannel .= " <link>".$link."</link>\n";
		$this->rssChannel .= " <description><![CDATA[".$description."]]></description>\n";
		$this->rssChannel .= " <image rdf:resource=\"".$logourl."\" />\n";
		$this->rssChannel .= "</channel>\n";
		$this->rssChannel .= "<image rdf:about=\"".$logourl."\">\n";
		$this->rssChannel .= " <title><![CDATA[".$title."]]></title>\n";
		$this->rssChannel .= " <url>".$logourl."</url>\n";
		$this->rssChannel .= "</image>\n";
	}
	
	/**
	 *	设置一条新闻\文章记录
	 *	@param $title 新闻\文章的标题
	 *	@param $link 新闻\文章在网页上的URL	
	 *	@param $description 新闻\文章的一个简短描述
	 */
	function setItem($title, $link, $description, $modified)
	{
		$this->rssItem .= "<item rdf:about=\"$link\">\n";
		$this->rssItem .= "<title><![CDATA[".$title."]]></title>\n";
		$this->rssItem .= "<link>".$link."</link>\n";
		$this->rssItem .= "<description><![CDATA[".$description."]]></description>\n";
		$this->rssItem .= "<dc:date>".$modified."</dc:date>\n";
		$this->rssItem .= "</item>\n";
	}

	/**
	 *	以直接输出或写入的方式生成RSS Feed文件
	 *	@param ECHO 直接输出rss feed
	 *	@param WRITE 写入方式生成 rss feed, 生成文件名为 rss.xml
	 */
	function buildRssFeed($tag)
	{
		if($tag == "ECHO")
		{
			header("Content-type:application/xml");
			echo $this->rssHeader;
			echo $this->rssChannel;
			echo $this->rssItem;
			echo "</rdf:RDF>";
		}
		else if($tag == "WRITE")
		{
			$all  = $this->rssHeader;
			$all .= $this->rssChannel;
			$all .= $this->rssItem;
			$all .= "</rdf:RDF>";

			$fp = @fopen("rss.xml", w);
			@fputs($fp, $all);
		}
	}
}

?>