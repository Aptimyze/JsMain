<?php

include("connect.inc");
include_once("../profile/pg/functions.php");
//$db2=connect_db2();
//$db=connect_db();
if(authenticated($cid))
{
	if($opsname)
		$operator_name=$opsname;
	else
	{
		$operator_name=getname($cid);
		$today=date("Y-m-d");
		list($yy,$mm,$dd)=explode("-",$today);
	}

	$st_date=$yy."-".$mm."-01 00:00:00";
	$end_date=$yy."-".$mm."-31 23:59:59";

	//$sql="SELECT j.USERNAME,STATUS,b.PROFILEID, AMOUNT,left(b.ENTRY_DT,10) as PAID_DT,left(j.ENTRY_DT,10) as ENTRY_DT FROM billing.PAYMENT_DETAIL b,newjs.JPROFILE j WHERE b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND') AND b.PROFILEID=j.PROFILEID ORDER BY PAID_DT DESC,ENTRY_DT DESC";

	$sql="SELECT j.USERNAME,STATUS,b.PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,left(b.ENTRY_DT,10) as PAID_DT,left(j.ENTRY_DT,10) as ENTRY_DT FROM billing.PAYMENT_DETAIL b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND') AND j.SUBSCRIPTION LIKE '%F%' ORDER BY PAID_DT DESC,ENTRY_DT DESC";
	
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

	while($row=mysql_fetch_array($res))
	{
		$cnt=0;
		$profileid=$row['PROFILEID'];
		$status=$row['STATUS'];
		//echo "<br>$profileid";

		$sql="SELECT WILL_PAY,CONVINCE_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOTED_TO='$operator_name'";
		$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($res1))
//		if($row1['cnt']>0)
		{
			//echo " - YES";
			$row1=mysql_fetch_array($res1);
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
			//$paid_dt[$k]=substr($row['PAID_DT'],0,10);
			$paid_dt[$k]=$row['PAID_DT'];
			$username[$k]=$row['USERNAME'];
			$convince_dt[$k]=substr($row1['CONVINCE_TIME'],0,10);
			$entry_dt[$k]=$row['ENTRY_DT'];
			$profilearr[$k]=$profileid;
			if($row1['WILL_PAY']=='H')
				$will_pay[$k]="Hot";
			elseif($row1['WILL_PAY']=='W')
				$will_pay[$k]="Warm";
			elseif($row1['WILL_PAY']=='C')
				$will_pay[$k]="Cold";
			elseif($row1['WILL_PAY']=='N')
				$will_pay[$k]="No Contact";
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
			
/*			if($oldprofileid!=$profileid)
			{
                                $k++;
			}
			$oldprofileid=$profileid;*/
		}
	}
	$smarty->assign("profilearr",$profilearr);
	$smarty->assign("username",$username);
	$smarty->assign("paid_date",$paid_dt);
	$smarty->assign("convince_date",$convince_dt);
	$smarty->assign("will_pay",$will_pay);
	$smarty->assign("amt",$amt);
	$smarty->assign("entry_dt",$entry_dt);
//	$smarty->assign("total_amt_ops",$total_amt_ops);
	$smarty->assign("total_amt",$total_amt);
	$smarty->assign("cid",$cid);

	$smarty->display("individual_operator_detail_sales_new.htm");
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
