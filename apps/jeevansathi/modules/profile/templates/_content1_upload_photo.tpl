
 <!--profile pic starts -->       
  <div class="pro_tupn pro_tup1n1" style="width:914px">
      <div>
      <div class="fl" style="padding-left:6px; margin-right:12px;">
 <div>
 ~if $GENDER eq "M"`

	~if !$crmback`<a  href="/social/addPhotos"~/if`><img src="~sfConfig::get('app_img_url')`/images/upload_photo_male.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">~if !$crmback`</a>~/if`
~else`
	~if !$crmback`<a  href="/social/addPhotos">~/if`<img src="~sfConfig::get('app_img_url')`/images/upload_photo_female.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">~if !$crmback`</a>~/if`
~/if`
</div>
      <br />
      <br />
      </div>
	<div class="fl">
	<div class="maroon" style="font-size:22px">
Upload your photo &amp; Get Paid Membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span> FREE </div>
<div class="sp5"></div>
<div class="f17">See e-mail IDs/Phone numbers of people you like. </div>
<div class="sp10">&nbsp;</div>
<div class="fl"><input style="width:184px" value="Upload Photo" class="grn-btn cp" type="button" onClick="redirect('~sfConfig::get('app_site_url')`/social/addPhotos');"></div>

<div class="sp5"></div>

<div class="f16" style=" text-align:right">
<a href="~$SITE_URL`/fto/offer?fromReferer=1">Know more</a>
</div>


<div style="width:726px" class="bottom-bar">



<div class="fl"><div class="bg-show sprte-icons fl"></div>

<div class="fl f14"><div class="sp15">&nbsp;</div>
<div style="width:103px" class="fl mar_left_10">Show your 
photo to people 
you like</div></div></div>
<div class="separator fl sprte-icons"></div>
<div class="fl"><div class="bg-no-download sprte-icons fl"></div><div class="fl f14"><div class="sp15">&nbsp;</div>

<div style="width:144px;margin-left:8px;" class="fl">Photos on Jeevansathi 
cannot be downloaded 
using right click</div></div></div>
<div class="fl separator sprte-icons"></div>

<div class="fl"><div class="bg-watermarked sprte-icons fl"></div><div class="fl f14"><div class="sp15">&nbsp;</div>

<div class="mar_left_10" style="Width:160px;">
Photos on Jeevansathi 
  are watermarked to 
  prevent tempering
  </div>
  
  </div></div>




</div>
</div>
</div>
		<!--profile pic end -->
		<!--profile content start -->
		<!--profile content end -->
