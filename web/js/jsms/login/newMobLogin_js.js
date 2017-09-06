
function getIosVersionOne(ua) {
    //return false;
    var ua = ua || navigator.userAgent;
    var match= ua.match(/(iPhone);/i);
    //console.log(match);
    var OsVersion=ua.match(/OS\s[0-9.]*/i);
    //console.log(OsVersion);    
    if(match==null)
        return false;
    else if(OsVersion==null)
    {
        return false
    }
    else if(OsVersion[0].substring(3,5) >= 7)
        return true;
    else
        return false;
    
}


function getAndroidVersionOne(ua) {
  var ua = ua || navigator.userAgent;
  var android=ua.indexOf("Android");
  var match = ua.match(/Android\s([0-9\.]*)/);
  var mobile=ua.indexOf("Mobile");
  var operaMini=ua.indexOf("Opera Mini");
  if(android==-1)
   return false;
 else if(operaMini!=-1){
   OperaMiniFlag=1;
   return true;
 }
 else if(match==null)
 {
   return true;
 }
 else if(typeof(parseFloat(match[1]))=='number')
 {
   var androidVersion=match[1].substring(0,3);
   if(androidVersion>2.3)
    return true;
  else if(androidVersion==2.3 && match[1].charAt(4)>0)
    return true;
  else
    return false;
}
else
 return true;
        
  };

$(function(){
           

	   var vwid = $( window ).width();
	   var vhgt = $( window ).height();
     var loginAttempts=0;
	   $('body').css('height',vhgt);
	   $('body').addClass("headerimg1");
	 
		    var hgt = $( window ).height();
			hgt = (hgt)+"px";
			$('#headerimg1').css( "height", hgt );
	  
           if(getAndroidVersionOne())
           {
               $("#appLinkAndroid").show();
           }
           if(getIosVersionOne())
           {
			   $("#appLinkIos").show();
		   }
           //$("#headerimg1").height($(window).height());
           $(document).ready(function(){
            logSiteUrl();
			RemovePresetColor();
            $(".loginLogo").attr("src","IMG_URL/images/jsms/commonImg/mainLogoNew.png");
            //src="~$IMG_URL`/images/jsms/commonImg/mainLogoNew.png" 
            setTimeout(function(){ 
                $("#mainContent").append("<div class='icons1 uicon dn'></div> <div class='mainsp baricon dn'></div>");
                loadCSS("IMG_URL/min/?f="+logoutCssFiles);
                loadCSS("https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700");
            }, 0);
            $("#hamburgerIcon").bind("click", function() {
                if($("#hamburger").length == 0){
                    $(".loaderSmallIcon").addClass("loaderimg").removeClass("dn");
                    $("#hamIc").hide();
                    loadCSS('IMG_URL/min/?f=/'+hamCss);
                  //  $("#hamburgerIcon").off("click");
                    $("#perspective").append('    <div id="hamburger" class="white posfix wid90p fullheight"><div id="outerHamDiv"><div id="mainHamDiv" ><div id="newHamlist" class="hamlist hampad1" ><ul id="scrollElem" style="-webkit-overflow-scrolling: touch;margin-left:0px" class="fontreg white listingHam overAutoHidden"><li class="f13 pb8 fontlig"><div id="appDownloadLink1" class="wid49p dispnone"><a bind-slide=1   href="/static/appredirect?type=jsmsHamburger" target="_blank" class="white fl mar0Imp">Download  App | 3MB only </a></div><div class="wid49p dispnone" id="appleAppDownloadLink1"><a bind-slide=1   href="/static/appredirect?type=jsmsHamburger&channel=iosLayer" target="_blank" class="white fl mar0Imp">Download iOS App </a></div><div class="wid49p dispibl"><div id="hindiLink" onclick="translateSite(\'http://js.mox.net.in\');" class="white fr mar0Imp">हिंदी में</div></div></li><div style="height: 1px;padding: 0px 20px;"><div style="background-color: white;height: 1px;opacity: .5;"></div></div><li class="mb12"><i class="hamSprite homeIcon mt10Imp"></i><a id="homeLink1" class="f17 white" href="/">Home</a></li><li class="mb12"><i class="hamSprite searchIcon"></i><a id="searchLink" class="white" href="/search/topSearchBand?isMobile=Y">Search</a></li><li class="mb12"><i class="hamSprite searchProfileIcon"></i><a id="searchProfileIdLink" href="/search/searchByProfileId" class="white">Search by Profile ID</a></li><li class="mb12"><i class="hamSprite editProfileIcon"></i><a href="/browse-matrimony-profiles-by-community-jeevansathi" id="borwseCommLink" class="f17 white">Browse By Community</a></li><li class="mb12"><div id="settingsParent"><i class="hamSprite settingsIcon"></i><div id="settingsLink" class="ml25 dispibl white">Settings & Assistance</div><i id="expandSettings" class="hamSprite plusIcon fr"></i></div><ul id="settingsMinor" class="dispnone minorList f15" style="margin-top: 12px;padding-left:40px"><li class="mb12 "><a id="contactUsLink" href="/contactus/index" class="newS white">Contact Us</a></li><li class="mb12"><a id="privacyPolicyLink" href="/static/page/privacypolicy" class="newS white">Privacy Policy</a></li><li class="mb12"><a id="termsLink" href="/static/page/disclaimer" class="newS white">Terms of use</a></li><li class="mb12"><a id="fraudLink" href="/static/page/fraudalert" class="newS white">Fraud Alert</a></li><li class="mb12"><a id="switchLink" href="/?desktop=Y" class="newS white">Switch to Desktop Site</a></li></ul></li></ul></div></div></div></div><div id="hamView" class="fullwid darkView fullheight hamView dn"></div>');
                    var imported = document.createElement('script');
                    imported.src = 'IMG_URL/min/?f=/'+hamJs;
                    imported.onerror = function() {
                       ShowTopDownError(['Something went wrong']); 
                       $("#hamburger").remove(); 
                       setTimeout(function(){
                           $(".loaderSmallIcon").addClass("dn");
                           $("#hamIc").show();
                       }, 100);   
                   };
                    imported.onload = function() {
                        BindNextPage();
                        $("#hamburgerIcon").click();
                        setTimeout(function(){
                            $(".loaderSmallIcon").addClass("dn").remove();
                            $("#hamIc").show();
                        }, 100);    
                    };
                    document.head.appendChild(imported);
                }
            });
		});
$("#loginButton").bind("touchstart",function(){
  /* GA tracking */
  GAMapper("GA_HOME_LOGIN_BTN");
	$(window).scrollTop(0);
        var email=$("#email").val();
        var pass=$("#password").val();
        var pUrl=$("#prev_url").val();
        $("input").blur();
        var errorMes="";
            if(email && pass)
            {
				
                if(validateEmail(email) && validateCaptcha())
                {       
                        stopTouchEvents(1);
                        setTimeout(function(){
                            stopTouchEvents(1,1,1);
                            $.ajax({
                            url:"/api/v1/api/login",
                            type: "POST",
                            datatype:'json',
                            cache: true,
                            async:true,
                            data:{email:email,password:escape(pass),newMob:1,rememberme:1,captcha:captchaShow,g_recaptcha_response:$("#g-recaptcha-response").val()},
                            success: function(result){
								var redirectUrl="";
								if((typeof result) != "object")
                                    result=JSON.parse(result);
								if(document.cookie.indexOf("loginAttempt")!=-1 && result.responseStatusCode!=0 && result.responseStatusCode!=8)
								{
//									if(!is_android){
									
										// removeCaptcha();
										createCaptcha();
									
									  if(captchaShow!=1)
									  {
										
										captchaShow=1;
                                        errorMes=result.responseMessage;
									  }
//								  }
								}

                                
                               
                                if(result.responseStatusCode==0)
                                {
                                    if(result.INCOMPLETE=='Y')
                                    {
                                            redirectUrl="/register/newJsmsReg?incompleteUser=1";
                                    }
                                    else 
                                    {
                                            if(typeof(historyStoreObj)!="undefined" && historyStoreObj.History.length)
                                            {
                                                    setTimeout(function(){startTouchEvents(10);historyStoreObj.pop()},animationtimer);
                                                    return;
                                            }
                                            if(pUrl)
                                            {

                                                    redirectUrl=pUrl;
                                            }	    
                                            else
                                                    redirectUrl="/index.php";
                                    }
                                }
                                else if(result.responseStatusCode==8)
                                {
                                    redirectUrl="/phone/jsmsDisplay";
                                  
                                }
                                else
                                    errorMes=result.responseMessage;
                                if(redirectUrl)
								{
                  GAMapper("GA_LOGIN_REPONSE_SUCCESS");
									setTimeout(function(){startTouchEvents(10);ShowNextPage(redirectUrl,0);},animationtimer);
									return;
								}
								if(errorMes)
								{
                  GAMapper("GA_LOGIN_REPONSE_FAIL");
									setTimeout(function(){startTouchEvents(10);ShowTopDownError([errorMes]);},animationtimer);
									return;
								}
                            },
                            error: function(statusCode,errorThrown) {
                                if(statusCode.status==0)
                                {
                                    startTouchEvents();
                                    ShowTopDownError(["No Internet Connection"]);
                                }
                            }
                        });
                    },animationtimer);
                        return;
                }
                else
                {    
					if(validateCaptcha()){
						errorMes="Provide a valid Email ID";
						$("#emailErr1").show();
						setTimeout(function(){$("#emailErr1").hide();},3000);
					}
                }   
            }
            else
            {
                if(email=="" && pass=="")
                    errorMes="Provide your login details"; 
                else if(email=="")
                {
                    errorMes="Provide your Email ID";
                    $("#emailErr1").show();
                    setTimeout(function(){$("#emailErr1").hide();},3000);
                }
                else if(pass=="")
                {
                    if(!validateEmail(email))
                    {
                        errorMes="Provide a valid Email ID";
                        $("#emailErr1").show();
                        setTimeout(function(){$("#emailErr1").hide();},3000);
                    }
                    else
                        errorMes="Provide your password";
                }
            }
            startTouchEvents();
            ShowTopDownError([errorMes]);
            
    });
function validateEmail(email) {
    var x = email;
    var re = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    return re.test(email);
    }

function validateCaptcha()
{
	// if($("#blueText").html()=="Slide to Verify" &&  $('#captchaDiv').is(':visible'))
 //    {
 //      ShowTopDownError(["Please slide to verify"]);
 //      return false;
 //    }
    return true;
}
});
$(window).load(function()
{
	var f=0;
	var farray={0:"text",1:"password"};
	var sarray={0:"Hide",1:"Show"};
        setTimeout(function(){
            if($("#password").val().length >0)
				$("#showHide").show();
        },200);
	$("#password").bind("paste keyup change",function(event){
             		if($("#password").val().length >0 || event.type=="paste")
				$("#showHide").show();
			else
				$("#showHide").hide();
	});
	
	$("#showHide").bind("touchstart",function(){
			$("#password").attr("type",farray[f]);
			$("#showHide").html(sarray[f]);
			f=f?0:1;
                        $("#password").focus();
	});
	
	 if(typeof(captchaShow)!="undefined")
    {
			if(captchaShow==1)
				createCaptcha();
    }

});


function createCaptcha(){
        var captchaDiv = '<div class="captchaDiv pad3"><img class="loaderSmallIcon2" src="https://static.jeevansathi.com/images/jsms/commonImg/loader.gif"><script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha dn" data-sitekey='+site_key+'></div></div>';
        if($(".g-recaptcha").length !=0){
            removeCaptcha();
        }
        $("#afterCaptcha").before(captchaDiv).promise().done(function() {
            setTimeout(function() {
                $(".loaderSmallIcon2").remove();
                $(".g-recaptcha").removeClass("dn");
            }, 1000);               
      });
	
}
function removeCaptcha()
{
	$(".captchaDiv").remove();
}



function RemovePresetColor()
{
if(navigator.userAgent.toLowerCase().indexOf("chrome") >= 0 || navigator.userAgent.toLowerCase().indexOf("safari") >= 0){
                setTimeout(function(){
                    $('#email,#password').each(function()
                        { var clone = $(this).clone(true, true); $(this).after(clone).remove(); }
                    );
                }, 500);
            }
}

function loadCSS(href) {
     var cssLink = $("<link>");
     $("head").append(cssLink);
     cssLink.attr({
       rel:  "stylesheet",
       type: "text/css",
       href: href
     });
}

function convertIntoHomePage(translateURL){
  newHref = translateURL+"?AUTHCHECKSUM="+readCookieHomePage("AUTHCHECKSUM");
  if(translateURL.indexOf('hindi')!=-1){
    createCookieHomePage("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
  } else {
    createCookieHomePage("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
  }
  window.location.href = newHref;
}

function readCookieHomePage(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function createCookieHomePage(name, value, days,specificDomain) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    if(specificDomain == undefined || specificDomain == ""){
      document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }
    else{
      document.cookie = escape(name) + "=" + escape(value) + expires + ";domain="+specificDomain+";path=/";
    }
}

function logSiteUrl()
{
  var url = location.href;
  if(url.indexOf("jeevansathi") == -1)
  {
    var dataObject = JSON.stringify({'url' : encodeURIComponent(url)});
    $.ajax({
      url : '/api/v1/common/logOtherUrl',
      dataType: 'json',
      data: 'data='+dataObject,
      success: function(response) {}
    });
  }
}
