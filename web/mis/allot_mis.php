<?php
/*************************************************************************************************************************
FILE NAME	: allot_mis.php
DESCRIPTION	: MIS for Inbound and Manual alloted users (agent wise, source wise and query type wise)
NAME		: Vibhor Garg
DATE		: 10th Feb 2010.
*************************************************************************************************************************/
include("connect.inc");
include("../crm/functions_inbound.php");
include("../billing/comfunc_sums.php");

$db = connect_misdb();

//array for call source
$call_source_arr = populate_call_source();
$smarty->assign("call_source_arr",$call_source_arr);

//array for query type
$query_type_arr = populate_query_type();
$smarty->assign("query_type_arr",$query_type_arr);

		$misname=getname($cid);
                /* locking */
                $LockingService = new LockingService;
                $file = "allotedMis_".$misname;
                $lock = $LockingService->getFileLock($file,1);
                if(!$lock)
                	die("Multiple Refresh is not allowed, please wait..."); 
		else
			echo "<span style='color:red;font-size:20px;'>Multiple Refresh is not allowed.</span>";

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
                for($j=0;$j<count($group_by_arr);$j++){
                        $group_by_arr_values[] = $group_by_arr[$j]['value'];
                        $group_by_arr_names[$group_by_arr[$j]['value']] = $group_by_arr[$j]['name'];
		}

		$group_by_values = array();
		$agent_array = array();
		
		$sql = "SELECT ia.PROFILEID,ia.ALLOTED_TO, ia.$group_by, IF(pd.TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT FROM incentive.INBOUND_ALLOT ia, incentive.CRM_DAILY_ALLOT cda, billing.PAYMENT_DETAIL pd WHERE ia.PROFILEID=cda.PROFILEID AND ia.PROFILEID = pd.PROFILEID AND ia.ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND cda.ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND DATE(pd.ENTRY_DT) <= cda.DE_ALLOCATION_DT AND pd.ENTRY_DT>=cda.ALLOT_TIME AND pd.STATUS = 'DONE' AND pd.AMOUNT!=0 AND cda.ALLOTED_TO=ia.ALLOTED_TO";
		if($group_by == "CALL_SOURCE")
			$sql .= " UNION SELECT ma.PROFILEID,ma.ALLOTED_TO, ma.$group_by, IF(pd.TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT FROM incentive.MANUAL_ALLOT ma, incentive.CRM_DAILY_ALLOT cda, billing.PAYMENT_DETAIL pd WHERE ma.PROFILEID=cda.PROFILEID AND ma.PROFILEID = pd.PROFILEID AND ma.ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND cda.ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND DATE(pd.ENTRY_DT) <=cda.DE_ALLOCATION_DT AND pd.ENTRY_DT>=cda.ALLOT_TIME AND pd.STATUS = 'DONE' AND pd.AMOUNT!=0 AND cda.ALLOTED_TO=ma.ALLOTED_TO"; 
		$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
                        if(!in_array($row[$group_by],$group_by_arr_values))
                                continue;

			//if(!@in_array($row['PROFILEID'], $profileid_arr))
			//{
				//creating an array for alloted users.
				if(!in_array($row["ALLOTED_TO"],$agent_array))
				{
					$agent_array[] = $row["ALLOTED_TO"];
				}

				//creating an array for possible values of CALL SOURCE / QUERY TYPE.
				if(!in_array($row[$group_by],$group_by_values))
				{
					$group_by_values[] = $row[$group_by];
					$group_by_names[] = $group_by_arr_names[$row[$group_by]];
				}

				$agent = $row['ALLOTED_TO'];
				$source = $row[$group_by];
				$amount = $row['AMOUNT'];

				$j = array_search($agent,$agent_array);
				$k = array_search($source,$group_by_values);

                                $profileid =$row['PROFILEID'];
                                if(!@in_array($profileid,$profileid_arr[$j][$k])){
                                        $count_arr[$j][$k] += 1;
                                	$profileid_arr[$j][$k][] =$profileid;
				}	
				//$count_arr[$j][$k] += 1;
				//$cnt=0;

				/*$sql1 = "SELECT COUNT(PROFILEID) as CNT FROM incentive.INBOUND_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND ALLOTED_TO='$agent' AND (CALL_SOURCE='$source' || QUERY_TYPE='$source')";
				$res1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
				if($row1 = mysql_fetch_array($res1))
					$cnt=$row1["CNT"];

				if($group_by == "CALL_SOURCE")
				{
	                                $sql2 ="SELECT COUNT(PROFILEID) as CNT FROM incentive.MANUAL_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' AND ALLOTED_TO='$agent' AND CALL_SOURCE='$source'";
        	                        $res2 = mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
                	                if($row2 = mysql_fetch_array($res2))
                        	                $cnt=$cnt+$row2["CNT"];
				}*/

				//$allot_count_arr[$j][$k]=$cnt;
				$revenue_arr[$j][$k] += $amount;
				$net_off_tax_arr[$j][$k] += net_off_tax_calculation($amount,$end_date);
				$total_count_arr[$k] += 1;
				$total_revenue_arr[$k] += $amount;
				$net_off_tax_total_arr[$k] += net_off_tax_calculation($amount,$end_date);
			//}
		}
		if($group_by == "CALL_SOURCE"){
			$sql_1 ="SELECT COUNT(PROFILEID) as CNT,ALLOTED_TO,CALL_SOURCE FROM incentive.INBOUND_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' GROUP BY ALLOTED_TO,CALL_SOURCE";
                        $res_1 = mysql_query_decide($sql_1,$db) or die("$sql_1".mysql_error_js());
                        while($row_1 = mysql_fetch_array($res_1)){
                                $cnt_1    	=$row_1['CNT'];
                                $agent_1  	=$row_1['ALLOTED_TO'];
                                $source_1 	=$row_1['CALL_SOURCE'];

                                if(in_array($agent_1,$agent_array) && in_array($source_1,$group_by_values)){
                                        $j =array_search($agent_1,$agent_array);
                                        $k =array_search($source_1,$group_by_values);
					if($count_arr[$j][$k])
	                                        $allot_count_arr[$j][$k] +=$cnt_1;
                                }
                        }
			$sql_2 ="SELECT COUNT(PROFILEID) as CNT,ALLOTED_TO,CALL_SOURCE FROM incentive.MANUAL_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' GROUP BY ALLOTED_TO,CALL_SOURCE";
			$res_2 = mysql_query_decide($sql_2,$db) or die("$sql_2".mysql_error_js());
			while($row_2 = mysql_fetch_array($res_2)){
				$cnt_2 		=$row_2['CNT'];
				$agent_2	=$row_2['ALLOTED_TO'];
				$source_2 	=$row_2['CALL_SOURCE'];

				if(in_array($agent_2,$agent_array) && in_array($source_2,$group_by_values)){
                                        $j =array_search($agent_2,$agent_array);
                                        $k =array_search($source_2,$group_by_values);
					if($count_arr[$j][$k])
						$allot_count_arr[$j][$k] +=$cnt_2;
				}
			}
		}
		else if($group_by == "QUERY_TYPE"){
                        $sql_3 ="SELECT COUNT(PROFILEID) as CNT,ALLOTED_TO,QUERY_TYPE FROM incentive.INBOUND_ALLOT WHERE ALLOT_TIME BETWEEN '$start_date' AND '$end_date' GROUP BY ALLOTED_TO,QUERY_TYPE";
                        $res_3 = mysql_query_decide($sql_3,$db) or die("$sql_3".mysql_error_js());
                        while($row_3 = mysql_fetch_array($res_3)){
                                $cnt_3          =$row_3['CNT'];
                                $agent_3        =$row_3['ALLOTED_TO'];
                                $source_3       =$row_3['QUERY_TYPE'];

                                if(in_array($agent_3,$agent_array) && in_array($source_3,$group_by_values)){
                                        $j =array_search($agent_3,$agent_array);
                                        $k =array_search($source_3,$group_by_values);
                                        if($count_arr[$j][$k])
                                                $allot_count_arr[$j][$k] +=$cnt_3;
                                }
                        }
		}

		$smarty->assign("SUBMITTED",1);
		$smarty->assign("display_msg",$display_msg);
		$smarty->assign("agent_array",$agent_array);
		$smarty->assign("group_by_names",$group_by_names);
		$smarty->assign("count_arr",$count_arr);
		$smarty->assign("allot_count_arr",$allot_count_arr);
		$smarty->assign("revenue_arr",$revenue_arr);
		$smarty->assign("net_off_tax_arr",$net_off_tax_arr);
		$smarty->assign("total_count_arr",$total_count_arr);
		$smarty->assign("total_revenue_arr",$total_revenue_arr);
		$smarty->assign("net_off_tax_total_arr",$net_off_tax_total_arr);
		$smarty->assign("group_by_values",$group_by_values);
		$smarty->assign("start_date",$start_date);
		$smarty->assign("end_date",$end_date);
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
	$smarty->display("allot_mis.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

?>
