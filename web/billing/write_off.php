<?php

include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

$ip=FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

$smarty->assign("DUP",stripslashes($dup));

if(authenticated($cid))
{
	if($submit)
	{
		$due_amt_new=$dueamt-$wrt_amount;
		if($due_amt < 0)
			$due_amt = 0;
		$center=getcenter_for_walkin($user);
		//update new due amount in PURCHASES table
		$sql_u="UPDATE billing.PURCHASES SET DUEAMOUNT='$due_amt_new' WHERE BILLID='$billid'";
		mysql_query_decide($sql_u) or logError_sums($sql_u,1);
		
		//new recipt for write-off
		$membershipObj = new Membership;
		$membershipObj->setBillid($billid);
		$membership_details["profileid"] = $profileid;
		$membership_details["status"] = "WRITE_OFF";
		$membership_details["mode"] = "OTHER";
		$membership_details["curtype"] = $cur_type;
		$membership_details["amount"] = $wrt_amount;
		$membership_details["reason"] = $reason;
		$membership_details["entryby"] = $user;
		$membership_details["deposit_branch"] = $center;
		$membership_details["ip"] = $ip;
		
		$membershipObj->startServiceBackend($membership_details);
		$membershipObj->generateReceipt();

		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("billid",$billid);
		$smarty->assign("flag","saved");
		$smarty->assign("phrase",$billid);
                $smarty->assign("criteria",'billid');

		$smarty->display("write_off.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("profileid",$pid);
		$smarty->assign("billid",$billid);
		$smarty->assign("cur_type",$cur_type);
		$smarty->assign("dueamt",$dueamt);
		$smarty->display("write_off.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
