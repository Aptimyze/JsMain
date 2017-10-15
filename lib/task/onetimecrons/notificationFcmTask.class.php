<?php
/**
 * This is the cron to schedule Broswer notification. This cron is used to add both instant and schedule notifications.
 * Parameters Description:
 *  type: 'instant' or 'scheduled'
 */
class notificationFcmTask extends sfBaseTask{
    
    protected function configure() {
        
        /*$this->addArguments(array(new sfCommandArgument('notificationType', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('selfUserId', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('otherUserId', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addArguments(array(new sfCommandArgument('message', sfCommandArgument::OPTIONAL, 'My argument')));*/
        
        $this->namespace = "browserNotification";
        $this->name = "notificationFcmTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [browserNotificationTask|INFO] task does things.
            Call it with:[php symfony browserNotification:notificationFcmTask|INFO]
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

$notificationData =array('REG_ID'=>array('d2OtXQ36oVQ:APA91bGVxSpjoLsE40rqevsF-x9OoBOl1-5N3qhuzvpfR6avBWS3dLd5JMCO3NY0b6CyMEInHvjofHVbhg6SREO08ZZ2sZEiDWnNON43cndeBi8wPx_B8ggpFHgrm2wonzxlavZgVX-c'),'NOTIFICATION_KEY'=>'JUST_JOIN','TITLE'=>'Just Joined Matches','MESSAGE'=>'Notification testing for male','ICON'=>'https://mediacdn.jeevansathi.com/7057/3/141143247-1507461115.jpeg','TAG'=>'JJ','MSG_ID'=>'12345566676','LANDING_ID'=>'https://www.jeevansathi.com/search/perform?justJoinedMatches=1');
FcmNotificationsSenderHandler::handleNotification('BROWSER_NOTIFICATION',$notificationData,false);
die('Done');
    }
}
