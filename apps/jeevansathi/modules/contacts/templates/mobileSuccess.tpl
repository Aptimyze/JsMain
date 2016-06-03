
~if $messagelayerid`

        ~Messages::setUserChecksum($messagelayerid)`
        ~else`
        ~if $contactEngineObj`
~Messages::setUserChecksum(CommonFunction::createChecksumForProfile($contactEngineObj->contactHandler->getviewed()->getPROFILEID()))`
        ~/if`
~/if`

<section class="s-info-bar">
	<div class="pgwrapper">
	~if $contactEngineObj->contactHandler->getEngineType() neq "INFO"`
	Confirmation
	~else`
	Contact Details
	~/if`
	~$BREADCRUMB|decodevar`
	</div>
</section>
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
<div class="mob_div">
	<form name=contact_engine method="POST" ~if $PostActionUrl`action="/contacts/~$PostActionUrl`?~$NAVIGATOR`&nav_type=~$nav_type`&profilechecksum=~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::PROFILECHECKSUM)`"~/if`>
		~include_partial("contacts/formElements",['contactEngineObj'=>$contactEngineObj])`
		
~include_partial("contacts/~$contactEngineObj->getComponent()->innerTpl`",['contactEngineObj'=>$contactEngineObj,"NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"sType"=>$sType])`
</form>
</div>
<!-- Header end-->
<!-- Similar Profile-->
~if $contactEngineObj->getComponent()->innerTpl eq "profile_eoi_fni_post" or $contactEngineObj->getComponent()->innerTpl eq "profile_eoi_eni_post"`
    ~if $finalResultsArray`
        <section class="s-info-bar">
    			<div class="pgwrapper">
        			Similar people you can Express Interest in

    			</div>
		</section>

		<section>
                                <div class="js-content">
            				~include_partial("contacts/profile_eoiSuggestion",['finalResultsArray'=>$finalResultsArray,"NAVIGATOR"=>$NAVIGATOR,"stype"=>$sType])`

        			</div> 
    		</section>

    ~/if`
~/if`
<br>
<p class="clr"></p>  
