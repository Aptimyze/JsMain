<?php
/**
 * This is the cron to schedule PENDING_EOI. This cron is used to add both instant and schedule notifications.
 * Parameters Description:
 *  type: 'instant' or 'scheduled'
 */
class browserNotificationPENDING_EOITask extends sfBaseTask{
    
    protected function configure() {
        
        $this->addArguments(array(new sfCommandArgument('notificationType', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('selfUserId', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('otherUserId', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('message', sfCommandArgument::OPTIONAL, 'My argument')));
        
        $this->namespace = "browserNotification";
        $this->name = "browserNotificationPENDING_EOITask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [browserNotificationTask|INFO] task does things.
            Call it with:[php symfony browserNotification:browserNotificationPENDING_EOITask|INFO]
EOF;
        $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
    }
    
    protected function execute($arguments = array(), $options = array()) 
    {
        //setting memory_limit and max_execution_time
        ini_set('max_execution_time',-1);
        ini_set('memory_limit','-1');
        ini_set('error_reporting',1);
        ini_set("mysql.connect_timeout",-1);
        
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        if($arguments){
            $notificationType = $arguments["notificationType"]; //INSTANT/SCHEDULED
            $notificationKey = $arguments["notificationKey"];
            $selfUserId = $arguments["selfUserId"];    //profileid/agentid to whom notification is to be sent
            $otherUserId = $arguments["otherUserId"]; //comma separated list of other profileids(whose data is used in notification)
            $message = $arguments["message"]; //For any other detail which needs to be passed as parameter
        }

        $notificationStop =JsConstants::$notificationStop;
        if($notificationStop && $notificationType=='SCHEDULED'){
                die('successfulDie');
        }

        if($otherUserId)
            $otherUserId = explode(",", $otherUserId);
        $processObj = new BrowserNotificationProcess();
        // obsolete
        /*if($notificationType == "INSTANT")
        {
            if(in_array($notificationKey, BrowserNotificationEnums::$instantNotifications))
            {
                $processObj->setDetails(array("method"=>$notificationType,"notificationKey"=>$notificationKey,"selfUserId"=>$selfUserId,"otherUserId"=>$otherUserId, "message" => $message));
                $browserNotificationObj = new BrowserNotification($notificationType,$processObj);
                $browserNotificationObj->addNotification($processObj);
            }
            else
            {
                die("Invalid instant notification key provided in browserNotificationTask");
            }
        }*/
        if($notificationType == "SCHEDULED"){
            //empty notification log files at start of schedule process 
            exec('echo "" > '.JsConstants::$cronDocRoot.BrowserNotificationEnums::$publishedNotificationLog);
            exec('echo "" > '.JsConstants::$cronDocRoot.BrowserNotificationEnums::$transferredNotificationlog);
            $browserNotificationObj = new BrowserNotification($notificationType,$processObj);
            //Block for scheduled notification
            $processObj->setmethod("SCHEDULED");
            
            echo "before PENDING_EOI \n";
            $processObj->setnotificationKey("PENDING_EOI");
            $browserNotificationObj->addNotification($processObj);
            echo "After PENDING_EOI \n";
        }
        else
        {
            echo "Wrong Notification type provided in browserNotificationTask";
            die;
        }
    }
    
}
