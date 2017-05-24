<div class="ce_357">

<div class="fs16" >
  <p>~if $contactEngineObj->contactHandler->getContactInitiator() eq S`
    ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` has accepted your interest.
    ~else` 
    You have accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest. ~/if`</p>
</div>
<div class="sp5"></div>
<div class="flcet">
  <textarea class="w358CE h90CE textDis" id="draft" name="draft"  disabled></textarea>
</div>
<div class="sp12"></div>
<div  >
~assign var = state value =   $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState()`
~assign var = ftostate value = $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState()`

~assign var = sender value =  $contactEngineObj->contactHandler->getContactInitiator()`
  <p class="fs16">In your 'Free Trial Offer' you can send Emails to only <span class="redCE"><b>
    ~if $state eq E5 || $ftostate eq FTOStateTypes::FTO_ACTIVE`
    ~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getTotalAcceptLimit()`</b></span> accepted members. 
    ~elseif $state eq E3 AND $sender eq S`
    ~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getInBoundAcceptLimit()`</b></span> members who accepted you.
    ~elseif $state eq E4 AND $sender eq R`
    ~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getOutBoundAcceptLimit()`</b></span> members you accepted.
    ~/if`
    </p>
</div>
<div class="sp12"></div>
<div class="fs16">

</div>
<div style="text-align:center; top:  210px; left: 60px;"></div>

<div></div>
<div class="fs16">To send a Message to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`,</div>
<div class="sp5"></div>
<div  class="fs14"> ~Messages::getBuyPaidMembershipButton(["NAVIGATOR"=>$NAVIGATOR])`</div>
<div class="sp5"></div>
</div>	
<br />~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
