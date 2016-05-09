<?php
	/*  New Registration Sales CSV */

	$curFilePath = dirname(__FILE__)."/";
        include_once("/usr/local/scripts/DocRoot.php");

       /******** Mail for CSV generation Start ******/
        $start_time=date("Y-m-d H:i:s");
        mail("manoj.rana@naukri.com","New Registration Sales Generation Started At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");

        ini_set("max_execution_time","0");
        include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");
	include_once(JsConstants::$cronDocRoot."/crontabs/crm/allocate_functions_revamp.php");
        $symfonyFilePath = JsConstants::$docRoot."/../";
        include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");

        $filename1 	=$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/newRegistrationSalesCsv_".date('Y-m-d').".dat";
        $fp1 		=fopen($filename1,"w+");
	if(!$fp1)
		die("no file pointer");

	// DB Connection 
	$db 		=connect_db();
	$db737  	=connect_737();

	// Patrameters defined
	$SITE_URL       	=JsConstants::$siteUrl;
        $today          	=date("Y-m-d");
        $yesterdayDt    	=date("Y-m-d",strtotime("$today -1 day"));
	$exclude_mtongue	="1,3,16,17,31";
	$includeCityRes		=array("UP12","UP25","UP47","UP21","HA03","HA02","UK05","UP06","UP01","DE","DE00","RA07");
	$includeCityStr 	="'".@implode("','",$includeCityRes)."'";	

	// Temporary table reset
	$sql="TRUNCATE TABLE incentive.TEMP_NEW_REGISTRATION_SALES";
	mysql_query($sql,$db) or die("$sql".mysql_error($db));

	// Fetch records
	$sql="select PROFILEID,USERNAME,GENDER,AGE,ENTRY_DT,CITY_RES,PHONE_MOB,PHONE_WITH_STD,SUBSCRIPTION,RELATION,PINCODE from newjs.JPROFILE WHERE ENTRY_DT>='$yesterdayDt 14:30:00' AND ENTRY_DT<='$today 14:30:00' AND MTONGUE NOT IN($exclude_mtongue) AND PHONE_FLAG!='I' AND INCOMPLETE='N' AND ISD IN('91','0091','+91') AND CITY_RES IN($includeCityStr)";
	$res =mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($row=mysql_fetch_array($res))
	{
		$gender 	=$row['GENDER'];
		$age 		=$row['AGE'];
		$subscription	=$row['SUBSCRIPTION'];	
		if( ($gender=='M' && $age<='23') || (strstr($subscription,"F")!="") || (strstr($subscription,"D")!=""))
			continue;
		 $profileid      =$row['PROFILEID'];

		// phone validation start
                $sql_AN = "SELECT ALT_MOBILE from newjs.JPROFILE_CONTACT where PROFILEID ='$profileid'";
                $res_AN = mysql_query($sql_AN,$db) or die("$sql_AN".mysql_error($db));
                $row_AN=mysql_fetch_array($res_AN);
                $alternateNum=$row_AN['ALT_MOBILE'];

		$mobile1  	=checkPhoneNumberValidity($row['PHONE_MOB']);
		$mobile2  	=checkPhoneNumberValidity($alternateNum);
		$landline 	=checkPhoneNumberValidity($row['PHONE_WITH_STD']);
		if(!$mobile1 && !$mobile2 && !$landline)
			continue;	
		// phone validation ends

                $username       =$row['USERNAME'];
                $gender         =$row['GENDER'];
                $entry_dt       =$row['ENTRY_DT'];
                $cityRes      	=$row['CITY_RES'];
                $relation       =$row['RELATION'];
		$pincode	=$row['PINCODE'];
	
		$sqlIns ="insert ignore into incentive.TEMP_NEW_REGISTRATION_SALES(`PROFILEID`,`USERNAME`,`GENDER`,`ENTRY_DT`,`CITY_RES`,`RELATION`,`MOBILE1`,`MOBILE2`,`LANDLINE`,`PINCODE`) value('$profileid','$username','$gender','$entry_dt','$cityRes','$relation','$mobile1','$mobile2','$landline','$pincode')";
		mysql_query($sqlIns,$db) or die("$sqlIns".mysql_error($db));	
	}

	// minimize data set	
	minimize_reg_sales_data();
	
	// Fetch data details
	$sql_pid = "SELECT * from incentive.TEMP_NEW_REGISTRATION_SALES ORDER BY PROFILEID DESC";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	while($row_pid = mysql_fetch_array($res_pid))
	{
		$profileid 	=$row_pid['PROFILEID'];
		$username	=$row_pid['USERNAME'];	
		$gender		=$row_pid['GENDER'];
		$entry_dt	=$row_pid['ENTRY_DT'];	
		$cityRes	=FieldMap::getFieldLabel('city',$row_pid['CITY_RES']);
		$relation	=FieldMap::getFieldLabel('relation',$row_pid['RELATION']);
		$mobile1	=$row_pid['MOBILE1'];
		$mobile2	=$row_pid['MOBILE2'];
		$landline	=$row_pid['LANDLINE'];
		$pincode 	=$row_pid['PINCODE'];
		$phoneNumArr	=array("MOBILE1"=>"$mobile1","MOBILE2"=>"$mobile2","LANDLINE"=>"$landline");
		write_contents_reg_sales_file($profileid,$username,$gender,$entry_dt,$cityRes,$relation,$phoneNumArr,$pincode);
	}
	fclose($fp1);

	 /******** Mail for CSV Completion  *************************/
        $end_time=date("Y-m-d H:i:s");
        mail("manoj.rana@naukri.com","New Registration Sales Generation Completed At $end_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
	
	$profileid_file = $SITE_URL."/crm/csv_files/newRegistrationSalesCsv_".date('Y-m-d').".dat";
        $msg.="\n New registration sales calling file : ".$profileid_file;

	$to="rohan.mathur@jeevansathi.com,manish.raj@jeevansathi.com";
 	$bcc="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
	//$to ="manoj.rana@naukri.com";
	$sub="New registration sales calling file";
	$from="From:JeevansathiCrm@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";
	/* live mail */
	mail($to,$sub,$msg,$from);

	// phone number validity check
	function checkPhoneNumberValidity($phoneNum='')
	{
		global $db737;
		if(!$phoneNum)
			return;
		$phoneNumber =phoneNumberCheck($phoneNum);
		if($phoneNumber){
			$sql ="select PHONE_NUM from newjs.PHONE_JUNK WHERE PHONE_NUM IN('$phoneNumber','0$phoneNumber')";
		        $res = mysql_query($sql,$db737) or die("$sql".mysql_error($db737));
		        if($row = mysql_fetch_array($res))
				$phoneJunk =true;
		}
		if($phoneNumber && !$phoneJunk)
			return $phoneNumber;
		return;	
	}

        function minimize_reg_sales_data()
        {
        	global $db;
                mysql_ping($db);
 
                // Negative profile list filter 
                $sql="delete incentive.TEMP_NEW_REGISTRATION_SALES.* from incentive.TEMP_NEW_REGISTRATION_SALES , incentive.NEGATIVE_PROFILE_LIST where incentive.TEMP_NEW_REGISTRATION_SALES.PROFILEID=incentive.NEGATIVE_PROFILE_LIST.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));
 
                //Do not call filter
                $sql="delete incentive.TEMP_NEW_REGISTRATION_SALES.* from incentive.TEMP_NEW_REGISTRATION_SALES,incentive.DO_NOT_CALL b where incentive.TEMP_NEW_REGISTRATION_SALES.PROFILEID=b.PROFILEID";
		mysql_query($sql,$db) or die("$sql".mysql_error($db));

                //Negative treatment list filter
                $sql="delete incentive.TEMP_NEW_REGISTRATION_SALES.* from incentive.TEMP_NEW_REGISTRATION_SALES,incentive.NEGATIVE_TREATMENT_LIST b where incentive.TEMP_NEW_REGISTRATION_SALES.PROFILEID=b.PROFILEID AND b.FLAG_OUTBOUND_CALL='N'";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

                // Registration sales log filter
                $sql="delete incentive.TEMP_NEW_REGISTRATION_SALES.* from incentive.TEMP_NEW_REGISTRATION_SALES,incentive.NEW_REGISTRATION_SALES_LOG b where incentive.TEMP_NEW_REGISTRATION_SALES.PROFILEID=b.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));

                // Current allocation filter
                $sql="delete incentive.TEMP_NEW_REGISTRATION_SALES.* from incentive.TEMP_NEW_REGISTRATION_SALES,incentive.MAIN_ADMIN b where incentive.TEMP_NEW_REGISTRATION_SALES.PROFILEID=b.PROFILEID";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));
	}
	function write_contents_reg_sales_file($profileid,$username,$gender,$entry_dt,$cityRes,$relation,$phoneNumArr,$pincode)
        {
                global $db,$fp1;

		// phone Numbers with prefix 0
		$mobile1 =$phoneNumArr['MOBILE1'];
		$mobile2 =$phoneNumArr['MOBILE2']; 
		$landline=$phoneNumArr['LANDLINE'];

		$entry_dt =strftime("%Y-%m-%d %H:%M",strtotime("$entry_dt + 09 hours 30 minutes"));

		if($cityRes != 'Delhi' && $cityRes != 'New Delhi')
                        $locality = $cityRes;
                else
		{
			if($pincode!='')
			{
				$sql="select LOCALITY from newjs.PINCODE_MAPPING WHERE PINCODE='$pincode'";
			        $res =mysql_query($sql,$db) or die("$sql".mysql_error($db));
			        if($row=mysql_fetch_array($res))
					$locality = $row['LOCALITY'];
			}
			else
				$locality = '';
		}
		
		$line="$username"."|"."$entry_dt"."|"."$gender"."|"."$relation"."|"."$cityRes"."|"."$locality"."|"."$mobile1"."|"."$landline"."|"."$mobile2"."\n";
                fwrite($fp1,$line);

                // logging of profileid 
                $sql="insert ignore into incentive.NEW_REGISTRATION_SALES_LOG(`PROFILEID`) VALUES($profileid)";
                mysql_query($sql,$db) or die("$sql".mysql_error($db));
         }
?>
