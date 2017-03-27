<?php
/************************************************************************************************************************
*    FILENAME           : jsPhoneStatus.php 
*    DESCRIPTION        : This file updates the phone verification status for the profile in JS
*    ACCESS		: accessible by third-party(Cellcaste)
			: third-party execute the file to send the phone status(confirm/denied/busy) to JS  
***********************************************************************************************************************/
include_once(JsConstants::$docRoot."/ivr/jsProfileVerify.class.php");
include_once(JsConstants::$docRoot."/profile/connect.inc");
if(!connect_db())
	$db=connect_db();
/**********Temporary Tracking*************/
$fp = fopen("/tmp/knw_res.txt","a+");
fwrite($fp,"\n\nDATE:".date('y-m-d h:i:s')."\n\n");
foreach($_SERVER as $key => $value){
fwrite($fp,$key."=>".$value."\n");
}
fclose($fp);
/*****************************************/

//set SET and GET parameter variables
$var_profilecode="profilecode";
$var_msisdn	="msisdn";
$var_validity	="validity";

$profilecode 	= $_GET["$var_profilecode"];
$phone 		= $_GET["$var_msisdn"];
$validity 	= $_GET["$var_validity"];



if(!(is_numeric($profilecode) && is_numeric($phone) && is_numeric($validity) ))
	die('one of the params profilecode, phone and validity non numeric.');
// log the JS hit from third-party(Cellcast)
$ivrJsProfileObj = new jsProfileVerify;
$reqType         ="phone verification";
$data 		 = "validity=".$validity;
$ivrJsProfileObj->logIVRHit($profilecode,$phone,$reqType,$data);

// phone verification status
if($validity && $profilecode){
	$requestState 	="1";
	$validity 	=strtolower($validity);
	$validity 	=trim($validity);
	$phone  	=trim($phone);

        if($validity =='1' || $validity =='confirm'){
                $status ="Y";
        }       
        else if($validity =='2' || $validity =='denied'){
                $status ="D";
	}
        else if($validity =='3' || $validity =='busy'){
                $status ="B";
	}
        else{
                $status ="I";
        }
	$type =$ivrJsProfileObj->getPhoneType($profilecode,$phone);
	if($type)
		$ivrJsProfileObj->phoneNumberVerifyStatus($profilecode,$phone,'IVR',$status,$type);
}
else{
	$requestState ="0";
}
$str = generateXML($profilecode,$requestState);
echo $str;

// function generate xml format
function generateXML($profilecode,$requestState)
{
	header('content-type: text/xml');
	$xmlStr ="";
	$xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
	$xmlStr.="\n\t<PROFILE>\n\t\t";
        $xmlStr.="\n\t\t<PROFILEID>$profilecode</PROFILEID>\n\t\t";
	$xmlStr.="\n\t\t<STATUS>$requestState</STATUS>\n\t\t";	
	$xmlStr.="\n\t</PROFILE>";
	return $xmlStr;
}

?>
