<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//die("This is temporarily down. Kindly Recheck shortly");
ini_set("max_execution_time","0");
include_once("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");
include_once("$_SERVER[DOCUMENT_ROOT]/profile/comfunc.inc");

$ts=time();
$ts-=30*24*60*60;
$last_day=date("Y-m-d",$ts);

if (1)//authenticated($cid))
{
	//mysql_close($db);

	//$db = connect_737();
	$db = connect_db();

	//echo "Generating List ....... Please Wait";

	$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"".","."\"SCORE\"".","."\"ENTRY_DATE\"\n";

	$filename1 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_n.txt";
	$filename2 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_s.txt";
	$filename3 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_roi.txt";
	//$filename4 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nw.txt";
	$filename5 = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mh.txt";
	//$filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d').".txt";
	//$filename = "/usr/local/apache/sites/jeevansathi.com/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d').".txt";
	//$filename1 = "/usr/local/apache/sites/jeevansathi.com/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_n.txt";
	//$filename2 = "/usr/local/apache/sites/jeevansathi.com/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_s.txt";
	$fp1 = fopen($filename1,"w+");
	$fp2 = fopen($filename2,"w+");
	$fp3 = fopen($filename3,"w+");
	//$fp4 = fopen($filename4,"w+");
	$fp5 = fopen($filename5,"w+");

	if(!$fp1 || !$fp2 || !$fp3 || !$fp5)
	{
		die("no file pointer");
	}

	fwrite($fp1,$header);
	fwrite($fp2,$header);
	fwrite($fp3,$header);
	//fwrite($fp4,$header);
	fwrite($fp5,$header);

	$ts=time();
	$ts-=15*24*60*60;
	$start_date = date("Y-m-d",$ts) ." 00:00:00";
	//$end_date = date("Y-m-d",$ts) ." 23:59:59";

	$j = 0;
	$s = 0;
	
	//$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH IN('UP25','')";
	$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY='' OR left(PRIORITY,4) = 'UP25'";
	$res_city =  mysql_query($sql_city) or logError($sql_city);
	while($row_city = mysql_fetch_array($res_city))
	{
		$cityarr[]	= $row_city['VALUE'];
	}
	$citystr = implode("','",$cityarr);

	$ncrarr = array('DE00','UP25','HA03','HA02','UP12');

	$sql="SELECT DISTINCT PROFILEID FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) UNION SELECT DISTINCT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND PAGE>1";
	$myres=mysql_query($sql) or logError($sql);
	if($myrow=mysql_fetch_array($myres))
	{
		do
		{
			$profileid=$myrow['PROFILEID'];
			if($profileid)
			{
				$sql="SELECT COUNT(*) as cnt FROM incentive.DO_NOT_CALL WHERE PROFILEID='$profileid' AND REMOVED='N'";
				$res_history = mysql_query($sql) or die("$sql".mysql_error());
				$row_history = mysql_fetch_array($res_history);

				$sql1="SELECT COUNT(*) AS CNT from incentive.INVALID_PHONE WHERE PROFILEID='$profileid'";
				$res1=mysql_query($sql1) or die("$sql1".mysql_error());
				$row_invalid=mysql_fetch_array($res1);

				$sql1="SELECT COUNT(*) AS CNT from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
				$res1=mysql_query($sql1) or die("$sql1".mysql_error());
				$row1=mysql_fetch_array($res1);

				if($row1['CNT']==0 && $row_history['cnt']==0 && $row_invalid['CNT']==0)
				{
					$sql_check="SELECT COUNT(*) as cnt FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
					$res_check=mysql_query($sql_check) or logError($sql_check);
					$row_check=mysql_fetch_array($res_check);
					if($row_check['cnt']==0 && $profileid)
					{
						$sql="SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , STD, PHONE_RES  , DTOFBIRTH  , PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO from newjs.JPROFILE where PROFILEID='$profileid' and COUNTRY_RES=51 and CITY_RES IN ('$citystr') AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND SUBSCRIPTION=''";
						$result=mysql_query($sql) or logError($sql);                
						if($row=mysql_fetch_array($result))
						{
							$PHONE_RES = $row['PHONE_RES'];
							if($PHONE_RES)
								$PHONE_RES=$row['STD'].$PHONE_RES;
							if(substr($PHONE_RES,0,1)==0)
								$PHONE_RES = substr($PHONE_RES,1);
							$PHONE_RES = substr($PHONE_RES,-10);
							if(strlen($PHONE_RES)<10)
								$PHONE_RES="";

							//added by sriarm on June 12 2007 to show alternate number (if any)
							$sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$profileid' ORDER BY ID DESC LIMIT 1";
							$res_alt = mysql_query($sql_alt) or die("$sql_alt".mysql_error());
							if($row_alt = mysql_fetch_array($res_alt))
								$PHONE_MOB = $row_alt['ALTERNATE_NUMBER'];
							else
								$PHONE_MOB = $row['PHONE_MOB'];
							//end of - added by sriarm on June 12 2007 to show alternate number (if any)
							if(substr($PHONE_MOB,0,1)==0)
								$PHONE_MOB = substr($PHONE_MOB,1);
							$PHONE_MOB = substr($PHONE_MOB,-10);
							if(strlen($PHONE_MOB)<10)
								$PHONE_MOB="";
							$PHOTO = $row['HAVEPHOTO'];
							$ENTRY_DT = $row['ENTRY_DT'];
							$city =  $row['CITY_RES'];
							$subcity=substr($city,0,2);

							if(trim($PHONE_RES) || trim($PHONE_MOB))
							{
								// query to find count of contacts made and accepted
								$sql1 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE RECEIVER = '$profileid' AND TYPE='A'";
								$res1 = mysql_query($sql1) or logError($sql1);//die("$sql1".mysql_error());
								$row1 = mysql_fetch_array($res1);
								$ACCEPTANCE_RCVD = $row1['CNT'];

								// query to find count of contacts initiated by user and accepted
								$sql2 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE SENDER = '$profileid' AND TYPE='A'";
								$res2 = mysql_query($sql2) or logError($sql2);//die("$sql2".mysql_error());
								$row2 = mysql_fetch_array($res2);
								$ACCEPTANCE_MADE = $row2['CNT'];
								
								$sql3="SELECT COUNT(*) AS CNT3 FROM newjs.CONTACTS WHERE RECEIVER = '$profileid' AND TYPE='I'";
								$res3 = mysql_query($sql3) or logError($sql3);//die("$sql3".mysql_error());
								$row3 = mysql_fetch_array($res3);
								$RECEIVE_CNT = $row3['CNT3'];

								$sql4 ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$profileid' AND TYPE='I'";
								$res4 = mysql_query($sql4) or logError($sql4);//die("$sql4".mysql_error()); 
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

								$sql5 = "SELECT PARTNERID FROM newjs.JPARTNER WHERE PROFILEID = '$profileid'";
								$res5 = mysql_query($sql5) or logError($sql5);
								$row5 = mysql_fetch_array($res5);

								$partner_id = $row5["PARTNERID"];
								if ($partner_id)
								{
									$partner_tbl_arr = array('PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COMP','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_ELEVEL_NEW','PARTNER_FBACK','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RES_STATUS','PARTNER_SMOKE');
									for($i=0;$i<count($partner_tbl_arr);$i++)
									{
										$sql6 = "SELECT COUNT(*) AS CNT4 FROM newjs.$partner_tbl_arr[$i] WHERE PARTNERID = '$partner_id'";
										$res6 = mysql_query($sql6) or logError($sql6);//die("$sql6".mysql_error());
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

								$score=0;

								$PROFILELENGTH = strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);

								$LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

							// cretaing content to be written to the file
								if ($PHONE_MOB && $PHONE_RES)
								{
									$line="\"$profileid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
								}
								elseif ($PHONE_MOB && $PHONE_RES =='')
								{
									$line="\"$profileid\"".","."\"$PHONE_MOB\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
								}
								elseif ($PHONE_MOB =='' && $PHONE_RES)
								{
									$line="\"$profileid\"".","."\"$PHONE_RES\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
								}

								$data = trim($line)."\n";
								$output = $data;
								//echo $data;
								unset($data);
								unset($DPP);
								// writing content to file

								if($subcity=="TN" || $subcity=="KE" || $subcity=="KA" ||$subcity=="AP")
								{
									if($s<1000)
										fwrite($fp2,$output);
									$s++;
								}
								elseif(in_array($city,$ncrarr))
								{
									if($j<2000)
										fwrite($fp1,$output);
									$j++;
								}
								else
								{
									if(0)//$subcity=="UP" || $subcity=="RA" || $subcity=="GU")
									{
										if($w<1000)
											fwrite($fp4,$output);
										$w++;
									}
									else
									{
										if($r<2500)
											fwrite($fp3,$output);
										$r++;
									}
								}
								$pidarr[]=$profileid;
							}
						}	
					}
				}
			}
		}while($myrow=mysql_fetch_array($myres));
	}

	$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'MH%' OR VALUE LIKE 'GU%'";
	$res=mysql_query($sql) or die("$sql".mysql_error());
	while($row=mysql_fetch_array($res))
	{
		$cityarr[]=$row['VALUE'];
	}

	$citystr = implode("','",$cityarr);

	$sql_pid = "SELECT PROFILEID , SCORE FROM incentive.MAIN_ADMIN_POOL WHERE ALLOTMENT_AVAIL='Y'  AND CITY_RES IN ('$citystr') AND TIMES_TRIED<3 ";
	if(count($pidarr))
	{
		$pidstr=implode(",",$pidarr);
		$sq_pid.=" AND PROFILEID NOT IN ($pidstr) ";
		unset($pidarr);
		unset($pidstr);
	}
	$sql_pid.=" ORDER BY SCORE DESC LIMIT 150000";

	$res_pid = mysql_query($sql_pid) or logError($sql_pid);
	$row_pid = mysql_fetch_array($res_pid);
	while($row_pid = mysql_fetch_array($res_pid))
	//while($j < 1000)
	{
		$pid = $row_pid['PROFILEID'];
		$score = $row_pid['SCORE'];

		unset($allow);
		$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$pid' ORDER BY ID DESC LIMIT 1";
		$res_history = mysql_query($sql_history) or die("$sql_history".mysql_error());
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

		$sql="SELECT COUNT(*) as cnt FROM incentive.DO_NOT_CALL WHERE PROFILEID='$pid' AND REMOVED='N'";
		$res_history = mysql_query($sql) or die("$sql".mysql_error());
		$row_history = mysql_fetch_array($res_history);
		if($row_history['cnt']>0)
			$allow=0;

		if($allow)	
		{
			// query to find details of users who have registered one week back and whose profiles are active 
			$sql = "SELECT ENTRY_DT, LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , STD, PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$pid' AND SUBSCRIPTION='' AND ENTRY_DT < '$start_date' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";

			$res = mysql_query($sql) or logError($sql);//die("$sql".mysql_error());
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

				//added by sriarm on June 12 2007 to show alternate number (if any)
				$sql_alt = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = '$pid' ORDER BY ID DESC LIMIT 1";
				$res_alt = mysql_query($sql_alt) or die("$sql_alt".mysql_error());
				if($row_alt = mysql_fetch_array($res_alt))
					$PHONE_MOB = $row_alt['ALTERNATE_NUMBER'];
				else
					$PHONE_MOB = $row['PHONE_MOB'];
				//end of - added by sriarm on June 12 2007 to show alternate number (if any)

				if(substr($PHONE_MOB,0,1)==0)
					$PHONE_MOB = substr($PHONE_MOB,1);
				$PHONE_MOB = substr($PHONE_MOB,-10);
				if(strlen($PHONE_MOB)<10)
					$PHONE_MOB="";
				$PHOTO = $row['HAVEPHOTO'];
				$ENTRY_DT = $row['ENTRY_DT'];

				$city =  $row['CITY_RES'];
				$subcity=substr($city,0,2);

				if($subcity=="MH" || $subcity=="GU")
				{
					$sql="SELECT COUNT(*) AS CNT FROM incentive.PROFILE_ALLOCATION WHERE PROFILEID='$pid'";
					$res_mh=mysql_query($sql) or die("$sql".mysql_error());
					$row_mh=mysql_fetch_array($res_mh);
					if($row_mh["CNT"]==0 && $score>=324)
						$mh_allow=1;
					else
						$mh_allow=0;
				}
				else
					$mh_allow=1;

				if((trim($PHONE_RES) || trim($PHONE_MOB)) && $mh_allow)
				{
					// query to find count of contacts made and accepted
					$sql1 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE RECEIVER = '$pid' AND TYPE='A'";
					$res1 = mysql_query($sql1) or logError($sql1);//die("$sql1".mysql_error());
					$row1 = mysql_fetch_array($res1);
					$ACCEPTANCE_RCVD = $row1['CNT'];

					// query to find count of contacts initiated by user and accepted
					$sql2 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TYPE='A'";
					$res2 = mysql_query($sql2) or logError($sql2);//die("$sql2".mysql_error());
					$row2 = mysql_fetch_array($res2);
					$ACCEPTANCE_MADE = $row2['CNT'];
					
					$sql3="SELECT COUNT(*) AS CNT3 FROM newjs.CONTACTS WHERE RECEIVER = '$pid' AND TYPE='I'";
					$res3 = mysql_query($sql3) or logError($sql3);//die("$sql3".mysql_error());
					$row3 = mysql_fetch_array($res3);
					$RECEIVE_CNT = $row3['CNT3'];

					$sql4 ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TYPE='I'";
					$res4 = mysql_query($sql4) or logError($sql4);//die("$sql4".mysql_error()); 
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
					$res5 = mysql_query($sql5) or logError($sql5);
					$row5 = mysql_fetch_array($res5);

					$partner_id = $row5["PARTNERID"];
					if ($partner_id)
					{
						$partner_tbl_arr = array('PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COMP','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_ELEVEL_NEW','PARTNER_FBACK','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RES_STATUS','PARTNER_SMOKE');
						for($i=0;$i<count($partner_tbl_arr);$i++)
						{
							$sql6 = "SELECT COUNT(*) AS CNT4 FROM newjs.$partner_tbl_arr[$i] WHERE PARTNERID = '$partner_id'";
							$res6 = mysql_query($sql6) or logError($sql6);//die("$sql6".mysql_error());
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
						$line="\"$pid\"".","."\"$PHONE_MOB\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
					}
					elseif ($PHONE_MOB =='' && $PHONE_RES)
					{
						$line="\"$pid\"".","."\"$PHONE_RES\"".",\"\","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"".","."\"$score\"".","."\"$ENTRY_DT\"";
					}

					$data = trim($line)."\n";
					$output = $data;
					//echo $data;
					unset($data);
					unset($DPP);
					// writing content to file

					$subcity=substr($city,0,2);

					if($subcity=="TN" || $subcity=="KE" || $subcity=="KA" ||$subcity=="AP")
					{
						if($s<1000)
							fwrite($fp2,$output);
						$s++;
					}
					elseif(in_array($city,$ncrarr))
					{
						if($j<2000)
							fwrite($fp1,$output);
						$j++;
					}
					else
					{
						if(0)//$subcity=="UP" || $subcity=="RA" || $subcity=="GU")
						{
							if($w<1000)
								fwrite($fp4,$output);
							$w++;
						}
						elseif($subcity=="MH" || $subcity=="GU")
						{
							//if($w<1000)
								fwrite($fp5,$output);
							//$w++;
						}
						else
						{
							if($r<2500)
								fwrite($fp3,$output);
							$r++;
						}
					}
				}
			}
		}
		if ($s == 1000)
		{
			$s++;
			fclose($fp2);
		}
		if ($j == 2000)
		{
			$j++;
			fclose($fp1);
		}
		if ($r == 2500)
		{
			$r++;
			fclose($fp3);
		}
		if (0)//$w == 1000)
		{
			$w++;
			fclose($fp4);
		}
		
		if($s>=1000 && $j>=2000 && $r>=2500)
			break;
	}

	fclose($fp5);

	//echo "<br>Lists Generated";

	$profileid_file1 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_n.txt";
	$profileid_file2 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_s.txt";
	$profileid_file3 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_roi.txt";
	//$profileid_file4 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_nw.txt";
	$profileid_file5 = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_mh.txt";

	$msg="For south : ".$profileid_file2;
	$msg.="\nFor NCR : ".$profileid_file1;
	$msg.="\nFor rest of india : ".$profileid_file3;
	//$msg.="\nFor Rajasthan,UP,Gujrat : ".$profileid_file4;
	$msg.="\nFor Maharastra : ".$profileid_file5;

	$to="anamika.singh@jeevansathi.com,surbhi.aggarwal@naukri.com,mandeep.choudhary@naukri.com,divya.jain@naukri.com,deepak.sharma@naukri.com,samrat.chadha@naukri.com";
	$bcc="shiv.narayan@jeevansathi.com";
	$sub="Daily CSV for calling";
	$from="From:shiv.narayan@jeevansathi.com";

	mail($to,$sub,$msg,$from);
	//send_email($to,$msg,$sub,$from,"",$bcc);
	//$smarty->assign("name",$name);
        //$smarty->assign("cid",$cid);
	//$smarty->assign("filename1",$profileid_file1);
	//$smarty->assign("filename2",$profileid_file2);
	//$smarty->display("crm_data_csv_generator.htm");
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
