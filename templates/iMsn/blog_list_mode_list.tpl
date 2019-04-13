{include file="header.tpl"}

<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
{section name=settop_blog loop=$settop_blogs_list}
<tr>
 <td height="20">{$PHP_LANG.index[28]} <a href="?play=reply&id={$settop_blogs_list[settop_blog].id}">{$settop_blogs_list[settop_blog].title}</a></td>
</tr>
{/section}
</table>

<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
{section name=blog loop=$blogs_list}
<tr>
 <td height="20"><a href="?play=reply&id={$blogs_list[blog].id}">{$blogs_list[blog].title}</a></td>
</tr>

{sectionelse}
<tr><td align=center><p align=center>{$PHP_LANG.index[48]}</p></td></tr>
{/section}
</table>

<!--  ????  -->
<table width="92%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
      {$counter.current_page|string_format:$PHP_LANG.index[49]}, {$counter.pages|string_format:$PHP_LANG.index[50]} 
      <a href="{$PHP_VARIABLES.PHP_SELF}" class="cn">{$PHP_LANG.index[51]}</a>
      {if $counter.current_page > 1}<a href="{$PHP_VARIABLES.PHP_SELF}?mode=list&page={math equation="x - 1" x=$counter.current_page}" class="cn">{$PHP_LANG.index[52]}</a>{/if}
      {if $counter.current_page < $counter.pages}<a href="{$PHP_VARIABLES.PHP_SELF}?mode=list&page={math equation="x + 1" x=$counter.current_page}" class="cn">{$PHP_LANG.index[53]}</a>{/if}
      <a href="{$PHP_VARIABLES.PHP_SELF}?page={$counter.pages}" class="cn">{$PHP_LANG.index[54]}</a>
    </td>
  </tr>
</table>

{include file="footer.tpl"}