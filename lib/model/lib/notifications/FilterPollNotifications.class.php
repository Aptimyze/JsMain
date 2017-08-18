<?php
/**
 * class NotificationScheduler
 * 
 */
 class FilterPollNotifications
{
  public $notifications=array();
  public $countSent = 0;
  public function filter($notifications)
  {
	$this->notifications = $notifications;
	$this->notifications =$this->filterLogic();
	$this->sortNotifications();
	$this->notifications =$this->limitNotifications();
	return $this->notifications;
  }
  public function filterLogic()
  {
	foreach($this->notifications as $k=>$v)
	{
    //error_log("ankita -".$v['NOTIFICATION_KEY']);
		if(!in_array($v['NOTIFICATION_KEY'], NotificationEnums::$notEligibleForPolling) && ($v['SENT']=="P"||$v['SENT']=="N"))
			$notifications[]=$v;
		else
			$this->countSent++;
	}
	return $notifications;
  }
  public function sortNotifications()
  {
	usort($this->notifications, array("FilterPollNotifications","cmp"));
  }
  public function limitNotifications()
  {
	return array_slice($this->notifications, 0, NotificationEnums::$scheduledNotificationCap);
  }
  public function cmp($a,$b)
  {
	return strcmp($a["PRIORITY"], $b["PRIORITY"]);
  }
}
?>
