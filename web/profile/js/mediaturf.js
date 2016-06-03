//Note to change the URL in Image field 
function SetCookie(cookieName,cookieValue,nDays)
{
    var today = new Date();
    var expire = new Date();
    if (nDays==null || nDays==0) nDays=90;
    expire.setTime(today.getTime() + 3600000*24*nDays);
    document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString()+";path=/;";
}
function ReadCookie(cookieName)
{
    var theCookie=""+document.cookie;
    var ind=theCookie.indexOf(cookieName+"=");
    if (ind==-1 || cookieName=="") return "";
    var ind1=theCookie.indexOf(';',ind);
    if (ind1==-1) ind1=theCookie.length;
    return unescape(theCookie.substring(ind+cookieName.length+1,ind1));
}
var referral=ReadCookie("referral");
var creative=ReadCookie("creative");
var section=ReadCookie("section");
var agency=ReadCookie("agency");
var client=ReadCookie("client");
var keyword=ReadCookie("keyword");
//alert(referral + " " + creative + " " + section + " " + agency + " " + client );

var imgval="<img src='http://67.19.228.180/cpa_tracker/jeevansathi/mkdbentry.php?referrer="+referral+"&creative="+creative+"&keyword="+keyword+"&section="+section+"&agency="+agency+"&client="+client+"' width='0' height='0' >";
document.write(imgval);

SetCookie("referral","",15);
SetCookie("creative","",15);
SetCookie("section","",15);
SetCookie("agency","",15);
SetCookie("client","",15);
SetCookie("keyword","",15);
