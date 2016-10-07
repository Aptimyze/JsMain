<?php
/*
This php script checks if RabbitMQ is working, checks memory consumption (memory alarm and/or disk alarm are raised if memory/disk consumption is more than the specified limit),
number of active consumer instances and sends alert if queues have number of messages more than N(a pre specified limit). Consumer instance is run if there are queued messages 
present in queues on the second server.
*/

class cronRabbitmqRecovery extends sfBaseTask
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
    $this->name                = 'cronRabbitmqRecovery';
    $this->briefDescription    = 'Checks if RabbitMQ is working,checks memory consumption, number of active consumer instances and whether queue do have number of messages more than N';
    $this->detailedDescription = <<<EOF
     The [cronRabbitmqRecovery|INFO] Checks if RabbitMQ is working, checks memory consumption, number of active consumer instances and send alert if queue do have number of messages more than N:
     [php symfony cron:cronRabbitmqRecovery] 
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
  private function callRabbitmqServerApi($serverid)
  {
    $messageCount=0;
    //checks whether server is alive or not.
    $aliveApi_url="/api/aliveness-test/%2F";
    $status_result=$this->checkRabbitmqServerStatus($serverid,$aliveApi_url);
    if(!$status_result || strtolower($status_result->status)!=="ok")
    {
      $str="\nRabbitmq Error Alert: Rabbitmq Server with host: ".JsConstants::$rabbitmqConfig[$serverid]['HOST']." is down.";
      RabbitmqHelper::sendAlert($str,"default");
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
          if(MessageQueues::$upperMessageLimitPerQueue[$queue_data->name])
            $messageLimit = MessageQueues::$upperMessageLimitPerQueue[$queue_data->name];
          else
            $messageLimit = MessageQueues::$upperMessageLimitPerQueue["default"];
     
          //keep some specific queues out of msg upper limit
          if($queue_data->name==="aliveness-test" || in_array($queue_data->name, MessageQueues::$queuesWithoutMsgCountLimit))
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
            RabbitmqHelper::sendAlert($str,"default");
          } 
          exec("ps aux | grep \"MessageQueues::CRONCONSUMER_STARTCOMMAND\" | grep -v grep | awk '{ print $2 }'", $out);
          if($serverid=="FIRST_SERVER" && $queue_data->messages_unacknowledged>$messageLimit)
          {
            $str="\nRabbitmq Error Alert: Number of unacknowledged messages pending in {$queue_data->name} is  {$queue_data->messages_unacknowledged} on first server. Restarting the consumers";
            exec("ps aux | grep \"".MessageQueues::CRONCONSUMER_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $out);
            exec("ps aux | grep \"".MessageQueues::CRONNOTIFICATION_CONSUMER_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $notificationConsumerOut);
            exec("ps aux | grep \"".MessageQueues::CRONDELETERETRIEVE_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $deleteRetrieveConsumerOut);
            exec("ps aux | grep \"".MessageQueues::UPDATESEEN_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $updateSeenConsumerOut);
            exec("ps aux | grep \"".MessageQueues::PROFILE_CACHE_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $profileCacheConsumerOut);
            exec("ps aux | grep \"".MessageQueues::UPDATE_VIEW_LOG_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $viewLogConsumerCount);
            exec("ps aux | grep \"".MessageQueues::CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND."\" | grep -v grep | awk '{ print $2 }'", $notificationLogConsumerCount);
            if(!empty($out) && is_array($out))
              foreach ($out as $key => $value) 
              {
                $count1 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count1 >0)
                  exec("kill -9 ".$value);
              }
              if(!empty($deleteRetrieveConsumerOut) && is_array($deleteRetrieveConsumerOut))
              foreach ($deleteRetrieveConsumerOut as $key => $value) 
              {
                $count1 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count1 >0)
                  exec("kill -9 ".$value);
              }
            if(!empty($notificationConsumerOut) && is_array($notificationConsumerOut))
              foreach ($notificationConsumerOut as $key => $value) 
              {
                $count2 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count2 >0)
                  exec("kill -9 ".$value);
              }
            if(!empty($updateSeenConsumerOut) && is_array($updateSeenConsumerOut))
              foreach ($updateSeenConsumerOut as $key => $value)
              {
                $count2 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count2 >0)
                  exec("kill -9 ".$value);
              }

            if(!empty($profileCacheConsumerOut) && is_array($profileCacheConsumerOut)) {
              foreach ($profileCacheConsumerOut as $key => $value)
              {
                $count2 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count2 >0)
                  exec("kill -9 ".$value);
              }
            }
            
            if(!empty($viewLogConsumerCount) && is_array($viewLogConsumerCount)) {
              foreach ($viewLogConsumerCount as $key => $value)
              {
                $count2 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count2 >0)
                  exec("kill -9 ".$value);
              }
            }
            if(!empty($notificationLogConsumerCount) && is_array($notificationLogConsumerCount)) {
              foreach ($notificationLogConsumerCount as $key => $value)
              {
                $count2 = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count2 >0)
                  exec("kill -9 ".$value);
              }
            }


            for($i=1;$i<=MessageQueues::CONSUMERCOUNT ;$i++)
              passthru(JsConstants::$php5path." ".MessageQueues::CRONCONSUMER_STARTCOMMAND." > /dev/null &"); 
            for($i=1;$i<=MessageQueues::NOTIFICATIONCONSUMERCOUNT ;$i++)
              passthru(JsConstants::$php5path." ".MessageQueues::CRONNOTIFICATION_CONSUMER_STARTCOMMAND." > /dev/null &");
              for($i=1;$i<=MessageQueues::CONSUMER_COUNT_SINGLE ;$i++)
              passthru(JsConstants::$php5path." ".MessageQueues::CRONDELETERETRIEVE_STARTCOMMAND." > /dev/null &");  
              for($i=1;$i<=MessageQueues::UPDATE_SEEN_CONSUMER_COUNT ;$i++)
              passthru(JsConstants::$php5path." ".MessageQueues::UPDATESEEN_STARTCOMMAND." > /dev/null &");

            for($i=1;$i<=MessageQueues::PROFILE_CACHE_CONSUMER_COUNT ;$i++) {
              passthru(JsConstants::$php5path." ".MessageQueues::PROFILE_CACHE_STARTCOMMAND." > /dev/null &");
            }
            for($i=1;$i<=MessageQueues::UPDATE_VIEW_LOG_CONSUMER_COUNT ;$i++) {
              passthru(JsConstants::$php5path." ".MessageQueues::UPDATE_VIEW_LOG_STARTCOMMAND." > /dev/null &");
            }
            for($i=1;$i<=MessageQueues::NOTIFICATION_LOG_CONSUMER_COUNT ;$i++) {
              passthru(JsConstants::$php5path." ".MessageQueues::CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND." > /dev/null &");
            }

            RabbitmqHelper::sendAlert($str,"default");
          }
        }
      }
      //checks whether rabbitmq has raised either the memory alarm or disk alarm.
      $alarmApi_url="/api/nodes";
      $resultAlarm=$this->checkRabbitmqServerStatus($serverid,$alarmApi_url);
      JsMemcache::getInstance()->set("mqMemoryAlarm".$serverid,false);
      JsMemcache::getInstance()->set("mqDiskAlarm".$serverid,false);
      if(is_array($resultAlarm))
      {
       foreach($resultAlarm as $row)
        {          
          if(($row->mem_limit - $row->mem_used) < MessageQueues::SAFE_LIMIT)
          {
            JsMemcache::getInstance()->set("mqMemoryAlarm".$serverid,true);
            $str="\nRabbitmq Error Alert: Memory alarm to be raised soon on the first server. Shifting Server";
            RabbitmqHelper::sendAlert($str,"default");
          }
          else
          {
            JsMemcache::getInstance()->set("mqMemoryAlarm".$serverid,false);
            
          }
          if(($row->disk_free - $row->disk_free_limit) < MessageQueues::SAFE_LIMIT)
          {
            JsMemcache::getInstance()->set("mqDiskAlarm".$serverid,true);
            $str="\nRabbitmq Error Alert: Disk alarm to be raised soon on the first server. Shifting server";
            RabbitmqHelper::sendAlert($str,"default");
          }
          else
            JsMemcache::getInstance()->set("mqDiskAlarm".$serverid,false);
        }
      }
    }
    
    return ($messageCount);
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
      if($sendAlertTo=="browserNotification")
        $str="\nError Alert: ".$inactiveInstancesNum." notification consumer instances are not running due to some error, restarting instances....";
      else
        $str="\nError Alert: ".$inactiveInstancesNum." consumer instances are not running due to some error, restarting instances....";
      RabbitmqHelper::sendAlert($str,$sendAlertTo);
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
     
    if(!sfContext::hasInstance())
    sfContext::createInstance($this->configuration);
    $this->callRabbitmqServerApi("FIRST_SERVER");
    
    if(MessageQueues::FALLBACK_STATUS==true)
    {
      $messageCount=$this->callRabbitmqServerApi("SECOND_SERVER");
    }
    
    //restart inactive default consumer for queues bound to default exchange
    $this->restartInactiveConsumer(MessageQueues::CONSUMERCOUNT,MessageQueues::CRONCONSUMER_STARTCOMMAND);

    //restart inactive notification consumer for queues bound to InstantNotificationExchange exchange
    $this->restartInactiveConsumer(MessageQueues::NOTIFICATIONCONSUMERCOUNT,MessageQueues::CRONNOTIFICATION_CONSUMER_STARTCOMMAND,"browserNotification");
    $this->restartInactiveConsumer(MessageQueues::CONSUMER_COUNT_SINGLE,MessageQueues::CRONDELETERETRIEVE_STARTCOMMAND,"DeleteRetrieve");
    $this->restartInactiveConsumer(MessageQueues::UPDATE_SEEN_CONSUMER_COUNT,MessageQueues::UPDATESEEN_STARTCOMMAND,"UpdateSeen");
    $this->restartInactiveConsumer(MessageQueues::PROFILE_CACHE_CONSUMER_COUNT,MessageQueues::PROFILE_CACHE_STARTCOMMAND,"ProfileCache Queue");
    $this->restartInactiveConsumer(MessageQueues::UPDATE_VIEW_LOG_CONSUMER_COUNT,MessageQueues::UPDATE_VIEW_LOG_STARTCOMMAND);
    $this->restartInactiveConsumer(MessageQueues::NOTIFICATION_LOG_CONSUMER_COUNT,MessageQueues::CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND);
    //runs consumer to consume accumulated messages in queues on the second server if fallback status flag is set.
    if(MessageQueues::FALLBACK_STATUS==true)
    {
      if($messageCount > 0)
      {  
        $consumerObj=new Consumer('SECOND_SERVER',$messageCount);
        $consumerObj->receiveMessage(); 
        $notificationConsumerObj=new JsNotificationsConsume('SECOND_SERVER',$messageCount);
        $notificationConsumerObj->receiveMessage();
        $delRetrieveConsumerObj=new deleteRetrieveConsumer('SECOND_SERVER',$messageCount);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $delRetrieveConsumerObj->receiveMessage();   
        $updateSeenConsumerObj=new updateSeenConsumer('SECOND_SERVER',$messageCount);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $updateSeenConsumerObj->receiveMessage();
        $profileCacheConsumerObj = new ProfileCacheConsumer('SECOND_SERVER', $messageCount);
        $profileCacheConsumerObj->receiveMessage();
        $updateViewLogConsumerObj = new updateViewLogConsumer('SECOND_SERVER', $messageCount);
        $updateViewLogConsumerObj->receiveMessage();
        unset($profileCacheConsumerObj);
        $notificationLogConsumerObj = new JsNotificationsLogConsume('SECOND_SERVER', $messageCount);
        $notificationLogConsumerObj->receiveMessage();
      }
    }    
  }
}
?>
