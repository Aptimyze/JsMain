 <!--profile pic starts -->       
  <div class="pro_tupn pro_tup1n1" style="width:914px">
      <div>
      <div class="fl" style="padding-left:6px; margin-right:12px;">
<div>~if !$crmback`<a  href="/social/addPhotos">~/if`<img src="~$PHOTO`" width="150" height="200" GALLERYIMG="NO" border="1" oncontextmenu="return false;">~if !$crmback`</a>~/if`</div>
			<div class="sp5"></div>
			<div>
			<div class="btn-folder fl">&nbsp;</div>
			<a class="fs14" href="/social/viewAllPhotos/none">Photos (~$NO_OF_PHOTOS`)</a>
			</div>
      </div>
      <div class="fl fs16" style="width:280px;padding-top:15px;">
<div class="sp10">&nbsp;</div>


 <div>Upload Photos of your</div>
 <div class="sp12"></div>
 <div class="fl ">
 	<div class="ico-family fl"></div>
    <div class="fl mar_left_10"><a href="/social/addPhotos" class="b">Family</a></div>
    <div class="sp12"></div>
    <div class="ico-friends fl"></div>
    <div class="fl" style="margin-left:4px"><a href="/social/addPhotos" class="b">Friends</a></div>
    <div class="sp12"></div>
    <div class="ico-house fl"></div>
    <div class="fl mar_left_10"><a href="/social/addPhotos" class="b">House</a></div>
    
 </div>
 <div class="sp15"></div>
<div class="sp3"></div>
<div>
<input class="grn-btn cp" value="Upload more Photos" type="button " onClick="redirect('~sfConfig::get('app_site_url')`/social/addPhotos');">

</div>

</div>



<!--profile pic end -->
<!--profile content start -->



</div>
<!--profile content end -->
<!--profile content1 start -->
<div style="width:403px; padding-left:20px; padding-right:20px; height:auto; outline:3px solid #cccccc; border:2px solid #888; margin-top:-7px; background:#FFF;" class="fr">
<br>
<div style="font-size:24px; text-decoration: none;"><a href="~$SITE_URL`/fto/offer?fromReferer=1" style="text-decoration: none;color:#de5400">Free Trial Offer</a></div>
<div>
  <p style="font-size:20px;">See Phone/Email of 
    members on acceptance</p>
  
  <div class="sp10">&nbsp;</div>
<div class="fs16">
~if  $FtoState eq 'D1'`
  <p><span style="width:198px;" class="fl">No of profile views</span> <strong>:&nbsp;~$PROFILE_VIEW`</strong></p>
  <p><span style="width:198px" class="fl">No of interests received	</span> <strong style="color:red">:&nbsp;~$INTR_REC`</strong></p>
  ~else`
  <p><span style="width:198px;" class="fl">No of interests sent</span> <strong>:&nbsp;~$INTR_SENT`</strong></p>
  <p><span style="width:198px" class="fl">No of acceptances received	</span> <strong style="color:red">:&nbsp;~$ACCPT_REC`</strong></p>
  ~/if`
</div>
<div class="sp10">&nbsp;</div>

  <p style="font-size:20px; ">~if $FtoState eq 'D1'`To get more Responses~else`To get more Acceptances~/if`,</p>
  <p style="font-size:20px; "><a href="~$LAYER_URL`" class="thickbox" style="font-weight:bold;text-decoration:underline">~$LAYER_TEXT`</a>

  </p>
   <div class="sp15">&nbsp;</div>
    <p class="fl"><a href="~$SITE_URL`/fto/offer?fromReferer=1" style="font-size:16px;text-decoration:underline">Know more</a>
	<div class="sp15"></div>
	</p>
<div class="sp5"></div>
</div>

</div>
		<!--profile content end -->
