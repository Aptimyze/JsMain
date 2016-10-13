$(function(){
           

	   var vwid = $( window ).width();
	   var vhgt = $( window ).height();
     var loginAttempts=0;
	   $('body').css('height',vhgt);
	   $('body').addClass("headerimg1");
	 
		    var hgt = $( window ).height();
			hgt = (hgt)+"px";
			$('#headerimg1').css( "height", hgt );
	  
           if(getAndroidVersion())
           {
               $("#appLinkAndroid").show();
           }
           if(getIosVersion())
           {
			   $("#appLinkIos").show();
		   }
           //$("#headerimg1").height($(window).height());
           $(document).ready(function(){
			RemovePresetColor();
            $(".loginLogo").attr("src","IMG_URL/images/jsms/commonImg/mainLogoNew.png");
            //src="~$IMG_URL`/images/jsms/commonImg/mainLogoNew.png" 
            setTimeout(function(){ 
                $("body").append("<div class='icons1 uicon dn'></div> <div class='mainsp baricon dn'></div>");
            }, 5000);
            $("#hamburgerIcon").on("click", function() {
                if($("#hamburger").length == 0){
                    $(".loaderSmallIcon").attr("src","IMG_URL/images/jsms/commonImg/loader.gif").removeClass("dn");
                    $("#hamIc").hide();
                    $("#perspective").append('<div id="hamburger" class="hamburgerCommon fullhgt fullwid dn"><div><div id="outerHamDiv" class="fullwid outerdiv"><div class="wid76p hamlist fl" id="mainHamDiv"><div class="clearfix fontlig padHamburger"></div><div class=" pt20  hampad1"><ul class="fontlig"><li><a href="#" onclick=translateSite("http://hindi.jeevansathi.com"); bind-slide=1 class="white" style="font-size: 19px">हिंदी में</a></li><li><a id="abc" href="/profile/mainmenu.php" bind-slide=1 class="white" style="font-size: 17px">Home</a></li><li><a href="/search/topSearchBand?isMobile=Y" bind-slide=1 class="white">Search</a></li> <li><a href="/search/searchByProfileId" bind-slide=1 class="white">Search by Profile ID</a></li><li><a href="/browse-matrimony-profiles-by-community-jeevansathi" bind-slide=1 class="white">Browse by Community</a></li><li><a href="/contactus/index" bind-slide=1 class="white">Contact Us</a></li><li><a href="/static/settings" bind-slide=1 class="white">Settings</a></li></ul></div><div class="hampad1"><ul class=" brdr9_ham fontlig"><li class="pt20"><a href="" onclick="window.location.href = \'tel:18004196299\';" title="call" alt="call" class="white">1800-419-6299 <span class="dispibl padl10 opa70 f12">Toll Free</span></a></li></ul></div><div class="hampad1" id="appDownloadLink2" style="display:none"><ul class=" brdr9_ham fontlig"><li class="pt20 white fb1 ham_opa fontrobbold">It\'s Free</li><li class=""><a onclick="window.location.href="/static/appredirect?type=jsmsHamburger";" bind-slide=1 class="white">Download  Android App </a></li></ul></div><div class="hampad1" id="appleAppDownloadLink2" style="display:none"><ul class=" brdr9_ham fontlig"><li class="pt20 white fb1 ham_opa fontrobbold">It\'s Free</li><li class=""><a onclick="window.location.href=\'/static/appredirect?type=jsmsHamburger&channel=iosLayer\';" bind-slide=1 class="white">Download iOS App </a></li></ul></div></div><div class="posfix ham_pos1 fullwid js-loginBtn"><div class="pad1"><div class="ham_bdr1"><div id="loggedOutHamFoot" class="pt10 fontlig f17"><div class="fl wid49p txtc ham_bdr2"><a bind-slide=1 href="/static/LogoutPage" class="white lh30">Login</a></div><div class="fl wid49p txtc"><a bind-slide=1 href="/register/page1?source=mobreg5" class="white lh30">Register</a></div></div></div></div></div></div></div></div>');
                    $("#hamburgerIcon").off("click");
                    var imported = document.createElement('script');
                    imported.src = '/js/jsms/hamburger/ham_js.js';
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
                            data:{email:email,password:escape(pass),newMob:1,rememberme:1,captcha:captchaShow},
                            success: function(result){
								var redirectUrl="";
								if((typeof result) != "object")
                                    result=JSON.parse(result);
								if(document.cookie.indexOf("loginAttempt")!=-1 && result.responseStatusCode!=0 && result.responseStatusCode!=8)
								{
									if(!is_android){
										removeCaptcha();
										createCaptcha();
									
									  if(captchaShow!=1)
									  {
										
										captchaShow=1;
										ShowTopDownError(["Please slide to verify"]);
										return 0;
									  }
								  }
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
									setTimeout(function(){startTouchEvents(10);ShowNextPage(redirectUrl,0);},animationtimer);
									return;
								}
								if(errorMes)
								{
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
	if($("#blueText").html()=="Slide to Verify" &&  $('#captchaDiv').is(':visible'))
    {
      ShowTopDownError(["Please slide to verify"]);
      return false;
    }
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
		
		if(!is_android){
			if(captchaShow==1)
				createCaptcha();
		}
    }

});


function createCaptcha(){
	
	var captchaDiv='<div id="captchaOuterDiv" class="fullwid brdr10">                <div id="captchaDiv" class="captcha" style="">    <div class="blueTxt" id="blueText">Slide to Verify</div>                      	<div class="fullwid transLayer"></div>                    <div class="slideCap" id="slideCap">                                  </div>                    <div class="handle" style=""></div>                </div>            </div>';
	
	$('#afterCaptcha').before(captchaDiv);
	 $('.captcha').slideToCAPTCHA();
	
	
}
function removeCaptcha()
{
	$("#captchaOuterDiv").remove();
}


(function($) {
    $.fn.slideToCAPTCHA = function(options) {
        options = $.extend({
            handle: '.handle',
            cursor: 'move',
            direction: 'x', //x or y
            customValidation: false,
            completedText: 'Done!'
        }, options);
        
        var $handle = this.find(options.handle),
            $slide = this,
            handleOWidth,
            xPos,
            yPos,
            slideXPos,
            slideWidth,
            slideOWidth,
            $activeHandle,
      slipStart,
            mousePressed = false,
            sliderCompleted = false,
            $formEl = $slide.parents('form');
        startSlider();
       
         $handle.css('cursor', options.cursor)
            .on('touchstart', function(e){ slideOn(e); });

        function startSlider() {
         
            if (options.customValidation === false) {
                $formEl.attr('onsubmit', "return $(this).attr('data-valid') === 'true';");
            }
            $slide.addClass('slide-to-captcha');
            $handle.addClass('slide-to-captcha-handle');
            handleOWidth = $handle.outerWidth();
            slideWidth = $slide.width();
            slideOWidth = $slide.outerWidth();
        }
        function slideOn(e) {
            mousePressed = true;
            $activeHandle = $handle.addClass('active-handle');
             xPos = $handle.offset().left + handleOWidth - (Math.round(e.originalEvent.touches[0].pageX));
            slideXPos = $slide.offset().left + ((slideOWidth - slideWidth) / 2);
      slipStart = $handle.offset().left;
             $activeHandle.on('touchmove', function(e){ slideMove(e); })
                .on('touchend', function(e){ slideOff(); });
            e.preventDefault();
        }
        function slideMove(e) {
             var MovepageX = Math.round(e.originalEvent.touches[0].pageX);
            var handleXPos = MovepageX + xPos - handleOWidth;
      var width = $handle.offset().left - slipStart;
            if (handleXPos > slideXPos && handleXPos < slideXPos + slideWidth - handleOWidth) {
                if ($handle.hasClass('active-handle')) {
          $('.active-handle').offset({
                        left: handleXPos
                    });
          $("#slideCap").css("width",width);
         
                }
            } else {
                if (handleXPos <= slideXPos === false) {
                    sliderComplete();
                }
                $activeHandle.mouseup();
            }
        }
        function sliderComplete() {
            sliderCompleted = true;
            $("#blueText").addClass("slided");
      $(".handle").removeClass("slide-to-captcha-handle").addClass("slide-to-captcha-handle-verified");
     
            $activeHandle.offset({
                left: slideXPos + slideWidth - handleOWidth
            });
            $activeHandle.off();
            slideOff();
           
            $slide.addClass('valid');
      $('#blueText').html('Verified');
      $("#slideCap").css("width","100%");
      
            //$('.slide-to-captcha').attr('data-content', options.completedText);
        }
        function slideOff() {
            mousePressed = false;
            if (sliderCompleted == false) {
              $("#blueText").removeClass("slided");
                $activeHandle.offset({
                    left: slideXPos+1
                });
      
                $activeHandle.removeClass('active-handle');
        $("#slideCap").css("width","0");
            }
        }
    }
})(jQuery);


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
