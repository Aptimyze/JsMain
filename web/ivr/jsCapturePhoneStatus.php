<?php
/************************************************************************************************************************
*    FILENAME           : jsCapturePhoneStatus.php 
*    DESCRIPTION        : This file updates the phone verification status for the profile in JS
*    ACCESS		: accessible by third-party(knowlarity)
			: third-party execute the file to send that this number has verified by calling on virtual number provided  
*    Author		: @Esha
***********************************************************************************************************************/
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$number 	= $_GET["number"];
//$date 		= $_GET["date"];
$virtualno = $_GET["virtual_number"];

$virtualno = substr($virtualno,-8);
$phoneno=trim(ltrim($number,'0'));
if($virtualno && $phoneno)
{
	
	if(in_array($_GET["virtual_number"],phoneKnowlarity::virtualNumbersListForLeads())) 
	{
		phoneKnowlarity::createLead($_GET["number"]);
	}
	else{

			$verificationObj=new MissedCallVerification($phoneno,$virtualno);
			$verificationObj->phoneUpdateProcess("KNW");	
	}
$xmlStr= phoneKnowlarity::genrate_xml();
echo $xmlStr;
}

?>
