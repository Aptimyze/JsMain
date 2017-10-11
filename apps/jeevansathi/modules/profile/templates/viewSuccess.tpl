<div id="main_cont">
	<?php include_partial('global/header')?>
	<div id="container">
	<!-- start search-->
	<!--QUICK SEARCH STARTS-->
		<p class="clr_8"></p>
		<div id="topSearchBand"></div>
		<?php include_partial('global/sub_header')?>
		<p class="clr_8"></p>
		<br>
		<br>
		<b>Logged in profile</b><br>
		Email: ~$loggedInProfile->getEMAIL()`<br>
		Caste: ~$loggedInProfile->getCASTE()`<br>
		Mother tongue: ~$loggedInProfile->getMTONGUE()`<br>
		Raw Landline: ~$loggedInProfile->getPHONE_RES()`<br>
		Phone: ~$loggedInProfile->getPhoneNumber()`
		<br>
		<b>Other profile</b>
                Email: ~$profile->getEMAIL()`<br>
                Caste: ~$profile->getCASTE()`<br>
                Mother tongue: ~$profile->getMTONGUE()`<br>
                Raw Landline: ~$profile->getPHONE_RES()`<br>
                Phone: ~$profile->getPhoneNumber()`<br>
	</div>
	<?php include_partial('global/footer')?>
</div>
