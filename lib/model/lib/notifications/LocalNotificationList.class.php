<?php
class LocalNotificationList implements LocalNotification {
	protected $profileid;
	public function getNotifications($notifications)
	{
		$notifications = $this->filterNotifications($notifications);
		$notifications = $this->formatNotifications($notifications);
		return $notifications;
	}
	public function filterNotifications($notifications)
	{
		$filterNotificationsObj = new FilterPollNotifications;
		return $filterNotificationsObj->filter($notifications);
	}
	public function formatNotifications($notifications)
	{
                foreach($notifications as $k=>$v)
                        $finalNotifications[$k] = FormatNotification::formater($v,'L');
		return  $finalNotifications;
	}
}
