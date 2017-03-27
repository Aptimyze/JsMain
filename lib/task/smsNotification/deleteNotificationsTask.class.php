<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class deleteNotifications extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'notification';
    $this->name             = 'deleteNotifications';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:deleteNotifications] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);
                if($dontTruncate=='')
                {
                        $scheduledAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS('newjs_masterDDL');
                        $scheduledAppNotificationsObj->truncate();
                }
  }
}
