<?php
include("connect.inc");
if(authenticated($cid))
{
	if($Submit)
	{
		$sql="UPDATE billing.PAYMENT_GATEWAY set ACTIVE='$cstatus'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());

		$msg=" $cstatus is now active PAYMENT GATEWAY<br><br>";
		$msg .= "<a href=\"mainpage.php?user=$user&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{	
		$sql="SELECT SQL_CACHE ACTIVE as STATUS from billing.PAYMENT_GATEWAY";
                $result=mysql_query_decide($sql) or die(mysql_error_js());
                $myrow1=mysql_fetch_array($result);

		$smarty->assign("STATUS",$myrow1["STATUS"]);
		$smarty->assign("cid",$cid);
		$smarty->display("manage_payment_gateway.htm");
	}
}
else
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
                                                                                                 

?>
