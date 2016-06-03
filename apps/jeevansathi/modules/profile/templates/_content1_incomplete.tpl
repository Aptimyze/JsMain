
 <!--profile pic starts -->       
  <div class="pro_tupn pro_tup1n1" style="width:914px">
      <div>
      <div class="fl" style="padding-left:6px; margin-right:12px;">
          ~if $PHOTO eq ""`
 <div>
 ~if $GENDER eq "M"`

	~if !$crmback`<a  href="/social/addPhotos"~/if`><img src="~sfConfig::get('app_img_url')`/images/upload_photo_male.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">~if !$crmback`</a>~/if`
~else`
	~if !$crmback`<a  href="/social/addPhotos">~/if`<img src="~sfConfig::get('app_img_url')`/images/upload_photo_female.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">~if !$crmback`</a>~/if`
~/if`
</div>
      <br />
      <br />
~else`
<div>~if !$crmback`<a  href="/social/addPhotos">~/if`<img src="~$PHOTO`" width="150" height="200" GALLERYIMG="NO" border="1" oncontextmenu="return false;">~if !$crmback`</a>~/if`</div>
			<div class="sp5"></div>
			<div>
			<div class="btn-folder fl">&nbsp;</div>
			<a class="fs14" href="/social/viewAllPhotos/none">Photos (~$NO_OF_PHOTOS`)</a>
			</div>
~/if`
      </div>
	  <div class="fl">
	  	<div class="maroon" style="font-size:22px">
		<div class="ico-info">&nbsp;</div>
		Your Profile is incomplete</div>
		<div class="sp5"></div>
		<div style="font-size:18px">Your profile is not visible to other members as it is incomplete. </div>

		<div class="sp10">&nbsp;</div>
		<div class="fl"><input style="width:276px;" value="Complete Your Profile" class=" fl grn-btn-verify cp" type="button" onClick="openLayer();"></div>
		<div class="sp8"></div>
		<div class="sp15"></div>
 ~if FTOLiveFlags::IS_FTO_LIVE`
		<div class="fullwidth" style=" font-size:20px; display:block;">You can also get paid membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span> FREE </div>
		<div class="f16" style=" text-align:left">
		<a href="~$SITE_URL`/fto/offer?fromReferer=1">Know more</a>
		</div>
		~/if`
		</div>
	</div>
		<!--profile pic end -->
		<!--profile content start -->
		<!--profile content end -->
<script type = "text/javascript">
function openLayer()
{
	$.colorbox({href:"~sfConfig::get('app_site_url')`/profile/editProfile?width=700&flag=INCOMP"});
}
</script>
