var isd,mainPhone,mainPhoneType,timeoutId,intervalId,limit,iteration,verifiedAjaxReq=null,trialOver='N',hashChangeFromFunction=0;
var verificationFailedOnce=0,currentPhoneType='M',chatHideIntervalOb;
var noOfAjaxRequestAllowed = 18;
var save=0;
var isd_regex = /^([0-9]{1,3})$/;///^[+]?[0-9]+$/;
var phonePatternIndia = /^([7-9]{1}[0-9]{9})$/;
var phonePatternOther = /^([1-9]{1}[0-9]{5,13})$/;
var isdCodes = ["0", "91","+91"];
var fromReg=0,groupname;
function jsmsPhoneReady()
{
	if($("#fromReg").val()==1)
		fromReg = 1;
	groupname = $("#groupname").val();
        sourcename = $("#sourcename").val();
	iteration = 0;
	limit=0;
	isd = $("#isdNumber").val();
	mainPhone = $("#mainPhoneNumber").val();
	mainPhoneType = "PHONE1";
	setPhoneValues();
    setDialNumber($("#dialNumber1").text());
    $("#mainBottomButton").unbind().bind('click',function(){
    	GAMapper("GA_PVS1_VERIFY_BTN");
    	GAMapper("GA_PVS2_SHOW");
    	sendSMSAjax(1);
    	
     });
    setTollFree();
	setFailedScroll();
 	hideChat();
}



function setTollFree()
{
if(isd=='91')
    {
    	$('#tollFree').attr('href','tel:'+tollFreeINR).text(tollFreeINR);
    }
    else $('#tollFree').attr('href','tel:'+tollFreeNRI).text(tollFreeNRI);
 	
}
function cssLayerFix() {
	$(".cssLayerFix").each(function(){
    	$(this).css('margin-left','-'+$(this).width()/2+'px')
    	.css('margin-top','-'+$(this).height()/2+'px');});
    
}

function hideChat() {

chatHideIntervalOb=setInterval(function () {
	if($("#fc_chat_layout").length){
		$("#fc_chat_layout").hide();
		clearInterval(chatHideIntervalOb);
	}
},100);

}
function showOTPLayer(){
	setHash("verifyLayer");
showNumberedLayer(2);
 	$("#backButton").unbind().bind('click',function() 
 	{
    		showMainLayer();
    		setHash("mainLayer");
    	
 	});

$("#mainBottomButton").unbind('click');
$("#mainBottomButton2").unbind().bind('click',sendMatchOtpAjax);
} 


function setHash(newHash) {
	hashChangeFromFunction=1;

	var url=document.URL.split("#");
	window.location.href=url[0]+"#"+newHash;
}
function showMainLayer(){
			showNumberedLayer(1);
    		$("#mainBottomButton").attr('href','javascript:;');
    		$("#mainBottomButton").unbind().bind('click',function(){
    			sendSMSAjax(1);
    			
    			
     });
}

function showNumberedLayer(layerNumber){
	$(".js-NumberedLayer").hide();
    $(".js-NumberedLayer"+layerNumber).show();
   
}
function setPhoneValues()
{
	$('span[id^="mainPhone"]').text(mainPhone);
    $('span[id^="isd"]').text(isd);
}
function showError(error)
{
		$( "#validation_error" ).text(error);
		$( "#validation_error" ).slideDown( "slow", function() {}).delay( 3000 );
		$( "#validation_error" ).slideUp( "slow", function() {});
}


function sendMatchOtpAjax() {
	GAMapper("GA_PVS2_VERIFY_BTN");
var OTP=$("#matchOtpText").val();
if(!OTP)
{
	showError('Please provide a code.'); return;
}
$("#mydiv").show();
var ajaxData={'enteredOtp':OTP,'phoneType':currentPhoneType};
$.ajax({
                                url:'/phone/matchOtp',
                                dataType: 'json',
                                data: ajaxData,
                                type: "POST",
                                  success: function(response) 
                        {
									$('#mydiv').hide(); 
									if(response.matched=='true')
									{	
									verifiedScreen();
									GAMapper("GA_PVS3_VERIFY_SUCCESS");
									// GAMapper("GA_PVS3_PHONEVERIFIED");

									}
									else if(response.matched=='false')
									{
									if(response.trialsOver=='N')
										{
										$("#otpWrongCodeLayer").show();cssLayerFix();
										trialsOver='N';
										GAMapper("GA_PVS3_WRONGOTPLAYER");
										}
									else if(response.trialsOver=='Y') 
										{

											showOTPFailedLayer();
											trialsOver='Y';
											$(".js-noTrials").show();
										GAMapper("GA_PVS3_TRIALSOVER");
										}

									}
						}
	});	
}


function sendSMSAjax(time) {
var ajaxData={'phoneType':currentPhoneType};
$.ajax
                        ({
                                url:'/phone/sendOtpSMS',
                                dataType: 'json',
                                data: ajaxData,
                                type: "POST",
                                  success: function(response) 
                        {
							$("#otpResendingLayer").hide();
							$("#mydiv").hide();

							if(response.trialsOver=='Y') 
										{
											trialsOver='Y';
											showOTPFailedLayer();

										}
							else if(time)showOTPLayer();			
				
							if(response.SMSLimitOver=='Y')
							{
						    $("#resendSMSDiv").hide();
							}
							else
							$("#resendSMSDiv").show();

						}




});

	if(time==1)	$("#mydiv").show();
	else {
		$("#otpResendingLayer").show();
		cssLayerFix();
}

                    }


function checkValidMobile(mobileISD,mobileNumber)
{
        if($.inArray(mobileISD,isdCodes)!= -1 && (mobileNumber.length!=10 || !phonePatternIndia.test(mobileNumber)))
        {
                errorIndex = 0;
                return false;
        }
        else if(mobileNumber.length<6 || mobileNumber.length>14 || !phonePatternOther.test(mobileNumber))
        {
                errorIndex = 0;
                return false;
        }
        return true;
}

function validatePhone()
{
	GAMapper("GA_PVS1_VALIDATE_NUMBER");
	var isdVal = $("#ISD").val().trim().replace(/^[0]+/g,"");
	var phone = $("#PHONE_MOB").val().trim().replace(/^[0]+/g,"");
	var error='';
	if(isdVal=="")
		error = "Provide an ISD Code";
        else if(!isd_regex.test(isdVal))
		error="Provide a valid ISD Code";
	else if(phone=="")
		error = "Provide a mobile number";
	else if(!checkValidMobile(isdVal,phone))
		error = "Provide a valid mobile number";
	if(error)
		showError(error);
	else
	{
		var url = "/api/v1/phone/save?nophver=1";
		var postData =
		{
			'NUMBER': $("#PHONE_MOB").val(),
			'ISD': $("#ISD").val(),
			'TYPE':mainPhoneType
		};
		$('#mydiv').show(); 
		var request = $.ajax
                        ({
        						headers: { 'X-Requested-By': 'jeevansathi' },       
                                url:url,
                                dataType: 'json',
                                data: postData,
                                type: "POST",
                                  success: function(result) {
                                        if(true)
                                        {
						$('#mydiv').hide(); 
                                                changingData=result;
                                                originalData=JSON.parse(JSON.stringify(changingData));
						if(originalData.responseStatusCode=='0')
						{
							save = 1;
							isd = isdVal;
							mainPhone = phone;
							closeEdit();
							if(originalData.DIAL_NUMBER)
								setDialNumber(originalData.DIAL_NUMBER);
							setPhoneValues();
							setTollFree();

						}
						else
							showError(originalData.responseMessage);
                                        }
                                  }
                        });

	}
}

function setDialNumber(dialNumber)
{
	$('[id^="dialNumber"]').text(dialNumber);
	$('[id^="call"]').attr("href","tel:"+dialNumber).text(dialNumber);
	$("#dialOnly").attr("href","tel:"+dialNumber);

}

function closeEdit()
{
	$("#mainScreen").show();
	setHash("mainLayer");
	$("#editScreen").hide();
}

function checkVerified()
{
	
//	$("#attemptingToVerify").show();
	var url = "/api/v1/phone/verified?nophver=1";
        verifiedAjaxReq = $.ajax
                        ({
                                url:url,
				dataType: 'json',

                                type: "POST",
				  success: function(result) {
					if(true)
					{
						limit=limit+1;
						changingData=result;
						originalData=JSON.parse(JSON.stringify(changingData));
						if(originalData.FLAG=="Y")
						{
							$('#mydiv').hide(); 
							verifiedScreen();
	
						}
						else
						{
							if((limit>=noOfAjaxRequestAllowed && intervalId)||iteration==0)
							{
								verificationFailedOnce=1;
								stopAttempt();
								
							}
						}
					}
				  }
                        });
}
function verifiedScreen()
{
	stopAjaxRequests();
	$("#attemptingToVerify").hide();
	setHash("verified");
	$("#verificationSuccessfull").show();
}
function failedScreen()
{
	setHash("failed");
	$("#phoneVerificationFailedScreen").show();

}
function failedOk()
{
	GAMapper("GA_OTP_VERIFY_FAILED");
	$("#phoneVerificationFailedScreen").hide();
	setHash("mainLayer");
	showMainLayer();

}

function verifyAttempt()
{
	setHash("attempt");
	$("#attemptingToVerify").show();//slideDown( 2000, function() {});
}

function stopAttempt()
{
	GAMapper("GA_PVS3_STOP");
	limit=0;
	setHash("failed");
	$("#attemptingToVerify").hide();//slideUp( "slow", function() {});
	stopAjaxRequests();
	failedScreen();
}
function editScreen()
{
	GAMapper("GA_PVS1_EDIT_NUMBER");

	setHash("edit");
	var title = '';
	if(mainPhone)
		title = "Edit Primary No.";
	$("#numberTitle").html(title);
	$("#ISD").val(isd);
	$("#PHONE_MOB").val(mainPhone);
	$("#mainScreen").hide();
	$("#editScreen").show();
}
function checkVerificationStatus()
{
	iteration = 1;
	verifyAttempt();
        if(timeoutId)
                clearTimeout(timeoutId);
        if(intervalId)
                stopAjaxRequests();
        timeoutId = setTimeout(function(){intervalId = window.setInterval(function(){checkVerified()},5000);},20000);
}
function stopAjaxRequests()
{
	iteration=0;
    verifiedAjaxReq=null;

        if(timeoutId)
                clearTimeout(timeoutId);
		if(intervalId)       
        window.clearInterval(intervalId);
        
}
function verifiedOk()
{
	GAMapper("GA_PVS3_VERIFIED_OK");
	setHash("verifiedOk");
	$('#mydiv').show();
	if(fromReg==1)
		window.location.href="/profile/viewprofile.php?ownview=1&groupname="+groupname+"&sourcename="+sourcename+"&fromPhone=1";
	else
		window.location.href="/profile/mainmenu.php";
}
function setFailedScroll()
{
	var screenHeight = $(window).height();
	var p1Height = $("#p1").height();
	var p2Height = $("#p2").height();
	var bottomButtonsHeight = $("#bottomButtons").height();
	var phoneLayoutHeight = p1Height+ p2Height+bottomButtonsHeight;
	if(screenHeight<phoneLayoutHeight)
	{
	var p2Height = screenHeight-(p1Height+bottomButtonsHeight);
	$("#p2").css({"height":p2Height+'px', "overflow":"auto"}).scrollTop($('#p2').height());
	}
}


$(window).bind('hashchange', function(e) {
    var newHash = window.location.hash.replace(/^#/,'');
	var oldHash=e.originalEvent.oldURL.replace(/.*#/,'');
		if(hashChangeFromFunction)
		{ 
			hashChangeFromFunction=0;
			return;
		}

	switch(oldHash)
	{
		
		case "verifyLayer":
				showMainLayer();
		break;

		case "OTPFailed":
				stopAttempt();
		break;

		case "edit":
				save=0;
				closeEdit();
		break;

		case "verifiedOk":
			window.location.href="/profile/mainmenu.php";
		break;
		
		case "failed":
				failedOk();
		break;
		
		case "attempt":
				stopAttempt();
		break;
	}
	setPhoneValues();
	setFailedScroll();
	
    });
$(function(){

           var vwid = $( window ).width();
           var vhgt = $( window ).height();


                    var hgt = $( window ).height();
                        hgt = hgt+"px";
                        var wid = $( window ).width();
                        wid = wid+"px";
                        $('div.grad_cp, div.outerdiv').css( "height", hgt );
                        $('.imgset1').css( "height", hgt );
                        $('.imgset1').css( "width", wid );
});

function showOTPFailedLayer() 
{
	setHash("OTPFailed");
	$(".js-noTrials").hide();
	showNumberedLayer(3);
    $("#mainBottomButton").unbind().bind('click',function(){
    	$(".js-NumberedLayer").hide();
    	checkVerificationStatus();
    	 });
    $("#mainBottomButton").attr('href','tel:'+$("#dialNumber1").text());
    $("#backButton").unbind().bind('click',function() 
 	{
    		showMainLayer();
    		setHash("mainLayer");
    	
 	});

    setFailedScroll();

}
