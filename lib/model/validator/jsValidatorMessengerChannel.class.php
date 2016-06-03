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
 
 
class jsValidatorMessengerChannel extends sfValidatorBase
{
/**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * err_messenger_invalid checks the restricted uname
   *  
   * 	Available error codes:
   *
   *  * err_messenger_channel_invalid
   *  
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
	  
	foreach(ErrorHelp::getErrorArrayByField('MESSENGER_CHANNEL') as $key=>$msg)
	{
		$this->addMessage($key, $msg);
	}
	$this->addOption('messenger_id');
  }

	/**
	* @see sfValidatorBase
	*/
	protected function doClean($value)
	{
		$messenger = $this->getOption('messenger_id');
		$channel = (string) $value;

		if ($this->_messengerValidation($channel,$messenger))
		{
		  throw new sfValidatorError($this, 'err_messenger_req', array('value' => $value));
		}
		
		return $channel;

	}

	private function _messengerValidation($channel, $messenger)
	{
	  if($messenger == 'e.g. raj1983, vicky1980 ' || $messenger == '')
	   return 0;
	   
	  //check for invalid id's
	  if($channel == '')
		return 1;
	  return 0;

	}

  protected function isEmpty($value)
  {
	  return in_array($value, array(null,array()), true);
	//return in_array($value, array(null,array()), true);
  }
}
