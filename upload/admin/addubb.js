var text_enter_url      = "请输入连接网址";
var text_enter_txt      = "请输入连接说明";
var text_enter_image    = "请输入图片网址";
var text_enter_sound    = "请输入声音文件网址";
var text_enter_email    = "请输入邮件网址";
var error_no_url        = "您必须输入网址";
var error_no_txt        = "您必须输入连接说明";
var error_no_email      = "您必须输入邮件网址";
var text_enter_fen    = "请输入数值,如：100 (即限制积分在100以下的用户不能浏览该内容!)";
var error_no_fen       = "您必须输入数值,如：100";
var text_enter_send    = "请输入数值,如：100 (即限制发贴数在100以下的用户不能浏览该内容!)";
var error_no_send       = "您必须输入数值,如：100";
function commentWrite(NewCode) {
document.exEdit.content.value+=NewCode;
document.exEdit.content.focus();
return;
}
function AddText(NewCode) {
	document.exEdit.content.value+=NewCode
}

function fontfy(){
document.exEdit.content.value=document.exEdit.content.value+fontbegin+fontend;
document.exEdit.content.focus();
}

function flash() {                  
		txt=prompt("Flash 文件的地址","http://");
		if (txt!=null) {             
			AddTxt="[swf]"+txt;
			AddText(AddTxt);
			AddTxt="[/swf]";
			AddText(AddTxt);
		}        
	} 
	
function doInsert(ibTag, ibClsTag, isSingle)
{
	var isClose = false;
	var obj_ta = document.exEdit.Post;

	if ( (myVersion >= 4) && is_ie && is_win) // Ensure it works for IE4up / Win only
	{
		if(obj_ta.isTextEdit){ // this doesn't work for NS, but it works for IE 4+ and compatible browsers
			obj_ta.focus();
			var sel = document.exEdit;
			var rng = sel.createRange();
			rng.colapse;
			if((sel.type == "Text" || sel.type == "None") && rng != null){
				if(ibClsTag != "" && rng.text.length > 0)
					ibTag += rng.text + ibClsTag;
				else if(isSingle)
					isClose = true;
	
				rng.text = ibTag;
			}
		}
		else{
			if(isSingle)
				isClose = true;
	
			obj_ta.value += ibTag;
		}
	}
	else
	{
		if(isSingle)
			isClose = true;

		obj_ta.value += ibTag;
	}

	obj_ta.focus();
	
	// clear multiple blanks
//	obj_ta.value = obj_ta.value.replace(/  /, " ");

	return isClose;
}	

function send() {
var FoundErrors = '';
var entersend  =prompt(text_enter_send,"100");
if (!entersend) {
FoundErrors += "\n" + error_no_send;
}
if (FoundErrors) {
alert("错误！"+FoundErrors);
return;
}
var ToAdd = "[send="+entersend+"][/send]";
commentWrite(ToAdd);
}

function fen() {
var FoundErrors = '';
var enterfen  =prompt(text_enter_fen,"100");
if (!enterfen) {
FoundErrors += "\n" + error_no_fen;
}
if (FoundErrors) {
alert("错误！"+FoundErrors);
return;
}
var ToAdd = "[fen="+enterfen+"][/fen]";
commentWrite(ToAdd);
}

function showsize(size)  {                       
		txt=prompt("大小 "+size,"文字"); 
		if (txt!=null) {             
			AddTxt="[size="+size+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/size]";
			AddText(AddTxt);
		}        
	}



function login(){  
fontbegin="[login]";
fontend="[/login]";
fontfy();
}
function replyview(){  
fontbegin="[replyview]";
fontend="[/replyview]";
fontfy();
}	
function bold(){  
fontbegin="[B]";
fontend="[/B]";
fontfy();
}
function italicize(){   
fontbegin="[i]";
fontend="[/i]";
fontfy();
}

function quote()  {   
fontbegin="[quote]";
fontend="[/quote]";
fontfy();
}


function center()  {  
		txt2=prompt("对齐样式\n输入 'center' 表示居中, 'left' 表示左对齐, 'right' 表示右对齐.","center");               
		while ((txt2!="center") && (txt2!="left") && (txt2!="right")) {
			txt2=prompt("错误!\n类型只能输入 'center' 、 'left' 或者 'right'.","center");               
		}
		txt=prompt("要对齐的文本","文本");     
		if (txt!=null) {          
			AddTxt="\r[align="+txt2+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/align]";
			AddText(AddTxt);
		}	       
	}


function hyperlink()  { 
var FoundErrors = '';
var enterURL   = prompt(text_enter_url, "http://");
var enterTxT   = prompt(text_enter_txt, enterURL);
if (!enterURL)    {
FoundErrors += "\n" + error_no_url;
}
if (!enterTxT)    {
FoundErrors += "\n" + error_no_txt;
}
if (FoundErrors)  {
alert("错误！"+FoundErrors);
return;
}
var ToAdd = "[URL="+enterURL+"]"+enterTxT+"[/URL]";
commentWrite(ToAdd);
}


function image()  {  
	var enterURL = prompt(text_enter_image,"");
if (!enterURL) { alert(error_no_url); return; }
var ToAdd = "[img]"+enterURL+"[/img]";
commentWrite(ToAdd);
}

function showcolor(color){  
     	txt=prompt("选择的颜色是: "+color,"文字");
		if(txt!=null) {
			AddTxt="[color="+color+"]"+txt;
			AddText(AddTxt);        
			AddTxt="[/color]";
			AddText(AddTxt);
		} 
	}

function showfont(font){                  
		txt=prompt("要设置字体的文字"+font,"文字");
		if (txt!=null) {             
			AddTxt="[face="+font+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/face]";
			AddText(AddTxt);
		}        
	}  


function showcode() {   
		fontbegin="[code]";
fontend="[/code]";
fontfy();
}
function list() {  
		txt=prompt("列表类型\n输入 'A' 表示字母列表, '1' 表示数字列表, 留空表示圆点列表.","");               
		while ((txt!="") && (txt!="A") && (txt!="a") && (txt!="1") && (txt!=null)) {
			txt=prompt("错误!\n类型只能输入 'A' 、 '1' 或者留空.","");               
		}
		if (txt!=null) {
			if (txt=="") {
				AddTxt="[list]";
			} else {
				AddTxt="[list="+txt+"]";
			} 
			txt="1";
			while ((txt!="") && (txt!=null)) {
				txt=prompt("列表项\n空白表示结束列表",""); 
				if (txt!="") {             
					AddTxt+="[*]"+txt+"[/*]"; 
				}                   
			} 
			AddTxt+="[/list] ";
			AddText(AddTxt); 
		}
	}

function underline() {  
		fontbegin="[u]";
fontend="[/u]";
fontfy();
}       

function setfly() {                  
		fontbegin="[fly]";
fontend="[/fly]";
fontfy();
}

function move() {  
		fontbegin="[move]";
fontend="[/move]";
fontfy();
}
function shadow() { 
		txt2=prompt("文字的长度、颜色和边界大小","250,blue,1"); 
		if (txt2!=null) {
			txt=prompt("要产生阴影效果的文字","文字");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[SHADOW=255, blue, 1]"+txt;
					AddText(AddTxt);
					AddTxt="[/SHADOW]";
					AddText(AddTxt);
				} else {
					AddTxt="[SHADOW="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/SHADOW]";
					AddText(AddTxt);
				}         
			} 
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

function xinxiang()  { 
			txt=prompt("电子信箱地址","请输入");
			if (txt!=null) {
					AddTxt="[email]"+txt;
					AddText(AddTxt);
					AddTxt="[/email]";
					AddText(AddTxt);
				}         
	}


function tag_email()
{
    var emailAddress = prompt(text_enter_email, "");

    if (!emailAddress) { 
		alert(error_no_email); 
		return; 
	}

	doInsert("[EMAIL]"+emailAddress+"[/EMAIL]", "", false);
}

function ra()  { 
			txt=prompt("音频文件的地址","请输入");
			if (txt!=null) {
					AddTxt="[ra]"+txt;
					AddText(AddTxt);
					AddTxt="[/ra]";
					AddText(AddTxt);
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

function wma()  { 
			txt=prompt("Wma文件的地址","请输入");
			if (txt!=null) {
					AddTxt="[wma]"+txt;
					AddText(AddTxt);
					AddTxt="[/wma]";
					AddText(AddTxt);
				}         
	}

function mp() { 
		txt2=prompt("视频的宽度，高度","300,200"); 
		if (txt2!=null) {
			txt=prompt("视频文件的地址","请输入");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[mp=500,350]"+txt;
					AddText(AddTxt);
					AddTxt="[/mp]";
					AddText(AddTxt);
				} else {
					AddTxt="[mp="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/mp]";
					AddText(AddTxt);
				}         
			} 
		}
	}


function qt() { 
		txt2=prompt("视频的宽度，高度","300,200"); 
		if (txt2!=null) {
			txt=prompt("视频文件的地址","请输入");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[qt=500,350]"+txt;
					AddText(AddTxt);
					AddTxt="[/qt]";
					AddText(AddTxt);
				} else {
					AddTxt="[qt="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/qt]";
					AddText(AddTxt);
				}         
			} 
		}
	}


function sk() 
	{ 
		txt2=prompt("Shockwave文件的宽度，高度","300,200"); 
		if (txt2!=null) {
			txt=prompt("Shockwave文件的地址","请输入地址");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[dir=500,350]"+txt;
					AddText(AddTxt);
					AddTxt="[/dir]";
					AddText(AddTxt);
				} else {
					AddTxt="[dir="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/dir]";
					AddText(AddTxt);
				}         
			} 
		}
	}


function glow() { 
		txt2=prompt("文字的长度、颜色和边界大小","250,red,2"); 
		if (txt2!=null) {
			txt=prompt("要产生光晕效果的文字.","文字");      
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[glow=255,red,2]"+txt;
					AddText(AddTxt);
					AddTxt="[/glow]";
					AddText(AddTxt);
				} else {
					AddTxt="[glow="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/glow]";
					AddText(AddTxt);
				}         
			} 
		}
}
function download() {  
var enterURL = prompt(text_enter_url,"");
if (!enterURL) { alert(error_no_url); return; }
var ToAdd = "[download]"+enterURL+"[/download]";
commentWrite(ToAdd);
}

function frame()  {  
var enterURL = prompt(text_enter_url,"");
if (!enterURL) { alert(error_no_url); return; }
var ToAdd = "[iframe]"+enterURL+"[/iframe]";
commentWrite(ToAdd);
}

function sound() {  
var enterURL = prompt(text_enter_sound,"");
if (!enterURL) { alert(error_no_url); return; }
var ToAdd = "[sound]"+enterURL+"[/sound]";
commentWrite(ToAdd);
}
//心情图片
function setsmiley(what) 
{ 
document.exEdit.content.value = document.exEdit.content.value+" "+what; 
document.exEdit.content.focus(); 
} 
//动作
function dongzuo(addTitle) { 
ToAdd = addTitle; 
commentWrite(ToAdd);
return; }
function huati(addTitle) { 
ToAdd = addTitle; 
document.exEdit.motif.value+=ToAdd;
document.exEdit.motif.focus();
return; }