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
		$sdate = date('Y-m-d', time()-5*86400);
		$edate = date('Y-m-d', time()-4*86400);
		$notificationLogObj =new MOBILE_API_NOTIFICATION_LOG;
		$notificationLogObj->deleteRecordDateWise($sdate,$edate);		
		$notificationLogObj =new MOBILE_API_NOTIFICATION_MESSAGE_LOG;
                $notificationLogObj->deleteRecordDateWise($sdate,$edate);
  }
}
