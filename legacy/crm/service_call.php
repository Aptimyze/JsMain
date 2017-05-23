<?php
/***************************************************************************************************************************
Filename    : service_call.php
Description : Dispaly the links of profiles to the service executive so that it handle its day to day working.
Created By  : Vibhor Garg
Created On  : 12 May 2008
****************************************************************************************************************************/

include("connect.inc");

if(authenticated($cid))
{
        $name= getname($cid);
	$today=date("Y-m-d");

        $sql =" SELECT COUNT(*) as cnt1 from incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND HANDLED_DT='$today' ";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
	$cnt1=$myrow['cnt1'];

	$sql =" SELECT COUNT(*) as cnt2 from incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND FEEDBACK_DT='$today' ";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $cnt2=$myrow['cnt2'];

	$sql =" SELECT COUNT(*) as cnt3 from incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND RECONVINCE_DT='$today' ";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $cnt3=$myrow['cnt3'];
	
	$sql =" SELECT COUNT(*) as cntf from incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND FOLLOWUP_DT='$today' ";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $cntf=$myrow['cntf'];

	$sql =" SELECT COUNT(*) as cntp from incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND CALL_STATUS!=0 ";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $cntp=$myrow['cntp'];

	$sql =" SELECT COUNT(*) as cntn from incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND ON_TIME='N' ";
        $result= mysql_query_decide($sql) or die(mysql_error_js());
        $myrow=mysql_fetch_array($result);
        $cntn=$myrow['cntn'];
	
	$smarty->assign("cnt1",$cnt1);
        $smarty->assign("cnt2",$cnt2);
        $smarty->assign("cnt3",$cnt3);
	$smarty->assign("cntf",$cntf);
	$smarty->assign("cntp",$cntp);
	$smarty->assign("cntn",$cntn);
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
        $smarty->display("service_call.htm");
}
else
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}                                                                                                
?>
