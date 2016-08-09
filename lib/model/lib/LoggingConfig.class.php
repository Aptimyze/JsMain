<?php 
	/**
	* Description of LoggingConfig
	* Library Class to handle configurations for Logging
	*/
	class LoggingConfig
	{
		/**
		* @var Object
		*/
		private static $instance = null;

		/**
		*	@var array
		*	'module name' => its configs
		*/
		private $arrConfig = array(
			// 'logging' => 1, logging is on for this module
			LoggingEnums::JSADMIN => array(
				LoggingEnums::logging => true,
				LoggingEnums::level => LoggingEnums::LOG_DEBUG,
				LoggingEnums::directory => true,
				LoggingEnums::stackTrace => false
				),
			LoggingEnums::EX500OR404 => array(
				LoggingEnums::logging => true,
				LoggingEnums::level => LoggingEnums::LOG_ERROR,
				LoggingEnums::directory => true,
				LoggingEnums::stackTrace => false
				),
			);

		/**
     	* Constructor function
     	*/
		private function __construct() {}

		/**
		* __destruct
		*/
		private function __destruct() 
		{
			self::$instance = null;
		}

		/**
		* To Stop clone of this class object
		*/
		private function __clone() {}

		/**
		* To stop unserialize for this class object
		*/
		private function __wakeup() {}

		/**
		* Get Instance
		* @return Object of LoggingConfig
		*/
		public static function getInstance()
		{
		    if (null === self::$instance) {
		        $className =  __CLASS__;
		        self::$instance = new $className;
		    }
		    return self::$instance;
		}

		/**
		* @return log status of module
		*/
		public function logStatus($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				// module not in config
				return true;
			}
			return $this->arrConfig[$module][LoggingEnums::logging];
		}

		/**
		* @return directory status of module
		*/
		public function dirStatus($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				return false;
			}
			return LoggingEnums::CONFIG_ON ? $this->arrConfig[$module][LoggingEnums::directory] : false;
		}

		/**
		* @return stack trace status of module
		*/
		public function traceStatus($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				return LoggingEnums::LOG_TRACE;
			}
			return LoggingEnums::CONFIG_ON ? $this->arrConfig[$module][LoggingEnums::stackTrace] : LoggingEnums::LOG_TRACE;
		}

		/**
		* @return get log level defined for the module
		*/
		public function getLogLevel($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				// 	By Default for Exception
				return LoggingEnums::LOG_ERROR;
			}
			return LoggingEnums::CONFIG_ON ? $this->arrConfig[$module][LoggingEnums::level] : LoggingEnums::LOG_ERROR;
		}
	}
?>