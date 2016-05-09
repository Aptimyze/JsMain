<?php
/*
* This file gives the status of the Caller and the Receiver to access Callnow feaure   
* param returns xml to the third party. 
*/
$host =FetchClientIP();

$_SERVER['DOCUMENT_ROOT'] =JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/IVRContact.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/ivr_errorcodes.php");

/**********Temporary Tracking*************
$fp = fopen("log.txt","a+");
fwrite($fp,"\n\nDATE:".date('y-m-d h:i:s')."\n\n");
foreach($_SERVER as $key => $value){
fwrite($fp,$key."=>".$value."\n");
}
fclose($fp);
*****************************************/

$phoneNumber = $_GET["phoneNumber"];
$dialCode = $_GET["dialCode"];
$dialerStatus = $_GET["dialerStatus"];
$dialerStatus=$dialerStatus=="P"?"R":$dialerStatus;//For patched calls set status R

connect_db();//Database Connection
if($phoneNumber && $dialCode && $dialerStatus){
        $ivrObj = new IVRContact($phoneNumber,$dialCode);
	$dialer = $ivrObj->getDialerDetail();
	if($dialer["DIALCODE"]){
		$ivrObj->setCallerReceiverDetail($dialer["CALLER"],$dialer["RECEIVER"]);
		$ivrObj->captureCallnowStatus($dialerStatus);
		$requestStatus = 1;
	}
	else 
		$requestStatus = $IVR_errorCodeArr['ERROR_DIALCODE'];
}
else{
        if(!$phoneNumber) $requestStatus = 18;
        if(!$dialCode) $requestStatus = 19;
	if(!$dialerStatus) $requestStatus = 20;
}
echo generateXML($phoneNumber, $dialCode, $requestStatus, $IVR_errorDesc);
function generateXML($phoneNumber, $dialCode, $requestStatus, $IVR_errorDesc)
{
        $success = 0;
        if($requestStatus == 1) $success = 1;
        // XML
        header('content-type: text/xml');
        $xmlStr ="";
        $xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xmlStr.="\n\t<PROFILE>";
        $xmlStr.="\n\t\t<PHONENO>$phoneNumber</PHONENO>";
        $xmlStr.="\n\t\t<DIALCODE>$dialCode</DIALCODE>";
        $xmlStr.="\n\t\t<SUCCESS>$success</SUCCESS>";
        $xmlStr.="\n\t\t<ERRORMSG ERR=\"".$requestStatus."\" DESC=\"$IVR_errorDesc[$requestStatus]\"></ERRORMSG>";
        $xmlStr.="\n\t</PROFILE>";
        return $xmlStr;
}
?>
