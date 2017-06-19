var a = document.getElementById("reg2").value;
//var tabs_arr = new Array("personal_details","family_details","education_profession","religion_ethnicity","upload_photo","about_myself");
if(a == "6" || a == "7")
	var tabs_arr = new Array("personal_details","family_details","education_profession","about_myself");
else
	var tabs_arr = new Array("personal_details","family_details","education_profession","religion_ethnicity","about_myself");

//alert(tabs_arr[3]);
var registration_pg2={
	'input' : function(element){
			if(element.type!='checkbox')
                        element.onclick = function(){
				remove_doesnt_matter_conflict(this);
				show_hide_horoscope();
				add_checkboxes(this);
                        }
			if(element.type!='checkbox')
			element.onfocus = function(){
				box_action(this,'clear');
				show_help(this);
				hide_suggest_divs(this);
			}
			if(element.type!='checkbox')
			element.onblur=function(){
				box_action(this,'fill');
				hide_help(this);
			}
		},

	'select' : function(element){
			element.onfocus = function(){
				show_help(this);
				hide_suggest_divs(this);
			}
			element.onblur = function(){
				hide_help(this);
			}
		},

	'textarea' : function(element){
			element.onfocus = function(){
				box_action(this,'clear');
				hide_suggest_divs(this);
			}
			element.onblur = function(){
				box_action(this,'fill');
			}
		},

	'a' : function(element){
			element.onclick = function(){
				if(this.id != "live_help")
				{
					add_checkboxes(this);
					remove_checkboxes(this);
					var current_id = this.id
					if(current_id)
						return false;
				}
			}
		},
		
	// '#live_help' : function(element){
	// 		element.onclick = function(){
	// 			window.open('http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&imageUrl=http://www.jeevansathi.com/images_try/liveperson&referrer='+escape(document.location),'chat13507809','width=472,height=320');
	// 			return false;
	// 		}
	// 	},
	
	'#more' : function(element){
			element.onclick = function(){
				window.open('http://www.google.com/transliterate/indic/about_hi.html');
				return false;
			}
		},
	
	'#more1' : function(element){
			element.onclick = function(){
				window.open('http://www.google.com/transliterate/indic/about_hi.html');
				return false;
			}
		},

	'#more2' : function(element){
			element.onclick = function(){
				window.open('http://www.google.com/transliterate/indic/about_hi.html');
				return false;
			}
		},
		
	'#personal_details' : function(element){
			element.onclick = function(){
				change_tab(this);
				return false;
			}
		},

	'#family_details' : function(element){
			element.onclick = function(){
				change_tab(this);
				return false;
			}
		},

	'#education_profession' : function(element){
			element.onclick = function(){
				change_tab(this);
				return false;
			}
		},

	'#religion_ethnicity' : function(element){
			element.onclick = function(){
				change_tab(this);
				return false;
			}
		},

	'#subcaste' : function(element){
			element.onkeyup = function(){
				auto_suggest(this,keycode,"subcaste_results","subcaste_results_div");
			}
			element.onkeydown = function(){
				if(keycode == "9")
				{
					document.getElementById("subcaste_results").style.display = 'none';
					document.getElementById("subcaste_results_iframe").style.display = 'none';
				}
			}
			element.onkeypress = function(){
				if(keycode == "9")
				{
					document.getElementById("subcaste_results").style.display = 'none';
					document.getElementById("subcaste_results_iframe").style.display = 'none';
				}
				else if(keycode == "13")
					return false;
			}
		},

	'#gotra' : function(element){
			element.onkeyup = function(){
				auto_suggest(this,keycode,"gotra_results","gotra_results_div");
			}
			element.onkeydown = function(){
				if(keycode == "9")
				{
					document.getElementById("gotra_results").style.display = 'none';
					document.getElementById("gotra_results_iframe").style.display = 'none';
				}
			}
			element.onkeypress = function(){
				if(keycode == "9")
				{
					document.getElementById("gotra_results").style.display = 'none';
					document.getElementById("gotra_results_iframe").style.display = 'none';
				}
				else if(keycode == "13")
					return false;
			}
		},

	'#diocese' : function(element){
			element.onkeyup = function(){
				auto_suggest(this,keycode,"diocese_results","diocese_results_div");
			}
			element.onkeydown = function(){
				if(keycode == "9")
				{
					document.getElementById("diocese_results").style.display = 'none';
					document.getElementById("diocese_results_iframe").style.display = 'none';
				}
			}
			element.onkeypress = function(){
				if(keycode == "9")
				{
					document.getElementById("diocese_results").style.display = 'none';
					document.getElementById("diocese_results_iframe").style.display = 'none';
				}
				else if(keycode == "13")
					return false;
			}
		},

/*	'#upload_photo' : function(element){
			element.onclick = function(){
				change_tab(this);
				return false;
			}
		}, */

	'#about_myself' : function(element){
			element.onclick = function(){
				change_tab(this);
				return false;
			}
		},

	'#personal_details_next' : function(element){
				element.onclick = function(){
					change_tab(this,"next");
			}
		},

	'#family_details_next' : function(element){
				element.onclick = function(){
					change_tab(this,"next");
			}
		},

	'#family_details_back' : function(element){
				element.onclick = function(){
					change_tab(this,"back");
			}
		},

	'#education_profession_next' : function(element){
				element.onclick = function(){
					change_tab(this,"next");
			}
		},

	'#education_profession_back' : function(element){
				element.onclick = function(){
					change_tab(this,"back");
			}
		},

	'#religion_ethnicity_next' : function(element){
				element.onclick = function(){
					change_tab(this,"next");
			}
		},

	'#religion_ethnicity_back' : function(element){
				element.onclick = function(){
					change_tab(this,"back");
			}
		},

/*	'#upload_photo_next' : function(element){
				element.onclick = function(){
					change_tab(this,"next");
			}
		},

	'#upload_photo_back' : function(element){
				element.onclick = function(){
					change_tab(this,"back");
			}
		},*/

	'#about_myself_back' : function(element){
				element.onclick = function(){
					change_tab(this,"back");
			}
		},

	'#weight' : function(element){
			element.onkeyup = function(){
				var validchars = "0123456789.";
				var isnumber=true;
				var cur_char;
				var weight_val = docF.weight.value
				for(var i = 0;i<weight_val.length && isnumber == true; i++) 
				{
					cur_char = weight_val.charAt(i);
					if(validchars.indexOf(cur_char) == -1) 
					{
						isnumber = false;
					}
				}
				if(!isnumber)
				{
					var url_for_contact ='registration_alert.php?width=520&height=111';
					//alert(docF.weight_string_error.value);
					$('.thickbox').colorbox();
					imgLoader = new Image();// preload image
					imgLoader.src = tb_pathToImage;
					$.colorbox({href:url_for_contact});
					docF.weight.value = "";
				}
				else if(weight_val.charAt(0) == "0")
				{
					//alert(docF.weight_invalid_error.value);
					$('.thickbox').colorbox();
					imgLoader = new Image();// preload image
					imgLoader.src = tb_pathToImage;
					$.colorbox({href:'registration_alert.php?number=1&width=520&height=111'});
					docF.weight.value = "";
				}
			}
		},

	'#handicap_section' : function(element){
			element.onclick = function(){
				var handicap_arr = document.getElementsByName("handicapped");
				var handicap_val;
				for(var i=0;i<handicap_arr.length;i++)
				{
					if(handicap_arr[i].checked == true)
						handicap_val = handicap_arr[i].value;
				}

				if(document.getElementById("handicap_nature"))
				{
					if(handicap_val == "1" || handicap_val == "2")
							document.getElementById("handicap_nature").style.display = 'block';
					else
							document.getElementById("handicap_nature").style.display = 'none';
				}
				if(document.getElementById("partner_handicapped_section"))
				{
					if(handicap_val)
					{
						if(handicap_val == "N")
							document.getElementById("partner_handicapped_section").style.display = 'none';
						else
							document.getElementById("partner_handicapped_section").style.display = 'block';
					}


					var partner_handicap_field = document.getElementsByName("partner_handicapped_arr[]");
					for(var i=0;i<partner_handicap_field.length;i++)
					{
						if(handicap_val == "1"|| handicap_val == "2")
						{
							if(partner_handicap_field[i].value == "1" || partner_handicap_field[i].value == "2")
								document.getElementById("partner_handicapped_"+partner_handicap_field[i].value).checked = true;
							else
								document.getElementById("partner_handicapped_"+partner_handicap_field[i].value).checked = false;
						}
						else if(handicap_val == "3"|| handicap_val == "4")
						{
							if(partner_handicap_field[i].value == "3" || partner_handicap_field[i].value == "4")
								document.getElementById("partner_handicapped_"+partner_handicap_field[i].value).checked = true;
							else
								document.getElementById("partner_handicapped_"+partner_handicap_field[i].value).checked = false;
						}
						else if(handicap_val == "N")
						{
							if(partner_handicap_field[i].value == "N")
								document.getElementById("partner_handicapped_"+partner_handicap_field[i].value).checked = true;
							else
								document.getElementById("partner_handicapped_"+partner_handicap_field[i].value).checked = false;
						}
					}
					swap_checkboxes("partner_handicapped");
				}
			}
		},

	'#brothers' : function(element){
			element.onchange = function(){
				if(document.getElementById("brothers_married_section"))
				{
					if(docF.brothers.value > 0)
						document.getElementById("brothers_married_section").style.display = 'inline'
					else
						document.getElementById("brothers_married_section").style.display = 'none'

					populate_married_count("brothers");
				}
			}
		},

	'#sisters' : function(element){
			element.onchange = function(){
				if(document.getElementById("sisters_married_section"))
				{
					if(docF.sisters.value > 0)
						document.getElementById("sisters_married_section").style.display = 'inline'
					else
						document.getElementById("sisters_married_section").style.display = 'none'

					populate_married_count("sisters");
				}
			}
		},

	'#amritdhari_section' : function(element){
			element.onclick = function(){
					var amrit_arr = document.getElementsByName("amritdhari");
					for(var i=0;i<amrit_arr.length;i++)
					{
						if(amrit_arr[i].checked == true)
							var amrit_val = amrit_arr[i].value;
					}

					if(amrit_val == "N")
					{
						if(document.getElementById("sikh_males_only_section"))
							document.getElementById("sikh_males_only_section").style.display = 'block';
						document.getElementById("cut_hair_section").style.display = 'block';
					}
					else
					{
						if(document.getElementById("sikh_males_only_section"))
							document.getElementById("sikh_males_only_section").style.display = 'none';
						document.getElementById("cut_hair_section").style.display = 'none';
					}
			}
		},

	'#about_yourself' : function(element){
			element.onkeyup = function(){
				var about_yourself_value = this.value;
				about_yourself_value = about_yourself_value.replace(/^\s+|\s+$/g, "");
				var about_yourself_value_count = about_yourself_value.length;
				if(about_yourself_value_count >= 100)
					document.getElementById("about_yourself_count").style.color = '#00BB00';
				else
					document.getElementById("about_yourself_count").style.color = '#FF0000';
				document.getElementById("about_yourself_count").innerHTML = about_yourself_value_count;
			}
			element.onkeydown = function(){
				if(shift_key == "pressed" && (keycode=="37" || keycode=="38" || keycode=="39" || keycode=="40"))
					return false;
			}
			//for IE4+
			element.onselectstart = function(){
				return false;
			}
			element.oncontextmenu = function(){
				return false;
			}
			element.onselect = function(){
				var temp_val = this.value;
				this.value = "";
				this.value = temp_val;
				this.focus();
			}
			if(window.sidebar)
			{
				element.onmousedown = function(){
					return false;
				}
				element.onclick = function(){
					var cur_name = this.name;
					eval("docF." + cur_name + ".focus();");
					return true;
				}
			}
			if(document.layers) 
				window.captureEvents(Event.MOUSEDOWN);
			window.onmousedown = disable_rightclick;
		},

		'#checkboxId' : function(element){
			element.onclick = function(){	
				checkboxClickHandler("checkboxId");
				//return false;
			}
		},

		'#checkboxId2' : function(element){
			element.onclick = function(){	
				checkboxClickHandler("checkboxId2");
				//return false;
			}
		},

		'#checkboxId3' : function(element){
			element.onclick = function(){	
				checkboxClickHandler("checkboxId3");
				//return false;
			}
		},

		/*'#reg_page2_form' : function(element){
			element.onsubmit = function(){
				if(document.getElementById("about_yourself_count").innerHTML < 100)
				{
					if(confirm(docF.alert_about_yourself.value))
					{
						return true;
					}
					else
					{
						change_tab("","","about_myself");
						docF.about_yourself.focus();
						return false;
					}
				}
			}
		},*/
		
		'#submit_pg2' : function(element){
			element.onclick = function(){
				if(document.getElementById("about_yourself_count").innerHTML < 100)
				{
					document.getElementById("gray_layer").style.display = 'block';
					document.getElementById("confirmation_layer").style.display = 'block';
//					document.getElementById("page2_body").style.overflow = 'hidden';
					return false;
				}
				else
					document.form1.submit();
			}
		},

		'#close' : function(element){
			element.onclick = function(){
				close_layer();
				return false;
			}
		},
		
		'#cancel' : function(element){
			element.onclick = function(){
				close_layer();
			}
		},
		
		'#continue' : function(element){
			element.onclick = function(){
				document.form1.submit();
				return false;
			}
		},

		'#close_help_af' : function(element){
			element.onclick = function(){
				close_help_family();
			}
		},

		'#close_help_yf' : function(element){
			element.onclick = function(){
				close_help_yourself();
			}
		},

		'#close_help_pr' : function(element){
			element.onclick = function(){
				close_help_partner();
			}
		}
};

Behaviour.register(registration_pg2);
Behaviour.addLoadEvent(onload_events);

function close_help_family()
{
        document.getElementById("aboutfamily_help").style.display='none';
	return false;
}

function close_help_yourself()
{
        document.getElementById("aboutyourself_help").style.display='none';
	return false;
}

function close_help_partner()
{
        document.getElementById("aboutpartner_help").style.display='none';
	return false;
}

//does this require any comments
function onload_events()
{
	docF = document.form1;
	var to_send_array = new Array("spoken_languages");
	fill_details(to_send_array);
}

//function to disable right click in IE//
function disable_rightclick(e) 
{
	if(navigator.appName == 'Netscape' && (e.which == 3 || e.which == 2))
		return false;
	else if(navigator.appName == 'Microsoft Internet Explorer' && (event.button == 2 || event.button == 3))
		return false;

	return true;
}

/*Function to show the desired section, either by clicking the tabs or by clicking next/back button
 * obj -> reference to the current object,
 * to -> next or back
 * id_of_div -> change tab to the specified div id.
 * */

function change_tab(obj,to,id_of_div)
{
	var section_div_id, link_id, tab_div_id, to_show, check_tab_div_id, to_append_id,span_id;

	if(id_of_div)
		var id = id_of_div;
	else
		var id = obj.id;

	if(to=="next")
	{
		for(var i=0;i<tabs_arr.length;i++)
		{
			if(id.match(tabs_arr[i]))
			{
				to_append_id = tabs_arr[i+1];
				check_tab_div_id = tabs_arr[i+1] + "_tab_div";
				if(document.getElementById(check_tab_div_id))
				{
					to_show = tabs_arr[i+1];
					break;
				}
				else
				{
					to_show = tabs_arr[i+2];
					break;
				}
			}
		}
		for(var i=0;i<tabs_arr.length;i++)
		{
			span_id = tabs_arr[i] + "_span";
			tab_div_id = tabs_arr[i] + "_tab_div";
			section_div_id = tabs_arr[i] + "_section";

			if(document.getElementById(tab_div_id))
			{
				if(to_show.match(tabs_arr[i]))
				{
					document.getElementById(section_div_id).style.display = 'block';
					document.getElementById(tab_div_id).className = 'opentab';
					document.getElementById(span_id).style.color = '#000000';
				}
				else
				{
					document.getElementById(section_div_id).style.display = 'none';
					document.getElementById(tab_div_id).className = 'closetab';
					document.getElementById(span_id).style.color = '#797979';
				}
			}
		}
	}
	else if(to == "back")
	{
		for(var i=0;i<tabs_arr.length;i++)
		{
			if(id.match(tabs_arr[i]))
			{
				to_append_id = tabs_arr[i-1];
				check_tab_div_id = tabs_arr[i-1] + "_tab_div";
				if(document.getElementById(check_tab_div_id))
				{
					to_show = tabs_arr[i-1];
					break;
				}
				else
				{
					to_show = tabs_arr[i-2];
					break;
				}
			}
		}
		for(var i=0;i<tabs_arr.length;i++)
		{
			span_id = tabs_arr[i] + "_span";
			tab_div_id = tabs_arr[i] + "_tab_div";
			section_div_id = tabs_arr[i] + "_section";

			if(document.getElementById(tab_div_id))
			{
				if(to_show.match(tabs_arr[i]))
				{
					document.getElementById(section_div_id).style.display = 'block';
					document.getElementById(tab_div_id).className = 'opentab';
					document.getElementById(span_id).style.color = '#000000';
				}
				else
				{
					document.getElementById(section_div_id).style.display = 'none';
					document.getElementById(tab_div_id).className = 'closetab';
					document.getElementById(span_id).style.color = '#797979';
				}
			}
		}
	}
	else
	{
		for(var i=0;i<tabs_arr.length;i++)
		{
			span_id = tabs_arr[i] + "_span";
			tab_div_id = tabs_arr[i] + "_tab_div";
			section_div_id = tabs_arr[i] + "_section";
			if(document.getElementById(tab_div_id))
			{
				if(id.match(tabs_arr[i]))
				{
					to_append_id = tabs_arr[i];
					document.getElementById(section_div_id).style.display = 'block';
					document.getElementById(tab_div_id).className = 'opentab';
					document.getElementById(span_id).style.color = '#000000';
				}
				else
				{
					document.getElementById(section_div_id).style.display = 'none';
					document.getElementById(tab_div_id).className = 'closetab';
					document.getElementById(span_id).style.color = '#797979';
				}
			}
		}
	}
	
	if(to_append_id == "about_myself")
		document.getElementById("finish_registration_button").style.display = 'block';
	else
		document.getElementById("finish_registration_button").style.display = 'none';
		
	var to_send_array_name = to_append_id + "_fields_arr";
	save_current_details(to_send_array_name);
}

function show_hide_horoscope()
{
	var horoscope_fields = document.getElementsByName("horoscope");

	for(var i=0;i<horoscope_fields.length;i++)
	{
		if(horoscope_fields[i].checked == true)
		{
			if(horoscope_fields[i].value == "Y")
				document.getElementById("horoscope_frame").style.display = 'block';
			else if(horoscope_fields[i].value == "N")
				document.getElementById("horoscope_frame").style.display = 'none';
		}
	}
}

function hide_suggest_divs(obj)
{
	var results_div_id;
	var suggest_divs_array = new Array("subcaste","gotra","diocese");
	var i1 = suggest_divs_array.length;
	for(var i=0;i<i1;i++)
	{
		if(obj.id != suggest_divs_array[i])
		{
			results_div_id = suggest_divs_array[i] + "_results";
			if(document.getElementById(results_div_id))
				document.getElementById(results_div_id).style.display = 'none';
		}
	}
}

function close_layer()
{
	document.getElementById("gray_layer").style.display = 'none';
	document.getElementById("confirmation_layer").style.display = 'none';
	//document.getElementById("page2_body").style.overflow = 'scroll';
	change_tab("","","about_myself");
	docF.about_yourself.focus();
	return false;
}


function populate_married_count(count_for)
{
	var total_count = document.getElementById(count_for).value;
	var married_count_id = document.getElementById("married_" + count_for);
	var pl_sel = married_count_id.options[0].text;

	married_count_id.options.length = 0;
	married_count_id.options[0] = new Option(pl_sel,"");
	for(var i=0;i<=total_count;i++)
	{
		if(i>3)
			married_count_id.options[i] = new Option("3+",i);
		else
			married_count_id.options[i] = new Option(i,i);
	}
}
