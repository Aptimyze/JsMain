<?php

include("../jsadmin/connect.inc");
include("functions.php");
include("../crm/mainmenunew.php");
$curdate = date("Y-m-d",time());

$ts = time();
$ts -= (24*60*60) * 5;
$before_five_days = date("Y-m-d",$ts);

//finding profileid's who made request.
$sql_pc = "SELECT DISTINCT(PROFILEID) FROM incentive.PAYMENT_COLLECT WHERE STATUS='S' AND BILLING='' AND ENTRY_DT BETWEEN '$before_five_days' AND '$curdate'";
$res_pc = mysql_query_decide($sql_pc) or die("$sql_pc".mysql_error_js());
while($row_pc = mysql_fetch_array($res_pc))
	$profileid_arr[] = $row_pc['PROFILEID'];

$profileid_str = @implode(",",$profileid_arr);

if(count($profileid_arr) > 0)
{
	//finding profileid's for which billing has been done.
	$sql_purch = "SELECT DISTINCT(PROFILEID) FROM billing.PURCHASES WHERE PROFILEID IN ($profileid_str) AND STATUS='DONE' AND ENTRY_DT >= '$before_five_days'";
	$res_purch = mysql_query_decide($sql_purch) or die($sql_purch.mysql_error_js());
	while($row_purch = mysql_fetch_array($res_purch))
		$billed_pid[] = $row_purch['PROFILEID'];

	$message = "List of user's from airex:\n".str_replace(",","\n",$profileid_str);
}
else
	$message = "List of user's from airex: None";

//finding profileid's for which billing has not been done.
for($i=0;$i<count($profileid_arr);$i++)
{
	if(!in_array($profileid_arr[$i],$billed_pid))
		$to_exp_pid[] = $profileid_arr[$i];
}

$billed_pid_str = @implode(",",$billed_pid);
$to_exp_pid_str = @implode(",",$to_exp_pid);

if(count($billed_pid) > 0)
{
	//mark BILLING = Y for profiles for which billing has been done.
	$sql_upd_pc = "UPDATE incentive.PAYMENT_COLLECT SET BILLING='Y' WHERE PROFILEID IN($billed_pid_str) AND STATUS='S'";
	mysql_query_decide($sql_upd_pc) or die($sql_upd_pc.mysql_error_js());

	$message .= "\nList of user's for whom billing has been done:\n".str_replace(",","\n",$billed_pid_str);
}
else
	$message .= "\nList of user's for whom billing has been done: None";

if(count($to_exp_pid) > 0)
{
	//unset user's SUBSCRIPTION for profiles for which billing has not been done.
	$sql_upd_jp = "UPDATE newjs.JPROFILE SET SUBSCRIPTION='' WHERE PROFILEID IN ($to_exp_pid_str)";
	mysql_query_decide($sql_upd_jp) or die($sql_upd_jp.mysql_error_js());

	$message .= "\nList of user's whose subscription expired:\n".str_replace(",","\n",$to_exp_pid_str);
}
else
	$message .= "\nList of user's whose subscription expired: None";

mail("sriram.viswanathan@jeevansathi.com","Inconsistent subscription expiry mail",nl2br($message));

unset($profileid_arr);
unset($profileid_str);
unset($billed_pid);
unset($billed_pid_str);
unset($to_exp_pid);
unset($to_exp_pid_str);
?>
