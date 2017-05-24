~if $contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() eq "Y"`
<div class="ico-info"></div>
<span style="margin-left:5px" class="fs14">
Your Expression of interest will be delivered once your profile is live.</span>
~else`
~if MobileCommon::isMobile() eq 1`
<p>
~else`
<div class="grn_tk sprite lf"></div>
<p class="lf" >
        <span style="margin-left:5px" class="fs14">
                <span style="color:#33b300" class="b">Congratulations !
                </span>
                ~/if`
                ~if $contactEngineObj->contactHandler->getToBeType() eq R`
                You have successfully sent reminder to ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`
                ~else`
                You have successfully expressed interest in ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()`
                ~/if`
                ~if MobileCommon::isMobile() eq 1`
                </p>
                ~else`
        </span>
        <div class="sp15"></div>
        <div class="sp5"></div>
~/if`
~/if`
