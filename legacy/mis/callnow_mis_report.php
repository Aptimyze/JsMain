<?php
/***************************************************************************************************************************
FILE NAME		: callnow_mis_report.php
DESCRIPTION		: This file shows count of unique callers,receiver,phone numbers for the call made using IVR Callnow.
DATE			: August 21th 2009.
CREATED BY		: Manoj 
***************************************************************************************************************************/
include('connect.inc');

$db = connect_misdb();
$db2 = connect_master();
if(authenticated($cid))
{
	if($outside=="Y")
	{
		$start_date = date("Y-m")."-01 00:00:00";
		$end_date = date("Y-m")."-31 23:59:59";
		$submit = 1;
	}
	else
	{
		$start_date = $year."-".$month."-01 00:00:00";
		$end_date = $year."-".$month."-31 23:59:59";
	}
	if($submit)
	{
		$gender="";
		$gender =$_GET['gender'];
		if($gender!='')
			$smarty->assign("gender",$gender);

		if($year<="2009" && $month<="7")
		{
			$smarty->assign("NO_DATA",1);
		}
		else
		{
			$callerTotalStr ="";
			$callerTotalArr =array();
			//finding count of unique call initiators
			$sql_up_req = "SELECT GROUP_CONCAT(distinct CALLER_PID) AS CALLER_TOTAL, DAYOFMONTH(CALL_DT) AS DAY FROM newjs.CALLNOW p WHERE CALL_DT BETWEEN '$start_date' AND '$end_date' GROUP BY DAY";
			$res_up_req = mysql_query_decide($sql_up_req) or die($sql_up_req.mysql_error_js());
			while($row_up_req = mysql_fetch_array($res_up_req))
			{
				$callerTotalStr = $row_up_req['CALLER_TOTAL'];
				$callerTotalArr =explode(",",$callerTotalStr);
				$countNo =count($callerTotalArr);

				// Gender specific query
				if($gender!='' && $callerTotalStr!='')
				{	
					$sql_g = "SELECT COUNT(distinct PROFILEID) AS COUNT FROM newjs.JPROFILE WHERE GENDER='$gender' AND PROFILEID IN($callerTotalStr)";
                        		$res_g = mysql_query_decide($sql_g) or die($sql_g.mysql_error_js());
                        		while($row_g = mysql_fetch_array($res_g))
                        		{
						$countNo =$row_g['COUNT'];
					}					
				}
				$day = $row_up_req['DAY'] - 1;
				$caller[$day] = $countNo;
				$total["CALLER"] += $countNo;
			}

                        //finding count of unique call receivers(successful received calls) 
                        $sql_up_req = "SELECT COUNT(distinct RECEIVER_PID) AS COUNT, DAYOFMONTH(CALL_DT) AS DAY FROM newjs.CALLNOW p WHERE CALL_DT BETWEEN '$start_date' AND '$end_date' AND `CALL_STATUS`='R' GROUP BY DAY";
                        $res_up_req = mysql_query_decide($sql_up_req) or die($sql_up_req.mysql_error_js());
                        while($row_up_req = mysql_fetch_array($res_up_req))
                        {
                                $day = $row_up_req['DAY'] - 1;
                                $receiver_success[$day] = $row_up_req['COUNT'];
                                $total["RECEIVER_SUCCESS"] += $row_up_req['COUNT'];
                        }

                        //finding count of unique call receivers(Missed) 
                        $sql_up_req = "SELECT COUNT(distinct RECEIVER_PID) AS COUNT, DAYOFMONTH(CALL_DT) AS DAY FROM newjs.CALLNOW p WHERE CALL_DT BETWEEN '$start_date' AND '$end_date' AND `CALL_STATUS` NOT IN('R','I','E') GROUP BY DAY";
                        $res_up_req = mysql_query_decide($sql_up_req) or die($sql_up_req.mysql_error_js());
                        while($row_up_req = mysql_fetch_array($res_up_req))
                        {
                                $day = $row_up_req['DAY'] - 1;
                                $receiver_missed[$day] = $row_up_req['COUNT'];
                                $total["RECEIVER_MISSED"] += $row_up_req['COUNT'];
                        }

                        //finding count of unique phone numbers 
                        $sql_up_req = "SELECT COUNT(distinct CALLER_PHONE) AS COUNT, DAYOFMONTH(CALL_DT) AS DAY FROM newjs.CALLNOW p WHERE CALL_DT BETWEEN '$start_date' AND '$end_date' GROUP BY DAY";
                        $res_up_req = mysql_query_decide($sql_up_req) or die($sql_up_req.mysql_error_js());
                        while($row_up_req = mysql_fetch_array($res_up_req))
                        {
                                $day = $row_up_req['DAY'] - 1;
                                $caller_phone_number[$day] = $row_up_req['COUNT'];
                                $total["CALLER_PHONE_NUMBER"] += $row_up_req['COUNT'];
                        }
		}

		for($i=1;$i<=31;$i++)
			$ddarr[] = $i;
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("caller",$caller);
		$smarty->assign("receiver_success",$receiver_success);
		$smarty->assign("receiver_missed",$receiver_missed);
		$smarty->assign("caller_phone_number",$caller_phone_number);
		$smarty->assign("total",$total);
		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->assign("RESULT",1);
	}
	else
	{
		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;

		for($i=2005;$i<=date('Y')+1;$i++)
			$yyarr[] = $i;

		list($curmonth,$curyear) = explode("-",date('m-Y'));
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("curyear",$curyear);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->display("callnow_mis_report.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
