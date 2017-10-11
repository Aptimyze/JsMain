<?php
/**
*       Filename        :       activate_service.php
*       Description     :     	user is asked to enter his contact details or astro details depending upon which service
				he has either EVALUE OR ECLASSIFIED OR for astro KUNDALI OR HOROSCOPE.
				This file is used both for MIS and also for final verification of VARIOUS SERVICES when
				user is contacted through MAIL.
				When the file is called through MIS , following is added to query string &mis=true
*       Created by      :       Puneet
*       Changed by      :
*       Changed on      :       22-8-2005
*       Changes         :
**/

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
connect_db();

$arr=explode("i",$checksum);
if($arr[0]!=md5($arr[1]))
{
	if($mis)
        {       $msg="Your session has been timed out<br>  ";
                $msg .="<a href=\"index.htm\">";
                $msg .="Login again </a>";
                $smarty->assign("MSG",$msg);
		$smarty->display("toviewservice.htm");
	}
        else
                logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

}
else
{
	$smarty->assign("SUBJECT",$subject);
	$id=$arr[1];
	$sql="select SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$id' and ACTIVATED IN ('Y','H')";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$data=mysql_fetch_array($result);

	if((strstr($data['SUBSCRIPTION'],'K')) || (strstr($data['SUBSCRIPTION'],'H')))
	{ 
		$smarty->assign("ASTRO","Y");
		$astroservice="yes";
	}                                                                                                                     
	if(strstr($data['SUBSCRIPTION'],'D'))
	{	
		$smarty->assign("HAS_CONTACT","Y");
		$valueservice="yes";
	}

	if(strstr($data['SUBSCRIPTION'],'K'))
	{	
		$smarty->assign("KUNDALI","Y");
	}

	if(strstr($data['SUBSCRIPTION'],'H'))
	{	
		$smarty->assign("HOROSCOPE","Y");
	}

	if((strstr($data['SUBSCRIPTION'],'D')) && (strstr($data['SUBSCRIPTION'],'F')))
	{	
		$smarty->assign("EVALUE","Y");
	}
	$smarty->assign("checksum",$checksum);
	$smarty->assign("cid",$cid);
	$smarty->assign("mis",$mis);

	if($submit)
	{	 // add slashes to prevent quotes problem
                 maStripVARS("addslashes");
		$is_error=0;
		$Email=trim($Email);

		if($astroservice)		
		{	if(trim($Country_Birth)=="")
			{
				$is_error++;
				$smarty->assign("check_country_birth","Y");
			}

			if(trim($City_Birth)=="")
			{
				$is_error++;
				$smarty->assign("check_city_birth","Y");
			}

			if((trim($Hour_Birth)=="") || (trim($Min_Birth)==""))
			{
				$is_error++;
				$smarty->assign("check_time_birth","Y");
			}
		}	

		if($valueservice)
		{
			if(trim($Messenger_ID) != "")
			{
				if($Messenger=="")
				{
					$is_error++;
					$smarty->assign("check_messenger","Y");
					$check_messenger="Y";
				}
			}
		
			if(!checkemail1($Email))
			{
				$is_error++;
				$smarty->assign("check_email","1");
			}
			
			$sql="select EMAIL from newjs.JPROFILE where PROFILEID='$id'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
															     
			$emailrow1=mysql_fetch_row($result);
															     
			if($emailrow1[0]!=$Email)
			{
				mysql_free_result($result);
															     
				$sql="select count(*) from newjs.JPROFILE where EMAIL='$Email' and PROFILEID<>'$id'";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				$emailrow=mysql_fetch_row($result);
															     
				$sql_d="select count(*) from newjs.OLDEMAIL where OLD_EMAIL='$Email' and PROFILEID<>'$id'";
				$result_d=mysql_query_decide($sql_d) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_d,"ShowErrTemplate");
				$emailrow_d=mysql_fetch_row($result_d);
				if($emailrow[0] > 0 ||  $emailrow_d[0]>0)
				{
					$is_error++;
					$smarty->assign("check_dup_email","Y");
				}
			}
			mysql_free_result($result);	
			
			if(trim($Address)=="")
			{
				$is_error++;
				$smarty->assign("check_address","Y");
			}
														     
			if(trim($pincode)=="" || !is_numeric($pincode))
			{
				$is_error++;
				$smarty->assign("check_pincode","Y");
			}
														     
			if(trim($Phone)=="" && trim($Mobile)=="")
			{
				$is_error++;
				$smarty->assign("check_phone","Y");
				$smarty->assign("phone_msg","Please fill one of the two phone numbers.");
			}
			elseif(checkrphone($Phone) && checkmphone($Mobile))
			{
				$is_error++;
				$smarty->assign("check_phone","Y");
				$smarty->assign("phone_msg","Phone no. has invalid characters");
			}
		}

		if($is_error > 0)
		{
			$smarty->assign("NO_OF_ERROR",$is_error);
			// remove slashes
			maStripVARS("stripslashes");
			
			if($astroservice)
			{
				$smarty->assign("COUNTRY_BIRTH",create_dd($Country_Birth,"Country_Birth"));
				$smarty->assign("CITY_BIRTH",$City_Birth);
				$smarty->assign("HOUR",$Hour_Birth);
				$smarty->assign("MINUTE",$Min_Birth);
				$smarty->assign("NAKSHATRA",$Nakshatram);
			}
			if($valueservice)
			{
				$smarty->assign("PARENTS_CONTACT",$Parents_Contact);
				$smarty->assign("SHOW_PARENTS_CONTACT",$Show_Parents_Contact);
				$smarty->assign("CONTACT",$Address);
				$smarty->assign("PINCODE",$pincode);
				$smarty->assign("PHONE_RES",$Phone);
				$smarty->assign("PHONE_MOB",$Mobile);
				$smarty->assign("SHOWPHONE_RES",$Showphone);
				$smarty->assign("SHOWPHONE_MOB",$Showmobile);
				$smarty->assign("MESSENGER_ID",$Messenger_ID);
				$smarty->assign("MESSENGER_CHANNEL",$Messenger);
				$smarty->assign("SHOWADDRESS",$showAddress);
				$smarty->assign("SHOWMESSENGER",$showMessenger);
				$smarty->assign("EMAIL",$Email);
			}
			
			$smarty->display("activate_service.htm");
		}
		else
		{//	echo "NO ERRORS<br><br>";
			$sql="select SUBSCRIPTION,CITY_BIRTH,NAKSHATRA,PARENTS_CONTACT,CONTACT,SCREENING,PHONE_RES,PHONE_MOB,MESSENGER_ID,STD from newjs.JPROFILE where PROFILEID='$id'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			//echo "<br><br>";										     
			$editrow=mysql_fetch_array($result);

			$arrsub=explode(",",$editrow["SUBSCRIPTION"]);

			if($mis)
			{
				if(in_array("D",$arrsub) && in_array("S",$arrsub))
					$arrser=substr($editrow["SUBSCRIPTION"],0,strlen($editrow["SUBSCRIPTION"])-2);
			}
			
			$curflag=$editrow["SCREENING"];
													     
			if($astroservice)
			{
				if(trim($City_Birth)=="")
					$curflag=setFlag("CITYBIRTH",$curflag);
				elseif($City_Birth!=$editrow["CITY_BIRTH"])
					$curflag=removeFlag("CITYBIRTH",$curflag);
														     
				if(trim($Nakshatram)=="")
					$curflag=setFlag("NAKSHATRA",$curflag);
				elseif($Nakshatram!=$editrow["NAKSHATRA"])
					$curflag=removeFlag("NAKSHATRA",$curflag);
			
				$btime=$Hour_Birth.":".$Min_Birth;
			}										     
			
			if($valueservice)
			{
				if(trim($Parents_Contact)=="")
					$curflag=setFlag("PARENTS_CONTACT",$curflag);
				elseif($Parents_Contact!=$editrow["PARENTS_CONTACT"])
					$curflag=removeFlag("PARENTS_CONTACT",$curflag);
														     
				if(trim($Address)=="")
					$curflag=setFlag("CONTACT",$curflag);
				elseif($Address!=$editrow["CONTACT"])
					$curflag=removeFlag("CONTACT",$curflag);
														     
				if(trim($Phone)=="")
					$curflag=setFlag("PHONERES",$curflag);
				elseif($Phone!=$editrow["PHONE_RES"])
					$curflag=removeFlag("PHONERES",$curflag);
				$std = $editrow["STD"];
				if(trim($Mobile)=="")
					$curflag=setFlag("PHONEMOB",$curflag);
				elseif($Mobile!=$editrow["PHONE_MOB"])
					$curflag=removeFlag("PHONEMOB",$curflag);
														     
				if(trim($Messenger_ID)=="")
					$curflag=setFlag("MESSENGER",$curflag);
				elseif($Messenger_ID!=$editrow["MESSENGER_ID"])
					$curflag=removeFlag("MESSENGER",$curflag);
														     
				if(!$showAddress)
					$showAddress="Y";
				else
					$showAddress="N";
														     
				if(!$showMessenger)
					$showMessenger="Y";
				else
					$showMessenger="N";										     
				if(!$Showphone)
					$Showphone="Y";
				else
					$Showphone="N";
														     
				if(!$Showmobile)
					$Showmobile="Y";
				else
					$Showmobile="N";
														     
				if(!$Show_Parents_Contact)
					$Show_Parents_Contact="Y";
				else
					$Show_Parents_Contact="N";
			}
		
			$today=date("Y-m-d");

			if($astroservice && $valueservice){
				$phone_with_std=$std.$Phone;
				if($Phone==""){
					$phone_with_std="";
					$std="";
				}
				$sql = "UPDATE newjs.JPROFILE SET COUNTRY_BIRTH='$Country_Birth',CITY_BIRTH='$City_Birth',BTIME='$btime',NAKSHATRA='$Nakshatram',PARENTS_CONTACT='$Parents_Contact',SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT='$Show_Parents_Contact',CONTACT='$Address',PINCODE='$pincode',PHONE_RES='$Phone',PHONE_WITH_STD='$phone_with_std',PHONE_MOB='$Mobile',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger',SHOWMESSENGER='$showMessenger',SCREENING='$curflag',INCOMPLETE='N',MOD_DT=now(),LAST_LOGIN_DT='$today',EMAIL='$Email'";
			}

			elseif($astroservice && !$valueservice)
				$sql="UPDATE newjs.JPROFILE SET COUNTRY_BIRTH ='$Country_Birth',CITY_BIRTH='$City_Birth',BTIME='$btime',NAKSHATRA='$Nakshatram',MOD_DT=now(),SCREENING='$curflag',INCOMPLETE='N',LAST_LOGIN_DT='$today'"; 

			elseif($valueservice && !$astroservice)
			{
				$phone_with_std=$std.$Phone;
				if($Phone=="")
					$phone_with_std="";
				$sql="UPDATE newjs.JPROFILE SET PARENTS_CONTACT='$Parents_Contact',SHOWADDRESS='$showAddress',SHOW_PARENTS_CONTACT='$Show_Parents_Contact',CONTACT='$Address',PINCODE='$pincode',PHONE_RES='$Phone',PHONE_WITH_STD='$phone_with_std',PHONE_MOB='$Mobile',SHOWPHONE_RES='$Showphone',SHOWPHONE_MOB='$Showmobile',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger',SHOWMESSENGER='$showMessenger',MOD_DT=now(),SCREENING='$curflag',INCOMPLETE='N',LAST_LOGIN_DT='$today', EMAIL='$Email'";
			}
			if($mis && $arrser)
				$sql.=",SUBSCRIPTION='$arrser'";
				$sql.=" WHERE PROFILEID='$id'";

				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			
			$sql="SELECT BILLID FROM billing.PURCHASES WHERE PROFILEID='$id' AND VERIFY_SERVICE='A' ORDER BY BILLID DESC";
			$billresult=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$billrow=mysql_fetch_array($billresult);
			$billid=$billrow['BILLID'];

			if($mis)
			{	$sql_update="UPDATE billing.PURCHASES  SET VERIFY_SERVICE='Y' WHERE BILLID='$billid' AND STATUS='DONE' AND  VERIFY_SERVICE='A'";
				mysql_query_decide($sql_update) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_update,"ShowErrTemplate");
				$smarty->display("toviewservice.htm");
			}
			else
			{	/*if($valueservice)
				{	$smarty->assign("CONTACT",$Address);
					$smarty->assign("PARENTS_CONTACT",$Parents_Contact);
					$smarty->assign("PHONE_RES",$Phone);
					$smarty->assign("PHONE_MOB",$Mobile);
					$smarty->assign("MESSENGER_ID",$Messenger_ID);
					$smarty->assign("EMAIL",$Email);
					$smarty->display("ec_edit.htm");
				}
				else
				{	
					$sql_update="UPDATE billing.PURCHASES  SET VERIFY_SERVICE='Y' WHERE BILLID='$billid' AND STATUS='DONE' AND  VERIFY_SERVICE='A'";
					mysql_query_decide($sql_update) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_update,"ShowErrTemplate");*/
					$smarty->display("ec_thanks.htm");
				//}
			}
		}
	}
	else
	{	 
		$sql = "Select COUNTRY_BIRTH,CITY_BIRTH,BTIME,NAKSHATRA,PARENTS_CONTACT,CONTACT,SHOWADDRESS,PINCODE,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,SHOW_PARENTS_CONTACT,EMAIL from newjs.JPROFILE where PROFILEID='$id'";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
														     
		$myrow=mysql_fetch_array($result);
														     
		if($astroservice)
		{
			$smarty->assign("COUNTRY_BIRTH",create_dd($myrow["COUNTRY_BIRTH"],"Country_Birth"));
			$smarty->assign("CITY_BIRTH",$myrow["CITY_BIRTH"]);
			$birthtime=explode(":",$myrow["BTIME"]);
			$smarty->assign("HOUR",$birthtime[0]);
			$smarty->assign("MINUTE",$birthtime[1]);
			$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"]);
		}
		
		if($valueservice)
		{
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
		}												     
		
		$smarty->display("activate_service.htm");
	}
}
?>
