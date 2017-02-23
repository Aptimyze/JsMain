<?php
/*
Task for chat notification. The task will run at fixed interval scheduled in Azkaban. It will pick set of profiles provided by the service and send notifications to those profiles.
*/

class chatNotificationTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for notification:chatNotification
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'notification';
    $this->name                = 'chatNotification';
    $this->briefDescription    = 'Initialises instance of chatNotifcation to send GCM notification';
    $this->detailedDescription = <<<EOF
     The [chatNotification|INFO] calls AppNotification class to send notifications:
     [php symfony notification:chatNotification] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function to excute cron to fetch notification profiles and send them GCM notifications.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    
    $notificationDataPoolObj = new NotificationDataPool();
    $data = $notificationDataPoolObj->getNotificationServiceData();
    
    $notificationData = $data["data"]["items"];
    $notificationDataPoolObj->sendChatNotification($notificationData);
    
    unset($notificationDataPoolObj,$notificationData);
    
  }
}
?>
