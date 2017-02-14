<?php

/**
 * Description of jsCacheWrapperException
 * Generic exception class for mongodb
 * 
 * @author Kunal Verma
 * @created 22nd June 2016
 */

class jsCacheWrapperException extends RuntimeException
{
  
  /**
   * Const of File Base Path
   */
  const LOG_FILE_BASE_PATH = '//uploads//SearchLogs//cacheWrapperExceptionLogs';
  
  /**
   * 
   * @param type $ex
   */
  public static function logThis($ex)
  {
    $errorString = $ex->__toString();
    $clientIp = FetchClientIP();
    $errorString = "Error [".$clientIp."]: ".$errorString;
    $errVal = print_r(sfContext::getInstance()->getRequest()->getParameterHolder()->getAll(),true);	
		$errorString = $errorString."\n[RequestParams] : ".$errVal;
    
    $currDate = Date('Y-m-d');
    $filePath =  JsConstants::$docRoot.self::LOG_FILE_BASE_PATH.'-'.$currDate.".log";
    //Add in log file
    $fileResource = fopen($filePath,"a");
    fwrite($fileResource,$errorString);
    fclose($fileResource);
    
  }
  
 
}
