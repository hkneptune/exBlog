<HTML>
<HEAD>
<title>{$PHP_CONFIG.global.siteName}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$PHP_LANG.public.charset}" />
<meta http-equiv="Content-Language" content="{$PHP_LANG.public.language}" />
<meta name="author" content="{$PHP_CONFIG.global.Webmaster}" />
<meta name="keywords" content="{$PHP_CONFIG.global.sitekeyword}" />
<meta name="description" content="{$PHP_CONFIG.global.Description}" />
<link rel="alternate" type="application/rss+xml" href="{$PHP_CONFIG.global.siteUrl}/rss.php" title="{$PHP_CONFIG.global.siteName}" />
<link rel="alternate" type="application/rss+xml" href="{$PHP_CONFIG.global.siteUrl}/atom.php" title="{$PHP_CONFIG.global.siteName}" />
<link rel="icon" href="./favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<link href="./{$PHP_CONFIG.global.tmpURL}/style/styles.css" rel="stylesheet" type="text/css" />
<script language=JavaScript src="./{$PHP_CONFIG.global.tmpURL}/style/common.js"></script>
<script language="JavaScript" type="text/javascript" src="./include/ex1.js"></script>
<Script language=JavaScript>
{literal}
function changeTemplate(obj_value)
{
	query_string = location.search;
	reg_expression_1 = /(.*?)template=(.*?)&(.*)/g;
	reg_expression_2 = /(.*?)template=.*/g;
	if ('' == query_string) query_string = "?template=" + obj_value;
	else if (reg_expression_1.test(query_string)) query_string = query_string.replace(reg_expression_1, "$1template=" + obj_value +"&$3");
	else if (reg_expression_2.test(query_string)) query_string = query_string.replace(reg_expression_2, "$1template=" + obj_value);
	else query_string += "&template=" + obj_value;
	tmp_string = location.protocol + "//" + location.hostname;
	if ('' != location.port) tmp_string += ":" + location.port;
	tmp_string += location.pathname + query_string;
	location.href = tmp_string;
}
{/literal}
</Script>
</HEAD>
<BODY leftMargin=0 topMargin=0 rightMargin=0 dir="ltr">
<TABLE width="750" border=0 align="center" cellPadding=0 cellSpacing=0 
background="./{$PHP_CONFIG.global.tmpURL}/images/top_bg.gif">
  <TBODY>
    <TR>
      <TD align=center width=147>
      <A hideFocus href="./"><IMG alt="logo" src="./{$PHP_CONFIG.global.tmpURL}/images/logo.gif" border=0></A> </TD>
      <TD align=right>
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
          <TBODY>
            <TR align=right>
              <TD height="38" align=right>
{section name=top_menu loop=$top_menus}
<SPAN class=bold><A hideFocus href="{$top_menus[top_menu].url}" title="{$top_menus[top_menu].text}" target="content">{$top_menus[top_menu].text}</A></SPAN> |
{/section}

<select name=template_name id=template_name onChange=changeTemplate(this.options[selectedIndex].value)>{section name=template_id loop=$templates}<option value="{$templates[template_id].path}"{if $smarty.get.template eq $templates[template_id].path} selected{/if}>{$templates[template_id].name}</option>{/section}</select>

&nbsp;&nbsp; </TD>
            </TR>
            <TR vAlign=center align=right>
              <TD height=27><div align="left">
{section name=column loop=$columns_list}
| 
<SPAN class=bold>
<A hideFocus href="./index.php?play=view&sort={$columns_list[column].id}" title="{$columns_list[column].description}">{$columns_list[column].cnName}</A>
{/section} |
              </div></TD>
            </TR>
          </TBODY>
      </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>
<table width="750" height="13"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="center">
        <MARQUEE onmouseover=this.stop(); onmouseout=this.start(); scrollAmount=3>
{section name=announce loop=$announces_list}
<a href="#" onclick="window.open('./index.php?play=announce&anid={$announces_list[announce].id}', 'newwindow', 'height=400, width=500, top=100,left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no')" class="G">{$announces_list[announce].title}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{/section}
</MARQUEE>
    </div></td>
  </tr>
</table>
<TABLE class=green2 cellSpacing=0 cellPadding=0 width="750" align=center border=0>
  <TBODY>
    <TR>
      <TD>
        <TABLE cellSpacing=0 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR class=category>
              <TD width="21%" height=18>&nbsp;</TD>
              <TD>
                <TABLE class=smalltxt 
                        style="TABLE-LAYOUT: fixed; WORD-WRAP: break-word" 
                        cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR style="COLOR: #000000">
                      <TD class=bold> </TD>
                      <TD noWrap align=right width=150> </TD>
                    </TR>
                  </TBODY>
              </TABLE></TD>
            </TR>
          </TBODY>
      </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 width="750" align=center 
            bgColor=#dbdbdb border=0>
              <TBODY>
              <TR>
<TD height=3></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width="750" align=center 
border=0>
  <TBODY>
    <TR>
      <TD bgColor=#dbdbdb>
        <TABLE style="TABLE-LAYOUT: fixed; WORD-WRAP: break-word" 
                  cellSpacing=1 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR bgColor=#ffffff><A name=pid15412></A>
              <TD vAlign=top width="200">
                  <TABLE cellSpacing=0 cellPadding=5 width="95%" 
                        align=center border=0>
                    <TBODY>
                      <TR>
                        <TD>
<style type="text/css">
{literal}
<!--
.style1 {color: #FF0000}
-->
{/literal}
</style>

      <table width="180" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr><td class="caltitle">{$calendar_title}</td></tr>
      </table>
<table width="180" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#f6f6f6">
  <tr align="center" bgcolor="#FDEAF0">
          {section name=week_day loop=$calendar_weekdays}
          <td bgcolor="#f6f6f6">{$calendar_weekdays[week_day]}</td>
          {/section}
        </tr>
        {section name=day loop=$calendar_days}
          {if $smarty.section.day.index % 7 eq 0}<tr>{/if}
          <td class="{if $calendar_days[day].today}weektodaycell{else}daycell{/if}">{if $calendar_days[day].url != ''}<a href="./index.php?play=calendar&date={$calendar_days[day].url}">{/if}{$calendar_days[day].text}{if $calendar_days[day].url != ''}</a>{/if}</td>
          {if $smarty.section.day.index % 7 eq 6}</tr>{/if}
        {/section}
    </table>
    <table width="180" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="calfooter">
          <A class="more" href="./index.php?play=calendar&date={$current_time|date_format:"%Y"}-{$nearby_dates.monthup}"><img src="./{$PHP_CONFIG.global.tmpURL}/images/3.gif" width="10" height="10" border="0"></A><B> {$PHP_LANG.public[39]} </B> <A class="more" href="./index.php?play=calendar&date={$current_time|date_format:"%Y"}-{$nearby_dates.monthdown}"><img src="./{$PHP_CONFIG.global.tmpURL}/images/4.gif" width="10" height="10" border="0"></A>
          <A class="more" href="./index.php?play=calendar&date={$nearby_dates.yearup}-{$current_time|date_format:"%m"}"><img src="./{$PHP_CONFIG.global.tmpURL}/images/3.gif" width="10" height="10" border="0"></A><B> {$PHP_LANG.public[40]} </B> <A class="more" href="./index.php?play=calendar&date={$nearby_dates.yeardown}-{$current_time|date_format:"%m"}"><img src="./{$PHP_CONFIG.global.tmpURL}/images/4.gif" width="10" height="10" border="0"></A>
        </td>
      </tr>
    </table>

<br>
<div align="center">
{if !$PHP_HAD_LOGIN}
<form method="post" action="./admin/login.php">
{$PHP_LANG.public[52]} <input name="user" type="text" maxlength="20" class="input" size="13"><br />
{$PHP_LANG.public[53]} <input name="passwd" type="password" maxlength="20" class="input" size="13"><br />
{if $PHP_CONFIG.global.GDswitch == 1}{$PHP_LANG.public[71]} <input name="imgVal" type="text" maxlength="5" class="input" size="5"> <img src="./include/VerifyImage.php?type=login" align="absmiddle"><br />{/if}
<input type="hidden" name="action" value="actlogin" />
<input type="hidden" name="play" value="login" />
<input type="submit" value="{$PHP_LANG.public[54]}" class="botton">
<input type="reset" value="{$PHP_LANG.public[55]}" class="botton">
</form>
{else}
{$user_info.name} [<a href=./index.php?play=logout>{$PHP_LANG.index[30]}</a>]
{/if}
</div>
<br>
                          <table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[41]}</td>
                            </tr>
                            <tr>
                              <td class="content1"><div align="center"></div>                                
                              <table align="left">
                                <tr> 
                                  <td>{$PHP_LANG.public[42]} {$PHP_CONFIG.global.blogCount}</td>
                                </tr>
                                <tr> 
                                  <td>{$PHP_LANG.public[43]} {$PHP_CONFIG.global.commentCount}</td>
                                </tr>
                                <tr> 
                                  <td>{$PHP_LANG.public[44]} {$PHP_CONFIG.global.trackbackCount}</td>
                                </tr>
                                <tr> 
                                  <td>{$PHP_LANG.public[45]} {$PHP_CONFIG.global.visits}</td>
                                </tr>
                                <tr> 
                                  <td>{$PHP_LANG.public[46]} {$PHP_CONFIG.global.todayVisits}</td>
                                </tr>
                                <tr>
                                  <td>{$PHP_LANG.public[47]} {$PHP_CONFIG.global.userCount} {if $PHP_CONFIG.global.userCount gt 0}<a href="./index.php?play=user">{$PHP_LANG.public[49]}</a>{/if}</td>
                                </tr>
{if $PHP_CONFIG.global.isCountOnlineUser == '1'}<tr><td>{$PHP_LANG.public[48]} {$PHP_CONFIG.global.online}</td></tr>{/if}
                              </table>
                              </td>
                            </tr>
                          </table>
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1">
                          <table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[50]}</td>
                            </tr>
                            <tr>
                              <td class="content2"><table>
{section name=pigeonhole loop=$pigeonholes_list}
            <tr><td><img src="./{$PHP_CONFIG.global.tmpURL}/images/arrow.gif" border="0">  <a href="./index.php?play=calendar&date={$pigeonholes_list[pigeonhole].url}">{$pigeonholes_list[pigeonhole].text}</a></td></tr>
            {/section}
                              </table></td>
                            </tr>
                          </table>
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1">
                          <table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[56]}</td>
                            </tr>
                            <tr>
                              <td class="content2"><table>
{section name=last_blog loop=$last_articles_list}
            <tr><td><img src="./{$PHP_CONFIG.global.tmpURL}/images/arrow.gif" border="0"> <a href="./index.php?play=reply&id={$last_articles_list[last_blog].id}" title="{$last_articles_list[last_blog].title}">{$last_articles_list[last_blog].summytitle}</a></td></tr>
            {/section}
                              </table></td>
                            </tr>
                          </table>
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1">
                          <table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[57]}</td>
                            </tr>
                            <tr>
                              <td class="content2"><table>
{section name=last_comment loop=$last_comments_list}
            <tr><td><img src="./{$PHP_CONFIG.global.tmpURL}/images/arrow.gif" border="0"> <a href="./index.php?play=reply&id={$last_comments_list[last_comment].commentID}" title="{$last_comments_list[last_comment].content}">{$last_comments_list[last_comment].summycontent}</a></td></tr>
            {/section}
                              </table></td>
                            </tr>
                          </table>
<!--
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1">
                          <table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[58]}</td>
                            </tr>
                            <tr>
                              <td class="content2"><table>
        
  <tr>
    <td>
    <img src="./{$PHP_CONFIG.global.tmpURL}/images/arrow.gif" border="0"> <a href="./index.php?play=reply&amp;id=150">???????</a>
    </td>
  </tr>

                              </table></td>
                            </tr>
                          </table>
-->
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1" height="16">
                          <table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[59]}</td>
                            </tr>
                            <tr>
                              <td class="content3"><table width="100%">
							  <tr>
							  <td>
{section name=last_link loop=$last_links_list}
<style type="text/css">
{literal}
<!--
.style3 {color: #99cc00}
-->
{/literal}
</style> 
   <span class="style3"><b>-</b></span> <a href="./index.php?play=links&action=GoTo&id={$last_links_list[last_link].id}" title="{$last_links_list[last_link].description}" target="_blank">{$last_links_list[last_link].homepage}</a>  ({$last_links_list[last_link].visits}) <br>
{/section}
							</td> 
							</tr>
                              </table>
                              <div align="right"><a href="./index.php?play=links&action=Register">{$PHP_LANG.public[60]}</a></div></td>
                            </tr>
                          </table>
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1" height="16">
                          <table width="180" border="0" align="center" cellpadding="2" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[63]}</td>
                            </tr>
                            <tr>
                              <td align="center" class="content3">
          <a href="./rss.php?version=1" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/rss10.png" alt="RSS View v:1.0" border="0"></a>
          <a href="./rss.php?version=2" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/rss20.png" alt="RSS View v:2.0" border="0"></a><br />
          <a href="./atom.php" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/atom03.png" alt="ATOM View v:0.3" border="0"></a>
          <a href="javascript:window.external.AddChannel('http://www.madcn.com/madcn.cdf');" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/channel.png" alt="channel" border="0"></a><br />
          <a href="./rss2.php?comment=10" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/comment.png" alt="{$PHP_LANG.public[65]}" border="0"></a>
          <a href="http://www.exBlog.net/" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/icon_power.png" alt="Powered by exBlog" border="0"></a><br />
          <a href="http://www.creativecommons.cn/licenses/by-sa/1.0/" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/cc.png" alt="Creative Commons" border="0"></a>
          <img src="{$PHP_CONFIG.global.tmpURL}/images/gb2312.gif" alt="DeCode: GB2312" border="0"><br />
          <a href="http://www.php.net/" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/php.png" alt="PHP" border="0"></a>
          <a href="http://www.mysql.com/" target="_blank"><img src="{$PHP_CONFIG.global.tmpURL}/images/mysql.png" alt="MySQL" border="0"></a><br />
</td>
                            </tr>							
                          </table>
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1" height="16">
<table width="180" border="0" align="center" cellpadding="4" cellspacing="0" class="border2">
                            <tr>
                              <td height="20" valign="middle" bgcolor="#e6e6e6" class="title1"> {$PHP_LANG.public[61]}</td>
                            </tr>
                            <tr>
                              <td class="content4"><form method="post" action="./search.php">
                                  <div align="center">
                                    <input name="keyword" type="text" class="input" onfocus=this.select() onClick=this.value='' onmouseover=this.focus() value="{$PHP_LANG.public[64]}" size="18">
                                    <input name="action2" type="submit" value="{$PHP_LANG.public[62]}" class="botton">
                                  </div>
                              </form></td>
                            </tr>
                          </table>
                          <img src="./{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1" height="16"></TD>
                      </TR>
                    </TBODY>
                  </TABLE>
              <SPAN 
                        class=smalltxt></SPAN></TD>
              <TD height="100%" valign="top" bgcolor="#ffffff">
                <TABLE 
                        style="TABLE-LAYOUT: fixed; WORD-WRAP: break-word" 
                        height="100%" cellSpacing=6 cellPadding=0 width="100%" 
                        border=0>
                    <TBODY>
                      <TR>
                        <TD vAlign=top>