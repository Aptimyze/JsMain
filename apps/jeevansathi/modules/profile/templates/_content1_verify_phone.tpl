
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
Verify your phone number &amp; Get Paid Membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span> FREE </div>
<div class="sp5"></div>
<div style="font-size:22px">See e-mail IDs/Phone numbers of people you like. </div>

<div class="sp10">&nbsp;</div>
<div class="fl"><input value="Verify your phone number" class=" fl grn-btn-verify cp" onClick="$.colobox({href:'/profile/myjs_verify_phoneno.php'});" type="button"></div>
<div class="sp5"></div>
<div class="f16" style=" text-align:right">
<a href="/fto/offer?fromReferer=1">Know more</a>
</div>


<div style="width:724px" class="bottom-bar">



<div class="fl"><div class="bg-show sprte-icons fl"></div>

<div class="fl f14"><div class="sp15">&nbsp;</div>
<div style="width:103px;" class="fl mar_left_10">Show your 
phone to people 
who meet your 
criteria </div></div></div>
<div class="separator fl sprte-icons"></div>
<div class="fl">
<div class="sp15"></div><div class="bg-con fl"></div><div class="fl f14">

<div style="width:142px" class="fl mar_left_10 ">You can hide 
your phone 
number 
from anyone</div></div></div>
<div class="fl separator sprte-icons"></div>

<div class="fl">
<div class="sp15"></div><div class="bg-ph fl"></div><div class="fl f14 mar_left_10">
  Jeevansathi does <br>
  not share your <br>
  number with <br>
  any other website</div></div>



</div>
</div>
</div>
		<!--profile content end -->
