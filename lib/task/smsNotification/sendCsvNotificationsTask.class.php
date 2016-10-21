<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class sendCsvNotifications extends sfBaseTask
{
    var $scheduledAppNotificationObj,$notificationSenderObj,$notificationKey;
  protected function configure()
  {

    $this->namespace        = 'smsNotification';
    $this->name             = 'sendCsvNotifications';
    $this->briefDescription = 'send Instant Notification using CSV Upload';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony smsNotification:sendCsvNotifications] 
EOF;
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

        $notificationKey ='CSV_UPLOAD'; 
	$instantNotificationObj =new InstantAppNotification($notificationKey);

	$this->csvNotificationObj = new MOBILE_API_CSV_NOTIFICATION_TEMP;
	$details = $this->csvNotificationObj->getData();

	foreach($details as $key=>$val){
		$profileid =$val['PROFILEID'];
		$message   =$val['MESSAGE'];
		$exUrl 	   =$val['URL'];
		// send Notification
		$instantNotificationObj->sendNotification($profileid,'',$message,$exUrl);
	}	
  }
}
