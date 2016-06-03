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
<div class="fto-main-heading w406 h57 sprte-fto" ></div>
<div class="sp15"></div>
<div class="fto-mem  center"><strong>Your Free Trial is now Active</strong></div>
<div class="block mar40top" >
<div class="w525 h84  sprte-fto pad15top pad48left" style="background-position:0px 95px; margin:0 auto" >

<span class="b  fl" style="font-size:25px ; line-height:3.8; overflow:hidden; margin-left: -30px;">Offer Expires in</span>
<span class="fl mar8left ">
~include_partial("timeCounter", ['expiryDate' => $expiryDate, 'fromOfferPage' => 'D', 'currentDate' => $currentDate])`
<div id="altCountDown" style="display: none;">
<div class="fl center fs16">Days <br /><input type="text" class="fto-txt-timer mar8left" value="00" style="width:60px" readonly/></div>
<div class="fl center fs16">Hrs <br />
<input type="text" class="fto-txt-timer mar16left"  value="00" style="width:60px" readonly/></div>
<div class="fl center fs16">Mins <br />
<input type="text" class="fto-txt-timer mar16left"  value="00"  style="width:60px" readonly/></div>
<div class="fl maroon center fs16">Secs <br />
<input type="text" class="fto-txt-timer mar16left maroon" value="00"  style="width:60px" readonly/></div>
</span>
</div>


</div></div>
<div class="h47"></div>

<fieldset class="pos-rel">
<div style="z-index: 1000; width: 90px; background: none repeat scroll 0px 0px rgb(255, 255, 255); font: 18px arial; position: absolute; left: 556px; top: 99px; text-align: center;height: 63px;">After<br> Acceptance</div>
<legend>See Phone/Email for FREE</legend>
<div class="fl w190 fs24" style="width: 200px; margin-top: 130px;">Phone/Email will 
get unlocked and 
you can see them</div>
<div class="w72 fl h100">&nbsp;</div>
<span class="w558 h263 fr ">
<div class="h263 sprte-fto fto-phone-email-bg">&nbsp;</div>
<div class="fullwidth fs11 clear">
<span class="fr">Note: These are dummy details</span>
</div>
</span>

<div class="sp15"></div>
</fieldset> 
<div class="mar47top"></div>
<fieldset>
<legend>Finding a Life Partner</legend>
<div class="mar40top"></div>

<div class="w320 fl fs24 ">
<p>Most people 'Express Interest' 
in at least<span  style="font-size:30px"> 40 profiles </span>
to find a Life Partner</p>
</div>

<div class="w86 fl" style="margin-left:105px;">
<div class="fto-arrow sprte-fto fl h63 w83 h63 ">&nbsp;</div>
</div>
<div class="fr w200 " >
<span class=" fl ">
<input type="button" id="ExpressInterestButton" class="w196 fto-btn-green-fto white fs24 sprte-fto fl" style="cursor: pointer;" value="Express Interest"/>
</span>
<div class="fs20" style="text-align: center; font-size: 18px;">in all the profiles you like</div>

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
<div class="fs24 center"></div>
<div class="sp10"></div>
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
or call us on 1- 800 - 419 - 6299
<div class="mar47top"></div>
</div>

</div><!--Main content finish -->
~BrijjTrackingHelper::setJsLoadFlag(1)`
~include_partial('global/footer',[data=>~$loggedInProfileid`,pageName=>$pageName])`


</body>
