<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once("$_SERVER[DOCUMENT_ROOT]/crm/connect.inc");

	$ts = time();
	$ts -= 24*60*60;

	$date = date("Y-m-d",$ts);

	$start_dt = $date." 00:00:00";
	$end_dt = $date." 23:59:59";

	$sql = "SELECT COUNT(*) AS COUNT FROM incentive.INVALID_PHONE WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt'";
	$res = mysql_query($sql) or mail_me($sql);
	$row = mysql_fetch_array($res);

	$sql_ins = "INSERT INTO incentive.INVALID_PHONE_COUNT(ENTRY_DT,COUNT) VALUES('$date','$row[COUNT]')";
	mysql_query($sql_ins) or mail_me($sql);

	mail("sriram.viswanathan@jeevansathi.com","Invalid Phone Count","$row[COUNT] invalid phone numbers for $date.");

	function mail_me($sql)
	{
		mail("sriram.viswanathan@jeevansathi.com","Error:Invalid phone count",$sql.mysql_error());
	}
?>
