<?php

/**
 * Description of GenerateOutboundEvent
 *
 * @package     cache
 * @author      Kunal Verma
 * @created     13th June 2017
 */
class GenerateOutboundEvent {

  const ENABLE_GENERATE_EVENT = true;

  /**
   * @var Object
   */ 
  private static $instance = null;

  /**
   * Constructor function
   */
  private function __construct() {
    
  }

  /**
   * __destruct
   */
  public function __destruct() {
    self::$instance = null;
  }

  /**
   * To Stop clone of this class object
   */
  private function __clone() {
    
  }

  /**
   * To stop un-serialize for this class object
   */
  private function __wakeup() {
    
  }

  /**
   * Get Instance
   * @return Object of ProfileCacheLib
   */
  public static function getInstance() {
    if (null === self::$instance) {
      $className = __CLASS__;
      self::$instance = new $className;
    }

    return self::$instance;
  }

 /**
  * 
  * @param type $enEventType
  * @param type $iPgProfileID
  * @param type $iPogProfileID
  */
  public function generate($enEventType, $iPgProfileID, $iPogProfileID = null) {
    if ( GenerateOutboundEvent::ENABLE_GENERATE_EVENT )
    {
      //Add into MQ
      $this->enqueue($enEventType, $iPgProfileID, $iPogProfileID);
      //TO Do Log this event
    }
  }

  /**
   * 
   * @param type $enEventType
   * @param type $iPgProfileID
   * @param type $iPogProfileID
   */
  private function enqueue($enEventType, $iPgProfileID, $iPogProfileID = null) {
    
    $szEventName = $this->getEventType($enEventType);
    $producerObj = new Producer();
    $queueData = array(
        'process' => MessageQueues::OUTBOUND_EVENT,
        'data' => array(
            'type' => $szEventName,
            'body' => array('PG_ID' => $iPgProfileID)),
        'redeliveryCount' => 0
    );

    if (false === is_null($iPogProfileID)) {
      $queueData['data']['body']['POG_PROFILEID_ID'] = $iPogProfileID;
    }
    $producerObj->sendMessage($queueData);
  }
  
  /**
   * 
   * @param type $enEventType
   * @return type
   */
  private function getEventType($enEventType) {
    return $enEventType;
  }
}
