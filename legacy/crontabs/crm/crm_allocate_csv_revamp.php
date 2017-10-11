<?php
        $curFilePath = dirname(__FILE__)."/";
        include_once("/usr/local/scripts/DocRoot.php");

	/*******************************************For testing comment live portion and uncomment the test portion*******************************************/
	//////////////////////////////////
        $start_time=date("Y-m-d H:i:s");
        mail("vibhor.garg@jeevansathi.com","CSV Generation Started At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
	/////////////////////////////////

	ini_set("max_execution_time","0");
	include("allocate_functions_revamp.php");

	/*live*/
	chdir(dirname(__FILE__));
	include ("$docRoot/crontabs/connect.inc");
	include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	/*live*/

	
	$SITE_URL=JsConstants::$ser6Url;


	$filePath   = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/";	
	$filename_1 = "bulk_csv_crm_data_".date('Y-m-d')."_nri1.txt";
        $filename_4 = "bulk_csv_crm_data_".date('Y-m-d')."_noida.dat";
        $filename_5 = "bulk_csv_crm_data_".date('Y-m-d')."_mumbai.dat";
        $filename_6 = "bulk_csv_crm_data_".date('Y-m-d')."_pune.dat";
        $filename_7 = "bulk_csv_crm_data_".date('Y-m-d')."_failed_payments_tech.dat";


	/* For NRI allocation */
        $ts = time();
        $ts -= 30*24*60*60;
        $last_day = date("Y-m-d",$ts);

	//North America, South America, Africa, Europe, West Asia
	$nri_arr=array(1,2,3,60,138,4,139,140,5,6,8,141,9,10,142,143,12,144,145,13,15,16,146,17,19,148,149,21,22,23,150,151,153,24,26,154,27,28,156,29,30,31,33,157,158,34,35,36,160,161,162,163,164,37,39,40,165,167,69,42,43,44,45,168,169,170,172,173,174,175,46,47,176,49,50,53,54,55,177,56,57,58,215,61,62,63,64,65,66,180,181,67,182,68,216,184,185,186,71,72,73,187,74,76,188,189,190,191,77,192,79,81,193,83,195,84,86,87,88,89,90,91,93,94,218,96,197,97,198,199,200,201,202,99,100,204,101,102,104,219,106,107,109,111,206,207,112,113,114,117,118,208,209,120,121,122,210,123,124,125,126,128,127,129,130,132,133,134,135);

        //$filename1 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri1.txt";
	$filename1 =$filePath.$filename_1;

        $fp1 = fopen($filename1,"w+");

	/* NRI allocation ends */

	$db 	= connect_db();
	$db_dnc = connect_dnc();

	//Belonging communities
	$belonging_arr = array(4,5,9,20,21,22,23,24,12,29,32,34,19);

	//Not Belonging communities
	$not_belonging_arr = array(3,16,17,31,4,5,9,20,21,22,23,24,12,29,32,34);

	//Mumbai cities
	$mum_city_arr = array('MH04','MH12','MH13','MH14');

	//Pune cities
	$pune_city_arr = array('MH01','MH02','MH03','MH05','MH06','MH07','MH08','MH09','MH10','MH11','MH15','MH16','MH17','MH18','MH19','MH20','MH21','MH22','MH23','MH24','MH25','MH26','MH27');

	//Not Belonging cities
	$not_belonging_city_arr = array('UP30','BI06','MP09','UP03');

	//Data limit
	$sql_lf="SELECT DATA_LIMIT FROM incentive.LARGE_FILE ORDER BY ENTRY_DT DESC LIMIT 1";
        $res_lf=mysql_query($sql_lf,$db) or die("$sql_lf".mysql_error($db));
        $row_lf=mysql_fetch_assoc($res_lf);
        $limit = $row_lf['DATA_LIMIT'];
	$branch_agents=array();
	$sql_prall = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%PREALL%'";
        $myres_prall=mysql_query($sql_prall,$db) or die("$sql_prall".mysql_error($db));
        while($myrow_prall=mysql_fetch_array($myres_prall))
                $branch_agents[]=$myrow_prall['USERNAME'];
	$branch_agents_str = implode("','",$branch_agents);
	
	//define header to write into csv file.
	//$header="\"PROFILEID\""."|"."\"PRIORITY\""."|"."\"ANALYTIC_SCORE\""."|"."\"OLD_PRIORITY\""."|"."\"DIAL_STATUS\""."|"."\"AGENT\""."|"."\"VD_PERCENT\""."|"."\"LAST_LOGIN_DATE\""."|"."\"PHONE_NO1\""."|"."\"PHONE_NO2\""."|"."\"PHOTO\""."|"."\"DOB\""."|"."\"MSTATUS\""."|"."\"EVER_PAID\""."|"."\"GENDER\""."|"."\"POSTEDBY\""."|"."\"NEW_VARIABLE\""."|"."\"EOI\""."|"."\"TOTAL_ACCEPTANCES\""."|"."\"PHONE1\""."|"."\"PHONE2\""."|"."\"LEAD_ID\"\n";
	$header_fp ="\"PROFILEID\""."|"."\"PRIORITY\""."|"."\"ANALYTIC_SCORE\""."|"."\"OLD_PRIORITY\""."|"."\"DIAL_STATUS\""."|"."\"AGENT\""."|"."\"VD_PERCENT\""."|"."\"LAST_LOGIN_DATE\""."|"."\"PHONE_NO1\""."|"."\"PHONE_NO2\""."|"."\"PHOTO\""."|"."\"DOB\""."|"."\"MSTATUS\""."|"."\"EVER_PAID\""."|"."\"GENDER\""."|"."\"POSTEDBY\""."|"."\"NEW_VARIABLE\""."|"."\"EOI\""."|"."\"TOTAL_ACCEPTANCES\""."|"."\"PHONE1\""."|"."\"PHONE2\""."|"."\"LEAD_ID\""."|"."\"CITY_RES\""."|"."\"TIMESTAMP\"\n";

	/*
	$filename4 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_noida.dat";
	$filename5 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mumbai.dat";
	$filename6 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_pune.dat";
	$filename7 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_failed_payments_tech.dat";
	*/
	$filename4 =$filePath.$filename_4;
	$filename5 =$filePath.$filename_5;
	$filename6 =$filePath.$filename_6;
	$filename7 =$filePath.$filename_7;

        $fp4 = fopen($filename4,"w+");
        $fp5 = fopen($filename5,"w+");
	$fp6 = fopen($filename6,"w+");
        $fp7 = fopen($filename7,"w+");

        if(!$fp1 || !$fp4 || !$fp5 || !$fp6 ||!$fp7)
        {
                die("no file pointer");
        }

        //fwrite($fp1,$header);
	//fwrite($fp4,$header);
        //fwrite($fp5,$header);
        //fwrite($fp6,$header);
        fwrite($fp7,$header_fp);

	unset($pidarr);
	unset($pidstr);

	//finding user's who tried payment.
	
	$sql = "SELECT DISTINCT PROFILEID FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) UNION SELECT DISTINCT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND PAGE>1";
	$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($myrow=mysql_fetch_array($myres))
	{
		$profileid=$myrow['PROFILEID'];
		if(!profile_allocated($profileid))
		{
			if(check_profile($profileid))
			{
				$failed_payments = 1;
				$sql_md="select ALLOTED_TO from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
			        $res_md = mysql_query($sql_md,$db) or die("$sql_md".mysql_error($db));
			        if($row_md = mysql_fetch_array($res_md))
			                $alloted = $row_md["ALLOTED_TO"];
				write_contents_to_file($profileid,"","",$failed_payments,$alloted);
			}
		}
		$pidarr[]=$profileid;
	}
	fclose($fp7);
	$sql="TRUNCATE TABLE incentive.TEMP_CSV_PROFILES_TECH";
	mysql_query($sql,$db) or die("$sql".mysql_error($db));
	
	$sql="insert ignore into incentive.TEMP_CSV_PROFILES_TECH (PROFILEID, SCORE, MTONGUE, CITY_RES) select PROFILEID, ANALYTIC_SCORE, MTONGUE, CITY_RES from incentive.MAIN_ADMIN_POOL WHERE ANALYTIC_SCORE>=1 AND ANALYTIC_SCORE<=100 AND CUTOFF_DT>=DATE_SUB(CURDATE(),INTERVAL 10 DAY) AND MTONGUE <> '1'";
	if(is_array($pidarr))
	{
		$pidstr = @implode("','",$pidarr);
		$sql .= " AND PROFILEID NOT IN ('$pidstr')";
	}
	mysql_query($sql,$db) or die("$sql".mysql_error($db));
	unset($pidarr);
	unset($pidstr);
	
	//Allocated
        $sql_md="select PROFILEID,ALLOTED_TO from incentive.MAIN_ADMIN WHERE ALLOTED_TO NOT IN ('$branch_agents_str')";
        $res_md = mysql_query($sql_md,$db) or die("$sql_md".mysql_error($db));
        while($row_md = mysql_fetch_array($res_md))
        {
                $pid_md = $row_md["PROFILEID"];
		$alloted = $row_md["ALLOTED_TO"];
        	$sql2="update incentive.TEMP_CSV_PROFILES_TECH set ALLOTED_TO='$alloted' where PROFILEID='$pid_md'";
	        mysql_query($sql2,$db) or die("$sql1".mysql_error($db));
	}
        //end 

	minimize_data();
	
	$sql_pid = "SELECT PROFILEID, SCORE, MTONGUE, CITY_RES, ALLOTED_TO from incentive.TEMP_CSV_PROFILES_TECH ORDER BY SCORE DESC";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));

	while($row_pid = mysql_fetch_array($res_pid))
	{
		$profileid = $row_pid['PROFILEID'];
		$mtongue = $row_pid['MTONGUE'];
		$score = $row_pid['SCORE'];
		$city = $row_pid['CITY_RES'];
		$alloted_to = $row_pid['ALLOTED_TO'];
		$sql_lim = "SELECT ISD,COUNTRY_RES from newjs.JPROFILE where PROFILEID = '$profileid'";
        	$res_lim = mysql_query($sql_lim,$db) or die("$sql_lim".mysql_error($db));
		if($row_lim = mysql_fetch_array($res_lim))
        	{
			$isd = $row_lim["ISD"]; 
			$country = $row_lim["COUNTRY_RES"];
		}
		$cnt1=count(file($filename4));
		$cnt2=count(file($filename5));
		$cnt3=count(file($filename6));
		$cnt4=count(file($filename1));
		if($cnt1<=$limit || $cnt2<=$limit || $cnt3<=$limit || $cnt4<=$limit)
		{
			if(limit_check($cnt1,$cnt2,$cnt3,$mtongue,$city,$isd,$country))
				write_contents_to_file($profileid,$mtongue,$score,'',$alloted_to);
		}
		else
			break;
	}
	
	fclose($fp1);
        fclose($fp4);
        fclose($fp5);
	fclose($fp6);
	//////////////////////////////////
        $end_time=date("Y-m-d H:i:s");
        mail("vibhor.garg@jeevansathi.com","CSV Generation Completed At $end_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
        /////////////////////////////////
	
	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nri1.txt";
	$profileid_file4 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_noida.dat";
        $profileid_file5 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mumbai.dat";
        $profileid_file6 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_pune.dat";
        $profileid_file7 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_failed_payments_tech.dat";

        $msg.="\nFor NRI : ".$profileid_file1;
	$msg.="\nFor Noida : ".$profileid_file4;
        $msg.="\nFor Mumbai : ".$profileid_file5;
        $msg.="\nFor Pune : ".$profileid_file6;
        $msg.="\nFor Failed Payments : ".$profileid_file7;


	$cmd1 = "sed -i 's/\"//g' $filename4";
        shell_exec($cmd1);
	$cmd2 = "sed -i 's/\"//g' $filename5";
        shell_exec($cmd2);
	$cmd3 = "sed -i 's/\"//g' $filename6";
        shell_exec($cmd3);

	$to="anamika.singh@jeevansathi.com,princy.gulati@jeevansathi.com,bharat.vaswani@jeevansathi.com,nidhi.aneja@jeevansathi.com,manish.raj@jeevansathi.com,amit.malhotra@jeevansathi.com,prakash.sangam@naukri.com";
 	$bcc="vibhor.garg@jeevansathi.com,aman.sharma@jeevansathi.com,manoj.rana@naukri.com";
	/* file sorting logic */
	$filenameArr =array($filename_4,$filename_5,$filename_6);
	foreach($filenameArr as $key=>$filenameVal)	
		sortFileContent($filenameVal,$filePath);
	$sub="Revamp CSVs for calling";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	/*live*/
	mail($to,$sub,$msg,$from);
	/*live*/

	function limit_check($cnt1,$cnt2,$cnt3,$mtongue,$city,$ISD,$country)
	{
		global $belonging_arr, $not_belonging_arr, $pune_city_arr , $not_belonging_city_arr , $nri_arr , $limit;
		if($ISD && ($ISD=='91' || $ISD=='0091' || $ISD=='+91'))
                {
			if(in_array($mtongue,$belonging_arr) && in_array($city,$pune_city_arr))
				$eligible_for = 3;
			elseif(in_array($mtongue,$belonging_arr) && !in_array($city,$not_belonging_city_arr))
                        	$eligible_for = 2;
			elseif(!in_array($mtongue,$not_belonging_arr) && !in_array($city,$not_belonging_city_arr))
        	                $eligible_for = 1;
		}
		elseif(in_array($country,$nri_arr))
			$eligible_for = 4;
		switch ($eligible_for) {
    		case 1:
		        if($cnt1>$limit)
				return 0;
			else
				return 1;
		        break;
		case 2:
			if($cnt2>$limit)
                                return 0;
                        else
                                return 1;
                        break;

    		case 3:
			if($cnt3>$limit)
                                return 0;
                        else
                                return 1;
                        break;
		case 4:
                        if($cnt4>$limit)
                                return 0;
                        else
                                return 1;
                        break;
		}
	}
?>
