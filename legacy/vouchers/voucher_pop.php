<?php
/************************************************************************************
Filename    : voucher_pop.php
Description : Display pop up on clicking logo on My Vouchers page
Created On  : 26 September 2007
Created By  : Sadaf Alam
*************************************************************************************/
include("connect.inc");
$db=connect_db();

$sql="SELECT HEADLINE,CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$row=mysql_fetch_assoc($res);
$smarty->assign("cname",$row["CLIENT_NAME"]);
$smarty->assign("headline",$row["HEADLINE"]);
$smarty->display("voucher_pop.htm");
?>
