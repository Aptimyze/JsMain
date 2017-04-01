<script>var savedNum='~$apiData.phoneDetails.PHONE1`';
var savedIsd='~$apiData.phoneDetails.ISD`';
var redirectUrlForPhoneModule=~if $requestUri`'~$requestUri`'~else`'/'~/if`;
var username='~$username`';
var disableBack='~$fromReg`';
var dialNumber='~$apiData.phoneDetails.DIAL_NUMBER`';
var tollFree_NRI='~$apiData.phoneDetails.TOLL_FREE_NRI`';
var tollFree_INR='~$apiData.phoneDetails.TOLL_FREE_INR`';
var showDuplicateConsentMsg='~$showDuplicateConsentMsg`';
</script>
~include_partial("global/gtm",['groupname'=>$groupname,'sourcename'=>$sourcename,'age'=>$loginProfile->getAGE(),'mtongue'=>$loginProfile->getMTONGUE(),'city'=>$loginProfile->getCITY_RES()])`
~if $sf_request->getAttribute('currency') eq 'RS'`~assign var='tollFree' value=CommonConstants::HELP_NUMBER_INR`~else`~assign var='tollFree' value=CommonConstants::HELP_NUMBER_NRI`~/if`

  <!--start:body-->
<link rel="stylesheet" async="true" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
~if isset($fromReg)`
<!--start:overlay-->
  <div class="hpoverlay z2 js-regOverlay disp-none"></div>
  <div class="pos_fix fullwid z3 reg1pos1 js-regOverlayMsg disp-none">
    <div class="wid36p mauto reg1bg1" >
    	<div class="padall-10">
        	<div class="txtr fontreg">
            	<i class="sprite2 reg1close cursp js-regOverlayClose"></i>
                <p class="txtc color2 f15 regMsgPad">You may access and update previous information after registration is complete</p>
            </div>
        </div>    
    </div>
  </div>
  <!--end:overlay-->
~/if`  
~include_partial('global/JSPC/_jspcCommonMemRegHeader')`
  <div class="bg_1">
    <div class="container mainwid fontlig">
      <ul class="listnone phnvcolor1 opa80 fontlig txtc pt30 pb48">
        <li id='phoneVericationForAutomation' class="f22">~if $fromReg eq 1`We are almost done!~else`Verify your number to proceed~/if`</li>
        <li class="f17 pt5">To let you connect with other members or for you to get contacted by them, we need to verify that this number belongs to you</li>
        <li class="f17 pt5">Just click the button below and follow the instructions - it will just take a few seconds</li>
      </ul>
      <div class="phnvp2 clearfix">
        <div class="color11 opa90 pt22 fl">Mobile number</div>
        <div class="pl20 fl" style='position:relative;'>
        <div class="wid500 txtl color5 f12" style='position:absolute; top:-14px;' id='phoneVerifyErr'></div>
          <div class="phnvbdr1 bg-white wid500 phnvp3">
            <div class="fullwid clearfix">
              <div class="fl phnvbdr2">
                <input type="text"  placeholder='ISD' id='isdMain' value="+~$apiData.phoneDetails.ISD`" class="bgnone brdr-0 phnvwid1 txtc f17 hgt50">
              </div>
              <div class="fl pl30">
                <input id='phoneNumberMain' phonetype='M' type="text" placeholder='Mobile Number' value="~$apiData.phoneDetails.PHONE1`" class="bgnone brdr-0 fontreg color11 phnvpout f17 hgt50">
              </div>
            </div>
          </div>
          <div class="clr"></div>
          <button id="verifyButton" style='font-weight:300;' class="cursp bg_pink brdr-0 colrw f20 fontreg mt40 lh41 pl30 pr30">Verify this number</button>
          



        </div>
        <div style="clear: both;">
          ~if $showDuplicateConsentMsg eq 'Y'`<p class="fontreg f13 color11 pt23 lh22">By verifying the number ~$apiData.phoneDetails.PHONE1` against this profile, I acknowledge that the other profile(s) which I have created and verified on Jeevansathi with the same number are of person(s) different from the person represented in this profile.</p>~/if`

          ~if $showConsentMsg eq 'Y'`<p class="fontreg f13 color11 pt23 lh22">We would like to inform you that by verifying the above number you are agreeing to receive calls from the customer support team of Jeevansathi, even though your number is registered with the NCPR. 
          <br /><br />Please note that you can change your preference from the ‘Alert Manager Settings’ page on the Desktop site any time.</p>~/if`
        </div>
      </div>
    	<div class="hgt102"></div>
       <div class="phnvwid2 mauto phncbdr3 txtc color11 opa60 lh40 f13">For assistance, contact customer care at ~$tollFree` or <a href="mailto:help@jeevansathi.com" target="_top">help@jeevansathi.com</a>
       
       
       </div> 
    
    
    </div>
  </div>
  <!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->
