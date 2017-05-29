<script type="text/javascript" language="Javascript">
var postDataVar={'profilechecksum':'','stype':"33",'suggest_profile':'','CURRENTUSERNAME':'~$loginProfile->getUSERNAME()`','page_source':'offer','divname':'div','draft':"~$draft|decodevar`"};
$(function () {
    $("#ExpressInterestButton").click(function () {
      window.location.href="~sfConfig::get('app_site_url')`/search/partnermatches";
      });
    });
    
</script>

<body>
<!-- start header -->
~include_partial('global/header',[showSearchBand=>0,searchId=>$searchId,pageName=>$pageName,loggedInProfileid=>$loggedInProfileid])`
<!--end header -->

<style>
.abcde{background:url(~$IMG_URL`/images/hm_pg_sprte3.gif) 0px -261px;}
</style>
<!--Main container starts here-->
<div class="fto-main-content">
<p class="clr_4">
</p>

<div id="topSearchBand">
</div>

~include_partial('global/sub_header',[pageName=>$pageName])`

<p class="clr_4">
</p>
<p class="clr_4">
</p>
~if $showBackLink eq 1`
<a href="~$refererUrl`" class="fs16" style="text-decoration:underline;">&lt;&lt;&nbsp;Back</a>
~/if`    
<div class="sp10"></div>
<div class="fto-main-heading-extend w406 h57 sprte-fto" ></div>
<div class="sp10"></div>
<div class="center" style="font-size: 20px;">
<strong>See contact details of first 
~if $inboundAcceptLimit eq 'one'` 
member who accepts 
~else`
~$inboundAcceptLimit` members who accept 
~/if`
your interest.</strong></div>
<div class="sp5"></div>
<div  class=" h34 b" style="font-size:30px; text-align:center">
Hurry! offer valid till <Span class="maroon">~$day`<sup>~$superscript`</sup>&nbsp;~$month`&nbsp;~$year`</Span>

</div>
<div class="h47"></div>

<fieldset class="pos-rel">
<div style="z-index: 1000; width: 90px; background: none repeat scroll 0px 0px rgb(255, 255, 255); font: 18px arial; position: absolute; left: 556px; top: 99px; text-align: center;height: 63px;">After<br> Acceptance</div>
<legend>See Phone/Email for FREE</legend>
<div class="fl w190 fs24" style="width: 200px; margin-top: 130px;">Phone/Email will 
get unlocked and 
you can see them</div>
<div class="w72 fl h100">&nbsp;</div>
<span class="w558 h263 fr "><div class="h263 sprte-fto fto-phone-email-bg">&nbsp;</div><div class="fullwidth fs11 clear"><span class="fr">Note: These are dummy details</span></div></span>

</fieldset> 
<div class="mar47top"></div>
<fieldset>
<legend>How to get <strong>ACCEPTANCE ?</strong></legend>
<div class="mar40top"></div>

<div class="w420 fl fs24 ">
<p><strong>Express Interest</strong><br />
In profiles you like</p>
<div class="mar24top"><p>If they also like you, they will <br />
send an<strong> Acceptance</strong><br />
</p></div>
<div class="mar24bottom"></div>
<div class="fs16"></div>


</div>
<div class="fr w413 fto-express sprte-fto pos-rel">
<div class="exp-int-text fs16">Express Interest</div>

<div class="img-swap1" style="background-image: url(~$loginThumbnailPicUrl`); background-repeat: no-repeat;">
<img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="60" height="57" galleryimg="NO" border="0" oncontextmenu="return false;">
</div>
~if $otherThumbnailPicUrl neq 'NA'`
<div class="img-swap2" style="background-image: url(~$otherThumbnailPicUrl`); z-index: 1001; background-repeat: no-repeat;">
~else if $loginGender eq 'M'`
<div class="img-swap2" style="background-image: url(~sfConfig::get('app_img_url')`/images/contactImages/dummy_female.png)">
~else`
<div class="img-swap2" style="background-image: url(~sfConfig::get('app_img_url')`/images/contactImages/dummy_male.png)">
~/if`
<img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="60" height="60" galleryimg="NO" border="0" oncontextmenu="return false;">
</div>
<div class="accept-text fs16">Acceptance</div>  
<div class="you-text">You</div>
<div class="js-user-text">Jeevansathi user</div>
</div>


</fieldset>
<div class="mar47top"></div>
<fieldset>
<legend>How to Express Interest ?</legend>

<div class="w307 fl">

<div class="mar90top">&nbsp;</div>
<div class="mar89top">&nbsp;</div>
<input type="button" class="w235 fto-btn-green-fto white fs24 sprte-fto" style="cursor: pointer;" value="Express Interest" id="ExpressInterestButton"/>
<br />
<div class="sp10"></div>
<div class="fs24" style="margin-left: 23px;">In profiles you like</div>
<div class="mar24top"></div>
<div class="mar24bottom"></div>
<div class="sp10"></div>
<div class="fs16"></div>


</div>
<div class="w86 fl">
<div class="mar90top">&nbsp;</div>
<div class="mar84top">&nbsp;</div>
<div class="fto-arrow sprte-fto fl h63 w83 h63 ">&nbsp;</div>
</div>
<div class="fr fs16 w453 h361 fto-express-int sprte-fto mar37top" >





</div>


</fieldset>
~if $noContact eq 0 and $showSuggestedMatches eq 1`
~include_partial("suggestedMatches", ['suggestedProfiles' => $suggestedProfiles, 'draft' => $draft])`
~else if $noContact eq 1`
~include_partial("profileTemplate", ['profileObject' => $profileObj, 'profilechecksum' => $profilechecksum, 'draft' => $draft, 'loginGender' => $loginGender])`
~/if`

<div class="sp5"></div> 
<div class="fs11 fl clear fullwidth">
<span class="fr">* For complete  details see <a  href="~sfConfig::get('app_site_url')`/profile/disclaimer.php" title="terms and conditions">terms and conditions</a></span></div>

<div class="h37 clear"></div>
<div  class=" h34 b" style="font-size:30px; text-align:center">
Hurry! offer valid till <Span class="maroon">~$day`<sup>~$superscript`</sup>&nbsp;~$month`&nbsp;~$year`</Span>

</div>
<div class="h39"></div>
<div class="fl center fullwidth fs24">
~if $noContact eq 1`
Know more about ~$profileObj.username`
<div class="sp10"></div>
<input type="button" class="w235 fto-btn-green-fto white fs24 sprte-fto " style="cursor: pointer;" value="See Full Profile" onclick="window.location='~$profileObj.viewProfileUrl`';"/>
~else`
<input type="button" class="w235 fto-btn-green-fto white fs24 sprte-fto " style="cursor: pointer;" value="See more matches" onclick="window.location='~sfConfig::get('app_site_url')`/search/partnermatches';"/>
~/if`
<br />
<div class="sp10"></div>
or call us on 1 - 800 - 419 - 6299
<div class="mar47top"></div>
</div>

</div><!--Main content finish -->
~BrijjTrackingHelper::setJsLoadFlag(1)`
~include_partial('global/footer',[data=>~$loggedInProfileid`,pageName=>$pageName])`


</body>
