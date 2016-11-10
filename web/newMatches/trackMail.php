<?php

include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/newMatches/TrackingFunctions.class.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$mysqlObj = new Mysql;
$db=$mysqlObj->connect("alerts");

$checksumArr = explode("i",$checksum);
if($checksumArr[0]==md5($checksumArr[1]) && $logic_used && is_numeric($logic_used))
{
	if($sent_date && !is_numeric($sent_date))
		$sent_date = "";

	$gap = MailerConfigVariables::getLogicalDate();
	$sql="INSERT INTO new_matches_emails.TOP_VIEW_COUNT(PROFILEID,DATE,LOGIC,SENT_DATE) VALUES('$checksumArr[1]','$gap','$logic_used','".$sent_date."')";
	$res=mysql_query($sql) or logError("Error while inserting data into new_matches_emails.TOP_VIEW_COUNT",$sql);

	if($sent_date)
	{
		$actualDate = MailerConfigVariables::decodeLogicalDate($sent_date);
	}
	$trackObj = new TrackingFunctions("",$mysqlObj);
	$trackObj->trackingMis(array("MAIL_OPEN"=>1),$actualDate);
	unset($trackObj);
}

header('Content-type: image/gif');
$path = JsConstants::$imgUrl."/profile/images/jsmail/newMatches/logo.gif";
readfile($path);

function logError($message,$query="")
{
        global $db, $checksum;

        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        $errorstring.="\" >> ".JsConstants::$alertDocRoot."/newMatches/logerror.txt";

		LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorstring));
        passthru($errorstring);
}
?>
