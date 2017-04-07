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
			$verified = $verificationObj->phoneUpdateProcess("KNW");
      $szMsg = $verificationObj->getTempText();
      
      if (strlen($szMsg) && !$verified) {
          $ob =  new Profile();
          $ob->getDetail(substr($phoneno,-10),'PHONE_MOB','MOB_STATUS,PROFILEID');
          if(!$ob->getPROFILEID()){
                    $contactArray= (new ProfileContact())->getArray(array('ALT_MOBILE'=>substr($phoneno,-10)),'','',"ALT_MOB_STATUS");
                    if($contactArray['0'])
                        $status=$contactArray['0']['ALT_MOB_STATUS']=='Y'?'Y':'N';
                    else $status ='Not found';
          }
          else $status = $ob->getMOB_STATUS()=='Y' ? 'Y' : 'N';
        mail("kunal.test02@gmail.com,palashc2011@gmail.com", "PhoneVerfication Issue ", $szMsg."mob st: ".$status);
      }
  }
$xmlStr= phoneKnowlarity::genrate_xml();
echo $xmlStr;
}

?>
