<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");
include_once("user_hierarchy.php");
$db=connect_misdb();
//$db=connect_737m();
$db2=connect_master();

if(authenticated($cid))
{
	mysql_close($db2);
	unset($db2);
	if($outside)
        {
		/*
                $CMDGo='Y';
                $branch='ALL';
                $today=date("Y-m-d");
                list($myear,$mmonth,$d)=explode("-",$today);
		*/
		mysql_close($db);
		unset($db);
		include("crm_handled_revenue_currentMis.php");
		die();
        }
	if($CMDGo)
	{
		$today=date("Y-m-d");
		list($myearCur,$mmonthCur,$dCur)=explode("-",$today);
		if($mmonth>0 && $mmonth<9)
			$mmonth ="0".$mmonth;

		$dateSelected =1;
		//if($myear==$myearCur && $mmonth==$mmonthCur){
		if(1)
		{
			mysql_close($db);
			unset($db);
			include("crm_handled_revenue_currentMis.php");	
			die();
		}

		$smarty->assign("flag","1");
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		$name=getname($cid);

		if($name == 'vibhor' || $name == 'shilpi.sharma')
                        $name = "anamika.singh";

		$allotstr = user_hierarchy($name);
		//$allotstr ="'rachna.mandal'";

		$smarty->assign("yy",$myear);
		$smarty->assign("mm",$mmonth);

		$st_date=$myear."-".$mmonth."-01 00:00:00";
		$end_date=$myear."-".$mmonth."-31 23:59:59";
		$bill_arr=array();
		$sql="SELECT a.STATUS,a.RECEIPTID,a.PROFILEID,a.BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,a.ENTRY_DT,DAYOFMONTH(a.ENTRY_DT) as dd, b.ALLOTED_TO FROM billing.PAYMENT_DETAIL a, incentive.CRM_DAILY_ALLOT b WHERE a.PROFILEID=b.PROFILEID AND a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND a.STATUS IN ('DONE','REFUND') AND b.ALLOT_TIME<=a.ENTRY_DT AND b.DE_ALLOCATION_DT>=DATE(a.ENTRY_DT) AND b.ALLOTED_TO IN ($allotstr)";

		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$ind=0;
				$profileid=$row['PROFILEID'];
				$amount=$row['AMOUNT'];
				$status=$row['STATUS'];
				$paid_dt=$row['ENTRY_DT'];
				$billid=$row['BILLID'].",".$row['RECEIPTID'];
				if(!in_array($billid,$bill_arr))
                		{
					$valid_id = 0;
					$valid_id = check_validity_followup($billid,$row['ALLOTED_TO'],$db);
					if($valid_id)
					{
						$bill_arr[]=$billid;
						{
							$dd=$row['dd']-1;
							$alloted_to=$row['ALLOTED_TO'];

							$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
							$res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
							$row_c=mysql_fetch_array($res_c);
							$center=strtoupper($row_c['CENTER']);
							if(is_array($brancharr))
							{
								if(!in_array($center,$brancharr))
								{
										$brancharr[]=$center;
								}
							}
							else
							{
									$brancharr[]=$center;
							}

							if(1)//$branch=='ALL' || strtoupper($branch)==$center)
							{
								$i=array_search($center,$brancharr);
								if(is_array($operatorarr[$i]))
								{
									if(!in_array($alloted_to,$operatorarr[$i]))
									{
										$operatorarr[$i][]=$alloted_to;
									}
								}
								else
								{
									$operatorarr[$i][]=$alloted_to;
								}
								$j=array_search($alloted_to,$operatorarr[$i]);
								if($status=='DONE')
								{
									$amt[$i][$j][$dd]+=$amount;
									$amta[$i][$j]+=$amount;
									$amtb[$i][$dd]+=$amount;
									$amttot[$i]+=$amount;
									$amt_all[$dd]+=$amount;	
									$totamnt+=$amount;
								}
								else
								{
									$amt[$i][$j][$dd]-=$amount;
									$amta[$i][$j]-=$amount;
									$amtb[$i][$dd]-=$amount;
									$amttot[$i]-=$amount;
									$amt_all[$dd]-=$amount;
									$totamnt-=$amount;
								}

								//added by sriram.
								//calculate net off tax values for each branch and also for total.
								$net_off_tax[$i][$j] = net_off_tax_calculation($amta[$i][$j],$end_date);
								$net_off_tax_final[$i] = net_off_tax_calculation($amttot[$i],$end_date);
							}
						}
					}
				}
			}while($row=mysql_fetch_array($res));
		}

		$net_off_tax_total = net_off_tax_calculation($totamnt,$end_date);	

		unset($bill_arr);
		if($brancharr)
			$smarty->assign("BRANCH","Y");

		
		$smarty->assign("cid",$cid);
		$smarty->assign("amt",$amt);
		$smarty->assign("amta",$amta);
		$smarty->assign("amtb",$amtb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("net_off_tax",$net_off_tax);
		$smarty->assign("net_off_tax_final",$net_off_tax_final);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);
		$smarty->assign("amt_all",$amt_all);
		$smarty->assign("totamnt",$totamnt);
		$smarty->assign("net_off_tax_total",$net_off_tax_total);

		$smarty->display("crm_users_revenue_new.htm");

	}
	else
	{
		$user=getname($cid);
		$smarty->assign("flag","0");

		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
                $smarty->assign("priv",$priv);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
		$smarty->display("crm_users_revenue_new.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
