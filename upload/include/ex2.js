var text_input = "文字";
var text_enter_url = "请输入连接网址";
var frame_help = "请输入想倒入框架的URL地址"
var help_mode = "Exblog 代码 - 帮助信息\n\n点击相应的代码按钮即可获得相应的说明和提示";
var adv_mode = "Exblog 代码  - 直接插入\n\n点击代码按钮后不出现提示即直接插入相应代码";
var normal_mode = "Exblog 代码  - 提示插入\n\n点击代码按钮后出现向导窗口帮助您完成代码插入";
var email_help = "插入邮件地址\n\n插入邮件地址连接。\n例如：\n[email]exblog@fengling.net[/email]\n[email=exblog@fengling.net]Exsoft team[/email]";
var email_normal = "请输入链接显示的文字，如果留空则直接显示邮件地址。";
var email_normal_input = "请输入邮件地址。";
var fontsize_help = "设置字号\n\n将标签所包围的文字设置成指定字号。\n例如：[size=3]文字大小为 3[/size]";
var fontsize_normal = "请输入要设置为指定字号的文字。";
var font_help = "设定字体\n\n将标签所包围的文字设置成指定字体。\n例如：[font=仿宋]字体为仿宋[/font]";
var font_normal = "请输入要设置成指定字体的文字。";
var exblur_help = "插入模糊文本\n\n将标签所包围的文本变成模糊文本。\n例如：[blur]Exsoft team[/blur]";
var exblur_normal = "请输入要设置成模糊文本的文字。";
var bold_help = "插入粗体文本\n\n将标签所包围的文本变成粗体。\n例如：[b]Exsoft team[/b]";
var bold_normal = "请输入要设置成粗体的文字。";
var strike_help = "插入删除文本\n\n将标签所包围的文本变成删除文本。\n例如：[s]Exsoft team[/s]";
var strike_normal = "请输入要设置成删除线的文字。";
var italicize_help = "插入斜体文本\n\n将标签所包围的文本变成斜体。\n例如：[i]Exsoft team[/i]";
var italicize_normal = "请输入要设置成斜体的文字。";
var sup_help = "插入上标文本\n\n将标签所包围的文本变成上标。\n例如：[sup]Exsoft team[/sup]";
var sup_normal = "请输入要设置成上标的文字。";
var sub_help = "插入下标文本\n\n将标签所包围的文本变成下标。\n例如：[sub]Exsoft team[/sub]";
var sub_normal = "请输入要设置成下标的文字。";
var move_help = "插入飞行文本\n\n将标签所包围的文本变成飞行文本。\n例如：[move]Exsoft team[/move]";
var move_normal = "请输入要设置成飞行的文字。";
var quote_help = "插入引用\n\n将标签所包围的文本作为引用特殊显示。"
var quote_normal = "请输入要作为引用显示的文字。";
var color_help = "定义文本颜色\n\n将标签所包围的文本变为制定颜色。\n例如：[color=red]红颜色[/color]";
var color_normal = "请输入要设置成指定颜色的文字。";
var left_help = "左对齐\n\n将标签所包围的文本左对齐显示。\n例如：[align=left]内容居中[/align]";
var left_normal = "请输入要左对齐的文字。";
var center_help = "居中对齐\n\n将标签所包围的文本居中对齐显示。\n例如：[align=center]内容居中[/align]";
var center_normal = "请输入要居中对齐的文字。";
var right_help = "右对齐\n\n将标签所包围的文本右对齐显示。\n例如：[align=right]内容居中[/align]";
var right_normal = "请输入要右对齐的文字。";
var justify_help = "自适应对齐\n\n将标签所包围的文本自适应对齐显示。\n例如：[align=justify]内容居中[/align]";
var justify_normal = "请输入要自适应对齐的文字。";
var link_help = "插入超级链接\n\n插入一个超级连接。\n例如：\n[url]http://www.exblog.net[/url]\n[url=http://www.exblog.com]Exsoft team[/url]";
var link_normal = "请输入链接显示的文字，如果留空则直接显示链接。";
var link_normal_input = "请输入 URL。";
var image_help = "插入图像\n\n在文本中插入一幅图像。"
var image_normal = "请输入图像的 URL。";
var flash_help = "插入flash\n\n在文本中插入flash 动画。\n例如：[swf]http://www.exblog.com/flash.swf[/swf]";
var flash_normal = "请输入 flash 动画的 URL。";
var fliph_help = "左右颠倒文字\n\n将标签所包围的文本变为左右颠倒文字。\n例如：[fliph]Exsoft team[/fliph]";
var fliph_normal = "请输入要设置成左右颠倒的文字。";
var flipv_help = "上下颠倒文字\n\n将标签所包围的文本变为上下颠倒文字。\n例如：[flipv]Exsoft team[/flipv]";
var flipv_normal = "请输入要设置成上下颠倒的文字。";
var code_help = "插入代码\n\n插入程序或脚本原始代码。\n例如：[code]echo\"这里是我的BLOG\";[/code]";
var code_normal = "请输入要插入的代码。";
var htmlcode_help = "插入html代码\n\n插入html代码。\n例如：[html]<b>这里是我的BLOG</b>[/html]";
var htmlcode_normal = "请输入要插入的html代码。";
var list_help = "插入列表\n\n插入可由浏览器显示来的规则列表项。\n例如：\n[list]\n[*]；列表项 #1\n[*]；列表项 #2\n[*]；列表项 #3\n[/list]";
var list_normal = "请选择列表格式：字母式列表输入 \"A\"；数字式列表输入 \"1\"。此处也可留空。";
var list_normal_error = "错误：列表格式只能选择输入 \"A\" 或 \"1\"。";
var list_normal_input = "请输入列表项目内容，如果留空表示项目结束。";
var spoiler_help = "插入spoiler效果文本\n\n将标签所包围的文本变成spoiler效果文本。\n例如：[spoiler]Exsoft team[/spoiler]";
var spoiler_normal = "请输入要设置成spoiler效果的文字。";
var underline_help = "插入下划线\n\n给标签所包围的文本加上下划线。\n例如：[u]Exsoft team[/u]";
var underline_normal = "请输入标注下划线的文字内容";
var wmv_help = "插入 Windows Media Player 视频\n\n在文本中插入 Windows Media Player 视频。\n例如：[wmv]http://www.exblog.com/go.wmv[/wmv]";
var wmv_normal = "视频文件的地址,请输入";
var wma_help = "插入 Windows Media Player 音频\n\n在文本中插入 Windows Media Player 音频。\n例如：[wma]http://www.exblog.com/go.wma[/wma]";
var wma_normal = "音频文件的地址,请输入";
var rm_help = "插入 real player 视频\n\n在文本中插入 real player 视频。\n例如：[rm]http://www.exblog.com/go.rm[/rm]";
var rm_normal = "rm文件的地址,请输入";
var ra_help = "插入 real player 音频\n\n在文本中插入 real player 音频。\n例如：[ra]http://www.exblog.com/go.ra[/ra]";
var ra_normal = "ra文件的地址,请输入";
var mp3_help = "插入 mp3 \n\n在文本中插入 mp3 。\n例如：[mp3]http://www.exblog.com/go.mp3[/mp3]";
var mp3_normal = "mp3 文件的地址,请输入";
var mov_help = "插入 mov \n\n在文本中插入 mov  支持格式 mov pitc aiff 。\n例如：[mov]http://www.exblog.com/go.mov[/mov]";
var mov_normal = "mov 文件的地址,请输入 支持格式 mov pitc aiff";

var media_help = "插入 media 通用标签 播放在线影视文件 \n\n在文本中插入 media 通用标签 播放在线影视文件 支持格式 rm ra ram rpm mpg mpv mpeg dat 。\n例如：[media]http://www.exblog.com/go.rm[/media]";
var media_normal = "在线影视文件的地址,支持格式 rm ra ram rpm mpg mpv mpeg dat,请输入";
var music_help = "插入 music 通用标签 播放在线音乐文件 \n\n在文本中插入 music 通用标签 播放在线音乐文件 支持格式 ram rmm rm mp3 mp2 mpa ra 。\n例如：[mov]http://www.exblog.com/go.mov[/mov]";
var music_normal = "在线音乐文件的地址,支持格式 ram rmm rm mp3 mp2 mpa ra,请输入";



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

function move() {
        if (helpmode) {
                alert(move_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[move]" + range.text + "[/move]";
        } else if (advmode) {
                AddTxt="[move] [/move]";
                AddText(AddTxt);
        } else {  
                txt=prompt(move_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[move]"+txt;
                        AddText(AddTxt);
                        AddText("[/move]");
                }       
        }
}

function spoiler() {
        if (helpmode) {
                alert(spoiler_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[spoiler]" + range.text + "[/spoiler]";
        } else if (advmode) {
                AddTxt="[spoiler] [/spoiler]";
                AddText(AddTxt);
        } else {  
                txt=prompt(spoiler_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[spoiler]"+txt;
                        AddText(AddTxt);
                        AddText("[/spoiler]");
                }       
        }
}

function exblur() {
        if (helpmode) {
                alert(exblur_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[blur]" + range.text + "[/blur]";
        } else if (advmode) {
                AddTxt="[blur] [/blur]";
                AddText(AddTxt);
        } else {  
                txt=prompt(exblur_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[blur]"+txt;
                        AddText(AddTxt);
                        AddText("[/blur]");
                }       
        }
}

function fliph() {
        if (helpmode) {
                alert(fliph_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[fliph]" + range.text + "[/fliph]";
        } else if (advmode) {
                AddTxt="[fliph] [/fliph]";
                AddText(AddTxt);
        } else {  
                txt=prompt(fliph_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[fliph]"+txt;
                        AddText(AddTxt);
                        AddText("[/fliph]");
                }       
        }
}

function flipv() {
        if (helpmode) {
                alert(flipv_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[flipv]" + range.text + "[/flipv]";
        } else if (advmode) {
                AddTxt="[flipv] [/flipv]";
                AddText(AddTxt);
        } else {  
                txt=prompt(flipv_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[flipv]"+txt;
                        AddText(AddTxt);
                        AddText("[/flipv]");
                }       
        }
}

function sup() {
        if (helpmode) {
                alert(sup_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[sup]" + range.text + "[/sup]";
        } else if (advmode) {
                AddTxt="[sup] [/sup]";
                AddText(AddTxt);
        } else {  
                txt=prompt(sup_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[sup]"+txt;
                        AddText(AddTxt);
                        AddText("[/sup]");
                }       
        }
}

function exsub() {
        if (helpmode) {
                alert(sub_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[sub]" + range.text + "[/sub]";
        } else if (advmode) {
                AddTxt="[sub] [/sub]";
                AddText(AddTxt);
        } else {  
                txt=prompt(sub_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[sub]"+txt;
                        AddText(AddTxt);
                        AddText("[/sub]");
                }       
        }
}

function strike() {
        if (helpmode) {
                alert(strike_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[s]" + range.text + "[/s]";
        } else if (advmode) {
                AddTxt="[s] [/s]";
                AddText(AddTxt);
        } else {  
                txt=prompt(strike_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[s]"+txt;
                        AddText(AddTxt);
                        AddText("[/s]");
                }       
        }
}

function left() {
        if (helpmode) {
                alert(left_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[left]" + range.text + "[/left]";
        } else if (advmode) {
                AddTxt="[align=left] [/align]";
                AddText(AddTxt);
        } else {  
                txt=prompt(left_normal,text_input);     
                if (txt!=null) {          
                        AddTxt="\r[align=left]"+txt;
                        AddText(AddTxt);
                        AddText("[/align]");
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

function right() {
        if (helpmode) {
                alert(right_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[right]" + range.text + "[/right]";
        } else if (advmode) {
                AddTxt="[align=right] [/align]";
                AddText(AddTxt);
        } else {  
                txt=prompt(right_normal,text_input);     
                if (txt!=null) {          
                        AddTxt="\r[align=right]"+txt;
                        AddText(AddTxt);
                        AddText("[/align]");
                }              
        }
}

function justify() {
        if (helpmode) {
                alert(justify_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[justify]" + range.text + "[/justify]";
        } else if (advmode) {
                AddTxt="[align=justify] [/align]";
                AddText(AddTxt);
        } else {  
                txt=prompt(justify_normal,text_input);     
                if (txt!=null) {          
                        AddTxt="\r[align=justify]"+txt;
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
		} else if (document.selection && document.selection.type == "Text") {
				var range = document.selection.createRange();
				range.text = "[img]" + range.text + "[/img]";
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

function flash()  { 
        if (helpmode) {
                alert(flash_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[flash]" + range.text + "[/flash]";
        } else if (advmode) {
                AddTxt="[flash=500,350] [/flash]";
                AddText(AddTxt);
        } else {
                txt2=prompt("flash的宽度，高度","300,200");
		if (txt2!=null) {
			txt=prompt(flash_normal,"请输入");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[flash=500,350]"+txt;
					AddText(AddTxt);
					AddTxt="[/flash]";
					AddText(AddTxt);
				} else {
					AddTxt="[flash="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/flash]";
					AddText(AddTxt);
				}
			} 
		}
	}
}

function music()  { 
        if (helpmode) {
                alert(music_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[music]" + range.text + "[/music]";
        } else if (advmode) {
                AddTxt="[music=1] [/music]";
                AddText(AddTxt);
        } else {
                txt2=prompt("播放器标号","1");
		if (txt2!=null) {
			txt=prompt(music_normal,"请输入");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[music=1]"+txt;
					AddText(AddTxt);
					AddTxt="[/music]";
					AddText(AddTxt);
				} else {
					AddTxt="[music="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/music]";
					AddText(AddTxt);
				}
			} 
		}
	}
}

function media()  { 
        if (helpmode) {
                alert(media_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[media]" + range.text + "[/media]";
        } else if (advmode) {
                AddTxt="[media=1] [/media]";
                AddText(AddTxt);
        } else {
                txt2=prompt("播放器标号","1");
		if (txt2!=null) {
			txt=prompt(media_normal,"请输入");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[media=1]"+txt;
					AddText(AddTxt);
					AddTxt="[/media]";
					AddText(AddTxt);
				} else {
					AddTxt="[media="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/media]";
					AddText(AddTxt);
				}
			} 
		}
	}
}

function mov() {
        if (helpmode) {
                alert(mov_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[mov]" + range.text + "[/mov]";
        } else if (advmode) {
                AddTxt="[mov] [/mov]";
                AddText(AddTxt);
        } else {  
                txt=prompt(mov_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[mov]"+txt;
                        AddText(AddTxt);
                        AddText("[/mov]");
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

function htmlcode() {
        if (helpmode) {
                alert(htmlcode_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[html]" + range.text + "[/html]";
        } else if (advmode) {
                AddTxt="\r[html]\r[/html]";
                AddText(AddTxt);
        } else {   
                txt=prompt(htmlcode_normal,"");     
                if (txt!=null) {          
                        AddTxt="\r[html]"+txt;
                        AddText(AddTxt);
                        AddText("[/html]");
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
        if (helpmode) {
                alert(wmv_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[wmv]" + range.text + "[/wmv]";
        } else if (advmode) {
                AddTxt="[wmv] [/wmv]";
                AddText(AddTxt);
        } else {  
                txt=prompt(wmv_normal,"请输入");
                if (txt!=null) {           
                        AddTxt="[wmv]"+txt;
                        AddText(AddTxt);
                        AddText("[/wmv]");
                }               
        }
}

function wma()  { 
        if (helpmode) {
                alert(wma_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[wma]" + range.text + "[/wma]";
        } else if (advmode) {
                AddTxt="[wma] [/wma]";
                AddText(AddTxt);
        } else {  
                txt=prompt(wma_normal,"请输入");
                if (txt!=null) {           
                        AddTxt="[wma]"+txt;
                        AddText(AddTxt);
                        AddText("[/wma]");
                }               
        }
}

function rm()  { 
        if (helpmode) {
                alert(rm_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[rm]" + range.text + "[/rm]";
        } else if (advmode) {
                AddTxt="[rm=500,350] [/rm]";
                AddText(AddTxt);
        } else {
                txt2=prompt("视频的宽度，高度","300,200");
		if (txt2!=null) {
			txt=prompt(rm_normal,"请输入");
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
}

function ra()  { 
        if (helpmode) {
                alert(ra_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[ra]" + range.text + "[/ra]";
        } else if (advmode) {
                AddTxt="[ra] [/ra]";
                AddText(AddTxt);
        } else {  
                txt=prompt(ra_normal,"请输入");
                if (txt!=null) {           
                        AddTxt="[ra]"+txt;
                        AddText(AddTxt);
                        AddText("[/ra]");
                }               
        }
}

function mp3()  { 
        if (helpmode) {
                alert(mp3_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[mp3]" + range.text + "[/mp3]";
        } else if (advmode) {
                AddTxt="[mp3] [/mp3]";
                AddText(AddTxt);
        } else {  
                txt=prompt(mp3_normal,"请输入");
                if (txt!=null) {           
                        AddTxt="[mp3]"+txt;
                        AddText(AddTxt);
                        AddText("[/mp3]");
                }               
        }
}

function setfocus() {
        document.exEdit.content.focus();
}
function checklength(theform) {
message = "";
	alert("你的信息已经有 "+theform.content.value.length+" 字节."+message);
}
