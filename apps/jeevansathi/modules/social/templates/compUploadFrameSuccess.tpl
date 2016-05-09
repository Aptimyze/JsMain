<div style="font-size:20px; font-weight:normal; ~if $errorMsg` display:block; ~else` display:none; ~/if`">
	You have reached your limit of ~sfConfig::get("app_max_no_of_photos")` photos.
	<br><br>
	Please <a href="~sfConfig::get('app_site_url')`/social/saveImage?err=excessError" target = "_parent">delete some photos</a> to add more photos.
	<br><br>
</div>

<div style = "~if $errorMsg` display:none; ~else` display:block; ~/if`">

<div id = "direction_text">
	<div class="protop3 b fl">
		Select photos you want to upload<br />
		<p class = "clr_2"></p>
		<p style = "font-size:14px" class="phblw no_b">~sfConfig::get("app_photo_formats")` | upto ~sfConfig::get("app_max_photo_size")`MB | ~if $uploadPicCount eq 0`No ~else` ~$uploadPicCount` ~/if` more ~if $uploadPicCount eq 1` photo ~else` photos ~/if` allowed</p>
	</div>
</div>

<p class="clr"></p>
<p class="clr_4"></p>
<input type = "hidden" id = "currentPicCount" value = "~$currentPicCount`" />
<input type = "hidden" id = "maxFileSize" value = "~sfConfig::get('app_max_photo_size')`" />
<input type = "hidden" id = "actualUploadCount" value = "~$uploadPicCount`" />
<input type = "hidden" id = "totalFileList" value = "" />
<input type = "hidden" id = "totalLargeFiles" value = "" />

<p class="clr_4"></p>
<span id = "demo-error">
<div id = "err_disp">
	<div class="ylerror no_b" id = "upload_error" style = "display:none; height:34px">
		<div class="naukri_btnup9 sprteup fl" style="margin:0px 8px 0px 8px;"></div>
		<span id = "error_display1" style = "display:none" class = "t13 mb_4">
			You already have ~$currentPicCount` ~if $currentPicCount eq 1` photo ~else` photos ~/if`. Only first ~$uploadPicCount` ~if $uploadPicCount eq 1` photo ~else` photos ~/if` will be uploaded.
		</span>
		<span id = "error_display2" style = "display:none" class = "t13 mb_4">		
			Photos highlighted below in red will  not be uploaded.
		</span>
		<span id = "error_display4" style = "display:none" class = "t13">
			Please select photos to upload.	
		</span>
	</div>
</div>
</span>

<p class="clr_4"></p>
<p class="clr_4"></p>

<div style="background-color:#e0e0e0; height:40px; width:537px;" id="demo-status1">
<p style="float:left; padding:5px 0px 0px 5px;" id = "browse_btn"><a href="#" style="cursor:pointer" id = "demo-browse" class = "naukri_btnup6 sprteup"></a></p>
<p style="float:left; padding:5px 0px 0px 5px; display:none" id = "upload_btn"><a href="#" style="cursor:pointer" id = "demo-upload" class = "naukri_btnup5 sprteup"></a></p>
<p style="float:right;padding:10px 12px 0px 0px; display: none" class="no_b" ><span id="total_size">Total Size 0B</span></p>
</div>

<form action="~sfConfig::get('app_site_url')`/social/compUploadAction/flash/~$echecksum`" method="post" enctype="multipart/form-data" id="form-demo" name="zzz">
	<div id = "demo-loader" style = "display:none">
		<span><center>
			<span style = "font-size:20px">Uploading photos in progress, please wait...</span><br /><br />
			<div style="margin-right: 10px;" class="aypprg_br"><span id = "rect_loader" style="width: 0%;" class="aypbar aypsprite"></span></div><br />
			<span id = "uploadProgressIndicator" style = "display:none">
				<b><span id = "uploadFileNo"></span></b> of <b><span id = "totalFileToUpload"></span></b> images uploaded.
			</span>
		</center></span>
	</div>
	<div class="uptry" style = "display:none" id="upload-err">
		<div align="center" class="no_b uptry1">
			<br /><br /><br />
			Upload Failed !
			<div style = "margin-top:10px">
				<a href="~sfConfig::get('app_site_url')`/social/compUpload" style="font-size:17px;" class="b" target = "_parent"><u>Try again</u></a>
			</div>
		</div>
	</div>
	<div id="demo-status">
		<div>		
			<span id="demo-list-left"></span>
			<span id="demo-list-right"></span>
		</div>

		<p class = "clr"></p>
		<div id = "progress-display" style = "display: none">
		<div>
			<img src="~sfConfig::get('app_img_url')`/images/bar.gif" class="progress overall-progress" />
		</div>
		<div>
			<img src="~sfConfig::get('app_img_url')`/images/bar.gif" class="progress current-progress" />
		</div>
		<div class="current-text"></div>
		</div>
	</div>
</form>

<p class="clr_4"></p>
<p class="clr_4"></p>
<div id = "bottom-text">
<p style="font-size:13px;" class="no_b">*Uncheck box if you do not want to upload a particular photo.</p>
<p class="clr_4"></p>
<p class="clr_4"></p>
<p class="clr_4"></p>
<p style = "color:#2C7D9B"><a href = "~sfConfig::get('app_site_url')`/social/compUploadNoFlash" style = "color:#2C7D9B" target = "_parent" class = "t17"><u>Switch to basic photo uploader</u></a></p>
</div>
</div>
