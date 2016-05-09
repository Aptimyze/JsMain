<?php
/***************************************************************************************************************************
FILE NAME		: pull_back_reallot.php
DESCRIPTION		: This file is used to re-allot a profile, already assigned to some other executive.
DATE			: July 12th 2007.
CREATED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("time.php");
if(authenticated($cid))
{
	if($submit)
	{
		//if allot button is clicked.
		if($allot)
		{
			for($i=0;$i<count($allot);$i++)
			{
				//taking log of details before re-alloting.
				$sql_ins = "INSERT INTO billing.MATRI_PROFILE_LOG (PROFILEID, USERNAME, ALLOTTED_TO, ALLOT_TIME, COMPLETION_TIME, ENTRY_DT, VERIFIED_BY, STATUS, RE_ALLOTTED_TO, RE_ALLOT_TIME, RE_ENTRY_DT) SELECT PROFILEID, USERNAME, ALLOTTED_TO, ALLOT_TIME, COMPLETION_TIME, ENTRY_DT, VERIFIED_BY, STATUS, '$executive', now(), now() FROM billing.MATRI_PROFILE WHERE PROFILEID='$allot[$i]'";
				mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());
			}
			$profileid_str = implode("','",$allot);
			$sql_upd = "UPDATE billing.MATRI_PROFILE mp, billing.PURCHASES p SET mp.ALLOTTED_TO='$executive', mp.ALLOT_TIME=now() WHERE p.PROFILEID = mp.PROFILEID AND p.ENTRY_DT <= mp.ENTRY_DT AND mp.PROFILEID IN ('$profileid_str')";
			mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());

			$smarty->assign("executive", $executive);
			$smarty->assign("RESULT", 1);
		}
	}
	else
	{
		$k=0;
		//finding details of profiles allotted to a particular executive.
		$sql_allotted = "SELECT p.USERNAME, m.PROFILEID, m.ENTRY_DT FROM billing.MATRI_PURCHASES m join billing.PURCHASES p on p.BILLID=m.BILLID join billing.MATRI_PROFILE mp on mp.PROFILEID = m.PROFILEID where p.STATUS='DONE' AND  m.ENTRY_DT <= mp.ENTRY_DT AND mp.ALLOTTED_TO='$allotted_to'";
		$res_allotted = mysql_query_decide("$sql_allotted".mysql_error_js());
		while($row_allotted = mysql_fetch_array($res_allotted))
		{
			$reallot[$k]['SNO']=$k+1;
			$reallot[$k]['PROFILEID']=$row_allotted['PROFILEID'];
			$reallot[$k]['USERNAME']=$row_allotted['USERNAME'];
			$reallot[$k]['ENTRY_DT']=$row_allotted['ENTRY_DT'];
			$reallot[$k]['SCHEDULED_TIME']=newtime($row_allotted['ENTRY_DT'],4,0,0);
			$k++;
		}

		//finding executives who have matri-profile privilage.
		$sql_priv = "SELECT DISTINCT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE REGEXP 'MPU' AND USERNAME <> '$allotted_to'";
		$res_priv = mysql_query_decide($sql_priv) or die("$sql_priv".mysql_error_js());
		while($row_priv = mysql_fetch_array($res_priv))
			$exec[] = $row_priv['USERNAME'];

		$smarty->assign("exec",$exec);
		$smarty->assign("allotted_to",$allotted_to);
		$smarty->assign("reallot",$reallot);
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("checksum",$cid);
	$smarty->display("pull_back_reallot.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
