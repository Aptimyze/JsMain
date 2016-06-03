
<div class="ce_357">

<div class="fs16">You have cancelled further communication with<br>
~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`</div>

<div class="sp12"></div>
<div class="fs16">Still like this member? </div>
<div class="sp12"></div>
</div>
<div class="flce">
~include_partial("contacts/messagedropdown",['drafts'=>$contactEngineObj->getComponent()->drafts])`
</div>
<div class="sp15"></div>
<div class="flcet">
<textarea name="draft" id="draft" class="w347CE h102CE">~ProfileDrafts::getMessage($contactEngineObj->getComponent()->drafts,'')`
</textarea></div>
<div class="sp15"></div>
<div class="center"> 
~Messages::getExpressButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>
<br>


