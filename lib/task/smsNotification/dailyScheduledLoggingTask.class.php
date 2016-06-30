<?php
class dailyScheduledLogging extends sfBaseTask
{
    var $tempObj,$gcmSenderObj;
  protected function configure()
  {
    $this->namespace        = 'notification';
    $this->name             = 'dailyScheduledLogging';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:dailyScheduledLogging] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

		$startDate 	=date('Y-m-d',time()-86400)." 18.30:00:00";
		$endDate 	=date("Y-m-d")." 18.30:00:00";
		$datePrev6day	=date("Y-m-d",time()-6*24*60*60)." 00:00:00";
		$entryDate	=date("Y-m-d");

		// get data from Notification Log
                $notificationsLogObj = new MOBILE_API_NOTIFICATION_LOG('newjs_slave'); 
		$logData =$notificationsLogObj->getDataCountForRange($startDate,$endDate);

		// get data from Gcm Response Log
		$gcmLogObj = new MOBILE_API_GCM_RESPONSE_LOG('newjs_slave');
		$gcmLogData =$gcmLogObj->getDataCountForRange($startDate,$endDate);

		// get Notification Keys
		$appNotificationObj =new MOBILE_API_APP_NOTIFICATIONS('newjs_slave');			
		$notificationArr =$appNotificationObj->getActiveNotifications();

                // get Local Notification Api Hit 
                $localNotififObj =new MOBILE_API_LOCAL_NOTIFICATION_LOG('newjs_slave');
                $localNotifData =$localNotififObj->getDataCountForRange($startDate,$endDate);
                //$localNotifData =$localNotififObj->getDataCountForDate($startDate,$endDate);

		// create Temp Table for Notification Log
		$notificationsLogObj->truncateTempNotificationLogTable();
		$notificationsLogObj->truncateTempLoginTrackingTable();
		$notificationsLogObj->createTempTablePool($startDate, $endDate);
	
		// create Temp Table for Login Tracking
		$misLoginTrackingObj =new MIS_LOGIN_TRACKING('newjs_slave');
		$misLoginTrackingObj->createTempTablePool($datePrev6day, $endDate);	

		// get 7 days Active Profiles
		$activeProfileCount7Day =$notificationsLogObj->getActiveProfileCount();

                // get 1 day Active Profiles
                $activeProfileCountDay =$notificationsLogObj->getActiveProfileCount($startDate, $endDate);

		// get Total IOS Request Failed
		$iosResponseLog =new MOBILE_API_IOS_RESPONSE_LOG('newjs_slave'); 
		$totalIosFailed =$iosResponseLog->getDataCountForRange($startDate, $endDate);
		
		// object of Daily Scheduled Log
		$dailyScheduledLog =new MOBILE_API_DAILY_NOTIFICATION_COUNT_LOG;

		// Browser notification Data
		$browserNotificationObj =new MOBILE_API_BROWSER_NOTIFICATION('newjs_slave');
		$browserNotificationData =$browserNotificationObj->getDataCountForRange($startDate, $endDate);

		foreach($notificationArr as $key=>$notificationKeyArr){

			$notificationKey	=$notificationKeyArr['NOTIFICATION_KEY'];
			$browserData		=$browserNotificationData[$notificationKey];

			// PUSH
			$recordCount 		=$logData[$notificationKey];
			$pushAcknowledged	=$recordCount['Y']['A']+$recordCount['Y']['I'];

			// LOCAL
			$localNotifRecord       =$localNotifData[$notificationKey];
            		$localDelivered 	=$localNotifRecord['D']['A']+$localNotifRecord['L']['A'];
       			$localAcknowledged	=$localNotifRecord['L']['A'];
			$localApiHit		=$localDelivered;

			$totalCount		=$pushAcknowledged+$localAcknowledged+$recordCount['P']['A']+$recordCount['P']['I'];	

			// GCM
			$gcmLogDataArr	=$gcmLogData[$notificationKey];
			if(count($gcmLogDataArr)>0){
				foreach($gcmLogDataArr as $key=>$value)
					$gcmPush +=$value;
				$gcmAccepted	=$gcmLogDataArr['SUCCESS'];
			}

			// IOS
			$totalIosPushed	  =$recordCount['P']['I']+$recordCount['Y']['I'];
			$totalIosReceived =$totalIosPushed-$totalIosFailed[$notificationKey];
			
			$active7DaysCount=$activeProfileCount7Day[$notificationKey];
			$active1DaysCount=$activeProfileCountDay[$notificationKey];	
	
			// Add record in daily log table	
			$dailyScheduledLog->insertData($notificationKey,$totalCount, $gcmPush,$gcmAccepted,$pushAcknowledged ,$localApiHit, $localDelivered,$localAcknowledged, $active7DaysCount, $active1DaysCount,$totalIosPushed, $totalIosReceived, $entryDate,'A_I');

                        // Browser Notification Records 
                        $desktopAcknowledged    =$browserData['D']['Y']['Y'];
                        $desktopPushedTGcm      =$desktopAcknowledged+$browserData['D']['N']['Y'];

                        $mobileAcknowledged    	=$browserData['M']['Y']['Y'];
                        $mobilePushedTGcm      	=$mobileAcknowledged+$browserData['M']['N']['Y'];

                        $dailyScheduledLog->insertData($notificationKey,$desktopPushedTGcm, $desktopPushedTGcm,'',$desktopAcknowledged ,'','','','','','', '', $entryDate,'D');
                        $dailyScheduledLog->insertData($notificationKey,$mobilePushedTGcm, $mobilePushedTGcm,'',$mobileAcknowledged ,'','','','','','', '', $entryDate,'M');
                        unset($gcmPush);
                        unset($gcmAccepted);
			unset($gcmLogDataArr);
			unset($recordCount);
		}
  }
}
