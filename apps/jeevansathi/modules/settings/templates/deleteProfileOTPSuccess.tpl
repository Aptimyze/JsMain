<div id='js-OTPverifyLayerId' class="phnvwid4 mauto layersZ pos_fix setshare disp-none fullwid bg-white" >
<i id="closeButtonOtp" style='z-index:10;' class="sprite2 close pos_fix closepos cursp disp-none"></i>
<div class="phnvp4 f17 fontreg color11 phnvbdr4"> Phone Verification </div>

<script type="text/javascript">
var resendOtpLink=function(){
$("#resendOtpLink").unbind('click');
  $("#resendOtpLink").html('Sending');
  $("#resendOtpGif").show();
var ajaxData={'phoneType':phoneType};
var ajaxConfig={};
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.success=function(response) {
  if(response.SMSLimitOver=='Y'){
    $("#js-SMSResendDiv").hide();
    return;
  }


$("#resendOtpLink").bind('click',resendOtpLink);
$("#resendOtpLink").html('Resend Code');
$("#resendOtpGif").hide();

};
ajaxConfig.url='/phone/sendOtpSMS';
jQuery.myObj.ajax(ajaxConfig);
};
$("#resendOtpLink").bind('click',resendOtpLink);
</script>

<input type="hidden" name="">
<input type="hidden" name="phoneType">
<input type="hidden" name="mobileNum">


   <!--start:layer 1-->
    <div class="color11">
              <!--start:div-->
        <div class="phnvwid3 mauto pt40 pb27 fontlig">
          <p class=" f17  txtc lh26  ">Verification Code has been sent to +<span class="fontreg" id='js-isdOTP' ></span>-<span id='js-MainNumOTP' class="fontreg"></span>.</p><p id='js-SMSResendDiv' class="txtc ~if $smsResend eq 'N'`disp-none~/if`" >Didn't receive code?&nbsp;<a id='resendOtpLink' class="color5 cursp fontreg f17">Resend Code</a><img id='resendOtpGif' class='disp-none' src="/images/jspc/commonimg/loader.gif"></p>
          <p class="f13 txtc pt15">
        </p><div id='OTPOuterInput' style="margin:1px 0px 0px 0px;" class="phnvbdr1 bg-white phnvp8">
                 <div class="fullwid clearfix">
                    <div class="fl lh30 fullwid">
                       <input id='matchOtpText' type="text" placeholder="Enter Code" class="bgnone brdr-0 pl10 fontreg phnvpout f17 color11 ">
                       <span id='OTPIncorrectSpan' class="disp-none fr color5 fontreg f14">Incorrect</span>
                    </div>
                 </div>
              </div>
    <p></p>
    <button id='matchOtpButton' style='font-weight:300;'  class="bg_pink cursp brdr-0 colrw f20 fontreg mt26 mb20 lh50 fullwid pl30 pr30">Submit</button>
  
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="bg_1 phnvp5">
          <div class="phnvwid3 mauto fontlig txtc">
              <p class="f15 opa80 cursp">You can also verify number by giving us a <a id='missedCallOption' class="color5 fontreg f17">Missed Call</a> </p>
               
            </div>        
        </div>        
        <!--end:div-->
        <!--start:div-->
        <div class="phnvbdr5 fontlig f13 opa60 txtc lh40">For assistance, contact customer care at <span id='js-TFNumberOTP'></span> or <a id='js-EmailOTP'  href="" target="_blank">help@jeevansathi.com</a>
        
        </div>

        <!--end:div-->
    </div>
   <!--end:layer 1-->
  </div>