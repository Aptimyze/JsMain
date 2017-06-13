<?php
require_once("connect.inc");
require_once("newsprom_comfunc.inc");

$db2      = dbsql2_connect();
$sql = "select d.PROFILEID from AFFILIATE_DATA d LEFT JOIN AFFILIATE_MAIN m ON d.EMAIL=m.EMAIL WHERE DAYOFMONTH(d.ENTRY_DT)='18' AND YEAR(d.ENTRY_DT)='2006' AND MONTH(d.ENTRY_DT)='03' AND m.EMAIL='';";
$res = mysql_query($sql) or die("$sql".mysql_error());
while($row=mysql_fetch_array($res))
{
	$pid = $row['PROFILEID'];
	$rand_email = email_gen(6);
	$email = $rand_email."@jsxyz.com";
	$sql_update = "UPDATE AFFILIATE_DATA SET EMAIL='$email' WHERE PROFILEID='$pid'";
	mysql_query($sql_update) or die("$sql_update".mysql_error());

	$mailsql = "INSERT IGNORE INTO jsadmin.AFFILIATE_MAIN (SOURCE , EMAIL , MODE , ENTRYBY , ENTRYTIME) values ('afl_rishta', '$email','N','anita',NOW())";
	$mailres = mysql_query($mailsql,$db2) or die("$mailsql".mysql_error());
}
?>
