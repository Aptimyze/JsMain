<?php
/**
 * class NotificationScheduler
 * 
 */
class NotificationScheduler
{
  public $notificationObj;
  public $noOfScripts;
  public $currentScript;
  public $notificationKey;

  public function __construct()
  {
		
  }

  public function notificationScheduler()
  {
	$notifications = $this->notificationObj->getNotifications();
	foreach($notifications as $notificationKey=>$notificationKeyDetails)
		$this->scheduleNotificationsForKey($notificationKey);
  }


  public function getDate($days)
  {
        if ($days == 0) {
            $timestamp = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        } else
            {
            $hrs                = $days * 24;
            $timestamp = mktime(date("H") - $hrs, date("i"), date("s"), date("m"), date("d"), date("Y"));
        }
        return $dateformat     = date("Y-m-d", $timestamp);
  }
}
?>
