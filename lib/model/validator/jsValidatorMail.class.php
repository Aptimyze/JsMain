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
 
 
class jsValidatorMail extends sfValidatorBase
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
		if($key == 'err_email_del')
			$msg = "The profile with this email has been deleted. To retrieve profile, kindly contact bug@jeevansathi.com";
		$this->addOption("altEmail");
		$this->addMessage($key, $msg);
	}
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $source = $_SERVER['HTTP_REFERER'];
    if(strpos($source,"viewprofile") !== $false)
    {
      $source = "EDIT";
    }
    elseif(strpos($source,"registration") !== $false)
    {
      $source = "REG";
    }
	   $email = (string) $value;
    $value = trim($email);
    $altEmail = trim($this->getOption('altEmail'));
    $activatedFlag = $this->_dupProfileEmail($value);
    
    if ($this->_emailValidation($value))
    {
      throw new sfValidatorError($this, 'err_email_req', array('value' => $value, 'err_email_req' => $this->getOption('err_email_req')));
    }
    
    if ($this->_emailOld($value,$activatedFlag))
    { 
	  $this->_trackDuplicateEmail($value,'Y');
      throw new sfValidatorError($this, 'err_email_duplicate', array('value' => $value, 'err_email_duplicate' => $this->getOption('err_email_duplicate')));
    }
    
    if ($this->_emailDeleted($value,$activatedFlag))
    {
	  $this->_trackDuplicateEmail($value,'Y',1);
          //modify old email
          $deletedEmailModify = new RegistrationFunctions();
          $affectedRows = $deletedEmailModify->deletedEmailModify($value);
          if($affectedRows == 0)
              throw new sfValidatorError($this, 'err_email_del', array('value' => $value, 'err_email_del' => $this->getOption('err_email_del')));
    }
    if($this->_sameEmail($value,$altEmail))
    {
      throw new sfValidatorError($this, 'err_email_same', array('value' => $value, 'err_email_same' => $this->getOption('err_email_same')));
    }
	$this->_trackDuplicateEmail($value,'N');
    $negativeProfileListObj = new incentive_NEGATIVE_LIST;
    $negativeEmail = $negativeProfileListObj->checkEmailOrPhone("EMAIL",$value);
    if($negativeEmail)
    {
      file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/loggingIgnoredEmailAndPhone.txt",$value."\t".date("Y-m-d H:i:s")."\t".$source."\n",FILE_APPEND);
	    throw new sfValidatorError($this, 'err_email_revoke', array('value' => $value));
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
  
  private function _dupProfileEmail($email)
  {
	$profileObj = new JPROFILE();
	$dupEmailAct = $profileObj->duplicateEmail($email);
	return $dupEmailAct;
  }
  
  private function _emailDeleted($email,$activated)
  {
	  if($activated === 'D')
	  {
		return 1;
	  }
	  return 0;
  }
  
  private function _emailOld($email,$activated)
  {
	  $oldEmailObj = new newjs_OLDEMAIL();
	  $dupOldEmailFlag = $oldEmailObj->duplicateOldEmail($email);
	   
	  // check in old email table or in jprofile table with ACTIVATED NOT AS 'D'
	  
	  if($dupOldEmailFlag || ($activated != 'D' && $activated != -1))
	  {
		 return 1;
	  }
	  return 0;
  }
  
  private function _trackDuplicateEmail($email,$flag,$emailDeletedProfile='')
  {
          //track deleted email reusage
          if($emailDeletedProfile){
            $duplicateEmailTrack = new TrackDuplicateEmailUsage();
            $duplicateEmailTrack->insertEmailEntry($email);
          }
            $dupObj = new MIS_TRACK_DUPLICATE_EMAIL();
            $ip = CommonFunction::getIP();
            $page = JsRegistrationCommon::getSourcePage();
            $dupObj->insert($email, $page, $ip, $flag); 
  }

  private function _sameEmail($email,$altEmail)
  {   
    if(strtolower($altEmail) == strtolower($email)) 
    {
      return 1;
    }
    else
    {
      return 0;
    }
  } 
}
