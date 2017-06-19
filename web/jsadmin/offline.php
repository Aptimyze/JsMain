<?php
include("connect.inc");
$db=connect_db();
$sql="UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE='Y',ACC_ALLOWED='10000' WHERE PROFILEID='3163247' ORDER BY BILLID DESC LIMIT 1";
$res=mysql_query($sql);
?>
