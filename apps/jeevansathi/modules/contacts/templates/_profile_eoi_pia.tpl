<div class="ce_357">

<div class="fs16 w300" >~Messages::getEOIMessage($contactEngineObj)` Please respond</div>

<div class="sp12"></div>
~if $contactEngineObj->contactHandler->getPageSource() eq 'VDP'`
	<div class="fl fs16"><input name="status" value = "accept" type="radio" checked onclick="changeRadio('interest')"/> 
	  Accept <i style="margin-left:55px">&nbsp;</i><input name="status" value = "notinterest" type="radio" onclick="changeRadio('notinterest')" /> 
	  Not Interested</div>
~else`	
	~if $contactEngineObj->contactHandler->getToBeType() eq A`
	<script>
		$("#notinterest").css("display","none");
	</script>
	~else`
	<script>
		$("#interest").css("display","none");
	</script>	
	~/if`

  ~/if`
<div class="sp15"></div>
<div class="flce">
~include_partial("contacts/messagedropdown",[drafts=>$contactEngineObj->getComponent()->drafts])`
</div><div class="sp12"></div>
<textarea name="draft" id="draft" class="w347CE h102CE" >~if $contactEngineObj->contactHandler->getToBeType() eq 'A' || $contactEngineObj->contactHandler->getToBeType() eq ''`~ProfileDrafts::getMessage($contactEngineObj->getComponent()->acceptdrafts,'')`~else`~ProfileDrafts::getMessage($contactEngineObj->getComponent()->declinedrafts,'')`~/if`

</textarea></div>
<div class="sp15"></div>
<div  class="center" id="interest" ~if $contactEngineObj->contactHandler->getToBeType() neq 'A'  && $contactEngineObj->contactHandler->getToBeType() neq ''` style="display:none"~/if`> 
~Messages::getAcceptButton([VALUE=>"Accept Interest",CLASS=>"fto-btn-green sprite-new cp"],CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>

<div  class="center" id="notinterest" ~if $contactEngineObj->contactHandler->getToBeType() eq 'A'` style="display:none"~/if`> 
~Messages::getNotInterestedButton([CLASS=>"fto-btn-green sprite-new cp"],CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID()))`
</div>

<div class="sp5"></div>
<br />

~if $contactEngineObj->contactHandler->getPageSource() eq 'VDP'`
	<script>
$(document).ready(function() {
			if($.browser.mozilla) $("form").attr("autocomplete", "off"); 	
});
$("#notinterest").css("display","");
$("#interest").css("display","");
	if ($("input[name='status']:checked").val() == 'accept')
		$("#notinterest").css("display","none");
	else
		$("#interest").css("display","none");
	</script>
~/if`
