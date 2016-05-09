<?php
class NotificationFunctions
{
	public static function getdppMatchNotificationCalcDate($profileid)
	{
		$scheduledAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
		$valArr['NOTIFICATION_KEY']="MATCHALERT";
		$valArr['PROFILEID']= $profileid;
		$notificationData = $scheduledAppNotificationsObj->getArray($valArr,'','',"SCHEDULED_DATE");
		$date  = $notificationData[0]['SCHEDULED_DATE'];
		list($y,$m,$d)=explode("-",$date);
		$dateStr = mktime(0, 0, 0, $m, $d-7, $y);
		return $finalDate = date("Y-m-d",$dateStr)."T".date("H:i:s",$dateStr)."Z";
	}
	public static function manageGcmRegistrationid($registrationid,$profileid='',$appVersion='',$osVersion='',$deviceBrand='',$deviceModel='')
	{
		if(!$registrationid)
			return false;
                $isAppType =MobileCommon::isApp();
                if($isAppType=='A')
                        $osType = "AND";
                else if($isAppType=='I')
                        $osType = "IOS";

		$registrationIdObj = new MOBILE_API_REGISTRATION_ID;
		$valArr['REG_ID']=$registrationid;
		$registrationIdData = $registrationIdObj->getArray($valArr,'','','*');
		if(is_array($registrationIdData))
		{
			if($registrationIdData[0]['PROFILEID']!=$profileid)
				$registrationIdObj->updateProfileId($registrationid,$profileid);
		}
		else{
			$appVersion =floor($appVersion*100)/100;
			$registrationIdObj->insert($registrationid,$profileid,$osType,$appVersion,$osVersion,$deviceBrand,$deviceModel);
		}
		return true;
	}
	public static function updateVersionDetails($request){
	        $registrationid = $request->getParameter('registrationid');
	        $deviceBrand = $request->getParameter('DEVICE_BRAND');
	        $deviceModel = $request->getParameter('DEVICE_MODEL');
	        $osVersion =$request->getParameter('CURRENT_VERSION');
		$appVersion =$request->getParameter('API_APP_VERSION');
		$appVersion =floor($appVersion*100)/100;
	        $registationIdObj = new MOBILE_API_REGISTRATION_ID;
		if($registrationid)
		        $registationIdObj->updateVersion($registrationid,$appVersion,$osVersion,$deviceBrand,$deviceModel);
	}
	public static function settingStatus($registrationid,$profileid='')
	{
		if(!$registrationid)
			return false;
		$osType = "AND";
		$registrationIdObj = new MOBILE_API_REGISTRATION_ID;
		$valArr['REG_ID']=$registrationid;
		$registrationIdData = $registrationIdObj->getArray($valArr,'','','*');
		return $registrationIdData[0]['NOTIFICATION_STATUS'];
	}
}
