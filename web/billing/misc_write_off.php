<?php

include_once("../jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("comfunc_sums.php");

$ip=FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

if(authenticated($cid))
{
	if($submit)
	{
		//finding the previous due amount.
		$sql_old_due = "SELECT DUEAMOUNT FROM billing.REV_MASTER WHERE SALEID='$saleid'";
		$res_old_due = mysql_query_decide($sql_old_due) or logError_sums($sql_old_due,0);
		$row_old_due = mysql_fetch_array($res_old_due);
		$dueamount = $row_old_due['DUEAMOUNT'];

		$dueamount_new = $dueamount - $wrt_amount;
		if($dueamount_new < 0)
			$dueamount_new = 0;

		$center=getcenter_for_walkin($user);

		//update new due amount in REV_MASTER table
		$sql_u="UPDATE billing.REV_MASTER SET DUEAMOUNT='$dueamount_new' WHERE SALEID='$saleid'";
		mysql_query_decide($sql_u) or logError_sums($sql_u,1);
		
		//new recipt for write-off	
		$sql_i="INSERT INTO billing.REV_PAYMENT(SALEID,STATUS,MODE,TYPE,AMOUNT,REASON,ENTRY_DT,ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,SOURCE) VALUES('$saleid','WRITE_OFF','OTHER','$cur_type','$wrt_amount','".addslashes(stripslashes($reason))."',now(),'$user',now(),'$center','OTHER')";
                mysql_query_decide($sql_i) or logError_sums($sql,1);

		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("saleid",$saleid);
		$smarty->assign("flag","saved");
		$sid = "JR-".$saleid;
		$smarty->assign("phrase",$sid);
                $smarty->assign("criteria",'billid');

		$smarty->display("misc_write_off.htm");
	}
	else
	{
		$sql = "SELECT CUR_TYPE,DUEAMOUNT FROM billing.REV_MASTER WHERE SALEID = '$saleid'";
		$res = mysql_query_decide($sql) or logError_sums($sql,0);
		$row = mysql_fetch_array($res);
		
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("cur_type",$row['CUR_TYPE']);
		$smarty->assign("saleid",$saleid);
		$smarty->assign("dueamount",$row['DUEAMOUNT']);
		$smarty->display("misc_write_off.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
