<?php

class ApiGetOnlineProfilesV1Action extends sfActions {
    /**
     * Get onlines profiles for the agent
     * @param sfRequest $request A request object
     * @return api response in JSON format
     *
     **/
    function execute($request){
        $profilesIdArr = $request->getParameter('profilesid');
        $profilesIdArr = json_decode($profilesIdArr);
        if(is_array($profilesIdArr)){
            $JsMemcacheObj  =JsMemcache::getInstance();
            $listName       =CommonConstants::ONLINE_USER_LIST;
            $onlineProfiles = $JsMemcacheObj->zScorePipelining($listName,$profilesIdArr);
        }
        if(!is_array($onlineProfiles))
            $onlineProfiles = array();
        $json_profiles = json_encode($onlineProfiles);
        echo $json_profiles;
        die;
    }
}

?>