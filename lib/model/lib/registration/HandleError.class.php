<?php
/**
 * HandleError class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */

class HandleError
{
  /*
   * Memeber Variables declaration
   */

  /*
   * action to be performed if an error is detected 
   * @access Private
   * @var array
   */
  private $actions = array();
  /*
   * error string to be logged 
   * @access Private
   * @var string
   */
  private $error = null;
  /*
   * determines whether the error detected should be logged in table or file
   * @access Private
   * @var string
   */
  private $logIn = "FILE";
  /*
   * determines the name of the store class of table or exact path of the file to log the error
   * @access Private
   * @var string
   */
  private $logInName = "/tmp/commonError.txt";

  /*
   * Defining Member Function
   */

  /**
   * Constructor 
   * @access Public
   * @return Void
   * <p>
   * </p>
   */
  public function __construct($error,$errorDetailsKey,$errorEnum) 
  {
    $this->error = $errorEnum.":".$error;
    if(!is_array(RegistrationEnums::$errorLoggingDetails[$errorDetailsKey]))
	$errorDetailsKey = "COMMON";
    $this->logIn = RegistrationEnums::$errorLoggingDetails[$errorDetailsKey]['IN'];
    $this->logInName = RegistrationEnums::$errorLoggingDetails[$errorDetailsKey]['NAME'];
    $this->actions = RegistrationEnums::$errorLoggingDetails[$errorDetailsKey]['ACTION'];
  }
  /**
   * function performing the actions to be taken for the errors detected
   * @access Public
   * @return Void
   * <p>
   * </p>
   */

  public function takeAction()
  {
    $this->sort();
    foreach($this->actions as $k=>$action)
    {
      switch($action)
      {
        case "LOG":
		$this->logError();
          break;
        case "SHOW_404":
	sfContext::getInstance()->getController()->forward("seo","404");
   //       header("HTTP/1.0 404 Not Found");
          die;
        case "SHOW_500":
          header("HTTP/1.0 500 Internal Server Error");
          die;
        case "SITE_DOWN_URL":
	  $siteDownUrl = JsConstants::$siteUrl . "/site_down.htm";
          sfContext::getInstance()->getController()->redirect($siteDownUrl);
          die;
      }
    } 
  }
  /**
   * function to log error in either file or table
   * @access Public
   * @return Void
   * <p>
   * </p>
   */

  public function logError()
  {
	if($this->logIn==RegistrationEnums::$errorLogInList['FILE'])
	{
		file_put_contents($this->logInName,$this->error."\n",FILE_APPEND | LOCK_EX);
	}
	elseif($this->logIn==RegistrationEnums::$errorLogInList['TABLE'])
	{
		$storeObj = new $this->logInName();
		$storeObj->logError($this->error);
	}
  }
  /**
   * function to sort the actions array so s to die in last and log in forst turn
   * @access Private
   * @return Void
   * <p>
   * </p>
   */

  private function sort()
  {
    $ordered = array();
    foreach(RegistrationEnums::$errorActionOrder as $key=>$val) {
        if(in_array($val,$this->actions)) 
	{
          $ordered[] = $val;
        }
    }
    $this->actions = $ordered;
  }
}
