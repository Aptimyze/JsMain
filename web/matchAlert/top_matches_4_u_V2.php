<?php

/***************************************************************************************************************
* FILE NAME     : top_matches_4_u.php
* DESCRIPTION   : Tracks the number of times matchalert has been viewed and displays "matches_for_u.gif"
* CREATION DATE : 06 March, 2006
* CREATED BY    : Gaurav Arora
*****************************************************************************************************************/

include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$mysqlObj = new Mysql;
$db=$mysqlObj->connect("alerts");

$checksumArr = explode("i",$checksum);
if($checksumArr[0]==md5($checksumArr[1]))
{
	$today=mktime(0,0,0,date("m"),date("d"),date("Y")); //timestamp for today
	$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
	$gap=($today-$zero)/(24*60*60); //$gap is the no. of days since 1 Jan 2006. This value will be entered in VIEW_COUNT table

	$sql="INSERT INTO matchalerts.TOP_VIEW_COUNT VALUES('$checksumArr[1]','$gap','$logic_used')";
	$res=mysql_query($sql) or logError("Error while inserting data into alerts.TOP_VIEW_COUNT",$sql);
}

header('Content-type: image/gif');
$path = JsConstants::$imgUrl."/profile/images/match-alert_new/img1.gif";
readfile($path);

function logError($message,$query="")
{
        LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($message));
        global $db, $checksum;

        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        $errorstring.="\" >> ".JsConstants::$alertDocRoot."/matchAlert/logerror.txt";

        passthru($errorstring);
}
?>
