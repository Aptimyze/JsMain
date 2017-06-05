$("#handle_click").bind("click", function(){
handle_click("off");
});

var contactType=type_of_contact;
var callNowDiv=0;
function show_callnow_layer()
{
	show_contact_info("callnow");
}
function make_call_info(callnowmes)
{
	show_contact_info("callnow");
}
function show_alb_loader()
{
	$("#exp_layer").hide();
	$("#alb_loader").show();
	$("#slideshow").css("z-index",-1);
}
var stop_eoi_layer=0;
function show_contact_info(CallOrContact)
{
	act_contact_id="exp_layer";
	if(CallOrContact=='contact')
	{
		if(typeof(PH_LAYER_STATUS_PL)!='undefined')
                {
                        if(PH_LAYER_STATUS_PL)
                        {
				$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=CONTACT&flag=1&width=700'});
				return;
                        }
                }
	}
	//("width",document.body.offsetWidth);
	var to_do="view_contact";
	if(CallOrContact=="callnow")
		to_do="callnow_layer&ONLY_LAYER=1";
	
	var ce_url=SITE_URL+"/profile/invoke_contact_engine.php?to_do="+to_do+"&index=0&ajax_error=2&profilechecksum="+dp_profilechecksum+"&close_func=hide_cont_layer()&FROM_ALBUM=1&checksum=&profile_url=1&rand="+Math.round(Math.random()*1000);
	show_alb_loader();
	
	stop_eoi_layer=0;
		$.ajax({
				url: ce_url,
				success: function(data){
					if(to_do=="callnow_layer&ONLY_LAYER=1")
						show_contact_engine(data,1);
					else
						show_contact_engine(data);	
				
					//dID("IndividualProfile").style.zIndex="10";
				}
		});
}
var last_to_do="";
function make_contact_info(to_do)
{
		if(to_do=='eoi')
		{
			if(typeof(noExpressInterest)!='undefined')
                        {
                                $.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=EOI&flag=1&width=700'});

				return;
                        }
		}
		//check_window("hide_exp_layer()");
		act_contact_id="exp_layer";
		//http://social.jeev.com/profile/get_details.php?ajax_error=2&ONLY_LAYER=1&countlogic=1&clicksource=&matchalert_mis_variable=&CURRENTUSERNAME=&crmback=&inf_checksum=&cid=&suggest_profile=&pr_view=&stype=&checksum=&profilechecksum="+dp_profilechecksum+"&viewed_profile="+dp_profilechecksum+"&filter_profile=&TextAllow=
		//var ce_url=SITE_URL+"/profile/invoke_contact_engine.php?to_do="+to_do+"&show_contacts=&index=0&ajax_error=2&profilechecksum="+dp_profilechecksum+"&close_func=hide_exp_layer()&checksum=&profile_url=1&rand="+Math.round(Math.random()*1000);
		
		last_to_do=to_do;
		var ce_url=SITE_URL+"/profile/get_details.php?CURRENTUSERNAME="+CURRENTUSERNAME+"&ajax_error=2&from_album=1&ONLY_LAYER=1&countlogic=1&clicksource=&matchalert_mis_variable="+matchalert_mis_variable+"&suggest_profile="+suggest_profile+"&pr_view=&stype="+stype+"&checksum=&profilechecksum="+dp_profilechecksum+"&viewed_profile="+dp_profilechecksum+"&to_do="+to_do+"&rand="+Math.round(Math.random()*1000);
		
		show_alb_loader();
		
		stop_eoi_layer=0;
		$.ajax({
				url: ce_url,
				success: function(data){
				show_contact_engine(data);
				
  }
});
}
function show_contact_engine(data,callpage)
{
	if(typeof(callpage)=="undefined")
		callpage=0;
	if(stop_eoi_layer==0)
	{
		if(data=="A_E")
		{
			$("#temp_cond").html(commor_error);
			//CloseContactLayer();
		}
		else if(data=="LOGIN" || data=="Login")
		{
			$("#alb_loader").hide();
			var url = SITE_URL+"/profile/login.php?SHOW_LOGIN_WINDOW=1";
			$.colorbox({href:url});
			CloseContactLayer(0);
		}
		else
		{
			$("#alb_loader").hide();
			$("#exp_layer").html("");
			
			if(callpage==1)
				if(data.search("<!--Error message-->")!=-1)
					$("#exp_layer").html(data);
				else	
					$("#exp_layer").html("<div class='lyr' style='display:block'><div class='lyr_tp_cur'></div><div class='call' id='call_directly'>"+data+"</div><div class='lyr_btm_cur'></div>");
			else	
				$("#exp_layer").html(data);
			$("#exp_layer").show();
			$("#inv_con_lay").css("z-index",101);
			$("#inv_con_lay").css("margin-left","0");
			//handle_click("show");
		}	
	}
	else
	{
		stop_eoi_layer=1;
	}	
		
	
}	
function handle_click(which)
{
	if(which=="show")
	{
		$("#handle_click").css("z-index",100);
		$("#handle_click").css("height",document.body.offsetHeight);
		$("#handle_click").css("width",document.body.offsetWidth);
		$("#handle_click").show();
	}
	else
	{
		$("#handle_click").css("z-index",-1);
		$("#handle_click").css("height",0);
		$("#handle_click").css("width",0);
		$("#handle_click").hide();
		hide_exp_layer();
	}	
}



function showLoadedImage()
{
	$("#loader").fadeOut("slow");
	$("#slider").fadeIn("slow");
	//afterMainPicLoad();
}
function CloseContactLayer(force)
{
	
	//alert($("#exp_layer").css("display"));
	if($("#exp_layer").css("display")=="none" || force==1)
		$("#slideshow").css("z-index",0);
}
var lastimgid=0;
function display_image(id)
{
	
	$("#slider").hide();
	$("#loader").show();
	//$("#display_main_pic").html("<img src='"+mainpic[id]+"' onload='return showimage()'/>");
        var newMainPicUrl;
        newMainPicUrl = mainpic[id].replace(/&amp;/g, '&');
        $('#display_main_pic_div').attr("src", newMainPicUrl);
	showimage();
	//lastimgid=imgarr[id];
	for(i=0;i<imgarr.length;i++)
	{
		if(id==imgarr[i])
		{
			lastimgid=i;
			$("#pictureIndex").html((i+1));
			break;
		}
	}
	set_title_keywords(id);
	//dID("display_main_pic").src=mainpic[id];
	
}
function set_title_keywords(id)
{
	$("#album_title").html(titles[id]);
	$("#picture_keywords").html(keywords[id]);
	if(keywords[id])
		$("#album_keywords").show();
	else
		$("#album_keywords").hide();	
	
}
function showimage()
{
	$("#loader").hide();
	$("#slider").show();
}
function display_image_action(move,totalcnt)
{
	if(move=="next")
	{ 
		if(lastimgid==(totalcnt-1))
			lastimgid=0;
		else
			lastimgid++;
			
	}
	if(move=="previous")
	{
		if(lastimgid==0)
			lastimgid=totalcnt-1;
		else
			lastimgid--;
	}
	display_image(imgarr[lastimgid]);
}
function hide_cont_layer()
{
	$("#exp_layer").hide();
	CloseContactLayer(0);	
	show_bottom_chatbar();	
	return false;
}
//allow_exp set to 1 if to prevent uncheck and function overwritten
//Allow_exp eq '' than function is called for removal of layer
//allow_exp eq 2 if only layer to remove not checkbox
function hide_exp_layer()
{
	hide_cont_layer();
}
$(document).keydown(function(event) {

                switch (event.keyCode) {
                    case 39: display_image_action("next",totalCnt); break;
                    case 37: display_image_action("previous",totalCnt); break;

                }
            });
         
//Setting title and keywords for first image            
if(imgarr)
{
	if(imgarr[0])
		set_title_keywords(imgarr[0]);
}
function show_login_layer(url)
{
        if(url=="call")
        {
                var url=SITE_URL+"/profile/login.php?SHOW_LOGIN_WINDOW=1&after_login_call="+escape('make_call_info("message")');
		$.colorbox({href:url});
        }
        else
        {
                check_window("from_link");
		$.colorbox({href:url});
     }
}


function reloadAlbumPageData()
{
	if(contactType==type_of_contact)
		return;
	$("#PROFILE_ALBUM_I").hide();
	$("#PROFILE_ALBUM_D").hide();
	$("#PROFILE_ALBUM_A").hide();
	$("#PROFILE_ALBUM_TEMP").hide();	
	$("#PROFILE_ALBUM_RC").hide();
	$("#PROFILE_ALBUM_RA").hide();
	$("#PROFILE_ALBUM_RE").hide();
	$("#PROFILE_ALBUM_RI").hide();
	
	if(contactType == 'C')
	{
		$("#PROFILE_ALBUM_RC").show();
	}
	else if(contactType == 'A')
	{
		$("#PROFILE_ALBUM_A").show();
	}
	else if(contactType == 'D')
	{
		$("#PROFILE_ALBUM_D").show();
	}
	else if(contactType =='I')
	{
		$("#PROFILE_ALBUM_I").show();
	}
	else if(contactType =='RA')
	{
		$("#PROFILE_ALBUM_A").show();
	}
	else if(contactType =='RC')
	{
		$("#PROFILE_ALBUM_D").show();
	}	
	else if(contactType =='RE')
	{
		$("#PROFILE_ALBUM_NO_CONTACT").show();
	}
	type_of_contact = contactType;
}

