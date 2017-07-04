<?php
/**
 * This cron monitors the day wise scheduling of notifications.
 * * <code>
 * To execute : $ symfony monitoring:scheduledNotificationsMonitoring
 * </code>
 */
class ScheduledNotificationsMonitoringTask extends sfBaseTask
{
  /*
   * To Show Debug Information on console
   */
  private $debugInfo = true;
  private $notificationKeyWiseThreshhold = array("JUST_JOIN"=>20000,"MATCH_OF_DAY"=>50000);
  private $mailId = "nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com,vibhor.garg@jeevansathi.com";
          
  protected function configure()
  {
      $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','jeevansathi'),
        ));
    $this->namespace        = 'monitoring';
    $this->name             = 'scheduledNotificationsMonitoring';
    $this->briefDescription = 'This cron checks whether all scheduled notifications have been scheduled';
    $this->detailedDescription = <<<EOF
    The [monitoring:scheduledNotificationsMonitoring|INFO] This cron checks whether all scheduled notifications have been scheduled.
    Call it with:

    [php symfony monitoring:scheduledNotificationsMonitoring|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
      $appNotificationObj = new MOBILE_API_APP_NOTIFICATIONS("newjs_slave");
      $data = $appNotificationObj->getActiveNotifications("NOTIFICATION_KEY,FREQUENCY");
      $settings = $this->removeInstantNotifications($data);
      
      $scheduledAppObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS("newjs_slave");
      $todaysNotifications = $scheduledAppObj->getTodaysNotifications();
      $notificationsNotSent = "";
      $mailMsg = "";
      foreach($settings as $key => $val){
          $notificationKey = $val["NOTIFICATION_KEY"];
          if(array_key_exists($notificationKey, $todaysNotifications)){
              if(array_key_exists($notificationKey, $this->notificationKeyWiseThreshhold) && $todaysNotifications[$notificationKey] < $this->notificationKeyWiseThreshhold[$notificationKey]){
                  $belowThresholdMsg = "Following notifications have below threshold message count<br>";
                  $belowThresholdMsg.= "$notificationKey = ".$todaysNotifications[$notificationKey];
              }
          }
          else{
             $notificationsNotSent.="$notificationKey<br>"; 
          }
      }
      
      if(!empty($notificationsNotSent)){
          $mailMsg = "Following Notifications not scheduled for today:<br>".$notificationsNotSent;
      }
      if(!empty($belowThresholdMsg)){
          $mailMsg.="<br><br><br>".$belowThresholdMsg;
      }
      $subject = "Scheduled notifications failure"."(Machine:".JsConstants::$whichMachine.")";
      print_r($mailMsg);
      CommonUtility::sendAlertMail($this->mailId, $mailMsg, $subject);
  }

  protected function removeInstantNotifications($settings){
      if($settings && is_array($settings)){
          foreach ($settings as $key => $val){
              if($val["FREQUENCY"] != "I")
                  $resutl[] = $val;
          }
          return $resutl;
      }
  }
}
?>
