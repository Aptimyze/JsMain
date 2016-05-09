~if !$profileObj`
~assign var=profileObj value=$contactEngineObj->contactHandler->getViewer()`
~/if`
~assign var=state value = $profileObj->getPROFILE_STATE()->getFTOStates()->getSubState()`
~if $state eq D1 || $state eq D2`
<div class="sp15"></div>
<div class="sp5"></div>
<div class="fs16 ml_23" ><strong>Express Interest</strong> in more profiles soon. The offer expires on&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>
~elseif $state eq D4`
<div class="sp15"></div>
<div class="sp5"></div>
<div class="fs16" style="margin-left:23px;">Express Interest selectively to get more Acceptances.</div>

~else`
<div class="sp15"></div>
<div class="fs16" style="margin-left:23px">~Messages::getLinkToShowDetail([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()),$contactEngineObj->contactHandler->getViewer()->getPROFILEID())` to increase response to your Expressions of Interest.</div>
<div class="sp5"></div>

~/if`
