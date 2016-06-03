<div class="profile-widget-container">
<script>
dp_type="";
dp_img_url="";
	
	dp_status="~$STATUS`";
	
	
	dp_removeText="";
	
	dp_disableAll="~$DISABLE_ALL`";
	
	dp_type="~$TYPE`";
	dp_viewprofile="~$VIEWPROFILE`";
	
	dp_allowAcceptDecline="~$ALLOW_ACCEPT_DECLINE`";
	
	dp_who="~$WHO`";
	
	dp_navigator="~$NAVIGATOR_LINK`";
	
	dp_imgurl="~$IMG_URL`";
	
	dp_tempContact="~$TEMP_CONTACT`";

	
	dpContactEngineError=0;
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
~Messages::setUserChecksum(CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getviewed()->getPROFILEID()))`
<div id="div_~CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getviewed()->getPROFILEID())`" class="ce_div" >
<form name=contact_engine method="POST">
~include_partial("contacts/formElements",['contactEngineObj'=>$contactEngineObj])`
~assign var=layerid value=CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getViewed()->getPROFILEID())`
~include_partial("contacts/~$contactEngineObj->getComponent()->innerTpl`",['contactEngineObj'=>$contactEngineObj,layerid=>$layerid])`

</form>
</div>
</div>

