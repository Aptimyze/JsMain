<?php
/***********************************************
class PaymentState
Desc: information regarding payment status of the profile
***********************************************/
class PaymentState
{
	const FTO = "FTO";
	const ERISHTA = "ERISHTA";
	const EVALUE = "EVALUE";
	const FREE = "FREE";
	const JSEXCLUSIVE = "JSEXCLUSIVE";
	private $EVALUE;
	private $ERISHTA;
	private $FTO;
	private $FREE;
	private $JSEXCLUSIVE;
	private $AP;

	public function __construct(Profile $profile,$ftoState='')
	{
		try
		{	$this->initialize($profile,$ftoState);}
		catch(Exception $e)
		{	throw new jsException($e);}
	}
	public function updatePaymentState(Profile $profile, $ftoState='')
	{
		try
		{	$this->initialize($profile,$ftoState);}
		catch(Exception $e)
		{	throw new jsException($e);}
	}
/**********************************************
getters and setters of class payment state
**********************************************/
	public function getFTO() { return $this->FTO; }
	public function getERISHTA() { return $this->ERISHTA; }
	public function getEVALUE() { return $this->EVALUE; }
	public function getFREE() { return $this->FREE; }
	public function getJSEXCLUSIVE() { return $this->JSEXCLUSIVE; }
	public function getAP() { return $this->AP; }
	public function isPAID()	
	{
		if($this->ERISHTA||$this->EVALUE)
			return true;
		else
			return false;
	}
/***************************************************
Function initialize
Desc: constructor logic
***************************************************/
	private function initialize(Profile $profile,$ftoStateObj='')
	{
		$this->EVALUE = false;
		$this->ERISHTA = false;
		$this->FREE = false;
		$this->FTO = false;
		$subscription =	 $profile->getSUBSCRIPTION();
		if(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D"))
			$this->EVALUE = true;
		elseif(strstr($subscription,"F"))
			$this->ERISHTA = true;
		elseif(($ftoStateObj->getState()==FTOStateTypes::FTO_ELIGIBLE) || 
			($ftoStateObj->getState()==FTOStateTypes::FTO_ACTIVE) || 
			(in_array( $ftoStateObj->getSubState(),array ( FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT, FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT,FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT)) ) )
			$this->FTO = true;
		else
			$this->FREE = true;
		if(MembershipHandler::isEligibleForRBHandling($this->profileObj->getPROFILEID()))
			$this->JSEXCLUSIVE = true;
	}
/**************************************************
function getPaymentStatus()
Desc: returns current status of the profile in form of string
**************************************************/
	public function getPaymentStatus()
	{
		if($this->FTO)
			return PaymentState::FTO;
		if($this->ERISHTA)
			return PaymentState::ERISHTA;
		if($this->EVALUE)
			return PaymentState::EVALUE;
		if($this->FREE)
			return PaymentState::FREE;
		if($this->JSEXCLUSIVE)
			return PaymentState::JSEXCLUSIVE;
	}
	public static function IsJsExclusiveMember($subscription)
	{
		if(stristr($subscription,"X"))
			return true;
		
		return false;
	}
}
?>
