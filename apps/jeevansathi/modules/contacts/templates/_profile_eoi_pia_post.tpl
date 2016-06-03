<div class="sp8"></div>
<div class="ce_357">
~if !$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
<div class="ico-right sprite-new fl">&nbsp;</div>
~/if`
<div class="fs15 fl w300 w80mob" style="margin-top:4px;">~Messages::getPostAcceptMessage($contactEngineObj)`.

~if $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
<div class ="sp5"></div>
To see Phone/Email of these members go to <a href="/profile/contacts_made_received.php?page=accept&filter=M">'People I accepted'</a>`
~else`Go to <a href="/profile/contacts_made_received.php?page=accept&filter=M">'People I Accepted'</a>&nbsp;page~/if`
</div>
<div class="sp15"></div>
~include_partial("contacts/saveDrafts",[contactEngineObj=>$contactEngineObj])`
</div>
<div style="width:358px"></div>
