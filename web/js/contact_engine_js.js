var show_anchor=1;
show_loader_in_express='<div style="text-align:center;" id="loader_in_layer"><img src="'+dp_imgurl+'/img_revamp/loader_big.gif"></div>  <div class="sp16"></div> <div class="sp8"></div>  <div style="text-align:center" class="t14 b">';
var show_err_layer='<div id="error_layer" style="display: block;" class="lyr"><div class="lyr_tp_cur"></div><div class="cnt"><a onclick="return hide_err_layer()" href="#" class="fr crs b">[x]</a><div class="clr"></div><div class="fl f_11 mt_15" id="err_message"></div><div class="sp5"></div><div class="sp5"></div><div class="sp5"></div><div class="sp5"></div><div class="sp5"></div></div><div class="clr"></div><div class="lyr_btm_cur"></div></div>';
var call_now_rec=0;
function verify_layer_dp()
{
	$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=CONTACT&flag=1&width=700'});
}

if(dpContactEngineError==0)
{
	//Contact engine starts here------------------------------
	// ivr show hide notes
	var height=40;
	function reset_textarea(inc_or_dec)
	{
		
	}
	var selid=dID("message_id");
	var textid=dID("text_id");
	var buttonid=dID("multi_button");


	var stype="";
	if(dID("STYPE"))
		stype=dID("STYPE").value;

	var temp="";
	var result;
	var pattern1 = /\#n\#/g;
	

	function change_message(values)
	{
		if(!values)	
		{
			values=dID("message_id").value;
		}
		sel_id=values;
		if(sel_id!="" && MES[sel_id])
			textid.value=check_special_chars(MES[sel_id]);
		else
			textid.value="";
		
	}
	function set_dropdown(select_id,val_mes)
	{
		removeAllOptions(select_id);
		var first_value_drop="";
		$("#message_id").html(val_mes);
		/*
		for(key in val_mes)
		{
			if(!first_value_drop)
				first_value_drop=key
			 var optn = document.createElement("OPTION");
			optn.text= check_special_chars(val_mes[key]);
			optn.value=key;
			select_id.options.add(optn)

		}*/
		change_message(first_value_drop);
	}
	function removeAllOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=0;i--)
		{
			selectbox.remove(i);
		}
	}
	function check_dropdown(type)
	{
		
		if(selid==null || typeof selid=="undefined")
			return;
			selid.innerHTML="";
		
		if(type=='accept')
		{
			set_dropdown(selid,acceptSel);
	//		selid.innerHTML=accept;
			if(dp_accept==1)
				dID("multi_button").value="Accept Interest";
			
			if(dp_selid==1)
				selid.style.display='none';
			
		}
		else
		{
			//selid.innerHTML=decline;
			set_dropdown(selid,declineSel);
			if(dp_accept==1)
				dID("multi_button").value="Not Interested";
			
			if(dp_selid==1)
			{
				textid.style.display='inline';
				selid.style.display='inline';
				//change_message();
			}
			
		}
		selid=dID("message_id");
		
		//This is required since we don't have to show the select options if options has no saved message
		if(selid.options.length<=1)
			selid.style.display='none';
		else if(selid.options.length<=1 && selid.options[0].value!='PRE_1' && selid.options[0].value!='D1')
		{
			selid.style.display='none'; 
		}
		else
		{
			if((selid.options[0].value=='PRE_1' || selid.options[0].value=='D1') && selid.options.length<=1)
			{
				textid.value=MES[selid.options[0].value];
			}	
			selid.style.display='inline';
		}

		var type=dp_status;
		
		//Remove message dropdown if status is accepted or declined
		if(type=='A' || type=='D')
			selid.style.display='none';
		//Removes text id 
		if(dp_removeText)
			textid.style.display='none';
		if(dp_disableAll)
		{
			selid.style.display='none';
			textid.style.display='none';
			buttonid.style.display='none';
		}
		if(selid.style.display=='none')
			reset_textarea('');
		else
			reset_textarea('dec');
	}
	var button_value="";

	//The transitionary message , while sending ajax request from contact engine
	var SHOW_MESSAGE=new Array;
	var which_to_show=0;
	var fin_contact="";
	SHOW_MESSAGE[0]="Expressing interest";
	SHOW_MESSAGE[1]="Sending Reminder";
	SHOW_MESSAGE[2]="Responding to Expression of Interest";
	SHOW_MESSAGE[3]="Sending Message";
	SHOW_MESSAGE[4]="Sending Acceptance";
	SHOW_MESSAGE[5]="Sending the response";
	SHOW_MESSAGE[6]="Responding";
	SHOW_MESSAGE[7]="Cancelling Expression of Interest";

	var text_message="";
	var draft_id="";
	if(typeof(albumPage)!='undefined')
		var from_albumPage=1;
	else
		var from_albumPage=0;
		
	function submit_form(button)
	{
		if(dp_type=='')
		{
			if(typeof(noExpressInterest)!='undefined')
			{
				if(typeof(albumPage)!='undefined')
					hide_exp_layer();
			
				$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=EOI&flag=1&width=700'});
			
        	    return;
			}
		}
		//If contact is in accepted state, and user trying to submit form without wriiting anything.
		if(dp_type=="A")
		{
			if(trim(dID("text_id").value)=="" && button=="Submit")
			{	
				dID('errorSpan').innerHTML='Please write a message to be sent';
				return false;
			}
			else
				dID('errorSpan').innerHTML='';
		}		
		
		var temp="";
		var extra_url="Submit=1&from_viewprofile="+dp_viewprofile;
		button_value=button;
		//Whether to show thickbox layer or not, by default its enabled.
		var show_layer=1;
		selid=dID("message_id");
		textid=dID("text_id");
		
		buttonid=dID("multi_button");
		text_message=escape(textid.value);
		
		if(dID("test_id") && text_message)
		{
			dID("test_id").value=textid.value;
		}	
			
		draft_id=selid.value;
		
		if(dp_allowAcceptDecline!="" && dp_allowAcceptDecline!=0)
			which_to_show=2;
		if(buttonid)
			but_value=buttonid.value;
		if(button=='A_NUDGE')
		{
			extra_url=extra_url+"&ACC_NUDGE=ACCEPTED_NUDGE";
		}
		else if(button=='D_NUDGE')
		{
			which_to_show=6;
			extra_url=extra_url+"&DEC_NUDGE=DECLINE_NUDGE";
		}
		else if(button=='DECLINE')
		{
			if(dp_who == 'SENDER')
			{
				which_to_show=7;
				extra_url=extra_url+"&status=C";
			}
			else
			{
				which_to_show=5;
				extra_url=extra_url+"&status=D";
			}
		}
		else if(but_value.search("Send Message")!=-1)
		{
			extra_url=extra_url+"&status=M";
			
			which_to_show=3;
			if(from_albumPage==0)
				show_layer=0;
			else
				extra_url=extra_url+"&CONTACT_FROM_ALBUM=1";
		}
		else if(but_value.search("Accept")!=-1)
		{
			which_to_show=2;
			fin_contact="Y";
		}
		else if(but_value.search("Not Interested")!=-1)
		{
			which_to_show=2;
			
		}
		else if(but_value.search("Reminder")!=-1)
		{
			which_to_show=1;
		}
		
		//If send message is clicked,then no thickbox layer to show.
		if(dID("contact_engine_0"))
			var input_id=dID("contact_engine_0").elements;
		else	
			var input_id=dID("contact_engine").elements;
			
		for(var i=0;i<input_id.length;i++)
		{
			
			if(input_id[i].type=='radio' && input_id[i].name=='status')
			{
				if(input_id[i].checked==true)
					extra_url=extra_url+"&"+input_id[i].name+"="+input_id[i].value;
			}
			else
				extra_url=extra_url+"&"+input_id[i].name+"="+escape(input_id[i].value);

		}
		
		extra_url=extra_url+"&ajax_error=2&NAVIGATOR="+escape(dp_navigator);

		if(show_layer)
		{
			if(from_albumPage)
				before_exp();
			else
				show_loader_in_exp();
			var url_data=SITE_URL+"/profile/single_contact_aj.php?width=726&TYPE_OF=CI&"+extra_url;
			//show_loader();
			$.colorbox({href:url_data});
		}
		else
		{
			
			
			send_ajax_request(SITE_URL+"/profile/single_contact_aj.php?"+extra_url,"show_loader_in_exp","hide_loader_in_exp");
		}	

	}
	
	
	var in_profile_album=0;
	function before_exp()
	{
		in_profile_album=1;
		if(which_to_show!=-1)
			$("#main_layer_dp").html(show_loader_in_express+SHOW_MESSAGE[which_to_show]+"...</div>");	
		else
			$("#main_layer_dp").html(show_loader_in_express+"</div>");
					
		$("#exp_layer").hide();
	}
	//Function to be called only after contact engine layer sends message.
	function after_exp(data,err_msg)
	{
		if(err_msg!=0)
		{
			$("#exp_layer").html(show_err_layer);
			$("#exp_layer").show();
			$("#err_message").html(data);
			$("#main_layer_dp").html(err_msg);
		}	
		else
		{
			
			//Show contact details of other user is contact comes to accepted state.
			if(data=="reload" && (type_of_contact=="I" ||  type_of_contact=="D" || type_of_contact=="RC" ))
			{
				ce_url=SITE_URL+"/profile/invoke_contact_engine.php?checksum=&STYPE=&profilechecksum="+dp_profilechecksum+"&index=0&to_do=view_contact&rand=103&redirect_to_contact=1";
				
				$.ajax({
				url: ce_url,
				success: function(data){
				$("#exp_layer").html(data);
				$("#exp_layer").show();
				$("#inv_con_lay").css("z-index",101);
				$("#inv_con_lay").css("margin-left","0");
				////dID("IndividualProfile").style.zIndex="10";
				  }
				});
			}
			//Update the contact engine of album page.
			upd_cnt_engine(data);
			
		}	
	}
	function hide_err_layer()
	{
		$("#exp_layer").html("");
		$("#exp_layer").hide();
	}
	function upd_cnt_engine(data)
	{
		var curT=type_of_contact;
		var nextT="";
		if(curT=="I")
			if(data=="reload")
				nextT="A";
			else
				nextT="D";
		if(curT=="A")
			if(which_to_show!=3)
				nextT="D";
		if(curT=="D")
			nextT="A";
		if(curT=="RA")
			if(which_to_show!=3)
				nextT="RC";
				
		if(curT=="RC")
			nextT="RA";
		if(nextT!="")	
		{
			dID("PROFILE_ALBUM_"+curT+"").style.display="none";
			dID("PROFILE_ALBUM_"+nextT+"").style.display="block";
			type_of_contact=nextT;
		}
	}
	function show_loader_in_exp()
	{
		if(which_to_show!=-1)
			dID("main_layer_dp").innerHTML=show_loader_in_express+SHOW_MESSAGE[which_to_show]+"...</div>";	
		else
			dID("main_layer_dp").innerHTML=show_loader_in_express+"</div>";
	}
	var type_of_contact_now=dp_type;
	function hide_loader_in_exp(data,err_msg)
	{
		if(typeof(err_msg)=='undefined')
			var err_msg=0;
		if(result=='A_E')
			result=common_error;
		if(data)
			result=data;
			
		//If expression of interest is made in profile album page.
		if(in_profile_album)
		{
			after_exp(result,err_msg);
			return;
		}
		
		dID("main_layer_dp").innerHTML=result;
		result="";
		var ele="";
		var send_url="";
		var show_acc_dec=0;
		
		if(buttonid)
			if(buttonid.value=='Accept Interest' || buttonid.value=='Not Interested')
				show_acc_dec=1;
		
				
		if((dp_who=='' && dp_type=='I') || button_value=='DECLINE' || data=='reload' || show_acc_dec==1)
		{
			send_url="?ajax_error=2&ONLY_LAYER=1";
			ele=dID("contact_engine").elements;
			
			for(var i=0;i<ele.length;i++)
			{
				if(ele[i].type=='hidden')
					send_url=send_url+"&"+ele[i].name+"="+escape(ele[i].value);
			}
			if(data=='reload')
				which_to_show=-1;
			if(show_acc_dec==1)
				send_url=send_url+"&type_of_action="+buttonid.value;

			send_url=send_url+"&rand="+Math.round(Math.random()*1000);
			var end_func_call="show_real_exp_con";

			send_ajax_request(SITE_URL+'/profile/get_details.php'+send_url,"show_loader_in_exp",end_func_call);
		}
		//else if(type_of_contact_now==type_of_contact_cur && type_of_contact_now=='A')
		//{
			if(data!='reload' && err_msg==0)
				change_tab(dID('contact_history'),'Contact_History',1);
		//}

	}
	//if(from_albumPage!=1)
	{
		if(dp_tempContact=="")
		{
			if(dp_type=="C")
				check_dropdown('decline');
			else
				check_dropdown('accept');
		}
		if(dp_searchDecline)
			check_dropdown("reject");
	}
	
	
}
function show_callnow_layer()
{
	call_now_layer();
	
}
function call_now_layer()
{
	//If request coming from album page 
	if(typeof(show_contact_info)!="undefined")
		show_contact_info("callnow");
	else
	{
		show_layer('show_callnow','show_express','callnow_layer','expr_layer','','show_contact','con_layer');
		if(call_now_rec==0)
		{
			var ce_url=SITE_URL+"/profile/invoke_contact_engine.php?to_do=callnow_layer&index=0&FROM_VIEW=1&ajax_error=2&profilechecksum="+dp_profileChecksum+"&close_func=hide_cont_layer()&checksum=&profile_url=1&rand="+Math.round(Math.random()*1000);
			
			$.ajax({
						url: ce_url,
						success: function(data){
						$("#main_layer_callnow").html(data);
						call_now_rec=1;
						//dID("IndividualProfile").style.zIndex="10";
						}
				});
				
		}		
	}	
}
if(from_albumPage)
{
$("#multi_button").bind("click",function(){
	submit_form('Submit');
	});
}
function show_login_layer(url)
{
        if(url=="call")
        {
                var url=SITE_URL+"/profile/login.php?SHOW_LOGIN_WINDOW=1&after_login_call="+escape("call_now_layer()");
                $.colorbox({href:url});
        }
        else
        {
                check_window("from_link");
       		$.colorbox({href:url});
     }
}	
function redirectPage(toPage)
{
	if(toPage == "awaiting")
        	document.location.href=SITE_URL+"/P/contacts_made_received.php?page=eoi&filter=R";
	else if(toPage == "alerts")
		document.location.href=SITE_URL+"/profile/contacts_made_received.php?page=matches&filter=R";
	else if(toPage == "matches")
		document.location.href=SITE_URL+"/search/partnermatches";
}
//-------------------------------- contact engine js ends here ------------------------------//	
