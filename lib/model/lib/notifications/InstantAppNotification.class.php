<?php
/**
 * class InstantAppNotification
 * 
 */
class InstantAppNotification
{
  public $notificationObj;
  public $notificationKey;
  public $unlimitedTimeCriteriaKeyArr;
  public function __construct($notificationKey)
  {
	$this->notificationObj = new AppNotification;
	$this->notificationKey = $notificationKey;
	$valueArray['FREQUENCY']="I";
	$valueArray['STATUS']="Y";
	$valueArray['NOTIFICATION_KEY']=$this->notificationKey;
	$this->notificationObj->setNotifications($this->notificationObj->getNotificationSettings($valueArray));
	$this->unlimitedTimeCriteriaKeyArr = array('ACCEPTANCE','MESSAGE_RECEIVED', 'PROFILE_VISITOR','BUY_MEMB','CSV_UPLOAD','PHOTO_UPLOAD');
  }
  public function sendNotification($selfProfile,$otherProfile='', $message='', $exUrl='')
  {
	$notificationSentCount = $this->getNotificationSentCount($selfProfile);
	if(in_array($this->notificationKey, $this->unlimitedTimeCriteriaKeyArr) || $this->notificationObj->notifications['TIME_CRITERIA'][$this->notificationKey]>$notificationSentCount)
	{
		if($selfProfile)
		{
			$notificationDetails = $this->notificationObj->getNotificationData(array("SELF"=>$selfProfile,"OTHER"=>$otherProfile),$this->notificationKey, $message);
			$notificationData = $notificationDetails[0];
			if(is_array($notificationData))
			{
				$profileDetails[$selfProfile]['FREQUENCY']=$notificationData['FREQUENCY'];
				$profileDetails[$selfProfile]['NOTIFICATION_KEY']=$notificationData['NOTIFICATION_KEY'];
				$profileDetails[$selfProfile]['MESSAGE']=$notificationData['NOTIFICATION_MESSAGE'];
				$profileDetails[$selfProfile]['LANDING_SCREEN']=$notificationData['LANDING_SCREEN'];
				$profileDetails[$selfProfile]['OS_TYPE']=$notificationData['OS_TYPE'];
				$profileDetails[$selfProfile]['COLLAPSE_STATUS']=$notificationData['COLLAPSE_STATUS'];
				$profileDetails[$selfProfile]['TTL']=$notificationData['TTL'];
				$profileDetails[$selfProfile]['TITLE']=$notificationData['TITLE'];
				$profileDetails[$selfProfile]['USERNAME']=$notificationData['SELF']['USERNAME'];
				$profileDetails[$selfProfile]['MSG_ID']=$notificationData['MSG_ID'];

                          	if($notificationData['PHOTO_URL']=="O")
                          	{
	                               	$profileObj = new Profile('',$otherProfile);
	                               	$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY");
					$havePhoto =$profileObj->getHAVEPHOTO();
					if($havePhoto=='Y')
					{
						$pictureServiceObj=new PictureService($profileObj);
						$profilePicObj = $pictureServiceObj->getProfilePic();
						if($profilePicObj)
							$thumbNail = $profilePicObj->getThumbailUrl();
					}
                          	}
                          	if($thumbNail)
					$profileDetails[$selfProfile]['PHOTO_URL']=$thumbNail;
                          	else  
					$profileDetails[$selfProfile]['PHOTO_URL']="D";

				if($notificationData['OTHER_PROFILE_CHECKSUM'])
					$profileDetails[$selfProfile]['PROFILE_CHECKSUM']=$notificationData['OTHER_PROFILE_CHECKSUM'];
				
				$notificationSenderObj = new NotificationSender;
				
				// For Pull notification(special clase for profile visitor sent via local also)
				if($notificationData['NOTIFICATION_KEY']=='PROFILE_VISITOR'){
					$profileDetails[$selfProfile]['PROFILEID']=$selfProfile;
					$profileDetails[$selfProfile]['PRIORITY']=$notificationData['PRIORITY'];
					$profileDetails[$selfProfile]['COUNT']=$notificationData['COUNT'];
					$profileDetails[$selfProfile]['SENT']='P';
					$scheduledAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
                  			$profileDetails[$selfProfile]['ID'] = $scheduledAppNotificationsObj->insert($profileDetails);
                  			$profileDetails=$notificationSenderObj->filterProfilesBasedOnNotificationCount($profileDetails,'PROFILE_VISITOR');
				}
				if($notificationData['NOTIFICATION_KEY']=='CSV_UPLOAD'){
					$profileDetails[$selfProfile]['IMG_URL']=$exUrl;
				}
				$notificationSenderObj->sendNotifications($profileDetails);
			}
		}
	}
  }
  private function getNotificationSentCount($profileid)
  {
	$count = 0;
	$dateToday = date("Y-m-d");
	$notificationLogObj = new MOBILE_API_NOTIFICATION_LOG;
	$valueArray['PROFILEID']=$profileid;
	$valueArray['NOTIFICATION_KEY']=$this->notificationKey;
	$greatorThan['SEND_DATE']=$dateToday." 00:00:00";
	$lessThan['SEND_DATE']=$dateToday." 23:59:59";
	$notificationSentDetails = $notificationLogObj->getArray($valueArray,'',$greatorThan,"COUNT(1) AS COUNT",$lessThan);
	if(is_array($notificationSentDetails))
	{
		$count = $notificationSentDetails[0]['COUNT'];
	}
	return $count;
  }

  /*send eoi reminder notification on app
  *@param : $senderName,$receiverProfileid,$reminderMsg
  */
  public function sendReminderInstantAppNotification($senderName,$receiverProfileid,$senderProfileid,$reminderMsg)
  {
    $subject = $senderName." has sent you a reminder. Kindly respond with an 'Accept'/'Decline'.";
    $this->sendNotification($receiverProfileid,$senderProfileid,$subject); 
  }
}
?>
