<?php
$unImpFieldCheck = 1;
$counterCheck = 4;
$changeUnimportantFieldOn = 8;
$sleepTime = 300;
$redis = new Redis();
$redis->pconnect(JsConstants::$ifSingleRedis['host'],JsConstants::$ifSingleRedis['port'],306,1);
$counter = 0;
while (1) {
        $unImpField = $redis->get("hideUnimportantFeatureAtPeakLoad");
        if($unImpField === false){
                $unImpField = JsConstants::$hideUnimportantFeatureAtPeakLoad;
        }
        $alertSMS = 0;
        
        if ($unImpField > $unImpFieldCheck) {
                $counter++;
        }
        if ($counterCheck <= $counter && $unImpField > $unImpFieldCheck) { // if timer is greater than equal to 4 and flag is greater than 1 send sms
                $alertSMS = 1;
        } elseif ($unImpField == $unImpFieldCheck) { // reset timer if flag is back to 1
                $counter = 0;
        }        
        if($counter >= $changeUnimportantFieldOn && $unImpField > $unImpFieldCheck){ // reset flag to 1 if time diff reached 1 hr
                $redis->set("hideUnimportantFeatureAtPeakLoad",0); // Set flag to 1
                sendJJSMS("reset");
                sendSlackmessage("FlagCount reset to 1 from $unImpField after ".($counter*5)." Seconds"); 
                $counter = 0;
        }elseif ($alertSMS == 1) {
                sendJJSMS($unImpField);
                sendSlackmessage("FlagCount set to $unImpField since ".($counter*5)." Seconds"); 
        }
        sleep($sleepTime); //sleep for 5 minutes
}

function sendJJSMS($flag = "") {
        $FROM_ID = "JSSRVR";
        $PROFILE_ID = "144111";
        $SMS_TO = array('9773889652','9818424749','9711304800','9953178503','9810300513','9711818214','9953457479','9873639543','9999216910','9868673707');
        $smsMessage = "Mysql Error Count have reached Threshold on FlagCount flag $flag within 5 minutes";
        foreach ($SMS_TO as $mobPhone) {
                $xml_head = "%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
                $xml_content = "%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22" . urlencode($smsMessage) . "%22%20PROPERTY=%220%22%20ID=%22" . $PROFILE_ID . "%22%3E%3CADDRESS%20FROM=%22" . $FROM_ID . "%22%20TO=%22" . $mobPhone . "e%22%20SEQ=%22" . $PROFILE_ID . "%22%20TAG=%22%22/%3E%3C/SMS%3E";
                $xml_end = "%3C/MESSAGE%3E";
                $xml_code = $xml_head . $xml_content . $xml_end;
                $fd = @fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send", "rb");
                if ($fd) {
                        $response = '';
                        while (!feof($fd)) {
                                $response.= fread($fd, 4096);
                        }
                        fclose($fd);
                        CommonUtility::logTechAlertSms($smsMessage, $mobPhone);
                }
        }
}

function sendSlackmessage($message)
{
   //$url = 'https://hooks.slack.com/services/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';
   $url = 'https://hooks.slack.com/services/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';
   $breaks = array("<br />","<br>","<br/>");
   $message = str_ireplace($breaks, "\n", $message);
   $data = array("text" => $message );
   $ch=curl_init($url);
   $data_string = json_encode($data);
   curl_setopt($ch, CURLOPT_HTTPHEADER,
      array("Content-type: application/json"));
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);


   $result = curl_exec($ch);
   curl_close($ch);

   echo $result;
}


?>
