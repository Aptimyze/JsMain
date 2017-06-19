<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$matchalertServer = 1;

function logerror1($message,$query="", $sendmailto="NO",$flag="N",$db="")
{
        LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($message));
        global $db, $smarty, $checksum,$path;
	global $smarty;
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();
	if(!$path)
		$path=JsConstants::$alertDocRoot;

        if($db)
                $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error($db)) ."\nMysql Error Number:". mysql_errno($db)."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        else
                $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
echo $errorstring;
        passthru($errorstring);
        $errorstringtemp.="\" >> ".$path."/new_matchalert/logerror_temp.txt";
        passthru($errorstringtemp);
        $errorstring.="\n#Details : $ret_val";
        if($sendmailto=="NO")
        if($flag=="Y")
                exit;
}
?>
