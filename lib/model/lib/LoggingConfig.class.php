<?php 
	/**
	* 
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
			
			LoggingEnums::JsA => array('logging' => 0, 'level' => 0, 'directory' => 1),
			'example_module' => array('logging' => 0, 'level' => 0, 'directory' => 1)
			);

		function __construct()
		{

		}

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
			if(array_key_exists($module, $this->arrConfig))
				return $this->arrConfig[$module]['logging'];
			else
			{
				
			}
		}

		/**
		* @return directory status of module
		*/
		public function dirStatus($module)
		{
			if(array_key_exists($module, $this->arrConfig))
				return $this->arrConfig[$module]['directory'];
			else
			{
				
			}
		}
	}
?>