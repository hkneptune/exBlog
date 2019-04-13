{include file="header.tpl"}

      <table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="content" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
              <tr>
                <td height="24" class="blogtitle">{$blog_info.title}</td>
              </tr>
              <tr>
                <td class="blogcontent"> 
		{assign var=x value=$blog_info.email|string_format:$PHP_LANG.index[2]}
		{assign var=y value=$blog_info.author|string_format:$x}
		{assign var=z value=$blog_info.cnName|string_format:$y}
                <div style="color:#10659e;text-align:right;"><img src="./images/weather/{$blog_info.weather}.gif" />{$blog_info.addtime|date_format:"%Y-%m-%d %H:%M:%S"|string_format:$z}</div>
                {$blog_info.content}
                <br>
                </td>
              </tr>
              <tr>
                <td class="blogcontent">
<img src="./{$PHP_CONFIG.global.tmpURL}/images/pl.gif" alt="Permanant URI"/> <strong>{$PHP_LANG.index[3]}</strong> <a href="{$PHP_CONFIG.global.siteUrl}/index.php?play=reply&id={$blog_info.id}">{$PHP_CONFIG.global.siteUrl}/index.php?play=reply&id={$blog_info.id}</a>
<br />
<img src="./{$PHP_CONFIG.global.tmpURL}/images/tb.gif" alt="Trackback URI"/> <strong>{$PHP_LANG.index[4]}</strong> {$PHP_CONFIG.global.siteUrl}/trackback.php?id={$blog_info.id}
                </td>
              </tr>
              <tr>
                <td align="right" class="blogother"> {$PHP_LANG.index[5]}{$blog_info.commentCount} | {$PHP_LANG.index[6]}{$blog_info.visits} | {$PHP_LANG.index[7]}{$blog_info.trackbackCount}
                </td>
              </tr>
            </table>
          </td>
        </tr>
		<tr>
		  <td height="20"> </td>
		</tr>

		<tr>
		  <td height="26" class="J">&nbsp;{$PHP_LANG.index[8]}</td>
		</tr>
		<tr>
		  <td>
<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
<tr>
    <td class="blogcontent">
    {section name=keyword loop=$blog_info.keywords}
    <a href="?play=tag&tags={$blog_info.keywords[keyword]}" rel="tag">{$blog_info.keywords[keyword]}</a>,
    {sectionelse}
    {$PHP_LANG.index[12]}
    {/section}
    <br /></td>
</tr>
<tr>
	<td height="2"></td>
</tr>
</table></td>
		</tr>

		<tr>
		  <td height="26" class="KKK">&nbsp;{$PHP_LANG.index[9]}</td>
		</tr>
		<tr>
		  <td>
<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
{section name=trackback_id loop=$trackbacks_list}
<tr>
    <td class="blogcontent"><b>{$trackbacks_list[trackback_id].title}</b><br />{$trackbacks_list[trackback_id].excerpt}</td>
</tr>
<tr>
	{assign var=x value=$trackbacks_list[trackback_id].addtime|date_format:"%Y-%m-%d %H:%M:%S"|string_format:$PHP_LANG.index[66]}
	{assign var=a value="<a href=%s target=_blank><img src=./%%s/images/homepage.gif width=15 height=12 border=0 alt='%%%%s'></a>"}
	{assign var=b value=$trackbacks_list[trackback_id].url|string_format:$a}
	{assign var=c value=$PHP_CONFIG.global.tmpURL|string_format:$b}
	{assign var=d value=$trackbacks_list[trackback_id].blog_name|string_format:$c}
	<td class="blogother" align='right'>{$d|string_format:$x}</td>
</tr>
{sectionelse}
<tr>
    <td class="blogcontent">&nbsp;{$PHP_LANG.index[13]}</td>
</tr>
{/section}
</table>
		  </td>
		</tr>

		<tr>
		  <td height="26" class="KKKK">&nbsp;{$PHP_LANG.index[10]}</td>
		</tr>
		<tr>
		  <td>
<table border="0" cellpadding="5" cellspacing="0" width="100%" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
{section name=comment loop=$comments_list}
<tr>
    <td class="blogcontent">{$comments_list[comment].content}</td>
</tr>
<tr>
    <td class="blogother" align="right">Posted by <a href="mailto:{$comments_list[comment].email}">{$comments_list[comment].author}</a> at
      <a href="{$comments_list[comment].homepage}" target="_blank"><img src="./{$PHP_CONFIG.global.tmpURL}/images/homepage.gif" width="15" height="12" border="0"></a>
      <a href="http://search.tencent.com/cgi-bin/friend/user_show_info?ln={$comments_list[comment].qq}" target="_blank"><img src="./{$PHP_CONFIG.global.tmpURL}/images/QQ.gif" width="15" height="12" border="0"></a></td>
</tr>
{sectionelse}
<tr>
    <td class="blogcontent">&nbsp;{$PHP_LANG.index[14]}</td>
</tr>
{/section}
<tr>
	<td height="2"></td>
</tr>
</table></td>
		</tr>

{if $blog_info.nocom == 0}
		<tr>
		  <td height="26" class="K">&nbsp;{$PHP_LANG.index[11]}</td>
		</tr>
        <tr>
          <td>
            <form name="exEdit" method="post" action="./comment.php">
                <table width="100%" border="0" cellpadding="0" cellspacing="2" class="main">
                  <tr>
                    <td>&nbsp;</td>
                  </tr>

{if !$PHP_HAD_LOGIN}
                  <tr>
                    <td width="20%">&nbsp;<img src="./{$PHP_CONFIG.global.tmpURL}/images/nick.gif" width="15" height="12"> {$PHP_LANG.index[15]}</td>
                    <td width="80%">
                      <input name="name" type="text" id="author" size="20" class="input">
                    <font color="#FF0000">*</font> {$PHP_LANG.index[16]} <input name="password" type="password" size="15"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;<img src="./{$PHP_CONFIG.global.tmpURL}/images/mail.gif" width="14" height="11"> {$PHP_LANG.index[17]}</td>
                    <td><input name="email" type="text" size="20" class="input"> <input type="checkbox" name="register" value="yes"> {$PHP_LANG.index[24]}</td>
                  </tr>
                  <tr>
                    <td>&nbsp;<img src="./{$PHP_CONFIG.global.tmpURL}/images/homepage.gif" width="15" height="12"> {$PHP_LANG.index[18]}</td>
                    <td><input name="homepage" type="text" size="30" class="input"> {$PHP_LANG.index[25]}</td>
                  </tr>
{/if}
                  <tr>
                    <td valign="top" nowrap>&nbsp;<img src="./{$PHP_CONFIG.global.tmpURL}/images/comment.gif" width="15" height="12"> {$PHP_LANG.index[20]}</td>
                    <td>
<a href="./{$PHP_CONFIG.global.tmpURL}/editor/panel1.htm" target="bjgjl" title="{$PHP_LANG.index[22]}">{$PHP_LANG.index[22]}</a> <a href="./{$PHP_CONFIG.global.tmpURL}/editor/panel2.htm" target="bjgjl" title="{$PHP_LANG.index[21]}">{$PHP_LANG.index[21]}</a><br />
<textarea name="content"  cols="60" rows="5" class="input"></textarea>
                    <font color="#FF0000">*</font> </td>
                  </tr>
                  <tr>
                    <td height="36" colspan="2"><div align="center">
                        <input type="hidden" name="id" value="{$blog_info.id}">
                        <input type="hidden" name="action" value="add_replay" />
                        <input type="submit" value="{$PHP_LANG.index[23]}" class="botton">
                    </div></td>
                  </tr>
              </table>
            </form>
{/if}
          </td>
        </tr>
      </table>

{include file="footer.tpl"}