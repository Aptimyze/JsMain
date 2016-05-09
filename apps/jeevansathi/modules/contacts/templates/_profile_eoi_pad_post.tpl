<div class="ce_357">
<div class="fs16" >You have declined this member, if you have <br />
  changed your mind, Accept</div>
<div class="sp12"></div>
</div>
<div class="flce">
~include_partial("contacts/messagedropdown",[drafts => $contactEngineObj->getComponent()->acceptdrafts])`
</div>
<div class="sp5"></div>
<div class="flcet">
<textarea name="draft" id="draft" class="w347CE h102CE" >~ProfileDrafts::getMessage($contactEngineObj->getComponent()->acceptdrafts,'')`
</textarea></div>
<div class="sp15"></div>
<div  class="center"> 
~Messages::getAcceptButton(array(),CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>
<div class="sp5"></div>
<br />

