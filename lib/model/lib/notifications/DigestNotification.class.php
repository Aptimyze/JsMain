<?php
/**
 * class InstantAppNotification
 * 
 */
class DigestNotification
{
  public $notificationObj;
  public $notificationKey;
 
  public function __construct($notificationKey)
  {
    $this->notificationObj = new AppNotification;
    $this->notificationKey = $notificationKey;
    $valueArray['FREQUENCY']="I";
    $valueArray['STATUS']="Y";
    $valueArray['NOTIFICATION_KEY']=$this->notificationKey;
    $this->notificationObj->setNotifications($this->notificationObj->getNotificationSettings($valueArray));
  }

  /*function to fetch digest notification data
  * @params: $selfProfile,$otherProfile="",$count=""
  *@return : $notificationDetails
  */
  public function fetchNotificationData($selfProfile,$otherProfile="",$count="")
  {
    $notificationDetails = $this->notificationObj->getNotificationData(array("SELF"=>$selfProfile,"OTHER"=>$otherProfile),$this->notificationKey,'',$count);
    if($notificationDetails && is_array($notificationDetails))
        return $notificationDetails[0];
    else
        return null;
  }

  /*function to send digest notifications
  * @params: $selfProfile,$otherProfile="",$notificationData
  *@return : none
  */
  public function sendNotification($selfProfile,$otherProfile="",$notificationData)
  {
	if(is_array($notificationData))
	{
        //map profile details
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
        $profileDetails[$selfProfile]['PHOTO_URL'] = $notificationData['PHOTO_URL'];
		if($notificationData['OTHER_PROFILE_CHECKSUM'])
			$profileDetails[$selfProfile]['PROFILE_CHECKSUM']=$notificationData['OTHER_PROFILE_CHECKSUM'];
        //print_r($profileDetails);
        //send notification
		$notificationSenderObj = new NotificationSender;
		$notificationSenderObj->sendNotifications($profileDetails);
        unset($notificationSenderObj);
	}
  }
}
?>
