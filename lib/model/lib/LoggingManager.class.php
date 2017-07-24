<?php
/**
 * Description of LoggingManager
 * Library Class to handle Loggings
 *
 * @package     jeevansathi
 * @author      Kunal Verma
 * @created     12th July 2016
 */

class LoggingManager
{

	/**
	 * @var Object
	 */
	private static $instance = null;
	
	/**
	 * @var boolean
	 */
	private $logged = false;

	private $baseLogPath = null;
	/**
	 *  Directory Path of Log file
	 * @var null|String
	 */
	private $logDirPath = null;

	/**
	 * @var null
	 */
	private $szLogPath = null;

	/**
	 * @var bool
	 */
	private $flexDir = false;

	/**
	 * @var null|string
	 */
	private $iUniqueID = null;

	/**
	 * @var null|string
	 */
	private $clientIp = null; 

	/**
	 * @var null|string
	 */
	private $channelName = null;

	/**
	 * @var null|string
	 */
	private $moduleName = null;

	/**
	 * @var json_object
	 */
	private $logData = array();
	/**
	 *  Path to Server Location for storing logs
	 */
	private $serverLogPath = '/data/applogs';

	/**
	 * Constructor function
	 */
	private function __construct($basePath = null)
	{
		$this->szLogPath = $basePath;
		$this->iUniqueID = uniqid();
		$this->baseLogPath = JsConstants::$cronDocRoot.'/log';
		if(true === is_dir($this->serverLogPath)){
			$this->baseLogPath = $this->serverLogPath;
	   }
	   $this->logDirPath = '/Logger/'.Date('Y-m-d').'/';
	}
	/**
	 * A function to retrieve uniqueId of the instance of LoggingManager
	 * @return iUniqueID
	 */
	public function getUniqueId()
	{   
		return($this->iUniqueID);
	}


	/**
	 * __destruct
	 */
	public function __destruct() {
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
	 * @param $basepath 
	 * @return Object of ProfileCacheLib
	 */
	public static function getInstance($basePath = null)
	{
		if (null === self::$instance) {
			$className =  __CLASS__;
			self::$instance = new $className;
		}
		if ($basePath == null)
		{
			self::$instance->flexDir = false;
		}
		else
		{
			self::$instance->flexDir = true;
		}
		self::$instance->szLogPath = $basePath;
		return self::$instance;
	}

	public function getLogged()
	{
		return $this->logged;
	}

	public function setLogged()
	{
		$this->logged = true;
	}

	 /**
	 * log the data passed, the format is:
	 * time logId clientChannel clientIp modulName actionName controllerName   
	 * @param $enLogType
	 * @param $Var contains exception details if exists, null otherwise.
	 * @param $logArray - an associative array which contains
	 *        moduleName (optional)
	 *       ,actionName(optional),
	 *       ,apiVersion(optional)
	 *       ,statusCode
	 *       ,MESSAGE
	 *       ,typeOfError(whether php error, or mysql etc.) 
	 */
	 public function logThis($enLogType,$Var,$logArray = array(),$isSymfony=true)
	 {
		if($this->canLog($enLogType,$Var,$isSymfony,$logArray))
		{
			switch ($enLogType) {
				case LoggingEnums::LOG_INFO:
				$this->logInfo($Var,$isSymfony,$logArray);
				break;
				case LoggingEnums::LOG_DEBUG:
				$this->logDebug($Var,$isSymfony,$logArray);
				break;
				case LoggingEnums::LOG_ERROR:
				$this->logException($Var,$isSymfony,$logArray);
				break;
				default:
				break;
			}
		}
	 }

	/**
	 * Write Exception logs to the file.
	 * @param Exception $exception The exception raised by code 
	 * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
	 * @param associative array $logArray 
	 */
	private function logException($exception,$isSymfony,$logArray)
	{

		$logData = $this->getLogData($exception,$isSymfony,$logArray);
		if($logData)
		{
			$logData[LoggingEnums::LOG_TYPE] = $this->getLogType(LoggingEnums::LOG_ERROR);
			if(LoggingConfig::getInstance()->debugStatus($this->moduleName) && LoggingConfig::getInstance()->serverParamStatus($this->moduleName))
			{
				foreach ($_SERVER as $key => $value) {
					$logData[$key] = $value;
				}
			}
			$this->writeToFile(json_encode($logData));
		}
	}

	/**
	 * @param String $message The message passed into $message variable 
	 * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
	 * @param associative array $logArray
	 */
	private function logInfo($message,$isSymfony=true,$logArray = array())
	{
		$logData = $this->getLogData($message,$isSymfony,$logArray);
		if($logData)
		{
			$logData['logType'] = $this->getLogType(LoggingEnums::LOG_INFO);
			$this->writeToFile(json_encode($logData));
		}
	}

	/**
	 * @param $message
	 * @param String $message The message passed into $message variable 
	 * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
	 * @param associative array $logArray
	 */
	private function logDebug($message,$isSymfony=true,$logArray = array())
	{

		$logData = $this->getLogData($message,$isSymfony,$logArray);
		if($logData)
		{
			$logData['logType'] = $this->getLogType(LoggingEnums::LOG_DEBUG);
			if(LoggingConfig::getInstance()->serverParamStatus($this->moduleName))
			{
				foreach ($_SERVER as $key => $value) {
					$logData[$key] = $value;
				}
			}
			$this->writeToFile(json_encode($logData));
		}
	}
	/**
	 * @return logdata.
	 * @param Exception $exception The exception raised by code 
	 * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
	 * @param associative array $logArray 
	 */
	private function getLogData($exception,$isSymfony,$logArray)
	{

		$time = time();
		$logId = $this->getLogId($logArray);
		$clientIp = $this->getLogClientIP();
		$channelName = $this->getLogChannelName();
		$moduleName = $this->getLogModuleName($isSymfony,$exception,$logArray);
		$actionName = $this->getLogActionName($isSymfony,$exception,$logArray);
		$apiVersion = $this->getLogAPI($logArray);
		$message = $this->getLogMessage($exception,$logArray);
		$uniqueSubId = $this->getLogUniqueSubId($logArray);
		$statusCode = $this->getLogStatusCode($exception,$logArray);
		$typeOfError = $this->getLogTypeOfError($exception,$logArray);
		$mappingName = $this->getlogMappingName($moduleName);
		$scriptName = $this->getlogScriptName();
		//$headers = getallheaders();
		$logData = array();

		if ( $time != "")
		{
			$logData[LoggingEnums::TIME] = $time;
		}

		if ($logId != "") 
		{
			$logData[LoggingEnums::LOG_ID] = $logId;
		}
		if ($clientIp != "")
		{
			$logData[LoggingEnums::CLIENT_IP] = $clientIp;
		}
		if ( $uniqueSubId != "")
		{
			$logData[LoggingEnums::UNIQUE_REQUEST_SUB_ID] = $uniqueSubId;
		} 

		if ( $channelName != "")
		{
			$logData[LoggingEnums::CHANNEL_NAME] = $channelName;
		} 

		if ( $apiVersion != "")
		{
			$logData[LoggingEnums::API_VERSION] = $apiVersion;
		} 

		if ( $moduleName != "")
		{
			$logData[LoggingEnums::MODULE_NAME] = $moduleName;
		} 

		if ( $actionName != "")
		{
			$logData[LoggingEnums::ACTION_NAME] = $actionName;
		} 

		if ( $typeOfError != "")
		{
			$logData[LoggingEnums::TYPE_OF_ERROR] = $typeOfError;
		} 

		if ( $statusCode != "")
		{
			$logData[LoggingEnums::STATUS_CODE] = strval($statusCode);
		} 

		$logData[LoggingEnums::MESSAGE] = $message;

		if($mappingName != "")
		{
			$logData[LoggingEnums::MAPPING] = $mappingName;
		}

		if($scriptName != "")
		{
			$logData[LoggingEnums::SCRIPT] = $scriptName;
		}

		if($this->canWriteTrace($this->moduleName))
		{
			if ( $exception instanceof Exception)
			{
				$logData[LoggingEnums::LOG_EXCEPTION] = $exception->getTrace();
			}
		}
		$logData[LoggingEnums::REQUEST_URI] = $_SERVER['REQUEST_URI'];
		$logData[LoggingEnums::DOMAIN] = $_SERVER['HTTP_HOST'];
		if(isset($logArray[LoggingEnums::REFERER]))
		{
			foreach (LoggingEnums::$Referer_ignore as $key => $value) {
				if(strpos($logArray[LoggingEnums::REFERER], $value) !== false) {
				    return false;
				}
			}

			$logData[LoggingEnums::REFERER] = $logArray[LoggingEnums::REFERER];
		}
		else
		{
			$logData[LoggingEnums::REFERER] = $_SERVER['HTTP_REFERER'];
		}

		if($exception instanceof Exception)
		{
			$logData[LoggingEnums::TRACE_STRING] = $exception->getTraceAsString();
		}

		if(isset($logArray[LoggingEnums::CONSUMER_NAME]))
		{
			$logData[LoggingEnums::CONSUMER_NAME] = $logArray[LoggingEnums::CONSUMER_NAME];
		}

		if(isset($logArray[LoggingEnums::PHISHING_URL]))
		{
			$logData[LoggingEnums::PHISHING_URL] = $logArray[LoggingEnums::PHISHING_URL];	
		}

		if(isset($logArray[LoggingEnums::DEVICEID]))
		{
			$logData[LoggingEnums::DEVICEID] = $logArray[LoggingEnums::DEVICEID];
		}

		if(isset($logArray[LoggingEnums::DETAILS]))
		{
			$logData[LoggingEnums::DETAILS] = $logArray[LoggingEnums::DETAILS];
		}

		return $logData;
	}

	/**
	 * @return logId
	 * @param associative array logArray
	 */
	private function getLogUniqueSubId($logArray)
	{
		if ( !isset($logArray[LoggingEnums::AJXRSI]))
		{ 
			$uniqueSubId = sfContext::getInstance()->getRequest()->getAttribute(LoggingEnums::AJXRSI);
			
		}
		else
		{ 
			$uniqueSubId = $logArray[LoggingEnums::AJXRSI];
		}
		return $uniqueSubId;
	}

	/**
	 * @return status code
	 * @param Exception $exception The exception raised by code 
	 * @param associative array $logArray 
	 */
	private function getLogStatusCode($exception,$logArray)
	{
		if ( !isset($logArray[LoggingEnums::STATUS_CODE]))
		{
			$statusCode = "";
			if ( $exception instanceof Exception)
			{
				$statusCode = $exception->getCode();
			}  
		}
		else
		{
			$statusCode = $logArray[LoggingEnums::STATUS_CODE];
		}
		return $statusCode;
	}

	/**
	 * @return UniqueSubId
	 * @param associative array $logArray 
	 */
	private function getLogId($logArray)
	{
		$logId = $this->iUniqueID;
		if ( isset($logArray[LoggingEnums::LOG_ID]))
		{
			$logId = $logArray[LoggingEnums::LOG_ID];
		}
		return $logId;
	}

	/**
	 * @param associative array $logArray 
	 * @return apiVersion
	 */
	private function getLogAPI($logArray)
	{
		if ( !isset($logArray[LoggingEnums::API_VERSION]))
		{
			$apiVersion =  sfContext::getInstance()->getRequest()->getParameter("version");
		}
		else
		{
			$apiVersion = $logArray[LoggingEnums::API_VERSION];
		}
		return $apiVersion;
	}

	/**
	 * @param Exception $exception The exception raised by code 
	 * @param associative array $logArray 
	 * @return typeOfError
	 */
	private function getLogTypeOfError($exception,$logArray)
	{
		if ( !isset($logArray['typeOfError']))
		{
			if ( $exception instanceof PDOException)
				return LoggingEnums::PDO_EXCEPTION;
			else if ( $exception instanceof AMQPException)
				return LoggingEnums::AMQP_EXCEPTION;
			else if ( $exception instanceof PredisException)
				return LoggingEnums::REDIS_EXCEPTION;
			else if ( $exception instanceof Exception)
				return LoggingEnums::EXCEPTION;
			else
				return "";
		}
		else
		{
			$typeOfError = $logArray[LoggingEnums::TYPE_OF_ERROR];
		}
		return $typeOfError;
	}


	/**
	 * @return message
	 * @param associative array $logArray 
	 */
	private function getLogMessage($exception,$logArray)
	{
		$message = "";
		if ( $exception instanceof Exception)
		{
			$message = $exception->getMessage();
		}
		else
		{
			$message = $exception; 
		}
		if ( isset($logArray[LoggingEnums::MESSAGE]))
		{
			$message = $message." ".$logArray[LoggingEnums::MESSAGE];
		}
		return $message;
	}

	/**
	 * @return channel name
	 */
	private function getLogChannelName()
	{
		return MobileCommon::getFullChannelName();
	}

	/**
	 * @return ip
	 */
	private function getLogClientIP()
	{
		return FetchClientIP();
	}


	/**
	 * @return module name
	 * @param Exception $exception The exception raised by code 
	 * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
	 * @param associative array $logArray
	 */
	private function getLogModuleName($isSymfony = true,$exception = null,$logArray = array())
	{
		$moduleName = "";
		if (!isset($logArray[LoggingEnums::MODULE_NAME]))
		{
			$request = sfContext::getInstance()->getRequest();
			$moduleName = $request->getParameter("module");
			if(isset($moduleName))
			{
				if($moduleName == "api")
				{
					$apiWebHandler = ApiRequestHandler::getInstance($request);
					$details = $apiWebHandler->getModuleAndActionName($request);
					$moduleName = $details['moduleName'].'_'.$moduleName;
				} 
				elseif($moduleName == "e")
				{
					$moduleName = "AutoLogin";
				}
			}
			else
			{
				// In case when we don't get module name from Symfony
				if($exception instanceof Exception)
				{
					$exceptionLiesIn = $exception->getTrace()[0]['file'];
					$arrExplodedPath = explode('/', $exceptionLiesIn);
					$moduleName = $arrExplodedPath[count($arrExplodedPath)-2];
				}
			}	
		}
		else
		{
			$moduleName = $logArray[LoggingEnums::MODULE_NAME];
		}
		return $moduleName;
	}

	/**
	 * @return action name
	 * @param Exception $exception The exception raised by code 
	 * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
	 * @param associative array $logArray 
	 */
	private function getLogActionName($isSymfony = true,$exception = null,$logArray = array())
	{
		$actionName = "";
		if ( !isset($logArray[LoggingEnums::ACTION_NAME]))
		{
			$request = sfContext::getInstance()->getRequest();
			$actionName = $request->getParameter("action");
			if(isset($actionName))
			{
				if($actionName == "apiRequest")
				{
					$apiWebHandler = ApiRequestHandler::getInstance($request);
					$details = $apiWebHandler->getModuleAndActionName($request);
					$actionName = $details['actionName'];
				}
			}
			else
			{
				// In case when we don't get action name from Symfony
				if($exception instanceof Exception)
				{
					$exceptionLiesIn = $exception->getTrace()[0]['file'];
					$arrExplodedPath = explode('/', $exceptionLiesIn);
					$actionName = $arrExplodedPath[count($arrExplodedPath)-1];
				}
			}
		}
		else
		{
			$actionName = $logArray[LoggingEnums::ACTION_NAME];
		}
		return $actionName;
	}

	/**
	 * @param $szLogPath
	 */
	private function createDirectory($szLogPath)
	{
		$dirPath = $this->baseLogPath.$this->logDirPath.$szLogPath;
		if (false === is_dir($dirPath)) {
			mkdir($dirPath,0777,true);
		}
	}

	/**
	 * @param $szLogString
	 */
	private function writeToFile($szLogString)
	{
		$currDate = Date('Y-m-d');
		$filePath =  $this->baseLogPath.$this->logDirPath."log-".$currDate.".log";
		if($this->canCreateDir($this->moduleName))
		{
			$this->createDirectory($this->szLogPath);
			$filePath =  $this->baseLogPath.$this->logDirPath.$this->szLogPath."//log-".$currDate.".log";
		}
		else
		{
			$this->createDirectory("");
		}
		$fileResource = fopen($filePath,"a");
		fwrite($fileResource,$szLogString."\n");
		fclose($fileResource);
                if(json_decode($szLogString, true)[LoggingEnums::LOG_TYPE] == 'Error')
                {
                        $this->setLogged();
                }

	}

	/**
	 * @param $enLogType
	 * @return string
	 */
	private function getLogType($enLogType)
	{
		switch ($enLogType) {
			case LoggingEnums::LOG_INFO:
			$szLogType = 'Info';
			break;
			case LoggingEnums::LOG_DEBUG:
			$szLogType = 'Debug';
			break;
			case LoggingEnums::LOG_ERROR:
			$szLogType = 'Error';
			break;
			default:
			$szLogType = 'Log';
			break;
		}
		return $szLogType;
	}

	/**
	 * @param $Var
	 * @return bool
	 */
	private function canLog($enLogType,$Var,$isSymfony,$logArray)
	{
		// A request should be logged only once.
		if($enLogType == LoggingEnums::LOG_ERROR && $this->getLogged())
		{
			return false;
		}
		// set module name
		$this->moduleName = $this->getLogModuleName($isSymfony,$Var,$logArray);
		// check Log Level
		$checkLogLevel = ($enLogType <= LoggingEnums::LOG_LEVEL || $enLogType <= LoggingConfig::getInstance()->getLogLevel($this->moduleName) || ($this->szLogPath != null));

		if($this->szLogPath == null)
		{
			$this->szLogPath = $this->moduleName;
		}
		// check if config is on, if yes then check if module can log
		$toLog = (LoggingEnums::CONFIG_ON ? LoggingConfig::getInstance()->logStatus($this->moduleName) : true);
		return $toLog && $checkLogLevel && LoggingEnums::MASTER_FLAG;
	}

	/**
	 * @param $moduleName
	 */
	private function canCreateDir($moduleName)
	{
	  // check if log for all modules is together, if not set then check if module can create diff directory
		if($this->flexDir)
		{
			$this->flexDir = false;
			return true;
		}
		return (LoggingEnums::LOG_TOGETHER ? 0 : LoggingConfig::getInstance()->dirStatus($moduleName));
	}


	/**
	 *sets unique id
	 * @param $uniqueID
	 */

	public function setUniqueId($uniqueID)
	{
		$this->iUniqueID = $uniqueID;
	}

	/**
	 * @param $moduleName
	 */
	private function canWriteTrace($moduleName)
	{
		return LoggingConfig::getInstance()->traceStatus($moduleName);
	}

	/**
	 * @param $moduleName
	 * @return Mapping name of a module
	 */
	private function getlogMappingName($moduleName)
	{
		if(in_array($moduleName, array_keys(LoggingEnums::$ModuleMapping)))
		{
			$mappingName = LoggingEnums::$MappingNames[ LoggingEnums::$ModuleMapping[$moduleName] ];
		}
		else if(strpos($moduleName, '404') !== false)
		{
			$mappingName = LoggingEnums::$MappingNames[20];
		}
		else
		{
			$mappingName = LoggingEnums::$MappingNames[21];
		}
		return $mappingName;
	}

	// Get script name for failed cli scripts like RabbitMQ consumer crons.
	private function getlogScriptName()
	{
		$scriptName = '';
		if(php_sapi_name() === 'cli')
		{
			$scriptName = json_encode($_SERVER['argv']) . $_SERVER['SCRIPT_FILENAME'];
		}
		return $scriptName;
	}

        public function writeToFileForCoolMetric($body)
	{

                if(!LoggingEnums::$COOL_METRIC[$body['type']])return;
                $dataOutput = array();
                $dataOutput['Date'] = $body['currentTime'];
                $dataOutput['logType'] = $body['type'];
                $dataOutput['channel'] = $body['whichChannel'];
                $dataOutput['profileId'] = $body['profileId'];
                $dataOutput = json_encode($dataOutput);
                $currDate = Date('Y-m-d');
                try{
		$filePath =  $this->serverLogPath."/coolMetric/$currDate/".$currDate."_".$body['type'].".log";
                if(!file_exists(dirname($filePath)))
                    mkdir(dirname($filePath), 0777, true);                
		$fileResource = fopen($filePath,"a");
		fwrite($fileResource,$dataOutput."\n");
		fclose($fileResource);
                }
                catch(Exception $e){
                    
                    return;
                }
	}

        
        
        
        
}
