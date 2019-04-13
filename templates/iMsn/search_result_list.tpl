{include file="header.tpl"}

<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
  <tr><td>{$PHP_INPUT.keyword|string_format:$PHP_LANG.search[11]}</td></tr>
</table>

{section name=blog loop=$blogs_list}
<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
 <td class="blogtitle" height="20"><a href="./index.php?play=reply&id={$blogs_list[blog].id}&keyword={$PHP_INPUT.keyword}">{$blogs_list[blog].title}</a></td>
</tr>
<tr>
    <td class="blogcontent">
	<div style="color:#10659e;text-align:right;"><img src="./images/weather/{$blogs_list[blog].weather}.gif" /> Posted by <a href="mailto:{$blogs_list[blog].email}" class="cn">{$blogs_list[blog].author}</a> in [{$blogs_list[blog].cnName}] at {$blogs_list[blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"} </div>
	{$blogs_list[blog].summarycontent}
	<br>
    </td>
</tr>
<tr>
    <td align="right" class="blogother"> <a href="./index.php?play=reply&id={$blogs_list[blog].id}" class="cn">{$PHP_LANG.search[7]}{$blogs_list[blog].commentCount}</a>
      | {$PHP_LANG.search[8]}{$blogs_list[blog].visits} | {$PHP_LANG.search[9]}{$blogs_list[blog].trackbackCount} | <a href="./index.php?play=reply&id={$blogs_list[blog].id}" target="_blank"class="cn">{$PHP_LANG.search[10]}</a>
    </td>
</tr>
</table>

{sectionelse}
{$PHP_LANG.index[48]}
{/section}

<!--  分页信息  -->
<table width="92%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
      {$counter.current_page|string_format:$PHP_LANG.search[5]}, {$counter.pages|string_format:$PHP_LANG.search[6]} 
      <a href="{$PHP_VARIABLES.PHP_SELF}?keyword={$PHP_INPUT.keyword}" class="cn">{$PHP_LANG.search[1]}</a>
      {if $counter.current_page > 1}<a href="{$PHP_VARIABLES.PHP_SELF}?keyword={$PHP_INPUT.keyword}&page={math equation="x - 1" x=$counter.current_page}" class="cn">{$PHP_LANG.search[2]}</a>{/if}
      {if $counter.current_page < $counter.pages}<a href="{$PHP_VARIABLES.PHP_SELF}?keyword={$PHP_INPUT.keyword}&page={math equation="x + 1" x=$counter.current_page}" class="cn">{$PHP_LANG.search[3]}</a>{/if}
      <a href="{$PHP_VARIABLES.PHP_SELF}?keyword={$PHP_INPUT.keyword}&page={$counter.pages}" class="cn">{$PHP_LANG.search[4]}</a>
    </td>
  </tr>
</table>

{include file="footer.tpl"}
