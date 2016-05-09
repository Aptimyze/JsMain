<?php

/**************************************************************************************************************************
FILE            : misc_view_edit_history.php
DESCRIPTION     : This file displays the edit history for a particular saleid (if history exits).
FILES INCLUDED  : connect.inc
CREATED BY      : SRIRAM VISWANTHAN.
DATE            : 12th April 2007.
**************************************************************************************************************************/
include("../jsadmin/connect.inc");
if(authenticated($cid))
{
	$sql = "SELECT * FROM billing.REV_EDIT_DETAILS_LOG WHERE SALEID='$saleid'";
	$res = mysql_query_decide($sql) or logError_sums($sql,0);
	$i=0;
	while($row = mysql_fetch_array($res))
	{
		$arr[$i]['SALEID'] = $row['SALEID'];
		$arr[$i]['RECEIPTID'] = $row['RECEIPTID'];
		$arr[$i]['CHANGES'] = nl2br($row['CHANGES']);
		$arr[$i]['ENTRYBY'] = $row['ENTRYBY'];
		$arr[$i]['ENTRY_DT'] = $row['ENTRY_DT'];
		$i++;
	}

	$sql = "SELECT COMP_NAME FROM billing.REV_MASTER WHERE SALEID='$saleid'";
	$res = mysql_query_decide($sql) or logError_sums($sql,0);
	$row = mysql_fetch_array($res);
	$clientname = $row["COMP_NAME"];
	$smarty->assign("arr",$arr);
	$smarty->assign("clientname",$clientname);
	$smarty->display("misc_view_edit_history.htm");
}
else
{
	$smarty->display('jsconnectError.tpl');
}
?>
