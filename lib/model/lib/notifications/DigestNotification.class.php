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

  public function fetchNotificationData($selfProfile,$count="")
  {
    $notificationDetails = $this->notificationObj->getNotificationData(array("SELF"=>$selfProfile,"OTHER"=>''),$this->notificationKey,'',$count);
    return $notificationDetails[0];
  }

  public function sendNotification($selfProfile,$notificationData)
  {
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
        print_r($profileDetails);die;
		//$notificationSenderObj = new NotificationSender;
		//$notificationSenderObj->sendNotifications($profileDetails);
	}
  }
}
?>
