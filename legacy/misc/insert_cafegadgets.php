<?php
include("../profile/connect.inc");
$db=connect_db();
for($i=1;$i<=18000;$i++)
{
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('CAFE01','JS001ASD234','E','2007-11-10')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());	
}
?>
