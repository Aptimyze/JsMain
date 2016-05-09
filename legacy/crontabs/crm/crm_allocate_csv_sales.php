<?php 
	$curFilePath = dirname(__FILE__)."/"; 
	include_once("/usr/local/scripts/DocRoot.php");

	ini_set("max_execution_time","0");
        chdir(dirname(__FILE__));

	$path =$_SERVER['DOCUMENT_ROOT'];
	include_once("allocate_functions_revamp.php");
	include_once($path."/crm/connect.inc");
	include_once($path."/profile/comfunc.inc");
        include_once($path."/classes/Memcache.class.php");
        include_once($path."/classes/globalVariables.Class.php");
	include_once($path."/classes/shardingRelated.php");
        include_once($path."/classes/Mysql.class.php");
        include_once($path."/classes/Jpartner.class.php");

        $mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;

	$db = connect_db();

        for($i=0;$i<$noOfActiveServers;$i++)
        {
                $myDbName=$activeServers[$i];
                $myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
        }

	$now = date("Y-m-d G:i:s");

	$ts = time();
	$ts -= 3*24*60*60;
	$three_days_before = date("Y-m-d",$ts);
	$ts -= 3*24*60*60;
	$six_days_before = date("Y-m-d",$ts);

	$extra_params["SERVICE_CALL"] = 1;

	$start_date = $six_days_before." 00:00:00";
	$end_date = $three_days_before." 23:59:59";

//	$three_days_before_start = $three_days_before." 00:00:00";
//	$three_days_before_end = $three_days_before." 23:59:59";

	//define header to write into csv file.
	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"\n";

        $filename1 = $path."/crm/csv_files/sales_csv_data_".date('Y-m-d').".txt";

        $fp1 = fopen($filename1,"w+");

        if(!$fp1)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);

	//$sql_purch = "SELECT PROFILEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$three_days_before_start' AND '$three_days_before_end' AND STATUS = 'DONE'";
	$sql_purch = "SELECT PROFILEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND STATUS = 'DONE'";
	$res_purch = mysql_query($sql_purch,$db) or die("$sql_purch".mysql_error($db));
	while($row_purch = mysql_fetch_array($res_purch))
	{
		$profileid=$row_purch['PROFILEID'];
		if(!profile_allocated($profileid,$extra_params))
		{
			if(check_profile($profileid,$extra_params))
			{
				$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                                $mysqlObj->ping($myDbArray[$myDbName]);
                                $myDb=$myDbArray[$myDbName];
                                $jpartnerObj->setPROFILEID($profileid);
                                if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
                                        $DPP=1;
                                else
                                        $DPP=0;
				write_contents_to_file($profileid,"","","",$DPP,$extra_params);
			}
		}
	}
	
        fclose($fp1);

	$profileid_file1 = $SITE_URL."/crm/csv_files/sales_csv_data_".date('Y-m-d').".txt";

	$msg="Service call csv: ".$profileid_file1;

	$to ="nitika.bhatia@jeevansathi.com";
	$cc ="anamika.singh@jeevansathi.com";
	$bcc="vibhor.garg@jeevansathi.com";
	$sub="CSV for service call";
	$from="From:JeevansathiCrm@jeevansathi.com";
	$from .= "\r\nCc:$cc\r\nBcc:$bcc";

	mail($to,$sub,$msg,$from);
?>
