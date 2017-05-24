<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc");

	$ts = time();
	$ts -= 24*60*60*4;
	$four_days_before = date("Y-m-d",$ts)." 00:00:00";

	$ts = time();
	$today = date("Y-m-d",$ts)." 23:59:59";
	$ts -= 24*60*60;
	$yest_day = date("Y-m-d", $ts)." 23:59:59";

	$msg = "";

	$sql_upd = "UPDATE newjs.DISCOUNT_CODE SET USED='N', USED_BY='', USED_DT='' WHERE PAYMENT_SUCCESSFUL = 'N' AND USED='Y' AND USED_DT BETWEEN '$four_days_before' AND '$yest_day'";
	mysql_query($sql_upd) or mail_me($sql_upd);

	$msg .= @mysql_affected_rows()." discount code(s) marked as unused.\n";

	$sql_upd = "UPDATE newjs.DISCOUNT_CODE_MULTIPLE SET ACTIVE='Y' WHERE START_DATE BETWEEN '$four_days_before' AND '$today' AND ACTIVE='N'";
	mysql_query($sql_upd) or mail_me($sql_upd);

	$msg .= @mysql_affected_rows()." reusable discount code(s) activated.\n";

	$sql_upd = "UPDATE newjs.DISCOUNT_CODE_MULTIPLE SET ACTIVE='N' WHERE END_DATE BETWEEN '$four_days_before' AND '$today' AND ACTIVE='Y'";
	mysql_query($sql_upd) or mail_me($sql_upd);

	$msg .= @mysql_affected_rows()." reusable discount code(s) deactivated.\n";

	$sql_upd = "UPDATE newjs.DISCOUNT_CODE SET ACTIVE='Y' WHERE DISCOUNT_START_DATE BETWEEN '$four_days_before' AND '$today' AND ACTIVE='N'";
	mysql_query($sql_upd) or mail_me($sql_upd);

	$msg .= @mysql_affected_rows()." one time use discount code(s) activated.\n";

	$sql_upd = "UPDATE newjs.DISCOUNT_CODE SET ACTIVE='N' WHERE DISCOUNT_END_DATE BETWEEN '$four_days_before' AND '$today' AND ACTIVE='Y'";
	mysql_query($sql_upd) or mail_me($sql_upd);

	$msg .= @mysql_affected_rows()." one time use discount code(s) deactivated.\n";

	mail("sriram.viswanathan@jeevansathi.com","Discount code(s)",$msg);

	function mail_me($sql)
	{
		mail("sriram.viswanathan@jeevansathi.com","Error : Voucher discount code",nl2br("Error in marking voucher discount code unused\n".$sql_upd.mysql_error()));
		exit;
	}
?>
