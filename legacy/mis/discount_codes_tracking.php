<?php
include_once("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($submit)
	{
		if($daywise_monthwise == "D")
		{
			$start_dt = $year."-".$month."-01 00:00:00";
			$end_dt = $year."-".$month."-31 23:59:59";

			$sql_multiple = "SELECT COUNT(*) AS COUNT, dcu.CODE AS CODE, dcm.NAME AS NAME, DAYOFMONTH(dcu.USED_DT) AS GBY, dcu.BILLID AS BILLID FROM newjs.DISCOUNT_CODE_USED dcu, newjs.DISCOUNT_CODE_MULTIPLE dcm WHERE dcu.CODE=dcm.CODE AND dcu.USED_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY GBY, BILLID";

			$sql_single = "SELECT COUNT(*) AS COUNT, NAME_OF_CODE AS NAME, BILLID, DAYOFMONTH(USED_DT) AS GBY FROM newjs.DISCOUNT_CODE WHERE PAYMENT_SUCCESSFUL='Y' AND USED_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY GBY, BILLID";

		}
		elseif($daywise_monthwise == "M")
		{
			$start_dt = $year."-01-01 00:00:00";
			$end_dt = $year."-12-31 23:59:59";

			$sql_multiple = "SELECT COUNT(*) AS COUNT, dcu.CODE AS CODE, dcm.NAME AS NAME, MONTH(dcu.USED_DT) AS GBY, dcu.BILLID AS BILLID FROM newjs.DISCOUNT_CODE_USED dcu, newjs.DISCOUNT_CODE_MULTIPLE dcm WHERE dcu.CODE=dcm.CODE AND dcu.USED_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY GBY, BILLID";

			$sql_single = "SELECT COUNT(*) AS COUNT, NAME_OF_CODE AS NAME, BILLID, MONTH(USED_DT) AS GBY FROM newjs.DISCOUNT_CODE WHERE PAYMENT_SUCCESSFUL='Y' AND USED_DT BETWEEN '$start_dt' AND '$end_dt' GROUP BY GBY, BILLID";
		}

		$res_multiple = mysql_query_decide($sql_multiple) or die($sql_multiple.mysql_error_js());
		while($row_multiple = mysql_fetch_array($res_multiple))
		{
			unset($offline_amount);
			unset($online_amount);
			$dd = $row_multiple['GBY'];
			$billid = $row_multiple['BILLID'];
			$code = $row_multiple['CODE'];
			$code_name = $row_multiple['NAME'];

			$sql_amt = "SELECT SUM(pd.AMOUNT) AS AMOUNT, p.ORDERID FROM billing.PAYMENT_DETAIL as pd, billing.PURCHASES as p WHERE pd.BILLID=p.BILLID AND pd.BILLID='$billid' GROUP BY ORDERID";
			$res_amt = mysql_query_decide($sql_amt) or die($sql_amt.mysql_error_js());
			$row_amt = mysql_fetch_array($res_amt);
			if($row_amt['ORDERID'] == 0)
				$offline_amount = $row_amt['AMOUNT'];
			else
				$online_amount = $row_amt['AMOUNT'];

			if(!@in_array($code,$codes_arr))
				$codes_arr[] = $code;

			$multiple_use[$dd][$code]["COUNT"]++;
			$multiple_use[$dd][$code]["CODE"] = $code;
			$multiple_use[$dd][$code]["NAME"] = $code_name;
			$multiple_use[$dd][$code]["OFFLINE_AMT"] += $offline_amount;
			$multiple_use[$dd][$code]["ONLINE_AMT"] += $online_amount;

			$multiple_col_total[$dd]['COUNT']++;
			$multiple_col_total[$dd]['OFFLINE_AMT'] += $offline_amount;
			$multiple_col_total[$dd]['ONLINE_AMT'] += $online_amount;

			$multiple_row_total[$code]['COUNT']++;
			$multiple_row_total[$code]['OFFLINE_AMT'] += $offline_amount;
			$multiple_row_total[$code]['ONLINE_AMT'] += $online_amount;
		}
		$smarty->assign("codes_arr",$codes_arr);
		$smarty->assign("multiple_use",$multiple_use);
		$smarty->assign("multiple_col_total",$multiple_col_total);
		$smarty->assign("multiple_row_total",$multiple_row_total);

		$res_single = mysql_query_decide($sql_single) or die($sql_single.mysql_error_js());
		while($row_single = mysql_fetch_array($res_single))
		{
			unset($offline_amount);
			unset($online_amount);
			$dd = $row_single['GBY'];
			$billid = $row_single['BILLID'];
			$code_name = $row_single['NAME'];

			$sql_amt = "SELECT SUM(pd.AMOUNT) AS AMOUNT, p.ORDERID FROM billing.PAYMENT_DETAIL as pd, billing.PURCHASES as p WHERE pd.BILLID=p.BILLID AND pd.BILLID='$billid' GROUP BY ORDERID";
			$res_amt = mysql_query_decide($sql_amt) or die($sql_amt.mysql_error_js());
			$row_amt = mysql_fetch_array($res_amt);
			if($row_amt['ORDERID'] == 0)
				$offline_amount = $row_amt['AMOUNT'];
			else
				$online_amount = $row_amt['AMOUNT'];

			if(!@in_array($code_name,$code_name_arr))
				$code_name_arr[] = $code_name;

			$single_use[$dd][$code_name]["COUNT"]++;
			$single_use[$dd][$code_name]["NAME"] = $code_name;
			$single_use[$dd][$code_name]["OFFLINE_AMT"] += $offline_amount;
			$single_use[$dd][$code_name]["ONLINE_AMT"] += $online_amount;

			$single_col_total[$dd]['COUNT']++;
			$single_col_total[$dd]['OFFLINE_AMT'] += $offline_amount;
			$single_col_total[$dd]['ONLINE_AMT'] += $online_amount;

			$single_row_total[$code]['COUNT']++;
			$single_row_total[$code]['OFFLINE_AMT'] += $offline_amount;
			$single_row_total[$code]['ONLINE_AMT'] += $online_amount;
		}
		$smarty->assign("code_name_arr",$code_name_arr);
		$smarty->assign("single_use",$single_use);
		$smarty->assign("single_use",$single_use);
		$smarty->assign("single_col_total",$single_col_total);
		$smarty->assign("single_row_total",$single_row_total);

		$smarty->assign("SUBMITTED",1);

		for($i=1;$i<=31;$i++)
			$ddarr[] = $i;

		$smarty->assign("ddarr",$ddarr);
	}
	else
	{
		$mmarr = array(
				array("NAME" => "Jan", "VALUE" => "01"),
				array("NAME" => "Feb", "VALUE" => "02"),
				array("NAME" => "Mar", "VALUE" => "03"),
				array("NAME" => "Apr", "VALUE" => "04"),
				array("NAME" => "May", "VALUE" => "05"),
				array("NAME" => "Jun", "VALUE" => "06"),
				array("NAME" => "Jul", "VALUE" => "07"),
				array("NAME" => "Aug", "VALUE" => "08"),
				array("NAME" => "Sep", "VALUE" => "09"),
				array("NAME" => "Oct", "VALUE" => "10"),
				array("NAME" => "Nov", "VALUE" => "11"),
				array("NAME" => "Dec", "VALUE" => "12"),
				);

		$year = date('Y');
		for($y=2007;$y<=$year;$y++)
		{
			$yyarr[] = $y;
		}

		$smarty->assign("curmonth",date('m'));
		$smarty->assign("curyear",date('Y'));
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	$smarty->display("discount_codes_tracking.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
