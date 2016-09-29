var loginAttempts=0;
function LoginValidation()
{
	var email=$.trim($("#email").val());
		var password=$("#password").val();
   
		if($("#remember").is(':checked'))
			remember=1;
		else
			remember=0;
		if(email && password)
		{				
			if(validateEmail(email) && validateCaptcha())
			{
				loginUrl=SSL_SITE_URL+"/api/v1/api/login?&captcha="+captchaShow+"&fromPc=1&rememberme="+$("#remember").val();
				$("#homePageLogin").attr('action',loginUrl);
				if(typeof(LoggedoutPage)!="undefined")
				{ 	
					if(LoggedoutPage){
						showCommonLoader();
					}
					else
						showCommonLoader();
				}
				else
					showCommonLoader();
				
				return true;
			}
			else
			{   
        
				if(validateCaptcha()){
					$("#emailErr").addClass("visb").html("Invalid Format");
					$("#EmailContainer").addClass("brderred");
					setTimeout(function(){
						$("#emailErr").removeClass("visb");
						$("#EmailContainer").removeClass("brderred");
					},3000);
				}
				return false; 
			}
      
		}
		else
		{
			if(email=="" && password==""){
				//errorMes="Provide your login details"; 
				$("#emailErr").addClass("visb").html("Required");	
				$("#passwordErr").addClass("visb").html("Required");	
				$("#EmailContainer").addClass("brderred");
				$("#PasswordContainer").addClass("brderred");
				setTimeout(function(){
					$("#emailErr").removeClass("visb");
					$("#EmailContainer").removeClass("brderred");
					$("#passwordErr").removeClass("visb");
					$("#PasswordContainer").removeClass("brderred");
					},3000);
			}
			else if(email=="")
			{
				$("#emailErr").addClass("visb").html("Required");
				$("#EmailContainer").addClass("brderred");
				setTimeout(function(){
					$("#emailErr").removeClass("visb");
					$("#EmailContainer").removeClass("brderred");
				},3000);
				
			}
			else if(password=="")
			{
				if(!validateEmail(email))
				{
					$("#emailErr").addClass("visb").html("Invalid Format");
					$("#EmailContainer").addClass("brderred");
					setTimeout(function(){
						$("#emailErr").removeClass("visb");
						$("#EmailContainer").removeClass("brderred");
					},3000);
				}					
				$("#passwordErr").addClass("visb").html("Required");
				$("#PasswordContainer").addClass("brderred");
				setTimeout(function(){
					$("#passwordErr").removeClass("visb");
					$("#PasswordContainer").removeClass("brderred");
				},3000);
			}
		return false;
		}
}
function validateEmail(email) {
    var x = $.trim(email);
    var re = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    return re.test(x);
    }
    
function validateMobile(mobile) {
	var str = $.trim(mobile);
	// removes leading zeros
	str = str.replace(/^0+/, '');
	if(str.indexOf('-') > -1)
	{
		var result = str.split("-");
		// remove leading zeros from number
		result[1] = result[1].replace(/^0+/, '');
		str = result.join("-");
	}

	if(str.indexOf('+') > -1)
	{
		var result = str.split("+");
		// remove leading zeros from isd
		result[1] = result[1].replace(/^0+/, '');
		str = result.join("+");
	}
	var re = /^((\+)?[0-9]*(-)?)?[0-9]{10,}$/i;
	var isd = '';
	var phone = '';
	var data = new Array();
	if(re.test(str))
	{
		str = str.split('+').join('');
		if(str.indexOf('-') > -1)
		{
			isd = str.slice(0, str.indexOf('-'));
			phone = str.slice(str.indexOf('-')+1, str.length);
		}
		else
		{
			phone = str.slice(-10);
			if(str.length == 10)
			{
				isd = '91';
			}
			else
			{
				isd = str.slice(0, str.length - 10);
			}
		}
		data['flag'] = 1;
		data['phone'] = phone;
		data['isd'] = isd;
	}
	else
	{
		data['flag'] = 0;
	}
	return data;
}
function validateCaptcha()
{
	 if($("#blueText").html()=="Slide to Verify" &&  $('#captchaDiv').is(':visible'))
    {
      $("#LoginMessage").hide();
      $("#LoginErrMessage").addClass("disp-none");
      $("#LoginErrMessage2").removeClass("disp-none");
      return false;
    }
    return true;
}

function after_login(response)
	{
		var address_url=window.location.href;
		if(window.location.href.indexOf("redirectUri=")>0)
			address_url=window.location.href.substr(window.location.href.indexOf("redirectUri=")+12);
		if(response==0)
		if( window.top.location.pathname=="/static/logoutPage" || window.top.location.pathname=="/jsmb/login_home.php")
			window.top.location.href = "/myjs/jspcPerform";
		else
			window.top.location.href = address_url;
	}

function onFrameLoginResponseReceived(message)
{
	if(message.origin === SSL_SITE_URL)
	{		
		var response="";
			if(!window.addEventListener)
				response=message.data;
			else
				response= message.data.body;
		if(response==1)
		{
		  if(document.cookie.indexOf("loginAttemptNew")!=-1 && captchaShow!=1)
		  {
			  createCaptcha("logoutPage");
				captchaShow=1;
				hideCommonLoader();
				$("#LoginMessage").addClass("disp-none");
				$("#LoginErrMessage").addClass("disp-none");
			  $("#LoginErrMessage2").removeClass("disp-none");
			  hideCommonLoader();
				removeCaptcha();
				if($("#commonOverlay").is(':visible')){
				 createCaptcha();
			   }
				else
				{ 
				  createCaptcha("logoutPage");
				}
		  }
      else{
  		/*	hideCommonLoader();
        removeCaptcha();
        if($("#commonOverlay").is(':visible')){
          console.log("aaa");
         createCaptcha();
        }
        else
        { 
          console.log("bbb");
          createCaptcha("logoutPage");
        }*/
        hideCommonLoader();
  			$("#LoginErrMessage").removeClass("disp-none");
  			$("#LoginMessage").addClass("disp-none");
  			$("#LoginErrMessage2").addClass("disp-none");
  			$("#EmailContainer").addClass("brderred");
  			$("#PasswordContainer").addClass("brderred");
  			setTimeout(function(){
  				$("#emailErr").removeClass("visb");
  				$("#EmailContainer").removeClass("brderred");
  				$("#passwordErr").removeClass("visb");
  				$("#PasswordContainer").removeClass("brderred");
  				},3000);
      }
		}
		else
		{
			after_login(response);
		}		
	}
	
		
}

function resetCaptcha(){
	/*resetCaptchaClass();
          $(".slideCap").width("0px");
          $("#captchaDiv").removeClass("valid");
          $(".handle").removeClass("slide-to-captcha-handle-verified").addClass("slide-to-captcha-handle").css("left","0px");
           //$('#blueText').html('Slide to Verify');
           $('#blueText').hide();
           $("#blueText").html("Slide to Verify");          
          $('.captcha').slideToCAPTCHA('captcha');*/
}
if(window.addEventListener)	
	{
		window.addEventListener("message", onFrameLoginResponseReceived, false);
	}
	else if ( window.attachEvent ) //For IE 8
	{
		window.attachEvent( "onmessage", onFrameLoginResponseReceived );
	}else if( window.onLoad)
	{
		window.onload = onFrameLoginResponseReceived;
	}

if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);


    $(document).ready(function(){ 
	
		LoginBinding();
		setTimeout(function(){
	
			 if(typeof(loggedInJspcUser)!="undefined")
					{
						if(loggedInJspcUser=="")
							LoginBinding();
					}
			},2000);
	
	});

function LoginBinding()
{
	$('#loginTopNavBar, .loginLayerJspc , .loginLayerOnShareClick, .loginLayerOnReqHoroClick,#mainServLoginBtn, #jsxServLoginBtn').unbind();
	$('#loginTopNavBar, .loginLayerJspc , .loginLayerOnShareClick, .loginLayerOnReqHoroClick,#mainServLoginBtn, #jsxServLoginBtn').click(function() {
        $.ajax({
            type: "POST",
            url: '/static/newLoginLayer',
            context:this,
            //data:{'captchaShow':captchaShow},
            beforeSend: function() {
                $('#commonOverlay').fadeIn(200, "linear");
                $('#topNavigationBar').removeClass("z2");
            },
            success: function(response) {
                $('#commonOverlay').after(response);
                $('#login-layer').fadeIn(300, "linear");
                if($(this).hasClass("loginAlbumSearch")){
					$("#loginRegistration").addClass("loginAlbumSearch");
					$("#LoginMessage").addClass('txtc').text("Login For the benefit of the privacy of all members, we require you to kindly Login or Register to view the photos");
				}
				else if($(this).hasClass("loginProfileSearch")){
					$("#loginRegistration").addClass("loginProfileSearch");
					$("#LoginMessage").addClass('txtc').text("For the benefit of the privacy of all members, we require you to kindly Login or Register to view the profile");
				}
                $('#cls-login').click(function() {
                  //alert("scc");
                    $('#login-layer').fadeOut(200, "linear", function() {
                        $('.js-overlay').fadeOut(300, "linear");
                         $('#login-layer').remove();
                         $('#forgotPasswordLayer').remove();
                       // $('.captcha2').slideToCAPTCHA('captcha')
                       if(typeof(LoggedoutPage)!="undefined")
                      {  
                        if(LoggedoutPage){
                        removeCaptcha();
                        createCaptcha("logoutPage");
                        }
                      }
        
                    });
                    
                    commonLoginBinding();
                 
                });
                 $('.js-overlay').bind("click",function(){
					 $('#login-layer').fadeOut(200, "linear", function() {
						$('.js-overlay').fadeOut(300, "linear");
                         $('#login-layer').remove();
                         $('#forgotPasswordLayer').remove();
                          if(typeof(LoggedoutPage)!="undefined")
                      {  
                        if(LoggedoutPage){
                        removeCaptcha();
                        createCaptcha("logoutPage");
                        }
                      }
					});
                 $(this).unbind("click");
                  
			  
             });
                //start remember me script
               commonLoginBinding();
               forgotPasswordBinding(1);
               customCheckboxLogin("remember",0);
               if(captchaShow==1)
      				{
      				   createCaptcha();
      				}
            },
            error: function(response) {
                $('.js-overlay').fadeOut(300, "linear");
            }
        });
		});
}

function commonLoginBinding()
{
	//start remember me script
                $(".remN").click(function() {
                    $("#selopt").animate({
                        left: '25px'
                    });
                    $("#remember").val("0");
                });
                $(".remY").click(function() {
                    $("#selopt").animate({
                        left: '2px'
                    });
                    $("#remember").val("1");
                });
                $("#loginRegistration").click(function() {
					if($(this).hasClass("logout"))
						location.href="/register/page1?source=login_p";
					else if($(this).hasClass("loginAlbumSearch"))
						location.href="/register/page1?source=album_l";
					else if($(this).hasClass("loginProfileSearch"))
						location.href="/register/page1?source=profile_l";
					else
						location.href="/register/page1?source=login_l";
                });
                $("#cls-login").on('click',function(){
                    $('#topNavigationBar').addClass("z2");
                });
}
function customCheckboxLogin(checkboxName,flag) {
    var checkBox = $('input[name="' + checkboxName + '"]');
    $(checkBox).each(function() {
		if($(this).closest('span').attr('id')!="remeberLogin")
        {
			$(this).wrap("<span id=\"remeberLogin\" class='custom-checkbox-login'></span>");
			if ($(this).is(':checked')) {
				$(this).parent().addClass("selected");
			}
		}
    });
    $("#remeberLogin").click(function() {
		if($("#remember").val()=="1")
			$("#remember").val("0");
		else
			$("#remember").val("1");		
        $(this).toggleClass("selected");
    });
}

$(document).ready(function(){
	commonLoginBinding();
	if(typeof(LoggedoutPage)!="undefined")
	{ 	
		if(LoggedoutPage){
			customCheckboxLogin("remember",1);
			if(fromSignout)
				$("#LoginMessage").html("You have successfully logged out");
			forgotPasswordBinding(0);
		}
	}
	if(typeof(ResetPasswordPage)!="undefined")
	{
		if(ResetPasswordPage)
			postForgotEmailLayer(3);
	}
  
    if(typeof(captchaShow)!="undefined")
    {
		if(captchaShow==1){
			if(typeof(LoggedoutPage)!="undefined")
			{ 	
				if(LoggedoutPage){
					createCaptcha('loggedout');
				}
			}
		}
    }


		
});

function forgotPasswordBinding(fromLayer)
{
	
	$('#forgotPasswordLoginLayer').click(function() {
		
		$("#ForgotPasswordMessage").html("Enter your registered email of Jeevansathi to receive an Email and SMS with the link to reset your password.");
		$("#forgotPasswordForm").removeClass("disp-none");
		
		$('#closeForgotLogin').unbind();
		forgotBindings(fromLayer);
	});
}
function forgotBindings(fromLayer)
{
		//open layer bindings
		if(fromLayer==1)
		{
                    $('#login-layer').fadeOut(200, "linear", function() {
                         $('#login-layer').remove();
                         $('#forgotPasswordLayer').removeClass("disp-none");
                    });
                    $('.js-overlay').bind("click",function(){
					 $('#forgotPasswordLayer').fadeOut(200, "linear", function() {
						$('.js-overlay').fadeOut(300, "linear");
						$('#forgotPasswordLayer').remove();
					});
                 $(this).unbind("click");
             });
		}
		else
		{
			 $('.js-overlay').fadeIn(200, "linear");
			 $('#forgotPasswordLayer').removeClass("disp-none").attr("style","block");
			 $('.js-overlay').bind("click",function(){
					 $('#forgotPasswordLayer').fadeOut(200, "linear", function() {
						$('.js-overlay').fadeOut(300, "linear");
					});
                 $(this).unbind("click");
             });
		}
		
		//close layer bindings
		if(fromLayer==1)
		{
			$('#closeForgotLogin').click(function() {
				$('#forgotPasswordLayer').fadeOut(200, "linear", function() {
					$('.js-overlay').fadeOut(300, "linear");
					$('#forgotPasswordLayer').remove();
				});
				$('.js-overlay').unbind();
			});
		}
		else
		{
			$('#closeForgotLogin').click(function() {
				$('#forgotPasswordLayer').fadeOut(200, "linear", function() {
					$('.js-overlay').fadeOut(300, "linear");
					$('#forgotPasswordLayer').addClass("disp-none");
				});
				$('.js-overlay').unbind();
			});
		}
		postForgotEmailLayer();

}

function postForgotEmailLayer()
{
	$("#userEmail").focus();
		$("#userEmail").bind("keydown",function(e){
		   if(e.keyCode == 13)
		   {    e.preventDefault();
			   $("#sendLink").click();
		   }
		});
		$("#sendLinkForgot").click(function(){
			$("#sendLinkForgot").unbind();
			var email=$("#userEmail").val();
			if(email)
			{
				var flag = validateEmail(email)?'E':false;
				var phone = null;
				var isd = null;
				if(!flag)
				{
					var data = validateMobile(email);
					flag = data['flag']?'M':false;
					phone = data['phone'];
					isd = data['isd'];
				}
				if(flag)
				{       
					showCommonLoader("#forgotPasswordContainer");
					 $.ajax({
						 url:"/api/v1/api/forgotlogin",
						 type: "POST",
						 datatype:'json',
						 cache: true,
						 async:false,
						 data:{'email':email.trim(), 'flag':flag, 'phone':phone, 'isd':isd},
						 success: function(result){
							 if(result.responseStatusCode==0)
							 {
								 $("#ForgotPasswordMessage").html("Link to reset your password has been sent to your registered Email Id and Mobile Number. The link will be valid for next 24 hours.");
								 $("#forgotPasswordForm").addClass("disp-none");
							 }
							 else
							 {
								 $("#forgotPasswordErr").html(result.responseMessage).addClass("visb");
								 $("#userEmailBox").addClass("brderred");
								 setTimeout(function(){
									 $("#forgotPasswordErr").removeClass("visb");
									$("#userEmailBox").removeClass("brderred");
								},3000);
							 }
							 hideCommonLoader();
							  return;
						 }
					});
				}
				else{
					$("#forgotPasswordErr").html("Provide a valid email address or phone number").addClass("visb");
					$("#userEmailBox").addClass("brderred");
					 setTimeout(function(){
						 $("#forgotPasswordErr").removeClass("visb");
						$("#userEmailBox").removeClass("brderred");
					},3000);
				}
			}
			else
			{
				$("#forgotPasswordErr").html("Provide your email address or phone number").addClass("visb");
				$("#userEmailBox").addClass("brderred");
				 setTimeout(function(){
					 $("#forgotPasswordErr").removeClass("visb");
					$("#userEmailBox").removeClass("brderred");
				},3000);
			}
		});
}

function createCaptcha(fromLoggedOut){
	
	var captchaDiv='<div id="captchaDiv" class="captcha" style=" width: 434px;">                                    <div class="slideCap" id="slideCap">                                        <div class="blueTxt" id="blueText">Slide to Verify</div>                                    </div>                                    <div id="textSlide" style="color: #888;z-index:9999; text-align:center; padding-top: 18px;">Slide to Verify</div>                                    <div class="handle" style="background-position: 10px 10px;background-image:url(/images/jsms/commonImg/nextIcon.png);background-repeat: no-repeat;"></div>                                </div>';
	if(fromLoggedOut)
	{
		if(typeof(parent.LoggedoutPage)!==undefined)
		{
			 parent.$('#afterCaptcha').before(captchaDiv);
			  parent.$("#loggedout").find('.captcha').slideToCAPTCHA('captcha');
		}
		else
		{
		   $('#afterCaptcha').before(captchaDiv);
		  $("#loggedout").find('.captcha').slideToCAPTCHA('captcha');
		}
	}
	else
	{
		$('#afterCaptcha').before(captchaDiv);
		$("#newLoginLayerJspc").find('.captcha').slideToCAPTCHA('captcha');
	}
	
	
}

function removeCaptcha()
{
  $('.captcha').each(function(index, element) {
      $(element).remove();});
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
            sliderCompleted = false;
			startSlider();
			$handle.css('cursor', options.cursor).on('mousedown', function(e) {
                slideOn(e);
            }).on('mouseup', function(e) {
				resetSlider();
            }).on('mouseleave', function(e) {
				if(mousePressed == true) {
					resetSlider();  
				}
            });
        function startSlider() {
            $slide.addClass('slide-to-captcha');
            $handle.addClass('slide-to-captcha-handle');
            handleOWidth = $handle.outerWidth();
            slideWidth = $slide.width();
            slideOWidth = $slide.outerWidth();
        }
        function slideOn(e) {
            mousePressed = true;
            $activeHandle = $handle.addClass('active-handle');
            xPos = $handle.offset().left + handleOWidth - e.pageX;
            slideXPos = $slide.offset().left + ((slideOWidth - slideWidth) / 2);
			slipStart = $handle.offset().left;
            $activeHandle.on('mousemove', function(e) {
                if (mousePressed == true) {
                    slideMove(e);
                }
            });
            e.preventDefault();
        }
        function slideMove(e) {
            var handleXPos = e.pageX + xPos - handleOWidth;
			var width = $handle.offset().left - slipStart;
            if (handleXPos > slideXPos && handleXPos < slideXPos + slideWidth - handleOWidth) {
                if ($handle.hasClass('active-handle')) {
					$handle.offset({
                        left: handleXPos
                    });
				$slide.find("#slideCap").css("width",width);
				if(width >= 151) {
					$slide.find("#blueText").show();  
				}
				else if(handleXPos < 151) {
					$slide.find("#blueText").hide();
					}
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
			$handle.css("background-image","url('/images/jsms/commonImg/completed.png')");
			$handle.css("background-position","0px -2px");
			$handle.css("margin","0px");
			$handle.css("border","1px solid #c0c0c0");
            $activeHandle.offset({
                left: slideXPos + slideWidth - handleOWidth
            });
            $activeHandle.off();
            resetSlider();
            $slide.addClass('valid');
			$slide.find('#blueText').html('Verified');
			$('LoginErrMessage2').hide();
			$slide.find("#slideCap").css("width","377px");
        }
        function resetSlider() {
            mousePressed = false;
            if (sliderCompleted == false) {
                $activeHandle.offset({
                    left: slideXPos+1
                });
			$slide.find("#blueText").hide();
            $activeHandle.removeClass('active-handle');
			$slide.find("#slideCap").css("width","0");
            }
        }
    }
})(jQuery);
