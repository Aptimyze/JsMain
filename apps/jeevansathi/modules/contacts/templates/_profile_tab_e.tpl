<div style="margin-left:45px">
	
	~if $contactEngineObj->getComponent()->errorMessage`
	<div class="sp15"></div>
	<div>
	~$contactEngineObj->getComponent()->errorMessage|decodevar`
	</div>
	<div class="sp15"></div>
	~else`
  <div class="fs16">Like this profile?  ~Messages::getExpressButton([ONCLICK=>'tab_express_interest()'])` </div>

<div class="sp12"></div>
<div>
  <p class="fs16">Expressing interest in this profile will send the 
    following message </p>
</div><div class="sp12"></div>
<div style="background:#e5ecc8; color:#4d4d4d; padding:5px; width:330px; border:1px solid #d1d8b3;" class="fs16">
~ProfileDrafts::getMessage($contactEngineObj->getComponent()->drafts,'')`
</div><div class="sp15"></div>
<div class="fs16">To edit the above message and include your 
  Phone Number/ Email Address,</div>
<div class="sp5"></div>
<div class="fs16"> ~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR])`</div>
<div class="sp5"></div>
~/if`
<div class="fs14" style="height:50px">
<div style="border:1px #dbdbdb;border-bottom-style:solid;width:98%">&nbsp;</div>
<div style="float:left;"></div><div class="rf" style="text-align:right;cursor:pointer;" onclick="javascript:close_tab()"><img src="~sfConfig::get("app_img_url")`/profile/images/icon_hide.gif"></div>
</div>

</div>
