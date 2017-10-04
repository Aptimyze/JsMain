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

$notificationData =array('REG_ID'=>array('dke9tFOnX-k:APA91bFTMGlFF1dxkpfDS7UN9k-jIUiCXLhtJlcFugyi3_lNi5KerU9TTojjVpyI8HqZhsB6evpxcLir0-DMmtx_itJtC7qsbWyrkhGIsEPGyj2L3rR5xMMUUrLxqtgCuLMz27jlbDZZ'),'NOTIFICATION_KEY'=>'JUST_JOIN','TITLE'=>'Fcm Notification','MESSAGE'=>'Testing Notification','ICON'=>'','TAG'=>'','MSG_ID'=>'12345566676','LANDING_ID'=>'https://wwww.jeevansathi.com');
FcmNotificationsSenderHandler::handleNotification('BROWSER_NOTIFICATION',$notificationData,false);
die('test');

    }
    
}
