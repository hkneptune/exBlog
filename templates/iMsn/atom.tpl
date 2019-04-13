<?xml version="1.0" encoding="{$PHP_LANG.public.charset}" standalone="yes"?>
<?xml-stylesheet href="./images/atom.css" type="text/css"?>
<feed version="0.3" xmlns="http://purl.org/atom/ns#">
  <title mode="escaped" type="text/html">{$PHP_CONFIG.global.siteName}</title>
  <tagline mode="escaped" type="text/html"><![CDATA[{$PHP_CONFIG.global.Description}]]></tagline>
  <link href="{$PHP_CONFIG.global.siteUrl}" rel="alternate" title="{$PHP_CONFIG.global.siteName}" type="text/html" />
  <author>
  <name>{$PHP_CONFIG.global.Webmaster}</name>
  <email>{$PHP_CONFIG.global.Webmaster}</email>

  <url>{$PHP_CONFIG.global.siteUrl}</url>
  </author>
  <id>{$PHP_CONFIG.global.siteUrl}/rss.php?version=atom</id>
  <generator url="{$PHP_CONFIG.global.siteUrl}" version="0.3">{$PHP_CONFIG.global.siteName}</generator>
  <copyright type="text/plain" mode="escaped"><![CDATA[{$PHP_CONFIG.global.copyright}]]></copyright>
  <info mode="xml" type="text/html">
    <div xmlns="http://www.w3.org/1999/xhtml">This is an Atom formatted XML site feed. It is intended to be viewed in a Newsreader or syndicated to another site. Please visit the <a href="{$PHP_CONFIG.global.siteUrl}">{$PHP_CONFIG.global.siteName}</a> for more info.</div>
  </info>

{section name=blog_id loop=$blogs_list}
  <entry>
    <id>{$PHP_CONFIG.global.siteUrl}:1:{$blogs_list[blog_id].id}</id>
    <title type="text/plain" mode="escaped"></title>
    <link rel="alternate" type="text/html" href="{$PHP_CONFIG.global.siteUrl}/index.php?play=reply&amp;id={$blogs_list[blog_id].id}" title="{$blogs_list[blog_id].title}" />
    <link rel="source" type="text/html" href="{$PHP_CONFIG.global.siteUrl}/index.php?play=reply&amp;id={$blogs_list[blog_id].id}" title="{$blogs_list[blog_id].title}" />
    <author>
      <name>{$blogs_list[blog_id].author}</name>
      <url>{$PHP_CONFIG.global.siteUrl}/index.php?play=reply&amp;id={$blogs_list[blog_id].id}</url>
      <email>{$blogs_list[blog_id].email}</email>
    </author>
    <modified>{$blogs_list[blog_id].addtime}</modified>
    <issued>{$blogs_list[blog_id].addtime}</issued>
    <created>{$blogs_list[blog_id].addtime}</created>
    <summary type="text/plain" mode="escaped"><![CDATA[{$blogs_list[blog_id].summarycontent}]]></summary>
    <content type="application/xhtml+xml" xml:lang="zh-CN" xml:space="preserve"><div xmlns="http://www.w3.org/1999/xhtml"><![CDATA[{$blogs_list[blog_id].content}]]></div></content>
  </entry>
{/section}

</feed>
