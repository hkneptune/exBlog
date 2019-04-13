<?php
// javascript脚本相关

require("../include/config.inc.php");
require("../include/global-C.inc.php");
setGlobal();

//---- 语言包 ----//
selectLangURL();
include("../$langURL/public.php");
include("../$langURL/blogadmin.php");
include("../$langURL/javascript.php");
?>

<!--
var text_input = "<? echo $lang[0]; ?>";
var text_enter_url = "<? echo $lang[1]; ?>";
var frame_help = "<? echo $lang[2]; ?>"
var help_mode = "<? echo $lang[3]; ?>";
var adv_mode = "<? echo $lang[4]; ?>";
var normal_mode = "<? echo $lang[5]; ?>";
var email_help = "<? echo $lang[6]; ?>";
var email_normal = "<? echo $lang[7]; ?>";
var email_normal_input = "<? echo $lang[8]; ?>";
var fontsize_help = "<? echo $lang[9]; ?>";
var fontsize_normal = "<? echo $lang[10]; ?>";
var font_help = "<? echo $lang[11]; ?>";
var font_normal = "<? echo $lang[12]; ?>";
var exblur_help = "<? echo $lang[13]; ?>";
var exblur_normal = "<? echo $lang[14]; ?>";
var bold_help = "<? echo $lang[15]; ?>";
var bold_normal = "<? echo $lang[16]; ?>";
var strike_help = "<? echo $lang[17]; ?>";
var strike_normal = "<? echo $lang[18]; ?>";
var italicize_help = "<? echo $lang[19]; ?>";
var italicize_normal = "<? echo $lang[20]; ?>";
var sup_help = "<? echo $lang[21]; ?>";
var sup_normal = "<? echo $lang[22]; ?>";
var sub_help = "<? echo $lang[23]; ?>";
var sub_normal = "<? echo $lang[24]; ?>";
var move_help = "<? echo $lang[25]; ?>";
var move_normal = "<? echo $lang[26]; ?>";
var quote_help = "<? echo $lang[27]; ?>"
var quote_normal = "<? echo $lang[28]; ?>";
var color_help = "<? echo $lang[29]; ?>";
var color_normal = "<? echo $lang[30]; ?>";
var left_help = "<? echo $lang[31]; ?>";
var left_normal = "<? echo $lang[32]; ?>";
var center_help = "<? echo $lang[33]; ?>";
var center_normal = "<? echo $lang[34]; ?>";
var right_help = "<? echo $lang[35]; ?>";
var right_normal = "<? echo $lang[36]; ?>";
var justify_help = "<? echo $lang[37]; ?>";
var justify_normal = "<? echo $lang[38]; ?>";
var link_help = "<? echo $lang[39]; ?>";
var link_normal = "<? echo $lang[40]; ?>";
var link_normal_input = "<? echo $lang[41]; ?>";
var image_help = "<? echo $lang[42]; ?>"
var image_normal = "<? echo $lang[43]; ?>";
var flash_help = "<? echo $lang[44]; ?>";
var flash_normal = "<? echo $lang[45]; ?>";
var flash_size = "<? echo $lang[46]; ?>";
var fliph_help = "<? echo $lang[47]; ?>";
var fliph_normal = "<? echo $lang[48]; ?>";
var flipv_help = "<? echo $lang[49]; ?>";
var flipv_normal = "<? echo $lang[50]; ?>";
var code_help = "<? echo $lang[51]; ?>";
var code_normal = "<? echo $lang[52]; ?>";
var htmlcode_help = "<? echo $lang[53]; ?>";
var htmlcode_normal = "<? echo $lang[54]; ?>";
var list_help = "<? echo $lang[55]; ?>";
var list_normal = "<? echo $lang[56]; ?>";
var list_normal_error = "<? echo $lang[57]; ?>";
var list_normal_input = "<? echo $lang[58]; ?>";
var spoiler_help = "<? echo $lang[59]; ?>";
var spoiler_normal = "<? echo $lang[60]; ?>";
var underline_help = "<? echo $lang[61]; ?>";
var underline_normal = "<? echo $lang[62]; ?>";
var wmv_help = "<? echo $lang[63]; ?>";
var wmv_normal = "<? echo $lang[64]; ?>";
var wma_help = "<? echo $lang[65]; ?>";
var wma_normal = "<? echo $lang[66]; ?>";
var rm_help = "<? echo $lang[67]; ?>";
var rm_normal = "<? echo $lang[68]; ?>";
var rm_size = "<? echo $lang[69]; ?>";
var ra_help = "<? echo $lang[70]; ?>";
var ra_normal = "<? echo $lang[71]; ?>";
var mp3_help = "<? echo $lang[72]; ?>";
var mp3_normal = "<? echo $lang[73]; ?>";
var mov_help = "<? echo $lang[74]; ?>";
var mov_normal = "<? echo $lang[75]; ?>";

var media_help = "<? echo $lang[76]; ?>";
var media_normal = "<? echo $lang[77]; ?>";
var music_help = "<? echo $lang[78]; ?>";
var music_normal = "<? echo $lang[79]; ?>";
var music_id = "<? echo $lang[80]; ?>";

var exobject_help = "<? echo $lang[81]; ?>";
var exobject_normal = "<? echo $lang[82]; ?>";
var exobject_id = "<? echo $lang[83]; ?>";
var exobject_size = "<? echo $lang[84]; ?>";

var checklength_t1 = "<? echo $lang[85]; ?>";
var checklength_t2 = "<? echo $lang[86]; ?>";

var pasteCtext = "<? echo $lang[87]; ?>";

var vertify_t1 = "<? echo $lang[88]; ?>";


var check_t1 = "<? echo $lang[89]; ?>";
var check_t2 = "<? echo $lang[90]; ?>";
var check_t3 = "<? echo $lang[91]; ?>";
var check_t4 = "<? echo $lang[92]; ?>";

var check_t5 = "<? echo $lang[93]; ?>";
var check_t6 = "<? echo $lang[94]; ?>";
var check_t7 = "<? echo $lang[95]; ?>";
var check_t8 = "<? echo $lang[96]; ?>";
var check_t9 = "<? echo $lang[97]; ?>";

var check_t10 = "<? echo $lang[98]; ?>";

var check_t11 = "<? echo $lang[99]; ?>";
var check_t12 = "<? echo $lang[100]; ?>";
var check_t13 = "<? echo $lang[101]; ?>";
var check_t14 = "<? echo $lang[102]; ?>";
var check_t15 = "<? echo $lang[103]; ?>";
var check_t16 = "<? echo $lang[104]; ?>";
var check_t17 = "<? echo $lang[105]; ?>";
var check_t18 = "<? echo $lang[106]; ?>";
var check_t19 = "<? echo $lang[107]; ?>";
var check_t20 = "<? echo $lang[108]; ?>";
var check_t21 = "<? echo $lang[109]; ?>";
var check_t22 = "<? echo $lang[110]; ?>";

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


<!-- auto copy to clipboard js by sheeryiro-->

    function copyC(){
	therange=document.exEdit.content.createTextRange();
	therange.execCommand("Copy");
	}

   function pasteC(){
	alert(pasteCtext);
	document.exEdit.content.focus();
	document.exEdit.content.createTextRange().execCommand("Paste");
	}


    function exEditadd(iEdit) {
    AddText(iEdit);
    document.exEdit.content.focus();
    }
    function vertify()
	{
	if (document.exEdit.esort.value=="")
		{
		alert(vertify_t1);
		return false;
		}
	else
		{
		return true;
		}
	}

    function adcheck()
	{
	if (exEdit.title.value=="") {
		alert ('<? echo $lang[111]; ?>');
		exEdit.title.focus();
		return false;
	}
	if (exEdit.author.value=="") {
		alert ('<? echo $lang[112]; ?>');
		exEdit.author.focus();
		return false;
	}
	if (exEdit.content.value=="") {
		alert ('<? echo $lang[113]; ?>');
		exEdit.content.focus();
		return false;
	}
	if (exEdit.content.value.length<6) {
		alert ('<? echo $lang[114]; ?>');
		exEdit.content.focus();
		return false;
	}
copyC();
	}

    function check(noe)
	{
	if (exEdit.title.value=="") {
		alert (check_t1);
		exEdit.title.focus();
		return false;
	}
	if (exEdit.author.value=="") {
		alert (check_t2);
		exEdit.author.focus();
		return false;
	}
	if (exEdit.content.value=="") {
		alert (check_t3);
		exEdit.content.focus();
		return false;
	}
	if (exEdit.content.value.length<6) {
		alert (check_t4);
		exEdit.content.focus();
		return false;
	}
copyC();

	//对用户自定义时间是否合法进行验证 GO
	if ((exEdit.at_datevalue.value.length<10)&&(exEdit.at_datevalue.value.length!=''))
	{
	   alert(check_t5)
	   exEdit.at_datevalue.focus()
	   return false
	 }
	if ((exEdit.at_second.value.length<2)&&(exEdit.at_second.value.length!=''))
	{
	   alert(check_t6)
	   exEdit.at_second.focus()
	   return false
	 }
	if ((exEdit.at_hour.value.length<2)&&(exEdit.at_hour.value.length!=''))
	{
	   alert(check_t7)
	   exEdit.at_hour.focus()
	   return false
	 }
	if ((exEdit.at_minute.value.length<2)&&(exEdit.at_minute.value.length!=''))
	{
	   alert(check_t8)
	   exEdit.at_minute.focus()
	   return false
	 }
	if ((exEdit.at_hour.value>23)||(exEdit.at_minute.value>59)||(exEdit.at_second.value>59))
	{
	   alert(check_t9)
	   exEdit.at_hour.focus()
	   return false
	 }
	//对用户自定义时间是否合法进行验证 END

if (noe=="1"){
    //对日期是否为空进行验证
    if ((exEdit.at_datevalue.value!='')||(exEdit.at_hour.value!='')||(exEdit.at_minute.value!='')||(exEdit.at_second.value!='')){
	   if(confirm(check_t10))
	     {	}
		 else
		 {
		   return false
		  }
	}	}
}

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

  var timerID = null
  var timerRunning = false
  function MakeArray(size) 
  {
  this.length = size;
  for(var i = 1; i <= size; i++)
  {
  this[i] = "";
  }
  return this;
  }
  function stopclock (){
  if(timerRunning)
  clearTimeout(timerID);
  timerRunning = false
  }
  function showtime () {
  var now = new Date();
  var year = now.getYear();
  var month = now.getMonth() + 1;
  var date = now.getDate();
  var hours = now.getHours();
  var minutes = now.getMinutes();
  var seconds = now.getSeconds();
  var day = now.getDay();
  Day = new MakeArray(7);
  Day[0]=check_t11;
  Day[1]=check_t12;
  Day[2]=check_t13;
  Day[3]=check_t14;
  Day[4]=check_t15;
  Day[5]=check_t16;
  Day[6]=check_t17;
  var timeValue = "";
  timeValue += year + check_t18;
  timeValue += ((month < 10) ? "0" : "") + month + check_t19;
  timeValue += date + check_t20;
  timeValue += (Day[day]) + "  ";
  timeValue += ((hours <= 12) ? hours : hours - 12);
  timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
  timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
  timeValue += (hours < 12) ? check_t21 : check_t22;
  liveclock.innerHTML = timeValue;
  timerID = setTimeout("showtime()",1000);
  timerRunning = true
  }
  function startclock () {
  stopclock();
  showtime()
  }

function  onlyNum()
{
if(!((event.keyCode>=48&&event.keyCode<=57)||(event.keyCode>=96&&event.keyCode<=105)||(event.keyCode==8)))
event.returnValue=false;
}
-->
