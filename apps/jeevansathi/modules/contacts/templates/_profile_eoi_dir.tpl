<div class="ce_357">
<div class="fs16" >You already expressed interest in this member</div>

<div class="sp12"></div>
<div class="sp12"></div>
<div class="fs16">To remind ~if $contactEngineObj->contactHandler->getViewed()->getGENDER() eq 'M'`
                            him
                            ~else`
                            her
                            ~/if` ~Messages::getSendReminderButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>

<input type="hidden" name="draft" id="draft" value="~ProfileDrafts::getMessage($contactEngineObj->getComponent()->reminderDrafts, '')`" />
<div class="sp12"></div>
<div class="sp12"></div>
<div class="fl" >
<p class="fs16">
<span>~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`</span>
</p>
<p class="fs16">See Phone/Email of this member if ~$contactEngineObj->getComponent()->genderPronoun` accepts 
your interest</p>
</div>
</div>

<div class="sp12"></div>
<div class="wm_ce"></div>
~if MobileCommon::isMobile() neq "1"`
<div class="fs16" >
<p>~Messages::getLinkToShowDetail([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()),$contactEngineObj->contactHandler->getViewer()->getPROFILEID(),$contactEngineObj->contactHandler->getPageSource())` to get <br />
faster response</p>
</div>
<div class="sp12"></div>
~/if`
<div class="fs16">Hurry! Offer expires on&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong></div>
<div class="sp5"></div>
<div  class="fs14"><strong>~Messages::getFreeTrialOfferLink(["NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"CLASS"=>"b underline fs14","LINK"=>"Know more"])`</strong>

~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`

<div class="sp5"></div>
<br />
</div>
