<?php
include_once(JsConstants::$cronDocRoot . '/amq/vendor/autoload.php');
use MessageQueues as MQ;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

//MessageQueues-having values defined for constants used in this class.

/*
This class defines rabbitmq producer for publishing messages on queues.
*/

class Producer
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
	 * @param $useFallbackServer (true-default/false)
	 */

	public function __construct($useFallbackServer = true)
	{
		if (JsMemcache::getInstance()->get("mqMemoryAlarmFIRST_SERVER") == true || JsMemcache::getInstance()->get("mqDiskAlarmFIRST_SERVER") == true || $this->serverConnection('FIRST_SERVER') == false) {
			if (MQ::FALLBACK_STATUS == true && $useFallbackServer == true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0) {
				if (JsMemcache::getInstance()->get("mqMemoryAlarmSECOND_SERVER") == true || JsMemcache::getInstance()->get("mqDiskAlarmSECOND_SERVER") == true || $this->serverConnection('SECOND_SERVER') == false) {
					$str = "\nRabbitMQ Error in producer, Connection to both rabbitmq brokers failed with host-> " . JsConstants::$rabbitmqConfig['FIRST_SERVER']['HOST'] . " and " . JsConstants::$rabbitmqConfig['SECOND_SERVER']['HOST'] . "\tLine:" . __LINE__;
					RabbitmqHelper::sendAlert($str, "default");
					$this->setRabbitMQServerConnected(0);
					return;
				}
			} else {
				$str = "\nRabbitMQ Error in producer, Connection to first rabbitmq broker with host-> " . JsConstants::$rabbitmqConfig['FIRST_SERVER']['HOST'] . " failed : \tLine:" . __LINE__;
				RabbitmqHelper::sendAlert($str, "default");
				$this->setRabbitMQServerConnected(0);
				return;
			}

		}
		try {
			$this->channel = $this->connection->channel();
			$this->channel->setBodySizeLimit(MQ::MSGBODYLIMIT);
		} catch (Exception $exception) {
			$str = "\nRabbitMQ Error in producer, Channel not formed : " . $exception->getMessage() . "\tLine:" . __LINE__;
			RabbitmqHelper::sendAlert($str, "default");
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
		try {
			$this->connection = new AMQPConnection(JsConstants::$rabbitmqConfig[$serverId]['HOST'], JsConstants::$rabbitmqConfig[$serverId]['PORT'], JsConstants::$rabbitmqConfig[$serverId]['USER'], JsConstants::$rabbitmqConfig[$serverId]['PASS'], JsConstants::$rabbitmqConfig[$serverId]['VHOST']);
			$this->setRabbitMQServerConnected(1);
			return true;
		} catch (Exception $e) {
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
	 * Encodes $msgdata(param) as json message and sends it to queue.
	 *
	 * <p>
	 * declares queues and then publishes message based on process(mail/sms/gcm).
	 * </p>
	 *
	 * @access public
	 * @param $msgdata
	 */

	public function sendMessage($msgdata)
	{
		try {
			$this->channel->queue_declare(MQ::MAILQUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::SMSQUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::CONTACTCACHEINITIATE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::INVALIDATECACHE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::AGENT_NOTIFICATIONSQUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::BUFFER_INSTANT_NOTIFICATION_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::DELETE_RETRIEVE_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::UPDATE_SEEN_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::UPDATE_SEEN_PROFILE_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::UPDATE_FEATURED_PROFILE_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

			$this->channel->queue_declare(MQ::CHAT_MESSAGE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

			$this->channel->queue_declare(MQ::DUPLICATE_LOG_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::PROFILE_CACHE_Q_DELETE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        $this->channel->queue_declare(MQ::VIEW_LOG, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::SCREENING_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::LOGGING_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
            $this->channel->queue_declare(MQ::DISC_HISTORY_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

      $this->channel->queue_declare(MQ::SCRIPT_PROFILER_Q, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);    
			$this->channel->queue_declare(MQ::WRITE_MSG_queueRightNow);
			$this->channel->exchange_declare(MQ::WRITE_MSG_exchangeRightNow, 'direct');
			$this->channel->queue_bind(MQ::WRITE_MSG_queueRightNow, MQ::WRITE_MSG_exchangeRightNow);
			$this->channel->queue_declare(MQ::WRITE_MSG_queueDelayed5min, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, 
					array(
						"x-dead-letter-exchange" => array("S", MQ::WRITE_MSG_exchangeRightNow),
						"x-message-ttl" => array("I", MQ::DELAY_WRITEMSG*1000))
					);
			$this->channel->exchange_declare(MQ::WRITE_MSG_exchangeDelayed5min, 'direct');
			$this->channel->queue_bind(MQ::WRITE_MSG_queueDelayed5min, MQ::WRITE_MSG_exchangeDelayed5min);

		} catch (Exception $exception) {
			$str = "\nRabbitMQ Error in producer, Unable to" . " declare queues : " . $exception->getMessage() . "\tLine:" . __LINE__;
			RabbitmqHelper::sendAlert($str, "default");
			return;
		}
		$data = json_encode($msgdata);
		$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
		$process = $msgdata['process'];
		try {
			switch ($process) {
				case "MAIL":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::MAILQUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "SMS":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::SMSQUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::SMSQUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "GCM":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::GCMQUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "AGENT_NOTIFICATIONS":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::AGENT_NOTIFICATIONSQUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "CACHE":
					$data = $msgdata['data'];
					$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::CONTACTCACHEINITIATE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "INVALIDATE":
					$data = $msgdata['data'];
					$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::INVALIDATECACHE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "BUFFER_INSTANT_NOTIFICATIONS" :
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::BUFFER_INSTANT_NOTIFICATION_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "DELETE_RETRIEVE":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::DELETE_RETRIEVE_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "CHATROSTERS":
					if (JsConstants::$jsChatFlag >= 1) {
						$data = $msgdata['data'];
						$msg = new AMQPMessage(json_encode($data), array('delivery_mode' => MQ::DELIVERYMODE));
						$this->channel->basic_publish($msg, MQ::CHATEXCHANGE, "roster");
					}
					break;
				case "UPDATE_SEEN":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_SEEN_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "UPDATE_SEEN_PROFILE":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_SEEN_PROFILE_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "UPDATE_FEATURED_PROFILE":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_FEATURED_PROFILE_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
					
				case "USERCREATION":
					if (JsConstants::$jsChatFlag >= 1) {
						$data = $msgdata['data'];
						$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
						$this->channel->basic_publish($msg, MQ::CHATEXCHANGE, "profile_created");
					}
					break;
				case "USERLOGIN":
					if (JsConstants::$jsChatFlag >= 1) {
						$data = $msgdata['data'];
						$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
						$this->channel->basic_publish($msg, MQ::CHATEXCHANGE, "profile_created");
					}
					break;
				case "DUPLICATE_LOG":
					    $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::DUPLICATE_LOG_QUEUE,MQ::MANDATORY,MQ::IMMEDIATE);
					    break;
				case "USER_DELETE":
					if (JsConstants::$jsChatFlag >= 1) {
						$data = $msgdata['data'];
						$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
						$this->channel->basic_publish($msg, MQ::CHATEXCHANGE, "profile_deleted");
					}
					break;				
				case "CHATMESSAGE":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::CHAT_MESSAGE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;

				case "ROSTERCREATION":
					$data = $msgdata['data'];
					$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
					$this->channel->basic_publish($msg, MQ::CHATEXCHANGE,"roster_created");
					break;
				case "DUPLICATE_LOG":
                    $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::DUPLICATE_LOG_QUEUE,MQ::MANDATORY,MQ::IMMEDIATE);
                    break;

				case MQ::PROCESS_PROFILE_CACHE_DELETE:
          $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::PROFILE_CACHE_Q_DELETE,MQ::MANDATORY,MQ::IMMEDIATE);
					break;
                                case "ViewLogQueue":
                                        $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::VIEW_LOG,MQ::MANDATORY,MQ::IMMEDIATE);
                                        break;
                case MQ::SCREENING_Q_EOI:
                	$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::SCREENING_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                	break;
				case MQ::WRITE_MSG_Q:
					$this->channel->basic_publish($msg, MQ::WRITE_MSG_exchangeDelayed5min);
					break;
                case 'LOGGING_TRACKING':
                	$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::LOGGING_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                	break;

                case 'DISCOUNT_HISTORY':
                    $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::DISC_HISTORY_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                    break;
        case MQ::SCRIPT_PROFILER_PROCESS:
            $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::SCRIPT_PROFILER_Q,MQ::MANDATORY,MQ::IMMEDIATE);
          break;

			}
		} catch (Exception $exception) {
			$str = "\nRabbitMQ Error in producer, Unable to publish message : " . $exception->getMessage() . "\tLine:" . __LINE__;
			RabbitmqHelper::sendAlert($str, "default");
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
		if ($this->getRabbitMQServerConnected()) {
			$this->channel->close();
			$this->connection->close();
		}
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

}

?>
