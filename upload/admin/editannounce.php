<?php
/*============================================================================
*\    exBlog Ver: 1.2.0 RC1 exBlog 网络日志(PHP+MYSQL) 1.2.0 RC1 版
*\----------------------------------------------------------------------------
*\    Copyright(C) exSoft Studio, 2004. All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.fengling.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 添加&编辑&删除公告页面
*\===========================================================================*/

require("../include/config.inc.php");
require("../include/global-B.inc.php");
require("../include/global-C.inc.php");
//---- 查询数据库表名 ----//
setGlobal();

checkLogin();       ### 检查用户是否非法登录
checkUserUid($_SESSION[exPass]);     ### 检查用户权限

$aboutAuthor=splitInfo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta Name="author" content="elliott,hunter">
<meta name="keywords" content="elliott,exBlog,blog,PHP,MySQL">
<meta name="description" content="exBlog - Powered by elliott">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>{title}</title>
<script src=addubb.js></script>
<link href="../images/admin.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<form method="post" action="<? echo $PHP_SELF; ?>" name="exEdit">
<script language="JavaScript">
    function exEdit(iEdit) {
    document.exEdit.content.value += iEdit+" ";
    document.exEdit.content.focus();
}
</script>
<script language="JavaScript">
var text_input = "文字";
var text_enter_url = "请输入连接网址";
var frame_help = "请输入想倒入框架的URL地址"
var help_mode = "exBlog 代码 - 帮助信息\n\n点击相应的代码按钮即可获得相应的说明和提示";
var adv_mode = "exBlog 代码  - 直接插入\n\n点击代码按钮后不出现提示即直接插入相应代码";
var normal_mode = "exBlog 代码  - 提示插入\n\n点击代码按钮后出现向导窗口帮助您完成代码插入";
var email_help = "插入邮件地址\n\n插入邮件地址连接。\n例如：\n[email]exblog@fengling.net[/email]\n[email=exblog@fengling.net]elliot[/email]";
var email_normal = "请输入链接显示的文字，如果留空则直接显示邮件地址。";
var email_normal_input = "请输入邮件地址。";
var fontsize_help = "设置字号\n\n将标签所包围的文字设置成指定字号。\n例如：[size=3]文字大小为 3[/size]";
var fontsize_normal = "请输入要设置为指定字号的文字。";
var font_help = "设定字体\n\n将标签所包围的文字设置成指定字体。\n例如：[font=仿宋]字体为仿宋[/font]";
var font_normal = "请输入要设置成指定字体的文字。";
var bold_help = "插入粗体文本\n\n将标签所包围的文本变成粗体。\n例如：[b]Exsoft team[/b]";
var bold_normal = "请输入要设置成粗体的文字。";
var italicize_help = "插入斜体文本\n\n将标签所包围的文本变成斜体。\n例如：[i]Exsoft team[/i]";
var italicize_normal = "请输入要设置成斜体的文字。";
var quote_help = "插入引用\n\n将标签所包围的文本作为引用特殊显示。"
var quote_normal = "请输入要作为引用显示的文字。";
var color_help = "定义文本颜色\n\n将标签所包围的文本变为制定颜色。\n例如：[color=red]红颜色[/color]";
var color_normal = "请输入要设置成指定颜色的文字。";
var center_help = "居中对齐\n\n将标签所包围的文本居中对齐显示。\n例如：[align=center]内容居中[/align]";
var center_normal = "请输入要居中对齐的文字。";
var link_help = "插入超级链接\n\n插入一个超级连接。\n例如：\n[url]http://kiki.class07.com[/url]\n[url=http://kiki.class07.com]Exsoft team[/url]";
var link_normal = "请输入链接显示的文字，如果留空则直接显示链接。";
var link_normal_input = "请输入 URL。";
var image_help = "插入图像\n\n在文本中插入一幅图像。"
var image_normal = "请输入图像的 URL。";
var flash_help = "插入 flash\n\n在文本中插入 flash 动画。"
var flash_normal = "请输入 flash 动画的 URL。";
var code_help = "插入代码\n\n插入程序或脚本原始代码。\n例如：[code]echo\"这里是我的BLOG\";[/code]";
var code_normal = "请输入要插入的代码。";
var list_help = "插入列表\n\n插入可由浏览器显示来的规则列表项。\n例如：\n[list]\n[*]；列表项 #1\n[*]；列表项 #2\n[*]；列表项 #3\n[/list]";
var list_normal = "请选择列表格式：字母式列表输入 \"A\"；数字式列表输入 \"1\"。此处也可留空。";
var list_normal_error = "错误：列表格式只能选择输入 \"A\" 或 \"1\"。";
var list_normal_input = "请输入列表项目内容，如果留空表示项目结束。";
var underline_help = "插入下划线\n\n给标签所包围的文本加上下划线。\n例如：[u]Exsoft team[/u]";
var underline_normal = "请输入标注下划线的文字内容";
defmode = "normalmode";		// default mode (normalmode, advmode, helpmode)

if (defmode == "advmode") {
        helpmode = false;
        normalmode = false;
        advmode = true;
} else if (defmode == "helpmode") {
        helpmode = true;
        normalmode = false;
        advmode = false;
} else {
        helpmode = false;
        normalmode = true;
        advmode = false;
}
function chmode(swtch){
        if (swtch == 1){
                advmode = false;
                normalmode = false;
                helpmode = true;
                alert(help_mode);
        } else if (swtch == 0) {
                helpmode = false;
                normalmode = false;
                advmode = true;
                alert(adv_mode);
        } else if (swtch == 2) {
                helpmode = false;
                advmode = false;
                normalmode = true;
                alert(normal_mode);
        }
}
function AddText(NewCode) {
        if(document.all){
        	insertAtCaret(document.exEdit.content, NewCode);
        	setfocus();
        } else{
        	document.exEdit.content.value += NewCode;
        	setfocus();
        }
}

function storeCaret (textEl){
        if(textEl.createTextRange){
                textEl.caretPos = document.selection.createRange().duplicate();
        }
}

function insertAtCaret (textEl, text){
        if (textEl.createTextRange && textEl.caretPos){
                var caretPos = textEl.caretPos;
                caretPos.text += caretPos.text.charAt(caretPos.text.length - 2) == ' ' ? text + ' ' : text;
        } else if(textEl) {
                textEl.value += text;
        } else {
        	textEl.value = text;
        }
}
function frame()  {  
		if (helpmode) {
			alert(frame_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[iframe]" + range.text + "[/iframe]";
		 } else if (advmode) {
                AddTxt="[iframe] [/iframe]";
                AddText(AddTxt);
	        } else {  
                txt=prompt(text_enter_url,"");     
                if (txt!=null) {           
                        AddTxt="[iframe]"+txt;
                        AddText(AddTxt);
                        AddText("[/iframe]");
                }       
        }
}
function chsize(size) {
        if (helpmode) {
                alert(fontsize_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[size=" + size + "]" + range.text + "[/size]";
        } else if (advmode) {
                AddTxt="[size="+size+"] [/size]";
                AddText(AddTxt);
        } else {                       
                txt=prompt(fontsize_normal,text_input); 
                if (txt!=null) {             
                        AddTxt="[size="+size+"]"+txt;
                        AddText(AddTxt);
                        AddText("[/size]");
                }        
        }
}

function chfont(font) {
        if (helpmode){
                alert(font_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[font=" + font + "]" + range.text + "[/font]";
        } else if (advmode) {
                AddTxt="[font="+font+"] [/font]";
                AddText(AddTxt);
        } else {                  
                txt=prompt(font_normal,text_input);
                if (txt!=null) {             
                        AddTxt="[font="+font+"]"+txt;
                        AddText(AddTxt);
                        AddText("[/font]");
                }        
        }  
}
function italicize() {
        if (helpmode) {
                alert(italicize_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[i]" + range.text + "[/i]";
        } else if (advmode) {
                AddTxt="[i] [/i]";
                AddText(AddTxt);
        } else {   
                txt=prompt(italicize_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[i]"+txt;
                        AddText(AddTxt);
                        AddText("[/i]");
                }               
        }
}

function quote() {
        if (helpmode){
                alert(quote_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[quote]" + range.text + "[/quote]";
        } else if (advmode) {
                AddTxt="\r[quote]\r[/quote]";
                AddText(AddTxt);
        } else {   
                txt=prompt(quote_normal,text_input);     
                if(txt!=null) {          
                        AddTxt="\r[quote]\r"+txt;
                        AddText(AddTxt);
                        AddText("\r[/quote]");
                }               
        }
}

function chcolor(color) {
        if (helpmode) {
                alert(color_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[color=" + color + "]" + range.text + "[/color]";
        } else if (advmode) {
                AddTxt="[color="+color+"] [/color]";
                AddText(AddTxt);
        } else {  
        txt=prompt(color_normal,text_input);
                if(txt!=null) {
                        AddTxt="[color="+color+"]"+txt;
                        AddText(AddTxt);
                        AddText("[/color]");
                }
        }
}

function bold() {
        if (helpmode) {
                alert(bold_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[b]" + range.text + "[/b]";
        } else if (advmode) {
                AddTxt="[b] [/b]";
                AddText(AddTxt);
        } else {  
                txt=prompt(bold_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[b]"+txt;
                        AddText(AddTxt);
                        AddText("[/b]");
                }       
        }
}
function center() {
        if (helpmode) {
                alert(center_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[center]" + range.text + "[/center]";
        } else if (advmode) {
                AddTxt="[align=center] [/align]";
                AddText(AddTxt);
        } else {  
                txt=prompt(center_normal,text_input);     
                if (txt!=null) {          
                        AddTxt="\r[align=center]"+txt;
                        AddText(AddTxt);
                        AddText("[/align]");
                }              
        }
}

function hyperlink() {
        if (helpmode) {
                alert(link_help);
		} else if (document.selection && document.selection.type == "Text") {
				var range = document.selection.createRange();
				range.text = "[url]" + range.text + "[/url]";
		} else if (advmode) {
                AddTxt="[url] [/url]";
                AddText(AddTxt);
        } else { 
                txt2=prompt(link_normal,""); 
                if (txt2!=null) {
                        txt=prompt(link_normal_input,"http://");      
                        if (txt!=null) {
                                if (txt2=="") {
                                        AddTxt="[url]"+txt;
                                        AddText(AddTxt);
                                        AddText("[/url]");
                                } else {
                                        AddTxt="[url="+txt+"]"+txt2;
                                        AddText(AddTxt);
                                        AddText("[/url]");
                                }         
                        } 
                }
        }
}

function tag_email() {
        if (helpmode) {
                alert(email_help);
		} else if (document.selection && document.selection.type == "Text") {
				var range = document.selection.createRange();
				range.text = "[email]" + range.text + "[/email]";
		} else if (advmode) {
				AddTxt="[email] [/email]";
                AddText(AddTxt);
		} else { 
                txt2=prompt(email_normal,""); 
                if (txt2!=null) {
                        txt=prompt(email_normal_input,"name@domain.com");      
                        if (txt!=null) {
                                if (txt2=="") {
                                        AddTxt="[email]"+txt;
                                        AddText(AddTxt);
                                        AddText("[/email]");
                				} else {
                                        AddTxt="[email="+txt+"]"+txt2;
                                        AddText(AddTxt);
                                        AddText("[/email]");
                                }                    
                        }
                }
        }
}

function image() {
        if (helpmode){
                alert(image_help);
        } else if (advmode) {
                AddTxt="[img] [/img]";
                AddText(AddTxt);
        } else {  
                txt=prompt(image_normal,"http://");    
                if(txt!=null) {            
                        AddTxt="\r[img]"+txt;
                        AddText(AddTxt);
                        AddText("[/img]");
                }       
        }
}

function flash() {
        if (helpmode){
                alert(flash_help);
        } else if (advmode) {
                AddTxt="[swf] [/swf]";
                AddText(AddTxt);
        } else {  
                txt=prompt(flash_normal,"http://");    
                if(txt!=null) {            
                        AddTxt="\r[swf]"+txt;
                        AddText(AddTxt);
                        AddText("[/swf]");
                }       
        }
}

function code() {
        if (helpmode) {
                alert(code_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[code]" + range.text + "[/code]";
        } else if (advmode) {
                AddTxt="\r[code]\r[/code]";
                AddText(AddTxt);
        } else {   
                txt=prompt(code_normal,"");     
                if (txt!=null) {          
                        AddTxt="\r[code]"+txt;
                        AddText(AddTxt);
                        AddText("[/code]");
                }              
        }
}

function list() {
        if (helpmode) {
                alert(list_help);
        } else if (advmode) {
                AddTxt="\r[list]\r[*]\r[*]\r[*]\r[/list]";
                AddText(AddTxt);
        } else {  
                txt=prompt(list_normal,"");
                while ((txt!="") && (txt!="A") && (txt!="a") && (txt!="1") && (txt!=null)) {
                        txt=prompt(list_normal_error,"");               
                }
                if (txt!=null) {
                        if (txt=="") {
                                AddTxt="\r[list]\r\n";
                        } else {
                                AddTxt="\r[list="+txt+"]\r";
                        } 
                        txt="1";
                        while ((txt!="") && (txt!=null)) {
                                txt=prompt(list_normal_input,""); 
                                if (txt!="") {             
                                        AddTxt+="[*]"+txt+"\r"; 
                                }                   
                        } 
                        AddTxt+="[/list]\r\n";
                        AddText(AddTxt); 
                }
        }
}

function underline() {
        if (helpmode) {
                alert(underline_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[u]" + range.text + "[/u]";
        } else if (advmode) {
                AddTxt="[u] [/u]";
                AddText(AddTxt);
        } else {  
                txt=prompt(underline_normal,text_input);
                if (txt!=null) {           
                        AddTxt="[u]"+txt;
                        AddText(AddTxt);
                        AddText("[/u]");
                }               
        }
}

function wmv()  { 
			txt=prompt("Wmv文件的地址","请输入");
			if (txt!=null) {
					AddTxt="[wmv]"+txt;
					AddText(AddTxt);
					AddTxt="[/wmv]";
					AddText(AddTxt);
				}         
}
	
function rm()  { 
		txt2=prompt("视频的宽度，高度","300,200"); 
		if (txt2!=null) {
			txt=prompt("视频文件的地址","请输入");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[rm=500,350]"+txt;
					AddText(AddTxt);
					AddTxt="[/rm]";
					AddText(AddTxt);
				} else {
					AddTxt="[rm="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/rm]";
					AddText(AddTxt);
				}         
			} 
		}
}
	
function setfocus() {
        document.exEdit.content.focus();
}
</script>	
<?php
    if($_POST['action'] == "添加公告")
	{
	    addAnnounce($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email']);
	}
	elseif($_GET['action'] == "delete")
	{
		deleteAnnounce($_GET['id']);
	}
	elseif($_POST['action'] == "修改公告")
	{
		UpdateAnnounce($_POST['title'], $_POST['content'], $_POST['author'], $_POST['email'], $_POST['iID']);
	}
    if($_GET['action'] == "add")
	{
?>
  <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr>
      <td class="a1" colspan=3 height=25 align="center"><b>添加公告</b></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告标题</td>
      <td width="80%" colspan="2"><input name="title" type="text" size="40" class="input">
</td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告作者</td>
      <td width="80%" colspan="2"><input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input"></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=98 rowspan="2" align="center">辅助功能</td>
      <td width="80%" colspan="2">
	     <table border="0" align="left" cellpadding="1" cellspacing="1">
           <tr> 
            <td><a href="javascript:exEdit(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_8:')"><img src="../images/ex_pose/ex_8.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_9:')"><img src="../images/ex_pose/ex_9.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_10:')"><img src="../images/ex_pose/ex_10.gif" width="20" height="20" border="0"></a></td>
          </tr>
       <tr> 
            <td><img  onclick=code()  src="../images/ex_code/ex_code.gif" alt="插入代码" width="23" height="22" border="0"></td>
            <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="插入引用" width="23" height="22" border="0"></td>
            <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="插入框架" width="23" height="22" border="0"></td>
            <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="插入email" width="23" height="22" border="0"></td>
            <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="插入超级链接" width="23" height="22" border="0"></td>
            <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="插入图像链接" width="23" height="22" border="0"></td>
            <td><img  onclick=bold()   src="../images/ex_code/ex_bold.gif" alt="插入粗体字" width="23" height="22" border="0"></td>
            <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="插入斜体字" width="23" height="22" border="0"></td>
       		<td><img  onclick=list()  src="../images/ex_code/ex_list.gif" alt="插入列表" width="23" height="22" border="0"></td>
			<td><img  onclick=center()  src="../images/ex_code/ex_center.gif" alt="文字局中" width="23" height="22" border="0"></td>
			<td><img  onclick=underline()  src="../images/ex_code/ex_underline.gif" alt="插入下划线文字" width="23" height="22" border="0"></td>
			<td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="插入Flash文件" width="23" height="22" border="0"></td>
			<td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="插入rm视频文件" width="23" height="22" border="0"></td>
			<td><img  onclick=wmv()  src="../images/ex_code/ex_wma.gif" alt="插入wma视频" width="23" height="22" border="0"></td>
	</tr>
	    </table>
		</td>
    </tr>
    <tr class=a4>
      <td colspan="2"><table>
		<tr>
			<td width="190">字体设置          
			   <select name="font" onFocus="this.selectedIndex=0" onChange="chfont(this.options[this.selectedIndex].value)" size="1">
               <option value="宋体">宋体</option>
               <option value="黑体">黑体</option>
               <option value="Arial">Arial</option>
               <option value="Book Antiqua">Book Antiqua</option>
               <option value="Century Gothic">Century Gothic</option>
               <option value="Courier New">Courier New</option>
               <option value="Georgia">Georgia</option>
               <option value="Impact">Impact</option>
               <option value="Tahoma">Tahoma</option>
               <option value="Times New Roman">Times New Roman</option>
               <option value="Verdana" selected>Verdana</option>
             	</select>
			</td>
			<td width="50">
           	<select name="size" onFocus="this.selectedIndex=2" onChange="chsize(this.options[this.selectedIndex].value)" size="1">
			<option value="-2">-2</option>
			<option value="-1">-1</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected>3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option></select>
			</td>
			<td width="85">
           <select name="color" onFocus="this.selectedIndex=1" onChange="chcolor(this.options[this.selectedIndex].value)" size="1">
			<option value="White" style="color:white;">White</option>
			<option value="Black" style="color:black;">Black</option>
			<option value="Red" style="color:red;">Red</option>
			<option value="Yellow" style="color:yellow;">Yellow</option>
			<option value="Pink" style="color:pink;">Pink</option>
			<option value="Green" style="color:green;">Green</option>
			<option value="Orange" style="color:orange;">Orange</option>
			<option value="Purple" style="color:purple;">Purple</option>
			<option value="Blue" style="color:blue;">Blue</option>
			<option value="Beige" style="color:beige;">Beige</option>
			<option value="Brown" style="color:brown;">Brown</option>
			<option value="Teal" style="color:teal;">Teal</option>
			<option value="Navy" style="color:navy;">Navy</option>
			<option value="Maroon" style="color:maroon;">Maroon</option>
			<option value="LimeGreen" style="color:limegreen;">LimeGreen</option></select>
			</td>
    	</tr>
   </table></td>
    </tr>    
    <tr class=a4>
      <td width="20%" height=23 rowspan="2" align="center">公告内容</td>
      <td width="60%" rowspan="2"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"></textarea></td>
      <td width="20%" height="37"><div align="center">代码插入方式：</div></td>
    </tr>
    <tr class=a4>
      <td><div align="center">
          <span>
      <input type="radio" name="mode" value="2" onclick="chmode('2')" checked> 
      提示插入<br>
      <input type="radio" name="mode" value="0" onclick="chmode('0')"> 
      直接插入<br>
      <input type="radio" name="mode" value="1" onclick="chmode('1')"> 
      帮助信息
          </span>
	  </div></td>
    </tr>
    <tr align="center" class=a4>
      <td height=30 colspan="3">
	  <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
	  <input type="submit" name="action" value="添加公告" class="botton">
	  </td>
    </tr>
  </table>
  <?php
    }
	elseif($_GET[action] == "edit")
	{
		checkUserUid($_SESSION[exPass]);     ### 检查用户权限
		$resultAnnounce=selectAnnounceModify();                  ### 显示文章列表
?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1" class="a2">
    <tr align="center">
      <td height="25" colspan="3" class="a1"><b>编辑公告</b></td>
    </tr>
    <?php
    while($editAnnounce=mysql_fetch_array($resultAnnounce))
	{
?>
    <tr class="a4">
      <td width="100" height="23" align="center">&nbsp;id:[<? echo "$editAnnounce[id]"; ?>]</td>
      <td><? echo "$editAnnounce[title]" ?></td>
      <td width="100" align="center"><a href="./editannounce.php?action=modify&id=<? echo "$editAnnounce[id]"?>">[编辑]</a><a href="./editannounce.php?action=delete&id=<? echo "$editAnnounce[id]"?>">[删除]</a></td>
    </tr>
    <? } ?>
    <tr class="a4">
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>
  <? }
   else if($_GET[action] == "modify")
   { 
	   checkUserUid($_SESSION[exPass]);     ### 检查用户权限
       selectEditAnnounce($_GET[id]);           ### 查询当前待编辑的链接信息
	   //print_r($resultAnnounce);
?>
   
 <table cellpadding="4" cellspacing="1" border="0" width="95%" align=center class="a2">
    <tr>
      <td class="a1" colspan=3 height=25 align="center"><b>添加公告</b></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告标题</td>
      <td width="80%" colspan="2"><input name="title" type="text" size="40" value="<? echo $resultAnnounce['title']; ?>" class="input">
</td>
    </tr>
    <tr class=a4>
      <td width="20%" height=23 align="center">公告作者</td>
      <td width="80%" colspan="2"><input name="author" type="text" value="<? echo $aboutAuthor['0']; ?>" class="input"></td>
    </tr>
    <tr class=a4>
      <td width="20%" height=98 rowspan="2" align="center">辅助功能</td>
      <td width="80%" colspan="2">
	     <table border="0" align="left" cellpadding="1" cellspacing="1">
           <tr> 
            <td><a href="javascript:exEdit(':ex_0:')"><img src="../images/ex_pose/ex_0.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_1:')"><img src="../images/ex_pose/ex_1.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_2:')"><img src="../images/ex_pose/ex_2.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_3:')"><img src="../images/ex_pose/ex_3.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_4:')"><img src="../images/ex_pose/ex_4.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_5:')"><img src="../images/ex_pose/ex_5.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_6:')"><img src="../images/ex_pose/ex_6.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_7:')"><img src="../images/ex_pose/ex_7.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_8:')"><img src="../images/ex_pose/ex_8.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_9:')"><img src="../images/ex_pose/ex_9.gif" width="20" height="20" border="0"></a></td>
            <td><a href="javascript:exEdit(':ex_10:')"><img src="../images/ex_pose/ex_10.gif" width="20" height="20" border="0"></a></td>
          </tr>
       <tr> 
            <td><img  onclick=code()  src="../images/ex_code/ex_code.gif" alt="插入代码" width="23" height="22" border="0"></td>
            <td><img  onclick=quote()  src="../images/ex_code/ex_quote.gif" alt="插入引用" width="23" height="22" border="0"></td>
            <td><img  onclick=frame()  src="../images/ex_code/ex_iframe.gif" alt="插入框架" width="23" height="22" border="0"></td>
            <td><img  onclick=tag_email()  src="../images/ex_code/ex_email.gif" alt="插入email" width="23" height="22" border="0"></td>
            <td><img  onclick=hyperlink()  src="../images/ex_code/ex_url.gif" alt="插入超级链接" width="23" height="22" border="0"></td>
            <td><img  onclick=image()  src="../images/ex_code/ex_image.gif" alt="插入图像链接" width="23" height="22" border="0"></td>
            <td><img  onclick=bold()   src="../images/ex_code/ex_bold.gif" alt="插入粗体字" width="23" height="22" border="0"></td>
            <td><img  onclick=italicize()  src="../images/ex_code/ex_italicize.gif" alt="插入斜体字" width="23" height="22" border="0"></td>
       		<td><img  onclick=list()  src="../images/ex_code/ex_list.gif" alt="插入列表" width="23" height="22" border="0"></td>
			<td><img  onclick=center()  src="../images/ex_code/ex_center.gif" alt="文字局中" width="23" height="22" border="0"></td>
			<td><img  onclick=underline()  src="../images/ex_code/ex_underline.gif" alt="插入下划线文字" width="23" height="22" border="0"></td>
			<td><img  onclick=flash()  src="../images/ex_code/ex_swf.gif" alt="插入Flash文件" width="23" height="22" border="0"></td>
			<td><img  onclick=rm()  src="../images/ex_code/ex_rm.gif" alt="插入rm视频文件" width="23" height="22" border="0"></td>
			<td><img  onclick=wmv()  src="../images/ex_code/ex_wma.gif" alt="插入wma视频" width="23" height="22" border="0"></td>
	</tr>
	    </table>
		</td>
    </tr>
    <tr class=a4>
      <td colspan="2"><table>
		<tr>
			<td width="190">字体设置          
			   <select name="font" onFocus="this.selectedIndex=0" onChange="chfont(this.options[this.selectedIndex].value)" size="1">
               <option value="宋体">宋体</option>
               <option value="黑体">黑体</option>
               <option value="Arial">Arial</option>
               <option value="Book Antiqua">Book Antiqua</option>
               <option value="Century Gothic">Century Gothic</option>
               <option value="Courier New">Courier New</option>
               <option value="Georgia">Georgia</option>
               <option value="Impact">Impact</option>
               <option value="Tahoma">Tahoma</option>
               <option value="Times New Roman">Times New Roman</option>
               <option value="Verdana" selected>Verdana</option>
             	</select>
			</td>
			<td width="50">
           	<select name="size" onFocus="this.selectedIndex=2" onChange="chsize(this.options[this.selectedIndex].value)" size="1">
			<option value="-2">-2</option>
			<option value="-1">-1</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected>3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option></select>
			</td>
			<td width="85">
           <select name="color" onFocus="this.selectedIndex=1" onChange="chcolor(this.options[this.selectedIndex].value)" size="1">
			<option value="White" style="color:white;">White</option>
			<option value="Black" style="color:black;">Black</option>
			<option value="Red" style="color:red;">Red</option>
			<option value="Yellow" style="color:yellow;">Yellow</option>
			<option value="Pink" style="color:pink;">Pink</option>
			<option value="Green" style="color:green;">Green</option>
			<option value="Orange" style="color:orange;">Orange</option>
			<option value="Purple" style="color:purple;">Purple</option>
			<option value="Blue" style="color:blue;">Blue</option>
			<option value="Beige" style="color:beige;">Beige</option>
			<option value="Brown" style="color:brown;">Brown</option>
			<option value="Teal" style="color:teal;">Teal</option>
			<option value="Navy" style="color:navy;">Navy</option>
			<option value="Maroon" style="color:maroon;">Maroon</option>
			<option value="LimeGreen" style="color:limegreen;">LimeGreen</option></select>
			</td>
    	</tr>
   </table></td>
    </tr>    
    <tr class=a4>
      <td width="20%" height=23 rowspan="2" align="center">公告内容</td>
      <td width="60%" rowspan="2"><textarea name="content" cols="60" rows="10" wrap="VIRTUAL" class="input"><? echo $resultAnnounce['content']; ?></textarea></td>
      <td width="20%" height="37"><div align="center">代码插入方式：</div></td>
    </tr>
    <tr class=a4>
      <td><div align="center">
          <span>
      <input type="radio" name="mode" value="2" onclick="chmode('2')" checked> 
      提示插入<br>
      <input type="radio" name="mode" value="0" onclick="chmode('0')"> 
      直接插入<br>
      <input type="radio" name="mode" value="1" onclick="chmode('1')"> 
      帮助信息
          </span>
	  </div></td>
    </tr>
    <tr align="center" class=a4>
      <td height=30 colspan="3">
	  <input type="hidden" name="email" value="<? echo $aboutAuthor['1']; ?>">
	  <input type="submit" name="action" value="编辑公告" class="botton">
	  </td>
    </tr>
  </table>
  <? } ?>
</form>
</body>
</html>
