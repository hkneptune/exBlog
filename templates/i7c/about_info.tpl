{include file="header.tpl"}

<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr> 
 <td class="blogtitle" height="20">{$PHP_LANG.public[68]}</td>
</tr>
<tr> 
    <td class="blogcontent">
	<b>{$PHP_LANG.index[39]} </b> {$about_info.name}<br />
	<b>{$PHP_LANG.index[40]} </b> <a target=blank href=http://wpa.qq.com/msgrd?V=1&Uin={$about_info.qq}&Site={$PHP_CONFIG.global.siteName|escape:"url"}&Menu=yes><img border="0" SRC=http://wpa.qq.com/pa?p=1:{$about_info.qq}:13 alt="{$about_info.qq}"></a><br />
	<b>{$PHP_LANG.index[41]} </b> {$about_info.icq}<br />
	<b>{$PHP_LANG.index[42]} </b> {$about_info.msn}<br />
	<b>{$PHP_LANG.index[43]} </b> <a href="mailto:{$about_info.email}"> {$about_info.email}</a><br />
	<b>{$PHP_LANG.index[44]} </b> <a href="{$PHP_CONFIG.global.siteUrl}" target="_blank">{$PHP_CONFIG.global.siteName}</a><br />
	<b>{$PHP_LANG.index[45]} </b> {$about_info.description}<br>
    </td>
</tr>
</table>


</TD>
                      </TR>

{include file="footer.tpl"}