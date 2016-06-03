<?php
/**
*       Filename        :	final_verify_service.php
*       Description     :	user is asked to mark the fields which he wants to be visible to other users or not.
				for ex - address, phoneno , messengerid etc.       
*       Created by      :       Puneet
*       Changed by      :
*       Changed on      :       22-8-2005
*       Changes         :
**/

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
connect_db();
                                                                                                                             
$arr=explode("i",$checksum);


if($arr[0]==md5($arr[1]))
{	
	$smarty->assign("SUBJECT",$subject);
	$id=$arr[1];
	$sql="SELECT BILLID FROM billing.PURCHASES WHERE PROFILEID='$id' AND VERIFY_SERVICE='A' AND STATUS='DONE'";
        $billresult=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
       	$billrow=mysql_fetch_array($billresult);
        $billid=$billrow['BILLID'];

	//when user presses the submit button his final verification is done for the services EVALUE OR ECLASSIFIED, OR 
	//HOROSCOPE OR KUNDALI if he has any. and VERIFY_SERVICE field in billing.PURCHASES is changed to 'Y' and
	//he is shown the THANKS PAGE OF VERIFICATION.	
	if($Submit)
	{
		maStripVARS("addslashes");

		$today=date("Y-m-d");

		if(!$showAddress)
			$showAddress="N";
													     
		if(!$Showphone)
			$Showphone="N";
													     
		if(!$Showmobile)
			$Showmobile="N";
													     
		if(!$Show_Parents_Contact)
			$Show_Parents_Contact="N";
		
		if(!$showMessenger)
                	$showMessenger="N";

		$sql="select SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$id'";
                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

		$editrow=mysql_fetch_array($result);
														     
		$arrsub=explode(",",$editrow["SUBSCRIPTION"]);
														     
		if(in_array("D",$arrsub) && in_array("S",$arrsub))
		$arrser=substr($editrow["SUBSCRIPTION"],0,strlen($editrow["SUBSCRIPTION"])-2);
		if(!$arrser)
			$arrser=$editrow["SUBSCRIPTION"];

		$sql="UPDATE newjs.JPROFILE SET SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT ='$Show_Parents_Contact',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',SHOWMESSENGER='$showMessenger', MOD_DT=now(),LAST_LOGIN_DT='$today' ,SUBSCRIPTION='$arrser'  WHERE PROFILEID='$id'";
                                                                                                                             
     		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		
		$sql_update="UPDATE billing.PURCHASES  SET VERIFY_SERVICE='Y' WHERE BILLID='$billid' AND STATUS='DONE' AND  VERIFY_SERVICE='A'";
                mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
;
		
		if((strstr($editrow['SUBSCRIPTION'],'D')) && (strstr($editrow['SUBSCRIPTION'],'F')))
			$smarty->assign("EVALUE","Y");
		
		$smarty->assign("HAS_CONTACT","Y");
		
		if(strstr($editrow['SUBSCRIPTION'],'K'))
                {	$smarty->assign("KUNDALI","Y");
                	$smarty->assign("ASTRO","Y");
		}
		if(strstr($editrow['SUBSCRIPTION'],'H'))
                {       $smarty->assign("HOROSCOPE","Y");		
                	$smarty->assign("ASTRO","Y");
		}
		$smarty->display("ec_thanks.htm");
	}
	else
	{	
		$sql = "Select SUBSCRIPTION,COUNTRY_BIRTH,CITY_BIRTH,BTIME,NAKSHATRA,PARENTS_CONTACT,CONTACT,SHOWADDRESS,PINCODE,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,SHOW_PARENTS_CONTACT ,EMAIL from newjs.JPROFILE where PROFILEID='$id'";

		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		$myrow=mysql_fetch_array($result);

		if(strstr($myrow['SUBSCRIPTION'],'D'))
		{	
			$smarty->assign("HAS_CONTACT","Y");
			if((!$myrow['CONTACT']) || (!$myrow['PINCODE']) || ((!$myrow['PHONE_RES']) && (!$myrow['PHONE_MOB'])))				
			{	
				$valueservice_error=1;
				
			}

		}
		
		if((strstr($myrow['SUBSCRIPTION'],'H')) || (strstr($myrow['SUBSCRIPTION'],'K')))
		{
			$smarty->assign("ASTRO","Y");
			if((!$myrow['COUNTRY_BIRTH']) || (!$myrow['CITY_BIRTH']) || (!$myrow['BTIME']))
			{	
        			$astroservice_error=1;
			}
		}		
		
		//if any of the mandatory field is empty than user is redirected to the activate_service.php page 
		//where he has to fill all the mandatory fields depending upon which service he has either D or H or K. 
		if(($astroservice_error) || ($valueservice_error))
		{	
			maStripVARS("stripslashes");
			$smarty->assign("EMAIL",$myrow["EMAIL"]);
			$smarty->assign("PARENTS_CONTACT",$myrow["PARENTS_CONTACT"]);
			$smarty->assign("SHOW_PARENTS_CONTACT",$myrow["SHOW_PARENTS_CONTACT"]);
			$smarty->assign("CONTACT",$myrow["CONTACT"]);
			$smarty->assign("SHOWADDRESS",$myrow["SHOWADDRESS"]);
			$smarty->assign("PINCODE",$myrow["PINCODE"]);
			$smarty->assign("PHONE_RES",$myrow["PHONE_RES"]);
			$smarty->assign("PHONE_MOB",$myrow["PHONE_MOB"]);
			$smarty->assign("SHOWPHONE_RES",$myrow["SHOWPHONE_RES"]);
			$smarty->assign("SHOWPHONE_MOB",$myrow["SHOWPHONE_MOB"]);
			$smarty->assign("MESSENGER_ID",$myrow["MESSENGER_ID"]);
			$smarty->assign("MESSENGER_CHANNEL",$myrow["MESSENGER_CHANNEL"]);
			$smarty->assign("SHOWMESSENGER",$myrow["SHOWMESSENGER"]);
			$smarty->assign("COUNTRY_BIRTH",create_dd($myrow["COUNTRY_BIRTH"],"Country_Birth"));
			$smarty->assign("CITY_BIRTH",$myrow["CITY_BIRTH"]);
			$birthtime=explode(":",$myrow["BTIME"]);
			$smarty->assign("HOUR",$birthtime[0]);
			$smarty->assign("MINUTE",$birthtime[1]);
			$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"]);
			$smarty->assign("checksum",$checksum);
			$smarty->display("activate_service.htm");
		}
		else
		{
			if(strstr($myrow['SUBSCRIPTION'],'D'))
			{
				$smarty->assign("CONTACT",$myrow['CONTACT']);
				$smarty->assign("PARENTS_CONTACT",$myrow['PARENTS_CONTACT']);
				$smarty->assign("PHONE_RES",$myrow['PHONE_RES']);
				$smarty->assign("PHONE_MOB",$myrow['PHONE_MOB']);
				$smarty->assign("MESSENGER_ID",$myrow['MESSENGER_ID']);
				$smarty->assign("EMAIL",$myrow['EMAIL']);
				if(strstr($myrow['SUBSCRIPTION'],'F'))
					$smarty->assign("EVALUE","Y");
				$smarty->assign("checksum",$checksum);
				$smarty->display("ec_edit.htm");
			}
			//if user doesn't have D service than he is shown the THANKS PAGE OF VERIFICATION.
			else
			{	
				maStripVARS("addslashes");
				$smarty->assign("KUNDALI","Y");
				if(strstr($myrow['SUBSCRIPTION'],'H'))
					$smarty->assign("HOROSCOPE","Y");
				$sql_update="UPDATE billing.PURCHASES  SET VERIFY_SERVICE='Y' WHERE BILLID='$billid' AND STATUS='DONE' AND  VERIFY_SERVICE='A'";
                                mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
;
				$smarty->display("ec_thanks.htm");
			}
		}
	}
}
else
	logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

?>
