<?php

        /*  return array
         *  function gets the states which do not have defined js franchise location.    
        */
        function getRestIndiaStates()
        {
		global $db;
                $todayDate      =date("Y-m-d");
                $last20Days     =date("Y-m-d H:i:s",JSstrToTime("$todayDate -20 days"));
                $centerArr      =array();
                $stateArr       =array();
                $otherStateArr  =array();

                $sql_pswrd ="SELECT DISTINCT CENTER from jsadmin.PSWRDS WHERE LAST_LOGIN_DT>='$last20Days'";
                $res_pswrd = mysql_query($sql_pswrd,$db) or die("$sql_pswrd".mysql_error($db));
                while($row_pswrd = mysql_fetch_array($res_pswrd)){
                        $centerArr[] =STRTOUPPER($row_pswrd['CENTER']);
                }
                $centerStr ="'".@implode("','",$centerArr)."'";

                $sql_loc ="SELECT DISTINCT STATE from incentive.LOCATION WHERE UPPER(`NAME`) IN($centerStr)";
                $res_loc = mysql_query($sql_loc,$db) or die("$sql_loc".mysql_error($db));
                while($row_loc = mysql_fetch_array($res_loc)){
                        $stateArr[] =$row_loc['STATE'];
                }
                $stateStr ="'".@implode("','",$stateArr)."'";

                $sql_locCity ="select DISTINCT STATE from incentive.LOCATION_CITY WHERE STATE NOT IN($stateStr)";
                $res_locCity = mysql_query($sql_locCity,$db) or die("$sql_locCity".mysql_error($db));
                while($row_locCity = mysql_fetch_array($res_locCity)){
                        $otherStateArr[] =$row_locCity['STATE'];
                }
                return $otherStateArr;
        }

	function check_profile($profileid,$extra_params="",$checkParam="")
	{
		global $db;
		
		$dateExclude =date('Y-m-d',time()-30*86400); 
		// Do not call check
		$sql_dnt_call = "SELECT COUNT(*) AS COUNT FROM incentive.DO_NOT_CALL WHERE PROFILEID='$profileid'";
		$res_dnt_call = mysql_query($sql_dnt_call,$db) or die("$sql_dnt_call".mysql_error($db));
		$row_dnt_call = mysql_fetch_array($res_dnt_call);
		if($row_dnt_call['COUNT']>0)
			return false;

		// Main admin check
                $sql_md = "SELECT COUNT(*) AS COUNT from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
                $res_md = mysql_query($sql_md,$db) or die("$sql_md".mysql_error($db));
                $row_md = mysql_fetch_array($res_md);
		if($row_md['COUNT']>0)
			return false;	
		/*
		if($checkParam)
			$row_check['COUNT']=0;
		else
		{
                	$sql_check = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
                	$res_check = mysql_query($sql_check,$db) or die("$sql_check".mysql_error($db));
                	$row_check = mysql_fetch_array($res_check);
		}*/	

		// Invalid phone check
		$sql_offline= "SELECT PHONE_FLAG FROM newjs.JPROFILE WHERE PROFILEID= '$profileid'";
                $res_offline = mysql_query($sql_offline,$db) or die("$sql_offline".mysql_error($db));
                $row_offline = mysql_fetch_array($res_offline);
		if($row_offline['PHONE_FLAG']=='I')
			return false;
		//
		// Negative profile list check
                $sql_neg_profile= "SELECT COUNT(*) AS COUNT from incentive.NEGATIVE_TREATMENT_LIST WHERE PROFILEID='$profileid' AND FLAG_OUTBOUND_CALL='N'";
                $res_neg_profile= mysql_query($sql_neg_profile,$db) or die("$sql_neg_profile".mysql_error($db));
                $row_neg_profile= mysql_fetch_array($res_neg_profile);
		if($row_neg_profile['COUNT']>0)
			return false;	
	
		return true;
	}

	function profile_allocated($profileid,$extra_params="")
	{
		global $db;

		$sql = "SELECT COUNT(*) AS COUNT FROM incentive.PROFILE_ALLOCATION_TECH WHERE PROFILEID='$profileid'";
                $res = mysql_query($sql,$db) or die("$sql".mysql_error($db));
                $row = mysql_fetch_array($res);

		if($row['COUNT'] > 0)
			return 1;
		else
			return 0;
	}

	function write_contents_to_file($profileid,$mtongue="",$score="",$failed_payments="",$alloted_to="")
	{
		global $fp1, $fp2, $fp3, $fp4, $fp5, $fp6, $fp7, $belonging_arr, $not_belonging_arr, $pune_city_arr, $not_belonging_city_arr , $db, $db_dnc,$nri_arr;

		// query to find details of users who have registered 3 days back and whose profiles are active 
		$sql = "SELECT ENTRY_DT, ISD, LAST_LOGIN_DT , COUNTRY_RES, PHONE_RES  , PHONE_WITH_STD, DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , GENDER , RELATION , AGE, INCOME,SEC_SOURCE, HAVEPHOTO , MSTATUS FROM newjs.JPROFILE WHERE PROFILEID ='$profileid' AND SUBSCRIPTION='' AND ACTIVATED IN ('Y') AND INCOMPLETE='N' AND PHONE_FLAG !='I'";

		if(!$failed_payments)
			$sql .= " AND LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND DATE(ENTRY_DT) <= DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND MTONGUE != '1'";

		$res = mysql_query($sql,$db) or die("$sql".mysql_error($db));

		if ($row = mysql_fetch_array($res))
		{
			$genderVal 	=$row['GENDER'];
			$relationVal	=$row['RELATION'];
			$ageVal		=$row['AGE'];
			$incomeVal	=$row['INCOME'];
			$sec_source	=$row['SEC_SOURCE'];		
			$ISD		=$row['ISD'];
			$country	=$row['COUNTRY_RES'];
			if($genderVal=='M' && $relationVal=='1' && $ageVal<='23' && ($incomeVal=='' || $incomeVal=='15') && ($sec_source=='' || $sec_source=='S'))
				return;

			//PHOTO
                        $PHOTO = $row['HAVEPHOTO'];
                        if($PHOTO == "Y")
                        	$PHOTO1 = "Yes";
                        else
                        	$PHOTO1 = "No";
                        //end

			$phoneNumArray = array();
			if($row['PHONE_WITH_STD']!="")
				$PHONE_RES = $row['PHONE_WITH_STD'];
			else
				$PHONE_RES = $row['STD'].$row['PHONE_RES'];
			if($PHONE_RES)
				$PHONE_RES =phoneNumberCheck($PHONE_RES);
			$phoneNumArray['PHONE3'] = $PHONE_RES;

			$AL_NUMBER =getOtherPhoneNums($profileid);
			if($AL_NUMBER)
				$PHONE_MOB =phoneNumberCheck($AL_NUMBER);
			else
				$PHONE_MOB ='';
			$phoneNumArray['PHONE1'] = $PHONE_MOB;				

			$PHONE_MOB2 =$row['PHONE_MOB'];
			if($PHONE_MOB2)
				$PHONE_MOB2 =phoneNumberCheck($PHONE_MOB2);
			$phoneNumArray['PHONE2']=$PHONE_MOB2;

			if($PHONE_MOB=="")
				$PHONE_MOB =$PHONE_MOB2;

			$phoneNumArray = checkDNC($phoneNumArray);
			$isDNC = $phoneNumArray["STATUS"];
			if(!$isDNC)
			{
				$cnt = 1;
				$PHONE_MOB="";
				$PHONE_RES="";
				while($PHONE_MOB=='' && $cnt!=4)
				{
					$param = "PHONE".$cnt."S";
					if($phoneNumArray["$param"]=='N' && $phoneNumArray["PHONE$cnt"]!='')
                                        	$PHONE_MOB = $phoneNumArray["PHONE$cnt"];
					$cnt++;
				}
				while($PHONE_RES=='' && $cnt!=4)
                                {
                                        $param = "PHONE".$cnt."S";
                                        if($phoneNumArray["$param"]=='N' && $phoneNumArray["PHONE$cnt"]!='')
                                                $PHONE_RES = $phoneNumArray["PHONE$cnt"];
					$cnt++;
                                }

				$CDATE = date("Y-m-d",time());
			        $sql_vd="select DISCOUNT from billing.VARIABLE_DISCOUNT WHERE '$CDATE'>=SDATE AND '$CDATE'<=EDATE AND PROFILEID='$profileid'";
				$res_vd = mysql_query($sql_vd,$db) or die("$sql_vd".mysql_error($db));
			        if($row_vd = mysql_fetch_array($res_vd))
			                $vd_percent = $row_vd["DISCOUNT"];
				else
					$vd_percent = 0;

				//PRIRIOTY,OLD_PRIORITY,DIAL_STATUS
			//	$last_login_day = date_calc('',$row['LAST_LOGIN_DT'],'day');
			//	$PROFILE_AGE = date_calc('',$row['ENTRY_DT'],'month');

				if($alloted_to=='' && $vd_percent && $score>=1 && $score<=100)
				{
					$dial_status = '1';
					$priority='6';
				}
				elseif( $alloted_to=='' && !$vd_percent)
				{
					$dial_status = '1';	
					if($score>=81 && $score<=100)
						$priority='5';
					elseif($score>=61 && $score<=80)
						$priority='4';
					elseif($score>=41 && $score<=60)
						$priority='3';
					elseif($score>=21 and $score<=40)
						$priority='2';
					elseif($score>=11 and $score<=20)
                                                $priority='1';
					elseif($score>=1 and $score<=10)
						$priority='0';
				}
				elseif($alloted_to !='')
				{
					if($score>=1 && $score <=100)
					{
						$dial_status = '2';
                                                $priority='0';
					}
				}
				//end

				//LAST LOGIN DATE
                                $LAST_LOGIN_DT=date_calc('',$row['LAST_LOGIN_DT'],'d/m/y');
				$dt_timestamp =JSstrToTime($row['LAST_LOGIN_DT']);
				//end

				//DATE OF BIRTH
				$DOB=date_calc('',$row['DTOFBIRTH'],'d/m/y');
				//end
				
				//MARITAL STATUS
				include($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
                                $MARITAL_STATUS = $MSTATUS[$row['MSTATUS']];
				//end

				//EVER PAID
				$sql_p = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE'";
				$res_p = mysql_query($sql_p,$db) or die("$sql_p".mysql_error());
				$row_p = mysql_fetch_array($res_p);
				if($row_p['COUNT'] > 0)
					$EVER_PAID = "Yes";
				else
					$EVER_PAID = "No";
				//end

				//GENDER
				if($row['GENDER']=='F')
                                        $GENDER='Female';
                                else
                                        $GENDER='Male';
				//end

				//POSTED BY
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
				//end
				// City field
				$city =  $row['CITY_RES'];

				//lead_id
                                if(!$failed_payments)
                                {
					$sql_lf="SELECT LEAD_ID_SUFFIX FROM incentive.LARGE_FILE ORDER BY ENTRY_DT DESC LIMIT 1";
				        $res_lf=mysql_query($sql_lf,$db) or die("$sql_lf".mysql_error($db));
					$row_lf=mysql_fetch_assoc($res_lf);
					$suffix = $row_lf['LEAD_ID_SUFFIX'];

					if($ISD && ($ISD=='91' || $ISD=='0091' || $ISD=='+91'))
                                        {
						if(in_array($mtongue,$belonging_arr) && in_array($city,$pune_city_arr))
							$leadid="mumbai$suffix";
						elseif(in_array($mtongue,$belonging_arr) && !in_array($city,$not_belonging_city_arr))
							$leadid="mumbai$suffix";
						elseif(!in_array($mtongue,$not_belonging_arr) && !in_array($city,$not_belonging_city_arr))
							$leadid="noida$suffix";
					}
					else
                                        {
						if(in_array($country,$nri_arr))
                                                        $leadid="nri$suffix";
                                        }
                                }
				// cretaing content to be written to the file
				if ($PHONE_MOB && $PHONE_RES)
                                {
                                        $line="\"$profileid\""."|"."\"$priority\""."|"."\"$score\""."|"."\"$priority\""."|"."\"$dial_status\""."|"."\"$alloted_to\""."|"."\"$vd_percent\""."|"."\"$LAST_LOGIN_DT\""."|"."\"0$PHONE_MOB\""."|"."\"0$PHONE_RES\""."|"."\"$PHOTO1\""."|"."\"$DOB\""."|"."\"$MARITAL_STATUS\""."|"."\"$EVER_PAID\""."|"."\"$GENDER\""."|"."\"$POSTEDBY\""."|"."\"\""."|"."\"\""."|"."\"\""."|"."\"0$PHONE_MOB\""."|"."\"0$PHONE_RES\""."|"."\"$leadid\"";

					if($failed_payments)
						$line .="|"."\"$city\"";
					$line .="|"."\"$dt_timestamp\"";
						
                                }
				elseif ($PHONE_MOB)
                                {
                                        $line="\"$profileid\""."|"."\"$priority\""."|"."\"$score\""."|"."\"$priority\""."|"."\"$dial_status\""."|"."\"$alloted_to\""."|"."\"$vd_percent\""."|"."\"$LAST_LOGIN_DT\""."|"."\"0$PHONE_MOB\""."|"."\"\""."|"."\"$PHOTO1\""."|"."\"$DOB\""."|"."\"$MARITAL_STATUS\""."|"."\"$EVER_PAID\""."|"."\"$GENDER\""."|"."\"$POSTEDBY\""."|"."\"\""."|"."\"\""."|"."\"\""."|"."\"0$PHONE_MOB\""."|"."\"\""."|"."\"$leadid\"";

                                        if($failed_payments)
                                                $line .="|"."\"$city\"";
					$line .="|"."\"$dt_timestamp\"";

                                }
                                elseif ($PHONE_MOB =='' && $PHONE_RES)
                                {
                                        $line="\"$profileid\""."|"."\"$priority\""."|"."\"$score\""."|"."\"$priority\""."|"."\"$dial_status\""."|"."\"$alloted_to\""."|"."\"$vd_percent\""."|"."\"$LAST_LOGIN_DT\""."|"."\"0$PHONE_RES\""."|"."\"\""."|"."\"$PHOTO1\""."|"."\"$DOB\""."|"."\"$MARITAL_STATUS\""."|"."\"$EVER_PAID\""."|"."\"$GENDER\""."|"."\"$POSTEDBY\""."|"."\"\""."|"."\"\""."|"."\"\""."|"."\"0$PHONE_RES\""."|"."\"\""."|"."\"$leadid\"";

                                        if($failed_payments)
                                                $line .="|"."\"$city\"";
					$line .="|"."\"$dt_timestamp\"";
                                }

				$data = trim($line)."\n";
				$output = $data;
				unset($data);
				unset($alloted_to);
				// writing content to file
				$insert=0;
				//$city =  $row['CITY_RES'];
	                        $subcity=substr($city,0,2);
				if($failed_payments)
					fwrite($fp7,$output);
				else
				{
					if($ISD && ($ISD=='91' || $ISD=='0091' || $ISD=='+91'))
					{
						if(in_array($mtongue,$belonging_arr) && in_array($city,$pune_city_arr))
						{
							$insert=1;
							fwrite($fp6,$output);
						}
						elseif(in_array($mtongue,$belonging_arr) && !in_array($city,$not_belonging_city_arr))
						{
							$insert=1;
							fwrite($fp5,$output);
						}
						elseif(!in_array($mtongue,$not_belonging_arr) && !in_array($city,$not_belonging_city_arr))
						{
							$insert=1;
							fwrite($fp4,$output);
						}
					}
					else
					{
						if(in_array($country,$nri_arr))
                                                	fwrite($fp1,$output);
					}
				}
				if($insert)
				{
					$sql="insert ignore into incentive.IN_DIALER (PROFILEID) VALUES ('$profileid')";
			                mysql_query($sql,$db) or die("$sql".mysql_error($db));
				}
			}
		}
	}

	function minimize_data()
	{
		global $db;
		mysql_ping($db);

		//Dialer duplication check
                $sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , incentive.IN_DIALER where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=incentive.IN_DIALER.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

		// Negative profile list check 
		$sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , incentive.NEGATIVE_PROFILE_LIST where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=incentive.NEGATIVE_PROFILE_LIST.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , incentive.DO_NOT_CALL  where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=incentive.DO_NOT_CALL.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		//$sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , incentive.MAIN_ADMIN b where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=b.PROFILEID";
		//mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , billing.PURCHASES b where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=b.PROFILEID AND STATUS='DONE' AND b.ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
		//mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , incentive.PROFILE_ALLOCATION_TECH b where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

		//$sql="delete incentive.TEMP_CSV_PROFILES_TECH.* from incentive.TEMP_CSV_PROFILES_TECH , incentive.HISTORY b where incentive.TEMP_CSV_PROFILES_TECH.PROFILEID=b.PROFILEID and b.ENTRY_DT>=DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
		//mysql_query($sql,$db) or die("$sql".mysql_error($db));

		$sql="update incentive.TEMP_CSV_PROFILES_TECH a , incentive.PROFILE_ALTERNATE_NUMBER b set a.AN=b.ALTERNATE_NUMBER where a.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

                $sql="select PROFILEID from incentive.TEMP_CSV_PROFILES_TECH";
                $res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
		
		$db737=connect_737();
                while($row=mysql_fetch_assoc($res))
                {
                        $profileid=$row['PROFILEID'];
                        $sql_prof="select PHONE_MOB,PHONE_RES,STD,PHONE_WITH_STD,MTONGUE,INCOME,FAMILY_INCOME,SUBSCRIPTION,ENTRY_DT from newjs.JPROFILE where PROFILEID='$profileid' and ACTIVATED IN ('Y') AND INCOMPLETE='N'";
                        $res_prof=mysql_query($sql_prof,$db737) or die("$sql".mysql_error($db737));
                        if($row_prof=mysql_fetch_array($res_prof))
                        {
				if((strstr($row_prof['SUBSCRIPTION'],"F")!="")||(strstr($row_prof['SUBSCRIPTION'],"D")!=""))
                                                continue;

				$regEntryDtTm =@explode(" ",$row_prof['ENTRY_DT']);
				$regEntryDt   =$regEntryDtTm[0];	
		               	$phone_mob=$row_prof['PHONE_MOB'];
				if($row_prof['PHONE_WITH_STD']!='')
					$phone_res=$row_prof['PHONE_WITH_STD'];
				else
	                      		$phone_res=$row_prof['STD'].$row_prof['PHONE_RES'];
				$phone_mob=addslashes(stripslashes($phone_mob));
                                $phone_res=addslashes(stripslashes($phone_res));
			
				$fto_profile=0; 
				$sql_fto="select STATE_ID,FTO_ENTRY_DATE,FTO_EXPIRY_DATE from FTO.FTO_CURRENT_STATE WHERE PROFILEID=$profileid";
				$res_fto=mysql_query($sql_fto,$db) or die("$sql_fto".mysql_error($db));
				$row_fto=mysql_fetch_assoc($res_fto); 
				$stateId=$row_fto['STATE_ID'];
				if(!$stateId)
					$ftoState="NEVER_EXPOSED";
				else
				{
					$ftoEntryDate=$row_fto['FTO_ENTRY_DATE'];
					$ftoExpiryDate=$row_fto['FTO_EXPIRY_DATE'];
					$sql_state="SELECT STATE from FTO.FTO_STATES WHERE STATE_ID=$stateId";
					$res_state=mysql_query($sql_state,$db) or die("$sql_state".mysql_error($db));
					$row_state=mysql_fetch_assoc($res_state);
					$ftoState=$row_state['STATE'];
				}
	
				/*$profileObj=new Profile("",$profileid);
				$ftoStatesObj=$profileObj->getPROFILE_STATE()->getFTOStates();
				echo $ftoState= $ftoStatesObj->getState();die("huh");*/
				
				/*$eligibleProfile=0;
                                if($ftoState=="EXPIRED")
                                {
                                        //$mtongue=$row_prof['MTONGUE'];
                                        $income=$row_prof['INCOME'];
					$familyIncome=$row_prof['FAMILY_INCOME'];
                                        $premiumIncome=array(13,14,17,18,19,20,21,22,23);
                                        //$exclude_mtongue=array(3,16,17,31);
                                        if(in_array("$income",$premiumIncome)||in_array("$familyIncome",$premiumIncome))
                                        {
                                                $ftoExpiryDt=date('Y-m-d',JSstrToTime($ftoExpiryDate. ' + 1 day'));
                                                $today=date('Y-m-d',time());
                                                if($today>$ftoExpiryDt)
                                                        $eligibleProfile=1;
                                        }
					else
						$eligibleProfile=1;
                                }
				elseif($ftoState=="NEVER_EXPOSED" || $ftoState=="ACTIVE")
					$eligibleProfile=1;
				else
					$eligibleProfile=0;
				*/
				$eligibleProfile=0;
				if($ftoState=="EXPIRED" || $ftoState=="NEVER_EXPOSED" || $ftoState=="ACTIVE")
				{
					$income=$row_prof['INCOME'];
                                        $familyIncome=$row_prof['FAMILY_INCOME'];
                                        $premiumIncome=array(13,14,17,18,19,20,21,22,23,24,25,26,27);
                                        //$exclude_mtongue=array(3,16,17,31);
                                        if(in_array("$income",$premiumIncome)||in_array("$familyIncome",$premiumIncome))
                                        {
						$today=date('Y-m-d',time());
                                                $profileReg3Dt=date('Y-m-d',JSstrToTime($today. ' - 3 day'));
                                                if(JSstrToTime($regEntryDt)<=JSstrToTime($profileReg3Dt))
                                                        $eligibleProfile=1;
					}
					else{
						$today=date('Y-m-d',time());
						$profileReg2Dt=date('Y-m-d',JSstrToTime($today. ' - 2 day'));
						if(JSstrToTime($regEntryDt)<=JSstrToTime($profileReg2Dt))
							$eligibleProfile=1;

					}
				}

				$permanent_excluded=0;
				if($eligibleProfile)
				{
					$sql_alloted="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
					$res_alloted = mysql_query($sql_alloted,$db737) or die("$sql_alloted".mysql_error($db737));
					if($row_alloted = mysql_fetch_array($res_alloted))
						$alloted_case=1;
					else
						$alloted_case=0;
					if(!$alloted_case)
					{
						$excl_d_dt=date('Y-m-d',time()-(30-1)*86400);
						$excl_dnc_dt=date('Y-m-d',time()-(30-1)*86400);
						$excl_cf_dt=date('Y-m-d',time()-(7-1)*86400);
						$excl_ni_dt=date('Y-m-d',time()-(7-1)*86400);
						//disposition
						$sql_history="SELECT ENTRY_DT,DISPOSITION FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
						$res_history = mysql_query($sql_history,$db737) or die("$sql_history".mysql_error($db737));
						if($row_history = mysql_fetch_array($res_history))
						{
							if(($row_history["ENTRY_DT"]>=$excl_cf_dt&&$row_history["DISPOSITION"]=='CF') || ($row_history["DISPOSITION"]=='D' && $row_history["ENTRY_DT"]>=$excl_d_dt )|| ($row_history["DISPOSITION"]=='DNC' && $row_history["ENTRY_DT"]>=$excl_dnc_dt) || ($row_history["DISPOSITION"]=='NI' && $row_history["ENTRY_DT"]>=$excl_ni_dt))
								$permanent_excluded=1;
						}
						//setting
						$sql_al = "SELECT MEMB_CALLS,OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='$profileid'";
						$res_al = mysql_query($sql_al,$db737) or die("$sql_al".mysql_error($db737));
						if($row_al = mysql_fetch_array($res_al))
						{
							if($row_al["MEMB_CALLS"]=='U' || $row_al["OFFER_CALLS"]=='U')
								$permanent_excluded=1;
						}
					}
					$sql_neg_profile= "SELECT COUNT(*) AS COUNT from incentive.NEGATIVE_TREATMENT_LIST WHERE PROFILEID='$profileid' AND FLAG_OUTBOUND_CALL='N'";
					$res_neg_profile= mysql_query($sql_neg_profile,$db) or die("$sql_neg_profile".mysql_error($db));
					$row_neg_profile= mysql_fetch_array($res_neg_profile);
					if($row_neg_profile['COUNT']>0)
						$permanent_excluded=1;
				}
				else
					$permanent_excluded=1;
				//END

				if($permanent_excluded)
					$sql="delete from incentive.TEMP_CSV_PROFILES_TECH where PROFILEID=$profileid";
				else
                                	$sql="update incentive.TEMP_CSV_PROFILES_TECH as a set a.MN='$phone_mob',a.RN='$phone_res' where a.PROFILEID=$profileid";
                                mysql_query($sql,$db) or die("$sql".mysql_error($db));
                        }
                }
		$sql="delete from incentive.TEMP_CSV_PROFILES_TECH where CHAR_LENGTH(AN)<10 AND CHAR_LENGTH(MN)<10 AND CHAR_LENGTH(RN)<10";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));
		@mysql_close($db737);
	}

        function checkDNC($phoneNumberArray)
        {
                global $db_dnc;
                mysql_ping($db_dnc);
                $DNCArr 	=array();
		$DNC_NumberArr 	=array();
		$selectedArr	=array();
		$status 	=true;

                if(!is_array($phoneNumberArray) || count($phoneNumberArray)=='0')
                        return false;
		else{
			foreach($phoneNumberArray as $key1=>$val1)
			{
				if($val1)
					$selectedArr[] =$val1;		
			}	
		}

                $phoneNumberStr =implode("','",$selectedArr);
                $sql="SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN('$phoneNumberStr')";
                $res=mysql_query($sql,$db_dnc) or die($sql.mysql_error());
                while($row=mysql_fetch_array($res))
                {
                        $DNC_NumberArr[] =$row['PHONE'];
                }

                foreach($phoneNumberArray as $key=>$val)
                {
                        if(in_array($val, $DNC_NumberArr)){
                                $DNCArr[$key] =$val;
				$key1 =$key."S";
				$DNCArr[$key1] ='Y';
                        }
                        else{
                                $DNCArr[$key] =$val;
				$key1 =$key."S";
				$DNCArr[$key1] ='N';
				if(in_array($val, $selectedArr))
                                	$status =false;
                        }
                }
                $DNCArr['STATUS'] =$status;
                return $DNCArr;
        }

        function getOtherPhoneNums($profileid)
        {
                global $db;
                $sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$profileid' ORDER BY ID DESC LIMIT 1";
                $res_alt = mysql_query($sql_alt,$db) or die("$sql_alt".mysql_error($db));
                if($row_alt = mysql_fetch_array($res_alt))
                {
                        $AL_NUMBER=$row_alt['ALTERNATE_NUMBER'];
                        return $AL_NUMBER;
                }
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

	function date_calc($d1='',$d2='',$form='')
	{
		if($d1=='')
			$dt1 = time();
		else
			$dt1 = JSstrToTime($d1);
		$dtf = JSstrToTime($d2);
		if($form=="d/m/y")
		{
			$lldt1=explode("-",$d2);
			$lldts=mktime(0,0,0,$lldt1[1],$lldt1[2],$lldt1[0]);
			$LAST_LOGIN_DT1=date("d/m/y",$lldts);
			$LAST_LOGIN_DT = $LAST_LOGIN_DT1;
			return $LAST_LOGIN_DT;
		}
		else
		{
			if($form=="month")
				$interval = round(((($dt1-$dtf)/86400)/30.42),0);
			else
				$interval = round((($dt1-$dtf)/86400),0);
			return $interval;
		}
	}

        function sortFileContent($filename,$filePath)
        {
                $tempDir ="/tmp/";
                $file_orig =$filePath.$filename;

                $file_orig_orig =$filePath."orig/".$filename;
                passthru("cp $file_orig $file_orig_orig");

                $file_tmp =$tempDir.$filename;

                //passthru("sort -r -t'|' -k2 -k3 -k23 $file_orig>$file_tmp");
                passthru("/bin/sort -rn -t'|' -k2 -k3 -k23 $file_orig>$file_tmp");
                passthru("mv $file_tmp $file_orig");
        }

?>
