function SetCookie(cookieName,cookieValue,nDays)
{
    var today = new Date();
    var expire = new Date();
    if (nDays==null || nDays==0) nDays=90;
    expire.setTime(today.getTime() + 3600000*24*nDays);
    document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString()+";path=/;";
}
function getURLParam(strParamName)
{
        var strReturn = "";
        var strHref = window.location.href;
        if ( strHref.indexOf("?") > -1 )
        {
                var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
                var aQueryString = strQueryString.split("&");
                for ( var iParam = 0; iParam < aQueryString.length; iParam++ )
                {
                        if (aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 )
                        {
                                var aParam = aQueryString[iParam].split("=");
                                strReturn = aParam[1];
                                break;
                        }
                }
        }
        return unescape(strReturn);
}
var referral=getURLParam("referral");
var creative=getURLParam("creative");
var section=getURLParam("section");
var agency=getURLParam("agency");
var client=getURLParam("client");
var keyword=getURLParam("keyword");

SetCookie("referral",referral,15);
SetCookie("creative",creative,15);
SetCookie("section",section,15);
SetCookie("agency",agency,15);
SetCookie("client",client,15);
SetCookie("keyword",keyword,15);

