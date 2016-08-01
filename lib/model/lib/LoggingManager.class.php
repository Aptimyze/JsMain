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
    const LOG_FILE_BASE_PATH = '/uploads/Logger/';

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
     * Constructor function
     */
    private function __construct($basePath = null)
    {
      $this->szLogPath = $basePath;
      $this->iUniqueID = uniqid();
    }

    /*
        A function to retrieve uniqueId of the instance of LoggingManager

    */
    public function getUniqueId()
    {   
        return($this->iUniqueID);
    }
    /* A function to print the all info of requests

    */

    public function allInfo($functionname)
    { 
        // die("reached");
        $currDate = Date('Y-m-d');

     $reqId = sfContext::getInstance()->getRequest()->getAttribute('REQUEST_ID_FOR_TRACKING');
    // die($reqId);
     $szStringToWrite=$reqId . "  module_name  action_name  " . $functionname ." works fine at time \n";
     $this->writeToFile($szStringToWrite);
     
    /* 
      $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH.$this->szLogPath."//log-".$currDate.".log";
      $this->createDirectory($filePath);
     //die($filePath);
      $fileResource = fopen($filePath,"a");
        fwrite($fileResource,$szStringToWrite);
        fclose($fileResource);
        */
        //
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
      if($this->canLog($Var,$isSymfony,$logArray))
      {
        if($enLogType > LoggingEnums::LOG_LEVEL) {
         return ;
       }

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
     * @param $exception
     */
    private function logException($exception,$isSymfony,$logArray)
    {
      $logData = $this->getLogType(LoggingEnums::LOG_ERROR);
      $logData = $logData." : ".$this->getLogData($exception,$isSymfony,$logArray);
      $this->writeToFile($logData);
    }

    private function getLogData($exception,$isSymfony,$logArray)
    {
      $time = date('h:i:s');
      $logData = "";
      $logId = $this->getLogId($logArray);
      $clientIp = $this->getLogClientIP();
      $channelName = $this->getLogChannelName();

      // $moduleName = $this->getLogModuleName($isSymfony,$exception,$logArray);
      $moduleName = $this->szLogPath;
      $actionName = $this->getLogActionName($isSymfony,$exception,$logArray);
      
      $apiVersion = $this->getLogAPI($logArray);
      $message = $this->getLogMessage($logArray);

      $logData = $logData."".":";
      $logData = $logData.":".$logId;
      $logData = $logData." ".$time;
      $logData = $logData.":";
      $logData = $logData." ".$channelName;
      $logData = $logData." ".$clientIp;
      $logData = $logData." ".$statusCode;
      $logData = $logData." ".$moduleName;
      $logData = $logData." ".$actionName;
      $logData = $logData." ".$typeOfError;
      $logData = $logData." ".$message;
      $logData = $logData." ".$exception;
      return $logData;
    }

    /**
     * @return logId
     */
    public function getLogId($logArray)
    {
      if ( !isset($logArray['logId']))
      {
        $logId = $this->iUniqueID;
      }
      else
      {
        $logId = $logArray['logId'];
      }
      return $logId;
    }

    /**
     * @return apiVersion
     */
    public function getLogAPI($logArray)
    {
      if ( !isset($logArray['apiVersion']))
      {
        $apiVersion = "";
      }
      else
      {
        $apiVersion = $logArray['apiVersion'];
      }
      return $apiVersion;
    }

    /**
     * @return apiVersion
     */
    public function getLogError($logArray)
    {
      if ( !isset($logArray['typeOfError']))
      {
        $typeOfError = "";
      }
      else
      {
        $typeOfError = $logArray['typeOfError'];
      }
      return $typeOfError;
    }

    /**
     * @return message
     */
    public function getLogMessage($logArray)
    {
      if ( !isset($logArray['message']))
      {
        $message = "";
      }
      else
      {
        $message = $logArray['message'];
      }
      return $message;
    }

    /**
     * @return channel name
     */
    public function getLogChannelName()
    {
      return MobileCommon::getFullChannelName();
    }

    /**
     * @return ip
     */
    public function getLogClientIP()
    {
      return FetchClientIP();
    }


    /**
     * @return module name
     * @param isSymfony for exception raised from either symfony or non-symfony
     * @param $exception 
     */
    public function getLogModuleName($isSymfony = true,$exception = null,$logArray = array())
    {
      if ( !isset($logArray['moduleName']))
      {
        if ( $isSymfony )
        {
          $moduleName = sfContext::getInstance()->getModuleName();
        }
        else
        {
          $exceptionRaisedFrom = $exception->getFile();
          $exceptionLiesIn = $exception->getTrace()[0]['file'];
          $module_action = str_replace(JsConstants::$docRoot, "", $exceptionLiesIn);
          $moduleName = explode('/', $module_action)[1];
        }
      }
      else
      {
        $moduleName = $logArray['moduleName'];
      }
      return $moduleName;
    }

    /**
     * @return action name
     * @param isSymfony for exception raised from either symfony or non-symfony
     * @param $exception 
     */
    public function getLogActionName($isSymfony = true,$exception = null,$logArray = array())
    {
      if ( !isset($logArray['actionName']))
      {
        if ( $isSymfony )
        {
          return sfContext::getInstance()->getActionName();
        }
        else
        {
          $exceptionRaisedFrom = $exception->getFile();
          $exceptionLiesIn = $exception->getTrace()[0]['file'];
          $module_action = str_replace(JsConstants::$docRoot, "", $exceptionLiesIn);
          $action_name = explode('/', $module_action)[2];
          return $action_name;
        }
      }
      else
      {
        $actionName = $logArray['actionName'];
      }
      return $action_name;
    }

    /**
     * @param $message
     */
    private function logInfo($message,$isSymfony=true,$logArray = array())
    {
      $logData = $this->getLogType(LoggingEnums::LOG_INFO);
      $logData = $logData." : ".$this->getLogData($message,$isSymfony,$logArray);
      $this->writeToFile($logData);
    }

 /**
     * @param $message
     */
    private function logDebug($message,$isSymfony=true,$logArray = array())
    {
      $logData = $this->getLogType(LoggingEnums::LOG_DEBUG);
      $logData = $logData." : ".$this->getLogData($message,$isSymfony,$logArray);
      $this->writeToFile($logData);
    }

    /**
     * @param $szPath
     */
    private function createDirectory($szPath)
    {
      $dirPath = JsConstants::$docRoot.self::LOG_FILE_BASE_PATH.$szPath;
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
        $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH."log-".$currDate.".log";
        if($this->canCreateDir($this->szLogPath))
        {
          $this->createDirectory($this->szLogPath);
          $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH.$this->szLogPath."//log-".$currDate.".log";
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
    private function canLog($Var,$isSymfony,$logArray)
    {
        // TODO: get module name
        if($this->szLogPath == null)
        {
          $this->szLogPath = $this->getLogModuleName($isSymfony,$Var,$logArray);
        }
        // check if log for all is set, if not set then check if module can log
        return (LoggingEnums::LOG_ALL ? 1 : LoggingConfig::getInstance()->logStatus($this->szLogPath));
    }

    /**
     * @param $szPath
     */
    private function canCreateDir($szLogPath)
    {
      // check if log for all modules is together, if not set then check if module can create diff directory
      return (LoggingEnums::LOG_TOGETHER ? 0 : LoggingConfig::getInstance()->dirStatus($szLogPath));
    }
}