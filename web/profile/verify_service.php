<?php

$dirname=dirname(__FILE__);
chdir($dirname);

// verify_service.php
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("../billing/comfunc_sums.php");

connect_db();

if($argv[1]=="reminder")
	$sql="SELECT DISTINCT P.BILLID, J.PROFILEID ,J.EMAIL, J.SUBSCRIPTION, J.CITY_BIRTH,J.COUNTRY_BIRTH,J.USERNAME,J.CONTACT,J.PHONE_RES,J.PHONE_MOB,J.PARENTS_CONTACT,J.NAKSHATRA ,J.BTIME ,J.MESSENGER_ID from billing.PURCHASES AS P left join newjs.JPROFILE AS J on J.PROFILEID=P.PROFILEID where P.VERIFY_SERVICE='A' AND P.STATUS='DONE'  AND DATE_SUB(CURDATE(),INTERVAL  7 DAY) =P.EMAIL_SENT_DT AND J.ACTIVATED IN ('Y','H')";

else
	$sql="SELECT DISTINCT P.BILLID, J.PROFILEID ,J.EMAIL, J.SUBSCRIPTION, J.CITY_BIRTH,J.COUNTRY_BIRTH,J.USERNAME,J.CONTACT,J.PHONE_RES,J.PHONE_MOB,J.PARENTS_CONTACT,J.NAKSHATRA ,J.BTIME ,J.MESSENGER_ID,J.SCREENING from billing.PURCHASES AS P left join newjs.JPROFILE AS J on J.PROFILEID=P.PROFILEID where P.VERIFY_SERVICE = 'N' AND P.STATUS='DONE' AND J.ACTIVATED IN ('Y','H')";

	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);

	while($myrow=mysql_fetch_array($result))
	{	
		if($myrow["SUBSCRIPTION"]=='')
			continue;

		if((strstr($myrow['SUBSCRIPTION'],'K')) || (strstr($myrow['SUBSCRIPTION'],'H')))
		{
			$smarty->assign("ASTRO","Y");
			$astroservice=1;
		}

		if(strstr($myrow['SUBSCRIPTION'],'D'))
		{
			$smarty->assign("HAS_CONTACT","Y");
			$valueservice=1;
		}

		if((strstr($myrow['SUBSCRIPTION'],'F')) && (strstr($myrow['SUBSCRIPTION'],'D')))
		{	$smarty->assign("EVALUE","Y");
			$evalue=1;
		}
		
		
		if(strstr($myrow['SUBSCRIPTION'],'K'))
		{	$smarty->assign("KUNDALI","Y");
			$kundali=1;
		}
		
		if(strstr($myrow['SUBSCRIPTION'],'H'))
		{	$smarty->assign("HOROSCOPE","Y");
			$horo=1;
		}
		
		$subject="Your Subscription to Jeevansathi ";
		if($evalue)
			$subject.="e-Value";
		elseif($valueservice)
			$subject.="e-Classifieds";
		
		
		if(($kundali) && (($evalue) || ($valueservice)))
			$subject.=" , Kundali";
		elseif(($kundali) && (!$evalue) && (!$valueservice))
			$subject.="Kundali";
		
		if(($horo) && (($evalue) || ($valueservice)) || ($kundali))
			$subject.=" , Horoscope";
		elseif(($horo) && (!$evalue) && (!$valueservice) && (!$kundali))
			$subject.="Horoscope";		
		
		$profileid=$myrow['PROFILEID'];
		$billid=$myrow['BILLID'];
		$username=$myrow['USERNAME'];
		$checksum=md5($profileid)."i".$profileid;	
		$smarty->assign("username",$username);
		$smarty->assign("checksum",$checksum);

		unset($invalid_a);
		unset($invalid_v);

		if($valueservice)
		{
			if(!isFlagSet("PHONERES",$myrow["SCREENING"]))
				$invalid_v["PHONERES"]=1;
			if(!isFlagSet("PHONEMOB",$myrow["SCREENING"]))
				$invalid_v["PHONEMOB"]=1;
			if(!isFlagSet("CONTACT",$myrow["SCREENING"]))
				$invalid_v["CONTACT"]=1;
			if(!isFlagSet("MESSENGER",$myrow["SCREENING"]))
				$invalid_v["MESSENGER"]=1;
			if(!isFlagSet("PARENTS_CONTACT",$myrow["SCREENING"]))
				$invalid_v["PARENTS_CONTACT"]=1;
			if(!isFlagSet("EMAIL",$myrow["SCREENING"]))
				$invalid_v["EMAIL"]=1;
		}


		if($astroservice)
		{
			if(!isFlagSet("NAKSHATRA",$myrow["SCREENING"]))
				$invalid_a["NAKSHATRA"]=1;
			if(!isFlagSet("CITYBIRTH",$myrow["SCREENING"]))
				$invalid_a["CITY_BIRTH"]=1;
		}
		if(!is_array($invalid_a) && !is_array($invalid_v))
		{
                        $sql_update="UPDATE billing.PURCHASES SET VERIFY_SERVICE='Y' WHERE BILLID='$billid' AND STATUS='DONE' AND VERIFY_SERVICE IN('A','N')";
	                mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"Y");
			if(strstr($myrow["SUBSCRIPTION"],"S"))
				evalue_privacy($profileid,$myrow["SUBSCRIPTION"]);
			else
				evalue_privacy($profileid);
			continue;
		}	
		$smarty->assign("invalid_a",$invalid_a);
		$smarty->assign("invalid_v",$invalid_v);
		
		if($astroservice)
		{	 
			$country_temp=label_select('COUNTRY',$myrow['COUNTRY_BIRTH']);
			$country=$country_temp[0];
			$smarty->assign("COUNTRY_BIRTH",$country);
			$smarty->assign("CITY_BIRTH",$myrow["CITY_BIRTH"]);
			$smarty->assign("TIMEOFBIRTH",$myrow["BTIME"]);
			$smarty->assign("NAKSHATRA",$myrow["NAKSHATRA"]);

		}
	
		if($valueservice)
		{	
			$smarty->assign("CONTACT",$myrow['CONTACT']);
			$smarty->assign("PARENTS_CONTACT",$myrow['PARENTS_CONTACT']);
			$smarty->assign("PHONE_RES",$myrow['PHONE_RES']);
			$smarty->assign("PHONE_MOB",$myrow['PHONE_MOB']);
			$smarty->assign("MESSENGER_ID",$myrow['MESSENGER_ID']);
			$smarty->assign("EMAIL",$myrow['EMAIL']);
		
		}
	
		/*$sql_detail="SELECT sum(if(TYPE='DOL',AMOUNT*$DOL_CONV_RATE,AMOUNT)) as AMOUNT, STATUS,TYPE from billing.PAYMENT_DETAIL where BILLID='$billid' group by STATUS";
		$result_detail=mysql_query_decide($sql_detail) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_detail,"Y");

		$totalpaid=0;
		$totalpaid_done=0;
		$totalrefund=0;
		$totalbounce=0;
		$totalpaid_adjust=0;
		while($myrow_detail=mysql_fetch_array($result_detail))
		{
			if($myrow_detail['STATUS']=='DONE')
				$totalpaid_done += $myrow_detail['AMOUNT'];
			elseif($myrow_detail['STATUS']=='REFUND')
				$totalrefund += $myrow_detail['AMOUNT'];
			elseif($myrow_detail['STATUS']=='ADJUST')
				$totalpaid_adjust += $myrow_detail['AMOUNT'];
                }

		$sql_duration="SELECT SERVICEID FROM billing.SERVICE_STATUS WHERE BILLID='$billid'";
		$result_duration=mysql_query_decide($sql_duration) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_duration,"Y");

		
		while($myrow_duration=mysql_fetch_array($result_duration))
			$duration=substr($myrow_duration['SERVICEID'],1,strlen($myrow_duration['SERVICEID']));
		
		$paidamount=$totalpaid_done+$totalpaid_adjust-$totalrefund;
		$smarty->assign("DURATION",$duration);
		$smarty->assign("AMOUNT",$paidamount);*/
		$smarty->assign("SUBJECT",$subject);

		$mesg=$smarty->fetch("ec_mail.htm");	
		$to=$myrow['EMAIL'];
		send_email($to,$mesg,$subject,"webmaster@jeevansathi.com");
		//echo $mesg;
		/*$horo=0;
		$kundali=0;
		$astroservice=0;
		$valueservice=0;
		$subject="";
		$mesg="";
		$evalue=0;
		$smarty->assign("ASTRO","N");
		$smarty->assign("HAS_CONTACT","N");
		$smarty->assign("DURATION",0);
		$smarty->assign("AMOUNT",0);
		$smarty->assign("EVALUE","N");
		$smarty->assign("KUNDALI","N");
		$smarty->assign("HOROSCOPE","N");
		$smarty->assign("SUBJECT","");*/
		
		if($argv[1]=="reminder")
			$sql_update="UPDATE billing.PURCHASES SET EMAIL_SENT_DT=now()  WHERE BILLID='$billid' AND STATUS='DONE' AND VERIFY_SERVICE='A'";
		else
			$sql_update="UPDATE billing.PURCHASES SET VERIFY_SERVICE='A',EMAIL_SENT_DT=now()  WHERE BILLID='$billid' AND STATUS='DONE' AND VERIFY_SERVICE = 'N'";
		mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"Y");
	}
?>
