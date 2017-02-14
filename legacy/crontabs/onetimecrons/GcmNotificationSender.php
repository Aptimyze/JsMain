<?php
        include(JsConstants::$docRoot."/profile/connect.inc");
	$db_slave =connect_slave();
        include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

	$notificationKey =$_SERVER['argv'][1]; 
	$engineObject =new GCM_NEW_dryRun();

	$sql ="select * from MOBILE_API.SCHEDULED_APP_NOTIFICATIONS WHERE SENT IN('N','P') AND NOTIFICATION_KEY='$notificationKey'";		
	$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($db_slave));
	while($details=mysql_fetch_array($res))
	{
		$profileid =$details['PROFILEID'];
	
		$sql1 ="select * from MOBILE_API.REGISTRATION_ID WHERE PROFILEID='$profileid' AND NOTIFICATION_STATUS='Y'";		
		$res1=mysql_query_decide($sql1,$db_slave) or die("$sql1".mysql_error_js($db_slave));
		while($row1=mysql_fetch_array($res1)){
			
			$regIdArr[] =$row1['REG_ID'];	
			
		}
		$engineObject->sendNotificationNew($regIdArr, $details, $profileid);
		unset($regIdArr);
	}
	echo "done";

