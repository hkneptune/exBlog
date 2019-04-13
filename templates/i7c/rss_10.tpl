<?xml version="1.0" encoding="{$PHP_LANG.public.charset}"?>
<?xml-stylesheet href="./images/rss.css" type="text/css"?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns="http://purl.org/rss/1.0/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:admin="http://webns.net/mvcb/"
	xmlns:cc="http://web.resource.org/cc/">
<channel rdf:about="{$PHP_CONFIG.global.siteUrl}">
 <title><![CDATA[{$PHP_CONFIG.global.siteName}]]></title>
 <link>{$PHP_CONFIG.global.siteUrl}</link>
 <description><![CDATA[{$PHP_CONFIG.global.Description}]]></description>
 <image rdf:resource="{$PHP_CONFIG.global.siteUrl}/images/icon_power.png" />
</channel>
<image rdf:about="{$PHP_CONFIG.global.siteUrl}/images/icon_power.png">
 <title><![CDATA[{$PHP_CONFIG.global.siteName}]]></title>
 <url>{$PHP_CONFIG.global.siteUrl}/images/icon_power.png</url>
</image>

{section name=blog_id loop=$blogs_list}
<item rdf:about="{$PHP_CONFIG.global.siteUrl}/index.php?id={$blogs_list[blog_id].id}">
<title><![CDATA[{$blogs_list[blog_id].title}]]></title>
<link>{$PHP_CONFIG.global.siteUrl}/index.php?id={$blogs_list[blog_id].id}</link>
<description><![CDATA[{$blogs_list[blog_id].content}]]></description>
<dc:date>{$blogs_list[blog_id].addtime}</dc:date>
</item>
{/section}

</rdf:RDF>