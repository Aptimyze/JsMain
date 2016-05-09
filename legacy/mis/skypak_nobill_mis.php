<?php
/**************************************************************************************************************************
	*	FILENAME     : 	skypakmis.php
	*	CREATED BY   : 	Shobha Kumari
	*       DESCRIPTION  : 	This script gives a daily mis of the username and membership status to whom 
				the mail (skypak) has been sent.
	*	FILE INCLUDED :  connect.inc
**************************************************************************************************************************/

include_once("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($submit)
	{	
		$flag = 1;
		if($month <=9)
			$month = "0".$month;
		if($day <=9)
                        $day = "0".$day;
		for($i=0;$i< 31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
               
		$viewer_name =getname($cid);
		$sql_log="INSERT into incentive.MIS_VIEW(VIEWED_BY,TIME) values ('$viewer_name',now())";
		mysql_query_decide($sql_log,$db2) or die("$sql_log".mysql_error_js($db2));                                              
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);

		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";

		if ($showall)
		{
			$allrecords = 1;
			include("skypak_no_bill_misdetails.php");
			exit(0);
		}
		else
		{
		// query to find MAILID of those to whom the mail was sent
			$sql ="SELECT SENT_TO , SENT_BY , DAYOFMONTH(TIME) AS dd ,TIME FROM incentive.INVOICE_TRACK WHERE TIME BETWEEN '$st_date' AND '$end_date' AND SENT_TO <> '' AND RESEND<>'Y'";
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                	while($row=mysql_fetch_array($res))
                	{
			$time_entry=$row["TIME"];
                        $sent_arr = explode(",",$row["SENT_TO"]);
			if($sent_arr)
				$sentstring = implode("','",$sent_arr);
			$sql_details = "SELECT PROFILEID FROM incentive.PAYMENT_COLLECT where ID IN ('$sentstring') ";
			if($city!='')
                                $sql_details.= " and CITY='$city' ";
			$res_details=mysql_query_decide($sql_details,$db) or die("$sql_details".mysql_error_js());
                        while($row_details=mysql_fetch_array($res_details))
			{
				$pid_arr[]=$row_details["PROFILEID"];
			}
			if(count($pid_arr)>0)
				$pid_str=implode("','",$pid_arr);
			$count_tot=COUNT($pid_arr);
			$sql_billing = "SELECT distinct PROFILEID FROM billing.PURCHASES where PROFILEID IN ('$pid_str') and ENTRY_DT > '$time_entry' and STATUS = 'DONE' ";
			$res_billing=mysql_query_decide($sql_billing,$db) or die("$sql_billing".mysql_error_js());
			while($row_billing=mysql_fetch_array($res_billing))
			{
				$prof_bill_arr[]=$row_billing["PROFILEID"];
			}
			$count_billing=count($prof_bill_arr);
			$sql_payment = "SELECT count(*) as CNT from incentive.PAYMENT_COLLECT where ID IN ('$sentstring') and STATUS  NOT IN ('','S')";
			if($city!='')
				$sql_payment.= " and CITY='$city' ";
			if($count_billing>0)
			{
				$prof_bill_str=implode("','",$prof_bill_arr);
				$sql_payment.=" and PROFILEID not in('$prof_bill_str') ";
			}
	                        $res_payment = mysql_query_decide($sql_payment,$db) or die("$sql_payment".mysql_error_js());
        	                $row_payment=mysql_fetch_array($res_payment);
				$count_action= $row_payment["CNT"];
			$cnt_billing=$count_billing +$count_action;

			$dd=$row["dd"]-1;
			$total_billing[$dd] += $cnt_billing;
			$totalcount[$dd] += $count_tot;
			$grandtotal_bill += $cnt_billing;
			$grandtotal += $count_tot;
			unset($pid_arr);
			unset($sent_arr);
			unset($count_tot);
			unset($prof_bill_arr);
			unset($count_action);
			unset($count_billing);
			}
			foreach($ddarr as $dd)
			{
				$dd--;
				$total_no_bill[$dd]= $totalcount[$dd]-$total_billing[$dd];
			}
			$grandtotal_no_bill=$grandtotal-$grandtotal_bill;
			$smarty->assign("grandtotal",$grandtotal);
			$smarty->assign("grandtotal_bill",$grandtotal_bill);
		 	$smarty->assign("grandtotal_no_bill",$grandtotal_no_bill);
			$smarty->assign("totalcount",$totalcount);
			$smarty->assign("totalcount_bill",$total_billing);
			$smarty->assign("totalcount_no_bill",$total_no_bill);
			$smarty->assign("flag",$flag);
			$smarty->assign("cid",$cid);
			$smarty->assign("year",$year);
			$smarty->assign("month",$month);
			$smarty->assign("CITY",$city);
			$city_lbl=label_select('BRANCH_CITY',$city,'incentive');
			$smarty->assign("CITY_LBL",$city_lbl[0]);
			$smarty->display("skypakmis_no_bill.htm");
		}
	}
	else
	{
		for($i=0;$i< 31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
                $sql="select b.LABEL as LABEL,b.VALUE as VALUE from incentive.BRANCH_CITY as b where PICKUP='Y'";  
		$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$city_arr[$i]['LABEL']=$row["LABEL"];
			$city_arr[$i]['VALUE']=$row["VALUE"];
			$i++;
		}
		$smarty->assign("city_arr",$city_arr);                                                                                                          
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);

                $smarty->display("skypakmis_no_bill.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
