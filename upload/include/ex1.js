function bbimg(o){
	var zoom=parseInt(o.style.zoom, 10)||100;zoom+=event.wheelDelta/12;if (zoom>0) o.style.zoom=zoom+'%';
	return false;
}

function ubbShowObj(strType,strID,strURL,intWidth,intHeight)
{
    var varHeader="b";
    var tmpstr="";
    var bSwitch = false;
    bSwitch = document.getElementById(varHeader+strID).value;
    bSwitch    =~bSwitch;
    document.getElementById(varHeader+strID).value = bSwitch;
    if(bSwitch){
        document.getElementById(strID).innerHTML = "<A href='"+strURL+"' target='_blank' alt='"+strURL+"' title='Source URL'>-- 右击鼠标，然后保存 --</a>";
    }else{
        switch(strType){
            case "swf":
                tmpstr="<OBJECT codeBase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0 classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 width="+intWidth+" height="+intHeight+"><param name=movie value='"+strURL+"'><param name=quality value=high><param name=AllowScriptAccess value=never><embed src="+strURL+" quality=high pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width="+intWidth+" height="+intHeight+">"+strURL+"</embed></OBJECT>";
                break;
            case "aif":
            case "aifc":
            case "aiff":
            case "au":
            case "avi":
            case "asx":
            case "mp1":
            case "mp2":
            case "mp3":
            case "m3u":
            case "mid":
            case "midi":
            case "mpeg":
            case "mpg":
            case "mpga":
            case "mpe":
            case "m1v":
            case "m2v":
            case "mp2":
            case "mpv2":
            case "mp2v":
            case "mpa":
            case "rmi":
            case "snd":
            case "wma":
            case "wax":
            case "wmv":
            case "wvx":
            case "wm":
            case "wmx":
            case "wmd":
            case "wmz":
            case "wpl":
            case "wav":
                tmpstr="<OBJECT classid='CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95' codebase='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,02,902' type='application/x-oleobject' standby='Loading...' width="+intWidth+" height="+intHeight+"><PARAM NAME='FileName' VALUE='"+strURL+"'><param name='ShowStatusBar' value='-1'><PARAM NAME='AutoStart' VALUE='true'><EMBED type='application/x-mplayer2' pluginspage='http://www.microsoft.com/Windows/MediaPlayer/' SRC='"+strURL+"' AutoStart=true width="+intWidth+" height="+intHeight+"></EMBED></OBJECT>";
                break;
            case "rmvb":
            case "rm":
            case "rt":
            case "rp":
            case "rv":
            case "aac":
            case "m4a":
            case "m4p":
            case "pls":
            case "xpl":
            case "smi":
            case "ssm":
                tmpstr="<OBJECT CLASSID='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' WIDTH="+intWidth+" HEIGHT="+intHeight+"><PARAM NAME='SRC' VALUE='"+strURL+"'><PARAM NAME='CONTROLS' VALUE='ImageWindow'><PARAM NAME='CONSOLE' VALUE='one'><PARAM NAME=AUTOSTART VALUE=true><EMBED SRC='"+strURL+"' NOJAVA=true CONTROLS=ImageWindow CONSOLE=one WIDTH="+intWidth+" HEIGHT="+intHeight+"></OBJECT><br><OBJECT CLASSID='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' WIDTH="+intWidth+" HEIGHT=32><PARAM NAME='CONTROLS' VALUE='StatusBar'><PARAM NAME=AUTOSTART VALUE=true><PARAM NAME='CONSOLE' VALUE='one'><EMBED SRC='"+strURL+"' NOJAVA=true CONTROLS=StatusBar CONSOLE=one WIDTH="+intWidth+" HEIGHT=24></OBJECT><br><OBJECT CLASSID='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' WIDTH="+intWidth+" HEIGHT=32><PARAM NAME='CONTROLS' VALUE='ControlPanel'><PARAM NAME=AUTOSTART VALUE=true><PARAM NAME='CONSOLE' VALUE='one'><EMBED SRC='"+strURL+"' NOJAVA=true CONTROLS=ControlPanel CONSOLE=one WIDTH="+intWidth+" HEIGHT=24 AUTOSTART=true LOOP=false></OBJECT>";                
                break;
            case "ra":
            case "ram":
            case "rpm":
                tmpstr="<object classid='clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA' id='RAOCX' width="+intWidth+" height="+intHeight+"><param name='_ExtentX' value='6694'><param name='_ExtentY' value='1588'><param name='AUTOSTART' value='0'><param name='SHUFFLE' value='0'><param name='PREFETCH' value='0'><param name='NOLABELS' value='0'><param name='SRC' value='"+strURL+"'><param name='CONTROLS' value='StatusBar,ControlPanel'><param name='LOOP' value='0'><param name='NUMLOOP' value='0'><param name='CENTER' value='0'><param name='MAINTAINASPECT' value='0'><param name='BACKGROUNDCOLOR' value='#000000'><embed src='"+strURL+"' width="+intWidth+" autostart='true' height=60></embed></object>;"
                break;
            case "bmp":
            case "gif":
            case "jpg":
            case "png":
                tmpstr="<img width="+intWidth+" height="+intHeight+" src='"+strURL+"' alt='图片'>;"
                break;
            case "qt":
            case "mov":
                tmpstr="<embed src='"+strURL+"' autoplay=true Loop=false controller=true playeveryframe=false cache=false scale=TOFIT bgcolor=#000000 kioskmode=false targetcache=false pluginspage=http://www.apple.com/quicktime/>"
                break;
            case "rar":
            case "zip":
                tmpstr="<a href='"+strURL+"' title='这是一个压缩包下载'>■　这是一个压缩包下载　■</a>"
                break;
            default :
                tmpstr="<a href='"+strURL+"' title='这是一个链接地址' target='_blank'>■　这是一个链接地址　■</a>"
                break;
        }
        document.getElementById(strID).innerHTML = tmpstr;
    }
}



var iFlag;

var NS4 = (document.layers);   
var IE4 = (document.all); 
// window to search. 
var win = window;    
var n   = 0; 
function replaceall(reptonoff) {
	var txt, i, found; 
	var wPopupElements=this.document.all;
	var str = wPopupElements.replacetext.value;
	if (str == "") {
		this.alert("请填入要标示的关键词");
		return false; 
	}
	if (NS4) { 
		if (!win.find(str)) 
			while(win.find(str, false, true)) 
				n++;
		else
			n++; 
	}
	if (IE4) { 
		txt = win.document.body.createTextRange(); 
		for (i = 0; i <= n && (found = txt.findText(str)) != false; i++) { 
			txt.moveStart("character", 1); 
			txt.moveEnd("textedit"); 
		} 
		if (found) { 
			txt.moveStart("character", -1); 
			txt.findText(str); 
			txt.select(); 
			txt.scrollIntoView(); 
			n++; 
		} 
		else { 
			if (n > 0) { 
				n = 0; 
				findInPage(str); 
			} 
		} 
		return false; 
	}
}

function replaceall2(reptonoff) {
 var reptonoff;
 var rng = document.body.createTextRange();
 var wPopupElements=this.document.all;
 if (wPopupElements.textcase.checked)
  {iFlag=4;}
 else
  {iFlag=0;}

if (reptonoff=="on") {
 var rept = wPopupElements.replacetext.value;
}else{
 var rept = "【"+wPopupElements.replacetext.value+"】";
}

 if (rept==null || rept=='')
  {
   this.alert("请填入要标示的关键词");
   return;
  }
  for (i=0; rng.findText(rept,10000,iFlag)!=false; i++)
  {
   rng.scrollIntoView();
if (reptonoff=="on") {
   rng.text = "【"+rept+"】";
}else{
   rng.text = wPopupElements.replacetext.value;
}
  }
if (reptonoff=="on") {
  //setTimeout('this.alert("共标示" + i + " 个关键词!")',200);
}else{
  //setTimeout('this.alert("共取消标示" + i + " 个关键词!")',200);
}
}

function showIntro(objID)
{
	if (document.getElementById(objID).style.display == "none") {
		document.getElementById(objID).style.display = "";
	}else{
		document.getElementById(objID).style.display = "none";
	}
}

var currentpos,timer;

function initialize()
{
timer=setInterval("scrollwindow()",2);
}
function sc(){
clearInterval(timer);
}
function scrollwindow()
{
currentpos=document.body.scrollTop;
window.scroll(0,++currentpos);
if (currentpos != document.body.scrollTop)
sc();
}
document.onmousedown=sc
document.ondblclick=initialize