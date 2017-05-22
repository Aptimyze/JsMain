<div class="ico-info"></div>
<div style="margin-left:5px" class="fs14">
~if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() eq "Y"`
Your Expression of interest will be deliverd once your profile is live.
~else`
Your Expression of interest will be delivered once your profile is complete.
~/if`
</div>

<div class="sp15"></div>

~if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() eq "Y"`
~if MobileCommon::isMobile()`
<div style="color:#505050; margin-left:5px" class="fs14">
~else`
<div style="color:#505050; margin-left:23px" class="fs14">
~/if`
We are happy you found a profile you like, we will notify you as soon as ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` responds. </div>
~else`
~if MobileCommon::isMobile()`
<div style="color:#505050; margin-left:5px" class="fs14">
~else`
<div style="color:#505050; margin-left:23px" class="fs14">
~/if`
To deliver your message~if FTOLiveFlags::IS_FTO_LIVE` and get the <span>~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`</span>~/if`, ~Messages::getCompleteYourProfileLink()`</div>
<div class="sp15"></div>
~if FTOLiveFlags::IS_FTO_LIVE`
~if MobileCommon::isMobile()`
<div style="color:#505050; margin-left:5px" class="fs14">
~else`
<div style="color:#505050; margin-left:23px" class="fs14">
~/if`
On activation of Free Trial Offer, you will be able to see the contact details of this user after <br>
  ~$contactEngineObj->getComponent()->genderPronoun` accepts your interest.
  </div>
  ~/if`
~/if`
