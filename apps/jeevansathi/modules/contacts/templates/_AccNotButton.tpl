~if $contactEngineObj->contactHandler->getPageSource() eq 'VDP'`
<p>  ~Messages::getAcceptButton()`&nbsp;&nbsp;&nbsp;~Messages::getNotInterestedButton()`</p>
~else if $contactEngineObj->contactHandler->getToBeType() eq 'D'`
<p>  ~Messages::getNotInterestedButton([CLASS=>"fto-btn-green sprite-new cp"])`</p>
~else if $contactEngineObj->contactHandler->getToBeType() eq 'A' && $contactEngineObj->contactHandler->getPageSource() neq 'VDP'`
<p>  ~Messages::getAcceptButton(["VALUE"=>"Accept Interest","CLASS"=>"fto-btn-green sprite-new cp"])`</p>
~/if`
