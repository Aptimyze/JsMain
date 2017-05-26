<?php
/**
 * class AppNotificationScheduler
 * 
 */
class AppNotificationScheduler extends NotificationScheduler
{
  public $notificationObj;
  public $notificationKey;
  public $noOfScripts;
  public $currentScript;
  public function __construct($notificationKey,$noOfScripts,$currentScript,$androidMaxVersion='')
  {
		$this->notificationKey = $notificationKey;
		$this->noOfScripts = $noOfScripts;
		$this->currentScript = $currentScript;
        $this->androidMaxVersion = $androidMaxVersion;
                $this->notificationObj = new AppNotification;
                $valueArray['STATUS']="Y";
                $valueArray['NOTIFICATION_KEY']=$this->notificationKey;
                $notificationSettings = $this->notificationObj->getNotificationSettings($valueArray);
                $this->notificationObj->setNotifications($notificationSettings);

		// New function
		$notificationDetail =$this->notificationObj->getNotificationDetail($valueArray);
		$this->osType =$notificationDetail[0]['OS_TYPE'];
  }
    public function scheduleNotificationsForKey($currentScript=0)
  {
	  // Just Join Check
	  if(in_array($this->notificationKey, NotificationEnums::$notificationTempLogArr)){
	        $tempObj =new NOTIFICATION_NEW_JUST_JOIN_TEMP();
	        $logProfiles = $tempObj->getProfiles($currentScript);
	  }

	  $appProfilesHandlerObj = new AppProfilesHandler;
	  $numberOfLoopsExecuted = 0;
	  while(1)
	  {
		  $appProfiles = array();
		  $restartLooper = false;
		  if($numberOfLoopsExecuted==0)
			$restartLooper = true;
		  $appProfiles = $appProfilesHandlerObj->getProfiles($this->notificationKey,$numberOfProfilesPerLoop=100,$restartLooper,$this->noOfScripts,$this->currentScript,$this->osType,$this->androidMaxVersion);
		  if(is_array($appProfiles))
		  {
			  $notificationData = $this->notificationObj->getNotificationData($appProfiles,$this->notificationKey,'','',$logProfiles,$currentScript);
			  $this->schedule($notificationData);
		  }
		  else
			break;
		  $numberOfLoopsExecuted++;
	  }
  }
  public function schedule($notificationData)
  {
	  if(is_array($notificationData))
	  {
		  foreach($notificationData as $k=>$v)
		  {
			  $insertData[$k]['PROFILEID']=$v['SELF']['PROFILEID'];
			  $insertData[$k]['NOTIFICATION_KEY']=$v['NOTIFICATION_KEY'];
			  $insertData[$k]['MESSAGE']=$v['NOTIFICATION_MESSAGE'];
			  $insertData[$k]['LANDING_SCREEN']=$v['LANDING_SCREEN'];
			  $insertData[$k]['OS_TYPE']=$v['OS_TYPE'];
			  $insertData[$k]['PRIORITY']=$v['PRIORITY'];
			  $insertData[$k]['COLLAPSE_STATUS']=$v['COLLAPSE_STATUS'];
			  $insertData[$k]['TTL']=$v['TTL'];
			  $insertData[$k]['COUNT']=$v['COUNT'];
			  $insertData[$k]['MSG_ID']=$v['MSG_ID'];
			  $insertData[$k]['SENT']='N';	
			  if($v['SELF']['REG_ID']){
			  	  $insertData[$k]['REG_ID']=$v['SELF']['REG_ID'];
			  }
			  else{
			  	$insertData[$k]['REG_ID']="";
			  }
		      $insertData[$k]['PHOTO_URL']=$v['PHOTO_URL'];
			  if($v['NOTIFICATION_KEY']=='VD')
				  $insertData[$k]['TITLE']=$v['NOTIFICATION_MESSAGE_TITLE'];		
			  else
				$insertData[$k]['TITLE']=$v['TITLE'];
              if($v['OTHER_PROFILE_CHECKSUM']){
                $insertData[$k]['PROFILE_CHECKSUM']=$v['OTHER_PROFILE_CHECKSUM'];
              }

				/*$dataSet =$insertData[$k];	
			  	$this->insert($dataSet);
				unset($dataSet);*/		
		  }
		  //print_r($insertData);
		  $scheduledAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
		  $scheduledAppNotificationsObj->insert($insertData);
	  }
  }

  public function insert($dataSet){
        $producerObj = new JsNotificationProduce();
        if(is_array($dataSet))
	    if($producerObj->getRabbitMQServerConnected()){
		$msgdata = FormatNotification::formatPushNotification($dataSet,$dataSet["OS_TYPE"],true);
		$producerObj->sendMessage($msgdata);
	    }
	    else{
		$str = "\nRabbitmq Notification Error Alert: Rabbitmq Server is down.";
		RabbitmqHelper::sendAlert($str,"browserNotification");
	    }
    }
}
?>
