<?php
//die("This is temporarily down. Kindly Recheck shortly");
ini_set("max_execution_time","0");

//include_once ("../connect.inc");
include_once ("connect.inc");

$ts=time();
$ts-=30*24*60*60;
$last_day=date("Y-m-d",$ts);

if (authenticated($cid))
{
	//mysql_close($db);

	$db = connect_737();

	echo "Generating List ....... Please Wait";

	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"\n";

	$filename = "/var/www/html/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d').".txt";
	//$filename = "/usr/local/apache/sites/jeevansathi.com/htdocs/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d').".txt";
	$fp = fopen($filename,"w+");
	if(!$fp)
	{
		die("no file pointer");
	}

	fwrite($fp,$header);

	$ts=time();
	$ts-=15*24*60*60;
	$start_date = date("Y-m-d",$ts) ." 00:00:00";
	//$end_date = date("Y-m-d",$ts) ." 23:59:59";


	$j = 0;
	
	//$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH IN('UP25','')";
	$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY='' OR left(PRIORITY,4) = 'UP25'";
	$res_city =  mysql_query_decide($sql_city) or logError($sql_city);
	while($row_city = mysql_fetch_array($res_city))
	{
		$cityarr[]	= $row_city['VALUE'];
	}
	$citystr = implode("','",$cityarr);

	$sql_pid = "SELECT PROFILEID , SCORE FROM incentive.MAIN_ADMIN_POOL WHERE ALLOTMENT_AVAIL='Y'  AND CITY_RES IN ('$citystr') ORDER BY SCORE DESC LIMIT 15000";

	$res_pid = mysql_query_decide($sql_pid) or logError($sql_pid);
	$row_pid = mysql_fetch_array($res_pid);
	while($row_pid = mysql_fetch_array($res_pid))
	//while($j < 1000)
	{
		$pid = $row_pid['PROFILEID'];
		$score = $row_pid['SCORE'];

		unset($allow);
		$sql_history = "SELECT max(ENTRY_DT) as ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$pid'";
		$res_history = mysql_query_decide($sql_history) or die("$sql_history".mysql_error_js());
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

		if($allow)	
		{
			// query to find details of users who have registered one week back and whose profiles are active 
			$sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$pid' AND SUBSCRIPTION='' AND ENTRY_DT < '$start_date' AND DATE(LAST_LOGIN_DT)>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";

			$res = mysql_query_decide($sql) or logError($sql);//die("$sql".mysql_error_js());
			if ($row = mysql_fetch_array($res))
			{

				$PHONE_RES = $row['PHONE_RES'];
				$PHONE_MOB = $row['PHONE_MOB'];
				$PHOTO = $row['HAVEPHOTO'];
				$ENTRY_DT = $row['ENTRY_DT'];

				if($PHONE_RES || $PHONE_MOB)
				{
					// query to find count of contacts made and accepted
					$sql1 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE RECEIVER = '$pid' AND TYPE='A'";
					$res1 = mysql_query_decide($sql1) or logError($sql1);//die("$sql1".mysql_error_js());
					$row1 = mysql_fetch_array($res1);
					$ACCEPTANCE_RCVD = $row1['CNT'];

					// query to find count of contacts initiated by user and accepted
					$sql2 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TYPE='A'";
					$res2 = mysql_query_decide($sql2) or logError($sql2);//die("$sql2".mysql_error_js());
					$row2 = mysql_fetch_array($res2);
					$ACCEPTANCE_MADE = $row2['CNT'];
					
					$sql3="SELECT COUNT(*) AS CNT3 FROM newjs.CONTACTS WHERE RECEIVER = '$pid' AND TYPE='I'";
					$res3 = mysql_query_decide($sql3) or logError($sql3);//die("$sql3".mysql_error_js());
					$row3 = mysql_fetch_array($res3);
					$RECEIVE_CNT = $row3['CNT3'];

					$sql4 ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TYPE='I'";
					$res4 = mysql_query_decide($sql4) or logError($sql4);//die("$sql4".mysql_error_js()); 
					$row4 = mysql_fetch_array($res4);
					$INITIATE_CNT= $row4['CNT4'];

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
					$COUNTRY= $country_label[0];
					$city =  $row['CITY_RES'];

					if($country=='51')
					{
						
						$city_label=label_select('CITY_INDIA',$city);
						$CITY_RES=$city_label[0];
					}
					elseif($country=='128')
					{
						$city_label=label_select('CITY_USA',$city);
						$CITY_RES=$city_label[0];
					}
					else
						$CITY_RES='NA';

					$sql5 = "SELECT PARTNERID FROM newjs.JPARTNER WHERE PROFILEID = '$pid'";
					$res5 = mysql_query_decide($sql5) or logError($sql5);
					$row5 = mysql_fetch_array($res5);

					$partner_id = $row5["PARTNERID"];
					if ($partner_id)
					{
						$partner_tbl_arr = array('PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COMP','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_ELEVEL_NEW','PARTNER_FBACK','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RES_STATUS','PARTNER_SMOKE');
						for($i=0;$i<count($partner_tbl_arr);$i++)
						{
							$sql6 = "SELECT COUNT(*) AS CNT4 FROM newjs.$partner_tbl_arr[$i] WHERE PARTNERID = '$partner_id'";
							$res6 = mysql_query_decide($sql6) or logError($sql6);//die("$sql6".mysql_error_js());
							if ($row6 = mysql_fetch_array($res6))
							{
								if ($row6["CNT4"] > 0)
								{
									$DPP =  1;
									break;
								}
								else
									$DPP =  0;
							}
						}
					}
					else
						$HAVEPARTNER = 'N';

					if ($DPP ==  1) // member as filled in his/her desired partner profile
						$HAVEPARTNER = 'Y';
					else
						$HAVEPARTNER = 'N';

					$PROFILELENGTH = strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);

					$LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

				// cretaing content to be written to the file
					if ($PHONE_MOB && $PHONE_RES)
					{
						$line="\"$pid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
					}
					elseif ($PHONE_MOB && $PHONE_RES =='')
					{
						$line="\"$pid\"".","."\"$PHONE_MOB\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
					}
					elseif ($PHONE_MOB =='' && $PHONE_RES)
					{
						$line="\"$pid\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
					}

					$data = trim($line)."\n";
					$output = $data;
					//echo $data;
					unset($data);
					unset($DPP);
					// writing content to file
					fwrite($fp,$output);
					$j++;
				}
			}
		}
		if ($j == 1500)
		{
			fclose($fp);
			//exit(0);
			break;
		}
	}

	//fclose($fp);

	echo "<br>List Generated";

	$profileid_file = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d').".txt";
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
	$smarty->assign("filename",$profileid_file);
	$smarty->display("crm_data_csv_generator.htm");
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
