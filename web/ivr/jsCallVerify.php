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

connect_db();//Database Connection
if($phoneNumber && $dialCode){
	$ivrObj = new IVRContact($phoneNumber,$dialCode);
	$requestStatus = 0;
	$receiverPhoneNumber = "";
	$requestStatus = $ivrObj->verifyContact($IVR_errorCodeArr);
	if($requestStatus==1){
		$ivrObj->captureCallnowStatus('I'); //Call initiated
		$receiverPhoneNumber = $ivrObj->getReceiverPhoneNumber();
	}else{
		$ivrObj->captureCallnowStatus('E',$requestStatus);
	}
}
else{
	if(!$phoneNumber) $requestStatus = 18;
	if(!$dialCode) $requestStatus = 19;
	$ivrObj = new IVRContact();
	$time = $ivrObj->getIST();
	$sql ="INSERT INTO newjs.CALLNOW(`CALLER_PID`,`RECEIVER_PID`,`CALLER_PHONE`,`CALL_DT`,`CALL_STATUS`,`DIALCODE`,`ERROR_CODE`) value('','','$phoneNumber','$time','E','$dialCode','$requestStatus')";
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
echo generateXML($receiverPhoneNumber, $dialCode, $requestStatus, $IVR_errorDesc);
function generateXML($receiverPhoneNumber, $dialCode, $requestStatus, $IVR_errorDesc)
{
	$success = 0;
	if($requestStatus == 1){ 
		$success = 1;
		$error = 0;
	}else $error = $requestStatus;
	// XML
        header('content-type: text/xml');
        $xmlStr ="";
        $xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xmlStr.="\n\t<PROFILE>";
	$xmlStr.="\n\t\t<PHONENO>$receiverPhoneNumber</PHONENO>";
	$xmlStr.="\n\t\t<DIALCODE>$dialCode</DIALCODE>";
	$xmlStr.="\n\t\t<SUCCESS>$success</SUCCESS>";
	$xmlStr.="\n\t\t<ERRORMSG ERR=\"".$error."\" DESC=\"$IVR_errorDesc[$requestStatus]\"></ERRORMSG>";
        $xmlStr.="\n\t</PROFILE>";
        return $xmlStr;
}

?>

