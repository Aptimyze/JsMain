<?php
include("connect.inc");

$db = connect_misdb();
$db2 = connect_master();

$data = authenticated($cid);
                                                                                                                             
if($data)
{
	if($submit)
	{
		if($day_month_wise=="D")
		{
			$start_dt = $year_d."-".$month."-01";
			$end_dt = $year_d."-".$month."-31";

			$sql_call = "SELECT (HOT_COUNT+WARM_COUNT+COLD_COUNT+NOCONTACT_COUNT+NOTINTERESTED_COUNT) AS COUNT, DAYOFMONTH(FOLLOW_DT) AS DAY, ALLOTED_TO FROM MIS.CRM_DAILY_COUNT WHERE FOLLOW_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY ALLOTED_TO, DAY";
			$res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
			while($row_call = mysql_fetch_array($res_call))
			{
				if(!@in_array($row_call['ALLOTED_TO'],$operator_arr))
					$operator_arr[] = $row_call['ALLOTED_TO'];
				$j = array_search($row_call['ALLOTED_TO'],$operator_arr);
				$i = $row_call['DAY'] - 1;
				$calls[$j][$i] += $row_call['COUNT'];
			}

			$sql_phone_verified = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(VERIFIED_DT) AS DAY, VERIFIED_BY FROM incentive.PHONE_DAILY_VERIFICATION WHERE VERIFIED_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY VERIFIED_BY, DAY";
			$res_phone_verified = mysql_query_decide($sql_phone_verified) or die($sql_phone_verified.mysql_error_js());
			while($row_phone_verified = mysql_fetch_array($res_phone_verified))
			{
				if(!@in_array($row_phone_verified['VERIFIED_BY'],$operator_arr))
					$operator_arr[] = $row_phone_verified['VERIFIED_BY'];
				$j = array_search($row_phone_verified['VERIFIED_BY'],$operator_arr);
				$i = $row_phone_verified['DAY'] - 1;
				$ver_phone[$j][$i] = $row_phone_verified['COUNT'];
			}

			$sql_phone_invalid = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(INVALID_DT) AS DAY, INVALID_BY FROM incentive.INVALID_PHONE_COUNT WHERE INVALID_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY INVALID_BY, DAY";
			$res_phone_invalid = mysql_query_decide($sql_phone_invalid) or die($sql_phone_invalid.mysql_error_js());
			while($row_phone_invalid = mysql_fetch_array($res_phone_invalid))
			{
				if(!@in_array($row_phone_invalid['INVALID_BY'],$operator_arr))
					$operator_arr[] = $row_phone_invalid['INVALID_BY'];
				$j = array_search($row_phone_invalid['INVALID_BY'],$operator_arr);
				$i = $row_phone_invalid['DAY'] - 1 ;
				$inv_phone[$j][$i] = $row_phone_invalid['COUNT'];
			}

			for($i=1;$i<=31;$i++)
				$arr[] = $i;

			$smarty->assign("head_label","Detiails for $month / $year_d");
		}
		elseif($day_month_wise=="M")
		{
			$start_dt = $year_m."-01-01";
			$end_dt = $year_m."-12-31";

			$sql_call = "SELECT (HOT_COUNT+WARM_COUNT+COLD_COUNT+NOCONTACT_COUNT+NOTINTERESTED_COUNT) AS COUNT, MONTH(FOLLOW_DT) AS MONTH, ALLOTED_TO FROM MIS.CRM_DAILY_COUNT WHERE FOLLOW_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY ALLOTED_TO, MONTH";
			$res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
			while($row_call = mysql_fetch_array($res_call))
			{
				if(!@in_array($row_call['ALLOTED_TO'],$operator_arr))
					$operator_arr[] = $row_call['ALLOTED_TO'];
				$j = array_search($row_call['ALLOTED_TO'],$operator_arr);
				$i = $row_call['MONTH'] - 1;
				$calls[$j][$i] += $row_call['COUNT'];
			}

			$sql_phone_verified = "SELECT COUNT(*) AS COUNT, MONTH(VERIFIED_DT) AS MONTH, VERIFIED_BY FROM incentive.PHONE_DAILY_VERIFICATION WHERE VERIFIED_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY VERIFIED_BY, MONTH";
			$res_phone_verified = mysql_query_decide($sql_phone_verified) or die($sql_phone_verified.mysql_error_js());
			while($row_phone_verified = mysql_fetch_array($res_phone_verified))
			{
				if(!@in_array($row_phone_verified['VERIFIED_BY'],$operator_arr))
					$operator_arr[] = $row_phone_verified['VERIFIED_BY'];
				$j = array_search($row_phone_verified['VERIFIED_BY'],$operator_arr);
				$i = $row_phone_verified['MONTH'] - 1;
				$ver_phone[$j][$i] = $row_phone_verified['COUNT'];
			}

			$sql_phone_invalid = "SELECT COUNT(*) AS COUNT, MONTH(INVALID_DT) AS MONTH, INVALID_BY FROM incentive.INVALID_PHONE_COUNT WHERE INVALID_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY INVALID_BY, MONTH";
			$res_phone_invalid = mysql_query_decide($sql_phone_invalid) or die($sql_phone_invalid.mysql_error_js());
			while($row_phone_invalid = mysql_fetch_array($res_phone_invalid))
			{
				if(!@in_array($row_phone_invalid['INVALID_BY'],$operator_arr))
					$operator_arr[] = $row_phone_invalid['INVALID_BY'];
				$j = array_search($row_phone_invalid['INVALID_BY'],$operator_arr);
				$i = $row_phone_invalid['MONTH'] - 1 ;
				$inv_phone[$j][$i] = $row_phone_invalid['COUNT'];
			}

			for($i=1;$i<=12;$i++)
				$arr[] = $i;

			$smarty->assign("head_label","Detiails for Year $year_m");
		}


		$smarty->assign("top_label","Day");
		$smarty->assign("arr",$arr);
		$smarty->assign("RESULT",1);
		$smarty->assign("calls",$calls);
		$smarty->assign("ver_phone",$ver_phone);
		$smarty->assign("inv_phone",$inv_phone);
		$smarty->assign("operator_arr",$operator_arr);
	}
	else
	{
		$date = date("Y-m");
		list($curyear,$curmonth) = explode("-",$date);

		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;

		for($i=2007;$i<=$curyear+2;$i++)
			$yyarr[] = $i;

		$smarty->assign("top_label","Month");
		$smarty->assign("curyear",$curyear);
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->display("call_details_mis.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

?>
