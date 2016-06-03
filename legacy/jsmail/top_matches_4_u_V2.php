<?php

/***************************************************************************************************************
* FILE NAME     : top_matches_4_u.php
* DESCRIPTION   : Tracks the number of times matchalert has been viewed and displays "matches_for_u.gif"
* CREATION DATE : 06 March, 2006
* CREATED BY    : Gaurav Arora
*****************************************************************************************************************/

include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/jsmail/mail_new_inc.php");

$mysqlObj = new Mysql;
$db=$mysqlObj->connect("alerts");

$checksumArr = explode("i",$checksum);
if($checksumArr[0]==md5($checksumArr[1]) && $logic_used && is_numeric($logic_used))
{
	if($sent_date && !is_numeric($sent_date))
		$sent_date = "";
	if($freq && !is_numeric($freq))
		$freq = "";		

	$gap = getLogicalDate();
	$sql="INSERT INTO matchalerts.TOP_VIEW_COUNT(PROFILEID,DATE,LOGIC,SENT_DATE,FREQUENCY) VALUES('$checksumArr[1]','$gap','$logic_used','".$sent_date."','".$freq."')";
	$res=mysql_query($sql) or logError("Error while inserting data into matchalerts.TOP_VIEW_COUNT",$sql);
}

header('Content-type: image/gif');
$path = JsConstants::$imgUrl."/profile/images/jsmail/newMatches/logo1.gif";
readfile($path);

function logError($message,$query="")
{
        global $db, $checksum;

        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        $errorstring.="\" >> ".JsConstants::$alertDocRoot."/jsmail/logerror.txt";

        passthru($errorstring);
}
?>
