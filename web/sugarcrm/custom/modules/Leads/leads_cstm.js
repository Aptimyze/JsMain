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
function xmlhttpPost(field,element)
{
	if(field=='phone_mobile')
		var res='result1';
	else
		if(field=='phone_home')
			var res='result';
		else
			if(field=='enquirer_landline_c')
				var res='result2';
			else
				if(field=='enquirer_email_id_c')
					var res='result4';
				else
					var res='result3';
	var field_val = trim(document.getElementById(field).value);
	var std='';
	if(field_val=='')	
	{
		document.getElementById(res).innerHTML='';
		return true;
	}
	if(field=='phone_home')
	{
		std=trim(document.getElementById('std_c').value);
		if(!isNumeric(field_val) || (std && !isNumeric(std)))
		{
	
			alert("Std/landline can only be numeric!");
			return true;
		}
	}
	if(field=='enquirer_landline_c')
        {
		std=trim(document.getElementById('std_enquirer_c').value);
                if(!isNumeric(field_val) || (std && !isNumeric(std)))
                {
                        alert("Std/landline can only be numeric!");
                        return true;
                }
        }
	if(field=='phone_mobile')
	{
		if(!isNumeric(field_val))
		{
			alert("Mobile number should be numeric!");
			return true;
		}
	}
	if(field=='enquirer_mobile_no_c')
	{
		if(!isNumeric(field_val))
		{
			alert("Enquirer mobile number should be numeric!");
			return true;
		}
	}

        var queryString=getquerystring(field,field_val,std);

        if(queryString)
        {
		var xmlHttp = createXMLHttpRequestObject();
	    	if(xmlHttp) 
	    	{
			document.getElementById(res).innerHTML = 'Checking...'
			xmlHttp.open('POST', 'index.php?entryPoint=LA', true);
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xmlHttp.onreadystatechange = function() 
			{
                        	if (xmlHttp.readyState == 4) 
				{
    	                            updatepage(xmlHttp.responseText,res);
        		        }
                        }
	                xmlHttp.send(getquerystring(field,field_val,std));
                 }
	}
	else
		return true;
}
function getquerystring(field,field_val,std) {
	if(document.EditView)
		var is_edit=true;
	if(field && field_val)
	{
		qstr = field+'=' + escape(field_val) + "&lead_type=SR"; // NOTE: no '?'before querystring
		var lead=document.EditView.record.value;
		if(lead)
			qstr = qstr+"&lead="+lead;
		if(std)
			qstr = qstr+"&std="+std;
		if(is_edit)
			qstr+="&edit=1";
    		return qstr;
	}
	else
		return false;
}
function updatepage(msg,res){
	if(msg=='')
	{
		document.getElementById(res).innerHTML = '';
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
		arrTempArr = arr[k].split("_");
         if(arr[k].substr(0,1) == code_p || arr[k] == '' || arrTempArr[0] == code_p) {
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

function checkDeliveryMode()
{
	var options=document.getElementById('response_delivery_mode_c').options;
	var index=document.getElementById('response_delivery_mode_c').selectedIndex;
	var value=options[index].text;
	if(value=="Email")
	{
		if(document.getElementById('Leads0emailAddress0').value=='' && document.getElementById('enquirer_email_id_c').value=='')
		{
			alert("Lead or enquirer email address required to proceed");
			document.getElementById('response_delivery_mode_c').selectedIndex=0;
		}
 	}
	if(value=="Courier")
	{
		if((document.getElementById('primary_address_street').value=='' || document.getElementById('primary_address_postalcode').value=='') && document.getElementById('p_o_box_no_c').value=='')
                {
                        alert("Address and pin code or P.O.Box No. needed to proceed");
                        document.getElementById('response_delivery_mode_c').selectedIndex=0;
                }
	}
	if(value=="Both")
	{
		if((document.getElementById('Leads0emailAddress0').value=='' && document.getElementById('enquirer_email_id_c').value=='') || ((document.getElementById('primary_address_street').value=='' || document.getElementById('primary_address_postalcode').value=='') && document.getElementById('p_o_box_no_c').value==''))
		{
			alert("Email address and P.O Box No. or Address with pin code needed to proceed");
			document.getElementById('response_delivery_mode_c').selectedIndex=0;
		}
	}
	
}
function aboutProfileLimitCheck()
{
	var value=document.getElementById('about_the_profile_c').value;
	value=trim(value);
	if(value!='')
	{
		var len=value.length;
		if(len<100)
			alert("Minimum 100 characters to be entered in About Profile Section");
	}
}
function checkEmail(field,element,deDupe)
{
	var field_val=trim(document.getElementById(field).value);
	if(field == 'enquirer_email_id_c')
		var res='result4';
	if(field_val)
	{
		var valid=isValidEmail(field_val);
		if(valid)
		{
			if(deDupe)
			{
				return xmlhttpPost('enquirer_email_id_c',element);
			}
			else
				return true;
		}
		else
		{
			document.getElementById(res).innerHTML="<font color='red'>Invalid Email!!</font>";
			return true;
		}	
	}
	else
	{
		document.getElementById(res).innerHTML='';
		return true;
	}
}
function checkLength(element,label,len)
{
	if(len>1)
	{
		var val=trim(document.getElementById(element).value);
		if(val.length && val.length<len)
		{
			if(label)
				alert(label+" cannnot be lesser than "+len+" characters");
			return false;
		}
		else
			return true;
	}
}
function changeDisposition()
{
	if(statusDispositionArr.length>0)
	{
		var disValArr=new Array;
		var disLabArr=new Array;
		var statusValue=document.EditView.status.value;
		var index=0;
		var i=0;
		if(statusDispositionArr[statusValue])
		{
			for(i=0;i<statusDispositionArr[statusValue].length;i++)
			{
				statusDispositionArr[statusValue][i].forEach(function(v,k) {disValArr[index]=k;disLabArr[index++]=v;});
			}
		}
		else
		{
			disValArr[0]=0;
			disLabArr[0]='';
		}
		var dispositionOptions=document.EditView.disposition_c.options;
		dispositionOptions.length=0;
		var selected='';
		for(i=0;i<index;i++)
		{
			dispositionOptions.length++;
			dispositionOptions[dispositionOptions.length-1].value=disValArr[i];
			dispositionOptions[dispositionOptions.length-1].text=disLabArr[i];
		}
		document.EditView.disposition_c.selectedIndex=0;
	}
	else
		return true;
}
function checkStatusComments()
{
	var stat=document.getElementById('status').value;
        var disp=document.getElementById('disposition_c').value;
        var comments=trim(document.getElementById('status_comments_c').value);
	if(stat==46 && (disp==21 || disp==20 || disp==25))
        {
                if(comments.length==0)
                {
                        alert("Please enter valid profile ID in Status/Disposition comments field");
                        return true;
                }
                var xmlHttp = createXMLHttpRequestObject();
                var responseStatus='';
                if(xmlHttp)
                {
                        xmlHttp.open('POST', 'index.php?entryPoint=PC', true);
                        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xmlHttp.onreadystatechange = function()
                        {
                                if (xmlHttp.readyState == 4)
                                {
                                        responseStatus=xmlHttp.responseText;
					//alert(responseStatus);
                                        switch(responseStatus)
                                        {
                                                case '1' : alert("Please enter valid profile ID in Status/Disposition comments field");
                                                           break;

                                                case '2' : alert("Profile ID entered in Status/Disposition comments field does not exist");
							   document.getElementById('status_comments_c').focus();
                                                           break;

                                                case '3' : document.getElementById('status_comments_c').value=comments;
                                                           break;
					
						case '4' : alert("Profile ID entered in Status/Disposition comments field is not deleted");
                                                           document.getElementById('status_comments_c').focus();
                                                           break;
			
						case '5' : alert("Profile ID entered in Status/Disposition comments field is not active on the site");
                                                           document.getElementById('status_comments_c').focus();
                                                           break;

						case '6' : alert("Profile ID entered in Status/Disposition comments field is not incomplete or is deleted");
                                                           document.getElementById('status_comments_c').focus();
                                                           break;
                                        }
                                }
                        }
                        xmlHttp.send('id='+comments+'&disposition='+disp);
                 }
        }
}
