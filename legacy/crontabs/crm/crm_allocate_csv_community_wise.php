<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


	ini_set("max_execution_time","0");
	include ("../connect.inc");
	include("allocate_functions.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");
	//include ("connect.inc");

	include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

	$mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;

	$db = connect_db();

	for($i=0;$i<$noOfActiveServers;$i++)
        {
                $myDbName=$activeServers[$i];                
		$myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
        }

	$ts = time();
	$ts -= 30*24*60*60;
	$last_day = date("Y-m-d",$ts);

	$sql_mt = "SELECT * FROM incentive.REGION_MTONGUE_MAPPING";
	$res_mt = mysql_query($sql_mt,$db) or die($sql_mt.mysql_error($db));
	while($row_mt = mysql_fetch_array($res_mt))
	{
		if($row_mt['REGION']=="E")
			$east_arr = explode(",",$row_mt['MTONGUE']);
		elseif($row_mt['REGION']=="W")
			$west_arr = explode(",",$row_mt['MTONGUE']);
		elseif($row_mt['REGION']=="S")
			$south_arr = explode(",",$row_mt['MTONGUE']);
		elseif($row_mt['REGION']=="D")
			$dncr_arr = explode(",",$row_mt['MTONGUE']);
	}

	//findng cities which fall under PUNE branch.
	$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY='MH08'";
	$res_city = mysql_query($sql_city,$db) or die($sql_city.mysql_error($db));
	while($row_city = mysql_fetch_array($res_city))
		$city_arr[] = $row_city['VALUE'];

	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"\n";

        $filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_east.txt";
        $filename2 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_west.txt";
        $filename3 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_south.txt";
        $filename4 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_dncr.txt";
        $filename5 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_pune.txt";
        $filename6 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_failed_payments.txt";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");
        $fp3 = fopen($filename3,"w+");
        $fp4 = fopen($filename4,"w+");
        $fp5 = fopen($filename5,"w+");
        $fp6 = fopen($filename6,"w+");

        if(!$fp1 || !$fp2 || !$fp3 || !$fp4 || !$fp5 || !$fp6)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);
        fwrite($fp2,$header);
        fwrite($fp3,$header);
        fwrite($fp4,$header);
        fwrite($fp5,$header);
        fwrite($fp6,$header);
	
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
				$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                                $mysqlObj->ping($myDbArray[$myDbName]);
                                $myDb=$myDbArray[$myDbName];
                                $jpartnerObj->setPROFILEID($profileid);
                                if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
                                        $DPP=1;
                                else
                                        $DPP=0;
				write_contents_to_file($profileid,"","",$failed_payments,$DPP);
			}
		}
		$pidarr[]=$profileid;
	}
	fclose($fp6);

	//MTONGE <> 1 condition added to remove Foreign origin profiles.
	$sql_pid = "SELECT PROFILEID, MTONGUE, SCORE FROM incentive.MAIN_ADMIN_POOL WHERE SCORE >= 324 AND ALLOTMENT_AVAIL='Y' AND TIMES_TRIED<3 AND MTONGUE <> '1'";
	if(is_array($pidarr))
	{
		$pidstr = @implode("','",$pidarr);
		$sql_pid .= " AND PROFILEID NOT IN ('$pidstr')";
	}
	unset($pidarr);
	unset($pidstr);
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	$row_pid = mysql_fetch_array($res_pid);
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
				write_contents_to_file($profileid,$mtongue,$score,'',$DPP);
			}			
		}
	}
        fclose($fp1);
        fclose($fp2);
        fclose($fp3);
        fclose($fp4);
        fclose($fp5);

	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_east.txt";
	$profileid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_west.txt";
	$profileid_file3 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_south.txt";
	$profileid_file4 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_dncr.txt";
	$profileid_file5 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_pune.txt";
	$profileid_file6 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_failed_payments.txt";

	$msg="For east and rest of india: ".$profileid_file1;
	$msg.="\nFor west : ".$profileid_file2;
	$msg.="\nFor south : ".$profileid_file3;
	$msg.="\nFor delhi - NCR : ".$profileid_file4;
	$msg.="\nFor pune : ".$profileid_file5;
	$msg.="\nFor failed payments : ".$profileid_file6;

	$to="anamika.singh@jeevansathi.com,surbhi.aggarwal@naukri.com,mandeep.choudhary@naukri.com,divya.jain@naukri.com,deepak.sharma@naukri.com, samrat.chadha@naukri.com";
	$bcc="shiv.narayan@jeevansathi.com, sriram.viswanathan@jeevansathi.com";
	$sub="Daily CSV for calling";
	$from="From:shiv.narayan@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	mail($to,$sub,$msg,$from);
?>
