<?php
/*
This class includes functions for pushing notifications to GCM based on type.
*/
class GcmNotificationsSender
{
	/*handle push notification 
	* @params : $notificationType,$notificationData,$handleRegIdExpireCase(true/false)
	*/
	public static function handleNotification($notificationType,$notificationData,$handleRegIdExpireCase=false)
	{
        	$fields['registration_ids'] = $notificationData["REG_ID"];
        	unset($notificationData["REG_ID"]);
        	$fields['data'] = $notificationData;
        	unset($notificationData);
        	$updateStatus = true;  //update status flags in BROWSER_NOTIFICATION table
		$gcmObj = new BrowserGCM($notificationType,$fields);
		$gcmObj->sendBrowserNotification($updateStatus,$handleRegIdExpireCase);
		unset($gcmObj);
	}
}
?>
