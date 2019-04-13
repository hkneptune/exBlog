{include file="header.tpl"}

<table width="92%" border="0" align="center" cellpadding="6" cellspacing="0" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
  <tr><td>{$PHP_INPUT.keyword|string_format:$PHP_LANG.search[11]}</td></tr>
</table>

{section name=blog loop=$blogs_list}
<TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
  <TBODY>
    <TR>
      <TD bgColor=#e6e6e6>
        <TABLE cellSpacing=1 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR class=header> 
              <TD width="57%"><strong><a href="?play=reply&id={$blogs_list[blog].id}&keyword={$PHP_INPUT.keyword}">{$blogs_list[blog].title}</a></strong></TD>
              <TD width="43%"><img src="./images/weather/{$blogs_list[blog].weather}.gif"> 
                &nbsp;&nbsp;&nbsp;{$blogs_list[blog].addtime|date_format:"%Y-%m-%d %H:%M:%S"}</TD>
            </TR>
            <TR> 
              <TD colspan="2" align=middle bgColor=#ffffff> <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR> 
                      <TD class=smalltxt 
                        align=middle onmouseover="this.style.backgroundColor='#F8F8F8'" 
                      onmouseout="this.style.backgroundColor='#FFFFFF'"><div align="left"><br>{$blogs_list[blog].summarycontent}<BR>

                        </div></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
            </TR>
            <TR> 
              <TD colspan="2" align=middle bgColor=#ffffff> <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR> 
                      <TD class=smalltxt 
                        align=middle ><div align="right">Posted by <a href="mailto:{$blogs_list[blog].email}" class="cn">{$blogs_list[blog].author}</a> 
                          in [{$blogs_list[blog].cnName}] | <a href="?play=reply&id={$blogs_list[blog].id}" class="cn">{$PHP_LANG.search[7]}{$blogs_list[blog].commentCount}</a> 
                          | {$PHP_LANG.search[8]}{$blogs_list[blog].visits} | {$PHP_LANG.search[9]}{$blogs_list[blog].trackbackCount} | <a href="./index.php?play=reply&id={$blogs_list[blog].id}" target="_blank"class="cn">{$PHP_LANG.search[10]}</a></div></TD>
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
{sectionelse}
<p align=center>{$PHP_LANG.index[48]}</p>
{/section}

<table width=90% cellpadding=2 cellspacing=2 border=0 align=center>
  <tr>
      <td height="30" class="multi"><span class="pagenum">{$counter.current_page|string_format:$PHP_LANG.search[6]}</span> <span class="pagenum">{$counter.pages|string_format:$PHP_LANG.search[5]}</span> <span class="pagenum">{if $counter.current_page > 1}<a href="{$PHP_VARIABLES.PHP_SELF}?page={math equation="x - 1" x=$counter.current_page}" class="cn">{$PHP_LANG.search[2]}</a>{/if}</span> <span class="pagenum">{if $counter.current_page < $counter.pages}<a href="{$PHP_VARIABLES.PHP_SELF}?page={math equation="x + 1" x=$counter.current_page}" class="cn">{$PHP_LANG.search[3]}</a>{/if}</span> <span class="pagenum"><a href="{$PHP_VARIABLES.PHP_SELF}" hidefocus=true>{$PHP_LANG.search[1]}</a></span> <span class="pagenum"><a href="{$PHP_VARIABLES.PHP_SELF}?page={$counter.pages}" hidefocus=true>{$PHP_LANG.search[4]}</a></span></td>
  </tr>
</table>
</TD>
                      </TR>

{include file="footer.tpl"}
