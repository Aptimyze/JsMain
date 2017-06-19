	<!-- Sub Title -->
	<section class="s-info-bar">
		<div class="pgwrapper">
		~if $output eq 'true'`
			Confirmation
		~else`
			Error
		~/if`
		~if $NAVIGATOR`
			~$BREADCRUMB|decodevar`
		~else if $httpRef`
			<a href="~$httpRef`" class="pull-right btn pre-next-btn" style="width:auto">Go back</a>
		~else`
                        <a href="javascript:void(0)" onclick="goBack();" class="pull-right btn pre-next-btn" style="width:auto">Go back</a>
		~/if`
		</div>
	</section>
	
	<!-- Confirmation -->
	<section>
		<div class="pgwrapper">
			<div class="js-content">
				~if $executionMessage`
					<p>~$executionMessage`</p>
				~/if`
			</div>
		</div> 
	</section>
