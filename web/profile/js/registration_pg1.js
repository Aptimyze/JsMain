/*Defining some global variables*/
//defining variable for document.form
var docF;
//used when changing div class
var alreadyProcessed = false;

//variable to set focus on error
var required_field_name = ""

//age variable.
var age = "";

//used when user selects "Looking for" to change selected gender.
var action = "";

//used to show age error depending on male/female selection.
var gender_val_selected = ""

//submit button variable.
var submit_button_clicked=0;

//storing mstatus selected value
var mstatus_selected_value;

//defining arrays for different sections to use when changing div class.
var email_section_arr = new Array("email","retrieve_profile_link","forgot_password_link","password","confirm_password");
var basicInfo_section_arr = new Array("fname_user","lname_user","username","gender","day","month","year","lage","hage","mstatus","court","mstatus_day","mstatus_month","mstatus_year","mstatus_reason","mstatus_details_submit","partner_mstatus_displaying_arr[]","partner_mstatus_select_all","partner_mstatus_clear_all","has_children","children","height","lheight","hheight","country_residence","citizenship","city_residence","country_code","state_code","phone","phone_number_owner","phone_owner_name","showphone","country_code_mob","mobile","mobile_number_owner","mobile_owner_name","showmobile","time_to_call_start","time_to_call_end","start_am_pm","time_to_call_end","end_am_pm");
//var educationCareer_section_arr = new Array("degree","partner_degree_displaying_arr[]","partner_degree_select_all","partner_degree_clear_all","occupation","income","partner_income_displaying_arr[]","partner_income_select_all","partner_income_clear_all");
var educationCareer_section_arr = new Array("degree","occupation","income","partner_income_displaying_arr[]","partner_income_select_all","partner_income_clear_all");
var religionEthnicity_section_arr = new Array("mtongue","partner_mtongue_displaying_arr[]","partner_mtongue_select_all","partner_mtongue_clear_all","religion","partner_religion_displaying_arr[]","partner_religion_select_all","partner_religion_clear_all","speak_urdu","caste","caste_entry","partner_caste_displaying_arr[]","partner_caste_select_all","partner_caste_clear_all");

//defining arrays, used to change section tabs depending on "Looking for " selection.
var relation_arr = new Array('self','friend','son','daughter','brother','sister','father','mother','marriageBureau');
var label_arr = new Array('_basicInfo','_educationCareer','_religionEthnicity');
var div_sections = new Array("email_section","basicInfo_section","educationCareer_section","religionEthnicity_section");

//array for contact number dropdown.
var contact_number = new Array("phone","mobile");
var contact_dropdown = new Array('Bride','Groom','Parent','Son','Daughter','Sibling','Other');
var contact_dropdown_val = new Array('1','2','3','4','5','6','7');

//var partner_fields_array = new Array("partner_mstatus","partner_degree","partner_income","partner_mtongue","partner_religion","partner_caste");
var partner_fields_array = new Array("partner_mstatus","partner_income","partner_mtongue","partner_religion","partner_caste");

var registration={
	'input' : function(element){
			element.onclick = function(){
				show_hide_partner(this);
				add_checkboxes(this);
//				age_calculation();
//				remove_doesnt_matter_conflict(this);
			}
			element.onfocus = function(){
				change_div_class(this);
				show_help(this);
				box_action(this,'clear');
			}
			element.onblur=function(){
				hide_help(this);
				box_action(this,'fill');
				ajaxValidation(this.name,this.value);
				validate(this);
			}
		},

	'select' : function(element){
			element.onfocus = function(){
				change_div_class(this);
				show_help(this);
			}
			element.onblur=function(){
				hide_help(this);
				validate(this);
			}
			element.onchange = function(){
				show_hide_partner(this);
			}
		},

	'textarea' : function(element){
				element.onblur=function(){
					validate(this);
				}
		},
	'about_yourself' : function(element){
				element.on
		},

	'a' : function(element){
			if(!element.onclick)
			element.onclick = function(){
				if(this.id != "live_help")
				{
					var current_id = this.id
					//edit_partner_selection(this);
					add_checkboxes(this);
					remove_checkboxes(this);
					mstatus_details(this);
					if(current_id)
					{
						if(current_id == "forgot_password_link")
						{
							send_username_password(docF.email.value);
							return false;
						}
						else if(current_id == "retrieve_profile_link")
							return true;
						else
							return false;
					}
				}
			}
			element.onfocus = function(){
				if(this.id != "live_help")
					change_div_class(this);
			}
		},
	
	// '#live_help' : function(element){
	// 		element.onclick = function(){
	// 			window.open('http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&imageUrl=http://www.jeevansathi.com/images_try/liveperson&referrer='+escape(document.location),'chat13507809','width=472,height=320');
	// 			return false;
	// 		}
	// 	},

	'#relationship' : function(element){
			element.onclick = function(){
				display_rest_of_page();
				change_tab_labels();
				gender_display_selection();
				toggle_contact_number_dropdown();
			}
			element.onkeydown = function(){
				//keycode=9 for tab, to disallow tabbing when the page is grayed out
				if(keycode == "9" && document.getElementById("gray_layer").style.display == "block")
					return false;
			}
			element.onkeypress = function(){
				//keycode=9 for tab, to disallow tabbing when the page is grayed out
				if(keycode == "9" && document.getElementById("gray_layer").style.display == "block")
					return false;
			}
		},

	'#email' : function(element){
			element.onkeydown = function(){
				docF.email_is_ok.value = "1";
				if(keycode == "9" && document.getElementById("gray_layer").style.display == "block")
					return false;
			}
			element.onkeypress = function(){
				//keycode=9 for tab, to disallow tabbing when the page is grayed out
				if(keycode == "9" && document.getElementById("gray_layer").style.display == "block")
					return false;
			}
		},

	'#username' : function(element){
			element.onkeydown = function(){
				docF.username_is_ok.value = "1";
			}
		},

	'#fname_user' : function(element){
			element.onkeyup = function(){
				toggle_contact_number_dropdown();
			}
		},

	'#lname_user' : function(element){
			element.onkeyup = function(){
				toggle_contact_number_dropdown();
			}
		},

	'#gender_section' : function(element){
			element.onclick = function(){
				toggle_contact_number_dropdown();
				populate_yob();
			}
		},

	'#mstatus_section' : function(element){
			element.onclick = function(){
				mstatus_details(this);
			}
		},

	'#mstatus_reason' : function(element){
			element.onfocus = function(){
				mstatus_details(this,"","clear");
			}
			element.onblur = function(){
				mstatus_details(this,"","fill");
			}
		},

	'#mstatus_details_submit' : function(element){
			element.onclick = function(){
				mstatus_details(this);
			}
		},

	'#country_residence' : function(element){
			element.onchange = function(){
				show_hide_citizenship();
				fetch_code("COUNTRY",docF.country_residence.value);
				populate_city();
			}
		},

	'#city_residence' : function(element){
			element.onchange = function(){
				fetch_code("CITY",docF.city_residence.value);
			}
		},

	'#phone' : function(element){
			element.onblur = function(){
				check_contact_number("PHONE",this.value);
				box_action(this,'fill');
				validate(this);
			}
		},

	'#mobile' : function(element){
			element.onblur = function(){
				check_contact_number("MOBILE",this.value);
				box_action(this,'fill');
				validate(this);
			}
		},

	'#phone_number_owner' : function(element){
			element.onchange = function(){
				fill_contact_number_name();
			}
		},

	'#mobile_number_owner' : function(element){
			element.onchange = function(){
				fill_contact_number_name();
			}
		},

	'#mtongue' : function(element){
			element.onchange = function(){
				populate_partner_mtongue_logical(this);
				show_hide_partner(this);
				get_caste_using_caste_mapping();
				show_hide_partner(document.getElementById('caste'));
			}
		},

	'#religion' : function(element){
			element.onchange = function(){
				show_hide_partner(this);
				populate_partner_religion(this);
				populate_caste_from_religion(this);
			}
		},

	'#caste' : function(element){
			element.onchange = function(){
				get_caste_using_caste_mapping(this);
				show_hide_partner(this);
			}
		},
	'#about_yourself' :function(element){
				element.onkeyup = function(){
					 var about_yourself_value = this.value;
	                                 about_yourself_value = about_yourself_value.replace(/^\s+|\s+$/g, "");
	                                 var about_yourself_value_count = about_yourself_value.length;
	                                 if(about_yourself_value_count >= 100)
		                                       document.getElementById("about_yourself_count").style.color = '#00BB00';
		                         else
		                                       document.getElementById("about_yourself_count").style.color = '#FF0000';
		                         document.getElementById("about_yourself_count").innerHTML = about_yourself_value_count;
				},

	'#submit_pg1' : function(element){
			element.onclick = function(){
				submit_button_clicked=1;
				if(!validate())
				{
					logerror_on_submit(2);
					var temp_arr = document.getElementsByName(required_field_name);
					if(temp_arr[0].type == "radio" || temp_arr[0].type == "checkbox")
						temp_arr[0].focus();
					else
						document.getElementById(required_field_name).focus();
						//eval("docF." + required_field_name + ".focus()");
				
					return false;
				}
				else if(docF.email_is_ok.value == 0)
				{
					logerror_on_submit(2);
					docF.email.focus();
					return false;
				}
				else if(docF.username_is_ok.value == 0)
				{
					logerror_on_submit(3);
					docF.username.focus();
					return false;
				}
//				else if((mstatus_selected_value == "D" || mstatus_selected_value == "A") && (trim(docF.court.value) == "" || docF.mstatus_day.value == "" || docF.mstatus_month.value == "" || docF.mstatus_year.value==""))
				else if((mstatus_selected_value == "A") && (trim(docF.court.value) == "" || docF.mstatus_day.value == "" || docF.mstatus_month.value == "" || docF.mstatus_year.value==""))
				{
					logerror_on_submit(4);
					mstatus_details("","mstatus_details_submit");
					docF.court.focus();
					return false;
				}
				else if((mstatus_selected_value == "M" || mstatus_selected_value == "S") && trim(docF.mstatus_reason.value) == "")
				{
					logerror_on_submit(5);
					mstatus_details("","mstatus_details_submit");
					docF.mstatus_reason.focus();
					return false;
				}
				else if(document.getElementById("about_yourself_count").innerHTML < 100)
				{
					return false;
				}
				logerror_on_submit(1);
			}
		}
};

Behaviour.register(registration);
Behaviour.addLoadEvent(onload_events);

function onload_events()
{
	display_rest_of_page("onload");
}

/*Function to display rest of page, i.e. hiding the gray layer*/
function display_rest_of_page(called_when)
{
	docF = document.form1;
	var enable_page=0;
	var relationship_arr = document.getElementsByName("relationship");
	var i1 = relationship_arr.length;
	for(var i=0;i<i1; i++)
	{
		if(relationship_arr[i].checked == true)
		{
			enable_page = 1;
			break;
		}
		else
			enable_page = 0;
	}

	if(enable_page)
	{
		document.getElementById("gray_layer").style.display='none';
		var select_dropdowns = document.getElementsByTagName("select");
		var i1 = select_dropdowns.length;
		for(var i=0;i<i1;i++)
			select_dropdowns[i].disabled = false;

		docF.email.focus();
	}
	else
	{
		var select_dropdowns = document.getElementsByTagName("select");
		var i1 = select_dropdowns.length;
		for(var i=0;i<i1;i++)
			select_dropdowns[i].disabled = true;
	}

	if(called_when == "onload" && enable_page)
	{
		change_tab_labels();
		gender_display_selection();
		show_hide_partner();
		show_hide_citizenship();
		populate_city(docF.city_residence_selected.value);
		populate_partner_mtongue_logical();
		populate_partner_religion();
		populate_caste_from_religion();
		get_caste_using_caste_mapping();
		fill_details(partner_fields_array);
	}
}

/*Function to change tab labels, depending on Looking for selecetion*/
function change_tab_labels()
{
	var i,j,selected_val;
	var relationship_arr = document.getElementsByName("relationship");
	var i1 = relationship_arr.length;
	for(i=0;i<i1;i++)
	{
		if(relationship_arr[i].checked==true)
			selected_val = relationship_arr[i].value;
	}

	if(selected_val=="1")
		label='self';
	else if(selected_val=="2")
	{
		label='son';
		action = 'male_select';
	}
	else if(selected_val=="2D")
	{
		label='daughter';
		action = 'female_select';
	}
	else if(selected_val=="3")
	{
		label='father';
		action = 'male_select';
	}
	else if(selected_val=="3D")
	{
		label='mother';
		action = 'female_select';
	}
	else if(selected_val=="4")
		label='friend';
	else if(selected_val=="5")
		label='marriageBureau';
	else if(selected_val=="6")
	{
		label='brother';
		action = 'male_select';
	}
	else if(selected_val=="6D")
	{
		label='sister';
		action = 'female_select';
	}

	if(typeof(label) != "undefined")
	{
		var i1 = relation_arr.length;
		var j1 = label_arr.length;
		for(i=0; i<i1; i++)
		{
			for(j=0; j<j1; j++)
			{
				var div_id = relation_arr[i] + label_arr[j];
				if(document.getElementById(div_id))
				{
					if(relation_arr[i] == label)
						document.getElementById(div_id).style.display = 'block';
					else
						document.getElementById(div_id).style.display = 'none';
				}
			}
		}
	}
}

/*Function to toggle gender selection, depending on Looking for selection*/
function gender_display_selection()
{
	var a=8;
	if(action=='male_select')
	{
		docF.gender.value = 'M';
		var gender_arr = document.getElementsByName("gender");
		var i1 = gender_arr.length;
		for(var i=0; i<i1; i++)
		{
			if(gender_arr[i].value=='M')
			{
				gender_arr[i].checked = true;
				document.getElementById("gender_section").style.display='none';
				break;
			}
		}

		/* Showing the Photo of Female*/

		if(document.styleSheets[4].insertRule)
		{
			document.styleSheets[4].deleteRule(0);
                        document.styleSheets[4].deleteRule(0);
			document.styleSheets[4].insertRule('.partner_image_male{display:none;}',0);
                        document.styleSheets[4].insertRule('.partner_image_female{display:inline;}',0);
		}
		else
		{
			document.styleSheets[4].removeRule(0);
			document.styleSheets[4].removeRule(0);
			document.styleSheets[4].addRule('.partner_image_male','{display:none;}',0);
			document.styleSheets[4].addRule('.partner_image_female','{display:inline;}',0);
		}

		/* End of the Section */
		
		/* Showing the Married option for the Male */
		
		document.getElementById("mstatus_married_field").style.display='inline';
		//document.getElementById("married_down_arrow").style.display='inline';
		document.getElementById("fill_mstatus_details").style.display='inline';
		document.getElementById("mstatus_error2").style.display='inline';
		var mstatus_selected_val = get_mstatus_value();
                if(mstatus_selected_val=='M')
			document.getElementById("have_child_section").style.display='inline';

		/* End of the Section*/

	}
	else if(action=='female_select')
	{
		docF.gender.value = 'F';
		var gender_arr = document.getElementsByName("gender");
		var i1 = gender_arr.length;
		for(var i=0; i<i1; i++)
		{
			if(gender_arr[i].value=='F')
			{
				gender_arr[i].checked = true;
				document.getElementById("gender_section").style.display='none';
				break;
			}
		}

		/* Start of the Section Male Photo Display Section */

		if(document.styleSheets[4].insertRule)
		{
			document.styleSheets[4].deleteRule(0);
			document.styleSheets[4].deleteRule(0);
			document.styleSheets[4].insertRule('.partner_image_male{display:inline;}',0);
			document.styleSheets[4].insertRule('.partner_image_female{display:none;}',0);
		}
		else
		{	
			document.styleSheets[4].removeRule(0);
                        document.styleSheets[4].removeRule(0);
			document.styleSheets[4].addRule('.partner_image_male','{display:inline;}',0);
			document.styleSheets[4].addRule('.partner_image_female','{display:none;}',0);
		}

		/* End of the Section*/

		/*for(var i=0;i<a;i++)
		{
			if(document.getElementsByName("partner_image_female")[i])
			{
				document.getElementsByName("partner_image_female")[i].style.display = 'none';
				document.getElementsByName("partner_image_male")[i].style.display = 'block';
			}
		}*/

		document.getElementById("gender_section").style.display='none';
		
		/* Hiding the Married option for the Female */

		document.getElementById("mstatus_married_field").style.display='none';
		document.getElementById("married_down_arrow").style.display='none';
		document.getElementById("fill_mstatus_details").style.display='none';
		document.getElementById("mstatus_error2").style.display='none';
		var mstatus_selected_val = get_mstatus_value();
		if(mstatus_selected_val=='M')
			document.getElementById("have_child_section").style.display='none';

		/* End of the Section */
	}
	else
		document.getElementById("gender_section").style.display='block';

	action = "";
}

/*Function to change div class i.e to change the background color depending on focus*/
function change_div_class(obj)
{
	if(in_array(obj.name,email_section_arr))
	{
		document.getElementById("email_section").className="gray_bg";
		document.getElementById("basicInfo_section").className="nothighlight";
		document.getElementById("educationCareer_section").className="nothighlight";
		document.getElementById("religionEthnicity_section").className="nothighlight";
	}
	else if(in_array(obj.name,basicInfo_section_arr))
	{
		document.getElementById("email_section").className="nothighlight";
		document.getElementById("basicInfo_section").className="gray_bg";
		document.getElementById("educationCareer_section").className="nothighlight";
		document.getElementById("religionEthnicity_section").className="nothighlight";
	}
	else if(in_array(obj.name,educationCareer_section_arr))
	{
		document.getElementById("email_section").className="nothighlight";
		document.getElementById("basicInfo_section").className="nothighlight";
		document.getElementById("educationCareer_section").className="gray_bg";
		document.getElementById("religionEthnicity_section").className="nothighlight";
	}
	else if(in_array(obj.name,religionEthnicity_section_arr))
	{
		document.getElementById("email_section").className="nothighlight";
		document.getElementById("basicInfo_section").className="nothighlight";
		document.getElementById("educationCareer_section").className="nothighlight";
		document.getElementById("religionEthnicity_section").className="gray_bg";
	}
}

/*Function to get the selected gender value*/
function get_gender_value()
{
	var gender_val;
	var gender_elements = document.getElementsByName("gender");
	var x1 = gender_elements.length;
	for(var x=0;x<x1;x++)
	{
		if(gender_elements[x].checked == true)
		{
			gender_val = gender_elements[x].value;
			break;
		}
	}
	return gender_val;
}

/*function to populate and select partner age depending on selected date of birth.*/
function age_calculation()
{
	var gender_val = get_gender_value();

	var current_date = docF.current_date.value.split("-");

	var current_year = parseInt(current_date[0]);
	var current_month = parseInt(current_date[1]);
	var current_day = parseInt(current_date[2]);

	var yob = parseInt(docF.year.value);
	var mob = parseInt(docF.month.value);
	var dob = parseInt(docF.day.value);

	var partner_lage_array = new Array();
	var partner_hage_array = new Array();

	age = current_year - yob;
	if(mob > current_month)
		age--;
	else if(mob == current_month && dob > current_day)
		age--;

	var start = age - 20;
	if(start < 18)
		start = 18;//This is the minimum allowed age.
	
	var end = age + 20;
	if(end > 70)
		end = 70;//This is the maximum allowed age.

	docF.lage.options.length = 0;
	docF.hage.options.length = 0;

	partner_lage_array.push("<select class=\"textbox\" size=\"1\" name=\"lage\" id=\"lage\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
	partner_hage_array.push("<select class=\"textbox\" size=\"1\" name=\"hage\" id=\"hage\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");

	for(var i=start;i<=end;i++)
	{
		partner_lage_array.push("<option value=\"");
		partner_lage_array.push(i);
		partner_lage_array.push("\"");

		partner_hage_array.push("<option value=\"");
		partner_hage_array.push(i);
		partner_hage_array.push("\"");

		if(gender_val == "M")
		{
			if((age - 7) == i)
				partner_lage_array.push(" selected=\"yes\" ");
			if(age == i)
				partner_hage_array.push(" selected=\"yes\" ");
		}
		else if(gender_val == "F")
		{
			if(age == i)
				partner_lage_array.push(" selected=\"yes\" ");
			if((age + 7) == i || (i==end && (age + 7)>=end))
				partner_hage_array.push(" selected=\"yes\" ");
		}

		partner_lage_array.push(">");
		partner_lage_array.push(i);
		partner_lage_array.push("</option>");

		partner_hage_array.push(">");
		partner_hage_array.push(i);
		partner_hage_array.push("</option>");
	}
	partner_lage_array.push("</select>");
	partner_hage_array.push("</select>");

	document.getElementById("lage_span_id").innerHTML = partner_lage_array.join('');
	document.getElementById("hage_span_id").innerHTML = partner_hage_array.join('');

	var mstatus_start_year;
	var j=0;

	if(gender_val == "M")
		mstatus_start_year = (current_year - age) + 21;
	else if(gender_val == "F")
		mstatus_start_year = (current_year - age) + 18;

	var mstatus_year_opt = docF.mstatus_year;
	var mstatus_year_array = new Array();

	mstatus_year_array.push("<select class=\"textbox\" size=\"1\" name=\"mstatus_year\" onfocus=\"change_div_class(this);\">");
	mstatus_year_array.push("<option value=\"");
	mstatus_year_array.push(mstatus_year_opt.options[0].value);
	mstatus_year_array.push("\">");
	mstatus_year_array.push(mstatus_year_opt.options[0].text);
	mstatus_year_array.push("</option>");

	for(var i=mstatus_start_year;i<=current_year;i++)
	{
		mstatus_year_array.push("<option value=\"");
		mstatus_year_array.push(i);
		mstatus_year_array.push("\">");
		mstatus_year_array.push(i);
		mstatus_year_array.push("</option>");
	}
	mstatus_year_array.push("</select>");

	document.getElementById("mstatus_year_span_id").innerHTML = mstatus_year_array.join('');
}

function height_calculation()
{
	var option_text, option_val, pos;
	var gender_val = get_gender_value();
	var height_dropdown = docF.height;
	var partner_lheight_array = new Array();
	var partner_hheight_array = new Array();

//	var height_plus = parseInt(height_dropdown.value) + 5;
	var height_plus = parseInt(height_dropdown.value) + 10;
	var max_height = parseInt(height_dropdown.options[height_dropdown.options.length - 1].value);

	if(height_plus >= max_height)
		height_plus = max_height;

//	var height_minus = parseInt(height_dropdown.value) - 5;
	var height_minus = parseInt(height_dropdown.value) - 10;
	var min_height = parseInt(height_dropdown.options[1].value);
	if(height_minus <= min_height)
		height_minus = min_height;

	partner_lheight_array.push("<select class=\"textbox\" size=\"1\" name=\"lheight\" id=\"lheight\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
	partner_hheight_array.push("<select class=\"textbox\" size=\"1\" name=\"hheight\" id=\"hheight\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");

	var i1 = height_dropdown.options.length;
	//starting from 1 because 0 is Please select.
	for(i=1; i<i1; i++)
	{
		option_val = height_dropdown.options[i].value;

		partner_lheight_array.push("<option value=\"");
		partner_lheight_array.push(option_val);
		partner_lheight_array.push("\"");

		partner_hheight_array.push("<option value=\"");
		partner_hheight_array.push(option_val);
		partner_hheight_array.push("\"");

		if(gender_val)
		{
			if(gender_val == 'M')
			{
				if(option_val == height_minus)
					partner_lheight_array.push(" selected=\"yes\" ");
				if(option_val == height_dropdown.value)
					partner_hheight_array.push(" selected=\"yes\" ");
			}
			else if(gender_val == 'F')
			{
				if(option_val == height_dropdown.value)
					partner_lheight_array.push(" selected=\"yes\" ");
				if(option_val == height_plus)
					partner_hheight_array.push(" selected=\"yes\" ");
			}
		}

		partner_lheight_array.push(">");
		partner_hheight_array.push(">");

		option_text = height_dropdown.options[i].text;
		pos = option_text.indexOf('(');

		partner_lheight_array.push(option_text.substr(0,pos-1));
		partner_lheight_array.push("</option>");
		partner_hheight_array.push(option_text.substr(0,pos-1));
		partner_hheight_array.push("</option>");
	}
	partner_lheight_array.push("</select>");
	partner_hheight_array.push("</select>");

	document.getElementById("lheight_span_id").innerHTML = partner_lheight_array.join('');
	document.getElementById("hheight_span_id").innerHTML = partner_hheight_array.join('');
}

/*Function to toggle 'contact number of' dropdown depending on 'looking for' selection*/
/*
 * looking_for = 1 => Self
 * looking_for = 2 => Son
 * looking_for = 2D => Daughter
 * looking_for = 3 => Father
 * looking_for = 3D => Mother
 * looking_for = 4 => Relative/Friend
 * looking_for = 5 => Client-Marriage Bureau
 * looking_for = 6 => Brother
 * looking_for = 6D => Sister
 * contact_number_dropdowns = 1 => Bride
 * contact_number_dropdowns = 2 => Groom
 * contact_number_dropdowns = 3 => Parent
 * contact_number_dropdowns = 4 => Son
 * contact_number_dropdowns = 5 => Daughter
 * contact_number_dropdowns = 6 => Sibling
 * contact_number_dropdowns = 7 => Other
*/
function toggle_contact_number_dropdown()
{
	var looking_for,gender_value;
	var looking_for_id = document.getElementsByName("relationship");
	var i1 = looking_for_id.length;
	for(var i=0;i<i1;i++)
	{
		if(looking_for_id[i].checked==true)
			looking_for = looking_for_id[i].value;
	}

	gender_value = get_gender_value();

	if(gender_value)
	{
		var i1 = contact_number.length;
		var j1 = contact_dropdown.length;
		var new_opt, cn_name, old_opt_text, old_opt_value,k;

		for(var i=0;i<i1;i++)
		{
			k=0;
			cn_name = contact_number[i]+"_number_owner";
			//eval("docF."+cn_name+".options.length = 0");
			document.getElementById(cn_name).options.length = 0;
			for(var j=0;j<j1;j++)
			{
				old_opt_text = contact_dropdown[j];
				old_opt_value = contact_dropdown_val[j];
				if(gender_value == "M")
				{
					if(looking_for == "1")
					{
						if(old_opt_value != 1 && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "2")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value)

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for.match("2"))
					{
						if(old_opt_value != 1 && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "3")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);
;
							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for.match("3"))
					{
						if(old_opt_value != 1 && old_opt_value !=  3)
						{
							new_opt = new Option(old_opt_text,old_opt_value);
							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for == "4")
					{
						if(old_opt_value != 1 && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "7")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for == "5")
					{
						if(old_opt_value != 1 && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "7")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for.match("6"))
					{
						if(old_opt_value != 1 && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "6")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
				}
				else if(gender_value == "F")
				{
					if(looking_for == "1")
					{
						if(old_opt_value != 2  && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "1")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value)

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for.match("2"))
					{
						if(old_opt_value != 2  && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "3")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for.match("3"))
					{
						if(old_opt_value != 2  && old_opt_value != 3)
						{
							new_opt = new Option(old_opt_text,old_opt_value);
							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for == "4")
					{
						if(old_opt_value != 2  && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "7")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for == "5")
					{
						if(old_opt_value != 2  && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "7")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value)

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
					else if(looking_for.match("6"))
					{
						if(old_opt_value != 2  && old_opt_value != 4 && old_opt_value != 5)
						{
							if(old_opt_value == "6")
								new_opt = new Option(old_opt_text,old_opt_value,false,true);
							else
								new_opt = new Option(old_opt_text,old_opt_value);

							document.getElementById(cn_name).options[k] = new_opt;
							//eval("docF."+cn_name+".options[k] = new_opt");
							k++;
						}
					}
				}
			}
			fill_contact_number_name();
		}
	}
}

/*Function to fill the contact number holder's name*/
function fill_contact_number_name()
{
	var i1 = contact_number.length;
	var to_check, to_check_val, to_write, first_name, last_name, full_name;
	for(var i=0;i<i1;i++)
	{
		to_check = contact_number[i] + "_number_owner";
		//to_check_val = eval("docF."+to_check+".value");
		to_check_val = document.getElementById(to_check).value;
		to_write = contact_number[i] + "_owner_name";
		if(to_check_val == "1" || to_check_val == "2")
		{
			first_name = docF.fname_user.value;
			last_name = docF.lname_user.value;
			if(first_name && last_name)
				full_name = first_name + " " + last_name;
			else if(first_name)
				full_name = first_name;
			else if(last_name)
				full_name = last_name;
			else
				full_name = "";

			document.getElementById(to_write).value = full_name;
			//eval("docF."+to_write+".value = full_name");
		}
		else
			document.getElementById(to_write).value = '';
			//eval("docF."+to_write+".value = ''");
	}
}

/*Function to show/hide the citizenship dropdown.*/
function show_hide_citizenship()
{
	if(docF.country_residence.value != "")
	{
		var country_val = docF.country_residence.value.split("|X|");
		country_val = country_val[0].split("|}|");
		if(country_val[1] != "51")
			document.getElementById("citizenship_show_hide").style.display = 'block';
		else
			document.getElementById("citizenship_show_hide").style.display = 'none';
	}
}

function populate_partner_religion(obj)
{
	if(obj)
		var dropdown_name = obj.name;
	else
		var dropdown_name = "religion";

	var religion_val_selected = get_religion_value();

	if(dropdown_name=="religion")
	{
		var partner_religion_options = document.getElementsByName("partner_religion_arr[]");
		var i1 = partner_religion_options.length;
		for(var i=0;i<i1;i++)
		{
			partner_religion_options[i].checked = false;
			if(partner_religion_options[i].value == religion_val_selected)
				partner_religion_options[i].checked = true;
		}
		swap_checkboxes("partner_religion");
		set_initial_focus("partner_religion_displaying_arr[]");
	}
}

/*Function to show caste depending on selected religion*/
function populate_caste_from_religion(obj)
{
	if(obj)
		var dropdown_name = obj.name;
	else
		var dropdown_name = "religion";
	if(dropdown_name == 'religion')
	{
		var partner_caste_hidden_array = new Array();
		var partner_caste_actual_array = new Array();

		var religion_options = docF.religion.options;
		var religion_label = religion_options[0].text;
		var religion_value = docF.religion.value;
		var caste_selected = docF.caste_selected.value;
		docF.caste.options.length=0;

		if(religion_value)
		{
			var caste_arr = religion_value.split("|X|");
			var caste_string = caste_arr[1].split("#");
			var j=0;
			var i1 = caste_string.length;
			var caste_dropdown_array = new Array();
			var caste, caste_option;
			
			/*var doesnt_matter_for_javascript = docF.doesnt_matter_for_javascript.value;
			partner_caste_hidden_array.push("<input type=\"checkbox\" name=\"partner_caste_arr[]\" id=\"partner_caste_DM\" value=\"DM\" \/>");
			partner_caste_hidden_array.push("<label>");
			partner_caste_hidden_array.push(doesnt_matter_for_javascript);
			partner_caste_hidden_array.push("</label><br />");*/
			if(document.getElementById("mtongue").value)
				caste_dropdown_array.push("<select class=\"textbox\" size=\"1\" name=\"caste\" id=\"caste\" style=\"width:204px;\" onchange=\"get_caste_using_caste_mapping(this); show_hide_partner(this);\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
				else
				caste_dropdown_array.push("<select class=\"textbox\" size=\"1\" name=\"caste\" id=\"caste\" style=\"width:204px;\" onchange=\"check_mtongue()\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
			for(var i=-1;i<i1;i++)
			{
				if(i==-1)
				{
					caste_dropdown_array.push("<option value=\"\">");
					caste_dropdown_array.push(religion_label);
					caste_dropdown_array.push("</option>");
				}
				else
				{
					caste = caste_string[i].split("$");
					if(!((caste[0]==14)||(caste[0]==149)||(caste[0]==154)||(caste[0]==173)||(caste[0]==2)))
					{
						caste_dropdown_array.push("<option value=\"");
						caste_dropdown_array.push(caste[0]);
						caste_dropdown_array.push("\"");
//						if(caste[0] == caste_selected || (caste_arr[0]=="2" && caste[0]=="152"))
						if(caste[0] == caste_selected)
							caste_dropdown_array.push("selected=\"yes\"");

						caste_dropdown_array.push(">");
						caste_dropdown_array.push(caste[1]);
						caste_dropdown_array.push("</option>");

						partner_caste_hidden_array.push("<input type=\"checkbox\" name=\"partner_caste_arr[]\" id=\"partner_caste_");
						partner_caste_hidden_array.push(caste[0]);
						partner_caste_hidden_array.push("\" value=\"");
						partner_caste_hidden_array.push(caste[0]);
						partner_caste_hidden_array.push("\" \/>");
						partner_caste_hidden_array.push("<label id=\"partner_caste_label_");
						partner_caste_hidden_array.push(caste[0]);
						partner_caste_hidden_array.push("\">");
						partner_caste_hidden_array.push(caste[1]);
						partner_caste_hidden_array.push("</label><br />");

						partner_caste_actual_array.push("<input type=\"checkbox\" name=\"partner_caste_displaying_arr[]\" class=\"chbx checkboxalign\" id=\"partner_caste_displaying_");
						partner_caste_actual_array.push(caste[0]);
						partner_caste_actual_array.push("\" value=\"");
						partner_caste_actual_array.push(caste[0]);
						partner_caste_actual_array.push("\" \/>");
						partner_caste_actual_array.push("<label id=\"partner_caste_displaying_label_");
						partner_caste_actual_array.push(caste[0]);
						partner_caste_actual_array.push("\">");
						partner_caste_actual_array.push(caste[1]);
						partner_caste_actual_array.push("</label><br />");
					}
				}
			}

			/*var none = docF.none_for_javascript.value;
			caste_dropdown_array.push("<option value=\"");
			caste_dropdown_array.push(none);
			caste_dropdown_array.push("\">");
			caste_dropdown_array.push(none);
			caste_dropdown_array.push("</option>");
			caste_dropdown_array.push("</select>");*/

			document.getElementById("caste_dropdown").innerHTML = caste_dropdown_array.join('');
			document.getElementById("partner_caste_div").innerHTML = partner_caste_hidden_array.join('');
			document.getElementById("partner_caste_source_div").innerHTML = partner_caste_actual_array.join('');
			show_hide_partner("","caste");

			if(caste_arr[0] == 5 || caste_arr[0] == 6 || caste_arr[0] == 7 || caste_arr[0] == 8)
				document.getElementById("caste_section").style.display = 'none';
			else
			{
				document.getElementById("caste_section").style.display = 'block';
				if(0)//caste_arr[0] == 2)
				{
					//here for muslim and christian, we show _christian as earlier label was maththab
					//but now changed to denomination
					document.getElementById("caste_label_muslim").style.display = "block";
					document.getElementById("caste_label_hindu").style.display = "none";
					document.getElementById("caste_label_christian").style.display = "none";

					document.getElementById("caste_error_muslim").style.display = "inline";
					document.getElementById("caste_error_hindu").style.display = "none";
					document.getElementById("caste_error_christian").style.display = "none";

					/*document.getElementById("caste_entry_label_muslim").style.display = "block";
					document.getElementById("caste_entry_label_hindu").style.display = "none";
					document.getElementById("caste_entry_label_christian").style.display = "none";

					document.getElementById("caste_entry_error_muslim").style.display = "inline";
					document.getElementById("caste_entry_error_hindu").style.display = "none";
					document.getElementById("caste_entry_error_christian").style.display = "none";*/

					document.getElementById("partner_caste_label_muslim").style.display = "block";
					document.getElementById("partner_caste_label_hindu").style.display = "none";
					document.getElementById("partner_caste_label_christian").style.display = "none";

					document.getElementById("partner_caste_error_muslim").style.display = "inline";
					document.getElementById("partner_caste_error_hindu").style.display = "none";
					document.getElementById("partner_caste_error_christian").style.display = "none";

				}
				else if(caste_arr[0] == 3 || caste_arr[0] == 2)
				{
					document.getElementById("caste_label_christian").style.display = "block";
					document.getElementById("caste_label_muslim").style.display = "none";
					document.getElementById("caste_label_hindu").style.display = "none";

					document.getElementById("caste_error_christian").style.display = "inline";
					document.getElementById("caste_error_muslim").style.display = "none";
					document.getElementById("caste_error_hindu").style.display = "none";

					/*document.getElementById("caste_entry_label_christian").style.display = "block";
					document.getElementById("caste_entry_label_muslim").style.display = "none";
					document.getElementById("caste_entry_label_hindu").style.display = "none";

					document.getElementById("caste_entry_error_christian").style.display = "inline";
					document.getElementById("caste_entry_error_muslim").style.display = "none";
					document.getElementById("caste_entry_error_hindu").style.display = "none";*/

					document.getElementById("partner_caste_label_christian").style.display = "block";
					document.getElementById("partner_caste_label_muslim").style.display = "none";
					document.getElementById("partner_caste_label_hindu").style.display = "none";

					document.getElementById("partner_caste_error_christian").style.display = "inline";
					document.getElementById("partner_caste_error_muslim").style.display = "none";
					document.getElementById("partner_caste_error_hindu").style.display = "none";
				}
				else
				{
					document.getElementById("caste_label_hindu").style.display = "block";
					document.getElementById("caste_label_christian").style.display = "none";
					document.getElementById("caste_label_muslim").style.display = "none";

					document.getElementById("caste_error_hindu").style.display = "inline";
					document.getElementById("caste_error_christian").style.display = "none";
					document.getElementById("caste_error_muslim").style.display = "none";

					/*document.getElementById("caste_entry_label_hindu").style.display = "block";
					document.getElementById("caste_entry_label_christian").style.display = "none";
					document.getElementById("caste_entry_label_muslim").style.display = "none";

					document.getElementById("caste_entry_error_hindu").style.display = "inline";
					document.getElementById("caste_entry_error_christian").style.display = "none";
					document.getElementById("caste_entry_error_muslim").style.display = "none";*/

					document.getElementById("partner_caste_label_hindu").style.display = "block";
					document.getElementById("partner_caste_label_christian").style.display = "none";
					document.getElementById("partner_caste_label_muslim").style.display = "none";

					document.getElementById("partner_caste_error_hindu").style.display = "inline";
					document.getElementById("partner_caste_error_christian").style.display = "none";
					document.getElementById("partner_caste_error_muslim").style.display = "none";
				}
			}
			//document.getElementById("caste_entry_section").style.display = 'none';
		}
		else
			docF.caste.options[0] = new Option(religion_label,"");
	}
}

/*Function to validate the form before submitting.*/
function validate(obj,to_validate_field)
{
	var error_fields = new Array();
	var correct_fields = new Array();
	var err_i=0;
	var cor_i=0;
	var religion_val = get_religion_value();
	var to_check_array = new Array();
	var temp_name;
	var called_from_on_blur = 0;

	if(!submit_button_clicked)
	{
		if((obj && obj.name != "relationship") || to_validate_field)
		{
			//alert(to_validate_field)
			//alert(obj.name);
			temp_name = obj.name ? obj.name : to_validate_field;
			if(temp_name.indexOf("_displaying_arr[]") > 0)	
				temp_name = temp_name.substr(0,temp_name.length - 17);
			else if(temp_name.indexOf("_arr[]") > 0)
				temp_name = temp_name.substr(0,temp_name.length - 6);
			else if(temp_name.indexOf("_button") > 0)
				temp_name = temp_name.substr(0,temp_name.length - 7);
			else if(temp_name == "lage" || temp_name == "hage")
				temp_name = "partner_age";
			else if(temp_name == "lheight" || temp_name == "hheight")
				temp_name = "partner_height";

			if(in_array(temp_name,validate_fields))
				to_check_array[0] = temp_name;

			called_from_on_blur = 1;
		}
	}
	else
	{
		submit_button_clicked = 0;
		to_check_array = validate_fields;
	}

	var i1 = to_check_array.length;
	for(var i=0;i<i1;i++)
	{
		var field_name = to_check_array[i];
		if(field_name=="email")
		{
			/*if(docF.email_is_ok.value == 0)
			{
				error_fields[err_i] = "email_submit_err";
				err_i++;
			}*/
			if(""==docF.email.value)
			{
				error_fields[err_i] = "email_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "email_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "password")
		{
			var trimmed_password = trim(docF.password.value);
			if(trimmed_password == "")
			{
				error_fields[err_i] = "password_submit_err";
				err_i++;

				document.getElementById("password_error1").style.display = 'inline';
				document.getElementById("password_error2").style.display = 'none';
				document.getElementById("password_error3").style.display = 'none';
			}
			else if(trimmed_password.length < 6 || trimmed_password.length > 40)
			{
				error_fields[err_i] = "password_submit_err";
				err_i++;

				document.getElementById("password_error1").style.display = 'none';
				document.getElementById("password_error2").style.display = 'inline';
				document.getElementById("password_error3").style.display = 'none';
			}
			else if(trimmed_password != docF.password.value)
			{
				error_fields[err_i] = "password_submit_err";
				err_i++;

				document.getElementById("password_error1").style.display = 'none';
				document.getElementById("password_error2").style.display = 'none';
				document.getElementById("password_error3").style.display = 'inline';
			}
			else
			{
				correct_fields[cor_i] = "password_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "confirm_password")
		{
			var trimmed_confirm_password = trim(docF.confirm_password.value);
			if(trimmed_confirm_password == "")
			{
				error_fields[err_i] = "confirm_password_submit_err";
				err_i++;

				document.getElementById("confirm_password_error1").style.display = 'inline';
				document.getElementById("confirm_password_error2").style.display = 'none';
				document.getElementById("confirm_password_error3").style.display = 'none';
			}
			else if(trim(docF.password.value) != trimmed_confirm_password)
			{
				error_fields[err_i] = "confirm_password_submit_err";
				err_i++;

				document.getElementById("confirm_password_error1").style.display = 'none';
				document.getElementById("confirm_password_error2").style.display = 'inline';
				document.getElementById("confirm_password_error3").style.display = 'none';
			}
			else if(trimmed_confirm_password != docF.confirm_password.value)
			{
				error_fields[err_i] = "confirm_password_submit_err";
				err_i++;

				document.getElementById("confirm_password_error1").style.display = 'none';
				document.getElementById("confirm_password_error2").style.display = 'none';
				document.getElementById("confirm_password_error3").style.display = 'inline';
			}
			else
			{
				correct_fields[cor_i] = "confirm_password_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "fname_user" || field_name=="lname_user")
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
				error_fields[err_i] = "fname_lname_submit_err";
				err_i++;

				document.getElementById("fname_error1").style.display = "inline";
				document.getElementById("fname_error2").style.display = "none";
			}
			else if(fname_invalid_chars || lname_invalid_chars)
			{
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
			/*if(docF.username_is_ok.value == 0)
			{
				error_fields[err_i] = "username_submit_err";
				err_i++;
			}*/
			if(""==docF.username.value)
			{
				error_fields[err_i] = "username_submit_err";
				err_i++;
			}
			else
			{
					var username_val="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ._1234567890"
					username_str=docF.username.value
					for (i=0; i<username_str.length; i++) {
						if (username_val.indexOf(username_str.charAt(i),0) == -1)
						{
							error_fields[err_i] = "username_submit_err";
							err_i++;
							break;
						}
					}
			}
			if(error_fields[err_i]!="username_submit_err")
			{
				correct_fields[cor_i] = "username_submit_err";
				cor_i++;
			}

		}
		else if(field_name=="gender")
		{
			var gender_selected = 0;
			var gender_arr = document.getElementsByName("gender");
			var j1 = gender_arr.length;
			for(var j=0;j<j1;j++)
			{
				if(gender_arr[j].checked == true)
					gender_selected = 1;
			}
			if(!gender_selected)
			{
				error_fields[err_i] = "gender_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "gender_submit_err";
				cor_i++;

				//show partner income only for females
				if(called_from_on_blur)
					show_hide_partner("","income");
			}
		}
		else if(field_name == "day" || field_name == "month" || field_name == "year")
		{
			var gender_arr = document.getElementsByName("gender");
			var j1 = gender_arr.length;
			for(var j=0;j<j1;j++)
			{
				if(gender_arr[j].checked == true)
					gender_val_selected = gender_arr[j].value;
			}

			if(""==document.getElementById(field_name).value)
			{
				error_fields[err_i] = "day_month_year_submit_err";
				err_i++;

				document.getElementById("dob_error1").style.display = 'inline';
				document.getElementById("dob_error2").style.display = 'none';
				document.getElementById("dob_error3").style.display = 'none';
			}
			else if(docF.day.value != "" && docF.month.value != "" && docF.year.value != "")
			{
				if(gender_val_selected == "M" && age < 21)
				{
					error_fields[err_i] = "day_month_year_submit_err";
					err_i++;

					document.getElementById("dob_error1").style.display = 'none';
					document.getElementById("dob_error2").style.display = 'inline';
					document.getElementById("dob_error3").style.display = 'none';
				}
				else if(gender_val_selected == "F" && age < 18)
				{
					error_fields[err_i] = "day_month_year_submit_err";
					err_i++;

					document.getElementById("dob_error1").style.display = 'none';
					document.getElementById("dob_error2").style.display = 'none';
					document.getElementById("dob_error3").style.display = 'inline';
				}
				else
				{
					correct_fields[cor_i] = "day_month_year_submit_err";
					cor_i++;
				}
			}
			else
			{
				correct_fields[cor_i] = "day_month_year_submit_err";
				cor_i++;
			}
		}
		else if(field_name=="partner_age")
		{
			if(docF.lage.value == "" || docF.hage.value == "")
			{
				document.getElementById("partner_age_error1").style.display = 'inline';
				document.getElementById("partner_age_error2").style.display = 'none';

				error_fields[err_i] = "partner_age_submit_err";
				err_i++;
			}
			else if(parseInt(docF.lage.value) > parseInt(docF.hage.value))
			{
				document.getElementById("partner_age_error1").style.display = 'none';
				document.getElementById("partner_age_error2").style.display = 'inline';

				error_fields[err_i] = "partner_age_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "partner_age_submit_err";
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

			if(!mstatus_selected)
			{
				document.getElementById("mstatus_error1").style.display = 'inline';
				document.getElementById("mstatus_error2").style.display = 'none';
				document.getElementById("married_down_arrow").style.display = 'none';

				error_fields[err_i] = "mstatus_submit_err";
				err_i++;
			}
			else if(mstatus_value == "M" && (religion_val == "" || religion_val != "2"))
			{

				error_fields[err_i] = "mstatus_submit_err";
				err_i++;

				document.getElementById("mstatus_error1").style.display = 'none';
				document.getElementById("mstatus_error2").style.display = 'inline';
				document.getElementById("married_down_arrow").style.display = 'block';
			}
			else
			{
				document.getElementById("married_down_arrow").style.display = 'none';
				correct_fields[cor_i] = "mstatus_submit_err";
				cor_i++;
			}
		}
		else if(field_name=="partner_mstatus")
		{
			var partner_mstatus_selected = 0;
			var partner_mstatus_checkboxes = document.getElementsByName("partner_mstatus_arr[]");
			var j1 = partner_mstatus_checkboxes.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_mstatus_checkboxes[j].checked == true)
				{
					partner_mstatus_selected = 1;
					break;
				}
			}
			/*if(!partner_mstatus_selected)
			{
				error_fields[err_i] = "partner_mstatus_submit_err";
				err_i++;
			}
			else */
			{
				correct_fields[cor_i] = "partner_mstatus_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "has_children")
		{
			if(typeof(mstatus_selected_value) == "undefined")
			{
				var mstatus_arr = document.getElementsByName("mstatus");
				var j1 = mstatus_arr.length;
				for(var j=0;j<j1;j++)
				{
					if(mstatus_arr[j].checked == true)
					{
						mstatus_selected_value = mstatus_arr[j].value;
						break;
					}
				}
			}

			if(mstatus_selected_value && mstatus_selected_value != "N")
			{
				var has_children_selected = 0;
				var has_children_arr = document.getElementsByName("has_children");
				var j1 = has_children_arr.length;
				for(var j=0;j<j1;j++)
				{
					if(has_children_arr[j].checked == true)
						has_children_selected = 1;
				}
				if(!has_children_selected)
				{
					error_fields[err_i] = "has_children_submit_err";
					err_i++;
				}
				else
				{
					correct_fields[cor_i] = "has_children_submit_err";
					cor_i++;
				}
			}
			else
			{
				correct_fields[cor_i] = "has_children_submit_err";
				cor_i++;
			}
		}
		else if(field_name=="partner_height")
		{
			if(docF.lheight.value == "" || docF.hheight.value == "")
			{
				error_fields[err_i] = "partner_height_submit_err";
				err_i++;

				document.getElementById("partner_height_error1").style.display = 'inline';
				document.getElementById("partner_height_error2").style.display = 'none';
			}
			else if(parseInt(docF.lheight.value) > parseInt(docF.hheight.value))
			{
				error_fields[err_i] = "partner_height_submit_err";
				err_i++;

				document.getElementById("partner_height_error1").style.display = 'none';
				document.getElementById("partner_height_error2").style.display = 'inline';
			}
			else
			{
				correct_fields[cor_i] = "partner_height_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "citizenship")
		{
			var country_val = docF.country_residence.value.split("|X|");
			country_val = country_val[0].split("|}|");
			if("" == docF.citizenship.value && "51" != country_val[1])
			{
				error_fields[err_i] = "citizenship_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "citizenship_submit_err";
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
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;

				document.getElementById("phone_error1").style.display = 'none';
				document.getElementById("phone_error2").style.display = 'inline';
				document.getElementById("phone_error3").style.display = 'none';
				document.getElementById("contact_number_error").style.display = 'none';
				document.getElementById("contact_number_noerror").style.display = 'inline';
			}
			else if(docF.phone.value.length < 6 && docF.phone.value != "")
			{
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
					error_fields[err_i] = "phone_owner_name_submit_err";
					err_i++;

					document.getElementById("phone_owner_name_error1").style.display = 'inline';
					document.getElementById("phone_owner_name_error2").style.display = 'none';
				}
				else if(invalid_chars)
				{
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
		else if(field_name == "showphone")
		{
			var found_in_array = 0;
			var j1 = box_action_values.length; 
			for(var j=0;j<j1;j++)
			{
				if(docF.phone.value == box_action_values[j])
					found_in_array = 1;
			}

			if(!found_in_array && docF.phone.value != "" && docF.showphone.value == "")
			{
				error_fields[err_i] = "showphone_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "showphone_submit_err";
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
					error_fields[err_i] = "mobile_owner_name_submit_err";
					err_i++;

					document.getElementById("mobile_owner_name_error1").style.display = 'inline';
					document.getElementById("mobile_owner_name_error2").style.display = 'none';
				}
				else if(invalid_chars)
				{
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
		else if(field_name == "showmobile")
		{
			var found_in_array = 0 ;
			var j1 = box_action_values.length;
			for(var j=0;j<j1;j++)
			{
				if(docF.mobile.value == box_action_values[j])
					found_in_array = 1;
			}

			if(!found_in_array && docF.mobile.value != "" && docF.showmobile.value == "")
			{
				error_fields[err_i] = "showmobile_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "showmobile_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "time_to_call_start" || field_name == "start_am_pm" || field_name=="time_to_call_end" || field_name == "end_am_pm")
		{
			if((docF.start_am_pm.value == docF.end_am_pm.value && parseInt(docF.time_to_call_start.value) >= parseInt(docF.time_to_call_end.value)) || (docF.start_am_pm.value == "PM" && docF.end_am_pm.value == "AM"))
			{
				error_fields[err_i] = "time_to_call_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "time_to_call_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "religion")
		{
			if(typeof(mstatus_selected_value) == "undefined")
			{
				var mstatus_arr = document.getElementsByName("mstatus");
				var j1 = mstatus_arr.length;
				for(var j=0;j<j1;j++)
				{
					if(mstatus_arr[j].checked == true)
					{
						mstatus_selected_value = mstatus_arr[j].value;
						break;
					}
				}
			}

			if(religion_val == "")
			{
				error_fields[err_i] = "religion_submit_err";
				err_i++;
			}
			else if(religion_val != "2" && mstatus_selected_value && mstatus_selected_value == "M")
			{
				error_fields[err_i] = "mstatus_submit_err";
				err_i++;

				document.getElementById("mstatus_error1").style.display = 'none';
				document.getElementById("mstatus_error2").style.display = 'inline';
				document.getElementById("married_down_arrow").style.display = 'block';
			}
			else
			{
				correct_fields[cor_i] = "religion_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "caste")
		{
			if(religion_val == "5" || religion_val == "6" || religion_val == "7" || religion_val == "8")
			{
				correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
			}
			else
			{
				if(docF.caste.value == "")
				{
					error_fields[err_i] = field_name + "_submit_err";
					err_i++;
				}
				else
				{
					correct_fields[cor_i] = field_name + "_submit_err";
					cor_i++;
				}
			}
		}
		/*else if(field_name=="partner_degree")
		{
			var partner_education_selected = 0;
			var partner_education_checkboxes = document.getElementsByName("partner_degree_arr[]");
			var j1 = partner_education_checkboxes.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_education_checkboxes[j].checked == true)
				{
					partner_education_selected = 1;
					break;
				}
			}
			if(!partner_education_selected)
			{
				error_fields[err_i] = "partner_degree_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "partner_degree_submit_err";
				cor_i++;
			}
		}*/
		else if(field_name=="partner_income")
		{
			var partner_income_selected = 0;
			var partner_income_checkboxes = document.getElementsByName("partner_income_arr[]");
			var j1 = partner_income_checkboxes.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_income_checkboxes[j].checked == true)
				{
					partner_income_selected = 1;
					break;
				}
			}
			if(!partner_income_selected && gender_val_selected == "F")
			{
				error_fields[err_i] = "partner_income_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "partner_income_submit_err";
				cor_i++;
			}
		}
		else if(field_name=="partner_mtongue")
		{
			var partner_mtongue_selected = 0;
			var partner_mtongue_checkboxes = document.getElementsByName("partner_mtongue_arr[]");
			var j1 = partner_mtongue_checkboxes.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_mtongue_checkboxes[j].checked == true)
				{
					partner_mtongue_selected = 1;
					break;
				}
			}
			if(!partner_mtongue_selected)
			{
				error_fields[err_i] = "partner_mtongue_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "partner_mtongue_submit_err";
				cor_i++;
			}
		}
		else if(field_name=="partner_religion")
		{
			var partner_religion_selected = 0;
			var partner_religion_checkboxes = document.getElementsByName("partner_religion_arr[]");
			var j1 = partner_religion_checkboxes.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_religion_checkboxes[j].checked == true)
				{
					partner_religion_selected = 1;
					break;
				}
			}
			if(!partner_religion_selected)
			{
				error_fields[err_i] = "partner_religion_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "partner_religion_submit_err";
				cor_i++;
			}
		}
/*		else if(field_name == "caste_entry")
		{
			if(docF.caste.value >= 242 && docF.caste.value <= 246)
			{
				var allowed_chars = /^[a-zA-Z\.\s]+$/;

				if(docF.caste_entry.value == "")
				{
					error_fields[err_i] = "caste_entry_submit_err";
					err_i++;

					//document.getElementById("caste_entry_error1").style.display = 'inline';
					//document.getElementById("caste_entry_error2").style.display = 'none';
				}
				else if(!allowed_chars.test(docF.caste_entry.value))
				{
					error_fields[err_i] = "caste_entry_submit_err";
					err_i++;

					//document.getElementById("caste_entry_error1").style.display = 'none';
					//document.getElementById("caste_entry_error2").style.display = 'inline';
				}
				else
				{
					correct_fields[cor_i] = "caste_entry_submit_err";
					cor_i++;
				}
			}
			else
			{
				correct_fields[cor_i] = "caste_entry_submit_err";
				cor_i++;
			}
		}
*/
		else if(field_name=="partner_caste")
		{
			var partner_caste_selected = 0;
			var partner_caste_checkboxes = document.getElementsByName("partner_caste_arr[]");
			var j1 = partner_caste_checkboxes.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_caste_checkboxes[j].checked == true)
				{
					partner_caste_selected = 1;
					break;
				}
			}
			if(!partner_caste_selected && religion_val != "5" && religion_val != "6" && religion_val != "7" && religion_val != "8")
			{
				error_fields[err_i] = "partner_caste_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "partner_caste_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "termsandconditions")
		{
			if(docF.termsandconditions.checked==false)
			{
				error_fields[err_i] = "termsandconditions_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = "termsandconditions_submit_err";
				cor_i++;
			}
		}
		else
		{
			if(""==document.getElementById(field_name).value)
			{
				error_fields[err_i] = field_name + "_submit_err";
				err_i++;
			}
			else
			{
				correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
			}
		}
	}

	var i1 = error_fields.length;
	for(var i=0;i<i1;i++)
	{
		div_id = error_fields[i];
		document.getElementById(div_id).style.display = 'block';
	}
	var i1 = correct_fields.length;
	for(var i=0;i<i1;i++)
	{
		var div_id = correct_fields[i];
		if(!in_array(div_id, error_fields))
			document.getElementById(div_id).style.display = 'none';
	}

	if(error_fields.length == 0)
		return true;
	else
	{
		var required_index = error_fields[0].indexOf("_submit_err");
		required_field_name = error_fields[0].substring(0,required_index);
		if(required_field_name.match("fname_lname"))
			required_field_name = "fname_user";
		else if(in_array(required_field_name, partner_fields_array))
			required_field_name += "_arr[]";
		else if(required_field_name == "day_month_year")
			required_field_name = "day";
		return false;
	}
}

/*Function to show/hide  mstatus reason entering layer*/
function mstatus_details(obj,required_id,action)
{
	if(obj)
		var id = obj.id;
	else if(required_id)
		var id = required_id;

	var mstatus_selected_val = get_mstatus_value();
	var rel_val = get_religion_value();
	var mstatus_default_reason_array = new Array(docF.married_default.value,docF.awaiting_divorce_default.value,docF.divorced_default.value,docF.annulled_default.value);

	if(id == "mstatus_section")
	{
		if(mstatus_selected_val == "M")
		{
			document.getElementById("married_heading_div").style.display = 'inline';
			document.getElementById("awaiting_divorce_heading_div").style.display = 'none';
			document.getElementById("divorced_heading_div").style.display = 'none';
			document.getElementById("annulled_heading_div").style.display = 'none';

			document.getElementById("married_reason_div").style.display = 'inline';
			document.getElementById("awaiting_divorce_reason_div").style.display = 'none';
			document.getElementById("divorced_reason_div").style.display = 'none';
			document.getElementById("annulled_reason_div").style.display = 'none';
			document.getElementById("edit_await_divorce_details").style.display = "none";

			if(docF.mstatus_reason.value == "" || in_array(docF.mstatus_reason.value,mstatus_default_reason_array))
				docF.mstatus_reason.value = docF.married_default.value
				
			if(rel_val == "" || rel_val != "2")
			{
				document.getElementById("mstatus_submit_err").style.display = 'block';
				document.getElementById("mstatus_error1").style.display = 'none';
				document.getElementById("mstatus_error2").style.display = 'inline';
				document.getElementById("married_down_arrow").style.display = 'block';
			}
		}
		else if(mstatus_selected_val == "S")
		{
			document.getElementById("married_heading_div").style.display = 'none';
			document.getElementById("awaiting_divorce_heading_div").style.display = 'inline';
			document.getElementById("divorced_heading_div").style.display = 'none';
			document.getElementById("annulled_heading_div").style.display = 'none';

			document.getElementById("married_reason_div").style.display = 'none';
			document.getElementById("awaiting_divorce_reason_div").style.display = 'inline';
			document.getElementById("divorced_reason_div").style.display = 'none';
			document.getElementById("annulled_reason_div").style.display = 'none';
			document.getElementById("edit_await_divorce_details").style.display = "none";

			if(docF.mstatus_reason.value == "" || in_array(docF.mstatus_reason.value,mstatus_default_reason_array))
				docF.mstatus_reason.value = docF.awaiting_divorce_default.value
		}
		/*else if(mstatus_selected_val == "D")
		{
			document.getElementById("married_heading_div").style.display = 'none';
			document.getElementById("awaiting_divorce_heading_div").style.display = 'none';
			document.getElementById("divorced_heading_div").style.display = 'inline';
			document.getElementById("annulled_heading_div").style.display = 'none';

			document.getElementById("divorced_court_div").style.display = 'inline';
			document.getElementById("divorced_date_div").style.display = 'inline';
			document.getElementById("annulled_court_div").style.display = 'none';
			document.getElementById("annulled_date_div").style.display = 'none';

			document.getElementById("married_reason_div").style.display = 'none';
			document.getElementById("awaiting_divorce_reason_div").style.display = 'none';
			document.getElementById("divorced_reason_div").style.display = 'inline';
			document.getElementById("annulled_reason_div").style.display = 'none';

			if(docF.mstatus_reason.value == "" || in_array(docF.mstatus_reason.value,mstatus_default_reason_array))
				docF.mstatus_reason.value = docF.divorced_default.value
		}*/
		else if(mstatus_selected_val == "A")
		{
			document.getElementById("married_heading_div").style.display = 'none';
			document.getElementById("awaiting_divorce_heading_div").style.display = 'none';
			document.getElementById("divorced_heading_div").style.display = 'none';
			document.getElementById("annulled_heading_div").style.display = 'inline';

			document.getElementById("divorced_court_div").style.display = 'none';
			document.getElementById("divorced_date_div").style.display = 'none';
			document.getElementById("annulled_court_div").style.display = 'inline';
			document.getElementById("annulled_date_div").style.display = 'inline';

			document.getElementById("married_reason_div").style.display = 'none';
			document.getElementById("awaiting_divorce_reason_div").style.display = 'none';
			document.getElementById("divorced_reason_div").style.display = 'none';
			document.getElementById("annulled_reason_div").style.display = 'inline';

			//document.getElementById("edit_await_divorce_details").style.display = "none";

/*			if(docF.mstatus_reason.value == "" || in_array(docF.mstatus_reason.value,mstatus_default_reason_array))
				docF.mstatus_reason.value = docF.annulled_default.value*/
		}
		

		document.getElementById("mstatus_details").style.display = 'none';
		document.getElementById("mstatus_details_error_img").style.display = 'none';
		document.getElementById("mstatus_details_box").className = 'graybox';
		document.getElementById("court_div").style.display = 'none';
		document.getElementById("mstatus_date_div").style.display = 'none';
		document.getElementById("annulled_down_arrow").style.display = 'none';
		document.getElementById("divorced_down_arrow").style.display = 'none';
		document.getElementById("married_down_arrow").style.display = 'none';
		document.getElementById("awaiting_divorce_down_arrow").style.display = 'none';
//		document.getElementById("edit_await_divorce_details").style.display = "none";

//		if(mstatus_selected_val == "A" || mstatus_selected_val == "D")
		if(mstatus_selected_val == "A")
		{
			document.getElementById("mstatus_details_layer").style.display = 'block';
			if(mstatus_selected_val == "A")
				document.getElementById("annulled_down_arrow").style.display = 'block';
			else if(mstatus_selected_val == "D")
				document.getElementById("divorced_down_arrow").style.display = 'block';

			document.getElementById("fill_mstatus_details").style.display = 'block';
			document.getElementById("court_div").style.display = 'block';
			document.getElementById("mstatus_date_div").style.display = 'block';
			document.getElementById("partner_mstatus").style.display = 'none';
		}
		else if(mstatus_selected_val == "S" || mstatus_selected_val == "M")
		{
				document.getElementById("mstatus_details_layer").style.display = 'block';
				if(mstatus_selected_val == "S")
					document.getElementById("awaiting_divorce_down_arrow").style.display = 'block';
				else if(mstatus_selected_val == "M")
					document.getElementById("married_down_arrow").style.display = 'block';

				document.getElementById("fill_mstatus_details").style.display = 'block';
				document.getElementById("court_div").style.display = 'none';
				document.getElementById("mstatus_date_div").style.display = 'none';
				document.getElementById("partner_mstatus").style.display = 'none';
		}
		else
		{
			document.getElementById("mstatus_details_layer").style.display = 'none';
			document.getElementById("partner_mstatus").style.display = 'block';
		}
	}
	else if(id == "mstatus_details_submit")
	{
//		if(mstatus_selected_val == "D" || mstatus_selected_val == "A")
		if(mstatus_selected_val == "S")
		{
			document.getElementById("edit_await_divorce_details").style.display = "inline";
		}
		if(mstatus_selected_val == "A")
		{
			if(trim(docF.court.value) == "" || docF.mstatus_day.value == "" || docF.mstatus_month.value == "" || docF.mstatus_year.value=="")
			{
				document.getElementById("mstatus_details_error_img").style.display="inline";
				document.getElementById("mstatus_details_box").className="redbox";
				document.getElementById("partner_mstatus").style.display = 'none';
			}
			else
			{
				document.getElementById("mstatus_details_error_img").style.display="none";
				document.getElementById("mstatus_details_box").className="graybox";

				document.getElementById("fill_mstatus_details").style.display = 'none';
				document.getElementById("married_down_arrow").style.display = 'none';
				document.getElementById("awaiting_divorce_down_arrow").style.display = 'none';
				document.getElementById("divorced_down_arrow").style.display = 'none';
				document.getElementById("annulled_down_arrow").style.display = 'none';

				document.getElementById("edit_mstatus_details").style.display = 'block';

				var to_write_mstatus_details = new Array();

				/*if(mstatus_selected_val == "D")
				{
					to_write_mstatus_details.push("\"");
					to_write_mstatus_details.push(docF.divorced_by.value);
					to_write_mstatus_details.push(" ");
				}
				else if(mstatus_selected_val == "A")*/
				{
					to_write_mstatus_details.push("\"");
					to_write_mstatus_details.push(docF.annulled_by.value);
					to_write_mstatus_details.push(" ");
				}

				to_write_mstatus_details.push(docF.court.value);
				to_write_mstatus_details.push(" ");
				to_write_mstatus_details.push(docF.on.value);
				to_write_mstatus_details.push(" ");
				to_write_mstatus_details.push(docF.mstatus_day.value);
				to_write_mstatus_details.push("/");
				to_write_mstatus_details.push(docF.mstatus_month.value);
				to_write_mstatus_details.push("/");
				to_write_mstatus_details.push(docF.mstatus_year.value);
				to_write_mstatus_details.push("\" ");
				to_write_mstatus_details.push("<a href=\"\" id=\"mstatus_details_edit_link\">");
				to_write_mstatus_details.push(docF.edit.value);
				to_write_mstatus_details.push("</a>");

				document.getElementById("mstatus_details").innerHTML = to_write_mstatus_details.join('');
				document.getElementById("mstatus_details").style.display = 'block';

				document.getElementById("partner_mstatus").style.display = 'block';

				Behaviour.apply("mstatus_details_layer");
			}
		}
		else
		{
			if(trim(docF.mstatus_reason.value) == "" || in_array(trim(docF.mstatus_reason.value), mstatus_default_reason_array))
			{
				document.getElementById("mstatus_details_error_img").style.display="inline";
				document.getElementById("mstatus_details_box").className="redbox";
				document.getElementById("partner_mstatus").style.display = 'none';
				document.getElementById("edit_married").style.display = 'none';
				document.getElementById("edit_ad").style.display = 'none';
			}
			else
			{
				document.getElementById("edit_ad").style.display = 'inline';
				document.getElementById("fill_mstatus_details").style.display = 'none';
				if(mstatus_selected_val == "S")
					document.getElementById("awaiting_divorce_down_arrow").style.display = 'none';

				document.getElementById("partner_mstatus").style.display = 'block';
			}
		}
	}
	else if(id=="mstatus_details_edit_link")
	{
		if(mstatus_selected_val == "A")
			document.getElementById("annulled_down_arrow").style.display = 'block';
		/*else if(mstatus_selected_val == "D")
			document.getElementById("divorced_down_arrow").style.display = 'block';*/

		document.getElementById("fill_mstatus_details").style.display = 'block';
		document.getElementById("court_div").style.display = 'block';
		document.getElementById("mstatus_date_div").style.display = 'block';
		document.getElementById("edit_mstatus_details").style.display = 'none';
		document.getElementById("partner_mstatus").style.display = 'none';
	}
	else if(id == "mstatus_reason")
	{
		if(action == "clear")
		{
			if(in_array(docF.mstatus_reason.value,mstatus_default_reason_array))
				docF.mstatus_reason.value = "";
			document.getElementById("mstatus_reason").style.color ='#000000';
		}
		else if(action == "fill")
		{
			document.getElementById("edit_ad").style.display = 'none';
			document.getElementById("edit_married").style.display = 'inline';
			if(trim(docF.mstatus_reason.value) == "")
			{
				document.getElementById("edit_married").style.display = 'none';
				document.getElementById("mstatus_reason").style.color ='#989491';
				if(mstatus_selected_val == "M")
					docF.mstatus_reason.value = docF.married_default.value;
				else if(mstatus_selected_val == "S")
					docF.mstatus_reason.value = docF.awaiting_divorce_default.value;
				/*else if(mstatus_selected_val == "D")
					docF.mstatus_reason.value = docF.divorced_default.value;
				else if(mstatus_selected_val == "A")
					docF.mstatus_reason.value = docF.annulled_default.value;*/
			}
		}
	}
	
	if(mstatus_selected_val != "M" && (rel_val == "" || rel_val != "2"))
	{
		document.getElementById("mstatus_submit_err").style.display = 'none';
		document.getElementById("mstatus_error1").style.display = 'none';
		document.getElementById("mstatus_error2").style.display = 'none';
		document.getElementById("married_down_arrow").style.display = 'none';
	}
}

function edit_mstatus()
{
	document.getElementById("fill_mstatus_details").style.display = 'block';	
	document.getElementById("partner_mstatus").style.display = 'none';	
}

/*Function to populate partner mtongue depending on the selection of his/her mtongue*/
function populate_partner_mtongue_logical(obj)
{
	if(obj)
		var dropdown_name = obj.name;
	else
		var dropdown_name = "mtongue";

	if(dropdown_name=="mtongue")
	{
		var checked_mtongue_array = new Array();
		var j=0;

		var mtongue_options = docF.mtongue.options;
		var mtongue_value = docF.mtongue.value;
		var all_hindi_arr = new Array("7","10","13","19","28","33");

		var i1 = mtongue_options.length;
		for(var i=0;i<i1;i++)
		{
			if(mtongue_options[i].value != "")
			{
				if((in_array(mtongue_value, all_hindi_arr) && in_array(mtongue_options[i].value,all_hindi_arr)) || mtongue_options[i].value == mtongue_value)
				{
					checked_mtongue_array[j] = mtongue_options[i].value;
					j++;
				}
			}
		}

		var partner_mtongue_checkboxes = document.getElementsByName("partner_mtongue_arr[]");
		var i1 = partner_mtongue_checkboxes.length;
		for(var i=0;i<i1;i++)
		{
			partner_mtongue_checkboxes[i].checked = false;
			var j1 = checked_mtongue_array.length;
			for(var j=0;j<j1;j++)
			{
				if(partner_mtongue_checkboxes[i].value == checked_mtongue_array[j])
					partner_mtongue_checkboxes[i].checked = true;
			}
		}

		swap_checkboxes("partner_mtongue");
		set_initial_focus("partner_mtongue_displaying_arr[]");
	}
}

/*Function to populate city depending on the selected country*/
function populate_city(city_residence_selected)
{
	if(docF.country_residence.value != "")
	{
		var city_value,city_label;
		var city_arr = new Array();
		var pop_city_array = new Array();

		var country_drop = docF.country_residence;

		var country_val_arr = country_drop.value.split("|X|");
		var city_label_value_arr = country_val_arr[1].split("#");
		var j=1;
		var i1 = city_label_value_arr.length;

		pop_city_array.push("<select class=\"textbox\" size=\"1\" name=\"city_residence\" id=\"city_residence\" style=\"width:204px\" onchange=\"fetch_code('CITY',this.value);\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
		pop_city_array.push("<option value=\"");
		pop_city_array.push(country_drop.options[0].value);
		pop_city_array.push("\">");
		pop_city_array.push(country_drop.options[0].text);
		pop_city_array.push("</option>");

		for(var i=0;i<i1;i++)
		{
			city_arr = city_label_value_arr[i].split("$");
			city_value = city_arr[0];
			city_label = city_arr[1];

			pop_city_array.push("<option value=\"");
			pop_city_array.push(city_value);
			pop_city_array.push("\"");

			if(city_value == city_residence_selected)
				pop_city_array.push("selected=\"yes\"");

			pop_city_array.push(">");
			pop_city_array.push(city_label);
			pop_city_array.push("</option>");
		}
		pop_city_array.push("</select>");

		document.getElementById("city_india_visible").innerHTML = pop_city_array.join('');
	}
}

/*Function to fetch the code depending on city/country*/
function fetch_code(code_for,value)
{
	if(code_for == "COUNTRY")
	{
		var country_code_arr = value.split("|}|");
		docF.country_code.value = country_code_arr[0];
		docF.country_code_mob.value = country_code_arr[0];
	}
	else if(code_for == "CITY")
	{
		var city_code_arr = value.split("|{|");
		docF.state_code.value = city_code_arr[0];
	}
}

function populate_yob()
{
	var gender_val = get_gender_value();
	var current_date = docF.current_date.value.split("-");
	var current_year = parseInt(current_date[0]);
	var start_year = current_year - 70;
	var year_dropdown = docF.year;
	var selected_yob = year_dropdown.value;
	var dob_year_array = new Array();

	if(gender_val == "M")
		var end_year = current_year - 21;
	else if(gender_val == "F")
		var end_year = current_year - 18;

	dob_year_array.push("<select class=\"textbox\" size=\"1\" name=\"year\" id=\"year\" style=\"width:60px\" onfocus=\"show_help(this); change_div_class(this);\" onchange=\"show_hide_partner(this);\" onblur=\"hide_help(this); validate(this);\">");
	dob_year_array.push("<option value=\"");
	dob_year_array.push(year_dropdown.options[0].value);
	dob_year_array.push("\">");
	dob_year_array.push(year_dropdown.options[0].text);
	dob_year_array.push("</option>");

	for(var i=end_year;i>=start_year;i--)
	{
		dob_year_array.push("<option value=\"");
		dob_year_array.push(i);
		dob_year_array.push("\"");

		if(i==selected_yob)
			dob_year_array.push(" selected=\"yes\" ");

		dob_year_array.push(">");
		dob_year_array.push(i);
		dob_year_array.push("</option>");
	}
	dob_year_array.push("</select>");

	document.getElementById("year_span_id").innerHTML = dob_year_array.join('');
}
