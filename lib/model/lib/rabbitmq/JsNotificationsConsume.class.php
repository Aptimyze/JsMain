<?php
include_once(JsConstants::$cronDocRoot.'/amq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MessageQueues as MQ;     //MessageQueues-having values defined for constants used in this class.

/*
This class defines rabbitmq notification consumer for receiving messages from queues and process messages based on type of notification.
*/
class JsNotificationsConsume
{
  private $connection;
  private $channel;
  private $messsagePending;
  private $serverid;
  public static $sendAlert=0;

  /**
   * 
   * Constructor for instantiating object of Consumer class
   * 
   * <p>
   * Consumer connects to server with $serverid and waits for incoming messages to consume.
   * </p>
   * 
   * @access public
   * @param $serverid,$messageCount
  */
  public function __construct($serverid,$messsageCount)
  {
    if($serverid=='SECOND_SERVER')
    {
      $this->messsagePending= $messsageCount;
      $this->serverid= 'SECOND_SERVER';
    }
    else
    {
      $this->serverid= 'FIRST_SERVER';
    }
    try 
    {
      $this->connection = new AMQPConnection(JsConstants::$rabbitmqConfig[$serverid]['HOST'], JsConstants::$rabbitmqConfig[$serverid]['PORT'], JsConstants::$rabbitmqConfig[$serverid]['USER'], JsConstants::$rabbitmqConfig[$serverid]['PASS'], JsConstants::$rabbitmqConfig[$serverid]['VHOST'] );
    } 
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, Connection to rabbitmq broker with host-> ".JsConstants::$rabbitmqConfig[$serverid]['HOST']. " failed: ".$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
    }
    try
    {
      $this->channel = $this->connection->channel();
      $this->channel->setBodySizeLimit(MQ::MSGBODYLIMIT);
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, Channel not formed : " . $exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
      return;
    }
    try
    {
      $this->channel = RabbitmqHelper::RMQDeclaration($this->channel,"notification");
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, common queue and exchange declaration failed : " . $exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
      return;
    }
  }
  
  /**
   * 
   * Consumer keeps listening to queues and retrieves messages to process them as they come. 
   * 
   * @access public
   * @param none
   */ 
  public function receiveMessage()
  {    
    try
    {
      $this->channel->basic_consume(MQ::$INSTANT_NOTIFICATION_QUEUE, MQ::CONSUMER, MQ::NO_LOCAL, MQ::NO_ACK,MQ::CONSUMER_EXCLUSIVE , MQ::NO_WAIT, array($this, 'processMessage'));
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, Unable to consume message from queues : " .$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
      return;
    }  
    if($this->serverid=='FIRST_SERVER')
    {
      while(count($this->channel->callbacks)) 
      {
        $this->channel->wait();
      }
    }
    else
    {
      while($this->messsagePending != 0) 
      {
        $this->messsagePending=$this->messsagePending - 1;
        $this->channel->wait();
      }
    }
  }

  /**
   * 
   * Decodes message($msg) and passes it to sendMail funtion of rabbitmq module via curl GET request.
   * 
   * <p>
   * Sends back acknowledgement after processing message.
   * </p>
   * 
   * @access public
   * @param $msg
   */
  public function processMessage(AMQPMessage $msg)
  {

    $msgdata=json_decode($msg->body,true);
    $process=$msgdata['process'];
    $redeliveryCount=$msgdata['redeliveryCount'];
    $type=$msgdata['data']['type'];
    $body=$msgdata['data']['body'];
    try
    {
      $handlerObj=new ProcessHandler();
      if(BrowserNotificationEnums::$addNotificationLog==true)
        RabbitmqHelper::addRabbitmqMsgLog(BrowserNotificationEnums::$transferredNotificationlog,$type."-".$body["NOTIFICATION_KEY"]);
      if(in_array($type, BrowserNotificationEnums::$notificationChannelType))
      { 
        $handlerObj->sendGcmNotification($type,$body);  
      }
      else if($type == 'APP_NOTIFICATION')
      {
	$notificationSenderObj = new NotificationSender;	
	$profileid =$body['PROFILEID'];
	$dataSet[$profileid] =$body;

	//filter profiles based on notification count
        /*if(in_array($body["NOTIFICATION_KEY"],NotificationEnums::$scheduledNotificationPriorityArr))
        	$filteredProfileDetails = $notificationSenderObj->filterProfilesBasedOnNotificationCount($dataSet,$body["NOTIFICATION_KEY"]);
        else
        	$filteredProfileDetails = $dataSet;*/
	//Send Notification
	/*$notificationSenderObj->sendNotifications($filteredProfileDetails);
	$scheduledAppNotificationUpdateSentObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS;
        $scheduledAppNotificationUpdateSentObj->updateSuccessSent(NotificationEnums::$PENDING,$body["MSG_ID"]);*/
      }
      else if($type == "MA_NOTIFICATION"){
          $handlerObj->processMatchAlertNotification($type,$body);
      }
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, Unable to process message: " .$exception->getMessage()."\tLine:".__LINE__;
      //RabbitmqHelper::sendAlert($str,"browserNotification");
     
      /*
       * The message due to which error is caused is reframed into a new message and the original message is dropped.
       * This new message is pushed at the back of the queue if the number of redelivery attempts is less than a specified a limit.
       */
      /*if($redeliveryCount<MessageQueues::REDELIVERY_LIMIT)
      {
        //RabbitmqHelper::sendAlert("\nRedelivery Count".$redeliveryCount."\n");
        $reSendData = array('process' =>'JS_INSTANT_NOTIFICATION','data'=>array('type' => $type,'body'=>$body), 'redeliveryCount'=> $redeliveryCount+1 );
        $producerObj=new JsNotificationProduce();
        $producerObj->sendMessage($reSendData);
      }
      else
      {
        RabbitmqHelper::sendAlert("\nDropping message as redelivery attempts exceeded the limit"."\n");
      }*/
    }
    try 
    {
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    } 
    catch(Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, Unable to send +ve acknowledgement: " .$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str);
    }
  }
}
?>
