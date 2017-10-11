<?php
	/*******************************************For testing comment live portion and uncomment the test portion*******************************************/
        ini_set("max_execution_time","0");
        $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;//live
        //$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;//test
	include($_SERVER['DOCUMENT_ROOT']."/sugarcrm/custom/include/language/en_us.lang.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

	$SITE_URL=JsConstants::$ser6Url;
	$db = connect_slave();
	$db_dnc = mysql_connect(MysqlDbConstants::$dnc[HOST].":".MysqlDbConstants::$dnc[PORT],MysqlDbConstants::$dnc[USER],MysqlDbConstants::$dnc[PASS]) or die("Unable to connect to dnc server");//live
	//$db_dnc = $db;//test
	global $app_list_strings;

	//Marathi+Konkani
	$mt_arr1 = array(20,34);

	//Hindi and Other
	$mt_arr2 = array(10,19,33,27,7,28,13,14,15,30,12,9,2,18,6,25,5,4,21,22,23,24,29,32);

	//define header to write into csv file.
	$header="\"LEAD ID\"".","."\"LEAD NAME\"".","."\"AGE\"".","."\"GENDER\"".","."\"MOTHER TONGUE\"".","."\"PHONE_NO1\"".","."\"PHONE_NO2\"".","."\"ENQUIRER NAME\"".","."\"EMAIL\"".","."\"CAMPAIGN USERNAME\"".","."\"CAMPAIGN NEWSPAPER DATE\"".","."\"JS USERNAME\"".","."\"PASSWORD\"\n";

        $filename1 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_reg_incomp.txt";
        $filename2 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_reg_incomp.txt";
	$filename3 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp.txt";
        $filename4 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp.txt";
	$filename5 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp_email.txt";
        $filename6 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp_email.txt";
	$filename7 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_reg_incomp_6mO.txt";
        $filename8 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_reg_incomp_6mO.txt";
        $filename9 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp_6mO.txt";
        $filename10 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp_6mO.txt";
        $filename11 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp_email_6mO.txt";
        $filename12 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp_email_6mO.txt";
	$filename13 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_reg_incomp.txt";
        $filename14 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_reg_incomp.txt";
        $filename15 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp.txt";
        $filename16 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp.txt";
        $filename17 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp_email.txt";
        $filename18 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp_email.txt";
        $filename19 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_reg_incomp_6mO.txt";
        $filename20 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_reg_incomp_6mO.txt";
        $filename21 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp_6mO.txt";
        $filename22 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp_6mO.txt";
        $filename23 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp_email_6mO.txt";
        $filename24 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp_email_6mO.txt";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");
	$fp3 = fopen($filename3,"w+");
	$fp4 = fopen($filename4,"w+");
        $fp5 = fopen($filename5,"w+");
        $fp6 = fopen($filename6,"w+");
	$fp7 = fopen($filename7,"w+");
        $fp8 = fopen($filename8,"w+");
        $fp9 = fopen($filename9,"w+");
        $fp10 = fopen($filename10,"w+");
        $fp11 = fopen($filename11,"w+");
        $fp12 = fopen($filename12,"w+");
	$fp13 = fopen($filename13,"w+");
        $fp14 = fopen($filename14,"w+");
        $fp15 = fopen($filename15,"w+");
        $fp16 = fopen($filename16,"w+");
        $fp17 = fopen($filename17,"w+");
        $fp18 = fopen($filename18,"w+");
        $fp19 = fopen($filename19,"w+");
        $fp20 = fopen($filename20,"w+");
        $fp21 = fopen($filename21,"w+");
        $fp22 = fopen($filename22,"w+");
        $fp23 = fopen($filename23,"w+");
        $fp24 = fopen($filename24,"w+");


        if(!$fp1 || !$fp2 || !$fp3 || !$fp4 || !$fp5 || !$fp6 || !$fp7 || !$fp8 || !$fp9 || !$fp10 || !$fp11 || !$fp12 || !$fp13 || !$fp14 || !$fp15 || !$fp16|| !$fp17 || !$fp18 || !$fp19 || !$fp20 || !$fp21 || !$fp22 || !$fp23 || !$fp24)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);
        fwrite($fp2,$header);
	fwrite($fp3,$header);
	fwrite($fp4,$header);
        fwrite($fp5,$header);
        fwrite($fp6,$header);
	fwrite($fp7,$header);
        fwrite($fp8,$header);
        fwrite($fp9,$header);
        fwrite($fp10,$header);
        fwrite($fp11,$header);
        fwrite($fp12,$header);
	fwrite($fp13,$header);
        fwrite($fp14,$header);
        fwrite($fp15,$header);
        fwrite($fp16,$header);
        fwrite($fp17,$header);
        fwrite($fp18,$header);
	fwrite($fp19,$header);
        fwrite($fp20,$header);
        fwrite($fp21,$header);
        fwrite($fp22,$header);
        fwrite($fp23,$header);
        fwrite($fp24,$header);

	$dt_7day=date("Y-m-d",time()-7*86400);
	$sqlj="SELECT id,phone_home,phone_mobile FROM sugarcrm_housekeeping.inactive_leads WHERE deleted=0";
        $resj=mysql_query($sqlj,$db) or die(mysql_error());
        while($rowj = mysql_fetch_array($resj))
	{
		$ok=0;
		$leadid = $rowj['id'];
		if($rowj['phone_home'] || $rowj['phone_mobile'])
	                $ok=1;

		$sql_lid = "SELECT enquirer_mobile_no_c,enquirer_landline_c,age_c,date_birth_c,gender_c,mother_tongue_c,std_c,std_enquirer_c,jsprofileid_c FROM sugarcrm_housekeeping.inactive_leads_cstm WHERE id_c='$leadid'";
		$res_lid = mysql_query($sql_lid,$db) or die(mysql_error());
	        if($row_lid = mysql_fetch_array($res_lid))
        	{
			unset($allow);
			$age = $row_lid['age_c'];
                	if(!$age)
                	{
				if($row_lid['date_birth_c']!='0000-00-00')
					$dob=$row_lid['date_birth_c'];
				if($dob)
					$age=getAge($dob);
                	}
	        	$gender_val = $row_lid['gender_c'];
			$gender = $app_list_strings['gender_list'][$gender_val];
                        $mother_tongue_val = $row_lid['mother_tongue_c'];
                        $mother_tongue = $app_list_strings['Mtongue'][$mother_tongue_val];
                        $enq_mobile = $row_lid['enquirer_mobile_no_c'];
                        $enq_landline = $row_lid['enquirer_landline_c'];
                        $std_enquirer = $row_lid['std_enquirer_c'];
                        $std_lead = $row_lid['std_c'];
                        $js_username = $row_lid['jsprofileid_c'];

			if($gender_val == 'M' && $age<23)
				$allow=0;
			else
			{
        	        	if(!$ok)
	        	        {
					if($row_lid['enquirer_mobile_no_c'] || $row_lid['enquirer_landline_c'])
						$allow=1;
					else
        	                        	$allow=0;
	        	        }
				else
					$allow=1;
			}
			if($allow)
				write_contents_to_file_ltf($leadid,$age,$gender,$mother_tongue,$mother_tongue_val,$enq_mobile,$enq_landline,$std_enquirer,$std_lead,$js_username);
        	}
	}

        fclose($fp1);
        fclose($fp2);
	fclose($fp3);
	fclose($fp4);
        fclose($fp5);
        fclose($fp6);
	fclose($fp7);
        fclose($fp8);
        fclose($fp9);
        fclose($fp10);
        fclose($fp11);
        fclose($fp12);
	fclose($fp13);
        fclose($fp14);
        fclose($fp15);
        fclose($fp16);
        fclose($fp17);
        fclose($fp18);
	fclose($fp19);
        fclose($fp20);
        fclose($fp21);
        fclose($fp22);
        fclose($fp23);
        fclose($fp24);


	$leadid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_reg_incomp.txt";
        $leadid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_reg_incomp.txt";
        $leadid_file3 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp.txt";
        $leadid_file4 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp.txt";
        $leadid_file5 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp_email.txt";
        $leadid_file6 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp_email.txt";
        $leadid_file7 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_reg_incomp_6mO.txt";
        $leadid_file8 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_reg_incomp_6mO.txt";
        $leadid_file9 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp_6mO.txt";
        $leadid_file10 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp_6mO.txt";
        $leadid_file11 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_ndnc_non_reg_incomp_email_6mO.txt";
        $leadid_file12 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mk_dnc_non_reg_incomp_email_6mO.txt";
        $leadid_file13 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_reg_incomp.txt";
        $leadid_file14 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_reg_incomp.txt";
        $leadid_file15 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp.txt";
        $leadid_file16 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp.txt";
        $leadid_file17 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp_email.txt";
        $leadid_file18 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp_email.txt";
        $leadid_file19 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_reg_incomp_6mO.txt";
        $leadid_file20 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_reg_incomp_6mO.txt";
        $leadid_file21 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp_6mO.txt";
        $leadid_file22 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp_6mO.txt";
        $leadid_file23 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_ndnc_non_reg_incomp_email_6mO.txt";
        $leadid_file24 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_rest_dnc_non_reg_incomp_email_6mO.txt";


	$msg.="\nFor Marathi+Konkani(NDNC) Registered Incomplete :".$leadid_file1;
	$msg.="\nFor Marathi+Konkani(DNC) Registered Incomplete :".$leadid_file2;
	$msg.="\nFor Marathi+Konkani(NDNC) Non-registered Incomplete :".$leadid_file3;
        $msg.="\nFor Marathi+Konkani(DNC) Non-registered Incomplete :".$leadid_file4;
	$msg.="\nFor Marathi+Konkani(NDNC) Non-registered Incomplete (Email) :".$leadid_file5;
        $msg.="\nFor Marathi+Konkani(DNC) Non-registered Incomplete (Email) :".$leadid_file6;
	$msg.="\nFor Marathi+Konkani(NDNC) Registered Incomplete {6 months Old} :".$leadid_file7;
        $msg.="\nFor Marathi+Konkani(DNC) Registered Incomplete {6 months Old} :".$leadid_file8;
        $msg.="\nFor Marathi+Konkani(NDNC) Non-registered Incomplete {6 months Old} :".$leadid_file9;
        $msg.="\nFor Marathi+Konkani(DNC) Non-registered Incomplete {6 months Old} :".$leadid_file10;
        $msg.="\nFor Marathi+Konkani(NDNC) Non-registered Incomplete (Email) {6 months Old} :".$leadid_file11;
        $msg.="\nFor Marathi+Konkani(DNC) Non-registered Incomplete (Email) {6 months Old} :".$leadid_file12;
	$msg.="\nFor Rest(NDNC) Registered Incomplete :".$leadid_file13;
        $msg.="\nFor Rest(DNC) Registered Incomplete :".$leadid_file14;
        $msg.="\nFor Rest(NDNC) Non-registered Incomplete :".$leadid_file15;
        $msg.="\nFor Rest(DNC) Non-registered Incomplete :".$leadid_file16;
        $msg.="\nFor Rest(NDNC) Non-registered Incomplete (Email) :".$leadid_file17;
        $msg.="\nFor Rest(DNC) Non-registered Incomplete (Email) :".$leadid_file18;
        $msg.="\nFor Rest(NDNC) Registered Incomplete {6 months Old} :".$leadid_file19;
        $msg.="\nFor Rest(DNC) Registered Incomplete {6 months Old} :".$leadid_file20;
        $msg.="\nFor Rest(NDNC) Non-registered Incomplete {6 months Old} :".$leadid_file21;
        $msg.="\nFor Rest(DNC) Non-registered Incomplete {6 months Old} :".$leadid_file22;
        $msg.="\nFor Rest(NDNC) Non-registered Incomplete (Email) {6 months Old} :".$leadid_file23;
        $msg.="\nFor Rest(DNC) Non-registered Incomplete (Email) {6 months Old} :".$leadid_file24;

	$to="manish.raj@jeevansathi.com";
	$bcc="vibhor.garg@jeevansathi.com,aman.sharma@jeevansathi.com";
	$sub="LTF CSVs";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	/*live*/
	mail($to,$sub,$msg,$from);
	/*live*/

	function write_contents_to_file_ltf($leadid,$age,$gender,$mother_tongue,$mother_tongue_val,$enq_mobile,$enq_landline,$std_enquirer,$std_lead,$js_username)
        {
                global $fp1, $fp2, $fp3, $fp4, $fp5, $fp6, $fp7, $fp8, $fp9, $fp10, $fp11, $fp12, $fp13, $fp14, $fp15, $fp16, $fp17, $fp18, $fp19, $fp20, $fp21, $fp22, $fp23, $fp24, $mt_arr1, $mt_arr2, $db, $db_dnc;
		$phone_no1='';
		$phone_no2='';

		$sql2 = "SELECT email_address_id FROM sugarcrm_housekeeping.inactive_email_addr_bean_rel where bean_id='$leadid'";
                $res2 = mysql_query($sql2,$db) or logError($sql2,$db);
                if ($row2 = mysql_fetch_array($res2))
                {
                        $email_address_id = $row2['email_address_id'];
			$sql21 = "SELECT email_address FROM sugarcrm_housekeeping.inactive_email_addresses where id='$email_address_id'";
                	$res21 = mysql_query($sql21,$db) or logError($sql21,$db);
	                if ($row21 = mysql_fetch_array($res21))
                	        $email = $row21['email_address'];
                }

		$timestamp_6mon=time()-183*86400;	
		$sql3 = "SELECT assistant,campaign_id,last_name,phone_home,phone_mobile,date_entered,status FROM sugarcrm_housekeeping.inactive_leads WHERE id ='$leadid'";
                $res3 = mysql_query($sql3,$db) or logError($sql3,$db);
                if ($row3 = mysql_fetch_array($res3))
		{
			$lead_mobile=$row3['phone_mobile'];
			$lead_landline = $row3['phone_home'];				
                        $enquirer_name = $row3['assistant'];
			$lead_name = $row3['last_name'];
			$campaign_id = $row3['campaign_id'];
			$lead_time =JSstrToTime($row3['date_entered']);
                        if($lead_time <= $timestamp_6mon)
                        	$old = 1;
			else
				$old = 0;
			if($row3['status']=='24')
				$reg_incomp = 1;
			else
				$reg_incomp = 0;
		}

		$sql4 = "SELECT name FROM sugarcrm.campaigns WHERE id ='$campaign_id'";
                $res4 = mysql_query($sql4,$db) or logError($sql4,$db);
                if ($row4 = mysql_fetch_array($res4))
                        $campaign_username = $row4['name'];
	
		$sql5 = "SELECT edition_c FROM sugarcrm.campaigns_cstm WHERE id_c ='$campaign_id'";	
                $res5 = mysql_query($sql5,$db) or logError($sql5,$db);
                if ($row5 = mysql_fetch_array($res5))
                        $campaign_newspaper_date = $row5['edition_c'];

		$phoneNumArray = array();
		$phoneNumArray['PHONE1'] = phoneNumberCheck($enq_mobile);
		$phoneNumArray['PHONE2'] = phoneNumberCheck($lead_mobile);
		$phoneNumArray['PHONE3'] = phoneNumberCheck($std_enquirer.$enq_landline);
		$phoneNumArray['PHONE4'] = phoneNumberCheck($std_lead.$lead_landline);
		$phoneNumArray = checkDNC($phoneNumArray);
		$isDNC = $phoneNumArray["STATUS"];

		if(!$isDNC)
		{
			if($phoneNumArray['PHONE1S']=='Y' || $phoneNumArray['PHONE1']=='')
				$enq_mobile = "";
			else
				$enq_mobile = $phoneNumArray['PHONE1'];
			if($phoneNumArray['PHONE2S']=='Y' || $phoneNumArray['PHONE2']=='')
				$lead_mobile = "";
			else
				$lead_mobile = $phoneNumArray['PHONE2'];
			if($phoneNumArray['PHONE3S']=='Y' || $phoneNumArray['PHONE3']=='')
				$enq_landline = "";
			else
				$enq_landline = $phoneNumArray['PHONE3'];
			if($phoneNumArray['PHONE4S']=='Y' || $phoneNumArray['PHONE4']=='')
				$lead_landline = "";
			else
				$lead_landline = $phoneNumArray['PHONE4'];
		}

		if($enq_mobile!='')
                        $enq_mobile = $phoneNumArray['PHONE1'];
                if($lead_mobile!='')
                        $lead_mobile = $phoneNumArray['PHONE2'];
		if($enq_landline!='')
			$enq_landline = $phoneNumArray['PHONE3'];
		if($lead_landline!='')
			$lead_landline = $phoneNumArray['PHONE4'];

		$phone_no1 = get_phone_no($enq_mobile,$lead_mobile,$enq_landline,$lead_landline,1);
		$phone_no2 = get_phone_no($enq_mobile,$lead_mobile,$enq_landline,$lead_landline,2);

		$password = '';
		// creating content to be written to the file
		if($phone_no1 && $phone_no2)
                {
			$line="\"$leadid\"".","."\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$mother_tongue\"".","."\"0$phone_no1\"".","."\"0$phone_no2\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_newspaper_date\"".","."\"$jsprofileid_c\"".","."\"$password\"\n";
		}
		elseif($phone_no1)
                {
			$line="\"$leadid\"".","."\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$mother_tongue\"".","."\"0$phone_no1\"".","."\"\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_newspaper_date\"".","."\"$jsprofileid_c\"".","."\"$password\"\n";
		}
		elseif($phone_no1 =='' && $phone_no2)
		{
			$line="\"$leadid\"".","."\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$mother_tongue\"".","."\"0$phone_no2\"".","."\"\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_newspaper_date\"".","."\"$jsprofileid_c\"".","."\"$password\"\n";
		}
		
		if($line!='')
		{
			$data = trim($line)."\n";
			$output = $data;
		}
		unset($data);
		// writing content to file
		if(in_array($mother_tongue_val,$mt_arr1))
		{
			if($isDNC)
			{
				if($reg_incomp)
				{
					if($old)
        	                                fwrite($fp8,$output);
	                                else
	                                        fwrite($fp2,$output);
				}
				elseif($email!='')
				{
					if($old)
	                                        fwrite($fp12,$output);
        	                        else
                	                        fwrite($fp6,$output);
				}
				else
				{
					if($old)
                                        	fwrite($fp10,$output);
                                	else
                                        	fwrite($fp4,$output);
				}
			}
			else
			{
				if($reg_incomp)
                                {
                                        if($old)
                                                fwrite($fp7,$output);
                                        else
                                                fwrite($fp1,$output);
                                }
                                elseif($email!='')
                                {
                                        if($old)
                                                fwrite($fp11,$output);
                                        else
                                                fwrite($fp5,$output);
                                }
                                else
                                {
                                        if($old)
                                                fwrite($fp9,$output);
                                        else
                                                fwrite($fp3,$output);
                                }       
			}
		}
		elseif(in_array($mother_tongue_val,$mt_arr2))
		{
			if($isDNC)
			{
				if($reg_incomp)
				{
					if($old)
        	                                fwrite($fp20,$output);
	                                else
	                                        fwrite($fp14,$output);
				}
				elseif($email!='')
				{
					if($old)
	                                        fwrite($fp24,$output);
        	                        else
                	                        fwrite($fp18,$output);
				}
				else
				{
					if($old)
                                        	fwrite($fp22,$output);
                                	else
                                        	fwrite($fp16,$output);
				}
			}
			else
			{
				if($reg_incomp)
                                {
                                        if($old)
                                                fwrite($fp19,$output);
                                        else
                                                fwrite($fp13,$output);
                                }
                                elseif($email!='')
                                {
                                        if($old)
                                                fwrite($fp23,$output);
                                        else
                                                fwrite($fp17,$output);
                                }
                                else
                                {
                                        if($old)
                                                fwrite($fp21,$output);
                                        else
                                                fwrite($fp15,$output);
                                }       
			}
		}
        }
	
	function get_phone_no($m1,$m2,$l1,$l2,$suffix)
	{
		if($m1)
			$p1 = $m1;
		elseif($m2)
			$p1 = $m2;
		elseif($l1)
			$p1 = $l1;
		else
			$p1 = $l2;

		if($suffix == 1)
                        return $p1;

		$p2='';
		if($p1 == $m1)
		{
			if($m2) 
				$p2 = $m2;
			elseif($l1) 
				$p2 = $l1;
			elseif($l2) 
				$p2 = $l2;
		}
		elseif($p1 == $m2)
		{
			if($l1)       
                                $p2 = $l1;
                        elseif($l2)       
                                $p2 = $l2;
		}
		elseif($p1 == $l1)
		{
			if($l2)
                                $p2 = $l2;
		}

		return $p2;
	}
	
	function checkDNC($phoneNumberArray)
        {
                global $db_dnc;
                mysql_ping($db_dnc);
                $DNCArr         =array();
                $DNC_NumberArr  =array();
                $selectedArr    =array();
                $status         =true;

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

	function phoneNumberCheck($phoneNumber)
        {
                $phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
                $phoneNumber    =ltrim($phoneNumber,0);
                if(!is_numeric($phoneNumber))
                        return "";
                if(strlen($phoneNumber)!=10)
                        return "";
                return $phoneNumber;
        }
?>
