~assign var=currentPageName value= $sf_request->getParameter('currentPageName')`
<script type="text/javascript">
currentPageName = "~$currentPageName`";
GAMapper("GA_PHONEVERIFICATION_PAGE");
var tollFreeINR='~$apiData.phoneDetails.TOLL_FREE_INR`';
var tollFreeNRI='~$apiData.phoneDetails.TOLL_FREE_NRI`';

var showDuplicateConsentMsg='~$showDuplicateConsentMsg`';

$(document).ready(function() {
  jsmsPhoneReady();
   if(typeof historyStoreObj != 'undefined'){
      historyStoreObj.push(onPhoneVerifyBack,"#mainLayer");
    }
});
function onPhoneVerifyBack(){
  if($('#selectNumberSetting1').length){
    window.location.href='/profile/mainmenu.php';
    return true;
  }
  return false;
}
</script>

<div id='otpResendingLayer' class="otplayer dispnone">
    <div class="otpcenter cssLayerFix bg4 fontlig f18">
        <div class="txtc pt40">
            <img src="/images/jsms/commonImg/loader_card.gif">
        </div>
        <p class="color3 txtc pt40">Resending Verification Code</p>
        <p class="color4 txtc pt15 optp4">Wait for a moment while we send the code.</p>
        
    </div>
</div>

<div class="txtc pad12 white fullwid f13 opaer1 posabs dispnone" id="validation_error" style="top: 55px;">Please provide a code.</div>

<div id='otpWrongCodeLayer' class="otplayer dispnone">
    <div class="otpcenter cssLayerFix bg4 fontlig f18">
        <div class="txtc pt40">
            <i class="mainsp otpic1"></i>
        </div>
        <p class="color3 txtc pt10">Phone Verification Failed</p>
        <p class="color4 txtc pt10 pb30">Make sure you entered correct code.</p>
        <div class="otpbr2 txtc otplh60">
            <div id='js-okIncorrectOtp'  onclick='$("#otpWrongCodeLayer").hide();return true;' class="f19 otpcolr2 fontthin">Ok</div>
        </div>
    </div>
</div>

<input type="hidden" id="fromReg" value="~$apiData.phoneDetails.fromReg`">
<input type="hidden" id="groupname" value="~$apiData.phoneDetails.groupname`">
<input type="hidden" id="sourcename" value="~$sourcename`">
<input type="hidden" id="mainPhoneNumber" value="~$apiData.phoneDetails.PHONE1`">
        <input type="hidden" id="isdNumber" value="~$apiData.phoneDetails.ISD`">
            <div class="fullwid rel_c1 outerdiv" id="mainScreen">
                
~include_partial("phone/jsmsVerificationAttempt",[phoneDetails=>$apiData.phoneDetails])`
~include_partial("phone/jsmsVerificationSuccessful",[phoneDetails=>$apiData.phoneDetails])`
~include_partial("phone/jsmsVerificationFailedScreen",[phoneDetails=>$apiData.phoneDetails])`



                <div id="selectNumberSetting1">
                    <!--start:div-->
                    <div class="fullwid bg1" id="p1">
                        <div class="pad1">
                            <div class="rem_pad1">
                                <div class="posrel txtc">
                                    <div class="white fontthin f19">Verify Number</div>
                                    <div class="posabs" style="left:-10px; top:0px;">
                                        <a href="/profile/logout.php" class="mrl10 white fontthin f14 js-NumberedLayer js-NumberedLayer1"  id="logout">Logout</a>
                                        <a id="backButton"  class="white fontthin dispnone js-NumberedLayer js-NumberedLayer2 js-NumberedLayer3 " style="font-size: 23px;"><i id="backBtn" class="mainsp arow2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:div-->
                      



                    <!--start:div-->
		    <div id = "p2"  style="overflow:auto;">
               
               <div class='dispnone js-NumberedLayer js-NumberedLayer2'><div class=" txtc f14 fontlig pt30 pb15">
    <p>Verification code sent to +<span id='isdDiv'></span> <span id='mainPhone'></span></p>
    <p id='resendSMSDiv' class="pt5">Didn't receive code? <a id='resendTextId' onclick='sendSMSAjax(); GAMapper("GA_PVS2_RESEND");' class="color2">Resend Code</a></p>
    </div>

      <div class="bg4 otpma">
    
            <div class="pt20 pb20 otpwid1">
           
            <input id='matchOtpText' type ="tel" placeholder="Enter Code" class="f19 fontlig  fullwid txtc">
           
            </div>
   
            </div>
            <div class="pt20">
                                <a id="mainBottomButton2" class="js-NumberedLayer js-NumberedLayer2 bg7  white lh30 fullwid dispbl txtc lh50 f19 fontlig">Verify this Number</a>
                        </div>
           
            </div>  


                 <div class='js-NumberedLayer js-NumberedLayer1'>
                    <div class="txtc f14 fontlig pt30 pb15"> Mention the number that you want to verify </div>
                    <!--end:div-->
                    <!--start:div-->
                    <div class="bg4">
                       <div class="pt20 pb20 txtc">
                          <a onclick="editScreen();return false;" id="switchNumber">
                            <div class="fl color1 f19 fontlig" style="width:90%">+
                            <span id="isd1"></span>-
                            <span id="mainPhone1"></span>
                        </div>
                        <div class="fl">
                            <div id='myjs-phoneEditIcon' class="icons1 edit"></div>
                        </div>
                        <div class="clr"></div>
                    </a>
                </div>
            </div>  <!--end:div-->
                    <!--start:div-->
                    <div class="pt15 txtc f15 fontlig">
                        <div class="color1">We will send verification code to this number</div>
                    </div>

                
		    ~include_partial("phone/jsmsVerificationFailed",[phoneDetails=>$apiData.phoneDetails])`
     ~if $showDuplicateConsentMsg eq 'Y'`<p class="color1 pt15 txtc f15 fontlig">By verifying the number ~$apiData.phoneDetails.PHONE1` against this profile, I acknowledge that the other profile(s) which I have created and verified on Jeevansathi with the same number are of person(s) different from the person represented in this profile</p>~/if`
       
     ~if $showConsentMsg eq 'Y'`<div class='color1 pt15 txtc f15 fontlig'>We would like to inform you that by verifying the above number you are agreeing to receive calls from the customer support team of Jeevansathi, even though your number is registered with the NCPR. 
<br /><br />Please note that you can change your preference from the ‘Alert Manager Settings’ page on the Desktop site any time.</div>~/if`
		


            </div>
                    <!--end:div-->

<div style='height:100%' class="bg4 otpma js-NumberedLayer js-NumberedLayer3 dispnone">
    
        <div class="txtc optp5 f18 fontlig">
            <i class="mainsp otpic1 js-noTrials dispnone"></i>
            <p class="otpcolr1 pt20 js-noTrials dispnone"><strong>Oops! Incorrect code.</strong></p>
            <p class="color13 otpp6 lh25">You have reached maximum number of attempts for Verification code. You can also <strong id='missedCallOption'>verify by giving us a missed call to <a id="call2" class='color2'></a></strong>.</p>
        
        </div>


   
  </div>
            </div>

                </div>
	    ~include_partial("phone/jsmsBottomButtons",[phoneDetails=>$apiData.phoneDetails])`
            </div>   
~include_partial("phone/jsmsEditNumberScreen")`

            <div id="mydiv" class='otplayer' style="display:none;">
                <img src="/images/jsms/commonImg/loader.gif" class="ajax-loader"/>
            </div>
~if $sourcename && $groupname`
~include_partial("global/gtm",['groupname'=>$groupname,'sourcename'=>$sourcename,'age'=>$loginProfile->getAGE(),'mtongue'=>$loginProfile->getMTONGUE(),'city'=>$loginProfile->getCITY_RES()])`
~/if`
~if $pixelcode`
~$pixelcode|decodevar`
~/if`
