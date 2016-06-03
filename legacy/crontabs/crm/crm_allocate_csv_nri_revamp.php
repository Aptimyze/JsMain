<?php
	$curFilePath = dirname(__FILE__)."/";
	include_once("/usr/local/scripts/DocRoot.php");

	ini_set("max_execution_time","0");
	chdir("$docRoot/crontabs/crm");
	include ("$docRoot/crontabs/connect.inc");
	include("allocate_functions_revamp.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

	$SITE_URL=JsConstants::$ser6Url;
	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;

	$db = connect_737();
        for($i=0;$i<$noOfActiveServers;$i++)
        {
                $myDbName=$slave_activeServers[$i];
                $myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
                mysql_query("set session wait_timeout=10000",$myDbArray[$myDbName]);
        }
	
	$ts = time();
	$ts -= 30*24*60*60;
	$last_day = date("Y-m-d",$ts);

	//For West Asia & Middle East
	$nri_arr1 = array(1,6,141,10,69,53,54,56,215,63,64,66,87,88,96,99,114,117,122,125,129,132,61,71,3,4,145,146,148,149,21,23,150,152,153,154,27,156,35,160,161,163,167,43,173,174,62,180,181,67,185,186,72,74,77,192,79,195,84,197,198,203,100,101,102,106,107,111,207,118,208,120,123,134,135,136);

	//For American continents & European contries
	$nri_arr2 = array(2,138,8,143,12,16,19,29,31,32,33,162,164,39,40,42,44,45,47,49,50,55,177,57,65,182,68,216,184,73,188,189,190,81,86,93,94,97,98,202,204,104,219,109,112,113,115,121,124,126,213,133,139,140,9,142,144,13,147,22,151,28,30,157,158,36,168,169,170,172,46,176,58,187,76,191,193,83,89,218,199,200,201,209,210,128,105,5,15,24,26,34,165,175,90,91,206,127,130,17,37);

	//For East Asia
	$nri_arr3 = array(11,14,18,20,25,159,48,52,59,179,183,70,75,78,80,85,92,103,108,110,116,119,131,60,7,155,38,166,217,178,194,82,196,205,214,212);

	$nri_str1 = implode(",",$nri_arr1);
	$nri_str2 = implode(",",$nri_arr2);
	$nri_str3 = implode(",",$nri_arr3);
	$nri_str = $nri_str1.",".$nri_str2.",".$nri_str3;

	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE_NO1\"".","."\"PHONE_NO2\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS_INITIATED\"".","."\"CONTACTS_ACCEPTED\"".","."\"CONTACTS_RECEIVED\"".","."\"ACCEPTANCE_RECEIVED\"".","."\"AGE\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED_PARTNER_PROFILE\"".","."\"LAST_LOGIN_DATE\"".","."\"ANALYTIC_SCORE\"".","."\"PROFILE_AGE\"".","."\"MSTATUS\"".","."\"INCOME\"".","."\"LEAD_ID\"".","."\"PHONE_1\"".","."\"PHONE_2\"".","."\"DIAL_STATUS\"".","."\"PRIORITY\"\n";

        $filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri1.txt";
        $filename2 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri2.txt";
	$filename3 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri3.txt";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");
	$fp3 = fopen($filename3,"w+");

        if(!$fp1 || !$fp2 || !$fp3)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);
        fwrite($fp2,$header);
	fwrite($fp3,$header);

	$sqlj="SELECT PROFILEID FROM newjs.JPROFILE WHERE SUBSCRIPTION = '' AND ACTIVATED IN ('Y', 'H') AND INCOMPLETE = 'N' AND ENTRY_DT < DATE_SUB(CURDATE(),INTERVAL 3 DAY) AND COUNTRY_RES IN ($nri_str)";
        $resj=mysql_query($sqlj,$db) or die(mysql_error());
        while($rowj = mysql_fetch_array($resj))
                $profileid_arr[] = $rowj['PROFILEID'];

	//VD
	$CDATE = date("Y-m-d",time());
        $sql_vd="select vd.PROFILEID from billing.VARIABLE_DISCOUNT as vd JOIN newjs.JPROFILE as j ON vd.PROFILEID=j.PROFILEID WHERE $CDATE>=SDATE AND $CDATE<=EDATE AND COUNTRY_RES IN ($nri_str)";
	$res_vd = mysql_query($sql_vd,$db) or die("$sql_vd".mysql_error($db));
        while($row_vd = mysql_fetch_array($res_vd))
		$profileid_arr[] = $row_vd["PROFILEID"];

	//end	

	$profileid_str = implode(",",$profileid_arr);

	$sql_pid = "SELECT PROFILEID, MTONGUE, ANALYTIC_SCORE FROM incentive.MAIN_ADMIN_POOL WHERE ANALYTIC_SCORE>=300 AND ALLOTMENT_AVAIL='Y' AND TIMES_TRIED<3 AND SOURCE NOT IN ('onoffreg','ofl_prof') AND PROFILEID IN ($profileid_str)"; 
        $res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
        while($row_pid = mysql_fetch_array($res_pid))
        {
                unset($allow);
                $profileid = $row_pid['PROFILEID'];
                if(!profile_allocated($profileid))
                {
                        $mtongue = $row_pid['MTONGUE'];
                        $score = $row_pid['ANALYTIC_SCORE'];

                        $sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
                        $res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error($db));
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

                        if(!check_profile($profileid))
                                $allow=0;
			if($allow)
                        {
                                $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                                $mysqlObj->ping($myDbArray[$myDbName]);
                                $myDb=$myDbArray[$myDbName];
                                $jpartnerObj->setPROFILEID($profileid);
                                if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
                                        $DPP=1;
                                else
                                        $DPP=0;
                                write_contents_to_file_nri($profileid,$score,$DPP);
                        }
                }
        }
        fclose($fp1);
        fclose($fp2);
	fclose($fp3);

	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri1.txt";
	$profileid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri2.txt";
	$profileid_file3 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri3.txt";

	$msg.="\nFor West Asia & Middle East: ".$profileid_file1;
	$msg.="\nFor American continents & European contries : ".$profileid_file2;
	$msg.="\nFor East Asia : ".$profileid_file3;

	$to="anamika.singh@jeevansathi.com,anjali.singh@jeevansathi.com,princy.gulati@jeevansathi.com,nitika.bhatia@jeevansathi.com,bharat.vaswani@jeevansathi.com,nidhi.aneja@jeevansathi.com";
	$bcc="vibhor.garg@jeevansathi.com";
	$sub="Revamp CSVs for NRI profiles";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	mail($to,$sub,$msg,$from);

	function write_contents_to_file_nri($profileid,$score="",$DPP=0)
        {
                global $fp1, $fp2, $fp3, $nri_arr1, $nri_arr2, $nri_arr3, $db;

		$sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE  , MSTATUS , INCOME , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE PROFILEID ='$profileid' AND PHONE_FLAG !='I'";
                $res = mysql_query($sql,$db) or logError($sql,$db);
                if ($row = mysql_fetch_array($res))
                {
                        $PHONE_RES = $row['PHONE_RES'];
                        if($PHONE_RES)
                                $PHONE_RES=$row['STD'].$PHONE_RES;
                        if(strlen($PHONE_RES)<10)
                                $PHONE_RES="";
			$PHONE_RES1 = "0".$PHONE_RES;
                        if(strlen($PHONE_RES1)<11)
                                $PHONE_RES1="";
			//added by sriarm on June 12 2007 to show alternate number (if any)
                        $sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$profileid' ORDER BY ID DESC LIMIT 1";
                        $res_alt = mysql_query($sql_alt,$db) or logError($sql_alt,$db);//die("$sql_alt".mysql_error($db));
                        if($row_alt = mysql_fetch_array($res_alt))
                                $PHONE_MOB = $row_alt['ALTERNATE_NUMBER'];
                        else
                                $PHONE_MOB = $row['PHONE_MOB'];
                        //end of - added by sriarm on June 12 2007 to show alternate number (if any)
                        if(strlen($PHONE_MOB)<10)
                                $PHONE_MOB="";
			$PHONE_MOB1 = "0".$PHONE_MOB;
                        if(strlen($PHONE_MOB1)<11)
                                $PHONE_MOB1="";
                        $PHOTO = $row['HAVEPHOTO'];
                        $ENTRY_DT = $row['ENTRY_DT'];
                        $PROFILE_AGE = round((((time()-strtotime($ENTRY_DT))/86400)/30.42),0);

                        $city =  $row['CITY_RES'];
                        $subcity=substr($city,0,2);
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
                                $AGE = getAge($DOB);
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

                                $caste = $row['CASTE'];
                                $caste_label= label_select('CASTE',$caste);
                                $CASTE=$caste_label;

                                $mtongue = $row['MTONGUE'];
                                $mtongue_label= label_select('MTONGUE',$mtongue);
                                $MTONGUE= $mtongue_label;

				$city_label=label_select('CITY_NEW',$city);
				$CITY_RES=$city_label;

				$country =  $row['COUNTRY_RES'];
                                $country_label = label_select('COUNTRY',$country);
                                $COUNTRY= $country_label;

                                // member as filled in his/her desired partner profile
                                if ($DPP ==  1)
                                        $HAVEPARTNER = 'Y';
                                else
                                        $HAVEPARTNER = 'N';

                                $PROFILELENGTH=$row['PROFILELENGTH'];

                                $LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];
                                $last_login_month = round((((time()-strtotime($LAST_LOGIN_DT))/86400)/30.42),0);
                                if($score>400)
                                {
                                        if($last_login_month<=1)
                                                $priority='5';
                                        else
                                                $priority='4';
                                }
				elseif($score>=348 && $score<=396)
                                {
                                        if($last_login_month<=1)
                                                $priority='4';
                                        else
                                                $priority='3';
                                }
                                elseif($score>=324 && $score<=336)
                                {
                                        if($last_login_month<=1)
                                                $priority='3';
                                        else
                                                $priority='2';
                                }
                                elseif($score==312)
                                {
                                        if($last_login_month<=1)
                                                $priority='2';
                                        else
                                                $priority='1';
                                }
                                elseif($score==300)
                                {
                                        if($last_login_month<=1)
                                                $priority='1';
                                        else
                                                $priority='0';
                                }
                                else
                                        $priority='0';
                                $lldt1=explode("-",$LAST_LOGIN_DT);
                                $lldts=mktime(0,0,0,$lldt1[1],$lldt1[2],$lldt1[0]);
                                $LAST_LOGIN_DT1=date("d/m/y",$lldts);
                                $LAST_LOGIN_DT = $LAST_LOGIN_DT1;
				//section added to check weather the profile has ever had any subscription.
				$sql_p = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE'";
				$res_p = mysql_query($sql_p,$db) or logError($sql_p,$db);//die("$sql_p".mysql_error());
				$row_p = mysql_fetch_array($res_p);
				if($row_p['COUNT'] > 0)
					$EVER_PAID = "Y";
				else
					$EVER_PAID = "N";
				//end of -- section added to check weather the profile has ever had any subscription.
                                if($EVER_PAID == "N")
                                {
                                        $new_dt=date("Y-m-d H:i:s",time()-15*24*60*60);
                                        $old_dt=date("Y-m-d H:i:s",time()-90*24*60*60);
                                        if($ENTRY_DT>=$new_dt)
                                                $status="NEW";
                                        elseif($ENTRY_DT>=$old_dt)
                                                $status="OLD";
                                        else
                                                $status="VERYOLD";
                                }
                                else
                                        $status="";
                                if($EVER_PAID == "N")
                                        $EVER_PAID1 = "No";
                                else
                                        $EVER_PAID1 = "Yes";
                                if($PHOTO == "Y")
                                        $PHOTO1 = "Yes";
				else
                                        $PHOTO1 = "No";
                                $TOTAL_ACCEPTANCE=$ACCEPTANCE_MADE+$ACCEPTANCE_RCVD;
                                $ldate=date("dmy",time());
				if(in_array($country,$nri_arr1))
					$lead_id="nri1$ldate";
                                elseif(in_array($country,$nri_arr2))
					$lead_id="nri2$ldate";
                                elseif(in_array($country,$nri_arr3))
					$lead_id="nri3$ldate";
                                include("$_SERVER[DOCUMENT_ROOT]/profile/arrays.php");
                                $MARITAL_STATUS = $MSTATUS[$row['MSTATUS']];
                                include("$_SERVER[DOCUMENT_ROOT]/profile/dropdowns.php");
                                $INCOME = $INCOME_DROP[$row['INCOME']];

                                if($row['GENDER']=='F')
                                        $GENDER='Female';
                                else
                                        $GENDER='Male';

                                // cretaing content to be written to the file
                                if ($PHONE_MOB && $PHONE_RES)
                                {
					$line="\"$profileid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES $EVER_PAID1\"".","."\"$PHOTO1 $status\"".","."\"$INITIATE_CNT\"".","."\"$TOTAL_ACCEPTANCE\"".","."\"$RECEIVE_CNT\"".","."\"$TOTAL_ACCEPTANCE\"".","."\"$AGE\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$PROFILE_AGE\"".","."\"$MARITAL_STATUS\"".","."\"$INCOME\"".","."\"$lead_id\"".","."\"$PHONE_MOB1\"".","."\"$PHONE_RES1\"".","."\"1\"".","."\"$priority\"";
                                }
                                elseif ($PHONE_MOB && $PHONE_RES =='')
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_MOB\"".",\"\","."\"$CITY_RES $EVER_PAID1\"".","."\"$PHOTO1 $status\"".","."\"$INITIATE_CNT\"".","."\"$TOTAL_ACCEPTANCE\"".","."\"$RECEIVE_CNT\"".","."\"$TOTAL_ACCEPTANCE\"".","."\"$AGE\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$PROFILE_AGE\"".","."\"$MARITAL_STATUS\"".","."\"$INCOME\"".","."\"$lead_id\"".","."\"$PHONE_MOB1\"".","."\"\"".","."\"1\"".","."\"$priority\"";
                                }
                                elseif ($PHONE_MOB =='' && $PHONE_RES)
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_RES\"".",\"\","."\"$CITY_RES $EVER_PAID1\"".","."\"$PHOTO1 $status\"".","."\"$INITIATE_CNT\"".","."\"$TOTAL_ACCEPTANCE\"".","."\"$RECEIVE_CNT\"".","."\"$TOTAL_ACCEPTANCE\"".","."\"$AGE\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$PROFILE_AGE\"".","."\"$MARITAL_STATUS\"".","."\"$INCOME\"".","."\"$lead_id\"".","."\"$PHONE_RES1\"".","."\"\"".","."\"1\"".","."\"$priority\"";
                                }

				if($line!='')
				{
                                	$data = trim($line)."\n";
                                	$output = $data;
				}
                                unset($data);
                                unset($DPP);
				// writing content to file
				if(in_array($country,$nri_arr1))
					fwrite($fp1,$output);
				elseif(in_array($country,$nri_arr2))
					fwrite($fp2,$output);
				elseif(in_array($country,$nri_arr3))
					fwrite($fp3,$output);
                        }
                }
        }
	function label_select($table,$value)
        {
                global $db;
                $sql = "SELECT LABEL FROM newjs.$table WHERE VALUE='$value'";
                $res = mysql_query($sql,$db) or die("Error in Label select".$sql.mysql_error());
                $row = mysql_fetch_array($res);
                $label = $row['LABEL'];
                return $label;
        }

?>
