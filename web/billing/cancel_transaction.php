<?php

/**************************************************************************************************************************
FILE 		: cancel_transaction.php
DESCRIPTION	: This file sets the STATUS field to CANCEL in PURCHASE and PAYMENT_DETAIL, and also sets the SUBSCRIPTION
		: field to blank in JPROFILE TABLE.
FILES INCLUDED	: connect.inc
CREATED BY	: SRIRAM VISWANTHAN.
DATE		: 18th October 2006.
**************************************************************************************************************************/

include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/billing/comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Membership.class.php");
if(authenticated($cid))
{
	maStripVARS_sums('stripslashes');
	$smarty->assign("cid",$cid);
	$smarty->assign("phrase",$phrase);
	$smarty->assign("criteria",$criteria);
	$smarty->assign("billid",$billid);
	$smarty->assign("uname",$uname);
	if($submit)
	{
		$membershipObj = new Membership;
		$is_error=0;
		if($reason=='')
		{
			$is_error++;
			$smarty->assign("CHECK_REASON","Y");
		}
		if($is_error=="0")
		{
			$entryby = getuser($cid);
			$sql_det = "SELECT PROFILEID,BILLID, RECEIPTID FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid' ORDER BY RECEIPTID DESC LIMIT 1";
			$res_det = mysql_query_decide($sql_det) or logError_sums($sql_det);
			$row_det = mysql_fetch_array($res_det);
			$profileid = $row_det["PROFILEID"];
			$billid = $row_det["BILLID"];
			$receiptid = $row_det["RECEIPTID"];
            
			$changes = "TRANSACTION CANCELLED \n";
			$changes .= "REASON :- ".$reason;
			$changes = addslashes(stripslashes($changes));

			//lock the edited details.
			$sql_log = "INSERT INTO billing.EDIT_DETAILS_LOG(PROFILEID, BILLID, RECEIPTID, CHANGES, ENTRYBY, ENTRY_DT) VALUES('$profileid','$billid','$receiptid','$changes','$entryby',now())";
			mysql_query_decide($sql_log) or logError_sums($sql_log,1);

			//passing billid, the modified string and "C" indicating that the transaction has been cancelled
			change_notify_mail($billid, $changes,"C");
            
            //Get the Receiptids of STATUS 'DONE' which are going to be marked CANCEL for negative entry
            $memHandlerObject = new MembershipHandler();
            $receiptidArr = $memHandlerObject->getReceiptids($billid);

			//set STATUS=CANCEL in PURCHASES table.
			$reason = addslashes(stripslashes($reason));
			$sql = "UPDATE billing.PURCHASES SET STATUS='CANCEL', COMMENT='$reason' WHERE BILLID = '$billid'";
			mysql_query_decide($sql) or logError_sums($sql,1);

			//set STATUS=CANCEL in PAYMENT_DETAIL table.
			$sql = "UPDATE billing.PAYMENT_DETAIL SET STATUS='CANCEL' WHERE BILLID = '$billid'";
			mysql_query_decide($sql) or logError_sums($sql,1);

			$sql = "UPDATE billing.PURCHASE_DETAIL_NEW SET STATUS='CANCEL' WHERE BILLID = '$billid'";
			mysql_query_decide($sql) or logError_sums($sql,1);
                        $membershipObj->stop_service($billid,$profileid);

			$sql_exp="DELETE from newjs.CONTACTS_STATUS where PROFILEID='$row_det[PROFILEID]'"; 
                        mysql_query_decide($sql_exp) or logError_sums($sql_exp);
            
            // CLEAR MEMCACHE FOR CURRENT USER
	    	$memCacheObject = JsMemcache::getInstance();
	    	if($memCacheObject){
		        $memCacheObject->remove($row_det['PROFILEID'] . '_MEM_NAME');
		        $memCacheObject->remove($row_det['PROFILEID'] . "_MEM_OCB_MESSAGE_API17");
		        $memCacheObject->remove($row_det['PROFILEID'] . "_MEM_HAMB_MESSAGE");
		        $memCacheObject->remove($row_det['PROFILEID'] . "_MEM_SUBSTATUS_ARRAY");
		    }
            
            
            //**START - Entry for negative transactions
            $memHandlerObject = new MembershipHandler();
            $memHandlerObject->handleNegativeTransaction($receiptidArr,'CANCEL');
            unset($memHandlerObject);
            //**END - Entry for negative transactions
            
			$smarty->assign("flag","1");
			$smarty->display("cancel_transaction.htm");
		}
		else
		{
			$smarty->assign("cid",$cid);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);
			$smarty->assign("uname",$uname);
			$smarty->display("cancel_transaction.htm");
		}
	}
	else
	{
		$smarty->display("cancel_transaction.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
