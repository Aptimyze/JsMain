<?php
	include("connect.inc");
	
	$db = connect_misdb();
	$db2 = connect_master();

	if(authenticated($cid))
	{
		if($submit)
		{
			$start_date = date("Y-m")."-01 00:00:00";
			$end_date = date("Y-m-d G:i:s");

			$curmonth = date("M");
			$curyear = date("Y");

			$smarty->assign("MIS_MONTH",$curmonth." ".$curyear);

			$table_name = "PERFORMANCE_TRACK_".strtoupper($curmonth)."_".$curyear;

			//check if the table for this month has been created or not.
			$table_exists = check_table_existance("MIS",$table_name);

			if($table_exists)
			{
				$uname_str = "'".@implode("','",$uname_arr)."'";
				//finding the number of days in the current month.
				$no_of_days_of_month = date("t");
				$today = date("d");

				//select the data from current month's table.
				$sql = "SELECT * FROM MIS.$table_name WHERE USERNAME IN ($uname_str)";
				$res = mysql_query_decide($sql,$db2) or die($sql.mysql_error_js($db2));
				while($row = mysql_fetch_array($res))
				{
					$count=0;
					$name_arr[] = $row['USERNAME'];

					$name = $row['USERNAME'];

					//finding the target achieved till date.
					$sql_ach = "SELECT SUM(IF(pd.TYPE='DOL',pd.AMOUNT*pd.DOL_CONV_RATE,pd.AMOUNT)) AS ACHIEVED FROM incentive.CRM_DAILY_ALLOT cda, billing.PAYMENT_DETAIL pd WHERE cda.PROFILEID=pd.PROFILEID AND pd.STATUS='DONE' AND cda.ALLOTED_TO='$name' AND pd.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
					$res_ach = mysql_query_decide($sql_ach,$db2) or die($sql_ach.mysql_error_js($db2));
					$row_ach = mysql_fetch_array($res_ach);
					$achieved = net_off_tax_calculation($row_ach['ACHIEVED'],$end_date);

					$target = net_off_tax_calculation($row['TARGET'],$end_date);

					$remaining_target = $target - $achieved;
					if($remaining_target < 0)
						$remaining_target = 0;

					//one is added to include current day.
					$remaining_working_days = $no_of_days_of_month - $today + 1;

					$leave_days = explode(",",$row['LEAVE_DATES']);

					for($i=$today;$i<=$no_of_days_of_month;$i++)
					{
						if(@in_array($i,$leave_days))
							$count++;
					}

					$remaining_working_days -= $count;

					//calculating average target for remaining days
					if($remaining_working_days > 0)
						$remaining_target_per_day = round($remaining_target/$remaining_working_days,2);

					if($remaining_target_per_day < 0)
						$remaining_target_per_day = 0;

					$user_arr[$name]['TARGET'] = $target;
					$user_arr[$name]['ACHIEVED'] = $achieved;
					$user_arr[$name]['REMAINING_TARGET'] = $remaining_target;
					for($i=$today;$i<=$no_of_days_of_month;$i++)
					{
						if(!@in_array($i,$leave_days))
							$user_arr[$name]['REMAINING_TARGET_DAY'][$i-1] = $remaining_target_per_day;
						else
							$user_arr[$name]['REMAINING_TARGET_DAY'][$i-1] = "<font color=\"red\">HD</font>";
					}
				}

				//date array
				for($i=1;$i<=$no_of_days_of_month;$i++)
					$ddarr[] = $i;

				$smarty->assign("ddarr",$ddarr);
				$smarty->assign("name_arr",$name_arr);
				$smarty->assign("user_arr",$user_arr);
			}
			else
				$smarty->assign("ERR_MSG",1);

			$smarty->assign("flag",1);
		}
		else
		{
			$sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE='Y' AND PRIVILAGE LIKE '%IUI%' OR '%IUO%'";
			$res = mysql_query_decide($sql,$db2) or die($sql.mysql_error_js($db2));
			while($row = mysql_fetch_array($res))
				$username[] = $row['USERNAME'];

			$smarty->assign("username",$username);
		}
		
		$smarty->assign("cid",$cid);
		$smarty->display("performance_track_mis.htm");
	}
	else
	{
		$smarty->assign("user",$username);
		$smarty->display("jsconnectError.tpl");
	}
?>
