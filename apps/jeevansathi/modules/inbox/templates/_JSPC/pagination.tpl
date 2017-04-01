<div id="ccPaginationDiv" class="disp-none">
	<!--start:control-->
	<div class="fr"> 
		<i class="disp_ib sprite2 pagprv cursp" id="ccPaginationPrev"></i>
	  <i class="disp_ib sprite2 pgnxt cursp" id="ccPaginationNext"></i>
	</div>
	<!--end:control-->   
	<!--start:count-->
	<div class="disp-none" id="ccPaginationCountStructure">
		<div id="pageCountBlock" data={currentPageNo} class={activeClass}></div>
		<span class="disp_ib pr5">{startPageNo}-{endPageNo}</span><span class="opa50">of {totalCount}</span>   
	</div>  
	<!--next:count-->
	<div id="ccPaginationCountDiv" class="fr fontlig f15 color11 pt5 pr10 ~if $hidePaginationCount eq 1` disp-none ~/if`">
	</div>
</div> 
