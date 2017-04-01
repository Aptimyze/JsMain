<?php

/*
 * This class is used for alternate email validation in registration and edit modules
 */

/**
 * jsValidatorEmail validates a string. It also converts the input value to a string.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Sanyam Chopra
 */
 
 
class jsValidatorAlternateMail extends sfValidatorBase
{
	CONST GMAIL = 'gmail';
	CONST SIFY = 'sify';
	CONST REDIFF = 'rediffmail';
	CONST YAHOO1 = 'yahoo';
	CONST YAHOO2 = 'ymail';
	CONST YAHOO3 = 'rocketmail';
	CONST REGEX_EMAIL = '/^([a-zA-Z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
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
   foreach(ErrorHelp::getErrorArrayByField('EMAIL') as $key=>$msg)
   {
    if($key == 'err_email_del') //CHECK THIS
     $msg = "The profile with this email has been deleted. To retrieve profile, kindly contact bug@jeevansathi.com";
   $this->addOption("email");
    $this->addMessage($key, $msg);
   }
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
	  $email = (string) $value;
    $value = trim($email);
    $primaryEmail = trim($this->getOption('email'));
    
    if ($this->_emailValidation($value))
    {
      throw new sfValidatorError($this, 'err_email_req', array('value' => $value, 'err_email_req' => $this->getOption('err_email_req')));
    }
    if($this->_sameEmail($value,$primaryEmail))
    {
      throw new sfValidatorError($this, 'err_email_same', array('value' => $value, 'err_email_same' => $this->getOption('err_email_same')));
    }
    return $value;
  }
  
  private function _emailValidation($email)
  {
	  $first = $domain = strstr($email, '@',true);
	  $domain = strstr($email, '@');
	  $dotpos = strrpos($domain,".");
	  $domain = substr($domain,1,$dotpos-1);
	  $domain = strtolower($domain);
	  //check for invalid domains
	  $emailArr = InvalidEmails::getInvalidEmailArr();
	 
	  if(in_array(strtolower($domain),$emailArr)) 
	  {
		  return 1;
	  }
	 
	  if(!preg_match(jsValidatorMail::REGEX_EMAIL,$email))
	  {
		  return 1;
	  }
	  else if($domain == jsValidatorMail::GMAIL) // check for duplicate emails
	  {
		  return (strlen($first) >= 6 && strlen($first) <= 30) ? 0 : 1;
	  }
	  else if($domain == jsValidatorMail::REDIFF)
	  {
		  return (strlen($first) >= 4 && strlen($first) <= 30) ? 0 : 1;
	  }
	  else if($domain == jsValidatorMail::SIFY)
	  {
		  return (strlen($first) >= 3 && strlen($first) <= 16) ? 0 : 1;
	  }
	  else if($first == jsValidatorMail::YAHOO1 || $first == jsValidatorMail::YAHOO2 || $first == jsValidatorMail::YAHOO3)
	  {
		  return (strlen($first) >= 4 && strlen($first) <= 32) ? 0 : 1;
	  }
	  
	  
  }
  
  private function _sameEmail($altEmail,$primaryEmail)
  {
	 if($primaryEmail =="")
   {
    $loggedInObj = LoggedInProfile::getInstance();
    $primaryEmail = $loggedInObj->getEMAIL();
   }
    if(strtolower($altEmail) == strtolower($primaryEmail))
    {      
      return 1;
    }
    else
    {     
      return 0;
    } 
  }
}
