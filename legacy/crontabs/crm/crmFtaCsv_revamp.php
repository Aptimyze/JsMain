<?php
	$curFilePath = dirname(__FILE__)."/";
        include_once("/usr/local/scripts/DocRoot.php");

       /*********************************For testing comment live portion ******************************************/
        $start_time=date("Y-m-d H:i:s");
        mail("manoj.rana@naukri.com","FTA CSV Revamp Generation Started At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");

        ini_set("max_execution_time","0");
        include ("$docRoot/crontabs/connect.inc");
        include(JsConstants::$docRoot."/classes/globalVariables.Class.php");
	include(JsConstants::$docRoot."/profile/comfunc.inc");
	include(JsConstants::$docRoot."/profile/contacts_functions.php");
	include(JsConstants::$docRoot."/classes/Mysql.class.php");
	include(JsConstants::$docRoot."/crm/mainmenunew.php");
	include_once(JsConstants::$docRoot."/profile/SymfonyPictureFunctions.class.php");
	include("$docRoot/crontabs/crm/allocate_functions_revamp.php");

	$SITE_URL	=JsConstants::$siteUrl;
        $filename1 	=$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ftaCsvCrmRevampData".date('Y-m-d').".dat";
        $fp1 		=fopen($filename1,"w+");
	$today 		=date("Y-m-d");	
	$yesterdayDt 	=date("Y-m-d",strtotime("$today -1 day"));

	$db 	= connect_db();
	//$db_dnc = connect_dnc();
	$db737  = connect_737();
	$exclude_mtongue="3,16,17,31";
        if(!$fp1)
                die("no file pointer");

	$sql="TRUNCATE TABLE incentive.TEMP_CSV_FTA_TECH_REVAMP";
	mysql_query($sql,$db) or die("$sql".mysql_error($db));

	$sql="select PROFILEID,USERNAME,GENDER,AGE,ENTRY_DT,ACTIVATED,HAVEPHOTO,SERIOUSNESS_COUNT,PHONE_MOB,PHONE_WITH_STD from newjs.JPROFILE WHERE ENTRY_DT>='$yesterdayDt 00:00:00' AND ENTRY_DT<='$yesterdayDt 23:59:59' AND MTONGUE NOT IN($exclude_mtongue) AND PHONE_FLAG!='I' AND INCOMPLETE='N' AND ISD IN('91','0091','+91')";
	$res =mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($row=mysql_fetch_array($res))
	{
		$gender 	=$row['GENDER'];
		$age 		=$row['AGE'];
		if($gender=='M' && $age<='23')
			continue;

		$profileid      =$row['PROFILEID'];

		// phone validation start
                $sql_AN = "SELECT ALT_MOBILE from newjs.JPROFILE_CONTACT where PROFILEID ='$profileid'";
                $res_AN = mysql_query($sql_AN,$db737) or die("$sql_AN".mysql_error($db737));
                $row_AN=mysql_fetch_array($res_AN);
                $alternateNum=$row_AN['ALT_MOBILE'];

		$mobile1  	=checkPhoneNumberValidity($row['PHONE_MOB']);
		$mobile2  	=checkPhoneNumberValidity($alternateNum);
		$landline 	=checkPhoneNumberValidity($row['PHONE_WITH_STD']);
		if(!$mobile1 && !$mobile2 && !$landline)
			continue;	
		// phone validation ends

                $username       =$row['USERNAME'];
                $gender         =$row['GENDER'];
                $entry_dt       =$row['ENTRY_DT'];
                $havePhoto      =$row['HAVEPHOTO'];
                $srs_cnt        =$row['SERIOUSNESS_COUNT'];
                $activated      =$row['ACTIVATED'];
	
		$sqlIns ="insert ignore into incentive.TEMP_CSV_FTA_TECH_REVAMP (`PROFILEID`,`USERNAME`,`GENDER`,`ENTRY_DT`,`ACTIVATED`,`HAVEPHOTO`,`SERIOUSNESS_COUNT`,`MOBILE1`,`MOBILE2`,`LANDLINE`) value('$profileid','$username','$gender','$entry_dt','$activated','$havePhoto','$srs_cnt','$mobile1','$mobile2','$landline')";
		mysql_query($sqlIns,$db) or die("$sqlIns".mysql_error($db));	
	}

	// minimize data set	
	minimize_fta_data();

	// Fetch data details
	$sql_pid = "SELECT PROFILEID,USERNAME,GENDER,HAVEPHOTO,SERIOUSNESS_COUNT,ACTIVATED,ENTRY_DT,MOBILE1,MOBILE2,LANDLINE from incentive.TEMP_CSV_FTA_TECH_REVAMP ORDER BY PROFILEID DESC";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	while($row_pid = mysql_fetch_array($res_pid))
	{
		$profileid 	=$row_pid['PROFILEID'];
		$username	=$row_pid['USERNAME'];	
		$gender		=$row_pid['GENDER'];
		$entry_dt	=$row_pid['ENTRY_DT'];	
		$havePhoto	=$row_pid['HAVEPHOTO'];
		$srs_cnt	=$row_pid['SERIOUSNESS_COUNT'];
		$activated	=$row_pid['ACTIVATED'];
		$mobile1	=$row_pid['MOBILE1'];
		$mobile2	=$row_pid['MOBILE2'];
		$landline	=$row_pid['LANDLINE'];
		$phoneNumArr	=array("MOBILE1"=>"$mobile1","MOBILE2"=>"$mobile2","LANDLINE"=>"$landline");

		if($activated=="D")
		        $table_name="DELETED_PROFILE_CONTACTS";
		else
        		$table_name="CONTACTS";

		write_contents_fta_file($profileid,$username,$gender,$entry_dt,$table_name,$srs_cnt,$havePhoto,$phoneNumArr);
	}
	fclose($fp1);
	//////////////////////////////////
        $end_time=date("Y-m-d H:i:s");
        mail("manoj.rana@naukri.com","FTA CSV Revamp Generation Completed At $end_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
        /////////////////////////////////
	
	$profileid_file1 = $SITE_URL."/crm/csv_files/ftaCsvCrmRevampData".date('Y-m-d').".dat";
        $msg.="\nFor FTA : ".$profileid_file1;

	$to="rohan.mathur@jeevansathi.com,shubhda.sinha@jeevansathi.com,pankaj.dubey@jeevansathi.com,mithun.kar@jeevansathi.com";
 	$bcc="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
	//$to ="manoj.rana@naukri.com";
	$sub="FTA CSV Revamp";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	/*live*/
	mail($to,$sub,$msg,$from);
	/*live*/

	// phone number validity check
	function checkPhoneNumberValidity($phoneNum='')
	{
		global $db737;
		if(!$phoneNum)
			return;
		$phoneNumber =phoneNumberCheck($phoneNum);
		if($phoneNumber){
			$sql ="select PHONE_NUM from newjs.PHONE_JUNK WHERE PHONE_NUM IN('$phoneNumber','0$phoneNumber')";
		        $res = mysql_query($sql,$db737) or die("$sql".mysql_error($db737));
		        if($row = mysql_fetch_array($res))
				$phoneJunk =true;
		}
		if($phoneNumber && !$phoneJunk)
			return $phoneNumber;
		return;	
	}

        function minimize_fta_data()
        {
        	global $db;
                mysql_ping($db);
 
                // Negative profile list filter 
                $sql="delete incentive.TEMP_CSV_FTA_TECH_REVAMP.* from incentive.TEMP_CSV_FTA_TECH_REVAMP , incentive.NEGATIVE_PROFILE_LIST where incentive.TEMP_CSV_FTA_TECH_REVAMP.PROFILEID=incentive.NEGATIVE_PROFILE_LIST.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));
 
                //Do not call filter
                $sql="delete incentive.TEMP_CSV_FTA_TECH_REVAMP.* from incentive.TEMP_CSV_FTA_TECH_REVAMP,incentive.DO_NOT_CALL b where incentive.TEMP_CSV_FTA_TECH_REVAMP.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

                //Negative treatment list filter
                $sql="delete incentive.TEMP_CSV_FTA_TECH_REVAMP.* from incentive.TEMP_CSV_FTA_TECH_REVAMP,incentive.NEGATIVE_TREATMENT_LIST b where incentive.TEMP_CSV_FTA_TECH_REVAMP.PROFILEID=b.PROFILEID AND b.FLAG_OUTBOUND_CALL='N'";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

		// Ever paid check
                $sql="delete incentive.TEMP_CSV_FTA_TECH_REVAMP.* from incentive.TEMP_CSV_FTA_TECH_REVAMP,billing.PURCHASES b where incentive.TEMP_CSV_FTA_TECH_REVAMP.PROFILEID=b.PROFILEID AND b.STATUS='DONE' AND b.MEMBERSHIP='Y'";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

                // LTF Check added
                $sql="delete incentive.TEMP_CSV_FTA_TECH_REVAMP.* from incentive.TEMP_CSV_FTA_TECH_REVAMP,MIS.LTF b where incentive.TEMP_CSV_FTA_TECH_REVAMP.PROFILEID=b.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

	}
	function write_contents_fta_file($profileid,$username,$gender,$entry_dt,$table_name,$srs_cnt,$havePhoto,$phoneNumArr)
        {
                 global $db,$fp1;
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
                 //Login Frequency ends

		 //EOI rcvd Vs Contact Viewed
		 $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()","","","","","","","$table_name");
                 $RECEIVEDSUM=$contactResult_recdsum[0]["CNT"];
 
                 $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL 90 DAY) AND DATE_SUB(NOW(),INTERVAL 30 DAY)","","","","","","","$table_name");
                 $RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];
 
                 $contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'D'","","","","","","","","","$table_name");
                 $RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];

                 $contactResult = getResultSet("SENDER,TIME","","","$profileid","","'A'");
 
                 $contacts_accepted_arr =array();
                 for($i=0;$i<count($contactResult);$i++){
                         $temp_arr[] = $contactResult[$i]["TIME"]."+".$contactResult[$i]["SENDER"]."+".$profileid;
                         $contacts_accepted_arr[] =$contactResult[$i]["SENDER"];
                 }
                 if($table_name!='CONTACTS')
                 {
                         $contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","","","","","","","","$table_name");
                         for($i=0;$i<count($contactResult_recdsum);$i++)
                                 $contacts_accepted_arr[] = $contactResult_recdsum[$i]["SENDER"];
                 }
                 $tot_contacts_accepted =0;
                 $tot_contacts_accepted =count($contacts_accepted_arr);
 			
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
                 $contactResult = getResultSet("RECEIVER,TIME","$profileid","","","","'A'","","","","","","","","","$table_name");
 
                  for($i=0;$i<count($contactResult);$i++)
                          $contact_made_accepted[] = $contactResult[$i]["RECEIVER"];
 
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

		// Photo Upload Status
		if($havePhoto=='Y')
			$photoUploadStatus ='Y';
		elseif($havePhoto=='U')
			$photoUploadStatus ='S';
		else
			$photoUploadStatus ='N';
		// ends
		
		 //sent EOI
		 $eoi_sent=$eoi_waiting+$eoi_declined+$eoi_accepted;

		 $sql_con_view="select count(*) as cnt from jsadmin.VIEW_CONTACTS_LOG where VIEWED='$profileid'";
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

		// phone Numbers with prefix 0
		$mobile1 =$phoneNumArr['MOBILE1'];
		$mobile2 =$phoneNumArr['MOBILE2']; 
		$landline=$phoneNumArr['LANDLINE'];
		if($mobile1)
	                $mobile1 ="0".$phoneNumArr['MOBILE1'];
		if($mobile2)
	                $mobile2 ="0".$phoneNumArr['MOBILE2'];
		if($landline)
        	        $landline="0".$phoneNumArr['LANDLINE'];
			
                 //Lead_ID Set
                 $lead_id="FTA_Revamp_1";
		 $dialStatus=1;
 
		 $line="$lead_id"."|"."$profileid"."|"."$username"."|"."$photoUploadStatus"."|"."$login_freq_perc"."|"."$eoi_rcvd_vs_viewed"."|"."$eoi_sent"."|"."$eoi_waiting"."|"."$eoi_declined"."|"."$photo_request"."|"."$gender"."|"."$eligible"."|"."$mobile1"."|"."$mobile2"."|"."$landline"."|"."$dialStatus"."\n";

                 fwrite($fp1,$line);
         }
?>
