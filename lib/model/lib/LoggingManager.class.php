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
     * Const of File Base Path
     */
    const LOG_FILE_BASE_PATH = '/log/Logger/';

    /**
     * @var null
     */
    private $szLogPath = null;

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
     * @var bool
     */
    private $bDoItOnce = true;

    /**
     * @var json_object
     */
    private $logData = array();

    /**
     * Constructor function
     */
    private function __construct($basePath = null)
    {
      $this->szLogPath = $basePath;
      $this->iUniqueID = uniqid();
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
    private function __destruct() {
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
        self::$instance->szLogPath = $basePath;
        return self::$instance;
    }

     /**
     * log the data passed, the format is:
     * time logId clientChannel clientIp modulName actionName controllerName   
     * @param $enLogType
     * @param $Var contains exception details if exists, null otherwise.
     * @param $logArray - an associative array which contains
     *        moduleName (optional)
     *       ,actionName(optional),
     *       ,controllerName(optional)
     *       ,apiVersion(optional)
     *       ,statusCode
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
      $logData['logType'] = $this->getLogType(LoggingEnums::LOG_ERROR);

      if(LoggingConfig::getInstance()->debugStatus())
      {
         foreach ($_SERVER as $key => $value) {
          $logData[$key] = $value;
        }
      }
      $this->writeToFile(json_encode($logData));
    }

    /**
     * @param String $message The message passed into $message variable 
     * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
     * @param associative array $logArray
     */
    private function logInfo($message,$isSymfony=true,$logArray = array())
    {
      $logData = $this->getLogData($message,$isSymfony,$logArray);
      $logData['logType'] = $this->getLogType(LoggingEnums::LOG_INFO);
      $this->writeToFile(json_encode($logData));
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
      $logData['logType'] = $this->getLogType(LoggingEnums::LOG_DEBUG);
        // $logData = $logData." ".print_r($_SERVER, true);
        foreach ($_SERVER as $key => $value) {
          $logData[$key] = $value;
        }
      $this->writeToFile(json_encode($logData));
    }
    /**
     * @return logdata.
     * @param Exception $exception The exception raised by code 
     * @param boolean $isSymfony Whether code is called from symfony code or non-symfony code
     * @param associative array $logArray 
     */
    private function getLogData($exception,$isSymfony,$logArray)
    {
      $time = date('h:i:s a');

      $logId = $this->getLogId($logArray);
      $clientIp = $this->getLogClientIP();
      $channelName = $this->getLogChannelName();
      $moduleName = $this->szLogPath;
      $actionName = $this->getLogActionName($isSymfony,$exception,$logArray);
      $apiVersion = $this->getLogAPI($logArray);
      $message = $this->getLogMessage($exception,$logArray);
      $uniqueSubId = $this->getLogUniqueSubId($logArray);
      $statusCode = $this->getLogStatusCode($exception,$logArray);
      $typeOfError = $this->getLogTypeOfError($exception,$logArray);
      $headers = getallheaders();
      $logData = array();
      $logData['logId'] = $logId;
      $logData['clientIp'] = $clientIp;
      $logData['time'] = $time;
      if($uniqueSubId != "")
        $logData['uniqueSubId'] = $uniqueSubId;
      $logData['channelName'] = $channelName;
      $logData['apiVersion'] = $apiVersion;
      $logData['modulName'] = $modulName;
      $logData['actionName'] = $actionName;
      $logData['typeOfError'] = $typeOfError;
      $logData['statusCode'] = $statusCode;
      $logData['message'] = $message;
      if($this->canWriteTrace($this->szLogPath))
      {
        $logData['exception'] = $exception;
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
      if ( !isset($logArray[LoggingEnums::MODULE_NAME]))
      {
        if ( $isSymfony )
        {
          $moduleName =  sfContext::getInstance()->getModuleName();
        }
        else
        {
          $modulName = "";
          if ( $exception instanceof Exception)
          {
            $exceptionLiesIn = $exception->getTrace()[0]['file'];
            $module_action = str_replace(JsConstants::$docRoot, "", $exceptionLiesIn);
            $moduleName = explode('/', $module_action)[1];
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
      if ( !isset($logArray[LoggingEnums::ACTION_NAME]))
      {
        if ( $isSymfony )
        {
          $actionName = sfContext::getInstance()->getActionName();
        }
        else
        {
          $actionName = "";
          if ( $exception instanceof Exception)
          {
            $exceptionLiesIn = $exception->getTrace()[0]['file'];
            $module_action = str_replace(JsConstants::$docRoot, "", $exceptionLiesIn);
            $actionName = explode('/', $module_action)[2];
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
     * @param $szPath
     */
    private function createDirectory($szPath)
    {
      $dirPath = JsConstants::$cronDocRoot.self::LOG_FILE_BASE_PATH.$szPath;
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
        $filePath =  JsConstants::$cronDocRoot.self::LOG_FILE_BASE_PATH."log-".$currDate.".log";
        if($this->canCreateDir($this->szLogPath))
        {
          $this->createDirectory($this->szLogPath);
          $filePath =  JsConstants::$cronDocRoot.self::LOG_FILE_BASE_PATH.$this->szLogPath."//log-".$currDate.".log";
        }
        else
        {
          $this->createDirectory("");
        }
        //Add in log file
      if($this->bDoItOnce) {
        $szLogString = "\n".$szLogString;
        $this->bDoItOnce = false;
      }
      $fileResource = fopen($filePath,"a");
      fwrite($fileResource,$szLogString."\n");
      fclose($fileResource);
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
        // set module name
      if($this->szLogPath == null)
      {
        $this->szLogPath = $this->getLogModuleName($isSymfony,$Var,$logArray);
      }
        // check if config is on, if yes then check if module can log
      $toLog = (LoggingEnums::CONFIG_ON ? LoggingConfig::getInstance()->logStatus($this->szLogPath) : true);
        // check Log Level
      $checkLogLevel = ($enLogType <= LoggingEnums::LOG_LEVEL || $enLogType <= LoggingConfig::getInstance()->getLogLevel($this->szLogPath));
      return $toLog & $checkLogLevel;
    }

    /**
     * @param $szPath
     */
    private function canCreateDir($szLogPath)
    {
        // check if log for all modules is together, if not set then check if module can create diff directory
      return (LoggingEnums::LOG_TOGETHER ? 0 : LoggingConfig::getInstance()->dirStatus($szLogPath));
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
     * @param $szPath
     */
    private function canWriteTrace($szLogPath)
    {
        return LoggingConfig::getInstance()->traceStatus($szLogPath);
    }  
}
