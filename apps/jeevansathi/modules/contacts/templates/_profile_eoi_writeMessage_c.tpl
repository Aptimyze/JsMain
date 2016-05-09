<div class="ce_357">

<div class="fs16" >
  <p> ~if $contactEngineObj->contactHandler->getContactInitiator() eq S`
    ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` has accepted your interest.
    ~else` 
    You have accepted ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`'s interest. ~/if`</p>
</div>
<div class="flcet">
  <textarea class="w358CE h90CE textDis" id="draft" name="draft"  disabled></textarea>
</div>
<div class="sp12"></div>
<div class="fl" >
  <p class="fs16">To send a message to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`,</p>
</div>
</div>
<div class="sp12"></div>
<div class="fs16">

</div>
<div clas="wm_ce"></div>

<div><span style="width:337px;">
  ~Messages::getFreeTrialOfferButton([NAVIGATOR=>$NAVIGATOR,"FROMPOST"=>$FROMPOST])`
</span></div>
<div class="sp12"></div>
<div class="fs16">Hurry! Offer valid till&nbsp;<strong>~$contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>
<div class="sp5"></div>
<div  class="fs14"> ~Messages::getFreeTrialOfferLink(["NAVIGATOR"=>$NAVIGATOR,"FROMPOST"=>$FROMPOST,"LINK"=>"Know more","CLASS"=>"b underline"])`</div>
<div class="sp5"></div>
<br />
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
