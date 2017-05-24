<?php

// $noChargeLog: 	parameter set to get details of all Users whose chargelog may or maynot exist.

include("../jsadmin/connect.inc");
include("../billing/comfunc_sums.php");
include("../classes/Services.class.php");
$data = authenticated($cid);
$noChargeLog =1;
if($data)
{
	if($submit)
	{
		if(($criteria=="uname" || $criteria=="email") && $phrase!='')
		{	
			if($criteria=="uname")
				$sql="select PROFILEID,USERNAME from newjs.JPROFILE where USERNAME='$phrase' ";
			else if($criteria=="email")
				$sql="select PROFILEID,USERNAME from newjs.JPROFILE where EMAIL='$phrase' ";
			
			$res=mysql_query_decide($sql) or die(mysql_error_js().$sql);
			$row=mysql_fetch_array($res);
			$pid=$row["PROFILEID"];
			$username = $row["USERNAME"];

			if($pid)
			{
				$sql=" SELECT * from billing.CHARGE_BACK_LOG where PROFILEID ='$pid' ORDER BY ID DESC LIMIT 1";
				$res=mysql_query_decide($sql) or die(mysql_error_js().$sql);
				$row=mysql_fetch_array($res);
				if(mysql_num_rows($res)==0)
				{
					$sql_receipt ="SELECT RECEIPTID from billing.PAYMENT_DETAIL where PROFILEID='$pid' AND STATUS='DONE' order by RECEIPTID DESC LIMIT 1";
                                	$res = mysql_query_decide($sql_receipt) or die(mysql_error_js().$sql_receipt);
                                	$row_receipt = mysql_fetch_array($res);
                                	$receiptid = $row_receipt["RECEIPTID"];
                                	$row =charge_back_stats_log($pid,$receiptid,$noChargeLog);

				}
			}
		}
	
		if($row['SERVICEID'])
		{
                        $serviceid      =$row['SERVICEID'];
                        $addonId        =$row['ADDON'];
                        $serviceIdStr   ="$serviceid,$addonId";

			$serviceObj 	=new Services(); 
                        $serviceNameArr =$serviceObj->getServiceName($serviceIdStr);
                        $row["SERVICE"] =$serviceNameArr["$serviceid"]['NAME'];
                        $row["ADDON_SERVICE"] =$serviceNameArr["$addonId"]['NAME'];


			$row["CONTACTS_MADE"] = nl2br(str_replace(" ","&nbsp;",$row["CONTACTS_MADE"]));
			$row["CONTACTS_ACC"] = nl2br(str_replace(" ","&nbsp;",$row["CONTACTS_ACC"]));

			$smarty->assign('row',$row);
			$smarty->assign('username',$username);
			$smarty->assign("RESULT_FOUND",1);
		}
		else
			$smarty->assign("RESULT_FOUND",0);

		$smarty->assign("flag",1);
	}
	$smarty->assign('user',$user);
	$smarty->assign('cid',$cid);
	$smarty->assign("noChargeLog",$noChargeLog);
	$smarty->display("charge_back_stats.htm");
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
	$smarty->assign('cid',$cid);
	$smarty->assign('user',$user);
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
	
