<?php
abstract class LocalNotificationDecorator implements LocalNotification {
     
	protected $localNotificationObj;
	protected $profileid;
    abstract public function getNotifications($list);
public function addNotifications($list1,$list2)
{
                if(!is_array($list2))
                        return $list1;
                if(!is_array($list1))
                        return $list2;
		return $finalList  = array_merge($list1,$list2);
}

    public function __construct(LocalNotification $localnotificationObj,$profileid) {
        $this->localNotificationObj=$localnotificationObj;
	$this->profileid = $profileid;
    }
}
