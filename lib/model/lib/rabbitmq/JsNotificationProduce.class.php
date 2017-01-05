<?php
include_once(JsConstants::$cronDocRoot.'/amq/vendor/autoload.php');  
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MessageQueues as MQ;               //MessageQueues-having values defined for constants used in this class.

/*
This class defines rabbitmq notification producer for publishing messages on queues.
*/
class JsNotificationProduce
{
  private $connection;
  private $channel;
  private $isRabbitMQServerConnected;   //Flag to check any of the rabbitMQ servers alive or not. 1 = connected, 0 = not connected
  
  /**
   * 
   * Constructor for instantiating object of Producer class
   * 
   * <p>
   * Tries to connect to FIRST_SERVER. If failed,then tries to connect to SECOND_SERVER. If failed,then 
   * finally the normal flow without rabbitmq is chosen. 
   * </p>
   * 
   * @access public
   * @param $useFallbackServer(true-default/false)
   */

  public function __construct($useFallbackServer=true)
  { 
    if(JsMemcache::getInstance()->get("mqMemoryAlarmFIRST_SERVER")==true || JsMemcache::getInstance()->get("mqDiskAlarmFIRST_SERVER")==true || $this->serverConnection('FIRST_SERVER')==false)
    {
      if(MQ::FALLBACK_STATUS==true && $useFallbackServer==true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0)
      {
        if(JsMemcache::getInstance()->get("mqMemoryAlarmSECOND_SERVER")==true || JsMemcache::getInstance()->get("mqDiskAlarmSECOND_SERVER")==true || $this->serverConnection('SECOND_SERVER')==false)
        {
          $str="\nRabbitMQ Error in JsNotificationProduce, Connection to both rabbitmq brokers failed with host-> " .JsConstants::$rabbitmqConfig['FIRST_SERVER']['HOST']." and ".JsConstants::$rabbitmqConfig['SECOND_SERVER']['HOST']."\tLine:".__LINE__;
          RabbitmqHelper::sendAlert($str,"browserNotification");
          $this->setRabbitMQServerConnected(0);
          return;
        }
      }
      else
      {
        $str="\nRabbitMQ Error in JsNotificationProduce, Connection to first rabbitmq broker with host-> ".JsConstants::$rabbitmqConfig['FIRST_SERVER']['HOST']." failed : \tLine:".__LINE__;
        RabbitmqHelper::sendAlert($str,"browserNotification"); 
        $this->setRabbitMQServerConnected(0);
        return;
      }
               
    } 
    try
    {
      $this->channel = $this->connection->channel();
      $this->channel->setBodySizeLimit(MQ::MSGBODYLIMIT);
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationProduce, Channel not formed : " . $exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
      return;
    }
    try
    {
      $this->channel = RabbitmqHelper::RMQDeclaration($this->channel,"notificationLog");
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationProduce, common queue and exchange declaration failed : " . $exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
      return;
    }
  }
  
   /**
   * 
   * Sets connection to server with credentials passed as $serverid(param)
   * 
   * <p>
   * returns true if connected otherwise false
   * </p>
   * 
   * @access private
   * @param $serverid
   */
  private function serverConnection($serverId)
  {
    try 
    {
      $this->connection = new AMQPConnection(JsConstants::$rabbitmqConfig[$serverId]['HOST'], JsConstants::$rabbitmqConfig[$serverId]['PORT'], JsConstants::$rabbitmqConfig[$serverId]['USER'], JsConstants::$rabbitmqConfig[$serverId]['PASS'], JsConstants::$rabbitmqConfig[$serverId]['VHOST'] );
      $this->setRabbitMQServerConnected(1);
      return true;
    } 
    catch (Exception $e) 
    {
      return false;
    }
  }

  /**
   * 
   * Sets flag $isRabbitMQServerConnected
   * 
   * <p>
   * $connectionStatus defines whether any of the server connected or not
   * 1 = connected
   * 0 = not connected
   * </p>
   * 
   * @access private
   * @param $connectionStatus
   */
  private function setRabbitMQServerConnected($connectionStatus)
  {
      $this->isRabbitMQServerConnected = $connectionStatus;
  }

  /**
   * 
   * get value of flag $isRabbitMQServerConnected
   * 
   * <p>
   * returns $isRabbitMQServerConnected
   * </p>
   * 
   * @access public
   * @param none
   */
  public function getRabbitMQServerConnected()
  {
      return $this->isRabbitMQServerConnected;
  }

  /**
   * 
   * Encodes $msgdata(param) as json message and sends it to queue.
   * 
   * <p>
   * declares queues and then publishes message based on process(mail/sms/gcm).
   * </p>
   * 
   * @access public
   * @param $msgdata
   */
  
  public function sendMessage($msgdata,$addLog=false)
  {
    $data=json_encode($msgdata);
    $msg = new AMQPMessage($data,array('delivery_mode' =>MQ::DELIVERYMODE));
    $process=$msgdata['process'];
    try
    {
      if($addLog==true)
        RabbitmqHelper::addRabbitmqMsgLog(BrowserNotificationEnums::$publishedNotificationLog,$msgdata['data']['type']."-".$msgdata['data']['body']['NOTIFICATION_KEY']);
      switch($process)
      {
        case "JS_NOTIFICATION1" :
                    $this->channel->basic_publish($msg,MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$scheduledNotificationBindingKeyArr[MQ::$SCHEDULED_NOTIFICATION_QUEUE1]);
                    break;
        case "JS_NOTIFICATION2" :
                    $this->channel->basic_publish($msg,MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$scheduledNotificationBindingKeyArr[MQ::$SCHEDULED_NOTIFICATION_QUEUE2]);
                    break;
        case "JS_NOTIFICATION3" :
                    $this->channel->basic_publish($msg,MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$scheduledNotificationBindingKeyArr[MQ::$SCHEDULED_NOTIFICATION_QUEUE3]);
                    break;
	case "JS_NOTIFICATION4" :
                    $this->channel->basic_publish($msg,MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$scheduledNotificationBindingKeyArr[MQ::$SCHEDULED_NOTIFICATION_QUEUE4]);
                    break;
        case "JS_NOTIFICATION5" :
                    $this->channel->basic_publish($msg,MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$scheduledNotificationBindingKeyArr[MQ::$SCHEDULED_NOTIFICATION_QUEUE5]);
                    break;
        case "JS_NOTIFICATION6" :
                    $this->channel->basic_publish($msg,MQ::$DELAYED_NOTIFICATION_EXCHANGE["NAME"],MQ::$scheduledNotificationBindingKeyArr[MQ::$SCHEDULED_NOTIFICATION_QUEUE6]);
                    break;
        case "JS_INSTANT_NOTIFICATION":
                    $this->channel->basic_publish($msg,MQ::$INSTANT_NOTIFICATION_EXCHANGE["NAME"]);
                    break;
        case "JS_NOTIFICATION_LOG":
                    $this->channel->basic_publish($msg,MQ::$NOTIFICATION_LOG_EXCHANGE["NAME"]);
                    break;
      }
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in JsNotificationProduce, Unable to publish message : " .$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"browserNotification");
      return;
    }      
  }

  /**
   * 
   * Destructor for destructing object of Producer class
   * 
   * <p>
   * It closes the channel and connection.
   * </p>
   * 
   * @access public
   * @param none
   */
  public function __destruct()
  {
      if($this->getRabbitMQServerConnected())
      {
          $this->channel->close();
          $this->connection->close();
      }
  }
}
?>
