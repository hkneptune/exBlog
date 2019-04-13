<?php
/*==================================================================
*\    Plugin for exBlog (http://www.exBlog.net/)
*\------------------------------------------------------------------
*\    Copyright(C) 2003 - 2005 exSoft Team, All rights reserved
*\------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\------------------------------------------------------------------
*\    本页说明: TrackBack 插件 原形参考：Nucleus CMS
*\================================================================*/

if($charset == "") $charset = "gb2312";
if($dir == "") $dir = "ltr";
if($mode == "") $mode = "ltr";

# 通用信息 开始
# 你可以这样填写，以方便自己，推荐使用英文
# $lang[my_blog_name] = "MadCN.com";
# $lang[my_title] = "MadCN.com";
# $lang[my_url] = "http://www.madcn.com/blog/exurl.php/reply/{blogID}.html";
# $lang[my_excerpt] = "MadCN.com";
# 用不到就留空，来填写吧：

    $lang[my_blog_name] = "";
    $lang[my_title] = "";
    $lang[my_url] = "";
    $lang[my_excerpt] = "";

# 通用信息 结束

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 echo $charset; ?>">
<meta Name="author" content="exSoft">
<meta name="keywords" content="exSoft,exBlog,blog,PHP,MySQL,TrackBack">
<meta name="description" content="exBlog - Powered by exSoft">
<title>exBlog - Powered by exSoft</title>
<link href="../images/style.css" rel="stylesheet" type="text/css">
</head>
<body class="main" dir="<?php echo $dir; ?>">

<?php

if($action == ""){

    funindexform();

}elseif($action == "sendping"){

    $blog_name = htmlspecialchars($blog_name);
    $title = htmlspecialchars($title);
    $excerpt = htmlspecialchars($excerpt);

    $error = sendPing($title, $url, $excerpt, $blog_name, $ping_url);

        $showMsg = "$error";
        $showReturn = "./trackback.php";
		display($showMsg, $showReturn);

}elseif($action == "pingform"){

    if ($mode==1){
        $display="";
        echo "<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">";
    }else{
        $display="style=\"display:none;\"";
        echo "<form method=\"post\" action=\"".$ping_url."\" target=\"_blank\">";
    }

    funlangform ($charset);
    funpingform ($ping_url, $display, $lang);

}

# 显示预备页面 开始
function funindexform()
{
?>

<form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">

  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr> 
    <td><table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
          <tr> 
            <td colspan="2" class="menu"><div align="center">exBlog TrackBack 插件</div></td>
          </tr>
          <tr> 
            <td width="25%">TrackBack Ping URL</td>
            <td width="75%"><input name="ping_url" type="text" value="" class="input" size="55">
            </td>
          </tr>
          <tr> 
            <td>对方站点使用的编码</td>
            <td>
  <select name="charset">
  <option value="gb2312">Chinese_GB2312</option>
  <option value="big5">Chinese_Big5</option>
  <option value="utf-8">Unicode_UTF-8</option>
  </select>
            </td>
          </tr>
          <tr> 
            <td>对方站点文字阅读方向</td>
            <td>
  <select name="dir">
  <option value="ltr">从左到右</option>
  <option value="rtl">从右到左</option>
  </select>
            </td>
          </tr>
          <tr> 
            <td>发送模式</td>
            <td>
  <select name="mode">
  <option value="1">模式1</option>
  <option value="2">模式2</option>
  </select>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center"> 
<input type="hidden" name="action" value="pingform">
                <input type="submit" value="发送页面" class="botton">
                &nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="reset" value="重置数据" class="botton">
              </div></td>
          </tr>
          <tr> 
            <td colspan="2">
<b>发送 TrackBack Ping 提示</b>
<ol>
  <li>请填入对方正确的 TrackBack Ping URL </li>
  <li>选择对方的编码，因为对方和你可能使用不同的编码</li>
  <li>选择对方站点文字阅读方向，一般情况下，这个你不用管</li>
  <li>发送模式，这个你不用管，如果没有发送成功再改改看</li>
  <li>点击 发送页面 进入 发送页面 ，进一步完成发送</li>
</ol>
</td>
          </tr>
        </table></td>
  </tr>
</table>

</form>

<?php
}
# 显示预备页面 结束

# 显示发送页面 开始
function funpingform($ping_url, $display, $lang)
{
?>

  <table width="540" border="0" cellpadding="0" cellspacing="0" class="border">
    <tr> 
    <td><table width="100%" border="0" cellpadding="1" cellspacing="1" class="main">
          <tr> 
            <td colspan="2" class="menu"><div align="center"><?php echo $lang[tbform]; ?></div></td>
          </tr>
          <tr <?php echo $display; ?>> 
            <td width="25%">TrackBack Ping URL</td>
            <td width="75%"> <input name="ping_url" type="text" value="<?php echo $ping_url; ?>" class="input" size="60"> 
            </td>
          </tr>
          <tr> 
            <td width="25%"><?php echo $lang[blog_name]; ?></td>
            <td width="75%"> <input name="blog_name" type="text" value="<?php echo $lang[my_blog_name]; ?>" class="input" size="60"> 
            </td>
          </tr>
          <tr>
            <td><?php echo $lang[title]; ?></td>
            <td> <input name="title" type="text" value="<?php echo $lang[my_title]; ?>" class="input" size="60"> 
            </td>
          </tr>
          <tr> 
            <td><?php echo $lang[url]; ?></td>
            <td><input name="url" type="text" class="input" id="age" value="<?php echo $lang[my_url]; ?>" size="60"></td>
          </tr>
          <tr> 
            <td valign="top">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td valign="top"><?php echo $lang[excerpt]; ?></td>
            <td><textarea name="excerpt" cols="60" rows="8" wrap="VIRTUAL" class="input"><?php echo $lang[my_excerpt]; ?></textarea>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center"> 
<input type="hidden" name="action" value="sendping">
                <input type="submit" value="<?php echo $lang[send]; ?>" class="botton">
                &nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="reset" value="<?php echo $lang[reset]; ?>" class="botton">
              </div></td>
          </tr>
        </table></td>
  </tr>
</table>

</form>

<?php
}
# 显示发送页面 结束





# 语言 开始
function funlangform($charset)
{ global $lang;
    if ($charset == "gb2312") {
$lang[tbform] = "TrackBack Ping 发送页面";
$lang[blog_name] = "你的 Blog 名称";
$lang[title] = "你的文章标题";
$lang[url] = "你的文章固定地址";
$lang[excerpt] = "你的文章摘要<br /><br />字数不要太多<br />不要使用HTML<br />UBB也不要用";
$lang[send] = "我要发送";
$lang[reset] = "重置数据";
$lang[reset] = "重置数据";
    } else {
$lang[tbform] = "Send TrackBack Ping";
$lang[blog_name] = "your Blog name";
$lang[title] = "title";
$lang[url] = "url";
$lang[excerpt] = "excerpt";
$lang[send] = "Send";
$lang[reset] = "Reset";
    }
}
# 语言 结束

# 发送 开始
#################
# 发送 ping ,如果出错就返回错误信息
#################
	function sendPing($title, $url, $excerpt, $blog_name, $ping_url) {
      // 检测
      if ( $ping_url == '' )
         return '没有 ping URL (No ping URL)';

      $parsed_url = parse_url( $ping_url );

      // error: bad url
      if ( $parsed_url['scheme'] != 'http' ||   $parsed_url['host'] == '' )
         return '错误的 ping URL (Bad ping URL)';

      // 推测端口数 guess port number
      $port = ( $parsed_url['port'] ) ? $parsed_url['port'] : 80;

      // 创建内容 create contents
      $content  = 'title=' . urlencode( $title );
      $content .= '&url=' . urlencode( $url );
      $content .= '&excerpt=' . urlencode( $excerpt );
      $content .= '&blog_name=' . urlencode( $blog_name );

      $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';

      // 创建 HTTP 请求 create HTTP request
      $request  = 'POST ' . $parsed_url['path'];
      if ( $parsed_url['query'] != '' )
         $request .= '?' . $parsed_url['query'];
      $request .= " HTTP/1.1\r\n";
      $request .= "Accept: */*\r\n";
      $request .= "User-Agent: " . $user_agent . "\r\n";
      $request .=   "Host: " . $parsed_url['host'] . ":" . $port . "\r\n";
      $request .= "Connection: Keep-Alive\r\n";
      $request .= "Cache-Control: no-cache\r\n";
      $request .= "Connection: Close\r\n";
      $request .=   "Content-Length: " . strlen( $content ) . "\r\n";
      $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $request .= "\r\n";
      $request .= $content;

      $socket = fsockopen( $parsed_url['host'], $port, $errno, $errstr );
      // 检测 socket 是否连接成功
      if ( ! $socket )
         return '无法发送 (Could not send ping): '.$errstr.' ('.$errno.')';

      // 发送请求 send request
      fputs( $socket, $request );

      // 接收应答 recieve response
      $result = '';
      while ( ! feof ( $socket ) ) {
         $result .= fgets( $socket, 4096 );
      }
      // 关闭 socket 连接
      fclose( $socket );

      // 检测错误级别
      // instead of parsing the XML, just check for the error string
      // [TODO] extract real error message and return that
      if ( strstr($result,'<error>1</error>') )
         //return 'TrackBack Ping 发送出错: '.htmlspecialchars($result);
         return 'TrackBack Ping 发送出错！';
      if ( strstr($result,'<error>0</error>') )
         return 'TrackBack Ping 发送成功！';
      if ( !strstr($result,'<error>') )
         return 'TrackBack Ping 发送失败！';
   }
# 发送 结束

# 对话框页面 开始
function display($iShowMsg,$iShowReturn)
{
?>
<html><head>
<style type="text/css">
.menu { font-size: 8pt; font-weight: bold; }
.content { font-size: 8pt; }
.border { border: 1px #000000 dotted; }
a:visited {  color: #000000; text-decoration: none }
a:link {  color: #000000; text-decoration: none }
a:hover {  color: blue; text-decoration: underline }
</style>
<title>发送结果</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta HTTP-EQUIV="REFRESH" CONTENT="2;URL=<? echo "$iShowReturn"; ?>">
</head>
<body dir="ltr">
<div id="msgboard" style="position:absolute; left:200px; top:150px; width:350px; height:100px; z-index:1;">
<table cellspacing="0" cellpadding="5" width="100%" class="border">
<tr bgcolor="#99CC66">
<td height="11" align="left" bgcolor="#E1E4CE" class="menu">发送结果</td></tr><tr>
<td align="left" class="content"><font color="red"><? echo "$iShowMsg"; ?></font> 
<p align="left"><a href="<? echo "$iShowReturn"; ?>">返回</a></p></td></tr>
</table>
</div>
</body></html>
<?php
    exit;
}
# 对话框页面 结束

?>

