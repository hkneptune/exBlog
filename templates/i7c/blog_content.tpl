{include file="header.tpl"}

<TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
  <TBODY>
    <TR>
      <TD bgColor=#e6e6e6>
        <TABLE cellSpacing=1 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR class=header> 
              <TD width="57%"><strong><img src="./{$PHP_CONFIG.global.tmpURL}/images/arrow_dw.gif" width="33" height="11">{$blog_info.title}</strong></TD>
              <TD width="43%"><img src="./images/weather/{$blog_info.weather}.gif"> 
                &nbsp;&nbsp;&nbsp;{$blog_info.addtime|date_format:"%Y-%m-%d %H:%M:%S"}</TD>
            </TR>
            <TR> 
              <TD colspan="2" align=middle bgColor=#ffffff> <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR> 
                      <TD class=smalltxt 
                        align=middle onmouseover="this.style.backgroundColor='#F8F8F8'" 
                      onmouseout="this.style.backgroundColor='#FFFFFF'"><div align="left"><br>
                          {$blog_info.content} <BR>
                        </div></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
            </TR>
            <TR> 
              <TD colspan="2" align=middle bgColor=#ffffff> <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR>
{assign var=x value=$blog_info.email|string_format:$PHP_LANG.index[67]}
{assign var=y value=$blog_info.author|string_format:$x}
                      <TD class=smalltxt 
                        align=middle ><div align="right">{$blog_info.cnName|string_format:$y}| {$PHP_LANG.index[6]}{$blog_info.visits} 
                          | {$PHP_LANG.index[5]}{$blog_info.commentCount} | {$PHP_LANG.index[7]}{$blog_info.trackbackCount}</div></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
            </TR>
          </TBODY>
        </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>
<br>

<div align="left"><span class="puttitle"><strong><img src="./{$PHP_CONFIG.global.tmpURL}/images/arrow_dw.gif" width="33" height="11">{$PHP_LANG.index[8]}</strong></span></div>

<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
    <td bgcolor="#e6e6e6" class="blogcontent"><b>{$PHP_LANG.index[8]}</b>
    {section name=keyword loop=$blog_info.keywords}
    <a href="?play=tag&tags={$blog_info.keywords[keyword]}" rel="tag">{$blog_info.keywords[keyword]}</a>,
    {sectionelse}
    {$PHP_LANG.index[12]}
    {/section}</td>
</tr>
<tr>
	<td height="2" bgcolor="#FFFFFF"></td>
</tr>
</table>

<div align="left"><span class="replytitle"><strong><img src="./templates/i7c/images/arrow_dw.gif" width="33" height="11">{$PHP_LANG.index[9]} [{$blog_info.trackbackCount}]</strong></span></div>
<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
    <td bgcolor="#e6e6e6" class="blogcontent">???Trackback</td>
</tr>
{section name=trackback_id loop=$trackbacks_list}
<tr>
    <td  align="right" class=smalltxt 
                         onMouseOver="this.style.backgroundColor='#F8F8F8'" 
                      onMouseOut="this.style.backgroundColor='#FFFFFF'">
	{$trackbacks_list[trackback_id].addtime|date_format:"%Y-%m-%d %H:%M:%S"|string_format:$PHP_LANG.index[66]}
 <a href="{$trackbacks_list[trackback_id].url}"><img src="./templates/i7c/images/homepage.gif" title="{$trackbacks_list[trackback_id].blog_name}" /></a></td>
</tr>
<tr>
	<td height="2" bgcolor="#FFFFFF"><b>{$trackbacks_list[trackback_id].title}</b><br />{$trackbacks_list[trackback_id].excerpt}</td>
</tr>
{/section}
</table>

<div align="left"><span class="replytitle"><strong><img src="./templates/i7c/images/arrow_dw.gif" width="33" height="11">{$PHP_LANG.index[10]} [{$blog_info.commentCount}]</strong></span></div>
{section name=comment loop=$comments_list}
<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
    <td bgcolor="#e6e6e6" class="blogcontent">{$comments_list[comment].content}</td>
</tr>
<tr>
    <td  align="right" class=smalltxt 
                         onMouseOver="this.style.backgroundColor='#F8F8F8'" 
                      onMouseOut="this.style.backgroundColor='#FFFFFF'">Posted by <a href="mailto:{$comments_list[comment].email}">{$comments_list[comment].author}</a> 
      at {$comments_list[comment].addtime|date_format:"%Y-%m-%d %H:%M:%S"} <a href="{$comments_list[comment].homepage}"> <img src="./{$PHP_CONFIG.global.tmpURL}/images/homepage.gif" width="15" height="12" border="0"></a> 
      <a href="http://search.tencent.com/cgi-bin/friend/user_show_info?ln={$comments_list[comment].qq}" target="_blank"><img src="./{$PHP_CONFIG.global.tmpURL}/images/QQ.gif" width="15" height="12" border="0"></td>
</tr>
<tr>
	<td height="2" bgcolor="#FFFFFF"></td>
</tr>
</table>
{sectionelse}
<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
    <td bgcolor="#e6e6e6" class="blogcontent">{$PHP_LANG.index[14]}</td>
</tr>
</table>
{/section}

{if $blog_info.nocom == 0}
<div align="left"><span class="puttitle"><strong><img src="./templates/i7c/images/arrow_dw.gif" width="33" height="11">{$PHP_LANG.index[11]}</strong></span></div>

<table  bgcolor=#F4F4F4 width="100%" border="0" cellpadding="0" cellspacing="2" class="main">
<form name="exEdit" method="post" action="./comment.php">
{if !$PHP_HAD_LOGIN}
<tr>
<td width="20%"><img src="./templates/i7c/images/nick.gif"> {$PHP_LANG.index[15]}</td>
<td width="80%"><input name="name" type="text" id="author" size="20" class="input">
<font color="#FF0000">*</font> {$PHP_LANG.index[16]} 
<input name="password" type="password" size="15" class="input"></td>
</tr>
<tr>
<td width="20%"><img src="./templates/i7c/images/mail.gif"> {$PHP_LANG.index[17]}</td>
<td width="80%"><input name="email" type="text" size="20" class="input">
<input type="checkbox" name="register" value="yes" class="input"> {$PHP_LANG.index[24]}</td>
</tr>
<tr>
<td width="20%"><img src="./templates/i7c/images/homepage.gif"> {$PHP_LANG.index[18]}</td>
<td width="80%"><input name="homepage" type="text" size="30" class="input"> {$PHP_LANG.index[25]}</td>
</tr>
{/if}
<tr><td valign="top">&nbsp;{$PHP_LANG.index[20]}</td><td>
<textarea name="content" cols="50" rows="5" wrap="VIRTUAL" class="input"></textarea>
<font color="#FF0000">*</font></td>
</tr>
<tr><td height="36" colspan="2"><div align="center">
<input type="hidden" name="id" value="{$blog_info.id}">
<input type="hidden" name="action" value="add_replay" />
<input type="submit" value="{$PHP_LANG.index[23]}" class="botton">
</div></td></tr>
</form>
{/if}
</TD>
</TR>

{include file="footer.tpl"}
