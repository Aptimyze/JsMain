<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("allocate_functions.php");
	include("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");
	include("$_SERVER[DOCUMENT_ROOT]/profile/comfunc.inc");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
        $SITE_URL="http://ser6.jeevansathi.com";
        $mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;

        for($i=0;$i<$noOfActiveServers;$i++)
        {
                $myDbName=$activeServers[$i];
                $myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
                mysql_query("set session wait_timeout=10000",$myDbArray[$myDbName]);
        }

	$db = connect_db();

	$ts = time();
        $ts -= 30*24*60*60;
        $last_day = date("Y-m-d",$ts);

	//truncate profile allocation table.
        $sql="TRUNCATE TABLE incentive.MAIN_ADMIN_POOL_BUFFER";
        mysql_query($sql,$db) or die("$sql".mysql_error());

	/*Section to write the data in buffer table*/

	$sql_branches = "SELECT DISTINCT(bc.PRIORITY) AS PRIORITY, b.NAME FROM incentive.BRANCHES b, incentive.BRANCH_CITY bc WHERE b.VALUE=PRIORITY AND PRIORITY != 'UP25'";
	$sql_branches .= " UNION SELECT VALUE,NAME FROM incentive.BRANCHES WHERE VALUE IN ('TN02','KE03')";
	$res_branches = mysql_query($sql_branches,$db) or die("$sql_branches".mysql_error());
	while ($row_branches = mysql_fetch_array($res_branches))
	{
		$branch = strtoupper($row_branches['NAME']);

		//query to find total executives for each Branch
		$sql_center = "SELECT COUNT(*) AS CNT from jsadmin.PSWRDS where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)='$branch' AND ACTIVE='Y'";
		$res_center = mysql_query($sql_center,$db) or die("$sql_center".mysql_error());
		if($row_center = mysql_fetch_array($res_center))
			$total_executives = $row_center['CNT'];

		$TOTAL_PROFILES = $total_executives * 2000;
		$PROFILES_INSERTED = 0;
		$city_res = $row_branches['PRIORITY'];
		// query to find cities which are covered by a particular Branch
		if($city_res == 'KA02' || $city_res == 'AP03' || $city_res == 'WB05')
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY = '$city_res'";
		elseif($city_res == 'MH04')
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'MH%' AND VALUE NOT IN ('AP13','UP31','MH08','MH15','MH16','MH17','MH18','MH19','MH20','MH21','MH22','MH23','MH24','MH25','MH26','MH27')";
		elseif($city_res == 'MH08')
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE = 'MH08'";
                else
                        $sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE = '$city_res'";
		$res_cities = mysql_query ($sql_cities,$db) or die("$sql_cities".mysql_error());
		while($row_cities = mysql_fetch_array($res_cities))
			$cityarr[] = $row_cities['VALUE'];

		if(is_array($cityarr))
			$city_str = "'".implode ("','",$cityarr)."'";
		if($city_str)
		{
			$sql = "SELECT PROFILEID,CITY_RES,SCORE FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND TIMES_TRIED < 3 ORDER BY SCORE DESC";
			$res = mysql_query($sql,$db) or die("$sql".mysql_error());
			while($row = mysql_fetch_array($res))
			{
				unset($allocate);
				$profileid = $row['PROFILEID'];
				$city_profile = $row['CITY_RES'];
				$score = $row['SCORE'];
				$sql_jp = "SELECT PHONE_RES,PHONE_MOB from newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$profileid' AND SUBSCRIPTION='' AND CRM_TEAM='online' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";
				$sql_jp .= " AND ENTRY_DT < DATE_SUB(CURDATE(), INTERVAL 3 DAY)";//Added on the instructions of Vivek
				$res_jp = mysql_query($sql_jp,$db) or die("$sql_jp".mysql_error());
				if($row_jp = mysql_fetch_array($res_jp))
				{
					if(check_profile($profileid))
					{
						$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
                                                $res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error());
                                                // profile has been handled once
                                                if($row_history = mysql_fetch_array($res_history))
                                                {
                                                        if($row_history['ENTRY_DT']<=$last_day)
                                                                $allocate = 1;
                                                }
                                                // new profile
                                                else
                                                {
                                                        $allocate = 1;
                                                }
                                                if($allocate && ($row_jp['PHONE_RES'] || $row_jp['PHONE_MOB']))
                                                {
								$sql_ins = "INSERT IGNORE INTO incentive.MAIN_ADMIN_POOL_BUFFER (PROFILEID,CITY_RES,SCORE) VALUES('$profileid','$city_profile','$score')";
								$res_ins = mysql_query($sql_ins,$db) or die("$sql_ins".mysql_error);
								if($res_ins)
									$PROFILES_INSERTED++;
						}
					}
				}
				if($PROFILES_INSERTED == $TOTAL_PROFILES)
					break;
			}
		}
		unset($city_str);
		unset($cityarr);
	}
	/*Section to write the data in file from table*/

	//define header to write into csv file.
        $header="\"PROFILEID\"".","."\"PHONE_NO1\"".","."\"PHONE_NO2\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS_INITIATED\"".","."\"CONTACTS_ACCEPTED\"".","."\"CONTACTS_RECEIVED\"".","."\"ACCEPTANCE_RECEIVED\"".","."\"DATE_OF_BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED_PARTNER_PROFILE\"".","."\"LAST_LOGIN_DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"".","."\"EVER_PAID\"\n";
	$filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_allocation.txt";
        $fp = fopen($filename,"w+");
        if(!$fp)
        {
                die("no file pointer");
        }
        fwrite($fp,$header);
	$sql_pid = "SELECT * from incentive.MAIN_ADMIN_POOL_BUFFER where 1";
        $res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
        $row_pid = mysql_fetch_array($res_pid);
        while($row_pid = mysql_fetch_array($res_pid))
        {
                $profileid = $row_pid['PROFILEID'];
		$city_profile = $row_pid['CITY_RES'];
		$score = $row_pid['SCORE'];
                $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                $mysqlObj->ping($myDbArray[$myDbName]);
                $myDb=$myDbArray[$myDbName];
                $jpartnerObj->setPROFILEID($profileid);
                if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
                        $DPP=1;
                else
                        $DPP=0;
                write_contents_to_file_allocation($profileid,$city_profile,$score,'',$DPP);
        }
        fclose($fp);
	$profileid_file = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_allocation.txt";
        $msg="For data allocation: ".$profileid_file;

        $to="moiz.saifee@naukri.com";
        $bcc="vibhor.garg@jeevansathi.com";
        $sub="Daily CSV for allocation";
        $from="From:vibhor.garg@jeevansathi.com";
        $from .= "\r\nBcc:$bcc";

        mail($to,$sub,$msg,$from);
	function write_contents_to_file_allocation($profileid,$city_profile="",$score="",$DPP=0)
        {
                global $fp,$db;
         
                $sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB ,COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , CHAR_LENGTH(YOURINFO) + CHAR_LENGTH(FAMILYINFO) + CHAR_LENGTH(SPOUSE) + CHAR_LENGTH(JOB_INFO) + CHAR_LENGTH(SIBLING_INFO) + CHAR_LENGTH(FATHER_INFO) AS PROFILELENGTH FROM newjs.JPROFILE WHERE PROFILEID ='$profileid'";

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
                                        $line="\"$profileid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$city_profile\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
                                }
                                elseif ($PHONE_MOB && $PHONE_RES =='')
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_MOB\"".",\"\","."\"$city_profile\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
                                }
                                elseif ($PHONE_MOB =='' && $PHONE_RES)
                                {
                                        $line="\"$profileid\"".","."\"$PHONE_RES\"".",\"\","."\"$city_profile\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"".","."\"$EVER_PAID\"";
                                }

                                $data = trim($line)."\n";
                                $output = $data;
                                unset($data);
                                unset($DPP);
                                fwrite($fp,$output);
                        }
                }
        }

?>
