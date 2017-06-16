<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class timeDistributor extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'notification';
    $this->name             = 'timeDistributor';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:timeDistributor] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	die('notRequired');
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);
	$this->alarmMinTime = NotificationEnums::$alarmMinTime;
	$this->alarmMaxTime = NotificationEnums::$alarmMaxTime;
	$appProfilesHandlerObj = new AppProfilesHandler;
	$alarmTimeObj = new MOBILE_API_ALARM_TIME;

	$numberOfLoopsExecuted = 0;
          while(1)
          {
                  $appProfiles = array();
                  $restartLooper = false;
                  if($numberOfLoopsExecuted==0)
                        $restartLooper = true;
                  $appProfiles = $appProfilesHandlerObj->getProfiles("DISTRIBUTOR",$numberOfProfilesPerLoop=5,$restartLooper,1,0);
                  if(is_array($appProfiles))
                  {
			foreach($appProfiles as $k=>$profileid)
			{
				$alarmTime[$profileid]=alarmTimeManager::getNextTime($this->alarmCurrentTime,$this->alarmMaxTime,$this->alarmMinTime);
				$this->alarmCurrentTime = $alarmTime[$profileid];
			}
			$alarmTimeObj->replace($alarmTime);
			unset($alarmTime);
                  }
                  else
                        break;
                  $numberOfLoopsExecuted++;
          }
	$maxAlarTimeObj = new MOBILE_API_MAX_ALARM_TIME('newjs_master');
	$maxAlarTimeObj->updateMaxAlarmTime($this->alarmCurrentTime);
  }
}
