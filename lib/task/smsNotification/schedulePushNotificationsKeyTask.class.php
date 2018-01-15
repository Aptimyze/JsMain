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
                $appNotificationSchedulerObj = new AppNotificationScheduler($notificationKey,$noOfScripts,$currentScript);
                $appNotificationSchedulerObj->scheduleNotificationsForKey();
  }
}
