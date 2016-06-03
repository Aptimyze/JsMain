<?php
/*************************************************************************************************************************
FILE NAME 	: telec_voice_log.php
DESCRIPTION	: This script finds and displays all the users id's who became paid member in the selected month,
		: along with their calling history.
CREATED BY	: Sriram Viswanathan.
DATE		: 3rd April 2007.
*************************************************************************************************************************/
include("connect.inc");

$data = authenticated($cid);

if($data)
{
	if($submit)
	{
		$start_dt = $from_year."-".$from_month."-".$from_date." 00:00:00";
		$end_dt = $to_year."-".$to_month."-".$to_date." 23:59:59";
		$i=0;

		$sql_crm = "SELECT * FROM incentive.CRM_VOICE_LOG WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt'";
		$res_crm = mysql_query_decide($sql_crm) or die($sql_crm.mysql_query_decide());
		while($row_crm = mysql_fetch_array($res_crm))
		{
			$details[$i]["SNO"] = $i+1;
			$details[$i]["PROFILEID"] = $row_crm["PROFILEID"];
			$details[$i]["USERNAME"] = $row_crm["USERNAME"];
			$details[$i]["PHONE"] = $row_crm["PHONE"];
			$details[$i]["ALLOTED_TO"] = $row_crm["ALLOTED_TO"];
			$details[$i]["COUNT"] = $row_crm["COUNT"];
			$details[$i]["ENTRY_DT"] = $row_crm["ENTRY_DT"];
			$details[$i]["ALLOT_TIME"] = $row_crm["ALLOT_TIME"];
			$i++;
		}

		$smarty->assign("FLAG","VIEW_VOICE_LOG");
		$smarty->assign("details",$details);
	}
	//if a particular usernid is clicked, then show his history
	elseif($view_call_history)
	{
		//query to find user's history.
		$sql_hist = "SELECT * FROM incentive.HISTORY WHERE PROFILEID='$pid' AND ENTRY_DT BETWEEN '$allot_time' AND '$entry_dt' ORDER BY ENTRY_DT DESC";
		$res_hist = mysql_query_decide($sql_hist) or die($sql_hist.mysql_error_js());
		$i = 0;
		while($row_hist = mysql_fetch_array($res_hist))
		{
			$history_details[$i]["SNO"] = $i+1;
			$history_details[$i]["USERNAME"] = $row_hist["USERNAME"];
			$history_details[$i]["ENTRYBY"] = $row_hist["ENTRYBY"];
			$history_details[$i]["COMMENT"] = $row_hist["COMMENT"];
			$history_details[$i]["ENTRY_DT"] = $row_hist["ENTRY_DT"];
			$i++;
		}
		$smarty->assign("FLAG","VIEW_HISTORY");
		$smarty->assign("history_details",$history_details);
	}
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2006;
		}

		list($cur_date,$cur_month,$cur_year) = explode("-",date("d-m-Y"));
		$smarty->assign("cur_date",$cur_date);
		$smarty->assign("cur_month",$cur_month);
		$smarty->assign("cur_year",$cur_year);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}

	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->display("telec_voice_log.htm");
}
else//user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
