<?
/*
This php script checks if RabbitMQ is working,number of active consumer instances and sends alert if queues have number of messages more than N(a pre specified limit). Consumer instance is run if there are queued messages 
present in queues on the second server.
*/

class cronChatConsumerRecoveryTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronRabbitmqRecovery
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronChatConsumerRecovery';
    $this->briefDescription    = 'Checks if RabbitMQ is working,checks memory consumption, number of active consumer instances and whether queue do have number of messages more than N';
    $this->detailedDescription = <<<EOF
     The [cronChatConsumerRecovery|INFO] Checks if RabbitMQ is working, checks memory consumption, number of active consumer instances and send alert if queue do have number of messages more than N:
     [php symfony cron:cronChatConsumerRecovery] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

   /**
   * 
   * This function calls Api's for aliveness test, Message count and alarm check (memory alarm and disk alarm) for respective servers as per the $serverid mentioned
   * 
   * <p> returns messageCount if $serverid = 'SECOND_SERVER' </p>
   * @access private
   * @param $serverid
   */ 
  private function callRabbitmqServerApi($serverid,$queuesArr)
  {
    $messageCount=0;
    //checks whether server is alive or not.
    $aliveApi_url="/api/aliveness-test/%2F";
    $status_result=$this->checkRabbitmqServerStatus($serverid,$aliveApi_url);
    if(!$status_result || strtolower($status_result->status)!=="ok")
    {
      $str="\nRabbitmq Error Alert: Rabbitmq Server with host: ".JsConstants::$rabbitmqConfig[$serverid]['HOST']." is down.";
      RabbitmqHelper::sendChatConsumerAlert($str);
    }
    else
    {
      //checks whether number of pending messages in queues is more than limit or not
      //$messageLimit=MessageQueues::MESSAGE_LIMIT;
      $queueApi_url="/api/queues/%2F";
      $resultQueues=$this->checkRabbitmqServerStatus($serverid,$queueApi_url);
      if(is_array($resultQueues))
      {
        foreach($resultQueues as $arr)
        {
          $queue_data=$arr;
          //print_r($queue_data->name."----");
          if(in_array($queue_data->name, $queuesArr)){
            
            if(MessageQueues::$upperMessageLimitPerQueue[$queue_data->name])
              $messageLimit = MessageQueues::$upperMessageLimitPerQueue[$queue_data->name];
            else
              $messageLimit = MessageQueues::$upperMessageLimitPerQueue["default"];
       
            if($queue_data->name==="aliveness-test")
            {
                continue;
            }
            if($serverid=="SECOND_SERVER" && $queue_data->messages>0)
            {
              $messageCount=$messageCount + $queue_data->messages;
            }
            if($serverid=="FIRST_SERVER" && $queue_data->messages_ready>$messageLimit)
            {
              $str="\nRabbitmq Error Alert: Number of unconsumed messages pending in {$queue_data->name} is  {$queue_data->messages_ready} on first server";
              RabbitmqHelper::sendChatConsumerAlert($str);
            } 
           
            if($serverid=="FIRST_SERVER" && $queue_data->messages_unacknowledged>$messageLimit)
            {
              $str="\nRabbitmq Error Alert: Number of unacknowledged messages pending in {$queue_data->name} is  {$queue_data->messages_unacknowledged} on first server. Restarting the consumers";
              
              exec("ps aux | grep \"".MessageQueues::CRONCHAT_CONSUMER_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $chatConsumerCount);
              if(!empty($chatConsumerCount) && is_array($chatConsumerCount))
                foreach ($chatConsumerCount as $key => $value) 
                {
                  $count1 = shell_exec("ps -p ".$value." | wc -l") -1;
                  if($count1 >0)
                    exec("kill -9 ".$value);
                }

              for($i=1;$i<=MessageQueues::CHAT_CONSUMER_COUNT ;$i++) {
                passthru(JsConstants::$php5path." ".MessageQueues::CRONCHAT_CONSUMER_STARTCOMMAND." > /dev/null &");
              }

              RabbitmqHelper::sendChatConsumerAlert($str);
            }
          }
        }
      }
      
    }
    
    return $messageCount;
  }
  
  /**
   * 
   * Function for checking server status with $serverid passed as param through curl request to specific rabbitmq API.
   * 
   * <p> returns output of curl request </p>
   * @access private
   * @param $serverid,$api_url
   */
  private function checkRabbitmqServerStatus($serverid,$api_url)
  {
    $server_credentials=JsConstants::$rabbitmqConfig[$serverid];
    $rabbitmq_mgmnt_port=JsConstants::$rabbitmqManagementPort;
    $rabbitmq_host=$server_credentials['HOST'];
    $rabbitmq_user=$server_credentials['USER'];
    $rabbitmq_pswd=$server_credentials['PASS'];
    $rabbitmq_creds="$rabbitmq_user:$rabbitmq_pswd";
    $rabbitmq_base_url="http://{$rabbitmq_host}:{$rabbitmq_mgmnt_port}";    
    $rest_url="{$rabbitmq_base_url}{$api_url}";
    $response=RabbitmqHelper::curlToRabbitmqAPI($rest_url,$rabbitmq_creds);
    return $response;
  }

  /*restart inactive instances of consumer
  * $totalInstancesNum,$CONSUMER_STARTCOMMAND,$sendAlertTo
  */
  private function restartInactiveConsumer($totalInstancesNum,$CONSUMER_STARTCOMMAND,$sendAlertTo="default")
  {
    $activeInstancesNum=shell_exec("ps ax | grep "."'".JsConstants::$php5path." ".$CONSUMER_STARTCOMMAND."'"." | wc -l") -2; //reason for -2:additional count for cronexecuteConsumer and tile line subtracted. 
    $inactiveInstancesNum=$totalInstancesNum - $activeInstancesNum;    
    if($inactiveInstancesNum > 0)
    {
      $str="\nError Alert: ".$inactiveInstancesNum." consumer instances are not running due to some error, restarting instances....";
      RabbitmqHelper::sendChatConsumerAlert($str);
      while($inactiveInstancesNum > 0)
      {
        passthru(JsConstants::$php5path." ".$CONSUMER_STARTCOMMAND." > /dev/null &"); 
        $inactiveInstancesNum = $inactiveInstancesNum -1;
      }      
    }

  }
  /**
   * 
   * Function for executing cron- checks status of rabbitmq server, checks memory consumption, no of messages pending in queues,
   * consuming messages queued on the second server and checking the number of active consumer instances.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
     $queuesArr = [MessageQueues::CHAT_MESSAGE];
    if(!sfContext::hasInstance())
    sfContext::createInstance($this->configuration);
    $this->callRabbitmqServerApi("FIRST_SERVER",$queuesArr);
    
    if(MessageQueues::FALLBACK_STATUS==true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0)
    {
      $messageCount=$this->callRabbitmqServerApi("SECOND_SERVER",$queuesArr);
    }
    
    $this->restartInactiveConsumer(MessageQueues::CHAT_CONSUMER_COUNT,MessageQueues::CRONCHAT_CONSUMER_STARTCOMMAND,"default");
    //runs consumer to consume accumulated messages in queues on the second server if fallback status flag is set.
    if(MessageQueues::FALLBACK_STATUS==true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0)
    {
      if($messageCount > 0)
      { 
        $chatConsumerObj=new chatMessageConsumer('SECOND_SERVER',$messageCount);  
        $chatConsumerObj->receiveMessage();
      }
    }    
  }
}
?>
