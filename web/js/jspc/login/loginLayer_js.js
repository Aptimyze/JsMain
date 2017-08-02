var loginAttempts=0;
var secureSite=0;
var LoginLayerByUserActions = false;
if (window.location.protocol == "https:")
	secureSite=1;
function LoginValidation()
{
	/* GA tracking */
	if(LoginLayerByUserActions){
		GAMapper("GA_LL_LOGIN");
	}
	else{
		GAMapper("GA_TOPBAR_LOGIN");
	}
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
				loginUrl=SSL_SITE_URL+"/api/v1/api/login?&captcha="+captchaShow+"&fromPc=1&rememberme="+$("#remember").val()+"&g_recaptcha_response="+$("#g-recaptcha-response").val()+"&secureSite="+secureSite;
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
	var re = /^((\+)?[0-9]*(-)?)?[0-9]{7,}$/i;
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
			isd = '';
			phone = str;
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
	 // if($("#blueText").html()=="Slide to Verify" &&  $('#captchaDiv').is(':visible'))
  //   {
  //     $("#LoginMessage").hide();
  //     $("#LoginErrMessage").addClass("disp-none");
  //     $("#LoginErrMessage2").removeClass("disp-none");
  //     return false;
  //   }
    return true;
}

function after_login(response)
	{
		if(response)
				GAMapper("GA_LL_LOGIN_SUCCESS");
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
	var loginFlag = false;
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
				$("#CaptchaErrMessage").removeClass("disp-none");
			  // $("#LoginErrMessage2").removeClass("disp-none");
			  hideCommonLoader();
				removeCaptcha();
				if($("#commonOverlay").is(':visible')){
				 createCaptcha();
			   }
				else
				{ 
				  createCaptcha("logoutPage");
				}
				GAMapper("GA_LL_LOGIN_FAILURE");
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
			$("#CaptchaErrMessage").addClass("disp-none");
  			// $("#LoginErrMessage2").addClass("disp-none");
  			$("#EmailContainer").addClass("brderred");
  			$("#PasswordContainer").addClass("brderred");
			if(captchaShow == 1)
			{
				createCaptcha();
			}
  			setTimeout(function(){
  				$("#emailErr").removeClass("visb");
  				$("#EmailContainer").removeClass("brderred");
  				$("#passwordErr").removeClass("visb");
  				$("#PasswordContainer").removeClass("brderred");
  				},3000);
  			GAMapper("GA_LL_LOGIN_FAILURE");
      }
		}
		else if(response == 2)
		{
			hideCommonLoader();
			$("#CaptchaErrMessage").removeClass("disp-none");
  			$("#LoginErrMessage").addClass("disp-none");
  			$("#LoginMessage").addClass("disp-none");
  			// $("#LoginErrMessage2").addClass("disp-none");
  			$("#EmailContainer").addClass("brderred");
  			$("#PasswordContainer").addClass("brderred");
  			setTimeout(function(){
  				$("#emailErr").removeClass("visb");
  				$("#EmailContainer").removeClass("brderred");
  				$("#passwordErr").removeClass("visb");
  				$("#PasswordContainer").removeClass("brderred");
  				},3000);
  			GAMapper("GA_LL_LOGIN_FAILURE");
		}
		else
		{
			loginFlag = true;
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
		LoginLayerByUserActions = false;
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
                	/* flag for user action resulting for login layer */
                	LoginLayerByUserActions = true;
                	/* GA tracking */
                	GAMapper("GAV_LL_SHOW",{action:"by user action"});
                	GAMapper("GA_SEARCH_LOGGEDOUT_ALBUM");
					$("#loginRegistration").addClass("loginAlbumSearch");
					$("#LoginMessage").addClass('txtc').text("Login For the benefit of the privacy of all members, we require you to kindly Login or Register to view the photos");
				}
				else if($(this).hasClass("loginProfileSearch")){
					/* flag for user action resulting for login layer */
                	LoginLayerByUserActions = true;
					/* GA tracking */
					GAMapper("GAV_LL_SHOW",{action:"by user action"});
                	GAMapper("GA_SEARCH_LOGGEDOUT_PROFILE");
					$("#loginRegistration").addClass("loginProfileSearch");
					$("#LoginMessage").addClass('txtc').text("For the benefit of the privacy of all members, we require you to kindly Login or Register to view the profile");
				}

				/* GA tracking */
				var SplitId = this.id.split('-'); 
				if(SplitId.length == 3){
					LoginLayerByUserActions = true;
					GAMapper("GAV_LL_SHOW",{action:"by user action"});
					GAMapper("GA_SEARCH_LOGGEDOUT_EOI", {"type": SplitId[0]});
				}

				if(!LoginLayerByUserActions)
					GAMapper("GAV_LL_SHOW");
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
                        // removeCaptcha();
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

					/* GA tracking */
					if(LoginLayerByUserActions){
						GAMapper("GA_LL_REGISTER");
					}
					else{
						GAMapper("GA_TOPBAR_REGISTER");
					}

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
	logSiteUrl();
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

			/* GA tracking */
		if(LoginLayerByUserActions){
			GAMapper("GA_LL_FORGOT");
		}
		else{
			GAMapper("GA_TOPBAR_FORGOT");
		}
		
		$("#ForgotPasswordMessage").html("Enter your registered email or phone number of Jeevansathi to receive an Email and SMS with the link to reset your password.");
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
                    	$("#sendLinkForgot").unbind('click');
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
			 		$("#sendLinkForgot").unbind('click');
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
				$("#sendLinkForgot").unbind('click');
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
				$("#sendLinkForgot").unbind('click');
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

			/* GA tracking */
			GAMapper("GA_FORGOTL_SENDLINK");

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
								 $("#ForgotPasswordMessage").html(result.responseMessage);
								 $("#forgotPasswordForm").addClass("disp-none");
								 $("#sendLinkForgot").unbind('click');
							 }
							 else
							 {
								 $("#forgotPasswordErr").html(result.responseMessage).addClass("visb");
								 $("#userEmailBox").addClass("brderred");
								 setTimeout(function(){
									 $("#forgotPasswordErr").removeClass("visb");
									$("#userEmailBox").removeClass("brderred");
								},10000);
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
	var captchaDiv = '<div class="captchaDiv pad3"><img class="loaderSmallIcon2" src="/images/jsms/commonImg/loader.gif"><script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha dn" data-sitekey='+site_key+'></div></div>';
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
  $('.captchaDiv').each(function(index, element) {
      $(element).remove();});
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