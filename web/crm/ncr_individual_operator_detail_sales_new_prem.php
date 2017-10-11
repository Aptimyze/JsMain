<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");
if(authenticated($cid))
{	
	if($opsname)
	{
		$operator_name=$opsname;
		$xls=1;
	}
	else
	{
		$operator_name=getname($cid);
		$today=date("Y-m-d");
		list($yy,$mm,$dd)=explode("-",$today);
	}
	if($operator_name == 'all')
	{
		$st_date=$yy."-".$mm."-".$dd." 00:00:00";
                $end_date=$yy."-".$mm."-".$dd." 23:59:59";
	}
	else
	{
		$st_date=$yy."-".$mm."-01 00:00:00";
		$end_date=$yy."-".$mm."-31 23:59:59";
	}

	//mysql_close();
	connect_db2();

	//code added by sriram to show call source for inbound users.
	$privilage = explode("+",getprivilage($cid));
	if($operator_name != 'all')
		$center = getcenter_for_walkin($operator_name);
	
	if($operator_name != 'all')
	{
		$sql="SELECT a.STATUS,a.RECEIPTID,a.BILLID,a.PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,a.ENTRY_DT as PAID_DT , a.MODE, b.ALLOT_TIME FROM billing.PAYMENT_DETAIL a, incentive.CRM_DAILY_ALLOT b WHERE a.PROFILEID=b.PROFILEID AND a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND a.STATUS IN ('DONE','REFUND') AND b.ALLOTED_TO='$operator_name' AND b.ALLOT_TIME<=a.ENTRY_DT AND b.DE_ALLOCATION_DT>=DATE(a.ENTRY_DT) ORDER BY PAID_DT DESC";
	}
	else
	{
		$name=getname($cid);
		include_once("../mis/user_hierarchy.php");
                $allotstr = user_hierarchy($name);
		$sql="SELECT a.STATUS,a.BILLID,a.RECEIPTID,a.PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,a.ENTRY_DT as PAID_DT , a.MODE, b.ALLOT_TIME,b.ALLOTED_TO FROM billing.PAYMENT_DETAIL a, incentive.CRM_DAILY_ALLOT b WHERE a.PROFILEID=b.PROFILEID AND a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND a.STATUS IN ('DONE','REFUND') AND b.ALLOTED_TO IN ($allotstr) AND b.ALLOT_TIME<=a.ENTRY_DT AND b.DE_ALLOCATION_DT>=DATE(a.ENTRY_DT) ORDER BY PAID_DT DESC";
	}
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

	//added for optimization
	while($row = mysql_fetch_array($res))
	{
		$valid_id = 0;
                $profileid=$row['PROFILEID'];
                $billid=$row['BILLID'];
		if($operator_name != 'all')
                        $valid_id = check_validity($billid,$operator_name);
                else
                        $valid_id = check_validity($billid);
                if($valid_id)
                        $profileid_arr[] = $profileid;
	}

	if(is_array($profileid_arr))
	{
		$profileid_str = implode(",",$profileid_arr);
		unset($profileid_arr);

		$sql_jp = "SELECT PROFILEID,USERNAME, LEFT(ENTRY_DT,10) AS ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID IN($profileid_str)";
		$res_jp = mysql_query_decide($sql_jp) or die("$sql".mysql_error_js());
		$i=0;
		while($row_jp = mysql_fetch_array($res_jp))
		{
			$profileid_arr[] = $row_jp['PROFILEID'];
			$jp_det[$i]["USERNAME"] = $row_jp['USERNAME'];
			$jp_det[$i]["ENTRY_DT"] = $row_jp['ENTRY_DT'];
			$i++;
		}
	}

	@mysql_data_seek($res,0);

	$bill_arr=array();
	while($row=mysql_fetch_array($res))
	{
		$profileid=$row['PROFILEID'];
		$billid=$row['BILLID'].",".$row['RECEIPTID'];
                if(!in_array($billid,$bill_arr))
                {
			$cnt=0;
			$valid_id = 0;
			if($operator_name != 'all')
				$valid_id = check_validity($billid,$operator_name);
			else
				$valid_id = check_validity($billid);
			if($valid_id)
			{
				$bill_arr[]=$billid;
				$status=$row['STATUS'];
				$payment_dt=$row['PAID_DT'];

				//if($row1=mysql_fetch_array($res1))
				{
					$allot_time=$row['ALLOT_TIME'];
					if($operator_name == 'all')
						$sql="SELECT WILL_PAY,CONVINCE_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOT_TIME='$allot_time'";
					else
						$sql="SELECT WILL_PAY,CONVINCE_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOTED_TO='$operator_name' AND ALLOT_TIME='$allot_time'";
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row1=mysql_fetch_array($res1);
			//		if($row1['cnt']>0)
					{
						if (is_array($modearr))
						{
							if (!in_array($row['MODE'],$modearr))
								$modearr[] = $row['MODE'];
						}
						else
							$modearr[] = $row['MODE'];

						if(is_array($temparr))
						{
							if(!in_array($profileid,$temparr))
							{
								$temparr[]=$profileid;
							}
						}
						else
						{
							$temparr[]=$profileid;
						}
						$k=array_search($profileid,$temparr);
						$paid_dt[$k]=substr($row['PAID_DT'],0,10);
						if($operator_name == 'all')
						{
							$operator[$k]=$row['ALLOTED_TO'];
							$score[$k]=get_score($profileid);
							$times_paid[$k]=get_no($profileid);
						}
						$l = array_search($profileid,$profileid_arr);
						$username[$k]=$jp_det[$l]['USERNAME'];
						$convince_dt[$k]=substr($row1['CONVINCE_TIME'],0,10);
						$allot_dt[$k]=substr($allot_time,0,10);
						$entry_dt[$k]=$jp_det[$l]['ENTRY_DT'];
						$profilearr[$k]=$profileid;
						if($row1['WILL_PAY']=='H')
							$will_pay[$k]="Hot";
						elseif($row1['WILL_PAY']=='W')
							$will_pay[$k]="Warm";
						elseif($row1['WILL_PAY']=='C')
							$will_pay[$k]="Cold";
						elseif($row1['WILL_PAY']=='N')
							$will_pay[$k]="No Contact";
						elseif($row1['WILL_PAY']=='D')
							$will_pay[$k]="No Interested";
						else
							$will_pay[$k]="Not Handled";

						if($status=='DONE')
						{
							$amt[$k]+=$row['AMOUNT'];
							$total_amt+=$row['AMOUNT'];
						}
						else
						{
							$amt[$k]-=$row['AMOUNT'];
							$total_amt-=$row['AMOUNT'];
						}
						$payment_mode[$k] .= $row['MODE']." , ";
						$m = array_search($row['MODE'],$modearr);
						$mode_wise_amt[$m]+= $row['AMOUNT'];

						//code added by sriram to show call source for inbound users.
						if($privilage && ($center=='NOIDA' || $center=='MUMBAI' || $center=='PUNE' || $center=='BANGALORE' || $center=='CHENNAI' || $center=='HYDERABAD'))
						{
							if(in_array("IA",$privilage) || in_array("IUI",$privilage))
							{
								$sql_inb = "SELECT CALL_SOURCE FROM incentive.INBOUND_ALLOT WHERE PROFILEID='$profileid' AND ALLOTED_TO='$operator_name' ORDER BY ID DESC LIMIT 1";
								$res_inb = mysql_query_decide($sql_inb) or die("$sql_inb".mysql_query_decide());
								$row_inb = mysql_fetch_array($res_inb);
								if($row_inb['CALL_SOURCE']!='')
									$call_source_arr[$k] = $row_inb['CALL_SOURCE'];
								else
								{       
									$sql_man = "SELECT CALL_SOURCE FROM incentive.MANUAL_ALLOT WHERE PROFILEID='$profileid' AND ALLOTED_TO='$operator_name' ORDER BY ID DESC LIMIT 1";
									$res_man = mysql_query_decide($sql_man) or die("$sql_man".mysql_query_decide());
									$row_man = mysql_fetch_array($res_man);
									$call_source_arr[$k] = $row_man['CALL_SOURCE'];
								}

								if($status=='DONE')
									$cs_cnt[$row_inb['CALL_SOURCE']]+=$row['AMOUNT'];
								else
									$cs_cnt[$row_inb['CALL_SOURCE']]-=$row['AMOUNT'];
								$show_inbound=1;
								$smarty->assign("SHOW_INBOUND",1);
							}
						}
						//end of - code added by sriram to show call source for inbound users.
					}
				}
			}
		}
	}
	unset($bill_arr);
	//$net_off_tax_rate = 0.11;
       	$net_off_tax = round($total_amt - (($net_off_tax_rate) * $total_amt),1);
	//$net_off_tax = round(net_off_tax_calculation($total_amt,$end_date), 1);       	       	
	if($xls)
	{
/********************************Code added by Aman for Excel format****************************************************/
		if($operator_name == 'all')
			$header = "Profileid"."\t"."Username"."\t"."Registered Date"."\t"."Score"."\t"."No. of times paid"."\t"."Operator"."\t"."Allocation Date"."\t"."Last Handling Date"."\t"."Status"."\t"."Amount Paid"."\t"."Mode of payment"."\t";
		else
			$header = "Profileid"."\t"."Username"."\t"."Registered Date"."\t"."Paid Date"."\t"."Allocation Date"."\t"."Last Handling Date"."\t"."Status"."\t"."Amount Paid"."\t"."Mode of payment"."\t";
		if($show_inbound==1)
			$header.="Query Source"."\t";
		for($i=0;$i<count($profilearr);$i++)
		{
			if($operator_name == 'all')
			{
				if ($operator[$i]!='')
                                        $op=$operator[$i];
			}
			else
			{
				if ($paid_dt[$i]!='') 
					$pd_dt=$paid_dt[$i];
				else
                                	$pd_dt="Yet to pay";
			}
			if ($convince_dt[$i]!="0000-00-00" )
				$conv_dt=$convince_dt[$i];
			else
				$conv_dt="Not Handled";
			if($amt[$i]!='' ) 
				$amt_pd=$amt[$i];
			else
				$amt_pd=0;
			
			if($operator_name == 'all')
			{
				if ($score[$i]!='')
                                        $sc=$score[$i];
				if ($times_paid[$i]!='')
                                        $tp=$times_paid[$i];
				$data.=$profilearr[$i]."\t".$username[$i]."\t".$entry_dt[$i]."\t".$sc."\t".$tp."\t".$op."\t".$allot_dt[$i]."\t".$conv_dt."\t".$will_pay[$i]."\t".$amt_pd."\t".$payment_mode[$i]."\t";
			}
			else
				$data.=$profilearr[$i]."\t".$username[$i]."\t".$entry_dt[$i]."\t".$pd_dt."\t".$allot_dt[$i]."\t".$conv_dt."\t".$will_pay[$i]."\t".$amt_pd."\t".$payment_mode[$i]."\t";
			if($show_inbound==1)
				$data.=$call_source_arr[$i]."\t";
			$data.="\n";
		}
		if($operator_name == 'all')
		{
			$data.="TOTAL"."\t\t\t\t\t\t\t\t\t".$total_amt."\n";
                	$data.="Net-Off Tax TOTAL"."\t\t\t\t\t\t\t\t\t".$net_off_tax."\n";
		}
		else
		{
			$data.="TOTAL"."\t\t\t\t\t\t\t".$total_amt."\n";
			$data.="Net-Off Tax TOTAL"."\t\t\t\t\t\t\t".$net_off_tax."\n";
		}
		$data = trim($data)."\t \n";

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=individual_operator_details.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $final_data = $header."\n".$data;
		die();
/***************************************End of code for Excel format***************************************************/
	}
	else
	{
		$smarty->assign("modearr",$modearr);
		$smarty->assign("mode_wise_amt",$mode_wise_amt);
		$smarty->assign("profilearr",$profilearr);
		$smarty->assign("call_source_arr",$call_source_arr);
		$smarty->assign("cs_cnt",$cs_cnt);
		$smarty->assign("payment_mode",$payment_mode);
		$smarty->assign("username",$username);
		$smarty->assign("paid_date",$paid_dt);
		$smarty->assign("convince_date",$convince_dt);
		$smarty->assign("allot_date",$allot_dt);
		$smarty->assign("will_pay",$will_pay);
		$smarty->assign("amt",$amt);
		$smarty->assign("entry_dt",$entry_dt);
		$smarty->assign("total_amt",$total_amt);
		$smarty->assign("cid",$cid);
		$smarty->assign("net_off_tax",$net_off_tax);

		$smarty->display("ncr_individual_operator_detail_sales_new.htm");
	}
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
