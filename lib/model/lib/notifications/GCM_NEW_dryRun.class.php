<?php
class GCM_NEW_dryRun {
    function __construct() {
    }
    public function sendNotificationNew($registrationIds, $details,$profileid) {
        $url = 'https://android.googleapis.com/gcm/send';
	$dataArray = FormatNotification::formater($details);
        $fields = array(
            'registration_ids' => $registrationIds,
            'data' => $dataArray);

	//if($details['COLLAPSE_STATUS']=="Y")
	//	$fields['collapse_key']=$details['NOTIFICATION_KEY'];

	if($details['NOTIFICATION_KEY']=='MATCHALERT')
		$details['TTL']='86400';
	
	if($details['TTL']!='' && $details['TTL']!=0)
	{
		$fields['time_to_live'] = intval($details['TTL']);
		$fields['delay_while_idle'] = true;
	}
	$fields['dry_run'] =true;

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
	if(is_array($response['results']))
	{
		foreach($response['results'] as $k=>$v)
		{
			$logArray[$count]['PROFILEID']=$profileid;
			$logArray[$count]['HTTP_STATUS_CODE']=$httpStatus;
			$logArray[$count]['REGISTRATION_ID']=$registrationIds[$k];
			$logArray[$count]['NOTIFICATION_KEY']=$details['NOTIFICATION_KEY'];
			if($v['registration_id'])
			{
				$oldRegistrationId = $registrationIds[$k];
				$newRegistrationId = $v['registration_id'];
				$logArray[$count]['STATUS_MESSAGE'] = "UPDATE_ID_TO:".$newRegistrationId;
			}
			elseif($v['error'])
				$logArray[$count]['STATUS_MESSAGE']=$v['error'];
			elseif($v['message_id'])
				$logArray[$count]['STATUS_MESSAGE']="SUCCESS";
			else
				$logArray[$count]['STATUS_MESSAGE']="OTHER";
			$count++;
		}
		if(is_array($logArray))
		{
			$logObj = new MOBILE_API_GCM_RESPONSE_LOG;
			$logObj->insertNew($logArray);
			unset($logArray);
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
