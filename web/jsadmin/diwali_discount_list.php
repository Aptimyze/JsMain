<?php
include("time.php");
include_once("connect.inc");
include_once("../billing/comfunc_sums.php");
if(authenticated($cid))
{
	$serviceid_str = "'P4','C4','P5','C5','P6','C6','P12','C12'";

	$sql = "SELECT USERNAME, SERVICEID, ENTRY_FROM FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '2007-11-05' AND '2007-11-11' AND SERVICEID IN($serviceid_str)";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	$i=0;
	while($row = mysql_fetch_array($res))
	{
		$duration = substr($row['SERVICEID'],1);
		$service_name = get_service_name(substr($row['SERVICEID'],0,1));
		$details[$i]['USERNAME'] = $row['USERNAME'];
		$details[$i]['SERVICE'] = $service_name." ".$duration." Month(s)";

		if("4" == $duration || "5" == $duration)
			$details[$i]['DIWALI_DHAMAKA'] = $service_name." 1 Month";
		elseif("6" == $duration || "12" == $duration)
			$details[$i]['DIWALI_DHAMAKA'] = $service_name." 2 Months";

		$i++;
	}
	$smarty->assign("details",$details);
	$smarty->assign("cid",$cid);
	$smarty->display("diwali_discount_list.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

?>
