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
  public function __construct($notificationKey,$noOfScripts,$currentScript)
  {
		$this->notificationKey = $notificationKey;
		$this->noOfScripts = $noOfScripts;
		$this->currentScript = $currentScript;
                $this->notificationObj = new AppNotification;
                $valueArray['STATUS']="Y";
                $valueArray['NOTIFICATION_KEY']=$this->notificationKey;
                $notificationSettings = $this->notificationObj->getNotificationSettings($valueArray);
                $this->notificationObj->setNotifications($notificationSettings);

		// New function
		$notificationDetail =$this->notificationObj->getNotificationDetail($valueArray);
		$this->osType =$notificationDetail[0]['OS_TYPE'];
  }
    public function scheduleNotificationsForKey()
  {
	  $appProfilesHandlerObj = new AppProfilesHandler;
	  $numberOfLoopsExecuted = 0;
	  while(1)
	  {
		  $appProfiles = array();
		  $restartLooper = false;
		  if($numberOfLoopsExecuted==0)
			$restartLooper = true;
		  $appProfiles = $appProfilesHandlerObj->getProfiles($this->notificationKey,$numberOfProfilesPerLoop=100,$restartLooper,$this->noOfScripts,$this->currentScript,$this->osType);
		  if(is_array($appProfiles))
		  {
			  $notificationData = $this->notificationObj->getNotificationData($appProfiles,$this->notificationKey);
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
