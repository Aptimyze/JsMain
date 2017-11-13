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
        $this->reqId = str_replace(".","",microtime(true));
		if (JsMemcache::getInstance()->get("mqMemoryAlarmFIRST_SERVER") == true || JsMemcache::getInstance()->get("mqDiskAlarmFIRST_SERVER") == true || $this->serverConnection('FIRST_SERVER') == false) {
			if (MQ::FALLBACK_STATUS == true && $useFallbackServer == true && JsConstants::$hideUnimportantFeatureAtPeakLoad == 0) {
				if (JsMemcache::getInstance()->get("mqMemoryAlarmSECOND_SERVER") == true || JsMemcache::getInstance()->get("mqDiskAlarmSECOND_SERVER") == true || $this->serverConnection('SECOND_SERVER') == false) {
                    $firstServerDiskAlarmValue = JsMemcache::getInstance()->get("mqDiskAlarmValueFIRST_SERVER");
                    $secondServerDiskAlarmValue = JsMemcache::getInstance()->get("mqDiskAlarmValueSECOND_SERVER");
                    $firstServerMemoryAlarmValue = JsMemcache::getInstance()->get("mqMemoryAlarmValueFIRST_SERVER");
                    $secondServerMemoryAlarmValue = JsMemcache::getInstance()->get("mqMemoryAlarmValueSECOND_SERVER");
                    $alarmStr = "1stDiskAlarm:".$firstServerDiskAlarmValue."\t 2ndDiskAlarm:".$secondServerDiskAlarmValue."\t 1stMemoryAlarm:".$firstServerMemoryAlarmValue."\t 2ndMemoryAlarm:".$secondServerMemoryAlarmValue."\t";
					$str = "\nRabbitMQ Error in producer, Connection to both rabbitmq brokers failed with host-> " . JsConstants::$rabbitmqConfig['FIRST_SERVER']['HOST'] . " and " . JsConstants::$rabbitmqConfig['SECOND_SERVER']['HOST'] . "\t 1sDiskAlarm: ".$alarmStr."Line:" . __LINE__;
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
			$startLogTime = microtime(true);
			$this->connection = new AMQPConnection(JsConstants::$rabbitmqConfig[$serverId]['HOST'], JsConstants::$rabbitmqConfig[$serverId]['PORT'], JsConstants::$rabbitmqConfig[$serverId]['USER'], JsConstants::$rabbitmqConfig[$serverId]['PASS'], JsConstants::$rabbitmqConfig[$serverId]['VHOST']);
			$endLogTime = microtime(true);
            $connectionRedis = MQ::$rmqConnectionTimeout["redisLogging"];
            $memcacheObj = JsMemcache::getInstance();
            if($connectionRedis){
                if($memcacheObj){
                    $totalConnectionKey = "Prodconn".date('Y-m-d');
                    $cacheValue = $memcacheObj->get($totalConnectionKey,null,0,0);
                    if(empty($cacheValue)==false){
                        $memcacheObj->incrCount($totalConnectionKey);
                    }
                    else{
                        $memcacheObj->set($totalConnectionKey,1,86400,0,'X');
                    }
                }
            }
            if(MQ::$logConnectionTime == 1){
                $logText["source"] = "ConnectionTime Producer";
                RabbitmqHelper::rmqLogging("",$startLogTime,$endLogTime,$this->reqId,MQ::$rmqConnectionTimeout["threshold"],$logText);
            }
			if(MQ::$logConnectionTime == 1){
				$diff = $endLogTime-$startLogTime;
                if($diff > MQ::$rmqConnectionTimeout["threshold"]){
                    if($connectionRedis && $memcacheObj){
                        $thresholdKey = "ProdconnTimeout".date('Y-m-d');
                        $cacheValue = $memcacheObj->get($thresholdKey,null,0,0);
                        if(empty($cacheValue)==false){
                            $memcacheObj->incrCount($thresholdKey);
                        }
                        else{
                            $memcacheObj->set($thresholdKey,1,86400,0,'X');
                        }
                    }
                }
			}
			$this->setRabbitMQServerConnected(1);
			return true;
		} catch (Exception $e) {
			//logging the counter for rabbitmq connection timeout in redis
			if(MQ::$rmqConnectionTimeout["log"] == 1 && $serverId == "FIRST_SERVER"){
                $logText["source"] = "ConnectionTimeOut Producer Exception";
                RabbitmqHelper::rmqLogging("",$startLogTime,$endLogTime,$this->reqId,MQ::$rmqConnectionTimeout["threshold"],$logText);
                
				if($connectionRedis && $memcacheObj){
					$cachekey = "rmqtimeout_".date("Y-m-d");
					$cacheValue = $memcacheObj->get($cachekey,null,0,0);
					if(empty($cacheValue)==false){
						$memcacheObj->incrCount($cachekey);
					}
					else{
						$memcacheObj->set($cachekey,1,86400,0,'X');
					}
				}
			}
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
                        
			$this->channel->queue_declare(MQ::UPDATE_MATCHALERTS_REG_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        
			$this->channel->queue_declare(MQ::UPDATE_FEATURED_PROFILE_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

			$this->channel->queue_declare(MQ::UPDATE_CRITICAL_INFO_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        $this->channel->queue_declare(MQ::UPDATE_MATCHALERTS_LAST_SEEN_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        $this->channel->queue_declare(MQ::UPDATE_JUSTJOINED_LAST_SEEN_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        
			$this->channel->queue_declare(MQ::CHAT_MESSAGE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

			$this->channel->queue_declare(MQ::DUPLICATE_LOG_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::PROFILE_CACHE_Q_DELETE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        $this->channel->queue_declare(MQ::VIEW_LOG, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::SCREENING_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
                        $this->channel->queue_declare(MQ::SCREENING_MAILER_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::LOGGING_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
            $this->channel->queue_declare(MQ::DISC_HISTORY_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
            $this->channel->queue_declare(MQ::COMMUNITY_DISCOUNT_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
			$this->channel->queue_declare(MQ::INSTANT_EOI_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

      $this->channel->queue_declare(MQ::SCRIPT_PROFILER_Q, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);    
			
      $this->channel->queue_declare(MQ::WRITE_MSG_queueRightNow);
       			$this->channel->queue_declare(MQ::PRODUCT_METRIC_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

			$this->channel->exchange_declare(MQ::WRITE_MSG_exchangeRightNow, 'direct');
			$this->channel->queue_bind(MQ::WRITE_MSG_queueRightNow, MQ::WRITE_MSG_exchangeRightNow);
			$this->channel->queue_declare(MQ::WRITE_MSG_queueDelayed5min, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, 
					array(
						"x-dead-letter-exchange" => array("S", MQ::WRITE_MSG_exchangeRightNow),
						"x-message-ttl" => array("I", MQ::DELAY_WRITEMSG*1000))
					);
			$this->channel->exchange_declare(MQ::WRITE_MSG_exchangeDelayed5min, 'direct');
      $this->channel->queue_bind(MQ::WRITE_MSG_queueDelayed5min, MQ::WRITE_MSG_exchangeDelayed5min);
      
      //For Instant Mail
      $this->channel->queue_declare(MQ::DELAYED_INSTANT_MAIL, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, 
					array(
            "x-dead-letter-routing-key"=>array("S",MQ::MAILQUEUE),
            "x-dead-letter-exchange" => array("S", MQ::EXCHANGE),
						"x-message-ttl" => array("I", MQ::INSTANT_MAIL_DELAY_TTL*1000))
					);
      $this->channel->queue_bind(MQ::DELAYED_INSTANT_MAIL, MQ::WRITE_MSG_exchangeDelayed5min, MQ::DELAYED_INSTANT_MAIL);
      
      //OutBound Event Queue
      $this->channel->queue_declare(MQ::OUTBOUND_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);

      //RB interests queue
      $this->channel->queue_declare(MQ::RB_INTERESTS_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
      
      //After Welcome call email for exclusive members delayed queue 
      $this->channel->queue_declare(MQ::EXCLUSIVE_MAIL_DELAY_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE, true, 
					array("x-dead-letter-exchange" => array("S", MQ::EXCHANGE),
                                              "x-message-ttl" => array("I", MQ::EXCLUSIVE_MAIL_DELAY_UNIT*60*60*1000),
                                              "x-dead-letter-routing-key"=>array("S",MQ::EXCLUSIVE_MAIL_SENDING_QUEUE)
                                              ));
      $this->channel->queue_declare(MQ::EXCLUSIVE_MAIL_SENDING_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
		} catch (Exception $exception) {
			$str = "\nRabbitMQ Error in producer, Unable to" . " declare queues : " . $exception->getMessage() . "\tLine:" . __LINE__;
			RabbitmqHelper::sendAlert($str, "default");
			return;
		}
		$data = json_encode($msgdata);
		$msg = new AMQPMessage($data, array('delivery_mode' => MQ::DELIVERYMODE));
		$process = $msgdata['process'];
		try {
            $startPublishTime = microtime(true);
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
				case "MATCHALERTS_REG":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_MATCHALERTS_REG_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
				case "UPDATE_CRITICAL_INFO_PROFILE":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_CRITICAL_INFO_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "MATCHALERTS_LAST_SEEN":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_MATCHALERTS_LAST_SEEN_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
				case "JUSTJOINED_LAST_SEEN":
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::UPDATE_JUSTJOINED_LAST_SEEN_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
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
                case MQ::SCREENING_MAILER:
                	$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::SCREENING_MAILER_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                	break;
                case 'LOGGING_TRACKING':
                	$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::LOGGING_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                	break;

                case 'DISCOUNT_HISTORY':
                    $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::DISC_HISTORY_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                    break;
                case 'COMMUNITY_DISCOUNT':
                    $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::COMMUNITY_DISCOUNT_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
                    break;
        case MQ::SCRIPT_PROFILER_PROCESS:
            $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::SCRIPT_PROFILER_Q,MQ::MANDATORY,MQ::IMMEDIATE);
          break;
				case MQ::INSTANT_EOI_PROCESS:
					$this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::INSTANT_EOI_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
					break;
        case MQ::DELAYED_MAIL_PROCESS:
          $this->channel->basic_publish($msg, MQ::WRITE_MSG_exchangeDelayed5min,MQ::DELAYED_INSTANT_MAIL);
          break;

        case MQ::PRODUCT_METRICS:
                     $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::PRODUCT_METRIC_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
        break;
        case MQ::OUTBOUND_EVENT:
            $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::OUTBOUND_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);

        case "RBSendInterests":
            $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::RB_INTERESTS_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
          break;
      
        case "EXCLUSIVE_DELAYED_EMAIL":
            $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::EXCLUSIVE_MAIL_DELAY_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
        break;

        case "EXCLUSIVE_MAIL":
            $this->channel->basic_publish($msg, MQ::EXCHANGE, MQ::EXCLUSIVE_MAIL_SENDING_QUEUE, MQ::MANDATORY, MQ::IMMEDIATE);
            break;
			}
            $endPublishTime = microtime(true);
            if(MQ::$rmqConnectionTimeout["logPublishTime"] == 1){
                $logPath = JsConstants::$cronDocRoot.'log/rabbitTimePublish'.date('Y-m-d').'.log';
                $logText["source"] = "PublishTime Producer";
                RabbitmqHelper::rmqLogging($logPath,$startPublishTime,$endPublishTime,$this->reqId,MQ::$rmqConnectionTimeout["publishThreshold"],$logText);
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
