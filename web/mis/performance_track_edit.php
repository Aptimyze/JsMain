<?php
	include("connect.inc");
	
	$db = connect_misdb();
	$db2 = connect_master();

	if(authenticated($cid))
	{
		$user = getname($cid);
		$privilage = explode("+",getprivilage($cid));
		$center = getcenter_for_operator($user);
		if(in_array("IA",$privilage) && "NOIDA"==$center)
		{
			$curmonth = date("M");
			$curyear = date("Y");

			$yday = date("d",mktime(0,0,0,date("m"),date("d")-1,$curyear));

			$smarty->assign("MIS_MONTH",$yday." ".$curmonth." ".$curyear);

			$table_name = "PERFORMANCE_TRACK_".strtoupper($curmonth)."_".$curyear;

			//check if the table for this month has been created or not.
			$table_exists = check_table_existance("MIS",$table_name);

			if($submit && $table_exists && count($modify) > 0)
			{
				for($i=0;$i<count($names);$i++)
				{
					$username = $names[$i];
					if($modify[$username]=="Y")
					{
						$target_new = $target[$username];
						$leave_dates_new = $leave_dates[$username];
						$no_of_leaves = count(explode(",",$leave_dates_new));

						$sql_upd = "UPDATE MIS.$table_name SET TARGET='$target_new', LEAVES='$no_of_leaves', LEAVE_DATES='$leave_dates_new' WHERE USERNAME = '$username'";
						mysql_query_decide($sql_upd,$db2) or die($sql_upd.mysql_error_js($db2));
					}
				}
				$smarty->assign("SUCCESSFUL",1);
			}
			else
			{
				if($table_exists)
				{
					if(is_array($names) && count($modify) <= 0)
						$smarty->assign("NOT_SELECTED",1);

					//finding the number of days in the current month.
					$no_of_days_of_month = date("t");
					$today_date = date("d");

					//select the data from current month's table.
					$sql = "SELECT * FROM MIS.$table_name";
					$res = mysql_query_decide($sql,$db2) or die($sql.mysql_error_js($db2));
					while($row = mysql_fetch_array($res))
					{
						$name_arr[] = $row['USERNAME'];

						$name = $row['USERNAME'];

						$user_arr[$name]['LEAVES'] = $row['LEAVES'];
						$user_arr[$name]['LEAVE_DATES'] = $row['LEAVE_DATES'];
						$user_arr[$name]['TARGET'] = $row['TARGET'];
					}

					$smarty->assign("name_arr",$name_arr);
					$smarty->assign("user_arr",$user_arr);
				}
				else
				{
					$smarty->assign("ERR_MSG",1);
				}
			}
		}
		else
			$smarty->assign("UNAUTHORIZED",1);

		$smarty->assign("cid",$cid);
		$smarty->display("performance_track_edit.htm");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->display("jsconnectError.tpl");
	}
?>
