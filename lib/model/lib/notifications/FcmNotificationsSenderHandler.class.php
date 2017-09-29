<?php
/*
This class includes functions for pushing notifications to FCM based on type.
*/
class FcmNotificationsSenderHandler 
{
	/*handle push notification 
	* @params : $notificationType,$notificationData,$handleRegIdExpireCase(true/false)
	*/
	public static function handleNotification($notificationType,$notificationData,$handleRegIdExpireCase=false)
	{
        	$fields['registration_ids'] 	= $notificationData["REG_ID"];
		unset($notificationData["REG_ID"]);

	        $formatterObj  =new FormatNotification();
	        $notificationData  =$formatterObj->formaterForFcmBrowser($notificationData);
        	$fields['data']	= $notificationData;
	        unset($notificationData);

	        $logUpdate =true;  	//update status flags in BROWSER_NOTIFICATION table
		$engineType ='FCM';
		$sendMultipleParallelNotification =false;
		$notificationEngineFactoryObj =new NotificationEngineFactory($sendMultipleParallelNotification);
		$engineObj = $notificationEngineFactoryObj->geNotificationEngineObject($engineType,$notificationType);
		$engineObj->sendNotification($logUpdate, $handleRegIdExpireCase, $fields);
	}
}
?>
