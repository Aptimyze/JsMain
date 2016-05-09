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

<script type="text/javascript">
$(function () {
    $("#photo").click(function () {
      if ($("#phoneSecurity").css('display') == 'none') {
      }
      else {
        $("#phoneSecurity").fadeOut('medium').css('display', 'none');
      }
      $("#photoSecurity").toggle('fast');
    });

    $("#phone").click(function () {
      if ($("#photoSecurity").css('display') == 'none') {
      }
      else {
        $("#photoSecurity").fadeOut('medium').css('display', 'none');
      }
      $("#phoneSecurity").toggle('fast');
      });
    $("#photoClose").click(function () {
      $("#photoSecurity").fadeOut('medium');
      });
    $("#phoneClose").click(function () {
        $("#phoneSecurity").fadeOut('medium');
        });
    });
</script>
~if $showBackLink eq 1`
  <a href="~$refererUrl`" class="fs16" style="text-decoration:underline;">&lt;&lt;&nbsp;Back</a>
~/if`
<div class="sp10"></div>
<div class="fto-main-heading w406 h57 sprte-fto" ></div>
<div class="abcde"></div>
<div class="sp15"></div>
<div class="fto-mem  center"><strong>Membership worth  <span style="text-decoration:line-through">Rs.1100</span>  FREE</strong></div>
<div class="h47"></div>
<fieldset class="pos-rel">
<legend>See Phone/Email for FREE</legend>
<div class="fl fs24 mar162top" style="width: 200px; margin-top: 130px;">Phone/Email will get unlocked and you can see them</div>
<div class="w72 fl h100">&nbsp;</div>
<span class="w558 h263 fr ">
<div class="h263 sprte-fto fto-phone-email-bg">&nbsp;</div>
<div class="fullwidth fs11 clear"><span class="fr">Note: These are dummy details</span></div>
</span>
</fieldset> 
<div class="mar47top"></div>
<fieldset style="position: relative;">
<legend>To take Free Trial Offer</legend>
~if $subState eq 'C1'`
<div id="phoneSecurity" class="fl mar20left fto-pop-box-down" style="display: none; top: 158px;">
~else`
<div id="phoneSecurity" class="fl mar20left fto-pop-box-down" style="display: none; top: 35px;">
~/if`
<div class="fl fto-popup-container fs16 b pos-rel" style="background-color: white;"> 
<div id="phoneClose" class="sprte-fto fto-green-close pos-abs" style="cursor: pointer;">
</div> 
<div class="w340 h104 block fl fto-brd-bot" >
<div class="fl sprte-fto fto-box"></div>
<div class="fl w175 mar30left mar31top ">Show your phone to 
people who meet your 
criteria </div>
</div>
<div class="w340 h91 block fl fto-brd-bot">
<div class="fl sprte-fto fto-box4 mar15top"></div>
<div class="fl w175  mar27left mar15top">You can hide your 
phone number from 
anyone
</div>    
</div>
<div class="w340 block fl">
<div class="fl sprte-fto fto-box5 mar15top "></div>
<div class="fl  w175 mar40left mar15top">Jeevansathi does not share your number with any other website</div>
</div>
</div>
<div class="sprte-fto fto-indicate pos-rel"></div>
</div>
~if $subState eq 'C1'`
<div id="photoSecurity" class="fl mar20left fto-pop-box" style="display: none; top: 10px;">
~else`
<div id="photoSecurity" class="fl mar20left fto-pop-box" style="display: none; top: 35px;">
~/if`
<div class="fl fto-popup-container fs16 b pos-rel" style="background-color: white;"> 
<div id="photoClose" class="sprte-fto fto-green-close pos-abs" style="cursor: pointer;"></div> 
<div class="w340 h104 block fl fto-brd-bot" >
<div class="fl sprte-fto fto-box"></div>
<div class="fl w175 mar30left mar31top ">Show your photo to people you like</div>
</div>
<div class="w340 h91 block fl fto-brd-bot">
<div class="fl sprte-fto fto-box2"></div>
<div class="fl w175  mar27left mar20top">Photos on Jeevansathi cannot be downloaded using right click</div>    
</div>
<div class="w340 block fl">
<div class="fl sprte-fto fto-box3 mar10top"></div>
<div class="fl  w175 mar90left mar20top">Photos on Jeevansathi are watermarked to prevent tampering</div>
</div>


</div>
<div class="sprte-fto fto-indicate pos-rel"></div>
</div>

~if $subState eq 'C1'`
<div class="mar45top"></div>
<div class="w307 fl">
<input type="button" class="w196 fto-btn-green-fto white fs24 sprte-fto " value="Upload photo" onClick="RedirectFromCE('/social/addPhotos')" style="cursor: pointer;"/>
<div class="sp10"></div>
<div class="fs16">Jeevansathi <span id="photo" style="text-decoration:underline; color: #117DAA; cursor: pointer;">photo security features</span></div>
<div class="mar24top"></div>
<div class="fs24">&amp;</div>
<div class="mar24bottom"></div>
<input type="button"  class="w235 fto-btn-green-fto white fs24 sprte-fto" value="Verify your phone" onClick="$.colorbox({href:'/profile/myjs_verify_phoneno.php?flag=1&width=700'});" style="cursor: pointer;"/>
<div class="sp10"></div>
<div class="fs16">Jeevansathi <span id="phone" style="text-decoration:underline; color: #117DAA; cursor: pointer;">phone security features</span></div>
</div>
~elseif $subState eq 'C2'`
<div class="mar45top"></div>
<div class="w307 fl mar33top">
<input type="button" class="w196 fto-btn-green-fto white fs24 sprte-fto " value="Upload photo" onClick="RedirectFromCE('/social/addPhotos')" style="cursor: pointer;"/>
<div class="sp10"></div>
<div class="fs16">Jeevansathi <span id="photo" style="text-decoration:underline; color: #117DAA; cursor: pointer;">photo security features</span></div>
<div class="mar24top"></div>
<div class="mar24bottom"></div>
<div class="sp10"></div>
<div class="fs16"></div>
</div>
~elseif $subState eq 'C3'`
<div class="mar45top"></div>
<div class="w307 fl mar33top">
<input type="button" class="w235 fto-btn-green-fto white fs24 sprte-fto " value="Verify your phone" onClick="$.colorbox({href:'/profile/myjs_verify_phoneno.php?flag=1&width=700'});" style="cursor: pointer;"/>
<div class="sp10"></div>
<div class="fs16">Jeevansathi <span id="phone" style="text-decoration:underline; color: #117DAA; cursor: pointer;">phone security features</span></div>
<div class="mar24top"></div>
<div class="mar24bottom"></div>
<div class="sp10"></div>
<div class="fs16"></div>
</div>
~/if`
<div class="w86 fl">
<div class="mar70top"></div>
<div class="fto-arrow sprte-fto fl h63 w83 h63 ">&nbsp;</div>
</div>
<div class="fr fs16 w374">
<!--div class="sp20"></div -->
<div class="sp5"></div>
<p>
~if $subState eq 'C1'`
Upload your photo &amp; Verify your phone
~elseif $subState eq 'C2'`
Upload your photo
~elseif $subState eq 'C3'`
Verify your phone
~/if` before
<span class="maroon b">~$day`<sup>~$superscript`</sup>&nbsp;~$month`,&nbsp;~$year`</span></span> to get Free Trial Offer </p>
<div class="sp10"></div>
<div id="timeLeft" class=" sprte-fto fl fto-timer fullwidth"><span class="b fs20 fl mar33top">Time left</span>
<span class="fl mar12left">
~include_partial("timeCounter", ['expiryDate' => $expiryDate, 'currentDate' => $currentDate])`
</span>
</div>
<div id="altCountDown" style="display: none;">
<div class=" sprte-fto fl fto-timer fullwidth">
<span class="b fs20 fl mar33top">Time left</span>
<span class="fl mar12left">
<div class="fl center">Days <br />
<input type="text" class="fto-txt-timer mar8left" value="00"/>
</div>
<div class="fl center">Hrs <br />
<input type="text" class="fto-txt-timer mar8left" value="00"/>
</div>
<div class="fl center">Mins <br />
<input type="text" class="fto-txt-timer mar8left" value="00" />
</div>
<div class="fl maroon center">Secs <br />
<input type="text" class="fto-txt-timer mar8left maroon" value="00" />
</div>
</span>
</div>

<div class="w373"></div>
</div>
</fieldset>
<div class="sp5"></div>
<div class="fs11 fl clear fullwidth">
<span class="fr">* For complete  details see <a  href="~sfConfig::get('app_site_url')`/profile/disclaimer.php" title="terms and conditions">terms and conditions</a></span>
</div>
<div class="sp15 clear"></div>
<div class="sp15"></div>
<div class="sp10"></div>
<div class="fl center fullwidth fs24">
~if $subState eq 'C1' or $subState eq 'C2'`
<input type="button" class="w196 fto-btn-green-fto white fs24 sprte-fto " value="Upload photo" style="cursor: pointer;" onClick="RedirectFromCE('/social/addPhotos')"/> 
~elseif $subState eq 'C3'`
<input type="button" class="w235 fto-btn-green-fto white fs24 sprte-fto " value="Verify your phone" style="cursor: pointer;" onClick="$.colorbox({href:'/profile/myjs_verify_phoneno.php?flag=1&width=700'});"/>
~/if`
to get offer<br />
<div class="sp10"></div>
or call us on 1 - 800 - 419 - 6299
<div class="mar47top"></div>
</div>
</div><!--Main content finish -->

<!-- div class="sp20 clear" ></div -->
~BrijjTrackingHelper::setJsLoadFlag(1)`
~include_partial('global/footer',[data=>~$loggedInProfileid`,pageName=>$pageName])`
