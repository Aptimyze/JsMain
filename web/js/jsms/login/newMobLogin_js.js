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
										// ShowTopDownError(["Please slide to verify"]);
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
		
		if(!is_android){
			if(captchaShow==1)
				createCaptcha();
		}
    }

});


function createCaptcha(){

	// var captchaDiv='<script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha pad20" data-sitekey="6LdOuQgUAAAAAHXJXnyncVB9OcZ5pGsXpx4l04t2"></div>';
        if($(".g-recaptcha").length !=0){
            removeCaptcha();
        }
        // $('#afterCaptcha').before(captchaDiv);
        $("#afterCaptcha").before('<div class="captchaDiv pad3"><img class="loaderSmallIcon2" src="http://static.jeevansathi.com/images/jsms/commonImg/loader.gif"><script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha dn" data-sitekey="6LdOuQgUAAAAAHXJXnyncVB9OcZ5pGsXpx4l04t2"></div></div>').promise().done(function() {
            setTimeout(function() {
                $(".loaderSmallIcon2").remove();
                $(".g-recaptcha").removeClass("dn");
            }, 1000);               
      });
	
}
function removeCaptcha()
{
	$(".g-recaptcha").remove();
}


// (function($) {
//     $.fn.slideToCAPTCHA = function(options) {
//         options = $.extend({
//             handle: '.handle',
//             cursor: 'move',
//             direction: 'x', //x or y
//             customValidation: false,
//             completedText: 'Done!'
//         }, options);
        
//         var $handle = this.find(options.handle),
//             $slide = this,
//             handleOWidth,
//             xPos,
//             yPos,
//             slideXPos,
//             slideWidth,
//             slideOWidth,
//             $activeHandle,
//       slipStart,
//             mousePressed = false,
//             sliderCompleted = false,
//             $formEl = $slide.parents('form');
//         startSlider();
       
//          $handle.css('cursor', options.cursor)
//             .on('touchstart', function(e){ slideOn(e); });

//         function startSlider() {
         
//             if (options.customValidation === false) {
//                 $formEl.attr('onsubmit', "return $(this).attr('data-valid') === 'true';");
//             }
//             $slide.addClass('slide-to-captcha');
//             $handle.addClass('slide-to-captcha-handle');
//             handleOWidth = $handle.outerWidth();
//             slideWidth = $slide.width();
//             slideOWidth = $slide.outerWidth();
//         }
//         function slideOn(e) {
//             mousePressed = true;
//             $activeHandle = $handle.addClass('active-handle');
//              xPos = $handle.offset().left + handleOWidth - (Math.round(e.originalEvent.touches[0].pageX));
//             slideXPos = $slide.offset().left + ((slideOWidth - slideWidth) / 2);
//       slipStart = $handle.offset().left;
//              $activeHandle.on('touchmove', function(e){ slideMove(e); })
//                 .on('touchend', function(e){ slideOff(); });
//             e.preventDefault();
//         }
//         function slideMove(e) {
//              var MovepageX = Math.round(e.originalEvent.touches[0].pageX);
//             var handleXPos = MovepageX + xPos - handleOWidth;
//       var width = $handle.offset().left - slipStart;
//             if (handleXPos > slideXPos && handleXPos < slideXPos + slideWidth - handleOWidth) {
//                 if ($handle.hasClass('active-handle')) {
//           $('.active-handle').offset({
//                         left: handleXPos
//                     });
//           $("#slideCap").css("width",width);
         
//                 }
//             } else {
//                 if (handleXPos <= slideXPos === false) {
//                     sliderComplete();
//                 }
//                 $activeHandle.mouseup();
//             }
//         }
//         function sliderComplete() {
//             sliderCompleted = true;
//             $("#blueText").addClass("slided");
//       $(".handle").removeClass("slide-to-captcha-handle").addClass("slide-to-captcha-handle-verified");
     
//             $activeHandle.offset({
//                 left: slideXPos + slideWidth - handleOWidth
//             });
//             $activeHandle.off();
//             slideOff();
           
//             $slide.addClass('valid');
//       $('#blueText').html('Verified');
//       $("#slideCap").css("width","100%");
      
//             //$('.slide-to-captcha').attr('data-content', options.completedText);
//         }
//         function slideOff() {
//             mousePressed = false;
//             if (sliderCompleted == false) {
//               $("#blueText").removeClass("slided");
//                 $activeHandle.offset({
//                     left: slideXPos+1
//                 });
      
//                 $activeHandle.removeClass('active-handle');
//         $("#slideCap").css("width","0");
//             }
//         }
//     }
// })(jQuery);


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
