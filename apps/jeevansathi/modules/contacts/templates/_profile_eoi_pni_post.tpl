~include_partial("contacts/eoiCongratulation",['contactEngineObj'=>$contactEngineObj])`
<div class="sp2"></div>
<div  class="fs14 c50 ml_23">We are happy you found a profile you like, we will notify you as soon as ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` responds.<br>
  You can see contact details of this member after ~$contactEngineObj->getComponent()->genderPronoun` accepts your interest.</div>
<div class="sp15"></div>
<div class="sp5"></div>
</span>
</p>
~if MobileCommon::isMobile()`
~Messages::getSuggestedMatchesLink([LINK=>"See Suggested Matches"])`
~/if`
