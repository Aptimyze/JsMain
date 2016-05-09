<section>
		<div class="pgwrapper">
			<div class="js-content">
				<p>
				~if $contactEngineObj->contactHandler->getToBeType() eq R`
				You have successfully sent reminder to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`. Write a personalized message. 
				~elseif $contactEngineObj->contactHandler->getToBeType() eq I`
				You have successfully expressed interest in ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`. Write a personalized message. 
				~elseif $contactEngineObj->contactHandler->getToBeType() eq A`
				You accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest. Write a personalized message.
				~/if`
				</p>
				<div class="frm-container">
					<div class="row03" style="width:360px;">
						<div style="width:60%;">
						~if $contactEngineObj->contactHandler->getToBeType() eq R`
							~include_partial("contacts/messagedropdown",[drafts => $contactEngineObj->getComponent()->reminderDrafts])`
						~elseif $contactEngineObj->contactHandler->getToBeType() eq I`
							~include_partial("contacts/messagedropdown",[drafts => $contactEngineObj->getComponent()->eoiDrafts])`
						~elseif $contactEngineObj->contactHandler->getToBeType() eq A`
							~include_partial("contacts/messagedropdown",[drafts => $contactEngineObj->getComponent()->acceptdrafts])`
						~/if`
							
						</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04">
						<div><textarea rows="10" name="draft" id="draft" >
						~if $contactEngineObj->contactHandler->getToBeType() eq R`
							~ProfileDrafts::getMessage($contactEngineObj->getComponent()->reminderDrafts,'')`
						~elseif $contactEngineObj->contactHandler->getToBeType() eq I`
							~ProfileDrafts::getMessage($contactEngineObj->getComponent()->eoiDrafts,'')`
						~elseif $contactEngineObj->contactHandler->getToBeType() eq A`
							~ProfileDrafts::getMessage($contactEngineObj->getComponent()->acceptdrafts,'')`
						~/if`
</textarea></div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04">
						<div>
						~if $contactEngineObj->contactHandler->getToBeType() neq A`
						~Messages::getSendEmailButton(["VALUE"=>"Send"], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
						~else`
						~Messages::getSendEmailButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
						~/if`
					</div>
				</div>
			</div>
		</div> 
	</section>
