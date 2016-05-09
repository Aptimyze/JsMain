<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//die("This is temporarily down. Kindly Recheck shortly");
ini_set("max_execution_time","0");

$flag_using_php5 = 1;

include("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");
include("$_SERVER[DOCUMENT_ROOT]/profile/comfunc.inc");
include("allocate_functions.php");

if($_SERVER['DOCUMENT_ROOT'])
{
	include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
}
else
{
	$path =$_SERVER['DOCUMENT_ROOT'];
	include("$path/classes/Memcache.class.php");
	include("$path/classes/globalVariables.Class.php");
	include("$path/classes/Mysql.class.php");
	include("$path/classes/Jpartner.class.php");
}

$mysqlObj=new Mysql;
for($i=0;$i<$noOfActiveServers;$i++)
{
	$myDbName=$activeServers[$i];
	$myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
}

$jpartnerObj=new Jpartner;

$ts=time();
$ts-=30*24*60*60;
$last_day=date("Y-m-d",$ts);

	$db = connect_737();

	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"".","."\"EVER_PAID\"\n";

	$filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_usa.txt";
	$filename2 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_uk.txt";

	$fp1 = fopen($filename1,"w+");
	$fp2 = fopen($filename2,"w+");

	if(!$fp1 || !$fp2)
	{
		die("no file pointer");
	}

	fwrite($fp1,$header);
	fwrite($fp2,$header);

	$ts=time();
	$ts-=15*24*60*60;
	$start_date = date("Y-m-d",$ts) ." 00:00:00";

	// query to find details of users who have registered one week back and whose profiles are active 
	$sql = "SELECT PROFILEID,ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND SUBSCRIPTION='' AND ENTRY_DT < '$start_date' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY) AND COUNTRY_RES IN ('126','128')";
	$res = mysql_query($sql,$db) or logError(mysql_error($db));//die("$sql".mysql_error($db));
	while($row = mysql_fetch_array($res))
	{
		$pid = $row['PROFILEID'];

		unset($allow);
		$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$pid' ORDER BY ID DESC LIMIT 1";
		$res_history = mysql_query($sql_history,$db) or logError(mysql_error($db));//die("$sql_history".mysql_error($db));
		if($row_history = mysql_fetch_array($res_history))
		{
			// profile has been handled once
			if($row_history['ENTRY_DT']<=$last_day)
				$allow=1;
		}
		else
		{
			// new profile
			$allow=1;
		}
		if(check_profile($pid))
			$allow = 1;

		if($allow)	
		{
			$sql_pid = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$pid' AND ALLOTMENT_AVAIL='Y' AND TIMES_TRIED<3";
			$res_pid = mysql_query($sql_pid,$db) or logError(mysql_error($db));//die("$sql_pid".mysql_error($db));
			$row_pid = mysql_fetch_array($res_pid);
			if ($row_pid = mysql_fetch_array($res_pid))
			{
				$score = $row_pid['SCORE'];

				$COUNTRY_RES = $row['COUNTRY_RES'];

				$PHONE_RES = $row['PHONE_RES'];
				if($PHONE_RES)
					$PHONE_RES=$row['STD'].$PHONE_RES;
				if(substr($PHONE_RES,0,1)==0)
					$PHONE_RES = substr($PHONE_RES,1);
				$PHONE_RES = substr($PHONE_RES,-10);
				if(strlen($PHONE_RES)<10)
					$PHONE_RES="";

				//added by sriarm on June 12 2007 to show alternate number (if any)
				$sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$pid' ORDER BY ID DESC LIMIT 1";
				$res_alt = mysql_query($sql_alt,$db) or logError(mysql_error($db));//die("$sql_alt".mysql_error($db));
				if($row_alt = mysql_fetch_array($res_alt))
					$PHONE_MOB = $row_alt['ALTERNATE_NUMBER'];
				else
					$PHONE_MOB = $row['PHONE_MOB'];
				//end of - added by sriarm on June 12 2007 to show alternate number (if any)

				/*if(substr($PHONE_MOB,0,1)==0)
					$PHONE_MOB = substr($PHONE_MOB,1);
				$PHONE_MOB = substr($PHONE_MOB,-10);
				if(strlen($PHONE_MOB)<10)
					$PHONE_MOB="";*/
				$PHOTO = $row['HAVEPHOTO'];
				$ENTRY_DT = $row['ENTRY_DT'];

				$city =  $row['CITY_RES'];

				if((trim($PHONE_RES) || trim($PHONE_MOB)))
				{
					//Code added by Vibhor for sharding of CONTACTS
                                        include_once("$_SERVER[DOCUMENT_ROOT]/profile/contacts_functions.php");
                                        // query to find count of contacts made and accepted
                                        $contactResultRA=getResultSet("count(*) as count","","",$profileid,"","'A'");
                                        $ACCEPTANCE_RCVD=$contactResultRA[0]['count'];

                                        // query to find count of contacts initiated by user and accepted
                                        $contactResultSA=getResultSet("count(*) as count",$profileid,"","","","'A'");
                                        $ACCEPTANCE_MADE=$contactResultSA[0]['count'];

                                        //query to find count of contacts received and not responded yet.
                                        $contactResultRI=getResultSet("count(*) as count","","",$profileid,"","'I'");
                                        $RECEIVE_CNT=$contactResultRI[0]['count'];

                                        //query to find count of contacts initiated by user and has not been responded.
                                        $contactResultSI=getResultSet("count(*) as count",$profileid,"","","","'I'");
                                        $INITIATE_CNT=$contactResultSI[0]['count'];
                                        //end

					$DOB = $row['DTOFBIRTH'];

					$posted = $row['RELATION'];
					switch($posted)
					{
						case '1': $POSTEDBY='Self';
							  break;
						case '2': $POSTEDBY='Parent/Guardian';
							  break;
						case '3': $POSTEDBY='Sibling';
							  break;
						case '4': $POSTEDBY='Friend';
							  break;
						case '5': $POSTEDBY='Marriage Bureau';
							  break;
						case '6': $POSTEDBY='Other';
							  break;
					}

					if($row['GENDER']=='F')
						$GENDER='Female';
					else
						$GENDER='Male';

					$caste = $row['CASTE'];
					$caste_label= label_select('CASTE',$caste);
					$CASTE=$caste_label[0];

					$mtongue = $row['MTONGUE'];
					$mtongue_label= label_select('MTONGUE',$mtongue);
					$MTONGUE= $mtongue_label[0];

					$country =  $row['COUNTRY_RES'];
					$country_label = label_select('COUNTRY',$country);
					$COUNTRY= $country_label[0];

					$city_label=label_select('CITY_USA',$city);
					$CITY_RES=$city_label[0];

					/*****************Sharding done by Sadaf : start************************/
					unset($myDb);
					unset($myDbName);
					$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
					$mysqlObj->ping($myDbArray[$myDbName]);
					$myDb=$myDbArray[$myDbName];
					if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$pid))
						$DPP=1;
					else
					{
						$DPP=0;
						$HAVEPARTNER='N';
					}
					/******************Sharding done by Sadaf : end**************************/

					if ($DPP ==  1) // member as filled in his/her desired partner profile
						$HAVEPARTNER = 'Y';
					else
						$HAVEPARTNER = 'N';

					$PROFILELENGTH = strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);

					$LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

					//section added to check weather the profile has ever had any subscription.
					$sql_p = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$pid' AND STATUS='DONE'";
					$res_p = mysql_query($sql_p,$db) or logError(mysql_error($db));//die("$sql_p".mysql_error($db));
					$row_p = mysql_fetch_array($res_p);
					if($row_p['COUNT'] > 0)
						$EVER_PAID = "Y";
					else
						$EVER_PAID = "N";

					// cretaing content to be written to the file
					if ($PHONE_MOB && $PHONE_RES)
					{
						$line="\"$pid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
					}
					elseif ($PHONE_MOB && $PHONE_RES =='')
					{
						$line="\"$pid\"".","."\"$PHONE_MOB\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
					}
					elseif ($PHONE_MOB =='' && $PHONE_RES)
					{
						$line="\"$pid\"".","."\"$PHONE_RES\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
					}

					$data = trim($line)."\n";
					$output = $data;
					//echo $data;
					unset($data);
					unset($DPP);
					// writing content to file

					if("128"==$COUNTRY_RES)
						fwrite($fp1,$output);
					elseif("126"==$COUNTRY_RES)
						fwrite($fp2,$output);
				}
			 }
		}
	}

	fclose($fp1);
	fclose($fp2);

	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_usa.txt";
	$profileid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_uk.txt";

	$msg="\nFor USA : ".$profileid_file1;
	$msg.="\nFor UK : ".$profileid_file2;

	$to="anamika.singh@jeevansathi.com,surbhi.aggarwal@naukri.com,mandeep.choudhary@naukri.com,divya.jain@naukri.com,shiv.narayan@jeevansathi.com";
	$bcc="shiv.narayan@jeevansathi.com, sriram.viswanathan@jeevansathi.com";
	$sub="Daily CSV for calling";
	$from="From:shiv.narayan@jeevansathi.com";

	mail($to,$sub,$msg,$from);
?>
