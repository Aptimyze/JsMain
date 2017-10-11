//var validate_fields = new Array("fname_user","lname_user","username","mstatus","has_children","country_residence","citizenship","city_residence","phone","phone_owner_name","showphone","mobile","mobile_owner_name","showmobile","time_to_call_start","start_am_pm","time_to_call_end","end_am_pm","degree","occupation","income","religion","caste","termsandconditions");
var validate_fields = new Array("fname_user","lname_user","username","mstatus","has_children","country_residence","citizenship","city_residence","phone","phone_owner_name","showphone","mobile","mobile_owner_name","showmobile","time_to_call_start","start_am_pm","time_to_call_end","end_am_pm","degree","occupation","income","termsandconditions","mtongue");

var incomplete={
        'input' : function(element){
                        element.onblur=function(){
                                validate(this);
                        }
			element.onfocus = function(){
                                box_action(this,'clear');
                        }

                },
	'select' : function(element){
                        element.onblur=function(){
                                validate(this);
                        }
                },
        '#fname_user' : function(element){
                        element.onkeyup = function(){
                                //toggle_contact_number_dropdown();
                        }
                },
        '#phone' : function(element){
                        element.onblur = function(){
                                //check_contact_number("PHONE",this.value);
                                box_action(this,'fill');
                                validate(this);
                                fill_contact_number_name('phone_number_owner','phone_owner_name');
                        }
                },
	'#mstatus' :function(element){
			element.onclick = function(){
				fill_mstatus_reason();
			}
		},
        '#mobile' : function(element){
                        element.onblur = function(){
                                //check_contact_number("PHONE",this.value);
                                box_action(this,'fill');
                                validate(this);
                                fill_contact_number_name('mobile_number_owner','mobile_owner_name');
                        }
		},
        '#relationship' : function(element){
                        element.onclick = function(){
                                new_contact_number_dropdown();
                                fill_contact_number_name('phone_number_owner','phone_owner_name');
                                fill_contact_number_name('mobile_number_owner','mobile_owner_name');
                        }
		},
	'#phone_number_owner' : function(element){
                        element.onchange = function(){
                                fill_contact_number_name('phone_number_owner','phone_owner_name');
                        }
                },
	'#mobile_number_owner' : function(element){
                        element.onchange = function(){
                                fill_contact_number_name('mobile_number_owner','mobile_owner_name');
                        }
                },
        '#mstatus_reason_married' : function(element){
                        element.onfocus = function(){
                                mstatus_details(this,"clear");
                        }
                        element.onblur = function(){
                                mstatus_details(this,"fill");
                        }
                },
        '#mstatus_reason_awaiting' : function(element){
                        element.onfocus = function(){
                                mstatus_details(this,"clear");
                        }
                        element.onblur = function(){
                                mstatus_details(this,"fill");
                        }
                },
        '#country_residence' : function(element){
                        element.onchange = function(){
                                show_hide_citizenship();
				change_city();
                                //fetch_code("COUNTRY",docF.country_residence.value);
                                //populate_city();
                        }
                },
	'#fsubmit' : function(element){
                        element.onclick = function(){
			var isError=validate();
				if(isError)
				{
					//alert(isError);
					if(isError=='mstatus')
						isError='mstatus_focus';
					eval('document.form1.' + isError + '.focus()');
					return false;
				}
				else
				{
					//alert("#");
				}

			}
		},
        '#mstatus_details_submit' : function(element){
                        element.onclick = function(){
                                mstatus_details(this);
                        }
		},
	'#username' : function(element){
                        element.onblur = function(){
				myjs_ajaxValidation(this.name,this.value);	
                                validate(this);
                        }
		}
        };
Behaviour.register(incomplete);
var box_action_names = new Array("state_code", "phone", "mobile");
var box_action_values = new Array("STD", "Phone No.", "Mobile No.");
var looking_for=0;
var mstatus_selected_value;

function mstatus_details(obj,clear_or_fill)
{
        if(obj)
                var id = obj.id;	
	if(id=='mstatus_details_submit')
	{
		var mstatus_selected_val=get_mstatus_value();
		if(mstatus_selected_val == "A")
		{
			if(trim(docF.court.value) == "" || docF.mstatus_day.value == "" || docF.mstatus_month.value == "" || docF.mstatus_year.value=="")
			{
                                document.getElementById("mstatus_details_error_img").style.display="inline";
                                document.getElementById("mstatus_details_box").className="redbox";
				docF.court.focus();
			}		
			else
			{
                                document.getElementById("mstatus_details_error_img").style.display="none";
				document.getElementById("married_reason_text").style.display="block";
				document.getElementById("married_reason_text").innerHTML='"'+"Marriage annulled by "+ docF.court.value +" on "+docF.mstatus_day.value +"/"+ docF.mstatus_month.value + "/"+ docF.mstatus_year.value +" <a href='#' id='mstatus_details_edit_link' onClick='javascript:mstatus_details();'>Edit</a>" + '"';
				document.getElementById("married_reason").style.display="block";
                                document.getElementById("mstatus_details_box").className="graybox";
				document.getElementById("mstatus_reason").style.display = 'none';
				docF.court.focus();
			}		
		}
		if(mstatus_selected_val == "I")
		{
			if(trim(docF.mstatus_reason_awaiting.value==''))
			{
                                document.getElementById("mstatus_details_box").className="redbox";
                                document.getElementById("mstatus_details_error_img").style.display="inline";
				docF.mstatus_reason_awaiting.focus();
			}	
			else
			{
                                document.getElementById("mstatus_details_error_img").style.display="none";
                                document.getElementById("mstatus_details_box").className="graybox";
				document.getElementById("mstatus_reason").style.display = 'none';
			}
		}
		if(mstatus_selected_val == "M")
		{
			if(trim(docF.mstatus_reason_married.value==''))
			{
				document.getElementById("mstatus_details_box").className="redbox";
				document.getElementById("mstatus_details_error_img").style.display="inline";
				docF.mstatus_reason_married.focus();
			}
			else
			{
				document.getElementById("mstatus_details_error_img").style.display="none";
				document.getElementById("mstatus_details_box").className="graybox";
				document.getElementById("mstatus_reason").style.display = 'none';
			}
		}
	}
	else if (id=='mstatus_reason_married')
	{
		var msg=docF.mstatus_reason_married.value;
		if(clear_or_fill=='clear')
		{
			if(msg=='Please let other users know about the consent of your current spouse.')
				docF.mstatus_reason_married.value='';
				
		}
		else if(clear_or_fill=='fill')
		{
			if(msg=='Please let other users know about the consent of your current spouse.' || msg=='')
				docF.mstatus_reason_married.value='Please let other users know about the consent of your current spouse.';
		}
	}
	else if (id=='mstatus_reason_awaiting')
	{
		var msg=docF.mstatus_reason_awaiting.value;
		if(clear_or_fill=='clear')
		{
			if(msg=='Please enter the status of the suit and likely dates for the ruling.')
				docF.mstatus_reason_awaiting.value='';
				
		}
		else if(clear_or_fill=='fill')
		{
			if(msg=='Please enter the status of the suit and likely dates for the ruling.' || msg=='')
				docF.mstatus_reason_awaiting.value='Please enter the status of the suit and likely dates for the ruling.';
		}
	}
	else 
	{
		document.getElementById("mstatus_reason").style.display = 'block';
		document.getElementById("mstatus_details_error_img").style.display="none";
		document.getElementById("mstatus_details_box").className="graybox";
		document.getElementById("married_reason_text").style.display="none";
		docF.court.focus();
		return false;
	}
}

function get_mstatus_value()
{
        var mstatus_radio_buttons = document.getElementsByName("mstatus");
        var i1 = mstatus_radio_buttons.length;
        for(var i=0; i<i1; i++)
        {
                if(mstatus_radio_buttons[i].checked == true)
                        return mstatus_radio_buttons[i].value;
        }
}

				
function get_mstatus_value()
{
        var mstatus_radio_buttons = document.getElementsByName("mstatus");
        var i1 = mstatus_radio_buttons.length;
        for(var i=0; i<i1; i++)
        {
                if(mstatus_radio_buttons[i].checked == true)
                        return mstatus_radio_buttons[i].value;
        }
}

function fill_mstatus_reason(obj)
{
	var mstatus_arr = document.getElementsByName("mstatus");
	var j1 = mstatus_arr.length;
	for(var j=0;j<j1;j++)
	{
		if(mstatus_arr[j].checked == true)
		{
			mstatus_selected = 1;
			var mstatus_value = mstatus_arr[j].value;
			mstatus_selected_value = mstatus_value;
			break;
		}
	}
        document.getElementById("mstatus_details_error_img").style.display="none";
	document.getElementById("married_reason_text").style.display="none";
	if(mstatus_selected_value == "A")
	{
		document.getElementById("mstatus_reason_awaiting1").style.display = 'none';
		document.getElementById("awaiting_reason_label").style.display = 'none';
		document.getElementById("mstatus_reason_awaiting1").style.display = 'none';
		if(document.getElementById("married_reason_label"))
		{
			document.getElementById("married_reason_label").style.display = 'none';
			document.getElementById("mstatus_reason_married1").style.display = 'none';
		}

		document.getElementById("mstatus_reason").style.display = 'block';
		document.getElementById("annueled_reason_label").style.display = 'inline';
		document.getElementById("mstatus_reason_annueled1").style.display = 'block';
		document.getElementById("mstatus_reason_annueled2").style.display = 'block';
		document.getElementById("mstatus_reason_annueled3").style.display = 'block';
	}
	else if(mstatus_selected_value == "I")
	{
		document.getElementById("mstatus_reason_annueled1").style.display = 'none';
		document.getElementById("mstatus_reason_annueled2").style.display = 'none';
		document.getElementById("mstatus_reason_annueled3").style.display = 'none';
		document.getElementById("annueled_reason_label").style.display = 'none';
		if(document.getElementById("married_reason_label"))
		{
			document.getElementById("married_reason_label").style.display = 'inline';
			document.getElementById("mstatus_reason_married1").style.display = 'none';
		}

		document.getElementById("mstatus_reason").style.display = 'block';
		document.getElementById("awaiting_reason_label").style.display = 'inline';
		document.getElementById("mstatus_reason_awaiting1").style.display = 'block';
	}
	else if(mstatus_selected_value == "M")
	{
		document.getElementById("mstatus_reason_awaiting1").style.display = 'none';
		document.getElementById("awaiting_reason_label").style.display = 'none';
		document.getElementById("mstatus_reason_awaiting1").style.display = 'none';
		document.getElementById("mstatus_reason_annueled1").style.display = 'none';
		document.getElementById("mstatus_reason_annueled2").style.display = 'none';
		document.getElementById("mstatus_reason_annueled3").style.display = 'none';
		document.getElementById("annueled_reason_label").style.display = 'none';

		document.getElementById("mstatus_reason").style.display = 'block';
		document.getElementById("married_reason_label").style.display = 'inline';
		document.getElementById("mstatus_reason_married1").style.display = 'block';
	}
	else
		document.getElementById("mstatus_reason").style.display = 'none';

        document.getElementById("mstatus_details_box").className="graybox";
}

function populate_std_code(obj)
{
	var temp=document.getElementById("city_residence").value.split("##");
	if(temp[1] || temp[1]=='')
		docF.state_code.value=temp[1];
}
function new_contact_number_dropdown(obj)
{
        var looking_for_id = document.getElementsByName("relationship");
	var looking_for_new;
        var i1 = looking_for_id.length;
        for(var i=0;i<i1;i++)
        {
                if(looking_for_id[i].checked==true)
                        looking_for = looking_for_id[i].value;
        }
	
	if(looking_for==1)
	{
		looking_for_new=1;
	}
	else if(looking_for==4)
	{
		looking_for_new=7;
	}
	else if(looking_for==2)
	{
		looking_for_new=3;
	}
	else if(looking_for==3)
	{
		looking_for_new=6;
	}
	else if(looking_for==5)
	{
		looking_for_new=7;
	}
	document.getElementById("phone_number_owner").value=looking_for_new;
	document.getElementById("mobile_number_owner").value=looking_for_new;
} 

function change_city(selected)
{
	var req = createNewXmlHttpObject();
        var country_code = document.getElementById('country_residence').value;
        if(selected)
	        var to_post="Only_city=1&Country_code="+country_code+"&cityRes="+selected;
	else if(country_code)
		var to_post="Only_city=1&Country_code="+country_code;
	if(to_post)
	{
		req.onreadystatechange = myjs_incomplete_handleResponse;
		req.open("POST","/P/myjs_incomplete_populate_city_code.php",true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send(to_post);
	}
}

function myjs_incomplete_handleResponse()
{
	if (req.readyState != 4)
	{
		return;
	}
	if (req.status == 200)
	{
		docF = document.form1;
		var RT = req.responseText.split("isd");
		if(document.getElementById("city_arr"))
			document.getElementById("city_arr").innerHTML= RT[0];
		if(docF.country_residence.value==51)
			docF.city_residence.focus();
		else
			docF.citizenship.focus();
		docF.country_code.value=RT[1];
		docF.country_code_mob.value=RT[1];
	}
}

function myjs_validation_response()
{
        if (req.readyState != 4)
        {
                return;
        }
        if (req.status == 200)
        {
		if(req.responseText=='Available.')
		{
			document.getElementById('username_message_ok').style.display = 'block';
			document.getElementById('username_message_ok').innerHTML='<img style="vertical-align: bottom;" src="images/registration_new/grtick.gif"><span style="color: rgb(11, 136, 5); padding-left: 4px;">Available</span>';
			document.getElementById('username_message_er').style.display = 'none';
		}
		else	
		{
			document.getElementById('username_message_er').innerHTML='<div class="spacer1">&nbsp;</div><div class="suberrmsg"><img src="images/registration_new/alert.gif" style="vertical-align: bottom;">&nbsp;<span style="color: rgb(227, 55, 59); padding-left: 1px;">'+ req.responseText + '</span></div>'
			document.getElementById('username_message_er').style.display = 'block';
			document.getElementById('username_message_ok').style.display = 'none';
		}
	}
}
function myjs_ajaxValidation(name,value)
{
	var req = createNewXmlHttpObject();
	var name = escape(name);
	var value = escape(value);
	var to_post = name + "=" + value + "&ajaxValidation=1";

	if(value != "")
	{
		req.open("POST","/P/myjs_username_validation.php",true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send(to_post);
		document.getElementById('username_message_ok').innerHTML='<img src="profile/images/registration_new/ajax-loader.gif">';
		document.getElementById('username_message_ok').style.display = 'block';
		req.onreadystatechange = myjs_validation_response;
	}
	else
	{
		document.getElementById('username_message_ok').style.display = 'none';
		document.getElementById('username_message_er').style.display = 'none';
	}
}

function validate(obj,to_validate_field)
{
	var temp_name , field_name;
	var error_fields = new Array();
        var correct_fields = new Array();
	var to_check_array = new Array();
        var err_i=0;
        var cor_i=0;
	var focusfield='',alreadypresent;
	docF = document.form1;
	if(obj)
		temp_name = obj.name ? obj.name : to_validate_field;
	//field_name = temp_name;
	if(temp_name)
	{
		var show_focus=0;
		to_check_array[0] = temp_name;
	}
	else
	{
		var show_focus=1;
		to_check_array = validate_fields;
	}
        var ii1 = to_check_array.length;
        for(var ii=0;ii<ii1;ii++)
	{
	        field_name = to_check_array[ii];
		//alert(field_name);
		alreadypresent=eval('document.form1.' + field_name + '.value');
		if(alreadypresent=='PRESENT')
			;
		else if((field_name == "fname_user" || field_name=="lname_user"))
		{
			var allowed_chars = /^[a-zA-Z\.\,\s]+$/;
			var fname_invalid_chars = 0;
			var lname_invalid_chars = 0;

			if(docF.fname_user.value != "")
			{
				if(!allowed_chars.test(docF.fname_user.value))
					fname_invalid_chars = 1;
			}
			if(docF.lname_user.value != "")
			{
				if(!allowed_chars.test(docF.lname_user.value))
					lname_invalid_chars = 1;
			}

			if(trim(docF.fname_user.value) == "" && trim(docF.lname_user.value) == "")
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = "fname_lname_submit_err";
				err_i++;
				document.getElementById("fname_error1").style.display = "inline";
				document.getElementById("fname_error2").style.display = "none";
			}
			else if(fname_invalid_chars || lname_invalid_chars)
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = "fname_lname_submit_err";
				err_i++;

				document.getElementById("fname_error1").style.display = "none";
				document.getElementById("fname_error2").style.display = "inline";
			}
			else
			{
				correct_fields[cor_i] = "fname_lname_submit_err";
				cor_i++;
			}
		}
		else if(field_name=="username")
		{
			if(docF.username.value=="")
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = "username_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "username_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "time_to_call_start" || field_name == "start_am_pm" || field_name=="time_to_call_end" || field_name == "end_am_pm")
		{
			if((docF.start_am_pm.value == docF.end_am_pm.value && parseInt(docF.time_to_call_start.value) >= parseInt(docF.time_to_call_end.value)) || (docF.start_am_pm.value == "PM" && docF.end_am_pm.value == "AM"))
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = "time_to_call_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "time_to_call_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "phone")
		{
			var found_in_array = 0 ;
			var mfound_in_array = 0 ;
			var j1 = box_action_values.length;
			for(var j=0;j<j1;j++)
			{
				if(docF.phone.value == box_action_values[j])
					found_in_array = 1;
				if(docF.mobile.value == box_action_values[j])
					mfound_in_array = 1;
			}
			var filter  = /^[0-9]+$/;
			if(found_in_array || docF.phone.value == "")
			{
				if(mfound_in_array ||docF.mobile.value == "")
				{
					if(focusfield=='')
						focusfield=field_name;
					error_fields[err_i] = field_name + "_submit_err";
					err_i++;

					document.getElementById("phone_error1").style.display = 'inline';
					document.getElementById("phone_error2").style.display = 'none';
					document.getElementById("phone_error3").style.display = 'none';
					document.getElementById("contact_number_error").style.display = 'inline';
					document.getElementById("contact_number_noerror").style.display = 'none';
				}
				else
				{
					correct_fields[cor_i] = field_name + "_submit_err";
					cor_i++;
				}
			}
			else if(!filter.test(docF.phone.value) && docF.phone.value != "")
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;
				//alert(error_fields.length);
				document.getElementById("phone_error1").style.display = 'none';
				document.getElementById("phone_error2").style.display = 'inline';
				document.getElementById("phone_error3").style.display = 'none';
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';
			}
			else if(docF.phone.value.length < 6 && docF.phone.value != "")
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;

				document.getElementById("phone_error1").style.display = 'none';
				document.getElementById("phone_error2").style.display = 'none';
				document.getElementById("phone_error3").style.display = 'inline';
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';
			}
			else
			{
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';

				correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "mobile")
		{
			var found_in_array = 0 ;
			var pfound_in_array = 0 ;
			var j1 = box_action_values.length;
			for(var j=0;j<j1;j++)
			{
				if(docF.mobile.value == box_action_values[j])
					found_in_array = 1;
				if(docF.phone.value == box_action_values[j])
					pfound_in_array = 1;
			}
			var filter  = /^[0-9]+$/;

			if(found_in_array || docF.mobile.value == "")
			{
				if(pfound_in_array || docF.phone.value == "")
				{
					if(focusfield=='')
						focusfield=field_name;
					error_fields[err_i] = field_name + "_submit_err";
					err_i++;

					document.getElementById("mobile_error1").style.display = 'inline';
					document.getElementById("mobile_error2").style.display = 'none';
					document.getElementById("mobile_error3").style.display = 'none';
					document.getElementById("contact_number_error").style.display = 'inline';
					document.getElementById("contact_number_noerror").style.display = 'none';
				}
				else
				{
					correct_fields[cor_i] = field_name + "_submit_err";
					cor_i++;
				}
			}
			else if(!filter.test(docF.mobile.value) && docF.mobile.value != "")
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;

				document.getElementById("mobile_error1").style.display = 'none';
				document.getElementById("mobile_error2").style.display = 'inline';
				document.getElementById("mobile_error3").style.display = 'none';
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';
			}
			else if(docF.mobile.value.length < 10 && docF.mobile.value != "")
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;

				document.getElementById("mobile_error1").style.display = 'none';
				document.getElementById("mobile_error2").style.display = 'none';
				document.getElementById("mobile_error3").style.display = 'inline';
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';
			}
			else
			{
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';

				correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "phone_owner_name")
		{
			var found_in_array = 0;
			var j1 = box_action_values.length;
			for(var j=0;j<j1;j++)
			{
				if(docF.phone.value == box_action_values[j])
					found_in_array = 1;
			}
			if(!found_in_array && docF.phone.value != "")
			{
				var allowed_chars = /^[a-zA-Z\.\,\s]+$/;
				var invalid_chars = 0
				if(""!=docF.phone_owner_name.value)
				{
					var temp_val = docF.phone_owner_name.value;
					if(!allowed_chars.test(temp_val))
						invalid_chars = 1;
				}

				if(""==docF.phone_owner_name.value)
				{
					if(focusfield=='')
						focusfield=field_name;
					error_fields[err_i] = "phone_owner_name_submit_err";
					err_i++;

					document.getElementById("phone_owner_name_error1").style.display = 'inline';
					document.getElementById("phone_owner_name_error2").style.display = 'none';
				}
				else if(invalid_chars)
				{
					if(focusfield=='')
						focusfield=field_name;
					error_fields[err_i] = "phone_owner_name_submit_err";
					err_i++;

					document.getElementById("phone_owner_name_error1").style.display = 'none';
					document.getElementById("phone_owner_name_error2").style.display = 'inline';
				}
				else
				{
					correct_fields[cor_i] = "phone_owner_name_submit_err";
					cor_i++;
				}
			}
			else
			{
				correct_fields[cor_i] = "phone_owner_name_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "mobile_owner_name")
		{
			var found_in_array = 0 ;
			var j1 = box_action_values.length;
			for(var j=0;j<j1;j++)
			{
				if(docF.mobile.value == box_action_values[j])
					found_in_array = 1;
			}
			if(!found_in_array && docF.mobile.value != "")
			{
				var allowed_chars = /^[a-zA-Z\.\,\s]+$/;
				var invalid_chars = 0
				if(""!=docF.mobile_owner_name.value)
				{
					var temp_val = docF.mobile_owner_name.value;
					if(!allowed_chars.test(temp_val))
						invalid_chars = 1;
				}

				if(""==docF.mobile_owner_name.value)
				{
					if(focusfield=='')
						focusfield=field_name;
					error_fields[err_i] = "mobile_owner_name_submit_err";
					err_i++;

					document.getElementById("mobile_owner_name_error1").style.display = 'inline';
					document.getElementById("mobile_owner_name_error2").style.display = 'none';
				}
				else if(invalid_chars)
				{
					if(focusfield=='')
						focusfield=field_name;
					error_fields[err_i] = "mobile_owner_name_submit_err";
					err_i++;

					document.getElementById("mobile_owner_name_error1").style.display = 'none';
					document.getElementById("mobile_owner_name_error2").style.display = 'inline';
				}
				else
				{
					correct_fields[cor_i] = "mobile_owner_name_submit_err";
					cor_i++;
				}
			}
			else
			{
				correct_fields[cor_i] = "mobile_owner_name_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "citizenship")
		{
			var country_val = docF.country_residence.value.split("|X|");
			country_val = country_val[0].split("|}|");
			if("" == docF.citizenship.value && "51" != country_val[1])
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = "citizenship_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "citizenship_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "mstatus")
		{
			var mstatus_selected = 0;
			var mstatus_arr = document.getElementsByName("mstatus");
			var j1 = mstatus_arr.length;
			for(var j=0;j<j1;j++)
			{
				if(mstatus_arr[j].checked == true)
				{
					mstatus_selected = 1;
					var mstatus_value = mstatus_arr[j].value;
					mstatus_selected_value = mstatus_value;
					break;
				}
			}

			if(mstatus_selected)
			{
				if(mstatus_value!= "N")
					document.getElementById("have_child_section").style.display = "block";
				else
					document.getElementById("have_child_section").style.display = "none";

				if(show_focus)
				{
					if(mstatus_value == "A")
					{
						if(trim(docF.court.value) == "" || docF.mstatus_day.value == "" || docF.mstatus_month.value == "" || docF.mstatus_year.value=="")
						{
							document.getElementById("mstatus_details_error_img").style.display="inline";
							document.getElementById("mstatus_details_box").className="redbox";
							if(focusfield=='')
								focusfield='court';
						}
					}
					else if(mstatus_value == "I")
					{
						var msg=docF.mstatus_reason_awaiting.value;
						if(msg=='Please enter the status of the suit and likely dates for the ruling.' || msg=='')
						{
							document.getElementById("mstatus_details_box").className="redbox";
							document.getElementById("mstatus_details_error_img").style.display="inline";
							if(focusfield=='')
								focusfield='mstatus_reason_awaiting';
						 }
					}
					else if(mstatus_value == "M")
					{
						var msg=docF.mstatus_reason_married.value;
						if(msg=='Please let other users know about the consent of your current spouse.' || msg=='')
						{
							document.getElementById("mstatus_details_box").className="redbox";
							document.getElementById("mstatus_details_error_img").style.display="inline";
							if(focusfield=='')
								focusfield='mstatus_reason_married';
						}
					}
				}
			}
			if(!mstatus_selected)
			{
				if(focusfield=='')
					focusfield=field_name;
				document.getElementById("mstatus_error1").style.display = 'inline';
				//document.getElementById("married_down_arrow").style.display = 'none';
				error_fields[err_i] = "mstatus_submit_err";
				err_i++;
			}
			
		}
                else if(field_name == "termsandconditions")
                {
                        if(docF.termsandconditions.checked==false)
                        {
				if(focusfield=='')
					focusfield='termsandconditions';
                                error_fields[err_i] = "termsandconditions_submit_err";
                                err_i++;
                        }
                        else
                        {
                                correct_fields[cor_i] = "termsandconditions_submit_err";
                                cor_i++;
                        }
                }
		else if (field_name=="degree" || field_name=="occupation" || field_name=="income" || field_name=='country_residence' || field_name=='city_residence' || field_name=="showmobile" || field_name=="showphone" || field_name=="showphone" || field_name=="mtongue")
		{
			//alert(field_name);
			if(""==document.getElementById(field_name).value)
			{
				if(focusfield=='')
					focusfield=field_name;
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
			}
		}

		var idName,idName2;

		if(error_fields.length == 0 && correct_fields.length>0)
		{
			var len2=correct_fields.length;
			for(var i=0;i<len2;i++)
			{
				idName2=correct_fields[i];
				if(document.getElementById(idName2))
					document.getElementById(idName2).style.display ="none";
			}
			if(show_focus=='')
				return true;
		}
		else
		{
	                var len = error_fields.length;
        	        for(var i=0;i<len;i++)
                	{
                        	idName=error_fields[i];
				if(document.getElementById(idName))
				document.getElementById(idName).style.display ="block";
			}
        	}
	}
	//if(show_focus)
	if (focusfield && show_focus)
	{
		//alert(focusfield);
		return focusfield;
	}
}

function trim(inputString) {
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) {
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
   }
   return retValue;
}

function box_action(obj,action)
{
        var value = obj.value;
        var name = obj.name;

	var i1 = box_action_names.length;
	for(var i=0;i<i1; i++)
	{
		if(box_action_names[i] == name && (box_action_values[i] == value || value==""))
		{
			if(action == "clear")
				document.getElementById(name).value = "";
			else if(action == "fill")
				document.getElementById(name).value = box_action_values[i];
			break;
		}
	}
}

function fill_contact_number_name(to_check,to_write)
{
	var to_check_val;
	//to_check_val=document.getElementById("phone_number_owner").value
	to_check_val=document.getElementById(to_check).value
	//if(to_check_val == "1" || to_check_val == "2")
	if(to_check_val==1)
	{
		first_name = docF.fname_user.value;
		last_name = docF.lname_user.value;
		
		if(first_name!='PRESENT')
		{	
			if(first_name && last_name)
				full_name = first_name + " " + last_name;
			else if(first_name)
				full_name = first_name;
			else if(last_name)
				full_name = last_name;
			else
				full_name = "";
			document.getElementById(to_write).value = full_name;
		}
	}
	else
		document.getElementById(to_write).value = '';
}

function show_hide_citizenship()
{
	docF = document.form1;
        if(docF.country_residence.value != "")
        {
		if(document.getElementById("citizenship_show_hide"))
		{
	                if(docF.country_residence.value != "51")
        	                document.getElementById("citizenship_show_hide").style.display = 'block';
                	else
                        	document.getElementById("citizenship_show_hide").style.display = 'none';
		}
        }
}
