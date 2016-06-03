<div class="ce_337 pd12">
<div  class="fs16">You already expressed interest in this member</div>
<div class="sp15"></div>
<input type="hidden" name="draft" id="draft" value="~ProfileDrafts::getMessage($contactEngineObj->getComponent()->reminderDrafts, '')`" />
<div class="fs16">To remind ~if $contactEngineObj->contactHandler->getViewed()->getGENDER() eq 'M'` 
                                him
                            ~else`
                                her
                            ~/if` ~Messages::getSendReminderButton([], CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>
<div class="sp5"></div>
<div class="sp15"></div>

<div class="fs16"> 
<p>Don't want to wait for ~$contactEngineObj->getComponent()->genderAddress` reply?    </p>
<p><strong>~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR])`</strong></p>
</div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15">
<p>&nbsp;</p>
</div>
<div class="sp5"></div>
</div>

~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`


<div class="sp8"></div>

