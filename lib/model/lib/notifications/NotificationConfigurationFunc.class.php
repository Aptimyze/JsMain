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
            if($browserCheck==true && !$registeredUser){
                $showLayer = 1;
            }
       }
       return $showLayer;
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