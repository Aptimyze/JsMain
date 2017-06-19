<?php
/************************************************************************************************************************
*    FILENAME           : ivrCallPreCheck.php
*    DESCRIPTION        : This file checks whether to call the phone number provided for the profile
*    ACCESS		: accessible by third-party(Knowlarity)
			: third-party execute the file to check the calling status for a profile phone number
***********************************************************************************************************************/
include_once(JsConstants::$docRoot."/ivr/jsProfileVerify.class.php");
include_once(JsConstants::$docRoot."/profile/connect.inc");
if(!connect_db())
	$db=connect_db();
/**********Temporary Tracking*************
$fp = fopen("/tmp/knw_res.txt","a+");
fwrite($fp,"\n\nDATE:".date('y-m-d h:i:s')."\n\n");
foreach($_SERVER as $key => $value){
fwrite($fp,$key."=>".$value."\n");
}
fclose($fp);
/*****************************************/
$profileid 	= $_GET["profileid"];
$number		= $_GET["number"];

$number	=trim($number);
if($profileid && $number){
	$ivrJsProfileObj = new jsProfileVerify;
	$type =$ivrJsProfileObj->getPhoneType($profileid,$number,$checkVerified=true);
	if($type)
	{
		$arr= explode("|",$type);
		if($arr[1]!="Y")
			$return = true;
		else
			$return =false;
	}
	else
		$return =false;
}
else{
	$return =false;
}
$str = generateXML($profileid,$return);
echo $str;

// function generate xml format
function generateXML($profileid,$requestState)
{
	header('content-type: text/xml');
	$xmlStr ="";
	$xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
	$xmlStr.="\n\t<PROFILE>\n\t\t";
        $xmlStr.="\n\t\t<PROFILEID>$profileid</PROFILEID>\n\t\t";
	$xmlStr.="\n\t\t<STATUS>$requestState</STATUS>\n\t\t";	
	$xmlStr.="\n\t</PROFILE>";
	return $xmlStr;
}

?>
