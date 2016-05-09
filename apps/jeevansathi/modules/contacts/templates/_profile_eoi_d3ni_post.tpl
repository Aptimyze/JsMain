~include_partial("contacts/eoiCongratulation",['contactEngineObj'=>$contactEngineObj])`

<div class="fs16" style="margin-left:23px" >
~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`
</div>
<div class="sp5"></div>
<div style="color:#505050; margin-left:23px" class="fs14">
 You will be able to see the contact details of this user after ~$contactEngineObj->getComponent()->genderPronoun` accepts your interest during the free trial period.
</div>
~if MobileCommon::isMobile() eq 1`
~include_partial("contacts/mobileDstateConfirmation",['contactEngineObj'=>$contactEngineObj])`
~else`
~include_partial("contacts/webDstateConfirmation",['contactEngineObj'=>$contactEngineObj])`
~/if`


