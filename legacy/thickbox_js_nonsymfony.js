/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
*/
//alert(SITE_URL);
var tb_pathToImage = "IMG_URL/profile/images/loadingAnimation.gif";
var need_to_apply_behaviour=0;

/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/

//on page load call tb_init
$(document).ready(function(){   
//<!--LAVESH REVAMP -->
//	tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
//	imgLoader = new Image();// preload image
//	imgLoader.src = tb_pathToImage;
//<!--LAVESH REVAMP -->
});

//add thickbox to href & area elements that have a class of .thickbox
var thickbox_initiated=0;
function tb_init(domChunk){
	if(thickbox_initiated)
                return 2;
        else
                thickbox_initiated=1;
	$(domChunk).click(function(){
	var t = this.title || this.name || null;
	var a = this.href || this.alt;
	var g = this.rel || false;
	tb_show(t,a,g);	
	this.blur();
	return false;
	});
}

function tb_show(caption, url, imageGroup) {//function called when the user clicks on a thickbox link
		
		
		
		 //Adding Ajax_error as separate error field.
		 if(url.indexOf("?")!=-1)
		 	url=url+"&ajax_error=1";
		 else
			 url=url+"?ajax_error=1";

		//url=escape(url);
		if(url.indexOf("selectedLabel")!=-1)
		{
			while(url.indexOf(" ") != -1)
			{
				url=url.replace(" ","%20");
			}
			url=url.replace("+","QQQ");
		}
		//alert("#");
		//If expression of interest is coming from detailed page, then don't show the loading image and don't change the opacity of page.		
		var from_viewprofile=0;
		if(url.indexOf("from_viewprofile=Y")!=-1)
			from_viewprofile=1;

		if(document.getElementById("check_clicked"))
		{
			var check_clicked=document.getElementById("check_clicked");
			ini_val=check_clicked.value;
			if(ini_val==2)
			{
				//This is required since express interest button is clicked and no profile is selected . 
				check_clicked.value=0;
				return 1;	
			}
		}
		if(document.getElementById("delete_profile_exists"))
		{
			if(document.forms['delete_profile'].delete_reason.value!="I found my match on Jeevansathi.com")
			{
				return 1;
			}
		}
		//added by manoranjan for showing refine layer in the middle
		if(typeof(top.js_window)!='undefined'){
			if (navigator.appName == "Microsoft Internet Explorer"){
			 top.scrollTo(0,55);
			}else{
			 top.scrollTo(0,95);
			}
		}
	try {
		if (typeof document.body.style.maxHeight === "undefined") {//if IE 6
			$("body","html").css({height: "100%", width: "100%"});
			//LAVESH RAWAT	
			//$("html").css("overflow","hidden");
			document.body.scroll="yes";//added
			//LAVESH RAWAT	
			if (document.getElementById("TB_HideSelect") === null) {//iframe to hide select elements in ie6
				$("body").append("<iframe id='TB_HideSelect'></iframe><div id='TB_overlay'></div><div id='TB_window'></div>");
				$("#TB_overlay").click(tb_remove);
			}
		}else{//all others
			if(document.getElementById("TB_overlay") === null){
				$("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>");
				$("#TB_overlay").click(tb_remove);
					
			}
		}
		if(tb_detectMacXFF()){
			$("#TB_overlay").addClass("TB_overlayMacFFBGHack");//use png overlay so hide flash
		}else{
				$("#TB_overlay").addClass("TB_overlayBG");//use background and opacity
		}
		
		if(caption===null){caption="";}


		//If Exp of interest is called from search than we have to show eoi layer instead of animator image.
		if(url.indexOf("TYPE_OF")!=-1)
		{
			//Exp_layer_setting is defined in new_changes_search.htm
			var data_to_get=exp_layer_setting();
			$("body").append("<div id='TB_load'>"+data_to_get+"</div>");
			TB_WIDTH=350;
			TB_HEIGHT=300;
			tb_position_load();
			//alert(dID('TB_load').innerHTML);
			//$("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' /></div>");//add loader to the page
			
		}
		else
		{
			if(from_viewprofile)
				$("body").append("<div id='TB_load'></div>");
			else	
				$("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' /></div>");//add loader to the page
		}
		$('#TB_load').show();//show loader
		
		var baseURL;
	   if(url.indexOf("?")!==-1){ //ff there is a query string involved
			baseURL = url.substr(0, url.indexOf("?"));
	   }else{ 
	   		baseURL = url;
	   }
	   var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
	   var urlType = baseURL.toLowerCase().match(urlString);

		if(urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp'){//code to show images
				
			TB_PrevCaption = "";
			TB_PrevURL = "";
			TB_PrevHTML = "";
			TB_NextCaption = "";
			TB_NextURL = "";
			TB_NextHTML = "";
			TB_imageCount = "";
			TB_FoundURL = false;
			if(imageGroup){
				TB_TempArray = $("a[@rel="+imageGroup+"]").get71();
				for (TB_Counter = 0; ((TB_Counter < TB_TempArray.length) && (TB_NextHTML === "")); TB_Counter++) {
					var urlTypeTemp = TB_TempArray[TB_Counter].href.toLowerCase().match(urlString);
						if (!(TB_TempArray[TB_Counter].href == url)) {						
							if (TB_FoundURL) {
								TB_NextCaption = TB_TempArray[TB_Counter].title;
								TB_NextURL = TB_TempArray[TB_Counter].href;
								TB_NextHTML = "<span id='TB_next'>&nbsp;&nbsp;<a href='#'>Next &gt;</a></span>";
							} else {
								TB_PrevCaption = TB_TempArray[TB_Counter].title;
								TB_PrevURL = TB_TempArray[TB_Counter].href;
								TB_PrevHTML = "<span id='TB_prev'>&nbsp;&nbsp;<a href='#'>&lt; Prev</a></span>";
							}
						} else {
							TB_FoundURL = true;
							TB_imageCount = "Image " + (TB_Counter + 1) +" of "+ (TB_TempArray.length);											
						}
				}
			}

			imgPreloader = new Image();
			imgPreloader.onload = function(){		
			imgPreloader.onload = null;
				
			// Resizing large images - orginal by Christian Montoya edited by me.
			var pagesize = tb_getPageSize();
			var x = pagesize[0] - 150;
			var y = pagesize[1] - 150;
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;
			if (imageWidth > x) {
				imageHeight = imageHeight * (x / imageWidth); 
				imageWidth = x; 
				if (imageHeight > y) { 
					imageWidth = imageWidth * (y / imageHeight); 
					imageHeight = y; 
				}
			} else if (imageHeight > y) { 
				imageWidth = imageWidth * (y / imageHeight); 
				imageHeight = y; 
				if (imageWidth > x) { 
					imageHeight = imageHeight * (x / imageWidth); 
					imageWidth = x;
				}
			}
			// End Resizing
			
			TB_WIDTH = imageWidth + 30;
			TB_HEIGHT = imageHeight + 60;
			$("#TB_window").append("<a href='' id='TB_ImageOff' title='Close'><img id='TB_Image' src='"+url+"' width='"+imageWidth+"' height='"+imageHeight+"' alt='"+caption+"'/></a>" + "<div id='TB_caption'>"+caption+"<div id='TB_secondLine'>" + TB_imageCount + TB_PrevHTML + TB_NextHTML + "</div></div><div id='TB_closeWindow'><a href='#' id='TB_closeWindowButton' title='Close'>close</a> or Esc Key</div>"); 		
			
			$("#TB_closeWindowButton").click(tb_remove);
			
			if (!(TB_PrevHTML === "")) {
				function goPrev(){
					if($(document).unbind("click",goPrev)){$(document).unbind("click",goPrev);}
					$("#TB_window").remove();
					$("body").append("<div id='TB_window'></div>");
					tb_show(TB_PrevCaption, TB_PrevURL, imageGroup);
					return false;	
				}
				$("#TB_prev").click(goPrev);
			}
			
			if (!(TB_NextHTML === "")) {		
				function goNext(){
					$("#TB_window").remove();
					$("body").append("<div id='TB_window'></div>");
					tb_show(TB_NextCaption, TB_NextURL, imageGroup);				
					return false;	
				}
				$("#TB_next").click(goNext);
				
			}

			document.onkeydown = function(e){ 	
				if (e == null) { // ie
					keycode = event.keyCode;
				} else { // mozilla
					keycode = e.which;
				}
				if(keycode == 27){ // close
					tb_remove();
				} else if(keycode == 190){ // display previous image
					if(!(TB_NextHTML == "")){
						document.onkeydown = "";
						goNext();
					}
				} else if(keycode == 188){ // display next image
					if(!(TB_PrevHTML == "")){
						document.onkeydown = "";
						goPrev();
					}
				}	
			};
			
			tb_position();
			$("#TB_load").remove();
			$("#TB_ImageOff").click(tb_remove);
			$("#TB_window").css({display:"block"}); //for safari using css instead of show
			};
			
			imgPreloader.src = url;
		}else{//code to show html
			
			var queryString = url.replace(/^[^\?]+\??/,'');
			var params = tb_parseQuery( queryString );
			TB_WIDTH = (params['width']*1) + 30 || 350; //defaults to 630 if no paramaters were added to URL
			TB_HEIGHT = (params['height']*1) + 40 || 450; //defaults to 440 if no paramaters were added to URL

			//Done by NIKHIL for photo request layer
			if(url.indexOf('layer_photorequest.php')!=-1)
				TB_WIDTH=512+30;
			/*if(url.indexOf('AjaxContact.php')!=-1)
			{
				TB_HEIGHT=300+40;
				TB_WIDTH=660+30;
			}*/

			ajaxContentW = TB_WIDTH - 30;
			ajaxContentH = TB_HEIGHT ; //<!--LAVESH REVAMP-->
		
				

			if(url.indexOf('TB_iframe') != -1){// either iframe or ajax window		
					urlNoQuery = url.split('TB_');
					$("#TB_iframeContent").remove();
					if(params['modal'] != "true"){//iframe no modal
						$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='Close1'>close</a> or Esc Key</div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' > </iframe>");
					}else{//iframe modal
					$("#TB_overlay").unbind();
						$("#TB_window").append("<iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;'> </iframe>");
					}
			}else{// not an iframe, ajax
					if($("#TB_window").css("display") != "block"){
						if(params['modal'] != "true"){//ajax no modal
						/*
						$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'>close[x]</a></div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>");
						*/
						$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>");
						}else{//ajax modal
						$("#TB_overlay").unbind();
						$("#TB_window").append("<div id='TB_ajaxContent' class='TB_modal' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>");	
						}
					}else{//this means the window is already up, we are just loading new content via ajax
						$("#TB_ajaxContent")[0].style.width = ajaxContentW +"px";
						$("#TB_ajaxContent")[0].style.height = ajaxContentH +"px";
						$("#TB_ajaxContent")[0].scrollTop = 0;
						$("#TB_ajaxWindowTitle").html(caption);
					}
			}
			//Added by LAVESH -- NIKHIL 
			document.getElementById("TB_ajaxContent").style.height="auto";
		        document.getElementById("TB_ajaxContent").style.width="auto";
			//Added by LAVESH -- NIKHIL	
			$("#TB_closeWindowButton").click(tb_remove);
			
				if(url.indexOf('TB_inline') != -1){	
					$("#TB_ajaxContent").append($('#' + params['inlineId']).children());
					$("#TB_window").unload(function () {
						$('#' + params['inlineId']).append( $("#TB_ajaxContent").children() ); // move elements back when you're finished
					});
					tb_position();
					$("#TB_load").remove();
					$("#TB_window").css({display:"block"}); 
				}else if(url.indexOf('TB_iframe') != -1){
					tb_position();
					if($.browser.safari){//safari needs help because it will not fire iframe onload
						$("#TB_load").remove();
						$("#TB_window").css({display:"block"});
					}
				}else{
								
					$("#TB_ajaxContent").load(url += "&random=" + (new Date().getTime()),function(){//to do a post change this load method
						if(url.indexOf("TYPE_OF")!=-1)
                				{
							if(dID("TB_ajaxContent"))
                                                                show_exp_error(dID("TB_ajaxContent"));
						}
					
						tb_position();
						$("#TB_load").remove();
						tb_init("#TB_ajaxContent a.thickbox");
						$("#TB_window").css({display:"block"});

						//NIKHIL--- Changes to the margin top bcz IE7 creating problem..
						if (document.getElementById("TB_HideSelect") === null)//lavesh:need to be done for ie7 only
						{
							if(document.getElementById("TB_ajaxContent"))
                                                	{
                                                       		TB_HEIGHT=document.getElementById("TB_ajaxContent").offsetHeight;
                                                        	$("#TB_window").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
                                                	}
						}
						//LAVESH REVAMP
					        if(document.getElementById("selectedclusterid"))
					                document.getElementById("selectedclusterid").focus();

						if(need_to_apply_behaviour==1)
							Behaviour.apply();

						need_to_apply_behaviour=0;
						//LAVESH REVAMP

					});
				}
			
		}

		if(!params['modal']){
			document.onkeyup = function(e){ 	
				if (e == null) { // ie
					keycode = event.keyCode;
				} else { // mozilla
					keycode = e.which;
				}
				if(keycode == 27){ // close
					tb_remove();
				}	
			};
		}
	} catch(e) {
		//nothing here
	}
	
	
	//added by manoranjan for hideing chat bar
		 if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="block"){
			top.document.getElementById("browseBottom").style.visibility="hidden";
			top.document.getElementById("browseBottom").style.display="none";
			if(navigator.appName.indexOf("Internet Explorer") != -1){
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight);
			}else{
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight);
			}
		}
	
	
}

//helper functions below
function tb_showIframe(){
	$("#TB_load").remove();
	$("#TB_window").css({display:"block"});
}

function tb_remove() {
        if(document.getElementsByName("for_home")[0])
        {
                var d = document.getElementsByTagName('head')[0];
                var el = d.getElementsByTagName('link')[1];
                  el.parentNode.removeChild(el);
        }
        
	//tb_remove function doesn't close the layer if below condition is true (added for trac 1078)
	if(document.getElementById("disableTbRemove"))
		if(document.getElementById("disableTbRemove").style.display != 'none')
		return false;
		
	//Disable checkboxes if checked
	//LAVESH -- NIKHIL
	if(typeof check_checkbox=='function')
	{
	//	check_checkbox('NO');
	}
	check_window('no_click');
	//This is required since album disables the right click
	document.oncontextmenu=new Function("return true");
	//LAVESH -- NIKHIL
	//
	
	
	
//<!--LAVESH REVAMP -->
	if(document.getElementById("TB_overlay"))
		document.getElementById("TB_overlay").style.display = "none";
	if(document.getElementById("cluster_lavesh"))
		document.getElementById("cluster_lavesh").style.display = "none";
	if(document.getElementById("TB_window"))
		document.getElementById("TB_window").style.display = "none";
	document.body.scroll="yes";
	//document.body.style.overflow="hidden"
	//document.body.style.overflow="auto"
 	//$("#TB_imageOff").unbind("click");
	//$("#TB_closeWindowButton").unbind("click");
	$("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});
	$("#TB_load").remove();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		$("body","html").css({height: "auto", width: "auto"});
		$("html").css("overflow","");
	}
	//added by manoranjan for scrolling up the parent page
	if(typeof(top.js_window)!='undefined'){
		top.scrollTo(0,0);
	}
	
	document.onkeydown = "";
	document.onkeyup = "";
	//added by manoranjan
	if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="none"){
		top.document.getElementById("browseBottom").style.visibility="visible";
		top.document.getElementById("browseBottom").style.display="block";
		if(top.iframeHeight != 0){
			if(navigator.appName.indexOf("Internet Explorer") != -1){
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-17;
			}else{
				top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-33;
			}	
		}
	}
	
	return false;
//<!--LAVESH REVAMP -->

 	$("#TB_imageOff").unbind("click");
	$("#TB_closeWindowButton").unbind("click");
	$("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});
	$("#TB_load").remove();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		$("body","html").css({height: "auto", width: "auto"});
		$("html").css("overflow","");
	}
	document.onkeydown = "";
	document.onkeyup = "";

	return false;
}

function tb_position_load() {
//$("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH - 18 + 'px'});//<!--LAVESH REVAMP -->
//This is required since login layer width is higher than any other layer.

if(document.getElementById("change_div"))
	TB_WIDTH=700
$("#TB_load").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: 0 + 'px'});
	if ( !(jQuery.browser.msie && jQuery.browser.version < 7)) { // take away IE6
		$("#TB_load").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
	}
//	if(jQuery.browser.msie && jQuery.browser.version>=)
}
function tb_position() {
//$("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH - 18 + 'px'});//<!--LAVESH REVAMP -->
//This is required since login layer width is higher than any other layer.

if(document.getElementById("change_div"))
	TB_WIDTH=700	
$("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: 0 + 'px'});
	if ( !(jQuery.browser.msie && jQuery.browser.version < 7)) { // take away IE6
		$("#TB_window").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
	}
//	if(jQuery.browser.msie && jQuery.browser.version>=)
}

function tb_parseQuery ( query ) {
   var Params = {};
   if ( ! query ) {return Params;}// return empty object
   var Pairs = query.split(/[;&]/);
   for ( var i = 0; i < Pairs.length; i++ ) {
      var KeyVal = Pairs[i].split('=');
      if ( ! KeyVal || KeyVal.length != 2 ) {continue;}
      var key = unescape( KeyVal[0] );
      var val = unescape( KeyVal[1] );
      val = val.replace(/\+/g, ' ');
      Params[key] = val;
   }
   return Params;
}

function tb_getPageSize(){
	var de = document.documentElement;
	var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = [w,h];
	return arrayPageSize;
}

function tb_detectMacXFF() {
  var userAgent = navigator.userAgent.toLowerCase();
  if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox')!=-1) {
    return true;
  }
}

//New Added

function get71(obj) 
{
        ajaxValidation(document.getElementById("userName").value,document.getElementById("pwd").value);
}


function checkXmlHttpStatus()
{
        if (req.readyState != 4)
        {
                return;
        }
        if (req.status == 200)
        {
                var docF = document.form1;
                var got_response = req.responseText.split("#");
                if(got_response[0]!="")
                {
			//document.getElementById('containerid_savesearch1').style.paddingLeft='170px';
	      		//document.getElementById('containerid_savesearch1').innerHTML=gotResponse();
			if(got_response[0]=='A_E')
			{
				gotResponse('Y');//ajax error
				return ;
			}
	      		gotResponse();
			if(got_response[0]!='Y')
			{
				if(!current || current!='undefined')
				{
					if(newTotalSearches<3)
					{
						if(newTotalSearches)
							newTotalSearches+=1;
						else
							newTotalSearches=1;
						current=newTotalSearches;
					}
				}
				if(current==1)
				{
					save1=got_response[1];
					save1_val=got_response[0];		
				}
				else if(current==2)
				{
					save2=got_response[1];
					save2_val=got_response[0];		
				}
				else if(current==3)
				{
					save3=got_response[1];
					save3_val=got_response[0];		
				}
			}
			/*
			alert(save1_val);
			alert(save2_val);
			alert(save3_val);
			*/
                }
                else
                {
			gotResponse('Y');//ajax error
			return ;
                }
        }
        else
        {
                return;
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

function ajaxValidation(checksum,saveId,name,searchid,pid,scount,replacedid)
{
	if(!checksum)
		return ;
        var req = createNewXmlHttpObject();
        var name = escape(name);
        var searchid = escape(searchid);
	var checksum=escape(checksum);
	var saveId=saveId;
	var errorflag=0;	

        var to_post =  "saved_searches=" + name + "&" + "ajaxValidation=1&searchid=" + searchid + "&ajaxValidation=1&submit=GO&pid=" + pid + "&saveId=" + saveId + "&checksum=" + checksum;

	if(escape(replacedid))
	{
        	var replacedid = escape(replacedid);
		to_post+="&replacedid=" + replacedid;
	}
	else if(scount>2 && name)
	{
		errorflag=1;
	}
//	alert(to_post);

        if(searchid!="" && pid!='' && ( (name!='' && !errorflag && saveId!='') || saveId==3) )
        {
                req.open("POST","/P/save_search.php",true);
                req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                req.send(to_post);
                req.onreadystatechange = checkXmlHttpStatus;
		//document.getElementById('containerid_savesearch1').style.paddingLeft='170px';
		//document.getElementById('containerid_savesearch1').innerHTML=waitingResponse();
		waitingResponse();
        }
        else
        {
                document.getElementById("invalidUser").style.visibilty = "visible";
                document.getElementById("invalidUser").style.display = "block";

		if(errorflag)
		{
			document.getElementById("invalidUser").innerHTML='';
			document.getElementById("invalidUser").innerHTML='<img src=\"IMG_URL/profile/images/iconError_16x16.gif\"><b> Please select the search agent to be replaced as you can save up to 3 agents only.</b>';
			document.getElementById("searchArrNameId").focus();
		}
		else if(!saveId)
		{
			document.getElementById("invalidUser").innerHTML='<img src=\"IMG_URL/profile/images/iconError_16x16.gif\"><b>Please select atleast one checkbox.</b>';
			//document.getElementById("searchNameId").focus();
		}
		else
		{
			document.getElementById("invalidUser").innerHTML='<img src=\"IMG_URL/profile/images/iconError_16x16.gif\"><b>Please enter a name for the search agent.</b>';
			document.getElementById("searchNameId").focus();
		}
        }
}
function closeLayer()
{
	tb_remove();
}

//Checks if chat bar is not disabled, if disabled then enable it.
function show_chat_bar()
{
//added by manoranjan
        if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="none"){
                top.document.getElementById("browseBottom").style.visibility="visible";
                top.document.getElementById("browseBottom").style.display="block";
                if(top.iframeHeight != 0){
                        if(navigator.appName.indexOf("Internet Explorer") != -1){
                                top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-17;
                        }else{
                                top.document.getElementById("jeevansathi").height=parseInt(top.iframeHeight)-33;
                        }
                }
        }
}

//Enable the chat bar if disabled .
show_chat_bar();
//New Added


