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
                txt2=prompt(flash_size,"300,200");
		if (txt2!=null) {
			txt=prompt(flash_normal,"http://");
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
                txt2=prompt(music_id,"1");
		if (txt2!=null) {
			txt=prompt(music_normal,"http://");
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
                txt2=prompt(music_id,"1");
		if (txt2!=null) {
			txt=prompt(media_normal,"http://");
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
                txt=prompt(wmv_normal,"http://");
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
                txt=prompt(wma_normal,"http://");
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
                txt2=prompt(rm_size,"300,200");
		if (txt2!=null) {
			txt=prompt(rm_normal,"http://");
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
                txt=prompt(ra_normal,"http://");
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
                txt=prompt(mp3_normal,"http://");
                if (txt!=null) {           
                        AddTxt="[mp3]"+txt;
                        AddText(AddTxt);
                        AddText("[/mp3]");
                }               
        }
}

function exobject()  { 
        if (helpmode) {
                alert(exobject_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[object1=500,350]" + range.text + "[/object]";
        } else if (advmode) {
                AddTxt="[object1=500,350] [/object]";
                AddText(AddTxt);
        } else {
                txt1=prompt(exobject_id,"1");
		if (txt1!=null) {
           	     txt2=prompt(exobject_size,"300,200");
			if (txt2!=null) {
				txt=prompt(exobject_normal,"http://");
				if (txt!=null) {
					if (txt1=="") {
						if (txt2=="") {
							AddTxt="[object1=500,350]"+txt;
							AddText(AddTxt);
							AddTxt="[/object]";
							AddText(AddTxt);
						} else {
							AddTxt="[object1="+txt2+"]"+txt;
							AddText(AddTxt);
							AddTxt="[/object]";
							AddText(AddTxt);
						}
					} else {
						if (txt2=="") {
							AddTxt="[object"+txt1+"=500,350]"+txt;
							AddText(AddTxt);
							AddTxt="[/object]";
							AddText(AddTxt);
						} else {
							AddTxt="[object"+txt1+"="+txt2+"]"+txt;
							AddText(AddTxt);
							AddTxt="[/object]";
							AddText(AddTxt);
						}
					}
				}
			} 
		}
	}
}

function setfocus() {
        document.exEdit.content.focus();
}
function checklength(theform) {
message = "";
	alert(checklength_t1+theform.content.value.length+checklength_t2+message);
}

function showIntro(objID)
{
	if (document.getElementById(objID).style.display == "none") {
		document.getElementById(objID).style.display = "";
	}else{
		document.getElementById(objID).style.display = "none";
	}
}
