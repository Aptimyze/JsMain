<?php

/**
 * Description of GCM
 *
 */

class GCM {

    function __construct($sendParallelNotification=false) {
    	$this->sendParallelNotification = $sendParallelNotification;
    	if($sendParallelNotification==true){
	    	$this->multiCurlObj = curl_multi_init();
	    	$this->curlArr = array();
	    	$this->multiCurlThreshold = NotificationEnums::$multiCurlReqConfig["threshold"];
	    	$this->pendingCurlRequestCount = 0;
	    	$this->profileDetailsPool = array();
	    }
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
		//$start = microtime();
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
        /*$end = microtime();
        $errorLogPath=JsConstants::$cronDocRoot.'/log/rabbitError.log';
    	$diff = $end-$start;
    	error_log("start:".$start." and end: ".$end.", single curl-".$diff."\n",3,$errorLogPath);*/
        curl_close($ch);
        return $result;
    }

    /**
     * Sending Push Notification
     */
    public function sendMultipleParallelNotification($registrationIds, $details,$profileid,$log=true) {
		$url = 'https://android.googleapis.com/gcm/send';
		$dataArray = FormatNotification::formater($details);
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

		$this->curlArr[$this->pendingCurlRequestCount] = curl_init();
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_URL, $url);
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_POST, true);
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount],CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($this->curlArr[$this->pendingCurlRequestCount], CURLOPT_TIMEOUT, 4);
		
		curl_multi_add_handle($this->multiCurlObj, $this->curlArr[$this->pendingCurlRequestCount]);

		//store profile details for later processing
		$this->profileDetailsPool[$this->pendingCurlRequestCount] = array("PROFILEID"=>$profileid,"details"=>$details,"registrationIds"=>$registrationIds,"log"=>$log);
		++$this->pendingCurlRequestCount;
		
		//execute batch of curl requests if count reached the threshold limit
		$this->executeMultiCurlRequest();
    }

    public function executeMultiCurlRequest($executeAllRequests=false){
    	if($this->pendingCurlRequestCount > 0 && ($this->pendingCurlRequestCount >= $this->multiCurlThreshold || $executeAllRequests == true)){
    		
			//$start = microtime();
			do {
				curl_multi_exec($this->multiCurlObj,$running);
			}while($running > 0);

			for($i = 0; $i < $this->pendingCurlRequestCount; $i++)
			{
				$invalidStatus ='I';
			    $result = curl_multi_getcontent($this->curlArr[$i]);
			   
			    $httpStatus = curl_getinfo($this->curlArr[$i], CURLINFO_HTTP_CODE);
				$response = json_decode($result, true);
				$count = 0;
				if(is_array($this->profileDetailsPool[$i]) && $this->profileDetailsPool[$i]['log'])
				{
					if(is_array($response['results']))
					{
						$statusArr =array();
						$msgId =$this->profileDetailsPool[$i]['details']['MSG_ID'];
						$schedduledAppNotificationObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
						$notificationLog = new MOBILE_API_NOTIFICATION_LOG;
						$registrationIdObj = new MOBILE_API_REGISTRATION_ID;
						foreach($response['results'] as $k=>$v)
						{
							$logArray[$count]['PROFILEID']=$this->profileDetailsPool[$i]["PROFILEID"];
							$logArray[$count]['HTTP_STATUS_CODE']=$httpStatus;
							$logArray[$count]['REGISTRATION_ID']=$this->profileDetailsPool[$i]["registrationIds"][$k];
							$logArray[$count]['NOTIFICATION_KEY']=$this->profileDetailsPool[$i]["details"]['NOTIFICATION_KEY'];
							$oldRegistrationId = $this->profileDetailsPool[$i]["registrationIds"][$k];
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
								$this->sendNotification(array($newRegistrationId), $detailsForRegIdChange,$this->profileDetailsPool[$i]["PROFILEID"],false);
							}
							elseif($v['error']){
								$logArray[$count]['STATUS_MESSAGE']=$v['error'];
								$statusArr[] =$invalidStatus;
								if($oldRegistrationId && ($v['error']=='InvalidRegistration' || $v['error']=='NotRegistered')){
									$registrationIdObj->deleteRegId($oldRegistrationId);
                                    //Changed below to insert entry in APP_UNINSTALL to send mailer 
                                    $appUninstallObj = new NOTIFICATION_APP_UNINSTALL();
                                    $appUninstallObj->insertUninstalledProfiles($this->profileDetailsPool[$i]["PROFILEID"],$oldRegistrationId);
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
			}
			/*$end = microtime();
	        $errorLogPath=JsConstants::$cronDocRoot.'/log/rabbitError.log';
	    	$diff = $end-$start;
	    	error_log("start: ".$start." and end: ".$end.", multi curl for curls ".$this->pendingCurlRequestCount." is ".$diff."\n",3,$errorLogPath);*/
			$this->pendingCurlRequestCount = 0;
			$this->profileDetailsPool = array();
			$this->curlArr = array();
		}
    }

    function __destruct(){
    	if($this->sendParallelNotification == true){
    		if($this->multiCurlObj != null){
    			curl_multi_close($this->multiCurlObj);
    			unset($this->profileDetailsPool);
				unset($this->curlArr);
    		}
    	}
	}
}
?>
