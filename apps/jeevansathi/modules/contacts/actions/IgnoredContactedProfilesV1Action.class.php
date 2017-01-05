<?php

class IgnoredContactedProfilesV1Action extends sfAction{
    
    function execute($request){
        $profileid = $request->getParameter("profileid");
        $ignoredContactedObj = new IgnoredContactedProfiles();
        $profileidList = array('removeProfiles'=>$ignoredContactedObj->getProfileList($profileid,"matchalerts"));
        $apiObj = ApiResponseHandler::getInstance();
        $apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $apiObj->setResponseBody($profileidList);
        $apiObj->generateResponse();
        die;
    }
}

