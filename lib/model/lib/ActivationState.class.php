<?php
/**********************************************
class ActivationState
Desc: information about profile activeness, completeness etc
*********************************************/
class ActivationState
{
//  private $profileStates;

  private $UNDERSCREENED;
  private $INCOMPLETE;
  private $DELETED;
  private $HIDDEN;
  private $DUPLICATE;
  private $ACTIVATED;

  public function __construct(Profile $profile)
  {
	try
	{
		$this->initialize($profile);
	}
	catch(Exception $e)
	{
	       throw new jsException($e);
	}

  }
  public function updateActivationState(Profile $profile)
  {
        try
        {
                $this->initialize($profile);
        }
        catch(Exception $e)
        {
               throw new jsException($e);
        }

  }
/*************************************************
getters and setters of activation class
*************************************************/
  public function getINCOMPLETE() { return $this->INCOMPLETE; }
  private function setINCOMPLETE($INCOMPLETE) { $this->INCOMPLETE=$INCOMPLETE; }
  public function getUNDERSCREENED() { return $this->UNDERSCREENED; }
  private function setUNDERSCREENED($UNDERSCREENED) { $this->UNDERSCREENED=$UNDERSCREENED; }
  public function getDELETED() { return $this->DELETED; }
  private function setDELETED($DELETED) { $this->DELETED=$DELETED; }
  public function getHIDDEN() { return $this->HIDDEN; }
  private function setHIDDEN($HIDDEN) { $this->HIDDEN=$HIDDEN; }
  public function getACTIVATED() { return $this->ACTIVATED; }
  private function setACTIVATED($ACTIVATED) { $this->ACTIVATED=$ACTIVATED; }
  public function getDUPLICATE() { return $this->DUPLICATE; }
  private function setDUPLICATE($DUPLICATE) { $this->DUPLICATE=$DUPLICATE; }

/***********************************************
function initialize
Desc: constructor logic
***********************************************/
  private function initialize(Profile $profile)
  {
    $profileid = $profile->getPROFILEID();
    $this->INCOMPLETE = ($profile->getINCOMPLETE()=='Y')?'Y':'N';
    $this->UNDERSCREENED='N';
    $this->DELETED='N';
    $this->HIDDEN ='N';
    $activatedStatus=$profile->getACTIVATED();
    switch($activatedStatus)
	{
		case 'U':
			if($this->INCOMPLETE=='N')
				$this->UNDERSCREENED='Y';
			break;
		case 'N':
			if($this->INCOMPLETE=='N')
                                $this->UNDERSCREENED='Y';
                        break;
		case 'H':
			$this->HIDDEN ='Y';
			break;
		case 'D':
			$this->DELETED='Y';
			break;
		case 'Y':
			$this->ACTIVATED='Y';
			break;
	}
  }
}
?>
