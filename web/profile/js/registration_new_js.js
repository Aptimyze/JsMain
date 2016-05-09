/*Defining some global variables*/
var keycode;
var shift_key;
//finding the pressed key code.
document.onkeydown = checkKeycode
document.onkeyup = crossCheckKeycode;

//array of fields which require validation.


//var validate_fields = new Array("email","password","gender","day","month","year","mstatus","has_children","height","country_residence","city_residence","phone","showphone","mobile","showmobile","degree","occupation","income","mtongue","religion","caste","termsandconditions");

var validate_fields = new Array("password","gender","day","month","year","mstatus","has_children","height","country_residence","city_residence","phone","showphone","mobile","showmobile","degree","occupation","income","mtongue","religion","caste","termsandconditions");

//for document.form
var docF = "";

//scroller array, selections which include scrolling gadget.
var scroller_arr = new Array('partner_mstatus','partner_degree','partner_income','partner_mtongue','partner_religion','partner_caste','partner_handicapped','spoken_languages','partner_wstatus','partner_occupation','partner_country','partner_city','partner_nhandicapped','partner_education','partner_hchild','partner_sampraday','partner_turban','partner_diet','partner_body','partner_complexion','partner_mathab','hobbies','music','interest','book','movies','cuisine','language','dress','sports','partner_smoke','partner_drink');

//defining arrays, for boxes which needs to be blanked on focus.
var box_action_names = new Array("state_code", "phone", "mobile");
var box_action_values = new Array("STD", "Phone No.", "Mobile No.");

var help_arr = new Array("email","password","username","day","month","year","mtongue","hiv","about_family","about_education","married_working","about_work","subcaste","gotra","ancestral_origin","diocese","occupation");

/*Function to clear/fill input box*/
function dID(arg)
{
	return document.getElementById(arg);
}
function box_action(obj,action)
{
        var value = obj.value;
        var name = obj.name;
        /* Work for Text Colour Change */

        if(obj.type=='text' && action!="" && (obj.name=='about_education' || obj.name=='about_work'))
        {
                        if(action=='clear')
                                obj.style.color='#000000';
                        if(obj.value=="")
                                obj.style.color='#989491';
        }

        if(obj.type=='textarea' && action!="" && ( obj.name=='about_family'))
        {
                         if(action=='clear')
                                 obj.style.color='#000000';
                         if(obj.value=="")
                                 obj.style.color='#989491';
        }

	var i1 = box_action_names.length;
	for(var i=0;i<i1; i++)
	{
		if(box_action_names[i] == name && (box_action_values[i] == value || value==""))
		{
			if(action == "clear")
				dID(name).value = "";
			else if(action == "fill")
				dID(name).value = box_action_values[i];
			break;
		}
	}
}
function check_mtongue()
{
	dID("mtongue_submit_err").style.display='inline';
	dID("mtongue").focus();
}
/*Function to show or hide partner details section*/
function show_hide_partner(obj,show_section)
{
}

/*Function to display help box (balloon tip)*/
function show_help(obj)
{
        var focus_name = obj.name;
        var i1 = help_arr.length;
        for(var i=0;i<i1;i++)
        {
                if(focus_name == help_arr[i])
                {
                        dID(focus_name+"_help").style.display='block';
                        break;
                }
        }
}

/*Function to hide help box (balloon tip)*/
function hide_help(obj)
{
        var blur_name = obj.name;
        var i1 = help_arr.length
        for(var i=0;i<i1;i++)
        {
                if(blur_name == help_arr[i])
                {
                        dID(blur_name+"_help").style.display='none';
                        break;
                }
        }
}
/*Function to trim specified characters*/
function trim(str, chars)
{
	return ltrim(rtrim(str, chars), chars);
}

/*Function to trim specified characters from left*/
function ltrim(str, chars)
{
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

/*Function to trim specified characters from right*/
function rtrim(str, chars)
{
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

/*Function to fill certain details on page rethrow*/
function fill_details(fill_array)
{
	var curname, fields, fields_type, str_name, csv_str, to_tick_id;
	var csv_arr = new Array();
	var i1 = fill_array.length;
	for(var i=0;i<i1;i++)
	{
		restore_checkboxes(fill_array[i]);
		curname = fill_array[i] + "_arr[]";
		if(document.getElementsByName(curname))
		{
			fields = document.getElementsByName(curname);
			if(fields[0])
			fields_type = fields[0].type;

			if(fields_type == "checkbox")
			{
				str_name = fill_array[i] + "_str";
				//var csv_str = eval("docF." + str_name + ".value");
				csv_str = dID(str_name).value;
				if(csv_str && csv_str!='undefined')
				{
					csv_str = rtrim(ltrim(csv_str,"'"),"'");
					csv_arr = csv_str.split("','");
					var j1 = csv_arr.length;
					for(var j=0;j<j1;j++)
					{
						to_tick_id = fill_array[i] + "_" + csv_arr[j];
						if(dID(to_tick_id))
						{
							dID(to_tick_id).checked = true;
						}
					}
					if(page=='AS')
						swap_checkboxes(fill_array[i],'load');
					else
						swap_checkboxes(fill_array[i]);
				}
			}
		}
	}
}

/*Function to capture key code*/
function checkKeycode(e)
{
	if(window.event)
		keycode = window.event.keyCode;
	else if(e)
		keycode = e.which;

	if(keycode == "16")
		shift_key = "pressed";
}

/*Function to crosscheck key code on key up*/
function crossCheckKeycode(e)
{
	if(window.event)
		keycode = window.event.keyCode;
	else if(e)
		keycode = e.which;

	if(keycode == "16")
		shift_key = "released";
}

/*Function to allow either doesn't matter or the actual value*/
function remove_doesnt_matter_conflict(obj)
{
	var checkboxes_array = new Array("partner_mstatus","partner_degree","partner_income","partner_mtongue","partner_religion","partner_caste","partner_wstatus","partner_occupation","partner_country","partner_nhandicapped","partner_handicapped");
	var required_field_name;
	var current_checkbox_array;
	var i1 = checkboxes_array.length;

	for(var i=0;i<i1;i++)
	{
		if(obj.name.match(checkboxes_array[i]) && obj.type == "checkbox")
		{
			current_checkbox_array = checkboxes_array[i];
			required_field_name = checkboxes_array[i] + "_arr[]";
			break;
		}
	}

	if(typeof(required_field_name) != "undefined")
	{
		var current_value;
		if(obj.checked == true)
			current_value = obj.value;

		if(current_value == "DM")
		{
			var checkboxes = document.getElementsByName(required_field_name);
			var i1 = checkboxes.length;
			for(var i=0;i<i1;i++)
			{
				if(checkboxes[i].value != "DM")
					checkboxes[i].checked = false;
			}
		}
		else
		{
			var checkboxes = current_checkbox_array + "_DM";
			if(dID(checkboxes))
				dID(checkboxes).checked = false;
		}
		swap_checkboxes(required_field_name);
	}
}

/*function to check if a value already exists in an array*/
function in_array(needle,haystack)
{
	var found = false;
	var i1 = haystack.length;
	for(var i=0;i<i1;i++)
	{
		if(needle == haystack[i])
		{
			found = true;
			break;
		}
	}

	return found;
}

/*Function to set the initial focus on partner details section*/
function set_initial_focus(array_name)
{
	var arr_name = document.getElementsByName(array_name);
	//trim the part _displaying_arr[] which comes out to be of 17 characters
	if(arr_name.length)
	{
		var trimmed_name = array_name.substr(0,array_name.length - 17);
		if(dID(trimmed_name) && document.getElementById(trimmed_name).style.display == "block")
		try{
			arr_name[0].focus();
		}
		catch(err)
		{
			var err_mes=1;
		}
	}
}

/*Function to get the maximum value from an array*/
function max(get_array)
{
	var maximum = 0;
	var i1 = get_array.length;
	for(var i=0;i<i1;i++)
	{
		if(get_array[i] > maximum)
			maximum = get_array[i];
	}
	return maximum;
}

/*Function to get the marital status value*/
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

/*Function to get the religion value*/
function get_religion_value()
{
	var religion_caste = docF.religion.value.split("|X|");
	return religion_caste[0];
}

// Function for the color changes 
function colorchange()
{
        dID("contact_address").style.color ='#000000';
	dID("about_desired_partner").style.color ='#000000';
}
