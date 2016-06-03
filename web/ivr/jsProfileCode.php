<?php
/************************************************************************************************************************
*    FILENAME           : jsProfileCode.php 
*    DESCRIPTION        : This file verifies the profile-id on hit by the third party
                        : $param returns xml format to the third party.
***********************************************************************************************************************/

$_SERVER['DOCUMENT_ROOT'] =JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsProfileVerify.class.php");

//Set GET parameter variables
$var_profilecode="profilecode";
$var_msisdn	="msisdn";

$profilecode 	=$_GET["$var_profilecode"];
$msisdn 	=$_GET["$var_msisdn"];

// profilecode validation
if($profilecode){
	$requestState ="1";
	$ivrJsProfileObj = new jsProfileVerify;
	$phone =trim($msisdn);
	$profileState = $ivrJsProfileObj->profileCodeVerify($profilecode,$phone);	
	if($profileState)
		$valid =1;
	else
		$valid =0;
}
else{
	$requestState ="0";
}
	
$str = generateXML($profilecode,$requestState,$valid);
echo $str;

// function generate xml format
function generateXML($profilecode,$requestState,$valid)
{
	header('content-type: text/xml');
	$xmlStr ="";
	$xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
	$xmlStr.="\n\t<PROFILE>\n\t\t";
	$xmlStr.="\n\t\t<PROFILEID>$profilecode</PROFILEID>\n\t\t";
	$xmlStr.="\n\t\t<STATUS>$requestState</STATUS>\n\t\t";
	$xmlStr.="\n\t\t<VALID>$valid</VALID>\n\t\t";
	$xmlStr.="\n\t</PROFILE>";
	return $xmlStr;
}

?>
