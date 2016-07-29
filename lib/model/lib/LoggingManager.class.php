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
     * @param $isSymfony checks whether the error raised is from symfony or non-symfony code
     */
    public function logThis($enLogType,$Var=null,$isSymfony=true)
    {
        if($this->canLog($Var))
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
                        $this->logException($Var,$isSymfony);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param $exception
     */
    private function logException($exception,$isSymfony)
    {
        // $errorString = $exception->__toString();
        // $clientIp = FetchClientIP();
        // $szLogType = $this->getLogType(LoggingEnums::LOG_ERROR);
        // $errorString = "$szLogType [{$this->iUniqueID}:{$clientIp}]: ".$errorString;
        // $errVal = print_r(sfContext::getInstance()->getRequest()->getParameterHolder()->getAll(),true);
        // $szLogString = $errorString."\n[RequestParams] : ".$errVal;

        $logData = "";
        $clientIp = FetchClientIP();
        $channelName = MobileCommon::getFullChannelName();

        if ( $isSymfony )
        {
            $module_name = sfContext::getInstance()->getModuleName();
            $action_name = sfContext::getInstance()->getActionName();
        }
        else
        {
            $exceptionRaisedFrom = $exception->getFile();
            $exceptionLiesIn = $exception->getTrace()[0]['file'];

            // let us get module name of the file.

            $module_action = str_replace(JsConstants::$docRoot, "", $exceptionLiesIn);

            // explode it to get module name.

            $module_name = explode('/', $module_action)[1];
            $action_name = explode('/', $module_action)[2];
        }


        $logData = $logData."".$this->iUniqueID;
        $logData = $logData." ".$channelName;
        $logData = $logData." ".$clientIp;
        $logData = $logData." ".$module_name;
        $logData = $logData." ".$action_name;
        $logData = $logData." ".$this->getLogType(LoggingEnums::LOG_ERROR);

        $logData = $logData." ".$exception;

        $this->writeToFile($logData);
    }

    /**
     * @param $message
     */
    private function logInfo($message)
    {
        $clientIp = FetchClientIP();
        $szLogType = $this->getLogType(LoggingEnums::LOG_INFO);
        $szLogString = "$szLogType [{$this->iUniqueID}:{$clientIp}]: ".$message;

        $this->writeToFile($szLogString);
    }

    /**
     * @param $message
     */
    private function logDebug($message)
    {
        $ex = new Exception($message);
        $stackTrace = $ex->__toString();

        $clientIp = FetchClientIP();
        $szLogType = $this->getLogType(LoggingEnums::LOG_DEBUG);
        $szLogString = "$szLogType [{$this->iUniqueID}:{$clientIp}]: ".$stackTrace;
        $this->writeToFile($szLogString);
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
        $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH."-".$currDate.".log";
        if ($this->szLogPath && $this->canCreateDir($this->szLogPath)) {
            $this->createDirectory($this->szLogPath);
            $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH.$this->szLogPath."//log-".$currDate.".log";
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
     */
    private function canLog($Var=null)
    {
        // TODO: get module name
        $module = $this->szLogPath;

        // check if log for all is set, if not set then check if module can log
        return (LoggingEnums::LOG_ALL ? 1 : LoggingConfig::getInstance()->logStatus($module));
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