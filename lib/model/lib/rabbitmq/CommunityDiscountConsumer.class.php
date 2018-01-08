<?php
include_once(JsConstants::$cronDocRoot.'/amq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MessageQueues as MQ;     //MessageQueues-having values defined for constants used in this class.

/*
This class defines rabbitmq consumer for receiving messages from queues and process messages based on type of msg.
*/
class CommunityDiscountConsumer
{
  private $connection;
  private $channel;
  private $messsagePending;
  private $serverid;

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
      $str="\nRabbitMQ Error in CommunityDiscountConsumer, Connection to rabbitmq broker with host-> ".JsConstants::$rabbitmqConfig[$serverid]['HOST']. " failed: ".$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"default");
    }
    try
    {
      $this->channel = $this->connection->channel();
      $this->channel->setBodySizeLimit(MQ::MSGBODYLIMIT);
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in DiscountTrackingConsumer, Channel not formed : " . $exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"default");
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
        $this->channel->queue_declare(MQ::COMMUNITY_DISCOUNT_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
    } 
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in consumer, Unable to declare queues : " . $exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"default");
      return;
    }  
    try
    {
      $this->channel->basic_consume(MQ::COMMUNITY_DISCOUNT_QUEUE, MQ::CONSUMER, MQ::NO_LOCAL, MQ::NO_ACK,MQ::CONSUMER_EXCLUSIVE , MQ::NO_WAIT, array($this, 'processMessage'));
    }
    catch (Exception $exception) 
    {
      $str="\nRabbitMQ Error in COMMUNITY_DISCOUNT_QUEUE, Unable to consume message from queues : " .$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"default");
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
    $codeException = 0;
    $deliveryException = 0;
    try
    {
      $handlerObj=new ProcessHandler();
      switch($process)
      {
        case 'COMMUNITY_DISCOUNT':  
          $handlerObj->addCommunityDiscount($body,$type);  
          break;
      }
      unset($handlerObj);
    }
    catch (Exception $exception) 
    {
      $codeException = 1;
      $str="\nRabbitMQ Error in COMMUNITY_DISCOUNT_QUEUE, Unable to process message: " .$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str,"default");
      //$msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], MQ::MULTIPLE_TAG,MQ::REQUEUE);
      /*
       * The message due to which error is caused is reframed into a new message and the original message is dropped.
       * This new message is pushed at the back of the queue if the number of redelivery attempts is less than a specified a limit.
       */
      if($redeliveryCount<MessageQueues::REDELIVERY_LIMIT)
      {
        //RabbitmqHelper::sendAlert("\nRedelivery Count".$redeliveryCount."\n");
        $reSendData = array('process' =>$process,'data'=>array('type' => $type,'body'=>$body), 'redeliveryCount'=> $redeliveryCount+1 );
        $producerObj=new Producer();
        $producerObj->sendMessage($reSendData);
      }
      else
      {
        RabbitmqHelper::sendAlert("\nDropping message as redelivery attempts exceeded the limit"."\n");
      }
    }
    try 
    {
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    } 
    catch(Exception $exception) 
    {
      $deliveryException = 1;
      $str="\nRabbitMQ Error in DiscountTrackingConsumer, Unable to send +ve acknowledgement: " .$exception->getMessage()."\tLine:".__LINE__;
      RabbitmqHelper::sendAlert($str);
    }
    if($codeException || $deliveryException){
        die("Killed due to code exception or delivery exception");
    }
  }
}
?>
