<div class="ce_357">

<div class="fs16" >
  <p>~if $contactEngineObj->contactHandler->getContactInitiator() eq S`
    ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` has accepted your interest.
    ~else` 
    You have accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest. ~/if`</p>
</div>

<div class="sp12"></div>
<textarea class="w342CE h90CE textDis" id="draft" name="draft"  disabled></textarea>

</div><div class="sp15"></div>
<div class="fs16">To send a Message to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`,</div>
<div class="sp5"></div>
<div  class="fs16"> 
  <p> ~Messages::getBuyPaidMembershipButton(["NAVIGATOR"=>$NAVIGATOR])` </p>
  <p>&nbsp;</p>
  <div class="sp50"></div>

</div>
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`

