<?php

/**************************************************************************************************************************
FILE 		: cancel_transaction.php
DESCRIPTION	: This file sets the STATUS field to CANCEL in PURCHASE and PAYMENT_DETAIL, and also sets the SUBSCRIPTION
		: field to blank in JPROFILE TABLE.
FILES INCLUDED	: connect.inc
CREATED BY	: SRIRAM VISWANTHAN.
DATE		: 18th October 2006.
**************************************************************************************************************************/

include('../jsadmin/connect.inc');
include('comfunc_sums.php');

if(authenticated($cid))
{
	maStripVARS_sums('stripslashes');
	$smarty->assign("USER",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("saleid",$saleid);
	if($submit)
	{
		$is_error=0;
		if($reason=='')
		{
			$is_error++;
			$smarty->assign("CHECK_REASON","Y");
		}
		if($is_error=="0")
		{
			$entryby = getuser($cid);

			$changes = "TRANSACTION CANCELLED \n";
                        $changes .= "REASON :- ".$reason;
                        $changes = addslashes(stripslashes($changes));

			//find the last receipt id for the particular saleid
			$sql_rect_id = "SELECT RECEIPTID FROM billing.REV_PAYMENT WHERE SALEID='$saleid' ORDER BY RECEIPTID DESC LIMIT 1";
			$res_rect_id = mysql_query_decide($sql_rect_id) or logError_sums($sql_rect_id,0);
			$row_rect_id = mysql_fetch_array($res_rect_id);

			//log these cancel details.
			$sql_log = "INSERT INTO billing.REV_EDIT_DETAILS_LOG(SALEID,RECEIPTID,CHANGES,ENTRYBY,ENTRY_DT) VALUES('$saleid','$row_rect_id[RECEIPTID]','$changes','$entryby',now())";
			mysql_query_decide($sql_log) or logError_sums($sql_log);

			//update STATUS=CANCEL in REV_MASTER.
			$sql_upd_rm = "UPDATE billing.REV_MASTER SET STATUS='CANCEL' WHERE SALEID='$saleid'";
			mysql_query_decide($sql_upd_rm) or logError_sums($sql_upd_rm,1);

			//update STATUS=CANCEL in REV_PAYMENT
			$sql_upd_rp = "UPDATE billing.REV_PAYMENT SET STATUS='CANCEL' WHERE SALEID='$saleid'";
			mysql_query_decide($sql_upd_rp) or logError_sums($sql_upd_rp,1);

			$smarty->assign("flag","1");
			$phrase = "JR-".$saleid;
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria","billid");
			$smarty->display("misc_cancel_transaction.htm");
		}
		else
		{
			$smarty->display("misc_cancel_transaction.htm");
		}
	}
	else
	{
		$smarty->display("misc_cancel_transaction.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
