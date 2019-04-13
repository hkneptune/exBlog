<html>
<head>
<link href="{$PHP_CONFIG.global.tmpURL}/style/styles.css" rel="stylesheet" type="text/css">
<title>{$PHP_LANG.public[10]}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$PHP_LANG.public.charset}" />
<meta http-equiv="Content-Language" content="{$PHP_LANG.public.language}" />
</head>
<body dir="{$PHP_LANG.public.dir}">
<div id="msgboard" style="position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;">
<table cellspacing="0" cellpadding="5" width="100%" border="0">
  <tr><td  class="blogtitle" height="20" align="left">{$PHP_LANG.public[11]}</td></tr>
  <tr><td align="left" class="blogcontent">
    <ul>
    {section name=error_id loop=$messages}
      <li></li><font color="red">{$messages[error_id]}</font>
    {/section}
    </ul>
  </td></tr>
  <tr><td align=center class="blogcontent"><a href="javascript:history.go(-1);">{$PHP_LANG.public[12]}</a></td></tr>
</table>
</div>
</body>
</html>