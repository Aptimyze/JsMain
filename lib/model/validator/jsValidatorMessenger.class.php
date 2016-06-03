<?php

/*
 * This class is used for messenger validation in registration and edit modules
 */

/**
 * jsValidatorMessenger validates a string. It also converts the input value to a string.
 *
 * @package    symfony
 * @subpackage validator
 * @author     hemant agrawal<hemant.a@jeevansathi.com>
 * @version    SVN: $Id: jsValidatorMesseneger.class.php 12641 2013-05-27 18:22:00Z hemant $
 */
 
 
class jsValidatorMessenger extends sfValidatorBase
{
	CONST REGEX_MES1 = '/^[a-zA-Z0-9._%+-@]+$/';
	CONST REGEX_MES2 = '/.*[a-zA-Z]+.*/';
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * err_messenger_invalid checks the restricted uname
   *  * err_messenger_alpha checks if messenger has atleast one alphabate
   *  * err_messenger_pattern checks if it has been as per messenger id rules
   *  * err_messenger_min checks if messneger id is of appropriate length
   * Available error codes:
   *
   *  * err_messenger_invalid
   *  * err_messenger_alpha
   *  * err_messenger_pattern
   *  * err_messenger_min
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
	foreach(ErrorHelp::getErrorArrayByField('MESSENGER_ID') as $key=>$msg)
	{
		$this->addMessage($key, $msg);
	}
  }

	/**
	* @see sfValidatorBase
	*/
	protected function doClean($value)
	{
		$mes = trim((string) $value);

		if ($this->_messengerValidation($mes))
		{
		  throw new sfValidatorError($this, 'err_messenger_invalid', array('value' => $value));
		}
		else if ($this->_messengerOneAlpha($mes))
		{
		  throw new sfValidatorError($this, 'err_messenger_alpha', array('value' => $value));
		}
		else if ($this->_messengerRegex($mes))
		{
		  throw new sfValidatorError($this, 'err_messenger_pattern', array('value' => $value));
		}
		else if ($this->_messengerLength($mes))
		{
		  throw new sfValidatorError($this, 'err_messenger_min', array('value' => $value));
		}
		
		if($mes == 'e.g. raj1983, vicky1980')
			$val = '' ;
		else
			$val = $mes;
		$mesId = explode('@',$val);
		return $mesId[0];

	}

	private function _messengerValidation($mes)
	{
	  if($mes == 'e.g. raj1983, vicky1980' || $mes == '')
	   return 0;
	   
	  //check for invalid id's
	  $mesArr = InvalidEmails::getInvalidMessengerArr();
	  $messenger = explode('@',$mes);
	  $mesId = $messenger[0];
	  
	  if(in_array(strtolower($mesId),$mesArr)) 
	  {
		  return 1;
	  }  
	  return 0;

	}

	private function _messengerRegex($mes)
	{
	  if($mes == 'e.g. raj1983, vicky1980' || $mes == '')
	   return 0;
	   
	  $messenger = explode('@',$mes);
	  $mesId = $messenger[0];
	  
	  if(!preg_match(jsValidatorMessenger::REGEX_MES1,$mesId))
	  {
		  return 1;
	  }
	  return 0; 
	  
	}

	private function _messengerOneAlpha($mes)
	{
	  if($mes == 'e.g. raj1983, vicky1980' || $mes == '')
	   return 0;
	   
	  $messenger = explode('@',$mes);
	  $mesId = $messenger[0];
	  
	  if(!preg_match(jsValidatorMessenger::REGEX_MES2,$mesId))
	  {
		  return 1;
	  }

	  return 0;
		
	}

	private function _messengerLength($mes)
	{
	  if($mes == 'e.g. raj1983, vicky1980' || $mes == '')
	   return 0;

	  $messenger = explode('@',$mes);
	  $mesId = $messenger[0];
	  
	  if(strlen($mesId) < 4)
	  {
		  return 1;
	  }
	  
	  return 0;
	  
	}
  
  
}
