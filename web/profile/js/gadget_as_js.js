//var gadget_array = new Array("partner_degree","partner_income");
var gadget_array = scroller_arr;
var gadget_name = "";
var original_name = "";
var original_arr = new Array();
var display_arr = new Array();
var checked_subcat_label_array = new Array();
var unchecked_subcat_label_array = new Array();
var flag_all=0;

if(window.location.href.search("advance_search.php")!=-1){
    var advanceSearchChanges=1;
}
else{
    var advanceSearchChanges=0;
}
if(advanceSearchChanges==1){
	var cityRight=0
	var stateRight=0;
	var cityLeft=0;
	var stateleft=0;
}
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
			if(gadget_name=='partner_mtongue')
			{
				document.getElementById("mton_sel").value=document.getElementById("mton_sel").value+"'"+clicked_arr[clicked_arr.length-1]+"',";
				if(to_tick_id=='partner_mtongue_10,19,33,7,28,13,41')
				{
						document.getElementById('partner_mtongue_10').checked=true;
						if(document.getElementById('partner_mtongue_displaying_10'))
						document.getElementById('partner_mtongue_displaying_10').checked=true;
						document.getElementById('partner_mtongue_41').checked=true;
						if(document.getElementById('partner_mtongue_displaying_41'))
						document.getElementById('partner_mtongue_displaying_41').checked=true;
						document.getElementById('partner_mtongue_19').checked=true;
						if(document.getElementById('partner_mtongue_displaying_19'))
						document.getElementById('partner_mtongue_displaying_19').checked=true;
						document.getElementById('partner_mtongue_33').checked=true;
						if(document.getElementById('partner_mtongue_displaying_33'))
						document.getElementById('partner_mtongue_displaying_33').checked=true;
						document.getElementById('partner_mtongue_7').checked=true;
						if(document.getElementById('partner_mtongue_displaying_7'))
						document.getElementById('partner_mtongue_displaying_7').checked=true;
						document.getElementById('partner_mtongue_28').checked=true;
						if(document.getElementById('partner_mtongue_displaying_28'))
						document.getElementById('partner_mtongue_displaying_28').checked=true;
						document.getElementById('partner_mtongue_13').checked=true;
						if(document.getElementById('partner_mtongue_displaying_13'))
						document.getElementById('partner_mtongue_displaying_13').checked=true;
						document.getElementById('partner_mtongue_10,19,33,7,28,13,41').checked=false;
						flag_all=1;
				}
				else
					flag_all=0;
			}
			if(gadget_name=='partner_city')
			{
					document.getElementById(obj.id).checked = true;
					if(to_tick_id=='partner_city_NCR')
					{
						document.getElementById('partner_city_DE00').checked=true;
                                                if(document.getElementById('partner_city_displaying_DE00'))
                                                document.getElementById('partner_city_displaying_DE00').checked=true;
						document.getElementById('partner_city_HA03').checked=true;
                                                if(document.getElementById('partner_city_displaying_HA03'))
                                                document.getElementById('partner_city_displaying_HA03').checked=true;
						document.getElementById('partner_city_UP25').checked=true;
                                                if(document.getElementById('partner_city_displaying_UP25'))
                                                document.getElementById('partner_city_displaying_UP25').checked=true;
						document.getElementById('partner_city_NCR').checked=false;
                                                if(document.getElementById('partner_city_displaying_NCR'))
                                                document.getElementById('partner_city_displaying_NCR').checked=false;

						//added by lavesh
                                                document.getElementById('partner_city_HA02').checked=true;
                                                if(document.getElementById('partner_city_displaying_HA02'))
                                                document.getElementById('partner_city_displaying_HA02').checked=true;

                                                document.getElementById('partner_city_UP47').checked=true;
                                                if(document.getElementById('partner_city_displaying_UP47'))
                                                document.getElementById('partner_city_displaying_UP47').checked=true;

                                                document.getElementById('partner_city_UP48').checked=true;
                                                if(document.getElementById('partner_city_displaying_UP48'))
                                                document.getElementById('partner_city_displaying_UP48').checked=true;

                                                document.getElementById('partner_city_UP12').checked=true;
                                                if(document.getElementById('partner_city_displaying_UP12'))
                                                document.getElementById('partner_city_displaying_UP12').checked=true;
						//added by lavesh

					}

					//------------->>>>>>>>>>>>>>>>>>>>
					if(to_tick_id=='partner_city_MNCR')
					{
						document.getElementById('partner_city_MH04').checked=true;
						if(document.getElementById('partner_city_displaying_MH04'))
						document.getElementById('partner_city_displaying_MH04').checked=true;

						document.getElementById('partner_city_MH12').checked=true;
						if(document.getElementById('partner_city_displaying_MH12'))
						document.getElementById('partner_city_displaying_MH12').checked=true;

						document.getElementById('partner_city_MH28').checked=true;
						if(document.getElementById('partner_city_displaying_MH28'))
						document.getElementById('partner_city_displaying_MH28').checked=true;

						document.getElementById('partner_city_MH29').checked=true;
						if(document.getElementById('partner_city_displaying_MH29'))
						document.getElementById('partner_city_displaying_MH29').checked=true;

						document.getElementById('partner_city_MNCR').checked=false;
						if(document.getElementById('partner_city_displaying_MNCR'))
						document.getElementById('partner_city_displaying_MNCR').checked=false;

					}
					//------------->>>>>>>>>>>>>>>>>>>>
			}
			if(gadget_name=='partner_diet')
                        {
				document.getElementById(obj.id).checked = true;
				if(to_tick_id=='partner_diet_V')
				{
					// for edit dpp
					if(document.getElementById('partner_diet_J'))
						document.getElementById('partner_diet_J').checked=true;

					// For advanced search
					if(document.getElementById('partner_diet_displaying_J'))
                                                document.getElementById('partner_diet_displaying_J').checked=true;
				}
				if(to_tick_id=='partner_diet_E')
				{
					// for edit dpp
					if(document.getElementById('partner_diet_J'))
						document.getElementById('partner_diet_J').checked=true;
					if(document.getElementById('partner_diet_V'))
						document.getElementById('partner_diet_V').checked=true;

					// for advanced search
					if(document.getElementById('partner_diet_displaying_J'))
                                                document.getElementById('partner_diet_displaying_J').checked=true;
                                        if(document.getElementById('partner_diet_displaying_V'))
                                                document.getElementById('partner_diet_displaying_V').checked=true;
				}	
				if(to_tick_id=='partner_diet_N')
				{
					// for edit dpp
					if(document.getElementById('partner_diet_E'))
	                                        document.getElementById('partner_diet_E').checked=true;

					// for advanced search
					if(document.getElementById('partner_diet_displaying_E'))
                                                document.getElementById('partner_diet_displaying_E').checked=true;
				}
			}
			
			if(gadget_name=='partner_smoke')
                        {
                                document.getElementById(obj.id).checked = true;
				if(to_tick_id=='partner_smoke_O')
				{
					// for edit dpp
					if(document.getElementById('partner_smoke_N'))
						document.getElementById('partner_smoke_N').checked=true;

					// for advanced search
					if(document.getElementById('partner_smoke_displaying_N'))
						document.getElementById('partner_smoke_displaying_N').checked=true;
				}
				if(to_tick_id=='partner_smoke_Y')
				{
					// for edit dpp
					if(document.getElementById('partner_smoke_O'))
						document.getElementById('partner_smoke_O').checked=true;

					// for advanced search
					if(document.getElementById('partner_smoke_displaying_O'))
						document.getElementById('partner_smoke_displaying_O').checked=true;
				}
				
			}
	
			if(gadget_name=='partner_drink')
                        {
                                document.getElementById(obj.id).checked = true;
				if(to_tick_id=='partner_drink_O')
				{
					// for edit dpp
					if(document.getElementById('partner_drink_N'))
						document.getElementById('partner_drink_N').checked=true;

					// for advanced search
					if(document.getElementById('partner_drink_displaying_N'))
						document.getElementById('partner_drink_displaying_N').checked=true;
				}
				if(to_tick_id=='partner_drink_Y')
				{
					// for edit dpp
					if(document.getElementById('partner_drink_O'))
						document.getElementById('partner_drink_O').checked=true;

					// for advanced search
					if(document.getElementById('partner_drink_displaying_O'))
						document.getElementById('partner_drink_displaying_O').checked=true;
				}
				
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
			for(var i=0;i<i1;i++)
				if(original_arr[i].value == "DM")
					original_arr[i].checked = false;
				else
				{
					original_arr[i].checked = true;
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
			document.getElementById(to_untick_id).checked = false;
			if(gadget_name=='partner_mtongue')
			{
                                document.getElementById("mton_sel").value=document.getElementById("mton_sel").value.replace("'"+clicked_arr[clicked_arr.length-1]+"'",'');
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
                /*
                    if(gadget_name=="partner_city" && advanceSearchChanges==1)
                    {
                      $("#partner_city_target_div span").remove();  
                    }
                */
	}
}

function swap_checkboxes(got_gadget_name,load)
{
        var docF=document.form1;
	if(got_gadget_name)
		is_gadget(got_gadget_name);

	var subcat_label,display_checkbox;
	var to_write_checked_str = new Array();
	var to_write_unchecked_str = new Array();
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
	var priority_str = new Array();
	if(document.getElementById("mton_sel"))
	var mton_sel=document.getElementById("mton_sel").value;
	if(gadget_name == "partner_caste")
        	document.getElementById("partner_caste_selected").value='';
	if(gadget_name == "partner_city")
	{
		var priority_arr=new Array();
        	document.getElementById("partner_city_selected").value='';
        	var count_str=document.getElementById("partner_country_selected").value;
                if(count_str.match("'51#"))
		{
			//priority_arr=new Array('NCR','MH04','KA02','AP03','MH08','TN02','WB05');
			priority_arr=new Array('NCR','MNCR','KA02','AP03','MH08','TN02','WB05');
			if(advanceSearchChanges==1)
                            MajorCity=1;
		}
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
			if(page=='AS')
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
			else
				checked_val=1;
		}
                
                if(gadget_name == "partner_city" && advanceSearchChanges==1){
                    if(original_arr[i].value.length==4 && original_arr[i].value!="MNCR")
                    {
                        if(cityRight==0){
                            var subcat_label="All Indian Cities";
                            cityRight=1;
                            cityLeft=1;
                        }
                    }
                    else if(original_arr[i].value.length==2 && original_arr[i].value!="DM" && priority_arr.toString().search(original_arr[i].value)==-1){
                        if(stateRight==0){
                         var subcat_label="Indian States";
                            stateRight=1;
                            stateLeft=1;
                        }
                    }
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
                                if(document.getElementById("partner_country_selected").value)
                                        document.getElementById("partner_country_selected").value+=",'"+original_arr[i].value+"'";
                                else
                                        document.getElementById("partner_country_selected").value="'"+original_arr[i].value+"'";
			}
			else if(gadget_name == "partner_caste")
	                {
				if(original_arr[i].value=='174' && page=='AS')
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
			else if(gadget_name == "partner_mstatus" && page != 'filter')
			{
				if(original_arr[i].value=='N')
				{
					document.getElementById("Have_child").style.display='none';
				}
				else 
				{
					document.getElementById("Have_child").style.display='block';
				}
			}
			else if(gadget_name == "partner_religion")
                        {
				religions.push(original_arr[i].value);
				if(page=='AS')
				{
				var rel_arr=original_arr[i].value.split('|X|');
				if(rel_arr[0]=='1' && !other)
                                {
                                        document.getElementById("hindu").style.display='block';
					document.getElementById("caste").style.display='block';
					document.getElementById("rel_caste").innerHTML="Caste :";
					if(!hindu)
					{
						document.getElementById("jain").style.display='none';
                                        	document.getElementById("sikh").style.display='none';
					}
					other=1; hindu=1;	
                                }
				else if(rel_arr[0]=='9' && (!other || hindu))
                                {
                                        document.getElementById("jain").style.display='block';
					document.getElementById("caste").style.display='block';
					document.getElementById("rel_caste").innerHTML="Caste :";
					if(!hindu)
						document.getElementById("hindu").style.display='none';
					hindu=0;
					other=1;
                                }
				else if(rel_arr[0]=='4' && (!other || hindu))
                                {
                                        document.getElementById("sikh").style.display='block';
					document.getElementById("caste").style.display='block';
					document.getElementById("rel_caste").innerHTML="Caste :";
					if(!hindu)
						document.getElementById("hindu").style.display='none';
					hindu=0;
					other=1;
                                }
                              	else if(rel_arr[0]=='2' && !other)
				{
					document.getElementById("muslim").style.display='block';
					other=1;hindu=0;
				}
				else if(rel_arr[0]=='5' && !other)
                                {
                                        document.getElementById("parsi").style.display='block';
					other=1;hindu=0;
                                }
				else if(rel_arr[0]=='3' && !other)
                                {
                                        document.getElementById("caste").style.display='block';
                                        document.getElementById("rel_caste").innerHTML="Sect :";
					other=1;hindu=0;
                                }
	                        else
				{
					document.getElementById("caste").style.display='none';
					document.getElementById("muslim").style.display='none';
                                        document.getElementById("hindu").style.display='none';
                                        document.getElementById("sikh").style.display='none';
                                        document.getElementById("jain").style.display='none';
                                        document.getElementById("parsi").style.display='none';
                                        other=1;hindu=0;
				}
				}
 
                        }

				display_checkbox = 1;

			if(typeof(subcat_label) != "undefined")
			{
                            if(((gadget_name == "partner_city" && (cityLeft==1 || stateLeft==1) && advanceSearchChanges==1) || gadget_name != "partner_city")){
                                    
                                    if(gadget_name == "partner_city" && advanceSearchChanges==1){
                                        if(cityLeft==1)
                                            cityLeft=2;
                                        if(stateLeft==1)
                                            stateLeft=2;
                                    }
                                
                                    to_write_checked_str.push("<span style=\"color:#0a89fe\">");
                                    to_write_checked_str.push(subcat_label);
                                    to_write_checked_str.push("</span>");
                                    to_write_checked_str.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
                            }
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
					if(gadget_name == "partner_handicapped")
	                                {
        	                                nhandicap_flag=1;
                	                        document.getElementById("nature_handicapped").style.display='none';
                        	        }

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
					if(page=='AS')
						to_write_checked_str.push("change_div_class(this); ");
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
					if(gadget_name == "partner_handicapped" &&  !nhandicap_flag && (original_arr[i].value == "N" || original_arr[i].value == "3" || original_arr[i].value == "4"))
					{
                                                document.getElementById("nature_handicapped").style.display='none';
					}
					else if(gadget_name == "partner_handicapped")
					{
						nhandicap_flag=1;
						document.getElementById("nature_handicapped").style.display='block';
					}
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
			else if(gadget_name == "partner_caste" && page=='AS')
                        {
                                if(original_arr[i].value=='174')
                                        document.getElementById("jain").style.display='block';
			}
			else if(gadget_name == "partner_city")
			{ 
			        if(original_arr[i].value=='NCR')
                                {
                                        if(document.getElementById("partner_city_DE00").checked || document.getElementById("partner_city_HA03").checked || document.getElementById("partner_city_UP25").checked|| document.getElementById("partner_city_HA02").checked || document.getElementById("partner_city_UP12").checked || document.getElementById("partner_city_UP47").checked || document.getElementById("partner_city_UP48").checked)
                                                continue;
                                }
                                if(original_arr[i].value=='MNCR')
                                {
                                        if(document.getElementById("partner_city_MH04").checked || document.getElementById("partner_city_MH12").checked || document.getElementById("partner_city_MH28").checked|| document.getElementById("partner_city_MH29").checked )
                                                continue;
                                }

			}

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
                            if((gadget_name == "partner_city" && (cityRight==1 || stateRight==1) && advanceSearchChanges==1) || gadget_name != "partner_city"){
                                    
                                    if(gadget_name == "partner_city" && advanceSearchChanges==1){
                                        if(cityRight==1)
                                            cityRight=2;
                                        if(stateRight==1)
                                            stateRight=2;
                                    }
				to_write_unchecked_str.push("<span style=\"color:#0a89fe\">");
				to_write_unchecked_str.push(subcat_label);
				to_write_unchecked_str.push("</span>");
				to_write_unchecked_str.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
                            }
				if(gadget_name == "partner_mtongue" && original_arr[i].value=="10,19,33,7,28,13,41" && flag_all)
					skip_allhindi=1;
			}
			if(original_arr[i].value.indexOf("|#|") < 0 && display_checkbox && original_arr[i].value!="DM" && !skip_allhindi)
			{
				if(gadget_name == "partner_city" && in_array(original_arr[i].value,priority_arr))
                                {
                                        if(advanceSearchChanges==1){
                                            if(MajorCity==1){
                                                priority_str.push("<span style=\"color:#0a89fe\">Major Indian Cities</span><div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
                                                MajorCity++;
                                            }
                                        }
                                        priority_str.push("<input type=\"checkbox\" name=\"");
                                        priority_str.push(gadget_name);
                                        priority_str.push("_displaying_arr[]\" id=\"");
                                        priority_str.push(gadget_name);
                                        priority_str.push("_displaying_");
                                        priority_str.push(original_arr[i].value);
                                        priority_str.push("\" value=\"");
                                        priority_str.push(original_arr[i].value);
                                        priority_str.push("\" class=\"chbx checkboxalign\" onclick=\"");
					if(page=='AS')
                                                priority_str.push("change_div_class(this); ");
                                        priority_str.push("add_checkboxes(this);\" >");
                                        priority_str.push("<label id=\"");
                                        priority_str.push(gadget_name);
                                        priority_str.push("_displaying_label_");
                                        priority_str.push(original_arr[i].value);
                                        priority_str.push("\" finder=\""+label.toLowerCase()+"\" >");
                                        priority_str.push(label);
                                        priority_str.push("</label><br />");
					if(original_arr[i].value=='NCR' || original_arr[i].value=='MNCR')
						continue;
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
                                        if(page=='AS')
                                                to_write_unchecked_str.push("change_div_class(this); ");
                                        to_write_unchecked_str.push("add_checkboxes(this);\" >");
				}
				to_write_unchecked_str.push("<label id=\"");
				to_write_unchecked_str.push(gadget_name);
				to_write_unchecked_str.push("_displaying_label_");
				to_write_unchecked_str.push(original_arr[i].value);
				to_write_unchecked_str.push("\" ");
                                if(gadget_name=="partner_city")
                                    to_write_unchecked_str.push("finder=\""+label.toLowerCase()+"\" ");
				to_write_unchecked_str.push(">");
                                to_write_unchecked_str.push(label);
				to_write_unchecked_str.push("</label><br />");
			}
		}
	}
if(advanceSearchChanges==1){
	cityRight=0;
        stateRight=0;
        cityLeft=0;
        stateLeft=0;
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
        if(gadget_name=='partner_city' && advanceSearchChanges==1)
            var separatorJoin="";
        else
            var separatorJoin="<div class=\"dhrow\"><span style=\"color: rgb(10, 137, 254);\">------</span></div>";
	if(priority_str!='' && priority_str!='undefined')
                document.getElementById(gadget_name + "_source_div").innerHTML = priority_str.join('')+separatorJoin+to_write_unchecked_str.join('');
        else
		document.getElementById(gadget_name + "_source_div").innerHTML = to_write_unchecked_str.join('');
	if(gadget_name == "partner_country")
	{ 
            	if(countries[0]!='DM' && countries[0]!=undefined && ((countries[0].split("#")[0]=='51' && countries.length==1 && advanceSearchChanges==1) || (countries.length<=5 && advanceSearchChanges!=1)))
		{
			document.getElementById("city").style.display="block";
			if(load!='load') 
		 	populate_city_new(countries);
		}
		else
		{
			document.getElementById("city").style.display="none";
		}
	}
	if(gadget_name == "partner_religion")
	{
		populate_caste_from_religion_as(religions);
	}
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
            if(got_gadget_name!="partner_wstatus" && got_gadget_name!="partner_hchild" && got_gadget_name!="partner_hcity"){
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
                                        //if(field[c].checked==true)
                                        {
                                                field[c].checked= false;
                                        }
                                }
                }
            }
return true;

}
function highlight(obj,on_or_off)
{
	if(on_or_off == "ON")
	{
		document.getElementById(obj.id).className = "grcolor";
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

//swap_checkboxes("partner_occupation","load");
if(advanceSearchChanges==1){
	swap_checkboxes("partner_wstatus","load");
//	swap_checkboxes("partner_hchild","load");
}
