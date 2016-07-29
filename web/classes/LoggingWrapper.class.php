<?php 
/**
* Description of LoggingWrapper
* This is a wrapper library which will use logging manager
*/

// including for logging purpose
include_once(JsConstants::$cronDocRoot."/lib/model/enums/LoggingEnums.class.php");
include_once(JsConstants::$cronDocRoot."/lib/model/lib/LoggingManager.class.php");

class LoggingWrapper
{
	
	/**
     * @var Object
     */
    private static $instance = null;

	private function __construct() {}

	/**
     * Get Instance
     * @return Object of LoggingWrapper
     */
	public function getInstance()
	{

		if (null === self::$instance) {
            $className =  __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
	}

	 /**
     * @param $enLogType
     * @param $Var
     */
	public function sendLog($enLogType,$Var=null)
	{
		// get module name 
		$module = 'jsadmin';
		return LoggingManager::getInstance($module)->logThis($enLogType,$Var,false);
	}
}

?>
