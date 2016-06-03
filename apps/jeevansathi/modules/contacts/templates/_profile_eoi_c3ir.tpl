<div class="ce_357">

<div class="fs16">You already expressed interest in this member</div>

<div class="sp12"></div>
<div class="sp12"></div>
<div class="fs16">To remind 
~if $contactEngineObj->contactHandler->getViewed()->getGENDER() eq 'M'`
him
~else`
her
~/if`
~Messages::getSendReminderButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>
<input type="hidden" name="draft" id="draft" value="~ProfileDrafts::getMessage($contactEngineObj->getComponent()->reminderDrafts, '')`" />
<div class="sp12"></div>
<div class="fl">
<p class="fs16">
~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`
</p>
<p class="fs16">See Phone/Email of this member if ~$contactEngineObj->getComponent()->genderPronoun` accepts 
your interest</p>
</div>


<div class="sp12"></div>
<div class="sp12"></div>

<div class="wm_ce"></div>

<div class="fs16">
<p>To avail this offer,   </p>
<p>~Messages::getVerifyPhoneLink()`</p>
</div>
<div class="sp12"></div>
<div class="fs16">Hurry! Offer valid till&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>
<div class="sp5"></div>
<div class="fs14">~Messages::getFreeTrialOfferLink(["NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"CLASS"=>"b underline fs14","LINK"=>"Know more"])`
</div>
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
<div class="sp5"></div>
<br>
</div>
