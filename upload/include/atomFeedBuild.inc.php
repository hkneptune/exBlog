<?php
/**
 *	ATOM Feed生成类
 *	输出atom feed内容,供相关工具察看atom用.
 *  useage:
 *		#1> include(rss.inc.php);
 *		#2> $rssFeed = new RssFeedBuild();
 *		#3> $rssFeed->setChannel("频道标题", "与频道相对应的网站URL", "与频道相对应的描述");
 *		#4> $rssFeed->setItem("文章标题", "与文章相对应的网站URL", "与文章相对应的描述", "文章发布时间", "文章发布时间", "文章作者");
 *		#5> $rssFeed->buildRssFeed($param);  //$param值为ECHO或WRITE, 为ECHO时,直接输出,否则写入rss.xml文件
 *	version: 1.0.0
 *  author: elliott [at] 2004-08-29
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
		$this->rssHeader  = "<?xml version=\"1.0\" encoding=\"GB2312\" standalone=\"yes\"?>\n";
		$this->rssHeader .= "<?xml-stylesheet href=\"http://www.madcn.com/blog/images/atom.css\" type=\"text/css\"?>\n";
		$this->rssHeader .= "<feed version=\"0.3\" xmlns=\"http://purl.org/atom/ns#\">\n";
	}
	
	/**
	 *	设置频道相关内容
	 *	@param $title 频道的标题
	 *	@param $link  与频道对应的那个网站URL
	 *	@param $description 与频道相关的简单介绍
	 *	@return null
	 */
	function setChannel($title, $link, $description)
	{
		$this->rssChannel = " <title mode=\"escaped\" type=\"text/html\"><![CDATA[".$title."]]></title>\n";
		$this->rssChannel .= " <tagline mode=\"escaped\" type=\"text/html\"><![CDATA[".$description."]]></tagline>\n";
		$this->rssChannel .= " <link href=\"$link\" rel=\"alternate\" title=\"$title\" type=\"text/html\"/>\n";
		$this->rssChannel .= " <generator url=\"http://www.madcn.com/blog/\">MadBLOG</generator>\n";
		$this->rssChannel .= " <info mode=\"xml\" type=\"text/html\">\n";
		$this->rssChannel .= " <div xmlns=\"http://www.w3.org/1999/xhtml\">This is an Atom formatted XML site feed. It is intended to be viewed in a Newsreader or syndicated to another site. Please visit the <a href=\"http://www.madcn.com\">Madman's Blog</a> for more info.</div>\n";
		$this->rssChannel .= "</info>\n";
	}
	
	/**
	 *	设置一条新闻\文章记录
	 *	@param $title 新闻\文章的标题
	 *	@param $link 新闻\文章在网页上的URL	
	 *	@param $description 新闻\文章的一个简短描述
	 */
	function setItem($id, $title, $link, $description, $modified, $issued, $author)
	{
		$this->rssItem .= "<entry>\n";
		$this->rssItem .= "<title><![CDATA[".$title."]]></title>\n";
		$this->rssItem .= "<link rel=\"alternate\" type=\"text/html\" href=\"$link\" />\n";
		$this->rssItem .= "<author>\n<name>$author</name>\n</author>\n";
		$this->rssItem .= "<modified>\n$modified\n</modified>\n";
		$this->rssItem .= "<issued>\n$issued\n</issued>\n";
		$this->rssItem .= "<content type=\"application/xhtml+xml\" xml:lang=\"en-EN\" xml:space=\"preserve\"><div xmlns=\"http://www.w3.org/1999/xhtml\"><![CDATA[".$description."]]></div></content>\n";
		$this->rssItem .= "</entry>\n";
		$this->rssItem .= "<id>\n$id\n</id>\n";
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
			echo "</feed>";
		}
		else if($tag == "WRITE")
		{
			$all  = $this->rssHeader;
			$all .= $this->rssChannel;
			$all .= $this->rssItem;
			$all .= "</feed>";

			$fp = @fopen("atom.xml", w);
			@fputs($fp, $all);
		}
	}
}

?>