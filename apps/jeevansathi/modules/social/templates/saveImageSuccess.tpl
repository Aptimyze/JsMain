~include_partial('global/header')`
<div id="main_cont">

<div id="container">
<!-- start search-->
<!--QUICK SEARCH STARTS-->
        <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
~include_partial('social_tabs')`
      
</div>

<!--profile details start-->

<div class="lf t12 b" style="width:650px;padding:5px; margin-right:6px;">

<div>

~if $noPhotosError neq 1` 
~if !$errorMsg`
<div class="protop4 no_b">Select your <strong style="color:#a82827;">Profile Picture* </strong>&amp; Add Title, Keywords to your Photos</div>
 <div style="font-size:14px; font-weight:normal; line-height:22px;">*Your <strong style = "color:#A82827">profile picture</strong> will appear in all communication with other members</div>
</div>
~/if`
~/if`

<p class = "clr_12"></p>

~if $errorMsg`
<div style="font-size:18px;font-weight:normal;margin-left:10px;" >
	Delete few photos to upload new photos.
	<p class="clr_2"></p>
</div>
<p class="clr_12"></p>
~/if`

~if $errorMsg2`
<div class="ylerror b" id = "error_block3" style = "width:700px">
     	<div class="fl naukri_btnup9 sprteup" style="margin:0px 10px 4px 10px;"/></div>
	~if $sizeErrCount || $formatErrCount`
	<span class = "error_display4">
	~else`
      	<span class = "error_display4" style = "line-height:34px">
	~/if`
		~if $sizeErrCount || $formatErrCount`
			<span class = "t13 mb_4" style = "display:block">
			~$actualPhotosUploaded` of ~$totalPhotosToUpload` photos ~if $importSite`imported.~else`uploaded. ~/if` 
			~if $sizeErrCount`
				~$sizeErrCount` ~if $sizeErrCount gt 1`photos are~else`photo is~/if` larger than ~sfConfig::get("app_max_photo_size")`MB in size. 
			~/if`
			~if $formatErrCount`
				~$formatErrCount` ~if $formatErrCount gt 1`photos are~else`photo is~/if` in invalid format.
			~/if`
			</span>
			<span class = "t13 mb_4" style = "display:block">
			~if $formatErrCount`
				We support these photo formats: ~$displayPicFormat`.
			~/if`
			~if $thresholdLimit` You have reached your limit of ~sfConfig::get("app_max_no_of_photos")` photos. ~else` <a href='~sfConfig::get("app_site_url")`/social/addPhotos'>Click here</a> to upload more photos. ~/if`
			</span>
		~else`
              		~$actualPhotosUploaded` of ~$totalPhotosToUpload` photos ~if $importSite`imported.~else`uploaded.~/if` 
           		~if $thresholdLimit` You have reached your limit of ~sfConfig::get("app_max_no_of_photos")` photos. ~else` <a href='~sfConfig::get("app_site_url")`/social/addPhotos'>Click here</a> to upload more photos. ~/if`
		~/if`
  	</span>
</div>
<p class="clr_12"></p>
~/if`

~if $noPhotosError && !$errorMsg && !$errorMsg2`
<div id="error_block" class="ylerror b" style="width: 700px">
	<div class="naukri_btnup9 sprteup fl" style="margin:0px 10px 4px 10px;"></div>
	<span style = "line-height:34px">
		You have not uploaded any photos yet. <a href='~sfConfig::get("app_site_url")`/social/addPhotos'>Click here </a> to add photos.
	</span>
</div>
<p class="clr_12"></p>
~/if`

~if $noPhotosError neq 1` 
<div align = "right" id = "save_submit_top" style = "width:710px"><a href="#" ~if $errorMsg`class="naukri_btnup7 sprteup" ~else` class="naukri_btnup10 sprteup" ~/if` onclick = "saveTags();">&nbsp;</a>
</div>
~/if`

<p class="clr_18"></p>

<div style="width:800px;">
<form name="list" >
<table width="800px" border="0" cellspacing="0" cellpadding="0">
~foreach from=$array10 item=link key=k`
~if $k %2 eq 0`
<tr> 
~/if`
<td valign = "top"><div id="val~$k`">
	<div class="fl" style="padding:2px; margin-right:6px;">
		<img src="~$link`"  height="96" width="96" style="border-style:solid;border-width:1px;border-color: #D3D3D3;"/><br>
	</div>
	<div class = "fl" style=" padding-right:12px;">
		<p class="no_b">Title</p>
		<p class = "spacing_4"></p>
		<input name="title_tag" type="text" style="width:200px;" value="~$titleArr[$k]`" maxlength="30" >
		<input type="hidden" name="picId_tag" value="~$picIdArr[$k]`" id = "pictureId"> </input>
		<input type="hidden" name="picType_tag" value="~$picType[$k]`"> </input>
		<p class = "spacing_4"></p>
		<p class="no_b">Keywords</p>
		<p class = "spacing_4"></p>
		<div style = "width:203px; z-index:1; display:block; position:absolute" onclick = "display_layer('keyword_layer~$k`');" onmouseover = "null" onmouseout = "hide_layer('keyword_layer~$k`');"><span style = "font-size:17px; display:block; zoom:1; opacity:0; filter:alpha(opacity=0); line-height:25px; cursor:default">HIDDEN TEXT IS HERE !!</span></div>
		<select name = "dropdown~$k`" id = "dropdown~$k`" style="width:200px; background-color:#FFFFFF" disabled>
			<option value = "" name = "" id = "dropdown~$k`value" selected />~$dropdownKeywordsLabel[$k]`
		</select>
		<div onmouseover = "display_layer('keyword_layer~$k`');" onmouseout = "hide_layer1('keyword_layer~$k`');">
			<div id = "keyword_layer~$k`" class = "no_b" style = "background-color: #FFFFFF; border: 1px solid #E2E2E2; width: 200px; padding: 5px 0px 5px 0px; display: none; z-index:1; position: absolute; height:auto">
				~foreach from=$keywords item=value key=kk`
					~assign var='keywd_index' value=$kk+1`
					<input type = "checkbox" id = "value~$kk`" value = "~$keywd_index`" ~if $keywordArrStr[$k]|contains:$keywd_index` checked ~/if` >~$value`<br />
				~/foreach`
			</div>
		</div>
		<p class = "spacing_4"></p>
		<input type = "hidden" id = "picture[~$k`]" name = "picture_tag" value="~$keywordArrStr[$k]`">
		<!--<p class ="no_b"><input name="profPic" type="radio" value="~$k`" id="profile~$k`" ~if 0 eq $k` checked ~/if` onclick = "change_link(this.value, '~$picIdArr[$k]`','~$picType[$k]`')"/> Select as Profile picture</p>-->
		<p class ="no_b"><input name="profPic" type="radio" value="~$k`" id="profile~$k`" ~if $k eq 0` checked ~/if` /> Select as Profile picture</p>
		<p class = "spacing_4"></p>
		<p class="no_b"><img src="~sfConfig::get('app_img_url')`/images/cross_icon.gif" />  
		<a href="~sfConfig::get('app_site_url')`/social/deleteLayer/~$picIdArr[$k]`/val~$k`/profile~$k`/~$picIdArr[0]`" onclick="checkProfPic('~$picIdArr[$k]`','delete~$k`','profile~$k`','~$picIdArr[0]`');" class="acol thickbox" id="delete~$k`" >Delete this photo</a>
		</p>
	</div>
</div>
<div id = "loaderImage~$k`" style = "display:none"><center><img src = "~sfConfig::get('app_img_url')`/images/loader_big.gif" style = "margin-right:200px" /></center></div>
	<p class="clr_18"></p>
	<p class="clr_18"></p>
</td>
~if $k %2 eq 1` 
</tr> 
~/if`
~/foreach`
</table>
</form>
</div>
</div>

<p class="clr_4"></p>

~if $noPhotosError neq 1`

<div><input type = "hidden" id = "allPhotoIds" value = "~$allPhotoIdsString`" /></div>
<div><input type = "hidden" id = "profilePic_Id" ~if $firstTime` value = "-1" ~else` value = "~$picIdArr[0]`" ~/if` /></div>

<div align="center" id = "save_submit" style = "width:750px"><a href="#" ~if $errorMsg`class="naukri_btnup7 sprteup" ~else` class="naukri_btnup10 sprteup" ~/if` onclick = "saveTags();">&nbsp;</a>
</div>
~/if`

</div>

<!--profile details end-->


 <!--right part strat here-->
<div class="lf" style="width:160px;">
        <p class=" clr_4"></p>
         <p class=" clr_12"></p>     
</div>
<!--right part ends here-->
	<p class=" clr_2"></p>
  	<p class=" clr_18"></p>

<!--mid bottom content end -->

<p class=" clr_18"></p>
<!--Main container ends here-->
</div>	
~if $pixelcode`
~$pixelcode|decodevar`
~/if`
~include_partial('global/footer')`
