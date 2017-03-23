<?php

/**
 * Description of GCM
 *
 */

class GCM {
    function __construct() {
    }
    /**
     * Sending Push Notification
     */
    public function sendNotification($registrationIds, $details,$profileid,$log=true) {
	$invalidStatus ='I';
        $url = 'https://android.googleapis.com/gcm/send';
	$dataArray = FormatNotification::formater($details);
	$msgId =$details['MSG_ID'];
        $fields = array(
            'registration_ids' => $registrationIds,
            'data' => $dataArray);

	if($details['COLLAPSE_STATUS']=="Y")
		$fields['collapse_key']=$details['NOTIFICATION_KEY'];
	if($details['TTL']!='' && $details['TTL']!=0)
	{
		$fields['time_to_live'] = intval($details['TTL']);
		$fields['delay_while_idle'] = true;
	}
        $headers = NotificationEnums::$GcmAppHeaders;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
	curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        $result = curl_exec($ch);
	$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$response = json_decode($result, true);
	$count = 0;
	if($log)
	{
		if(is_array($response['results']))
		{
			$statusArr =array();
			$schedduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
			$notificationLog = new MOBILE_API_NOTIFICATION_LOG;
			$registrationIdObj = new MOBILE_API_REGISTRATION_ID;
			foreach($response['results'] as $k=>$v)
			{
				$logArray[$count]['PROFILEID']=$profileid;
				$logArray[$count]['HTTP_STATUS_CODE']=$httpStatus;
				$logArray[$count]['REGISTRATION_ID']=$registrationIds[$k];
				$logArray[$count]['NOTIFICATION_KEY']=$details['NOTIFICATION_KEY'];
				$oldRegistrationId = $registrationIds[$k];
				if($v['registration_id'])
				{
					$newRegistrationId = $v['registration_id'];
					$regIdExists = $registrationIdObj->getArray(array('REG_ID'=>$newRegistrationId));
					if(!is_array($regIdExists))
						$rowsUpdated = $registrationIdObj->updateRegId($oldRegistrationId,$newRegistrationId);
					if(is_array($regIdExists)||!$rowsUpdated)
						$registrationIdObj->deleteRegId($oldRegistrationId);
					//$logArray[$count]['STATUS_MESSAGE'] = "UPDATE_ID_TO:".$newRegistrationId;
					$logArray[$count]['STATUS_MESSAGE'] = "UPDATE_ID";
					$detailsForRegIdChange["NEW_REGID"]=$newRegistrationId;
					$detailsForRegIdChange["LANDING_SCREEN"]=0;
					$this->sendNotification(array($newRegistrationId), $detailsForRegIdChange,$profileid,false);
				}
				elseif($v['error']){
					$logArray[$count]['STATUS_MESSAGE']=$v['error'];
					$statusArr[] =$invalidStatus;
					if($oldRegistrationId && ($v['error']=='InvalidRegistration' || $v['error']=='NotRegistered')){
						$registrationIdObj->deleteRegId($oldRegistrationId);
                                                //Changed below to insert entry in APP_UNINSTALL to send mailer 
                                                $appUninstallObj = new NOTIFICATION_APP_UNINSTALL();
                                                $appUninstallObj->insertUninstalledProfiles($profileid,$oldRegistrationId);
                                        }
				}
				elseif($v['message_id']){
					$logArray[$count]['STATUS_MESSAGE']="SUCCESS";
					$statusArr[] ='Y';
				}
				else
					$logArray[$count]['STATUS_MESSAGE']="OTHER";
				$count++;
			}
			if(is_array($logArray))
			{
				$logObj = new MOBILE_API_GCM_RESPONSE_LOG;
				$logObj->insert($logArray);
				unset($logArray);

				if(!in_array("Y", $statusArr) && $msgId){
					$notificationLog->updateSent($msgId, $invalidStatus,'A');
					$schedduledAppNotificationObj->updateSuccessSent($invalidStatus, $msgId);	
				}
				unset($statusArr);	
			}
		}
	}
        if ($result === FALSE) {
		$f = fopen("/tmp/gcmError.txt","a+");
		fwrite($f,curl_error($ch));
		fclose($f);
        }
        curl_close($ch);
        return $result;
    }
}
?>
