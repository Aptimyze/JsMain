<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class archiveNotificationLog extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'notification';
    $this->name             = 'archiveNotificationsLog';
    $this->briefDescription = 'cleanup of Notification Log Table in MOBILE_API';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:archiveNotificationLog] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

		ini_set("memory_limit","-1");		
		$sdate = date('Y-m-d', time()-2*86400);
		$edate = date('Y-m-d', time()-1*86400);
		$notificationLogObj1 =new MOBILE_API_NOTIFICATION_LOG;
		$notificationLogObj1->deleteRecordDateWise($sdate,$edate);		
		$notificationLogObj2 =new MOBILE_API_NOTIFICATION_MESSAGE_LOG;
                $notificationLogObj2->deleteRecordDateWise($sdate,$edate);
		$notificationLogObj3 =new MOBILE_API_GCM_RESPONSE_LOG;
                $notificationLogObj3->deleteRecordDateWise($sdate,$edate);
		$notificationLogObj3 =new MOBILE_API_LOCAL_NOTIFICATION_LOG;
                $notificationLogObj3->deleteRecordDateWise($sdate,$edate);
  }
}
