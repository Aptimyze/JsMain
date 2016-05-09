~if !$profileObj`
~assign var=profileObj value=$contactEngineObj->contactHandler->getViewer()`
~/if`
~assign var=state value = $profileObj->getPROFILE_STATE()->getFTOStates()->getSubState()`
~if $state eq D4`
<div class="quick2 fs14">
  <p>You are nearing the limit of the number 
    of interests you are allowed to send. <br />
  So, Express Interest selectively.</p>
</div>
~else`
<div class="sp15"></div>
<div class="sp5"></div>
<div class="fs16 ml_23" ><strong>Express Interest</strong> in more profiles soon. The offer expires on&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>
<div>
~Messages::getSuggestedMatchesLink([LINK=>"See Suggested Matches"])`
</div>

~/if`
