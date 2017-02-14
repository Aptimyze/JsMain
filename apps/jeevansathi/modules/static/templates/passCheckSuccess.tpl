<div id="mainContent">
  <div class="loader" id="pageloader"></div>
  <div id="deleteProfilePasswordPage"> 
  
    <!--start:option-->

      <div id = "showDuringOTP" class='js-NumberedLayer'>
		<div id="overlayHead" class="bg1 txtc pad15">
 
		      <div class="posrel lh30">
        		<div class="fontthin f20 white">Your Password</div>
        		~if $deleteOption neq '1'`
        		<a href="/static/deleteReason?delete_option=~$deleteOption`"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
        		~else`
         		<a href="/static/deleteOption"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
         		~/if`
       		</div>
    		</div>
 
   <div class="bg4 f16 fontlig color13"> 
   		
        <!--start:input field-->
        <div style="padding:20%">
        	<input id="passValueID" type="password" placeholder="Enter Password" class="f20 fontthin color11 fullwid txtc">        
        </div>
        </div>
        <!--end:input field-->
         <!--start:OTP field-->

        <!--end:OTP field-->
        <!--start:submit button-->
        <div id="foot" class="posfix fullwid bg7 btmo">
			<input type="submit" id="passCheckID" class="fullwid dispbl lh50 txtc f16 white" value="Delete My Profile">
		</div>
        <!--end:submit button-->
        ~if $showOTP eq 'Y'`
       <div style="text-align: center;padding-top: 10px;"><a id="otpProfileDeletionJSMS" class="fontlig white f14 pb10 color16" style="color : #d9475c;"> Delete Using One Time Code</a>
    </div>
    ~/if`
~if ($deleteOption eq '1') || ($deleteOption eq '2') || ($deleteOption eq '3')`
    <div id="offerCheckBox" class="disp-none" style="padding: 25px 10% 0px 10%;">       
      <div class="fl">
        <li style="list-style: none;"><input id='offerConsentCB' type="checkbox" name="js-offerConsentCheckBox" checked="checked"></li>
      </div>
    <div class="fontlig pl20 f15 grey5  mt20 pr10" style="margin-left: 20px;">I authorize Jeevansathi to send Emails containing attractive offers related to the wedding</div>
    </div>
    </div>
   
~/if`    
    <!--end:option--> 
   
  </div>
</div>
<div id="deleteConfirmation-Layer" class ='dn' style="background-color: #09090b;">
  <div  class="posrel " style="padding:5% 0 8% 0;">

	<div class="br50p txtc" style='height:80px;'>
			
		</div>
		 
	</div>
		 
	<div class="txtc">	 
	<div class="fontlig white f18 pb10 color16">Delete Profile Permanently</div>
	<div class="pad1 lh25 fontlig f14" style='color:#cccccc;'>This will completely delete your profile information, contact history and active paid membership(s), if any. Are you sure about deleting your profile?</div>
  </div>
  <!--start:div-->
  <div style='padding: 25px 0 8% 0;'>
	<div id='deleteYesConfirmation' class="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick="deleteConfirmation('Y');">Yes, Delete Profile Permanently</div>
  </div>
  <!--end:div-->
  <div id='deleteNoConfirmation' onclick="deleteConfirmation('/static/deleteOption');" style='color:#cccccc; padding-top: 12%;' class="pdt15 pb10 txtc white f14" style="padding-top:15%;">Dismiss</div>
  </div>

<script>
    var delete_reason='~$deleteReason`';
    var delete_option='~$deleteOption`';
    var successFlow='~$successFlow`';
    </script>

<div id='otpWrongCodeLayer' class="otplayer dispnone">
    <div class="otpcenter cssLayerFix bg4 fontlig f18">
        <div class="txtc pt40">
            <i class="mainsp otpic1"></i>
        </div>
        <p class="color3 txtc pt10">OTP Verification Failed</p>
        <p class="color4 txtc pt10 pb30">Make sure you entered correct code.</p>
        <div class="otpbr2 txtc otplh60">
            <div id='js-okIncorrectOtp'  onclick='$("#otpWrongCodeLayer").hide();
            return true;' class="f19 otpcolr2 fontthin">Ok</div>
        </div>
    </div>
</div>

<div id='otpResendingLayer' class="otplayer dispnone">
    <div class="otpcenter cssLayerFix bg4 fontlig f18">
        <div class="txtc pt40">
            <img src="/images/jsms/commonImg/loader_card.gif">
        </div>
        <p class="color3 txtc pt40">Resending Verification Code</p>
        <p class="color4 txtc pt15 optp4">Wait for a moment while we send the code.</p>
        
    </div>
</div>

                    <!--start:div-->
        <div id = "bringSuccessLayerOnMobile" class='js-NumberedLayer'  style="overflow:auto;display:none;">

		<div id="overlayHead" class="bg1 txtc pad15">

			<div class="posrel lh30">
				<div class="fontthin f20 white">Delete Using OTP</div>
				<a onclick='$("#bringSuccessLayerOnMobile").hide();$("#showDuringOTP").show();'><i class="mainsp posabs set_arow1 set_pos1"></i></a>
			</div>
		</div>    
          
               <div id ="putPasswordLayer" class='js-NumberedLayer2'><div class=" txtc f14 fontlig pt30 pb15">
    <p>Profile Deletion code sent to +~$isd`-~$phoneNum` <span id='isdDiv'></span> <span id='mainPhone'></span></p>
    <div id = "hideOnTrialsOver">
    <p id='resendSMSDiv' class="pt5">Didn't receive code? <a id='resendTextId'  class="color2">Resend Code</a></p> </div>
    </div>

      <div class="bg4 otpma">
    
            <div class="pt20 pb20 otpwid1">
           
            <input id='matchOtpText' type ="tel" placeholder="Enter Code" autocomplete="off" class="f19 fontlig  fullwid txtc">
           
            </div>
   
            </div>
            <div id="buttonForCode" class="pt20">
                                <a id="mainBottomButton2" class=" js-NumberedLayer2 bg7  white lh30 fullwid dispbl txtc lh50 f19 fontlig">Delete Using OTP</a>
                        </div>
           
            </div>  


              
                    <!--end:div-->

         
        </div>
                     <div id ="attemptsOver" style='height:100%' class="bg4 otpma js-NumberedLayer js-NumberedLayer3 dispnone">
                    <div id="overlayHead" class="bg1 txtc pad15">

                        <div class="posrel lh30">
                        <div class="fontthin f20 white">Delete Using OTP</div>
                        <a onclick="$('.js-NumberedLayer').hide();$('#showDuringOTP').show();"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
                        </div>
                    </div>
                  <div class="txtc optp5 f18 fontlig">
                      <i class="mainsp otpic1 js-noTrials"></i>
                      <p id='oopsDiv' class="otpcolr1 pt20 js-noTrials"><strong>Oops! You have exhausted all your trials !</strong></p>
                      <p class="color13 otpp6 lh25">You have reached maximum number of attempts for Verification code.</p>
                  
                  </div>


             
            </div>
                    
