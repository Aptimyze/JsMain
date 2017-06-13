<!-- Sub Title -->
<section class="s-info-bar">
	<div class="pgwrapper">
	Confirmation
	~$BREADCRUMB|decodevar`
	</div>
</section>

<!-- Confirmation -->
<section>
	<div class="pgwrapper">
		<div class="js-content">
			<p>Your message has been sent successfully.</p>
		</div>
	</div> 
</section>
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
	
