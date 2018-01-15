var bannerSrc={};
var bannerWidth={};
var bannerHeight={};
var loggedInPid;
var startTimeL2=0;
var errorMes='An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.';
var common_error='<span style="color:#FF0000"><img src="IMG_URL/profile/images/iconError_16x16.gif"><b>'+errorMes+'</b></span>';
//This variable is used to hide the layer that are not required to show, such as more option layer, save search option etc.
var common_check=0;
var function_to_call=""
var same_function_call=0;
var imediate=0
var from_outside=0
var on_click_force=0
var logged_in_username=""
var cur_err_msg="Please provide valid Email";
var greenLoginRegistrationLayer=0;
var searchPageLoginLayer=0;
if(typeof pinkLoginRegistrationLayer=="undefined")
	var pinkLoginRegistrationLayer=0;
if(typeof SSL_SITE_URL=="undefined")
		var SSL_SITE_URL="https://"+top.location.host;
//This variable will change only after confirmation done by user.
var allow_decline=0;
 if (!jQuery.browser) {

    jQuery.browser = {};
    jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
    jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
    jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
    jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());

}
function check_special_chars(mes)
{
        var myreg=/&amp;/g;
        mes=mes.replace(myreg, "&");
        mes=mes.replace(/&quot;/g, "\"");
        mes=mes.replace(/&#039;/g, "'");
        mes=mes.replace(/&lt;/g, "<");
        mes=mes.replace(/&gt;/g,">");
        return mes;


}
function for_confirmation(request_for,who_is,user_name,index)
{
	var url_str="";
	if(request_for=='DECLINE')
	{
		if(who_is=='SENDER')
			url_str="&status=C";
		else
			url_str="&status=D";
		url_str=url_str+"&request_for=DECLINE&other_username="+escape(user_name)+"&index="+index;
		url_str=SITE_URL+"/profile/conf_dec_can.php?temp=1"+url_str;
		$.colorbox({href:url_str});
	}
}


//Here close_func variable signifies the function that to be called for closing the layer.
function set_focus_on_anchor(name_of_anchor)
{
        var loc_str=document.location.href;
        var regExpr=/#[a-z\_A-Z0-9]*/;
	
        loc_str=loc_str.replace(regExpr,"");
        document.location.href=loc_str+name_of_anchor

}
function set_onclick_on_all_link()
{
	/*var all=document.getElementsByTagName("a");
	for(var i=0;i<all.length;i++)
	{
	        if(!all[i].onclick && all[i].className!='thickbox' )
        	{
			//all[i].attachEvent("onclick","check_window('no_onclick')");
                	all[i].onclick=function(){check_window('no_onclick');};
        	}
	}
	*/
                        
}
function dID(arg)
{
	return document.getElementById(arg);

}
function check_window(close_func)
{
	//This function is used to return value false if a tag is clicked.
	if (typeof(close_func)=='undefined' || typeof(close_func)=='object' )
	{
		if(common_check==1 && function_to_call!=""  && imediate==0)
		{
			eval(function_to_call);
		}
		//Added only this condition to hide the layer by just click on outside.
		if(imediate==1)
			imediate=0;

		if(!e) var e=window.event;	
		if(!e) var e=close_func;
		var tg = (window.event) ? e.srcElement : e.target;

		//Prevent thickbox layer to reload the page.
		if(tg.className=='thickbox')
			return false;

		if(tg.nodeName=='INPUT')
		{
			if(tg.value=="Express Interest - Free")
                                return false;
			if(tg.parentNode==null)
				return true;

			tg2=tg.parentNode;
			if(tg2.nodeName!='A')
				return true;
		}
		if(tg.nodeName=='DIV' || tg.nodeName=='SPAN' || tg.nodeName=='TD' || tg.nodeName=='TR' || tg.NodeName=='TABLE' || tg.nodeName=='IMG' || tg.nodeName=='i')
		{
			tg=tg.parentNode;
			if(tg.nodeName=='A' && !tg.onclick)
				return true;
			if(tg.nodeName=='A')
				return false;
			imediate=0;
		}
		else if(tg.nodeName=='A')
		{
			if(!tg.onclick)
				return true;
			return false;
		}
	}

	if(typeof(close_func)!='object' && typeof(close_func)!='undefined')
	{//alert(common_check+"  "+function_to_call+" "+close_func);
		if(common_check==1 && function_to_call!="" && function_to_call!=close_func )
		{
			eval(function_to_call);

		}
	/*	var e=event;
		if(!e)
			var e=window.event
		e.cancelBubble=true;*/
		//var e=window.event;
		//e.stopPropagation();

		
	}
        if(typeof(close_func)!='object' && typeof(close_func)!='undefined')
        {
		if(close_func==function_to_call)
			same_function_call=1;
                imediate=1
                return 1
        }
	if(imediate)
	{
		imediate=0
		return false;
	}
	if(same_function_call)
	{
		same_function_call=0;
		return false;
	}	
	if(common_check==1)
	{
		if(function_to_call)
			eval(function_to_call);
	}
	return true;
}
function send_ajax_request(url,before_call_func,after_call_func,method)
{
	 if(url.indexOf(SITE_URL)==-1)
                url=SITE_URL+"/profile/"+url;
	if(url.indexOf("single_contact_aj.php")!=-1 || url.indexOf("AjaxContact.php")!=-1)
		method="POST";
        var ajaxRequest;  // The variable that makes Ajax possible!
	result=""
	if(method=="")
		method="GET";
        try
        {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
        }
        catch (e)
        {
                // Internet Explorer Browsers
                try
                {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                // Something went wrong
                                alert("Your browser broke!");
                                return false;
                        }
                }
        }
        // Create a function that will receive data sent from the server
        ajaxRequest.onreadystatechange = function()
        {
                if(ajaxRequest.readyState == 4)
                {
			if(ajaxRequest.status==200)
			{
				//Please defined this variable in the script tag where this send_ajax function is called, this is required since result is required at function called below
				result= ajaxRequest.responseText;
				if(after_call_func)
					eval(after_call_func+"()");
				//Nikhil setting onclick on every a tagname
        	                //set_onclick_on_all_link();
        	        }
			else
			{
				result="A_E";
				if(after_call_func)
                                        eval(after_call_func+"()");
			}
                }
        }
	if(method=='POST')
	{
		var send_params = url.replace(/^[^\?]+\??/,'');
		var check_url=url.replace(/[\?].+/,'');
		//send_params=send_params.substr(1,send_params.length);
		ajaxRequest.open("POST",check_url, true);
                ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajaxRequest.setRequestHeader("Content-length", send_params.length);
                ajaxRequest.setRequestHeader("Connection", "close");
                ajaxRequest.send(send_params);
	}
	else
	        ajaxRequest.open("GET",url, true);
	if(before_call_func)
		eval(before_call_func+"()");

	//This is required only for those ajax request that send data through GET
	if(method!='POST')
		ajaxRequest.send(null);
}
function checkemail(emailadd)
{
        var ce_results = false;
        var theStr = new String(emailadd);
        var index = theStr.indexOf("@");
        if (index > 0)
        {
        var pindex = theStr.indexOf(".",index);
        if ((pindex > index+1) && (theStr.length > pindex+2))
                ce_results = true;
        }

        return ce_results;
}

function writeafterSave(pid)
{
        var str,loop=1;

	str='<span style="visibility:hidden;"><a class="fr" href="#" onclick="save_search_options(\'show\','+pid+');return false;"> My Saved Searches </a><i class="fr sprte2 blue_arw ml_17"></i></span><IFRAME id="iframeshim"  src="" style="display: inline; left: 20px; top: 32px; z-index: 2; position: absolute; width: 190px; height: 78px;filter: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);" frameBorder="0" scrolling="no"></IFRAME><span style="position:absolute;margin-left:50px; z-index:1000;margin-top:-5px;">';
        str+='<div class="lf" style="width:188px;"><div class="m_saved_topbg"><div class="t12 b" style="padding:4px 0 0 5px;"><img src="IMG_URL/profile/images/dw_blk_arrow.gif"> My Saved Searches</div></div><div class="m_saved_hrbg" style="padding:17px 15px; width:150px; line-height:20px">';
        str+='<span id="my_save_search_id">';
        str+='<div style="text-align:center"><img src="IMG_URL/profile/images/ajax-loader.gif"><\/div>';
        str+='</span>';
        str+='<div style="text-align:right;padding-top:4px;"><a href="#" onclick=\'save_search_options("hide",'+pid+');return false;\' class="t12 blink b">Close[x]</a></div></div><div class="m_saved_footerbg"></div></div></span>';
        mysavesearches_ajaxValidation(pid);
        return str;
}
function save_search_options(what,pid,type)
{
	var close_save1="<a class='fr' href='#' onclick=\"save_search_options('show',"+pid+");return false;\"> My Saved Searches </a><i class=\"fr sprte2 blue_arw ml_17\"></i>";
        if(what=='show')
        {
		if(type==1)
		{
			check_window('save_search_options(\'hide\',\''+pid+'\',1)');
			function_to_call="save_search_options('hide',"+pid+",1)";
                	dID("my_saved").style.display="block";
                	dID("save_search_text").style.display="none";
                	dID("save_search_arrw").style.display="none";
			mysavesearches_ajaxValidation(pid,1);
		}
		else
		{
			check_window('save_search_options(\'hide\',\''+pid+'\')');
			function_to_call="save_search_options('hide',"+pid+")";
                	dID("save_search_option").innerHTML=writeafterSave(pid);
		}
		common_check=1;
        }
        else
        {
		common_check=0;
		function_to_call="";
		if(type==1)
		{
	        	dID("my_saved").style.display="none";
                	dID("save_search_text").style.display="block";
                	dID("save_search_arrw").style.display="block";
                        dID("my_save_search_id").innerHTML="";
			dID("saveSearchLoader").style.display="block";
		}
		else
	        	dID("save_search_option").innerHTML=close_save1;
	}
}
function createNewXmlHttpObject()
{
        req = false;
        if(window.XMLHttpRequest)
        {
                try
                {
                        req = new XMLHttpRequest();
                }
                catch (e)
                {
                        req = false;
                }
        }
        else if(window.ActiveXObject)
        {
                try
                {
                        req = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                req = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                req = false;
                        }
                }
        }
        return req;
}
function createNewXmlHttpObject1()
{
        req1 = false;
        if(window.XMLHttpRequest)
        {
                try
                {
                        req1 = new XMLHttpRequest();
                }
                catch (e)
                {
                        req1 = false;
                }
        }
        else if(window.ActiveXObject)
        {
                try
                {
                        req1 = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                req1 = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                req1 = false;
                        }
                }
        }
        return req1;
}

var req = createNewXmlHttpObject();
function mysavesearches_ajaxValidation(pid,type)
{
	loggedInPid=pid;

	if(type==1)
        	var to_post = "list_save=1&frmSearch=1";
	else
        	var to_post = "list_save=1";
        if(pid)
        {
                req.open("POST","/search/displayMySaveSearch",true);
                req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                req.send(to_post);
                req.onreadystatechange = mysavesearches_checkXmlHttpStatus;
        }
        else
        {

        }
}

function mysavesearches_checkXmlHttpStatus()
{
         var str='';
	var searchPage = false;
	var data = 0;
        if (req.readyState != 4)
        {
                return;
        }
        if (req.status == 200)
        {
                var got_response = req.responseText.split("$");
                if(got_response[0]!="")
                {
			var searchPage = "";
			if(got_response[0]=="logoutSearch")
				$.colorbox({href:"/static/registrationLayer?pageSource=searchpage"});
			else if(got_response[0]=="logout")
				show_loggedIn_window();
			else if(got_response[0]=="zeroSearch")
			{
				searchPage = true;
				str = "You need not enter your search criteria every time. Save your search once and just click it whenever you want to search next. All your saved searches will be shown here.";
			}
			else if(got_response[0]=="zero")		
				str = "You need not enter your search criteria every time. Save your search once and just click it whenever you want to search next. All your saved searches will be shown here.";
			else
                        {
                                if(got_response[0])
				{
					var temp = got_response[0].split("**");
					if(temp[0]=="SearchPage")
					{
						searchPage = true;
						got_response[0] = temp[1];
					}
					else
					{
						searchPage = false;
						got_response[0] = temp[0];
					}
                                        var gotresponsestr=got_response[0].split("#");
					data = 1;
				}
                                if(got_response[1])
                                        var gotresponsestr1=got_response[1].split("#");
                                if(got_response[2])
                                        var gotresponsestr2=got_response[2].split("#");
                                if(got_response[3])
                                        var gotresponsestr3=got_response[3].split("#");
                                if(got_response[4])
                                        var gotresponsestr4=got_response[4].split("#");
                        }

			if(searchPage)
			{
				if(data==1)
				{
					if(got_response[0])
						str+='<span class="width100 bg-grey fl"><a class = "blink wordwrap" style = "white-space: pre-wrap; word-wrap: break-word;" href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr[1] + '">' + gotresponsestr[0] + '</a></span><br />';
					if(got_response[1])
						str+='<span class="fl"><a class = "blink wordwrap" style = "white-space: pre-wrap; word-wrap: break-word;" href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr1[1] + '">' + gotresponsestr1[0] + '</a></span><br />';
					if(got_response[2])
						str+='<span class="width100 bg-grey fl"><a class = "blink wordwrap" style = "white-space: pre-wrap; word-wrap: break-word;" href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr2[1] + '">' + gotresponsestr2[0] + '</a></span><br />';
					if(got_response[3])
						str+='<span class="fl"><a class = "blink wordwrap" style = "white-space: pre-wrap; word-wrap: break-word;" href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr3[1] + '">' + gotresponsestr3[0] + '</a></span><br />';
					if(got_response[4])
						str+='<span class="width100 bg-grey fl"><a class = "blink wordwrap" style = "white-space: pre-wrap; word-wrap: break-word;" href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr4[1] + '">' + gotresponsestr4[0] + '</a></span><br />';
				}
				dID("saveSearchLoader").style.display="none";
			}
			else
			{
				if(data==1)
				{
					if(got_response[0])
						str+='<a href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr[1] + '"class="blink">' + gotresponsestr[0] + '</a><br>';
					if(got_response[1])
						str+='<a href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr1[1] + '"class="blink">' + gotresponsestr1[0] + '</a><br>';
					if(got_response[2])
						str+='<a href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr2[1] + '"class="blink">' + gotresponsestr2[0] + '</a><br>';
					if(got_response[3])
						str+='<a href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr3[1] + '"class="blink">' + gotresponsestr3[0] + '</a><br>';
					if(got_response[4])
						str+='<a href="'+SITE_URL+'/search/perform?mySaveSearchId=' + gotresponsestr4[1] + '"class="blink">' + gotresponsestr4[0] + '</a><br>';
				}
			}
                        dID("my_save_search_id").innerHTML=str;
                        return ;
                }
                else
                {
                        dID("my_save_search_id").innerHTML=str;
			return;
                }
        }
        else
        {
                return;
        }
}

function Get_Cookie( name )
{
        var start = document.cookie.indexOf( name + "=" );
        var len = start + name.length + 1;
        if ( ( !start ) &&
        ( name != document.cookie.substring( 0, name.length ) ) )
        {
        return null;
        }
        if ( start == -1 ) return null;
        var end = document.cookie.indexOf( ";", len );
        if ( end == -1 ) end = document.cookie.length;
        return unescape( document.cookie.substring( len, end ) );
}

function Set_Cookie( name, value, days, path, domain, secure ) 
{
	
	var date = new Date();
	if(days)
	{
		//Make cookie for day
		//date.setTime(date.getTime()+(days*24*60*60*1000));
		//Make cookie for hour
		//date.setTime(date.getTime()+(days60*60*1000));
		//Make cookie for minute
		date.setTime(date.getTime()+(days*60*1000));
		
	}

        document.cookie = name + "=" +escape( value ) +
        ( ( days ) ? ";expires=" + date.toGMTString() : "" ) + 
        ( ( path ) ? ";path=" + path : "" ) + 
        ( ( domain ) ? ";domain=" + domain : "" ) +
        ( ( secure ) ? ";secure" : "" );
}
function MM_openBrWindow(theURL,winName,features)
{
window.open(theURL,winName,features);
}

if(typeof SITE_URL=="undefined")
                var SITE_URL="http://"+top.location.host;
var after_searchbyid='<span style="visibility:hidden;width:200px"><a class="fr" href="#" onclick="search_by_id(\'show\');return false;"> Search by profile id</a><i class="fr sprte2 blue_arw ml_17"></i></span><span style="position:absolute;margin-left:493px;z-index:1000;margin-top:-5px;"><div class="lf" style="width:188px;" ><div class="sr_display_top" style="z-index:1000;" ><div class="t12 b" style="padding:4px 0 0 75px;"><img src="IMG_URL/profile/images/dw_blk_arrow.gif"> Search by profile id</div></div><div class="sr_display_hr" style="padding:10px 15px; line-height:20px"><input type="text" id="SEARCH_BY_USERNAME" value="" class="pd" style="width:140px" onkeydown="javascript:check_enter(\'search_by_username()\',event)">&nbsp;&nbsp;&nbsp;<input type="button" style="width: 60px;" value="Search" class="b green_btn" onclick="javascript:search_by_username()"/><span style="color: red; display: none;padding-top:5px;font-size:11px" id="email_error">Email ID is not allowed. Please provide a profile id</span></div><div  class="sr_display_hr" style="padding: 10px 15px;text-align:center" ><a href="#" onclick="search_by_id(\'hide\');return false;" class="blink b">Close[X]</a></div><div class="sr_display_footer"></div> </div></span>';

var after_searchbyid_kundli='<span style="visibility:hidden;width:200px"><a class="fr" href="#" onclick="search_by_id(\'show\',2);return false;"> Search by profile id</a><i class="fr sprte2 blue_arw ml_17"></i></span><span style="position:absolute;margin-left:550px;z-index:1000;margin-top:-5px;"><div class="lf" style="width:188px;" ><div class="sr_display_top" style="z-index:1000;" ><div class="t12 b" style="padding:4px 0 0 75px;"><img src="IMG_URL/profile/images/dw_blk_arrow.gif"> Search by profile id</div></div><div class="sr_display_hr" style="padding:10px 15px; line-height:20px"><input type="text" id="SEARCH_BY_USERNAME" value="" class="pd" style="width:140px" onkeydown="javascript:check_enter(\'search_by_username()\',event)">&nbsp;&nbsp;&nbsp;<input type="button" style="width: 60px;" value="Search" class="b green_btn" onclick="javascript:search_by_username()"/></div><div  class="sr_display_hr" style="padding: 10px 15px;text-align:center" ><a href="#" onclick="search_by_id(\'hide\',2);return false;" class="blink b">Close[X]</a></div><div class="sr_display_footer"></div> </div></span>';

var after_searchbyid_seo='<span style="visibility:hidden;width:200px"><a class="fr" href="#" onclick="search_by_id_seo(\'show\');return false;"> Search by profile id</a><i class="fr sprte2 blue_arw ml_17"></i></span><span style="position:absolute;left:424px;z-index:1000;top:0px;"><div class="lf" style="width:188px;" ><div class="sr_display_top" style="z-index:1000;" ><div class="t12 b" style="padding:4px 0 0 75px;"><img src="IMG_URL/profile/images/dw_blk_arrow.gif"> Search by profile id</div></div><div class="sr_display_hr" style="padding:10px 15px; line-height:20px"><input type="text" id="SEARCH_BY_USERNAME" value="" class="pd" style="width:140px" onkeydown="javascript:check_enter(\'search_by_username()\',event)">&nbsp;&nbsp;&nbsp;<input type="button" style="width: 60px;" value="Search" class="b green_btn" onclick="javascript:search_by_username()"/></div><div  class="sr_display_hr" style="padding: 10px 15px;text-align:center" ><a href="#" onclick="search_by_id_seo(\'hide\');return false;" class="blink b">Close[X]</a></div><div class="sr_display_footer"></div> </div></span>';

var close_searchbyid_seo='<a class="fr" href=# onclick="search_by_id_seo(\'show\');return false;"> Search by profile id</a><i class="fr sprte blue_arw ml_17"></i>';

var close_searchbyid='<a class="fr" href=# onclick="search_by_id(\'show\');return false;"> Search by profile id</a><i class="fr sprte2 blue_arw ml_17"></i>';

var close_searchbyid_kundli='<a class="fr" href=# onclick="search_by_id(\'show\',2);return false;"> Search by profile id</a><i class="fr sprte2 blue_arw ml_17"></i>';

function check_enter(java_func,e)
{
	var numCode=e.keyCode;
	if(numCode==13)
	{
		if(java_func!="")
			eval(java_func);
	}
}

function search_by_id_seo(what)
{
        if(what=="show")
        {
		check_window('search_by_id_seo(\'hide\')');
	        dID("search_by_id_seo").innerHTML=after_searchbyid_seo;
		dID("SEARCH_BY_USERNAME").focus();
		common_check=1;
		function_to_call="search_by_id_seo('hide')";
	}
        else
        {
		common_check=0;
		function_to_call="";
	        dID("search_by_id_seo").innerHTML=close_searchbyid_seo;
	}
}

function search_by_id(what,type)
{
        if(what=="show")
        {
		if(type==1)
		{
			check_window('search_by_id(\'hide\',1)');
	        	dID("saved_by_profile").style.display="block";
			function_to_call="search_by_id('hide',1)";
		}
		else
		{
			if(type==2)
			{
				check_window('search_by_id(\'hide\',2)');
	        		dID("search_by_id").innerHTML=after_searchbyid_kundli;
				function_to_call="search_by_id('hide',2)";
			}
			else
			{
				check_window('search_by_id(\'hide\')');
	        		dID("search_by_id").innerHTML=after_searchbyid;
				function_to_call="search_by_id('hide')";
			}
		}
		dID("SEARCH_BY_USERNAME").focus();
		common_check=1;
	}
        else
        {
		common_check=0;
		function_to_call="";
		if(type==1)
		{
	        	dID("saved_by_profile").style.display="none";
		}
		else
		{
			if(type==2)
	        		dID("search_by_id").innerHTML=close_searchbyid_kundli;
			else
	        		dID("search_by_id").innerHTML=close_searchbyid;
		}
	}
}

/* Added new condition and function for sulekha condition only  
   Since header/searchband/sub-head/footer are the older one used in case of sulekha 
*/
var after_searchbyid_sulekha='<span style="visibility:hidden;width:200px"><img src="'+SITE_URL+'/profile/images/icon_rect.gif"> <a href="#" onclick="search_by_id_sulekha(\'show\');return false;" class="blink"> Search by profile id</a></span><IFRAME id="iframeshim"  src="" style="display: inline; left: -60px; top: 32px; z-index: 2; position: absolute; width: 270px; height: 78px;filter: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);" frameBorder="0" scrolling="no"></IFRAME><span style="position:absolute;left:-70px;z-index:1000;top:0px"><div class="lf" style="width:188px;" ><div class="sr_display_top" style="z-index:1000;" ><div class="t12 b" style="padding:4px 0 0 75px;"><img src="'+SITE_URL+'/profile/images/dw_blk_arrow.gif"> Search by profile id</div></div><div class="sr_display_hr" style="padding:10px 15px; line-height:20px"><input type="text" id="SEARCH_BY_USERNAME" value="" class="pd" style="width:140px" onkeydown="javascript:check_enter(\'search_by_username()\',event)">&nbsp;&nbsp;&nbsp;<input type="button" style="width: 60px;" value="Search" class="b green_btn" onclick="javascript:search_by_username()"/></div><div  class="sr_display_hr" style="padding: 10px 15px;text-align:center" ><a href="#" onclick="search_by_id_sulekha(\'hide\');return false;" class="blink b">Close[X]</a></div><div class="sr_display_footer"></div> </div></span>';

var close_searchbyid_sulekha=' <a href=# onclick="search_by_id_sulekha(\'show\');return false;" class="blink"> Search by profile id</a>';

function search_by_id_sulekha(what)
{
        if(what=="show")
        {
                check_window('search_by_id_sulekha(\'hide\')');
                dID("search_by_id").innerHTML=after_searchbyid_sulekha;
                dID("SEARCH_BY_USERNAME").focus();
                common_check=1;
                function_to_call="search_by_id_sulekha('hide')";
        }
        else
        {
                common_check=0;
                function_to_call="";
                dID("search_by_id").innerHTML=close_searchbyid_sulekha;
        }
}
// Added new condition ends

function search_by_username()
{
        //check is navigator is set

         if(typeof NAVIGATOR=="undefined")
		var NAVIGATOR='';
	if(typeof SITE_URL=="undefined")
		var SITE_URL="http://"+top.location.host;

        search_username_id=dID("SEARCH_BY_USERNAME");

        var username_id = $.trim(search_username_id.value);
        if (username_id) {
            if (username_id.indexOf("@") > -1) {
              document.getElementById("email_error").style.display = 'block';
            }
            else {
	          var url_for_profile=SITE_URL+'/profile/viewprofile.php?search=1&username='+escape(username_id)+'&'+NAVIGATOR+'&overwrite=1&stype=4';
            if(username_id==logged_in_username && username_id!="" && logged_in_username!="")
            {
                window.open(url_for_profile,"SAME_USERNAME",'width=800,height=600,status=1,scrollbars=1,resizable=yes');
            }
            else
                window.location=url_for_profile;

            search_by_id('hide');
            }
          }
}

if(typeof SITE_URL=="undefined")
                var SITE_URL="http://"+top.location.host;

after_moreclick='<span style="visibility:hidden;width:200px"> <img src="IMG_URL/profile/images/icon_rect.gif"> <a class=blink" href=# onclick="javascript:show_more_options(\'show\')">More Options</a></span><span style="position:absolute;left:-74px;top:-4px;z-index:1000"><div class="lf" style="width:188px;"><div class="more_topbg"><div class="blink b" style="padding:4px 0 0 75px;"><img src="IMG_URL/profile/images/icon_rect.gif"> <a href="#" class="blink">More Options</a></div></div><div class="more_hrbg" style="padding:10px 15px; width:150px; line-height:20px"><a href="'+SITE_URL+'/profile/advance_search.php" class="blink">Advanced Search</a><br><!--a href="#" class="blink">Members looking for me</a><br--><a href="'+SITE_URL+'/search/partnermatches" class="blink">Members I am looking for</a></div><div  class="more_hrbg" style="padding: 5px 15px; width: 150px;text-align:center" ><a href="javascript:show_more_options(\'hide\')" class="blink b">Close[X]</a></div><div class="more_footerbg"></div>        </div></span>';
close_moreclick=' <img src="IMG_URL/profile/images/icon_rect.gif"> <a class=blink" href="javascript:show_more_options(\'show\')">More Options</a>';


function show_more_options(what)
{
        if(what=="show")        
        {
		common_check=1;
                function_to_call="show_more_options('hide')";
	        dID("more_option").innerHTML=after_moreclick;
	}
        else
        {
		common_check=0;
                function_to_call="";
	        dID("more_option").innerHTML=close_moreclick;
	}
}


function redirect(prev_url)
{
        if(prev_url!="")
                document.location=prev_url;
        return false;
}
function check_thickbox_command()
{
	var arr=new Array;
        var idd="";
	var after_call="";
	if(dID("AFTER_LOGIN_CALL"))
		after_call=dID("AFTER_LOGIN_CALL").value;
        if(dID("CALL_THICK"))
        {
                arr[0]=dID("CALL_THICK").value;
                arr[1]=dID("id_checked").value;

        }
        if(arr[0] || after_call)
        {
                        var checkboxes=arr[1].split("-----");
                        for(var i=0;i<checkboxes.length;i++)
                        {
                                idd=checkboxes[i];
                                if(dID(idd))
                                        dID(idd).checked=true;

                        }
		if(after_call)
		{
			var url_thick=unescape(arr[0]);
        	        if(url_thick.length>10)
				$.colorbox({href:url_thick});
			eval(after_call);
		}
		else
		{
			var url_thick=unescape(arr[0]);
        	        if(url_thick.length>10)
				$.colorbox({href:url_thick});
		}
        }

        if(startTimeL2)//TRACKING
        {
        var endTimeL = (new Date()).getTime();
        var differnceDNS=startTimeL-CLICKTIME;
        var difference = endTimeL - startTimeL;
        var differenceBody = startTimeL2 - startTimeL;
        var differenceHead = startTimeL1 - startTimeL;
        var to_post = "difference=" + difference + "&headTime=" + differenceHead + "&bodyTime=" + differenceBody + "&pagExec=" + pagExec + "&searchid=" + searchidL + "&differnceDNS=" + differnceDNS + "&sphinxS=" + sphinxS + "&nikhil=" + startTimeL + "&ankit=" + CLICKTIME;
        var req = createNewXmlHttpObject();
        req.open("POST","/P/searchTrack.php",true);
        req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        req.send(to_post);

        }


}
function initializeBody() 
{
	if(!dID("abc1"))
		return;
        var dareDiv = dID("abc1");
        var wheatDiv = dID("abc2");
        dareDiv.onmouseover = colorIt;
        dareDiv.onmouseout = uncolorIt
        wheatDiv.onmouseover = colorIt;
        wheatDiv.onmouseout = uncolorIt;

}
function colorIt() 
{
        dID("abc2").style.visibility = "visible";
        dID("abc1").style.visibility = "hidden";
}
function uncolorIt() 
{
        dID("abc1").style.visibility = "visible";
        dID("abc2").style.visibility = "hidden";
}
function get_present_date()
{
        var dt=new Date();
        var day=dt.getDate();
        var monthIndex=dt.getMonth();
        var year=dt.getFullYear();
        var month_name=new Array();
        month_name[0]="Jan";
        month_name[1]="Feb";
        month_name[2]="Mar";
        month_name[3]="Apr";
        month_name[4]="May";
        month_name[5]="Jun";
        month_name[6]="Jul";
        month_name[7]="Aug";
        month_name[8]="Sep";
        month_name[9]="Oct";
        month_name[10]="Nov";
        month_name[11]="Dec";
        var month=month_name[monthIndex];
        var complete_date=day+" "+month+" "+year;
        return complete_date;
}

function includeCSSfile(href)
{
        var head_node = document.getElementsByTagName('head')[0];
        var link_tag = document.createElement('link');
        link_tag.setAttribute('rel', 'stylesheet');
        link_tag.setAttribute('type', 'text/css');
        link_tag.setAttribute('href', href);
        link_tag.setAttribute('name', 'for_home');
        head_node.appendChild(link_tag);
}
function reload_messages(draft_id,draft_name,draft_mes)
{
        var change_id=draft_id.value;
        if(typeof(MES)!='undefined')
		MES[change_id]=draft_mes.value.replace("<br />","\n");

	if(typeof(decline)!='undefined')
	       decline[change_id]=draft_name.value.replace("<br />","\n");

        if(typeof(accept)!='undefined')
		accept[change_id]=draft_name.value.replace("<br />","\n");
}
function call_dp(profileid,noJsCode)
{
	if(dID(profileid))
	{
		var id_hidden=dID(profileid).value;
		if(!noJsCode)
		id_hidden=id_hidden+"&after_login_call="+escape("change_tab(dID('horoscope'),'Horoscope')")
		document.location=id_hidden;
	}
	return false;
}
function up_launch(receiversid, sendersid,senderusername,receiverusername,threadname,status)
{
if(senderusername>receiverusername)
threadname=senderusername+"_"+receiverusername;
else
threadname=receiverusername+"_"+senderusername;
threadname=up_replaceAlpha(threadname);
window.open('/profile/chatwindow.php?receiversid='+receiversid+'&sendersid='+sendersid+'&senderusername='+senderusername+'&receiverusername='+receiverusername+"&status="+status+"&checksum="+prof_checksum,threadname,'width=342,height=274,status=1,scrollbars=0,resizable=no');
}

function up_replaceAlpha( strIn )
{
        var strOut = "";
        for( var i = 0 ; i < strIn.length ; i++ )
        {
                var cChar = strIn.charAt(i);
                if( ( cChar >= 'A' && cChar <= 'Z' )
                        || ( cChar >= 'a' && cChar <= 'z' )
                        || ( cChar >= '0' && cChar <= '9' ) )
                {
                        strOut += cChar;
                }
                else
                {
                        strOut += "_";
                }
        }
        return strOut;

}

function trackClicks(link)
{
}

function astro_icons()
{
        var docF=document.getElementsByTagName("input");
        var pstring="";
        for(var i=0;i<docF.length;i++)
        {
                if(docF[i].name.match("horo_astro") && docF[i].value)
                {
                        pstring1=docF[i].value;
                        pstring=pstring+pstring1+"@";
                }
        }
        var request_url = SITE_URL+"/profile/issue_2472.php?profileid=&checksum="+prof_checksum+"&compstring="+pstring;
        send_ajax_request(request_url,"","after_astro_call","GET");
}
function after_astro_call()
{
	if(typeof showOnlyGunaMatch == 'undefined')
		showOnlyGunaMatch='';

        var res = result;
        var x = res.substr(0).split("/>");
        for (var j=0; j<(x.length-1); j++)
        {
                if(x[j].charAt(0)==">")
                        x[j]=x[j].substr(1,x[j].length);
                var y = x[j].split(":");
                if(typeof(y[2]) != "undefined")
                {
                        var Guna_str = "Guna_"+y[0];

			if(showOnlyGunaMatch != 'Y')
				var Guna  = y[2].substr(0,4)+"/36";
			else
				var Guna  = y[2].substr(0,4)+" of 36";
                        var Lg = y[3].substr(0,2);
                        var Su = y[4].substr(0,2);
                        var Me = y[5].substr(0,2);
                        var Ju = y[6].substr(0,2);
                        var Sa = y[7].substr(0,2);

                        var lagan = "LAGAN_ID_"+y[0];
                        var lagan_prof = "LAGAN_ID_PROFILE_"+y[0];
			if(dID(lagan_prof))
			{
				lagan=lagan_prof;
			}		
			var main_lagan="MAIN_"+lagan;
			var imgs=new Array;
			var titles=new Array;
			if(showOnlyGunaMatch != 'Y')
			{
				if(Lg == 1)
				{
					imgs[0]="lagan_p";
					titles[0]="Lagan Favourable";
				
				}
				else if(Lg == -1)
				{
					imgs[1]="lagan_m";
					titles[1]="Lagan Unfavourable"
				}
				if(Su == 1)
				{
					imgs[2]="sun_p"
					titles[2]="Sun Favourable"
				}
				else if(Su == -1)
				{
                                imgs[3]="sun_m";
                                titles[3]="Sun Unfavourable";
				}
				if(Me == 1)
				{
					imgs[4]="murcury_p";
					titles[4]="Mercury Favourable"
				}
				else if(Me == -1)
				{
					imgs[5]="murcury_m";
					titles[5]="Mercury Unfavourable"
				}
				if(Ju == 1)
				{
					imgs[6]="jupitar_p";
					titles[6]="Jupitor Favourable"
				}
				else if(Ju == -1)
				{
					imgs[7]="jupitar_m";
					titles[7]="Jupitor Unfavourable"
				}
				if(Sa == 1 || Sa == "1")
				{
					imgs[8]="saturn_p";
					titles[8]="Saturn Favourable"
				}
				else if(Sa == -1)
				{
					imgs[9]="saturn_m";
					titles[9]="Saturn Unfavourable"
				}
			}

                        if(Guna)
                        {
                                var img_str="";
				var prof_lagan="";
                                var MG_URLL="IMG_URL/profile/ser4_images/";
                                for(var i=0;i<imgs.length;i++)
                                {
                                        if(imgs[i])
						if(lagan==lagan_prof)
							prof_lagan+="<span class='"+imgs[i]+"' title='"+titles[i]+"'>&nbsp;</span>";
						else
                                                	img_str=img_str+"<Td><img border=0 src='"+MG_URLL+imgs[i]+".jpg' title='"+titles[i]+"' ></td>";
                                }

				if(showOnlyGunaMatch != 'Y')
					img_str=img_str+"<td>"+Guna+"</td>";
				else
					img_str=img_str+"<div style='font-size:9px' class='fl mar_left_10'>Guna Match<br><strong style='color:#000;font-size:10px'>["+Guna+"]</strong></div>";
                                if(lagan==lagan_prof)
					$("#"+lagan).html(prof_lagan+" <span class=\"fl\" title=\"Guna Matches out of 36\">"+Guna+"</span>");
				else if(showOnlyGunaMatch == 'Y')
					$("#"+lagan).html(img_str);
				else
					$("#"+lagan).html("<table cellspacing=0 cellpadding=3 border=0 style=\"padding:4px;margin:0\"><TR>"+img_str+"</tr></table>");
				
                                //dID(lagan).innerHTML="<table cellspacing=0 cellpadding=3 border=0 style=\"padding:4px;margin:0\"><TR>"+img_str+"</tr></table>";
				if(dID(main_lagan))
					dID(main_lagan).style.marginTop="-49px";
				
                        }

                }
        }
}
var from_view_similar=0;
function save_draft(view_sim)
{
	var s_draft_show="s_draft_show";
	var s_draft_id="s_draft_id";
	var s_draft_name="s_draft_name";
	var s_draft_mes="s_draft_mes";
	from_view_similar=0;
	if(typeof(view_sim)!='undefined')
	{
		from_view_similar=1;
		s_draft_show="s_draft_show_page";
		s_draft_id="s_draft_id_page";
		s_draft_name="s_draft_name_page";
		s_draft_mes="s_draft_mes_page";
	}
        var draft_id='';
        var draft_name='';
        var draft_mes='';
        var id_name='';
        var draft_main=dID(s_draft_show);

        if(dID(s_draft_id))
                draft_id=dID(s_draft_id);
        if(dID(s_draft_name))
                draft_name=dID(s_draft_name);
        if(dID(s_draft_mes))
                draft_mes=dID(s_draft_mes);
        if(draft_name)
        {
                reload_messages(draft_id,draft_name,draft_mes);
                if(draft_name.value=="")
                {
                        draft_name.focus();
                        return 0;
                }
                else 
                {
                        if(draft_id)
                        {
                                if(draft_id.value=="")
                                {
                                        draft_id.focus();
                                        return 0;
                                }
                                id_name=draft_id.value;
                        }

                }
                draftname=escape(draft_name.value);
                real_mes=escape(draft_mes.value);
                data_to_send=SITE_URL+"/profile/SaveDraft.php?DRAFT_NAME="+draftname+"&DRAFT_ID="+id_name+"&DRAFT_MES="+real_mes+"&D_STATUS="+d_status;
                draft_main.innerHTML=dra_loader;
		send_ajax_request(data_to_send,"","after_save_draft","POST");
            //    sendAjaxRequestForContact("SAVE_DRAFT",data_to_send);
        }
                

}
function after_save_draft()
{
	var s_draft_show="s_draft_show";
	if(from_view_similar)
	{
		s_draft_show="s_draft_show_page";
	}
	var draft_main=dID(s_draft_show);
	if(result=='A_E')
	{
		draft_main.innerHTML="<span>"+common_error+"</span>";
	}
	if(result.substr(0,5)=='ERROR')
	{
		draft_main.innerHTML="<span>"+result.substr(6,result.length)+"</span>";
	}
	else
	{
		draft_main.innerHTML=dra_end1+result+dra_end2;
	}
}
function disable_button(id_name,upto)
{
                for(i=0;i<upto;i++)
                {
                        if(eval("dID('"+id_name+i+"')"))
                                eval("dID('"+id_name+i+"').style.display='none'");
                }
}
function enable_button(id_name,upto)
{
        for(i=0;i<upto;i++)
                {
                        if(eval("dID('"+id_name+i+"')"))
                                eval("dID('"+id_name+i+"').style.display='inline'");
                }
}
function show_save(bool,from_sim)
{
	var but_id="but_id";
	var mes_id="mes_";
	if(typeof(from_sim)!='undefined')
	{
		var but_id="but_id_page";
		var mes_id="page_mes_";
	}
        if(bool)
        {
		if(dID(but_id))
			dID(but_id).style.display='inline';
                enable_button(mes_id,2);
        }
        else
        {
	        disable_button(mes_id,2);
		if(dID(but_id))
			dID(but_id).style.display='none';
	}
}
function red_view_similar(var_data)
{
	if(var_data)
	{
			var all_data=var_data.split(":");

                        var contact_id=all_data[1];
                        var sim_username=all_data[2];
		
			//Send reminder case.
			if(sim_username=='sendrem')
				draft_id="";

                        var type_of_con=all_data[3];
                        var from_search=0;
                        if(dID("from_search"))
                                from_search=1;
			if(typeof(MES)!='undefined' &&  draft_id!="")
			{
				var temp_mes=unescape(text_message);
				var temp_act=MES[draft_id];
				
				temp_mes=temp_mes.replace(/\n/g,"");
				temp_mes=temp_mes.replace(/\r/g,"");
				temp_act=temp_act.replace(/\n/g,"");
				temp_act=temp_act.replace(/\r/g,"");
				if(temp_act==temp_mes)
				{
					text_message="";
				}
			}

			
			var html_form="<form name=fr1 id=vsp method=POST action='/profile/view_similar_profile.php?&draft_name="+draft_id+"&contact="+contact_id+"&SIM_USERNAME="+sim_username+"&stype=CN&"+navig+"&type_of_con="+type_of_con+"&from_search="+from_search+"'><input type='hidden' name='MESSAGE' value='"+text_message+"'></form>";

			document.body.innerHTML=html_form+document.body.innerHTML;

			dID("vsp").submit();

//                        var url_for_contact="/profile/view_similar_profile.php?&draft_name="+draft_id+"&contact="+contact_id+"&SIM_USERNAME="+sim_username+"&MESSAGE="+text_message+"&stype=CN&"+navig+"&type_of_con="+type_of_con+"&from_search="+from_search;
  //                      document.location.href=url_for_contact;
			$.colorbox.close();
	}
}
function parsedata ( query ) {
   var Params_str = "";
   if ( ! query ) {return Params;}// return empty object
   var Pairs = query.split("viewprofile.php?");
   for ( var i = 0; i < Pairs.length; i++ ) {
      Params_str=Pairs[1];
   }
	return Params_str;
   
}
var loader_Id="call_directly";
function show_contact(both_users,profilechecksum,from_search)
{
        
        send_ajax_request("call_directly.php?&ajax_error=2&profilechecksum="+profilechecksum+"&show_con=1&both_users="+both_users+"&from_search="+from_search,"call_direct_loader","show_direct_contact");
}
var previous_data="";
function call_direct_loader()
{
        previous_data=dID(loader_Id).innerHTML;
        dID(loader_Id).innerHTML='<div style="text-align:center;margin-top:65px;"><img src="IMG_URL/img_revamp/loader_big.gif"></div>  <div class="sp16"></div> <div class="sp8"></div>  <div style="text-align:center" class="t14 b"></div>';
}
function show_direct_contact()
{
        if(result=='A_E')
                dID(loader_Id).innerHTML=previous_data;
        else
                dID(loader_Id).innerHTML=result;
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
function show_m(param)
{
	if(param=="show"){
		dID('comm_more').style.display='inline';
		dID('comm_iframe').style.display='inline';
		return false;
	}
	else{
		dID('comm_more').style.display='none';
		dID('comm_iframe').style.display='none';
	}

}
/* Function for the More community Layer*/
function show_more(param){
	if(param=="show"){
		dID("comm_more").style.display='inline';
		dID("comm_more_iframe").style.display='inline';
		common_check=1;
                function_to_call="show_more('hide')";
		return false;
	}
	else{
		dID("comm_more").style.display="none";
		dID("comm_more_iframe").style.display="none";
		return false;
	}
}

/* Function for the More community Layer*/
function show_more_home(param){
	if(param=="show"){
		dID("comm_more").style.display='inline';
		dID("comm_more_iframe").style.display='inline';
		//common_check=1;
                //function_to_call="show_more_home('hide')";
		return false;
	}
	else{
		dID("comm_more").style.display="none";
		dID("comm_more_iframe").style.display="none";
		return false;
	}
}

function show_rest(key,show){
	if(show=="hide"){
		dID(key).style.display='none';
		dd=0;
	}
	else
	{
		if(key)
			dID(key).style.display='inline';
	}
}

function show_loggedIn_window()
{
	var url_str='/profile/login.php?SHOW_LOGIN_WINDOW=1';
	$.colorbox({href:url_str});
}
function show_ajax_connectionErrorLayer()
{
	var url_str='/static/connectionErrorLayer';
	$.colorbox({href:url_str});
}
function show_login_layer(url)
{	
	if(url=="call")
	{
		var url=SITE_URL+"/profile/login.php?SHOW_LOGIN_WINDOW=1&after_login_call="+escape("show_callnow_layer()");
		$.colorbox({href:url});
	}
	else
	{
		check_window("from_link");
		$.colorbox({href:url});
     }   
}
function show_hideinfo(divname,showhide)
{
	var divid="#hide_"+divname;
	var dotid="#dot_"+divname;
	var readmore="#readmore_"+divname;
	var readless="#readless_"+divname;
	if(showhide)
	{
		
		$(divid).css("display","inline");
		$(dotid).css("display","none");
		$(readmore).hide();
		$(readless).show();
	}
	else
	{
		//$(divid).hide("slow");
		$(divid).css("display","none");
		$(readmore).show();
		$(readless).hide();
		$(dotid).css("display","inline");
	}
	return false;
}
function setbanner(keyid,keyvalue,keywidth,keyheight)
{
	bannerSrc[keyid]=keyvalue;
	bannerWidth[keyid]=keywidth;
	bannerHeight[keyid]=keyheight;
	
}
function g_code()
{
	var domainCode={};
	domainCode[".hindijeevansathi.in"]="UA-20942264-1";
	domainCode[".jeevansathi.co.in"]="UA-20941176-1";
	domainCode[".marathijeevansathi.in"]="UA-20941180-1";
	domainCode[".punjabijeevansathi.com"]="UA-20941670-1";
	domainCode[".punjabijeevansathi.in"]="UA-20941669-1";
	domainCode[".jeevansathi.com"]="UA-179986-1";
	
	var host_url="http://"+window.location.host;
	var j_domain=host_url.match(/:\/\/[\w]{0,10}(.[^/]+)/)[1];
	j_domain=j_domain.toLowerCase();

	var ucode=domainCode[j_domain];
	
	if(ucode)
	{
		var _gaq = _gaq || [];
		
		_gaq.push(['_setAccount', ucode]);
		_gaq.push(['_setDomainName', j_domain]);
		_gaq.push(['_trackPageview']);
		_gaq.push(['_trackPageLoadTime']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();		
	}
}
var first_html="<input type=\"image\"  src=\"IMG_URL/P/images/request-photo.jpg\" class=\"btn-req-photo\" onclick = \"photo_ajax_request('PROFILECHECKSUM','photo_request_end',PROFILEID,'search')\"/>";
var sec_html = "<img src=\"IMG_URL/P/images/loader_extra_small.gif\" class=\"loader\"/>";
var third_html = "<font class=\"btn-req-photo white  b\">Photo request sent</font>";
var requestFrom="search";
function photo_ajax_request(profilechecksum,java_after_send,profileId,fromPage)
{  
   requestFrom=fromPage;
   var tim = (new Date()).getTime();
   if(user_login)
   { 
	    photo_request_start(profileId);
            var data1={"newPR":1,"profilechecksum":profilechecksum};
            var url1="/social/photoRequest";
		$.ajax({type: "GET",url:url1,data:data1,cache:false, success:function(responseText){
                responseText = $.trim(responseText);
                if(responseText=="true")
                        responseText="";
                photo_request_end(responseText,profileId,profilechecksum);
        }});
	}
	else
    {
		var fnc_to_call="photo_ajax_request(\""+profilechecksum+"\",\""+java_after_send+"\","+profileId+",\""+fromPage+"\")";
		if(fromPage=='search')
			handleRegistrationLayer();
		else
			$.colorbox({href:"/profile/login.php?SHOW_LOGIN_WINDOW=1"});
		
    }
}
function photo_request_start(profileId)
{
	var photoHTML = sec_html;
	$("#photo_req_layer"+profileId).text("");
	$("#photo_req_layer"+profileId).append(photoHTML);
	
	
}
function invalid_chars_present(profile_id) {
	
  var invalid_chars = ["'", "$", "#", "!", "@", "%", "^", "&", "*", "(", ")", "-", "_", "+", "=", "\\", "{", "}", "|", "[", "]", ":", ";", "\"", ",", "<", ">", ".", "?", "/", "~", "`"];
  var char_present = false;
  for (var i = 0; i < profile_id.length; ++i) {
    if ($.inArray(profile_id[i], invalid_chars) !== -1) {
      char_present = true;
      break;
    }
  }

  if (true === char_present) {
    return true;
  }
  else {
    return false;
  }
}
function photo_request_end(error,profileId,profilechecksum)
{


	var mes="Oops, please try after sometime.";	
	var photoHTML,oldHTML,newHTML;
	if(error)
	{	
		if(error=='A_E')
			mes=common_error;
		if(error=='F')
			mes="You cannot request photo as this person has filtered you.";
		if(error=='G')
			mes="You cannot request photo to a profile of the same gender.";
		if(error=='E')
			mes="You have already requested this user for photo.";
		if(error=='U')
			mes="You cannot request photo as your profile is still being screened.";
		if(error=="LOGIN")
			mes="Please login first";
		photoHTML = first_html;
		photoHTML = photoHTML.replace(/PROFILEID/g,profileId);
		photoHTML = photoHTML.replace(/PROFILECHECKSUM/g,profilechecksum)
		$("#photo_req_layer"+profileId).text("");
		$("#photo_req_layer"+profileId).append(photoHTML);
		oldHTML = dID('err_mes').innerHTML;
		newHTML=oldHTML.replace(/PROFILEID/g,profileId);
		newHTML=newHTML.replace(/ERROR MESSAGE/g,mes); 
		$("#PHOTO_REQ"+profileId).text("");
		$("#PHOTO_REQ"+profileId).append(newHTML);		
			
		if(error=="LOGIN")
		{
			if(requestFrom=='search')
				handleLoginLayer();
			else
				$.colorbox({href:"/profile/login.php?SHOW_LOGIN_WINDOW=1"});
		}
	}
	else
	{
		photoHTML = third_html;
		$("#photo_req_layer"+profileId).text("");
		$("#photo_req_layer"+profileId).append(photoHTML);		
		oldHTML = dID('req_mes').innerHTML;
		newHTML=oldHTML.replace(/PROFILEID/g,profileId); 
		$("#PHOTO_REQ"+profileId).text("");
		$("#PHOTO_REQ"+profileId).append(newHTML);
		
	}
	check_window('close_photo_mes('+profileId+')');
	function_to_call="close_photo_mes("+profileId+")";
	common_check=1;
	
	error="";
}
function close_photo_mes(profileId)
{
	common_check=0;
	function_to_call="";
	$('#success_mes'+profileId).hide('slow');	
}

function sub_header_fn(isLogin,pageName,pageNo)
{
	if(pageNo == 9999)
	{
		if(pageName=="membership")
			var url = SITE_URL+"/static/registrationLayer?pageSource=membershipMain";
		else
			var url =  SITE_URL+"/static/registrationLayer?pageSource=searchpage";
		$.colorbox({href:url});
		return false;
	}
	if(!isLogin)
	{
		if(pageName=="SearchPage")
			var url = SITE_URL+"/static/registrationLayer?page="+pageNo+"&pageSource=searchPage";
		else if(pageName=="membership")
		{
			var url = SITE_URL+"/static/registrationLayer?pageSource=membershipMain";
		}
		else
			var url = SITE_URL+"/profile/before_log.php?page="+pageNo;
		$.colorbox({href:url});
		return false;
	}
	else
		return true;
}


function removeJunk(text_val)
{
        text_val=text_val.replace(/&amp;/g, "&");
        text_val=text_val.replace(/&quot;/g, "\"");
        text_val=text_val.replace(/&#039;/g, "'");
        text_val=text_val.replace(/&lt;/g, "<");
        text_val=text_val.replace(/&gt;/g,">");
        text_val=text_val.replace(/#n#/g,"\n");
        text_val=text_val.replace(/<br>/g,"\n");
	text_val=text_val.replace(/< br >/g,"\n");
        return text_val;
}

function seo_change_tab(to_show)
{
	var sec_arr = new Array('tab1','tab2','tab3','tab4','tab5','tab6','tab7','tab8');
	var li_arr = new Array('community_li','caste_li','religion_li','city_li','occupation_li','state_li','nri_li','splcases_li');
	for(var i=0;i<sec_arr.length;i++)
	{
		if(document.getElementById(sec_arr[i]))
		{
			document.getElementById(sec_arr[i]).style.display="none";
			if(sec_arr[i] == to_show)
			{
				dID(to_show).style.display='inline';
				dID(to_show).style.outline=0;
				if(to_show!='tab1')
				dID(to_show).focus();
				document.getElementById(li_arr[i]).className = 'active';
			}
			else
				document.getElementById(li_arr[i]).className = '';
		}
	}
}

function gutterBanner()
{
       
	
	
	var height = 600;
	var winHeight = $(window).height();
	var footerTop = $('#ftr-id').offset().top;
	var bannerTop = $('#gutterBanner').offset().top;
	var gap = 7;
	$(window).scroll(function (event) {
	    
		var y = $(document).scrollTop();
		
	    // whether that's below the footer
		if (y+height>=footerTop && bannerTop+height<=footerTop) {
			 $("#gutterBanner").removeClass('sideBarFixed').css('margin-top',footerTop-height-gap-160+'px');
	    	
	    }
		else if (y >= bannerTop && y+height <=footerTop) {			
			$("#gutterBanner").addClass('sideBarFixed').css('margin-top','-80px');
	    	
	    }
		else
		{
			$("#gutterBanner").removeClass('sideBarFixed').css('margin-top','179px');
		}
	    });
	}

//This function was previously present in thickbox javascript file
function tb_getPageSize(){
        var de = document.documentElement;
        var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
        var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
        arrayPageSize = [w,h];
        return arrayPageSize;
}

function sendXSLTrequest(xslUrl,xmlUrl,displayId,params,condition)
{
        var xsl;
        var xml;
/*
        $.ajax({type: "GET",url:xslUrl,cache:true,beforeSend:function(xhr, settings){
			//try{xhr.responseType = "msxml-document";} catch(err){}
}, success:function(data){
                xsl = data;
                $.ajax({type: "GET",url:xmlUrl,data:params,beforeSend:function(xhr, settings){
	try{xhr.responseType = "msxml-document";} catch(err){}
}, success:function(data){
			alert("HERE");
                        xml = data;
                        if (window.ActiveXObject)
                        {
                                ex=xml.transformNode(xsl);
                                document.getElementById(displayId).innerHTML=ex;
                        }
                        // code for Mozilla, Firefox, Opera, etc.
                        else if (document.implementation && document.implementation.createDocument)
                        {
                                xsltProcessor=new XSLTProcessor();
                                xsltProcessor.importStylesheet(xsl);
                                resultDocument = xsltProcessor.transformToFragment(xml,document);
                                document.getElementById(displayId).innerHTML="";
                                document.getElementById(displayId).appendChild(resultDocument);
                        }        
                        if(condition=="searchPage")
			{
				alert("BB");
                                $.colorbox({href:"#"+displayId, inline:true, overlayClose:"albumCode", escKey:"albumCode", onComplete:function(){basicData();}});
			}
                },dataType:"xml"});
        },dataType:"xml"});
	*/

	xsl = loadXMLDoc(xslUrl);
	xml = loadXMLDoc(xmlUrl+"?"+params);
	if (window.ActiveXObject || "ActiveXObject" in window)
	{
		ex=xml.transformNode(xsl);
		document.getElementById(displayId).innerHTML=ex;
	}
	// code for Mozilla, Firefox, Opera, etc.
	else if (document.implementation && document.implementation.createDocument)
	{
		xsltProcessor=new XSLTProcessor();
		xsltProcessor.importStylesheet(xsl);
		resultDocument = xsltProcessor.transformToFragment(xml,document);
		document.getElementById(displayId).innerHTML="";
		document.getElementById(displayId).appendChild(resultDocument);
	}        
	if(condition=="searchPage")
	{
		$.colorbox({href:"#"+displayId, inline:true, overlayClose:"albumCode", escKey:"albumCode", onComplete:function(){basicData();}});
	}
}
function loginValidate(redirect)
{
	
$("#error_mess").html("");
var user_name=$("#username").val();
var pass_word=$("#password").val();
var show_cross=1;
if(!checkemail($("#username").val()))
{
        $("#error_mess").html(cur_err_msg);
        
        $("#username").focus();
	if(user_name && pass_word)
	{
		show_cross=0;
        $("#error_mess").prev().css('display','none');
        $("#error_mess").html(" Loading <img src='IMG_URL/images/searchImages/loader_extra_small.gif' style='margin-top:1px'></img>");
		loginUrl=SSL_SITE_URL+"/static/verifyAuth?username="+user_name+"password="+pass_word;
					$("#homepageLogin").attr('action',loginUrl);
		/*$.post( "/static/verifyAuth", { "username": user_name, "password": pass_word })
			  .done(function( data ) {
			$("#error_mess").html(cur_err_msg);
                            if(data)
                                $("#error_mess").html(data);
                                $("#error_mess").prev().css('display','inline');
			
		  });*/
	}
}
else if(!$("#password").val())
{
        $("#error_mess").html(" Please provide valid password");
        $("#password").focus();
}
if($("#error_mess").html())
{
	if(show_cross)
        $("#error_mess").prev().css('display','inline');
        return false;
}
else
        $("#error_mess").prev().css('display','none');

if(redirect)
	loginUrl=SSL_SITE_URL+"/profile/redirect.php?redirectProperly=1";
else
	loginUrl=SSL_SITE_URL+"/profile/login.php?redirectProperly=1";
$("#homepageLogin").attr('target', "iframe_login");

$("#homepageLogin").attr('action',loginUrl);
return true;
}



function after_login(result)
	{
		//hide loader
		//A_E --> error because of query failure
		//N --> Wrong username/password
		//O --> Stopping offline login
		//Y --> succesfully login
		//YI --> incomplete profile.
		
		if(result=='N')
		{
			$("#hompageLoginError").css("display","block");
			return 1;
		}
		else if (result=='O')
		{
			if($("#loginError").length > 0)
			{
				$("#loginError").html("Profile Inactive");
				$("#errorMsg").show();
			}
			if($("#loginErrorRegPage").length > 0)
			{
				$("#loginErrorRegPage").html("Profile Inactive");
				$("#invalidEmail").hide();
				$("#invalidPassword").hide();
				$("#errorMsg").show();
			}
			return 1;
		}
		else if(result=='Y' || result=='YI')
		{
			var address_url=window.top.location.href;
			var temp_url=window.location.href;
			if(pageSource=="successStory")
                        {
                              	$.colorbox({href:"/successStory/layer?width=700"});
                                return 1;
                        }
			else if(pageSource=="MemChsPlan"||pageSource=="MemChsVAS"||pageSource=="MemPymtOpt"||pageSource=="membershipMain")
			{
				address_url="/membership/jspc";
				window.top.location = address_url;
                                return 1;
			}
			else if(pageSource.indexOf("MemJSEx")>-1)
                        {
				jsExcRadioSel="X"+pageSource.substring(7);
                                var nextAction="/membership/jspc?displayPage=3&mainMem=X&mainMemDur="+jsExcRadioSel.replace('X','');
                        }
			else if((address_url.indexOf("success_stories")!=-1 || address_url.indexOf("successStory")!=-1 || temp_url.indexOf("success_stories")!=-1) && $("#nextAction").val()=="")
			{
				window.top.location="/profile/intermediate.php?parentUrl=/success/success_stories.php";
				return 1;
			}
			else if(address_url.indexOf("intermediate.php")!=-1 || address_url.indexOf("login.php")!=-1)
			{
				address_url = SITE_URL+"/profile/intermediate.php?parentUrl=/search/perform?searchId="+searchId+"&currentPage="+currentPage;
				window.top.location = address_url;
				return 1;
			}
			else if($("#nextAction").val() === undefined)
			{
				var nextAction = "/search/perform?searchId="+searchId+"&currentPage="+currentPage;
			}
			else if($("#nextAction").val() != '')
			{
				var nextAction = $("#nextAction").val();
			}
			else
			{
				var nextAction = "/search/perform?searchId="+searchId+"&currentPage="+currentPage;
			}
			window.location = SITE_URL+"/profile/intermediate.php?parentUrl="+nextAction;
			return 1;
		}
		
	}

function onFrameMessageSearchReceived(message)
{
	if(message.origin === SSL_SITE_URL)
	{		
			if(!window.addEventListener)
			{
				var emailCheck=message.data.indexOf("Email");
				if(emailCheck!==-1 || message.data.indexOf("invalidAuth")!==-1)
				{
					if(searchPageLoginLayer){
						em_after_login();
						if(emailCheck!==-1)
						{
							$("#invalidEmail").html(message.data);
							$("#loginError").html(message.data);
						}
					}
					else if(pinkLoginRegistrationLayer)
					{
						if(emailCheck!==-1)
						{
							$("#layer_email_error").html(message.data);
							stop_loader_login();
						}
					}
					else
					{
						$("#error_mess").html(cur_err_msg);
						if(emailCheck!==-1)
						{
							$("#error_mess").html(message.data);
							 $("#error_mess").prev().css('display','inline');
						}
					}
				}
				else
				{
					after_login(message.data);
				}
			}
			else
			{
				var emailCheck=message.data.body.indexOf("Email");
				if(emailCheck!==-1 ||  message.data.body.indexOf("invalidAuth")!==-1 )
				{
					if(searchPageLoginLayer){
						em_after_login();
						if(emailCheck!==-1)
						{
							$("#invalidEmail").html(message.data.body);
							$("#loginError").html(message.data.body);
						}
					}
					else if(pinkLoginRegistrationLayer)
					{
						
						stop_loader_login();
						if(emailCheck!==-1)
						{
							$("#layer_email_error").html(message.data.body);
						}
					}
					else
					{
						$("#error_mess").html(cur_err_msg);
						if(emailCheck!==-1)
						{
							 $("#error_mess").html(message.data.body);
							 $("#error_mess").prev().css('display','inline');
						}
					}
				}
				else
				{
					after_login(message.data.body);
				}
					
			}
	}
	
		
}

if(window.addEventListener)	
	{
		window.addEventListener("message", onFrameMessageSearchReceived, false);
	}
	else if ( window.attachEvent ) //For IE 8
	{
		window.attachEvent( "onmessage", onFrameMessageSearchReceived );
	}else if( window.onLoad)
	{
		window.onload = onFrameMessageSearchReceived;
	}

if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);

