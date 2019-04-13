<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<head>
<title>{$PHP_CONFIG.global.siteName}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$PHP_LANG.public.charset}" />
<meta http-equiv="Content-Language" content="{$PHP_LANG.public.language}" />
<meta content="all" name="robots" />
<meta name="author" content="{$PHP_CONFIG.global.Webmaster}" />
<meta name="Copyright" content="{$PHP_CONFIG.global.copyright}" />
<meta name="keywords" content="{$PHP_CONFIG.global.sitekeyword}" />
<meta name="description" content="{$PHP_CONFIG.global.Description}" />
<link rel="alternate" type="application/rss+xml" href="{$PHP_CONFIG.global.siteUrl}/rss.php" title="{$PHP_CONFIG.global.siteName}" />
<link rel="alternate" type="application/rss+xml" href="{$PHP_CONFIG.global.siteUrl}/atom.php" title="{$PHP_CONFIG.global.siteName}" />
<link rel="icon" href="./favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<link href="{$PHP_CONFIG.global.tmpURL}/style/styles.css" rel="stylesheet" type="text/css">
<Script Language=Javascript src="{$PHP_CONFIG.global.tmpURL}/style/style.js"></Script>
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
</head>
<body bgcolor="#336699" dir="{$PHP_LANG.public.dir}">

<!--  顶部菜单列表开始  -->
<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
  <TBODY>
    <TR><TD colSpan="2"><IMG height="1" src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="779"></TD></TR>
    <TR><TD>
      <TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
        <TBODY>
          <TR>
            <TD rowSpan=2 valign="top" background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.dln.gif"><img src="{$PHP_CONFIG.global.tmpURL}/images/logo_exblog.gif" width="118" height="35"> </TD>
            <TD background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.dln.gif" rowSpan=2></TD>
            <TD rowSpan=2><IMG src="{$PHP_CONFIG.global.tmpURL}/images/tab.slide.hm.li.gif" width="109" height="48"></TD>
            <TD height=13 colSpan=12 bgColor=#336699></TD>
          </TR>

          <TR>
            {section name=top_menu loop=$top_menus}
            {if $top_menus[top_menu].selected}
            <TD background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.dln.gif"><IMG src="{$PHP_CONFIG.global.tmpURL}/images/tab.separator.on.l.gif" width="2" height="35"></TD>
            <TD noWrap="noWrap" background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.on.gif">&nbsp;&nbsp;&nbsp;<a class="E" href="{$top_menus[top_menu].url}">{$top_menus[top_menu].text}</a>&nbsp;&nbsp;&nbsp; </TD>
            {else}
            <TD><IMG src="{$PHP_CONFIG.global.tmpURL}/images/tab.separator.off.gif" width="2" height="35"></TD>
            <TD noWrap="noWrap" background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.off.gif">&nbsp;&nbsp;&nbsp;<a class="E" href="{$top_menus[top_menu].url}">{$top_menus[top_menu].text}</a>&nbsp;&nbsp;&nbsp; </TD>
            {/if}
            {/section}
            <TD><IMG src="{$PHP_CONFIG.global.tmpURL}/images/tab.separator.end.gif" width="1" height="34"></TD>
            <TD width="100%" background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.sln.gif">&nbsp;</TD>
            <TD align=right background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.sln.gif"><select name=template_name id=template_name onChange=changeTemplate(this.options[selectedIndex].value)>{section name=template_id loop=$templates}<option value="{$templates[template_id].path}"{if $smarty.get.template eq $templates[template_id].path} selected{/if}>{$templates[template_id].name}</option>{/section}</select></TD>
            <TD width=10 background="{$PHP_CONFIG.global.tmpURL}/images/tab.bg.sln.gif">&nbsp;</TD>
          </TR>

        </TBODY>
      </TABLE>
      </TD>
    </TR>
  </TBODY>
</TABLE>
<!--  顶部菜单列表结束  -->

<!--  系统公告列表开始  -->
<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
  <TBODY>
    <TR bgColor="#4791c5"><TD colSpan=3><IMG height=1 src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width=779 border="0"></TD></TR>
    <TR bgColor="#4791c5">
      <TD width="80%" style="PADDING-LEFT: 10px; BORDER-BOTTOM: #10659e 1px solid; HEIGHT: 20px">
        <TABLE class="G" cellSpacing="0" cellPadding="0" width="100%" border="0">
          <TBODY>
            <TR>
              <TD vAlign="center" align="left" width="20%">{$PHP_LANG.public[37]}</TD>
              <TD vAlign="center" align="left" width="6%"><img src="{$PHP_CONFIG.global.tmpURL}/images/announce.gif" width="14" height="13"></TD>
              <TD vAlign="center" align="left" width="74%">
                <marquee behavior="scroll" scrollDelay="80" scrollamount="3" onmouseover="this.stop()" onmouseout="this.start()">
                {section name=announce loop=$announces_list}
                ·<a href="#" onclick="window.open('./index.php?play=announce&anid={$announces_list[announce].id}', 'newwindow', 'height=400, width=500, top=100,left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no')" class="G">{$announces_list[announce].title}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {/section}
                </marquee>
              </TD>
            </TR>
          </TBODY>
      </TABLE>
      </TD>
      <TD width="8%" align="right" style="PADDING-LEFT: 10px; BORDER-BOTTOM: #10659e 1px solid; HEIGHT: 20px">
        <TABLE cellSpacing="0" cellPadding="0" border="0">
          <TBODY><TR><TD vAlign="center" align="right"></TD></TR></TBODY>
        </TABLE>
      </TD>
    </TR>
  </TBODY>
</TABLE>
<!--  系统公告列表结束  -->

<!--  分类列表开始  -->
<TABLE class="N" id="HMTB" cellSpacing="0" cellPadding="0" width="100%" border="0">
  <TBODY>
    <TR><TD colSpan=2><IMG height=1 src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="779"></TD></TR>
    <TR>
      <TD height="16">
        <table height="16"  border="0" cellpadding="0" cellspacing="0" class="O">
          <tr>
            <td width="150" align="center" class="col">{$PHP_LANG.public[38]} <img src="{$PHP_CONFIG.global.tmpURL}/images/right.gif" width="9" height="9"></td>
{section name=column loop=$columns_list}
<td width="6" class="LL">|</td>
<td class="P" onMouseOver="MO()" onMouseOut="MU()" nowrap><img src="{$PHP_CONFIG.global.tmpURL}/images/col.gif" width="10" height="10"><a href="./index.php?play=view&sort={$columns_list[column].id}" title="{$columns_list[column].description}" class="column"> {$columns_list[column].cnName}</a></td>
{/section}
          </tr>
        </table>
      </TD>
      <TD height="16" style="CURSOR: auto"><img src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="1" height="1"></TD>
    </TR>
    <TR><TD colSpan="2"><IMG height="1" src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="779"></TD></TR></TBODY>
</TABLE>
<!--  分类列表结束  -->

<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="AA">
  <tr>
    <td width="200" valign="top" bgcolor="#dbeaf5">

<!--  日历显示开始  -->
      <img src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="200" height="10">
      <table width="180" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr><td class="caltitle">{$calendar_title}</td></tr>
      </table>
      <table width="180" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#87b3d0">
        <tr align="center" bgcolor="#FDEAF0">
          {section name=week_day loop=$calendar_weekdays}
          <td bgcolor="#DBEAF5" widtd="0">{$calendar_weekdays[week_day]}</td>
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
          <A class="more" href="./index.php?play=calendar&date={$current_time|date_format:"%Y"}-{$nearby_dates.monthup}"><SPAN class="arrow">3</SPAN></A><B> {$PHP_LANG.public[39]} </B> <A class="more" href="./index.php?play=calendar&date={$current_time|date_format:"%Y"}-{$nearby_dates.monthdown}"><SPAN class="arrow">4</SPAN></A>
          <A class="more" href="./index.php?play=calendar&date={$nearby_dates.yearup}-{$current_time|date_format:"%m"}"><SPAN class="arrow">3</SPAN></A><B> {$PHP_LANG.public[40]} </B> <A class="more" href="./index.php?play=calendar&date={$nearby_dates.yeardown}-{$current_time|date_format:"%m"}"><SPAN class="arrow">4</SPAN></A>
        </td>
      </tr>
    </table>
<!--  日历显示结束  -->

<!--  统计信息列表开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title">{$PHP_LANG.public[41]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%">
            <tr><td>{$PHP_LANG.public[42]} {$PHP_CONFIG.global.blogCount}</td></tr>
            <tr><td>{$PHP_LANG.public[43]} {$PHP_CONFIG.global.commentCount}</td></tr>
            <tr><td>{$PHP_LANG.public[44]} {$PHP_CONFIG.global.trackbackCount}</td></tr>
            <tr><td>{$PHP_LANG.public[45]} {$PHP_CONFIG.global.visits}</td></tr>
            <tr><td>{$PHP_LANG.public[46]} {$PHP_CONFIG.global.todayVisits}</td></tr>
            <tr><td>{$PHP_LANG.public[47]} {$PHP_CONFIG.global.userCount} {if $PHP_CONFIG.global.userCount gt 0}<a href="./index.php?play=user">{$PHP_LANG.public[49]}</a>{/if}</td></tr>
            {if $PHP_CONFIG.global.isCountOnlineUser == '1'}<tr><td>{$PHP_LANG.public[48]} {$PHP_CONFIG.global.online}</td></tr>{/if}
          </table>
        </td>
      </tr>
    </table>
<!--  统计信息列表结束  -->

<!--  分类存档日期列表开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[50]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%">
            {section name=pigeonhole loop=$pigeonholes_list}
            <tr><td><a class="con" href="./index.php?play=calendar&date={$pigeonholes_list[pigeonhole].url}"> - {$pigeonholes_list[pigeonhole].text}</a></td></tr>
            {/section}
          </table>
        </td>
      </tr>
    </table>
<!--  分类存档日期列表结束  -->

<!--  用户登录单元开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[51]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%">
{if !$PHP_HAD_LOGIN}
            <form method="post" action="./admin/login.php">
            <tr>
              <td>
              {$PHP_LANG.public[52]} <input name="user" type="text" maxlength="20" class="input" size="13"><br />
              {$PHP_LANG.public[53]} <input name="passwd" type="password" maxlength="20" class="input" size="13"><br />
              {if $PHP_CONFIG.global.GDswitch == 1}{$PHP_LANG.public[71]} <input name="imgVal" type="text" maxlength="5" class="input" size="5"> <img src="./include/VerifyImage.php?type=login" align="absmiddle">{/if}
              <input type='hidden' name='action' value='actlogin'>
              <input type="submit" value="{$PHP_LANG.public[54]}" class="botton">
              <input type="reset" value="{$PHP_LANG.public[55]}" class="botton">
              </td>
            </tr>
            </form>
{else}
<tr>
  <td align=center>{$user_info.name} [<a href=./index.php?play=logout>{$PHP_LANG.index[30]}</a>]</td>
</tr>
{/if}
          </table>
        </td>
      </tr>
    </table>
<!--  用户登录单元结束  -->

<!--  最新BLOG列表开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[56]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%">
            {section name=last_blog loop=$last_articles_list}
            <tr><td><a class="con" href="./index.php?play=reply&id={$last_articles_list[last_blog].id}" title="{$last_articles_list[last_blog].title}"> - {$last_articles_list[last_blog].summytitle}</a></td></tr>
            {/section}
          </table>
        </td>
      </tr>
    </table>
<!--  最新BLOG列表结束  -->

<!--  最新评论列表开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[57]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%">
            {section name=last_comment loop=$last_comments_list}
            <tr><td><a class="con" href="./index.php?play=reply&id={$last_comments_list[last_comment].commentID}" title="{$last_comments_list[last_comment].content}"> - {$last_comments_list[last_comment].summycontent}</a></td></tr>
            {/section}
          </table>
        </td>
      </tr>
    </table>
<!--  最新评论列表结束  -->

<!--  最新trackback列表开始  -->
<!--
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[58]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%"><tr><td><a class="con" href="./index.php?play=reply&amp;id=1"> - 最近无引用内容</a></td></tr></table>
        </td>
      </tr>
    </table>
-->
<!--  最新trackback列表结束  -->

<!--  友情链接列表开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[59]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content">
          <table width="100%">
            {section name=last_link loop=$last_links_list}
            <tr><td><a class="con" href="./index.php?play=links&action=GoTo&id={$last_links_list[last_link].id}" title="{$last_links_list[last_link].description}" target="_blank">{$last_links_list[last_link].homepage}</a> ({$last_links_list[last_link].visits})</td></tr>
            {/section}
            <tr><td align="right"><a href="./index.php?play=links&action=Register">{$PHP_LANG.public[60]}</a></td></tr>
          </table>
        </td>
      </tr>
    </table>
<!--  友情链接列表结束  -->

<!--  搜索单元开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <form method="get" action="./search.php">
      <tr><td class="title"> {$PHP_LANG.public[61]}</td></tr>
      <tr>
        <td height="30" bgcolor="#DBEAF5" class="search">
          <input name="keyword" type="text" class="input" onfocus=this.select() onClick=this.value='' onmouseover=this.focus() value="{$PHP_LANG.public[64]}" size="16">
          <input type="submit" value="{$PHP_LANG.public[62]}" class="botton">
        </td>
      </tr>
      </form>
    </table>
<!--  搜索单元结束  -->

<!--  其他单元开始  -->
    <br>
    <table width="180"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#87b3d0">
      <tr><td class="title"> {$PHP_LANG.public[63]}</td></tr>
      <tr>
        <td bgcolor="#DBEAF5" class="content" align="center"><br />
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
          <!--  <script type="text/javascript" src="http://technorati.com/embed/kput6cqkkr.js"> </script> -->
        </td>
      </tr>
    </table>
<!--  其他单元结束  -->

    <br>
    </td>
    <td valign="top" bgcolor="#FFFFFF">
      <img src="{$PHP_CONFIG.global.tmpURL}/images/spacer.gif" width="577" height="10" border="0">