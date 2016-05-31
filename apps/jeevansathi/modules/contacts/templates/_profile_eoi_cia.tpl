<div class="ce_357">

<div class="fs16" >
  <p>~Messages::getEOIMessage($contactEngineObj)`</p>
  <div class="sp12"></div>
  
  ~include_partial("contacts/AccNotButton",[contactEngineObj=>$contactEngineObj])`
</div>

<div class="sp24"></div>
<div class="fl" >
  <p class="fs16">
  ~Messages::getFreeTrialOfferLink([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`</p>
  <p class="fs16">~Messages::getFTOMessage($contactEngineObj)`</p>
</div>
</div>

<div class="sp24"></div>
<div class="fs16">
  <p>To avail this offer,</p>
  
  ~include_partial("contacts/profile_phone_photo_c",[contactEngineObj=>$contactEngineObj])`

<div class="sp24"></div>
<div class="fs16">Hurry! Offer valid till&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>
<div class="sp5"></div>
<div  class="fs16"> ~Messages::getFreeTrialOfferLink(["NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"CLASS"=>"b underline fs14","LINK"=>"Know more"])`</div>
<div class="sp5"></div>
<br />
</div>
<textarea name="draft" id="draft" class="w347CE h102CE hideCE" >~if $contactEngineObj->contactHandler->getToBeType() eq 'A' || $contactEngineObj->contactHandler->getToBeType() eq ''`~ProfileDrafts::getMessage($contactEngineObj->getComponent()->acceptdrafts,'')`~else`~ProfileDrafts::getMessage($contactEngineObj->getComponent()->declinedrafts,'')`~/if`
</textarea>