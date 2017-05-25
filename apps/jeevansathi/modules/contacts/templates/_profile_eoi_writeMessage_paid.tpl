<div class="ce_357">
<div class="js-content">
<div class="fs16" >~if $contactEngineObj->contactHandler->getContactInitiator() eq S`
    ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` has accepted your interest.
    ~else` 
    You have accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest. ~/if`
    ~if MobileCommon::isMobile() eq 1`
    Write a personalized message.
    ~/if`
    </div>
</div>
<div class="sp12"></div>
 ~if MobileCommon::isMobile() eq 1`
<div class="frm-container">
					<div class="row03">
						<div style="width:60%">
~else`
<div class="flce">
~/if`
~include_partial("contacts/messagedropdown",[drafts=>$contactEngineObj->getComponent()->drafts])`
~if MobileCommon::isMobile() eq 1`
</div>
</div >
~/if`
</div>
<div class="sp15"></div>
<div class="flcet">
<textarea class="w347CE h102CE" id="draft" name="draft">
</textarea></div>
<div class="sp15"></div>
<div  class="center">
 ~if MobileCommon::isMobile() eq 1`
<div class="frm-container">
	<div class="row04">
	~/if`
~Messages::getSendEmail(array(),CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
 ~if MobileCommon::isMobile() eq 1`
	</div>
</div>
~/if`
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
<br />
</div>
