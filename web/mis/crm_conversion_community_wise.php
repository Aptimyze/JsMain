<?php
include("connect.inc");
include("../profile/pg/functions.php");
ini_set("memory_limit","16M");
ini_set("max_execution_time","0");
$db=connect_misdb();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
			for($i=0;$i<31;$i++)
                	{
                        	$ddarr[$i]=$i+1;
                	}
                	$smarty->assign("month",$month);
			$smarty->assign("year",$year);

			$dflag=1;
			if ($month < 9)
				$month = "0".$month;

			$sql_mtongue = "SELECT DISTINCT(SMALL_LABEL),VALUE FROM newjs.MTONGUE";
			$res_mtongue = mysql_query_decide($sql_mtongue) or die("$sql_mtongue".mysql_error_js());
			$i=0;
			while($row_mtongue = mysql_fetch_array($res_mtongue))
			{
				$mtongue_arr_lab[$i] = $row_mtongue['SMALL_LABEL'];
				$mtongue_arr_val[$i] = $row_mtongue['VALUE'];
				$i++;
			}
			$smarty->assign("mtongue_arr_lab",$mtongue_arr_lab);

			$sql="SELECT COUNT(*) as cnt,madp.MTONGUE,DAYOFMONTH(cda.ALLOT_TIME) as dd FROM incentive.CRM_DAILY_ALLOT cda, incentive.MAIN_ADMIN_POOL madp WHERE cda.PROFILEID=madp.PROFILEID AND cda.ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' GROUP BY madp.MTONGUE,dd";

			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$dd=$row['dd']-1;
				$mtongue = $row['MTONGUE'];
				$j = array_search($mtongue,$mtongue_arr_val);

				$calls_arr[$j][$dd]['callcnt'] = $row['cnt'];
				$total_calls_arr[$j]['callcnt'] += $row['cnt'];

				$total[$dd]['callcnt'] += $row['cnt'];
				$grandtotal['callcnt'] += $row['cnt'];
			}
			$bill_arr=array();
                        $pro_arr=array();
			$sql = "SELECT pd.BILLID,pd.RECEIPTID,pd.PROFILEID,if(pd.TYPE='DOL', pd.DOL_CONV_RATE*pd.AMOUNT,pd.AMOUNT) as amt, madp.MTONGUE , DAYOFMONTH(cda.ALLOT_TIME) as dd FROM incentive.CRM_DAILY_ALLOT AS cda, billing.PAYMENT_DETAIL pd, incentive.MAIN_ADMIN_POOL madp, billing.PURCHASES c WHERE pd.PROFILEID = cda.PROFILEID AND pd.PROFILEID=madp.PROFILEID AND c.BILLID=pd.BILLID AND cda.ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' AND pd.ENTRY_DT >= cda.ALLOT_TIME AND pd.ENTRY_DT <=cda.DE_ALLOCATION_DT AND pd.STATUS IN ('DONE', 'ADJUST') AND c.STATUS='DONE'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$billid=$row['BILLID'].",".$row['RECEIPTID'];
                                $profileid = $row['PROFILEID'];
		                if(!in_array($billid,$bill_arr))
                		{
					$bill_arr[]=$billid;
					$dd=$row['dd']-1;
					if(!in_array($profileid,$pro_arr))
                                        {
                                                $pro_arr[]=$profileid;
                                                $row['cnt']= 1;
                                        }
                                        else
                                                $row['cnt']= 0;

					$mtongue = $row['MTONGUE'];
	
					$j = array_search($mtongue,$mtongue_arr_val);
	
					$calls_arr[$j][$dd]['conv'] += $row['cnt'];
					$total_calls_arr[$j]['conv'] += $row['cnt'];
					$total_calls_arr[$j]['total_amt'] += $row['amt'];

					$total[$dd]['conv'] += $row['cnt'];
					$total[$dd]['total_amt'] += $row['amt'];

					$grandtotal['conv'] += $row['cnt'];
					$grandtotal['total_amt'] += $row['amt'];
				}
			}
			unset($bill_arr);
                        unset($pro_arr);
			if (!count($calls_arr))
				$norecords = 1;

		for($i=0;$i<12;$i++)
                {
                        $montharr[$i]=$i+1;
                }

/**********************************************Code for Excel*********************************************/
		if($mis_type=="XLS")
                {
                        $header = "Community / Days"."\t";
                        for($i=0;$i<count($ddarr);$i++)
                        {
                                $header=$header.$ddarr[$i]."\t";
                        }
                        $header=$header."Total"."\t"."Amount";
			
			for($i=0;$i<count($mtongue_arr_lab);$i++)
			{
				$data.=$mtongue_arr_lab[$i]."-Total Calls\t";
				for($k=0;$k<count($ddarr);$k++)
				{
					$data.=$calls_arr[$i][$k]["callcnt"]."\t";
				}
				$data.=$total_calls_arr[$i]["callcnt"]."\n";
			
				$data.=$mtongue_arr_lab[$i]."-Conv\t";
				for($k=0;$k<count($ddarr);$k++)
				{
					$data.=$calls_arr[$i][$k]["conv"]."\t";
				}
				$data.=$total_calls_arr[$i]["conv"]."\n";
				
				$data .= $mtongue_arr_lab[$i]."-Total Amount Collected\t";
				for($k=0;$k<count($ddarr);$k++)
				{
					$data .= "\t";
				}
				$data .= "\t".$total_calls_arr[$i]['total_amt']."\n";

			}
			$data .= "Total-Calls\t";
			for($i=0;$i<count($ddarr);$i++)
			{
				$data .= $total[$i]['callcnt']."\t";
			}
			$data .= $grandtotal['callcnt']."\n";
			
			$data .= "Total-Conv\t";
			for($i=0;$i<count($ddarr);$i++)
			{
				$data .= $total[$i]['conv']."\t";
			}
			$data .= $grandtotal['conv']."\n";

			$data .= "Total-Conv\t";
			for($i=0;$i<count($ddarr);$i++)
			{
				$data .= $total[$i]['total_amt']."\t";
			}
			$data .= "\t".$grandtotal['total_amt']."\n";

			$data = trim($data)."\t \n";

			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=crm_conversion_community_wise.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $final_data = $header."\n".$data;
			die();
		}
/************************************************Code Ended for Excel*****************************************************/

		$smarty->assign("calls_arr",$calls_arr);
		$smarty->assign("total_calls_arr",$total_calls_arr);
		$smarty->assign("yy",$yy);
		$smarty->assign("norecords",$norecords);
		$smarty->assign("grandtotal",$grandtotal);
		$smarty->assign("total",$total);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("montharr",$montharr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("flag",1);
		$smarty->assign("dflag",$dflag);
		$smarty->display("crm_conversion_community_wise.htm");

		unset($grandtotal);
		unset($norecords);
		unset($calls_arr);
		unset($total_calls_arr);
	}
	else
	{
                $smarty->assign("flag","0");
                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		$smarty->assign("cid",$cid);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->display("crm_conversion_community_wise.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
