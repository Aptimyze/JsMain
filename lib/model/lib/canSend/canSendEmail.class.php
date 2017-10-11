<?php

/**
 * canSendEmail class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 */
class canSendEmail extends canSendBaseClass {
 
  /*
   * Declaring Member Varibales
   */
  /*
   * @access protected emailid
   */

  protected $email;
  /*
   * @access protected profileSubscription
   */

  protected $profileSubscription;

  /*
   * @access protected subscriptionField
   */

  protected $subscriptionField;

  /*
   * Declaring and Defining Member Function
   */
   
  public function __construct($emailArray,$profileid,$subscriptionClassObj='',$bouncedMailObj,$mailID="") 
  {
	parent::__construct($profileid);
	$this->channel = CanSendEnums::$channelEnums[EMAIL];
	$this->email = $emailArray['EMAIL'];
        $this->subscriptionClassObj = $subscriptionClassObj;
	$this->alertType = $emailArray['EMAIL_TYPE'];
        $this->bouncedMailObj = $bouncedMailObj;
        if($mailID)
        {
          $this->mailID = $mailID;
        }
	if(!$this->email || !$this->alertType)
		$this->incompleteData=true;
  }
 /*
  * checks if email can be sent to that particluar user
  */

  public function canSendIt() 
  {
	if($this->incompleteData==true){
		$this->deliveryStatus='I';
		return $this->canSend=false;
	}
	$this->canSend = $this->checkInBounceEmails($this->bouncedMailObj);
	if(!$this->canSend)
		$this->deliveryStatus='B';
	if($this->canSend)
	{
		$this->canSend = $this->checkSubscription();
		if(!$this->canSend)
			$this->deliveryStatus='U';
	}
	if($this->canSend)
		$this->deliveryStatus='Y';

	return $this->canSend;
  }
  public function getDeliveryStatus()
  {
        return $this->deliveryStatus;
  }

 /*
  * checks if email id is present in bounced mail logs or not
  */

  protected function checkInBounceEmails($bouncedMailObj)
  {
	//$bouncedMailObj = new bounces_BOUNCED_MAILS;

	$emailBounced = $bouncedMailObj->checkEntry($this->email);
        
	if($emailBounced)
		return false;
	return true;
  }
  /*
   * checks if the particula ruser has subscribed for the alert
   */
  private function checkSubscription()
  {
        $subscriptionField = CanSendEnums::$channelTypeToFieldMap[$this->channel][$this->alertType];
	if($subscriptionField)
	{
		$profileSubscribed = $this->subscriptionClassObj->getSubscriptions($this->profileid,$subscriptionField);
    if($this->mailID && in_array($this->mailID, CanSendEnums::$exceptionForMailId))
    {
      return true;
    }
		elseif($profileSubscribed && $profileSubscribed==CanSendEnums::$fieldMap[$subscriptionField]['NOT_ALLOWED_VALUE'])
		{
			return false;
		}
	}
	return true;
  }
}
