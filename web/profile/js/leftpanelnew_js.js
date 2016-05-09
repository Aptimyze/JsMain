function break_words(word_length,middle_string,input_string)
  {     var re = new RegExp("[^ ]{"+word_length+",}","g");
    if(!(str_ary = input_string.match(re)))return input_string;
    for(var i=0;i<str_ary.length;i++)
    {
      newstr = str_ary[i].substring(0,word_length)+middle_string+str_ary[i].substring(word_length,str_ary[i].length);
      input_string = input_string.replace(str_ary[i],newstr);
    }
    return input_string;
  }
                                                                                                                             
  function google_ad_request_done(google_ads) {
                                                                                                                             
    // Proceed only if we have ads to display!
    if (google_ads.length < 1 )
      return;
                                                                                                                             
    var max_word_len=20;
    var break_char="<br>";
                                                                                                                             
        document.write("<br><table width=\"120\" height=\"240\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#FFE5CC\"><tr><td><table width=\"120\" height=\"240\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"sblack\" style=\"line-height:16px; padding-left:2px \" bgcolor=\"#FFFFFF\"><tr bgcolor=\"#FFE5CC\"><td><div align=\"center\"><font color=\"#999999\"><b>Ads by google</b></font></div></td></tr>");
                                                                                                                             
        for(i = 0; i < google_ads.length; ++i) {document.write("<tr><td><FONT style=\"FONT-SIZE: 11px; FONT-FAMILY: Arial\"><a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\" style=\"color:#990000 \" ><b>" + break_words(max_word_len,break_char,google_ads[i].line1) + "</b></a></span><br><span class=blacklink>" +"<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\" >" + break_words(max_word_len,break_char,google_ads[i].line2) +" "+ break_words(max_word_len,break_char,google_ads[i].line3) + "</a></span><br><span class=blacklinku>" + "<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\" \"color:#cc0000\" >" + break_words(max_word_len,break_char,google_ads[i].visible_url) +"</a></td></tr>");
        }
document.write ("</table></td></tr></table>");
  }
                                                                                                                             
function MM_openBrWindow1(theURL,winName,features)
{
        window.open(theURL,winName,features);
}
                                                                                                                             
function get_cookie(Name) {
var search = Name + "="
var returnvalue = "";
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search)
if (offset != -1) {
offset += search.length
end = document.cookie.indexOf(";", offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset, end))
}
}
return returnvalue;
}
                                                                                                                             
function onloadfunction(){
/*if (persistmenu=="yes"){
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=get_cookie(cookiename)
if (cookievalue!="")
document.getElementById(cookievalue).style.display="block"
}*/
}
                                                                                                                             
function savemenustate(){
var inc=1, blockid=""
while (document.getElementById("sub"+inc)){
if (document.getElementById("sub"+inc).style.display=="block"){
blockid="sub"+inc
break
}
inc++
}
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=(persisttype=="sitewide")? blockid+";path=/" : blockid
document.cookie=cookiename+"="+cookievalue
}
if (window.addEventListener)
window.addEventListener("load", onloadfunction, false)
else if (window.attachEvent)
window.attachEvent("onload", onloadfunction)
else if (document.getElementById)
window.onload=onloadfunction
                                                                                                                             
/*if (persistmenu=="yes" && document.getElementById)
window.onunload=savemenustate*/
