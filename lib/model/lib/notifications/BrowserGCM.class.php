<?php
/*
 * GCM request for Browser based notification
 */
class BrowserGCM {

    private $fields;
    private $url;
    private $headers;

    function __construct($notificationType,$dataArray=array()) 
    {
        $this->url = BrowserNotificationEnums::GCM_REQUEST_URL;
        $this->headers = BrowserNotificationEnums::$gcmHeaders[$notificationType];
        if(is_array($dataArray) && $dataArray)
        {
            $this->fields = $dataArray;
        }
    }

    /**
     * Sending Push Browser Notification---
     * send curl request to GCM and update database records(if $log flag is true)
     * @param : $regIds(array of single registration id),$log(flag-true/false),$handleRegInactive(flag-true/false)
     * @return : none
     */
    public function sendBrowserNotification($log=true,$handleRegInactive=false) {
        $regIds = $this->fields["registration_ids"];
        $msgIds = array($this->fields["data"]["MSG_ID"]);
        if($this->fields["data"]['TTL']!='' && $this->fields["data"]['TTL']!=0)
        {
            $this->fields['time_to_live'] = intval($this->fields["data"]['TTL']);
            $this->fields['delay_while_idle'] = true;
        }
        //send curl request to GCM and fetch response
        $response = $this->sendGCMCurlRequest($this->url,$this->headers,$this->fields);
		if($log==true)
		{
			if($response && is_array($response['results']))
			{
				//log sent details in DB
				$this->logSentDetails($response['results'],$regIds,$msgIds,$handleRegInactive);
			}
		}
    }

    /**
     * Sending curl request to GCM---
     * @param : $url,$headers,$fields
     * @return : $response(GCM response if returned else null)
     */
    private function sendGCMCurlRequest($url,$headers,$fields)
    {
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        if($headers)
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($fields)
        	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        $result = curl_exec($ch);
		$response = json_decode($result, true);
		curl_close($ch);
		if($response)
			return $response;
		else 
		{
            JsNotificationsConsume::$sendAlert = JsNotificationsConsume::$sendAlert+1;
            if(JsNotificationsConsume::$sendAlert==1)
            {
                //CRMAlertManager::sendMailAlert("no gcm response to live notification","test");
            }
            return null;
        }
    }

    /**
     * log notification sent details---
     * @param : $result,$regIds,$msgIds,$handleRegInactive(flag-true/false)
     * @return : none
     */
    private function logSentDetails($result,$regIds,$msgIds,$handleRegInactive=false)
    {
		$notificationObj = new BrowserNotification();	
		$index = 0;
		$updateRegArr = array();
		foreach($result as $k=>$v)
		{
			if($v['registration_id'])
			{
				$updateRegArr[$index]["old_regid"] = $regIds[$k];
                $updateRegArr[$index]["new_regid"] = $v['registration_id'];
                $index++;
                $updateArr = array("RESPONSE"=>"REGID_UPDATE","SENT_TO_GCM"=>'Y',"STATUS"=>BrowserNotificationEnums::GCM_REGID_EXPIRED);
			}
			else if($v['message_id'])
			{
				$updateArr = array("RESPONSE"=>ltrim($v['message_id'],"0:"),"SENT_TO_GCM"=>'Y',"STATUS"=>BrowserNotificationEnums::GCM_SUCCESS);
			}
			else if($v['error'])
			{
				$updateArr = array("RESPONSE"=>$v['error'],"SENT_TO_GCM"=>'Y',"STATUS"=>BrowserNotificationEnums::GCM_FAILURE);
			}
			else
			{
				$updateArr = array("RESPONSE"=>"INVALID","SENT_TO_GCM"=>'Y',"STATUS"=>BrowserNotificationEnums::GCM_INVALID);
			}
			
			if($updateArr)
				$notificationObj->updateSentNotificationDetails("MSG_ID",$msgIds[$k],$updateArr);
			
		}
		unset($notificationObj);
		if($updateRegArr && $handleRegInactive==true)
		{
			$this->handleRegIdsUpdate($updateRegArr);
		}
		unset($updateRegArr);
    }

    /*update expired registration id with new one provided by GCM
    * @params : $updateRegArr
    * @return : none
    */
    private function handleRegIdsUpdate($updateRegArr)
    {
    	if($updateRegArr)
    	{
            $notificationObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
    		foreach ($updateRegArr as $key => $value) {
                $notificationObj->updateRegId("REG_ID",$value["old_regid"],$value["new_regid"]);  
                $this->fields["registration_ids"] = array($value["new_regid"]);
                $this->sendBrowserNotification(false); 
            }
    		unset($notificationObj);

    	}
    }
}
