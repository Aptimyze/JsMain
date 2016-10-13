<?php

/*
 * Author: Esha Jain
 * This task schedules notification key(not daily scheduled) on specific day.
 */

class schedulePushNotificationsKeyDAY_BASED extends sfBaseTask
{
  protected function configure()
  {
$this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::REQUIRED, 'My argument')));
$this->addArguments(array(new sfCommandArgument('noOfScripts', sfCommandArgument::REQUIRED, 'My argument')));
$this->addArguments(array(new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument')));

    $this->namespace        = 'notification';
    $this->name             = 'schedulePushNotificationsKeyDAY_BASED';
    $this->briefDescription = 'This task schedules notification key(not daily scheduled) on specific day';
    $this->detailedDescription = <<<EOF
      The [schedulePushNotificationsKeyDAY_BASED|INFO] This task schedules notification key(not daily scheduled) on specific day.
      Call it with:

      [php symfony notification:schedulePushNotificationsKeyDAY_BASED] 
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
    
    $todaysDay  = strtoupper(date("D", time()));
    $scheduleDaysKeyBased = NotificationEnums::$keyBasedScheduleDaysConfig[$notificationKey];
    if(in_array($todaysDay, $scheduleDaysKeyBased))
    {
      $appNotificationSchedulerObj = new AppNotificationScheduler($notificationKey,$noOfScripts,$currentScript);
      $appNotificationSchedulerObj->scheduleNotificationsForKey();
    }
  }
}
