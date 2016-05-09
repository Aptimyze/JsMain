<?php
include("connect.inc");

if(authenticated($cid))
{
	$operator_name=getname($cid);

	$branch = getcenter_for_walkin($operator_name);
        if ($branch == 'NOIDA')
                $ncr = 'Y';

	$today=date("Y-m-d");
	list($yy,$mm,$dd)=explode("-",$today);

	$st_date=$yy."-".$mm."-".$dd." 00:00:00";
	$end_date=$yy."-".$mm."-".$dd." 23:59:59";

	unset($usernamearr);
	$sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOT_TIME BETWEEN '$st_date' AND '$end_date' AND ALLOTED_TO='$operator_name'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$pid = $row['PROFILEID'];
		$profilearr[]=$row['PROFILEID'];
		if ($ncr != 'Y')
		{
			$sql_name = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";
			$res_name = mysql_query_decide($sql_name) or die("$sql".mysql_error_js());
			$row_name = mysql_fetch_array($res_name);
			$usernamearr[$pid]=$row_name['USERNAME'];
		}
	}

	$smarty->assign("usernamearr",$usernamearr);
	$smarty->assign("TOTAL",count($profilearr));
	$smarty->assign("profilearr",$profilearr);
	$smarty->assign("ncr",$ncr);
	$smarty->assign("cid",$cid);

	$smarty->display("daily_handled_list.htm");
}
else
{
	$msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
