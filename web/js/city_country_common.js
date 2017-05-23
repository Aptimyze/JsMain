function display_city_dd()
{
//	fetch_code("COUNTRY",docF.country_residence.value);
	//making pincode blank if city is changed
		if(dID("pincode"))
			$("#pincode").val("");
	
	if(dID("state_code"))
		document.getElementById('state_code').value="";
	if(docF.country_code){
	if(docF.country_code.value =='+91'){
		if(dID("pincodeid"))
			dID("pincodeid").style.display="block";
		if(dID("parent_pincodeid"))
			dID("parent_pincodeid").style.display="block";
	}
	else{
		if(dID("pincodeid"))
			dID("pincodeid").style.display="none";
		if(dID("parent_pincodeid"))
			dID("parent_pincodeid").style.display="none";
	}

	if(docF.country_code.value != '+1' && docF.country_code.value != '+91'){
	}
}
	populate_city();

}
/*Function to fetch the code depending on city/country*/
function fetch_code(code_for,value)
{
	if(code_for == "COUNTRY")
	{
		var country_code_arr = value.split("|}|");
		if(docF.country_code)
			docF.country_code.value = country_code_arr[0];
		if(docF.country_code_mob)
			docF.country_code_mob.value = country_code_arr[0];
		if(docF.country_code_mob1)
			docF.country_code_mob1.value = country_code_arr[0];
		if(docF.phone_isd)
			docF.phone_isd.value=country_code_arr[0];
		if(docF.ALT_MOBILE_ISD)
		docF.ALT_MOBILE_ISD.value = country_code_arr[0];
	}
	else if(code_for == "CITY")
	{
		//making pincode blank if city is changed
		if(dID("pincode"))
			$("#pincode").val("");
		var city_code_arr = value.split("|{|");
		if(docF.state_code)
			docF.state_code.value = city_code_arr[0];
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
		if(docF.country_residence)
		{
			var country_drop = docF.country_residence;
			
			var country_val_arr = country_drop.value.split("|X|");
			if(!country_val_arr[1])
			{
				if(dID("city_res_show_hide"))
					dID("city_res_show_hide").style.display='none';
				if(dID("city_residence"))	
					dID("city_residence").value='';
				if(dID("city_residence_submit_err"))				
					dID("city_residence_submit_err").style.display='none';
				return 1;
			}
			else
			{
				if(dID("city_res_show_hide"))
					dID("city_res_show_hide").style.display='inline';
			}
			if(country_val_arr[1])
			{
				var city_label_value_arr = country_val_arr[1].split("#");
				var i1 = city_label_value_arr.length;
				pop_city_array.push("<select size=\"1\" name=\"city_residence\" id=\"city_residence\" onchange=\"fetch_code('CITY',this.value);\" style=\"width:185px;\">");
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
			if(country_val_arr[0])
			country_val_in=country_val_arr[0].split("|}|");
			if(country_val_in[1]=='51' && !city_residence_selected)
				fetch_code('CITY',dID("city_residence").value);
		}
	}
}
function check_pincode(pos)
{
	if(pos==1){
		pincode=docF.pincode.value;
		if(pincode && (pincode.length != 6 || !/^\d*$/.test(pincode))){
			dID("pincode_span").style.display="block";
			dID("pincode").focus();
			return false;
		}
		else
			dID("pincode_span").style.display="none";
	}
	if(pos==2){
		parent_pincode=docF.parent_pincode.value;
		if(parent_pincode && (parent_pincode.length != 6 || !/^\d*$/.test(parent_pincode))){
			dID("parent_pincode_span").style.display="block";
			dID("parent_pincode_span").focus();
			return false;
		}
		else
			dID("parent_pincode_span").style.display="none";
	}
	return true;
}
function change_isd(val,frm){
		docF.country_code.value = val;
		docF.phone_isd.value = val;
		docF.country_code_mob.value = val;
		docF.country_code_mob1.value = val;
		if(docF.ALT_MOBILE_ISD)
			docF.ALT_MOBILE_ISD.value = val;
		docF.isd_change_src.value= frm;
}
