<?php
/*************************************************************************************************************************
FILE NAME	: inbound_allot_mis.php
DESCRIPTION	: MIS for Inboound alloted users (agent wise, source wise and query type wise)
NAME		: Sriram Viswanathan
DATE		: 20th April 2007.
*************************************************************************************************************************/
include("connect.inc");
include("../crm/functions_inbound.php");
include("../billing/comfunc_sums.php");

$db = connect_misdb();
$db2 = connect_master();

//array for call source
$call_source_arr = populate_call_source();
$smarty->assign("call_source_arr",$call_source_arr);

//array for query type
$query_type_arr = populate_query_type();
$smarty->assign("query_type_arr",$query_type_arr);

if(authenticated($cid))
{
	//if submit button is clicked.
	if($submit)
	{
		$start_date = $Year."-".$Month."-01 00:00:00";
		$end_date = $Year."-".$Month."-31 23:59:59";

		//if grouping required on CALL_SOURCE
		if($group_by == "CS")
		{
			$group_by = "CALL_SOURCE";
			$display_msg = "Call Source";
			$group_by_arr = $call_source_arr;
		}
		//if grouping required on QUERY_TYPE
		elseif($group_by == "QT")
		{
			$group_by = "QUERY_TYPE";
			$display_msg = "Query Type";
			$group_by_arr = $query_type_arr;
		}

		$group_by_values = array();
		$agent_array = array();

		$sql = "SELECT ia.ALLOTED_TO, ia.$group_by, IF(pd.TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,pd.RECEIPTID FROM incentive.INBOUND_ALLOT ia, incentive.CRM_DAILY_ALLOT cda, billing.PAYMENT_DETAIL pd WHERE ia.PROFILEID=cda.PROFILEID AND ia.PROFILEID = pd.PROFILEID AND ia.ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND pd.ENTRY_DT <= DATE_ADD(ia.ALLOT_TIME, INTERVAL (cda.ALLOCATION_DAYS + cda.RELAX_DAYS) DAY) AND pd.STATUS = 'DONE'";

		$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
			if(!@in_array($row['RECEIPTID'], $receiptid_arr))
			{
				$receiptid_arr[] = $row['RECEIPTID'];

				//creating an array for alloted users.
				if(!in_array($row["ALLOTED_TO"],$agent_array))
				{
					$agent_array[] = $row["ALLOTED_TO"];
				}

				//creating an array for possible values of CALL SOURCE / QUERY TYPE.
				if(!in_array($row[$group_by],$group_by_values))
				{
					$group_by_values[] = $row[$group_by];
				}

				$agent = $row['ALLOTED_TO'];
				$source = $row[$group_by];
				$amount = $row['AMOUNT'];

				$j = array_search($agent,$agent_array);
				$k = array_search($source,$group_by_values);

				$count_arr[$j][$k] += 1;
				$revenue_arr[$j][$k] += $amount;
				$net_off_tax_arr[$j][$k] += net_off_tax_calculation($amount,$end_date);
				$total_count_arr[$k] += 1;
				$total_revenue_arr[$k] += $amount;
				$net_off_tax_total_arr[$k] += net_off_tax_calculation($amount,$end_date);
			}
		}

		for($i=0;$i<count($group_by_values);$i++)
		{
			for($j=0;$j<count($group_by_arr);$j++)
			{
				if($group_by_values[$i] == $group_by_arr[$j]['value'])
				{
					$group_by_names[] = $group_by_arr[$j]['name'];
				}
			}
		}

		$smarty->assign("SUBMITTED",1);
		$smarty->assign("display_msg",$display_msg);
		$smarty->assign("agent_array",$agent_array);
		$smarty->assign("group_by_names",$group_by_names);
		$smarty->assign("count_arr",$count_arr);
		$smarty->assign("revenue_arr",$revenue_arr);
		$smarty->assign("net_off_tax_arr",$net_off_tax_arr);
		$smarty->assign("total_count_arr",$total_count_arr);
		$smarty->assign("total_revenue_arr",$total_revenue_arr);
		$smarty->assign("net_off_tax_total_arr",$net_off_tax_total_arr);
		$smarty->assign("group_by_values",$group_by_values);
	}
	//when link from MIS mainpage is clicked.
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
		for($y=2004;$y<=$year;$y++)
		{
			$yyarr[] = $y;
		}
																     
		$smarty->assign("curmonth",date('m'));
		$smarty->assign("curyear",date('Y'));
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->display("inbound_allot_mis.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

?>
