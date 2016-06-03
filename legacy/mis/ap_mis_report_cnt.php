<?php
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_functions.php");

/*  Variable defined
 * $MGR: set when managed logged in and viewing the report
 * $role: role of the logged in person
 * $name: username of the logged in person	
 * $outside :variable set when viewed report through Jump link
*/

$db = connect_misdb();
$db2 = connect_master();

$data = authenticated($cid);
$name =getname($cid);
$role =trim($role);
//$MGR ='1';

if($role=='')
	echo "No report exist.";	
 
// Check added when mis viewed through Jump       
if($outside=='Y'){
        $submit ='1';
        $day_month_wise ='D';
        $dateArray =explode("-",date("Y-m-d"));
        $month =$dateArray[1];
        $year_d =$dateArray[0];
}

if($data)
{
	if($submit)
	{

		// Manager logged in case check
		if($MGR){ 
			if(is_array($namesArr)){
				$nameStr =implode("','",$namesArr);	
				$nameStr ="'".$nameStr."'";
			}
			else{
				$namesArr=getMng_EmployeeNames($name,$role);
				$nameStr =implode("','",$namesArr);
				$nameStr ="'".$nameStr."'";
			}
		}
		else
			$nameStr ="'".$name."'";		
		// Ends Manager logged in

		if($day_month_wise=="D")
		{
			$start_dt = $year_d."-".$month."-01";
			$end_dt = $year_d."-".$month."-31";
			
			if($role=='TC')
				$sql_call = "SELECT count(`MATCH_ID`) AS COUNT, DAYOFMONTH(CALL_DATE) AS DAY FROM Assisted_Product.AP_CALL_HISTORY WHERE `CALL_DATE` BETWEEN '$start_dt' AND '$end_dt' AND CALL_STATUS='Y' AND TELECALLER IN($nameStr) GROUP BY DAY";
			elseif($role=='DIS')
				$sql_call = "SELECT count(`MATCH_ID`) AS COUNT, DAYOFMONTH(DATE) AS DAY FROM Assisted_Product.AP_AUDIT_TRAIL WHERE `DATE` BETWEEN '$start_dt' AND '$end_dt' AND DESTINATION='DIS' AND MOVED_BY IN($nameStr) GROUP BY DAY";
			elseif($role=='QA')
				$sql_call = "SELECT count(`PROFILEID`) AS COUNT, DAYOFMONTH(`SUBMIT_TIME`) AS DAY FROM Assisted_Product.AP_QUEUE_LOG WHERE SUBMIT_TIME BETWEEN '$start_dt' AND '$end_dt' AND STATUS='DONE' AND (ASSIGNED_FOR='NQA' OR ASSIGNED_FOR='RQA') AND ASSIGNED_TO IN($nameStr) GROUP BY DAY";

			$res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
			while($row_call = mysql_fetch_array($res_call))
			{
				$day = $row_call['DAY'] - 1;
				$rec_count[$day] = $row_call['COUNT'];
			}

			for($i=1;$i<=31;$i++)
				$arr[] = $i;

			$smarty->assign("top_label","Day");
			$smarty->assign("head_label","Details for $month / $year_d");
		}
		elseif($day_month_wise=="M")
		{
			$start_dt = $year_m."-01-01";
			$end_dt = $year_m."-12-31";
			if($role=='TC')
				$sql_call = "SELECT count(`MATCH_ID`) AS COUNT, MONTH(CALL_DATE) AS MONTH FROM Assisted_Product.AP_CALL_HISTORY WHERE `CALL_DATE` BETWEEN '$start_dt' AND '$end_dt' AND `CALL_STATUS`='Y' AND TELECALLER IN($nameStr) GROUP BY MONTH";
			elseif($role=='DIS')
				$sql_call = "SELECT count(`MATCH_ID`) AS COUNT, MONTH(DATE) AS MONTH FROM Assisted_Product.AP_AUDIT_TRAIL WHERE `DATE` BETWEEN '$start_dt' AND '$end_dt' AND `DESTINATION`='DIS' AND MOVED_BY IN($nameStr) GROUP BY MONTH";
                        elseif($role=='QA')
                                $sql_call = "SELECT count(`PROFILEID`) AS COUNT, MONTH(`SUBMIT_TIME`) AS MONTH FROM Assisted_Product.AP_QUEUE_LOG WHERE SUBMIT_TIME BETWEEN '$start_dt' AND '$end_dt' AND STATUS='DONE' AND (ASSIGNED_FOR='NQA' OR ASSIGNED_FOR='RQA') AND ASSIGNED_TO IN($nameStr) GROUP BY MONTH";

			$res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
			while($row_call = mysql_fetch_array($res_call))
			{
				$month = $row_call['MONTH'] - 1;
				$rec_count[$month] = $row_call['COUNT'];
			}

			for($i=1;$i<=12;$i++)
				$arr[] = $i;

			$smarty->assign("top_label","Month");
			$smarty->assign("head_label","Details for Year $year_m");
		}
                elseif($day_month_wise=="R")
                {
                        $start_dt = $year_r1."-".$month_r1."-".$day_r1;
                        $end_dt = $year_r2."-".$month_r2."-".$day_r2;
                        if($role=='TC')
                                $sql_call = "SELECT count(`MATCH_ID`) AS COUNT, DAYOFMONTH(`CALL_DATE`) AS DAY, MONTH(`CALL_DATE`) AS MONTH, YEAR(`CALL_DATE`) AS YEAR FROM Assisted_Product.AP_CALL_HISTORY WHERE `CALL_DATE` BETWEEN '$start_dt' AND '$end_dt' AND CALL_STATUS='Y' AND TELECALLER IN($nameStr) GROUP BY DAY";
                        elseif($role=='DIS')
                                $sql_call = "SELECT count(`MATCH_ID`) AS COUNT, DAYOFMONTH(`DATE`) AS DAY, MONTH(`DATE`) AS MONTH, YEAR(`DATE`) AS YEAR FROM Assisted_Product.AP_AUDIT_TRAIL WHERE `DATE` BETWEEN '$start_dt' AND '$end_dt' AND DESTINATION='DIS' AND MOVED_BY IN($nameStr) GROUP BY DAY";
                        elseif($role=='QA')
                                $sql_call = "SELECT count(`PROFILEID`) AS COUNT, DAYOFMONTH(`SUBMIT_TIME`) AS DAY, MONTH(`SUBMIT_TIME`) AS MONTH, YEAR(`SUBMIT_TIME`) AS YEAR FROM Assisted_Product.AP_QUEUE_LOG WHERE SUBMIT_TIME BETWEEN '$start_dt' AND '$end_dt' AND STATUS='DONE' AND (ASSIGNED_FOR='NQA' OR ASSIGNED_FOR='RQA') AND ASSIGNED_TO IN($nameStr) GROUP BY DAY";

                        $res_call = mysql_query_decide($sql_call) or die($sql_call.mysql_error_js());
                        while($row_call = mysql_fetch_array($res_call))
                        {
                                $day = $row_call['DAY'];
				$month =$row_call['MONTH'];
				$year =$row_call['YEAR'];
                                $rec_count[$year][$month][$day] = $row_call['COUNT'];
                        }

                        for($i=$year_r1;$i<=$year_r2;$i++)
                                $arr_y[] = $i;

                        for($i=$month_r1;$i<=$month_r2;$i++)
                                $arr_m[] = $i;

                        for($i=$day_r1;$i<=$day_r2;$i++)
                                $arr_d[] = $i;

			$smarty->assign("top_label","Day");
			$smarty->assign("date_range","1");
                        $smarty->assign("head_label","Details for $day_r1/$month_r1/$year_r1 to $day_r2/$month_r2/$year_r2");
                }

		$smarty->assign("arr",$arr);
		$smarty->assign("RESULT",1);
		$smarty->assign("rec_count",$rec_count);
	
		$smarty->assign("arr_y",$arr_y);	
		$smarty->assign("arr_m",$arr_m);
		$smarty->assign("arr_d",$arr_d);

	}
	else
	{
		// Condition before submit state
		
		// Manager logged in case check
		if($MGR){
			$namesArr=getMng_EmployeeNames($name,$role);
			$smarty->assign("namesArr",$namesArr);
			$smarty->assign("MGR",$MGR);
		}
		// Ends Manager logged in case

		$date = date("Y-m");
		list($curyear,$curmonth) = explode("-",$date);

                for($i=1;$i<=31;$i++)
                        $ddarr[] = $i;

		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;

		for($i=2007;$i<=$curyear+2;$i++)
			$yyarr[] = $i;

		$smarty->assign("top_label","Month");
		$smarty->assign("curyear",$curyear);
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("role",$role);
	$smarty->display("ap_mis_report_cnt.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

?>
