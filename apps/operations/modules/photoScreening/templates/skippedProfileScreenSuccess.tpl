~include_partial('global/header')`

        ~if $preprocessing eq 1`
	<div align="center">
		<b>
			Photos are still under pre-processing and will be available in a while.
		</b>
		<br><br><br>
		<b>
		Click on back button of browser or <a href = "~JsConstants::$siteUrl`/jsadmin/view_skipped_profiles.php?cid=~$cid`&name=~$username`&val=~$val`&from=~$from`">Go Back</a>
		</b>
	</div>
        ~/if`
~include_partial('global/footer')`
