<?php
Class FailedNotification extends LocalNotificationDecorator{
	
	public function getNotifications($notifyList=null)
	{
			
                        $scheduleAppNotificationsObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS('newjs_masterRep');
                        $valueArray['PROFILEID']  = $this->profileid;
                        $valueArray['OS_TYPE']  = "ALL,AND";
                        $notifications = $scheduleAppNotificationsObj->getArray($valueArray,'','',"*");
			$notifications = $this->addNotifications($notifyList, $notifications);
			return $this->localNotificationObj->getNotifications($notifications);
	}
}
