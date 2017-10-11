var called=0;
var callnowHit ="";
var prev="";
//Result variable is filled by ajax function
var result="";
show_tab_id=dID("show_tab");
var error_message="";
$("#con_tab").show();
function close_tab()
{
	if(prev)
	{
		prev.className="";
		prev="";
		show_tab_id.innerHTML="";
	}	
}
var focus_on_tab=1;
function change_tab(dom_ele,sele,force_send,from_primary)
{
	var extra_url="";
	var url=SITE_URL+"/profile/open_tab.php";
	if(from_primary)
		focus_on_tab=0
	else
		focus_on_tab=1

	if(sele=="Horoscope")
	{
		//Removing horoscope check because of ticket 139
	//	if('~$RELIGION_SELF`'!='Hindu' && '~$RELIGION_SELF`'!='Jain')
	//		return false;
	}

	if(!prev)
	{
		dom_ele.className="current";
		prev=dom_ele;
	}
	else
	{
		if(dom_ele==prev && force_send!=1)
			return false;
		prev.className="";
		dom_ele.className="current";
		prev=dom_ele;
	}
	if(sele=='Contact_History')
	{
		if(onlyContactTab)
		{
			showTabOnly();
			return;
		}
		extra_url="&contact_status="+dp_contactStatus+"&other_profile="+dp_otherProfile+"&";
	}
	if(sele=="Horoscope")
	{
		url="horoscope_astro.php";
		extra_url="&checksum="+dp_checksum+"&profilechecksum="+dp_profileChecksum+"&HOROSCOPE="+dp_horoScope;
	}
	if(sele=="Similar_Profile")
	{
		extra_url="&other_profile="+dp_profileChecksumNew;
	}
	if(sele=="Compatibility")
	{
		 extra_url="&checksum="+dp_checksum+"&profilechecksum="+dp_profileChecksum;
	}
	if(dp_errorMessage)
	{
		error_message=escape(dp_errorMessage);
	}
	url=url+"?SAMEGENDER="+dp_sameGender+"&FILTER="+dp_filter+"&ERROR_MES="+error_message+"&view_username="+dp_profileName+"&SIM_USERNAME="+dp_profileName+"&type="+sele+"&ajax_error=2"+extra_url+"&"+dp_navig+"&randValue="+Math.round(Math.random()*1000);
	send_ajax_request(url,"show_loader","show_data");
	return false;

}
function show_loader()
{
	        if(focus_on_tab)
		{
			set_focus_on_anchor("#tabs_comb");
		}

	var loader='<div class="t12" style="border:1px #cad890; padding:10px 0px 0px 20px;width:auto;border-bottom-style:solid;border-left-style:solid;border-right-style:solid; background-color:#f3fbd1;"><div class="t12" style="text-align:center"> <img src="IMG_URL/img_revamp/loader_small.gif"></div><div class="sp3"></div></div></div>';
	show_tab_id.innerHTML=loader;
	
}
function showTabOnly()
{
	$("#show_tab").html($("#contactTab").html());
}
function show_data()
{
	if(result=="A_E")
                show_tab_id.innerHTML='<div class="t12" style="border:1px #cad890; padding:10px 0px 0px 20px;width:auto;border-bottom-style:solid;border-left-style:solid;border-right-style:solid; background-color:#f3fbd1;"><div class="t12" style="text-align:center">'+common_error+'</div><div class="sp3"></div></div></div>';
        else
                show_tab_id.innerHTML=result;
        var ele=dID("set_scroll");
        if(ele.offsetHeight>290)
                ele.style.height="290px";
	
}

if(typeof(dp_type)=='undefined')

var type_of_contact_cur=dp_type;

function show_real_exp_con()
{
	var exp_above = dID('express_layer_above');
	var exp_below = dID('express_layer_below');	
	exp_below.innerHTML="";
	exp_above.removeChild(exp_below);
	var newdiv = document.createElement('div');
	newdiv.setAttribute('id',"express_layer_below");
	newdiv.setAttribute('style',"display:inline");
	if(result=='A_E')
		result=common_error;

		//$(newdiv).html(result);
	newdiv.innerHTML=result;
	result="";
	//exp_above.appendChild(newdiv);
	$("#express_layer_above").append(newdiv);
	//Eval of script tag is necessary , IE and chrome doesn't support automatic execution of script tag while adding content to innerHTML
	try
	{
	//	eval(dID("script_of_dp").innerHTML);
	}
	catch(e)
	{
	}
	if(type_of_contact_cur!=type_of_contact_now)
		show_layer('con',1);

	type_of_contact_cur=type_of_contact_now;
}
if(dp_login)
	show_astro_icons();
function show_astro_icons()
{
	if(dp_otherProfile)
	{
		send_ajax_request(SITE_URL+"/profile/issue_2472.php?other_profileid="+dp_otherProfile,"","after_astro_call");
	}
}
function showicons()
{
	if(result)
	{
			
	}
}
function annulled_show()
{
	$('#annulled_reason').show();
	$('#show_express').css("z-index",0);
}
function annulled_hide()
{
	$("#annulled_reason").hide();
	$('#show_express').css("z-index",10);
}
//added by manoranjan

var span_layer_id="";
	
function openChatWindow(aJid,param,profileID,userName,have_photo,checksum){
	//alert("login or not>>>>~$LOGIN`");
	if(user_login=="")
	{
		if(span_layer_id)
               span_layer_id.style.zIndex=0;
		var after_login_call="openChatWindow('"+aJid+"','"+param+"','"+profileID+"','"+userName+"','"+have_photo+"','"+checksum+"')";
		$.colorbox({href:'/profile/login.php?SHOW_LOGIN_WINDOW=1'});
		return true;
	}
	//alert("top.ajaxChatRequest is >>>>>"+top.ajaxChatRequest);
	if(top.ajaxChatRequest){
		top.ajaxChatRequest(aJid,param,profileID,userName,have_photo,checksum);
	}else{
		$.colorbox({href:'/profile/jsChatBarNotFound.php'});
	}
	
}

//added by manoranjan

function uploadOnlineMem(){
	//alert("old online member is >>>>"+top.old_onlineusers);
	top.document.getElementById("onlineUser").innerHTML=top.old_onlineusers;
}
if(typeof(top.change_online_update_orNot)!='undefined')
{
	top.change_online_update_orNot();
//	onunload = top.change_online_update_orNot;
}

function submit_lead(){
var lead_email_val=dID("lead_email").value;
if(!check_email(lead_email_val)){
  dID("lead_email").focus();
  dID('email_err').innerHTML="Please enter valid email address";
}
else{
dID('email_err').innerHTML="&nbsp;";
dID('form_d').style.display="none";
dID('thanks').style.display="block";
data1={"viewed_profileid": dp_otherProfile, "email": lead_email_val,"source_c": 17, "gender": gender_val};
if(show_lead_age!="0")
data1['age']=dID("lead_age").value;
if(show_lead_mobile!="0")
data1['mobile']=dID("lead_mobile").value;
if(show_lead_mtongue!="0")
data1['mtongue']=dID("lead_mtongue").value;
$.ajax({
type: 'POST',
url: '/profile/sugarcrm_registration/create_lead.php',
data: data1,
success:function(data){
}
});
}
return false;
}
function form_submit()
{ 
		val_ret =lead_valid();
		if(val_ret)
				document.mini_reg_lead.submit(); 
}
var horos_call=0;
function horos_ajax_request(profilechecksum,fromAstro)
{       
    
   var response = '';
   var mes='';
   var correctHoros=0;
   //show_tab_id=dID("horoscope");
   if(user_login)
   {
     	data1={"profilechecksum":profilechecksum,"ajax_error":2,"Submit":1};
     	if(fromAstro)
     		document.location.href="#horoscope";
        if(horos_call!=0)
            return;
        horos_call=1;		
		dID("horoscope").innerHTML='<span ><img src="IMG_URL/P/images/loader_extra_small.gif" style="float:left"></img><strong>&nbsp; Sending Horoscope</strong></span>';

		url=SITE_URL+"/profile/horos_req_layer.php/profilechecksum="+profilechecksum+"&ajax_error=2&Submit=1";
		$.ajax({
		type: 'POST',
		url: url,
		data: data1,
		success:function(data){
		response = data;
		
		if(ltrim(response)=="A_E")
            mes=common_error;
        else if(ltrim(response)=="F")
        {
        	dp_filter=1;
        	mes="Your profile has been filtered out";
        }	
        else if(ltrim(response)=="G")
           	mes="Horoscope request for same gender is not allowed";
        else if(ltrim(response)=="E")
           	mes="You have already requested this user for horoscope.";
        else if(ltrim(response)=="U")
          	mes="Please wait till your profile is screened.";
        else
        { 
        	correctHoros=1;
        	dID("horoscope").innerHTML='<span><div class="spritem lf hrscpe_icon"></div>&nbsp;<strong>Horoscope Requested</strong></span>';
        	if(fromAstro)
     	     		dID("horos").value="Horoscope Requested";
     	    change_tab(dID('contact_history'),'Contact_History'); 		    
        }
		
		if(correctHoros==0)
		{
				dID("horoscope").innerHTML='<span><div class="spritem lf hrscpe_icon"></div>&nbsp;<strong>Request Horoscope</strong></span>';
				dID("horos").value="Request Horoscope";
				change_tab(dID("horoscope"),'Horoscope',0,1);
		}
        
        }
	    }
	    );
     }    
     else
     {
        var fnc_to_call="horos_ajax_request(\""+profilechecksum+"\",1)";
       	$.colorbox({href:"/profile/login.php?SHOW_LOGIN_WINDOW=1&after_login_call="+escape(fnc_to_call)});
     }
     if(fromAstro)
    	 document.location.href="#horoscope"
     //return true;
}
//functions to trim whitespaces
function ltrim(str) { 
	for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
	return str.substring(k, str.length);
}
function rtrim(str) {
	for(var j=str.length-1; j>=0 && isWhitespace(str.charAt(j)) ; j--) ;
	return str.substring(0,j+1);
}
function trim(str) {
	return ltrim(rtrim(str));
}
function isWhitespace(charToCheck) {
	var whitespaceChars = " \t\n\r\f";
	return (whitespaceChars.indexOf(charToCheck) != -1);
}


$('#exp_layer').bind("click",function(){
        show_layer('exp');
});
$('#con_layer').bind("click",function(){
        show_layer('con');
});
//tabbing
var nottoupdate=1;
function show_layer(tabName,autocall)
{
	if(typeof(autocall)=='undefined')
		autocall=0;
	var exist="";
	exist=con_data;
	if(tabName=="exp")
		exist=exp_data;
	if(tabName=='con')
	{
		call_ajax_contact_display();
		if(updateEvalueTracking)
		{
			send_ajax_request(SITE_URL+"/common/updateEvalueTracking?id="+updateEvalueTracking)
			updateEvalueTracking = 0;
		}
		//Showing verify layer when phone is not verified.
		if(typeof(PH_LAYER_STATUS_DP)!='undefined' && autocall==0)
		{
			if(PH_LAYER_STATUS_DP)
			{
				setTimeout("verify_layer_dp()",1000);
			}
		}
	}
	
	$("#exp_layer").removeClass("active");
		$("#exp_layer").addClass("notactive");
		$("#con_layer").removeClass("active");
		$("#con_layer").addClass("notactive");
		$("#"+tabName+"_layer").removeClass("notactive");
		$("#"+tabName+"_layer").addClass("active");

	
	if(exist)
	{
		//$("#exp_tab").css("display","none");
		//$("#con_tab").css("display","none");
		//$("#"+tabName+"_tab").css("display","inline");
		$("#exp_tab").html("");
		$("#con_tab").html("");
		
		data=exp_data;
		if(tabName=="con")
		data=con_data;
		
		$("#"+tabName+"_tab").html(data);
		
		bindThickbox();
	}
		if(reloadOtherTab)
	{
			unknownTab("div",reloadOtherTab,tabName);
			nottoupdate=1;
	}
	reloadOtherTab="";
	
	
	
}
function tab_express_interest()
{
	//dp_profilechecksum="";
	document.location=document.location+"#det_prof";
	$("#draft_name")
	$("#draft_name").val($("#draft_nametab").val());
	$("#draft").val($("#tab_textarea").val());
	show_layer("exp");
	onExpressInterest(dp_profilechecksum);
	close_tab();
}
var exp_data="";
var con_data="";
updateTabData('con');
if(defaultContactTab)
show_layer('con',1);
function updateTabData(setblank)
{
	if($("#exp_tab").html())
		exp_data=$("#exp_tab").html();
	if($("#con_tab").html())	
		con_data=$("#con_tab").html();
	
	if(setblank)
		$("#"+setblank+"_tab").html("");
	
}
function onSelectiontab(value)
{
	$("#tab_textarea").val(MES[value]);
}
if(kundli_type==1)
	show_layer('con',1);
//Added by Anand
if(kundli_type==2)
{
	change_tab(dID('horoscope'),'Horoscope',0,1);
	window.scroll(0,350);
}
else if(dp_contactHistoryTab)	
	change_tab(dID('contact_history'),'Contact_History',0,1);
//Added by Anand ends
/*if(kundli_type==3)//Added by Anand
	{//show_layer('show_contact','show_express','con_layer','expr_layer',1,'show_callnow','callnow_layer');
	}	//Added by Anand ends
else if(dp_showContactTabEv)
	show_layer('con',1);*/


function call_ajax_contact_display()
{
	if(!called)
	{
		$.ajax({
          url: "/api/v2/contacts/contactDetails?r=1&actionName=ContactDetails&fromDetailedProfileAjaxCall=1&profilechecksum="+dp_profileChecksum,
          type: 'POST',
          datatype: 'json',
          cache: true,
          async: true,
          success: function(result) {               
				return true;
			},
		  error: function() {
			
			return true;
		
			}
		});
	}
called=1;
}
 /*
 *  Functin to Show and Hide Block and Unblock Button
 */
function blockUnblockToggle(idToShow,idToHide){
  idToShow = '#'+idToShow;
  idToHide = '#'+idToHide;
  if($(idToShow).length === 0 || $(idToHide).length === 0)
    return ;
  
  $(idToHide).addClass('dspN');
  $(idToHide).removeClass('block');
  
  $(idToShow).removeClass('dspN');
  $(idToShow).addClass('block');
}