<?php
/**
 * @class ProfileState
 * @brief contains get, set, update methods for current state(profile state and FTO state) of individual profile registered on Jeevansathi
 * object is created with Profile object
 * Find more information in http://devjs.infoedge.com/mediawiki/wikiImages/states.png
 * @author Esha Jain
 */

class ProfileState
{
        private $activationStates;
        private $FTOStates;
	private $paymentStates;
        public function __construct(Profile $profile)
        {
		try
		{	$this->initialize($profile);}
		catch(Exception $e)
		{	throw new jsException($e);}
        }
        public function getActivationState() { return $this->activationStates;}
	public function getFTOStates() { return $this->FTOStates;}
	public function getPaymentStates() { return $this->paymentStates;}
	public function setActivationState($ACTIVATION_STATES) 
	{ 
		if($ACTIVATION_STATES instanceof ActivationState)
			$this->activationStates=$ACTIVATION_STATES;
	}
	public function setFTOStates($FTOSTATES) 
	{
		if($FTOSTATES instanceof FTOState)
			 $this->FTOStates=$FTOSTATES;
	}
	public function setPaymentStates($PAYMENT_STATES) 
	{ 
		if($PAYMENT_STATES instanceof PaymentState)
			$this->paymentStates=$PAYMENT_STATES;
	}

        private function initialize(Profile $profile)
        {
                if ($profile instanceof Profile) {
			$this->activationStates	=       new ActivationState($profile);
			if(!($this->FTOStates instanceof FTOState))
				$this->FTOStates        =       new FTOState($profile);
			$this->paymentStates	=	new PaymentState($profile,$this->FTOStates);
		}
        }
        public function updateFTOState(Profile $profile, $action='')
        {
		try
		{
			$this->FTOStates->updateState($profile,$action);
	//                print_r($this->FTOStates);
		}
		catch(Exception $e)
		{	throw new jsException($e);}
        }

}
?>
