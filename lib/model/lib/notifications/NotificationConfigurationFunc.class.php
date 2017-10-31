<?php
class NotificationConfigurationFunc
{
	public function checkProfileIfSubscribed($profileid,$channel="")
    {
        $regObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
        $subscribeStatus = $regObj->checkForRegisteredUser($profileid,$channel,"ACTIVATED");
        unset($regObj);
        if($subscribeStatus[0]["ACTIVATED"]=="Y")
            $output = "S";
        else
            $output = "U";
        return $output;
    }

    /*show toggle JSPC notifications layer
    @params : $profileid
    @return : $showLayer(1/0)
    */
    public function showNotificationToggleLayer($profileid)
    {
        $showLayer = 0;
        $browserCheck = $this->browserVersionCheck(BrowserNotificationEnums::$minChromeVersion);
        $channel = MobileCommon::isMobile()?"M":"D";
        $browserNotificationRegObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
        $checkRegUser = $browserNotificationRegObj->checkForRegisteredUser($profileid,$channel,"*");
        unset($browserNotificationRegObj);
        //if($profileid == '3406012'||$channel=="M")  //comment LATER
        {
            if(is_array($checkRegUser)){
                $registeredUser = 1;
            }
            if($browserCheck==true && $registeredUser){
                $showLayer = 1;
            }
       }
       $output = array("notificationStatus"=>$checkRegUser[0]["ACTIVATED"],"showToggleLayer"=>$showLayer);
       return $output;
    }

    /*show enable JSMS/JSPC notifications layer
    @params : $profileid
    @return : $showLayer(1/0)
    */
    public function showEnableNotificationLayer($profileid)
    {
        $showLayer = 0;
        $registeredUser = 0;
        $browserCheck = $this->browserVersionCheck(BrowserNotificationEnums::$minChromeVersion);
        $channel = MobileCommon::isMobile()?"M":"D";
        
        $browserNotificationLayerObj = new MOBILE_API_BROWSER_NOTIFICATION_LAYER();
        $layerData = $browserNotificationLayerObj->getArray($profileid,"PROFILEID");
        if($layerData){
            $countChannel = ($channel == 'M')?"MOBILE_COUNT":"DESKTOP_COUNT";
            $lastLoginChannel = ($channel == 'M')?"MOBILE_LAST_CLICK":"DESKTOP_LAST_CLICK";
            $channelShowLayer = ($channel == 'M')?"MOBILE_LAYER":"DESKTOP_LAYER";
            $currentDate = strtotime(date('Y-m-d'));
            $dateDiff = ($currentDate - strtotime($layerData[0][$lastLoginChannel]))/(60*60*24);
            //if($layerData[0][$countChannel] >=5 || ($dateDiff < 7) || ($layerData[0][$channelShowLayer] == 'Y') ){
	    if(($dateDiff < 2) || ($layerData[0][$channelShowLayer] == 'Y')){	
                $registeredUser = 1;
            }
	    if($layerData[0][$channelShowLayer] == 'Y')
		$notificationEnabled =1;	
        }
        elseif($browserCheck){
            $browserNotificationLayerMasterObj = new MOBILE_API_BROWSER_NOTIFICATION_LAYER();
            unset($paramsArr);
            $paramsArr['PROFILEID'] = $profileid;
            $paramsArr['MOBILE_COUNT'] = 0;
            $paramsArr['DESKTOP_COUNT'] = 0;
            $paramsArr['MOBILE_LAST_CLICK'] = date('Y-m-d');
            $paramsArr['DESKTOP_LAST_CLICK'] = date('Y-m-d');
            $paramsArr['MOBILE_LAYER'] = 'N';
            $paramsArr['DESKTOP_LAYER'] = 'N';
            if($channel = 'M'){
                $paramsArr['MOBILE_LAST_CLICK'] = "0000-00-00";
                $paramsArr['DESKTOP_LAST_CLICK'] = "0000-00-00";
            }
            else{
                $paramsArr['DESKTOP_LAST_CLICK'] = "0000-00-00";
                $paramsArr['MOBILE_LAST_CLICK'] = "0000-00-00";
            }
            $browserNotificationLayerMasterObj->insert($paramsArr);
        }
        unset($browserNotificationRegObj);
        
        if($browserCheck==true && !$registeredUser){
        	$showLayer = 1;
        }
        return array("showLayer"=>$showLayer,"enabled"=>$notificationEnabled);	
        //return $showLayer;
    }

    /*check brower is chrome and version is higher than specified
    @params : $minVersion
    @return : true/false
    */
    public function browserVersionCheck($minVersion)
    {
        $browserCheck = new BrowserCheck();
        $browserArr = $browserCheck->getBrowser();
        $version = explode(".",$browserArr['version']);
        $name = $browserArr['name']; 
        if($version[0] >= $minVersion && preg_match('/Chrome/i',$name))
            return true;
        else
            return false;
    }
    
    


}
?>
