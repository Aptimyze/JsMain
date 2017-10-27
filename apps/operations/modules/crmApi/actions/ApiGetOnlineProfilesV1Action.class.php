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
        if(is_array($profilesIdArr)){
            $chatLibrary = new ChatLibrary();
            $profileIdArr = array_keys($profilesIdArr);
            $profilesIdStr = implode(",",$profileIdArr);
            $onlineProfiles = $chatLibrary->getIfUserIsOnlineInJSChat($profilesIdStr,1);
        } else{
            $onlineProfiles = array();
        }
        $json_profiles = json_encode($onlineProfiles);
        echo $json_profiles;
        die;
    }
}

?>