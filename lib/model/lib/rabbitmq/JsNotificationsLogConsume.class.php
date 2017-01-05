<?php
include_once(JsConstants::$cronDocRoot.'/amq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MessageQueues as MQ;     //MessageQueues-having values defined for constants used in this class.

/*
This class defines rabbitmq notification consumer for receiving messages from queues and process messages based on type of notification.
*/
class JsNotificationsLogConsume
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
      $this->channel = RabbitmqHelper::RMQDeclaration($this->channel,"notificationLog");
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
      $this->channel->basic_consume(MQ::$NOTIFICATION_LOG_QUEUE, MQ::CONSUMER, MQ::NO_LOCAL, MQ::NO_ACK,MQ::CONSUMER_EXCLUSIVE , MQ::NO_WAIT, array($this, 'processMessage'));
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

      if($type == 'REGISTRATION_ID')
      {
	$registationIdObj = new MOBILE_API_REGISTRATION_ID();
	$registationIdObj->updateVersion($body['regid'],$body['appVersion'],$body['osVersion'],$body['brand'],$body['model']);
      }     
      elseif($type == 'LOCAL_NOTIFICATION_LOG')
      {
	$localLogObj = new MOBILE_API_LOCAL_NOTIFICATION_LOG();
	$localLogObj->insert($body['profileid'],$body['notificationKey'],$body['messageId'],$body['status'],$body['alarmTime'],$body['osType']);
      }
      elseif($type == 'DELIVERY_TRACKING_API')
      {
        $profileid 	=$body['profileid'];
	$notificationKey=$body['notificationKey'];
	$messageId	=$body['messageId'];
	$status		=$body['status'];
	$osType		=$body['osType'];			
	NotificationFunctions::deliveryTrackingHandling($profileid,$notificationKey,$messageId,$status,$osType);
      }
      elseif($type == 'UPDATE_NOTIFICATION_STATUS_API')
      {
        $profileid      	=$body['profileid'];
        $notificationStatus     =$body['status'];
        $mobileApiRegistrationObj = new MOBILE_API_REGISTRATION_ID;
        $mobileApiRegistrationObj->updateNotificationStatus($profileid,$notificationStatus);
      }
      elseif($type == 'REGISTRATION_API')
      {
        $profileid      =$body['profileid'];
        $registrationid =$body['registrationid'];
        $appVersion     =$body['appVersion'];
        $osVersion      =$body['osVersion'];
        $deviceBrand    =$body['deviceBrand'];
	$deviceModel	=$body['deviceModel'];
	NotificationFunctions::registrationIdInsert($profileid,$registrationid,$appVersion,$osVersion,$deviceBrand,$deviceModel);
      }
      else if($type == "NOTIFICATION_OPENED_TRACKING_API" && is_array($body)){
          NotificationFunctions::logNotificationOpened($body);
      }

    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationConsume, Unable to process message: " .$exception->getMessage()."\tLine:".__LINE__;
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
