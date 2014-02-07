var ie=document.all
var dom=document.getElementById
var ns4=document.layers

var bouncelimit=32 //(must be divisible by 8)
var direction="up"

function initbox(){
if (!dom&&!ie&&!ns4)
return
crossobj=(dom)?document.getElementById("dropin").style : ie? document.all.dropin : document.dropin
scroll_top=(ie)? document.body.scrollTop : window.pageYOffset
crossobj.top=scroll_top-250
crossobj.visibility=(dom||ie)? "visible" : "show"
dropstart=setInterval("dropin()",50)
}



function bouncein(){
crossobj.top=parseInt(crossobj.top)-bouncelimit
if (bouncelimit<0)
bouncelimit+=8
bouncelimit=bouncelimit*-1
if (bouncelimit==0){
clearInterval(bouncestart)
}
}

function dismissbox(){
try{
if (document.getElementById('dropframe')) {frames['dropframe'].location.href = 'about:blank';}}
catch (e) {} 
if (window.bouncestart) clearInterval(bouncestart)
crossobj.visibility="hidden"
}

//window.onload=initbox

function getexpirydate( nodays){
var UTCstring;
Today = new Date();
nomilli=Date.parse(Today);
Today.setTime(nomilli+nodays*24*60*60*1000);
UTCstring = Today.toUTCString();
return UTCstring;
}
function getcookie(cookiename) {
 var cookiestring=""+document.cookie;
 var index1=cookiestring.indexOf(cookiename);
 if (index1==-1 || cookiename=="") return ""; 
 var index2=cookiestring.indexOf(';',index1);
 if (index2==-1) index2=cookiestring.length; 
 return unescape(cookiestring.substring(index1+cookiename.length+1,index2));
}
function setcookie(name,value,duration){
cookiestring=name+"="+escape(value)+";EXPIRES="+getexpirydate(duration);
document.cookie=cookiestring;
//document.write("Saved");
if(!getcookie(name)){
return false;
}
else{
return true;
}
}
function date1(){
now = new Date();
then = new Date(" Jan 01 1970 00:00:00");
seconds=now-then/1000;
month=1+now.getMonth();
day=now.getDate();
year=now.getFullYear();
document.write( day+"-"+month+"-"+year+"");
}
function checkout(){
		if(getcookie('buy')=="yes")
		{
			return true;
		}
		else
		{
			window.location.href = "default.htm";
			openIT('mc.htm',410,410,null,null,'mywinname01');
		}
};