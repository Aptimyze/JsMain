<?php

/*
 * This class is used for email validation in registration and edit modules
 */

/**
 * jsValidatorEmail validates a string. It also converts the input value to a string.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Esha Jain<esha.jain@jeevansathi.com>
 * @version    SVN: $Id: jsValidatorNameOfUser.class.php 12641 2013-05-27 18:22:00Z
 */
 
 
class jsValidatorNameOfUser extends sfValidatorBase
{
	CONST NAME_REGEX = '/^[a-zA-Z\s\.\'\,]*$/';
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
        $this->addOption('nameOfUser');
        foreach(ErrorHelp::getErrorArrayByField('NAME_OF_USER') as $key=>$msg)
        {
                $this->addMessage($key, $msg);
        }
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {

     $nameOfUser=trim(strtolower($value));
        if(!$nameOfUser)
        {
		return true;
        }
          if(!preg_match(self::NAME_REGEX,$nameOfUser))
          {
                throw new sfValidatorError($this, 'err_invalid_name', array('value' => $value, 'err_invalid_name' => $this->getOption('err_invalid_name')));
                  return 1;
          }
    return trim($value);
  }
  
  public static function validateNameOfUser($name_of_user){
        $name_of_user = str_replace("."," ",$name_of_user);
        $name_of_user = preg_replace("/dr|ms|mr|miss/i", "",$name_of_user);
        $name_of_user = preg_replace("/\,|\'/i", "",$name_of_user);
        $name_of_user = trim(preg_replace("/\s+/i", " ",$name_of_user));
        if($name_of_user == "")return false;
            $match = preg_match("/^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i",$name_of_user);
        if(!$match)
            return false;
        else{
            $arr=  explode(" ", $name_of_user);
            if(count($arr)<2)return false;
            return true;
        }
      
      
  }
}
