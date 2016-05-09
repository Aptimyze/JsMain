~include_partial("contacts/eoiCongratulation",['contactEngineObj'=>$contactEngineObj])`
<div class="sp2"></div>
<div class="fs16 ml_23" >
~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`
</div>
<div class="sp5"></div>
<div  class="fs14 c50 ml_23">
You will be able to see the contact details of this user after
~$contactEngineObj->getComponent()->genderPronoun` accepts your interest during the free trial period.
</div>
~if MobileCommon::isMobile() eq 1`
<div>
~Messages::getSuggestedMatchesLink([LINK=>"See Suggested Matches"])`
</div>
~/if`
