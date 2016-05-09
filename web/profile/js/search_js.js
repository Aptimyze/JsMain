var BoxHeights =
{
        maxh: 0,
        boxes: Array(),
        num: 0,
        op_test: false,
                                                                                                                             
        equalise: function()
        {
                this.num = arguments.length;
                for (var i=0;i<this.num;i++)
                {
                        if (!$(arguments[i]))
                        {
                                if(i==2)
                                        this.num = arguments.length-1;
                                else
                                        return;
                        }
                }
                this.boxes = arguments;
                this.maxheight();
                for (var i=0;i<this.num;i++)
                {
                        $(arguments[i]).style.height = this.maxh+"px";
                }
        },
        maxheight: function()
        {
                var heights = new Array();
                for (var i=0;i<this.num;i++)
                {
			 if (navigator.userAgent.toLowerCase().indexOf('opera') == -1)
                        {
                                heights.push($(this.boxes[i]).scrollHeight);
                        }
                        else
                        {
                                heights.push($(this.boxes[i]).offsetHeight);
                        }
                }
                heights.sort(this.sortNumeric);
                this.maxh = heights[this.num-1];
        },
        sortNumeric: function(f,s)
        {
                return f-s;
        }
}

/*function up_launch(receiversid, sendersid,senderusername,receiverusername,threadname,status)
{
	if(senderusername>receiverusername)
		threadname=senderusername+"_"+receiverusername;
	else
		threadname=receiverusername+"_"+senderusername;
	threadname=up_replaceAlpha(threadname);
	window.open('http://'+CHAT_URL+'/profile/chatwindow.php?receiversid='+receiversid+'&sendersid='+sendersid+'&senderusername='+senderusername+'&receiverusername='+receiverusername+"&status="+status+"&checksum=~$CHECKSUM`",threadname,'width=342,height=274,status=1,scrollbars=0,resizable=no');
}*/
																														   
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
                                                                                                                             
function MM_openBrWindow(theURL,winName,features)
{
	window.open(theURL,winName,features);
}
														     
function changeAction(actionName)
{
	document.form1.SELaction.value=actionName;
}

function $()
{
        var elements = new Array();
        for (var i=0;i<arguments.length;i++)
        {
                var element = arguments[i];
                if (typeof element == 'string') element = document.getElementById(element);
                if (arguments.length == 1) return element;
                elements.push(element);
        }
        return elements;
}
function MM_openProfileWindow(theURL,winName,features)
{
        window.open(theURL,winName,'width=760,height=570,resizable=1,scrollbars=1');
}
function GetXmlHttpObject(handler)
{
	var objXmlHttp=null
																														   
	if (navigator.userAgent.indexOf("Opera")>=0)
	{
		try
		{
		alert("This doesn't work in Opera")
		return;
		}
		catch(e)
		{
		alert("Error. Scripting for ActiveX might be disabled")
		return
		}
	}
	if (navigator.userAgent.indexOf("MSIE")>=0)
	{
		var strName="Msxml2.XMLHTTP"
		if (navigator.appVersion.indexOf("MSIE 5.5")>=0 || navigator.appVersion.indexOf("MSIE 6")>=0)
		{
			strName="Microsoft.XMLHTTP"
			try
			{
				objXmlHttp=new ActiveXObject(strName)
				objXmlHttp.onreadystatechange=handler
				return objXmlHttp
			}
			catch(e)
			{
				alert("Error. Scripting for ActiveX might be disabled")
				return
			}
		}
		else if(navigator.appVersion.indexOf("MSIE 7")>=0)
		{
			try
                        {
				objXmlHttp = new XMLHttpRequest()
			}
			catch(e)
			{
				alert("Error. Scripting for ActiveX might be disabled")
	                        return
			}
		}
	}
	if (navigator.userAgent.indexOf("Mozilla")>=0)
	{
		try
		{
		objXmlHttp=new XMLHttpRequest()
		objXmlHttp.onload=handler
		objXmlHttp.onerror=handler
		return objXmlHttp
		}
		catch(e)
		{
		alert("Error. Scripting for ActiveX might be disabled")
		return
		}
	}
}
														     
function stateChanged()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		try
		{
			//alert('here');
			//xmlDoc= xmlHttp.responseXML;
		}
		catch(e)
		{
			alert("Error")
		}
														     
	}
}

function contact_layer(layer_id,action)
{
	var divs = document.getElementsByTagName("div");
	var j=0;
	var contact_layers = new Array();
	for(var i=0; i<divs.length; i++)
	{
		if(divs[i].id.match("contact_layer_"))
		{
			contact_layers[j] = divs[i].id;
			j++
		}
	}
	if(action=='open')
	{
		for(var i=0;i<contact_layers.length; i++)
		{
			if(layer_id == contact_layers[i])
				document.getElementById(contact_layers[i]).style.display = "block";
			else
				document.getElementById(contact_layers[i]).style.display = "none";
		}
	}
	else if(action=='close')
		document.getElementById(layer_id).style.display = "none";
}
