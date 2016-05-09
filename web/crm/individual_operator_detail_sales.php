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

	$k=0;

	//$sql="SELECT PROFILEID,sum(if(TYPE='DOL',AMOUNT*45,AMOUNT)) AS AMOUNT,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' GROUP BY PROFILEID";
	$sql="SELECT STATUS,PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,BILLID FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' ORDER BY PROFILEID";
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
			$status=$row['STATUS'];
			$entry_dt_org=$row['ENTRY_DT'];
			$entry_dt=$row['ENTRY_DT'];
			list($edt,$etime)=explode(" ",$entry_dt);
			list($yy,$mm,$dd)=explode("-",$edt);
			list($hr,$min,$sec)=explode(":",$etime);
			$ts=mktime($hr,$min,$sec,$mm,$dd,$yy);
			$ts+=24*60*60;
			$entry_dt=date("Y-m-d H:i:s",$ts);//." 23:59:59" ;

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
				$amt[$k]=$row['AMOUNT'];
				$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row1=mysql_fetch_array($res1))
				{
					$username[$k]=$row1['USERNAME'];
				}

				$flag=0;
				$sql="(SELECT CLAIM_TIME as claim_time,MODE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') UNION (SELECT CONVINCE_TIME as claim_time,MODE FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') ORDER BY claim_time ASC";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row1=mysql_fetch_array($res1))
				{
					$mode=$row1['MODE'];
					if(!$flag)
						$firstmode=$mode;
					$flag=1;
				}

				if($firstmode=='O')
				{
					$profilearr[$k]=$profileid;
					$cntops[$k]=1;
					$paid_date[$k]=substr($entry_dt_org,0,10);
				}
				else
				{
					$opsarr[$k]=array();
					$cntops[$k]=0;

					$profilearr[$k]=$profileid;
					$paid_date[$k]=substr($entry_dt_org,0,10);

					$sql="SELECT DISTINCT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F' AND WILL_PAY='Y' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
					//$sql="SELECT DISTINCT ENTRYBY FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y'";// AND CLAIM_TIME<='$entry_dt'";
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					while($row1=mysql_fetch_array($res1))
					{
						$opsarr[$k][]=$row1['ALLOTED_TO'];
					}

					//$sql="SELECT COUNT(DISTINCT ALLOTED_TO) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F' AND WILL_PAY='Y' AND CLAIM_TIME<>0";// AND CONVINCE_TIME<='$entry_dt'";
					$sql="SELECT COUNT(DISTINCT ENTRYBY) as cnt FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
					if($opsarr)
					{
						$cntops[$k]=count($opsarr[$k]);
						$opsstr="'".implode("','",$opsarr[$k])."'";
						$sql.=" AND ENTRYBY NOT IN ($opsstr)";
					}
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row1=mysql_fetch_array($res1);
					$cntops[$k]+=$row1['cnt'];
				}
				if($cntops[$k])
				{
					if($status=='DONE')
					{
						$amt_each_op[$k]+=round($amt[$k]/$cntops[$k],2);
					}
					else
					{
						$amt_each_op[$k]-=round($amt[$k]/$cntops[$k],2);
					}
				}
				if($status=='DONE')
				{
					$total_amt_ops+=round($amt_each_op[$k],2);
					$total_amt+=round($amt[$k],2);
				}
				else
				{
					$total_amt_ops-=round($amt_each_op[$k],2);
					$total_amt-=round($amt[$k],2);
				}
				if($oldprofileid!=$profileid)
					$k++;
				$oldprofileid=$profileid;
			}
		}
	}

	$smarty->assign("profilearr",$profilearr);
	$smarty->assign("username",$username);
	$smarty->assign("paid_date",$paid_date);
	$smarty->assign("cnt_ops",$cntops);
	$smarty->assign("amt",$amt);
	$smarty->assign("amt_each_ops",$amt_each_op);
	$smarty->assign("total_amt_ops",$total_amt_ops);
	$smarty->assign("total_amt",$total_amt);
	$smarty->assign("cid",$cid);

	$smarty->display("individual_operator_detail_sales.htm");
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
