//var gadget_array = new Array("partner_degree","partner_income");
var gadget_array = scroller_arr;
var gadget_name = "";
var original_name = "";
var original_arr = new Array();
var display_arr = new Array();
var checked_subcat_label_array = new Array();
var unchecked_subcat_label_array = new Array();
var flag_all=0;
var firstTimeCasteUpdate=1;
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
			var all_hindi;
			var to_tick_id = gadget_name + "_" + clicked_arr[clicked_arr.length-1];
			document.getElementById(to_tick_id).checked = true;
			if(document.getElementById(gadget_name +"_selected"))
				document.getElementById(obj.id).checked = true;
			if(gadget_name=='partner_mtongue')
			{
				document.getElementById("mton_sel").value=document.getElementById("mton_sel").value+"'"+clicked_arr[clicked_arr.length-1]+"',";
				if(to_tick_id=='partner_mtongue_10,19,33,7,28,13,41')
				{		
						$("#partner_mtongue_10").prop('checked',true);
						
						
						if(document.getElementById('partner_mtongue_displaying_10')){
							$("#partner_mtongue_displaying_10").prop('checked',true);
	                                                $("#partner_mtongue_41").prop('checked',true);
						}
						if(document.getElementById('partner_mtongue_displaying_41')){
							$("#partner_mtongue_displaying_41").prop('checked',true);
                                                        $("#partner_mtongue_19").prop('checked',true);
						}
						if(document.getElementById('partner_mtongue_displaying_19')){
							$("#partner_mtongue_displaying_19").prop('checked',true);
                                                        $("#partner_mtongue_33").prop('checked',true);
						}
						if(document.getElementById('partner_mtongue_displaying_33')){
							$("#partner_mtongue_displaying_33").prop('checked',true);
                                                        $("#partner_mtongue_7").prop('checked',true);
						}
						if(document.getElementById('partner_mtongue_displaying_7')){
							$("#partner_mtongue_displaying_7").prop('checked',true);
                                                        $("#partner_mtongue_28").prop('checked',true);
						}
						if(document.getElementById('partner_mtongue_displaying_28')){
							$("#partner_mtongue_displaying_28").prop('checked',true);
                                                        $("#partner_mtongue_13").prop('checked',true);
						}
						if(document.getElementById('partner_mtongue_displaying_13')){
							$("#partner_mtongue_displaying_13").prop('checked',true);
                                                        $("#partner_mtongue_10,19,33,7,28,13,41").prop('checked',true);
						}
						flag_all=1;
				}
				else
					flag_all=0;
			}
			if(document.getElementById(gadget_name + "_DM"))
                                document.getElementById(gadget_name + "_DM").checked = false;
		}
		else if(obj.id.match("select_all"))
		{
			var i1 = original_arr.length;
			var display_name = gadget_name + "_displaying_arr[]";
			display_arr = document.getElementsByName(display_name);
			var i2 = display_arr.length;
			for(var i=0;i<i1;i++){
				if(original_arr[i].value == "DM")
					original_arr[i].checked = false;
				else
				{
					original_arr[i].checked = true;
				}
			}
			for(var i=0;i<i2;i++)
			{
				if(display_arr.value == "DM")
				{
					display_arr[i].checked = false;
				}
				else
				{
					display_arr[i].checked = true;
				}
			}
		}
		swap_checkboxes('');
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
			if(dID(to_untick_id))
					document.getElementById(to_untick_id).checked = false;
			if(gadget_name=='partner_mtongue')
			{
                                document.getElementById("mton_sel").value=document.getElementById("mton_sel").value.replace("'"+clicked_arr[clicked_arr.length-1]+"'",'');
				if(to_untick_id=='partner_mtongue_10,19,33,7,28,13,41')
				{		
						document.getElementById('partner_mtongue_10').checked=false;

						if(document.getElementById('partner_mtongue_displaying_10')){
							document.getElementById('partner_mtongue_displaying_10').checked=false;
							document.getElementById('partner_mtongue_41').checked=false;
						}
						if(document.getElementById('partner_mtongue_displaying_41')){
							document.getElementById('partner_mtongue_displaying_41').checked=false;
							document.getElementById('partner_mtongue_19').checked=false;
						}
						if(document.getElementById('partner_mtongue_displaying_19')){
							document.getElementById('partner_mtongue_displaying_19').checked=false;
							document.getElementById('partner_mtongue_33').checked=false;
						}
						if(document.getElementById('partner_mtongue_displaying_33')){
							document.getElementById('partner_mtongue_displaying_33').checked=false;
							document.getElementById('partner_mtongue_7').checked=false;
						}
						if(document.getElementById('partner_mtongue_displaying_7')){
							document.getElementById('partner_mtongue_displaying_7').checked=false;
							document.getElementById('partner_mtongue_28').checked=false;
						}
						if(document.getElementById('partner_mtongue_displaying_28')){
							document.getElementById('partner_mtongue_displaying_28').checked=false;
							document.getElementById('partner_mtongue_13').checked=false;
						}
						if(document.getElementById('partner_mtongue_displaying_13')){
							document.getElementById('partner_mtongue_displaying_13').checked=false;
							document.getElementById('partner_mtongue_10,19,33,7,28,13,41').checked=false;
						}
						flag_all=1;
				}
				else
						flag_all=0;
			}
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
			var display_name = gadget_name + "_displaying_arr[]";
			display_arr = document.getElementsByName(display_name);
			var i2 = display_arr.length;
			
			for(var i=0;i<i1;i++)
			{
				if(original_arr[i].value == "DM")
				{
					original_arr[i].checked = true;
				}
				else
				{
					original_arr[i].checked = false;
				}
			}
			for(var i=0;i<i2;i++)
                        {
                                if(display_arr.value == "DM")
                                {
                                        display_arr[i].checked = true;
                                }
                                else
                                {
                                        display_arr[i].checked = false;
                                }
                        }

			
			if(document.getElementById("mton_sel"))
				document.getElementById("mton_sel").value='';
		}
		flag_all=0;
		swap_checkboxes('');
	}
	
}

function swap_checkboxes(got_gadget_name)
{
	var docF=document.form1;
	if(got_gadget_name)
		is_gadget(got_gadget_name);

	var subcat_label,display_checkbox;
	var to_write_checked_str = new Array();
	var to_write_unchecked_str = new Array();
	var  priority_str = new Array();
	var image_url = docF.img_url.value;
	checked_subcat_label_array = new Array();
	unchecked_subcat_label_array = new Array();
	var i1 = original_arr.length;
	var countries= new Array();
	var religions= new Array();
	var mix=1;var hindu=0;
	var other=0; 
	var nhandicap_flag=0;
	var skip_allhindi;
	var checked_box;
	var checked_val;
	if(document.getElementById("mton_sel")){
	var mton_sel=document.getElementById("mton_sel").value;
	priority_arr=new Array();
	}
	if(document.getElementById(gadget_name+"_selected"))
	{
		priority_arr=document.getElementById(gadget_name+"_selected").value.split(",");
	}	
	for(var i=0;i<i1;i++)
	{
		var div_id = gadget_name + "_label_" + original_arr[i].value;
		var label = document.getElementById(div_id).innerHTML;
		skip_allhindi=0;
		if(i==0 && gadget_name == "partner_mtongue")
		{
			if(mton_sel.match("'10'") && mton_sel.match("'33'") && mton_sel.match("'7'") && mton_sel.match("'28'") && mton_sel.match("'13'") && (mton_sel.match("'19'") || mton_sel.match("'41'")))
			flag_all=1;
			mton_sel='';
		
		}
		checked_val=0;
		if(original_arr[i].checked)
		{
			checked_box=document.getElementById(gadget_name+'_displaying_'+original_arr[i].value);
			if(checked_box)
				if(checked_box.checked)
					checked_val=1;
				else
					checked_val=0;
			else
				checked_val=1;
		}
		if(checked_val)
		{
			if(gadget_name == "partner_mtongue" || gadget_name == "partner_education")
			{
				var subcat_label = get_category_label(original_arr[i].value,"checked");
				if(gadget_name == "partner_mtongue")
				{
				mton_sel+="'"+original_arr[i].value+"',";
				document.getElementById("mton_sel").value=mton_sel;
				}

			}
			else if(gadget_name == "partner_country")
			{
				countries.push(original_arr[i].value);
			}
		/*	else if(gadget_name == "partner_caste")
	                {
				/*if(original_arr[i].value=='174' && page=='AS')
					document.getElementById("jain").style.display='none';
	                        if(document.getElementById("partner_caste_selected").value)
	                                document.getElementById("partner_caste_selected").value+=",'"+original_arr[i].value+"'";
	                        else
	                                document.getElementById("partner_caste_selected").value="'"+original_arr[i].value+"'";
	                }
			else if(gadget_name == "partner_city")
	                {
	                        if(document.getElementById("partner_city_selected").value)
	                                document.getElementById("partner_city_selected").value+=",'"+original_arr[i].value+"'";
	                        else
	                                document.getElementById("partner_city_selected").value="'"+original_arr[i].value+"'";
	                }
			/*else if(gadget_name == "partner_mstatus")
			{
				if(original_arr[i].value=='N')
				{
					document.getElementById("Have_child").style.display='none';
				}
				else 
				{
					document.getElementById("Have_child").style.display='block';
				}
			}*/
			else if(gadget_name == "partner_religion")
                        {
				religions.push(original_arr[i].value);
				var rel_arr=original_arr[i].value.split('|X|');
                                if(rel_arr[0]=='1' && !other)
                                {
                                        document.getElementById("caste").style.display='block';
                                        document.getElementById("rel_caste").innerHTML="Partner Caste :";
                                        other=1; hindu=1;
                                }
                                else if(rel_arr[0]=='9' && (!other || hindu))
                                {
																				document.getElementById("caste").style.display='block';
                                        document.getElementById("rel_caste").innerHTML="Partner Caste :";
                                        hindu=0;
                                        other=1;
                                }
                                else if(rel_arr[0]=='4' && (!other || hindu))
                                {
                                        document.getElementById("caste").style.display='block';
                                        document.getElementById("rel_caste").innerHTML="Partner Caste :";
                                        hindu=0;
                                        other=1;
                                }
                                else if(rel_arr[0]=='2' && !other)
                                {
                                        document.getElementById("caste").style.display='block';
                                        document.getElementById("rel_caste").innerHTML="Partner Sect :";
                                        other=1;hindu=0;
                                }
                                else if(rel_arr[0]=='5' && !other)
                                {
                                        other=1;hindu=0;
                                }
                                else if(rel_arr[0]=='3' && !other)
                                {
                                        document.getElementById("caste").style.display='block';
                                        document.getElementById("rel_caste").innerHTML="Partner Sect :";
                                        other=1;hindu=0;
                                }
                                else
                                {
																				
                                        document.getElementById("caste").style.display='none';
                                        other=1;hindu=0;
                                }
                                
                        }

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
					to_write_checked_str.push("\" name=\"");
					to_write_checked_str.push(gadget_name);
					to_write_checked_str.push("_displaying_arr[]\"");
					to_write_checked_str.push("onmouseover=\"highlight(this,'ON');\"");
					to_write_checked_str.push("onmouseout=\"highlight(this,'OFF');\"");
				
					to_write_checked_str.push("onclick=\"");
					/*if(page=='AS')
						to_write_checked_str.push("change_div_class(this); ");*/
					to_write_checked_str.push("remove_checkboxes(this);\" >");
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
		else
		{
			if(gadget_name == "partner_mtongue" || gadget_name== "partner_education")
			{
				subcat_label = get_category_label(original_arr[i].value,"unchecked");
				if(gadget_name == "partner_mtongue")
				{
				if(mton_sel=="'"+original_arr[i].value+"'")
				document.getElementById("mton_sel").value=mton_sel.replace("'"+original_arr[i].value+"'",'');
				}
				//if(original_arr[i].value=="10,19,33,7,28,13" && !flag_all)
                                  //      continue;
			}
			/*else if(gadget_name == "partner_caste" && page=='AS')
                        {
                                if(original_arr[i].value=='174')
                                        document.getElementById("jain").style.display='block';
			}*/
			/*if(gadget_name == "partner_income")
			{
				if(original_arr[i].value == "DM")
					display_checkbox = 1;
				else
					display_checkbox = partner_income_checkbox(original_arr[i].value);
			}
			else*/
				display_checkbox = 1;

			if(typeof(subcat_label) != "undefined")
			{
				to_write_unchecked_str.push("<span style=\"color:#0a89fe\">");
				to_write_unchecked_str.push(subcat_label);
				to_write_unchecked_str.push("</span>");
				to_write_unchecked_str.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
				if(gadget_name == "partner_mtongue" && original_arr[i].value=="10,19,33,7,28,13,41" && flag_all)
					skip_allhindi=1;
			}
			if(original_arr[i].value.indexOf("|#|") < 0 && display_checkbox && original_arr[i].value!="DM" && !skip_allhindi)
			{
				if(in_array(original_arr[i].value,priority_arr))
				{
					priority_str.push("<input type=\"checkbox\" name=\"");
					priority_str.push(gadget_name);
					priority_str.push("_displaying_arr[]\" id=\"");
					priority_str.push(gadget_name);
					priority_str.push("_displaying_");
					priority_str.push(original_arr[i].value);
					priority_str.push("\" value=\"");
					priority_str.push(original_arr[i].value);
					priority_str.push("\" class=\"chbx checkboxalign\" onclick=\"");
					priority_str.push("add_checkboxes(this);\" >");
					priority_str.push("<label id=\"");
					priority_str.push(gadget_name);
					priority_str.push("_displaying_label_");
					priority_str.push(original_arr[i].value);
					priority_str.push("\" >");
					priority_str.push(label);
					priority_str.push("</label><br />");

				}
				to_write_unchecked_str.push("<input type=\"checkbox\" name=\"");
				to_write_unchecked_str.push(gadget_name);
				to_write_unchecked_str.push("_displaying_arr[]\" id=\"");
				to_write_unchecked_str.push(gadget_name);
				to_write_unchecked_str.push("_displaying_");
				to_write_unchecked_str.push(original_arr[i].value);
				to_write_unchecked_str.push("\" value=\"");
				to_write_unchecked_str.push(original_arr[i].value);
				if(gadget_name == "partner_handicapped" || gadget_name == "spoken_languages")
					to_write_unchecked_str.push("\" class=\"chbx checkboxalign\" onclick=\"add_checkboxes(this); \" \/>");
				else
				{
					to_write_unchecked_str.push("\" class=\"chbx checkboxalign\" onclick=\"");
                                        /*if(page=='AS')
                                                to_write_unchecked_str.push("change_div_class(this); ");*/
                                        to_write_unchecked_str.push("add_checkboxes(this);\" >");
				}
				to_write_unchecked_str.push("<label id=\"");
				to_write_unchecked_str.push(gadget_name);
				to_write_unchecked_str.push("_displaying_label_");
				to_write_unchecked_str.push(original_arr[i].value);
				to_write_unchecked_str.push("\" >");
				to_write_unchecked_str.push(label);
				to_write_unchecked_str.push("</label><br />");
			}
		}
	}
	
	if(to_write_unchecked_str.length  == 0)
		document.getElementById(gadget_name + "_select_all").style.color = '#9C9C9C';
	else
		document.getElementById(gadget_name + "_select_all").style.color = '';
	 if(to_write_checked_str.length  == 0)
                document.getElementById(gadget_name + "_clear_all").style.color = '#9C9C9C';
        else
                document.getElementById(gadget_name + "_clear_all").style.color = '';

		
	document.getElementById(gadget_name + "_target_div").innerHTML = to_write_checked_str.join('');
	if(priority_str!='' && priority_str!='undefined')
		document.getElementById(gadget_name + "_source_div").innerHTML = priority_str.join('')+"<div class=\"dhrow\"><span style=\"color: rgb(10, 137, 254);\">------</span></div>"+to_write_unchecked_str.join('');
	else
		 document.getElementById(gadget_name + "_source_div").innerHTML = to_write_unchecked_str.join('');
	if(gadget_name == "partner_religion")
	{
		populate_caste_from_religion_as(religions);
		
		if(firstTimeCasteUpdate)
		{
			//firstTimeCasteUpdate=0;
			if(!!("SelectedCaste"))
				SelectedCaste();
		}
	}
	if(document.getElementById("tieup_source").value=='ofl_prof')
	        data2string(document.form1);
	        
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

function restore_checkboxes(got_gadget_name)
{
	if(got_gadget_name)
                is_gadget(got_gadget_name);
	var curname = got_gadget_name + "_displaying_arr[]";
        if(document.getElementsByName(curname))
        {
		var field,field_val;
        	var fields = document.getElementsByName(curname);
        	var field = document.getElementsByName(got_gadget_name + "_arr[]");
		var c1= fields.length;
		for(var c=0;c<c1;c++)
		{
			if(fields[c].checked==true)
			{
			///	field_val=fields[c].id.split("_");
                        //	field = gadget_name + "_" + field_val[field_val.length-1];
			//	document.getElementById(field).checked=true;
				fields[c].checked= false;
			}
			
		}
		var c2=field.length;
			for(var c=0;c<c2;c++)
			{
				if(field[c].checked!=true)
				{
					field[c].checked= false;
				}
			}
	}
return true;

}
function highlight(obj,on_or_off)
{
	if(on_or_off == "ON")
	{
		//document.getElementById(obj.id).className = "grcolor";
		$(obj).removeClass().addClass("grcolor");
		var img_id=obj.id;
		var img_id = obj.id.replace(/link/,"image");
		if(document.getElementById(img_id))
		document.getElementById(img_id).src = document.getElementById(img_id).src.replace(/_gray/,"_blue");
	}
	else if(on_or_off == "OFF")
	{
		$(obj).removeClass();
		//document.getElementById(obj.id).className = "";
		var img_id = obj.id.replace(/link/,"image");
		if(document.getElementById(img_id))
		document.getElementById(img_id).src = document.getElementById(img_id).src.replace(/_blue/,"_gray");
	}
}

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

//defining arrays for different sections to use when changing div class.
var basic_detail_arr = new Array("Min_Age","Max_Age","Min_Height[]","Max_Height[]","partner_mstatus_displaying_arr[]","partner_mstatus_select_all","partner_mstatus_clear_all");
var rel_ethnic_arr = new Array("partner_mtongue_displaying_arr[]","partner_mtongue_select_all","partner_mtongue_clear_all","partner_religion_displaying_arr[]","partner_religion_select_all","partner_religion_clear_all","partner_caste_displaying_arr[]","partner_caste_select_all","partner_caste_clear_all");

var partner_fields_array = new Array("partner_mstatus","partner_religion","partner_mtongue");
$("input[type=checkbox]").bind("click",function(){add_checkboxes(this);});
var registration={
		'a' : function(element){
                        element.onclick = function(){
                                var current_id = this.id
                                if(this.id.match("_select_all"))
                                {
                                        add_checkboxes(this);
                                        return false;
                                }
                                else if(this.id.match("_clear_all"))
                                {
                                        remove_checkboxes(this);
                                        return false;
                                }
                        }
                        element.onfocus = function(){
                                //change_div_class(this);
                        }
                }
                
};
Behaviour.register(registration);


//Function to change div class i.e to change the background color depending on focus
function change_div_class(obj)
{
	if(in_array(obj.name,basic_detail_arr) || in_array(obj.id,basic_detail_arr))
	{
		document.getElementById("mstatus_label").className="lf b t12";
		
		document.getElementById("rel_label").className="lf gray b t12";
                document.getElementById("mtongue_label").className="lf gray b t12";
                document.getElementById("rel_caste").className="lf gray b t12";
	}
	else if(in_array(obj.name,rel_ethnic_arr) || (in_array(obj.id,rel_ethnic_arr)))
        {
		document.getElementById("mstatus_label").className="lf gray b t12";

		document.getElementById("rel_label").className="lf b t12";
		document.getElementById("mtongue_label").className="lf b t12";
		document.getElementById("rel_caste").className="lf b t12";
        }
}

/*Function to show caste depending on selected religion*/
function populate_caste_from_religion_as(religions)
{
	var hidden_vals = new Array();
	var shown_vals = new Array();
	var mapped_caste_arr = new Array();
	var priority_vals = new Array();
	var j1= religions.length;
	var others;
	hidden_vals.push("<input type=\"hidden\" name=\"partner_caste_str\" id=\"partner_caste_str\" value=\"\">");
        hidden_vals.push(" <input type=\"checkbox\" value=\"DM\" name=\"partner_caste_arr[]\" id=\"partner_caste_DM\"> <label id=\"partner_caste_label_DM\">Any</label><br>");
	if(document.getElementById('partner_caste_selected').value)
	{
		 mapped_caste_arr=document.getElementById('partner_caste_selected').value.split(",");
	}
	for( var j=0; j<j1;j++)
	{
		religion_value=religions[j];
		if(religion_value!='DM')
		{
			var caste_arr = religion_value.split("|X|");
			if(caste_arr[0]=='1')
				others='242';
			else if(caste_arr[0]=='3')
				others='244';
			else if(caste_arr[0]=='9')
				others='246';
			else if(caste_arr[0]=='2')
				others='243';
			else if(caste_arr[0]=='4')
				others='245';
		
			var caste_string = caste_arr[1].split("#");
			var i1 = caste_string.length;
			var caste_dropdown_array = new Array();
			var caste, caste_option,flag;
			var caste_r;
			for(var i=0;i<i1-1;i++)
			{
				caste = caste_string[i].split("$");
				caste_r="'"+caste[0]+"'";
				if(in_array(caste_r,mapped_caste_arr))
				{
					priority_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_caste_displaying_arr[]\" id=\"partner_caste_displaying_");
                                        priority_vals.push(caste[0]);
                                        priority_vals.push("\" value=\"");
                                        priority_vals.push(caste[0]);
                                        priority_vals.push("\" onClick=\"add_checkboxes(this); ");
                                        priority_vals.push(" \"><label id=\"partner_caste_displaying_label_");
                                        priority_vals.push(caste[0]);
                                        priority_vals.push("\">");
                                        priority_vals.push(caste[1]);
                                        priority_vals.push("</label><br>");
					
				}
				if(!((caste[0]==14)||(caste[0]==149)||(caste[0]==154)||(caste[0]==173)||(caste[0]==2)))
				{
                        		caste[1]=caste[1].replace(/:/g,' ');
                        		hidden_vals.push("<input type=\"checkbox\" value=");
                        		hidden_vals.push(caste[0]);
                        		hidden_vals.push(" name=\"partner_caste_arr[]\" id=\"partner_caste_");
                        		hidden_vals.push(caste[0]);
                        		hidden_vals.push("\"> <label id=\"partner_caste_label_");
                        		hidden_vals.push(caste[0]);
                        		hidden_vals.push("\">");
                        		hidden_vals.push(caste[1]);
                        		hidden_vals.push("</label><br>");

					if(!document.getElementById("partner_caste_selected").value.match(caste[0]))
					{
                        		shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_caste_displaying_arr[]\" id=\"partner_caste_displaying_");
                        		shown_vals.push(caste[0]);
                        		shown_vals.push("\" value=\"");
                        		shown_vals.push(caste[0]);
                        		shown_vals.push("\" onClick=\"add_checkboxes(this); ");
					if(document.getElementsByName('type').value=='AS')
                        			shown_vals.push(" change_div_class(this);");
                        		shown_vals.push(" \"><label id=\"partner_caste_displaying_label_");
                        		shown_vals.push(caste[0]);
                        		shown_vals.push("\">");
                        		shown_vals.push(caste[1]);
                        		shown_vals.push("</label><br>");
					}
				}
			}
		}
	}
	hidden_vals.push(" <input type=\"checkbox\" value=\"");
	hidden_vals.push(others);
	hidden_vals.push("\" name=\"partner_caste_arr[]\" id=\"partner_caste_");
	hidden_vals.push(others);
	hidden_vals.push("\"> <label id=\"partner_caste_label_");
	hidden_vals.push(others);
	hidden_vals.push("\">Others</label><br>");

	shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_caste_displaying_arr[]\" id=\"partner_caste_displaying_");
	shown_vals.push(others);
	shown_vals.push("\" value=\"");
	shown_vals.push(others);
	shown_vals.push("\" onClick=\"add_checkboxes(this);\"><label id=\"partner_caste_displaying_label_");
	shown_vals.push(others);
	shown_vals.push("\">Others</label><br>");

	document.getElementById("partner_caste_div").innerHTML = hidden_vals.join('');
	if(priority_vals!='' && priority_vals!='undefined')
        	document.getElementById("partner_caste_source_div").innerHTML = priority_vals.join('')+"<div class=\"dhrow\"><span style=\"color: rgb(10, 137, 254);\">------</span></div>"+shown_vals.join('');
	else
		document.getElementById("partner_caste_source_div").innerHTML = shown_vals.join('');

	/*if(document.getElementById("partner_caste_selected").value)
        {
                document.getElementById("partner_caste_str").value=document.getElementById("partner_caste_selected").value;
                var fill_arr=  new Array('partner_caste');
                fill_details(fill_arr);
        }*/

}

/*populate city depending on country(for advance search)*/
function populate_city_new(country_selected)
{
	var j1=country_selected.length;
	var country;var country_arr;
	var city_value,city_label;
	var city_arr = new Array();
	var hidden_vals = new Array();
	var shown_vals = new Array();
	var j1=country_selected.length;
	hidden_vals.push(" <input type=\"checkbox\" value=\"DM\" name=\"partner_city_arr[]\" id=\"partner_city_DM\"> <label id=\"partner_city_label_DM\">Any</label><br>");
	for (var j=0;j<j1;j++)
	{	
		var city_arr= country_selected[j].split("#");
		var country=city_arr[0];
		var i1 = city_arr.length;
		var city= new Array();
		for (var i=1;i<i1;i++)
		{
			city=city_arr[i].split("|");
			city_value=city[1];
			city_label=city[0];
			city_label=city_label.replace(/:/g,' ');
			hidden_vals.push("<input type=\"checkbox\" value=");
			hidden_vals.push(city_value);
			hidden_vals.push(" name=\"partner_city_arr[]\" id=\"partner_city_");
			hidden_vals.push(city_value);
			hidden_vals.push("\"> <label id=\"partner_city_label_");
			hidden_vals.push(city_value);
			hidden_vals.push("\">");
			hidden_vals.push(city_label);
			hidden_vals.push("</label><br>");
		
			shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_");
			shown_vals.push(city_value);
			shown_vals.push("\" value=");
			shown_vals.push(city_value);
			shown_vals.push(" onClick=\"add_checkboxes(this); ");
                        if(document.getElementsByName('type').value=='AS')
                        	shown_vals.push(" change_div_class(this);");
			shown_vals.push(" \"><label id=\"partner_city_displaying_label_");

			shown_vals.push(city_value);
			shown_vals.push("\">");
			shown_vals.push(city_label);
			shown_vals.push("</label><br>");
			
		}
	}
	hidden_vals.push("<input type=\"checkbox\" value=\"0\" name=\"partner_city_arr[]\" id=\"partner_city_0\"> <label id=\"partner_city_label_0\">Others</label><br>");
	shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_0\" value=\"0\" onClick=\"add_checkboxes(this); ");
	if(document.getElementsByName('type').value=='AS')
        	shown_vals.push(" change_div_class(this);");
        shown_vals.push(" \"><label id=\"partner_city_displaying_label_0\">Others</label><br>");	
	document.getElementById("partner_city_div").innerHTML = hidden_vals.join('');
	document.getElementById("partner_city_source_div").innerHTML = shown_vals.join('');
/*	if(document.getElementById("partner_city_selected").value)
        {
                document.getElementById("partner_city_str").value=document.getElementById("partner_city_selected").value;
                var fill_arr=  new Array('partner_city');
                fill_details(fill_arr);
        }
*/
}

function data2string()
{
	var j=0;
        var strSubmit       = '';
        var formElem;
        var strLastElemName = '';
        var str= '';
	var i1;
        strSubmit ="Min_Age="+document.getElementById('Min_Age').value+"&";
        strSubmit +="Max_Age="+document.getElementById('Max_Age').value+"&";
        strSubmit +="Min_Height="+document.getElementById('Min_Height').value+"&";
        strSubmit +="Max_Height="+document.getElementById('Max_Height').value+"&";
               
        var elem_arr= new Array('partner_mstatus','partner_mtongue','partner_religion','partner_caste');
	var sel_string=''; 
        for (j = 0; j < elem_arr.length; j++) 
        {
                formElem = elem_arr[j];
		is_gadget(formElem);
		i1 = original_arr.length;
		for(var i=0;i<i1;i++)
	        {
			if(original_arr[i].checked)
	                {
				if(sel_string=='')
					sel_string=original_arr[i].value;
				else
					sel_string+=","+original_arr[i].value;
			}
		}                
		strSubmit +=formElem+"="+sel_string+"&";
		sel_string='';
        }
	ajaxFunction(strSubmit); 
}
/*Function to fill certain details on page rethrow
function fill_details(fill_array)
{
        var curname, fields, fields_type, str_name, csv_str, to_tick_id;
        var csv_arr = new Array();
        var i1 = fill_array.length;
        for(var i=0;i<i1;i++)
        {
                curname = fill_array[i] + "_arr[]";
                if(document.getElementsByName(curname))
                {
                        fields = document.getElementsByName(curname);
                        fields_type = fields[0].type;

                        if(fields_type == "checkbox")
                        {
                                str_name = fill_array[i] + "_str";
                                csv_str = document.getElementById(str_name).value;
				if(csv_str && csv_str!='undefined')
                                {
                                        csv_str = rtrim(ltrim(csv_str,"'"),"'");
                                        csv_arr = csv_str.split("','");
                                        var j1 = csv_arr.length;
                                        for(var j=0;j<j1;j++)
                                        {
                                                to_tick_id = fill_array[i] + "_" + csv_arr[j];
                                                if(document.getElementById(to_tick_id))
                                                        document.getElementById(to_tick_id).checked = true;
                                        }
                                        swap_checkboxes(fill_array[i]);
                                }
                                else
                                {
                                        restore_checkboxes(fill_array[i]);
                                }
                        }
                }
        }
}
*/

