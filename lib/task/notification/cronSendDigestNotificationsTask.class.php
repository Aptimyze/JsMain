<?php

/*cron to send digest notifications at end of day */

class cronSendDigestNotificationsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::REQUIRED, 'My argument')));
    $this->namespace        = 'notification';
    $this->name             = 'cronSendDigestNotifications';
    $this->briefDescription = 'send digest notifications at end of day';
    $this->detailedDescription = <<<EOF
      The [cronSendDigestNotifications|INFO] task send digest notifications at end of day.
      Call it with:

      [php symfony notification:cronSendDigestNotifications] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

    protected function execute($arguments = array(), $options = array())
    {
        //ini_set('max_execution_time',0);
        //ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $notificationKey = $arguments["notificationKey"];
        if(in_array($notificationKey,NotificationEnums::$digestNotificationKeys))
        {
            $digestNotObj = new MOBILE_API_DIGEST_NOTIFICATIONS();
            $data = $digestNotObj->getRows("*",$notificationKey); 
            foreach ($data as $key => $value) 
            {
                $instantNotObj = new DigestNotification($value['NOTIFICATION_KEY']);
                $notificationDetails = $instantNotObj->fetchNotificationData($value['PROFILEID'],$value['COUNT']);
                print_r($notificationDetails);
                $instantNotObj->sendNotification($value['PROFILEID'],$notificationDetails);
                unset($instantNotObj);
            }
        }
    }
}
