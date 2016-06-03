<?php
include("../jsadmin/connect.inc");
//mysql_close();
connect_slave();

$curdate = date("Y-m-d",time());
$msg = '';

$sql = "Truncate table billing.UNAUTHORISED_MEMBERS";
$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());

$sql = "insert into billing.UNAUTHORISED_MEMBERS( PROFILEID, USERNAME, SUBSCRIPTION, ENTRY_DT, MOD_DT, LAST_LOGIN_DT) select  PROFILEID, USERNAME, SUBSCRIPTION, ENTRY_DT, MOD_DT, LAST_LOGIN_DT from newjs.JPROFILE where SUBSCRIPTION  <> '' and ACTIVATED <> 'D'";
$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());

$msg .= mysql_affected_rows_js()." member has subscribed.\n";

$sql2 = "update billing.UNAUTHORISED_MEMBERS, billing.PURCHASES set billing.UNAUTHORISED_MEMBERS.SUBSCRIBED = 'Y' where billing.UNAUTHORISED_MEMBERS.PROFILEID = billing.PURCHASES.PROFILEID";
$res2 = mysql_query_decide($sql2) or die("$sql2".mysql_error_js());

$msg .= "Subscription found for ".mysql_affected_rows_js()." member's in purchases\n";

$sql2 = "update billing.UNAUTHORISED_MEMBERS, billing.SUBSCRIPTION_EXPIRE set billing.UNAUTHORISED_MEMBERS.SUBSCRIBED = 'O' where billing.UNAUTHORISED_MEMBERS.PROFILEID = billing.SUBSCRIPTION_EXPIRE.PROFILEID and billing.UNAUTHORISED_MEMBERS.SUBSCRIBED = ''";
$res2 = mysql_query_decide($sql2) or die("$sql2".mysql_error_js());

$msg .= "Subscription found for ".mysql_affected_rows_js()." member's in old list\n";

$sql2 = "update billing.UNAUTHORISED_MEMBERS, incentive.PAYMENT_COLLECT set billing.UNAUTHORISED_MEMBERS.SUBSCRIBED = 'A' where billing.UNAUTHORISED_MEMBERS.PROFILEID = incentive.PAYMENT_COLLECT.PROFILEID and incentive.PAYMENT_COLLECT.STATUS = 'S' and billing.UNAUTHORISED_MEMBERS.SUBSCRIBED = ''";
$res2 = mysql_query_decide($sql2) or die("$sql2".mysql_error_js());

$msg .= "Subscription found for ".mysql_affected_rows_js()." member's in ARAMEX list\n";

$sql3 = "Select count(*) as NUM from billing.UNAUTHORISED_MEMBERS where billing.UNAUTHORISED_MEMBERS.SUBSCRIBED <> 'Y'";
$res3 = mysql_query_decide($sql3);
$myrow3 = mysql_fetch_array($res3);

$msg .= "Total Unauthorized member found is : ".$myrow3["NUM"];
mail("shiv.gautam@jeevansathi.com,sngautam@gmail.com,alok@jeevansathi.com","Unauthorised members","$msg");

//$msg1=profileview($profileid,$checksum);

?>
