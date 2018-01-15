<?php

include("connect.inc");

//$db2=connect_db2();
//$db=connect_db();

$db=connect_db();
if(authenticated($cid))
{
        @mysql_close($db);
        $db=connect_81();
	$operator_name=getname($cid);

	$today=date("Y-m-d");
	list($yy,$mm,$dd)=explode("-",$today);

	$st_date=$yy."-".$mm."-01 00:00:00";
	$end_date=$yy."-".$mm."-31 23:59:59";

	$i=0;
	$j=0;

	$sql="(SELECT PROFILEID,CLAIM_TIME as claim_time,MODE FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$operator_name' AND STATUS='F' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND CLAIM_TIME<>0) UNION (SELECT PROFILEID,CONVINCE_TIME as claim_time,MODE FROM incentive.CLAIM WHERE ENTRYBY='$operator_name' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date') ORDER BY claim_time ASC";
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
		$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profilearr[$k]'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$username[$k]=$row['USERNAME'];
		}

		if($modearr[$k]=='O')
		{
			$cnt_ops[$k]=1;
		}
		else
		{
			$opsarr[$k]=array();
			$sql="SELECT DISTINCT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND STATUS='F' AND CLAIM_TIME<>0 AND CLAIM_TIME BETWEEN '$st_date' AND '$end_date'";
//			$sql="SELECT DISTINCT ENTRYBY FROM incentive.CLAIM WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					if(is_array($opsarr[$k]))
					{
						if(!in_array($row['ALLOTED_TO'],$opsarr[$k]))
						{
							$opsarr[$k][]=$row['ALLOTED_TO'];
						}
					}
					else
					{
						$opsarr[$k][]=$row['ALLOTED_TO'];
					}
				}while($row=mysql_fetch_array($res));
			}
			$cnt_ops[$k]=count($opsarr[$k]);
			mysql_free_result($res);

			if($opsarr[$k])
				$ops_str="'".implode("','",$opsarr[$k])."'";

//			$sql="SELECT COUNT(DISTINCT ALLOTED_TO) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND STATUS='F' AND CLAIM_TIME<>0";
			$sql="SELECT count(DISTINCT ENTRYBY) as cnt FROM incentive.CLAIM WHERE PROFILEID='$profilearr[$k]' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date'";
			if($opsarr[$k])
				$sql.=" AND ENTRYBY NOT IN ($ops_str)";

			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
				$cnt_ops[$k]+=$row['cnt'];
			mysql_free_result($res);
		}

		//$sql="SELECT sum(AMOUNT) as amt,PROFILEID,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profilearr[$k]' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 31 DAY) GROUP BY PROFILEID";
		$sql="SELECT STATUS,AMOUNT as amt,PROFILEID,ENTRY_DT,BILLID FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profilearr[$k]' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 31 DAY)";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$valid_id = 0;
		                $profileid=$row['PROFILEID'];
				$billid=$row['BILLID'];
                		$valid_id = check_validity($billid,$operator_name);
                		if($valid_id)
                		{
					$entry_dt=$row['ENTRY_DT'];
					$amt[$k]=$row['amt'];
					$status=$row['STATUS'];
					list($edt,$etime)=explode(" ",$entry_dt);
					list($yy,$mm,$dd)=explode("-",$edt);
					list($hr,$min,$sec)=explode(":",$etime);
					$ts=mktime($hr,$min,$sec,$mm,$dd,$yy);
					$ts+=24*60*60;
					$entry_dt=date("Y-m-d H:i:s",$ts);
					if($entry_dt>=$convince_time[$k])
					{
						if($cnt_ops[$k])
						{
							if($status=='DONE')
								$amt_each_op[$k]+=round($amt[$k]/$cnt_ops[$k],2);
							else
								$amt_each_op[$k]-=round($amt[$k]/$cnt_ops[$k],2);
						}
					}

					$paid_date[$k]=substr($row['ENTRY_DT'],0,10);
					$bandcolor[$k]="fieldsnewgreen";
					if($status=='DONE')
					{
						$total_amt_ops+=$amt_each_op[$k];
						$total_amt_all+=$amt[$k];
					}
					else
					{
						$total_amt_ops-=$amt_each_op[$k];
						$total_amt_all-=$amt[$k];
					}
				}
			}while($row=mysql_fetch_array($res));
		}
		else
		{
                        $bandcolor[$k]="fieldsnew";
		}
		$convince_time[$k]=substr($convince_time[$k],0,10);
		mysql_free_result($res);
	}

	$total_paid=$j;
	$total_nonpaid=$total_claim-$total_paid;

	$smarty->assign("claim_date",$convince_time);
	$smarty->assign("amt",$amt_each_op);
	$smarty->assign("total_amt",$amt);
	$smarty->assign("cnt_ops",$cnt_ops);
	$smarty->assign("paid_date",$paid_date);
	$smarty->assign("username",$username);
	$smarty->assign("profilearr",$profilearr);
	$smarty->assign("bandcolor",$bandcolor);
	$smarty->assign("total_amt_ops",$total_amt_ops);
	$smarty->assign("total_amt_all",$total_amt_all);
	$smarty->assign("cid",$cid);

	$smarty->display("individual_operator_detail.htm");
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
