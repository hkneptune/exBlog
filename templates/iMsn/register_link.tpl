b{include file="header.tpl"}

{if $RegisterLinkSuccessful == TRUE}
<p align=center>{$PHP_LANG.index[64]}</p>
{else}
<form method="post" action="{$smarty.server.PHP_SELF}">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr> 
    <td>　</td>
  </tr>
{if $PHP_CONFIG.global.GDswitch == 1}
  <tr> 
    <td width="20%">&nbsp; {$PHP_LANG.public[71]}</td>
    <td width="80%"> <input name="imgVal" type="text" size="6" maxsize="6"> <img src="./include/VerifyImage.php?type=RegisterLink&length=6" align="absmiddle">
      <font color="#FF0000">*</font> </td>
  </tr>
{/if}
  <tr> 
    <td width="20%">&nbsp; {$PHP_LANG.index[32]}</td>
    <td width="80%"> <input name="webTitle" type="text" size="40"> 
      <font color="#FF0000">*</font> </td>
  </tr>
  <tr> 
    <td>&nbsp; {$PHP_LANG.index[33]}</td>
    <td><input name="webUrl" type="text" size="40"> <font color="#FF0000">*</font> 
      {$PHP_LANG.index[37]} </td>
  </tr>
  <tr> 
    <td>&nbsp; {$PHP_LANG.index[34]}</td>
    <td><input name="webLogo" type="text" size="40"> {$PHP_LANG.index[38]} </td>
  </tr>
  <tr> 
    <td valign="top">&nbsp; {$PHP_LANG.index[35]}</td>
    <td><textarea name="webDescription" cols="60" rows="5" wrap="VIRTUAL"></textarea> 
    </td>
  </tr>
  <tr> 
    <td height="35" colspan="2">
<div align="center">
<input type="hidden" name="action" value="reglink" />
        <input type="submit" value="{$PHP_LANG.index[36]}">
      </div></td>
  </tr>
</table>
</form>
{/if}

{include file="footer.tpl"}