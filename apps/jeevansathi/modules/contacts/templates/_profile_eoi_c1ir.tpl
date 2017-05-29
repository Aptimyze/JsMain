<div class="ce_357">

<div class="fs16" >You cannot send 
~if $contactEngineObj->contactHandler->getViewed()->getGENDER() eq 'M'`
him 
~else`
her 
~/if`
a reminder as your profile does not have a photo  </div>

<div class="sp12"></div>
<div class="sp12"></div>
<div class="fs16">To send 
~if $contactEngineObj->contactHandler->getViewed()->getGENDER() eq 'M'`
him 
~else`
her 
~/if`
a reminder ~Messages::getUploadPhotoButton(["USERNAME" => $contactEngineObj->contactHandler->getViewer()->getUSERNAME()])`
</div>
<div class="sp12"></div>
<div class="fl" >
<p class="fs16">
~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`
</p>
<p class="fs16">See Phone/Email of this member if ~$contactEngineObj->getComponent()->genderPronoun` accepts 
your interest</p>
</div>
<div class="fs16">~Messages::getFreeTrialOfferLink(["CLASS" => "b underline", "LINK" => "Get Free Trial Offer"])`</div>
<div class="sp12"></div>
<div class="wm_ce"></div>

<div class="sp12"></div>
<div class="fs16">Hurry! Offer valid till&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
<br />
~Messages::getFreeTrialOfferLink(["NAVIGATOR" => $NAVIGATOR, "FROMPOST" => $FROMPOST, "CLASS" => "b underline fs14", "LINK" => "Know more"])`
</div>
<div class="sp15"></div>
<div class="sp5"></div>

~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
<div class="sp5"></div>
<br />
</div>
