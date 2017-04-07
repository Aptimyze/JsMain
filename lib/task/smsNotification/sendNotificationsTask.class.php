<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class sendNotifications extends sfBaseTask
{
    var $scheduledAppNotificationObj,$notificationSenderObj,$notificationKey;
  protected function configure()
  {

    $this->namespace        = 'smsNotification';
    $this->name             = 'sendNotifications';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony smsNotification:sendNotifications NOTIFICATION_KEY] 
EOF;
$this->addArguments(array(
      new sfCommandArgument('notificationKey', sfCommandArgument::REQUIRED, 'My argument')
        ));

$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);
	$notificationStop =JsConstants::$notificationStop;
        if($notificationStop)
        	die('successfulDie');

        $this->notificationKey = $arguments["notificationKey"]; // NEW / EDIT
	$this->scheduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS('newjs_masterRep');
	$this->scheduledAppNotificationUpdateSentObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
	$maxIdData = $this->scheduledAppNotificationObj->getArray("","","","max(ID) as maxId");
	$this->maxId = $maxIdData[0][maxId];
	$this->notificationSenderObj = new NotificationSender($this->notificationKey);
	$this->doneTillId = 1;
        while($this->doneTillId<=$this->maxId)
        {
                if(is_array($details = $this->getDetails()))
                {

			foreach($details as $k=>$v)
			{
				$profileDetails[$v['PROFILEID']]=$v;
				$idArr[] = $v['ID'];
			}
			
			//filter profiles based on notification count
			if(in_array($this->notificationKey,NotificationEnums::$scheduledNotificationPriorityArr))
				$filteredProfileDetails = $this->notificationSenderObj->filterProfilesBasedOnNotificationCountNew($profileDetails,$this->notificationKey);
			else
				$filteredProfileDetails = $profileDetails;
			unset($profileDetails);
			$this->sendPushNotifications($filteredProfileDetails,$idArr);
			unset($details);
			unset($filteredProfileDetails);
			unset($idArr);
		}
		if($this->doneTillId>=$this->maxId)
		{
			$maxIdData = $this->scheduledAppNotificationObj->getArray("","","","max(ID) as maxId");
			$this->maxId = $maxIdData[0][maxId];
		}
        }
  }
  private function sendPushNotifications($profileDetails,$idArr)
  {
	$status =0;//CommonUtility::hideFeaturesForUptime();
	if($status || JsConstants::$hideUnimportantFeatureAtPeakLoad >= 9)
		successfullDie();
	$this->notificationSenderObj->sendNotifications($profileDetails);
	if(is_array($idArr))
		$this->scheduledAppNotificationUpdateSentObj->updateSent($idArr,$this->notificationKey,NotificationEnums::$PENDING);
	unset($status);
  }
  
  private function getDetails()
  {
	$limit = 5000;
	$valueArr['SENT'] = "N";
	$valueArr['NOTIFICATION_KEY']=$this->notificationKey;
	$greaterThanEqualArrayWithoutQuote['ID']=$this->doneTillId;
	$lessThanArray['ID']=$this->doneTillId+$limit;
	$details = $this->scheduledAppNotificationObj->getArray($valueArr,$excludeArr,'','*',$lessThanArray,'',$limit,$greaterThanEqualArrayWithoutQuote);
	$this->doneTillId = $this->doneTillId+$limit;
	if(is_array($details))
		return $details;
	return false;
  }
}
