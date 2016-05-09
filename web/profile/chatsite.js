var receiversid, sendersid,senderusername,receiverusername,threadname,status,initialtitle;
var current_decline="";
var nikhil = new Array();
var nikhil1=0,browser,version;
systemP();
var nv=0;
if((browser=="MSIE" && version>5) || browser=="Netscape6/")nv=1;
else nv=0;
nikhil[0]="";
window.onerror = trapError;
function trapError(msg, URI, ln)
{
	alert("\n message="+msg+"\n URI="+URI+"\n line="+ln);
	return true;
}
function myMem_DoFSCommand(command,args){if(command=="newmessage"){parseAndShow(args);}}
function systemP()
{
        ua=navigator.userAgent;s="MSIE";OS=navigator.platform;
        if((i=ua.indexOf(s))>=0)
        {version=parseFloat(ua.substr(i+s.length));browser=s;return;}
        s="Netscape6/";
        if((i=ua.indexOf(s))>=0)
        {browser=s;version=parseFloat(ua.substr(i+s.length));return;}
        s="Gecko";
        if((i=ua.indexOf(s))>=0)
        {version=6.1;browser=s;return;}
}
function putmessenger()
{
	alert("here");
	document.write('<embed src="/messenger/m.swf?username1=~$CURRENTUSERNAME`&pid=~$PROFILEID4CHAT`&gender=~$gender`&nv='+nv+'" width="1" height="1" name="myMem" id="myMem" swLiveConnect="true"></embed>');
}
function parseAndShow(strIn)
{
        k=strIn.length;u=0;var a=new Array();
        for(i=0;i<k;i++)
        {
                if(!a[i])a[i]="";
                cChar = strIn.charAt(i);
                if(cChar=="|")u++;
                else a[u]+=cChar;
        }
        acceptdecline(a[0],a[1],a[2],a[3],'','receiver');
}
function up_launch1()
{
        accept_js();
        k=window.open('/profile/chatwindow.php?receiversid='+receiversid+'&sendersid='+sendersid+'&senderusername='+senderusername+'&receiverusername='+receiverusername+"&status="+status+"&checksum=4049f46696d549c65f5832e15664afddi13267",threadname,'width=342,height=274,status=1,scrollbars=0,resizable=yes');
}
function generate(senderusername,receiverusername)
{
        if(senderusername>receiverusername)r=receiverusername+"_"+senderusername;
        else r=senderusername+"_"+receiverusername;
        return r;
}
function acceptdecline(r,s,sd,re,t,st)
{
        initialtitle=document.title;
        receiversid=r;sendersid=s;senderusername=sd;receiverusername=re;threadname=t;status=st;
        if(!threadname)threadname=generate(senderusername,receiverusername);
        threadname=up_replaceAlpha(threadname);
	if(check_if_decline(senderusername))
		decline_js(1)
	else{
	newtitlez("Chat-Request");
        nikhil1++;
        nikhil2=nikhil1-1;
        nikhil[nikhil1]=tablestyle(senderusername);
        if(nikhil2>0&&nikhil[nikhil1]&&nikhil[nikhil2]&&nikhil[nikhil1]==nikhil[nikhil2])
                nikhil1=nikhil1-1;
        document.getElementById('accept_decline').innerHTML =nikhil[nikhil1];
        document.getElementById('accept_decline').innerHTML+='<td align="center" bgcolor="#bfd0ea">You have '+ nikhil1 + ' pending chat request';
        window.focus();
        document.getElementById('accept_decline').style.display = "block";
        if(nikhil1>1)
                document.getElementById('accept_decline').innerHTML+='s.';
        document.getElementById('accept_decline').innerHTML+='</td></table>';
	}
}
function tablestyle(senderusername)
{
	current_decline=senderusername;
        showaccept_table='<table><td bgcolor="#bfd0ea" align="center"><a href="/profile/viewprofile.php?username='+senderusername+'&VIEWING_PROFILE=0" target="_blank" onclick="view_js();">'+senderusername+'Requested a chat with you.</a><br></td><td bgcolor="#bfd0ea" valign="bottom"><a href="" onclick="up_launch1();return false;"><img alt="yes" title="yes" src="/messenger/nr_yes.gif" border="0"></a>&nbsp;<a href="" onclick="decline_js(0);return false;"><img alt="no" title="no" src="/messenger/nr_no.gif" border="0"></a></td>';
        return showaccept_table;
}
function accept_js()
{
        try{
        window.document.myMem.SetVariable("accept_js","1");
        window.document.myMem.GotoFrame(6);
        }catch(e){}
        shownext();
}
function shownext()
{
        nikhil1=nikhil1-1;
        if(nikhil1>0)
                document.getElementById('accept_decline').innerHTML=nikhil[nikhil1];
        else
        {
                closethisbox();
                sitetitle(initialtitle);
        }
}
function view_js()
{
        try{
        window.document.myMem.SetVariable("view_js","1");
        window.document.myMem.GotoFrame(8);
        }catch(e){}
}
function decline_js(wha)
{
	try{
                window.document.myMem.SetVariable("decline_js","1");
                window.document.myMem.GotoFrame(7);
        }catch(e){}

	if(wha==0)
	{
		val=readCookie('jssitechat');
		if(val)	current_decline=val+current_decline+"|";
		else current_decline=current_decline+"|";
		createCookie('jssitechat',current_decline,'');
        	shownext();
	}
}
function check_if_decline(sender)
{
	val=readCookie('jssitechat');
	if(!val)return false;
	len=val.length;
	var t="";
	for(i=0;i<len;i++)
	{
		var cChar = val.charAt(i);
                if(cChar == "|"){if(t==sender)return true;else t="";}
		else t=t+cChar;
	}
	return false;
}

function readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
/*
function readCookie(cookieName) {
 var theCookie=""+document.cookie;
 var ind=theCookie.indexOf(cookieName);
 if (ind==-1 || cookieName=="") return ""; 
 var ind1=theCookie.indexOf(';',ind);
 if (ind1==-1) ind1=theCookie.length; 
 return unescape(theCookie.substring(ind+cookieName.length+1,ind1));
}*/
function createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function closethisbox()
{
        document.getElementById('accept_decline').style.display = "none";
}
function up_replaceAlpha( strIn )
{
        var strOut = "";
        for( var i = 0 ; i < strIn.length ; i++ )
        {
                var cChar = strIn.charAt(i);
                if( ( cChar >= 'A' && cChar <= 'Z' )
                        || ( cChar >= 'a' && cChar <= 'z' )
                        || ( cChar >= '0' && cChar <= '9' ) )
                {
                        strOut += cChar;
                }
                else
                {
                        strOut += "_";
                }
        }
                                                                                                 
        return strOut;
}
