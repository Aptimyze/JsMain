<?php

/**
 * Description of GCM
 *
 */

class NotificationSender
{
    function __construct() 
    {
    }

    /**
     * Sending Push Notification
     */
    public function sendNotifications($profileDetails,$regIds='') 
    {
	if(is_array($profileDetails))
	{
		$notificationLogObj = new MOBILE_API_NOTIFICATION_LOG;
		$notificationLogAtnObj = new MOBILE_API_NOTIFICATION_LOG_ATN;
		$notificationLogEtnObj = new MOBILE_API_NOTIFICATION_LOG_ETN;
		foreach($profileDetails as $profileid=>$details)
		{
			$osType = "";
			if(!isset($details))
				continue;
			if(!is_array($regIds)){
				$regIds = $this->getRegistrationIds($profileid,$profileDetails[$profileid]['OS_TYPE']);
			}
			if(is_array($regIds))
			{
				if(is_array($regIds[$profileid]["AND"]))
				{
					$osType = "AND";
					if($details['NOTIFICATION_KEY']=='ATN')
						$notificationLogAtnObj->insert($profileid,$details['NOTIFICATION_KEY'],$details['MSG_ID'],NotificationEnums::$PENDING,$osType);
					if($details['NOTIFICATION_KEY']=='ETN')
						$notificationLogEtnObj->insert($profileid,$details['NOTIFICATION_KEY'],$details['MSG_ID'],NotificationEnums::$PENDING,$osType);
					$notificationLogObj->insert($profileid,$details['NOTIFICATION_KEY'],$details['MSG_ID'],NotificationEnums::$PENDING,$osType);
					$engineObject =NotificationEngineFactory::geNotificationEngineObject('GCM');
					$result = $engineObject->sendNotification($regIds[$profileid]["AND"], $details,$profileid);
				}
				if(is_array($regIds[$profileid]["IOS"]))
                                {
					$osType = "IOS";
			                $details['PHOTO_URL'] = 'D'; //Added here so that any image url generated is sent to android and not to IOS
					if($details['NOTIFICATION_KEY']=='ATN')
						$notificationLogAtnObj->insert($profileid,$details['NOTIFICATION_KEY'],$details['MSG_ID'],NotificationEnums::$PENDING,$osType);
					if($details['NOTIFICATION_KEY']=='ETN')
						$notificationLogEtnObj->insert($profileid,$details['NOTIFICATION_KEY'],$details['MSG_ID'],NotificationEnums::$PENDING,$osType);
					$notificationLogObj->insert($profileid,$details['NOTIFICATION_KEY'],$details['MSG_ID'],NotificationEnums::$PENDING,$osType);
					$engineObject =NotificationEngineFactory::geNotificationEngineObject($osType);
					$engineObject->sendNotification($regIds[$profileid]['IOS'], $details,$profileid);
                                }
			}
			// logging of Notification Messages 
			$key            =$details['NOTIFICATION_KEY'];
			$msgId          =$details['MSG_ID'];
			$message        =$details['MESSAGE'];
			$title          =$details['TITLE'];
			$notificationMsgLog =new MOBILE_API_NOTIFICATION_MESSAGE_LOG();
			$notificationMsgLog->insert($key,$msgId,$message,$title);
			// end
			unset($regIds);
		}
	}

    }
    public function getRegistrationIds($profileid,$osType,$notificationKey='')
    {
	$valArr['PROFILEID']=$profileid;
	if($osType != "ALL")
		$valArr['OS_TYPE']=$osType;
	$valArr['NOTIFICATION_STATUS'] = "Y";
    
    if(array_key_exists($notificationKey, NotificationEnums::$appVersionCheck)){
        $appVersion = NotificationEnums::$appVersionCheck[$notificationKey];
    }
    else{
        $appVersion = NotificationEnums::$appVersionCheck["DEFAULT"];
    }
    if($osType=='AND' || $osType=='ALL')
        $appVersionAnd =$appVersion['AND'];
    if($osType=='IOS' || $osType=='ALL')
        $appVersionIos =$appVersion['IOS'];
    
    //$appVersion = NotificationEnums::$appVersionCheck["DEFAULT"];
    //$appVersionAnd =$appVersion['AND'];
    //$appVersionIos =$appVersion['IOS'];
    
	$registrationIdObj = new MOBILE_API_REGISTRATION_ID('newjs_masterRep');
	$registrationIdData = $registrationIdObj->getArray($valArr,'','','*');
	if(is_array($registrationIdData))
	{
		foreach($registrationIdData as $k=>$v){
			$os_type 	=$v['OS_TYPE'];
			$appVersion 	=$v['APP_VERSION'];
			if(($os_type=='AND' && $appVersion>=$appVersionAnd) || ($os_type=='IOS' && $appVersion>=$appVersionIos))
				$regIdArr[$v['PROFILEID']][$v['OS_TYPE']][]=$v['REG_ID'];
		}
		return $regIdArr;
	}
	return false;
    }

    public function filterProfilesBasedOnNotificationCount($profiledetailsArr,$notificationKey)
    {
    	$profileidArr = array_keys($profiledetailsArr);
	$profileidStr = implode(",",$profileidArr);
    	$countObj = new MOBILE_API_SENT_NOTIFICATIONS_COUNT();
	$scheduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS();
	$count_arr =  $countObj->getCountGroupByProfile($profileidStr);
    	$idArr = array();
    	foreach($profileidArr as $key=>$profileid)
    	{
    		$count = $count_arr[$profileid];
    		if($count>=0 && $count<NotificationEnums::$scheduledNotificationsLimit)
    		{
    			$countObj->incrementNotificationsCountForProfile($profileid,$count+1);
    		}
    		else if($count==NotificationEnums::$scheduledNotificationsLimit)
    		{
                        $scheduledAppNotificationObj->updateSuccessSent(NotificationEnums::$CANCELLED,$profiledetailsArr[$profileid]['MSG_ID']);
			unset($profiledetailsArr[$profileid]);
    		}
    	}
	unset($count_arr);
    	unset($profileidArr);
    	unset($countObj);
	unset($scheduledAppNotificationObj);

	return $profiledetailsArr;
     }
    public function filterProfilesBasedOnNotificationCountNew($profiledetailsArr,$notificationKey)
    {
        $profileidArr = array_keys($profiledetailsArr);
        $profileidStr = implode(",",$profileidArr);
        $countObj = new MOBILE_API_SENT_NOTIFICATIONS_COUNT();
        $count_arr =  $countObj->getCountGroupByProfile($profileidStr);
        $idArr = array();
        foreach($profileidArr as $key=>$profileid)
        {
                $count = $count_arr[$profileid];
                if($count>=0 && $count<NotificationEnums::$scheduledNotificationsLimit)
                {
                        $countObj->incrementNotificationsCountForProfile($profileid,$count+1);
                }
                else if($count==NotificationEnums::$scheduledNotificationsLimit)
                {
                        $idArr[] = $profiledetailsArr[$profileid]['ID'];
                        unset($profiledetailsArr[$profileid]);
                }
        }
        unset($count_arr);
        unset($profileidArr);
        unset($countObj);

        if(is_array($idArr) && $idArr)
                {
                        $scheduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS();
                        $scheduledAppNotificationObj->updateNotificationStatus($idArr,$notificationKey,NotificationEnums::$CANCELLED);
                        unset($scheduledAppNotificationObj);
                }
                return $profiledetailsArr;
        }

}
?>
