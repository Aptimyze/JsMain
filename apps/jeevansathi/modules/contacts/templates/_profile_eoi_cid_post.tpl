~if MobileCommon::isMobile() neq 1`
<div class="fs16 fl">
	<div class="ce_357">
		<div class="sp5"></div>
		<div class="sp5"></div>
		~if !$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
		<div class="ico-wrong sprite-new fl">&nbsp;</div>
		~/if`
		<div class="fs16">
			<p>~Messages::getPostDeclineMessage($contactEngineObj)`.</p>
			~if $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
<div class="sp5"></div>
If you want to further communicate with these members go to <a href="/profile/contacts_made_received.php?page=decline&filter=M">'People I Declined'</a>~/if`
		</div>
		<div class="sp15"></div>			
    </div>  
</div>
~else`
<section>
		<div class="pgwrapper">
			<div class="js-content">
				<p>You have declined the interest from ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` </p>
			</div>
		</div> 
	</section>
~/if`
