
<div class="ce_357">

<div class="fs16" >
  <p>~if $contactEngineObj->contactHandler->getContactInitiator() eq S`
    ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` has accepted your interest.
    ~else` 
    You have accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest. ~/if`</p>
</div>
<div class="sp5"></div>
<div class="flcet">
  <textarea class="w347CE h88CE" id="draft" name="draft" ></textarea>
</div>
</div>
<div class="sp12"></div>
<div style="text-align:center; top:  210px; left: 60px;"></div>
<div class="center">
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
<div class="fs16"></div>
<div class="sp50"></div>
<div class="sp50"></div>
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`

