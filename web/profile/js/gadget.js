//var gadget_array = new Array("partner_degree","partner_income");
var gadget_array = scroller_arr;
var gadget_name = "";
var original_name = "";
var original_arr = new Array();
var checked_subcat_label_array = new Array();
var unchecked_subcat_label_array = new Array();

function is_gadget(name)
{
	var i1 = gadget_array.length;
	for(var i=0;i<i1;i++)
	{
		if(name.match(gadget_array[i]))
		{
			gadget_name = gadget_array[i];
			original_name = gadget_name + "_arr[]";
			original_arr = document.getElementsByName(original_name);
			return true;
		}
	}
	return false;
}

function add_checkboxes(obj)
{
	if(is_gadget(obj.id))
	{
		if(obj.type == "checkbox")
		{
			var clicked_arr = obj.id.split("_");
			var to_tick_id = gadget_name + "_" + clicked_arr[clicked_arr.length-1];
			document.getElementById(to_tick_id).checked = true;
			
			if(document.getElementById(gadget_name + "_DM"))
				document.getElementById(gadget_name + "_DM").checked = false;
		}
		else if(obj.id.match("select_all"))
		{
			var i1 = original_arr.length;
			for(var i=0;i<i1;i++)
				if(original_arr[i].value == "DM")
					original_arr[i].checked = false;
				else
					original_arr[i].checked = true;
		}
		swap_checkboxes();
	}
}

function remove_checkboxes(obj)
{
	if(is_gadget(obj.id))
	{
		if(obj.id.match("_link_"))
		{
			var cleared = 0,dmid;
			var clicked_arr = obj.id.split("_");
			var to_untick_id = gadget_name + "_" + clicked_arr[clicked_arr.length-1];
			document.getElementById(to_untick_id).checked = false;
			var i1 = original_arr.length;
			for(var i=0;i<i1;i++)
			{
				if(original_arr[i].checked == true)
				{
					cleared = 0;
					break;
				}
				else
					cleared=1;
			}
			dmid = gadget_name + "_DM";
			if(cleared && document.getElementById(dmid))
				document.getElementById(dmid).checked = true;
		}
		else if(obj.id.match("clear_all"))
		{
			var i1 = original_arr.length;
			for(var i=0;i<i1;i++)
			{
				if(original_arr[i].value == "DM")
					original_arr[i].checked = true;
				else
					original_arr[i].checked = false;
			}
		}
		swap_checkboxes();
	}
}

function swap_checkboxes(got_gadget_name)
{
	if(got_gadget_name)
		is_gadget(got_gadget_name);
		
	//code for partner_caste gadget --- separator patch
	if(document.getElementById("mapped_caste_values"))
	{
		var mapped_caste_id = document.getElementById("mapped_caste_values");
		var mapped_caste = mapped_caste_id.value.split("|#|");
		var original_mapped_caste_length = mapped_caste.length;
		var mapped_caste_length = original_mapped_caste_length;
	}

	var subcat_label,display_checkbox;
	var to_write_checked_str = new Array();
	var to_write_unchecked_str = new Array();
	var image_url = docF.img_url.value;
	checked_subcat_label_array = new Array();
	unchecked_subcat_label_array = new Array();
	var selected_caste_values = new Array();
	var duplicate;
	var temp_caste_checked_array = new Array();
	var temp_caste_unchecked_array = new Array();
	var count_for_separator = 0;
	var i1 = original_arr.length;
	var separator_only_once = 0;
	for(var i=0;i<i1;i++)
	{
		var div_id = gadget_name + "_label_" + original_arr[i].value;
		var label = document.getElementById(div_id).innerHTML;
		duplicate=0;

		if(original_arr[i].checked)
		{
			if(gadget_name == "partner_caste" && in_array(original_arr[i].value,selected_caste_values))
				duplicate = 1;
				
			if(!duplicate)
			{
				if(document.getElementById("mapped_caste_values") && in_array(original_arr[i].value,mapped_caste) && !in_array(original_arr[i].value,temp_caste_checked_array))
				{
					if(mapped_caste_length > 0)
					{
						mapped_caste_length--;
						temp_caste_checked_array.push(original_arr[i].value);
					}
				}
				if(gadget_name == "partner_mtongue")
					var subcat_label = get_category_label(original_arr[i].value,"checked");
					
				if(gadget_name == "partner_caste")
					selected_caste_values.push(original_arr[i].value);
	
				if(gadget_name == "partner_income")
				{
					if(original_arr[i].value == "DM")
						display_checkbox = 1;
					else
						display_checkbox = partner_income_checkbox(original_arr[i].value);
				}
				else
					display_checkbox = 1;
	
				if(typeof(subcat_label) != "undefined")
				{
					to_write_checked_str.push("<span style=\"color:#0a89fe\">");
					to_write_checked_str.push(subcat_label);
					to_write_checked_str.push("</span>");
					to_write_checked_str.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
				}
	
				if(original_arr[i].value.indexOf("|#|") < 0 && display_checkbox)
				{
					if(original_arr[i].value == "DM")
					{
						to_write_checked_str.push("<div id=\"");
						to_write_checked_str.push(gadget_name);
						to_write_checked_str.push("_link_");
						to_write_checked_str.push(original_arr[i].value);
						to_write_checked_str.push("\"");
						to_write_checked_str.push("<label>");
						to_write_checked_str.push(label);
						to_write_checked_str.push("</label></div>");
					}
					else
					{
						to_write_checked_str.push("<div id=\"");
						to_write_checked_str.push(gadget_name);
						to_write_checked_str.push("_link_");
						to_write_checked_str.push(original_arr[i].value);
						to_write_checked_str.push("\"");
						to_write_checked_str.push("onmouseover=\"highlight(this,'ON');\"");
						to_write_checked_str.push("onmouseout=\"highlight(this,'OFF');\"");
						to_write_checked_str.push("onclick=\"remove_checkboxes(this);\" >");
						to_write_checked_str.push("<img id=\"");
						to_write_checked_str.push(gadget_name);
						to_write_checked_str.push("_image_");
						to_write_checked_str.push(original_arr[i].value);
						to_write_checked_str.push("\" src=\"");
						to_write_checked_str.push(image_url);
						to_write_checked_str.push("/remove_gray.gif\" \/>");
						to_write_checked_str.push("&nbsp;<label>");
						to_write_checked_str.push(label);
						to_write_checked_str.push("</label></div>");
					}
				}
			}
		}
		else
		{
			if(gadget_name == "partner_caste" && in_array(original_arr[i].value,selected_caste_values))
				duplicate = 1;
				
			if(!duplicate)
			{
				
				if(document.getElementById("mapped_caste_values") && in_array(original_arr[i].value,mapped_caste) && !in_array(original_arr[i].value,temp_caste_unchecked_array))
				{
					if(mapped_caste_length != (original_mapped_caste_length - temp_caste_checked_array.length))
					{
						mapped_caste_length++;
						temp_caste_unchecked_array.push(original_arr[i].value);
					}
				}
				
				if(gadget_name == "partner_mtongue")
					subcat_label = get_category_label(original_arr[i].value,"unchecked");
	
				if(gadget_name == "partner_income")
				{
					if(original_arr[i].value == "DM")
						display_checkbox = 1;
					else
						display_checkbox = partner_income_checkbox(original_arr[i].value);
				}
				else
					display_checkbox = 1;
	
				if(typeof(subcat_label) != "undefined")
				{
					to_write_unchecked_str.push("<span style=\"color:#0a89fe\">");
					to_write_unchecked_str.push(subcat_label);
					to_write_unchecked_str.push("</span>");
					to_write_unchecked_str.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
				}
	
				if(original_arr[i].value.indexOf("|#|") < 0 && display_checkbox && original_arr[i].value != "DM")
				{
					to_write_unchecked_str.push("<input type=\"checkbox\" name=\"");
					to_write_unchecked_str.push(gadget_name);
					to_write_unchecked_str.push("_displaying_arr[]\" id=\"");
					to_write_unchecked_str.push(gadget_name);
					to_write_unchecked_str.push("_displaying_");
					to_write_unchecked_str.push(original_arr[i].value);
					to_write_unchecked_str.push("\" value=\"");
					to_write_unchecked_str.push(original_arr[i].value);
					if(gadget_name == "partner_handicapped" || gadget_name == "spoken_languages")
						to_write_unchecked_str.push("\" class=\"chbx checkboxalign\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this);\" \/>");
					else
						to_write_unchecked_str.push("\" class=\"chbx checkboxalign\" onfocus=\"change_div_class(this);\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this);\" onblur=\"validate(this);\"\/>");
					to_write_unchecked_str.push("<label id=\"");
					to_write_unchecked_str.push(gadget_name);
					to_write_unchecked_str.push("_displaying_label_");
					to_write_unchecked_str.push(original_arr[i].value);
					to_write_unchecked_str.push("\" >");
					to_write_unchecked_str.push(label);
					to_write_unchecked_str.push("</label><br />");
					
					count_for_separator++;
				}
			}
		}
		if(gadget_name == "partner_caste" && (count_for_separator == mapped_caste_length) && !separator_only_once && (mapped_caste.length != temp_caste_checked_array.length))
		{
			to_write_unchecked_str.push("<div class=\"dhrow\"><span style=\"color:#0a89fe;\">------</span></div><div class=\"clear\"></div>");
			separator_only_once = 1;
		}
	}
	
	/*if(to_write_unchecked_str.length  == 0)
	{
		document.getElementById(gadget_name + "_select_all").style.color = '#9C9C9C';
		//document.getElementById(gadget_name + "_clear_all").style.color = '';
	}
	else
	{
		document.getElementById(gadget_name + "_select_all").style.color = '';
		//document.getElementById(gadget_name + "_clear_all").style.color = '#9C9C9C';
	}*/

	if(to_write_unchecked_str.length  == 0)
                document.getElementById(gadget_name + "_select_all").style.color = '#9C9C9C';
        else
                document.getElementById(gadget_name + "_select_all").style.color = '';
	if(to_write_checked_str.length  == 8)
                document.getElementById(gadget_name + "_clear_all").style.color = '#9C9C9C';
        else
                document.getElementById(gadget_name + "_clear_all").style.color = '';


	document.getElementById(gadget_name + "_target_div").innerHTML = to_write_checked_str.join('');
	document.getElementById(gadget_name + "_source_div").innerHTML = to_write_unchecked_str.join('');

	//Behaviour.apply(gadget_name + "_target_div");
	//Behaviour.apply(gadget_name + "_source_div");
	if(in_array(gadget_name,validate_fields))
		validate("",gadget_name);
}

function get_category_label(for_value,for_input)
{
	var j=0;
	var k=0;
	var i1 = original_arr.length;
	for(var i=0;i<i1;i++)
	{
		if(original_arr[i].value.indexOf("|#|") > 0)
		{
			var subcat_array = original_arr[i].value.split("|#|");
			if(in_array(for_value, subcat_array))
			{
				var div_id = gadget_name + "_label_" + original_arr[i].value;
				var subcat_label = document.getElementById(div_id).innerHTML;
				if(!in_array(subcat_label, checked_subcat_label_array) && for_input == "checked")
				{
					checked_subcat_label_array[j] = subcat_label;
					j++;
					return subcat_label;
				}
				else if(!in_array(subcat_label, unchecked_subcat_label_array) && for_input == "unchecked")
				{
					unchecked_subcat_label_array[k] = subcat_label;
					k++;
					return subcat_label;
				}
			}
		}
	}
}

function highlight(obj,on_or_off)
{
	if(on_or_off == "ON")
	{
		document.getElementById(obj.id).className = "hover";
		var img_id = obj.id.replace(/link/,"image");
		document.getElementById(img_id).src = document.getElementById(img_id).src.replace(/_gray/,"_blue");
	}
	else if(on_or_off == "OFF")
	{
		document.getElementById(obj.id).className = "";
		var img_id = obj.id.replace(/link/,"image");
		document.getElementById(img_id).src = document.getElementById(img_id).src.replace(/_blue/,"_gray");
	}
}
