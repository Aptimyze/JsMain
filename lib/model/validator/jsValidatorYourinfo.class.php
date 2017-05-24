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
 * @version    SVN: $Id: jsValidatorEmail.class.php 12641 2013-05-27 18:22:00Z hemant $
 */
 
 
class jsValidatorYourinfo extends sfValidatorBase
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
	$this->addOption('min_length');		
	$this->addOption('yourinfo');		
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
     $value=trim(strtolower($value));
		$output=strstr($value,"you may consider answering these questions");
	if($output || !$value || strlen($value)<100)
	{
		
		throw new sfValidatorError($this,ErrorHelp::$ERR_REQUIRED[yourinfo], array('value' => $value));
	}
	return $value;
      //throw new sfValidatorError($this, 'err_email_del', array('value' => $value, 'err_email_del' => $this->getOption('err_email_del')));
  } 
}
