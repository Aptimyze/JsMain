~if $userTimedOutError`

<script type = "text/javascript">
parent.closeIframeAjaxError();
</script>

~else`

~if $layerContent eq 0`

<div style = "width:395px; height:135px;" class="pink">
	<div class="topbg_new">
	<div class="lf pd b t12">My Photos: Edit/Crop  Photo</div>
	<div class="rf pd b t12"><a href="#" class="blink" onclick = "exitLayer();">Close [x]</a></div>
	</div>
       <div class=" t14 b" style="padding-top:34px;" align="center">Please Select one picture as your profile picture<br />
</div>

~else if $layerContent eq 1` 

<!--<body onload = "cropper();">-->
<div style="width:695px; height:435px;" class="pink">
	<div class="topbg_new">
		<div class="lf pd b t12">My Photos: Edit/Crop  Photo</div>
		<div class="rf pd b t12"><a href="#" class="blink" ~if $outputDisplayContent eq 1` onclick = "exitLayer();" ~else if $outputDisplayContent eq 2` onclick = "exitLayer1();" ~/if`>Close [x]</a></div>
	</div>
	<div id = "profile_layer_data">
		<div style="display: block;" id="saved1" class="green_verify b mar_top_6">Create a profile picture. Drag the frame to adjust.</div>
		<div class="pink_photo_tip">
			<div class="lf">
				<span class="b">Actual Photo</span>
				<div class="big_img_load_new mar_top_4" id="testWrap">
					<img src="~$canvasPicUrl`" alt="test image" id="testImage" />
				</div>

			</div>
			<div class="rf" style="width:305px;">
				<div class="actual_size lf" style="margin-right:10px;" align="center">
					<span class="b">Profile Photo</span><br>
					<div id="previewArea" class = "mar_top_4"></div>
				</div><br />

				<div class="rf tip_block_new t11 mar_top_6"><img src="~sfConfig::get('app_img_url')`/images/crop_ph_img_small-new.gif" />
					<div><br /></div>
					<div class="b mar_top_10">Note</div>
					<p>This photo is shown on your profile page for other users</p>
				</div>
				<div class="clear"></div>
				<div><br /><br /></div>
			</div>
		</div>
		<p style="clear:both;"></p>

		<div id="results">
			<input type="hidden" name="x1" id="x1" />
			<input type="hidden" name="y1" id="y1" />
			<input type="hidden" name="x2" id="x2" />
			<input type="hidden" name="y2" id="y2" />
			<input type="hidden" name="width" id="width" />
			<input type="hidden" name="height" id="height" />
		</div>	

		<div style="text-align:center;width:100%; padding-top:10px;">
			<input type="button" class="b green_btn" value="Save &amp; Next" style="width:90px;" onclick = "saveProfilePic('~$picId`','profile','no');">
			&nbsp;&nbsp;&nbsp;
			~if $outputDisplayContent eq 1`
			<a href="~sfConfig::get('app_site_url')`/social/addPhotos" style=" color:#127da9; font-size:15px;text-decoration:none;" class="b" target = "_parent" onclick = "skipLayer();">Skip</a>
			~else if $outputDisplayContent eq 2`
			<a href="~sfConfig::get('app_site_url')`/social/viewAllPhotos/none" style=" color:#127da9; font-size:15px;text-decoration:none;" class="b" target = "_parent" onclick = "skipLayer();">Skip</a>
			~/if`
		</div>
		<div class="sp8"></div>
	</div>

<!-- *****************************************THUMBNAIL*********************************************** -->

<div id = "thumbnail_layer_data" style = "display:none">
<div style="display: block;" id="saved3" class="green_verify b mar_top_6">Create a thumbnail. Drag the frame to adjust.</div>
<div class="pink_photo_tip">
<div class="lf">
<span class="b">Actual Photo</span>
<div class="big_img_load_new mar_top_4" id="testWrap">
<img src="~$canvasPicUrl`" alt="test image" id="testImage" />
</div>

</div>
<div class="rf" style="width:300px;">
<div class="actual_size lf" style="margin-right:10px;" align="center">
<span class="b">Thumbnail</span><br>
<div id="previewArea" class = "mar_top_4"></div>
<!--<img src="images/crop_ph_img_small.jpg" />-->
</div><br />

<div class="rf tip_block_new t11 mar_top_6"><img src="~sfConfig::get('app_img_url')`/images/thumb_page_img.jpg" />
<div><br /></div>
<div class="b mar_top_10">Note</div>
<p>
This photo is shown in my jeevansathi and mailers sent to members of Jeevansathi</p>

</div>
<div class="clear">

</div>
<div><br />
<br />
</div>

</div>

</div>
<p style="clear:both;"></p>

<div id="results">
	<input type="hidden" name="x1" id="x1" />
	<input type="hidden" name="y1" id="y1" />
	<input type="hidden" name="x2" id="x2" />
	<input type="hidden" name="y2" id="y2" />
	<input type="hidden" name="width" id="width" />
	<input type="hidden" name="height" id="height" />
</div>

<div style="text-align:center;width:100%; padding-top:10px;">
  <input type="button" class="b green_btn" value="Save" style="width:70px;" ~if $outputDisplayContent eq 1` onclick = "saveProfilePic('~$picId`','thumbnail','save')" ~else if $outputDisplayContent eq 2` onclick = "saveProfilePic('~$picId`','thumbnail','view')" ~/if` />&nbsp; </div>
		<div class="sp8"></div>
</div>

<!--*****************************************LOADER***********************************************************-->
<div id = "loader_layer_data" style = "display:none">
<table width = "690" height = "420" align = "center">
<tr>
<td valign = "center">
<center><img src = "~sfConfig::get('app_img_url')`/images/loader_big.gif" /><br /><br />Please Wait</center>
</td>
</tr>
</table>
</div>
</div>
<!--</body>-->

<script type = "text/javascript">
window.onload = function(){ 
	cropper();
}
</script>
~/if`
~/if`
