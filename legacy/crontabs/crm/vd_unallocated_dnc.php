<?php
        $curFilePath = dirname(__FILE__)."/";
        include_once("/usr/local/scripts/DocRoot.php");

        ini_set("max_execution_time","0");
        include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");
        include_once(JsConstants::$cronDocRoot."/crontabs/crm/allocate_functions_revamp.php");
        $symfonyFilePath = JsConstants::$docRoot."/../";
        include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");

	$filePath   = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/";	
        $filename_7 = "vd_unallocated_dnc_data_".date('Y-m-d').".dat";

	$db 	= connect_db();
	$db_dnc = connect_dnc();
        $db737  =connect_737();

	$filename7 =$filePath.$filename_7;
        $fp7 = fopen($filename7,"w+");
        if(!$fp7)
                die("no file pointer");

	$sdate='2014-03-08';
	$edate='2014-03-15';
        $sql="select PROFILEID,DISCOUNT from billing.VARIABLE_DISCOUNT where SDATE='$sdate' AND EDATE='$edate'";
	$res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($row=mysql_fetch_array($res))
	{
		$profileid =$row['PROFILEID'];
		$vd        =$row['DISCOUNT'];		

		$sqlM = "SELECT PROFILEID from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
		$resM=mysql_query($sqlM,$db) or die("$sqlM".mysql_error($db));
		$count=mysql_num_rows($resM);
		if(count>0)
			continue;
	
		$sqlJ ="select USERNAME,LAST_LOGIN_DT,CITY_RES,PHONE_MOB,PHONE_WITH_STD from newjs.JPROFILE where PROFILEID='$profileid'";	
		$resJ=mysql_query($sqlJ,$db737) or die("$sqlJ".mysql_error($db));
		if($rowJ=mysql_fetch_array($resJ)){
			
			$username 		=$rowJ['USERNAME'];
			$lastLoginDt 		=$rowJ['LAST_LOGIN_DT'];	
			$cityRes        	=FieldMap::getFieldLabel('city',$rowJ['CITY_RES']);

			$phone1 		=phoneNumberCheck($rowJ['PHONE_MOB']);
			$phone2             	=phoneNumberCheck($rowJ['PHONE_WITH_STD']);
			if(!$phone1 && !$phone2)
				continue;
			if($phone1)
				$phoneNumArr['PHONE1'] 	=$phone1;
			if($phone2)
				$phoneNumArr['PHONE2']	=$phone2;

                        $phoneNumArray 		=checkDNC($phoneNumArr);
                        $isDNC 			=$phoneNumArray["STATUS"];
			if($isDNC){
				$sqlP ="select ANALYTIC_SCORE from incentive.MAIN_ADMIN_POOL where PROFILEID='$profileid'";
				$resP=mysql_query($sqlP,$db737) or die("$sqlP".mysql_error($db));
				if($rowP=mysql_fetch_array($resP))
					$analyticScore =$rowP['ANALYTIC_SCORE'];

				$line =$username."|".$cityRes."|".$vd."|".$analyticScore."|".$lastLoginDt."|".$phone1."|".$phone2;		
				$data =trim($line)."\n";
				fwrite($fp7,$data);
			}
		}
	}
	fclose($fp7);
	
	/*
        $profileid_file7 = $SITE_URL."/crm/csv_files/vd_unallocated_dnc_data_".date('Y-m-d').".dat";
        $msg.="\nUnallocated DNC profile : ".$profileid_file7;
	$to ="manoj.rana@naukri.com";
	$sub="Unallocated DNC profile";
	$from="From:vibhor.garg@jeevansathi.com";
	mail($to,$sub,$msg,$from);
	*/
?>
