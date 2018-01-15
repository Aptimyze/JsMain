<!-- Header end-->

<div id="mainpart">

		<section class="s-info-bar">

		<div class="pgwrapper">

			Confirmation

		</div>

	</section>

		<section>

		<div class="pgwrapper">

			<div class="js-content">

				~if $ERROR_MESSAGE`

	<p style="float:left;color:#000000">

	~$ERROR_MESSAGE`</p>

~else`



	~if $MESSAGE1`

	<p class="flt"><img src="~$IMG_URL`/profile/I/mobilejs/right-arrow.gif" />&nbsp;	~$MESSAGE1`<BR>~$MESSAGE2`</p>

	~/if`

~/if`

			</div>

		</div> 

	</section>

<!-- Header end-->

</div>
