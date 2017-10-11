var loc=1;
var loc_array = new Array();
var data_send = 0;
function Ajax()
{
	if (ajaxDebug) this.DebugMessage = function(text) { alert("Ajax Debug:\n " + text) };
	
	this.workId = 'ajaxWork'+ new Date().getTime();
	this.depth = 0;
	
	//Get the XMLHttpRequest Object
	this.getRequestObject = function()
	{
		if (ajaxDebug) this.DebugMessage("Initializing Request Object..");
		var req;
		try
		{
			req=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				req=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e2)
			{
				req=null;
			}
		}
		if(!req && typeof XMLHttpRequest != "undefined")
			req = new XMLHttpRequest();
		
			if (ajaxDebug) {
				if (!req) this.DebugMessage("Request Object Instantiation failed.");
			}
			
		return req;
	}

	this.$ = function(sId)
	{
		if (!sId) {
			return null;
		}
		var returnObj = document.getElementById(sId);
		if (ajaxDebug && !returnObj && sId != this.workId) {
			this.DebugMessage("Element with the id \"" + sId + "\" not found.");
		}
		return returnObj;
	}
	
	this.include = function(sFileName)
	{
		var objHead = document.getElementsByTagName('head');
		var objScript = document.createElement('script');
		objScript.type = 'text/javascript';
		objScript.src = sFileName;
		objHead[0].appendChild(objScript);
	}
	
	this.addHandler = function(sElementId, sEvent, sFunctionName)
	{
		if (window.addEventListener)
		{
			eval("this.$('"+sElementId+"').addEventListener('"+sEvent+"',"+sFunctionName+",false);");
		}
		else
		{
			eval("this.$('"+sElementId+"').attachEvent('on"+sEvent+"',"+sFunctionName+",false);");
		}
	}
	
	this.removeHandler = function(sElementId, sEvent, sFunctionName)
	{
		if (window.addEventListener)
		{
			eval("this.$('"+sElementId+"').removeEventListener('"+sEvent+"',"+sFunctionName+",false);");
		}
		else
		{
			eval("this.$('"+sElementId+"').detachEvent('on"+sEvent+"',"+sFunctionName+",false);");
		}
	}
	
	this.create = function(sParentId, sTag, sId)
	{
		var objParent = this.$(sParentId);
		objElement = document.createElement(sTag);
		objElement.setAttribute('id',sId);
		objParent.appendChild(objElement);
	}
	
	this.insert = function(sBeforeId, sTag, sId)
	{
		var objSibling = this.$(sBeforeId);
		objElement = document.createElement(sTag);
		objElement.setAttribute('id',sId);
		objSibling.parentNode.insertBefore(objElement, objSibling);
	}
	
	this.getInput = function(sType, sName, sId)
	{
		var Obj;
		if (sType == "radio" && !window.addEventListener)
		{
			Obj = document.createElement('<input type="radio" id="'+sId+'" name="'+sName+'">');
		}
		else
		{
			Obj = document.createElement('input');
			Obj.setAttribute('type',sType);
			Obj.setAttribute('name',sName);
			Obj.setAttribute('id',sId);
		}
		return Obj;
	}
	
	this.createInput = function(sParentId, sType, sName, sId)
	{
		var objParent = this.$(sParentId);
		var objElement = this.getInput(sType, sName, sId);
		objParent.appendChild(objElement);
	}
	
	this.insertInput = function(sBeforeId, sType, sName, sId)
	{
		var objSibling = this.$(sBeforeId);
		var objElement = this.getInput(sType, sName, sId);
		objSibling.parentNode.insertBefore(objElement, objSibling);
	}
	
	this.remove = function(sId)
	{
		objElement = this.$(sId);
		if (objElement.parentNode && objElement.parentNode.removeChild)
		{
			objElement.parentNode.removeChild(objElement);
		}
	}
	
	this.replace = function(sId,sAttribute,sSearch,sReplace)
	{
		var bFunction = false;
		
		if (sAttribute == "innerHTML")
			sSearch = this.getBrowserHTML(sSearch);
		
		eval("var txt=document.getElementById('"+sId+"')."+sAttribute);
		if (typeof txt == "function")
        {
            txt = txt.toString();
            bFunction = true;
        }
		if (txt.indexOf(sSearch)>-1)
		{
			var newTxt = '';
			while (txt.indexOf(sSearch) > -1)
			{
				x = txt.indexOf(sSearch)+sSearch.length+1;
				newTxt += txt.substr(0,x).replace(sSearch,sReplace);
				txt = txt.substr(x,txt.length-x);
			}
			newTxt += txt;
			if (bFunction)
			{
				eval("newTxt =" + newTxt); 
				eval('this.$("'+sId+'").'+sAttribute+'=newTxt;');
			}
			else if (this.willChange(sId,sAttribute,newTxt))
			{
				eval('this.$("'+sId+'").'+sAttribute+'=newTxt;');
			}
		}
	}
	
	this.getFormValues = function(frm)
	{
		var objForm;
		var submitDisabledElements = false;
		if (arguments.length > 1 && arguments[1] == true)
			submitDisabledElements = true;
		
		if (typeof(frm) == "string")
			objForm = this.$(frm);
		else
			objForm = frm;
		var sXml = "<xjxquery><q>";
		if (objForm && objForm.tagName == 'FORM')
		{
			var formElements = objForm.elements;
			for( var i=0; i < formElements.length; i++)
			{
				if (formElements[i].type && (formElements[i].type == 'radio' || formElements[i].type == 'checkbox') && formElements[i].checked == false)
					continue;
				if (formElements[i].disabled && formElements[i].disabled == true && submitDisabledElements == false) continue;
				var name = formElements[i].name;
				if (name)
				{
					if (sXml != '<xjxquery><q>')
						sXml += '&';
					if(formElements[i].type=='select-multiple')
					{
						for (var j = 0; j < formElements[i].length; j++)
						{
							if (formElements[i].options[j].selected == true)   sXml += name+"="+encodeURIComponent(formElements[i].options[j].value)+"&";
						}
					}
					else
					{
						sXml += name+"="+encodeURIComponent(formElements[i].value);
					}
				} 
			}
		}
		
		sXml +="</q></xjxquery>";
		
		return sXml;
	}
	
	this.objectToXML = function(obj)
	{
		var sXml = "<xjxobj>";
		for (i in obj)
		{
			try
			{
				if (i == 'constructor')
					continue;
				if (obj[i] && typeof(obj[i]) == 'function')
					continue;
					
				var key = i;
				var value = obj[i];
				if (value && typeof(value)=="object" && 
					(value.constructor == Array
					 ) && this.depth <= 50)
				{
					this.depth++;
					value = this.objectToXML(value);
					this.depth--;
				}
				
				sXml += "<e><k>"+key+"</k><v>"+value+"</v></e>";
				
			}
			catch(e)
			{
				if (ajaxDebug) this.DebugMessage(e);
			}
		}
		sXml += "</xjxobj>";
	
		return sXml;
	}

	this.call = function(sFunction, aArgs, sRequestType, flag)
	{
		/* Code for displaying time		*/
		CurrentTime = new Date();
//		document.getElementById('sendtime').value = CurrentTime;
		/* ends here 		*/
		var i,r,postData;
		if (document.body && ajaxWaitCursor)
			document.body.style.cursor = 'wait';
		if (ajaxStatusMessages == true) window.status = 'Sending Request...';
		if (ajaxDebug) this.DebugMessage("Starting ajax...");
		if (sRequestType == null) {
		   var ajaxRequestType = ajaxDefinedPost;
		}
		else {
			var ajaxRequestType = sRequestType;
		}
		var uri = ajaxRequestUri;
		var value;
		switch(ajaxRequestType)
		{
			case ajaxDefinedGet:{
				var uriGet = uri.indexOf("?")==-1?"?ajax="+encodeURIComponent(sFunction):"&ajax="+encodeURIComponent(sFunction);
				if (aArgs) {
					for (i = 0; i<aArgs.length; i++)
					{
						value = aArgs[i];
						if (typeof(value)=="object")
							value = this.objectToXML(value);
						uriGet += "&ajaxargs[]="+encodeURIComponent(value);
					}
				}
				uriGet += "&ajaxr=" + new Date().getTime();
				uri += uriGet;
				postData = null;
				} break;
			case ajaxDefinedPost:{
				postData = "ajax="+encodeURIComponent(sFunction);
				postData += "&ajaxr="+new Date().getTime();
				if (aArgs) {
					for (i = 0; i <aArgs.length; i++)
					{
						value = aArgs[i];
						if (typeof(value)=="object")
							value = this.objectToXML(value);
						postData = postData+"&ajaxargs[]="+encodeURIComponent(value);
					}
				}
				} break;
			default:
				alert("Illegal request type: " + ajaxRequestType); return false; break;
		}
		r = this.getRequestObject();
		if (!r) return false;
		r.open(ajaxRequestType==ajaxDefinedGet?"GET":"POST", uri, true);
		if (ajaxRequestType == ajaxDefinedPost)
		{
			try
			{
				r.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
				r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			}
			catch(e)
			{
				alert("Your browser does not appear to  support asynchronous requests using POST.");
				return false;
			}
		}
		r.onreadystatechange = function()
		{
			if (r.readyState != 4)
				return;
			
			if (r.status==200)
			{
				if (ajaxDebug && r.responseText.length < 1000) ajax.DebugMessage("Received:\n" + r.responseText);
				else if (ajaxDebug) ajax.DebugMessage("Received:\n" + r.responseText.substr(0,1000)+"...\n[long response]\n...</ajax>");
				if (r.responseXML)
					ajax.processResponse(r.responseXML);
				else {
					alert("Error: the XML response that was returned from the server is invalid.");
					document.body.style.cursor = 'default';
					if (ajaxStatusMessages == true) window.status = 'Invalid XML response error';				
				}
			}
			
			delete r;
		}
		if (ajaxDebug) this.DebugMessage("Calling "+sFunction +" uri="+uri+" (post:"+ postData +")");
		r.send(postData);
		data_send = 1;
		if(flag != 1)
			ajax.saveState(sFunction, aArgs, sRequestType);
		if (ajaxStatusMessages == true) window.status = 'Waiting for data...';
		delete r;
		return true;
	}
	
	this.getBrowserHTML = function(html)
	{
		tmpAjax = this.$(this.workId);
		if (tmpAjax == null)
		{
			tmpAjax = document.createElement("div");
			tmpAjax.setAttribute('id',this.workId);
			tmpAjax.style.display = "none";
			tmpAjax.style.visibility = "hidden";
			document.body.appendChild(tmpAjax);
		}
		tmpAjax.innerHTML = html;
		var browserHTML = tmpAjax.innerHTML;
		tmpAjax.innerHTML = '';	
		
		return browserHTML;
	}
	
	this.willChange = function(element, attribute, newData)
	{
		if (!document.body)
		{
			return true;
		}
		var oldData;
		if (attribute == "innerHTML")
		{
			newData = this.getBrowserHTML(newData);
		}
		eval("oldData=document.getElementById('"+element+"')."+attribute);
		if (newData != oldData)
			return true;
			
		return false;
	}
	
	this.processResponse = function(xml)
	{
		if (ajaxStatusMessages == true) window.status = 'Processing...';
		var tmpAjax = null;
		xml = xml.documentElement;
		if (xml == null) {
			alert("Error: the XML response that was returned from the server cannot be processed.");
			document.body.style.cursor = 'default';
			if (ajaxStatusMessages == true) window.status = 'XML response processing error';
			return;
		}
		for (i=0; i<xml.childNodes.length; i++)
		{
			if (xml.childNodes[i].nodeName == "cmd")
			{
				var cmd;
				var id;
				var property;
				var data;
				var search;
				var type;
				var before;
				
				for (j=0; j<xml.childNodes[i].attributes.length; j++)
				{
					if (xml.childNodes[i].attributes[j].name == "n")
					{
						cmd = xml.childNodes[i].attributes[j].value;
					}
					if (xml.childNodes[i].attributes[j].name == "t")
					{
						id = xml.childNodes[i].attributes[j].value;
					}
					if (xml.childNodes[i].attributes[j].name == "p")
					{
						property = xml.childNodes[i].attributes[j].value;
					}
					if (xml.childNodes[i].attributes[j].name == "c")
					{
						type = xml.childNodes[i].attributes[j].value;
					}
				}
				if (xml.childNodes[i].childNodes.length > 1)
				{
					for (j=0; j<xml.childNodes[i].childNodes.length; j++)
					{
						if (xml.childNodes[i].childNodes[j].nodeName == "s")
						{
							if (xml.childNodes[i].childNodes[j].firstChild)
								search = xml.childNodes[i].childNodes[j].firstChild.nodeValue;
						}
						if (xml.childNodes[i].childNodes[j].nodeName == "r")
						{
							if (xml.childNodes[i].childNodes[j].firstChild)
								data = xml.childNodes[i].childNodes[j].firstChild.data;
						}
					}
				}
				else if (xml.childNodes[i].firstChild)
					data = xml.childNodes[i].firstChild.nodeValue;
				else
					data = "";
				
				var objElement = this.$(id);
				try
				{
					if (cmd=="al")
					{
						alert(data);
					}
					if (cmd=="js")
					{
						eval(data);
					}
					if (cmd=="in")
					{
						this.include(data);
					}
					if (cmd=="as")
					{
						if (this.willChange(id,property,data))
						{
							eval("objElement."+property+"=data;");
						}
					}
					if (cmd=="ap")
					{
						eval("objElement."+property+"+=data;");
					}
					if (cmd=="pp")
					{
						eval("objElement."+property+"=data+objElement."+property);
					}
					if (cmd=="rp")
					{
						this.replace(id,property,search,data)
					}
					if (cmd=="rm")
					{
						this.remove(id);
					}
					if (cmd=="ce")
					{
						this.create(id,data,property);
					}
					if (cmd=="ie")
					{
						this.insert(id,data,property);
					}
					if (cmd=="ci")
					{
						this.createInput(id,type,data,property);
					}
					if (cmd=="ii")
					{
						this.insertInput(id,type,data,property);
					}
					if (cmd=="ev")
					{
						eval("this.$('"+id+"')."+property+"= function(){"+data+";}");
					}
					if (cmd=="ah")
					{
						this.addHandler(id, property, data);
					}
					if (cmd=="rh")
					{
						this.removeHandler(id, property, data);
					}
				}
				catch(e)
				{
//					alert(e);
				}
				delete objElement;
				delete cmd;
				delete id;
				delete property;
				delete search;
				delete data;
				delete type;
				delete before;
			}	
		}
		delete xml;
		document.body.style.cursor = 'default';
		if (ajaxStatusMessages == true) window.status = 'Done';
	}

	//State is saved in AJAX for enabling back button
	this.saveState = function(sFunction, aArgs, sRequestType)
        {
		var temp = Array(sFunction, aArgs, sRequestType);
		var current = window.location.href;
	        if(current.indexOf('#') != -1)
	                current = current.substring(0,current.indexOf('#'));
		window.location.href = current + '#' + loc;
		var hash = getHash('url');
		var newsrc = 'mock.php?hash='+hash;
		if(document.iframesfix)
		{
                        ajaxnav.location.href = newsrc;
			ac = ajaxnav.location.href;
		}
		loc_array[loc++] = temp;
	}
}
var ajax = new Ajax();

//GetHash() returns the part after the hash, be it 'hash=' (IE) or '#' (Others)
function getHash(where)
{
	var current = window.location.href;
	hash_part = 0;
        if(current.indexOf('#') != -1)
        {
                var url_elements = current.split('#');
                hash_part = url_elements[url_elements.length-1];
        }
	if(where == 'url')
		return hash_part;
	//For IE
	if(document.iframesfix)
	{	
		if(document.getElementById('ajaxnav'))
		{
			var iframe_src = ajaxnav.location.href;
			var url_elements = iframe_src.split('hash=');
			hash_part = url_elements[url_elements.length-1];
		}
	}
	return hash_part;
}

//Function to check URL every 500 milli-seconds
function checkWhetherChanged(location) 
{
	var current = window.location.href;
	var hash_part = getHash('url');
	if(current != location && data_send == 0 && loc_array[hash_part])
	{
		ajax.call(loc_array[hash_part][0],loc_array[hash_part][1],loc_array[hash_part][2],1);
	}
												       
	data_send = 0;
	var str = "checkWhetherChanged('"+current+"')"
	timer2 = setTimeout(str,500);
}

//Function to check Iframe URL every 500 milli-seconds
function checkWhetherIframeChanged(location)
{
	var current = ajaxnav.location.href;
	var hash_part = getHash('iframe');
	if(current != location && data_send == 0 && loc_array[hash_part])
	{
		ajax.call(loc_array[hash_part][0],loc_array[hash_part][1],loc_array[hash_part][2],1);
	}
	data_send = 0;
        var str = "checkWhetherIframeChanged('"+current+"')"
        timer2 = setTimeout(str,2000);
}

/* CROSS-BROWSER EVENT HANDLER */
function addEvent(obj, evType, fn)
{
	 if (obj.addEventListener)
	 {
		 obj.addEventListener(evType, fn, true);
		 return true;
	 } 
	 else if (obj.attachEvent)
	 {
		 var r = obj.attachEvent("on"+evType, fn);
		return r;
	 } 
	 else 
	 {
		return false;
	 }
}

function FixBackAndBookmarking() 
{
	if(!document.getElementById || !document.getElementsByTagName) return;
	if(document.iframesfix) 
	{
		var current = ajaxnav.location.href;
		var str = "checkWhetherIframeChanged('"+current+"')"
                timer2 = setTimeout(str,2000);
	} 
	else 
	{
		var current = window.location.href;
		var str = "checkWhetherChanged('"+current+"')"
		timer2 = setTimeout(str,2000);
	}
}

function nothing()
{
	
}
	
var detect = navigator.userAgent.toLowerCase();
if(detect.indexOf("msie")>-1) 
	document.iframesfix = true;
addEvent(window, "load", FixBackAndBookmarking);
