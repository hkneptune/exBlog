<?xml version="1.0" encoding="{$PHP_LANG.public.charset}"?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">

<wml>
  <card id="{$card_id}">
    <p align="center"><b>{$PHP_CONFIG.global.siteName}</b><br/></p>

{if $action == 'view'}

<p align="center"><b>-{$PHP_LANG.wap[2]}-</b><br/></p>
 <p>{$PHP_LANG.wap[3]}{$article_info.title}</p>
<p>{$PHP_LANG.wap[4]}{$article_info.author}</p>
<p>{$PHP_LANG.wap[5]}{$article_info.content|strip_tags:false}</p>
<p align="center"><a href="{$smarty.server.PHP_SELF}?action=viewcomment&amp;id={$article_info.id}">{$PHP_LANG.wap[6]}</a><br /><a href="{$smarty.server.PHP_SELF}?action=addcomment&amp;id={$article_info.id}">{$PHP_LANG.wap[7]}</a><br /><a href="{$smarty.server.PHP_SELF}?action=list">{$PHP_LANG.wap[19]}</a></p>

{elseif $action  == 'addcomment'}

<p align="center"><b>-{$PHP_LANG.wap[8]}-</b><br/></p>
<p>
{$PHP_LANG.wap[9]}<input name="name" type="text" /><br/>
{$PHP_LANG.wap[10]}<input name="password" type="text" /><br/>
{$PHP_LANG.wap[11]}<input name="email" type="text" /><br/>
{$PHP_LANG.wap[12]}<input name="homepage" type="text" emptyok="true" /><br/>
{$PHP_LANG.wap[14]}<input name="content" type="text" /><br/>
{$PHP_LANG.wap[15]}
</p>
<p align="center">
  <anchor title="submit">{$PHP_LANG.wap[16]}
    <go href="{$smarty.server.PHP_SELF}?action=addcomment" method="post">
      <postfield name="name" value="$(name)" />
      <postfield name="password" value="$(password)" />
      <postfield name="email" value="$(email)" />
      <postfield name="homepage" value="$(homepage)" />
      <postfield name="id" value="{$smarty.get.id}" />
      <postfield name="content" value="$(content)" />
    </go>
  </anchor>
</p>
<p align="center"><a href="{$smarty.server.PHP_SELF}?action=view&amp;id={$smarty.get.id}">{$PHP_LANG.wap[19]}</a></p>

{elseif $action == 'viewcomment'}

<p align="center"><b>-{$PHP_LANG.wap[17]}-</b><br/></p>
{section name=comment loop=$comments_list}
<p>
  <b>{$smarty.section.comment.index} </b>{$PHP_LANG.wap[18]}{$comments_list[comment].author}<br/>
  {$comments_list[comment].content|strip_tags:false}
</p>
{/section}
<p align="center"><a href="{$smarty.server.PHP_SELF}?action=view&amp;id={$smarty.get.id}">{$PHP_LANG.wap[19]}</a></p>

{elseif $action == 'error'}

<p align="center"><b>-{$PHP_LANG.wap[31]}-</b><br /></p>
{section name=error loop=$message}
<p>{$message[error]}</p>
{/section}
<p align="center"><a href="{$smarty.server.PHP_SELF}?action=viewcomment&amp;id={$smarty.get.id}">{$PHP_LANG.wap[19]}</a></p>

{elseif $action == 'message'}

<p align="center"><b>-{$PHP_LANG.wap[32]}-</b><br /></p>
<p>{$message}</p>
<p align="center"><a href="{$smarty.server.PHP_SELF}?action=viewcomment&amp;id={$smarty.get.id}">{$PHP_LANG.wap[19]}</a></p>

{else}

    <p align="center"><b>-{$PHP_LANG.wap[0]}-</b><br/></p>
{section name=article loop=$articles_list}
    <p><b>{$smarty.section.article.index}&nbsp;</b><a href="{$smarty.server.PHP_SELF}?action=view&amp;id={$articles_list[article].id}">{$articles_list[article].title}</a>{$articles_list[article].addtime|date_format:"%Y-%m-%d"}<br /></p>
{/section}

{/if}
    <p align="center">
      {$PHP_LANG.wap[1]}
    </p>
  </card>
</wml>