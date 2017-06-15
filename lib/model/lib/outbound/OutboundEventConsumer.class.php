<?php

include_once(JsConstants::$cronDocRoot . '/amq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MessageQueues as MQ;     //MessageQueues-having values defined for constants used in this class.

/*
  This class defines rabbitmq consumer for receiving messages from queues and process messages based on type of msg.
 */

class OutboundEventConsumer {

  private $connection;
  private $channel;
  private $messsagePending;
  private $serverid;
  private $memHandlerObj;
  private $jProfileObj;
  private $bDebugInfo = true;
  const PROFILE_DETAILS = "PROFILEID,ACTIVATED,SUBSCRIPTION,ISD,PHONE_MOB,MOB_STATUS,LANDL_STATUS,PHONE_WITH_STD,COUNTRY_RES";
  /**
   *
   * Constructor for instantiating object of Consumer class
   *
   * <p>
   * Consumer connects to server with $serverid and waits for incoming messages to consume.
   * </p>
   *
   * @access public
   * @param $serverid ,$messageCount
   */
  public function __construct($serverid, $messsageCount) {
    if ($serverid == 'SECOND_SERVER') {
      $this->messsagePending = $messsageCount;
      $this->serverid = 'SECOND_SERVER';
    } else {
      $this->serverid = 'FIRST_SERVER';
    }
    try {
      $this->connection = new AMQPConnection(JsConstants::$rabbitmqConfig[$serverid]['HOST'], JsConstants::$rabbitmqConfig[$serverid]['PORT'], JsConstants::$rabbitmqConfig[$serverid]['USER'], JsConstants::$rabbitmqConfig[$serverid]['PASS'], JsConstants::$rabbitmqConfig[$serverid]['VHOST']);
    } catch (Exception $exception) {
      $str = "\nRabbitMQ Error in consumer, Connection to rabbitmq broker with host-> " . JsConstants::$rabbitmqConfig[$serverid]['HOST'] . " failed: " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str, "default");
    }
    try {
      $this->channel = $this->connection->channel();
      $this->channel->setBodySizeLimit(MQ::MSGBODYLIMIT);
    } catch (Exception $exception) {
      $str = "\nRabbitMQ Error in consumer, Channel not formed : " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str, "default");
      return;
    }
    
    $this->memHandlerObj = new MembershipHandler();
    $this->jProfileObj = JPROFILE::getInstance("newjs_masterRep");
  }

  /**
   *
   * Consumer keeps listening to queues and retrieves messages to process them as they come.
   *
   * @access public
   * @param none
   */
  public function receiveMessage() {
    try {
      $this->channel->queue_declare(MQ::OUTBOUND_QUEUE, MQ::PASSIVE, MQ::DURABLE, MQ::EXCLUSIVE, MQ::AUTO_DELETE);
    } catch (Exception $exception) {
      $str = "\nRabbitMQ Error in consumer, Unable to declare queues : " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str, "default");
      return;
    }
    try {
      $this->channel->basic_consume(MQ::OUTBOUND_QUEUE, MQ::CONSUMER, MQ::NO_LOCAL, MQ::NO_ACK, MQ::CONSUMER_EXCLUSIVE, MQ::NO_WAIT, array($this, 'processMessage'));
    } catch (Exception $exception) {
      $str = "\nRabbitMQ Error in consumer, Unable to consume message from queues : " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str, "default");
      return;
    }
    if ($this->serverid == 'FIRST_SERVER') {
      while (count($this->channel->callbacks)) {
        $this->channel->wait();
      }
    } else {
      while ($this->messsagePending != 0) {
        $this->messsagePending = $this->messsagePending - 1;
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
  //public function processMessage(AMQPMessage $msg) { // TODO 
  public function processMessage($msg) {
    //$msgdata = json_decode($msg->body, true); //TODO 
    $msgdata = json_decode($msg, true);
    $process = $msgdata['process'];
    $redeliveryCount = $msgdata['redeliveryCount'];
    $type = $msgdata['data']['type'];
    $body = $msgdata['data']['body'];
    try {

      switch ($process) {
        case MQ::OUTBOUND_EVENT:
          $this->consumeEvent($body, $type);
          break;
      }
    } catch (Exception $exception) {
      $str = "\nRabbitMQ Error in consumer, Unable to process message: " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str, "default");
      /*
       * The message due to which error is caused is reframed into a new message and the original message is dropped.
       * This new message is pushed at the back of the queue if the number of redelivery attempts is less than a specified a limit.
       */
      if ($redeliveryCount < MessageQueues::REDELIVERY_LIMIT) {
        $reSendData = array('process' => $process, 'data' => array('type' => $type, 'body' => $body), 'redeliveryCount' => $redeliveryCount + 1);
        $producerObj = new Producer();
        $producerObj->sendMessage($reSendData);
      } else {
        RabbitmqHelper::sendAlert("\nDropping message as redelivery attempts exceeded the limit" . "\n");
      }
    }
    try {
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    } catch (Exception $exception) {
      $str = "\nRabbitMQ Error in consumer, Unable to send +ve acknowledgement: " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str);
    }
  }

  /**
   * 
   * @param type $arrData
   * @param type $enEventType
   */
  private function consumeEvent($arrData, $enEventType) {
    
    $iPgId = $arrData['PG_ID'];
    $iPogId = $arrData['POG_PROFILEID_ID'];

    $profileInfo = array("PROFILEID"=>$iPgId);
    //Get in Data 
    if(false === $this->isValidTime()) {
      //TODO Add Logging If Required
      $this->logThis("Time Window Checks Failed", $enEventType, $profileInfo);
      return ;
    }
    
    //Ever Paid Check
    $userDetails = $this->userCheckAndDetails($enEventType, $iPgId);
    if(false === $userDetails) {
      $this->logThis("User Checks Failed", $enEventType, $profileInfo);
      return ;
    }
    
    //Outbound Call happened in Last 
     if(false === $this->outBoundCallStatus($iPgId)) {
      $this->logThis("Outbound call check failed", $enEventType, $profileInfo);
      return ;
    }
    
    $memberShipDetails = $this->checkAnalyticScore($enEventType, $iPgId) ;
    if(false === $memberShipDetails) {
      $this->logThis("Analytic Score check failed", $enEventType, $profileInfo);
      return ;
    }
    
    //TODO Get Verified Phone Numbers
    $verifiedNumber = $this->getVerifiedPhone($userDetails);
    
    if(false === $verifiedNumber) {
      $this->logThis("Phone check failed", $enEventType, $profileInfo);
      return ;
    }
    
    $verifiedNumber = "0".$verifiedNumber;
    $this->logThis("Verified Number of user : ", $enEventType, $verifiedNumber);
    
    /**
     * 
     */
    //TODO check LandingFlowId
    switch ($enEventType) {
      case OutBoundEventEnums::VIEW_CONTACT:
        $landingFlowId = "132130";

        break;
      
      case OutBoundEventEnums::INTEREST_ACCEPTED:
        $landingFlowId = "132129";

        break;
      
      case OutBoundEventEnums::ACCEPT_INTEREST:
        $landingFlowId = "136074"; 

        break;
      default:
        //TODO Add some logging
        $this->logThis("Unsupported Event", $enEventType, $ProfileInfo);
        return ;
        break;
    }
    $landingFlowId = "136074"; 
    $this->callThirdPartyApi("08010619996", "08039510994", $landingFlowId);
  }

  /**
   * 
   * @param type $iProfileId
   * @param type $withInLastDays
   */
  private function outBoundCallStatus($iProfileId, $withInLastDays="3 days") {
    return true;
  }
  
  /**
   * Perform Various User Check
   * Like User is Free or Ever Paid  
   * @param type $iProfileId
   */
  private function userCheckAndDetails($enEventType, $iProfileId) {
    
    //Currently for Event Check for Free User Only
    //TODO Check Deleted User Case
    $userDetail = $this->jProfileObj->get($iProfileId, "PROFILEID", self::PROFILE_DETAILS);
    
    if(0 === strlen($userDetail["SUBSCRIPTION"])) {
      return false;
    }
    
    return $userDetail;
  }
  
  /**
   * 
   * @param type $enEventType
   * @param type $iProfileID
   */
  private function checkAnalyticScore($enEventType, $iProfileID) {
    return true;
    return $this->memHandlerObj->checkEligibleForMemCall($iProfileID);
  }
  
  /**
   * 
   * @return boolean
   */
  private function isValidTime() {
    $orgTZ = date_default_timezone_get();
    date_default_timezone_set("Asia/Calcutta");
    
    $bReturn = false;
    $currentHour = date('H');
    
    if($currentHour >= OutBoundEventEnums::OUTBOUND_CALL_TIME_START && $currentHour <= OutBoundEventEnums::OUTBOUND_CALL_TIME_END) {
      $bReturn = true;
    }
    
    date_default_timezone_set($orgTZ);
    return $bReturn;
  }
  
  /**
   * 
   * @param type $from
   * @param type $to
   * @param type $CallerId
   * @param type $landingFlowId
   */
  private function callThirdPartyApi($toUser, $CallerId, $landingFlowId) {
    $apiId = OutBoundEventEnums::THIRD_PARTY_API_ID;
    $apiAuthToken = OutBoundEventEnums::THIRD_PARTY_API_AUTH_TOKEN;

    //Third Party Url
    $thirdPartyUrl = "https://{$apiId}:{$apiAuthToken}@twilix.exotel.in/v1/Accounts/{$apiId}/Calls/connect";


    $landingUrl = "http://my.exotel.in/exoml/start/{$landingFlowId}";

    $post_data = array(
        'From' => $toUser,
        'CallerId' => $CallerId, //"<Your-Exotel-virtual-number>",
        //'TimeLimit' => "<time-in-seconds> (optional)",
        //'TimeOut' => "<time-in-seconds (optional)>",
        'CallType' => "trans", //Can be "trans" for transactional and "promo" for promotional content
        'Url' => $landingUrl
    );
    
    var_dump($post_data);die(X);
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $thirdPartyUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    
    $http_result = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);

    curl_close($ch);

var_dump($http_result);
  }
  
  /**
   * SUBSCRIPTION
   * @param type $iProfileID
   */
  private function getVerifiedPhone($userDetail)
  {
    $iProfileID = $userDetail["PROFILEID"];
    
    if($userDetail["ISD"] != "91") {
      return false;
    }
    
    $userVerifiedNumber = false;
    
    if($userDetail['MOB_STATUS'] == "Y") {
      $userVerifiedNumber = $userDetail['PHONE_MOB'];
    } else if ($userDetail['LANDL_STATUS'] == "Y") {
      $userVerifiedNumber = $userDetail['PHONE_WITH_STD'];
    } else {
      $objContact = new ProfileContact();
      $contactDetails = $objContact->getProfileContacts($iProfileID);
      $userVerifiedNumber = ($contactDetails['ALT_MOB_STATUS']) == "Y" ? $contactDetails["ALT_MOBILE"] : false; 
    }
    
    return $userVerifiedNumber;
  }
  
  private function logThis($msg, $enEventType, $ProfileInfo)
  {
    if($this->bDebugInfo) {
      echo "\n<br\>",$enEventType,$msg,$ProfileInfo,"\n<br\>";
    }
    
  }
}

?>