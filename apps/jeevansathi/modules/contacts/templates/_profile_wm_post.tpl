 ~if MobileCommon::isMobile() neq 1`
<div class="sp8"></div>
<div class="ce_357" style="height:256px;">

<div class="ico-right sprite-new fl">&nbsp;</div>
<div class="fs15">Your message was sent successfully to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`. 
</div>	
~if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID() eq 1` 
<div class="sp15"></div>
~include_partial("contacts/saveDrafts",[contactEngineObj=>$contactEngineObj])`
~/if`
</div>
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
~else`


<section>
	<div class="pgwrapper">
		<div class="js-content">
			<p>Your message has been sent successfully.</p>
		</div>
	</div> 
</section>
~/if`
