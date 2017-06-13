/*Defining some global variables*/

//defining variable for document.form
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
var email_section_arr=new Array("email","retrieve_profile_link","forgot_password_link","password");
var basicInfo_section_arr = new Array("gender","day","month","year","mstatus","has_children","height","country_residence","city_residence","country_code","state_code","pincode","phone","showphone","country_code_mob","mobile","showmobile","occupation","education","income");
var religionEthnicity_section_arr = new Array("mtongue","religion","caste");
var educationCareer_section_arr = new Array('');
//defining arrays, used to change section tabs depending on "Looking for " selection.
var relation_arr = new Array('self','friend','son','daughter','brother','sister','father','mother','marriageBureau');
var label_arr = new Array('_basicInfo','_religionEthnicity');
var div_sections = new Array("email_section","basicInfo_section","religionEthnicity_section");

//array for contact number dropdown.
var contact_number = new Array("phone","mobile");
var contact_dropdown = new Array('Bride','Groom','Parent','Son','Daughter','Sibling','Other');
var contact_dropdown_val = new Array('1','2','3','4','5','6','7');

var anurag="";
var registration={
	'input' : function(element){
//		alert(record_id);
			element.onclick = function(){
				for_radio(this);
			}
			element.onfocus = function(){
				if(false){
					change_div_class(this);
					show_help(this);
					box_action(this,'clear');
				}
			}
			element.onblur=function(){
				hide_help(this);
				box_action(this,'fill');
				//ajaxValidation(this.name,this.value);
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

			}
		},

	'textarea' : function(element){
				element.onblur=function(){
					validate(this);
				}
		},

	'a' : function(element){
			if(!element.onclick)
			element.onclick = function(){
				if(this.id != "live_help")
				{
					var current_id = this.id
					//mstatus_details(this);
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
			element.onchange = function(){
            var record_id=document.getElementById("record_id").value;
            var edit_option=document.getElementById("Edit").value;
//				display_rest_of_page();
				if(record_id!='' && edit_option==''){
				}else{
				change_tab_labels();
				gender_display_selection();
				toggle_contact_number_dropdown();
				}
			}
			element.onkeydown = function(){
			}
			element.onkeypress = function(){
			}
		},

	'#email' : function(element){
			element.onkeydown = function(){
				docF.email_is_ok.value = "1";
				/*
				if(keycode == "9" && dID("gray_layer").style.display == "block")
					return false;
				*/
			}
			element.onkeypress = function(){
				//keycode=9 for tab, to disallow tabbing when the page is grayed out
				/*
				if(keycode == "9" && dID("gray_layer").style.display == "block")
					return false;
				*/
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

	'#country_residence' : function(element){
			element.onchange = function(){
				fetch_code("COUNTRY",docF.country_residence.value);
				document.getElementById('state_code').value="";
				populate_city();
				callPincode();
			}
		},

	'#city_residence' : function(element){
			element.onchange = function(){
				fetch_code("CITY",docF.city_residence.value);
				if(document.getElementById("pincode").value)
					CheckPincode();
			}
		},
	'#pincode': function(element)
		{
			element.onblur=function(){
				CheckPincode();
			}
		},
	'#phone' : function(element){
			element.onblur = function(){
				check_contact_number("PHONE",this.value);
				//box_action(this,'fill');
				validate(this);
			}
		},

	'#mobile' : function(element){
			element.onblur = function(){
				check_contact_number("MOBILE",this.value);
				//box_action(this,'fill');
				validate(this);
			}
		},

	'#contact_option' : function(element){
			element.onclick = function(){
				change_contact();
			}
		},

	'#mtongue' : function(element){
			element.onchange = function(){
				get_caste_using_caste_mapping();
				}
		},

	'#religion' : function(element){
			element.onchange = function(){
//				show_hide_partner(this);
//				populate_partner_religion(this);
				populate_caste_from_religion(this);
                                showHideJamaat();
                                showHideCasteMuslim();
			}
		},

	'#caste' : function(element){
			element.onchange = function(){
				get_caste_using_caste_mapping(this);
                                showHideJamaat();
//				show_hide_partner(this);
			}
		},
	'#income' : function(element){
			element.onchange = function(){
				validate(this);
			}
		},
	'#occupation' : function(element){
			element.onchange = function(){
				validate(this);
			}
		},
	'#degree' : function(element){
			element.onchange = function(){
				validate(this);
			}
		},
	'#submit_pg1' : function(element){
			element.onclick = function(){
				anurag=""
				submit_button_clicked=1;
				if(!validate())
				{
					get();
					logerror_on_submit(2);
					var temp_arr = document.getElementsByName(required_field_name);
					if(temp_arr[0].type == "radio" || temp_arr[0].type == "checkbox")
						temp_arr[0].focus();
					else
						dID(required_field_name).focus();
						//eval("docF." + required_field_name + ".focus()");
				
					return false;
				}
				logerror_on_submit(1);
			}
		}
};
Behaviour.register(registration);
Behaviour.addLoadEvent(onload_events);

function change_contact(){

	for (i=0;i<document.form1.contact_option.length;i++)
	{
	      if (document.form1.contact_option[i].checked)
	      {
	  	    val_mob = document.form1.contact_option[i].value;
	      }
	}
	
	if(val_mob=='M')
	{
		document.getElementById('mobile_show').style.display='block';
		document.getElementById('phone_show').style.display='none';
		document.getElementById('phone').value='';
	}
	else if(val_mob=='L')
	{
		document.getElementById('phone_show').style.display='block';
		document.getElementById('mobile_show').style.display='none';
		document.getElementById('mobile').value='';
	}
}

function onload_events()
{
	var email_1=document.getElementById('email').value;
	var pass_1=document.getElementById('password');
	var from_sugar=document.getElementById('fromsugar');
	if(email_1!='' && !from_sugar)
	{
	      pass_1.focus();
	}
	display_rest_of_page("onload");
}

// Tracking Code starts Here

var httprequest=false
function createAjaxObj(url,parameters)
{
        httprequest=false
        if (window.XMLHttpRequest)
        { // if Mozilla, Safari etc
                httprequest=new XMLHttpRequest()
                if (httprequest.overrideMimeType)
                        httprequest.overrideMimeType('text/html')
        }
        else if (window.ActiveXObject)
        { // if IE
                try 
                {
                        httprequest=new ActiveXObject("Msxml2.XMLHTTP");
                } 
                catch (e)
                {
                        try
                        {
                                httprequest=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e){}
                }
        }
        if (!httprequest) 
        {
                 alert('Cannot create XMLHTTP instance');
                return false;
        }

        httprequest.onreadystatechange = alertContents;
        httprequest.open('POST', url, true);
        httprequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httprequest.setRequestHeader("Content-length", parameters.length);
        httprequest.setRequestHeader("Connection", "close");
        httprequest.send(parameters);

}
function alertContents() 
{
        if (httprequest.readyState == 4) 
        {
                if (httprequest.status == 200) 
                {
                        result = httprequest.responseText;              
                } 
                else 
                {
                        alert('There was a problem with the request.');
                }
         }
}
function get() 
{
      var poststr ="field="+anurag;
      anurag="";
	if(docF)
	{
		var sub_url=docF.site_url.value+"/profile/submit_hit_try.php";
	}
      createAjaxObj(sub_url, poststr);
}

// Trackign Ends Here

//function to disable right click in IE//
function disable_rightclick(e)
{
        if(navigator.appName == 'Netscape' && (e.which == 3 || e.which == 2))
		return false;
	else if(navigator.appName == 'Microsoft Internet Explorer' && (event.button == 2 || event.button == 3))
		return false;
	return true;
}

/*Function to display rest of page, i.e. hiding the gray layer*/
function display_rest_of_page(called_when)
{
	docF = document.form1;
	var enable_page=0;
	var relationship_arr = document.getElementById("relationship");
	/*var i1 = relationship_arr.length;
	for(var i=0;i<i1; i++)
	{
		if(relationship_arr[i].checked == true)
		{
			enable_page = 1;
			break;
		}
		else
			enable_page = 0;
	}*/

	if(called_when == "onload" )
	{
		var record_id=document.getElementById("record_id").value;
		var edit_opt=document.getElementById("Edit").value;
		if(!record_id||edit_opt){
		change_tab_labels();
		gender_display_selection();
	}
//		show_hide_partner();
        var caste_sugar=document.getElementById("caste_sugar").value;
		if(caste_sugar==null){
		populate_caste_from_religion();
		get_caste_using_caste_mapping();
		}
		var city_res=document.getElementById("sugar_city_res");
		if(city_res ==null){
		populate_city(docF.city_residence_selected.value);
		fetch_code("COUNTRY",docF.country_residence.value);
		}
//		populate_partner_mtongue_logical();
//		populate_partner_religion();
//		fill_details(partner_fields_array);
	}
}

/*Function to change tab labels, depending on Looking for selecetion*/
function change_tab_labels()
{
	var i,j,selected_val;
	var relationship_arr = document.getElementById("relationship").value;
	selected_val=relationship_arr;

	/*var i1 = relationship_arr.length;
	var i1 = 7;
	for(i=0;i<i1;i++)
	{
		if(relationship_arr[i].checked==true)
			selected_val = relationship_arr[i].value;
	}*/
	
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
				if(dID(div_id))
				{
					if(relation_arr[i] == label)
						dID(div_id).style.display = 'block';
					else
						dID(div_id).style.display = 'none';
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
				dID("gender_section").style.display='none';
				dID("gender_padding").style.padding='0';
				break;
			}
		}

		/* Showing the Photo of Female*/

		/* End of the Section */
		
		/* Showing the Married option for the Male */
		
		dID("mstatus_married_field").style.display='inline';
		//dID("married_down_arrow").style.display='inline';
		//dID("fill_mstatus_details").style.display='inline';
		//dID("mstatus_error2").style.display='inline';
		
		var mstatus_selected_val = get_mstatus_value();
		
		if(mstatus_selected_val!='N' && mstatus_selected_val !=undefined)
			dID("have_child_section").style.display='inline';

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
				dID("gender_section").style.display='none';
				dID("gender_padding").style.padding='0';
				break;
			}
		}

		/* Start of the Section Male Photo Display Section */

		/* End of the Section*/

		/*for(var i=0;i<a;i++)
		{
			if(document.getElementsByName("partner_image_female")[i])
			{
				document.getElementsByName("partner_image_female")[i].style.display = 'none';
				document.getElementsByName("partner_image_male")[i].style.display = 'block';
			}
		}*/

		dID("gender_section").style.display='none';
		/* Hiding the Married option for the Female */

		dID("mstatus_married_field").style.display='none';
		document.form1.mstatus[5].checked=false;
	
		var mstatus_selected_val = get_mstatus_value();
		if(mstatus_selected_val=='N')
		{
			dID("have_child_section").style.display='none';
			document.form1.has_children[0].checked=false;
			document.form1.has_children[1].checked=false;
			document.form1.has_children[2].checked=false;
		}
		/* End of the Section */
	}
	else
	{
		dID("gender_section").style.display='block';
		dID("gender_padding").style.padding='';
	}

	action = "";
}

/*Function to change div class i.e to change the background color depending on focus*/
function change_div_class(obj)
{
/*	if(in_array(obj.name,email_section_arr))
	{
		dID("email_section").className="y_bdr mt_15 fl b p5";
		dID("basicInfo_section").className="y_bdr mt_15 fl b p5";
		dID("educationCareer_section").className="y_bdr mt_15 fl b p5";
		dID("religionEthnicity_section").className="y_bdr mt_15 fl b p5";
	}
	else if(in_array(obj.name,basicInfo_section_arr))
	{
		dID("email_section").className="y_bdr mt_15 fl b p5";
		dID("basicInfo_section").className="y_bdr mt_15 fl b p5";
		dID("educationCareer_section").className="y_bdr mt_15 fl b p5";
		dID("religionEthnicity_section").className="y_bdr mt_15 fl b p5";

	}
	else if(in_array(obj.name,educationCareer_section_arr))
	{
		dID("email_section").className="y_bdr mt_15 fl b p5";
		dID("basicInfo_section").className="y_bdr mt_15 fl b p5";
		dID("educationCareer_section").className="y_bdr mt_15 fl b p5";
		dID("religionEthnicity_section").className="y_bdr mt_15 fl b p5";
	}
	else if(in_array(obj.name,religionEthnicity_section_arr))
	{
		dID("email_section").className="y_bdr mt_15 fl b p5";
		dID("basicInfo_section").className="y_bdr mt_15 fl b p5";
		dID("educationCareer_section").className="y_bdr mt_15 fl b p5";
		dID("religionEthnicity_section").className="y_bdr mt_15 fl b p5";
	}*/
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

}

function height_calculation()
{
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
}

/*Function to fill the contact number holder's name*/
function fill_contact_number_name()
{
}

function populate_partner_religion(obj)
{

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

		var religion_options = docF.religion.options;
		var religion_label = religion_options[0].text;
		var religion_value = docF.religion.value;
		var caste_selected = docF.caste_selected.value;
		docF.caste.options.length=0;

		if(religion_value)
		{
			var caste_arr = religion_value.split("|X|");
			var caste_string = caste_arr[1].split("#");
			var caste_string_religion = caste_arr[0].split("#");
			var j=0;
			var i1 = caste_string.length;
			var caste_dropdown_array = new Array();
			var caste, caste_option;

			/*if(caste_string_religion=='2')
				dID("speak_urdu_id").style.display = 'block';
			else
				dID("speak_urdu_id").style.display = 'none';
			*/

			if(dID("mtongue").value)
				caste_dropdown_array.push("<select class=\"sel1 fl\" size=\"1\" name=\"caste\" id=\"caste\" style=\"width:204px;\" onchange=\"get_caste_using_caste_mapping(this); show_hide_partner(this); showHideJamaat();\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
				else
				caste_dropdown_array.push("<select class=\"sel1 fl\" size=\"1\" name=\"caste\" id=\"caste\" style=\"width:204px;\" onchange=\"check_mtongue(); showHideJamaat()\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
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

			dID("caste_dropdown").innerHTML = caste_dropdown_array.join('');

			if(caste_arr[0] == 5 || caste_arr[0] == 6 || caste_arr[0] == 7 || caste_arr[0] == 8 || caste_arr[0] == 10)
			{
				dID("caste_section").style.display = 'none';
				dID("caste_submit_err").style.display='none';
			}
			else
			{
				dID("caste_section").style.display = 'block';
				if(0)//caste_arr[0] == 2)
				{
					//here for muslim and christian, we show _christian as earlier label was maththab
					//but now changed to denomination
					dID("caste_label_muslim").style.display = "block";
					dID("caste_label_hindu").style.display = "none";
					dID("caste_label_christian").style.display = "none";

					dID("caste_error_muslim").style.display = "inline";
					dID("caste_error_hindu").style.display = "none";
					dID("caste_error_christian").style.display = "none";

				


				}
				else if(caste_arr[0] == 3 || caste_arr[0] == 2)
				{
					dID("caste_label_christian").style.display = "block";
					dID("caste_label_muslim").style.display = "none";
					dID("caste_label_hindu").style.display = "none";

					dID("caste_error_christian").style.display = "inline";
					dID("caste_error_muslim").style.display = "none";
					dID("caste_error_hindu").style.display = "none";

				

				}
				else
				{
					dID("caste_label_hindu").style.display = "block";
					dID("caste_label_christian").style.display = "none";
					dID("caste_label_muslim").style.display = "none";

					dID("caste_error_hindu").style.display = "inline";
					dID("caste_error_christian").style.display = "none";
					dID("caste_error_muslim").style.display = "none";

					
				}
			}
			
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
	var country_val = docF.country_residence.value.split("|X|");
	country_val = country_val[0].split("|}|");

	if(!submit_button_clicked)
	{
		if((obj && obj.name != "") || to_validate_field)
		{
		//	alert(to_validate_field)
		//	alert(obj.name);
			temp_name = obj.name ? obj.name : to_validate_field;
			if(temp_name.indexOf("_displaying_arr[]") > 0)	
				temp_name = temp_name.substr(0,temp_name.length - 17);
			else if(temp_name.indexOf("_arr[]") > 0)
				temp_name = temp_name.substr(0,temp_name.length - 6);
			else if(temp_name.indexOf("_button") > 0)
				temp_name = temp_name.substr(0,temp_name.length - 7);
			

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
		//console.log(field_name+"   ");
		if(field_name=="email")
		{
			var email_act=docF.email.value;
			if(!email_check(email_act))
			{
				error_fields[err_i] = "email_submit_err";
				err_i++;
			}
			else
			{
				dID("phpEmailError").style.display='none';
				correct_fields[cor_i] = "email_submit_err";
				cor_i++;
			}
		}
		else if(field_name == "password")
		{
			var from_sugar_exec=docF.from_sugar_exec.value;
			//alert(from_sugar_exec);
			if(from_sugar_exec!='Y'){
				var trimmed_password = trim(docF.password.value);
				if(trimmed_password == "")
				{
					error_fields[err_i] = "password_submit_err";
					err_i++;

					dID("password_error1").style.display = 'inline';
					dID("password_error2").style.display = 'none';
					dID("password_error3").style.display = 'none';
				}
				else if(trimmed_password.length < 6 || trimmed_password.length > 40)
				{
					error_fields[err_i] = "password_submit_err";
					err_i++;

					dID("password_error1").style.display = 'none';
					dID("password_error2").style.display = 'inline';
					dID("password_error3").style.display = 'none';
				}
				else if(trimmed_password != docF.password.value)
				{
					error_fields[err_i] = "password_submit_err";
					err_i++;

					dID("password_error1").style.display = 'none';
					dID("password_error2").style.display = 'none';
					dID("password_error3").style.display = 'inline';
				}
				else
				{
					dID("password_error1").style.display = 'none';
									dID("password_error2").style.display = 'none';
					dID("password_error3").style.display = 'none';
					correct_fields[cor_i] = "password_submit_err";
					cor_i++;
				}
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
			age_calculation();
			var j1 = gender_arr.length;
			for(var j=0;j<j1;j++)
			{
				if(gender_arr[j].checked == true)
					gender_val_selected = gender_arr[j].value;
			}

			if(""==dID(field_name).value)
			{
				error_fields[err_i] = "day_month_year_submit_err";
				err_i++;

				dID("dob_error1").style.display = 'inline';
				dID("dob_error2").style.display = 'none';
				dID("dob_error3").style.display = 'none';
			}
			else if(docF.day.value != "" && docF.month.value != "" && docF.year.value != "")
			{
				
				if(gender_val_selected == "M" && age < 21)
				{
					error_fields[err_i] = "day_month_year_submit_err";
					err_i++;

					dID("dob_error1").style.display = 'none';
					dID("dob_error2").style.display = 'inline';
					dID("dob_error3").style.display = 'none';
				}
				else if(gender_val_selected == "F" && age < 18)
				{
					error_fields[err_i] = "day_month_year_submit_err";
					err_i++;

					dID("dob_error1").style.display = 'none';
					dID("dob_error2").style.display = 'none';
					dID("dob_error3").style.display = 'inline';
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
		else if(field_name == "mstatus")
		{
			var mstatus_selected = 0;
			var mstatus_arr = document.getElementsByName("mstatus");
			dID("mstatus_error2").style.display = 'none';
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
				dID("mstatus_error1").style.display = 'inline';
				//dID("mstatus_error2").style.display = 'none';
				error_fields[err_i] = "mstatus_submit_err";
				err_i++;
			}
			else if(mstatus_value == "M")
			{
					if(religion_val)
					{
						if(religion_val!="2")
						{
							dID("mstatus_error2").style.display = 'inline';
							error_fields[err_i] = "mstatus_submit_err";
							err_i++;
						}
						else
						{
							correct_fields[cor_i] = "mstatus_submit_err";
							cor_i++;
						}
					}
					else
					{
						correct_fields[cor_i] = "mstatus_submit_err";
						cor_i++;
					}

			}
			else
			{
				correct_fields[cor_i] = "mstatus_submit_err";
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
		}
		else if(field_name == "phone")
		{
			
			if(docF.contact_option[1].checked)
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

						dID("phone_error1").style.display = 'inline';
						dID("phone_error2").style.display = 'none';
						dID("phone_error3").style.display = 'none';
						dID("contact_number_error").style.display = 'inline';
						dID("contact_number_noerror").style.display = 'none';
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

					dID("phone_error1").style.display = 'none';
					dID("phone_error2").style.display = 'inline';
					dID("phone_error3").style.display = 'none';
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';
				}
				else if(docF.phone.value.length < 6 && docF.phone.value != "")
				{
					error_fields[err_i] = field_name + "_submit_err";
					err_i++;

					dID("phone_error1").style.display = 'none';
					dID("phone_error2").style.display = 'none';
					dID("phone_error3").style.display = 'inline';
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';
				}
				else
				{
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';

					correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
				}
			}
		}
		else if(field_name == "showphone")
		{
			if(docF.contact_option[1].checked)
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
		}
		else if(field_name == "mobile")
		{
			if(docF.contact_option[0].checked)
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

						dID("mobile_error1").style.display = 'inline';
						dID("mobile_error2").style.display = 'none';
						dID("mobile_error3").style.display = 'none';
						dID("contact_number_error").style.display = 'inline';
						dID("contact_number_noerror").style.display = 'none';
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

					dID("mobile_error1").style.display = 'none';
					dID("mobile_error2").style.display = 'inline';
					dID("mobile_error3").style.display = 'none';
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';
				}
				else if(docF.mobile.value.length < 8 && docF.mobile.value != "" && country_val[1] != "51")
				{
					error_fields[err_i] = field_name + "_submit_err";
					err_i++;

					dID("mobile_error1").style.display = 'none';
					dID("mobile_error2").style.display = 'none';
					dID("mobile_error3").style.display = 'none';
					dID("mobile_error4").style.display = 'inline';
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';
				}
				else if(docF.mobile.value.length < 10 && docF.mobile.value != "" && country_val[1] == "51")
				{
					error_fields[err_i] = field_name + "_submit_err";
					err_i++;

					dID("mobile_error1").style.display = 'none';
					dID("mobile_error2").style.display = 'none';
					dID("mobile_error4").style.display = 'none';
					dID("mobile_error3").style.display = 'inline';
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';
				}
				else
				{
					dID("contact_number_error").style.display = 'none';
					dID("contact_number_noerror").style.display = 'inline';

					correct_fields[cor_i] = field_name + "_submit_err";
					cor_i++;
				}
			}
		}
		else if(field_name == "showmobile")
		{
			if(docF.contact_option[0].checked)
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
		}
		else if(field_name == "religion")
		{
			dID("mstatus_error2").style.display = 'none';
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

				dID("mstatus_error1").style.display = 'none';
				dID("mstatus_error2").style.display = 'inline';
				//dID("married_down_arrow").style.display = 'block';
			}
			else
			{
				if(mstatus_selected_value)
				{
					correct_fields[cor_i] = "mstatus_submit_err";
					cor_i++;
				}
				correct_fields[cor_i] = "religion_submit_err";
				cor_i++;
			}
			if(religion_val!="")
			{
				correct_fields[cor_i] = "religion_submit_err";
				cor_i++;
			}
			
		}
		else if(field_name == "caste")
		{
			if(religion_val == "5" || religion_val == "6" || religion_val == "7" || religion_val == "8" || religion_val == "10")
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
                else if(field_name == "jamaat")
		{
			if(religion_val == "2" && docF.caste.value == "152" && docF.jamaat.value == "")
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
                else if(field_name == "casteMuslim")
		{
			if(religion_val == "2" && docF.casteMuslim.value=="")
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
		else if(field_name == "income")
		{
			if(docF.income.value == "")			
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
		else if(field_name == "occupation")
		{
			if(docF.occupation.value == "")			
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
		else if(field_name == "degree")
		{
			if(docF.degree.value == "")			
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
		/*else if(field_name == "drink")
		{
				var drink_selected = 0;
				var drink_arr = document.getElementsByName("drink");
				var j1 = drink_arr.length;
				for(var j=0;j<j1;j++)
				{
					if(drink_arr[j].checked == true)
					      drink_selected = 1;
				}
				if(!drink_selected)
				{
				      error_fields[err_i] = "drink_submit_err";
				      err_i++;
				}
				else
				{
					correct_fields[cor_i] = "drink_submit_err";
					cor_i++;
				}
			}
		else if(field_name == "smoke")
		{
				var smoke_selected = 0;
				var smoke_arr = document.getElementsByName("smoke");
				var j1 = smoke_arr.length;
				for(var j=0;j<j1;j++)
				{
					if(smoke_arr[j].checked == true)
					      smoke_selected = 1;
				}
				if(!smoke_selected)
				{
				      error_fields[err_i] = "smoke_submit_err";
				      err_i++;
				}
				else
				{
					correct_fields[cor_i] = "smoke_submit_err";
					cor_i++;
				}
			}*/
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
		else if(field_name=="pincode")
		{
			if(CheckPincode())
			{
				error_fields[err_i] = "pincode_submit_err";
                                err_i++;
			}
			else
			{
				correct_fields[cor_i] = "pincode_submit_err";
                                cor_i++;
			}
		}
		
		else if(field_name=='city_residence')
		{
			if(dID('city_res_show_hide').style.display=='none')
			{       
				correct_fields[cor_i] = field_name + "_submit_err";
				cor_i++;
			}
			else
			{
				if(""==dID(field_name).value)
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
			if(typeof("callPincode")!='undefined')
			callPincode();
		}
		else
		{	if(!dID(field_name))
				alert(field_name);
			if(""==dID(field_name).value)
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
	var div_id = error_fields[i];
	var label_id=error_fields[i]+"_label";
	var label_idd=dID(label_id);
	var div_idd=dID(div_id);
	anurag=anurag+","+ div_id;
	if(!div_idd)
		alert(div_id);
//		if(div_idd)
		div_idd.style.display = 'block';
		if(label_idd)
			if(div_id=='phone_submit_err' || div_id=='mobile_submit_err')
			{
				label_idd=dID("phone_mobile");
				label_idd.style.color='#FF0000';
			}
			else
			 {
				if(div_id=='caste_submit_err')
				{
				      document.getElementById('caste_label_hindu').style.color='#FF0000';
				      document.getElementById('caste_label_muslim').style.color='#FF0000';
				      document.getElementById('caste_label_christian').style.color='#FF0000';
				 }
				label_idd.style.color='#FF0000';
			}
}
	var i1 = correct_fields.length;
	for(var i=0;i<i1;i++)
	{
		var div_id = correct_fields[i];
		var label_id=correct_fields[i]+"_label";
		var label_idd=dID(label_id);
		var div_idd=dID(div_id);
		if(!div_idd)
			alert(div_id);
//		if(div_idd)
			if(!in_array(div_id, error_fields))
			{
				div_idd.style.display = 'none';
				if(label_idd)
				{
					if(div_id=='phone_submit_err' || div_id=='mobile_submit_err')
					{
						label_idd=dID("phone_mobile");
						label_idd.style.color='#4B4B4B';
					}
					else
					{
						if(div_id=='caste_submit_err')
						{
						      document.getElementById('caste_label_hindu').style.color='#4B4B4B';
						      document.getElementById('caste_label_muslim').style.color='#4B4B4B';
						      document.getElementById('caste_label_christian').style.color='#4B4B4B';
						}
						label_idd.style.color='#4B4B4B';
					}
				}
			}
	}

	if(error_fields.length == 0)
		return true;
	else
	{
		var required_index = error_fields[0].indexOf("_submit_err");
		required_field_name = error_fields[0].substring(0,required_index);
		if(required_field_name == "day_month_year")
			required_field_name = "day";
		return false;
	}
	
}

/*Function to show/hide  mstatus reason entering layer*/
function mstatus_details(obj,required_id,action)
{
	var mstatus_selected_val = get_mstatus_value();
	if(mstatus_selected_val!='N' && mstatus_selected_val !=undefined)
  	    dID("have_child_section").style.display='inline';
	else
	    dID("have_child_section").style.display='none';

}

function edit_mstatus()
{

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
		if(!country_val_arr[1])
		{
			dID("city_res_show_hide").style.display='none';
			dID("city_residence_submit_err").style.display='none';
			dID("city_padding").style.padding='0px';
			return 1;
		}
		else
		{
			dID("city_res_show_hide").style.display='inline';
			dID("city_padding").style.padding='';
		}
		var city_label_value_arr = country_val_arr[1].split("#");
		var j=1;
		var i1 = city_label_value_arr.length;

		pop_city_array.push("<select class=\"sel1 fl\" size=\"1\" name=\"city_residence\" id=\"city_residence\" style=\"width:204px\" onchange=\"fetch_code('CITY',this.value);\" onfocus=\"change_div_class(this);\" onblur=\"validate(this);\">");
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
			
			if(city_label==" ")
				pop_city_array.push("<optgroup label=\"&nbsp;\"></optgroup>");
			else
			{
				pop_city_array.push("<option value=\"");
				pop_city_array.push(city_value);
				pop_city_array.push("\"");
				if(city_residence_selected && parseInt(city_residence_selected)!=0)
				{	
					if(city_value.indexOf(city_residence_selected)!=-1)
					{
						pop_city_array.push("selected=\"yes\"");
					}
				}
				else
				{
				      if(city_value==0 && parseInt(city_residence_selected)==0)
				      {
					      	pop_city_array.push("selected=\"yes\"");
				      }
				}
				pop_city_array.push(">");
				pop_city_array.push(city_label);
				pop_city_array.push("</option>");
			}
		}
		pop_city_array.push("</select>");

		dID("city_india_visible").innerHTML = pop_city_array.join('');
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

	if(docF.country_code_mob.value=='+91')
	{
	      dID("verify_message_phone").style.display='inline';
	      dID("verify_message_mobile").style.display='inline';
	}
	else
	{
		dID("verify_message_phone").style.display='none';
		dID("verify_message_mobile").style.display='none';
	}
}
function for_radio(element)
{
	if(element.type=='radio')
	{
		validate(element);
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
	var mstatus_selected_val = get_mstatus_value();

	if(gender_val == "M")
	{
		var end_year = current_year - 21;
		dID("mstatus_married_field").style.display='inline';
		if(mstatus_selected_val!='N' && mstatus_selected_val !=undefined)
                          dID("have_child_section").style.display='inline';
	}
	else if(gender_val == "F")
	{
		var end_year = current_year - 18;
		dID("mstatus_married_field").style.display='none';
		document.form1.mstatus[5].checked=false;
		/* Code for the Have Children Section for the Male and Female Case starts here */
		if(mstatus_selected_val=='N')
		{
			dID("have_child_section").style.display='none';
			document.form1.has_children[0].checked=false;
			document.form1.has_children[1].checked=false;
			document.form1.has_children[2].checked=false;
		}
	}

	dob_year_array.push("<select class=\"sel2 fl ml_10\" size=\"1\" name=\"year\" id=\"year\" style=\"width:60px\" onfocus=\"show_help(this); change_div_class(this);\" onblur=\"hide_help(this); validate(this);\">");
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

	dID("year_span_id").innerHTML = dob_year_array.join('');
}

var ArrayPincode={'11|':{0:["1100","2013","1220","2010","1210","1245"],1:4,2:"Please provide a pincode that belongs to Delhi"},"22|":{0:["400","401","410","421","416"],1:3,2:"Please provide a pincode that belongs to Mumbai"},"20|":{0:["410","411","412","413"],1:3,2:"Please provide a pincode that belongs to Pune"}};
function InCity(needle)
{
	if(typeof(ArrayPincode[needle])=='undefined')
		return false;
	return true;
}
function InPin(needle,arr)
{
	for(i=0;i<arr.length;i++)
		if(arr[i]==needle)
			return true;
	return false;
}
function callPincode()
{
	var city_res_val=docF.city_residence.value.substring(0,3);
	//alert(city_res_val);
	var country_res_val=docF.country_residence.value.substring(0,4);
        if(InCity(city_res_val) && country_res_val=="+91|")
		document.getElementById("pincode_hide").style.display="inline";
	else
		document.getElementById("pincode_hide").style.display="none";
}
function CheckPincode()
{
	var p_val=docF.pincode.value;
	var city_val=docF.city_residence.value.substring(0,3);
	var country_val=docF.country_residence.value.substring(0,4);
	var err_msg="";
	if(InCity(city_val) && country_val=="+91|")
	{
		if(p_val=="")
			err_msg="Please provide the Pincode of your residence";
		else if(p_val.length!=6 || (parseInt(p_val)).toString().length!=6)
			err_msg="Pincode you provided is invalid";
		else 
		{
			var initial = p_val.substring(0,ArrayPincode[city_val][1]);
			if(!InPin(initial,ArrayPincode[city_val][0]))
        		        err_msg=ArrayPincode[city_val][2];
	       	}
	}
	
	document.getElementById("pincode_err_msg").innerHTML=err_msg;
	if(err_msg)
	{
		document.getElementById("pincode_submit_err_label").style.color="red";
		document.getElementById("pincode_submit_err").style.display="inline";
	}
	else
	{
		document.getElementById("pincode_submit_err_label").style.color="";
		document.getElementById("pincode_submit_err").style.display="none";
	}
	return err_msg;
}
function showHideJamaat(){
     religionSelected = document.getElementById("religion").value.split("|X|")[0];
     casteSelected = document.getElementById("caste").value.split("|X|")[0];
     if(religionSelected == '2' && casteSelected == '152')
         document.getElementById("jamaatDiv").style.display = "inline";
     else{
         document.getElementById("jamaatDiv").style.display = "none";
         document.getElementById("jamaat").value = '';
     }
 }
function showHideCasteMuslim(){
    religionSelected = document.getElementById("religion").value.split("|X|")[0];
    if(religionSelected == '2')
        document.getElementById("CasteMuslimDiv").style.display = "inline";
    else{
        document.getElementById("CasteMuslimDiv").style.display = "none";
        document.getElementById("casteMuslim").value = '';
    }
}
