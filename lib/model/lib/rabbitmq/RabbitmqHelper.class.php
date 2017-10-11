<?php
use MessageQueues as MQ;
/* This class consists of common helper functions used by Rabbitmq files. */
class RabbitmqHelper
{
 
/**
* 
* Function for sending alert mail to destination email address and logging message in destination log file
* 
* @access public
* @param $message
**/
  public static function sendAlert($message,$to="default")
  {
    $exception = new Exception($message);
    if($exception->getTrace())
    {
      $consumerName = $exception->getTrace()[0]['file'];
    }
    LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $exception, array(LoggingEnums::CONSUMER_NAME => $consumerName, LoggingEnums::MODULE_NAME => "RabbitmqConsumers"));
    $emailAlertArray=array("queueMail"=>"",
                          "queueSmsGcm"=>"",
                          "browserNotification"=>"nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com",
			  "UpdateSeen"=>"eshajain88@gmail.com,lavesh.rawat@gmail.com",
                          "default"=>"pankaj.khandelwal@jeevansathi.com,tanu.gupta@brijj.com,ankita.g@jeevansathi.com,sanyam.chopra@jeevansathi.com,nitish.sharma@jeevansathi.com",
                          "loggingQueue"=>"palash.chordia@jeevansathi.com,nitesh.s@jeevansathi.com",
                          "screening" => "niteshsethi1987@gmail.com,nikmittal4994@gmail.com",
                          "instantEoi" => "nikmittal4994@gmail.com,niteshsethi1987@gmail.com",
                          "writeMsg" => "niteshsethi1987@gmail.com,nikmittal4994@gmail.com",
                          "updateSeenProfile" => "niteshsethi1987@gmail.com",
                          "updateSeen" => "niteshsethi1987@gmail.com",
                          "memoryAlarmAlert"=>"pankaj.khandelwal@jeevansathi.com,lavesh.rawat@jeevansathi.com,ankita.g@jeevansathi.com,nitish,sharma@jeevansathi.com"
                          );            
    
    $emailTo=$emailAlertArray[$to];
    $subject = $to." Rabbitmq Error @".JsConstants::$whichMachine;
    if($to == "browserNotification")
        $subject = "Notification RMQ Error";
    $message=$message.".....site->".JsConstants::$siteUrl."...@".date('d-m-Y H:i:s');
    $errorLogPath=JsConstants::$cronDocRoot.'/log/rabbitError.log';
    if(file_exists($errorLogPath)==false)
      exec("touch"." ".$errorLogPath,$output);
    error_log($message,3,$errorLogPath);
    // enable alerts for these
    $arrEnableAlert = array("screening","instantEoi","writeMsg","loggingQueue","updateSeenProfile","updateSeen");

    if(in_array($to, $arrEnableAlert))
    {
  //    SendMail::send_email($emailTo,$message,$subject);
    }
    self::killConsumerForErrorPattern($message,$consumerName);
  }

  public static function sendChatConsumerAlert($message)
  {    
    $emailTo="nitishpost@gmail.com,lavesh.rawat@gmail.com,pankaj139@gmail.com,maxspeed83@gmail.com";
    $subject="Rabbitmq Chat php consumer error @".JsConstants::$whichMachine;
    $message=$message.".....site->".JsConstants::$siteUrl."...@".date('d-m-Y H:i:s');
    SendMail::send_email($emailTo,$message,$subject);           
  }

  /**
   * 
   * Function for curl request to rabbitmq api($url-param)
   *<p> returns response</p>
   * 
   * @access public
   * @param $url(api url),$rabbitmq_credentials(rabbitmq username and password)
   */
  public static function curlToRabbitmqAPI($url,$rabbitmq_credentials)
  {
    $curl=  curl_init();
    curl_setopt($curl, CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,True);
    curl_setopt($curl, CURLOPT_USERPWD,$rabbitmq_credentials);

    $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
    curl_setopt($curl, CURLOPT_HEADER, $header);
    curl_setopt($curl, CURLOPT_USERAGENT,"JsInternal");    

    $response= curl_exec($curl);

    // remove header from curl Response 
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $response = substr($response, $header_size);

    curl_close($curl);
    $result =json_decode($response); 
    return $result;
  }

  /*add msg to log file
  *@param: filePath(relative to branch path in function),$msg,$truncateLogDaily
  */
  public static function addRabbitmqMsgLog($filePath,$msg)
  {
    $logPath = JsConstants::$cronDocRoot.$filePath;
    if(file_exists($logPath)==false)
      exec("touch"." ".$logPath,$output);
    file_put_contents($logPath, $msg."\n",FILE_APPEND);
  }

  /*all queues and exchange declaration common to producer and consumer
  * @param : $channel,$key
  * @return : $channel
  */
  public static function RMQDeclaration($channel,$key)
  {
    if($key=="notification")
    {
      $channel->exchange_declare(MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"], MQ::$DELAYED_NOTIFICATION_EXCHANGE["TYPE"], MQ::PASSIVE, MQ::$DELAYED_NOTIFICATION_EXCHANGE["DURABLE"], MQ::AUTO_DELETE);
      $channel->exchange_declare(MQ::$INSTANT_NOTIFICATION_EXCHANGE["NAME"], MQ::$INSTANT_NOTIFICATION_EXCHANGE["TYPE"] , MQ::PASSIVE, MQ::$INSTANT_NOTIFICATION_EXCHANGE["DURABLE"], MQ::AUTO_DELETE);
      $channel->queue_declare(MQ::$INSTANT_NOTIFICATION_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

      //create queues and bind to delayed exchange for scheduled notifications
      foreach (MQ::$scheduledNotificationBindingKeyArr as $key => $value) 
      {
        $channel->queue_declare(MQ::${$key}, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, array(
          "x-dead-letter-exchange" => array("S", MQ::$INSTANT_NOTIFICATION_EXCHANGE["NAME"]),
          "x-message-ttl" => array("I", MQ::$scheduledNotificationDelayMappingArr[MQ::${$key}]*MQ::$notificationDelayMultiplier*1000)/*,
         "x-expires" => array("I", MQ::$notificationQueueExpiryTime*MQ::$notificationDelayMultiplier*1000)*/
        ));
        $channel->queue_bind(MQ::${$key}, MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],$value);
      }
      $channel->queue_bind(MQ::$INSTANT_NOTIFICATION_QUEUE, MQ::$INSTANT_NOTIFICATION_EXCHANGE["NAME"]);
      $channel->queue_declare(MQ::$MA_NOTIFICATION_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, 
					array(
						"x-dead-letter-exchange" => array("S", MQ::$INSTANT_NOTIFICATION_EXCHANGE["NAME"]),
    "x-message-ttl" => array("I", MQ::$scheduledNotificationDelayMappingArr[MQ::$MA_NOTIFICATION_QUEUE]*MQ::$notificationDelayMultiplier*1000),
                        "x-dead-letter-routing-key"=>array("S",MQ::$INSTANT_NOTIFICATION_QUEUE)
					));
      $channel->queue_bind(MQ::$MA_NOTIFICATION_QUEUE, MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$MA_NOTIFICATION_QUEUE);
      return $channel;
    }
    elseif($key=='notificationLog'){
       $channel->exchange_declare(MQ::$NOTIFICATION_LOG_EXCHANGE["NAME"], MQ::$NOTIFICATION_LOG_EXCHANGE["TYPE"] , MQ::PASSIVE, MQ::$NOTIFICATION_LOG_EXCHANGE["DURABLE"], MQ::AUTO_DELETE);
       $channel->queue_declare(MQ::$NOTIFICATION_LOG_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
       $channel->queue_bind(MQ::$NOTIFICATION_LOG_QUEUE, MQ::$NOTIFICATION_LOG_EXCHANGE["NAME"]);	
       return $channel;	
    }		
    else
      return null;
    
  }
  
  public function killConsumerForCommand($command){
    exec("ps aux | grep \"".$command."\" | grep -v grep | awk '{ print $2 }'", $output);
    //echo "\n".$command."-";
    //print_r($output);
    if(!empty($output) && is_array($output))
    {
      foreach ($output as $key => $value) 
      {
        $count1 = shell_exec("ps -p ".$value." | wc -l") -1;
        if($count1 >0)
          exec("kill -9 ".$value);
      }
    }
    unset($output);
  }

  public static function sendRMQAlertSMS($msg=''){
    include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
    $mobileNumberArr = array("nitesh"=>"9953178503","lavesh"=>"9818424749","pankaj"=>"9810300513");
    if(JsConstants::$whichMachine == "test"){
        $mobileNumberArr = array("nitesh"=>"9953178503","lavesh"=>"9818424749","pankaj"=>"9810300513");
    }
    foreach($mobileNumberArr as $k=>$v){
        RabbitmqHelper::smsRMQ($v,$msg);
    }
  }
  
  public static function smsRMQ($mobile,$msg){
    $t = time();
    if($msg){
        $message    = "Mysql Error Count have reached ".$msg." $t";
    }
    else{
        $message    = "Mysql Error Count have reached Rabbitmq killed $t";
    }
    $from           = "JSSRVR";
    $profileid      = "144111";
    $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
   
  }
  
  /*
   * Function to modify consumer data so as to add a flag on the basis of which any requeue of the message need to be checked
   * @param: consumer data
   * @return modified data
   */
  public static function modifyDataForConsumer($data){
        if(MQ::$flagForDuplicateDataCheck && $data && is_array($data)){
            $data["processed"] = "1";
        }
        return $data;
    }
    
   /*
    * Function to check whether processed flag exist in the consumer data and on the basis of this send delivery acknowledgement
    * @param: consumer data
    * @return modified data
   */
    public static function isQueueDataProcessed($data,$msg){
        if(MQ::$flagForDuplicateDataCheck && $data && is_array($data) && $data["processed"] == "1"){
            try{
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                //CommonUtility::sendAlertMail("nitishpost@gmail.com", "Queue data already processed", "Queue data already processed");
                return true;
            } 
            catch(Exception $exception){
                $str="\nRabbitMQ Error in consumer, Unable to send +ve acknowledgement: " .$exception->getMessage()."\tLine:".__LINE__;
                RabbitmqHelper::sendAlert($str);
            }
        }
        return false;
    }
    
    /*
     * Function to do logging of rabbitmq timeouts
     * The "time" key should be first thing to be printed in the log file.
     */
    public static function rmqLogging($logPath="",$start,$end,$reqId,$threshold,$dataArray){
        $diff = $end-$start;
        if($logPath == ""){
            $logPath = JsConstants::$cronDocRoot.'log/rabbitTime'.date('Y-m-d').'.log';
            //$logPath = "/data/applogs/Logger/".date('Y-m-d').'rabbitTimePublish.log';
        }
        if($diff >= $threshold){
            $logText["time"] = time();
            $logText["connTime"] = round($diff,4);
            $logText["requestId"] = $reqId;
            $logText["source"] = $dataArray["source"];
            if(file_exists($errorLogPath)==false)
                exec("touch"." ".$logPath,$output);
            error_log(json_encode($logText)."\n",3,$logPath);
        }
    }
    
    
    public static function killConsumerForErrorPattern($message,$consumerName){
        $errorPatternArray = array("MySQL server has gone away");        
        $logPath = JsConstants::$cronDocRoot.'log/rabbitErrorToKillConsumer'.date('Y-m-d').'.log';
        $logText["source"] = "In function killConsumerForErrorPattern";
        $logText["message"] = $message;
        self::rmqLogging($logPath,0,0,0,0,$logText);
        foreach($errorPatternArray as $key => $val){
            if(strpos($message, $val) !== false){
                //CommonUtility::sendAlertMail("nitishpost@gmail.com", "MySQL gone away $consumerName killed at ".JsConstants::$siteUrl, "MySQL gone away in consumer");
                //die("ConsumerKilled");
            }
        }
    }
  
}
?>
