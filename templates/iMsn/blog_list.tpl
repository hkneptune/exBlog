{include file="header.tpl"}

{section name=settop_blog loop=$settop_blogs_list}
{assign var=section1_total value=$smarty.section.settop_blog.total}
<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
 <td class="blogtitle" height="20">{$PHP_LANG.index[28]} <a href="?play=reply&id={$settop_blogs_list[settop_blog].id}">{$settop_blogs_list[settop_blog].title}</a></td>
</tr>
<tr>
    <td class="blogcontent">
	<div style="color:#10659e;text-align:right;"><img src="./images/weather/{$settop_blogs_list[settop_blog].weather}.gif" />
{assign var=x value=$settop_blogs_list[settop_blog].email|string_format:$PHP_LANG.index[2]}
{assign var=y value=$settop_blogs_list[settop_blog].author|string_format:$x}
{assign var=z value=$settop_blogs_list[settop_blog].cnName|string_format:$y}
{$settop_blogs_list[settop_blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"|string_format:$z}
</div>
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
{assign var=section2_total value=$smarty.section.blog.total}
<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
 <td class="blogtitle" height="20"><a href="?play=reply&id={$blogs_list[blog].id}">{$blogs_list[blog].title}</a></td>
</tr>
<tr>
    <td class="blogcontent">
	<div style="color:#10659e;text-align:right;"><img src="./images/weather/{$blogs_list[blog].weather}.gif" />
{assign var=x value=$blogs_list[blog].email|string_format:$PHP_LANG.index[2]}
{assign var=y value=$blogs_list[blog].author|string_format:$x}
{assign var=z value=$blogs_list[blog].cnName|string_format:$y}
{$blogs_list[blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"|string_format:$z} </div>
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
{if ($section2_total+$section1_total) eq 0}<p align=center>{$PHP_LANG.index[48]}</p>{/if}
{/section}

<!--  分页信息  -->
<table width="92%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
      {$counter.current_page|string_format:$PHP_LANG.index[50]}, {$counter.pages|string_format:$PHP_LANG.index[49]} 
      <a href="javascript:changeQuery('page',1);" class="cn">{$PHP_LANG.index[51]}</a>
      {if $counter.current_page > 1}<a href="javascript:changeQuery('page',{math equation="x - 1" x=$counter.current_page});" class="cn">{$PHP_LANG.index[52]}</a>{/if}
      {if $counter.current_page < $counter.pages}<a href="javascript:changeQuery('page',{math equation="x + 1" x=$counter.current_page});" class="cn">{$PHP_LANG.index[53]}</a>{/if}
      <a href="javascript:changeQuery('page',{$counter.pages});" class="cn">{$PHP_LANG.index[54]}</a>
    </td>
  </tr>
</table>

{include file="footer.tpl"}
