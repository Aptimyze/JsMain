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
			LoggingEnums::JSA => array(
				'logging' => 1,
				'level' => LoggingEnums::LOG_DEBUG,
				'directory' => 1,
				'stack_trace' => 1
				),
			LoggingEnums::EX500OR404 => array(
				'logging' => 1,
				'level' => LoggingEnums::LOG_ERROR,
				'directory' => 1,
				'stack_trace' => 0
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
				return 1;
			}
			return $this->arrConfig[$module]['logging'];
		}

		/**
		* @return directory status of module
		*/
		public function dirStatus($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				return 0;
			}
			return $this->arrConfig[$module]['directory'];
		}

		/**
		* @return stack trace status of module
		*/
		public function traceStatus($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				return 1;
			}
			return $this->arrConfig[$module]['stack_trace'];
		}

		/**
		* @return get log level defined for the module
		*/
		public function getLogLevel($module)
		{
			if(!array_key_exists($module, $this->arrConfig)){
				return 0;
			}
			return $this->arrConfig[$module]['level'];
		}
	}
?>