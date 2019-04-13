{include file="header.tpl"}

{section name=settop_blog loop=$settop_blogs_list}
<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
 <td class="blogtitle" height="20">{$PHP_LANG.index[28]} <a href="?play=reply&id={$settop_blogs_list[settop_blog].id}">{$settop_blogs_list[settop_blog].title}</a></td>
</tr>
<tr>
    <td class="blogcontent">
	<div style="color:#10659e;text-align:right;"><img src="./images/weather/{$settop_blogs_list[settop_blog].weather}.gif" /> Posted by <a href="mailto:{$settop_blogs_list[settop_blog].email}" class="cn">{$settop_blogs_list[settop_blog].author}</a> in [{$settop_blogs_list[settop_blog].cnName}] at {$settop_blogs_list[settop_blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"} </div>
	{$settop_blogs_list[settop_blog].summarycontent}
	<br>
    </td>
</tr>
<tr>
    <td align="right" class="blogother"> <a href="?play=reply&id={$settop_blogs_list[settop_blog].id}" class="cn">{$PHP_LANG.index[5]}{$settop_blogs_list[settop_blog].commentCount}</a>
      | {$PHP_LANG.index[6]}{$settop_blogs_list[settop_blog].visits} | {$PHP_LANG.index[7]}{$settop_blogs_list[settop_blog].trackbackCount} | <a href="./index.php?play=reply&id={$settop_blogs_list[settop_blog].id}" target="_blank"class="cn">{$PHP_LANG.index[29]}</a>
    </td>
</tr>
</table>
{/section}

{section name=blog loop=$blogs_list}
<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
 <td class="blogtitle" height="20"><a href="?play=reply&id={$blogs_list[blog].id}">{$blogs_list[blog].title}</a></td>
</tr>
<tr>
    <td class="blogcontent">
	<div style="color:#10659e;text-align:right;"><img src="./images/weather/{$blogs_list[blog].weather}.gif" /> Posted by <a href="mailto:{$blogs_list[blog].email}" class="cn">{$blogs_list[blog].author}</a> in [{$blogs_list[blog].cnName}] at {$blogs_list[blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"} </div>
	{$blogs_list[blog].summarycontent}
	<br>
    </td>
</tr>
<tr>
    <td align="right" class="blogother"> <a href="?play=reply&id={$blogs_list[blog].id}" class="cn">{$PHP_LANG.index[5]}{$blogs_list[blog].commentCount}</a>
      | {$PHP_LANG.index[6]}{$blogs_list[blog].visits} | {$PHP_LANG.index[7]}{$blogs_list[blog].trackbackCount} | <a href="./index.php?play=reply&id={$blogs_list[blog].id}" target="_blank"class="cn">{$PHP_LANG.index[29]}</a>
    </td>
</tr>
</table>

{sectionelse}
<p align=center>{$PHP_LANG.index[48]}</p>
{/section}

<!--  分页信息  -->
<table width="92%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
      {$counter.current_page|string_format:$PHP_LANG.index[49]}, {$counter.pages|string_format:$PHP_LANG.index[50]} 
      <a href="{$PHP_VARIABLES.PHP_SELF}" class="cn">{$PHP_LANG.index[51]}</a>
      {if $counter.current_page > 1}<a href="{$PHP_VARIABLES.PHP_SELF}?page={math equation="x - 1" x=$counter.current_page}" class="cn">{$PHP_LANG.index[52]}</a>{/if}
      {if $counter.current_page < $counter.pages}<a href="{$PHP_VARIABLES.PHP_SELF}?page={math equation="x + 1" x=$counter.current_page}" class="cn">{$PHP_LANG.index[53]}</a>{/if}
      <a href="{$PHP_VARIABLES.PHP_SELF}?page={$counter.pages}" class="cn">{$PHP_LANG.index[54]}</a>
    </td>
  </tr>
</table>

{include file="footer.tpl"}