<div style="margin-left:45px">
  
~include_partial("contacts/profile_locked_phoneEmail",['EXT'=>1,'GRAY'=>2])`
<p class="clr_18"></p>
<div class="fs16" style="font-weight:normal">
  <p>To see Phone/Email of this member,   </p>
  <p>
  ~Messages::getCompleteNowButton()`
  </p>
</div>
<p class="clr_18"></p>
~if FTOLiveFlags::IS_FTO_LIVE`
<div class="fs16" style="font-weight:normal">You can also get the ~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`
<div class="fs14"></div>
</div>
~/if`
<p class="clr_18"></p>
<div class="fs16" style="font-weight:normal"> Can't complete your profile now? ~Messages::getExpressLink([ONCLICK=>"tab_express_interest()"])`</div>
<div class="sp5"></div>
<div class="fs14" style="height:50px">
<div style="border:1px #dbdbdb;border-bottom-style:solid;width:98%">&nbsp;</div>
<div style="float:left;"></div><div class="rf" style="text-align:right;cursor:pointer;" onclick="javascript:close_tab()"><img src="~sfConfig::get("app_img_url")`/profile/images/icon_hide.gif"></div>
</div>
  </div>
