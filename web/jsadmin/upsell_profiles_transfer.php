<?php
include_once("connect.inc");
$data=authenticated($cid);
if($data)
{
        if($Submit)
        {
		$alloted_dt=$year."-".$month."-".$day;
		$sql_main="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO = '$transfer_of' AND ALLOT_TIME>='$alloted_dt 00:00:00' AND ALLOT_TIME<='$alloted_dt 23:59:59'";
		$res_main=mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());
		while($row_main=mysql_fetch_array($res_main))
			$profileid_arr[]=$row_main['PROFILEID'];
		if(count($profileid_arr))
		{
			$count=0;
			for($i=0;$i<count($profileid_arr);$i++)
			{
				$task=allocate_due4renewal($profileid_arr[$i],$transfer_to,$alloted_dt);
				if($task)
					$count++;
			}
			if($count)
				$smarty->assign("msg","$count upsell profile(s) transferred.<br><a href=\"$SITE_URL/jsadmin/upsell_profiles_transfer.php?name=$user&cid=$cid\">Next</a>");
			else
				$smarty->assign("msg","The profile(s) alloted are not upsell profiles.<br><a href=\"$SITE_URL/jsadmin/upsell_profiles_transfer.php?name=$user&cid=$cid\">Next</a>");
		}
		else
			$smarty->assign("msg","No upsell profile alloted to $transfer_of on $alloted_dt.<br><a href=\"$SITE_URL/jsadmin/upsell_profiles_transfer.php?name=$user&cid=$cid\">Back</a>");
	}		
	$name=getname($cid);
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
	//$all_sql= "SELECT USER FROM jsadmin.UPSELL_AGENT";
	$all_sql= "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%ExcUpS%' AND ACTIVE='Y'";
        $all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
        while($all_row= mysql_fetch_array($all_res))
                $agent_arr[]= $all_row['USERNAME'];
        $smarty->assign("agent_arr",$agent_arr);
	for($i=0;$i<31;$i++)
		$day_arr[$i]=$i+1;
	$smarty->assign("day_arr",$day_arr);
	for($i=0;$i<12;$i++)
                $month_arr[$i]=$i+1;
        $smarty->assign("month_arr",$month_arr);
	$j=2009;
	for($i=0;$i<7;$i++)
	{
                $year_arr[$i]=$j;
		$j++;
	}
        $smarty->assign("year_arr",$year_arr);
        unset($Submit);
        $smarty->display("upsell_profiles_transfer.htm");
}
function allocate_due4renewal($profileid,$allot_to,$alloted_dt)
{
	$ok=1;
	/*
        $ts = JSstrToTime($alloted_dt);
        $check_dt = date("Y-m-d",$ts+10*86400);
        $sqlc = "SELECT EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileid' AND ACTIVE='Y'";
        $resc = mysql_query_decide($sqlc,$myDb) or die($sqlc.mysql_error($myDb));
        while($rowc = mysql_fetch_array($resc))
        {
                $exp_dt = $rowc['EXPIRY_DT'];
                if("$exp_dt"=="$check_dt")
                        $ok=1;
        }
	*/

        if($ok)
        {
		$sql="SELECT PHONE_RES, PHONE_MOB, EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$ph_res=$row['PHONE_RES'];
			$ph_mob=$row['PHONE_MOB'];
			$email=$row['EMAIL'];
		}

		$sql1="UPDATE incentive.MAIN_ADMIN SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql1) or die("$sql1".mysql_error_js());

		$sql2="UPDATE incentive.CRM_DAILY_ALLOT SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid' AND ALLOT_TIME>='$alloted_dt 00:00:00' AND ALLOT_TIME<='$alloted_dt 23:59:59'";
		mysql_query_decide($sql2) or die("$sql2".mysql_error_js());

		$sql4="UPDATE incentive.MANUAL_ALLOT SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid' AND ALLOT_TIME>='$alloted_dt 00:00:00' AND ALLOT_TIME<='$alloted_dt 23:59:59'";
		mysql_query_decide($sql4) or die("$sql4".mysql_error_js());
		return 1;
	}
	else
		return 0;
}
?>
