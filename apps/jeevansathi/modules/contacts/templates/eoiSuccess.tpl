~if $onlyInnerTpl || $errorTpl` 
~include_partial("contacts/~$contactEngineObj->getComponent()->innerTpl`",['contactEngineObj'=>$contactEngineObj])`
~else`
<table align="center" width="100%" height="100%" style="text-align: center;"><tbody><tr><td><img src="~$IMG_URL`/images/contactImages/loader_big.gif"><div class="sp10"></div><div class="fs15 b"></div></td></tr>
</tbody></table> 
<div style="display:none">
	
<div id="eoiData">
	~include_partial("contacts/~$contactEngineObj->getComponent()->innerTpl`",['contactEngineObj'=>$contactEngineObj])`
</div>	
</div>
<form id="eoiForm" name="eoiForm" action="/search/viewSimilarProfile?draft_name=~$contactEngineObj->contactHandler->getElements('DRAFT_NAME')`&type_of_con=~$contactEngineObj->contactHandler->getContactType()`&contact=~$contactEngineObj->contactHandler->getViewed()->getPROFILEID()`&SIM_USERNAME=~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`" method="POST">
<input type="hidden" name="MESSAGE" value="~$contactEngineObj->contactHandler->getElements('MESSAGE')`"></input>
<input type="hidden" name="profile_name" value="~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`"></input>
<input id=contactEngineConfirmation type="hidden" name="contactEngineConfirmation" value="">
~if $layerToShow`
<input id="layerToShow" type="hidden" name ="layerToShow" value='~$layerToShow`'>
<input id="contactType" type="hidden" name = contactType value=~$contactEngineObj->contactHandler->getToBeType()`>
~/if`
</form>

<script>
	$("#contactEngineConfirmation").val($("#eoiData").html())
~if $contactEngineObj->contactHandler->getPageSource() neq 'search'`
$("#eoiForm").attr("action",$("#eoiForm").attr("action")+"&"+$("#NAVI").val());
	document.eoiForm.submit();
~/if`
</script>

~/if`
