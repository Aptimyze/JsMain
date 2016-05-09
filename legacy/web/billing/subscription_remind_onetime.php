<?php
include "../jsadmin/connect.inc";
include "functions.php";
include("../crm/mainmenunew.php");

$msg="";

$from_email = "webmaster@jeevansathi.com";
$subject2 = "Still looking for a dream partner?";

$sql="SELECT s.PROFILEID, max(s.EXPIRY_DT) AS EXPIRY_DT, n.USERNAME, n.EMAIL FROM billing.SERVICE_STATUS s, newjs.JPROFILE n WHERE s.PROFILEID = n.PROFILEID GROUP BY s.PROFILEID having max(s.EXPIRY_DT) >= '2005-03-11' and max(s.EXPIRY_DT) <= '2005-03-19'"; 
$result=mysql_query_decide($sql) or  $msg .= "\n$sql \nError :".mysql_error_js();

while($myrow=mysql_fetch_array($result))
{
	$smarty->assign("USERNAME",$myrow["USERNAME"]);
	$smarty->assign("EMAIL",$myrow["EMAIL"]);
	profileview($myrow["PROFILEID"]);

//	list($year,$month,$day) = explode("-",$myrow["EXPIRY_DT"]);
//	$smarty->assign("EXPIREDATE",my_format_date($day,$month,$year));

	$attachment = $smarty->fetch("subscription_renew_memberships.htm");

	$retval = sendmail($from_email,$myrow["EMAIL"],'','kush.asthana@jeevansathi.com',$subject2,$attachment);

	if($retval)
		$msg .= "\n Reminder Mail for Expiry can not be sent to $myrow[EMAIL] due to $retval";
}
mail("kush.asthana@jeevansathi.com","Subscription ended up mail","$msg");
?>
