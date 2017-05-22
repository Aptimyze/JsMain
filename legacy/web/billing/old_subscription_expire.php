<?php
include("../jsadmin/connect.inc");
include("functions.php");
include("../crm/mainmenunew.php");

$curdate = date("Y-m-d",time());
$msg = '';


/* Subscription endup part begin here */

$sql = "update billing.SUBSCRIPTION_EXPIRE, billing.PURCHASES set SUBSCRIPTION_EXPIRE.RESUBSCRIBED = 'Y' WHERE SUBSCRIPTION_EXPIRE.PROFILEID = billing.PURCHASES.PROFILEID and SUBSCRIPTION_EXPIRE.RESUBSCRIBED <> 'Y'";
$res = mysql_query_decide($sql) or $msg .= "\n$sql \nError :".mysql_error_js();

$msg .= mysql_affected_rows_js()." member has resubscribe.\n";

$sql2 = "update newjs.JPROFILE, billing.SUBSCRIPTION_EXPIRE set newjs.JPROFILE.SUBSCRIPTION = '' where  newjs.JPROFILE.PROFILEID = billing.SUBSCRIPTION_EXPIRE.PROFILEID and billing.SUBSCRIPTION_EXPIRE.EXPIRY_DT <= '$curdate' and billing.SUBSCRIPTION_EXPIRE.RESUBSCRIBED <> 'Y'";
$res2 = mysql_query_decide($sql2) or $msg .= "\n$sql2 \nError :".mysql_error_js();

$msg .= "\nSubscription endedup for ".mysql_affected_rows_js()." member/s\n";
/* Subscription endup part end here */

/* Subscription endup mail sent part begin here */
$sql3 = "select j.PROFILEID, j.USERNAME,j.EMAIL from newjs.JPROFILE as j, billing.SUBSCRIPTION_EXPIRE s where j.PROFILEID = s.PROFILEID and s.EXPIRY_DT = '$curdate' and s.RESUBSCRIBED <> 'Y'";
$res3 = mysql_query_decide($sql3) or $msg .= "\n$sql3 \nError :".mysql_error_js();

$from_email = "webmaster@jeevansathi.com";
$subject = "Your membership expires today";

while($myrow3 = mysql_fetch_array($res3))
{
	$smarty->assign("USERNAME",$myrow3["USERNAME"]);
        $smarty->assign("EMAIL",$myrow3["EMAIL"]);
	$attachment = $smarty->fetch("subscription_expiry_reminder.htm");

	$retval = sendmail($from_email,$myrow3["EMAIL"],'','',$subject,$attachment);
	//$retval = sendmail($from_email,'alok@jeevansathi.com,devanshu@jeevansathi.com','','',$subject,$attachment);

	if($retval)
		$msg .= "\n Expiry mail can not be sent to $myrow3[EMAIL] due to $retval";
}

/* Subscription endup mail sent part end here */

/*Reminder mail to resubscribe sent 10 days before start here*/

$sql4 = "select j.PROFILEID, j.USERNAME, j.EMAIL, s.EXPIRY_DT from newjs.JPROFILE as j, billing.SUBSCRIPTION_EXPIRE s where j.PROFILEID = s.PROFILEID and s.EXPIRY_DT = DATE_ADD('$curdate', INTERVAL 10 DAY) and s.RESUBSCRIBED <> 'Y'";
$res4 = mysql_query_decide($sql4) or $msg .= "\n$sql4 \nError :".mysql_error_js();

$from_email = "webmaster@jeevansathi.com";
$subject = "Still looking for a dream partner?";

while($myrow4 = mysql_fetch_array($res4))
{
	$smarty->assign("USERNAME",$myrow4["USERNAME"]);
        $smarty->assign("EMAIL",$myrow4["EMAIL"]);

	//This function will assign all the values needed for show contacted information
	profileview($myrow4["PROFILEID"]);

	list($year,$month,$day) = explode("-",$myrow4["EXPIRY_DT"]);
        $smarty->assign("EXPIREDATE",my_format_date($day,$month,$year));

	$attachment = $smarty->fetch("subscription_renew_memberships.htm");

	$retval = sendmail($from_email,$myrow4["EMAIL"],'','',$subject,$attachment);
	//$retval = sendmail($from_email,'alok@jeevansathi.com,devanshu@jeevansathi.com','','',$subject,$attachment);

	if($retval)
		$msg .= "\n Reminder Mail for Expiry can not be sent to $myrow4[EMAIL] due to $retval";
}

/*Reminder mail to resubscribe sent 10 days before end here*/

// overall status sent 
mail("alok@jeevansathi.com","Subscription ended up mail","$msg");
?>
