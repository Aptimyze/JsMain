
<input type="hidden" name="countlogic" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::COUNTLOGIC)`">
<input type="hidden" name="clicksource" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::CLICKSOURCE)`">
<input type="hidden" name="matchalert_mis_variable" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MATCHALERT_MIS_VARIABLE)`">
<input type="hidden" name="CURRENTUSERNAME" value="~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`">
<input type="hidden" name="suggest_profile" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::SUGGEST_PROFILE)`">
<input type="hidden" name="pr_view" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::PR_VIEW)`">
<input type="hidden" name="stype" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::STYPE)`">
<input type="hidden" name="profilechecksum" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::PROFILECHECKSUM)`">
<input type="hidden" name="viewed_profile" id="viewed_profile" value="~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::PROFILECHECKSUM)`">

<input type="hidden" name="page_source" id="page_source" value="~$contactEngineObj->contactHandler->getPageSource()`">

<input type="hidden" name="responseTracking" id="responseTracking" value = "~$contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::RESPONSETRACKING)`">
~if ~$contactEngineObj->messageId` neq ''`
<input type="hidden" name="messageid" id="messageid" value="~$contactEngineObj->messageId`">
~/if`
