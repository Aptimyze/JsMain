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
			$appVersion =abs($appVersion*100)/100;
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
		$appVersion =abs($appVersion*100)/100;
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
        public static function registrationIdInsert($profileid='',$registrationid,$appVersion='',$osVersion='',$deviceBrand='',$deviceModel=''){
        	if($profileid){
        	        $maxAlarmTimeObj = new MOBILE_API_MAX_ALARM_TIME('newjs_masterDDL');
        	        $alarmCurrentTimeData = $maxAlarmTimeObj->getArray();
        	        $alarmCurrentTime = $alarmCurrentTimeData[0][MAX_ALARM_TIME];
        	        $alarmTime[$profileid]=alarmTimeManager::getNextTime($alarmCurrentTime,NotificationEnums::$alarmMaxTime,NotificationEnums::$alarmMinTime);
        	        $alarmTimeObj = new MOBILE_API_ALARM_TIME;
        	        $alarmTimeObj->replace($alarmTime);
        	        $maxAlarmTimeObj->updateMaxAlarmTime($alarmTime[$profileid]);
        	}
        	NotificationFunctions::manageGcmRegistrationid($registrationid,$profileid,$appVersion,$osVersion,$deviceBrand,$deviceModel);
        }
        public static function deliveryTrackingHandling($profileid,$notificationKey,$messageId='',$status='',$osType='')
        {
		$scheduledNotificationKey  =NotificationEnums::$scheduledNotificationKey;
		// code execute for Scheduled Notification      
		if(in_array("$notificationKey", $scheduledNotificationKey)){
			$schedduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
			if(!$messageId)
				$schedduledAppNotificationObj->updateSent('',$notificationKey,$status,$profileid);
			else if($messageId)
				$schedduledAppNotificationObj->updateSuccessSent($status,$messageId);
		}
		if($status=='L'){
			$notificationObj =new MOBILE_API_LOCAL_NOTIFICATION_LOG;
			$notificationDelLogObj= new MOBILE_API_NOTIFICATION_LOG;
		}
		else{
			$notificationObj =new MOBILE_API_NOTIFICATION_LOG;
			$notificationDelLogObj= new MOBILE_API_LOCAL_NOTIFICATION_LOG;
		}
		if(!$messageId){
			$notificationObj->updateSentPrev($profileid,$notificationKey,$status);
		}
		else if($messageId && $osType){
			$notificationObj->updateSent($messageId,$status,$osType);
			$notificationDelLogObj->deleteNotification($messageId,$osType);
		}
	}
        public function notificationCheck($request)
        {
                $notificationStop =JsConstants::$notificationStop;
                if((date("H")>='11' && date("H")<='15') || (date("H")>='00' && date("H")<='03'))
                        $notificationStop=1;
                if($notificationStop)
                {
                        $notificationData['notifications'] = '';
                        $notificationData['alarmTime']= '';
                        $data =json_encode($notificationData);
                        return $data;
                }
                else
                        return;
        }
}
