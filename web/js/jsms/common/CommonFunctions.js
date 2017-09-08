function GAMapper(GAEvent, extraParams)
{
    try {
    	var PageName = "";
    	if(typeof(currentPageName) != "undefined"){
         PageName = currentPageName || "";
    	}
        var userStatus = "Unregistered";
        if(typeof(loggedInJspcGender) === "string" && loggedInJspcGender.length > 0){
            userStatus = loggedInJspcGender;
        }

        var GAMapping = {
        	"GA_HOME_PAGE"					:["V", "Home page"],

        	"GA_LOGIN_REPONSE_FAIL"			:["E", "Login Response", "Success"],
        	"GA_LOGIN_REPONSE_SUCCESS"		:["E", "Login Response", "Fail"],
        	"GA_HOME_LOGIN_BTN"				:["E", PageName || "Login Page", "Login Button"],
        	"GA_HOME_REGISTER"				:["E", PageName || "Login Page", "Register Button"],
        	"GA_HOME_SEARCH"				:["E", PageName || "Login Page", "Search Button"],
        	"GA_HOME_FORGOT"				:["E", PageName || "Login Page", "Forgot Password"],

        	"GA_FORGOT_CANCEL"				:["E", "Forgot Page", "Cancel"],
        	"GA_FORGOT_RESET"				:["E", "Forgot Page", "Reset"],
            // verify otp layer
            // 
            "GA_PVS1_EDIT_NUMBER"			:["E", "Phone Verification", "Edit Number"],
            "GA_PVS1_VALIDATE_NUMBER"		:["E", "Phone Verification", "Continue"],

            "GA_PVS1_VERIFY_BTN"			:["E", "Phone Verification", "Verify Button"],
            "GA_PVS2_SHOW"					:["V", "Submit Otp Layer"],
            "GA_PVS2_VERIFY_BTN"			:["E", "Verify Otp Layer", "Verify Button"],
            "GA_PVS2_RESEND"				:["E", "Verify Otp Layer", "Resend"],
            "GA_PVS3_WRONGOTPLAYER"			:["V", "Wrong otp layer"],
            "GA_PVS3_TRIALSOVER"			:["V", "Trials Over layer"],
            "GA_PVS3_VERIFY_SUCCESS"		:["V", "Otp verified layer"],
            "GA_PVS3_WRONGOTPLAYER"			:["V", "Wrong otp layer"],
            "GA_PVS3_TRIALSOVER"			:["V", "Trials over layer"],


            "GA_PVS2_MISS_CALL"				:["E", "Phone Verification", "Miss Call"],
            "GA_PVS3_STOP"					:["E", "Phone Verification Attempt", "Stop"],
            "GA_PVS3_VERIFIED_OK"			:["V", "Phone Verified OKay layer"],

            "GA_OTP_VERIFY_FAILED"			:["E", "Phone Verification Attempt", "Failed"],

            "GA_CAL_CLOSE"					:["E", "CAL " + extraParams['currentPageName'] , "Close"],
            "GA_CAL_ACCEPT"					:["E", "CAL " + extraParams['currentPageName'] , "Open"],


            "GA_MYJS_PAGE"					:["V", "Myjs page"],
            "GA_PHONEVERIFICATION_PAGE"		:["V", "Phone Verification Screen"],
            "GA_CAL_PAGE"					:["V", "CAL "+extraParams['layerid']],

  			"GA_CONTACT_ENGINE"				:["E", PageName || "Contact Engine",  extraParams["actionDetail"] || "default"]

        }
        if(GAMapping[GAEvent]){
        	// console.log(GAMapping[GAEvent]);
            if(GAMapping[GAEvent][0] == "E"){
                trackJsEventGA(GAMapping[GAEvent][1], GAMapping[GAEvent][2], userStatus);
            }else if(GAMapping[GAEvent][0] == "V"){
                _gaq.push(['_trackPageview', GAMapping[GAEvent][1]]);            
            }
        }
    }
    catch(err) {
        return;
    }
}

/**
* This function will replace string with mapping present in object(mapObj)
* @param str {string}
* @param mapObj {object}
* example of  mapbj
	var mapObj = {
	search1:replace1,
	search2:replace2
	};
*/
$.ReplaceJsVars = function(str,mapObj){
	var re = new RegExp(Object.keys(mapObj).join("|"),"gi");
	str = str.replace(re, function(matched){
	  return mapObj[matched];
	});
	return str;
}

/**
* This function will add paramter to existing string and if that name exits it removes
* @param str {string} 
* @param param {string}
* @param value {string}
* @return updated string {string}
*/
$.addReplaceParam = function(str,param,value){
	str = str.replace(param,'noUseVarHahH');
	str = str+"&"+param+"="+value;
	return str;
}

/**
* This fucntion will replace null by blank values
*/
function removeNull(msg)
{
        if(msg)
                return msg;
        return '';
}

function isSessionStorageExist()
    {
        var bVal = true;
        if(typeof(Storage)=='undefined')
            bVal = false;
        
        try{
            sessionStorage.setItem('testLS',"true");
            sessionStorage.getItem('testLS');
            sessionStorage.removeItem('testLS');
        }catch(e)
        {
            bVal = false;
        }
        return bVal;
    }

/**
* This function will show slider with validation message {e} at a particular height {divhgt}
* @param className {string} 
* @param e {string}
* @param addClass {string}
*/
function showSlider(className, e, addClass,ifVisible,timeDuration) {

        var divhgt = 0;
        if(typeof(timeDuration)==='undefined') timeDuration = 3000;
	if(ifVisible)
	{
		if(!$(className).is(":visible"))
			className='';
	}
        if(className != "null")
                divhgt = $(className).height();

	//-10px and 110% added for android browser on search band.
        var divstr = '<div id="page-wrap" style="position:fixed;top:'+divhgt+'px;width:100%;z-index:20000;left:0px">' + '<div id="note" class="'+addClass+'" style="color:#fff;font-size:14px;text-align:center;font-family: "Roboto Light, Arial, sans-serif, Helvetica Neue",Helvetica;">' + e + '</div>' + '</div>';
        $("body").prepend(divstr);

        $("#page-wrap").slideDown( "slow", function() {
                setTimeout(function()
                {
                       $("#page-wrap").slideUp();
                       $("#page-wrap").remove();		
                },timeDuration);
        });
}

/**
* This function will add error message at the bottom of the page
* @param msg {string }message to be displayed
* @param action {string} href
*/
function bottomErrorMsg(msg,action,addclass)
{
	var msg1 = '<div class="fullwid srp_bgmsg pad5 color2 fontlig txtc '+addclass+'">'+msg+'</div>';
	if(action)
		msg1 = '<a href="'+action+'">'+msg1+'</a>';
	$( "body" ).append(msg1);
}

function topErrorMsg(msg,action,addclass)
{
	$(".loaderTopDiv").addClass("fullwid srp_bgmsg pad5 color2 fontlig txtc");
	$("."+addclass).html(msg);
}
var clickEventType=((document.ontouchstart!==null)?'click':'touchstart');
clickEventType="click";
function showCenterLoader(){
 
        $("body").prepend("<div class='overlay'><table style='width: 100%;height: 100%;align-content: center;text-align: center;'><TR><TD><img src='/profile/images/ajax-loader.gif'/></td></tr></table></div>");
        disable_scroll();
}

function capitalise(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}
var checkEmpty=function(label,val)
	{
		if(typeof(val)=='undefined' || val=="" || val==null || val=="-")
		{
			
			return {0:"<g>"+label+"</g>",1:1};
		}
		return {0:label,1:0} ;
	}
	
var changeHash=0;
var browserback=0;
var HistoryStore={};


function UpdateHtml(str,json)
{
	
	$.each(json, function(key,val){
		var re = new RegExp("\{\{"+key+"\}\}", "g");
			str=str.replace(re,val);
		});
		str=str.replace(/\{\{\w+\}\}/g,"");
		return str;
}


function fetchEditDetails(key,to_search,json)
{
	var store="";
	$.each(json["OnClick"],function(key,val){
			if(val.key==to_search)
				store=val.value;
		});
		return store;
}
function removeOthersFieldValue(json1,json2)
{
	var othersRemove=1;
	var output=json2;
	var objLength2=Object.keys(json2).length;
	var objLength1=Object.keys(json1).length;
	if(Object.keys(json1).length<=0)
		othersRemove=0;
	if(othersRemove)
	{	
		$.each(json2,function(key,value){
				$.each(value,function(k,v){
					if(v=="Others")
						delete output[key][k];
			});
		});
	}
	if(objLength1)
	for(var i=0;i<objLength1;i++)
		output[objLength2+i]=json1[i];
	
	
	return output;
}





function jsTimedOut()
{
	window.location.href = "/static/LogoutPage?prev_url=";
}
$(document).ready(
function(){
	BindNextPage();
}
);


function BindNextPage(){
	
	$("[bind-slide]").each(function(key,val){
		
		var url=$(val).attr("href");
		
		var bindSlide=parseInt($(val).attr("bind-slide"));
		
		//$(val).attr("href","#");
		$(val).unbind("click");
		$(val).bind("click",function(){
			
			if(url && typeof(url) == "string" && url.indexOf("search/topSearchBand")!=-1)
			{
				var d = new Date();
				url = url+"&stime="+d.getTime();
				
			}	
			if(bindSlide==2)
				ShowNextPage(url,0,1);
			else	
				ShowNextPage(url,0);
			return false;
		});
	});
}
function ShowNextPage(url,nottostore,transition)
{
	localStorage.setItem("prevUrlListing",window.location.href);
	if(typeof(history.pushState)=='undefined')
	{
		document.location.href=url; 
		return;
	}
	stopTouchEvents(1);
        var timer=0;
        if(!$("#hamburger").hasClass("dn"))
        {
            $("#wrapper").trigger("click");
            timer=animationtimer;
        }
        setTimeout(function(){
                if(typeof(transition)=="undefined")
                        transition=0;

                $("#urldiv").addClass('dn').removeClass('urldivleft').removeClass("urldivright");
                if(transition)
                        $("#urldiv").addClass("urldivleft");
                else
                        $("#urldiv").addClass("urldivright");

                $("#urldiv").removeClass("dn");
		if(ISBrowser("UC"))
			$("#urldiv").css("height",$(window).height()+80);
		if(ISBrowser("safari"))
			$("#urldiv").css("height",window.innerHeight);
		else
			$("#urldiv").css("height",$(window).height());
			
                setTimeout(function(){
		$("#urldiv").css("z-index",100000);
                        $("#urldiv").addClass("showurldiv");
                        setTimeout(function(){
                                //document.location.href=url;
                                //return;
                                SingleTonNextPage(data,nottostore,url,transition);
                        },300);
                        },100);
                    },timer);
	return false;
}
var Single=0;
var cancelUrl={};
var xhrReq={}
var timer=300;
function SingleTonNextPage(data,nottostore,url,transition)
{
   var random=Math.random();
   $.each(cancelUrl,function(key,value)
   {
       if(xhrReq.hasOwnProperty(key))
            xhrReq[key].abort();
        
       cancelUrl[key]=2;
   });
   cancelUrl[random]=1;
   
   //Before hitting AJAX call for HTML, we will check the URL with MYJS URL and 
   //check timestamp in session storage and if it is less than 1 minute, 
   //we will use HTML from session storage and not hit Ajax
   var mySiteUrl = location.protocol + "//" + location.hostname;
   var arrAllowedUrls = [mySiteUrl + "/#mham",mySiteUrl,mySiteUrl+"/?mobile_view=Y#mham",mySiteUrl+"/?mobile_view=Y",mySiteUrl+"/profile/mainmenu.php",mySiteUrl+"/profile/mainmenu.php#mham","/","//"];
   var cacheMin = 2;
   var ttl = 60000 * cacheMin;
    
   if(isSessionStorageExist() && arrAllowedUrls.indexOf(url) != -1 && 
     sessionStorage.getItem("myjsTime") != undefined && 
     new Date().getTime() - sessionStorage.getItem("myjsTime") < ttl) 
   {
      var data = sessionStorage.getItem("myjsHtml");
      
      trackJsEventGA("jsms","fetchLocalHtml", "", "");
      if(cancelUrl[random]==1) {
      	if(typeof pageMyJs != 'undefined' && pageMyJs == 1)
      		pageMyJs = 0;
        ShowNextPageTrue(data,nottostore,url,transition);  
      }
			
      startTouchEvents(timer);
   } else  {
      xhrReq[random]=$.ajax({url: url}).done(function(data){
      if(arrAllowedUrls.indexOf(url) != -1 && isSessionStorageExist()) {
      	sessionStorage.setItem("myjsTime",new Date().getTime());
        sessionStorage.setItem("myjsHtml",data);	
      }
      
      if(cancelUrl[random]==1) {
      	if(typeof pageMyJs != 'undefined' && pageMyJs == 1)
      		pageMyJs = 0;
        ShowNextPageTrue(data,nottostore,url,transition);
      }
        
      startTouchEvents(timer);

      })
      .fail(function(){
                      if(cancelUrl[random]==1)
        StopNextPage();
        startTouchEvents(timer);
      });
    }
    
}
function StopNextPage(transition)
{
	$("#urldiv").removeClass("showurldiv");
	setTimeout(function(){
		$("#urldiv").addClass('dn').removeClass("urldivright").removeClass("urldivleft");
		ShowTopDownError(Array("Something went wrong"));
		},animationtimer);
	//historyStoreObj.pop();
}

function ShowNextPageTrue(data,nottostore,url,transition)
{
       
	try{
	if(!nottostore)
		{
			var dlh=document.location.href;
			
			historyStoreObj.push(function(){ShowNextPage(dlh,1,!transition);return true;},url);
		}
		else
			if(typeof(history.pushState)!=='undefined')
				history.pushState(null,"",url);
	}catch(e)
	{}
	
	
	//actualD=actualD.replace('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>',"");
	//Fetching Head text
	
	//$("body").html('<div class="urldiv urldivright showurldiv" id="urldiv" style="height: 480px;"></div>');
	
	
	
	UpdateWholeHtml(data);
	
	
}
var data;
function UpdateWholeHtml(htmlContent)
{
	var height=$(window).height();
	document.title="Loading...";
	$("html").html("");
	$("html").children().remove();
	$("html").append(document.createElement("head"));
	$("html").append(document.createElement("body"));
	$("head").html('<style>#urldiv{width: 100%;background: white;position: fixed;top: 0px;z-index: 10;background-image: url(\'IMG_URL/images/jsms/commonImg/loader.gif\');background-repeat: no-repeat;background-position: center;}</style>');
	$("body").html('<div id="urldiv" style="height: '+height+'px"></div></body>');
	
	var filledHtml=document.createElement("html");
	filledHtml.innerHTML=htmlContent;
data=filledHtml;
//return;
	UpdateScriptsInIframe(filledHtml,htmlContent);	
	//UpdateHeadHtml(filledHtml,htmlContent);
	
	
}
var filledHtml_load;
var htmlContent_load;
function UpdateScriptsInIframe(filledHtml,htmlContent)
{
	filledHtml_load=filledHtml;
	htmlContent_load=htmlContent;
	if($("#dumpIframe").length)
		$("#dumpIframe").remove();
	try{
		var scripts=$(filledHtml).find("script[src]");
	var scripts_str="";
	$.each(scripts,function(key,value){
		var str=$(value)[0].outerHTML;
		//str=str.replace("src=","onerror='parent.geterror()' src=");
		scripts_str=scripts_str+str;
});
	var css_str="";
	var css=$(filledHtml).find("link[href]");
	$.each(css,function(key,value){
                css_str=css_str+$(value)[0].outerHTML;
});

		var iframe_html="<html><head><script>var alreadyPhotoCount=0,selectFile=0,picCount=0,getBackLink=0,firstResponse=0,_SEARCH_RESULTS_PER_PAGE=0,imageLoadComplete=0</script>"+scripts_str+css_str+"</head><body onload='parent.UpdateFrameHeadHtml()'></body></html>";
		$("body").append("<iframe id='dumpIframe' style='display:none'></iframe>");
		var doc=$("#dumpIframe")[0].contentDocument;
		doc.open();
		doc.write(iframe_html);
		doc.close();
	}
	catch(e)
	{
		UpdateHeadHtml(filledHtml,htmlContent);
	}
}
function geterror()
{
	
	try{
		}
		catch(e)
		{}
}
function UpdateFrameHeadHtml()
{

	UpdateHeadHtml(filledHtml_load,htmlContent_load);
}
var loadingUrl=0;
function UpdateHeadHtml(htmlPart,htmlContent)
{
	
	$("#dumpIframe").remove();
	document.open();
	document.write(htmlContent);
	document.close();
	return;
}

/**
* This function will ab used for no results page
*/
function addNoResDivs(noresultmessage,id,skipHeader)
{
	var len=0;
	if(skipHeader)
		len = $(skipHeader).height();
	var height = $(window).height()-len;
        var str = '<div id="noResultListingDiv" class="disptbl fullwid bg4 pad5" style="height:'+height+'px;"><div class="dispcell txtc vertmid" target="_blank"><div><img src="IMG_URL/images/jsms/commonImg/face.png"></div><div class="pt10"></div><div class="f14 fontlig">'+noresultmessage+'</div></div></div>';
	disable_touch();
	$(id).append(str);
}
function ShowTopDownError(jsonError,timeToHide)
{
        if(typeof timeToHide=="undefined")
            timeToHide = 3000;
        if($(".errClass").length)
		return;
	var tempHtml='<div class="pad12_e white f15 op1">{{VALIDATORTEXT}}</div>';
	var errArr=[];
	
	for(var i=0;i<jsonError.length;i++)
		errArr[i]=UpdateHtml(tempHtml,{"VALIDATORTEXT":jsonError[i]});
	jsonError=[];
	var correctHtml="<div class='errClass'>"+errArr.join("")+"</div>";
	$("body").prepend(correctHtml);
	if($('#privacyoptionshow').is(':visible'))
	setTimeout(function(){$(".errClass").addClass("showErr")},10);
	else
	setTimeout(function(){
            if($("#overlayHead").is(':visible'))
                $(".errClass").addClass("showErr").css("top",$("#overlayHead").outerHeight());
            else {
                $(".errClass").addClass("showErr").css("top","0px").css("position","fixed");
            }
        },10);
	setTimeout(function(){$(".errClass").removeClass("showErr");
		setTimeout(function(){$(".errClass").remove();},100);
		startTouchEvents(1);
		},timeToHide);
	
}
function CommonErrorHandling(json,toAppend)
{
	var output;
	if((typeof(json)).toLowerCase()=="string")
	{
		try
		{
			output=JSON.parse(json);
		}
		catch(e)
		{
            startTouchEvents(1);
			return false;
		}
	}
	else
		output=json;
	if(typeof(output)!='object')
	{
		ShowTopDownError(['Something went wrong']);
        startTouchEvents(1);
		return false;
	}
	try{
			if(json.responseStatusCode==0)
				return true;
			var statusCode=json.responseStatusCode;	
			if(json.responseStatusCode==9){
			if (toAppend === undefined || toAppend === null)		
						ShowNextPage("/",0);
					else ShowNextPage("/"+toAppend,0);
			}
			if(json.responseStatusCode==7)
				ShowNextPage("/register/newJsmsReg?incompleteUser=1",0);	
			if(json.responseStatusCode==8)
				ShowNextPage("/phone/jsmsDisplay",0);
			if(statusCode==1)
			{
               if(json.error.indexOf("banned") !=0 || json.error.indexOf("same phone number")!=0)
               {
               		ShowTopDownError([json.error],5000);
               }
               else
               {
               		ShowTopDownError([json.responseMessage],5000);
               }            
			}

                        if(statusCode)
				if($.inArray(parseInt(statusCode),[1,0,9,8,7])!=-1)
					throw new Error("Redirecting you");
            startTouchEvents(1);	
            return false;		
	}
	catch(e)
	{
		if(e.message=="Redirecting you")
		{
				//throw new Error("Redirecting you");
                                
		}		
		
	}
    startTouchEvents(1);
    return false;
}

function ISBrowser(type)
{
	if(type=="UC")
	{
		if(navigator.userAgent.match(/UCBrowser/i))
			return true;
	}
	if(type=="safari")
	{
		if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) 
			return true;
	}
	if(type=="AndroidNative")
	{
		if (navigator.userAgent.match(/Android/i) && navigator.userAgent.match(/Version/i))
                        return true;
	}
	return false;
	
}

function popBrowserStack()
{
    history.back();
}

/*
 * Look for Key Search Query of Location.search and return its value else false
 */
function getSearchQureyParameter(key){
  var value = false;
  if(location.search.indexOf(key)!=-1){
    value = location.search.substr(location.search.indexOf(key)).split('&')[0].split('=')[1];
  }
  return value;
}

function hostReachable() {
    var xhr = new ( window.ActiveXObject || XMLHttpRequest )( "Microsoft.XMLHTTP" );
    var status;
    xhr.open( "HEAD", "//" + window.location.hostname + "/?rand=" + Math.floor((1 + Math.random()) * 0x10000), false );
    try {
        xhr.send();
        return ( xhr.status >= 200 && (xhr.status < 300 || xhr.status === 304) );
    } catch (error) {
        return false;
    }
}

function createCookieExpireMidnight(name,value,path,specificDomain) {
	var expires = "";
	var date = new Date();
	var midnight = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59);
	expires = "; expires=" + midnight.toGMTString();
	if (!path) {
		path = "/";
	}
        if(specificDomain == undefined || specificDomain == ""){
            document.cookie = escape(name) + "=" + escape(value) + expires + "; path="+path;
        }else{
            document.cookie = escape(name) + "=" + escape(value) + expires + ";domain="+specificDomain+";path="+path;
        }
        
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}



function showTimerForLightningMemberShipPlan(source) {
    if(source == "jsmsMyjs"){
        if(getIosVersion()){
            var cT = new Date(current.replace(/\s+/g, 'T'));
            var eT = new Date(membershipPlanExpiry.replace(/\s+/g, 'T'));
        }
        else{
            var cT = new Date(current);
            var eT = new Date(membershipPlanExpiry);
        }
        lightningDealExpiryInSec = Math.floor((eT-cT)/1000);
    }
    if(!lightningDealExpiryInSec) 
        return;
    var currentTime=new Date(); 
    var expiryDate=new Date();
    expiryDate.setSeconds(expiryDate.getSeconds() + parseInt(lightningDealExpiryInSec));
    if(expiryDate<currentTime) return;
    var timeDiffInSeconds=(expiryDate-currentTime)/1000;
    if (timeDiffInSeconds>48*60*60) return;  // check for the timer if the time diff is less than 48 hrs
    var temp=timeDiffInSeconds;
    var timerSeconds=temp%60;
    temp=Math.floor(temp/60);
    var timerMinutes=temp%60;
    temp=Math.floor(temp/60);
    var timerHrs=temp;
    memTimerExtraDays=Math.floor(timerHrs/24);
    memTimerTime=new Date();
    memTimerTime.setHours(timerHrs);
    memTimerTime.setMinutes(timerMinutes);
    memTimerTime.setSeconds(timerSeconds);
    src = source;
    memTimer=setInterval('updateMemTimer()',1000);
}


function formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}


function updateMemTimer(){
  var h = memTimerTime.getHours();
  var s = memTimerTime.getSeconds();
  var m = memTimerTime.getMinutes();
  if (!m && !s && !h) {
    if(!memTimerExtraDays) clearInterval(memTimer);
    else memTimerExtraDays--;
  }
  
    memTimerTime.setSeconds(s-1);
    h=h+memTimerExtraDays*24;
    
    m = formatTime(m);
    s = formatTime(s);
    h = formatTime(h);
    
    if(src == "jsmsLanding"){
        $("#jsmsLandingM").html(m);
        $("#jsmsLandingS").html(s);
    }
    else if (src == "jsmsMyjs"){
        $("#myjsM").html(m);
        $("#mysjsS").html(s);
    }
    else if( src == "jspcLanding"){
        $("#jspcLandingM").html(m);
        $("#jspcLandingS").html(s);
    }
    else if( src == "jspcMyjs"){
        $("#jspcMyjsM").html(m);
        $("#jspcMyjsS").html(s);
    }
}

(function(){
  $(document).ready(function() {
    if(typeof trackingProfile != "undefined" && trackingProfile!=""){
        var url = window.location.hostname;
        var profile = readCookie('hinditracking');
        if((url.indexOf("hindi") != -1 )&& (profile!=trackingProfile) && (trackingProfile!="")){
            createCookieExpireMidnight("hinditracking",trackingProfile);
            trackJsEventGA('jsms', 'hindi',trackingProfile);
        }
    }
    if(navigator.userAgent.indexOf("UCBrowser") != -1) {
        setInterval(function(){
            var online = hostReachable();
            if(online) {
                var offlineData = localStorage.getItem("offline")
                // var offlineTime = localStorage.getItem("offline_timestamp");
                // var onlineTime = Math.floor(Date.now() / 1000);
                // var totalTime = (onlineTime-offlineTime);
                if(offlineData != null) {
                    ShowTopDownError(["You are now online."]);
                    localStorage.removeItem("offline");
                    // localStorage.removeItem("offline_timestamp");
      /*              setTimeout(function(){ 
                        var startTime, endTime, download = new Image();
                        var currentLocation = window.location.href;
                        download.onload = function () {
                          
                          endTime = (new Date()).getTime();
                          setTimeout(function(){
                              var duration = (endTime - startTime) / 1000;
                              var kbitsLoaded = 21.04;
                              var speedKbps = (kbitsLoaded / duration);
                              if(speedKbps < 20){
                                //track event 
                                trackJsEventGA("jsms","2Gdata", offlineData, currentLocation);
                              }
                              //alert(offlineData+currentLocation+ speedKbps);
                          }, 1000);
                        }
                        startTime = (new Date()).getTime();
                        download.src = "http://www.jeevansathi.com/images/mrevamp/logo.png?v="+new Date().getTime(); 
                    }, 2000);
	*/
                    // if(totalTime <= 1800){
                        // trackJsEventGA("jsms","offline_to_online", offlineData, totalTime);
                        // alert("logging user : "+offlineData+", totalTime : "+totalTime);
                    //}
                }
            }
            else if(!online){
                var offlineData = localStorage.getItem("offline");
                //alert("checking for offlineData : "+offlineData);
                if(offlineData == null) {
                    ShowTopDownError(["Your are offline."]);
                    localStorage.setItem("offline",trackingProfile);
                    // localStorage.setItem("offline_timestamp", Math.floor(Date.now() / 1000));
                }
            }
        }, 5000);
    } else {
      $(window).on("offline", function() {
          ShowTopDownError(["Your are offline."]);
          localStorage.setItem("offline",trackingProfile);
          // localStorage.setItem("offline_timestamp", Math.floor(Date.now() / 1000));         
      });
      $(window).on("online", function() {
          ShowTopDownError(["You are now online."]);
          var offlineData = localStorage.getItem("offline");
          // var offlineTime = localStorage.getItem("offline_timestamp");
          // var onlineTime = Math.floor(Date.now() / 1000);
          // var totalTime = (onlineTime-offlineTime);
          if(offlineData != null) {
              localStorage.removeItem("offline");
          /*    setTimeout(function(){ 
                  var startTime, endTime, download = new Image();
                  var currentLocation = window.location.href;
                  download.onload = function () {
                  
                    endTime = (new Date()).getTime();
                    setTimeout(function(){
                        var duration = (endTime - startTime) / 1000;
                        var kbitsLoaded = 21.04;
                        var speedKbps = (kbitsLoaded / duration);
                        if(speedKbps < 20){
                          //track event 
                          trackJsEventGA("jsms","2Gdata", offlineData, currentLocation); 
                        }
                        //alert(offlineData+ currentLocation+ speedKbps);
                    }, 1000);
                  }
                  startTime = (new Date()).getTime();
                  download.src = "http://www.jeevansathi.com/images/mrevamp/logo.png?v="+new Date().getTime(); 
              }, 2000); */
              // localStorage.removeItem("offline_timestamp");
              // if(totalTime <= 1800){
                  // trackJsEventGA("jsms","offline_to_online", offlineData, totalTime);
                  // alert("logging user : "+offlineData+", totalTime : "+totalTime);
              // }
          }
      });
    }
  });
})();
