<?php
include("connect.inc");
include("../crm/functions_inbound.php");
include("../billing/comfunc_sums.php");

$db = connect_misdb();
$db2 = connect_master();
$i=0;

// Log tables of allotment
$sql = "(SELECT PROFILEID,ALLOT_TIME FROM incentive.INBOUND_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND ALLOTED_TO='$user' AND (CALL_SOURCE='$group' || QUERY_TYPE='$group') ORDER BY ALLOT_TIME DESC)";
$sql .=" UNION (SELECT PROFILEID,ALLOT_TIME FROM incentive.MANUAL_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND ALLOTED_TO='$user' AND CALL_SOURCE='$group' ORDER BY ALLOT_TIME DESC)";

$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
while($row = mysql_fetch_array($res))
{
	$profileid_arr[] = $row['PROFILEID'];
	$pid=$row["PROFILEID"];
	$allot_time=$row["ALLOT_TIME"];

	// paid profile check
	$sql1 = "SELECT a.PROFILEID,IF(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE,a.AMOUNT) AS AMOUNT FROM billing.PAYMENT_DETAIL a, incentive.CRM_DAILY_ALLOT b WHERE a.PROFILEID = b.PROFILEID AND a.STATUS = 'DONE' AND a.AMOUNT!=0 AND b.ALLOT_TIME <= a.ENTRY_DT AND b.DE_ALLOCATION_DT >= DATE(a.ENTRY_DT) AND b.PROFILEID = '$pid' AND b.ALLOTED_TO = '$user' AND b.ALLOT_TIME BETWEEN '$start_date' AND '$end_date'";
	$res1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
	if($row1 = mysql_fetch_array($res1))
	{
		$proid=$row1["PROFILEID"];
		$amount=$row1["AMOUNT"];
	}

	// free profile condition
	$j=0;
	if($proid=='')
	{
		$sql2 = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	        $res2 = mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
        	if($row2 = mysql_fetch_array($res2))
                	$username=$row2["USERNAME"];
		$user_arr[$i][$j]=$username;
		$j=$j+1;
		$user_arr[$i][$j]=$allot_time;
		$j=$j+1;
		$user_arr[$i][$j]='Unpaid';
		if($group=='FP')
		{
			$j=$j+1;
			$sql3 = "SELECT AMOUNT FROM billing.ORDERS WHERE PROFILEID='$pid' AND ENTRY_DT<='$allot_time' ORDER BY ID DESC LIMIT 1";
        	        $res3 = mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
                	if($row3 = mysql_fetch_array($res3))
                        	$amount_tried=$row3["AMOUNT"];
			$user_arr[$i][$j]=$amount_tried;
			$j=$j+1;
			$user_arr[$i][$j]=$amount;
		}
		elseif($group=='CONCL' || $group=='OFFORDER')
		{
			$j=$j+1;
                        $sql3 = "SELECT AMOUNT FROM incentive.PAYMENT_COLLECT WHERE PROFILEID='$pid' AND ENTRY_DT<='$allot_time' ORDER BY ID DESC LIMIT 1";
                        $res3 = mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
                        if($row3 = mysql_fetch_array($res3))
                                $amount_tried=$row3["AMOUNT"];
                        $user_arr[$i][$j]=$amount_tried;
                        $j=$j+1;
                        $user_arr[$i][$j]=$amount;
		}
	}
	else
	{
		$sql2 = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$proid'";
                $res2 = mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
                if($row2 = mysql_fetch_array($res2))
                        $username=$row2["USERNAME"];
		$user_arr[$i][$j]=$username;
                $j=$j+1;
                $user_arr[$i][$j]=$allot_time;
                $j=$j+1;
                $user_arr[$i][$j]='Paid';

		// Failed payment order
		if($group=='FP')
                {
			$j=$j+1;
			$sql3 = "SELECT AMOUNT FROM billing.ORDERS WHERE PROFILEID='$proid' AND ENTRY_DT<='$allot_time' ORDER BY ID DESC LIMIT 1";
                	$res3 = mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
	                if($row3 = mysql_fetch_array($res3))
        	                $amount_tried=$row3["AMOUNT"];
			$user_arr[$i][$j]=$amount_tried;
	                $j=$j+1;
			$user_arr[$i][$j]=$amount;
		}
		// Offline order generated 
		elseif($group=='CONCL' || $group=='OFFORDER')
		{       
                        $j=$j+1;
                        $sql3 = "SELECT AMOUNT FROM incentive.PAYMENT_COLLECT WHERE PROFILEID='$pid' AND ENTRY_DT<='$allot_time' ORDER BY ID DESC LIMIT 1";
                        $res3 = mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
                        if($row3 = mysql_fetch_array($res3))
                                $amount_tried=$row3["AMOUNT"];
                        $user_arr[$i][$j]=$amount_tried;
                        $j=$j+1;
                        $user_arr[$i][$j]=$amount;
                }
	}
	$i=$i+1;
	unset($proid);
	unset($amount);
}
$smarty->assign("group",$group);
$smarty->assign("user_arr",$user_arr);
$smarty->display("user_info.htm");
?>
