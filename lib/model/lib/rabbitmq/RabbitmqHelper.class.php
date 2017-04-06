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
    $emailAlertArray=array("queueMail"=>"",
                          "queueSmsGcm"=>"",
                          "browserNotification"=>"nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com",
			  "UpdateSeen"=>"eshajain88@gmail.com,lavesh.rawat@gmail.com",
                          "default"=>"pankaj.khandelwal@jeevansathi.com,tanu.gupta@brijj.com,ankita.g@jeevansathi.com,sanyam.chopra@jeevansathi.com,nitish.sharma@jeevansathi.com",
                          "loggingQueue"=>"palash.chordia@jeevansathi.com,nitesh.s@jeevansathi.com",
                          "screening" => "nitesh.s@jeevansathi.com,nikmittal4994@gmail.com",
                          "instantEoi" => "nikmittal4994@gmail.com",
                          );            
    
    $emailTo=$emailAlertArray[$to];
    $subject="Rabbitmq Error @".JsConstants::$whichMachine;
    if($to == "browserNotification")
        $subject = "Notification RMQ Error";
    $message=$message.".....site->".JsConstants::$siteUrl."...@".date('d-m-Y H:i:s');
    $errorLogPath=JsConstants::$cronDocRoot.'/log/rabbitError.log';
    if(file_exists($errorLogPath)==false)
      exec("touch"." ".$errorLogPath,$output);
    error_log($message,3,$errorLogPath);
    if($to == "screening" || $to == "instantEoi")
    {
      SendMail::send_email($emailTo,$message,$subject);
    }
  }

  public static function sendChatConsumerAlert($message)
  {    
    $emailTo="nitishpost@gmail.com,nsitankita@gmail.com,lavesh.rawat@gmail.com,pankaj139@gmail.com,maxspeed83@gmail.com";
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
    $response= curl_exec($curl);
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
}
?>
