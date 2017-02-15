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
    $this->name             = 'archiveNotificationLog';
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
		
		$sdate = '2017-01-01';
		$edate = '2017-01-02';
		$notificationLogObj =new MOBILE_API_NOTIFICATION_LOG("crm_slave");
                $rowArr = $notificationLogObj->selectRecord($sdate,$edate);         
		$cnt = count($rowArr);

		$notificationLogArchiveObj = new MOBILE_API_NOTIFICATION_LOG_ARCHIVE;
		for($i=0;$i<$cnt;$i++)
		{
			$pid = $rowArr[$i]['PROFILEID'];
			$nk = $rowArr[$i]['NOTIFICATION_KEY'];
			$mi = $rowArr[$i]['MESSAGE_ID'];
			$sdate = $rowArr[$i]['SEND_DATE'];
			$udate = $rowArr[$i]['UPDATE_DATE'];
			$sent = $rowArr[$i]['SENT'];
			$ot = $rowArr[$i]['OS_TYPE'];
			$notificationLogArchiveObj->insertRecord($pid,$nk,$mi,$sdate,$udate,$sent,$ot);	
		}
	
		$notificationLogObj =new MOBILE_API_NOTIFICATION_LOG;
		$notificationLogObj->deleteRecordDateWise($sdate,$edate);		
  }
}
