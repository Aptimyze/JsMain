var act_contact_id="";
var act_con_profilechecksum="";
var act_eoi_label="";
var act_par_span_label="";
function show_exp_layer(a,b,e)
{
        close_all_con_layer();
	check_window('hide_exp_layer()');
	check_checkbox(a,b);
	if(!e) var e=window.event;
	 e.stopPropagation();
        return false;
	
}
function show_details(contact_id,con_profilechecksum,force_focus,eoi_label,par_span_label)
{
	if(typeof(PH_UNVERIFIED_STATUS)!='undefined')
	{
		if(PH_UNVERIFIED_STATUS)
		{
			$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=CONTACT&flag=1'});
        	        return;
		}
	}	
        //If layer already opens
        if(act_contact_id)
                close_all_con_layer();
        check_window('close_all_con_layer()');
        common_check=1;
        function_to_call="close_all_con_layer()";
        act_contact_id=contact_id;
        act_con_profilechecksum=con_profilechecksum;
        act_eoi_label=eoi_label;
        act_par_span_label=par_span_label;
        if(contact_id && force_focus)
                set_focus_on_anchor("#"+contact_id);
	var profile_id=eoi_label.replace("SPAN_","");
	var send_url="?to_do=view_contact&show_contacts=&index=0&rand=444&ajax_error=2&from_search=1&profilechecksum="+con_profilechecksum+"&close_func=close_all_con_layer()&checksum="+prof_checksum+"&profile_url="+profile_id+"&prev_cache="+Math.round(Math.random()*1000);
        send_ajax_request('invoke_contact_engine.php'+send_url,'con_show_loader','append_contact_det');
//        var send_url="?ajax_error=2&from_search=1&ONLY_LAYER=1&profile_url="+profile_id+"&profilechecksum="+con_profilechecksum+"&checksum="+prof_checksum;
  //      send_ajax_request('get_details.php'+send_url,'con_show_loader','append_contact_det');		

	//added by manoranjan
	if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="block")
	{
		top.document.getElementById("browseBottom").style.visibility="hidden";
		top.document.getElementById("browseBottom").style.display="none";
		if(navigator.appName.indexOf("Internet Explorer") != -1){
			top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)+17;
		}else{
			top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight);
		}
				
	}        
return false;
}
function con_show_loader()
{
        if(act_contact_id)
        {
                var loader_data='<div id="show_contact" style="padding: 1px 0px; width: 282px; position: relative; z-index: 0; display: inline;" class="lf"><div class="lf"><img src="IMG_URL/img_revamp/cont_top_bg.gif"/></div><div style="padding: 2px 0pt 0pt 10px; width: 275px; height: 219px; position: relative;" class="lf cont_hr_bg"><div style="width: 260px;"><div class="dark_orange t14 b lf">Contact Details</div><div style="float: right;"><a href="#" class="blink" onclick="return close_all_con_layer()"><b>[ x ]</b></a></div><div class="sp5"></div><div align="center" id="LOGIN_TO_CON"><div style="height:50px">&nbsp;</div>&nbsp;&nbsp;<img src="IMG_URL/img_revamp/loader_big.gif"></div><div style="position: absolute; bottom: 5px; left: 10px;" class="t11"></div><div style="height: 8px;" class="sp12 width1"></div></div></div><div><img src="IMG_URL/img_revamp/cont_bottom_bg.gif"></div><div class="sp5 width1"></div></div>';
                dID(act_contact_id).innerHTML=loader_data;
                if(dID(act_eoi_label))
                        dID(act_eoi_label).style.position="";
                if(dID("hide_1"))
                        dID("hide_1").style.position="";
                if(dID(act_par_span_label))
                        dID(act_par_span_label).style.zIndex=1002;
        }
}

function append_contact_det()
{
        if(act_contact_id)
        {
                if(result=='A_E')
                        dID("LOGIN_TO_CON").innerHTML=common_error;
                else if(result=="LOGIN" || result=='Login')
                {
                        var func_to_call=escape("show_details('"+act_contact_id+"','"+act_con_profilechecksum+"',1,'"+act_eoi_label+"','"+act_par_span_label+"')");
                        close_all_con_layer();
                        var url = "login.php?SHOW_LOGIN_WINDOW=1&after_login_call="+func_to_call;
			$.colorbox({href:url});
                }
                else
                {
                        dID(act_contact_id).innerHTML=result;
                }

                //If show contact id exist
                if(dID("show_contact"))
                {
                        dID("show_contact").style.display='inline';
                        //dID("show_express").innerHTML="";
                        //dID("show_express").style.display="none";
		}
	}
}
function close_all_con_layer()
{

	if(typeof CloseContactLayer =="function")
	{
		//Defined in contact_engine_js
		CloseContactLayer(1);
	}
	
	if(typeof(showOrHideAllDropDowns)=="function")
                showOrHideAllDropDowns('visible');
	common_check=0;
	function_to_call="";
	if(dID(act_contact_id))
	{
			dID(act_contact_id).innerHTML="";
			act_contact_id="";
			act_con_profilechecksum="";
			if(dID(act_eoi_label))
					dID(act_eoi_label).style.position="relative";
			if(dID("hide_1"))
					dID("hide_1").style.position="relative";
			if(dID(act_par_span_label))
					dID(act_par_span_label).style.zIndex=0;

	}
    
        //added by manoranjan
		if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="none"){
			top.document.getElementById("browseBottom").style.visibility="visible";
			top.document.getElementById("browseBottom").style.display="block";
			top.scrollTo(0,0);
			if(top.iframeHeight != 0){
			if(navigator.appName.indexOf("Internet Explorer") != -1){
					top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-17;
				}else{
					top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-33;
				}	
			}
			
		}
		
        
        
        return false;
}
var font_img=new Array;
var font_img_ho=new Array;
font_img_ho[1]="IMG_URL/profile/images/a_ho.gif";
font_img_ho[2]="IMG_URL/profile/images/biga_ho.gif";
font_img_ho[3]="IMG_URL/profile/images/biggera_ho.gif";
font_img[1]="IMG_URL/profile/images/a.gif";
font_img[2]="IMG_URL/profile/images/biga.gif";
font_img[3]="IMG_URL/profile/images/biggera.gif";
function reduce_to(values)
{
        
        if(!values)
                return 1;
        var percent="12px";
        var no_rec="14px";
	var gray="12px";
        if(values==1)
        {
                
                percent="11px";
                no_rec="13px";
                gray="10px";
        }
        if(values==2)
        {
                percent="12px";
                no_rec="14px";
                gray="11px";
        }
        if(values==3)
        {
                percent="13px";
                no_rec="15px";
                gray="12px";
        }
	if(dID('font_1'))
		dID('font_1').src=font_img[1];
	if(dID('font_2'))
                dID('font_2').src=font_img[2];
	if(dID('font_3'))
                dID('font_3').src=font_img[3];
        eval("dID('font_"+values+"').src='"+font_img_ho[values]+"'");
        Set_Cookie( 'font_set', values,'' , '/', '', '' );
        document.body.style.fontSize=percent;
        //Required for no result page.
        if(dID("no_res"))
        {
                dID("no_res").style.fontSize=no_rec;
                dID("no_rec").style.fontSize=no_rec;
        }
}
var span_layer_id="";
var con_det_id="";
function check_checkbox(checkbox_id,sno)
{
	if(user_login=="" && sno!=-1)
	{
		if(span_layer_id)
                        span_layer_id.style.zIndex=0;
		var after_login_call="check_checkbox('"+checkbox_id+"','"+sno+"')";
		var url = 'login.php?SHOW_LOGIN_WINDOW=1&after_login_call='+after_login_call;
		$.colorbox({href:url});
		return false;
	}
	if(checkbox_id=='NO_ID')
	{
		do_not_check=1;
		//hide_exp_layer(1);
		common_check=1;
                function_to_call="hide_exp_layer(1)";
	}
	if(sno>=0 && checkbox_id!='NO_ID')
	{
		common_check=1;
        	function_to_call="hide_exp_layer()";
	}
	var is_chb_chk=0;
	var checkbox_check_cnt=0;
	for(i=0;i<10;i++)
        {
		eval("var check_box=dID('PROFILE_"+i+"')");
           	if(sno>=0)
		{
			//Getting the resource of particular checkbox
			eval("var exp_layer_id=dID('EXP_LAYER_"+i+"')");
			if(exp_layer_id)
				exp_layer_id.style.zIndex="1002";
			eval("var span_id=dID('SPAN_"+i+"')");
			if(span_id)
				span_id.style.zIndex="100";
			if(sno==i)
				span_id.style.zIndex="1000";
			if(exp_layer_id)
				if(!exp_layer_id.innerHTML)
					exp_layer_id.innerHTML="";
				else
					first_time_exp=exp_layer_id;
		}
		
                	//If actually exist that resource
	                if(check_box)
        	                //If checkbox is clicked or not
                	        if(check_box.checked)
                        	{
					checkbox_check_cnt++;
					if(checkbox_id=='NO_ID')
						is_chb_chk=1;
					else
						check_box.checked=false;
	                        }
        }
	if(is_chb_chk==0 && checkbox_id=='NO_ID')
	{
		call_tb_show("nothing_selected.php?width=520&height=120&TYPE=EOI");
		return false;		
	}
	
	//Hide dropdown if eoi layer is shown.
	if(typeof(showOrHideAllDropDowns)=="function")
                showOrHideAllDropDowns('hidden');
	//When no checkbox clicked
	if(checkbox_id!="NO")
	{
		if(dID(checkbox_id))
			dID(checkbox_id).checked=true;
		if(sno==11)
			span_layer_id=dID('hide_0');
		else
			eval("span_layer_id=dID('SPAN_"+sno+"')");
		if(span_layer_id)
			span_layer_id.style.zIndex=1000;
		if(sno>=0)
		{
			if(first_time_exp.innerHTML=="")
				first_time_exp=dID("exp_layer");
			
			eval("var exp_layer_ch_id=dID('EXP_LAYER_"+sno+"')");
			show_with_opacity(exp_layer_ch_id,first_time_exp);
			eval("current_exp_layer=dID('EXP_LAYER_"+sno+"');");
			first_time_exp.innerHTML="";
			if(dID("multi_button"))
			{
				
				dID("multi_button").focus();
				if(!dID("text_id").disabled)
					dID("text_id").focus();
			}
			
			change_message(dID('message_id').options[0].value);
			start=0;
		}
	}
	if(checkbox_check_cnt<=1)
	{
		if(dID("SINGLE_EOI"))
                        dID("SINGLE_EOI").style.display='block';
                if(dID("MULTI_EOI"))
                        dID("MULTI_EOI").style.display='none';	
	}
	else
	{
		if(dID("SINGLE_EOI"))	
			dID("SINGLE_EOI").style.display='none';
		if(dID("MULTI_EOI"))
                        dID("MULTI_EOI").style.display='block';
	}
	id_check.value=1;
	//added by manoranjan for hideing chat bar
		 if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="block")
		{
			top.document.getElementById("browseBottom").style.visibility="hidden";
			top.document.getElementById("browseBottom").style.display="none";
			if(navigator.appName.indexOf("Internet Explorer") != -1){
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)+17;
			}else{
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight);
			}
			
		}
	
	
	return false;	
}
var do_not_check=0;
function call_tb_show(nothing_selected_url)
{
	common_check=0;
	function_to_call="";
	$.colorbox({href:nothing_selected_url});
}
//allow_exp set to 1 if to prevent uncheck and function overwritten
//Allow_exp eq '' than function is called for removal of layer
//allow_exp eq 2 if only layer to remove not checkbox
function hide_exp_layer(allow_exp)
{
	close_all_con_layer();
	if(typeof(showOrHideAllDropDowns)=="function")
		showOrHideAllDropDowns('visible');	//Variable required in check_window function
        if(!allow_exp || allow_exp==2)
	{
		common_check=0;
	        function_to_call="";
	}
	
	if(allow_exp==1)
	{
		function_to_call="";
		common_check=0;
	}
	if(dID("hide_0"))
		dID('hide_0').style.zIndex=0;
	var exp_layer_id=dID("exp_layer");
	if(span_layer_id)
		span_layer_id.style.zIndex=0;
	if(exp_layer_id.innerHTML=="")
	{
		if(typeof(current_exp_layer)!='undefined')
		{
			if(current_exp_layer.innerHTML)
			{
				exp_layer_id.innerHTML=current_exp_layer.innerHTML;
				current_exp_layer.innerHTML="";
				current_exp_layer=exp_layer_id;
				first_exp_layer=exp_layer_id;
				if(do_not_check==0 && allow_exp!=2)
					uncheck_all();
				if(allow_exp)
				{	
					do_not_check=0;
					if(allow_exp==1)
						uncheck_all();
				}
				else
					uncheck_all();
			}
			start=0;
		}	
	}
	else
	{
		current_exp_layer=exp_layer_id;
		first_exp_layer=current_exp_layer;
		if(allow_exp !=1 && allow_exp>0)
		{       
			do_not_check=0;
		}
		else
			uncheck_all();		
	}
		
	//added by manoranjan
	if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="none")
	{
		top.document.getElementById("browseBottom").style.visibility="visible";
		top.document.getElementById("browseBottom").style.display="block";
		top.scrollTo(0,0);
		if(top.iframeHeight != 0){
			if(navigator.appName.indexOf("Internet Explorer") != -1){
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight);
			}else{
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-33;
			}	
		}
		
		
	}
	
		
	return false;

}

function uncheck_all()
{
	if(dID("PROFILE_0"))
        {       
                for(i=0;i<10;i++)
                {
                        //Getting the resource of particular checkbox
                        eval("check_box=dID('PROFILE_"+i+"')");
			if(check_box)
				check_box.checked=false;
			
		}
	}
}

function checkbox_checked()
{
	var id="";
	if(dID("PROFILE_0"))
	{	
		for(i=0;i<10;i++)
	        {
        	        //Getting the resource of particular checkbox
                	eval("var check_box=dID('PROFILE_"+i+"')");
	                //If actually exist that resource
        	        if(check_box)
                	        //If checkbox is clicked or not
                        	if(check_box.checked)
	                        {
        	                        if(id=="")
						id="PROFILE_"+i;
					else
						id=id+"-----PROFILE_"+i;
	                        }
        	}
	}
	return id;
}
//Variable required to set opacity.
var start_opaque=1;
//Layer id to opaque
var id_to_opaque="";
function show_with_opacity(first_id,second_id)
{
	id_to_opaque=first_id;
	id_to_opaque.style.opacity=start_opaque/10;
	id_to_opaque.style.filter='alpha(opacity=' + start_opaque*10 + ')';
	first_id.innerHTML=second_id.innerHTML;	
	setopacity_to_100();
}
function setopacity_to_100()
{
	start_opaque=start_opaque+1;
	id_to_opaque.style.opacity=start_opaque/10;
        id_to_opaque.style.filter='alpha(opacity=' + start_opaque*10 + ')';		
	if(start_opaque<10)
		setTimeout("setopacity_to_100()",10);
	else
		start_opaque=.10;
}
var text_message="";
var draft_id="";
function express_interest()
{
	//check_window('from_link');
        var profile_str="";
        var selected=0;
        for(i=0;i<10;i++)
        {
                eval("var check_box=dID('PROFILE_"+i+"')");
                if(check_box)
                        if(check_box.checked)
                        {
                                if(selected==0)
                                        profile_str=check_box.value;
                                else
                                        profile_str=profile_str+","+check_box.value;
                                selected++;
                        }
        }
	
        if(profile_str)
        {
		if(selected>=0)
			var type_of_contact='S';
		if(selected>1)
			var type_of_contact='M';
		text_message=dID("text_id").value;
                text_message=escape(text_message);
		draft_id=dID("message_id").value;
			
		var url_for_contact="AjaxContact.php?TYPE_OF="+type_of_contact+"&draft_name="+draft_id+"&senders_data="+profile_str+"&MESSAGE="+text_message+"&stype="+stype+"&height=240&width=300";
		
		$.colorbox({href:url_for_contact});
        }
}
function exp_layer_setting()
{
	var return_data='<div  style="padding: 1px 0px; width: 282px; position: relative; z-index: 100;" class="lf"><div><img src="IMG_URL/img_revamp/cont_top_bg.gif"></img></div><div class="cont_hr_bg_temp" style="background-image:url(IMG_URL/img_revamp/cont_hr_bg.gif);padding: 50px 0pt 0pt 10px; width: 273px; height: 170px;" id="main_layer"><div style="text-align: center;"><img src="IMG_URL/img_revamp/loader_big.gif"></img></div>  <div class="sp16"></div> <div class="sp8"></div>  <div class="t14 b" style="text-align: center;">Expressing Interest...</div></div><div><img src="IMG_URL/img_revamp/cont_bottom_bg.gif"></div></div>';
	
	hide_exp_layer(2);
	return return_data;	
}
function remove_express_link()
{

        for(i=0;i<10;i++)
        {

                //Getting the resource of particular checkbox

                eval("var span_box=dID('SPAN_"+i+"')");

                eval("var check_box=dID('PROFILE_"+i+"')");

                //If actually exist that resource

                if(check_box)

                        //If checkbox is clicked or not

                        if(check_box.checked)

			{

				check_box.checked=false;

				//check_box.disabled=true;

				span_box.innerHTML="<img src=\"IMG_URL/img_revamp/icon_expressint.gif\" align=\"top\"><label class='gray' style='display:inline'> Interest Expressed</label> <div id='EXP_LAYER_"+i+"' style='position:absolute;display:inline;left:18px;top:-15px' onclick='javascript:check_window(\"hide_exp_layer()\")'></DIV>";

			}

	}

}
function show_exp_error(id_ajax)
{
	var st_error_data='<div  style="padding: 1px 0px; width: 282px; position: relative; z-index: 100;" class="lf"><div class="topbg"><div class="lf pd b t12"></div><div class="rf pd b t12" ><a onclick="$.colorbox.close(); return false;" class="blink" href="#">Close [x]</a></div></div><div class=clear></div>';
	var mid_error_data='<div class="cont_hr_bg_temp" style="background-image:url(IMG_URL/img_revamp/cont_hr_bg.gif);padding: 50px 0pt 0pt 10px; width: 273px; height: 170px;" ><div class="t14 b" style="text-align: center;">';
        var end_error_data='</div></div><div><img src="IMG_URL/img_revamp/cont_bottom_bg.gif"></div></div>';

	var ajax_value=id_ajax.innerHTML;
	if(ajax_value=='A_E')
		id_ajax.innerHTML=st_error_data+mid_error_data+common_error+end_error_data;
	else if(ajax_value.substr(0,5)=='ERROR')
	{
		if (ajax_value.substr(0,8)=='ERROR#20')
			id_ajax.innerHTML=st_error_data+ajax_value.substr(8,ajax_value.length)+'</div><div><img src="IMG_URL/img_revamp/cont_bottom_bg.gif"></div></div>';
		else
			id_ajax.innerHTML=st_error_data+mid_error_data+ajax_value.substr(6,ajax_value.length)+end_error_data;
	}
	else
	{
		if(ajax_value.substr(0,8)=='REDIRECT')
		{
			var all_data=ajax_value.split(":");
			
			var contact_id=all_data[1];
			var sim_username=all_data[2];
			var type_of_con=all_data[3];
			var from_search=0;
			if(dID("from_search"))
				from_search=1;
			var url_for_contact="/profile/view_similar_profile.php?&draft_name="+draft_id+"&contact="+contact_id+"&SIM_USERNAME="+sim_username+"&MESSAGE="+text_message+"&stype=CN&"+navig+"&type_of_con="+type_of_con+"&from_search="+from_search;
			document.location.href=url_for_contact;
			$.colorbox.close();		
		}
		else
		{
			function_to_call=""
			remove_express_link();
			TB_WIDTH=760		
		}
	}
		
	if(ajax_value.replace("multiple","")!=ajax_value)
		disable_button("hide_",2);
//	return (st_error_data+error+end_error_data);
}
function show_bottom_chatbar()
{
	//added by manoranjan
	if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="none")
	{
		top.document.getElementById("browseBottom").style.visibility="visible";
		top.document.getElementById("browseBottom").style.display="block";
		top.scrollTo(0,0);
		if(top.iframeHeight != 0){
			if(navigator.appName.indexOf("Internet Explorer") != -1){
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight);
			}else{
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-33;
			}	
		}
		
		
	}
}

