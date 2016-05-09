~if MobileCommon::isMobile()`
<div class="quickce fs14">
  <div>Phone  : <img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/phone-blur.jpg" class="imgR"><img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/lock.jpg" class="imgR"></div>
  <div style="height:10px"></div>
  <div>Email  &nbsp;&nbsp;: <img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/email-blur.jpg" class="imgR" ><img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/lock.jpg" class="imgR" ></div>
</div>
~else`
<div class="~if !$EXT`fs16~/if` fl ph_lock fwn_ce" ~if $EXT`style="width:127px;~if $EXT` eq 1`margin-top:3px~/if`"~/if` >
  <div class="fl">Phone ~if $EXT`Number~/if`</div><div class="fr">:</div>
  <div class="sp5"></div>
  ~if $EXT eq 1`
  <div class="sp5"></div>
  ~/if`
  <div class="fl"> Email ~if $EXT`Address~/if`</div><div class="fr">:</div>
</div>
<i class="~if $CLASS`~$CLASS`~else`ico-blur-phone~$GRAY` sprite-new fl~/if`">&nbsp;</i>
~/if`
