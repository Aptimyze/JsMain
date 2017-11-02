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
  
  
  /**
   *
   * @var type 
   */
  private $memHandlerObj;
  
  /**
   *
   * @var type 
   */
  private $jProfileObj;
  
  /**
   *
   * @var type 
   */
  private $bDebugInfo = false;
  
  /**
   * 
   */
  const PROFILE_DETAILS = "PROFILEID,ACTIVATED,SUBSCRIPTION,ISD,PHONE_MOB,MOB_STATUS,LANDL_STATUS,PHONE_WITH_STD,COUNTRY_RES,MTONGUE";
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
  public function processMessage(AMQPMessage $msg) {
    $msgdata = json_decode($msg->body, true); 
    
    $process = $msgdata['process'];
    $redeliveryCount = $msgdata['redeliveryCount'];
    $type = $msgdata['data']['type'];
    $body = $msgdata['data']['body'];
    $codeException = 0;
    $deliveryException = 0;
    try {

      switch ($process) {
        case MQ::OUTBOUND_EVENT:
          $this->consumeEvent($body, $type);
          break;
      }
    } catch (Exception $exception) {
      $codeException = 1;
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
      $deliveryException = 1;
      $str = "\nRabbitMQ Error in consumer, Unable to send +ve acknowledgement: " . $exception->getMessage() . "\tLine:" . __LINE__;
      RabbitmqHelper::sendAlert($str);
    }
    if($codeException || $deliveryException){
        die("Killed due to code exception or delivery exception");
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

    $arrInfo = array("PROFILEID"=>$iPgId);
    //Get in Data 
    if(false === $this->isValidTime()) {
      $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_APINOTCALLED;
      $this->logThis("Time Window Checks Failed", $enEventType, $arrInfo);
      return ;
    }
    
    //Ever Paid Check and Mtongue Check and Activation Check
    $userDetails = $this->userCheckAndDetails($enEventType, $iPgId);
    if(false === $userDetails) {
      $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_APINOTCALLED;
      $this->logThis("User Checks Failed", $enEventType, $arrInfo);
      return ;
    }
    
    //Outbound Call happened in Last 
     if(false === $this->outBoundCallStatus($iPgId)) {
      $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_APINOTCALLED;
      $this->logThis("Outbound call check failed", $enEventType, $arrInfo);
      return ;
    }
    
    //Check analytic score and get Minimun Memebership plan to pitch user
    $memberShipValue = $this->getMinMemberShipValue($enEventType, $iPgId) ;
    if(false === $memberShipValue) {
      $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_APINOTCALLED;
      $this->logThis("Analytic Score check failed", $enEventType, $arrInfo);
      return ;
    }
     $memberShipValue = $memberShipValue['OFFER_PRICE'];
 
    //Get Verified Phone Numbers
    $verifiedNumber = $this->getVerifiedPhone($userDetails);
    
    if(false === $verifiedNumber) {
      $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_APINOTCALLED;
      $this->logThis("Verified Phone check failed", $enEventType, $arrInfo);
      return ;
    }
    
    // TODO: No status in arr?
    $verifiedNumber = "0".$verifiedNumber;
    //$this->logThis("Verified Number of user : ".$verifiedNumber, $enEventType, $arrInfo);
    
    /**
     * 
     */
    //check LandingFlowId
    switch ($enEventType) {
      case OutBoundEventEnums::VIEW_CONTACT:
        $landingFlowId = "136659";

        break;
      
      case OutBoundEventEnums::INTEREST_ACCEPTED:
        $landingFlowId = "136663";

        break;
      
      case OutBoundEventEnums::ACCEPT_INTEREST:
        $landingFlowId = "136074"; 

        break;
      default:
        //TODO Add some logging
        $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_APINOTCALLED;
        $this->logThis("Unsupported Event", $enEventType, $arrInfo);
        return ;
        break;
    }

    $callerId = '01139587944';
    //$verifiedNumber = "08010619996";//TODO remove this
    
    $response = $this->callThirdPartyApi($verifiedNumber, $callerId, $landingFlowId, $memberShipValue);
    
    if(false !== $response['response']) {
      $this->logThisApiCall($iPgId, $verifiedNumber, $enEventType, $callerId, $landingFlowId, $response['response'], $response['sid']);
    }
    $arrInfo['STATUS'] = OutBoundEventEnums::OUTBOUND_EVENT_STATUS_SUCCESS;
    $this->logThis("Success", $enEventType, $arrInfo);
  }

  /**
   * 
   * @param type $iProfileId
   * @param type $withInLastDays : In days
   */
  private function outBoundCallStatus($iProfileId, $withInLastDays = OutBoundEventEnums::OUTBOUND_CALL_NOT_HAPPENED_IN_LAST_DAYS) {
    $storeObj = new OUTBOUND_THIRD_PARTY_CALL_LOGS;
    $lastRecordData = $storeObj->getLastRecord($iProfileId);
    
    if($lastRecordData) {
      $datetime1 = new DateTime(date('Y-m-d H:i:s'));
      $datetime2 = new DateTime($lastRecordData["DATE_TIME"]);
      
      $interval = $datetime1->diff($datetime2);
      if($interval->days < $withInLastDays) {
        return false;
      }
    }
    return true;
  }
  
  /**
   * Perform Various User Check
   * Like User is Free or Ever Paid  
   * @param type $iProfileId
   */
  private function userCheckAndDetails($enEventType, $iProfileId) {
    
    //Currently for Event Check for Free User Only
    $userDetail = $this->jProfileObj->get($iProfileId, "PROFILEID", self::PROFILE_DETAILS);

    if ($userDetail["ACTIVATED"] !== 'Y') {
      return false;
    }

    if(0 !== strlen($userDetail["SUBSCRIPTION"])) {
      return false;
    }
    
    //Mother Tongue
    $allHindiMtongue = FieldMap::getFieldLabel("allHindiMtongues", '',1);
    if(false === in_array($userDetail['MTONGUE'],$allHindiMtongue)) {
      return false;
    }
    
    return $userDetail;
  }
  
  /**
   * 
   * @param type $enEventType
   * @param type $iProfileID
   */
  private function getMinMemberShipValue($enEventType, $iProfileID) {
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
    $currentMin = date('i');
    
    if($currentHour >= OutBoundEventEnums::OUTBOUND_CALL_TIME_START && $currentHour <= OutBoundEventEnums::OUTBOUND_CALL_TIME_END) {
      $bReturn = true;
    }
    
    if($bReturn && $currentHour == OutBoundEventEnums::OUTBOUND_CALL_TIME_END && $currentMin > OutBoundEventEnums::OUTBOUND_CALL_TIME_MINUTES_END) {
      $bReturn = false;
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
  private function callThirdPartyApi($toUser, $CallerId, $landingFlowId, $minMemberShipValue) {
    $apiId = OutBoundEventEnums::THIRD_PARTY_API_ID;
    $apiAuthToken = OutBoundEventEnums::THIRD_PARTY_API_AUTH_TOKEN;

    //Third Party Url
    $thirdPartyUrl = "https://{$apiId}:{$apiAuthToken}@twilix.exotel.in/v1/Accounts/{$apiId}/Calls/connect";


    $landingUrl = "http://my.exotel.in/exoml/start/{$landingFlowId}";

    $callBackUrl = JsConstants::$siteUrl."/api/v1/static/outboundcallstatus";

    $post_data = array(
        'From' => $toUser,
        'CallerId' => $CallerId, //"<Your-Exotel-virtual-number>",
        //'TimeLimit' => "<time-in-seconds> (optional)",
        //'TimeOut' => "<time-in-seconds (optional)>",
        'CallType' => "trans", //Can be "trans" for transactional and "promo" for promotional content
        'Url' => $landingUrl,
        'StatusCallback' => $callBackUrl,
        'CustomField' => $minMemberShipValue
    );
    
    //var_dump($post_data);die(X);
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $thirdPartyUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    
    $stTime = microtime();
    $http_result = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    $endTime = microtime();
    //TODO : Parse Http Result
    $apiResponse = $this->parseXML($http_result);
    return $apiResponse;
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
    }
    
    return $userVerifiedNumber;
  }
  
  /**
   * 
   * @param type $msg
   * @param type $enEventType
   * @param type $profileInfo
   */
  private function logThis($msg, $enEventType, $arrInfo)
  {
    if($this->bDebugInfo) {
      echo "\n<br\>",$enEventType,$msg,print_r($arrInfo,true),"\n<br\>";
    }

    $now = date('Y-m-d H:i:s');
    $arrRecordData = array(
        "REASON" => $msg,
        "PGID" => $arrInfo["PROFILEID"],
        "EVENT_TYPE" => $enEventType,
        "STATUS" => $arrInfo["STATUS"],
        "DATE_TIME" => $now,
      );

    $storeObj = new OUTBOUND_STATUS_LOGS();
    $storeObj->insertRecord($arrRecordData);

  }
  
  /**
   * 
   * @param type $szCalledUserId
   * @param type $szPhoneNumber
   * @param type $szEventType
   * @param type $szCallerId
   * @param type $szLandingFlowId
   * @param type $apiResponse
   */
  private function logThisApiCall($szCalledUserId, $szPhoneNumber, $szEventType, $szCallerId, $szLandingFlowId, $apiResponse, $callSid) 
  {  
  
    $now = date('Y-m-d H:i:s');
    $arrRecordData = array(
       "CALLED_USER_ID" => $szCalledUserId,
       "PHONE_NUMBER" => $szPhoneNumber,
       "EVENT_TYPE" => $szEventType,
       "CALLER_ID" => $szCallerId,
       "LANDING_FLOW_ID" => $szLandingFlowId,
       "RESPONSE_FROM_THIRD_PARTY" => $apiResponse,
       "DATE_TIME" => $now,
       "CALLSID" => $callSid,
      ); 
    
    $storeObj = new OUTBOUND_THIRD_PARTY_CALL_LOGS();
    $storeObj->insertRecord($arrRecordData);
  }
  
  /**
   * 
   * @param type $xmlString
   * @return type
   */
  private function parseXML($xmlString) {
    $xmlObj = simplexml_load_string($xmlString, "SimpleXMLElement");
    $json = json_encode($xmlObj);
    $array = json_decode($json,TRUE);
    if($array["Call"])
    {
      $callSid = $array["Call"]["Sid"];
      $response  = " Status : ".$array["Call"]["Status"]." - StartTime : ".$array["Call"]["StartTime"];
      return array("response" => $response, "sid" => $callSid);
    }
    elseif($array["RestException"])
    {
      $response = "Status : " . $array["RestException"]["Status"].' - Message'.$array["RestException"]["Message"];
      return array("response" => $response);
    }
  }
}

?>
