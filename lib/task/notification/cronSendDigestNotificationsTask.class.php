<?php

/*cron to send digest notifications at end of day */

class cronSendDigestNotificationsTask extends sfBaseTask
{
  protected function configure()
  {
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

        $digestNotObj = new MOBILE_API_DIGEST_NOTIFICATIONS();
        $count = $digestNotObj->getRowsCount(date("Y-m-d"));
        echo "count---".$count;
        $limit = 1000;
        for($i=0;$i<=$count;$i+=$limit)
        {
            $data = $digestNotObj->getRows("*",$limit,$i);
            print_r($data);
            foreach ($data as $key => $value) 
            {
                $instantNotObj = new DigestNotification($value['NOTIFICATION_KEY']);
                $instantNotObj->sendNotification($value['PROFILEID']);
            }
           
            die;
        }  
    }
}
