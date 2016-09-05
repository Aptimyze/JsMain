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
	CONST NAME_REGEX = '/^[a-zA-Z\s\.\']*$/';
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
                throw new sfValidatorError($this,ErrorHelp::$ERR_STRING[nameOfUser], array('value' => $value));
                  return 1;
          }
    return $nameOfUser;
  }
}
