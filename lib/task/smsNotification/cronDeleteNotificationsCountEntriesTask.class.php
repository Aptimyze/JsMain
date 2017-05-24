<?php

/*
 * This cron truncates MOBILE_API.SENT_NOTIFICATIONS_COUNT table and MOBILE_API.DIGEST_NOTIFICATIONS table.
 */

class cronDeleteNotificationsCountEntries extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'notification';
    $this->name             = 'cronDeleteNotificationsCountEntries';
    $this->briefDescription = 'truncates MOBILE_API.SENT_NOTIFICATIONS_COUNT table and MOBILE_API.DIGEST_NOTIFICATIONS table';
    $this->detailedDescription = <<<EOF
      The [cronDeleteNotificationsCountEntries|INFO] task truncates MOBILE_API.SENT_NOTIFICATIONS_COUNT table and MOBILE_API.DIGEST_NOTIFICATIONS table.
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
    
    //remove sent notification count entries
    $notificationsCountObj = new MOBILE_API_SENT_NOTIFICATIONS_COUNT('newjs_master');
    $notificationsCountObj->truncateCountEntries();
    unset($notificationsCountObj);

    //remove all digest notification entries
    $digestNotificationObj = new MOBILE_API_DIGEST_NOTIFICATIONS('newjs_master');
    $digestNotificationObj->removeEntries(date("Y-m-d"));
    unset($digestNotificationObj);
  }
}
