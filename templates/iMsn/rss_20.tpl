<?xml version="1.0" encoding="{$PHP_LANG.public.charset}"?>
<?xml-stylesheet href="./images/rss2.css" type="text/css"?>
<rss version="2.0">
<channel>
 <title>{$PHP_CONFIG.global.siteName}</title>
 <link>{$PHP_CONFIG.global.siteUrl}</link>
 <description><![CDATA[{$PHP_CONFIG.global.Description}]]></description>
 <language>{$PHP_LANG.public.language}</language>
 <copyright><![CDATA[{$PHP_CONFIG.global.copyright}]]></copyright>
 <managingEditor>{$PHP_CONFIG.global.Webmaster}</managingEditor>
 <webMaster>{$PHP_CONFIG.global.Webmaster}</webMaster>
 <image>
 <url>{$PHP_CONFIG.global.siteUrl}/images/icon_power.png</url>
 <title>{$PHP_CONFIG.global.siteName}</title>
 <link>{$PHP_CONFIG.global.siteUrl}</link>
 </image>

{section name=blog_id loop=$blogs_list}
<item>
<title><![CDATA[{$blogs_list[blog_id].title}]]></title>
<link>{$PHP_CONFIG.global.siteUrl}/index.php?id={$blogs_list[blog_id].id}</link>
<author>{$blogs_list[blog_id].author} ({$blogs_list[blog_id].email})</author>
<category>{$blogs_list[blog_id].cnName}</category>
<description><![CDATA[{$blogs_list[blog_id].content}]]></description>
<pubDate>{$blogs_list[blog_id].addtime}</pubDate>
<guid>{$PHP_CONFIG.global.siteUrl}/index.php?id={$blogs_list[blog_id].id}</guid>
</item>
{/section}

</channel>
</rss>