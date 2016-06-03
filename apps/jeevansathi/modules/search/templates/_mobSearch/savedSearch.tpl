  <!--start:saved search listing-->
<script>
	var manage=1;	
	
</script>
~foreach from=$savedSearches item=value key=kk`
  <div class="brdr1 savedSearch" id="~$value->ID`">
    <div class="pad18">
      <div class="fl wid94p srfrm_wrap">
	<div class="f14 savsrc-colr1">~$value->SEARCH_NAME`</div>
	<div id="~$value->ID`" class="color8 f16 pt10 savsrc-list savedSearchList" data="~$value->dataString`">
		
	 </div>
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
~/foreach`
 <!--end:saved search listing-->          
 <div class="brdr1">
     <div class="pad18 f14 savsrc-colr1 txtc">
	     ~if $maxSaveSearches eq '1'` Saved searches limit reached, tap ~else` Tap~/if` on 'Manage' to delete
     </div>
  </div>
