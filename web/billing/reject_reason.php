<?php
/**************************************************************************************************************************
FILE		: reject_reason.php
DESCRIPTION	: This script is used to store the reason of rejection from Bank Transfer and Confirm Client modules.
CREATED BY	: Sriram Viswanathan.
DATE		: 18th April 2007
**************************************************************************************************************************/
include("../jsadmin/connect.inc");
include("comfunc_sums.php");

if(authenticated($cid))
{
	//populate reject reason.
	$reject_reason_arr = populate_reject_reason($from);
	$smarty->assign("reject_reason_arr",$reject_reason_arr);

	$entry_by = getname($cid);

	if($submit)
	{
		if(trim($reject_reason) == "")
		{
			$smarty->assign("CHECK_REASON",1);
			$smarty->assign("cid",$cid);
			$smarty->assign("order_id",$order_id);
			$smarty->assign("from",$from);
		}
		else
		{
			//function called to store details.
			reject_reason($order_id,$entry_by,$reject_reason,$from);
			$smarty->assign("successfully_rejected",1);
		}
		$smarty->display("reject_reason.htm");
	}
	else
	{
		$smarty->assign("cid",$cid);
		$smarty->assign("order_id",$order_id);
		$smarty->assign("from",$from);
		$smarty->display("reject_reason.htm");
	}
}
else
{
        $msg="Your session is timed out";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("billing_msg.tpl");
                                                                                                                             
}
?>
