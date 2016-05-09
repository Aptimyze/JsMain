<?php
include_once("connect.inc");
$data=authenticated($cid);
if($data)
{
        if($Submit)
        {
		$alloted_dt=$year."-".$month."-".$day;
		$sql_main="SELECT PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = '$transfer_of' AND HANDLED='N'";
		$res_main=mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());
		while($row_main=mysql_fetch_array($res_main))
			$profileid_arr[]=$row_main['PROFILEID'];
		if(count($profileid_arr))
		{
			for($i=0;$i<count($profileid_arr);$i++)
				transfer_profiles($profileid_arr[$i],$transfer_to);
			$smarty->assign("msg","Profiles transferred.<br><a href=\"$SITE_URL/jsadmin/transfer_fresh_profiles.php?name=$user&cid=$cid\">Next</a>");
		}
		else
			$smarty->assign("msg","There are zero alloted profiles to $transfer_of on $alloted_dt.<br><a href=\"$SITE_URL/jsadmin/transfer_fresh_profiles.php?name=$user&cid=$cid\">Back</a>");
	}		
	$name=getname($cid);
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
	$all_sql= "SELECT DISTINCT(ALLOTED_TO) AS USER FROM incentive.PROFILE_ALLOCATION_TECH";
        $all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
        while($all_row= mysql_fetch_array($all_res))
                $agent_arr[]= $all_row['USER'];
        $smarty->assign("agent_arr",$agent_arr);
        unset($Submit);
        $smarty->display("transfer_fresh_profiles.htm");
}
function transfer_profiles($profileid,$allot_to)
{
	$sql1="UPDATE incentive.PROFILE_ALLOCATION_TECH SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
}
?>
