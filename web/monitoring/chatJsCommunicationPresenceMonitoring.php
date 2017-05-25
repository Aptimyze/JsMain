<?php
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$mobileNumberArr = array("9910244159","9650879575","9873639543","8989931104",/*"9810300513",*/"9868673709");
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");
/*$mqQueuesArr = array("profile-created-queue","profile-deleted-queue","roster-created-acceptance","roster-created-acceptance_sent","roster-created-intrec","roster-created-intsent","roster-created-shortlist","roster-updated-queue","roster-created-dpp","chat");
$msgLimitPerQueue = 5000;
$queuesWithExtraLimit = array("roster-created-dpp"=>10000);*/
/*$status = sendPresenceRequest();
if($status!='200')
{
        $status = sendPresenceRequest();
        if($status!=200)
        {
                foreach($mobileNumberArr as $k=>$v)
                {
                       // sms($v);
                }
        }
}*/
$serverUrlArray = array("http://10.10.18.75:8590","http://10.10.18.72:8590","http://10.10.18.104:8590");
foreach($serverUrlArray as $k=>$v){
        $status = sendPresenceRequest($v);
        if($status!='200'){
                $status = sendPresenceRequest($v);
               
                if($status!=200)
                {
					$status = sendPresenceRequest($v);
					 if($status!='200'){						
                        mail ("reshu.rajput@jeevansathi.com,lavesh.rawat@gmail.com,pankaj139@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com","Error in presence api @".$v,"Please check");
						foreach($mobileNumberArr as $n=>$no)
						{
							sms($no);
						}
					}
                }
        }       
}
//get data about rabbitmq queues
//$queueResponse = checkRabbitmqQueueMsgCount("FIRST_SERVER");
//check overflow in queues and send alert in case of overflow
//checkForQueueOverflow($mqQueuesArr,$queueResponse);

/*function checkForQueueOverflow($queueArr,$queueResponse){
        global $msgLimitPerQueue,$queuesWithExtraLimit;
        if(is_array($queueResponse)){
                foreach($queueResponse as $arr){
                        $queue_data=$arr;
                        $msgLimit = $msgLimitPerQueue;
                        if($queuesWithExtraLimit[$queue_data->name]){
                                $msgLimit = $queuesWithExtraLimit[$queue_data->name];
                        }
                        //echo $queue_data->name."---".$msgLimit."\n";
                        if(in_array($queue_data->name, $queueArr) && $queue_data->messages_ready>$msgLimitPerQueue)
                        {
                                $overflowQueueArr[] = $queue_data->name."(".$queue_data->messages_ready.")";
                        }
                }
        }
        //die;
        unset($queueResponse);
        //print_r($overflowQueueArr);die;
        if($overflowQueueArr && count($overflowQueueArr)>0){
                $queueStr = implode(",", $overflowQueueArr);
                //var_dump($queueStr);die;
                mail ("lavesh.rawat@gmail.com,pankaj139@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com","Overflow in chat queues @10.10.18.62","Please check queues - ".$queueStr);
        }
}
function checkRabbitmqQueueMsgCount($serverid){
        $server_credentials=JsConstants::$rabbitmqConfig[$serverid];
        $rabbitmq_mgmnt_port=JsConstants::$rabbitmqManagementPort;
        $api_url = "/api/queues/%2F";
        $rabbitmq_creds="{$server_credentials['USER']}:{$server_credentials['PASS']}";
        $rabbitmq_url="http://{$server_credentials['HOST']}:{$rabbitmq_mgmnt_port}{$api_url}"; 
        //echo $rabbitmq_url."\n";die;
        $response=RabbitmqHelper::curlToRabbitmqAPI($rabbitmq_url,$rabbitmq_creds);
        //print_r($response);
        return $response;
}*/

function sendPresenceRequest($url)
{
        $url = $url."/jspresence/v1/presence?pfids=9061321";
        $res = CommonUtility::sendCurlGetRequest($url,'',10);
        $res = (array) json_decode($res);
		$res =  $res["header"];
        $status = $res->status;
       
        return $status;
}
function sms($mobile)
{
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached jscommunicationPresence $date within 5 minutes";
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
}
