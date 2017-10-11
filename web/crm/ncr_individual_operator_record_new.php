<?php
//die("This module is temporarily down");
include("connect.inc");
include_once("../profile/pg/functions.php");

if(authenticated($cid))
{
	$mmarr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	$operator_name=getname($cid);
	$priv=getprivilage("",$operator_name);
        $privilages=explode("+",$priv);

	connect_db2();

	$today=date("Y-m-d");
	list($yy,$mm,$dd)=explode("-",$today);

	$st_date=$yy."-".$mm."-01 00:00:00";
	$end_date=$yy."-".$mm."-31 23:59:59";

	$previous_year = date("Y") - 1;
	$current_year = date("Y");
	$financial_year = $previous_year."-".$current_year;
	$smarty->assign("financial_year",$financial_year);

	$total_paid	=0;
	$total_amt	=0;
	$profilearr	=array();
	$bill_arr	=array();


	if(in_array("ExcPrm",$privilages) || in_array("ExPrmO",$privilages)){
		$sql="SELECT a.PROFILEID,a.RECEIPTID,a.BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,a.STATUS,a.ENTRY_DT FROM billing.PAYMENT_DETAIL a, incentive.CRM_DAILY_ALLOT b WHERE a.PROFILEID=b.PROFILEID AND a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND a.STATUS IN ('DONE','REFUND') AND b.ALLOT_TIME<=a.ENTRY_DT AND b.DE_ALLOCATION_DT>=DATE(a.ENTRY_DT) AND b.ALLOTED_TO='$operator_name' ORDER BY a.PROFILEID";

		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$profileid	=$row['PROFILEID'];
			$billid		=$row['BILLID'].",".$row['RECEIPTID'];
                	if(!in_array($billid,$bill_arr)){
				$valid_id = 0;
				$valid_id = check_validity($billid,$operator_name);
				if($valid_id){
					$bill_arr[]=$billid;
					$amt	=$row['AMOUNT'];
					$status	=$row['STATUS'];
					//$paid_time=$row['ENTRY_DT'];

					if($status=='DONE'){
						if(!in_array($profileid,$profilearr))
							$total_paid++;
						$total_amt+=$row['AMOUNT'];
					}
					else{
						$total_paid--;
						$total_amt-=$row['AMOUNT'];
					}
					if(!in_array($profileid,$profilearr))
						$profilearr[]=$profileid;
				}
			}
		}
	}
	else{
		$db2 =connect_db();
        	$sql ="SELECT PROFILEID,AMOUNT FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT >='$st_date' AND ENTRY_DT<='$end_date' AND ALLOTED_TO='$operator_name'";
	        $res=mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js());
	        while($row=mysql_fetch_array($res)){
        	        $profilearr[]	=$row['PROFILEID'];
			$total_amt	+=$row['AMOUNT'];
		}
		$profilearr =array_unique($profilearr);
		$total_paid =count($profilearr);
		mysql_close($db2);
	}
	unset($bill_arr);
	unset($profilearr);

	$sql = "SELECT AMOUNT , MONTH(ENTRY_DT) AS MM FROM MIS.CRM_MONTHLY_REVENUE WHERE USER ='$operator_name' AND ENTRY_DT >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) GROUP BY MM ";
        $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while ($row = mysql_fetch_array($res))
        {
                $mm 		=$row['MM'] - 1 ;
                $revenue[$mm] 	=$row['AMOUNT'];
                $amttot		+=$row['AMOUNT'];
        }

	$smarty->assign("revenue",$revenue);
        $smarty->assign("amttot",$amttot);
        $smarty->assign("mmarr",$mmarr);

	$smarty->assign("total_paid",$total_paid);
	$smarty->assign("total_amt",$total_amt);
	$smarty->assign("cid",$cid);

	$smarty->display("ncr_individual_operator_record_new.htm");
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
