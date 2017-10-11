<?php
	$curFilePath = dirname(__FILE__)."/";
        include_once("/usr/local/scripts/DocRoot.php");

       /******************************************For testing comment live portion and uncomment the test portion******************************************/
       //////////////////////////////////
        $start_time=date("Y-m-d H:i:s");
        mail("vibhor.garg@jeevansathi.com,lakshay@jeevansathi.com","FTA CSV Generation Started At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
       /////////////////////////////////

        ini_set("max_execution_time","0");
        include ("$docRoot/crontabs/connect.inc");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	include($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/crm/mainmenunew.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

	include("allocate_functions_revamp.php");
	$SITE_URL="http://www.jeevansathi.com";
	/*for test uncomment this
	include($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	*/

        $filename1 = $_SERVER['DOCUMENT_ROOT']."/uploads/csv_files/ftaCsvCrmData".date('Y-m-d')."DNC.dat";
        $filename2 = $_SERVER['DOCUMENT_ROOT']."/uploads/csv_files/ftaCsvCrmData".date('Y-m-d')."nonDNC.dat";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");

	$db 	= connect_db();
	$db_dnc = connect_dnc();
	$db737  = connect_737();
	$exclude_mtongue="3,16,17,31,1";

        if(!$fp1 || !$fp2)
        {
                die("no file pointer");
        }

	$sql="TRUNCATE TABLE incentive.TEMP_CSV_FTA_TECH";
	mysql_query($sql,$db) or die("$sql".mysql_error($db));

	$sql="insert ignore into incentive.TEMP_CSV_FTA_TECH (PROFILEID) (select PROFILEID from newjs.SEARCH_MALE WHERE HAVEPHOTO !='Y' AND MTONGUE NOT IN($exclude_mtongue))";
	mysql_query($sql,$db) or die("$sql".mysql_error($db));
	
	$sql="insert ignore into incentive.TEMP_CSV_FTA_TECH (PROFILEID) (select PROFILEID from newjs.SEARCH_FEMALE WHERE HAVEPHOTO !='Y' AND MTONGUE NOT IN($exclude_mtongue))";
	mysql_query($sql,$db) or die("$sql".mysql_error($db));
	minimize_fta_data();

	$sql_pid = "SELECT PROFILEID from incentive.TEMP_CSV_FTA_TECH ORDER BY PROFILEID DESC";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));

	while($row_pid = mysql_fetch_array($res_pid))
	{
		$profileid = $row_pid['PROFILEID'];

                // New Fto Check added
                $ftoStateArray          =SymfonyFTOFunctions::getFTOStateArray($profileid);
                $ftoState               =$ftoStateArray['STATE'];
                if($ftoState!=FTOStateTypes::FTO_EXPIRED && $ftoState!=FTOStateTypes::NEVER_EXPOSED)
                        continue;
                // Ends

		$sql_data = "SELECT USERNAME,GENDER,PHONE_MOB,ENTRY_DT,ACTIVATED,SERIOUSNESS_COUNT,STD,ISD,PHONE_RES,PHONE_WITH_STD from newjs.JPROFILE where PROFILEID = '$profileid'";
        	$res_data = mysql_query($sql_data,$db737) or die("$sql_data".mysql_error($db737));
		if($row_data = mysql_fetch_array($res_data))
        	{
			$gender = $row_data["GENDER"]; 
			$username = $row_data["USERNAME"];
			$entry_dt = substr($row_data['ENTRY_DT'],0,10);
			$srs_cnt=$row_data['SERIOUSNESS_COUNT'];
			$activated=$row_data['ACTIVATED'];
			$isd=$row_data['ISD'];
			
			if($myrow["ACTIVATED"]=="D")
			        $table_name="DELETED_PROFILE_CONTACTS";
			else
        			$table_name="CONTACTS";
	
			$phoneNumArray = array();
			if($row_data['PHONE_WITH_STD']!="")
         			$PHONE_WITH_STD = $row_data['PHONE_WITH_STD'];
 			else
         			$PHONE_WITH_STD = $row_data['STD'].$row_data['PHONE_RES'];
 			if($PHONE_WITH_STD)
         			$PHONE_WITH_STD =phoneNumberCheck($PHONE_WITH_STD);
 			$phoneNumArray['PHONE3'] = $PHONE_WITH_STD;

			$sql_AN = "SELECT ALT_MOBILE from newjs.JPROFILE_CONTACT where PROFILEID = '$profileid'";
			$res_AN = mysql_query($sql_AN,$db737) or die("$sql_AN".mysql_error($db737));
			$row_AN=mysql_fetch_array($res_AN);
			$AL_NUMBER=$row_AN['ALT_MOBILE'];

 			if($AL_NUMBER)
         			$PHONE_AN =phoneNumberCheck($AL_NUMBER);
 			else
         			$PHONE_AN ='';
 			$phoneNumArray['PHONE2'] = $PHONE_AN;

 			$PHONE_MOB =$row_data['PHONE_MOB'];
 			if($PHONE_MOB)
        			$PHONE_MOB =phoneNumberCheck($PHONE_MOB);
 			$phoneNumArray['PHONE1']=$PHONE_MOB;
 			if($PHONE_AN=="")
         			$PHONE_AN =$PHONE_MOB;

 			$phoneNumArray = checkDNC($phoneNumArray);
 			$isDNC = $phoneNumArray["STATUS"];
		
			//set DNC numbers blank 
 			if(!$isDNC)
			{
				$forDNC=False;
				$cnt=1;
				while($cnt!=4)
				{
					$param = "PHONE".$cnt."S";
					if($phoneNumArray[$param]=='Y')
						$phoneNumArray["PHONE$cnt"]="";
					$cnt++;	
				}
			}
			else
				$forDNC=True;
			for($cnt=1;$cnt<3;$cnt++)
			{
				if($phoneNumArray["PHONE$cnt"]!="")
					$phoneNumArray["PHONE$cnt"]="0".$phoneNumArray["PHONE$cnt"];
			}
		}
		
		//PARMANENT EXCLUSION RULE
                $sql_alloted="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
                $res_alloted = mysql_query($sql_alloted,$db737) or die("$sql_alloted".mysql_error($db737));
                if($row_alloted = mysql_fetch_array($res_alloted))
                	$alloted_case=1;
                else
                	$alloted_case=0;
                $permanent_excluded=0;
		//Indian Number Check
		$indianNo =isIndianNo($isd);
		if(!$indianNo)
			$permanent_excluded=1;
		//Invalid phone check,30 days Disposition Check
		$valid=check_profile($profileid);
		if(!$valid)
			$permanent_excluded=1;
		if($alloted_case)
			$permanent_excluded=1;
		/*	
		$alloted_case=1;
                if(!$alloted_case)
                {
                	$excl_dnc_dt=date('Y-m-d',time()-30*86400);
                	$excl_rest_dt=date('Y-m-d',time()-7*86400);
                        $excl_ni_dt=date('Y-m-d',time()-21*86400);
                        //disposition
                        $sql_history="SELECT ENTRY_DT,DISPOSITION FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
                        $res_history = mysql_query($sql_history,$db737) or die("$sql_history".mysql_error($db737));
                        if($row_history = mysql_fetch_array($res_history))
			{
				if($row_history["DISPOSITION"]=='DNC' && $row_history["ENTRY_DT"]>=$excl_dnc_dt)
					$permanent_excluded=1;
			}
                                        //setting
		}*/
		if(!$permanent_excluded)
			write_contents_fta_file($profileid,$username,$gender,$entry_dt,$table_name,$srs_cnt,$phoneNumArray,$forDNC);
	}
	
	fclose($fp1);
        fclose($fp2);
	//////////////////////////////////
        $end_time=date("Y-m-d H:i:s");
        mail("vibhor.garg@jeevansathi.com,lakshay@jeevansathi.com","FTA CSV Generation Completed At $end_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
        /////////////////////////////////

	/*	
	$profileid_file1 = $SITE_URL."/crm/csv_files/ftaCsvCrmData".date('Y-m-d')."DNC.dat";
	$profileid_file2 = $SITE_URL."/crm/csv_files/ftaCsvCrmData".date('Y-m-d')."nonDNC.dat";

        $msg.="\nFor DNC : ".$profileid_file1;
	$msg.="\nFor NonDNC : ".$profileid_file2;

	$to="rohan.mathur@jeevansathi.com,nisha.kumari@jeevansathi.com";
 	$bcc="vibhor.garg@jeevansathi.com,lakshay@jeevansathi.com";
	$sub="FTA CSVs";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";
	*/

	/*live*/
	//mail($to,$sub,$msg,$from);
	/*live*/

        function minimize_fta_data()
        {
        	global $db;
                mysql_ping($db);
 
                // Negative profile list check 
                $sql="delete incentive.TEMP_CSV_FTA_TECH.* from incentive.TEMP_CSV_FTA_TECH , incentive.NEGATIVE_PROFILE_LIST where incentive.TEMP_CSV_FTA_TECH.PROFILEID=incentive.NEGATIVE_PROFILE_LIST.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));
 
                //Profile Allocation Check
                $sql="delete incentive.TEMP_CSV_FTA_TECH.* from incentive.TEMP_CSV_FTA_TECH , incentive.PROFILE_ALLOCATION_TECH b where incentive.TEMP_CSV_FTA_TECH.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

                //Negative treatment list Check
                $sql="delete incentive.TEMP_CSV_FTA_TECH.* from incentive.TEMP_CSV_FTA_TECH , incentive.NEGATIVE_TREATMENT_LIST b where incentive.TEMP_CSV_FTA_TECH.PROFILEID=b.PROFILEID AND b.FLAG_OUTBOUND_CALL='N'";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

	}
	function write_contents_fta_file($profileid,$username,$gender,$entry_dt,$table_name,$srs_cnt,$phoneNumArray,$forDNC)
        {
                 global $db,$fp1,$fp2;
		 $mysql=new Mysql;
                 //Login Frequency       
		 $ts=time();
		 $ts-=90*24*60*60;
	   	 $date=date("Y-m-d",$ts);

		 $myDbName=getProfileDatabaseConnectionName($profileid);
		 $myDb=$mysql->connect("$myDbName");

                 $sql_his = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$profileid' AND LOGIN_DT >= '$date'";
		 $res_his=$mysql->executeQuery($sql_his,$myDb);
		 $row_his=$mysql->fetchArray($res_his);
                 $LOGINCNT=$row_his['CNT'];
                 $date=date("Y-m-d");
                 list($yy,$mm,$dd)=explode("-",$date);
                 $today = mktime(0,0,0,$mm,$dd,$yy);
                 list($b_yy,$b_mm,$b_dd) = explode("-",$entry_dt);
                 $entry_dt = mktime(0,0,0,$b_mm,$b_dd,$b_yy);
                 $days = ($today-$entry_dt);
                 $diff = (int) ($days/(24*60*60)); // find the number of days a user has been registered with us
                 if ($diff >= 90)
                         $diff = 90;
		 $ageInMonths=round($diff*12/365,2);
		 $login_freq_perc=$LOGINCNT/$diff*100;
		 $login_freq_perc=round($login_freq_perc);
                 //ends

		 //EOI rcvd Vs Contact Viewed

		$sql_p="SELECT BILLID FROM billing.PURCHASES p WHERE p.PROFILEID = '$profileid' and p.STATUS='DONE' ORDER BY p.BILLID desc limit 1";
                $res_p=mysql_query_decide($sql_p) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow_p=mysql_fetch_array($res_p);

                $sql_p2="SELECT ss.ACTIVATED_ON,ss.EXPIRY_DT FROM billing.SERVICE_STATUS ss WHERE ss.BILLID='$myrow_p[BILLID]' ORDER BY ss.EXPIRY_DT desc limit 1";
                $res_p2=mysql_query_decide($sql_p2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow_p2=mysql_fetch_array($res_p2);
                $paid_str='';
                if($myrow_p2['EXPIRY_DT'] && $page_mail)
                {
                        $ex_dt= $myrow_p2['EXPIRY_DT']." 23:59:59";
                        $paid_str= "TIME<='$ex_dt'";
                }

		 $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()","","","","","","","$table_name");
                 $RECEIVEDSUM=$contactResult_recdsum[0]["CNT"];
 
                 $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL 90 DAY) AND DATE_SUB(NOW(),INTERVAL 30 DAY)","","","","","","","$table_name");
                 $RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];
 
                 if($paid_str)
                         $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'D'","","$paid_str","","","","","","","$table_name");
                 else
                         $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'D'","","","","","","","","","$table_name");
                 $RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];

		 if($paid_str)
                         $contactResult = getResultSet("SENDER,TIME","","","$profileid","","'A'","","$paid_str");
                 else
                         $contactResult = getResultSet("SENDER,TIME","","","$profileid","","'A'");
 
                 $contacts_accepted_arr =array();
                 for($i=0;$i<count($contactResult);$i++){
                         $temp_arr[] = $contactResult[$i]["TIME"]."+".$contactResult[$i]["SENDER"]."+".$profileid;
                         $contacts_accepted_arr[] =$contactResult[$i]["SENDER"];
                 }
                 if($table_name!='CONTACTS')
                 {
                         if($paid_str)
                                 $contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","$paid_str","","","","","","","$table_name");
                         else
                                 $contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","","","","","","","","$table_name");
                         for($i=0;$i<count($contactResult_recdsum);$i++)
                                 $contacts_accepted_arr[] = $contactResult_recdsum[$i]["SENDER"];
                 }
                 $tot_contacts_accepted =0;
                 $tot_contacts_accepted =count($contacts_accepted_arr);
 
                 $accepted_free_profiles =getFreeProfiles($contacts_accepted_arr);
 
                 $RECEIVEDSUM+=$tot_contacts_accepted;

		 if($_SERVER['DOCUMENT_ROOT'])
		         include_once($_SERVER['DOCUMENT_ROOT']."/profile/ntimes_function.php");
 		else
         		include_once("../web/profile/ntimes_function.php");

 		 $ntimes = ntimes_count($profileid,"SELECT");
			
 
                 if($RECEIVEDSUM && $ntimes)
                          $eoi_rcvd_vs_viewed =round((($RECEIVEDSUM/$ntimes)*100));
		 else 
			$eoi_rcvd_vs_viewed=0;
                 //ends
 			
                 //contacts made and accepted 
		  if($paid_str)
			  $contactResult = getResultSet("RECEIVER,TIME","$profileid","","","","'A'","","$paid_str","","","","","","","$table_name");
                  else
                          $contactResult = getResultSet("RECEIVER,TIME","$profileid","","","","'A'","","","","","","","","","$table_name");
 
                  for($i=0;$i<count($contactResult);$i++)
                          $contact_made_accepted[] = $contactResult[$i]["RECEIVER"];
 
                  if($paid_str)
                  {
                          $contact_made_accepted =array();
                          $contactResult = getResultSet("RECEIVER","$profileid","","","","'A'","","","","","","","","","$table_name");
                          for($i=0;$i<count($contactResult);$i++)
                                  $contact_made_accepted[] = $contactResult[$i]["RECEIVER"];
                  }
 
                 $eoi_accepted =count($contact_made_accepted);
 
                 //Contacts made and Declined
                 $contactResult = getResultSet("RECEIVER","$profileid","","","","'D'","","","","","","","","","$table_name");
                 for($i=0;$i<count($contactResult);$i++)
			 $contact_made_denied[] = $contactResult[$i]["RECEIVER"];
                 $total_contacts_made_denied =count($contact_made_denied);
                 $eoi_declined+=$total_contacts_made_denied;
                 //ends
 
                 //contact made and waiting
                 $contactResult = getResultSet("RECEIVER","$profileid","","","","'I'","","","","","","","","","$table_name");
                 for($i=0;$i<count($contactResult);$i++)
                         $contact_made_initiated[] = $contactResult[$i]["RECEIVER"];
                 $total_contacts_made_initiated =count($contact_made_initiated);
                 $eoi_waiting+=$total_contacts_made_initiated;
                 //Photo Request Recieved
                 $sql_photo="SELECT count(*) as cnt FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$profileid'";
		$result_photo = $mysql->executeQuery($sql_photo,$myDb);
		 $row_photo=$mysql->fetchArray($result_photo);
                 $photo_request =$row_photo['cnt'];
                 //ends
		
		 //sent EOI
		 $eoi_sent=$eoi_waiting+$eoi_declined+$eoi_accepted;

		 $sql_con_view="select count(*) as cnt from jsadmin.VIEW_CONTACTS_LOG where VIEWED='$profileid'  AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
                 $res_con_view=mysql_query_decide($sql_con_view);
                 $row_con_view=mysql_fetch_assoc($res_con_view);
                 $total_con_viewed=$row_con_view['cnt'];

 
                 //Response Booster eligiblity
                 $eligibleForRB=rbEligibilityFlag('N',$eoi_sent,count($contact_made_accepted),$LOGINCNT,$diff,$total_con_viewed,$ageInMonths,$srs_cnt);
		 if($eligibleForRB=="Eligible,If photo is Uploaded")	
			$eligible='Y';
		 else
			$eligible='N';
		 //ends
 
                 //Lead_ID
                 $date_suffix=date("dMy");
                 $lead_id="FTA_".$date_suffix;
 
		// prefix 0 to all phone No.
                 $mobile1=$phoneNumArray['PHONE1'];
                 $mobile2=$phoneNumArray['PHONE2'];
                 $landline=$phoneNumArray['PHONE3'];
		
		 $dialStatus=1;
 
                 $line="$lead_id"."|"."$profileid"."|"."$username"."|"."$login_freq_perc"."|"."$eoi_rcvd_vs_viewed"."|"."$eoi_sent"."|"."$eoi_waiting"."|"."$eoi_declined"."|"."$photo_request"."|"."$gender"."|"."$eligible"."|"."$mobile1"."|"."$mobile2"."|"."$landline"."|"."$dialStatus"."\n";
 
                 if($forDNC)
                 {	
                         $insert=1;
                         fwrite($fp1,$line);
                 }
                 else
                 {
                         $insert=1;
                         fwrite($fp2,$line);
                 }
                 if($insert)
                 {
                         $sql="insert ignore into incentive.IN_DIALER (PROFILEID) VALUES ('$profileid')";
			 mysql_query($sql,$db) or die("$sql".mysql_error($db));
                 }
 
         }
	function isIndianNo($num){
        	if($num && ($num==91 || $num=='0091' || $num=='+91'))
                	return 1;
        	else
                	return 0;
	}
?>
