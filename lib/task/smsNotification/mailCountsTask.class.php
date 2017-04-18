<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class mailCounts extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'notification';
    $this->name             = 'mailCounts';
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony notification:mailCounts] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
                $msg = '';
                $scheduledAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
                $countsArr = $scheduledAppNotificationsObj->getArray('','','','COUNT(*),NOTIFICATION_KEY,SENT','','','','','','','','NOTIFICATION_KEY,SENT');
                $msg = "<table><tr><td>Key</td><td>count</td><td>sent</td></tr>";
                foreach($countsArr as $k=>$v)
                {
                        $msg.="<tr><td>".$v['NOTIFICATION_KEY']."</td><td>".$v['COUNT(*)']."</td><td>".$v['SENT']."</td></tr>";
                }
                $msg.="</table>";
		include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
		$to='manoj.rana@naukri.com';
		$subject="Notifications sent till now";
		$msg.="<br/>Warm Regards";
		send_email($to,$msg,$subject,"",$cc);
  }
}
