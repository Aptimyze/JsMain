/*
 File created by nikhil tandon to log all javascript errors taking place at the client end.
 The errors are returned to the server by an ajax (RPC)request.
*/
try{
window.onerror = trapError;
}catch(e){}
function trapError(msg, URI, ln)
{
	return true;//not logging errors now
	s="plugin.SetWindow";
	if((msg.indexOf(s))>=0)return;
        // wrap our unknown error condition in an object
	var error = new Error(msg);
	error.location = URI; // add custom property
	error.line=ln;
	systemParameters();
	Logger(error);
	return true; 
}
var req;
function Logger(err)
{
	if (window.XMLHttpRequest) req = new XMLHttpRequest();
        else if (window.ActiveXObject) req =
                new ActiveXObject("Microsoft.XMLHTTP");
        else return;
	req.open("GET", "/messenger_new/ajaxlog.php?name="+err.name+"&message="+err.message+"&location="+err.location+"&line="+err.line+"&version="+version+"&os="+OS+"&browser="+browser);
        req.onreadystatechange = errorLogged;
        req.send('null');
	timeout = window.setTimeout("abortLog();", 10000);
}

function errorLogged()
{
	try{
        if (req.readyState != 4) return;
        window.clearTimeout(timeout);
	}
	catch(e)
        {}
        // request completed
        //if (req.status >= 400)
                //alert('Attempt to log the error failed.');
}

function abortLog()
{
        req.abort();
}
var OS;
var version;
function systemParameters()
{
	ua=navigator.userAgent;
	s="MSIE";
	OS=navigator.platform;
	if((i=ua.indexOf(s))>=0)
	{
		version=parseFloat(ua.substr(i+s.length));
		browser=s;
		return;
	}
	s="Netscape6/";
	if((i=ua.indexOf(s))>=0)
	{
		browser=s;
		version=parseFloat(ua.substr(i+s.length));
		return;
	}
	s="Gecko";
	if((i=ua.indexOf(s))>=0)
	{
		version=6.1;
		browser=s;
		return;
	}
}
