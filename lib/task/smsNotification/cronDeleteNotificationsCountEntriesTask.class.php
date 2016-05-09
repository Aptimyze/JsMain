<?php

/*
 * This cron truncates MOBILE_API.SENT_NOTIFICATIONS_COUNT table.
 */

class cronDeleteNotificationsCountEntries extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'notification';
    $this->name             = 'cronDeleteNotificationsCountEntries';
    $this->briefDescription = 'truncates MOBILE_API.SENT_NOTIFICATIONS_COUNT table';
    $this->detailedDescription = <<<EOF
      The [cronDeleteNotificationsCountEntries|INFO] task truncates MOBILE_API.SENT_NOTIFICATIONS_COUNT table.
      Call it with:

      [php symfony notification:cronDeleteNotificationsCountEntries] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
    if(!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $notificationsCountObj = new MOBILE_API_SENT_NOTIFICATIONS_COUNT();
    $notificationsCountObj->truncateCountEntries();
  }
}
