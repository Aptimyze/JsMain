<div class="ce_357">

<div class="fs16 w300">Expressing interest in this profile will send the following message</div>

<div class="sp12"></div>
<div class="flce">
~include_partial("contacts/messagedropdown",[drafts => $contactEngineObj->getComponent()->eoiDrafts])`
</div>
<div class="sp15"></div>
<div class="flcet">
<textarea name="draft" id="draft" class="w347CE h102CE">~ProfileDrafts::getMessage($contactEngineObj->getComponent()->eoiDrafts,'')`
</textarea>
</div>
<div class="sp15"></div>
<div class="center"> 
~Messages::getExpressButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>
<div class="sp5"></div>
<br>
</div>
