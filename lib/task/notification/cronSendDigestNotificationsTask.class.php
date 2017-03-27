<?php

/*cron to send digest notifications at end of day */

class cronSendDigestNotificationsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::REQUIRED, 'My argument')));
    $this->namespace        = 'notification';
    $this->name             = 'cronSendDigestNotifications';
    $this->briefDescription = 'send digest notifications at end of day';
    $this->detailedDescription = <<<EOF
      The [cronSendDigestNotifications|INFO] task send digest notifications at end of day.
      Call it with:

      [php symfony notification:cronSendDigestNotifications] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

    protected function execute($arguments = array(), $options = array())
    {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
	$notificationStop =JsConstants::$notificationStop;
        if($notificationStop)
        	die('successfulDie');

        $notificationKey = $arguments["notificationKey"];
        if(in_array($notificationKey,NotificationEnums::$digestNotificationKeys))
        {
            $digestNotObj = new MOBILE_API_DIGEST_NOTIFICATIONS("newjs_slave");
            //get profileids eligible for this digest notification
            $data = $digestNotObj->getRows("*",$notificationKey);
            if(is_array($data))
            {
                $instantNotObj = new DigestNotification($notificationKey);
                foreach ($data as $key => $value) 
                {
                    unset($interestRecData);
                    $deleteArray[] = $value['PROFILEID'];
                    $notificationDataPoolObj = new NotificationDataPool();
                    $stDate = $value['SCHEDULED_DATE'];
                    //$stDate = date('Y-m-d H:i:s', strtotime('-2 sec',  strtotime($stDate))); 
                    $endDate = date('Y-m-d H:i:s');
                    $interestRecData = $notificationDataPoolObj->getInterestReceivedForDuration($value['PROFILEID'], $stDate, $endDate);
                    if($interestRecData['COUNT']){
                        //get notification data for each profile
                        $notificationDetails = $instantNotObj->fetchNotificationData($interestRecData['SELF'],$interestRecData['OTHER_PROFILEID'],$interestRecData['COUNT']);
                        //print_r($notificationDetails);
                        //send digest notification
                        if($notificationDetails)
                            $instantNotObj->sendNotification($interestRecData['SELF'],$interestRecData['OTHER_PROFILEID'],$notificationDetails);
                    }
                }
                $deleteStr = implode(',', $deleteArray);
                $digestNotMasterObj = new MOBILE_API_DIGEST_NOTIFICATIONS();
                $digestNotMasterObj->removeEntriesHavingProfiles($deleteStr);
                unset($deleteArray);
                unset($deleteStr);
                unset($instantNotObj);
            }
            unset($digestNotObj);
        }
    }
}
?>
