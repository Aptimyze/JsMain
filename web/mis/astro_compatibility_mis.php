<?php
include("connect.inc");

$db = connect_misdb();

if(authenticated($cid))
{
	if($outside == "Y")
	{
		$type="D";
		$submit = 1;
		$dyear = date('Y');
		$month = date('m');
	}
	if($submit)
	{
		//storing the service price(for astro compatibility) in an array;
		$sql_price = "SELECT SERVICEID,PRICE_RS_TAX FROM billing.SERVICES WHERE SERVICEID LIKE 'A%'";
		$res_price = mysql_query_decide($sql_price) or die($sql_price.mysql_error_js());
		while($row_price = mysql_fetch_array($res_price))
			$price_arr[$row_price['SERVICEID']] = $row_price['PRICE_RS_TAX'];

		if($type=="D")
		{
			$start_date = $dyear."-".$month."-01 00:00:00";
			$end_date = $dyear."-".$month."-31 23:59:59";

			//finding count and amount of astro compatibility sold day-wise and service duration wise.
			//$sql_amount = "SELECT COUNT(DISTINCT(p.BILLID)) AS COUNT, DAYOFMONTH(p.ENTRY_DT) AS VALUE, p.SERVICEID FROM billing.PURCHASES p, billing.PAYMENT_DETAIL pd WHERE p.BILLID = pd.BILLID AND p.ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND p.STATUS='DONE' AND ADDON_SERVICEID LIKE '%A%' GROUP BY VALUE, p.SERVICEID";
			$sql_amount = "SELECT DISTINCT(BILLID), DAYOFMONTH(ENTRY_DT) AS VALUE, SERVICEID,ADDON_SERVICEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND STATUS='DONE' AND (ADDON_SERVICEID LIKE '%A%' OR SERVICEID LIKE '%A%') ";
		}
		elseif($type=="M")
		{
			$start_date = $myear."-01-01 00:00:00";
			$end_date = $myear."-12-31 23:59:59";

			//finding count and amount of astro compatibility sold month-wise and service duration wise.
			//$sql_amount = "SELECT COUNT(DISTINCT(p.BILLID)) AS COUNT, MONTH(p.ENTRY_DT) AS VALUE, p.SERVICEID FROM billing.PURCHASES p, billing.PAYMENT_DETAIL pd WHERE p.BILLID = pd.BILLID AND p.ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND p.STATUS='DONE' AND ADDON_SERVICEID LIKE '%A%' AND p.SERVICEID NOT LIKE 'S%' GROUP BY VALUE, p.SERVICEID";
			$sql_amount = "SELECT DISTINCT(BILLID), MONTH(ENTRY_DT) AS VALUE, SERVICEID,ADDON_SERVICEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND STATUS='DONE' AND (ADDON_SERVICEID LIKE '%A%' OR SERVICEID LIKE '%A%') ";
		}

		$res_amount = mysql_query_decide($sql_amount) or die("$sql_amount".mysql_error_js());
		while($row_amount = mysql_fetch_array($res_amount))
		{
			$value = $row_amount['VALUE'] - 1;
			$serviceid = $row_amount['SERVICEID'];
			$addonid = $row_amount['ADDON_SERVICEID'];
			if(strstr($serviceid,'A'))
			{
				find_astro($serviceid,$value,$end_date);	
			}
			else
			{
				find_astro($addonid,$value,$end_date);	
			}
		}
		/*while($row_amount = mysql_fetch_array($res_amount))
		{
			$value = $row_amount['VALUE'] - 1;
			$count_service = $row_amount['COUNT'];
			$serviceid = $row_amount['SERVICEID'];
			$service_duration = substr($serviceid,1);
			
			if(strstr($serviceid,"12"))
			{
				$astro_count[0][$value] += $row_amount['COUNT'];
				$astro_count_total[0] += $row_amount['COUNT'];
				$astro_amount[0][$value] += ($count_service * $price_arr["A12"]);
				$astro_amount_total[0] += ($count_service * $price_arr["A12"]);
			}
			elseif(strstr($serviceid,"6"))
			{
				$astro_count[1][$value] += $row_amount['COUNT'];
				$astro_count_total[1] += $row_amount['COUNT'];
				$astro_amount[1][$value] += ($count_service * $price_arr["A6"]);
				$astro_amount_total[1] += ($count_service * $price_arr["A6"]);
			}
			elseif(strstr($serviceid,"5"))
			{
				$astro_count[2][$value] += $row_amount['COUNT'];
				$astro_count_total[2] += $row_amount['COUNT'];
				$astro_amount[2][$value] += ($count_service * $price_arr["A5"]);
				$astro_amount_total[2] += ($count_service * $price_arr["A5"]);
			}
			elseif(strstr($serviceid,"4"))
			{
				$astro_count[3][$value] += $row_amount['COUNT'];
				$astro_count_total[3] += $row_amount['COUNT'];
				$astro_amount[3][$value] += ($count_service * $price_arr["A4"]);
				$astro_amount_total[3] += ($count_service * $price_arr["A4"]);
			}
			elseif(strstr($serviceid,"3"))
			{
				$astro_count[4][$value] += $row_amount['COUNT'];
				$astro_count_total[4] += $row_amount['COUNT'];
				$astro_amount[4][$value] += ($count_service * $price_arr["A3"]);
				$astro_amount_total[4] += ($count_service * $price_arr["A3"]);
			}
			elseif(strstr($serviceid,"2"))
			{
				$astro_count[5][$value] += $row_amount['COUNT'];
				$astro_count_total[5] += $row_amount['COUNT'];
				$astro_amount[5][$value] += ($count_service * $price_arr["A2"]);
				$astro_amount_total[5] += ($count_service * $price_arr["A2"]);
			}

			$total_astro_count[$value] += $row_amount['COUNT'];
			$total_astro_count_overall += $row_amount['COUNT'];

			$total_astro_amount[$value] += ($count_service * $price_arr["A".$service_duration]);
			$total_astro_amount_net_off_tax[$value] = net_off_tax_calculation($total_astro_amount[$value]);

			$total_astro_amount_overall += ($count_service * $price_arr["A".$service_duration]);
			$total_astro_amount_overall_net_off_tax = net_off_tax_calculation($total_astro_amount_overall);

			$match_astro_share[$value] = round(($total_astro_amount_net_off_tax[$value]/2),2);
			$match_astro_share_overall = round(($total_astro_amount_overall_net_off_tax/2),2);
		}
		*/
		if($type == "M")
		{
			$arr = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
			$smarty->assign("year",$myear);
		}
		elseif($type == "D")
		{
			for($i=1;$i<=31;$i++)
				$arr[] = $i;

			$smarty->assign("year",$dyear);
		}

		$service_durations = array("12 Months", "6 Months", "5 Months", "4 Months", "3 Months", "2 Months");

		$smarty->assign("month",$month);

		$smarty->assign("service_durations",$service_durations);
		$smarty->assign("arr",$arr);
		$smarty->assign("astro_count",$astro_count);
		$smarty->assign("astro_amount",$astro_amount);
		$smarty->assign("astro_count_total",$astro_count_total);
		$smarty->assign("astro_amount_total",$astro_amount_total);
		$smarty->assign("total_astro_count",$total_astro_count);
		$smarty->assign("total_astro_count_overall",$total_astro_count_overall);
		$smarty->assign("total_astro_amount",$total_astro_amount);
		$smarty->assign("total_astro_amount_net_off_tax",$total_astro_amount_net_off_tax);
		$smarty->assign("total_astro_amount_overall",$total_astro_amount_overall);
		$smarty->assign("total_astro_amount_overall_net_off_tax",$total_astro_amount_overall_net_off_tax);
		$smarty->assign("match_astro_share",$match_astro_share);
		$smarty->assign("match_astro_share_overall",$match_astro_share_overall);
		$smarty->assign("RESULT",1);
		$smarty->assign("type",$type);
		$smarty->assign("total_revenue_astro",$total_revenue_astro);
	}
	else
	{
		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;
		for($i=2005;$i<=date("Y")+1;$i++)
			$yyarr[] = $i;

		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->display("astro_compatibility_mis.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

function find_astro($serviceid,$value,$date='')
{
	global $astro_count,$astro_count_total,$astro_amount,$astro_amount_total,$total_astro_amount,$total_astro_count,$total_astro_amount_net_off_tax,$total_astro_count_overall,$match_astro_share,$match_astro_share_overall,$total_astro_amount_overall_net_off_tax,$price_arr,$total_astro_amount_overall;
	if(strstr($serviceid,"A12"))
	{
		$astro_count[0][$value]++;
		$astro_count_total[0]++;
		$astro_amount[0][$value] += $price_arr["A12"];
		$astro_amount_total[0] +=$price_arr["A12"];
		$service_duration=12;
	}
	elseif(strstr($serviceid,"A6"))
	{
		$astro_count[1][$value] ++;
		$astro_count_total[1] ++;
		$astro_amount[1][$value] += $price_arr["A6"];
		$astro_amount_total[1] += $price_arr["A6"];
		$service_duration=6;
	}
	elseif(strstr($serviceid,"A5"))
	{
		$astro_count[2][$value] ++;
		$astro_count_total[2] ++;
		$astro_amount[2][$value] += $price_arr["A5"];
		$astro_amount_total[2] += $price_arr["A5"];
		$service_duration=5;
	}
	elseif(strstr($serviceid,"A4"))
	{
		$astro_count[3][$value] ++;
		$astro_count_total[3] ++;
		$astro_amount[3][$value] += $price_arr["A4"];
		$astro_amount_total[3] += $price_arr["A4"];
		$service_duration=4;
	}
	elseif(strstr($serviceid,"A3"))
	{
		$astro_count[4][$value] ++;
		$astro_count_total[4] ++;
		$astro_amount[4][$value] += $price_arr["A3"];
		$astro_amount_total[4] +=$price_arr["A3"];
		$service_duration=3;
	}
	elseif(strstr($serviceid,"A2"))	
	{
		$astro_count[5][$value] ++;
		$astro_count_total[5] ++;
		$astro_amount[5][$value] += $price_arr["A2"];
		$astro_amount_total[5] +=$price_arr["A2"];
		$service_duration=2;
	}
	$total_astro_count[$value] ++;
	$total_astro_count_overall++;

	$total_astro_amount[$value] += $price_arr['A'.$service_duration];
	$total_astro_amount_net_off_tax[$value] = net_off_tax_calculation($total_astro_amount[$value],$date);

	$total_astro_amount_overall += $price_arr["A".$service_duration];
	$total_astro_amount_overall_net_off_tax = net_off_tax_calculation($total_astro_amount_overall,$date);

	$match_astro_share[$value] = round(($total_astro_amount_net_off_tax[$value]/2),2);
	$match_astro_share_overall = round(($total_astro_amount_overall_net_off_tax/2),2);
}
?>
