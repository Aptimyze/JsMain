<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


	ini_set("max_execution_time","0");
	$flag_using_php5 = 1;
	//include ("../connect.inc");
	include("allocate_functions.php");
	include("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");
	include("$_SERVER[DOCUMENT_ROOT]/profile/comfunc.inc");
	//include ("connect.inc");

	if($_SERVER['DOCUMENT_ROOT'])
	{
		include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
		include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
		include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
		include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	}
	else
	{
		$path =$_SERVER[DOCUMENT_ROOT];
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

	$db = connect_db();

	$ts = time();
	$ts -= 31*24*60*60;
	$last_day = date("Y-m-d",$ts);

	$ts1 = time();
	$ts1 -= 2*24*60*60;
	$start_date = date("Y-m-d",$ts1)." 00:00:00";
	$end_date = date("Y-m-d",$ts1)." 23:59:59";

	$sql_mt = "SELECT * FROM incentive.REGION_MTONGUE_MAPPING WHERE REGION='S'";
	$res_mt = mysql_query($sql_mt,$db) or die($sql_mt.mysql_error($db));
	while($row_mt = mysql_fetch_array($res_mt))
		$south_arr = explode(",",$row_mt['MTONGUE']);

	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"\n";

        $filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_new_register_south.txt";
        $filename2 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_new_register_roi.txt";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");

        if(!$fp1 || !$fp2)
                die("no file pointer");

        fwrite($fp1,$header);
        fwrite($fp2,$header);
	
	unset($pidarr);
	unset($pidstr);

	//finding user's who tried payment.
	$sql = "SELECT DISTINCT PROFILEID FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' UNION SELECT DISTINCT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND PAGE>1";
	$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($myrow=mysql_fetch_array($myres))
		$pidarr[]=$myrow['PROFILEID'];

	//$mtongue_str = "'".@implode("','",$south_arr)."'";

	$sql = "SELECT PROFILEID, ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND COUNTRY_RES=51 AND INCOMPLETE='N' AND SUBSCRIPTION='' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
	if(is_array($pidarr))
	{
		$pidstr = @implode("','",$pidarr);
		$sql .= " AND PROFILEID NOT IN ('$pidstr')";
	}
	unset($pidarr);
	unset($pidstr);
	$res = mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($row = mysql_fetch_array($res))
	{
		unset($allow);

		$profileid = $row['PROFILEID'];
		$sql_pid = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE TIMES_TRIED<3 AND PROFILEID='$profileid'";
		$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
		if($row_pid = mysql_fetch_array($res_pid))
		{
			$mtongue = $row['MTONGUE'];
			if(!profile_allocated($profileid))
			{
				$score = $row_pid['SCORE'];

				//$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
				//$res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error($db));
				if(0)//$row_history = mysql_fetch_array($res_history))
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

				if(!check_profile($profileid))
					$allow=0;

				if($allow)
				{
					$PHONE_RES = $row['PHONE_RES'];
					if($PHONE_RES)
						$PHONE_RES=$row['STD'].$PHONE_RES;
					if(substr($PHONE_RES,0,1)==0)
						$PHONE_RES = substr($PHONE_RES,1);
					$PHONE_RES = substr($PHONE_RES,-10);
					if(strlen($PHONE_RES)<10)
						$PHONE_RES="";

					//added by sriarm on June 12 2007 to show alternate number (if any)
					$sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$profileid' ORDER BY ID DESC LIMIT 1";
					$res_alt = mysql_query($sql_alt,$db) or die("$sql_alt".mysql_error($db));
					if($row_alt = mysql_fetch_array($res_alt))
						$PHONE_MOB = $row_alt['ALTERNATE_NUMBER'];
					else
						$PHONE_MOB = $row['PHONE_MOB'];
					//end of - added by sriarm on June 12 2007 to show alternate number (if any)

					if(substr($PHONE_MOB,0,1)==0)
						$PHONE_MOB = substr($PHONE_MOB,1);
					$PHONE_MOB = substr($PHONE_MOB,-10);
					if(strlen($PHONE_MOB)<10)
						$PHONE_MOB="";
					$PHOTO = $row['HAVEPHOTO'];
					$ENTRY_DT = $row['ENTRY_DT'];

					$city =  $row['CITY_RES'];
					$subcity=substr($city,0,2);

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

						if($country=='51')
						{
							$city_label=label_select('CITY_INDIA',$city);
							$CITY_RES=$city_label[0];
						}
						elseif($country=='128')
						{
							$city_label=label_select('CITY_USA',$city);
							$CITY_RES=$city_label[0];
						}
						else
							$CITY_RES='NA';

						/************* Sharding done by Sadaf : start********************/
						unset($myDb);
						unset($myDbName);
						$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
						$mysqlObj->ping($myDbArray[$myDbName]);
						$myDb=$myDbArray[$myDbName];
						if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
							$DPP=1;
						else
						{
							$DPP=0;
							$HAVEPARTNER='N';
						}

						//echo $profileid."    ".$myDbName."    ".$myDb."     ".$DPP."\n";
						/************** Sharding done by Sadaf : end***********************/

						// member as filled in his/her desired partner profile
						if ($DPP ==  1)
							$HAVEPARTNER = 'Y';
						else
							$HAVEPARTNER = 'N';

						$PROFILELENGTH=$row['PROFILELENGTH'];

						$LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

						// cretaing content to be written to the file
						if ($PHONE_MOB && $PHONE_RES)
						{
							$line="\"$profileid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
						}
						elseif ($PHONE_MOB && $PHONE_RES =='')
						{
							$line="\"$profileid\"".","."\"$PHONE_MOB\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
						}
						elseif ($PHONE_MOB =='' && $PHONE_RES)
						{
							$line="\"$profileid\"".","."\"$PHONE_RES\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
						}

						$data = trim($line)."\n";
						$output = $data;
						unset($data);
						unset($DPP);

						// writing content to file
						if(@in_array($mtongue,$south_arr))
							fwrite($fp1,$output);
						else
							fwrite($fp2,$output);
					}
				}
			}
		}
	}
	fclose($fp1);
	fclose($fp2);

	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_new_register_south.txt";
	$profileid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_new_register_roi.txt";

	$msg="For newly registered south community profiles: ".$profileid_file1;
	$msg.="\nFor newly registered profiles(roi): ".$profileid_file2;

	$to="anamika.singh@jeevansathi.com,surbhi.aggarwal@naukri.com,mandeep.choudhary@naukri.com,divya.jain@naukri.com,deepak.sharma@naukri.com, samrat.chadha@naukri.com";
	$bcc="shiv.narayan@jeevansathi.com, sriram.viswanathan@jeevansathi.com";
	$sub="Daily CSV for calling";
	$from="From:shiv.narayan@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	mail($to,$sub,$msg,$from);
?>
