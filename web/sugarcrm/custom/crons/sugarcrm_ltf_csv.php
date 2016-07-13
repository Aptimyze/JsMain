<?php
	/*******************************************For testing comment live portion and uncomment the test portion*******************************************/
	chdir(dirname(__FILE__));
        ini_set("max_execution_time","0");
        $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;//live
        //$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;//test
	include($_SERVER['DOCUMENT_ROOT']."/sugarcrm/custom/include/language/en_us.lang.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

	$SITE_URL=JsConstants::$ser6Url;
	$dbSlave = connect_slave();
	$db_dnc = mysql_connect(MysqlDbConstants::$dnc[HOST].":".MysqlDbConstants::$dnc[PORT],MysqlDbConstants::$dnc[USER],MysqlDbConstants::$dnc[PASS]) or die("Unable to connect to dnc server");
	//Marathi+Konkani
	$mt_arr1 = array(20,34);

	//Tamil
	$mt_arr2 = array(31);

	//Telegu
	$mt_arr3 = array(3);

	//Malayalam
	$mt_arr4 = array(17);

	//Kannad
	$mt_arr5 = array(16);
	
	//Hindi and Other
	$mt_arr6 = array(10,19,33,27,7,28,13,14,15,30,12,9,2,18,6,25,5,4,21,22,23,24,29,32);

	//define header to write into csv file.
	$header="\"LEAD ID\"".","."\"LEAD NAME\"".","."\"AGE\"".","."\"GENDER\"".","."\"HEIGHT\"".","."\"MARITAL STATUS\"".","."\"RELIGION\"".","."\"MOTHER TONGUE\"".","."\"CASTE\"".","."\"EDUCATION\"".","."\"OCCUPATION\"".","."\"INCOME\"".","."\"MANGLIK\"".","."\"PHONE_NO1\"".","."\"PHONE_NO2\"".","."\"CAMPAIGN SOURCE\"".","."\"LEAD SOURCE\"".","."\"ENQUIRER NAME\"".","."\"EMAIL\"".","."\"CAMPAIGN USERNAME\"".","."\"CAMPAIGN DESCRIPTION\"".","."\"CAMPAIGN NEWSPAPER\"".","."\"CAMPAIGN NEWSPAPER DATE\"".","."\"CAMPAIGN EDITION\"".","."\"CAMPAIGN EMAILID\"".","."\"CAMPAIGN MOBILE\"".","."\"PRIORITY\"".","."\"USERNAME\"".","."\"PASSWORD\"".","."\"ENTRY_DATE\"\n";

        $filename1 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt1.csv";
        $filename2 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt2.csv";
	$filename3 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt3.csv";
	$filename4 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt4.csv";
        $filename5 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt5.csv";
        $filename6 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt6.csv";
	$filename1d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt1d.csv";
        $filename2d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt2d.csv";
        $filename3d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt3d.csv";
        $filename4d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt4d.csv";
        $filename5d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt5d.csv";
        $filename6d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt6d.csv";
	$filename7d = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt7d.csv";

        $fp1 = fopen($filename1,"w+");
        $fp2 = fopen($filename2,"w+");
	$fp3 = fopen($filename3,"w+");
	$fp4 = fopen($filename4,"w+");
        $fp5 = fopen($filename5,"w+");
        $fp6 = fopen($filename6,"w+");
	$fp1d = fopen($filename1d,"w+");
        $fp2d = fopen($filename2d,"w+");
        $fp3d = fopen($filename3d,"w+");
        $fp4d = fopen($filename4d,"w+");
        $fp5d = fopen($filename5d,"w+");
        $fp6d = fopen($filename6d,"w+");
	$fp7d = fopen($filename7d,"w+");

        if(!$fp1 || !$fp2 || !$fp3 || !$fp4 || !$fp5 || !$fp6 || !$fp1d || !$fp2d || !$fp3d || !$fp4d || !$fp5d || !$fp6d || !$fp7d)
        {
                die("no file pointer");
        }

        fwrite($fp1,$header);
        fwrite($fp2,$header);
	fwrite($fp3,$header);
	fwrite($fp4,$header);
        fwrite($fp5,$header);
        fwrite($fp6,$header);
	fwrite($fp1d,$header);
        fwrite($fp2d,$header);
        fwrite($fp3d,$header);
        fwrite($fp4d,$header);
        fwrite($fp5d,$header);
        fwrite($fp6d,$header);
	fwrite($fp7d,$header);	

	$quartile1 = '';
        $quartile2 = '';
        $quartile3 = '';
        $quartile4 = '';

	$sqlq="SELECT max(score_c) as max,min(score_c) as min FROM sugarcrm.leads_cstm";
	$resq=mysql_query($sqlq,$dbSlave) or die(mysql_error());
        while($rowq = mysql_fetch_array($resq))
        {
                $diff_limit = ($rowq['max']-$rowq['min'])/4;
		$quartile1 = $rowq['max']-$diff_limit;
		$quartile2 = $quartile1-$diff_limit;
		$quartile3 = $quartile2-$diff_limit;
		$quartile4 = $quartile3-$diff_limit;
        }

        $dt_1day	=date("Y-m-d");
        $todayStartTime =$dt_1day." 00:00:00";
        $todayEndTime   =$dt_1day." 23:59:59";
        $todayTime1     =JSstrToTime($todayStartTime);
        $todayTime2     =JSstrToTime($todayEndTime);

	// Mobile leads registered in sugarcrm
        $sqlM="SELECT l.date_entered,l.id,l.phone_home,l.phone_mobile,c.enquirer_mobile_no_c,c.enquirer_landline_c FROM sugarcrm.leads as l JOIN sugarcrm.leads_cstm as c ON l.id=c.id_c WHERE l.assigned_user_id='' AND l.date_entered<='$todayEndTime' AND l.deleted=0 and l.status IN ('13','24') and c.source_c='12'";
        $resM=mysql_query($sqlM,$dbSlave) or die(mysql_error());
        while($rowM = mysql_fetch_array($resM))
        {
                $date_entered =JSstrToTime($rowM['date_entered']);
                $mobileLead=0;
                if(($date_entered>$todayTime1) && ($date_entered<$todayTime2))
                        $mobileLead=1;

                if($rowM['phone_home'] || $rowM['phone_mobile'] || $rowM['enquirer_mobile_no_c'] || $rowM['enquirer_landline_c'])
                        write_contents_to_file_ltf($rowM['id'],$mobileLead);
        }
	// ends

	// Other leads registered in sugarcrm
	$dt_6day=date("Y-m-d",time()-6*86400);
        $sqlj="SELECT l.id,l.phone_home,l.phone_mobile,c.enquirer_mobile_no_c,c.enquirer_landline_c FROM sugarcrm.leads as l JOIN sugarcrm.leads_cstm as c ON l.id=c.id_c WHERE l.assigned_user_id='' AND l.date_entered<='$dt_6day 23:59:59' AND l.deleted=0 and l.status IN ('13','24') and c.source_c!='12'";
        $resj=mysql_query($sqlj,$dbSlave) or die(mysql_error());
        while($rowj = mysql_fetch_array($resj))
        {
                if($rowj['phone_home'] || $rowj['phone_mobile'] || $rowj['enquirer_mobile_no_c'] || $rowj['enquirer_landline_c'])
                        write_contents_to_file_ltf($rowj['id']);
        }
        fclose($fp1);
        fclose($fp2);
	fclose($fp3);
	fclose($fp4);
        fclose($fp5);
        fclose($fp6);
	fclose($fp1d);
        fclose($fp2d);
        fclose($fp3d);
        fclose($fp4d);
        fclose($fp5d);
        fclose($fp6d);
	fclose($fp7d);

	$leadid_file1 = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt1.csv";
	$leadid_file2 = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt2.csv";
	$leadid_file3 = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt3.csv";
	$leadid_file4 = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt4.csv";
        $leadid_file5 = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt5.csv";
        $leadid_file6 = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt6.csv";
	$leadid_file1d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt1d.csv";
        $leadid_file2d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt2d.csv";
        $leadid_file3d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt3d.csv";
        $leadid_file4d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt4d.csv";
        $leadid_file5d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt5d.csv";
        $leadid_file6d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt6d.csv";
	$leadid_file7d = $SITE_URL."/crm/csv_files/ltf_files/bulk_csv_crm_data_".date('Y-m-d')."_mt7d.csv";

	$msg.="\nFor Marathi+Konkani :".$leadid_file1;
	$msg.="\nFor Marathi+Konkani(DNC) :".$leadid_file1d;
	$msg.="\nFor Tamil :".$leadid_file2;
	$msg.="\nFor Tamil(DNC) :".$leadid_file2d;
	$msg.="\nFor Telegu :".$leadid_file3;
	$msg.="\nFor Telegu(DNC) :".$leadid_file3d;
	$msg.="\nFor Malayalam : ".$leadid_file4;
	$msg.="\nFor Malayalam(DNC) : ".$leadid_file4d;
        $msg.="\nFor Kannad : ".$leadid_file5;
	$msg.="\nFor Kannad(DNC) : ".$leadid_file5d;
        $msg.="\nFor Hindi and Other : ".$leadid_file6;
	$msg.="\nFor Hindi and Other(DNC) : ".$leadid_file6d;
	$msgMobileLead="\nMobile leads(DNC) : ".$leadid_file7d;

	$to="shubhda.sinha@jeevansathi.com,anjali.singh@jeevansathi.com,rohan.mathur@jeevansathi.com";
	$bcc="vibhor.garg@jeevansathi.com,aman.sharma@jeevansathi.com,manoj.rana@naukri.com";
	$sub="LTF CSVs";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	/*live*/
	mail($to,$sub,$msg,$from);

	// Mail for mobile registered leads
	$to ='shweta.gupta@jeevansathi.com,manoj.rana@naukri.com,rohan.mathur@jeevansathi.com';
	$sub="LTF CSVs for Mobile leads(DNC)";
	mail($to,$sub,$msgMobileLead,$from);
	/*live*/

	function write_contents_to_file_ltf($leadid,$mobileLead='')
        {
                global $fp1, $fp2, $fp3, $fp4, $fp5, $fp6, $fp1d, $fp2d, $fp3d, $fp4d, $fp5d, $fp6d, $fp7d, $mt_arr1, $mt_arr2, $mt_arr3, $mt_arr4, $mt_arr5, $mt_arr6, $db, $db_dnc, $app_list_strings, $quartile1, $quartile2, $quartile3, $quartile4;
		$phone_no1='';
		$phone_no2='';

		$sql1 = "SELECT age_c,date_birth_c,gender_c,height_c,marital_status_c,religion_c,mother_tongue_c,caste_c,education_c,occupation_c,income_c,manglik_c,source_c,enquirer_mobile_no_c,enquirer_landline_c,score_c,std_c,std_enquirer_c,jsprofileid_c FROM sugarcrm.leads_cstm WHERE id_c ='$leadid'";
                $res1 = mysql_query($sql1,$dbSlave) or logError($sql1,$dbSlave);
                if ($row1 = mysql_fetch_array($res1))
                {
			$age = $row1['age_c'];
			if(!$age)
			{
				if($row1['date_birth_c']!='0000-00-00')
	                        	$dob=$row1['date_birth_c'];
				if($dob)
                                	$age=getAge($dob);

			}
			$gender_val = $row1['gender_c'];
			$gender = $app_list_strings['gender_list'][$gender_val];
                        $marital_status_val = $row1['marital_status_c'];
			$marital_status = $app_list_strings['Mstatus'][$marital_status_val];
                        $religion_val = $row1['religion_c'];
			$religion = $app_list_strings['Religion'][$religion_val];
                        $mother_tongue_val = $row1['mother_tongue_c'];
			$mother_tongue = $app_list_strings['Mtongue'][$mother_tongue_val];
			$caste_val = $row1['caste_c'];
			$caste = $app_list_strings['Caste'][$caste_val];
                        $education_val = $row1['education_c'];
			$education = $app_list_strings['Education'][$education_val];
                        $occupation_val = $row1['occupation_c'];
			$occupation = $app_list_strings['Occupation'][$occupation_val];
                        $income_val = $row1['income_c'];
			$income = $app_list_strings['Income'][$income_val];
			$manglik_val = $row1['manglik_c'];
			$manglik = $app_list_strings['Manglik_list'][$manglik_val];
                        $campaign_source_val = $row1['source_c'];
			$campaign_source = $app_list_strings['source_dom'][$campaign_source_val];
			$height_val = $row1['height_c'];
			$enq_mobile = $row1['enquirer_mobile_no_c'];
			$enq_landline = $row1['enquirer_landline_c'];
			$std_enquirer = $row1['std_enquirer_c'];
			$std_lead = $row1['std_c'];
			$score = $row1['score_c'];
			$username = $row1['jsprofileid_c'];
                        $sql11 = "SELECT LABEL from newjs.HEIGHT WHERE VALUE='$height_val'";
                        $res11 = mysql_query($sql11,$dbSlave) or logError($sql11,$dbSlave);
                        if($row11 = mysql_fetch_array($res11))
                                $height = $row11['LABEL'];
			/* Commented Code
			if($gender_val=='F')
                        {
				$minAge=$age;
				$maxAge=$age+5;
				$minHeight=$height_val;
				$maxHeight=$height_val+5;
				$table="newjs.SEARCH_MALE";
                        }
			elseif($gender_val=='M')
			{
				$minAge=$age-5;
				$maxAge=$age;
				$minHeight=$height_val-5;
				$maxHeight=$height_val;
				$table="newjs.SEARCH_FEMALE";
			}
			if($table)
			{
				$casteArr=explode("_",$caste_val);
				$caste_val1=$casteArr[1];
				$sql12="SELECT COUNT(*) AS COUNT FROM $table WHERE AGE BETWEEN '$minAge' AND '$maxAge' AND HEIGHT BETWEEN '$minHeight' AND '$maxHeight' AND RELIGION IN ('$religion_val') AND CASTE IN ('$caste_val1') AND MTONGUE IN ('$mother_tongue_val')";
				$res12 = mysql_query($sql12,$db) or logError($sql12,$db);
				if($row12 = mysql_fetch_array($res12))
					$forward_matches = $row12['COUNT'];
			}
			*/

		}
	
		$sql2 = "SELECT email_address_id FROM sugarcrm.email_addr_bean_rel where bean_id='$leadid'";
                $res2 = mysql_query($sql2,$dbSlave) or logError($sql2,$dbSlave);
                if ($row2 = mysql_fetch_array($res2))
                {
                        $email_address_id = $row2['email_address_id'];
			$sql21 = "SELECT email_address FROM sugarcrm.email_addresses where id='$email_address_id'";
                	$res21 = mysql_query($sql21,$dbSlave) or logError($sql21,$dbSlave);
	                if ($row21 = mysql_fetch_array($res21))
                	        $email = $row21['email_address'];
                }
	
		$sql3 = "SELECT assistant,campaign_id,last_name,lead_source,phone_home,phone_mobile,date_entered,status FROM sugarcrm.leads WHERE id ='$leadid'";
                $res3 = mysql_query($sql3,$dbSlave) or logError($sql3,$dbSlave);
                if ($row3 = mysql_fetch_array($res3))
		{
			$lead_mobile=$row3['phone_mobile'];
			$lead_landline = $row3['phone_home'];				
                        $enquirer_name = $row3['assistant'];
			$lead_name = $row3['last_name'];
			$ent_date = $row3['date_entered'];
			$campaign_id = $row3['campaign_id'];
			$lead_source_val = $row3['lead_source'];
			$lead_source = $app_list_strings['lead_source_list'][$lead_source_val];
			$status = $row3['status'];
		}

		$sql4 = "SELECT name,content FROM sugarcrm.campaigns WHERE id ='$campaign_id'";
                $res4 = mysql_query($sql4,$dbSlave) or logError($sql4,$dbSlave);
                if ($row4 = mysql_fetch_array($res4))
                {
                        $campaign_username = $row4['name'];
                        $campaign_description = trim($row4['content']);
                }
	
		$sql5 = "SELECT newspaper_c,edition_c,newspaper_edition_c,email_id_c,mobile_no_c FROM sugarcrm.campaigns_cstm WHERE id_c ='$campaign_id'";	
                $res5 = mysql_query($sql5,$dbSlave) or logError($sql5,$dbSlave);
                if ($row5 = mysql_fetch_array($res5))
                {
                        $campaign_newspaper_val = $row5['newspaper_c'];
			$campaign_newspaper = $app_list_strings['type_lead'][$campaign_newspaper_val];
                        $campaign_newspaper_date = $row5['edition_c'];
			$campaign_edition_val = $row5['newspaper_edition_c'];
			$campaign_edition = $app_list_strings['newspaper_edition_list'][$campaign_edition_val];
			$campaign_emailid = $row5['email_id_c'];
			$campaign_mobile = $row5['mobile_no_c'];
                }
		
		$password = '';
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

		if($score>=$quartile1)
			$quartile=1;
		elseif($score>=$quartile2)
			$quartile=2;
		elseif($score>=$quartile3)
			$quartile=3;
		else
			$quartile=4;
		$priority = get_priority($status,$lead_source,$gender_val,$ent_date,$quartile);

		// creating content to be written to the file
		if($phone_no1 && $phone_no2)
                {
			$line="\"$leadid\"".","."\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$height\"".","."\"$marital_status\"".","."\"$religion\"".","."\"$mother_tongue\"".","."\"$caste\"".","."\"$education\"".","."\"$occupation\"".","."\"$income\"".","."\"$manglik\"".","."\"0$phone_no1\"".","."\"0$phone_no2\"".","."\"$campaign_source\"".","."\"$lead_source\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_description\"".","."\"$campaign_newspaper\"".","."\"$campaign_newspaper_date\"".","."\"$campaign_edition\"".","."\"$campaign_emailid\"".","."\"$campaign_mobile\"".","."\"$priority\"".","."\"$username\"".","."\"$password\"".","."\"$ent_date\"\n";
		}
		elseif($phone_no1)
                {
			$line="\"$leadid\"".","."\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$height\"".","."\"$marital_status\"".","."\"$religion\"".","."\"$mother_tongue\"".","."\"$caste\"".","."\"$education\"".","."\"$occupation\"".","."\"$income\"".","."\"$manglik\"".","."\"0$phone_no1\"".","."\"\"".","."\"$campaign_source\"".","."\"$lead_source\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_description\"".","."\"$campaign_newspaper\"".","."\"$campaign_newspaper_date\"".","."\"$campaign_edition\"".","."\"$campaign_emailid\"".","."\"$campaign_mobile\"".","."\"$priority\"".","."\"$username\"".","."\"$password\"".","."\"$ent_date\"\n";
		}
		elseif($phone_no1 =='' && $phone_no2)
		{
			$line="\"$leadid\"".","."\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$height\"".","."\"$marital_status\"".","."\"$religion\"".","."\"$mother_tongue\"".","."\"$caste\"".","."\"$education\"".","."\"$occupation\"".","."\"$income\"".","."\"$manglik\"".","."\"0$phone_no2\"".","."\"\"".","."\"$campaign_source\"".","."\"$lead_source\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_description\"".","."\"$campaign_newspaper\"".","."\"$campaign_newspaper_date\"".","."\"$campaign_edition\"".","."\"$campaign_emailid\"".","."\"$campaign_mobile\"".","."\"$priority\"".","."\"$username\"".","."\"$password\"".","."\"$ent_date\"\n";
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
				fwrite($fp1d,$output);
			else
				fwrite($fp1,$output);
		}
		elseif(in_array($mother_tongue_val,$mt_arr2))
		{
			if($isDNC)
                                fwrite($fp2d,$output);
                        else
				fwrite($fp2,$output);
		}
		elseif(in_array($mother_tongue_val,$mt_arr3))
		{
			if($isDNC)
                                fwrite($fp3d,$output);
                        else
				fwrite($fp3,$output);
		}
		elseif(in_array($mother_tongue_val,$mt_arr4))
		{
			if($isDNC)
                                fwrite($fp4d,$output);
                        else
				fwrite($fp4,$output);
		}
		elseif(in_array($mother_tongue_val,$mt_arr5))
		{
			if($isDNC)
                                fwrite($fp5d,$output);
                        else
				fwrite($fp5,$output);
		}
		elseif(in_array($mother_tongue_val,$mt_arr6) || $mother_tongue_val==0)
		{
			if($isDNC)
                                fwrite($fp6d,$output);
                        else
                                fwrite($fp6,$output);
		}
      		// DNC lead registered through mobile 
		if($mobileLead){
                        if($isDNC)
                                fwrite($fp7d,$output);
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
	
	function get_priority($status,$lead_source,$gender_val,$ent_date,$quartile)
	{
		if($status == 16)
		{
			if($gender_val=='F')
				return 28; 
			if($gender_val=='M')
				return 27;
		}
		if($lead_source == 'TV Show')
                {
                        if($gender_val=='F')
                                return 26;
                        if($gender_val=='M')
                                return 25;
                }
		$date_30=date('Y-m-d',time()-30*86400);
		$date_90=date('Y-m-d',time()-90*86400);
		if($gender_val=='F')
		{
			if($ent_date>=$date_30)
			{
				if($quartile==1)
					return 24;
				elseif($quartile==2)
					return 23;
				elseif($quartile==3)
					return 22;
				else
					return 6;
			}
			elseif($ent_date>=$date_90)
			{
				if($quartile==1)
					return 18;
                                elseif($quartile==2)
					return 17;
                                elseif($quartile==3)
					return 16;
				else
                                        return 5;
			}
			else
			{
				if($quartile==1)
					return 12;
                                elseif($quartile==2)
					return 11;
                                elseif($quartile==3)
					return 10;
				else
                                        return 4;
			}
		}
		else
		{
			if($ent_date>=$date_30)
                        {
                                if($quartile==1)
                                        return 21;
                                elseif($quartile==2)
                                        return 20;
                                elseif($quartile==3)
                                        return 19;
				else
                                        return 3;
                        }		
                        elseif($ent_date>=$date_90)
                        {
                                if($quartile==1)
					return 15;
                                elseif($quartile==2)
					return 14;
                                elseif($quartile==3)
					return 13;
				else
                                        return 2;
                        }
                        else
                        {
                                if($quartile==1)
					return 9;
                                elseif($quartile==2)
					return 8;
                                elseif($quartile==3)
					return 7;
				else
                                        return 1;
                        }

		}
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
