<div class="fs16 fl">
	<div class="ce_357">
		~if !$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
		<div class="ico-wrong sprite-new fl">&nbsp;</div>
		~/if`
		<div class="fs16">
			<p>~Messages::getPostDeclineMessage($contactEngineObj)`. </p>
			~if $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
<div class="sp5"></div>
If you want to further communicate with these members go to <a href="/profile/contacts_made_received.php?page=decline&filter=M">'People I Declined'</a>~/if`
		</div>
		<div class="sp15"></div>
			~include_partial("contacts/saveDrafts",[contactEngineObj=>$contactEngineObj])`
    </div>  
</div>
