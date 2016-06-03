<?php
//ini_set("max_execution_time","0");

/************************************************************************************************************************
*    FILENAME           : mobile_verification_sms.php 
*    INCLUDED           : connect.inc
*    DESCRIPTION        : To send sms for user having having not responded the IVR call or have denied verification through SMS.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
//include_once("connect.inc");
$root_path=realpath(dirname(__FILE__)."/..");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");

function SEND_MOBSMS($profileid,$mobile,$status='')
{
	$msg=" call to function SEND_MOBSMS function in mobile_verification_sms.php with folowing details:\n profileid:". $profileid." , mobile no: ".$mobile." and status: ".$status.". warm regards.";
	if($msg)
	{
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
                $cc='esha.jain@jeevansathi.com';
                $to='tanu.gupta@jeevansathi.com';
                $subject="call to SEND_MOBSMS()";
                send_email($to,$msg,$subject,"",$cc);

	}
}

?>
