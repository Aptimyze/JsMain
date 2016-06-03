<div class="inner_div_detail">
<input type="hidden" name="draft" id="draft" value="~ProfileDrafts::getMessage($contactEngineObj->getComponent()->drafts,'')`" />

~include_partial("contacts/profile_locked_phoneEmail")`
<div class="sp15"></div>
~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`

~if $contactEngineObj->contactHandler->getPageSource() neq 'search'`
<div class="fs16">To see contact details
~Messages::getExpressButton()`</div>
<div class="sp15"></div>
~/if`
<div class="fs16">See Phone/ Email if ~$contactEngineObj->getComponent()->genderPronoun` accepts your interest <br />
  Hurry! Offer expires on&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong></div>
  <div>~Messages::getFreeTrialOfferLink(["NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"LINK"=>"Know more","CLASS"=>"b fs14 underline"])`</div>

<div class="sp5"></div>
<div class="fs16">or</div>
<div class="sp5"></div>
<div class="fs16">Don't want to wait for ~$contactEngineObj->getComponent()->genderAddress` reply? </div>~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR,"CLASS"=>"b fs16 underline"])`

</div>

<div class="sp8"></div>
<br />

