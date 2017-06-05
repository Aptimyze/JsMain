<?php

/**
 * CLASS canSendBase
 * This class carries all common functionalities and must methods prototyping.</p>
 * @package   jeevansathi
 * @author    Esha Jain <esha.jain@jeevansathi.com>
 * @copyright 2015 Esha Jain
 */
abstract class canSendBaseClass {
  /*
   * Declaring Memeber Varibales
   */
 
  /*
   * @access protected canSend
   */

  protected $canSend;

  /*
   * @access protected channel
   */

  protected $channel;
  /*
   * @access protected alertType
   */

  protected $alertType;

  /*
   * @access protected profileObj;
   */

  protected $profileid;

  /*
   * @access protected incompleteData;
   */

  protected $incompleteData=false;

  /*
   * @access protected mailStatus;
   */

  protected $deliveryStatus;
 
  public function __construct($profileid) {
	$this->profileid = $profileid;
  }
 /*
  * checks if the alert can be sent to that particluar user
  */
  abstract protected function canSendIt();
}
?>
