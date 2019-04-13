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
            <TR> 
              <TD colspan="2" align=middle bgColor=#ffffff> <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR> 
                      <TD class=smalltxt 
                        align=middle onmouseover="this.style.backgroundColor='#F8F8F8'" 
                      onmouseout="this.style.backgroundColor='#FFFFFF'"><div align="left"><br>{$settop_blogs_list[settop_blog].summarycontent}<BR>
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
                        align=middle ><div align="right">Posted by <a href="mailto:{$settop_blogs_list[settop_blog].email}" class="cn">{$settop_blogs_list[settop_blog].author}</a> 
                          in [{$settop_blogs_list[settop_blog].cnName}] | <a href="?play=reply&id={$settop_blogs_list[settop_blog].id}" class="cn">{$PHP_LANG.index[5]}{$settop_blogs_list[settop_blog].commentCount}</a> 
                          | {$PHP_LANG.index[6]}{$settop_blogs_list[settop_blog].visits} | {$PHP_LANG.index[7]}{$settop_blogs_list[settop_blog].trackbackCount} | <a href="./index.php?play=reply&id={$settop_blogs_list[settop_blog].id}" target="_blank"class="cn">{$PHP_LANG.index[29]}</a></div></TD>
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
                          in [{$blogs_list[blog].cnName}] | <a href="?play=reply&id={$blogs_list[blog].id}" class="cn">{$PHP_LANG.index[5]}{$blogs_list[blog].commentCount}</a> 
                          | {$PHP_LANG.index[6]}{$blogs_list[blog].visits} | {$PHP_LANG.index[7]}{$blogs_list[blog].trackbackCount} | <a href="./index.php?play=reply&id={$blogs_list[blog].id}" target="_blank"class="cn">{$PHP_LANG.index[29]}</a></div></TD>
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

<!--  ????  -->
</TD>
                      </TR>

                      <TR>
      <td height="30" class="multi"><span class="pagenum">{$counter.pages|string_format:$PHP_LANG.index[50]}</span> <span class="pagenum">{$counter.current_page|string_format:$PHP_LANG.index[49]}</span> <span class="pagenum">{if $counter.current_page > 1}<a href="{$PHP_VARIABLES.PHP_SELF}?page={math equation="x - 1" x=$counter.current_page}" class="cn">{$PHP_LANG.index[52]}</a>{/if}</span> <span class="pagenum">{if $counter.current_page < $counter.pages}<a href="{$PHP_VARIABLES.PHP_SELF}?page={math equation="x + 1" x=$counter.current_page}" class="cn">{$PHP_LANG.index[53]}</a>{/if}</span> <span class="pagenum"><a href="{$PHP_VARIABLES.PHP_SELF}" hidefocus=true>{$PHP_LANG.index[51]}</a></span> <span class="pagenum"><a href="{$PHP_VARIABLES.PHP_SELF}?page={$counter.pages}" hidefocus=true>{$PHP_LANG.index[54]}</a></span></td>
                      </TR>

{include file="footer.tpl"}