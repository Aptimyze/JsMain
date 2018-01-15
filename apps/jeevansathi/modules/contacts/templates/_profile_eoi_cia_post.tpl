~if MobileCommon::isMobile() neq 1`
<div class="sp8"></div>
<div class="ce_357">
<div class="sp5"></div>
<div class="sp5"></div>
~if !$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
<div class="ico-right sprite-new fl">&nbsp;</div>
~/if`
<div class="fs15 fl w300 w80mob" style="margin-top:4px;">~Messages::getPostAcceptMessage($contactEngineObj)`.
~if $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI)`
<div class="sp5"></div>
To see Phone/Email of these members go to <a href="/profile/contacts_made_received.php?page=accept&filter=M">'People I Accepted'</a>`
~else`Go to <a href="/profile/contacts_made_received.php?page=accept&filter=M">'People I Accepted'</a>&nbsp;page~/if`
</div>
<div class="sp15"></div>
</div>
<div style="width:358px"></div>


~else`
<section>
		<div class="pgwrapper">
			<div class="js-content">
				<p>You have accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest, To send a Message to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`,</p> 
				<p class="clearfix">
				~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR,"CLASS"=>"pull-left btn pre-next-btn actived","Link"=>"Buy Paid Membership","style"=>"width:auto"])`
				</p>
			</div>
		</div> 
	</section>
	~/if`
