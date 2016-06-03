<?php

/**
 * notification actions.
 *
 * @package    jeevansathi
 * @subpackage notification
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class notificationActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  public function executeUpdateNotificationStatusV1(sfWebRequest $request)
  {
	$respObj = ApiResponseHandler::getInstance();
	$registrationid = $request->getParameter('registrationid');
	$notificationStatus = $request->getParameter('notificationStatus');
	if($request->getAttribute("loginData"))
	{
		$loggedInProfileObj=LoggedInProfile::getInstance('newjs_master');
		$profileid= $loggedInProfileObj->getPROFILEID();
		$mobileApiRegistrationObj = new MOBILE_API_REGISTRATION_ID;
		$mobileApiRegistrationObj->updateNotificationStatus($registrationid,$profileid,$notificationStatus);
	}
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->generateResponse();
	die;
  }
  public function executeRegistrationIdInsertV1(sfRequest $request)
  {
	$respObj = ApiResponseHandler::getInstance();
        $registrationid = $request->getParameter('registrationid');
	$deviceBrand = $request->getParameter('DEVICE_BRAND');
	$deviceModel = $request->getParameter('DEVICE_MODEL');
        $osVersion=$request->getParameter('CURRENT_VERSION');
        $appVersion=$request->getParameter('API_APP_VERSION');
        $loginData =$request->getAttribute("loginData");
        $profileid =$loginData['PROFILEID'];
	
	if($profileid)
	{
		//$loggedInProfileObj=LoggedInProfile::getInstance('newjs_master');
		//$profileid= $loggedInProfileObj->getPROFILEID();
		$maxAlarmTimeObj = new MOBILE_API_MAX_ALARM_TIME;
		$alarmCurrentTimeData = $maxAlarmTimeObj->getArray();
		$alarmCurrentTime = $alarmCurrentTimeData[0][MAX_ALARM_TIME];
		$alarmTime[$profileid]=alarmTimeManager::getNextTime($alarmCurrentTime,NotificationEnums::$alarmMaxTime,NotificationEnums::$alarmMinTime);
		$alarmTimeObj = new MOBILE_API_ALARM_TIME;
		$alarmTimeObj->replace($alarmTime);
		$maxAlarmTimeObj->updateMaxAlarmTime($alarmTime[$profileid]);
	}
	$done = NotificationFunctions::manageGcmRegistrationid($registrationid,$profileid,$appVersion,$osVersion,$deviceBrand,$deviceModel);
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->generateResponse();
	die;
  }

  public function executeDeliveryTrackingV1(sfRequest $request)
  {
        $respObj = ApiResponseHandler::getInstance();
        $notificationKey = $request->getParameter('notificationKey');
	$notificationType = $request->getParameter('notificationType');
	$messageId = $request->getParameter('messageId');	
	if($notificationType=="pull")
		$status = NotificationEnums::$LOCAL;
	else
		$status = NotificationEnums::$DELIVERED;
	$osType =MobileCommon::isApp();
        $loginData =$request->getAttribute("loginData");
        $profileid =$loginData['PROFILEID'];
        if($profileid)
        {
                //$loggedInProfileObj=LoggedInProfile::getInstance('newjs_master');
                //$profileid= $loggedInProfileObj->getPROFILEID();
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

                // temporary_logging    
                //$fileName ="manoj_".$notificationKey.".txt";
                //passthru("echo ' $profileid $status ' >>/tmp/$fileName");
	}
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->generateResponse();
	die;
  }
  public function executePollV1(sfRequest $request)
  {
	$currentOSversion	=$request->getParameter('CURRENT_VERSION');
	$apiappVersion		=intval($request->getParameter('API_APP_VERSION'));
        $deviceBrand 		=$request->getParameter('DEVICE_BRAND');
        $deviceModel 		=$request->getParameter('DEVICE_MODEL');
        $registrationid 	=$request->getParameter('registrationid');
	$loginData 		=$request->getAttribute("loginData");
	$profileid 		=$loginData['PROFILEID'];

	/*if($request->getAttribute("loginData")){
	        $loggedInProfileObj=LoggedInProfile::getInstance('newjs_master');
	        $profileid= $loggedInProfileObj->getPROFILEID();
	}*/
	$localLogObj= new MOBILE_API_LOCAL_NOTIFICATION_LOG();
	$localLogObj->addLog($registrationid,$apiappVersion, $currentOSversion,$profileid,$deviceBrand,$deviceModel);
	if(!$profileid){
                $respObj = ApiResponseHandler::getInstance();
                $respObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
                $respObj->generateResponse();
                die;
	}
	/*$registationIdObj = new MOBILE_API_REGISTRATION_ID('newjs_slave');
	$notificationState =$registationIdObj->getNotificationState($registrationid);
	unset($registationIdObj);*/
	$registationIdObj = new MOBILE_API_REGISTRATION_ID();
	$registationIdObj->updateVersion($registrationid,$apiappVersion,$currentOSversion,$deviceBrand,$deviceModel);
	$respObj = ApiResponseHandler::getInstance();
        if($profileid)
        {
		//if($notificationState){
			$localNotificationObj=new LocalNotificationList();
			$failedDecorator=new FailedNotification($localNotificationObj,$profileid);
			$notifications = $failedDecorator->getNotifications();
		//}
		$alarmTimeObj = new MOBILE_API_ALARM_TIME('newjs_slave');
		$alarmTime = $alarmTimeObj->getData($profileid);
		$alarmDate = alarmTimeManager::getNextDate($alarmTime);
	}
	$notificationData['notifications'] = $notifications;
	$notificationData['alarmTime']=$alarmDate;

	//$scheduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;	
	$osType =MobileCommon::isApp();
	$status ='D';
	if(count($notifications)>0){
		foreach($notifications as $key=>$val){
			$notificationKey =$val['NOTIFICATION_KEY'];
			$messageId =$val['MSG_ID'];
                        //$scheduledAppNotificationObj->updateSuccessSent($status,$messageId);
			$localLogObj->insert($profileid,$notificationKey,$messageId,$status,$alarmDate,$osType);
		}
	}
	echo json_encode($notificationData);die;
  }
  
  public function executeNotify(sfWebRequest $request){
      
  }
  
    public function executeInsertChromeIdV1(sfWebRequest $request)
    {
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData = $request->getAttribute("loginData");
        $profileId = $loginData['PROFILEID'];
        if($profileId){
            $regId = $request->getParameter('regId');
            $browserRegObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
            if($regId && $browserRegObj)
            {
                $paramsArr["REG_ID"] = $regId;
                $paramsArr["PROFILEID"] = $profileId;
                $paramsArr["CHANNEL"] = MobileCommon::isMobile()?"M":"D";
                $paramsArr["ENTRY_DT"] = date("Y-m-d H:i:s");
                $paramsArr["ACTIVATED"] = "Y";
                $paramsArr["USER_AGENT"] = $_SERVER['HTTP_USER_AGENT'];
                $res = $browserRegObj->insertRegistrationDetails($paramsArr);
                unset($paramsArr);
                if($res){
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_ID_INSERT_SUCCESS);
                }
                else{
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_ID_INSERT_FAILURE);
                }
            }
            else{
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_ID_INVALID_PARAM);
            }
        }
        else{
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
        }
        $apiResponseHandlerObj->generateResponse();
        die;
    }
    
    public function executeGetNotificationV1($request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $regId = $request->getParameter('regId');
        $browserNotificationObj = new MOBILE_API_BROWSER_NOTIFICATION();
        $notifications = $browserNotificationObj->getNotification($regId);

        if($notifications){
            $browserNotificationObj->updateEntryDetails("ID", $notifications["ID"],array("SENT_TO_CHANNEL" =>"Y","REQUEST_DT"=>date('Y-m-d H:i:s')));
            $response = array('title' => $notifications["TITLE"],
                          'body' => $notifications["MESSAGE"],
                          'icon' => $notifications["ICON"],
                          'tag' => $notifications["TAG"],
                          'regId' => $notifications["REG_ID"],
                          'url'=> $notifications["LANDING_ID"]);
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_SUCCESS);
            $apiResponseHandlerObj->setResponseBody($response);
        }
        else{
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_FAILURE);
        }
        /*
        $response = array('title' => 'Jeevansathi.com - Match Alert',
                          'body' => 'Pooja has sent you an interest. Click here to view her profile',
                          'icon' => 'http://mediacdn.jeevansathi.com/2575/18/51518499-1454658632.jpeg',
                          //'tag' => "MA",
                          'tag' => "MA",
                          'regId' => $regId,
                          'url'=> "http://www.jeevansathi.com/profiles/ZWRS3785");
         */
//        $response = array('title' => 'Jeevansathi.com: Chat request',
//                          'body' => 'Simran has sent you a chat request. Click here to subscribe',
//                          'icon' => 'http://mediacdn.jeevansathi.com/1831/6/36626927-1438947150.jpeg',
//                          //'tag' => "MA",
//                          'tag' => "CR",
//                          'regId' => $regId,
//                          'url'=> "http://www.jeevansathi.com/profiles/ZXWA1376");
        $apiResponseHandlerObj->generateResponse();
        die;
    }
    
    public function executeUpdateNotificationSettingV1($request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $channel = MobileCommon::isMobile()?"M":"D";
        $loginData = $request->getAttribute("loginData");
        $profileId = $loginData["PROFILEID"];
        $status = $request->getParameter('status');
        if(MobileCommon::isMobile()){
            if($status == 'Y' || $status == 'N'){
                $browserNotificationObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
                $browserNotificationObj->updateActivationStatus($profileId, $status,$channel);
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_SUCCESS);
            }
            else{
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_PARAM);
            }
        }
        else{
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_CHANNEL);
        }
        $apiResponseHandlerObj->generateResponse();
        die;
    }
    
    public function executeNotificationLayerSettingsV1($request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $channel = MobileCommon::isMobile()?"M":"D";
        $loginData = $request->getAttribute("loginData");
        $profileId = $loginData["PROFILEID"];
        $active = $request->getParameter("active");
        if($active == 'Y' || $active == 'N'){
            if($channel == 'M'){
                $paramsArr['MOBILE_LAST_CLICK'] = date('Y-m-d');
                $paramsArr['MOBILE_COUNT'] = "MOBILE_COUNT";
                $paramsArr["MOBILE_LAYER"] = $active;
            }
            else{
                $paramsArr['DESKTOP_LAST_CLICK'] = date('Y-m-d');
                $paramsArr['DESKTOP_COUNT'] = "DESKTOP_COUNT";
                $paramsArr["DESKTOP_LAYER"] = $active;
            }
            $notificationLayerObj = new MOBILE_API_BROWSER_NOTIFICATION_LAYER();
            $notificationLayerObj->updateEntryDetails("PROFILEID", $profileId, $paramsArr);
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_SUCCESS);
        }
        else{
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_PARAM);
        }
        $apiResponseHandlerObj->generateResponse();
        die;
    }

   
}
