<?php
include("connect.inc");
include("../billing/comfunc_sums.php");

$db = connect_misdb();
$db2 = connect_master();

$req_date_start = $Year."-".$Month."-".$Day." 00:00:00";
$req_date_end = $Year."-".$Month."-".$Day." 23:59:59";
$Month1=$Month-1;
$Month2=$Month+1;
$start_date = $Year."-".$Month1."-01 00:00:00";
$end_date = $Year."-".$Month2."-31 23:59:59";
$i=0;
//find the user's for a particular branch.
if($mis_type == "CITY_WISE")
	$username_str = get_users_for_branch($branch);

if($cc)
{
	//finding the count of rejected, accepted and total records for confirm client part.
	$sql = "SELECT ACC_REJ_MAIL_BY,PROFILEID,ENTRY_DT FROM incentive.PAYMENT_COLLECT WHERE CONFIRM ='Y' AND ENTRY_DT BETWEEN '$req_date_start' AND '$req_date_end' AND PICKUP_TYPE='CHEQ_REQ_USER' ORDER BY REQ_DT DESC";

	//for a prticular agent
	if($mis_type == "AGENT_WISE")
		$sql .= " AND ACC_REJ_MAIL_BY = '$agent'";
	//for a prticular branch
	elseif($mis_type == "CITY_WISE")
		$sql .= " AND ACC_REJ_MAIL_BY IN ($username_str)";

	$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		if(!@in_array($row['PROFILEID'], $pro_arr) && $row['ACC_REJ_MAIL_BY']!='')
                {
                        $pro_arr[] = $row['PROFILEID'];
			$pid=$row["PROFILEID"];
			$sql2 = "SELECT MAX(ENTRY_DT) AS EDATE FROM incentive.PAYMENT_COLLECT WHERE PROFILEID='$pid'";
                        $res2 = mysql_query_decide($sql2,$db) or die($sql2.mysql_error_js());
                        if($row2 = mysql_fetch_array($res2))
                                $edate=$row2['EDATE'];
                        if($edate==$row["ENTRY_DT"])
                        {
		        	$sql1 = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	        		$res1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			        if($row1 = mysql_fetch_array($res1))
        			        $username=$row1["USERNAME"];
				$j=0;
				$user_arr[$i][$j]=$username;
				$j=$j+1;
				$user_arr[$i][$j]=$row["ACC_REJ_MAIL_BY"];
				$i=$i+1;
			}
		}
	}
}
elseif($bt)
{
	$sql1 = "SELECT DISTINCT(PROFILEID) FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$req_date_start' AND '$req_date_end' AND STATUS='DONE'";
	$res1 = mysql_query_decide($sql1,$db) or die($sql1.mysql_error_js());
	while($row1 = mysql_fetch_array($res1))
		$pid_arr[] = $row1['PROFILEID'];
	$pid_str=implode(",",$pid_arr);
	//finding the count of rejected, accepted records for bank tranfer record part.
	$sql = "SELECT ACC_REJ_MAIL_BY,pc.PROFILEID,crd.ENTRY_DT FROM billing.CHEQUE_REQ_DETAILS crd, incentive.PAYMENT_COLLECT pc WHERE crd.REQUEST_ID = pc.ID AND crd.STATUS='DONE' AND crd.PROFILEID IN ($pid_str) AND crd.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";

	//for a prticular agent
	if($mis_type == "AGENT_WISE")
		$sql .= " AND pc.ACC_REJ_MAIL_BY = '$agent'";
	//for a prticular branch
	elseif($mis_type == "CITY_WISE")
		$sql .= " AND pc.ACC_REJ_MAIL_BY IN ($username_str)";
	$sql .= " ORDER BY REQ_DT DESC";
	$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		if(!@in_array($row['PROFILEID'], $pro_arr) && $row['ACC_REJ_MAIL_BY']!='')
                {
			$pid=$row["PROFILEID"];
			$pro_arr[] = $row['PROFILEID'];
			$sql2 = "SELECT MAX(ENTRY_DT) AS EDATE FROM billing.CHEQUE_REQ_DETAILS WHERE PROFILEID='$pid'";
			$res2 = mysql_query_decide($sql2,$db) or die($sql2.mysql_error_js());
		        if($row2 = mysql_fetch_array($res2))
				$edate=$row2['EDATE'];
			if($edate==$row["ENTRY_DT"])
			{
				$sql1 = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
				$res1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
				if($row1 = mysql_fetch_array($res1))
					$username=$row1["USERNAME"];
				$j=0;
				$user_arr[$i][$j]=$username;
				$j=$j+1;
				$user_arr[$i][$j]=$row["ACC_REJ_MAIL_BY"];
				$i=$i+1;
			}
		}
	}
}
$smarty->assign("mis_type",$mis_type);
$smarty->assign("branch",$branch);
$smarty->assign("agent",$agent);
$smarty->assign("Month",$Month);
$smarty->assign("Year",$Year);
$smarty->assign("cid",$cid);
$smarty->assign("user",$user);
$smarty->assign("user_arr",$user_arr);
$smarty->display("accept_records.htm");

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
