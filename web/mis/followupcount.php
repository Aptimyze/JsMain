<?php
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$scorearr=array("600","550","500","450","400","350","300","250","200","150","100","50","0");

	if($year)
	{
		$sql="SELECT COUNT(*) as cnt, SCORE, c.ALLOTED_TO  FROM incentive.MAIN_ADMIN_POOL p , incentive.CRM_DAILY_ALLOT c WHERE p.PROFILEID = c.PROFILEID  AND c.ALLOTED_TO ='$alloted_to' AND c.ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' GROUP BY SCORE";

		$sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, a.ALLOTED_TO FROM incentive.MAIN_ADMIN_POOL m, incentive.CRM_DAILY_ALLOT a , billing.PAYMENT_DETAIL p WHERE m.PROFILEID = a.PROFILEID AND m.PROFILEID = p.PROFILEID AND p.STATUS IN ('DONE','ADJUST') AND a.ALLOTED_TO ='$alloted_to' AND a.ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' AND  p.ENTRY_DT >= a.ALLOT_TIME AND p.ENTRY_DT <=a.DE_ALLOCATION_DT GROUP BY m.SCORE";
	}
	$res = mysql_query_decide($sql) or die("Due to a temporary problem your information cannot be processed.Please try after a few minutes".mysql_error_js());
	
	while ($row = mysql_fetch_array($res))
	{
		$score=$row['SCORE'];
		$j=array_search($score,$scorearr);
		$count[$j]+=$row['cnt'];
		$total+=$row['cnt'];
	}
	$res1 = mysql_query_decide($sql1) or die("Due to a temporary problem your information cannot be processed.Please try after a few minutes".mysql_error_js());

	while ($row1 = mysql_fetch_array($res1))
        {
                $score=$row1['SCORE'];
                $j=array_search($score,$scorearr);

                $paid_count[$j]+=$row1['cnt'];
		$paid_total+=$row1['cnt'];
        }
	$smarty->assign("year",$year);
	$smarty->assign("month",$month);
	$smarty->assign("alloted_to",$alloted_to);
	$smarty->assign("scorearr",$scorearr);
	$smarty->assign("count",$count);
	$smarty->assign("paid_count",$paid_count);
	$smarty->assign("total",$total);
	$smarty->assign("paid_total",$paid_total);
	$smarty->assign("cid",$cid);
	$smarty->assign("head",$smarty->fetch("head.htm"));
	$smarty->assign("foot",$smarty->fetch("foot.htm"));

	$smarty->display('followupcount.htm');
}
else //user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsconnectError.tpl");
}

?>
