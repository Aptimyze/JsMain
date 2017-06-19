<?php
include("connect.inc");
//$db2=connect_db2();
//$db=connect_db();
if(authenticated($cid))
{
	$operator_name=getname($cid);

	$today=date("Y-m-d");
	list($yy,$mm,$dd)=explode("-",$today);

	$st_date=$yy."-".$mm."-01 00:00:00";
	$end_date=$yy."-".$mm."-31 23:59:59";

	$amt_each_op=0;
	$i=0;
	$j=0;

	$sql="(SELECT PROFILEID,CLAIM_TIME as claim_time,MODE FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$operator_name' AND STATUS='F' AND WILL_PAY='Y' AND CLAIM_TIME BETWEEN '$st_date' AND '$end_date' AND CLAIM_TIME<>0) UNION (SELECT PROFILEID,CONVINCE_TIME as claim_time,MODE FROM incentive.CLAIM WHERE ENTRYBY='$operator_name' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date') ORDER BY claim_time ASC";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$profileid=$row['PROFILEID'];
			if(is_array($profilearr))
			{
				if(!in_array($profileid,$profilearr))
				{
					$profilearr[$i]=$profileid;
					$convince_time[$i]=$row['claim_time'];
					$modearr[$i]=$row['MODE'];
					$i++;
				}
			}
			else
			{
				$profilearr[$i]=$profileid;
				$convince_time[$i]=$row['claim_time'];
				$modearr[$i]=$row['MODE'];
				$i++;
			}
		}while($row=mysql_fetch_array($res));
	}

	mysql_free_result($res);
	$total_claim=$i;

	for($k=0;$k<count($profilearr);$k++)
	{
		if($modearr[$k]=='O')
		{
			$cnt_ops=1;
		}
		else
		{
			$opsarr=array();
			$sql="SELECT DISTINCT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND STATUS='F' AND CLAIM_TIME<>0 AND CLAIM_TIME BETWEEN '$st_date' AND '$end_date'";
//			$sql="SELECT DISTINCT ENTRYBY FROM incentive.CLAIM WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND CONVINCE_TIME<>0 CONVINCE_TIME BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$cnt_ops=0;
				do
				{
					if(is_array($opsarr))
					{
						if(!in_array($row['ALLOTED_TO'],$opsarr))
						{
							$opsarr[]=$row['ALLOTED_TO'];
						}
					}
					else
					{
						$opsarr[]=$row['ALLOTED_TO'];
					}
				}while($row=mysql_fetch_array($res));
			}
			$cnt_ops=count($opsarr);
			mysql_free_result($res);

			if($opsarr)
				$ops_str="'".implode("','",$opsarr)."'";

//			$sql="SELECT COUNT(DISTINCT ALLOTED_TO) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND STATUS='F' AND CLAIM_TIME<>0 AND CLAIM_TIME BETWEEN '$st_date' AND '$end_date'";
			$sql="SELECT count(DISTINCT ENTRYBY) as cnt FROM incentive.CLAIM WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date'";
			if($opsarr)
				$sql.=" AND ENTRYBY NOT IN ($ops_str)";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$cnt_ops+=$row['cnt'];

			mysql_free_result($res);
		}

		$sql="SELECT COUNT(*) as cnt FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profilearr[$k]' AND DATE_ADD(ENTRY_DT,INTERVAL 1 DAY)>='$convince_time[$k]' AND STATUS='DONE' AND SERVEFOR LIKE '%F%'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			if($row['cnt']>0)
				$j++;
		}
		mysql_free_result($res);
	}

	$total_paid=$j;
	$total_nonpaid=$total_claim-$total_paid;

	//$sql="SELECT PROFILEID,sum(if(TYPE='DOL',AMOUNT*45,AMOUNT)) AS AMOUNT,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' GROUP BY PROFILEID";
	$sql="SELECT STATUS,PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,BILLID FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND')";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$cnt=0;
		$profileid=$row['PROFILEID'];
		$billid=$row['BILLID'];
		$valid_id = 0;
		$valid_id = check_validity($billid,$operator_name);
		if($valid_id)
		{
			$amt=$row['AMOUNT'];
			$entry_dt=$row['ENTRY_DT'];
			$status=$row['STATUS'];
			list($edt,$etime)=explode(" ",$entry_dt);
			list($yy,$mm,$dd)=explode("-",$edt);
			list($hr,$min,$sec)=explode(":",$etime);
			$ts=mktime($hr,$min,$sec,$mm,$dd,$yy);
			$ts+=24*60*60;
			$entry_dt=date("Y-m-d H:i:s",$ts);

			$sql="SELECT COUNT(*) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F' AND WILL_PAY='Y' AND ALLOTED_TO='$operator_name' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
			$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row1=mysql_fetch_array($res1))
			{
				$cnt=$row1['cnt'];
			}

			$sql="SELECT COUNT(*) as cnt FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND ENTRYBY='$operator_name' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
			$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row1=mysql_fetch_array($res1))
			{
				$cnt+=$row1['cnt'];
			}

			if($cnt>0)
			{
				$sql="(SELECT CLAIM_TIME as claim_time,MODE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') UNION (SELECT CONVINCE_TIME as claim_time,MODE FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') ORDER BY claim_time ASC LIMIT 1";
				//$sql="(SELECT CLAIM_TIME as claim_time,MODE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND AND STATUS='F' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date') UNION (SELECT CONVINCE_TIME as claim_time,MODE FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date') ORDER BY claim_time ASC LIMIT 1";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row1=mysql_fetch_array($res1);
				$mode=$row1['MODE'];

				if($mode=='O')
				{
					$cntops=1;
				}
				else
				{
					$opsarr=array();
					$cntops=0;
					$sql="SELECT DISTINCT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F' AND WILL_PAY='Y' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					while($row1=mysql_fetch_array($res1))
					{
						$opsarr[]=$row1['ALLOTED_TO'];
					}

					$sql="SELECT COUNT(DISTINCT ENTRYBY) as cnt FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
					if($opsarr)
					{
						$cntops=count($opsarr);
						$opsstr="'".implode("','",$opsarr)."'";
						$sql.=" AND ENTRYBY NOT IN ($opsstr)";
					}
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row1=mysql_fetch_array($res1);
					$cntops+=$row1['cnt'];
				}

				if($status=='DONE')
					$amt_each_op+=round($amt/$cntops,2);
				else
					$amt_each_op-=round($amt/$cntops,2);
			}
		}
	}

	$smarty->assign("total_claim",$total_claim);
	$smarty->assign("total_paid",$total_paid);
	$smarty->assign("total_amt",$amt_each_op);
	$smarty->assign("total_nonpaid",$total_nonpaid);
	$smarty->assign("cid",$cid);

	$smarty->display("individual_operator_record.htm");
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
