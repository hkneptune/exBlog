{include file="header.tpl"}

<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr> 
 <td class="blogtitle" height="20">{$PHP_LANG.index[46]}</td>
</tr>
<tr> 
    <td class="blogcontent">
	
{section name=tag_id loop=$tags_list}
<span class="size5"><a href="./index.php?play=tag&tags={$tags_list[tag_id]}">{$tags_list[tag_id]}</a></span>&nbsp;&nbsp;
{sectionelse}
{$PHP_LANG.index[47]}
{/section}

    </td>
</tr>
</table>

{include file="footer.tpl"}