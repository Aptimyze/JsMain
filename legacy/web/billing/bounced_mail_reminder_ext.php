<?php
include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("bounced_mail.php");
$data=authenticated($cid);
$flag=0;

if(isset($data))
{
	if($CMDSubmit)
	{
		$iserror = 0;
		$user=getname($cid);
		if($action == 'D')
		if (trim($reminder_dt) == "" || ($reminder_dt ==0) || !(ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $reminder_dt)))
                {
                	$iserror++;
                        $dt_clr="red";
                        $smarty->assign("dt_clr",$dt_clr);
		}
		if (trim($del_reason) == "")
		{
			$iserror++;
                        $reason_clr="red";
                        $smarty->assign("reason_clr",$reason_clr);
		}
		if ($iserror > 0)
		{
			$sql="SELECT AMOUNT,CD_NUM,CD_DT,CD_CITY,BANK,REASON FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
	                $res=mysql_query_decide($sql) or die(mysql_error_js());
                	if($row=mysql_fetch_array($res))
                	{
                        	$amt=$row['AMOUNT'];
                        	$cd_num=$row['CD_NUM'];
                        	$cd_dt=$row['CD_DT'];
                        	$cd_city=$row['CD_CITY'];
                        	$bank=$row['BANK'];
                        	$reason=$row['REASON'];
			}
			$i = 0;
			$sql = "SELECT BILLID , ACTION , DEL_REASON , REMINDER_DT ,  ENTRYBY , ENTRY_DT FROM billing.BOUNCED_CHEQUE_LOG  WHERE RECEIPTID='$receiptid'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
                	while($row1=mysql_fetch_array($res))
			{
				$userinfo[$i]["receiptid"] = $receiptid;
				$userinfo[$i]["billid"] = $row1['BILLID'];
				if ($row1['ACTION'] == 'D')
					$userinfo[$i]["action"] = 'Second Reminder Mail Delayed';
				elseif ($row1['ACTION'] == 'R')
					$userinfo[$i]["action"] = 'Username removed from list';
				$userinfo[$i]["reminder_dt"] = $row1['REMINDER_DT'];
				$userinfo[$i]["del_reason"] = $row1['DEL_REASON'];
				$userinfo[$i]["entryby"] = $row1['ENTRYBY'];
				$userinfo[$i]["entry_dt"] = $row1['ENTRY_DT'];
				$i++;
			}
			if (count($userinfo) == 0)
                	{
                        	$norecord = "No Records To Show";
                	}
               	 	$smarty->assign("norecord",$norecord);

			$smarty->assign("userinfo",$userinfo);

			$smarty->assign("username",$username);
			$smarty->assign("amt",$amt);
			$smarty->assign("flag",$flag);
			$smarty->assign("cd_num",$cd_num);
			$smarty->assign("cd_dt",$cd_dt);
			$smarty->assign("cd_city",$cd_city);
			$smarty->assign("bank",$bank);
			$smarty->assign("reason",$reason);
			$smarty->assign("receiptid",$receiptid);
			$smarty->assign("user",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);

			$smarty->assign("action",$action);
			$smarty->assign("reminder_dt",$reminder_dt);
			$smarty->assign("profileid",$profileid);
			$smarty->assign("del_reason",$del_reason);
			$smarty->display("bounced_mail_reminder_ext.htm");
		}
		else
		{
			if ($action == 'R')
			{
				$show ='N';
			}
			elseif ($action == 'D')
			{
				$show = 'Y';
			}
			$sql = "INSERT INTO billing.BOUNCED_CHEQUE_LOG (RECEIPTID,PROFILEID , BILLID,ACTION,DEL_REASON,REMINDER_DT,ENTRYBY,ENTRY_DT,DISPLAY) VALUES('$receiptid','$profileid','$billid','$action','$del_reason','$reminder_dt','$user',NOW(),'$show')";
                        $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$sql = "UPDATE billing.BOUNCED_CHEQUE_HISTORY SET ACTION ='$action',DEL_REASON ='$del_reason'";
			if ($action == 'D')
				$sql.=" , REMINDER_DT = '$reminder_dt' ";
			$sql.=" , ENTRYBY = '$user' , ENTRY_DT = NOW(), DISPLAY  ='$show'  WHERE RECEIPTID = '$receiptid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			if (mysql_affected_rows_js() == 0)
			{
				$sql = "INSERT INTO BOUNCED_CHEQUE_HISTORY VALUES('','$receiptid','$profileid','$billid','$bounce_dt','$action','$del_reason','$reminder_dt','$user','NOW()',$show)";
				$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}
			$msg .= "<br><br><a href=\"javascript:window.close()\">Continue &gt;&gt;</a>";
			//$msg .= "<br><br><a href=\"bounced_cheque_mis.php?user=$user&cid=$cid\">Continue &gt;&gt;</a>";
                        $smarty->assign("cid",$cid);
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$sql="SELECT AMOUNT,CD_NUM,CD_DT,CD_CITY,BANK,REASON FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$amt=$row['AMOUNT'];
			$cd_num=$row['CD_NUM'];
			$cd_dt=$row['CD_DT'];
			$cd_city=$row['CD_CITY'];
			$bank=$row['BANK'];
			$reason=$row['REASON'];
		}
		$i = 0;
		$sql = "SELECT BILLID , ACTION , DEL_REASON , REMINDER_DT ,  ENTRYBY , ENTRY_DT FROM billing.BOUNCED_CHEQUE_LOG  WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row1=mysql_fetch_array($res))
                {
			$userinfo[$i]["receiptid"] = $receiptid;
			$userinfo[$i]["billid"] = $row1['BILLID'];
			if ($row1['ACTION'] == 'D')
				$userinfo[$i]["action"] = 'Second Reminder Mail Delayed';
			elseif ($row1['ACTION'] == 'R')
				$userinfo[$i]["action"] = 'Username removed from list';
			$userinfo[$i]["reminder_dt"] = $row1['REMINDER_DT'];
			$userinfo[$i]["del_reason"] = $row1['DEL_REASON'];
			$userinfo[$i]["entryby"] = $row1['ENTRYBY'];
			$userinfo[$i]["entry_dt"] = $row1['ENTRY_DT'];
			$i++;
		}
		$username = $uname;
		if (count($userinfo) == 0)
		{
			$norecord = "No Records To Show";
		}
		$smarty->assign("norecord",$norecord);
		$smarty->assign("profileid",$profileid);
		$smarty->assign("userinfo",$userinfo);
		$smarty->assign("username",$username);
		$smarty->assign("amt",$amt);
		$smarty->assign("flag",$flag);
		$smarty->assign("cd_num",$cd_num);
		$smarty->assign("cd_dt",$cd_dt);
		$smarty->assign("cd_city",$cd_city);
		$smarty->assign("bank",$bank);
		$smarty->assign("reason",$reason);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);

		$smarty->display("bounced_mail_reminder_ext.htm");
	}
}
else
{
        $smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->display("jsconnectError.tpl");
}
?>
