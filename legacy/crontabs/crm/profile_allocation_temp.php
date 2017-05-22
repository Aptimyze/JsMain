<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include ("../connect.inc");
	//include ("connect.inc");
	$db	= connect_db();

	//$regarr 	= array('N','S','W');
	$regarr=array('W');
	$regcount 	= count($regarr);

	$ts=time();
	$ts-=30*24*60*60;
	$last_day=date("Y-m-d",$ts);

	$sql="TRUNCATE TABLE incentive.PROFILE_ALLOCATION";
	//mysql_query($sql) or die("$sql".mysql_error());

	for ($i = 0;$i < $regcount;$i++)
	{
		$c	= 0;

		// to find cities which fall in a particular region
		$sql = " SELECT NAME , VALUE  FROM incentive.BRANCHES WHERE REGION = '$regarr[$i]' AND VALUE='MH04'";
		$res = mysql_query ($sql) or die("$sql".mysql_error());
		while ($row = mysql_fetch_array($res))
		{
			$cityA[$regarr[$i]]['NAME'][$c] = $row['NAME'];
			$cityA[$regarr[$i]]['VALUE'][$c] = $row['VALUE'];
			$c++;
		}
		$cityA_count 	= count($cityA[$regarr[$i]]['NAME']);
		for ($j = 0;$j < $cityA_count; $j++)
		{
			$center		= strtoupper($cityA[$regarr[$i]]['NAME'][$j]);
			//if($center=="CHENNAI")
			//	$center="BANGALORE";

			$x		= 0;

			//query to find executives for each Branch
			$sql_center 	= "SELECT USERNAME from jsadmin.PSWRDS where USERNAME IN ('alka.baria','urvi.savla','harshada.chougule')";
			$res_center	= mysql_query ($sql_center) or die("$sql_center".mysql_error);
			while ($row_center = mysql_fetch_array($res_center))
			{
				$userarr[$x]['NAME'] 	= $row_center['USERNAME'];
				$userarr[$x]['ALLOTED']	= 0;
				$x++;
			}
			$cnt_user	= count($userarr);
			$city_res 	= $cityA[$regarr[$i]]['VALUE'][$j];

			// query to find cities which are covered by a particular Branch
			$sql_cities	= "SELECT VALUE FROM incentive.BRANCH_CITY WHERE LEFT(PRIORITY,4) = '$city_res'";
			$res_cities	= mysql_query ($sql_cities) or die("$sql_cities".mysql_error());
			while ($row_cities = mysql_fetch_array($res_cities))
			{
				$cityarr[] = $row_cities['VALUE'];
			}

			// temporary allotment of chennai profiles to bangalore
			/*if($city_res=='KA02')
			{
				$sql_cities	= "SELECT VALUE FROM incentive.BRANCH_CITY WHERE LEFT(PRIORITY,4) = 'TN02'";
				$res_cities	= mysql_query ($sql_cities) or die("$sql_cities".mysql_error());
				while ($row_cities = mysql_fetch_array($res_cities))
				{
					$cityarr[] = $row_cities['VALUE'];
				}
			}*/

			unset($city_str);
			if(is_array($cityarr))
				$city_str	= implode ("','",$cityarr);
			unset($cityarr);

			$limit		= 3000 * $cnt_user;
			$max_limit	= $limit/40;

			$u 		= 0;
			$rec_count      = 0;
			//print_r($userarr);


			$ts	      = time();
                       	$ts1          = $ts - 15*24*60*60;
                        $datelimit    = date("Y-m-d",$ts1);

			if($city_str)
			{
			// query to find profiles for allocation
			//$sql_pid = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ('$city_str') AND ALLOTMENT_AVAIL ='Y' AND TIMES_TRIED < 3 AND ENTRY_DT < '$datelimit' ORDER BY SCORE DESC LIMIT $limit";
			$sql_pid = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ('$city_str') AND ALLOTMENT_AVAIL ='Y' AND TIMES_TRIED < 3 AND ENTRY_DT < '$datelimit' ORDER BY SCORE DESC";
			$res_pid = mysql_query ($sql_pid) or die("$sql_pid".mysql_error());
			$cnt    = mysql_num_rows($res_pid);
			while ($row_pid = mysql_fetch_array($res_pid))
			{
				$pid		= $row_pid['PROFILEID'];

				$sql	= "SELECT PHONE_RES,PHONE_MOB from newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$pid' AND SUBSCRIPTION='' AND LAST_LOGIN_DT>DATE_SUB(CURDATE(), INTERVAL 45 DAY)";
				$result	= mysql_query($sql) or die("$sql".mysql_error());
				$row1 	= mysql_fetch_array($result);
				
				if ($rec_count < $max_limit)
				{
					unset($allow);
					$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$pid' ORDER BY ID DESC LIMIT 1";
					$res_history = mysql_query($sql_history) or die("$sql_history".mysql_error());
					if($row_history = mysql_fetch_array($res_history))
					{
						$profile_type = 'O';  // profile has been handled once
						if($row_history['ENTRY_DT']<=$last_day)
							$allow=1;
					}
					else
					{
						$profile_type = 'N';	// new profile
						$allow=1;
					}

					$sql="SELECT COUNT(*) as cnt FROM incentive.PROFILE_ALLOCATION WHERE PROFILEID='$pid'";
					$res_history = mysql_query($sql) or die("$sql".mysql_error());
					$row_history = mysql_fetch_array($res_history);
					if($row_history['cnt']>0)
						$allow=0;

					$sql="SELECT COUNT(*) as cnt FROM incentive.DO_NOT_CALL WHERE PROFILEID='$pid' AND REMOVED='N'";
					$res_history = mysql_query($sql) or die("$sql".mysql_error());
					$row_history = mysql_fetch_array($res_history);
					if($row_history['cnt']>0)
						$allow=0;

					if ($allow && ($row1['PHONE_RES'] || $row1['PHONE_MOB']))
					{
						$user_value	= $userarr[$u]['NAME'];
						$sql_exists	= "SELECT COUNT(*)  AS CNT FROM incentive.MAIN_ADMIN WHERE PROFILEID='$pid'";
						$res_exists 	= mysql_query($sql_exists) or logError($sql_exists);
						$row_exists	= mysql_fetch_array($res_exists);
						if ($row_exists['CNT'] == 0)
						{
							$sql_ins	= "INSERT IGNORE into incentive.PROFILE_ALLOCATION (PROFILEID, ALLOTED_TO , ALLOT_DT,STATUS,PROFILE_TYPE) values ('$pid','$user_value',now(),'N','$profile_type')";
							mysql_query($sql_ins) or logError($sql_ins);
							$userarr[$u]['ALLOTED']++;
							$rec_count++;

							$u = $u+1;
							if ($u	== $cnt_user)
								$u = 0;	
						}
					}
				
				}
				else
				{
					break;
				}
			}
			}
			unset ($userarr);
		}
		unset ($cityA);
		unset ($userarr);
	}

	mail("shiv.narayan@jeevansathi.com","CRM Allotment","allotment done");
?>
