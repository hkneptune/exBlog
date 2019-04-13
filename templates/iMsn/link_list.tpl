{include file="header.tpl"}

{section name=link_id loop=$links_list}
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr><td class="blogtitle" height="20" colspan=2>{$links_list[link_id].homepage}</td></tr>
  <tr>
    <td rowspan=2 width=100>{if $links_list[link_id].logoURL ne ''}<a href={$PHP_VARIABLES.PHP_SELF}?play=links&action=GoTo&id={$links_list[link_id].id}><img src={$links_list[link_id].logoURL} border=0 width=88 height=31></a>{/if}</td>
    <td><a href={$PHP_VARIABLES.PHP_SELF}?play=links&action=GoTo&id={$links_list[link_id].id}>{$links_list[link_id].url}</a></td>
  </tr>
  <tr><td>{$links_list[link_id].description}</td></tr>
</table>
<br /><br />
{/section}

{include file="footer.tpl"}