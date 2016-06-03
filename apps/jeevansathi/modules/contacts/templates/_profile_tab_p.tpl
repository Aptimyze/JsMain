<div>
  <div style=" height: 300px;width: 460px;" id="main_layer_dp">
<div style="width:460px; padding-top:7px;">

<div class="fs16">Expressing interest in this profile will send the 
  following message</div>

<div class="sp12"></div>
</div>
<div class="fr">
~include_partial("contacts/messagedropdown",[drafts=>$contactEngineObj->getComponent()->drafts,tab=>'tab'])`
</div>
<div class="sp15"></div>
<div class="flcet">
<textarea class="w457CE h102CE" id="tab_textarea">~ProfileDrafts::getMessage($contactEngineObj->getComponent()->drafts,'')`
</textarea></div>
<div class="sp15"></div>
<div class="fl"> 
~assign var=dp_profile value=$contactEngineObj->contactHandler->getViewed()->getPROFILEID()`
~Messages::getExpressButton([ONCLICK=>'tab_express_interest()'])`
</div>
<div class="sp5"></div>
<br>
</div>
<div class="fs14" style="height:50px">
<div style="border:1px #dbdbdb;border-bottom-style:solid;width:98%">&nbsp;</div>
<div style="float:left;"></div><div class="rf" style="text-align:right;cursor:pointer;" onclick="javascript:close_tab()"><img src="~sfConfig::get("app_img_url")`/profile/images/icon_hide.gif"></div>
</div>
</div>
