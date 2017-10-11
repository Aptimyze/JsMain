/*************************************************************************

  dw_viewport.js
  version date Nov 2003
  
  This code is from Dynamic Web Coding 
  at http://www.dyn-web.com/
  Copyright 2003 by Sharon Paine 
  See Terms of Use at http://www.dyn-web.com/bus/terms.html
  regarding conditions under which you may use this code.
  This notice must be retained in the code as is!

*************************************************************************/  
  
var viewport = {
  getWinWidth: function () {
    this.width = 0;
    if (window.innerWidth) this.width = window.innerWidth - 18;
    else if (document.documentElement && document.documentElement.clientWidth) 
  		this.width = document.documentElement.clientWidth;
    else if (document.body && document.body.clientWidth) 
  		this.width = document.body.clientWidth;
  },
  
  getWinHeight: function () {
    this.height = 0;
    if (window.innerHeight) this.height = window.innerHeight - 18;
  	else if (document.documentElement && document.documentElement.clientHeight) 
  		this.height = document.documentElement.clientHeight;
  	else if (document.body && document.body.clientHeight) 
  		this.height = document.body.clientHeight;
  },
  
  getScrollX: function () {
    this.scrollX = 0;
  	if (typeof window.pageXOffset == "number") this.scrollX = window.pageXOffset;
  	else if (document.documentElement && document.documentElement.scrollLeft)
  		this.scrollX = document.documentElement.scrollLeft;
  	else if (document.body && document.body.scrollLeft) 
  		this.scrollX = document.body.scrollLeft; 
  	else if (window.scrollX) this.scrollX = window.scrollX;
  },
  
  getScrollY: function () {
    this.scrollY = 0;    
    if (typeof window.pageYOffset == "number") this.scrollY = window.pageYOffset;
    else if (document.documentElement && document.documentElement.scrollTop)
  		this.scrollY = document.documentElement.scrollTop;
  	else if (document.body && document.body.scrollTop) 
  		this.scrollY = document.body.scrollTop; 
  	else if (window.scrollY) this.scrollY = window.scrollY;
  },
  
  getAll: function () {
    this.getWinWidth(); this.getWinHeight();
    this.getScrollX();  this.getScrollY();
  }
  
}

 var notesarray=new Array('','','','','','','','','','','','','');
function closesuccessmsg()
{
	document.getElementById('successmsg').style.visibility='hidden';
}
function checkcontent(fieldname,numname, formname)
{
	var str=document.forms[formname].elements[fieldname].value.length;
	if(str>250)
	{
		var msg=document.forms[formname].elements[fieldname].value.substring(0,250);
		document.forms[formname].elements[fieldname].value=msg;
		document.forms[formname].elements[numname].value=0;
	}
	else
	{
		str=250-str;
		document.forms[formname].elements[numname].value=str;
	}

}

function ajaxFunction(formname,divname,fieldname,bookmarkee,bookmarker,n,linkname,siteurl)
{
	var xmlHttp;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		// Internet Explorer
                try
                {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                alert("Your browser does not support AJAX!");
                                return false;
                        }
                }
        }
        xmlHttp.onreadystatechange=function()
        {
                if(xmlHttp.readyState==4)
                {
                        //document.forms[formname].elements[fieldname].value=xmlHttp.responseText;
                }
        }

        var x=(document.forms[formname].elements[fieldname].value).replace(/\n/g," ");
        var y=escape(x);
	if(n==1)
        {
        	xmlHttp.open("GET",siteurl+"/profile/bookmarktest.php?bookmarker="+bookmarker+"&bkmarkee="+bookmarkee+"&text="+y,true);
                document.getElementById(linkname).display='none';
                document.getElementById(linkname).style.visibility='hidden';
                if(document.getElementById('successmsg').style.visibility=='hidden')
                document.getElementById('successmsg').style.visibility='visible';
                var y=document.getElementById(divname).style.left;
                document.getElementById('successmsg').style.left=y;
                y=document.getElementById(divname).style.top;
                document.getElementById('successmsg').style.top=y;
                setTimeout("closesuccessmsg()",2000);
        }
        else
        {
                xmlHttp.open("GET","bookmarktest.php?edit=1&bookmarker="+bookmarker+"&bookmarkee="+bookmarkee+"&text="+y,true);
                if(divname.length==3)
                var index=divname.substr(1,2);
                else
                var index=divname.substr(1,1);
                if(x=='')
                notesarray[index]="blank";
                else
                notesarray[index]=x;
        }
        xmlHttp.send(null);
        document.getElementById(divname).style.visibility='hidden';
        return false;

}
function showmsgedit(formname,fieldname,numname,divname,dragdivname,txt)
{
            if(theTimeout)
            clearTimeout(theTimeout);
            if(divname.length==2)
            var index=divname.substr(1,1);
            else
            var index=divname.substr(1,2);
            if(txt!="")
            {
                 var txt1=txt.replace("#n#","\n");
                 while(txt1.length!=txt.length)
                 {
                           txt=txt1;
                           txt1=txt.replace("#n#","\n");
                 }
            }
            if(txt!=notesarray[index] && notesarray[index]!="")
            {
                if(notesarray[index]!="blank")
                {
                        txt=notesarray[index];
                }
                else
                {
                        txt="";
                }
            }
            cleardiv(formname);
            document.getElementById(dragdivname).style.display='none';
            if(document.getElementById(divname).style.display=='none')
            document.getElementById(divname).style.display='inline';
            document.getElementById(divname).style.visibility='visible';
            var y=document.getElementById(dragdivname).style.left;
            document.getElementById(divname).style.left=y;
            y=document.getElementById(dragdivname).style.top;
            document.getElementById(divname).style.top=y;
            document.forms[formname].elements[fieldname].value=txt;
            y=txt.length;
            document.forms[formname].elements[numname].value=250-y;
            document.forms[formname].elements[fieldname].focus();
            return false;
}
function hidemsg(divname)
{
	theTimeout=setTimeout("disappear('"+divname+"')",500);
}
function disappear(divname)
{
	document.getElementById(divname).style.display='none';
}
function showmsg(divname)
{
	if(theTimeout)
	clearTimeout(theTimeout);
	document.getElementById(divname).style.display='inline';
}
function hidemsgedit(divname)
{
	document.getElementById(divname).style.visibility='hidden';
}
function showmsgadd(e, offx, offy, divname,formname,origmsg,bkmark)
{
	cleardiv(formname);
	if(divname.length==3)
	var index=divname.substr(1,2);
	else
	var index=divname.substr(1,1);
	if((index==1) || (index==4) || (index==7) || (index==10))
	offx=1;
	if((index==3) || (index==6) || (index==9) || (index==12))
	offx=-250;
	if(!origmsg)
	origmsg='';
	if(bkmark==1)
	{
		divname='l'+index;
		var dragdivname='t'+index;
	}
	var x=0, y=0; viewport.getAll();
	var o=document.getElementById(divname);
	// check positioning choices
	if ( this.offX == "c" )
	{
		x = Math.round( (viewport.width - o.offsetWidth)/2 ) + viewport.scrollX;
	}
	else
	{  // use mouse location onclick to position
		x = e.pageX? e.pageX: e.clientX + viewport.scrollX;
		offx = offx || this.offX;  // check for passed offsets
		if ( x + o.offsetWidth + offx > viewport.width + viewport.scrollX )
		x = viewport.width + viewport.scrollX - o.offsetWidth;
		else x = x + offx;
	}
	if ( this.offY == "c" )
	{
		y = Math.round( (viewport.height - o.offsetHeight)/2 ) + viewport.scrollY;
	}
	else
	{
		y = e.pageY? e.pageY: e.clientY + viewport.scrollY;
		offy = offy || this.offY;
		if ( y + o.offsetHeight + offy > viewport.height + viewport.scrollY )
		y = viewport.height + viewport.scrollY - o.offsetHeight;
		else y = y + offy;
	}
	document.getElementById(divname).style.left = x + "px";
	document.getElementById(divname).style.top = y + "px";
	document.getElementById(divname).style.visibility='visible';
	document.getElementById(divname).style.display="inline";
	if(origmsg!="")
	{
		var origmsg1=origmsg.replace("#n#","\n");
		while(origmsg1.length!=origmsg.length)
		{
			   origmsg=origmsg1;
			   origmsg1=origmsg.replace("#n#","\n");
		}
	}
	if(bkmark==1)
	{
		if(origmsg!=notesarray[index] && notesarray[index]!="")
		{
			if(notesarray[index]!="blank")
			{
				document.getElementById(dragdivname).innerHTML=wordwrap(notesarray[index]);
			}
			else
			{
				document.getElementById(dragdivname).innerHTML="";
			}
		}
		else
		{
			if(origmsg!="")
			document.getElementById(dragdivname).innerHTML=wordwrap(origmsg);
		}

	}
	else
	eval('document.'+formname+'.e'+index+'.focus()');
}
function cleardiv(formname)
{
	if(document.getElementById('f1'))
	{
		if(document.getElementById('f1').style.visibility=='visible')
		{
			document.forms[formname].elements['e1'].blur();
			document.getElementById('f1').style.visibility='hidden';
		}
	}
	if(document.getElementById('f2'))
	{
		if(document.getElementById('f2').style.visibility=='visible')
		{
			document.forms[formname].elements['e2'].blur();
			document.getElementById('f2').style.visibility='hidden';
		}
	}
	if(document.getElementById('f3'))
	{
		if(document.getElementById('f3').style.visibility=='visible')
		{
			document.forms[formname].elements['e3'].blur();
			document.getElementById('f3').style.visibility='hidden';
		}
	}
	 if(document.getElementById('f4'))
	{
		if(document.getElementById('f4').style.visibility=='visible')
		{
			document.forms[formname].elements['e4'].blur();
			document.getElementById('f4').style.visibility='hidden';
		}
	}
	if(document.getElementById('f5'))
	{
		if(document.getElementById('f5').style.visibility=='visible')
		{
			document.forms[formname].elements['e5'].blur();
			document.getElementById('f5').style.visibility='hidden';
		}
		}
	if(document.getElementById('f6'))
	{
		if(document.getElementById('f6').style.visibility=='visible')
		{
			document.forms[formname].elements['e6'].blur();
			document.getElementById('f6').style.visibility='hidden';
		}
	}
	if(document.getElementById('f7'))
	{
		if(document.getElementById('f7').style.visibility=='visible')
		{
			document.forms[formname].elements['e7'].blur();
			document.getElementById('f7').style.visibility='hidden';
		}
	}
	 if(document.getElementById('f8'))
	{
		if(document.getElementById('f8').style.visibility=='visible')
		{
			document.forms[formname].elements['e8'].blur();
			document.getElementById('f8').style.visibility='hidden';
		}
	}
	if(document.getElementById('f9'))
	{
		if(document.getElementById('f9').style.visibility=='visible')
		{
			document.forms[formname].elements['e9'].blur();
			document.getElementById('f9').style.visibility='hidden';
		}
	}
	if(document.getElementById('f10'))
	{
		if(document.getElementById('f10').style.visibility=='visible')
		{
			document.forms[formname].elements['e10'].blur();
			document.getElementById('f10').style.visibility='hidden';
		}
	}
	if(document.getElementById('f11'))
	{
		if(document.getElementById('f11').style.visibility=='visible')
		{
			document.forms[formname].elements['e11'].blur();
			document.getElementById('f11').style.visibility='hidden';
		}
	}
	if(document.getElementById('f12'))
	{
		if(document.getElementById('f12').style.visibility=='visible')
		{
			document.forms[formname].elements['e12'].blur();
			document.getElementById('f12').style.visibility='hidden';
		}
	}
}
function wordwrap(txt)
{
	var a=txt;
	var final='';
	if(a)
	{
	while(a.length>30)
	{
		var display=a.substr(0,30);
		if(display[29]=='')
		{
			a=a.substr(30,a.length-30);
		}
		else
		{
			for(var i=display.length-1;i>=0;i--)
			{
				if(display[i]==" ")
				break;
			}
			if(i>=19)
			{
				var leftover=display.substr(i+1,display.length-i-1);
				display=display.substr(0,i+1);

				a=a.substr(30,a.length-30);
				a=leftover+a;

			}
			else
			a=a.substr(30,a.length-30);
		}
		final=final+display+"<br>";
	}
	final=final+a;
	return final;
	}
}

