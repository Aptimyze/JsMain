timer1=null,timer2=null, timerTime=null,verifyFail=0,updatedIsd=null, updatedNum=null,mobileNumDiv=null,isdDiv=null,mailToText='mailto:help@jeevansathi.com?Subject=Please assist. Phone verification failed for: {username}',_NProgress=0,phoneType='',verifyAjaxSent=0;


function shakeOTPInput() {
   var l = 10;  
   var temp=$( "#OTPOuterInput");
   if(!temp) return;
    var brdr=temp.css('border-color');
    temp.css('border-color','#d9475c');
   for( var i = 0; i < 10; i++ )   
   	 $( "#OTPOuterInput").animate( { 
         'margin-left': "+=" + ( l = -l ) + 'px',
         'margin-right': "-=" + l + 'px'
      }, 30);  
   $("#OTPIncorrectSpan").hide();
   $("#OTPIncorrectSpan").fadeIn(1000);
   
     }

 
function formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function updateTime(){

    var s = timerTime.getSeconds();
	var m = timerTime.getMinutes();
	if (!m && !s) {clearInterval(timer1); clearInterval(timer2); NProgress.set(1.0);
 showFailedLayer(); verifyFail=1;


}
    timerTime.setSeconds(s-1);
    s = timerTime.getSeconds();
	m = timerTime.getMinutes();

    m = formatTime(m);
    s = formatTime(s);
  
    document.getElementById('missedCallTimer').innerHTML =
    m + ":" + s;
    _NProgress+=1/120;
    NProgress.set(_NProgress);
timerTime.setSeconds(s);
}


function showFailedLayer(){
var ajaxData={'layerType':'failed'};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.url='/common/phoneVerifyLayer';

sendAjaxHtmlDisplay(ajaxConfig,afterFailedLayer);

}

function afterFailedLayer(){
if (updatedIsd=='91') $(".TFNFailedLayer").html(tollFree_INR); else $(".TFNFailedLayer").html(tollFree_NRI);
$("#verifTryAgain").bind('click',function (){showVerifyLayer(isdDiv,mobileNumDiv,editButton,landLineFlag);});
$("#failedLayerNum").html(updatedNum);
$("#failedLayerIsd").html(updatedIsd);
temp=mailToText.replace(/{username}/g,username);
$(".failedLayerEmail").attr('href',temp);


}


function afterVerifyLayer() {
$("#verifyLayerCancel").bind('click',function (){
	clearInterval(timer2);
	clearInterval(timer1);
	
closeCurrentLayerCommon(closeButtonClick);
});

if (updatedIsd=='91') $(".TFNveriLayer").html(tollFree_INR); else $(".TFNveriLayer").html(tollFree_NRI); 
$("#verifyLayerNum").html(updatedNum);
$("#verifyLayerIsd").html(updatedIsd);
$("#dialNumber").html(dialNumber);
temp=mailToText.replace(/{username}/g,username);
$("#verifyLayerEmail").attr('href',temp);;

$("#verifyLayerUsername").html(username);
NProgress.start();
timerTime=new Date();
timerTime.setMinutes(2);
timerTime.setSeconds(0);
timer1=setInterval('updateTime()',1000);
timer2=setInterval('checkVerifiedOrNot()',5000);

}



function afterOtpFailedLayer() {
if(!$("#closeButtonOtp"))
	$("#closeButtonOtp").prependTo('body');	
$("#missedCallButton").bind('click',function (){
	/* GA tracking */
	GAMapper("GA_VOL_MISS_CALL");
showVerifyLayer();
});
fillLayerDetails();
}




function showOtpLayer(_isdDiv,_mobileNumDiv,_editButton,_landLine){
landLineFlag=_landLine?_landLine:'';
editButton=_editButton?_editButton:'';
updatedIsd=$("#"+_isdDiv).attr('saved');
updatedNum=$("#"+_mobileNumDiv).attr('saved');
phoneType=$("#"+_mobileNumDiv).attr('phonetype');
isdDiv=_isdDiv;
mobileNumDiv=_mobileNumDiv;

var isd=$("#"+isdDiv).val();
var mobileNum=$("#"+mobileNumDiv).val();

		if (isd)
		isd=isd.trim();

		if(mobileNum)
		mobileNum=mobileNum.trim();
	if(isd.indexOf('+')==0) isdNew=isd.substring(1);
	else isdNew=isd;
	isdNew = isdNew.replace(/^0+/, '');

valid=validateNum(mobileNum,isdNew);
	if (valid!='pass' && !landLineFlag){
 	 $("#phoneVerifyErr").html(valid).show(); //only for phone page... 
 	 return;
	}

if (updatedIsd==isdNew && updatedNum==mobileNum){
	//showVerifyLayer();return;

	if(landLineFlag) 
	{
	showVerifyLayer();
	return;
	}
$("#phoneVerifyErr").hide();
var ajaxData={'phoneType':phoneType,'PCLayer':'Y'};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.url='/phone/sendOtpSMS';
sendAjaxHtmlDisplay(ajaxConfig,afterOtpLayer);
}
else {
	$("#phoneVerifyErr").hide();
 	sendSaveRequest(mobileNum,isdNew);
 

}

}

function showVerifyLayer(){
	
NProgress.configure({ parent: '#forNProgress',trickle:false,minimum:0});
NProgress.done();
_NProgress=0;
verifyFail=0;
$("#phoneVerifyErr").hide();
var ajaxData={'layerType':'verify','phoneType':phoneType};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.url='/common/phoneVerifyLayer';
sendAjaxHtmlDisplay(ajaxConfig,afterVerifyLayer);



}
function sendOtpFailedLayerRequest(){

var ajaxData={'layerType':'verify'};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.url='/phone/desktopOtpFailedLayer';
sendAjaxHtmlDisplay(ajaxConfig,afterOtpFailedLayer);

}


function sendSaveRequest(mobileNumTemp,isdTemp){
showCommonLoader();
if (isdTemp.indexOf('+')==0) isdTemp=isdTemp.substring(1);
var ajaxConfig={};
ajaxConfig.url = "/api/v1/phone/save?nophver=1";
ajaxConfig.data =
		{
			'NUMBER': mobileNumTemp,
			'ISD': isdTemp,
			'TYPE':"PHONE1"
		};

ajaxConfig.dataType='json';
ajaxConfig.type="POST";
ajaxConfig.headers = { 'X-Requested-By': 'jeevansathi' };
ajaxConfig.success=function(result) {
									hideCommonLoader();
                                	if(result.responseStatusCode=='0'){
                                	updatedIsd=isdTemp;
                                	updatedNum=mobileNumTemp;
                                	$("#"+isdDiv).attr('saved',isdTemp);
                                	$("#"+mobileNumDiv).attr('saved',mobileNumTemp);
                                	showOtpLayer(isdDiv,mobileNumDiv,editButton,landLineFlag);
                                }
                                else{ $("#phoneVerifyErr").html(result.responseMessage).show();closeCurrentLayerCommon(closeButtonClick);}
                               } 
       					

jQuery.myObj.ajax(ajaxConfig);

}



function checkValidPhone(mobileISD,mobileNumber)
	{

	var phonePatternIndia = /^([7-9]{1}[0-9]{9})$/;
	var phonePatternOther = /^([1-9]{1}[0-9]{5,13})$/;
	var isdCodes = ["0", "91","+91"];

		if(isdCodes.indexOf(mobileISD)!= -1 && (mobileNumber.length!=10 || !phonePatternIndia.test(mobileNumber)))
			return false;
		else if(mobileNumber.length<6 || mobileNumber.length>14 || !phonePatternOther.test(mobileNumber))
			return false;
		return true;
	}





function checkVerifiedOrNot(){
	if(verifyFail==1) {clearInterval(timer2);return;}
	if(verifyAjaxSent ) return;
	var ajaxConfig={};
ajaxConfig.url = "/api/v1/phone/verified?nophver=1";
ajaxConfig.data ={'phoneType':phoneType};
ajaxConfig.dataType='json';
ajaxConfig.type="POST";
verifyAjaxSent=1;
ajaxConfig.success=function(result) {
					verifyAjaxSent=0;
				  	if (verifyFail!=1){
				  	originalData=JSON.parse(JSON.stringify(result));
						if(originalData.FLAG=="Y"){
						clearInterval(timer1);
						clearInterval(timer2);
					showSuccessLayer();
										}
										
										}
				}
ajaxConfig.error=function(){verifyAjaxSent=0;}
jQuery.myObj.ajax(ajaxConfig);

}



function afterSuccessLayer(){
	$("#closeButtonOtp").hide();
$("#verifSuccessOk").bind('click',function (){
	/* GA tracking */
	GAMapper("GA_VOL_SUCCESS_OK");
	if(typeof(redirectUrlForPhoneModule)!='undefined')
	window.location.href=redirectUrlForPhoneModule;
else {closeCurrentLayerCommon(closeButtonClick);

	//for edit profile page to change the css of the verify buttons
	if(editButton){

		$('#'+editButton).html('Verified').removeClass('cursp color5').addClass('color12');

	}
}

});

if (updatedIsd=='91') $("#TFNSuccessLayer").html(tollFree_INR); else $("#TFNSuccessLayer").html(tollFree_NRI); 
$("#successLayerNum").html(updatedNum);
$("#successLayerIsd").html(updatedIsd);
temp=mailToText.replace(/{username}/g,username);
$("#successLayerEmail").attr('href',temp);;


}

function showSuccessLayer() {
var ajaxData={'layerType':'success'};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.url='/common/phoneVerifyLayer';

sendAjaxHtmlDisplay(ajaxConfig,afterSuccessLayer);
}


function validateNum(mobileNumTemp,isdTemp){


	var isd_regex = /^([1-9]{1}[0-9]{0,2})$/;
	var error=null;
		
		if(isdTemp=="")
		error="Please provide an ISD code";
		if(!isd_regex.test(isdTemp))
		error ="Please provide a valid ISD code";
        else if(mobileNumTemp=="")
		error ="Please provide a mobile number";
        else if(!this.checkValidPhone(isdTemp,mobileNumTemp))
		error ="Please provide  a valid mobile number";
		if (error) return error; else return 'pass';


}



function afterOtpLayer() {
$("#closeButtonOtp").prependTo('body');
$("#closeButtonOtp").show().unbind().bind('click',function(){closeCurrentLayerCommon(closeButtonClick);$(this).hide(); });
$("#matchOtpButton").bind('click',function (){
	/* GA tracking */
	GAMapper("GA_VOL_SUBMIT");
	sendMatchOtpAjax();
});
}

function sendMatchOtpAjax() {

var currentLayer=$("#"+currentlyDisplayedLayer);
var OTP=$("#matchOtpText").val();
if(!OTP){shakeOTPInput(); return;}
var ajaxData={'enteredOtp':OTP,'phoneType':phoneType};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.success=function(response) {
	hideCommonLoader();
	if(response.matched=='true'){
		showSuccessLayer();
		GAMapper("GA_VOL_SUBMIT_SUCCESS");
	}
	else if(response.matched=='false'){
		if(response.trialsOver=='N'){
			shakeOTPInput();
			GAMapper("GA_VOL_SUBMIT_ERROR");
			currentLayer.find('#matchOtpText').css('width','83%');
			currentLayer.find("#OTPOuterInput").removeClass('phnvbdr1').addClass('brdr-1');
		}
		else if(response.trialsOver=='Y') showOTPFailedLayer();
	}
}
ajaxConfig.url='/phone/matchOtp';
jQuery.myObj.ajax(ajaxConfig);
showCommonLoader();


}

var closeButtonClick=function() 
{
$("#closeButtonOtp").hide();
clearInterval(timer1);
verifyFail=1;
}

function showOTPFailedLayer(){
$("#closeButtonOtp").show().unbind().bind('click',function(){closeCurrentLayerCommon(closeButtonClick); });
var ajaxConfig={};
ajaxConfig.type='POST';
ajaxConfig.url='/phone/desktopOtpFailedLayer';
sendAjaxHtmlDisplay(ajaxConfig,afterOtpFailedLayer)
}


fillLayerDetails=function(layerId) {
if(!layerId) return;
obj=$("#"+layerId);
var tempDiv='';
if(tempDiv=obj.find("#js-TFNumberOTP")){
if (updatedIsd=='91') tempDiv.html(tollFree_INR); else tempDiv.html(tollFree_NRI);
}

if(tempDiv=obj.find("#js-EmailOTP")){
temp=mailToText.replace(/{username}/g,username);
tempDiv.attr('href',temp);
}

if(tempDiv=obj.find("#js-MainNumOTP")){
tempDiv.html(updatedNum);
}

if(tempDiv=obj.find("#js-isdOTP")){
tempDiv.html(updatedIsd);
}


}