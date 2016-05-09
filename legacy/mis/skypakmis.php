<?php
/**************************************************************************************************************************
	*	FILENAME     : 	skypakmis.php
	*	CREATED BY   : 	Shobha Kumari
	*       DESCRIPTION  : 	This script gives a daily mis of the username and membership status to whom 
				the mail (skypak) has been sent.
	*	FILE INCLUDED :  connect.inc
**************************************************************************************************************************/

include("connect.inc");

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
                                                                                                                            
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);

		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";
		
		// query to find MAILID of those to whom the mail was sent
		$sql ="SELECT SENT_TO , SENT_BY , DAYOFMONTH(TIME) AS dd FROM incentive.INVOICE_TRACK WHERE TIME BETWEEN '$st_date' AND '$end_date' AND SENT_TO <> '' AND RESEND='' ";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        $sent_arr = explode(",",$row["SENT_TO"]);
			if($sent_arr)
				$sentstring = implode("','",$sent_arr);

			$sql_details = "SELECT COUNT(*) AS CNT , STATUS FROM incentive.PAYMENT_COLLECT where ID IN ('$sentstring') GROUP BY STATUS";	
			$res_details=mysql_query_decide($sql_details,$db) or die("$sql_details".mysql_error_js());
			while($row_details=mysql_fetch_array($res_details))
			{
				$dd = $row["dd"]-1;
				if ($row_details["STATUS"] == '')
					$status = 'N';
				else
					$status = $row_details["STATUS"];

				if (is_array($statusarr))
				{
					if (!in_array($status,$statusarr))
						$statusarr[] = $status;
				}
				else
				{
					$statusarr[] = $status;
				}

				if (in_array($status,$statusarr))
				{
					$k = array_search($status,$statusarr);
					$statuscount[$k][$dd] += $row_details["CNT"];
					$totalcount[$dd] += $row_details["CNT"];
					$totalstatcount[$k] += $row_details["CNT"];
					$grandtotal += $row_details["CNT"];
				}
                        }
                }
		//print_r($totalcount);
		$smarty->assign("grandtotal",$grandtotal);
		$smarty->assign("totalstatcount",$totalstatcount);
		$smarty->assign("totalcount",$totalcount);
		$smarty->assign("statuscount",$statuscount);
		$smarty->assign("statusarr",$statusarr);
		$smarty->assign("flag",$flag);
                $smarty->assign("cid",$cid);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
                $smarty->display("skypakmis.htm");
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
                                                                                                                            
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);

                $smarty->display("skypakmis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
