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
		});
$("#loginButton").bind(clickEventType,function(){
  
  
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
	
	$("#showHide").bind("click",function(){
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
        var captchaDiv = '<div class="captchaDiv pad3"><img class="loaderSmallIcon2" src="http://static.jeevansathi.com/images/jsms/commonImg/loader.gif"><script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha dn" data-sitekey='+site_key+'></div></div>';
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
