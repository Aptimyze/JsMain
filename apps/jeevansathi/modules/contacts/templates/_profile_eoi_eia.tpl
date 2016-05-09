<div class="ce_357">

<div class="fs15" >
  <p>~Messages::getEOIMessage($contactEngineObj)`</p>
  <div class="sp12"></div>
   ~include_partial("contacts/AccNotButton",[contactEngineObj=>$contactEngineObj])`
</div>

<div class="sp12"></div>
<div class="fl" >
  <p class="fs15">~if $contactEngineObj->contactHandler->getToBeType() eq 'A'` Accepting ~else` Declining ~/if` ~if $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)` these profiles ~else` this profile ~/if` will send the following <br />
    message</p>
</div>
</div>
<div class="sp12"></div>
~if MobileCommon::isMobile() neq 1`
<div class="fs15" > <textarea class="textCE textDis" name="draft" id="draft" disabled="disabled" >~Messages::getAcceptMessage($contactEngineObj)`</textarea> </div>
~/if`
<div class="sp15"></div>
<div class="fs15">To edit the above message and include your <br />
  Phone Number/ Email Address,</div>
<div class="sp5"></div>
<div  class="fs15">~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR])`</div>
<div class="sp5"></div>
<br />
