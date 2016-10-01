var checksum;

$(document).ready(function(){
  $("#deleteButtonID").bind('click',function() {
    var delete_reason=$("#DeleteReasonID").val();

    parent.location.href= '/static/passCheck?delete_option='+delete_option+'&delete_reason='+delete_reason;
  });

  $("#passCheckID").bind('click',function() {
    var pswrd = $('#passValueID').val();
    ajaxPassword(checksum,pswrd);
  });


$( "#otpProfileDeletionJSMS").bind('click',function() {
  showCommonOtpLayer(1);
 });
$( "#resendTextId").bind('click',function() {
        $("#otpResendingLayer").show();
        cssLayerFix(); 
        showCommonOtpLayer(0);
 });

$( "#mainBottomButton2").bind('click',function() {
  sendMatchOtpAjax();
 });

});


function cssLayerFix() {
  $(".cssLayerFix").each(function(){
      $(this).css('margin-left','-'+$(this).width()/2+'px')
      .css('margin-top','-'+$(this).height()/2+'px');});
    
}


function ajaxPassword(checksum,pswrd)
{
  $.ajax({                 
    url: '/profile/password_check.php?',
    data: "checksum="+checksum+"&pswrd="+pswrd,
    success: function(response) 
    {
      if(response=="true")
      {
          $("#deleteConfirmation-Layer").removeClass("dn").css('height',$(window).height());
          $("#deleteProfilePasswordPage").addClass('dn');
      }
      else
      {
        setTimeout(function(){
          ShowTopDownError(["<center>Invalid Password</center>"]);
        },animationtimer);
      }
    }
  });
}

function ajaxDelete(specifyReason,deleteReason)
{
            
  if(sessionStorage.getItem('offerConsent')) offerConsent='Y';
  else offerConsent='N';
  $.ajax({                 
    url: '/api/v1/settings/deleteProfile',
    data: {"deleteReason":deleteReason,"specifyReason":specifyReason,'offerConsent':offerConsent},
    success: function(response) 
    {
      if(response.output=="Deleted Successfully"){
        parent.location.href= "/static/logoutPage";
      }
      else 
      {
        setTimeout(function(){
          ShowTopDownError(["<center>Something went wrong</center>"]);
        },animationtimer);
      }
    }
  });
}

function deleteConfirmation(action)
{
	if(action=="Y"){
		if($("#offerConsentCB").is(":checked"))
				sessionStorage.setItem('offerConsent',1);
			  else 
				sessionStorage.setItem('offerConsent',0);

			if(successFlow == 1){
			  url = "/successStory/jsmsInputStory";
			  parent.location.href = url;
			} else {
			  ajaxDelete(delete_reason,delete_option);
			}
	}
	else
		window.location.href=action;
}


function showCommonOtpLayer(showLayer){

var ajaxData={'phoneType':'M'};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.url='/common/SendOtpSMS';

ajaxConfig.success=function(response) 
{
        $("#otpResendingLayer").hide();
        if(showLayer == 1)
        bringSuccessLayerOnMobile(response);
        if(response.SMSLimitOver =='Y') 
        {
            
            $("#resendTextId").hide();
        }
      
}
$.ajax(ajaxConfig);
}


function sendMatchOtpAjax() {
var OTP=$("#matchOtpText").val();
if(!OTP)
{
  displayOTPError();return;
}

var ajaxData={'enteredOtp':OTP,'phoneType':'M'};
$.ajax({
                                url:'/common/matchOtp',
                                dataType: 'json',
                                data: ajaxData,
                                type: "POST",
                                  success: function(response) 
                        { 
                  if(response.matched=='true')
                  { //what to
                  $("#deleteConfirmation-Layer").removeClass("dn").css('height',$(window).height());
                  $("#deleteProfilePasswordPage").addClass('dn');
                  }
                  else if(response.matched=='false')
                  {
                  if(response.trialsOver=='N')
                    {
                    displayOTPError();
                    }
                  else if(response.trialsOver=='Y') 
                    {
                      $("#otpProfileDeletionJSMS").hide();  
                      showOTPFailedLayer();
                      trialsOver='Y';

                    }

                  }
            }
  }); 
}



function bringSuccessLayerOnMobile()
{

  $('.js-NumberedLayer').hide();
  $("#bringSuccessLayerOnMobile").show();
}

function bringFailureLayerOnMobile()
{ 
  $('.js-NumberedLayer').hide();
   $("#attemptsOver").show();


}

function displayOTPError()
{
//$('.js-NumberedLayer').hide();
  //$("#offerCheckBox").hide();
  //$("#putPasswordLayer").show();
  //$("#buttonForCode").hide();
   //$("#bringSuccessLayerOnMobile").hide();
  $("#otpWrongCodeLayer").show();  
  cssLayerFix(); 

}

function showOTPFailedLayer(){
    
    $('.js-NumberedLayer').hide();
    
    $('.js-NumberedLayer3').show();
}
