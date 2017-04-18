<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class schedulePushNotificationsKey extends sfBaseTask
{
  protected function configure()
  {
$this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::REQUIRED, 'My argument')));
$this->addArguments(array(new sfCommandArgument('noOfScripts', sfCommandArgument::REQUIRED, 'My argument')));
$this->addArguments(array(new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument')));
$this->addArguments(array(new sfCommandArgument('androidMaxVersion', sfCommandArgument::OPTIONAL, 'My argument')));
$this->addArguments(array(new sfCommandArgument('currentAndroidMaxVersion', sfCommandArgument::OPTIONAL, 'My argument')));

    $this->namespace        = 'notification';
    $this->name             = 'schedulePushNotificationsKey';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:schedulePushNotificationsKey] 
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
		$noOfScripts = $arguments["noOfScripts"];
		$currentScript = $arguments["currentScript"];
        $androidMaxVersion = $arguments["androidMaxVersion"];
        $currentAndroidMaxVersion = $arguments["currentAndroidMaxVersion"];
        $this->checkForUpdateApp($notificationKey, $androidMaxVersion, $currentAndroidMaxVersion);
                $appNotificationSchedulerObj = new AppNotificationScheduler($notificationKey,$noOfScripts,$currentScript,$androidMaxVersion);
                $appNotificationSchedulerObj->scheduleNotificationsForKey();
                $this->mailScheduleComplete($notificationKey,$noOfScripts,$currentScript);
  }
  
  public function checkForUpdateApp($notificationKey,$androidMaxVersion,$currentAndroidMaxVersion){
      if($notificationKey == "UPGRADE_APP"){
          if(!($androidMaxVersion && $currentAndroidMaxVersion && is_numeric($androidMaxVersion) && is_numeric($currentAndroidMaxVersion))){
              die("Please provide android version till which update app notification needs to be send and current max android version");
          }
          else{
              $upgradeAppObj = new MOBILE_API_UPGRADE_APP_NOTIFICATION();
              $upgradeAppObj->insert($androidMaxVersion, $currentAndroidMaxVersion);
          }
      }
  }
  
  public function mailScheduleComplete($notificationKey,$noOfScripts,$currentScript){
      if(in_array($notificationKey, NotificationEnums::$mailScheduleComplete)){
          $msg = "$notificationKey notification current script $currentScript, Total script: $noOfScripts  scheduling complete";
          SendMail::send_email(NotificationEnums::$jscDevMail, $msg, $msg);
      }
  }
}
