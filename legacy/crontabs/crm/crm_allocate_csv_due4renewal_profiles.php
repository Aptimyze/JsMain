<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
	include("allocate_functions.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	$SITE_URL="http://www.jeevansathi.com";
	$mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;

	$db = connect_slave();

	for($i=0;$i<$noOfActiveServers;$i++)
        {
                $myDbName=$activeServers[$i];                
		$myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
		mysql_query("set session wait_timeout=10000",$myDbArray[$myDbName]);
        }

	$ts = time();
	$ts1 = $ts;
	$ts -= 30*24*60*60;
	$ts1 += 10*24*60*60;
	$last_day = date("Y-m-d",$ts);
	$check_day=date("Y-m-d",$ts1);
	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"COUNTRY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"".","."\"EVER_PAID\"\n";

        $filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_due4renewal.txt";

        $fp = fopen($filename,"w+");

        if(!$fp)
        {
                die("no file pointer");
        }
 	$sqlj="SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE ACTIVE = 'Y' AND SERVEFOR LIKE '%F%' AND EXPIRY_DT='$check_day'";
	$resj=mysql_query($sqlj,$db) or die(mysql_error()); 
	while($rowj = mysql_fetch_array($resj))
                $profileid_arr[] = $rowj['PROFILEID'];
	if(count($profileid_arr)>1)
		$profileid_str = implode(",",$profileid_arr);
	else
		$profileid_str = $profileid_arr[0];
        fwrite($fp,$header);
	$sql_pid = "SELECT PROFILEID, MTONGUE, SCORE FROM incentive.MAIN_ADMIN_POOL WHERE ALLOTMENT_AVAIL='Y' AND TIMES_TRIED<3 AND PROFILEID IN ($profileid_str)";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	while($row_pid = mysql_fetch_array($res_pid))
	{
		unset($allow);
		$profileid = $row_pid['PROFILEID'];
		if(!profile_allocated($profileid))
		{
			$mtongue = $row_pid['MTONGUE'];
			$score = $row_pid['SCORE'];

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
				write_contents_to_file_due4renewal($profileid,$score,$DPP);
			}			
		}
	}
        fclose($fp);

	$profileid_file = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_due4renewal.txt";

	$msg="For profiles due for renewal: ".$profileid_file;

	$to="anamika.singh@jeevansathi.com,surbhi.aggarwal@naukri.com,mandeep.choudhary@naukri.com,divya.jain@naukri.com,deepak.sharma@naukri.com, samrat.chadha@naukri.com";
	$bcc="vibhor.garg@jeevansathi.com";
	$sub="Daily CSV for profiles due for renewal";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	mail($to,$sub,$msg,$from);
	function write_contents_to_file_due4renewal($profileid,$score="",$DPP=0)
        {
                global $fp,$db;

                $sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB ,COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE PROFILEID ='$profileid' AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";

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

                        $sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$profileid' ORDER BY ID DESC LIMIT 1";
                        $res_alt = mysql_query($sql_alt,$db) or logError($sql_alt,$db);//die("$sql_alt".mysql_error($db));
                        if($row_alt = mysql_fetch_array($res_alt))
                                $PHONE_MOB = $row_alt['ALTERNATE_NUMBER'];
                        else
                                $PHONE_MOB = $row['PHONE_MOB'];

                        if(substr($PHONE_MOB,0,1)==0)
                                $PHONE_MOB = substr($PHONE_MOB,1);
                        $PHONE_MOB = substr($PHONE_MOB,-10);
                        if(strlen($PHONE_MOB)<10)
                                $PHONE_MOB="";
                        $PHOTO = $row['HAVEPHOTO'];
                        $ENTRY_DT = $row['ENTRY_DT'];
                        $subcity=substr($city,0,2);

                        if((trim($PHONE_RES) || trim($PHONE_MOB)))
                        {
                                include_once("$_SERVER[DOCUMENT_ROOT]/profile/contacts_functions.php");
                                $contactResultRA=getResultSet("count(*) as count","","",$profileid,"","'A'");
                                $ACCEPTANCE_RCVD=$contactResultRA[0]['count'];

                                $contactResultSA=getResultSet("count(*) as count",$profileid,"","","","'A'");
                                $ACCEPTANCE_MADE=$contactResultSA[0]['count'];

                                $contactResultRI=getResultSet("count(*) as count","","",$profileid,"","'I'");
                                $RECEIVE_CNT=$contactResultRI[0]['count'];

                                $contactResultSI=getResultSet("count(*) as count",$profileid,"","","","'I'");
                                $INITIATE_CNT=$contactResultSI[0]['count'];

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
                                $COUNTRY= $country_label;
                                if ($DPP ==  1)
                                        $HAVEPARTNER = 'Y';
                                else
                                        $HAVEPARTNER = 'N';

                                $PROFILELENGTH=$row['PROFILELENGTH'];

                                $LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

				$sql_p = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND MEMBERSHIP='Y'";
				$res_p = mysql_query($sql_p,$db) or logError($sql_p,$db);
				$row_p = mysql_fetch_array($res_p);
				if($row_p['COUNT'] > 0)
					$EVER_PAID = "Y";
				else
					$EVER_PAID = "N";

                                // cretaing content to be written to the file
                                if ($PHONE_MOB && $PHONE_RES)
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$COUNTRY\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
                                }
				elseif ($PHONE_MOB && $PHONE_RES =='')
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_MOB\"".",\"\","."\"$COUNTRY\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
                                }
                                elseif ($PHONE_MOB =='' && $PHONE_RES)
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_RES\"".",\"\","."\"$COUNTRY\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
                                }

                                $data = trim($line)."\n";
                                $output = $data;
                                unset($data);
                                unset($DPP);
                                fwrite($fp,$output);
                        }
                }
        }
	function label_select($table,$value)
	{
		$sql = "SELECT LABEL FROM newjs.$table WHERE VALUE='$value'";
		$res = mysql_query($sql) or logError($sql);//die("Error in Label select".$sql.mysql_error());
		$row = mysql_fetch_array($res);
		$label = $row['LABEL'];
		return $label;
	}

?>
