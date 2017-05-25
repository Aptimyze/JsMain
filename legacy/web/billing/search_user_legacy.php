<?php
/*********************************************************************************************
* FILE NAME   : search_user.php
* DESCRIPTION : Displays the billing details for the username or billid entered by the user
* REASON             : Allow bill entry from Airex Pickup list based upon ID in 
*               incentive.PAYMENT_COLLECT table
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/


include("../jsadmin/connect.inc");

$data=authenticated($cid);

if(isset($data))
{
	$user=getuser($cid);
	$privilage=getprivilage($cid);
	
	$sql="SELECT * from billing.SUBSCRIPTION_EXPIRE where PROFILEID='$pid'";	
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		$username=$myrow['USERNAME'];
		$expiry_dt=$myrow['EXPIRY_DT'];
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("PID",$pid);
		$smarty->assign("username",$username);
		$smarty->assign("expiry_dt",$expiry_dt);
		$smarty->assign("SHOWLINK","PAID");
		$smarty->display("search_user_legacy.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("SHOWLINK","NO");
		$smarty->display("search_user_legacy.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("username","$username");
	$smarty->assign("CID",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
