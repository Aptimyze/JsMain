<?php 
//Connection at JSDB
/*include_once("../../web/profile/connect_db.php");
$db_js = connect_db();*/
$db_js = mysql_connect("ser2.jeevansathi.com","user_dialer","DIALlerr") or die("Unable to connect to js server");
mysql_query('set session wait_timeout=50000',$db_js);

//Compute all the active campaigns
$camp_array = array();
$sqlc= "SELECT CAMPAIGN FROM incentive.CAMPAIGN WHERE ACTIVE = 'F'";
$resc=mysql_query($sqlc,$db_js) or die($sqlc.mysql_error($db_js));
while($myrowc = mysql_fetch_array($resc))
	$camp_array[] = $myrowc["CAMPAIGN"];

//Compute all non-eligible profiles
global $ignore_array;
global $eligible_array;
$sql = "SELECT PROFILEID,ELIGIBLE,PRIORITY FROM incentive.FTA_IN_DIALER";
$res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
while($row = mysql_fetch_array($res))
{
	if($row['ELIGIBLE']=='N')
	{
		if($row["PRIORITY"]==5)
			$score_array[] = $row["PROFILEID"];
		$ignore_array[] = $row["PROFILEID"];
	}
	else
		$eligible_array[] = $row["PROFILEID"];
}
if(count($camp_array)>0)
{
	//Connection at DialerDB
    	$db_dialer = mssql_connect("dailer.jeevansathi.com","easy","G0dblessyou") or die("Unable to connect to dialer server");
	
	/*Stop non-eligible profiles*/
	echo "/////////////////Part-1//////////////////"."\n";
	for($i=0;$i<count($camp_array);$i++)
	{
		$campaign_name = $camp_array[$i];
		echo "/////////////////".$campaign_name."//////////////////"."\n";

		$squery1 = "SELECT easycode,Profile_ID,Dial_status FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0";
		$sresult1 = mssql_query($squery1,$db_dialer) or logerror($squery1,$db_dialer);
		while($srow1 = mssql_fetch_array($sresult1))	
		{
			$ecode = $srow1["easycode"];
			$proid = $srow1["Profile_ID"];
			if($srow1["Dial_status"]!='0' && in_array($proid,$ignore_array))
			{
				echo $query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_status='0' WHERE easycode='$ecode'";
				echo "\n";
				mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer);
			}
		}

    	}

    	/*Update data of eligible profiles*/
	echo "/////////////////Part-2//////////////////"."\n";
	//Update dial status for those having registered on 6,16,31,61,91st day from now
	for($e=0;$e<count($eligible_array);$e++)
	{
		$prid = $eligible_array[$e]; 
		echo $sql_dialer="UPDATE easy.dbo.ct_$campaign_name SET Dial_status=1 WHERE DATEDIFF(day,Reg_Date,getdate()) IN (5,15,30,60,90) and Dial_status IN (0,2) and score!=5 and Profile_ID = $prid";echo "\n";
		mssql_query($sql_dialer,$db_dialer) or logerror($sql_dialer,$db_dialer);
	}

	/*Update data of eligible profiles*/
	echo "/////////////////Part-3//////////////////"."\n";
        for($i=0;$i<count($camp_array);$i++)
        {
                $campaign_name = $camp_array[$i];
                echo "//////////".$campaign_name."//////////"."\n";

		$squery2 = "SELECT easycode,Profile_ID,User_Photo,Phone_vrfy,Login_frequency,Sent_Eoi_Awaiting,Sent_Eoi_Declined,Photo_Requests,PHONE_NO1,PHONE_NO2,PHONE_NO3,score FROM easy.dbo.ct_$campaign_name WHERE Dial_status=1";
                $sresult2 = mssql_query($squery2,$db_dialer) or logerror($squery2,$db_dialer);		
                while($srow1 = mssql_fetch_array($sresult2))
		{
			$ecode = $srow1["easycode"];
			$proid=$dialer_data["PROFILEID"] = $srow1["Profile_ID"];
			$dialer_data["PHONE_NO1"] = $srow1["PHONE_NO1"];
			$dialer_data["PHONE_NO2"] = $srow1["PHONE_NO2"];
			$dialer_data["PHONE_NO3"] = $srow1["PHONE_NO3"];
			//$dialer_data["Free_RB_Eligibility"] = $srow1["Free_RB_Eligibility"];
			//$dialer_data["Sent_Eoi_Awaiting"] = $srow1["Sent_Eoi_Awaiting"];
			//$dialer_data["Sent_Eoi_Declined"] = $srow1["Sent_Eoi_Declined"];
			//$dialer_data["Prcnt_Eoi_Rcvd"] = $srow1["Prcnt_Eoi_Rcvd"];
			$dialer_data["Phone_vrfy"] = $srow1["Phone_vrfy"];	
			//$dialer_data["Login_frequency"] = $srow1["Login_frequency"];	
			$dialer_data["User_Photo"] = $srow1["User_Photo"];
			//$dialer_data["Photo_Requests"] = $srow1["Photo_Requests"];
			$dialer_data["score"] = $srow1["score"];
			
			if(in_array($proid,$eligible_array) || in_array($proid,$score_array))
			{
				$query1 = "";
				@mysql_ping($db_js);
				$jp_condition=data_comparision($dialer_data,$db_js,$campaign_name);
				if($jp_condition!='ignore')
				{
					echo $query1 = "UPDATE easy.dbo.ct_$campaign_name SET $jp_condition WHERE easycode='$ecode'";echo "\n";
					mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer);
					echo $queryp = "UPDATE easy.dbo.ph_contact SET priority=score FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and code='$ecode' and Dial_Status='1'";
					mssql_query($queryp,$db_dialer) or logerror($queryp,$db_dialer);
				}
			}
			unset($dialer_data);
		}
	}
	
	/*Update data of eligible profiles*/
        echo "/////////////////Part-4//////////////////"."\n";
        //Update dial status for those whose score is 5
        //echo $sql_dialer="UPDATE easy.dbo.ct_$campaign_name SET Dial_status=0 WHERE score=5";echo "\n";
	echo $sql_dialer = "UPDATE easy.dbo.ct_$campaign_name SET Dial_status=0 FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and priority=5";
        mssql_query($sql_dialer,$db_dialer) or logerror($sql_dialer,$db_dialer);
}

function data_comparision($dialer_data,$db_js,$campaign_name)
{
	@mysql_ping($db_js);
	//$mysql=new Mysql;
	$profileid = $dialer_data["PROFILEID"];
	//$myDbName=getProfileDatabaseConnectionName($profileid);
	//$myDb=$mysql->connect("$myDbName");
	//$eoi_declined=0;
	//$eoi_waiting=0;
	$update_str='';

	//JPROFILE Checks
 	$sql_jp="select PHONE_MOB,PHONE_WITH_STD,HAVEPHOTO,MOB_STATUS,LANDL_STATUS,ENTRY_DT,SERIOUSNESS_COUNT from newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res_jp = mysql_query($sql_jp,$db_js) or die("$sql_ma".mysql_error($db_js));
	while($row_jp = mysql_fetch_array($res_jp))
	{
		$updatedData["PHONE_NO1"]=phoneNumberCheck($row_jp["PHONE_MOB"]);
		$updatedData["PHONE_NO3"]=phoneNumberCheck($row_jp["PHONE_WITH_STD"]);
		if($row_jp["MOB_STATUS"]=='Y'||$row_jp["LANDL_STATUS"]=="Y")
			$updatedData["Phone_vrfy"]="Y";
		else 
			$updatedData["Phone_vrfy"]="N";	
		if($row_jp["HAVEPHOTO"]=='Y')
			$updatedData["User_Photo"]="Yes";
		else if($row_jp["HAVEPHOTO"]=='U')
			$updatedData["User_Photo"]="Scrn";
		else
			$updatedData["User_Photo"]="No";	
		$entryDt=$row_jp["ENTRY_DT"];
		$srs_cnt=$row_jp["SERIOUSNESS_COUNT"];
	}
	
	//ALternate Mobile
	$sql_AN = "SELECT ALT_MOBILE,ALT_MOB_STATUS from newjs.JPROFILE_CONTACT where PROFILEID = '$profileid'";
	$res_AN = mysql_query($sql_AN,$db_js) or die("$sql_AN".mysql_error($db));  
	$row_AN=mysql_fetch_array($res_AN);
	$updatedData["PHONE_NO2"]=phoneNumberCheck($row_AN['ALT_MOBILE']);
	if($row_AN['ALT_MOB_STATUS']=='Y')
		$updatedData["Phone_vrfy"]="Y";
		
	//Photo Request Recieved
	/*$sql_photo="SELECT count(*) as cnt FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$profileid'";
	$result_photo = $mysql->executeQuery($sql_photo,$myDb);
	$row_photo=$mysql->fetchArray($result_photo);
	$updatedData["Photo_Requests"] =$row_photo['cnt'];*/
	//ends

	//Login Frequency       
	 /*$date=date("Y-m-d",time()-90*24*60*60);
	 $sql_his = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$profileid' AND LOGIN_DT >= '$date'";
	 $res_his=$mysql->executeQuery($sql_his,$myDb);
	 $row_his=$mysql->fetchArray($res_his);
	 $LOGINCNT=$row_his['CNT'];
	 $now=time();
	 $entryDt=strtotime($entryDt);
	 $days = ($now-$entryDt);
	 // find the number of days a user has been registered with us
	 $diff = (int) ($days/(24*60*60)); 
	 if ($diff >= 90)
			 $diff = 90;
	 $ageInMonths=round($diff*12/365,2);
	 $login_freq_perc=$LOGINCNT/$diff*100;
	 $login_freq_perc=round($login_freq_perc);
	 $updatedData["Login_frequency"]=$login_freq_perc;*/
	 //ends
	 
	//EOI rcvd Vs Contact Viewed
	/*$sql_p="SELECT BILLID FROM billing.PURCHASES p WHERE p.PROFILEID = '$profileid' and p.STATUS='DONE' ORDER BY p.BILLID desc limit 1";
	$res_p=mysql_query($sql_p,$db_js) or die("$sql_p".mysql_error($db_js));
	$myrow_p=mysql_fetch_array($res_p);

	$sql_p2="SELECT ss.ACTIVATED_ON,ss.EXPIRY_DT FROM billing.SERVICE_STATUS ss WHERE ss.BILLID='$myrow_p[BILLID]' ORDER BY ss.EXPIRY_DT desc limit 1";
	$res_p2=mysql_query($sql_p2,$db_js) or die("$sql_p2".mysql_error($db_js));
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
		
	//Contacts made and Accepted
	 $contactResult = getResultSet("RECEIVER","$profileid","","","","'A'","","","","","","","","","$table_name");
	 for($i=0;$i<count($contactResult);$i++)
		$contact_made_accepted[] = $contactResult[$i]["RECEIVER"];
	 $total_contacts_made_accepted =count($contact_made_accepted);
	 $eoi_accepted+=$total_contacts_made_accepted;
	 //ends
	 
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
	 
	 //sent EOI
	 $eoi_sent=$eoi_waiting+$eoi_declined+$eoi_accepted;
	 //ends
	 
	 $sql_con_view="select count(*) as cnt from jsadmin.VIEW_CONTACTS_LOG where VIEWED='$profileid'";
	 $res_con_view=mysql_query($sql_con_view,$db_js);
	 $row_con_view=mysql_fetch_assoc($res_con_view);
	 $total_con_viewed=$row_con_view['cnt'];
	 
	 //Response Booster eligiblity
	 $eligibleForRB=rbEligibilityFlag('N',$eoi_sent,count($contact_made_accepted),$LOGINCNT,$diff,$total_con_viewed,$ageInMonths,$srs_cnt);
	 if($eligibleForRB=="Eligible,If photo is Uploaded")	
		 $updatedData["Free_RB_Eligibility"]='Y';
	 else
		 $updatedData["Free_RB_Eligibility"]='N';
	 //ends
	
	$updatedData["Sent_Eoi_Awaiting"]=$eoi_waiting;
	$updatedData["Sent_Eoi_Declined"]=$eoi_declined;
	$updatedData["Prcnt_Eoi_Rcvd"]=$eoi_rcvd_vs_viewed;*/
	
	//PRIORITY UPDATE
	@mysql_ping($db_js);
	$sql_priority="SELECT PRIORITY FROM incentive.FTA_IN_DIALER WHERE PROFILEID='$profileid'"; 
	$res_priority=mysql_query($sql_priority,$db_js) or mysql_error($sql_priority);
	$row_priority=mysql_fetch_array($res_priority);
	$updatedData["score"]=$row_priority["PRIORITY"];
	
	foreach($dialer_data as $key=>$value)
	{
		if($key!="PROFILEID")
		{
			if($value!=$updatedData[$key])
			{
				if($update_str=="")
					$update_str="$key='$updatedData[$key]'";
				else
					$update_str.=",$key='$updatedData[$key]'";
			}
		}
	}
	if($update_str!='')
		return $update_str;
	else
		return "ignore";
}

function phoneNumberCheck($phoneNumber)
{
	$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
	$phoneNumber    =ltrim($phoneNumber,0);
	if(!is_numeric($phoneNumber))
		return false;
	if(strlen($phoneNumber)!=10)
		return false;
	return $phoneNumber;
}

function logerror($sql="",$db="",$ms)
{
	$today=@date("Y-m-d h:m:s");
	$filename="logerror.txt";
	if(is_writable($filename))
	{
		if (!$handle = fopen($filename, 'a'))
		{
			echo "Cannot open file ($filename)";
			exit;
		}
		if($ms)
			fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mssql_get_last_message(). " \t $today");
		else
			fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mysql_error(). " \t $today");
		fclose($handle);
	}
	else
	{
		echo "The file $filename is not writable";
	}
}
?>
