
<div class="sp8"></div>
<div class="inner_div">

~include_partial("contacts/profile_locked_phoneEmail")`
~assign var = state value =   $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState()`
~assign var = ftostate value = $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState()`
~assign var = sender value =  $contactEngineObj->contactHandler->getContactInitiator()`

<div class="sp15"></div>
<div class="fs16">
  <p>In your 'Free Trial Offer' you can see contact <br />
    details of only <span class="redCE"><b> 
    ~if $state eq E5 || $ftostate eq ACTIVE`
    ~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getTotalAcceptLimit()`</b></span> accepted members. 
    ~elseif $state eq E3 AND $sender eq S`
    ~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getInBoundAcceptLimit()`</b></span> members who accepted you.
    ~elseif $state eq E4 AND $sender eq R`
    ~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getOutBoundAcceptLimit()`</b></span> members you accepted.
    ~/if`</p>
    <div class="sp15"></div>
    To Unlock Phone/Email of this member NOW,</div>
<div class="sp15"></div>
<p>
~Messages::getBuyPaidMembershipButton(["NAVIGATOR"=>$NAVIGATOR])`
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

</div>

~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`

