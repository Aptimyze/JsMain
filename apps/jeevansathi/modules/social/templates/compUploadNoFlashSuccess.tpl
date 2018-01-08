<!-- start header -->
~include_partial('global/header')`
<!--end header -->
<!--Main container starts here-->
<!--pink strip starts here-->
<div id="main_cont">	

<!-- start search-->
<!--QUICK SEARCH STARTS-->
        <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
~include_partial('social_tabs')`

<div class="lf t12 b" style="width:650px;padding:5px; margin-left:15px;">

<div id = "direction_text">
	<div class="protop3 b fl">Select photos you want to upload<br />
	<p class = "clr_2"></p>
        <p style = "font-size:14px" class="phblw no_b">~sfConfig::get("app_photo_formats")` | upto ~sfConfig::get("app_max_photo_size")`MB | ~if $uploadPicCount eq 0`No ~else` ~$uploadPicCount` ~/if` more ~if $uploadPicCount eq 1` photo ~else` photos ~/if` allowed</p>
	</div>
</div>
<p class="clr"></p>
<p class="clr_4"></p>

<span id = "demo-error">
	<div class = "err_display">
		<div class="ylerror no_b" id = "upload_error4" style = "display:none; width:400px; height:34px">
			<div class="fl naukri_btnup9 sprteup" style="margin:0px 8px 0px 8px;"/></div>
			<span class = "error_display4 t13" style = "line-height:30px">		
				Please select photos to upload
			</span>
		</div>
	</div>
</span>

<form action = "~sfConfig::get('app_site_url')`/social/compUploadAction/nonFlash/~$echecksum`" method = "post" enctype = "multipart/form-data" id="form-demo-nonflash" style = "display:block" name = "non_flash_form">
	<div id = "demo-loader" style = "display:none">
                <br />
            	<span style = "font-size:19px" class = "no_b">Uploading photos in progress, please wait...</span><br /><br /><br />
                <br />
        </div>
        <div id="demo-status2">
		~assign var='kk' value=0`
		<div style = "display:block">
		~section name = file_tags loop = $uploadPicCount`
			~assign var='kk' value=$kk+1`
			<div>
                	<input type = "file" name = "photoupload~$kk`" onchange="check_file(this.value,this.id);" style = "position:absolute; z-index:2; opacity:0; height:30px; width:406px; filter:alpha(opacity=0)" ~if $fileTagSize` size = "45" ~else` size = "50" ~/if` id = "file~$kk`" />
                	<span class = "naukri_btnup14 sprteup" style = "margin-left: 332px; position:absolute; z-index:1"></span>
                	<input type = "text" style = "height: 29px; width: 322px" id = "text_box~$kk`" class = "disabled_text_box" disabled />
			</div>
			<br />
		~if $kk%5 eq 0 && $kk neq $uploadPicCount`
			<div style = "width:410px; background-color:#E9E9E9; font-size:15px; color:#007FFF" class = "no_b" id = "add~$kk/5`" onclick = "display_more(this.id)"><span style = "cursor:pointer">Add more</span></div><br />
		</div>
		<div style = "display:none" id = "list~$kk/5`">
		~/if`
		~/section`
		</div>
        </div>
	<div id = "upload_btn" style = "width:410px"><center><a href="#" class = "naukri_btnup5 sprteup" onclick = "check_for_upload();"></a></center></div>
</form>

<input type = "hidden" id = "totalTags" value = "~$uploadPicCount`" />

<p class="clr_18"></p>
 
<p class="clr"></p>
</div>
  <!--right part strat here-->
  <!--right part ends here-->
 <!--<p class=" clr_2"></p>
  <p class=" clr_18"></p>-->
 
  
<!--mid bottom content end -->
<!-- <p class=" clr_18"></p>-->
<!--tabbing  start -->

<!--tabbing  end -->
<p class=" clr_18"></p>
</div>

<!--Main container ends here-->	
<!--Footer starts here and same as the footer of seo community pages-->
<!--<p class="clr_4"></p>-->
~include_partial('global/footer')`
<!--Footer ends here and same as the footer of seo community pages-->
