<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	function check_profile($profileid,$extra_params="")
	{
		global $db;
		$sql_dnt_call = "SELECT COUNT(*) AS COUNT FROM incentive.DO_NOT_CALL WHERE PROFILEID='$profileid' AND REMOVED='N'";
		$res_dnt_call = mysql_query($sql_dnt_call,$db) or die("$sql_dnt_call".mysql_error($db));
		$row_dnt_call = mysql_fetch_array($res_dnt_call);

		$sql_invalid = "SELECT COUNT(*) AS COUNT from incentive.INVALID_PHONE WHERE PROFILEID='$profileid'";
		$res_invalid = mysql_query($sql_invalid,$db) or die("$sql_invalid".mysql_error($db));
		$row_invalid = mysql_fetch_array($res_invalid);

		if($extra_params["SERVICE_CALL"] == "1")
		{
			$allot_in_csv = 0;
			//query to find whether the user has been handled already or the user has been marked as not connected.
			$sql_md = "SELECT COMFORT FROM incentive.SERVICE_ADMIN WHERE PROFILEID = '$profileid'";
			$res_md = mysql_query($sql_md,$db) or die("$sql_md".mysql_error($db));
			if($row_md = mysql_fetch_array($res_md))
			{
				if($row_md["COMFORT"] == "NC")
					$allot_in_csv = 1;
				else
					$allot_in_csv = 0;
			}
			else
				$allot_in_csv = 1;

			if($allot_in_csv)
				$row_md["COUNT"] = 0;
			else
				$row_md["COUNT"] = 1;

			$row_check["COUNT"] = 0;
		}
		else
		{
			$sql_md = "SELECT COUNT(*) AS COUNT from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
			$res_md = mysql_query($sql_md,$db) or die("$sql_md".mysql_error($db));
			$row_md = mysql_fetch_array($res_md);

			$sql_check = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
			$res_check = mysql_query($sql_check,$db) or die("$sql_check".mysql_error($db));
			$row_check = mysql_fetch_array($res_check);
		}

		$sql_offline= "SELECT COUNT(*) AS COUNT FROM newjs.JPROFILE WHERE PROFILEID= '$profileid' AND SOURCE= 'OFL_PROF'";
		$res_offline = mysql_query($sql_offline,$db) or die("$sql_offline".mysql_error($db));
		$row_offline = mysql_fetch_array($res_offline);

		if($row_dnt_call['COUNT']==0 && $row_invalid['COUNT']==0 && $row_md['COUNT']==0 && $row_check['COUNT']==0 && $row_offline['COUNT']==0)
			return 1;
		else
			return 0;
	}

	function profile_allocated($profileid,$extra_params="")
	{
		global $db;
		if($extra_params["SERVICE_CALL"] == 1)
                {
                        $sql = "SELECT COUNT(*) AS COUNT FROM incentive.SERVICE_ADMIN WHERE PROFILEID='$profileid'";
                        $res = mysql_query($sql,$db) or die("$sql".mysql_error($db));
                        $row = mysql_fetch_array($res);
                }
                else
		{
			$sql = "SELECT COUNT(*) AS COUNT FROM incentive.PROFILE_ALLOCATION WHERE PROFILEID='$profileid'";
			$res = mysql_query($sql,$db) or die("$sql".mysql_error($db));
			$row = mysql_fetch_array($res);
		}

		if($row['COUNT'] > 0)
			return 1;
		else
			return 0;
	}

	function write_contents_to_file($profileid,$mtongue="",$score="",$failed_payments="",$DPP=0,$extra_params="")
	{
		global $fp1, $fp2, $fp3, $fp4, $fp5, $fp6, $south_arr, $dncr_arr, $pune_city_arr, $west_city_arr, $db;

		// query to find details of users who have registered one week back and whose profiles are active 
		//$sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND COUNTRY_RES=51 AND INCOMPLETE='N' AND PROFILEID ='$profileid' AND SUBSCRIPTION='' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";

		if($extra_params["SERVICE_CALL"] == 1)
                        $sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE PROFILEID ='$profileid'";
                else
			$sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE PROFILEID ='$profileid' AND SUBSCRIPTION='' AND ACTIVATED IN ('Y','H') AND COUNTRY_RES=51 AND INCOMPLETE='N' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";

		if(!$failed_payments && $extra_params["SERVICE_CALL"] != 1)
			$sql .= " AND ENTRY_DT < DATE_SUB(CURDATE(), INTERVAL 8 DAY) AND MTONGUE <> '1'";
			//$sql .= " AND ENTRY_DT < DATE_SUB(CURDATE(), INTERVAL 15 DAY) AND MTONGUE <> '1'";

		$res = mysql_query($sql,$db) or logError($sql,$db);
		if ($row = mysql_fetch_array($res))
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
			$res_alt = mysql_query($sql_alt,$db) or logError($sql_alt,$db);//die("$sql_alt".mysql_error($db));
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

				// member as filled in his/her desired partner profile
				if ($DPP ==  1)
					$HAVEPARTNER = 'Y';
				else
					$HAVEPARTNER = 'N';

				//$PROFILELENGTH = strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);
				$PROFILELENGTH=$row['PROFILELENGTH'];

				$LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

				if($extra_params["SERVICE_CALL"] != 1)
                                {
					//section added to check weather the profile has ever had any subscription.
					$sql_p = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE'";
					$res_p = mysql_query($sql_p,$db) or logError($sql_p,$db);//die("$sql_p".mysql_error());
					$row_p = mysql_fetch_array($res_p);
					if($row_p['COUNT'] > 0)
						$EVER_PAID = "Y";
					else
						$EVER_PAID = "N";
					//end of -- section added to check weather the profile has ever had any subscription.
				}
				else
					$EVER_PAID = "Y";

				// cretaing content to be written to the file
				if ($PHONE_MOB && $PHONE_RES)
				{
					$line="\"$profileid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
				}
				elseif ($PHONE_MOB && $PHONE_RES =='')
				{
					$line="\"$profileid\"".","."\"$PHONE_MOB\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
				}
				elseif ($PHONE_MOB =='' && $PHONE_RES)
				{
					$line="\"$profileid\"".","."\"$PHONE_RES\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
				}

				$data = trim($line)."\n";
				$output = $data;
				unset($data);
				unset($DPP);

				// writing content to file
				if($extra_params["SERVICE_CALL"] == "1")
                                        fwrite($fp1,$output);
				else
				{
					if($failed_payments)
						fwrite($fp6,$output);
					else
					{
						//if(@in_array($mtongue,$south_arr))
						if(@in_array($city,$south_arr))
							fwrite($fp1,$output);
						elseif(@in_array($city,$dncr_arr))
							fwrite($fp2,$output);
						elseif(@in_array($city,$pune_city_arr))
							fwrite($fp3,$output);
						elseif(@in_array($city,$west_city_arr))
							fwrite($fp4,$output);
						else
							fwrite($fp5,$output);
					}
				}
			}
		}
	}

	function minimize_data()
	{
		global $db;
		mysql_ping($db);
		$sql="delete incentive.TEMP_CSV_PROFILES.* from incentive.TEMP_CSV_PROFILES , incentive.DO_NOT_CALL  where incentive.TEMP_CSV_PROFILES.PROFILEID=incentive.DO_NOT_CALL.PROFILEID and incentive.DO_NOT_CALL.REMOVED='N'";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES.* from incentive.TEMP_CSV_PROFILES , incentive.INVALID_PHONE b where incentive.TEMP_CSV_PROFILES.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES.* from incentive.TEMP_CSV_PROFILES , incentive.MAIN_ADMIN b where incentive.TEMP_CSV_PROFILES.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES.* from incentive.TEMP_CSV_PROFILES , billing.PURCHASES b where incentive.TEMP_CSV_PROFILES.PROFILEID=b.PROFILEID AND STATUS='DONE' AND b.ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES.* from incentive.TEMP_CSV_PROFILES , incentive.PROFILE_ALLOCATION b where incentive.TEMP_CSV_PROFILES.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES.* from incentive.TEMP_CSV_PROFILES , incentive.HISTORY b where incentive.TEMP_CSV_PROFILES.PROFILEID=b.PROFILEID and b.ENTRY_DT>=DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="update incentive.TEMP_CSV_PROFILES a , incentive.PROFILE_ALTERNATE_NUMBER b set a.AN=b.ALTERNATE_NUMBER where a.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

                $sql="select PROFILEID from incentive.TEMP_CSV_PROFILES";
                $res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
		
		$db737=connect_737();

                while($row=mysql_fetch_assoc($res))
                {
                        $profileid=$row['PROFILEID'];
                        $sql_prof="select PHONE_MOB,PHONE_RES,STD from newjs.JPROFILE where PROFILEID=$profileid and ACTIVATED IN ('Y','H') AND COUNTRY_RES=51 AND INCOMPLETE='N' AND SUBSCRIPTION='' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";
                        $res_prof=mysql_query($sql_prof,$db737) or die("$sql".mysql_error($db737));
                        if($row_prof=mysql_fetch_array($res_prof))
                        {

                                $phone_mob=$row_prof['PHONE_MOB'];
                                $phone_res=$row_prof['STD'].$row_prof['PHONE_RES'];

                                $sql="update incentive.TEMP_CSV_PROFILES as a set a.MN='$phone_mob',a.RN='$phone_res' where a.PROFILEID=$profileid";
                                mysql_query($sql,$db) or die("$sql".mysql_error($db));
                        }
                }
		@mysql_close($db737);
		$sql="delete from incentive.TEMP_CSV_PROFILES where CHAR_LENGTH(AN)<10 AND CHAR_LENGTH(MN)<10 AND CHAR_LENGTH(RN)<10";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));
	}
?>
