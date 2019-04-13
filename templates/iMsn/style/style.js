function MO(e)
{
if (!e)
var e=window.event;
var S=e.srcElement;
while (S.tagName!="TD")
{S=S.parentElement;}
S.className="T";
}
function MU(e)
{
if (!e)
var e=window.event;
var S=e.srcElement;
while (S.tagName!="TD")
{S=S.parentElement;}
S.className="P";
}