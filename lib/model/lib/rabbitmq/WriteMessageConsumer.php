<?php
include_once(JsConstants::$cronDocRoot.'/amq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MessageQueues as MQ;     //MessageQueues-having values defined for constants used in this class.

/*
This class defines rabbitmq consumer for receiving messages from queues and process messages based on type of msg.
*/
class WriteMessageConsumer
{
  private $connection;
  private $channel;
  private $messsagePending;
  private $serverid;

  /**
   * 
   * Constructor for instantiating object of deleteRetrieveConsumer class
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
	  $str="\nRabbitMQ Error in consumer, Connection to rabbitmq broker with host-> ".JsConstants::$rabbitmqConfig[$serverid]['HOST']. " failed: ".$exception->getMessage()."\tLine:".__LINE__;
	  RabbitmqHelper::sendAlert($str,"default");
	}
	try
	{
	  $this->channel = $this->connection->channel();
	  $this->channel->setBodySizeLimit(MQ::MSGBODYLIMIT);
	}
	catch (Exception $exception) 
	{
	  $str="\nRabbitMQ Error in consumer, Channel not formed : " . $exception->getMessage()."\tLine:".__LINE__;
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
		$this->channel->queue_declare(MQ::WRITE_MSG_queueRightNow);
		$this->channel->exchange_declare(MQ::WRITE_MSG_exchangeRightNow, 'direct');
		$this->channel->exchange_declare(MQ::WRITE_MSG_exchangeDelayed5min, 'direct');
		$this->channel->queue_bind(MQ::WRITE_MSG_queueRightNow, MQ::WRITE_MSG_exchangeRightNow);
		$this->channel->queue_declare(MQ::WRITE_MSG_queueDelayed5min, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, array("x-dead-letter-exchange" => array("S", MQ::WRITE_MSG_exchangeRightNow),"x-message-ttl" => array("I", MQ::DELAY_WRITEMSG*1000)));
		$this->channel->queue_bind(MQ::WRITE_MSG_queueDelayed5min, MQ::WRITE_MSG_exchangeDelayed5min);
	} 
	catch (Exception $exception) 
	{
	  $str="\nRabbitMQ Error in consumer, Unable to declare queues : " . $exception->getMessage()."\tLine:".__LINE__;
	  RabbitmqHelper::sendAlert($str,"default");
	  return;
	}  
	try
	{
		$this->channel->basic_consume(MQ::WRITE_MSG_queueRightNow, MQ::CONSUMER, MQ::NO_LOCAL, MQ::NO_ACK,MQ::CONSUMER_EXCLUSIVE , MQ::NO_WAIT, array($this, 'processMessage'));
	}
	catch (Exception $exception) 
	{
	  $str="\nRabbitMQ Error in consumer, Unable to consume message from queues : " .$exception->getMessage()."\tLine:".__LINE__;
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
	try
	{
	  $handlerObj=new ProcessHandler();
	  switch($process)
	  {
		case MQ::WRITE_MSG_Q :
			$key = $body['key'];
			$data = JsMemcache::getInstance()->getHashAllValue($key);
			
            $orgTZ = date_default_timezone_get();
            date_default_timezone_set("Asia/Calcutta");

			// print_r($data);die;
			$timeDiff = floor( (time() - $data['time'])/60 );
			$senderid=$body['senderid'];
			$receiverid=$body['receiverid'];
			
			if($timeDiff >= MQ::DELAY_MINUTE)
			{
				// delete key data
				JsMemcache::getInstance()->delete($key);
				// Sender Receiver objects
				$senderObj = new Profile('',$senderid);   
				$senderObj->getDetail("","","*");
				$receiverObj = new Profile('',$receiverid);
				$receiverObj->getDetail("","","*");
				// send mail
				$conversation = $data['message'];
				if($data['sendToBoth'])
				{
					// send this mail both to sender and receiver
					$search = "<TAG>".$senderObj->getUSERNAME()."</TAG>,";
					$senderEmailMsg = str_replace($search, 'You,', $conversation);
					$search = "<TAG>".$receiverObj->getUSERNAME()."</TAG>,";
					$senderEmailMsg = str_replace($search, $receiverObj->getUSERNAME().',', $senderEmailMsg);
					$this->sendMail($receiverObj, $senderObj, $senderEmailMsg, $type);
					
					$search = "<TAG>".$receiverObj->getUSERNAME()."</TAG>,";
					$receiverEmailMsg = str_replace($search, 'You,', $conversation);
					$search = "<TAG>".$senderObj->getUSERNAME()."</TAG>,";
					$receiverEmailMsg = str_replace($search, $senderObj->getUSERNAME().',', $receiverEmailMsg);
					$this->sendMail($senderObj, $receiverObj, $receiverEmailMsg, $type);
				}
				else
				{
					// send only to receiver
					$search = "<TAG>".$receiverObj->getUSERNAME()."</TAG>,";
					$receiverEmailMsg = str_replace($search, 'You,', $conversation);
					$search = "<TAG>".$senderObj->getUSERNAME()."</TAG>,";
					$receiverEmailMsg = str_replace($search, $senderObj->getUSERNAME().',', $receiverEmailMsg);
					$this->sendMail($senderObj, $receiverObj, $receiverEmailMsg, $type);
				}

			}
            date_default_timezone_set($orgTZ);
			break;
	  }
	}
	catch (Exception $exception) 
	{
	  $str="\nRabbitMQ Error in consumer, Unable to process message: " .$exception->getMessage()."\tLine:".__LINE__;
	  RabbitmqHelper::sendAlert($str,"default");
	  //$msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], MQ::MULTIPLE_TAG,MQ::REQUEUE);
	  /*
	   * The message due to which error is caused is reframed into a new message and the original message is dropped.
	   * This new message is pushed at the back of the queue if the number of redelivery attempts is less than a specified a limit.
	   */
	  if($redeliveryCount < MessageQueues::REDELIVERY_LIMIT)
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
	  $str="\nRabbitMQ Error in consumer, Unable to send +ve acknowledgement: " .$exception->getMessage()."\tLine:".__LINE__;
	  RabbitmqHelper::sendAlert($str);
	}
  }

  /**
   * 
   * Function for sending e-mail
   * 
   * @access public
   * @param $senderObj,$receiverObj,$message,$type
   */
	public function sendMail($senderObj, $receiverObj, $message, $type)
	{
	    if($type == 'MESSAGE')
	    {
	    	ContactMailer::sendMessageMailer($receiverObj, $senderObj,$message);
	    }
	}
}
?>