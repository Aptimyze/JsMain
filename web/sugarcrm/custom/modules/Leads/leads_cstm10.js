
function createXMLHttpRequestObject()
       {
   var xmlHttp;
   if (window.XMLHttpRequest) {
       xmlHttp = new XMLHttpRequest();
    }
    // IE
else if (window.ActiveXObject) {
   xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
}
if (!xmlHttp)
         alert("Error creating the Request object");
    else
         return xmlHttp;
}
function xmlhttpPost(field)
{
	if(field=='phone_mobile')
		var res='result1';
	else
		var res='result';
		var field_val = document.getElementById(field).value;
	if(field_val=='')	
	{
		document.getElementById(res).innerHTML='';
		return true;
	}
    var xmlHttp = createXMLHttpRequestObject();
    if (xmlHttp) {
                    document.getElementById(res).innerHTML = 'Checking...'
                    //xmlHttp.open('POST', 'index.php?entryPoint=LA', true);
		    xmlHttp.open('POST', 'custom/modules/Leads/LA.php', true);
                    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xmlHttp.onreadystatechange = function() {
                        if (xmlHttp.readyState == 4) {
                            updatepage(xmlHttp.responseText,field);
                        }
                    }
                    xmlHttp.send(getquerystring(field));
    }
}
function getquerystring(field) {
		var field_val = document.getElementById(field).value;
	    qstr = field+'=' + escape(field_val) + "&lead_type=SR"; // NOTE: no '?'before querystring
	var lead= document.EditView.record.value;
	if(lead)
		qstr = qstr+"&lead="+lead;
		if(field=='phone_home')
		{
			var std= document.getElementById('std_c').value;
			qstr = qstr+"&std="+std;
		}
    return qstr;
}
function updatepage(str,field_name){
       var msg='';
       /*var s = str.indexOf("phone_mobile");
       var e = str.indexOf("</msg>");
	if(s>1)*/
	if(field_name=='phone_mobile')
	{
		var res='result1';
       		//msg = str.substring(17,e);
		msg=trim(str);
	}
	else
	{
		if(field_name=='phone_home')
		{
			var res='result';
			/*if(str.indexOf("phone_home")>1)
			{
				msg = str.substring(15,e);
			}*/
			msg=trim(str);
		}
	}
	if(msg=='')
	{
		document.getElementById(res).innerHTML='';
		return false;
	}
	else
		document.getElementById(res).innerHTML = msg;
}


var arr;
var arr1;
if(document.EditView)
{
function Check() {
if(document.EditView.caste_c || document.EditView.religion_c) {
        var caste_c = document.EditView.caste_c.options;
        arr = new Array;
        for(i=0; i<caste_c.length; i++) {
arr.push(caste_c[i].value, caste_c[i].text);
}
}
initData();
}
}

function initData(){
if(document.EditView)
{
        var current_p= document.EditView.religion_c;
        var code_p = current_p.value;
        var current_v= document.EditView.caste_c;
        var code_v = current_v.value;
        var code_v_idx = 0;

        var select_ticket = document.EditView.caste_c.options;
        select_ticket.length=0;
        var l = 0;
        for(k=0; k<arr.length; k+=2) {
         if(arr[k].substr(0,1) == code_p || arr[k] == '') {
         select_ticket.length++;
         select_ticket[select_ticket.length-1].value = arr[k];
         select_ticket[select_ticket.length-1].text = arr[k+1];
         if(code_v == arr[k]){
                 code_v_idx = l;
         }
         l++;
         }
        }
        if(code_p == ''){
                select_ticket[select_ticket.length-1].value = '';
         select_ticket[select_ticket.length-1].text = 'Select from religion';
        }
        document.EditView.caste_c.selectedIndex = code_v_idx;;
}
}


if(document.EditView)
{
if (window.addEventListener)
window.addEventListener("load", Check, false);
else if (window.attachEvent)
window.attachEvent("onload", Check);
else if (document.getElementById)
window.onload=Check;
}


