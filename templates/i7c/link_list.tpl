{include file="header.tpl"}

<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
{section name=link_id loop=$links_list}
  <tr> 
    <td width="25%" class="content" height="50">&nbsp;{$links_list[link_id].homepage}</td>
  <td width="30%" class="content" height="50" align="left>{if $links_list[link_id].logoURL ne ''}<a href={$PHP_VARIABLES.PHP_SELF}?play=links&action=GoTo&id={$links_list[link_id].id}><img src={$links_list[link_id].logoURL} border=0 width=88 height=31></a>{/if}</td>
  <td width="45%" class="content"><a href={$PHP_VARIABLES.PHP_SELF}?play=links&action=GoTo&id={$links_list[link_id].id}>{$links_list[link_id].url}</a>&nbsp;&nbsp;({$links_list[link_id].visits}) </td>
  </tr>
{/section}
</table>
</TD>
                      </TR>

{include file="footer.tpl"}