<?php
class IOS implements NotificationEngine {
    function __construct() {

	// Certificate details
	$this->passphrase = JsConstants::$passphrase;
	$this->certKey = JsConstants::$iosCertificateKey;
	$this->iosUrl = JsConstants::$iosApnsUrl;
    }

    public function sendNotification($registrationIds, $details, $profileid='') 
    {
	$this->profileid =$profileid;
	$this->logObj = new MOBILE_API_IOS_RESPONSE_LOG;
	$this->notificationKey = $details['NOTIFICATION_KEY'];
	$this->msgId = $details['MSG_ID'];
	$this->resCodeArr =NotificationEnums::$iosResponseCodeArr;
 	if($details['TTL'])
                $tokenExpiry = intval($details['TTL']);
	$tokenIdentifier = $this->msgId;


	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certKey);
	stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);
	// Open a connection to the APNS server
	$fp = stream_socket_client($this->iosUrl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	if(!$fp){
		// IOS APNS Failed Connection Response Logging 
		$errResponse = "Failed to connect: $err $errstr".PHP_EOL;
		$this->logObj->insert($this->profileid, $registrationIds[0], $this->msgId ,'500', $errResponse, $this->notificationKey);
		return;
	}
	stream_set_blocking ($fp, 0);
	$dataArray =FormatNotification::formaterForIos($details);
	
	foreach($registrationIds as $key=>$deviceToken){
		
		// Message details start:
		$message = $details['MESSAGE'];
		$title = $details['TITLE'];
		$body['aps'] = array('alert' => array("body" => $message,"title"=>$title),'badge' =>1,'sound'=>'default','mutable-content'=>'1','category'=>'JSIMAGE');
		$body['otherCustomURL'] = $details['PHOTO_URL'];
               	$body['Arguments'] = $dataArray;
        //print_r($body);die;
		// Encode the payload as JSON
                $payload = json_encode($body);

		// Build the binary notification -Enhanced Notification
		//$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		$msg = pack("C", 1) . pack("N", $tokenIdentifier) . pack("N", $tokenExpiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		usleep(100000);
		$this->checkAppleErrorResponse($fp,$deviceToken);
	    }
	    // Close the connection to the server	
	    fclose($fp);	
	}

	// Resonse handling
	public function checkAppleErrorResponse($fp,$registrationId){

		/* byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID),Should return nothing if OK.	
		NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait forever when there is no response to be sent.*/
		$apple_error_response = fread($fp, 6);
		if($apple_error_response){
			// Failure handling: unpack the error response (first byte 'command" should always be 8)
			$error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);
			$status_code =$error_response['status_code'];	
			if($status_code)
				$status_code =$this->resCodeArr[$status_code];
			else	
				$status_code = $error_response['status_code'].'-Not listed';

			$msg ='Response Command:'.$error_response['command'];
			$msg .='Identifier:'.$error_response['identifier'];
			$msg .='Status:'.$status_code;
			$this->logObj->insert($this->profileid,$registrationId, $this->msgId ,$status_code, $msg, $this->notificationKey);
			unset($msg);

                        // Condition to delete invalid device token from Registartion table
                        if($status_code=='8'){
                                $registrationIdObj ==new MOBILE_API_REGISTRATION_ID;
                                $registrationIdObj->deleteRegId($registrationId);
                        }
		}
	}
}
?>
