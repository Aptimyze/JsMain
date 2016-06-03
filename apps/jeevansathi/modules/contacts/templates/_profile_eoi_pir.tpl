<div class="ce_357">

<div class="fs16">You have already expressed interest in this <br>
member, you can send a personalized message <br>
as a reminder </div>

<div class="sp12"></div>
</div>
<div class="flce">
~include_partial("contacts/messagedropdown",[drafts => $contactEngineObj->getComponent()->reminderDrafts])`
</div>
<div class="sp15"></div>
<div class="flcet">
<textarea name="draft" id="draft" class="w347CE h102CE">~ProfileDrafts::getMessage($contactEngineObj->getComponent()->reminderDrafts,'')`
</textarea></div>
<div class="sp15"></div>
<div class="center"> 
~Messages::getSendReminderButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
</div>
<div class="sp5"></div>
<br>

