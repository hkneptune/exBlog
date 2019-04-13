<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
<meta http-equiv="Content-Language" content="{$language}" />
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>{$global.siteName} - {$announce_info.title}</title>
</head>
<body>
<p align="center">{$PHP_LANG.index[26]}</p>
<link href="./{$PHP_CONFIG.global.tmpURL}/style/styles.css" rel="stylesheet" type="text/css">
<br>
<TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
  <TBODY>
    <TR>
      <TD bgColor=#e6e6e6>
        <TABLE cellSpacing=1 cellPadding=4 width="100%" border=0>
          <TBODY>
            <TR class=header><TD><strong>{$announce_info.title}</strong></TD></TR>
            <TR>
              <TD align=middle bgColor=#ffffff>
                <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR><TD class=smalltxt align=middle onmouseover="this.style.backgroundColor='#F8F8F8'" onmouseout="this.style.backgroundColor='#FFFFFF'"><div align="left"><br>{$announce_info.content}<BR></div></TD></TR>
                  </TBODY>
              </TABLE></TD>
            </TR>
            <TR>
              <TD align=middle bgColor=#ffffff>
                <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR>
                      <TD class=smalltxt align=middle><div align="right">Posted by <a href="mailto:{$announce_info.email}" class="cn">{$announce_info.author}</a>
                          at {$announce_info.addtime|date_format:"%Y-%m-%d %H:%M:%S"}</div></TD>
                    </TR>
                  </TBODY>
              </TABLE></TD>
            </TR>
          </TBODY>
      </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="10">
  <tr>
    <td align="center"><input name="button" type=button class="botton" onclick=window.close() value="{$PHP_LANG.index[27]}"></td>
  </tr>
</table>
</BODY>
</html>