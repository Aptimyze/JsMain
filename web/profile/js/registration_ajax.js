//Variable added for the Advance Search
var page = 'AS';
//End of Advance Search Variable 
var current_id;
var get_code_for;
var name_valid;
//var personal_details_fields_arr = new Array("partner_city_arr[]","residency_status","diet","partner_diet_arr[]","drink","partner_drink_arr[]","smoke","partner_smoke_arr[]","blood_group","hiv","body_type","weight","complexion","handicapped","nature_of_handicap","partner_handicapped_arr[]","spoken_languages_arr[]","messenger_id","messenger_channel","showmessenger","orkut_username","contact_address","showaddress");
var personal_details_fields_arr = new Array("residency_status","diet","drink","smoke","blood_group","hiv","body_type","weight","complexion","handicapped","nature_of_handicap","partner_handicapped_arr[]","spoken_languages_arr[]","messenger_id","messenger_channel","showmessenger","contact_address","showaddress");

var family_details_fields_arr = new Array("family_values","family_type","family_status","father_occupation","mother_occupation","brothers","married_brothers","sisters","married_sisters","about_family");

var education_profession_fields_arr = new Array("about_education","work_status","married_working","about_work");

var religion_ethnicity_fields_arr = new Array("subcaste","gotra","ancestral_origin","manglik","nakshatra","rashi","horoscope_match","horoscope","maththab","namaz","zakat","fasting","umrah_hajj","quran","sunnah_beard","sunnah_cap","hijab","hijab_marriage","working_marriage","diocese","baptised","read_bible","offer_tithe","spreading_gospel","amritdhari","cut_hair","trim_beard","wear_turban","clean_shaven","sampraday","zarathushtri","parents_zarathushtri");

var upload_photo_fields_arr = new Array("photo_privacy");

var about_myself_fields_arr = new Array("about_yourself","about_desired_partner");

var prev_value_array = new Array();
// Get an XMLHttpRequest object in a portable way.
function createNewXmlHttpObject()
{
	req = false;
	// For Safari, Firefox, and other non-MS browsers
	if(window.XMLHttpRequest)
	{
		try
		{
			req = new XMLHttpRequest();
		}
		catch (e)
		{
			req = false;
		}
	}
	// For Internet Explorer on Windows
	else if(window.ActiveXObject)
	{
		try
		{
			req = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e)
			{
				req = false;
			}
		}
	}
	return req;
}

/*Function called when response from ajax request for username/email has been received*/
function write_validation_response()
{
	if (req.readyState != 4)
	{
		// Not ready yet.
		//document.getElementById('code_status').innerHTML = "Checking..";
		return;
	}
	if (req.status == 200)
	{
		var docF = document.form1;
		var img_url = docF.img_url.value;
		var forgot_password_url;
		// The good stuff happens here!
		var got_response = req.responseText.split("#");
		if(got_response[1] == "OK")
		{
			var to_write = new Array();

			to_write.push("<img src=\"");
			to_write.push(img_url);
			to_write.push("/grtick.gif\" style=\"vertical-align:bottom;\" />");
			to_write.push("<span style=\"color:#0b8805; padding-left:4px;\">");
			to_write.push(got_response[0]);
			to_write.push("</span>");

			document.getElementById(current_id + "_ok").style.display = 'block';
			document.getElementById(current_id + "_ok").style.position = 'absolute';
			document.getElementById(current_id + "_ok").style.left = '0px';
			document.getElementById(current_id + "_ok").style.top = '0px';
			document.getElementById(current_id + "_ok").innerHTML = to_write.join('');
			document.getElementById(current_help_id).style.left = '114px';
			document.getElementById(current_id + "_er").style.display = 'none';

			document.getElementById(name_valid+"_is_ok").value = 1;
			//eval("docF." + name_valid + "_is_ok.value = 1");
		}
		else
		{
			var to_write = new Array();

			to_write.push("<div class=\"spacer1\">&#160;</div>");
			to_write.push("<div class=\"suberrmsg\"><img src=\"");
			to_write.push(img_url);
			to_write.push("/alert.gif\" style=\"vertical-align:bottom;\" />&nbsp;");
			to_write.push("<span style=\"color:#e3373b; padding-left:4px; font:10px\">");
			to_write.push(got_response[0]);
			to_write.push("</span></div>");

			document.getElementById(current_id + "_er").style.display = 'block';
			document.getElementById(current_id + "_er").innerHTML = to_write.join('');
			document.getElementById(current_help_id).style.left = '13px';
			document.getElementById(current_id + "_ok").style.display = 'none';
			if(got_response[1] == "LINK")
			Behaviour.apply("email_message_er");
			document.getElementById(name_valid+"_is_ok").value = 0;
			//eval("docF." + name_valid + "_is_ok.value = 0");
		}
	}
	else
	{
		// The web server gave us an error
		//document.getElementById('code_status').innerHTML = "Error:";
		return;
	}
}

/*Function to validate username/email using ajax request.*/
function ajaxValidation(name,value)
{
	if(name == "username" || name=="email")
	{
		var req = createNewXmlHttpObject();
		var name = escape(name);
		var value = escape(value);
		var docF = document.form1;
		var site_url = docF.site_url.value;
		var image_url = docF.img_url.value;
		current_id = name + "_message";
		current_help_id = name + "_help";
		name_valid = name;
		//var to_post = name + "=" + value + "&ajaxValidation=1";
		if(name == "username")
		{
			value_new = document.getElementById('email').value;
			var to_post = name + "=" + value + "&ajaxValidation=1" + "&" + "email" + "=" + value_new;
		}
		else
		{
			//value_new = document.getElementById('username').value;
			//var to_post = name + "=" + value + "&ajaxValidation=1" + "&" +"username" + "=" +value_new;
			var to_post = name + "=" + value + "&ajaxValidation=1" ;
		}
			
		if(value != "")
		{
			req.open("POST",site_url + "/profile/registration_ajax_validation.php",true);
			req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			req.send(to_post);

			document.getElementById(current_id + "_ok").style.display = 'block';
			document.getElementById(current_id + "_ok").style.position = 'absolute';
			document.getElementById(current_id + "_ok").style.left = '0px';
			document.getElementById(current_id + "_ok").style.top = '0px';
			document.getElementById(current_id + "_ok").innerHTML = "<img src=\"" + image_url + "/ajax-loader.gif\" \/>";
			document.getElementById(current_help_id).style.left = '114px';
			document.getElementById(current_id + "_er").style.display = 'none';

			req.onreadystatechange = write_validation_response;
		}
		else
		{
			document.getElementById(current_id + "_er").style.display = 'none';
			document.getElementById(current_id + "_ok").style.display = 'none';
			document.getElementById(current_help_id).style.left = '13px';
		}
		//layer(); // Commenting this as we dont need that now
	}
}

/*Function to save details using ajax on changing tab (clicking next/back)*/
function save_current_details(fields_array_name)
{
	var to_save_array = new Array();
	var tabs_present_name = new Array("personal_details_fields_arr","family_details_fields_arr","education_profession_fields_arr","religion_ethnicity_fields_arr","upload_photo_fields_arr","about_myself_fields_arr");
	var j=0
	var i1 = tabs_present_name.length;
	for(var i=0;i<i1;i++)
	{
		if(fields_array_name != tabs_present_name[i])
		{
			to_save_array[j] = tabs_present_name[i];
			j++;
		}
	}

	var to_post_arr  = new Array(),to_post;
	var docF = document.form1;
	var site_url = docF.site_url.value;
	var array_count = 0;
	var x1 = to_save_array.length;
	var i1;

	for(var x=0;x<x1;x++)
	{
		fields_array = to_save_array[x];
		i1 = eval(fields_array + ".length");

		for(var i=0;i<i1;i++)
		{
			var field_name = eval(fields_array + "[i]");
			var field_value = "";
			var value_string = "";
			var element_exists = 0;
			/*if(field_name.indexOf("[]") > 0)
			{
				if(document.getElementsByName(field_name))
					element_exists = 1;
			}
			else*/
			{
				if(document.getElementById(field_name))
					element_exists = 1;
			}
			
			if(element_exists)
			{
				var name_array = document.getElementsByName(field_name);
				if(name_array[0].type)
					var name_array_type = name_array[0].type
				else
					var name_array_type = name_array.type;

				if(field_name.indexOf("[]") > 0)
				{
					if(name_array_type == "checkbox")
					{
						var j1 = name_array.length;
						for(var j=0;j<j1;j++)
						{
							if(name_array[j].checked == true)
							value_string += name_array[j].value + ",";
						}
					}
					/*else if(name_array_type == "select-multiple")
					{
						var j1 = name_array[0].options.length;
						for(var j=0;j<j1;j++)
						{
							if(name_array[0].options[j].selected == true)
							value_string += name_array[0].options[j].value + ",";
						}
					}*/

					field_name = rtrim(field_name,"[]");
					if(value_string)
					field_value = rtrim(value_string,",");
				}
				else if(name_array_type == "radio" || name_array_type == "checkbox")
				{
					var j1 = name_array.length;
					for(var j=0;j<j1;j++)
					{
						if(name_array[j].checked == true)
						value_string += name_array[j].value + ",";
					}
					if(value_string)
					field_value = rtrim(value_string,",");
				}
				else
					field_value = eval("docF." + field_name + ".value");

				if(prev_value_array[field_name] != field_value && field_value != "")
				{
					to_post_arr[array_count] = escape(field_name) + "=" + escape(field_value);
					array_count++;
				}
				prev_value_array[field_name] = field_value;
			}
		}
	}
	if(to_post_arr.length > 0)
	{
		var req = createNewXmlHttpObject();
		var profileid = docF.profileid.value;

		to_post = to_post_arr.join("&");
		to_post += "&ajax_submit_pg2=1&profileid=" + profileid;

		req.open("POST",site_url + "/profile/registration_pg2.php",true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send(to_post);
		
		req.onreadystatechange = write_profile_percent;
	}
}


/*Function to check existance of phone/mobile number*/
function check_contact_number(which_number,value)
{
	var req = createNewXmlHttpObject();
	var for_which_number = escape(which_number);
	value = escape(value);
	docF = document.form1;
	var site_url = docF.site_url.value;

	var to_post = "for_which_number=" + for_which_number + "&value=" + value + "&check_contact_number=1";

	if(value != "")
	{
		req.open("POST",site_url + "/profile/registration_ajax_validation.php",true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send(to_post);

		req.onreadystatechange = show_login;
	}
}

/*Function to show/hide link to login page if contact number already exists*/
function show_login()
{
	if (req.readyState != 4)
	{
		return;
	}
	if (req.status == 200)
	{
		if(req.responseText == "SHOW_LOGIN_MOB")
		{
			document.getElementById("login_link_mob").style.display = 'block';
			document.getElementById("login_link_phone").style.display = 'none';
		}
		else
		{
			document.getElementById("login_link_mob").style.display = 'none';
		}

		if(req.responseText == "SHOW_LOGIN_PHONE")
		{
			document.getElementById("login_link_phone").style.display = 'block';
			document.getElementById("login_link_mob").style.display = 'none';
		}
		else
		{
			document.getElementById("login_link_phone").style.display = 'none';
		}
	}
	else
	return;
}

/*Function to get partner caste using caste mapping*/
function get_caste_using_caste_mapping(obj)
{
	if(obj)
		var dropdown_name = obj.name;
	else
		var dropdown_name = "caste";

	if(dropdown_name == "caste")
	{
		var docF = document.form1;
		var req = createNewXmlHttpObject();
		var mtongue_val = escape(docF.mtongue.value);
		var caste_val = escape(docF.caste.value);
		var religion_val = escape(get_religion_value());
		var site_url = docF.site_url.value;
		
		/* Commenting this condition as we dont need caste entry from the users for time being , uncomment this condition if you need input from users for the extra caste */
		
		/*if(caste_val >= 242 && caste_val <= 246)
			document.getElementById("caste_entry_section").style.display = 'block';
		else
			document.getElementById("caste_entry_section").style.display = 'none'; 
		*/

		var to_post = "mtongue=" + mtongue_val + "&caste=" + caste_val + "&religion=" +  religion_val + "&caste_mapping=1";

		if(mtongue_val != "" && caste_val != "")
		{
			req.open("POST",site_url + "/profile/registration_ajax_validation.php",true);
			req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			req.send(to_post);

			req.onreadystatechange = populate_caste_using_caste_mapping;
		}
		else if(mtongue_val == "" && caste_val != "")
		{
			var caste_dropdown = docF.caste;
			var caste_options = caste_dropdown.options;
			var i1 = caste_options.length;
			for(var i=0; i<i1;i++)
			{
				if(caste_options[i].value != "")
				caste_options[i].checked = false;
			}

			var to_tick_id = "partner_caste_" + caste_dropdown.value;
			document.getElementById(to_tick_id).checked = true;
			swap_checkboxes("partner_caste");
		}

		/* Div Style Change for the  Caste , Intergrated Loader After changing caste every time */
		document.getElementById("caste_loader").style.display='block';
		document.getElementById("lgreen_caste").style.display='none'; 
	}
}

/*Function to write the caste - mapping values in the preferred partner div*/
function populate_caste_using_caste_mapping()
{
	if (req.readyState != 4)
	{
		return;
	}
	if (req.status == 200)
	{
		var got_response = req.responseText.split("|#X#|");
		document.getElementById("partner_caste_source_div").innerHTML = got_response[0];
		document.getElementById("partner_caste_div").innerHTML = got_response[1];
		document.getElementById("partner_caste_target_div").innerHTML = got_response[2];
		document.getElementById("partner_caste_DM").checked = true;
		/* Div Style Change for the  Caste , Intergrated Loader After changing caste every time */
		document.getElementById("caste_loader").style.display='none';
		document.getElementById("lgreen_caste").style.display='block'; 
		Behaviour.apply("partner_caste_source_div");
		Behaviour.apply("partner_caste_div");
		set_initial_focus("partner_caste_displaying_arr[]");
	}
	else
	return;
}

/*Function to send forgot password info*/
function send_username_password(to_send_email)
{
	var req = createNewXmlHttpObject();
	to_send_email = escape(to_send_email);
	docF = document.form1;
	var site_url = docF.site_url.value;

	var to_post = "to_send_email=" + to_send_email + "&forgot_password=1";

	if(to_send_email != "")
	{
		req.open("POST",site_url + "/profile/registration_ajax_validation.php",true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send(to_post);

		req.onreadystatechange = email_sent_notify;
	}
}

/*Function to notify that email has been sent.*/
function email_sent_notify()
{
	if (req.readyState != 4)
	{
		return;
	}
	if (req.status == 200)
	{
		var img_url = docF.img_url.value;
		var to_write = new Array();

		to_write.push("<div class=\"spacer1\">&#160;</div>");
		to_write.push("<div class=\"suberrmsg\"><img src=\"");
		to_write.push(img_url);
		to_write.push("/alert.gif\" style=\"vertical-align:bottom;\" />&nbsp;");
		to_write.push("<span style=\"padding-left:4px; font:10px; color:#e3373b;\">");
		to_write.push(req.responseText);
		to_write.push('</span></div>');

		document.getElementById("email_message_er").innerHTML = to_write.join('');
	}
	else
		return;
}

function write_profile_percent()
{
	if(req.readyState != 4)
		return;
	
	if(req.status == 200)
	{
		if(req.responseText)
		{
			//document.getElementById("profile_percent_image_div").style.width = req.responseText;
			document.getElementById("profile_percent_image_div").width = req.responseText;
			document.getElementById("profile_percent_span").innerHTML = req.responseText;
		}
	}
	else
		return;
}
