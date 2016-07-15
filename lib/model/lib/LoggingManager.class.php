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
    const LOG_FILE_BASE_PATH = '/uploads/Logger/log';

    /**
     * @var null
     */
    private $szLogPath = null;

    /**
     * @var null|string
     */
    private $iUniqueID = null;

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
     * @param $enLogType
     * @param $Var
     */
    public function logThis($enLogType,$Var=null)
    {
        if($enLogType > LoggingEnums::LOG_LEVEL) {
           return ;
        }

        switch ($enLogType) {
            case LoggingEnums::LOG_INFO:
                    $this->logInfo($Var);
                break;
            case LoggingEnums::LOG_DEBUG:
                    $this->logDebug($Var);
                break;
            case LoggingEnums::LOG_ERROR:
                    $this->logException($Var);
                break;
            default:
                break;
        }
    }

    /**
     * @param $exception
     */
    private function logException($exception)
    {
        $errorString = $exception->__toString();
        $clientIp = FetchClientIP();
        $szLogType = $this->getLogType(LoggingEnums::LOG_ERROR);
        $errorString = "$szLogType [{$this->iUniqueID}:{$clientIp}]: ".$errorString;
        $errVal = print_r(sfContext::getInstance()->getRequest()->getParameterHolder()->getAll(),true);
        $szLogString = $errorString."\n[RequestParams] : ".$errVal;

        $this->writeToFile($szLogType, $szLogString);
    }

    /**
     * @param $message
     */
    private function logInfo($message)
    {
        $clientIp = FetchClientIP();
        $szLogType = $this->getLogType(LoggingEnums::LOG_INFO);
        $szLogString = "$szLogType [{$this->iUniqueID}:{$clientIp}]: ".$message;

        $this->writeToFile($szLogType, $szLogString);
    }

    /**
     * @param $message
     */
    private function logDebug($message)
    {
        $ex = new Exception($message);
        $stackTrace = $ex->__toString();

        $clientIp = FetchClientIP();
        $szLogType = $this->getLogType(LoggingEnums::LOG_INFO);
        $szLogString = "$szLogType [{$this->iUniqueID}:{$clientIp}]: ".$stackTrace;

        $this->writeToFile($this->getLogType(LoggingEnums::LOG_ERROR), $szLogString);
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
     * @param $szLogType
     * @param $szLogString
     */
    private function writeToFile($szLogType, $szLogString)
    {
        $currDate = Date('Y-m-d');
        $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH."{$szLogType}-".$currDate.".log";
        if ($this->szLogPath) {
            $this->createDirectory($this->szLogPath);
            $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH.$this->szLogPath."//{$szLogType}log-".$currDate.".log";
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

}