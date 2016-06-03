/* variable defined to generate dialCode to access Calln ow feature */
var typeVal=0;
var elementID=0;
/* ends  */

function getDialcode(receiverid,typeValSel)
{
	elementID=receiverid;
	if(typeValSel)
		typeVal=typeValSel;
	url 			= "dialcode_generate.php";
	parameters	 	="ajax_error=2&RECEIVER="+elementID;
	url			=url+"?"+parameters;
	call_after_function 	="show_callnow";
	send_ajax_request(url,"",call_after_function);
}
function show_callnow()
{
	var response=result;
	if(response =='ERROR'){
		msg ="Your request could not be processed due to technical issue, try later";
		return;
	}else{
		if(typeVal)
			dID(elementID).innerHTML=response;
		else
			dID('dialcode').innerHTML=response;
		return;
	}
	typeVal=0;
	elementID=0;
}

// function to record the callnow link hits in loggedin case
function callnow_hits()
{
	if(callnowHit ==''){
        	url                     ="callnow_hits.php";
        	parameters              ="ajax_error=2";
        	url                     =url+"?"+parameters;
        	send_ajax_request(url,"","");
	}
	callnowHit ='1';
} 

// function to hide and show the Callnow-notes content
function CallnotesLayer(val)
{
       	var sign;
	sign = Get_Cookie('hide_note');
       	if(sign=='-')
       	{
		if(val){
			dID('callHint').style.display="block";
			dID('sign').innerHTML='-';
			dID('note').innerHTML='Hide notes';
		}else{
       	        	dID('callHint').style.display="none";
       	        	dID('sign').innerHTML='+';
			dID('note').innerHTML='Show notes';
			Set_Cookie('hide_note','+','','/','','')
		}
       	}
       	else
       	{
		if(val && sign){
			dID('callHint').style.display="none";
			dID('sign').innerHTML='+';
			dID('note').innerHTML='Show notes';
		}else{
       	        	dID('callHint').style.display="block";
       	        	dID('sign').innerHTML='-';
			dID('note').innerHTML='Hide notes';
			Set_Cookie('hide_note','-','','/','','')
		}
       	}
}

// function to display the Callnow link with display and hidden content of Callnow
function CallnowLayer(idVal)
{
	var val;
	val = dID(idVal).value;
	if(val =='show'){
		dID(idVal).innerHTML='Call now <img src="images/grey_dwn_arrow.gif" border="0">';
		dID(idVal).value='hide';	
		dID('exp_callnow_layer').style.display="block";
		dID(idVal).className='blink t16 blk';
	}
	else{
		dID(idVal).innerHTML='Call now <img src="images/next_arr.gif" border="0">';
		dID(idVal).value='show';
		dID('exp_callnow_layer').style.display="none";
		dID(idVal).className='blink t16';
	}
}

// function to show express interest layer
function show_exp_rem(){
	dID('disp_express_interest_feature').style.display="block";
	dID('disp_callnow_feature').style.display="none";	
}




