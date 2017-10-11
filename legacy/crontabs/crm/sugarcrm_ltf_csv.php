<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	/*******************************************For testing comment live portion and uncomment the test portion*******************************************/
	ini_set("max_execution_time","0");
        /*live*/
        chdir("$docRoot/crontabs/crm");
        include ("../connect.inc");
        include($_SERVER['DOCUMENT_ROOT']."/sugarcrm/custom/include/language/en_us.lang.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        /*live*/

	$SITE_URL=JsConstants::$ser6Url;
	$db = connect_737();
	$db_master=connect_db();

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
	$header="\"LEAD NAME\"".","."\"AGE\"".","."\"GENDER\"".","."\"HEIGHT\"".","."\"MARITAL STATUS\"".","."\"RELIGION\"".","."\"MOTHER TONGUE\"".","."\"CASTE\"".","."\"EDUCATION\"".","."\"OCCUPATION\"".","."\"INCOME\"".","."\"MANGLIK\"".","."\"PHONE_NO1\"".","."\"PHONE_NO2\"".","."\"FORWARD MATCHES\"".","."\"CAMPAIGN SOURCE\"".","."\"LEAD SOURCE\"".","."\"ENQUIRER NAME\"".","."\"EMAIL\"".","."\"CAMPAIGN USERNAME\"".","."\"CAMPAIGN DESCRIPTION\"".","."\"CAMPAIGN NEWSPAPER\"".","."\"CAMPAIGN NEWSPAPER DATE\"".","."\"CAMPAIGN EDITION\"".","."\"CAMPAIGN EMAILID\"".","."\"CAMPAIGN MOBILE\"".","."\"PRIORITY\"\n";

        $filename1 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt1.txt";
        $filename2 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt2.txt";
	$filename3 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt3.txt";
	$filename4 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt4.txt";
        $filename5 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt5.txt";
        $filename6 = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt6.txt";

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

	$quartile1 = '';
        $quartile2 = '';
        $quartile3 = '';
        $quartile4 = '';

	$sqlq="SELECT max(score_c) as max,min(score_c) as min FROM sugarcrm.leads_cstm";
	$resq=mysql_query($sqlq,$db) or die(mysql_error());
        while($rowq = mysql_fetch_array($resq))
        {
                $diff_limit = ($rowq['max']-$rowq['min'])/4;
		$quartile1 = $rowq['max']-$diff_limit;
		$quartile2 = $quartile1-$diff_limit;
		$quartile3 = $quartile2-$diff_limit;
		$quartile4 = $quartile1-$quartile3;
        }

	$dt_7day=date("Y-m-d",time()-7*86400);
	$sqlj="SELECT id,phone_home,phone_mobile FROM sugarcrm.leads WHERE assigned_user_id='' AND date_entered<='$dt_7day'";
        $resj=mysql_query($sqlj,$db) or die(mysql_error());
        while($rowj = mysql_fetch_array($resj))
	{
		if($rowj['phone_home'] || $rowj['phone_mobile'])
	                $leadid_arr1[] = $rowj['id'];
		else
			$leadid_arr2[] = $rowj['id'];
	}

	$leadid_str1 = implode("','",$leadid_arr1);
	$leadid_str2 = implode("','",$leadid_arr2);
	
	$leadid_str = $leadid_str1."','".$leadid_str2;

	$sql_lid = "SELECT id_c,enquirer_mobile_no_c,enquirer_landline_c FROM sugarcrm.leads_cstm WHERE id_c IN ('$leadid_str') AND opt_in_c='1'";
        $res_lid = mysql_query($sql_lid,$db) or die(mysql_error());
        while($row_lid = mysql_fetch_array($res_lid))
        {
		unset($allow);
                $leadid = $row_lid['id_c'];
                if(in_array($leadid,$leadid_arr2))
                {
			if($row_lid['enquirer_mobile_no_c'] || $row_lid['enquirer_landline_c'])
				$allow=1;
			else
                                $allow=0;
                }
		else
			$allow=1;
		if($allow)
			write_contents_to_file_ltf($leadid);
        }
        fclose($fp1);
        fclose($fp2);
	fclose($fp3);
	fclose($fp4);
        fclose($fp5);
        fclose($fp6);

	$leadid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt1.txt";
	$leadid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt2.txt";
	$leadid_file3 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt3.txt";
	$leadid_file4 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt4.txt";
        $leadid_file5 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt5.txt";
        $leadid_file6 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mt6.txt";

	$msg.="\nFor : ".$leadid_file1;
	$msg.="\nFor : ".$leadid_file2;
	$msg.="\nFor : ".$leadid_file3;
	$msg.="\nFor : ".$leadid_file4;
        $msg.="\nFor : ".$leadid_file5;
        $msg.="\nFor : ".$leadid_file6;


	$to="vibhor.garg@jeevansathi.com";
	$bcc="vibhor.garg@jeevansathi.com";
	$sub="LTF CSVs";
	$from="From:vibhor.garg@jeevansathi.com";
	$from .= "\r\nBcc:$bcc";

	/*live*/
	mail($to,$sub,$msg,$from);
	/*live*/

	function write_contents_to_file_ltf($leadid)
        {
                global $fp1, $fp2, $fp3, $fp4, $fp5, $fp6, $mt_arr1, $mt_arr2, $mt_arr3, $mt_arr4, $mt_arr5, $mt_arr6, $db, $db_master, $app_list_strings, $quartile1, $quartile2, $quartile3, $quartile4;
		$phone_no1='';
		$phone_no2='';

		$sql1 = "SELECT age_c,gender_c,height_c,marital_status_c,religion_c,mother_tongue_c,caste_c,education_c,occupation_c,income_c,manglik_c,source_c,enquirer_mobile_no_c,enquirer_landline_c,score_c FROM sugarcrm.leads_cstm WHERE id_c ='$leadid'";
                $res1 = mysql_query($sql1,$db) or logError($sql1,$db);
                if ($row1 = mysql_fetch_array($res1))
                {
			$age = $row1['age_c'];
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
			$score = $row1['score_c'];
                        $sql11 = "SELECT LABEL from newjs.HEIGHT WHERE VALUE='$height_val'";
                        $res11 = mysql_query($sql11,$db) or logError($sql11,$db);
                        if($row11 = mysql_fetch_array($res11))
                                $height = $row11['LABEL'];
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

		}
	
		$sql2 = "SELECT email_address_id FROM sugarcrm.email_addr_bean_rel where bean_id='$leadid'";
                $res2 = mysql_query($sql2,$db) or logError($sql2,$db);
                if ($row2 = mysql_fetch_array($res2))
                {
                        $email_address_id = $row2['email_address_id'];
			$sql21 = "SELECT email_address FROM sugarcrm.email_addresses where id='$email_address_id'";
                	$res21 = mysql_query($sql21,$db) or logError($sql21,$db);
	                if ($row21 = mysql_fetch_array($res21))
                	        $email = $row21['email_address'];
                }
	
		$sql3 = "SELECT assistant,campaign_id,last_name,lead_source,phone_home,phone_mobile,date_entered,status FROM sugarcrm.leads WHERE id ='$leadid'";
                $res3 = mysql_query($sql3,$db) or logError($sql3,$db);
                if ($row3 = mysql_fetch_array($res3))
		{
			$lead_mobile=$row3['phone_mobile'];
			$lead_landline = $row3['phone_home'];				
                        $enquirer_name = $row3['assistant'];
			$lead_name = $row3['last_name'];
			$ent_date = $row3['date_entered'];
			$campaign_id = $row3['campaign_id'];
			$lead_source_val = $row3['lead_source'];
			$lead_source = $app_list_strings['lead_source_dom'][$lead_source_val];
			$status = $row3['status'];
		}

		$sql4 = "SELECT name,content FROM sugarcrm.campaigns WHERE id ='$campaign_id'";
                $res4 = mysql_query($sql4,$db) or logError($sql4,$db);
                if ($row4 = mysql_fetch_array($res4))
                {
                        $campaign_username = $row4['name'];
                        $campaign_description = trim($row4['content']);
                }
	
		$sql5 = "SELECT newspaper_c,edition_c,newspaper_edition_c,email_id_c,mobile_no_c FROM sugarcrm.campaigns_cstm WHERE id_c ='$campaign_id'";	
                $res5 = mysql_query($sql5,$db) or logError($sql5,$db);
                if ($row5 = mysql_fetch_array($res5))
                {
                        $campaign_newspaper = $row5['newspaper_c'];
                        $campaign_newspaper_date = $row5['edition_c'];
			$campaign_edition = $row5['newspaper_edition_c'];
                        $campaign_email_id = $row5['email_id_c'];
			$campaign_mobile = $row5['mobile_no_c'];
                }

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

		// cretaing content to be written to the file
		$line="\"$lead_name\"".","."\"$age\"".","."\"$gender\"".","."\"$height\"".","."\"$marital_status\"".","."\"$religion\"".","."\"$mother_tongue\"".","."\"$caste\"".","."\"$education\"".","."\"$occupation\"".","."\"$income\"".","."\"$manglik\"".","."\"$phone_no1\"".","."\"$phone_no2\"".","."\"$forward_matches\"".","."\"$campaign_source\"".","."\"$lead_source\"".","."\"$enquirer_name\"".","."\"$email\"".","."\"$campaign_username\"".","."\"$campaign_description\"".","."\"$campaign_newspaper\"".","."\"$campaign_newspaper_date\"".","."\"$campaign_edition\"".","."\"$campaign_email_id\"".","."\"$campaign_mobile\"".","."\"$priority\"\n";
		if($line!='')
		{
			$data = trim($line)."\n";
			$output = $data;
		}
		unset($data);
		// writing content to file
		if(in_array($mother_tongue_val,$mt_arr1))
			fwrite($fp1,$output);
		elseif(in_array($mother_tongue_val,$mt_arr2))
			fwrite($fp2,$output);
		elseif(in_array($mother_tongue_val,$mt_arr3))
			fwrite($fp3,$output);
		elseif(in_array($mother_tongue_val,$mt_arr4))
			fwrite($fp4,$output);
		elseif(in_array($mother_tongue_val,$mt_arr5))
			fwrite($fp5,$output);
		elseif(in_array($mother_tongue_val,$mt_arr6))
                                fwrite($fp6,$output);
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
                $DNCArr =array();
                $status =true;

                if(!is_array($phoneNumberArray) || count($phoneNumberArray)=='0')
                        return false;

                $phoneNumberStr =implode("','",$phoneNumberArray);
                $sql="SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN('$phoneNumberSt')";
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
                                $status =false;
                        }
                }
                $DNCArr['STATUS'] =$status;
                return $DNCArr;
        }
?>
