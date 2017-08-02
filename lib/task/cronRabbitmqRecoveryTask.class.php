<?php
/*
This php script checks if RabbitMQ is working, checks memory consumption (memory alarm and/or disk alarm are raised if memory/disk consumption is more than the specified limit),
number of active consumer instances and sends alert if queues have number of messages more than N(a pre specified limit). Consumer instance is run if there are queued messages 
present in queues on the second server.
*/

class cronRabbitmqRecovery extends sfBaseTask
{

private $consumerRestarted = 0;
private $consumerToCountMapping = array();

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
$this->addArguments(array(
new sfCommandArgument('server', sfCommandArgument::OPTIONAL, 'My argument')
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
$msgOverflow = 0;
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
$overflowQueueData = "";
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
    $msgOverflow = 1;
    $overflowQueueData .= $queue_data->name.":".$queue_data->messages_ready."(ready), ";
    RabbitmqHelper::sendAlert($str,"default");
  } 
  //exec("ps aux | grep \"MessageQueues::CRONCONSUMER_STARTCOMMAND\" | grep -v grep | awk '{ print $2 }'", $out);
  if($serverid=="FIRST_SERVER" && $queue_data->messages_unacknowledged>$messageLimit)
  {
    $str="\nRabbitmq Error Alert: Number of unacknowledged messages pending in {$queue_data->name} is  {$queue_data->messages_unacknowledged} on first server. Restarting the consumers";
    $msgOverflow = 1;
    $overflowQueueData .= $queue_data->name.":".$queue_data->messages_unacknowledged."(unacked), ";
    RabbitmqHelper::sendAlert($str,"default");
  }
}
if($msgOverflow == 1 && $serverid == "FIRST_SERVER"){
  //echo "killAndRestartConsumer"."\n";

  RabbitmqHelper::sendRMQAlertSMS($overflowQueueData);
  //die("123");
  //kill and start consumers again
  $this->killAndRestartConsumer();
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
  if($row->mem_used >= MessageQueues::SAFE_LIMIT)
  {
    JsMemcache::getInstance()->set("mqMemoryAlarm".$serverid,true);
    $str="\nRabbitmq Error Alert: Memory alarm to be raised soon on the first server. Shifting Server";
    RabbitmqHelper::sendAlert($str,"default");
    
    CommonUtility::sendSlackmessage("Rabbitmq Error Alert: Memory alarm to be raised soon,memory used- ".round($row->mem_used/(1024*1024*1024),2). " GB at ".$row->cluster_links[0]->name,"rabbitmq");
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

/*kill active instances of consumer and restart them
* $@param : none
*/
private function killAndRestartConsumer(){

//print_r($consumerToCountMapping);
foreach ($this->consumerToCountMapping as $command => $count) 
{
if($this->checkRestart($command)){
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
    for($i=1;$i<=$count ;$i++)
    { 
      //var_dump(JsConstants::$php5path." ".$command." > /dev/null &");
      passthru(JsConstants::$php5path." ".$command." > /dev/null &"); 
    }
}
}
//echo "flag set";
$this->consumerRestarted = 1;
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

private function checkRestart($command){
if($command == MessageQueues::CRON_DISCOUNT_TRACKING_CONSUMER_STARTCOMMAND){
$inactiveHours = array("10","11","12","13","14");
$currentHr = date("H");
if(in_array($currentHr, $inactiveHours)){
  return false;
}
else{
  return true;
}
}
else{
return true;
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
    
    if($arguments["server"] == "72"){
        $this->consumerToCountMapping = array(
                                  MessageQueues::CRON_BUFFER_INSTANT_NOTIFICATION_START_COMMAND => MessageQueues::BUFFER_INSTANT_NOTIFICATION_CONSUMER_COUNT,
                                  MessageQueues::CRON_DISCOUNT_TRACKING_CONSUMER_STARTCOMMAND=>MessageQueues::DISCOUNT_TRACKING_CONSUMER_COUNT,
                                  MessageQueues::CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND=>MessageQueues::NOTIFICATION_LOG_CONSUMER_COUNT,
                                  MessageQueues::CRONNOTIFICATION_CONSUMER_STARTCOMMAND=>MessageQueues::NOTIFICATIONCONSUMERCOUNT,
                                  MessageQueues::CRONCONSUMER_STARTCOMMAND => MessageQueues::CONSUMERCOUNT,
                                  MessageQueues::CRON_INSTANT_EOI_QUEUE_CONSUMER_STARTCOMMAND=>MessageQueues::INSTANTEOICONSUMERCOUNT,
                                  MessageQueues::CRONMATCHALERTSLASTSEEN_STARTCOMMAND=>MessageQueues::MATCHALERT_LAST_SEEN_CONSUMER_COUNT,
                                  MessageQueues::CRONJUSTJOINEDLASTSEEN_STARTCOMMAND=>MessageQueues::JUST_JOINED_LAST_SEEN_CONSUMER_COUNT,
                                  MessageQueues::OUTBOUND_STARTCOMMAND=>MessageQueues::OUTBOUND_CONSUMER_COUNT,
                                  MessageQueues::CRONEXCLUSIVEDELAYEDMAILER_STARTCOMMAND=>MessageQueues::CRONEXCLUSIVEDELAYEDMAILER_CONSUMER_COUNT,
                          	  MessageQueues::CRON_EXECUTE_COMMUNITY_DISCOUNT_STARTCOMMAND=>  MessageQueues::COMMUNITY_DISCOUNT_CONSUMER_COUNT
                                    );
    }
    elseif($arguments["server"] == "63"){
        $this->consumerToCountMapping = array(
                                  MessageQueues::UPDATESEEN_STARTCOMMAND=>MessageQueues::UPDATE_SEEN_CONSUMER_COUNT,
                                  MessageQueues::UPDATESEENPROFILE_STARTCOMMAND=>MessageQueues::UPDATE_SEEN_PROFILE_CONSUMER_COUNT
				);
    }
    elseif($arguments["server"] == "82"){
        $this->consumerToCountMapping = array(
                                  MessageQueues::UPDATEMATCHALERTSREG_STARTCOMMAND=>MessageQueues::UPDATE_MATCHALERT_REG_COUNT
				);
    }
    else{
        $this->consumerToCountMapping = array(
                                  MessageQueues::CRONDELETERETRIEVE_STARTCOMMAND=>MessageQueues::CONSUMER_COUNT_SINGLE,
                                  MessageQueues::UPDATESEEN_STARTCOMMAND=>MessageQueues::UPDATE_SEEN_CONSUMER_COUNT,
                                  MessageQueues::UPDATESEENPROFILE_STARTCOMMAND=>MessageQueues::UPDATE_SEEN_PROFILE_CONSUMER_COUNT,
                                  MessageQueues::UPDATECRITICALINFO_STARTCOMMAND=>MessageQueues::UPDATE_CRITICAL_INFO_CONSUMER_COUNT            ,
                                  MessageQueues::PROFILE_CACHE_STARTCOMMAND=>MessageQueues::PROFILE_CACHE_CONSUMER_COUNT,
                                  MessageQueues::UPDATE_VIEW_LOG_STARTCOMMAND=>MessageQueues::UPDATE_VIEW_LOG_CONSUMER_COUNT,
                                  MessageQueues::CRONSCREENINGQUEUE_CONSUMER_STARTCOMMAND=>MessageQueues::SCREENINGCONSUMERCOUNT,
                                  MessageQueues::UPDATE_FEATURED_PROFILE_STARTCOMMAND=>MessageQueues::FEATURED_PROFILE_CONSUMER_COUNT,
                                  MessageQueues::CRONWRITEMESSAGEQUEUE_CONSUMER_STARTCOMMAND=>MessageQueues::WRITEMESSAGECONSUMERCOUNT,
                                  MessageQueues::CRON_LOGGING_QUEUE_CONSUMER_STARTCOMMAND=>MessageQueues::LOGGING_QUEUE_CONSUMER_COUNT,
                                  MessageQueues::CRON_PRODUCT_METRIC_QUEUE_CONSUMER_STARTCOMMAND=>MessageQueues::PRODUCT_METRIC_QUEUE_CONSUMER_COUNT,
                                    );
    }
    $this->callRabbitmqServerApi("FIRST_SERVER");
    
    if(MessageQueues::FALLBACK_STATUS==true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0)
    {
      //echo "111";die;
      $messageCount=$this->callRabbitmqServerApi("SECOND_SERVER");
    }
    if($this->consumerRestarted == 0){
        //echo "restartInactiveConsumer ..";
        //restart inactive default consumer for queues bound to default exchange
        foreach ($this->consumerToCountMapping as $command => $count) {
            if($this->checkRestart($command)){
                $this->restartInactiveConsumer($count,$command);
            }
        }
    }

      //runs consumer to consume accumulated messages in queues on the second server if fallback status flag is set.
    /*if(MessageQueues::FALLBACK_STATUS==true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0)
    {
      if($messageCount > 0)
      {  
        $msgPickCount = MessageQueues::FALLBACK_SERVER_MSGPICK_COUNT;
        $consumerObj=new Consumer('SECOND_SERVER',$msgPickCount);
        $consumerObj->receiveMessage(); 
        $notificationConsumerObj=new JsNotificationsConsume('SECOND_SERVER',$msgPickCount);
        $notificationConsumerObj->receiveMessage();
        $delRetrieveConsumerObj=new deleteRetrieveConsumer('SECOND_SERVER',$msgPickCount);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $delRetrieveConsumerObj->receiveMessage();   
        $updateSeenConsumerObj=new updateSeenConsumer('SECOND_SERVER',$msgPickCount);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $updateSeenConsumerObj->receiveMessage();
        $updateFeaturedProfileConsumerObj=new updateSeenConsumer('SECOND_SERVER',$msgPickCount);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $updateFeaturedProfileConsumerObj->receiveMessage();
        $profileCacheConsumerObj = new ProfileCacheConsumer('SECOND_SERVER', $msgPickCount);
        $profileCacheConsumerObj->receiveMessage();
        $updateViewLogConsumerObj = new updateViewLogConsumer('SECOND_SERVER', $msgPickCount);
        $updateViewLogConsumerObj->receiveMessage();
        $notificationLogConsumerObj = new JsNotificationsLogConsume('SECOND_SERVER', $msgPickCount);
        $notificationLogConsumerObj->receiveMessage();
      }
    }*/  
  }
}
?>
