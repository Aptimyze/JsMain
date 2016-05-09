<?php

/**************************************************************************************************************************
FILE            : view_edit_history.php
DESCRIPTION     : This file displays the edit history for a particular billid (if history exits).
FILES INCLUDED  : connect.inc
CREATED BY      : SRIRAM VISWANTHAN.
DATE            : 18th October 2006.
**************************************************************************************************************************/

include("../jsadmin/connect.inc");
if(authenticated($cid))
{
	$sql = "SELECT * FROM billing.EDIT_DETAILS_LOG WHERE BILLID='$billid'";
	$res = mysql_query_decide($sql) or logError_sums($sql,0);
	$i=0;
	while($row = mysql_fetch_array($res))
	{
		$arr[$i]['BILLID'] = $row['BILLID'];
		$arr[$i]['RECEIPTID'] = $row['RECEIPTID'];
		$arr[$i]['CHANGES'] = nl2br($row['CHANGES']);
		$arr[$i]['ENTRYBY'] = $row['ENTRYBY'];
		$arr[$i]['ENTRY_DT'] = $row['ENTRY_DT'];
		$i++;
	}
	$smarty->assign("arr",$arr);
	$smarty->assign("username",$username);
	$smarty->display("view_edit_history.htm");
}
else
{
	$smarty->display('jsconnectError.tpl');
}
?>
