{include file="header.tpl"}

{section name=settop_blog loop=$settop_blogs_list}
<TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
  <TBODY>
    <TR>
      <TD bgColor=#e6e6e6>
        <TABLE cellSpacing=1 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR class=header> 
              <TD width="57%"><strong><span style="color: red;">{$PHP_LANG.index[28]}</span><a href="?play=reply&id={$settop_blogs_list[settop_blog].id}">{$settop_blogs_list[settop_blog].title}</a></strong></TD>
              <TD width="43%"><img src="./images/weather/{$settop_blogs_list[settop_blog].weather}.gif"> 
                &nbsp;&nbsp;&nbsp;{$settop_blogs_list[settop_blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"}</TD>
            </TR>
          </TBODY>
        </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>
<br>
{/section}

{section name=blog loop=$blogs_list}
<TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
  <TBODY>
    <TR>
      <TD bgColor=#e6e6e6>
        <TABLE cellSpacing=1 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR class=header> 
              <TD width="57%"><strong><a href="?play=reply&id={$blogs_list[blog].id}">{$blogs_list[blog].title}</a></strong></TD>
              <TD width="43%"><img src="./images/weather/{$blogs_list[blog].weather}.gif"> 
                &nbsp;&nbsp;&nbsp;{$blogs_list[blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"}</TD>
            </TR>
          </TBODY>
        </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>
<br>
{sectionelse}
<p align=center>{$PHP_LANG.index[48]}</p>
{/section}

<table width=90% cellpadding=2 cellspacing=2 border=0 align=center>
  <tr>
      <td height="30" class="multi"><span class="pagenum">{$counter.current_page|string_format:$PHP_LANG.index[50]}</span> <span class="pagenum">{$counter.pages|string_format:$PHP_LANG.index[49]}</span> <span class="pagenum">{if $counter.current_page > 1}<a href="javascript:changeQuery('page', {math equation="x - 1" x=$counter.current_page});" class="cn">{$PHP_LANG.index[52]}</a>{/if}</span> <span class="pagenum">{if $counter.current_page < $counter.pages}<a href="javascript:changeQuery('page', {math equation="x + 1" x=$counter.current_page});" class="cn">{$PHP_LANG.index[53]}</a>{/if}</span> <span class="pagenum"><a href="javascript:changeQuery('page', 1);" hidefocus=true>{$PHP_LANG.index[51]}</a></span> <span class="pagenum"><a href="javascript:changeQuery('page', {$counter.pages});" hidefocus=true>{$PHP_LANG.index[54]}</a></span></td>
  </tr>
</table>
</TD>
                      </TR>

{include file="footer.tpl"}
