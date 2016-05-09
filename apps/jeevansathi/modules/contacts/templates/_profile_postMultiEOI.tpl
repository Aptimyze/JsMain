<div class="sp5"></div>
<div class="sp15"></div>
 <div class="sprite-new ico-right fl">
 </div>
 <span style="margin-left:5px" class="fs16">You have successfully expressed interest in ~$contactEngineObj->getComponent()->numberOfProfiles` members.
 </span>
<div class="sp15"></div>
~if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState() eq FTOStateTypes::FTO_ELIGIBLE`
  ~include_partial("contacts/profile_eoi_cni_post_multi", ["contactEngineObj" => $contactEngineObj])`
~else if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState() eq FTOStateTypes::FTO_ACTIVE`
  ~include_partial("contacts/profile_eoi_dni_post_multi", ["contactEngineObj" => $contactEngineObj])`
~else if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState() eq FTOStateTypes::FTO_EXPIRED or $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState() eq FTOStateTypes::NEVER_EXPOSED or $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState() eq FTOStateTypes::DUPLICATE` 
  ~include_partial("contacts/profile_eoi_eni_post_multi", ["contactEngineObj" => $contactEngineObj])`
~else if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState() eq FTOStateTypes::PAID`
  ~include_partial("contacts/profile_eoi_paid_post_multi", ["contactEngineObj" => $contactEngineObj])`
~/if`
