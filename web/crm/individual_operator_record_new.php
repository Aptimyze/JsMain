<?php
include("connect.inc");
include_once("../profile/pg/functions.php");
//$db2=connect_db2();
//$db=connect_db();
if(authenticated($cid))
{
	$mmarr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$operator_name=getname($cid);

	$today=date("Y-m-d");
	list($yy,$mm,$dd)=explode("-",$today);

	$st_date=$yy."-".$mm."-01 00:00:00";
	$end_date=$yy."-".$mm."-31 23:59:59";

	$total_paid=0;
	$total_amt=0;

	$profilearr=array();

	$sql="SELECT PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,STATUS,BILLID FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND') AND ORDER BY PROFILEID";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$profileid=$row['PROFILEID'];
		$billid=$row['BILLID'];
	 	$valid_id = 0;
		$valid_id = check_validity($billid,$operator_name);
		if($valid_id)
		{
			$amt=$row['AMOUNT'];
			$status=$row['STATUS'];

			$sql="SELECT COUNT(*) AS cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOTED_TO='$operator_name'";
			$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row1=mysql_fetch_array($res1);
			if($row1['cnt']>0)
			{

				if($status=='DONE')
				{
					if(!in_array($profileid,$profilearr))
						$total_paid++;
					$total_amt+=$row['AMOUNT'];
				}
				else
				{
					$total_paid--;
					$total_amt-=$row['AMOUNT'];
				}

				if(!in_array($profileid,$profilearr))
				{
					$profilearr[]=$profileid;
				}
			}
		}
	}
	$sql = "SELECT AMOUNT , MONTH(ENTRY_DT) AS MM FROM MIS.CRM_MONTHLY_REVENUE WHERE USER ='$operator_name' GROUP BY MM ";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while ($row = mysql_fetch_array($res))
	{
		$mm = $row['MM'] - 1 ;
		$amt[$mm] = $row['AMOUNT'];
		$amttot+=$row['AMOUNT'];
	}
	unset($profilearr);

	$smarty->assign("amt",$amt);
	$smarty->assign("amttot",$amttot);
	$smarty->assign("mmarr",$mmarr);
//	$smarty->assign("total_claim",$total_claim);
	$smarty->assign("total_paid",$total_paid);
	$smarty->assign("total_amt",$total_amt);
//	$smarty->assign("total_nonpaid",$total_nonpaid);
	$smarty->assign("cid",$cid);

	$smarty->display("individual_operator_record_new.htm");
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
