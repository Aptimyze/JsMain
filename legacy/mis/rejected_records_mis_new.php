<?php
include("connect.inc");
include("../billing/comfunc_sums.php");

$db = connect_misdb();
$db2 = connect_master();

$start_date = $Year."-".$Month."-01 00:00:00";
$end_date = $Year."-".$Month."-31 23:59:59";

$from = "MIS";
$reject_reason_arr = populate_reject_reason($from);

$agent_arr = populate_misc_saleby();
$smarty->assign("agent_arr",$agent_arr);

$dep_branch_arr = get_deposit_branches();
$smarty->assign("dep_branch_arr", $dep_branch_arr);

if(authenticated($cid))
{
	//when Go button is clicked from duration select page.
	if($submit)
	{
		for($i=1; $i<=31;$i++)
		{
			$ddarr[] = $i;
		}

		$MIS_FOR = $Month." / ".$Year;

		//find the user's for a particular branch.
		if($mis_type == "CITY_WISE")
			$username_str = get_users_for_branch($branch);

		//query to find count grouped on reject reason's for Confirm client part.
		$sql = "SELECT COUNT(*) AS COUNT, REJECT_REASON FROM billing.REJECTED_RECORDS rr, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = pc.ID AND pc.REQ_DT BETWEEN '$start_date' AND '$end_date' AND pc.PICKUP_TYPE='CHEQ_REQ_USER'";

		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";

		$sql .= " GROUP BY REJECT_REASON ORDER BY COUNT DESC";
		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			$cc_reject[$i]['COUNT'] = $row['COUNT'];
			//finding the postion of REJECTED VALUE in the REJECT REASON array.
			list($position,$value) = multi_array_search($row['REJECT_REASON'],$reject_reason_arr);
			$cc_reject[$i]['REASON'] = $reject_reason_arr[$position]['name'];
			$i++;
		}
		
		//query to find count grouped on reject reason's for Bank Transfer part.
		$sql = "SELECT COUNT(*) AS COUNT, REJECT_REASON FROM billing.REJECTED_RECORDS rr, billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = crd.REQUEST_ID AND crd.REQUEST_ID = pc.ID AND crd.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";

		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";

		$sql .= " GROUP BY REJECT_REASON ORDER BY COUNT DESC";

		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			$bt_reject[$i]['COUNT'] = $row['COUNT'];
			//finding the postion of REJECTED VALUE in the REJECT REASON array.
			list($position,$value) = multi_array_search($row['REJECT_REASON'],$reject_reason_arr);
			$bt_reject[$i]['REASON'] = $reject_reason_arr[$position]['name'];
			$i++;
		}
		
		//finding the count of rejected, accepted and total records for confirm client part.
		//ACCEPTED
		$sql = "SELECT COUNT( * ) AS COUNT, CONFIRM,PROFILEID,ENTRY_DT FROM incentive.PAYMENT_COLLECT WHERE CONFIRM='Y' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND PICKUP_TYPE='CHEQ_REQ_USER'";
		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND ACC_REJ_MAIL_BY IN ($username_str)";
		$sql .= " GROUP BY PROFILEID, CONFIRM";
		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
                        $pid=$row['PROFILEID'];
			$sql2 = "SELECT MAX(ENTRY_DT) AS EDATE,DAYOFMONTH(MAX(ENTRY_DT)) AS DAY FROM incentive.PAYMENT_COLLECT WHERE PROFILEID='$pid'";
                        $res2 = mysql_query_decide($sql2,$db) or die($sql2.mysql_error_js());
                        if($row2 = mysql_fetch_array($res2))
                                $edate=$row2['EDATE'];
                        if($edate==$row["ENTRY_DT"])
                        {
                        	$day = $row2['DAY']-1;
	                        if($day>0)
        	                {
					$touched_cc['ACCEPT'][$day]++;
					//row total for accepted records..
					$total_cc['ACCEPT']++;
				}
			}
		}
		//REJECTED
		$sql = "SELECT DISTINCT(pc.ID), pc.USERNAME, LEFT(pc.REQ_DT,10) AS REQ_DT, rr.REJECTED_BY, rr.REJECT_REASON, LEFT(rr.ENTRY_DT,10) AS ENTRY_DT,DAYOFMONTH(rr.ENTRY_DT) AS DAY FROM billing.REJECTED_RECORDS rr, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = pc.ID AND rr.ENTRY_DT BETWEEN '$start_date' AND '$end_date'  AND pc.PICKUP_TYPE='CHEQ_REQ_USER' ";
                //for a prticular agent
                if($mis_type == "AGENT_WISE")
                        $sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
                //for a prticular branch
                elseif($mis_type == "CITY_WISE")
                        $sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";
                $res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
                while($row = mysql_fetch_array($res))
                {
			$day=$row['DAY']-1;
			$touched_cc['REJECT'][$day]++;
	                //row total for rejected records..
                        $total_cc['REJECT']++;
			//column total.
                        $touched_cc["TOTAL"][$day] = $touched_cc['ACCEPT'][$day] + $touched_cc['REJECT'][$day];
                }
		//row total for touched records..
		$total_cc['TOUCHED'] = $total_cc['REJECT'] + $total_cc['ACCEPT'];

		//finding the count of rejected, accepted records for bank tranfer record part.
		//ACCEPTED
		$sql = "SELECT COUNT( * ) AS COUNT,crd.STATUS,pc.PROFILEID,crd.ENTRY_DT FROM billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE crd.REQUEST_ID = pc.ID AND crd.STATUS='DONE' AND crd.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";
		$sql .= " GROUP BY pc.PROFILEID";
		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
 			$pid=$row['PROFILEID'];
			$sql2 = "SELECT MAX(ENTRY_DT) AS EDATE FROM billing.CHEQUE_REQ_DETAILS WHERE PROFILEID='$pid'";
                        $res2 = mysql_query_decide($sql2,$db) or die($sql2.mysql_error_js());
                        if($row2 = mysql_fetch_array($res2))
                                $edate=$row2['EDATE'];
			$sql1 = "SELECT DAYOFMONTH(MAX(ENTRY_DT)) AS DAY FROM billing.PURCHASES WHERE PROFILEID='$pid' AND STATUS='DONE'";
	                $res1 = mysql_query_decide($sql1,$db) or die($sql1.mysql_error_js());
                       	if($row1 = mysql_fetch_array($res1))
                        	$day = $row1['DAY']-1;
                        if($edate==$row["ENTRY_DT"])
                        {
				if($day>0)
				{
					$touched_bt['ACCEPT'][$day]++;
					//row total for accepted records..
					$total_bt['ACCEPT']++;
				}
			}
		}
		//REJECTED
		$sql = "SELECT DISTINCT(crd.REQUEST_ID), crd.PROFILEID, LEFT(crd.ENTRY_DT,10) AS REQ_DT, rr.REJECTED_BY, rr.REJECT_REASON, LEFT(rr.ENTRY_DT,10) AS ENTRY_DT,DAYOFMONTH(rr.ENTRY_DT) AS DAY FROM billing.REJECTED_RECORDS rr, billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = crd.REQUEST_ID AND crd.REQUEST_ID = pc.ID AND rr.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
                //for a prticular agent
                if($mis_type == "AGENT_WISE")
                        $sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
                //for a prticular branch
                elseif($mis_type == "CITY_WISE")
                        $sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";
                $res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
			$day = $row['DAY']-1;
                        if($day>0)
                        {
				$touched_bt['REJECT'][$day]++;
				//row total for rejected records..
				$total_bt['REJECT']++;
			}
		}

		//finding the PENDING records for which mail has been sent.
		$sql = "SELECT COUNT( * ) AS COUNT,DAYOFMONTH( crd.ENTRY_DT ) AS DAY,pc.PROFILEID FROM billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE crd.REQUEST_ID = pc.ID AND crd.ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND crd.MAIL_SENT > 0";
		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";
		$sql .= " GROUP BY pc.PROFILEID";
		$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
			$day = $row['DAY'] - 1;
                        $pid=$row['PROFILEID'];
                        if($day>0)
                        {
				$touched_bt['MAIL_SENT'][$day]++;
				//row total for accepted records..
				$total_bt['MAIL_SENT']++;
			}
		}

		//row total for touched records.
		$total_bt['TOUCHED'] = $total_bt['REJECT'] + $total_bt['ACCEPT'] + $total_bt['MAIL_SENT'];

		//column total.
		for($i = 0; $i <= 31; $i++)
		{
			$day = $i;
			$touched_bt["TOTAL"][$day] = $touched_bt['ACCEPT'][$day] + $touched_bt['REJECT'][$day] + $touched_bt['MAIL_SENT'][$day];
		}

		$smarty->assign("mis_type",$mis_type);
		$smarty->assign("branch",$branch);
		$smarty->assign("agent",$agent);
		$smarty->assign("cc_reject",$cc_reject);
		$smarty->assign("bt_reject",$bt_reject);
		$smarty->assign("SUBMITTED",1);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("MIS_FOR",$MIS_FOR);
		$smarty->assign("Month",$Month);
		$smarty->assign("Year",$Year);
		$smarty->assign("touched_cc",$touched_cc);
		$smarty->assign("touched_bt",$touched_bt);
		$smarty->assign("total_cc",$total_cc);
		$smarty->assign("total_bt",$total_bt);
	}
	//when any rejected records link is clicked from Confirm client part.
	elseif($cc_link_clicked)
	{
		$req_date_start = $Year."-".$Month."-".$Day." 00:00:00";
		$req_date_end = $Year."-".$Month."-".$Day." 23:59:59";

		$username_str = get_users_for_branch($branch);

		//query to find count grouped on reject reason's
		$sql = "SELECT COUNT(*) AS COUNT, REJECT_REASON FROM billing.REJECTED_RECORDS rr, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = pc.ID AND rr.ENTRY_DT BETWEEN '$req_date_start' AND '$req_date_end' AND pc.PICKUP_TYPE='CHEQ_REQ_USER'";

		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";

		$sql .= " GROUP BY REJECT_REASON ORDER BY COUNT DESC";
		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			$reject[$i]['COUNT'] = $row['COUNT'];
			list($position,$value) = multi_array_search($row['REJECT_REASON'],$reject_reason_arr);
			$reject[$i]['REASON'] = $reject_reason_arr[$position]['name'];
			$i++;
		}

		//finding the overal details for that particular clicked day.
		$sql = "SELECT DISTINCT(pc.ID), pc.USERNAME, LEFT(pc.REQ_DT,10) AS REQ_DT, rr.REJECTED_BY, rr.REJECT_REASON, LEFT(rr.ENTRY_DT,10) AS ENTRY_DT FROM billing.REJECTED_RECORDS rr, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = pc.ID AND rr.ENTRY_DT BETWEEN '$req_date_start' AND '$req_date_end'  AND pc.PICKUP_TYPE='CHEQ_REQ_USER' ";

		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";

		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		$i = 0;
		if(mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_array($res))
			{
				$details[$i]['USERNAME'] = $row['USERNAME'];
				$details[$i]['REQ_DT'] = $row['REQ_DT'];
				$details[$i]['REJECTED_BY'] = $row['REJECTED_BY'];

				list($position,$value) = multi_array_search($row['REJECT_REASON'],$reject_reason_arr);
				$details[$i]['REJECT_REASON'] = $reject_reason_arr[$position]['name'];
				$details[$i]['REJECT_DT'] = $row['ENTRY_DT'];
				$details[$i]['REQUEST_ID'] = $row['ID'];
				$i++;
			}


			$smarty->assign("details",$details);
			$smarty->assign("reject",$reject);
		}
		else
			$smarty->assign("NO_RESULT",1);

		$smarty->assign("CC_LINK_CLICKED",1);
	}
	//when any rejected records link is clicked from Bank Transfer Record part.
	elseif($bt_link_clicked)
	{
		$entry_date_start = $Year."-".$Month."-".$Day." 00:00:00";
		$entry_date_end = $Year."-".$Month."-".$Day." 23:59:59";

		$username_str = get_users_for_branch($branch);

		//finding the overal details for that particular clicked day.
		$sql = "SELECT DISTINCT(crd.REQUEST_ID), crd.PROFILEID, LEFT(crd.ENTRY_DT,10) AS REQ_DT, rr.REJECTED_BY, rr.REJECT_REASON, LEFT(rr.ENTRY_DT,10) AS ENTRY_DT FROM billing.REJECTED_RECORDS rr, billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = crd.REQUEST_ID AND crd.REQUEST_ID = pc.ID AND rr.ENTRY_DT BETWEEN '$entry_date_start' AND '$entry_date_end'";

		//for a prticular agent
		if($mis_type == "AGENT_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
		//for a prticular branch
		elseif($mis_type == "CITY_WISE")
			$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";

		$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		$i = 0;
		if(mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_array($res))
			{
				$sql_jp = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
				$res_jp = mysql_query_decide($sql_jp,$db) or die($sql_jp.mysql_error_js());
				$row_jp = mysql_fetch_array($res_jp);

				$details[$i]['USERNAME'] = $row_jp['USERNAME'];
				$details[$i]['REQ_DT'] = $row['REQ_DT'];
				$details[$i]['REJECTED_BY'] = $row['REJECTED_BY'];

				list($position,$value) = multi_array_search($row['REJECT_REASON'],$reject_reason_arr);
				$details[$i]['REJECT_REASON'] = $reject_reason_arr[$position]['name'];
				$details[$i]['REJECT_DT'] = $row['ENTRY_DT'];
				$details[$i]['REQUEST_ID'] = $row['REQUEST_ID'];
				$i++;
			}

			//query to find count grouped on reject reason's
			$sql = "SELECT COUNT(DISTINCT(REQUEST_ID)) AS COUNT, REJECT_REASON FROM billing.REJECTED_RECORDS rr, billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE rr.ORDER_ID = crd.REQUEST_ID AND crd.REQUEST_ID = pc.ID AND rr.ENTRY_DT BETWEEN '$entry_date_start' AND '$entry_date_end'";
			//for a prticular agent
			if($mis_type == "AGENT_WISE")
				$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
			//for a prticular branch
			elseif($mis_type == "CITY_WISE")
				$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";

			$sql .= " GROUP BY REJECT_REASON ORDER BY COUNT DESC";

			$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
			$i = 0;
			while($row = mysql_fetch_array($res))
			{
				$reject[$i]['COUNT'] = $row['COUNT'];
				list($position,$value) = multi_array_search($row['REJECT_REASON'],$reject_reason_arr);
				$reject[$i]['REASON'] = $reject_reason_arr[$position]['name'];
				$i++;
			}
			$smarty->assign("details",$details);
			$smarty->assign("reject",$reject);
		}
		else
			$smarty->assign("NO_RESULT",1);

		$smarty->assign("BT_LINK_CLICKED",1);
	}
	else
	{
		$mmarr = array(
				array("NAME" => "Jan", "VALUE" => "01"),
				array("NAME" => "Feb", "VALUE" => "02"),
				array("NAME" => "Mar", "VALUE" => "03"),
				array("NAME" => "Apr", "VALUE" => "04"),
				array("NAME" => "May", "VALUE" => "05"),
				array("NAME" => "Jun", "VALUE" => "06"),
				array("NAME" => "Jul", "VALUE" => "07"),
				array("NAME" => "Aug", "VALUE" => "08"),
				array("NAME" => "Sep", "VALUE" => "09"),
				array("NAME" => "Oct", "VALUE" => "10"),
				array("NAME" => "Nov", "VALUE" => "11"),
				array("NAME" => "Dec", "VALUE" => "12"),
			);
																     
		$year = date('Y');
		for($y=2004;$y<=$year;$y++)
		{
			$yyarr[] = $y;
		}
																     
		$smarty->assign("curmonth",date('m'));
		$smarty->assign("curyear",date('Y'));
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}

	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->display("rejected_records_mis_new.htm");
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");

}

function get_users_for_branch($branch)
{
	$sql_branches = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE UPPER(CENTER)='$branch'";
	$res_branches = mysql_query_decide($sql_branches) or die($sql_branches.mysql_error_js());
	while($row_branches = mysql_fetch_array($res_branches))
		$username[] = $row_branches['USERNAME'];

	$username_str = "'".implode("','",$username)."'";

	return $username_str;
}
?>
