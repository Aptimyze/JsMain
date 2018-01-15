	<!-- Sub Title -->
	<section class="s-info-bar">
		<div class="pgwrapper">
		~if $err neq ''`
			Photo Upload Error
		~elseif $uploadType eq 'photo'`
			Confirmation
		~elseif $uploadType eq 'mail'`
			Send your photos
		~/if`
		~if $pageName eq 'profile'`
		<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1" onclick="" class="pull-right btn pre-next-btn" style="width:auto">Go back</a>
		~elseif $pageName eq 'photoRequest'`
		<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=photo&filter=R" onclick="" class="pull-right btn pre-next-btn" style="width:auto">Go back</a>
		~/if`
		</div>
	</section>
	
	<!-- Confirmation -->
	<section>
		<div class="pgwrapper">
			<div class="js-content">
				~if $uploadType eq 'photo'`
					~if $err eq 'excessError'`
						<p class = "error-icon">&nbsp;</p>
						<p>Delete few photos to upload new photos.</p>
						<p>You can delete your existing photos from the desktop version of our site.</p>
					~elseif $err neq ''`
						<p class = "error-icon">&nbsp;</p>
						<p>
						~$actualPhotosUploaded` of ~$totalPhotosToUpload` ~if $totalPhotosToUpload gt 1`photos have~else`photo has~/if` been uploaded~if $actualPhotosUploaded gt 0`, ~else`. ~/if` ~if $actualPhotosUploaded gt 0` ~if $actualPhotosUploaded gt 1`they ~else`it ~/if`will be made live after screening.~/if`&nbsp;
						~if $sizeErrCount || $formatErrCount`
								~if $sizeErrCount`
                                					~$sizeErrCount` ~if $sizeErrCount gt 1`photos are~else`photo is~/if` larger than ~sfConfig::get("app_max_photo_size")`MB in size.&nbsp;
                        					~/if`
                        					~if $formatErrCount`
                                					~$formatErrCount` ~if $formatErrCount gt 1`photos are~else`photo is~/if` in invalid format.&nbsp;We support these photo formats: ~$displayPicFormat`.
                        					~/if`
						~/if`
						</p>
						~if $thresholdLimit` 
							~if $extraPhotosNotUploaded gt 0`
								<p>~$extraPhotosNotUploaded` ~if $extraPhotosNotUploaded gt 1`photos~else`photo~/if` couldn't be uploaded as you have reached the limit of ~sfConfig::get("app_max_no_of_photos")` photos</p>
							~else`
								<p>You have reached your limit of ~sfConfig::get("app_max_no_of_photos")` photos.</p>
							~/if`
						~/if`
					~else`
					<p>
						~if $uploadCount gt 1`
							Your photos have been uploaded, they will be made live after screening
						~else`
							Your photo has been uploaded, it will be made live after screening
						~/if`
					</p>
						~if $thresholdLimit` 
							~if $extraPhotosNotUploaded gt 0`
								<p>~$extraPhotosNotUploaded` ~if $extraPhotosNotUploaded gt 1`photos~else`photo~/if` couldn't be uploaded as you have reached the limit of ~sfConfig::get("app_max_no_of_photos")` photos</p>
							~else`
								<p>You have reached your limit of ~sfConfig::get("app_max_no_of_photos")` photos.</p>
							~/if`
                                                ~/if`
					~/if`
						~if $picsInAlbum gte 1`
							<p>You can view the photos in your album</p>
						~/if`
			
					~if $err neq 'excessError' && !$thresholdLimit`
					<div id = "uploadPhotoButton">
						<form name = "uploadPhotoForm" method = "post" enctype = "multipart/form-data" action="~sfConfig::get('app_site_url')`/social/mobileUploadAction/photo?page=~$pageName`">
							<input type = "file" name = "photoInput[]" id="photoInput" style = "position:absolute; z-index:2; opacity:0; height:41px; width:162px; filter:alpha(opacity=0); cursor:pointer;" size="0" multiple />
						</form>
						<a href="javascript:void(0)" class="pull-left btn pre-next-btn" style="width:auto; z-index:1; cursor:pointer;">~if $err`Try again~else`Upload more photos~/if`</a>
					</div>
					<div class = "hide" id = "uploadPhotoLoader">
                                               	&nbsp;&nbsp;&nbsp;<img src = "~sfConfig::get('app_img_url')`/images/searchImages/loader_small.gif" />
                                     	</div>
					~/if`
				~elseif $uploadType eq 'mail'`
					<p>For some technical reasons photo upload is not working on your mobile.</p>
					<p>Please send your photos to :</p>
					<div>
						<a href='mailto:~sfConfig::get("app_photo_email")`?subject=~$username`' class="pull-left btn pre-next-btn" style="width:auto;">~sfConfig::get("app_photo_email")`</a>
					</div>
					<p class = "clearfix"></p>
					<p>Please mention your username (~$username`) in the subject line of your mail</p>
				~/if`
			</div>
		</div> 
	</section>

<script type = "text/javascript">
	~if $uploadType eq 'photo' && $err neq 'excessError'`
		document.getElementById("photoInput").addEventListener('change', handlePhotoSelect, false);
	~/if`
</script>
