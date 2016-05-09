<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


	ini_set("max_execution_time","0");
	//include ("../connect.inc");
	include("test_allocate_functions.php");
	include("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");
	include("$_SERVER[DOCUMENT_ROOT]/profile/comfunc.inc");
	//include ("connect.inc");

	//define values to be used globally.
	$MAX_ALLOCATE_TOTAL = 75;
	$MAX_ALLOCATE_FAILED = 25;
	$MAX_ALLOCATE_NEW = 50;
	$FAILED_ALLOCATED = 0;

	$db = connect_db();

	$ts = time();
	$ts -= 30*24*60*60;
	$last_day = date("Y-m-d",$ts);

	//truncate profile allocation table.
	$sql="TRUNCATE TABLE incentive.TEST_PROFILE_ALLOCATION";
	mysql_query($sql) or die("$sql".mysql_error());

	$sql_branches = "SELECT DISTINCT(bc.PRIORITY) AS PRIORITY, b.NAME FROM incentive.BRANCHES b, incentive.BRANCH_CITY bc WHERE b.VALUE=PRIORITY AND PRIORITY != 'UP25'";
	$res_branches = mysql_query($sql_branches) or die("$sql_branches".mysql_error());
	while ($row_branches = mysql_fetch_array($res_branches))
	{
		//$branch = strtoupper($branch_arr[$regarr[$i]]['NAME'][$k]);
		$branch = strtoupper($row_branches['NAME']);
		$l=0;
		$m=0;
		$n=0;
		$allocate_new_profiles = 0;

		//query to find executives for each Branch
		$sql_center = "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)='$branch' AND ACTIVE='Y'";
		$res_center = mysql_query($sql_center) or die("$sql_center".mysql_error());
		while($row_center = mysql_fetch_array($res_center))
		{
			$userarr[$l]['NAME'] = $row_center['USERNAME'];
			$userarr[$l]['ALLOTED']	= 0;
			$l++;
		}

		$total_executives = count($userarr);
		$city_res = $row_branches['PRIORITY'];

		// query to find cities which are covered by a particular Branch
		if($city_res == 'KA02')
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE (PRIORITY='KA02' OR VALUE='TN02' OR VALUE='AP03')";
		elseif($city_res == 'MH04')
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY LIKE 'MH%' AND VALUE != 'MH08'";
		elseif($city_res == 'MH08')
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE = 'MH08'";
		else
			$sql_cities = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE PRIORITY = '$city_res'";
		$res_cities = mysql_query ($sql_cities) or die("$sql_cities".mysql_error());
		while($row_cities = mysql_fetch_array($res_cities))
			$cityarr[] = $row_cities['VALUE'];

		if(is_array($cityarr))
			$city_str = "'".implode ("','",$cityarr)."'";
		if($city_str)
		{
			if($city_res !='WB05' && $city_res !='PH00')
			{
				$MAX_ALLOCATE = $MAX_ALLOCATE_FAILED;
				//finding user's who tried for payment.
				$sql = "SELECT DISTINCT PROFILEID FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) UNION SELECT DISTINCT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND PAGE>1";
				$res = mysql_query($sql) or die("$sql".mysql_error());
				while($row = mysql_fetch_array($res))
				{
					unset($allocate);
					$profileid = $row['PROFILEID'];

					$sql_jp = "SELECT PHONE_RES,PHONE_MOB,CITY_RES from newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$profileid' AND SUBSCRIPTION='' AND CITY_RES IN ($city_str) AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";

					$res_jp = mysql_query($sql_jp) or die("$sql_jp".mysql_error());
					if($row_jp = mysql_fetch_array($res_jp))
					{
						if(check_profile($profileid))
						{
							$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
							$res_history = mysql_query($sql_history) or die("$sql_history".mysql_error());
							// profile has been handled once
							if($row_history = mysql_fetch_array($res_history))
							{
								$profile_type = 'O';
								if($row_history['ENTRY_DT']<=$last_day)
								{
									$allocate = 1;
								}
							}
							// new profile
							else
							{
								$profile_type = 'N';    
								$allocate = 1;
							}
							if($allocate && ($row_jp['PHONE_RES'] || $row_jp['PHONE_MOB']))
							{
								if($city_res == 'KA02')
                                                        	{
                                                                	if(($userarr[0]['ALLOTED'] < $MAX_ALLOCATE || $userarr[1]['ALLOTED'] < $MAX_ALLOCATE || $userarr[2]['ALLOTED'] < $MAX_ALLOCATE || $userarr[3]['ALLOTED'] < $MAX_ALLOCATE || $userarr[4]['ALLOTED'] < $MAX_ALLOCATE || $userarr[5]['ALLOTED'] < $MAX_ALLOCATE || $userarr[6]['ALLOTED'] < $MAX_ALLOCATE)&& !profile_allocated($profileid))
                                                                	{
                                                                        	/* banf is the variable which use same index
										   value as in the table PSWRDS for the 
										   executives of bangalore branch*/
										if($row_jp['CITY_RES']=='TN02')
                                                                        	{
                                                                                	if($userarr[3]['ALLOTED'] < $MAX_ALLOCATE)
                                                                                        	$banf=3;
                                                                                	elseif($userarr[6]['ALLOTED'] < $MAX_ALLOCATE)
                                                                                        	$banf=6;
                                                                        	}
                                                                        	elseif($row_jp['CITY_RES']=='AP03')
                                                                        	{
                                   	                                             if($userarr[5]['ALLOTED'] < $MAX_ALLOCATE)
                                        	                                                $banf=5;
                                                	                        }
                                                        	                elseif($row_jp['CITY_RES']=='KA02')
                                                                	        {
                                                                        	        if($userarr[0]['ALLOTED'] < $MAX_ALLOCATE && $userarr[0]['ALLOTED'] <= $userarr[1]['ALLOTED'] && $userarr[0]['ALLOTED'] <= $userarr[2]['ALLOTED'] && $userarr[0]['ALLOTED'] <= $userarr[4]['ALLOTED'])
                                                                                	        $banf=0;
                                    	                                            	elseif($userarr[1]['ALLOTED'] < $MAX_ALLOCATE && $userarr[1]['ALLOTED'] <= $userarr[2]['ALLOTED'] && $userarr[1]['ALLOTED'] <= $userarr[4]['ALLOTED'])
                                        	                                                $banf=1;
                                                	                                elseif($userarr[2]['ALLOTED'] < $MAX_ALLOCATE && $userarr[2]['ALLOTED'] <= $userarr[4]['ALLOTED'])
                                                        	                                $banf=2;
                                                                	                elseif($userarr[4]['ALLOTED'] < $MAX_ALLOCATE)
                                                                        	                $banf=4;
                                                                        	}
                                                                        	if(isset($banf))
										 {
                	                                                                $user_value = $userarr[$banf]['NAME'];
                        	                                                        //allocate the profile.
                                	                                                $sql_ins = "INSERT IGNORE INTO incentive.TEST_PROFILE_ALLOCATION (PROFILEID, ALLOTED_TO , ALLOT_DT,STATUS,PROFILE_TYPE) VALUES('$profileid','$user_value',now(),'N','$profile_type')";
                                        	                                        mysql_query($sql_ins) or die("$sql_ins".mysql_error);
                                                	                                $userarr[$banf]['ALLOTED']++;
                                                        	                }
                                                                	        unset($banf);
                                                                	}
                                                        	}
								elseif($userarr[$total_executives-1]['ALLOTED'] < $MAX_ALLOCATE && !profile_allocated($profileid))
								{
									$user_value = $userarr[$m]['NAME'];

									//allocate the profile.
									$sql_ins = "INSERT IGNORE INTO incentive.TEST_PROFILE_ALLOCATION (PROFILEID, ALLOTED_TO , ALLOT_DT,STATUS,PROFILE_TYPE) VALUES('$profileid','$user_value',now(),'N','$profile_type')";
									mysql_query($sql_ins) or die("$sql_ins".mysql_error);
									$userarr[$m]['ALLOTED']++;

									$m++;
									if($m == $total_executives)
										$m = 0;
								}
							}
						}
					}
				}
			}
			$MAX_ALLOCATE = $MAX_ALLOCATE_NEW + $MAX_ALLOCATE_FAILED;
			//finding user's who registered in last 15 days.
			//$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND TIMES_TRIED < 3 AND ENTRY_DT < DATE_SUB(CURDATE(),INTERVAL 15 DAY) ORDER BY SCORE DESC";
			//finding user's who registered in last 8 days.
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND TIMES_TRIED < 3 AND ENTRY_DT < DATE_SUB(CURDATE(),INTERVAL 8 DAY) ORDER BY SCORE DESC";
			$res = mysql_query($sql) or die("$sql".mysql_error());
			while($row = mysql_fetch_array($res))
			{
				unset($allocate);
				$profileid = $row['PROFILEID'];
				//$city_profile = $row['CITY_RES'];
				$sql_jp = "SELECT PHONE_RES,PHONE_MOB,CITY_RES from newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$profileid' AND SUBSCRIPTION='' AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 45 DAY)";
				$res_jp = mysql_query($sql_jp) or die("$sql_jp".mysql_error());
				if($row_jp = mysql_fetch_array($res_jp))
				{
					if(check_profile($profileid))
					{
						$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
						$res_history = mysql_query($sql_history) or die("$sql_history".mysql_error());
						// profile has been handled once
						if($row_history = mysql_fetch_array($res_history))
						{
							$profile_type = 'O';
							if($row_history['ENTRY_DT']<=$last_day)
							{
								$allocate = 1;
							}
						}
						// new profile
						else
						{
							$profile_type = 'N';    
							$allocate = 1;
						}
						if($allocate && ($row_jp['PHONE_RES'] || $row_jp['PHONE_MOB']))
						{
							if($city_res == 'KA02')
							{
								if(($userarr[0]['ALLOTED'] < $MAX_ALLOCATE || $userarr[1]['ALLOTED'] < $MAX_ALLOCATE || $userarr[2]['ALLOTED'] < $MAX_ALLOCATE || $userarr[3]['ALLOTED'] < $MAX_ALLOCATE || $userarr[4]['ALLOTED'] < $MAX_ALLOCATE || $userarr[5]['ALLOTED'] < $MAX_ALLOCATE || $userarr[6]['ALLOTED'] < $MAX_ALLOCATE)&& !profile_allocated($profileid))
	                                                        {
									/* ban is the variable which use same index
                                                                           value as in the table PSWRDS for the 
                                                                           executives of bangalore branch*/
									if($row_jp["CITY_RES"]=='TN02')
									{
										if($userarr[3]['ALLOTED'] < $MAX_ALLOCATE)
											$ban=3;
										elseif($userarr[6]['ALLOTED'] < $MAX_ALLOCATE)
											$ban=6;
									}
									elseif($row_jp["CITY_RES"]=='AP03')
									{
										if($userarr[5]['ALLOTED'] < $MAX_ALLOCATE)
											$ban=5;
									}
									elseif($row_jp["CITY_RES"]=='KA02')
									{
										if($userarr[0]['ALLOTED'] < $MAX_ALLOCATE && $userarr[0]['ALLOTED'] <= $userarr[1]['ALLOTED'] && $userarr[0]['ALLOTED'] <= $userarr[2]['ALLOTED'] && $userarr[0]['ALLOTED'] <= $userarr[4]['ALLOTED'])
											$ban=0;
										elseif($userarr[1]['ALLOTED'] < $MAX_ALLOCATE && $userarr[1]['ALLOTED'] <= $userarr[2]['ALLOTED'] && $userarr[1]['ALLOTED'] <= $userarr[4]['ALLOTED'])
											$ban=1;
										elseif($userarr[2]['ALLOTED'] < $MAX_ALLOCATE && $userarr[2]['ALLOTED'] <= $userarr[4]['ALLOTED'])
											$ban=2;
										elseif($userarr[4]['ALLOTED'] < $MAX_ALLOCATE)
											$ban=4;
                                                                        }
									if(isset($ban))
									{
										$user_value = $userarr[$ban]['NAME'];
										//allocate the profile.
										$sql_ins = "INSERT IGNORE INTO incentive.TEST_PROFILE_ALLOCATION (PROFILEID, ALLOTED_TO , ALLOT_DT,STATUS,PROFILE_TYPE) VALUES('$profileid','$user_value',now(),'N','$profile_type')";
                                                                		mysql_query($sql_ins) or die("$sql_ins".mysql_error);
                                                         			$userarr[$ban]['ALLOTED']++;    
									}
									unset($ban);
								}			                  	
							}
							elseif($userarr[$total_executives-1]['ALLOTED'] < $MAX_ALLOCATE && !profile_allocated($profileid))
							{
								$user_value = $userarr[$n]['NAME'];
								//allocate the profile.
								$sql_ins = "INSERT IGNORE INTO incentive.TEST_PROFILE_ALLOCATION (PROFILEID, ALLOTED_TO , ALLOT_DT,STATUS,PROFILE_TYPE) VALUES('$profileid','$user_value',now(),'N','$profile_type')";
								mysql_query($sql_ins) or die("$sql_ins".mysql_error);
								$userarr[$n]['ALLOTED']++;

								$n++;
								if($n == $total_executives)
									$n = 0;
							}
						}
					}
				}
				if($city_res == "KA02")
				{
					if(($userarr[0]['ALLOTED'] == $MAX_ALLOCATE && $userarr[1]['ALLOTED'] == $MAX_ALLOCATE && $userarr[2]['ALLOTED'] == $MAX_ALLOCATE && $userarr[3]['ALLOTED'] == $MAX_ALLOCATE && $userarr[4]['ALLOTED'] == $MAX_ALLOCATE && $userarr[5]['ALLOTED'] == $MAX_ALLOCATE && $userarr[6]['ALLOTED'] == $MAX_ALLOCATE))
						break;
				}
				else
				{
					if($userarr[$total_executives-1]['ALLOTED'] == $MAX_ALLOCATE)
						break;
				}
			}
		}
		unset($city_str);
		unset($cityarr);
		unset($userarr);
	}
?>
