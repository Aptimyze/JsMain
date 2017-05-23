<?php

/*
 * This task gets send notification to users who have requested photo on photo upload.
 */

class photoUploadNotifications extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'smsNotification';
    $this->name             = 'photoUploadNotifications';
    $this->briefDescription = 'send Instant Notification Photo Upload';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony smsNotification:photoUploadNotifications] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

	// Added Instant Notification for first photo upload
	
	$ID ='14';
	$lastHandledDateObj =new incentive_LAST_HANDLED_DATE();
	$dateTimeStart =$lastHandledDateObj->getHandledDate($ID);

	$instantNotifObj =new InstantAppNotification('PHOTO_UPLOAD');
	$photo_firstObj =new PHOTO_FIRST();
	$photoProfiles =$photo_firstObj->getProfilesScreenedForNotification($dateTimeStart);
        //$photoProfiles = array(0=>array('PROFILEID'=>924,'ENTRY_DT'=>'2015-03-22'));
	if(is_array($photoProfiles)){
		foreach($photoProfiles as $key=>$data){
			$profileid 	=$data['PROFILEID'];
			$dateTimeSet 	=$data['ENTRY_DT'];
			$InformationTypeAdapterObj =new InformationTypeAdapter('PHOTO_REQUEST_RECEIVED',$profileid);	
			$profiles =$InformationTypeAdapterObj->getProfiles('','');
                        
                        //$profiles = array('658'=>658);
			// send Instant Notification
			foreach($profiles as $key=>$pid){
                                $profileObj1 = Profile::getInstance('', $key);
                                $profileObj2 = Profile::getInstance('', $profileid);
                                $contactsObj = new Contacts($profileObj1,$profileObj2);
                                $ignore=new IgnoredProfiles("newjs_master");
                                
                                $type = $contactsObj->getTYPE();
                                if($type != 'C' && $type != 'D' && $type != 'E'){
                                    if(!$ignore->ifIgnored($profileid,$key,"byMe") && !$ignore->ifIgnored($key,$profileid,"byMe")){
                                        $instantNotifObj->sendNotification($key,$profileid);
                                        requestedPhotoUploadedMail::sendUploadPhotoMail($key,$profileid);
                                    }
                                }
			}
			unset($profiles);
		}
		$lastHandledDateObj->setHandledDate($ID, $dateTimeSet);
	}
  }
}
