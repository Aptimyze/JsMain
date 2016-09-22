<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class schedulePushNotifications extends sfBaseTask
{
    var $tempObj,$gcmSenderObj;
  protected function configure()
  {
$this->addArguments(array(
        new sfCommandArgument('noOfScripts', sfCommandArgument::REQUIRED, 'My argument'),
        new sfCommandArgument('matchalert', sfCommandArgument::OPTIONAL, 'My argument')
        ));


    $this->namespace        = 'notification';
    $this->name             = 'schedulePushNotifications';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:schedulePushNotifications noOfScripts] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        $noOfScripts = $arguments["noOfScripts"];
        $matchalert = $arguments["matchalert"];
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);
	$orgTZ = date_default_timezone_get();
	date_default_timezone_set("Asia/Calcutta");
                if($matchalert=='')
                {
                        $scheduledAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS('newjs_masterDDL');
                        $scheduledAppNotificationsObj->truncate();
                }
                $this->notificationObj = new AppNotification;
                $weekDay  = strtoupper(date("D", time()));
		$valueArray['FREQUENCY']=$weekDay.",D";
                $valueArray['STATUS']="Y";
                $notificationSettings = $this->notificationObj->getNotificationSettings($valueArray);

        foreach($notificationSettings as $notificationKey=>$notificationKeyDetails)
	{
		try
                {
			if(($matchalert==''&& $notificationKey!="MATCHALERT") || ($matchalert && $notificationKey=="MATCHALERT"))
			{
				$currentPath= getcwd();
				chdir(JsConstants::$docRoot."/../");
				for($currentScript=0;$currentScript<$noOfScripts;$currentScript++)
				{
				//	$command = "php symfony notification:schedulePushNotificationsKey ".$notificationKey." ".$noOfScripts." ".$currentScript." > /dev/null 2>&1 &";
					$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony notification:schedulePushNotificationsKey ".$notificationKey." ".$noOfScripts." ".$currentScript." >>/tmp/noti.txt 2>&1 &";
					exec($command);
				}
				chdir($currentPath);
			}
                }
                catch(Exception $e)
                {
			include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
                        $to='esha.jain@jeevansathi.com,manoj.rana@naukri.com,vibhor.garg@jeevansathi.com';
                        $msg='';
                        $subject="Error: scheduling notification for key".$notificationKey;
                        $msg='<br/><br/>Warm Regards';
                        send_email($to,$msg,$subject,"",$cc);

                }

	}
	date_default_timezone_set($orgTZ);
  }
}
