~if $contactEngineObj`
~if $contactEngineObj->contactHandler->getPageSource() neq 'search' && MobileCommon::isMobile() neq 1`
<div class="sp8" style="border-bottom:1px solid #ccc; width:358px"></div>
~if $p`
  <p>&nbsp;</p>
  <p>&nbsp;</p>
~/if`
<div style="width:358px">~Messages::getNotInterestedLink(["ONCLICK"=>"onNotInterestDetail('~Messages::getUserChecksum()`')"])`
</div>
~/if`
~/if`
