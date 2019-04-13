<?php
/**
 *	RSS2.0 Feed生成类
 *	输出rss feed内容,供相关工具察看rss用.
 *  useage:
 *		#1> include(rss.inc.php);
 *		#2> $rssFeed = new RssFeedBuild();
 *		#3> $rssFeed->setChannel("频道标题", "与频道相对应的网站URL", "与频道相对应的描述", "频道管理员", "版权", "logo地址");
 *		#4> $rssFeed->setItem("文章标题", "与文章相对应的网站URL", "与文章相对应的描述", "文章发布时间");
 *		#5> $rssFeed->buildRssFeed($param);  //$param值为ECHO或WRITE, 为ECHO时,直接输出,否则写入rss.xml文件
 *	version: 1.0.0
 *  author: elliott [at] 2004-08-29
 * RSS2.0的RSS是 Really Simple Syndication 的缩写
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
		$this->rssHeader .= "<?xml-stylesheet href=\"./images/rss2.css\" type=\"text/css\"?>\n";
		$this->rssHeader .= "<rss version=\"2.0\">\n";
	}
	
	/**
	 *	设置频道相关内容
	 *	@param $title 频道的标题
	 *	@param $link  与频道对应的那个网站URL
	 *	@param $description 与频道相关的简单介绍
	 *	@return null
	 */
	function setChannel($title, $link, $description, $webmaster, $copyright, $logourl)
	{
         global $langpublic;
		$this->rssChannel  = "<channel>\n";
		$this->rssChannel .= " <title>".$title."</title>\n";			//频道名称
		$this->rssChannel .= " <link>".$link."</link>\n";				//频道的URL
		$this->rssChannel .= " <description><![CDATA[".$description."]]></description>\n";	//频道的描述
		$this->rssChannel .= " <language>".$langpublic[language]."</language>\n";
		$this->rssChannel .= " <copyright><![CDATA[".$copyright."]]></copyright>\n";		//频道内容的版权说明
		$this->rssChannel .= " <managingEditor>".$webmaster."</managingEditor>\n";	//责任编辑的email
		$this->rssChannel .= " <webMaster>".$webmaster."</webMaster>\n";		//负责频道技术事务的网站管理员email
		$this->rssChannel .= " <image>\n";							//频道图标
		$this->rssChannel .= " <url>".$logourl."</url>\n";
		$this->rssChannel .= " <title>".$title."</title>\n";
		$this->rssChannel .= " <link>".$link."</link>\n";
		$this->rssChannel .= " </image>\n";
	}
	
	/**
	 *	设置一条新闻\文章记录
	 *	@param $title 新闻\文章的标题
	 *	@param $link 新闻\文章在网页上的URL	
	 *	@param $description 新闻\文章的一个简短描述
	 */
	function setItem($title, $link, $description, $pubdate, $author, $sort)
	{
		$this->rssItem .= "<item>\n";
		$this->rssItem .= "<title>".$title."</title>\n";
		$this->rssItem .= "<link>".$link."</link>\n";
		$this->rssItem .= "<author>".$author."</author>\n";
		$this->rssItem .= "<category>".$sort."</category>\n";
		$this->rssItem .= "<description><![CDATA[".$description."]]></description>\n";
		$this->rssItem .= "<pubDate>".$pubdate."</pubDate>\n";		//发布日期，格式遵循RFC822格式
		$this->rssItem .= "<guid>".$link."</guid>\n";
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
			echo "</channel></rss>";
		}
		else if($tag == "WRITE")
		{
			$all  = $this->rssHeader;
			$all .= $this->rssChannel;
			$all .= $this->rssItem;
			$all .= "</channel></rss>";

			$fp = @fopen("rss2.xml", w);
			@fputs($fp, $all);
		}
	}
}

?>