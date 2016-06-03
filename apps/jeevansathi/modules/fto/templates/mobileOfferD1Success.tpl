<!-- Header starts -->
<!-- Header ends -->

~include_partial("mobileDCommon", ["showBackLink" => $showBackLink, "refererUrl" => $refererUrl])`

<div class="quickMob">
<fieldset class="fto-fldset">
<legend class="fs14 fto-legend">How to get
Acceptance?</legend>
<div class="content-holder">
<div class="mt10">
<div class="mt10">
<p>
<strong>Express Interest</strong> in profiles you
like
</p>
<p>
If they also like you, they will send an<br>
<strong>Acceptance</strong>
</p>
</div>
<div class="fs14 mt10">
<div style="background-image:url(~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/pic.jpg); width:203px; height:151px; position:relative; font-size:12px">
<div style="position:absolute; top:118px; left:25px;  width:auto; background:#FFF">You</div>
<div style="background-image: url(~$loginThumbnailPicUrl`); position:absolute; top:54px; left:7px; width:60px; height: 60px;">
<img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="60" height="60" border="0">
</div>
<div style="position:absolute; top:7px; left:134px; width:64px; background-color:#FFFFFF; text-align:center">Jeevansathi user</div>
~if $otherThumbnailPicUrl neq 'NA'`
<div style="height: 47px; left: 116px; position: absolute; text-align: center; top: 35px; width: 49px; background-image: url(~$otherThumbnailPicUrl`);">
~else if $loginGender eq 'M'`
<div style="height: 47px; left: 116px; position: absolute; text-align: center; top: 35px; width: 49px; background-image: url(~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/mobile_dummy_female.jpg);">
~else`
<div style="height: 47px; left: 116px; position: absolute; text-align: center; top: 35px; width: 49px; background-image: url(~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/mobile_dummy_male.jpg);">
~/if`
</div>
</div>
</div>
<div class="mt10 fs14"></div>
<div class=" mt10 fs14"></div>
</div>
</div>
</fieldset>
</div>

<div class="quickMob">
<fieldset class="fto-fldset">
<legend class="fs14 fto-legend">How to Express
Interest?</legend>
<div class="content-holder">
<div class="mt10">
<div class="mt10">
<p>
<span class="fs16">
<a href="~sfConfig::get('app_site_url')`/search/partnermatches">
<input value="Express Interest" class="searchbtn fbld" style="font-size:12px; margin-left:0px; padding:0px; text-align: center; width: auto;" readonly>
</a>
</span> in profiles you like
</p>
</div>
<div class="fs14 mt10">
<strong>
<img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/pic2.jpg">
</strong>
</div>
<div class="mt10 fs14"></div>
<div class=" mt10 fs14"></div>
</div>
</div>
</fieldset>
</div>

~if $showSuggestedMatches eq 1`
~include_partial("mobileSuggestedProfiles", ["suggestedProfiles" => $suggestedProfiles])`
~/if`

<div class="quickMob" style="text-align: center;">
Hurry! offer vaild till 
<strong style=" color:#b00800;">~$day`<sup>~$superscript`</sup>
&nbsp;~$month`,&nbsp;~$year`</strong>
</div>

<div class="quickMob" style="margin:0 auto; position:relative; text-align:center">
<a href="~sfConfig::get('app_site_url')`/search/partnermatches">
<input value="See more matches" class="searchbtn fbld" style="font-size:12px; margin-left:0px; padding:0px; text-align: center;">
</a>
</div>

<div class="quickMob fs14" style="text-align: center;">
or call us on 
<a href="tel:18004196299">
1 - 800 - 419 - 6299
</a>
</div>

