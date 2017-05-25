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
        /*$notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
		$respObj = ApiResponseHandler::getInstance();
                $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $respObj->generateResponse();
                die;
        }*/
	$respObj = ApiResponseHandler::getInstance();
	$notificationStatus = $request->getParameter('notificationStatus');
	$loginData =$request->getAttribute("loginData");
	$profileid =$loginData['PROFILEID'];
	if($profileid)
	{
                $producerObj = new JsNotificationProduce();
                if($producerObj->getRabbitMQServerConnected()){
                        $dataSet =array('profileid'=>$profileid,'status'=>$notificationStatus);
                        $msgdata = FormatNotification::formatLogData($dataSet,'','UPDATE_NOTIFICATION_STATUS_API');
                        $producerObj->sendMessage($msgdata);
                }
		else{
			$mobileApiRegistrationObj = new MOBILE_API_REGISTRATION_ID;
			$mobileApiRegistrationObj->updateNotificationStatus($profileid,$notificationStatus);
		}
	}
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->generateResponse();
	die;
  }
  public function executeRegistrationIdInsertV1(sfRequest $request)
  {
        /*$notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $respObj->generateResponse();
                die;
        }*/
	$respObj = ApiResponseHandler::getInstance();
        $registrationid = $request->getParameter('registrationid');
	$deviceBrand = $request->getParameter('DEVICE_BRAND');
	$deviceModel = $request->getParameter('DEVICE_MODEL');
        $osVersion=$request->getParameter('CURRENT_VERSION');
        $appVersion=$request->getParameter('API_APP_VERSION');
        $loginData =$request->getAttribute("loginData");
        $profileid =$loginData['PROFILEID'];
	
        /*$producerObj = new JsNotificationProduce();
        if($producerObj->getRabbitMQServerConnected()){
        	$dataSet =array('profileid'=>$profileid,'registrationid'=>$registrationid,'appVersion'=>$appVersion,'osVersion'=>$osVersion,'deviceBrand'=>$deviceBrand,'deviceModel'=>$deviceModel);
                $msgdata = FormatNotification::formatLogData($dataSet,'','REGISTRATION_API');
                $producerObj->sendMessage($msgdata);
        }
	else{*/
		NotificationFunctions::registrationIdInsert($profileid,$registrationid,$appVersion,$osVersion,$deviceBrand,$deviceModel);
	//}
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->generateResponse();
	die;
  }

  public function executeDeliveryTrackingV1(sfRequest $request)
  {
	$notificationStop =JsConstants::$notificationStop;
	if($notificationStop){
		$respObj = ApiResponseHandler::getInstance();
	        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	        $respObj->generateResponse();
	        die;
	}
        $respObj = ApiResponseHandler::getInstance();
        $notificationKey = $request->getParameter('notificationKey');
	$notificationType = $request->getParameter('notificationType');
	$messageId = $request->getParameter('messageId');	

	// New parameter
        $currentOSversion       =$request->getParameter('CURRENT_VERSION');
        $apiappVersion          =intval($request->getParameter('API_APP_VERSION'));
        $deviceBrand            =$request->getParameter('DEVICE_BRAND');
        $deviceModel            =$request->getParameter('DEVICE_MODEL');
        $registrationid         =$request->getParameter('registrationid');
	$deviceUpgrade          =$request->getParameter('deviceUpgrade');

	/*if(is_null($deviceUpgrade) || empty($deviceUpgrade))
		$upgradeFlagSet =false;*/

	if($notificationType=="pull")
		$status = NotificationEnums::$LOCAL;
	else
		$status = NotificationEnums::$DELIVERED;
	$osType =MobileCommon::isApp();
        $loginData =$request->getAttribute("loginData");
        $profileid =$loginData['PROFILEID'];
        if($profileid || in_array($notificationKey, NotificationEnums::$loggedOutNotifications))
        {
		// New code
	        if(($apiappVersion>=90 || $deviceUpgrade==true) && $registrationid){
	                $upStatus =NotificationFunctions::deviceUpgradeDetails($registrationid,$apiappVersion,$currentOSversion,$deviceBrand,$deviceModel);
	        }
                $producerObj = new JsNotificationProduce();
                if($producerObj->getRabbitMQServerConnected()){

                        $notificationFunction =new NotificationFunctions();
                        $notificationFunction->appNotificationCountCachng('','','DELIVERY_TRACKING_API');

                        $dataSet =array('profileid'=>$profileid,'notificationKey'=>$notificationKey,'messageId'=>$messageId,'status'=>$status,'osType'=>$osType);
                        $msgdata = FormatNotification::formatLogData($dataSet,'','DELIVERY_TRACKING_API');
                        $producerObj->sendMessage($msgdata);
                }
       		/*else{
       			NotificationFunctions::deliveryTrackingHandling($profileid,$notificationKey,$messageId,$status,$osType);
       		}*/

                // temporary_logging    
                //$fileName ="manoj_chat".$notificationKey.".txt";
                //passthru("echo ' $registrationid $apiappVersion $currentOSversion $deviceBrand $deviceModel ' >>/tmp/$fileName");

                //log the notification click event only for IOS app
                if($osType == "I"){
                    NotificationFunctions::handleNotificationClickEvent(array("profileid"=>$profileid,"messageId"=>$messageId,"notificationKey"=>$notificationKey),$osType);
                }
	}
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	$output = array('deviceUpgradeFlag'=>$upStatus);
	$respObj->setResponseBody($output);
        $respObj->generateResponse();
	die;
  }
  public function executePollV1(sfRequest $request)
  {
	$notifCheck =NotificationFunctions::notificationCheck($request);
	if($notifCheck){
		echo $notifCheck;die;
	}
	$currentOSversion	=$request->getParameter('CURRENT_VERSION');
	$apiappVersion		=intval($request->getParameter('API_APP_VERSION'));
        $deviceBrand 		=$request->getParameter('DEVICE_BRAND');
        $deviceModel 		=$request->getParameter('DEVICE_MODEL');
        $registrationid 	=$request->getParameter('registrationid');
	$loginData 		=$request->getAttribute("loginData");
	$profileid 		=$loginData['PROFILEID'];
	$deviceUpgrade		=$request->getParameter('deviceUpgrade');
	$upStatus		=false;
	
        /*if(is_null($deviceUpgrade) || empty($deviceUpgrade))
                $upgradeFlagSet =true;*/

	if(!$profileid){
                $respObj = ApiResponseHandler::getInstance();
                $respObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
                $respObj->generateResponse();
                die;
	}
	if($apiappVersion>=90 || $deviceUpgrade==true){
		$upStatus =NotificationFunctions::deviceUpgradeDetails($registrationid,$apiappVersion,$currentOSversion,$deviceBrand,$deviceModel);
	}
	$respObj = ApiResponseHandler::getInstance();
        if($profileid)
        {
		$localNotificationObj=new LocalNotificationList();
		$failedDecorator=new FailedNotification($localNotificationObj,$profileid);
		$notifications = $failedDecorator->getNotifications();
		/*$alarmTimeObj = new MOBILE_API_ALARM_TIME('newjs_slave');
		$alarmTime = $alarmTimeObj->getData($profileid);
		$alarmDate = alarmTimeManager::getNextDate($alarmTime);*/
	}
	$alarmDate ='2020-01-01 00:00:00';
	if(!is_array($notifications))
		$notifications =array();	
	$notificationData['notifications'] = $notifications;
	$notificationData['alarmTime']=$alarmDate;
	$notificationData['deviceUpgradeFlag']=$upStatus;

	$osType =MobileCommon::isApp();
	$status ='D';
	if(count($notifications)>0){
		$producerObj = new JsNotificationProduce();
		if($producerObj->getRabbitMQServerConnected())
			$producerObjSet =true;
                if($producerObjSet){
                        foreach($notifications as $key=>$val){
                                $notificationKey =$val['NOTIFICATION_KEY'];
                                $messageId =$val['MSG_ID'];
                                $dataSet1 =array('profileid'=>$profileid,'notificationKey'=>$notificationKey,'messageId'=>$messageId,'status'=>$status,'alarmTime'=>$alarmDate,'osType'=>$osType);
                                $msgdata1 = FormatNotification::formatLogData($dataSet1,'LOCAL_NOTIFICATION_LOG');
                                $producerObj->sendMessage($msgdata1);
                        }
                }
		else{
			$localLogObj= new MOBILE_API_LOCAL_NOTIFICATION_LOG();
			foreach($notifications as $key=>$val){
				$notificationKey =$val['NOTIFICATION_KEY'];
				$messageId =$val['MSG_ID'];
				$localLogObj->insert($profileid,$notificationKey,$messageId,$status,$alarmDate,$osType);
			}
		}
	unset($producerObjSet);
	}
	echo json_encode($notificationData);die;
  }
  
  public function executeNotify(sfWebRequest $request){
      
  }
  
    public function executeInsertChromeIdV1(sfWebRequest $request)
    {
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        /*$notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_ID_INSERT_FAILURE);
                $apiResponseHandlerObj->generateResponse();
                die;
        }*/
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
        $notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_FAILURE);	
                $apiResponseHandlerObj->generateResponse();
                die;
        }
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
        $apiResponseHandlerObj->generateResponse();
        die;
    }
    
    public function executeUpdateNotificationSettingV1($request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        /*$notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_PARAM);
                $apiResponseHandlerObj->generateResponse();
                die;
        }*/
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
        /*$notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_PARAM);
                $apiResponseHandlerObj->generateResponse();
                die;
        }*/
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
    
    public function executeMonitoringNotificationsKeyV1(sfWebRequest $request)
    {
        $notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
                $respObj = ApiResponseHandler::getInstance();
                $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $respObj->generateResponse();
                die;
        }
        $respObj = ApiResponseHandler::getInstance();
        $notificationKey = $request->getParameter('notificationKey');
        if ($notificationKey) {
            $mobApiNotMsgLogObj = new MOBILE_API_NOTIFICATION_MESSAGE_LOG('notification_local111');
            $output = $mobApiNotMsgLogObj->fetchNotificationKeyLatestEntry($notificationKey);
        } else {
            $output = array('error'=>"Please pass param 'notificationKey'");
        }
        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->setResponseBody($output);
        $respObj->generateResponse();
        die;
    }
    
    /* function to get notification subsciption status
    */
    public function executeNotificationSubscriptionStatusV1(sfWebRequest $request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData = $request->getAttribute("loginData");
        $profileId = $loginData["PROFILEID"];
        if($profileId){
            $mobileApiRegistrationObj = new MOBILE_API_REGISTRATION_ID("newjs_masterRep");
            $res = $mobileApiRegistrationObj->checkNotificationSubscriptionStatus($profileId);
            if($res){
                $output['result']= $res;
            }
            else{
                $output['result']= array("notificationStatus"=>"NotFound");   
            }
        }
        else{
            $output = array("error"=>"Profileid not found");
        }
        $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $apiResponseHandlerObj->setResponseBody($output);
        $apiResponseHandlerObj->generateResponse();
        die;
    }

    /* NotificationOpenedTrackingV1 api
    * tracks the opened notification details in MOBILE_API.NOTIFICATION_OPENED_TRACKING table
    * @params: $request
    */
    public function executeNotificationOpenedTrackingV1(sfRequest $request)
    {
        $notificationStop =JsConstants::$notificationStop;
        if($notificationStop){
            $respObj = ApiResponseHandler::getInstance();
            $respObj->setHttpArray(ResponseHandlerConfig::$PEAK_LOAD_FAILURE);
            $respObj->generateResponse();
            die;
        }
        $respObj = ApiResponseHandler::getInstance();
        $notificationKey = $request->getParameter('notificationKey');
        $messageId = $request->getParameter('messageId');
        $loginData =$request->getAttribute("loginData");
        $profileid = ($loginData['PROFILEID'] ? $loginData['PROFILEID'] : null);
        $osType = MobileCommon::isApp();
        if($osType == null){
            $webOs = MobileCommon::isAppWebView();
            if($webOs == "A"){
                $osType = "A";
            }
        }
        try{
            if($osType == null || $osType == ""){
                $respObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_CHANNEL);
                $respObj->setResponseBody(array("trackingDone"=>false));
            }
            else if($messageId && $notificationKey){
                if(is_numeric($messageId)){
                    $dataSet = array('PROFILEID'=>$profileid,'MESSAGE_ID'=>$messageId,'NOTIFICATION_KEY'=>$notificationKey,'CLICKED_DATE'=>date('Y-m-d H:i:s'),'CHANNEL'=>$osType);
                    //print_r($dataSet);
                    $producerObj = new JsNotificationProduce();
                    if($producerObj->getRabbitMQServerConnected()){     //flow with rabbitmq
                        $msgdata = FormatNotification::formatLogData($dataSet,'','NOTIFICATION_OPENED_TRACKING_API');
                        //echo "rabbitmq flow";
                        //print_r($msgdata);
                        $producerObj->sendMessage($msgdata);
                    }
                    else{  
                        //echo "without rabbitmq";                        //flow without rabbitmq
                        NotificationFunctions::logNotificationOpened($dataSet);
                    }
                    $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                    $respObj->setResponseBody(array("trackingDone"=>true));
                    
                }
                else{
                    $respObj->setHttpArray(ResponseHandlerConfig::$BROWSER_NOTIFICATION_INVALID_PARAM);
                    $respObj->setResponseBody(array("trackingDone"=>false));
                }
            }
            else{
                $respObj->setHttpArray(ResponseHandlerConfig::$LOGIN_FAILURE_MISSING);
                $respObj->setResponseBody(array("trackingDone"=>false)); 
            }
            $respObj->generateResponse();
            die;
        }
        catch(Exception $e){
            $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
            $respObj->setResponseBody(array("trackingDone"=>false));
            $respObj->generateResponse();
            die;
        }
       
  }
}
