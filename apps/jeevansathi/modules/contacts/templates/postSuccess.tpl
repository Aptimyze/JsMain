~if $onlyInnerTpl`~include_partial("contacts/~$contactEngineObj->getComponent()->innerTpl`",['contactEngineObj'=>$contactEngineObj])`
~else`
~if $messagelayerid`
	
	~Messages::setUserChecksum($messagelayerid)`
	~else`
	~if $contactEngineObj`
~Messages::setUserChecksum(CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getviewed()->getPROFILEID()))`
	~/if`
~/if`
~if $Layer || $allow eq 1`
<div ~if $FROM_SEARCH || $contactEngineObj->contactHandler->getPageSource() eq 'VSM'` class="divlinks fl ce_360"~/if` style="display:block">
	
~if $FROM_SEARCH || $contactEngineObj->contactHandler->getPageSource() eq 'VSM'`
<div id="div_~Messages::getUserChecksum()`">
~/if`
<form name=contact_engine>
	~include_partial("contacts/formElements",['contactEngineObj'=>$contactEngineObj])`
~/if` 
~if $contactEngineObj->contactHandler->getContactObj()->getTYPE()` 
		<script>contactType='~if $contactEngineObj->contactHandler->getContactInitiator() eq "S"`R~/if`~$contactEngineObj->contactHandler->getContactObj()->getTYPE()`';</script>
~/if`

~include_partial("contacts/~$contactEngineObj->getComponent()->innerTpl`",['contactEngineObj'=>$contactEngineObj])`
~if $Layer || $allow`
<script type="text/javascript">

	var pattern1 = /\#n\#/g;
	if(typeof(MES)=='undefined')
		var MES = new Array();
	if(typeof(acceptDrop)=='undefined')
		var acceptDrop=new Array();
	if(typeof(declineDrop)=='undefined')
		var declineDrop=new Array();
	
	var i=0;
	~assign var="draft" value=$contactEngineObj->getComponent()->drafts`
	~foreach from=$draft item=message key=id`
	 temp="~$draft[$id][1]`";
		MES['~$draft[$id][2]`']=temp.replace(pattern1,"\n");
	~/foreach`
	~assign var="draft" value=$contactEngineObj->getComponent()->acceptdrafts`
	~foreach from=$draft item=message key=id`
	 temp="~$draft[$id][1]`";
	 acceptDrop['~$draft[$id][2]`']="~$draft[$id][0]`";
		MES['~$draft[$id][2]`']=temp.replace(pattern1,"\n");
	~/foreach`
	~assign var="draft" value=$contactEngineObj->getComponent()->declinedrafts`
	~foreach from=$draft item=message key=id`
	 temp="~$draft[$id][1]`";
	 declineDrop['~$draft[$id][2]`']="~$draft[$id][0]`";
		MES['~$draft[$id][2]`']=temp.replace(pattern1,"\n");
	~/foreach`	
</script>
</form>
~if $FROM_SEARCH || $contactEngineObj->contactHandler->getPageSource() eq 'search' || $contactEngineObj->contactHandler->getPageSource() eq 'VSM'`

<div class="separator fl width100"></div>
<div class="fr b"><a href="#"  onclick="return CloseLayer(postData,'view_contact_~Messages::getUserChecksum()`',event)">Close [x]</a></div>
~if $FROM_SEARCH`
</div>
~/if`
~/if`
</div>
~/if`
~/if`
