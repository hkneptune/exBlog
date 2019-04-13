
var no = 10; // number of hearts
var speed = 25; // smaller number moves the hearts faster
var heart = "images/snow.gif";
var flag;
var ns4up = (document.layers) ? 1 : 0; // browser sniffer
var ie4up = (document.all) ? 1 : 0;

var dx, xp, yp; // coordinate and position variables
var am, stx, sty; // amplitude and step variables
var i, doc_width = 800, doc_height = 600;
if (ns4up) {
doc_width = self.innerWidth;
doc_height = self.innerHeight;
} else if (ie4up) {
doc_width = document.body.clientWidth;
doc_height = document.body.clientHeight;
}
dx = new Array();
xp = new Array();
yp = new Array();
amx = new Array();
amy = new Array();
stx = new Array();
sty = new Array();
flag = new Array();
for (i = 0; i < no; ++ i) {
dx[i] = 0; // set coordinate variables
xp[i] = Math.random()*(doc_width-30)+10; // set position variables
yp[i] = Math.random()*doc_height;
amy[i] = 12+ Math.random()*20; // set amplitude variables
amx[i] = 10+ Math.random()*40;
stx[i] = 0.02 + Math.random()/10; // set step variables
sty[i] = 0.7 + Math.random(); // set step variables
flag[i] = (Math.random()>0.5)?1:0;
if (ns4up) { // set layers
if (i == 0) {
document.write("<layer name=\"dot"+ i +"\" left=\"15\" ");
document.write("top=\"15\" visibility=\"show\"><img src=\"");
document.write(heart+ "\" border=\"0\"></layer>");
} else {
document.write("<layer name=\"dot"+ i +"\" left=\"15\" ");
document.write("top=\"15\" visibility=\"show\"><img src=\"");
document.write(heart+ "\" border=\"0\"></layer>");
}
} else
if (ie4up) {
if (i == 0) {
document.write("<div id=\"dot"+ i +"\" style=\"POSITION: ");
document.write("absolute; Z-INDEX: "+ i +"; VISIBILITY: ");
document.write("visible; TOP: 15px; LEFT: 15px;\"><img src=\"");
document.write(heart+ "\" border=\"0\"></div>");
} else {
document.write("<div id=\"dot"+ i +"\" style=\"POSITION: ");
document.write("absolute; Z-INDEX: "+ i +"; VISIBILITY: ");
document.write("visible; TOP: 15px; LEFT: 15px;\"><img src=\"");
document.write(heart+ "\" border=\"0\"></div>");
}
}
}

function snowNS() { // Netscape main animation function
for (i = 0; i < no; ++ i) { // iterate for every dot
if (yp[i] > doc_height-50) {
xp[i] = 10+ Math.random()*(doc_width-amx[i]-30);
yp[i] = 0;
flag[i]=(Math.random()<0.5)?1:0;
stx[i] = 0.02 + Math.random()/10;
sty[i] = 0.7 + Math.random();
doc_width = self.innerWidth;
doc_height = self.innerHeight;
}
if (flag[i])
dx[i] += stx[i];
else
dx[i] -= stx[i];
if (Math.abs(dx[i]) > Math.PI) {
yp[i]+=Math.abs(amy[i]*dx[i]);
xp[i]+=amx[i]*dx[i];
dx[i]=0;
flag[i]=!flag[i];
}
document.layers["dot"+i].top = yp[i] + amy[i]*(Math.abs(Math.sin(dx[i])+dx[i]));
document.layers["dot"+i].left = xp[i] + amx[i]*dx[i];

}
setTimeout("snowNS()", speed);
}

function snowIE() { // IE main animation function
for (i = 0; i < no; ++ i) { // iterate for every dot
if (yp[i] > doc_height-50) {
xp[i] = 10+ Math.random()*(doc_width-amx[i]-30);
yp[i] = 0;
stx[i] = 0.02 + Math.random()/10;
sty[i] = 0.7 + Math.random();
flag[i]=(Math.random()<0.5)?1:0;
doc_width = document.body.clientWidth;
doc_height = document.body.clientHeight;
}
if (flag[i])
dx[i] += stx[i];
else
dx[i] -= stx[i];
if (Math.abs(dx[i]) > Math.PI) {
yp[i]+=Math.abs(amy[i]*dx[i]);
xp[i]+=amx[i]*dx[i];
dx[i]=0;
flag[i]=!flag[i];
}

document.all["dot"+i].style.pixelTop = yp[i] + amy[i]*(Math.abs(Math.sin(dx[i])+dx[i]));
document.all["dot"+i].style.pixelLeft = xp[i] + amx[i]*dx[i];
}
setTimeout("snowIE()", speed);
}

if (ns4up) {
snowNS();
} else if (ie4up) {
snowIE();
}
