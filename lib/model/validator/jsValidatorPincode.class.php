<?php

/*
 * This class is used for email validation in registration and edit modules
 */

/**
 * jsValidatorEmail validates a string. It also converts the input value to a string.
 *
 * @package    symfony
 * @subpackage validator
 * @author     hemant agrawal<hemant.a@jeevansathi.com>
 * @version    SVN: $Id: jsValidatorPincode.class.php 2013-10-28 $
 */
 
 
class jsValidatorPincode extends sfValidatorBase
{
	
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * jsmailFormat checks the input format
   *  * jsmailOld checks if email is already registered
   *  * jsmailDel checks if it has been registered and deleted
   *
   * Available error codes:
   *
   *  * jsmailFormat
   *  * jsmailOld
   *  * jsmailDel
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
	foreach(ErrorHelp::getErrorArrayByField('PINCODE') as $key=>$msg)
	{
		$this->addMessage($key, $msg);
	}
	$this->addOption('city');
	$this->arrayPincode=array("DE00"=>array(0=>array("1100","2013","1220","2010","1210","1245"),
            1=>4,2=>"err_pin_delhi"),
                    "MH04"=>array(0=>array("400","401","410","421","416"),1=>3,
                        2=>"err_pin_mumbai"),
                    "MH08"=>array("0"=>array("411","410","412","413"),1=>3,
                 2=>"err_pin_pune"));
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
	$pin = (string) $value;
    $pin = trim($pin);

    $city = $this->getOption('city');
    if ($this->_pincodeValidation($pin, $city))
    {
      throw new sfValidatorError($this, 'err_pin_req', array('value' => $pin, 'err_pin_req' => $this->getOption('err_pin_req')));
    }
    
     if ($this->_pincodeInitials($pin, $city))
    { 
		throw new sfValidatorError($this, 'err_pin_invalid', array('value' => $pin, 'err_pin_invalid' => $this->getOption('err_pin_invalid')));
    }
    
    if ($this->_pincodeDelhi($pin, $city))
    { 
		throw new sfValidatorError($this,$this->arrayPincode[$city][2], array('value' => $pin, 'err_pin_invalid' => $this->getOption('err_pin_invalid')));
    }
    
   
    if(!$this->arrayPincode[$city])
    {
		$pin = '';
	}
    
    return $pin;
  }
  

	private function _pincodeValidation($pin, $city)
	{
		if($this->arrayPincode[$city] && $pin=="")
		{
			return 1;
		}
		return 0;

	}
	
	private function _pincodeDelhi($pin, $city)
	{
		$cnt=$this->arrayPincode[$city][1];
		$pinInititial = substr($pin, 0, $cnt);
		if($pinInititial!=false && is_array($this->arrayPincode[$city][0]) && !in_array($pinInititial,$this->arrayPincode[$city][0]))
		{
			if($this->arrayPincode[$city])
				return 1;
		}
					
		return 0;
	}
	
	private function _pincodeInitials($pin, $city)
	{
		if(!(strlen($pin) == 6 && jsvalidatorPincode::is_digits($pin)))
		{
			if($this->arrayPincode[$city])
				return 1;
		}
		return 0;
	}
	
	/* digits only, no dots */
	static function is_digits($element) {
		return !preg_match ("/[^0-9]/", $element);
	}
 
	protected function isEmpty($value)
	{
		return in_array($value, array(null,array()), true);
	}
}
