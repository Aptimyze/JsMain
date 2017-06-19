<div style="margin-left:45px;">
    <div>
    <div class="sp15"></div>
      <div class="fs16">Like this profile?
       ~Messages::getExpressButton([ONCLICK=>'tab_express_interest()'])` 
      </div>
      <div class="sp12"></div>
      <div>
	~include_partial("contacts/profile_locked_phoneEmail",['EXT'=>0,'GRAY'=>2])`
        </div>
      <div class="sp12"></div>
      <div class="fl">
        <p class="fs16">Phone/Email are locked. To see Phone/Email of this member, buy paid membership or take ~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`</p>
      </div>
    </div>
    <div class="sp12"></div>
    <div class="fs16">
      <p>To get the ~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`,</p>
      ~include_partial("contacts/profile_phone_photo_c", ['contactEngineObj' => $contactEngineObj])`
  </div>
    <div style="text-align:center; top:  210px; left: 60px;"></div>
    <div class="sp12"></div>
    <div class="fs16">Hurry! Offer valid till&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong></div>
    <div class="sp5"></div>
    <div class="fs14">
      <p>~Messages::getFreeTrialOfferLink(["NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"CLASS"=>"b underline fs14","LINK"=>"Know more"])`</p>
      <p>&nbsp;</p>
      <p></p>
    </div>
<div class="fs14" style="height:50px">
<div style="border:1px #dbdbdb;border-bottom-style:solid;width:98%">&nbsp;</div>
<div style="float:left;"></div><div class="rf" style="text-align:right;cursor:pointer;" onclick="javascript:close_tab()"><img src="~sfConfig::get("app_img_url")`/profile/images/icon_hide.gif"></div>
</div>
  </div>
