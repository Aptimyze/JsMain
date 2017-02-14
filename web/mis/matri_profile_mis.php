<?php
/***************************************************************************************************************************
FILE NAME		: matri_profile_mis.php
DESCRIPTION		: This file shows count of matri-profiles under various STATUS.
DATE			: July 26th 2007.
CREATED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include('connect.inc');

$db = connect_misdb();
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
		if($year<="2007" && $month<="8")
		{
			$smarty->assign("NO_DATA",1);
		}
		else
		{
			//finding count of matri-profile requests
			$sql_up_req = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(ENTRY_DT) AS DAY FROM billing.PURCHASES p WHERE STATUS = 'DONE' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND (SERVICEID LIKE '%M%' OR ADDON_SERVICEID REGEXP 'M' ) GROUP BY DAY";
			$res_up_req = mysql_query_decide($sql_up_req) or die($sql_up_req.mysql_error_js());
			while($row_up_req = mysql_fetch_array($res_up_req))
			{
				$day = $row_up_req['DAY'] - 1;
				$upload_req[$day] = $row_up_req['COUNT'];
				$total["UP_REQ"] += $row_up_req['COUNT'];
			}

			//finding count of profiles developed.
			$sql_devp = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(COMPLETION_TIME) AS DAY FROM billing.MATRI_PROFILE WHERE COMPLETION_TIME BETWEEN '$start_date' AND '$end_date' AND STATUS='F' GROUP BY DAY";
			$res_devp = mysql_query_decide($sql_devp) or die($sql_devp.mysql_error_js());
			while($row_devp = mysql_fetch_array($res_devp))
			{
				$day = $row_devp['DAY'] - 1;
				$devp[$day] = $row_devp['COUNT'];
				$total["DEVP"] += $row_devp['COUNT'];
			}

			//finding count of matri-profile requests completed/closed.
			$sql_req_comp = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(COMPLETION_TIME) AS DAY FROM billing.MATRI_COMPLETED WHERE COMPLETION_TIME BETWEEN '$start_date' AND '$end_date' GROUP BY DAY";
			$res_req_comp = mysql_query_decide($sql_req_comp) or die($sql_req_comp.mysql_error_js());
			while($row_req_comp = mysql_fetch_array($res_req_comp))
			{
				$day = $row_req_comp['DAY'] - 1;
				$req_comp[$day] = $row_req_comp['COUNT'];
				$total["REQ_COMP"] += $row_req_comp['COUNT'];
			}

			//finding count of mail's sent to users.
			$sql_mail_sent = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(ENTRY_DT) AS DAY FROM billing.MATRI_CUTS WHERE UPLOADED_BY <> 'USER' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY DAY";
			$res_mail_sent = mysql_query_decide($sql_mail_sent) or die($sql_mail_sent.mysql_error_js());
			while($row_mail_sent = mysql_fetch_array($res_mail_sent))
			{
				$day = $row_mail_sent['DAY'] - 1;
				$mail_sent[$day] = $row_mail_sent['COUNT'];
				$total["MAIL_SENT"] += $row_mail_sent['COUNT'];
			}

			//finding count of response from users.
			$sql_resp = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(ENTRY_DT) AS DAY FROM billing.MATRI_CUTS WHERE UPLOADED_BY = 'USER' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY DAY";
			$res_resp = mysql_query_decide($sql_resp) or die($sql_resp.mysql_error_js());
			while($row_resp = mysql_fetch_array($res_resp))
			{
				$day = $row_resp['DAY'] - 1;
				$resp[$day] = $row_resp['COUNT'];
				$total["RESP"] += $row_resp['COUNT'];
			}


			for($i=0;$i<31;$i++)
				$devp[$i] += $req_comp[$i];

			$total["DEVP"] += $total["REQ_COMP"];

		}
		for($i=1;$i<=31;$i++)
			$ddarr[] = $i;
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("upload_req",$upload_req);
		$smarty->assign("req_comp",$req_comp);
		$smarty->assign("mail_sent",$mail_sent);
		$smarty->assign("resp",$resp);
		$smarty->assign("devp",$devp);
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
	$smarty->display("matri_profile_mis.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
