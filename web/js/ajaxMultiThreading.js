var xmlreqs = new Array(); 
function CXMLReq(type, xmlhttp) 
{ 
	this.type = type; this.xmlhttp = xmlhttp; 
} 
function xmlreqGET(url) 
{ 
	var xmlhttp=false; if (window.XMLHttpRequest) 
	{ 
		xmlhttp=new XMLHttpRequest(); 
		xmlhttp.onreadystatechange = xmlhttpChange; 
		xmlhttp.open("GET",url,true); xmlhttp.send(null); 
	} 
	else if (window.ActiveXObject) 
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
		if (xmlhttp) 
		{ 
			xmlhttp.onreadystatechange = xmlhttpChange; 
			xmlhttp.open("GET",url,true); xmlhttp.send(); 
		} 
	} 
	var xmlreq = new CXMLReq('', xmlhttp); xmlreqs.push(xmlreq); 
} 

function xmlreqPOST(url,data) 
{ 
	var xmlhttp=false; 
	if (window.XMLHttpRequest) 
	{ 
		// Mozilla etc. 
		xmlhttp=new XMLHttpRequest(); 
		xmlhttp.onreadystatechange=xmlhttpChange; 
		xmlhttp.open("POST",url,true); 
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		xmlhttp.send(data); 
	} 
	else if (window.ActiveXObject) 
	{ 
		// IE 
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
		if (xmlhttp) 
		{ 
			xmlhttp.onreadystatechange=xmlhttpChange; 
			xmlhttp.open("POST",url,true); 
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			xmlhttp.send(data); 
		} 
	} 
	var xmlreq = new CXMLReq('', xmlhttp); 
	xmlreqs.push(xmlreq); 
} 

function xmlhttpChange() 
{ 
	if (typeof(window['xmlreqs']) == "undefined") 
		return; 
	var xmldoc = null; 
	for(var i=0; i < xmlreqs.length; i++) 
	{ 
		if (xmlreqs[i].xmlhttp.readyState == 4) 
		{ 
			if (xmlreqs[i].xmlhttp.status == 200 || xmlreqs[i].xmlhttp.status == 304) 
			{ 
				if (document.implementation && document.implementation.createDocument) 
				{ 
					xmldoc = document.implementation.createDocument("", "", null); 
				} 
				else if (window.ActiveXObject) 
				{ 
					xmldoc = new ActiveXObject("Microsoft.XMLDOM"); 
				} 
				captureResponse(xmlreqs[i].xmlhttp.responseText);
				xmlreqs.splice(i,1); i--; 
			} 
			else 
			{ 
				xmlreqs.splice(i,1); i--; 
			} 
		} 
	} 
}
