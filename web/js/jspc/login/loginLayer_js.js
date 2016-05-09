
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
			if(validateEmail(email))
			{
				loginUrl=SSL_SITE_URL+"/api/v1/api/login?fromPc=1&rememberme="+$("#remember").val();
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
				$("#emailErr").addClass("visb").html("Invalid Format");
				$("#EmailContainer").addClass("brderred");
				setTimeout(function(){
					$("#emailErr").removeClass("visb");
					$("#EmailContainer").removeClass("brderred");
				},3000);
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
    var x = email;
    var re = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    return re.test(email);
    }

function after_login(response)
	{
		var address_url=window.location.href;
		if(window.location.href.indexOf("redirectUri=")>0)
			address_url=window.location.href.substr(window.location.href.indexOf("redirectUri=")+12);
		if(response==0)
		if( window.top.location.pathname=="/static/logoutPage" || window.top.location.pathname=="/jsmb/login_home.php")
			window.top.location.href = "/profile/intermediate.php?parentUrl=/myjs/jspcPerform";
		else
			window.top.location.href = "/profile/intermediate.php?parentUrl="+address_url;
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
			hideCommonLoader();
			$("#LoginErrMessage").removeClass("disp-none");
			$("#LoginMessage").addClass("disp-none");
			$("#EmailContainer").addClass("brderred");
			$("#PasswordContainer").addClass("brderred");
			setTimeout(function(){
				$("#emailErr").removeClass("visb");
				$("#EmailContainer").removeClass("brderred");
				$("#passwordErr").removeClass("visb");
				$("#PasswordContainer").removeClass("brderred");
				},3000);
		}
		else
		{
			after_login(response);
		}		
	}
	
		
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
            beforeSend: function() {
                $('#commonOverlay').fadeIn(200, "linear");
                $('#topNavigationBar').removeClass("z2");
            },
            success: function(response) {
                $('#commonOverlay').after(response);
                $('#login-layer').fadeIn(300, "linear");
                $('#cls-login').click(function() {
                    $('#login-layer').fadeOut(200, "linear", function() {
                        $('.js-overlay').fadeOut(300, "linear");
                         $('#login-layer').remove();
                         $('#forgotPasswordLayer').remove();
                    });
                    commonLoginBinding();
                });
                 $('.js-overlay').bind("click",function(){
					 $('#login-layer').fadeOut(200, "linear", function() {
						$('.js-overlay').fadeOut(300, "linear");
                         $('#login-layer').remove();
                         $('#forgotPasswordLayer').remove();
					});
                 $(this).unbind("click");
             });
                //start remember me script
               commonLoginBinding();
               forgotPasswordBinding(1);
               customCheckboxLogin("remember",0);
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
				if(validateEmail(email))
				{       
					showCommonLoader("#forgotPasswordContainer");
					 $.ajax({
						 url:"/api/v1/api/forgotlogin",
						 type: "POST",
						 datatype:'json',
						 cache: true,
						 async:false,
						 data:{email:email.trim()},
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
					$("#forgotPasswordErr").html("Provide a valid email address").addClass("visb");
					$("#userEmailBox").addClass("brderred");
					 setTimeout(function(){
						 $("#forgotPasswordErr").removeClass("visb");
						$("#userEmailBox").removeClass("brderred");
					},3000);
				}
			}
			else
			{
				$("#forgotPasswordErr").html("Provide your email address").addClass("visb");
				$("#userEmailBox").addClass("brderred");
				 setTimeout(function(){
					 $("#forgotPasswordErr").removeClass("visb");
					$("#userEmailBox").removeClass("brderred");
				},3000);
			}
		});
}
